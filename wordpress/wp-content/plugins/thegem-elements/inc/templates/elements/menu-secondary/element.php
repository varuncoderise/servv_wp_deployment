<?php

class TheGem_Template_Element_Menu_Secondary extends TheGem_Template_Element {

	public function __construct() {
		if ( !defined('THEGEM_TE_MENU_SECONDARY_DIR' )) {
			define('THEGEM_TE_MENU_SECONDARY_DIR', rtrim(__DIR__, ' /\\'));
		}

		if ( !defined('THEGEM_TE_MENU_SECONDARY_URL') ) {
			define('THEGEM_TE_MENU_SECONDARY_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}

		wp_register_style('thegem-te-menu-secondary', THEGEM_TE_MENU_SECONDARY_URL . '/css/menu-secondary.css');
		wp_register_style('thegem-te-menu-secondary-editor', THEGEM_TE_MENU_SECONDARY_URL . '/css/menu-secondary-editor.css');
		wp_register_script('thegem-te-menu-secondary', THEGEM_TE_MENU_SECONDARY_URL . '/js/menu-secondary.js', array('jquery'), false, true);
	}

	public function get_name() {
		return 'thegem_te_menu_secondary';
	}

	public function head_scripts($attr) {
		wp_enqueue_script('thegem-te-menu-secondary');
		wp_enqueue_style('thegem-te-menu-secondary');
	}

	public function front_editor_scripts() {
		wp_enqueue_style('thegem-te-menu-secondary');
	}

	public function shortcode_output($atts, $content = '') {
		$params = shortcode_atts(array_merge(array(
			'menu_source' => '',
			'type' => 'list',
			'dropdown_spacing' => '20',
			'text_color' => '',
			'text_color_hover' => '',
			'text_color_active' => '',
			'dropdown_background_color' => '',
			'dropdown_shadow' => '',
			'dropdown_shadow_color' => 'rgba(0, 0, 0, 0.05)',
			'dropdown_shadow_position' => 'outline',
			'dropdown_shadow_horizontal' => '0',
			'dropdown_shadow_vertical' => '0',
			'dropdown_shadow_blur' => '20',
			'dropdown_shadow_spread' => '0',
			'dropdown_padding_top' => '',
			'dropdown_padding_right' => '',
			'dropdown_padding_bottom' => '',
			'dropdown_padding_left' => '',
			'list_arrow_prefix' => '1',
			'list_item_space_between' => '',
			'walker' => new thegem_walker_primary_nav_menu,
		), thegem_templates_design_options_extract(), thegem_templates_extra_options_extract()), $atts, 'thegem_te_menu_secondary');

		//General
		$extra_id = $extra_cls = '';
		if (!empty($params['element_id'])){ $extra_id = $params['element_id']; }
		if (!empty($params['element_class'])){ $extra_cls = $params['element_class']; }

		// Init Design Options Params
		$return_html = $custom_css = $uniqid = '';
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-menu-secondary', $params);
        
        // Get dropdown current title
		$current_item_title = '';
		if ( $menu_items = wp_get_nav_menu_items( $params['menu_source'] ) ) {
			foreach ( $menu_items as $menu_item ) {
				if ($menu_item->object_id == get_queried_object_id()) {
					$current_item_title = $menu_item->title;
				} elseif($menu_item->menu_order == 1) {
					$current_item_title = $menu_item->title;
                }
			}
		}

		// Init Menu
		ob_start(); ?>

		<div <?php if ($extra_id): ?>id="<?=esc_attr($extra_id); ?>"<?php endif;?> class="thegem-te-menu-secondary <?= esc_attr($extra_cls); ?> <?=esc_attr($uniqid)?>" <?=thegem_data_editor_attribute($uniqid.'-editor')?>>
            <?php if($params['type'] == 'list'): ?>
                <div class="thegem-te-menu-secondary-nav">
                    <?= wp_nav_menu(array("menu" => $params['menu_source'], 'menu_class' => 'nav-menu styled', 'container' => false, "echo" => false, "walker" => $params['walker'])); ?>
                </div>
            <?php endif; ?>
			
			<?php if($params['type'] == 'dropdown'): ?>
                <div class="thegem-te-menu-secondary-dropdown">
                    <div class="dropdown-item">
                        <div class="dropdown-item__current"><?= $current_item_title ?></div>
                        <div class="dropdown-item__wrapper">
			                <?= wp_nav_menu(array("menu" => $params['menu_source'], 'menu_class' => 'nav-menu styled', 'container' => false, "echo" => false, "walker" => $params['walker'])); ?>
                        </div>
                    </div>
                </div>
			<?php endif; ?>
		</div>

		<?php

		// Icon Custom Styles
		$customize = '.thegem-te-menu-secondary.'.$uniqid;
		
		// General Colors
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .thegem-te-menu-secondary-dropdown .dropdown-item__wrapper ul > li > a {color: ' . $params['text_color'] . ';}';
			$custom_css .= $customize.' .thegem-te-menu-secondary-nav ul.nav-menu > li > a {color: ' . $params['text_color'] . ';}';
		}
		if (!empty($params['text_color_hover'])) {
			$custom_css .= $customize.' .thegem-te-menu-secondary-dropdown .dropdown-item__wrapper ul > li > a:hover {color: ' . $params['text_color_hover'] . ';}';
			$custom_css .= $customize.' .thegem-te-menu-secondary-nav ul.nav-menu > li > a:hover {color: ' . $params['text_color_hover'] . ';}';
		}
		if (!empty($params['text_color_active'])) {
			$custom_css .= $customize.' .thegem-te-menu-secondary-dropdown .dropdown-item__current {color: ' . $params['text_color_active'] . ';}';
			$custom_css .= $customize.' .thegem-te-menu-secondary-dropdown .dropdown-item:after {color: ' . $params['text_color_active'] . ';}';
			$custom_css .= $customize.' .thegem-te-menu-secondary-nav ul.nav-menu > li.menu-item-active > a {color: ' . $params['text_color_active'] . ';}';
		}
		
		// Dropdown
		if (!empty($params['dropdown_spacing'])) {
			$custom_css .= $customize.' .thegem-te-menu-secondary-nav ul.nav-menu > li.menu-item-has-children {margin-bottom: -' . $params['dropdown_spacing'] . 'px; padding-bottom: ' . $params['dropdown_spacing'] . 'px;}';
			$custom_css .= $customize.' .thegem-te-menu-secondary-dropdown .dropdown-item {margin-bottom: -' . $params['dropdown_spacing'] . 'px; padding-bottom: ' . $params['dropdown_spacing'] . 'px;}';
		}
		if (!empty($params['dropdown_background_color'])) {
			$custom_css .= $customize.' .thegem-te-menu-secondary-dropdown .dropdown-item__wrapper { background-color: ' . $params['dropdown_background_color'] . ';}';
		}
		if(!empty($params['dropdown_shadow'])) {
			$shadow_position = '';
			if($params['dropdown_shadow_position'] == 'inset') {
				$shadow_position = 'inset';
			}
			if(empty($params['dropdown_shadow_horizontal'])) {
				$params['dropdown_shadow_horizontal'] = 0;
			}
			if(empty($params['dropdown_shadow_vertical'])) {
				$params['dropdown_shadow_vertical'] = 0;
			}
			if(empty($params['dropdown_shadow_blur'])) {
				$params['dropdown_shadow_blur'] = 0;
			}
			if(empty($params['dropdown_shadow_spread'])) {
				$params['dropdown_shadow_spread'] = 0;
			}
			if(empty($params['dropdown_shadow_color'])) {
				$params['dropdown_shadow_color'] = '#000';
			}
			$custom_css .= $customize.' .thegem-te-menu-secondary-dropdown .dropdown-item__wrapper {'
				. '-webkit-box-shadow: '.$shadow_position.' '.$params['dropdown_shadow_horizontal'].'px '.$params['dropdown_shadow_vertical'].'px '.$params['dropdown_shadow_blur'].'px '.$params['dropdown_shadow_spread'].'px '.$params['dropdown_shadow_color'].';'
				. '-moz-box-shadow: '.$shadow_position.' '.$params['dropdown_shadow_horizontal'].'px '.$params['dropdown_shadow_vertical'].'px '.$params['dropdown_shadow_blur'].'px '.$params['dropdown_shadow_spread'].'px '.$params['dropdown_shadow_color'].';;'
				. '-o-box-shadow: '.$shadow_position.' '.$params['dropdown_shadow_horizontal'].'px '.$params['dropdown_shadow_vertical'].'px '.$params['dropdown_shadow_blur'].'px '.$params['dropdown_shadow_spread'].'px '.$params['dropdown_shadow_color'].';;'
				. 'box-shadow: '.$shadow_position.' '.$params['dropdown_shadow_horizontal'].'px '.$params['dropdown_shadow_vertical'].'px '.$params['dropdown_shadow_blur'].'px '.$params['dropdown_shadow_spread'].'px '.$params['dropdown_shadow_color'].';;'
				. '}';
		}
		if (isset($params['dropdown_padding_top'])) {
			$custom_css .= $customize.' .thegem-te-menu-secondary-dropdown .dropdown-item__wrapper {padding-top: ' . $params['dropdown_padding_top'] . 'px;}';
		}
		if (isset($params['dropdown_padding_right'])) {
			$custom_css .= $customize.' .thegem-te-menu-secondary-dropdown .dropdown-item__wrapper {padding-right: ' . $params['dropdown_padding_right'] . 'px;}';
		}
		if (isset($params['dropdown_padding_bottom'])) {
			$custom_css .= $customize.' .thegem-te-menu-secondary-dropdown .dropdown-item__wrapper {padding-bottom: ' . $params['dropdown_padding_bottom'] . 'px;}';
		}
		if (isset($params['dropdown_padding_left'])) {
			$custom_css .= $customize.' .thegem-te-menu-secondary-dropdown .dropdown-item__wrapper {padding-left: ' . $params['dropdown_padding_left'] . 'px; margin-left: -' . $params['dropdown_padding_left'] . 'px; min-width: calc(100% + '. $params['dropdown_padding_left'] . 'px);}';
		}
		
		// List
		if(empty($params['list_arrow_prefix'])) {
			$custom_css .= $customize.' .thegem-te-menu-secondary-nav ul.nav-menu > li > a:before {display: none;}';
		}
		if (!empty($params['list_item_space_between'])) {
			$custom_css .= $customize.' .thegem-te-menu-secondary-nav ul.nav-menu {margin: 0 calc(-' . $params['list_item_space_between'] . 'px/2);}';
			$custom_css .= $customize.' .thegem-te-menu-secondary-nav ul.nav-menu > li {padding: 0 calc(' . $params['list_item_space_between'] . 'px/2);}';
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

	public function shortcode_settings() {
		return array(
			'name' => __('Secondary Menu', 'thegem'),
			'base' => 'thegem_te_menu_secondary',
			'icon' => 'thegem-icon-wpb-ui-element-secondary-menu',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('Secondary Menu (Header Builder)', 'thegem'),
			'params' => array_merge(
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Menu', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Menu Source', 'thegem'),
						'param_name' => 'menu_source',
						'value' => thegem_get_menu_list(),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'description' => __('Go to the <a href="'.get_site_url().'/wp-admin/nav-menus.php" target="_blank">Menus screen</a> to manage your menus', 'thegem'),
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Menu Type', 'thegem'),
						'param_name' => 'type',
						'value' => array(
							__('List', 'thegem') => 'list',
							__('Dropdown', 'thegem') => 'dropdown',
						),
						'std' => 'list',
						'dependency' => array(
							'callback' => 'thegem_templates_switcers_callback'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Dropdown Spacing', 'thegem'),
						'param_name' => 'dropdown_spacing',
						'std' => 20,
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('General', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
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
						'param_name' => 'text_color',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover', 'thegem'),
						'param_name' => 'text_color_hover',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'text_color_active',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Dropdown', 'thegem'),
						'param_name' => 'layout_delim_head',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'thegem-param-delimeter-heading param--dropdown no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'dropdown_background_color',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Box Shadow', 'thegem'),
						'param_name' => 'dropdown_shadow',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Shadow color', 'thegem'),
						'param_name' => 'dropdown_shadow_color',
						'dependency' => array('element' => 'dropdown_shadow', 'not_empty' => true),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'std' => 'rgba(0, 0, 0, 0.05)',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Position', 'thegem'),
						'param_name' => 'dropdown_shadow_position',
						'value' => array(
							__('Outline', 'thegem') => 'outline',
							__('Inset', 'thegem') => 'inset'
						),
						'dependency' => array('element' => 'dropdown_shadow', 'not_empty' => true),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'std' => 'outline',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Horizontal', 'thegem'),
						'param_name' => 'dropdown_shadow_horizontal',
						'dependency' => array('element' => 'dropdown_shadow', 'not_empty' => true),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '0',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Vertical', 'thegem'),
						'param_name' => 'dropdown_shadow_vertical',
						'dependency' => array('element' => 'dropdown_shadow', 'not_empty' => true),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '0',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Blur', 'thegem'),
						'param_name' => 'dropdown_shadow_blur',
						'dependency' => array('element' => 'dropdown_shadow', 'not_empty' => true),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '20',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Spread', 'thegem'),
						'param_name' => 'dropdown_shadow_spread',
						'dependency' => array('element' => 'dropdown_shadow', 'not_empty' => true),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '0',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Padding', 'thegem'),
						'param_name' => 'sub_delim_head_dropdown_padding',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'dropdown_padding_top',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'dropdown_padding_right',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'dropdown_padding_bottom',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'dropdown_padding_left',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('List', 'thegem'),
						'param_name' => 'layout_delim_head',
						'dependency' => array('element' => 'type', 'value' => 'list'),
						'edit_field_class' => 'thegem-param-delimeter-heading param--list no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Arrow Prefix', 'thegem'),
						'param_name' => 'list_arrow_prefix',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => 1,
						'dependency' => array('element' => 'type', 'value' => 'list'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Space Between', 'thegem'),
						'param_name' => 'list_item_space_between',
						'dependency' => array('element' => 'type', 'value' => 'list'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
					),
				),

				/* General - Extra */
				thegem_set_elements_extra_options(),

				/* Design Options */
				thegem_set_elements_design_options()
			),
		);
	}
}

$templates_elements['thegem_te_menu_secondary'] = new TheGem_Template_Element_Menu_Secondary();
$templates_elements['thegem_te_menu_secondary']->init();