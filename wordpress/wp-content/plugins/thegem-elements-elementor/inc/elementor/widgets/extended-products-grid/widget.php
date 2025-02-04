<?php

namespace TheGem_Elementor\Widgets\ExtendedProductsGrid;

use Elementor\Controls_Manager;
use TheGem_Elementor\Group_Control_Background_Light;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) exit;

/**
 * Elementor widget for Products Grid.
 */
class TheGem_ExtendedProductsGrid extends Widget_Base {

	public function __construct($data = [], $args = null) {

		$template_type = $GLOBALS['thegem_template_type'] ?? thegem_get_template_type(get_the_ID());
		$this->is_product_single = $template_type === 'single-product' || (empty($template_type) && is_singular('product'));
		$this->is_product_cart = $template_type === 'cart' || (empty($template_type) && get_the_ID() == get_option('woocommerce_cart_page_id'));
		$this->is_product_archive = $template_type === 'product-archive' || (empty($template_type) && (is_tax('product_cat') || is_tax('product_tag') || is_post_type_archive('product')));

		if (isset($data['settings']) && (empty($_REQUEST['action']) || !in_array($_REQUEST['action'], array('thegem_importer_process', 'thegem_templates_new')))) {

			if (!isset($data['settings']['source_type'])) {
				if ($this->is_product_single) {
					$data['settings']['source_type'] = 'related_upsell';
				} else if ($this->is_product_cart) {
					$data['settings']['source_type'] = 'cross_sell';
				} else if ($this->is_product_archive) {
					$data['settings']['source_type'] = 'archive';
				} else {
					$data['settings']['source_type'] = 'custom';
				}
			}

			if (!isset($data['settings']['skeleton_loader'])) {
				if ($this->is_product_archive) {
					$data['settings']['skeleton_loader'] = '';
				} else {
					$data['settings']['skeleton_loader'] = 'yes';
				}
			}

			if (!isset($data['settings']['ignore_highlights'])) {
				if ($this->is_product_archive) {
					$data['settings']['ignore_highlights'] = 'yes';
				} else {
					$data['settings']['ignore_highlights'] = '';
				}
			}

			if (!isset($data['settings']['filters_scroll_top'])) {
				if ($this->is_product_archive) {
					$data['settings']['filters_scroll_top'] = 'yes';
				} else {
					$data['settings']['filters_scroll_top'] = '';
				}
			}
		}

		parent::__construct($data, $args);

		$localize = array(
			'action' => 'extended_products_grid_load_more',
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('extended_products_grid_ajax-nonce')
		);
		wp_localize_script('thegem-portfolio-grid-extended', 'thegem_portfolio_ajax', $localize);

		$this->states_list = [
			'normal' => __('Normal', 'thegem'),
			'hover' => __('Hover', 'thegem'),
			'active' => __('Active', 'thegem'),
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
		return 'thegem-extended-products-grid';
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
		return defined( 'WC_PLUGIN_FILE' );
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __('Products Grid', 'thegem');
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'thegem-eicon thegem-eicon-products-grid';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		if (get_post_type() === 'thegem_templates' && thegem_get_template_type(get_the_id()) === 'single-product') {
			return ['thegem_single_product_builder'];
		}
		if (get_post_type() === 'thegem_templates' && thegem_get_template_type(get_the_id()) === 'product-archive') {
			return ['thegem_product_archive_builder'];
		}
		if (get_post_type() === 'thegem_templates' && thegem_get_template_type(get_the_id()) === 'cart') {
			return ['thegem_cart_builder'];
		}
		return ['thegem_woocommerce'];
	}

	public function get_style_depends() {
		if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
			return [
				'thegem-button',
				'thegem-hovers-default',
				'thegem-hovers-zooming-blur',
				'thegem-hovers-horizontal-sliding',
				'thegem-hovers-vertical-sliding',
				'thegem-hovers-gradient',
				'thegem-hovers-circular',
				'thegem-hovers-slide',
				'thegem-hovers-fade',
				'thegem-hovers-list-slide',
				'thegem-hovers-list-fade',
				'thegem-portfolio-products-extended'];
		}
		return ['thegem-portfolio-products-extended'];
	}

	public function get_script_depends() {
		if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
			return [
				'jquery-dlmenu',
				'wc-jquery-ui-touchpunch',
				'thegem-animations',
				'thegem-items-animations',
				'thegem-scroll-monitor',
				'thegem-isotope-metro',
				'thegem-isotope-masonry-custom',
				'wc-single-product',
				'wc-add-to-cart-variation',
				'thegem-product-quick-view',
				'thegem-woocommerce',
				'thegem-portfolio-grid-extended'];
		}
		return ['thegem-woocommerce','thegem-portfolio-grid-extended'];
	}

	/* Show reload button */
	public function is_reload_preview_required() {
		return true;
	}

	/**
	 * Create presets options for Select
	 *
	 * @access protected
	 * @return array
	 */
	protected function get_presets_options() {
		$out = array_merge(
			[
				'below-default-cart-button' => [
					'label' => __('Default, Cart Button', 'thegem'),
					'group' => 'caption-below',
				],
				'below-default-cart-icon' => [
					'label' => __('Default, Cart Icon', 'thegem'),
					'group' => 'caption-below',
				],
				'below-cart-disabled' => [
					'label' => __('Solid, Cart Disabled', 'thegem'),
					'group' => 'caption-below',
				],
				'below-border-cart-icon' => [
					'label' => __('Border, Cart Icon', 'thegem'),
					'group' => 'caption-below',
				],
				'below-shadow-hover-01' => [
					'label' => __('Shadow Hover 01', 'thegem'),
					'group' => 'caption-below',
				],
				'below-shadow-hover-02' => [
					'label' => __('Shadow Hover 02', 'thegem'),
					'group' => 'caption-below',
				],
				'below-rounded-images' => [
					'label' => __('Rounded Images', 'thegem'),
					'group' => 'caption-below',
				],
				'below-rectangle-button-01' => [
					'label' => __('Rectangle Button 01', 'thegem'),
					'group' => 'caption-below',
				],
				'below-rectangle-button-02' => [
					'label' => __('Rectangle Button 02', 'thegem'),
					'group' => 'caption-below',
				],
				'below-separator-01' => [
					'label' => __('Product Separator 01', 'thegem'),
					'group' => 'caption-below',
				],
				'below-separator-02' => [
					'label' => __('Product Separator 02', 'thegem'),
					'group' => 'caption-below',
				],
				'image-default-cart-button' => [
					'label' => __('Default, Cart Button', 'thegem'),
					'group' => 'caption-image',
				],
				'image-default-cart-icon' => [
					'label' => __('Default, Cart Icon', 'thegem'),
					'group' => 'caption-image',
				],
				'image-solid-background' => [
					'label' => __('Solid Caption Background', 'thegem'),
					'group' => 'caption-image',
				],
				'image-rounded-corners' => [
					'label' => __('Rounded Corners', 'thegem'),
					'group' => 'caption-image',
				],
				'image-shadow-hover-01' => [
					'label' => __('Shadow Hover 01', 'thegem'),
					'group' => 'caption-image',
				],
				'image-shadow' => [
					'label' => __('Shadow', 'thegem'),
					'group' => 'caption-image',
				],
				'image-separator-01' => [
					'label' => __('Product Separator 01', 'thegem'),
					'group' => 'caption-image',
				],
				'image-separator-02' => [
					'label' => __('Product Separator 02', 'thegem'),
					'group' => 'caption-image',
				],
				'hover-default' => [
					'label' => __('Default', 'thegem'),
					'group' => 'caption-hover',
				],
				'hover-rounded-corners' => [
					'label' => __('Rounded Corners', 'thegem'),
					'group' => 'caption-hover',
				],
				'hover-solid-background' => [
					'label' => __('Solid Caption Background', 'thegem'),
					'group' => 'caption-hover',
				],
				'hover-separator' => [
					'label' => __('Product Separator', 'thegem'),
					'group' => 'caption-hover',
				],
				'hover-centered-caption' => [
					'label' => __('Centered Caption', 'thegem'),
					'group' => 'caption-hover',
				],
				'hover-shadow-hover' => [
					'label' => __('Shadow Hover', 'thegem'),
					'group' => 'caption-hover',
				],
				'hover-gradient-hover' => [
					'label' => __('Gradient Hover', 'thegem'),
					'group' => 'caption-hover',
				],
			]
		);
		return $out;
	}

	private function get_options_by_groups($skins, $group = false) {
		$group_labels = [
			'caption-below' => __('Caption Below', 'thegem'),
			'caption-image' => __('Caption on Image', 'thegem'),
			'caption-hover' => __('Caption on Hover', 'thegem'),
		];
		foreach ($skins as $key => $skin) {
			if (!isset($groups[$skin['group']])) {
				$groups[$skin['group']] = [
					'label' => $group_labels[$skin['group']],
					'options' => [],
				];
			}
			$groups[$skin['group']]['options'][$key] = $skin['label'];
		}

		if ($group && isset($groups[$group])) {
			return $groups[$group];
		}
		return $groups;
	}

	/**
	 * Get default presets options for Select
	 *
	 * @param int $index
	 *
	 * @access protected
	 * @return string
	 */
	protected function set_default_presets_options() {
		return 'below-default-cart-button';
	}

	/**
	 * Make options select products sets
	 * @access protected
	 * @return array
	 */
	protected function select_products_sets($exclude_all = false) {
		$out = [];
		if (!$exclude_all) {
			$out = ['0' => __('All', 'thegem')];
		}
		$terms = get_terms([
			'taxonomy' => 'product_cat',
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
	 * Make options select products attributes
	 * @access protected
	 * @return array
	 */
	protected function select_products_attributes() {
		global $wc_product_attributes;
		$out = [];

		if (empty($wc_product_attributes) || is_wp_error($wc_product_attributes)) {
			return $out;
		}

		foreach ((array)$wc_product_attributes as $attr) {
			if (!empty($attr->attribute_name)) {
				$out[$attr->attribute_name] = $attr->attribute_label;
			}
		}
		return $out;
	}

	/**
	 * Make options select products tags
	 * @access protected
	 * @return array
	 */
	protected function select_products_tags() {
		$out = [];
		$terms = get_terms( [
			'taxonomy' => 'product_tag',
			'hide_empty' => true,
		] );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return $out;
		}

		foreach ( (array) $terms as $term ) {
			if ( ! empty( $term->name ) ) {
				$out[ $term->slug ] = $term->name;
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
		$skins = $this->get_presets_options();

		$this->start_controls_section(
			'section_layout',
			[
				'label' => __('Layout', 'thegem'),
			]
		);

		$this->add_control(
			'layout_type',
			[
				'label' => __('Layout', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid' => __('Grid', 'thegem'),
					'list' => __('List', 'thegem'),
				],
			]
		);

		$this->add_control(
			'thegem_elementor_preset',
			[
				'label' => __('Skin', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'groups' => array_values($this->get_options_by_groups($skins)),
				'default' => $this->set_default_presets_options(),
				'frontend_available' => true,
				'render_type' => 'none',
				'condition' => [
					'layout_type!' => 'list',
				],
			]
		);

		$this->add_control(
			'columns_desktop_list',
			[
				'label' => __('Columns Desktop', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => '2x',
				'options' => [
					'1x' => __('1x columns', 'thegem'),
					'2x' => __('2x columns', 'thegem'),
					'3x' => __('3x columns', 'thegem'),
					'4x' => __('4x columns', 'thegem'),
				],
				'condition' => [
					'layout_type' => 'list',
				],
			]
		);

		$this->add_control(
			'columns_tablet_list',
			[
				'label' => __('Columns Tablet', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => '2x',
				'options' => [
					'1x' => __('1x columns', 'thegem'),
					'2x' => __('2x columns', 'thegem'),
				],
				'condition' => [
					'layout_type' => 'list',
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
					'1x' => __('1x columns', 'thegem'),
					'2x' => __('2x columns', 'thegem'),
					'3x' => __('3x columns', 'thegem'),
					'4x' => __('4x columns', 'thegem'),
					'5x' => __('5x columns', 'thegem'),
					'6x' => __('6x columns', 'thegem'),
					'100%' => __('100% width', 'thegem'),
				],
				'condition' => [
					'layout_type!' => 'list',
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
					'layout_type!' => 'list',
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
					'layout_type!' => 'list',
				],
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => __('Grid Layout', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'justified',
				'options' => [
					'justified' => __('Justified Grid', 'thegem'),
					'masonry' => __('Masonry Grid', 'thegem'),
					'metro' => __('Metro Style', 'thegem'),
				],
				'condition' => [
					'layout_type!' => 'list',
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
					'6' => __('6 Columns', 'thegem'),
				],
				'condition' => [
					'columns_desktop' => '100%',
					'layout_type!' => 'list',
				],
				'description' => __('Number of columns for 100% width grid for desktop resolutions starting from 1920 px and above', 'thegem'),
			]
		);

		$this->add_control(
			'image_aspect_ratio',
			[
				'label' => __('Image Aspect Ratio', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'portrait',
				'options' => [
					'portrait' => __('Portrait', 'thegem'),
					'square' => __('Square', 'thegem'),
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'layout',
							'operator' => '=',
							'value' => 'justified',
						],
						[
							'name' => 'layout_type',
							'operator' => '=',
							'value' => 'list',
						],
					],
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
					'layout_type!' => 'list',
					'columns_desktop!' => '100%',
					'layout' => 'justified',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_products',
			[
				'label' => __('Products', 'thegem'),
			]
		);

		$this->add_control(
			'source_header',
			[
				'label' => __('Source', 'thegem'),
				'type' => Controls_Manager::HEADING,
			]
		);

		if ($this->is_product_single) {

			$this->add_control(
				'source_type',
				[
					'label' => __('Products Source', 'thegem'),
					'default' => 'related_upsell',
					'type' => Controls_Manager::SELECT,
					'label_block' => true,
					'options' => [
						'related_upsell' => __('Display related or upsell products', 'thegem'),
						'custom' => __('Display products by categories / attributes', 'thegem'),
					],
				]
			);

		} else if ($this->is_product_cart) {

			$this->add_control(
				'source_type',
				[
					'label' => __('Products Source', 'thegem'),
					'default' => 'cross_sell',
					'type' => Controls_Manager::SELECT,
					'label_block' => true,
					'options' => [
						'cross_sell' => __('Cross-Sell Products', 'thegem'),
						'custom' => __('Display products by categories / attributes', 'thegem'),
					],
				]
			);

		} else if ($this->is_product_archive) {

			$this->add_control(
				'source_type',
				[
					'label' => __('Products Source', 'thegem'),
					'default' => 'archive',
					'type' => Controls_Manager::SELECT,
					'label_block' => true,
					'options' => [
						'archive' => __('Products Archive', 'thegem'),
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
			'related_upsell_source',
			[
				'label' => __('Select Products to Display', 'thegem'),
				'default' => 'related',
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'options' => [
					'related' => __('Related Products', 'thegem'),
					'upsell' => __('Upsell Products', 'thegem')
				],
				'condition' => [
					'source_type' => 'related_upsell',
				],
			]
		);

		$this->add_control(
			'source',
			[
				'label' => __('Source', 'thegem'),
				'default' => ['category'],
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'category' => __('Category', 'thegem'),
					'attribute' => __('Attribute', 'thegem'),
					'tag' => __('Tag', 'thegem'),
					'products' => __('Products', 'thegem'),
				],
				'condition' => [
					'source_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'content_products_cat',
			[
				'label' => __('Select Products Categories', 'thegem'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $this->select_products_sets(),
				'frontend_available' => true,
				'condition' => [
					'source_type' => 'custom',
					'source' => 'category',
				],
			]
		);

		$this->add_control(
			'content_products_attr',
			[
				'label' => __('Select Products Attributes', 'thegem'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $this->select_products_attributes(),
				'default' => '0',
				'frontend_available' => true,
				'condition' => [
					'source_type' => 'custom',
					'source' => 'attribute',
				],
			]
		);

		global $wc_product_attributes;

		if (!empty($wc_product_attributes) && !is_wp_error($wc_product_attributes)) {

			foreach ((array)$wc_product_attributes as $attr) {

				if (!empty($attr->attribute_name)) {
					$terms = get_terms('pa_' . $attr->attribute_name);
					$options = ['0' => __('All', 'thegem')];
					if (!empty($terms) && !is_wp_error($terms)) {

						foreach ($terms as $term) {
							$options[$term->slug] = $term->name;
						}
					}

					$this->add_control(
						'content_products_attr_val_' . $attr->attribute_name,
						[
							'label' => __('Select', 'thegem') . ' ' . $attr->attribute_label . ' ' . __('Values', 'thegem'),
							'type' => Controls_Manager::SELECT2,
							'label_block' => true,
							'multiple' => true,
							'options' => $options,
							'frontend_available' => true,
							'condition' => [
								'source_type' => 'custom',
								'source' => 'attribute',
								'content_products_attr' => $attr->attribute_name,
							],
						]
					);
				}
			}
		}

		$this->add_control(
			'content_products_tags',
			[
				'label' => __( 'Select Products Tags', 'thegem' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->select_products_tags(),
				'frontend_available' => true,
				'label_block' => true,
				'condition' => [
					'source_type' => 'custom',
					'source' => 'tag',
				],
			]
		);

		$this->add_control(
			'content_products',
			[
				'label' => __('Select Products', 'thegem'),
				'type' => 'gem-query-control',
				'search' => 'thegem_get_posts_by_query',
				'render' => 'thegem_get_posts_title_by_id',
				'post_type' => 'product',
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'source_type' => 'custom',
					'source' => 'products',
				],
				'description' => __('Add products by title.', 'thegem'),
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
			'exclude_products',
			[
				'label' => __('Exclude Products', 'thegem'),
				'type' => 'gem-query-control',
				'search' => 'thegem_get_posts_by_query',
				'render' => 'thegem_get_posts_title_by_id',
				'post_type' => 'product',
				'label_block' => true,
				'multiple' => true,
				'description' => __('Add products by title.', 'thegem'),
			]
		);

		$this->add_control(
			'sorting_header',
			[
				'label' => __('Default Sorting', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __('Order By', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'default' => __('WooCommerce Default Sorting', 'thegem'),
					'date' => __('Date', 'thegem'),
					'popularity' => __('Popularity', 'thegem'),
					'rating' => __('Rating', 'thegem'),
					'title' => __('Name', 'thegem'),
					'price' => __('Price', 'thegem'),
					'rand' => __('Random', 'thegem'),
				],
				'default' => 'default',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __('Order', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'asc' => __('ASC', 'thegem'),
					'desc' => __('DESC', 'thegem'),
				],
				'default' => 'asc',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cross_sell_title',
			[
				'label' => __('Title', 'thegem'),
				'condition' => [
					'source_type' => 'cross_sell',
				],
			]
		);

		$this->add_control(
			'show_cross_sell_title', [
				'label' => __('Title', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'thegem'),
				'label_off' => __('Hide', 'thegem'),
			]
		);

		$this->add_control(
			'cross_sell_title_text',
			[
				'label' => __('Title Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('You may also like', 'thegem'),
				'condition' => [
					'show_cross_sell_title' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'cross_sell_title_alignment',
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
					'justify' => [
						'title' => __('Justified', 'thegem'),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'selectors_dictionary' => [
					'left' => 'justify-content: left; text-align: left;',
					'right' => 'justify-content: right; text-align: right;',
					'center' => 'justify-content: center; text-align: center;',
					'justify' => 'justify-content: space-between; text-align: justify;',
				],
				'selectors' => [
					'{{WRAPPER}} .cross-sell-title' => '{{VALUE}};',
				],
				'condition' => [
					'show_cross_sell_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'cross_sell_title_tag',
			[
				'label' => __('HTML Tag', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => __('H1', 'thegem'),
					'h2' => __('H2', 'thegem'),
					'h3' => __('H3', 'thegem'),
					'h4' => __('H4', 'thegem'),
					'h5' => __('H5', 'thegem'),
					'h6' => __('H6', 'thegem'),
					'p' => __('p', 'thegem'),
					'div' => __('div', 'thegem'),
				],
				'default' => 'div',
				'condition' => [
					'show_cross_sell_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'cross_sell_title_style',
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
				'default' => 'title-h3',
				'condition' => [
					'show_cross_sell_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'cross_sell_title_weight',
			[
				'label' => __('Font weight', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __('Default', 'thegem'),
					'light' => __('Thin', 'thegem'),
				],
				'default' => 'light',
				'condition' => [
					'show_cross_sell_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'cross_sell_title_letter_spacing',
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
					'{{WRAPPER}} .cross-sell-title' => 'letter-spacing: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_cross_sell_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'cross_sell_title_transform',
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
					'{{WRAPPER}} .cross-sell-title' => '{{VALUE}};',
				],
				'condition' => [
					'show_cross_sell_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'cross_sell_title_color',
			[
				'label' => __('Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .cross-sell-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_cross_sell_title' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'cross_sell_spacing',
			[
				'label' => __('Spacing', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .cross-sell-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_cross_sell_title' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_caption',
			[
				'label' => __('Caption', 'thegem'),
			]
		);

		$this->add_control(
			'caption_position_list',
			[
				'label' => __('Caption Position', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'right' => __('Right', 'thegem'),
					'left' => __('Left', 'thegem'),
					'zigzag' => __('Zigzag', 'thegem'),
				],
				'condition' => [
					'layout_type' => 'list',
				],
			]
		);

		$this->add_control(
			'caption_layout_list',
			[
				'label' => __('Caption Layout', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'vertical',
				'options' => [
					'vertical' => __('Vertical', 'thegem'),
					'inline' => __('Inline', 'thegem'),
				],
				'condition' => [
					'layout_type' => 'list',
				],
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
				'condition' => [
					'layout_type!' => 'list',
				],
			]
		);

		$this->add_responsive_control(
			'product_show_categories', [
				'label' => __('Categories', 'thegem'),
				'desktop_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block',
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .categories' => 'display: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_show_title', [
				'label' => __('Product Title', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true
			]
		);

		$this->add_control(
			'product_show_description', [
				'label' => __('Product Description', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'layout_type' => 'list',
				],
			]
		);

		$this->add_control(
			'product_show_price', [
				'label' => __('Product Price', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true
			]
		);

		$this->add_responsive_control(
			'product_show_reviews', [
				'label' => __('Reviews', 'thegem'),
				'desktop_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block',
				],
				'selectors' => [
					'{{WRAPPER}} .reviews' => 'display: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'attribute_swatches', [
				'label' => __('Attribute Swatches', 'thegem'),
				'desktop_default' => '',
				'tablet_default' => '',
				'mobile_default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block',
				],
				'selectors' => [
					'{{WRAPPER}} .product-variations' => 'display: {{VALUE}}',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'attribute_title',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __('Title', 'thegem'),
				'default' => __('Attribute', 'thegem'),
			]
		);

		$repeater->add_control(
			'attribute_name',
			[
				'label' => __('Select Attribute', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'options' => $this->select_products_attributes(),
			]
		);

		$repeater->add_control(
			'attribute_count',
			[
				'label' => __('Number of values to show', 'thegem'),
				'type' => Controls_Manager::NUMBER,
				'min' => -1,
				'max' => 100,
				'step' => 1,
				'default' => 4,
				'description' => __('Use -1 to show all', 'thegem'),
			]
		);

		$repeater->add_control(
			'attribute_show_name', [
				'label' => __('Show Attribute Name', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true
			]
		);

		$this->add_control(
			'repeater_swatches',
			[
				'type' => Controls_Manager::REPEATER,
				'label' => __('Attributes', 'thegem'),
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ attribute_title }}}',
				'default' => [
					[
						'attribute_title' => __('Attribute 1', 'thegem'),
					]
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'attribute_swatches',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'attribute_swatches_tablet',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'attribute_swatches_mobile',
							'operator' => '=',
							'value' => 'yes',
						]
					]
				]
			]
		);

		$this->add_control(
			'add_to_cart_header',
			[
				'label' => __('Add to Cart', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_show_add_to_cart', [
				'label' => __('Add to Cart', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true
			]
		);

		$this->add_control(
			'product_show_add_to_cart_mobiles', [
				'label' => __('Show On Mobiles', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'product_show_add_to_cart' => 'yes',
				],
			]
		);

		$this->add_control(
			'add_to_cart_type',
			[
				'label' => __('Type', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'icon' => __('Icon', 'thegem'),
					'button' => __('Button', 'thegem'),
				],
				'default' => 'button',
				'frontend_available' => true,
				'condition' => [
					'product_show_add_to_cart' => 'yes',
					'layout_type!' => 'list',
				],
			]
		);

		$this->add_control(
			'cart_button_show_icon', [
				'label' => __('Show Icon', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'conditions' => [
					'terms' => [
						[
							'name' => 'product_show_add_to_cart',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'layout_type',
									'operator' => '=',
									'value' => 'list',
								],
								[
									'name' => 'add_to_cart_type',
									'operator' => '=',
									'value' => 'button',
								],
							]
						],
					]
				]
			]
		);

		$this->add_control(
			'cart_button_text',
			[
				'label' => __('Button Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Add To Cart', 'thegem'),
				'conditions' => [
					'terms' => [
						[
							'name' => 'product_show_add_to_cart',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'layout_type',
									'operator' => '=',
									'value' => 'list',
								],
								[
									'name' => 'add_to_cart_type',
									'operator' => '=',
									'value' => 'button',
								],
							]
						],
					]
				]
			]
		);

		$this->add_control(
			'cart_icon',
			[
				'label' => __('Cart Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'product_show_add_to_cart',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'add_to_cart_type',
									'operator' => '=',
									'value' => 'icon',
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'cart_button_show_icon',
											'operator' => '=',
											'value' => 'yes',
										],
										[
											'relation' => 'or',
											'terms' => [
												[
													'name' => 'layout_type',
													'operator' => '=',
													'value' => 'list',
												],
												[
													'name' => 'add_to_cart_type',
													'operator' => '=',
													'value' => 'button',
												],
											]
										]
									]
								]
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'select_options_button_text',
			[
				'label' => __('Select Options Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Select Options', 'thegem'),
				'conditions' => [
					'terms' => [
						[
							'name' => 'product_show_add_to_cart',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'layout_type',
									'operator' => '=',
									'value' => 'list',
								],
								[
									'name' => 'add_to_cart_type',
									'operator' => '=',
									'value' => 'button',
								],
							]
						],
					]
				]
			]
		);

		$this->add_control(
			'select_options_icon',
			[
				'label' => __('Select Options Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'product_show_add_to_cart',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'add_to_cart_type',
									'operator' => '=',
									'value' => 'icon',
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'cart_button_show_icon',
											'operator' => '=',
											'value' => 'yes',
										],
										[
											'relation' => 'or',
											'terms' => [
												[
													'name' => 'layout_type',
													'operator' => '=',
													'value' => 'list',
												],
												[
													'name' => 'add_to_cart_type',
													'operator' => '=',
													'value' => 'button',
												],
											]
										]
									]
								]
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'add_to_wishlist_header',
			[
				'label' => __('Add to Wishlist', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		if (!defined('YITH_WCWL')) {

			$this->add_control(
				'wishlist_warning_text',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => __('Please install “YITH WooCommerce Wishlist” plugin to activate “add to wishlist” feature.', 'thegem'),
					'content_classes' => 'elementor-descriptor',
				]
			);

		} else {

			$this->add_control(
				'product_show_wishlist', [
					'label' => __('Add to Wishlist', 'thegem'),
					'default' => 'yes',
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __('On', 'thegem'),
					'label_off' => __('Off', 'thegem'),
					'frontend_available' => true
				]
			);

			$this->add_control(
				'add_wishlist_icon',
				[
					'label' => __('Add to Wishlist Icon', 'thegem'),
					'type' => Controls_Manager::ICONS,
					'condition' => [
						'product_show_wishlist' => 'yes',
					],
				]
			);

			$this->add_control(
				'added_wishlist_icon',
				[
					'label' => __('Added to Wishlist Icon', 'thegem'),
					'type' => Controls_Manager::ICONS,
					'condition' => [
						'product_show_wishlist' => 'yes',
					],
				]
			);
		}

		$this->add_control(
			'product_show_divider',
			[
				'label' => __( 'Divider', 'thegem' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'thegem' ),
				'label_off' => __( 'Hide', 'thegem' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'layout_type' => 'list',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => __('Divider Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-grid.extended-products-grid.list-style.with-divider .portfolio-item .wrap:before' => 'border-color: {{VALUE}};'
				],
				'condition' => [
					'layout_type' => 'list',
					'product_show_divider' => 'yes',
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
					'arrows' => __('Arrows', 'thegem'),
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
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'more_stretch_full_width',
							'operator' => '!=',
							'value' => 'yes',
						],
						[
							'name' => 'pagination_type',
							'operator' => '=',
							'value' => 'more',
						],
						[
							'name' => 'show_pagination',
							'operator' => '=',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_labels',
			[
				'label' => __('Labels', 'thegem'),
			]
		);

		$this->add_control(
			'labels_design',
			[
				'label' => __('Labels Style', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => __('Style 1', 'thegem'),
					'2' => __('Style 2', 'thegem'),
					'3' => __('Style 3', 'thegem'),
					'4' => __('Style 4', 'thegem'),
					'5' => __('Style 5', 'thegem'),
					'6' => __('Style 6', 'thegem'),
				],
			]
		);

		$this->add_control(
			'product_show_new', [
				'label' => __('"New" Label', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true
			]
		);

		$this->add_control(
			'new_label_text',
			[
				'label' => __('Label Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('New', 'thegem'),
				'condition' => [
					'product_show_new' => 'yes',
				],
			]
		);

		$this->add_control(
			'product_show_sale', [
				'label' => __('"Sale" Label', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true
			]
		);

		$this->add_control(
			'sale_label_type',
			[
				'label' => __('Sale Label Type', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'percentage',
				'options' => [
					'percentage' => __('Show Discount Percentage', 'thegem'),
					'text' => __('Show Text', 'thegem'),
				],
				'condition' => [
					'product_show_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'sale_label_prefix',
			[
				'label' => __('Prefix', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('-', 'thegem'),
				'condition' => [
					'product_show_sale' => 'yes',
					'sale_label_type' => 'percentage',
				],
			]
		);

		$this->add_control(
			'sale_label_suffix',
			[
				'label' => __('Suffix', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('%', 'thegem'),
				'condition' => [
					'product_show_sale' => 'yes',
					'sale_label_type' => 'percentage',
				],
			]
		);

		$this->add_control(
			'sale_label_text',
			[
				'label' => __('Label Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('On Sale', 'thegem'),
				'condition' => [
					'product_show_sale' => 'yes',
					'sale_label_type' => 'text',
				],
			]
		);

		$this->add_control(
			'product_show_out', [
				'label' => __('"Out of stock" Label', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true
			]
		);

		$this->add_control(
			'out_label_text',
			[
				'label' => __('Label Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Out of stock', 'thegem'),
				'condition' => [
					'product_show_out' => 'yes',

				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filters',
			[
				'label' => __('Filters & Sorting', 'thegem'),
			]
		);

		$this->add_control(
			'product_show_sorting', [
				'label' => __('Sorting on Frontend', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'product_show_filter', [
				'label' => __('Filters', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'fullwidth_section_sorting',
			[
				'label' => __('Used in fullwidth section (no gaps)', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'description' => __('Activate to add extra padding', 'thegem'),
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'product_show_filter',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'product_show_sorting',
							'operator' => '=',
							'value' => 'yes',
						],
					],
				],
			]
		);

		if ( $this->is_product_archive ) {

			$this->add_control(
				'filters_type',
				[
					'label' => __('Filters Type', 'thegem'),
					'type' => Controls_Manager::SELECT,
					'default' => 'filter_thegem',
					'options' => [
						'filter_thegem' => __('TheGem Filters', 'thegem'),
						'filter_woo' => __('WooCommerce Sidebar Widgets', 'thegem'),
					],
					'condition' => [
						'product_show_filter' => 'yes',
					],
				]
			);

		} else {

			$this->add_control(
				'filters_type',
				[
					'type' => Controls_Manager::HIDDEN,
					'default' => 'filter_thegem',
				]
			);

		}

		$this->add_control(
			'filter_woo_description',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __('To add filter widgets to WooCommerce Sidebar go to <a href="'.get_site_url().'/wp-admin/widgets.php" target="_blank">Appearance -> Widgets</a>.', 'thegem'),
				'content_classes' => 'elementor-descriptor',
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_woo',
				],
			]
		);

		$this->add_control(
			'woo_filters_style',
			[
				'label' => __('Filters Style', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'sidebar',
				'options' => [
					'sidebar' => __('Sidebar', 'thegem'),
					'hidden' => __('Hidden Sidebar', 'thegem'),
				],
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_woo',
				],
			]
		);

		$this->add_control(
			'woo_ajax_filtering',
			[
				'label' => __('Ajax Filtering', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_woo',
				],
			]
		);

		$this->add_control(
			'woo_remove_counts',
			[
				'label' => __('Remove Attributes Counts', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_woo',
				],
			]
		);

		$this->add_control(
			'filters_style',
			[
				'label' => __('Filters Style', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'standard',
				'options' => [
					'standard' => __('Horizontal', 'thegem'),
					'sidebar' => __('Sidebar', 'thegem'),
					'hidden' => __('Hidden Sidebar', 'thegem'),
					'tabs' => __('Products Tabs', 'thegem'),
				],
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'sidebar_position',
			[
				'label' => __('Sidebar Position', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __('Left', 'thegem'),
					'right' => __('Right', 'thegem'),
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'product_show_filter',
							'operator' => '=',
							'value' => 'yes',
						], [
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'filters_type',
											'operator' => '=',
											'value' => 'filter_woo',
										], [
											'name' => 'woo_filters_style',
											'operator' => '=',
											'value' => 'sidebar',
										],
									]
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'filters_type',
											'operator' => '=',
											'value' => 'filter_thegem',
										], [
											'name' => 'filters_style',
											'operator' => '=',
											'value' => 'sidebar',
										],
									]
								]
							],
						],
					]
				]
			]
		);

		$this->add_control(
			'sidebar_sticky',
			[
				'label' => __('Sticky Sidebar', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'product_show_filter',
							'operator' => '=',
							'value' => 'yes',
						], [
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'filters_type',
											'operator' => '=',
											'value' => 'filter_woo',
										], [
											'name' => 'woo_filters_style',
											'operator' => '=',
											'value' => 'sidebar',
										],
									]
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'filters_type',
											'operator' => '=',
											'value' => 'filter_thegem',
										], [
											'name' => 'filters_style',
											'operator' => '=',
											'value' => 'sidebar',
										],
									]
								]
							],
						],
					]
				]
			]
		);

		$filters_scroll_top_default = '';
		if ($this->is_product_archive) {
			$filters_scroll_top_default = 'yes';
		}

		$this->add_control(
			'filters_scroll_top', [
				'label' => __('Scroll To Top', 'thegem'),
				'default' => $filters_scroll_top_default,
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'product_show_filter',
							'operator' => '=',
							'value' => 'yes',
						], [
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_woo',
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'filters_type',
											'operator' => '=',
											'value' => 'filter_thegem',
										], [
											'name' => 'filters_style',
											'operator' => '!=',
											'value' => 'tabs',
										],
									]
								]
							],
						],
					]
				],
			]
		);

		$this->add_control(
			'filters_categories_header',
			[
				'label' => __('Categories', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_categories', [
				'label' => __('Filter by Categories', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_categories_hierarchy', [
				'label' => __('Hierarchy', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'thegem'),
				'label_off' => __('Hide', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'filter_by_categories' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_categories_count', [
				'label' => __('Product Counts', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'thegem'),
				'label_off' => __('Hide', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'filter_by_categories' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_categories_title',
			[
				'label' => __('Title', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Categories', 'thegem'),
				'condition' => [
					'filter_by_categories' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_categories_order_by',
			[
				'label' => __('Order By', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => [
					'name' => __('Name', 'thegem'),
					'term_order' => __('Category Order', 'thegem'),
				],
				'condition' => [
					'filter_by_categories' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_categories_order',
			[
				'label' => __('Appearance Order', 'thegem'),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 5,
				'step' => 1,
				'default' => 1,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filter-item.cats' => 'order: {{VALUE}};'
				],
				'condition' => [
					'filter_by_categories' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filters_attributes_header',
			[
				'label' => __('Attributes', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_attribute', [
				'label' => __('Filter by Attribute', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'attribute_title',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __('Title', 'thegem'),
				'default' => __('Filter by', 'thegem'),
			]
		);

		$repeater->add_control(
			'attribute_name',
			[
				'label' => __('Attribute', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'options' => $this->select_products_attributes(),
			]
		);

		$repeater->add_control(
			'attribute_query_type',
			[
				'label' => __('Query Type', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'default' => 'and',
				'options' => [
					'and' => __('AND', 'thegem'),
					'or' => __('OR', 'thegem'),
				],
			]
		);

		$this->add_control(
			'repeater_attributes',
			[
				'type' => Controls_Manager::REPEATER,
				'label' => __('Attributes', 'thegem'),
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ attribute_title }}}',
				'default' => [
					[
						'attribute_title' => __('Attribute 1', 'thegem'),
					],
					[
						'attribute_title' => __('Attribute 2', 'thegem'),
					]
				],
				'condition' => [
					'filter_by_attribute' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_attribute_count', [
				'label' => __('Product Counts', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'thegem'),
				'label_off' => __('Hide', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'filter_by_attribute' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_attribute_hide_null', [
				'label' => __('Hide Empty', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'thegem'),
				'label_off' => __('Hide', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'filter_by_attribute' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_attribute_order',
			[
				'label' => __('Appearance Order', 'thegem'),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 5,
				'step' => 1,
				'default' => 2,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filter-item.attribute' => 'order: {{VALUE}};'
				],
				'condition' => [
					'filter_by_attribute' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filters_price_header',
			[
				'label' => __('Price', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_price', [
				'label' => __('Filter by Price', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_price_title',
			[
				'label' => __('Price', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Price', 'thegem'),
				'condition' => [
					'filter_by_price' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_price_order',
			[
				'label' => __('Appearance Order', 'thegem'),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 5,
				'step' => 1,
				'default' => 3,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filter-item.price' => 'order: {{VALUE}};'
				],
				'condition' => [
					'filter_by_price' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filters_status_header',
			[
				'label' => __('Product Status', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_status', [
				'label' => __('Filter by Product Status', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_status_title',
			[
				'label' => __('Title', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Status', 'thegem'),
				'condition' => [
					'filter_by_status' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_status_sale', [
				'label' => __('On Sale', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'filter_by_status' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_status_sale_text',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __('“On Sale” Text', 'thegem'),
				'input_type' => 'text',
				'default' => __('On Sale', 'thegem'),
				'condition' => [
					'filter_by_status_sale' => 'yes',
					'filter_by_status' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_status_stock', [
				'label' => __('In Stock', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'filter_by_status' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_status_stock_text',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __('“In Stock” Text', 'thegem'),
				'input_type' => 'text',
				'default' => __('In Stock', 'thegem'),
				'condition' => [
					'filter_by_status_stock' => 'yes',
					'filter_by_status' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_status_count', [
				'label' => __('Status Counts', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'thegem'),
				'label_off' => __('Hide', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'filter_by_status' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_status_order',
			[
				'label' => __('Appearance Order', 'thegem'),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 5,
				'step' => 1,
				'default' => 4,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filter-item.status' => 'order: {{VALUE}};'
				],
				'condition' => [
					'filter_by_status' => 'yes',
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filters_search_header',
			[
				'label' => __('Product Search', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_by_search', [
				'label' => __('Product Search', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filters_text_labels_header',
			[
				'label' => __('Text Labels', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filters_text_labels_all_text',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __('“Show All” Text', 'thegem'),
				'input_type' => 'text',
				'default' => __('Show All', 'thegem'),
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filters_text_labels_clear_text',
			[
				'label' => __('“Clear Filters” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Clear Filters', 'thegem'),
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
				],
			]
		);

		$this->add_control(
			'filters_text_labels_search_text',
			[
				'label' => __('“Search by Product” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Search by Product', 'thegem'),
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_buttons_hidden_show_text',
			[
				'label' => __('“Show Filters” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Show Filters', 'thegem'),
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
				],
			]
		);

		$this->add_control(
			'filter_buttons_hidden_sidebar_title',
			[
				'label' => __('Sidebar Title', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Filter', 'thegem'),
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filter_buttons_hidden_filter_by_text',
			[
				'label' => __('“Filter By” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Filter By', 'thegem'),
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_style!' => 'tabs',
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$this->add_control(
			'filters_tabs_style',
			[
				'label' => __('Tabs Style', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __('Default', 'thegem'),
					'alternative' => __('Alternative', 'thegem'),
				],
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_thegem',
					'filters_style' => 'tabs',
				],
			]
		);

		$this->add_control(
			'tabs_preloading',
			[
				'label' => __('Tabs Preloading', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_thegem',
					'filters_style' => 'tabs',
				],
				'description' => __('If enabled, items in the tabs will be preloaded on the current page.', 'thegem'),
			]
		);

		$this->add_control(
			'filters_tabs_title_header',
			[
				'label' => __('Title', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_thegem',
					'filters_style' => 'tabs',
				],
			]
		);

		$this->add_control(
			'filters_tabs_title_text',
			[
				'label' => __('Title', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_thegem',
					'filters_style' => 'tabs',
				],
			]
		);

		$this->add_control(
			'filters_tabs_title_style_preset',
			[
				'label' => __('Style Preset', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'bold',
				'options' => [
					'bold' => __('Bold Title', 'thegem'),
					'thin' => __('Thin Title', 'thegem'),
				],
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_thegem',
					'filters_style' => 'tabs',
				],
			]
		);

		$this->add_control(
			'filters_tabs_title_separator',
			[
				'label' => __('Separator', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_thegem',
					'filters_style' => 'tabs',
					'filters_tabs_style' => 'alternative',
				],
			]
		);

		$this->add_control(
			'filters_tabs_tabs_header',
			[
				'label' => __('Tabs', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_thegem',
					'filters_style' => 'tabs',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'filters_tabs_tab_title',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __('Tabs Title', 'thegem'),
				'default' => __('Title', 'thegem'),
			]
		);

		$repeater->add_control(
			'filters_tabs_tab_filter_by',
			[
				'label' => __('Filter By', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'default' => 'categories',
				'options' => [
					'categories' => __('Categories', 'thegem'),
					'featured' => __('Featured Products', 'thegem'),
					'sale' => __('On Sale Products', 'thegem'),
					'recent' => __('Recent Products', 'thegem'),
				],
			]
		);

		$repeater->add_control(
			'filters_tabs_tab_products_cat',
			[
				'label' => __('Select Category', 'thegem'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'options' => $this->select_products_sets(),
				'frontend_available' => true,
				'condition' => [
					'filters_tabs_tab_filter_by' => 'categories',
				],
			]
		);

		$this->add_control(
			'filters_tabs_tabs',
			[
				'type' => Controls_Manager::REPEATER,
				'label' => __('Tabs', 'thegem'),
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ filters_tabs_tab_title }}}',
				'default' => [
					[
						'filters_tabs_tab_title' => __('Featured Products', 'thegem'),
						'filters_tabs_tab_filter_by' => 'featured',
					],
					[
						'filters_tabs_tab_title' => __('On Sale Products', 'thegem'),
						'filters_tabs_tab_filter_by' => 'sale',
					]
				],
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_thegem',
					'filters_style' => 'tabs',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_notification',
			[
				'label' => __('Notification', 'thegem'),
			]
		);

		$this->add_control(
			'added_cart_text',
			[
				'label' => __('“Added to Cart” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => thegem_get_option('product_archive_added_cart_text'),
			]
		);

		$this->add_control(
			'added_wishlist_text',
			[
				'label' => __('“Added to Wishlist” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => thegem_get_option('product_archive_added_wishlist_text'),
				'condition' => [
					'product_show_wishlist' => 'yes',
				],
			]
		);

		$this->add_control(
			'removed_wishlist_text',
			[
				'label' => __('“Removed from Wishlist” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => thegem_get_option('product_archive_removed_wishlist_text'),
				'condition' => [
					'product_show_wishlist' => 'yes',
				],
			]
		);

		$this->add_control(
			'view_cart_button_text',
			[
				'label' => __('“View Cart” Button Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => thegem_get_option('product_archive_view_cart_button_text'),
			]
		);

		$this->add_control(
			'checkout_button_text',
			[
				'label' => __('“Checkout” Button Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => thegem_get_option('product_archive_checkout_button_text'),
			]
		);

		$this->add_control(
			'view_wishlist_button_text',
			[
				'label' => __('“View Wishlist” Button Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => thegem_get_option('product_archive_view_wishlist_button_text'),
				'condition' => [
					'product_show_wishlist' => 'yes',
				],
			]
		);

		$this->add_control(
			'not_found_text',
			[
				'label' => __('“No Products Found” Button Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'label_block' => true,
				'default' => thegem_get_option('product_archive_not_found_text'),
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
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
			]
		);

		$this->add_control(
			'sharing_icon',
			[
				'label' => __('Sharing Icon', 'thegem'),
				'type' => Controls_Manager::ICONS,
				'condition' => [
					'social_sharing' => 'yes'
				],
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
			'section_additional',
			[
				'label' => __('Additional Options', 'thegem'),
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



		$ignore_highlights_default = '';
		$skeleton_loader_default = 'yes';
		if ($this->is_product_archive) {
			$ignore_highlights_default = 'yes';
			$skeleton_loader_default = '';
		}

		$this->add_control(
			'ignore_highlights',
			[
				'label' => __('Ignore Highlighted Products', 'thegem'),
				'default' => $ignore_highlights_default,
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'conditions' => [
					'terms' => [
						[
							'name' => 'layout_type',
							'operator' => '!=',
							'value' => 'list',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '!=',
									'value' => 'justified',
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
										]
									]
								]
							]
						]
					]
				]
			]
		);

		$this->add_control(
			'skeleton_loader',
			[
				'label' => __('Skeleton Preloader on grid loading', 'thegem'),
				'default' => $skeleton_loader_default,
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'conditions' => [
					'terms' => [
						[
							'name' => 'columns_desktop',
							'operator' => '!=',
							'value' => '100%',
						],
						[
							'name' => 'layout_type',
							'operator' => '!=',
							'value' => 'list',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '!=',
									'value' => 'justified',
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
										]
									]
								]
							]
						]
					]
				]
			]
		);

		$this->add_control(
			'featured_only',
			[
				'label' => __('Show only “Featured” Products', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
			]
		);

		$this->add_control(
			'sale_only',
			[
				'label' => __('Show only “On Sale” Products', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
			]
		);

		$this->add_control(
			'stock_only',
			[
				'label' => __('Hide “out of stock“ products', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
			]
		);

		if (thegem_get_option('product_quick_view')) {
			$quick_default = 'yes';
		} else {
			$quick_default = '';
		}

		$this->add_control(
			'quick_view',
			[
				'label' => __('Quick View', 'thegem'),
				'default' => $quick_default,
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
			]
		);

		$this->add_control(
			'quick_view_text',
			[
				'label' => __('Quick View Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Quick View', 'thegem'),
				'condition' => [
					'quick_view' => 'yes',
					'caption_position' => 'page',
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

		/* Icons Style */
		$this->icons_style($control);

		/* Buttons Style */
		$this->buttons_style($control);

		/* Filter Style (Standard) */
		$this->filter_buttons_standard_style($control);

		/* Filter Style (Sidebar) */
		$this->filter_buttons_hidden_style($control);

		/* Product Tabs Style */
		$this->product_tabs_style($control);

		/* Sorting Style */
		$this->sorting_style($control);

		/* Pagination Style */
		$this->pagination_style($control);

		/* Pagination More Style */
		$this->pagination_more_style($control);

		/* Labels Style */
		$this->labels_style($control);

		/* Notification Style */
		$this->notification_style($control);
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
			'image_column_width',
			[
				'label' => __('Image Column Width (%)', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid.list-style .portfolio-set .portfolio-item .wrap > .image' => 'width: {{SIZE}}%;',
				],
				'condition' => [
					'layout_type' => 'list',
				],
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
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item:not(.size-item)' => 'padding: calc({{SIZE}}{{UNIT}}/2) !important;',
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.size-item' => 'padding: 0 calc({{SIZE}}{{UNIT}}/2) !important;',
					'{{WRAPPER}} .portfolio.extended-products-grid:not(.item-separator) .portfolio-row' => 'margin: calc(-{{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .portfolio.extended-products-grid.item-separator .portfolio-row' => 'margin: 0 calc(-{{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .portfolio.extended-products-grid.fullwidth-columns:not(.item-separator) .portfolio-row' => 'margin: calc(-{{SIZE}}{{UNIT}}/2) 0;',
					'{{WRAPPER}} .portfolio.extended-products-grid.fullwidth-columns.item-separator .portfolio-row' => 'margin: 0;',
					'{{WRAPPER}} .portfolio.extended-products-grid .fullwidth-block:not(.no-paddings)' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.extended-products-grid .fullwidth-block .portfolio-row' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .portfolio.extended-products-grid:not(.item-separator) .fullwidth-block .portfolio-top-panel' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.extended-products-grid.item-separator .fullwidth-block .portfolio-top-panel' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .portfolio.extended-products-grid.fullwidth-columns .with-filter-sidebar .filter-sidebar' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skeleton-posts .portfolio-item' => 'padding: calc({{SIZE}}{{UNIT}}/2) !important;',
					'{{WRAPPER}} .skeleton-posts' => 'margin: calc(-{{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .portfolio.extended-products-grid.list-style.with-divider .portfolio-set .portfolio-item .wrap:before' => 'top: calc(-{{SIZE}}{{UNIT}}/2);'
				]
			]
		);


		$control->add_responsive_control(
			'fullwidth_padding',
			[
				'label' => __('Filters/Sorting Padding', 'thegem'),
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
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'columns_desktop',
							'operator' => '=',
							'value' => '100%',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'product_show_filter',
									'operator' => '=',
									'value' => 'yes',
								],
								[
									'name' => 'product_show_sorting',
									'operator' => '=',
									'value' => 'yes',
								],
							],
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid:not(.item-separator) .fullwidth-block .portfolio-top-panel' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.extended-products-grid.item-separator .fullwidth-block .portfolio-top-panel' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .portfolio.extended-products-grid.fullwidth-columns .with-filter-sidebar .filter-sidebar' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$control->add_control(
			'product_separator',
			[
				'label' => __('Product Separator', 'thegem'),
				'default' => '',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
			]
		);

		$control->add_control(
			'product_separator_color',
			[
				'label' => __('Separator Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .portfolio.item-separator .portfolio-item:before, 
					{{WRAPPER}} .portfolio.item-separator .portfolio-item:after, 
					{{WRAPPER}} .portfolio.item-separator .portfolio-item .item-separator-box:before, 
					{{WRAPPER}} .portfolio.item-separator .portfolio-item .item-separator-box:after' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'product_separator' => 'yes',
				],
			]
		);

		$control->add_control(
			'product_separator_width',
			[
				'label' => __('Separator Width', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 4,
					],
				],
				'default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .portfolio.item-separator .portfolio-item:before, 
					{{WRAPPER}} .portfolio.item-separator .portfolio-item:after, 
					{{WRAPPER}} .portfolio.item-separator .portfolio-item .item-separator-box:before, 
					{{WRAPPER}} .portfolio.item-separator .portfolio-item .item-separator-box:after' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.item-separator .portfolio-item:before, 
					{{WRAPPER}} .portfolio.item-separator .portfolio-item:after' => 'height: calc(100% + {{SIZE}}{{UNIT}}); top: calc(-{{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .portfolio.item-separator .portfolio-item .item-separator-box:before, 
					{{WRAPPER}} .portfolio.item-separator .portfolio-item .item-separator-box:after' => 'width: calc(100% + {{SIZE}}{{UNIT}}); left: calc(-{{SIZE}}{{UNIT}}/2);',
				],
				'condition' => [
					'product_separator' => 'yes',
				],
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
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .image-inner,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .variations-notification,
					{{WRAPPER}} .portfolio.extended-products-grid.caption-position-hover .portfolio-item .wrap,
					{{WRAPPER}} .portfolio.extended-products-grid.caption-position-image .portfolio-item .wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.extended-products-grid.caption-position-page .portfolio-item .wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
				],
			]
		);

		$control->add_responsive_control(
			'image_content_paddings',
			[
				'label' => __('Content Paddings', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
//					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .portfolio-icons,
//					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption,
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .portfolio-icons' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption' => 'padding-bottom: {{BOTTOM}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links' => 'padding: 0;',
//					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'caption_position!' => 'page',
				],
			]
		);


		$control->add_control(
			'image_hover_effect_page',
			[
				'label' => __('Hover Effect', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'slide' => __('Show Next Image (Slide)', 'thegem'),
					'fade' => __('Show Next Image (Fade)', 'thegem'),
					'disabled' => __('Disabled', 'thegem'),
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'layout_type',
							'operator' => '=',
							'value' => 'list',
						],
						[
							'name' => 'caption_position',
							'operator' => '=',
							'value' => 'page',
						],
					]
				]
			]
		);

		$control->add_control(
			'image_hover_effect_image',
			[
				'label' => __('Hover Effect', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'slide' => __('Show Next Image (Slide)', 'thegem'),
					'fade' => __('Show Next Image (Fade)', 'thegem'),
					'gradient' => __('Gradient', 'thegem'),
					'circular' => __('Circular Overlay', 'thegem'),
					'disabled' => __('Disabled', 'thegem'),
				],
				'condition' => [
					'caption_position' => 'image',
					'layout_type!' => 'list',
				],
			]
		);

		$control->add_control(
			'image_hover_effect_hover',
			[
				'label' => __('Hover Effect', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'slide' => __('Show Next Image (Slide)', 'thegem'),
					'fade' => __('Show Next Image (Fade)', 'thegem'),
					'default' => __('Cyan Breeze', 'thegem'),
					'zooming-blur' => __('Zooming White', 'thegem'),
					'horizontal-sliding' => __('Horizontal Sliding', 'thegem'),
					'vertical-sliding' => __('Vertical Sliding', 'thegem'),
					'gradient' => __('Gradient', 'thegem'),
					'circular' => __('Circular Overlay', 'thegem'),
					'disabled' => __('Disabled', 'thegem'),
				],
				'condition' => [
					'caption_position' => 'hover',
					'layout_type!' => 'list',
				],
			]
		);

		$control->add_control(
			'image_hover_effect_fallback',
			[
				'label' => __('Fallback Hover', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'zooming',
				'options' => [
					'disabled' => __('Disabled', 'thegem'),
					'zooming' => __('Zooming White', 'thegem'),
				],
				'description' => __('Used in case of only one product image', 'thegem'),
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'layout_type',
							'operator' => '=',
							'value' => 'list',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'caption_position',
									'operator' => '=',
									'value' => 'page',
								],
								[
									'name' => 'image_hover_effect_page',
									'operator' => 'in',
									'value' => ['slide', 'fade']
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'caption_position',
									'operator' => '=',
									'value' => 'image',
								],
								[
									'name' => 'image_hover_effect_image',
									'operator' => 'in',
									'value' => ['slide', 'fade']
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'caption_position',
									'operator' => '=',
									'value' => 'hover',
								],
								[
									'name' => 'image_hover_effect_hover',
									'operator' => 'in',
									'value' => ['slide', 'fade']
								],
							],
						],
					],
				],
			]
		);

		$control->add_group_control(
			Group_Control_Background_Light::get_type(),
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
							'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .overlay:before, {{WRAPPER}} .portfolio.extended-products-grid.hover-circular .portfolio-item .image .overlay .overlay-circle' => 'background: {{VALUE}} !important;',
						],
					],
					'gradient_angle' => [
						'selectors' => [
							'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .overlay:before, {{WRAPPER}} .portfolio.extended-products-grid.hover-circular .portfolio-item .image .overlay .overlay-circle' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}) !important;',
						],
					],
					'gradient_position' => [
						'selectors' => [
							'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .overlay:before, {{WRAPPER}} .portfolio.extended-products-grid.hover-circular .portfolio-item .image .overlay .overlay-circle' => 'background-color: transparent; background-image: radial-gradient(at {{SIZE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}) !important;',
						],
					],
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'caption_position',
									'operator' => '=',
									'value' => 'image',
								],
								[
									'name' => 'image_hover_effect_image',
									'operator' => '!in',
									'value' => ['slide', 'fade'],
								],
							]
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'caption_position',
									'operator' => '=',
									'value' => 'hover',
								],
								[
									'name' => 'image_hover_effect_hover',
									'operator' => '!in',
									'value' => ['slide', 'fade'],
								],
							]
						]
					]
				]
			]
		);

		$control->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_hover_css',
				'label' => __('CSS Filters', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item:hover .image-inner,
				{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.hover-effect .image-inner',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'caption_position',
									'operator' => '=',
									'value' => 'image',
								],
								[
									'name' => 'image_hover_effect_image',
									'operator' => '!in',
									'value' => ['slide', 'fade'],
								],
							]
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'caption_position',
									'operator' => '=',
									'value' => 'hover',
								],
								[
									'name' => 'image_hover_effect_hover',
									'operator' => '!in',
									'value' => ['slide', 'fade'],
								],
							]
						]
					]
				]
			]
		);

		$control->start_controls_tabs('full_item_border_tabs');

		$control->start_controls_tab('full_item_border_tab_normal', ['label' => __('Normal', 'thegem')]);


		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'full_item_border_type',
				'label' => __('Border Type', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image',
			]
		);

		$control->remove_control('full_item_border_type_color');


		$control->add_control(
			'full_item_border_color_normal',
			[
				'label' => __('Border Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'full_item_border_type_border!' => '',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_shadow_normal',
				'label' => __('Shadow', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid:not(.shadowed-container) .portfolio-item .image, 
						{{WRAPPER}} .portfolio.extended-products-grid.shadowed-container .portfolio-item .wrap',
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab('full_item_border_tab_hover', ['label' => __('Hover', 'thegem')]);

		$control->add_control(
			'full_item_border_color_hover',
			[
				'label' => __('Border Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'default' => '#dfe5e8',
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item:hover .image,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.hover-effect .image' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'full_item_border_type_border!' => '',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_shadow_hover',
				'label' => __('Shadow', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid:not(.shadowed-container) .portfolio-item:hover .image,
					{{WRAPPER}} .portfolio.extended-products-grid:not(.shadowed-container) .portfolio-item.hover-effect .image, 
					{{WRAPPER}} .portfolio.extended-products-grid.shadowed-container .portfolio-item:hover .wrap, 
					{{WRAPPER}} .portfolio.extended-products-grid.shadowed-container .portfolio-item.hover-effect .wrap',
			]
		);

		$control->end_controls_tab();

		$control->end_controls_tabs();

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
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'image_shadow_normal_box_shadow_type',
									'operator' => '=',
									'value' => 'yes',
								],
								[
									'name' => 'image_shadow_hover_box_shadow_type',
									'operator' => '=',
									'value' => 'yes',
								],
							],
						],
					],
				],
			]
		);


		$control->add_control(
			'quick_view_header',
			[
				'label' => __('Quick View', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'quick_view' => 'yes',
					'caption_position' => 'page',
				],
			]
		);

		$control->add_control(
			'quick_view_background_color',
			[
				'label' => __('Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .quick-view-button' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'quick_view' => 'yes',
					'caption_position' => 'page',
				],
			]
		);

		$control->add_control(
			'quick_view_text_color',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .quick-view-button' => 'color: {{VALUE}};',
				],
				'condition' => [
					'quick_view' => 'yes',
					'caption_position' => 'page',
				],
			]
		);

		$control->add_group_control(Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'fquick_view_text_typography',
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .quick-view-button',
				'condition' => [
					'quick_view' => 'yes',
					'caption_position' => 'page',
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
				'condition' => [
					'columns_desktop!' => '100%',
				],
			]
		);

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

		$control->add_control(
			'font_size_preset',
			[
				'label' => __('Font size preset', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'enlarged',
				'options' => [
					'enlarged' => __('Enlarged', 'thegem'),
					'normal' => __('Normal', 'thegem'),
				],
				'condition' => [
					'caption_position' => 'hover',
					'columns_desktop' => ['1x', '2x'],
				],
			]
		);

		$conditions_caption_container = [
			'relation' => 'and',
			'terms' => [
				[
					'name' => 'layout_type',
					'operator' => '!=',
					'value' => 'list',
				],
				[
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'caption_position',
							'operator' => '=',
							'value' => 'image',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'caption_position',
									'operator' => '=',
									'value' => 'hover',
								],
								[
									'name' => 'image_hover_effect_hover',
									'operator' => 'in',
									'value' => ['slide', 'fade']
								],
							],
						],
					],
				]
			]
		];

		$control->add_control(
			'caption_container_header_hover',
			[
				'label' => __('Container', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'conditions' => $conditions_caption_container,
			]
		);

		$control->add_control(
			'caption_container_preset_hover',
			[
				'label' => __('Preset', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => __('Light Gradient', 'thegem'),
					'dark' => __('Dark Gradient', 'thegem'),
					'solid' => __('Solid transparent', 'thegem'),
					'transparent' => __('Transparent', 'thegem'),
				],
				'conditions' => $conditions_caption_container,
			]
		);

		$control->add_responsive_control(
			'caption_container_height_hover',
			[
				'label' => __('Container Height', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links-wrapper .links' => 'height: {{SIZE}}%',
				],
				'conditions' => $conditions_caption_container,
			]
		);

		$control->add_group_control(
			Group_Control_Background_Light::get_type(),
			[
				'name' => 'caption_container_background_hover',
				'label' => __('Background Type', 'thegem'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links-wrapper .links',
				'conditions' => $conditions_caption_container,
			]
		);

		$control->add_responsive_control(
			'caption_container_alignment_hover',
			[
				'label' => __('Content Alignment', 'thegem'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'separator' => 'after',
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
				'conditions' => $conditions_caption_container,
			]
		);

		$caption_options = [
			'categories' => __('Categories', 'thegem'),
			'title' => __('Product Title', 'thegem'),
			'description' => __('Description', 'thegem'),
			'price' => __('Product Price', 'thegem'),
		];

		foreach ($caption_options as $ekey => $elem) {

			$term_show = [
				'name' => 'product_show_'.$ekey,
				'operator' => '=',
				'value' => 'yes',
			];

			if ($ekey == 'categories') {
				$term_show = [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'product_show_categories',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'product_show_categories_tablet',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'product_show_categories_mobile',
							'operator' => '=',
							'value' => 'yes',
						],
					],
				];
			}

			$condition = [
				'relation' => 'and',
				'terms' => [
					$term_show,
					[
						'name' => 'caption_position',
						'operator' => 'in',
						'value' => $ekey == 'description' ? ['hover'] : ['hover', 'image'],
					],
					[
						'name' => 'layout_type',
						'operator' => '!=',
						'value' => 'list',
					],
				]
			];
			$separator = 'before';
			$additional_selector = '';

			if ($ekey == 'categories') {
				$selector = '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .overlay .caption .categories,
				{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .overlay .caption .categories a';
				$separator = 'default';
			} else if ($ekey == 'title') {
				$selector = '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .caption .title, 
				{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .caption .title a, 
				{{WRAPPER}} .portfolio.extended-products-grid .product-variations';
				$additional_selector = '{{WRAPPER}} .portfolio.extended-products-grid .gem-attribute-selector.type-color .gem-attribute-options li:not(.selected),
 				{{WRAPPER}} .portfolio.extended-products-grid .gem-attribute-selector.type-label .gem-attribute-options li:not(.selected)';
			} else if ($ekey == 'description') {
				$selector = '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .overlay .caption .description';
			} else if ($ekey == 'price') {
				$selector = '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .caption .product-price .price,
				{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .empty-price';
			} else {
				$selector = '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .overlay .caption .' . $ekey;
			}

			$control->add_control(
				$ekey . '_header',
				[
					'label' => $elem,
					'type' => Controls_Manager::HEADING,
					'separator' => $separator,
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							$term_show,
							[
								'name' => 'layout_type',
								'operator' => '!=',
								'value' => 'list',
							],
						]
					]
				]
			);

			$control->add_group_control(Group_Control_Typography::get_type(),
				[
					'label' => __('Typography', 'thegem'),
					'name' => $ekey . '_typography',
					'selector' => $selector,
					'conditions' => $condition
				]
			);

			$control->add_control(
				$ekey . '_color',
				[
					'label' => __('Color', 'thegem'),
					'type' => Controls_Manager::COLOR,
					'label_block' => false,
					'selectors' => [
						$selector => 'color: {{VALUE}} !important;',
						$additional_selector => 'border-color: {{VALUE}}CC !important;',
					],
					'conditions' => $condition
				]
			);

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
							'product_show_title' => 'yes',
							'layout_type' => 'list',
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
							'product_show_title' => 'yes',
							'layout_type' => 'list',
						],
						'selectors' => [
							$selector => 'text-transform: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'title_weight',
					[
						'label' => __('Title Font weight', 'thegem'),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'' => __('Default', 'thegem'),
							'light' => __('Thin', 'thegem'),
						],
						'default' => '',
						'condition' => [
							'product_show_title' => 'yes',
							'layout_type' => 'list',
						],
					]
				);

			} else if ($ekey == 'description') {

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
							'product_show_description' => 'yes',
							'layout_type' => 'list',
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
						'default' => 2,
						'selectors' => [
							'{{WRAPPER}} .portfolio-item .caption .description .subtitle span' => 'max-height: initial; display: -webkit-box; -webkit-line-clamp: {{VALUE}}; line-clamp: {{VALUE}}; -webkit-box-orient: vertical;',
						],
						'condition' => [
							'product_show_description' => 'yes',
							'layout_type' => 'list',
						]
					]
				);

				$this->add_responsive_control(
					'description_max_width',
					[
						'label' => __('Description Max. Width', 'thegem'),
						'type' => Controls_Manager::SLIDER,
						'size_units' => ['px'],
						'range' => [
							'px' => [
								'min' => 1,
								'max' => 1000,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .portfolio-item .wrap > .caption .description' => 'display: inline-block; max-width: {{SIZE}}{{UNIT}};',
						],
						'condition' => [
							'product_show_description' => 'yes',
							'layout_type' => 'list',
						],
					]
				);

			} else if ($ekey == 'price') {

				$this->add_control(
					'price_preset',
					[
						'label' => 'Price Size Preset',
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
							'product_show_price' => 'yes',
							'layout_type' => 'list',
						],
					]
				);

			} else if ($ekey == 'categories') {

				$control->add_control(
					$ekey . '_background_color',
					[
						'label' => __('Background Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .caption .categories' => 'background-color: {{VALUE}} !important;',
						],
						'conditions' => $condition
					]
				);

			}

			$tabs_condition = [
				'relation' => 'and',
				'terms' => [
					$term_show,
					[
						'relation' => 'or',
						'terms' => [
							[
								'name' => 'caption_position',
								'operator' => '=',
								'value' => 'page',
							],
							[
								'name' => 'layout_type',
								'operator' => '=',
								'value' => 'list',
							],
						]
					]
				]
			];

			$control->start_controls_tabs($ekey . '_tabs', [
				'conditions' => $tabs_condition,
			]);

			if (!empty($control->states_list)) {
				foreach ((array)$control->states_list as $stkey => $stelem) {
					$state = '';
					$state_touch = '';
					if ($stkey == 'active') {
						continue;
					} else if ($stkey == 'hover') {
						$state = ':hover';
						$state_touch = '.hover-effect';
					}

					if ($ekey == 'categories') {
						if ($stkey == 'hover') {
							$selector = '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .caption .categories a' . $state;
						} else {
							$selector = '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .caption .categories,
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .caption .categories a';
						}
					} else if ($ekey == 'title') {
						$selector = '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .caption .title a' . $state . ', 
						{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product' . $state . ' .product-variations';
						$additional_selector = '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product' . $state . ' .gem-attribute-selector.type-color .gem-attribute-options li:not(.selected),
 						{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product' . $state . ' .gem-attribute-selector.type-label .gem-attribute-options li:not(.selected)';
					} else if ($ekey == 'description') {
						$selector = '{{WRAPPER}} .description' . $state;
					} else if ($ekey == 'price') {
						$selector = '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product' . $state . '  .caption .product-price .price';

						if ($stkey == 'hover') {
							$selector .= ', {{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product' . $state_touch . '  .caption .product-price .price';
						}
					}

					$control->start_controls_tab($ekey . '_tab_' . $stkey, [
						'label' => $stelem
					]);

					$control->add_group_control(Group_Control_Typography::get_type(),
						[
							'label' => __('Typography', 'thegem'),
							'name' => $ekey . '_typography_' . $stkey,
							'selector' => $selector
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
								$additional_selector => 'border-color: {{VALUE}}CC !important;',
							]
						]
					);

					$control->end_controls_tab();

				}
			}

			$control->end_controls_tabs();
		}

		$control->add_control(
			'rating_header',
			[
				'label' => __('Rating Stars', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_control(
			'rated_color',
			[
				'label' => __('Rated Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .portfolio-item .star-rating > span:before' => 'color: {{VALUE}} !important;',
				],
				'condition' => [
					'caption_position' => ['hover', 'image'],
				]
			]
		);

		$control->add_control(
			'base_color',
			[
				'label' => __('Base Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .portfolio-item .star-rating:before' => 'color: {{VALUE}} !important;',
				],
				'condition' => [
					'caption_position' => ['hover', 'image'],
				]
			]
		);

		$control->start_controls_tabs('rating_tabs', [
			'condition' => [
				'caption_position' => 'page',
			],
		]);

		$control->start_controls_tab('rating_tab_normal', [
			'label' => __('Normal', 'thegem'),
		]);

		$control->add_control(
			'rated_color_normal',
			[
				'label' => __('Rated Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .portfolio-item .star-rating > span:before' => 'color: {{VALUE}} !important;',
				]
			]
		);

		$control->add_control(
			'base_color_normal',
			[
				'label' => __('Base Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .portfolio-item .star-rating:before' => 'color: {{VALUE}} !important;',
				]
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab('rating_tab_hover', [
			'label' => __('Hover', 'thegem'),
			'condition' => ['caption_position' => 'page']
		]);

		$control->add_control(
			'rated_color_hover',
			[
				'label' => __('Rated Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .portfolio-item:hover .star-rating > span:before,
					{{WRAPPER}} .woocommerce .portfolio-item.hover-effect .star-rating > span:before' => 'color: {{VALUE}} !important;',
				],
				'condition' => ['caption_position' => 'page']
			]
		);

		$control->add_control(
			'base_color_hover',
			[
				'label' => __('Base Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .woocommerce .portfolio-item:hover .star-rating:before,
					{{WRAPPER}} .woocommerce .portfolio-item.hover-effect .star-rating:before' => 'color: {{VALUE}} !important;',
				],
				'condition' => ['caption_position' => 'page']
			]
		);

		$control->end_controls_tab();

		$control->end_controls_tabs();

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
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'caption_position',
							'operator' => '=',
							'value' => 'page',
						],
						[
							'name' => 'layout_type',
							'operator' => '=',
							'value' => 'list',
						],
					]
				]
			]
		);

		$control->add_control(
			'caption_container_preset',
			[
				'label' => __('Preset', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'default' => 'transparent',
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
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.extended-products-grid.caption-position-page .portfolio-item .wrap' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius:{{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
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
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption' => 'text-align: {{VALUE}} !important',
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .star-rating' => 'margin-{{VALUE}}: 0',
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-rating .empty-rating:before' => 'margin-{{VALUE}}: 0',
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .categories' => 'margin-{{VALUE}}: 0',
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-bottom' => 'margin-{{VALUE}}: 0',
				],
			]
		);

		$control->start_controls_tabs('caption_container_tabs');

		$control->start_controls_tab('caption_container_tab_normal', ['label' => __('Normal', 'thegem')]);

		$control->add_group_control(
			Group_Control_Background_Light::get_type(),
			[
				'name' => 'caption_container_background_page_normal',
				'label' => __('Background Type', 'thegem'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption',
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'caption_container_border_normal',
				'label' => __('Border', 'thegem'),
//				'fields_options' => [
//					'color' => [
//						'default' => '#dfe5e8',
//					],
//				],
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption',
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab('caption_container_tab_hover', ['label' => __('Hover', 'thegem')]);

		$control->add_group_control(
			Group_Control_Background_Light::get_type(),
			[
				'name' => 'caption_container_background_page_hover',
				'label' => __('Background Type', 'thegem'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item:hover .wrap > .caption,
				{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.hover-effect .wrap > .caption',
			]
		);

		$control->add_control(
			'caption_container_border_color_hover',
			[
				'label' => __('Border Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item:hover .wrap > .caption,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.hover-effect .wrap > .caption' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'caption_container_border_normal_border!' => '',
				],
			]
		);

		$control->end_controls_tab();

		$control->end_controls_tabs();

		$control->add_control(
			'spacing_title_categories',
			[
				'label' => 'Categories',
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_responsive_control(
			'spacing_categories_top',
			[
				'label' => __('Top Spacing', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .categories' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$control->add_control(
			'spacing_title_header',
			[
				'label' => 'Product Title',
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'spacing_title',
			[
				'label' => __('Top and Bottom Spacing', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'allowed_dimensions' => 'vertical',
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_control(
			'spacing_price_header',
			[
				'label' => 'Product Price',
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'spacing_price',
			[
				'label' => __('Top and Bottom Spacing', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'allowed_dimensions' => 'vertical',
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$control->add_control(
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
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .product-info .product-rating .empty-rating:before' => 'border-width: {{SIZE}}{{UNIT}}',
				]
			]
		);

		$control->start_controls_tabs('spacing_separator_tabs');

		$control->start_controls_tab('spacing_separator_tab_normal', ['label' => __('Normal', 'thegem')]);

		$control->add_control(
			'spacing_separator_color_normal',
			[
				'label' => __('Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .product-info .product-rating .empty-rating:before' => 'border-color: {{VALUE}};',
				],
			]
		);

		$control->add_responsive_control(
			'spacing_separator_size_normal',
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
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .product-info .product-rating .empty-rating:before' => 'width: {{SIZE}}{{UNIT}}',
				]
			]
		);

		$control->end_controls_tab();

		$control->start_controls_tab('spacing_separator_tab_hover', ['label' => __('Hover', 'thegem')]);

		$control->add_control(
			'spacing_separator_color_hover',
			[
				'label' => __('Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item:hover .product-info .product-rating .empty-rating:before,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.hover-effect .product-info .product-rating .empty-rating:before' => 'border-color: {{VALUE}};',
				],
			]
		);

		$control->add_responsive_control(
			'spacing_separator_size_hover',
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
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item:hover .product-info .product-rating .empty-rating:before,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.hover-effect .product-info .product-rating .empty-rating:before' => 'width: {{SIZE}}{{UNIT}}',
				]
			]
		);

		$control->end_controls_tab();

		$control->end_controls_tabs();

		$control->end_controls_section();
	}

	/**
	 * Icons Style
	 * @access protected
	 */
	protected function icons_style($control) {

		$control->start_controls_section(
			'icons_style',
			[
				'label' => __('Icons Style', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$control->add_responsive_control(
			'icons_size',
			[
				'label' => __('Size', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon' => 'width: {{SIZE}}{{UNIT}}!important; height: {{SIZE}}{{UNIT}}!important; line-height: {{SIZE}}{{UNIT}} !important;',

					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .product-bottom.on-page-caption a.icon' => 'width: {{SIZE}}{{UNIT}}!important; height: {{SIZE}}{{UNIT}}!important; border-radius: calc({{SIZE}}{{UNIT}}/2)!important;',

					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon i, 
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon:before' => 'font-size: {{SIZE}}{{UNIT}}!important;',

					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .product-bottom.on-page-caption a.icon i, 
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .product-bottom.on-page-caption a.icon:before,
					{{WRAPPER}} .portfolio.extended-products-grid.hover-slide .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon i, 
					{{WRAPPER}} .portfolio.extended-products-grid.hover-slide .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon:before,
					{{WRAPPER}} .portfolio.extended-products-grid.hover-fade .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon i, 
					{{WRAPPER}} .portfolio.extended-products-grid.hover-fade .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon:before,
					{{WRAPPER}} .portfolio.extended-products-grid.hover-zooming-blur .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon i, 
					{{WRAPPER}} .portfolio.extended-products-grid.hover-zooming-blur .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon:before, 
					{{WRAPPER}} .portfolio.extended-products-grid.hover-gradient .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon i, 
					{{WRAPPER}} .portfolio.extended-products-grid.hover-gradient .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon:before' => 'font-size: calc({{SIZE}}{{UNIT}}/2) !important;',

					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon svg' => 'width: {{SIZE}}{{UNIT}}!important; height: {{SIZE}}{{UNIT}}!important;',

					'{{WRAPPER}} .portfolio.extended-products-grid.hover-zooming-blur .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon svg,
					{{WRAPPER}} .portfolio.extended-products-grid.hover-gradient .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon svg,
					{{WRAPPER}} .portfolio.extended-products-grid.hover-slide .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon svg,
					{{WRAPPER}} .portfolio.extended-products-grid.hover-fade .portfolio-item .image .overlay .links .portfolio-icons.product-bottom a.icon svg' => 'width: calc({{SIZE}}{{UNIT}}/2)!important; height: calc({{SIZE}}{{UNIT}}/2)!important;',
				],
			]
		);

		$control->add_responsive_control(
			'icons_spacing',
			[
				'label' => __('Spacing Between', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .product-bottom.on-page-caption a.icon' => 'margin: 0 calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .portfolio-icons .portfolio-icons-inner' => 'margin-right: -{{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .portfolio-icons .portfolio-icons-inner > .icon' => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .product-bottom.on-page-caption .icons-top .icon' => 'margin: 0 0 {{SIZE}}{{UNIT}} 0',
				],
			]
		);


		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icons_border_type',
				'label' => __('Border Type', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .product-bottom.on-page-caption a.icon,
				{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .portfolio-icons .portfolio-icons-inner a.icon',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'caption_position',
							'operator' => '=',
							'value' => 'page',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'caption_position',
									'operator' => '=',
									'value' => 'hover',
								],
								[
									'name' => 'image_hover_effect_hover',
									'operator' => 'in',
									'value' => ['zooming-blur', 'gradient', 'slide', 'fade'],
								],
							]
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'caption_position',
									'operator' => '=',
									'value' => 'image',
								],
								[
									'name' => 'image_hover_effect_image',
									'operator' => 'in',
									'value' => ['gradient', 'slide', 'fade'],
								],
							]

						],
					],
				],
			]
		);
		$control->remove_control('icons_border_type_color');

		$control->start_controls_tabs('icons_style_tabs');
		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {
				$condition = [];
				$state = '';
				if ($stkey == 'active') {
					continue;
				} else if ($stkey == 'hover') {
					$state = ':hover';
				}

				$control->start_controls_tab('icons_tab_' . $stkey, [
					'label' => $stelem,
					'condition' => $condition
				]);

				$control->add_control(
					'icons_color_' . $stkey,
					[
						'label' => __('Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .product-bottom.on-page-caption a.icon' . $state . ',
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .portfolio-icons .portfolio-icons-inner a.icon' . $state => 'color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'icons_background_color_' . $stkey,
					[
						'label' => __('Background Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .product-bottom.on-page-caption a.icon' . $state . ',
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .portfolio-icons .portfolio-icons-inner a.icon' . $state => 'background-color: {{VALUE}};',
						],
						'conditions' => [
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'caption_position',
									'operator' => '=',
									'value' => 'page',
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'caption_position',
											'operator' => '=',
											'value' => 'hover',
										],
										[
											'name' => 'image_hover_effect_hover',
											'operator' => 'in',
											'value' => ['zooming-blur', 'gradient', 'slide', 'fade']
										],
									]
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'caption_position',
											'operator' => '=',
											'value' => 'image',
										],
										[
											'name' => 'image_hover_effect_image',
											'operator' => 'in',
											'value' => ['gradient', 'slide', 'fade']
										],
									]

								],
							],
						],
					]
				);

				$control->add_control(
					'icons_border_color_' . $stkey,
					[
						'label' => __('Border Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .product-bottom.on-page-caption a.icon' . $state . ',
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .portfolio-icons .portfolio-icons-inner a.icon' . $state => 'border-color: {{VALUE}};',
						],
						'condition' => [
							'icons_border_type_border!' => '',
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
	 * Buttons Style
	 * @access protected
	 */
	protected function buttons_style($control) {

		$control->start_controls_section(
			'buttons_style',
			[
				'label' => __('Buttons Style', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$control->add_control(
			'buttons_general_header',
			[
				'label' => __('General Settings', 'thegem'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$control->add_responsive_control(
			'buttons_top_spacing',
			[
				'label' => __('Top Spacing', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption .add_to_cart_button.type_button,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .actions .button' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
				'condition' => ['caption_position!' => 'page'],
			]
		);

		$control->add_responsive_control(
			'buttons_border_radius',
			[
				'label' => __('Border Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap .product-bottom.on-page-caption .add_to_cart_button.type_button,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption .add_to_cart_button.type_button,
					.thegem-popup-notification-wrap[data-style-uid="{{ID}}"] .thegem-popup-notification,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .actions .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'buttons_border_type',
				'label' => __('Border Type', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-bottom .add_to_cart_button.type_button,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption .add_to_cart_button.type_button,
					.thegem-popup-notification-wrap[data-style-uid="{{ID}}"] .thegem-popup-notification,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .actions .button',
			]
		);
		$control->remove_control('buttons_border_type_color');

		$control->add_responsive_control(
			'buttons_text_padding',
			[
				'label' => __('Text Padding', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-bottom .add_to_cart_button.type_button,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption .add_to_cart_button.type_button,
					.thegem-popup-notification-wrap[data-style-uid="{{ID}}"] .thegem-popup-notification,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .actions .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$control->add_responsive_control(
			'buttons_icon_alignment',
			[
				'label' => __('Icon Alignment', 'thegem'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
					'left' => 'flex-direction: row;',
					'right' => 'flex-direction: row-reverse;'
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-bottom .add_to_cart_button.type_button,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption .add_to_cart_button.type_button' => '{{VALUE}}',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'product_show_add_to_cart',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'cart_button_show_icon',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'layout_type',
									'operator' => '=',
									'value' => 'list',
								],
								[
									'name' => 'add_to_cart_type',
									'operator' => '=',
									'value' => 'button',
								],
							]
						],
					],
				],
			]
		);

		$control->add_responsive_control(
			'buttons_icon_spacing',
			[
				'label' => __('Icon Spacing', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-bottom .add_to_cart_button.type_button .space,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption a.add_to_cart_button.type_button .space' => 'width: calc({{SIZE}}{{UNIT}} - 5px)',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'product_show_add_to_cart',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'cart_button_show_icon',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'layout_type',
									'operator' => '=',
									'value' => 'list',
								],
								[
									'name' => 'add_to_cart_type',
									'operator' => '=',
									'value' => 'button',
								],
							]
						],
					],
				],
			]
		);

		$control->add_group_control(Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'button_text_typography',
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-bottom .add_to_cart_button.type_button,
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption a.add_to_cart_button,
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .actions .button',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'product_show_add_to_cart',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'cart_button_show_icon',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'layout_type',
									'operator' => '=',
									'value' => 'list',
								],
								[
									'name' => 'add_to_cart_type',
									'operator' => '=',
									'value' => 'button',
								],
							]
						],
					],
				]
			]
		);

		$control->add_control(
			'button_cart_header',
			[
				'label' => __('Add to Cart Button Colors', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'product_show_add_to_cart',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'cart_button_show_icon',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'layout_type',
									'operator' => '=',
									'value' => 'list',
								],
								[
									'name' => 'add_to_cart_type',
									'operator' => '=',
									'value' => 'button',
								],
							]
						],
					],
				]
			]
		);

		$control->start_controls_tabs('button_cart_tabs', [
			'conditions' => [
				'relation' => 'and',
				'terms' => [
					[
						'name' => 'product_show_add_to_cart',
						'operator' => '=',
						'value' => 'yes',
					],
					[
						'name' => 'cart_button_show_icon',
						'operator' => '=',
						'value' => 'yes',
					],
					[
						'relation' => 'or',
						'terms' => [
							[
								'name' => 'layout_type',
								'operator' => '=',
								'value' => 'list',
							],
							[
								'name' => 'add_to_cart_type',
								'operator' => '=',
								'value' => 'button',
							],
						]
					],
				],
			],
		]);

		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {
				$state = '';
				if ($stkey == 'active') {
					continue;
				} else if ($stkey == 'hover') {
					$state = ':hover';
				}

				$control->start_controls_tab('button_cart_tab_' . $stkey, [
					'label' => $stelem,
				]);

				$control->add_control(
					'button_cart_color_' . $stkey,
					[
						'label' => __('Text Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-bottom .add_to_cart_button.type_button.product_type_simple' . $state . ',
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption a.add_to_cart_button.product_type_simple' . $state . ',
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .actions .button' . $state => 'color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'button_cart_background_color_' . $stkey,
					[
						'label' => __('Background Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-bottom .add_to_cart_button.type_button.product_type_simple' . $state . ',
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption a.add_to_cart_button.product_type_simple' . $state . ',
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .actions .button' . $state => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'button_cart_border_color_' . $stkey,
					[
						'label' => __('Border Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-bottom .add_to_cart_button.type_button.product_type_simple' . $state . ',
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption a.add_to_cart_button.product_type_simple' . $state . ',
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .actions .button' . $state => 'border-color: {{VALUE}};',
						],
					]
				);

				$control->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_cart_shadow_' . $stkey,
						'label' => __('Shadow', 'thegem'),
						'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-bottom .add_to_cart_button.type_button.product_type_simple' . $state . ',
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption a.add_to_cart_button.product_type_simple' . $state . ',
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .actions .button' . $state,
					]
				);

				$control->end_controls_tab();

			}
		}

		$control->end_controls_tabs();

		$control->add_control(
			'button_options_header',
			[
				'label' => __('Select Options Button Colors', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'product_show_add_to_cart',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'cart_button_show_icon',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'layout_type',
									'operator' => '=',
									'value' => 'list',
								],
								[
									'name' => 'add_to_cart_type',
									'operator' => '=',
									'value' => 'button',
								],
							]
						],
					],
				]
			]
		);

		$control->start_controls_tabs('button_options_tabs', [
			'conditions' => [
				'relation' => 'and',
				'terms' => [
					[
						'name' => 'product_show_add_to_cart',
						'operator' => '=',
						'value' => 'yes',
					],
					[
						'name' => 'cart_button_show_icon',
						'operator' => '=',
						'value' => 'yes',
					],
					[
						'relation' => 'or',
						'terms' => [
							[
								'name' => 'layout_type',
								'operator' => '=',
								'value' => 'list',
							],
							[
								'name' => 'add_to_cart_type',
								'operator' => '=',
								'value' => 'button',
							],
						]
					],
				],
			]
		]);

		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {
				$state = '';
				if ($stkey == 'active') {
					continue;
				} else if ($stkey == 'hover') {
					$state = ':hover';
				}

				$control->start_controls_tab('button_options_tab_' . $stkey, [
					'label' => $stelem,
				]);

				$control->add_control(
					'button_options_color_' . $stkey,
					[
						'label' => __('Text Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-bottom .add_to_cart_button.type_button.product_type_variable' . $state . ',
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption a.add_to_cart_button.product_type_variable' . $state => 'color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'button_options_background_color_' . $stkey,
					[
						'label' => __('Background Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-bottom .add_to_cart_button.type_button.product_type_variable' . $state . ',
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption a.add_to_cart_button.product_type_variable' . $state => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'button_options_border_color_' . $stkey,
					[
						'label' => __('Border Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-bottom .add_to_cart_button.type_button.product_type_variable' . $state . ',
							{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption a.add_to_cart_button.product_type_variable' . $state => 'border-color: {{VALUE}};',
						],
					]
				);

				$control->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_options_shadow_' . $stkey,
						'label' => __('Shadow', 'thegem'),
						'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .wrap > .caption .product-bottom .add_to_cart_button.type_button.product_type_variable' . $state . ',
						{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item .image .overlay .links .caption a.add_to_cart_button.product_type_variable' . $state,
					]
				);

				$control->end_controls_tab();

			}
		}

		$control->end_controls_tabs();


		$control->end_controls_section();
	}

	/**
	 * Filter Style (Standard)
	 * @access protected
	 */
	protected function filter_buttons_standard_style($control) {

		$control->start_controls_section(
			'filter_buttons_standard_style',
			[
				'label' => __('Filter Style (Standard)', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_thegem',
					'filters_style' => 'standard',
				]
			]
		);

		$control->add_group_control(Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'filter_buttons_standard_typography',
				'selector' => '{{WRAPPER}} .portfolio-filters-list.style-standard,
				{{WRAPPER}} .portfolio-filters-list .portfolio-show-filters-button,
				{{WRAPPER}} .portfolio.extended-products-grid .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-form input',
			]
		);

		$control->add_control(
			'filter_buttons_standard_color',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list.style-standard .portfolio-filter-item .name,
					{{WRAPPER}} .portfolio-filters-list .portfolio-show-filters-button' => 'color: {{VALUE}};',
				],
			]
		);

		$control->add_responsive_control(
			'filter_buttons_standard_border_radius',
			[
				'label' => __('Border Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list.style-standard .portfolio-filter-item .name,
					{{WRAPPER}} .portfolio-filters-list .portfolio-show-filters-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'filter_buttons_standard_border_type',
				'label' => __('Border Type', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio-filters-list.style-standard .portfolio-filter-item .name,
				{{WRAPPER}} .portfolio-filters-list .portfolio-show-filters-button',
			]
		);

		$control->add_responsive_control(
			'filter_buttons_standard_text_padding',
			[
				'label' => __('Text Padding', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list.style-standard .portfolio-filter-item .name,
					{{WRAPPER}} .portfolio-filters-list .portfolio-show-filters-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_responsive_control(
			'filter_buttons_standard_bottom_spacing',
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
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-top-panel' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				]
			]
		);

		$control->add_responsive_control(
			'filter_buttons_standard_space_between',
			[
				'label' => __('Space Between', 'thegem'),
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
					'{{WRAPPER}} .portfolio-filters-list.style-standard .portfolio-filter-item' => 'margin-right: {{SIZE}}{{UNIT}}',
				]
			]
		);

		$control->add_control(
			'filter_buttons_standard_alignment',
			[
				'label' => __('Alignment', 'thegem'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => __('Left', 'thegem'),
					'right' => __('Right', 'thegem'),
					'center' => __('Center', 'thegem'),
				],
			]
		);

		$control->add_control(
			'filter_buttons_standard_dropdown_header',
			[
				'label' => __('Dropdown', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_group_control(
			Group_Control_Background_Light::get_type(),
			[
				'name' => 'filter_buttons_standard_dropdown_background',
				'label' => __('Background Type', 'thegem'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .portfolio-filters-list.style-standard:not(.single-filter) .portfolio-filter-item .portfolio-filter-item-list',
			]
		);

		$control->add_responsive_control(
			'filter_buttons_standard_dropdown_border_radius',
			[
				'label' => __('Border Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list.style-standard:not(.single-filter) .portfolio-filter-item .portfolio-filter-item-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'filter_buttons_standard_dropdown_shadow',
				'label' => __('Shadow', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio-filters-list.style-standard:not(.single-filter) .portfolio-filter-item .portfolio-filter-item-list',
			]
		);

		$control->start_controls_tabs('filter_buttons_standard_dropdown_tabs');

		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {
				$state = '';
				$addition_selector = '';
				$addition_selector_normal = '';
				if ($stkey == 'active') {
					$state = '.active';
					$addition_selector = '{{WRAPPER}} .portfolio-filters-list.style-standard .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-range,
					{{WRAPPER}} .portfolio-filters-list.style-standard .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-handle + span,
					{{WRAPPER}} .portfolio-filters-list.style-standard .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-handle';
				} else if ($stkey == 'hover') {
					$state = ':hover';
				} else {
					$addition_selector_normal = ', {{WRAPPER}} .portfolio-filters-list.style-standard .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount, 
					{{WRAPPER}} .portfolio-filters-list.style-standard .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount .slider-amount-text';
				}

				$control->start_controls_tab('filter_buttons_standard_dropdown_tab_' . $stkey, ['label' => $stelem]);

				$control->add_control(
					'filter_buttons_standard_dropdown_text_color_' . $stkey,
					[
						'label' => __('Text Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-filters-list.style-standard .portfolio-filter-item .portfolio-filter-item-list ul li a' . $state . $addition_selector_normal => 'color: {{VALUE}};',
							$addition_selector => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'filter_buttons_standard_dropdown_counts_color_' . $stkey,
					[
						'label' => __('Counts Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-filters-list.style-standard .portfolio-filter-item .portfolio-filter-item-list ul li a' . $state . ' .count' => 'color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'filter_buttons_standard_dropdown_counts_background_color_' . $stkey,
					[
						'label' => __('Counts Label Background', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-filters-list.style-standard .portfolio-filter-item .portfolio-filter-item-list ul li a' . $state . ' .count' => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'filter_buttons_standard_dropdown_price_range_background_color_' . $stkey,
					[
						'label' => __('Price Range Background', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-filters-list.style-standard .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount' . $state => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->end_controls_tab();
			}
		}

		$control->end_controls_tabs();

		$control->add_control(
			'filter_buttons_standard_selected_header',
			[
				'label' => __('Selected Filters', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_group_control(Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'filter_buttons_standard_selected_typography',
				'selector' => '{{WRAPPER}} .portfolio-selected-filters .portfolio-filter-item',
			]
		);

		$control->add_responsive_control(
			'filter_buttons_standard_selected_border_radius',
			[
				'label' => __('Border Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-selected-filters .portfolio-filter-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'filter_buttons_standard_selected_border_type',
				'label' => __('Border Type', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio-selected-filters .portfolio-filter-item',
			]
		);

		$control->add_responsive_control(
			'filter_buttons_standard_selected_text_padding',
			[
				'label' => __('Text Padding', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-selected-filters .portfolio-filter-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->start_controls_tabs('filter_buttons_standard_selected_tabs');

		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {
				$state = '';
				if ($stkey == 'active') {
					continue;
				} else if ($stkey == 'hover') {
					$state = ':hover';
				}

				$control->start_controls_tab('filter_buttons_standard_selected_tab_' . $stkey, ['label' => $stelem]);

				$control->add_control(
					'filter_buttons_standard_selected_text_color_' . $stkey,
					[
						'label' => __('Text Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-selected-filters .portfolio-filter-item' . $state => 'color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'filter_buttons_standard_selected_background_color_' . $stkey,
					[
						'label' => __('Background Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-selected-filters .portfolio-filter-item' . $state => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->end_controls_tab();
			}
		}

		$control->end_controls_tabs();

		$control->add_control(
			'filter_buttons_standard_search_header',
			[
				'label' => __('Product Search', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_control(
			'filter_buttons_standard_search_icon_color',
			[
				'label' => __('Icon Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-button,
					{{WRAPPER}} .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter .portfolio-search-filter-button' => 'color: {{VALUE}};',
				],
			]
		);

		$control->add_control(
			'filter_buttons_standard_search_icon_background_color',
			[
				'label' => __('Icon Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$control->add_control(
			'filter_buttons_standard_search_icon_border_color',
			[
				'label' => __('Icon Border Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$control->add_responsive_control(
			'filter_buttons_standard_search_input_border_radius',
			[
				'label' => __('Input Border Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-form input,
					{{WRAPPER}} .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_control(
			'filter_buttons_standard_search_input_color',
			[
				'label' => __('Input Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-form input,
					{{WRAPPER}} .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input' => 'color: {{VALUE}};',
				]
			]
		);

		$control->add_control(
			'filter_buttons_standard_search_input_background_color',
			[
				'label' => __('Input Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-form input,
					{{WRAPPER}} .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$control->end_controls_section();
	}

	/**
	 * Filter Style (Sidebar)
	 * @access protected
	 */
	protected function filter_buttons_hidden_style($control) {

		$control->start_controls_section(
			'filter_buttons_style_hidden',
			[
				'label' => __('Filter Style (Sidebar)', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'product_show_filter',
							'operator' => '=',
							'value' => 'yes',
						], [
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_woo',
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'filters_type',
											'operator' => '=',
											'value' => 'filter_thegem',
										], [
											'name' => 'filters_style',
											'operator' => '!=',
											'value' => 'tabs',
										],
									]
								]
							],
						],
					]
				],
			]
		);

		$control->add_control(
			'filter_buttons_responsive_sidebar_header',
			[
				'label' => __('Sidebar', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'filters_style' => 'sidebar',
				]
			]
		);


		$control->add_responsive_control(
			'filter_buttons_hidden_sidebar_separator_width',
			[
				'label' => __('Separator Width', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 4,
					],
				],
				'default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filter-item, 
					{{WRAPPER}} .portfolio-filters-list.style-sidebar .portfolio-filter-item, 
					{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filter-item,
					{{WRAPPER}} .portfolio-filters-list.style-hidden .widget-area .widget, 
					{{WRAPPER}} .portfolio-filters-list.style-sidebar .widget-area .widget, 
					{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .widget-area .widget' => 'border-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$control->add_control(
			'filter_buttons_hidden_sidebar_separator_color',
			[
				'label' => __('Separator Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filter-item, 
					{{WRAPPER}} .portfolio-filters-list.style-sidebar .portfolio-filter-item, 
					{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filter-item,
					{{WRAPPER}} .portfolio-filters-list.style-hidden .widget-area .widget, 
					{{WRAPPER}} .portfolio-filters-list.style-sidebar .widget-area .widget, 
					{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .widget-area .widget' => 'border-color: {{VALUE}};',
				],
			]
		);

		$control->add_group_control(Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'filter_buttons_hidden_sidebar_typography',
				'selector' => '{{WRAPPER}} .portfolio-filters-list .portfolio-filter-item, 
				{{WRAPPER}} .portfolio-filters-list .portfolio-filter-item ul li a span.count, 
				{{WRAPPER}} .portfolio-filters-list .widget-area .widget, 
				{{WRAPPER}} .portfolio-filters-list .widget-area .widget.widget_product_categories > ul, 
				{{WRAPPER}} .portfolio-filters-list .widget_product_categories ul li a, 
				{{WRAPPER}} .portfolio-filters-list .widget_product_categories ul li span.count, 
				{{WRAPPER}} .portfolio-filters-list .widget_layered_nav ul li span.count,
				{{WRAPPER}} .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input,
				{{WRAPPER}} .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input::placeholder,
				{{WRAPPER}} .portfolio-filters-list .widget_product_search input,
				{{WRAPPER}} .portfolio-filters-list .widget_product_search input::placeholder',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				],
			]
		);

		$control->add_control(
			'filter_buttons_hidden_sidebar_typography_note',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __('Filter titles inherit style settings from sidebar widgets and can be adjusted in theme options under "Typography -> Elements -> Sidebar Widgets"', 'thegem'),
				'content_classes' => 'elementor-descriptor',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				],
			]
		);

		$control->start_controls_tabs('filter_buttons_hidden_sidebar_selected_tabs', [
			'conditions' => [
				'relation' => 'and',
				'terms' => [
					[
						'name' => 'filters_type',
						'operator' => '=',
						'value' => 'filter_thegem',
					], [
						'name' => 'filters_style',
						'operator' => '!=',
						'value' => 'standard',
					],
				]
			],
		]);

		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {
				$state = '';
				$addition_selector = '';
				$addition_selector_normal = '';
				if ($stkey == 'active') {
					$state = '.active';
					$addition_selector = '{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-range,
					{{WRAPPER}} .portfolio-filters-list.style-sidebar .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-range,
					{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-range,
					{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-handle + span,
					{{WRAPPER}} .portfolio-filters-list.style-sidebar .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-handle + span,
					{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-handle + span,
					{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-handle,
					{{WRAPPER}} .portfolio-filters-list.style-sidebar .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-handle,
					{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-handle';
				} else if ($stkey == 'hover') {
					$state = ':hover';
				} else {
					$addition_selector_normal = ', {{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount, 
					{{WRAPPER}} .portfolio-filters-list.style-sidebar .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount, 
					{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount, 
					{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount .slider-amount-text,
					{{WRAPPER}} .portfolio-filters-list.style-sidebar .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount .slider-amount-text,
					{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount .slider-amount-text';
				}

				$control->start_controls_tab('filter_buttons_hidden_sidebar_selected_tab_' . $stkey, ['label' => $stelem]);

				$control->add_control(
					'filter_buttons_hidden_sidebar_text_color_' . $stkey,
					[
						'label' => __('Text Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filter-item .portfolio-filter-item-list ul li a' . $state . ', 
							{{WRAPPER}} .portfolio-filters-list.style-sidebar .portfolio-filter-item .portfolio-filter-item-list ul li a' . $state . ', 
							{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filter-item .portfolio-filter-item-list ul li a' . $state . $addition_selector_normal => 'color: {{VALUE}};',
							$addition_selector => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'filter_buttons_hidden_sidebar_counts_color_' . $stkey,
					[
						'label' => __('Counts Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filter-item .portfolio-filter-item-list ul li a' . $state . ' .count, 
							{{WRAPPER}} .portfolio-filters-list.style-sidebar .portfolio-filter-item .portfolio-filter-item-list ul li a' . $state . ' .count, 
							{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filter-item .portfolio-filter-item-list ul li a' . $state . ' .count' => 'color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'filter_buttons_hidden_sidebar_counts_background_color_' . $stkey,
					[
						'label' => __('Counts Label Background', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filter-item .portfolio-filter-item-list ul li a' . $state . ' .count, 
							{{WRAPPER}} .portfolio-filters-list.style-sidebar .portfolio-filter-item .portfolio-filter-item-list ul li a' . $state . ' .count, 
							{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filter-item .portfolio-filter-item-list ul li a' . $state . ' .count' => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'filter_buttons_hidden_sidebar_range_background_color_' . $stkey,
					[
						'label' => __('Price Range Background', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount' . $state . ',
							{{WRAPPER}} .portfolio-filters-list.style-sidebar .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount' . $state . ',
							{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount' . $state => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->end_controls_tab();
			}
		}

		$control->end_controls_tabs();

		$control->add_control(
			'filter_buttons_show_filters_header',
			[
				'label' => __('Show Filters', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_woo',
								], [
									'name' => 'woo_filters_style',
									'operator' => '=',
									'value' => 'hidden',
								],
							]
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '=',
									'value' => 'hidden',
								],
							]
						]
					],
				]
			]
		);

		$control->add_control(
			'filter_buttons_show_responsive_filters_header',
			[
				'label' => __('Responsive Mode - Show Filters', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_woo',
								], [
									'name' => 'woo_filters_style',
									'operator' => '!=',
									'value' => 'hidden',
								],
							]
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'hidden',
								],
							]
						]
					],
				]
			]
		);

		$control->add_control(
			'filter_buttons_hidden_show_icon', [
				'label' => __('Show Icon', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
			]
		);

		$control->add_control(
			'filter_buttons_hidden_color',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list .portfolio-show-filters-button' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$control->add_group_control(Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'filter_buttons_hidden_typography',
				'selector' => '{{WRAPPER}} .portfolio-filters-list .portfolio-show-filters-button',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$control->add_responsive_control(
			'filter_buttons_hidden_border_radius',
			[
				'label' => __('Border Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list .portfolio-show-filters-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'filter_buttons_hidden_border_type',
				'label' => __('Border Type', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio-filters-list .portfolio-show-filters-button',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$control->add_responsive_control(
			'filter_buttons_hidden_text_padding',
			[
				'label' => __('Text Padding', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list .portfolio-show-filters-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$control->add_responsive_control(
			'filter_buttons_hidden_bottom_spacing',
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
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-top-panel' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				]
			]
		);

		$control->add_control(
			'filter_buttons_hidden_sidebar_header',
			[
				'label' => __('Sidebar', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_woo',
								], [
									'name' => 'woo_filters_style',
									'operator' => '=',
									'value' => 'hidden',
								],
							]
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '=',
									'value' => 'hidden',
								],
							]
						]
					],
				]
			]
		);

		$control->add_control(
			'filter_buttons_responsive_hidden_sidebar_header',
			[
				'label' => __('Responsive Mode - Hidden Sidebar', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_woo',
								], [
									'name' => 'woo_filters_style',
									'operator' => '!=',
									'value' => 'hidden',
								],
							]
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'hidden',
								],
							]
						]
					],
				]
			]
		);

		$control->add_group_control(Group_Control_Typography::get_type(),
			[
				'label' => __('Sidebar Title Typography', 'thegem'),
				'name' => 'filter_buttons_hidden_sidebar_title_typography',
				'selector' => '{{WRAPPER}} .portfolio-filters-list .portfolio-filters-area h2',
				'condition' => [
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$control->add_control(
			'filter_buttons_hidden_sidebar_title_color',
			[
				'label' => __('Sidebar Title Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list .portfolio-filters-area h2' => 'color: {{VALUE}};',
				],
				'condition' => [
					'filters_type' => 'filter_thegem',
				],
			]
		);

		$control->add_group_control(Group_Control_Typography::get_type(),
			[
				'label' => __('"Filter by" Typography', 'thegem'),
				'name' => 'filter_buttons_hidden_sidebar_filterby_typography',
				'selector' => '{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filters-area-scrollable .widget-title, 
				{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filters-area-scrollable .widget-title,
				{{WRAPPER}} .portfolio-filters-list.style-sidebar .portfolio-filters-area-scrollable .widget-title',
			]
		);

		$control->add_control(
			'filter_buttons_hidden_sidebar_filterby_color',
			[
				'label' => __('"Filter by" Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filters-area-scrollable .widget-title, 
				{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filters-area-scrollable .widget-title,
				{{WRAPPER}} .portfolio-filters-list.style-sidebar .portfolio-filters-area-scrollable .widget-title' => 'color: {{VALUE}};',
				],
			]
		);

		$control->add_responsive_control(
			'filter_buttons_hidden_sidebar_width',
			[
				'label' => __('Sidebar Width', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filters-outer .portfolio-filters-area,
					 {{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filters-outer .portfolio-filters-area,
					{{WRAPPER}} .portfolio-filters-list.style-sidebar-mobile .portfolio-filters-outer .portfolio-filters-area' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Background_Light::get_type(),
			[
				'name' => 'filter_buttons_hidden_sidebar_background',
				'label' => __('Background Type', 'thegem'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filters-outer .portfolio-filters-area,
				{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filters-outer .portfolio-filters-area,
				{{WRAPPER}} .portfolio-filters-list.style-sidebar-mobile .portfolio-filters-outer .portfolio-filters-area',
			]
		);

		$control->add_control(
			'filter_buttons_hidden_sidebar_overlay_color',
			[
				'label' => __('Overlay Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filters-outer,
					{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filters-outer,
					{{WRAPPER}} .portfolio-filters-list.style-sidebar-mobile .portfolio-filters-outer' => 'background-color: {{VALUE}};',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'filter_buttons_hidden_sidebar_overlay_shadow',
				'label' => __('Shadow', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio-filters-list.style-hidden .portfolio-filters-outer .portfolio-filters-area,
				{{WRAPPER}} .portfolio-filters-list.style-standard-mobile .portfolio-filters-outer .portfolio-filters-area,
				{{WRAPPER}} .portfolio-filters-list.style-sidebar-mobile .portfolio-filters-outer .portfolio-filters-area',
			]
		);

		$control->add_control(
			'filter_buttons_hidden_sidebar_close_icon_color',
			[
				'label' => __('“Close” Icon Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list .portfolio-close-filters' => 'color: {{VALUE}};',
				],
			]
		);

		$control->add_control(
			'filter_buttons_hidden_selected_header',
			[
				'label' => __('Selected Filters', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$this->add_control(
			'duplicate_selected_in_sidebar', [
				'label' => __('Duplicate in Sidebar', 'thegem'),
				'default' => 'yes',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'thegem'),
				'label_off' => __('Off', 'thegem'),
				'frontend_available' => true,
				'condition' => [
					'filters_style' => 'sidebar',
				]
			]
		);

		$control->add_group_control(Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'filter_buttons_hidden_selected_typography',
				'selector' => '{{WRAPPER}} .portfolio-selected-filters .portfolio-filter-item',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$control->add_responsive_control(
			'filter_buttons_hidden_selected_border_radius',
			[
				'label' => __('Border Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-selected-filters .portfolio-filter-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'filter_buttons_hidden_selected_border_type',
				'label' => __('Border Type', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio-selected-filters .portfolio-filter-item',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$control->add_responsive_control(
			'filter_buttons_hidden_selected_text_padding',
			[
				'label' => __('Text Padding', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-selected-filters .portfolio-filter-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$control->add_responsive_control(
			'filter_buttons_hidden_selected_space_between',
			[
				'label' => __('Space Between', 'thegem'),
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
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio-selected-filters .portfolio-filter-item' => 'margin-right: {{SIZE}}{{UNIT}} !important',
				]
			]
		);

		$control->start_controls_tabs('filter_buttons_hidden_selected_tabs', [
			'conditions' => [
				'relation' => 'or',
				'terms' => [
					[
						'name' => 'filters_type',
						'operator' => '=',
						'value' => 'filter_woo',
					],
					[
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'filters_type',
								'operator' => '=',
								'value' => 'filter_thegem',
							], [
								'name' => 'filters_style',
								'operator' => '!=',
								'value' => 'standard',
							],
						]
					]
				],
			],
		]);

		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {
				$state = '';
				if ($stkey == 'active') {
					continue;
				} else if ($stkey == 'hover') {
					$state = ':hover';
				}

				$control->start_controls_tab('filter_buttons_hidden_selected_tab_' . $stkey, ['label' => $stelem]);

				$control->add_control(
					'filter_buttons_hidden_selected_text_color_' . $stkey,
					[
						'label' => __('Text Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-selected-filters .portfolio-filter-item' . $state => 'color: {{VALUE}};',
						],
						'conditions' => [
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_woo',
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'filters_type',
											'operator' => '=',
											'value' => 'filter_thegem',
										], [
											'name' => 'filters_style',
											'operator' => '!=',
											'value' => 'standard',
										],
									]
								]
							],
						]
					]
				);

				$control->add_control(
					'filter_buttons_hidden_selected_background_color_' . $stkey,
					[
						'label' => __('Background Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-selected-filters .portfolio-filter-item' . $state => 'background-color: {{VALUE}};',
						],
						'conditions' => [
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_woo',
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'filters_type',
											'operator' => '=',
											'value' => 'filter_thegem',
										], [
											'name' => 'filters_style',
											'operator' => '!=',
											'value' => 'standard',
										],
									]
								]
							],
						]
					]
				);

				$control->end_controls_tab();
			}
		}

		$control->end_controls_tabs();

		$control->add_control(
			'filter_buttons_hidden_search_header',
			[
				'label' => __('Product Search', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$control->add_control(
			'filter_buttons_hidden_search_icon_color',
			[
				'label' => __('Icon Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter .portfolio-search-filter-button,
					{{WRAPPER}} .portfolio-filters-list .widget_product_search button:before,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-button' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$control->add_responsive_control(
			'filter_buttons_hidden_search_input_border_radius',
			[
				'label' => __('Input Border Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input,
					{{WRAPPER}} .portfolio-filters-list .widget_product_search input,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-form input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$control->add_control(
			'filter_buttons_hidden_search_input_color',
			[
				'label' => __('Input Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input,
					{{WRAPPER}} .portfolio-filters-list .widget_product_search input,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-form input' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$control->add_control(
			'filter_buttons_hidden_search_input_background_color',
			[
				'label' => __('Input Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input,
					{{WRAPPER}} .portfolio-filters-list .widget_product_search input,
					{{WRAPPER}} .portfolio.extended-products-grid .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-form input' => 'background-color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'filters_type',
							'operator' => '=',
							'value' => 'filter_woo',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'filters_type',
									'operator' => '=',
									'value' => 'filter_thegem',
								], [
									'name' => 'filters_style',
									'operator' => '!=',
									'value' => 'standard',
								],
							]
						]
					],
				]
			]
		);

		$control->end_controls_tabs();

		$control->end_controls_section();
	}

	/**
	 * Product Tabs Style
	 * @access protected
	 */
	protected function product_tabs_style($control) {

		$control->start_controls_section(
			'tabs_style',
			[
				'label' => __('Product Tabs Style', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'product_show_filter' => 'yes',
					'filters_type' => 'filter_thegem',
					'filters_style' => 'tabs',
				]
			]
		);

		$control->add_control(
			'product_tabs_alignment',
			[
				'label' => __('Alignment', 'thegem'),
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
			]
		);

		$control->add_control(
			'product_tabs_title_header',
			[
				'label' => __('Title', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_group_control(Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'product_tabs_title_typography',
				'selector' => '{{WRAPPER}} .portfolio-filter-tabs .portfolio-filter-tabs-title',
			]
		);

		$control->add_control(
			'product_tabs_title_color',
			[
				'label' => __('Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filter-tabs .portfolio-filter-tabs-title' => 'color: {{VALUE}};',
				],
			]
		);

		$control->add_responsive_control(
			'product_tabs_title_bottom_spacing',
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
					'{{WRAPPER}} .portfolio-filter-tabs.style-default .portfolio-filter-tabs-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'filters_tabs_style' => 'default',
				],
			]
		);

		$control->add_control(
			'product_tabs_tab_header',
			[
				'label' => __('Tabs', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->start_controls_tabs('product_tabs_tab');

		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {
				$state = '';
				if ($stkey == 'hover') {
					continue;
				} else if ($stkey == 'active') {
					$state = '.active';
				} else if ($stkey == 'normal') {
					$state = ':not(.active)';
				}

				$control->start_controls_tab('product_tabs_tab_' . $stkey, ['label' => $stelem]);

				$control->add_group_control(Group_Control_Typography::get_type(),
					[
						'label' => __('Typography', 'thegem'),
						'name' => 'product_tabs_tab_typography_' . $stkey,
						'selector' => '{{WRAPPER}} .portfolio-filter-tabs ul.portfolio-filter-tabs-list li' . $state,
					]
				);

				$control->add_control(
					'product_tabs_tab_color_' . $stkey,
					[
						'label' => __('Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-filter-tabs ul.portfolio-filter-tabs-list li' . $state => 'color: {{VALUE}};',
						],
					]
				);

				$control->end_controls_tab();
			}
		}

		$control->add_control(
			'product_tabs_tab_separator_header',
			[
				'label' => __('Separator', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'filters_tabs_style' => 'alternative',
					'filters_tabs_title_separator' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'product_tabs_tab_separator_width',
			[
				'label' => __('Separator Width', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio-filter-tabs.style-alternative.separator,
					{{WRAPPER}} .portfolio-filter-tabs.style-alternative.separator .portfolio-filter-tabs-list' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'filters_tabs_style' => 'alternative',
					'filters_tabs_title_separator' => 'yes',
				],
			]
		);

		$control->add_control(
			'product_tabs_tab_separator_color',
			[
				'label' => __('Separator Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-filter-tabs.style-alternative.separator,
					{{WRAPPER}} .portfolio-filter-tabs.style-alternative.separator .portfolio-filter-tabs-list' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'filters_tabs_style' => 'alternative',
					'filters_tabs_title_separator' => 'yes',
				],
			]
		);

		$control->end_controls_tabs();

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
				'condition' => ['product_show_sorting' => 'yes']
			]
		);

		$this->add_control(
			'sorting_text',
			[
				'label' => __('“Default sorting” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Default sorting', 'woocommerce'),
			]
		);

		$control->add_control(
			'sorting_text_color',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'{{WRAPPER}} .portfolio-sorting-select div.portfolio-sorting-select-current' => 'color: {{VALUE}};',
				],
			]
		);

		$control->add_group_control(Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'sorting_text_typography',
				'selector' => '{{WRAPPER}} .portfolio-sorting-select',
			]
		);

		$control->add_responsive_control(
			'sorting_border_radius',
			[
				'label' => __('Border Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-sorting-select div.portfolio-sorting-select-current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sorting_border_type',
				'label' => __('Border Type', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio-sorting-select div.portfolio-sorting-select-current',
			]
		);

		$control->add_responsive_control(
			'sorting_padding',
			[
				'label' => __('Paddings', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-sorting-select div.portfolio-sorting-select-current' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_responsive_control(
			'sorting_bottom_spacing',
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
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-top-panel' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				]
			]
		);

		$control->add_control(
			'sorting_dropdown_header',
			[
				'label' => __('Dropdown', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_group_control(
			Group_Control_Background_Light::get_type(),
			[
				'name' => 'sorting_dropdown_background',
				'label' => __('Background Type', 'thegem'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .portfolio-sorting-select ul',
			]
		);


		$control->add_responsive_control(
			'sorting_dropdown_border_radius',
			[
				'label' => __('Border Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio-sorting-select ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'sorting_dropdown_shadow',
				'label' => __('Shadow', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio-sorting-select ul',
			]
		);

		$this->add_control(
			'sorting_dropdown_latest_text',
			[
				'label' => __('“Sort by latest” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Sort by latest', 'woocommerce'),
			]
		);

		$this->add_control(
			'sorting_dropdown_popularity_text',
			[
				'label' => __('“Sort by popularity” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Sort by popularity', 'woocommerce'),
			]
		);

		$this->add_control(
			'sorting_dropdown_rating_text',
			[
				'label' => __('“Sort by average rating” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Sort by average rating', 'woocommerce'),
			]
		);

		$this->add_control(
			'sorting_dropdown_price_low_high_text',
			[
				'label' => __('“Sort by price: low to high” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Sort by price: low to high', 'woocommerce'),
			]
		);

		$this->add_control(
			'sorting_dropdown_price_high_low_text',
			[
				'label' => __('“Sort by price: high to low” Text', 'thegem'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'default' => __('Sort by price: high to low', 'woocommerce'),
			]
		);

		$control->start_controls_tabs('sorting_dropdown_tabs');

		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {
				$state = '';
				if ($stkey == 'active') {
					$state = '.portfolio-sorting-select-current';
				} else if ($stkey == 'hover') {
					$state = ':hover';
				}
				$control->start_controls_tab('sorting_dropdown_tab_' . $stkey, ['label' => $stelem]);

				$control->add_control(
					'sorting_dropdown_text_color_' . $stkey,
					[
						'label' => __('Text Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio-sorting-select ul li' . $state => 'color: {{VALUE}};',
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
	 * Pagination Style
	 * @access protected
	 */
	protected function pagination_style($control) {

		$control->start_controls_section(
			'pagination_normal_style',
			[
				'label' => __('Pagination Style', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_pagination' => 'yes',
					'pagination_type' => ['normal', 'arrows']
				]
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
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-row + .gem-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$control->add_control(
			'pagination_numbers_header',
			[
				'label' => __('Numbers', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'pagination_type' => 'normal'
				],
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
				'condition' => [
					'pagination_type' => 'normal'
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
				],
				'condition' => [
					'pagination_type' => 'normal'
				],
			]
		);

		$control->start_controls_tabs('pagination_numbers_tabs', [
			'condition' => [
				'pagination_type' => 'normal'
			],
		]);

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
					'pagination_numbers_background_color_' . $stkey,
					[
						'label' => __('Background Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .gem-pagination a' . $state => 'background-color: {{VALUE}};',
						],
						'condition' => [
							'pagination_type' => 'normal'
						],
					]
				);

				$control->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagination_numbers_border_type_' . $stkey,
						'label' => __('Border', 'thegem'),
						'selector' => '{{WRAPPER}} .gem-pagination a' . $state,
						'condition' => [
							'pagination_type' => 'normal'
						],
					]
				);

				$control->add_control(
					'pagination_numbers_text_color_' . $stkey,
					[
						'label' => __('Text Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .gem-pagination a' . $state => 'color: {{VALUE}};',
						],
						'condition' => [
							'pagination_type' => 'normal'
						],
					]
				);

				$control->add_group_control(Group_Control_Typography::get_type(),
					[
						'label' => __('Typography', 'thegem'),
						'name' => 'pagination_numbers_text_typography_' . $stkey,
						'selector' => '{{WRAPPER}} .gem-pagination a' . $state,
						'condition' => [
							'pagination_type' => 'normal'
						],
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
					'{{WRAPPER}} .portfolio.extended-products-grid .gem-pagination .prev i, 
					{{WRAPPER}} .portfolio.extended-products-grid .gem-pagination .next i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .portfolio.extended-products-grid .gem-pagination .prev, {{WRAPPER}} .portfolio.extended-products-grid .gem-pagination .next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .portfolio.extended-products-grid .gem-pagination .prev, {{WRAPPER}} .portfolio.extended-products-grid .gem-pagination .next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					$state = ':not(.disabled):hover';
				}

				$control->start_controls_tab('pagination_arrows_tab_' . $stkey, ['label' => $stelem]);

				$control->add_control(
					'pagination_arrows_background_color_' . $stkey,
					[
						'label' => __('Background Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.extended-products-grid .gem-pagination .prev' . $state . ', {{WRAPPER}} .portfolio.extended-products-grid .gem-pagination .next' . $state => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagination_arrows_border_type_' . $stkey,
						'label' => __('Border Type', 'thegem'),
						'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .gem-pagination .prev' . $state . ', {{WRAPPER}} .portfolio.extended-products-grid .gem-pagination .next' . $state,
					]
				);

				$control->add_control(
					'pagination_arrows_icon_color_' . $stkey,
					[
						'label' => __('Icon Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'{{WRAPPER}} .portfolio.extended-products-grid .gem-pagination .prev' . $state . ', {{WRAPPER}} .portfolio.extended-products-grid .gem-pagination .next' . $state => 'color: {{VALUE}};',
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
				'condition' => [
					'show_pagination' => 'yes',
					'pagination_type' => 'more',
				]
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
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-load-more' => 'margin-top: {{SIZE}}{{UNIT}};',
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
						'icon' => 'eicon - h - align - left',
					],
					'right' => [
						'title' => __('Right', 'thegem'),
						'icon' => 'eicon - h - align - right',
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
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-holder' => 'width: {{SIZE}}{{UNIT}}; flex-grow: initial;',
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
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-type-square' => 'padding: 0 calc(50% - {{SIZE}}{{UNIT}});',
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
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-holder .gem-button-separator-line' => 'border-width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-holder .gem-button-separator-line' => 'border-top: {{SIZE}}{{UNIT}} solid; border-bottom: {{SIZE}}{{UNIT}} solid;',
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
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-holder .gem-button-separator-line' => 'border-width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-holder .gem-button-separator-line' => 'height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-line' => 'border-color: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .portfolio-load-more .gem-button-separator-type-square svg line' => 'stroke: {{VALUE}};',
				],
			]
		);

		$control->end_controls_section();
	}

	/**
	 * Labels Style
	 * @access protected
	 */
	protected function labels_style($control) {

		$control->start_controls_section(
			'labels_style',
			[
				'label' => __('Labels Style', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'product_show_new',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'product_show_sale',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'product_show_out',
							'operator' => '=',
							'value' => 'yes',
						]
					],
				],
			]
		);

		$this->add_responsive_control(
			'labels_margin',
			[
				'label' => __('Labels Margin', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product .product-labels' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_control(
			'new_label_heading',
			[
				'label' => __('“New” Label', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'product_show_new' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'new_label_size',
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
					'{{WRAPPER}} .portfolio.extended-products-grid .product .label.new-label' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.extended-products-grid .product .product-labels.style-1 .label.new-label' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'product_show_new' => 'yes',
				],
			]
		);

		$control->add_control(
			'new_label_box_rotate',
			[
				'label' => __('Rotate Box', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
						'step' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product .label.new-label' => 'transform: rotate({{SIZE}}deg); -webkit-transform: rotate({{SIZE}}deg);',
					'{{WRAPPER}} .portfolio.extended-products-grid .product .label.new-label .rotate-back' => 'transform: rotate(-{{SIZE}}deg); -webkit-transform: rotate(-{{SIZE}}deg);',
				],
				'condition' => [
					'product_show_new' => 'yes',
				],
			]
		);

		$control->add_control(
			'new_label_text_rotate',
			[
				'label' => __('Rotate Text', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
						'step' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product .label.new-label .text' => 'transform: rotate({{SIZE}}deg); -webkit-transform: rotate({{SIZE}}deg);',
				],
				'condition' => [
					'product_show_new' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'new_label_background',
			[
				'label' => __('Background', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .product-labels .label.new-label' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .product-labels .label.new-label:after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
				],
				'condition' => [
					'product_show_new' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'new_label_radius',
			[
				'label' => __('Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'separator' => 'after',
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product .label.new-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'product_show_new' => 'yes',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'new_label_border',
				'label' => __('Border', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .product .label.new-label',
				'condition' => [
					'product_show_new' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'new_label_margin',
			[
				'label' => __('Margin', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product .label.new-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'product_show_new' => 'yes',
				],
			]
		);

		$control->add_control(
			'new_label_text_color',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product .label.new-label' => 'color: {{VALUE}};',
				],
				'condition' => [
					'product_show_new' => 'yes',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'new_label_text_typo',
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .product .label.new-label .text',
				'condition' => [
					'product_show_new' => 'yes',
				],
			]
		);

		$control->add_control(
			'sale_label_heading',
			[
				'label' => __('“Sale” Label', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'product_show_sale' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'sale_label_size',
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
					'{{WRAPPER}} .portfolio.extended-products-grid .product .label.onsale' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.extended-products-grid .product .product-labels.style-1 .label.onsale' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'product_show_sale' => 'yes',
				],
			]
		);

		$control->add_control(
			'sale_label_box_rotate',
			[
				'label' => __('Rotate Box', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
						'step' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.onsale' => 'transform: rotate({{SIZE}}deg); -webkit-transform: rotate({{SIZE}}deg);',
					'{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.onsale .rotate-back' => 'transform: rotate(-{{SIZE}}deg); -webkit-transform: rotate(-{{SIZE}}deg);',
				],
				'condition' => [
					'product_show_sale' => 'yes',
				],
			]
		);

		$control->add_control(
			'sale_label_text_rotate',
			[
				'label' => __('Rotate Text', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
						'step' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.onsale .text' => 'transform: rotate({{SIZE}}deg); -webkit-transform: rotate({{SIZE}}deg);',
				],
				'condition' => [
					'product_show_sale' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'sale_label_background',
			[
				'label' => __('Background', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .product-labels .label.onsale' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .product-labels .label.onsale:after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
				],
				'condition' => [
					'product_show_sale' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'sale_label_radius',
			[
				'label' => __('Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'separator' => 'after',
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'product_show_sale' => 'yes',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sale_label_border',
				'label' => __('Border', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.onsale',
				'condition' => [
					'product_show_sale' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'sale_label_margin',
			[
				'label' => __('Margin', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.onsale' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'product_show_sale' => 'yes',
				],
			]
		);

		$control->add_control(
			'sale_label_text_color',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.onsale' => 'color: {{VALUE}};',
				],
				'condition' => [
					'product_show_sale' => 'yes',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'sale_label_text_typo',
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.onsale .text',
				'condition' => [
					'product_show_sale' => 'yes',
				],
			]
		);

		$control->add_control(
			'out_label_heading',
			[
				'label' => __('“Out of stock” Label', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'product_show_out' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'out_label_size',
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
					'{{WRAPPER}} .portfolio.extended-products-grid .product .label.out-of-stock-label' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .portfolio.extended-products-grid .product .product-labels.style-1 .label.out-of-stock-label' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'product_show_out' => 'yes',
				],
			]
		);

		$control->add_control(
			'out_label_box_rotate',
			[
				'label' => __('Rotate Box', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
						'step' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.out-of-stock-label' => 'transform: rotate({{SIZE}}deg); -webkit-transform: rotate({{SIZE}}deg);',
					'{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.out-of-stock-label .rotate-back' => 'transform: rotate(-{{SIZE}}deg); -webkit-transform: rotate(-{{SIZE}}deg);',
				],
				'condition' => [
					'product_show_out' => 'yes',
				],
			]
		);

		$control->add_control(
			'out_label_text_rotate',
			[
				'label' => __('Rotate Text', 'thegem'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
						'step' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.out-of-stock-label .text' => 'transform: rotate({{SIZE}}deg); -webkit-transform: rotate({{SIZE}}deg);',
				],
				'condition' => [
					'product_show_out' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'out_label_background',
			[
				'label' => __('Background', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .product-labels .label.out-of-stock-label' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .portfolio.extended-products-grid .portfolio-item.product .product-labels .label.out-of-stock-label:after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
				],
				'condition' => [
					'product_show_out' => 'yes',
				],
			]
		);

		$control->add_responsive_control(
			'out_label_radius',
			[
				'label' => __('Radius', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'rem', 'em'],
				'separator' => 'after',
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.out-of-stock-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'product_show_out' => 'yes',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'out_label_border',
				'label' => __('Border', 'thegem'),
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.out-of-stock-label',
				'condition' => [
					'product_show_out' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'out_label_margin',
			[
				'label' => __('Margin', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.out-of-stock-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'product_show_out' => 'yes',
				],
			]
		);

		$control->add_control(
			'out_label_text_color',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.out-of-stock-label' => 'color: {{VALUE}};',
				],
				'condition' => [
					'product_show_out' => 'yes',
				],
			]
		);

		$control->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __('Typography', 'thegem'),
				'name' => 'out_label_text_typo',
				'selector' => '{{WRAPPER}} .portfolio.extended-products-grid .product-labels .label.out-of-stock-label .text',
				'condition' => [
					'product_show_out' => 'yes',
				],
			]
		);

		$control->end_controls_section();
	}


	/**
	 * Notification Style
	 * @access protected
	 */
	protected function notification_style($control) {

		$control->start_controls_section(
			'notification_style',
			[
				'label' => __('Notification Style', 'thegem'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$control->add_control(
			'stay_visible',
			[
				'label' => __('Stay visible, ms', 'thegem'),
				'type' => Controls_Manager::NUMBER,
				'min' => 500,
				'max' => 10000,
				'step' => 100,
				'default' => 4000,
			]
		);

		$control->add_responsive_control(
			'notification_padding',
			[
				'label' => __('Padding', 'thegem'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em'],
				'label_block' => true,
				'selectors' => [
					'.thegem-popup-notification-wrap[data-style-uid="{{ID}}"] .thegem-popup-notification .notification-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$control->add_control(
			'notification_background_color',
			[
				'label' => __('Background Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'.thegem-popup-notification-wrap[data-style-uid="{{ID}}"] .thegem-popup-notification .notification-message' => 'background-color: {{VALUE}};',
				],
			]
		);

		$control->add_control(
			'notification_text_color',
			[
				'label' => __('Text Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'.thegem-popup-notification-wrap[data-style-uid="{{ID}}"] .thegem-popup-notification .notification-message' => 'color: {{VALUE}};',
				],
			]
		);

		$control->add_control(
			'notification_icon_color',
			[
				'label' => __('Icon Color', 'thegem'),
				'type' => Controls_Manager::COLOR,
				'label_block' => false,
				'selectors' => [
					'.thegem-popup-notification-wrap[data-style-uid="{{ID}}"] .thegem-popup-notification .notification-message:before' => 'color: {{VALUE}};',
				],
			]
		);

		$control->add_control(
			'button_wishlist_header',
			[
				'label' => __('View Cart & View Wishlist Buttons Colors', 'thegem'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->start_controls_tabs('button_wishlist_tabs');

		if (!empty($control->states_list)) {
			foreach ((array)$control->states_list as $stkey => $stelem) {
				$state = '';
				if ($stkey == 'active') {
					continue;
				} else if ($stkey == 'hover') {
					$state = ':hover';
				}

				$control->start_controls_tab('button_wishlist_tab_' . $stkey, [
					'label' => $stelem,
				]);

				$control->add_control(
					'button_wishlist_color_' . $stkey,
					[
						'label' => __('Text Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'.thegem-popup-notification-wrap[data-style-uid="{{ID}}"] .thegem-popup-notification' . $state => 'color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'button_wishlist_background_color_' . $stkey,
					[
						'label' => __('Background Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'.thegem-popup-notification-wrap[data-style-uid="{{ID}}"] .thegem-popup-notification' . $state => 'background-color: {{VALUE}};',
						],
					]
				);

				$control->add_control(
					'button_wishlist_border_color_' . $stkey,
					[
						'label' => __('Border Color', 'thegem'),
						'type' => Controls_Manager::COLOR,
						'label_block' => false,
						'selectors' => [
							'.thegem-popup-notification-wrap[data-style-uid="{{ID}}"] .thegem-popup-notification' . $state => 'border-color: {{VALUE}};',
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
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	public function render() {

		if (!thegem_is_plugin_active('woocommerce/woocommerce.php')) {
			return '';
		}
		if (!wp_script_is('thegem-portfolio-grid-extended-inline')) {
			wp_enqueue_script('thegem-portfolio-grid-extended-inline');
			wp_add_inline_script( 'thegem-portfolio-grid-extended-inline', "jQuery('.extended-products-grid .yith-icon').each(function () {
					var addIcon = jQuery(this).children('.add-wishlist-icon').clone();
					var addedIcon = jQuery(this).children('.added-wishlist-icon').clone();
					jQuery(this).find('a i').remove();
					jQuery(this).find('a svg').remove();
					jQuery(this).find('.yith-wcwl-add-button a:not(.delete_item)').prepend(addIcon);
					jQuery(this).find('.yith-wcwl-add-button a.delete_item').prepend(addedIcon);
					jQuery(this).find('.yith-wcwl-wishlistexistsbrowse a').prepend(addedIcon);
					jQuery(this).find('a').addClass('icon');
					jQuery(this).find('a.gem-button').removeAttr('class').removeAttr('style').removeAttr('onmouseleave').removeAttr('onmouseenter').addClass('icon');
					jQuery(this).find('.yith-wcwl-wishlistaddedbrowse a').prepend(addedIcon);
				});" );
		}

		global $post;
		$portfolio_posttemp = $post;
		$params = $this->get_settings_for_display();
		$widget_uid = $this->get_id();

		if ($params['layout_type'] == 'list') {
			$params['thegem_elementor_preset'] = 'below-default-cart-button';
			$params['layout'] = 'justified';
			$params['columns_desktop'] = $params['columns_desktop_list'];
			$params['columns_tablet'] = $params['columns_tablet_list'];
			$params['columns_mobile'] = '1x';
			$params['caption_position'] = 'page';
			$params['ignore_highlights'] = 'yes';
			$params['add_to_cart_type'] = 'button';
			$params['caption_container_alignment'] = !empty($params['caption_container_alignment']) ? $params['caption_container_alignment'] : 'default';
		}

		$is_archive_template = false;
		if ( $params['source_type'] == 'archive' && (is_tax( 'product_cat' ) || is_tax( 'product_tag' ) || is_post_type_archive( 'product' )) ) {
			$is_archive_template = true;
		}

		if ($params['disable_preloader'] == 'yes') {
			$params['ignore_highlights'] = 'yes';
			$params['skeleton_loader'] = '';
		}

		$queried = get_queried_object();

		if ( is_tax( 'product_cat' ) && $params['source_type'] == 'archive' ) {
			$params['source'] = array('category');
			$params['content_products_cat'] = array($queried->slug);
		}

		$grid_uid = $is_archive_template ? '' : 'grid_'.$widget_uid;
		$grid_uid_url = $is_archive_template ? '' : 'grid_'.$widget_uid.'-';

		$params['style_uid'] = $widget_uid;

		if ($params['source_type'] == 'archive') {
			if ( is_tax( 'product_tag' ) ) {
				$params['source'] = array('tag');
				$params['content_products_tags'] = array($queried->slug);
			} else if (isset($queried->taxonomy)) {
				$params['select_products_tax'] = $queried->taxonomy;
				$params['content_products_tax'] = array($queried->slug);
			}
		}

		$post__in = null;
		$is_related_upsell = false;
		if ($params['source_type'] == 'related_upsell') {
			$product = thegem_templates_init_product();
			if (empty($product)) {
				$post = $portfolio_posttemp;
				return;
			}
			$is_related_upsell = true;
			if ($params['related_upsell_source'] == 'related') {
				$related_products = wc_get_related_products($product->get_id(), -1);
				$post__in = $related_products;
			} else {
				global $thegem_product_data;
				$upsells = $product->get_upsell_ids();
				if(intval($thegem_product_data['product_page_elements_upsell_items']) > -1) {
					$upsells = array_slice($upsells, 0, intval($thegem_product_data['product_page_elements_upsell_items']));
				}
				$post__in = $upsells;
			}

			$params['source'] = array('products');
			$params['content_products'] = $post__in;

			if (empty($post__in) && !is_admin()) {
				$post = $portfolio_posttemp;
				return;
			}
		} else if ($params['source_type'] == 'cross_sell') {
			$cross_sells_ids_in_cart = array();

			if (WC()->cart) {
				foreach ( WC()->cart->get_cart() as $cart_item ) {
					if ( $cart_item['quantity'] > 0 ) {
						$cross_sells_ids_in_cart = array_merge( $cart_item['data']->get_cross_sell_ids(), $cross_sells_ids_in_cart );
					}
				}
			}

			$cross_sells = wp_parse_id_list( $cross_sells_ids_in_cart );
			$post__in = $cross_sells;

			$params['source'] = array('products');
			$params['content_products'] = $post__in;

			if (empty($post__in) && !is_admin()) {
				$post = $portfolio_posttemp;
				return;
			}

			if ($params['show_cross_sell_title'] == 'yes') {
				$text_styled_class = implode(' ', array($params['cross_sell_title_style'], $params['cross_sell_title_weight'])); ?>
				<<?php echo $params['cross_sell_title_tag']; ?> class="cross-sell-title <?php echo $text_styled_class; ?>">
					<?php echo $params['cross_sell_title_text']; ?>
				</<?php echo $params['cross_sell_title_tag']; ?>>
				<?php
			}
		}

		$params['action'] = 'extended_products_grid_load_more';
		$localize = array(
			'data' => $params,
			'action' => 'extended_products_grid_load_more',
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('extended_products_grid_ajax-nonce')
		);
		wp_localize_script('thegem-portfolio-grid-extended', 'thegem_portfolio_ajax_' . $widget_uid, $localize);

		$normal_filter = true;
		$native_ajax = false;
		if ($params['filters_type'] == 'filter_woo') {
			$normal_filter = false;
			$params['filters_style'] = $params['woo_filters_style'];

			if ($params['woo_ajax_filtering'] == 'yes') {
				$native_ajax = true;
			}
		}

		if (!empty($params['source']) && in_array('category', $params['source'])) {
			$categories = $params['content_products_cat'];
		} else {
			$categories = ['0'];
		}

		$cat_args = array(
			'hide_empty' => true,
			'orderby' => $params['filter_by_categories_order_by']
		);
		if ($params['filter_by_categories_order_by'] == 'term_order') {
			$cat_args['orderby'] = 'meta_value_num';
			$cat_args['meta_key'] = 'order';
		}

		if ($categories && in_array('0', $categories)) {
			if ($params['filter_by_categories_hierarchy'] == 'yes') {
				$cat_args['parent'] = 0;
			}
		} else {
			$cat_args['slug'] = $categories;
		}
		$terms = get_terms('product_cat', $cat_args);

		$categories_filter = null;
		if (!empty($_GET[$grid_uid_url . 'category'])) {
			$active_cat = $_GET[$grid_uid_url . 'category'];
			$categories_current = [$active_cat];
			$categories_filter = $active_cat;
		} else if (is_tax('product_cat') && $params['source_type'] == 'archive') {
			$active_cat = $queried->slug;
			$categories_current = [$active_cat];
			$cat_args['slug'] = [];
			$cat_args['parent'] = $queried->term_id;
			$terms = get_terms('product_cat', $cat_args);
		} else {
			$active_cat = 'all';
			$categories_current = $categories;
		}

		$attributes = [];
		if (!empty($params['source']) && in_array('attribute', $params['source'])) {
			$attrs = $params['content_products_attr'];

			if ($attrs) {
				foreach ($attrs as $attr) {
					$values = $params['content_products_attr_val_' . $attr];
					if (empty($values) || in_array('0', $values)) {
						$values = get_terms('pa_' . $attr, array('fields' => 'slugs'));
					}
					$attributes[$attr] = $values;
				}
			}
		}

		$attributes_url = [];
		$has_attr_url = false;
		$attributes_list = $this->select_products_attributes();
		foreach ($attributes_list as $attr => $name) {
			if (!empty($_GET[$grid_uid_url . 'filter_' . $attr])) {
				$attributes_url[$attr] = explode(",", $_GET[$grid_uid_url . 'filter_' . $attr]);
				$has_attr_url = true;
			}
		}
		$attributes_filter = null;
		if ($has_attr_url) {
			$attributes_current = $attributes_url;
			$attributes_filter = $attributes_url;
		} else {
			$attributes_current = $attributes;
		}

		$active_tag = $products_tax = $products_tax_value = null;
		if (!empty($params['source']) && in_array('tag', $params['source'])) {
			$active_tag = $params['content_products_tags'];
		}
		if (!empty($params['select_products_tax'])) {
			$products_tax = $params['select_products_tax'];
			$products_tax_value = $params['content_products_tax'];
		}

		if (!empty($params['source']) && in_array('products', $params['source'])) {
			$post__in = $params['content_products'];
		}

		if ($params['caption_position'] == 'image') {
			$hover_effect = $params['image_hover_effect_image'];
		} else if ($params['caption_position'] == 'page') {
			$hover_effect = $params['image_hover_effect_page'];
		} else {
			$hover_effect = $params['image_hover_effect_hover'];
		}

		if ( $params['layout_type'] == 'list') {
			$hover_effect = 'list-'.$hover_effect;
		}
		wp_enqueue_style('thegem-hovers-' . $hover_effect);

		if ($params['pagination_type'] == 'more') {
			wp_enqueue_style('thegem-button');
		} else if ($params['pagination_type'] == 'scroll') {
			wp_enqueue_script('thegem-scroll-monitor');
		}

		if ($params['quick_view'] == 'yes') {
			wp_enqueue_script('wc-single-product');
			wp_enqueue_script('wc-add-to-cart-variation');
			wp_enqueue_script('thegem-product-quick-view');
            wp_enqueue_script('thegem-quick-view');
            wp_enqueue_style('thegem-quick-view');

            if(thegem_get_option('product_page_layout') == 'default') {
                if(thegem_get_option('product_page_button_add_to_cart_icon') && thegem_get_option('product_page_button_add_to_cart_icon_pack')) {
                    wp_enqueue_style('icons-'.thegem_get_option('product_page_button_add_to_cart_icon_pack'));
                }
                if(thegem_get_option('product_page_button_add_to_wishlist_icon') && thegem_get_option('product_page_button_add_to_wishlist_icon_pack')) {
                    wp_enqueue_style('icons-'.thegem_get_option('product_page_button_add_to_wishlist_icon_pack'));
                }
                if(thegem_get_option('product_page_button_added_to_wishlist_icon') && thegem_get_option('product_page_button_added_to_wishlist_icon_pack')) {
                    wp_enqueue_style('icons-'.thegem_get_option('product_page_button_added_to_wishlist_icon_pack'));
                }
            }
			if (thegem_get_option('product_gallery') != 'legacy') {
				wp_enqueue_style('thegem-product-gallery');
			} else {
				wp_enqueue_style('thegem-hovers');
			}
		}

		if (isset($params['attribute_swatches']) && ($params['attribute_swatches'] == 'yes' || $params['attribute_swatches_tablet'] == 'yes' || $params['attribute_swatches_mobile'] == 'yes')) {
			wp_enqueue_script('wc-add-to-cart-variation');
		}

		if ($params['product_show_filter'] === 'yes') {
			wp_enqueue_script('jquery-dlmenu');

			if ($params['filter_by_price'] === 'yes') {
				wp_enqueue_script('wc-jquery-ui-touchpunch');
			}
		}

		if ($params['loading_animation'] === 'yes') {
			wp_enqueue_style('thegem-animations');
			wp_enqueue_script('thegem-items-animations');
			wp_enqueue_script('thegem-scroll-monitor');
		}

		if ($params['layout'] !== 'justified' || $params['ignore_highlights'] !== 'yes') {

			if ($params['layout'] == 'metro') {
				wp_enqueue_script('thegem-isotope-metro');
			} else {
				wp_enqueue_script('thegem-isotope-masonry-custom');
			}
		}

		if ( $params['sidebar_sticky'] == 'yes' ) {
			wp_enqueue_script( 'thegem-sticky' );
		}

		$items_per_page = intval($params['items_per_page']) ?: 8;

		$page = 1;
		$next_page = 0;

		if (!empty($_GET[$grid_uid_url . 'page'])) {
			$page = $_GET[$grid_uid_url . 'page'];
		}

		if (!empty($_GET[$grid_uid_url . 'orderby'])) {
			$orderby = $_GET[$grid_uid_url . 'orderby'];
			$order = 'desc';
			$sortby = $orderby;
			if ($sortby == 'price' || $sortby == 'title') {
				$order = 'asc';
			}
		} else {
			$orderby = $params['orderby'];
			$order = $params['order'];
			$sortby = 'default';
		}

		$featured_only = $params['featured_only'] == 'yes';
		$sale_only = $params['sale_only'] == 'yes';
		$stock_only = $params['stock_only'] == 'yes';

		$status_current = null;
		if (!empty($_GET[$grid_uid_url . 'status'])) {
			$status_current = explode(",", $_GET[$grid_uid_url . 'status']);
			if (in_array('sale', $status_current)) {
				$sale_only = true;
			}
			if (in_array('stock', $status_current)) {
				$stock_only = true;
			}
		}

		$price_current = null;
		if (!empty($_GET[$grid_uid_url . 'min_price']) || !empty($_GET[$grid_uid_url . 'max_price'])) {
			$price_range = thegem_extended_products_get_product_price_range($featured_only, $sale_only, $categories, $attributes);
			$current_min_price = !empty($_GET[$grid_uid_url . 'min_price']) ? floatval($_GET[$grid_uid_url . 'min_price']) : $price_range['min'];
			$current_max_price = !empty($_GET[$grid_uid_url . 'max_price']) ? floatval($_GET[$grid_uid_url . 'max_price']) : $price_range['max'];
			$price_current = [$current_min_price, $current_max_price];
		}

		$search_current = null;
		if (!empty($_GET[$grid_uid_url . 's'])) {
			$search_current = $_GET[$grid_uid_url . 's'];
		}

		$active_tab = 0;
		if ($params['product_show_filter'] == 'yes' && $params['filters_style'] == 'tabs') {
			if (!empty($_GET[$grid_uid_url . 'tab'])) {
				$active_tab = intval($_GET[$grid_uid_url . 'tab']);
			} else {
				$active_tab = 1;
			}

			$filter_tabs = $params['filters_tabs_tabs'];
			$filter_tabs_current = $filter_tabs[$active_tab - 1];
			if ($filter_tabs_current['filters_tabs_tab_filter_by'] == 'featured') {
				$featured_only = true;
			} else if ($filter_tabs_current['filters_tabs_tab_filter_by'] == 'sale') {
				$sale_only = true;
			} else if ($filter_tabs_current['filters_tabs_tab_filter_by'] == 'recent') {
				$orderby = 'date';
				$order = 'desc';
			} else if ($filter_tabs_current['filters_tabs_tab_filter_by'] == 'categories') {
				$categories_current = [$filter_tabs_current['filters_tabs_tab_products_cat']];
			}
		}

		$product_loop = thegem_extended_products_get_posts($page, $items_per_page, $orderby, $order, $featured_only, $sale_only, $stock_only, $categories_current, $attributes_current, $price_current, $search_current, null, $active_tag, $products_tax, $products_tax_value, $post__in, $params['offset'], $params['exclude_products']);

		if ($product_loop && $product_loop->have_posts() || $search_current != null || $price_current != null) :

			$max_page = ceil(($product_loop->found_posts - intval($params['offset'])) / $items_per_page);
			if ($max_page > $page)
				$next_page = $page + 1;
			else
				$next_page = 0;

			if ($params['columns_desktop'] == '100%' || $params['fullwidth_section_sorting'] == 'yes') {
			if (isset($params['image_gaps']['size']) && $params['image_gaps']['size'] < 21) { ?>
				<style>
					.elementor-element-<?php echo $widget_uid; ?> .fullwidth-block .portfolio-top-panel,
					.elementor-element-<?php echo $widget_uid; ?> .portfolio-item.not-found .wrap {
						padding-left: 21px !important;
						padding-right: 21px !important;
					}

					.elementor-element-<?php echo $widget_uid; ?> .with-filter-sidebar .filter-sidebar {
						padding-left: 21px !important;
					}
				</style>
			<?php }
			if (!empty($params['image_gaps_tablet']['size']) && $params['image_gaps_tablet']['size'] < 21) { ?>
				<style>
					@media (min-width: 768px) and (max-width: 1024px) {
						.elementor-element-<?php echo $widget_uid; ?> .fullwidth-block .portfolio-top-panel,
						.elementor-element-<?php echo $widget_uid; ?> .portfolio-item.not-found .wrap {
							padding-left: 21px !important;
							padding-right: 21px !important;
						}

						.elementor-element-<?php echo $widget_uid; ?> .with-filter-sidebar .filter-sidebar {
							padding-left: 21px !important;
						}
					}
				</style>
			<?php }
			if (!empty($params['image_gaps_mobile']['size']) && $params['image_gaps_mobile']['size'] < 21) { ?>
				<style>
					@media (max-width: 767px) {
						.elementor-element-<?php echo $widget_uid; ?> .fullwidth-block .portfolio-top-panel,
						.elementor-element-<?php echo $widget_uid; ?> .portfolio-item.not-found .wrap {
							padding-left: 21px !important;
							padding-right: 21px !important;
						}

						.elementor-element-<?php echo $widget_uid; ?> .with-filter-sidebar .filter-sidebar {
							padding-left: 21px !important;
						}
					}
				</style>
			<?php }
		}

			if ($params['product_separator'] == 'yes' && $params['product_separator_width']['size'] % 2 !== 0) {
				$floor = floor($params['product_separator_width']['size'] / 2);
				$ceil = ceil($params['product_separator_width']['size'] / 2); ?>
				<style>
					.elementor-element-<?php echo $widget_uid; ?> .portfolio-set .portfolio-item:before {
						transform: translateX(-<?php echo $floor; ?>px) !important;
						top: -<?php echo $floor; ?>px !important;
					}

					.elementor-element-<?php echo $widget_uid; ?> .portfolio-set .portfolio-item:after {
						transform: translateX(<?php echo $ceil; ?>px) !important;
						top: -<?php echo $floor; ?>px !important;
					}

					.elementor-element-<?php echo $widget_uid; ?> .portfolio-set .portfolio-item .item-separator-box:before {
						transform: translateY(-<?php echo $floor; ?>px) !important;
						left: -<?php echo $floor; ?>px !important;
					}

					.elementor-element-<?php echo $widget_uid; ?> .portfolio-set .portfolio-item .item-separator-box:after {
						transform: translateY(<?php echo $ceil; ?>px) !important;
						left: -<?php echo $floor; ?>px !important;
					}
				</style>
			<?php }

			$item_classes = get_thegem_extended_products_render_item_classes($params);
			$thegem_sizes = get_thegem_extended_products_render_item_image_sizes($params);
			$search_only = true;
			if ($params['product_show_filter'] == 'yes' && (!$normal_filter || (
						($params['filter_by_categories'] == 'yes' && count($terms) > 0) ||
						$params['filter_by_attribute'] == 'yes' ||
						$params['filter_by_price'] == 'yes' ||
						$params['filter_by_status'] == 'yes'
					))) {
				$search_only = false;
			}

			if ($params['columns_desktop'] == '100%' || (($params['ignore_highlights'] !== 'yes' || $params['layout'] !== 'justified') && $params['skeleton_loader'] !== 'yes')) {
				echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="preloader-spin"></div></div>');
			} else if ($params['skeleton_loader'] == 'yes') { ?>
				<div class="preloader save-space">
					<div class="skeleton panel-sidebar-position-<?php echo $params['sidebar_position']; ?>">
						<?php if ($params['product_show_filter'] == 'yes' && $params['filters_style'] == 'sidebar' && !$search_only) { ?>
						<div class="with-filter-sidebar">
							<div class="filter-sidebar">
								<div class="widget"></div>
								<div class="widget"></div>
								<div class="widget"></div>
							</div>
							<div class="content">
								<?php }
								if ($params['product_show_sorting'] == 'yes') { ?>
									<div class="portfolio-top-panel">
										<div class="skeleton-sorting"></div>
									</div>
								<?php } ?>
								<div class="skeleton-posts row portfolio-row">
									<?php for ($x = 0; $x < $product_loop->post_count; $x++) {
										echo thegem_extended_products_render_item($params, $item_classes);
									} ?>
								</div>
								<?php if ($params['product_show_filter'] == 'yes' && $params['filters_style'] == 'sidebar' && !$search_only) { ?>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>
			<?php } ?>

			<div class="portfolio-preloader-wrapper panel-sidebar-position-<?php echo $params['sidebar_position']; ?>">

				<?php
				if ($params['caption_position'] == 'hover') {
					$title_on = 'hover';
				} else {
					$title_on = 'page';
				}
				$this->add_render_attribute(
					'products-wrap',
					[
						'class' => [
							'portfolio portfolio-grid extended-products-grid',
							'woocommerce',
							'products',
							'no-padding',
							'portfolio-preset-' . $params['thegem_elementor_preset'],
							'portfolio-pagination-' . $params['pagination_type'],
							'portfolio-style-' . $params['layout'],
							'background-style-' . $params['caption_container_preset'],
							'caption-container-preset-' . $params['caption_container_preset_hover'],
							'caption-position-' . $params['caption_position'],
							'caption-alignment-' . $params['caption_container_alignment_hover'],
							'aspect-ratio-' . $params['image_aspect_ratio'],
							'hover-' . $hover_effect,
							'title-on-' . $title_on,
							($params['layout_type'] == 'list' ? 'list-style disabled-hover caption-position-list-' . $params['caption_position_list'] : ''),
							($params['layout_type'] == 'list' ? 'caption-alignment-list-' . $params['caption_container_alignment'] : ''),
							($params['layout_type'] == 'list' ? 'caption-layout-list-' . $params['caption_layout_list'] : ''),
							($is_archive_template ? 'main-loop-grid' : ''),
							($params['loading_animation'] == 'yes' ? 'loading-animation' : ''),
							($params['loading_animation'] == 'yes' && $params['animation_effect'] ? 'item-animation-' . $params['animation_effect'] : ''),
							($params['loading_animation'] == 'yes' && $params['loading_animation_mobile'] == 'yes' ? 'enable-animation-mobile' : ''),
							($params['image_gaps']['size'] == 0 ? 'no-gaps' : ''),
							($params['shadowed_container'] == 'yes' ? 'shadowed-container' : ''),
							($params['columns_desktop'] == '100%' || $params['fullwidth_section_sorting'] == 'yes' ? 'fullwidth-columns' : ''),
							($params['columns_desktop'] == '100%' ? 'fullwidth-columns-desktop-' . $params['columns_100'] : ''),
							($params['caption_position'] == 'image' && $params['image_hover_effect_image'] == 'gradient' ? 'hover-gradient-title' : ''),
							($params['caption_position'] == 'image' && $params['image_hover_effect_image'] == 'circular' ? 'hover-circular-title' : ''),
							($params['caption_position'] == 'hover' || $params['caption_position'] == 'image' ? 'hover-title' : ''),
							($params['social_sharing'] != 'yes' ? 'portfolio-disable-socials' : ''),
							($params['layout'] == 'masonry' ? 'portfolio-items-masonry' : ''),
							($params['columns_desktop'] != '100%' ? 'columns-desktop-' . $params['columns_desktop'] : 'columns-desktop-' . $params['columns_100']),
							('columns-tablet-' . $params['columns_tablet']),
							('columns-mobile-' . $params['columns_mobile']),
							($params['product_separator'] == 'yes' ? 'item-separator' : ''),
							($params['full_item_border_type_border'] != '' ? 'full-item-border' : ''),
							($params['layout'] == 'justified' && $params['ignore_highlights'] == 'yes' ? 'disable-isotope' : ''),
							($params['next_page_preloading'] == 'yes' && $params['show_pagination'] === 'yes' ? 'next-page-preloading' : ''),
							($params['tabs_preloading'] == 'yes' ? 'tabs-preloading' : ''),
							($params['product_show_divider'] == 'yes' ? 'with-divider' : ''),
						],
						'data-per-page' => esc_attr($items_per_page),
						'data-current-page' => esc_attr($page),
						'data-current-tab' => esc_attr($active_tab),
						'data-next-page' => esc_attr($next_page),
						'data-pages-count' => esc_attr($max_page),
						'data-style-uid' => esc_attr($widget_uid),
						'data-portfolio-uid' => esc_attr($grid_uid),
						'data-hover' => esc_attr($hover_effect),
						'data-portfolio-filter' => esc_attr($categories_filter),
						'data-portfolio-filter-attributes' => esc_attr(json_encode($attributes_filter)),
						'data-portfolio-filter-status' => esc_attr(json_encode($status_current)),
						'data-portfolio-filter-price' => esc_attr(json_encode($price_current)),
						'data-portfolio-filter-search' => esc_attr($search_current),
					]
				);
				?>

				<div <?php echo $this->get_render_attribute_string('products-wrap'); ?>>
					<?php
					$show_widgets = false;
					$has_right_panel = $params['product_show_sorting'] == 'yes' || ( $params['filter_by_search'] == 'yes' && ( $params['filters_style'] == 'standard' || $search_only)); ?>

					<div class="portfolio-row-outer <?php if ($params['columns_desktop'] == '100%' || $params['fullwidth_section_sorting'] == 'yes'): ?>fullwidth-block no-paddings<?php endif; ?>">
						<?php if ($is_archive_template) {
							$shop_url = get_post_type_archive_link('product');
							if (!$normal_filter && is_tax('product_cat')) {
								$shop_url = get_term_link($queried->slug, 'product_cat');
							} else if (!$normal_filter && is_tax('product_tag')) {
								$shop_url = get_term_link($queried->slug, 'product_tag');
							} ?>
							<input id="shop-page-url" type="hidden"
								   value="<?php echo esc_url($shop_url); ?>">
						<?php } ?>
						<?php if ($params['product_show_filter'] == 'yes' && $params['filters_style'] == 'sidebar' && !$search_only) { ?>
						<div class="with-filter-sidebar <?php echo $params['sidebar_sticky'] == 'yes' ? 'sticky-sidebar' : ''; ?>">
							<div class="filter-sidebar <?php echo $params['product_show_sorting'] == 'yes' ? 'left' : ''; ?>">
								<?php
								if ( $normal_filter ) {
									include( locate_template( array( 'gem-templates/products-extended/filters.php' ) ) );
								} else { ?>
									<div class="portfolio-filters-list sidebar
										<?php echo $normal_filter ? 'normal hide-mobile hide-tablet' : 'native'; ?>
										style-sidebar <?php echo $params['filters_scroll_top'] == 'yes' ? 'scroll-top' : ''; ?>
										<?php echo $has_right_panel ? 'has-right-panel' : ''; ?>
										<?php echo $params['woo_remove_counts'] == 'yes' ? 'hide-filter-counts' : ''; ?>
										<?php echo $native_ajax ? 'native-ajax-filters' : ''; ?>">
										<div class="portfolio-show-filters-button <?php echo $params['filter_buttons_hidden_show_icon'] == 'yes' ? 'with-icon' : ''; ?>">
											<?php echo esc_html( $params['filter_buttons_hidden_show_text'] ); ?>
											<?php if ( $params['filter_buttons_hidden_show_icon'] == 'yes' ) { ?>
												<span class="portfolio-show-filters-button-icon"></span>
											<?php } ?>
										</div>

										<div class="portfolio-filters-outer">
											<div class="portfolio-filters-area">
												<div class="portfolio-filters-area-scrollable">
													<div class="widget-area-wrap">
														<?php get_sidebar('shop'); ?>
													</div>
												</div>
											</div>
											<div class="portfolio-close-filters"></div>
										</div>

									</div>
								<?php } ?>
							</div>
							<div class="content">
								<?php } ?>
								<?php if (($params['product_show_filter'] == 'yes' || $params['product_show_sorting'] == 'yes') && $params['filters_style'] != 'tabs'): ?>
									<div class="portfolio-top-panel <?php echo $params['filters_style'] == 'sidebar' ? 'sidebar-filter' : ''; ?> <?php echo ($params['product_show_sorting'] != 'yes' && ($params['filter_by_search'] != 'yes' || $params['filters_style'] != 'standard')) ? 'selected-only' : ''; ?> ">
										<div class="portfolio-top-panel-row">
											<div class="portfolio-top-panel-left <?php echo esc_attr($params['filter_buttons_standard_alignment']); ?>">
												<?php
												if ($normal_filter) {
													if ($params['product_show_filter'] == 'yes' && $params['filters_style'] != 'sidebar' && !$search_only) {
														include(locate_template(array('gem-templates/products-extended/filters.php')));
													}
													if (($params['product_show_filter'] == 'yes' && $params['filters_style'] == 'sidebar') || $params['product_show_filter'] != 'yes') {
														include(locate_template(array('gem-templates/products-extended/selected-filters.php')));
													}
												} else {
													if ($params['filters_style'] == 'hidden' && is_active_sidebar('shop-sidebar')) { ?>
														<div class="portfolio-filters-list sidebar native style-<?php echo esc_attr($params['filters_style']); ?>
															<?php echo $params['filters_scroll_top'] == 'yes' ? 'scroll-top' : ''; ?>
															<?php echo $has_right_panel ? 'has-right-panel' : ''; ?>
															<?php echo $params['woo_remove_counts'] == 'yes' ? 'hide-filter-counts' : ''; ?>
															<?php echo $native_ajax ? 'native-ajax-filters' : ''; ?>">

															<div class="portfolio-show-filters-button <?php echo $params['filter_buttons_hidden_show_icon'] == 'yes' ? 'with-icon' : ''; ?>">
																<?php echo esc_html($params['filter_buttons_hidden_show_text']); ?>
																<?php if ($params['filter_buttons_hidden_show_icon'] == 'yes') { ?>
																	<span class="portfolio-show-filters-button-icon"></span>
																<?php } ?>
															</div>

															<div class="portfolio-filters-outer">
																<div class="portfolio-filters-area">
																	<div class="portfolio-filters-area-scrollable">
																		<div class="widget-area-wrap">
																			<?php get_sidebar('shop'); ?>
																		</div>
																	</div>
																</div>
																<div class="portfolio-close-filters"></div>
															</div>

														</div>
													<?php }
													if ($params['filters_style'] == 'sidebar' && $native_ajax) {
														include(locate_template(array('gem-templates/products-extended/selected-filters.php')));
													}
												} ?>
											</div>
											<?php if ($has_right_panel): ?>
												<div class="portfolio-top-panel-right">
													<?php if ($params['product_show_sorting'] == 'yes'): ?>
														<div class="portfolio-sorting-select">
															<div class="portfolio-sorting-select-current">
																<div class="portfolio-sorting-select-name">
																	<?php
																	switch ($sortby) {
																		case "date":
																			echo esc_html($params["sorting_dropdown_latest_text"]);
																			break;
																		case "popularity":
																			echo esc_html($params["sorting_dropdown_popularity_text"]);
																			break;
																		case "rating":
																			echo esc_html($params["sorting_dropdown_rating_text"]);
																			break;
																		case "price":
																			echo esc_html($params["sorting_dropdown_price_low_high_text"]);
																			break;
																		case "price-desc":
																			echo esc_html($params["sorting_dropdown_price_high_low_text"]);
																			break;
																		default:
																			echo esc_html($params['sorting_text']);
																	} ?>
																</div>
																<span class="portfolio-sorting-select-current-arrow"></span>
															</div>
															<ul>
																<li class="default <?php echo $sortby == 'default' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="<?php echo esc_attr($params['orderby']); ?>"
																	data-order="<?php echo esc_attr($params['order']); ?>"><?php echo esc_html($params['sorting_text']); ?></li>
																<li class="<?php echo $sortby == 'date' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="date"
																	data-order="desc"><?php echo esc_html($params['sorting_dropdown_latest_text']); ?>
																</li>
																<li class="<?php echo $sortby == 'popularity' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="popularity"
																	data-order="desc"><?php echo esc_html($params['sorting_dropdown_popularity_text']); ?>
																</li>
																<li class="<?php echo $sortby == 'rating' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="rating"
																	data-order="desc"><?php echo esc_html($params['sorting_dropdown_rating_text']); ?>
																</li>
																<li class="<?php echo $sortby == 'price' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="price"
																	data-order="asc"><?php echo esc_html($params['sorting_dropdown_price_low_high_text']); ?>
																</li>
																<li class="<?php echo $sortby == 'price-desc' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="price"
																	data-order="desc"><?php echo esc_html($params['sorting_dropdown_price_high_low_text']); ?>
																</li>
															</ul>
														</div>
													<?php endif; ?>

													<?php if ($params['filter_by_search'] == 'yes' && ($params['filters_style'] == 'standard' || $search_only)) { ?>
														<span>&nbsp;</span>
														<form class="portfolio-search-filter <?php echo $search_only ? 'mobile-visible' : ''; ?>"
															  role="search" action="">
															<div class="portfolio-search-filter-form">
																<input type="search"
																	   placeholder="<?php echo esc_attr($params['filters_text_labels_search_text']); ?>"
																	   value="<?php echo esc_attr($search_current); ?>">
															</div>
															<div class="portfolio-search-filter-button"></div>
														</form>
													<?php } ?>
												</div>
											<?php endif; ?>
										</div>
										<?php if ($params['product_show_filter'] == 'yes') {
											include(locate_template(array('gem-templates/products-extended/selected-filters.php')));
										} ?>
									</div>
								<?php endif; ?>
								<?php if ($params['product_show_filter'] == 'yes' && $params['filters_style'] == 'tabs'): ?>
									<div class="portfolio-top-panel">
										<div class="portfolio-filter-tabs style-<?php echo $params['filters_tabs_style']; ?> alignment-<?php echo $params['product_tabs_alignment']; ?> <?php echo $params['filters_tabs_title_separator'] == 'yes' ? 'separator' : ''; ?>">
											<?php if ($params['filters_tabs_title_text'] != '') { ?>
												<div class="title-h4 portfolio-filter-tabs-title <?php echo $params['filters_tabs_title_style_preset'] == 'thin' ? 'light' : ''; ?>"><?php echo $params['filters_tabs_title_text']; ?></div>
											<?php }
											$filter_tabs = $params['filters_tabs_tabs'];
											if (!empty($filter_tabs)) { ?>
												<ul class="portfolio-filter-tabs-list">
													<?php foreach ($filter_tabs as $index => $item) {
														if (!empty($item['filters_tabs_tab_title'])) { ?>
															<li class="portfolio-filter-tabs-list-tab <?php echo $index == $active_tab - 1 ? 'active' : ''; ?>"
																data-num="<?php echo $index + 1; ?>"
																data-filter="<?php echo $item['filters_tabs_tab_filter_by'] ?>"
																data-filter-cat="<?php echo isset($item['filters_tabs_tab_products_cat']) ? $item['filters_tabs_tab_products_cat'] : ''; ?>">
																<?php echo $item['filters_tabs_tab_title'] ?>
															</li>
														<?php }
													} ?>
												</ul>
											<?php } ?>
											<?php if ($params['pagination_type'] == 'arrows' && $params['filters_tabs_style'] == 'alternative'): ?>
												<div class="portfolio-navigator gem-pagination gem-pagination-arrows">
													<a href="" class="prev">
														<?php if ($params['pagination_arrows_left_icon']['value']) {
															Icons_Manager::render_icon($params['pagination_arrows_left_icon'], ['aria-hidden' => 'true']);
														} else { ?>
															<i class="default"></i>
														<?php } ?>
													</a>
													<a href="" class="next">
														<?php if ($params['pagination_arrows_right_icon']['value']) {
															Icons_Manager::render_icon($params['pagination_arrows_right_icon'], ['aria-hidden' => 'true']);
														} else { ?>
															<i class="default"></i>
														<?php } ?>
													</a>
												</div>
											<?php endif; ?>
										</div>
									</div>
								<?php endif; ?>
								<div class="row portfolio-row clearfix">
									<div class="portfolio-set"
										 data-max-row-height="<?php echo $params['metro_max_row_height'] ? $params['metro_max_row_height']['size'] : ''; ?>">
										<?php
										if ($product_loop->have_posts()) {
											remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
											remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
											remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
											remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
											remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
											remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
											remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
											remove_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_link', 15);
											remove_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_wishlist', 20);
											while ($product_loop->have_posts()) : $product_loop->the_post(); ?>
												<?php echo thegem_extended_products_render_item($params, $item_classes, $thegem_sizes, get_the_ID(), false, true); ?>
											<?php endwhile;
										} else { ?>
											<div class="portfolio-item not-found">
												<div class="wrap clearfix">
													<div class="image-inner"></div>
													<?php echo esc_html($params['not_found_text']); ?>
												</div>
											</div>
										<?php }
										wp_reset_postdata();
										?>
									</div><!-- .portflio-set -->
									<div class="portfolio-item-size-container">
										<?php echo thegem_extended_products_render_item($params, $item_classes); ?>
									</div>
								</div><!-- .row-->
								<?php

								/** Pagination */

								if ('yes' === ($params['show_pagination'])) : ?>
									<?php if ($params['pagination_type'] == 'normal'): ?>
										<div class="portfolio-navigator gem-pagination">
											<a href="" class="prev">
												<?php if ($params['pagination_arrows_left_icon']['value']) {
													Icons_Manager::render_icon($params['pagination_arrows_left_icon'], ['aria-hidden' => 'true']);
												} else { ?>
													<i class="default"></i>
												<?php } ?>
											</a>
											<div class="pages"></div>
											<a href="" class="next">
												<?php if ($params['pagination_arrows_right_icon']['value']) {
													Icons_Manager::render_icon($params['pagination_arrows_right_icon'], ['aria-hidden' => 'true']);
												} else { ?>
													<i class="default"></i>
												<?php } ?>
											</a>
										</div>
									<?php endif; ?>
									<?php
									if ($params['pagination_type'] == 'more' && $next_page > 0):

										$separator_enabled = !empty($params['more_show_separator']) ? true : false;

										// Container
										$classes_container = 'gem-button-container gem-widget-button ';

										if ($separator_enabled) {
											$classes_container .= 'gem-button-position-center gem-button-with-separator ';
										} else {
											if ('yes' === $params['more_stretch_full_width']) {
												$classes_container .= 'gem-button-position-fullwidth ';
											}
										}

										// Separator
										$classes_separator = 'gem-button-separator ';

										if (!empty($params['pagination_more_button_separator_style_active'])) {
											$classes_separator .= esc_attr($params['pagination_more_button_separator_style_active']);
										}

										// Link
										$classes_button = "load-more-button gem-button gem-button-text-weight-normal 
										gem-button-size-" . $params['pagination_more_button_size'] . " 
										gem-button-style-" . $params['pagination_more_button_type'] . "
										gem-button-icon-position-" . $params['pagination_more_button_icon_align'];
										?>

										<div class="portfolio-load-more">
											<div class="inner">
												<?php include(locate_template(array('gem-templates/products-extended/more-button.php'))); ?>
											</div>
										</div>
									<?php endif; ?>
									<?php if ($params['pagination_type'] == 'scroll' && $next_page > 0): ?>
										<div class="portfolio-scroll-pagination"></div>
									<?php endif; ?>
								<?php endif; ?>
								<?php if ($params['pagination_type'] == 'arrows' && ($params['filters_style'] !== 'tabs' || $params['filters_tabs_style'] == 'default')): ?>
									<div class="portfolio-navigator gem-pagination gem-pagination-arrows alignment-<?php echo $params['product_tabs_alignment']; ?>">
										<a href="#" class="prev">
											<?php if ($params['pagination_arrows_left_icon']['value']) {
												Icons_Manager::render_icon($params['pagination_arrows_left_icon'], ['aria-hidden' => 'true']);
											} else { ?>
												<i class="default"></i>
											<?php } ?>
										</a>
										<a href="#" class="next">
											<?php if ($params['pagination_arrows_right_icon']['value']) {
												Icons_Manager::render_icon($params['pagination_arrows_right_icon'], ['aria-hidden' => 'true']);
											} else { ?>
												<i class="default"></i>
											<?php } ?>
										</a>
									</div>
								<?php endif; ?>

								<?php if ($params['product_show_filter'] == 'yes' && $params['filters_style'] == 'sidebar' && !$search_only) { ?>
							</div>
						</div>
					<?php }

					thegem_woocommerce_product_page_ajax_notification($params); ?>

					</div><!-- .full-width -->
				</div><!-- .portfolio-->
			</div><!-- .portfolio-preloader-wrapper-->
		<?php

		else: ?>
			<?php if (!$is_related_upsell) { ?>
				<div class="bordered-box centered-box styled-subtitle">
					<?php echo esc_html__('Please select products in "Products" section', 'thegem') ?>
				</div>
			<?php } ?>
		<?php endif;
		$post = $portfolio_posttemp; ?>

		<script>
			(function ($) {
				$(document).ready(function () {
					$('.portfolio-filters-list .widget_layered_nav, .portfolio-filters-list .widget_product_categories').find('.count').each(function () {
						$(this).html($(this).html().replace('(', '').replace(')', '')).css('opacity', 1);
					});
				});
			})(jQuery);
		</script>

		<?php if (is_admin() && Plugin::$instance->editor->is_edit_mode()): ?>
			<script>
				if (typeof widget_settings == 'undefined') {
					var widget_settings = [];
				}
				widget_settings['<?php echo $widget_uid ?>'] = JSON.parse('<?php echo json_encode($params) ?>');
			</script>
			<script type="text/javascript">
				(function ($) {

					setTimeout(function () {
						if (!$('.elementor-element-<?php echo $this->get_id(); ?> .extended-products-grid').length) {
							return;
						}
						$('.elementor-element-<?php echo $this->get_id(); ?> .extended-products-grid').initExtendedProductsGrids();
					}, 1000);

					elementor.channels.editor.on('change', function (view) {
						var changed = view.elementSettingsModel.changed;

						if (changed.image_gaps !== undefined || changed.image_gaps_mobile !== undefined || changed.image_gaps_tablet !== undefined ||
							changed.caption_container_padding !== undefined || changed.spacing_title !== undefined || changed.spacing_description !== undefined) {
							setTimeout(function () {
								$('.elementor-element-<?php echo $this->get_id(); ?> .extended-products-grid').initExtendedProductsGrids();
							}, 500);
						}
					});

				})(jQuery);

			</script>
		<?php endif;
	}

	public function get_preset_data() {

		return array(
			/** Caption Below: Default, Cart Button */
			'below-default-cart-button' => array(
				'caption_position' => 'page',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'button',
				'product_show_add_to_cart_mobiles' => 'yes',
				'cart_button_show_icon' => 'yes',
				'labels_design' => '1',
				'sale_label_type' => 'percentage',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',
			),

			/** Caption Below: Default, Cart Icon */
			'below-default-cart-icon' => array(
				'caption_position' => 'page',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => 'yes',
				'labels_design' => '4',
				'sale_label_type' => 'text',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',
			),

			/** Caption Below: Solid, Cart Disabled */
			'below-cart-disabled' => array(
				'caption_position' => 'page',
				'product_show_add_to_cart' => '',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '3',
				'sale_label_type' => 'percentage',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 22, 'unit' => 'px'],
				'caption_container_preset' => 'gray',
				'pagination_numbers_radius' => ['top' => '100', 'right' => '100', 'bottom' => '100', 'left' => '100', 'unit' => 'px'],
				'pagination_numbers_border_type_normal_border' => 'solid',
				'pagination_numbers_border_type_hover_border' => 'solid',
				'pagination_numbers_border_type_active_border' => 'solid',
				'pagination_numbers_border_type_normal_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_hover_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_active_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
			),

			/** Caption Below: Border, Cart Icon */
			'below-border-cart-icon' => array(
				'caption_position' => 'page',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => 'yes',
				'labels_design' => '2',
				'sale_label_type' => 'text',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 12, 'unit' => 'px'],
				'full_item_border_type_width' => ['top' => '1', 'right' => '1', 'bottom' => '0', 'left' => '1', 'unit' => 'px'],
				'full_item_border_type_border' => 'solid',
				'caption_container_preset' => 'white',
				'caption_container_border_normal_width' => ['top' => '0', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'caption_container_border_normal_border' => 'solid',
			),

			/** Caption Below: Shadow Hover 01 */
			'below-shadow-hover-01' => array(
				'caption_position' => 'page',
				'product_show_add_to_cart' => '',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '3',
				'sale_label_type' => 'text',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => 'yes',

				'image_shadow_hover_box_shadow_type' => 'yes',
				'image_shadow_hover_box_shadow' => [
					'horizontal' => 0,
					'vertical' => 0,
					'blur' => 7,
					'spread' => 0,
					'color' => 'rgba(0,0,0,0.2)',
				],
				'shadowed_container' => 'yes',
				'caption_container_preset' => 'transparent',
			),

			/** Caption Below: Shadow Hover 02 */
			'below-shadow-hover-02' => array(
				'caption_position' => 'page',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'button',
				'product_show_add_to_cart_mobiles' => 'yes',
				'cart_button_show_icon' => 'yes',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '3',
				'sale_label_type' => 'percentage',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 30, 'unit' => 'px'],
				'image_border_radius' => ['top' => '12', 'right' => '12', 'bottom' => '0', 'left' => '0', 'unit' => 'px'],
				'full_item_border_type_border' => 'solid',
				'full_item_border_type_width' => ['top' => '1', 'right' => '1', 'bottom' => '0', 'left' => '1', 'unit' => 'px'],
				'full_item_border_color_hover' => '#DFE5E800',
				'image_shadow_hover_box_shadow_type' => 'yes',
				'image_shadow_hover_box_shadow' => [
					'horizontal' => 0,
					'vertical' => 0,
					'blur' => 30,
					'spread' => 0,
					'color' => 'rgba(49, 50, 51, 0.18)',
				],
				'shadowed_container' => 'yes',
				'caption_container_preset' => 'transparent',
				'caption_container_radius' => ['top' => '0', 'right' => '0', 'bottom' => '12', 'left' => '12', 'unit' => 'px'],
				'caption_container_padding' => ['top' => '23', 'right' => '20', 'bottom' => '35', 'left' => '20', 'unit' => 'px'],
				'caption_container_border_normal_border' => 'solid',
				'caption_container_border_normal_width' => ['top' => '0', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'caption_container_border_color_hover' => '#02010100',
				'icons_border_type_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'icons_border_type_border' => 'solid',
				'icons_color_normal' => '#99A9B5',
				'icons_background_color_normal' => '#02010100',
				'icons_border_color_normal' => '#99A9B5',
				'icons_color_hover' => '#FFFFFF',
				'icons_background_color_hover' => '#00BCD4',
				'icons_border_color_hover' => '#00BCD4',
				'spacing_separator_weight' => ['size' => 0, 'unit' => 'px'],
				'button_cart_color_normal' => '#99A9B5',
				'button_cart_color_hover' => '#FFFFFF',
				'button_cart_background_color_normal' => '#02010100',
				'button_cart_background_color_hover' => '#00BCD4',
				'button_cart_border_color_normal' => '#99A9B5',
				'button_cart_border_color_hover' => '#00BCD4',
				'button_options_color_normal' => '#99A9B5',
				'button_options_color_hover' => '#FFFFFF',
				'button_options_background_color_normal' => '#02010100',
				'button_options_background_color_hover' => '#00BCD4',
				'button_options_border_color_normal' => '#99A9B5',
				'button_options_border_color_hover' => '#00BCD4',
				'pagination_spacing' => ['size' => 70, 'unit' => 'px'],
				'pagination_numbers_radius' => ['top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => 'px'],
				'pagination_numbers_padding' => ['top' => '2', 'right' => '2', 'bottom' => '2', 'left' => '2', 'unit' => 'px'],
				'pagination_numbers_background_color_normal' => '#FFFFFF',
				'pagination_numbers_background_color_hover' => '#00BCD4',
				'pagination_numbers_background_color_active' => '#99A9B5',
				'pagination_numbers_border_type_normal_border' => 'solid',
				'pagination_numbers_border_type_hover_border' => 'solid',
				'pagination_numbers_border_type_active_border' => 'solid',
				'pagination_numbers_border_type_normal_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_hover_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_active_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_active_color' => '#99A9B5',
				'pagination_numbers_border_type_hover_color' => '#00BCD4',
				'pagination_numbers_text_color_hover' => '#FFFFFF',
				'pagination_numbers_text_color_active' => '#FFFFFF',
				'pagination_numbers_text_typography_normal_typography' => 'custom',
				'pagination_numbers_text_typography_normal_font_weight' => 400,
				'pagination_arrows_left_icon' => ['value' => 'gem-elegant arrow-carrot-left', 'library' => 'thegem-elegant'],
				'pagination_arrows_right_icon' => ['value' => 'gem-elegant arrow-carrot-right', 'library' => 'thegem-elegant'],
				'pagination_arrows_icon_size' => ['size' => 16, 'unit' => 'px'],
				'pagination_arrows_background_color_hover' => '#FFFFFF',
				'pagination_arrows_border_type_normal_border' => 'solid',
				'pagination_arrows_border_type_hover_border' => 'solid',
				'pagination_arrows_border_type_normal_color' => '#FFFFFF',
				'pagination_arrows_border_type_hover_color' => '#00BCD4',
				'pagination_arrows_icon_color_normal' => '#99A9B5',
				'pagination_arrows_icon_color_hover' => '#00BCD4',
			),

			/** Caption Below: Rounded Images */
			'below-rounded-images' => array(
				'caption_position' => 'page',
				'product_show_add_to_cart' => '',
				'labels_design' => '1',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'sale_label_type' => 'percentage',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 42, 'unit' => 'px'],
				'image_border_radius' => ['top' => '24', 'right' => '24', 'bottom' => '24', 'left' => '24', 'unit' => 'px'],
				'caption_container_preset' => 'transparent',
				'pagination_numbers_radius' => ['top' => '100', 'right' => '100', 'bottom' => '100', 'left' => '100', 'unit' => 'px'],
			),

			/** Caption Below: Rectangle Button 01 */
			'below-rectangle-button-01' => array(
				'caption_position' => 'page',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'button',
				'product_show_add_to_cart_mobiles' => 'yes',
				'cart_button_show_icon' => '',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '3',
				'sale_label_type' => 'text',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 32, 'unit' => 'px'],
				'caption_container_preset' => 'transparent',
				'caption_container_alignment' => 'left',
				'caption_container_padding' => ['top' => '20', 'right' => '0', 'bottom' => '20', 'left' => '0', 'unit' => 'px'],
				'buttons_border_radius' => ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px'],
			),

			/** Caption Below: Rectangle Button 02 */
			'below-rectangle-button-02' => array(
				'caption_position' => 'page',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'button',
				'product_show_add_to_cart_mobiles' => 'yes',
				'cart_button_show_icon' => 'yes',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '1',
				'sale_label_type' => 'percentage',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 16, 'unit' => 'px'],
				'caption_container_preset' => 'transparent',
				'caption_container_alignment' => 'left',
				'caption_container_padding' => ['top' => '0', 'right' => '20', 'bottom' => '20', 'left' => '0', 'unit' => 'px'],
				'spacing_categories_top' => ['size' => 13, 'unit' => 'px'],
				'spacing_title' => ['top' => '8', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px'],
				'spacing_price' => ['top' => '7', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px'],
				'buttons_border_radius' => ['top' => '3', 'right' => '3', 'bottom' => '3', 'left' => '3', 'unit' => 'px'],
				'buttons_text_padding' => ['top' => '10', 'right' => '16', 'bottom' => '10', 'left' => '16', 'unit' => 'px'],
				'button_options_background_color_normal' => '#DFE5E8',
				'button_options_background_color_hover' => '#00BCD4',
				'button_options_border_color_normal' => '#02010100',
				'pagination_spacing' => ['size' => 70, 'unit' => 'px'],
				'pagination_numbers_radius' => ['top' => '3', 'right' => '3', 'bottom' => '3', 'left' => '3', 'unit' => 'px'],
				'pagination_numbers_background_color_normal' => '#FFFFFF',
				'pagination_numbers_background_color_hover' => '#00BCD4',
				'pagination_numbers_background_color_active' => '#99A9B5',
				'pagination_numbers_border_type_normal_border' => 'solid',
				'pagination_numbers_border_type_hover_border' => 'solid',
				'pagination_numbers_border_type_active_border' => 'solid',
				'pagination_numbers_border_type_normal_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_hover_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_active_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_active_color' => '#99A9B5',
				'pagination_numbers_border_type_hover_color' => '#00BCD4',
				'pagination_numbers_text_color_hover' => '#FFFFFF',
				'pagination_numbers_text_color_active' => '#FFFFFF',
				'pagination_numbers_text_typography_normal_typography' => 'custom',
				'pagination_numbers_text_typography_normal_font_weight' => 400,
				'pagination_arrows_left_icon' => ['value' => 'gem-elegant arrow-carrot-left', 'library' => 'thegem-elegant'],
				'pagination_arrows_right_icon' => ['value' => 'gem-elegant arrow-carrot-right', 'library' => 'thegem-elegant'],
				'pagination_arrows_icon_size' => ['size' => 16, 'unit' => 'px'],
				'pagination_arrows_background_color_hover' => '#FFFFFF',
				'pagination_arrows_border_type_normal_border' => 'solid',
				'pagination_arrows_border_type_hover_border' => 'solid',
				'pagination_arrows_border_type_normal_color' => '#FFFFFF',
				'pagination_arrows_border_type_hover_color' => '#00BCD4',
				'pagination_arrows_icon_color_normal' => '#99A9B5',
				'pagination_arrows_icon_color_hover' => '#00BCD4',
			),

			/** Caption Below: Product Separator 01 */
			'below-separator-01' => array(
				'caption_position' => 'page',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'button',
				'product_show_add_to_cart_mobiles' => 'yes',
				'cart_button_show_icon' => 'yes',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '4',
				'sale_label_type' => 'percentage',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 36, 'unit' => 'px'],
				'product_separator' => 'yes',
				'product_separator_color' => '#F0F3F2',
				'product_separator_width' => ['size' => 2, 'unit' => 'px'],
				'title_color_normal' => '#5F727F',
				'price_color_normal' => '#5F727F',
				'caption_container_preset' => 'gray',
				'caption_container_background_page_hover_background' => 'classic',
				'caption_container_background_page_hover_color' => '#FFFFFF',
				'spacing_price' => ['top' => '2', 'right' => '0', 'bottom' => '2', 'left' => '0', 'unit' => 'px'],
				'pagination_numbers_radius' => ['top' => '5', 'right' => '5', 'bottom' => '5', 'left' => '5', 'unit' => 'px'],
			),

			/** Caption Below: Product Separator 02 */
			'below-separator-02' => array(
				'caption_position' => 'page',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => 'yes',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'labels_design' => '6',
				'sale_label_type' => 'percentage',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 0, 'unit' => 'px'],
				'product_separator' => 'yes',
				'product_separator_color' => '#212227',
				'product_separator_width' => ['size' => 1, 'unit' => 'px'],
				'caption_container_preset' => 'transparent',
				'caption_container_background_page_hover_background' => 'classic',
				'caption_container_background_page_hover_color' => '#FFFFFF',
				'caption_container_padding' => ['top' => '10', 'right' => '10', 'bottom' => '10', 'left' => '10', 'unit' => 'px'],
				'spacing_price' => ['top' => '2', 'right' => '0', 'bottom' => '2', 'left' => '0', 'unit' => 'px'],
				'icons_spacing' => ['size' => 0, 'unit' => 'px'],
				'icons_background_color_normal' => '#02010100',
				'icons_color_hover' => '#00BCD4',
				'pagination_numbers_border_type_normal_border' => 'solid',
				'pagination_numbers_border_type_hover_border' => 'solid',
				'pagination_numbers_border_type_active_border' => 'solid',
				'pagination_numbers_border_type_normal_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_hover_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_active_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
			),

			/** Caption on Image: Default, Cart Button */
			'image-default-cart-button' => array(
				'caption_position' => 'image',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'button',
				'product_show_add_to_cart_mobiles' => '',
				'cart_button_show_icon' => 'yes',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '3',
				'sale_label_type' => 'percentage',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',
			),

			/** Caption on Image: Default, Cart Icon */
			'image-default-cart-icon' => array(
				'caption_position' => 'image',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '4',
				'sale_label_type' => 'text',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',
			),

			/** Caption on Image: Solid Caption Background */
			'image-solid-background' => array(
				'caption_position' => 'image',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '3',
				'sale_label_type' => 'text',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'caption_container_preset_hover' => 'solid',
			),

			/** Caption on Image: Rounded Corners */
			'image-rounded-corners' => array(
				'caption_position' => 'image',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '1',
				'sale_label_type' => 'percentage',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_border_radius' => ['top' => '24', 'right' => '24', 'bottom' => '24', 'left' => '24', 'unit' => 'px'],
				'caption_container_alignment_hover' => 'center',
				'pagination_numbers_radius' => ['top' => '5', 'right' => '5', 'bottom' => '5', 'left' => '5', 'unit' => 'px'],
				'pagination_numbers_background_color_normal' => '#99a9b5',
				'pagination_numbers_background_color_hover' => '#00BCD4',
				'pagination_numbers_background_color_active' => '#3c3950',
				'pagination_numbers_border_type_normal_border' => 'solid',
				'pagination_numbers_border_type_hover_border' => 'solid',
				'pagination_numbers_border_type_active_border' => 'solid',
				'pagination_numbers_border_type_normal_width' => ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px'],
				'pagination_numbers_border_type_hover_width' => ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px'],
				'pagination_numbers_border_type_active_width' => ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px'],
				'pagination_numbers_text_color_normal' => '#FFFFFF',
			),

			/** Caption on Image: Shadow Hover 01 */
			'image-shadow-hover-01' => array(
				'caption_position' => 'image',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '2',
				'sale_label_type' => 'text',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 22, 'unit' => 'px'],
				'image_shadow_hover_box_shadow_type' => 'yes',
				'image_shadow_hover_box_shadow' => [
					'horizontal' => 0,
					'vertical' => 0,
					'blur' => 7,
					'spread' => 0,
					'color' => 'rgba(0, 0, 0, 0.25)',
				],
				'caption_container_alignment_hover' => 'center',
			),

			/** Caption on Image: Shadow */
			'image-shadow' => array(
				'caption_position' => 'image',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'button',
				'product_show_add_to_cart_mobiles' => '',
				'cart_button_show_icon' => 'yes',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '2',
				'sale_label_type' => 'percentage',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_border_radius' => ['top' => '10', 'right' => '10', 'bottom' => '10', 'left' => '10', 'unit' => 'px'],
				'image_content_paddings' => ['top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'unit' => 'px'],
				'image_shadow_normal_box_shadow_type' => 'yes',
				'image_shadow_normal_box_shadow' => [
					'horizontal' => 0,
					'vertical' => 0,
					'blur' => 40,
					'spread' => 5,
					'color' => 'rgba(33, 34, 39, 0.2)',
				],
				'image_shadow_hover_box_shadow_type' => 'yes',
				'image_shadow_hover_box_shadow' => [
					'horizontal' => 0,
					'vertical' => 10,
					'blur' => 50,
					'spread' => 10,
					'color' => 'rgba(33, 34, 39, 0.3)',
				],
				'caption_container_alignment_hover' => 'right',
				'pagination_numbers_radius' => ['top' => '100', 'right' => '100', 'bottom' => '100', 'left' => '100', 'unit' => 'px'],
				'pagination_numbers_background_color_normal' => '#99a9b5',
				'pagination_numbers_background_color_hover' => '#00BCD4',
				'pagination_numbers_background_color_active' => '#3c3950',
				'pagination_numbers_border_type_active_border' => 'solid',
				'pagination_numbers_border_type_active_width' => ['top' => '2', 'right' => '2', 'bottom' => '2', 'left' => '2', 'unit' => 'px'],
				'pagination_numbers_border_type_active_color' => '#3C3950',
				'pagination_numbers_text_color_normal' => '#FFFFFF',
			),

			/** Caption on Image: Product Separator 01 */
			'image-separator-01' => array(
				'caption_position' => 'image',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '3',
				'sale_label_type' => 'text',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 0, 'unit' => 'px'],
				'image_content_paddings' => ['top' => '16', 'right' => '16', 'bottom' => '16', 'left' => '16', 'unit' => 'px'],
				'product_separator' => 'yes',
				'product_separator_color' => '#DFE5E8',
				'product_separator_width' => ['size' => 4, 'unit' => 'px'],
				'caption_container_alignment_hover' => 'center',
				'pagination_numbers_background_color_active' => '#3c3950',
				'pagination_numbers_border_type_normal_border' => 'solid',
				'pagination_numbers_border_type_hover_border' => 'solid',
				'pagination_numbers_border_type_active_border' => 'solid',
				'pagination_numbers_border_type_normal_width' => ['top' => '4', 'right' => '4', 'bottom' => '4', 'left' => '4', 'unit' => 'px'],
				'pagination_numbers_border_type_hover_width' => ['top' => '4', 'right' => '4', 'bottom' => '4', 'left' => '4', 'unit' => 'px'],
				'pagination_numbers_border_type_active_width' => ['top' => '4', 'right' => '4', 'bottom' => '4', 'left' => '4', 'unit' => 'px'],
				'pagination_numbers_border_type_normal_color' => '#dfe5eb',
				'pagination_numbers_border_type_hover_color' => '#00BCD4',
				'pagination_numbers_border_type_active_color' => '#3C3950',
				'pagination_arrows_background_color_normal' => '#DFE5E8',
				'pagination_arrows_background_color_hover' => '#00BCD4',
			),

			/** Caption on Image: Product Separator 02 */
			'image-separator-02' => array(
				'caption_position' => 'image',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'labels_design' => '5',
				'sale_label_type' => 'percentage',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 0, 'unit' => 'px'],
				'product_separator' => 'yes',
				'product_separator_color' => '#212227',
				'product_separator_width' => ['size' => 1, 'unit' => 'px'],
				'image_hover_effect_image' => 'gradient',
				'image_hover_overlay_background' => 'classic',
				'image_hover_overlay_color' => '#F0F3F200',
				'caption_container_preset_hover' => 'solid',
				'caption_container_alignment_hover' => 'center',
				'icons_background_color_normal' => '#02010100',
				'pagination_numbers_border_type_normal_border' => 'solid',
				'pagination_numbers_border_type_hover_border' => 'solid',
				'pagination_numbers_border_type_active_border' => 'solid',
				'pagination_numbers_border_type_normal_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_hover_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_active_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
			),

			/** Caption on Hover: Default */
			'hover-default' => array(
				'caption_position' => 'hover',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => 'yes',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '4',
				'sale_label_type' => 'text',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',
			),

			/** Caption on Hover: Rounded Corners */
			'hover-rounded-corners' => array(
				'caption_position' => 'hover',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => 'yes',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'labels_design' => '1',
				'sale_label_type' => 'percentage',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_border_radius' => ['top' => '24', 'right' => '24', 'bottom' => '24', 'left' => '24', 'unit' => 'px'],
				'image_hover_effect_hover' => 'zooming-blur',
				'image_hover_overlay_background' => 'classic',
				'image_hover_overlay_color' => '#F0F3F2D4',
				'pagination_numbers_radius' => ['top' => '5', 'right' => '5', 'bottom' => '5', 'left' => '5', 'unit' => 'px'],
			),

			/** Caption on Hover: Solid Caption Background */
			'hover-solid-background' => array(
				'caption_position' => 'hover',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => 'yes',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'product_show_reviews' => '',
				'product_show_reviews_tablet' => '',
				'product_show_reviews_mobile' => '',
				'labels_design' => '2',
				'sale_label_type' => 'text',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 32, 'unit' => 'px'],
				'caption_container_preset_hover' => 'solid',
			),

			/** Caption on Hover: Product Separator */
			'hover-separator' => array(
				'caption_position' => 'hover',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => 'yes',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'labels_design' => '3',
				'sale_label_type' => 'text',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 0, 'unit' => 'px'],
				'product_separator' => 'yes',
				'product_separator_color' => '#dfe5e8',
				'product_separator_width' => ['size' => 1, 'unit' => 'px'],
				'caption_container_alignment_hover' => 'center',
				'pagination_numbers_border_type_normal_border' => 'solid',
				'pagination_numbers_border_type_hover_border' => 'solid',
				'pagination_numbers_border_type_active_border' => 'solid',
				'pagination_numbers_border_type_normal_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_hover_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_active_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
			),

			/** Caption on Hover: Centered Caption */
			'hover-centered-caption' => array(
				'caption_position' => 'hover',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'button',
				'product_show_add_to_cart_mobiles' => 'yes',
				'cart_button_show_icon' => 'yes',
				'product_show_categories' => '',
				'product_show_categories_tablet' => '',
				'product_show_categories_mobile' => '',
				'product_show_new' => '',
				'product_show_sale' => '',
				'product_show_out' => '',
				'social_sharing' => '',

				'image_gaps' => ['size' => 6, 'unit' => 'px'],
				'image_content_paddings' => ['top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'unit' => 'px'],
				'caption_container_height_hover' => ['size' => 70, 'unit' => '%'],
				'caption_container_background_hover_background' => 'gradient',
				'caption_container_background_hover_color' => '#F0F3F200',
				'caption_container_background_hover_color_b' => '#F0F3F2',
				'caption_container_alignment_hover' => 'center',
				'pagination_numbers_radius' => ['top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => 'px'],
				'pagination_numbers_border_type_normal_border' => 'solid',
				'pagination_numbers_border_type_hover_border' => 'solid',
				'pagination_numbers_border_type_active_border' => 'solid',
				'pagination_numbers_border_type_normal_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_hover_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
				'pagination_numbers_border_type_active_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
			),

			/** Caption on Hover: Shadow Hover */
			'hover-shadow-hover' => array(
				'caption_position' => 'hover',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => 'yes',
				'labels_design' => '1',
				'sale_label_type' => 'percentage',
				'product_show_new' => 'yes',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 26, 'unit' => 'px'],
				'image_content_paddings' => ['top' => '10', 'right' => '30', 'bottom' => '0', 'left' => '30', 'unit' => 'px'],
				'image_hover_effect_hover' => 'zooming-blur',
				'image_shadow_hover_box_shadow_type' => 'yes',
				'image_shadow_hover_box_shadow' => [
					'horizontal' => 0,
					'vertical' => 0,
					'blur' => 20,
					'spread' => 0,
					'color' => 'rgba(29, 42, 47, 0.21)',
				],
			),

			/** Caption on Hover: Gradient Hover */
			'hover-gradient-hover' => array(
				'caption_position' => 'hover',
				'product_show_add_to_cart' => 'yes',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => 'yes',
				'labels_design' => '1',
				'sale_label_type' => 'percentage',
				'product_show_new' => '',
				'product_show_sale' => 'yes',
				'product_show_out' => 'yes',
				'social_sharing' => '',

				'image_gaps' => ['size' => 16, 'unit' => 'px'],
				'image_content_paddings' => ['top' => '30', 'right' => '30', 'bottom' => '30', 'left' => '30', 'unit' => 'px'],
				'image_hover_effect_hover' => 'gradient',
				'image_hover_overlay_background' => 'gradient',
				'image_hover_overlay_color' => '#FFDE1596',
				'image_hover_overlay_color_b' => '#E9135CBA',
				'image_hover_overlay_gradient_angle' => ['size' => 225, 'unit' => 'deg'],
			),

		);
	}
}

if (defined('WC_PLUGIN_FILE') && function_exists('get_thegem_extended_products_render_item_image_sizes')) {
	\Elementor\Plugin::instance()->widgets_manager->register(new TheGem_ExtendedProductsGrid());
}