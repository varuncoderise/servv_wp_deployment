<?php

class TheGem_Template_Element_Socials extends TheGem_Template_Element {

	public function __construct() {
		if ( !defined('THEGEM_TE_SOCIALS_URL') ) {
			define('THEGEM_TE_SOCIALS_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}
		wp_register_style('thegem-te-socials', THEGEM_TE_SOCIALS_URL . '/css/socials.css');
	}

	public function get_name() {
		return 'thegem_te_socials';
	}

	public function head_scripts($attr) {
		wp_enqueue_style('thegem-te-socials');
	}

	public function front_editor_scripts() {
		wp_enqueue_style('thegem-te-socials');
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		extract($extract = shortcode_atts(array_merge(array(
			'style' => 'default',
			'colored' => 'default',
			'color' => '',
			'hover_color' => '',
			'icons_spacing' => '15',
			'size' => 'tiny',
			'icons_size' => '',
			'socials' => urlencode(json_encode(array(
				array(
					'social' => 'facebook',
					'url' => 'url:%23|title:Facebook|target:_blank',
				),
				array(
					'social' => 'twitter',
					'url' => 'url:%23|title:Twitter|target:_blank',
				),
				array(
					'social' => 'instagram',
					'url' => 'url:%23|title:Instagram|target:_blank',
				),
			))),
			'extra_class' => '',
		), thegem_templates_design_options_extract()), $atts, 'thegem_te_socials'));

		$params = array_merge(array(
			'style' => $style,
			'colored' => $colored,
			'color' => $color,
			'hover_color' => $hover_color,
			'icons_spacing' => $icons_spacing,
			'size' => $size,
			'icons_size' => $icons_size,
			'socials' => $socials,
			'extra_class' => $extra_class,
		), thegem_templates_design_options_output($extract));

		$return_html = $custom_css = '';

		$uniqid = uniqid('thegem-te-socials-').rand(1,9999);

		if($colored != 'custom') {
			$extra_class .= ' socials-colored';
		} else {
			$extra_class .= ' socials-colored-hover';
		}

		$return_html .= '<div class="thegem-te-socials '.esc_attr($uniqid).'" ' . thegem_data_editor_attribute($uniqid . '-editor') . '>';
			$socials = vc_param_group_parse_atts($socials);
			$socials_html = '';
			foreach($socials as $social) {
				$link_atts = '';
				$social = shortcode_atts(array(
					'social' => 'facebook',
					'url' => 'url:%23|title:Facebook|target:_blank',
				), $social);


				if($social['url'] != '') {
					$link = vc_build_link($social['url']);
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
				}


				if(function_exists('thegem_additionals_socials_enqueue_style')){
					thegem_additionals_socials_enqueue_style($social['social']);
				}
				$socials_html .= '<a class="socials-item" '.$link_atts.'><i class="socials-item-icon '.$social['social'].'"></i></a>';
			}
			$return_html .= '<div class="socials socials-list socials-'.$style.' '.esc_attr($extra_class).' thegem-te-socials-size-'.esc_attr($size).'">'.$socials_html.'</div>';
		$return_html .= '</div>';

		// Init Design Options Params
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-socials', $params);

		if(!empty($color)) {
			$custom_css .= '.'.$uniqid.' .socials-item {color: '.$color.';}';
		}
		if(!empty($hover_color)) {
			$custom_css .= '.'.$uniqid.' .socials-list.socials-colored .socials-item:hover .socials-item-icon, .'.$uniqid.' .socials-list.socials-colored-hover .socials-item:hover .socials-item-icon {color: '.$hover_color.';}';
		}
		if($size == 'custom' && !empty($icons_size)) {
			$custom_css .= '.'.$uniqid.'.thegem-te-socials .socials-item-icon {font-size: '.$icons_size.'px;}';
		}
		if(!empty($icons_spacing) || strcmp($icons_spacing, '0') === 0) {
			$custom_css .= '.'.$uniqid.' .socials-list .socials-item {margin-left: '.(int)$icons_spacing / 2 .'px; margin-right: '.(int)$icons_spacing / 2 .'px;}';
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
			'name' => __('Social Icons', 'thegem'),
			'base' => 'thegem_te_socials',
			'icon' => 'thegem-icon-wpb-ui-element-socials',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('Social Network Icons (Header Builder)', 'thegem'),
			'params' => array_merge(
				array(
					array(
						'type' => 'dropdown',
						'heading' => __('Style', 'thegem'),
						'param_name' => 'style',
						'value' => array(
							__('Default', 'thegem') => 'default',
							__('Rounded', 'thegem') => 'rounded',
							__('Square', 'thegem') => 'square',
						),
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Icons color', 'thegem'),
						'param_name' => 'colored',
						'value' => array(
							__('Official', 'thegem') => 'default',
							__('Custom', 'thegem') => 'custom',
						),
						'edit_field_class' => 'vc_col-sm-6 vc_column no-top-padding'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Custom color', 'thegem'),
						'param_name' => 'color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency' => array(
							'element' => 'colored',
							'value' => 'custom'
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Custom hover color', 'thegem'),
						'param_name' => 'hover_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Spacing', 'thegem'),
						'param_name' => 'icons_spacing',
						'std' => '15'
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
						'std' => 'tiny',
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Icons size', 'thegem'),
						'param_name' => 'icons_size',
						'dependency' => array(
							'element' => 'size',
							'value' => 'custom'
						),
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type' => 'param_group',
						'heading' => __( 'Socials', 'thegem' ),
						'param_name' => 'socials',
						'value' => urlencode(json_encode(array(
							array(
								'social' => 'facebook',
								'url' => 'url:%23|title:Facebook|target:_blank',
							),
							array(
								'social' => 'twitter',
								'url' => 'url:%23|title:Twitter|target:_blank',
							),
							array(
								'social' => 'instagram',
								'url' => 'url:%23|title:Instagram|target:_blank',
							),
						))),
						'params' => array_merge(array(
							array(
								'type' => 'dropdown',
								'heading' => __( 'Social', 'thegem' ),
								'param_name' => 'social',
								'value' => array_flip(apply_filters('thegem_socials_icons_list', array(
									'facebook' => 'Facebook', 'linkedin' => 'LinkedIn', 'twitter' => 'Twitter (X)', 'instagram' => 'Instagram',
									'pinterest' => 'Pinterest', 'stumbleupon' => 'StumbleUpon', 'rss' => 'RSS',
									'vimeo' => 'Vimeo', 'youtube' => 'YouTube', 'flickr' => 'Flickr', 'tumblr' => 'Tumblr',
									'wordpress' => 'WordPress', 'dribbble' => 'Dribbble', 'deviantart' => 'DeviantArt', 'share' => 'Share',
									'myspace' => 'Myspace', 'skype' => 'Skype', 'picassa' => 'Picassa', 'googledrive' => 'Google Drive',
									'blogger' => 'Blogger', 'spotify' => 'Spotify', 'delicious' => 'Delicious', 'telegram' => 'Telegram',
									'vk' => 'VK', 'whatsapp' => 'WhatsApp', 'viber' => 'Viber', 'ok' => 'OK', 'reddit' => 'Reddit',
									'slack' => 'Slack', 'askfm' => 'ASKfm', 'meetup' => 'Meetup', 'weibo' => 'Weibo', 'qzone' => 'Qzone',
									'tiktok' => 'TikTok', 'soundcloud' => 'SoundCloud', 'discord' => 'Discord'
								)))
							),
							array(
								'type' => 'vc_link',
								'heading' => __('Link', 'thegem'),
								'param_name' => 'url',
								'std' => '#'
							),
						)),
						'description' => __('Go to <a href="'.get_site_url().'/wp-admin/admin.php?page=thegem-theme-options#/contacts-and-socials/socials" target="_blank">Theme Options</a> to manage your social profiles.', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Extra class name', 'thegem'),
						'param_name' => 'extra_class'
					),
				),
				thegem_set_elements_design_options()
			)
		);
	}
}

$templates_elements['thegem_te_socials'] = new TheGem_Template_Element_Socials();
$templates_elements['thegem_te_socials']->init();