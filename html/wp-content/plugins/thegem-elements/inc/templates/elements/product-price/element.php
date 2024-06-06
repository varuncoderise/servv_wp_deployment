<?php

class TheGem_Template_Element_Product_Price extends TheGem_Product_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_product_price';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'alignment' => 'left',
			'strikethrough' => '1',
			'text_style' => 'title-default',
			'text_font_weight' => '',
			'text_font_style' => '',
			'text_letter_spacing' => '',
			'text_transform' => '',
			'text_color' => '',
		),
			thegem_templates_extra_options_extract(),
            thegem_templates_design_options_extract('single-product')
        ), $atts, 'thegem_te_product_price');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-product-price', $params);
		
		// Init Price
		ob_start();
		$product = thegem_templates_init_product();
  
		if (empty($product) || empty($product->get_price_html())) {
			ob_end_clean();
			return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), '');
		}
		
		$textClass = thegem_te_product_text_styled($params);

		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-product-price <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
             <?=thegem_data_editor_attribute($uniqid.'-editor')?>>
            
            <div class="<?= esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?> <?=!$params['strikethrough'] ? 'not-strikethrough' : null?>">
				<span class="<?= $textClass ?>">
                    <?= $product->get_price_html(); ?>
                </span>
            </div>
		</div>

		<?php
		//Custom Styles
		$customize = '.thegem-te-product-price.'.$uniqid;
		
		if (!empty($params['alignment'])) {
			$custom_css .= $customize.' .price {justify-content: ' . $params['alignment'] . '; text-align: ' . $params['alignment'] . ';}';
		}
		if ($params['text_letter_spacing'] != '') {
			$custom_css .= $customize.' .price span {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
		}
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .price span {color: ' . $params['text_color'] . ';}';
			$custom_css .= $customize.' .price span del {color: ' . $params['text_color'] . ';}';
			$custom_css .= $customize.' .price span del:before {background-color: ' . $params['text_color'] . ';}';
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
			'group' => $group
		);
        $result[] = array(
            'type' => 'checkbox',
            'heading' => __('Strikethrough Price', 'thegem'),
            'param_name' => 'strikethrough',
            'value' => array(__('Yes', 'thegem') => '1'),
            'std' => '1',
            'edit_field_class' => 'vc_column vc_col-sm-12',
            'group' => 'General',
        );
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
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
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'text_font_weight',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Thin', 'thegem') => 'light',
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'text_letter_spacing',
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'text_color',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => 'General'
		);
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'description' => __('To style default price typography go to <a href="'.get_site_url().'/wp-admin/admin.php?page=thegem-theme-options#/woocommerce/product-styles" target="_blank">Theme Options → WooCommerce → Elements Styles → Product Price</a>.', 'thegem'),
			'group' => $group
		);
		
		return $result;
	}

	public function shortcode_settings() {

		return array(
			'name' => __('Product Price', 'thegem'),
			'base' => 'thegem_te_product_price',
			'icon' => 'thegem-icon-wpb-ui-element-product-price',
			'category' => __('Single Product Builder', 'thegem'),
			'description' => __('Product Price (Product Builder)', 'thegem'),
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

$templates_elements['thegem_te_product_price'] = new TheGem_Template_Element_Product_Price();
$templates_elements['thegem_te_product_price']->init();
