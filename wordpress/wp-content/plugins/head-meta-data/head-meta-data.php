<?php 
/*
	Plugin Name: Head Meta Data
	Plugin URI: https://perishablepress.com/head-metadata-plus/
	Description: Adds a custom set of &lt;meta&gt; tags to the &lt;head&gt; section of all posts &amp; pages.
	Tags: meta, metadata, head, header, tags,  custom, custom content, author, publisher, language, wp_head
	Author: Jeff Starr
	Author URI: https://plugin-planet.com/
	Donate link: https://m0n.co/donate
	Contributors: specialk
	Requires at least: 4.1
	Tested up to: 4.9
	Stable tag: 20171103
	Version: 20171103
	Requires PHP: 5.2
	Text Domain: head-meta-data
	Domain Path: /languages
	License: GPL v2 or later
*/

/*
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 
	2 of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	with this program. If not, visit: https://www.gnu.org/licenses/
	
	Copyright 2017 Monzilla Media. All rights reserved.
*/

if (!defined('ABSPATH')) die();

$hmd_wp_vers = '4.1';
$hmd_version = '20171103';
$hmd_plugin  = esc_html__('Head Meta Data', 'head-meta-data');
$hmd_options = get_option('hmd_options');
$hmd_path    = plugin_basename(__FILE__); // 'head-meta-data/head-meta-data.php';
$hmd_homeurl = 'https://perishablepress.com/head-metadata-plus/';

function hmd_i18n_init() {
	load_plugin_textdomain('head-meta-data', false, dirname(plugin_basename(__FILE__)) .'/languages/');
}
add_action('plugins_loaded', 'hmd_i18n_init');

function hmd_require_wp_version() {
	global $hmd_path, $hmd_plugin, $hmd_wp_vers;
	if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
		$wp_version = get_bloginfo('version');
		if (version_compare($wp_version, $hmd_wp_vers, '<')) {
			if (is_plugin_active($hmd_path)) {
				deactivate_plugins($hmd_path);
				$msg =  '<strong>' . $hmd_plugin . '</strong> ' . esc_html__('requires WordPress ', 'head-meta-data') . $hmd_wp_vers . esc_html__(' or higher, and has been deactivated!', 'head-meta-data') . '<br />';
				$msg .= esc_html__('Please return to the', 'head-meta-data') . ' <a href="' . admin_url() . '">' . esc_html__('WordPress Admin area', 'head-meta-data') . '</a> ' . esc_html__('to upgrade WordPress and try again.', 'head-meta-data');
				wp_die($msg);
			}
		}
	}
}
add_action('admin_init', 'hmd_require_wp_version');

function head_meta_data() { 
	echo hmd_display_content();
}
add_action('wp_head', 'head_meta_data');

function hmd_shortcode() {
	$get_meta_data = hmd_display_content();
	$the_meta_data = str_replace(array('>', '<'), array('&gt;','&lt;'), $get_meta_data);
	return $the_meta_data;
}
add_shortcode('head_meta_data','hmd_shortcode');

function hmd_display_content() {
	global $hmd_options;
	$hmd_output = '';
	$hmd_enable = $hmd_options['hmd_enable']; 
	$hmd_format = $hmd_options['hmd_format'];
	if ($hmd_format == false) {
		$close_tag = '" />' . "\n";
	} else {
		$close_tag = '">' . "\n";
	}
	if ($hmd_enable == true) {
		if ($hmd_options['hmd_abstract']   !== '') $hmd_output  = "\t\t" .'<meta name="abstract" content="'       . $hmd_options['hmd_abstract']   . $close_tag;
		if ($hmd_options['hmd_author']     !== '') $hmd_output .= "\t\t" .'<meta name="author" content="'         . $hmd_options['hmd_author']     . $close_tag;
		if ($hmd_options['hmd_classify']   !== '') $hmd_output .= "\t\t" .'<meta name="classification" content="' . $hmd_options['hmd_classify']   . $close_tag;
		if ($hmd_options['hmd_copyright']  !== '') $hmd_output .= "\t\t" .'<meta name="copyright" content="'      . $hmd_options['hmd_copyright']  . $close_tag;
		if ($hmd_options['hmd_designer']   !== '') $hmd_output .= "\t\t" .'<meta name="designer" content="'       . $hmd_options['hmd_designer']   . $close_tag;
		if ($hmd_options['hmd_distribute'] !== '') $hmd_output .= "\t\t" .'<meta name="distribution" content="'   . $hmd_options['hmd_distribute'] . $close_tag;
		if ($hmd_options['hmd_language']   !== '') $hmd_output .= "\t\t" .'<meta name="language" content="'       . $hmd_options['hmd_language']   . $close_tag;
		if ($hmd_options['hmd_publisher']  !== '') $hmd_output .= "\t\t" .'<meta name="publisher" content="'      . $hmd_options['hmd_publisher']  . $close_tag;
		if ($hmd_options['hmd_rating']     !== '') $hmd_output .= "\t\t" .'<meta name="rating" content="'         . $hmd_options['hmd_rating']     . $close_tag;
		if ($hmd_options['hmd_resource']   !== '') $hmd_output .= "\t\t" .'<meta name="resource-type" content="'  . $hmd_options['hmd_resource']   . $close_tag;
		if ($hmd_options['hmd_revisit']    !== '') $hmd_output .= "\t\t" .'<meta name="revisit-after" content="'  . $hmd_options['hmd_revisit']    . $close_tag;
		if ($hmd_options['hmd_subject']    !== '') $hmd_output .= "\t\t" .'<meta name="subject" content="'        . $hmd_options['hmd_subject']    . $close_tag;
		if ($hmd_options['hmd_template']   !== '') $hmd_output .= "\t\t" .'<meta name="template" content="'       . $hmd_options['hmd_template']   . $close_tag;
	}
	return $hmd_output;
}

function hmd_custom_shortcode() {
	global $hmd_options;
	if ($hmd_options['hmd_custom'] !== '') {
		$get_custom_data = $hmd_options['hmd_custom'];
		$the_custom_data = "\t\t" . str_replace(array('>', '<'), array('&gt;','&lt;'), $get_custom_data);
		return $the_custom_data;
	}
}
add_shortcode('hmd_custom','hmd_custom_shortcode');

function hmd_custom_content() {
	global $hmd_options;
	
	$custom = isset($hmd_options['hmd_custom']) ? $hmd_options['hmd_custom'] : '';
	
	$format = apply_filters('hmd_date_format', 'Y-m-d');
	
	$post_date = is_singular() ? get_the_modified_date($format) : hmd_latest_post_date($format);
	
	$patterns = array();
	$patterns[0] = "/\[hmd_post_date\]/";
	
	$replacements = array();
	$replacements[0] = $post_date;
	
	$custom = preg_replace($patterns, $replacements, $custom);
	
	echo "\t\t" . $custom . "\n";
	
}
add_action('wp_head', 'hmd_custom_content');

function hmd_latest_post_date($format) {
	
	$args = array(
		'posts_per_page' => 1,
		'orderby'        => 'date',
		'post_status'    => 'publish',
	);
	$posts = get_posts($args);
	
	$updated = '';
	
	foreach ($posts as $post) {
		
		setup_postdata($post);
		
		$updated = get_the_modified_date($format);
		
	}
	
	wp_reset_postdata();
	
	return $updated;
	
}

function hmd_plugin_action_links($links, $file) {
	global $hmd_path;
	if ($file == $hmd_path) {
		$hmd_links = '<a href="' . get_admin_url() . 'options-general.php?page=' . $hmd_path . '">' . esc_html__('Settings', 'head-meta-data') .'</a>';
		array_unshift($links, $hmd_links);
	}
	return $links;
}
add_filter ('plugin_action_links', 'hmd_plugin_action_links', 10, 2);

function add_hmd_links($links, $file) {
	if ($file == plugin_basename(__FILE__)) {
		
		$href  = 'https://wordpress.org/support/plugin/head-meta-data/reviews/?rate=5#new-post';
		$title = esc_html__('Give us a 5-star rating at WordPress.org', 'head-meta-data');
		$text  = esc_html__('Rate this plugin', 'head-meta-data') .'&nbsp;&raquo;';
		
		$links[] = '<a target="_blank" href="'. $href .'" title="'. $title .'">'. $text .'</a>';
		
	}
	return $links;
}
add_filter('plugin_row_meta', 'add_hmd_links', 10, 2);

function hmd_delete_plugin_options() {
	delete_option('hmd_options');
}
if ($hmd_options['default_options'] == 1) {
	register_uninstall_hook (__FILE__, 'hmd_delete_plugin_options');
}

function hmd_add_defaults() {
	// meta subject
	$args = array('orderby'=>'name', 'order'=>'ASC');
	$categories = get_categories($args);
	$num_cats = count($categories);
	$subjects = '';
	$i = 0;
	foreach ($categories as $category) { 
		$subjects .= $category->name;
		if (++$i !== $num_cats) {
			$subjects .= ', ';
		}
	}
	// name, description, language
	$site_name = get_bloginfo('name');
	$site_desc = get_bloginfo('description');
	$site_lang = get_bloginfo('language');
	// template and designer
	$get_theme = wp_get_theme();
	$the_theme = $get_theme->Name;
	$designer  = $get_theme->display('Author', FALSE);;
	// author name
	$user_info = get_userdata(1);
	if ($user_info == true) {
		$admin_name = $user_info->user_login;
	} else {
		$admin_name = 'Perishable';
	}
	$tmp = get_option('hmd_options');
	if(($tmp['default_options'] == '1') || (!is_array($tmp))) {
		$arr = array(
			'default_options' => 0,
			'hmd_abstract'    => $site_desc,
			'hmd_author'      => $admin_name,
			'hmd_classify'    => $subjects,
			'hmd_copyright'   => 'Copyright ' . $site_name . ' - All rights Reserved.',
			'hmd_designer'    => $designer,
			'hmd_distribute'  => 'Global',
			'hmd_language'    => $site_lang,
			'hmd_publisher'   => $site_name,
			'hmd_rating'      => 'General',
			'hmd_resource'    => 'Document',
			'hmd_revisit'     => '3',
			'hmd_subject'     => $subjects,
			'hmd_template'    => $the_theme,
			'hmd_enable'      => 1,
			'hmd_custom'      => '<meta name="example" content="custom">',
			'hmd_format'      => 1,
		);
		update_option('hmd_options', $arr);
	}
}
register_activation_hook (__FILE__, 'hmd_add_defaults');

function hmd_init() {
	register_setting('hmd_plugin_options', 'hmd_options', 'hmd_validate_options');
}
add_action ('admin_init', 'hmd_init');

function hmd_validate_options($input) {
	
	if (!isset($input['default_options'])) $input['default_options'] = null;
	$input['default_options'] = ($input['default_options'] == 1 ? 1 : 0);

	$input['hmd_abstract']   = esc_attr($input['hmd_abstract']);
	$input['hmd_author']     = esc_attr($input['hmd_author']);
	$input['hmd_classify']   = esc_attr($input['hmd_classify']);
	$input['hmd_copyright']  = esc_attr($input['hmd_copyright']);
	$input['hmd_designer']   = esc_attr($input['hmd_designer']);
	$input['hmd_distribute'] = esc_attr($input['hmd_distribute']);
	$input['hmd_language']   = esc_attr($input['hmd_language']);
	$input['hmd_publisher']  = esc_attr($input['hmd_publisher']);
	$input['hmd_rating']     = esc_attr($input['hmd_rating']);
	$input['hmd_resource']   = esc_attr($input['hmd_resource']);
	$input['hmd_revisit']    = esc_attr($input['hmd_revisit']);
	$input['hmd_subject']    = esc_attr($input['hmd_subject']);
	$input['hmd_template']   = esc_attr($input['hmd_template']);

	if (!isset($input['hmd_enable'])) $input['hmd_enable'] = null;
	$input['hmd_enable'] = ($input['hmd_enable'] == 1 ? 1 : 0);

	// dealing with kses
	global $allowedposttags;
	$allowed_atts = array(
		'align'=>array(), 'class'=>array(), 'id'=>array(), 'dir'=>array(), 'lang'=>array(), 'style'=>array(), 'label'=>array(), 'url'=>array(), 
		'xml:lang'=>array(), 'src'=>array(), 'alt'=>array(), 'name'=>array(), 'content'=>array(), 'http-equiv'=>array(), 'profile'=>array(), 
		'href'=>array(), 'property'=>array(), 'title'=>array(), 'rel'=>array(), 'type'=>array(), 'charset'=>array(), 'media'=>array(), 'rev'=>array(),
		);
	$allowedposttags['strong'] = $allowed_atts;
	$allowedposttags['script'] = $allowed_atts;
	$allowedposttags['style'] = $allowed_atts;
	$allowedposttags['small'] = $allowed_atts;
	$allowedposttags['span'] = $allowed_atts;
	$allowedposttags['meta'] = $allowed_atts;
	$allowedposttags['item'] = $allowed_atts;
	$allowedposttags['base'] = $allowed_atts;
	$allowedposttags['link'] = $allowed_atts;
	$allowedposttags['abbr'] = $allowed_atts;
	$allowedposttags['code'] = $allowed_atts;
	$allowedposttags['div'] = $allowed_atts;
	$allowedposttags['img'] = $allowed_atts;
	$allowedposttags['h1'] = $allowed_atts;
	$allowedposttags['h2'] = $allowed_atts;
	$allowedposttags['h3'] = $allowed_atts;
	$allowedposttags['h4'] = $allowed_atts;
	$allowedposttags['h5'] = $allowed_atts;
	$allowedposttags['ol'] = $allowed_atts;
	$allowedposttags['ul'] = $allowed_atts;
	$allowedposttags['li'] = $allowed_atts;
	$allowedposttags['em'] = $allowed_atts;
	$allowedposttags['p'] = $allowed_atts;
	$allowedposttags['a'] = $allowed_atts;

	$input['hmd_custom'] = wp_kses($input['hmd_custom'], $allowedposttags);

	if (!isset($input['hmd_format'])) $input['hmd_format'] = null;
	$input['hmd_format'] = ($input['hmd_format'] == 1 ? 1 : 0);

	return $input;
}

function hmd_add_options_page() {
	global $hmd_plugin;
	add_options_page($hmd_plugin, $hmd_plugin, 'manage_options', __FILE__, 'hmd_render_form');
}
add_action ('admin_menu', 'hmd_add_options_page');

function hmd_render_form() {
	global $hmd_plugin, $hmd_options, $hmd_path, $hmd_homeurl, $hmd_version; ?>

	<style type="text/css">
		.mm-panel-overview {
			padding: 0 15px 15px 140px; 
			background-image: url(<?php echo plugins_url(); ?>/head-meta-data/hmd-logo.jpg);
			background-repeat: no-repeat; background-position: 15px 0; background-size: 120px 88px;
			}
		
		#mm-plugin-options h1 small { line-height: 12px; font-size: 12px; color: #bbb; }
		#mm-plugin-options h2 { margin: 0; padding: 12px 0 12px 15px; font-size: 16px; cursor: pointer; }
		#mm-plugin-options h3 { margin: 20px 15px; font-size: 14px; }
		
		#mm-plugin-options p { margin-left: 15px; }
		#mm-plugin-options ul { margin-left: 40px; margin-bottom: 15px; }
		#mm-plugin-options li { margin: 8px 0; list-style-type: disc; }
		#mm-plugin-options abbr { cursor: help; border-bottom: 1px dotted #dfdfdf; }
		
		.mm-table-wrap { margin: 15px; }
		.mm-table-wrap td { padding: 5px 10px; vertical-align: middle; }
		.mm-table-wrap .widefat th { padding: 10px 15px; vertical-align: middle; }
		.mm-table-wrap .widefat td { padding: 10px; vertical-align: middle; }

		.mm-item-caption { margin: 3px 0 0 3px; font-size: 11px; color: #777; line-height: 17px; }
		.mm-item-caption code { font-size: 11px; }
		.mm-code-example { margin: 10px 0 20px 0; }
		.mm-code-example div { margin-left: 15px; }
		.mm-code-example pre { width: 90%; overflow: auto; margin: 10px 20px; padding: 10px; border: 1px solid #efefef; }
		.mm-code { background-color: #fafae0; color: #333; font-size: 14px; }

		#setting-error-settings_updated { margin: 8px 0 15px 0; }
		#setting-error-settings_updated p { margin: 7px 0; }
		#mm-plugin-options .button-primary { margin: 0 0 15px 15px; }

		#mm-panel-toggle { margin: 5px 0; }
		#mm-credit-info { margin-top: -5px; }
	</style>

	<div id="mm-plugin-options" class="wrap">
		<h1><?php echo $hmd_plugin; ?> <small><?php echo 'v' . $hmd_version; ?></small></h1>
		<div id="mm-panel-toggle"><a href="<?php get_admin_url() . 'options-general.php?page=' . $hmd_path; ?>"><?php esc_html_e('Toggle all panels', 'head-meta-data'); ?></a></div>

		<form method="post" action="options.php">
			<?php $hmd_options = get_option('hmd_options'); settings_fields('hmd_plugin_options'); ?>

			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					
					<div id="mm-panel-overview" class="postbox">
						<h2><?php esc_html_e('Overview', 'head-meta-data'); ?></h2>
						<div class="toggle<?php if (isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<div class="mm-panel-overview">
								<p>
									<strong><?php echo $hmd_plugin; ?></strong> <?php esc_html_e('(HMD) adds a custom set of', 'head-meta-data'); ?> 
									<code>&lt;meta&gt;</code> <?php esc_html_e('tags to the', 'head-meta-data'); ?> 
									<code>&lt;head&gt;</code> <?php esc_html_e('section of all posts and pages.', 'head-meta-data'); ?>
								</p>
								<ul>
									<li><a id="mm-panel-primary-link" href="#mm-panel-primary"><?php esc_html_e('Plugin Settings', 'head-meta-data'); ?></a></li>
									<li><a id="mm-panel-secondary-link" href="#mm-panel-secondary"><?php esc_html_e('Live Preview', 'head-meta-data'); ?></a></li>
									<li><a target="_blank" href="https://wordpress.org/plugins/head-meta-data/"><?php esc_html_e('Plugin Homepage', 'head-meta-data'); ?></a></li>
								</ul>
								<p>
									<?php esc_html_e('If you like this plugin, please', 'head-meta-data'); ?> 
									<a target="_blank" href="https://wordpress.org/support/plugin/head-meta-data/reviews/?rate=5#new-post" title="<?php esc_attr_e('THANK YOU for your support!', 'head-meta-data'); ?>"><?php esc_html_e('give it a 5-star rating', 'head-meta-data'); ?>&nbsp;&raquo;</a>
								</p>
							</div>
						</div>
					</div>
					
					<div id="mm-panel-primary" class="postbox">
						<h2><?php esc_html_e('Plugin Settings', 'head-meta-data'); ?></h2>
						<div class="toggle<?php if (!isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<h3><?php esc_html_e('Meta tags', 'head-meta-data'); ?></h3>
							<p>
								<?php esc_html_e('Here you may define your', 'head-meta-data'); ?> <code>&lt;meta&gt;</code> <?php esc_html_e('tags. To disable any tag, leave it blank.', 'head-meta-data'); ?> 
								<a target="_blank" href="https://perishablepress.com/contact/"><?php esc_html_e('Suggest more meta tags', 'head-meta-data'); ?>&nbsp;&raquo;</a>
							</p>
							<div class="mm-table-wrap">
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_enable]"><?php esc_html_e('Enable tags', 'head-meta-data'); ?></label></th>
										<td><input type="checkbox" name="hmd_options[hmd_enable]" value="1" <?php if (isset($hmd_options['hmd_enable'])) { checked('1', $hmd_options['hmd_enable']); } ?> /> 
										<span class="mm-item-caption"><?php esc_html_e('Check this box to enable meta tags.', 'head-meta-data'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_format]"><?php esc_html_e('HTML format', 'head-meta-data'); ?></label></th>
										<td><input type="checkbox" name="hmd_options[hmd_format]" value="1" <?php if (isset($hmd_options['hmd_format'])) { checked('1', $hmd_options['hmd_format']); } ?> /> 
										<span class="mm-item-caption"><?php esc_html_e('Uncheck this box if you want to enable XHTML format. Leave checked for HTML (default).', 'head-meta-data'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_abstract]"><?php esc_html_e('Meta abstract', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_abstract]" value="<?php echo $hmd_options['hmd_abstract']; ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Provide an abstract summarizing the content of your website. Should be one brief sentence.', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_author]"><?php esc_html_e('Meta author', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_author]" value="<?php echo $hmd_options['hmd_author']; ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Indicate the author(s) of the website. May also include email address if you prefer plenty spam.', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_classify]"><?php esc_html_e('Meta classification', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_classify]" value="<?php echo $hmd_options['hmd_classify']; ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Classify your website. Examples: Website Design, Digital Photography.', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_copyright]"><?php esc_html_e('Meta copyright', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_copyright]" value="<?php echo $hmd_options['hmd_copyright']; ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Indicate the copyright information for the site. May include trademark names, patent numbers, etc.', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_designer]"><?php esc_html_e('Meta designer', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_designer]" value="<?php echo $hmd_options['hmd_designer']; ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Indicate the designer(s) of the website.', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_distribute]"><?php esc_html_e('Meta distribution', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_distribute]" value="<?php echo $hmd_options['hmd_distribute']; ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Indicate the distribution level on the web. Examples: Global, Regional, Local.', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_language]"><?php esc_html_e('Meta language', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_language]" value="<?php echo $hmd_options['hmd_language']; ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Indicate the primary language used for your website. Examples: EN-US, EN, FR.', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_publisher]"><?php esc_html_e('Meta publisher', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_publisher]" value="<?php echo $hmd_options['hmd_publisher']; ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Indicate the publisher of the website.', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_rating]"><?php esc_html_e('Meta rating', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_rating]" value="<?php echo $hmd_options['hmd_rating']; ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Indicate the rating of the site&rsquo;s content. Examples: General, Mature, Restricted.', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_resource]"><?php esc_html_e('Meta resource-type', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_resource]" value="<?php echo $hmd_options['hmd_resource']; ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Indicate the primary resource type for the site. Example: Document.', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_revisit]"><?php esc_html_e('Meta revisit-after', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_revisit]" value="<?php echo $hmd_options['hmd_revisit']; ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Frequency (in days) that search engines should revisit your site for re-indexing. Examples: 1, 2, 3.', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_subject]"><?php esc_html_e('Meta subject', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_subject]" value="<?php echo $hmd_options['hmd_subject']; ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Indicate the primary subject(s) of the website. Examples: Photography, Sports, Pancakes, etc.', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_template]"><?php esc_html_e('Meta template', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_template]" value="<?php echo $hmd_options['hmd_template']; ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Indicate any template that pertains to the site. Example: Zonked Out WordPress Theme.', 'head-meta-data'); ?></div></td>
									</tr>
								</table>
							</div>
							<h3><?php esc_html_e('Custom content', 'head-meta-data'); ?></h3>
							<p><?php esc_html_e('Here you may define any custom content that should be included in the head section of your pages. ', 'head-meta-data'); ?></p>
							<p><?php esc_html_e('Tip: you can display the date of the latest post or update via the', 'head-meta-data'); ?> <code>[hmd_post_date]</code> <?php esc_html_e('shortcode.', 'head-meta-data'); ?></p>
							<div class="mm-table-wrap">
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_custom]"><?php esc_html_e('Custom content', 'head-meta-data'); ?></label></th>
										<td>
											<textarea type="textarea" rows="7" cols="55" name="hmd_options[hmd_custom]"><?php echo esc_textarea($hmd_options['hmd_custom']); ?></textarea>
											<div class="mm-item-caption">
												<?php esc_html_e('Optional text/markup to be displayed in the', 'head-meta-data'); ?> 
												<code>&lt;head&gt;</code> <?php esc_html_e('section. Use single quotes for attributes. Leave blank to disable.', 'head-meta-data'); ?>
											</div>
										</td>
									</tr>
								</table>
							</div>
							<input type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'head-meta-data'); ?>" />
						</div>
					</div>
					
					<div id="mm-panel-secondary" class="postbox">
						<h2><?php esc_html_e('Live Preview', 'head-meta-data'); ?></h2>
						<div class="toggle default-hidden">
							<p><?php esc_html_e('Here is a preview of your meta tags and custom content. Note that any special characters will be encoded in the actual page markup.', 'head-meta-data'); ?></p>
							<div class="mm-code-example">
								
								<h3><?php esc_html_e('Meta tags', 'head-meta-data'); ?></h3>
								<pre><?php echo do_shortcode('[head_meta_data]', 'head-meta-data'); ?></pre>
								
								<h3><?php esc_html_e('Custom content', 'head-meta-data'); ?></h3>
								<pre><?php echo do_shortcode('[hmd_custom]', 'head-meta-data'); ?></pre>
								
								<h3><?php esc_html_e('More infos', 'head-meta-data'); ?></h3>
								<ul>
									<li>
										<?php esc_html_e('For more information on document headers:', 'head-meta-data'); ?> 
										<a target="_blank" href="https://m0n.co/c" title="<?php esc_attr_e('XHTML Document Header Resource', 'head-meta-data'); ?>">https://m0n.co/c</a>
									</li>
									<li>
										<?php esc_html_e('And more specifically the section on meta tags:', 'head-meta-data'); ?> 
										<a target="_blank" href="https://m0n.co/d" title="<?php esc_attr_e('XHTML Document Header Resource: meta tags', 'head-meta-data'); ?>">https://m0n.co/d</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					
					<div id="mm-restore-settings" class="postbox">
						<h2><?php esc_html_e('Restore Defaults', 'head-meta-data'); ?></h2>
						<div class="toggle<?php if (!isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<p>
								<input name="hmd_options[default_options]" type="checkbox" value="1" id="mm_restore_defaults" <?php if (isset($hmd_options['default_options'])) { checked('1', $hmd_options['default_options']); } ?> /> 
								<label class="description" for="hmd_options[default_options]"><?php esc_html_e('Restore default options upon plugin deactivation/reactivation.', 'head-meta-data'); ?></label>
							</p>
							<p>
								<small>
									<strong><?php esc_html_e('Tip:', 'head-meta-data'); ?></strong> 
									<?php esc_html_e('leave this option unchecked to remember your settings. Or, to go ahead and restore all default options, check the box, save your settings, and then deactivate/reactivate the plugin.', 'head-meta-data'); ?>
								</small>
							</p>
							<input type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'head-meta-data'); ?>" />
						</div>
					</div>
					
					<div id="mm-panel-current" class="postbox">
						<h2><?php esc_html_e('Show Support', 'head-meta-data'); ?></h2>
						<div class="toggle">
							<?php require_once('support-panel.php'); ?>
						</div>
					</div>
					
				</div>
			</div>
			
			<div id="mm-credit-info">
				<a target="_blank" href="<?php echo $hmd_homeurl; ?>" title="<?php esc_attr_e('Plugin Homepage', 'head-meta-data'); ?>"><?php echo $hmd_plugin; ?></a> <?php esc_html_e('by', 'head-meta-data'); ?> 
				<a target="_blank" href="https://twitter.com/perishable" title="<?php esc_attr_e('Jeff Starr on Twitter', 'head-meta-data'); ?>">Jeff Starr</a> @ 
				<a target="_blank" href="https://monzillamedia.com/" title="<?php esc_attr_e('Obsessive Web Design &amp; Development', 'head-meta-data'); ?>">Monzilla Media</a>
			</div>
			
		</form>
	</div>
	
	<script type="text/javascript">
		jQuery(document).ready(function(){
			// toggle panels
			jQuery('.default-hidden').hide();
			jQuery('#mm-panel-toggle a').click(function(){
				jQuery('.toggle').slideToggle(300);
				return false;
			});
			jQuery('h2').click(function(){
				jQuery(this).next().slideToggle(300);
			});
			jQuery('#mm-panel-primary-link').click(function(){
				jQuery('.toggle').hide();
				jQuery('#mm-panel-primary .toggle').slideToggle(300);
				return true;
			});
			jQuery('#mm-panel-secondary-link').click(function(){
				jQuery('.toggle').hide();
				jQuery('#mm-panel-secondary .toggle').slideToggle(300);
				return true;
			});
			// prevent accidents
			if(!jQuery("#mm_restore_defaults").is(":checked")){
				jQuery('#mm_restore_defaults').click(function(event){
					var r = confirm("<?php esc_html_e('Are you sure you want to restore all default options? (this action cannot be undone)', 'head-meta-data'); ?>");
					if (r == true){  
						jQuery("#mm_restore_defaults").attr('checked', true);
					} else {
						jQuery("#mm_restore_defaults").attr('checked', false);
					}
				});
			}
		});
	</script>

<?php }
