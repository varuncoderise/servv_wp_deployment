<?php

class TheGem_Template_Element_Product_Content extends TheGem_Product_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_product_content';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_design_options_extract('single-product')
        ), $atts, 'thegem_te_product_content');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-product-content', $params);
		
		// Init Content
		ob_start();
		$product = thegem_templates_init_product();
  
		if (empty($product)) {
			ob_end_clean();
			return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), '');
		}
		
		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-product-content <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
			<?= thegem_data_editor_attribute($uniqid . '-editor') ?>>
			
			<?= thegem_woocommerce_single_product_page_content() ?>
		</div>

		<?php
		//Custom Styles
		$customize = '.thegem-te-product-content.'.$uniqid;

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		$custom_css .= get_post_meta(get_the_ID(), '_wpb_shortcodes_custom_css', true) . get_post_meta(get_the_ID(), '_wpb_post_custom_css', true);

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
			'name' => __('Product Content', 'thegem'),
			'base' => 'thegem_te_product_content',
			'icon' => 'thegem-icon-wpb-ui-element-product-content',
			'category' => __('Single Product Builder', 'thegem'),
			'description' => __('Product Content (Product Builder)', 'thegem'),
			'params' => array_merge(
				
				/* Extra Options */
				thegem_set_elements_extra_options(),
				
				/* Flex Options */
				thegem_set_elements_design_options('single-product')
			),
		);
	}
}

$templates_elements['thegem_te_product_content'] = new TheGem_Template_Element_Product_Content();
$templates_elements['thegem_te_product_content']->init();
