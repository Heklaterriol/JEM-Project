<?php
/**
 * @version 2.3.0
 * @package JEM
 * @copyright (C) 2013-2019 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;
$group = 'globalattribs';
?>

<div class="width-100">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_JEM_REGISTRATION'); ?></legend>
		<ul class="adminformlist">
			<li><?php echo $this->form->getLabel('event_show_attendeenames',$group); ?> <?php echo $this->form->getInput('event_show_attendeenames',$group); ?></li>
			<li><?php echo $this->form->getLabel('event_show_more_attendeedetails',$group); ?> <?php echo $this->form->getInput('event_show_more_attendeedetails',$group); ?></li>
			<li><?php echo $this->form->getLabel('event_comunsolution',$group); ?> <?php echo $this->form->getInput('event_comunsolution',$group); ?></li>
			<li id="comm1" style="display:none"><?php echo $this->form->getLabel('event_comunoption',$group); ?> <?php echo $this->form->getInput('event_comunoption',$group); ?></li>
		</ul>
	</fieldset>
</div>
