<?php

$PORTFOLIO_TYPE_OPTIONS = array('self-link' => __('Portfolio Page', 'thegem'), 'inner-link' => __('Internal Link', 'thegem'), 'outer-link' => __('External Link', 'thegem'), 'full-image' => __('Full-Size Image', 'thegem'), 'youtube' => __('YouTube Video', 'thegem'), 'vimeo' => __('Vimeo Video', 'thegem'), 'self_video' => __('Self-Hosted Video', 'thegem'));

function thegem_portfolio_item_post_type_init() {
	$labels = array(
		'name'               => __('Portfolio Items', 'thegem'),
		'singular_name'      => __('Portfolio Item', 'thegem'),
		'menu_name'          => __('Portfolios', 'thegem'),
		'name_admin_bar'     => __('Portfolio Item', 'thegem'),
		'add_new'            => __('Add New', 'thegem'),
		'add_new_item'       => __('Add New Portfolio Item', 'thegem'),
		'new_item'           => __('New Portfolio Item', 'thegem'),
		'edit_item'          => __('Edit Portfolio Item', 'thegem'),
		'view_item'          => __('View Portfolio Item', 'thegem'),
		'all_items'          => __('All Portfolio Items', 'thegem'),
		'search_items'       => __('Search Portfolio Items', 'thegem'),
		'not_found'          => __('No portfolio items found.', 'thegem'),
		'not_found_in_trash' => __('No portfolio items found in Trash.', 'thegem')
	);

	$args = array(
		'labels'               => $labels,
		'public'               => true,
		'publicly_queryable'   => true,
		'show_ui'              => true,
		'query_var'            => true,
		'hierarchical'         => false,
		'register_meta_box_cb' => 'thegem_portfolio_items_register_meta_box',
		'taxonomies'           => array('thegem_portfolios'),
		'rewrite'              => array('slug' => apply_filters('thegem_portfolio_items_rewrite_slug', 'pf'), 'with_front' => false),
		'capability_type'      => 'page',
		'supports'             => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'comments', 'custom-fields'),
		'menu_position'        => 32,
	);

	register_post_type('thegem_pf_item', $args);

	$labels = array(
		'name'                       => __('Portfolio Categories', 'thegem'),
		'singular_name'              => __('Portfolio Category', 'thegem'),
		'search_items'               => __('Search Portfolio Categories', 'thegem'),
		'popular_items'              => __('Popular Portfolio Categories', 'thegem'),
		'all_items'                  => __('All Portfolio Categories', 'thegem'),
		'edit_item'                  => __('Edit Portfolio Category', 'thegem'),
		'update_item'                => __('Update Portfolio Category', 'thegem'),
		'add_new_item'               => __('Add New Portfolio Category', 'thegem'),
		'new_item_name'              => __('New Portfolio Category Name', 'thegem'),
		'separate_items_with_commas' => __('Separate Portfolio Categories with commas', 'thegem'),
		'add_or_remove_items'        => __('Add or remove Portfolio Categories', 'thegem'),
		'choose_from_most_used'      => __('Choose from the most used Portfolio Categories', 'thegem'),
		'not_found'                  => __('No portfolio categories found.', 'thegem'),
		'menu_name'                  => __('Portfolio Categories', 'thegem'),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => false,
		'public'                => false,
		'rewrite'               => false,
	);

	register_taxonomy('thegem_portfolios', 'thegem_pf_item', $args);
}
add_action('init', 'thegem_portfolio_item_post_type_init', 5);

function thegem_options_portfolio_items_rewrite_slug($value) {
	if(sanitize_title(thegem_get_option('portfolio_rewrite_slug'))) {
		$value = sanitize_title(thegem_get_option('portfolio_rewrite_slug'));
	}
	return $value;
}
add_filter('thegem_portfolio_items_rewrite_slug', 'thegem_options_portfolio_items_rewrite_slug');

/* PORTFOLIO ITEM POST META BOX */
function thegem_portfolio_items_register_meta_box($post) {
	global $thegem_use_old_page_options;

	if ($thegem_use_old_page_options) {
		add_meta_box('thegem_portfolio_item_settings', __('Portfolio Item Settings', 'thegem'), 'thegem_portfolio_item_settings_box', 'thegem_pf_item', 'normal', 'high');
	}

	// Portfolio gallery add meta box
	add_meta_box('thegem_portfolio_item_settings_gallery', __('Project Gallery', 'thegem'), 'thegem_portfolio_item_settings_gallery_box', 'thegem_pf_item', 'side', 'low');
}

function print_portfolio_more_type($item_type, $types_index) {
	global $PORTFOLIO_TYPE_OPTIONS;
?>
	<div class="portfolio_item_element_<?php echo $types_index; ?>" id="portfolio_item_type_<?php echo $types_index; ?>_wrapper">
		<label for="portfolio_item_type_<?php echo $types_index; ?>"><?php _e('Type of portfolio item', 'thegem'); ?>:</label><br />
		<?php thegem_print_select_input($PORTFOLIO_TYPE_OPTIONS, $item_type['type'], 'thegem_portfolio_item_data[types]['.$types_index.'][type]', 'portfolio_item_type_'.$types_index); ?>
	</div>

	<div class="portfolio_item_element_<?php echo $types_index; ?>" id="portfolio_item_link_target_<?php echo $types_index; ?>_wrapper">
		<label for="portfolio_item_link_target_<?php echo $types_index; ?>"><?php _e('Link target', 'thegem'); ?>:</label><br />
		<?php thegem_print_select_input(array('_self' => 'Self', '_blank' => 'Blank'), $item_type['link_target'], 'thegem_portfolio_item_data[types]['.$types_index.'][link_target]', 'portfolio_item_link_target_'.$types_index); ?>
	</div>

	<div class="portfolio_item_element_<?php echo $types_index; ?>" id="portfolio_item_link_<?php echo $types_index; ?>_wrapper">
		<label for="portfolio_item_link_<?php echo $types_index; ?>"><?php _e('Link to another page or video ID (for YouTube or Vimeo):', 'thegem'); ?>:</label><br />
		<input name="thegem_portfolio_item_data[types][<?php echo esc_attr($types_index); ?>][link]" type="text" id="portfolio_item_link_<?php echo esc_attr($types_index); ?>" value="<?php echo esc_attr($item_type['link']); ?>" size="60" />
	</div>

	<div class="portfolio_item_element_<?php echo $types_index; ?>" id="portfolio_item_remove_button_<?php echo $types_index; ?>_wrapper">
		<a href="#" onclick="return portfolio_remove_item_type(this);">Remove type</a>
	</div>
	<div class="portfolio_item_element_<?php echo $types_index; ?>"><br /></div>
<?php
}

function thegem_portfolio_item_settings_box($post) {
	global $PORTFOLIO_TYPE_OPTIONS;

	wp_nonce_field('thegem_portfolio_item_settings_box', 'thegem_portfolio_item_settings_box_nonce');
	$portfolio_item_data = thegem_get_sanitize_pf_item_data($post->ID);
	$default_portfolio_type = array('link' => '', 'link_target' => '_self', 'type' => 'self-link');
	if (empty($portfolio_item_data['types']))
		$portfolio_item_data['types'] = array(0 => $default_portfolio_type);
	$types_index = 0;
?>
<p class="meta-options">
	<input name="thegem_portfolio_item_data[fullwidth]" type="checkbox" id="portfolio_item_fullwidth" value="1" <?php checked($portfolio_item_data['fullwidth'], 1); ?> />
	<label for="portfolio_item_fullwidth"><?php _e('100% page layout', 'thegem'); ?></label>
	<br /><br />

	<input name="thegem_portfolio_item_data[highlight]" type="checkbox" id="portfolio_item_highlight" value="1" <?php checked($portfolio_item_data['highlight'], 1); ?> />
	<label for="portfolio_item_highlight"><?php _e('Show as Highlight?', 'thegem'); ?></label>
	<br /><br />

	<label for="portfolio_item_highlight_type"><?php _e('Highlight Type', 'thegem'); ?>:</label><br />
	<?php thegem_print_select_input(array('squared' => 'Squared', 'horizontal' => 'Horizontal', 'vertical' => 'Vertical'), $portfolio_item_data['highlight_type'], 'thegem_portfolio_item_data[highlight_type]', 'portfolio_item_data_highlight_type'); ?>
	<br /><br />

	<label for="portfolio_item_overview_title"><?php _e('Overview title', 'thegem'); ?>:</label><br />
	<input name="thegem_portfolio_item_data[overview_title]" type="text" id="portfolio_item_overview_title" value="<?php echo esc_attr($portfolio_item_data['overview_title']); ?>" size="60" />
	<br /><br />

	<label for="portfolio_item_project_link"><?php _e('Project Preview Button Link', 'thegem'); ?>:</label><br />
	<input name="thegem_portfolio_item_data[project_link]" type="text" id="portfolio_item_project_link" value="<?php echo esc_attr($portfolio_item_data['project_link']); ?>" size="60" />
	<br /><br />

	<label for="portfolio_item_project_text"><?php _e('Project Preview Button Text', 'thegem'); ?>:</label><br />
	<input name="thegem_portfolio_item_data[project_text]" type="text" id="portfolio_item_project_text" value="<?php echo esc_attr($portfolio_item_data['project_text']); ?>" size="60" />
	<br /><br />

	<label for="portfolio_item_back_url"><?php _e('Back to overview URL', 'thegem'); ?>:</label><br />
	<input name="thegem_portfolio_item_data[back_url]" type="text" id="portfolio_item_project_text" value="<?php echo esc_attr($portfolio_item_data['back_url']); ?>" size="60" />
	<br /><br />

	<div id="add_portfolio_item_type_template" style="display: none;">
		<?php print_portfolio_more_type($default_portfolio_type, '%INDEX%'); ?>
	</div>

	<div class="portfolio-types">
		<?php
			foreach($portfolio_item_data['types'] as $item_type) {
				print_portfolio_more_type($item_type, $types_index);
				$types_index++;
			}
		?>
	</div>
	<a href="#" onclick="return portfolio_add_item_type(this);">Add one more type</a>
	<script type='text/javascript'>
		init_portfolio_settings();
	</script>
</p>
<?php
}

// Portfolio gallery init meta box
function thegem_portfolio_item_settings_gallery_box($post) {
	wp_nonce_field('thegem_portfolio_item_settings_gallery_box', 'thegem_portfolio_item_settings_gallery_box_nonce');
	if(metadata_exists('post', $post->ID, 'thegem_portfolio_item_gallery_images')) {
		$thegem_gallery_images_ids = get_post_meta($post->ID, 'thegem_portfolio_item_gallery_images', true);
	} else {
		$attachments_ids = get_posts('post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids');
		$thegem_gallery_images_ids = implode(',', $attachments_ids);
	}
	$attachments_ids = array_filter(explode(',', $thegem_gallery_images_ids));

	echo '<div id="gallery_manager" class="gallery_settings_box">';
	echo '<input type="hidden" id="thegem_gallery_images" name="thegem_portfolio_item_gallery_images" value="' . esc_attr($thegem_gallery_images_ids) . '" />';

	echo '<ul class="gallery-images">';
	if($attachments_ids) {
		foreach($attachments_ids as $attachment_id) {
			$attachment = wp_get_attachment_image( $attachment_id );
			if ( empty( $attachment ) ) {
				continue;
			}
			echo '<li class="image" data-attachment_id="' . esc_attr($attachment_id) . '"><a target="_blank" href="' . get_edit_post_link($attachment_id) . '" class="edit">' .wp_get_attachment_image($attachment_id, 'thumbnail') . '</a><a href="javascript:void(0);" class="remove">x</a></li>';
		}
	}
	echo '</ul><br class="clear" />';

	echo '<a id="upload_button" href="javascript:void(0);">' . __('Add gallery images','thegem') . '</a>';

	echo '</div>';
}

function thegem_portfolio_item_save_meta_box_data($post_id) {
	if(!isset($_POST['thegem_portfolio_item_settings_box_nonce'])) {
		return;
	}
	if(!wp_verify_nonce($_POST['thegem_portfolio_item_settings_box_nonce'], 'thegem_portfolio_item_settings_box')) {
		return;
	}
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if(isset($_POST['post_type']) && 'thegem_pf_item' == $_POST['post_type']) {
		if(!current_user_can('edit_page', $post_id)) {
			return;
		}
	} else {
		if(!current_user_can('edit_post', $post_id)) {
			return;
		}
	}

	if(!isset($_POST['thegem_portfolio_item_data']) || !is_array($_POST['thegem_portfolio_item_data'])) {
		return;
	}

	$portfolio_item_data = thegem_get_sanitize_pf_item_data(0, $_POST['thegem_portfolio_item_data']);
	update_post_meta($post_id, 'thegem_portfolio_item_data', $portfolio_item_data);
	$portfolio_item_elements = thegem_get_sanitize_pf_item_elements_data(0, $_POST['thegem_pf_item_page_elements_data']);
	update_post_meta($post_id, 'thegem_pf_item_page_elements_data', $portfolio_item_elements);
}

if (!empty($thegem_use_old_page_options)) {
	add_action('save_post', 'thegem_portfolio_item_save_meta_box_data');
}

// Portfolio gallery save data
function thegem_portfolio_item_save_gallery_data($post_id) {
	if(!isset($_POST['thegem_portfolio_item_settings_gallery_box_nonce'])) {
		return;
	}
	if(!wp_verify_nonce($_POST['thegem_portfolio_item_settings_gallery_box_nonce'], 'thegem_portfolio_item_settings_gallery_box')) {
		return;
	}
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if(isset($_POST['post_type']) && 'thegem_pf_item' == $_POST['post_type']) {
		if(!current_user_can('edit_page', $post_id)) {
			return;
		}
	} else {
		if(!current_user_can('edit_post', $post_id)) {
			return;
		}
	}

	if(!isset($_POST['thegem_portfolio_item_gallery_images'])) {
		return;
	}

	update_post_meta($post_id, 'thegem_portfolio_item_gallery_images', $_POST['thegem_portfolio_item_gallery_images']);
}
add_action('save_post', 'thegem_portfolio_item_save_gallery_data');

function thegem_get_sanitize_pf_item_data($post_id = 0, $item_data = array()) {
	$output_data = thegem_get_sanitize_admin_pf_item_data($post_id, $item_data);

	if ($output_data['portfolio_layout_settings'] == 'default') {
		$output_data['portfolio_layout_source'] = thegem_get_option('portfolio_layout_source');
		$output_data['portfolio_builder_template'] = thegem_get_option('portfolio_builder_template');
	}

	return $output_data;
}

function thegem_get_sanitize_admin_pf_item_data($post_id = 0, $item_data = array()) {
	global $PORTFOLIO_TYPE_OPTIONS;

	$portfolio_item_data = array(
		'portfolio_layout_settings' => 'default',
		'portfolio_layout_source' => thegem_get_option('portfolio_layout_source'),
		'portfolio_builder_template' => thegem_get_option('portfolio_builder_template'),
		'fullwidth' => 0,
		'highlight' => 0,
		'highlight_type' => '',
		'overview_title' => '',
		'project_link' => '',
		'project_text' => '',
		'back_url' => '',
		'types' => array(),
		'grid_appearance_type' => 'featured_image',
		'grid_appearance_featured_image' => '',
		'grid_appearance_gif' => '',
		'grid_appearance_gif_start' => 'autoplay',
		'grid_appearance_gif_poster' => '',
		'grid_appearance_gif_preload' => '',
		'grid_appearance_video' => '',
		'grid_appearance_video_type' => '',
		'grid_appearance_video_aspect_ratio' => '',
		'grid_appearance_video_play_on_mobile' => '',
		'grid_appearance_video_overlay' => '',
		'grid_appearance_video_poster' => '',
		'grid_appearance_video_start' => 'autoplay',
		'grid_appearance_gallery_autoscroll' => '1',
		'grid_appearance_gallery_autoscroll_speed' => '3000',
		'grid_appearance_image_behavior' => 'link_to_page',
		'grid_appearance_gif_behavior' => 'link_to_page',
		'grid_appearance_video_behavior' => 'link_to_page',
		'grid_appearance_gallery_behavior' => 'link_to_page',
		'grid_appearance_behavior_target' => '_self',
		'grid_appearance_behavior_custom_link' => '',
		'grid_appearance_behavior_custom_link_target' => '_self',
		'grid_appearance_lightbox_image' => '',
	);
	if(is_array($item_data) && !empty($item_data)) {
		$portfolio_item_data = array_merge($portfolio_item_data, $item_data);
	} elseif($post_id != 0 && function_exists('thegem_get_post_data')) {
		$portfolio_item_data = thegem_get_post_data($portfolio_item_data, 'portfolio_item', $post_id);
	}

	$portfolio_item_data['portfolio_layout_settings'] = thegem_check_array_value(array('default', 'custom'), $portfolio_item_data['portfolio_layout_settings'], 'default');
	$portfolio_item_data['portfolio_layout_source'] = thegem_check_array_value(array('default', 'builder'), $portfolio_item_data['portfolio_layout_source'], 'default');
	$portfolio_item_data['portfolio_builder_template'] = strval(intval($portfolio_item_data['portfolio_builder_template']) >= 0 ? intval($portfolio_item_data['portfolio_builder_template']) : 0);
	$portfolio_item_data['fullwidth'] = $portfolio_item_data['fullwidth'] ? 1 : 0;
	$portfolio_item_data['highlight'] = $portfolio_item_data['highlight'] ? 1 : 0;
	$portfolio_item_data['highlight_type'] = thegem_check_array_value(array('squared', 'horizontal', 'vertical'), $portfolio_item_data['highlight_type'], 'squared');
	$portfolio_item_data['overview_title'] = sanitize_text_field($portfolio_item_data['overview_title']);
	$portfolio_item_data['project_link'] = esc_url($portfolio_item_data['project_link']);
	$portfolio_item_data['project_text'] = sanitize_text_field($portfolio_item_data['project_text']);
	$portfolio_item_data['back_url'] = esc_url($portfolio_item_data['back_url']);
	if (isset($portfolio_item_data['types']['%INDEX%']))
		unset($portfolio_item_data['types']['%INDEX%']);

	$portfolio_item_data_types = array();
	foreach ($portfolio_item_data['types'] as $k => $v) {
		$v['link_target'] = thegem_check_array_value(array('_blank', '_self'), $v['link_target'], '_self');
		$portfolio_type_options = array_keys($PORTFOLIO_TYPE_OPTIONS);
		$v['type'] = thegem_check_array_value($portfolio_type_options, $v['type'], 'self-link');
		if (!in_array($v['type'], array('youtube', 'vimeo'))) {
			$v['link'] = esc_url($v['link']);
		}

		$portfolio_item_data_types[] = $v;
	}
	$portfolio_item_data['types'] = array_slice($portfolio_item_data_types, 0, 4);

	$portfolio_item_data['grid_appearance_type'] = thegem_check_array_value(array('featured_image', 'animated_gif', 'video', 'gallery'), $portfolio_item_data['grid_appearance_type'], 'featured_image');
	//$portfolio_item_data['grid_appearance_featured_image'] = $portfolio_item_data['grid_appearance_featured_image'];
	//$portfolio_item_data['grid_appearance_gif'] = $portfolio_item_data['grid_appearance_gif'];
	$portfolio_item_data['grid_appearance_gif_start'] = thegem_check_array_value(array('autoplay', 'play_on_hover'), $portfolio_item_data['grid_appearance_gif_start'], 'autoplay');
	$portfolio_item_data['grid_appearance_gif_poster'] = esc_url($portfolio_item_data['grid_appearance_gif_poster']);
	$portfolio_item_data['grid_appearance_gif_preload'] = $portfolio_item_data['grid_appearance_gif_preload'] ? 1 : 0;
	$portfolio_item_data['grid_appearance_video'] = esc_html($portfolio_item_data['grid_appearance_video']);
	$portfolio_item_data['grid_appearance_video_type'] = thegem_check_array_value(array('youtube', 'vimeo', 'self'), $portfolio_item_data['grid_appearance_video_type'], 'youtube');
	$portfolio_item_data['grid_appearance_video_aspect_ratio'] = sanitize_text_field($portfolio_item_data['grid_appearance_video_aspect_ratio']);
	$portfolio_item_data['grid_appearance_video_play_on_mobile'] = $portfolio_item_data['grid_appearance_video_play_on_mobile'] ? 1 : 0;
	$portfolio_item_data['grid_appearance_video_overlay'] = sanitize_text_field($portfolio_item_data['grid_appearance_video_overlay']);
	$portfolio_item_data['grid_appearance_video_poster'] = esc_url($portfolio_item_data['grid_appearance_video_poster']);
	$portfolio_item_data['grid_appearance_video_start'] = thegem_check_array_value(array('autoplay', 'play_on_hover', 'open_in_lightbox'), $portfolio_item_data['grid_appearance_video_start'], 'autoplay');
	$portfolio_item_data['grid_appearance_gallery_autoscroll'] = $portfolio_item_data['grid_appearance_gallery_autoscroll'] ? 1 : 0;
	$portfolio_item_data['grid_appearance_gallery_autoscroll_speed'] = strval(intval($portfolio_item_data['grid_appearance_gallery_autoscroll_speed']) >= 0 ? intval($portfolio_item_data['grid_appearance_gallery_autoscroll_speed']) : 0);
	$portfolio_item_data['grid_appearance_image_behavior'] = thegem_check_array_value(array('link_to_page', 'lightbox', 'multiple_choice', 'custom_link', 'click_disabled'), $portfolio_item_data['grid_appearance_image_behavior'], 'link_to_page');
	$portfolio_item_data['grid_appearance_gif_behavior'] = thegem_check_array_value(array('link_to_page', 'lightbox', 'custom_link', 'click_disabled'), $portfolio_item_data['grid_appearance_gif_behavior'], 'link_to_page');
	$portfolio_item_data['grid_appearance_video_behavior'] = thegem_check_array_value(array('link_to_page', 'lightbox', 'custom_link', 'click_disabled'), $portfolio_item_data['grid_appearance_video_behavior'], 'link_to_page');
	$portfolio_item_data['grid_appearance_gallery_behavior'] = thegem_check_array_value(array('link_to_page', 'lightbox', 'multiple_choice', 'custom_link', 'click_disabled'), $portfolio_item_data['grid_appearance_gallery_behavior'], 'link_to_page');
	$portfolio_item_data['grid_appearance_behavior_target'] = thegem_check_array_value(array('_self', '_blank'), $portfolio_item_data['grid_appearance_behavior_target'], '_self');
	$portfolio_item_data['grid_appearance_behavior_custom_link'] = esc_url($portfolio_item_data['grid_appearance_behavior_custom_link']);
	$portfolio_item_data['grid_appearance_behavior_custom_link_target'] = thegem_check_array_value(array('_self', '_blank'), $portfolio_item_data['grid_appearance_behavior_custom_link_target'], '_self');

	return $portfolio_item_data;
}

function thegem_get_sanitize_pf_item_elements_data($post_id = 0, $item_data = array()) {
	$post_item_data = apply_filters('thegem_pf_item_page_elements_defaults', array(
		'portfolio_page_elements' => 'default',
		'portfolio_hide_top_navigation' => thegem_get_option('portfolio_hide_top_navigation'),
		'portfolio_hide_date' => thegem_get_option('portfolio_hide_date'),
		'portfolio_hide_sets' => thegem_get_option('portfolio_hide_sets'),
		'portfolio_hide_likes' => thegem_get_option('portfolio_hide_likes'),
		'portfolio_hide_socials' => thegem_get_option('portfolio_hide_socials'),
		'portfolio_hide_bottom_navigation' => thegem_get_option('portfolio_hide_bottom_navigation'),
	), $post_id, $item_data);

	if(is_array($item_data) && !empty($item_data)) {
		$post_item_data = array_merge($post_item_data, $item_data);
	} elseif($post_id != 0 && function_exists('thegem_get_post_data')) {
		$post_item_data = thegem_get_post_data($post_item_data, 'pf_item_page_elements', $post_id);
	}

	$post_item_data['portfolio_page_elements'] = thegem_check_array_value(array('default', 'custom'), $post_item_data['portfolio_page_elements'], 'default');
	$post_item_data['portfolio_hide_top_navigation'] = $post_item_data['portfolio_hide_top_navigation'] ? 1 : 0;
	$post_item_data['portfolio_hide_date'] = $post_item_data['portfolio_hide_date'] ? 1 : 0;
	$post_item_data['portfolio_hide_sets'] = $post_item_data['portfolio_hide_sets'] ? 1 : 0;
	$post_item_data['portfolio_hide_likes'] = $post_item_data['portfolio_hide_likes'] ? 1 : 0;
	$post_item_data['portfolio_hide_socials'] = $post_item_data['portfolio_hide_socials'] ? 1 : 0;
	$post_item_data['portfolio_hide_bottom_navigation'] = $post_item_data['portfolio_hide_bottom_navigation'] ? 1 : 0;

	return $post_item_data;
}

function thegem_get_output_pf_item_elements_data($post_id) {
	$output_data = thegem_get_sanitize_pf_item_elements_data($post_id);
	if($output_data['portfolio_page_elements'] == 'default') {
		foreach($output_data as $key => $value) {
			$output_data[$key] = thegem_get_option($key);
		}
	}
	return $output_data;
}

add_action('thegem_portfolios_edit_form','thegem_portfolios_form');
add_action('thegem_portfolios_add_form','thegem_portfolios_form');

function thegem_portfolios_form() {
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
}

function thegem_portfolios_edit_form_fields() {
?>
	<tr class="form-field">
		<th valign="top" scope="row"><label for="portfoliosets_icon_pack"><?php _e('Icon Pack', 'thegem'); ?></label></th>
		<td>
			<?php thegem_print_select_input(thegem_icon_packs_select_array(), esc_attr(get_option('portfoliosets_' . $_REQUEST['tag_ID'] . '_icon_pack')), 'portfoliosets_icon_pack', 'portfoliosets_icon_pack'); ?><br />
		</td>
	</tr>
	<tr class="form-field">
		<th valign="top" scope="row"><label for="portfoliosets_icon"><?php _e('Icon', 'thegem'); ?></label></th>
		<td>
			<input class= "icon" type="text" id="portfoliosets_icon" name="portfoliosets_icon" value="<?php echo esc_attr(get_option('portfoliosets_' . $_REQUEST['tag_ID'] . '_icon')); ?>"/><br />
			<span class="description"><?php echo thegem_icon_packs_infos(); ?></span>
		</td>
	</tr>
	<tr class="form-field">
		<th valign="top" scope="row"><label for="portfoliosets_order"><?php _e('Order', 'thegem'); ?></label></th>
		<td>
			<input type="text" id="portfoliosets_order" name="portfoliosets_order" value="<?php echo esc_attr(get_option('portfoliosets_' . $_REQUEST['tag_ID'] . '_order', 0)); ?>"/><br />
		</td>
	</tr>
<?php
}
add_action('thegem_portfolios_edit_form_fields','thegem_portfolios_edit_form_fields');

function thegem_portfolios_add_form_fields() {
?>
	<div class="form-field">
		<label for="portfoliosets_icon"><?php _e('Icon Pack', 'thegem'); ?></label>
		<?php thegem_print_select_input(thegem_icon_packs_select_array(), 'elegant', 'portfoliosets_icon_pack', 'portfoliosets_icon_pack'); ?><br />
	</div>
	<div class="form-field">
		<label for="portfoliosets_icon"><?php _e('Icon', 'thegem'); ?></label>
		<input class= "icon" type="text" id="portfoliosets_icon" name="portfoliosets_icon"/><br/>
		<?php echo thegem_icon_packs_infos(); ?>
	</div>
	<div class="form-field">
		<label for="portfoliosets_order"><?php _e('Order', 'thegem'); ?></label>
		<input class= "icon" type="text" id="portfoliosets_order" name="portfoliosets_order" value="0"/><br/>
	</div>
<?php
}
add_action('thegem_portfolios_add_form_fields','thegem_portfolios_add_form_fields');

function thegem_portfolios_create($id) {
	if(isset($_REQUEST['portfoliosets_icon_pack'])) {
		update_option( 'portfoliosets_' . $id . '_icon_pack', $_REQUEST['portfoliosets_icon_pack'] );
	}
	if(isset($_REQUEST['portfoliosets_icon'])) {
		update_option( 'portfoliosets_' . $id . '_icon', $_REQUEST['portfoliosets_icon'] );
	}
	$order = isset($_REQUEST['portfoliosets_order']) ? intval($_REQUEST['portfoliosets_order']) : 0;
	update_option( 'portfoliosets_' . $id . '_order', $order );
}
add_action('create_thegem_portfolios','thegem_portfolios_create');

function thegem_portfolios_update($id) {
	if(isset($_REQUEST['portfoliosets_icon_pack'])) {
		update_option( 'portfoliosets_' . $id . '_icon_pack', $_REQUEST['portfoliosets_icon_pack'] );
	}
	if(isset($_REQUEST['portfoliosets_icon'])) {
		update_option( 'portfoliosets_' . $id . '_icon', $_REQUEST['portfoliosets_icon'] );
	}
	$order = isset($_REQUEST['portfoliosets_order']) ? intval($_REQUEST['portfoliosets_order']) : 0;
	update_option( 'portfoliosets_' . $id . '_order', $order );
}
add_action('edit_thegem_portfolios','thegem_portfolios_update');

function thegem_portfolios_delete($id) {
	delete_option( 'portfoliosets_' . $id . '_icon_pack' );
	delete_option( 'portfoliosets_' . $id . '_icon' );
	delete_option( 'portfoliosets_' . $id . '_order' );
}
add_action('delete_thegem_portfolios','thegem_portfolios_delete');

function portfolio_load_more_callback() {
	$response = array();
	/*
	if ( !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'portfolio_ajax-nonce' ) ) {
		$response = array('status' => 'error', 'message' => 'Error verify nonce');
		$response = json_encode($response);
		header( "Content-Type: application/json" );
		echo $response;
		exit;
	}
	*/
	$data = isset($_POST['data']) ? $_POST['data'] : array();
	$data['is_ajax'] = true;
	$response = array('status' => 'success');
	ob_start();
	thegem_portfolio($data);
	$response['html'] = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	$response = json_encode($response);
	header( "Content-Type: application/json" );
	echo $response;
	exit;
}
add_action('wp_ajax_portfolio_load_more', 'portfolio_load_more_callback');
add_action('wp_ajax_nopriv_portfolio_load_more', 'portfolio_load_more_callback');

function thegem_portfolio_video_background($video_type, $video, $color = '', $poster='', $autoplay = '', $play_on_mobile = '') {
	$output = '';
	$uniqid = uniqid('thegem-video-frame-').rand(1,9999);
	$video_type = thegem_check_array_value(array('', 'youtube', 'vimeo', 'self'), $video_type, '');

	if ( $video_type && $video ) {
		$overlay_css = '';
		if ( $video_type == 'youtube' || $video_type == 'vimeo' ) {
			$video_block = '<div id="' . $uniqid . '" class="video-embed"></div>';
			if ( $video_type == 'youtube' ) {
				if ( $autoplay && !$play_on_mobile ) {
					$link = '//www.youtube.com/embed/' . $video . '?playlist=' . $video . '&autoplay=1&mute=1&controls=0&playsinline=1&enablejsapi=1&loop=1&fs=0&showinfo=0&autohide=1&iv_load_policy=3&rel=0&disablekb=1&wmode=transparent';
					$video_block = '<iframe id="' . $uniqid . '" class="gem-video-background-iframe" src="' . esc_url( $link ) . '" frameborder="0" muted="muted"></iframe>';
				}
			}
			if ( $video_type == 'vimeo' ) {
				if ( $autoplay && !$play_on_mobile ) {
					$link = '//player.vimeo.com/video/' . $video . '?autoplay=1&muted=1&controls=0&loop=1&title=0&badge=0&byline=0&autopause=0';
					$video_block = '<iframe id="' . $uniqid . '" class="gem-video-background-iframe" src="' . esc_url( $link ) . '" frameborder="0" muted="muted"></iframe>';
				}
			}
		} else {
			$video_block = '<video id="' . $uniqid . '" ' . ( $autoplay ? 'autoplay' : '' ) . ' muted playsinline loop src="' . $video . '" ' . ( $poster ? ' poster="' . esc_url( $poster ) . '"' : '' ) . '></video>';
		}

		$output .= $video_block;

		if ( $color ) {
			$overlay_css .= 'background-color: ' . $color . ';';
		}

		if (!empty($overlay_css) || $autoplay) {
			$output .= '<div class="gem-video-portfolio-overlay" style="' . $overlay_css . '"></div>';
		}
	}

	if (class_exists('TheGemGdpr')) {
		$type = null;
		switch ($video_type) {
			case 'youtube':
				$type = TheGemGdpr::CONSENT_NAME_YOUTUBE;
				break;
			case 'vimeo':
				$type = TheGemGdpr::CONSENT_NAME_VIMEO;
				break;
		}

		if (!empty($type)) {
			return TheGemGdpr::getInstance()->replace_disallowed_content($output, $type);
		}
	}


	return $output;
}

function thegeme_migrate_portfolio_item_data($post_data = array()) {
	if(!isset($post_data['grid_appearance_type']) && !empty($post_data['types']) && is_array($post_data['types'])) {
		if(count($post_data['types']) == 1) {
			$type = reset($post_data['types']);
			if(isset($type['type'])) {
				if($type['type'] === 'self-link') {
					$post_data['grid_appearance_image_behavior'] = 'link_to_page';
					$post_data['grid_appearance_behavior_target'] = isset($type['link_target']) ? $type['link_target'] : '_self';
				} elseif($type['type'] === 'inner-link' || $type['type'] === 'outer-link') {
					$post_data['grid_appearance_image_behavior'] = 'custom_link';
					$post_data['grid_appearance_behavior_custom_link'] = isset($type['link']) ? $type['link'] : '';
					$post_data['grid_appearance_behavior_target'] = isset($type['link_target']) ? $type['link_target'] : '_blank';
				} elseif($type['type'] === 'full-image') {
					$post_data['grid_appearance_image_behavior'] = 'lightbox';

				} else {
					$post_data['grid_appearance_image_behavior'] = 'multiple_choice';
				}
			}
		} elseif(count($post_data['types']) > 1) {
			$post_data['grid_appearance_image_behavior'] = 'multiple_choice';
		}
	}
	return $post_data;
}
