<?php
/**
 * @package    JEM
 * @copyright  (C) 2013-2026 joomlaeventmanager.net
 * @copyright  (C) 2005-2009 Christoph Lukes
 * @license    https://www.gnu.org/licenses/gpl-3.0 GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\Controller\BaseController;

// include files
require_once (JPATH_COMPONENT_SITE.'/factory.php');
require_once (JPATH_COMPONENT_SITE.'/helpers/helper.php');
require_once (JPATH_COMPONENT_SITE.'/helpers/mailtohelper.php');
require_once (JPATH_COMPONENT_SITE.'/helpers/route.php');
require_once (JPATH_COMPONENT_SITE.'/helpers/countries.php');
require_once (JPATH_COMPONENT_SITE.'/classes/config.class.php');
require_once (JPATH_COMPONENT_SITE.'/classes/user.class.php');
require_once (JPATH_COMPONENT_SITE.'/classes/image.class.php');
require_once (JPATH_COMPONENT_SITE.'/classes/output.class.php');
require_once (JPATH_COMPONENT_SITE.'/classes/view.class.php');
require_once (JPATH_COMPONENT_SITE.'/classes/attachment.class.php');
require_once (JPATH_COMPONENT_SITE.'/classes/categories.class.php');
require_once (JPATH_COMPONENT_SITE.'/classes/calendar.class.php');
require_once (JPATH_COMPONENT_SITE.'/classes/activecalendarweek.php');
require_once (JPATH_COMPONENT_SITE.'/helpers/category.php');

// Register JEM table autoloader (replaces deprecated Table::addIncludePath)
// Register JEM table class autoloader (replaces deprecated Table::addIncludePath)
spl_autoload_register(static function (string $class): void {
    static $map = null;
    if ($map === null) {
        $t = JPATH_ADMINISTRATOR . '/components/com_jem/tables/';
        $map = [
            'jem_register'              => $t . 'jem_register.php',
            'jem_attachments'           => $t . 'jem_attachments.php',
            'jem_cats_event_relations'  => $t . 'jem_cats_event_relations.php',
            'jem_events'                => $t . 'jem_events.php',
            'jem_venues'                => $t . 'jem_venues.php',
            'jem_groups'                => $t . 'jem_groups.php',
            'jem_groupmembers'          => $t . 'jem_groupmembers.php',
            'jem_settings'              => $t . 'jem_settings.php',
            'jem_categories'            => $t . 'jem_categories.php',
            'jemtableevent'             => $t . 'event.php',
            'jemtablevenue'             => $t . 'venue.php',
            'jemtablecategory'          => $t . 'category.php',
            'jemtablesettings'          => $t . 'settings.php',
            'jemtablegroup'             => $t . 'group.php',
        ];
    }
    $key = strtolower($class);
    if (isset($map[$key])) {
        require_once $map[$key];
    }
});
$document = Factory::getApplication()->getDocument();
$wa = $document->getWebAssetManager();
$wa->useScript('jquery');
// create JEM's file logger
JemHelper::addFileLogger();

//perform cleanup if it wasn't done today (archive, delete, recurrence)
JemHelper::cleanup();

// Get an instance of the controller
$controller = BaseController::getInstance('Jem');

// Perform the Request task
$input = Factory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
HTMLHelper::_('bootstrap.framework');
HTMLHelper::_('bootstrap.tooltip','.hasTooltip');
