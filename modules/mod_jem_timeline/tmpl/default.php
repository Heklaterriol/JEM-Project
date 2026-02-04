<?php
/**
 * @package    JEM
 * @subpackage JEM Timeline Module
 * @copyright  (C) 2013-2026 joomlaeventmanager.net
 * @license    https://www.gnu.org/licenses/gpl-3.0 GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

JemHelper::loadModuleStyleSheet('mod_jem_timeline', 'mod_jem_timeline');

$app = Factory::getApplication();
$wa  = $app->getDocument()->getWebAssetManager();

$datemethod      = (int)$params->get('datemethod', 1);
$showflyer = (int) $params->get('showflyer', 1);
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
$timeline_color = $params->get('color', 'rgb(128,128,128)');

$css = '
.jemmoduletimeline .timeline-badge a {
    background: ' . $timeline_color . ';  
}
.jemmoduletimeline .timeline-badge a:hover {
    background: ' . $timeline_color . 'aa;    
}
.timeline-center-line {
    background: ' . $timeline_color . '33;    
}';  
$wa->addInlineStyle($css);?>

<div class="jemmoduletimeline jem-timeline-centered<?php echo $params->get('moduleclass_sfx'); ?>">
    <div class="timeline-wrapper">

        <div class="timeline-center-line"></div>

        <?php if (!empty($list)) : ?>
            <?php foreach ($list as $i => $item) : ?>
                <?php
                    // left / right alternating
                    $rowClass = ($i % 2 === 0) ? 'row-left' : 'row-right';
                    $font_color = !empty($item->color_is_dark) ? 'light' : 'dark';
                ?>

                <div class="timeline-row <?php echo $rowClass; ?> event_id<?php echo (int) $item->eventid; ?>"
                     itemprop="event"
                     itemscope
                     itemtype="https://schema.org/Event">

                    <section class="timeline-card">

                        <span class="timeline-icon" style="--event-color: <?php echo $timeline_color; ?>;">
                            <i class="fas fa-calendar-alt"></i>
                        </span>

                        <div class="timeline-details">
                            <span class="timeline-title">
                                <?php if (!empty($item->eventlink)) : ?>
                                    <a href="<?php echo $item->eventlink; ?>">
                                        <?php echo $item->title; ?>
                                    </a>
                                <?php else : ?>
                                    <?php echo $item->title; ?>
                                <?php endif; ?>
                            </span>
                            <div class="timeline-dates">
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
                        <?php if (!empty($item->catname)) : ?>
                            <div class="timeline-badge <?php echo $font_color; ?>">
                                <?php echo $item->catname; ?>
                            </div>
                        <?php endif;
                        if ($showflyer && !empty($item->eventimageorig)) : ?>
                            <div class="timeline-image">
                                <img src="<?php echo $item->eventimageorig; ?>"
                                     alt="<?php echo htmlspecialchars($item->title, ENT_QUOTES); ?>">
                            </div>
                        <?php endif; ?>

                        <?php if ($showdesc && !empty($item->eventdescription)) : ?>
                            <p class="timeline-description">
                                <?php echo strip_tags(substr($item->eventdescription, 0, 160)); ?>…
                            </p>
                        <?php endif; ?>
                        <div class="timeline-bottom">
                            <?php if (!empty($item->link)) : ?>
                                <a class="timeline-readmore timeline-button-<?php echo $font_color; ?>" style="background-color:<?php echo $timeline_color; ?>" href="<?php echo $item->link; ?>">
                                <?php echo Text::_('MOD_JEM_TIMELINE_READMORE'); ?>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($item->venue)) : ?>
                                <span class="timeline-venue">
                                    <?php echo $item->venue; ?>
                                </span>
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
