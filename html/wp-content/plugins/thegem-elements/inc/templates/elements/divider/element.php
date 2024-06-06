<?php

class TheGem_Template_Element_Divider extends TheGem_Template_Element {

	public function __construct() {
		if ( !defined('THEGEM_TE_DIVIDER_URL') ) {
			define('THEGEM_TE_DIVIDER_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}
		wp_register_style('thegem-te-divider', THEGEM_TE_DIVIDER_URL . '/css/divider.css');
	}

	public function get_name() {
		return 'thegem_te_divider';
	}

	public function head_scripts($attr) {
		wp_enqueue_style('thegem-te-divider');
	}

	public function front_editor_scripts() {
		wp_enqueue_style('thegem-te-divider');
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		extract($extract = shortcode_atts(array_merge(array(
			'direction' => 'vertical',
			'vertical_divider_custom_height_desktop' => '50',
			'vertical_divider_custom_height_tablet' => '50',
			'vertical_divider_custom_height_mobile' => '50',
			'horizontal_enable_full_width' => '',
			'horizontal_divider_custom_width_desktop' => '50',
			'horizontal_divider_custom_width_tablet' => '50',
			'horizontal_divider_custom_width_mobile' => '50',
			'style' => 'solid',
			'color' => '',
			'divider_weight' => '1',
			'class_name' => '',
		), thegem_templates_design_options_extract()), $atts, 'thegem_te_divider'));

		$params = array_merge(array(
			'direction' => $direction,
			'vertical_divider_custom_height_desktop' => $vertical_divider_custom_height_desktop,
			'vertical_divider_custom_height_tablet' => $vertical_divider_custom_height_tablet,
			'vertical_divider_custom_height_mobile' => $vertical_divider_custom_height_mobile,
			'horizontal_enable_full_width' => $horizontal_enable_full_width,
			'horizontal_divider_custom_width_desktop' => $horizontal_divider_custom_width_desktop,
			'horizontal_divider_custom_width_tablet' => $horizontal_divider_custom_width_tablet,
			'horizontal_divider_custom_width_mobile' => $horizontal_divider_custom_width_mobile,
			'style' => $style,
			'color' => $color,
			'divider_weight' => $divider_weight,
			'class_name' => $class_name,
		), thegem_templates_design_options_output($extract));

		$return_html = $custom_css = $divider_class = $editor_class = '';
		$uniqid = uniqid('thegem-te-divider-').rand(1,9999);

		// Init Design Options Params
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-divider', $params);

		$divider_html = '';

		$class_name .= ' gem-divider-style-'.$style;

		$class_name .= ' gem-divider-direction-'.$direction;

		if(empty($color)) {
			$color = thegem_get_option('divider_default_color');
		}

		if($direction == 'vertical') {
			if(!empty($vertical_divider_custom_height_desktop)) {
				$custom_css .= '.'.$uniqid.' .gem-divider {height: '.$vertical_divider_custom_height_desktop.'px;}';
			}
			if(!empty($vertical_divider_custom_height_tablet)) {
				$custom_css .= '@media screen and (max-width: 1023px) {.'.$uniqid.' .gem-divider {height: '.$vertical_divider_custom_height_tablet.'px;}}';
			}
			if(!empty($vertical_divider_custom_height_mobile)) {
				$custom_css .= '@media screen and (max-width: 767px) {.'.$uniqid.' .gem-divider {height: '.$vertical_divider_custom_height_mobile.'px;}}';
			}
			if($style == 'stroked') {
				$divider_html = '<svg width="1px" height="100%"><line x1="0" x2="1" y1="0" y2="100%" stroke="'.$color.'" stroke-width="2" stroke-linecap="black" stroke-dasharray="4, 4"/></svg>';
			}
		} elseif($direction == 'horizontal') {
			if(!empty($horizontal_enable_full_width)) {
				$class_name .= ' gem-divider-horizontal-full';
				$editor_class .= 'thegem-te-divider-editor-horizontal-full';
			} else {
				if(!empty($horizontal_divider_custom_width_desktop)) {
					$custom_css .= '.'.$uniqid.' .gem-divider {width: '.$horizontal_divider_custom_width_desktop.'px;}';
				}
				if(!empty($horizontal_divider_custom_width_tablet)) {
					$custom_css .= '@media screen and (max-width: 1023px) {.'.$uniqid.' .gem-divider {width: '.$horizontal_divider_custom_width_tablet.'px;}}';
				}
				if(!empty($horizontal_divider_custom_width_mobile)) {
					$custom_css .= '@media screen and (max-width: 767px) {.'.$uniqid.' .gem-divider {width: '.$horizontal_divider_custom_width_mobile.'px;}}';
				}
				$editor_class .= 'thegem-te-divider-editor-horizontal-not-full';
			}
			if($style == 'stroked') {
				$divider_html = '<svg width="100%" height="1px"><line x1="0" x2="100%" y1="0" y2="0" stroke="'.$color.'" stroke-width="2" stroke-linecap="black" stroke-dasharray="4, 4"/></svg>';
			}
		}

		if($style == 'solid' || $style == 'dotted' || $style == 'dashed') {
			if(!empty($divider_weight)) {
				$custom_css .= '.'.$uniqid.'.thegem-te-divider .gem-divider {border-width: '.$divider_weight.'px;}';
			}
		}

		$return_html .= '<div class="thegem-te-divider '.esc_attr($class_name).' '.$uniqid.'" '.thegem_data_editor_attribute($uniqid . '-editor '.$editor_class).'>';
			$return_html .= '<div class="gem-divider '.esc_attr($divider_class).'">'.$divider_html.'</div>';
		$return_html .= '</div>';

		$custom_css .= '.'.$uniqid.' .gem-divider {border-color: '.$color.';}';

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}

		$return_html = $css_output.$return_html;
		return $return_html;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Divider', 'thegem'),
			'base' => 'thegem_te_divider',
			'icon' => 'thegem-icon-wpb-ui-element-divider',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('Vertical / horizontal divider (Header Builder)', 'thegem'),
			'params' => array_merge(
				array(
					array(
						'type' => 'dropdown',
						'heading' => __('Direction', 'thegem'),
						'param_name' => 'direction',
						'value' => array(
							__('Vertical', 'thegem') => 'vertical',
							__('Horizontal', 'thegem') => 'horizontal'
						),
						'edit_field_class' => 'vc_column vc_col-sm-12'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Height Desktop (px)', 'thegem'),
						'param_name' => 'vertical_divider_custom_height_desktop',
						'std' => '50',
						'dependency' => array(
							'element' => 'direction',
							'value' => 'vertical'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Height Tablet (px)', 'thegem'),
						'param_name' => 'vertical_divider_custom_height_tablet',
						'std' => '50',
						'dependency' => array(
							'element' => 'direction',
							'value' => 'vertical'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Height Mobile (px)', 'thegem'),
						'param_name' => 'vertical_divider_custom_height_mobile',
						'std' => '50',
						'dependency' => array(
							'element' => 'direction',
							'value' => 'vertical'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Width Desktop (px)', 'thegem'),
						'param_name' => 'horizontal_divider_custom_width_desktop',
						'std' => '50',
						'dependency' => array(
							'element' => 'direction',
							'value' => 'horizontal'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Width Tablet (px)', 'thegem'),
						'param_name' => 'horizontal_divider_custom_width_tablet',
						'std' => '50',
						'dependency' => array(
							'element' => 'direction',
							'value' => 'horizontal'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Width Mobile (px)', 'thegem'),
						'param_name' => 'horizontal_divider_custom_width_mobile',
						'std' => '50',
						'dependency' => array(
							'element' => 'direction',
							'value' => 'horizontal'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Enable Full Width', 'thegem'),
						'param_name' => 'horizontal_enable_full_width',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'direction',
							'value' => 'horizontal'
						),
						'description' => __('Enable stretching divider to full width of page content.', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-12'
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Style', 'thegem'),
						'param_name' => 'style',
						'value' => array(
							__('Solid', 'thegem') => 'solid',
							__('Stroked', 'thegem') => 'stroked',
							__('Dotted', 'thegem') => 'dotted',
							__('Dashed', 'thegem') => 'dashed',
//							__('Zigzag', 'thegem') => 'zigzag',
//							__('Wave', 'thegem') => 'wave'
						)
					),
					array(
						'type' => 'textfield',
						'heading' => __('Weight (px)', 'thegem'),
						'param_name' => 'divider_weight',
						'dependency' => array(
							'element' => 'style',
							'value' => array('solid', 'dotted', 'dashed')
						),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'color',
						'dependency' => array(
							'element' => 'style',
							'value' => array('solid', 'stroked', 'dotted', 'dashed')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Extra class name', 'thegem'),
						'param_name' => 'class_name',
					),
				),
				thegem_set_elements_design_options()
			)
		);
	}
}

$templates_elements['thegem_te_divider'] = new TheGem_Template_Element_Divider();
$templates_elements['thegem_te_divider']->init();