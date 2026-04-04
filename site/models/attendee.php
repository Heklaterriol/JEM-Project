<?php
/**
 * @package    JEM
 * @copyright  (C) 2013-2026 joomlaeventmanager.net
 * @copyright  (C) 2005-2009 Christoph Lukes
 * @license    https://www.gnu.org/licenses/gpl-3.0 GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Language\Text;

/**
 * JEM Component attendee Model
 *
 * @package JEM
 *
 */
class JemModelAttendee extends BaseDatabaseModel
{
    /**
     * Attendee id
     *
     * @var int
     */
    protected int $_id = 0;

    /**
     * Attendee data
     *
     * @var object|null
     */
    protected ?object $_data = null;

    /**
     * Use real name or username
     *
     * @var int
     */
    protected int $regname = 1;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();

        $settings = JemHelper::globalattribs();
        $this->regname = (int) $settings->get('global_regname', 1);

        $app   = Factory::getApplication();
        $input = $app->input;

        $cid = $input->get('cid', [0], 'array');
        $this->setId((int) ($cid[0] ?? 0));
    }

    /**
     * Method to set the identifier
     *
     * @access public
     * @param  int attendee/registration identifier
     */
    public function setId(int $id): void
    {
        // Set category id and wipe data
        $this->_id   = $id;
        $this->_data = null;
    }

    /**
     * Method to get attendee data
     *
     * @access public
     * @return array
     */
    public function getData(): object
    {
        if (!$this->_loadData()) {
            $this->_initData();
        }

        return $this->_data;
    }

    /**
     * Method to load attendee data
     *
     * @access protected
     * @return boolean  True on success
     */
    protected function _loadData(): bool
    {
        // Lets load the content if it doesn't already exist
        if ($this->_data === null) {
            $db = $this->getDatabase();

            $query = $db->getQuery(true)
                ->select([
                    'r.*',
                    ($this->regname ? 'u.name' : 'u.username') . ' AS username'
                ])
                ->from('#__jem_register AS r')
                ->leftJoin('#__users AS u ON u.id = r.uid')
                ->where('r.id = :id')
                ->bind(':id', $this->_id, \PDO::PARAM_INT);

            $db->setQuery($query);

            try {
                $this->_data = $db->loadObject();
            } catch (\RuntimeException $e) {
                $this->setError($e->getMessage());
                return false;
            }

            return (bool) $this->_data;
        }

        return true;
    }

    /**
     * Method to initialise attendee data
     *
     * @access protected
     * @return boolean  True on success
     */
    protected function _initData(): bool
    {
        if ($this->_data === null) {
            $table = $this->getTable('jem_register', '');
            $table->username = null;
            $this->_data = $table;
        }

        return true;
    }

    /**
     * Toggle waiting status
     */
    public function toggle(): bool
    {
        $attendee = $this->getData();

        if (empty($attendee->id)) {
            $this->setError(Text::_('COM_JEM_MISSING_ATTENDEE_ID'));
            return false;
        }

        $row = $this->getTable('jem_register', '');

        // bind() returns false, does not throw an exception
        if (!$row->bind((array) $attendee)) {
            $this->setError(Text::_('COM_JEM_ERROR_BIND_FAILED'));
            return false;
        }

        $row->waiting = ((int) $attendee->waiting === 1) ? 0 : 1;

        // store() throws RuntimeException in J6
        try {
            $row->store();
        } catch (\RuntimeException $e) {
            $this->setError($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Method to store the attendee
     *
     * @access public
     * @return boolean  True on success
     */
    public function store(array $data)
    {
        $db      = $this->getDatabase();
        $eventid = (int) ($data['event'] ?? 0);

        $row = $this->getTable('jem_register', '');

        // bind() returns false, does not throw RuntimeException
        if (!$row->bind($data)) {
            $this->setError(Text::_('COM_JEM_ERROR_BIND_FAILED'));
            return false;
        }

        // sanitise id field
        $row->id = (int) $row->id;

        // New registration
        if (!$row->id) {
            $row->uregdate = gmdate('Y-m-d H:i:s');

            $query = $db->getQuery(true)
                ->select([
                    'e.maxplaces',
                    'e.waitinglist',
                    'COUNT(r.id) AS booked'
                ])
                ->from('#__jem_events AS e')
                ->innerJoin('#__jem_register AS r ON r.event = e.id')
                ->where('e.id = :eventid')
                ->where('r.status = 1')
                ->where('r.waiting = 0')
                ->group('e.id')
                ->bind(':eventid', $eventid, \PDO::PARAM_INT);

            $db->setQuery($query);

            try {
                $details = $db->loadObject();
            } catch (\RuntimeException $e) {
                $this->setError($e->getMessage());
                return false;
            }

            if ($details && (int) $details->maxplaces > 0) {
                if ((int) $details->booked >= (int) $details->maxplaces) {
                    if (!(int) $details->waitinglist) {
                        Factory::getApplication()->enqueueMessage(
                            Text::_('COM_JEM_ERROR_REGISTER_EVENT_IS_FULL'),
                            'warning'
                        );
                        return false;
                    }
                    $row->waiting = 1;
                }
            }
        }

        // Validate + store
        try {
            $row->check();
            $row->store();
        } catch (\RuntimeException $e) {
            $this->setError($e->getMessage());
            return false;
        }

        return $row;
    }
}