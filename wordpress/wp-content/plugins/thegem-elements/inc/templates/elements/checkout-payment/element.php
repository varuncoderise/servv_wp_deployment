<?php

class TheGem_Template_Element_Checkout_Payment extends TheGem_Checkout_Template_Element{

	public function __construct(){
	}

	public function get_name(){
		return 'thegem_te_checkout_payment';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'dividers' => '1',
			'dividers_color' => '',
			'text_color' => '',
			'links_color' => '',
			'links_color_hover' => '',
			'payment_box_text_color' => '',
			'payment_box_background' => '',
			'payment_box_paddings' => '1',
			'loading_overlay_color' => '',
			'label_text_color' => '',
			'input_marker_color' => '',
			'input_background_color' => '',
			'input_border_color' => '',
			'input_checkbox_border_radius' => '',
			'order_btn' => '1',
			'order_btn_alignment' => 'fullwidth',
			'order_btn_border_width' => '',
			'order_btn_border_radius' => '',
			'order_btn_text_color' => '',
			'order_btn_text_color_hover' => '',
			'order_btn_background_color' => '',
			'order_btn_background_color_hover' => '',
			'order_btn_border_color' => '',
			'order_btn_border_color_hover' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_checkout_payment');


		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-') . rand(1, 9999);
		ob_start();

		$checkout = WC()->checkout();
		$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
		$order_button_text = apply_filters('woocommerce_order_button_text', __('Place order', 'woocommerce'));

		$params['element_class'] .= empty($params['order_btn']) ? ' place-order-btn--hide' : null;
		$params['element_class'] .= empty($params['payment_box_paddings']) ? ' payment-box-paddings--hide' : null;
		$params['element_class'] .= ' place-order-btn--'.$params['order_btn_alignment'];
		$params['element_class'] = implode(' ', array($params['element_class'], thegem_templates_responsive_options_output($params)));

		do_action( 'woocommerce_review_order_before_payment' );

		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?= esc_attr($params['element_id']); ?>"<?php endif; ?>
             class="thegem-te-checkout-payment <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">

            <div id="payment" class="woocommerce-checkout-payment">
				<?php if (WC()->cart->needs_payment()) : ?>
                    <ul class="wc_payment_methods payment_methods methods">
						<?php
						if (!empty($available_gateways)) {
							foreach ($available_gateways as $gateway) {
								wc_get_template('checkout/payment-method.php', array('gateway' => $gateway));
							}
						} else {
							echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__('Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce') : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce')) . '</li>'; // @codingStandardsIgnoreLine
						}
						?>
                    </ul>
				<?php endif; ?>
                <div class="form-row place-order">
                    <noscript>
						<?php
						/* translators: $1 and $2 opening and closing emphasis tags respectively */
						printf(esc_html__('Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce'), '<em>', '</em>');
						?>
                        <br/>
                        <button type="submit" class="button alt" name="woocommerce_checkout_update_totals"
                                value="<?php esc_attr_e('Update totals', 'woocommerce'); ?>"><?php esc_html_e('Update totals', 'woocommerce'); ?></button>
                    </noscript>

					<?php wc_get_template('checkout/terms.php'); ?>

					<?php do_action('woocommerce_review_order_before_submit'); ?>

		            <?php if (!empty($params['order_btn'])): ?>
                        <div class="checkout-navigation-buttons">
                            <?php
                            thegem_button(array(
                                'tag' => 'button', 'text' => esc_attr($order_button_text), 'style' => 'flat', 'size' => 'small', 'position' => 'fullwidth', 'extra_class' => 'checkout-place-order', 'attributes' => array(
                                    'id' => 'place_order', 'name' => 'woocommerce_checkout_place_order', 'value' => esc_attr($order_button_text), 'type' => 'submit', 'data-value' => esc_attr($order_button_text), 'class' => 'gem-button-tablet-size-small'
                                )
                            ), true);
                            ?>
                        </div>
                    <?php endif; ?>

					<?php do_action('woocommerce_review_order_after_submit'); ?>

					<?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
                </div>
            </div>

			<?php if ( is_ajax() || !defined('WC_GERMANIZED_VERSION') ) { do_action( 'woocommerce_review_order_after_payment' ); } ?>
        </div>

		<?php

		//Custom Styles
		$customize = '.thegem-te-checkout-payment.'.$uniqid;
		$custom_css = '';

		// Content Styles
		if (empty($params['dividers'])) {
			$custom_css .= $customize.' .woocommerce-checkout-payment .payment_methods li {border: 0 !important;}';
		}
		if (!empty($params['dividers_color'])) {
			$custom_css .= $customize.' .woocommerce-checkout-payment .payment_methods li {border-color:'.$params['dividers_color'].' !important;}';
		}
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .woocommerce-checkout-payment .place-order {color: ' . $params['text_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-checkout-payment .woocommerce-terms-and-conditions-checkbox-text {color: ' . $params['text_color'] . ' !important;}';
		}
		if (!empty($params['links_color'])) {
			$custom_css .= $customize.' .woocommerce-checkout-payment a {color: ' . $params['links_color'] . ' !important;}';
		}
		if (!empty($params['links_color_hover'])) {
			$custom_css .= $customize.' .woocommerce-checkout-payment .place-order a:hover {color: ' . $params['links_color_hover'] . ' !important;}';
		}
		if (!empty($params['payment_box_text_color'])) {
			$custom_css .= $customize.' .woocommerce-checkout-payment .payment_methods li .payment_box {color: ' . $params['payment_box_text_color'] . ' !important;}';
		}
		if (!empty($params['payment_box_background'])) {
			$custom_css .= $customize.' .woocommerce-checkout-payment .payment_methods li .payment_box {background-color: ' . $params['payment_box_background'] . ' !important;}';
        }

		// Form Styles
		if (!empty($params['label_text_color'])) {
			$custom_css .= $customize.' .woocommerce-checkout-payment .payment_methods li label {color: ' . $params['label_text_color'] . ' !important;}';
		}
		if (!empty($params['input_marker_color'])) {
			$custom_css .= $customize.' .woocommerce-checkout-payment .radio-sign:before {background-color: ' . $params['input_marker_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-checkout-payment .checkbox-sign:before {color: ' . $params['input_marker_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-checkout-payment span.required {color: ' . $params['input_marker_color'] . ' !important;}';
		}
		if (!empty($params['input_background_color'])) {
			$custom_css .= $customize.' .woocommerce-checkout-payment .radio-sign {background-color: ' . $params['input_background_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-checkout-payment .checkbox-sign {background-color: ' . $params['input_background_color'] . ' !important;}';
		}
		if (!empty($params['input_border_color'])) {
			$custom_css .= $customize.' .woocommerce-checkout-payment .radio-sign {border-color: ' . $params['input_border_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-checkout-payment .checkbox-sign {border-color: ' . $params['input_border_color'] . ' !important;}';
		}
		if (!empty($params['input_checkbox_border_radius']) || $params['input_checkbox_border_radius'] == 0) {
			$custom_css .= $customize.' .woocommerce-checkout-payment .checkbox-sign {border-radius: ' . $params['input_checkbox_border_radius'] . 'px !important;}';
		}

		// Order Button Styles
		if (!empty($params['order_btn_border_width'])) {
			$add_to_cart_btn_line_height = intval(40 - $params['order_btn_border_width']*2);
			$custom_css .= $customize.' .checkout-navigation-buttons .checkout-place-order .gem-button {border-width:'.$params['order_btn_border_width'].'px !important;}';
			$custom_css .= $customize.' .checkout-navigation-buttons .checkout-place-order .gem-button {line-height: '.$add_to_cart_btn_line_height.'px !important;}';
		}
		if (!empty($params['order_btn_border_radius']) || $params['order_btn_border_radius'] == 0) {
			$custom_css .= $customize.' .checkout-navigation-buttons .checkout-place-order .gem-button {border-radius:'.$params['order_btn_border_radius'].'px !important;}';
		}
		if (!empty($params['order_btn_text_color'])) {
			$custom_css .= $customize.' .checkout-navigation-buttons .checkout-place-order .gem-button {color:'.$params['order_btn_text_color'].'!important;}';
		}
		if (!empty($params['order_btn_text_color_hover'])) {
			$custom_css .= $customize.' .checkout-navigation-buttons .checkout-place-order .gem-button:hover {color:'.$params['order_btn_text_color_hover'].'!important;}';
		}
		if (!empty($params['order_btn_background_color'])) {
			$custom_css .= $customize.' .checkout-navigation-buttons .checkout-place-order .gem-button {background-color:'.$params['order_btn_background_color'].'!important;}';
		}
		if (!empty($params['order_btn_background_color_hover'])) {
			$custom_css .= $customize.' .checkout-navigation-buttons .checkout-place-order .gem-button:hover {background-color:'.$params['order_btn_background_color_hover'].'!important;}';
		}
		if (!empty($params['order_btn_border_color'])) {
			$custom_css .= $customize.' .checkout-navigation-buttons .checkout-place-order .gem-button {border-color:'.$params['order_btn_border_color'].'!important;}';
		}
		if (!empty($params['order_btn_border_color_hover'])) {
			$custom_css .= $customize.' .checkout-navigation-buttons .checkout-place-order .gem-button:hover {border-color:'.$params['order_btn_border_color_hover'].'!important;}';
		}
		if (!empty($params['loading_overlay_color'])) {
			$custom_css .= $customize.' .blockOverlay {background-color: ' . $params['loading_overlay_color'] . ' !important;}';
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
			'type' => 'checkbox',
			'heading' => __('Dividers', 'thegem'),
			'param_name' => 'dividers',
			'value' => array(__('Show', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Dividers Color', 'thegem'),
			'param_name' => 'dividers_color',
			'dependency' => array(
				'element' => 'dividers',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'text_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Links Color', 'thegem'),
			'param_name' => 'links_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Links Hover Color', 'thegem'),
			'param_name' => 'links_color_hover',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Payment Box Text Color', 'thegem'),
			'param_name' => 'payment_box_text_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Payment Box Background', 'thegem'),
			'param_name' => 'payment_box_background',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Loading Overlay Color', 'thegem'),
			'param_name' => 'loading_overlay_color',
			'group' => $group
		);
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Payment Box Paddings', 'thegem'),
			'param_name' => 'payment_box_paddings',
			'value' => array(__('Enable', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		return $result;
	}

	public function set_form_params() {
		$group = __('General', 'thegem');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Checkbox & Radio Button', 'thegem'),
			'param_name' => 'delimiter_heading_description',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Label Text Color', 'thegem'),
			'param_name' => 'label_text_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Marker Color', 'thegem'),
			'param_name' => 'input_marker_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Input Background Color', 'thegem'),
			'param_name' => 'input_background_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Input Border Color', 'thegem'),
			'param_name' => 'input_border_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Checkbox Border Radius', 'thegem'),
			'param_name' => 'input_checkbox_border_radius',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		return $result;
	}

	public function set_order_btn_params() {
		$result = array();
		$group = __('General', 'thegem');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('"Place Order" Button', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Button', 'thegem'),
			'param_name' => 'order_btn',
			'value' => array(__('Show', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Button Alignment', 'thegem'),
			'param_name' => 'order_btn_alignment',
			'value' => array_merge(array(
					__('Left', 'thegem') => 'left',
					__('Center', 'thegem') => 'center',
					__('Right', 'thegem') => 'right',
					__('Fullwidth', 'thegem') => 'fullwidth',
				)
			),
			'std' => 'fullwidth',
			'dependency' => array(
				'element' => 'order_btn',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Border Width', 'thegem'),
			'param_name' => 'order_btn_border_width',
			'value' => array_merge(array(
					__('Default', 'thegem') => '',
					__('1', 'thegem') => '1',
					__('2', 'thegem') => '2',
					__('3', 'thegem') => '3',
					__('4', 'thegem') => '4',
					__('5', 'thegem') => '5',
					__('6', 'thegem') => '6',
				)
			),
			'std' => '',
			'dependency' => array(
				'element' => 'order_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group,
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Radius', 'thegem'),
			'param_name' => 'order_btn_border_radius',
			'value' => '',
			'dependency' => array(
				'element' => 'order_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'order_btn_text_color',
			'std' => '',
			'dependency' => array(
				'element' => 'order_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color on Hover', 'thegem'),
			'param_name' => 'order_btn_text_color_hover',
			'std' => '',
			'dependency' => array(
				'element' => 'order_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color', 'thegem'),
			'param_name' => 'order_btn_background_color',
			'std' => '',
			'dependency' => array(
				'element' => 'order_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color on Hover', 'thegem'),
			'param_name' => 'order_btn_background_color_hover',
			'std' => '',
			'dependency' => array(
				'element' => 'order_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color', 'thegem'),
			'param_name' => 'order_btn_border_color',
			'std' => '',
			'dependency' => array(
				'element' => 'order_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color on Hover', 'thegem'),
			'param_name' => 'order_btn_border_color_hover',
			'std' => '',
			'dependency' => array(
				'element' => 'order_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		return $result;
	}

	public function shortcode_settings(){
		return array(
			'name' => __('Payment Methods', 'thegem'),
            'base' => 'thegem_te_checkout_payment',
            'icon' => 'thegem-icon-wpb-ui-element-checkout-payment',
            'category' => __('Checkout Builder', 'thegem'),
            'description' => __('Payment Methods (Checkout Builder)', 'thegem'),
			'params' => array_merge(

			    /* General - Content */
				$this->set_content_params(),

				/* General - Form */
				$this->set_form_params(),

				/* General - Order Button */
				$this->set_order_btn_params(),

				/* Extra Options */
				thegem_set_elements_extra_options(),

				/* Responsive Options */
				thegem_set_elements_responsive_options()
			),
		);
	}
}

$templates_elements['thegem_te_checkout_payment'] = new TheGem_Template_Element_Checkout_Payment();
$templates_elements['thegem_te_checkout_payment']->init();
