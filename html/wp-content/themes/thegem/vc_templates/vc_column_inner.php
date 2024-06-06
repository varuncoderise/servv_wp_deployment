<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $width
 * @var $css
 * @var $offset
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Column_Inner
 */
$el_class = $width = $el_id = $css = $offset = '';
$element_css = $output = $before_output = $uniqid = $uniqInnerClass = '';
$disable_custom_paddings_mobile = $disable_custom_paddings_tablet = $z_index = '';
$template_flex = ! empty($atts['template_flex']);
$visible_element_users = !empty($atts['visible_element_users']) ? $atts['visible_element_users'] : '';
$flex_atts = $atts;
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$width = wpb_translateColumnWidthToSpan( $width );
$width = vc_column_offset_class_merge( $offset, $width );

$uniqid = uniqid('thegem-custom-') .rand(1,9999);
$uniqInnerClass = uniqid('thegem-custom-inner-');

$css_classes = array(
	$this->getExtraClass( $el_class ),
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
) ) ) {
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
if (!empty($atts['thegem_featured_image_for_bg_active'])) {
	$thegem_featured_image_for_bg = thegem_get_featured_image_for_bg();
	if(!empty($thegem_featured_image_for_bg)) {
		$element_css .= '.'.esc_attr($uniqid).' .vc_column-inner {background-image: url("'.$thegem_featured_image_for_bg.'") !important;}';
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

$data_sticky = '';
if (!empty($el_sticky) && isset($el_sticky_offset)) {
	wp_enqueue_script( 'thegem-stickyColumn' );
	$data_sticky .= ' data-sticky-offset="' . esc_attr(intval($el_sticky_offset)) . '"';
}

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
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
