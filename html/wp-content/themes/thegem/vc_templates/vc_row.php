<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $full_width
 * @var $full_height
 * @var $equal_height
 * @var $columns_placement
 * @var $content_placement
 * @var $parallax
 * @var $parallax_image
 * @var $css
 * @var $el_id
 * @var $video_bg
 * @var $video_bg_url
 * @var $video_bg_parallax
 * @var $parallax_speed_bg
 * @var $parallax_speed_video
 * @var $content - shortcode content
 * @var $css_animation
 * @var $vertical_slider
 * @var $vertical_slider_title
 * @var $vertical_slider_anchor
 * @var $ken_burns_enabled
 * @var $ken_burns_image
 * @var $ken_burns_direction
 * @var $ken_burns_transition_speed
 * @var $video_background_type
 * @var $video_background_src
 * @var $video_background_acpect_ratio
 * @var $video_background_play_on_mobile
 * @var $video_background_fallback
 * @var $video_background_overlay_color
 * @var $video_background_overlay_opacity
 * @var $video_background_poster
 *
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Row
 */

$el_class = $full_height = $thegem_fix_full_height = $parallax_speed_bg = $parallax_speed_video = '';
$full_width = $equal_height = $flex_row = $columns_placement = $content_placement = $parallax = '';
$parallax_image = $css = $el_id = $video_bg = $video_bg_url = $video_bg_parallax = $css_animation = '';
$ken_burns_enabled = $header_sticky_row = $header_shrink_row = $disable_element = $uniqid = $element_css = '';
$thegem_revesrse_columns_tablet = $thegem_revesrse_columns_mobile = '';
$output = $after_output = $before_output = '';

$fw_container_start = '<div class="container">';
$fw_container_end = '</div>';
$disable_custom_paddings_mobile = $disable_custom_paddings_tablet = $z_index = $vertical_slider = '';
$template_fw = ! empty($atts['template_fw']);
$visible_element_users = !empty($atts['visible_element_users']) ? $atts['visible_element_users'] : '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$uniqid = uniqid('thegem-custom-') .rand(1,9999);

wp_enqueue_script( 'wpb_composer_front_js' );

$el_class = $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_classes = array(
	'vc_row',
	'wpb_row',
	//deprecated
	'vc_row-fluid',
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

$css_classes[] = $uniqid;

if ( 'yes' === $disable_element ) {
	if ( vc_is_page_editable() ) {
		$css_classes[] = 'vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md';
	} else {
		return '';
	}
}

if ( !is_singular('thegem_templates') && (('in' === $visible_element_users && !is_user_logged_in()) || ('out' === $visible_element_users && is_user_logged_in())) ) {
	if ( vc_is_page_editable() ) {
		$css_classes[] = 'vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md';
	} else {
		return '';
	}
}

if ( vc_shortcode_custom_css_has_property( $css, array(
		'border',
		'background',
	) ) || $video_bg || $parallax
) {
	$css_classes[] = 'vc_row-has-fill';
}

if ($disable_custom_paddings_tablet) {
	$css_classes[] = 'disable-custom-paggings-tablet';
}
if ($disable_custom_paddings_mobile) {
	$css_classes[] = 'disable-custom-paggings-mobile';
}
if ($vertical_slider) {
	$full_width = empty($full_width) ? 'stretch_row' : $full_width;
	$full_height = 1;
}

if ( ! empty( $atts['gap'] ) ) {
	$css_classes[] = 'vc_column-gap-' . $atts['gap'];
}
if ( ! empty( $atts['rtl_reverse'] ) ) {
	$css_classes[] = 'vc_rtl-columns-reverse';
}
$wrapper_attributes = array();
// build attributes for wrapper
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
} else {
	$el_id = 'vc_row-' . uniqid();
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $full_width ) ) {
	$wrapper_attributes[] = 'data-vc-full-width="true"';
	$wrapper_attributes[] = 'data-vc-full-width-init="false"';
	if ( 'stretch_row_content' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
	} elseif ( 'stretch_row_content_no_spaces' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
		$css_classes[] = 'vc_row-no-padding';
	}
	$after_output .= '<div class="vc_row-full-width vc_clearfix"></div>';
}

if ( ! empty( $full_height ) ) {
	$css_classes[] = 'vc_row-o-full-height';
	if ( ! empty( $columns_placement ) ) {
		$flex_row = true;
		$css_classes[] = 'vc_row-o-columns-' . $columns_placement;
		if ( 'stretch' === $columns_placement ) {
			$css_classes[] = 'vc_row-o-equal-height';
		}
	}
	if( ! empty( $thegem_fix_full_height ) ) {
		$css_classes[] = 'thegem-fix-full-height';
	}
}

if ( ! empty( $equal_height ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-equal-height';
}

if ( ! empty( $content_placement ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-content-' . $content_placement;
}

if ( !empty( $thegem_revesrse_columns_tablet ) ) {
	$flex_row = true;
	$css_classes[] = 'thegem-reverse-columns-tablet';
}
if ( !empty( $thegem_revesrse_columns_mobile ) ) {
	$flex_row = true;
	$css_classes[] = 'thegem-reverse-columns-mobile';
}


if ( ! empty( $flex_row ) ) {
	$css_classes[] = 'vc_row-flex';
}

$has_video_bg = ( ! empty( $video_bg ) && ! empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );

$parallax_speed = $parallax_speed_bg;
if ( $has_video_bg ) {
	$parallax = $video_bg_parallax;
	$parallax_speed = $parallax_speed_video;
	$parallax_image = $video_bg_url;
	$css_classes[] = 'vc_video-bg-container';

	$output_not_consent = '';
	if (class_exists('TheGemGdpr')) {
		$output_not_consent = TheGemGdpr::getInstance()->replace_disallowed_content('', TheGemGdpr::CONSENT_NAME_YOUTUBE);
	}

	if (empty($output_not_consent)) {
	wp_enqueue_script( 'vc_youtube_iframe_api_js' );
}
}

if ($z_index !== '') {
	$element_css .= '.'.esc_attr($uniqid).'.wpb_row {z-index: '.intval($atts['z_index']).';position: relative;}';
}

if ( ! empty( $parallax ) ) {
	wp_enqueue_script( 'vc_jquery_skrollr_js' );
	$wrapper_attributes[] = 'data-vc-parallax="' . esc_attr( $parallax_speed ) . '"'; // parallax speed
	$css_classes[] = 'vc_general vc_parallax vc_parallax-' . $parallax;
	if ( false !== strpos( $parallax, 'fade' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fade';
		$wrapper_attributes[] = 'data-vc-parallax-o-fade="on"';
	} elseif ( false !== strpos( $parallax, 'fixed' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fixed';
	}
}

if ( ! empty( $parallax ) ) {
	if ( $has_video_bg ) {
		$parallax_image_src = $parallax_image;
	} else {
		$parallax_image_id = preg_replace( '/[^\d]/', '', $parallax_image );
		$parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'full' );
		if ( ! empty( $parallax_image_src[0] ) ) {
			$parallax_image_src = $parallax_image_src[0];
		}
	}
	
	if (!empty($parallax_image_src)) {
		$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $parallax_image_src ) . '"';
	} elseif (!empty($atts['thegem_featured_image_for_bg_active'])){
		$thegem_featured_image_for_bg = thegem_get_featured_image_for_bg();
		if(!empty($thegem_featured_image_for_bg)) {
			$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $thegem_featured_image_for_bg ) . '"';
		}
	} else{
		$parsed = thegem_string_between_parser($atts['css'], '?id=', ')');
		$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( thegem_attachment_url($parsed) ) . '"';
	}
	
	if(isset($background_style) && !empty($background_style) && ( strcmp($background_style, 'no-repeat') === 0 || strcmp($background_style, 'repeat') === 0) ) {
		$element_css .= '.'.esc_attr($uniqid).'.vc_parallax .vc_parallax-inner {'
						. 'background-repeat: '.esc_attr($background_style).';'
						. 'background-position: '.esc_attr($background_position_horizontal).' '.esc_attr($background_position_vertical).';'
					. ';}';
	} elseif(isset($background_style) && !empty($background_style) && ( strcmp($background_style, 'no-repeat') !== 0 || strcmp($background_style, 'repeat') !== 0 ) ) {
		$element_css .= '.'.esc_attr($uniqid).'.vc_parallax .vc_parallax-inner {'
						. 'background-size: '.esc_attr($background_style).';'
						. 'background-position: '.esc_attr($background_position_horizontal).' '.esc_attr($background_position_vertical).';'
					. ';}';
	}
}
if ( ! $parallax && $has_video_bg ) {
	$wrapper_attributes[] = 'data-vc-video-bg="' . esc_attr( $video_bg_url ) . '"';
}

if ($vertical_slider) {
	if (!preg_match('/scroller-block/', $el_class)) {
		$css_classes[] = 'scroller-block';
	}

	$page_effects_data = thegem_get_sanitize_page_effects_data(get_the_ID());
	if ($page_effects_data['effects_page_scroller_type'] == 'advanced') {
		if (!empty($vertical_slider_anchor)) {
			$vertical_slider_anchor = preg_replace('/[^A-Za-z0-9-_]/', '', $vertical_slider_anchor);
			$wrapper_attributes[] = 'data-anchor="'.esc_attr($vertical_slider_anchor).'"';
		}
		$wrapper_attributes[] = 'data-tooltip="'.esc_attr($vertical_slider_title).'"';
	}
}

if ($ken_burns_enabled) {
	wp_enqueue_style('thegem-ken-burns');
	$page_effects_data = thegem_get_sanitize_page_effects_data(get_the_ID());
	if (!$page_effects_data['effects_page_scroller']) {
		wp_enqueue_script('thegem-ken-burns');
	}

	$css_classes[] = 'vc_row_thegem-ken-burns';
	
	$ken_burns_styles = [];
	if (!empty($ken_burns_image)) {
		$ken_burns_styles = [
			'animation-duration: '.(!empty($ken_burns_transition_speed) ? esc_attr(trim($ken_burns_transition_speed)) : 15000).'ms;',
			'background-image: url('.esc_url(thegem_attachment_url($ken_burns_image)).')'
		];
	} elseif (!empty($atts['thegem_featured_image_for_bg_active'])){
		$thegem_featured_image_for_bg = thegem_get_featured_image_for_bg();
		if(!empty($thegem_featured_image_for_bg)) {
			$ken_burns_styles = [
				'animation-duration: '.(!empty($ken_burns_transition_speed) ? esc_attr(trim($ken_burns_transition_speed)) : 15000).'ms;',
				'background-image: url('.esc_url($thegem_featured_image_for_bg).')'
			];
		}
	} else{
		$parsed = thegem_string_between_parser($atts['css'], '?id=', ')');
		$ken_burns_styles = [
			'animation-duration: '.(!empty($ken_burns_transition_speed) ? esc_attr(trim($ken_burns_transition_speed)) : 15000).'ms;',
			'background-image: url('.esc_url(thegem_attachment_url($parsed)).')'
		];
	}

	$ken_burns_classes = $ken_burns_direction == 'zoom_in' ? 'thegem-ken-burns-zoom-in' : 'thegem-ken-burns-zoom-out';
	$ken_burns_output = '<div class="thegem-ken-burns-bg '.$ken_burns_classes.'" style="'.implode(' ', $ken_burns_styles).'"></div>';
}

if (!empty($video_background_type) && !empty($video_background_src)) {
	$fullwidth_uid = uniqid();
	$video = thegem_video_background(
		$video_background_type,
		$video_background_src,
		$video_background_acpect_ratio,
		false,
		$video_background_overlay_color,
		$video_background_overlay_opacity,
		thegem_attachment_url($video_background_poster),
		$video_background_play_on_mobile,
		thegem_attachment_url($video_background_fallback),
		$background_style,
		$background_position_horizontal,
		$background_position_vertical
	);
	
	/*
	$fullwidth_uid = uniqid();
	$html_js = '<script type="text/javascript">if (typeof(gem_fix_fullwidth_position) == "function") { gem_fix_fullwidth_position(document.getElementById("fullwidth-block-' . $fullwidth_uid . '")); }</script>';
	$before_output .= '<div id="fullwidth-block-' . $fullwidth_uid . '" class="fullwidth-block">'.$html_js.$video.'</div>';
	*/
	
	$before_output .= '<div class="vc_row_custom_video_background">'.$video.'</div>';
}

if(!empty($thegem_background_overlay)) {
	$before_output .= '<div class="gem-vc-background-overlay"></div>';
	$css_classes[] = 'gem-vc-background-overlay-container';
	$element_css .= thegem_vc_background_overlay_css($atts, $uniqid);
}
if(!empty($thegem_row_overflow) && $thegem_row_overflow === 'hidden') {
	$element_css .= '.'.esc_attr($uniqid).'.wpb_row {overflow: hidden;}';
}

if(!empty($interactions_enabled)) {
	$wrapper_attributes[] = interactions_data_attr($atts);
	$css_classes[] = 'gem-interactions-enabled';
}

if ( $header_sticky_row ) {
	$css_classes[] = 'header-sticky-row';
	if ( $header_shrink_row ) {
		$css_classes[] = 'header-shrink-row';
	}
}

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

if(isset($effects_enabled_delay) && !empty($effects_enabled_delay)) {
	wp_enqueue_style('thegem-wpb-animations');
	$element_css .= '.'.esc_attr($uniqid).' {-webkit-animation-delay: '.(int)$effects_enabled_delay.'ms;'
				. '-moz-animation-delay: '.(int)$effects_enabled_delay.'ms;'
				. '-o-animation-delay: '.(int)$effects_enabled_delay.'ms;'
				. 'animation-delay: '.(int)$effects_enabled_delay.'ms;}';
}

$offset_name = array('padding', 'margin');
$direction = array('top', 'bottom', 'left', 'right');
foreach ($offset_name as $name) {
	foreach ($direction as $dir) {
		$unit = 'px';
		$result = $last_result = '';
		if(isset($atts['tablet_'.$name.'_'.$dir]) && !empty($atts['tablet_'.$name.'_'.$dir]) || strcmp($atts['tablet_'.$name.'_'.$dir], '0') === 0) {
			$result = str_replace(' ', '', $atts['tablet_'.$name.'_'.$dir]);
			$last_result = substr($result, -1);
			if($last_result == '%') {
				$result = str_replace('%', '', $result);
				$unit = $last_result;
			}
			$element_css .= '@media screen and (max-width: 1023px) {.'.esc_attr($uniqid).'.wpb_row {'.$name.'-'.$dir.': '.$result.$unit.' !important;}}';
		}
		if(isset($atts['mobile_'.$name.'_'.$dir]) && !empty($atts['mobile_'.$name.'_'.$dir]) || strcmp($atts['mobile_'.$name.'_'.$dir], '0') === 0) {
			$result = str_replace(' ', '', $atts['mobile_'.$name.'_'.$dir]);
			$last_result = substr($result, -1);
			if($last_result == '%') {
				$result = str_replace('%', '', $result);
				$unit = $last_result;
			}
			$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqid).'.wpb_row {'.$name.'-'.$dir.': '.$result.$unit.' !important;}}';
		}
	}
}

if (isset($atts['el_disable_desktop']) && !empty($atts['el_disable_desktop'])) {
	$element_css .= '@media screen and (min-width: 1024px) {.'.esc_attr($uniqid).'.wpb_row {display: none;}}';
}
if (isset($atts['el_disable_tablet']) && !empty($atts['el_disable_tablet'])) {
	$element_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {.'.esc_attr($uniqid).'.wpb_row {display: none;}}';
}
if (isset($atts['el_disable_mobile']) && !empty($atts['el_disable_mobile'])) {
	$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqid).'.wpb_row {display: none;}}';
}

if (isset($atts['background_color_tablet']) && !empty($atts['background_color_tablet'])) {
	$element_css .= '@media screen and (max-width: 1023px) {.'.esc_attr($uniqid).'.wpb_row {background-color: '.$atts['background_color_tablet'].' !important;}}';
}
if (isset($atts['background_color_mobile']) && !empty($atts['background_color_mobile'])) {
	$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqid).'.wpb_row {background-color: '.$atts['background_color_mobile'].' !important;}}';
}
if (isset($atts['background_image_tablet']) && !empty($atts['background_image_tablet']) && $bg_image_tablet = thegem_attachment_url($atts['background_image_tablet'])) {
	$element_css .= '@media screen and (max-width: 1023px) {.'.esc_attr($uniqid).'.wpb_row {background-image: url('.$bg_image_tablet.') !important;}}';
}
if (isset($atts['background_image_mobile']) && !empty($atts['background_image_mobile']) && $bg_image_mobile = thegem_attachment_url($atts['background_image_mobile'])) {
	$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqid).'.wpb_row {background-image: url('.$bg_image_mobile.') !important;}}';
}

if (!empty($atts['thegem_featured_image_for_bg_active'])) {
	$thegem_featured_image_for_bg = thegem_get_featured_image_for_bg();
	if(!empty($thegem_featured_image_for_bg)) {
		$element_css .= '.'.esc_attr($uniqid).'.wpb_row {background-image: url("'.$thegem_featured_image_for_bg.'") !important;}';
	}
}
if (isset($atts['background_position_desktop']) && !empty($atts['background_position_desktop'])) {
	$element_css .= '.'.esc_attr($uniqid).'.wpb_row {background-position: '.$atts['background_position_desktop'].' !important;}';
}
if (isset($atts['background_position_tablet']) && !empty($atts['background_position_tablet'])) {
	$element_css .= '@media screen and (max-width: 1023px) {.'.esc_attr($uniqid).'.wpb_row {background-position: '.$atts['background_position_tablet'].' !important;}}';
}
if (isset($atts['background_position_mobile']) && !empty($atts['background_position_mobile'])) {
	$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqid).'.wpb_row {background-position: '.$atts['background_position_mobile'].' !important;}}';
}
if (isset($atts['background_style_tablet'])) {
	if($atts['background_style_tablet'] == 'cover' || $atts['background_style_tablet'] == 'contain') {
		$element_css .= '@media screen and (max-width: 1023px) {.'.esc_attr($uniqid).'.wpb_row {background-size: '.$atts['background_style_tablet'].' !important;background-repeat: no-repeat !important;}}';
	}
	if($atts['background_style_tablet'] == 'no-repeat' || $atts['background_style_tablet'] == 'repeat') {
		$element_css .= '@media screen and (max-width: 1023px) {.'.esc_attr($uniqid).'.wpb_row {background-repeat: '.$atts['background_style_tablet'].' !important;background-size: auto !important;}}';
	}
}
if (isset($atts['background_style_mobile'])) {
	if($atts['background_style_mobile'] == 'cover' || $atts['background_style_mobile'] == 'contain') {
		$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqid).'.wpb_row {background-size: '.$atts['background_style_mobile'].' !important;background-repeat: no-repeat !important;}}';
	}
	if($atts['background_style_mobile'] == 'no-repeat' || $atts['background_style_mobile'] == 'repeat') {
		$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqid).'.wpb_row {background-repeat: '.$atts['background_style_mobile'].' !important;background-size: auto !important;}}';
	}
}

if(!empty($thegem_enable_shadow)) {
	$shadow_position = '';
	if($thegem_shadow_position == 'inset') {
		$shadow_position = 'inset';
	}
	if(empty($thegem_shaow_horizontal)) {
		$thegem_shaow_horizontal = 0;
	}
	if(empty($thegem_shaow_vertical)) {
		$thegem_shaow_vertical = 0;
	}
	if(empty($thegem_shaow_blur)) {
		$thegem_shaow_blur = 0;
	}
	if(empty($thegem_shaow_spread)) {
		$thegem_shaow_spread = 0;
	}
	if(empty($thegem_shadow_color)) {
		$thegem_shadow_color = '#000';
	}
	$element_css .= '.'.esc_attr($uniqid).'.wpb_row {'
		. 'box-shadow: '.$shadow_position.' '.$thegem_shaow_horizontal.'px '.$thegem_shaow_vertical.'px '.$thegem_shaow_blur.'px '.$thegem_shaow_spread.'px '.$thegem_shadow_color.';'
	. '}';
}

if ( ! empty( $full_width ) ) {
	$output .= '<div class="vc_row-full-width-before"></div>';
} elseif($template_fw) {
	$output .= $fw_container_start;
}

if (isset($output_not_consent) && !empty($output_not_consent)) {
	foreach ($wrapper_attributes as $k=>$wrapper_attribute) {
		preg_match('%(data-vc-video-bg|data-vc-parallax-image).*%', $wrapper_attribute, $matches);
		if (isset($matches[0])) {
			unset($wrapper_attributes[$k]);
		}
	}

$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
	$output .= $output_not_consent;
} else {
	$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
}
if ($template_fw && $full_width === 'stretch_row') {
	$output .= $fw_container_start;
}

if ($ken_burns_enabled) {
	$output .= $ken_burns_output;
}

if ( ! empty( $full_height ) ) {
	$output .= '<script type="text/javascript">fullHeightRow();</script>';
}

if ( ! empty( $full_width ) && !$template_fw ) {
	$output .= '<script type="text/javascript">if (typeof(gem_fix_fullwidth_position) == "function") { gem_fix_fullwidth_position(document.getElementById("' . $el_id . '")); }</script>';
}

if ( ! empty( $custom_css_code ) && is_string($custom_css_code) ) {
	$output .= PHP_EOL.'<style>'.PHP_EOL.thegem_addPrefixToCssSelectors('#'.esc_attr( $el_id ), rawurldecode(base64_decode(wp_strip_all_tags($custom_css_code)))).PHP_EOL.'</style>'.PHP_EOL;
}
if(!empty($element_css)) {
	$output .= '<style>'. $element_css .'</style>';
}

$output .= $before_output;
$output .= wpb_js_remove_wpautop( $content );
if ($template_fw && $full_width === 'stretch_row') {
	$output .= $fw_container_end;
}
$output .= '</div>';
if ($template_fw && empty( $full_width ) ) {
	$output .= $fw_container_end;
}
$output .= $after_output;

echo $output;
