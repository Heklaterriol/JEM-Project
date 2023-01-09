<?php
/**
 * @version 2.3.1
 * @package JEM
 * @copyright (C) 2013-2021 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die;


use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Factory;
/**
 * Housekeeping-View
 */
class JemViewHousekeeping extends JemAdminView
{

	public function display($tpl = null) {

		$app = Factory::getApplication();

		$this->totalcats = $this->get('Countcats');

		//only admins have access to this view
		if (!JemFactory::getUser()->authorise('core.manage', 'com_jem')) {
			Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$app->redirect('index.php?option=com_jem&view=main');
		}

		// Load css
		// HTMLHelper::_('stylesheet', 'com_jem/backend.css', array(), true);
		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
	
		$wa->registerStyle('jem.backend', 'com_jem/backend.css')->useStyle('jem.backend');
		// Load Script
		// HTMLHelper::_('behavior.framework');

		// add toolbar
		$this->addToolbar();

		parent::display($tpl);
	}


	/**
	 * Add Toolbar
	 */
	protected function addToolbar()
	{
		ToolbarHelper::title(Text::_('COM_JEM_HOUSEKEEPING'), 'housekeeping');

		ToolbarHelper::back();
		ToolbarHelper::divider();
		ToolbarHelper::help('housekeeping', true);
	}
}
?>
