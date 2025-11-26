<?php
function metaImage()
{
	if(!empty(get_option('curatescapeseo_meta_image'))){
		// string/url (plugin option)
		return validateMetaImage(
			trim(option('curatescapeseo_meta_image')));
	}
	if(!empty(get_theme_option('curatescapeseo_meta_image'))){
		// theme upload (available for theme developers)
		return validateMetaImage(
			WEB_ROOT.'/files/theme_uploads/'.
			trim(get_theme_option('curatescapeseo_meta_image'))); 
	}
	if(!empty(get_theme_option('curatescape_meta_image'))){
		// theme upload (legacy support for curatescape themes)
		return validateMetaImage(
			WEB_ROOT.'/files/theme_uploads/'.
			trim(get_theme_option('curatescape_meta_image'))); 
	}
	if(!empty(get_theme_option('custom_meta_img'))){
		// theme upload (legacy support for curatescape themes)
		return validateMetaImage(
			WEB_ROOT.'/files/theme_uploads/'.
			trim(get_theme_option('custom_meta_img'))); 
	}
	return '';
}

function validateMetaImage($url = null)
{
	if(!$url) return '';
	$url = html_escape(filter_var($url, FILTER_SANITIZE_URL));
	if(substr($url,0,4) !== "http" || !allowedExtensionImg($url)){
		return '';
	}
	if(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) === FALSE) {
		return '';
	}
	return $url;
}

function metaTags($args)
{
	$metaTitle= get_option('site_title');
	$metaText= get_option('description');
	$metaImg= metaImage();
	$metaUrl = WEB_ROOT.current_url();
	$metaTitle = isset($args['view']->title) ? $args['view']->title : $metaTitle;
	if($item = $args['view']->getCurrentRecord('item', false)){
		$metaText = dc($item, 'Description') ? dc($item, 'Description') : $metaText;
		$metaImg = preferredItemImageUrl($item);
	}
	elseif($page = $args['view']->getCurrentRecord('simple_pages_page', false)){
		$metaText = $page->text ? $page->text : $metaText;
	}	
	elseif($tour = $args['view']->getCurrentRecord('tour', false)){
		if(plugin_is_active('Curatescape')){
			$metaText = $tour->description ? $tour->description : $metaText;
			if($firstTourItem = $tour->getTourItemByIndex(0)){
				$metaImg = preferredItemImageUrl($firstTourItem);
			}
		}
	}
	elseif($exhibit = $args['view']->getCurrentRecord('exhibit', false)){
		$metaText = $exhibit->description ? $exhibit->description : $metaText;
		$metaImg = record_image_url($exhibit, 'fullsize') ? record_image_url($exhibit, 'fullsize') : $metaImg;
	}
	elseif($collection = $args['view']->getCurrentRecord('collection', false)){
		$metaText = dc($collection, 'Description') ? dc($collection, 'Description') : $metaText;
		$metaImg = record_image_url($collection, 'fullsize') ? record_image_url($collection, 'fullsize') : $metaImg;
	}
	elseif(isset($args['view']->title) && str_starts_with($args['view']->title, 'Contribution')){
		$metaTitle = $metaTitle.(get_option('site_title') !== $metaTitle ? ' | '.get_option('site_title') : null);
	}
	elseif(isset($args['view']->title) && str_starts_with($args['view']->title, 'Browse Items on the Map')){
		$metaTitle = __('Map').(get_option('site_title') !== $metaTitle ? ' | '.get_option('site_title') : null);
	}
	if(empty($metaImg) || !empty($metaImg) && str_contains($metaImg, 'application/views/scripts/images/fallback')){
		$metaImg = metaImage();
	}
	$metaText = $metaText ? snippet(strip_tags(htmlspecialchars($metaText)),0, 250) : __('Preview text unavailable');
	$metaTitle = $metaTitle ? snippet(strip_tags(htmlspecialchars($metaTitle)),0, 250) : get_option('site_title');
	?>

	<!-- Meta Tags (Curatescape SEO plugin) -->
	<meta property="og:site_name" content="<?php echo option('site_title');?>">
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?php echo $metaUrl;?>" />
	<meta property="og:title" content="<?php echo $metaTitle;?>" />
	<meta property="og:description" content="<?php echo $metaText;?>" />
	<meta property="og:image" content="<?php echo $metaImg;?>" />
	<meta property="twitter:card" content="summary_large_image" />
	<meta property="twitter:url" content="<?php echo $metaUrl;?>" />
	<meta property="twitter:title" content="<?php echo $metaTitle;?>" />
	<meta property="twitter:description" content="<?php echo $metaText;?>" />
	<meta property="twitter:image" content="<?php echo $metaImg;?>" />
	<?php 
}
function configFormCheckBox($optionName, $labelName, $helperText)
{
	if(!$optionName || !$labelName || !$helperText) return null;
	?>
	<div class="field">
		<div class="two columns alpha">
			<label for="<?php echo $optionName;?>"><?php echo __($labelName); ?></label>
		</div>
		<div class="inputs five columns omega">
			<p class="explanation"><?php echo __($helperText); ?></p>
			<?php echo get_view()->formCheckbox($optionName, true,
			array('checked'=>(boolean)get_option($optionName))); ?>
		</div>
	</div>
	<?php
}

function configFormSelect($optionName, $labelName, $helperText, $options=array())
{
	if(!$optionName || !$labelName || !$helperText || !count($options)) return null;
	?>
	<div class="field">
		<div class="two columns alpha">
			<label for="<?php echo $optionName;?>"><?php echo __($labelName); ?></label>
		</div>
		<div class="inputs five columns omega">
			<p class="explanation"><?php echo __($helperText); ?></p>
			<?php echo get_view()->formSelect($optionName, get_option($optionName), null, $options); ?>
		</div>
	</div>
	<?php
}

function configFormText($optionName, $labelName, $helperText, $placeholder=null)
{
	if(!$optionName || !$labelName || !$helperText) return null;
	?>
	<div class="field">
		<div class="two columns alpha">
			<label for="<?php echo $optionName;?>"><?php echo __($labelName); ?></label>
		</div>
		<div class="inputs five columns omega">
			<p class="explanation"><?php echo __($helperText); ?></p>
			<?php echo get_view()->formText($optionName, get_option($optionName), array('placeholder' => __($placeholder))); ?>
		</div>
	</div>
	<?php
}