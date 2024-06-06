<?php

class TheGem_Template_Element_Checkout_Thanks_Shipping_Details  extends TheGem_Checkout_Thanks_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_checkout_thanks_shipping_details';
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
            'list_alignment' => 'left',
            'list_color' => '',
        ),
            thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_checkout_thanks_shipping_details');
		
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
		
		$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
  
		$title_classes = implode(' ', array('woocommerce-shipping-details__title', $params['heading_text_style'], $params['heading_font_weight']));
		$title_tag = empty($params['heading_text_style']) ? 'h3' : 'div';
  
		$params['element_class'] .= !empty($params['list_alignment']) ? 'list-alignment--'.$params['list_alignment'] : null;
		$params['element_class'] = implode(' ', array($params['element_class'], thegem_templates_responsive_options_output($params)));
        
        if (!$show_shipping) return;
  
		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-checkout-thanks-shipping-details <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
        
            <?php if (!empty($params['heading'])) : ?>
                <<?= $title_tag; ?> class="<?= $title_classes; ?>"><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></<?= $title_tag; ?>>
            <?php endif; ?>

            <address class="woocommerce-shipping-details">
                <?php echo wp_kses_post( $order->get_formatted_shipping_address( __( 'N/A', 'woocommerce' ) ) ); ?>
            </address>
        </div>
        
		<?php
		//Custom Styles
		$customize = '.thegem-te-checkout-thanks-shipping-details.'.$uniqid;
		$custom_css = '';
		$resolution = array('desktop', 'tablet', 'mobile');
		
		// Heading Styles
		if (!empty($params['heading_alignment'])) {
			$custom_css .= $customize.' .woocommerce-shipping-details__title {text-align: ' . $params['heading_alignment'] . ';}';
		}
		if (!empty($params['heading_letter_spacing'])) {
			$custom_css .= $customize.' .woocommerce-shipping-details__title {letter-spacing: ' . $params['heading_letter_spacing'] . 'px;}';
		}
		if (!empty($params['heading_text_transform'])) {
			$custom_css .= $customize.' .woocommerce-shipping-details__title {text-transform: ' . $params['heading_text_transform'] . ';}';
		}
		if (!empty($params['heading_text_color'])) {
			$custom_css .= $customize.' .woocommerce-shipping-details__title {color: ' . $params['heading_text_color'] . ';}';
		}
		foreach ($resolution as $res) {
			if (!empty($params['heading_spacing_'.$res]) || strcmp($params['heading_spacing_'.$res], '0') === 0) {
				$result = str_replace(' ', '', $params['heading_spacing_'.$res]);
				$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
				if ($res == 'desktop') {
					$custom_css .= $customize.' .woocommerce-shipping-details__title {margin-bottom:'.$result.$unit.' !important;}';
				} else {
					$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
					$custom_css .= '@media screen and ('.$width.') {'.$customize.' .woocommerce-shipping-details__title {margin-bottom:'.$result.$unit.' !important;}}';
				}
			}
		}
		
		// Content Styles
		if (!empty($params['list_color'])) {
			$custom_css .= $customize.' .woocommerce-shipping-details {color: ' . $params['list_color'] . ' !important;}';
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
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Content', 'thegem'),
			'param_name' => 'delimiter_heading_description',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
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
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		return $result;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Shipping Details', 'thegem'),
			'base' => 'thegem_te_checkout_thanks_shipping_details',
			'icon' => 'thegem-icon-wpb-ui-element-checkout-shipping',
			'category' => __('Purchase Summary Builder', 'thegem'),
			'description' => __('Purchase Summary Shipping Details', 'thegem'),
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

$templates_elements['thegem_te_checkout_thanks_shipping_details'] = new TheGem_Template_Element_Checkout_Thanks_Shipping_Details();
$templates_elements['thegem_te_checkout_thanks_shipping_details']->init();
