<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_id
 * @var $el_class
 * @var $width
 * @var $css
 * @var $offset
 * @var $content - shortcode content
 * @var $css_animation
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Column
 */
$el_class = $el_id = $width = $parallax_speed_bg = $parallax_speed_video = $parallax = $parallax_image = $video_bg = $video_bg_url = $video_bg_parallax = $css = $offset = $css_animation = '';
$output = $before_output = '';
$disable_custom_paddings_mobile = $disable_custom_paddings_tablet = $z_index = '';
$background_style = $background_position_horizontal = $background_position_vertical = '';
$element_css = $output = $uniqid = $uniqInnerClass = $editor_attr = '';
$template_flex = ! empty($atts['template_flex']);
$visible_element_users = !empty($atts['visible_element_users']) ? $atts['visible_element_users'] : '';
$flex_atts = $atts;
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$uniqid = uniqid('thegem-custom-') .rand(1,9999);
$uniqInnerClass = uniqid('thegem-custom-inner-');

wp_enqueue_script( 'wpb_composer_front_js' );

$width = wpb_translateColumnWidthToSpan( $width );
$width = vc_column_offset_class_merge( $offset, $width );

$css_classes = array(
	$this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation ),
	'wpb_column',
	'vc_column_container',
	$width,
);

$css_classes[] = $uniqid;

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
	$css_classes[] = 'vc_col-has-fill';
}

if ($disable_custom_paddings_tablet) {
	$css_classes[] = 'disable-custom-paggings-tablet';
}
if ($disable_custom_paddings_mobile) {
	$css_classes[] = 'disable-custom-paggings-mobile';
}

if (!empty($thegem_content_alignment)) {
	$css_classes[] = 'gem-content-alignment-'.$thegem_content_alignment;
}

if ($z_index !== '') {
	$element_css .= '.'.esc_attr($uniqid).'.wpb_column {z-index: '.intval($atts['z_index']).';}';
}

$wrapper_attributes = array();

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
	
	//print_r($parallax_image_src); exit;
	
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
	
	$element_css .= '.'.esc_attr($uniqid).' .vc_column-inner {background-image: none !important;}';
}
if ( ! $parallax && $has_video_bg ) {
	$wrapper_attributes[] = 'data-vc-video-bg="' . esc_attr( $video_bg_url ) . '"';
}

$data_sticky = '';
if (!empty($el_sticky) && isset($el_sticky_offset)) {
	wp_enqueue_script( 'thegem-stickyColumn' );
	$data_sticky .= ' data-sticky-offset="' . esc_attr(intval($el_sticky_offset)) . '"';
}

if(!empty($thegem_background_overlay)) {
	$before_output .= '<div class="gem-vc-background-overlay"></div>';
	$css_classes[] = 'gem-vc-background-overlay-container';
	$element_css .= thegem_vc_background_overlay_css($atts, $uniqid);
}

if(!empty($interactions_enabled)) {
	$wrapper_attributes[] = interactions_data_attr($atts);
    $css_classes[] = 'gem-interactions-enabled';
}

$flex_css = '';
if(function_exists('thegem_templates_flex_options') && $template_flex && (!isset($flex_atts['flexbox_enabled']) || !empty($flex_atts['flexbox_enabled']))) {
	$flex_css = thegem_templates_flex_options($uniqid, '.thegem-template-wrapper .wpb_wrapper', $flex_atts);
}

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

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
			$element_css .= '@media screen and (max-width: 1023px) {.'.esc_attr($uniqInnerClass).'.vc_column-inner {'.$name.'-'.$dir.': '.$result.$unit.' !important;}}';
		}
		if(isset($atts['mobile_'.$name.'_'.$dir]) && !empty($atts['mobile_'.$name.'_'.$dir]) || strcmp($atts['mobile_'.$name.'_'.$dir], '0') === 0) {
			$result = str_replace(' ', '', $atts['mobile_'.$name.'_'.$dir]);
			$last_result = substr($result, -1);
			if($last_result == '%') {
				$result = str_replace('%', '', $result);
				$unit = $last_result;
			}
			$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqInnerClass).'.vc_column-inner {'.$name.'-'.$dir.': '.$result.$unit.' !important;}}';
		}
	}
}

if (isset($atts['background_color_tablet']) && !empty($atts['background_color_tablet'])) {
	$element_css .= '@media screen and (max-width: 1023px) {.'.esc_attr($uniqInnerClass).'.vc_column-inner {background-color: '.$atts['background_color_tablet'].' !important;}}';
}
if (isset($atts['background_color_mobile']) && !empty($atts['background_color_mobile'])) {
	$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqInnerClass).'.vc_column-inner {background-color: '.$atts['background_color_mobile'].' !important;}}';
}
if (isset($atts['background_image_tablet']) && !empty($atts['background_image_tablet']) && $bg_image_tablet = thegem_attachment_url($atts['background_image_tablet'])) {
	$element_css .= '@media screen and (max-width: 1023px) {.'.esc_attr($uniqInnerClass).'.vc_column-inner {background-image: url('.$bg_image_tablet.') !important;}}';
}
if (isset($atts['background_image_mobile']) && !empty($atts['background_image_mobile']) && $bg_image_mobile = thegem_attachment_url($atts['background_image_mobile'])) {
	$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqInnerClass).'.vc_column-inner {background-image: url('.$bg_image_mobile.') !important;}}';
}
if (!empty($atts['thegem_featured_image_for_bg_active']) && empty($parallax)) {
	$thegem_featured_image_for_bg = thegem_get_featured_image_for_bg();
	if(!empty($thegem_featured_image_for_bg)) {
		$element_css .= '.'.esc_attr($uniqid).' > .vc_column-inner {background-image: url("'.$thegem_featured_image_for_bg.'") !important;}';
	}
}
if (isset($atts['background_position_desktop']) && !empty($atts['background_position_desktop'])) {
	$element_css .= '.'.esc_attr($uniqInnerClass).'.vc_column-inner {background-position: '.$atts['background_position_desktop'].' !important;}';
}
if (isset($atts['background_position_tablet']) && !empty($atts['background_position_tablet'])) {
	$element_css .= '@media screen and (max-width: 1023px) {.'.esc_attr($uniqInnerClass).'.vc_column-inner {background-position: '.$atts['background_position_tablet'].' !important;}}';
}
if (isset($atts['background_position_mobile']) && !empty($atts['background_position_mobile'])) {
	$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqInnerClass).'.vc_column-inner {background-position: '.$atts['background_position_mobile'].' !important;}}';
}
if (isset($atts['background_style_tablet'])) {
	if($atts['background_style_tablet'] == 'cover' || $atts['background_style_tablet'] == 'contain') {
		$element_css .= '@media screen and (max-width: 1023px) {.'.esc_attr($uniqInnerClass).'.vc_column-inner {background-size: '.$atts['background_style_tablet'].' !important;background-repeat: no-repeat !important;}}';
	}
	if($atts['background_style_tablet'] == 'no-repeat' || $atts['background_style_tablet'] == 'repeat') {
		$element_css .= '@media screen and (max-width: 1023px) {.'.esc_attr($uniqInnerClass).'.vc_column-inner {background-repeat: '.$atts['background_style_tablet'].' !important;background-size: auto !important;}}';
	}
}
if (isset($atts['background_style_mobile'])) {
	if($atts['background_style_mobile'] == 'cover' || $atts['background_style_mobile'] == 'contain') {
		$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqInnerClass).'.vc_column-inner {background-size: '.$atts['background_style_mobile'].' !important;background-repeat: no-repeat !important;}}';
	}
	if($atts['background_style_mobile'] == 'no-repeat' || $atts['background_style_mobile'] == 'repeat') {
		$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqInnerClass).'.vc_column-inner {background-repeat: '.$atts['background_style_mobile'].' !important;background-size: auto !important;}}';
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
	$element_css .= '.'.esc_attr($uniqid).'.wpb_column > .vc_column-inner {'
		. 'box-shadow: '.$shadow_position.' '.$thegem_shaow_horizontal.'px '.$thegem_shaow_vertical.'px '.$thegem_shaow_blur.'px '.$thegem_shaow_spread.'px '.$thegem_shadow_color.';'
	. '}';
}

if(function_exists('thegem_data_editor_attribute')) {
	$editor_attr = thegem_data_editor_attribute($uniqid.'-editor');
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
	$output .= '<div ' . implode( ' ', $wrapper_attributes ) . ' ' . $editor_attr . '>';
}

$output .= $before_output;
$output .= '<div class="vc_column-inner '.esc_attr($uniqInnerClass).' ' . esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) ) . '"'.$data_sticky.'>';
if(!empty($element_css) || !empty($flex_css)) {
	$output .= '<style>'. $element_css . PHP_EOL . $flex_css .'</style>';
}
$output .= '<div class="wpb_wrapper '.esc_attr($uniqid).'">';
$output .= wpb_js_remove_wpautop( $content );
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';

echo $output;
