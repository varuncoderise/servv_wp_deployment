<?php

class TheGem_Template_Element_Cart extends TheGem_Template_Element {

	public function __construct() {

		if ( !defined('THEGEM_TE_CART_DIR' )) {
			define('THEGEM_TE_CART_DIR', rtrim(__DIR__, ' /\\'));
		}

		if ( !defined('THEGEM_TE_CART_URL') ) {
			define('THEGEM_TE_CART_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}

		wp_register_script('thegem-te-cart', THEGEM_TE_CART_URL . '/js/cart.js', array('jquery'), false, true);
		wp_register_style('thegem-te-cart', THEGEM_TE_CART_URL . '/css/cart.css');
		wp_register_style('thegem-te-cart-editor', THEGEM_TE_CART_URL . '/css/cart-editor.css');
	}

	public function get_name() {
		return 'thegem_te_cart';
	}

	public function head_scripts($attr) {
		$attr = shortcode_atts(array('pack' => 'thegem-header'), $attr, 'thegem-te-wishlist');
		wp_enqueue_style('icons-'.$attr['pack']);

		wp_enqueue_script('thegem-te-cart');
		wp_enqueue_style('thegem-te-cart');
	}

	public function front_editor_scripts() {
		wp_enqueue_style('thegem-te-cart-editor');
	}

	public function is_woocommerce_exist() {
		return thegem_is_plugin_active('woocommerce/woocommerce.php');
	}

	public function shortcode_output($atts, $content = '') {
		if (!$this->is_woocommerce_exist()) return;

		// General params
		$params = shortcode_atts(array_merge(array(
			'pack' => 'thegem-header',
			'icon_elegant' => '',
			'icon_material' => '',
			'icon_fontawesome' => '',
			'icon_thegemdemo' => '',
			'icon_userpack' => '',
			'icon_thegem_header' => '',
			'custom_link' => '',
			'icon_size' => 'small',
			'icon_size_custom' => '',
			'icon_color' => '',
			'icon_color_hover' => '',
			'label_type' => 'circle',
			'label_color' => '',
			'label_background' => '',
			'label_color_hover' => '',
			'label_background_hover' => '',
			'minicart_spacing' => '20',
			'view_type' => wp_is_mobile() ? 'mobile-view' : 'desktop-view',
			'mini_cart_type' => thegem_get_option('mini_cart_type'),
			//Extra
			'element_id' => '',
			'element_class' => '',
			'element_link' => '',
		), thegem_templates_design_options_extract()), $atts, 'thegem_te_cart');

		// Init Design Options Params
		$custom_css = $uniqid = $icon = '';
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-cart', $params);

		//General
		$el_id = $el_class = '';
		if (!empty($params['element_id'])){ $el_id = $params['element_id']; }
		if (!empty($params['element_class'])){ $el_class = $params['element_class']; }


		// Label
		if ($params['label_type'] == 'circle'){
			$label_type = 'circle-count';
		} else {
			$label_type = 'label-count';
		}

		// Init cart
		$count = thegem_get_cart_count();
		ob_start();
		woocommerce_mini_cart();
		$minicart = ob_get_clean();

		ob_start(); ?>

		<div <?php if ($el_id): ?>id="<?=esc_attr($el_id); ?>"<?php endif;?> class="thegem-te-cart <?=esc_attr($label_type)?> <?=$params['view_type']?> <?= esc_attr($el_class); ?> <?= esc_attr($uniqid); ?>" <?=thegem_data_editor_attribute($uniqid . '-editor')?>>
			<div class="menu-item-cart <?=thegem_te_delay_class()?>">
				<a href="<?=esc_url(get_permalink(wc_get_page_id('cart')))?>" class="minicart-menu-link <?php if ($count == 0):?>empty<?php endif; ?>">
					<span class="minicart-item-count"><?=$count?></span>
				</a>

				<?php if (!empty($params['mini_cart_type']) && $params['mini_cart_type'] == 'dropdown'): ?>
                    <div class="minicart">
                        <div class="widget_shopping_cart_content"><?= $minicart ?></div>
                    </div>
                    <div class="mobile-minicart-overlay"></div>
				<?php endif; ?>
			</div>
		</div>

		<?php
		
		// Icon Custom Styles
		$customize = '.thegem-te-cart.'.$uniqid;

		if ($params['pack'] == 'elegant' && $params['icon_elegant']) {
			$custom_css .= $customize.' .minicart-menu-link:before {content: "\\'.$params['icon_elegant'].'"; font-family: "ElegantIcons";}';
		}
		if ($params['pack'] == 'material' && empty($icon) && $params['icon_material']) {
			$custom_css .= $customize.' .minicart-menu-link:before {content: "\\'.$params['icon_material'].'"; font-family: "MaterialDesignIcons";}';
		}
		if ($params['pack'] == 'fontawesome' && empty($icon) && $params['icon_fontawesome']) {
			$custom_css .= $customize.' .minicart-menu-link:before {content: "\\'.$params['icon_fontawesome'].'"; font-family: "FontAwesome";}';
		}
		if ($params['pack'] == 'thegemdemo' && empty($icon) && $params['icon_thegemdemo']) {
			$custom_css .= $customize.' .minicart-menu-link:before {content: "\\'.$params['icon_thegemdemo'].'"; font-family: "TheGemDemoIcons";}';
		}
		if ($params['pack'] == 'userpack' && empty($icon) && $params['icon_userpack']) {
			$custom_css .= $customize.' .minicart-menu-link:before {content: "\\'.$params['icon_userpack'].'"; font-family: "Userpack";}';
		}
		if($params['pack'] == 'thegem-header' && !empty($params['icon_thegem_header'])) {
			$custom_css .= $customize.' .minicart-menu-link:before {content: "\\'.$params['icon_thegem_header'].'"; font-family: "TheGem Header";}';
		}

		if(!empty($params['icon_size_custom']) && $params['icon_size'] == 'custom') {
			$custom_size = $params['icon_size_custom'];
			$custom_css .= $customize.' .minicart-menu-link:before {font-size: '.esc_attr($custom_size).'px;}';
		}

		if(!empty($params['icon_color'])) {
			$custom_css .= $customize.' .minicart-menu-link {color: '.$params['icon_color'].';}';
		}
		if(!empty($params['icon_color_hover'])) {
			$custom_css .= $customize.' .minicart-menu-link:hover {color: '.$params['icon_color_hover'].';}';
		}

		if(!empty($params['label_color'])) {
			$custom_css .= $customize.' .minicart-item-count {color: '.$params['label_color'].';}';
		}
		if(!empty($params['label_background'])) {
			$custom_css .= $customize.' .minicart-item-count {background-color: '.$params['label_background'].';}';
			$custom_css .= $customize.'.label-count .minicart-item-count:after {background-color: '.$params['label_background'].';}';
		}
		if(!empty($params['label_color_hover'])) {
			$custom_css .= $customize.' .minicart-menu-link:hover .minicart-item-count {color: '.$params['label_color_hover'].';}';
		}
		if(!empty($params['label_background_hover'])) {
			$custom_css .= $customize.' .minicart-menu-link:hover .minicart-item-count {background-color: '.$params['label_background_hover'].';}';
			$custom_css .= $customize.'.label-count .minicart-menu-link:hover .minicart-item-count:after {background-color: '.$params['label_background_hover'].';}';
		}

		// Spacing
		if (isset($params['minicart_spacing']) && !empty($params['minicart_spacing'])) {
			$custom_css .= $customize.'.desktop-view .minicart {top: calc(100% + ' . $params['minicart_spacing'] . 'px);}';
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

	public function thegem_te_cart_set_params () {
		if ($this->is_woocommerce_exist()) {
			$params = array_merge(
			    /* General - Icon */
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Icon pack', 'thegem'),
						'param_name' => 'pack',
						'value' => array_merge(array(
							__('Elegant', 'thegem') => 'elegant',
							__('Material Design', 'thegem') => 'material',
							__('FontAwesome', 'thegem') => 'fontawesome',
							__('Header Icons', 'thegem') => 'thegem-header',
							__('Additional', 'thegem') => 'thegemdemo'),
							thegem_userpack_to_dropdown()
						),
						'std' => 'thegem-header',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_thegem_header',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'icon_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover color', 'thegem'),
						'param_name' => 'icon_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Size', 'thegem'),
						'param_name' => 'icon_size',
						'value' => array(
							__('Tiny', 'thegem') => 'tiny',
							__('Small', 'thegem') => 'small',
							__('Medium', 'thegem') => 'medium',
							__('Custom', 'thegem') => 'custom'
						),
						'std' => 'small',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Custom size', 'thegem'),
						'param_name' => 'icon_size_custom',
						'dependency' => array(
							'element' => 'icon_size',
							'value' => array('custom')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					)
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6 no-top-padding',
						'group' => __('General', 'thegem')
					),
				)),

				/* General - Label */
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Label', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Amount label type', 'thegem'),
						'param_name' => 'label_type',
						'value' => array(
							__('Circle', 'thegem') => 'circle',
							__('Label', 'thegem') => 'label',
						),
						'std' => 'circle',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Label text color', 'thegem'),
						'param_name' => 'label_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Label background color', 'thegem'),
						'param_name' => 'label_background',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Label text color hover', 'thegem'),
						'param_name' => 'label_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Label background color hover', 'thegem'),
						'param_name' => 'label_background_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
				),

				/* General - Spacing */
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Spacing', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Mini Cart Spacing', 'thegem'),
						'param_name' => 'minicart_spacing',
						'std' => 20,
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('General', 'thegem')
					),
				),

				/* General - Extra */
				thegem_set_elements_extra_options(),

				thegem_set_elements_design_options()
			);
		} else {
			$params = array(
				array(
					'type' => 'thegem_delimeter_heading',
					'param_name' => 'layout_delim_head',
					'edit_field_class' => 'vc_column vc_col-sm-12',
					'description' => __('<div class="thegem-param-alert">You need to install WooCommerce.<br/> <a href="'.get_site_url().'/wp-admin/plugins.php" target="_blank">Go to install plugins page.</a></div>', 'thegem'),
				)
			);
		}

		return $params;
    }

	public function shortcode_settings() {
		return array(
			'name' => __('Cart', 'thegem'),
			'base' => 'thegem_te_cart',
			'icon' => 'thegem-icon-wpb-ui-element-cart',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('Cart Icon (Header Builder)', 'thegem'),
			'params' => $this->thegem_te_cart_set_params()
		);
	}
}

$templates_elements['thegem_te_cart'] = new TheGem_Template_Element_Cart();
$templates_elements['thegem_te_cart']->init();
