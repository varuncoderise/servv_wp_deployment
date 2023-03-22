<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $source
 * @var $text
 * @var $link
 * @var $google_fonts
 * @var $font_container
 * @var $el_class
 * @var $el_id
 * @var $css
 * @var $css_animation
 * @var $font_container_data - returned from $this->getAttributes
 * @var $google_fonts_data - returned from $this->getAttributes
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Custom_heading $this
 */
$source = $text = $link = $google_fonts = $font_container = $el_id = $el_class = $css = $css_animation = $font_container_data = $google_fonts_data = $styles = $delay_styles = array();
// This is needed to extract $font_container_data and $google_fonts_data
extract( $this->getAttributes( $atts ) );

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$interactions_before_html = $interactions_after_html = '';
if(!empty($interactions_enabled)) {
	$interactions_before_html .= '<div class="gem-interactions-enabled '.$el_class.'" '.interactions_data_attr($atts).'>';
		if($mouse_effects) {
			$interactions_before_html .= '<div class="mouse-effects-container">';
			$interactions_after_html .= '</div>';
		}
	$interactions_after_html .= '</div>';
	/**
	 * @var $css_class
	 */
	extract( $this->getStyles( $this->getCSSAnimation( $css_animation ), $css, $google_fonts_data, $font_container_data, $atts ) );
} else {
	/**
	 * @var $css_class
	 */
	extract( $this->getStyles( $el_class . $this->getCSSAnimation( $css_animation ), $css, $google_fonts_data, $font_container_data, $atts ) );
}

$settings = get_option( 'wpb_js_google_fonts_subsets' );
if ( is_array( $settings ) && ! empty( $settings ) ) {
	$subsets = '&subset=' . implode( ',', $settings );
} else {
	$subsets = '';
}

if ( ( ! isset( $atts['use_theme_fonts'] ) || 'yes' !== $atts['use_theme_fonts'] ) && isset( $google_fonts_data['values']['font_family'] ) ) {
	wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets, [], WPB_VC_VERSION );
}

if(isset($effects_enabled_delay) && !empty($effects_enabled_delay)) {
	wp_enqueue_style('thegem-wpb-animations');
	$styles[] = '-webkit-animation-delay: '.(int)$effects_enabled_delay.'ms';
	$styles[] = '-moz-animation-delay: '.(int)$effects_enabled_delay.'ms';
	$styles[] = '-o-animation-delay: '.(int)$effects_enabled_delay.'ms';
	$styles[] = 'animation-delay: '.(int)$effects_enabled_delay.'ms';
}

if ( ! empty( $styles ) ) {
	$style = 'style="' . esc_attr( implode( ';', $styles ) ) . ''.esc_attr( implode( ';', $delay_styles ) ).'"';
} else {
	$style = '';
}

if ( 'post_title' === $source ) {
	$text = get_the_title( get_the_ID() );
}

if ( ! empty( $link ) ) {
	$link = vc_build_link( $link );
	$text = '<a href="' . esc_url( $link['url'] ) . '"' . ( $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '"' : '' ) . ( $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : '' ) . ( $link['title'] ? ' title="' . esc_attr( $link['title'] ) . '"' : '' ) . '>' . $text . '</a>';
}
$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$output = '';
$output .= $interactions_before_html;
	if ( apply_filters( 'vc_custom_heading_template_use_wrapper', false ) ) {
		$output .= '<div class="' . esc_attr( $css_class ) . '" ' . implode( ' ', $wrapper_attributes ) . '>';
		$output .= '<' . $font_container_data['values']['tag'] . ' ' . $style . ' >';
		$output .= $text;
		$output .= '</' . $font_container_data['values']['tag'] . '>';
		$output .= '</div>';
	} else {
		$output .= '<' . $font_container_data['values']['tag'] . ' ' . $style . ' class="' . esc_attr( $css_class ) . '" ' . implode( ' ', $wrapper_attributes ) . '>';
		$output .= $text;
		$output .= '</' . $font_container_data['values']['tag'] . '>';
	}
$output .= $interactions_after_html;

return $output;
