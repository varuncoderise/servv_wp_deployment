<?php

class TheGem_Template_Element_Product_Meta_Value extends TheGem_Product_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_product_meta_value';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'meta_key' => '',
			'alignment' => 'left',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_design_options_extract('single-product')
        ), $atts, 'thegem_te_product_meta_value');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-product-meta-value', $params);
		
		// Init Extra Description
		ob_start();
		$product = thegem_templates_init_product();
		
		if (empty($product)) {
			ob_end_clean();
			return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), '');
		}
		
		$meta_data = '';
        if (!empty($params['meta_key'])) {
	        $meta_data = get_post_meta($product->get_id(), $params['meta_key'], true);
        }
        
		if (empty($meta_data)) {
			ob_end_clean();
			return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), '');
		}
  
		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-product-meta-value <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
			<?= thegem_data_editor_attribute($uniqid . '-editor') ?>>
			
            <?php if (!is_array($meta_data)): ?>
	            <?= do_shortcode($meta_data) ?>
            <?php endif;?>
		</div>

		<?php
		//Custom Styles
		$customize = '.thegem-te-product-meta-value.'.$uniqid;
		
		if (!empty($params['alignment'])) {
			$custom_css .= $customize.' {justify-content: ' . $params['alignment'] . '; text-align: ' . $params['alignment'] . ';}';
		}

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}

		$return_html = $css_output.$return_html;
		return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), $return_html);
	}

	public function shortcode_settings() {

		return array(
			'name' => __('Product Meta Value', 'thegem'),
			'base' => 'thegem_te_product_meta_value',
			'icon' => 'thegem-icon-wpb-ui-element-product-meta-value',
			'category' => __('Single Product Builder', 'thegem'),
			'description' => __('Product Meta Value (Product Builder)', 'thegem'),
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
						"type" => "textfield",
						'heading' => __('Meta Key', 'thegem'),
						'param_name' => 'meta_key',
						"edit_field_class" => "vc_column vc_col-sm-12",
						'group' => 'General'
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
					)
				),
    
				/* Extra Options */
				thegem_set_elements_extra_options(),
				
				/* Flex Options */
				thegem_set_elements_design_options('single-product')
			),
		);
	}
}

$templates_elements['thegem_te_product_meta_value'] = new TheGem_Template_Element_Product_Meta_Value();
$templates_elements['thegem_te_product_meta_value']->init();
