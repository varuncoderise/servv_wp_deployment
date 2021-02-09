=== Head Meta Data ===

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

Adds a custom set of &lt;meta&gt; tags to the &lt;head&gt; section of all posts &amp; pages.



== Description ==

Head Meta Data (formerly "Head Metadata Plus") improves the definition and semantic quality of your web pages by adding a custom set of `<meta>` tags to the `<head>` section of your web pages.

**Example**

	
	<head>
	
		<meta name="abstract" content="Obsessive Web & Graphic Design.">
		<meta name="author" content="Perishable">
		<meta name="classification" content="Website Design">
		<meta name="copyright" content="Copyright Perishable Press - All rights Reserved.">
		<meta name="designer" content="Monzilla Media">
		<meta name="language" content="EN-US">
		<meta name="publisher" content="Perishable Press">
		<meta name="rating" content="General">
		<meta name="resource-type" content="Document">
		<meta name="revisit-after" content="3">
		<meta name="subject" content="WordPress, Web Design, Code & Tutorials">
		<meta name="template" content="Volume Theme">
	
	</head>
	

**Features**

* Plug-n-play functionality
* Born of simplicity, no frills
* Easy to configure from the WP Admin
* Customize each `<meta>` tag with your own info
* Adds set of meta tags to `<head>` section of posts & pages
* Adds custom tags/markup to `<head>` section of posts & pages
* Customize additional content with any text/markup
* Supports Twitter Cards and Open Graph tags via custom content
* Disable any field by leaving it blank
* Auto-generates known information from your site
* Choose HTML or XHTML format for meta tags
* Enable/disable plugin output from the settings page
* Includes live preview of your meta tags and custom content
* Option to reset default settings

This plugin is designed to complete a site's head construct by including some of the more obscure meta tags, such as "author", "copyright", "designer", and so forth. As a matter of practicality, the more widely used tags such as "description" and "keywords" have been omitted, as they are already included via wide variety of plugins (such as "All in One SEO") in a more dynamic way. Even so, adding "description", "keyword", or any other tags is easy from the plugin's settings page. Note: the metadata output via this plugin applies to the entire site.



== Installation ==

**Installation**

1. Upload the plugin to your blog and activate
2. Visit the settings to configure your options

[More info on installing WP plugins](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)


**Upgrades**

To upgrade Head Meta Data, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.

__Note:__ uninstalling the plugin from the WP Plugins screen results in the removal of all settings from the WP database. 


**Restore Default Options**

To restore default plugin options, either uninstall/reinstall the plugin, or visit the plugin settings &gt; Restore Default Options.


**Uninstalling**

Head Meta Data cleans up after itself. All plugin settings will be removed from your database when the plugin is uninstalled via the Plugins screen.



== Upgrade Notice ==

To upgrade Head Meta Data, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.

__Note:__ uninstalling the plugin from the WP Plugins screen results in the removal of all settings from the WP database. 



== Screenshots ==

1. Head Meta Data: Plugin Settings (panels toggle open/closed)

More screenshots available at the [HMD Homepage](https://perishablepress.com/head-metadata-plus/).



== Frequently Asked Questions ==

To ask a question, suggest a feature, or provide feedback, [contact me directly](https://perishablepress.com/contact/).



== Support development of this plugin ==

I develop and maintain this free plugin with love for the WordPress community. To show support, you can [make a cash donation](https://m0n.co/donate), [bitcoin donation](https://m0n.co/bitcoin), or purchase one of my books:  

* [The Tao of WordPress](https://wp-tao.com/)
* [Digging into WordPress](https://digwp.com/)
* [.htaccess made easy](https://htaccessbook.com/)
* [WordPress Themes In Depth](https://wp-tao.com/wordpress-themes-book/)

And/or purchase one of my premium WordPress plugins:

* [BBQ Pro](https://plugin-planet.com/bbq-pro/) - Pro version of Block Bad Queries
* [Blackhole Pro](https://plugin-planet.com/blackhole-pro/) - Pro version of Blackhole for Bad Bots
* [SES Pro](https://plugin-planet.com/ses-pro/) - Super-simple &amp; flexible email signup forms
* [USP Pro](https://plugin-planet.com/usp-pro/) - Pro version of User Submitted Posts

Links, tweets and likes also appreciated. Thanks! :)



== Changelog ==

**20171103**

* Removes extra `manage_options` check for plugin settings
* Tests on WordPress 4.9

**20171022**

* Adds extra `manage_options` capability check to modify settings
* Streamlines Support panel in plugin settings
* Tests on WordPress 4.9

**20170731**

* Updates GPL license blurb
* Adds GPL license text file
* Tests on WordPress 4.9 (alpha)

**20170324**

* Refines display of settings page
* Refines display of settings panels
* Updates show support panel in plugin settings
* Changes translation domain in plugin header to `head-meta-data`
* Adds `[hmd_post_date]` shortcode to display latest post date in custom content
* Replaces global `$wp_version` with `get_bloginfo('version')`
* Generates new default translation template
* Tests on WordPress version 4.8

**20161116**

* Updates plugin author URL
* Updates Twitter URL to https
* Changes stable tag from trunk to latest version
* Refactors `add_hmd_links()` function
* Updates URL for rate this plugin links
* Tests on WordPress version 4.7 (beta)

**20160811**

* Fixed backslash-apostrophe bug via esc_attr()
* Streamlined and optimized plugin settings page
* Replaced `_e()` with `esc_html_e()` or `esc_attr_e()`
* Replaced `__()` with `esc_html__()` or `esc_attr__()`
* Added plugin icons and larger banner image
* Improved translation support
* Deleted unused plugin icon
* General fine-tuning of code
* Tested on WordPress 4.6

**20160331**

* Replaced icon with retina version
* Added screenshot to readme/docs
* Added retina version of banner
* Added overflow: auto to pre tag
* Reorganized and refreshed readme.txt
* Tested on WordPress version 4.5 beta

**20151110**

* Updated heading hierarchy in plugin settings
* Added French translation (Thanks to [Patrice Chassaing](http://cause.i.am.online.fr/))
* Updated translation template file
* Updated minimum version requirement
* Tested on WordPress 4.4 beta

**20150808**

* Tested on WordPress 4.3
* Updated minimum version requirement

**20150507**

* Tested with WP 4.2 + 4.3 (alpha)
* Changed a few "http" links to "https"

**20150315**

* Tested with latest version of WP (4.1)
* Increased minimum version to WP 3.8
* Added $hmd_wp_vers for version check
* Streamline/fine-tune plugin code
* Added Text Domain and Domain Path to file header
* Added .pot template for localization
* Removed deprecated screen_icon()

**20140923**

* Tested with latest version of WordPress (4.0)
* Increased minimum WP version requirement to 3.7
* Added conditional check on min-version function

**20140123**

* Tested with latest WordPress (3.8)
* Added trailing slash to load_plugin_textdomain()

**20131107**

* Added uninstall.php file
* Added "rate this plugin" links
* Added support for i18n

**20131104**

* Added line to prevent direct script access
* Changed default value for copyright meta
* Improved support for custom content
* Fixed bug reported [here](http://wordpress.org/support/topic/strange-custom-tag)
* Replaced wp_kses_post with wp_kses
* Added "href", "property", "title", "rel", "type", "charset", "media", "rev" to list of allowed attributes
* Removed closing "?>" tag in head-meta-data.php
* Tested with latest version of WordPress (3.7)

**20130705**

* General code check n clean, plus Overview and Updates admin panels now toggled open by default.

**20130103**

* Added margins to submit buttons (required in WP 3.5)

**20121102**

* Rebuilt plugin, changed name from "Head MetaData Plus" to "Head Meta Data".

**20060502**

* Initial release.


