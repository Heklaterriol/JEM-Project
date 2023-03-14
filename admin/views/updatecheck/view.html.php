<?php
/**
 * @version 2.3.10
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
 * View class for the JEM Updatecheck screen
 *
 * @package JEM
 *
 */
class JemViewUpdatecheck extends JemAdminView
{

	public function display($tpl = null)
	{
		//Get data from the model
		$updatedata      	= $this->get('Updatedata');

		// Load css
		// HTMLHelper::_('stylesheet', 'com_jem/backend.css', array(), true);
		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
	
		$wa->registerStyle('jem.backend', 'com_jem/backend.css')->useStyle('jem.backend');
		// Load script
		// HTMLHelper::_('behavior.framework');

		//assign data to template
		$this->updatedata	= $updatedata;

		// add toolbar
		$this->addToolbar();

		parent::display($tpl);
	}


	/**
	 * Add Toolbar
	 */
	protected function addToolbar()
	{
		ToolbarHelper::title(Text::_('COM_JEM_UPDATECHECK_TITLE'), 'settings');

		ToolbarHelper::back();
		ToolbarHelper::divider();
		ToolbarHelper::help('update', true);
	}
}
