<?php

class TheGem_Template_Element_Cart_Totals extends TheGem_Cart_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_cart_totals';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
            'heading' => '1',
            'heading_alignment' => 'left',
            'heading_text_style' => 'title-h3',
            'heading_font_weight' => 'light',
            'heading_letter_spacing' => '',
            'heading_text_transform' => '',
            'heading_text_color' => '',
			'heading_spacing_desktop' => '',
			'heading_spacing_tablet' => '',
			'heading_spacing_mobile' => '',
			'dividers' => '1',
			'dividers_color' => '',
			'loading_overlay_color' => '',
			'text_color' => '',
			'links_color' => '',
			'links_color_hover' => '',
			'subtotal_total_color' => '',
			'panel_padding_desktop_top' => '',
			'panel_padding_desktop_bottom' => '',
			'panel_padding_desktop_left' => '',
			'panel_padding_desktop_right' => '',
			'panel_padding_tablet_top' => '',
			'panel_padding_tablet_bottom' => '',
			'panel_padding_tablet_left' => '',
			'panel_padding_tablet_right' => '',
			'panel_padding_mobile_top' => '',
			'panel_padding_mobile_bottom' => '',
			'panel_padding_mobile_left' => '',
			'panel_padding_mobile_right' => '',
			'panel_background_color' => '',
			'panel_border_radius' => '',
			'label_text_color' => '',
			'input_text_color' => '',
			'input_background_color' => '',
			'input_border_color' => '',
			'input_border_color_error' => '',
			'input_placeholder_color' => '',
			'input_border_radius' => '',
			'checkout_btn' => '1',
			'checkout_btn_text' => 'Proceed to checkout',
			'checkout_btn_alignment' => 'fullwidth',
			'checkout_btn_border_width' => '',
			'checkout_btn_border_radius' => '',
			'checkout_btn_text_color' => '',
			'checkout_btn_text_color_hover' => '',
			'checkout_btn_background_color' => '',
			'checkout_btn_background_color_hover' => '',
			'checkout_btn_border_color' => '',
			'checkout_btn_border_color_hover' => '',
		),
			thegem_templates_extra_options_extract()
		), $atts, 'thegem_te_cart_totals');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		ob_start();
		
		$cart_totals_title_class = implode(' ', array($params['heading_text_style'], $params['heading_font_weight']));
		$cart_totals_btn_class = 'checkout-btn--'.$params['checkout_btn_alignment'];
		$params['element_class'] .= empty($params['heading']) ? ' cart-totals-title--hide' : null;
		$params['element_class'] .= empty($params['checkout_btn']) ? ' checkout-btn--hide' : null;
  
		add_action('woocommerce_before_cart_totals', 'woocommerce_cart_totals_wrap_start', 1);
		add_action('woocommerce_after_cart_totals', 'woocommerce_cart_totals_wrap_end', 100);
		WC()->cart->calculate_totals();
  
		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-cart-totals <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
             data-btn-classes="<?=$cart_totals_btn_class?>">

            <div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">
		
		        <?php do_action( 'woocommerce_before_cart_totals' ); ?>
	
	            <?php if (!empty($params['heading'])): ?>
                    <div class="cart_totals_title">
                        <span class="<?= $cart_totals_title_class ?>">
                            <?php esc_html_e( 'Cart totals', 'woocommerce' ); ?>
                        </span>
                    </div>
	            <?php endif; ?>

                <table cellspacing="0" class="shop_table shop_table_responsive">
    
                    <tr class="cart-subtotal">
                        <th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                        <td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
                    </tr>
            
                    <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                        <tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                            <th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
                            <td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
                        </tr>
                    <?php endforeach; ?>
            
                    <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                
                        <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
                
                        <?php wc_cart_totals_shipping_html(); ?>
                
                        <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
            
                    <?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>
    
                        <tr class="shipping">
                            <th><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></th>
                            <td data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
                        </tr>
            
                    <?php endif; ?>
            
                    <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                        <tr class="fee">
                            <th><?php echo esc_html( $fee->name ); ?></th>
                            <td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
                        </tr>
                    <?php endforeach; ?>
            
                    <?php
                    if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
                        $taxable_address = WC()->customer->get_taxable_address();
                        $estimated_text  = '';
                
                        if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
                            /* translators: %s location. */
                            $estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
                        }
                
                        if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
                            foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                                ?>
                                <tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                                    <th><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
                                    <td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr class="tax-total">
                                <th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
                                <td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
            
                    <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>
    
                    <tr class="order-total">
                        <th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
                        <td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
                    </tr>
            
                    <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
    
                </table>
	
	            <?php if (!empty($params['checkout_btn'])): ?>
                    <div class="wc-proceed-to-checkout checkout-btn--<?=$params['checkout_btn_alignment']?>">
			            <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
                    </div>
	            <?php endif; ?>
        
                <?php do_action( 'woocommerce_after_cart_totals' ); ?>

            </div>

            <script type="text/javascript">
                (function ($) {
                    $(document.body).on('updated_cart_totals', function () {
                        const $wrapper = $('.thegem-te-cart-totals.<?= esc_attr($uniqid) ?>');
                        const btnClass = $wrapper.data('btn-classes');
                        
                        $('.cart_totals_title', $wrapper).html(`<span class="<?= $cart_totals_title_class ?>"><?php esc_html_e( 'Cart totals', 'woocommerce' ); ?></span>`);
                        $('.wc-proceed-to-checkout', $wrapper).addClass(btnClass);
                    });
                })(jQuery);
            </script>
        </div>
        
		<?php
		//Custom Styles
		$customize = '.thegem-te-cart-totals.'.$uniqid;
		$custom_css = '';
		
		// Panel Styles
		$resolution = array('desktop', 'tablet', 'mobile');
		$directions = array('top', 'bottom', 'left', 'right');
		
		// Heading Styles
		if (!empty($params['heading_alignment'])) {
			$custom_css .= $customize.' .cart_totals .cart_totals-inner .cart_totals_title span {text-align: ' . $params['heading_alignment'] . ';}';
		}
		if (!empty($params['heading_letter_spacing'])) {
			$custom_css .= $customize.' .cart_totals .cart_totals-inner .cart_totals_title span {letter-spacing: ' . $params['heading_letter_spacing'] . 'px;}';
		}
		if (!empty($params['heading_text_transform'])) {
			$custom_css .= $customize.' .cart_totals .cart_totals-inner .cart_totals_title span {text-transform: ' . $params['heading_text_transform'] . ';}';
		}
        if (!empty($params['heading_text_color'])) {
			$custom_css .= $customize.' .cart_totals .cart_totals-inner .cart_totals_title span {color: ' . $params['heading_text_color'] . ';}';
		}
		foreach ($resolution as $res) {
			if (!empty($params['heading_spacing_'.$res]) || strcmp($params['heading_spacing_'.$res], '0') === 0) {
				$result = str_replace(' ', '', $params['heading_spacing_'.$res]);
				$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
				if ($res == 'desktop') {
					$custom_css .= $customize.' .cart_totals .cart_totals-inner .cart_totals_title span {margin-bottom:'.$result.$unit.' !important;}';
				} else {
					$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
					$custom_css .= '@media screen and ('.$width.') {'.$customize.' .cart_totals .cart_totals-inner .cart_totals_title span {margin-bottom:'.$result.$unit.' !important;}}';
				}
			}
		}
		
		// Content Styles
		if (!empty($params['panel_background_color'])) {
			$custom_css .= $customize.' .cart_totals .cart_totals-inner {background-color: ' . $params['panel_background_color'] . ' !important;}';
		}
		if (!empty($params['panel_border_radius']) || $params['panel_border_radius'] == 0) {
			$custom_css .= $customize.' .cart_totals .cart_totals-inner {border-radius: ' . $params['panel_border_radius'] . 'px !important;}';
		}
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .cart_totals .cart_totals-inner table.shop_table th {color: ' . $params['text_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .cart_totals-inner table.shop_table td {color: ' . $params['text_color'] . ' !important;}';
		}
		if (!empty($params['links_color'])) {
			$custom_css .= $customize.' .cart_totals .cart_totals-inner table.shop_table td a {color: ' . $params['links_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator button {color: ' . $params['links_color'] . ' !important;}';
		}
		if (!empty($params['links_color_hover'])) {
			$custom_css .= $customize.' .cart_totals .cart_totals-inner table.shop_table td a:hover {color: ' . $params['links_color_hover'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator button:hover {color: ' . $params['links_color_hover'] . ' !important;}';
		}
		if (!empty($params['subtotal_total_color'])) {
			$custom_css .= $customize.' .cart_totals .cart_totals-inner table.shop_table td .amount {color: ' . $params['subtotal_total_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .cart_totals-inner table.shop_table .cart-discount td {color: ' . $params['subtotal_total_color'] . ' !important;}';
		}
		if (empty($params['dividers'])) {
			$custom_css .= $customize.' .cart_totals .cart_totals-inner table.shop_table th {border: 0;}';
			$custom_css .= $customize.' .cart_totals .cart_totals-inner table.shop_table td {border: 0;}';
		}
		if (!empty($params['dividers_color'])) {
			$custom_css .= $customize.' .cart_totals .cart_totals-inner table.shop_table th {border-color:'.$params['dividers_color'].';}';
			$custom_css .= $customize.' .cart_totals .cart_totals-inner table.shop_table td {border-color:'.$params['dividers_color'].';}';
		}
		foreach ($resolution as $res) {
			foreach ($directions as $dir) {
				if (!empty($params['panel_padding'.'_'.$res.'_'.$dir]) || strcmp($params['panel_padding'.'_'.$res.'_'.$dir], '0') === 0) {
					$result = str_replace(' ', '', $params['panel_padding'.'_'.$res.'_'.$dir]);
					$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
					if ($res == 'desktop') {
						$custom_css .= $customize.' .cart_totals .cart_totals-inner {padding-'.$dir.':'.$result.$unit.' !important;}';
					} else {
						$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' .cart_totals .cart_totals-inner {padding-'.$dir.':'.$result.$unit.' !important;}}';
					}
				}
			}
		}
		
		// Checkout Button Styles
		if (!empty($params['checkout_btn_border_width'])) {
			$add_to_cart_btn_line_height = intval(40 - $params['checkout_btn_border_width']*2);
			$custom_css .= $customize.' .cart_totals .wc-proceed-to-checkout .gem-button {border-width:'.$params['checkout_btn_border_width'].'px !important;}';
			$custom_css .= $customize.' .cart_totals .wc-proceed-to-checkout .gem-button {line-height: '.$add_to_cart_btn_line_height.'px !important;}';
		}
		if (!empty($params['checkout_btn_border_radius']) || $params['checkout_btn_border_radius'] == 0) {
			$custom_css .= $customize.' .cart_totals .wc-proceed-to-checkout .gem-button {border-radius:'.$params['checkout_btn_border_radius'].'px !important;}';
		}
		if (!empty($params['checkout_btn_text_color'])) {
			$custom_css .= $customize.' .cart_totals .wc-proceed-to-checkout .gem-button {color:'.$params['checkout_btn_text_color'].'!important;}';
		}
		if (!empty($params['checkout_btn_text_color_hover'])) {
			$custom_css .= $customize.' .cart_totals .wc-proceed-to-checkout .gem-button:hover {color:'.$params['checkout_btn_text_color_hover'].'!important;}';
		}
		if (!empty($params['checkout_btn_background_color'])) {
			$custom_css .= $customize.' .cart_totals .wc-proceed-to-checkout .gem-button {background-color:'.$params['checkout_btn_background_color'].'!important;}';
		}
		if (!empty($params['checkout_btn_background_color_hover'])) {
			$custom_css .= $customize.' .cart_totals .wc-proceed-to-checkout .gem-button:hover {background-color:'.$params['checkout_btn_background_color_hover'].'!important;}';
		}
		if (!empty($params['checkout_btn_border_color'])) {
			$custom_css .= $customize.' .cart_totals .wc-proceed-to-checkout .gem-button {border-color:'.$params['checkout_btn_border_color'].'!important;}';
		}
		if (!empty($params['checkout_btn_border_color_hover'])) {
			$custom_css .= $customize.' .cart_totals .wc-proceed-to-checkout .gem-button:hover {border-color:'.$params['checkout_btn_border_color_hover'].'!important;}';
		}
		
		// Form Styles
		if (!empty($params['input_text_color'])) {
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row input.input-text {color: ' . $params['input_text_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row .select2-selection__rendered {color: ' . $params['input_text_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row .select2-selection__arrow {color: ' . $params['input_text_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row .checkbox-sign:before {color: ' . $params['input_text_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-totals .radio-sign.checked:before {background-color: ' . $params['input_text_color'] . ' !important;}';
		}
		if (!empty($params['input_background_color'])) {
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row input.input-text {background-color: ' . $params['input_background_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row .select2-selection__rendered {background-color: ' . $params['input_background_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row .checkbox-sign {background-color: ' . $params['input_background_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-totals .radio-sign {background-color: ' . $params['input_background_color'] . ' !important;}';
		}
		if (!empty($params['input_border_color'])) {
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row input.input-text {border-color: ' . $params['input_border_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row .select2-selection--single {border-color: ' . $params['input_border_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row .checkbox-sign {border-color: ' . $params['input_border_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-totals .radio-sign {border-color: ' . $params['input_border_color'] . ' !important;}';
		}
		if (!empty($params['input_placeholder_color'])) {
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row .select2-selection__placeholder {color: ' . $params['input_placeholder_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row input.input-text::placeholder {color: ' . $params['input_placeholder_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row textarea.input-text::placeholder {color: ' . $params['input_placeholder_color'] . ' !important;}';
		}
		if (!empty($params['label_text_color'])) {
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row label {color: ' . $params['label_text_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-totals label {color: ' . $params['label_text_color'] . ' !important;}';
		}
		if (!empty($params['input_border_radius']) || $params['input_border_radius'] == 0) {
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row input.input-text {border-radius: ' . $params['input_border_radius'] . 'px !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row .select2-selection--single {border-radius: ' . $params['input_border_radius'] . 'px !important;}';
			$custom_css .= $customize.' .cart_totals .woocommerce-shipping-calculator .form-row .select2-selection__rendered {border-radius: ' . $params['input_border_radius'] . 'px !important;}';
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
				__('Default', 'thegem') => 'title-h3',
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
			'std' => 'title-h3',
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
			'type' => 'colorpicker',
			'heading' => __('Background Color', 'thegem'),
			'param_name' => 'panel_background_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			"type" => "textfield",
			'heading' => __('Border Radius', 'thegem'),
			'param_name' => 'panel_border_radius',
			"edit_field_class" => "vc_column vc_col-sm-6",
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
			'type' => 'checkbox',
			'heading' => __('Dividers', 'thegem'),
			'param_name' => 'dividers',
			'value' => array(__('Show', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-6',
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
					'param_name' => 'panel_padding_'.$res.'_'.$dir,
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
			'heading' => __('Form', 'thegem'),
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
			'heading' => __('Input Text Color', 'thegem'),
			'param_name' => 'input_text_color',
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
			'type' => 'colorpicker',
			'heading' => __('Input Placeholder Color', 'thegem'),
			'param_name' => 'input_placeholder_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Input Border Radius', 'thegem'),
			'param_name' => 'input_border_radius',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		return $result;
	}
 
	public function set_checkout_btn_params() {
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Checkout Button', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Checkout Button', 'thegem'),
			'param_name' => 'checkout_btn',
			'value' => array(__('Show', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
        /*
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Checkout Button Text', 'thegem'),
			'param_name' => 'checkout_btn_text',
			'edit_field_class' => 'vc_column vc_col-sm-12',
            'std' => 'Proceed to checkout',
			'dependency' => array(
				'element' => 'checkout_btn',
				'value' => '1'
			),
			'group' => $group
		);
        */
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Checkout Button Alignment', 'thegem'),
			'param_name' => 'checkout_btn_alignment',
			'value' => array_merge(array(
					__('Left', 'thegem') => 'left',
					__('Center', 'thegem') => 'center',
					__('Right', 'thegem') => 'right',
					__('Fullwidth', 'thegem') => 'fullwidth',
				)
			),
			'std' => 'fullwidth',
			'dependency' => array(
				'element' => 'checkout_btn',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Border Width', 'thegem'),
			'param_name' => 'checkout_btn_border_width',
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
				'element' => 'checkout_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group,
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Radius', 'thegem'),
			'param_name' => 'checkout_btn_border_radius',
			'value' => '',
			'dependency' => array(
				'element' => 'checkout_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'checkout_btn_text_color',
			'std' => '',
			'dependency' => array(
				'element' => 'checkout_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color on Hover', 'thegem'),
			'param_name' => 'checkout_btn_text_color_hover',
			'std' => '',
			'dependency' => array(
				'element' => 'checkout_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color', 'thegem'),
			'param_name' => 'checkout_btn_background_color',
			'std' => '',
			'dependency' => array(
				'element' => 'checkout_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color on Hover', 'thegem'),
			'param_name' => 'checkout_btn_background_color_hover',
			'std' => '',
			'dependency' => array(
				'element' => 'checkout_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color', 'thegem'),
			'param_name' => 'checkout_btn_border_color',
			'std' => '',
			'dependency' => array(
				'element' => 'checkout_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color on Hover', 'thegem'),
			'param_name' => 'checkout_btn_border_color_hover',
			'std' => '',
			'dependency' => array(
				'element' => 'checkout_btn',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		return $result;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Cart Totals', 'thegem'),
			'base' => 'thegem_te_cart_totals',
			'icon' => 'thegem-icon-wpb-ui-element-cart-totals',
			'category' => __('Cart Builder', 'thegem'),
			'description' => __('Cart Totals', 'thegem'),
			'params' => array_merge(
                
                /* General - Heading */
				$this->set_heading_params(),
				
				/* General - Content */
				$this->set_content_params(),
				
				/* General - Form */
				$this->set_form_params(),
                
                /* General - Checkout Button */
				$this->set_checkout_btn_params(),
				
				/* Extra Options */
				thegem_set_elements_extra_options()
			),
		);
	}
}

$templates_elements['thegem_te_cart_totals'] = new TheGem_Template_Element_Cart_Totals();
$templates_elements['thegem_te_cart_totals']->init();
