<?php

class TheGem_Template_Element_Checkout_Thanks_Cart_Totals  extends TheGem_Checkout_Thanks_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_checkout_thanks_cart_totals';
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
			'dividers_color' => '',
			'text_color' => '',
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
        ),
            thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_checkout_thanks_cart_totals');
		
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
  
		$downloads = $order->get_downloadable_items();
		$show_downloads = $order->has_downloadable_item() && $order->is_download_permitted();
		
		if ($show_downloads) {
			wc_get_template('order/order-downloads.php', array('downloads' => $downloads, 'show_title' => true));
		}
		
		$title_classes = implode(' ', array('woocommerce-cart-totals__title', $params['heading_text_style'], $params['heading_font_weight']));
		$title_tag = empty($params['heading_text_style']) ? 'h3' : 'div';
		
		$params['element_class'] .= empty($params['dividers']) ? ' hide-dividers' : null;
		$params['element_class'] = implode(' ', array($params['element_class'], thegem_templates_responsive_options_output($params)));
  
		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-checkout-thanks-cart-totals <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">

            <div class="order-details-column">
                <?php if (!empty($params['heading'])) : ?>
                    <<?= $title_tag; ?> class="<?= $title_classes; ?>"><?php esc_html_e( 'Cart totals', 'woocommerce' ); ?></<?= $title_tag; ?>>
                <?php endif; ?>

                <div class="cart_totals default-background">
                    <table>
                        <tbody cellspacing="0">
                        <?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
                            <tr>
                                <th scope="row" colspan="2"><?php echo esc_html( $total['label'] ); ?></th>
                                <td colspan="2"><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ( $order->get_customer_note() ) : ?>
                            <tr>
                                <th colspan="2"><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
                                <td colspan="2"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
		<?php
		//Custom Styles
		$customize = '.thegem-te-checkout-thanks-cart-totals.'.$uniqid;
		$custom_css = '';
		$resolution = array('desktop', 'tablet', 'mobile');
		$directions = array('top', 'bottom', 'left', 'right');
		
		// Heading Styles
		if (!empty($params['heading_alignment'])) {
			$custom_css .= $customize.' .woocommerce-cart-totals__title {text-align: ' . $params['heading_alignment'] . ';}';
		}
		if (!empty($params['heading_letter_spacing'])) {
			$custom_css .= $customize.' .woocommerce-cart-totals__title {letter-spacing: ' . $params['heading_letter_spacing'] . 'px;}';
		}
		if (!empty($params['heading_text_transform'])) {
			$custom_css .= $customize.' .woocommerce-cart-totals__title {text-transform: ' . $params['heading_text_transform'] . ';}';
		}
		if (!empty($params['heading_text_color'])) {
			$custom_css .= $customize.' .woocommerce-cart-totals__title {color: ' . $params['heading_text_color'] . ';}';
		}
		foreach ($resolution as $res) {
			if (!empty($params['heading_spacing_'.$res]) || strcmp($params['heading_spacing_'.$res], '0') === 0) {
				$result = str_replace(' ', '', $params['heading_spacing_'.$res]);
				$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
				if ($res == 'desktop') {
					$custom_css .= $customize.' .woocommerce-cart-totals__title {margin-bottom:'.$result.$unit.' !important;}';
				} else {
					$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
					$custom_css .= '@media screen and ('.$width.') {'.$customize.' .woocommerce-cart-totals__title {margin-bottom:'.$result.$unit.' !important;}}';
				}
			}
		}
		
		// Content Styles
		if (!empty($params['panel_background_color'])) {
			$custom_css .= $customize.' .cart_totals {background-color: ' . $params['panel_background_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals table td {background-color: ' . $params['panel_background_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals table th {background-color: ' . $params['panel_background_color'] . ' !important;}';
		}
		if (!empty($params['panel_border_radius']) || $params['panel_border_radius'] == 0) {
			$custom_css .= $customize.' .cart_totals {border-radius: ' . $params['panel_border_radius'] . 'px !important;}';
		}
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .cart_totals table th {color: ' . $params['text_color'] . ' !important;}';
			$custom_css .= $customize.' .cart_totals table td {color: ' . $params['text_color'] . ' !important;}';
		}
		if (!empty($params['subtotal_total_color'])) {
			$custom_css .= $customize.' .cart_totals table td .amount {color: ' . $params['subtotal_total_color'] . ' !important;}';
		}
		if (!empty($params['dividers_color'])) {
			$custom_css .= $customize.' .cart_totals table th {border-color:'.$params['dividers_color'].' !important;}';
			$custom_css .= $customize.' .cart_totals table td {border-color:'.$params['dividers_color'].' !important;}';
		}
		foreach ($resolution as $res) {
			foreach ($directions as $dir) {
				if (!empty($params['panel_padding'.'_'.$res.'_'.$dir]) || strcmp($params['panel_padding'.'_'.$res.'_'.$dir], '0') === 0) {
					$result = str_replace(' ', '', $params['panel_padding'.'_'.$res.'_'.$dir]);
					$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
					if ($res == 'desktop') {
						$custom_css .= $customize.' .cart_totals {padding-'.$dir.':'.$result.$unit.' !important;}';
					} else {
						$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' .cart_totals {padding-'.$dir.':'.$result.$unit.' !important;}}';
					}
				}
			}
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

	public function shortcode_settings() {
		return array(
			'name' => __('Cart Totals', 'thegem'),
			'base' => 'thegem_te_checkout_thanks_cart_totals',
			'icon' => 'thegem-icon-wpb-ui-element-cart-totals',
			'category' => __('Purchase Summary Builder', 'thegem'),
			'description' => __('Purchase Summary Cart Totals', 'thegem'),
			'params' => array_merge(
			
			    /* General - Heading */
				$this->set_heading_params(),
				
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

$templates_elements['thegem_te_checkout_thanks_cart_totals'] = new TheGem_Template_Element_Checkout_Thanks_Cart_Totals();
$templates_elements['thegem_te_checkout_thanks_cart_totals']->init();
