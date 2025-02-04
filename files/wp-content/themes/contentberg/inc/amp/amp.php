<?php
/**
 * AMP support
 */
class Bunyad_Theme_Amp 
{
	/**
	 * Layout classes collected by the sanitizer
	 */
	public $layout_classes;

	/**
	 * Map of original => min classes
	 */
	public $min_map;

	public function __construct()
	{
		add_action('after_setup_theme', array($this, 'init'));
	}

	public function init()
	{
		// If not enabled or the plugin's not the right version
		if (!Bunyad::options()->amp_enabled OR !defined('BUNYAD_AMP')) {
			return;
		}

		// Add our sanitizer
		add_filter('amp_content_sanitizers', array($this, 'add_sanitizer'));

		// Add support 
		add_theme_support('amp', array(
			'paired' => true,
			// 'templates_supported' => array(
			// 'is_singular' => true, 
			// 'is_front_page' => false, 
			// 'is_home' => false
			// ),
		));

		add_action('wp', array($this, 'setup'));

		// Disable legacy customizer
		add_filter('amp_customizer_is_enabled', '__return_false');

		// Needed for Autoptimize not playing well with 1.0
		if (isset($_GET['amp'])) {
			add_filter('autoptimize_filter_noptimize', '__return_true');
		}

		add_filter('init', array($this, 'fix_validation'));
		add_action('admin_init', array($this, 'validation_menu'));
	}

	/**
	 * Setup later than parse_query when is_amp_endpoint() is available
	 */
	public function setup()
	{

		// Front-end only
		if (!$this->active()) {
			return;
		}

		/**
		 * Mobile menu changes
		 */
		require_once get_template_directory() . '/inc/amp/menu-walker.php';

		add_filter('wp_nav_menu_objects', array($this, 'store_menu_state'), 10, 2);
		add_filter('wp_nav_menu', array($this, 'add_menu_state_data'));

		// At a priority one less than where custom css is done
		add_action('wp_enqueue_scripts', array($this, 'register_assets'), 98);

		// Disable admin bar - too much CSS
		add_filter('show_admin_bar', '__return_false', 101);

		// No sidebar needed in AMP
		add_action('template_redirect', function() {
			Bunyad::core()->set_sidebar('none');
		});

		// Whether to filter a selector not. Callback for hook in class-amp-style-sanitizer.php
		// @deprecated: add_filter('amp_selector_should_include', array($this, 'selector_should_include'), 10, 3);

		// Remove empty queries
		add_filter('amp_stylesheet_part', array($this, 'remove_empty_queries'));

		// Create map of min classes
		$this->create_class_map();

		// Remove Visual Composer noscript part as it creates a problem with libxml < 2.8
		if (function_exists('visual_composer') && did_action('vc_after_init_base')) {
			remove_action('wp_head', array(visual_composer(), 'addNoScript'), 1000);
		}
	}

	/**
	 * Checks if currently viewing via AMP
	 * 
	 * Note: Valid only after parse_query (before 'wp' but after 'init') action.
	 */
	public function active()
	{
		if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
			return true;
		}

		return false;
	}

	/**
	 * Fix unnecessary validation errros in the paired mode
	 */
	public function fix_validation()
	{
		if (!class_exists('AMP_Validation_Manager')) {
			return;
		}

		remove_action('edit_form_top', array('AMP_Validation_Manager', 'print_edit_form_validation_status'), 10, 2);
		remove_action('all_admin_notices', array('AMP_Validation_Manager', 'print_plugin_notice'));

		// Gutenberg validation
		remove_action('rest_api_init', array('AMP_Validation_Manager', 'add_rest_api_fields'));

		if (class_exists('AMP_Validated_URL_Post_Type')) {
			remove_action('dashboard_glance_items', array('AMP_Validated_URL_Post_Type', 'filter_dashboard_glance_items'));
		}
	}

	/**
	 * Hide validation unless needed
	 */
	public function validation_menu()
	{
		global $submenu;

		if (empty($submenu['amp-options'])) {
			return;
		}

		$validated = $submenu['amp-options'][2];
		$index     = $submenu['amp-options'][3];

		$show_validation = isset($_GET['amp_debug']);

		// Remove validation urls unless in debug and add hidden entries instead.
		if (!$show_validation) {

			if ($validated) {
				unset($submenu['amp-options'][2]);
				$submenu[] = $validated;
			}

			// Remove errors
			if ($index) {
				unset($submenu['amp-options'][3]);
				$submenu[] = $index;
			}
		}

	}

	/**
	 * Get a class name from the min map
	 * 
	 * @param array|string $class
	 * @return mixed
	 */
	public function get_min_class($class)
	{
		if (is_array($class)) {
			return array_map(array($this, __METHOD__), $class);
		}

		if (isset($this->min_map[$class])) {
			return $this->min_map[$class];
		}

		return $class;
	}

	/**
	 * Register assets
	 */
	public function register_assets()
	{
		wp_deregister_style('contentberg-core');
		wp_deregister_style('contentberg-skin');

		wp_enqueue_style('contentberg-core', get_template_directory_uri() . '/css/min/amp.css', array(), Bunyad::options()->get_config('theme_version'));
		
		// DEV:
		// wp_enqueue_style('contentberg-core', get_template_directory_uri() . '/css/amp.css', array(), Bunyad::options()->get_config('theme_version'));

		// Dash icons are excessive
		wp_dequeue_style('dashicons');

		if (!is_page()) {
			wp_dequeue_style('contact-form-7');
		}

		// AMP expects fontawesome via CDN
		wp_dequeue_style('font-awesome');
		wp_enqueue_style('font-awesome-cdn', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), null);

		// Add Typekit Kit
		if (Bunyad::options()->typekit_id) {
			wp_enqueue_style('contentberg-typekit', 'https://use.typekit.net/' . Bunyad::options()->typekit_id . '.css', array(), null);
		}
	}

	/**
	 * Add our custom theme sanitizer
	 */
	public function add_sanitizer($sanitizers)
	{
		require_once get_template_directory() . '/inc/amp/sanitize-classes.php';
		$sanitizers['Bunyad_Theme_Amp_SanitizeClasses'] = array();

		require_once get_template_directory() . '/inc/amp/sanitizer.php';
		$sanitizers['Bunyad_Theme_Amp_Sanitizer'] = array();

		return $sanitizers;
	}

	/**
	 * Store menu state in a local var
	 */
	public function store_menu_state($items, $args) {
			
		foreach ($items as $item) {
			if (in_array('menu-item-has-children', $item->classes)) {
				$this->menu_state['item' . $item->ID] = false;
			}
		}

		return $items;
	}

	/**
	 * Output menu state for AMP
	 */
	public function add_menu_state_data($output)
	{
		if (!empty($this->menu_state)) {
			$output .= sprintf(
				'<amp-state id="%s"><script type="application/json">%s</script></amp-state>',
				esc_attr('mobileNav'),
				wp_json_encode($this->menu_state)
			);
		}

		return $output;
	}

	/**
	 * Create a map of minified classes
	 */
	public function create_class_map()
	{
		$map = json_decode(
			file_get_contents(get_template_directory() . '/inc/amp/map.json'),
			true
		);

		// Remove . char
		foreach ($map as $key => $value) {
			$map[ str_replace('.', '', $key) ] = $value;
			unset($map[$key]);
		}

		// Set the map 
		$this->min_map = $map;

		// Disable: $this->min_map = array();

		return $map;
	}

	/**
	 * Match joined classes in a selector against provided classes
	 */
	protected function _multi_classes_match($class, $selector, $classes = array())
	{
		$class = $this->get_min_class($class);

		if (preg_match('/\.' . preg_quote($class) . '\.([a-zA-Z0-9_\-\.]+)/', $selector, $match)) {
			$sel_classes = explode('.', $match[1]);

			// Not all classes present?
			if (array_diff($sel_classes, $classes)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Callback for hook in class-amp-style-sanitizer.php
	 * 
	 * @deprecated  When min classes are used, this is usually not needed unless a conflict occurs.
	 */
	public function selector_should_include($should_include, $selector, $parsed) 
	{
		// This is getting removed already - no further checks needed
		if (!$should_include) {
			return false;
		}

		// Check if joined classes present in the selector actually match the ones present in DOM
		if (
			!$this->_multi_classes_match('main-head', $selector, $this->layout_classes['header'])
			OR
			!$this->_multi_classes_match('main-footer', $selector, $this->layout_classes['footer'])
		) {

			return false;
		}			

		return $should_include;
	}

	/**
	 * Remove empty queries
	 */
	public function remove_empty_queries($css)
	{
		$css = preg_replace('/@(supports|media)[^{]+?{\s*}/', '', $css);
		return $css;
	}
}

// init and make available in Bunyad::get('amp')
Bunyad::register('amp', array(
	'class' => 'Bunyad_Theme_Amp',
	'init' => true
));