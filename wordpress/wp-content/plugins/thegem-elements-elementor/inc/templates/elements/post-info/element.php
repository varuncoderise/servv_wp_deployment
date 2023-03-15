<?php

namespace TheGem_Elementor\Widgets\TemplatePostInfo;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Repeater;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) exit;

/**
 * Elementor widget for Blog Title.
 */

class TheGem_TemplatePostInfo extends Widget_Base {
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
	}
	
	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'thegem-template-post-info';
	}
	
	/**
	 * Show in panel.
	 *
	 * Whether to show the widget in the panel or not. By default returns true.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return bool Whether to show the widget in the panel or not.
	 */
	public function show_in_panel() {
		return get_post_type() === 'thegem_templates' && thegem_get_template_type(get_the_id()) === 'single-post';
	}
	
	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __('Post Info', 'thegem');
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
		return ['thegem_single_post_builder'];
	}
	
	/** Show reload button */
	public function is_reload_preview_required() {
		return true;
	}
	
	/** Get widget wrapper */
	public function get_widget_wrapper() {
		return 'thegem-te-post-info';
	}
	
	/** Get customize class */
	public function get_customize_class($only_parent = false) {
		if ($only_parent) {
			return ' .'.$this->get_widget_wrapper();
		}
		
		return ' .'.$this->get_widget_wrapper().' .post-info';
	}
	
	/**
	 * Register the widget controls.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		// General Section
		$this->start_controls_section(
			'section_general',
			[
				'label' => __('General', 'thegem'),
			]
		);
  
		$this->add_control(
			'skin',
			[
				'label' => __('Skin', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'classic' => __('Classic', 'thegem'),
					'modern' => __('Modern', 'thegem'),
				],
				'default' => 'classic',
			]
		);
		
		$this->add_control(
			'layout',
			[
				'label' => __('Layout', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'horizontal' => __('Horizontal', 'thegem'),
					'vertical' => __('Vertical', 'thegem'),
				],
				'default' => 'horizontal',
			]
		);
		
		$repeater = new Repeater();
		
		// Type
		$repeater->add_control(
			'type',
			[
				'label' => __('Type', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'author' => __('Author', 'thegem'),
					'cats' => __('Categories', 'thegem'),
					'date' => __('Date', 'thegem'),
					'time' => __('Time', 'thegem'),
					'comments' => __('Comments', 'thegem'),
					'likes' => __('Likes', 'thegem'),
				],
				'default' => 'author',
			]
		);
  
		// Author
		$repeater->add_control(
			'author_label',
			[
				'label' => __('Label', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'default' => __('By', 'thegem'),
				'condition' => [
					'type' => 'author',
				],
			]
		);
		
		$repeater->add_control(
			'author_avatar',
			[
				'label' => __('Avatar', 'thegem'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'type' => 'author',
				],
			]
		);
		
		$repeater->add_control(
			'author_avatar_size',
			[
				'label' => __('Size', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
				],
				'condition' => [
					'type' => 'author',
					'author_avatar' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' ' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$repeater->add_control(
			'author_link',
			[
				'label' => __('Link', 'thegem'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'type' => 'author',
				],
			]
		);
		
		// Categories
		$repeater->add_control(
			'cats_link',
			[
				'label' => __('Link', 'thegem'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'type' => 'cats'
				],
			]
		);
		
		// Date
		$repeater->add_control(
			'date_label',
			[
				'label' => __('Label', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'type' => 'date',
				],
			]
		);
  
		$repeater->add_control(
			'date_format',
			[
				'label' => __('Date Format', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __('Default', 'thegem'),
					'1' => __('March 6, 2018 (F j, Y)', 'thegem'),
					'2' => __('2018-03-06 (Y-m-d)', 'thegem'),
					'3' => __('03/06/2018 (m/d/Y)', 'thegem'),
					'4' => __('06/03/2018 (d/m/Y)', 'thegem'),
					'5' => __('06.03.2018 (d.m.Y)', 'thegem'),
				],
				'default' => '',
				'condition' => [
					'type' => 'date',
				],
			]
		);
		
		$repeater->add_control(
			'date_icon',
			[
				'label' => __('Icon', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __('None', 'thegem'),
					'default' => __('Default', 'thegem'),
					'custom' => __('Custom', 'thegem'),
				],
				'default' => 'default',
				'condition' => [
					'type' => 'date',
				],
			]
		);
		
		$repeater->add_control(
			'date_icon_select',
			[
				'label' => __('Select Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
				'condition' => [
					'type' => 'date',
					'date_icon' => 'custom',
				],
			]
		);
		
		$repeater->add_control(
			'date_link',
			[
				'label' => __('Link', 'thegem'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'type' => 'date',
				],
			]
		);
		
		// Time
		$repeater->add_control(
			'time_label',
			[
				'label' => __('Label', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'type' => 'time',
				],
			]
		);
		
		$repeater->add_control(
			'time_format',
			[
				'label' => __('Time Format', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __('Default', 'thegem'),
					'1' => __('3:31 pm (g:i a)', 'thegem'),
					'2' => __('3:31 PM (g:i A)', 'thegem'),
					'3' => __('15:31 (H:i)', 'thegem'),
				],
				'default' => '',
				'condition' => [
					'type' => 'time',
				],
			]
		);
		
		$repeater->add_control(
			'time_icon',
			[
				'label' => __('Icon', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __('None', 'thegem'),
					'default' => __('Default', 'thegem'),
					'custom' => __('Custom', 'thegem'),
				],
				'default' => 'default',
				'condition' => [
					'type' => 'time',
				],
			]
		);
		
		$repeater->add_control(
			'time_icon_select',
			[
				'label' => __('Select Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
				'condition' => [
					'type' => 'time',
					'time_icon' => 'custom',
				],
			]
		);
		
		// Comments
		$repeater->add_control(
			'comments_label',
			[
				'label' => __('Label', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'type' => 'comments',
				],
			]
		);
		
		$repeater->add_control(
			'comments_icon',
			[
				'label' => __('Icon', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __('None', 'thegem'),
					'default' => __('Default', 'thegem'),
					'custom' => __('Custom', 'thegem'),
				],
				'default' => 'default',
				'condition' => [
					'type' => 'comments',
				],
			]
		);
		
		$repeater->add_control(
			'comments_icon_select',
			[
				'label' => __('Select Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
				'condition' => [
					'type' => 'comments',
					'comments_icon' => 'custom',
				],
			]
		);
		
		$repeater->add_control(
			'comments_link',
			[
				'label' => __('Link', 'thegem'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'type' => 'comments',
				],
			]
		);
		
		// Likes
		$repeater->add_control(
			'likes_label',
			[
				'label' => __('Label', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'type' => 'likes',
				],
			]
		);
		
		$repeater->add_control(
			'likes_icon',
			[
				'label' => __('Icon', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __('None', 'thegem'),
					'default' => __('Default', 'thegem'),
					//'custom' => __('Custom', 'thegem'),
				],
				'default' => 'default',
				'condition' => [
					'type' => 'likes',
				],
				'selectors_dictionary' => [
					'' => 'display: none;',
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item .zilla-likes:before' => '{{VALUE}};',
				],
				
			]
		);
		
		$repeater->add_control(
			'likes_icon_select',
			[
				'label' => __('Select Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
				'condition' => [
					'type' => 'likes',
					'likes_icon' => 'custom',
				],
			]
		);
		
		$this->add_control(
			'info_content',
			[
				'label' => __('Items', 'thegem'),
				'type' => Controls_Manager::REPEATER,
				'show_label' => false,
				'fields' => $repeater->get_controls(),
			]
		);
		
		$this->end_controls_section();
		
		// List Section Style
		$this->start_controls_section(
			'section_list_style',
			[
				'label' => __('List', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'list_alignment',
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
			'list_divider',
			[
				'label' => __('Divider', 'thegem'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'skin' => 'classic',
				],
			]
		);
		
		$this->add_control(
			'list_divider_color',
			[
				'label' => __('Divider Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'condition' => [
					'skin' => 'classic',
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item:not(:last-child):after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item-cats .separator' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'list_spacing_horizontal',
			[
				'label' => __('Space Between', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'condition' => [
					'layout' => 'horizontal',
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item' => 'margin-right: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item-cats .separator' => 'margin: 0 {{SIZE}}{{UNIT}};',
				],
			]
		);
  
		$this->add_responsive_control(
			'list_spacing_vertical',
			[
				'label' => __('Space Between', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'condition' => [
					'layout' => 'vertical',
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// Icon Section Style
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => __('Icon', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __('Size', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item .icon i' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item .zilla-likes:before' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => __('Spacing', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item .icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item .zilla-likes:before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item .avatar' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'icon_color',
			[
				'label' => __('Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item .icon' => 'color: {{VALUE}};',
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item .zilla-likes' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// Text Section Style
		$this->start_controls_section(
			'section_text_style',
			[
				'label' => __('Text', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'text_style',
			[
				'label' => __('Text Style', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __('Default', 'thegem'),
					'title-h2' => __('Title H2', 'thegem'),
					'title-h3' => __('Title H3', 'thegem'),
					'title-h4' => __('Title H4', 'thegem'),
					'title-h5' => __('Title H5', 'thegem'),
					'title-h6' => __('Title H6', 'thegem'),
					'title-xlarge' => __('Title xLarge', 'thegem'),
					'styled-subtitle' => __('Styled Subtitle', 'thegem'),
					'title-main-menu' => __('Main Menu', 'thegem'),
					'title-text-body' => __('Body', 'thegem'),
					'title-text-body-tiny' => __('Tiny Body', 'thegem'),
				],
				'default' => '',
			]
		);
		
		$this->add_control(
			'text_font_weight',
			[
				'label' => __('Font weight', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __('Default', 'thegem'),
					'light' => __('Thin', 'thegem'),
				],
				'default' => '',
			]
		);
		
		$this->add_control(
			'text_letter_spacing',
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
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item' => 'letter-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'text_transform',
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
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item' => '{{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'text_color',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item' => 'color: {{VALUE}};',
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'text_color_hover',
			[
				'label' => __('Text Color on Hover', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item a:hover .icon' => 'color: {{VALUE}};',
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item .zilla-likes:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// Style Section
		$this->start_controls_section(
			'section_cats_style',
			[
				'label' => __('Categories', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
        /*
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cats_typography',
				'selector' => '{{WRAPPER}}'.$this->get_customize_class().' .post-info-item-cats a, {{WRAPPER}}'.$this->get_customize_class().' .post-tags__list a',
			]
		);
		
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'cats_text_shadow',
				'selector' => '{{WRAPPER}}'.$this->get_customize_class().' .post-info-item-cats a, {{WRAPPER}}'.$this->get_customize_class().' .post-tags__list a',
			]
		);
        */
		
		$this->add_control(
			'cats_border',
			[
				'label' => __('Border', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __('None', 'thegem'),
					'solid' => __('Solid', 'thegem'),
				],
				'default' => 'solid',
				'condition' => [
					'skin' => 'modern',
				],
				'selectors_dictionary' => [
					'' => 'border: 0',
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item-cats a' => '{{VALUE}};',
				],
			]
		);
  
		$this->add_control(
			'cats_border_width',
			[
				'label' => __('Border Width', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'skin' => 'modern',
					'cats_border' => 'solid',
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item-cats a' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'cats_border_radius',
			[
				'label' => __('Border Radius', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'skin' => 'modern',
					'cats_border' => 'solid',
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item-cats a' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'cats_text_color',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'condition' => [
					'skin' => 'modern',
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item-cats a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'cats_text_color_hover',
			[
				'label' => __('Text Color on Hover', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'condition' => [
					'skin' => 'modern',
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item-cats a:not(.readonly):hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'cats_background_color',
			[
				'label' => __('Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'condition' => [
					'skin' => 'modern',
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item-cats a' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'cats_background_color_hover',
			[
				'label' => __('Background Color on Hover', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'condition' => [
					'skin' => 'modern',
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item-cats a:not(.readonly):hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'cats_border_color',
			[
				'label' => __('Border Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'condition' => [
					'skin' => 'modern',
					'cats_border' => 'solid',
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item-cats a' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'cats_border_color_hover',
			[
				'label' => __('Border Color on Hover', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'condition' => [
					'skin' => 'modern',
					'cats_border' => 'solid',
				],
				'selectors' => [
					'{{WRAPPER}}'.$this->get_customize_class().' .post-info-item-cats a:not(.readonly):hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
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
		$params = array_merge(array(), $settings);
		$uniqid = $this->get_id();
		
		ob_start();
		$single_post = thegem_templates_init_post();
		$info_content = $params['info_content'];
  
		if (empty($single_post) || empty($info_content)) {
			ob_end_clean();
			echo thegem_templates_close_post(str_replace('-template-', '-te-', $this->get_name()), $this->get_title(), '');
			return;
		}
		
		$skin = 'post-info--'.$params['skin'];
		$layout = 'post-info--'.$params['layout'];
		$alignment = 'post-info--'.$params['list_alignment'];
		$divider = !empty($params['list_divider']) ? 'post-info--divider-show' : 'post-info--divider-hide';
		$params['element_class'] = implode(' ', array(
            $this->get_widget_wrapper(), $skin, $layout, $alignment, $divider
		));
		$text_styled = implode(' ', array($params['text_style'], $params['text_font_weight']));
		
		?>

        <div class="<?= esc_attr($params['element_class']); ?>">
            <div class="post-info">
		        <?php foreach ($info_content as $item): ?>
			        <?php switch ($item['type']) {
				        case 'cats':
					        $cats = get_the_category();
					        $cats_list = [];
					        foreach ($cats as $cat) {
						        if (!empty($item['cats_link'])) {
							        $cats_list[] = '<a href="' . esc_url(get_category_link($cat->term_id)) . '" title="' . esc_attr(sprintf(__("View all posts in %s", "thegem"), $cat->name)) . '">' . $cat->cat_name . '</a>';
						        } else{
							        $cats_list[] = '<a class="readonly">' . $cat->cat_name . '</a>';
						        }
					        }
					        $cats_output = implode(' <span class="separator"></span> ', $cats_list);
					
					        echo '<div class="post-info-item post-info-item-'.$item['type'].' '.$text_styled.'">'.$cats_output.'</div>';
					        break;
				        case 'author':
					        $author_output = $author_label = '';
					
					        if (!empty($item['author_avatar'])) {
						        $size = !empty($item['author_avatar_size']['size']) ? $item['author_avatar_size']['size'] : 20;
						        $author_output .= '<div class="avatar">'.get_avatar($single_post, $size ).'</div>';
					        }
					
					        if (!empty($item['author_label'])) {
						        $author_label = esc_html($item['author_label']);
					        }
					
					        if (!empty($item['author_link'])) {
						        $author_output .= '<div class="name">'.$author_label.' '.get_the_author_posts_link().'</div>';
					        } else {
						        $author_output .= '<div class="name">'.$author_label.' '.get_the_author_meta('display_name').'</div>';
					        }
					
					        echo '<div class="post-info-item post-info-item-'.$item['type'].' '.$text_styled.'">'.$author_output.'</div>';
					        break;
				        case 'date':
					        $date_output = $format = '';
					
					        if (!empty($item['date_format'])) {
						        if ($item['date_format'] == '1') $format = 'F j, Y';
						        if ($item['date_format'] == '2') $format = 'Y-m-d';
						        if ($item['date_format'] == '3') $format = 'm/d/Y';
						        if ($item['date_format'] == '4') $format = 'd/m/Y';
						        if ($item['date_format'] == '5') $format = 'd.m.Y';
					        }
					
					        if (!empty($item['date_icon']) && $item['date_icon'] == 'custom' && !empty($item['date_icon_select']['value'])) {
						        ob_start();
						        Icons_Manager::render_icon($item['date_icon_select'], ['aria-hidden' => 'true']);
						        $date_output .= '<div class="icon">'.ob_get_clean().'</div>';
					        } else if (!empty($item['date_icon']) && $item['date_icon'] == 'default') {
						        $date_output .= '<div class="icon"><i class="icon-default"></i></div>';
					        }
					
					        if (!empty($item['date_label'])) {
						        $date_output .= '<div class="label-before">'.esc_html($item['date_label']).'</div>';
					        }
					
					        if (!empty($item['date_link'])) {
						        $year = get_the_time('Y');
						        $month = get_the_time('m');
						        $day = get_the_time('d');
						
						        $date_output .= '<a class="date" href="'.get_day_link( $year, $month, $day).'">'.get_the_date($format, $single_post).'</a>';
					        } else {
						        $date_output .= '<div class="date">'.get_the_date($format, $single_post).'</div>';
					        }
					
					        echo '<div class="post-info-item post-info-item-'.$item['type'].' '.$text_styled.'">'.$date_output.'</div>';
					        break;
				        case 'time':
					        $time_output = $format = '';
					
					        if (!empty($item['time_format'])) {
						        if ($item['time_format'] == '1') $format = 'g:i a';
						        if ($item['time_format'] == '2') $format = 'g:i A';
						        if ($item['time_format'] == '3') $format = 'H:i';
					        }
					
					        if (!empty($item['time_icon']) && $item['time_icon'] == 'custom' && !empty($item['time_icon_select']['value'])) {
						        ob_start();
						        Icons_Manager::render_icon($item['time_icon_select'], ['aria-hidden' => 'true']);
						        $time_output .= '<div class="icon">'.ob_get_clean().'</div>';
					        } else if (!empty($item['time_icon']) && $item['time_icon'] == 'default') {
						        $time_output .= '<div class="icon"><i class="icon-default"></i></div>';
					        }
					
					        if (!empty($item['time_label'])) {
						        $time_output .= '<div class="label-before">'.esc_html($item['time_label']).'</div>';
					        }
					
					        $time_output .= '<div class="time">'.get_the_time($format, $single_post).'</div>';
					
					        echo '<div class="post-info-item post-info-item-'.$item['type'].' '.$text_styled.'">'.$time_output.'</div>';
					        break;
				        case 'comments':
					        if ( !comments_open()) break;
					
					        $comments_output = $comments_label = $comments_icon = '';
					        if (!empty($item['comments_icon']) && $item['comments_icon'] == 'custom' && !empty($item['comments_icon_select']['value'])) {
						        ob_start();
						        Icons_Manager::render_icon($item['comments_icon_select'], ['aria-hidden' => 'true']);
						        $comments_icon = '<div class="icon">'.ob_get_clean().'</div>';
					        } else if (!empty($item['comments_icon']) && $item['comments_icon'] == 'default') {
						        $comments_icon = '<div class="icon"><i class="icon-default"></i></div>';
					        }
					
					        $comments_label = '<div class="count">'.$single_post->comment_count.'</div>';
					
					        if (!empty($item['comments_label'])) {
						        $comments_label .= '<div class="label-after">'.esc_html($item['comments_label']).'</div>';
					        }
					
					        if (!empty($item['comments_link'])) {
						        $comments_output = $comments_icon.' '.'<a class="comments-link" href="'.get_permalink( $single_post ).'#respond">'.$comments_label.'</a>';
					        } else{
						        $comments_output = $comments_icon.' '.$comments_label;
					        }
					
					        echo '<div class="post-info-item post-info-item-'.$item['type'].' '.$text_styled.'">'.$comments_output.'</div>';
					        break;
				        case 'likes':
					        if ( !function_exists('zilla_likes')) break;
					
					        ob_start();
					        zilla_likes();
					        $likes_output = '<div class="likes">'.ob_get_clean().'</div>';
					
					        if (!empty($item['likes_label'])) {
						        $likes_output .= '<div class="label-after">'.esc_html($item['likes_label']).'</div>';
					        }
					
					        echo '<div class="post-info-item post-info-item-'.$item['type'].' '.$text_styled.'">'.$likes_output.'</div>';
					
					        break;
			        } ?>
		        <?php endforeach; ?>
            </div>
        </div>
		
		<?php
		
		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
		
		echo thegem_templates_close_post(str_replace('-template-', '-te-', $this->get_name()), $this->get_title(), $return_html);
	}
}

\Elementor\Plugin::instance()->widgets_manager->register(new TheGem_TemplatePostInfo());
