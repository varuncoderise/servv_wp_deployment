<?php

require_once get_template_directory() . '/plugins/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'thegem_register_required_plugins' );
function thegem_register_required_plugins() {
	$plugins = array(
		array(
			'name' => esc_html__('TheGem Theme Elements', 'thegem'),
			'slug' => 'thegem-elements',
			'source' => esc_url('http://democontent.codex-themes.com/plugins/thegem/required/thegem-elements.zip'),
			'required' => true,
			'version' => '',
			'force_activation' => false,
			'force_deactivation' => false,
			'external_url' => '',
		),
		array(
			'name' => esc_html__('TheGem Blocks', 'thegem'),
			'slug' => 'thegem-blocks',
			'source' => esc_url('http://democontent.codex-themes.com/plugins/thegem/recommended/thegem-blocks.zip'),
			'required' => false,
			'version' => '',
			'force_activation' => false,
			'force_deactivation' => false,
			'external_url' => '',
		),
		array(
			'name' => esc_html__('TheGem Demo Import', 'thegem'),
			'slug' => 'thegem-importer',
			'source' => esc_url('http://democontent.codex-themes.com/plugins/thegem/recommended/thegem-importer.zip'),
			'required' => false,
			'version' => '',
			'force_activation' => false,
			'force_deactivation' => false,
			'external_url' => '',
		),
		array(
			'name' => esc_html__('LayerSlider WP', 'thegem'),
			'slug' => 'LayerSlider',
			'source' => esc_url('http://democontent.codex-themes.com/plugins/thegem/recommended/layersliderwp.installable.zip'),
			'required' => false,
			'version' => '',
			'force_activation' => false,
			'force_deactivation' => false,
			'external_url' => '',
		),
		array(
			'name' => esc_html__('Revolution Slider', 'thegem'),
			'slug' => 'revslider',
			'source' => esc_url('http://democontent.codex-themes.com/plugins/thegem/recommended/revslider.zip'),
			'required' => false,
			'version' => '',
			'force_activation' => false,
			'force_deactivation' => false,
			'external_url' => '',
		),
		array(
			'name' => esc_html__('Wordpress Page Widgets', 'thegem'),
			'slug' => 'wp-page-widget',
			'source' => esc_url('http://democontent.codex-themes.com/plugins/thegem/recommended/wp-page-widget.zip'),
			'required' => false,
			'version' => '',
			'force_activation' => false,
			'force_deactivation' => false,
			'external_url' => '',
		),
		array(
			'name' => esc_html__('WPBakery Visual Composer', 'thegem'),
			'slug' => 'js_composer',
			'source' => esc_url('http://democontent.codex-themes.com/plugins/thegem/required/js_composer.zip'),
			'required' => true,
			'version' => '',
			'force_activation' => false,
			'force_deactivation' => false,
			'external_url' => '',
		),
		array(
			'name' => esc_html__('Contact Form 7', 'thegem'),
			'slug' => 'contact-form-7',
			'required' => false,
		),
		array(
			'name' => esc_html__('MailChimp for WordPress', 'thegem'),
			'slug' => 'mailchimp-for-wp',
			'required' => false,
		),
		array(
			'name' => esc_html__('Easy Forms for MailChimp by YIKES', 'thegem'),
			'slug' => 'yikes-inc-easy-mailchimp-extender',
			'source' => esc_url('http://democontent.codex-themes.com/plugins/thegem/recommended/yikes-inc-easy-mailchimp-extender.zip'),
			'required' => false,
			'version' => '',
			'force_activation' => false,
			'force_deactivation' => false,
			'external_url' => '',
		),
		array(
			'name' => esc_html__('ZillaLikes', 'thegem'),
			'slug' => 'zilla-likes',
			'source' => esc_url('http://democontent.codex-themes.com/plugins/thegem/recommended/zilla-likes.zip'),
			'required' => false,
			'version' => '1.1.1',
			'force_activation' => false,
			'force_deactivation' => false,
			'external_url' => '',
		),
	);

	if(thegem_is_plugin_active('woocommerce/woocommerce.php')) {
		$plugins[] = array(
			'name' => esc_html__('YITH WooCommerce Wishlist', 'thegem'),
			'slug' => 'yith-woocommerce-wishlist',
			'required' => false,
		);
	}

	$config = array(
		'domain' => 'thegem',
		'default_path' => '',
		'parent_slug' => 'admin.php',
		'menu' => 'install-required-plugins',
		'has_notices' => true,
		'is_automatic' => true,
		'message' => '',
		'strings' => array(
			'page_title' => esc_html__( 'Install Plugins', 'thegem' ),
			'menu_title' => esc_html__( 'Install Plugins', 'thegem' ),
			'installing' => esc_html__( 'Installing Plugin: %s', 'thegem' ),
			'oops' => esc_html__( 'Something went wrong with the plugin API.', 'thegem' ),
			'notice_can_install_required' => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'thegem' ),
			'notice_can_install_recommended' => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'thegem' ),
			'notice_cannot_install' => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'thegem' ),
			'notice_can_activate_required' => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'thegem' ),
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'thegem' ),
			'notice_cannot_activate' => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'thegem' ),
			'notice_ask_to_update' => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'thegem' ),
			'notice_cannot_update' => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'thegem' ),
			'install_link' => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'thegem' ),
			'activate_link' => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'thegem' ),
			'return' => esc_html__( 'Return to Required Plugins Installer', 'thegem' ),
			'plugin_activated' => esc_html__( 'Plugin activated successfully.', 'thegem' ),
			'complete' => esc_html__( 'All plugins installed and activated successfully. %s', 'thegem' ),
			'nag_type' => 'updated'
		)
	);

	tgmpa( $plugins, $config );

}

add_action( 'admin_init', 'thegem_updater_plugin_load' );
function thegem_updater_plugin_load() {
	if ( ! class_exists( 'TGM_Updater' ) ) {
		require get_template_directory() . '/plugins/class-tgm-updater.php';
	}
	if(thegem_is_plugin_active('thegem-elements/thegem-elements.php')) {
		$plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).'thegem-elements/thegem-elements.php');
		$args = array(
			'plugin_name' => esc_html__('TheGem Theme Elements', 'thegem'),
			'plugin_slug' => 'thegem-elements',
			'plugin_path' => 'thegem-elements/thegem-elements.php',
			'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'thegem-elements',
			'remote_url'  => esc_url('http://democontent.codex-themes.com/plugins/thegem/required/thegem-elements.json'),
			'version'     => $plugin_data['Version'],
			'key'         => ''
		);
		$tgm_updater = new TGM_Updater( $args );
	}
	if(thegem_is_plugin_active('thegem-blocks/thegem-blocks.php')) {
		$plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).'thegem-blocks/thegem-blocks.php');
		$args = array(
			'plugin_name' => esc_html__('TheGem Blocks', 'thegem'),
			'plugin_slug' => 'thegem-blocks',
			'plugin_path' => 'thegem-blocks/thegem-blocks.php',
			'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'thegem-blocks',
			'remote_url'  => esc_url('http://democontent.codex-themes.com/plugins/thegem/recommended/thegem-blocks.json'),
			'version'     => $plugin_data['Version'],
			'key'         => ''
		);
		$tgm_updater = new TGM_Updater( $args );
	}
	if(thegem_is_plugin_active('thegem-import/thegem-import.php')) {
		$plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).'thegem-import/thegem-import.php');
		$args = array(
			'plugin_name' => esc_html__('TheGem Import', 'thegem'),
			'plugin_slug' => 'thegem-import',
			'plugin_path' => 'thegem-import/thegem-import.php',
			'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'thegem-import',
			'remote_url'  => esc_url('http://democontent.codex-themes.com/plugins/thegem/recommended/thegem-import.json'),
			'version'     => $plugin_data['Version'],
			'key'         => ''
		);
		$tgm_updater = new TGM_Updater( $args );
	}
	if(thegem_is_plugin_active('thegem-importer/thegem-importer.php')) {
		$plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).'thegem-importer/thegem-importer.php');
		$args = array(
			'plugin_name' => esc_html__('TheGem Demo Import', 'thegem'),
			'plugin_slug' => 'thegem-importer',
			'plugin_path' => 'thegem-importer/thegem-importer.php',
			'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'thegem-importer',
			'remote_url'  => esc_url('http://democontent.codex-themes.com/plugins/thegem/recommended/thegem-importer.json'),
			'version'     => $plugin_data['Version'],
			'key'         => ''
		);
		$tgm_updater = new TGM_Updater( $args );
	}
	if(thegem_is_plugin_active('LayerSlider/layerslider.php')) {
		$plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).'LayerSlider/layerslider.php');
		$args = array(
			'plugin_name' => esc_html__('LayerSlider WP', 'thegem'),
			'plugin_slug' => 'LayerSlider',
			'plugin_path' => 'LayerSlider/layerslider.php',
			'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'LayerSlider',
			'remote_url'  => esc_url('http://democontent.codex-themes.com/plugins/thegem/recommended/layerslider.json'),
			'version'     => $plugin_data['Version'],
			'key'         => ''
		);
		$tgm_updater = new TGM_Updater( $args );
	}
	if(thegem_is_plugin_active('revslider/revslider.php')) {
		$plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).'revslider/revslider.php');
		$args = array(
			'plugin_name' => esc_html__('Revolution Slider', 'thegem'),
			'plugin_slug' => 'revslider',
			'plugin_path' => 'revslider/revslider.php',
			'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'revslider',
			'remote_url'  => esc_url('http://democontent.codex-themes.com/plugins/thegem/recommended/revslider.json'),
			'version'     => $plugin_data['Version'],
			'key'         => ''
		);
		$tgm_updater = new TGM_Updater( $args );
	}
	if(thegem_is_plugin_active('js_composer/js_composer.php')) {
		$plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).'js_composer/js_composer.php');
		$args = array(
			'plugin_name' => esc_html__('WPBakery Visual Composer', 'thegem'),
			'plugin_slug' => 'js_composer',
			'plugin_path' => 'js_composer/js_composer.php',
			'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'js_composer',
			'remote_url'  => esc_url('http://democontent.codex-themes.com/plugins/thegem/required/js_composer.json'),
			'version'     => $plugin_data['Version'],
			'key'         => ''
		);
		$tgm_updater = new TGM_Updater( $args );
	}
	if(thegem_is_plugin_active('zilla-likes/zilla-likes.php')) {
		$plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).'zilla-likes/zilla-likes.php');
		$args = array(
			'plugin_name' => esc_html__('ZillaLikes', 'thegem'),
			'plugin_slug' => 'zilla-likes',
			'plugin_path' => 'zilla-likes/zilla-likes.php',
			'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'zilla-likes',
			'remote_url'  => esc_url('http://democontent.codex-themes.com/plugins/thegem/recommended/zilla-likes.json'),
			'version'     => $plugin_data['Version'],
			'key'         => ''
		);
		$tgm_updater = new TGM_Updater( $args );
	}
    /*
	if(thegem_is_plugin_active('wp-rocket/wp-rocket.php')) {
		$plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).'wp-rocket/wp-rocket.php');
		$args = array(
			'plugin_name' => esc_html__('WP Rocket', 'thegem'),
			'plugin_slug' => 'wp-rocket',
			'plugin_path' => 'wp-rocket/wp-rocket.php',
			'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'wp-rocket',
			'remote_url'  => esc_url('http://democontent.codex-themes.com/plugins/thegem/recommended/wp-rocket.json'),
			'version'     => $plugin_data['Version'],
			'key'         => ''
		);
		$tgm_updater = new TGM_Updater( $args );
	}
    */
}

function thegem_get_purchase() {
	if(!defined('ENVATO_HOSTED_SITE')) {
		$theme_options = get_option('thegem_theme_options');
		if($theme_options && isset($theme_options['purchase_code'])) {
			return $theme_options['purchase_code'];
		}
	} else {
		return 'envato_hosted:'.(defined('SUBSCRIPTION_CODE') ? SUBSCRIPTION_CODE : '');
	}
	return false;
}

add_action( 'vc_before_init', 'thegem_vcSetAsTheme' );
function thegem_vcSetAsTheme() {
	//vc_set_as_theme();
}

if(function_exists('layerslider_set_as_theme')) layerslider_set_as_theme();

function thegem_upgrader_pre_download($reply, $package, $upgrader) {
	if(strpos($package, 'democontent.codex-themes.com') !== false && strpos($package, 'envato-wordpress-toolkit') === false) {
		if(!thegem_get_purchase()) {
			if(!defined('ENVATO_HOSTED_SITE')) {
				return new WP_Error('thegem_purchase_empty', sprintf(wp_kses(__('Purchase code verification failed. <a href="%s" target="_blank">Activate TheGem</a>', 'thegem'), array('a' => array('href' => array(), 'target' => array()))),esc_url(admin_url('admin.php?page=thegem-dashboard-welcome'))));
			}
		}
		$response_p = wp_remote_get(add_query_arg(array('code' => thegem_get_purchase(), 'info'=>thegem_get_activation_info(), 'site_url' => get_site_url()), 'http://democontent.codex-themes.com/av_validate_code'.(defined('ENVATO_HOSTED_SITE') ? '_envato' : '').'.php'), array('timeout' => 20));
		if(is_wp_error($response_p)) {
			return new WP_Error('thegem_connection_failed', esc_html__('Some troubles with connecting to TheGem server.', 'thegem'));
		}
		$rp_data = json_decode($response_p['body'], true);
		if(!(is_array($rp_data) && isset($rp_data['result']) && $rp_data['result'] && isset($rp_data['item_id']) && $rp_data['item_id'] === '16061685')) {
			if(!defined('ENVATO_HOSTED_SITE')) {
				return new WP_Error('thegem_purchase_error', sprintf(wp_kses(__('Purchase code verification failed. <a href="%s" target="_blank">Activate TheGem</a>', 'thegem'), array('a' => array('href' => array(), 'target' => array()))), esc_url(admin_url('admin.php?page=thegem-dashboard-welcome'))));
			}
		}
	}
	return $reply;
}
add_filter('upgrader_pre_download', 'thegem_upgrader_pre_download', 10, 3);

function thegem_pre_set_site_transient_update_themes( $transient ) {

	$response = wp_remote_get('http://democontent.codex-themes.com/plugins/thegem/theme/theme.json', array('timeout' => 5));
	if ( is_wp_error( $response ) ) {
		return $transient;
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, 1);
	if ( ! isset( $data['new_version'] ) ) {
		return $transient;
	}

	$new_version = $data['new_version'];

	// Save update info if there are newer version.
	$theme = wp_get_theme('thegem');
	if ( version_compare( $theme->get( 'Version' ), $new_version, '<' ) ) {
		$transient->response[ 'thegem' ] = array(
			'theme' => 'thegem',
			'new_version' => $new_version,
			'url' => $data['changelog'],
			'package' => $data['package'],
		);
	} else {
		$transient->no_update[ 'thegem' ] = array(
			'theme' => 'thegem',
			'new_version' => $new_version,
			'url' => $data['changelog'],
			'package' => $data['package'],
		);
	}

	return $transient;
}
add_filter('pre_set_site_transient_update_themes', 'thegem_pre_set_site_transient_update_themes', 10, 3);

function thegem_pre_set_site_transient_update_plugins( $transient ) {

	$response = wp_remote_get('http://democontent.codex-themes.com/plugins/thegem/recommended/autoptimize.json', array('timeout' => 5));

	if ( is_wp_error( $response ) ) {
		return $transient;
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, 1);
	if ( ! isset( $data['new_version'] ) ) {
		return $transient;
	}
	$new_version = $data['new_version'];

	$autoptimize_data = new stdClass;

	if(isset($transient->response['autoptimize/autoptimize.php'])) {
		$autoptimize_data = $transient->response['autoptimize/autoptimize.php'];
		unset($transient->response['autoptimize/autoptimize.php']);
	} elseif(isset($transient->no_update['autoptimize/autoptimize.php'])) {
		$autoptimize_data = $transient->no_update['autoptimize/autoptimize.php'];
		unset($transient->no_update['autoptimize/autoptimize.php']);
	}

	if(defined( 'AUTOPTIMIZE_PLUGIN_VERSION' ) && isset($autoptimize_data->new_version)) {
		if ( version_compare( AUTOPTIMIZE_PLUGIN_VERSION, $new_version, '<' ) ) {
			$autoptimize_data->new_version = $new_version;
			$autoptimize_data->package = $data['package'];
			$transient->response['autoptimize/autoptimize.php'] = $autoptimize_data;
		} else {
			$autoptimize_data->new_version = $new_version;
			$autoptimize_data->package = $data['package'];
			$transient->no_update['autoptimize/autoptimize.php'] = $autoptimize_data;
		}
	}

	$response = wp_remote_get('http://democontent.codex-themes.com/plugins/thegem/recommended/wp-super-cache.json', array('timeout' => 5));

	if ( is_wp_error( $response ) ) {
		return $transient;
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, 1);
	if ( ! isset( $data['new_version'] ) ) {
		return $transient;
	}
	$new_version = $data['new_version'];

	$wp_super_cache_data = new stdClass;

	if(isset($transient->response['wp-super-cache/wp-cache.php'])) {
		$wp_super_cache_data = $transient->response['wp-super-cache/wp-cache.php'];
		unset($transient->response['wp-super-cache/wp-cache.php']);
	} elseif(isset($transient->no_update['wp-super-cache/wp-cache.php'])) {
		$wp_super_cache_data = $transient->no_update['wp-super-cache/wp-cache.php'];
		unset($transient->no_update['wp-super-cache/wp-cache.php']);
	}

	if(thegem_is_plugin_active('wp-super-cache/wp-cache.php') && isset($wp_super_cache_data->new_version)) {
		$plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).'wp-super-cache/wp-cache.php');
		if ( version_compare( $plugin_data['Version'], $new_version, '<' ) ) {
			$wp_super_cache_data->new_version = $new_version;
			$wp_super_cache_data->package = $data['package'];
			$transient->response['wp-super-cache/wp-cache.php'] = $wp_super_cache_data;
		} else {
			$wp_super_cache_data->new_version = $new_version;
			$wp_super_cache_data->package = $data['package'];
			$transient->no_update['wp-super-cache/wp-cache.php'] = $wp_super_cache_data;
		}
	}

	$response = wp_remote_get('http://democontent.codex-themes.com/plugins/thegem/recommended/yikes-inc-easy-mailchimp-extender.json', array('timeout' => 5));

	if ( is_wp_error( $response ) ) {
		return $transient;
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, 1);
	if ( ! isset( $data['new_version'] ) ) {
		return $transient;
	}
	$new_version = $data['new_version'];

	$yikes_easy_mailchimp_extender_data = new stdClass;

	if(isset($transient->response['yikes-inc-easy-mailchimp-extender/yikes-inc-easy-mailchimp-extender.php'])) {
		$yikes_easy_mailchimp_extender_data = $transient->response['yikes-inc-easy-mailchimp-extender/yikes-inc-easy-mailchimp-extender.php'];
		unset($transient->response['yikes-inc-easy-mailchimp-extender/yikes-inc-easy-mailchimp-extender.php']);
	} elseif(isset($transient->no_update['yikes-inc-easy-mailchimp-extender/yikes-inc-easy-mailchimp-extender.php'])) {
		$yikes_easy_mailchimp_extender_data = $transient->no_update['yikes-inc-easy-mailchimp-extender/yikes-inc-easy-mailchimp-extender.php'];
		unset($transient->no_update['yikes-inc-easy-mailchimp-extender/yikes-inc-easy-mailchimp-extender.php']);
	}

	if(defined( 'YIKES_MC_VERSION' ) && isset($yikes_easy_mailchimp_extender_data->new_version)) {
		if ( version_compare( YIKES_MC_VERSION, $new_version, '<' ) ) {
			$yikes_easy_mailchimp_extender_data->new_version = $new_version;
			$yikes_easy_mailchimp_extender_data->package = $data['package'];
			$transient->response['yikes-inc-easy-mailchimp-extender/yikes-inc-easy-mailchimp-extender.php'] = $yikes_easy_mailchimp_extender_data;
		} else {
			$yikes_easy_mailchimp_extender_data->new_version = $new_version;
			$yikes_easy_mailchimp_extender_data->package = $data['package'];
			$transient->no_update['yikes-inc-easy-mailchimp-extender/yikes-inc-easy-mailchimp-extender.php'] = $yikes_easy_mailchimp_extender_data;
		}
	}

	$response = wp_remote_get('http://democontent.codex-themes.com/plugins/thegem/required/js_composer.json', array('timeout' => 5));

	if ( is_wp_error( $response ) ) {
		return $transient;
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, 1);
	if ( ! isset( $data['new_version'] ) ) {
		return $transient;
	}
	$new_version = $data['new_version'];

	$js_composer_data = new stdClass;

	if(isset($transient->response['js_composer/js_composer.php'])) {
		$js_composer_data = $transient->response['js_composer/js_composer.php'];
		unset($transient->response['js_composer/js_composer.php']);
	} elseif(isset($transient->no_update['js_composer/js_composer.php'])) {
		$js_composer_data = $transient->no_update['js_composer/js_composer.php'];
		unset($transient->no_update['js_composer/js_composer.php']);
	}

	if(defined( 'WPB_VC_VERSION' ) && isset($js_composer_data->new_version)) {
		if ( version_compare( WPB_VC_VERSION, $new_version, '<' ) ) {
			$js_composer_data->new_version = $new_version;
			$js_composer_data->package = $data['package'];
			$transient->response['js_composer/js_composer.php'] = $js_composer_data;
		} else {
			$js_composer_data->new_version = $new_version;
			$js_composer_data->package = $data['package'];
			$transient->no_update['js_composer/js_composer.php'] = $js_composer_data;
		}
	}

	return $transient;
}
add_filter('pre_set_site_transient_update_plugins', 'thegem_pre_set_site_transient_update_plugins', 15, 3);

add_action('wp_ajax_thegem_theme_update_confirm', 'thegem_theme_update_confirm_content');
function thegem_theme_update_confirm_content() {
?>
<div class="fancybox-content thegem-theme-update-fancybox-content">
	<div class="thegem-theme-update-confirm-content">
		<div class="ttucc-title"><img src="<?php echo THEGEM_THEME_URI; ?>/images/admin-images/ttucc-title.png" alt="#" /></div>
		<div class="ttucc-description"><?php esc_html_e('Before updating, it would be better if you make a backup of your current theme files (via FTP). Also please note: if you have done any code modifications directly in parent’s theme source files, this changes may be overwritten. We recommend to use TheGem child theme for any code modifications and customizations in order to ensure all further updates without any issues.', 'thegem'); ?></div>
		<div class="ttucc-confirm">
			<div class="ttucc-confirm-checkbox">
				<label for="thegem-update-confirm-checkbox"><input type="checkbox" name="confirm" id="thegem-update-confirm-checkbox" value="1" /><?php esc_html_e('I have read this notice and agree to proceed', 'thegem'); ?></label>
			</div>
			<div class="ttucc-confirm-button">
				<button id="thegem-update-confirm-button" disabled="disabled"><?php esc_html_e('Proceed with update', 'thegem'); ?></button>
			</div>
		</div>
	</div>
</div>
<?php
	die(-1);
}

function thegem_update_notice() {
	if ( !current_user_can('update_themes' ) )
		return false;
	if ( !isset($themes_update) )
		$themes_update = get_site_transient('update_themes');
	if ( isset($themes_update->response['thegem']) ) {
		$update = $themes_update->response['thegem'];
		$theme = wp_prepare_themes_for_js( array( wp_get_theme('thegem') ) );
		$details_url = add_query_arg(array(), $update['url']);
		$update_url = wp_nonce_url( admin_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( 'thegem' ) ), 'upgrade-theme_thegem' );
		if(isset($theme[0]) && isset($theme[0]['hasUpdate']) && $theme[0]['hasUpdate']) {
			wp_enqueue_script('jquery-fancybox');
			wp_enqueue_style('jquery-fancybox');
			echo '<div class="thegem-update-notice notice notice-warning is-dismissible">';
			echo '<p>'.sprintf(wp_kses(__('There is a new version of TheGem theme available. Your current version is <strong>%s</strong>. Update to <strong>%s</strong>.', 'thegem'), array('strong' => array())), $theme[0]['version'], $update['new_version']).'</p>';
			echo '<p>'.sprintf(wp_kses(__('<strong><a href="%s" class="thegem-view-details-link">View update details</a></strong> or <strong><a href="%s" class="thegem-update-link">Update now</a></strong>.', 'thegem'), array('strong' => array(), 'a' => array('href' => array(), 'class' => array()))), $details_url, $update_url).'</p>';
			echo '</div>';
		}
	}
}
add_action('admin_notices', 'thegem_update_notice');

function thegem_plugins_update_notice() {
	if ( !current_user_can('update_plugins' ) )
		return false;
	$plugins = get_site_transient('update_plugins');
	$thegem_plugins = array(
		'thegem-elements/thegem-elements.php',
		'thegem-blocks/thegem-blocks.php',
		'thegem-import/thegem-import.php',
		'thegem-importer/thegem-importer.php',
		'LayerSlider/layerslider.php',
		'revslider/revslider.php',
		'js_composer/js_composer.php',
		//'wp-rocket/wp-rocket.php',
	);
	if ( isset($plugins->response) && is_array($plugins->response) ) {
		wp_enqueue_script('jquery-fancybox');
		wp_enqueue_style('jquery-fancybox');
		$plugins_ids = array_keys( $plugins->response );
		foreach ( $plugins_ids as $plugin_file ) {
			if(in_array($plugin_file, $thegem_plugins)) {
				$plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).$plugin_file);
				$plugin_update = $plugins->response[$plugin_file];
				echo '<div class="thegem-update-notice notice notice-warning is-dismissible">';
				echo '<p>'.sprintf(wp_kses(__('There is a new version of <strong>%s</strong> plugin available. Your current version is <strong>%s</strong>. Update to <strong>%s</strong>.', 'thegem'), array('strong' => array())), $plugin_data['Name'], $plugin_data['Version'], $plugin_update->new_version).'</p>';
				echo '<p>'.sprintf(wp_kses(__('<strong><a href="%s">Update now</a></strong>.', 'thegem'), array('strong' => array(), 'a' => array('href' => array()))), esc_url(admin_url('update-core.php'))).'</p>';
				echo '</div>';
			}
		}
	}
}
add_action('admin_notices', 'thegem_plugins_update_notice');

function thegem_plugins_update_latest_version_notice() {

	if(thegem_is_plugin_active('thegem-elements/thegem-elements.php')) {
		$plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).'thegem-elements/thegem-elements.php');
		if(version_compare($plugin_data['Version'], '5.9.3', '<')) {
			echo '<div class="thegem-update-notice-new notice notice-error" style="display: flex; align-items: center;">';
			echo '<p style="margin: 5px 15px 0 10px;"><img src=" '.THEGEM_THEME_URI . '/images/alert-icon.svg'.' " width="40px" alt="thegem-blocks-logo"></p>';
			echo '<p><b style="display: block; font-size: 14px; padding-bottom: 5px">'.__('IMPORTANT:', 'thegem').'</b>'.__('Please update <strong>«TheGem Theme Elements»</strong> plugin to the latest version.', 'thegem').'</p>';
			echo '<p style="margin-left: auto;">'.sprintf(wp_kses(__('<a href="%s" class="button button-primary">Update now</a>', 'thegem'), array('strong' => array(), 'a' => array('href' => array(), 'class' => array()))), esc_url(admin_url('update-core.php'))).'</p>';
			echo '</div>';
		}
	}

	if(thegem_is_plugin_active('thegem-importer/thegem-importer.php')) {
		$plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).'thegem-importer/thegem-importer.php');
		if(version_compare($plugin_data['Version'], '5.9.3', '<')) {
			echo '<div class="thegem-update-notice-new notice notice-error" style="display: flex; align-items: center;">';
			echo '<p style="margin: 5px 15px 0 10px;"><img src=" '.THEGEM_THEME_URI . '/images/alert-icon.svg'.' " width="40px" alt="thegem-blocks-logo"></p>';
			echo '<p><b style="display: block; font-size: 14px; padding-bottom: 5px">'.__('IMPORTANT:', 'thegem').'</b>'.__('Please update <strong>«TheGem Demo Import»</strong> plugin to the latest version.', 'thegem').'</p>';
			echo '<p style="margin-left: auto;">'.sprintf(wp_kses(__('<a href="%s" class="button button-primary">Update now</a>', 'thegem'), array('strong' => array(), 'a' => array('href' => array(), 'class' => array()))), esc_url(admin_url('update-core.php'))).'</p>';
			echo '</div>';
		}
	}
}
add_action('admin_notices', 'thegem_plugins_update_latest_version_notice');

function thegem_tgmpa_admin_menu_args($args) {
	$args['parent_slug'] = 'thegem-dashboard-welcome';
	$args['position'] = 40;
	return $args;
}
add_filter('tgmpa_admin_menu_args', 'thegem_tgmpa_admin_menu_args');

function thegem_elementor_available_notice() {
	if (!isset($_COOKIE['thegem_elementor_available_notice'])) {
		?>

		<div id="thegem-elementor-notice" class="notice notice-success is-dismissible">
			<div class="thegem-elementor-notice-inner" style="display: flex; align-items: center; width: 100%;">
				<div class="thegem-elementor-notice-logo">
					<img src="<?= THEGEM_THEME_URI . '/images/elementor-logo.svg' ?>" width="40px" alt="thegem-blocks-logo">
				</div>

				<div class="thegem-elementor-notice-info">
					<p><b><?= __('TheGem for Elementor released!', 'thegem')?></b></p>
					<p><?= __('100% compatibility with Elementor and Elementor Pro. With all 400+ pre-built demos. Including 30+ unique TheGem elements (extended, updated, reworked). With powerful Elementor extensions. Full control of responsiveness. Unlimited customization options.', 'thegem') ?></p>
					<p><a href="//democontent.codex-themes.com/plugins/thegem/theme/elementor_update.html" class="thegem-elementor-notice-link"><b><?= __('Learn more...', 'thegem') ?></b></a> | <a href="#" class="thegem-notice-dismiss"><b><?= __('Dismiss this notice', 'thegem') ?></b></a></p>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			window.addEventListener('load', function() {
				(function ($) {
					$('#thegem-elementor-notice .thegem-elementor-notice-link').fancybox({
						type: 'iframe',
						toolbar: false,
						smallBtn : true
					});
					$('#thegem-elementor-notice').on('click', '.notice-dismiss, .thegem-notice-dismiss', function() {
						let dt = new Date();
						let days = 30;
						dt.setDate(dt.getDate() + days);

						let name = '<?= esc_attr('thegem_elementor_available_notice'); ?>';
						let value = encodeURIComponent('<?= esc_attr('1.0.0'); ?>') + ("; expires=" + dt.toUTCString());
						document.cookie = name + "=" + value;
						$('#thegem-elementor-notice').remove();
					});
				})(window.jQuery);
			});
		</script>

	<?php }
}
//add_action('admin_notices', 'thegem_elementor_available_notice');


function thegem_downgrade_admin_menu() {
	add_submenu_page(null, esc_html__('Downgrade TheGem','thegem'), esc_html__('Downgrade TheGem','thegem'), 'edit_theme_options', 'thegem-downgrade', 'thegem_downgrade', 0);
}

add_action('admin_menu', 'thegem_downgrade_admin_menu', 70);

function thegem_downgrade() {
	echo '<style>.thegem-downgrade-panel .wrap+.wrap form{display:none}.thegem-downgrade-panel .wrap:first-child a,.thegem-downgrade-panel .wrap:first-child iframe{display:none}</style>';
	echo '<div id="thegem-downgrade-overlay" style="position: fixed;display:flex;align-items:center;justify-content:center;width: 100%;height: 100%;top: 0;left: 0;right: 0;bottom: 0;font-size:24px;color:#ffffff;background-color: rgba(51,58,66,.9);z-index: 9999;"><span>'.esc_html__( 'Processing the installation of previous versions, please wait...', 'thegem' ).'</span></div>';
	echo '<div class="thegem-downgrade-panel">';
	$plugins_updates = get_site_transient('update_plugins');
	$plugin_info = new stdClass();
	$plugin_info->new_version = '';
	$plugin_info->slug = 'thegem-elements';
	$plugin_info->package = 'http://democontent.codex-themes.com/plugins/thegem/required/old/thegem-elements-5.7.2.zip';
	$plugin_info->url = '';
	$plugins_updates->response['thegem-elements/thegem-elements.php'] = $plugin_info;

	remove_all_filters( 'pre_set_site_transient_update_plugins' );
	remove_filter('nonce_life', 'thegem_nonce_life');
	set_site_transient('update_plugins', $plugins_updates);
	require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
	$plugin = 'thegem-elements/thegem-elements.php';

	$upgrader_args = [
		'url' => 'admin.php?page=thegem-downgrade',
		'plugin' => $plugin,
		'nonce' => 'upgrade-plugin_' . $plugin,
		'title' => esc_html__( 'Downgrade Theme', 'thegem' ),
	];
	$upgrader = new Plugin_Upgrader( new Plugin_Upgrader_Skin( $upgrader_args ) );
	$upgrader->upgrade( $plugin );
	unset($plugins_updates->response['thegem-elements/thegem-elements.php']);
	set_site_transient('update_plugins', $plugins_updates);


	$theme_updates = get_site_transient('update_themes');
	$theme_updates->response['thegem'] = array(
		'package' => 'http://democontent.codex-themes.com/plugins/thegem/theme/old/thegem-5.7.2.zip',
		'url' => '',
		'new_version' => '',
	);
	remove_all_filters('pre_set_site_transient_update_themes');
	set_site_transient('update_themes', $theme_updates);
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	$theme = 'thegem';

	$title = ''; //esc_html__( 'Downgrade Theme', 'thegem' );

	$nonce = 'upgrade-theme_' . $theme;
	$url = 'admin.php?page=thegem-downgrade';
	$upgrader = new Theme_Upgrader( new Theme_Upgrader_Skin( compact( 'title', 'nonce', 'url', 'theme' ) ) );
	$upgrader->upgrade( $theme );
	unset($theme_updates->response['thegem']);
	set_site_transient('update_themes', $theme_updates);

	echo '</div>';

	?>
	<script type="text/javascript">
		window.addEventListener('load', function() {
			document.getElementById('thegem-downgrade-overlay').style.display = 'none';
		});
	</script>
	<?php
}

function thegem_install_optimizers_admin_menu() {
	add_submenu_page(null, esc_html__('Install TheGem Optimizers','thegem'), esc_html__('Install TheGem Optimizers','thegem'), 'edit_theme_options', 'thegem-install-optimizers', 'thegem_install_optimizers', 0);
}


function thegem_optimizers_backup_settings() {
	global $wpdb;

	if (!get_option('thegem_optimizers_backup_settings')) {
		$data = array();

		$data['wprocket'] = get_option('wp_rocket_settings');
		$data['autoptimize'] = [];
	
		$autoptimizeOptions = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'autoptimize_%'" );
		foreach($autoptimizeOptions as $option ) {
			$data['autoptimize'][$option->option_name] = get_option($option->option_name);
		}

		update_option('thegem_optimizers_backup_settings', $data);
	}
}


function thegem_install_optimizers() {
	$optimizer = 'wp-super-cache';
	if(isset($_REQUEST['optimizer'])) {
		$optimizer = $_REQUEST['optimizer'];
	}
	echo '<style>.thegem-downgrade-panel .wrap+.wrap form{display:none}.thegem-downgrade-panel .wrap:first-child a,.thegem-downgrade-panel .wrap:first-child iframe{display:none} .blink_me {animation: blinker 1s linear infinite;} @keyframes blinker {50% {opacity: 0;}}</style>';
	echo '<div id="thegem-downgrade-overlay" style="position: fixed;display:flex;align-items:center;justify-content:center;width: 100%;height: 100%;top: 0;left: 0;right: 0;bottom: 0;font-size:24px;color:#ffffff;background-color: rgba(51,58,66,0.99);z-index: 9999;"><span>'.esc_html__( 'Processing the one click optimization. Please wait', 'thegem' ).'</span><span class="blink_me">...</span></div>';
	echo '<div class="thegem-downgrade-panel">';
	remove_all_filters( 'pre_set_site_transient_update_plugins' );
	remove_filter('nonce_life', 'thegem_nonce_life');
	require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

	$plugins_updates = get_site_transient('update_plugins');

	if($optimizer === 'wp-rocket') {
		$plugin_info = new stdClass();
		$plugin_info->new_version = '';
		$plugin_info->slug = 'wp-rocket';
		$plugin_info->package = 'http://democontent.codex-themes.com/plugins/thegem/recommended/wp-rocket-disable-google-font-optimization.zip';
		$plugin_info->url = '';
		$plugins_updates->response['wp-rocket-disable-google-font-optimization/wp-rocket-disable-google-font-optimization.php'] = $plugin_info;
	}

	$plugin_info = new stdClass();
	$plugin_info->new_version = '';
	$plugin_info->slug = 'autoptimize';
	$plugin_info->package = 'http://democontent.codex-themes.com/plugins/thegem/recommended/autoptimize.zip';
	$plugin_info->url = '';
	$plugins_updates->response['autoptimize/autoptimize.php'] = $plugin_info;

	if($optimizer === 'wp-super-cache') {
		$plugin_info = new stdClass();
		$plugin_info->new_version = '';
		$plugin_info->slug = 'wp-super-cache';
		$plugin_info->package = 'http://democontent.codex-themes.com/plugins/thegem/recommended/wp-super-cache.zip';
		$plugin_info->url = '';
		$plugins_updates->response['wp-super-cache/wp-cache.php'] = $plugin_info;
	}

	set_site_transient('update_plugins', $plugins_updates);

	if($optimizer === 'wp-rocket') {
		$plugin = 'wp-rocket/wp-rocket.php';
		$upgrader_args = [
			'url' => 'admin.php?page=thegem-install-optimizers',
			'plugin' => $plugin,
			'nonce' => 'upgrade-plugin_' . $plugin,
			'title' => esc_html__( 'Install and Configure WP-Rocket and Autoptimize', 'thegem' ),
		];
	}
	if($optimizer === 'wp-super-cache') {
		$plugin = 'wp-super-cache/wp-cache.php';
		$upgrader_args = [
			'url' => 'admin.php?page=thegem-install-optimizers',
			'plugin' => $plugin,
			'nonce' => 'upgrade-plugin_' . $plugin,
			'title' => esc_html__( 'Install and Configure WP Super Cache and Autoptimize', 'thegem' ),
		];
	}
	$upgrader = new Plugin_Upgrader( new Plugin_Upgrader_Skin( $upgrader_args ) );
	if($optimizer === 'wp-rocket') {
		$installed = $upgrader->bulk_upgrade( ['wp-rocket-disable-google-font-optimization/wp-rocket-disable-google-font-optimization.php', 'autoptimize/autoptimize.php'] );
	}
	if($optimizer === 'wp-super-cache') {
		$installed = $upgrader->bulk_upgrade( ['wp-super-cache/wp-cache.php', 'autoptimize/autoptimize.php'] );
	}

	$installed_result = 'success';

	if(is_array($installed)) {
		foreach($installed as $plugin) {
			if(is_wp_error($plugin) && $installed_result === 'success') {
				$installed_result === 'error';
				if(isset($plugin->errors) && isset($plugin->errors['thegem_purchase_error'])) {
					$installed_result = 'error_purchase';
				}
			}
		}
	}

	if ($installed_result === 'success') {
		unset($plugins_updates->response['autoptimize/autoptimize.php']);
		if($optimizer === 'wp-rocket') {
			unset($plugins_updates->response['wp-rocket-disable-google-font-optimization/wp-rocket-disable-google-font-optimization.php']);
		}
		if($optimizer === 'wp-super-cache') {
			unset($plugins_updates->response['wp-super-cache/wp-cache.php']);
		}

		set_site_transient('update_plugins', $plugins_updates);

		global $wpdb;
		$theme_options = get_option('thegem_theme_options');

		if($optimizer === 'wp-rocket') {
			update_option('thegem_enabled_wprocket_autoptimize', 1);
			thegem_optimizers_backup_settings();
			delete_option('wp_rocket_settings');


			// delete old autoptimize options
			$oldWPRocketOptions = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '%%wp_rocket%'" );
			foreach($oldWPRocketOptions as $option ) {
				delete_option( $option->option_name );
			}

			$wpRocketSettings = array (
				'secret_cache_key' => str_replace( '.', '', uniqid( '', true )),
				'cache_mobile' => 1,
				'do_caching_mobile_files' => 0,
				'cache_webp' => 0,
				'cache_logged_user' => 0,
				'cache_ssl' => 1,
				'emoji' => 1,
				'cache_reject_uri' =>
				array (
				),
				'cache_reject_cookies' =>
				array (
				),
				'cache_reject_ua' =>
				array (
				),
				'cache_query_strings' =>
				array (
				),
				'cache_purge_pages' =>
				array (
				),
				'purge_cron_interval' => 10,
				'purge_cron_unit' => 'HOUR_IN_SECONDS',
				'exclude_css' =>
				array (
				),
				'exclude_js' =>
				array (
				),
				'exclude_inline_js' =>
				array (
				),
				'defer_all_js' => 0,
				'async_css' => 0,
				'critical_css' => '',
				'lazyload' => 0,
				'lazyload_iframes' => 0,
				'lazyload_youtube' => 0,
				'minify_css' => 0,
				'minify_css_key' => str_replace( '.', '', uniqid( '', true )),
				'minify_concatenate_css' => 0,
				'minify_js' => 0,
				'minify_js_key' => str_replace( '.', '', uniqid( '', true )),
				'minify_concatenate_js' => 0,
				'minify_google_fonts' => 1,
				'manual_preload' => 1,
				'sitemap_preload' => 0,
				'sitemap_preload_url_crawl' => '500000',
				'sitemaps' =>
				array (
				),
				'dns_prefetch' =>
				array (
				),
				'preload_fonts' =>
				array (
				),
				'database_revisions' => 0,
				'database_auto_drafts' => 0,
				'database_trashed_posts' => 0,
				'database_spam_comments' => 0,
				'database_trashed_comments' => 0,
				'database_all_transients' => 0,
				'database_optimize_tables' => 0,
				'schedule_automatic_cleanup' => 0,
				'automatic_cleanup_frequency' => '',
				'cdn' => 0,
				'cdn_cnames' =>
				array (
				),
				'cdn_zone' =>
				array (
				),
				'cdn_reject_files' =>
				array (
				),
				'do_cloudflare' => 0,
				'cloudflare_email' => '',
				'cloudflare_api_key' => '',
				'cloudflare_zone_id' => '',
				'cloudflare_devmode' => 0,
				'cloudflare_protocol_rewrite' => 0,
				'cloudflare_auto_settings' => 0,
				'cloudflare_old_settings' => '',
				'control_heartbeat' => 1,
				'heartbeat_site_behavior' => 'reduce_periodicity',
				'heartbeat_admin_behavior' => 'reduce_periodicity',
				'heartbeat_editor_behavior' => 'reduce_periodicity',
				'varnish_auto_purge' => 0,
				'analytics_enabled' => 0,
				'sucury_waf_cache_sync' => 0,
				'sucury_waf_api_key' => '',
				'async_css_mobile' => 1,
				'exclude_defer_js' =>
				array (
				),
				'delay_js' => 1,
				'delay_js_exclusions' =>
				array (
				  0 => 'thegem-pagespeed-lazy-items',
				  1 => '\/jquery-?([0-9.]){0,10}\.(min\.|slim\.|slim\.min\.)?js',
				  2 => 'revslider',
				  3 => 'layerslider',
				  4 => '\(',
				  5 => '\{',
				  6 => '\/recaptcha\/api\.js',
				  7 => 'odometer.js'
				),
				'remove_unused_css' => 0,
				'remove_unused_css_safelist' =>
				array (
				),
				'preload_links' => 1,
				'yoast_xml_sitemap' => 0,
				'image_dimensions' => 0,
				'exclude_lazyload' =>
				array (
				),
				'license' => time(),
				'version' => '3.10.1',
			);

			$response_p = wp_remote_get(add_query_arg(array('code' => $theme_options['purchase_code'], 'info'=>thegem_get_activation_info(), 'site_url' => get_site_url()), esc_url('http://democontent.codex-themes.com/av_validate_code.php')), array('timeout' => 20));
			if(!is_wp_error($response_p)) {
				$rp_data = json_decode($response_p['body'], true);
				if(is_array($rp_data) && isset($rp_data['wp_rocket_settings'])) {
					delete_option('thegem_optimizer_error');
				} else {
					update_option('thegem_optimizer_error', __('Something went wrong during the installation and activation of caching and minifying plugins. Please try again to click on "Reinstall" button or contact our support. Thank you', 'thegem'));
				}
			} else {
				update_option('thegem_optimizer_error', __('Something went wrong during the installation and activation of caching and minifying plugins. Please try again to click on "Reinstall" button or contact our support. Thank you', 'thegem'));
			}

			update_option('wp_rocket_settings', $wpRocketSettings);

			//activate_plugin('wp-rocket/wp-rocket.php');
			activate_plugin('wp-rocket-disable-google-font-optimization/wp-rocket-disable-google-font-optimization.php');
			$theme_options['caching_plugin'] = 'wp_rocket';
			update_option('thegem_theme_options', $theme_options);
		}

		if($optimizer === 'wp-super-cache') {
			global $wp_filesystem;
			$theme_options['caching_plugin'] = 'wp_super_cache';
			update_option('thegem_theme_options', $theme_options);
			$copy_result = false;
			$put_result = false;
			if(is_object( $wp_filesystem ) && !(is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors())) {
				$wp_filesystem->delete(trailingslashit( $wp_filesystem->wp_content_dir() ) . 'advanced-cache.php');
				$copy_result = $wp_filesystem->copy($wp_filesystem->find_folder(THEGEM_THEME_PATH) . '/plugins/super-cache-config/advanced-cache.php', trailingslashit( $wp_filesystem->wp_content_dir() ) . 'advanced-cache.php');
				$wp_filesystem->chmod(trailingslashit( $wp_filesystem->wp_content_dir() ) . 'advanced-cache.php', 0666);
				$wpsc_config_content = $wp_filesystem->get_contents($wp_filesystem->find_folder(THEGEM_THEME_PATH) . '/plugins/super-cache-config/wp-cache-config.php');
				$home_path = parse_url( site_url() );
				$home_path = trailingslashit( array_key_exists( 'path', $home_path ) ? $home_path['path'] : '' );
				$wpsc_config_content = str_replace('$wp_cache_home_path = \'/\';', '$wp_cache_home_path = \''.$home_path.'\';', $wpsc_config_content);
				$wpsc_config_content = str_replace('$cache_page_secret = \'\';', '$cache_page_secret = \''.md5( date( 'H:i:s' ) . mt_rand() ).'\';', $wpsc_config_content);
				$put_result = $wp_filesystem->put_contents(trailingslashit( $wp_filesystem->wp_content_dir() ) . 'wp-cache-config.php', $wpsc_config_content);
				$wp_filesystem->chmod(trailingslashit( $wp_filesystem->wp_content_dir() ) . 'wp-cache-config.php', 0666);
				$wp_config_content = $wp_filesystem->get_contents_array($wp_filesystem->find_folder($wp_filesystem->abspath()) . '/wp-config.php');
				foreach($wp_config_content as $number => $line) {
					if(strpos($line, 'WPCACHEHOME') !== false || strpos($line, 'WP_CACHE') !== false) {
						unset($wp_config_content[$number]);
					}
				}
				$wp_filesystem->put_contents($wp_filesystem->find_folder($wp_filesystem->abspath()) . '/wp-config.php', implode("\n", $wp_config_content));
				$wp_config_content = $wp_filesystem->get_contents($wp_filesystem->find_folder($wp_filesystem->abspath()) . '/wp-config.php');
				if(strpos($wp_config_content, 'WPCACHEHOME') === false) {
					$wp_config_content = str_replace('<?php', '<?php'.PHP_EOL.'define( \'WPCACHEHOME\', \''.WP_PLUGIN_DIR . '/wp-super-cache/'.'\');', $wp_config_content);
				}
				if(strpos($wp_config_content, 'WP_CACHE') === false) {
					$wp_config_content = str_replace('<?php', '<?php'.PHP_EOL.'define( \'WP_CACHE\', true);', $wp_config_content);
				}
				$wp_filesystem->put_contents($wp_filesystem->find_folder($wp_filesystem->abspath()) . '/wp-config.php', $wp_config_content);
				if(!$wp_filesystem->exists(trailingslashit( $wp_filesystem->wp_content_dir() ) . 'cache')) {
					$wp_filesystem->mkdir(trailingslashit( $wp_filesystem->wp_content_dir() ) . 'cache', 0777);
				}
			}
			if($copy_result && $put_result) {
				update_option('thegem_enabled_wpsupercache_autoptimize', 1);
				update_option('thegem_wpsupercache_activated', 1);
				activate_plugin('wp-super-cache/wp-cache.php');
			} else {
				update_option('thegem_wpsupercache_error', __('One-click optimization cannot be completed. WP Super Cache configuration file cannot be modified. Please adjust file permissions or deinstall WP Super Cache from your WordPress installation and run one-click optimization again.', 'thegem'));
			}
		}

		// delete old autoptimize options
		$oldAutoptimizeOptions = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'autoptimize_%'" );
		foreach($oldAutoptimizeOptions as $option ) {
			delete_option( $option->option_name );
		}
	
		activate_plugin('autoptimize/autoptimize.php');
	
		// set autoptimize options
		$autoptimizeOptions = array(
			'autoptimize_enable_site_config'        => 'on',
			'autoptimize_js_defer_not_aggregate'    => '',
			'autoptimize_js_defer_inline'           => '',
			'autoptimize_js_trycatch'               => '',
			'autoptimize_js_justhead'               => '',
			'autoptimize_js_include_inline'         => '',
			'autoptimize_js_forcehead'              => '',
			'autoptimize_css_exclude'               => 'admin-bar.min.css, dashicons.min.css, wp-content/cache/, wp-content/uploads/',
			'autoptimize_css_justhead'              => '',
			'autoptimize_css_defer'                 => '',
			'autoptimize_css_defer_inline'          => '',
			'autoptimize_css_inline'                => '',
			'autoptimize_css_datauris'              => '',
			'autoptimize_cdn_url'                   => '',
			'autoptimize_cache_nogzip'              => 'on',
			'autoptimize_optimize_checkout'         => 'on',
			'autoptimize_minify_excluded'           => 'on',
			'autoptimize_cache_fallback'            => 'on',
			'autoptimize_enable_meta_ao_settings'   => 'on',

			'autoptimize_js'=>'on',
			'autoptimize_js_aggregate'=>'on',
			'autoptimize_js_exclude' => 'wp-includes/js/dist/, wp-includes/js/tinymce/, js/jquery/jquery.js, js/jquery/jquery.min.js,thegem-pagespeed-lazy-items.js, revslider, layerslider, odometer.js',
			'autoptimize_css'=>'on',
			'autoptimize_css_aggregate'=>'on',
			'autoptimize_css_include_inline'=>'',
			'autoptimize_html'=>'on',
			'autoptimize_html_keepcomments'=>'on',
			'autoptimize_optimize_logged'=>'',
		);

		foreach($autoptimizeOptions as $optName => $optValue) {
			update_option($optName, $optValue);
		}

		$autoptimize_extra_defaults = array(
			'autoptimize_extra_checkbox_field_1' => '1',
			'autoptimize_extra_checkbox_field_0' => '0',
			'autoptimize_extra_radio_field_4' => '1',
			'autoptimize_extra_text_field_2' => '',
			'autoptimize_extra_text_field_3' => '',
			'autoptimize_extra_text_field_7' => '',
			'autoptimize_extra_checkbox_field_8' => '0',
		);
		update_option('autoptimize_extra_settings', $autoptimize_extra_defaults);

	} elseif( $installed_result === 'error_purchase' ) {
		update_option('thegem_wpsupercache_error', __('Purchase code verification failed. One-click optimization has been aborted.', 'thegem'));
	} else {
		update_option('thegem_wpsupercache_error', __('One-click optimization failed. Caching or minifying plugin could not be installed. Please check your folders permissions and try again.', 'thegem'));
	}
	echo '</div>';

	?>
	<script type="text/javascript">
		window.addEventListener('load', function() {
			<?php if ($installed) { ?>
				window.location.href='admin.php?page=thegem-theme-options#/performance/page-speed';
			<?php } else { ?>
				document.getElementById('thegem-downgrade-overlay').style.display = 'none';
			<?php } ?>
		});
	</script>
	<?php
}

add_action('admin_menu', 'thegem_install_optimizers_admin_menu', 71);

function thegem_uninstall_optimizers_admin_menu() {
	add_submenu_page(null, esc_html__('Uninstall TheGem Optimizers','thegem'), esc_html__('Uninstall TheGem Optimizers','thegem'), 'edit_theme_options', 'thegem-uninstall-optimizers', 'thegem_uninstall_optimizers', 0);
}

function thegem_uninstall_optimizers() {
	$uninstalled = false;
	echo '<style>.thegem-downgrade-panel .wrap+.wrap form{display:none}.thegem-downgrade-panel .wrap:first-child a,.thegem-downgrade-panel .wrap:first-child iframe{display:none} .blink_me {animation: blinker 1s linear infinite;} @keyframes blinker {50% {opacity: 0;}}</style>';
	echo '<div id="thegem-downgrade-overlay" style="position: fixed;display:flex;align-items:center;justify-content:center;width: 100%;height: 100%;top: 0;left: 0;right: 0;bottom: 0;font-size:24px;color:#ffffff;background-color: rgba(51,58,66,0.99);z-index: 9999;"><span>'.esc_html__( 'Processing the one click optimization. Please wait', 'thegem' ).'</span><span class="blink_me">...</span></div>';
	echo '<div class="thegem-downgrade-panel">';
	remove_all_filters( 'pre_set_site_transient_update_plugins' );
	remove_filter('nonce_life', 'thegem_nonce_life');
	require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

	$url = wp_nonce_url('admin.php?page=thegem-uninstall-optimizers','thegem-theme-options');
	if (false === ($creds = request_filesystem_credentials($url) ) ) {

	} elseif(!WP_Filesystem($creds)) {
		request_filesystem_credentials($url, '', true);
	}
	if(get_option('thegem_enabled_wpsupercache_autoptimize') && function_exists('wpsc_init')) {
		global $wp_filesystem;
		if(is_object( $wp_filesystem ) && !(is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors())) {
			$result = $wp_filesystem->delete(trailingslashit( $wp_filesystem->wp_content_dir() ) . 'advanced-cache.php');
			$result = $wp_filesystem->delete(trailingslashit( $wp_filesystem->wp_content_dir() ) . 'wp-cache-config.php');
			$wp_config_content = $wp_filesystem->get_contents_array($wp_filesystem->find_folder($wp_filesystem->abspath()) . '/wp-config.php');
			foreach($wp_config_content as $number => $line) {
				if(strpos($line, 'WPCACHEHOME') !== false || strpos($line, 'WP_CACHE') !== false) {
					unset($wp_config_content[$number]);
				}
			}
			$wp_filesystem->put_contents($wp_filesystem->find_folder($wp_filesystem->abspath()) . '/wp-config.php', implode("\n", $wp_config_content));

			deactivate_plugins(['wp-super-cache/wp-cache.php'], true);
			delete_option('thegem_enabled_wpsupercache_autoptimize');
			$uninstalled = true;
		}
	}

	echo '</div>';

	?>
	<script type="text/javascript">
		window.addEventListener('load', function() {
			<?php if ($uninstalled) { ?>
				window.location.href='admin.php?page=thegem-theme-options#/performance/page-speed';
			<?php } else { ?>
				document.getElementById('thegem-downgrade-overlay').style.display = 'none';
			<?php } ?>
		});
	</script>
	<?php
}

add_action('admin_menu', 'thegem_uninstall_optimizers_admin_menu', 71);
