<?php

class TheGem_Template_Element_Button extends TheGem_Template_Element {

	public function __construct() {
		if ( !defined('THEGEM_TE_BUTTON_URL') ) {
			define('THEGEM_TE_BUTTON_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}
		wp_register_style('thegem-te-button', THEGEM_TE_BUTTON_URL . '/css/button.css');
	}

	public function get_name() {
		return 'thegem_te_button';
	}

	public function head_scripts($attr) {
		wp_enqueue_style('thegem-te-button');
	}

	public function front_editor_scripts() {
		wp_enqueue_style('thegem-te-button');
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		extract($extract = shortcode_atts(array_merge(array(
			'text' => 'Button',
			'link' => '',
			'style' => 'flat',
			'size' => 'tiny',
			'text_weight' => 'normal',
			'font_type' => 'default',
			'no_uppercase' => 0,
			'corner' => 3,
			'border' => 2,
			'text_color' => '',
			'background_color' => '',
			'border_color' => '',
			'hover_text_color' => '',
			'hover_background_color' => '',
			'hover_border_color' => '',
			'icon_pack' => 'elegant',
			'icon_elegant' => '',
			'icon_material' => '',
			'icon_fontawesome' => '',
			'icon_thegem_header' => '',
			'icon_userpack' => '',
			'icon_position' => 'left',
			'extra_class' => '',
			'id' => '',
			'gradient_backgound' => '',
			'gradient_radial_swap_colors' => '',
			'gradient_backgound_from' => '',
			'gradient_backgound_to' => '',
			'gradient_backgound_hover_from' => '',
			'gradient_backgound_hover_to' => '',
			'gradient_backgound_style' => 'linear',
			'gradient_backgound_angle' => 'to bottom',
			'gradient_backgound_cusotom_deg' => '180',
			'gradient_radial_backgound_position' => 'at top',
		), thegem_templates_design_options_extract()), $atts, 'thegem_te_button'));

		$return_html = $custom_css = $uniqid = '';

		$link = ( '||' === $link ) ? '' : $link;
		if($link === 'post_link') {
			$a_href = $link;
			$a_title = '';
			$a_target = '';
		} else {
			$link = vc_build_link( $link );
			$a_href = $link['url'];
			$a_title = $link['title'];
			$a_target = strlen( $link['target'] ) > 0 ? $link['target'] : '_self';
		}
		$icon = '';
		if($icon_elegant && $icon_pack == 'elegant') {
			$icon = $icon_elegant;
		}
		if($icon_material && $icon_pack == 'material') {
			$icon = $icon_material;
		}
		if($icon_fontawesome && $icon_pack == 'fontawesome') {
			$icon = $icon_fontawesome;
		}
		if($icon_thegem_header && $icon_pack == 'thegem-header') {
			$icon = $icon_thegem_header;
		}
		if($icon_userpack && $icon_pack == 'userpack') {
			$icon = $icon_userpack;
		}

		$uniqid = uniqid('thegem-te-button-').rand(1,9999);

		if($font_type == 'body') {
			$extra_class .= esc_attr(' gem-button-font-type-body');
		}

		$params = array_merge(array(
			'text' => $text,
			'href' => $a_href,
			'target' => $a_target,
			'title' => $a_title,
			'style' => $style,
			'size' => $size,
			'text_weight' => $text_weight,
			'no-uppercase' => $no_uppercase,
			'corner' => $corner,
			'border' => $border,
			'text_color' => $text_color,
			'background_color' => $background_color,
			'border_color' => $border_color,
			'hover_text_color' => $hover_text_color,
			'hover_background_color' => $hover_background_color,
			'hover_border_color' => $hover_border_color,
			'icon_pack' => $icon_pack,
			'icon' => $icon,
			'icon_position' => $icon_position,
			'extra_class' => $extra_class,
			'id' => $id,
			'gradient_backgound' => $gradient_backgound,
			'gradient_backgound_from' => $gradient_backgound_from,
			'gradient_backgound_to' => $gradient_backgound_to,
			'gradient_backgound_hover_from' => $gradient_backgound_hover_from,
			'gradient_backgound_hover_to' => $gradient_backgound_hover_to,
			'gradient_backgound_style' => $gradient_backgound_style,
			'gradient_radial_swap_colors' => $gradient_radial_swap_colors,
			'gradient_backgound_angle' => $gradient_backgound_angle,
			'gradient_backgound_cusotom_deg' => $gradient_backgound_cusotom_deg,
			'gradient_radial_backgound_position' => $gradient_radial_backgound_position,
		), thegem_templates_design_options_output($extract));

		$return_html .= '<div class="thegem-te-button '.$uniqid.'" '.thegem_data_editor_attribute($uniqid . '-editor').'>';
			$return_html .= thegem_button($params);
		$return_html .= '</div>';

		// Init Design Options Params
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-button', $params);

		if(empty(thegem_get_option('button_letter_spacing'))) {
			$custom_css .= '.'.$uniqid.' .gem-button {letter-spacing: 0;}';
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
			'name' => __('Button', 'thegem'),
			'base' => 'thegem_te_button',
			'icon' => 'thegem-icon-wpb-ui-element-button',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('Styled Button (Header Builder)', 'thegem'),
			'params' => array_merge(
				array(
					array(
						'type' => 'textfield',
						'heading' => __('Button Text', 'thegem'),
						'param_name' => 'text',
						'std' => 'Button'
					),
					array(
						'type' => 'vc_link',
						'heading' => __( 'URL (Link)', 'thegem' ),
						'param_name' => 'link',
						'description' => __( 'Add link to button.', 'thegem' )
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Style', 'thegem'),
						'param_name' => 'style',
						'value' => array(
							__('Flat', 'thegem') => 'flat',
							__('Outline', 'thegem') => 'outline'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
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
							__('Giant', 'thegem') => 'giant'
						),
						'std' => 'tiny',
						'edit_field_class' => 'vc_column vc_col-sm-6 no-top-padding',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Font Type', 'thegem'),
						'param_name' => 'font_type',
						'value' => array(
							__('Default', 'thegem') => 'default',
							__('Body', 'thegem') => 'body'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Text weight', 'thegem'),
						'param_name' => 'text_weight',
						'value' => array(
							__('Normal', 'thegem') => 'normal',
							__('Thin', 'thegem') => 'thin',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'dependency' => array(
							'element' => 'font_type',
							'value' => array('default')
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Disable Uppercase', 'thegem'),
						'param_name' => 'no_uppercase',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border radius', 'thegem'),
						'param_name' => 'corner',
						'value' => 3,
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Border width', 'thegem'),
						'param_name' => 'border',
						'value' => array(1, 2, 3, 4, 5, 6),
						'std' => 2,
						'dependency' => array(
							'element' => 'style',
							'value' => array('outline')
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text color', 'thegem'),
						'param_name' => 'text_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover text color', 'thegem'),
						'param_name' => 'hover_text_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background color', 'thegem'),
						'param_name' => 'background_color',
						'dependency' => array(
							'element' => 'style',
							'value' => array('flat')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover background color', 'thegem'),
						'param_name' => 'hover_background_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border color', 'thegem'),
						'param_name' => 'border_color',
						'dependency' => array(
							'element' => 'style',
							'value' => array('outline')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover border color', 'thegem'),
						'param_name' => 'hover_border_color',
						'dependency' => array(
							'element' => 'style',
							'value' => array('outline')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Use Gradient Backgound Colors', 'thegem'),
						'param_name' => 'gradient_backgound',
						'value' => array(__('Yes', 'thegem') => '1'),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background From', 'thegem'),
						'param_name' => 'gradient_backgound_from',
						'dependency' => array(
							'element' => 'gradient_backgound',
							'value' => array('1')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background To', 'thegem'),
						'param_name' => 'gradient_backgound_to',
						'dependency' => array(
							'element' => 'gradient_backgound',
							'value' => array('1')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Background From', 'thegem'),
						"edit_field_class" => "vc_col-sm-6 vc_column",
						'param_name' => 'gradient_backgound_hover_from',
						'dependency' => array(
							'element' => 'gradient_backgound',
							'value' => array('1')
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Background To', 'thegem'),
						"edit_field_class" => "vc_col-sm-6 vc_column",
						'param_name' => 'gradient_backgound_hover_to',
						'dependency' => array(
							'element' => 'gradient_backgound',
							'value' => array('1')
						),
						'group' => __('Style', 'thegem')
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
						),
						'group' => __('Style', 'thegem')
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
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Swap Colors', 'thegem'),
						'param_name' => 'gradient_radial_swap_colors',
						"edit_field_class" => "vc_col-sm-4 vc_column",
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'gradient_backgound_style',
							'value' => array(
								'radial',
							)
						),
						'group' => __('Style', 'thegem')
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
						),
						'group' => __('Style', 'thegem')
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
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'thegem' ),
						'param_name' => 'extra_class',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'ID name', 'thegem' ),
						'param_name' => 'id',
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Icon pack', 'thegem'),
						'param_name' => 'icon_pack',
						'value' => array_merge(array(__('Elegant', 'thegem') => 'elegant', __('Material Design', 'thegem') => 'material', __('FontAwesome', 'thegem') => 'fontawesome', __('Header Icons', 'thegem') => 'thegem-header'), thegem_userpack_to_dropdown()),
						'std' => 2,
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'icon_pack',
							'value' => array('elegant')
						),
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'icon_pack',
							'value' => array('material')
						),
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'icon_pack',
							'value' => array('fontawesome')
						),
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_thegem_header',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'icon_pack',
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
							'element' => 'icon_pack',
							'value' => array('userpack')
						),
					),
				)),
				array(
					array(
						'type' => 'dropdown',
						'heading' => __( 'Icon position', 'thegem' ),
						'param_name' => 'icon_position',
						'value' => array(__( 'Left', 'thegem' ) => 'left', __( 'Right', 'thegem' ) => 'right'),
					),
				),
				thegem_set_elements_design_options()
			),
		);
	}
}

$templates_elements['thegem_te_button'] = new TheGem_Template_Element_Button();
$templates_elements['thegem_te_button']->init();