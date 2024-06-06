<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $css
 * @var $el_id
 * @var $equal_height
 * @var $content_placement
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Row_Inner
 */
$el_class = $equal_height = $content_placement = $css = $el_id = '';
$disable_element = '';
$thegem_revesrse_columns_tablet = $thegem_revesrse_columns_mobile = '';
$output = $before_output = $after_output = $element_css = $id = '';
$disable_custom_paddings_mobile = $disable_custom_paddings_tablet = $z_index = '';
$visible_element_users = !empty($atts['visible_element_users']) ? $atts['visible_element_users'] : '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$uniqid = uniqid('thegem-custom-') .rand(1,9999);

$el_class = $this->getExtraClass( $el_class );
$css_classes = array(
	'vc_row',
	'wpb_row',
	//deprecated
	'vc_inner',
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
) ) ) {
	$css_classes[] = 'vc_row-has-fill';
}

if ($disable_custom_paddings_tablet) {
	$css_classes[] = 'disable-custom-paggings-tablet';
}
if ($disable_custom_paddings_mobile) {
	$css_classes[] = 'disable-custom-paggings-mobile';
}

if ( ! empty( $atts['gap'] ) ) {
	$css_classes[] = 'vc_column-gap-' . $atts['gap'];
}

if ( ! empty( $equal_height ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-equal-height';
}

if ( ! empty( $atts['rtl_reverse'] ) ) {
	$css_classes[] = 'vc_rtl-columns-reverse';
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

if ($z_index !== '') {
	$element_css .= '.'.esc_attr($uniqid).'.wpb_row {z-index: '.intval($atts['z_index']).';position: relative;}';
}

$wrapper_attributes = array();

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

$css_classes[] = $uniqClass = uniqid('custom-inner-column-');

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
			$element_css .= '@media screen and (max-width: 1023px) {.'.esc_attr($uniqClass).'.wpb_row {'.$name.'-'.$dir.': '.$result.$unit.' !important;}}';
		}
		if(isset($atts['mobile_'.$name.'_'.$dir]) && !empty($atts['mobile_'.$name.'_'.$dir]) || strcmp($atts['mobile_'.$name.'_'.$dir], '0') === 0) {
			$result = str_replace(' ', '', $atts['mobile_'.$name.'_'.$dir]);
			$last_result = substr($result, -1);
			if($last_result == '%') {
				$result = str_replace('%', '', $result);
				$unit = $last_result;
			}
			$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqClass).'.wpb_row {'.$name.'-'.$dir.': '.$result.$unit.' !important;}}';
		}
	}
}
if (isset($atts['el_disable_desktop']) && !empty($atts['el_disable_desktop'])) {
	$element_css .= '@media screen and (min-width: 1024px) {.'.esc_attr($uniqClass).'.wpb_row {display: none;}}';
}
if (isset($atts['el_disable_tablet']) && !empty($atts['el_disable_tablet'])) {
	$element_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {.'.esc_attr($uniqClass).'.wpb_row {display: none;}}';
}
if (isset($atts['el_disable_mobile']) && !empty($atts['el_disable_mobile'])) {
	$element_css .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqClass).'.wpb_row {display: none;}}';
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

$element_css .= thegem_row_inner_absolute_css($atts, $uniqid);

if(function_exists('thegem_data_editor_attribute')) {
	$editor_attr = thegem_data_editor_attribute($uniqid.'-editor');
}

// build attributes for wrapper
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

$output .= '<div ' . implode( ' ', $wrapper_attributes ) . ' ' . $editor_attr . '>';
$output .= $before_output;
$output .= wpb_js_remove_wpautop( $content );
$output .= '</div>';
$output .= $after_output;

if(!empty($element_css)) {
    $output .= '<style type="text/css">'.$element_css.'</style>';
}

echo $output;
