<?php

class TheGem_Template_Element_Portfolio_Title extends TheGem_Portfolio_Item_Template_Element {
	
	public function __construct() {
	}
	
	public function get_name() {
		return 'thegem_te_portfolio_title';
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
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_portfolio_title');
  
		// Init Title
		ob_start();
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$portfolio = thegem_templates_init_portfolio();
		
		if (empty($portfolio)) {
			ob_end_clean();
			return thegem_templates_close_portfolio($this->get_name(), $this->shortcode_settings(), '');
		}
		
		$params['element_class'] = implode(' ', array($params['element_class'], thegem_templates_responsive_options_output($params)));
		$params['text_styled'] = implode(' ', array($params['text_style'], $params['text_font_weight']));
		
		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?= esc_attr($params['element_id']); ?>"<?php endif; ?> class="thegem-te-portfolio-title <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
            <<?= $params['text_tag'] ?> class="portfolio-title <?= $params['text_styled'] ?>">
                <?= get_the_title($portfolio); ?>
            </<?= $params['text_tag'] ?>>
        </div>
		
		<?php
		//Custom Styles
		$customize = '.thegem-te-portfolio-title.'.$uniqid;
		$custom_css = '';
		
		// Layout Styles
		if (!empty($params['alignment'])) {
			$custom_css .= $customize.' {justify-content: ' . $params['alignment'] . '; text-align: ' . $params['alignment'] . ';}';
		}
		if (!empty($params['max_width'])) {
			$custom_css .= $customize.' .portfolio-title {max-width: ' . $params['max_width'] . 'px;}';
		}
		
		// Text Styles
		if ($params['text_letter_spacing'] != '') {
			$custom_css .= $customize.' .portfolio-title {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
		}
		if ($params['text_transform'] != '') {
			$custom_css .= $customize.' .portfolio-title {text-transform: ' . $params['text_transform'] . ';}';
		}
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .portfolio-title {color: ' . $params['text_color'] . ';}';
		}
		
		// Custom Font Styles
		if (!empty($params['use_custom_font_size'])) {
			if (!empty($params['custom_font_size'])) {
				$custom_css .= $customize.' .portfolio-title {font-size: '.esc_attr($params['custom_font_size']).'px;}';
			}
			if (!empty($params['custom_line_height'])) {
				$custom_css .= $customize.' .portfolio-title {line-height: '.esc_attr($params['custom_line_height']).'px;}';
			}
			if(!empty($params['custom_letter_spacing']) || strcmp($params['custom_letter_spacing'], '0') === 0) {
				$custom_css .= $customize.' .portfolio-title {letter-spacing: '.esc_attr($params['custom_letter_spacing']).'px;}';
			}
			if (!empty($params['custom_text_transform'])) {
				$custom_css .= $customize.' .portfolio-title {text-transform: '.esc_attr($params['custom_text_transform']).';}';
			}
		}
		if (!empty($params['use_custom_responsive_font'])) {
			if (!empty($params['custom_font_size_tablet'])) {
				$custom_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {
				    '.$customize.' .portfolio-title {font-size: '.esc_attr($params['custom_font_size_tablet']).'px;
				}}';
			}
			if (!empty($params['custom_line_height_tablet'])) {
				$custom_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {
				    '.$customize.' .portfolio-title {line-height: '.esc_attr($params['custom_line_height_tablet']).'px;
				}}';
			}
			if(!empty($params['custom_letter_spacing_tablet']) || strcmp($params['custom_letter_spacing_tablet'], '0') === 0 ) {
				$custom_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {
				    '.$customize.' .portfolio-title {letter-spacing: '.esc_attr($params['custom_letter_spacing_tablet']).'px;
				}}';
			}
			if (!empty($params['custom_font_size_mobile'])) {
				$custom_css .= '@media screen and (max-width: 767px) {
				    '.$customize.' .portfolio-title {font-size: '.esc_attr($params['custom_font_size_mobile']).'px;
				}}';
			}
			if (!empty($params['custom_line_height_mobile'])) {
				$custom_css .= '@media screen and (max-width: 767px) {
				    '.$customize.' .portfolio-title {line-height: '.esc_attr($params['custom_line_height_mobile']).'px;
				}}';
			}
			if(!empty($params['custom_letter_spacing_mobile']) || strcmp($params['custom_letter_spacing_mobile'], '0') === 0) {
				$custom_css .= '@media screen and (max-width: 767px) {
				    '.$customize.' .portfolio-title {letter-spacing: '.esc_attr($params['custom_letter_spacing_mobile']).'px;
				}}';
			}
		}
		if (!empty($params['use_custom_font_size']) && !empty($params['use_custom_google_font'])) {
			$font = thegem_font_parse($params['custom_google_fonts']);
			$custom_css .= $customize.' .portfolio-title .light, '.$customize.' .portfolio-title {'.esc_attr($font).'}';
		}
  
		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
		
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
			'description' => __('To style default blog title typography go to <a href="'.get_site_url().'/wp-admin/admin.php?page=thegem-theme-options#/typography/headings-and-body" target="_blank">Theme Options → Typography</a>.', 'thegem'),
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
			'name' => __('Title', 'thegem'),
			'base' => 'thegem_te_portfolio_title',
			'icon' => 'thegem-icon-wpb-ui-element-portfolio-title',
			'category' => __('Portfolio Page Builder', 'thegem'),
			'description' => __('Title (Portfolio Page Builder)', 'thegem'),
			'params' => array_merge(
			
			    /* General - Layout */
				$this->set_layout_params(),
                
                /* Style - Custom Style */
				$this->set_custom_font_params(),
				
				/* Extra Options */
				thegem_set_elements_extra_options(),
				
				/* Responsive Options */
				thegem_set_elements_responsive_options()
			),
		);
	}
}

$templates_elements['thegem_te_portfolio_title'] = new TheGem_Template_Element_Portfolio_Title();
$templates_elements['thegem_te_portfolio_title']->init();
