<?php

class TheGem_Template_Element_Cart_Checkout_Notices extends TheGem_Cart_Template_Element {

	public function __construct() {
	}

	public function shortcode_activate($shortcodes) {
		global $pagenow;
		if((is_admin() && in_array($pagenow, array('post-new.php', 'post.php', 'admin-ajax.php'))) || (!is_admin() && in_array($pagenow, array('index.php')))) {
			$activate = 0;
			if($pagenow === 'post-new.php' && !empty($_REQUEST['post_type']) && $_REQUEST['post_type'] === 'thegem_templates') {
				$activate = true;
			}
			if($pagenow === 'post.php' && !empty($_REQUEST['post']) && get_post_type($_REQUEST['post']) === 'thegem_templates' && (thegem_get_template_type($_REQUEST['post']) == 'cart')) {
				$activate = true;
			}
			if($pagenow === 'post.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && (thegem_get_template_type($_REQUEST['post_id']) == 'cart')) {
				$activate = true;
			}
			if($pagenow === 'admin-ajax.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && (thegem_get_template_type($_REQUEST['post_id']) == 'cart')) {
				$activate = true;
			}
			if($pagenow === 'index.php' && !empty($_REQUEST['vc_post_id']) && get_post_type($_REQUEST['vc_post_id']) === 'thegem_templates' && (thegem_get_template_type($_REQUEST['vc_post_id']) == 'cart')) {
				$activate = true;
			}
			if($activate) {
				$shortcodes[$this->get_name()] = $this->shortcode_settings();
			}
		}
		return $shortcodes;
	}

	public function get_name() {
		return 'thegem_te_cart_checkout_notices';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'update_background_color' => '',
			'update_icon_color' => '',
			'update_text_color' => '',
			'update_link_color' => '',
			'update_link_color_hover' => '',
			'error_background_color' => '',
			'error_icon_color' => '',
			'error_text_color' => '',
			'error_link_color' => '',
			'error_link_color_hover' => '',
        ),
            thegem_templates_extra_options_extract()
		), $atts, 'thegem_te_cart_checkout_notices');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		ob_start();
  
		if (!is_cart() && !is_checkout() && !is_singular('blocks')) {
			ob_end_clean();
			return thegem_templates_close_cart($this->get_name(), $this->shortcode_settings(), '');
		}
  
		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-cart-checkout-notices <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
			<?= thegem_data_editor_attribute($uniqid . '-editor') ?>>

            <div class="cart-notices">
                <?= woocommerce_output_all_notices(); ?>
            </div>
        </div>
        
		<?php
		//Custom Styles
		$customize = '.thegem-te-cart-checkout-notices.'.$uniqid;
		$custom_css = '';
		
		// Content Styles
		if (!empty($params['update_background_color'])) {
			$custom_css .= $customize.' .woocommerce-message {background-color: ' . $params['update_background_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-info {background-color: ' . $params['update_background_color'] . ' !important;}';
		}
		if (!empty($params['update_icon_color'])) {
			$custom_css .= $customize.' .woocommerce-message:before {color: ' . $params['update_icon_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-info:before {color: ' . $params['update_icon_color'] . ' !important;}';
		}
		if (!empty($params['update_text_color'])) {
			$custom_css .= $customize.' .woocommerce-message {color: ' . $params['update_text_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-info {color: ' . $params['update_text_color'] . ' !important;}';
		}
		if (!empty($params['update_link_color'])) {
			$custom_css .= $customize.' .woocommerce-message a {color: ' . $params['update_link_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-info a {color: ' . $params['update_link_color'] . ' !important;}';
		}
		if (!empty($params['update_link_color_hover'])) {
			$custom_css .= $customize.' .woocommerce-message a:hover {color: ' . $params['update_link_color_hover'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-info a:hover {color: ' . $params['update_link_color_hover'] . ' !important;}';
		}
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
			'heading' => __('Update Notices', 'thegem'),
			'param_name' => 'delimiter_heading_description',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color', 'thegem'),
			'param_name' => 'update_background_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Icon Color', 'thegem'),
			'param_name' => 'update_icon_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Notice Text Color', 'thegem'),
			'param_name' => 'update_text_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Link Normal Color', 'thegem'),
			'param_name' => 'update_link_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Link Hover Color', 'thegem'),
			'param_name' => 'update_link_color_hover',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Error Notices', 'thegem'),
			'param_name' => 'delimiter_heading_description',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
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
			'name' => __('WooCommerce Notices', 'thegem'),
			'base' => 'thegem_te_cart_checkout_notices',
			'icon' => 'thegem-icon-wpb-ui-element-cart-checkout-notices',
			'category' => thegem_is_template_post('cart') ? __('Cart Builder', 'thegem') : __('Checkout Builder', 'thegem'),
			'description' => __('WooCommerce Notices', 'thegem'),
			'params' => array_merge(
			
			    /* General - Content */
				$this->set_content_params(),
    
				/* Extra Options */
				thegem_set_elements_extra_options()
			),
		);
	}
}

$templates_elements['thegem_te_cart_checkout_notices'] = new TheGem_Template_Element_Cart_Checkout_Notices();
$templates_elements['thegem_te_cart_checkout_notices']->init();
