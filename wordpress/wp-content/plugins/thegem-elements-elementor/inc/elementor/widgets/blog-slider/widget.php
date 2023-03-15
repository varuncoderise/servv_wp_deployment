<?php
namespace TheGem_Elementor\Widgets\BlogSlider;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Schemes;

use WP_Query;


if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * Elementor widget for BlogSlider.
 */
class TheGem_Blogslider extends Widget_Base {

	/**
	 * Presets
	 * @access protected
	 * @var array $presets Array objects presets.
	 */

	public function __construct( $data = [], $args = null ) {

		$template_type = $GLOBALS['thegem_template_type'] ?? thegem_get_template_type(get_the_ID());
		$this->is_blog_archive = $template_type === 'blog-archive' || (empty($template_type) && (is_category() || is_tag() || is_author() || is_post_type_archive('post')));
		$this->is_blog_post = $template_type === 'single-post' || (empty($template_type) && is_singular('post'));

		if (isset($data['settings']) && (empty($_REQUEST['action']) || !in_array($_REQUEST['action'], array('thegem_importer_process', 'thegem_templates_new')))) {

			if (!isset($data['settings']['source_type'])) {
				if ($this->is_blog_post) {
					$data['settings']['source_type'] = 'related';
				} else if ($this->is_blog_archive) {
					$data['settings']['source_type'] = 'archive';
				} else {
					$data['settings']['source_type'] = 'custom';
				}
			}

			if (isset($data['settings']['source']) && !is_array($data['settings']['source'])) {
				$data['settings']['source'] = [$data['settings']['source']];
			}
		}

		parent::__construct( $data, $args );

		if (!defined('THEGEM_ELEMENTOR_WIDGET_BLOGSLIDER_DIR')) {
			define('THEGEM_ELEMENTOR_WIDGET_BLOGSLIDER_DIR', rtrim(__DIR__, ' /\\'));
		}

		if (!defined('THEGEM_ELEMENTOR_WIDGET_BLOGSLIDER_URL')) {
			define('THEGEM_ELEMENTOR_WIDGET_BLOGSLIDER_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}
		wp_register_style('thegem-blogslider-css', THEGEM_ELEMENTOR_WIDGET_BLOGSLIDER_URL . '/assets/css/thegem-blogslider.css', array('thegem-blog'), NULL);
		wp_register_script('thegem-blogslider-js', THEGEM_ELEMENTOR_WIDGET_BLOGSLIDER_URL . '/assets/js/thegem-blogslider.js', array('jquery', 'thegem-blog', 'jquery-carouFredSel'), null, true);

	}


	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'thegem-blogslider';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Blog Slider', 'thegem' );
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
		if (get_post_type() === 'thegem_templates' && thegem_get_template_type(get_the_id()) === 'blog-archive') {
			return ['thegem_blog_archive_builder'];
		}
		return [ 'thegem_blog' ];
	}

	public function get_style_depends() {
		return [ 'thegem-additional-blog', 'thegem-animations', 'thegem-blogslider-css' ];
	}

	public function get_script_depends()
    {
		return [ 'thegem-blogslider-js'];
	}

	/*Show reload button*/
	public function is_reload_preview_required() {
		return true;
	}


	/**
	 * Retrieve the value setting
	 * @access public
	 *
	 * @param string $control_id Control id
	 * @param string $control_sub Control value name (size, unit)
	 *
	 * @return string
	 */
	public function get_val( $control_id, $control_sub = null ) {
		if ( empty( $control_sub ) ) {
			return $this->get_settings()[ $control_id ];
		} else {
			return $this->get_settings()[ $control_id ][ $control_sub ];
		}
	}

	
	/**
	 * Register the widget controls.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		
		$this->start_controls_section(
			'section_blog',
			[
				'label' => __( 'Blog', 'thegem' ),
			]
		);

		if ( $this->is_blog_archive ) {

			$this->add_control(
				'source_type',
				[
					'label' => __('Blog Source', 'thegem'),
					'default' => 'archive',
					'type' => Controls_Manager::SELECT,
					'label_block' => true,
					'options' => [
						'archive' => __('Blog Archive', 'thegem'),
						'custom' => __('Custom Selection', 'thegem'),
					],
				]
			);

		} else if ($this->is_blog_post) {

			$this->add_control(
				'source_type',
				[
					'label' => __('Blog Source', 'thegem'),
					'default' => 'related',
					'type' => Controls_Manager::SELECT,
					'label_block' => true,
					'options' => [
						'related' => __('Related Posts', 'thegem'),
						'custom' => __('Custom Selection', 'thegem'),
					],
				]
			);

		} else {

			$this->add_control(
				'source_type',
				[
					'type' => Controls_Manager::HIDDEN,
					'default' => 'custom',
				]
			);
		}

		$this->add_control(
			'related_by',
			[
				'label' => __('Related by', 'thegem'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'categories' => __('Category', 'thegem'),
					'tags' => __('Tag', 'thegem'),
					'authors' => __('Author', 'thegem'),
				],
				'default' => ['categories'],
				'condition' => [
					'source_type' => 'related',
				],
			]
		);

		$this->add_control(
			'source',
			[
				'label' => __('Source', 'thegem'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'categories' => __('Categories', 'thegem'),
					'tags' => __('Tags', 'thegem'),
					'posts' => __('Posts', 'thegem'),
					'authors' => __('Authors', 'thegem'),
				],
				'default' => ['categories'],
				'condition' => [
					'source_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'select_blog_cat',
			[
				'label' => __('Select Blog Categories', 'thegem'),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => get_thegem_select_blog_categories(),
				'frontend_available' => true,
				'label_block' => true,
				'condition' => [
					'source_type' => 'custom',
					'source' => 'categories',
				],
			]
		);

		$this->add_control(
			'select_blog_tags',
			[
				'label' => __( 'Select Blog Tags', 'thegem' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => get_thegem_select_blog_tags(),
				'frontend_available' => true,
				'label_block' => true,
				'condition' => [
					'source_type' => 'custom',
					'source' => 'tags',
				],
			]
		);

		$this->add_control(
			'select_blog_posts',
			[
				'label' => __('Select Blog Posts', 'thegem'),
				'type' => 'gem-query-control',
				'search' => 'thegem_get_posts_by_query',
				'render' => 'thegem_get_posts_title_by_id',
				'post_type' => 'post',
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'source_type' => 'custom',
					'source' => 'posts',
				],
			]
		);

		$this->add_control(
			'select_blog_authors',
			[
				'label' => __( 'Select Blog Authors', 'thegem' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => get_thegem_select_blog_authors(),
				'frontend_available' => true,
				'label_block' => true,
				'condition' => [
					'source_type' => 'custom',
					'source' => 'authors',
				],
			]
		);

		$this->add_control(
			'order_by',
			[
				'label' => __('Order By', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'default' => __('Default', 'thegem'),
					'date' => __('Date', 'thegem'),
					'id' => __('ID', 'thegem'),
					'author' => __('Author', 'thegem'),
					'title' => __('Title', 'thegem'),
					'modified' => __('Last modified date', 'thegem'),
					'comment_count' => __('Number of comments', 'thegem'),
					'rand' => __('Random', 'thegem'),
				],
				'default' => 'default',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __('Sort Order', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'default' => __('Default', 'thegem'),
					'desc' => __('Descending', 'thegem'),
					'asc' => __('Ascending', 'thegem'),
				],
				'default' => 'default',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'offset',
			[
				'label' => __('Offset', 'thegem'),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'description' => __('Number of items to displace or pass over', 'thegem'),
			]
		);

		$this->add_control(
			'exclude_blog_posts',
			[
				'label' => __('Exclude Blog Posts', 'thegem'),
				'type' => 'gem-query-control',
				'search' => 'thegem_get_posts_by_query',
				'render' => 'thegem_get_posts_title_by_id',
				'post_type' => 'post',
				'label_block' => true,
				'multiple' => true,
			]
		);

		$this->add_responsive_control(
			'max_posts',
			[
				'label' => __('Max. number of posts in slider', 'thegem'),
				'type' => Controls_Manager::NUMBER,
				'min' => -1,
				'max' => 100,
				'step' => 1,
				'description' => __('Use -1 to show all', 'thegem'),
			]
		);
		
		$this->add_control(
			'show_title',
			[
				'label' => __( 'Show Title', 'thegem' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'thegem' ),
				'label_off' => __( 'Hide', 'thegem' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_description',
			[
				'label' => __( 'Show Description', 'thegem' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'thegem' ),
				'label_off' => __( 'Hide', 'thegem' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_date',
			[
				'label' => __( 'Show Date', 'thegem' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'thegem' ),
				'label_off' => __( 'Hide', 'thegem' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_author',
			[
				'label' => __( 'Show Author', 'thegem' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'thegem' ),
				'label_off' => __( 'Hide', 'thegem' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_comments',
			[
				'label' => __( 'Show Comments', 'thegem' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'thegem' ),
				'label_off' => __( 'Hide', 'thegem' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_likes',
			[
				'label' => __( 'Show Likes', 'thegem' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'thegem' ),
				'label_off' => __( 'Hide', 'thegem' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();		

		$this->start_controls_section(
			'section_arrows',
			[
				'label' => __('Arrows', 'thegem'),
			]
		);

		$this->add_control(
			'arrows_show',
			[
				'label' => __('Arrows Bar', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'thegem'),
				'label_off' => __('Hide', 'thegem'),
			]
		);

		$this->add_control(
			'left_icon',
			[
				'label' => __('Left Arrow Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'gem-elegant arrow-carrot-left',
					'library' => 'thegem-elegant',
				],
				'condition' => [
					'arrows_show' => 'yes',
				],
			]
		);

		$this->add_control(
			'right_icon',
			[
				'label' => __('Right Arrow Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'gem-elegant arrow-carrot-right',
					'library' => 'thegem-elegant',
				],
				'condition' => [
					'arrows_show' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_options',
			[
				'label' => __('Options', 'thegem'),
			]
		);

		$this->add_control(
			'ignore_sticky_posts',
			[
				'label' => __( 'Ignore Sticky Posts', 'thegem' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'thegem' ),
				'label_off' => __( 'Off', 'thegem' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'autoscroll',
			[
				'label' => __('Autoscroll', 'thegem'),
				'default' => 'no',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
			]
		);

		$this->add_responsive_control(
			'autoscroll_speed',
			[
				'label' => __('Autoplay Speed', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
						'step' => 500,
					],
				],
				'default' => [
					'size' => 0,
				],
				'condition' => [
					'autoscroll' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->add_styles_controls( $this );
	}

	/**
	 * Controls call
	 * @access public
	 */
	public function add_styles_controls( $control ) {

		$this->control = $control;

		/* Container Styles*/
		$this->container_styles( $control );

		/* Image Styles */
		$this->image_styles( $control );

		/* Caption Styles */
		$this->caption_styles( $control );

		/* Caption Container Styles */
		$this->caption_container_styles( $control );

		/* SArrows Styles */
		$this->arrows_styles( $control );


	}

    /**
	 * Container Styles
	 * @access protected
	 */
	protected function container_styles( $control ) {

		$control->start_controls_section(
			'container_section',
			[
				'label' => __( 'Container Style', 'thegem' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$control->add_responsive_control(
			'container_height',
			[
				'label' => __( 'Height', 'thegem' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'render_type' => 'template',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 525,
					'unit' => 'px',
				],
				'selectors' => [	
					'{{WRAPPER}} article, {{WRAPPER}} article .gem-slider-item-image img' => 'height: {{SIZE}}{{UNIT}};',				
				],
			]
		);

		$control->add_control(
			'container_radius',
			[
				'label' => __('Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} article, {{WRAPPER}} .gem-blog-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'label' => __('Border', 'thegem'),
				'selector' => '{{WRAPPER}} article',
			]
		);

		$control->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_shadow',
				'label' => __('Shadow', 'thegem'),
				'selector' => '{{WRAPPER}} .gem-blog-slider',
			]
		);

		$control->end_controls_section();
	}

    /**
	 * Image Styles
	 * @access protected
	 */
	protected function image_styles( $control ) {

		$control->start_controls_section(
			'image_style_section',
			[
				'label' => __( 'Image Style', 'thegem' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$control->start_controls_tabs('image_tabs');
		$control->start_controls_tab('image_tabs_normal', ['label' => __('Normal', 'thegem'),]);

		$control->add_responsive_control(
			'image_position',
			[
				'label' => __( 'Position', 'thegem' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'Default', 'thegem' ),
					'top left' => __( 'Top Left', 'thegem' ),
					'top center' => __( 'Top Center', 'thegem' ),
					'top right' => __( 'Top Right', 'thegem' ),
					'center left' => __( 'Center Left', 'thegem' ),
					'center center' => __( 'Center Center', 'thegem' ),
					'center right' => __( 'Center Right', 'thegem' ),
					'bottom left' => __( 'Bottom Left', 'thegem' ),
					'bottom center' => _x( 'Bottom Center', 'thegem' ),
					'bottom right' => __( 'Bottom Right', 'thegem' ),
				],
				'selectors' => [
					'{{WRAPPER}} article .gem-slider-item-image' => 'background-position: {{VALUE}}!important;',
				],
			]
		);

		$control->add_responsive_control(
			'image_opacity_normal',
			[
				'label' => __('Opacity', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .gem-slider-item-image' => 'opacity: calc({{SIZE}}/100);',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_normal',
				'label' => __('CSS Filters', 'thegem'),
				'selector' => '{{WRAPPER}} .gem-slider-item-image',
			]
		);

		$control->end_controls_tab();
		$control->start_controls_tab('image_tabs_hover', ['label' => __('Hover', 'thegem'),]);

		$control->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'image_hover_overlay',
				'label' => __('Overlay Type', 'thegem'),
				'types' => ['classic', 'gradient'],
				'toggle' => true,
				'selector' => '{{WRAPPER}} article:hover .gem-slider-item-image:before',
				'fields_options' => [
					'background' => [
						'label' => _x('Overlay Type', 'Background Control', 'thegem'),
					],
				],
			]
		);

		$control->remove_control('image_hover_overlay_image');

		$control->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_hover_css',
				'label' => __('CSS Filters', 'thegem'),
				'selector' => '{{WRAPPER}} article:hover .gem-slider-item-image',
			]
		);

		$control->end_controls_tab();
		$control->end_controls_tabs();		

		$control->end_controls_section();
	}

    /**
	 * Caption Styles
	 * @access protected
	 */
	protected function caption_styles( $control ) {

		$control->start_controls_section(
			'caption_style_section',
			[
				'label' => __( 'Caption Style', 'thegem' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$control->add_control(
			'caption_title_heading',
			[
				'label' => __( 'Title', 'thegem' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);
		$control->start_controls_tabs( 'caption_title_tabs' );
		$control->start_controls_tab( 'caption_title_tab_normal', [ 'label' => __( 'Normal', 'thegem' ),'condition' => [ 'show_title' => 'yes',	], ] );


		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'thegem' ),
				'name' => 'caption_title_typography',
				'selector' => '{{WRAPPER}} .post-title .light',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$control->add_control(
			'caption_title_color',
			[
				'label' => __( 'Color', 'thegem' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .post-title .light' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab( 'caption_title_tab_hover', [ 'label' => __( 'Hover', 'thegem' ),'condition' => [ 'show_title' => 'yes',	], ] );

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'thegem' ),
				'name' => 'caption_title_typography_hover',
				'selector' => '{{WRAPPER}} article:hover .post-title .light',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$control->add_control(
			'caption_title_color_hover',
			[
				'label' => __( 'Color', 'thegem' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} article:hover .post-title .light' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$control->end_controls_tab();
		$control->end_controls_tabs();

		$control->add_control(
			'caption_description_heading',
			[
				'label' => __( 'Description', 'thegem' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_description' => 'yes',
				],
			]
		);
		$control->start_controls_tabs( 'caption_description_tabs' );
		$control->start_controls_tab( 'caption_description_tab_normal', [ 'label' => __( 'Normal', 'thegem' ), 'condition' => ['show_description' => 'yes',],] );


		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'thegem' ),
				'name' => 'caption_description_typography',
				'selector' => '{{WRAPPER}} .post-text .summary',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'condition' => [
					'show_description' => 'yes',
				],
			]
		);

		$control->add_control(
			'caption_description_color',
			[
				'label' => __( 'Color', 'thegem' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .post-text .summary' => 'color: {{VALUE}} !important;',
				],
				'condition' => [
					'show_description' => 'yes',
				],
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab( 'caption_description_tab_hover', [ 'label' => __( 'Hover', 'thegem' ),'condition' => ['show_description' => 'yes',], ] );

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'thegem' ),
				'name' => 'caption_description_typography_hover',
				'selector' => '{{WRAPPER}} article:hover .post-text .summary',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'condition' => [
					'show_description' => 'yes',
				],
			]
		);

		$control->add_control(
			'caption_description_color_hover',
			[
				'label' => __( 'Color', 'thegem' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} article:hover .post-text .summary' => 'color: {{VALUE}} !important;',
				],
				'condition' => [
					'show_description' => 'yes',
				],
			]
		);

		$control->end_controls_tab();
		$control->end_controls_tabs();

		$control->add_control(
			'caption_date_heading',
			[
				'label' => __( 'Date', 'thegem' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_date' => 'yes',
				],
			]
		);
		$control->start_controls_tabs( 'caption_date_tabs' );
		$control->start_controls_tab( 'caption_date_tab_normal', [ 'label' => __( 'Normal', 'thegem' ),'condition' => ['show_date' => 'yes',], ] );


		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'thegem' ),
				'name' => 'caption_date_typography',
				'selector' => '{{WRAPPER}} .post-title .entry-title-date',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'condition' => [
					'show_date' => 'yes',
				],
			]
		);

		$control->add_control(
			'caption_date_color',
			[
				'label' => __( 'Color', 'thegem' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .post-title .entry-title-date' => 'color: {{VALUE}} !important;',
				],
				'condition' => [
					'show_date' => 'yes',
				],
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab( 'caption_date_tab_hover', [ 'label' => __( 'Hover', 'thegem' ),'condition' => ['show_date' => 'yes',], ] );

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'thegem' ),
				'name' => 'caption_date_typography_hover',
				'selector' => '{{WRAPPER}} article:hover .post-title .entry-title-date',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'condition' => [
					'show_date' => 'yes',
				],
			]
		);

		$control->add_control(
			'caption_date_color_hover',
			[
				'label' => __( 'Color', 'thegem' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} article:hover .post-title .entry-title-date' => 'color: {{VALUE}} !important;',
				],
				'condition' => [
					'show_date' => 'yes',
				],
			]
		);

		$control->end_controls_tab();
		$control->end_controls_tabs();		

		$control->add_control(
			'caption_author_heading',
			[
				'label' => __( 'Author', 'thegem' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_author' => 'yes',
				],
			]
		);
		$control->start_controls_tabs( 'caption_author_tabs' );
		$control->start_controls_tab( 'caption_author_tab_normal', [ 'label' => __( 'Normal', 'thegem' ), 'show_author' => 'yes' ] );

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'thegem' ),
				'name' => 'caption_author_typography',
				'selector' => '{{WRAPPER}} .post-meta .post-meta-author',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'condition' => [
					'show_author' => 'yes',
				],
			]
		);

		$control->add_control(
			'caption_author_color',
			[
				'label' => __( 'Color', 'thegem' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .post-meta .post-meta-author' => 'color: {{VALUE}} !important;',
				],
				'condition' => [
					'show_author' => 'yes',
				],
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab( 'caption_author_tab_hover', [ 'label' => __( 'Hover', 'thegem' ), 'show_author' => 'yes'] );

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'thegem' ),
				'name' => 'caption_author_typography_hover',
				'selector' => '{{WRAPPER}} article:hover .post-meta .post-meta-author',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'condition' => [
					'show_author' => 'yes',
				],
			]
		);

		$control->add_control(
			'caption_author_color_hover',
			[
				'label' => __( 'Color', 'thegem' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} article:hover .post-meta .post-meta-author' => 'color: {{VALUE}} !important;',
				],
				'condition' => [
					'show_author' => 'yes',
				],
			]
		);

		$control->end_controls_tab();
		$control->end_controls_tabs();

		$this->add_control(
			'caption_author_by_text',
			[
				'label' => __('"By" Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'default' => __('By', 'thegem'),
				'condition' => [
					'show_author' => 'yes',
				],
			]
		);

		$control->add_control(
			'caption_delimiter_heading',
			[
				'label' => __( 'Delimiter', 'thegem' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$control->start_controls_tabs( 'caption_delimiter_tabs' );
		$control->start_controls_tab( 'caption_delimiter_tab_normal', [ 'label' => __( 'Normal', 'thegem' ) ] );


		$control->add_control(
			'caption_delimiter_color',
			[
				'label' => __( 'Color', 'thegem' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .post-meta-right .sep' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab( 'caption_delimiter_tab_hover', [ 'label' => __( 'Hover', 'thegem' ) ] );

		$control->add_control(
			'caption_delimiter_color_hover',
			[
				'label' => __( 'Color', 'thegem' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} article:hover .post-meta-right .sep' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$control->end_controls_tab();
		$control->end_controls_tabs();

		$control->add_control(
			'caption_likes_heading',
			[
				'label' => __( 'Likes', 'thegem' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_likes' => 'yes',
				],
			]
		);

		$control->add_control(
			'likes_icon',
			[
				'label' => __( 'Icon', 'thegem' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'gem-mdi mdi-heart',
					'library' => 'thegem-mdi',
				],
				'condition' => [
					'show_likes' => 'yes',
				],
			]
		);

		$control->start_controls_tabs( 'likes_icon_tabs' );
		$control->start_controls_tab( 'likes_icon_tab_normal', [ 'label' => __( 'Normal', 'thegem' ),	'show_likes' => 'yes' ] );

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'thegem' ),
				'name' => 'caption_likes_typography_normal',
				'selector' => '{{WRAPPER}} .post-meta-right .zilla-likes-count',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'condition' => [
					'show_likes' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'likes_icon_color_normal',
			[
				'label' => __( 'Icon Color', 'thegem' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .post-meta-right .zilla-likes:not(.active) + i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .post-meta-right .zilla-likes:not(.active) + svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .post-meta-right .zilla-likes:not(.active) .zilla-likes-count' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_likes' => 'yes',
				],
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab( 'likes_icon_tab_hover', [ 'label' => __( 'Hover', 'thegem' ),	'show_likes' => 'yes' ] );

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'thegem' ),
				'name' => 'caption_likes_typography_hover',
				'selector' => '{{WRAPPER}} .post-meta-right .post-meta-likes:hover .zilla-likes-count',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'condition' => [
					'show_likes' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'likes_icon_color_hover',
			[
				'label' => __( 'Icon Color', 'thegem' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .post-meta-right .post-meta-likes:hover .zilla-likes:not(.active) + i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .post-meta-right .post-meta-likes:hover .zilla-likes:not(.active) + svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .post-meta-right .post-meta-likes:hover .zilla-likes:not(.active) .zilla-likes-count' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_likes' => 'yes',
				],
			]
		);

		$control->end_controls_tab();
		$control->end_controls_tabs();		

		$control->add_control(
			'caption_comments_heading',
			[
				'label' => __( 'Comments', 'thegem' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_comments' => 'yes',
				],
			]
		);

		$control->add_control(
			'comments_icon',
			[
				'label' => __( 'Icon', 'thegem' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'gem-elegant icon-comment',
					'library' => 'thegem-elegant',
				],
				'condition' => [
					'show_comments' => 'yes',
				],
			]
		);

		$control->start_controls_tabs( 'comments_icon_tabs' );
		$control->start_controls_tab( 'comments_icon_tab_normal', [ 'label' => __( 'Normal', 'thegem' ) ] );

		$control->add_responsive_control(
			'comments_icon_color_normal',
			[
				'label' => __( 'Icon Color', 'thegem' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .post-meta-right .comments-link i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .post-meta-right .comments-link svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .post-meta-right .comments-link a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_comments' => 'yes',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'thegem' ),
				'name' => 'caption_comments_typography_normal',
				'selector' => '{{WRAPPER}} .post-meta-right .comments-link a',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'condition' => [
					'show_comments' => 'yes',
				],
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab( 'comments_icon_tab_hover', [ 'label' => __( 'Hover', 'thegem' ) ] );

		$control->add_responsive_control(
			'comments_icon_color_hover',
			[
				'label' => __( 'Icon Color', 'thegem' ),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .post-meta-right .comments-link:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .post-meta-right .comments-link:hover svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .post-meta-right .comments-link:hover a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_comments' => 'yes',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'thegem' ),
				'name' => 'caption_comments_typography_hover',
				'selector' => '{{WRAPPER}} .post-meta-right .comments-link:hover a',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'condition' => [
					'show_comments' => 'yes',
				],
			]
		);

		$control->end_controls_tab();
		$control->end_controls_tabs();		

		$control->end_controls_section();
	}

    /**
	 * Caption Container Styles
	 * @access protected
	 */
	protected function caption_container_styles( $control ) {

		$control->start_controls_section(
			'caption_container_style_section',
			[
				'label' => __( 'Caption Container Style', 'thegem' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$control->add_responsive_control(
			'caption_container_width',
			[
				'label' => __('Container Width', 'thegem'),
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
				'default' => [
					'size' => 50,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider .gem-slider-item-overlay' => 'width:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$control->add_responsive_control(
			'caption_container_height',
			[
				'label' => __('Container Height', 'thegem'),
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
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 70,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 70,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider .gem-slider-item-overlay' => 'height:{{SIZE}}{{UNIT}};',
				],
			]
		);
        
		$control->add_responsive_control(
			'caption_container_border_radius',
			[
				'label' => __('Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider .gem-slider-item-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'caption_container_border',
				'label' => __('Border', 'thegem'),
				'selector' => '{{WRAPPER}} .gem-blog-slider .gem-slider-item-overlay',
			]
		);

		$control->remove_control('caption_container_border_color');

		$control->add_responsive_control(
			'caption_container_padding',
			[
				'label' => __('Padding', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider .gem-slider-item-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_responsive_control(
			'caption_container_spacing_h',
			[
				'label' => __('Horizontal Spacing', 'thegem'),
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
				'default' => [
					'size' => 0,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 0,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 0,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider .gem-slider-item-overlay' => 'left:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$control->add_responsive_control(
			'caption_container_spacing_v',
			[
				'label' => __('Vertical Spacing', 'thegem'),
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
				'default' => [
					'size' => 0,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 30,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 30,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider .gem-slider-item-overlay' => 'top:{{SIZE}}{{UNIT}};',
				],
			]
		);	

		$control->start_controls_tabs( 'caption_container_tabs' );
		$control->start_controls_tab( 'caption_container_tab_normal', [ 'label' => __( 'Normal', 'thegem' ), ] );

		$control->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'caption_container_background',
				'label' => __('Background', 'thegem'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .gem-blog-slider .gem-slider-item-overlay',

			]
		);

		$control->remove_control('caption_container_background_image');

		$control->add_control(
			'caption_container_bordercolor',
			[
				'label' => __('Border Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider .gem-slider-item-overlay' => 'border-color: {{VALUE}};',
				],
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab( 'caption_container_tab_hover', [ 'label' => __( 'Hover', 'thegem' ), ] );

		$control->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'caption_container_background_hover',
				'label' => __('Background', 'thegem'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .gem-blog-slider article:hover .gem-slider-item-overlay',

			]
		);

		$control->remove_control('caption_container_background_hover_image');

		$control->add_control(
			'caption_container_bordercolor_hover',
			[
				'label' => __('Border Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider article:hover .gem-slider-item-overlay' => 'border-color: {{VALUE}};',
				],
			]
		);

		$control->end_controls_tab();

		$control->end_controls_tabs();

		$control->add_control(
			'title_spacing_heading',
			[
				'label' => __( 'Title', 'thegem' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_description' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'title_bottom_spacing',
			[
				'label' => __( 'Bottom Spacing', 'thegem' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider .post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$control->add_control(
			'description_bottom_spacing_heading',
			[
				'label' => __( 'Description', 'thegem' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_description' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'description_bottom_spacing',
			[
				'label' => __( 'Bottom Spacing', 'thegem' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider .post-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$control->end_controls_section();
	}    

	/**
	 * Arrows Styles
	 * @access protected
	 */
	protected function arrows_styles( $control ) {

		$control->start_controls_section(
			'arrows_styles_section',
			[
				'label' => __( 'Arrows Style', 'thegem' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'arrows_show' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'arrows_align',
			[
				'label' => __('Position', 'thegem'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __('Left', 'thegem'),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __('Center', 'thegem'),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __('Right', 'thegem'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'left',
				'toggle' => false,
				'selectors_dictionary' => [
					'left' => 'text-align: left;',
					'center' => 'text-align: center;',
					'right' => 'text-align: right;',
				],
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider .gem-blog-slider-navigation' => '{{VALUE}}',
				],
			]
		);

		$control->add_responsive_control(
			'arrows_spacing_v',
			[
				'label' => __('Top Spacing', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'rem', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
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
				'default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider .gem-blog-slider-navigation' => 'margin-top:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$control->add_responsive_control(
			'arrows_spacing_h',
			[
				'label' => __('Space Between', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'rem', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
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
				'default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider .gem-blog-slider-prev' => 'margin-right:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$control->add_responsive_control(
			'arrows_size',
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
					'{{WRAPPER}} .gem-blog-slider-prev, {{WRAPPER}} .gem-blog-slider-next' => 'width: {{SIZE}}px; height: {{SIZE}}px;', 
					'{{WRAPPER}} .gem-blog-slider-prev i, {{WRAPPER}} .gem-blog-slider-next i' => 'line-height: {{SIZE}}px;',
					'{{WRAPPER}} .gem-blog-slider-prev svg, {{WRAPPER}} .gem-blog-slider-next svg' => 'height: {{SIZE}}px;',
				],
			]
		);

		$control->add_responsive_control(
			'arrows_icon_size',
			[
				'label' => __('Icon Size', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider-prev i, {{WRAPPER}} .gem-blog-slider-next i' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .gem-blog-slider-prev svg, {{WRAPPER}} .gem-blog-slider-next svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
				],
			]
		);

		$control->add_responsive_control(
			'arrows_icon_radius',
			[
				'label' => __('Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
                    '{{WRAPPER}} .gem-blog-slider-prev, {{WRAPPER}} .gem-blog-slider-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'arrows_border',
				'label' => __('Border', 'thegem'),
				'selector' => '{{WRAPPER}} .gem-blog-slider-prev, {{WRAPPER}} .gem-blog-slider-next',
			]
		);

		$control->remove_control('arrows_border_color');

		$control->start_controls_tabs('arrows_tabs');
		$control->start_controls_tab('arrows_tabs_normal', ['label' => __('Normal', 'thegem'),]);

		$control->add_responsive_control(
			'arrows_bg_color_normal',
			[
				'label' => __('Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider-prev, {{WRAPPER}} .gem-blog-slider-next' => 'background-color: {{VALUE}};',
				],
			]
		);	

		$control->add_responsive_control(
			'arrows_border_color_normal',
			[
				'label' => __('Border Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider-prev, {{WRAPPER}} .gem-blog-slider-next' => 'border-color: {{VALUE}};',
				],
			]
		);	

		$control->add_responsive_control(
			'arrows_icon_color_normal',
			[
				'label' => __('Icon Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .gem-blog-slider-prev i, {{WRAPPER}} .gem-blog-slider-next i' => 'color: {{VALUE}}!important;',
					'{{WRAPPER}} .gem-blog-slider-prev svg, {{WRAPPER}} .gem-blog-slider-next svg' => 'fill: {{VALUE}}!important;',
				],
			]
		);			

		$control->end_controls_tab();

		$control->start_controls_tab(
			'arrows_tabs_hover',
			[
				'label' => __('Hover', 'thegem'),
			]
		);	

		$control->add_responsive_control(
			'arrows_bg_color_hover',
			[
				'label' => __('Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} article .gem-blog-slider-prev:hover, {{WRAPPER}} article .gem-blog-slider-next:hover' => 'background-color: {{VALUE}};',
				],
			]
		);	

		$control->add_responsive_control(
			'arrows_border_color_hover',
			[
				'label' => __('Border Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} article .gem-blog-slider-prev:hover, {{WRAPPER}} article .gem-blog-slider-next:hover' => 'border-color: {{VALUE}};',
				],
			]
		);	

		$control->add_responsive_control(
			'arrows_icon_color_hover',
			[
				'label' => __('Icon Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} article .gem-blog-slider-prev:hover i, {{WRAPPER}} article .gem-blog-slider-next:hover i' => 'color: {{VALUE}}!important;',
					'{{WRAPPER}} article .gem-blog-slider-prev:hover svg, {{WRAPPER}} article .gem-blog-slider-next:hover svg' => 'fill: {{VALUE}}!important;',
				],
			]
		);		

		$control->end_controls_tab();
		$control->end_controls_tabs();

		$control->end_controls_section();
	}    

	/**
	 * Helper check in array value
	 * @access protected
	 * @return string
	 */
	function is_in_array_value( $array = array(), $value = '', $default = '' ) {
		if ( in_array( $value, $array ) ) {
			return $value;
		}
		return $default;
	}

	protected function get_setting_preset( $val ) {
		if( empty( $val ) ) {
			return '';
		}

		return $val;
	}

	protected function get_presets_arg( $val ) {
		if ( empty( $val ) ) {
			return null;
		}

		return json_decode( $val, true );
	}

	protected function get_setting_cat( $val ) {
		if ( empty( $val ) ) {
			return (array) 'all';
		}

		return (array) $val;
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

		if ('yes' === $settings['autoscroll']) {
			$this->add_render_attribute( 'scroll-wrap', 'data-autoscroll', $settings['autoscroll_speed']['size']);
		}

		$this->add_render_attribute('blogslider_wrapper', 'class',
			[
				'gem-blog-slider gem-blog-slider-style-fullwidth clearfix ',
				(Plugin::$instance->editor->is_edit_mode() ? 'lazy-loading-not-hide' : '')
			]);	
		
		$this->add_render_attribute('blogslider_wrapper', 'data-autoscroll',
			[
				'yes' === $settings['autoscroll'] ? $settings['autoscroll_speed']['size'] : '',
			]);

		$thegem_post_data = thegem_get_sanitize_page_title_data(get_the_ID());

		$thegem_classes[] = 'clearfix';

		if ($settings['source_type'] == 'archive') {
			if ( is_category() ) {
				$settings['source'] = ['categories'];
				$settings['select_blog_cat'] = array(get_queried_object()->slug);
			} else if ( is_tag() ) {
				$settings['source'] = ['tags'];
				$settings['select_blog_tags'] = array(get_queried_object()->slug);
			} else if ( is_author() ) {
				$settings['source'] = ['authors'];
				$settings['select_blog_authors'] = array(get_queried_object()->ID);
			} else {
				$settings['source'] = ['categories'];
				$settings['select_blog_cat'] = ['0'];
			}
		} else if ($settings['source_type'] == 'related') {
			$settings['source'] = $settings['related_by'];
			$settings['select_blog_cat'] = ['0'];
			if (in_array('categories', $settings['source'])) {
				$settings['select_blog_cat'] = [];
				$categories = get_the_category();
				if ( ! empty( $categories ) ) {
					foreach( $categories as $category ) {
						$settings['select_blog_cat'][] = $category->slug;
					}
				}
			}
			if (in_array('tags', $settings['source'])) {
				$settings['select_blog_tags'] = [];
				$tags = get_the_terms( get_the_ID(), 'post_tag' );
				if ( ! empty( $tags ) ) {
					foreach( $tags as $tag ) {
						$settings['select_blog_tags'][] = $tag->slug;
					}
				}
			}
			if (in_array('authors', $settings['source'])) {
				$settings['select_blog_authors'] = get_the_author_meta( 'ID' );
			}
			if ($settings['exclude_blog_posts']) {
				$settings['exclude_blog_posts'][] = get_the_ID();
			} else {
				$settings['exclude_blog_posts'] = [get_the_ID()];
			}
		}

		if (!is_array($settings['source'])) {
			$settings['source'] = array($settings['source']);
		}
		$blog_categories = $blog_tags = $blog_posts = $blog_authors = [];
		if (in_array('categories', $settings['source']) && !empty($settings['select_blog_cat'])) {
			$blog_categories = $settings['select_blog_cat'];
		}
		if (in_array('tags', $settings['source']) && !empty($settings['select_blog_tags'])) {
			$blog_tags = $settings['select_blog_tags'];
		}
		if (in_array('posts', $settings['source']) && !empty($settings['select_blog_posts'])) {
			$blog_posts = $settings['select_blog_posts'];
		}
		if (in_array('authors', $settings['source']) && !empty($settings['select_blog_authors'])) {
			$blog_authors = $settings['select_blog_authors'];
		}

		if (empty($settings['source']) && $settings['source_type'] == 'custom') { ?>
			<div class="bordered-box centered-box styled-subtitle">
				<?php echo __('Please select blog sources in "Blog" section', 'thegem') ?>
			</div>
			<?php
			return;
		}

		$orderby = $order = '';
		if (isset($settings['order_by']) && $settings['order_by'] != 'default') {
			$orderby = $settings['order_by'];
		}
		if (isset($settings['order']) && $settings['order'] != 'default') {
			$order = $settings['order'];
		}

		$items_per_page = $settings['max_posts'] ?: -1;

		$posts = get_thegem_extended_blog_posts($blog_categories, $blog_tags, $blog_posts, $blog_authors, 1, $items_per_page, $orderby, $order, $settings['offset'], $settings['exclude_blog_posts'], $settings['ignore_sticky_posts']);

		if ( $posts->have_posts() ) { ?>
			<div class="preloader"><div class="preloader-spin"></div></div>
			<div <?php echo $this->get_render_attribute_string( 'blogslider_wrapper' ); ?>>
			<?php while($posts->have_posts()) {
			$posts->the_post();

				$preset_path = __DIR__ . '/templates/content-blog-item.php';
				$preset_path_filtered = apply_filters( 'thegem_blog_slider_item_preset', $preset_path);
				$preset_path_theme = get_stylesheet_directory() . '/templates/blog-slider/content-blog-item.php';

				if (!empty($preset_path_theme) && file_exists($preset_path_theme)) {
					include($preset_path_theme);
				} else if (!empty($preset_path_filtered) && file_exists($preset_path_filtered)) {
					include($preset_path_filtered);
				}
			} ?>
			</div>
			<?php if(is_admin() && Plugin::$instance->editor->is_edit_mode() ): ?>
			<script type="text/javascript">
				jQuery('body').prepareBlogSlider();
				jQuery('body').updateBlogSlider();
			</script>
			<?php endif; 	
			wp_reset_postdata();
		}
	}
}

\Elementor\Plugin::instance()->widgets_manager->register( new TheGem_Blogslider() );