<?php

function thegem_title_post_type_init() {
	$labels = array(
		'name'			   => __('Titles', 'thegem'),
		'singular_name'	  => __('Titler', 'thegem'),
		'menu_name'		  => __('Custom Titles', 'thegem'),
		'name_admin_bar'	 => __('Title', 'thegem'),
		'add_new'			=> __('Add New', 'thegem'),
		'add_new_item'	   => __('Add New Title', 'thegem'),
		'new_item'		   => __('New Title', 'thegem'),
		'edit_item'		  => __('Edit Title', 'thegem'),
		'view_item'		  => __('View Title', 'thegem'),
		'all_items'		  => __('All Titles', 'thegem'),
		'search_items'	   => __('Search Titles', 'thegem'),
		'not_found'		  => __('No titles found.', 'thegem'),
		'not_found_in_trash' => __('No titles found in Trash.', 'thegem')
	);

	$args = array(
		'labels'			   => $labels,
		'public'			   => true,
		'exclude_from_search'  => true,
		'publicly_queryable'   => true,
		'show_ui'			  => false,
		'query_var'			=> false,
		'hierarchical'		 => false,
		'supports'			 => array('title', 'editor'),
		'menu_position'		=> 39
	);

	register_post_type('thegem_title', $args);
}
add_action('init', 'thegem_title_post_type_init', 5);

function thegem_force_title_type_private($post) {
	if ($post['post_type'] == 'thegem_title' && $post['post_status'] != 'trash') {
		$post['post_status'] = 'private';
	}
	return $post;
}
add_filter('wp_insert_post_data', 'thegem_force_title_type_private');

function thegem_custom_title_shortcodes_array($shortcodes) {
	global $pagenow;
	if((is_admin() && in_array($pagenow, array('post-new.php', 'post.php', 'admin-ajax.php'))) || (!is_admin() && in_array($pagenow, array('index.php')))) {
		$activate = 0;
		if($pagenow === 'post-new.php' && !empty($_REQUEST['post_type']) && $_REQUEST['post_type'] === 'thegem_title') {
			$activate = true;
		}
		if($pagenow === 'post.php' && !empty($_REQUEST['post']) && get_post_type($_REQUEST['post']) === 'thegem_title') {
			$activate = true;
		}
		if($pagenow === 'post.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_title') {
			$activate = true;
		}
		if($pagenow === 'admin-ajax.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_title') {
			$activate = true;
		}
		if($pagenow === 'index.php' && !empty($_REQUEST['vc_post_id']) && get_post_type($_REQUEST['vc_post_id']) === 'thegem_title') {
			$activate = true;
		}
		if($pagenow === 'post.php' && !empty($_REQUEST['post']) && get_post_type($_REQUEST['post']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post']) == 'title') {
			$activate = true;
		}
		if($pagenow === 'post.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post_id']) == 'title') {
			$activate = true;
		}
		if($pagenow === 'admin-ajax.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post_id']) == 'title') {
			$activate = true;
		}
		if($pagenow === 'index.php' && !empty($_REQUEST['vc_post_id']) && get_post_type($_REQUEST['vc_post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['vc_post_id']) == 'title') {
			$activate = true;
		}
		if($activate) {
			$interactions = thegem_get_interactions_options();
			$shortcodes['gem_title_background'] = array(
				'name' => __('Background Container', 'thegem'),
				'base' => 'gem_title_background',
				'is_container' => true,
				'js_view' => 'VcGemTitleBackgroundView',
				'icon' => 'thegem-icon-wpb-ui-title-background',
				'category' => __('Custom Page Title', 'thegem'),
				'description' => __('Container for custom title background', 'thegem'),
				'weight' => 10,
				'params' => array_merge(array(
					array(
						'type' => 'checkbox',
						'heading' => __('Fullwidth', 'thegem'),
						'param_name' => 'fullwidth',
						'description' => __('If enabled, title\'s background container will be stretched to fullwidth.', 'thegem'),
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => 1,
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Disable content stretching', 'thegem'),
						'param_name' => 'container',
						'description' => __('If activated, content inside the background section will not be stretched to fullwidth.', 'thegem'),
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'fullwidth',
							'value' => array('1')
						),
						'std' => 1,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Default text color', 'thegem'),
						'param_name' => 'color',
						'description' => __('Specify default color for text content inside the background container.', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Background type', 'thegem'),
						'param_name' => 'background_type',
						'value' => array(
							__('Color', 'thegem') => 'color',
							__('Gradient', 'thegem') => 'gradient',
							__('Image / Post Featured Image', 'thegem') => 'image',
							__('Video', 'thegem') => 'video',
						),
						'description' => __('Specify background type of the background container.', 'thegem'),
						'save_always' => true,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'background_color',
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('color')
						),
						'description' => __('Specify background color for page/post title\'s background. Note: if background color is set in page options -> title area -> dynamic settings, it will overwrite the value set here.', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Use post featured image', 'thegem'),
						'param_name' => 'background_image_use_featured',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('image')
						),
					),
					array(
						'type' => 'attach_image',
						'heading' => __('Background image', 'thegem'),
						'param_name' => 'background_image',
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('image')
						),
						'description' => __('Select image for page/post title\'s background. Note: if background image is set in page options -> title area -> dynamic settings, it will overwrite the values set here.', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Background repeat', 'thegem'),
						'param_name' => 'background_image_repeat',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('image')
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Background size', 'thegem'),
						'param_name' => 'background_size',
						'value' => array(
							__('Auto', 'thegem') => 'auto',
							__('Cover', 'thegem') => 'cover',
							__('Contain', 'thegem') => 'contain',
						),
						'std' => 'cover',
						'save_always' => true,
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('image')
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Background horizontal position', 'thegem'),
						'param_name' => 'background_image_position_x',
						'value' => array(
							__('Center', 'thegem') => 'center',
							__('Left', 'thegem') => 'left',
							__('Right', 'thegem') => 'right'
						),
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('image')
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Background vertical position', 'thegem'),
						'param_name' => 'background_image_position_y',
						'value' => array(
							__('Top', 'thegem') => 'top',
							__('Center', 'thegem') => 'center',
							__('Bottom', 'thegem') => 'bottom'
						),
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('image')
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'background_image_color',
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('image')
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Overlay Color', 'thegem'),
						'param_name' => 'background_image_overlay',
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('image')
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Parallax', 'thegem'),
						'param_name' => 'background_parallax',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('image')
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Ken Burns effect', 'thegem'),
						'param_name' => 'ken_burns_enabled',
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('image')
						),
					),
					array(
						'type' => 'dropdown',
						'value' => array(__('Zoom In', 'thegem') => 'zoom_in', __('Zoom Out', 'thegem') => 'zoom_out'),
						'heading' => __('Direction', 'thegem'),
						'param_name' => 'ken_burns_direction',
						'dependency' => array(
							'element' => 'ken_burns_enabled',
							'not_empty' => true
						)
					),
					array(
						'type' => 'textfield',
						'heading' => __('Transition speed, ms', 'thegem'),
						'value' => 15000,
						'param_name' => 'ken_burns_transition_speed',
						'dependency' => array(
							'element' => 'ken_burns_enabled',
							'not_empty' => true
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Background video type', 'thegem'),
						'param_name' => 'video_background_type',
						'value' => array(
							__('YouTube', 'thegem') => 'youtube',
							__('Vimeo', 'thegem') => 'vimeo',
							__('Self', 'thegem') => 'self'
						),
						'description' => __('Specify video for page/post title\'s background. Note: if background video is set in page options -> title area -> dynamic settings, it will overwrite the values set here.', 'thegem'),
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('video')
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Video id (YouTube or Vimeo) or src', 'thegem'),
						'param_name' => 'video_background_src',
						'value' => '',
						'dependency' => array(
							'element' => 'video_background_type',
							'value' => array('youtube', 'vimeo', 'self')
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Video Aspect ratio (16:9, 16:10, 4:3...)', 'thegem'),
						'param_name' => 'video_background_acpect_ratio',
						'value' => '16:9',
						'dependency' => array(
							'element' => 'video_background_type',
							'value' => array('youtube', 'vimeo', 'self')
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Play On Mobile', 'thegem'),
						'param_name' => 'play_on_mobile',
						'dependency' => array(
							'element' => 'video_background_type',
							'not_empty' => true
						)
					),
					array(
						'type' => 'attach_image',
						'heading' => __('Background Fallback', 'thegem'),
						'param_name' => 'background_fallback',
						'dependency' => array(
							'element' => 'video_background_type',
							'not_empty' => true
						)
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background video overlay color', 'thegem'),
						'param_name' => 'video_background_overlay_color',
						'dependency' => array(
							'element' => 'video_background_type',
							'value' => array('youtube', 'vimeo', 'self')
						),
					),
					array(
						'type' => 'attach_image',
						'heading' => __('Video Poster', 'thegem'),
						'param_name' => 'video_background_poster',
						'dependency' => array(
							'element' => 'video_background_type',
							'value' => array('self')
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Background Gradient Type', 'thegem'),
						'param_name' => 'background_gradient_type',
						'value' => array(
							__('Linear', 'thegem') => 'linear',
							__('Radial', 'thegem') => 'radial',
						),
						'description' => __('Specify gradient settings for page/post title\'s background. Note: if background gradient is set in page options -> title area -> dynamic settings, it will overwrite the values set here.', 'thegem'),
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('gradient')
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Background Gradient Angle', 'thegem'),
						'param_name' => 'background_gradient_angle',
						'dependency' => array(
							'element' => 'background_gradient_type',
							'value' => array('linear')
						),
						'std' => '0',
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Background Gradient Position', 'thegem'),
						'param_name' => 'background_gradient_position',
						'value' => array(
							__('Top Left', 'thegem') => 'top left',
							__('Top Center', 'thegem') => 'top center',
							__('Top Right', 'thegem') => 'top right',
							__('Center Left', 'thegem') => 'center left',
							__('Center Center', 'thegem') => 'center center',
							__('Center Right', 'thegem') => 'center right',
							__('Bottom Left', 'thegem') => 'bottom left',
							__('Bottom Center', 'thegem') => 'bottom center',
							__('Bottom Right', 'thegem') => 'bottom right',
						),
						'std' => 'center center',
						'dependency' => array(
							'element' => 'background_gradient_type',
							'value' => array('radial')
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Gradient Color 1', 'thegem'),
						'param_name' => 'background_gradient_point1_color',
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('gradient')
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Background Gradient Position 1 (%)', 'thegem'),
						'param_name' => 'background_gradient_point1_position',
						'std' => '0',
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('gradient')
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Gradient Color 2', 'thegem'),
						'param_name' => 'background_gradient_point2_color',
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('gradient')
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Background Gradient Position 2 (%)', 'thegem'),
						'param_name' => 'background_gradient_point2_position',
						'dependency' => array(
							'element' => 'background_type',
							'value' => array('gradient')
						),
						'std' => '100',
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Padding top', 'thegem'),
						'param_name' => 'padding_top_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'padding_top',
						'std' => 80,
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'padding_top_tablet',
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'padding_top_mobile',
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Padding bottom', 'thegem'),
						'param_name' => 'padding_bottom_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'padding_bottom',
						'std' => 80,
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'padding_bottom_tablet',
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'padding_bottom_mobile',
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Padding left', 'thegem'),
						'param_name' => 'padding_left_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'padding_left',
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'padding_left_tablet',
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'padding_left_mobile',
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Padding right', 'thegem'),
						'param_name' => 'padding_right_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'padding_right',
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'padding_right_tablet',
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'padding_right_mobile',
						'edit_field_class' => 'vc_column vc_col-sm-4'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Extra Class', 'thegem'),
						'param_name' => 'extra_class',
					),
				)),
			);
			$shortcodes['gem_title_title'] = array(
				'name' => __('Title', 'thegem'),
				'base' => 'gem_title_title',
				'icon' => 'thegem-icon-wpb-ui-title-title',
				'category' => __('Custom Page Title', 'thegem'),
				'description' => __('Custom Title - Title', 'thegem'),
				'weight' => 10,
				'params' => array_merge(array(
					array(
						'type' => 'checkbox',
						'heading' => __('Disable automatic page / post title', 'thegem'),
						'param_name' => 'use_shortcode_data',
						'description' => __('By default custom title gets its content dynamically from the page/post title where it is used. Enabling this checkbox deactivates dynamic content.', 'thegem'),
						'value' => array(__('Yes', 'thegem') => '1'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Type', 'thegem'),
						'param_name' => 'type',
						'description' => __('Specify content type for title: simple text or rich text.', 'thegem'),
						'value' => array(__('Simple text', 'thegem') => 'sipmle', __('Rich text', 'thegem') => 'rich'),
						'dependency' => array(
							'element' => 'use_shortcode_data',
							'not_empty' => true
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Text', 'thegem'),
						'param_name' => 'text',
						'dependency' => array(
							'element' => 'type',
							'value' => 'sipmle'
						),
					),
					array(
						'type' => 'textarea_html',
						'heading' => __('Content', 'thegem'),
						'param_name' => 'content',
						'dependency' => array(
							'element' => 'type',
							'value' => 'rich'
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Alignment', 'thegem'),
						'param_name' => 'alignment',
						'value' => array(__('Left', 'thegem') => 'left', __('Right', 'thegem') => 'right', __('Center', 'thegem') => 'center'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('xLarge text', 'thegem'),
						'param_name' => 'xlarge',
						'value' => array(__('Yes', 'thegem') => '1'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Max width', 'thegem'),
						'param_name' => 'width',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Extra Class', 'thegem'),
						'param_name' => 'extra_class',
					),
					array(
						'type' => 'thegem_hidden_param',
						'param_name' => 'v',
						'std' => '5',
						'save_always' => true,
					),
					array(
						'type' => 'dropdown',
						'heading' => __('HTML Tag', 'thegem'),
						'param_name' => 'page_title_tag',
						'value' => array(
							__('H1', 'thegem') => 'h1',
							__('H2', 'thegem') => 'h2',
							__('H3', 'thegem') => 'h3',
							__('H4', 'thegem') => 'h4',
							__('H5', 'thegem') => 'h5',
							__('H6', 'thegem') => 'h6',
							__('p', 'thegem') => 'p',
							__('div', 'thegem') => 'div'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Style', 'thegem'),
						'param_name' => 'page_title_div_style',
						'value' => array(
							__('None', 'thegem') => '',
							__('Title H1', 'thegem') => 'title-h1',
							__('Title H2', 'thegem') => 'title-h2',
							__('Title H3', 'thegem') => 'title-h3',
							__('Title H4', 'thegem') => 'title-h4',
							__('Title H5', 'thegem') => 'title-h5',
							__('Title H6', 'thegem') => 'title-h6',
							__('Styled Subtitle', 'thegem') => 'styled-subtitle',
						),
						'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Font weight', 'thegem'),
						'param_name' => 'page_title_text_weight',
						'value' => array(
							__('Default', 'thegem') => 'default',
							__('Thin', 'thegem') => 'light',
						),
						'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Font italic', 'thegem'),
						'param_name' => 'page_title_text_style',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Underline', 'thegem'),
						'param_name' => 'page_title_text_decoration',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text color', 'thegem'),
						'param_name' => 'color',
						'description' => __('Specify color for page/post title. Note: if title\'s color is set in page options -> title area -> dynamic settings, it will overwrite the value set here.', 'thegem'),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Use custom font size?', 'thegem'),
						'param_name' => 'page_title_custom_font_size',
						'value' => array(__('Yes', 'thegem') => '1'),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Font size', 'thegem'),
						'param_name' => 'page_title_font_size',
						'dependency' => array(
							'element' => 'page_title_custom_font_size',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Line height', 'thegem'),
						'param_name' => 'page_title_line_height',
						'dependency' => array(
							'element' => 'page_title_custom_font_size',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Letter spacing', 'thegem'),
						'param_name' => 'page_title_letter_spacing',
						'dependency' => array(
							'element' => 'page_title_custom_font_size',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Text transform', 'thegem'),
						'param_name' => 'page_title_text_transform',
						'value' => array(
							__('None', 'thegem') => 'none',
							__('Capitalize', 'thegem') => 'capitalize',
							__('Lowercase', 'thegem') => 'lowercase',
							__('Uppercase', 'thegem') => 'uppercase'
						),
						'dependency' => array(
							'element' => 'page_title_custom_font_size',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Responsive font size options', 'thegem'),
						'param_name' => 'page_title_responsive_font',
						'value' => array(__('Yes', 'thegem') => '1'),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Font size tablet', 'thegem'),
						'param_name' => 'page_title_font_size_tablet',
						'dependency' => array(
							'element' => 'page_title_responsive_font',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Line height tablet', 'thegem'),
						'param_name' => 'page_title_line_height_tablet',
						'dependency' => array(
							'element' => 'page_title_responsive_font',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Letter spacing tablet', 'thegem'),
						'param_name' => 'page_title_letter_spacing_tablet',
						'dependency' => array(
							'element' => 'page_title_responsive_font',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Font size mobile', 'thegem'),
						'param_name' => 'page_title_font_size_mobile',
						'dependency' => array(
							'element' => 'page_title_responsive_font',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Line height mobile', 'thegem'),
						'param_name' => 'page_title_line_height_mobile',
						'dependency' => array(
							'element' => 'page_title_responsive_font',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Letter spacing mobile', 'thegem'),
						'param_name' => 'page_title_letter_spacing_mobile',
						'dependency' => array(
							'element' => 'page_title_responsive_font',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Use Google fonts?', 'thegem'),
						'param_name' => 'page_title_google_font',
						'value' => array(__('Yes', 'thegem') => '1'),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'google_fonts',
						'param_name' => 'page_title_custom_fonts',
						'value' => '',
						'settings' => array(
							'fields' => array(
								'font_family_description' => esc_html__('Select font family.', 'thegem'),
								'font_style_description' => esc_html__('Select font styling.', 'thegem'),
							),
						),
						'dependency' => array(
							'element' => 'page_title_google_font',
							'value' => '1'
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Margin top', 'thegem'),
						'param_name' => 'margin_top_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'margin_top',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'margin_top_tablet',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'margin_top_mobile',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Margin bottom', 'thegem'),
						'param_name' => 'margin_bottom_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'margin_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'margin_bottom_tablet',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'margin_bottom_mobile',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Animation Type', 'thegem'),
						'param_name' => 'page_title_animation_type',
						'value' => array(
							__('Default', 'thegem') => 'default',
							//__('Advanced', 'thegem') => 'advanced'
						),
						'group' => __('Animations', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('CSS Animation', 'thegem'),
						'param_name' => 'page_title_letter_animation_type',
						'value' => array(
							__('Lorem ipsum 1', 'thegem') => '1',
							__('Lorem ipsum 2', 'thegem') => '2',
							__('Lorem ipsum 3', 'thegem') => '3',
							__('Lorem ipsum 4', 'thegem') => '4',
						),
						'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'thegem' ),
						'dependency' => array(
							'element' => 'page_title_animation_type',
							'value' => 'advanced'
						),
						'group' => __('Animations', 'thegem')
					),
					array(
						'type' => 'animation_style',
						'heading' => esc_html__( 'CSS Animation', 'thegem' ),
						'param_name' => 'css_animation',
						'value' => '',
						'settings' => array(
							'type' => 'in',
							'custom' => array(
								array(
									'label' => esc_html__( 'Default', 'thegem' ),
									'values' => array(
										esc_html__( 'Top to bottom', 'thegem' ) => 'top-to-bottom',
										esc_html__( 'Bottom to top', 'thegem' ) => 'bottom-to-top',
										esc_html__( 'Left to right', 'thegem' ) => 'left-to-right',
										esc_html__( 'Right to left', 'thegem' ) => 'right-to-left',
										esc_html__( 'Appear from center', 'thegem' ) => 'appear',
									),
								),
							),
						),
						'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'thegem' ),
						'dependency' => array(
							'element' => 'page_title_animation_type',
							'value' => 'default'
						),
						'group' => __('Animations', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Animation Speed', 'thegem'),
						'param_name' => 'page_title_animation_speed',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'page_title_animation_type',
							'value' => 'advanced'
						),
						'group' => __('Animations', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Animation Delay', 'thegem'),
						'param_name' => 'page_title_animation_delay',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Animations', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Animation Interval', 'thegem'),
						'param_name' => 'page_title_animation_interval',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'page_title_animation_type',
							'value' => 'advanced'
						),
						'group' => __('Animations', 'thegem')
					),
				),
				$interactions,
				array(
					array(
						'type' => 'checkbox',
						'heading' => __('Hide on desktop', 'thegem'),
						'param_name' => 'page_title_disable_desktop',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
						'group' => __('Responsive', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Hide on tablet', 'thegem'),
						'param_name' => 'page_title_disable_tablet',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
						'group' => __('Responsive', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Hide on mobile', 'thegem'),
						'param_name' => 'page_title_disable_mobile',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
						'group' => __('Responsive', 'thegem')
					),
				))
			);
			$shortcodes['gem_title_excerpt'] = array(
				'name' => __('Excerpt', 'thegem'),
				'base' => 'gem_title_excerpt',
				'icon' => 'thegem-icon-wpb-ui-title-excerpt',
				'category' => __('Custom Page Title', 'thegem'),
				'description' => __('Custom Title - Excerpt', 'thegem'),
				'weight' => 10,
				'params' => array_merge(array(
					array(
						'type' => 'checkbox',
						'heading' => __('Disable automatic page/post excerpt ', 'thegem'),
						'param_name' => 'use_shortcode_data',
						'value' => array(__('Yes', 'thegem') => '1'),
						'description' => __('By default custom excerpt gets its content dynamically from the page/post excerpt where it is used. Enabling this checkbox deactivates dynamic content.', 'thegem'),
					),
					array(
						'type' => 'textarea_html',
						'heading' => __('Content', 'thegem'),
						'param_name' => 'content',
						'dependency' => array(
							'element' => 'use_shortcode_data',
							'not_empty' => true
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Alignment', 'thegem'),
						'param_name' => 'alignment',
						'value' => array(__('Left', 'thegem') => 'left', __('Right', 'thegem') => 'right', __('Center', 'thegem') => 'center'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Max width', 'thegem'),
						'param_name' => 'width',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Extra Class', 'thegem'),
						'param_name' => 'extra_class',
					),
					array(
						'type' => 'thegem_hidden_param',
						'param_name' => 'v',
						'std' => '5',
						'save_always' => true,
					),
					array(
						'type' => 'dropdown',
						'heading' => __('HTML Tag', 'thegem'),
						'param_name' => 'page_excerpt_tag',
						'value' => array(
							__('Default', 'thegem') => '',
							__('H1', 'thegem') => 'h1',
							__('H2', 'thegem') => 'h2',
							__('H3', 'thegem') => 'h3',
							__('H4', 'thegem') => 'h4',
							__('H5', 'thegem') => 'h5',
							__('H6', 'thegem') => 'h6',
							__('p', 'thegem') => 'p',
							__('div', 'thegem') => 'div'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Style', 'thegem'),
						'param_name' => 'page_excerpt_div_style',
						'value' => array(
							__('Default', 'thegem') => '',
							__('Title H1', 'thegem') => 'title-h1',
							__('Title H2', 'thegem') => 'title-h2',
							__('Title H3', 'thegem') => 'title-h3',
							__('Title H4', 'thegem') => 'title-h4',
							__('Title H5', 'thegem') => 'title-h5',
							__('Title H6', 'thegem') => 'title-h6',
							__('Styled Subtitle', 'thegem') => 'styled-subtitle'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Font weight', 'thegem'),
						'param_name' => 'page_excerpt_text_weight',
						'value' => array(
							__('Default', 'thegem') => 'default',
							__('Thin', 'thegem') => 'light',
						),
						'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Font italic', 'thegem'),
						'param_name' => 'page_excerpt_text_style',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Underline', 'thegem'),
						'param_name' => 'page_excerpt_text_decoration',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text color', 'thegem'),
						'param_name' => 'color',
						'description' => 'Specify color for page/post excerpt. Note: if excerpt\'s color is set in page options -> title area -> dynamic settings, it will overwrite the value set here.',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Use custom font size?', 'thegem'),
						'param_name' => 'page_excerpt_custom_font_size',
						'value' => array(__('Yes', 'thegem') => '1'),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Font size', 'thegem'),
						'param_name' => 'page_excerpt_font_size',
						'dependency' => array(
							'element' => 'page_excerpt_custom_font_size',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Line height', 'thegem'),
						'param_name' => 'page_excerpt_line_height',
						'dependency' => array(
							'element' => 'page_excerpt_custom_font_size',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Letter spacing', 'thegem'),
						'param_name' => 'page_excerpt_letter_spacing',
						'dependency' => array(
							'element' => 'page_excerpt_custom_font_size',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Text transform', 'thegem'),
						'param_name' => 'page_excerpt_text_transform',
						'value' => array(
							__('None', 'thegem') => 'none',
							__('Capitalize', 'thegem') => 'capitalize',
							__('Lowercase', 'thegem') => 'lowercase',
							__('Uppercase', 'thegem') => 'uppercase'
						),
						'dependency' => array(
							'element' => 'page_excerpt_custom_font_size',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Responsive font size options', 'thegem'),
						'param_name' => 'page_excerpt_responsive_font',
						'value' => array(__('Yes', 'thegem') => '1'),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Font size tablet', 'thegem'),
						'param_name' => 'page_excerpt_font_size_tablet',
						'dependency' => array(
							'element' => 'page_excerpt_responsive_font',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Line height tablet', 'thegem'),
						'param_name' => 'page_excerpt_line_height_tablet',
						'dependency' => array(
							'element' => 'page_excerpt_responsive_font',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Letter spacing tablet', 'thegem'),
						'param_name' => 'page_excerpt_letter_spacing_tablet',
						'dependency' => array(
							'element' => 'page_excerpt_responsive_font',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Font size mobile', 'thegem'),
						'param_name' => 'page_excerpt_font_size_mobile',
						'dependency' => array(
							'element' => 'page_excerpt_responsive_font',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Line height mobile', 'thegem'),
						'param_name' => 'page_excerpt_line_height_mobile',
						'dependency' => array(
							'element' => 'page_excerpt_responsive_font',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Letter spacing mobile', 'thegem'),
						'param_name' => 'page_excerpt_letter_spacing_mobile',
						'dependency' => array(
							'element' => 'page_excerpt_responsive_font',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Use Google fonts?', 'thegem'),
						'param_name' => 'page_excerpt_google_font',
						'value' => array(__('Yes', 'thegem') => '1'),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'google_fonts',
						'param_name' => 'page_excerpt_custom_fonts',
						'value' => '',
						'settings' => array(
							'fields' => array(
								'font_family_description' => esc_html__('Select font family.', 'thegem'),
								'font_style_description' => esc_html__('Select font styling.', 'thegem'),
							),
						),
						'dependency' => array(
							'element' => 'page_excerpt_google_font',
							'value' => '1'
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Margin top', 'thegem'),
						'param_name' => 'margin_top_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'margin_top',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'margin_top_tablet',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'margin_top_mobile',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Margin bottom', 'thegem'),
						'param_name' => 'margin_bottom_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'margin_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'margin_bottom_tablet',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'margin_bottom_mobile',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Animation Type', 'thegem'),
						'param_name' => 'page_excerpt_animation_type',
						'value' => array(
							__('Default', 'thegem') => 'default',
							//__('Advanced', 'thegem') => 'advanced'
						),
						'group' => __('Animations', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('CSS Animation', 'thegem'),
						'param_name' => 'page_excerpt_letter_animation_type',
						'value' => array(
							__('Lorem ipsum 1', 'thegem') => '1',
							__('Lorem ipsum 2', 'thegem') => '2',
							__('Lorem ipsum 3', 'thegem') => '3',
							__('Lorem ipsum 4', 'thegem') => '4',
						),
						'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'thegem' ),
						'dependency' => array(
							'element' => 'page_excerpt_animation_type',
							'value' => 'advanced'
						),
						'group' => __('Animations', 'thegem')
					),
					array(
						'type' => 'animation_style',
						'heading' => esc_html__( 'CSS Animation', 'thegem' ),
						'param_name' => 'css_animation',
						'value' => '',
						'settings' => array(
							'type' => 'in',
							'custom' => array(
								array(
									'label' => esc_html__( 'Default', 'thegem' ),
									'values' => array(
										esc_html__( 'Top to bottom', 'thegem' ) => 'top-to-bottom',
										esc_html__( 'Bottom to top', 'thegem' ) => 'bottom-to-top',
										esc_html__( 'Left to right', 'thegem' ) => 'left-to-right',
										esc_html__( 'Right to left', 'thegem' ) => 'right-to-left',
										esc_html__( 'Appear from center', 'thegem' ) => 'appear',
									),
								),
							),
						),
						'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'thegem' ),
						'dependency' => array(
							'element' => 'page_excerpt_animation_type',
							'value' => 'default'
						),
						'group' => __('Animations', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Animation Speed', 'thegem'),
						'param_name' => 'page_excerpt_animation_speed',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'page_excerpt_animation_type',
							'value' => 'advanced'
						),
						'group' => __('Animations', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Animation Delay', 'thegem'),
						'param_name' => 'page_excerpt_animation_delay',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Animations', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Animation Interval', 'thegem'),
						'param_name' => 'page_excerpt_animation_interval',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'page_excerpt_animation_type',
							'value' => 'advanced'
						),
						'group' => __('Animations', 'thegem')
					),
				),
				$interactions,
				array(
					array(
						'type' => 'checkbox',
						'heading' => __('Hide on desktop', 'thegem'),
						'param_name' => 'page_excerpt_disable_desktop',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
						'group' => __('Responsive', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Hide on tablet', 'thegem'),
						'param_name' => 'page_excerpt_disable_tablet',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
						'group' => __('Responsive', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Hide on mobile', 'thegem'),
						'param_name' => 'page_excerpt_disable_mobile',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
						'group' => __('Responsive', 'thegem')
					),
				)),
			);
			$shortcodes['gem_title_icon'] = array(
				'name' => __('Icon', 'thegem'),
				'base' => 'gem_title_icon',
				'icon' => 'thegem-icon-wpb-ui-title-icon',
				'category' => __('Custom Page Title', 'thegem'),
				'description' => __('Custom Title - Icon', 'thegem'),
				'weight' => 10,
				'params' => array_merge(array(
					array(
						'type' => 'dropdown',
						'heading' => __('Icon pack', 'thegem'),
						'param_name' => 'pack',
						'value' => array_merge(array(__('Elegant', 'thegem') => 'elegant', __('Material Design', 'thegem') => 'material', __('FontAwesome', 'thegem') => 'fontawesome', __('Header Icons', 'thegem') => 'thegem-header'), thegem_userpack_to_dropdown()),
						'std' => 'material'
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('elegant')
						),
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('material')
						),
						'std' => 'f287'
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('fontawesome')
						),
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_thegem_header',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('thegem-header')
						),
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('userpack')
						),
					),
				)),
				array(
					array(
						'type' => 'dropdown',
						'heading' => __('Shape', 'thegem'),
						'param_name' => 'shape',
						'value' => array(__('Square', 'thegem') => 'square', __('Circle', 'thegem') => 'circle', __('Rhombus', 'thegem') => 'romb', __('Hexagon', 'thegem') => 'hexagon'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Style', 'thegem'),
						'param_name' => 'style',
						'value' => array(__('Default', 'thegem') => '', __('45 degree Right', 'thegem') => 'angle-45deg-r', __('45 degree Left', 'thegem') => 'angle-45deg-l', __('90 degree', 'thegem') => 'angle-90deg'),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'color',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color 2', 'thegem'),
						'param_name' => 'color_2',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'background_color',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'border_color',
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Size', 'thegem'),
						'param_name' => 'size',
						'value' => array(__('Small', 'thegem') => 'small', __('Medium', 'thegem') => 'medium', __('Large', 'thegem') => 'large', __('Extra Large', 'thegem') => 'xlarge'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Alignment', 'thegem'),
						'param_name' => 'alignment',
						'value' => array(__('Default position', 'thegem') => 'default', __('Centered', 'thegem') => 'center', __('Left to content', 'thegem') => 'left', __('Right to content', 'thegem') => 'right'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Margin left', 'thegem'),
						'param_name' => 'margin_left',
						'dependency' => array(
							'element' => 'alignment',
							'value' => array('right')
						),
						'std' => 30,
					),
					array(
						'type' => 'textfield',
						'heading' => __('Margin right', 'thegem'),
						'param_name' => 'margin_right',
						'dependency' => array(
							'element' => 'alignment',
							'value' => array('left')
						),
						'std' => 30,
					),
					array(
						'type' => 'textfield',
						'heading' => __('Extra Class', 'thegem'),
						'param_name' => 'extra_class',
					),
				)),
			);
		}
	}
	return $shortcodes;
}
add_filter('thegem_shortcodes_array', 'thegem_custom_title_shortcodes_array');

function thegem_vc_add_element_categories_custom_title($tabs) {
	$title_tab = false;
	$title_tab_key = -1;
	foreach($tabs as $key => $tab) {
		if($tab['name'] === __('Custom Page Title', 'thegem')) {
			$title_tab = $tab;
			$title_tab_key = $key;
		}
	}
	if($title_tab_key > -1) {
		unset($tabs[$title_tab_key]);
		$title_tab['active'] = 1;
		foreach($tabs as $key => $tab) {
			if($tab['active']) {
				$tabs[$key]['active'] = false;
			}
		}
		$tabs = array_merge(array($title_tab), $tabs);
	}
	return $tabs;
}
add_filter('vc_add_element_categories', 'thegem_vc_add_element_categories_custom_title');

add_shortcode('gem_title_title', 'thegem_title_title_shortcode');
add_shortcode('gem_title_excerpt', 'thegem_title_excerpt_shortcode');
add_shortcode('gem_title_icon', 'thegem_title_icon_shortcode');
add_shortcode('gem_title_background', 'thegem_title_background_shortcode');

function thegem_title_title_shortcode($atts, $content) {
	$output = $main_class = $inner_class = $uniqid = $inner_styles = '';
	$atts = shortcode_atts( array(
		'use_shortcode_data' => 0,
		'type' => 'simple',
		'text' => '',
		'content' => $content,
		'color' => '',
		'xlarge' => '',
		'alignment' => 'left',
		'width' => '',
		'margin_top' => '',
		'margin_bottom' => '',
		'extra_class' => '',
		'v' => '',
		/**********/
		'margin_top_tablet' => '',
		'margin_top_mobile' => '',
		'margin_bottom_tablet' => '',
		'margin_bottom_mobile' => '',
		'page_title_tag' => 'h1',
		'page_title_div_style' => 'title-h1',
		'page_title_text_weight' => 'default',
		'page_title_text_style' => '',
		'page_title_text_decoration' => '',
		'page_title_custom_font_size' => '',
		'page_title_font_size' => '',
		'page_title_line_height' => '',
		'page_title_letter_spacing' => '',
		'page_title_text_transform' => 'none',
		'page_title_responsive_font' => '',
		'page_title_font_size_tablet' => '',
		'page_title_line_height_tablet' => '',
		'page_title_letter_spacing_tablet' => '',
		'page_title_font_size_mobile' => '',
		'page_title_line_height_mobile' => '',
		'page_title_letter_spacing_mobile' => '',
		'page_title_google_font' => '',
		'page_title_custom_fonts' => '',
		'page_title_letter_animation_type' => '',
		'css_animation' => '',
		'page_title_animation_speed' => '',
		'page_title_animation_delay' => '',
		'page_title_animation_interval' => '',
		'page_title_disable_desktop' => '',
		'page_title_disable_tablet' => '',
		'page_title_disable_mobile' => '',
			/*interactions*/
		'interactions_enabled' => '',
		'vertical_scroll' => '',
		'vertical_scroll_direction' => 'vertical_scroll_direction_up',
		'vertical_scroll_speed' => '3',
		'vertical_scroll_viewport_bottom' => '0',
		'vertical_scroll_viewport_top' => '100',
		'horizontal_scroll' => '',
		'horizontal_scroll_direction' => 'horizontal_scroll_direction_left',
		'horizontal_scroll_speed' => '3',
		'horizontal_scroll_viewport_bottom' => '0',
		'horizontal_scroll_viewport_top' => '100',
		'mouse_effects' => '',
		'mouse_effects_direction' => 'mouse_effects_direction_opposite',
		'mouse_effects_speed' => '3',
		'disable_effects_desktop' => '',
		'disable_effects_tablet' => '',
		'disable_effects_mobile' => ''
			/*end interactions*/
	), $atts, 'gem_title_title' );
	
	global $thegem_page_title_template_data;
	
	$wrap_class = uniqid('thegem-title-wrap-');
	$main_class .= $wrap_class;
	
	$uniqid = uniqid('thegem-page-title-');
	$inner_class .= $uniqid;
	
	if(!empty($atts['extra_class'])) {
		$main_class .= ' '.$atts['extra_class'];
	}
	
	if(!empty($atts['xlarge'])) {
		$inner_class .= ' title-xlarge';
	} else if(isset($atts['page_title_div_style']) && !empty($atts['page_title_div_style'])) {
		$inner_class .= ' '.$atts['page_title_div_style'];
	}
	if(isset($atts['page_title_text_weight']) && $atts['page_title_text_weight'] == 'light') {
		$inner_class .= ' '.$atts['page_title_text_weight'];
	}
	
	if(!empty($inner_class)) {
		$inner_class = 'class="'.esc_attr($inner_class).'"';
	}
	
	$page_data = $thegem_page_title_template_data;
	if(!empty($thegem_page_title_template_data['title_use_page_settings'])) {
		$atts = array_merge($atts, array(
			'type' => $page_data['title_rich_content'] ? 'rich' : 'simple',
			'text' => !empty($thegem_page_title_template_data['main_title']) && empty($atts['use_shortcode_data']) ? $thegem_page_title_template_data['main_title'] : $atts['text'],
			'content' => $page_data['title_content'],
			'color' => $page_data['title_text_color'] ? $page_data['title_text_color'] : $atts['color'],
			//'xlarge' => $atts['xlarge'] || !empty($page_data['title_xlarge_custom_migrate']),
		));
		if(empty($atts['v'])) {
			$atts['xlarge'] = $atts['xlarge'] || !empty($page_data['title_xlarge']);
			$atts['alignment'] = $page_data['title_alignment'] != '' ? $page_data['title_alignment'] : $atts['alignment'];
		}
	}
	if(empty($atts['text']) && !empty($thegem_page_title_template_data['main_title'])) {
		$atts['text'] = $thegem_page_title_template_data['main_title'];
	}
	if(empty($atts['use_shortcode_data']) && !empty($page_data['title_rich_content']) && !empty($page_data['title_content'])) {
		$atts['type'] = 'rich';
		$atts['content'] = $page_data['title_content'];
	}
	$styles = '';
	if($atts['alignment'] == 'right' || $atts['alignment'] == 'center') {
		$styles .= 'text-align: '.$atts['alignment'].';';
	}
	if($atts['alignment'] == 'center') {
		$styles .= 'margin-left: auto;margin-right: auto;';
	}

	if($atts['alignment'] == 'right') {
		$styles .= 'margin-left: auto;margin-right: 0;';
	}

	if(isset($atts['margin_top']) && !empty($atts['margin_top']) || strcmp($atts['margin_top'], '0') === 0) {
		$inner_styles .= '.'.esc_attr($wrap_class).'.custom-title-title, .fullwidth-block-inner > .container > .'.esc_attr($wrap_class).'.custom-title-title {margin-top: '.intval($atts['margin_top']).'px;}';
	}
	if(isset($atts['margin_top_tablet']) && !empty($atts['margin_top_tablet']) || strcmp($atts['margin_top_tablet'], '0') === 0) {
		$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {.'.esc_attr($wrap_class).'.custom-title-title, .fullwidth-block-inner > .container > .'.esc_attr($wrap_class).'.custom-title-title {margin-top: '.intval($atts['margin_top_tablet']).'px;}}';
	}
	if(isset($atts['margin_top_mobile']) && !empty($atts['margin_top_mobile']) || strcmp($atts['margin_top_mobile'], '0') === 0) {
		$inner_styles .= '@media screen and (max-width: 767px) {.'.esc_attr($wrap_class).'.custom-title-title, .fullwidth-block-inner > .container > .'.esc_attr($wrap_class).'.custom-title-title {margin-top: '.intval($atts['margin_top_mobile']).'px;}}';
	}
	if(isset($atts['margin_bottom']) && !empty($atts['margin_bottom']) || strcmp($atts['margin_bottom'], '0') === 0) {
		$inner_styles .= '.'.esc_attr($wrap_class).'.custom-title-title {margin-bottom: '.intval($atts['margin_bottom']).'px;}';
	}
	if(isset($atts['margin_bottom_tablet']) && !empty($atts['margin_bottom_tablet']) || strcmp($atts['margin_bottom_tablet'], '0') === 0) {
		$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {.'.esc_attr($wrap_class).'.custom-title-title {margin-bottom: '.intval($atts['margin_bottom_tablet']).'px;}}';
	}
	if(isset($atts['margin_bottom_mobile']) && !empty($atts['margin_bottom_mobile']) || strcmp($atts['margin_bottom_mobile'], '0') === 0) {
		$inner_styles .= '@media screen and (max-width: 767px) {.'.esc_attr($wrap_class).'.custom-title-title {margin-bottom: '.intval($atts['margin_bottom_mobile']).'px;}}';
	}
	if(intval($atts['width']) > 0) {
		$styles .= 'max-width: '.intval($atts['width']).'px;';
	}
	
	if(!empty($atts['color'])) {
		$inner_styles .= '.'.esc_attr($uniqid).' {color: '.esc_attr($atts['color']).'}';
	}
	if(isset($atts['page_title_text_style']) && !empty($atts['page_title_text_style'])) {
		$inner_styles .= '.'.esc_attr($uniqid).', .'.esc_attr($uniqid).'.light {font-style: italic;}';
	}
	if(isset($atts['page_title_text_decoration']) && !empty($atts['page_title_text_decoration'])) {
		$inner_styles .= '.'.esc_attr($uniqid).', .'.esc_attr($uniqid).'.light {text-decoration: underline;}';
	}
	
	if(!empty($atts['page_title_custom_font_size'])) {
		if(isset($atts['page_title_font_size']) && !empty($atts['page_title_font_size'])) {
			$inner_styles .= '.'.esc_attr($uniqid).' {font-size: '.esc_attr($atts['page_title_font_size']).'px;}';
		}
		if(isset($atts['page_title_line_height']) && !empty($atts['page_title_line_height'])) {
			$inner_styles .= '.'.esc_attr($uniqid).' {line-height: '.esc_attr($atts['page_title_line_height']).'px;}';
		}
		if(isset($atts['page_title_letter_spacing']) && !empty($atts['page_title_letter_spacing']) || strcmp($atts['page_title_letter_spacing'], '0') === 0) {
			$inner_styles .= '.'.esc_attr($uniqid).' {letter-spacing: '.esc_attr($atts['page_title_letter_spacing']).'px;}';
		}
		if(isset($atts['page_title_text_transform']) && !empty($atts['page_title_text_transform'])) {
			$inner_styles .= '.'.esc_attr($uniqid).', .'.esc_attr($uniqid).'.light {text-transform: '.esc_attr($atts['page_title_text_transform']).';}';
		}
	}
	
	if(!empty($atts['page_title_responsive_font'])) {
		if(isset($atts['page_title_font_size_tablet']) && !empty($atts['page_title_font_size_tablet'])) {
			$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {.'.esc_attr($uniqid).' {font-size: '.esc_attr($atts['page_title_font_size_tablet']).'px;}}';
		}
		if(isset($atts['page_title_line_height_tablet']) && !empty($atts['page_title_line_height_tablet'])) {
			$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {.'.esc_attr($uniqid).' {line-height: '.esc_attr($atts['page_title_line_height_tablet']).'px;}}';
		}
		if(isset($atts['page_title_letter_spacing_tablet']) && !empty($atts['page_title_letter_spacing_tablet']) || strcmp($atts['page_title_letter_spacing_tablet'], '0') === 0) {
			$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {.'.esc_attr($uniqid).' {letter-spacing: '.esc_attr($atts['page_title_letter_spacing_tablet']).'px;}}';
		}
		if(isset($atts['page_title_font_size_mobile']) && !empty($atts['page_title_font_size_mobile'])) {
			$inner_styles .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqid).' {font-size: '.esc_attr($atts['page_title_font_size_mobile']).'px;}}';
		}
		if(isset($atts['page_title_line_height_mobile']) && !empty($atts['page_title_line_height_mobile'])) {
			$inner_styles .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqid).' {line-height: '.esc_attr($atts['page_title_line_height_mobile']).'px;}}';
		}
		if(isset($atts['page_title_letter_spacing_mobile']) && !empty($atts['page_title_letter_spacing_mobile']) || strcmp($atts['page_title_letter_spacing_mobile'], '0') === 0) {
			$inner_styles .= '@media screen and (max-width: 767px) {.'.esc_attr($uniqid).' {letter-spacing: '.esc_attr($atts['page_title_letter_spacing_mobile']).'px;}}';
		}
	}
	
	if(isset($atts['page_title_disable_desktop']) && !empty($atts['page_title_disable_desktop'])) {
		$inner_styles .= '@media screen and (min-width: 1024px) {.'.esc_attr($wrap_class).' {display: none;}}';
	}
	if(isset($atts['page_title_disable_tablet']) && !empty($atts['page_title_disable_tablet'])) {
		$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {.'.esc_attr($wrap_class).' {display: none;}}';
	}
	if(isset($atts['page_title_disable_mobile']) && !empty($atts['page_title_disable_mobile'])) {
		$inner_styles .= '@media screen and (max-width: 767px) {.'.esc_attr($wrap_class).' {display: none;}}';
	}
	
	if(!empty($atts['page_title_custom_fonts']) && !empty($atts['page_title_google_font'])) {
		$font = thegem_font_parse($atts['page_title_custom_fonts']);
		$inner_styles .= '.'.$uniqid.', .'.$uniqid.'.light {'.esc_attr($font).'}';
	}
	
	$interactions_data = '';
	if(isset($atts['interactions_enabled']) && !empty($atts['interactions_enabled'])) {
		$main_class .= ' gem-interactions-enabled';
		$interactions_data = interactions_data_attr($atts);
	}
	
	if($atts['css_animation']) {
		wp_enqueue_script( 'vc_waypoints' );
		wp_enqueue_style( 'vc_animate-css' );
		$main_class .= ' '.$atts['css_animation'] . ' wpb_animate_when_almost_visible wpb_'.$atts['css_animation'];
		
		if(isset($atts['page_title_animation_delay']) && !empty($atts['page_title_animation_delay'])) {
			$inner_styles .= '.'.$wrap_class.' {
												-webkit-animation-delay: '.esc_attr((int)$atts['page_title_animation_delay']).'ms;
												-moz-animation-delay: '.esc_attr((int)$atts['page_title_animation_delay']).'ms;
												-o-animation-delay: '.esc_attr((int)$atts['page_title_animation_delay']).'ms;
												animation-delay: '.esc_attr((int)$atts['page_title_animation_delay']).'ms;
											}';
		}
	}
	
	$output .= '<div class="custom-title-title '.esc_attr($main_class).'"'.($styles ? ' style ="'.esc_attr($styles).'"' : '').' '.$interactions_data.'>';

		if($atts['type'] == 'simple') {
			$output .= '<'.$atts['page_title_tag'].' '.$inner_class.'>';
		} else {
			$output .= '<div class="custom-title-rich '.esc_attr($uniqid).'">';
		}
		
		if($atts['type'] == 'simple' && !empty($atts['text'])) {
			$output .= $atts['text'];
		} elseif($atts['type'] == 'rich') {
			$output .= do_shortcode($atts['content']);
		} else {
			$output .= 'Custom Title';
		}
		
		if($atts['type'] == 'simple') {
			$output .= '</'.$atts['page_title_tag'].'>';
		} else {
			$output .= '</div>';
		}

	$output .= '</div>';
	
	if(!empty($inner_styles)) {
		$output .=	'<style>'.$inner_styles.'</style>';
	}
	
	return $output;
}

function thegem_title_excerpt_shortcode($atts, $content) {
	$output = $inner_styles = $inner_class = $wrap_class = '';
	$atts = shortcode_atts( array(
		'use_shortcode_data' => 0,
		'content' => $content,
		'color' => '',
		'alignment' => '',
		'width' => '',
		'margin_top' => '',
		'margin_bottom' => '',
		'extra_class' => '',
		'v' => '',
		/**********/
		'margin_top_tablet' => '',
		'margin_top_mobile' => '',
		'margin_bottom_tablet' => '',
		'margin_bottom_mobile' => '',
		'page_excerpt_tag' => 'div',
		'page_excerpt_div_style' => 'styled-subtitle',
		'page_excerpt_text_weight' => 'default',
		'page_excerpt_text_style' => '',
		'page_excerpt_text_decoration' => '',
		'page_excerpt_custom_font_size' => '',
		'page_excerpt_font_size' => '',
		'page_excerpt_line_height' => '',
		'page_excerpt_letter_spacing' => '',
		'page_excerpt_text_transform' => 'none',
		'page_excerpt_responsive_font' => '',
		'page_excerpt_font_size_tablet' => '',
		'page_excerpt_line_height_tablet' => '',
		'page_excerpt_letter_spacing_tablet' => '',
		'page_excerpt_font_size_mobile' => '',
		'page_excerpt_line_height_mobile' => '',
		'page_excerpt_letter_spacing_mobile' => '',
		'page_excerpt_google_font' => '',
		'page_excerpt_custom_fonts' => '',
		'page_excerpt_letter_animation_type' => '',
		'css_animation' => '',
		'page_excerpt_animation_speed' => '',
		'page_excerpt_animation_delay' => '',
		'page_excerpt_animation_interval' => '',
		'page_excerpt_disable_desktop' => '',
		'page_excerpt_disable_tablet' => '',
		'page_excerpt_disable_mobile' => '',
			/*interactions*/
		'interactions_enabled' => '',
		'vertical_scroll' => '',
		'vertical_scroll_direction' => 'vertical_scroll_direction_up',
		'vertical_scroll_speed' => '3',
		'vertical_scroll_viewport_bottom' => '0',
		'vertical_scroll_viewport_top' => '100',
		'horizontal_scroll' => '',
		'horizontal_scroll_direction' => 'horizontal_scroll_direction_left',
		'horizontal_scroll_speed' => '3',
		'horizontal_scroll_viewport_bottom' => '0',
		'horizontal_scroll_viewport_top' => '100',
		'mouse_effects' => '',
		'mouse_effects_direction' => 'mouse_effects_direction_opposite',
		'mouse_effects_speed' => '3',
		'disable_effects_desktop' => '',
		'disable_effects_tablet' => '',
		'disable_effects_mobile' => ''
			/*end interactions*/
	), $atts, 'gem_title_excerpt' );
	
	$uniq_inner_class = uniqid('thegem-title-excerpt-inner-');
	$inner_class = ' '.$uniq_inner_class;
	
	if(!empty($atts['extra_class'])) {
		$inner_class .= ' '.$atts['extra_class'];
	}
	
	if(empty($atts['v']) && empty($atts['color'])) {
		$global_data = thegem_get_sanitize_options_page_data(get_option('thegem_options_page_settings_global'), 'global');
		$atts['color'] = $global_data['title_excerpt_text_color'];
	}
	global $thegem_page_title_template_data;
	$page_data = $thegem_page_title_template_data;
	if(!empty($thegem_page_title_template_data['title_use_page_settings'])) {
		$atts = array_merge($atts, array(
			'color' => $page_data['title_excerpt_text_color'] ? $page_data['title_excerpt_text_color'] : $atts['color'],
		));
	}
	if(!$atts['use_shortcode_data'] && !empty($page_data['title_excerpt'])) {
		$atts['content'] = $page_data['title_excerpt'];
	}
	
	if(isset($atts['page_excerpt_div_style']) && !empty($atts['page_excerpt_div_style'])) {
		$inner_class .= ' '.$atts['page_excerpt_div_style'];
	} else {
		$inner_class .= ' styled-subtitle';
	}
	
	if(isset($atts['page_excerpt_text_weight']) && $atts['page_excerpt_text_weight'] == 'light') {
		$inner_class .= ' '.$atts['page_excerpt_text_weight'];
	}
	
	if(isset($atts['margin_top']) && !empty($atts['margin_top']) || strcmp($atts['margin_top'], '0') === 0) {
		$inner_styles .= '.'.esc_attr($uniq_inner_class).'.custom-title-excerpt {margin-top: '.intval($atts['margin_top']).'px;}';
	}
	if(isset($atts['margin_top_tablet']) && !empty($atts['margin_top_tablet']) || strcmp($atts['margin_top_tablet'], '0') === 0) {
		$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {.'.esc_attr($uniq_inner_class).'.custom-title-excerpt {margin-top: '.intval($atts['margin_top_tablet']).'px;}}';
	}
	if(isset($atts['margin_top_mobile']) && !empty($atts['margin_top_mobile']) || strcmp($atts['margin_top_mobile'], '0') === 0) {
		$inner_styles .= '@media screen and (max-width: 767px) {.'.esc_attr($uniq_inner_class).'.custom-title-excerpt {margin-top: '.intval($atts['margin_top_mobile']).'px;}}';
	}
	if(isset($atts['margin_bottom']) && !empty($atts['margin_bottom']) || strcmp($atts['margin_bottom'], '0') === 0) {
		$inner_styles .= '.'.esc_attr($uniq_inner_class).'.custom-title-excerpt {margin-bottom: '.intval($atts['margin_bottom']).'px;}';
	}
	if(isset($atts['margin_bottom_tablet']) && !empty($atts['margin_bottom_tablet']) || strcmp($atts['margin_bottom_tablet'], '0') === 0) {
		$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {.'.esc_attr($uniq_inner_class).'.custom-title-excerpt {margin-bottom: '.intval($atts['margin_bottom_tablet']).'px;}}';
	}
	if(isset($atts['margin_bottom_mobile']) && !empty($atts['margin_bottom_mobile']) || strcmp($atts['margin_bottom_mobile'], '0') === 0) {
		$inner_styles .= '@media screen and (max-width: 767px) {.'.esc_attr($uniq_inner_class).'.custom-title-excerpt {margin-bottom: '.intval($atts['margin_bottom_mobile']).'px;}}';
	}

	if(isset($atts['page_excerpt_text_style']) && !empty($atts['page_excerpt_text_style'])) {
		$inner_styles .= '.'.esc_attr($uniq_inner_class).', .'.esc_attr($uniq_inner_class).'.light {font-style: italic;}';
	}
	if(isset($atts['page_excerpt_text_decoration']) && !empty($atts['page_excerpt_text_decoration'])) {
		$inner_styles .= '.'.esc_attr($uniq_inner_class).', .'.esc_attr($uniq_inner_class).'.light {text-decoration: underline;}';
	}
	
	if(!empty($atts['page_excerpt_custom_font_size'])) {
		if(isset($atts['page_excerpt_font_size']) && !empty($atts['page_excerpt_font_size'])) {
			$inner_styles .= '.'.esc_attr($uniq_inner_class).' {font-size: '.esc_attr($atts['page_excerpt_font_size']).'px;}';
		}
		if(isset($atts['page_excerpt_line_height']) && !empty($atts['page_excerpt_line_height'])) {
			$inner_styles .= '.'.esc_attr($uniq_inner_class).' {line-height: '.esc_attr($atts['page_excerpt_line_height']).'px;}';
		}
		if(isset($atts['page_excerpt_letter_spacing']) && !empty($atts['page_excerpt_letter_spacing']) || strcmp($atts['page_excerpt_letter_spacing'], '0') === 0) {
			$inner_styles .= '.'.esc_attr($uniq_inner_class).' {letter-spacing: '.esc_attr($atts['page_excerpt_letter_spacing']).'px;}';
		}
		if(isset($atts['page_excerpt_text_transform']) && !empty($atts['page_excerpt_text_transform'])) {
			$inner_styles .= '.'.esc_attr($uniq_inner_class).', .'.esc_attr($uniq_inner_class).'.light {text-transform: '.esc_attr($atts['page_excerpt_text_transform']).';}';
		}
	}
	
	if(!empty($atts['page_excerpt_responsive_font'])) {
		if(isset($atts['page_excerpt_font_size_tablet']) && !empty($atts['page_excerpt_font_size_tablet'])) {
			$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {.'.esc_attr($uniq_inner_class).' {font-size: '.esc_attr($atts['page_excerpt_font_size_tablet']).'px;}}';
		}
		if(isset($atts['page_excerpt_line_height_tablet']) && !empty($atts['page_excerpt_line_height_tablet'])) {
			$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {.'.esc_attr($uniq_inner_class).' {line-height: '.esc_attr($atts['page_excerpt_line_height_tablet']).'px;}}';
		}
		if(isset($atts['page_excerpt_letter_spacing_tablet']) && !empty($atts['page_excerpt_letter_spacing_tablet']) || strcmp($atts['page_excerpt_letter_spacing_tablet'], '0') === 0) {
			$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {.'.esc_attr($uniq_inner_class).' {letter-spacing: '.esc_attr($atts['page_excerpt_letter_spacing_tablet']).'px;}}';
		}
		if(isset($atts['page_excerpt_font_size_mobile']) && !empty($atts['page_excerpt_font_size_mobile'])) {
			$inner_styles .= '@media screen and (max-width: 767px) {.'.esc_attr($uniq_inner_class).' {font-size: '.esc_attr($atts['page_excerpt_font_size_mobile']).'px;}}';
		}
		if(isset($atts['page_excerpt_line_height_mobile']) && !empty($atts['page_excerpt_line_height_mobile'])) {
			$inner_styles .= '@media screen and (max-width: 767px) {.'.esc_attr($uniq_inner_class).' {line-height: '.esc_attr($atts['page_excerpt_line_height_mobile']).'px;}}';
		}
		if(isset($atts['page_excerpt_letter_spacing_mobile']) && !empty($atts['page_excerpt_letter_spacing_mobile']) || strcmp($atts['page_excerpt_letter_spacing_mobile'], '0') === 0) {
			$inner_styles .= '@media screen and (max-width: 767px) {.'.esc_attr($uniq_inner_class).' {letter-spacing: '.esc_attr($atts['page_excerpt_letter_spacing_mobile']).'px;}}';
		}
	}
	
	if(isset($atts['page_excerpt_disable_desktop']) && !empty($atts['page_excerpt_disable_desktop'])) {
		$inner_styles .= '@media screen and (min-width: 1024px) {.'.esc_attr($uniq_inner_class).' {display: none;}}';
	}
	if(isset($atts['page_excerpt_disable_tablet']) && !empty($atts['page_excerpt_disable_tablet'])) {
		$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {.'.esc_attr($uniq_inner_class).' {display: none;}}';
	}
	if(isset($atts['page_excerpt_disable_mobile']) && !empty($atts['page_excerpt_disable_mobile'])) {
		$inner_styles .= '@media screen and (max-width: 767px) {.'.esc_attr($uniq_inner_class).' {display: none;}}';
	}
	
	if(!empty($atts['page_excerpt_custom_fonts']) && !empty($atts['page_excerpt_google_font'])) {
		$font = thegem_font_parse($atts['page_excerpt_custom_fonts']);
		$inner_styles .= '.'.$uniq_inner_class.', .'.$uniq_inner_class.'.light {'.esc_attr($font).'}';
	}
	
	$interactions_data = $before_html = $after_html = '';
	if((isset($atts['interactions_enabled']) && !empty($atts['interactions_enabled'])) || ($atts['css_animation'])) {
		$uniq_wrap_class = uniqid('thegem-title-excerpt-wrap-');
		$wrap_class .= ' '.$uniq_wrap_class;
		if(isset($atts['interactions_enabled']) && !empty($atts['interactions_enabled'])) {
			$wrap_class .= ' gem-interactions-enabled';
			$interactions_data = interactions_data_attr($atts);
		}

		if($atts['css_animation']) {
			wp_enqueue_script( 'vc_waypoints' );
			wp_enqueue_style( 'vc_animate-css' );
			$wrap_class .= ' '.$atts['css_animation'] . ' wpb_animate_when_almost_visible wpb_'.$atts['css_animation'];

			if(isset($atts['page_excerpt_animation_delay']) && !empty($atts['page_excerpt_animation_delay'])) {
				$inner_styles .= '.'.$uniq_wrap_class.' {
													-webkit-animation-delay: '.esc_attr((int)$atts['page_excerpt_animation_delay']).'ms;
													-moz-animation-delay: '.esc_attr((int)$atts['page_excerpt_animation_delay']).'ms;
													-o-animation-delay: '.esc_attr((int)$atts['page_excerpt_animation_delay']).'ms;
													animation-delay: '.esc_attr((int)$atts['page_excerpt_animation_delay']).'ms;
												}';
			}
		}
		$before_html = '<div class="'.esc_attr($wrap_class).'" '.$interactions_data.'>';
		$after_html = '</div>';
	}
	
	$output .= $before_html.'<'.(!empty($atts['page_excerpt_tag']) ? $atts['page_excerpt_tag'] : 'div').' class="custom-title-excerpt '.esc_attr($inner_class).'"';
		$styles = '';
		if($atts['alignment'] == 'right' || $atts['alignment'] == 'center') {
			$styles .= 'text-align: '.$atts['alignment'].';';
		}
		if($atts['alignment'] == 'center') {
			$styles .= 'margin-left: auto;margin-right: auto;';
		}
		if(intval($atts['width']) > 0) {
			$styles .= 'max-width: '.intval($atts['width']).'px;';
		}
		if($atts['color']) {
			$styles .= 'color: '.$atts['color'].';';
		}
		$output .= ' style="'.esc_attr($styles).'">';
		if(!empty($atts['content'])) {
			$output .= do_shortcode($atts['content']);
		} else {
			$output .= 'Custom Excerpt';
		}
	$output .= '</'.(!empty($atts['page_excerpt_tag']) ? $atts['page_excerpt_tag'] : 'div').'>'.$after_html;
	
	if(!empty($inner_styles)) {
		$output .= '<style>'.$inner_styles.'</style>';
	}
	return $output;
}

function thegem_title_icon_shortcode($atts) {
	extract(shortcode_atts(array(
		'use_shortcode_data' => 0,
		'pack' => 'material',
		'icon_elegant' => '',
		'icon_material' => 'f287',
		'icon_fontawesome' => '',
		'icon_thegem_header' => '',
		'icon_userpack' => '',
		'shape' => 'square',
		'style' => '',
		'color' => '',
		'color_2' => '',
		'background_color' => '',
		'border_color' => '',
		'size' => 'small',
		'alignment' => 'default',
		'margin_left' => 30,
		'margin_right' => 30,
		'extra_class' => '',
	), $atts, 'gem_title_icon'));
	global $thegem_page_title_template_data;
	$page_data = $thegem_page_title_template_data;
	if(!empty($thegem_page_title_template_data['title_use_page_settings']) && $page_data['title_icon']) {
		$pack = $page_data['title_icon_pack'];
		$icon_elegant = $page_data['title_icon'];
		$icon_material = $page_data['title_icon'];
		$icon_fontawesome = $page_data['title_icon'];
		$icon_thegem_header = $page_data['title_icon'];
		$icon_userpack = $page_data['title_icon'];
		$shape = $page_data['title_icon_shape'];
		$style = $page_data['title_icon_style'];
		$color = $page_data['title_icon_color'];
		$color_2 = $page_data['title_icon_color'];
		$background_color = $page_data['title_icon_background_color'];
		$border_color = $page_data['title_icon_border_color'];
		$size = $page_data['title_icon_size'];
	}
	if($pack =='elegant' && empty($icon) && $icon_elegant) {
		$icon = $icon_elegant;
	}
	if($pack =='material' && empty($icon) && $icon_material) {
		$icon = $icon_material;
	}
	if($pack =='fontawesome' && empty($icon) && $icon_fontawesome) {
		$icon = $icon_fontawesome;
	}
	if($pack =='thegem-header' && empty($icon) && $icon_thegem_header) {
		$icon = $icon_thegem_header;
	}
	if($pack =='userpack' && empty($icon) && $icon_userpack) {
		$icon = $icon_userpack;
	}
	wp_enqueue_style('icons-'.$pack);
	$shape = thegem_check_array_value(array('circle', 'square', 'romb', 'hexagon'), $shape, 'square');
	$style = thegem_check_array_value(array('', 'angle-45deg-r', 'angle-45deg-l', 'angle-90deg', 'gradient'), $style, '');
	$size = thegem_check_array_value(array('small', 'medium', 'large', 'xlarge'), $size, 'small');
	$alignment = thegem_check_array_value(array('default', 'center', 'left', 'right'), $alignment, 'default');
	$css_style_icon = '';
	$css_style_icon_background = '';
	$css_style_icon_1 = '';
	$css_style_icon_2 = '';
	$css_style_icon_3 = '';

	if($background_color) {
		$css_style_icon_background .= 'background-color: '.$background_color.';';
		if(!$border_color || !$style == 'gradient') {
			$css_style_icon .= 'border-color: '.$background_color.';';
		}
	}

	if($border_color) {
		$css_style_icon .= 'border-color: '.$border_color.';';
	}

	$simple_icon = '';
	if(!($background_color || $border_color)) {
		$simple_icon = ' gem-simple-icon';
	}

	if($color = $color) {
		$css_style_icon_1 = 'color: '.$color.';';
		if(($color_2 = $color_2) && $style) {
			$css_style_icon_2 = 'color: '.$color_2.';';
		} else {
			$css_style_icon_2 = 'color: '.$color.';';
		}
	}

	$output = '<span class="gem-icon-half-1" style="' . $css_style_icon_1 . '"><span class="back-angle">&#x' . $icon . ';</span></span>'.
	'<span class="gem-icon-half-2" style="' . $css_style_icon_2 . '"><span class="back-angle">&#x' . $icon . ';</span></span>';

	$return_html = '<div class="gem-icon gem-icon-pack-'.$pack.' gem-icon-size-'.$size.' '.$style.' gem-icon-shape-'.$shape.$simple_icon.esc_attr($extra_class).'" style="'.$css_style_icon.'">'.

		($shape == 'hexagon' ? '<div class="gem-icon-shape-hexagon-back"><div class="gem-icon-shape-hexagon-back-inner"><div class="gem-icon-shape-hexagon-back-inner-before" style="background-color: '.($border_color ? $border_color : $background_color).'"></div></div></div><div class="gem-icon-shape-hexagon-top"><div class="gem-icon-shape-hexagon-top-inner"><div class="gem-icon-shape-hexagon-top-inner-before" style="'.$css_style_icon_background.'"></div></div></div>' : '').
		'<div class="gem-icon-inner" style="'.$css_style_icon_background.'">'.
		($shape == 'romb' ? '<div class="romb-icon-conteiner">' : '').
		$output.
		($shape == 'romb' ? '</div>' : '').
		'</div>'.
		'</div>';

	$margin_styles = '';
	if($alignment == 'left') {
		$margin_styles .= 'margin-right: '.intval($margin_left).'px;';
	}
	if($alignment == 'right') {
		$margin_styles .= 'margin-left: '.intval($margin_right).'px;';
	}
	$output_html = '<div class="custom-title-icon custom-title-icon-alignment-'.esc_attr($alignment).'" style="'.$margin_styles.'">'.$return_html.'</div>';

	return $output_html;
}

function thegem_title_background_shortcode($atts, $content) {
	$satts = shortcode_atts(array(
		'use_shortcode_data' => 0,
		'fullwidth' => '',
		'container' => '',
		'color' => '',
		'background_style' => '',
		'background_position_horizontal' => 'center',
		'background_position_vertical' => 'top',
		'background_type' => '',
		'background_color' => '',
		'background_image' => '',
		'background_image_use_featured' => 0,
		'background_image_color' => '',
		'background_image_repeat' => '',
		'background_size' => 'auto',
		'background_image_position_x' => 'center',
		'background_image_position_y' => 'top',
		'background_parallax' => '',
		'background_image_overlay' => '',
		'video_background_type' => 'youtube',
		'video_background_src' => '',
		'video_background_acpect_ratio' => '16:9',
		'video_background_overlay_color' => '',
		'video_background_overlay_opacity' => '1',
		'video_background_poster' => '',
		'background_gradient_type' => 'linear',
		'background_gradient_angle' => '0',
		'background_gradient_position' => 'center center',
		'background_gradient_point1_color' => '',
		'background_gradient_point1_position' => '0',
		'background_gradient_point2_color' => '',
		'background_gradient_point2_position' => '100',
		'padding_top' => '80',
		'padding_top_tablet' => '',
		'padding_top_mobile' => '',
		'padding_bottom' => '80',
		'padding_bottom_tablet' => '',
		'padding_bottom_mobile' => '',
		'padding_left' => '',
		'padding_left_tablet' => '',
		'padding_left_mobile' => '',
		'padding_right' => '',
		'padding_right_tablet' => '',
		'padding_right_mobile' => '',
		'extra_class' => '',
		'ken_burns_enabled' => false,
		'ken_burns_direction' => 'zoom_in',
		'ken_burns_transition_speed' => 15000,
		'play_on_mobile' => '',
		'background_fallback' => ''
	), $atts, 'gem_title_background');
	
	$inner_styles = '';
	
	$fullwidth_uid = uniqid('fullwidth-block-');
	
	$old_version = $satts['background_type'] == '';
	$satts['background_image'] = thegem_attachment_url($satts['background_image']);
	global $thegem_page_title_template_data;
	$page_data = $thegem_page_title_template_data;
	if(!empty($thegem_page_title_template_data['title_use_page_settings'])) {
		if(!$old_version) {
			if($page_data['title_background_type'] == 'color' && !empty($page_data['title_background_color'])) {
				$satts['background_type'] = $page_data['title_background_type'];
				$satts['background_color'] = $page_data['title_background_color'];
			}
			if($page_data['title_background_type'] == 'image' && !empty($page_data['title_background_image'])) {
				$satts['background_type'] = $page_data['title_background_type'];
				$satts['background_color'] = $page_data['title_background_color'];
				$satts['background_image'] = $page_data['title_background_image'];
				$satts['background_image_color'] = $page_data['title_background_image_color'];
				$satts['background_image_repeat'] = $page_data['title_background_image_repeat'];
				$satts['background_size'] = $page_data['title_background_size'];
				$satts['background_image_position_x'] = $page_data['title_background_position_x'];
				$satts['background_image_position_y'] = $page_data['title_background_position_y'];
				$satts['ken_burns_enabled'] = $page_data['title_background_effect'] == 'ken_burns';
				$satts['ken_burns_direction'] = empty($page_data['title_background_ken_burns_direction']) ? $satts['ken_burns_direction'] : $page_data['title_background_ken_burns_direction'];
				$satts['ken_burns_transition_speed'] = empty($page_data['ken_burns_transition_speed']) ? $satts['ken_burns_transition_speed'] : $page_data['ken_burns_transition_speed'];
				$satts['background_parallax'] = $page_data['title_background_effect'] == 'parallax';
				$satts['background_image_overlay'] = $page_data['title_background_image_overlay'];
				if(!empty($satts['background_image_use_featured'])) {
					$pid = get_queried_object_id();
					$thumbnail_id = 0;
					if(is_singular()) {
						$thumbnail_id = get_post_thumbnail_id($pid);
					}
					if(is_tax('product_cat')) {
						$attachment_id = get_term_meta( $pid, 'thumbnail_id', true );
						if($attachment_id) {
							$thumbnail_id = $attachment_id;
						}
					}
					if(!empty($thumbnail_id)) {
						$satts['background_image'] = thegem_attachment_url($thumbnail_id);
					}
				}
			}
			if($page_data['title_background_type'] == 'video' && !empty($page_data['title_background_video'])) {
				$satts['background_type'] = $page_data['title_background_type'];
				$satts['video_background_type'] = $page_data['title_background_video_type'];
				$satts['video_background_src'] = $page_data['title_background_video'];
				$satts['video_background_acpect_ratio'] = $page_data['title_background_video_aspect_ratio'];
				$satts['video_background_overlay_color'] = $page_data['title_background_video_overlay'];
				$satts['video_background_overlay_opacity'] = $page_data['title_video_overlay_opacity'];
				$satts['video_background_poster'] = $page_data['title_background_video_poster'];
			}
			if($page_data['title_background_type'] == 'gradient') {
				$satts['background_type'] = $page_data['title_background_type'];
				$satts['background_gradient_type'] = $page_data['title_background_gradient_type'];
				$satts['background_gradient_angle'] = $page_data['title_background_gradient_angle'];
				$satts['background_gradient_position'] = $page_data['title_background_gradient_position'];
				$satts['background_gradient_point1_color'] = $page_data['title_background_gradient_point1_color'];
				$satts['background_gradient_point1_position'] = $page_data['title_background_gradient_point1_position'];
				$satts['background_gradient_point2_color'] = $page_data['title_background_gradient_point2_color'];
				$satts['background_gradient_point2_position'] = $page_data['title_background_gradient_point2_position'];
			}
		} else {
			if($page_data['title_background_type'] == 'color' && !empty($page_data['title_background_color'])) {
				$satts['background_color'] = $page_data['title_background_color'];
			}
			if($page_data['title_background_type'] == 'image' && !empty($page_data['title_background_image'])) {
				$satts['background_color'] = $page_data['title_background_image_color'];
				$satts['background_image'] = $page_data['title_background_image'];
				$satts['background_image_color'] = $page_data['title_background_image_color'];
				$satts['background_parallax'] = $page_data['title_background_effect'] == 'parallax';
				$satts['background_image_overlay'] = $page_data['title_background_image_overlay'];
				$satts['ken_burns_enabled'] = $page_data['title_background_effect'] == 'ken_burns';
				$satts['ken_burns_direction'] = empty($page_data['title_background_ken_burns_direction']) ? $satts['ken_burns_direction'] : $page_data['title_background_ken_burns_direction'];
				$satts['ken_burns_transition_speed'] = empty($page_data['ken_burns_transition_speed']) ? $satts['ken_burns_transition_speed'] : $page_data['ken_burns_transition_speed'];
				$satts['background_image_position_x'] = $satts['background_position_horizontal'];
				$satts['background_image_position_y'] = $satts['background_position_vertical'];
			}
			if($page_data['title_background_type'] == 'video' && !empty($page_data['title_background_video'])) {
				$satts['video_background_type'] = $page_data['title_background_video_type'];
				$satts['video_background_src'] = $page_data['title_background_video'];
				$satts['video_background_acpect_ratio'] = $page_data['title_background_video_aspect_ratio'];
				$satts['video_background_overlay_color'] = $page_data['title_background_video_overlay'];
				$satts['video_background_overlay_opacity'] = $page_data['title_video_overlay_opacity'];
				$satts['video_background_poster'] = $page_data['title_background_video_poster'];
			}
		}
	}

	if($old_version) {
		if($satts['background_style'] == 'repeat') {
			$satts['background_image_repeat'] = true;
		} elseif($satts['background_style'] == 'cover') {
			$satts['background_size'] = 'cover';
		} elseif($satts['background_style'] == 'contain') {
			$satts['background_size'] = 'contain';
		}
		if(!empty($satts['background_image'])) {
			$satts['background_type'] = 'image';
		}
		if(!empty($satts['video_background_src'])) {
			$satts['background_type'] = 'video';
		}
		if(empty($satts['background_type'])) {
			$satts['background_type'] = 'color';
		}
	}
	$satts['background_image'] = empty($satts['background_image']) ? THEGEM_THEME_URI . '/images/dummy.png' : $satts['background_image'];
	$css_style = '';
	if($satts['color']) {
		$css_style .= 'color: '.$satts['color'].';';
	}
	$background_image_style = '';
	$video = '';
	$overlay = '';
	if($satts['background_type'] == 'image') {
		if(!empty($satts['background_image'])) {
			$background_image_style .= 
				'background-image: url(\''.$satts['background_image'].'\');'.
				'background-repeat: '.($satts['background_image_repeat'] ? '' : 'no-').'repeat;'.
				//'background-color: '.$satts['background_image_color'].';'.
				'background-position-x: '.$satts['background_image_position_x'].';'.
				'background-position-y: '.$satts['background_image_position_y'].';'.
				'background-size: '.$satts['background_size'].';';
				$css_style .= 'background-color: '.$satts['background_image_color'].';';
			if(!empty($satts['background_image_overlay'])) {
				$overlay = '<div class="page-title-background-overlay" style="background-color: '.esc_attr($satts['background_image_overlay']).'"></div>';
			}
		}
	} elseif($satts['background_type'] == 'gradient') {
		if($satts['background_gradient_type'] == 'radial') {
			$background_image_style .= 
				'background-image: radial-gradient(at '.$satts['background_gradient_position'].','.
				$satts['background_gradient_point1_color'].' '.$satts['background_gradient_point1_position'].'%,'.
				$satts['background_gradient_point2_color'].' '.$satts['background_gradient_point2_position'].'%);';
		} else {
			$background_image_style .= 
				'background-image: linear-gradient('.$satts['background_gradient_angle'].'deg,'.
				$satts['background_gradient_point1_color'].' '.$satts['background_gradient_point1_position'].'%,'.
				$satts['background_gradient_point2_color'].' '.$satts['background_gradient_point2_position'].'%);';
		}
	} elseif($satts['background_type'] == 'video') {
		$video = thegem_video_background($satts['video_background_type'], $satts['video_background_src'], $satts['video_background_acpect_ratio'], false, $satts['video_background_overlay_color'], $satts['video_background_overlay_opacity'], thegem_attachment_url($satts['video_background_poster']), $satts['play_on_mobile'], thegem_attachment_url($satts['background_fallback']), $satts['background_size'], $satts['background_image_position_x'], $satts['background_image_position_y']);
	} else {
		if(!empty($satts['background_color'])) {
			$css_style .= 'background-color: '.$satts['background_color'].';';
		}
	}

	if($satts['padding_top']) {
		$inner_styles .= '#'.$fullwidth_uid.' {padding-top: '.$satts['padding_top'].'px;}';
	}
	if($satts['padding_top_tablet']) {
		$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {#'.$fullwidth_uid.' {padding-top: '.$satts['padding_top_tablet'].'px;}}';
	}
	if($satts['padding_top_mobile']) {
		$inner_styles .= '@media screen and (max-width: 767px) {#'.$fullwidth_uid.' {padding-top: '.$satts['padding_top_mobile'].'px;}}';
	}
	if($satts['padding_bottom']) {
		$inner_styles .= '#'.$fullwidth_uid.' {padding-bottom: '.$satts['padding_bottom'].'px;}';
	}
	if($satts['padding_bottom_tablet']) {
		$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {#'.$fullwidth_uid.' {padding-bottom: '.$satts['padding_bottom_tablet'].'px;}}';
	}
	if($satts['padding_bottom_mobile']) {
		$inner_styles .= '@media screen and (max-width: 767px) {#'.$fullwidth_uid.' {padding-bottom: '.$satts['padding_bottom_mobile'].'px;}}';
	}
	if($satts['padding_left']) {
		$inner_styles .= '#'.$fullwidth_uid.' {padding-left: '.$satts['padding_left'].'px;}';
	}
	if($satts['padding_left_tablet']) {
		$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {#'.$fullwidth_uid.' {padding-left: '.$satts['padding_left_tablet'].'px;}}';
	}
	if($satts['padding_left_mobile']) {
		$inner_styles .= '@media screen and (max-width: 767px) {#'.$fullwidth_uid.' {padding-left: '.$satts['padding_left_mobile'].'px;}}';
	}
	if($satts['padding_right']) {
		$inner_styles .= '#'.$fullwidth_uid.' {padding-right: '.$satts['padding_right'].'px;}';
	}
	if($satts['padding_right_tablet']) {
		$inner_styles .= '@media screen and (max-width: 1023px) and (min-width: 768px) {#'.$fullwidth_uid.' {padding-right: '.$satts['padding_right_tablet'].'px;}}';
	}
	if($satts['padding_right_mobile']) {
		$inner_styles .= '@media screen and (max-width: 767px) {#'.$fullwidth_uid.' {padding-right: '.$satts['padding_right_mobile'].'px;}}';
	}

	if ($satts['background_parallax']) {
		wp_enqueue_script('thegem-parallax-vertical');
	}

	$html_js = '';
	if($satts['fullwidth']) {
		$html_js = '<script type="text/javascript">if (typeof(gem_fix_fullwidth_position) == "function") { gem_fix_fullwidth_position(document.getElementById("' . $fullwidth_uid . '")); }</script>';
	}

	if ($satts['ken_burns_enabled']) {
		wp_enqueue_style('thegem-ken-burns');
		wp_enqueue_script('thegem-ken-burns');

		$ken_burns_classes[] = 'thegem-ken-burns-bg';
		$ken_burns_classes[] = $satts['ken_burns_direction'] == 'zoom_in' ? 'thegem-ken-burns-zoom-in' : 'thegem-ken-burns-zoom-out';

		$ken_burns_classes = ' '.implode(' ', $ken_burns_classes);
		$background_image_style .= ' animation-duration: '.(!empty($satts['ken_burns_transition_speed']) ? esc_attr(trim($satts['ken_burns_transition_speed'])) : 15000).'ms;';
	}

	$return_html = '<div id="'.$fullwidth_uid.'" class="custom-title-background'.($satts['fullwidth'] ? ' fullwidth-block' : '') . ($satts['extra_class'] ? ' '.esc_attr($satts['extra_class']) : '') . ($satts['background_parallax'] ? ' fullwidth-block-parallax-vertical' : '') .($satts['ken_burns_enabled'] ? ' custom-title-ken-burns-block' : ''). ' clearfix" ' . ' style="'.$css_style.'">' .  $html_js . ($background_image_style != '' ? '<div class="fullwidth-block-background'.(!empty($ken_burns_classes) ? $ken_burns_classes : '').'" style="'.  $background_image_style.'"></div>' : '') .$overlay.$video. '<div class="fullwidth-block-inner">'.  ($satts['container'] ? '<div class="container">' : '').do_shortcode($content).($satts['container'] ? '</div>' : '').'</div></div>'.(!empty($inner_styles) ? '<style>'.$inner_styles.'</style>' : '');

	return $return_html;
}

if(class_exists('WPBakeryShortCodesContainer')) {
	class WPBakeryShortCode_gem_title_background extends WPBakeryShortCodesContainer {}
}