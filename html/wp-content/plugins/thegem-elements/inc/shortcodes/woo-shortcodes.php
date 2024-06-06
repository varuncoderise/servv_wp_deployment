<?php

function thegem_add_woocommerce_shortcodes($shortcodes) {
	if(thegem_is_plugin_active('woocommerce/woocommerce.php')) {
		add_filter( 'vc_autocomplete_gem_product_categories_ids_callback', 'TheGemProductCategoryCategoryAutocompleteSuggester', 10, 1 );
		add_filter( 'vc_autocomplete_gem_product_categories_ids_render', 'TheGemProductCategoryCategoryRenderByIdExact', 10, 1 );
		$shortcodes['product_categories'] = array(
			'name' => __( 'Product categories (Legacy)', 'thegem' ),
			'base' => 'gem_product_categories',
			'icon' => 'thegem-icon-wpb-ui-product-categories',
			'category' => array(__( 'TheGem', 'thegem' ), esc_html__( 'WooCommerce', 'js_composer' )),
			'description' => __( 'Display product categories loop', 'thegem' ),
			'deprecated' => 5,
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Number', 'thegem' ),
					'param_name' => 'number',
					'description' => __( 'The `number` field is used to display the number of products.', 'thegem' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Order by', 'thegem' ),
					'param_name' => 'orderby',
					'value' => array(
						'',
						__( 'Date', 'thegem' ) => 'date',
						__( 'ID', 'thegem' ) => 'ID',
						__( 'Author', 'thegem' ) => 'author',
						__( 'Title', 'thegem' ) => 'title',
						__( 'Modified', 'thegem' ) => 'modified',
						__( 'Random', 'thegem' ) => 'rand',
						__( 'Comment count', 'thegem' ) => 'comment_count',
						__( 'Menu order', 'thegem' ) => 'menu_order',
					),
					'save_always' => true,
					'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'thegem' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Sort order', 'thegem' ),
					'param_name' => 'order',
					'value' => array(
						'',
						__( 'Descending', 'thegem' ) => 'DESC',
						__( 'Ascending', 'thegem' ) => 'ASC',
					),
					'save_always' => true,
					'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'thegem' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Columns', 'thegem' ),
					'value' => 4,
					'param_name' => 'columns',
					'save_always' => true,
					'description' => __( 'How much columns grid', 'thegem' ),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Number', 'thegem' ),
					'param_name' => 'hide_empty',
					'description' => __( 'Hide empty', 'thegem' ),
				),
				array(
					'type' => 'autocomplete',
					'heading' => __( 'Categories', 'thegem' ),
					'param_name' => 'ids',
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
					),
					'save_always' => true,
					'description' => __( 'List of product categories', 'thegem' ),
				),
			),
		);

		add_filter( 'vc_autocomplete_gem_product_grid_grid_categories_callback', 'TheGemProductCategoryCategoryAutocompleteSuggesterBySlug', 10, 1 );
		add_filter( 'vc_autocomplete_gem_product_grid_grid_categories_render', 'TheGemProductCategoryCategoryRenderBySlugExact', 10, 1 );
		$shortcodes['product_grid'] = array(
			'name' => __( 'Products Classic Grid', 'thegem' ),
			'base' => 'gem_product_grid',
			'icon' => 'thegem-icon-wpb-ui-product-grid',
			'category' => array(__( 'TheGem', 'thegem' ), esc_html__( 'WooCommerce', 'js_composer' )),
			'description' => __( 'Display products grid', 'thegem' ),
			'deprecated' => 5,
			'params' => array(
				array(
					'type' => 'dropdown',
					'heading' => __('Layout', 'thegem'),
					'param_name' => 'grid_layout',
					'value' => array(__('2x columns', 'thegem') => '2x', __('3x columns', 'thegem') => '3x', __('4x columns', 'thegem') => '4x', __('100% width', 'thegem') => '100%', __('1x column list', 'thegem') => '1x')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Layout Version', 'thegem'),
					'param_name' => 'grid_layout_version',
					'value' => array(__('Fullwidth', 'thegem') => 'fullwidth', __('With Sidebar', 'thegem') => 'sidebar'),
					'dependency' => array(
						'element' => 'grid_layout',
						'value' => array('1x')
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Caption Position', 'thegem'),
					'param_name' => 'grid_caption_position',
					'value' => array(__('Right', 'thegem') => 'right', __('Left', 'thegem') => 'left', __('Zigzag', 'thegem') => 'zigzag'),
					'dependency' => array(
						'element' => 'grid_layout',
						'value' => array('1x')
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'thegem'),
					'param_name' => 'grid_style',
					'value' => array(__('Justified Grid', 'thegem') => 'justified', __('Masonry Grid ', 'thegem') => 'masonry', __('Metro Style', 'thegem') => 'metro'),
					'dependency' => array(
						'element' => 'grid_layout',
						'value' => array('2x', '3x', '4x', '100%'),
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Columns 100% Width (1920x Screen)', 'thegem'),
					'param_name' => 'grid_fullwidth_columns',
					'value' => array(__('4 Columns', 'thegem') => '4', __('5 Columns', 'thegem') => '5', __('6 Columns', 'thegem') => '6'),
					'dependency' => array(
						'element' => 'grid_layout',
						'value' => array('100%')
					),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Gaps Size', 'thegem'),
					'param_name' => 'grid_gaps_size',
					'std' => 42,
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Display Titles', 'thegem'),
					'param_name' => 'grid_display_titles',
					'value' => array(__('On Page', 'thegem') => 'page', __('On Hover', 'thegem') => 'hover')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Hover Type', 'thegem'),
					'param_name' => 'grid_hover',
					'value' => array(__('Cyan Breeze', 'thegem') => 'default', __('Zooming White', 'thegem') => 'zooming-blur', __('Horizontal Sliding', 'thegem') => 'horizontal-sliding', __('Vertical Sliding', 'thegem') => 'vertical-sliding', __('Gradient', 'thegem') => 'gradient', __('Circular Overlay', 'thegem') => 'circular'),
					'dependency' => array(
						'element' => 'grid_display_titles',
						'value' => array('hover')
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Hover Type', 'thegem'),
					'param_name' => 'grid_hover_title_on_page',
					'value' => array(__('Show next product image', 'thegem') => 'default', __('Gradient', 'thegem') => 'gradient', __('Circular Overlay', 'thegem') => 'circular'),
					'dependency' => array(
						'element' => 'grid_display_titles',
						'value' => array('page')
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Background Style', 'thegem'),
					'param_name' => 'grid_background_style',
					'value' => array(__('White', 'thegem') => 'white', __('Grey', 'thegem') => 'gray', __('Dark', 'thegem') => 'dark'),
					'dependency' => array(
						'callback' => 'display_titles_hover_callback'
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Title Style', 'thegem'),
					'param_name' => 'grid_title_style',
					'value' => array(__('Light', 'thegem') => 'light', __('Dark', 'thegem') => 'dark'),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Pagination', 'thegem'),
					'param_name' => 'grid_pagination',
					'value' => array(__('Normal', 'thegem') => 'normal', __('Load More ', 'thegem') => 'more', __('Infinite Scroll ', 'thegem') => 'scroll')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Loading animation', 'thegem'),
					'param_name' => 'loading_animation',
					'std' => 'move-up',
					'value' => array(__('Disabled', 'thegem') => 'disabled', __('Bounce', 'thegem') => 'bounce', __('Move Up', 'thegem') => 'move-up', __('Fade In', 'thegem') => 'fade-in', __('Fall Perspective', 'thegem') => 'fall-perspective', __('Scale', 'thegem') => 'scale', __('Flip', 'thegem') => 'flip'),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Items per page', 'thegem'),
					'param_name' => 'grid_items_per_page',
					'std' => '8'
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Product Separator', 'thegem'),
					'param_name' => 'grid_item_separator',
					'value' => array(__('Yes', 'thegem') => '1'),
					'dependency' => array(
						'callback' => 'item_separator_callback'
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Disable highlighted products view', 'thegem'),
					'param_name' => 'grid_ignore_highlights',
					'value' => array(__('Yes', 'thegem') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Disable sharing buttons', 'thegem'),
					'param_name' => 'grid_disable_socials',
					'value' => array(__('Yes', 'thegem') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Activate Filter', 'thegem'),
					'param_name' => 'grid_with_filter',
					'value' => array(__('Yes', 'thegem') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Show only "featured" products', 'thegem'),
					'param_name' => 'gem_product_grid_featured_products',
					'value' => array(
						__('Yes', 'thegem') => 'yes',
						__('Hide "New" label', 'thegem') => 'hide'
					)
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Show only "on sale" products', 'thegem'),
					'param_name' => 'gem_product_grid_onsale_products',
					'value' => array(
						__('Yes', 'thegem') => 'yes',
						__('Hide "Sale" label', 'thegem') => 'hide',
					)
				),
				array(
					'type' => 'textfield',
					'heading' => __('Title', 'thegem'),
					'param_name' => 'grid_title'
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Activate Sorting', 'thegem'),
					'param_name' => 'grid_sorting',
					'value' => array(__('Yes', 'thegem') => '1')
				),
				array(
					'type' => 'autocomplete',
					'heading' => __( 'Product categories', 'thegem' ),
					'param_name' => 'grid_categories',
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
					),
					'save_always' => true,
					'description' => __( 'List of product categories', 'thegem' ),
					'group' =>__('Select Product Categories', 'thegem'),
				),

				array(
					'type' => 'textfield',
					'heading' => __('Button Text', 'thegem'),
					'param_name' => 'button_text',
					'group' => __('Load More Button', 'thegem'),
					'std' => __('Load More', 'thegem'),
					'dependency' => array(
						'element' => 'grid_pagination',
						'value' => array('more')
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'thegem'),
					'param_name' => 'button_style',
					'value' => array(__('Flat', 'thegem') => 'flat', __('Outline', 'thegem') => 'outline'),
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'grid_pagination',
						'value' => array('more')
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Size', 'thegem'),
					'param_name' => 'button_size',
					'value' => array(__('Tiny', 'thegem') => 'tiny', __('Small', 'thegem') => 'small', __('Medium', 'thegem') => 'medium', __('Large', 'thegem') => 'large', __('Giant', 'thegem') => 'giant'),
					'std' => 'medium',
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'grid_pagination',
						'value' => array('more')
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Text weight', 'thegem'),
					'param_name' => 'button_text_weight',
					'value' => array(__('Normal', 'thegem') => 'normal', __('Thin', 'thegem') => 'thin'),
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'grid_pagination',
						'value' => array('more')
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('No uppercase', 'thegem'),
					'param_name' => 'button_no_uppercase',
					'value' => array(__('Yes', 'thegem') => '1'),
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'grid_pagination',
						'value' => array('more')
					),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Border radius', 'thegem'),
					'param_name' => 'button_corner',
					'std' => 25,
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'grid_pagination',
						'value' => array('more')
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Border width', 'thegem'),
					'param_name' => 'button_border',
					'value' => array(1, 2, 3, 4, 5, 6),
					'std' => 2,
					'dependency' => array(
						'element' => 'button_style',
						'value' => array('outline')
					),
					'group' => __('Load More Button', 'thegem'),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Text color', 'thegem'),
					'param_name' => 'button_text_color',
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'grid_pagination',
						'value' => array('more')
					),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Hover text color', 'thegem'),
					'param_name' => 'button_hover_text_color',
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'grid_pagination',
						'value' => array('more')
					),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Background color', 'thegem'),
					'param_name' => 'button_background_color',
					'dependency' => array(
						'element' => 'button_style',
						'value' => array('flat')
					),
					'std' => '#00bcd5',
					'group' => __('Load More Button', 'thegem'),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Hover background color', 'thegem'),
					'param_name' => 'button_hover_background_color',
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'grid_pagination',
						'value' => array('more')
					),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Border color', 'thegem'),
					'param_name' => 'button_border_color',
					'dependency' => array(
						'element' => 'button_style',
						'value' => array('outline')
					),
					'group' => __('Load More Button', 'thegem'),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Hover border color', 'thegem'),
					'param_name' => 'button_hover_border_color',
					'dependency' => array(
						'element' => 'button_style',
						'value' => array('outline')
					),
					'group' => __('Load More Button', 'thegem'),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Use Gradient Backgound Colors', 'thegem'),
					'param_name' => 'button_gradient_backgound',
					'value' => array(__('Yes', 'thegem') => '1'),
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'post_pagination',
						'value' => array('more')
					),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Background From', 'thegem'),
					"edit_field_class" => "vc_col-sm-5 vc_column",
					'param_name' => 'button_gradient_backgound_from',
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'button_gradient_backgound',
						'value' => array('1')
					)
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Background To', 'thegem'),
					"edit_field_class" => "vc_col-sm-5 vc_column",
					'group' => __('Load More Button', 'thegem'),
					'param_name' => 'button_gradient_backgound_to',
					'dependency' => array(
						'element' => 'button_gradient_backgound',
						'value' => array('1')
					)
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Hover Background From', 'thegem'),
					"edit_field_class" => "vc_col-sm-5 vc_column",
					'param_name' => 'button_gradient_backgound_hover_from',
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'button_gradient_backgound',
						'value' => array('1')
					)
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Hover Background To', 'thegem'),
					"edit_field_class" => "vc_col-sm-5 vc_column",
					'param_name' => 'button_gradient_backgound_hover_to',
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'button_gradient_backgound',
						'value' => array('1')
					)
				),
				array(
					"type" => "dropdown",
					'heading' => __('Style', 'thegem'),
					'param_name' => 'button_gradient_backgound_style',
					"edit_field_class" => "vc_col-sm-4 vc_column",
					'group' => __('Load More Button', 'thegem'),
					"value" => array(
						__('Linear', "thegem") => "linear",
						__('Radial', "thegem") => "radial",
					) ,
					"std" => 'linear',
					'dependency' => array(
						'element' => 'button_gradient_backgound',
						'value' => array('1')
					)
				),
				array(
					"type" => "dropdown",
					'heading' => __('Gradient Position', 'thegem'),
					'param_name' => 'button_gradient_radial_backgound_position',
					"edit_field_class" => "vc_col-sm-4 vc_column",
					'group' => __('Load More Button', 'thegem'),
					"value" => array(
						__('Top', "thegem") => "at top",
						__('Bottom', "thegem") => "at bottom",
						__('Right', "thegem") => "at right",
						__('Left', "thegem") => "at left",
						__('Center', "thegem") => "at center",

					) ,
					'dependency' => array(
						'element' => 'button_gradient_backgound_style',
						'value' => array(
							'radial',
						)
					)
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Swap Colors', 'thegem'),
					'param_name' => 'button_gradient_radial_swap_colors',
					"edit_field_class" => "vc_col-sm-4 vc_column",
					'group' => __('Load More Button', 'thegem'),
					'value' => array(__('Yes', 'thegem') => '1'),
					'dependency' => array(
						'element' => 'button_gradient_backgound_style',
						'value' => array(
							'radial',
						)
					)
				),


				array(
					"type" => "dropdown",
					'heading' => __('Custom Angle', 'thegem'),
					'param_name' => 'button_gradient_backgound_angle',
					'group' => __('Load More Button', 'thegem'),
					"edit_field_class" => "vc_col-sm-4 vc_column",
					"value" => array(
						__('Vertical to bottom ↓', "thegem") => "to bottom",
						__('Vertical to top ↑', "thegem") => "to top",
						__('Horizontal to left  →', "thegem") => "to right",
						__('Horizontal to right ←', "thegem") => "to left",
						__('Diagonal from left to bottom ↘', "thegem") => "to bottom right",
						__('Diagonal from left to top ↗', "thegem") => "to top right",
						__('Diagonal from right to bottom ↙', "thegem") => "to bottom left",
						__('Diagonal from right to top ↖', "thegem") => "to top left",
						__('Custom', "thegem") => "cusotom_deg",

					) ,
					'dependency' => array(
						'element' => 'button_gradient_backgound_style',
						'value' => array(
							'linear',
						)
					)
				),
				array(
					"type" => "textfield",
					'heading' => __('Angle', 'thegem'),
					'param_name' => 'button_gradient_backgound_cusotom_deg',
					'group' => __('Load More Button', 'thegem'),
					"edit_field_class" => "vc_col-sm-4 vc_column",
					'description' => __('Set value in DG 0-360', 'thegem'),
					'dependency' => array(
						'element' => 'button_gradient_backgound_style',
						'value' => array(
							'cusotom_deg',
						)
					)
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Icon pack', 'thegem'),
					'param_name' => 'button_icon_pack',
					'value' => array_merge(array(__('Elegant', 'thegem') => 'elegant', __('Material Design', 'thegem') => 'material', __('FontAwesome', 'thegem') => 'fontawesome', __('Header Icons', 'thegem') => 'thegem-header'), thegem_userpack_to_dropdown()),
					'std' => 2,
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'grid_pagination',
						'value' => array('more')
					),
				),
				array(
					'type' => 'thegem_icon',
					'heading' => __('Icon', 'thegem'),
					'param_name' => 'button_icon_elegant',
					'icon_pack' => 'elegant',
					'dependency' => array(
						'element' => 'button_icon_pack',
						'value' => array('elegant')
					),
					'group' => __('Load More Button', 'thegem'),
				),
				array(
					'type' => 'thegem_icon',
					'heading' => __('Icon', 'thegem'),
					'param_name' => 'button_icon_material',
					'icon_pack' => 'material',
					'dependency' => array(
						'element' => 'button_icon_pack',
						'value' => array('material')
					),
					'group' => __('Load More Button', 'thegem'),
				),
				array(
					'type' => 'thegem_icon',
					'heading' => __('Icon', 'thegem'),
					'param_name' => 'button_icon_fontawesome',
					'icon_pack' => 'fontawesome',
					'dependency' => array(
						'element' => 'button_icon_pack',
						'value' => array('fontawesome')
					),
					'group' => __('Load More Button', 'thegem'),
				),
				array(
					'type' => 'thegem_icon',
					'heading' => __('Icon', 'thegem'),
					'param_name' => 'button_icon_thegem_header',
					'icon_pack' => 'thegem-header',
					'dependency' => array(
						'element' => 'button_icon_pack',
						'value' => array('thegem-header')
					),
					'group' => __('Load More Button', 'thegem'),
				),
			),
			thegem_userpack_to_shortcode(array(
				array(
					'type' => 'thegem_icon',
					'heading' => __('Icon', 'thegem'),
					'param_name' => 'button_icon_userpack',
					'icon_pack' => 'userpack',
					'dependency' => array(
						'element' => 'button_icon_pack',
						'value' => array('userpack')
					),
				),
			)),
			array(
				array(
					'type' => 'dropdown',
					'heading' => __( 'Icon position', 'thegem' ),
					'param_name' => 'button_icon_position',
					'value' => array(__( 'Left', 'thegem' ) => 'left', __( 'Right', 'thegem' ) => 'right'),
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'grid_pagination',
						'value' => array('more')
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Separatot Style', 'thegem'),
					'param_name' => 'button_separator',
					'value' => array(
						__('None', 'thegem') => '',
						__('Single', 'thegem') => 'single',
						__('Square', 'thegem') => 'square',
						__('Soft Double', 'thegem') => 'soft-double',
						__('Strong Double', 'thegem') => 'strong-double',
						__('Load More', 'thegem') => 'load-more'
					),
					'std' => 'load-more',
					'group' => __('Load More Button', 'thegem'),
					'dependency' => array(
						'element' => 'grid_pagination',
						'value' => array('more')
					),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Max. row\'s height in grid (px)', 'thegem'),
					'param_name' => 'metro_max_row_height',
					'dependency' => array(
						'callback' => 'metro_max_row_height_callback'
					),
					'std' => 380,
				),
			),
		);
		/******************************************/
		add_filter('vc_autocomplete_gem_product_grid_extended_content_products_cat_callback', 'TheGemProductCategoryCategoryAutocompleteSuggesterBySlug', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_extended_content_products_cat_render', 'TheGemProductCategoryCategoryRenderBySlugExact', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_extended_content_products_attr_val_callback', 'TheGemProductAttributesAutocompleteSuggester', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_extended_content_products_attr_val_render', 'TheGemProductAttributesRenderExact', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_extended_content_products_tags_callback', 'TheGemProductTagsAutocompleteSuggester', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_extended_content_products_tags_render', 'TheGemProductTagsRenderExact', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_extended_content_products_callback', 'TheGemProductsAutocompleteSuggester', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_extended_content_products_render', 'TheGemProductsRenderExact', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_extended_exclude_products_callback', 'TheGemProductsAutocompleteSuggester', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_extended_exclude_products_render', 'TheGemProductsRenderExact', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_extended_exclude_product_terms_callback', 'TheGemTaxonomyAutocompleteSuggesterById', 10, 3);
		add_filter('vc_autocomplete_gem_product_grid_extended_exclude_product_terms_render', 'TheGemTaxonomyRenderById', 10, 1);
		add_filter('vc_form_fields_render_field_gem_product_grid_extended_filter_param', 'thegem_shortcode_product_attribute_filter_param', 10, 4);

		$wishlist_attr = array();
		if(defined('YITH_WCWL')) {
			$wishlist_attr = array_merge(
				array(
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Add to Wishlist', 'thegem'),
						'param_name' => 'add_to_wishlist_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Add to Wishlist', 'thegem'),
						'param_name' => 'product_show_wishlist',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Add to Wishlist Icon pack', 'thegem'),
						'param_name' => 'add_wishlist_icon_pack',
						'value' => array_merge(array(
							__('Elegant', 'thegem') => 'elegant',
							__('Material Design', 'thegem') => 'material',
							__('FontAwesome', 'thegem') => 'fontawesome',
							__('Header Icons', 'thegem') => 'thegem-header',
							__('Additional', 'thegem') => 'thegemdemo'),
							thegem_userpack_to_dropdown()
						),
						'std' => 'material',
						'dependency' => array(
							'element' => 'product_show_wishlist',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Added to Wishlist Icon pack', 'thegem'),
						'param_name' => 'added_wishlist_icon_pack',
						'value' => array_merge(array(
							__('Elegant', 'thegem') => 'elegant',
							__('Material Design', 'thegem') => 'material',
							__('FontAwesome', 'thegem') => 'fontawesome',
							__('Header Icons', 'thegem') => 'thegem-header',
							__('Additional', 'thegem') => 'thegemdemo'),
							thegem_userpack_to_dropdown()
						),
						'std' => 'material',
						'dependency' => array(
							'element' => 'product_show_wishlist',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'add_wishlist_icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'add_wishlist_icon_pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'add_wishlist_icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'add_wishlist_icon_pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'add_wishlist_icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'add_wishlist_icon_pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'add_wishlist_icon_thegemheader',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'add_wishlist_icon_pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'add_wishlist_icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'add_wishlist_icon_pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'add_wishlist_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'add_wishlist_icon_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				)),
				array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'added_wishlist_icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'added_wishlist_icon_pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'added_wishlist_icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'added_wishlist_icon_pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'added_wishlist_icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'added_wishlist_icon_pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'added_wishlist_icon_thegemheader',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'added_wishlist_icon_pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'added_wishlist_icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'added_wishlist_icon_pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'added_wishlist_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'added_wishlist_icon_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				))
			);
		}

		$product_source_fields_custom = array(
			array(
				'type' => 'checkbox',
				'heading' => __('Select Products Categories', 'thegem'),
				'param_name' => 'select_products_categories',
				'value' => array(__('Yes', 'thegem') => '1'),
				'dependency' => array(
					'element' => 'source_type',
					'value' => 'custom'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'autocomplete',
				'heading' => __('Select Products Categories', 'thegem'),
				'param_name' => 'content_products_cat',
				'settings' => array(
					'multiple' => true,
					'sortable' => true,
				),
				'save_always' => true,
				'dependency' => array(
					'element' => 'select_products_categories',
					'not_empty' => true
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'thegem_hidden_param',
				'param_name' => 'clearfix',
				'dependency' => array(
					'element' => 'source_type',
					'value' => 'custom'
				),
				'edit_field_class' => 'no-top-padding',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'checkbox',
				'heading' => __('Select Products Attributes', 'thegem'),
				'param_name' => 'select_products_attributes',
				'value' => array(__('Yes', 'thegem') => '1'),
				'dependency' => array(
					'element' => 'source_type',
					'value' => 'custom'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'autocomplete',
				'heading' => __('Select Products Attributes', 'thegem'),
				'param_name' => 'content_products_attr_val',
				'settings' => array(
					'multiple' => true,
					'sortable' => true,
				),
				'save_always' => true,
				'dependency' => array(
					'element' => 'select_products_attributes',
					'not_empty' => true
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'thegem_hidden_param',
				'param_name' => 'clearfix',
				'dependency' => array(
					'element' => 'source_type',
					'value' => 'custom'
				),
				'edit_field_class' => 'no-top-padding',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'checkbox',
				'heading' => __('Select Products Tags', 'thegem'),
				'param_name' => 'select_products_tags',
				'value' => array(__('Yes', 'thegem') => '1'),
				'dependency' => array(
					'element' => 'source_type',
					'value' => 'custom'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'autocomplete',
				'heading' => __('Select Products Tags', 'thegem'),
				'param_name' => 'content_products_tags',
				'settings' => array(
					'multiple' => true,
					'sortable' => true,
				),
				'save_always' => true,
				'dependency' => array(
					'element' => 'select_products_tags',
					'not_empty' => true
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'thegem_hidden_param',
				'param_name' => 'clearfix',
				'dependency' => array(
					'element' => 'source_type',
					'value' => 'custom'
				),
				'edit_field_class' => 'no-top-padding',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'checkbox',
				'heading' => __('Select Products', 'thegem'),
				'param_name' => 'select_products',
				'value' => array(__('Yes', 'thegem') => '1'),
				'dependency' => array(
					'element' => 'source_type',
					'value' => 'custom'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'autocomplete',
				'heading' => __('Select Products', 'thegem'),
				'param_name' => 'content_products',
				'settings' => array(
					'multiple' => true,
					'sortable' => true,
				),
				'save_always' => true,
				'dependency' => array(
					'element' => 'select_products',
					'not_empty' => true
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'thegem_hidden_param',
				'param_name' => 'clearfix',
				'dependency' => array(
					'element' => 'source_type',
					'value' => 'custom'
				),
				'edit_field_class' => 'no-top-padding vc_column vc_col-sm-12',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'textfield',
				'heading' => __('Offset', 'thegem'),
				'param_name' => 'offset',
				'description' => __('Number of items to displace or pass over', 'thegem'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'dropdown',
				'heading' => __('Exclude', 'thegem'),
				'param_name' => 'exclude_type',
				'value' => array(
					__('Manual Selection', 'thegem') => 'manual',
					__('Current Post', 'thegem') => 'current',
					__('Term', 'thegem') => 'term',
				),
				'std' => 'manual',
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'autocomplete',
				'heading' => __('Exclude Products', 'thegem'),
				'param_name' => 'exclude_products',
				'settings' => array(
					'multiple' => true,
					'sortable' => true,
				),
				'description' => __('Add product by title.', 'thegem'),
				'dependency' => array(
					'element' => 'exclude_type',
					'value' => array('manual')
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __('Products & Layout', 'thegem')
			),
			array(
				'type' => 'autocomplete',
				'heading' => __('Exclude Products Terms', 'thegem'),
				'param_name' => 'exclude_product_terms',
				'settings' => array(
					'multiple' => true,
					'sortable' => true,
				),
				'description' => __('Add product term by name.', 'thegem'),
				'dependency' => array(
					'element' => 'exclude_type',
					'value' => array('term')
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __('Products & Layout', 'thegem')
			),
		);

		$product_filters_type = array(
			array(
				'type' => 'thegem_hidden_param',
				'param_name' => 'filters_type',
				'std' => 'filter_thegem',
				'dependency' => array(
					'element' => 'product_show_filter',
					'not_empty' => true
				),
				'edit_field_class' => 'no-top-padding vc_column vc_col-sm-12',
				'group' => __('Filters & Sorting', 'thegem')
			),
			array(
				'type' => 'thegem_hidden_param',
				'param_name' => 'woo_filters_style',
				'std' => '',
				'edit_field_class' => 'no-top-padding vc_column vc_col-sm-12',
				'group' => __('Filters & Sorting', 'thegem')
			),
		);
		$product_source_fields_single = array(
			array(
				'type' => 'thegem_hidden_param',
				'param_name' => 'source_type',
				'std' => 'custom',
				'edit_field_class' => 'no-top-padding vc_column vc_col-sm-12',
				'group' => __('Products & Layout', 'thegem')
			),
		);
		$product_source_fields_archive = array();
		$product_grid_shortcode_categories = array(__( 'TheGem', 'thegem' ), esc_html__( 'WooCommerce', 'js_composer' ));
		$product_carousel_shortcode_categories = array(__( 'TheGem', 'thegem' ), esc_html__( 'WooCommerce', 'js_composer' ));
		if(thegem_is_template_post('single-product')) {
			$product_source_fields_single = array(
				array(
					'type' => 'dropdown',
					'heading' => __('Products Source', 'thegem'),
					'param_name' => 'source_type',
					'value' => array(
						__('Display related or upsell products', 'thegem') => 'related_upsell',
						__('Display products by categories / attributes', 'thegem') => 'custom',
					),
					'std' => 'related_upsell',
					'save_always' => true,
					'edit_field_class' => 'vc_column vc_col-sm-12',
					'group' => __('Products & Layout', 'thegem')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Select Products to Display', 'thegem'),
					'param_name' => 'related_upsell_source',
					'value' => array(
						__('Related Products', 'thegem') => 'related',
						__('Upsell Products', 'thegem') => 'upsell'
					),
					'dependency' => array(
						'element' => 'source_type',
						'value' => 'related_upsell'
					),
					'save_always' => true,
					'edit_field_class' => 'vc_column vc_col-sm-12',
					'group' => __('Products & Layout', 'thegem')
				),
			);
			$product_grid_shortcode_categories[] = __( 'Single Product Builder', 'thegem' );
			$product_carousel_shortcode_categories[] = __( 'Single Product Builder', 'thegem' );
		} else if(thegem_is_template_post('cart')) {
			$product_source_fields_single = array(
				array(
					'type' => 'dropdown',
					'heading' => __('Products Source', 'thegem'),
					'param_name' => 'source_type',
					'value' => array(
						__('Cross-Sell Products', 'thegem') => 'cross_sell',
						__('Display products by categories / attributes', 'thegem') => 'custom',
					),
					'std' => 'cross_sell',
					'save_always' => true,
					'edit_field_class' => 'vc_column vc_col-sm-12',
					'group' => __('Products & Layout', 'thegem')
				),
			);
			$product_grid_shortcode_categories[] = __( 'Cart Builder', 'thegem' );
			$product_carousel_shortcode_categories[] = __( 'Cart Builder', 'thegem' );
		} else if ( thegem_is_template_post( 'product-archive' ) ) {
			$product_source_fields_single = array();
			$product_source_fields_archive = array(
				array(
					'type' => 'dropdown',
					'heading' => __('Products Source', 'thegem'),
					'param_name' => 'source_type',
					'value' => array(
						__('Products Archive', 'thegem') => 'archive',
						__('Custom Selection', 'thegem') => 'custom',
					),
					'std' => 'archive',
					'save_always' => true,
					'edit_field_class' => 'vc_column vc_col-sm-12',
					'group' => __('Products & Layout', 'thegem')
				),
			);
			$product_filters_type = array(
				array(
					'type' => 'dropdown',
					'heading' => __('Filters Type', 'thegem'),
					'param_name' => 'filters_type',
					'value' => array(
						__('TheGem Filters', 'thegem') => 'filter_thegem',
						__('WooCommerce Sidebar Widgets', 'thegem') => 'filter_woo',
					),
					'std' => 'filter_thegem',
					'save_always' => true,
					'dependency' => array(
						'element' => 'product_show_filter',
						'not_empty' => true
					),
					'edit_field_class' => 'vc_column vc_col-sm-12',
					'group' => __('Filters & Sorting', 'thegem')
				),
				array(
					'type' => 'thegem_delimeter_heading',
					'param_name' => 'layout_delim_head',
					'edit_field_class' => 'vc_column vc_col-sm-12',
					'description' => __('To add filter widgets to WooCommerce Sidebar go to <a href="'.get_site_url().'/wp-admin/widgets.php" target="_blank">Appearance -> Widgets</a>.', 'thegem'),
					'dependency' => array(
						'element' => 'filters_type',
						'value' => array('filter_woo')
					),
					'group' => __('Filters & Sorting', 'thegem')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Filters Style', 'thegem'),
					'param_name' => 'woo_filters_style',
					'value' => array(
						__('Sidebar', 'thegem') => 'sidebar',
						__('Hidden Sidebar', 'thegem') => 'hidden',
					),
					'std' => 'sidebar',
					'save_always' => true,
					'dependency' => array(
						'element' => 'filters_type',
						'value' => array('filter_woo')
					),
					'edit_field_class' => 'vc_column vc_col-sm-12',
					'group' => __('Filters & Sorting', 'thegem')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Ajax Filtering', 'thegem'),
					'param_name' => 'woo_ajax_filtering',
					'save_always' => true,
					'value' => array(__('Yes', 'thegem') => '1'),
					'dependency' => array(
						'element' => 'filters_type',
						'value' => array('filter_woo')
					),
					'edit_field_class' => 'vc_column vc_col-sm-12',
					'group' => __('Filters & Sorting', 'thegem')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Remove Attributes Counts', 'thegem'),
					'param_name' => 'woo_remove_counts',
					'save_always' => true,
					'value' => array(__('Yes', 'thegem') => '1'),
					'dependency' => array(
						'element' => 'filters_type',
						'value' => array('filter_woo')
					),
					'edit_field_class' => 'vc_column vc_col-sm-12',
					'group' => __('Filters & Sorting', 'thegem')
				),
			);
			$product_grid_shortcode_categories[] = __( 'Archive Product Builder', 'thegem' );
		}

		$filter_acf = [];
		if (thegem_is_plugin_active('advanced-custom-fields/acf.php') || thegem_is_plugin_active('advanced-custom-fields-pro/acf.php')){
			foreach (thegem_cf_get_acf_plugin_groups() as $gr) {
				$options = thegem_cf_get_acf_plugin_fields_by_group($gr);
				if (empty($options)) {
					$options = [__('Fields not found.', 'thegem') => ''];
				}
				$filter_acf[] = array(
					'type' => 'dropdown',
					'heading' => __('Select Custom Field', 'thegem'),
					'param_name' => 'attribute_custom_fields_acf_' . $gr,
					'value' => $options,
					'dependency' => array(
						'element' => 'attribute_type',
						'value' => $gr
					),
					'description' => __('Go to the <a href="'.get_site_url().'/wp-admin/edit.php?post_type=acf-field-group" target="_blank">ACF -> Field Groups</a> to manage your custom fields.', 'thegem'),
					'edit_field_class' => 'vc_column vc_col-sm-6',
					'group' => __('Filters & Sorting', 'thegem')
				);
			}
		}

		$shortcodes['product_grid_extended'] = array(
			'name' => __('Products Grid', 'thegem'),
			'base' => 'gem_product_grid_extended',
			'icon' => 'thegem-icon-wpb-ui-product-grid',
			'category' => $product_grid_shortcode_categories,
			'description' => __('Display products grid', 'thegem'),
			'params' => array_merge(
				array(
					array(
						'type' => 'textfield',
						'heading' => __('Unique Grid ID', 'thegem'),
						'param_name' => 'portfolio_uid',
						'value' => 'grid_' . substr(md5(rand()), 0, 7),
						'description' => __('In case of adding multiple product grids on the same page please ensure that each product grid has its own unique ID to avoid conflicts. ', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem'),
						'save_always' => true,
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Products', 'thegem'),
						'param_name' => 'products_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Source', 'thegem'),
						'param_name' => 'products_source_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
				),
				$product_source_fields_single,
				$product_source_fields_archive,
				$product_source_fields_custom,
				array(
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Sorting', 'thegem'),
						'param_name' => 'products_sorting_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Default Sorting Order By', 'thegem'),
						'param_name' => 'orderby',
						'value' => array(
							__('WooCommerce Default Sorting', 'thegem') => 'default',
							__('Date', 'thegem') => 'date',
							__('Popularity', 'thegem') => 'popularity',
							__('Rating', 'thegem') => 'rating',
							__('Name', 'thegem') => 'title',
							__('Price', 'thegem') => 'price',
							__('Random', 'thegem') => 'rand',
						),
						'std' => 'default',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Sorting Order', 'thegem'),
						'param_name' => 'order',
						'value' => array(
							__('ASC', 'thegem') => 'asc',
							__('DESC', 'thegem') => 'desc',
						),
						'std' => 'asc',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_hidden_param',
						'param_name' => 'new_default_sorting',
						'std' => '1',
						'save_always' => true,
						'edit_field_class' => 'no-top-padding vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Layout', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Layout', 'thegem'),
						'param_name' => 'layout_type',
						'value' => array(
							__('Grid', 'thegem') => 'grid',
							__('List', 'thegem') => 'list',
						),
						'dependency' => array(
							'callback' => 'extended_grid_skin_callback',
						),
						'std' => 'grid',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Skin', 'thegem'),
						'param_name' => 'extended_grid_skin',
						'value' => array(
							__('Below, Default, Cart Button', 'thegem') => 'below-default-cart-button',
							__('Below, Default, Cart Icon', 'thegem') => 'below-default-cart-icon',
							__('Below, Solid, Cart Disabled', 'thegem') => 'below-cart-disabled',
							__('Below, Border, Cart Icon', 'thegem') => 'below-border-cart-icon',
							__('Below, Shadow Hover 01', 'thegem') => 'below-shadow-hover-01',
							__('Below, Shadow Hover 02', 'thegem') => 'below-shadow-hover-02',
							__('Below, Rounded Images', 'thegem') => 'below-rounded-images',
							__('Below, Rectangle Button', 'thegem') => 'below-rectangle-button-01',
							__('Below, Product Separator 01', 'thegem') => 'below-separator-01',
							__('Below, Product Separator 02', 'thegem') => 'below-separator-02',
							__('Image, Default, Cart Button', 'thegem') => 'image-default-cart-button',
							__('Image, Default, Cart Icon', 'thegem') => 'image-default-cart-icon',
							__('Image, Solid Caption Background', 'thegem') => 'image-solid-background',
							__('Image, Rounded Corners', 'thegem') => 'image-rounded-corners',
							__('Image, Shadow Hover 01', 'thegem') => 'image-shadow-hover-01',
							__('Image, Shadow', 'thegem') => 'image-shadow',
							__('Image, Product Separator 01', 'thegem') => 'image-separator-01',
							__('Image, Product Separator 02', 'thegem') => 'image-separator-02',
							__('Hover, Default', 'thegem') => 'hover-default',
							__('Hover, Rounded Corners', 'thegem') => 'hover-rounded-corners',
							__('Hover, Solid Caption Background', 'thegem') => 'hover-solid-background',
							__('Hover, Product Separator', 'thegem') => 'hover-separator',
							__('Hover, Centered Caption', 'thegem') => 'hover-centered-caption',
							__('Hover, Shadow Hover', 'thegem') => 'hover-shadow-hover',
							__('Hover, Gradient Hover', 'thegem') => 'hover-gradient-hover',
						),
						'dependency' => array(
							'element' => 'layout_type',
							'value' => 'grid'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Columns Desktop', 'thegem'),
						'param_name' => 'columns_desktop_list',
						'value' => array(
							__('1x columns', 'thegem') => '1x',
							__('2x columns', 'thegem') => '2x',
							__('3x columns', 'thegem') => '3x',
							__('4x columns', 'thegem') => '4x',
						),
						'std' => '2x',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => 'list'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Columns Tablet', 'thegem'),
						'param_name' => 'columns_tablet_list',
						'value' => array(
							__('1x columns', 'thegem') => '1x',
							__('2x columns', 'thegem') => '2x',
						),
						'std' => '2x',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => 'list'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Columns Desktop', 'thegem'),
						'param_name' => 'columns_desktop',
						'value' => array(
							__('1x columns', 'thegem') => '1x',
							__('2x columns', 'thegem') => '2x',
							__('3x columns', 'thegem') => '3x',
							__('4x columns', 'thegem') => '4x',
							__('5x columns', 'thegem') => '5x',
							__('6x columns', 'thegem') => '6x',
							__('100% width', 'thegem') => '100%',
						),
						'std' => '4x',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => 'grid'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Columns Tablet', 'thegem'),
						'param_name' => 'columns_tablet',
						'value' => array(
							__('1x columns', 'thegem') => '1x',
							__('2x columns', 'thegem') => '2x',
							__('3x columns', 'thegem') => '3x',
							__('4x columns', 'thegem') => '4x',
						),
						'std' => '3x',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => 'grid'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Columns Mobile', 'thegem'),
						'param_name' => 'columns_mobile',
						'value' => array(
							__('1x columns', 'thegem') => '1x',
							__('2x columns', 'thegem') => '2x',
						),
						'std' => '2x',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => 'grid'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Grid Layout', 'thegem'),
						'param_name' => 'layout',
						'value' => array(
							__('Justified Grid', 'thegem') => 'justified',
							__('Masonry Grid', 'thegem') => 'masonry',
							__('Metro Style', 'thegem') => 'metro',
						),
						'std' => 'justified',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => 'grid'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Image Size', 'thegem'),
						'param_name' => 'image_size',
						'value' => array(
							__('As in Grid Layout (TheGem Thumbnails)', 'thegem') => 'default',
							__('Full Size', 'thegem') => 'full',
							__('WooCommerce Thumbnail', 'thegem') => 'woocommerce_thumbnail',
							__('WooCommerce Single', 'thegem') => 'woocommerce_single',
							__('WordPress Thumbnail', 'thegem') => 'thumbnail',
							__('WordPress Medium', 'thegem') => 'medium',
							__('WordPress Medium Large', 'thegem') => 'medium_large',
							__('WordPress Large', 'thegem') => 'large',
							__('1536x1536', 'thegem') => '1536x1536',
							__('2048x2048', 'thegem') => '2048x2048',
						),
						'std' => 'default',
						'dependency' => array(
							'callback' => 'image_size_callback',
						),
						'edit_field_class' => 'image_size vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Image Ratio', 'thegem'),
						'param_name' => 'image_ratio_full',
						'std' => 1,
						'dependency' => array(
							'element' => 'image_size',
							'value' => array('full')
						),
						'description' => __('Specify a decimal value, i.e. instead of 3:4, specify 0.75. Use a dot as a decimal separator. Leave blank to show the original image ratio', 'thegem'),
						'edit_field_class' => 'image_size vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Image Aspect Ratio', 'thegem'),
						'param_name' => 'image_aspect_ratio',
						'value' => array(
							__('Portrait', 'thegem') => 'portrait',
							__('Square', 'thegem') => 'square',
							__('Custom Selection', 'thegem') => 'custom',
						),
						'std' => 'portrait',
						'dependency' => array(
							'element' => 'image_size',
							'value' => array('default')
						),
						'edit_field_class' => 'image_size vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Image Ratio', 'thegem'),
						'param_name' => 'image_ratio_custom',
						'dependency' => array(
							'element' => 'image_aspect_ratio',
							'value' => array('custom')
						),
						'description' => __('Specify a decimal value, i.e. instead of 3:4, specify 0.75. Use a dot as a decimal separator. Leave blank to show the original image ratio', 'thegem'),
						'edit_field_class' => 'image_size vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('100% Width Columns', 'thegem'),
						'param_name' => 'columns_100',
						'value' => array(
							__('4x columns', 'thegem') => '4',
							__('5x columns', 'thegem') => '5',
							__('6x columns', 'thegem') => '6',
						),
						'std' => '4',
						'dependency' => array(
							'element' => 'columns_desktop',
							'value' => array('100%')
						),
						'description' => __('Number of columns for 100% width grid for desktop resolutions starting from 1920 px and above', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Title', 'thegem'),
						'param_name' => 'cross_sell_title_delim_head',
						'dependency' => array(
							'element' => 'source_type',
							'value' => array('cross_sell')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Title', 'thegem'),
						'param_name' => 'show_cross_sell_title',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'source_type',
							'value' => array('cross_sell')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Title Text', 'thegem'),
						'param_name' => 'cross_sell_title_text',
						'value' => __('You may also like', 'thegem'),
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Title Alignment', 'thegem'),
						'param_name' => 'cross_sell_title_alignment',
						'value' => array(
							__('Left', 'thegem') => 'left',
							__('Center', 'thegem') => 'center',
							__('Right', 'thegem') => 'right',
						),
						'std' => 'left',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('HTML Tag', 'thegem'),
						'param_name' => 'cross_sell_title_tag',
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
						'std' => 'div',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Style', 'thegem'),
						'param_name' => 'cross_sell_title_style',
						'value' => array(
							__('Default', 'thegem') => 'title-h3',
							__('Title H1', 'thegem') => 'title-h1',
							__('Title H2', 'thegem') => 'title-h2',
							__('Title H3', 'thegem') => 'title-h3',
							__('Title H4', 'thegem') => 'title-h4',
							__('Title H5', 'thegem') => 'title-h5',
							__('Title H6', 'thegem') => 'title-h6',
							__('Title xLarge', 'thegem') => 'title-xlarge',
							__('Styled Subtitle', 'thegem') => 'styled-subtitle',
							__('Main Menu', 'thegem') => 'title-main-menu',
							__('Body', 'thegem') => 'text-body',
							__('Tiny Body', 'thegem') => 'text-body-tiny',
						),
						'std' => 'title-h3',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Font weight', 'thegem'),
						'param_name' => 'cross_sell_title_weight',
						'value' => array(
							__('Default', 'thegem') => '',
							__('Thin', 'thegem') => 'light',
						),
						'std' => 'light',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Letter spacing', 'thegem'),
						'param_name' => 'cross_sell_title_letter_spacing',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Text transform', 'thegem'),
						'param_name' => 'cross_sell_title_transform',
						'value' => array(
							__('Default', 'thegem') => '',
							__('None', 'thegem') => 'none',
							__('Capitalize', 'thegem') => 'capitalize',
							__('Lowercase', 'thegem') => 'lowercase',
							__('Uppercase', 'thegem') => 'uppercase',
						),
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'cross_sell_title_color',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top Spacing (desktop)', 'thegem'),
						'param_name' => 'cross_sell_title_top_spacing_desktop',
						'value' => '',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top Spacing (tablet)', 'thegem'),
						'param_name' => 'cross_sell_title_top_spacing_tablet',
						'value' => '',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top Spacing (mobile)', 'thegem'),
						'param_name' => 'cross_sell_title_top_spacing_mobile',
						'value' => '',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom Spacing (desktop)', 'thegem'),
						'param_name' => 'cross_sell_title_bottom_spacing_desktop',
						'value' => '',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom Spacing (tablet)', 'thegem'),
						'param_name' => 'cross_sell_title_bottom_spacing_tablet',
						'value' => '',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom Spacing (mobile)', 'thegem'),
						'param_name' => 'cross_sell_title_bottom_spacing_mobile',
						'value' => '',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Caption', 'thegem'),
						'param_name' => 'caption_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Caption Position', 'thegem'),
						'param_name' => 'caption_position_list',
						'value' => array(
							__('Right', 'thegem') => 'right',
							__('Left', 'thegem') => 'left',
							__('Zigzag', 'thegem') => 'zigzag',
						),
						'std' => 'right',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => 'list'
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Caption Layout', 'thegem'),
						'param_name' => 'caption_layout_list',
						'value' => array(
							__('Vertical', 'thegem') => 'vertical',
							__('Inline', 'thegem') => 'inline',
						),
						'std' => 'vertical',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => 'list'
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Caption Position', 'thegem'),
						'param_name' => 'caption_position',
						'value' => array(
							__('Below Image', 'thegem') => 'page',
							__('On Hover', 'thegem') => 'hover',
							__('On Image', 'thegem') => 'image',
						),
						'std' => 'page',
//						'dependency' => array(
//							'element' => 'layout_type',
//							'value' => 'grid'
//						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show categories', 'thegem'),
						'param_name' => 'product_show_categories',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show product title', 'thegem'),
						'param_name' => 'product_show_title',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show product description', 'thegem'),
						'param_name' => 'product_show_description',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => 'list'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show product price', 'thegem'),
						'param_name' => 'product_show_price',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show product reviews', 'thegem'),
						'param_name' => 'product_show_reviews',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Social Sharing', 'thegem'),
						'param_name' => 'social_sharing',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Attribute Swatches', 'thegem'),
						'param_name' => 'attribute_swatches',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show on Desktop', 'thegem'),
						'param_name' => 'attribute_swatches_desktop',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'attribute_swatches',
							'not_empty' => true
						),
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show on Tablet', 'thegem'),
						'param_name' => 'attribute_swatches_tablet',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'attribute_swatches',
							'not_empty' => true
						),
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show on Mobile', 'thegem'),
						'param_name' => 'attribute_swatches_mobile',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'attribute_swatches',
							'not_empty' => true
						),
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'param_group',
						'heading' => __('Attributes', 'thegem'),
						'param_name' => 'repeater_swatches',
						'value' => urlencode(json_encode(array(
							array(
								'attribute_count' => '4',
								'attribute_name' => '',
							),
						))),
						'params' => array(
							array(
								'type' => 'dropdown',
								'heading' => __('Select Attribute', 'thegem'),
								'param_name' => 'attribute_name',
								'value' => get_woo_attribute_productGrid(),
								'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
							),
							array(
								'type' => 'textfield',
								'heading' => __('Number of values to show', 'thegem'),
								'param_name' => 'attribute_count',
								'value' => '4',
								'description' => __('Use -1 to show all', 'thegem'),
								'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
							),
							array(
								'type' => 'checkbox',
								'heading' => __('Show Attribute Name', 'thegem'),
								'param_name' => 'attribute_show_name',
								'value' => array(__('Yes', 'thegem') => '1'),
								'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
							),
						),
						'dependency' => array(
							'element' => 'attribute_swatches',
							'not_empty' => true
						),
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show Attributes in Simple Products', 'thegem'),
						'param_name' => 'attribute_swatches_simple',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'attribute_swatches',
							'not_empty' => true
						),
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Add to cart', 'thegem'),
						'param_name' => 'add_to_cart_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show add to cart', 'thegem'),
						'param_name' => 'product_show_add_to_cart',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show add to cart on mobile', 'thegem'),
						'param_name' => 'product_show_add_to_cart_mobiles',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'product_show_add_to_cart',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Add To Card Type', 'thegem'),
						'param_name' => 'add_to_cart_type',
						'value' => array(
							__('Icon', 'thegem') => 'icon',
							__('Button', 'thegem') => 'buttons',
						),
						'std' => 'buttons',
						'dependency' => array(
							'element' => 'product_show_add_to_cart',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show Icon', 'thegem'),
						'param_name' => 'cart_button_show_icon',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'product_show_add_to_cart',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Add To Cart Button Text', 'thegem'),
						'param_name' => 'cart_button_text',
						'value' => __('Add To Cart', 'thegem'),
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Select Options Button Text', 'thegem'),
						'param_name' => 'select_options_button_text',
						'value' => __('Select Options', 'thegem'),
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Add To Card Icon pack', 'thegem'),
						'param_name' => 'cart_button_icon_pack',
						'value' => array_merge(array(
							__('Elegant', 'thegem') => 'elegant',
							__('Material Design', 'thegem') => 'material',
							__('FontAwesome', 'thegem') => 'fontawesome',
							__('Header Icons', 'thegem') => 'thegem-header',
							__('Additional', 'thegem') => 'thegemdemo'),
							thegem_userpack_to_dropdown()
						),
						'std' => 'elegant',
						'dependency' => array(
							'element' => 'cart_button_show_icon',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Select Options Icon pack', 'thegem'),
						'param_name' => 'select_options_icon_pack',
						'value' => array_merge(array(
							__('Elegant', 'thegem') => 'elegant',
							__('Material Design', 'thegem') => 'material',
							__('FontAwesome', 'thegem') => 'fontawesome',
							__('Header Icons', 'thegem') => 'thegem-header',
							__('Additional', 'thegem') => 'thegemdemo'),
							thegem_userpack_to_dropdown()
						),
						'std' => 'elegant',
						'dependency' => array(
							'element' => 'cart_button_show_icon',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'cart_button_icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'cart_button_icon_pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'cart_button_icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'cart_button_icon_pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'cart_button_icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'cart_button_icon_pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'cart_button_icon_thegemheader',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'cart_button_icon_pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'cart_button_icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'cart_button_icon_pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'cart_button_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'cart_button_icon_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				)),
				array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'select_options_icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'select_options_icon_pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'select_options_icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'select_options_icon_pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'select_options_icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'select_options_icon_pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'select_options_icon_thegemheader',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'select_options_icon_pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'select_options_icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'select_options_icon_pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'select_options_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'select_options_icon_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				)),
				$wishlist_attr,
				array(
					array(
						'type' => 'checkbox',
						'heading' => __('Show product divider', 'thegem'),
						'param_name' => 'product_show_divider',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => 'list'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Divider Color', 'thegem'),
						'param_name' => 'divider_color',
						'dependency' => array(
							'element' => 'product_show_divider',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Pagination', 'thegem'),
						'param_name' => 'pagination_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Items Per Page', 'thegem'),
						'param_name' => 'items_per_page',
						'value' => '8',
						'description' => __('Use -1 to show all', 'thegem'),
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show pagination', 'thegem'),
						'param_name' => 'show_pagination',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Pagination Type', 'thegem'),
						'param_name' => 'pagination_type',
						'value' => array(
							__('Numbers', 'thegem') => 'normal',
							__('Load More Button', 'thegem') => 'more',
							__('Infinite Scroll', 'thegem') => 'scroll',
							__('Arrows', 'thegem') => 'arrows',
						),
						'dependency' => array(
							'element' => 'show_pagination',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Next page preloading', 'thegem'),
						'param_name' => 'next_page_preloading',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'show_pagination',
							'not_empty' => true
						),
						'description' => __('If enabled, items for the next page will be preloaded on the current page.', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Reduce HTML Size', 'thegem'),
						'param_name' => 'reduce_html_size',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Items on Page Load', 'thegem'),
						'param_name' => 'items_on_load',
						'std' => '8',
						'description' => __('Number of items to load on initial page load. The rest will be loaded on user scroll to reduce HTML size of the page for better pagespeed performance.', 'thegem'),
						'dependency' => array(
							'element' => 'reduce_html_size',
							'not_empty' => true,
						),
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Button Text', 'thegem'),
						'param_name' => 'more_button_text',
						'value' => __('Load More', 'thegem'),
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Button Icon', 'thegem'),
						'param_name' => 'more_icon_pack',
						'value' => array_merge(array(
							__('Elegant', 'thegem') => 'elegant',
							__('Material Design', 'thegem') => 'material',
							__('FontAwesome', 'thegem') => 'fontawesome',
							__('Header Icons', 'thegem') => 'thegem-header',
							__('Additional', 'thegem') => 'thegemdemo'),
							thegem_userpack_to_dropdown()
						),
						'std' => 'material',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'more_icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'more_icon_pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'more_icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'more_icon_pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'more_icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'more_icon_pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'more_icon_thegem_header',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'more_icon_pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'more_icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'more_icon_pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'more_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'more_icon_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				)),
				array(
					array(
						'type' => 'checkbox',
						'heading' => __('Stretch to Full Width', 'thegem'),
						'param_name' => 'more_stretch_full_width',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Separator', 'thegem'),
						'param_name' => 'more_show_separator',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Labels', 'thegem'),
						'param_name' => 'labels_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Labels Style', 'thegem'),
						'param_name' => 'labels_design',
						'value' => array(
							__('Style 1', 'thegem') => '1',
							__('Style 2', 'thegem') => '2',
							__('Style 3', 'thegem') => '3',
							__('Style 4', 'thegem') => '4',
							__('Style 5', 'thegem') => '5',
							__('Style 6', 'thegem') => '6',
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('"New" Label', 'thegem'),
						'param_name' => 'product_show_new',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Label Text', 'thegem'),
						'param_name' => 'new_label_text',
						'value' => __('New', 'thegem'),
						'dependency' => array(
							'element' => 'product_show_new',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('"Sale" Label', 'thegem'),
						'param_name' => 'product_show_sale',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Sale Label Type', 'thegem'),
						'param_name' => 'sale_label_type',
						'value' => array(
							__('Show Discount Percentage', 'thegem') => 'percentage',
							__('Show Text', 'thegem') => 'text'
						),
						'dependency' => array(
							'element' => 'product_show_sale',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Prefix', 'thegem'),
						'param_name' => 'sale_label_prefix',
						'value' => __('-', 'thegem'),
						'dependency' => array(
							'element' => 'sale_label_type',
							'value' => array('percentage')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Suffix', 'thegem'),
						'param_name' => 'sale_label_suffix',
						'value' => __('%', 'thegem'),
						'dependency' => array(
							'element' => 'sale_label_type',
							'value' => array('percentage')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Label Text', 'thegem'),
						'param_name' => 'sale_label_text',
						'value' => __('On Sale', 'thegem'),
						'dependency' => array(
							'element' => 'sale_label_type',
							'value' => array('text')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('"Out of stock" Label', 'thegem'),
						'param_name' => 'product_show_out',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Label Text', 'thegem'),
						'param_name' => 'out_label_text',
						'value' => __('Out of stock', 'thegem'),
						'dependency' => array(
							'element' => 'product_show_out',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Grid image style', 'thegem'),
						'param_name' => 'grid_img_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Image Column Width (%)', 'thegem'),
						'param_name' => 'image_column_width',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => 'list'
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Gaps', 'thegem'),
						'param_name' => 'gaps_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'image_gaps',
						'value' => __('42', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'image_gaps_tablet',
						'value' => __('42', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'image_gaps_mobile',
						'value' => __('42', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Product Separator', 'thegem'),
						'param_name' => 'product_separator_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show', 'thegem'),
						'param_name' => 'product_separator',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Width', 'thegem'),
						'param_name' => 'product_separator_width',
						'dependency' => array(
							'element' => 'product_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'product_separator_color',
						'dependency' => array(
							'element' => 'product_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Image Border', 'thegem'),
						'param_name' => 'thumb_border_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Width', 'thegem'),
						'param_name' => 'image_border_width',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Radius', 'thegem'),
						'param_name' => 'image_border_radius',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal Color', 'thegem'),
						'param_name' => 'image_border_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'image_border_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Apply border on caption container', 'thegem'),
						'param_name' => 'border_caption_container',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Shadow', 'thegem'),
						'param_name' => 'thumb_border_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Enable Shadow', 'thegem'),
						'param_name' => 'enable_shadow',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Shadow color', 'thegem'),
						'param_name' => 'shadow_color',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'std' => 'rgba(0, 0, 0, 0.15)',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Position', 'thegem'),
						'param_name' => 'shadow_position',
						'value' => array(
							__('Outline', 'thegem') => 'outline',
							__('Inset', 'thegem') => 'inset'
						),
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'std' => 'outline',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Horizontal', 'thegem'),
						'param_name' => 'shadow_horizontal',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '0',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Vertical', 'thegem'),
						'param_name' => 'shadow_vertical',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '5',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Blur', 'thegem'),
						'param_name' => 'shadow_blur',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '5',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Spread', 'thegem'),
						'param_name' => 'shadow_spread',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '-5',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Apply shadow on caption container', 'thegem'),
						'param_name' => 'shadowed_container',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('', 'thegem'),
						'param_name' => 'thumb_border_end_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Hover Effect', 'thegem'),
						'param_name' => 'image_hover_effect_page',
						'value' => array(
							__('Show Next Image (Slide)', 'thegem') => 'slide',
							__('Show Next Image (Fade)', 'thegem') => 'fade',
							__('Disabled', 'thegem') => 'disabled',
						),
						'std' => 'fade',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Hover Effect', 'thegem'),
						'param_name' => 'image_hover_effect_list',
						'value' => array(
							__('Show Next Image (Slide)', 'thegem') => 'slide',
							__('Show Next Image (Fade)', 'thegem') => 'fade',
							__('Disabled', 'thegem') => 'disabled',
						),
						'std' => 'fade',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => 'list'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Hover Effect', 'thegem'),
						'param_name' => 'image_hover_effect_image',
						'value' => array(
							__('Show Next Image (Slide)', 'thegem') => 'slide',
							__('Show Next Image (Fade)', 'thegem') => 'fade',
							__('Gradient', 'thegem') => 'gradient',
							__('Circular Overlay', 'thegem') => 'circular',
							__('Disabled', 'thegem') => 'disabled',
						),
						'std' => 'fade',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('image')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Hover Effect', 'thegem'),
						'param_name' => 'image_hover_effect_hover',
						'value' => array(
							__('Show Next Image (Slide)', 'thegem') => 'slide',
							__('Show Next Image (Fade)', 'thegem') => 'fade',
							__('Cyan Breeze', 'thegem') => 'default',
							__('Zooming White', 'thegem') => 'zooming-blur',
							__('Horizontal Sliding', 'thegem') => 'horizontal-sliding',
							__('Vertical Sliding', 'thegem') => 'vertical-sliding',
							__('Gradient', 'thegem') => 'gradient',
							__('Circular Overlay', 'thegem') => 'circular',
							__('Disabled', 'thegem') => 'disabled',
						),
						'std' => 'fade',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('hover')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Fallback Hover', 'thegem'),
						'param_name' => 'image_hover_effect_fallback',
						'value' => array(
							__('Disabled', 'thegem') => 'disabled',
							__('Zooming White', 'thegem') => 'zooming',
						),
						'std' => 'zooming',
						'description' => __('Used in case of only one product image', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Better Thumbnails Quality', 'thegem'),
						'param_name' => 'fullwidth_section_images',
						'value' => array(__('Yes', 'thegem') => '1'),
						'description' => __('Activate for better image quality in case of using in fullwidth section', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Caption style', 'thegem'),
						'param_name' => 'caption_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Background Preset', 'thegem'),
						'param_name' => 'caption_container_preset_hover',
						'value' => array(
							__('Light Gradient', 'thegem') => 'light',
							__('Dark Gradient', 'thegem') => 'dark',
							__('Solid transparent', 'thegem') => 'solid',
							__('Transparent', 'thegem') => 'transparent',
						),
						'std' => 'light',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('image', 'hover')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Content Alignment', 'thegem'),
						'param_name' => 'caption_container_alignment_hover',
						'value' => array(
							__('Default', 'thegem') => '',
							__('Left', 'thegem') => 'left',
							__('Centered', 'thegem') => 'center',
							__('Right', 'thegem') => 'right',
						),
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('image', 'hover')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'caption_container_preset_hover_background_color',
						'dependency' => array(
							'element' => 'caption_container_preset_hover',
							'value' => array('solid', 'light', 'dark')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Font Size Preset', 'thegem'),
						'param_name' => 'font_size_preset',
						'value' => array(
							__('Enlarged', 'thegem') => 'enlarged',
							__('Normal', 'thegem') => 'normal',
						),
						'std' => 'enlarged',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('hover')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Categories', 'thegem'),
						'param_name' => 'categories_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'categories_color_normal',
						'dependency' => array(
							'element' => 'product_show_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'categories_color_hover',
						'dependency' => array(
							'element' => 'product_show_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Product Title', 'thegem'),
						'param_name' => 'product_title_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_title',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Title Size Preset', 'thegem'),
						'param_name' => 'title_preset',
						'value' => array(
							__('Default', 'thegem') => 'default',
							__('Title H1', 'thegem') => 'title-h1',
							__('Title H2', 'thegem') => 'title-h2',
							__('Title H3', 'thegem') => 'title-h3',
							__('Title H4', 'thegem') => 'title-h4',
							__('Title H5', 'thegem') => 'title-h5',
							__('Title H6', 'thegem') => 'title-h6',
							__('Title xLarge', 'thegem') => 'title-xlarge',
							__('Styled Subtitle', 'thegem') => 'styled-subtitle',
							__('Main Menu', 'thegem') => 'main-menu-item',
							__('Body', 'thegem') => 'text-body',
							__('Tiny Body', 'thegem') => 'text-body-tiny',
						),
						'std' => 'default',
						'dependency' => array(
							'element' => 'product_show_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Title Font Transform', 'thegem'),
						'param_name' => 'title_transform',
						'value' => array(
							__('Default', 'thegem') => '',
							__('None', 'thegem') => 'none',
							__('Lowercase', 'thegem') => 'lowercase',
							__('Uppercase', 'thegem') => 'uppercase',
							__('Capitalize', 'thegem') => 'capitalize',
						),
						'std' => '',
						'dependency' => array(
							'element' => 'product_show_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Title Font Weight', 'thegem'),
						'param_name' => 'title_weight',
						'value' => array(
							__('Default', 'thegem') => '',
							__('Thin', 'thegem') => 'thin',
						),
						'std' => '',
						'dependency' => array(
							'element' => 'product_show_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem'),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'title_color_normal',
						'dependency' => array(
							'element' => 'product_show_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'title_color_hover',
						'dependency' => array(
							'element' => 'product_show_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Description', 'thegem'),
						'param_name' => 'product_description_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_description',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Description Size Preset', 'thegem'),
						'param_name' => 'description_preset',
						'value' => array(
							__('Default', 'thegem') => 'default',
							__('Title H1', 'thegem') => 'title-h1',
							__('Title H2', 'thegem') => 'title-h2',
							__('Title H3', 'thegem') => 'title-h3',
							__('Title H4', 'thegem') => 'title-h4',
							__('Title H5', 'thegem') => 'title-h5',
							__('Title H6', 'thegem') => 'title-h6',
							__('Title xLarge', 'thegem') => 'title-xlarge',
							__('Styled Subtitle', 'thegem') => 'styled-subtitle',
							__('Main Menu', 'thegem') => 'main-menu-item',
							__('Tiny', 'thegem') => 'text-body',
							__('Tiny Body', 'thegem') => 'text-body-tiny',
						),
						'std' => 'default',
						'dependency' => array(
							'element' => 'product_show_description',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Truncate Description (Lines)', 'thegem'),
						'param_name' => 'truncate_description',
						'dependency' => array(
							'element' => 'product_show_description',
							'not_empty' => true
						),
						'std' => '2',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Description Max. Width (px)', 'thegem'),
						'param_name' => 'description_max_width',
						'dependency' => array(
							'element' => 'product_show_description',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'description_color_normal',
						'dependency' => array(
							'element' => 'product_show_description',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'description_color_hover',
						'dependency' => array(
							'element' => 'product_show_description',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Product Price', 'thegem'),
						'param_name' => 'product_price_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_price',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Price Size Preset', 'thegem'),
						'param_name' => 'price_preset',
						'value' => array(
							__('Default', 'thegem') => 'default',
							__('Title H1', 'thegem') => 'title-h1',
							__('Title H2', 'thegem') => 'title-h2',
							__('Title H3', 'thegem') => 'title-h3',
							__('Title H4', 'thegem') => 'title-h4',
							__('Title H5', 'thegem') => 'title-h5',
							__('Title H6', 'thegem') => 'title-h6',
							__('Title xLarge', 'thegem') => 'title-xlarge',
							__('Styled Subtitle', 'thegem') => 'styled-subtitle',
							__('Main Menu', 'thegem') => 'main-menu-item',
							__('Tiny', 'thegem') => 'text-body',
							__('Tiny Body', 'thegem') => 'text-body-tiny',
						),
						'std' => 'default',
						'dependency' => array(
							'element' => 'product_show_price',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'price_color_normal',
						'dependency' => array(
							'element' => 'product_show_price',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'price_color_hover',
						'dependency' => array(
							'element' => 'product_show_price',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Rating Stars Rated', 'thegem'),
						'param_name' => 'rated_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'rated_color_normal',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'rated_color_hover',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Rating Stars Base', 'thegem'),
						'param_name' => 'base_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'base_color_normal',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'base_color_hover',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Caption Container Style', 'thegem'),
						'param_name' => 'caption_container_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Preset', 'thegem'),
						'param_name' => 'caption_container_preset',
						'value' => array(
							__('Transparent', 'thegem') => 'transparent',
							__('White', 'thegem') => 'white',
							__('Gray', 'thegem') => 'gray',
							__('Dark', 'thegem') => 'dark',
						),
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Content Alignment', 'thegem'),
						'param_name' => 'caption_container_alignment',
						'value' => array(
							__('Default', 'thegem') => '',
							__('Left', 'thegem') => 'left',
							__('Centered', 'thegem') => 'center',
							__('Right', 'thegem') => 'right',
						),
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Caption Background Color', 'thegem'),
						'param_name' => 'caption_background',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Caption Background Color on hover', 'thegem'),
						'param_name' => 'caption_background_hover',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Separator Width', 'thegem'),
						'param_name' => 'spacing_separator_weight',
						'value' => '1',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Separator Color', 'thegem'),
						'param_name' => 'spacing_separator_color',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Separator Color on hover', 'thegem'),
						'param_name' => 'spacing_separator_color_hover',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Icon Style', 'thegem'),
						'param_name' => 'icon_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icons Color', 'thegem'),
						'param_name' => 'icons_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icons Color on hover', 'thegem'),
						'param_name' => 'icons_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icons Background Color', 'thegem'),
						'param_name' => 'icons_background_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icons Background Color on hover', 'thegem'),
						'param_name' => 'icons_background_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icons Border Color', 'thegem'),
						'param_name' => 'icons_border_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icons Border Color on hover', 'thegem'),
						'param_name' => 'icons_border_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'icons_border_width',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Buttons Style', 'thegem'),
						'param_name' => 'buttons_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'buttons_border_width',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Radius', 'thegem'),
						'param_name' => 'buttons_border_radius',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Icon Alignment', 'thegem'),
						'param_name' => 'buttons_icon_alignment',
						'value' => array(
							__('Left', 'thegem') => 'left',
							__('Right', 'thegem') => 'right',
						),
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Add to Cart button', 'thegem'),
						'param_name' => 'add_to_cart_colors_sub_delim_head',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'button_cart_color_normal',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color on hover', 'thegem'),
						'param_name' => 'button_cart_color_hover',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'button_cart_background_color_normal',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color on hover', 'thegem'),
						'param_name' => 'button_cart_background_color_hover',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'button_cart_border_color_normal',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color on hover', 'thegem'),
						'param_name' => 'button_cart_border_color_hover',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Select Options button', 'thegem'),
						'param_name' => 'select_options_colors_sub_delim_head',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'button_options_color_normal',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color on hover', 'thegem'),
						'param_name' => 'button_options_color_hover',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'button_options_background_color_normal',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color on hover', 'thegem'),
						'param_name' => 'button_options_background_color_hover',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'button_options_border_color_normal',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color on hover', 'thegem'),
						'param_name' => 'button_options_border_color_hover',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Pagination Style', 'thegem'),
						'param_name' => 'pagination_style_normal_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal','arrows')
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top Spacing', 'thegem'),
						'param_name' => 'pagination_spacing',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal','arrows')
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Left Icon', 'thegem'),
						'param_name' => 'pagination_arrows_left_pack',
						'value' => array_merge(array(
							__('Elegant', 'thegem') => 'elegant',
							__('Material Design', 'thegem') => 'material',
							__('FontAwesome', 'thegem') => 'fontawesome',
							__('Header Icons', 'thegem') => 'thegem-header',
							__('Additional', 'thegem') => 'thegemdemo'),
							thegem_userpack_to_dropdown()
						),
						'std' => 'elegant',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('arrows')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Right Icon', 'thegem'),
						'param_name' => 'pagination_arrows_right_pack',
						'value' => array_merge(array(
							__('Elegant', 'thegem') => 'elegant',
							__('Material Design', 'thegem') => 'material',
							__('FontAwesome', 'thegem') => 'fontawesome',
							__('Header Icons', 'thegem') => 'thegem-header',
							__('Additional', 'thegem') => 'thegemdemo'),
							thegem_userpack_to_dropdown()
						),
						'std' => 'elegant',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('arrows')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'pagination_arrows_left_icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'pagination_arrows_left_pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'pagination_arrows_left_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'pagination_arrows_left_pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'pagination_arrows_left_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'pagination_arrows_left_pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'pagination_arrows_left_thegem_header',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'pagination_arrows_left_pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'pagination_arrows_left_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'pagination_arrows_left_pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'pagination_arrows_left_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'pagination_arrows_left_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
				)),
				array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'pagination_arrows_right_icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'pagination_arrows_right_pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'pagination_arrows_right_icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'pagination_arrows_right_pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'pagination_arrows_right_icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'pagination_arrows_right_pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'pagination_arrows_right_icon_thegem_header',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'pagination_arrows_right_pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'pagination_arrows_right_icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'pagination_arrows_right_pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'pagination_arrows_right_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'pagination_arrows_right_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
				)),
				array(
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal Arrow Color', 'thegem'),
						'param_name' => 'pagination_arrows_color_normal',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Arrow Color', 'thegem'),
						'param_name' => 'pagination_arrows_color_hover',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'pagination_numbers_border_width',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Radius', 'thegem'),
						'param_name' => 'pagination_numbers_border_radius',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background', 'thegem'),
						'param_name' => 'pagination_numbers_background_color_normal',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background on hover', 'thegem'),
						'param_name' => 'pagination_numbers_background_color_hover',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background on active', 'thegem'),
						'param_name' => 'pagination_numbers_background_color_active',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'pagination_numbers_text_color_normal',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color on hover', 'thegem'),
						'param_name' => 'pagination_numbers_text_color_hover',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color on active', 'thegem'),
						'param_name' => 'pagination_numbers_text_color_active',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'pagination_numbers_border_color_normal',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color on hover', 'thegem'),
						'param_name' => 'pagination_numbers_border_color_hover',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color on active', 'thegem'),
						'param_name' => 'pagination_numbers_border_color_active',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Arrow Background', 'thegem'),
						'param_name' => 'pagination_arrows_background_color_normal',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Arrow Background on hover', 'thegem'),
						'param_name' => 'pagination_arrows_background_color_hover',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Arrow Border Color', 'thegem'),
						'param_name' => 'pagination_arrows_border_color_normal',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Arrow Border Color on hover', 'thegem'),
						'param_name' => 'pagination_arrows_border_color_hover',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Arrow Color', 'thegem'),
						'param_name' => 'pagination_arrows_icon_color_normal',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Arrow Color on hover', 'thegem'),
						'param_name' => 'pagination_arrows_icon_color_hover',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('normal')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Pagination Style', 'thegem'),
						'param_name' => 'pagination_style_more_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top Spacing', 'thegem'),
						'param_name' => 'pagination_more_spacing',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Separator Style', 'thegem'),
						'param_name' => 'pagination_more_button_separator_style',
						'value' => array(
							__('Single', 'thegem') => 'gem-button-separator-type-single',
							__('Square', 'thegem') => 'gem-button-separator-type-square',
							__('Soft Double', 'thegem') => 'gem-button-separator-type-soft-double',
							__('Strong Double', 'thegem') => 'gem-button-separator-type-strong-double',
						),
						'std' => 'gem-button-separator-type-single',
						'dependency' => array(
							'element' => 'more_show_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Button Type', 'thegem'),
						'param_name' => 'pagination_more_button_type',
						'value' => array(
							__('Flat', 'thegem') => 'flat',
							__('Outline', 'thegem') => 'outline',
						),
						'std' => 'flat',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Size', 'thegem'),
						'param_name' => 'pagination_more_button_size',
						'value' => array(
							__('Tiny', 'thegem') => 'tiny',
							__('Small', 'thegem') => 'small',
							__('Medium', 'thegem') => 'medium',
							__('Large', 'thegem') => 'large',
							__('Giant', 'thegem') => 'giant'
						),
						'std' => 'small',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Text weight', 'thegem'),
						'param_name' => 'pagination_more_button_text_weight',
						'value' => array(
							__('Normal', 'thegem') => 'normal',
							__('Thin', 'thegem') => 'thin'
						),
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('No uppercase', 'thegem'),
						'param_name' => 'pagination_more_button_no_uppercase',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border radius', 'thegem'),
						'param_name' => 'pagination_more_button_border_radius',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Border width', 'thegem'),
						'param_name' => 'pagination_more_button_border_width',
						'value' => array(1, 2, 3, 4, 5, 6),
						'std' => 2,
						'dependency' => array(
							'element' => 'pagination_more_button_type',
							'value' => array('outline')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'param_name' => 'pagination_more_button_text_color_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'pagination_more_button_text_color_normal',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover text color', 'thegem'),
						'param_name' => 'pagination_more_button_text_color_hover',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background color', 'thegem'),
						'param_name' => 'pagination_more_button_bg_color_normal',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover background color', 'thegem'),
						'param_name' => 'pagination_more_button_bg_color_hover',
						'dependency' => array(
							'element' => 'pagination_type',
							'value' => array('more')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border color', 'thegem'),
						'param_name' => 'pagination_more_button_border_color_normal',
						'dependency' => array(
							'element' => 'pagination_more_button_type',
							'value' => array('outline')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover border color', 'thegem'),
						'param_name' => 'pagination_more_button_border_color_hover',
						'dependency' => array(
							'element' => 'pagination_more_button_type',
							'value' => array('outline')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Sorting Style', 'thegem'),
						'param_name' => 'sorting_style_more_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Default sorting" Text', 'thegem'),
						'param_name' => 'sorting_text',
						'value' => __('Default sorting', 'woocommerce' ),
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'sorting_text_color',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'sorting_background_color',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'sorting_border_color',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Radius', 'thegem'),
						'param_name' => 'sorting_border_radius',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'sorting_border_width',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom Spacing', 'thegem'),
						'param_name' => 'sorting_bottom_spacing',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Padding top', 'thegem'),
						'param_name' => 'sorting_padding_top',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Padding bottom', 'thegem'),
						'param_name' => 'sorting_padding_bottom',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Padding left', 'thegem'),
						'param_name' => 'sorting_padding_left',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Padding right', 'thegem'),
						'param_name' => 'sorting_padding_right',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Sort by latest" Text', 'thegem'),
						'param_name' => 'sorting_dropdown_latest_text',
						'value' => __( 'Sort by latest', 'woocommerce' ),
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Sort by popularity" Text', 'thegem'),
						'param_name' => 'sorting_dropdown_popularity_text',
						'value' => __( 'Sort by popularity', 'woocommerce'),
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Sort by average rating" Text', 'thegem'),
						'param_name' => 'sorting_dropdown_rating_text',
						'value' => __( 'Sort by average rating', 'woocommerce'),
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Sort by price: low to high" Text', 'thegem'),
						'param_name' => 'sorting_dropdown_price_low_high_text',
						'value' => __( 'Sort by price: low to high', 'woocommerce' ),
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Sort by price: high to low" Text', 'thegem'),
						'param_name' => 'sorting_dropdown_price_high_low_text',
						'value' => __( 'Sort by price: high to low', 'woocommerce' ),
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Dropdown', 'thegem'),
						'param_name' => 'sorting_dropdown_header',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'sorting_dropdown_text_color_normal',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Text Color', 'thegem'),
						'param_name' => 'sorting_dropdown_text_color_hover',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active Text Color', 'thegem'),
						'param_name' => 'sorting_dropdown_text_color_active',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Dropdown Background Color', 'thegem'),
						'param_name' => 'sorting_dropdown_background_color',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Labels Style', 'thegem'),
						'param_name' => 'labels_style_more_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('"New" Label colors', 'thegem'),
						'param_name' => 'new_label_colors_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_new',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background', 'thegem'),
						'param_name' => 'new_label_background',
						'dependency' => array(
							'element' => 'product_show_new',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text', 'thegem'),
						'param_name' => 'new_label_text_color',
						'dependency' => array(
							'element' => 'product_show_new',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('"Sale" Label colors', 'thegem'),
						'param_name' => 'sale_label_colors_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_sale',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background', 'thegem'),
						'param_name' => 'sale_label_background',
						'dependency' => array(
							'element' => 'product_show_sale',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text', 'thegem'),
						'param_name' => 'sale_label_text_color',
						'dependency' => array(
							'element' => 'product_show_sale',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('"Out of stock" Label colors', 'thegem'),
						'param_name' => 'out_label_colors_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_out',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background', 'thegem'),
						'param_name' => 'out_label_background',
						'dependency' => array(
							'element' => 'product_show_out',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text', 'thegem'),
						'param_name' => 'out_label_text_color',
						'dependency' => array(
							'element' => 'product_show_out',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Labels Margin', 'thegem'),
						'param_name' => 'labels_margin_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top Margin', 'thegem'),
						'param_name' => 'labels_margin_top',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom Margin', 'thegem'),
						'param_name' => 'labels_margin_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left Margin', 'thegem'),
						'param_name' => 'labels_margin_left',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right Margin', 'thegem'),
						'param_name' => 'labels_margin_right',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Sorting on Frontend', 'thegem'),
						'param_name' => 'product_show_sorting',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Sorting Type', 'thegem'),
						'param_name' => 'sorting_type',
						'value' => array(
							__('Default', 'thegem') => 'default',
							__('Extended', 'thegem') => 'extended',
						),
						'std' => 'default',
						'edit_field_class' => 'no-top-padding vc_column vc_col-sm-6',
						'dependency' => array(
							'element' => 'product_show_sorting',
							'not_empty' => true
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Open Dropdown', 'thegem'),
						'param_name' => 'sorting_dropdown_open',
						'value' => array(
							__('On Hover', 'thegem') => 'hover',
							__('On Click', 'thegem') => 'click',
						),
						'std' => 'hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'dependency' => array(
							'element' => 'sorting_type',
							'value' => 'extended'
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'param_group',
						'heading' => __('Sorting', 'thegem'),
						'param_name' => 'repeater_sort',
						'params' => array_merge(
							array(
								array(
									'type' => 'textfield',
									'heading' => __('Title', 'thegem'),
									'param_name' => 'title',
									'value' => __('Title', 'thegem'),
									'edit_field_class' => 'vc_column vc_col-sm-6',
								),
								array(
									'type' => 'dropdown',
									'heading' => __('Sort By', 'thegem'),
									'param_name' => 'attribute_type',
									'value' => array_merge(
										[
											__('Title', 'thegem') => 'title',
											__('Date', 'thegem') => 'date',
											__('Price', 'thegem') => 'price',
											__('Rating', 'thegem') => 'rating',
											__('Popularity', 'thegem') => 'popularity',
											__('Custom Fields (TheGem)', 'thegem') => 'custom_fields',
											__('Project Details', 'thegem') => 'details',
											__('Manual Meta Key', 'thegem') => 'manual_key',
										],
										thegem_cf_get_acf_plugin_groups()
									),
									'std' => 'title',
									'edit_field_class' => 'no-top-padding vc_column vc_col-sm-6',
								),
								array(
									'type' => 'textfield',
									'heading' => __('Specify Field`s Name', 'thegem'),
									'param_name' => 'manual_key_field',
									'dependency' => array(
										'element' => 'attribute_type',
										'value' => array('manual_key')
									),
									'edit_field_class' => 'vc_column vc_col-sm-6',
								),
								array(
									'type' => 'dropdown',
									'heading' => __('Select Field', 'thegem'),
									'param_name' => 'attribute_details',
									'value' => select_portfolio_details(true),
									'dependency' => array(
										'element' => 'attribute_type',
										'value' => array('details')
									),
									'description' => __('Go to the <a href="' . get_site_url() . '/wp-admin/admin.php?page=thegem-theme-options#/single-pages/portfolio" target="_blank">Theme Options -> Single Pages -> Portfolio Page</a> to manage your custom fields.', 'thegem'),
									'edit_field_class' => 'vc_column vc_col-sm-6',
								),
								array(
									'type' => 'dropdown',
									'heading' => __('Select Field', 'thegem'),
									'param_name' => 'attribute_custom_fields',
									'value' => select_theme_options_custom_fields_all(true),
									'dependency' => array(
										'element' => 'attribute_type',
										'value' => array('custom_fields')
									),
									'description' => __('Go to the <a href="' . get_site_url() . '/wp-admin/admin.php?page=thegem-theme-options#/single-pages" target="_blank">Theme Options -> Single Pages</a> to manage your custom fields.', 'thegem'),
									'edit_field_class' => 'vc_column vc_col-sm-6',
								),
							),
							$filter_acf,
							array(
								array(
									'type' => 'dropdown',
									'heading' => __('Field Type', 'thegem'),
									'param_name' => 'field_type',
									'value' => array(
										__('Text', 'thegem') => 'text',
										__('Number', 'thegem') => 'number',
									),
									'std' => 'text',
									'dependency' => array(
										'element' => 'attribute_type',
										'value' => array_merge(
											array('custom_fields', 'details', 'manual_key'),
											thegem_cf_get_acf_plugin_groups_keys()
										),
									),
									'edit_field_class' => 'vc_column vc_col-sm-6',
								),
								array(
									'type' => 'dropdown',
									'heading' => __('Order', 'thegem'),
									'param_name' => 'sort_order',
									'value' => array(
										__('ASC', 'thegem') => 'asc',
										__('DESC', 'thegem') => 'desc',
									),
									'std' => 'asc',
									'edit_field_class' => 'vc_column vc_col-sm-6',
								),
							)
						),
						'dependency' => array(
							'element' => 'sorting_type',
							'value' => 'extended'
						),
						'edit_field_class' => 'extended_sorting vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_hidden_param',
						'param_name' => 'sorting_clearfix',
						'edit_field_class' => 'no-top-padding vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show Filters', 'thegem'),
						'param_name' => 'product_show_filter',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'callback' => 'filters_style_callback'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Used in fullwidth section (no gaps)', 'thegem'),
						'param_name' => 'fullwidth_section_sorting',
						'value' => array(__('Yes', 'thegem') => '1'),
						'description' => __('Activate to add extra padding', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
				),
				$product_filters_type,
				array(
					array(
						'type' => 'dropdown',
						'heading' => __('Filters Style', 'thegem'),
						'param_name' => 'filters_style',
						'value' => array(
							__('Horizontal', 'thegem') => 'standard',
							__('Sidebar', 'thegem') => 'sidebar',
							__('Hidden Sidebar', 'thegem') => 'hidden',
							__('Products Tabs', 'thegem') => 'tabs',
						),
						'dependency' => array(
							'element' => 'filters_type',
							'value' => array('filter_thegem')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Sidebar Position', 'thegem'),
						'param_name' => 'sidebar_position',
						'value' => array(
							__('Left', 'thegem') => 'left',
							__('Right', 'thegem') => 'right',
						),
						'std' => 'left',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('sidebar')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Sidebar Position', 'thegem'),
						'param_name' => 'woo_sidebar_position',
						'value' => array(
							__('Left', 'thegem') => 'left',
							__('Right', 'thegem') => 'right',
						),
						'std' => 'left',
						'dependency' => array(
							'element' => 'woo_filters_style',
							'value' => array('sidebar')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Sticky Sidebar', 'thegem'),
						'param_name' => 'sidebar_sticky',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('sidebar')
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Sticky Filters', 'thegem'),
						'param_name' => 'filters_sticky',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard')
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Sticky Filters Background', 'thegem'),
						'param_name' => 'filters_sticky_color',
						'dependency' => array(
							'element' => 'filters_sticky',
							'not_empty' => true
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Sticky Sidebar', 'thegem'),
						'param_name' => 'woo_sidebar_sticky',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'woo_filters_style',
							'value' => array('sidebar')
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Scroll To Top', 'thegem'),
						'param_name' => 'filters_scroll_top',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar', 'hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Scroll To Top', 'thegem'),
						'param_name' => 'woo_filters_scroll_top',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'woo_filters_style',
							'value' => array('standard', 'sidebar')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Filter by Categories', 'thegem'),
						'param_name' => 'filter_by_categories_heading',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Filter by Categories', 'thegem'),
						'param_name' => 'filter_by_categories',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Hierarchy', 'thegem'),
						'param_name' => 'filter_by_categories_hierarchy',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'filter_by_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Collapsible', 'thegem'),
						'param_name' => 'filter_by_categories_collapsible',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'filter_by_categories_hierarchy',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Product Counts', 'thegem'),
						'param_name' => 'filter_by_categories_count',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'filter_by_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Title', 'thegem'),
						'param_name' => 'filter_by_categories_title',
						'value' => __('Categories', 'thegem'),
						'dependency' => array(
							'element' => 'filter_by_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show Title', 'thegem'),
						'param_name' => 'filter_by_categories_show_title',
						'std' => '1',
						'dependency' => array(
							'element' => 'filter_by_categories',
							'not_empty' => true
						),
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'hide_standard vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Order By', 'thegem'),
						'param_name' => 'filter_by_categories_order_by',
						'value' => array(
							__('Name', 'thegem') => 'name',
							__('Category Order', 'thegem') => 'term_order'
						),
						'dependency' => array(
							'element' => 'filter_by_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Display Type', 'thegem'),
						'param_name' => 'filter_by_categories_display_type',
						'value' => array(
							__('List', 'thegem') => 'list',
							__('Dropdown', 'thegem') => 'dropdown',
						),
						'std' => 'list',
						'dependency' => array(
							'element' => 'filter_by_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'hide_standard vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Open Dropdown', 'thegem'),
						'param_name' => 'filter_by_categories_display_dropdown_open',
						'value' => array(
							__('On Hover', 'thegem') => 'hover',
							__('On Click', 'thegem') => 'click',
						),
						'std' => 'hover',
						'dependency' => array(
							'element' => 'filter_by_categories_display_type',
							'value' => 'dropdown'
						),
						'edit_field_class' => 'hide_standard vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Appearance Order', 'thegem'),
						'param_name' => 'filter_by_categories_order',
						'std' => '1',
						'dependency' => array(
							'element' => 'filter_by_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Filter by Attribute', 'thegem'),
						'param_name' => 'filter_by_attribute_heading',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Filter by Attribute', 'thegem'),
						'param_name' => 'filter_by_attribute',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'param_group',
						'heading' => __('Attributes', 'thegem'),
						'param_name' => 'repeater_attributes',
						'value' => urlencode(json_encode(array(
							array(
								'attribute_title' => 'Attribute 1',
							),
							array(
								'attribute_title' => 'Attribute 2',
							),
						))),
						'dependency' => array(
							'element' => 'filter_by_attribute',
							'not_empty' => true
						),
						'params' => array(
							array(
								'type' => 'textfield',
								'heading' => __('Title', 'thegem'),
								'param_name' => 'attribute_title',
								'value' => __('Attribute 1', 'thegem'),
								'edit_field_class' => 'vc_column vc_col-sm-4',
							),
							array(
								'type' => 'checkbox',
								'heading' => __('Show Title', 'thegem'),
								'param_name' => 'show_title',
								'std' => '1',
								'value' => array(__('Yes', 'thegem') => '1'),
								'edit_field_class' => 'hide_standard no-top-padding vc_column vc_col-sm-6',
							),
							array(
								'type' => 'thegem_hidden_param',
								'param_name' => 'show_title_clearfix',
								'edit_field_class' => 'no-top-padding vc_column vc_col-sm-12',
							),
							array(
								'type' => 'dropdown',
								'heading' => __('Attribute', 'thegem'),
								'param_name' => 'attribute_name',
								'value' => get_woo_attribute_productGrid(),
								'edit_field_class' => 'vc_column vc_col-sm-4',
							),
							array(
								'type' => 'dropdown',
								'heading' => __('Query Type', 'thegem'),
								'param_name' => 'attribute_query_type',
								'value' => array(
									__('AND', 'thegem') => 'and',
									__('OR', 'thegem') => 'or',
								),
								'edit_field_class' => 'vc_column vc_col-sm-4',
							),
							array(
								'type' => 'dropdown',
								'heading' => __('Display Type', 'thegem'),
								'param_name' => 'attribute_display_type',
								'value' => array(
									__('List', 'thegem') => 'list',
									__('Dropdown', 'thegem') => 'dropdown',
								),
								'std' => 'list',
								'edit_field_class' => 'hide_standard vc_column vc_col-sm-4',
							),
							array(
								'type' => 'dropdown',
								'heading' => __('Open Dropdown', 'thegem'),
								'param_name' => 'attribute_display_dropdown_open',
								'value' => array(
									__('On Hover', 'thegem') => 'hover',
									__('On Click', 'thegem') => 'click',
								),
								'std' => 'hover',
								'dependency' => array(
									'element' => 'attribute_display_type',
									'value' => 'dropdown'
								),
								'edit_field_class' => 'hide_standard vc_column vc_col-sm-4',
							),
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Product Counts', 'thegem'),
						'param_name' => 'filter_by_attribute_count',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'filter_by_attribute',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6 no-top-padding',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Hide Empty', 'thegem'),
						'param_name' => 'filter_by_attribute_hide_null',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'filter_by_attribute',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6 no-top-padding',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Appearance Order', 'thegem'),
						'param_name' => 'filter_by_attribute_order',
						'std' => '2',
						'dependency' => array(
							'element' => 'filter_by_attribute',
							'not_empty' => true
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Filter by Price', 'thegem'),
						'param_name' => 'filter_by_price_heading',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Filter by Price', 'thegem'),
						'param_name' => 'filter_by_price',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Title', 'thegem'),
						'param_name' => 'filter_by_price_title',
						'value' => __('Price', 'thegem'),
						'dependency' => array(
							'element' => 'filter_by_price',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show Title', 'thegem'),
						'param_name' => 'filter_by_price_show_title',
						'std' => '1',
						'dependency' => array(
							'element' => 'filter_by_price',
							'not_empty' => true
						),
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'hide_standard vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Display Type', 'thegem'),
						'param_name' => 'filter_by_price_display_type',
						'value' => array(
							__('List', 'thegem') => 'list',
							__('Dropdown', 'thegem') => 'dropdown',
						),
						'std' => 'list',
						'dependency' => array(
							'element' => 'filter_by_price',
							'not_empty' => true
						),
						'edit_field_class' => 'hide_standard vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Open Dropdown', 'thegem'),
						'param_name' => 'filter_by_price_display_dropdown_open',
						'value' => array(
							__('On Hover', 'thegem') => 'hover',
							__('On Click', 'thegem') => 'click',
						),
						'std' => 'hover',
						'dependency' => array(
							'element' => 'filter_by_price_display_type',
							'value' => 'dropdown'
						),
						'edit_field_class' => 'hide_standard vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Appearance Order', 'thegem'),
						'param_name' => 'filter_by_price_order',
						'std' => '3',
						'dependency' => array(
							'element' => 'filter_by_price',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Filter by Product Status', 'thegem'),
						'param_name' => 'filter_by_status_heading',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Filter by Product Status', 'thegem'),
						'param_name' => 'filter_by_status',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Title', 'thegem'),
						'param_name' => 'filter_by_status_title',
						'value' => __('Status', 'thegem'),
						'dependency' => array(
							'element' => 'filter_by_status',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show Title', 'thegem'),
						'param_name' => 'filter_by_status_show_title',
						'std' => '1',
						'dependency' => array(
							'element' => 'filter_by_status',
							'not_empty' => true
						),
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'hide_standard vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('On Sale', 'thegem'),
						'param_name' => 'filter_by_status_sale',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'filter_by_status',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"On Sale" Text', 'thegem'),
						'param_name' => 'filter_by_status_sale_text',
						'value' => __('On Sale', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('In Stock', 'thegem'),
						'param_name' => 'filter_by_status_stock',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'filter_by_status',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"In Stock" Text', 'thegem'),
						'param_name' => 'filter_by_status_stock_text',
						'value' => __('In Stock', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Status Counts', 'thegem'),
						'param_name' => 'filter_by_status_count',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'filter_by_status',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Display Type', 'thegem'),
						'param_name' => 'filter_by_status_display_type',
						'value' => array(
							__('List', 'thegem') => 'list',
							__('Dropdown', 'thegem') => 'dropdown',
						),
						'std' => 'list',
						'dependency' => array(
							'element' => 'filter_by_status',
							'not_empty' => true
						),
						'edit_field_class' => 'hide_standard vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Open Dropdown', 'thegem'),
						'param_name' => 'filter_by_status_display_dropdown_open',
						'value' => array(
							__('On Hover', 'thegem') => 'hover',
							__('On Click', 'thegem') => 'click',
						),
						'std' => 'hover',
						'dependency' => array(
							'element' => 'filter_by_status_display_type',
							'value' => 'dropdown'
						),
						'edit_field_class' => 'hide_standard vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Appearance Order', 'thegem'),
						'param_name' => 'filter_by_status_order',
						'std' => '4',
						'dependency' => array(
							'element' => 'filter_by_status',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Product Search', 'thegem'),
						'param_name' => 'filter_by_search_heading',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Product Search', 'thegem'),
						'param_name' => 'filter_by_search',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Text Labels', 'thegem'),
						'param_name' => 'text_labels_heading',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Show All" Text', 'thegem'),
						'param_name' => 'filters_text_labels_all_text',
						'value' => __('Show All', 'thegem'),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Clear Filters" Text', 'thegem'),
						'param_name' => 'filters_text_labels_clear_text',
						'value' => __('Clear Filters', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Search by Product" Text', 'thegem'),
						'param_name' => 'filters_text_labels_search_text',
						'value' => __('Search by Product', 'thegem'),
						'dependency' => array(
							'element' => 'filter_by_search',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Show Filters" Text', 'thegem'),
						'param_name' => 'filter_buttons_hidden_show_text',
						'value' => __('Show Filters', 'thegem'),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Sidebar Title" Text', 'thegem'),
						'param_name' => 'filter_buttons_hidden_sidebar_title',
						'value' => __('Filter', 'thegem'),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Filter By" Text', 'thegem'),
						'param_name' => 'filter_buttons_hidden_filter_by_text',
						'value' => __('Filter By', 'thegem'),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Filter Style (Standard)', 'thegem'),
						'param_name' => 'filter_style_standard_delim_head',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'filter_buttons_standard_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard')
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'filter_buttons_standard_background_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard')
						),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'filter_buttons_standard_border_width',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Radius', 'thegem'),
						'param_name' => 'filter_buttons_standard_border_radius',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom Spacing', 'thegem'),
						'param_name' => 'filter_buttons_standard_bottom_spacing',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Alignment', 'thegem'),
						'param_name' => 'filter_buttons_standard_alignment',
						'value' => array(
							__('Left', 'thegem') => 'left',
							__('Right', 'thegem') => 'right',
							__('Center', 'thegem') => 'center',
						),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Dropdown Colors', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_text_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Filter Style (Sidebar)', 'thegem'),
						'param_name' => 'filter_style_hidden_sidebar_delim_head',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('sidebar')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Filter Style (Hidden)', 'thegem'),
						'param_name' => 'filter_style_hidden_delim_head',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('hidden')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Text Colors', 'thegem'),
						'param_name' => 'filter_buttons_hidden_sidebar_text_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('hidden', 'sidebar')
						),
						'description' => __('Filter titles inherit style settings from sidebar widgets and can be adjusted in theme options under "Typography -> Elements -> Sidebar Widgets"', 'thegem'),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Normal', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_text_color_normal',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Hover', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_text_color_hover',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Active', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_text_color_active',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Dropdown Background', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_background_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Counts & Arrow Colors', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_counts_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_counts_color_normal',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_counts_color_hover',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_counts_color_active',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Counts & Arrow Background', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_counts_background_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_counts_background_color_normal',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_counts_background_color_hover',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_counts_background_color_active',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Price Range Background', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_price_range_background_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_price_range_background_color_normal',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_price_range_background_color_hover',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'filter_buttons_standard_dropdown_price_range_background_color_active',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Sidebar Separator', 'thegem'),
						'param_name' => 'product_separator_sidebar_sub',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('hidden', 'sidebar')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Filter List Max Height', 'thegem'),
						'param_name' => 'items_list_max_height',
						'description' => __('Limit the filter attributes displayed in the list using the scrollbar. Leave blank for a complete list.', 'thegem'),
						'edit_field_class' => 'extended_filter vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Separator Width', 'thegem'),
						'param_name' => 'filter_buttons_hidden_sidebar_separator_width',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('hidden', 'sidebar')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Separator Color', 'thegem'),
						'param_name' => 'filter_buttons_hidden_sidebar_separator_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('hidden', 'sidebar')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Selected Filters', 'thegem'),
						'param_name' => 'selected_filters_sub',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Duplicate in Sidebar', 'thegem'),
						'param_name' => 'duplicate_selected_in_sidebar',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('sidebar')
						),
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Selected Filters Border Radius', 'thegem'),
						'param_name' => 'filter_buttons_standard_selected_border_radius',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'filter_buttons_standard_selected_text_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color on hover', 'thegem'),
						'param_name' => 'filter_buttons_standard_selected_text_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'filter_buttons_standard_selected_background_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color on hover', 'thegem'),
						'param_name' => 'filter_buttons_standard_selected_background_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Padding top', 'thegem'),
						'param_name' => 'filter_buttons_standard_selected_padding_top',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Padding bottom', 'thegem'),
						'param_name' => 'filter_buttons_standard_selected_padding_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Padding left', 'thegem'),
						'param_name' => 'filter_buttons_standard_selected_padding_left',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Padding right', 'thegem'),
						'param_name' => 'filter_buttons_standard_selected_padding_right',
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Sidebar Responsive Mode', 'thegem'),
						'param_name' => 'sidebar_responsive_mode_filters_heading',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Sidebar', 'thegem'),
						'param_name' => 'sidebar_hidden_filters_heading',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('hidden')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('"Show Filters" Icon', 'thegem'),
						'param_name' => 'filter_buttons_hidden_show_icon',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('"Show Filters" Text Color', 'thegem'),
						'param_name' => 'filter_buttons_sidebar_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Show Filters" Border Radius', 'thegem'),
						'param_name' => 'filter_buttons_sidebar_border_radius',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Show Filters" Border Width', 'thegem'),
						'param_name' => 'filter_buttons_sidebar_border_width',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Sidebar Background Color', 'thegem'),
						'param_name' => 'filter_buttons_standard_background',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Overlay Color', 'thegem'),
						'param_name' => 'filter_buttons_standard_overlay_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('"Close" Icon Color', 'thegem'),
						'param_name' => 'filter_buttons_standard_close_icon_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Product Search', 'thegem'),
						'param_name' => 'search_standard_filters_sub',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icon color', 'thegem'),
						'param_name' => 'filter_buttons_standard_search_icon_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Input Border radius', 'thegem'),
						'param_name' => 'filter_buttons_standard_search_input_border_radius',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Input color', 'thegem'),
						'param_name' => 'filter_buttons_standard_search_input_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Input Background color', 'thegem'),
						'param_name' => 'filter_buttons_standard_search_input_background_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('standard', 'sidebar','hidden')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Tabs Style', 'thegem'),
						'param_name' => 'filters_tabs_style',
						'value' => array(
							__('Default', 'thegem') => 'default',
							__('Alternative', 'thegem') => 'alternative'
						),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('tabs')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Tabs Preloading', 'thegem'),
						'param_name' => 'tabs_preloading',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('tabs')
						),
						'description' => __('If enabled, items in the tabs will be preloaded on the current page.', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Title', 'thegem'),
						'param_name' => 'filters_tabs_title_header',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('tabs')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Title', 'thegem'),
						'param_name' => 'filters_tabs_title_text',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('tabs')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Style Preset', 'thegem'),
						'param_name' => 'filters_tabs_title_style_preset',
						'value' => array(
							__('Bold Title', 'thegem') => 'bold',
							__('Thin Title', 'thegem') => 'thin'
						),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('tabs')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Separator', 'thegem'),
						'param_name' => 'filters_tabs_title_separator',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'filters_tabs_style',
							'value' => array('alternative')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Tabs', 'thegem'),
						'param_name' => 'filters_tabs_tabs_header',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('tabs')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'param_group',
						'heading' => __('Tabs', 'thegem'),
						'param_name' => 'filters_tabs_tabs',
						'value' => urlencode(json_encode(array(
							array(
								'filters_tabs_tab_title' => 'Featured Products',
								'filters_tabs_tab_filter_by' => 'featured',
							),
							array(
								'filters_tabs_tab_title' => 'On Sale Products',
								'filters_tabs_tab_filter_by' => 'sale',
							),
						))),
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('tabs')
						),
						'params' => array_merge(array(
							array(
								'type' => 'textfield',
								'heading' => __('Title', 'thegem'),
								'param_name' => 'filters_tabs_tab_title',
								'value' => __('Title', 'thegem'),
								'edit_field_class' => 'vc_column vc_col-sm-4',
							),
							array(
								'type' => 'dropdown',
								'heading' => __('Filter By', 'thegem'),
								'param_name' => 'filters_tabs_tab_filter_by',
								'value' => array(
									__('Categories', 'thegem') => 'categories',
									__('Featured Products', 'thegem') => 'featured',
									__('On Sale Products', 'thegem') => 'sale',
									__('Recent Products', 'thegem') => 'recent',
								),
								'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
							),
							array(
								'type' => 'dropdown',
								'heading' => __('Select Category', 'thegem'),
								'param_name' => 'filters_tabs_tab_products_cat',
								'value' => get_woo_category_productGrid('all'),
								'dependency' => array(
									'element' => 'filters_tabs_tab_filter_by',
									'value' => array('categories')
								),
								'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
							),
						)),
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Product Tabs Style', 'thegem'),
						'param_name' => 'product_tabs_head',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('tabs')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Alignment', 'thegem'),
						'param_name' => 'product_tabs_alignment',
						'value' => array(
							__('Select Alignment', 'thegem') => '',
							__('Left', 'thegem') => 'left',
							__('Right', 'thegem') => 'right',
							__('Center', 'thegem') => 'center',
						),
						'std' => '',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('tabs')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Title Bottom Spacing', 'thegem'),
						'param_name' => 'product_tabs_title_bottom_spacing',
						'dependency' => array(
							'element' => 'filters_tabs_style',
							'value' => array('default')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Colors', 'thegem'),
						'param_name' => 'product_tabs_colors_header',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('tabs')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Title Color', 'thegem'),
						'param_name' => 'product_tabs_title_color',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('tabs')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active Tab Color', 'thegem'),
						'param_name' => 'product_tabs_tab_color_active',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('tabs')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal Tab Color', 'thegem'),
						'param_name' => 'product_tabs_tab_color_normal',
						'dependency' => array(
							'element' => 'filters_style',
							'value' => array('tabs')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Separator', 'thegem'),
						'param_name' => 'product_tabs_separator_header',
						'dependency' => array(
							'element' => 'filters_tabs_title_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Separator Width', 'thegem'),
						'param_name' => 'product_tabs_tab_separator_width',
						'dependency' => array(
							'element' => 'filters_tabs_title_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Filters & Sorting', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Separator Color', 'thegem'),
						'param_name' => 'product_tabs_tab_separator_color',
						'dependency' => array(
							'element' => 'filters_tabs_title_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Filters & Sorting', 'thegem')
					),

					/*End filters*/
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Additional Display Options', 'thegem'),
						'param_name' => 'additional_display_options_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show only "Featured" Products', 'thegem'),
						'param_name' => 'featured_only',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show only "On Sale" Products', 'thegem'),
						'param_name' => 'sale_only',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show only "Recently Viewed" Products', 'thegem'),
						'param_name' => 'recently_viewed_only',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show only "New" Products', 'thegem'),
						'param_name' => 'new_only',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Hide "out of stock" products', 'thegem'),
						'param_name' => 'stock_only',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Load with AJAX', 'thegem'),
						'param_name' => 'grid_ajax_load',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'description' => __('Enable this in case you have activated any of the additional display options and you use full-page cache like WP Super Cache.', 'thegem'),
						'group' => __('Additional', 'thegem'),
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Additional Options', 'thegem'),
						'param_name' => 'additional_options_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Lazy Loading Animation', 'thegem'),
						'param_name' => 'loading_animation',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Animation Effect', 'thegem'),
						'param_name' => 'animation_effect',
						'std' => 'bounce',
						'value' => array(
							__('Bounce', 'thegem') => 'bounce',
							__('Move Up', 'thegem') => 'move-up',
							__('Fade In', 'thegem') => 'fade-in',
							__('Fall Perspective', 'thegem') => 'fall-perspective',
							__('Scale', 'thegem') => 'scale',
							__('Flip', 'thegem') => 'flip'
						),
						'dependency' => array(
							'element' => 'loading_animation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Enable animation on mobile', 'thegem'),
						'param_name' => 'loading_animation_mobile',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'loading_animation',
							'not_empty' => true
						),
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Ignore Highlighted Products', 'thegem'),
						'param_name' => 'ignore_highlights',
						'std' => '1',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'layout_type',
							'value' => 'grid'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('WooCommerce Add to Cart Hook', 'thegem'),
						'param_name' => 'cart_hook',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Skeleton Preloader on grid loading', 'thegem'),
						'param_name' => 'skeleton_loader',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'dependency' => array(
							'element' => 'columns_desktop',
							'value' => array('2x', '3x', '4x', '5x', '6x')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('AJAX Preloader Type', 'thegem'),
						'param_name' => 'ajax_preloader_type',
						'value' => array(
							__('Default', 'thegem') => 'default',
							__('Minimal', 'thegem') => 'minimal',
						),
						'std' => 'default',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Preloader Color', 'thegem'),
						'param_name' => 'minimal_preloader_color',
						'dependency' => array(
							'element' => 'ajax_preloader_type',
							'value' => 'minimal'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Quick View', 'thegem'),
						'param_name' => 'quick_view_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show', 'thegem'),
						'param_name' => 'quick_view',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Quick View Text', 'thegem'),
						'param_name' => 'quick_view_text',
						'value' => __('Quick View', 'thegem'),
						'dependency' => array(
							'element' => 'quick_view',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Quick View Text Color', 'thegem'),
						'param_name' => 'quick_view_text_color',
						'dependency' => array(
							'element' => 'quick_view',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Quick View Background Color', 'thegem'),
						'param_name' => 'quick_view_background_color',
						'dependency' => array(
							'element' => 'quick_view',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Max. row\'s height in metro grid (px)', 'thegem'),
						'param_name' => 'metro_max_row_height',
						'description' => __('Metro grid auto sets the row\'s height. Specify max. allowed height for best appearance (380px recommended).', 'thegem'),
						'std' => 380,
						'dependency' => array(
							'element' => 'layout',
							'value' => array('metro')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Notification', 'thegem'),
						'param_name' => 'notification_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Added to Cart" Text', 'thegem'),
						'param_name' => 'added_cart_text',
						'value' => thegem_get_option('product_archive_added_cart_text'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Added to Wishlist" Text', 'thegem'),
						'param_name' => 'added_wishlist_text',
						'value' => thegem_get_option('product_archive_added_wishlist_text'),
						'dependency' => array(
							'element' => 'product_show_wishlist',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Removed from Wishlist" Text', 'thegem'),
						'param_name' => 'removed_wishlist_text',
						'value' => thegem_get_option('product_archive_removed_wishlist_text'),
						'dependency' => array(
							'element' => 'product_show_wishlist',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"View Cart" Button Text', 'thegem'),
						'param_name' => 'view_cart_button_text',
						'value' => thegem_get_option('product_archive_view_cart_button_text'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Checkout" Button Text', 'thegem'),
						'param_name' => 'checkout_button_text',
						'value' => thegem_get_option('product_archive_checkout_button_text'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"View Wishlist" Button Text', 'thegem'),
						'param_name' => 'view_wishlist_button_text',
						'value' => thegem_get_option('product_archive_view_wishlist_button_text'),
						'dependency' => array(
							'element' => 'product_show_wishlist',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"No Products Found" Button Text', 'thegem'),
						'param_name' => 'not_found_text',
						'value' => thegem_get_option('product_archive_not_found_text'),
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Notification Style', 'thegem'),
						'param_name' => 'notification_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Stay visible, ms', 'thegem'),
						'param_name' => 'stay_visible',
						'value' => __('4000', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'notification_background_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'notification_text_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icon Color', 'thegem'),
						'param_name' => 'notification_icon_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('View Cart & View Wishlist Buttons Colors', 'thegem'),
						'param_name' => 'cart_wishlist_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'button_wishlist_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color on hover', 'thegem'),
						'param_name' => 'button_wishlist_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'button_wishlist_background_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color on hover', 'thegem'),
						'param_name' => 'button_wishlist_background_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'button_wishlist_border_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color on hover', 'thegem'),
						'param_name' => 'button_wishlist_border_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
				)
			)
		);

		add_filter('vc_autocomplete_gem_product_compact_grid_content_products_cat_callback', 'TheGemProductCategoryCategoryAutocompleteSuggesterBySlug', 10, 1);
		add_filter('vc_autocomplete_gem_product_compact_grid_content_products_cat_render', 'TheGemProductCategoryCategoryRenderBySlugExact', 10, 1);
		add_filter('vc_autocomplete_gem_product_compact_grid_content_products_attr_val_callback', 'TheGemProductAttributesAutocompleteSuggester', 10, 1);
		add_filter('vc_autocomplete_gem_product_compact_grid_content_products_attr_val_render', 'TheGemProductAttributesRenderExact', 10, 1);

		$shortcodes['product_compact_grid'] = array(
			'name' => __('Products Compact Grid/List', 'thegem'),
			'base' => 'gem_product_compact_grid',
			'icon' => 'thegem-icon-wpb-ui-product-grid',
			'category' => array(__( 'TheGem', 'thegem' ), esc_html__( 'WooCommerce', 'js_composer' )),
			'description' => __('Display products compact grid', 'thegem'),
			'params' => array_merge(
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Products', 'thegem'),
						'param_name' => 'products_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Source', 'thegem'),
						'param_name' => 'products_source_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Select Products Categories', 'thegem'),
						'param_name' => 'select_products_categories',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'autocomplete',
						'heading' => __('Select Products Categories', 'thegem'),
						'param_name' => 'content_products_cat',
						'settings' => array(
							'multiple' => true,
							'sortable' => true,
						),
						'save_always' => true,
						'dependency' => array(
							'element' => 'select_products_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Select Products Attributes', 'thegem'),
						'param_name' => 'select_products_attributes',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'autocomplete',
						'heading' => __('Select Products Attributes', 'thegem'),
						'param_name' => 'content_products_attr_val',
						'settings' => array(
							'multiple' => true,
							'sortable' => true,
						),
						'save_always' => true,
						'dependency' => array(
							'element' => 'select_products_attributes',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Sorting', 'thegem'),
						'param_name' => 'products_sorting_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Default Sorting Order By', 'thegem'),
						'param_name' => 'orderby',
						'value' => array(
							__('WooCommerce Default Sorting', 'thegem') => 'default',
							__('Date', 'thegem') => 'date',
							__('Popularity', 'thegem') => 'popularity',
							__('Rating', 'thegem') => 'rating',
							__('Name', 'thegem') => 'title',
							__('Price', 'thegem') => 'price',
							__('Random', 'thegem') => 'rand',
						),
						'std' => 'default',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Sorting Order', 'thegem'),
						'param_name' => 'order',
						'value' => array(
							__('ASC', 'thegem') => 'asc',
							__('DESC', 'thegem') => 'desc',
						),
						'std' => 'asc',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_hidden_param',
						'param_name' => 'new_default_sorting',
						'std' => '1',
						'save_always' => true,
						'edit_field_class' => 'no-top-padding vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Layout', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Layout', 'thegem'),
						'param_name' => 'layout',
						'value' => array(
							__('Compact List', 'thegem') => 'list',
							__('Compact Grid', 'thegem') => 'grid',
						),
						'std' => 'list',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Columns', 'thegem'),
						'param_name' => 'columns',
						'value' => array(
							__('1x columns', 'thegem') => '1x',
							__('2x columns', 'thegem') => '2x',
							__('3x columns', 'thegem') => '3x',
							__('4x columns', 'thegem') => '4x',
							__('5x columns', 'thegem') => '5x',
							__('6x columns', 'thegem') => '6x',
						),
						'std' => '2x',
						'dependency' => array(
							'element' => 'layout',
							'value' => array('grid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Image Aspect Ratio', 'thegem'),
						'param_name' => 'image_aspect_ratio',
						'value' => array(
							__('Portrait', 'thegem') => 'portrait',
							__('Square', 'thegem') => 'square',
						),
						'std' => 'portrait',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Caption', 'thegem'),
						'param_name' => 'caption_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show categories', 'thegem'),
						'param_name' => 'product_show_categories',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show product title', 'thegem'),
						'param_name' => 'product_show_title',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show product price', 'thegem'),
						'param_name' => 'product_show_price',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show product reviews', 'thegem'),
						'param_name' => 'product_show_reviews',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Items Count', 'thegem'),
						'param_name' => 'items_per_page',
						'value' => '4',
						'description' => __('Use -1 to show all', 'thegem'),
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Grid image style', 'thegem'),
						'param_name' => 'grid_img_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Gaps', 'thegem'),
						'param_name' => 'gaps_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'gaps',
						'value' => __('20', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'gaps_tablet',
						'value' => __('20', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'gaps_mobile',
						'value' => __('20', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Radius', 'thegem'),
						'param_name' => 'image_border_radius',
						'dependency' => array(
							'element' => 'layout',
							'value' => array('list')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Product Separator', 'thegem'),
						'param_name' => 'product_separator_sub_delim_head',
						'dependency' => array(
							'element' => 'layout',
							'value' => array('list')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show', 'thegem'),
						'param_name' => 'product_separator',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'layout',
							'value' => array('list')
						),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Width', 'thegem'),
						'param_name' => 'product_separator_width',
						'dependency' => array(
							'element' => 'product_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'product_separator_color',
						'dependency' => array(
							'element' => 'product_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Caption style', 'thegem'),
						'param_name' => 'caption_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Categories', 'thegem'),
						'param_name' => 'categories_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'categories_color_normal',
						'dependency' => array(
							'element' => 'product_show_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'categories_color_hover',
						'dependency' => array(
							'element' => 'product_show_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Product Title', 'thegem'),
						'param_name' => 'product_title_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_title',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'title_color_normal',
						'dependency' => array(
							'element' => 'product_show_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'title_color_hover',
						'dependency' => array(
							'element' => 'product_show_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Product Price', 'thegem'),
						'param_name' => 'product_price_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_price',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'price_color_normal',
						'dependency' => array(
							'element' => 'product_show_price',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Rating Stars', 'thegem'),
						'param_name' => 'rated_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Rated Color', 'thegem'),
						'param_name' => 'rating_rated_color',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Base Color', 'thegem'),
						'param_name' => 'rating_base_color',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Caption Container Style', 'thegem'),
						'param_name' => 'caption_container_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Content Alignment', 'thegem'),
						'param_name' => 'caption_alignment',
						'value' => array(
							__('Left', 'thegem') => 'left',
							__('Centered', 'thegem') => 'center',
							__('Right', 'thegem') => 'right',
						),
						'std' => 'left',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'caption_background',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Additional Options', 'thegem'),
						'param_name' => 'additional_options_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show only "Featured" Products', 'thegem'),
						'param_name' => 'featured_only',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show only "On Sale" Products', 'thegem'),
						'param_name' => 'sale_only',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show only "Recently Viewed" Products', 'thegem'),
						'param_name' => 'recently_viewed_only',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show only "New" Products', 'thegem'),
						'param_name' => 'new_only',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
				)
			)
		);

		add_filter('vc_autocomplete_gem_product_grid_carousel_content_products_cat_callback', 'TheGemProductCategoryCategoryAutocompleteSuggesterBySlug', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_carousel_content_products_cat_render', 'TheGemProductCategoryCategoryRenderBySlugExact', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_carousel_content_products_attr_val_callback', 'TheGemProductAttributesAutocompleteSuggester', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_carousel_content_products_attr_val_render', 'TheGemProductAttributesRenderExact', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_carousel_content_products_tags_callback', 'TheGemProductTagsAutocompleteSuggester', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_carousel_content_products_tags_render', 'TheGemProductTagsRenderExact', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_carousel_content_products_callback', 'TheGemProductsAutocompleteSuggester', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_carousel_content_products_render', 'TheGemProductsRenderExact', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_carousel_exclude_products_callback', 'TheGemProductsAutocompleteSuggester', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_carousel_exclude_products_render', 'TheGemProductsRenderExact', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_carousel_exclude_product_terms_callback', 'TheGemTaxonomyAutocompleteSuggesterById', 10, 3);
		add_filter('vc_autocomplete_gem_product_grid_carousel_exclude_product_terms_render', 'TheGemTaxonomyRenderById', 10, 1);

		$shortcodes['product_grid_carousel'] = array(
			'name' => __('Products Carousel', 'thegem'),
			'base' => 'gem_product_grid_carousel',
			'icon' => 'thegem-icon-wpb-ui-product-slider',
			'category' => $product_carousel_shortcode_categories,
			'description' => __('Display products grid', 'thegem'),
			'params' => array_merge(
				array(
					array(
						'type' => 'textfield',
						'heading' => __('Unique Grid ID', 'thegem'),
						'param_name' => 'portfolio_uid',
						'value' => 'grid_' . substr(md5(rand()), 0, 7),
						'description' => __('In case of adding multiple product grids on the same page please ensure that each product grid has its own unique ID to avoid conflicts.', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem'),
						'save_always' => true,
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Products', 'thegem'),
						'param_name' => 'products_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Source', 'thegem'),
						'param_name' => 'products_source_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
				),
				$product_source_fields_single,
				$product_source_fields_custom,
				array(
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Sorting', 'thegem'),
						'param_name' => 'products_sorting_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Default Sorting Order By', 'thegem'),
						'param_name' => 'orderby',
						'value' => array(
							__('WooCommerce Default Sorting', 'thegem') => 'default',
							__('Date', 'thegem') => 'date',
							__('Popularity', 'thegem') => 'popularity',
							__('Rating', 'thegem') => 'rating',
							__('Name', 'thegem') => 'title',
							__('Price', 'thegem') => 'price',
							__('Random', 'thegem') => 'rand',
						),
						'std' => 'default',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Sorting Order', 'thegem'),
						'param_name' => 'order',
						'value' => array(
							__('ASC', 'thegem') => 'asc',
							__('DESC', 'thegem') => 'desc',
						),
						'std' => 'asc',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Layout', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Skin', 'thegem'),
						'param_name' => 'extended_grid_skin',
						'value' => array(
							__('Below, Default, Cart Button', 'thegem') => 'below-default-cart-button',
							__('Below, Default, Cart Icon', 'thegem') => 'below-default-cart-icon',
							__('Below, Solid, Cart Disabled', 'thegem') => 'below-cart-disabled',
							__('Below, Border, Cart Icon', 'thegem') => 'below-border-cart-icon',
							__('Below, Shadow Hover 01', 'thegem') => 'below-shadow-hover-01',
							__('Below, Shadow Hover 02', 'thegem') => 'below-shadow-hover-02',
							__('Below, Rounded Images', 'thegem') => 'below-rounded-images',
							__('Below, Rectangle Button', 'thegem') => 'below-rectangle-button-01',
							__('Below, Product Separator 01', 'thegem') => 'below-separator-01',
							__('Below, Product Separator 02', 'thegem') => 'below-separator-02',
							__('Image, Default, Cart Button', 'thegem') => 'image-default-cart-button',
							__('Image, Default, Cart Icon', 'thegem') => 'image-default-cart-icon',
							__('Image, Solid Caption Background', 'thegem') => 'image-solid-background',
							__('Image, Rounded Corners', 'thegem') => 'image-rounded-corners',
							__('Image, Shadow Hover 01', 'thegem') => 'image-shadow-hover-01',
							__('Image, Shadow', 'thegem') => 'image-shadow',
							__('Image, Product Separator 01', 'thegem') => 'image-separator-01',
							__('Image, Product Separator 02', 'thegem') => 'image-separator-02',
							__('Hover, Default', 'thegem') => 'hover-default',
							__('Hover, Rounded Corners', 'thegem') => 'hover-rounded-corners',
							__('Hover, Solid Caption Background', 'thegem') => 'hover-solid-background',
							__('Hover, Product Separator', 'thegem') => 'hover-separator',
							__('Hover, Centered Caption', 'thegem') => 'hover-centered-caption',
							__('Hover, Shadow Hover', 'thegem') => 'hover-shadow-hover',
							__('Hover, Gradient Hover', 'thegem') => 'hover-gradient-hover',
						),
						'dependency' => array(
							'callback' => 'extended_grid_skin_callback',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Columns Desktop', 'thegem'),
						'param_name' => 'columns_desktop',
						'value' => array(
							__('1x columns', 'thegem') => '1x',
							__('2x columns', 'thegem') => '2x',
							__('3x columns', 'thegem') => '3x',
							__('4x columns', 'thegem') => '4x',
							__('5x columns', 'thegem') => '5x',
							__('6x columns', 'thegem') => '6x',
							__('100% width', 'thegem') => '100%',
						),
						'std' => '4x',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Columns Tablet', 'thegem'),
						'param_name' => 'columns_tablet',
						'value' => array(
							__('1x columns', 'thegem') => '1x',
							__('2x columns', 'thegem') => '2x',
							__('3x columns', 'thegem') => '3x',
							__('4x columns', 'thegem') => '4x',
						),
						'std' => '3x',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Columns Mobile', 'thegem'),
						'param_name' => 'columns_mobile',
						'value' => array(
							__('1x columns', 'thegem') => '1x',
							__('2x columns', 'thegem') => '2x',
						),
						'std' => '2x',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Image Size', 'thegem'),
						'param_name' => 'image_size',
						'value' => array(
							__('As in Grid Layout (TheGem Thumbnails)', 'thegem') => 'default',
							__('Full Size', 'thegem') => 'full',
							__('WooCommerce Thumbnail', 'thegem') => 'woocommerce_thumbnail',
							__('WooCommerce Single', 'thegem') => 'woocommerce_single',
							__('WordPress Thumbnail', 'thegem') => 'thumbnail',
							__('WordPress Medium', 'thegem') => 'medium',
							__('WordPress Medium Large', 'thegem') => 'medium_large',
							__('WordPress Large', 'thegem') => 'large',
							__('1536x1536', 'thegem') => '1536x1536',
							__('2048x2048', 'thegem') => '2048x2048',
						),
						'std' => 'default',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Image Ratio', 'thegem'),
						'param_name' => 'image_ratio',
						'std' => 1,
						'dependency' => array(
							'element' => 'image_size',
							'value' => array('full')
						),
						'description' => __('Specify a decimal value, i.e. instead of 3:4, specify 0.75. Use a dot as a decimal separator. Leave blank to show the original image ratio', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Image Aspect Ratio', 'thegem'),
						'param_name' => 'image_aspect_ratio',
						'value' => array(
							__('Portrait', 'thegem') => 'portrait',
							__('Square', 'thegem') => 'square',
							__('Custom Selection', 'thegem') => 'custom',
						),
						'std' => 'portrait',
						'dependency' => array(
							'element' => 'image_size',
							'value' => array('default')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Image Ratio', 'thegem'),
						'param_name' => 'image_ratio_custom',
						'dependency' => array(
							'element' => 'image_aspect_ratio',
							'value' => array('custom')
						),
						'description' => __('Specify a decimal value, i.e. instead of 3:4, specify 0.75. Use a dot as a decimal separator. Leave blank to show the original image ratio', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('100% Width Columns', 'thegem'),
						'param_name' => 'columns_100',
						'value' => array(
							__('4x columns', 'thegem') => '4',
							__('5x columns', 'thegem') => '5',
							__('6x columns', 'thegem') => '6',
						),
						'std' => '4',
						'dependency' => array(
							'element' => 'columns_desktop',
							'value' => array('100%')
						),
						'description' => __('Number of columns for 100% width grid for desktop resolutions starting from 1920 px and above', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Title', 'thegem'),
						'param_name' => 'cross_sell_title_delim_head',
						'dependency' => array(
							'element' => 'source_type',
							'value' => array('cross_sell')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Title', 'thegem'),
						'param_name' => 'show_cross_sell_title',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'source_type',
							'value' => array('cross_sell')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Title Text', 'thegem'),
						'param_name' => 'cross_sell_title_text',
						'value' => __('You may also like', 'thegem'),
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Title Alignment', 'thegem'),
						'param_name' => 'cross_sell_title_alignment',
						'value' => array(
							__('Left', 'thegem') => 'left',
							__('Center', 'thegem') => 'center',
							__('Right', 'thegem') => 'right',
						),
						'std' => 'left',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('HTML Tag', 'thegem'),
						'param_name' => 'cross_sell_title_tag',
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
						'std' => 'div',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Style', 'thegem'),
						'param_name' => 'cross_sell_title_style',
						'value' => array(
							__('Default', 'thegem') => 'title-h3',
							__('Title H1', 'thegem') => 'title-h1',
							__('Title H2', 'thegem') => 'title-h2',
							__('Title H3', 'thegem') => 'title-h3',
							__('Title H4', 'thegem') => 'title-h4',
							__('Title H5', 'thegem') => 'title-h5',
							__('Title H6', 'thegem') => 'title-h6',
							__('Title xLarge', 'thegem') => 'title-xlarge',
							__('Styled Subtitle', 'thegem') => 'styled-subtitle',
							__('Main Menu', 'thegem') => 'title-main-menu',
							__('Body', 'thegem') => 'text-body',
							__('Tiny Body', 'thegem') => 'text-body-tiny',
						),
						'std' => 'title-h3',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Font weight', 'thegem'),
						'param_name' => 'cross_sell_title_weight',
						'value' => array(
							__('Default', 'thegem') => '',
							__('Thin', 'thegem') => 'light',
						),
						'std' => 'light',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Letter spacing', 'thegem'),
						'param_name' => 'cross_sell_title_letter_spacing',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Text transform', 'thegem'),
						'param_name' => 'cross_sell_title_transform',
						'value' => array(
							__('Default', 'thegem') => '',
							__('None', 'thegem') => 'none',
							__('Capitalize', 'thegem') => 'capitalize',
							__('Lowercase', 'thegem') => 'lowercase',
							__('Uppercase', 'thegem') => 'uppercase',
						),
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'cross_sell_title_color',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top Spacing (desktop)', 'thegem'),
						'param_name' => 'cross_sell_title_top_spacing_desktop',
						'value' => '',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top Spacing (tablet)', 'thegem'),
						'param_name' => 'cross_sell_title_top_spacing_tablet',
						'value' => '',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top Spacing (mobile)', 'thegem'),
						'param_name' => 'cross_sell_title_top_spacing_mobile',
						'value' => '',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom Spacing (desktop)', 'thegem'),
						'param_name' => 'cross_sell_title_bottom_spacing_desktop',
						'value' => '',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom Spacing (tablet)', 'thegem'),
						'param_name' => 'cross_sell_title_bottom_spacing_tablet',
						'value' => '',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom Spacing (mobile)', 'thegem'),
						'param_name' => 'cross_sell_title_bottom_spacing_mobile',
						'value' => '',
						'dependency' => array(
							'element' => 'show_cross_sell_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Products & Layout', 'thegem'),
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Caption', 'thegem'),
						'param_name' => 'caption_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Caption Position', 'thegem'),
						'param_name' => 'caption_position',
						'value' => array(
							__('Below Image', 'thegem') => 'page',
							__('On Hover', 'thegem') => 'hover',
							__('On Image', 'thegem') => 'image',
						),
						'std' => 'page',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show categories', 'thegem'),
						'param_name' => 'product_show_categories',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show product title', 'thegem'),
						'param_name' => 'product_show_title',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show product price', 'thegem'),
						'param_name' => 'product_show_price',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show product reviews', 'thegem'),
						'param_name' => 'product_show_reviews',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Social Sharing', 'thegem'),
						'param_name' => 'social_sharing',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Add to cart', 'thegem'),
						'param_name' => 'add_to_cart_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show add to cart', 'thegem'),
						'param_name' => 'product_show_add_to_cart',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show add to cart on mobile', 'thegem'),
						'param_name' => 'product_show_add_to_cart_mobiles',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'product_show_add_to_cart',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Add To Card Type', 'thegem'),
						'param_name' => 'add_to_cart_type',
						'value' => array(
							__('Icon', 'thegem') => 'icon',
							__('Button', 'thegem') => 'buttons',
						),
						'std' => 'buttons',
						'dependency' => array(
							'element' => 'product_show_add_to_cart',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show Icon', 'thegem'),
						'param_name' => 'cart_button_show_icon',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'product_show_add_to_cart',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Add To Cart Button Text', 'thegem'),
						'param_name' => 'cart_button_text',
						'value' => __('Add To Cart', 'thegem'),
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Select Options Button Text', 'thegem'),
						'param_name' => 'select_options_button_text',
						'value' => __('Select Options', 'thegem'),
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Add To Card Icon pack', 'thegem'),
						'param_name' => 'cart_button_icon_pack',
						'value' => array_merge(array(
							__('Elegant', 'thegem') => 'elegant',
							__('Material Design', 'thegem') => 'material',
							__('FontAwesome', 'thegem') => 'fontawesome',
							__('Header Icons', 'thegem') => 'thegem-header',
							__('Additional', 'thegem') => 'thegemdemo'),
							thegem_userpack_to_dropdown()
						),
						'std' => 'elegant',
						'dependency' => array(
							'element' => 'cart_button_show_icon',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Select Options Icon pack', 'thegem'),
						'param_name' => 'select_options_icon_pack',
						'value' => array_merge(array(
							__('Elegant', 'thegem') => 'elegant',
							__('Material Design', 'thegem') => 'material',
							__('FontAwesome', 'thegem') => 'fontawesome',
							__('Header Icons', 'thegem') => 'thegem-header',
							__('Additional', 'thegem') => 'thegemdemo'),
							thegem_userpack_to_dropdown()
						),
						'std' => 'elegant',
						'dependency' => array(
							'element' => 'cart_button_show_icon',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'cart_button_icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'cart_button_icon_pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'cart_button_icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'cart_button_icon_pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'cart_button_icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'cart_button_icon_pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'cart_button_icon_thegemheader',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'cart_button_icon_pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'cart_button_icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'cart_button_icon_pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'cart_button_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'cart_button_icon_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				)),
				array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'select_options_icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'select_options_icon_pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'select_options_icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'select_options_icon_pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'select_options_icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'select_options_icon_pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'select_options_icon_thegemheader',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'select_options_icon_pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'select_options_icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'select_options_icon_pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'select_options_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'select_options_icon_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				)),
				$wishlist_attr,
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Navigation', 'thegem'),
						'param_name' => 'navigation_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Number of Items', 'thegem'),
						'param_name' => 'items_per_page',
						'value' => '12',
						'description' => __('Use -1 to show all', 'thegem'),
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Dots Navigation', 'thegem'),
						'param_name' => 'show_dots_navigation',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Arrows Navigation', 'thegem'),
						'param_name' => 'show_arrows_navigation',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Arrows Position', 'thegem'),
						'param_name' => 'arrows_navigation_position',
						'value' => array(
							__('Outside Product Items', 'thegem') => 'outside',
							__('On Product Items', 'thegem') => 'on',
						),
						'std' => 'outside',
						'dependency' => array(
							'element' => 'show_arrows_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Arrows Visibility', 'thegem'),
						'param_name' => 'arrows_navigation_visibility',
						'value' => array(
							__('Visible on Hover', 'thegem') => 'hover',
							__('Always Visible', 'thegem') => 'always',
						),
						'std' => 'hover',
						'dependency' => array(
							'element' => 'show_arrows_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),

					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Labels', 'thegem'),
						'param_name' => 'labels_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Labels Style', 'thegem'),
						'param_name' => 'labels_design',
						'value' => array(
							__('Style 1', 'thegem') => '1',
							__('Style 2', 'thegem') => '2',
							__('Style 3', 'thegem') => '3',
							__('Style 4', 'thegem') => '4',
							__('Style 5', 'thegem') => '5',
							__('Style 6', 'thegem') => '6',
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('"New" Label', 'thegem'),
						'param_name' => 'product_show_new',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Label Text', 'thegem'),
						'param_name' => 'new_label_text',
						'value' => __('New', 'thegem'),
						'dependency' => array(
							'element' => 'product_show_new',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('"Sale" Label', 'thegem'),
						'param_name' => 'product_show_sale',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Sale Label Type', 'thegem'),
						'param_name' => 'sale_label_type',
						'value' => array(
							__('Show Discount Percentage', 'thegem') => 'percentage',
							__('Show Text', 'thegem') => 'text'
						),
						'dependency' => array(
							'element' => 'product_show_sale',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Prefix', 'thegem'),
						'param_name' => 'sale_label_prefix',
						'value' => __('-', 'thegem'),
						'dependency' => array(
							'element' => 'sale_label_type',
							'value' => array('percentage')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Suffix', 'thegem'),
						'param_name' => 'sale_label_suffix',
						'value' => __('%', 'thegem'),
						'dependency' => array(
							'element' => 'sale_label_type',
							'value' => array('percentage')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Label Text', 'thegem'),
						'param_name' => 'sale_label_text',
						'value' => __('On Sale', 'thegem'),
						'dependency' => array(
							'element' => 'sale_label_type',
							'value' => array('text')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('"Out of stock" Label', 'thegem'),
						'param_name' => 'product_show_out',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Label Text', 'thegem'),
						'param_name' => 'out_label_text',
						'value' => __('Out of stock', 'thegem'),
						'dependency' => array(
							'element' => 'product_show_out',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Grid image style', 'thegem'),
						'param_name' => 'grid_img_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Gaps', 'thegem'),
						'param_name' => 'gaps_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'image_gaps',
						'value' => __('42', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'image_gaps_tablet',
						'value' => __('42', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'image_gaps_mobile',
						'value' => __('42', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Product Separator', 'thegem'),
						'param_name' => 'product_separator_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show', 'thegem'),
						'param_name' => 'product_separator',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Width', 'thegem'),
						'param_name' => 'product_separator_width',
						'dependency' => array(
							'element' => 'product_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'product_separator_color',
						'dependency' => array(
							'element' => 'product_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Image Border', 'thegem'),
						'param_name' => 'thumb_border_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Width', 'thegem'),
						'param_name' => 'image_border_width',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Radius', 'thegem'),
						'param_name' => 'image_border_radius',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal Color', 'thegem'),
						'param_name' => 'image_border_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'image_border_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Apply border on caption container', 'thegem'),
						'param_name' => 'border_caption_container',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('', 'thegem'),
						'param_name' => 'thumb_border_end_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Enable Shadow', 'thegem'),
						'param_name' => 'enable_shadow',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Shadow color', 'thegem'),
						'param_name' => 'shadow_color',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'std' => 'rgba(0, 0, 0, 0.15)',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Position', 'thegem'),
						'param_name' => 'shadow_position',
						'value' => array(
							__('Outline', 'thegem') => 'outline',
							__('Inset', 'thegem') => 'inset'
						),
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'std' => 'outline',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Horizontal', 'thegem'),
						'param_name' => 'shadow_horizontal',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '0',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Vertical', 'thegem'),
						'param_name' => 'shadow_vertical',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '5',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Blur', 'thegem'),
						'param_name' => 'shadow_blur',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '5',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Spread', 'thegem'),
						'param_name' => 'shadow_spread',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '-5',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Apply shadow on caption container', 'thegem'),
						'param_name' => 'shadowed_container',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('', 'thegem'),
						'param_name' => 'thumb_border_end_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Hover Effect', 'thegem'),
						'param_name' => 'image_hover_effect_page',
						'value' => array(
							__('Show Next Image (Slide)', 'thegem') => 'slide',
							__('Show Next Image (Fade)', 'thegem') => 'fade',
							__('Disabled', 'thegem') => 'disabled',
						),
						'std' => 'fade',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Hover Effect', 'thegem'),
						'param_name' => 'image_hover_effect_image',
						'value' => array(
							__('Show Next Image (Slide)', 'thegem') => 'slide',
							__('Show Next Image (Fade)', 'thegem') => 'fade',
							__('Gradient', 'thegem') => 'gradient',
							__('Circular Overlay', 'thegem') => 'circular',
							__('Disabled', 'thegem') => 'disabled',
						),
						'std' => 'fade',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('image')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Hover Effect', 'thegem'),
						'param_name' => 'image_hover_effect_hover',
						'value' => array(
							__('Show Next Image (Slide)', 'thegem') => 'slide',
							__('Show Next Image (Fade)', 'thegem') => 'fade',
							__('Cyan Breeze', 'thegem') => 'default',
							__('Zooming White', 'thegem') => 'zooming-blur',
							__('Horizontal Sliding', 'thegem') => 'horizontal-sliding',
							__('Vertical Sliding', 'thegem') => 'vertical-sliding',
							__('Gradient', 'thegem') => 'gradient',
							__('Circular Overlay', 'thegem') => 'circular',
							__('Disabled', 'thegem') => 'disabled',
						),
						'std' => 'fade',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('hover')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Fallback Hover', 'thegem'),
						'param_name' => 'image_hover_effect_fallback',
						'value' => array(
							__('Disabled', 'thegem') => 'disabled',
							__('Zooming White', 'thegem') => 'zooming',
						),
						'std' => 'zooming',
						'description' => __('Used in case of only one product image', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Better Thumbnails Quality', 'thegem'),
						'param_name' => 'fullwidth_section_images',
						'value' => array(__('Yes', 'thegem') => '1'),
						'description' => __('Activate for better image quality in case of using in fullwidth section', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Caption style', 'thegem'),
						'param_name' => 'caption_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Background Preset', 'thegem'),
						'param_name' => 'caption_container_preset_hover',
						'value' => array(
							__('Light Gradient', 'thegem') => 'light',
							__('Dark Gradient', 'thegem') => 'dark',
							__('Solid transparent', 'thegem') => 'solid',
							__('Transparent', 'thegem') => 'transparent',
						),
						'std' => 'light',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('image', 'hover')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Content Alignment', 'thegem'),
						'param_name' => 'caption_container_alignment_hover',
						'value' => array(
							__('Default', 'thegem') => '',
							__('Left', 'thegem') => 'left',
							__('Centered', 'thegem') => 'center',
							__('Right', 'thegem') => 'right',
						),
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('image', 'hover')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'caption_container_preset_hover_background_color',
						'dependency' => array(
							'element' => 'caption_container_preset_hover',
							'value' => array('solid', 'light', 'dark')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Font Size Preset', 'thegem'),
						'param_name' => 'font_size_preset',
						'value' => array(
							__('Enlarged', 'thegem') => 'enlarged',
							__('Normal', 'thegem') => 'normal',
						),
						'std' => 'enlarged',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('hover')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Categories', 'thegem'),
						'param_name' => 'categories_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'categories_color_normal',
						'dependency' => array(
							'element' => 'product_show_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'categories_color_hover',
						'dependency' => array(
							'element' => 'product_show_categories',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Product Title', 'thegem'),
						'param_name' => 'product_title_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_title',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'title_color_normal',
						'dependency' => array(
							'element' => 'product_show_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'title_color_hover',
						'dependency' => array(
							'element' => 'product_show_title',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Product Price', 'thegem'),
						'param_name' => 'product_price_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_price',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'price_color_normal',
						'dependency' => array(
							'element' => 'product_show_price',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'price_color_hover',
						'dependency' => array(
							'element' => 'product_show_price',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Rating Stars Rated', 'thegem'),
						'param_name' => 'rated_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'rated_color_normal',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'rated_color_hover',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Rating Stars Base', 'thegem'),
						'param_name' => 'base_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'base_color_normal',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'base_color_hover',
						'dependency' => array(
							'element' => 'product_show_reviews',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Caption Container Style', 'thegem'),
						'param_name' => 'caption_container_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Preset', 'thegem'),
						'param_name' => 'caption_container_preset',
						'value' => array(
							__('Transparent', 'thegem') => 'transparent',
							__('White', 'thegem') => 'white',
							__('Gray', 'thegem') => 'gray',
							__('Dark', 'thegem') => 'dark',
						),
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Content Alignment', 'thegem'),
						'param_name' => 'caption_container_alignment',
						'value' => array(
							__('Default', 'thegem') => '',
							__('Left', 'thegem') => 'left',
							__('Centered', 'thegem') => 'center',
							__('Right', 'thegem') => 'right',
						),
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Caption Background Color', 'thegem'),
						'param_name' => 'caption_background',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Caption Background Color on hover', 'thegem'),
						'param_name' => 'caption_background_hover',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Separator Width', 'thegem'),
						'param_name' => 'spacing_separator_weight',
						'value' => '1',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Separator Color', 'thegem'),
						'param_name' => 'spacing_separator_color',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Separator Color on hover', 'thegem'),
						'param_name' => 'spacing_separator_color_hover',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('page')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Icon Style', 'thegem'),
						'param_name' => 'icon_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icons Color', 'thegem'),
						'param_name' => 'icons_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icons Color on hover', 'thegem'),
						'param_name' => 'icons_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icons Background Color', 'thegem'),
						'param_name' => 'icons_background_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icons Background Color on hover', 'thegem'),
						'param_name' => 'icons_background_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icons Border Color', 'thegem'),
						'param_name' => 'icons_border_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icons Border Color on hover', 'thegem'),
						'param_name' => 'icons_border_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'icons_border_width',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Buttons Style', 'thegem'),
						'param_name' => 'buttons_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'buttons_border_width',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Radius', 'thegem'),
						'param_name' => 'buttons_border_radius',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Icon Alignment', 'thegem'),
						'param_name' => 'buttons_icon_alignment',
						'value' => array(
							__('Left', 'thegem') => 'left',
							__('Right', 'thegem') => 'right',
						),
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Add to Cart button', 'thegem'),
						'param_name' => 'add_to_cart_colors_sub_delim_head',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'button_cart_color_normal',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color on hover', 'thegem'),
						'param_name' => 'button_cart_color_hover',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'button_cart_background_color_normal',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color on hover', 'thegem'),
						'param_name' => 'button_cart_background_color_hover',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'button_cart_border_color_normal',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color on hover', 'thegem'),
						'param_name' => 'button_cart_border_color_hover',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Select Options button', 'thegem'),
						'param_name' => 'select_options_colors_sub_delim_head',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'button_options_color_normal',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color on hover', 'thegem'),
						'param_name' => 'button_options_color_hover',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'button_options_background_color_normal',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color on hover', 'thegem'),
						'param_name' => 'button_options_background_color_hover',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'button_options_border_color_normal',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color on hover', 'thegem'),
						'param_name' => 'button_options_border_color_hover',
						'dependency' => array(
							'element' => 'add_to_cart_type',
							'value' => array('buttons')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Navigation Style', 'thegem'),
						'param_name' => 'navigation_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Arrows', 'thegem'),
						'param_name' => 'navigation_style_arrows_sub_delim_head',
						'dependency' => array(
							'element' => 'show_arrows_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal Arrow Color', 'thegem'),
						'param_name' => 'navigation_arrows_icon_color_normal',
						'dependency' => array(
							'element' => 'show_arrows_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Arrow Color', 'thegem'),
						'param_name' => 'navigation_arrows_icon_color_hover',
						'dependency' => array(
							'element' => 'show_arrows_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'navigation_arrows_border_width',
						'dependency' => array(
							'element' => 'arrows_navigation_position',
							'value' => array('on')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Radius', 'thegem'),
						'param_name' => 'navigation_arrows_border_radius',
						'dependency' => array(
							'element' => 'arrows_navigation_position',
							'value' => array('on')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'navigation_arrows_border_color_normal',
						'dependency' => array(
							'element' => 'arrows_navigation_position',
							'value' => array('on')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color on hover', 'thegem'),
						'param_name' => 'navigation_arrows_border_color_hover',
						'dependency' => array(
							'element' => 'arrows_navigation_position',
							'value' => array('on')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background', 'thegem'),
						'param_name' => 'navigation_arrows_background_color_normal',
						'dependency' => array(
							'element' => 'arrows_navigation_position',
							'value' => array('on')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background on hover', 'thegem'),
						'param_name' => 'navigation_arrows_background_color_hover',
						'dependency' => array(
							'element' => 'arrows_navigation_position',
							'value' => array('on')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Horizontal Spacing', 'thegem'),
						'param_name' => 'navigation_arrows_spacing',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top Spacing', 'thegem'),
						'param_name' => 'navigation_top_spacing',
						'description' => __('Note: px units are used by default. For % units add values with %, eg. 10%', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Dots Navigation', 'thegem'),
						'param_name' => 'navigation_style_dots_sub_delim_head',
						'dependency' => array(
							'element' => 'show_dots_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),

					array(
						'type' => 'textfield',
						'heading' => __('Top Spacing', 'thegem'),
						'param_name' => 'navigation_dots_spacing',
						'dependency' => array(
							'element' => 'show_dots_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'navigation_dots_border_width',
						'dependency' => array(
							'element' => 'show_dots_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'navigation_dots_border_color_normal',
						'dependency' => array(
							'element' => 'show_dots_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color active', 'thegem'),
						'param_name' => 'navigation_dots_border_color_active',
						'dependency' => array(
							'element' => 'show_dots_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background', 'thegem'),
						'param_name' => 'navigation_dots_background_color_normal',
						'dependency' => array(
							'element' => 'show_dots_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background active', 'thegem'),
						'param_name' => 'navigation_dots_background_color_active',
						'dependency' => array(
							'element' => 'show_dots_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Labels Style', 'thegem'),
						'param_name' => 'labels_style_more_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('"New" Label colors', 'thegem'),
						'param_name' => 'new_label_colors_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_new',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background', 'thegem'),
						'param_name' => 'new_label_background',
						'dependency' => array(
							'element' => 'product_show_new',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text', 'thegem'),
						'param_name' => 'new_label_text_color',
						'dependency' => array(
							'element' => 'product_show_new',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('"Sale" Label colors', 'thegem'),
						'param_name' => 'sale_label_colors_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_sale',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background', 'thegem'),
						'param_name' => 'sale_label_background',
						'dependency' => array(
							'element' => 'product_show_sale',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text', 'thegem'),
						'param_name' => 'sale_label_text_color',
						'dependency' => array(
							'element' => 'product_show_sale',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('"Out of stock" Label colors', 'thegem'),
						'param_name' => 'out_label_colors_sub_delim_head',
						'dependency' => array(
							'element' => 'product_show_out',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background', 'thegem'),
						'param_name' => 'out_label_background',
						'dependency' => array(
							'element' => 'product_show_out',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text', 'thegem'),
						'param_name' => 'out_label_text_color',
						'dependency' => array(
							'element' => 'product_show_out',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Labels Margin', 'thegem'),
						'param_name' => 'labels_margin_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top Margin', 'thegem'),
						'param_name' => 'labels_margin_top',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom Margin', 'thegem'),
						'param_name' => 'labels_margin_bottom',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left Margin', 'thegem'),
						'param_name' => 'labels_margin_left',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right Margin', 'thegem'),
						'param_name' => 'labels_margin_right',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show Product Tabs', 'thegem'),
						'param_name' => 'product_show_tabs',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Tabs Style', 'thegem'),
						'param_name' => 'filters_tabs_style',
						'value' => array(
							__('Default', 'thegem') => 'default',
							__('Alternative', 'thegem') => 'alternative'
						),
						'dependency' => array(
							'element' => 'product_show_tabs',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Title', 'thegem'),
						'param_name' => 'filters_tabs_title_header',
						'dependency' => array(
							'element' => 'product_show_tabs',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Title', 'thegem'),
						'param_name' => 'filters_tabs_title_text',
						'dependency' => array(
							'element' => 'product_show_tabs',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Style Preset', 'thegem'),
						'param_name' => 'filters_tabs_title_style_preset',
						'value' => array(
							__('Bold Title', 'thegem') => 'bold',
							__('Thin Title', 'thegem') => 'thin'
						),
						'dependency' => array(
							'element' => 'product_show_tabs',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Separator', 'thegem'),
						'param_name' => 'filters_tabs_title_separator',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'product_show_tabs',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Tabs', 'thegem'),
						'param_name' => 'filters_tabs_tabs_header',
						'dependency' => array(
							'element' => 'product_show_tabs',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'param_group',
						'heading' => __('Tabs', 'thegem'),
						'param_name' => 'filters_tabs_tabs',
						'value' => urlencode(json_encode(array(
							array(
								'filters_tabs_tab_title' => 'Featured Products',
								'filters_tabs_tab_filter_by' => 'featured',
							),
							array(
								'filters_tabs_tab_title' => 'On Sale Products',
								'filters_tabs_tab_filter_by' => 'sale',
							),
						))),
						'dependency' => array(
							'element' => 'product_show_tabs',
							'not_empty' => true
						),
						'params' => array_merge(array(
							array(
								'type' => 'textfield',
								'heading' => __('Title', 'thegem'),
								'param_name' => 'filters_tabs_tab_title',
								'value' => __('Title', 'thegem'),
								'edit_field_class' => 'vc_column vc_col-sm-4',
							),
							array(
								'type' => 'dropdown',
								'heading' => __('Filter By', 'thegem'),
								'param_name' => 'filters_tabs_tab_filter_by',
								'value' => array(
									__('Categories', 'thegem') => 'categories',
									__('Featured Products', 'thegem') => 'featured',
									__('On Sale Products', 'thegem') => 'sale',
									__('Recent Products', 'thegem') => 'recent',
								),
								'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
							),
							array(
								'type' => 'dropdown',
								'heading' => __('Select Category', 'thegem'),
								'param_name' => 'filters_tabs_tab_products_cat',
								'value' => get_woo_category_productGrid(),
								'dependency' => array(
									'element' => 'filters_tabs_tab_filter_by',
									'value' => array('categories')
								),
								'edit_field_class' => 'vc_column vc_col-sm-4 no-top-padding',
							),
						)),
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Product Tabs Style', 'thegem'),
						'param_name' => 'product_tabs_head',
						'dependency' => array(
							'element' => 'product_show_tabs',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Alignment', 'thegem'),
						'param_name' => 'product_tabs_alignment',
						'value' => array(
							__('Select Alignment', 'thegem') => '',
							__('Left', 'thegem') => 'left',
							__('Right', 'thegem') => 'right',
							__('Center', 'thegem') => 'center',
						),
						'std' => '',
						'dependency' => array(
							'element' => 'product_show_tabs',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Title Bottom Spacing', 'thegem'),
						'param_name' => 'product_tabs_title_bottom_spacing',
						'dependency' => array(
							'element' => 'product_show_tabs',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Colors', 'thegem'),
						'param_name' => 'product_tabs_colors_header',
						'dependency' => array(
							'element' => 'product_show_tabs',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Title Color', 'thegem'),
						'param_name' => 'product_tabs_title_color',
						'dependency' => array(
							'element' => 'product_show_tabs',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active Tab Color', 'thegem'),
						'param_name' => 'product_tabs_tab_color_active',
						'dependency' => array(
							'element' => 'product_show_tabs',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal Tab Color', 'thegem'),
						'param_name' => 'product_tabs_tab_color_normal',
						'dependency' => array(
							'element' => 'product_show_tabs',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Separator', 'thegem'),
						'param_name' => 'product_tabs_separator_header',
						'dependency' => array(
							'element' => 'filters_tabs_title_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Separator Width', 'thegem'),
						'param_name' => 'product_tabs_tab_separator_width',
						'dependency' => array(
							'element' => 'filters_tabs_title_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Separator Color', 'thegem'),
						'param_name' => 'product_tabs_tab_separator_color',
						'dependency' => array(
							'element' => 'filters_tabs_title_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Product Tabs', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Additional Options', 'thegem'),
						'param_name' => 'additional_options_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Sliding Animation', 'thegem'),
						'param_name' => 'sliding_animation',
						'std' => 'default',
						'value' => array(
							__('Default', 'thegem') => 'default',
							__('One-by-One', 'thegem') => 'one-by-one'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Slider Loop', 'thegem'),
						'param_name' => 'slider_loop',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Autoscroll', 'thegem'),
						'param_name' => 'autoscroll',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Autoplay Speed', 'thegem'),
						'param_name' => 'autoscroll_speed',
						'std' => 2000,
						'dependency' => array(
							'element' => 'autoscroll',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Init carousel on scroll', 'thegem'),
						'param_name' => 'slider_scroll_init',
						'description' => __('This option allows you to init carousel script only when visitor scroll the page to the slider. Useful for performance optimization.', 'thegem'),
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Lazy Loading Animation', 'thegem'),
						'param_name' => 'loading_animation',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Animation Effect', 'thegem'),
						'param_name' => 'animation_effect',
						'std' => 'bounce',
						'value' => array(
							__('Bounce', 'thegem') => 'bounce',
							__('Move Up', 'thegem') => 'move-up',
							__('Fade In', 'thegem') => 'fade-in',
							__('Fall Perspective', 'thegem') => 'fall-perspective',
							__('Scale', 'thegem') => 'scale',
							__('Flip', 'thegem') => 'flip'
						),
						'dependency' => array(
							'element' => 'loading_animation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Disable Skeleton Preloader', 'thegem'),
						'param_name' => 'disable_preloader',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show only "Featured" Products', 'thegem'),
						'param_name' => 'featured_only',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show only "On Sale" Products', 'thegem'),
						'param_name' => 'sale_only',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show only "Recently Viewed" Products', 'thegem'),
						'param_name' => 'recently_viewed_only',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show only "New" Products', 'thegem'),
						'param_name' => 'new_only',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Hide "out of stock" products', 'thegem'),
						'param_name' => 'stock_only',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('WooCommerce Add to Cart Hook', 'thegem'),
						'param_name' => 'cart_hook',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Quick View', 'thegem'),
						'param_name' => 'quick_view_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show', 'thegem'),
						'param_name' => 'quick_view',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Quick View Text', 'thegem'),
						'param_name' => 'quick_view_text',
						'value' => __('Quick View', 'thegem'),
						'dependency' => array(
							'element' => 'quick_view',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Quick View Text Color', 'thegem'),
						'param_name' => 'quick_view_text_color',
						'dependency' => array(
							'element' => 'quick_view',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Quick View Background Color', 'thegem'),
						'param_name' => 'quick_view_background_color',
						'dependency' => array(
							'element' => 'quick_view',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Notification', 'thegem'),
						'param_name' => 'notification_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Added to Cart" Text', 'thegem'),
						'param_name' => 'added_cart_text',
						'value' => thegem_get_option('product_archive_added_cart_text'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Added to Wishlist" Text', 'thegem'),
						'param_name' => 'added_wishlist_text',
						'value' => thegem_get_option('product_archive_added_wishlist_text'),
						'dependency' => array(
							'element' => 'product_show_wishlist',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Removed from Wishlist" Text', 'thegem'),
						'param_name' => 'removed_wishlist_text',
						'value' => thegem_get_option('product_archive_removed_wishlist_text'),
						'dependency' => array(
							'element' => 'product_show_wishlist',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"View Cart" Button Text', 'thegem'),
						'param_name' => 'view_cart_button_text',
						'value' => thegem_get_option('product_archive_view_cart_button_text'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Checkout" Button Text', 'thegem'),
						'param_name' => 'checkout_button_text',
						'value' => thegem_get_option('product_archive_checkout_button_text'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"View Wishlist" Button Text', 'thegem'),
						'param_name' => 'view_wishlist_button_text',
						'value' => thegem_get_option('product_archive_view_wishlist_button_text'),
						'dependency' => array(
							'element' => 'product_show_wishlist',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"No Products Found" Button Text', 'thegem'),
						'param_name' => 'not_found_text',
						'value' => thegem_get_option('product_archive_not_found_text'),
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Notification Style', 'thegem'),
						'param_name' => 'notification_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Stay visible, ms', 'thegem'),
						'param_name' => 'stay_visible',
						'value' => __('4000', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'notification_background_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'notification_text_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icon Color', 'thegem'),
						'param_name' => 'notification_icon_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('View Cart & View Wishlist Buttons Colors', 'thegem'),
						'param_name' => 'cart_wishlist_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'button_wishlist_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color on hover', 'thegem'),
						'param_name' => 'button_wishlist_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'button_wishlist_background_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color on hover', 'thegem'),
						'param_name' => 'button_wishlist_background_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'button_wishlist_border_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color on hover', 'thegem'),
						'param_name' => 'button_wishlist_border_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
				)
			)
		);

		$creative_categories_schemes_list = [
			'6' => [
				'6a' => [
					'count' => 9,
					0 => 'squared',
				],
				'6b' => [
					'count' => 7,
					0 => 'squared',
					1 => 'horizontal',
					6 => 'horizontal',
				],
				'6c' => [
					'count' => 9,
					0 => 'horizontal',
					3 => 'horizontal',
					6 => 'horizontal',
				],
				'6d' => [
					'count' => 9,
					0 => 'horizontal',
					1 => 'horizontal',
					2 => 'horizontal',
				],
				'6e' => [
					'count' => 6,
					0 => 'squared',
					1 => 'squared',
				]
			],
			'5' => [
				'5a' => [
					'count' => 7,
					0 => 'squared',
				],
				'5b' => [
					'count' => 8,
					0 => 'horizontal',
					4 => 'horizontal',
				],
				'5c' => [
					'count' => 6,
					0 => 'horizontal',
					1 => 'horizontal',
					4 => 'horizontal',
					5 => 'horizontal',
				],
				'5d' => [
					'count' => 4,
					0 => 'squared',
					1 => 'vertical',
					2 => 'horizontal',
					3 => 'horizontal',
				]
			],
			'4' => [
				'4a' => [
					'count' => 5,
					0 => 'squared',
				],
				'4b' => [
					'count' => 4,
					0 => 'squared',
					1 => 'horizontal',
				],
				'4c' => [
					'count' => 4,
					0 => 'squared',
					1 => 'vertical',
				],
				'4d' => [
					'count' => 7,
					0 => 'vertical',
				],
				'4e' => [
					'count' => 4,
					0 => 'vertical',
					1 => 'vertical',
					2 => 'horizontal',
					3 => 'horizontal',
				],
				'4f' => [
					'count' => 6,
					0 => 'horizontal',
					5 => 'horizontal',
				]
			],
			'3' => [
				'3a' => [
					'count' => 4,
					0 => 'vertical',
					1 => 'vertical',
				],
				'3b' => [
					'count' => 4,
					1 => 'horizontal',
					2 => 'horizontal',
				],
				'3c' => [
					'count' => 5,
					0 => 'vertical',
				],
				'3d' => [
					'count' => 5,
					0 => 'horizontal',
				],
				'3e' => [
					'count' => 3,
					0 => 'squared',
				],
				'3f' => [
					'count' => 4,
					0 => 'horizontal',
					1 => 'vertical',
				],
				'3g' => [
					'count' => 4,
					0 => 'vertical',
					3 => 'horizontal',
				],
				'3h' => [
					'count' => 5,
					2 => 'vertical',
				]
			],
		];

		$creative_categories_schemes_values = array();

		foreach ((array)$creative_categories_schemes_list as $scheme_col => $scheme_values) {

			$options = [];
			$default = '';
			$i = 1;

			foreach ($scheme_values as $scheme_key => $scheme_val) {
				$options[__('Scheme', 'thegem') . $i] = $scheme_key;
				if ($i == 1) {
					$default = $scheme_key;
				}
				$i++;
			}

			array_push($creative_categories_schemes_values, array(
				'type' => 'dropdown',
				'heading' => __('Layout Scheme', 'thegem') . ' ' . strval($scheme_col) . 'x',
				'param_name' => 'layout_scheme_' . strval($scheme_col) . 'x',
				'value' => $options,
				'std' => $default,
				'edit_field_class' => 'vc_column vc_col-sm-6 layout_scheme',
				'group' => __('Categories & Layout', 'thegem')
			));

			foreach ($scheme_values as $scheme_key => $scheme_val) {

				array_push($creative_categories_schemes_values, array(

					'type' => 'thegem_delimeter_heading_two_level',
					'heading' => '',
					'param_name' => 'layout_scheme_' . $scheme_key,
					'description' => '<img src="' . plugin_dir_url(__FILE__) . '/category-schemes/scheme' . $scheme_key . '.png">',
					'dependency' => array(
						'element' => 'layout_scheme_' . strval($scheme_col) . 'x',
						'value' => array($scheme_key)
					),
					'edit_field_class' => 'vc_column vc_col-sm-6 layout_scheme',
					'group' => __('Categories & Layout', 'thegem')
				));

			}
		}

		add_filter('vc_autocomplete_gem_product_grid_categories_content_products_cat_callback', 'TheGemProductCategoryCategoryAutocompleteSuggesterBySlug', 10, 1);
		add_filter('vc_autocomplete_gem_product_grid_categories_content_products_cat_render', 'TheGemProductCategoryCategoryRenderBySlugExact', 10, 1);

		$product_categories_source_fields = array(
			array(
				'type' => 'thegem_hidden_param',
				'param_name' => 'source',
				'std' => 'manual',
				'edit_field_class' => 'no-top-padding vc_column vc_col-sm-12',
				'group' => __('Categories & Layout', 'thegem')
			),
		);
		$product_categories_shortcode_categories = array(__( 'TheGem', 'thegem' ), esc_html__( 'WooCommerce', 'js_composer' ));
		if ( thegem_is_template_post( 'product-archive' ) ) {
			$product_categories_source_fields = array(
				array(
					'type' => 'dropdown',
					'heading' => __('Source', 'thegem'),
					'param_name' => 'source',
					'value' => array(
						__('Manual Selection', 'thegem') => 'manual',
						__('Show Subcategories', 'thegem') => 'subcategories',
					),
					'save_always' => true,
					'std' => 'manual',
					'edit_field_class' => 'vc_column vc_col-sm-12',
					'group' => __('Categories & Layout', 'thegem')
				),
			);
			$product_categories_shortcode_categories[] = __( 'Archive Product Builder', 'thegem' );
		}

		$shortcodes['product_grid_categories'] = array(
			'name' => __('Product Categories', 'thegem'),
			'base' => 'gem_product_grid_categories',
			'icon' => 'thegem-icon-wpb-ui-product-categories',
			'category' => $product_categories_shortcode_categories,
			'description' => __('Display product categories', 'thegem'),
			'params' => array_merge(
				array(
					array(
						'type' => 'textfield',
						'heading' => __('Unique ID', 'thegem'),
						'param_name' => 'portfolio_uid',
						'value' => 'grid_' . substr(md5(rand()), 0, 7),
						'description' => __('In case of adding multiple product categories grids / carousels on the same page please ensure that each product categories grid / carousel has its own unique ID to avoid conflicts.', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Categories & Layout', 'thegem'),
						'save_always' => true,
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Categories', 'thegem'),
						'param_name' => 'categories_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Source', 'thegem'),
						'param_name' => 'products_source_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Categories & Layout', 'thegem')
					),
				),
				$product_categories_source_fields,
				array(
					array(
						'type' => 'autocomplete',
						'heading' => __('Select Products Categories', 'thegem'),
						'param_name' => 'content_products_cat',
						'settings' => array(
							'multiple' => true,
							'sortable' => true,
						),
						'save_always' => true,
						'dependency' => array(
							'element' => 'source',
							'value' => array('manual')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Hide Empty', 'thegem'),
						'param_name' => 'hide_empty',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Sorting', 'thegem'),
						'param_name' => 'products_sorting_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Order By', 'thegem'),
						'param_name' => 'orderby',
						'value' => array(
							__('Title', 'thegem') => 'name',
							__('ID', 'thegem') => 'term_id',
							__('Product Count', 'thegem') => 'count',
							__('Date', 'thegem') => 'id',
							__('Menu Order', 'thegem') => 'order',
						),
						'std' => 'name',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Order', 'thegem'),
						'param_name' => 'order',
						'value' => array(
							__('ASC', 'thegem') => 'asc',
							__('DESC', 'thegem') => 'desc',
						),
						'std' => 'asc',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Layout', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Skin', 'thegem'),
						'param_name' => 'product_grid_categories_skin',
						'value' => array(
							__('Image, Light Caption', 'thegem') => 'image-light-caption',
							__('Image, Dark Caption', 'thegem') => 'image-dark-caption',
							__('Image, Transparent, Light Title', 'thegem') => 'image-transparent-light-title',
							__('Image, Transparent, Dark Title', 'thegem') => 'image-transparent-dark-title',
							__('Image, Bold Title, Light', 'thegem') => 'image-bold-title-light',
							__('Image, Bold Title, Dark', 'thegem') => 'image-bold-title-dark',
							__('Below, Default', 'thegem') => 'below-default',
							__('Below, Bordered', 'thegem') => 'below-bordered',
							__('Below, Solid', 'thegem') => 'below-solid',
						),
						'dependency' => array(
							'callback' => 'product_grid_categories_skin_callback',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Layout Type', 'thegem'),
						'param_name' => 'layout_type',
						'value' => array(
							__('Grid', 'thegem') => 'grid',
							__('Creative Grid', 'thegem') => 'creative',
							__('Carousel', 'thegem') => 'carousel',
						),
						'dependency' => array(
							'callback' => 'layout_type_callback'
						),
						'std' => 'grid',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Columns Desktop', 'thegem'),
						'param_name' => 'columns_desktop',
						'value' => array(
							__('1x columns (for mega menu, sidebar, narrow column)', 'thegem') => '1x',
							__('2x columns (for mega menu, sidebar, narrow column)', 'thegem') => '2x',
							__('3x columns', 'thegem') => '3x',
							__('4x columns', 'thegem') => '4x',
							__('5x columns', 'thegem') => '5x',
							__('6x columns', 'thegem') => '6x',
							__('100% width', 'thegem') => '100%',
						),
						'std' => '4x',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Columns Tablet', 'thegem'),
						'param_name' => 'columns_tablet',
						'value' => array(
							__('1x columns', 'thegem') => '1x',
							__('2x columns', 'thegem') => '2x',
							__('3x columns', 'thegem') => '3x',
							__('4x columns', 'thegem') => '4x',
						),
						'std' => '3x',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Columns Mobile', 'thegem'),
						'param_name' => 'columns_mobile',
						'value' => array(
							__('1x columns', 'thegem') => '1x',
							__('2x columns', 'thegem') => '2x',
						),
						'std' => '2x',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('100% Width Columns', 'thegem'),
						'param_name' => 'columns_100',
						'value' => array(
							__('4x columns', 'thegem') => '4',
							__('5x columns', 'thegem') => '5',
							__('6x columns', 'thegem') => '6',
						),
						'std' => '4',
						'dependency' => array(
							'element' => 'columns_desktop',
							'value' => array('100%')
						),
						'description' => __('Number of columns for 100% width grid for desktop resolutions starting from 1920 px and above', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Image Size', 'thegem'),
						'param_name' => 'image_size',
						'value' => array(
							__('As in Grid Layout (TheGem Thumbnails)', 'thegem') => 'default',
							__('Full Size', 'thegem') => 'full',
							__('WooCommerce Thumbnail', 'thegem') => 'woocommerce_thumbnail',
							__('WooCommerce Single', 'thegem') => 'woocommerce_single',
							__('WordPress Thumbnail', 'thegem') => 'thumbnail',
							__('WordPress Medium', 'thegem') => 'medium',
							__('WordPress Medium Large', 'thegem') => 'medium_large',
							__('WordPress Large', 'thegem') => 'large',
							__('1536x1536', 'thegem') => '1536x1536',
							__('2048x2048', 'thegem') => '2048x2048',
						),
						'std' => 'default',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Image Ratio', 'thegem'),
						'param_name' => 'image_ratio_full',
						'std' => 1,
						'dependency' => array(
							'element' => 'image_size',
							'value' => array('full')
						),
						'description' => __('Specify a decimal value, i.e. instead of 3:4, specify 0.75. Use a dot as a decimal separator. Leave blank to show the original image ratio', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Image Aspect Ratio', 'thegem'),
						'param_name' => 'image_aspect_ratio',
						'value' => array(
							__('Portrait', 'thegem') => 'portrait',
							__('Square', 'thegem') => 'square',
							__('Custom Selection', 'thegem') => 'custom',
						),
						'std' => 'portrait',
						'dependency' => array(
							'element' => 'image_size',
							'value' => array('default')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Image Ratio', 'thegem'),
						'param_name' => 'image_ratio_custom',
						'dependency' => array(
							'element' => 'image_aspect_ratio',
							'value' => array('custom')
						),
						'description' => __('Specify a decimal value, i.e. instead of 3:4, specify 0.75. Use a dot as a decimal separator. Leave blank to show the original image ratio', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Custom Images Height', 'thegem'),
						'param_name' => 'custom_images_height',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => array('grid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Images Height', 'thegem'),
						'param_name' => 'images_height',
						'dependency' => array(
							'element' => 'custom_images_height',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Creative Layouts', 'thegem'),
						'param_name' => 'creative_layouts_sub_delim_head',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => array('creative')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Categories & Layout', 'thegem')
					),
				),
				$creative_categories_schemes_values,
				array(

					array(
						'type' => 'checkbox',
						'heading' => __('Apply on mobiles', 'thegem'),
						'param_name' => 'scheme_apply_mobiles',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => array('creative')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Apply on tablets', 'thegem'),
						'param_name' => 'scheme_apply_tablets',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => array('creative')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Categories & Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Caption', 'thegem'),
						'param_name' => 'caption_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Caption Position', 'thegem'),
						'param_name' => 'caption_position',
						'value' => array(
							__('On Image', 'thegem') => 'image',
							__('Below Image', 'thegem') => 'below',
						),
						'std' => 'image',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Product Counts', 'thegem'),
						'param_name' => 'product_counts',
						'value' => array(
							__('Always Visible', 'thegem') => 'always',
							__('Visible on Hover', 'thegem') => 'hover',
							__('Hidden', 'thegem') => 'hidden',
						),
						'std' => 'hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Product" Text', 'thegem'),
						'param_name' => 'product_singular_text',
						'std' => 'product',
						'dependency' => array(
							'element' => 'product_counts',
							'value' => array('always', 'hover')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"Products" Text', 'thegem'),
						'param_name' => 'product_plural_text',
						'std' => 'products',
						'dependency' => array(
							'element' => 'product_counts',
							'value' => array('always', 'hover')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Separator', 'thegem'),
						'param_name' => 'caption_separator',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Items Count', 'thegem'),
						'param_name' => 'items_count',
						'std' => '12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_hidden_param',
						'param_name' => 'new_default_counts',
						'std' => '1',
						'save_always' => true,
						'edit_field_class' => 'no-top-padding vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Navigation', 'thegem'),
						'param_name' => 'navigation_delim_head',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => array('carousel')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Dots Navigation', 'thegem'),
						'param_name' => 'show_dots_navigation',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => array('carousel')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Arrows Navigation', 'thegem'),
						'param_name' => 'show_arrows_navigation',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => array('carousel')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Arrows Position', 'thegem'),
						'param_name' => 'arrows_navigation_position',
						'value' => array(
							__('Outside Product Items', 'thegem') => 'outside',
							__('On Product Items', 'thegem') => 'on',
						),
						'std' => 'outside',
						'dependency' => array(
							'element' => 'show_arrows_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Arrows Visibility', 'thegem'),
						'param_name' => 'arrows_navigation_visibility',
						'value' => array(
							__('Visible on Hover', 'thegem') => 'hover',
							__('Always Visible', 'thegem') => 'always',
						),
						'std' => 'hover',
						'dependency' => array(
							'element' => 'show_arrows_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Grid image style', 'thegem'),
						'param_name' => 'grid_img_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Gaps', 'thegem'),
						'param_name' => 'gaps_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'image_gaps',
						'value' => __('42', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Tablet', 'thegem'),
						'param_name' => 'image_gaps_tablet',
						'value' => __('42', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'image_gaps_mobile',
						'value' => __('42', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Categories Separator', 'thegem'),
						'param_name' => 'product_separator_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show', 'thegem'),
						'param_name' => 'product_separator',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Width', 'thegem'),
						'param_name' => 'product_separator_width',
						'dependency' => array(
							'element' => 'product_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'product_separator_color',
						'dependency' => array(
							'element' => 'product_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Image Border', 'thegem'),
						'param_name' => 'thumb_border_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Width', 'thegem'),
						'param_name' => 'image_border_width',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Radius', 'thegem'),
						'param_name' => 'image_border_radius',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal Color', 'thegem'),
						'param_name' => 'image_border_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'image_border_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Apply border on caption container', 'thegem'),
						'param_name' => 'border_caption_container',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('below')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('', 'thegem'),
						'param_name' => 'thumb_border_end_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Enable Shadow', 'thegem'),
						'param_name' => 'enable_shadow',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Shadow color', 'thegem'),
						'param_name' => 'shadow_color',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'std' => 'rgba(0, 0, 0, 0.15)',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Position', 'thegem'),
						'param_name' => 'shadow_position',
						'value' => array(
							__('Outline', 'thegem') => 'outline',
							__('Inset', 'thegem') => 'inset'
						),
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'std' => 'outline',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Horizontal', 'thegem'),
						'param_name' => 'shadow_horizontal',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '0',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Vertical', 'thegem'),
						'param_name' => 'shadow_vertical',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '5',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Blur', 'thegem'),
						'param_name' => 'shadow_blur',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '5',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Spread', 'thegem'),
						'param_name' => 'shadow_spread',
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '-5',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Apply shadow on caption container', 'thegem'),
						'param_name' => 'shadowed_container',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'enable_shadow',
							'not_empty' => true,
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('', 'thegem'),
						'param_name' => 'thumb_border_end_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Better Thumbnails Quality', 'thegem'),
						'param_name' => 'fullwidth_section_images',
						'value' => array(__('Yes', 'thegem') => '1'),
						'description' => __('Activate for better image quality in case of using in fullwidth section', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Overlay Color (Normal)', 'thegem'),
						'param_name' => 'image_overlay_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Overlay Color (Hover)', 'thegem'),
						'param_name' => 'image_overlay_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Caption style', 'thegem'),
						'param_name' => 'caption_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Preset', 'thegem'),
						'param_name' => 'caption_container_preset',
						'value' => array(
							__('Solid', 'thegem') => 'solid',
							__('Transparent', 'thegem') => 'transparent',
							__('Bold Title', 'thegem') => 'bold',
						),
						'std' => 'solid',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('image')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Color Scheme', 'thegem'),
						'param_name' => 'caption_container_preset_color',
						'value' => array(
							__('Light', 'thegem') => 'light',
							__('Dark', 'thegem') => 'dark',
						),
						'std' => 'light',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('image')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Vertical Position', 'thegem'),
						'param_name' => 'caption_container_vertical_position',
						'value' => array(
							__('Top', 'thegem') => 'top',
							__('Centered', 'thegem') => 'center',
							__('Bottom', 'thegem') => 'bottom',
						),
						'std' => 'bottom',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('image',)
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Content Alignment', 'thegem'),
						'param_name' => 'caption_container_alignment',
						'value' => array(
							__('Left', 'thegem') => 'left',
							__('Centered', 'thegem') => 'center',
							__('Right', 'thegem') => 'right',
						),
						'std' => 'center',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('image')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color (Normal)', 'thegem'),
						'param_name' => 'caption_container_background_color_normal',
						'dependency' => array(
							'element' => 'caption_container_preset',
							'value' => array('solid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color (Hover)', 'thegem'),
						'param_name' => 'caption_container_background_color_hover',
						'dependency' => array(
							'element' => 'caption_container_preset',
							'value' => array('solid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Categories Title', 'thegem'),
						'param_name' => 'categories_title_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'categories_title_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'categories_title_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Product Counts', 'thegem'),
						'param_name' => 'categories_counts_sub_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'categories_counts_color_normal',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'categories_counts_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Separator', 'thegem'),
						'param_name' => 'caption_separator_sub_delim_head',
						'dependency' => array(
							'element' => 'caption_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal Color', 'thegem'),
						'param_name' => 'caption_separator_color_normal',
						'dependency' => array(
							'element' => 'caption_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color ', 'thegem'),
						'param_name' => 'caption_separator_color_hover',
						'dependency' => array(
							'element' => 'caption_separator',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Caption Container Style', 'thegem'),
						'param_name' => 'caption_container_style_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('below')
						),
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Preset', 'thegem'),
						'param_name' => 'caption_container_preset_below',
						'value' => array(
							__('Transparent', 'thegem') => 'transparent',
							__('White', 'thegem') => 'white',
							__('Gray', 'thegem') => 'gray',
							__('Dark', 'thegem') => 'dark',
						),
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('below')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Content Alignment', 'thegem'),
						'param_name' => 'caption_container_alignment_below',
						'value' => array(
							__('Left', 'thegem') => 'left',
							__('Centered', 'thegem') => 'center',
							__('Right', 'thegem') => 'right',
						),
						'std' => 'center',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('below')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color (Normal)', 'thegem'),
						'param_name' => 'caption_container_background_below_normal',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('below')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color (Hover)', 'thegem'),
						'param_name' => 'caption_container_background_below_hover',
						'dependency' => array(
							'element' => 'caption_position',
							'value' => array('below')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),

					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Navigation Style', 'thegem'),
						'param_name' => 'navigation_style_delim_head',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => array('carousel')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Arrows', 'thegem'),
						'param_name' => 'navigation_style_arrows_sub_delim_head',
						'dependency' => array(
							'element' => 'show_arrows_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal Arrow Color', 'thegem'),
						'param_name' => 'navigation_arrows_icon_color_normal',
						'dependency' => array(
							'element' => 'show_arrows_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Arrow Color', 'thegem'),
						'param_name' => 'navigation_arrows_icon_color_hover',
						'dependency' => array(
							'element' => 'show_arrows_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'navigation_arrows_border_width',
						'dependency' => array(
							'element' => 'arrows_navigation_position',
							'value' => array('on')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Radius', 'thegem'),
						'param_name' => 'navigation_arrows_border_radius',
						'dependency' => array(
							'element' => 'arrows_navigation_position',
							'value' => array('on')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'navigation_arrows_border_color_normal',
						'dependency' => array(
							'element' => 'arrows_navigation_position',
							'value' => array('on')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color on hover', 'thegem'),
						'param_name' => 'navigation_arrows_border_color_hover',
						'dependency' => array(
							'element' => 'arrows_navigation_position',
							'value' => array('on')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background', 'thegem'),
						'param_name' => 'navigation_arrows_background_color_normal',
						'dependency' => array(
							'element' => 'arrows_navigation_position',
							'value' => array('on')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background on hover', 'thegem'),
						'param_name' => 'navigation_arrows_background_color_hover',
						'dependency' => array(
							'element' => 'arrows_navigation_position',
							'value' => array('on')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Dots Navigation', 'thegem'),
						'param_name' => 'navigation_style_dots_sub_delim_head',
						'dependency' => array(
							'element' => 'show_dots_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top Spacing', 'thegem'),
						'param_name' => 'navigation_dots_spacing',
						'dependency' => array(
							'element' => 'show_dots_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'navigation_dots_border_width',
						'dependency' => array(
							'element' => 'show_dots_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'navigation_dots_border_color_normal',
						'dependency' => array(
							'element' => 'show_dots_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color active', 'thegem'),
						'param_name' => 'navigation_dots_border_color_active',
						'dependency' => array(
							'element' => 'show_dots_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background', 'thegem'),
						'param_name' => 'navigation_dots_background_color_normal',
						'dependency' => array(
							'element' => 'show_dots_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background active', 'thegem'),
						'param_name' => 'navigation_dots_background_color_active',
						'dependency' => array(
							'element' => 'show_dots_navigation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Additional Options', 'thegem'),
						'param_name' => 'additional_options_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Sliding Animation', 'thegem'),
						'param_name' => 'sliding_animation',
						'std' => 'default',
						'value' => array(
							__('Default', 'thegem') => 'default',
							__('One-by-One', 'thegem') => 'one-by-one'
						),
						'dependency' => array(
							'element' => 'layout_type',
							'value' => array('carousel')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Slider Loop', 'thegem'),
						'param_name' => 'slider_loop',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => array('carousel')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Autoscroll', 'thegem'),
						'param_name' => 'autoscroll',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => array('carousel')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Autoplay Speed', 'thegem'),
						'param_name' => 'autoscroll_speed',
						'std' => 2000,
						'dependency' => array(
							'element' => 'autoscroll',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Init carousel on scroll', 'thegem'),
						'param_name' => 'slider_scroll_init',
						'description' => __('This option allows you to init carousel script only when visitor scroll the page to the slider. Useful for performance optimization.', 'thegem'),
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => array('carousel')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Lazy Loading Animation', 'thegem'),
						'param_name' => 'loading_animation',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Animation Effect', 'thegem'),
						'param_name' => 'animation_effect',
						'std' => 'bounce',
						'value' => array(
							__('Bounce', 'thegem') => 'bounce',
							__('Move Up', 'thegem') => 'move-up',
							__('Fade In', 'thegem') => 'fade-in',
							__('Fall Perspective', 'thegem') => 'fall-perspective',
							__('Scale', 'thegem') => 'scale',
							__('Flip', 'thegem') => 'flip'
						),
						'dependency' => array(
							'element' => 'loading_animation',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Disable Skeleton Preloader', 'thegem'),
						'param_name' => 'disable_preloader',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'layout_type',
							'value' => array('carousel')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Skeleton Preloader on grid loading', 'thegem'),
						'param_name' => 'skeleton_loader',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'dependency' => array(
							'element' => 'layout_type',
							'value' => array('grid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Additional', 'thegem')
					)
				)
			)
		);
		/******************************************/

		add_filter( 'vc_autocomplete_gem_product_slider_slider_categories_callback', 'TheGemProductCategoryCategoryAutocompleteSuggesterBySlug', 10, 1 );
		add_filter( 'vc_autocomplete_gem_product_slider_slider_categories_render', 'TheGemProductCategoryCategoryRenderBySlugExact', 10, 1 );
		$shortcodes['product_slider'] = array(
			'name' => __( 'Products Slider (Legacy)', 'thegem' ),
			'base' => 'gem_product_slider',
			'icon' => 'thegem-icon-wpb-ui-product-slider',
			'category' => array(__( 'TheGem', 'thegem' ), esc_html__( 'WooCommerce', 'js_composer' )),
			'description' => __( 'Display products slider', 'thegem' ),
			'deprecated' => 5,
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Title', 'thegem'),
					'param_name' => 'slider_title',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Layout', 'thegem'),
					'param_name' => 'slider_layout',
					'value' => array(__('2x columns', 'thegem') => '2x', __('3x columns', 'thegem') => '3x', __('100% width', 'thegem') => '100%'),
					'std' => '3x',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Columns 100% Width (1920x Screen)', 'thegem'),
					'param_name' => 'slider_fullwidth_columns',
					'value' => array(__('3 Columns', 'thegem') => '3', __('4 Columns', 'thegem') => '4', __('5 Columns', 'thegem') => '5', __('6 Columns', 'thegem') => '6'),
					'std' => '4',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'thegem'),
					'param_name' => 'slider_style',
					'value' => array(__('Justified', 'thegem') => 'justified', __('Masonry ', 'thegem') => 'masonry'),
					'std' => 'justified',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Gaps Size', 'thegem'),
					'param_name' => 'slider_gaps_size',
					'std' => 42,
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Display Titles', 'thegem'),
					'param_name' => 'slider_display_titles',
					'value' => array(__('On Page', 'thegem') => 'page', __('On Hover', 'thegem') => 'hover')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Hover Type', 'thegem'),
					'param_name' => 'slider_hover',
					'value' => array(__('Cyan Breeze', 'thegem') => 'default', __('Zooming White', 'thegem') => 'zooming-blur', __('Horizontal Sliding', 'thegem') => 'horizontal-sliding', __('Vertical Sliding', 'thegem') => 'vertical-sliding', __('Gradient', 'thegem') => 'gradient', __('Circular Overlay', 'thegem') => 'circular'),
					'dependency' => array(
						'element' => 'slider_display_titles',
						'value' => array('hover')
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Hover Type', 'thegem'),
					'param_name' => 'slider_hover_title_on_page',
					'value' => array(__('Show next product image', 'thegem') => 'default', __('Gradient', 'thegem') => 'gradient', __('Circular Overlay', 'thegem') => 'circular'),
					'dependency' => array(
						'element' => 'slider_display_titles',
						'value' => array('page')
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Background Style', 'thegem'),
					'param_name' => 'slider_background_style',
					'value' => array(__('White', 'thegem') => 'white', __('Grey', 'thegem') => 'gray', __('Dark', 'thegem') => 'dark'),
					'dependency' => array(
						'callback' => 'display_titles_hover_callback'
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Title Style', 'thegem'),
					'param_name' => 'slider_title_style',
					'value' => array(__('Light', 'thegem') => 'light', __('Dark', 'thegem') => 'dark'),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Product Separator', 'thegem'),
					'param_name' => 'slider_item_separator',
					'value' => array(__('Yes', 'thegem') => '1'),
					'dependency' => array(
						'callback' => 'item_separator_callback'
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Disable sharing buttons', 'thegem'),
					'param_name' => 'slider_disable_socials',
					'value' => array(__('Yes', 'thegem') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Lazy loading enabled', 'thegem'),
					'param_name' => 'effects_enabled',
					'value' => array(__('Yes', 'thegem') => '1')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Arrow', 'thegem'),
					'param_name' => 'slider_slider_arrow',
					'value' => array(__('Big', 'thegem') => 'portfolio_slider_arrow_big', __('Small', 'thegem') => 'portfolio_slider_arrow_small')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Animation', 'thegem'),
					'param_name' => 'slider_animation',
					'value' => array(__('Dynamic slide', 'thegem') => 'dynamic', __('One-by-one', 'thegem') => 'one')
				),
				array(
					'type' => 'textfield',
					'heading' => __('Autoscroll', 'thegem'),
					'description' => __('Speed in Milliseconds, example - 5000', 'thegem'),
					'param_name' => 'slider_autoscroll',
				),
				array(
					'type' => 'autocomplete',
					'heading' => __( 'Product categories', 'thegem' ),
					'param_name' => 'slider_categories',
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
					),
					'save_always' => true,
					'description' => __( 'List of product categories', 'thegem' ),
					'group' =>__('Select Product Categories', 'thegem'),
				),
			),
		);
	}
	uasort($shortcodes, 'thegem_sort_shortcodes_array');
	return $shortcodes;
}
add_action('thegem_shortcodes_array', 'thegem_add_woocommerce_shortcodes', 11);

function thegem_product_grid_shortcode($atts) {

	if (!thegem_is_plugin_active('woocommerce/woocommerce.php')) {
		return '';
	}

	extract(shortcode_atts(array(
		'grid_categories' => '',
		'grid_layout' => '2x',
		'grid_style' => 'justified',
		'grid_layout_version' => 'fullwidth',
		'grid_caption_position' => 'right',
		'grid_gaps_size' => 42,
		'grid_display_titles' => 'page',
		'grid_background_style' => 'white',
		'grid_title_style' => 'light',
		'grid_hover' => 'default',
		'grid_hover_title_on_page' => 'default',
		'grid_pagination' => 'normal',
		'loading_animation' => 'move-up',
		'grid_items_per_page' => 8,
		'grid_with_filter' => '',
		'gem_product_grid_featured_products' => '',
		'gem_product_grid_onsale_products' => '',
		'grid_title' => '',
		'grid_item_separator' => '',
		'grid_ignore_highlights' => '',
		'grid_disable_socials' => '',
		'grid_fullwidth_columns' => '4',
		'grid_sorting' => false,
		'metro_max_row_height' => 380
	), $atts, 'gem_product_grid'));

	if($gem_product_grid_featured_products != ''){
		(in_array('yes', explode(',', $gem_product_grid_featured_products))) ? $gem_product_grid_featured_products_active = 1 : $gem_product_grid_featured_products_active = 0;
		(in_array('hide', explode(',', $gem_product_grid_featured_products))) ? $gem_product_grid_featured_products_hide_label = 1 : $gem_product_grid_featured_products_hide_label = 0;
	} else {
		$gem_product_grid_featured_products_active = 0;
		$gem_product_grid_featured_products_hide_label = 0;
	}

	if($gem_product_grid_onsale_products != ''){
		(in_array('yes', explode(',', $gem_product_grid_onsale_products))) ? $gem_product_grid_onsale_products_active = 1 : $gem_product_grid_onsale_products_active = 0;
		(in_array('hide', explode(',', $gem_product_grid_onsale_products))) ? $gem_product_grid_onsale_products_hide_label = 1 : $gem_product_grid_onsale_products_hide_label = 0;
	} else {
		$gem_product_grid_onsale_products_active = 0;
		$gem_product_grid_onsale_products_hide_label = 0;
	}

	if(thegem_is_plugin_active('js_composer/js_composer.php')) {
		global $vc_manager;
		if($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode() == 'page_editable') {
			return '<div class="portfolio-shortcode-dummy"></div>';
		}
	}
	$button_params = array();
	foreach($atts as $key => $value) {
		if(substr($key, 0, 7) == 'button_') {
			$button_params[substr($key, 7)] = $value;
		}
	}
	ob_start();
	thegem_products_grid(array(
		'categories' => $grid_categories,
		'title' => $grid_title,
		'layout' => $grid_layout,
		'layout_version' => $grid_layout_version,
		'caption_position' => $grid_caption_position,
		'style' => $grid_style,
		'gaps_size' => $grid_gaps_size,
		'display_titles' => $grid_display_titles,
		'background_style' => $grid_background_style,
		'title_style' => $grid_title_style,
		'hover' => $grid_display_titles == 'page' ? $grid_hover_title_on_page :$grid_hover,
		'pagination' => $grid_pagination,
		'loading_animation' => $loading_animation,
		'items_per_page' => $grid_items_per_page,
		'with_filter' => $grid_with_filter,
		'gem_product_grid_featured_products' => $gem_product_grid_featured_products_active,
		'gem_product_grid_featured_products_hide_label' => $gem_product_grid_featured_products_hide_label,
		'gem_product_grid_onsale_products' => $gem_product_grid_onsale_products_active,
		'gem_product_grid_onsale_products_hide_label' => $gem_product_grid_onsale_products_hide_label,
		'item_separator' => $grid_item_separator,
		'ignore_highlights' => $grid_ignore_highlights,
		'disable_socials' => $grid_disable_socials,
		'fullwidth_columns' => $grid_fullwidth_columns,
		'sorting' => $grid_sorting,
		'button' => $button_params,
		'metro_max_row_height' => $metro_max_row_height
	));
	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	return $return_html;
}

function product_grid_load_more_callback() {
	$response = array();
	$data = isset($_POST['data']) ? $_POST['data'] : array();
	$data['is_ajax'] = true;
	$response = array('status' => 'success');
	ob_start();
	thegem_products_grid($data);
	$response['html'] = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	$response = json_encode($response);
	header( "Content-Type: application/json" );
	echo $response;
	exit;
}
add_action('wp_ajax_product_grid_load_more', 'product_grid_load_more_callback');
add_action('wp_ajax_nopriv_product_grid_load_more', 'product_grid_load_more_callback');

function thegem_product_grid_extended_shortcode($atts) {

	if (thegem_is_plugin_active('js_composer/js_composer.php')) {
		global $vc_manager;
		if ($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode() == 'page_editable') {
			return '<div class="portfolio-shortcode-dummy"></div>';
		}
	}

	if (!thegem_is_plugin_active('woocommerce/woocommerce.php') || !function_exists('get_thegem_portfolio_render_item_image_sizes')) {
		return '';
	}

	ob_start();

	if (!isset($atts['new_default_sorting'])) {
		if (!isset($atts['orderby'])) {
			$atts['orderby'] = 'date';
		}
		if (!isset($atts['order'])) {
			$atts['order'] = 'asc';
		}
		if (!isset($atts['ignore_highlights'])) {
			$atts['ignore_highlights'] = '';
		}
	}

	$source_type = 'custom';
	if (!empty($atts['source_type'])) {
		$source_type = $atts['source_type'];
	}
	$related_upsell_source = '';
	if (!empty($atts['related_upsell_source'])) {
		$related_upsell_source = $atts['related_upsell_source'];
	}
	$woo_filters_style = 'sidebar';
	if (!empty($atts['woo_filters_style'])) {
		$woo_filters_style = $atts['woo_filters_style'];
	}
	$woo_ajax_filtering = '';
	if (!empty($atts['woo_ajax_filtering'])) {
		$woo_ajax_filtering = $atts['woo_ajax_filtering'];
	}
	$woo_remove_counts = '';
	if (!empty($atts['woo_remove_counts'])) {
		$woo_remove_counts = $atts['woo_remove_counts'];
	}

	$atts = vc_map_get_attributes('gem_product_grid_extended', $atts);

	$atts['source_type'] = $source_type;
	$atts['related_upsell_source'] = $related_upsell_source;
	$atts['woo_filters_style'] = $woo_filters_style;
	$atts['woo_ajax_filtering'] = $woo_ajax_filtering;
	$atts['woo_remove_counts'] = $woo_remove_counts;

	if ($atts['add_to_cart_type'] == 'buttons') {
		$atts['add_to_cart_type'] = 'button';
	}

	thegem_products_grid_extended($atts);
	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	return $return_html;
}

function thegem_product_compact_grid_shortcode($atts) {

	if (!thegem_is_plugin_active('woocommerce/woocommerce.php') || !function_exists('get_thegem_portfolio_render_item_image_sizes')) {
		return '';
	}

	ob_start();

	$atts = vc_map_get_attributes('gem_product_compact_grid', $atts);

	thegem_products_compact_grid($atts);
	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	return $return_html;
}

function thegem_product_grid_carousel_shortcode($atts) {

	if (!thegem_is_plugin_active('woocommerce/woocommerce.php') || !function_exists('get_thegem_portfolio_render_item_image_sizes')) {
		return '';
	}

	ob_start();

	$source_type = 'custom';
	if (!empty($atts['source_type'])) {
		$source_type = $atts['source_type'];
	}
	$related_upsell_source = '';
	if (!empty($atts['related_upsell_source'])) {
		$related_upsell_source = $atts['related_upsell_source'];
	}

	$atts = vc_map_get_attributes('gem_product_grid_carousel', $atts);

	$atts['source_type'] = $source_type;
	$atts['related_upsell_source'] = $related_upsell_source;

	if ($atts['add_to_cart_type'] == 'buttons') {
		$atts['add_to_cart_type'] = 'button';
	}

	thegem_products_grid_carousel_extended($atts);
	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	return $return_html;
}

function thegem_product_grid_categories_shortcode($atts) {

	if (!thegem_is_plugin_active('woocommerce/woocommerce.php') || !function_exists('get_thegem_portfolio_render_item_image_sizes')) {
		return '';
	}

	ob_start();

	$source = 'manual';
	if (!empty($atts['source'])) {
		$source = $atts['source'];
	}

	if (!isset($atts['new_default_counts'])) {
		if (!isset($atts['items_count'])) {
			$atts['items_count'] = '';
		}
	}

	$atts = vc_map_get_attributes('gem_product_grid_categories', $atts);

	$atts['source'] = $source;

	thegem_products_grid_categories($atts);
	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	return $return_html;
}

function thegem_product_slider_shortcode($atts) {

	if (!thegem_is_plugin_active('woocommerce/woocommerce.php')) {
		return '';
	}

	extract(shortcode_atts(array(
		'slider_categories' => '',
		'slider_title' => '',
		'slider_layout' => '3x',
		'slider_no_gaps' => '',
		'slider_display_titles' => 'page',
		'slider_hover' => 'default',
		'slider_hover_title_on_page' => 'default',
		'slider_background_style' => 'white',
		'slider_title_style' => 'light',
		'slider_item_separator' => '',
		'slider_disable_socials' => '',
		'slider_fullwidth_columns' => '4',
		'effects_enabled' => false,
		'slider_gaps_size' => 42,
		'slider_slider_arrow' => 'portfolio_slider_arrow_big',
		'slider_animation' => 'dynamic',
		'slider_autoscroll' => false,
		'slider_style' => 'justified',
	), $atts, 'gem_product_slider'));
	if(thegem_is_plugin_active('js_composer/js_composer.php')) {
		global $vc_manager;
		if($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode() == 'page_editable') {
			return '<div class="portfolio-slider-shortcode-dummy"></div>';
		}
	}
	ob_start();
	thegem_product_slider(array(
		'categories' => $slider_categories,
		'title' => $slider_title,
		'layout' => $slider_layout,
		'no_gaps' => $slider_no_gaps,
		'display_titles' => $slider_display_titles,
		'hover' => $slider_display_titles == 'page' ? $slider_hover_title_on_page :$slider_hover,
		'background_style' => $slider_background_style,
		'title_style' => $slider_title_style,
		'item_separator' => $slider_item_separator,
		'disable_socials' => $slider_disable_socials,
		'fullwidth_columns' => $slider_fullwidth_columns,
		'effects_enabled' => $effects_enabled,
		'gaps_size' => $slider_gaps_size,
		'slider_arrow' => $slider_slider_arrow,
		'animation' => $slider_animation,
		'autoscroll' => $slider_autoscroll,
		'style' => $slider_style
	));
	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	return $return_html;
}

function thegem_product_categories($atts) {
	if(!thegem_is_plugin_active('woocommerce/woocommerce.php')) return ;
	global $thegem_product_categories_images;
	$thegem_product_categories_images = true;
	$output = WC_Shortcodes::product_categories($atts);
	$thegem_product_categories_images = false;
	return $output;
}
add_shortcode('gem_product_categories', 'thegem_product_categories');

add_shortcode('product_category_gem', 'thegem_product_category_gem' );
function thegem_product_category_gem( $atts ) {
	if ( empty( $atts['category'] ) ) {
		return '';
	}

	$atts = array_merge( array(
		'limit'		=> '12',
		'columns'	  => '4',
		'orderby'	  => 'menu_order title',
		'order'		=> 'ASC',
		'category'	 => '',
		'cat_operator' => 'IN',
		'product_grid_featured_products' => false,
		'product_grid_featured_products_hide_label' => false,
		'product_grid_onsale_products' => false,
		'product_grid_onsale_products_hide_label' => false,
	), (array) $atts );

	if(!class_exists('WC_Shortcode_Products')) {
		return '';
	}

	if(!class_exists('TheGem_WC_Shortcode_Products')) {
		thegem_shortcode_product_class_init();
	}
	$shortcode = new TheGem_WC_Shortcode_Products( $atts, 'product_category' );

	return $shortcode->get_content();
}

function thegem_wc_hook_shortcode($atts) {
	$default_settings = array(
		'hook_type' => 'manual',
		'manual_hook' => '',
		'wc_hook_type' => 'product',
		'product_hook' => 'default',
		'cart_hook' => '0',
		'checkout_hook' => '0',
		'clean_actions' => '1',
		'alignment' => 'left',
		'css' => '',
	);

	$settings = wp_parse_args( $atts, $default_settings );

	$wrapper_classes = apply_filters( 'vc_shortcodes_css_class', '', '', $settings );

	if ( $settings['css'] ) {
		$wrapper_classes .= ' ' . vc_shortcode_custom_css_class( $settings['css'] );
	}

	ob_start();

	if($settings['hook_type'] === 'manual' && !empty(trim($settings['manual_hook']))) {
		echo '<div class="thegem-wp-hook thegem-wp-hook-'.esc_attr(trim($settings['manual_hook'])).' thegem-wp-hook-alignment-'.esc_attr($settings['alignment']).' '.$wrapper_classes.'">';
		do_action(trim($settings['manual_hook']));
		echo '</div>';
	}

	if($settings['hook_type'] === 'woocommerce') {
		$hook = '';

		if($settings['wc_hook_type'] === 'product') {
			$hook = $settings['product_hook'] === 'default' ? 'woocommerce_single_product_summary' : $settings['product_hook'];
			$product = thegem_templates_init_product();
		} elseif($settings['wc_hook_type'] === 'cart') {
			$hook = $settings['cart_hook'];
		}elseif($settings['wc_hook_type'] === 'checkout') {
			$hook = $settings['checkout_hook'];
		}

		if ( '1' === $settings['clean_actions'] ) {
			if ( 'woocommerce_checkout_billing' === $hook ) {
				remove_action( 'woocommerce_checkout_billing', array( WC()->checkout(), 'checkout_form_billing' ) );
			} elseif ( 'woocommerce_checkout_shipping' === $hook ) {
				remove_action( 'woocommerce_checkout_shipping', array( WC()->checkout(), 'checkout_form_shipping' ) );
			} elseif ( 'woocommerce_checkout_before_customer_details' === $hook ) {
				remove_action( 'woocommerce_checkout_before_customer_details', 'wc_get_pay_buttons', 30 );
			} elseif ( 'woocommerce_before_checkout_form' === $hook ) {
				remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
				remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
				remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10 );
			} elseif ( 'woocommerce_cart_collaterals' === $hook ) {
				remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
				remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
			} elseif ( 'woocommerce_before_cart' === $hook ) {
				remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );
			} elseif ( 'woocommerce_before_single_product' === $hook ) {
				remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices' );
				remove_action( 'woocommerce_before_single_product', 'wc_print_notices' );
			} elseif ( 'woocommerce_before_single_product_summary' === $hook ) {
				remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash' );
				remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
				remove_action('woocommerce_before_single_product_summary', 'woocommerce_template_single_meta', 35);
			} elseif ( 'woocommerce_product_thumbnails' === $hook ) {
				remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
			} elseif ( 'woocommerce_single_product_summary' === $hook ) {
				remove_action( 'woocommerce_single_product_summary', 'thegem_woocommerce_back_to_shop_button', 4 );
				remove_action( 'woocommerce_single_product_summary', 'thegem_woocommerce_product_page_navigation', 5 );
				remove_action( 'woocommerce_single_product_summary', 'thegem_woocommerce_product_page_attribute', 6 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 60 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating' );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price' );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
				remove_action('woocommerce_single_product_summary', 'thegem_woocommerce_size_guide', 35);
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_loop_add_to_cart', 30 );
			} elseif ( 'woocommerce_before_variations_form' === $hook ) {
				remove_action( 'woocommerce_before_variations_form', 'woocommerce_single_variation' );
			} elseif ( 'woocommerce_single_variation' === $hook ) {
				remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation' );
				remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
				remove_action( 'woocommerce_before_variations_form', 'woocommerce_single_variation' );
			} elseif ( 'woocommerce_after_single_product_summary' === $hook ) {
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs' );
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			} elseif ( 'woocommerce_checkout_order_review' === $hook ) {
				remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
				remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 20 );
				remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
				remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 10 );
			}
		}

		echo '<div class="thegem-wp-hook thegem-wp-hook-'.esc_attr($hook).' thegem-wc-hook-'.esc_attr($settings['wc_hook_type']).' thegem-wp-hook-alignment-'.esc_attr($settings['alignment']).' '.$wrapper_classes.'">';
		if ( 'woocommerce_before_checkout_form' === $hook || 'woocommerce_after_checkout_form' === $hook ) {
			do_action($hook, WC()->checkout());
		} else {
			do_action($hook);
		}
			echo '</div>';


		if($settings['wc_hook_type'] === 'product') {
			thegem_templates_close_product('', array('name' => ''));
		}

	}

	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

	return $return_html;
}