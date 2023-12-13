<?php

class TheGem_Template_Element_Menu extends TheGem_Template_Element {

	public function __construct() {
		if ( !defined('THEGEM_TE_MENU_DIR' )) {
			define('THEGEM_TE_MENU_DIR', rtrim(__DIR__, ' /\\'));
		}

		if ( !defined('THEGEM_TE_MENU_URL') ) {
			define('THEGEM_TE_MENU_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}

		wp_register_style('thegem-te-menu', THEGEM_TE_MENU_URL . '/css/menu.css');
		wp_register_style('thegem-te-menu-editor', THEGEM_TE_MENU_URL . '/css/menu-editor.css');
		wp_register_style('thegem-te-menu-default', THEGEM_TE_MENU_URL . '/css/menu-default.css');
		wp_register_style('thegem-te-menu-overlay', THEGEM_TE_MENU_URL . '/css/menu-overlay.css');
		wp_register_style('thegem-te-menu-hamburger', THEGEM_TE_MENU_URL . '/css/menu-hamburger.css');
		wp_register_style('thegem-te-menu-mobile-default', THEGEM_TE_MENU_URL . '/css/menu-mobile-default.css');
		wp_register_style('thegem-te-menu-mobile-sliding', THEGEM_TE_MENU_URL . '/css/menu-mobile-sliding.css');

		wp_register_script('thegem-te-menu', THEGEM_TE_MENU_URL . '/js/menu.js', array('jquery'), false, true);
		wp_localize_script('thegem-te-menu', 'thegem_menu_data', array(
			'ajax_url' => esc_url(admin_url('admin-ajax.php')),
			'ajax_nonce' => wp_create_nonce('ajax_security'),
			'backLabel' => !empty(thegem_get_option('mobile_menu_back_text')) ? thegem_get_option('mobile_menu_back_text') : esc_html__('Back', 'thegem'),
			'showCurrentLabel' => !empty(thegem_get_option('mobile_menu_show_this_page_text')) ? thegem_get_option('mobile_menu_show_this_page_text') : esc_html__('Show this page', 'thegem'),
		));
		wp_register_script('thegem-te-menu-editor', THEGEM_TE_MENU_URL . '/js/menu-editor.js', array('jquery'), false, true);
	}

	public function get_name() {
		return 'thegem_te_menu';
	}

	public function thegem_templates_menu_first_level_link_class( $classes, $item, $args ) {
		if(isset($args->first_level_link_class) && $item->menu_item_parent == 0) {
			$classes['class'] = $args->first_level_link_class;
		}
		return $classes;
	}

	public function head_scripts($attr) {
		$attr = shortcode_atts(array('menu_layout_desktop' => 'default', 'menu_layout_mobile' => 'default', 'pack' => 'thegem-header'), $attr, 'thegem_te_menu');

		wp_enqueue_script('thegem-te-menu');
		wp_enqueue_style('thegem-te-menu');

		if (isset($attr['menu_layout_desktop'])) {
			if ($attr['menu_layout_desktop'] == 'default' || $attr['menu_layout_desktop'] == 'split') {
				wp_enqueue_style('thegem-te-menu-default');
			}

			if ($attr['menu_layout_desktop'] == 'overlay') {
				wp_enqueue_style('thegem-te-menu-overlay');
			}

			if ($attr['menu_layout_desktop'] == 'hamburger') {
				wp_enqueue_style('thegem-te-menu-hamburger');
			}
		}

		if ($attr['menu_layout_mobile'] == 'overlay') {
			wp_enqueue_style('thegem-te-menu-overlay');
		}

		if ($attr['menu_layout_mobile'] == 'default') {
			wp_enqueue_style('thegem-te-menu-mobile-default');
			wp_enqueue_script('jquery-dlmenu');
			wp_localize_script('jquery-dlmenu', 'thegem_dlmenu_settings', array(
				'backLabel' => esc_html__('Back', 'thegem'),
				'showCurrentLabel' => esc_html__('Show this page', 'thegem')
			));
		}

		if ($attr['menu_layout_mobile'] == 'slide-horizontal' || $attr['menu_layout_mobile'] == 'slide-vertical') {
			wp_enqueue_style('thegem-te-menu-mobile-sliding');
		}

		wp_enqueue_style('icons-'.$attr['pack']);
		wp_enqueue_style('thegem-te-icon');
	}

	public function front_editor_scripts($attr) {
		$attr = shortcode_atts(array('pack' => 'thegem-header'), $attr, 'thegem_te_menu');

		wp_enqueue_style('thegem-te-menu');
		wp_enqueue_style('thegem-te-menu-editor');
		wp_enqueue_script('thegem-te-menu-editor');

		wp_enqueue_style('icons-'.$attr['pack']);
		wp_enqueue_style('thegem-te-icon');
	}

	public function shortcode_output($atts, $content = '') {
		$params = shortcode_atts(array_merge(array(
			'menu_source' => '',
			'different_source_mobile' => '',
			'menu_source_mobile' => '',
			'menu_layout_desktop' => 'default',
			'menu_layout_tablet_landscape' => 'default',
			'menu_layout_tablet_portrait' => 'mobile',
			'menu_layout_mobile' => 'default',
			'menu_layout_mobile_effect' => '1',
			'menu_layout_tablet_breakpoint' => '',
			'menu_layout_mobile_breakpoint' => '',
			'hamburger_overlay_menu_source' => 'default',
			'hamburger_overlay_template' => '',
			'overlay_template_container' => '',
			'hamburger_icon_size' => 'small',
			'hamburger_canvas_width' => '',
			'hamburger_canvas_padding_top' => '',
			'hamburger_canvas_padding_bottom' => '',
			'hamburger_canvas_padding_left' => '',
			'hamburger_canvas_padding_right' => '',
			'desktop_menu_stretch' => '',
			'tablet_landscape_menu_stretch' => '',
			'tablet_portrait_menu_stretch' => '',
			'dropdown_spacing' => '20',
			'dropdown_spacing_mobile' => '20',
			'submenu_indicator' => '',
			'pack' => 'thegem-header',
			'submenu_icon_elegant' => '',
			'submenu_icon_material' => '',
			'submenu_icon_fontawesome' => '',
			'submenu_icon_thegemdemo' => '',
			'submenu_icon_thegemheader' => '',
			'submenu_icon_userpack' => '',
			'submenu_icon_size' => '',
			'submenu_icon_margin' => '',
			'thegem_te_menu_skin' => 'inherit',
			'walker' => new TheGem_Mega_Menu_Walker,
			'menu_class' => '',
			'is_white_main_menu' => thegem_get_option('header_style') == 4,
			'split_logo' => 'desktop_logo_dark',
			'split_after_item' => '2',
			'split_layout_type' => 'full',
			'split_logo_left_margin' => '',
			'split_logo_right_margin' => '',
			'split_logo_absolute' => '',
			'use_light' => '',
			'search_menu_widget' => '',
			'mobile_menu_search' => '',
			'socials_menu_widget' => '',
			'mobile_menu_socials' => '',
			'hamburger_icon_color' => '',
			'close_icon_color' => '',
			'background_color_hamburger' => '',
			'background_color_overlay_hamburger' => '',
			'background_color_overlay' => '',
			'color_menu_item' => '',
			'color_menu_item_hover' => '',
			'color_menu_item_active' => '',
			'background_color_menu_item' => '',
			'background_color_menu_item_hover' => '',
			'background_color_menu_item_active' => '',
			'color_menu_item_overlay' => '',
			'color_menu_item_overlay_hover' => '',
			'color_menu_item_overlay_active' => '',
			'menu_item_space_between' => '',
			'menu_item_padding_top' => '',
			'menu_item_padding_right' => '',
			'menu_item_padding_bottom' => '',
			'menu_item_padding_left' => '',
			'text_color_level2' => '',
			'text_color_level3' => '',
			'background_color_level2' => '',
			'background_color_level3' => '',
			'text_color_level2_hover' => '',
			'text_color_level3_hover' => '',
			'background_color_level2_hover' => '',
			'background_color_level3_hover' => '',
			'submenu_hover_pointer_color' => '',
			'text_color_level2_active' => '',
			'text_color_level3_active' => '',
			'background_color_level2_active' => '',
			'background_color_level3_active' => '',
			'submenu_active_pointer_color' => '',
			'submenu_border' => '1',
			'submenu_level2_border_color' => '',
			'submenu_level3_border_color' => '',
			'submenu_enable_shadow' => '',
			'submenu_shadow_color' => 'rgba(0, 0, 0, 0.15)',
			'submenu_shadow_position' => 'outline',
			'submenu_shadow_horizontal' => '0',
			'submenu_shadow_vertical' => '5',
			'submenu_shadow_blur' => '5',
			'submenu_shadow_spread' => '-5',
			'submenu_padding_top' => '',
			'submenu_padding_right' => '',
			'submenu_padding_bottom' => '',
			'submenu_padding_left' => '',
			'hamburger_icon_color_mobile' => '',
			'close_icon_color_mobile' => '',
			'mobile_menu_lvl_1_border' => '1',
			'mobile_menu_lvl_1_border_color' => '',
			'mobile_menu_lvl_1_color' => '',
			'mobile_menu_lvl_1_background_color' => '',
			'mobile_menu_lvl_1_color_active' => '',
			'mobile_menu_lvl_1_background_color_active' => '',
			'mobile_menu_lvl_2_border' => '1',
			'mobile_menu_lvl_2_border_color' => '',
			'mobile_menu_lvl_2_color' => '',
			'mobile_menu_lvl_2_background_color' => '',
			'mobile_menu_lvl_2_color_active' => '',
			'mobile_menu_lvl_2_background_color_active' => '',
			'mobile_menu_lvl_3_border' => '1',
			'mobile_menu_lvl_3_border_color' => '',
			'mobile_menu_lvl_3_color' => '',
			'mobile_menu_lvl_3_background_color' => '',
			'mobile_menu_lvl_3_color_active' => '',
			'mobile_menu_lvl_3_background_color_active' => '',
			'close_icon_color_mobile_overlay' => '',
			'mobile_menu_overlay_background_color' => '',
			'mobile_menu_social_icon_color' => '',
			'mobile_menu_overlay_color' => '',
			'mobile_menu_overlay_color_active' => '',
			'menu_pointer_style_hover' => 'text-color',
			'menu_pointer_style_active' => 'frame-default',
			'animation_framed' => 'fade',
			'animation_line' => 'fade',
			'animation_background' => 'fade',
			'pointer_color_menu_item_hover' => '',
			'pointer_width_menu_item_hover' => '',
			'pointer_color_menu_item_active' => '',
			'pointer_width_menu_item_active' => '',
			'menu_item_border_radius' => '',
			'menu_lvl_1_text_style' => '',
			'menu_lvl_1_font_weight' => '',
			'menu_lvl_1_letter_spacing' => '',
			'menu_lvl_1_text_transform' => '',
		), thegem_templates_design_options_extract(), thegem_templates_extra_options_extract()), $atts, 'thegem_te_menu');

		$params['is_desktop_default'] = ($params['menu_layout_desktop'] == 'default' || $params['menu_layout_desktop'] == 'split');
		$params['is_overlay'] = $params['menu_layout_desktop'] == 'overlay' || $params['menu_layout_mobile'] == 'overlay';
		$params['is_hamburger'] = $params['menu_layout_desktop'] == 'hamburger';
		$params['is_mobile_default'] = $params['menu_layout_mobile'] == 'default';
		$params['is_mobile_sliding'] = ($params['menu_layout_mobile'] == 'slide-horizontal' || $params['menu_layout_mobile'] == 'slide-vertical');
		$params['is_mobile'] = $params['is_mobile_default'] || $params['is_mobile_sliding'] || $params['menu_layout_mobile'] == 'overlay';
		$params['is_light_submenu'] = $params['thegem_te_menu_skin'] == 'light';
		$params['is_dark_submenu'] = $params['thegem_te_menu_skin'] == 'dark';
		$params['is_light_mobile_menu'] = $params['thegem_te_menu_skin'] == 'light';
		$params['is_dark_mobile_menu'] = $params['thegem_te_menu_skin'] == 'dark';

		// Desktop colors presets
		$menu_desktop_preset = '';
		if ($params['is_light_submenu']) {
			$menu_desktop_preset = 'menu--light-submenu';
		}
		if ($params['is_dark_submenu']) {
			$menu_desktop_preset = 'menu--dark-submenu';
		}
		if ($params['is_white_main_menu']) {
			$menu_desktop_preset = 'menu--white-mainmenu';
		}

		// Mobile colors presets
		$menu_mobile_preset = '';
		if ($params['is_light_mobile_menu']) {
			$menu_mobile_preset = 'menu-mobile--light';
		}
		if ($params['is_dark_mobile_menu']) {
			$menu_mobile_preset = 'menu-mobile--dark';
		}

		if ($params['is_mobile_default']) {
			$params['menu_class'] .= 'dl-menu';
		}

		if ($params['submenu_indicator']) {
			$params['menu_class'] .= ' submenu-icon';
		}

		if (isset($params['submenu_border']) && $params['submenu_border'] !== '1') {
			$params['menu_class'] .= ' submenu-hide-border';
		}

		// Menu type
		$menu_type = $menu_mobile_type = '';
		if ($params['menu_layout_desktop'] == 'split') {
			$menu_type = 'default';
		} else {
			$menu_type = $params['menu_layout_desktop'];
		}
		$menu_mobile_type = $params['menu_layout_mobile'];

		//Search Widget Init
		$menu_widget = $menu_mobile_widget = '';
		if ($params['menu_layout_desktop'] == 'hamburger' || $params['menu_layout_desktop'] == 'overlay' || $params['is_mobile']) {
			add_filter('wp_nav_menu_items', 'thegem_templates_menu_search_widget', 10, 2);

			if ($params['search_menu_widget']) {
				$menu_widget .= ' show-desktop-search';
			}

			if ($params['mobile_menu_search']) {
				$menu_mobile_widget .= ' show-mobile-search';
			}
		} else {
			remove_filter('wp_nav_menu_items', 'thegem_templates_menu_search_widget', 10, 2);
		}

		// Hamburger Socials Widget Init
		if ($params['menu_layout_desktop'] == 'hamburger' && $params['socials_menu_widget']) {
			$menu_widget .= ' show-desktop-socials';
			add_filter('wp_nav_menu_items', 'thegem_templates_menu_socials_widget', 10, 2);
		} else {
			remove_filter('wp_nav_menu_items', 'thegem_templates_menu_socials_widget', 10, 2);
		}

		//Mobile Menu Socials Widget Init
		if ($params['is_mobile_sliding'] && $params['mobile_menu_socials']) {
			$menu_mobile_widget .= ' show-mobile-socials';
			add_filter('wp_nav_menu_items', 'thegem_templates_menu_mobile_socials_widget', 10, 2);
		} else {
			remove_filter('wp_nav_menu_items', 'thegem_templates_menu_mobile_socials_widget', 10, 2);
		}

		//Split logo init
		if ($params['menu_layout_desktop'] == 'split') {
			$params['menu_class'] .= ' nav-menu--split';
			$output_logo = $output_logo_style = '';
			$echo = false;

			if ($params['split_layout_type'] == 'full') {
				$params['menu_class'] .= ' fullwidth-logo';
			}

			if ($params['split_logo_absolute']) {
				$params['menu_class'] .= ' absolute';
				$output_logo_style = 'opacity: 0';
			}

			if (empty($params['use_light']) && isset($params['split_logo']) && $params['split_logo'] == 'desktop_logo_dark') {
				$output_logo = thegem_get_logo_img(esc_url(thegem_get_option('logo')), intval(thegem_get_option('logo_width')), 'tgp-exclude default', $echo);
			}
			if((empty($params['use_light']) && isset($params['split_logo']) && $params['split_logo'] == 'desktop_logo_light') || ($params['split_logo'] == 'desktop_logo_dark' && !empty($params['use_light']))) {
				$output_logo = thegem_get_logo_img(esc_url(thegem_get_option('logo_light')), intval(thegem_get_option('logo_width')), 'tgp-exclude default light', $echo);
			}
			if (empty($params['use_light']) && isset($params['split_logo']) && $params['split_logo'] == 'mobile_logo_dark') {
				$output_logo = thegem_get_logo_img(esc_url(thegem_get_option('small_logo')), intval(thegem_get_option('small_logo_width')), 'tgp-exclude small', $echo);
			}
			if((empty($params['use_light']) && isset($params['split_logo']) && $params['split_logo'] == 'mobile_logo_light') || ($params['split_logo'] == 'mobile_logo_dark' && !empty($params['use_light']))) {
				$output_logo = thegem_get_logo_img(esc_url(thegem_get_option('small_logo_light')), intval(thegem_get_option('small_logo_width')), 'tgp-exclude small light', $echo);
			}

			add_filter('wp_nav_menu_items', 'thegem_add_menu_item_split_logo', 10, 2);
		} else {
			remove_filter('wp_nav_menu_items', 'thegem_add_menu_item_split_logo', 10, 2);
		}

		$different_source = '';
		if ($params['different_source_mobile']) {
			$different_source = 'different-source-mobile';
		}

		//General
		$extra_id = $extra_cls = '';
		if (!empty($params['element_id'])){ $extra_id = $params['element_id']; }
		if (!empty($params['element_class'])){ $extra_cls = $params['element_class']; }

		// Init Appearance Params
		$return_html = $custom_css = $uniqid = '';
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-menu', $params);
		$editor_suffix = '';
		$shortcode = '.thegem-te-menu';
		if(function_exists('vc_is_page_editable') && vc_is_page_editable()) {
			$editor_suffix = '-editor ';
			$shortcode = '';
		}

		// Editor Fix
		$custom_css .= $shortcode. '.' . esc_attr($uniqid.$editor_suffix) . '{width: fit-content; min-height: auto !important;}';

		// Breakpoints
		$desktop_breakpoint = 1212;
		$tablet_breakpoint = 980;
		if (isset($params['menu_layout_tablet_breakpoint']) && !empty($params['menu_layout_tablet_breakpoint'])){
			$tablet_breakpoint = intval($params['menu_layout_tablet_breakpoint']);
		}

		$mobile_breakpoint = 768;
		if (isset($params['menu_layout_mobile_breakpoint']) && !empty($params['menu_layout_mobile_breakpoint'])){
			$mobile_breakpoint = intval($params['menu_layout_mobile_breakpoint']);
		}

		// Horizontal menu stretch
		if ($params['is_desktop_default'] && ($params['desktop_menu_stretch'] || $params['tablet_landscape_menu_stretch'] || $params['tablet_portrait_menu_stretch'] || $params['split_layout_type'] == 'full')) {
			$params['menu_class'] .= ' nav-menu--stretch';

			if ($params['desktop_menu_stretch'] || ($params['menu_layout_desktop'] == 'split' && $params['split_layout_type'] == 'full')) {
				$custom_css .= $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{width: 100% !important;}';
				$custom_css .= $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . ' .thegem-te-menu {width: 100% !important;}';
			} else {
				$custom_css .= $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{width: fit-content !important;}';
				$custom_css .= $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . ' .thegem-te-menu {width: fit-content !important;}';
			}
			if (($params['tablet_landscape_menu_stretch'] && $params['menu_layout_tablet_landscape'] == 'default') || ($params['menu_layout_desktop'] == 'split' && $params['split_layout_type'] == 'full' && $params['menu_layout_tablet_landscape'] == 'default')) {
				$custom_css .= '@media screen and (max-width: '.$desktop_breakpoint.'px) {'. $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{width: 100% !important; }}';
				$custom_css .= '@media screen and (max-width: '.$desktop_breakpoint.'px) {'. $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . ' .thegem-te-menu {width: 100% !important; }}';
			} else {
				$custom_css .= '@media screen and (max-width: '.$desktop_breakpoint.'px) {'.$shortcode. '.' . esc_attr($uniqid.$editor_suffix) . '{width: fit-content !important; }}';
				$custom_css .= '@media screen and (max-width: '.$desktop_breakpoint.'px) {'. $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . ' .thegem-te-menu {width: fit-content !important; }}';
			}
			if (($params['tablet_portrait_menu_stretch'] && $params['menu_layout_tablet_portrait'] == 'default') || ($params['menu_layout_desktop'] == 'split' && $params['split_layout_type'] == 'full' && $params['menu_layout_tablet_portrait'] == 'default')) {
				$custom_css .= '@media screen and (max-width: '.($tablet_breakpoint - 1).'px) {'. $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{width: 100% !important; }}';
				$custom_css .= '@media screen and (max-width: '.($tablet_breakpoint - 1).'px) {'. $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . ' .thegem-te-menu {width: 100% !important; }}';
			} else {
				$custom_css .= '@media screen and (max-width: '.($tablet_breakpoint - 1).'px) {'. $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{width: fit-content !important; }}';
				$custom_css .= '@media screen and (max-width: '.($tablet_breakpoint - 1).'px) {'. $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . ' .thegem-te-menu {width: fit-content !important; }}';
			}
			$custom_css .= '@media screen and (max-width: '.($mobile_breakpoint - 1).'px) {'. $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{width: fit-content !important; }}';
			$custom_css .= '@media screen and (max-width: '.($mobile_breakpoint - 1).'px) {'. $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . ' .thegem-te-menu {width: fit-content !important; }}';
		}

		// Horizontal menu spacing
		if (!empty($params['dropdown_spacing'])) {
			$custom_css .= $shortcode. '.' . esc_attr($uniqid.$editor_suffix) . ' .thegem-te-menu__default.desktop-view ul.nav-menu > li.menu-item-has-children, ' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . ' .thegem-te-menu__default.desktop-view ul.nav-menu > li.megamenu-template-enable {margin-bottom: -' . $params['dropdown_spacing'] . 'px; padding-bottom: ' . $params['dropdown_spacing'] . 'px;}';
		}

		// Mobile menu spacing
		if (!empty($params['dropdown_spacing_mobile'])) {
			$custom_css .= $shortcode. '.' . esc_attr($uniqid.$editor_suffix) . ' .thegem-te-menu-mobile__default.mobile-view .dl-menu, '.$shortcode. '.' . esc_attr($uniqid.$editor_suffix) . ' .thegem-te-menu-mobile__default.mobile-view > .dl-submenu {top: calc(100% + ' . $params['dropdown_spacing_mobile'] . 'px);}';
		}

		// Submenu Icon
		if (!empty($params['submenu_icon_size'])) {
			$custom_css .= $shortcode. '.' . esc_attr($uniqid.$editor_suffix) . ' .thegem-te-menu .nav-menu.submenu-icon > li.menu-item-has-children > a i {font-size: ' . $params['submenu_icon_size'] . 'px;}';
		}
		if (!empty($params['submenu_icon_margin'])) {
			$custom_css .= $shortcode. '.' . esc_attr($uniqid.$editor_suffix) . ' .thegem-te-menu .nav-menu.submenu-icon > li.menu-item-has-children > a i {margin-left: ' . $params['submenu_icon_margin'] . 'px;}';
		}

		// Split Menu zIndex Fix
		if ($params['menu_layout_desktop'] == 'split' && $params['split_logo_absolute']) {
			$custom_css .= '@media screen and (min-width: 768px) {'. $shortcode. '.' . esc_attr($uniqid.$editor_suffix) . '{z-index: 0 !important;}}';
		}
		// Split Menu margins
		if ($params['menu_layout_desktop'] == 'split' && $params['split_layout_type'] == 'standard') {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu .menu-item-type-split-logo {margin-left: ' . $params['split_logo_left_margin'] . 'px; margin-right: ' . $params['split_logo_right_margin'] . 'px;}';
		}

		// Menu Style
		if (!empty($params['hamburger_icon_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view .menu-toggle .menu-line-1, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view .menu-toggle .menu-line-2, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view .menu-toggle .menu-line-3 {background-color: ' . $params['hamburger_icon_color'] . ';}';
		}
		if (!empty($params['close_icon_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view .overlay-toggle-close .menu-line-1, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view .overlay-toggle-close .menu-line-2, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view .overlay-toggle-close .menu-line-3, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view .hamburger-toggle-close .menu-line-1, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view .hamburger-toggle-close .menu-line-2, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view .hamburger-toggle-close .menu-line-3 {background-color: ' . $params['close_icon_color'] . ';}';
		}
		if (!empty($params['background_color_hamburger'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu__hamburger.desktop-view ul.nav-menu {background-color: ' . $params['background_color_hamburger'] . ';}';
		}
		if (!empty($params['background_color_overlay_hamburger'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu__hamburger.desktop-view .hamburger-menu-back {background-color: ' . $params['background_color_overlay_hamburger'] . ';}';
		}
		if (!empty($params['background_color_overlay'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu__overlay.desktop-view .overlay-menu-back {background-color: ' . $params['background_color_overlay'] . ';}';
		}
		if (!empty($params['color_menu_item'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' > nav.thegem-te-menu__overlay.desktop-view ul.nav-menu li a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' > nav.thegem-te-menu__overlay.desktop-view ul.nav-menu li .menu-item-parent-toggle {color: ' . $params['color_menu_item'] . ';}';
		}
		if (!empty($params['color_menu_item_hover'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current):hover > a {color: ' . $params['color_menu_item_hover'] . ';}';
		}
		if (!empty($params['color_menu_item_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.menu-item-active > a:hover, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.menu-item-current > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.menu-item-current > a:hover {color: ' . $params['color_menu_item_active'] . ';}';
		}
		if (!empty($params['background_color_menu_item'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(:hover):not(:focus):not(.highlighted):not(.menu-item-active):not(.menu-item-current) > a {background-color: ' . $params['background_color_menu_item'] . ';}';
		}
		if (!empty($params['background_color_menu_item_hover'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-framed > nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current):hover > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-lined > nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current):hover > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-background > nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current):hover > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-text > nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current):hover > a {background-color: ' . $params['background_color_menu_item_hover'] . ';}';
		}
		if (!empty($params['background_color_menu_item_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-framed > nav.desktop-view ul.nav-menu > li.menu-item-active > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-framed > nav.desktop-view ul.nav-menu > li.menu-item-current > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-lined > nav.desktop-view ul.nav-menu > li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-lined > nav.desktop-view ul.nav-menu > li.menu-item-current > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-background > nav.desktop-view ul.nav-menu > li.menu-item-active > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-background > nav.desktop-view ul.nav-menu > li.menu-item-current > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-text > nav.desktop-view ul.nav-menu > li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-text > nav.desktop-view ul.nav-menu > li.menu-item-current > a {background-color: ' . $params['background_color_menu_item_active'] . ';}';
		}
		if (!empty($params['pointer_color_menu_item_hover'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-framed nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-framed nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:after  {border-color: ' . $params['pointer_color_menu_item_hover'] . ';}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-lined nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-lined nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:after, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-background.style-hover-type-background-underline nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:after  {background-color: ' . $params['pointer_color_menu_item_hover'] . ';}';
		}
		if (!empty($params['pointer_color_menu_item_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-framed nav.desktop-view ul.nav-menu > li.menu-item-active > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-framed nav.desktop-view ul.nav-menu > li.menu-item-current > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-framed nav.desktop-view ul.nav-menu > li.menu-item-active > a:after, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-framed nav.desktop-view ul.nav-menu > li.menu-item-current > a:after  {border-color: ' . $params['pointer_color_menu_item_active'] . ';}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-lined nav.desktop-view ul.nav-menu > li.menu-item-active > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-lined nav.desktop-view ul.nav-menu > li.menu-item-current > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-lined nav.desktop-view ul.nav-menu > li.menu-item-active > a:after, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-lined nav.desktop-view ul.nav-menu > li.menu-item-current > a:after, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-background.style-active-type-background-underline nav.desktop-view ul.nav-menu > li.menu-item-active > a:after, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-background.style-active-type-background-underline nav.desktop-view ul.nav-menu > li.menu-item-current > a:after  {background-color: ' . $params['pointer_color_menu_item_active'] . ';}';
		}
		if (!empty($params['pointer_width_menu_item_hover'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-framed nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-framed nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:after  {border-width: ' . $params['pointer_width_menu_item_hover'] . 'px;}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-lined nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-lined nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:after, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-background.style-hover-type-background-underline nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:after {height: ' . $params['pointer_width_menu_item_hover'] . 'px;}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-framed.style-hover-animation-corners nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-framed.style-hover-animation-corners nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:after, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-framed.style-hover-animation-draw nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-framed.style-hover-animation-draw nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:after  {width: ' . $params['pointer_width_menu_item_hover'] . 'px; height: ' . $params['pointer_width_menu_item_hover'] . 'px;}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-framed.style-hover-animation-corners nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:before {border-width: ' . $params['pointer_width_menu_item_hover'] . 'px 0 0 ' . $params['pointer_width_menu_item_hover'] . 'px;}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-framed.style-hover-animation-corners nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:after  {border-width: 0 ' . $params['pointer_width_menu_item_hover'] . 'px ' . $params['pointer_width_menu_item_hover'] . 'px 0;}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-framed.style-hover-animation-draw nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:before  {border-width: 0 0 ' . $params['pointer_width_menu_item_hover'] . 'px ' . $params['pointer_width_menu_item_hover'] . 'px;}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-hover-framed.style-hover-animation-draw nav.desktop-view ul.nav-menu > li:not(.menu-item-active):not(.menu-item-current) > a:after  {border-width: ' . $params['pointer_width_menu_item_hover'] . 'px ' . $params['pointer_width_menu_item_hover'] . 'px 0 0;}';
		}
		if (!empty($params['pointer_width_menu_item_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-framed nav.desktop-view ul.nav-menu > li.menu-item-active > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-framed nav.desktop-view ul.nav-menu > li.menu-item-current > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-framed nav.desktop-view ul.nav-menu > li.menu-item-active > a:after, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-framed nav.desktop-view ul.nav-menu > li.menu-item-current > a:after  {border-width: ' . $params['pointer_width_menu_item_active'] . 'px;}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-lined nav.desktop-view ul.nav-menu > li.menu-item-active > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-lined nav.desktop-view ul.nav-menu > li.menu-item-current > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-lined nav.desktop-view ul.nav-menu > li.menu-item-active > a:after, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-lined nav.desktop-view ul.nav-menu > li.menu-item-current > a:after, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-background.style-active-type-background-underline nav.desktop-view ul.nav-menu > li.menu-item-active > a:after, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . '.style-active-background.style-active-type-background-underline nav.desktop-view ul.nav-menu > li.menu-item-current > a:after {height: ' . $params['pointer_width_menu_item_active'] . 'px;}';
		}
		if (!empty($params['color_menu_item_overlay'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' > nav.thegem-te-menu__overlay.desktop-view ul.nav-menu li a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' > nav.thegem-te-menu__overlay.desktop-view ul.nav-menu li .menu-item-parent-toggle {color: ' . $params['color_menu_item_overlay'] . ';}';
		}
		if (!empty($params['color_menu_item_overlay_hover'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' > nav.thegem-te-menu__overlay.desktop-view ul.nav-menu li:hover > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' > nav.thegem-te-menu__overlay.desktop-view ul.nav-menu li:hover > .menu-item-parent-toggle {color: ' . $params['color_menu_item_overlay_hover'] . ';}';
		}
		if (!empty($params['color_menu_item_overlay_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' > nav.thegem-te-menu__overlay.desktop-view ul.nav-menu li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' > nav.thegem-te-menu__overlay.desktop-view ul.nav-menu li.menu-item-active > span > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' > nav.thegem-te-menu__overlay.desktop-view ul.nav-menu li.menu-item-current > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' > nav.thegem-te-menu__overlay.desktop-view ul.nav-menu li.menu-item-current > span > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' > nav.thegem-te-menu__overlay.desktop-view ul.nav-menu li.menu-item-active > .menu-item-parent-toggle, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' > nav.thegem-te-menu__overlay.desktop-view ul.nav-menu li.menu-item-current > .menu-item-parent-toggle {color: ' . $params['color_menu_item_overlay_active'] . ';}';
		}
		if (!empty($params['menu_item_space_between'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li {margin: 0 calc(' . $params['menu_item_space_between'] . 'px/2);}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu {margin: 0 calc(-' . $params['menu_item_space_between'] . 'px/2);}';
		}
		if (isset($params['menu_item_padding_top']) && $params['menu_item_padding_top'] !== '') {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > a {padding-top: ' . $params['menu_item_padding_top'] . 'px !important;}';
		}
		if (isset($params['menu_item_padding_right']) && $params['menu_item_padding_right'] !== '') {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > a {padding-right: ' . $params['menu_item_padding_right'] . 'px !important;}';
		}
		if (isset($params['menu_item_padding_bottom']) && $params['menu_item_padding_bottom'] !== '') {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > a {padding-bottom: ' . $params['menu_item_padding_bottom'] . 'px !important;}';
		}
		if (isset($params['menu_item_padding_left']) && $params['menu_item_padding_left'] !== '') {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > a {padding-left: ' . $params['menu_item_padding_left'] . 'px !important;}';
		}
		if (isset($params['menu_item_border_radius']) && $params['menu_item_border_radius'] !== '') {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > a:after {border-radius: ' . $params['menu_item_border_radius'] . 'px !important;}';
		}
		if (!empty($params['text_color_level2'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > ul > li > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li > span > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li > span > a:before {color: ' . $params['text_color_level2'] . ' !important;}';
		}
		if (!empty($params['text_color_level3'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li > ul > li a {color: ' . $params['text_color_level3'] . ';}';
		}
		if (!empty($params['background_color_level2'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > ul > li > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li > span > a {background-color: ' . $params['background_color_level2'] . ' !important;}';
		}
		if (!empty($params['background_color_level3'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li > ul > li a {background-color: ' . $params['background_color_level3'] . ';}';
		}
		if (!empty($params['text_color_level2_hover'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > ul > li:hover > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li:hover > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li:hover > span > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li:hover > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li:hover > span > a:before {color: ' . $params['text_color_level2_hover'] . ' !important;}';
		}
		if (!empty($params['text_color_level3_hover'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li > ul li:hover > a {color: ' . $params['text_color_level3_hover'] . ';}';
		}
		if (!empty($params['background_color_level2_hover'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > ul > li:hover > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li:hover > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li:hover > span > a {background-color: ' . $params['background_color_level2_hover'] . ' !important;}';
		}
		if (!empty($params['background_color_level3_hover'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li > ul li:hover > a {background-color: ' . $params['background_color_level3_hover'] . ';}';
		}
		if (!empty($params['submenu_hover_pointer_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li li:hover > a {border-color: ' . $params['submenu_hover_pointer_color'] . ';}';
		}
		if (!empty($params['text_color_level2_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > ul > li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li.menu-item-active > span > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li.menu-item-active > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li.menu-item-active > span > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > ul > li.menu-item-current > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li.menu-item-current > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li.menu-item-current > span > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li.menu-item-current > a:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li.menu-item-current > span > a:before {color: ' . $params['text_color_level2_active'] . ' !important;}';
		}
		if (!empty($params['text_color_level3_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li > ul li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li > ul li.menu-item-current > a {color: ' . $params['text_color_level3_active'] . ';}';
		}
		if (!empty($params['background_color_level2_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > ul > li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li.menu-item-active > span > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > ul > li.menu-item-current > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li.menu-item-current > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li.menu-item-current > span > a {background-color: ' . $params['background_color_level2_active'] . ' !important;}';
		}
		if (!empty($params['background_color_level3_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li > ul li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li > ul li.menu-item-current > a {background-color: ' . $params['background_color_level3_active'] . ';}';
		}
		if (!empty($params['submenu_active_pointer_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li li.menu-item-current > a {border-color: ' . $params['submenu_active_pointer_color'] . ';}';
		}
		if (!empty($params['submenu_level2_border_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > ul, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > ul > li, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul > li span.megamenu-column-header, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul li {border-color: ' . $params['submenu_level2_border_color'] . ';}';
		}
		if (!empty($params['submenu_level3_border_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li > ul, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li > ul ul, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li > ul li {border-color: ' . $params['submenu_level3_border_color'] . ';}';
		}
		if(!empty($params['submenu_enable_shadow'])) {
			$shadow_position = '';
			if($params['submenu_shadow_position'] == 'inset') {
				$shadow_position = 'inset';
			}
			if(empty($params['submenu_shadow_horizontal'])) {
				$params['submenu_shadow_horizontal'] = 0;
			}
			if(empty($params['submenu_shadow_vertical'])) {
				$params['submenu_shadow_vertical'] = 0;
			}
			if(empty($params['submenu_shadow_blur'])) {
				$params['submenu_shadow_blur'] = 0;
			}
			if(empty($params['submenu_shadow_spread'])) {
				$params['submenu_shadow_spread'] = 0;
			}
			if(empty($params['submenu_shadow_color'])) {
				$params['submenu_shadow_color'] = '#000';
			}
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable):not(.megamenu-template-enable) ul, '.$shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li.megamenu-enable > ul {'
				. '-webkit-box-shadow: '.$shadow_position.' '.$params['submenu_shadow_horizontal'].'px '.$params['submenu_shadow_vertical'].'px '.$params['submenu_shadow_blur'].'px '.$params['submenu_shadow_spread'].'px '.$params['submenu_shadow_color'].';'
				. '-moz-box-shadow: '.$shadow_position.' '.$params['submenu_shadow_horizontal'].'px '.$params['submenu_shadow_vertical'].'px '.$params['submenu_shadow_blur'].'px '.$params['submenu_shadow_spread'].'px '.$params['submenu_shadow_color'].';;'
				. '-o-box-shadow: '.$shadow_position.' '.$params['submenu_shadow_horizontal'].'px '.$params['submenu_shadow_vertical'].'px '.$params['submenu_shadow_blur'].'px '.$params['submenu_shadow_spread'].'px '.$params['submenu_shadow_color'].';;'
				. 'box-shadow: '.$shadow_position.' '.$params['submenu_shadow_horizontal'].'px '.$params['submenu_shadow_vertical'].'px '.$params['submenu_shadow_blur'].'px '.$params['submenu_shadow_spread'].'px '.$params['submenu_shadow_color'].';;'
				. '}';
		}
		if (isset($params['submenu_padding_top'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li a {padding-top: ' . $params['submenu_padding_top'] . 'px;}';
		}
		if (isset($params['submenu_padding_right'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li a {padding-right: ' . $params['submenu_padding_right'] . 'px;}';
		}
		if (isset($params['submenu_padding_bottom'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li a {padding-bottom: ' . $params['submenu_padding_bottom'] . 'px;}';
		}
		if (isset($params['submenu_padding_left'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li:not(.megamenu-enable) > ul > li a {padding-left: ' . $params['submenu_padding_left'] . 'px;}';
		}
		if (!empty($params['hamburger_icon_color_mobile'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view .menu-toggle .menu-line-1, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view .menu-toggle .menu-line-2, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view .menu-toggle .menu-line-3 {background-color: ' . $params['hamburger_icon_color_mobile'] . ';}';
		}
		if (!empty($params['close_icon_color_mobile'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view .mobile-menu-slide-close:before, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view .mobile-menu-slide-close:after {background-color: ' . $params['close_icon_color_mobile'] . ';}';
		}
		if (isset($params['mobile_menu_lvl_1_border']) && $params['mobile_menu_lvl_1_border'] == '') {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > a {border: none !important;}';
		}
		if (!empty($params['mobile_menu_lvl_1_border_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' .thegem-te-menu-mobile__default.mobile-view ul.nav-menu > li.menu-item-type-search-widget > .minisearch, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' .thegem-te-menu-mobile__default.mobile-view ul.nav-menu > li.menu-item-type-search-widget > .minisearch .sf-input {border-color: ' . $params['mobile_menu_lvl_1_border_color'] . ';}';
		}
		if (!empty($params['mobile_menu_lvl_1_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > .menu-item-parent-toggle, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li.menu-item-type-search-widget > .minisearch .sf-input, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li.menu-item-type-search-widget > .minisearch .sf-input::placeholder {color: ' . $params['mobile_menu_lvl_1_color'] . ';}';
		}
		if (!empty($params['mobile_menu_lvl_1_background_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu-mobile__slide-horizontal.mobile-view .mobile-menu-slide-wrapper, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu-mobile__slide-vertical.mobile-view .mobile-menu-slide-wrapper {background-color: ' . $params['mobile_menu_lvl_1_background_color'] . ';}';
		}
		if (!empty($params['mobile_menu_lvl_1_color_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li.menu-item-current > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li.menu-item-active > .menu-item-parent-toggle, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li.menu-item-current > .menu-item-parent-toggle {color: ' . $params['mobile_menu_lvl_1_color_active'] . ';}';
		}
		if (!empty($params['mobile_menu_lvl_1_background_color_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li.menu-item-current > a {background-color: ' . $params['mobile_menu_lvl_1_background_color_active'] . ';}';
		}
		if (isset($params['mobile_menu_lvl_2_border']) && $params['mobile_menu_lvl_2_border'] == '') {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view > ul.sub-menu.level3 > li a {border: none !important;}';
		}
		if (!empty($params['mobile_menu_lvl_2_border_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view > ul.sub-menu.level3 > li a {border-color: ' . $params['mobile_menu_lvl_2_border_color'] . ';}';
		}
		if (!empty($params['mobile_menu_lvl_2_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > .menu-item-parent-toggle, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > span > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view > ul.sub-menu.level3 > li a {color: ' . $params['mobile_menu_lvl_2_color'] . ';}';
		}
		if (!empty($params['mobile_menu_lvl_2_background_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > span > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view > ul.sub-menu.level3 > li a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' .thegem-te-menu-mobile__slide-horizontal.mobile-view li.menu-item-type-search-widget > .minisearch .sf-input, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' .thegem-te-menu-mobile__slide-vertical.mobile-view li.menu-item-type-search-widget > .minisearch .sf-input, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' .thegem-te-menu-mobile__slide-horizontal.mobile-view li.menu-item-type-search-widget > .minisearch .sf-input {background-color: ' . $params['mobile_menu_lvl_2_background_color'] . ';}';
		}
		if (!empty($params['mobile_menu_lvl_2_color_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li.menu-item-current > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li.menu-item-active > .menu-item-parent-toggle, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li.menu-item-current > .menu-item-parent-toggle {color: ' . $params['mobile_menu_lvl_2_color_active'] . ';}';
		}
		if (!empty($params['mobile_menu_lvl_2_background_color_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li.menu-item-current > a {background-color: ' . $params['mobile_menu_lvl_2_background_color_active'] . ';}';
		}
		if (isset($params['mobile_menu_lvl_3_border']) && $params['mobile_menu_lvl_3_border'] == '') {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > ul li, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > ul li a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view > ul.sub-menu.level4 > li a {border: none !important;}';
		}
		if (!empty($params['mobile_menu_lvl_3_border_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > ul li, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > ul li a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view > ul.sub-menu.level4 > li a {border-color: ' . $params['mobile_menu_lvl_3_border_color'] . ';}';
		}
		if (!empty($params['mobile_menu_lvl_3_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > ul li a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > ul li .menu-item-parent-toggle, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view > ul.sub-menu.level4 > li a {color: ' . $params['mobile_menu_lvl_3_color'] . ';}';
		}
		if (!empty($params['mobile_menu_lvl_3_background_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > ul li a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view > ul.sub-menu.level4 > li a {background-color: ' . $params['mobile_menu_lvl_3_background_color'] . ';}';
		}
		if (!empty($params['mobile_menu_lvl_3_color_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > ul li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > ul li.menu-item-current > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > ul li.menu-item-active > .menu-item-parent-toggle, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > ul li.menu-item-current > .menu-item-parent-toggle {color: ' . $params['mobile_menu_lvl_3_color_active'] . ';}';
		}
		if (!empty($params['mobile_menu_lvl_3_background_color_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > ul li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view ul.nav-menu > li > ul > li > ul li.menu-item-current > a {background-color: ' . $params['mobile_menu_lvl_3_background_color_active'] . ';}';
		}
		if (!empty($params['close_icon_color_mobile_overlay'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view .overlay-toggle-close .menu-line-1, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view .overlay-toggle-close .menu-line-2, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.mobile-view .overlay-toggle-close .menu-line-3 {background-color: ' . $params['close_icon_color_mobile_overlay'] . ';}';
		}
		if (!empty($params['mobile_menu_social_icon_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' .thegem-te-menu-mobile__slide-horizontal.mobile-view li.menu-item-type-socials-widget > .menu-item-socials .socials-item, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' .thegem-te-menu-mobile__slide-vertical.mobile-view li.menu-item-type-socials-widget > .menu-item-socials .socials-item {color: ' . $params['mobile_menu_social_icon_color'] . ';}';
		}
		if (!empty($params['mobile_menu_overlay_background_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu-mobile__overlay.mobile-view .overlay-menu-back {background-color: ' . $params['mobile_menu_overlay_background_color'] . ';}';
		}
		if (!empty($params['mobile_menu_overlay_color'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu-mobile__overlay.mobile-view ul.nav-menu li a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu-mobile__overlay.mobile-view ul.nav-menu li .menu-item-parent-toggle, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu-mobile__overlay.mobile-view ul.nav-menu > li.menu-item-type-search-widget > .minisearch .sf-input, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu-mobile__overlay.mobile-view ul.nav-menu > li.menu-item-type-search-widget > .minisearch .sf-input::placeholder, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu-mobile__overlay.mobile-view ul.nav-menu > li.menu-item-type-search-widget > .minisearch .sf-submit-icon:before {color: ' . $params['mobile_menu_overlay_color'] . ';}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu-mobile__overlay.mobile-view ul.nav-menu > li.menu-item-type-search-widget > .minisearch .sf-input {border-color: ' . $params['mobile_menu_overlay_color'] . ';}';
		}
		if (!empty($params['mobile_menu_overlay_color_active'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu-mobile__overlay.mobile-view ul.nav-menu li.menu-item-active > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu-mobile__overlay.mobile-view ul.nav-menu li.menu-item-active > span > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' > nav.thegem-te-menu-mobile__overlay.mobile-view ul.nav-menu li.menu-item-active .menu-item-parent-toggle, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu-mobile__overlay.mobile-view ul.nav-menu li.menu-item-current > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' > nav.thegem-te-menu-mobile__overlay.mobile-view ul.nav-menu li.menu-item-current > span > a, ' . $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.thegem-te-menu-mobile__overlay.mobile-view ul.nav-menu li.menu-item-current > .menu-item-parent-toggle {color: ' . $params['mobile_menu_overlay_color_active'] . ';}';
		}
		if ($params['menu_layout_desktop'] == 'hamburger' && intval($params['hamburger_canvas_width']) > 0) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' .thegem-te-menu__hamburger.desktop-view ul.nav-menu {width: ' . intval($params['hamburger_canvas_width']) . 'px;}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' .thegem-te-menu__hamburger.desktop-view:not(.hamburger-active) ul.nav-menu {transform: translateX(' . intval($params['hamburger_canvas_width']) . 'px);}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' .thegem-te-menu__hamburger.desktop-view.hamburger-active .hamburger-toggle-close {transform: translateX(-' . intval($params['hamburger_canvas_width']) . 'px);}';
		}
		if ($params['menu_layout_desktop'] == 'hamburger' || $params['menu_layout_desktop'] == 'overlay') {
			if ($params['hamburger_canvas_padding_top'] !== '') {
				$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' .thegem-te-menu__'.$params['menu_layout_desktop'].'.desktop-view ul.nav-menu {padding-top: ' . intval($params['hamburger_canvas_padding_top']) . 'px;}';
			}
			if ($params['hamburger_canvas_padding_bottom'] !== '') {
				$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' .thegem-te-menu__'.$params['menu_layout_desktop'].'.desktop-view ul.nav-menu {padding-bottom: ' . intval($params['hamburger_canvas_padding_bottom']) . 'px;}';
			}
			if ($params['hamburger_canvas_padding_left'] !== '') {
				$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' .thegem-te-menu__'.$params['menu_layout_desktop'].'.desktop-view ul.nav-menu {padding-left: ' . intval($params['hamburger_canvas_padding_left']) . 'px;}';
			}
			if ($params['hamburger_canvas_padding_right'] !== '') {
				$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' .thegem-te-menu__'.$params['menu_layout_desktop'].'.desktop-view ul.nav-menu {padding-right: ' . intval($params['hamburger_canvas_padding_right']) . 'px;}';
			}
		}

		$pointer_classes = '';
		if (isset($params['menu_pointer_style_hover']) && $params['menu_layout_desktop'] != 'overlay') {
			if (in_array($params['menu_pointer_style_hover'], ['frame-default', 'frame-rounded'])) {
				$pointer_classes = 'style-hover-framed style-hover-animation-'.$params['animation_framed'];
			} else if (in_array($params['menu_pointer_style_hover'], ['line-underline-1', 'line-underline-2', 'line-overline-1', 'line-overline-2', 'line-top-bottom'])) {
				$pointer_classes = 'style-hover-lined style-hover-animation-'.$params['animation_line'];
			} else if (in_array($params['menu_pointer_style_hover'], ['background-underline', 'background-color', 'background-rounded', 'background-extra-paddings'])) {
				$pointer_classes = 'style-hover-background style-hover-animation-'.$params['animation_background'];
			} else {
				$pointer_classes = 'style-hover-text';
			}
			$pointer_classes .= ' style-hover-type-'.$params['menu_pointer_style_hover'];
		}
		if (isset($params['menu_pointer_style_active']) && $params['menu_layout_desktop'] != 'overlay') {
			if (in_array($params['menu_pointer_style_active'], ['frame-default', 'frame-rounded'])) {
				$pointer_classes .= ' style-active-framed';
			} else if (in_array($params['menu_pointer_style_active'], ['line-underline-1', 'line-underline-2', 'line-overline-1', 'line-overline-2', 'line-top-bottom'])) {
				$pointer_classes .= ' style-active-lined';
			} else if (in_array($params['menu_pointer_style_active'], ['background-underline', 'background-color', 'background-rounded', 'background-extra-paddings'])) {
				$pointer_classes .= ' style-active-background';
			} else {
				$pointer_classes .= ' style-active-text';
			}
			$pointer_classes .= ' style-active-type-'.$params['menu_pointer_style_active'];
		}


        // Menu level 1 link preset classes
		$menu_level_1_link_classes = implode(' ', array($params['menu_lvl_1_text_style'], $params['menu_lvl_1_font_weight']));
		add_filter( 'nav_menu_link_attributes', array($this, 'thegem_templates_menu_first_level_link_class'), 10, 3);
		if (!empty($params['menu_lvl_1_letter_spacing'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > a {letter-spacing: ' . $params['menu_lvl_1_letter_spacing'] . 'px !important;}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > a ~ span {letter-spacing: ' . $params['menu_lvl_1_letter_spacing'] . 'px !important;}';
		}
		if (!empty($params['menu_lvl_1_text_transform'])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > a {text-transform: ' . $params['menu_lvl_1_text_transform'] . ' !important;}';
			$custom_css .= $shortcode . '.' . esc_attr($uniqid . $editor_suffix) . ' nav.desktop-view ul.nav-menu > li > a ~ span {text-transform: ' . $params['menu_lvl_1_text_transform'] . ' !important;}';
		}

		// Horizontal Menu Absolute Fix
		if ($params['menu_layout_desktop'] == 'default' && !empty($params['desktop_absolute'])){
			$custom_css .= $shortcode. '.' . esc_attr($uniqid.$editor_suffix) . '{width: max-content !important;}';
		}

		// Init Menu
		ob_start();
		if (!empty($params['menu_source']) && is_nav_menu($params['menu_source']) && !empty(wp_get_nav_menu_items($params['menu_source']))): ?>

		<div <?php if ($extra_id): ?>id="<?=esc_attr($extra_id); ?>"<?php endif;?> class="thegem-te-menu <?= esc_attr($extra_cls); ?> <?=esc_attr($menu_desktop_preset)?> <?=esc_attr($menu_mobile_preset)?> <?=esc_attr($uniqid)?> <?=esc_attr($pointer_classes)?>" <?=thegem_data_editor_attribute($uniqid.'-editor')?>>
			<nav id="<?=esc_attr($uniqid)?>" class="desktop-view thegem-te-menu__<?=$menu_type?> thegem-te-menu-mobile__<?=$menu_mobile_type?> <?=$menu_widget?> <?=$menu_mobile_widget?> <?php echo esc_attr($different_source) ?>"
				 data-tablet-landscape="<?=$params['menu_layout_tablet_landscape']?>"
				 data-tablet-portrait="<?=$params['menu_layout_tablet_portrait']?>"
				 data-desktop-breakpoint="<?= $desktop_breakpoint ?>"
				 data-tablet-breakpoint="<?= $tablet_breakpoint ?>"
				 data-mobile-breakpoint="<?= $mobile_breakpoint ?>"
				 role="navigation">

				<script type="text/javascript">
					(function ($) {
						const tabletLandscapeMaxWidth = <?= $desktop_breakpoint ?>;
						const tabletLandscapeMinWidth = <?= $tablet_breakpoint ?>;
						const tabletPortraitMaxWidth = <?= $tablet_breakpoint - 1 ?>;
						const tabletPortraitMinWidth = <?= $mobile_breakpoint ?>;
						let viewportWidth = $(window).width();
						let menu = $('#<?=esc_attr($uniqid)?>');
						if (menu.data("tablet-landscape") === 'default' && viewportWidth >= tabletLandscapeMinWidth && viewportWidth <= tabletLandscapeMaxWidth) {
							menu.removeClass('mobile-view').addClass('desktop-view');
						} else if (menu.data("tablet-portrait") === 'default' && viewportWidth >= tabletPortraitMinWidth && viewportWidth <= tabletPortraitMaxWidth) {
							menu.removeClass('mobile-view').addClass('desktop-view');
						} else if (viewportWidth <= tabletLandscapeMaxWidth) {
							menu.removeClass('desktop-view').addClass('mobile-view');
						} else {
							menu.removeClass('mobile-view').addClass('desktop-view');
						}
					})(jQuery);
				</script>

				<?php if ($params['is_mobile_default'] || $params['is_mobile_sliding']): ?>
					<button class="menu-toggle dl-trigger<?=thegem_te_delay_class()?>">
						<?php esc_html_e('Menu', 'thegem'); ?>
						<span class="menu-line-1"></span><span class="menu-line-2"></span><span class="menu-line-3"></span>
					</button>
				<?php endif; ?>

				<?php if ($params['is_hamburger']): ?>
					<button class="menu-toggle hamburger-toggle <?=$params['hamburger_icon_size']?><?=thegem_te_delay_class()?>">
						<?php esc_html_e('Menu', 'thegem'); ?>
						<span class="menu-line-1"></span><span class="menu-line-2"></span><span class="menu-line-3"></span>
					</button>
				<?php endif; ?>

				<?php if ($params['is_overlay']): ?>
					<button class="menu-toggle overlay-toggle <?=$params['hamburger_icon_size']?><?=thegem_te_delay_class()?>">
						<?php esc_html_e('Menu', 'thegem'); ?>
						<span class="menu-line-1"></span><span class="menu-line-2"></span><span class="menu-line-3"></span>
					</button>
				<?php endif; ?>

				<?php if ($params['is_hamburger']): ?>
					<div class="hamburger-menu-back">
						<button class="hamburger-toggle-close <?=$params['hamburger_icon_size']?>">
							<?php esc_html_e('Close', 'thegem'); ?>
							<span class="menu-line-1"></span><span class="menu-line-2"></span><span class="menu-line-3"></span>
						</button>
					</div>
				<?php endif; ?>

				<?php if ($params['is_overlay']): ?> <!--Overlay menu start-->
					<div class="overlay-menu-back">
						<button class="overlay-toggle-close <?=$params['hamburger_icon_size']?>">
							<?php esc_html_e('Close', 'thegem'); ?>
							<span class="menu-line-1"></span><span class="menu-line-2"></span><span class="menu-line-3"></span>
						</button>
					</div>
					<div class="overlay-menu-wrapper">
						<div class="overlay-menu-table">
							<div class="overlay-menu-row">
								<div class="overlay-menu-cell">
				<?php endif; ?>

				<?php if ($params['is_mobile_sliding']): ?><!--Mobile sliding start-->
					<div class="mobile-menu-slide-back"></div>
					<div class="mobile-menu-slide-wrapper">
						<button class="mobile-menu-slide-close"><?php esc_html_e('Close', 'thegem'); ?></button>
				<?php endif; ?>

				<?php
				$link_after = '';
				if ($params['submenu_indicator']) {
					if (isset($params['pack']) && $params['submenu_icon_' . str_replace("-", "", $params['pack'])] != '') {
						$link_after = thegem_build_icon($params['pack'], $params['submenu_icon_' . str_replace("-", "", $params['pack'])]);
					} else {
						$link_after = '<i class="default"></i>';
					}
				}

				if ($params['different_source_mobile']) {
					wp_nav_menu(array(
						"menu" => $params['menu_source_mobile'],
						"menu_class" => 'nav-menu mobile-menu-source ' . $params['menu_class'] . ' styled',
						"container" => null,
						"echo" => true,
						"link_after" => $link_after,
						"walker" => $params['walker']
					));
				}

				if($params['menu_layout_desktop'] == 'hamburger' || $params['menu_layout_desktop'] == 'overlay') {
					if($params['hamburger_overlay_menu_source'] == 'template' && !empty($params['hamburger_overlay_template'])) {
						$GLOBALS['thegem_menu_template'] = $params['hamburger_overlay_template'];
						add_filter('wp_nav_menu_items', 'thegem_templates_menu_insert_template', 10, 2);
						$params['menu_class'] .= ' hamburger-with-template';
						if($params['menu_layout_desktop'] == 'overlay' && $params['overlay_template_container'] == 'boxed') {
							$GLOBALS['thegem_menu_template_container'] = 1;
						}
					}
				}

				wp_nav_menu(array(
					"menu" => $params['menu_source'],
					"menu_class" => 'nav-menu ' . $params['menu_class'] . ' styled',
					"container" => null,
					"echo" => true,
					"link_after" => $link_after,
					"first_level_link_class" => $menu_level_1_link_classes,
					"walker" => $params['walker']
				));

				remove_filter('wp_nav_menu_items', 'thegem_templates_menu_insert_template', 10, 2);
				remove_filter('wp_nav_menu_items', 'thegem_templates_menu_search_widget', 10, 2);
				remove_filter('wp_nav_menu_items', 'thegem_templates_menu_socials_widget', 10, 2);
				remove_filter('wp_nav_menu_items', 'thegem_templates_menu_mobile_socials_widget', 10, 2);
				remove_filter('wp_nav_menu_items', 'thegem_add_menu_item_split_logo', 10, 2);
				remove_filter('nav_menu_link_attributes', array($this, 'thegem_templates_menu_first_level_link_class'), 10, 3);
                ?>

				<?php if ($params['is_mobile_sliding']): ?>
					</div>
				<?php endif; ?><!--Mobile sliding end-->

				<?php if ($params['is_overlay']): ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?><!--Overlay menu end-->
			</nav>
		</div>

		<?php if($params['menu_layout_desktop'] == 'split' && !empty($output_logo)): ?>
			<script type="text/javascript">

				(function ($) {
					let $wpbWrapper = $('.thegem-te-menu.<?=esc_attr($uniqid)?>').closest('.wpb_wrapper');
					$wpbWrapper[0].style.setProperty('flex-wrap', 'nowrap', 'important');

					$('.thegem-te-menu.<?=esc_attr($uniqid)?> .menu-item-type-split-logo > .logo-fullwidth-block').html('<div class="site-logo" style="<?= esc_attr($output_logo_style); ?>"><a href="<?= esc_url(home_url('/'))?>" rel="home"><span class="logo"><?= $output_logo; ?></span></a></div>');

					$('.thegem-te-menu.<?=esc_attr($uniqid)?> .nav-menu:not(.mobile-menu-source) > .menu-item').each(function (index) {
						if ($(this).hasClass('menu-item-type-split-logo')) {
							$(this).css('order', <?= $params['split_after_item']; ?>);
						} else {
							$(this).css('order', index + 1);
						}
					});
				})(jQuery);

				<?php if($params['split_logo_absolute']) { ?>
					(function ($) {

						function fullwidth_block_update($item) {
							var $page = $('#page'),
								pageOffset = $page.offset(),
								pagePaddingLeft = $page.css('padding-left'),
								pageWidth = $page.width();

							var $prevElement = $item.prev(),
								extra_padding = 0;
							if ($prevElement.length == 0 || $prevElement.hasClass('logo-fullwidth-block')) {
								$prevElement = $item.parent();
								extra_padding = parseInt($prevElement.css('padding-left'));
							}

							var offsetKey = window.gemSettings.isRTL ? 'right' : 'left';
							var cssData = {
								width: pageWidth
							};
							cssData[offsetKey] = pageOffset.left - ($prevElement.length ? $prevElement.offset().left : 0) + parseInt(pagePaddingLeft) - extra_padding;

							$item.css(cssData);
						}

						let $fullwidth = $(".thegem-te-menu.<?=esc_attr($uniqid)?> .nav-menu > .menu-item-type-split-logo > .logo-fullwidth-block");
						let $logo = $fullwidth.find('.site-logo');

						setTimeout(function () {
							fullwidth_block_update($fullwidth);
							$logo.css('opacity', '1');

							let resizeTimer;
							$(window).on('resize', function () {
								clearTimeout(resizeTimer);
								resizeTimer = setTimeout(function () {
									fullwidth_block_update($fullwidth);
								}, 500);
							});
						}, 250);

					})(jQuery);
				<?php } ?>

			</script>
		<?php endif; ?>

		<?php else : ?>
			<div class="bordered-box centered-box styled-subtitle">
				<?php echo esc_html__('Please select Menu Source', 'thegem') ?>
			</div>
		<?php endif;

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

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
			'name' => __('Menu', 'thegem'),
			'base' => 'thegem_te_menu',
			'icon' => 'thegem-icon-wpb-ui-element-menu',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('Site Menu (Header Builder)', 'thegem'),
			'params' => array_merge(
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Menu Source', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'param_name' => 'menu_source',
						'value' => thegem_get_menu_list(),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'description' => __('Go to the <a href="'.get_site_url().'/wp-admin/nav-menus.php" target="_blank">Menus screen</a> to manage your menus', 'thegem'),
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Different Source for Mobile', 'thegem'),
						'param_name' => 'different_source_mobile',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'param_name' => 'menu_source_mobile',
						'value' => thegem_get_menu_list(),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('General', 'thegem'),
						'dependency' => array(
							'element' => 'different_source_mobile',
							'not_empty' => true
						),
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Menu Layout', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'menu_layout_desktop',
						'value' => array(
							__('Horizontal', 'thegem') => 'default',
							__('Hamburger', 'thegem') => 'hamburger',
							__('Overlay', 'thegem') => 'overlay',
							__('Logo Split', 'thegem') => 'split',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Layout Mobile', 'thegem'),
						'param_name' => 'menu_layout_mobile',
						'value' => array(
							__('Default', 'thegem') => 'default',
							__('Overlay', 'thegem') => 'overlay',
							__('Slide Left', 'thegem') => 'slide-horizontal',
							__('Slide Top', 'thegem') => 'slide-vertical',
						),
						'std' => 'default',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Tablet (Landscape)', 'thegem'),
						'param_name' => 'menu_layout_tablet_landscape',
						'value' => array(
							__('As set for desktop', 'thegem') => 'default',
							__('As set for mobiles', 'thegem') => 'mobile',
						),
						'std' => 'default',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Tablet (Portrait)', 'thegem'),
						'param_name' => 'menu_layout_tablet_portrait',
						'value' => array(
							__('As set for desktop', 'thegem') => 'default',
							__('As set for mobiles', 'thegem') => 'mobile',
						),
						'std' => 'mobile',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Tablet Breakpoint', 'thegem'),
						'param_name' => 'menu_layout_tablet_breakpoint',
						'std' => '',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Mobile Breakpoint', 'thegem'),
						'param_name' => 'menu_layout_mobile_breakpoint',
						'std' => '',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Content for Offcanvas Area (Desktop)', 'thegem'),
						'param_name' => 'hamburger_overlay_menu_source',
						'value' => array(
							__('As set in Menu Source above', 'thegem') => 'default',
							__('TheGem Section Template', 'thegem') => 'template',
						),
						'std' => 'default',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('hamburger', 'overlay'),
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Section Template', 'thegem'),
						'param_name' => 'hamburger_overlay_template',
						'value' => array_flip(thegem_get_section_templates_list()),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
						'save_always' => true,
						'dependency' => array(
							'element' => 'hamburger_overlay_menu_source',
							'value' => 'template',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Hamburger Icon Size', 'thegem'),
						'param_name' => 'hamburger_icon_size',
						'value' => array(
							__('Default', 'thegem') => 'small',
							__('Large', 'thegem') => 'default',
							__('Small', 'thegem') => 'small',
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('hamburger', 'overlay')),
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Search In Menu', 'thegem'),
						'param_name' => 'search_menu_widget',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('General', 'thegem'),
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('hamburger', 'overlay')),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Stretch Menu on Desktop', 'thegem'),
						'param_name' => 'desktop_menu_stretch',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('General', 'thegem'),
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('default', 'split')),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Stretch Menu on Tablet (Landscape)', 'thegem'),
						'param_name' => 'tablet_landscape_menu_stretch',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('General', 'thegem'),
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('default', 'split'))
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Stretch Menu on Tablet (Portrait)', 'thegem'),
						'param_name' => 'tablet_portrait_menu_stretch',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('General', 'thegem'),
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('default', 'split'))
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Select Logo', 'thegem'),
						'param_name' => 'split_logo',
						'value' => array(
							__('Desktop Logo (Dark)', 'thegem') => 'desktop_logo_dark',
							__('Desktop Logo (Light)', 'thegem') => 'desktop_logo_light',
							__('Mobile Logo (Dark)', 'thegem') => 'mobile_logo_dark',
							__('Mobile Logo (Light)', 'thegem') => 'mobile_logo_light',
						),
						'std' => 'desktop_logo_dark',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('split')),
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Split Layout', 'thegem'),
						'param_name' => 'split_layout_type',
						'value' => array(
							__('Fullwidth', 'thegem') => 'full',
							__('Standard', 'thegem') => 'standard',
						),
						'std' => 'full',
						'dependency' => array(
							'element' => 'menu_layout_desktop', 'value' => array('split'),
							'callback' => 'thegem_templates_menu_split_callback'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Split after menu item', 'thegem'),
						'param_name' => 'split_after_item',
						'std' => 2,
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('split'))
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Logo Center Absolute', 'thegem'),
						'param_name' => 'split_logo_absolute',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
						'dependency' => array('element' => 'split_layout_type', 'value' => array('full', 'standard'))
					),
					array(
						'type' => 'textfield',
						'heading' => __('Split Left Margin', 'thegem'),
						'param_name' => 'split_logo_left_margin',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
						'dependency' => array('element' => 'split_layout_type', 'value' => array('standard'))
					),
					array(
						'type' => 'textfield',
						'heading' => __('Split Right Margin', 'thegem'),
						'param_name' => 'split_logo_right_margin',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
						'dependency' => array('element' => 'split_layout_type', 'value' => array('standard'))
					),
					array(
						'type' => 'textfield',
						'heading' => __('Dropdown Spacing', 'thegem'),
						'param_name' => 'dropdown_spacing',
						'std' => 20,
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('default', 'split'))
					),
					array(
						'type' => 'textfield',
						'heading' => __('Dropdown Spacing Mobile', 'thegem'),
						'param_name' => 'dropdown_spacing_mobile',
						'std' => 20,
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('default', 'split'))
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Search In Mobile Menu', 'thegem'),
						'param_name' => 'mobile_menu_search',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Socials In Menu', 'thegem'),
						'param_name' => 'socials_menu_widget',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('General', 'thegem'),
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('hamburger')),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Socials In Mobile Menu', 'thegem'),
						'param_name' => 'mobile_menu_socials',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('General', 'thegem'),
						'dependency' => array('element' => 'menu_layout_mobile', 'value' => array('slide-horizontal', 'slide-vertical')),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Submenu Indicator', 'thegem'),
						'param_name' => 'submenu_indicator',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('General', 'thegem'),
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('default', 'split', 'hamburger'))
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
						'group' => __('General', 'thegem'),
						'dependency' => array(
							'element' => 'submenu_indicator',
							'not_empty' => true
						),
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'submenu_icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'submenu_icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'submenu_icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'submenu_icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'submenu_icon_thegemheader',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'submenu_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
				)),
				array(
					array(
						'type' => 'textfield',
						'heading' => __('Icon Size', 'thegem'),
						'param_name' => 'submenu_icon_size',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
						'dependency' => array(
							'element' => 'submenu_indicator',
							'not_empty' => true
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Icon Left Margin', 'thegem'),
						'param_name' => 'submenu_icon_margin',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
						'dependency' => array(
							'element' => 'submenu_indicator',
							'not_empty' => true
						),
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Style Presets', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Submenu Color Presets', 'thegem'),
						'param_name' => 'layout_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Color Preset', 'thegem'),
						'param_name' => 'thegem_te_menu_skin',
						'value' => array(
							__('Inherit Theme Options', 'thegem') => 'inherit',
							__('Light', 'thegem') => 'light',
							__('Dark', 'thegem') => 'dark',
						),
						'std' => 'inherit',
						'dependency' => array(
							'callback' => 'thegem_te_menu_skin_callback',
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('General', 'thegem'),
						'description' => __('Submenu & responsive menu color presets', 'thegem'),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Main Menu Text Presets', 'thegem'),
						'param_name' => 'layout_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Text Style', 'thegem'),
						'param_name' => 'menu_lvl_1_text_style',
						'value' => array(
							__('Default', 'thegem') => '',
							__('Title H3', 'thegem') => 'title-h3',
							__('Title H4', 'thegem') => 'title-h4',
							__('Title H5', 'thegem') => 'title-h5',
							__('Title H6', 'thegem') => 'title-h6',
							__('Styled Subtitle', 'thegem') => 'styled-subtitle',
							__('Body', 'thegem') => 'title-text-body',
							__('Tiny Body', 'thegem') => 'title-text-body-tiny',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Font weight', 'thegem'),
                        'param_name' => 'menu_lvl_1_font_weight',
                        'value' => array(
                            __('Default', 'thegem') => '',
                            __('Thin', 'thegem') => 'light',
                         ),
                         'edit_field_class' => 'vc_column vc_col-sm-6',
                         'group' => __('General', 'thegem'),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Letter Spacing', 'thegem'),
                        'param_name' => 'menu_lvl_1_letter_spacing',
                        'edit_field_class' => 'vc_column vc_col-sm-6',
                        'group' => __('General', 'thegem'),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Text Transform', 'thegem'),
                        'param_name' => 'menu_lvl_1_text_transform',
                        'value' => array(
                            __('Default', 'thegem') => '',
                            __('None', 'thegem') => 'none',
                            __('Capitalize', 'thegem') => 'capitalize',
                            __('Lowercase', 'thegem') => 'lowercase',
                            __('Uppercase', 'thegem') => 'uppercase',
                        ),
                        'edit_field_class' => 'vc_column vc_col-sm-6',
                        'group' => __('General', 'thegem'),
                    ),
					array(
						'type' => 'thegem_delimeter_heading',
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'description' => __('To style default main menu typography go to <a href="'.get_site_url().'/wp-admin/admin.php?page=thegem-theme-options#/menu-and-header/typography" target="_blank">Theme Options → Menu & Header → Typography</a>.', 'thegem'),
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'thegem_hidden_param',
						'param_name' => 'ver',
						'std' => '5.3',
						'save_always' => true,
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Main Menu', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Hover Pointer', 'thegem'),
						'param_name' => 'menu_pointer_style_hover',
						'value' => array(
							__('Frame', 'thegem') => 'frame-default',
							__('Rounded Frame', 'thegem') => 'frame-rounded',
							__('Underline #1', 'thegem') => 'line-underline-1',
							__('Underline #2', 'thegem') => 'line-underline-2',
							__('Overline #1', 'thegem') => 'line-overline-1',
							__('Overline #2', 'thegem') => 'line-overline-2',
							__('Top & Bottom', 'thegem') => 'line-top-bottom',
							__('Background & Underline', 'thegem') => 'background-underline',
							__('Background Color', 'thegem') => 'background-color',
							__('Rounded Background', 'thegem') => 'background-rounded',
							__('Extra Paddings Background', 'thegem') => 'background-extra-paddings',
							__('Text Color', 'thegem') => 'text-color'
						),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'std' => 'text-color',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Active Pointer', 'thegem'),
						'param_name' => 'menu_pointer_style_active',
						'value' => array(
							__('Frame', 'thegem') => 'frame-default',
							__('Rounded Frame', 'thegem') => 'frame-rounded',
							__('Underline #1', 'thegem') => 'line-underline-1',
							__('Underline #2', 'thegem') => 'line-underline-2',
							__('Overline #1', 'thegem') => 'line-overline-1',
							__('Overline #2', 'thegem') => 'line-overline-2',
							__('Top & Bottom', 'thegem') => 'line-top-bottom',
							__('Background & Underline', 'thegem') => 'background-underline',
							__('Background Color', 'thegem') => 'background-color',
							__('Rounded Background', 'thegem') => 'background-rounded',
							__('Extra Paddings Background', 'thegem') => 'background-extra-paddings',
							__('Text Color', 'thegem') => 'text-color'
						),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'std' => 'frame-default',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Hover Animation', 'thegem'),
						'param_name' => 'animation_framed',
						'value' => array(
							__('Fade', 'thegem') => 'fade',
							__('Grow', 'thegem') => 'grow',
							__('Shrink', 'thegem') => 'shrink',
							__('Draw', 'thegem') => 'draw',
							__('Corners', 'thegem') => 'corners',
							__('None', 'thegem') => 'none',
						),
						'dependency' => array(
							'element' => 'menu_pointer_style_hover',
							'value' => array('frame-default', 'frame-rounded'),
						),
						'edit_field_class' => 'vc_column vc_col-sm-6 ',
						'std' => 'fade',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Hover Animation', 'thegem'),
						'param_name' => 'animation_line',
						'value' => array(
							__('Fade', 'thegem') => 'fade',
							__('Slide Left', 'thegem') => 'slide-left',
							__('Slide Right', 'thegem') => 'slide-right',
							__('Grow', 'thegem') => 'grow',
							__('Drop In', 'thegem') => 'drop-in',
							__('Drop Out', 'thegem') => 'drop-out',
							__('None', 'thegem') => 'none',
						),
						'dependency' => array(
							'element' => 'menu_pointer_style_hover',
							'value' => array('line-underline-1', 'line-underline-2', 'line-overline-1', 'line-overline-2', 'line-top-bottom'),
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'std' => 'fade',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Hover Animation', 'thegem'),
						'param_name' => 'animation_background',
						'value' => array(
							__('Fade', 'thegem') => 'fade',
							__('Grow', 'thegem') => 'grow',
							__('Shrink', 'thegem') => 'shrink',
							__('Sweep Left', 'thegem') => 'sweep-left',
							__('Sweep Right', 'thegem') => 'sweep-right',
							__('Sweep Up', 'thegem') => 'sweep-up',
							__('Sweep Down', 'thegem') => 'sweep-down',
							__('None', 'thegem') => 'none',
						),
						'dependency' => array(
							'element' => 'menu_pointer_style_hover',
							'value' => array('background-underline', 'background-color', 'background-rounded', 'background-extra-paddings'),
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'std' => 'fade',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hamburger Icon Color', 'thegem'),
						'param_name' => 'hamburger_icon_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('hamburger', 'overlay'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Close Icon Color', 'thegem'),
						'param_name' => 'close_icon_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('hamburger', 'overlay'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'background_color_hamburger',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => 'hamburger',
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Overlay Color', 'thegem'),
						'param_name' => 'background_color_overlay_hamburger',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => 'hamburger',
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'background_color_overlay',
						'edit_field_class' => 'vc_col-sm-12 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => 'overlay',
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'sub_delim_head_menu_item_text_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'color_menu_item',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'color_menu_item_overlay',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => 'overlay',
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover', 'thegem'),
						'param_name' => 'color_menu_item_hover',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover', 'thegem'),
						'param_name' => 'color_menu_item_overlay_hover',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => 'overlay',
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'color_menu_item_active',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'color_menu_item_overlay_active',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => 'overlay',
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'sub_delim_head_menu_item_background_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'background_color_menu_item',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover', 'thegem'),
						'param_name' => 'background_color_menu_item_hover',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'background_color_menu_item_active',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Pointer', 'thegem'),
						'param_name' => 'sub_delim_head_menu_item_pointer',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'pointer_color_menu_item_hover',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active Color', 'thegem'),
						'param_name' => 'pointer_color_menu_item_active',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Pointer Width Hover', 'thegem'),
						'param_name' => 'pointer_width_menu_item_hover',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Pointer Width Active', 'thegem'),
						'param_name' => 'pointer_width_menu_item_active',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Space Between', 'thegem'),
						'param_name' => 'menu_item_space_between',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split'),
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Padding', 'thegem'),
						'param_name' => 'sub_delim_head_menu_item_padding',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'menu_item_padding_top',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'menu_item_padding_right',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'menu_item_padding_bottom',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'menu_item_padding_left',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Radius', 'thegem'),
						'param_name' => 'menu_item_border_radius',
						'edit_field_class' => 'vc_col-sm-12 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Level 2 Text Color', 'thegem'),
						'param_name' => 'sub_delim_head_level2_text_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'text_color_level2',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover', 'thegem'),
						'param_name' => 'text_color_level2_hover',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'text_color_level2_active',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Level 2 Background Color', 'thegem'),
						'param_name' => 'sub_delim_head_level2_background_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'background_color_level2',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover', 'thegem'),
						'param_name' => 'background_color_level2_hover',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'background_color_level2_active',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Level 3+ Text Color', 'thegem'),
						'param_name' => 'sub_delim_head_level3_text_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'text_color_level3',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover', 'thegem'),
						'param_name' => 'text_color_level3_hover',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'text_color_level3_active',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Level 3+ Background Color', 'thegem'),
						'param_name' => 'sub_delim_head_level3_background_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'background_color_level3',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover', 'thegem'),
						'param_name' => 'background_color_level3_hover',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'background_color_level3_active',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Submenu Pointer Color', 'thegem'),
						'param_name' => 'sub_delim_head_pointer_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover', 'thegem'),
						'param_name' => 'submenu_hover_pointer_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'submenu_active_pointer_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Border', 'thegem'),
						'param_name' => 'submenu_border',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Level 2 Border Color', 'thegem'),
						'param_name' => 'submenu_level2_border_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'submenu_border',
							'not_empty' => true
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Level 3+ Border Color', 'thegem'),
						'param_name' => 'submenu_level3_border_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'submenu_border',
							'not_empty' => true
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Shadow', 'thegem'),
						'param_name' => 'sub_delim_head_submenu_shadow',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Enable Shadow', 'thegem'),
						'param_name' => 'submenu_enable_shadow',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Shadow color', 'thegem'),
						'param_name' => 'submenu_shadow_color',
						'dependency' => array(
							'element' => 'submenu_enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'std' => 'rgba(0, 0, 0, 0.15)',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Position', 'thegem'),
						'param_name' => 'submenu_shadow_position',
						'value' => array(
							__('Outline', 'thegem') => 'outline',
							__('Inset', 'thegem') => 'inset'
						),
						'dependency' => array(
							'element' => 'submenu_enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'std' => 'outline',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Horizontal', 'thegem'),
						'param_name' => 'submenu_shadow_horizontal',
						'dependency' => array(
							'element' => 'submenu_enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '0',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Vertical', 'thegem'),
						'param_name' => 'submenu_shadow_vertical',
						'dependency' => array(
							'element' => 'submenu_enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '5',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Blur', 'thegem'),
						'param_name' => 'submenu_shadow_blur',
						'dependency' => array(
							'element' => 'submenu_enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '5',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Spread', 'thegem'),
						'param_name' => 'submenu_shadow_spread',
						'dependency' => array(
							'element' => 'submenu_enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '-5',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Padding', 'thegem'),
						'param_name' => 'sub_delim_head_submenu_padding',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'submenu_padding_top',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'submenu_padding_right',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'submenu_padding_bottom',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'submenu_padding_left',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('default', 'split', 'hamburger'),
						),
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Mobile Menu', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hamburger Icon Color', 'thegem'),
						'param_name' => 'hamburger_icon_color_mobile',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Close Icon Color', 'thegem'),
						'param_name' => 'close_icon_color_mobile',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Close Icon Color', 'thegem'),
						'param_name' => 'close_icon_color_mobile_overlay',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => 'overlay',
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'mobile_menu_overlay_background_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => 'overlay',
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Social Icons Color', 'thegem'),
						'param_name' => 'mobile_menu_social_icon_color',
						'edit_field_class' => 'vc_col-sm-12 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'mobile_menu_socials',
							'not_empty' => true
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('1st Level Text Color', 'thegem'),
						'param_name' => 'sub_delim_head_mob_level1_text_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'mobile_menu_lvl_1_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'mobile_menu_lvl_1_color_active',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('1st Level Background Color', 'thegem'),
						'param_name' => 'sub_delim_head_mob_level1_background_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'mobile_menu_lvl_1_background_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'mobile_menu_lvl_1_background_color_active',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('1st Level Border', 'thegem'),
						'param_name' => 'mobile_menu_lvl_1_border',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'mobile_menu_lvl_1_border_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'mobile_menu_lvl_1_border',
							'not_empty' => true
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('2nd Level Text Color', 'thegem'),
						'param_name' => 'sub_delim_head_mob_level2_text_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'mobile_menu_lvl_2_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'mobile_menu_lvl_2_color_active',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('2nd Level Background Color', 'thegem'),
						'param_name' => 'sub_delim_head_mob_level2_background_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'mobile_menu_lvl_2_background_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'mobile_menu_lvl_2_background_color_active',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('2nd Level Border', 'thegem'),
						'param_name' => 'mobile_menu_lvl_2_border',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'mobile_menu_lvl_2_border_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'mobile_menu_lvl_2_border',
							'not_empty' => true
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('3+ Level Text Color', 'thegem'),
						'param_name' => 'sub_delim_head_mob_level3_text_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'mobile_menu_lvl_3_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'mobile_menu_lvl_3_color_active',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('3+ Level Background Color', 'thegem'),
						'param_name' => 'sub_delim_head_mob_level3_background_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'mobile_menu_lvl_3_background_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'mobile_menu_lvl_3_background_color_active',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('3+ Level Border', 'thegem'),
						'param_name' => 'mobile_menu_lvl_3_border',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => array('default', 'slide-horizontal', 'slide-vertical'),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'mobile_menu_lvl_3_border_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'mobile_menu_lvl_3_border',
							'not_empty' => true
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'sub_delim_head_mob_overlay_text_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => 'overlay',
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'mobile_menu_overlay_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => 'overlay',
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'mobile_menu_overlay_color_active',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_mobile',
							'value' => 'overlay',
						),
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Off Canvas Content Area', 'thegem'),
						'param_name' => 'hamburger_canvas_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => array('hamburger', 'overlay'),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bar Width (px)', 'thegem'),
						'param_name' => 'hamburger_canvas_width',
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('hamburger')),
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bar Width (px)', 'thegem'),
						'param_name' => 'hamburger_canvas_width',
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('hamburger')),
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Content Container Width', 'thegem'),
						'param_name' => 'overlay_template_container',
						'value' => array(
							__('Fullwidth', 'thegem') => 'fullwidth',
							__('Boxed', 'thegem') => 'boxed',
						),
						'dependency' => array(
							'element' => 'menu_layout_desktop',
							'value' => 'overlay',
						),
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Padding', 'thegem'),
						'param_name' => 'hamburger_canvas_paddings_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('hamburger', 'overlay')),
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'hamburger_canvas_padding_top',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('hamburger', 'overlay')),
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'hamburger_canvas_padding_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('hamburger', 'overlay')),
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'hamburger_canvas_padding_left',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('hamburger', 'overlay')),
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'hamburger_canvas_padding_right',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'dependency' => array('element' => 'menu_layout_desktop', 'value' => array('hamburger', 'overlay')),
						'group' => __('Appearance', 'thegem'),
					),
				),
				/* General - Extra */
				thegem_set_elements_extra_options(),
				/* Appearance */
				thegem_set_elements_design_options()
			),
		);
	}
}

$templates_elements['thegem_te_menu'] = new TheGem_Template_Element_Menu();
$templates_elements['thegem_te_menu']->init();
