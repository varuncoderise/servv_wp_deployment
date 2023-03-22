<?php

class TheGem_Template_Element_Cart_Checkout_Steps extends TheGem_Cart_Template_Element {

	public function __construct() {
	}

	public function shortcode_activate($shortcodes) {
		global $pagenow;
		if((is_admin() && in_array($pagenow, array('post-new.php', 'post.php', 'admin-ajax.php'))) || (!is_admin() && in_array($pagenow, array('index.php')))) {
			$activate = 0;
			if($pagenow === 'post-new.php' && !empty($_REQUEST['post_type']) && $_REQUEST['post_type'] === 'thegem_templates') {
				$activate = true;
			}
			if($pagenow === 'post.php' && !empty($_REQUEST['post']) && get_post_type($_REQUEST['post']) === 'thegem_templates' && (thegem_get_template_type($_REQUEST['post']) == 'cart' || thegem_get_template_type($_REQUEST['post']) == 'checkout' || thegem_get_template_type($_REQUEST['post']) == 'checkout-thanks')) {
				$activate = true;
			}
			if($pagenow === 'post.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && (thegem_get_template_type($_REQUEST['post_id']) == 'cart' || thegem_get_template_type($_REQUEST['post_id']) == 'checkout' || thegem_get_template_type($_REQUEST['post_id']) == 'checkout-thanks')) {
				$activate = true;
			}
			if($pagenow === 'admin-ajax.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && (thegem_get_template_type($_REQUEST['post_id']) == 'cart' || thegem_get_template_type($_REQUEST['post_id']) == 'checkout' || thegem_get_template_type($_REQUEST['post_id']) == 'checkout-thanks')) {
				$activate = true;
			}
			if($pagenow === 'index.php' && !empty($_REQUEST['vc_post_id']) && get_post_type($_REQUEST['vc_post_id']) === 'thegem_templates' && (thegem_get_template_type($_REQUEST['vc_post_id']) == 'cart' || thegem_get_template_type($_REQUEST['vc_post_id']) == 'checkout' || thegem_get_template_type($_REQUEST['vc_post_id']) == 'checkout-thanks')) {
				$activate = true;
			}
			if($activate) {
				$shortcodes[$this->get_name()] = $this->shortcode_settings();
			}
		}
		return $shortcodes;
	}

	public function get_name() {
		return 'thegem_te_cart_checkout_steps';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
            'style' => 'default',
            'custom_selection' => '',
            'steps_color' => '',
            'steps_color_active' => '',
            'divider_color' => '',
			'steps_text_style' => '',
			'steps_font_weight' => '',
			'steps_letter_spacing' => '',
			'steps_text_transform' => '',
            'step_1_text' => '',
            'step_2_text' => '',
            'step_3_text' => '',
		),
			thegem_templates_extra_options_extract()
		), $atts, 'thegem_te_cart_checkout_steps');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		ob_start();
        
		$steps_classes = !empty($params['custom_selection']) && !empty($params['steps_text_style']) ? $params['steps_text_style'] : null;
  
		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-cart-checkout-steps <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
			<?= thegem_data_editor_attribute($uniqid . '-editor') ?>>
		
		<?php if ($params['style'] == 'default'): ?>
            <div class="woocommerce-cart-checkout-steps woocommerce-cart-checkout-steps-title cart-checkout-steps--builder">
                <div class="step step-cart <?= (!empty($steps_classes) ? $steps_classes : 'title-h2') ?> <?= (is_cart() || thegem_get_template_type(get_the_ID()) === 'cart' || get_post_meta(get_the_ID(), 'thegem_is_cart', true) ? 'active' : 'light'); ?>">
	                <?php !empty($params['step_1_text']) ? esc_html_e($params['step_1_text']) : esc_html_e('Shopping cart', 'thegem'); ?>
                </div>
                <div class="step step-checkout <?= (!empty($steps_classes) ? $steps_classes : 'title-h2') ?> <?php echo ((is_checkout() && !is_wc_endpoint_url('order-received') || thegem_get_template_type(get_the_ID()) === 'checkout' || get_post_meta(get_the_ID(), 'thegem_is_checkout', true)) ? 'active' : 'light'); ?>">
	                <?php !empty($params['step_2_text']) ? esc_html_e($params['step_2_text']) : esc_html_e('Checkout', 'thegem'); ?>
                </div>
                <div class="step step-order-complete <?= (!empty($steps_classes) ? $steps_classes : 'title-h2') ?> <?php echo ((is_checkout() && is_wc_endpoint_url('order-received') || thegem_get_template_type(get_the_ID()) === 'checkout-thanks') ? 'active' : 'light'); ?>">
	                <?php !empty($params['step_3_text']) ? esc_html_e($params['step_3_text']) : esc_html_e('Order complete', 'thegem'); ?>
                </div>
            </div>
		<?php endif; ?>
	
        <?php if ($params['style'] == 'elegant'): ?>
            <div class="woocommerce-cart-checkout-steps woocommerce-cart-checkout-steps-content cart-checkout-steps--builder">
                <div class="step step-cart <?= (!empty($steps_classes) ? $steps_classes : 'title-h6') ?> <?php echo (is_cart() || thegem_get_template_type(get_the_ID()) === 'cart' || get_post_meta(get_the_ID(), 'thegem_is_cart', true) ? 'active' : 'light'); ?>">
	                <?php !empty($params['step_1_text']) ? esc_html_e($params['step_1_text']) : esc_html_e('1. Shopping cart', 'thegem'); ?>
                </div>
                <div class="step step-checkout <?= (!empty($steps_classes) ? $steps_classes : 'title-h6') ?> <?php echo ((is_checkout() && !is_wc_endpoint_url('order-received') || thegem_get_template_type(get_the_ID()) === 'checkout' || get_post_meta(get_the_ID(), 'thegem_is_checkout', true)) ? 'active' : 'light'); ?>">
	                <?php !empty($params['step_2_text']) ? esc_html_e($params['step_2_text']) : esc_html_e('2. Checkout', 'thegem'); ?>
                </div>
                <div class="step step-order-complete <?= (!empty($steps_classes) ? $steps_classes : 'title-h6') ?> <?php echo ((is_checkout() && is_wc_endpoint_url('order-received') || thegem_get_template_type(get_the_ID()) === 'checkout-thanks') ? 'active' : 'light'); ?>">
	                <?php !empty($params['step_3_text']) ? esc_html_e($params['step_3_text']) : esc_html_e('3. Order complete', 'thegem'); ?>
                </div>
            </div>
        <?php endif; ?>
        </div>
        
		<?php
		//Custom Styles
		$customize = '.thegem-te-cart-checkout-steps.'.$uniqid;
		$custom_css = '';
		
		// Steps Styles
		if (!empty($params['steps_color'])) {
			$custom_css .= $customize.' .woocommerce-cart-checkout-steps-content .step {color: ' . $params['steps_color'] . ';}';
			$custom_css .= $customize.' .woocommerce-cart-checkout-steps-title .step {color: ' . $params['steps_color'] . ';}';
		}
		if (!empty($params['steps_color_active'])) {
			$custom_css .= $customize.' .woocommerce-cart-checkout-steps-content .step.active {color: ' . $params['steps_color_active'] . '; border-color: ' . $params['steps_color_active'] . ';}';
			$custom_css .= $customize.' .woocommerce-cart-checkout-steps-title .step.active {color: ' . $params['steps_color_active'] . ';}';
		}
        if (!empty($params['divider_color'])) {
			$custom_css .= $customize.' .woocommerce-cart-checkout-steps-content .step:not(.active) {border-color: ' . $params['divider_color'] . ';}';
		}
		if (!empty($params['steps_letter_spacing'])) {
			$custom_css .= $customize.' .woocommerce-cart-checkout-steps-content .step {letter-spacing: ' . $params['steps_letter_spacing'] . 'px;}';
			$custom_css .= $customize.' .woocommerce-cart-checkout-steps-title .step {letter-spacing: ' . $params['steps_letter_spacing'] . 'px;}';
		}
		if (!empty($params['steps_text_transform'])) {
			$custom_css .= $customize.' .woocommerce-cart-checkout-steps-content .step {text-transform: ' . $params['steps_text_transform'] . ';}';
			$custom_css .= $customize.' .woocommerce-cart-checkout-steps-title .step {text-transform: ' . $params['steps_text_transform'] . ';}';
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
    
    public function getCategoryName() {
	    $result = '';
        
        if (thegem_is_template_post('cart')) {
	        $result = __('Cart Builder', 'thegem');
        }
	
	    if (thegem_is_template_post('checkout')) {
		    $result = __('Checkout Builder', 'thegem');
	    }
	
	    if (thegem_is_template_post('checkout-thanks')) {
		    $result = __('Purchase Summary Builder', 'thegem');
	    }
	
	    return $result;
    }
	
	public function set_layout_params() {
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('General', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Style', 'thegem'),
			'param_name' => 'style',
			'value' => array_merge(array(
					__('Bold', 'thegem') => 'default',
					__('Elegant', 'thegem') => 'elegant',
				)
			),
			'std' => 'default',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Custom Selection', 'thegem'),
			'param_name' => 'custom_selection',
			'value' => array(__('Enable', 'thegem') => '1'),
			'std' => '0',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'steps_text_style',
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
				'element' => 'custom_selection',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'steps_letter_spacing',
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'dependency' => array(
				'element' => 'custom_selection',
				'value' => '1'
			),
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Transform', 'thegem'),
			'param_name' => 'steps_text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'dependency' => array(
				'element' => 'custom_selection',
				'value' => '1'
			),
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'steps_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Active Text Color', 'thegem'),
			'param_name' => 'steps_color_active',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Divider Color', 'thegem'),
			'param_name' => 'divider_color',
			'dependency' => array(
				'element' => 'style',
				'value' => 'elegant'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			"type" => "textfield",
			'heading' => __('Step 1 Text', 'thegem'),
			'param_name' => 'step_1_text',
			"edit_field_class" => "vc_column vc_col-sm-12",
			'group' => $group
		);
  
		$result[] = array(
			"type" => "textfield",
			'heading' => __('Step 2 Text', 'thegem'),
			'param_name' => 'step_2_text',
			"edit_field_class" => "vc_column vc_col-sm-12",
			'group' => $group
		);
  
		$result[] = array(
			"type" => "textfield",
			'heading' => __('Step 3 Text', 'thegem'),
			'param_name' => 'step_3_text',
			"edit_field_class" => "vc_column vc_col-sm-12",
			'group' => $group
		);
		
		return $result;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Checkout Steps', 'thegem'),
			'base' => 'thegem_te_cart_checkout_steps',
			'icon' => 'thegem-icon-wpb-ui-element-cart-checkout-steps',
			'category' => $this->getCategoryName(),
			'description' => __('Checkout Steps', 'thegem'),
			'params' => array_merge(
       
			    /* General - Layout */
				$this->set_layout_params(),
				
				/* Extra Options */
				thegem_set_elements_extra_options()
			),
		);
	}
}

$templates_elements['thegem_te_cart_checkout_steps'] = new TheGem_Template_Element_Cart_Checkout_Steps();
$templates_elements['thegem_te_cart_checkout_steps']->init();
