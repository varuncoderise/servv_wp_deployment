<?php

require_once(plugin_dir_path( __FILE__ ) . 'galleries.php');
require_once(plugin_dir_path( __FILE__ ) . 'portfolios.php');
require_once(plugin_dir_path( __FILE__ ) . 'quickfinders.php');
require_once(plugin_dir_path( __FILE__ ) . 'teams.php');
require_once(plugin_dir_path( __FILE__ ) . 'clients.php');
require_once(plugin_dir_path( __FILE__ ) . 'testimonials.php');
require_once(plugin_dir_path( __FILE__ ) . 'news.php');
require_once(plugin_dir_path( __FILE__ ) . 'titles.php');
require_once(plugin_dir_path( __FILE__ ) . 'footers.php');
require_once(plugin_dir_path( __FILE__ ) . 'slideshows.php');

function thegem_rewrite_flush() {
	thegem_news_post_type_init();
	thegem_portfolio_item_post_type_init();

	thegem_rewrite_rules_flush();
}

function thegem_rewrite_rules_flush() {
	// force recreate rewrite rules, flush_rewrite_rules works unstable
	delete_option( 'rewrite_rules' );
}

register_activation_hook($thegem_plugin_file, 'thegem_rewrite_flush' );
register_deactivation_hook($thegem_plugin_file, 'thegem_rewrite_rules_flush' );

add_action( 'after_switch_theme', 'thegem_rewrite_rules_flush' );

// Post gallery meta box
function thegem_post_item_meta_box_gallery($post) {
	wp_nonce_field(__FILE__, '_thegem_post_item_meta_box_gallery_nonce');
	if (metadata_exists('post', $post->ID, 'thegem_post_item_gallery_images')) {
		$thegem_gallery_images_ids = get_post_meta($post->ID, 'thegem_post_item_gallery_images', true);
	} else {
		$attachments_ids = get_posts('post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids');
		$thegem_gallery_images_ids = implode(',', $attachments_ids);
	}
	$attachments_ids = array_filter(explode(',', $thegem_gallery_images_ids));

	echo '<div id="gallery_manager" class="gallery_settings_box">';
	echo '<input type="hidden" id="thegem_gallery_images" name="thegem_post_item_gallery_images" value="' . esc_attr($thegem_gallery_images_ids) . '" />';

	echo '<ul class="gallery-images">';
	if ($attachments_ids) {
		foreach ($attachments_ids as $attachment_id) {
			$attachment = wp_get_attachment_image($attachment_id);
			if (empty($attachment)) {
				continue;
			}
			echo '<li class="image" data-attachment_id="' . esc_attr($attachment_id) . '"><a target="_blank" href="' . get_edit_post_link($attachment_id) . '" class="edit">' . $attachment . '</a><a href="javascript:void(0);" class="remove">x</a></li>';
		}
	}
	echo '</ul><br class="clear" />';

	echo '<a id="upload_button" href="javascript:void(0);">' . __('Add gallery images', 'thegem') . '</a>';

	echo '</div>';
}

add_action('add_meta_boxes', function () {
	$post_types = thegem_get_available_po_custom_post_types();
	add_meta_box('thegem_post_item_gallery', 'Post Gallery', 'thegem_post_item_meta_box_gallery', 'post', 'side');
	add_meta_box('thegem_post_item_gallery', 'Post Gallery', 'thegem_post_item_meta_box_gallery', 'page', 'side');
	foreach($post_types as $post_type) {
		if(!thegem_get_option($post_type.'_post_gallery_disabled')) {
			add_meta_box('thegem_post_item_gallery', 'Post Gallery', 'thegem_post_item_meta_box_gallery', $post_type, 'side');
		}
	}
});

add_action('save_post', function ($post_id) {
	if (isset($_POST['thegem_post_item_gallery_images'], $_POST['_thegem_post_item_meta_box_gallery_nonce']) && wp_verify_nonce($_POST['_thegem_post_item_meta_box_gallery_nonce'], __FILE__)) {
		update_post_meta($post_id, 'thegem_post_item_gallery_images', sanitize_text_field($_POST['thegem_post_item_gallery_images']));
	}
});