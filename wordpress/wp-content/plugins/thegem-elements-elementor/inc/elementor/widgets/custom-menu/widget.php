<?php

namespace TheGem_Elementor\Widgets\TheGem_Custom_Menu;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) exit;


/**
 * Elementor widget for Custom Menu.
 */
class TheGem_Custom_Menu extends Widget_Base {

	public function __construct($data = [], $args = null) {

		parent::__construct($data, $args);

		if (!defined('THEGEM_ELEMENTOR_WIDGET_MENU_CUSTOM_DIR')) {
			define('THEGEM_ELEMENTOR_WIDGET_MENU_CUSTOM_DIR', rtrim(__DIR__, ' /\\'));
		}

		if (!defined('THEGEM_ELEMENTOR_WIDGET_MENU_CUSTOM_URL')) {
			define('THEGEM_ELEMENTOR_WIDGET_MENU_CUSTOM_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}

		wp_register_style('thegem-menu-custom', THEGEM_ELEMENTOR_WIDGET_MENU_CUSTOM_URL . '/assets/css/thegem-menu-custom.css');
	}


	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */

	public function get_name() {

		return 'thegem-custom-menu';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */

	public function get_title() {

		return __('Custom Menu', 'thegem');
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */

	public function get_icon() {
		return str_replace('thegem-', 'thegem-eicon thegem-eicon-', $this->get_name());
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		if (get_post_type() === 'thegem_templates' && thegem_get_template_type(get_the_id()) === 'megamenu') {
			return ['thegem_megamenu_builder'];
		}
		return ['thegem_elements'];
	}

	public function get_style_depends() {
		return ['thegem-menu-custom'];
	}

	public function get_script_depends() {
		return [];
	}

	public function is_reload_preview_required() {
		return true;
	}

	protected function get_menu_list() {
		$menus = get_terms('nav_menu');
		$menus_list = [];
		foreach ($menus as $menu) {
			$menus_list[$menu->slug] = $menu->name;
		}
		return $menus_list;
	}


	/**
	 * Register the widget controls.
	 *
	 * @access protected
	 */
	protected function register_controls() {

		// Sections Layout
		$this->start_controls_section(
			'source',
			[
				'label' => __('Menu Source', 'thegem'),
			]
		);

		$this->add_control(
			'menu_source',
			[
				'label' => __('Menu Source', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'nav_menu',
				'options' => [
					'nav_menu' => __('Appearance -> Menus', 'thegem'),
					'custom' => __('Custom List', 'thegem'),
				],
			]
		);

		$this->add_control(
			'nav_menu',
			[
				'label' => __('Select Menu', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_menu_list(),
				'description' => __('Go to the <a href="' . get_site_url() . '/wp-admin/nav-menus.php" target="_blank">Menus screen</a> to manage your menus.', 'thegem'),
				'condition' => [
					'menu_source' => 'nav_menu'
				],
			]
		);
		
		$this->add_control(
			'submenu_style',
			[
				'label' => __('Custom Menu Style', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'vertical' => __('Vertical', 'thegem'),
					'horizontal' => __('Horizontal', 'thegem'),
				],
				'default' => 'vertical',
			]
		);

		$this->add_control(
			'font_preset',
			[
				'label' => __('Font Preset', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'submenu-item' => __('Submenu', 'thegem'),
					'main-menu-item' => __('Main Menu', 'thegem'),
					'title-h1' => __('Title H1', 'thegem'),
					'title-h2' => __('Title H2', 'thegem'),
					'title-h3' => __('Title H3', 'thegem'),
					'title-h4' => __('Title H4', 'thegem'),
					'title-h5' => __('Title H5', 'thegem'),
					'title-h6' => __('Title H6', 'thegem'),
					'title-xlarge' => __('Title xLarge', 'thegem'),
					'styled-subtitle' => __('Styled Subtitle', 'thegem'),
					'text-body' => __('Body', 'thegem'),
					'text-body-tiny' => __('Tiny Body', 'thegem'),
				],
				'default' => 'submenu-item',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_title',
			[
				'label' => __('Title', 'thegem'),
				'type' => Controls_Manager::TEXTAREA,
			]
		);

		$repeater->add_control(
			'item_link',
			[
				'label' => __('Link', 'thegem'),
				'type' => Controls_Manager::URL,
			]
		);

		$repeater->add_control(
			'item_icon',
			[
				'label' => __('Icon', 'thegem'),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
			]
		);

		$repeater->add_control(
			'item_icon_select',
			[
				'label' => __('Select Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
				'condition' => [
					'item_icon' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'item_label_text',
			[
				'label' => __('Label Text', 'thegem'),
				'type' => Controls_Manager::TEXTAREA,
			]
		);

		$repeater->add_control(
			'item_label_color',
			[
				'label' => __('Label Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
			]
		);

		$repeater->add_control(
			'item_label_background',
			[
				'label' => __('Label Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'menu_custom',
			[
				'label' => __('Custom List', 'thegem'),
				'type' => Controls_Manager::REPEATER,
				'show_label' => false,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{item_title}}',
				'default' => [
					[
						'item_title' => 'Custom Link 1',
					],
					[
						'item_title' => 'Custom Link 2',
					],
					[
						'item_title' => 'Custom Link 3',
					]
				],
				'condition' => [
					'menu_source' => 'custom'
				],
			]
		);

		$this->add_control(
			'menu_indicator',
			[
				'label' => __('Menu Indicator', 'thegem'),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
			]
		);
  
		$this->add_control(
			'submenu_separator',
			[
				'label' => __('Custom Menu Separator', 'thegem'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'submenu_style' => 'horizontal',
				],
			]
		);

		$this->end_controls_section();

		// Sections Heading
		$this->start_controls_section(
			'heading',
			[
				'label' => __('Heading', 'thegem'),
			]
		);

		$this->add_control(
			'heading_text',
			[
				'label' => __('Heading Text', 'thegem'),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __('This is heading', 'thegem'),
			]
		);

		$this->add_control(
			'heading_icon',
			[
				'label' => __('Heading Icon', 'thegem'),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
			]
		);

		$this->add_control(
			'heading_icon_select',
			[
				'label' => __('Select Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
				'condition' => [
					'heading_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_link',
			[
				'label' => __('Link', 'thegem'),
				'type' => Controls_Manager::URL,
			]
		);

		$this->add_control(
			'heading_tag',
			[
				'label' => __('HTML Tag', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'p' => 'p',
					'div' => 'div',
				],
				'default' => 'div',
			]
		);

		$this->add_control(
			'heading_div_style',
			[
				'label' => __('Style', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'main-menu-item' => __('Main Menu', 'thegem'),
					'title-h1' => __('Title H1', 'thegem'),
					'title-h2' => __('Title H2', 'thegem'),
					'title-h3' => __('Title H3', 'thegem'),
					'title-h4' => __('Title H4', 'thegem'),
					'title-h5' => __('Title H5', 'thegem'),
					'title-h6' => __('Title H6', 'thegem'),
					'title-xlarge' => __('Title xLarge', 'thegem'),
					'styled-subtitle' => __('Styled Subtitle', 'thegem'),
					'text-body' => __('Body', 'thegem'),
					'text-body-tiny' => __('Tiny Body', 'thegem'),
				],
				'default' => 'main-menu-item',
			]
		);

		$this->add_control(
			'heading_label_text',
			[
				'label' => __('Label Text', 'thegem'),
				'type' => Controls_Manager::TEXTAREA,
			]
		);

		$this->add_control(
			'heading_separator',
			[
				'label' => __('Separator', 'thegem'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
			]
		);

		$this->end_controls_section();

		$this->add_styles_controls($this);
	}

	/**
	 * Heading Style
	 * @access protected
	 */
	protected function heading_style($control) {

		$control->start_controls_section(
			'heading_section',
			[
				'label' => __('Heading', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$control->add_responsive_control(
			'heading_alignment',
			[
				'label' => __('Alignment', 'thegem'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'thegem'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'thegem'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'thegem'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
				'selectors_dictionary' => [
					'left' => 'justify-content: flex-start;',
					'center' => 'justify-content: center;',
					'right' => 'justify-content: flex-end;',
				],
				'selectors' => [
					'{{WRAPPER}} .menu-custom-header a,
					{{WRAPPER}} .menu-custom-header > span,
					{{WRAPPER}} .menu-custom-header .separator' => '{{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .menu-custom-header',
			]
		);

		$control->start_controls_tabs('tabs_heading_style');

		$control->start_controls_tab(
			'heading_style_normal',
			[
				'label' => __('Normal', 'thegem'),
			]
		);

		$control->add_control(
			'heading_color',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-custom-header' => 'color: {{VALUE}}',
				],
			]
		);

		$control->add_control(
			'heading_icon_color',
			[
				'label' => __('Icon Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-custom-header .icon' => 'color: {{VALUE}}',
				],
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab(
			'heading_style_hover',
			[
				'label' => __('Hover', 'thegem'),
			]
		);

		$control->add_control(
			'heading_color_hover',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-custom-header:hover a,
					{{WRAPPER}} .menu-custom-header:hover > span' => 'color: {{VALUE}}',
				],
			]
		);

		$control->add_control(
			'heading_icon_color_hover',
			[
				'label' => __('Icon Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-custom-header .icon' => 'transition: all 0.3s;',
					'{{WRAPPER}} .menu-custom-header:hover .icon' => 'color: {{VALUE}}',
				],
			]
		);

		$control->end_controls_tab();

		$control->end_controls_tabs();

		$control->add_control(
			'label_header',
			[
				'label' => __('Label', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .menu-custom-header .label',
			]
		);

		$control->add_control(
			'heading_label_color',
			[
				'label' => __('Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-custom-header .label' => 'color: {{VALUE}}',
				],
			]
		);

		$control->add_control(
			'heading_label_background',
			[
				'label' => __('Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-custom-header .label' => 'background-color: {{VALUE}}',
				],
			]
		);

		$control->add_control(
			'separator_header',
			[
				'label' => __('Separator', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'heading_separator' => 'yes',
				],
			]
		);

		$control->add_control(
			'heading_separator_color',
			[
				'label' => __('Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-custom-header .separator span' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'heading_separator' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'heading_separator_weight',
			[
				'label' => __('Weight, px', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 6,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .menu-custom-header .separator span' => 'height:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'heading_separator' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'heading_separator_width',
			[
				'label' => __('Width', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'rem', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'rem' => [
						'min' => 1,
						'max' => 100,
					],
					'em' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .menu-custom-header .separator span' => 'width:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'heading_separator' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'heading_separator_top_spacing',
			[
				'label' => __('Top Spacing', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .menu-custom-header .separator' => 'margin-top:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'heading_separator' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'heading_bottom_spacing',
			[
				'label' => __('Bottom Spacing', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .menu-custom-header' => 'margin-bottom:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$control->end_controls_section();
	}

	/**
	 * Menu Style
	 * @access protected
	 */
	protected function menu_style($control) {
		$control->start_controls_section(
			'menu_section',
			[
				'label' => __('Menu / List', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$control->add_responsive_control(
			'menu_alignment',
			[
				'label' => __('Alignment', 'thegem'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'thegem'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'thegem'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'thegem'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
			]
		);
		
		$this->add_control(
			'menu_item_text_style',
			[
				'label' => __('Font Preset', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'submenu-item' => __('Submenu', 'thegem'),
					'title-main-menu' => __('Main Menu', 'thegem'),
					'title-h1' => __('Title H1', 'thegem'),
					'title-h2' => __('Title H2', 'thegem'),
					'title-h3' => __('Title H3', 'thegem'),
					'title-h4' => __('Title H4', 'thegem'),
					'title-h5' => __('Title H5', 'thegem'),
					'title-h6' => __('Title H6', 'thegem'),
					'title-xlarge' => __('Title xLarge', 'thegem'),
					'styled-subtitle' => __('Styled Subtitle', 'thegem'),
					'text-body' => __('Body', 'thegem'),
					'text-body-tiny' => __('Tiny Body', 'thegem'),
				],
				'default' => 'submenu-item',
			]
		);
		
		$this->add_control(
			'menu_item_font_weight',
			[
				'label' => __('Font weight', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => __('Default', 'thegem'),
					'light' => __('Thin', 'thegem'),
				],
				'default' => 'default',
			]
		);
		
		$this->add_control(
			'menu_item_letter_spacing',
			[
				'label' => __('Letter Spacing', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li a,
					{{WRAPPER}} ul.nav-menu-custom li > span' => 'letter-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'menu_item_text_transform',
			[
				'label' => __('Text Transform', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => __('Default', 'thegem'),
					'none' => __('None', 'thegem'),
					'capitalize' => __('Capitalize', 'thegem'),
					'lowercase' => __('Lowercase', 'thegem'),
					'uppercase' => __('Uppercase', 'thegem'),
				],
				'default' => 'default',
				'selectors_dictionary' => [
					'default' => '',
					'none' => 'text-transform: none;',
					'capitalize' => 'text-transform: capitalize;',
					'lowercase' => 'text-transform: lowercase;',
					'uppercase' => 'text-transform: uppercase;',
				],
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li a,
					{{WRAPPER}} ul.nav-menu-custom li > span' => '{{VALUE}};',
				],
			]
		);
  
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'menu_typography',
				'selector' => '{{WRAPPER}} ul.nav-menu-custom li a,
					{{WRAPPER}} ul.nav-menu-custom li > span',
			]
		);
		
		$control->add_responsive_control(
			'menu_item_spacing_vertical',
			[
				'label' => __('Spacing', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => [
					'submenu_style' => 'vertical',
				],
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
  
		$control->add_responsive_control(
			'menu_item_spacing_horizontal',
			[
				'label' => __('Spacing', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => [
					'submenu_style' => 'horizontal',
				],
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
        // Separator Styles
		$control->add_control(
			'label_submenu_separator',
			[
				'label' => __('Separator', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'submenu_style' => 'horizontal',
					'submenu_separator' => 'yes',
				],
			]
		);
  
		$this->add_control(
			'submenu_separator_weight',
			[
				'label' => __('Weight', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'condition' => [
					'submenu_style' => 'horizontal',
					'submenu_separator' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li:before' => 'width: {{SIZE}}{{UNIT}} !important',
				],
			]
		);
  
		$this->add_control(
			'submenu_separator_height',
			[
				'label' => __('Height', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'condition' => [
					'submenu_style' => 'horizontal',
					'submenu_separator' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li:before' => 'height: {{SIZE}}{{UNIT}} !important',
				],
			]
		);
  
		$this->add_control(
			'submenu_separator_color',
			[
				'label' => __('Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'condition' => [
					'submenu_separator' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li:before' => 'background-color: {{VALUE}} !important;',
				],
			]
		);
		
		// Pointer Styles
		$control->add_control(
			'label_submenu_pointer',
			[
				'label' => __('Pointers', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'menu_pointer_style_hover',
			[
				'label' => __('Hover Pointer', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'background-color' => __('Background Color', 'thegem'),
					'background-rounded' => __('Rounded Background', 'thegem'),
					'frame-default' => __('Frame', 'thegem'),
					'frame-rounded' => __('Rounded Frame', 'thegem'),
					'line-underline-1' => __('Underline', 'thegem'),
					'line-overline-1' => __('Overline', 'thegem'),
					'line-top-bottom' => __('Top & Bottom', 'thegem'),
					'text-color' => __('Text Color', 'thegem'),
				],
				'default' => 'background-color',
			]
		);
  
		$this->add_control(
			'menu_pointer_style_active',
			[
				'label' => __('Active Pointer', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'background-color' => __('Background Color', 'thegem'),
					'background-rounded' => __('Rounded Background', 'thegem'),
					'frame-default' => __('Frame', 'thegem'),
					'frame-rounded' => __('Rounded Frame', 'thegem'),
					'line-underline-1' => __('Underline', 'thegem'),
					'line-overline-1' => __('Overline', 'thegem'),
					'line-top-bottom' => __('Top & Bottom', 'thegem'),
					'text-color' => __('Text Color', 'thegem'),
				],
				'default' => 'background-color',
			]
		);
  
		$this->add_control(
			'animation_framed',
			[
				'label' => __('Hover Animation', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'fade' => __('Fade', 'thegem'),
					'grow' => __('Grow', 'thegem'),
					'shrink' => __('Shrink', 'thegem'),
					'draw' => __('Draw', 'thegem'),
					'corners' => __('Corners', 'thegem'),
					'none' => __('None', 'thegem'),
				],
				'default' => 'fade',
				'condition' => [
					'menu_pointer_style_hover' => ['frame-default', 'frame-rounded'],
				],
			]
		);
  
		$this->add_control(
			'animation_line',
			[
				'label' => __('Hover Animation', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'fade' => __('Fade', 'thegem'),
					'slide-left' => __('Slide Left', 'thegem'),
					'slide-right' => __('Slide Right', 'thegem'),
					'grow' => __('Grow', 'thegem'),
					'drop-in' => __('Drop In', 'thegem'),
					'drop-out' => __('Drop Out', 'thegem'),
					'none' => __('None', 'thegem'),
				],
				'default' => 'fade',
				'condition' => [
					'menu_pointer_style_hover' => ['line-underline-1', 'line-underline-2', 'line-overline-1', 'line-overline-2', 'line-top-bottom'],
				],
			]
		);
  
		$this->add_control(
			'animation_background',
			[
				'label' => __('Hover Animation', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'fade' => __('Fade', 'thegem'),
					'grow' => __('Grow', 'thegem'),
					'shrink' => __('Shrink', 'thegem'),
					'sweep-left' => __('Sweep Left', 'thegem'),
					'sweep-right' => __('Sweep Right', 'thegem'),
					'sweep-up' => __('Sweep Up', 'thegem'),
					'sweep-down' => __('Sweep Down', 'thegem'),
					'none' => __('None', 'thegem'),
				],
				'default' => 'fade',
				'condition' => [
					'menu_pointer_style_hover' => ['background-underline', 'background-color', 'background-rounded', 'background-extra-paddings'],
				],
			]
		);

		$control->start_controls_tabs('tabs_menu_style');

		$control->start_controls_tab(
			'menu_style_normal',
			[
				'label' => __('Normal', 'thegem'),
			]
		);

		$control->add_control(
			'menu_item_color',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li a,
					{{WRAPPER}} ul.nav-menu-custom li > span' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$control->add_control(
			'menu_item_background',
			[
				'label' => __('Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li a,
					{{WRAPPER}} ul.nav-menu-custom li > span' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$control->add_control(
			'menu_item_icon_color',
			[
				'label' => __('Icon Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li a .icon,
					{{WRAPPER}} ul.nav-menu-custom li > span .icon' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab(
			'menu_style_hover',
			[
				'label' => __('Hover', 'thegem'),
			]
		);

		$control->add_control(
			'menu_item_color_hover',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li:hover a,
					{{WRAPPER}} ul.nav-menu-custom li:hover > span' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$control->add_control(
			'menu_item_background_hover',
			[
				'label' => __('Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li:hover a:before,
					{{WRAPPER}} ul.nav-menu-custom li:hover > span' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$control->add_control(
			'menu_item_icon_color_hover',
			[
				'label' => __('Icon Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li .icon' => 'transition: all 0.3s;',
					'{{WRAPPER}} ul.nav-menu-custom li:hover a .icon,
					{{WRAPPER}} ul.nav-menu-custom li:hover > span .icon' => 'color: {{VALUE}} !important;',
				],
			]
		);
		
		$control->add_control(
			'pointer_color_menu_item_hover',
			[
				'label' => __('Pointer Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li:hover a .text:before,
					{{WRAPPER}} ul.nav-menu-custom li:hover a .text:after' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} ul.nav-menu-custom li:hover a:before,
					{{WRAPPER}} ul.nav-menu-custom li:hover a:after' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$control->end_controls_tab();
		
		$control->start_controls_tab(
			'menu_style_active',
			[
				'label' => __('Active', 'thegem'),
			]
		);
		
		$control->add_control(
			'menu_item_color_active',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li.menu-item-active a,
					{{WRAPPER}} ul.nav-menu-custom li.menu-item-active > span' => 'color: {{VALUE}} !important;',
				],
			]
		);
		
		$control->add_control(
			'menu_item_background_active',
			[
				'label' => __('Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li.menu-item-active a:before,
					{{WRAPPER}} ul.nav-menu-custom li.menu-item-active > span' => 'background-color: {{VALUE}} !important;',
				],
			]
		);
		
		$control->add_control(
			'menu_item_icon_color_active',
			[
				'label' => __('Icon Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li.menu-item-active a .icon,
					{{WRAPPER}}ul.nav-menu-custom li.menu-item-active > span .icon' => 'color: {{VALUE}} !important;',
				],
			]
		);
  
		$control->add_control(
			'pointer_color_menu_item_active',
			[
				'label' => __('Pointer Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li.menu-item-active a .text:before,
					{{WRAPPER}} ul.nav-menu-custom li.menu-item-active a .text:after' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} ul.nav-menu-custom li.menu-item-active a:before,
					{{WRAPPER}} ul.nav-menu-custom li.menu-item-active a:after' => 'border-color: {{VALUE}} !important;',
				],
			]
		);
		
		$control->end_controls_tab();

		$control->end_controls_tabs();
		
		$this->add_control(
			'pointer_width_menu_item',
			[
				'label' => __('Pointer Width (%)', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li a .text:before' => 'width: {{SIZE}}% !important',
					'{{WRAPPER}} ul.nav-menu-custom li a .text:after' => 'width: {{SIZE}}% !important',
				],
			]
		);
		
		$this->add_control(
			'pointer_height_menu_item',
			[
				'label' => __('Pointer Weight (px)', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li a .text:before' => 'height: {{SIZE}}{{UNIT}} !important',
					'{{WRAPPER}} ul.nav-menu-custom li a .text:after' => 'height: {{SIZE}}{{UNIT}} !important',
				],
			]
		);
		
		$this->add_control(
			'pointer_top_spacing_menu_item',
			[
				'label' => __('Pointer Top Spacing (px)', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} ul.nav-menu-custom li a .text:before' => 'top: -{{SIZE}}{{UNIT}} !important',
					'{{WRAPPER}} ul.nav-menu-custom li a .text:after' => 'bottom: -{{SIZE}}{{UNIT}} !important',
				],
			]
		);

		$control->end_controls_section();
	}


	/**
	 * Controls call
	 * @access public
	 */
	public function add_styles_controls($control) {
		$this->control = $control;

		/* Heading Styles */
		$this->heading_style($control);

		/* Menu Styles */
		$this->menu_style($control);
	}


	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	public function render() {

		$settings = $this->get_settings_for_display();
		$uniqid = $this->get_id();
		
		$pointer_classes = '';
		if (isset($settings['menu_pointer_style_hover'])) {
			if (in_array($settings['menu_pointer_style_hover'], ['frame-default', 'frame-rounded'])) {
				$pointer_classes = 'style-hover-framed style-hover-animation-'.$settings['animation_framed'];
			} else if (in_array($settings['menu_pointer_style_hover'], ['line-underline-1', 'line-underline-2', 'line-overline-1', 'line-overline-2', 'line-top-bottom'])) {
				$pointer_classes = 'style-hover-lined style-hover-animation-'.$settings['animation_line'];
			} else if (in_array($settings['menu_pointer_style_hover'], ['background-underline', 'background-color', 'background-rounded', 'background-extra-paddings'])) {
				$pointer_classes = 'style-hover-background style-hover-animation-'.$settings['animation_background'];
			} else {
				$pointer_classes = 'style-hover-text';
			}
			$pointer_classes .= ' style-hover-type-'.$settings['menu_pointer_style_hover'];
		}
		if (isset($settings['menu_pointer_style_active'])) {
			if (in_array($settings['menu_pointer_style_active'], ['frame-default', 'frame-rounded'])) {
				$pointer_classes .= ' style-active-framed';
			} else if (in_array($settings['menu_pointer_style_active'], ['line-underline-1', 'line-underline-2', 'line-overline-1', 'line-overline-2', 'line-top-bottom'])) {
				$pointer_classes .= ' style-active-lined';
			} else if (in_array($settings['menu_pointer_style_active'], ['background-underline', 'background-color', 'background-rounded', 'background-extra-paddings'])) {
				$pointer_classes .= ' style-active-background';
			} else {
				$pointer_classes .= ' style-active-text';
			}
			$pointer_classes .= ' style-active-type-'.$settings['menu_pointer_style_active'];
		}
		
		$menu_item_font_class = implode(' ', array($settings['menu_item_text_style'], $settings['menu_item_font_weight']));
        
        ?>

		<div id="<?php echo(esc_attr($uniqid)); ?>" class="thegem-menu-custom thegem-menu-custom--<?= $settings['submenu_style'] ?> thegem-menu-custom--<?= $settings['menu_alignment'] ?> <?php echo $settings['menu_indicator'] == 'yes' ? 'menu-indicator' : ''; ?> <?php echo $settings['submenu_separator'] == 'yes' ? 'menu-separator' : ''; ?> <?=$pointer_classes?>">
			<?php if (!empty($settings['heading_text'])) {
				$link_before = '<span>';
				$link_after = '</span>';
				$icon = $label = $class = $separator = '';

				if (!empty($settings['heading_link']['url'])) {
					$this->add_link_attributes('url', $settings['heading_link']);

					$link_before = '<a ' . $this->get_render_attribute_string('url') . '>';
					$link_after = '</a>';
				}

				if ($settings['heading_icon'] == 'yes' && !empty($settings['heading_icon_select']['value'])) {
					ob_start();
					Icons_Manager::render_icon($settings['heading_icon_select'], ['aria-hidden' => 'true', 'class' => 'hidden-icon']);
					$icon = '<span class="icon">'.ob_get_clean().'</span>';
				}

				if (!empty($settings['heading_label_text'])) {
					$label = '<span class="label title-h6">' . $settings['heading_label_text'] . '</span>';
				}

				if (!empty($settings['heading_separator'])) {
					$separator = '<div class="separator"><span></span></div>';
				}

				echo '<' . $settings['heading_tag'] . ' class="menu-custom-header ' . esc_attr($class) . ' ' . esc_attr($settings['heading_div_style']) . '">' . $link_before . $icon . $settings['heading_text'] . $label . $link_after . $separator . '</' . $settings['heading_tag'] . '>';
			}

			if ($settings['menu_source'] == 'nav_menu') {
				if (!empty($settings['nav_menu']) && is_nav_menu($settings['nav_menu']) && !empty(wp_get_nav_menu_items($settings['nav_menu']))) {
					if (!empty($settings['show_submenus'])) {
						$depth = 0;
					} else {
						$depth = 1;
					}

					wp_nav_menu(array(
						'menu' => $settings['nav_menu'],
						'menu_class' => 'nav-menu-custom',
						'container' => null,
						'echo' => true,
						'depth' => $depth,
						'link_before' => '<span class="text">',
						'link_after' => '<i class="indicator"></i></span>',
						'li_class' => esc_attr($menu_item_font_class)
					));
				}
			} else {
				$menu_custom = $settings['menu_custom'];

				if (!empty($menu_custom)) {
					echo '<ul class="nav-menu-custom">';
					foreach ($menu_custom as $i=>$item) {
						if (empty($item['item_title'])) {
							continue;
						}
						$item_link_before = '<span>';
						$item_link_after = '</span>';
						$item_icon = $item_label = '';

						if (!empty($item['item_link']['url'])) {
							$this->add_link_attributes('url'.$i, $item['item_link']);

							$item_link_before = '<a ' . $this->get_render_attribute_string('url'.$i) . '>';
							$item_link_after = '</a>';
						}

						if ($item['item_icon'] == 'yes' && !empty($item['item_icon_select']['value'])) {
							ob_start();
							Icons_Manager::render_icon($item['item_icon_select'], ['aria-hidden' => 'true', 'class' => 'hidden-icon']);
							$item_icon = '<span class="icon">'.ob_get_clean().'</span>';
						}

						if (!empty($item['item_label_text'])) {
							$item_label_style = '';

							if (!empty($item['item_label_color'])) {
								$item_label_style .= 'color:' . $item['item_label_color'] . ';';
							}

							if (!empty($item['item_label_background'])) {
								$item_label_style .= 'background-color:' . $item['item_label_background'] . ';';
							}

							$item_label = '<span class="label title-h6" style="' . $item_label_style . '">' . $item['item_label_text'] . '</span>';
						}

						echo '<li class="' . esc_attr($menu_item_font_class) . '" style="">' . $item_link_before . $item_icon .'<span class="text">'.$item['item_title'].'<i class="indicator"></i></span>'. $item_label . $item_link_after . '</li>';
					}
					echo '</ul>';
				}
			} ?>
		</div>

		<?php

	}
}

Plugin::instance()->widgets_manager->register(new TheGem_Custom_Menu());
