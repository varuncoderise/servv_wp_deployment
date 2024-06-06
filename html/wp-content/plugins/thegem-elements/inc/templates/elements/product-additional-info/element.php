<?php

class TheGem_Template_Element_Product_Additional_Info extends TheGem_Product_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_product_additional_info';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'title' => 'Additional Info',
			'title_alignment' => 'left',
			'bottom_spacing' => '32',
			'text_style' => 'title-default',
			'text_font_weight' => '',
			'text_font_style' => '',
			'text_letter_spacing' => '',
			'text_transform' => '',
			'title_color' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_design_options_extract('single-product')
        ), $atts, 'thegem_te_product_additional_info');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-product-additional-info', $params);
		
		// Init Additional_Info
		ob_start();
		$product = thegem_templates_init_product();
		
		if (empty($product)) {
			ob_end_clean();
			return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), '');
		}
        
        $isEmpty = !$product->has_attributes() && !$product->has_dimensions() && !$product->has_weight();
        
		if ($isEmpty) {
			ob_end_clean();
			return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), '');
		}
		
		$titleClass = thegem_te_product_text_styled($params);
		
		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-product-additional-info <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
			<?= thegem_data_editor_attribute($uniqid . '-editor') ?>>
            
            <div class="product-additional-info">
                <?php if (!empty($params['title'])): ?>
                    <div class="product-additional-info-title">
                       <span <?php if (!empty($titleClass)): ?>class="<?= $titleClass ?>"<?php endif; ?>>
                            <?= $params['title'] ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <div class="product-additional-info-table">
	                <?= woocommerce_product_additional_information_tab() ?>
                </div>
            </div>
		</div>

		<?php
		//Custom Styles
		$customize = '.thegem-te-product-additional-info.'.$uniqid;
		
		// Layout Styles
		if (!empty($params['title_alignment'])) {
			$custom_css .= $customize.' .product-additional-info-title {justify-content: ' . $params['title_alignment'] . '; text-align: ' . $params['title_alignment'] . ';}';
		}
		if ($params['text_letter_spacing'] != '') {
			$custom_css .= $customize.' .product-additional-info-title span {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
		}
		if (!empty($params['title_color'])) {
			$custom_css .= $customize.' .product-additional-info-title span {color: ' . $params['title_color'] . ';}';
		}
		if (!empty($params['bottom_spacing'])){
			$custom_css .= $customize.' .product-additional-info-title {margin-bottom:'.$params['bottom_spacing'].'px;}';
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
	
	public function set_layout_params() {
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('General', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		$result[] = array(
			"type" => "textfield",
			'heading' => __('Title', 'thegem'),
			'param_name' => 'title',
			'std' => 'Additional Info',
			"edit_field_class" => "vc_column vc_col-sm-12",
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Title Alignment', 'thegem'),
			'param_name' => 'title_alignment',
			'value' => array_merge(array(
					__('Left', 'thegem') => 'left',
					__('Center', 'thegem') => 'center',
					__('Right', 'thegem') => 'right',
				)
			),
			'std' => 'left',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			"type" => "textfield",
			'heading' => __('Bottom Spacing', 'thegem'),
			'param_name' => 'bottom_spacing',
			'std' => '32',
			"edit_field_class" => "vc_column vc_col-sm-6",
			'group' => $group
		);;
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Title Style', 'thegem'),
			'param_name' => 'text_style',
			'value' => array(
				__('Default', 'thegem') => 'title-default',
				__('Title H1', 'thegem') => 'title-h1',
				__('Title H2', 'thegem') => 'title-h2',
				__('Title H3', 'thegem') => 'title-h3',
				__('Title H4', 'thegem') => 'title-h4',
				__('Title H5', 'thegem') => 'title-h5',
				__('Title H6', 'thegem') => 'title-h6',
				__('Title xLarge', 'thegem') => 'title-xlarge',
				__('Styled Subtitle', 'thegem') => 'styled-subtitle',
				__('Main Menu', 'thegem') => 'title-main-menu',
				__('Body', 'thegem') => 'title-text-body',
				__('Tiny Body', 'thegem') => 'title-text-body-tiny',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Title font weight', 'thegem'),
			'param_name' => 'text_font_weight',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Thin', 'thegem') => 'light',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'text_letter_spacing',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Title text transform', 'thegem'),
			'param_name' => 'text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'transform-none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Title Color', 'thegem'),
			'param_name' => 'title_color',
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		
		return $result;
	}

	public function shortcode_settings() {

		return array(
			'name' => __('Product Additional Info', 'thegem'),
			'base' => 'thegem_te_product_additional_info',
			'icon' => 'thegem-icon-wpb-ui-element-product-additional-info',
			'category' => __('Single Product Builder', 'thegem'),
			'description' => __('Product Additional Info (Product Builder)', 'thegem'),
			'params' => array_merge(
			
			    /* General - Layout */
				$this->set_layout_params(),
				
				/* Extra Options */
				thegem_set_elements_extra_options(),
				
				/* Flex Options */
				thegem_set_elements_design_options('single-product')
			),
		);
	}
}

$templates_elements['thegem_te_product_additional_info'] = new TheGem_Template_Element_Product_Additional_Info();
$templates_elements['thegem_te_product_additional_info']->init();
