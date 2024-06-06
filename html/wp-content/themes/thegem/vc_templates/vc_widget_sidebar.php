<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $el_class
 * @var $el_id
 * @var $sidebar_id
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Widget_sidebar $this
 */
$title = $el_class = $el_id = $sidebar_id = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if ( '' === $sidebar_id ) {
	return null;
}

$el_class = $this->getExtraClass( $el_class );

ob_start();
dynamic_sidebar( $sidebar_id );
$sidebar_value = ob_get_contents();
ob_end_clean();

$sidebar_value = trim( $sidebar_value );
$sidebar_value = ( '<li' === substr( $sidebar_value, 0, 3 ) ) ? '<ul>' . $sidebar_value . '</ul>' : $sidebar_value;

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_widgetised_column wpb_content_element' . $el_class, $this->settings['base'], $atts );
$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

if(isset($GLOBALS['thegem_template_type']) && ($GLOBALS['thegem_template_type'] === 'single-product' || $GLOBALS['thegem_template_type'] === 'product-archive')) {
	$thegem_sidebar_classes = 'portfolio-filters-list native style-sidebar';
	if (!empty(thegem_get_option('categories_collapsible'))) {
		$thegem_sidebar_classes .= ' categories-widget-collapsible';
	}
	$output =
		'<div ' . implode( ' ', $wrapper_attributes ) . ' class="' . esc_attr( $css_class ) . '">
			<div class="wpb_wrapper">
				<div class="' . $thegem_sidebar_classes . '">
					<div class="widget-area-wrap">
						<div class="page-sidebar widget-area">
							'.wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_widgetised_column_heading')).' '.$sidebar_value.'
						</div>
					</div>
				</div>
			</div>
		</div>';
} else {
	$output = '<div ' . implode( ' ', $wrapper_attributes ) . ' class="' . esc_attr( $css_class ) . '">
		<div class="wpb_wrapper">
			' . wpb_widget_title( array(
			'title' => $title,
			'extraclass' => 'wpb_widgetised_column_heading',
		) ) . '
			' . $sidebar_value . '
		</div>
	</div>';
}

return $output;
