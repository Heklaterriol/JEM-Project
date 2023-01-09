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

HTMLHelper::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$document    = JFactory::getDocument();
$wa = $document->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('form.validate')
// HTMLHelper::_('behavior.tooltip');
// HTMLHelper::_('behavior.formvalidation');
// HTMLHelper::_('behavior.keepalive');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'source.cancel' || document.formvalidator.isValid(document.getElementById('source-form'))) {
			<?php //echo $this->form->getField('source')->save(); ?>
			Joomla.submitform(task, document.getElementById('source-form'));
		} else {
			alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jem&layout=edit'); ?>" method="post" name="adminForm" id="source-form" class="form-validate">
	<?php if ($this->ftp) : ?>
		<?php echo $this->loadTemplate('ftp'); ?>
	<?php endif; ?>
	<fieldset class="adminform">
		<legend><?php
		if ($this->source->custom) {
			echo Text::sprintf('COM_JEM_CSSMANAGER_FILENAME_CUSTOM', $this->source->filename);
		} else {
			echo Text::sprintf('COM_JEM_CSSMANAGER_FILENAME', $this->source->filename);
		}
		?></legend>

		<?php echo $this->form->getLabel('source'); ?>
		<div class="clr"></div>
		<div class="editor-border">
		<?php echo $this->form->getInput('source'); ?>
		</div>
		<input type="hidden" name="task" value="" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</fieldset>

	<?php echo $this->form->getInput('filename'); ?>
</form>
