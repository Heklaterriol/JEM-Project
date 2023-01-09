<?php
/**
 * @version 2.3.1
 * @package JEM
 * @copyright (C) 2013-2021 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

// HTMLHelper::_('behavior.tooltip');
// HTMLHelper::_('behavior.modal');

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive');


$canDo = JEMHelperBackend::getActions();
?>

<form action="<?php echo Route::_('index.php?option=com_jem&view=cssmanager'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (isset($this->sidebar)) : ?>
	<!-- <div id="j-sidebar-container" class="span2">
		<?php //echo $this->sidebar; ?>
	</div> -->
	<?php endif; ?>
	<div id="j-main-container" class="j-main-container">
		<div class="row">			
			<div class="col-md-6">
				<fieldset class="adminform">
					<legend><?php echo Text::_('COM_JEM_CSSMANAGER_DESCRIPTION_LEGEND');?></legend>
					<p><?php echo Text::_('COM_JEM_CSSMANAGER_DESCRIPTION');?></p>
				</fieldset>

				<fieldset class="adminform">
					<legend><?php echo Text::_('COM_JEM_CSSMANAGER_LINENUMBER_LEGEND');?></legend>
					<p><?php echo Text::_('COM_JEM_CSSMANAGER_LINENUMBER_DESCRIPTION'); ?></p>
					<h3><?php echo Text::_('COM_JEM_CSSMANAGER_LINENUMBER_STATUS'); ?></h3>
					<p>
					<?php if ($this->statusLinenumber) : ?>
						<?php echo Text::_('COM_JEM_CSSMANAGER_LINENUMBER_ENABLED'); ?>
						<br />
						<a href="<?php echo Route::_('index.php?option=com_jem&amp;task=cssmanager.disablelinenumber');?>">
							<?php echo Text::_('COM_JEM_CSSMANAGER_LINENUMBER_DISABLE'); ?>
						</a>
					<?php else: ?>
						<?php echo Text::_('COM_JEM_CSSMANAGER_LINENUMBER_DISABLED'); ?>
						<br />
						<a href="<?php echo Route::_('index.php?option=com_jem&amp;task=cssmanager.setlinenumber');?>">
							<?php echo Text::_('COM_JEM_CSSMANAGER_LINENUMBER_ENABLE'); ?>
						</a>
					<?php endif; ?>
					</p>
				</fieldset>

				<div class="clr"></div>
			</div>

			<div class="col-md-6">
				<fieldset class="adminform">
					<legend><?php echo Text::_('COM_JEM_CSSMANAGER_FILENAMES');?></legend>
					<?php if (!empty($this->files['css'])) : ?>
						<ul>
						<?php foreach ($this->files['css'] as $file) : ?>
							<li>
							<?php if ($canDo->get('core.edit')) : ?>
								<a href="<?php echo Route::_('index.php?option=com_jem&task=source.edit&id='.$file->id);?>">
							<?php endif; ?>
							<?php echo Text::sprintf('COM_JEM_CSSMANAGER_EDIT_CSS', $file->name);?>
							<?php if ($canDo->get('core.edit')) : ?>
								</a>
							<?php endif; ?>
							</li>
						<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<br>
					<legend><?php echo Text::_('COM_JEM_CSSMANAGER_FILENAMES_CUSTOM');?></legend>
					<?php if (!empty($this->files['custom'])) : ?>
						<ul>
						<?php foreach ($this->files['custom'] as $file) : ?>
							<li>
							<?php if ($canDo->get('core.edit')) : ?>
								<a href="<?php echo Route::_('index.php?option=com_jem&task=source.edit&id='.$file->id);?>">
							<?php endif; ?>
							<?php echo Text::sprintf('COM_JEM_CSSMANAGER_EDIT_CSS', $file->name);?>
							<?php if ($canDo->get('core.edit')) : ?>
								</a>
							<?php endif; ?>
							</li>
						<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</fieldset>
				<div class="clr"></div>
				<input type="hidden" name="task" value="" />
			</div>			
		</div>
	</div>
	<?php //if (isset($this->sidebar)) : ?>
	<?php //endif; ?>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php
//keep session alive while editing
// HTMLHelper::_('behavior.keepalive');
?>
