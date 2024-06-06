<?php

class TheGem_Template_Element_Product_Archive_Breadcrumbs extends TheGem_Product_Archive_Template_Element {
	
	public function __construct() {
	}
	
	public function get_name() {
		return 'thegem_te_product_archive_breadcrumbs';
	}
	
	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'alignment' => 'left',
			'color' => '',
			'color_hover' => '',
			'color_active' => '',
			'desktop_padding_top' => '',
			'desktop_padding_bottom' => '',
			'desktop_padding_left' => '',
			'desktop_padding_right' => '',
			'tablet_padding_top' => '',
			'tablet_padding_bottom' => '',
			'tablet_padding_left' => '',
			'tablet_padding_right' => '',
			'mobile_padding_top' => '',
			'mobile_padding_bottom' => '',
			'mobile_padding_left' => '',
			'mobile_padding_right' => '',
			'desktop_margin_top' => '',
			'desktop_margin_bottom' => '',
			'desktop_margin_left' => '',
			'desktop_margin_right' => '',
			'tablet_margin_top' => '',
			'tablet_margin_bottom' => '',
			'tablet_margin_left' => '',
			'tablet_margin_right' => '',
			'mobile_margin_top' => '',
			'mobile_margin_bottom' => '',
			'mobile_margin_left' => '',
			'mobile_margin_right' => '',
		),
			thegem_templates_extra_options_extract()
		), $atts, 'thegem_te_product_archive_breadcrumbs');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		
		// Init Breadcrumbs
		ob_start();
		$term = thegem_templates_product_archive_source();
  
		if (empty($term)) {
			ob_end_clean();
			return thegem_templates_close_product_archive($this->get_name(), $this->shortcode_settings(), '');
		}
		
		?>
		
		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
		     class="thegem-te-product-archive-breadcrumbs <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">

            <div class="product-breadcrumbs">
				<?= gem_breadcrumbs(true) ?>
            </div>
		</div>
		
		<?php
		//Custom Styles
		$customize = '.thegem-te-product-archive-breadcrumbs.'.$uniqid;
		$custom_css = '';
		
		if (!empty($params['alignment'])) {
			$custom_css .= $customize.' .product-breadcrumbs {justify-content: ' . $params['alignment'] . '; text-align: ' . $params['alignment'] . ';}';
			$custom_css .= $customize.' .product-breadcrumbs ul {justify-content: ' . $params['alignment'] . '; text-align: ' . $params['alignment'] . ';}';
		}
		if (!empty($params['color'])) {
			$custom_css .= $customize.' .product-breadcrumbs ul li {color: ' . $params['color'] . ';}';
			$custom_css .= $customize.' .product-breadcrumbs ul li a {color: ' . $params['color'] . ';}';
		}
		if (!empty($params['color_hover'])) {
			$custom_css .= $customize.' .product-breadcrumbs ul li a:hover {color: ' . $params['color_hover'] . ';}';
		}
		if (!empty($params['color_active'])) {
			$custom_css .= $customize.' .product-breadcrumbs ul li:last-child {color: ' . $params['color_active'] . ';}';
		}

		$padding_styles = '';
		$padding_tablet_styles = '';
		$padding_mobile_styles = '';
		if(intval($params['desktop_padding_top']) > 0 || $params['desktop_padding_bottom'] === '0') {
			$padding_styles .= 'padding-top: '.intval($params['desktop_padding_top']).'px;';
		}
		if(intval($params['desktop_padding_bottom']) > 0 || $params['desktop_padding_bottom'] === '0') {
			$padding_styles .= 'padding-bottom: '.intval($params['desktop_padding_bottom']).'px;';
		}
		if(intval($params['desktop_padding_left']) > 0 || $params['desktop_padding_left'] === '0') {
			$padding_styles .= 'padding-left: '.intval($params['desktop_padding_left']).'px;';
		}
		if(intval($params['desktop_padding_right']) > 0 || $params['desktop_padding_right'] === '0') {
			$padding_styles .= 'padding-right: '.intval($params['desktop_padding_right']).'px;';
		}

		if(intval($params['tablet_padding_left']) > 0 || $params['tablet_padding_left'] === '0') {
			$padding_tablet_styles .= 'padding-top: '.intval($params['tablet_padding_left']).'px;';
		}
		if(intval($params['tablet_padding_right']) > 0 || $params['tablet_padding_right'] === '0') {
			$padding_tablet_styles .= 'padding-bottom: '.intval($params['tablet_padding_right']).'px;';
		}
		if(intval($params['tablet_padding_left']) > 0 || $params['tablet_padding_left'] === '0') {
			$padding_tablet_styles .= 'padding-left: '.intval($params['tablet_padding_left']).'px;';
		}
		if(intval($params['tablet_padding_right']) > 0 || $params['tablet_padding_right'] === '0') {
			$padding_tablet_styles .= 'padding-right: '.intval($params['tablet_padding_right']).'px;';
		}

		if(intval($params['mobile_padding_left']) > 0 || $params['mobile_padding_left'] === '0') {
			$padding_mobile_styles .= 'padding-top: '.intval($params['mobile_padding_left']).'px;';
		}
		if(intval($params['mobile_padding_right']) > 0 || $params['mobile_padding_right'] === '0') {
			$padding_mobile_styles .= 'padding-bottom: '.intval($params['mobile_padding_right']).'px;';
		}
		if(intval($params['mobile_padding_left']) > 0 || $params['mobile_padding_left'] === '0') {
			$padding_mobile_styles .= 'padding-left: '.intval($params['mobile_padding_left']).'px;';
		}
		if(intval($params['mobile_padding_right']) > 0 || $params['mobile_padding_right'] === '0') {
			$padding_mobile_styles .= 'padding-right: '.intval($params['mobile_padding_right']).'px;';
		}

		if(intval($params['desktop_margin_top']) > 0 || $params['desktop_margin_bottom'] === '0') {
			$padding_styles .= 'margin-top: '.intval($params['desktop_margin_top']).'px;';
		}
		if(intval($params['desktop_margin_bottom']) > 0 || $params['desktop_margin_bottom'] === '0') {
			$padding_styles .= 'margin-bottom: '.intval($params['desktop_margin_bottom']).'px;';
		}
		if(intval($params['desktop_margin_left']) > 0 || $params['desktop_margin_left'] === '0') {
			$padding_styles .= 'margin-left: '.intval($params['desktop_margin_left']).'px;';
		}
		if(intval($params['desktop_margin_right']) > 0 || $params['desktop_margin_right'] === '0') {
			$padding_styles .= 'margin-right: '.intval($params['desktop_margin_right']).'px;';
		}

		if(intval($params['tablet_margin_left']) > 0 || $params['tablet_margin_left'] === '0') {
			$padding_tablet_styles .= 'margin-top: '.intval($params['tablet_margin_left']).'px;';
		}
		if(intval($params['tablet_margin_right']) > 0 || $params['tablet_margin_right'] === '0') {
			$padding_tablet_styles .= 'margin-bottom: '.intval($params['tablet_margin_right']).'px;';
		}
		if(intval($params['tablet_margin_left']) > 0 || $params['tablet_margin_left'] === '0') {
			$padding_tablet_styles .= 'margin-left: '.intval($params['tablet_margin_left']).'px;';
		}
		if(intval($params['tablet_margin_right']) > 0 || $params['tablet_margin_right'] === '0') {
			$padding_tablet_styles .= 'margin-right: '.intval($params['tablet_margin_right']).'px;';
		}

		if(intval($params['mobile_margin_left']) > 0 || $params['mobile_margin_left'] === '0') {
			$padding_mobile_styles .= 'margin-top: '.intval($params['mobile_margin_left']).'px;';
		}
		if(intval($params['mobile_margin_right']) > 0 || $params['mobile_margin_right'] === '0') {
			$padding_mobile_styles .= 'margin-bottom: '.intval($params['mobile_margin_right']).'px;';
		}
		if(intval($params['mobile_margin_left']) > 0 || $params['mobile_margin_left'] === '0') {
			$padding_mobile_styles .= 'margin-left: '.intval($params['mobile_margin_left']).'px;';
		}
		if(intval($params['mobile_margin_right']) > 0 || $params['mobile_margin_right'] === '0') {
			$padding_mobile_styles .= 'margin-right: '.intval($params['mobile_margin_right']).'px;';
		}

		$padding_custom_css ='';
		if(!empty($padding_styles)) {
			$padding_custom_css .= $customize.' {'.$padding_styles.'}';
		}
		if(!empty($padding_tablet_styles)) {
			$padding_custom_css .='@media (max-width: 992px) {'.$customize.' {'.$padding_tablet_styles.'}}';
		}
		if(!empty($padding_mobile_styles)) {
			$padding_custom_css .='@media (max-width: 767px) {'.$customize.' {'.$padding_mobile_styles.'}}';
		}

		$custom_css .= $padding_custom_css;

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}
		
		$return_html = $css_output.$return_html;
		return thegem_templates_close_product_archive($this->get_name(), $this->shortcode_settings(), $return_html);
	}
	
	public function shortcode_settings() {
		
		return array(
			'name' => __('Archive Breadcrumbs', 'thegem'),
			'base' => 'thegem_te_product_archive_breadcrumbs',
			'icon' => 'thegem-icon-wpb-ui-element-product-breadcrumbs',
			'category' => __('Archive Product Builder', 'thegem'),
			'description' => __('Archive Breadcrumbs (Archive Product Builder)', 'thegem'),
			'params' => array_merge(
			
			/* General - Layout */
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Layout', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Alignment', 'thegem'),
						'param_name' => 'alignment',
						'value' => array_merge(array(
								__('Left', 'thegem') => 'left',
								__('Center', 'thegem') => 'center',
								__('Right', 'thegem') => 'right',
							)
						),
						'std' => 'left',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal Color', 'thegem'),
						'param_name' => 'color',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active Color', 'thegem'),
						'param_name' => 'color_active',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => 'General'
					),

					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Padding', 'thegem'),
						'param_name' => 'padding_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
						'group' => 'General',
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'padding_desktop_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'desktop_padding_top',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'desktop_padding_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'desktop_padding_left',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'desktop_padding_right',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'padding_tablet_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'tablet_padding_top',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'tablet_padding_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'tablet_padding_left',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'tablet_padding_right',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'padding_mobile_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'mobile_padding_top',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'mobile_padding_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'mobile_padding_left',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'mobile_padding_right',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),

					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Margin', 'thegem'),
						'param_name' => 'padding_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
						'group' => 'General',
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'padding_desktop_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'desktop_margin_top',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'desktop_margin_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'desktop_margin_left',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'desktop_margin_right',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'padding_tablet_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'tablet_margin_top',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'tablet_margin_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'tablet_margin_left',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'tablet_margin_right',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'padding_mobile_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'mobile_margin_top',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'mobile_margin_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'mobile_margin_left',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'mobile_margin_right',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),

				),

				/* Extra Options */
				thegem_set_elements_extra_options()
			),
		);
	}
}

$templates_elements['thegem_te_product_archive_breadcrumbs'] = new TheGem_Template_Element_Product_Archive_Breadcrumbs();
$templates_elements['thegem_te_product_archive_breadcrumbs']->init();
