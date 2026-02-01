<?php
/**
 * @package JEM
 * @subpackage JEM Banner Module
 * @copyright (C) 2013-2026 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license https://www.gnu.org/licenses/gpl-3.0 GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

JemHelper::loadModuleStyleSheet('mod_jem_banner', 'mod_jem_banner_timeline');

$app = Factory::getApplication();
$wa = $app->getDocument()->getWebAssetManager();

$datemethod = (int)$params->get('datemethod', 1);
$showcalendar = (int)$params->get('showcalendar', 1);
$showflyer = (int)$params->get('showflyer', 1);
$flyer_link_type = (int)$params->get('flyer_link_type', 0);

if ($flyer_link_type == 1) {
    echo JemOutput::lightbox();
    $modal = 'lightbox';
} elseif ($flyer_link_type == 0) {
    $modal = 'notmodal';
} else {
    $modal = '';
}

$uri = Uri::getInstance();
$document = Factory::getDocument();

// Add Font Awesome if not already loaded
$wa->registerAndUseStyle('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

?>

<div class="jemmodulebanner-timeline<?php echo $params->get('moduleclass_sfx'); ?>">
    <div class="timeline-wrapper">
        <div class="center-line">
            <a href="#" class="scroll-icon"><i class="fas fa-caret-up"></i></a>
        </div>
        
        <?php if (count($list) > 0) : ?>
            <?php $rowCount = 0; ?>
            <?php foreach ($list as $item) : ?>
                <?php 
                $rowCount++;
                $rowClass = ($rowCount % 2 == 1) ? 'row-1' : 'row-2';
                ?>
                
                <div class="timeline-row <?php echo $rowClass; ?>">
                    <section class="timeline-section event_id<?php echo $item->eventid; ?>" itemprop="event" itemscope itemtype="https://schema.org/Event">
                        
                        <!-- Event Icon/Image -->
                        <?php if (($showflyer == 1) && !empty($item->eventimage)) : ?>
                            <div class="timeline-icon" style="background-image: url('<?php echo $item->eventimageorig; ?>');">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        <?php else : ?>
                            <i class="timeline-icon fas fa-calendar-alt"></i>
                        <?php endif; ?>
                        
                        <!-- Event Details -->
                        <div class="timeline-details">
                            <span class="timeline-title">
                                <?php echo $item->eventlink ? '<a href="'.$item->eventlink.'">'.$item->title.'</a>' : $item->title; ?>
                            </span>
                            <span class="timeline-date">
                                <?php echo $item->startdate['day']; ?> 
                                <?php echo $item->startdate['month']; ?> 
                                <?php echo $item->startdate['year']; ?>
                                <?php if (!empty($item->time)) : ?>
                                    - <?php echo $item->time; ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <!-- Event Category Badge -->
                        <?php if (($params->get('showcategory', 1) == 1) && !empty($item->catname)) : ?>
                            <div class="timeline-badge">
                                <?php echo $item->catname; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Event Description -->
                        <?php if ($params->get('showdesc', 1) == 1) : ?>
                            <p class="timeline-description">
                                <?php echo strip_tags(substr($item->eventdescription, 0, 150)); ?>...
                            </p>
                        <?php endif; ?>
                        
                        <!-- Event Meta & Actions -->
                        <div class="timeline-bottom">
                            <?php if (isset($item->link) && ($item->readmore != 0 || $params->get('readmore'))) : ?>
                                <a href="<?php echo $item->link; ?>" class="timeline-link">
                                    <?php echo Text::_('MOD_JEM_BANNER_READMORE'); ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (($params->get('showvenue', 1) == 1) && (!empty($item->venue))) : ?>
                                <i class="timeline-venue">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo $item->venue; ?>
                                </i>
                            <?php endif; ?>
                        </div>
                        
                    </section>
                </div>
                
            <?php endforeach; ?>
        <?php else : ?>
            <div class="jem-no-events">
                <?php echo Text::_('MOD_JEM_BANNER_NO_EVENTS'); ?>
            </div>
        <?php endif; ?>
        
    </div>
</div>
