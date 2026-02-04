<?php
/**
 * @package    JEM
 * @subpackage JEM Timeline Module
 * @copyright  (C) 2013-2026 joomlaeventmanager.net
 * @license    https://www.gnu.org/licenses/gpl-3.0 GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

JemHelper::loadModuleStyleSheet('mod_jem_timeline', 'mod_jem_timeline-slide-in');

$app = Factory::getApplication();
$wa  = $app->getDocument()->getWebAssetManager();

$showflyer       = (int)$params->get('showflyer', 1);
$flyer_link_type = (int)$params->get('flyer_link_type', 0);

// Vanilla JavaScript Version (kein jQuery mehr nötig)
$wa->registerAndUseScript('mod_jem_timeline.script',
    Uri::root(true) . 'modules/mod_jem_timeline/media/js/slidein.js',
    [],
    ['defer' => true]
);

if ($flyer_link_type == 1) {
    echo JemOutput::lightbox();
    $modal = 'lightbox';
} elseif ($flyer_link_type == 0) {
    $modal = 'notmodal';
} else {
    $modal = '';
}

$document = Factory::getDocument();
$timeline_color = $params->get('color', 'rgb(128,128,128)');
$css = '
.jem-timeline_line-progress,
.js-jem-active .jem-timeline-card_date,
.jem-timeline-card_arrow {
  background-color: ' . $timeline_color . ';
  }
.jem-timeline-card_date {
  border: 3px solid ' . $timeline_color . ';
  }
.jem-timeline-card_title-box-title,
.jem-timeline-card_title,
.jem-timeline-card_date-text,
.jem-timeline-card_categories,
.jem-timeline-card_title a {
  color: ' . $timeline_color . ';
  }
  .jem-timeline-card_item {
  box-shadow: 0 0 1px 1px ' . $timeline_color . ';
  }';    
$wa->addInlineStyle($css);
?>

<div class="jem-timeline-block">
  <section class="jem-section">
    <div class="jem-format-container">
      <div class="js-timeline jem-timeline">
        <div class="js-timeline_line jem-timeline_line">
          <div class="js-timeline_line-progress jem-timeline_line-progress"></div>
        </div>
        <div class="jem-timeline_list">

        <?php $itemCount = count($list); ?>
        <?php if ($itemCount > 0) : ?>
        <?php $i = 0; // Zähler für alternierende Layouts ?>
        <?php foreach ($list as $item) : ?>
        <?php $i++; ?>
        <div class="js-timeline_item jem-timeline_item event_id<?php echo $item->eventid; ?>" itemprop="event" itemscope itemtype="https://schema.org/Event">
        
            <div class="jem-timeline-card_box">
                <?php if ($i % 2 == 1) : // Ungerade Items: date-box zuerst ?>
                <div class="js-timeline-card_point-box jem-timeline-card_date-box">
                    <div class="jem-timeline-card_date"><?php echo $item->startdate['day']; ?> <?php echo $item->startdate['month']; ?></div>
                </div>
                <div class="jem-timeline-card_title-box">
                    <div class="jem-timeline-card_title-box-title">
						<?php echo $item->title; ?>
					</div>
            	</div>
                <?php else : // Gerade Items: title-box zuerst ?>
                <div class="jem-timeline-card_title-box">
                    <div class="jem-timeline-card_title-box-title">
						<?php echo $item->title; ?>
					</div>
            	</div>
                <div class="js-timeline-card_point-box jem-timeline-card_date-box">
                    <div class="jem-timeline-card_date"><?php echo $item->startdate['day']; ?> <?php echo $item->startdate['month']; ?></div>
                </div>
                <?php endif; ?>
            </div>
            <div class="jem-timeline-card_item">
            	<div class="jem-timeline-card_inner">
            	  <?php if (($showflyer == 1) && !empty($item->eventimage)) : ?>
                	<div class="jem-timeline-card_img-box">
							<?php if ($flyer_link_type != 3) : ?>
								<a href="<?php echo ($flyer_link_type == 2) ? $item->eventlink : $item->eventimageorig; ?>" rel="<?php echo $modal;?>" class="banner-flyerimage" <?php if ($flyer_link_type == 0) echo 'target="_blank" '; ?> title="<?php echo ($flyer_link_type == 2) ? $item->fulltitle : Text::_('COM_JEM_CLICK_TO_ENLARGE'); ?>" data-title="<?php echo $item->title; ?>">
							<?php endif; ?>
							<img class="timeline-image <?php echo 'image-preview2'; ?>" src="<?php echo $item->eventimageorig; ?>" alt="<?php echo $item->title; ?>" itemprop="image" />
							<?php if ($flyer_link_type != 3) { echo '</a>'; } ?>
                		</div>
                		<?php endif; ?>
                		<div class="jem-timeline-card_info">
                  <div class="jem-timeline-card_header">
                    <div class="jem-timeline-card_title">
                    <?php if ($item->eventlink) : ?>
							<a href="<?php echo $item->eventlink; ?>" title="<?php echo $item->fulltitle; ?>" itemprop="url"><?php echo $item->title; ?></a>
						<?php else : ?>
							<?php echo $item->title; ?>
						<?php endif; ?>
					</div>
                    <div class="jem-timeline-card_date-text" title="<?php echo strip_tags($item->dateinfo); ?>">
                        <span class="timeline-date">
                            <?php echo $item->startdatetime; ?>
                        </span>
                        <?php if (!empty($item->enddatetime)) : ?>
                            <span class="timeline-date timeline-enddate">
                                <?php echo $item->enddatetime; ?>
                            </span>
                        <?php endif; ?>
					</div>
					</div>
                  <div class="jem-timeline-card_desc">
                    <?php if ($params->get('showdesc', 1) == 1) :
						echo $item->eventdescription;
						if (isset($item->link) && $item->readmore != 0 && $params->get('readmore')) :
            			echo '</br><a class="readmore" href="'.$item->link.'">'.$item->linkText.'</a>';
            		endif; ?>
    			</div>
				<?php endif; ?>
                <?php if (($params->get('showcategory', 1) == 1) && !empty($item->catname)) :?>
    <div class="jem-timeline-card_categories">
        <?php echo $item->catname; ?>
    </div>
<?php endif; ?>                
                </div>
              </div>
              <div class="jem-timeline-card_arrow"></div>
            </div>
          </div>

<?php endforeach; ?>

<?php else : ?>
	<?php echo Text::_('MOD_JEM_BANNER_NO_EVENTS'); ?>
<?php endif; ?>
       </div>
      </div>
    </div>
  </section>
</div>