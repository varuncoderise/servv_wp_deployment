<?php

class TheGem_Template_Element_Checkout_Errors extends TheGem_Checkout_Template_Element{

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_checkout_errors';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'error_background_color' => '',
			'error_icon_color' => '',
			'error_text_color' => '',
			'error_link_color' => '',
			'error_link_color_hover' => '',
		),
			thegem_templates_extra_options_extract()
		), $atts, 'thegem_te_checkout_errors');
  
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		ob_start();

		if (!is_checkout() && !is_singular('blocks')) {
			ob_end_clean();
			return '<div class="thegem-te-checkout-errors template-checkout-empty-output default-background">'.__('Checkout Errors', 'thegem').'</div>';
		}

		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
			class="thegem-te-checkout-errors <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
			<?= thegem_data_editor_attribute($uniqid . '-editor') ?>>
		</div>
  
		<?php
		//Custom Styles
		$customize = '.thegem-te-checkout-errors.'.$uniqid;
		$custom_css = '';
		
		// Content Styles
		if (!empty($params['error_background_color'])) {
			$custom_css .= $customize.' ul.woocommerce-error {background-color: ' . $params['error_background_color'] . ' !important;}';
		}
		if (!empty($params['error_icon_color'])) {
			$custom_css .= $customize.' ul.woocommerce-error:before {color: ' . $params['error_icon_color'] . ' !important;}';
		}
		if (!empty($params['error_text_color'])) {
			$custom_css .= $customize.' ul.woocommerce-error li {color: ' . $params['error_text_color'] . ' !important;}';
		}
		if (!empty($params['error_link_color'])) {
			$custom_css .= $customize.' ul.woocommerce-error li a {color: ' . $params['error_link_color'] . ' !important;}';
		}
		if (!empty($params['error_link_color_hover'])) {
			$custom_css .= $customize.' ul.woocommerce-error li a:hover {color: ' . $params['error_link_color_hover'] . ' !important;}';
		}

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}

		$return_html = $css_output.$return_html;
		return $return_html;
	}
	
	public function set_content_params() {
		$group = __('General', 'thegem');
  
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Error Notices', 'thegem'),
			'param_name' => 'delimiter_heading_description',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color', 'thegem'),
			'param_name' => 'error_background_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Icon Color', 'thegem'),
			'param_name' => 'error_icon_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Notice Text Color', 'thegem'),
			'param_name' => 'error_text_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Link Normal Color', 'thegem'),
			'param_name' => 'error_link_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Link Hover Color', 'thegem'),
			'param_name' => 'error_link_color_hover',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		return $result;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Checkout Errors', 'thegem'),
			'base' => 'thegem_te_checkout_errors',
			'icon' => 'thegem-icon-wpb-ui-element-checkout-errors',
			'category' => __('Checkout Builder', 'thegem'),
			'description' => __('Checkout Errors (Checkout Builder)', 'thegem'),
			'params' => array_merge(
			
			    /* General - Content */
				$this->set_content_params(),
				
				/* Extra Options */
				thegem_set_elements_extra_options()
			),
		);
	}
}

$templates_elements['thegem_te_checkout_errors'] = new TheGem_Template_Element_Checkout_Errors();
$templates_elements['thegem_te_checkout_errors']->init();
