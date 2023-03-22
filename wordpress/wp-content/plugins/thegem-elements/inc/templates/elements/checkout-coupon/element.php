<?php

class TheGem_Template_Element_Checkout_Coupon extends TheGem_Checkout_Template_Element{

	public function __construct(){
	}

	public function get_name(){
		return 'thegem_te_checkout_coupon';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = array_merge(
			array(
				'text' => __( 'Have a coupon?', 'woocommerce' ),
				'link_text' => __( 'Click here to enter your code', 'woocommerce' ),
				'input_border_radius' => '',
				'btn_border_radius' => '',
				'inline' => '',
				'separator' => '',
				'separator_position' => 'left',
				'separator_spacing' => '',
			),
			thegem_templates_extra_options_extract()
		);
		if(is_array($atts)) {
			$params = array_merge($params, $atts);
		}
		if ( ! wc_coupons_enabled() ) {
			if(thegem_get_template_type(get_the_ID()) === 'checkout') {
				return '<div class="thegem-te-checkout-coupon template-checkout-empty-output default-background">'.__('Apply Coupon', 'thegem').'</div>';
			} else {
				return '';
			}
		}
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-') . rand(1, 9999);
		ob_start();


		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?= esc_attr($params['element_id']); ?>"<?php endif; ?>
			 class="thegem-te-checkout-coupon <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
<div class="checkout-notice checkout-coupon-notice">
	<?php if(!empty($params['inline']) && !empty($params['separator']) && $params['separator_position'] == 'left') { echo '<span class="separator"></span>';}
	echo esc_html($params['text']); ?> <a href="#" class="showcoupon checkout-show-coupon-popup"><?= esc_html($params['link_text']); ?></a><?php
	if(!empty($params['inline']) && !empty($params['separator']) && $params['separator_position'] == 'right') { echo '<span class="separator"></span>';} ?>
</div>
		</div>

		<?php

		//Custom Styles
		$customize = '.thegem-te-checkout-coupon.'.$uniqid;
		$custom_css = '';
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .checkout-coupon-notice {color: ' . $params['text_color'] . ' !important;}';
			$custom_css .= $customize.' .separator {background-color: ' . $params['text_color'] . ' !important;}';
		}
		if (!empty($params['link_color'])) {
			$custom_css .= $customize.' .checkout-coupon-notice a {color: ' . $params['link_color'] . ' !important;}';
		}
		if (!empty($params['link_hover_color'])) {
			$custom_css .= $customize.' .checkout-coupon-notice a:hover {color: ' . $params['link_hover_color'] . ' !important;}';
		}
		if (!empty($params['popup_background_color'])) {
			$custom_css .= '#checkout-coupon-popup {background-color: ' . $params['popup_background_color'] . ' !important;}';
		}
		if (!empty($params['input_text_color'])) {
			$custom_css .= '#checkout-coupon-popup input[type="text"].coupon-code {color: ' . $params['input_text_color'] . ' !important;}';
		}
		if (!empty($params['input_background_color'])) {
			$custom_css .= '#checkout-coupon-popup input[type="text"].coupon-code {background-color: ' . $params['input_background_color'] . ' !important;}';
		}
		if (!empty($params['input_border_color'])) {
			$custom_css .= '#checkout-coupon-popup input[type="text"].coupon-code {border-color: ' . $params['input_border_color'] . ' !important;}';
		}
		if (!empty($params['placeholder_text_color'])) {
			$custom_css .= '#checkout-coupon-popup input[type="text"].coupon-code::placeholder {color: ' . $params['placeholder_text_color'] . ' !important;}';
		}
		if (!empty($params['input_border_radius']) || $params['input_border_radius'] === '0') {
			$custom_css .= '#checkout-coupon-popup input[type="text"].coupon-code {border-radius: ' . $params['input_border_radius'] . 'px !important;}';
		}
		if (!empty($params['btn_border_width'])) {
			$custom_css .= '#checkout-coupon-popup form.checkout_coupon .gem-button {border-width:'.$params['btn_border_width'].'px !important;}';
			$btn_line_height = intval(40 - $params['btn_border_width']*2);
			$custom_css .= '#checkout-coupon-popup form.checkout_coupon .gem-button {line-height: '.$btn_line_height.'px !important;}';
		}
		if (!empty($params['btn_border_radius']) || $params['btn_border_radius'] === '0') {
			$custom_css .= '#checkout-coupon-popup form.checkout_coupon .gem-button {border-radius:'.$params['btn_border_radius'].'px !important;}';
		}
		if (!empty($params['btn_text_color'])) {
			$custom_css .= '#checkout-coupon-popup form.checkout_coupon .gem-button {color:'.$params['btn_text_color'].'!important;}';
		}
		if (!empty($params['btn_text_color_hover'])) {
			$custom_css .= '#checkout-coupon-popup form.checkout_coupon .gem-button:hover {color:'.$params['btn_text_color_hover'].'!important;}';
		}
		if (!empty($params['btn_background_color'])) {
			$custom_css .= '#checkout-coupon-popup form.checkout_coupon .gem-button {background-color:'.$params['btn_background_color'].'!important;}';
		}
		if (!empty($params['btn_background_color_hover'])) {
			$custom_css .= '#checkout-coupon-popup form.checkout_coupon .gem-button:hover {background-color:'.$params['btn_background_color_hover'].'!important;}';
		}
		if (!empty($params['btn_border_color'])) {
			$custom_css .= '#checkout-coupon-popup form.checkout_coupon .gem-button {border-color:'.$params['btn_border_color'].'!important;}';
		}
		if (!empty($params['btn_border_color_hover'])) {
			$custom_css .= '#checkout-coupon-popup form.checkout_coupon .gem-button:hover {border-color:'.$params['btn_border_color_hover'].'!important;}';
		}
		if (!empty($params['inline'])) {
			$custom_css .= $customize.' {display: inline-block; vertical-align: middle;}';
		}
		if(!empty($params['inline']) && !empty($params['separator']) && (intval($params['separator_spacing']) > 0 || $params['separator_spacing'] === '0')) {
			$custom_css .= $customize.' .separator {margin-left: ' . intval($params['separator_spacing']) . 'px !important; margin-right: ' . intval($params['separator_spacing']) . 'px !important;}';
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

	public function shortcode_settings(){
		return array(
			'name' => __('Apply Coupon', 'thegem'),
			'base' => 'thegem_te_checkout_coupon',
			'icon' => 'thegem-icon-wpb-ui-element-checkout-coupon',
			'category' => __('Checkout Builder', 'thegem'),
			'description' => __('Apply Coupon (Checkout Builder)', 'thegem'),
			'params' => array_merge(
				array(
					array(
						'type' => 'textfield',
						'heading' => __('Text', 'thegem'),
						'param_name' => 'text',
						'std' => __( 'Have a coupon?', 'woocommerce' ),
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Link Text', 'thegem'),
						'param_name' => 'link_text',
						'std' => __( 'Click here to enter your code', 'woocommerce' ),
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'text_color',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Link Color', 'thegem'),
						'param_name' => 'link_color',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Link Hover Color', 'thegem'),
						'param_name' => 'link_hover_color',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Inline element', 'thegem'),
						'param_name' => 'inline',
						'value' => array(__('Yes', 'thegem') => '1'),
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Separator', 'thegem'),
						'param_name' => 'separator',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'inline',
							'value' => '1'
						),
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Separator Position', 'thegem'),
						'param_name' => 'separator_position',
							'value' => array_merge(array(
								__('Left', 'thegem') => 'left',
								__('Right', 'thegem') => 'right',
							)
						),
						'dependency' => array(
							'element' => 'separator',
							'value' => '1'
						),
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Separator Spacing', 'thegem'),
						'param_name' => 'separator_spacing',
						'dependency' => array(
							'element' => 'separator',
							'value' => '1'
						),
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Popup', 'thegem'),
						'param_name' => 'popup_styles_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Popup Background Color', 'thegem'),
						'param_name' => 'popup_background_color',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Coupon Form Input', 'thegem'),
						'param_name' => 'apply_coupon_input_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Input Text Color', 'thegem'),
						'param_name' => 'input_text_color',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Input Background Color', 'thegem'),
						'param_name' => 'input_background_color',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Input Border Color', 'thegem'),
						'param_name' => 'input_border_color',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Placeholder Text Color', 'thegem'),
						'param_name' => 'placeholder_text_color',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Input Border Radius', 'thegem'),
						'param_name' => 'input_border_radius',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Coupon Form Button', 'thegem'),
						'param_name' => 'apply_coupon_btn_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'btn_border_width',
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
						'group' => __('General', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-5',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Radius', 'thegem'),
						'param_name' => 'btn_border_radius',
						'value' => '',
						'group' => __('General', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'btn_text_color',
						'std' => '',
						'group' => __('General', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'btn_background_color',
						'std' => '',
						'group' => __('General', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'btn_border_color',
						'std' => '',
						'group' => __('General', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color on Hover', 'thegem'),
						'param_name' => 'btn_text_color_hover',
						'std' => '',
						'group' => __('General', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color on Hover', 'thegem'),
						'param_name' => 'btn_background_color_hover',
						'std' => '',
						'group' => __('General', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color on Hover', 'thegem'),
						'param_name' => 'btn_border_color_hover',
						'std' => '',
						'group' => __('General', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
					),
				),
				/* Extra Options */
				thegem_set_elements_extra_options()
			),
		);
	}
}

$templates_elements['thegem_te_checkout_coupon'] = new TheGem_Template_Element_Checkout_Coupon();
$templates_elements['thegem_te_checkout_coupon']->init();
