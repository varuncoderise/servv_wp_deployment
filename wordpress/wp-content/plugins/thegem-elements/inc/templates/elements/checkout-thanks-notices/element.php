<?php

class TheGem_Template_Element_Checkout_Thanks_Notices  extends TheGem_Checkout_Thanks_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_checkout_thanks_notices';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
            'success_background_color' => '',
            'success_icon_color' => '',
            'success_text_color' => '',
            'success_link_color' => '',
            'success_link_color_hover' => '',
			'error_background_color' => '',
			'error_icon_color' => '',
			'error_text_color' => '',
			'error_link_color' => '',
			'error_link_color_hover' => '',
        ),
            thegem_templates_extra_options_extract()
		), $atts, 'thegem_te_checkout_thanks_notices');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		ob_start();
		
		global $wp;
		$order_id = $wp->query_vars['order-received'];
		$order = new WC_Order( $order_id );
		
		if (!is_checkout() || !$order) {
			ob_end_clean();
			return thegem_templates_close_checkout_thanks($this->get_name(), $this->shortcode_settings(), '');
		}
  
		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-checkout-thanks-notices <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
            
            <?php if ($order->has_status('failed')): ?>
				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
					<?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?>
				</p>

				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
					<a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay"><?php esc_html_e('Pay', 'woocommerce'); ?></a>
					<?php if (is_user_logged_in()) : ?>
						<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button pay"><?php esc_html_e('My account', 'woocommerce'); ?></a>
					<?php endif; ?>
				</p>
			<?php else: ?>
				<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
					<?php echo apply_filters('woocommerce_thankyou_order_received_text', esc_html__('Thank you. Your order has been received.', 'woocommerce'), null); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</p>
			<?php endif; ?>
        </div>
        
		<?php
		//Custom Styles
		$customize = '.thegem-te-checkout-thanks-notices.'.$uniqid;
		$custom_css = '';
		
		// Content Styles
		if (!empty($params['success_background_color'])) {
			$custom_css .= $customize.' .woocommerce-notice--success {background-color: ' . $params['success_background_color'] . ' !important;}';
		}
		if (!empty($params['success_icon_color'])) {
			$custom_css .= $customize.' .woocommerce-notice--success:before {color: ' . $params['success_icon_color'] . ' !important;}';
		}
		if (!empty($params['success_text_color'])) {
			$custom_css .= $customize.' .woocommerce-notice--success {color: ' . $params['success_text_color'] . ' !important;}';
		}
		if (!empty($params['success_link_color'])) {
			$custom_css .= $customize.' .woocommerce-notice--success a {color: ' . $params['success_link_color'] . ' !important;}';
		}
		if (!empty($params['success_link_color_hover'])) {
			$custom_css .= $customize.' .woocommerce-notice--success a:hover {color: ' . $params['success_link_color_hover'] . ' !important;}';
		}
		if (!empty($params['error_background_color'])) {
			$custom_css .= $customize.' .woocommerce-notice--error {background-color: ' . $params['error_background_color'] . ' !important;}';
		}
		if (!empty($params['error_icon_color'])) {
			$custom_css .= $customize.' .woocommerce-notice--error:before {color: ' . $params['error_icon_color'] . ' !important;}';
		}
		if (!empty($params['error_text_color'])) {
			$custom_css .= $customize.' .woocommerce-notice--error {color: ' . $params['error_text_color'] . ' !important;}';
		}
		if (!empty($params['error_link_color'])) {
			$custom_css .= $customize.' .woocommerce-notice--error a {color: ' . $params['error_link_color'] . ' !important;}';
		}
		if (!empty($params['error_link_color_hover'])) {
			$custom_css .= $customize.' .woocommerce-notice--error a:hover {color: ' . $params['error_link_color_hover'] . ' !important;}';
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
			'heading' => __('Success Notices', 'thegem'),
			'param_name' => 'delimiter_heading_description',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color', 'thegem'),
			'param_name' => 'success_background_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Icon Color', 'thegem'),
			'param_name' => 'success_icon_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Notice Text Color', 'thegem'),
			'param_name' => 'success_text_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Link Normal Color', 'thegem'),
			'param_name' => 'success_link_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Link Hover Color', 'thegem'),
			'param_name' => 'success_link_color_hover',
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
			'base' => 'thegem_te_checkout_thanks_notices',
			'icon' => 'thegem-icon-wpb-ui-element-cart-checkout-notices',
			'category' => __('Purchase Summary Builder', 'thegem'),
			'description' => __('Purchase Summary Notices', 'thegem'),
			'params' => array_merge(
			
			    /* General - Content */
				$this->set_content_params(),
    
				/* Extra Options */
				thegem_set_elements_extra_options()
			),
		);
	}
}

$templates_elements['thegem_te_checkout_thanks_notices'] = new TheGem_Template_Element_Checkout_Thanks_Notices();
$templates_elements['thegem_te_checkout_thanks_notices']->init();
