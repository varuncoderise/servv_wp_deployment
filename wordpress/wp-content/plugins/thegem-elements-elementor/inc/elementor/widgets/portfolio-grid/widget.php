<?php

namespace TheGem_Elementor\Widgets\Portfolio;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;

if (!defined('ABSPATH')) exit;

/**
 * Elementor widget for Portfolio Grid.
 */
class TheGem_Portfolio extends Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		$localize = array(
			'action' => 'portfolio_grid_load_more',
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('portfolio_ajax-nonce')
		);
		wp_localize_script('thegem-portfolio-grid-extended', 'thegem_portfolio_ajax', $localize);

		$this->states_list = [
			'normal' => __('Normal', 'thegem'),
			'hover' => __('Hover', 'thegem'),
			'active' => __('Active', 'thegem'),
		];

		$this->schemes_list = [
			'6' => [
				'6a' => [
					'count' => 9,
					0 => 'squared',
				],
				'6b' => [
					'count' => 7,
					0 => 'squared',
					1 => 'horizontal',
					6 => 'horizontal',
				],
				'6c' => [
					'count' => 9,
					0 => 'horizontal',
					3 => 'horizontal',
					6 => 'horizontal',
				],
				'6d' => [
					'count' => 9,
					0 => 'horizontal',
					1 => 'horizontal',
					2 => 'horizontal',
				],
				'6e' => [
					'count' => 6,
					0 => 'squared',
					1 => 'squared',
				]
			],
			'5' => [
				'5a' => [
					'count' => 7,
					0 => 'squared',
				],
				'5b' => [
					'count' => 8,
					0 => 'horizontal',
					4 => 'horizontal',
				],
				'5c' => [
					'count' => 6,
					0 => 'horizontal',
					1 => 'horizontal',
					4 => 'horizontal',
					5 => 'horizontal',
				],
				'5d' => [
					'count' => 4,
					0 => 'squared',
					1 => 'vertical',
					2 => 'horizontal',
					3 => 'horizontal',
				]
			],
			'4' => [
				'4a' => [
					'count' => 5,
					0 => 'squared',
				],
				'4b' => [
					'count' => 4,
					0 => 'squared',
					1 => 'horizontal',
				],
				'4c' => [
					'count' => 4,
					0 => 'squared',
					1 => 'vertical',
				],
				'4d' => [
					'count' => 7,
					0 => 'vertical',
				],
				'4e' => [
					'count' => 4,
					0 => 'vertical',
					1 => 'vertical',
					2 => 'horizontal',
					3 => 'horizontal',
				],
				'4f' => [
					'count' => 6,
					0 => 'horizontal',
					5 => 'horizontal',
				]
			],
			'3' => [
				'3a' => [
					'count' => 4,
					0 => 'vertical',
					1 => 'vertical',
				],
				'3b' => [
					'count' => 4,
					1 => 'horizontal',
					2 => 'horizontal',
				],
				'3c' => [
					'count' => 5,
					0 => 'vertical',
				],
				'3d' => [
					'count' => 5,
					0 => 'horizontal',
				],
				'3e' => [
					'count' => 3,
					0 => 'squared',
				],
				'3f' => [
					'count' => 4,
					0 => 'horizontal',
					1 => 'vertical',
				],
				'3g' => [
					'count' => 4,
					0 => 'vertical',
					3 => 'horizontal',
				],
				'3h' => [
					'count' => 5,
					2 => 'vertical',
				]
			],
			'2' => [
				'2a' => [
					'count' => 5,
					0 => 'vertical',
				],
				'2b' => [
					'count' => 5,
					3 => 'vertical',
				],
				'2c' => [
					'count' => 4,
					0 => 'vertical',
					2 => 'vertical',
				],
				'2d' => [
					'count' => 4,
					0 => 'horizontal',
					1 => 'vertical',
				],
				'2e' => [
					'count' => 5,
					0 => 'horizontal',
				],
				'2f' => [
					'count' => 4,
					0 => 'horizontal',
					1 => 'horizontal',
				],
				'2g' => [
					'count' => 5,
					2 => 'horizontal',
				],
				'2h' => [
					'count' => 4,
					0 => 'horizontal',
					3 => 'horizontal',
				],
			]
		];
	}

	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'thegem-portfolio';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __('Portfolio Grid', 'thegem');
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
		return ['thegem_portfolios'];
	}

	public function get_style_depends() {
		if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
			return [
				'thegem-button',
				'thegem-animations',
				'thegem-hovers-default',
				'thegem-hovers-zooming-blur',
				'thegem-hovers-horizontal-sliding',
				'thegem-hovers-vertical-sliding',
				'thegem-hovers-zoom-overlay',
				'thegem-hovers-gradient',
				'thegem-hovers-circular',
				'thegem-portfolio'];
		}
		return ['thegem-portfolio'];
	}

	public function get_script_depends() {
		if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
			return [
				'jquery-dlmenu',
				'thegem-animations',
				'thegem-items-animations',
				'thegem-scroll-monitor',
				'thegem-isotope-metro',
				'thegem-isotope-masonry-custom',
				'thegem-portfolio-grid-extended'];
		}
		return ['thegem-portfolio-grid-extended'];
	}

	/* Show reload button */
	public function is_reload_preview_required() {
		return true;
	}


	/**
	 * Make options select portfolio sets
	 * @access protected
	 * @return array
	 */
	protected function select_portfolio_sets() {
		$out = ['0' => __('All', 'thegem')];
		$terms = get_terms([
			'taxonomy' => 'thegem_portfolios',
			'hide_empty' => true,
		]);

		if (empty($terms) || is_wp_error($terms)) {
			return $out;
		}

		foreach ((array)$terms as $term) {
			if (!empty($term->name)) {
				$out[$term->slug] = $term->name;
			}
		}
		return $out;
	}

	/**
	 * Register the widget controls.
	 *
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_layout',
			[
				'label' => __('Layout', 'thegem'),
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => __('Layout', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'justified',
				'options' => [
					'justified' => __('Justified Grid', 'thegem'),
					'masonry' => __('Masonry Grid', 'thegem'),
					'metro' => __('Metro Style', 'thegem'),
					'creative' => __('Creative Grid', 'thegem'),
				],
			]
		);

		$this->add_control(
			'columns',
			[
				'label' => __('Columns', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => '4x',
				'options' => [
					'2x' => __('2x columns', 'thegem'),
					'3x' => __('3x columns', 'thegem'),
					'4x' => __('4x columns', 'thegem'),
					'100%' => __('100% width', 'thegem'),
				],
				'condition' => [
					'layout!' => 'creative',
				],
			]
		);

		$this->add_control(
			'columns_desktop',
			[
				'label' => __('Columns Desktop', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => '4x',
				'options' => [
					'2x' => __('2x columns', 'thegem'),
					'3x' => __('3x columns', 'thegem'),
					'4x' => __('4x columns', 'thegem'),
					'5x' => __('5x columns', 'thegem'),
					'6x' => __('6x columns', 'thegem'),
					'100%' => __('100% width', 'thegem'),
				],
				'condition' => [
					'layout' => 'creative',
				],
			]
		);

		$this->add_control(
			'columns_tablet',
			[
				'label' => __('Columns Tablet', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => '3x',
				'options' => [
					'1x' => __('1x columns', 'thegem'),
					'2x' => __('2x columns', 'thegem'),
					'3x' => __('3x columns', 'thegem'),
					'4x' => __('4x columns', 'thegem'),
				],
				'condition' => [
					'layout' => 'creative',
				],
			]
		);

		$this->add_control(
			'columns_mobile',
			[
				'label' => __('Columns Mobile', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => '2x',
				'options' => [
					'1x' => __('1x columns', 'thegem'),
					'2x' => __('2x columns', 'thegem'),
				],
				'condition' => [
					'layout' => 'creative',
				],
			]
		);

		$this->add_control(
			'columns_100',
			[
				'label' => __('100% Width Columns', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => '4',
				'options' => [
					'4' => __('4 Columns', 'thegem'),
					'5' => __('5 Columns', 'thegem'),
				],
				'condition' => [
					'columns' => '100%',
					'layout!' => 'creative',
				],
				'description' => __('Number of columns for 100% width grid for desktop resolutions starting from 1920 px and above', 'thegem'),
			]
		);

		$this->add_control(
			'columns_100_creative',
			[
				'label' => __('100% Width Columns', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => '4',
				'options' => [
					'4' => __('4 Columns', 'thegem'),
					'5' => __('5 Columns', 'thegem'),
					'6' => __('6 Columns', 'thegem'),
				],
				'condition' => [
					'columns_desktop' => '100%',
					'layout' => 'creative',
				],
				'description' => __('Number of columns for 100% width grid for desktop resolutions starting from 1920 px and above', 'thegem'),
			]
		);

		foreach ((array)$this->schemes_list as $scheme_col => $scheme_values) {

			$options = [];
			$default = '';
			$i = 1;

			foreach ($scheme_values as $scheme_key => $scheme_val) {
				$options[$scheme_key] = __('Scheme', 'thegem') . $i;
				if ($i == 1) {
					$default = $scheme_key;
				}
				$i++;
			}

			$this->add_control(
				'layout_scheme_' . strval($scheme_col) . 'x',
				[
					'label' => __('Layout Scheme', 'thegem'),
					'type' => Controls_Manager::SELECT,
					'default' => $default,
					'options' => $options,
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'layout',
								'operator' => '=',
								'value' => 'creative',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'columns_desktop',
										'operator' => '=',
										'value' => strval($scheme_col) . 'x',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'columns_desktop',
												'operator' => '=',
												'value' => '100%',
											], [
												'name' => 'columns_100_creative',
												'operator' => '=',
												'value' => strval($scheme_col),
											],
										]
									]
								],
							],
						],
					],
				]
			);

			foreach ($scheme_values as $scheme_key => $scheme_val) {

				$this->add_control(
					'layout_scheme_' . $scheme_key,
					[
						'type' => \Elementor\Controls_Manager::RAW_HTML,
						'raw' => '<img src="' . THEGEM_ELEMENTOR_URL . '/assets/img/creative/scheme' . $scheme_key . '.png">',
						'conditions' => [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '=',
									'value' => 'creative',
								],
								[
									'relation' => 'or',
									'terms' => [
										[
											'name' => 'columns_desktop',
											'operator' => '=',
											'value' => strval($scheme_col) . 'x',
										],
										[
											'relation' => 'and',
											'terms' => [
												[
													'name' => 'columns_desktop',
													'operator' => '=',
													'value' => '100%',
												], [
													'name' => 'columns_100_creative',
													'operator' => '=',
													'value' => strval($scheme_col),
												],
											]
										]
									],
								],
								[
									'name' => 'layout_scheme_' . strval($scheme_col) . 'x',
									'operator' => '=',
									'value' => strval($scheme_key),
								]
							],
						],
					]
				);
			}
		}

		$this->add_control(
			'scheme_apply_mobiles', [
				'label' => __('Apply on mobiles', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'layout' => 'creative',
				],
			]
		);

		$this->add_control(
			'scheme_apply_tablets', [
				'label' => __('Apply on tablets', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'layout' => 'creative',
				],
			]
		);

		$this->add_control(
			'disable_preloader', [
				'label' => __('Disable Grid Preloader', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'layout' => 'justified',
					'columns!' => '100%',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_portfolios',
			[
				'label' => __('Portfolios', 'thegem'),
			]
		);

		$this->add_control(
			'content_portfolios_cat',
			[
				'label' => __('Select Portfolios', 'thegem'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $this->select_portfolio_sets(),
				'frontend_available' => true,
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
			'exclude_portfolios',
			[
				'label' => __('Exclude Portfolio Items', 'thegem'),
				'type' => 'gem-query-control',
				'search' => 'thegem_get_posts_by_query',
				'render' => 'thegem_get_posts_title_by_id',
				'post_type' => 'thegem_pf_item',
				'label_block' => true,
				'multiple' => true,
			]
		);

		$portfolio_fields = [
			'title' => __('Title', 'thegem'),
			'description' => __('Description', 'thegem'),
			'date' => __('Date', 'thegem'),
			'sets' => __('Categories', 'thegem'),
			'filter' => __('Filter Buttons', 'thegem'),
			'sorting' => __('Sorting', 'thegem'),
			'likes' => __('Likes', 'thegem'),
		];

		foreach ($portfolio_fields as $ekey => $elem) {

			$condition = [];

			if ($ekey == 'likes') {
				$condition = [
					'caption_position' => 'page',
				];
			}

			$this->add_control(
				'portfolio_show_' . $ekey, [
					'label' => $elem,
					'default' => 'yes',
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __('Show', 'thegem'),
					'label_off' => __('Hide', 'thegem'),
					'frontend_available' => true,
					'condition' => $condition,
				]
			);

			if ($ekey == 'filter') {
				$this->add_control(
					'filters_preloading',
					[
						'label' => __('Filter Preloading', 'thegem'),
						'default' => 'yes',
						'type' => Controls_Manager::SWITCHER,
						'label_on' => __('On', 'thegem'),
						'label_off' => __('Off', 'thegem'),
						'condition' => [
							'portfolio_show_filter' => 'yes',
						],
						'description' => __('If enabled, items in the filter buttons will be preloaded on the current page.', 'thegem'),
					]
				);
			}
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_caption',
			[
				'label' => __('Caption', 'thegem'),
			]
		);

		$this->add_control(
			'caption_position',
			[
				'label' => __('Caption Position', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'page',
				'options' => [
					'page' => __('Below Image', 'thegem'),
					'hover' => __('On Hover', 'thegem'),
					'image' => __('On Image', 'thegem'),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination',
			[
				'label' => __('Pagination', 'thegem'),
			]
		);

		$this->add_control(
			'items_per_page',
			[
				'label' => __('Items Per Page', 'thegem'),
				'type' => Controls_Manager::NUMBER,
				'min' => -1,
				'max' => 100,
				'step' => 1,
				'default' => 8,
				'description' => __('Use -1 to show all', 'thegem'),
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => __('Pagination', 'thegem'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label' => __('Pagination Type', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => __('Numbers', 'thegem'),
					'more' => __('Load More Button', 'thegem'),
					'scroll' => __('Infinite Scroll', 'thegem'),
				],
				'condition' => [
					'show_pagination' => 'yes',
				],
			]
		);

		$this->add_control(
			'next_page_preloading',
			[
				'label' => __('Next page preloading', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'show_pagination' => 'yes',
				],
				'description' => __('If enabled, items for the next page will be preloaded on the current page.', 'thegem'),
			]
		);

		$this->add_control(
			'more_button_text',
			[
				'label' => __('Button Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Load More', 'thegem'),
				'condition' => [
					'pagination_type' => 'more',
					'show_pagination' => 'yes',
				],
			]
		);

		$this->add_control(
			'more_icon',
			[
				'label' => __('Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
				'condition' => [
					'pagination_type' => 'more',
					'show_pagination' => 'yes',
				],
			]
		);

		$this->add_control(
			'more_stretch_full_width',
			[
				'label' => __('Stretch to Full Width', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'pagination_type' => 'more',
					'show_pagination' => 'yes',
				],
			]
		);

		$this->add_control(
			'more_show_separator',
			[
				'label' => __('Separator', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'more_stretch_full_width!' => 'yes',
					'pagination_type' => 'more',
					'show_pagination' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_sharing',
			[
				'label' => __('Social Sharing', 'thegem'),
			]
		);

		$this->add_control(
			'social_sharing',
			[
				'label' => __('Social Sharing', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
			]
		);

		$share_options = [
			'facebook' => __('Facebook', 'thegem'),
			'twitter' => __('Twitter', 'thegem'),
			'pinterest' => __('Pinterest', 'thegem'),
			'tumblr' => __('Tumblr', 'thegem'),
			'linkedin' => __('Linkedin', 'thegem'),
			'reddit' => __('Reddit', 'thegem'),
		];

		foreach ($share_options as $ekey => $elem) {

			$this->add_control(
				'shares_show_' . $ekey, [
					'label' => $elem,
					'default' => 'yes',
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __('Show', 'thegem'),
					'label_off' => __('Hide', 'thegem'),
					'condition' => [
						'social_sharing' => 'yes',
					]
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_animations',
			[
				'label' => __('Animations', 'thegem'),
			]
		);

		$this->add_control(
			'loading_animation',
			[
				'label' => __('Lazy Loading Animation', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
			]
		);

		$this->add_control(
			'animation_effect',
			[
				'label' => __('Animation Effect', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'bounce',
				'options' => [
					'bounce' => __('Bounce', 'thegem'),
					'move-up' => __('Move Up', 'thegem'),
					'fade-in' => __('Fade In', 'thegem'),
					'fall-perspective' => __('Fall Perspective', 'thegem'),
					'scale' => __('Scale', 'thegem'),
					'flip' => __('Flip', 'thegem'),
				],
				'condition' => [
					'loading_animation' => 'yes',
				],
			]
		);

		$this->add_control(
			'loading_animation_mobile',
			[
				'label' => __('Enable animation on mobile', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'loading_animation' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional',
			[
				'label' => __('Additional Options', 'thegem'),
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'layout',
							'operator' => '!=',
							'value' => 'justified',
						],
						[
							'name' => 'columns',
							'operator' => '=',
							'value' => '100%',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '=',
									'value' => 'justified',
								],
								[
									'name' => 'disable_preloader',
									'operator' => '!=',
									'value' => 'yes',
								],
							]
						]
					]
				]
			]
		);

		$this->add_control(
			'ignore_highlights',
			[
				'label' => __('Ignore Highlighted Products', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
			]
		);

		$this->add_control(
			'skeleton_loader',
			[
				'label' => __('Skeleton Preloader on grid loading', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'columns!' => '100%',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_metro',
			[
				'label' => __('Metro Options', 'thegem'),
				'condition' => [
					'layout' => 'metro',
				],
			]
		);

		$this->add_responsive_control(
			'metro_max_row_height',
			[
				'label' => __('Max. row\'s height in metro grid (px)', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 600,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 380
				],
				'description' => __('Metro grid auto sets the row\'s height. Specify max. allowed height for best appearance (380px recommended).', 'thegem'),
			]
		);

		$this->end_controls_section();

		$this->add_styles_controls($this);
	}

	/**
	 * Controls call
	 * @access public
	 */
	public function add_styles_controls($control) {
		$this->control = $control;

		/* Images Style */
		$this->image_style($control);

		/* Caption Style */
		$this->caption_style($control);

		/* Caption Container Style */
		$this->caption_container_style($control);

		/* Filter Buttons Style */
		$this->filter_buttons_style($control);

		/* Sorting Style */
		$this->sorting_style($control);

		/* Pagination Style */
		$this->pagination_style($control);

		/* Pagination More Style */
		$this->pagination_more_style($control);

		/* Likes Style */
		$this->likes_style($control);

		/* Hover Icons (Custom) */
		$this->hover_icons_style($control);
	}

	/**
	 * Grid Images Style
	 * @access protected
	 */
	protected function image_style($control) {
		$control->start_controls_section(
			'image_style',
			[
				'label' => __('Grid Images Style', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$control->add_responsive_control(
			'image_gaps',
			[
				'label' => __('Gaps', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'rem', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'rem' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 42,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item,
					{{WRAPPER}} .skeleton-posts.portfolio-row .portfolio-item' => 'padding: calc({{SIZE}}{{UNIT}}/2) !important;',
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-row,
					{{WRAPPER}} .skeleton-posts.portfolio-row' => 'margin: calc(-{{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .portfolio.portfolio-grid.fullwidth-columns .portfolio-row' => 'margin: calc(-{{SIZE}}{{UNIT}}/2) 0;',
					'{{WRAPPER}} .portfolio.portfolio-grid .fullwidth-block:not(.no-paddings)' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.portfolio-grid .fullwidth-block .portfolio-row' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
				]
			]
		);

		$control->add_control(
			'image_height',
			[
				'label' => __('Image Height', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item:not(.double-item) .image-inner' => 'height: {{SIZE}}{{UNIT}} !important; padding-bottom: 0 !important;',
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item:not(.double-item) .gem-simple-gallery .gem-gallery-item a' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$control->add_control(
			'fullwidth_section_images',
			[
				'label' => __('Used in fullwidth section', 'thegem'),
				'separator' => 'before',
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'description' => __('Activate for better image quality in case of using in fullwidth section', 'thegem'),
			]
		);

		$control->add_responsive_control(
			'image_border_radius',
			[
				'label' => __('Border Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.portfolio-grid.caption-position-page .portfolio-item .wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
					'{{WRAPPER}} .portfolio.portfolio-grid.caption-position-hover .portfolio-item .wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.portfolio-grid.caption-position-image .portfolio-item .wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_shadow',
				'label' => __('Shadow', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio.portfolio-grid:not(.shadowed-container) .portfolio-item .image, {{WRAPPER}} .portfolio.portfolio-grid.shadowed-container .portfolio-item .wrap',
			]
		);

		$control->add_control(
			'shadowed_container',
			[
				'label' => __('Apply shadow on caption container', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'thegem'),
				'label_off' => __('Hide', 'thegem'),
				'frontend_available' => true,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'caption_position',
							'operator' => '=',
							'value' => 'page',
						],
						[
							'name' => 'image_shadow_box_shadow_type',
							'operator' => '=',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$control->start_controls_tabs('image_tabs');
		$control->start_controls_tab('image_tab_normal', ['label' => __('Normal', 'thegem'),]);

		$control->add_control(
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
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .image-inner' => 'opacity: calc({{SIZE}}/100);',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_normal',
				'label' => __('CSS Filters', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .image-inner',
			]
		);


		$control->end_controls_tab();
		$control->start_controls_tab('image_tab_hover', ['label' => __('Hover', 'thegem'),]);

		$this->add_control(
			'image_hover_effect_hover',
			[
				'label' => __('Hover Effect', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __('Cyan Breeze', 'thegem'),
					'zooming-blur' => __('Zooming White', 'thegem'),
					'horizontal-sliding' => __('Horizontal Sliding', 'thegem'),
					'vertical-sliding' => __('Vertical Sliding', 'thegem'),
					'gradient' => __('Gradient', 'thegem'),
					'circular' => __('Circular Overlay', 'thegem'),
					'zoom-overlay' => __('Zoom & Overlay', 'thegem'),
					'disabled' => __('Disabled', 'thegem'),
				],
				'condition' => ['caption_position' => 'hover',],
			]
		);

		$this->add_control(
			'image_hover_effect_page',
			[
				'label' => __('Hover Effect', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __('Cyan Breeze', 'thegem'),
					'zooming-blur' => __('Zooming White', 'thegem'),
					'horizontal-sliding' => __('Horizontal Sliding', 'thegem'),
					'vertical-sliding' => __('Vertical Sliding', 'thegem'),
					'zoom-overlay' => __('Zoom & Overlay', 'thegem'),
					'disabled' => __('Disabled', 'thegem'),
				],
				'condition' => ['caption_position' => 'page',],
			]
		);

		$this->add_control(
			'image_hover_effect_image',
			[
				'label' => __('Hover Effect', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'gradient',
				'options' => [
					'gradient' => __('Gradient', 'thegem'),
					'circular' => __('Circular Overlay', 'thegem'),
				],
				'condition' => ['caption_position' => 'image',],
			]
		);

		$this->add_control(
			'hover_elements_size',
			[
				'label' => __('Hover Elements Size', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __('Default', 'thegem'),
					'small' => __('Small', 'thegem'),
					'big' => __('Big', 'thegem'),
				],
			]
		);

		$control->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'image_hover_overlay',
				'label' => __('Overlay Type', 'thegem'),
				'types' => ['classic', 'gradient'],
				'default' => 'classic',
				'fields_options' => [
					'background' => [
						'label' => _x('Overlay Type', 'Background Control', 'thegem'),
					],
					'color' => [
						'selectors' => [
							'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .overlay:before, {{WRAPPER}} .portfolio.portfolio-grid.hover-circular .portfolio-item .image .overlay .overlay-circle' => 'background: {{VALUE}} !important;',
						],
					],
					'gradient_angle' => [
						'selectors' => [
							'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .overlay:before, {{WRAPPER}} .portfolio.portfolio-grid.hover-circular .portfolio-item .image .overlay .overlay-circle' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}) !important;',
						],
					],
					'gradient_position' => [
						'selectors' => [
							'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .overlay:before, {{WRAPPER}} .portfolio.portfolio-grid.hover-circular .portfolio-item .image .overlay .overlay-circle' => 'background-color: transparent; background-image: radial-gradient(at {{SIZE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}) !important;',
						],
					],
				]

			]
		);

		$control->remove_control('image_hover_overlay_image');

		$control->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_hover_css',
				'label' => __('CSS Filters', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item:hover .image-inner',
			]
		);

		$control->add_control(
			'icons_header',
			[
				'label' => __('Icons', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_control(
			'icons_show',
			[
				'label' => __('Show', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'thegem'),
				'label_off' => __('Hide', 'thegem'),
				'frontend_available' => true,
			]
		);

		$control->add_control(
			'icons_color',
			[
				'label' => __('Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .image .overlay .links a.icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .image .overlay .links .overlay-line:after' => 'background: {{VALUE}};'
				],
				'condition' => [
					'icons_show' => 'yes',
				],
			]
		);

		$control->add_control(
			'icons_background_color',
			[
				'label' => __('Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .image .overlay .links a.icon i' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'icons_show' => 'yes',
					'caption_position' => 'hover',
					'image_hover_effect_hover' => 'zooming-blur'
				],
			]
		);


		$control->add_responsive_control(
			'icons_size',
			[
				'label' => __('Size', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'rem', 'em'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 300,
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
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item:not(.hover-zooming-blur) .image .overlay .links a.icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item:not(.hover-zooming-blur) .image .overlay .links a.icon i' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item:not(.hover-zooming-blur) .image .overlay .links a.icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.portfolio-grid.hover-zooming-blur .portfolio-item .image .overlay .links a.icon, {{WRAPPER}} .portfolio.portfolio-grid.hover-gradient .portfolio-item .image .overlay .links a.icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.portfolio-grid.hover-zooming-blur .portfolio-item .image .overlay .links a.icon i, {{WRAPPER}} .portfolio.portfolio-grid.hover-gradient .portfolio-item .image .overlay .links a.icon i' => 'font-size: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .portfolio.portfolio-grid.hover-zooming-blur .portfolio-item .image .overlay .links a.icon svg, {{WRAPPER}} .portfolio.portfolio-grid.hover-gradient .portfolio-item .image .overlay .links a.icon svg' => 'width: calc({{SIZE}}{{UNIT}}/2); height: calc({{SIZE}}{{UNIT}}/2);',
				],
				'condition' => [
					'icons_show' => 'yes',
				],
			]
		);


		$control->add_control(
			'icons_customize',
			[
				'label' => __('Want to customize?', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'icons_show' => 'yes',
				],
			]
		);

		$control->end_controls_tab();
		$control->end_controls_tabs();

		$control->end_controls_section();
	}

	/**
	 * Caption Style
	 * @access protected
	 */
	protected function caption_style($control) {
		$control->start_controls_section(
			'caption_style',
			[
				'label' => __('Caption Style', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$caption_options = [
			'title' => __('Title', 'thegem'),
			'subtitle' => __('Description', 'thegem'),
			'date' => __('Date', 'thegem'),
			'set' => __('Category', 'thegem'),
		];

		foreach ($caption_options as $ekey => $elem) {

			if ($ekey == 'set') {
				$selector = '{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .wrap .overlay .caption .' . $ekey . ', {{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .wrap .overlay .caption .info .set a';
			} else {
				$selector = '{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .wrap .overlay .caption .' . $ekey;
			}

			$control->add_control(
				$ekey . '_header',
				[
					'label' => $elem,
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			if ($ekey == 'subtitle') {

				$this->add_control(
					'description_preset',
					[
						'label' => 'Description Size Preset',
						'type' => Controls_Manager::SELECT,
						'options' => [
							'default' => __('Default', 'thegem'),
							'title-h1' => __('Title H1', 'thegem'),
							'title-h2' => __('Title H2', 'thegem'),
							'title-h3' => __('Title H3', 'thegem'),
							'title-h4' => __('Title H4', 'thegem'),
							'title-h5' => __('Title H5', 'thegem'),
							'title-h6' => __('Title H6', 'thegem'),
							'title-xlarge' => __('Title xLarge', 'thegem'),
							'styled-subtitle' => __('Styled Subtitle', 'thegem'),
							'main-menu-item' => __('Main Menu', 'thegem'),
							'text-body' => __('Body', 'thegem'),
							'text-body-tiny' => __('Tiny Body', 'thegem'),
						],
						'default' => 'default',
						'condition' => [
							'portfolio_show_description' => 'yes',
						],
					]
				);

				$this->add_control(
					'truncate_description',
					[
						'label' => __('Truncate Description (Lines)', 'thegem'),
						'type' => Controls_Manager::NUMBER,
						'min' => 1,
						'max' => 10,
						'step' => 1,
						'selectors' => [
							'{{WRAPPER}} .portfolio-item .caption .subtitle' => 'max-height: initial !important;',
							'{{WRAPPER}} .portfolio-item .caption .subtitle span' => 'white-space: initial; display: -webkit-box; -webkit-line-clamp: {{VALUE}}; line-clamp: {{VALUE}}; -webkit-box-orient: vertical;',
							'{{WRAPPER}} .portfolio-item .caption .subtitle a, {{WRAPPER}} .portfolio-item .caption .subtitle p' => 'white-space: initial; overflow: initial;',
						],
						'condition' => [
							'portfolio_show_description' => 'yes',
						]
					]
				);

			}

			if ($ekey == 'title') {

				$this->add_control(
					'title_preset',
					[
						'label' => 'Title Size Preset',
						'type' => Controls_Manager::SELECT,
						'options' => [
							'default' => __('Default', 'thegem'),
							'title-h1' => __('Title H1', 'thegem'),
							'title-h2' => __('Title H2', 'thegem'),
							'title-h3' => __('Title H3', 'thegem'),
							'title-h4' => __('Title H4', 'thegem'),
							'title-h5' => __('Title H5', 'thegem'),
							'title-h6' => __('Title H6', 'thegem'),
							'title-xlarge' => __('Title xLarge', 'thegem'),
							'styled-subtitle' => __('Styled Subtitle', 'thegem'),
							'main-menu-item' => __('Main Menu', 'thegem'),
							'text-body' => __('Body', 'thegem'),
							'text-body-tiny' => __('Tiny Body', 'thegem'),
						],
						'default' => 'default',
						'condition' => [
							'portfolio_show_title' => 'yes',
						],
					]
				);

				$this->add_control(
					'title_transform',
					[
						'label' => 'Title Font Transform',
						'type' => Controls_Manager::SELECT,
						'options' => [
							'' => __('Default', 'thegem'),
							'none' => __('None', 'thegem'),
							'lowercase' => __('Lowercase', 'thegem'),
							'uppercase' => __('Uppercase', 'thegem'),
							'capitalize' => __('Capitalize', 'thegem'),
						],
						'default' => '',
						'condition' => [
							'portfolio_show_title' => 'yes',
						],
						'selectors' => [
							'{{WRAPPER}} .portfolio-item .caption .title span' => 'text-transform: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'title_font_weight',
					[
						'label' => __('Title Font weight', 'thegem'),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'' => __('Default', 'thegem'),
							'light' => __('Thin', 'thegem'),
						],
						'default' => '',
						'condition' => [
							'portfolio_show_title' => 'yes',
						],
					]
				);

				$this->add_control(
					'truncate_titles',
					[
						'label' => __('Truncate Title (Lines)', 'thegem'),
						'type' => Controls_Manager::NUMBER,
						'min' => 1,
						'max' => 10,
						'step' => 1,
						'selectors' => [
							'{{WRAPPER}} .portfolio-item .caption .title span' => 'max-height: initial; white-space: initial; display: -webkit-box; -webkit-line-clamp: {{VALUE}}; line-clamp: {{VALUE}}; -webkit-box-orient: vertical;',
						],
						'condition' => [
							'portfolio_show_description' => 'yes',
						]
					]
				);
			}

			if ($ekey == 'date') {


				$this->add_control(
					'truncate_info',
					[
						'label' => __('Truncate Date and Sets (Lines)', 'thegem'),
						'type' => Controls_Manager::NUMBER,
						'min' => 1,
						'max' => 10,
						'step' => 1,
						'selectors' => [
							'{{WRAPPER}} .portfolio-item .caption .info' => 'max-height: initial; white-space: initial; display: -webkit-box; -webkit-line-clamp: {{VALUE}}; line-clamp: {{VALUE}}; -webkit-box-orient: vertical;',
						],
						'condition' => [
							'portfolio_show_description' => 'yes',
						]
					]
				);
			}

			$control->add_group_control(Group_Control_Typography::get_type(),
				[
					'label' => __('Typography', 'thegem'),
					'name' => $ekey . '_typography',
					'selector' => $selector,
					'condition' => [
						'caption_position' => ['hover', 'image'],
					]
				]
			);

			$control->add_control(
				$ekey . '_color',
				[
					'label' => __('Color', 'thegem'),
					'type' => Controls_Manager::COLOR,
					'label_block' => false,
					'selectors' => [
						$selector => 'color: {{VALUE}};',
					],
					'condition' => [
						'caption_position' => ['hover', 'image'],
					]
				]
			);

			if ($ekey == 'set') {

				$this->add_control(
					'category_in_text',
					[
						'label' => __('"in" Text', 'thegem'),
						'type' => Controls_Manager::TEXT,
						'input_type' => 'text',
						'default' => __('in', 'thegem'),
						'condition' => [
							'caption_position' => ['hover'],
						]
					]
				);

				$control->add_control(
					'category_in_color',
					[
						'label' => __('"in" Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .caption .info .set .in_text' => 'color: {{VALUE}};',
						],
						'condition' => [
							'caption_position' => ['hover'],
						]
					]
				);


			}

			$control->start_controls_tabs($ekey . '_tabs', [
				'condition' => [
					'caption_position' => ['page'],
				],
			]);

			if (!empty($control->states_list)) {
				foreach ((array)$control->states_list as $stkey => $stelem) {
					$condition = [];
					$state = '';
					if ($stkey == 'active') {
						continue;
					} else if ($stkey == 'hover') {
						$condition = ['caption_position' => 'page'];
						$state = ':hover';
					}
					if ($ekey == 'set') {
						$selector = '{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item' . $state . ' .caption .info .set a';
					} else {
						$selector = '{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item' . $state . ' .caption .' . $ekey;
					}

					$control->start_controls_tab($ekey . '_tab_' . $stkey, [
						'label' => $stelem,
						'condition' => $condition
					]);

					$control->add_group_control(Group_Control_Typography::get_type(),
						[
							'label' => __('Typography', 'thegem'),
							'name' => $ekey . '_typography_' . $stkey,
							'selector' => $selector,
							'condition' => $condition
						]
					);

					$control->add_control(
						$ekey . '_color_' . $stkey,
						[
							'label' => __('Color', 'thegem'),
							'type' => Controls_Manager::COLOR,
							'label_block' => false,
							'selectors' => [
								$selector => 'color: {{VALUE}};',
							],
							'condition' => $condition
						]
					);

					if ($ekey == 'set' && $stkey == 'normal') {

						$this->add_control(
							'category_in_text_page',
							[
								'label' => __('"in" Text', 'thegem'),
								'type' => Controls_Manager::TEXT,
								'input_type' => 'text',
								'default' => __('in', 'thegem'),
							]
						);

						$control->add_control(
							'category_in_color_page',
							[
								'label' => __('"in" Color', 'thegem'),
								'type' => Controls_Manager::COLOR,
								'label_block' => false,
								'selectors' => [
									'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .caption .info .set .in_text' => 'color: {{VALUE}};',
								],
								'condition' => $condition
							]
						);


					}

					$control->end_controls_tab();

				}
			}

			$control->end_controls_tabs();
		}

		$control->end_controls_section();
	}

	/**
	 * Caption Container Style
	 * @access protected
	 */
	protected function caption_container_style($control) {
		$control->start_controls_section(
			'caption_container_style',
			[
				'label' => __('Caption Container Style', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => ['caption_position' => 'page']
			]
		);

		$control->add_control(
			'caption_container_preset',
			[
				'label' => __('Preset', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'white',
				'options' => [
					'transparent' => __('Transparent', 'thegem'),
					'white' => __('White', 'thegem'),
					'gray ' => __('Gray', 'thegem'),
					'dark' => __('Dark', 'thegem'),
				]
			]
		);

		$control->add_responsive_control(
			'caption_container_radius',
			[
				'label' => __('Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .wrap > .caption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.portfolio-grid.title-on-page .portfolio-item .wrap' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius:{{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_responsive_control(
			'caption_container_padding',
			[
				'label' => __('Padding', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .wrap > .caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_responsive_control(
			'caption_container_alignment',
			[
				'label' => __('Content Alignment', 'thegem'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __('Left', 'thegem'),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __('Centered', 'thegem'),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __('Right', 'thegem'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .wrap > .caption' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .wrap > .caption .caption-separator' => 'margin-{{VALUE}}: 0',
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-likes' => 'text-align: -webkit-{{VALUE}}',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'caption_container_shadow',
				'label' => __('Shadow', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .wrap > .caption',
			]
		);

		$control->start_controls_tabs('caption_container_tabs');

		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {
				if ($stkey == 'active') {
					continue;
				}
				$state = '';
				if ($stkey == 'hover') {
					$state = ':hover';
				}

				$control->start_controls_tab('caption_container_tab_' . $stkey, ['label' => $stelem]);

				$control->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'caption_container_background_' . $stkey,
						'label' => __('Background Type', 'thegem'),
						'types' => ['classic', 'gradient'],
						'selector' => '{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item' . $state . ' .wrap > .caption',
					]
				);
				$control->remove_control('image_hover_overlay_image');

				$control->remove_control('caption_container_background_' . $stkey . '_image');

				$control->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'caption_container_border_' . $stkey,
						'label' => __('Border', 'thegem'),
						'fields_options' => [
							'color' => [
								'default' => '#dfe5e8',
							],
						],
						'selector' => '{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item' . $state . ' .wrap > .caption',
					]
				);

				$control->end_controls_tab();

			}
		}

		$control->end_controls_tabs();

		$control->add_control(
			'spacing_title_header',
			[
				'label' => 'Title',
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'spacing_title',
			[
				'label' => __('Bottom Spacing', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'allowed_dimensions' => ['bottom'],
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .wrap > .caption .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_control(
			'spacing_description_header',
			[
				'label' => 'Description',
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'spacing_description',
			[
				'label' => __('Top and Bottom Spacing', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'allowed_dimensions' => 'vertical',
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .wrap > .caption .subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$control->add_control(
			'spacing_separator_header',
			[
				'label' => 'Separator',
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_responsive_control(
			'spacing_separator_weight',
			[
				'label' => __('Weight', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'rem', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'rem' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .wrap > .caption .caption-separator' => 'height: {{SIZE}}{{UNIT}}',
				]
			]
		);

		$control->start_controls_tabs('spacing_separator_tabs');

		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {
				if ($stkey == 'active') {
					continue;
				}
				$state = '';
				if ($stkey == 'hover') {
					$state = ':hover';
				}

				$control->start_controls_tab('spacing_separator_tab_' . $stkey, ['label' => $stelem]);

				$control->add_control(
					'spacing_separator_color' . $stkey,
					[
						'label' => __('Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item' . $state . ' .wrap > .caption .caption-separator' => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->add_responsive_control(
					'spacing_separator_size' . $stkey,
					[
						'label' => __('Size', 'thegem'),
						'type' => Controls_Manager::SLIDER,
						'size_units' => ['px', '%', 'rem', 'em'],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 300,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
							'rem' => [
								'min' => 0,
								'max' => 100,
							],
							'em' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item' . $state . ' .wrap > .caption .caption-separator' => 'width: {{SIZE}}{{UNIT}}',
						]
					]
				);

				$control->end_controls_tab();

			}
		}

		$control->end_controls_tabs();

		$control->end_controls_section();
	}

	/**
	 * Filter Buttons Style
	 * @access protected
	 */
	protected function filter_buttons_style($control) {

		$control->start_controls_section(
			'filter_buttons_style',
			[
				'label' => __('Filter Buttons Style', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => ['portfolio_show_filter' => 'yes']
			]
		);

		$control->add_control(
			'filter_buttons_position',
			[
				'label' => __('Position', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'left' => __('Left', 'thegem'),
					'right ' => __('Right', 'thegem'),
					'center' => __('Centered', 'thegem'),
				],
				'condition' => ['portfolio_show_sorting' => ''],
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters' => 'text-align: {{VALUE}};',
				],
			]
		);

		$control->add_responsive_control(
			'filter_buttons_radius',
			[
				'label' => __('Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'filter_buttons_border_type',
				'label' => __('Border Type', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters a',
			]
		);

		$control->add_responsive_control(
			'filter_buttons_text_padding',
			[
				'label' => __('Text Padding', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$control->add_responsive_control(
			'filter_buttons_bottom_spacing',
			[
				'label' => __('Bottom Spacing', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'rem', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
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
					'{{WRAPPER}} .portfolio.portfolio-grid .portfilio-top-panel' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				]
			]
		);

		$control->start_controls_tabs('filter_buttons_tabs');

		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {
				$state = '';
				if ($stkey == 'active') {
					$state = '.active';
				} else if ($stkey == 'hover') {
					$state = ':hover';
				}

				$control->start_controls_tab('filter_buttons_tab_' . $stkey, ['label' => $stelem]);

				$control->add_control(
					'filter_buttons_background_color' . $stkey,
					[
						'label' => __('Background Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters a' . $state => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'filter_buttons_border_color' . $stkey,
					[
						'label' => __('Border Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters a' . $state => 'border-color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'filter_buttons_text_color' . $stkey,
					[
						'label' => __('Text Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters a' . $state => 'color: {{VALUE}};',
						],
					]
				);

				$control->add_group_control(Group_Control_Typography::get_type(),
					[
						'label' => __('Typography', 'thegem'),
						'name' => 'filter_buttons_text_typography' . $stkey,
						'selector' => '{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters a' . $state . ' span',
					]
				);

				$control->end_controls_tab();

			}
		}

		$control->end_controls_tabs();

		$this->add_control(
			'show_all_button_text',
			[
				'label' => __('“Show All” Button Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Show All', 'thegem'),
			]
		);


		$control->add_control(
			'filter_responsive_header',
			[
				'label' => __('Filter in responsive mode', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_control(
			'filter_responsive_icon',
			[
				'label' => __('Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
			]
		);

		$control->add_control(
			'filter_responsive_icon_color' . $stkey,
			[
				'label' => __('Icon Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters-resp .menu-toggle i' => 'color: {{VALUE}};',
				],
			]
		);

		$control->add_responsive_control(
			'filter_responsive_icon_size',
			[
				'label' => __('Icon Size', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'rem', 'em'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 300,
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
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters-resp .menu-toggle i' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters-resp .menu-toggle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$control->add_group_control(Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'filter_responsive_typography',
				'selector' => '{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters-resp ul li a',
			]
		);

		$control->add_control(
			'filter_responsive_text_color',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters-resp ul li a' => 'color: {{VALUE}};',
				],
			]
		);

		$control->add_control(
			'filter_responsive_background_color',
			[
				'label' => __('Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters-resp ul li' => 'background-color: {{VALUE}};',
				],
			]
		);

		$control->add_control(
			'filter_responsive_separator_color',
			[
				'label' => __('Separator Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters-resp ul li, {{WRAPPER}} .portfolio.portfolio-grid .portfolio-filters-resp ul' => 'border-color: {{VALUE}};',
				],
			]
		);

		$control->end_controls_section();
	}


	/**
	 * Sorting Style
	 * @access protected
	 */
	protected function sorting_style($control) {

		$control->start_controls_section(
			'sorting_style',
			[
				'label' => __('Sorting Style', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => ['portfolio_show_sorting' => 'yes']
			]
		);

		$control->add_control(
			'switch_background_color',
			[
				'label' => __('Switch Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .sorting-switcher' => 'background-color: {{VALUE}};',
				],
			]
		);

		$control->add_control(
			'switch_color',
			[
				'label' => __('Switch Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .sorting-switcher:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$control->add_control(
			'sorting_separator_color',
			[
				'label' => __('Separator Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-sorting-sep' => 'background-color: {{VALUE}};',
				],
			]
		);

		$control->add_control(
			'sorting_text_color',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-sorting label' => 'color: {{VALUE}};',
				],
			]
		);

		$control->add_group_control(Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'sorting_text_typography',
				'selector' => '{{WRAPPER}} .portfolio.portfolio-grid .portfolio-sorting label',
			]
		);

		$this->add_control(
			'sorting_date_text',
			[
				'label' => __('“Date” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Date', 'thegem'),
			]
		);

		$this->add_control(
			'sorting_name_text',
			[
				'label' => __('“Name” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Name', 'thegem'),
			]
		);

		$this->add_control(
			'sorting_desc_text',
			[
				'label' => __('“Desc” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Desc', 'thegem'),
			]
		);

		$this->add_control(
			'sorting_asc_text',
			[
				'label' => __('“Asc” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Asc', 'thegem'),
			]
		);


		$control->end_controls_section();
	}


	/**
	 * Pagination Style
	 * @access protected
	 */
	protected function pagination_style($control) {

		$control->start_controls_section(
			'pagination_normal_style',
			[
				'label' => __('Pagination Style', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => ['pagination_type' => 'normal']
			]
		);

		$control->add_responsive_control(
			'pagination_spacing',
			[
				'label' => __('Top Spacing', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'rem', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'rem' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .gem-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$control->add_control(
			'pagination_numbers_header',
			[
				'label' => __('Numbers', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_responsive_control(
			'pagination_numbers_radius',
			[
				'label' => __('Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .gem-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_responsive_control(
			'pagination_numbers_padding',
			[
				'label' => __('Padding', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .gem-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$control->start_controls_tabs('pagination_numbers_tabs');

		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {
				$state = '';
				if ($stkey == 'active') {
					$state = '.current';
				} else if ($stkey == 'hover') {
					$state = ':hover';
				}
				$control->start_controls_tab('pagination_numbers_tab_' . $stkey, ['label' => $stelem]);

				$control->add_control(
					'pagination_numbers_background_color' . $stkey,
					[
						'label' => __('Background Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .gem-pagination a' . $state => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagination_numbers_border_type' . $stkey,
						'label' => __('Border', 'thegem'),
						'selector' => '{{WRAPPER}} .gem-pagination a' . $state,
					]
				);

				$control->add_control(
					'pagination_numbers_text_color' . $stkey,
					[
						'label' => __('Text Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .gem-pagination a' . $state => 'color: {{VALUE}};',
						],
					]
				);

				$control->add_group_control(Group_Control_Typography::get_type(),
					[
						'label' => __('Typography', 'thegem'),
						'name' => 'pagination_numbers_text_typography' . $stkey,
						'selector' => '{{WRAPPER}} .gem-pagination a' . $state,
					]
				);

				$control->end_controls_tab();

			}
		}

		$control->end_controls_tabs();

		$control->add_control(
			'pagination_arrows_header',
			[
				'label' => __('Arrows', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_control(
			'pagination_arrows_left_icon',
			[
				'label' => __('Left Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
			]
		);

		$control->add_control(
			'pagination_arrows_right_icon',
			[
				'label' => __('Right Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
			]
		);

		$control->add_responsive_control(
			'pagination_arrows_icon_size',
			[
				'label' => __('Icon Size', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'rem', 'em'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 300,
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
					'{{WRAPPER}} .portfolio.portfolio-grid .gem-pagination .prev i, {{WRAPPER}} .portfolio.portfolio-grid .gem-pagination .next i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};
					font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$control->add_responsive_control(
			'pagination_arrows_radius',
			[
				'label' => __('Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .gem-pagination .prev, {{WRAPPER}} .portfolio.portfolio-grid .gem-pagination .next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_responsive_control(
			'pagination_arrows_padding',
			[
				'label' => __('Padding', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .gem-pagination .prev, {{WRAPPER}} .portfolio.portfolio-grid .gem-pagination .next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$control->start_controls_tabs('pagination_arrows_tabs');

		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {

				if ($stkey == 'active') {
					continue;
				}
				$state = '';
				if ($stkey == 'hover') {
					$state = ':hover';
				}

				$control->start_controls_tab('pagination_arrows_tab_' . $stkey, ['label' => $stelem]);

				$control->add_control(
					'pagination_arrows_background_color' . $stkey,
					[
						'label' => __('Background Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.portfolio-grid .gem-pagination .prev' . $state . ', {{WRAPPER}} .portfolio.portfolio-grid .gem-pagination .next' . $state => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagination_arrows_border_type' . $stkey,
						'label' => __('Border Type', 'thegem'),
						'selector' => '{{WRAPPER}} .portfolio.portfolio-grid .gem-pagination .prev' . $state . ', {{WRAPPER}} .portfolio.portfolio-grid .gem-pagination .next' . $state,
					]
				);

				$control->add_control(
					'pagination_arrows_icon_color' . $stkey,
					[
						'label' => __('Icon Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.portfolio-grid .gem-pagination .prev' . $state . ', {{WRAPPER}} .portfolio.portfolio-grid .gem-pagination .next' . $state => 'color: {{VALUE}};',
						],
					]
				);

				$control->end_controls_tab();

			}
		}

		$control->end_controls_tabs();

		$control->end_controls_section();
	}


	/**
	 * Pagination More Style
	 * @access protected
	 */
	protected function pagination_more_style($control) {

		$control->start_controls_section(
			'pagination_more_style',
			[
				'label' => __('"Load More" Button Style', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => ['pagination_type' => 'more']
			]
		);

		$control->add_responsive_control(
			'pagination_more_spacing',
			[
				'label' => __('Top Spacing', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'rem', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'rem' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-load-more' => 'margin-top: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$control->add_control(
			'pagination_more_button_type',
			[
				'label' => __('Button Type', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'flat',
				'options' => [
					'flat' => __('Flat', 'thegem'),
					'outline' => __('Outline', 'thegem'),
				],
			]
		);

		$control->add_responsive_control(
			'pagination_more_button_size',
			[
				'label' => __('Size', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'small',
				'options' => [
					'small' => __('Small', 'thegem'),
					'medium' => __('Medium', 'thegem'),
					'large' => __('Large', 'thegem'),
				],
			]
		);

		$control->add_responsive_control(
			'pagination_more_button_border_radius',
			[
				'label' => __('Border Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-load-more button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'pagination_more_button_border_type',
				'label' => __('Border Type', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio-load-more button',
			]
		);

		$control->add_responsive_control(
			'pagination_more_button_text_padding',
			[
				'label' => __('Text Padding', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-load-more button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->start_controls_tabs('pagination_more_button_tabs');
		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {

				if ($stkey == 'active') {
					continue;
				}

				$state = '';
				if ($stkey == 'hover') {
					$state = ':hover';
				}

				$control->start_controls_tab('pagination_more_button_tab_' . $stkey, ['label' => $stelem]);

				$control->add_responsive_control(
					'pagination_more_button_text_color_' . $stkey,
					[
						'label' => __('Text Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-load-more button' . $state . ' span' => 'color: {{VALUE}};',
							'{{WRAPPER}} .portfolio-load-more button' . $state . ' i:before' => 'color: {{VALUE}};',
							'{{WRAPPER}} .portfolio-load-more button' . $state . ' svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$control->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => __('Typography', 'thegem'),
						'name' => 'pagination_more_button_typography_' . $stkey,
						'selector' => '{{WRAPPER}} .portfolio-load-more button' . $state . ' span',
						'scheme' => Schemes\Typography::TYPOGRAPHY_1,
					]
				);

				$control->add_responsive_control(
					'pagination_more_button_bg_color_' . $stkey,
					[
						'label' => __('Background Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-load-more button' . $state => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->add_responsive_control(
					'pagination_more_button_border_color_' . $stkey,
					[
						'label' => __('Border Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-load-more button' . $state => 'border-color: {{VALUE}};',
						],
					]
				);

				$control->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'pagination_more_button_shadow_' . $stkey,
						'label' => __('Shadow', 'thegem'),
						'selector' => '{{WRAPPER}} .portfolio-load-more button' . $state,
					]
				);

				$control->end_controls_tab();

			}
		}

		$control->end_controls_tabs();

		$control->add_responsive_control(
			'pagination_more_button_icon_align',
			[
				'label' => __('Icon Alignment', 'thegem'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => __('Left', 'thegem'),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __('Right', 'thegem'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'toggle' => false,
				'selectors_dictionary' => [
					'left' => 'left',
					'right' => 'right',
				],
			]
		);

		$control->add_responsive_control(
			'pagination_more_button_icon_spacing',
			[
				'label' => __('Icon Spacing', 'thegem'),
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
				'selectors' => [
					'{{WRAPPER}} .portfolio-load-more button.gem-button-icon-position-right .gem-button-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .portfolio-load-more button.gem-button-icon-position-left .gem-button-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$control->add_control(
			'pagination_more_button_separator_header',
			[
				'label' => __('Separator', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'more_show_separator' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_more_button_separator_style_active',
			[
				'label' => __('Separator Style', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'gem-button-separator-type-single',
				'options' => [
					'gem-button-separator-type-single' => __('Single', 'thegem'),
					'gem-button-separator-type-square' => __('Square', 'thegem'),
					'gem-button-separator-type-soft-double' => __('Soft Double', 'thegem'),
					'gem-button-separator-type-strong-double' => __('Strong Double', 'thegem'),
				],
				'condition' => [
					'more_show_separator' => 'yes',
				],
			]
		);

		// Size Strong Double & Soft Double & Single
		$control->add_responsive_control(
			'pagination_more_button_separator_size',
			[
				'label' => __('Size', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'condition' => [
					'pagination_more_button_separator_style_active' =>
						[
							'gem-button-separator-type-single',
							'gem-button-separator-type-soft-double',
							'gem-button-separator-type-strong-double',
						],
					'more_show_separator' => 'yes',
				],
				'size_units' => ['%',],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 50,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-holder' => 'width:{{SIZE}}{{UNIT}}; flex-grow: initial;',
				],
			]
		);

		// Size Square
		$control->add_responsive_control(
			'pagination_more_button_separator_size_square',
			[
				'label' => __('Size', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'condition' => [
					'pagination_more_button_separator_style_active' => 'gem-button-separator-type-square',
					'more_show_separator' => 'yes',
				],
				'size_units' => ['px', '%', 'rem', 'em'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 50,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-type-square' => 'padding:0 calc( 50% - {{SIZE}}{{UNIT}} );',
				],
			]
		);

		// Weight Strong Double
		$control->add_responsive_control(
			'pagination_more_button_separator_double_weight',
			[
				'label' => __('Weight', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'condition' => [
					'pagination_more_button_separator_style_active' => 'gem-button-separator-type-strong-double',
					'more_show_separator' => 'yes',
				],
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-holder .gem-button-separator-line' => 'border-width:{{SIZE}}{{UNIT}};',
				],
			]
		);

		// Weight Soft Double
		$control->add_responsive_control(
			'pagination_more_button_separator_soft_weight',
			[
				'label' => __('Weight', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'condition' => [
					'pagination_more_button_separator_style_active' => 'gem-button-separator-type-soft-double',
					'more_show_separator' => 'yes',
				],
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-holder .gem-button-separator-line' => 'border-top:{{SIZE}}{{UNIT}} solid; border-bottom:{{SIZE}}{{UNIT}} solid;',
				],
			]
		);

		// Weight Single
		$control->add_responsive_control(
			'pagination_more_button_separator_single_weight',
			[
				'label' => __('Weight', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'condition' => [
					'pagination_more_button_separator_style_active' => 'gem-button-separator-type-single',
					'more_show_separator' => 'yes',
				],
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-holder .gem-button-separator-line' => 'border-width:{{SIZE}}{{UNIT}};',
				],
			]
		);

		// Height Strong Double & Soft
		$control->add_responsive_control(
			'pagination_more_button_separator_double_height',
			[
				'label' => __('Height', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'condition' => [
					'pagination_more_button_separator_style_active' =>
						[
							'gem-button-separator-type-strong-double',
							'gem-button-separator-type-soft-double',
						],
					'more_show_separator' => 'yes',
				],
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-holder .gem-button-separator-line' => 'height:{{SIZE}}{{UNIT}};',
				],
			]
		);

		// Height Square
		$control->add_responsive_control(
			'pagination_more_button_separator_square_height',
			[
				'label' => __('Height', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'condition' => [
					'pagination_more_button_separator_style_active' => 'gem-button-separator-type-square',
					'more_show_separator' => 'yes',
				],
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-type-square .gem-button-separator-button' => 'margin: {{SIZE}}{{UNIT}} 0;',
				],
			]
		);

		// Color
		$control->add_control(
			'pagination_more_button_color_border',
			[
				'label' => __('Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'more_show_separator' => 'yes',
				],
				'default' => '#b6c6c9',
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-line' => 'border-color:{{VALUE}}; color:{{VALUE}};',
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-type-square svg line' => 'stroke:{{VALUE}};',
				],
			]
		);

		$control->end_controls_section();
	}


	/**
	 * Likes Style
	 * @access protected
	 */
	protected function likes_style($control) {

		$control->start_controls_section(
			'likes_style',
			[
				'label' => __('Likes Style', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'caption_position' => 'page',
				],
			]
		);

		$control->add_control(
			'likes_icon',
			[
				'label' => __('Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
			]
		);

		$control->start_controls_tabs('likes_tabs');
		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {

				if ($stkey == 'active') {
					continue;
				}

				$state = '';
				if ($stkey == 'hover') {
					$state = ':hover';
				}

				$control->start_controls_tab('likes_tab_' . $stkey, ['label' => $stelem]);

				$control->add_control(
					'likes_icon_color_' . $stkey,
					[
						'label' => __('Icon Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-likes .zilla-likes' . $state . ' i' => 'color: {{VALUE}}; transition: all 0.3s;',
						]
					]
				);

				$control->add_control(
					'likes_text_color_' . $stkey,
					[
						'label' => __('Text Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-likes .zilla-likes' . $state => 'color: {{VALUE}};',
						]
					]
				);

				$control->add_group_control(Group_Control_Typography::get_type(),
					[
						'label' => __('Typography', 'thegem'),
						'name' => 'likes_typography_' . $stkey,
						'selector' => '{{WRAPPER}} .portfolio-likes .zilla-likes' . $state,
					]
				);


				$control->end_controls_tab();

			}
		}


		$control->end_controls_section();
	}


	/**
	 * Hover Icons (Custom)
	 * @access protected
	 */
	protected function hover_icons_style($control) {

		$control->start_controls_section(
			'hover_icons_style',
			[
				'label' => __('Hover Icons (Custom) ', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'icons_customize' => 'yes',
				]
			]
		);

		$this->add_control(
			'important_note',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __('Hover icons depend on types of portfolio item (image, link, video etc.). If some type is not selected, corresponding icon will not appear. ', 'thegem'),
				'content_classes' => 'elementor-control-field-description',
			]
		);

		$icons_list = [
			'share' => __('Sharing Button', 'thegem'),
			'self-link' => __('Portfolio Page', 'thegem'),
			'full-image' => __('Fancybox Image', 'thegem'),
			'inner-link' => __('Internal Link', 'thegem'),
			'outer-link' => __('External Link', 'thegem'),
			'video' => __('Video', 'thegem')
		];

		foreach ($icons_list as $ekey => $elem) {

			$condition = [];

			$add_selector = '';
			if ($ekey == 'share') {
				$condition = [
					'social_sharing' => 'yes'
				];
				$add_selector = ', {{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .image .overlay .links .socials-item-icon';
			}

			$control->add_control(
				'hover_icon_header_' . $ekey,
				[
					'label' => $elem,
					'type' => Controls_Manager::HEADING,
					'condition' => $condition
				]
			);

			$control->add_control(
				'hover_icon_' . $ekey,
				[
					'label' => __('Icon', 'thegem'),
					'type' => Controls_Manager::ICONS,
					'condition' => $condition
				]
			);

			$control->add_control(
				'hover_icon_color_' . $ekey,
				[
					'label' => __('Icon Color', 'thegem'),
					'type' => Controls_Manager::COLOR,
					'label_block' => false,
					'selectors' => [
						'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .image .overlay .links a.' . $ekey . ' i' . $add_selector => 'color: {{VALUE}};',
						'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .image .overlay .links a.' . $ekey . ' svg' . $add_selector => 'fill: {{VALUE}};',
					],
					'condition' => $condition
				]
			);

			$control->add_control(
				'hover_icon_rotate_' . $ekey,
				[
					'label' => __('Rotate', 'thegem'),
					'type' => Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 360,
							'step' => 15,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .image .overlay .links a.' . $ekey . ' i' => 'transform: rotate({{SIZE}}deg); -webkit-transform: rotate({{SIZE}}deg);',
						'{{WRAPPER}} .portfolio.portfolio-grid .portfolio-item .image .overlay .links a.' . $ekey . ' svg' => 'transform: rotate({{SIZE}}deg); -webkit-transform: rotate({{SIZE}}deg);',
					],
					'condition' => $condition
				]
			);

			$control->end_controls_tab();

		}

		$control->end_controls_section();
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
		$grid_uid = $this->get_id();

		if ($settings['layout'] == 'creative') {
			$settings['columns'] = $settings['columns_desktop'];
			$settings['columns_100'] = $settings['columns_100_creative'];
			$settings['ignore_highlights'] = 'yes';
		}

		if ($settings['disable_preloader'] == 'yes') {
			$settings['ignore_highlights'] = 'yes';
			$settings['skeleton_loader'] = '';
		}

		if ($settings['portfolio_show_sorting'] == 'yes') {
			$settings['orderby'] = 'date';
		} else if (isset($settings['order_by']) && $settings['order_by'] != 'default') {
			$settings['orderby'] = $settings['order_by'];
		} else {
			$settings['orderby'] = 'menu_order ID';
		}

		if ($settings['portfolio_show_sorting'] == 'yes') {
			$settings['order'] = 'DESC';
		} else if (!isset($settings['order']) || $settings['order'] == 'default') {
			$settings['order'] = 'ASC';
		}

		$localize = array(
			'data' => $settings,
			'action' => 'portfolio_grid_load_more',
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('portfolio_ajax-nonce')
		);
		wp_localize_script('thegem-portfolio-grid-extended', 'thegem_portfolio_ajax_' . $grid_uid, $localize);
		$settings['action'] = 'portfolio_grid_load_more';

		$terms = $settings['content_portfolios_cat'];

		$categories_filter = null;
		if (isset($_GET[$grid_uid . '-category'])) {
			$active_cat = $_GET[$grid_uid . '-category'];
			$categories_current = [$active_cat];
			$categories_filter = $active_cat;
		} else {
			$active_cat = 'all';
			$categories_current = $terms;
		}

		if ($settings['loading_animation'] === 'yes') {
			wp_enqueue_style('thegem-animations');
			wp_enqueue_script('thegem-items-animations');
			wp_enqueue_script('thegem-scroll-monitor');
		}

		if ($settings['caption_position'] == 'image') {
			$hover_effect = $settings['image_hover_effect_image'];
		} else if ($settings['caption_position'] == 'page') {
			$hover_effect = $settings['image_hover_effect_page'];
		} else {
			$hover_effect = $settings['image_hover_effect_hover'];
		}

		wp_enqueue_style('thegem-hovers-' . $hover_effect);

		if ($settings['pagination_type'] == 'more') {
			wp_enqueue_style('thegem-button');
		} else if ($settings['pagination_type'] == 'scroll') {
			wp_enqueue_script('thegem-scroll-monitor');
		}

		if ($settings['layout'] !== 'creative' && ($settings['layout'] !== 'justified' || $settings['ignore_highlights'] !== 'yes')) {

			if ($settings['layout'] == 'metro') {
				wp_enqueue_script('thegem-isotope-metro');
			} else {
				wp_enqueue_script('thegem-isotope-masonry-custom');
			}
		}

		if ($settings['portfolio_show_filter'] == 'yes') {
			wp_enqueue_script('jquery-dlmenu');
		}

		$items_per_page = intval($settings['items_per_page']) ? intval($settings['items_per_page']) : 8;

		$page = 1;
		$next_page = 0;

		if (isset($_GET[$grid_uid . '-page'])) {
			$page = $_GET[$grid_uid . '-page'];
		}

		$portfolio_loop = thegem_get_portfolio_posts($categories_current, $page, $items_per_page, $settings['orderby'], $settings['order'], $settings['offset'], $settings['exclude_portfolios']);

		$portfolio_title = '';
		global $post;
		$portfolio_posttemp = $post;

		if ($portfolio_loop && $portfolio_loop->have_posts()) :

			$max_page = ceil(($portfolio_loop->found_posts - intval($settings['offset'])) / $items_per_page);

			if ($max_page > $page)
				$next_page = $page + 1;
			else
				$next_page = 0;

			if (in_array('0', $terms)) {
				$terms = get_terms('thegem_portfolios');
			} else {
				foreach ($terms as $key => $term) {
					$terms[$key] = get_term_by('slug', $term, 'thegem_portfolios');
					if (!$terms[$key]) {
						unset($terms[$key]);
					}
				}
			}

			$terms = apply_filters('portfolio_terms_filter', $terms);

			usort($terms, 'portolios_cmp');
			$thegem_terms_set = array();
			foreach ($terms as $term) {
				$thegem_terms_set[$term->slug] = $term;
			}

			$item_classes = get_thegem_portfolio_render_item_classes($settings);
			$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($settings);

			if ($settings['columns'] == '100%' || (($settings['ignore_highlights'] !== 'yes' || in_array($settings['layout'], ['masonry', 'metro'])) && $settings['skeleton_loader'] !== 'yes')) {
				echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="preloader-spin"></div></div>');
			} else if ($settings['skeleton_loader'] == 'yes') { ?>
				<div class="preloader save-space">
					<div class="skeleton">
						<div class="skeleton-posts portfolio-row">
							<?php for ($x = 0; $x < $portfolio_loop->post_count; $x++) {
								echo thegem_portfolio_grid_render_item($settings, $item_classes);
							} ?>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="portfolio-preloader-wrapper">
				<?php if ($portfolio_title): ?>
					<h3 class="title portfolio-title"><?php echo $portfolio_title; ?></h3>
				<?php endif; ?>

				<?php
				if ($settings['caption_position'] == 'hover') {
					$title_on = 'hover';
				} else {
					$title_on = 'page';
				}
				$this->add_render_attribute(
					'portfolio-wrap',
					[
						'class' => [
							'portfolio portfolio-grid no-padding',
							'portfolio-pagination-' . $settings['pagination_type'],
							'portfolio-style-' . $settings['layout'],
							'background-style-' . $settings['caption_container_preset'],
							'hover-' . $hover_effect,
							'title-on-' . $title_on,
							'caption-position-' . $settings['caption_position'],
							'hover-elements-size-' . $settings['hover_elements_size'],
							($settings['loading_animation'] == 'yes' ? 'loading-animation' : ''),
							($settings['loading_animation'] == 'yes' && $settings['animation_effect'] ? 'item-animation-' . $settings['animation_effect'] : ''),
							($settings['loading_animation'] == 'yes' && $settings['loading_animation_mobile'] == 'yes' ? 'enable-animation-mobile' : ''),
							($settings['image_gaps']['size'] == 0 ? 'no-gaps' : ''),
							($settings['shadowed_container'] == 'yes' ? 'shadowed-container' : ''),
							($settings['columns'] == '100%' ? 'fullwidth-columns fullwidth-columns-' . $settings['columns_100'] : ''),
							($settings['caption_position'] == 'image' && $settings['image_hover_effect_image'] == 'gradient' ? 'hover-gradient-title' : ''),
							($settings['caption_position'] == 'image' && $settings['image_hover_effect_image'] == 'circular' ? 'hover-circular-title' : ''),
							($settings['caption_position'] == 'hover' || $settings['caption_position'] == 'image' ? 'hover-title' : ''),
							($settings['layout'] == 'masonry' && $settings['columns'] != '1x' ? 'portfolio-items-masonry' : ''),
							($settings['columns'] != '100%' ? 'columns-' . str_replace("x", "", $settings['columns']) : ''),
							(isset($settings['columns_tablet']) ? 'columns-tablet-' . str_replace("x", "", $settings['columns_tablet']) : ''),
							(isset($settings['columns_mobile']) ? 'columns-mobile-' . str_replace("x", "", $settings['columns_mobile']) : ''),
							($settings['layout'] == 'creative' || ($settings['layout'] == 'justified' && $settings['ignore_highlights'] == 'yes') ? 'disable-isotope' : ''),
							($settings['next_page_preloading'] == 'yes' && $settings['show_pagination'] === 'yes' ? 'next-page-preloading' : ''),
							($settings['filters_preloading'] == 'yes' ? 'filters-preloading' : ''),
							($settings['layout'] == 'creative' && $settings['scheme_apply_mobiles'] !== 'yes' ? 'creative-disable-mobile' : ''),
							($settings['layout'] == 'creative' && $settings['scheme_apply_tablets'] !== 'yes' ? 'creative-disable-tablet' : ''),
						],
						'data-portfolio-uid' => esc_attr($grid_uid),
						'data-current-page' => esc_attr($page),
						'data-per-page' => esc_attr($items_per_page),
						'data-next-page' => esc_attr($next_page),
						'data-pages-count' => esc_attr($max_page),
						'data-hover' => esc_attr($hover_effect),
						'data-portfolio-filter' => esc_attr($categories_filter),
					]
				);

				?>

				<div <?php echo $this->get_render_attribute_string('portfolio-wrap'); ?>>
					<?php if (($settings['portfolio_show_filter'] == 'yes' && count($terms) > 0) || $settings['portfolio_show_sorting'] == 'yes'): ?>
						<div class="portfilio-top-panel<?php if ($settings['columns'] == '100%'): ?> fullwidth-block<?php endif; ?>">
							<div class="portfilio-top-panel-row">
								<div class="portfilio-top-panel-left">
									<?php if ($settings['portfolio_show_filter'] == 'yes' && count($terms) > 0): ?>
										<div class="portfolio-filters">
											<a href="#" data-filter="*"
											   class="<?php echo $active_cat == 'all' ? 'active' : ''; ?> all title-h6"><span
														class="light"><?php echo $settings['show_all_button_text']; ?></span></a>
											<?php foreach ($terms as $term) : ?>
												<a href="#" data-filter=".<?php echo $term->slug; ?>"
												   class="<?php echo $active_cat == $term->slug ? 'active' : ''; ?> title-h6"><?php if (get_option('portfoliosets_' . $term->term_id . '_icon_pack') && get_option('portfoliosets_' . $term->term_id . '_icon')) {
														echo thegem_build_icon(get_option('portfoliosets_' . $term->term_id . '_icon_pack'), get_option('portfoliosets_' . $term->term_id . '_icon'));
													} ?><span class="light"><?php echo $term->name; ?></span></a>
											<?php endforeach; ?>
										</div>
										<div class="portfolio-filters-resp">
											<button class="menu-toggle dl-trigger"><?php _e('Portfolio filters', 'thegem'); ?>
												<?php if ($settings['filter_responsive_icon'] && $settings['filter_responsive_icon']['value']) {
													Icons_Manager::render_icon($settings['filter_responsive_icon'], ['aria-hidden' => 'true']);
												} else { ?>
													<span class="menu-line-1"></span>
													<span class="menu-line-2"></span>
													<span class="menu-line-3"></span>
												<?php } ?>
											</button>
											<ul class="dl-menu">
												<li><a href="#"
													   data-filter="*"><?php echo $settings['show_all_button_text']; ?></a>
												</li>
												<?php foreach ($terms as $term) : ?>
													<li><a href="#"
														   data-filter=".<?php echo esc_attr($term->slug); ?>"><?php echo $term->name; ?></a>
													</li>
												<?php endforeach; ?>
											</ul>
										</div>
									<?php endif; ?>
								</div>
								<div class="portfilio-top-panel-right">
									<?php if ($settings['portfolio_show_sorting'] == 'yes'): ?>
										<div class="portfolio-sorting title-h6">
											<div class="orderby light">
												<label for=""
													   data-value="date"><?php echo $settings['sorting_date_text']; ?></label>
												<a href="javascript:void(0);" class="sorting-switcher"
												   data-current="date"></a>
												<label for=""
													   data-value="title"><?php echo $settings['sorting_name_text']; ?></label>
											</div>
											<div class="portfolio-sorting-sep"></div>
											<div class="order light">
												<label for=""
													   data-value="DESC"><?php echo $settings['sorting_desc_text']; ?></label>
												<a href="javascript:void(0);" class="sorting-switcher"
												   data-current="DESC"></a>
												<label for=""
													   data-value="ASC"><?php echo $settings['sorting_asc_text']; ?></label>
											</div>
										</div>

									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<div class="portfolio-row-outer <?php if ($settings['columns'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
						<div class="row portfolio-row">
							<div class="portfolio-set clearfix"
								 data-max-row-height="<?php echo $settings['metro_max_row_height'] ? $settings['metro_max_row_height']['size'] : ''; ?>">
								<?php
								if ($settings['layout'] == 'creative') {
									$creative_blog_schemes_list = [
										'6' => [
											'6a' => [
												'count' => 9,
												0 => 'squared',
											],
											'6b' => [
												'count' => 7,
												0 => 'squared',
												1 => 'horizontal',
												6 => 'horizontal',
											],
											'6c' => [
												'count' => 9,
												0 => 'horizontal',
												3 => 'horizontal',
												6 => 'horizontal',
											],
											'6d' => [
												'count' => 9,
												0 => 'horizontal',
												1 => 'horizontal',
												2 => 'horizontal',
											],
											'6e' => [
												'count' => 6,
												0 => 'squared',
												1 => 'squared',
											]
										],
										'5' => [
											'5a' => [
												'count' => 7,
												0 => 'squared',
											],
											'5b' => [
												'count' => 8,
												0 => 'horizontal',
												4 => 'horizontal',
											],
											'5c' => [
												'count' => 6,
												0 => 'horizontal',
												1 => 'horizontal',
												4 => 'horizontal',
												5 => 'horizontal',
											],
											'5d' => [
												'count' => 4,
												0 => 'squared',
												1 => 'vertical',
												2 => 'horizontal',
												3 => 'horizontal',
											]
										],
										'4' => [
											'4a' => [
												'count' => 5,
												0 => 'squared',
											],
											'4b' => [
												'count' => 4,
												0 => 'squared',
												1 => 'horizontal',
											],
											'4c' => [
												'count' => 4,
												0 => 'squared',
												1 => 'vertical',
											],
											'4d' => [
												'count' => 7,
												0 => 'vertical',
											],
											'4e' => [
												'count' => 4,
												0 => 'vertical',
												1 => 'vertical',
												2 => 'horizontal',
												3 => 'horizontal',
											],
											'4f' => [
												'count' => 6,
												0 => 'horizontal',
												5 => 'horizontal',
											]
										],
										'3' => [
											'3a' => [
												'count' => 4,
												0 => 'vertical',
												1 => 'vertical',
											],
											'3b' => [
												'count' => 4,
												1 => 'horizontal',
												2 => 'horizontal',
											],
											'3c' => [
												'count' => 5,
												0 => 'vertical',
											],
											'3d' => [
												'count' => 5,
												0 => 'horizontal',
											],
											'3e' => [
												'count' => 3,
												0 => 'squared',
											],
											'3f' => [
												'count' => 4,
												0 => 'horizontal',
												1 => 'vertical',
											],
											'3g' => [
												'count' => 4,
												0 => 'vertical',
												3 => 'horizontal',
											],
											'3h' => [
												'count' => 5,
												2 => 'vertical',
											]
										],
										'2' => [
											'2a' => [
												'count' => 5,
												0 => 'vertical',
											],
											'2b' => [
												'count' => 5,
												3 => 'vertical',
											],
											'2c' => [
												'count' => 4,
												0 => 'vertical',
												2 => 'vertical',
											],
											'2d' => [
												'count' => 4,
												0 => 'horizontal',
												1 => 'vertical',
											],
											'2e' => [
												'count' => 5,
												0 => 'horizontal',
											],
											'2f' => [
												'count' => 4,
												0 => 'horizontal',
												1 => 'horizontal',
											],
											'2g' => [
												'count' => 5,
												2 => 'horizontal',
											],
											'2h' => [
												'count' => 4,
												0 => 'horizontal',
												3 => 'horizontal',
											],
										]
									];
									$columns = $settings['columns'] != '100%' ? str_replace("x", "", $settings['columns']) : $settings['columns_100'];
									$items_sizes = $creative_blog_schemes_list[$columns][$settings['layout_scheme_' . $columns . 'x']];
									$items_count = $items_sizes['count'];
								}
								$i = 0;
								while ($portfolio_loop->have_posts()) : $portfolio_loop->the_post();
									$thegem_highlight_type_creative = null;
									if ($settings['layout'] == 'creative') {
										$thegem_highlight_type_creative = 'disabled';
										$item_num = $i % $items_count;
										if (isset($items_sizes[$item_num])) {
											$thegem_highlight_type_creative = $items_sizes[$item_num];
										}
									}
									echo thegem_portfolio_grid_render_item($settings, $item_classes, $thegem_sizes, get_the_ID(), $thegem_highlight_type_creative);
									if ($settings['layout'] == 'creative' && $i == 0) {
										echo thegem_portfolio_grid_render_item($settings, ['size-item'], $thegem_sizes);
									}
									$i++;
								endwhile; ?>

							</div><!-- .portflio-set -->
							<?php if ($settings['layout'] != 'creative'): ?>
								<div class="portfolio-item-size-container">
									<?php echo thegem_portfolio_grid_render_item($settings, $item_classes, $thegem_sizes); ?>
								</div>
							<?php endif; ?>
						</div><!-- .row-->
						<?php

						/** Pagination */

						if ('yes' === ($settings['show_pagination'])) : ?>
							<?php if ($settings['pagination_type'] == 'normal'): ?>
								<div class="portfolio-navigator gem-pagination">
									<a href="#" class="prev">
										<?php if ($settings['pagination_arrows_left_icon']['value']) {
											Icons_Manager::render_icon($settings['pagination_arrows_left_icon'], ['aria-hidden' => 'true']);
										} else { ?>
											<i class="default"></i>
										<?php } ?>
									</a>
									<div class="pages"></div>
									<a href="#" class="next">
										<?php if ($settings['pagination_arrows_right_icon']['value']) {
											Icons_Manager::render_icon($settings['pagination_arrows_right_icon'], ['aria-hidden' => 'true']);
										} else { ?>
											<i class="default"></i>
										<?php } ?>
									</a>
								</div>
							<?php endif; ?>
							<?php
							if ($settings['pagination_type'] == 'more' && $next_page > 0):

								$separator_enabled = !empty($settings['more_show_separator']) ? true : false;

								// Container
								$classes_container = 'gem-button-container gem-widget-button ';

								if ($separator_enabled) {
									$classes_container .= 'gem-button-position-center gem-button-with-separator ';
								} else {
									if ('yes' === $settings['more_stretch_full_width']) {
										$classes_container .= 'gem-button-position-fullwidth ';
									}
								}
								$attr_container = [
									'class' => $classes_container,
								];
								$this->add_render_attribute('attr_container', $attr_container);

								// Separator
								$classes_separator = 'gem-button-separator ';

								if (!empty($settings['pagination_more_button_separator_style_active'])) {

									$classes_separator .= esc_attr($settings['pagination_more_button_separator_style_active']);
								}
								$attr_separator = [

									'class' => $classes_separator,
								];

								$this->add_render_attribute('attr_separator', $attr_separator);

								// Link

								$this->add_render_attribute(
									'button-wrap',
									[
										'class' => [
											'load-more-button gem-button',
											'gem-button-size-' . $settings['pagination_more_button_size'],
											'gem-button-style-' . $settings['pagination_more_button_type'],
											'gem-button-icon-position-' . $settings['pagination_more_button_icon_align'],
											'gem-button-text-weight-normal',
										],
									]
								);
								?>

								<div class="portfolio-load-more">
									<div class="inner">
										<?php include(locate_template(array('gem-templates/portfolio/more-button.php'))); ?>
									</div>
								</div>
							<?php endif; ?>
							<?php if ($settings['pagination_type'] == 'scroll' && $next_page > 0): ?>
								<div class="portfolio-scroll-pagination"></div>
							<?php endif; ?>
						<?php endif; ?>
					</div><!-- .full-width -->
				</div><!-- .portfolio-->
			</div><!-- .portfolio-preloader-wrapper-->
		<?php

		else: ?>
			<div class="bordered-box centered-box styled-subtitle">
				<?php echo __('Please select portfolios in "Portfolios" section', 'thegem') ?>
			</div>
		<?php endif;
		$post = $portfolio_posttemp;
		wp_reset_postdata();

		if (is_admin() && Plugin::$instance->editor->is_edit_mode()): ?>
			<script>
				if (typeof widget_settings == 'undefined') {
					var widget_settings = [];
				}
				widget_settings['<?php echo $grid_uid ?>'] = JSON.parse('<?php echo json_encode($settings) ?>');
			</script>
			<script type="text/javascript">
				(function ($) {

					setTimeout(function () {
						if (!$('.elementor-element-<?php echo $this->get_id(); ?> .portfolio-grid').length) {
							return;
						}
						$('.elementor-element-<?php echo $this->get_id(); ?> .portfolio-grid').initExtendedProductsGrids();
					}, 1000);

					elementor.channels.editor.on('change', function (view) {
						var changed = view.elementSettingsModel.changed;

						if (changed.image_gaps !== undefined || changed.caption_container_padding !== undefined || changed.spacing_title !== undefined || changed.spacing_description !== undefined) {
							setTimeout(function () {
								$('.elementor-element-<?php echo $this->get_id(); ?> .portfolio-grid').initExtendedProductsGrids();
							}, 500);
						}
					});

				})(jQuery);

			</script>
		<?php endif;
	}
}

\Elementor\Plugin::instance()->widgets_manager->register(new TheGem_Portfolio());