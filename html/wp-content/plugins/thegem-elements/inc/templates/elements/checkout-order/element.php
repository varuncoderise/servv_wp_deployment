<?php

class TheGem_Template_Element_Checkout_Order extends TheGem_Checkout_Template_Element
{
	
	public function __construct(){
	}
	
	public function get_name(){
		return 'thegem_te_checkout_order';
	}
	
	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'heading' => '1',
			'heading_alignment' => 'left',
			'heading_text_style' => '',
			'heading_font_weight' => 'light',
			'heading_letter_spacing' => '',
			'heading_text_transform' => '',
			'heading_text_color' => '',
            'heading_spacing_desktop' => '',
            'heading_spacing_tablet' => '',
            'heading_spacing_mobile' => '',
			'dividers' => '1',
			'divider_top' => '1',
			'divider_bottom' => '1',
			'dividers_color' => '',
			'text_color' => '',
			'links_color' => '',
			'links_color_hover' => '',
			'subtotal_total_color' => '',
			'amount_background' => '',
			'loading_overlay_color' => '',
			'label_text_color' => '',
			'input_marker_color' => '',
			'input_text_color' => '',
			'input_background_color' => '',
			'input_border_color' => '',
			'input_placeholder_color' => '',
			'input_border_radius' => '',
			'input_checkbox_border_radius' => '',
			'content_padding_desktop_top' => '',
			'content_padding_desktop_bottom' => '',
			'content_padding_desktop_left' => '',
			'content_padding_desktop_right' => '',
			'content_padding_tablet_top' => '',
			'content_padding_tablet_bottom' => '',
			'content_padding_tablet_left' => '',
			'content_padding_tablet_right' => '',
			'content_padding_mobile_top' => '',
			'content_padding_mobile_bottom' => '',
			'content_padding_mobile_left' => '',
			'content_padding_mobile_right' => '',
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
		), $atts, 'thegem_te_checkout_order');
  
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-') . rand(1, 9999);
		ob_start();
  
		$checkout = WC()->checkout();
		$title_classes = implode(' ', array('checkout-order-title', $params['heading_text_style'], $params['heading_font_weight']));
		$title_tag = empty($params['heading_text_style']) ? 'h3' : 'div';
		
		$params['element_class'] .= empty($params['dividers']) ? ' hide-dividers' : null;
		$params['element_class'] .= empty($params['divider_top']) ? ' hide-divider-top' : null;
		$params['element_class'] .= empty($params['divider_bottom']) ? ' hide-divider-bottom' : null;
        
        if (defined('WC_GERMANIZED_VERSION')) {
	        $params['element_class'] .= empty($params['order_btn']) ? ' place-order-btn--hide' : null;
	        $params['element_class'] .= ' place-order-btn--'.$params['order_btn_alignment'];
        }
		
		$params['element_class'] = implode(' ', array($params['element_class'], thegem_templates_responsive_options_output($params)));
  
		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?= esc_attr($params['element_id']); ?>"<?php endif; ?>
             class="thegem-te-checkout-order <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
            
            <div id="order_review">
	            <?php if (!empty($params['heading'])) : ?>
                    <<?= $title_tag; ?> class="<?= $title_classes; ?>"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></<?= $title_tag; ?>>
	            <?php endif; ?>
	
	            <?php if ( !is_ajax() && defined('WC_GERMANIZED_VERSION') ) { do_action( 'woocommerce_review_order_after_payment' ); } ?>
            
                <table class="shop_table woocommerce-checkout-review-order-table">
                    <thead>
                        <tr>
                            <th class="product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
                            <th class="product-total"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        do_action('woocommerce_review_order_before_cart_contents');
                
                        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    
                            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                                ?>
                                <tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                                    <td class="product-name">
                                        <div class="product-wrap">
                                            <div class="product-image">
                                                <?php
                                                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                                                if (!$product_permalink) {
                                                    echo $thumbnail; // PHPCS: XSS ok.
                                                } else {
                                                    printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                                                }
                                                ?>
                                            </div>
                                            <div class="product-info">
                                                <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) . '&nbsp;'; ?>
                                                <?php echo (!empty(wc_get_formatted_cart_item_data($cart_item))) ? '<br>' . wc_get_formatted_cart_item_data($cart_item) : ''; ?>
                                                <br>
                                                <?php echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf('&times;&nbsp;%s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                                <?php echo '<br>' . wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="product-total">
                                        <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                
                        do_action('woocommerce_review_order_after_cart_contents');
                        ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="1000">
                            <table class="shop_table woocommerce-checkout-payment-total">
                                <tr class="cart-subtotal">
                                    <th><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
                                    <td><?php wc_cart_totals_subtotal_html(); ?></td>
                                </tr>
						
						        <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                                    <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                                        <th><?php wc_cart_totals_coupon_label($coupon); ?></th>
                                        <td><?php wc_cart_totals_coupon_html($coupon); ?></td>
                                    </tr>
						        <?php endforeach; ?>
						
						        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
							
							        <?php do_action('woocommerce_review_order_before_shipping'); ?>
							
							        <?php wc_cart_totals_shipping_html(); ?>
							
							        <?php do_action('woocommerce_review_order_after_shipping'); ?>
						
						        <?php endif; ?>
						
						        <?php foreach (WC()->cart->get_fees() as $fee) : ?>
                                    <tr class="fee">
                                        <th><?php echo esc_html($fee->name); ?></th>
                                        <td><?php wc_cart_totals_fee_html($fee); ?></td>
                                    </tr>
						        <?php endforeach; ?>
						
						        <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
							        <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
								        <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                                            <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                                                <th><?php echo esc_html($tax->label); ?></th>
                                                <td><?php echo wp_kses_post($tax->formatted_amount); ?></td>
                                            </tr>
								        <?php endforeach; ?>
							        <?php else : ?>
                                        <tr class="tax-total">
                                            <th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
                                            <td><?php wc_cart_totals_taxes_total_html(); ?></td>
                                        </tr>
							        <?php endif; ?>
						        <?php endif; ?>
						
						        <?php do_action('woocommerce_review_order_before_order_total'); ?>

                                <tr class="order-total">
                                    <th><?php esc_html_e('Total', 'woocommerce'); ?></th>
                                    <td><?php wc_cart_totals_order_total_html(); ?></td>
                                </tr>
						
						        <?php do_action('woocommerce_review_order_after_order_total'); ?>
                            </table>
                        </td>
                    </tr>
                    </tfoot>
                </table>
                
                <?php if (defined('WC_GERMANIZED_VERSION')) { woocommerce_gzd_template_order_submit(); } ?>
            </div>
        
        </div>
        
		<?php
		//Custom Styles
		$customize = '.thegem-te-checkout-order.'.$uniqid;
		$custom_css = '';
		$resolution = array('desktop', 'tablet', 'mobile');
		$directions = array('top', 'bottom', 'left', 'right');
		
		// Heading Styles
		if (empty($params['heading'])) {
			$custom_css .= $customize.' #order_review .woocommerce-checkout-review-order-table tbody tr:first-child th {border: 0; padding-top: 0;}';
			$custom_css .= $customize.' #order_review .woocommerce-checkout-review-order-table tbody tr:first-child td {border: 0; padding-top: 0;}';
		}
		if (!empty($params['heading_alignment'])) {
			$custom_css .= $customize.' .checkout-order-title {text-align: ' . $params['heading_alignment'] . ';}';
		}
		if (!empty($params['heading_letter_spacing'])) {
			$custom_css .= $customize.' .checkout-order-title {letter-spacing: ' . $params['heading_letter_spacing'] . 'px;}';
		}
		if (!empty($params['heading_text_transform'])) {
			$custom_css .= $customize.' .checkout-order-title {text-transform: ' . $params['heading_text_transform'] . ';}';
		}
		if (!empty($params['heading_text_color'])) {
			$custom_css .= $customize.' .checkout-order-title {color: ' . $params['heading_text_color'] . ';}';
		}
		foreach ($resolution as $res) {
			if (!empty($params['heading_spacing_'.$res]) || strcmp($params['heading_spacing_'.$res], '0') === 0) {
				$result = str_replace(' ', '', $params['heading_spacing_'.$res]);
				$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
				if ($res == 'desktop') {
					$custom_css .= $customize.' .checkout-order-title {margin-bottom:'.$result.$unit.' !important;}';
				} else {
					$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
					$custom_css .= '@media screen and ('.$width.') {'.$customize.' .checkout-order-title {margin-bottom:'.$result.$unit.' !important;}}';
				}
			}
		}
		
		// Content Styles
		if (!empty($params['dividers_color'])) {
			$custom_css .= $customize.' #order_review .woocommerce-checkout-review-order-table th {border-color:'.$params['dividers_color'].' !important;}';
			$custom_css .= $customize.' #order_review .woocommerce-checkout-review-order-table td {border-color:'.$params['dividers_color'].' !important;}';
		}
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' #order_review .woocommerce-checkout-payment-total th {color: ' . $params['text_color'] . ' !important;}';
			$custom_css .= $customize.' #order_review .woocommerce-checkout-payment-total td {color: ' . $params['text_color'] . ' !important;}';
			$custom_css .= $customize.' #order_review .woocommerce-checkout-review-order-table .cart_item {color: ' . $params['text_color'] . ' !important;}';
			$custom_css .= $customize.' #order_review .woocommerce-checkout-review-order-table .cart_item .product-title {color: ' . $params['text_color'] . ' !important;}';
			$custom_css .= $customize.' #order_review .woocommerce-checkout-review-order-table .cart_item .product-quantity {color: ' . $params['text_color'] . ' !important;}';
			$custom_css .= $customize.' #order_review .woocommerce-checkout-review-order-table .cart_item .variation {color: ' . $params['text_color'] . ' !important;}';
			$custom_css .= $customize.' #order_review .woocommerce-checkout-review-order-table .cart_item .product-total {color: ' . $params['text_color'] . ' !important;}';
			$custom_css .= $customize.' .dhl-preferred-service-content {color: ' . $params['text_color'] . ' !important;}';
			$custom_css .= $customize.' .dhl-preferred-service-item .woocommerce-help-tip {color: ' . $params['text_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-checkout-payment-total td p.wc-gzd-additional-info {color: ' . $params['text_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-gzd-legal-checkbox-text {color: ' . $params['text_color'] . ' !important;}';
		}
		if (!empty($params['links_color'])) {
			$custom_css .= $customize.' #order_review .woocommerce-checkout-review-order-table a {color: ' . $params['links_color'] . ' !important;}';
			$custom_css .= $customize.' #order_review .woocommerce-checkout-payment-total a {color: ' . $params['links_color'] . ' !important;}';
			$custom_css .= $customize.' .wc-gzd-checkbox-placeholder a {color: ' . $params['links_color'] . ' !important;}';
			$custom_css .= $customize.' .dhl-preferred-service-content a {color: ' . $params['links_color'] . ' !important;}';
		}
		if (!empty($params['links_color_hover'])) {
			$custom_css .= $customize.' #order_review .woocommerce-checkout-review-order-table a:hover {color: ' . $params['links_color_hover'] . ' !important;}';
			$custom_css .= $customize.' #order_review .woocommerce-checkout-payment-total a:hover {color: ' . $params['links_color_hover'] . ' !important;}';
			$custom_css .= $customize.' .wc-gzd-checkbox-placeholder a:hover {color: ' . $params['links_color_hover'] . ' !important;}';
			$custom_css .= $customize.' .dhl-preferred-service-content a:hover {color: ' . $params['links_color_hover'] . ' !important;}';
		}
		if (!empty($params['subtotal_total_color'])) {
			$custom_css .= $customize.' #order_review .woocommerce-checkout-payment-total th span {color: ' . $params['subtotal_total_color'] . ' !important;}';
			$custom_css .= $customize.' #order_review .woocommerce-checkout-payment-total td {color: ' . $params['subtotal_total_color'] . ' !important;}';
			$custom_css .= $customize.' #order_review .woocommerce-checkout-payment-total td span {color: ' . $params['subtotal_total_color'] . ' !important;}';
		}
		if (!empty($params['amount_background'])) {
			$custom_css .= $customize.' #order_review .woocommerce-checkout-review-order-table .product-quantity {background-color: ' . $params['amount_background'] . ' !important;}';
			$custom_css .= $customize.' .dhl-preferred-service-item .woocommerce-help-tip {background-color: ' . $params['amount_background'] . ' !important;}';
		}
		foreach ($resolution as $res) {
			foreach ($directions as $dir) {
				if (!empty($params['content_padding'.'_'.$res.'_'.$dir]) || strcmp($params['content_padding'.'_'.$res.'_'.$dir], '0') === 0) {
					$result = str_replace(' ', '', $params['content_padding'.'_'.$res.'_'.$dir]);
					$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
					if ($res == 'desktop') {
						$custom_css .= $customize.' #order_review {padding-'.$dir.':'.$result.$unit.' !important;}';
					} else {
						$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' #order_review {padding-'.$dir.':'.$result.$unit.' !important;}}';
					}
				}
			}
		}
		
		// Form Styles
		if (!empty($params['label_text_color'])) {
			$custom_css .= $customize.' .woocommerce-shipping-methods label {color: ' . $params['label_text_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-gzd-legal-checkbox-text {color: ' . $params['label_text_color'] . ' !important;}';
		}
		if (!empty($params['input_marker_color'])) {
			$custom_css .= $customize.' .woocommerce-shipping-methods .radio-sign:before {background-color: ' . $params['input_marker_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-shipping-methods .checkbox-sign:before {color: ' . $params['input_marker_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-shipping-methods span.required {color: ' . $params['input_marker_color'] . ' !important;}';
			$custom_css .= $customize.' .wc-gzd-checkbox-placeholder .checkbox-sign:before {color: ' . $params['input_marker_color'] . ' !important;}';
			$custom_css .= $customize.' .dhl-preferred-service-content .radio-sign:before {background-color: ' . $params['input_marker_color'] . ' !important;}';
		}
		if (!empty($params['input_text_color'])) {
			$custom_css .= $customize.' .dhl-preferred-service-content input[type=text] {color: ' . $params['input_text_color'] . ' !important;}';
		}
		if (!empty($params['input_placeholder_color'])) {
			$custom_css .= $customize.' .dhl-preferred-service-content input[type=text]::placeholder {color: ' . $params['input_placeholder_color'] . ' !important;}';
		}
		if (!empty($params['input_background_color'])) {
			$custom_css .= $customize.' .woocommerce-shipping-methods .radio-sign {background-color: ' . $params['input_background_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-shipping-methods .checkbox-sign {background-color: ' . $params['input_background_color'] . ' !important;}';
			$custom_css .= $customize.' .wc-gzd-checkbox-placeholder .checkbox-sign {background-color: ' . $params['input_background_color'] . ' !important;}';
			$custom_css .= $customize.' .dhl-preferred-service-content .radio-sign {background-color: ' . $params['input_background_color'] . ' !important;}';
			$custom_css .= $customize.' .dhl-preferred-service-content input[type=text] {background-color: ' . $params['input_background_color'] . ' !important;}';
		}
		if (!empty($params['input_border_color'])) {
			$custom_css .= $customize.' .woocommerce-shipping-methods .radio-sign {border-color: ' . $params['input_border_color'] . ' !important;}';
			$custom_css .= $customize.' .woocommerce-shipping-methods .checkbox-sign {border-color: ' . $params['input_border_color'] . ' !important;}';
			$custom_css .= $customize.' .wc-gzd-checkbox-placeholder .checkbox-sign {border-color: ' . $params['input_border_color'] . ' !important;}';
			$custom_css .= $customize.' .dhl-preferred-service-content .radio-sign {border-color: ' . $params['input_border_color'] . ' !important;}';
			$custom_css .= $customize.' .dhl-preferred-service-content input[type=text] {border-color: ' . $params['input_border_color'] . ' !important;}';
		}
		if (!empty($params['input_border_radius']) || $params['input_border_radius'] == 0) {
			$custom_css .= $customize.' .dhl-preferred-service-content input[type=text] {border-radius: ' . $params['input_border_radius'] . 'px !important;}';
		}
		if (!empty($params['input_checkbox_border_radius']) || $params['input_checkbox_border_radius'] == 0) {
			$custom_css .= $customize.' .woocommerce-checkout-payment .checkbox-sign {border-radius: ' . $params['input_checkbox_border_radius'] . 'px !important;}';
			$custom_css .= $customize.' .wc-gzd-checkbox-placeholder .checkbox-sign {border-radius: ' . $params['input_checkbox_border_radius'] . 'px !important;}';
		}
		
		// Order Button Styles
		if (!empty($params['order_btn_border_width'])) {
			$add_to_cart_btn_line_height = intval(40 - $params['order_btn_border_width']*2);
			$custom_css .= $customize.' .wc-gzd-order-submit .gem-button {border-width:'.$params['order_btn_border_width'].'px !important;}';
			$custom_css .= $customize.' .wc-gzd-order-submit .gem-button {line-height: '.$add_to_cart_btn_line_height.'px !important;}';
		}
		if (!empty($params['order_btn_border_radius']) || $params['order_btn_border_radius'] == 0) {
			$custom_css .= $customize.' .wc-gzd-order-submit .gem-button {border-radius:'.$params['order_btn_border_radius'].'px !important;}';
		}
		if (!empty($params['order_btn_text_color'])) {
			$custom_css .= $customize.' .wc-gzd-order-submit .gem-button {color:'.$params['order_btn_text_color'].'!important;}';
		}
		if (!empty($params['order_btn_text_color_hover'])) {
			$custom_css .= $customize.' .wc-gzd-order-submit .gem-button:hover {color:'.$params['order_btn_text_color_hover'].'!important;}';
		}
		if (!empty($params['order_btn_background_color'])) {
			$custom_css .= $customize.' .wc-gzd-order-submit .gem-button {background-color:'.$params['order_btn_background_color'].'!important;}';
		}
		if (!empty($params['order_btn_background_color_hover'])) {
			$custom_css .= $customize.' .wc-gzd-order-submit .gem-button:hover {background-color:'.$params['order_btn_background_color_hover'].'!important;}';
		}
		if (!empty($params['order_btn_border_color'])) {
			$custom_css .= $customize.' .wc-gzd-order-submit .gem-button {border-color:'.$params['order_btn_border_color'].'!important;}';
		}
		if (!empty($params['order_btn_border_color_hover'])) {
			$custom_css .= $customize.' .wc-gzd-order-submit .gem-button:hover {border-color:'.$params['order_btn_border_color_hover'].'!important;}';
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
	
	public function set_heading_params() {
		$resolutions = array('desktop', 'tablet', 'mobile');
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Heading', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Heading', 'thegem'),
			'param_name' => 'heading',
			'value' => array(__('Show', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Alignment', 'thegem'),
			'param_name' => 'heading_alignment',
			'value' => array_merge(array(
					__('Left', 'thegem') => 'left',
					__('Center', 'thegem') => 'center',
					__('Right', 'thegem') => 'right',
				)
			),
			'std' => 'left',
			'dependency' => array(
				'element' => 'heading',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'heading_text_style',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Title H1', 'thegem') => 'title-h1',
				__('Title H2', 'thegem') => 'title-h2',
				__('Title H3', 'thegem') => 'title-h3',
				__('Title H4', 'thegem') => 'title-h4',
				__('Title H5', 'thegem') => 'title-h5',
				__('Title H6', 'thegem') => 'title-h6',
				__('Title xLarge', 'thegem') => 'title-xlarge',
				__('Styled Subtitle', 'thegem') => 'styled-subtitle',
				__('Main Menu', 'thegem') => 'title-main-menu',
				__('Body', 'thegem') => 'text-body',
				__('Tiny Body', 'thegem') => 'text-body-tiny',
			),
			'std' => '',
			'dependency' => array(
				'element' => 'heading',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'heading_font_weight',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Thin', 'thegem') => 'light',
			),
			'std' => 'light',
			'dependency' => array(
				'element' => 'heading',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'heading_letter_spacing',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'heading',
				'value' => '1'
			),
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Transform', 'thegem'),
			'param_name' => 'heading_text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'heading',
				'value' => '1'
			),
			'group' => $group
		);
		
		foreach ($resolutions as $res) {
			$result[] = array(
				'type' => 'textfield',
				'heading' => __('Bottom Spacing ('.$res.')', 'thegem'),
				'param_name' => 'heading_spacing_'.$res,
				'value' => '',
				'dependency' => array(
					'element' => 'heading',
					'value' => '1'
				),
				'edit_field_class' => 'vc_column vc_col-sm-4',
				'group' => $group
			);
		}
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'heading_text_color',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'dependency' => array(
				'element' => 'heading',
				'value' => '1'
			),
			'group' => $group
		);
		
		return $result;
	}
	
	public function set_content_params() {
		$group = __('General', 'thegem');
		$resolutions = array('desktop', 'tablet', 'mobile');
		$directions = array('top', 'bottom', 'left', 'right');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Content', 'thegem'),
			'param_name' => 'delimiter_heading_description',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
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
			'type' => 'checkbox',
			'heading' => __('Top Divider', 'thegem'),
			'param_name' => 'divider_top',
			'value' => array(__('Show', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'dividers',
				'value' => '1'
			),
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Bottom Divider', 'thegem'),
			'param_name' => 'divider_bottom',
			'value' => array(__('Show', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'dividers',
				'value' => '1'
			),
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
			'heading' => __('Subtotal & Total Color', 'thegem'),
			'param_name' => 'subtotal_total_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Amount Background Color', 'thegem'),
			'param_name' => 'amount_background',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Loading Overlay Color', 'thegem'),
			'param_name' => 'loading_overlay_color',
			'group' => $group
		);
		foreach ($resolutions as $res) {
			$result[] = array(
				'type' => 'thegem_delimeter_heading_two_level',
				'heading' => __(''.ucfirst($res).' '.'Paddings', 'thegem'),
				'param_name' => 'delimiter_heading_two_level_panel',
				'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
				'group' => $group
			);
			foreach ($directions as $dir) {
				$result[] = array(
					'type' => 'textfield',
					'heading' => __(ucfirst($dir), 'thegem'),
					'param_name' => 'content_padding_'.$res.'_'.$dir,
					'value' => '',
					'edit_field_class' => 'vc_column vc_col-sm-3',
					'group' => $group
				);
			}
		}
		
		return $result;
	}
	
	public function set_form_params() {
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __( !defined('WC_GERMANIZED_VERSION') ? 'Checkbox & Radio Button' : 'Form', 'thegem'),
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
		
		if (defined('WC_GERMANIZED_VERSION')) {
			$result[] = array(
				'type' => 'colorpicker',
				'heading' => __('Input Text Color', 'thegem'),
				'param_name' => 'input_text_color',
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			
			$result[] = array(
				'type' => 'colorpicker',
				'heading' => __('Input Placeholder Color', 'thegem'),
				'param_name' => 'input_placeholder_color',
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
		}
  
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
  
        if (defined('WC_GERMANIZED_VERSION')) {
	        $result[] = array(
		        'type' => 'textfield',
		        'heading' => __('Border Radius', 'thegem'),
		        'param_name' => 'input_border_radius',
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
        }
		
		return $result;
	}
 
	public function set_order_btn_params() {
		$result = array();
		$group = __('General', 'thegem');
		
		if (!defined('WC_GERMANIZED_VERSION')) return $result;
  
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('"Buy Now" Button', 'thegem'),
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
			'name' => __('Order Review', 'thegem'),
            'base' => 'thegem_te_checkout_order',
            'icon' => 'thegem-icon-wpb-ui-element-checkout-order',
            'category' => __('Checkout Builder', 'thegem'),
            'description' => __('Order Review (Checkout Builder)', 'thegem'),
			'params' => array_merge(
			
                /* General - Heading */
                $this->set_heading_params(),
			
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

$templates_elements['thegem_te_checkout_order'] = new TheGem_Template_Element_Checkout_Order();
$templates_elements['thegem_te_checkout_order']->init();
