<?php

class TheGem_Template_Element_Checkout_Thanks_Order_Overview  extends TheGem_Checkout_Thanks_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_checkout_thanks_order_overview';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
            'list_alignment' => 'left',
            'list_color' => '',
            'list_color_active' => '',
            'bullets_color' => '',
        ),
            thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_checkout_thanks_order_overview');
		
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
  
		$params['element_class'] .= !empty($params['list_alignment']) ? 'list-alignment--'.$params['list_alignment'] : null;
		$params['element_class'] = implode(' ', array($params['element_class'], thegem_templates_responsive_options_output($params)));
  
		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-checkout-thanks-order-overview <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
            
            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details body-small">
                <li class="woocommerce-order-overview__order order">
			        <?php esc_html_e('Order number:', 'woocommerce'); ?>
                    <strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
                </li>

                <li class="woocommerce-order-overview__date date">
			        <?php esc_html_e('Date:', 'woocommerce'); ?>
                    <strong><?php echo wc_format_datetime($order->get_date_created()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
                </li>
		
		        <?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email()) : ?>
                    <li class="woocommerce-order-overview__email email">
				        <?php esc_html_e('Email:', 'woocommerce'); ?>
                        <strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
                    </li>
		        <?php endif; ?>

                <li class="woocommerce-order-overview__total total">
			        <?php esc_html_e('Total:', 'woocommerce'); ?>
                    <strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
                </li>
		
		        <?php if ($order->get_payment_method_title()) : ?>
                    <li class="woocommerce-order-overview__payment-method method">
				        <?php esc_html_e('Payment method:', 'woocommerce'); ?>
                        <strong><?php echo wp_kses_post($order->get_payment_method_title()); ?></strong>
                    </li>
		        <?php endif; ?>
            </ul>
        </div>
        
		<?php
		//Custom Styles
		$customize = '.thegem-te-checkout-thanks-order-overview.'.$uniqid;
		$custom_css = '';
		
		// Content Styles
		if (!empty($params['list_color'])) {
			$custom_css .= $customize.' .woocommerce-order-overview li {color: ' . $params['list_color'] . ' !important;}';
		}
		if (!empty($params['list_color_active'])) {
			$custom_css .= $customize.' .woocommerce-order-overview li strong {color: ' . $params['list_color_active'] . ' !important;}';
		}
		if (!empty($params['bullets_color'])) {
			$custom_css .= $customize.' .woocommerce-order-overview li:before {color: ' . $params['bullets_color'] . ' !important;}';
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
			'heading' => __('Content', 'thegem'),
			'param_name' => 'delimiter_heading_description',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Alignment', 'thegem'),
			'param_name' => 'list_alignment',
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
			'type' => 'colorpicker',
			'heading' => __('List Color', 'thegem'),
			'param_name' => 'list_color',
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('List Color Active', 'thegem'),
			'param_name' => 'list_color_active',
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Bullets Color', 'thegem'),
			'param_name' => 'bullets_color',
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		
		return $result;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Order Overview', 'thegem'),
			'base' => 'thegem_te_checkout_thanks_order_overview',
			'icon' => 'thegem-icon-wpb-ui-element-checkout-order',
			'category' => __('Purchase Summary Builder', 'thegem'),
			'description' => __('Purchase Summary Order Overview', 'thegem'),
			'params' => array_merge(
			
			    /* General - Content */
				$this->set_content_params(),
    
				/* Extra Options */
				thegem_set_elements_extra_options(),
                
                /* Responsive Options */
				thegem_set_elements_responsive_options()
			),
		);
	}
}

$templates_elements['thegem_te_checkout_thanks_order_overview'] = new TheGem_Template_Element_Checkout_Thanks_Order_Overview();
$templates_elements['thegem_te_checkout_thanks_order_overview']->init();
