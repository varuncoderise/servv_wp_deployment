<?php
/*
Plugin Name: Perfect Images (Retina, Thumbnails, Replace)
Plugin URI: https://meowapps.com
Description: Retina, Replace Images, Regenerate Thumbnails, Image Sizes Management, Image Threshold and more.
Version: 6.2.9
Author: Jordy Meow
Author URI: https://meowapps.com
Text Domain: wp-retina-2x
Domain Path: /languages

Originally developed for two of my websites:
- Jordy Meow (https://offbeatjapan.org)
- Haikyo (https://haikyo.org)
*/

if ( !defined( 'WR2X_VERSION' ) ) {
	define( 'WR2X_VERSION', '6.2.9' );
	define( 'WR2X_PREFIX', 'wr2x' );
	define( 'WR2X_DOMAIN', ' wp-retina-2x' );
	define( 'WR2X_ENTRY', __FILE__ );
	define( 'WR2X_PATH', dirname( __FILE__ ) );
	define( 'WR2X_URL', plugin_dir_url( __FILE__ ) );
	define( 'WR2X_VERSION_RETINAJS', '5.6.1' );
	define( 'WR2X_VERSION_PICTUREFILL', '3.0.2' );
	define( 'WR2X_VERSION_LAZYSIZES', '5.1.0' );
	define( 'WR2X_VERSION_RETINA_IMAGES', '1.7.2' );
	define( 'WR2X_BASENAME', plugin_basename( __FILE__ ) );
}

require_once( 'classes/init.php');

?>
