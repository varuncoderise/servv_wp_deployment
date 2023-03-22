<?php

class TheGem_Template_Element_Product_Archive_Title extends TheGem_Product_Archive_Template_Element {
	
	public function __construct() {
	}
	
	public function get_name() {
		return 'thegem_te_product_archive_title';
	}
	
	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'alignment' => 'left',
			'max_width' => '',
			'text_tag' => 'h1',
			'text_style' => '',
			'text_font_weight' => '',
			'text_font_style' => '',
			'text_letter_spacing' => '',
			'text_transform' => '',
			'text_color' => '',
			
			'use_custom_font_size' => '0',
			'custom_font_size' => '',
			'custom_line_height' => '',
			'custom_letter_spacing' => '',
			'custom_text_transform' => '',
			'use_custom_responsive_font' => '0',
			'custom_font_size_tablet' => '',
			'custom_line_height_tablet' => '',
			'custom_letter_spacing_tablet' => '',
			'custom_font_size_mobile' => '',
			'custom_line_height_mobile' => '',
			'custom_letter_spacing_mobile' => '',
			'use_custom_google_font' => '0',
			'custom_google_fonts' => '',
			'desktop_padding_top' => '',
			'desktop_padding_bottom' => '',
			'desktop_padding_left' => '',
			'desktop_padding_right' => '',
			'tablet_padding_top' => '',
			'tablet_padding_bottom' => '',
			'tablet_padding_left' => '',
			'tablet_padding_right' => '',
			'mobile_padding_top' => '',
			'mobile_padding_bottom' => '',
			'mobile_padding_left' => '',
			'mobile_padding_right' => '',
			'desktop_margin_top' => '',
			'desktop_margin_bottom' => '',
			'desktop_margin_left' => '',
			'desktop_margin_right' => '',
			'tablet_margin_top' => '',
			'tablet_margin_bottom' => '',
			'tablet_margin_left' => '',
			'tablet_margin_right' => '',
			'mobile_margin_top' => '',
			'mobile_margin_bottom' => '',
			'mobile_margin_left' => '',
			'mobile_margin_right' => '',
		),
			thegem_templates_extra_options_extract()
		), $atts, 'thegem_te_product_archive_title');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		
		// Init Title
		ob_start();
		$term = thegem_templates_product_archive_source();
		
		if (empty($term) || empty($term->name)) {
			ob_end_clean();
			return thegem_templates_close_product_archive($this->get_name(), $this->shortcode_settings(), '');
		}

		$title = $term->name;
		if(is_post_type_archive('product')) {
			$shop_page_id = wc_get_page_id('shop');
			if($shop_page_id > 0 && get_post($shop_page_id)) {
				$title = get_the_title($shop_page_id);
			} else {
				$title = $term->label;
			}
		}

		$text_styled_class = thegem_te_product_text_styled($params);
		
		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-product-archive-title <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
        
            <<?= $params['text_tag'] ?> class="product-title <?= $text_styled_class ?>">
		        <?= $title ?>
            </<?= $params['text_tag'] ?>>
        </div>
		
		<?php
		//Custom Styles
		$customize = '.thegem-te-product-archive-title.'.$uniqid;
		$custom_css = '';
		
		// Layout Styles
		if (!empty($params['alignment'])) {
			$custom_css .= $customize.' {justify-content: ' . $params['alignment'] . '; text-align: ' . $params['alignment'] . ';}';
		}
		if (!empty($params['max_width'])) {
			$custom_css .= $customize.' .product-title {max-width: ' . $params['max_width'] . 'px;}';
		}
		
		// Text Styles
		if ($params['text_letter_spacing'] != '') {
			$custom_css .= $customize.' .product-title {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
		}
		if ($params['text_transform'] != '') {
			$custom_css .= $customize.' .product-title {text-transform: ' . $params['text_transform'] . ';}';
		}
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .product-title {color: ' . $params['text_color'] . ';}';
		}
		
		// Custom Font Styles
		if (!empty($params['use_custom_font_size'])) {
			if (!empty($params['custom_font_size'])) {
				$custom_css .= $customize.' .product-title {font-size: '.esc_attr($params['custom_font_size']).'px;}';
			}
			if (!empty($params['custom_line_height'])) {
				$custom_css .= $customize.' .product-title {line-height: '.esc_attr($params['custom_line_height']).'px;}';
			}
			if(!empty($params['custom_letter_spacing']) || strcmp($params['custom_letter_spacing'], '0') === 0) {
				$custom_css .= $customize.' .product-title {letter-spacing: '.esc_attr($params['custom_letter_spacing']).'px;}';
			}
			if (!empty($params['custom_text_transform'])) {
				$custom_css .= $customize.' .product-title {text-transform: '.esc_attr($params['custom_text_transform']).';}';
			}
		}
		if (!empty($params['use_custom_responsive_font'])) {
			if (!empty($params['custom_font_size_tablet'])) {
				$custom_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {
				    '.$customize.' .product-title {font-size: '.esc_attr($params['custom_font_size_tablet']).'px;
				}}';
			}
			if (!empty($params['custom_line_height_tablet'])) {
				$custom_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {
				    '.$customize.' .product-title {line-height: '.esc_attr($params['custom_line_height_tablet']).'px;
				}}';
			}
			if(!empty($params['custom_letter_spacing_tablet']) || strcmp($params['custom_letter_spacing_tablet'], '0') === 0 ) {
				$custom_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {
				    '.$customize.' .product-title {letter-spacing: '.esc_attr($params['custom_letter_spacing_tablet']).'px;
				}}';
			}
			if (!empty($params['custom_font_size_mobile'])) {
				$custom_css .= '@media screen and (max-width: 767px) {
				    '.$customize.' .product-title {font-size: '.esc_attr($params['custom_font_size_mobile']).'px;
				}}';
			}
			if (!empty($params['custom_line_height_mobile'])) {
				$custom_css .= '@media screen and (max-width: 767px) {
				    '.$customize.' .product-title {line-height: '.esc_attr($params['custom_line_height_mobile']).'px;
				}}';
			}
			if(!empty($params['custom_letter_spacing_mobile']) || strcmp($params['custom_letter_spacing_mobile'], '0') === 0) {
				$custom_css .= '@media screen and (max-width: 767px) {
				    '.$customize.' .product-title {letter-spacing: '.esc_attr($params['custom_letter_spacing_mobile']).'px;
				}}';
			}
		}
		if (!empty($params['use_custom_font_size']) && !empty($params['use_custom_google_font'])) {
			$font = thegem_font_parse($params['custom_google_fonts']);
			$custom_css .= $customize.' .product-title .light, '.$customize.' .product-title {'.esc_attr($font).'}';
		}

		$padding_styles = '';
		$padding_tablet_styles = '';
		$padding_mobile_styles = '';
		if(intval($params['desktop_padding_top']) > 0 || $params['desktop_padding_bottom'] === '0') {
			$padding_styles .= 'padding-top: '.intval($params['desktop_padding_top']).'px;';
		}
		if(intval($params['desktop_padding_bottom']) > 0 || $params['desktop_padding_bottom'] === '0') {
			$padding_styles .= 'padding-bottom: '.intval($params['desktop_padding_bottom']).'px;';
		}
		if(intval($params['desktop_padding_left']) > 0 || $params['desktop_padding_left'] === '0') {
			$padding_styles .= 'padding-left: '.intval($params['desktop_padding_left']).'px;';
		}
		if(intval($params['desktop_padding_right']) > 0 || $params['desktop_padding_right'] === '0') {
			$padding_styles .= 'padding-right: '.intval($params['desktop_padding_right']).'px;';
		}

		if(intval($params['tablet_padding_left']) > 0 || $params['tablet_padding_left'] === '0') {
			$padding_tablet_styles .= 'padding-top: '.intval($params['tablet_padding_left']).'px;';
		}
		if(intval($params['tablet_padding_right']) > 0 || $params['tablet_padding_right'] === '0') {
			$padding_tablet_styles .= 'padding-bottom: '.intval($params['tablet_padding_right']).'px;';
		}
		if(intval($params['tablet_padding_left']) > 0 || $params['tablet_padding_left'] === '0') {
			$padding_tablet_styles .= 'padding-left: '.intval($params['tablet_padding_left']).'px;';
		}
		if(intval($params['tablet_padding_right']) > 0 || $params['tablet_padding_right'] === '0') {
			$padding_tablet_styles .= 'padding-right: '.intval($params['tablet_padding_right']).'px;';
		}

		if(intval($params['mobile_padding_left']) > 0 || $params['mobile_padding_left'] === '0') {
			$padding_mobile_styles .= 'padding-top: '.intval($params['mobile_padding_left']).'px;';
		}
		if(intval($params['mobile_padding_right']) > 0 || $params['mobile_padding_right'] === '0') {
			$padding_mobile_styles .= 'padding-bottom: '.intval($params['mobile_padding_right']).'px;';
		}
		if(intval($params['mobile_padding_left']) > 0 || $params['mobile_padding_left'] === '0') {
			$padding_mobile_styles .= 'padding-left: '.intval($params['mobile_padding_left']).'px;';
		}
		if(intval($params['mobile_padding_right']) > 0 || $params['mobile_padding_right'] === '0') {
			$padding_mobile_styles .= 'padding-right: '.intval($params['mobile_padding_right']).'px;';
		}

		if(intval($params['desktop_margin_top']) > 0 || $params['desktop_margin_bottom'] === '0') {
			$padding_styles .= 'margin-top: '.intval($params['desktop_margin_top']).'px;';
		}
		if(intval($params['desktop_margin_bottom']) > 0 || $params['desktop_margin_bottom'] === '0') {
			$padding_styles .= 'margin-bottom: '.intval($params['desktop_margin_bottom']).'px;';
		}
		if(intval($params['desktop_margin_left']) > 0 || $params['desktop_margin_left'] === '0') {
			$padding_styles .= 'margin-left: '.intval($params['desktop_margin_left']).'px;';
		}
		if(intval($params['desktop_margin_right']) > 0 || $params['desktop_margin_right'] === '0') {
			$padding_styles .= 'margin-right: '.intval($params['desktop_margin_right']).'px;';
		}

		if(intval($params['tablet_margin_left']) > 0 || $params['tablet_margin_left'] === '0') {
			$padding_tablet_styles .= 'margin-top: '.intval($params['tablet_margin_left']).'px;';
		}
		if(intval($params['tablet_margin_right']) > 0 || $params['tablet_margin_right'] === '0') {
			$padding_tablet_styles .= 'margin-bottom: '.intval($params['tablet_margin_right']).'px;';
		}
		if(intval($params['tablet_margin_left']) > 0 || $params['tablet_margin_left'] === '0') {
			$padding_tablet_styles .= 'margin-left: '.intval($params['tablet_margin_left']).'px;';
		}
		if(intval($params['tablet_margin_right']) > 0 || $params['tablet_margin_right'] === '0') {
			$padding_tablet_styles .= 'margin-right: '.intval($params['tablet_margin_right']).'px;';
		}

		if(intval($params['mobile_margin_left']) > 0 || $params['mobile_margin_left'] === '0') {
			$padding_mobile_styles .= 'margin-top: '.intval($params['mobile_margin_left']).'px;';
		}
		if(intval($params['mobile_margin_right']) > 0 || $params['mobile_margin_right'] === '0') {
			$padding_mobile_styles .= 'margin-bottom: '.intval($params['mobile_margin_right']).'px;';
		}
		if(intval($params['mobile_margin_left']) > 0 || $params['mobile_margin_left'] === '0') {
			$padding_mobile_styles .= 'margin-left: '.intval($params['mobile_margin_left']).'px;';
		}
		if(intval($params['mobile_margin_right']) > 0 || $params['mobile_margin_right'] === '0') {
			$padding_mobile_styles .= 'margin-right: '.intval($params['mobile_margin_right']).'px;';
		}

		$padding_custom_css ='';
		if(!empty($padding_styles)) {
			$padding_custom_css .= $customize.' {'.$padding_styles.'}';
		}
		if(!empty($padding_tablet_styles)) {
			$padding_custom_css .='@media (max-width: 992px) {'.$customize.' {'.$padding_tablet_styles.'}}';
		}
		if(!empty($padding_mobile_styles)) {
			$padding_custom_css .='@media (max-width: 767px) {'.$customize.' {'.$padding_mobile_styles.'}}';
		}

		$custom_css .= $padding_custom_css;

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
		
		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}
		
		$return_html = $css_output.$return_html;
		return thegem_templates_close_product_archive($this->get_name(), $this->shortcode_settings(), $return_html);
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
			'heading' => __('Alignment', 'thegem'),
			'param_name' => 'alignment',
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
			'type' => 'textfield',
			'heading' => __('Max Width', 'thegem'),
			'param_name' => 'max_width',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('HTML Tag', 'thegem'),
			'param_name' => 'text_tag',
			'value' => array(
				__('H1', 'thegem') => 'h1',
				__('H2', 'thegem') => 'h2',
				__('H3', 'thegem') => 'h3',
				__('H4', 'thegem') => 'h4',
				__('H5', 'thegem') => 'h5',
				__('H6', 'thegem') => 'h6',
				__('p', 'thegem') => 'p',
				__('div', 'thegem') => 'div'
			),
			'std' => 'h1',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'text_style',
			'value' => array(
				__('Default', 'thegem') => 'title-default',
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
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'text_font_weight',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Thin', 'thegem') => 'light',
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'text_letter_spacing',
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Transform', 'thegem'),
			'param_name' => 'text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'text_color',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'description' => __('To style default product title typography go to <a href="'.get_site_url().'/wp-admin/admin.php?page=thegem-theme-options#/woocommerce/product-styles" target="_blank">Theme Options → WooCommerce → Elements Styles → Product Title</a>.', 'thegem'),
			'group' => $group
		);
		
		return $result;
	}
	
	public function set_custom_font_params() {
		$result = array();
		$group = __('Style', 'thegem');
		
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Use custom font size?', 'thegem'),
			'param_name' => 'use_custom_font_size',
			'value' => array(__('Yes', 'thegem') => '1'),
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Font size', 'thegem'),
			'param_name' => 'custom_font_size',
			'dependency' => array(
				'element' => 'use_custom_font_size',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Line height', 'thegem'),
			'param_name' => 'custom_line_height',
			'dependency' => array(
				'element' => 'use_custom_font_size',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter spacing', 'thegem'),
			'param_name' => 'custom_letter_spacing',
			'dependency' => array(
				'element' => 'use_custom_font_size',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text transform', 'thegem'),
			'param_name' => 'custom_text_transform',
			'value' => array(
				__('None', 'thegem') => 'none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase'
			),
			'dependency' => array(
				'element' => 'use_custom_font_size',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Responsive font size options', 'thegem'),
			'param_name' => 'use_custom_responsive_font',
			'value' => array(__('Yes', 'thegem') => '1'),
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Font size tablet', 'thegem'),
			'param_name' => 'custom_font_size_tablet',
			'dependency' => array(
				'element' => 'use_custom_responsive_font',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Line height tablet', 'thegem'),
			'param_name' => 'custom_line_height_tablet',
			'dependency' => array(
				'element' => 'use_custom_responsive_font',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter spacing tablet', 'thegem'),
			'param_name' => 'custom_letter_spacing_tablet',
			'dependency' => array(
				'element' => 'use_custom_responsive_font',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Font size mobile', 'thegem'),
			'param_name' => 'custom_font_size_mobile',
			'dependency' => array(
				'element' => 'use_custom_responsive_font',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Line height mobile', 'thegem'),
			'param_name' => 'custom_line_height_mobile',
			'dependency' => array(
				'element' => 'use_custom_responsive_font',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter spacing mobile', 'thegem'),
			'param_name' => 'custom_letter_spacing_mobile',
			'dependency' => array(
				'element' => 'use_custom_responsive_font',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Use Google fonts?', 'thegem'),
			'param_name' => 'use_custom_google_font',
			'value' => array(__('Yes', 'thegem') => '1'),
			'group' => $group
		);
		$result[] = array(
			'type' => 'google_fonts',
			'param_name' => 'custom_google_fonts',
			'value' => '',
			'settings' => array(
				'fields' => array(
					'font_family_description' => esc_html__('Select font family.', 'thegem'),
					'font_style_description' => esc_html__('Select font styling.', 'thegem'),
				),
			),
			'dependency' => array(
				'element' => 'use_custom_google_font',
				'value' => '1'
			),
			'group' => $group
		);
		
		return $result;
	}
	
	public function shortcode_settings() {
		
		return array(
			'name' => __('Archive Title', 'thegem'),
			'base' => 'thegem_te_product_archive_title',
			'icon' => 'thegem-icon-wpb-ui-element-product-title',
			'category' => __('Archive Product Builder', 'thegem'),
			'description' => __('Archive Title (Archive Product Builder)', 'thegem'),
			'params' => array_merge(
			
			    /* General - Layout */
				$this->set_layout_params(),
				
				/* Style - Custom Style */
				$this->set_custom_font_params(),

				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Padding', 'thegem'),
						'param_name' => 'padding_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
						'group' => 'General',
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'padding_desktop_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'desktop_padding_top',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'desktop_padding_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'desktop_padding_left',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'desktop_padding_right',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'padding_tablet_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'tablet_padding_top',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'tablet_padding_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'tablet_padding_left',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'tablet_padding_right',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'padding_mobile_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'mobile_padding_top',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'mobile_padding_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'mobile_padding_left',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'mobile_padding_right',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),

					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Margin', 'thegem'),
						'param_name' => 'padding_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
						'group' => 'General',
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'padding_desktop_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'desktop_margin_top',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'desktop_margin_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'desktop_margin_left',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'desktop_margin_right',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'padding_tablet_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'tablet_margin_top',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'tablet_margin_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'tablet_margin_left',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'tablet_margin_right',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'padding_mobile_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'mobile_margin_top',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'mobile_margin_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'mobile_margin_left',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'mobile_margin_right',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => 'General',
					),
				),

				/* Extra Options */
				thegem_set_elements_extra_options()
			),
		);
	}
}

$templates_elements['thegem_te_product_archive_title'] = new TheGem_Template_Element_Product_Archive_Title();
$templates_elements['thegem_te_product_archive_title']->init();
