<?php
/**
 * @version 2.3.8
 * @package JEM
 * @copyright (C) 2013-2021 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/eventslist.php';

/**
 * Model Categorycal
 *
 * @package JEM
 */
class JemModelCategoryCal extends JemModelEventslist
{
	/**
	 * Category id
	 *
	 * @var int
	 */
	protected $_id = null;

	/**
	 * Date as timestamp useable for strftime()
	 *
	 * @var int
	 */
	protected $_date = null;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$app    = JFactory::getApplication();
		$params = $app->getParams();

		$id = $app->input->getInt('id', 0);
		if (empty($id)) {
			$id = $params->get('id', 0);
		}

		$this->setdate(time());
		$this->setId((int)$id);

		parent::__construct();
	}

	public function setdate($date)
	{
		$this->_date = $date;
	}

	/**
	 * Method to set the category id
	 *
	 * @access public
	 * @param  int  category ID
	 */
	public function setId($id)
	{
		// Set new category ID and wipe data
		$this->_id   = $id;
		//$this->_data = null;
	}

	/**
	 * Method to auto-populate the model state.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app          = JFactory::getApplication();
		$params       = $app->getParams();
		$itemid       = $app->input->getInt('Itemid', 0);
		$task         = $app->input->getCmd('task', '');
		$startdayonly = $params->get('show_only_start', false);

		# params
		$this->setState('params', $params);

		# publish state
		//$this->setState('filter.published', 1);
		$this->_populatePublishState($task);

		###########
		## DATES ##
		###########

		#only select events within specified dates. (chosen month)
		// $monthstart = mktime(0, 0,  1, strftime('%m', $this->_date),   1, strftime('%Y', $this->_date));
		// $monthend   = mktime(0, 0, -1, strftime('%m', $this->_date)+1, 1, strftime('%Y', $this->_date));

		// $filter_date_from = strftime('%Y-%m-%d', $monthstart);
		// $filter_date_to   = strftime('%Y-%m-%d', $monthend);

		$monthstart = mktime(0, 0,  1, date('m', $this->_date),   1, date('Y', $this->_date));
		$monthend   = mktime(0, 0, -1, date('m', $this->_date)+1, 1, date('Y', $this->_date));

		$filter_date_from = date('Y-m-d', $monthstart);
		$filter_date_to   = date('Y-m-d', $monthend);

		$where = ' DATEDIFF(IF (a.enddates IS NOT NULL, a.enddates, a.dates), '. $this->_db->Quote($filter_date_from) .') >= 0';
		$this->setState('filter.calendar_from', $where);

		$where = ' DATEDIFF(a.dates, '. $this->_db->Quote($filter_date_to) .') <= 0';
		$this->setState('filter.calendar_to', $where);

		# set filter
		$this->setState('filter.calendar_multiday', true);
		$this->setState('filter.calendar_startdayonly', (bool)$startdayonly);
		$this->setState('filter.filter_catid', $this->_id);

		$app->setUserState('com_jem.categorycal.catid'.$itemid, $this->_id);

		# groupby
		$this->setState('filter.groupby', array('a.id'));
	}

	/**
	 * Method to get the events
	 *
	 * @access public
	 * @return array
	 */
	public function getItems()
	{
		$items = parent::getItems();

		if ($items) {
			return $items;
		}

		return array();
	}

	/**
	 * @return JDatabaseQuery
	 */
	protected function getListQuery()
	{
		// Let parent create a new query object.
		$query = parent::getListQuery();

		// here we can extend the query of the Eventslist model
		$query->select('DATEDIFF(a.enddates, a.dates) AS datesdiff,DAYOFMONTH(a.dates) AS start_day, YEAR(a.dates) AS start_year, MONTH(a.dates) AS start_month');

		return $query;
	}
}
?>
