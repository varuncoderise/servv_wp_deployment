<?php
namespace TheGem_Elementor;
use Elementor\Controls_Manager;

/**
 * Class Plugin
 * Main Plugin class
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * widget_global_scripts
	 * Load global scripts & css files required plugin core.
	 *
	 * @access public
	 */
	public function widget_global_scripts() {

	}

	/**
	 * Register custom category for widgets
	 * @access public
	 */
	public function widget_categories( $el_manager ) {
		$el_manager->add_category(
			'thegem_elements',
			[
				'title' => __( 'TheGem Elements', 'thegem' ),
			]
		);
		$el_manager->add_category(
			'thegem_portfolios',
			[
				'title' => __( 'TheGem Portfolios', 'thegem' ),
			]
		);
		$el_manager->add_category(
			'thegem_blog',
			[
				'title' => __( 'TheGem Blog', 'thegem' ),
			]
		);
		$el_manager->add_category(
			'thegem_woocommerce',
			[
				'title' => __( 'TheGem WooCommerce', 'thegem' ),
			]
		);
		$el_manager->add_category(
			'thegem_header_builder',
			[
				'title' => __( 'TheGem Header Builder', 'thegem' ),
			]
		);
		$el_manager->add_category(
			'thegem_single_product_builder',
			[
				'title' => __( 'TheGem Single Product Builder', 'thegem' ),
			]
		);
		$el_manager->add_category(
			'thegem_product_archive_builder',
			[
				'title' => __( 'TheGem Product Archives Builder', 'thegem' ),
			]
		);
		$el_manager->add_category(
			'thegem_blog_archive_builder',
			[
				'title' => __( 'TheGem Blog Archives Builder', 'thegem' ),
			]
		);
		$el_manager->add_category(
			'thegem_cart_builder',
			[
				'title' => __( 'TheGem Cart Builder', 'thegem' ),
			]
		);
		$el_manager->add_category(
			'thegem_checkout_builder',
			[
				'title' => __( 'TheGem Checkout Builder', 'thegem' ),
			]
		);
		$el_manager->add_category(
			'thegem_checkout_thanks_builder',
			[
				'title' => __( 'Purchase Summary Builder', 'thegem' ),
			]
		);
		$el_manager->add_category(
			'thegem_megamenu_builder',
			[
				'title' => __( 'TheGem Mega-Menu Builder', 'thegem' ),
			]
		);
		$el_manager->add_category(
			'thegem_single_post_builder',
			[
				'title' => __( 'TheGem Single Post Builder', 'thegem' ),
			]
		);
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @access public
	 */
	public function register_widgets() {
		if(function_exists('thegem_scripts')) {
			foreach ( glob( THEGEM_ELEMENTOR_DIR . '/widgets/*/widget.php' ) as $filename ) {
				if ( empty( $filename ) || ! is_readable( $filename ) ) {
					continue;
				}
				require $filename;
			}
		}
	}

	public function register_ajax() {
		foreach ( glob( THEGEM_ELEMENTOR_DIR . '/widgets/*/ajax.php' ) as $filename ) {
			if ( empty( $filename ) || ! is_readable( $filename ) ) {
				continue;
			}
			require $filename;
		}
	}

	/**
	 * Register Dynamic Tags
	 *
	 * Register new Elementor dynamic tags.
	 *
	 * @access public
	 */
	public function register_dynamic_tags($dynamic_tags) {

		$tags = array(
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/custom-title-title.php',
				'class' => 'TheGem_Elementor\DynamicTags\Custom_Title_Title',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/custom-title-rich-title.php',
				'class' => 'TheGem_Elementor\DynamicTags\Custom_Title_Rich_Title',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/custom-title-excerpt.php',
				'class' => 'TheGem_Elementor\DynamicTags\Custom_Title_Excerpt',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/custom-title-background.php',
				'class' => 'TheGem_Elementor\DynamicTags\Custom_Title_Background',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/custom-title-color.php',
				'class' => 'TheGem_Elementor\DynamicTags\Custom_Title_Color',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/custom-title-excerpt-color.php',
				'class' => 'TheGem_Elementor\DynamicTags\Custom_Title_Excerpt_Color',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/custom-title-background-color.php',
				'class' => 'TheGem_Elementor\DynamicTags\Custom_Title_Background_Color',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/custom-title-background-video.php',
				'class' => 'TheGem_Elementor\DynamicTags\Custom_Title_Background_Video',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/custom-title-video-poster.php',
				'class' => 'TheGem_Elementor\DynamicTags\Custom_Title_Video_Poster',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/custom-title-video-overlay.php',
				'class' => 'TheGem_Elementor\DynamicTags\Custom_Title_Video_Overlay',
			),
			//array(
			//	'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/post-title-icon.php',
			// 	'class' => 'TheGem_Elementor\DynamicTags\Post_Title_Icon',
			//),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/post-title.php',
				'class' => 'TheGem_Elementor\DynamicTags\Post_Title',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/post-excerpt.php',
				'class' => 'TheGem_Elementor\DynamicTags\Post_Excerpt',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/post-featured-image.php',
				'class' => 'TheGem_Elementor\DynamicTags\Post_Featured_Image',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/post-date.php',
				'class' => 'TheGem_Elementor\DynamicTags\Post_Date',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/post-time.php',
				'class' => 'TheGem_Elementor\DynamicTags\Post_Time',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/author-name.php',
				'class' => 'TheGem_Elementor\DynamicTags\Author_Name',
			),
			array(
				'file'  =>  THEGEM_ELEMENTOR_DIR . '/dynamic-tags/comments-number.php',
				'class' => 'TheGem_Elementor\DynamicTags\Comments_Number',
			),
		);

		if(get_post_type() === 'thegem_title' || (get_post_type() === 'thegem_templates' && thegem_get_template_type(get_the_id()) === 'title') || get_post_type() === 'blocks') {
			\Elementor\Plugin::instance()->dynamic_tags->register_group( 'thegem-title' , [
				'title' => 'TheGem Custom Title'
			] );
		} else {
			\Elementor\Plugin::instance()->dynamic_tags->register_group( 'thegem' , [
				'title' => 'TheGem'
			] );
		}

		foreach ( $tags as $tag ) {
			if( ! empty( $tag['file'] ) && ! empty( $tag['class'] ) ){
				include_once( $tag['file'] );
				if( class_exists( $tag['class'] ) ){
					$class_name = $tag['class'];
				}
				$dynamic_tags->register( new $class_name() );
			}
		}
	}

	/**
	 * Section Change Gaps Control
	 *
	 * Change default elementor section gaps to TheGem theme dafault.
	 *
	 * @access public
	 */
	public function section_change_gaps_control($widget, $args) {
		$default_value = 'thegem';
		if(thegem_get_template_type( get_the_ID() ) === 'popup') {
			$default_value = 'default';
		}
		$widget->update_control(
			'gap',
			[
				'label' => __( 'Columns Gap', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => $default_value,
				'options' => [
					'default' => __( 'Default', 'elementor' ),
					'no' => __( 'No Gap', 'elementor' ),
					'narrow' => __( 'Narrow', 'elementor' ),
					'extended' => __( 'Extended', 'elementor' ),
					'wide' => __( 'Wide', 'elementor' ),
					'wider' => __( 'Wider', 'elementor' ),
					'thegem' => __( 'TheGem', 'elementor' ),
				],
			]
		);
	}

	public function section_offset_control($widget, $args) {
		$widget->start_injection( [
			'at' => 'before',
			'of' => 'column_position',
		] );
		$widget->add_responsive_control(
			'thegem_offset',
			[
				'label' => __( 'Offset', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%', 'vh', 'vw' ],
				'selectors' => [
					'{{WRAPPER}}' => 'height: calc(100vh - {{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'height' => [ 'full' ],
				],
			]
		);
		$widget->end_injection();
	}

	public function global_change_lightbox_control($widget, $args) {
		$widget->update_control(
			'elementor_global_image_lightbox',
			[
				'default' => 'no'
			]
		);
	}

	public function change_template($post_ID, $post, $update) {
		if(!$update && ('yes' !== get_transient( 'wc_installing' )) && $post->post_name !== 'wishlist' && (empty($_REQUEST['action']) || $_REQUEST['action'] !== 'thegem_importer_process')) {
			if($post->post_type === 'page') {
				update_post_meta($post_ID, '_wp_page_template', 'page-fullwidth.php');
			} elseif(in_array($post->post_type, array('post', 'thegem_pf_item'), true)) {
				update_post_meta($post_ID, '_wp_page_template', 'single-fullwidth.php');
			} elseif($post->post_type === 'thegem_title') {
				update_post_meta($post_ID, '_wp_page_template', 'single-thegem_title-fullwidth.php');
			} elseif($post->post_type === 'thegem_footer') {
				update_post_meta($post_ID, '_wp_page_template', 'single-thegem_footer-fullwidth.php');
			}
		}
	}

	public function enqueue_editor_scripts() {
		wp_enqueue_script('thegem-elementor-editor', plugins_url( 'assets/js/editor.js', __FILE__ ), array('elementor-editor'), false, true);
		wp_localize_script('thegem-elementor-editor', 'thegemElementor', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'secret' => wp_create_nonce('thegem-elementor-secret')
		));
	}

	public function editor_after_enqueue_styles() {
		wp_enqueue_style('thegem-elementor-editor', plugins_url( 'assets/css/editor.css', __FILE__ ), array('elementor-editor'));
	}

	public function enqueue_preview_styles() {
		wp_enqueue_style('thegem-elementor-editor-preview', plugins_url( 'assets/css/editor-preview.css', __FILE__ ), array('editor-preview'));
	}

	public function get_preset_settings() {
		if ( ! check_ajax_referer( 'thegem-elementor-secret', 'secret' ) ) {
			wp_send_json_error( __( 'Invalid request', 'thegem' ), 403 );
		}

		if ( empty( $_REQUEST['widget'] ) ) {
			wp_send_json_error( __( 'Incomplete request', 'thegem' ), 404 );
		}

		$widget = \Elementor\Plugin::instance()->widgets_manager->get_widget_types($_REQUEST['widget']);

		$data = false;
		if(method_exists($widget, 'get_preset_data')) {
			$data = $widget->get_preset_data();
		}

		if ( !$data ) {
			wp_send_json_error( __( 'Not found', 'thegem' ), 404 );
		}

		wp_send_json_success( $data, 200 );
	}

	public function add_thegem_icons_tabs($tabs) {
		$theme_url = THEGEM_THEME_URI;
		$tabs['thegem-elegant'] = [
			'name' => 'thegem-elegant',
			'label' => __( 'TheGem - Elegant', 'thegem' ),
			'url' => $theme_url . '/css/icons-elegant.css',
			'enqueue' => [ $theme_url . '/css/icons-elegant.css' ],
			'prefix' => '',
			'displayPrefix' => 'gem-elegant',
			'labelIcon' => 'gem-elegant gem-elegant-label',
			'ver' => '1.0.0',
			'fetchJson' => THEGEM_ELEMENTOR_URL . '/assets/icons/gem-elegant.js',
			'native' => true,
		];
		$tabs['thegem-mdi'] = [
			'name' => 'thegem-mdi',
			'label' => __( 'TheGem - Material Design', 'thegem' ),
			'url' => $theme_url . '/css/icons-material.css',
			'enqueue' => [ $theme_url . '/css/icons-material.css' ],
			'prefix' => 'mdi-',
			'displayPrefix' => 'gem-mdi',
			'labelIcon' => 'gem-mdi gem-mdi-label',
			'ver' => '1.0.0',
			'fetchJson' => THEGEM_ELEMENTOR_URL . '/assets/icons/gem-mdi.js',
			'native' => true,
		];
		$tabs['thegem-hbi'] = [
			'name' => 'thegem-hbi',
			'label' => __( 'TheGem - Header Builder', 'thegem' ),
			'url' => $theme_url . '/css/icons-thegem-header.css',
			'enqueue' => [ $theme_url . '/css/icons-thegem-header.css' ],
			'prefix' => '',
			'displayPrefix' => 'tgh-icon',
			'labelIcon' => 'tgh-icon tgh-icon-label',
			'ver' => '1.0.0',
			'fetchJson' => THEGEM_ELEMENTOR_URL . '/assets/icons/gem-hbi.js',
			'native' => true,
		];
		return $tabs;
	}

	public function widgets_black_list($black_list) {
		global $wp_widget_factory;
		foreach ( $wp_widget_factory->widgets as $widget_class => $widget_obj ) {

			if(substr($widget_class, 0, 7) === 'The_Gem') {
				$black_list[] = $widget_class;
			}

			if ( in_array( $widget_class, $black_list ) ) {
				continue;
			}
		}
		return $black_list;
	}

	public function remove_experiments_additional_custom_breakpoints($exp_manager) {
		$exp_manager->remove_feature('additional_custom_breakpoints');
	}

	public function fix_custom_title($post_css) {
		if(get_post_type($post_css->get_post_id()) === 'thegem_title' || (get_post_type($post_css->get_post_id()) === 'thegem_templates' && thegem_get_template_type($post_css->get_post_id()) === 'title')) {
			$post_css->get_stylesheet()->add_rules('body:not(.elementor-editor-active):not(.elementor-editor-preview) .page-title-block.custom-page-title .elementor', array('opacity' => '1'));
		}
	}

	public function include_controls() {
		require_once( __DIR__ . '/controls/background-light.php' );
		require_once( __DIR__ . '/controls/query-control.php' );
	}

	public function register_query_control() {
		$controls_manager = \Elementor\Plugin::$instance->controls_manager;
		$controls_manager->register( new QueryControl() );
	}

	public function register_controls() {
		$controls_manager = \Elementor\Plugin::$instance->controls_manager;
		$controls_manager->add_group_control( 'background-light', new Group_Control_Background_Light() );
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @access public
	 */
	public function __construct() {

		// Register global widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_global_scripts' ] );

		// Register categories
		add_action( 'elementor/elements/categories_registered', [ $this, 'widget_categories'] );

		// Register widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

		// Register dynamic tags
		add_action( 'elementor/dynamic_tags/register', [ $this, 'register_dynamic_tags' ] );

		//Change section default gap
		add_action( 'elementor/element/section/section_layout/before_section_end', [ $this, 'section_change_gaps_control' ], 10, 2);

		//Change section default gap
		add_action( 'elementor/element/section/section_layout/before_section_end', [ $this, 'section_offset_control' ], 10, 2);

		//Change default LightBox
		add_action( 'ementor/element/global-settings/lightbox/before_section_end', [ $this, 'global_change_lightbox_control' ], 10, 2);

		//Change page default template
		add_action( 'wp_insert_post', [ $this, 'change_template' ], 10, 3);
		add_filter( 'default_page_template_title', function() { return esc_html__('TheGem Boxed', 'thegem'); });

		//Fix Custom Title
		add_action( 'elementor/css-file/post/parse', [ $this, 'fix_custom_title' ] );

		// Add Scripts
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ], 20 );

		add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_preview_styles' ] );

		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_after_enqueue_styles' ] );

		add_action( 'wp_ajax_thegem_elementor_get_preset_settings', [ $this, 'get_preset_settings' ] );

		add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'add_thegem_icons_tabs' ] );

		add_filter( 'elementor/widgets/black_list', [ $this, 'widgets_black_list' ] );

		add_action('elementor/experiments/default-features-registered', [ $this, 'remove_experiments_additional_custom_breakpoints' ]);

		// Include controls files
		$this->include_controls();

		// Register controls
		add_action( 'elementor/controls/register', [ $this, 'register_query_control' ] );
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

		require THEGEM_ELEMENTOR_DIR . '/widgets/section-parallax/init.php';
		require THEGEM_ELEMENTOR_DIR . '/widgets/section-fullpage/init.php';
		require THEGEM_ELEMENTOR_DIR . '/widgets/heading-extended/init.php';
		require THEGEM_ELEMENTOR_DIR . '/widgets/divider-extended/init.php';
		require THEGEM_ELEMENTOR_DIR . '/widgets/text-editor-dropcap/init.php';
		require THEGEM_ELEMENTOR_DIR . '/widgets/interactions/init.php';

		$this->register_ajax();

		require THEGEM_ELEMENTOR_DIR . '/thegem-options-section.php';
		require THEGEM_ELEMENTOR_DIR . '/editor-styles.php';

	}
}

// Instantiate Plugin Class
Plugin::instance();
