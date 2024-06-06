<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $css_animation
 * @var $css
 * @var $content - shortcode content
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Column_text $this
 */
$el_class = $el_id = $css = $css_animation = '';
$design_atts = $atts;
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$styles = '';
if(isset($effects_enabled_delay) && !empty($effects_enabled_delay)) {
	wp_enqueue_style('thegem-wpb-animations');
	$styles .= '-webkit-animation-delay: '.(int)$effects_enabled_delay.'ms;';
	$styles .= '-moz-animation-delay: '.(int)$effects_enabled_delay.'ms;';
	$styles .= '-o-animation-delay: '.(int)$effects_enabled_delay.'ms;';
	$styles .= 'animation-delay: '.(int)$effects_enabled_delay.'ms;';
}
if(!empty($styles)) {
	$styles = ' style="'.$styles.'"';
}
$class_to_filter = 'wpb_text_column wpb_content_element ' . $this->getCSSAnimation( $css_animation );

$interactions_before_html = $interactions_after_html = '';
if(!empty($interactions_enabled)) {
	$interactions_before_html .= '<div class="gem-interactions-enabled '.$this->getExtraClass( $el_class ).'" '.interactions_data_attr($atts).'>';
		if($mouse_effects) {
			$interactions_before_html .= '<div class="mouse-effects-container">';
			$interactions_after_html .= '</div>';
		}
	$interactions_after_html .= '</div>';
	$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) ;
} else {
	$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
}

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

// Init Design Options Params
$resolution = array('desktop', 'tablet', 'mobile');
$gaps = array('padding', 'margin');
$position = array('top', 'bottom', 'left', 'right');
$resolution_params = [];
foreach ($resolution as $res) {
	foreach ($gaps as $gap) {
		foreach ($position as $pos) {
			$resolution_params[$res.'_'.$gap.'_'.$pos ] = '';
		}
	}
}
$params = array_merge($resolution_params, $design_atts);
$uniqid = uniqid('thegem-custom-').rand(1,9999);
$css_class .= ' thegem-vc-text '.$uniqid;
$custom_css = $editor_attr = '';
if(function_exists('thegem_templates_element_design_options') && !empty($params)) {
	$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-vc-text', $params);
	
	if(function_exists('thegem_data_editor_attribute')) {
		$editor_attr = thegem_data_editor_attribute($uniqid.'-editor');
	}
}

$thegem_css = !empty($custom_css) ? '<style>'. $custom_css .'</style>' : '';

$output = '
	'.$interactions_before_html.'
		<div class="' . esc_attr( $css_class ) . '" ' . implode( ' ', $wrapper_attributes ) . ''.$styles.' '.$editor_attr.'>
			<div class="wpb_wrapper">
				' . wpb_js_remove_wpautop( $content, true ) . '
			</div>
			'. $thegem_css .'
		</div>
	'.$interactions_after_html.'
';

echo $output;
