<?php
/**
 * @package    JEM
 * @subpackage JEM Banner Module
 * @copyright  (C) 2013-2026 joomlaeventmanager.net
 * @copyright  (C) 2005-2009 Christoph Lukes
 * @license    https://www.gnu.org/licenses/gpl-3.0 GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

JemHelper::loadModuleStyleSheet('mod_jem_timeline', 'elements');

$app = Factory::getApplication();
$wa  = $app->getDocument()->getWebAssetManager();

$datemethod      = (int)$params->get('datemethod', 1);
$showcalendar    = (int)$params->get('showcalendar', 1);
$showflyer       = (int)$params->get('showflyer', 1);
$flyer_link_type = (int)$params->get('flyer_link_type', 0);
$imagewidthmax   = (int)$params->get('imagewidthmax', 0);

if ($flyer_link_type == 1) {
    echo JemOutput::lightbox();
    $modal = 'lightbox';
} elseif ($flyer_link_type == 0) {
    $modal = 'notmodal';
} else {
    $modal = '';
}

$document = Factory::getDocument();
$widthStyle = $imagewidthmax ? 'width:' . $imagewidthmax . 'px' : 'max-width: 100%';

$css = '
    .flyer-image img {
        ' . $widthStyle . ';
    }';
$wa->addInlineStyle($css);
?>

<div class="jemmodulebanner<?php echo $params->get('moduleclass_sfx')?>" id="jemmodulebanner">
    <div class="eventset">
        <?php $i = count($list); ?>
        <?php if ($i > 0) : ?>
        <?php foreach ($list as $item) : ?>
        <div class="event_id<?php echo $item->eventid; ?>" itemprop="event" itemscope itemtype="https://schema.org/Event">
            
<!-- ##### Title Start ##### -->
<h2 class="event-title jxtitle" itemprop="name">
	<?php if ($item->eventlink) : ?>
		<a href="<?php echo $item->eventlink; ?>" title="<?php echo $item->fulltitle; ?>" itemprop="url">
			<?php echo $item->title; ?>
		</a>
	<?php else : ?>
		<?php echo $item->title; ?>
	<?php endif; ?>
</h2>
<!-- ##### Title End ##### -->
       
<div class="jem-row-banner <?php echo $banneralignment; ?>">

<!-- ##### dateinfo title attribut Start ##### -->                         
<div class="calendar jxdateinfo" title="<?php echo strip_tags($item->dateinfo); ?>"><?php echo strip_tags($item->dateinfo); ?></div>
<!-- ##### dateinfo title attribut End ##### -->             


<!-- ##### Color Start ##### -->         
<div class="jxcolor" style="background-color:<?php echo !empty($item->color) ? $item->color : 'rgb(128,128,128)'; ?>"></div>
<!-- ##### Color End ##### -->             

<!-- ##### Font Color in Contrast to BG Color Start ##### -->         
<div class="monthbanner jxdark">
<?php echo !empty($item->color_is_dark) ? 'light' : 'dark'; ?>
</div>
<!-- ##### Font Color in Contrast to BG Color End ##### -->             

<!-- ##### Month Start ##### --> 
<div class="jxmonth">
Start: <?php echo $item->startdate['month']; ?><br>
End: <?php echo $item->enddate['month']; ?>
</div>
<!-- ##### Color End ##### --> 

<!-- ##### Week Day Start ##### --> 
<div class="jxwday">
Start: <?php echo $item->startdate['weekday']; ?><br>
End: <?php echo $item->enddate['weekday']; ?>
</div>
<!-- ##### Week Day End ##### --> 

<!-- ##### Day Start ##### -->
<div class="daynumbanner jxday">
Start: <?php echo $item->startdate['day']; ?><br>
End: <?php echo $item->enddate['day']; ?>
</div>
<!-- ##### Day End ##### --> 

<!-- ##### Microdata Date Schema Start ##### -->
<?php echo $item->dateschema; ?>
<!-- ##### Microdata Date Schema End ##### -->

<!-- ##### Date + Time Start ##### -->
<div class="jxdate" title="<?php echo strip_tags($item->dateinfo); ?>">
    <?php echo $item->date; ?>
</div>
<div class="jxtime" title="<?php echo strip_tags($item->dateinfo); ?>">
    <?php echo $item->time; ?>
</div>
<!-- ##### Date + Time End ##### -->


<!-- ##### Image Start ##### -->

<?php if (($showflyer == 1) && !empty($item->eventimage)) : ?>
	<div>
		<div class="flyer-image jximage">
			<?php $class = ($showcalendar == 1) ? 'image-preview' : 'image-preview2'; ?>
			<?php if ($flyer_link_type != 3) : ?>
				<a href="<?php echo ($flyer_link_type == 2) ? $item->eventlink : $item->eventimageorig; ?>" rel="<?php echo $modal;?>" class="banner-flyerimage" <?php if ($flyer_link_type == 0) echo 'target="_blank" '; ?> title="<?php echo ($flyer_link_type == 2) ? $item->fulltitle : Text::_('COM_JEM_CLICK_TO_ENLARGE'); ?>" data-title="<?php echo $item->title; ?>">
			<?php endif; ?>
			<img class="float_right <?php echo 'image-preview2'; ?>" src="<?php echo $item->eventimageorig; ?>" alt="<?php echo $item->title; ?>" itemprop="image" />
			<?php if ($flyer_link_type != 3) { echo '</a>'; } ?>
			<?php echo $widthStyle; ?>
    	</div>
	</div>
<div class="clr"></div>
<?php else : ?>
	<div>
    	<div class="flyer-image jximage">
    		no image / or show image = 0
    	</div>
	</div>
<?php endif; ?>

<!-- ##### Image End ##### -->

<!-- ##### Description Text Start ##### -->
<?php if ($params->get('showdesc', 1) == 1) :?>
    <div class="desc jxtext">
        <?php echo $item->eventdescription; ?>
        <?php if (isset($item->link) && $item->readmore != 0 && $params->get('readmore')) :
            echo '</br><a class="readmore" href="'.$item->link.'">'.$item->linkText.'</a>';
        endif;?>
    </div>
<?php endif; ?>
<!-- ##### Description Text End ##### -->

<!-- ##### Venue Start ##### -->
<?php if (($params->get('showvenue', 1) == 1) && (!empty($item->venue))) :?>
<div class="jxvenue">
    <div class="venue-title">
        <?php if ($item->venuelink) : ?>
            <a href="<?php echo $item->venuelink; ?>" title="<?php echo $item->venue; ?>"><?php echo $item->venue; ?></a>
        <?php else : ?>
            <?php echo $item->venue; ?>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<!-- ##### Venue END ##### -->


<!-- ##### Category Start ##### -->

<?php if (($params->get('showcategory', 1) == 1) && !empty($item->catname)) :?>
    <div class="jxcategory">
        <?php echo $item->catname; ?>
    </div>
<?php endif; ?>
<!-- ##### Category End ##### -->



<div itemprop="location" itemscope itemtype="https://schema.org/Place" style="display:none;">
    <meta itemprop="name" content="<?php echo $item->venue; ?>" />
    <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress" style="display:none;">
        <meta itemprop="streetAddress" content="<?php echo $item->street; ?>" />
        <meta itemprop="addressLocality" content="<?php echo $item->city; ?>" />
        <meta itemprop="addressRegion" content="<?php echo $item->state; ?>" />
        <meta itemprop="postalCode" content="<?php echo $item->postalCode; ?>" />
    </div>
</div>


<div class="clr"></div>



<!-- ##### hr line at the end START ##### -->

<?php if (--$i > 0) : /* no hr after last entry */ ?>
    <div class="hr">yy<hr />zz</div>
<?php endif; ?>
<!-- ##### hr line at the end END ##### -->

</div>
<?php endforeach; ?>


<?php else : ?>
	<?php echo Text::_('MOD_JEM_BANNER_NO_EVENTS'); ?>
<?php endif; ?>

</div>
</div>
</div>
