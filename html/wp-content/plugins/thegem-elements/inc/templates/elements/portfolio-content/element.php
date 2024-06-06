<?php

class TheGem_Template_Element_Portfolio_Content extends TheGem_Portfolio_Item_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_portfolio_content';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'alignment' => 'left',
			'max_width' => '',
			'text_style' => '',
			'text_font_weight' => '',
			'text_font_style' => '',
			'text_letter_spacing' => '',
			'text_transform' => '',
			'text_color' => '',
		),
			thegem_templates_extra_options_extract()
		), $atts, 'thegem_te_portfolio_content');

		// Init Content
		ob_start();
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$portfolio = thegem_templates_init_portfolio();

		if (empty($portfolio) || empty(get_the_content())) {
			ob_end_clean();
			return thegem_templates_close_portfolio($this->get_name(), $this->shortcode_settings(), '');
		}

		$params['text_styled'] = implode(' ', array($params['text_style'], $params['text_font_weight']));

		thegem_single_post_page_content();

		//Custom Styles
		$customize = '.thegem-te-portfolio-content.'.$uniqid;
		$custom_css = '';

		// Layout Styles
		if (!empty($params['alignment'])) {
			$custom_css .= $customize.' {justify-content: ' . $params['alignment'] . '; text-align: ' . $params['alignment'] . ';}';
		}
		if (!empty($params['max_width'])) {
			$custom_css .= $customize.' .portfolio-content {max-width: ' . $params['max_width'] . 'px;}';
		}

		// Text Styles
		if ($params['text_letter_spacing'] != '') {
			$custom_css .= $customize.' .portfolio-content {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
		}
		if ($params['text_transform'] != '') {
			$custom_css .= $customize.' .portfolio-content {text-transform: ' . $params['text_transform'] . ';}';
		}
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .portfolio-content {color: ' . $params['text_color'] . ';}';
		}

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		if(empty($return_html)) {
			return thegem_templates_close_single_post($this->get_name(), $this->shortcode_settings(), $return_html);
		}

		$return_html = '<div'.(!empty($params['element_id']) ? ' id="'.esc_attr($params['element_id']).'"' : '').' class="thegem-te-portfolio-content '.esc_attr($params['element_class']).' '.esc_attr($uniqid).'"><div class="portfolio-content '.$params['text_styled'].'">'.$return_html.'</div></div>';


		$custom_css .= get_post_meta(get_the_ID(), '_wpb_shortcodes_custom_css', true) . get_post_meta(get_the_ID(), '_wpb_post_custom_css', true);

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}

		$return_html = $css_output.$return_html;
		return thegem_templates_close_portfolio($this->get_name(), $this->shortcode_settings(), $return_html);
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
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'text_style',
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
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'text_letter_spacing',
			'edit_field_class' => 'vc_column vc_col-sm-6',
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
			'edit_field_class' => 'vc_column vc_col-sm-6',
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
			'description' => __('To style default blog title typography go to <a href="'.get_site_url().'/wp-admin/admin.php?page=thegem-theme-options#/typography/headings-and-body" target="_blank">Theme Options → Typography</a>.', 'thegem'),
			'group' => $group
		);

		return $result;
	}

	public function shortcode_settings() {

		return array(
			'name' => __('Content', 'thegem'),
			'base' => 'thegem_te_portfolio_content',
			'icon' => 'thegem-icon-wpb-ui-element-portfolio-content',
			'category' => __('Portfolio Page Builder', 'thegem'),
			'description' => __('Content (Portfolio Page Builder)', 'thegem'),
			'params' => array_merge(

			    /* General - Layout */
				$this->set_layout_params(),

				/* Extra Options */
				thegem_set_elements_extra_options()
			),
		);
	}
}

$templates_elements['thegem_te_portfolio_content'] = new TheGem_Template_Element_Portfolio_Content();
$templates_elements['thegem_te_portfolio_content']->init();
