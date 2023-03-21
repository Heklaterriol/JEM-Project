<?php

/**
 * @version 2.3.12
 * @package JEM
 * @copyright (C) 2013-2021 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die;

//$max_custom_fields = $this->settings->get('global_editvenue_maxnumcustomfields', -1); // default to All
?>

<fieldset>
	<legend><?php echo JText::_('COM_JEM_EDITVENUE_PUBLISHING_LEGEND'); ?></legend>
	<dl class="adminformlist jem-dl">
		<dt><?php echo $this->form->getLabel('published'); ?></dt>
		<dd><?php echo $this->form->getInput('published'); ?></dd>
	</dl>
</fieldset>

<!-- META -->
<fieldset class="">
	<legend><?php echo JText::_('COM_JEM_METADATA'); ?></legend>
	<input type="button" class="button btn" value="<?php echo JText::_('COM_JEM_ADD_VENUE_CITY'); ?>" onclick="meta()" />
	<p>&nbsp;</p>
	<?php foreach ($this->form->getFieldset('meta') as $field) : ?>
	<dl class="jem-dl">
		<dt class="control-label"><?php echo $field->label; ?></dt>
		<dd class="controls"><?php echo $field->input; ?></dd>
	</dl>
	<?php endforeach; ?>
</fieldset>
