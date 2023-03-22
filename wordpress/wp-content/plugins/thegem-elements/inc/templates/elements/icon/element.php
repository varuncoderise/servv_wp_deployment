<?php

class TheGem_Template_Element_Icon extends TheGem_Template_Element {

	public function __construct() {
		if ( !defined('THEGEM_TE_ICON_URL') ) {
			define('THEGEM_TE_ICON_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}
		wp_register_style('thegem-te-icon', THEGEM_TE_ICON_URL . '/css/icon.css');
	}

	public function get_name() {
		return 'thegem_te_icon';
	}

	public function head_scripts($attr) {
		$attr = shortcode_atts(array('pack' => 'thegem-header',), $attr, 'thegem_te_icon');
		wp_enqueue_style('icons-'.$attr['pack']);
		wp_enqueue_style('thegem-te-icon');
	}

	public function front_editor_scripts($attr) {
		$attr = shortcode_atts(array('pack' => 'material',), $attr, 'thegem_te_icon');
		wp_enqueue_style('icons-'.$attr['pack']);
		wp_enqueue_style('thegem-te-icon');
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		extract($extract = shortcode_atts(array_merge(array(
			'pack' => 'thegem-header',
			'icon_elegant' => '',
			'icon_material' => '',
			'icon_fontawesome' => '',
			'icon_thegemdemo' => '',
			'icon_userpack' => '',
			'icon_thegem_header' => 'e600',
			'shape' => 'simple',
			'style' => '',
			'color' => '',
			'hover_color' => '',
			'gradient_color_style' => 'linear',
			'color_2' => '',
			'hover_color_2' => '',
			'gradient_radial_color_position' => 'at top',
			'gradient_color_angle' => 'to bottom',
			'gradient_color_cusotom_deg' => '',
			'background_color' => '',
			'hover_background_color' => '',
			'border_color' => '',
			'hover_border_color' => '',
			'gradient_backgound' => '',
			'gradient_backgound_from' => '',
			'gradient_backgound_to' => '',
			'gradient_backgound_style' => 'linear',
			'gradient_radial_backgound_position' => 'at top',
			'gradient_backgound_angle' => 'to bottom',
			'gradient_backgound_cusotom_deg' => '',
			'size' => 'small',
			'icon_size' => '',
			'link' => '',
			'extra_class' => '',
		), thegem_templates_design_options_extract()), $atts, 'thegem_te_icon'));

		$params = array_merge(array(
			'pack' => $pack,
			'icon_elegant' => $icon_elegant,
			'icon_material' => $icon_material,
			'icon_fontawesome' => $icon_fontawesome,
			'icon_thegemdemo' => $icon_thegemdemo,
			'icon_userpack' => $icon_userpack,
			'icon_thegem_header' => $icon_thegem_header,
			'shape' => $shape,
			'style' => $style,
			'color' => $color,
			'hover_color' => $hover_color,
			'color_2' => $color_2,
			'hover_color_2' => $hover_color_2,
			'gradient_color_style' => $gradient_color_style,
			'gradient_radial_color_position' => $gradient_radial_color_position,
			'gradient_color_angle' => $gradient_color_angle,
			'gradient_color_cusotom_deg' => $gradient_color_cusotom_deg,
			'background_color' => $background_color,
			'hover_background_color' => $hover_background_color,
			'border_color' => $border_color,
			'hover_border_color' => $hover_border_color,
			'gradient_backgound' => $gradient_backgound,
			'gradient_backgound_from' => $gradient_backgound_from,
			'gradient_backgound_to' => $gradient_backgound_to,
			'gradient_backgound_style' => $gradient_backgound_style,
			'gradient_radial_backgound_position' => $gradient_radial_backgound_position,
			'gradient_backgound_angle' => $gradient_backgound_angle,
			'gradient_backgound_cusotom_deg' => $gradient_backgound_cusotom_deg,
			'size' => $size,
			'icon_size' => $icon_size,
			'link' => $link,
			'extra_class' => $extra_class,
		), thegem_templates_design_options_output($extract));

		$return_html = $custom_css = $output = $link_atts = $class = '';
		$css_style_icon_1_hover = $css_style_icon_2_hover = $css_style_icon_3_hover = $css_style_icon_background_hover = $css_style_icon_hover = '';

		$uniqid = uniqid('thegem-te-icon-').rand(1,9999);

		if($pack =='elegant' && empty($icon) && $icon_elegant) {
			$icon = $icon_elegant;
		}
		if($pack =='material' && empty($icon) && $icon_material) {
			$icon = $icon_material;
		}
		if($pack =='fontawesome' && empty($icon) && $icon_fontawesome) {
			$icon = $icon_fontawesome;
		}
		if($pack =='thegemdemo' && empty($icon) && $icon_thegemdemo) {
			$icon = $icon_thegemdemo;
		}
		if($pack =='userpack' && empty($icon) && $icon_userpack) {
			$icon = $icon_userpack;
		}
		if($pack =='thegem-header' && empty($icon) && $icon_thegem_header) {
			$icon = $icon_thegem_header;
		}

		$shape = thegem_check_array_value(array('simple', 'circle', 'square'), $shape, 'simple');
		$style = thegem_check_array_value(array('', 'angle-45deg-r', 'angle-45deg-l', 'angle-90deg', 'gradient'), $style, '');
		$size = thegem_check_array_value(array('tiny', 'small', 'medium', 'large', 'xlarge', 'custom'), $size, 'small');
		$css_style_icon = '';
		$css_style_icon_background = '';
		$css_style_icon_1 = '';
		$css_style_icon_2 = '';
		$css_style_icon_3 = '';

		if ($gradient_backgound_angle == 'cusotom_deg') {
			$gradient_backgound_angle = $gradient_backgound_cusotom_deg.'deg';
		}
		if($gradient_backgound and $gradient_backgound_style == 'linear') {
			$css_style_icon_background .= 'background: linear-gradient('.$gradient_backgound_angle.', '.$gradient_backgound_from.', '.$gradient_backgound_to.');';
		}
		if($gradient_backgound and $gradient_backgound_style == 'radial') {
			$css_style_icon_background .= 'background: radial-gradient('.$gradient_radial_backgound_position.', '.$gradient_backgound_from.', '.$gradient_backgound_to.');';
		}

		if($background_color && $shape != 'simple') {
			$css_style_icon_background .= 'background-color: '.$background_color.';';
			if(!$border_color || !$style == 'gradient') {
				$css_style_icon .= 'border-color: '.$background_color.';';
			}
		}

		if($hover_background_color && $shape != 'simple') {
			$css_style_icon_background_hover .= 'background-color: '.$hover_background_color.';';
			if(!$hover_border_color || !$style == 'gradient') {
				$css_style_icon_hover .= 'border-color: '.$hover_background_color.';';
			}
		}

		if($border_color && $shape != 'simple') {
			$css_style_icon .= 'border-color: '.$border_color.';';
		}

		if($hover_border_color && $shape != 'simple') {
			$css_style_icon_hover .= 'border-color: '.$hover_border_color.';';
		}

		if(!($background_color || $border_color || $gradient_backgound)) {
			$class .= ' gem-simple-icon';
		}

		if($color = $color) {
			$css_style_icon_1 = 'color: '.$color.';';
			if(($color_2 = $color_2) && $style) {
				$css_style_icon_2 = 'color: '.$color_2.';';
			} else {
				$css_style_icon_2 = 'color: '.$color.';';
			}

			if ($gradient_color_angle == 'cusotom_deg') {
				$gradient_color_angle = $gradient_color_cusotom_deg.'deg';
			}

			if( $gradient_color_style == 'linear') {
				$css_style_icon_3 .= 'background: linear-gradient( '.$gradient_color_angle.', '.$color.', '.$color_2.'); -webkit-text-fill-color: transparent; -webkit-background-clip: text;';
			}
			if ($gradient_color_style == 'radial') {
				$css_style_icon_3 .= 'background: radial-gradient( '.$gradient_radial_color_position.', '.$color.', '.$color_2.'); -webkit-text-fill-color: transparent; -webkit-background-clip: text;';
			}
		}

		if($hover_color = $hover_color) {
			$css_style_icon_1_hover = 'color: '.$hover_color.';';
			if(($hover_color_2 = $hover_color_2) && $style) {
				$css_style_icon_2_hover = 'color: '.$hover_color_2.';';
			} else {
				$css_style_icon_2_hover = 'color: '.$hover_color.';';
			}
			if( $gradient_color_style == 'linear') {
				$css_style_icon_3_hover .= 'background: linear-gradient( '.$gradient_color_angle.', '.$hover_color.', '.$hover_color_2.'); -webkit-text-fill-color: transparent; -webkit-background-clip: text;';
			}
			if ($gradient_color_style == 'radial') {
				$css_style_icon_3_hover .= 'background: radial-gradient( '.$gradient_radial_color_position.', '.$hover_color.', '.$hover_color_2.'); -webkit-text-fill-color: transparent; -webkit-background-clip: text;';
			}
		}

		if ($style != 'gradient') {
			$output = '<span class="gem-icon-half-1"><span class="back-angle">&#x' . $icon . ';</span></span>'.
				'<span class="gem-icon-half-2"><span class="back-angle">&#x' . $icon . ';</span></span>';
		} else {
			$output = '<span class="gem-icon-style-gradient"><span class="back-angle">&#x' . $icon . ';</span></span>';
		}

		if($link != '') {
			$link = vc_build_link($link);
			if(isset($link['url']) && $link['url'] != '') {
				$link_atts .= 'href="'.esc_url($link['url']).'"';
			} else {
				$link_atts .= 'href="#"';
			}
			if(isset($link['title']) && $link['title'] != '') {
				$link_atts .= ' title="'.esc_attr($link['title']).'"';
			}
			if(isset($link['target']) && $link['target'] != '') {
				$link_atts .= ' target="'.str_replace(' ','',esc_attr($link['target']).'"');
			}
			if(isset($link['rel']) && $link['rel'] != '') {
				$link_atts .= ' rel="'.esc_attr($link['rel']).'"';
			}
			$output = '<a '.$link_atts.'>'.$output.'</a>';
		}

		if(!empty($pack)) {
			$class .= ' gem-icon-pack-'.$pack;
		}
		if(!empty($size)) {
			$class .= ' gem-icon-size-'.$size;
		}
		if(!empty($style)) {
			$class .= ' '.$style;
		}
		if($shape != 'simple') {
			$class .= ' gem-icon-shape-'.$shape;
		}

		$return_html .= '<div class="thegem-te-icon '.esc_attr($uniqid).' '.esc_attr($extra_class).'" ' . thegem_data_editor_attribute($uniqid . '-editor') . '>';
			$return_html .= '<div class="gem-icon '.esc_attr($class).'">';
				$return_html .= '<div class="gem-icon-inner">';
					$return_html .= $output;
				$return_html .= '</div>';
			$return_html .= '</div>';
		$return_html .= '</div>';

		// Init Design Options Params
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-icon', $params);

		if(!empty($css_style_icon_background)) {
			$custom_css .= '.'.$uniqid.' .gem-icon-inner {'.$css_style_icon_background.'}';
		}
		if(!empty($css_style_icon_1)) {
			$custom_css .= '.'.$uniqid.' .gem-icon-half-1 {'.$css_style_icon_1.'}';
		}
		if(!empty($css_style_icon_2)) {
			$custom_css .= '.'.$uniqid.' .gem-icon-half-2 {'.$css_style_icon_2.'}';
		}
		if(!empty($css_style_icon_3)) {
			$custom_css .= '.'.$uniqid.' .gem-icon-style-gradient .back-angle {'.$css_style_icon_3.'}';
		}
		if(!empty($css_style_icon)) {
			$custom_css .= '.'.$uniqid.' .gem-icon  {'.$css_style_icon.'}';
		}
		if($size == 'custom' && !empty($icon_size)) {
			$custom_css .= '.'.$uniqid.' .gem-icon {font-size: '.esc_attr($icon_size).'px;}';
			$custom_css .= '.'.$uniqid.' .gem-icon:not(.gem-simple-icon) .gem-icon-inner {width: '. $icon_size * 1.5 .'px; height: '. $icon_size * 1.5 .'px; line-height: '. $icon_size * 1.5 .'px;}';
			$custom_css .= '.'.$uniqid.' .gem-icon.gem-simple-icon {width: '. $icon_size .'px; height: '. $icon_size .'px; line-height: '. $icon_size .'px;}';
		}

		if(!empty($css_style_icon_1_hover)) {
			$custom_css .= '.'.$uniqid.' .gem-icon a:hover .gem-icon-half-1 {'.$css_style_icon_1_hover.'}';
		}
		if(!empty($css_style_icon_2_hover)) {
			$custom_css .= '.'.$uniqid.' .gem-icon a:hover .gem-icon-half-2 {'.$css_style_icon_2_hover.'}';
		}
		if(!empty($css_style_icon_3_hover)) {
			$custom_css .= '.'.$uniqid.' .gem-icon a:hover .gem-icon-style-gradient .back-angle {'.$css_style_icon_3_hover.'}';
		}
		if(!empty($css_style_icon_background_hover)) {
			$custom_css .= '.'.$uniqid.' .gem-icon a:hover .gem-icon-inner {'.$css_style_icon_background_hover.'}';
		}
		if(!empty($css_style_icon_hover)) {
			$custom_css .= '.'.$uniqid.' .gem-icon a:hover .gem-icon  {'.$css_style_icon_hover.'}';
		}

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
			'name' => __('Icon', 'thegem'),
			'base' => 'thegem_te_icon',
			'icon' => 'thegem-icon-wpb-ui-element-icon',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('Icon from Icon Library  (Header Builder)', 'thegem'),
			'params' => array_merge(
				array(
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
						'std' => 'f287'
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
					),
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
					),
				)),
				array(
					array(
						'type' => 'dropdown',
						'heading' => __('Shape', 'thegem'),
						'param_name' => 'shape',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'value' => array(
							__('Simple', 'thegem') => 'simple',
							__('Square', 'thegem') => 'square',
							__('Circle', 'thegem') => 'circle'
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Color Split', 'thegem'),
						'param_name' => 'style',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'value' => array(
							__('Disabled', 'thegem') => '',
							__('Gradient', 'thegem') => 'gradient',
							__('45 degree Right', 'thegem') => 'angle-45deg-r',
							__('45 degree Left', 'thegem') => 'angle-45deg-l',
							__('90 degree', 'thegem') => 'angle-90deg'
						)
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover color', 'thegem'),
						'param_name' => 'hover_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color 2', 'thegem'),
						'param_name' => 'color_2',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'dependency' => array(
							'element' => 'style',
							'not_empty' => true
						)
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover color 2', 'thegem'),
						'param_name' => 'hover_color_2',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'dependency' => array(
							'element' => 'style',
							'not_empty' => true
						)
					),
					array(
						"type" => "dropdown",
						'heading' => __('Style', 'thegem'),
						'param_name' => 'gradient_color_style',
						"edit_field_class" => "vc_col-sm-4 vc_column",
						"value" => array(
							__('Linear', "thegem") => "linear",
							__('Radial', "thegem") => "radial",
						) ,
						"std" => 'linear',
						'dependency' => array(
							'element' => 'style',
							'value' => array(
								'gradient',
							)
						)
					),
					array(
						"type" => "dropdown",
						'heading' => __('Gradient Position', 'thegem'),
						'param_name' => 'gradient_radial_color_position',
						"edit_field_class" => "vc_col-sm-4 vc_column",
						"value" => array(
							__('Top', "thegem") => "at top",
							__('Bottom', "thegem") => "at bottom",
							__('Right', "thegem") => "at right",
							__('Left', "thegem") => "at left",
							__('Center', "thegem") => "at center",

						) ,
						'dependency' => array(
							'element' => 'gradient_color_style',
							'value' => array(
								'radial',
							)
						)
					),
					array(
						"type" => "dropdown",
						'heading' => __('Custom Angle', 'thegem'),
						'param_name' => 'gradient_color_angle',
						"edit_field_class" => "vc_col-sm-4 vc_column",
						"value" => array(
							__('Vertical to bottom ↓', "thegem") => "to bottom",
							__('Vertical to top ↑', "thegem") => "to top",
							__('Horizontal to left  →', "thegem") => "to right",
							__('Horizontal to right ←', "thegem") => "to left",
							__('Diagonal from left to bottom ↘', "thegem") => "to bottom right",
							__('Diagonal from left to top ↗', "thegem") => "to top right",
							__('Diagonal from right to bottom ↙', "thegem") => "to bottom left",
							__('Diagonal from right to top ↖', "thegem") => "to top left",
							__('Custom', "thegem") => "cusotom_deg",

						) ,
						'dependency' => array(
							'element' => 'gradient_color_style',
							'value' => array(
								'linear',
							)
						)
					),
					array(
						"type" => "textfield",
						'heading' => __('Angle', 'thegem'),
						'param_name' => 'gradient_color_cusotom_deg',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'description' => __('Set value in DG 0-360', 'thegem'),
						'dependency' => array(
							'element' => 'gradient_color_angle',
							'value' => array(
								'cusotom_deg',
							)
						)
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'background_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'dependency' => array(
							'element' => 'shape',
							'value' => array('square', 'circle')
						)
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Background Color', 'thegem'),
						'param_name' => 'hover_background_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'dependency' => array(
							'element' => 'shape',
							'value' => array('square', 'circle')
						)
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'border_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'dependency' => array(
							'element' => 'shape',
							'value' => array('square', 'circle')
						)
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Border Color', 'thegem'),
						'param_name' => 'hover_border_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'dependency' => array(
							'element' => 'shape',
							'value' => array('square', 'circle')
						)
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Use Gradient Backgound', 'thegem'),
						'param_name' => 'gradient_backgound',
						'value' => array(__('Yes', 'thegem') => '1')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('From', 'thegem'),
						'param_name' => 'gradient_backgound_from',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'dependency' => array(
							'element' => 'gradient_backgound',
							'value' => array('1')
						)
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('To', 'thegem'),
						'param_name' => 'gradient_backgound_to',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'dependency' => array(
							'element' => 'gradient_backgound',
							'value' => array('1')
						)
					),
					array(
						"type" => "dropdown",
						'heading' => __('Style', 'thegem'),
						'param_name' => 'gradient_backgound_style',
						"edit_field_class" => "vc_col-sm-4 vc_column",
						"value" => array(
							__('Linear', "thegem") => "linear",
							__('Radial', "thegem") => "radial",
						) ,
						"std" => 'linear',
						'dependency' => array(
							'element' => 'gradient_backgound',
							'value' => array('1')
						)
					),
					array(
						"type" => "dropdown",
						'heading' => __('Gradient Position', 'thegem'),
						'param_name' => 'gradient_radial_backgound_position',
						"edit_field_class" => "vc_col-sm-4 vc_column",
						"value" => array(
							__('Top', "thegem") => "at top",
							__('Bottom', "thegem") => "at bottom",
							__('Right', "thegem") => "at right",
							__('Left', "thegem") => "at left",
							__('Center', "thegem") => "at center",

						) ,
						'dependency' => array(
							'element' => 'gradient_backgound_style',
							'value' => array(
								'radial',
							)
						)
					),
					array(
						"type" => "dropdown",
						'heading' => __('Custom Angle', 'thegem'),
						'param_name' => 'gradient_backgound_angle',
						"edit_field_class" => "vc_col-sm-4 vc_column",
						"value" => array(
							__('Vertical to bottom ↓', "thegem") => "to bottom",
							__('Vertical to top ↑', "thegem") => "to top",
							__('Horizontal to left  →', "thegem") => "to right",
							__('Horizontal to right ←', "thegem") => "to left",
							__('Diagonal from left to bottom ↘', "thegem") => "to bottom right",
							__('Diagonal from left to top ↗', "thegem") => "to top right",
							__('Diagonal from right to bottom ↙', "thegem") => "to bottom left",
							__('Diagonal from right to top ↖', "thegem") => "to top left",
							__('Custom', "thegem") => "cusotom_deg",

						) ,
						'dependency' => array(
							'element' => 'gradient_backgound_style',
							'value' => array(
								'linear',
							)
						)
					),
					array(
						"type" => "textfield",
						'heading' => __('Angle', 'thegem'),
						'param_name' => 'gradient_backgound_cusotom_deg',
						"edit_field_class" => "vc_col-sm-4 vc_column",
						'description' => __('Set value in DG 0-360', 'thegem'),
						'dependency' => array(
							'element' => 'gradient_backgound_angle',
							'value' => array(
								'cusotom_deg',
							)
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Size', 'thegem'),
						'param_name' => 'size',
						'value' => array(
							__('Tiny', 'thegem') => 'tiny',
							__('Small', 'thegem') => 'small',
							__('Medium', 'thegem') => 'medium',
							__('Large', 'thegem') => 'large',
							__('Extra Large', 'thegem') => 'xlarge',
							__('Custom', 'thegem') => 'custom'
						),
						'std' => 'small'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Icon size', 'thegem'),
						'param_name' => 'icon_size',
						'dependency' => array(
							'element' => 'size',
							'value' => array('custom')
						)
					),
					array(
						'type' => 'vc_link',
						'heading' => __('Link', 'thegem'),
						'param_name' => 'link',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Extra class name', 'thegem'),
						'param_name' => 'extra_class',
					),
				),
				thegem_set_elements_design_options()
			)
		);
	}
}

$templates_elements['thegem_te_icon'] = new TheGem_Template_Element_Icon();
$templates_elements['thegem_te_icon']->init();