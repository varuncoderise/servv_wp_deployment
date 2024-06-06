<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * @var $el_class
 * @var $el_id
 * @var WPBakeryShortCode_Vc_Tta_Accordion|WPBakeryShortCode_Vc_Tta_Tabs|WPBakeryShortCode_Vc_Tta_Tour|WPBakeryShortCode_Vc_Tta_Pageable $this
 */
$el_class = $css = $css_animation = $thegem_custom_css = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$this->resetVariables( $atts, $content );
extract( $atts );
$this->setGlobalTtaInfo();

$this->enqueueTtaStyles();
$this->enqueueTtaScript();

// It is required to be before tabs-list-top/left/bottom/right for tabs/tours
$prepareContent = $this->getTemplateVariable( 'content' );

$class_to_filter = $this->getTtaGeneralClasses();
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getCSSAnimation( $css_animation );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

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
$uniqClass = uniqid('thegem-custom-') .rand(1,9999);
if( isset($thegem_top_spacing) && (intval($thegem_top_spacing) > 0 || $thegem_top_spacing === '0') ) {
	$thegem_custom_css .= '.'.$uniqClass.'.vc_tta.vc_general.vc_tta-accordion.vc_tta-style-'.$style.' .vc_tta-panel-body { padding-top: '.intval($thegem_top_spacing).'px; }';
}
if( isset($thegem_bottom_spacing) && (intval($thegem_bottom_spacing) > 0 || $thegem_bottom_spacing === '0') ) {
	$thegem_custom_css .= '.'.$uniqClass.'.vc_tta.vc_general.vc_tta-accordion.vc_tta-style-'.$style.' .vc_tta-panel-body { padding-bottom: '.intval($thegem_bottom_spacing).'px; }';
}
if( !empty($thegem_separator_color) ) {
	$thegem_custom_css .= '.'.$uniqClass.'.vc_tta.vc_general.vc_tta-accordion.vc_tta-style-'.$style.' .vc_tta-panel + .vc_tta-panel { border-top-color: '.$thegem_separator_color.' }';
}
if( !empty($thegem_custom_css) ) {
	$css_class .= ' '.$uniqClass;
	$thegem_custom_css = '<style>'.$thegem_custom_css.'</style>';
}

$output = '<div ' . $this->getWrapperAttributes() . '>'.$thegem_custom_css;
$output .= $this->getTemplateVariable( 'title' );
$output .= '<div class="' . esc_attr( $css_class ) . '" '.$styles.'>';
$output .= $this->getTemplateVariable( 'tabs-list-top' );
$output .= $this->getTemplateVariable( 'tabs-list-left' );
$output .= '<div class="vc_tta-panels-container">';
$output .= $this->getTemplateVariable( 'pagination-top' );
$output .= '<div class="vc_tta-panels">';
$output .= $prepareContent;
$output .= '</div>';
$output .= $this->getTemplateVariable( 'pagination-bottom' );
$output .= '</div>';
$output .= $this->getTemplateVariable( 'tabs-list-bottom' );
$output .= $this->getTemplateVariable( 'tabs-list-right' );
$output .= '</div>';
$output .= '</div>';

return do_shortcode($output);
