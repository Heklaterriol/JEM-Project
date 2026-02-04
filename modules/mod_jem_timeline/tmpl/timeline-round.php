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

JemHelper::loadModuleStyleSheet('mod_jem_timeline', 'mod_jem_timeline-round');

$app = Factory::getApplication();
$wa  = $app->getDocument()->getWebAssetManager();

$showflyer       = (int)$params->get('showflyer', 1);
$showdesc  = (int) $params->get('showdesc', 1);
$flyer_link_type = (int)$params->get('flyer_link_type', 0);

if ($flyer_link_type == 1) {
    echo JemOutput::lightbox();
    $modal = 'lightbox';
} elseif ($flyer_link_type == 0) {
    $modal = 'notmodal';
} else {
    $modal = '';
}

$document = Factory::getDocument();
$timeline_color = $params->get('color', 'rgba(201,197,195,1)');
$css = '
.main-timeline:before,
.main-timeline .timeline-content:before {
  background: ' . $timeline_color . ';
}
.main-timeline .timeline:first-child:before,
.main-timeline .timeline:last-child:before
.main-timeline .timeline:last-child:nth-child(even):before,
.main-timeline .icon,
.main-timeline .title a:hover,
.main-timeline .circle {
  border: 3px solid ' . $timeline_color . ';
}
.main-timeline .title a {
  background: oklch(from ' . $timeline_color . ' min(l, 0.75) c h);
  border: 3px oklch(from ' . $timeline_color . ' min(l, 0.75) c h) solid;
}
.main-timeline .title a:hover {
  border: 3px ' . $timeline_color . ' solid;
  color: oklch(from ' . $timeline_color . ' min(l, 0.75) c h);
}
.main-timeline .date,
.main-timeline .description.category a,
.main-timeline .description.venue a {
  color: oklch(from ' . $timeline_color . ' min(l, 0.75) c h);
  text-decoration: none;
}';

$wa->addInlineStyle($css);
?>

<section>
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="main-timeline">

                <?php $itemCount = count($list); ?>
                <?php if ($itemCount > 0) : ?>
                <?php foreach ($list as $item) : ?>
                
                <div class="timeline event_id<?php echo $item->eventid; ?>" itemprop="event" itemscope itemtype="https://schema.org/Event">
                    <div class="timeline-content">
                        <div class="circle">
                            <span class="homebox">
                                <?php if ($showflyer && $item->eventimageorig) : ?>
                                    <?php if ($flyer_link_type != 3) : ?>
                                        <a href="<?php echo ($flyer_link_type == 2) ? $item->eventlink : $item->eventimageorig; ?>" rel="<?php echo $modal;?>" <?php if ($flyer_link_type == 0) echo 'target="_blank" '; ?> title="<?php echo ($flyer_link_type == 2) ? $item->fulltitle : Text::_('COM_JEM_CLICK_TO_ENLARGE'); ?>" data-title="<?php echo $item->title; ?>">
                                    <?php endif; ?>
                                    <img src="<?php echo $item->eventimageorig; ?>" alt="<?php echo $item->title; ?>" class="img" itemprop="image" />
                                    <?php if ($flyer_link_type != 3) { echo '</a>'; } ?>
                                <?php else : ?>
                                    <img src="<?php echo Uri::base(true); ?>/media/com_jem/images/blank.png" alt="<?php echo $item->title; ?>" class="img" />
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="content">
                            <h2 class="title">
                                <?php if ($item->eventlink) : ?>
                                    <a href="<?php echo $item->eventlink; ?>" title="<?php echo $item->fulltitle; ?>" itemprop="url"><?php echo $item->title; ?></a>
                                <?php else : ?>
                                    <?php echo $item->title; ?>
                                <?php endif; ?>
                            </h2>
                            
                            <h3 class="date" title="<?php echo strip_tags($item->dateinfo); ?>">
                                <?php if (!empty($item->startdatetime)) : ?>
                                    <?php echo $item->startdatetime; ?>
                                    <?php if (!empty($item->enddatetime) && $item->enddatetime != $item->startdatetime) : ?>
                                        &nbsp;–&nbsp;<?php echo $item->enddatetime; ?>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <?php echo $item->date; ?>
                                    <?php if ($item->time) : ?>
                                        <span class="time">, <?php echo $item->time; ?></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </h3>
                            
                            <?php if ($params->get('showdesc', 1) == 1) : ?>
                            <p class="description">
                                <?php echo $item->eventdescription; ?>
                                <?php if (isset($item->link) && $item->readmore != 0 && $params->get('readmore')) : ?>
                                    <br><a class="readmore" href="<?php echo $item->link; ?>"><?php echo $item->linkText; ?></a>
                                <?php endif; ?>
                            </p>
                            <?php endif; ?>
                            
                            <?php if (($params->get('showcategory', 1) == 1) && !empty($item->catname)) : ?>
                            <p class="description category">
                                <strong><?php echo Text::_('COM_JEM_CATEGORY'); ?>:</strong> <?php echo $item->catname; ?>
                            </p>
                            <?php endif; ?>
                            
                            <?php if (($params->get('showvenue', 0) == 1) && !empty($item->venue)) : ?>
                            <p class="description venue">
                                <strong><?php echo Text::_('COM_JEM_VENUE'); ?>:</strong> 
                                <?php if ($item->venuelink) : ?>
                                    <a href="<?php echo $item->venuelink; ?>"><?php echo $item->venue; ?></a>
                                <?php else : ?>
                                    <?php echo $item->venue; ?>
                                <?php endif; ?>
                                <?php if (!empty($item->city)) : ?>
                                    , <?php echo $item->city; ?>
                                <?php endif; ?>
                            </p>
                            <?php endif; ?>
                            
                            <div class="icon"><span></span></div>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>
                <?php else : ?>
                <div class="timeline">
                    <div class="timeline-content">
                        <div class="content">
                            <p class="description"><?php echo Text::_('MOD_JEM_TIMELINE_NO_EVENTS'); ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
</section>
