<?php
// Print Products Grid Extended
function thegem_products_grid_extended($params) {
	global $post;
	$portfolio_posttemp = $post;

	wp_enqueue_script('thegem-woocommerce');
	wp_enqueue_style('thegem-portfolio-products-extended');
	if (!wp_script_is('thegem-portfolio-grid-extended')) {
		wp_enqueue_script('thegem-portfolio-grid-extended');
		wp_add_inline_script( 'thegem-portfolio-grid-extended', "jQuery('.extended-products-grid .yith-icon').each(function () {
					var addIcon = jQuery(this).children('.add-wishlist-icon').clone();
					var addedIcon = jQuery(this).children('.added-wishlist-icon').clone();
					jQuery(this).find('a i').remove();
					jQuery(this).find('a svg').remove();
					jQuery(this).find('.yith-wcwl-add-button a:not(.delete_item)').prepend(addIcon);
					jQuery(this).find('.yith-wcwl-add-button a.delete_item').prepend(addedIcon);
					jQuery(this).find('.yith-wcwl-wishlistexistsbrowse a').prepend(addedIcon);
					jQuery(this).find('a').addClass('icon');
					jQuery(this).find('a.gem-button').removeAttr('class').removeAttr('style').removeAttr('onmouseleave').removeAttr('onmouseenter').addClass('icon');
					jQuery(this).find('.yith-wcwl-wishlistaddedbrowse a').prepend(addedIcon);
				});" );
	}

	$widget_uid = $params['portfolio_uid'];
	$style_uid = !empty($_GET['style_uid']) ? $_GET['style_uid'] : substr(md5(rand()), 0, 7);
	$params['style_uid'] = $style_uid;

	if ($params['layout_type'] == 'list') {
		$params['extended_grid_skin'] = 'below-default-cart-button';
		$params['layout'] = 'justified';
		$params['columns_desktop'] = $params['columns_desktop_list'];
		$params['columns_tablet'] = $params['columns_tablet_list'];
		$params['columns_mobile'] = '1x';
		$params['caption_position'] = 'page';
		$params['ignore_highlights'] = '1';
		$params['add_to_cart_type'] = 'button';
		$params['caption_container_alignment'] = !empty($params['caption_container_alignment']) ? $params['caption_container_alignment'] : 'default';
	}

	$is_archive_template = false;
	if ( $params['source_type'] == 'archive' && (is_tax( 'product_cat' ) || is_tax( 'product_tag' ) || is_post_type_archive( 'product' )) ) {
		$is_archive_template = true;
	}

	if ($params['filters_type'] == 'filter_woo') {
		$params['filters_scroll_top'] = $params['woo_filters_scroll_top'];
		$params['sidebar_position'] = $params['woo_sidebar_position'];
	}

	$queried = get_queried_object();

	if ( is_tax( 'product_cat' ) && $params['source_type'] == 'archive' ) {
		$params['select_products_categories'] = '1';
		$params['content_products_cat'] = $queried->slug;
	}

	$grid_uid = $is_archive_template ? '' : $widget_uid;
	$grid_uid_url = $is_archive_template ? '' : $widget_uid.'-';

	$params['portfolio_uid'] = $widget_uid;

	if ($params['source_type'] == 'archive') {
		if (isset($queried->taxonomy) && !isset($_GET[$queried->taxonomy])) {
			if ($queried->taxonomy == 'product_tag') {
				$params['select_products_tags'] = '1';
				$params['content_products_tags'] = $queried->slug;
			} else {
				$params['select_products_tax'] = $queried->taxonomy;
				$params['content_products_tax'] = array($queried->slug);
			}
		}
	}

	$post__in = null;
	$is_related_upsell = false;
	if ($params['source_type'] == 'related_upsell') {
		$product = thegem_templates_init_product();
		if (empty($product)) {
			$post = $portfolio_posttemp;
			return;
		}
		$is_related_upsell = true;
		if ($params['related_upsell_source'] == 'related') {
			$related_products = wc_get_related_products($product->get_id(), -1);
			$post__in = $related_products;
		} else {
			global $thegem_product_data;
			$upsells = $product->get_upsell_ids();
			if(intval($thegem_product_data['product_page_elements_upsell_items']) > -1) {
				$upsells = array_slice($upsells, 0, intval($thegem_product_data['product_page_elements_upsell_items']));
			}
			$post__in = $upsells;
		}

		$params['select_products'] = '1';
		$params['content_products'] = implode(",", $post__in);

		if (empty($post__in) && !is_admin()) {
			$post = $portfolio_posttemp;
			return;
		}
	} else if ($params['source_type'] == 'cross_sell') {
		$cross_sells_ids_in_cart = array();

		if (WC()->cart) {
			foreach ( WC()->cart->get_cart() as $cart_item ) {
				if ( $cart_item['quantity'] > 0 ) {
					$cross_sells_ids_in_cart = array_merge( $cart_item['data']->get_cross_sell_ids(), $cross_sells_ids_in_cart );
				}
			}
		}

		$cross_sells = wp_parse_id_list( $cross_sells_ids_in_cart );
		$post__in = $cross_sells;

		$params['select_products'] = '1';
		$params['content_products'] = implode(",", $post__in);

		if (empty($post__in) && !is_admin()) {
			$post = $portfolio_posttemp;
			return;
		}
	}

	if ($params['exclude_type'] == 'current') {
		$params['exclude_products'] = [get_the_ID()];
	} else if ($params['exclude_type'] == 'term') {
		$params['exclude_products'] = thegem_get_posts_query_section_exclude_ids($params['exclude_product_terms'], 'product');
	} else if (!empty($params['exclude_products'])) {
		$params['exclude_products'] = explode(',', $params['exclude_products']);
	}

	if (isset($params['attribute_swatches']) && $params['attribute_swatches'] == '1' && ($params['attribute_swatches_desktop'] == '1' || $params['attribute_swatches_tablet'] == '1' || $params['attribute_swatches_mobile'] == '1')) {
		$params['attribute_swatches'] = $params['attribute_swatches_desktop'];
		$params['repeater_swatches'] = vc_param_group_parse_atts($params['repeater_swatches']);
		wp_enqueue_script('wc-add-to-cart-variation');
	} else {
		$params['attribute_swatches'] = '';
		$params['attribute_swatches_tablet'] = '';
		$params['attribute_swatches_mobile'] = '';
	}

	if(!empty($params['grid_ajax_load'])) {
		$params['reduce_html_size'] = 1;
		$params['items_on_load'] = 0;
	}

	$localize = array(
		'data' => $params,
		'action' => 'extended_products_grid_load_more',
		'url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('extended_products_grid_ajax-nonce')
	);
	wp_localize_script('thegem-portfolio-grid-extended', 'thegem_portfolio_ajax_' . $style_uid, $localize);

	$normal_filter = true;
	$native_ajax = false;
	if ($params['filters_type'] == 'filter_woo') {
		$normal_filter = false;
		$params['filters_style'] = $params['woo_filters_style'];

		if ($params['woo_ajax_filtering']) {
			$native_ajax = true;
		}
	}

	if ($params['select_products_categories'] == '1') {
		$categories = explode(",", $params['content_products_cat']);
	} else {
		$categories = ['0'];
	}

	$cat_args = array(
		'hide_empty' => true,
		'orderby' => $params['filter_by_categories_order_by']
	);
	if ($params['filter_by_categories_order_by'] == 'term_order') {
		$cat_args['orderby'] = 'meta_value_num';
		$cat_args['meta_key'] = 'order';
	}

	if (in_array('0', $categories)) {
		if ($params['filter_by_categories_hierarchy'] == '1') {
			$cat_args['parent'] = 0;
		}
	} else {
		$cat_args['slug'] = $categories;
	}
	$terms = get_terms('product_cat', $cat_args);

	$filters_tax_url = $taxonomy_filter_current = $filters_meta_url = $meta_filter_current = $filters_attributes_url = [];

	$categories_filter = null;
	if (!empty($_GET[$grid_uid_url . 'category'])) {
		$active_cat = $_GET[$grid_uid_url . 'category'];
		$taxonomy_filter_current['product_cat'] = [$active_cat];
		$categories_filter = $active_cat;
	} else if (is_tax('product_cat') && $params['source_type'] == 'archive') {
		$active_cat = $queried->slug;
		$taxonomy_filter_current['product_cat'] = [$active_cat];
		$cat_args['slug'] = [];
		$cat_args['parent'] = $queried->term_id;
		$terms = get_terms('product_cat', $cat_args);
	} else {
		$active_cat = 'all';
		$taxonomy_filter_current['product_cat'] = $categories;
	}

	$attributes = [];
	if ($params['select_products_attributes'] == '1') {
		if (!empty($params['content_products_attr'])) {
			$attrs = explode(",", $params['content_products_attr']);

			if ($attrs) {
				foreach ($attrs as $attr) {
					$values = explode(",", $params['content_products_attr_val_' . $attr]);
					if (in_array('0', $values) || empty($values)) {
						$values = get_terms('pa_' . $attr, array('fields' => 'slugs'));
					}
					$attributes[$attr] = $values;
				}
			}
		} else if (!empty($params['content_products_attr_val'])) {
			$attrs = explode(", ", $params['content_products_attr_val']);

			if ($attrs) {
				foreach ($attrs as $attr) {
					$attr_arr = explode("|", $attr);
					if ($attr_arr[1] == 'all') {
						$values = get_terms('pa_' . $attr_arr[0], array('fields' => 'slugs'));
					} else {
						if (isset($attributes[$attr_arr[0]])) {
							if (!in_array($attr_arr[1], $attributes[$attr_arr[0]])) {
								$values = array_push($attributes[$attr_arr[0]], $attr_arr[1]);
							} else {
								$values = false;
							}
						} else {
							$values = [$attr_arr[1]];
						}
					}
					if ($values) {
						$attributes[$attr_arr[0]] = $values;
					}
				}
			}
		}
	}

	if ($params['select_products_tags'] == '1') {
		$taxonomy_filter_current['product_tag'] = explode(",", $params['content_products_tags']);
	}
	if (!empty($params['select_products_tax'])) {
		$taxonomy_filter_current[$params['select_products_tax']] = $params['content_products_tax'];
	}

	$taxonomies_list = get_object_taxonomies('product');
	foreach($_GET as $key => $value) {
		if ($grid_uid_url !== '' && strpos($key, $grid_uid_url) !== 0) {
			continue;
		}
		$key = str_replace($grid_uid_url, '', $key);
		if (in_array($key, $taxonomies_list) || strpos($key, 'filter_tax_') === 0) {
			$attr = str_replace('filter_tax_', '', $key);
			if (in_array($attr, $taxonomies_list)) {
				$filters_tax_url['tax_' . $attr] = $taxonomy_filter_current[$attr] = explode(",", $value);
			}
		} else if (strpos($key, 'filter_meta_') === 0) {
			$attr = str_replace('filter_meta_', '', $key);
			$filters_meta_url['meta_' . $attr] = $meta_filter_current[$attr] = explode(",", $value);
		} else if (strpos($key, 'filter_') === 0) {
			$attr = str_replace('filter_', '', $key);
			if (in_array('pa_' . $attr, $taxonomies_list)) {
				$filters_attributes_url[$attr] = explode(",", $value);
			}
		}
	}
	$attributes_filter = array_merge($filters_tax_url, $filters_meta_url, $filters_attributes_url);
	if (empty($attributes_filter)) { $attributes_filter = null; }
	$attributes_current = empty($filters_attributes_url) ? $attributes : $filters_attributes_url;

	if ($params['select_products'] == '1') {
		$post__in = explode(",", $params['content_products']);
	}

	if ($params['caption_position'] == 'image') {
		$hover_effect = $params['image_hover_effect_image'];
	} else if ($params['caption_position'] == 'page') {
		$hover_effect = $params['image_hover_effect_page'];
	} else {
		$hover_effect = $params['image_hover_effect_hover'];
	}

	if ( $params['layout_type'] == 'list') {
		$hover_effect = 'list-' . $hover_effect;
	}

	wp_enqueue_style('thegem-hovers-' . $hover_effect);

	if ($params['pagination_type'] == 'more') {
		wp_enqueue_style('thegem-button');
	} else if ($params['pagination_type'] == 'scroll') {
		wp_enqueue_script('thegem-scroll-monitor');
	}

	if ($params['quick_view'] == '1') {
		wp_enqueue_script('wc-single-product');
		wp_enqueue_script('wc-add-to-cart-variation');
		wp_enqueue_script('thegem-product-quick-view');
		wp_enqueue_script('thegem-quick-view');
		wp_enqueue_style('thegem-quick-view');

		if(thegem_get_option('product_page_layout') == 'default') {
			if(thegem_get_option('product_page_button_add_to_cart_icon') && thegem_get_option('product_page_button_add_to_cart_icon_pack')) {
				wp_enqueue_style('icons-'.thegem_get_option('product_page_button_add_to_cart_icon_pack'));
			}
			if(thegem_get_option('product_page_button_add_to_wishlist_icon') && thegem_get_option('product_page_button_add_to_wishlist_icon_pack')) {
				wp_enqueue_style('icons-'.thegem_get_option('product_page_button_add_to_wishlist_icon_pack'));
			}
			if(thegem_get_option('product_page_button_added_to_wishlist_icon') && thegem_get_option('product_page_button_added_to_wishlist_icon_pack')) {
				wp_enqueue_style('icons-'.thegem_get_option('product_page_button_added_to_wishlist_icon_pack'));
			}
		}
		if (thegem_get_option('product_gallery') != 'legacy') {
			wp_enqueue_style('thegem-product-gallery');
		} else {
			wp_enqueue_style('thegem-hovers');
		}
	}

	if ($params['product_show_filter'] === '1') {
		wp_enqueue_script('jquery-dlmenu');

		if ($params['filter_by_price'] === '1') {
			wp_enqueue_script('wc-jquery-ui-touchpunch');
		}
	}

	if ($params['loading_animation'] === '1') {
		wp_enqueue_style('thegem-animations');
		wp_enqueue_script('thegem-items-animations');
		wp_enqueue_script('thegem-scroll-monitor');
	}

	if ($params['layout'] !== 'justified' || $params['ignore_highlights'] !== '1') {

		if ($params['layout'] == 'metro') {
			wp_enqueue_script('thegem-isotope-metro');
		} else {
			wp_enqueue_script('thegem-isotope-masonry-custom');
		}
	}

	if ( $params['sidebar_sticky'] || $params['woo_sidebar_sticky'] ) {
		wp_enqueue_script( 'thegem-sticky' );
	}

	$page = 1;
	$next_page = 0;
	if (!empty($_GET[$grid_uid_url . 'page'])) {
		$page = $_GET[$grid_uid_url . 'page'];
	}

	if ($page !== 1) {
		$params['reduce_html_size'] = 0;
	}
	$items_per_page = $params['items_per_page'] ? intval($params['items_per_page']) : 8;
	if ($params['reduce_html_size']) {
		$items_on_load = $params['items_on_load'] ? intval($params['items_on_load']) : 8;
		if ($items_on_load >= $items_per_page) {
			$params['reduce_html_size'] = 0;
			$items_on_load = $items_per_page;
		}
	} else {
		$items_on_load = $items_per_page;
	}
	if(!empty($params['grid_ajax_load'])) {
		$items_on_load = 0;
	}

	$selected_orderby = $selected_order = 'default';

	if (!empty($_GET[$grid_uid_url . 'orderby'])) {
		$orderby = $_GET[$grid_uid_url . 'orderby'];
		$order = 'desc';
		$selected_orderby = $orderby;
	} else {
		$orderby = $params['orderby'];
		$order = $params['order'];
	}

	if (!empty($_GET[$grid_uid_url . 'order'])) {
		$order = $_GET[$grid_uid_url . 'order'];
		if ($params['sorting_type'] == 'extended') {
			$selected_order = $order;
		} else {
			if ($selected_orderby == 'price') {
				$selected_orderby .= '-' . $order;
			}
		}
	}

	$featured_only = $params['featured_only'] == '1';
	$sale_only = $params['sale_only'] == '1';
	$stock_only = $params['stock_only'] == '1';
	$recently_viewed_only = $params['recently_viewed_only'] == '1';
	$new_only = $params['new_only'] == '1';

	$status_current = null;
	if (!empty($_GET[$grid_uid_url . 'status'])) {
		$status_current = explode(",", $_GET[$grid_uid_url . 'status']);
		if (in_array('sale', $status_current)) {
			$sale_only = true;
		}
		if (in_array('stock', $status_current)) {
			$stock_only = true;
		}
	}

	$price_current = null;
	if (!empty($_GET[$grid_uid_url . 'min_price']) || !empty($_GET[$grid_uid_url . 'max_price'])) {
		$current_min_price = isset($_GET[$grid_uid_url . 'min_price']) ? floatval($_GET[$grid_uid_url . 'min_price']) : 0;
		$current_max_price = isset($_GET[$grid_uid_url . 'max_price']) ? floatval($_GET[$grid_uid_url . 'max_price']) : PHP_INT_MAX;
		$price_current = [$current_min_price, $current_max_price];
	}

	$search_current = null;
	if (!empty($_GET[$grid_uid_url . 's'])) {
		$search_current = $_GET[$grid_uid_url . 's'];
	}

	$active_tab = 0;
	if ($params['product_show_filter'] == '1' && $params['filters_style'] == 'tabs') {
		if (!empty($_GET[$grid_uid_url . 'tab'])) {
			$active_tab = intval($_GET[$grid_uid_url . 'tab']);
		} else {
			$active_tab = 1;
		}

		$filter_tabs = vc_param_group_parse_atts($params['filters_tabs_tabs']);
		$filter_tabs_current = $filter_tabs[$active_tab - 1];
		if ($filter_tabs_current['filters_tabs_tab_filter_by'] == 'featured') {
			$featured_only = true;
		} else if ($filter_tabs_current['filters_tabs_tab_filter_by'] == 'sale') {
			$sale_only = true;
		} else if ($filter_tabs_current['filters_tabs_tab_filter_by'] == 'recent') {
			$orderby = 'date';
			$order = 'desc';
		} else if ($filter_tabs_current['filters_tabs_tab_filter_by'] == 'categories') {
			$taxonomy_filter_current['product_cat'] = [$filter_tabs_current['filters_tabs_tab_products_cat']];
		}
	}

	$product_loop = thegem_extended_products_get_posts($page, $items_on_load, $orderby, $order, $featured_only, $sale_only, $stock_only, $recently_viewed_only, $new_only, $taxonomy_filter_current, $meta_filter_current, $attributes_current, $price_current, $search_current, null, $post__in, $params['offset'], $params['exclude_products']);

	if ($product_loop && $product_loop->have_posts() || $search_current != null || $price_current != null) :

		echo thegem_extended_products_render_styles($params);

		$max_page = ceil(($product_loop->found_posts - intval($params['offset'])) / $items_per_page);

		if ($params['reduce_html_size']) {
			$next_page = $product_loop->found_posts > $items_on_load ? 2 : 0;
			$next_page_pagination = $max_page > $page ? $page + 1 : 0;
		} else {
			$next_page = $next_page_pagination = $max_page > $page ? $page + 1 : 0;
		}
		if(!empty($params['grid_ajax_load'])) {
			$next_page = $next_page_pagination = 1;
		}

		$item_classes = get_thegem_portfolio_render_item_classes($params);
		$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params);

		$search_only = true;
		if ($params['product_show_filter'] == '1' && (!$normal_filter || (
				($params['filter_by_categories'] == '1' && count($terms) > 0) ||
				$params['filter_by_attribute'] == '1' ||
				$params['filter_by_price'] == '1' ||
				$params['filter_by_status'] == '1'
			))) {
			$search_only = false;
		}

		if ($params['columns_desktop'] == '100%' || (($params['ignore_highlights'] !== '1' || $params['layout'] !== 'justified') && $params['skeleton_loader'] !== '1')) {
			$spin_class = 'preloader-spin';
			if (isset($params['ajax_preloader_type']) && $params['ajax_preloader_type'] == 'minimal') {
				$spin_class = 'preloader-spin-new';
			}
			echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="' . $spin_class . '"></div></div>');
		} else if ($params['skeleton_loader'] == '1') { ?>
			<div id="style-preloader-<?php echo esc_attr($style_uid); ?>" class="preloader save-space">
				<div class="skeleton panel-sidebar-position-<?php echo $params['sidebar_position']; ?>">
					<?php if ($params['product_show_filter'] == '1' && $params['filters_style'] == 'sidebar' && !$search_only) { ?>
					<div class="with-filter-sidebar">
						<div class="filter-sidebar">
							<div class="widget"></div>
							<div class="widget"></div>
							<div class="widget"></div>
						</div>
						<div class="content">
							<?php }
							if ($params['product_show_sorting'] == '1') { ?>
								<div class="portfolio-top-panel">
									<div class="skeleton-sorting"></div>
								</div>
							<?php } ?>
							<div class="skeleton-posts row portfolio-row">
								<?php for ($x = 0; $x < $product_loop->post_count; $x++) {
									echo thegem_extended_products_render_item($params, $item_classes);
								} ?>
							</div>
							<?php if ($params['product_show_filter'] == '1' && $params['filters_style'] == 'sidebar' && !$search_only) { ?>
						</div>
					</div>
				<?php } ?>
				</div>
			</div>
		<?php } ?>

		<div class="portfolio-preloader-wrapper panel-sidebar-position-<?php echo $params['sidebar_position']; ?> ">

			<?php
			if ($params['caption_position'] == 'hover') {
				$title_on = 'hover';
			} else {
				$title_on = 'page';
			}

			$portfolio_classes = array(
				'portfolio portfolio-grid extended-portfolio-grid extended-products-grid',
				'woocommerce',
				'products',
				'no-padding',
				'portfolio-preset-' . $params['extended_grid_skin'],
				$params['show_pagination'] === '1' ? 'portfolio-pagination-' . $params['pagination_type'] : 'portfolio-pagination-disabled',
				'portfolio-style-' . $params['layout'],
				'background-style-' . $params['caption_container_preset'],
				(($params['caption_position'] == 'hover' && ($params['image_hover_effect_hover'] == 'slide' || $params['image_hover_effect_hover'] == 'fade')) || $params['caption_position'] == 'image') ? 'caption-container-preset-' . $params['caption_container_preset_hover'] : '',
				(($params['caption_position'] == 'hover' && ($params['image_hover_effect_hover'] == 'slide' || $params['image_hover_effect_hover'] == 'fade')) || $params['caption_position'] == 'image') ? 'caption-alignment-' . $params['caption_container_alignment_hover'] : '',
				'caption-position-' . $params['caption_position'],
				'hover-' . $hover_effect,
				'title-on-' . $title_on,
				(!isset($params['image_size']) || $params['image_size'] == 'default' ? 'aspect-ratio-' . $params['image_aspect_ratio'] : ''),
				($params['layout_type'] == 'list' ? 'list-style disabled-hover caption-position-list-' . $params['caption_position_list'] : ''),
				($params['layout_type'] == 'list' ? 'caption-alignment-list-' . $params['caption_container_alignment'] : ''),
				($params['layout_type'] == 'list' ? 'caption-layout-list-' . $params['caption_layout_list'] : ''),
				($is_archive_template ? 'main-loop-grid' : ''),
				($params['loading_animation'] == '1' ? 'loading-animation' : ''),
				($params['loading_animation'] == '1' && $params['animation_effect'] ? 'item-animation-' . $params['animation_effect'] : ''),
				($params['loading_animation'] == '1' && $params['loading_animation_mobile'] ? 'enable-animation-mobile' : ''),
				($params['image_gaps'] == 0 ? 'no-gaps' : ''),
				($params['shadowed_container'] == '1' ? 'shadowed-container' : ''),
				($params['columns_desktop'] == '100%' || $params['fullwidth_section_sorting'] == '1' ? 'fullwidth-columns' : ''),
				($params['columns_desktop'] == '100%' ? 'fullwidth-columns-desktop-' . $params['columns_100'] : ''),
				($params['caption_position'] == 'image' && $params['image_hover_effect_image'] == 'gradient' ? 'hover-gradient-title' : ''),
				($params['caption_position'] == 'image' && $params['image_hover_effect_image'] == 'circular' ? 'hover-circular-title' : ''),
				($params['caption_position'] == 'hover' || $params['caption_position'] == 'image' ? 'hover-title' : ''),
				($params['social_sharing'] != '1' ? 'portfolio-disable-socials' : ''),
				($params['layout'] == 'masonry' ? 'portfolio-items-masonry' : ''),
				($params['columns_desktop'] != '100%' ? 'columns-desktop-' . $params['columns_desktop'] : 'columns-desktop-' . $params['columns_100']),
				('columns-tablet-' . $params['columns_tablet']),
				('columns-mobile-' . $params['columns_mobile']),
				($params['product_separator'] == '1' ? 'item-separator' : ''),
				($params['layout'] == 'justified' && $params['ignore_highlights'] == '1' ? 'disable-isotope' : ''),
				($params['next_page_preloading'] == '1' && $params['show_pagination'] === '1' ? 'next-page-preloading' : ''),
				($params['tabs_preloading'] == '1' ? 'tabs-preloading' : ''),
				($params['product_show_divider'] == '1' ? 'with-divider' : ''),
				((isset($params['image_size']) && $params['image_size'] == 'full' && empty($params['image_ratio_full']) || !in_array($params['image_size'], ['full', 'default'])) ? 'full-image' : 'aspect-ratio-custom'),
				(isset($params['ajax_preloader_type']) && $params['ajax_preloader_type'] == 'minimal' ? 'minimal-preloader' : ''),
				($params['reduce_html_size'] ? 'reduce-size' : ''),
			);
			?>

			<div class="<?php echo esc_attr(implode(' ', $portfolio_classes)) ?>"
				 id="style-<?php echo esc_attr($style_uid); ?>"
				 data-per-page="<?php echo esc_attr($items_per_page) ?>"
				 data-current-page="<?php echo esc_attr($page) ?>"
				 data-next-page="<?php echo esc_attr($next_page) ?>"
				 data-next-tab="<?php echo esc_attr($active_tab) ?>"
				 data-pages-count="<?php echo esc_attr($max_page) ?>"
				 data-style-uid="<?php echo esc_attr($style_uid); ?>"
				 data-portfolio-uid="<?php echo esc_attr($grid_uid) ?>"
				 data-hover="<?php echo esc_attr($hover_effect) ?>"
				 data-portfolio-filter='<?php echo esc_attr($categories_filter) ?>'
				 data-portfolio-filter-attributes='<?php echo esc_attr(json_encode($attributes_filter)) ?>'
				 data-portfolio-filter-status='<?php echo esc_attr(json_encode($status_current)) ?>'
				 data-portfolio-filter-price='<?php echo esc_attr(json_encode($price_current)) ?>'
				 data-portfolio-filter-search='<?php echo esc_attr($search_current) ?>'>
				<?php
				$show_widgets = false;
				$has_right_panel = $params['product_show_sorting'] == '1' || ( $params['filter_by_search'] == '1' && ( $params['filters_style'] == 'standard' || $search_only)); ?>

				<?php if ($params['source_type'] == 'cross_sell' && $params['show_cross_sell_title']) {
					$text_styled_class = implode(' ', array($params['cross_sell_title_style'], $params['cross_sell_title_weight'])); ?>
					<<?php echo $params['cross_sell_title_tag']; ?> class="cross-sell-title <?php echo $text_styled_class; ?>">
						<?php echo $params['cross_sell_title_text']; ?>
					</<?php echo $params['cross_sell_title_tag']; ?>>
				<?php } ?>

				<div class="portfolio-row-outer <?php if ($params['columns_desktop'] == '100%' || $params['fullwidth_section_sorting'] == '1'): ?>fullwidth-block no-paddings<?php endif; ?>">
					<?php if ($is_archive_template) {
						$shop_url = get_post_type_archive_link('product');
						if (!$normal_filter && is_tax('product_cat')) {
							$shop_url = get_term_link($queried->slug, 'product_cat');
						} else if (!$normal_filter && is_tax('product_tag')) {
							$shop_url = get_term_link($queried->slug, 'product_tag');
						} ?>
						<input id="shop-page-url" type="hidden"
							   value="<?php echo esc_url($shop_url); ?>">
					<?php } ?>
					<?php if ($params['product_show_filter'] == '1' && $params['filters_style'] == 'sidebar' && !$search_only) { ?>
					<div class="with-filter-sidebar <?php echo $params['sidebar_sticky'] || $params['woo_sidebar_sticky'] ? 'sticky-sidebar' : ''; ?>">
						<div class="filter-sidebar <?php echo $params['product_show_sorting'] == '1' ? 'left' : ''; ?>">
							<?php
							if ( $normal_filter ) {
								include( locate_template( array( 'gem-templates/products-extended/filters.php' ) ) );
							} else { ?>
								<div class="portfolio-filters-list sidebar
										<?php echo $normal_filter ? 'normal hide-mobile hide-tablet' : 'native'; ?>
										style-sidebar <?php echo $params['filters_scroll_top'] == '1' ? 'scroll-top' : ''; ?>
										<?php echo $has_right_panel ? 'has-right-panel' : ''; ?>
										<?php echo $params['woo_remove_counts'] == '1' ? 'hide-filter-counts' : ''; ?>
										<?php echo $native_ajax ? 'native-ajax-filters' : ''; ?>">
									<div class="portfolio-show-filters-button <?php echo $params['filter_buttons_hidden_show_icon'] == '1' ? 'with-icon' : ''; ?>">
										<?php echo esc_html( $params['filter_buttons_hidden_show_text'] ); ?>
										<?php if ( $params['filter_buttons_hidden_show_icon'] == '1' ) { ?>
											<span class="portfolio-show-filters-button-icon"></span>
										<?php } ?>
									</div>

									<div class="portfolio-filters-outer">
										<div class="portfolio-filters-area">
											<div class="portfolio-filters-area-scrollable">
												<div class="widget-area-wrap">
													<?php get_sidebar('shop'); ?>
												</div>
											</div>
										</div>
										<div class="portfolio-close-filters"></div>
									</div>

								</div>
							<?php } ?>
						</div>
						<div class="content">
							<?php }
							$selected_shown = false;
							if (($params['product_show_filter'] == '1' || $params['product_show_sorting'] == '1') && $params['filters_style'] != 'tabs'): ?>
								<div class="portfolio-top-panel<?php
								echo $params['filters_style'] == 'sidebar' ? ' sidebar-filter' : '';
								echo (!$params['product_show_sorting'] && (!$search_only && $params['filters_style'] == 'sidebar')) ? ' selected-only' : '';
								echo $params['filters_sticky'] ? ' filters-top-sticky' : ''; ?>">
									<?php if ($params['filters_sticky']) {
										wp_enqueue_script('thegem-sticky');
									} ?>
									<div class="portfolio-top-panel-row">
										<div class="portfolio-top-panel-left <?php echo esc_attr($params['filter_buttons_standard_alignment']); ?>">
											<?php
											if ($normal_filter) {
												if ($params['product_show_filter'] == '1' && $params['filters_style'] != 'sidebar' && !$search_only) {
													include(locate_template(array('gem-templates/products-extended/filters.php')));
												}
												if (($params['product_show_filter'] == '1' && $params['filters_style'] == 'sidebar') || $params['product_show_filter'] != '1') {
													$selected_shown = true;
													include(locate_template(array('gem-templates/products-extended/selected-filters.php')));
												}
											} else {
												if ($params['filters_style'] == 'hidden' && is_active_sidebar('shop-sidebar')) { ?>
													<div class="portfolio-filters-list sidebar native style-<?php echo esc_attr($params['filters_style']); ?>
															<?php echo $params['filters_scroll_top'] == '1' ? 'scroll-top' : ''; ?>
															<?php echo $has_right_panel ? 'has-right-panel' : ''; ?>
															<?php echo $params['woo_remove_counts'] == '1' ? 'hide-filter-counts' : ''; ?>
															<?php echo $native_ajax ? 'native-ajax-filters' : ''; ?>">

														<div class="portfolio-show-filters-button <?php echo $params['filter_buttons_hidden_show_icon'] == '1' ? 'with-icon' : ''; ?>">
															<?php echo esc_html($params['filter_buttons_hidden_show_text']); ?>
															<?php if ($params['filter_buttons_hidden_show_icon'] == '1') { ?>
																<span class="portfolio-show-filters-button-icon"></span>
															<?php } ?>
														</div>

														<div class="portfolio-filters-outer">
															<div class="portfolio-filters-area">
																<div class="portfolio-filters-area-scrollable">
																	<div class="widget-area-wrap">
																		<?php get_sidebar('shop'); ?>
																	</div>
																</div>
															</div>
															<div class="portfolio-close-filters"></div>
														</div>

													</div>
												<?php }
												if ($params['filters_style'] == 'sidebar' && $native_ajax) {
													$selected_shown = true;
													include(locate_template(array('gem-templates/products-extended/selected-filters.php')));
												}
											} ?>
										</div>
										<?php if ($has_right_panel): ?>
											<div class="portfolio-top-panel-right">
												<?php if ($params['product_show_sorting'] == '1') {
													if ($params['sorting_type'] == 'extended') {
														$repeater_sort = vc_param_group_parse_atts($params['repeater_sort']);
														if (!empty($repeater_sort)) { ?>
															<div class="portfolio-sorting-select open-dropdown-<?php
															echo $params['sorting_dropdown_open']; ?>">
																<div class="portfolio-sorting-select-current">
																	<div class="portfolio-sorting-select-name">
																		<?php
																		if ($selected_orderby == 'default') {
																			echo esc_html($params['sorting_text']);
																		} else {
																			foreach ($repeater_sort as $item) {
																				if (in_array($item['attribute_type'], ['date', 'title', 'price', 'rating', 'popularity'])) {
																					$sort_by = $item['attribute_type'];
																				} else {
																					if ($item['attribute_type'] == 'details') {
																						$sort_by = isset($item['attribute_details']) ? $item['attribute_details'] : '';
																					} else if ($item['attribute_type'] == 'custom_fields') {
																						$sort_by = isset($item['attribute_custom_fields']) ? $item['attribute_custom_fields'] : '';
																					} else if ($item['attribute_type'] == 'manual_key') {
																						$sort_by = isset($item['manual_key_field']) ? $item['manual_key_field'] : '';
																					} else {
																						$sort_by = isset($item['attribute_custom_fields_acf_' . $item['attribute_type']]) ? $item['attribute_custom_fields_acf_' . $item['attribute_type']] : '';
																					}
																					if (empty($sort_by)) continue;
																					if (isset($item['field_type']) && $item['field_type'] == 'number') {
																						$sort_by = 'num_' . $sort_by;
																					}
																				}
																				if ($selected_orderby == $sort_by && $selected_order == $item['sort_order']) {
																					echo esc_html($item['title']);
																					break;
																				}
																			}
																		} ?>
																	</div>
																	<span class="portfolio-sorting-select-current-arrow"></span>
																</div>
																<ul>
																	<li class="default <?php echo $selected_orderby == 'default' ? 'portfolio-sorting-select-current' : ''; ?>"
																		data-orderby="default" data-order="default">
																		<?php echo esc_html($params['sorting_text']); ?>
																	</li>
																	<?php foreach ($repeater_sort as $item) {
																		if (in_array($item['attribute_type'], ['date', 'title', 'price', 'rating', 'popularity'])) {
																			$sort_by = $item['attribute_type'];
																		} else {
																			if ($item['attribute_type'] == 'details') {
																				$sort_by = isset($item['attribute_details']) ? $item['attribute_details'] : '';
																			} else if ($item['attribute_type'] == 'custom_fields') {
																				$sort_by = isset($item['attribute_custom_fields']) ? $item['attribute_custom_fields'] : '';
																			} else if ($item['attribute_type'] == 'manual_key') {
																				$sort_by = isset($item['manual_key_field']) ? $item['manual_key_field'] : '';
																			} else {
																				$sort_by = isset($item['attribute_custom_fields_acf_' . $item['attribute_type']]) ? $item['attribute_custom_fields_acf_' . $item['attribute_type']] : '';
																			}
																			if (empty($sort_by)) continue;
																			if (isset($item['field_type']) && $item['field_type'] == 'number') {
																				$sort_by = 'num_' . $sort_by;
																			}
																		} ?>
																		<li class="<?php echo $selected_orderby == $sort_by && $selected_order == $item['sort_order'] ? 'portfolio-sorting-select-current' : ''; ?>"
																			data-orderby="<?php echo esc_attr($sort_by); ?>" data-order="<?php echo esc_attr($item['sort_order']); ?>">
																			<?php echo esc_html($item['title']); ?>
																		</li>
																	<?php } ?>
																</ul>
															</div>
														<?php }
													} else { ?>
														<div class="portfolio-sorting-select">
															<div class="portfolio-sorting-select-current">
																<div class="portfolio-sorting-select-name">
																	<?php
																	switch ($selected_orderby) {
																		case "date":
																			echo esc_html($params["sorting_dropdown_latest_text"]);
																			break;
																		case "popularity":
																			echo esc_html($params["sorting_dropdown_popularity_text"]);
																			break;
																		case "rating":
																			echo esc_html($params["sorting_dropdown_rating_text"]);
																			break;
																		case "price-asc":
																			echo esc_html($params["sorting_dropdown_price_low_high_text"]);
																			break;
																		case "price-desc":
																			echo esc_html($params["sorting_dropdown_price_high_low_text"]);
																			break;
																		default:
																			echo esc_html($params['sorting_text']);
																	} ?>
																</div>
																<span class="portfolio-sorting-select-current-arrow"></span>
															</div>
															<ul>
																<li class="default <?php echo $selected_orderby == 'default' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="<?php echo esc_attr($params['orderby']); ?>"
																	data-order="<?php echo esc_attr($params['order']); ?>"><?php echo esc_html($params['sorting_text']); ?></li>
																<li class="<?php echo $selected_orderby == 'date' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="date"
																	data-order="desc"><?php echo esc_html($params['sorting_dropdown_latest_text']); ?>
																</li>
																<li class="<?php echo $selected_orderby == 'popularity' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="popularity"
																	data-order="desc"><?php echo esc_html($params['sorting_dropdown_popularity_text']); ?>
																</li>
																<li class="<?php echo $selected_orderby == 'rating' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="rating"
																	data-order="desc"><?php echo esc_html($params['sorting_dropdown_rating_text']); ?>
																</li>
																<li class="<?php echo $selected_orderby == 'price-asc' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="price"
																	data-order="asc"><?php echo esc_html($params['sorting_dropdown_price_low_high_text']); ?>
																</li>
																<li class="<?php echo $selected_orderby == 'price-desc' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="price"
																	data-order="desc"><?php echo esc_html($params['sorting_dropdown_price_high_low_text']); ?>
																</li>
															</ul>
														</div>
												<?php }
												} ?>

												<?php if ($params['filter_by_search'] == '1' && ($params['filters_style'] == 'standard' || $search_only)) { ?>
													<span>&nbsp;</span>
													<form class="portfolio-search-filter <?php echo $search_only ? 'mobile-visible' : ''; ?>"
														  role="search" action="">
														<div class="portfolio-search-filter-form">
															<input type="search"
																   placeholder="<?php echo esc_attr($params['filters_text_labels_search_text']); ?>"
																   value="<?php echo esc_attr($search_current); ?>">
														</div>
														<div class="portfolio-search-filter-button"></div>
													</form>
												<?php } ?>
											</div>
										<?php endif; ?>
									</div>
									<?php if ($params['product_show_filter'] == '1') {
										$selected_shown = true;
										include(locate_template(array('gem-templates/products-extended/selected-filters.php')));
									} ?>
								</div>
							<?php endif; ?>
							<?php if ($params['product_show_filter'] == '1' && $params['filters_style'] == 'tabs'): ?>
								<div class="portfolio-top-panel">
									<div class="portfolio-filter-tabs style-<?php echo $params['filters_tabs_style']; ?> alignment-<?php echo $params['product_tabs_alignment']; ?> <?php echo $params['filters_tabs_title_separator'] == '1' ? 'separator' : ''; ?>">
										<?php if ($params['filters_tabs_title_text'] != '') { ?>
											<div class="title-h4 portfolio-filter-tabs-title <?php echo $params['filters_tabs_title_style_preset'] == 'thin' ? 'light' : ''; ?>"><?php echo $params['filters_tabs_title_text']; ?></div>
										<?php }
										$filter_tabs = vc_param_group_parse_atts($params['filters_tabs_tabs']);
										if (!empty($filter_tabs)) { ?>
											<ul class="portfolio-filter-tabs-list">
												<?php foreach ($filter_tabs as $index => $item) {
													if (!empty($item['filters_tabs_tab_title'])) { ?>
														<li class="portfolio-filter-tabs-list-tab <?php echo $index == $active_tab - 1 ? 'active' : ''; ?>"
															data-num="<?php echo $index + 1; ?>"
															data-filter="<?php echo $item['filters_tabs_tab_filter_by'] ?>"
															data-filter-cat="<?php echo isset($item['filters_tabs_tab_products_cat']) ? $item['filters_tabs_tab_products_cat'] : ''; ?>">
															<?php echo $item['filters_tabs_tab_title'] ?>
														</li>
													<?php }
												} ?>
											</ul>
										<?php } ?>
										<?php if ($params['pagination_type'] == 'arrows' && $params['filters_tabs_style'] == 'alternative'): ?>
											<div class="portfolio-navigator gem-pagination gem-pagination-arrows">
												<a href="" class="prev">
													<?php
													if (isset($params['pagination_arrows_left_pack']) && $params['pagination_arrows_left_icon_' . $params['pagination_arrows_left_pack']] != '') {
														echo thegem_build_icon($params['pagination_arrows_left_pack'], $params['pagination_arrows_left_icon_' . $params['pagination_arrows_left_pack']]);
													} else { ?>
														<i class="default"></i>
													<?php } ?>
												</a>
												<a href="" class="next"><?php
													if (isset($params['pagination_arrows_right_pack']) && $params['pagination_arrows_right_icon_' . $params['pagination_arrows_right_pack']] != '') {
														echo thegem_build_icon($params['pagination_arrows_right_pack'], $params['pagination_arrows_right_icon_' . $params['pagination_arrows_right_pack']]);
													} else { ?>
														<i class="default"></i>
													<?php } ?></a>
											</div>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php if (!$selected_shown) { ?>
								<div class="portfolio-top-panel selected-only">
									<?php include(locate_template(array('gem-templates/products-extended/selected-filters.php'))); ?>
								</div>
							<?php } ?>
							<div class="row portfolio-row clearfix">
								<div class="portfolio-set"
									 data-max-row-height="<?php echo $params['metro_max_row_height'] ? esc_attr($params['metro_max_row_height']) : ''; ?>">
									<?php
									if ($product_loop->have_posts()) {
										remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
										remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
										remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
										remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
										remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
										remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
										remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
										remove_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_link', 15);
										remove_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_wishlist', 20);
										while ($product_loop->have_posts() && empty($params['grid_ajax_load'])) : $product_loop->the_post(); ?>
											<?php echo thegem_extended_products_render_item($params, $item_classes, $thegem_sizes, get_the_ID()); ?>
										<?php
										endwhile;
										wp_reset_postdata();
									} else { ?>
										<div class="portfolio-item not-found">
											<div class="found-wrap">
												<div class="image-inner empty"></div>
												<div class="msg">
													<?php echo wp_kses($params['not_found_text'], 'post'); ?>
												</div>
											</div>
										</div>
									<?php } ?>
								</div><!-- .portflio-set -->
								<div class="portfolio-item-size-container">
									<?php echo thegem_extended_products_render_item($params, $item_classes); ?>
								</div>
							</div><!-- .row-->
							<?php

							/** Pagination */

							if ('1' === ($params['show_pagination'])) : ?>
								<?php if ($params['pagination_type'] == 'normal'): ?>
									<div class="portfolio-navigator gem-pagination"<?php if ($max_page < 2) { echo ' style="display:none;"'; } ?>>
										<a href="" class="prev"><i class="default"></i></a>
										<div class="pages"></div>
										<a href="" class="next"><i class="default"></i></a>
									</div>
								<?php endif; ?>
								<?php
								if ($params['pagination_type'] == 'more' && $next_page_pagination > 0):

									$separator_enabled = ($params['more_show_separator'] === '1' && $params['more_stretch_full_width'] !== '1') ? true : false;

									// Container
									$classes_container = 'gem-button-container gem-widget-button ';

									if ($separator_enabled) {
										$classes_container .= 'gem-button-position-center gem-button-with-separator ';
									} else {
										if ('1' === $params['more_stretch_full_width']) {
											$classes_container .= 'gem-button-position-fullwidth ';
										}
									}

									// Separator
									$classes_separator = 'gem-button-separator ';

									if (!empty($params['pagination_more_button_separator_style'])) {
										$classes_separator .= $params['pagination_more_button_separator_style'];
									}

									// Link
									$classes_button = "load-more-button gem-button gem-button-text-weight-" . $params['pagination_more_button_text_weight'] . " gem-button-size-" . $params['pagination_more_button_size'] . " gem-button-style-" . $params['pagination_more_button_type'];
									?>

									<div class="portfolio-load-more gem-pagination">
										<div class="inner">
											<?php include(locate_template(array('gem-templates/products-extended/more-button.php'))); ?>
										</div>
									</div>
								<?php endif; ?>
								<?php if ($params['pagination_type'] == 'scroll' && $next_page_pagination > 0): ?>
									<div class="portfolio-scroll-pagination gem-pagination"></div>
								<?php endif; ?>
							<?php endif; ?>
							<?php if ($params['pagination_type'] == 'arrows' && ($params['filters_style'] !== 'tabs' || $params['filters_tabs_style'] == 'default')): ?>
								<div class="portfolio-navigator gem-pagination gem-pagination-arrows alignment-<?php echo $params['product_tabs_alignment']; ?>">
									<a href="" class="prev"><i class="default"></i></a>
									<a href="" class="next"><i class="default"></i></a>
								</div>
							<?php endif; ?>

							<?php if ($params['product_show_filter'] == '1' && $params['filters_style'] == 'sidebar' && !$search_only) { ?>
						</div>
					</div>
				<?php }

				thegem_woocommerce_product_page_ajax_notification($params); ?>

				</div><!-- .full-width -->
			</div><!-- .portfolio-->
			<script>
				(function ($) {
					$(document).ready(function () {
						$('.portfolio-filters-list .widget_layered_nav, .portfolio-filters-list .widget_product_categories').find('.count').each(function () {
							$(this).html($(this).html().replace('(', '').replace(')', '')).css('opacity', 1);
						});
					});
				})(jQuery);
			</script>
		</div><!-- .portfolio-preloader-wrapper-->
	<?php

	else: ?>
		<?php if (!$is_related_upsell && !$recently_viewed_only) { ?>
			<div class="bordered-box centered-box styled-subtitle">
				<?php echo esc_html__('Please select products in "Products" section', 'thegem') ?>
			</div>
		<?php } ?>
	<?php endif;
	$post = $portfolio_posttemp;
}

// Print Products Compact Grid
function thegem_products_compact_grid($params) {
	global $post;
	$portfolio_posttemp = $post;

	wp_enqueue_style('thegem-products-compact-grid');

	$unique_id = uniqid('thegem-custom-menu-') . rand(1, 9999);
	$custom_css = '';

	$taxonomy_filter_current = [];
	if ($params['select_products_categories'] == '1') {
		$taxonomy_filter_current['product_cat'] = explode(",", $params['content_products_cat']);
	} else {
		$taxonomy_filter_current['product_cat'] = ['0'];
	}

	$attributes = [];
	if ($params['select_products_attributes'] == '1') {
		if (!empty($params['content_products_attr'])) {
			$attrs = explode(",", $params['content_products_attr']);

			if ($attrs) {
				foreach ($attrs as $attr) {
					$values = explode(",", $params['content_products_attr_val_' . $attr]);
					if (in_array('0', $values) || empty($values)) {
						$values = get_terms('pa_' . $attr, array('fields' => 'slugs'));
					}
					$attributes[$attr] = $values;
				}
			}
		} else if (!empty($params['content_products_attr_val'])) {
			$attrs = explode(", ", $params['content_products_attr_val']);

			if ($attrs) {
				foreach ($attrs as $attr) {
					$attr_arr = explode("|", $attr);
					if ($attr_arr[1] == 'all') {
						$values = get_terms('pa_' . $attr_arr[0], array('fields' => 'slugs'));
					} else {
						if (isset($attributes[$attr_arr[0]])) {
							if (!in_array($attr_arr[1], $attributes[$attr_arr[0]])) {
								$values = array_push($attributes[$attr_arr[0]], $attr_arr[1]);
							} else {
								$values = false;
							}
						} else {
							$values = [$attr_arr[1]];
						}
					}
					if ($values) {
						$attributes[$attr_arr[0]] = $values;
					}
				}
			}
		}
	}

	$items_per_page = $params['items_per_page'] ? intval($params['items_per_page']) : 8;

	$page = 1;
	$orderby = $params['orderby'];
	$order = $params['order'];

	$featured_only = $params['featured_only'] == '1';
	$sale_only = $params['sale_only'] == '1';
	$recently_viewed_only = $params['recently_viewed_only'] == '1';
	$new_only = $params['new_only'] == '1';

	$product_loop = thegem_extended_products_get_posts($page, $items_per_page, $orderby, $order, $featured_only, $sale_only, $stock_only = false, $recently_viewed_only, $new_only, $taxonomy_filter_current, [], $attributes);

	if ($product_loop && $product_loop->have_posts()) :

		if (!empty($params['gaps_mobile'])) {
			$custom_css .= '.products-compact-grid#' . esc_attr($unique_id) . '.layout-grid { grid-row-gap:' . $params['gaps_mobile'] . 'px; grid-column-gap:' . $params['gaps_mobile'] . 'px; }
			.products-compact-grid#' . esc_attr($unique_id) . '.layout-list:not(.with-separator) .compact-product-item { padding-bottom:' . $params['gaps_mobile'] . 'px; }
			.products-compact-grid#' . esc_attr($unique_id) . '.layout-list.with-separator .compact-product-item { padding-bottom: calc(' . $params['gaps_mobile'] . 'px / 2); margin-bottom: calc(' . $params['gaps_mobile'] . 'px / 2); }';
		}

		if (!empty($params['gaps_tablet'])) {
			$custom_css .= '@media (min-width: 768px) {
			.products-compact-grid#' . esc_attr($unique_id) . '.layout-grid { grid-row-gap:' . $params['gaps_tablet'] . 'px; grid-column-gap:' . $params['gaps_tablet'] . 'px; }
			.products-compact-grid#' . esc_attr($unique_id) . '.layout-list:not(.with-separator) .compact-product-item { padding-bottom:' . $params['gaps_tablet'] . 'px; }
			.products-compact-grid#' . esc_attr($unique_id) . '.layout-list.with-separator .compact-product-item { padding-bottom: calc(' . $params['gaps_tablet'] . 'px / 2); margin-bottom: calc(' . $params['gaps_tablet'] . 'px / 2); }
			}';
		}

		if (!empty($params['gaps'])) {
			$custom_css .= '@media (min-width: 992px) {
			.products-compact-grid#' . esc_attr($unique_id) . '.layout-grid { grid-row-gap:' . $params['gaps'] . 'px; grid-column-gap:' . $params['gaps'] . 'px; }
			.products-compact-grid#' . esc_attr($unique_id) . '.layout-list:not(.with-separator) .compact-product-item { padding-bottom:' . $params['gaps'] . 'px; }
			.products-compact-grid#' . esc_attr($unique_id) . '.layout-list.with-separator .compact-product-item { padding-bottom: calc(' . $params['gaps'] . 'px / 2); margin-bottom: calc(' . $params['gaps'] . 'px / 2); }
			}';
		}

		if (!empty($params['image_border_radius'])) {
			$custom_css .= '.products-compact-grid#' . esc_attr($unique_id) . '.layout-list .image a { border-radius:' . $params['image_border_radius'] . 'px; }';
		}

		if ($params['product_separator'] == '1') {
			if (!empty($params['product_separator_width'])) {
				$custom_css .= '.products-compact-grid#' . esc_attr($unique_id) . '.layout-list.with-separator .compact-product-item { border-width:' . $params['product_separator_width'] . 'px; }';
			}
			if (!empty($params['product_separator_color'])) {
				$custom_css .= '.products-compact-grid#' . esc_attr($unique_id) . '.layout-list.with-separator .compact-product-item { border-color:' . $params['product_separator_color'] . '; }';
			}
		}

		if (!empty($params['categories_color_normal'])) {
			$custom_css .= '.products-compact-grid#' . esc_attr($unique_id) . ' .caption .categories { color:' . $params['categories_color_normal'] . '; }';
		}

		if (!empty($params['categories_color_hover'])) {
			$custom_css .= '.products-compact-grid#' . esc_attr($unique_id) . ' .caption .categories a:hover { color:' . $params['categories_color_hover'] . '; }';
		}

		if (!empty($params['title_color_normal'])) {
			$custom_css .= '.products-compact-grid#' . esc_attr($unique_id) . ' .caption .title { color:' . $params['title_color_normal'] . '; }';
		}

		if (!empty($params['title_color_hover'])) {
			$custom_css .= '.products-compact-grid#' . esc_attr($unique_id) . ' .caption .title a:hover { color:' . $params['title_color_hover'] . '; }';
		}

		if (!empty($params['price_color_normal'])) {
			$custom_css .= '.products-compact-grid#' . esc_attr($unique_id) . ' .product-price .price { color:' . $params['price_color_normal'] . '; }';
		}

		if (!empty($params['rating_rated_color'])) {
			$custom_css .= '.products-compact-grid#' . esc_attr($unique_id) . ' .star-rating > span:before { color:' . $params['rating_rated_color'] . '; }';
		}

		if (!empty($params['rating_base_color'])) {
			$custom_css .= '.products-compact-grid#' . esc_attr($unique_id) . ' .star-rating:before { color:' . $params['rating_base_color'] . '; }';
		}

		if (!empty($params['caption_background'])) {
			$custom_css .= '.products-compact-grid#' . esc_attr($unique_id) . ' .wrap { background-color:' . $params['caption_background'] . '; }';
		}

		$portfolio_classes = array(
			'products-compact-grid woocommerce',
			'layout-' . $params['layout'],
			'columns-' . $params['columns'],
			'alignment-' . $params['caption_alignment'],
			'aspect-ratio-' . $params['image_aspect_ratio'],
			$params['product_separator'] == 1 ? 'with-separator' : '',
		);
		?>

		<div id="<?php echo(esc_attr($unique_id)); ?>" class="<?php echo esc_attr(implode(' ', $portfolio_classes)) ?>">
			<?php
			if ($product_loop->have_posts()) {
				while ($product_loop->have_posts()) : $product_loop->the_post();
					include(locate_template(array('gem-templates/products-extended/content-product-grid-item-compact.php'))); ?>
				<?php
				endwhile;
				wp_reset_postdata();
			} else { ?>
				<div class="portfolio-item not-found">
					<div class="found-wrap">
						<div class="image-inner empty"></div>
						<div class="msg">
							<?php echo wp_kses($params['not_found_text'], 'post'); ?>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>

		<?php // Print custom css
		if (!empty($custom_css)) { ?>
			<style><?php echo $custom_css; ?></style>
		<?php } ?>
	<?php else: ?>
		<?php if (!$recently_viewed_only) { ?>
			<div class="bordered-box centered-box styled-subtitle">
				<?php echo esc_html__('Please select products in "Products" section', 'thegem') ?>
			</div>
		<?php } ?>
	<?php endif;

	$post = $portfolio_posttemp;
}

// Print Products Grid Carousel Extended
function thegem_products_grid_carousel_extended($params) {
	global $post;
	$portfolio_posttemp = $post;
	$grid_uid = $params['portfolio_uid'];
	$style_uid = substr(md5(rand()), 0, 7);
	$params['style_uid'] = $style_uid;

	wp_enqueue_style('thegem-portfolio-products-carousel');
	wp_enqueue_script('thegem-portfolio-products-carousel');

	$taxonomy_filter_current = [];
	if ($params['select_products_categories'] == '1') {
		$taxonomy_filter_current['product_cat'] = explode(",", $params['content_products_cat']);
	} else {
		$taxonomy_filter_current['product_cat'] = ['0'];
	}

	$attributes = [];
	if ($params['select_products_attributes'] == '1') {
		if (!empty($params['content_products_attr'])) {
			$attrs = explode(",", $params['content_products_attr']);

			if ($attrs) {
				foreach ($attrs as $attr) {
					$values = explode(",", $params['content_products_attr_val_' . $attr]);
					if (in_array('0', $values) || empty($values)) {
						$values = get_terms('pa_' . $attr, array('fields' => 'slugs'));
					}
					$attributes[$attr] = $values;
				}
			}
		} else if (!empty($params['content_products_attr_val'])) {
			$attrs = explode(", ", $params['content_products_attr_val']);

			if ($attrs) {
				foreach ($attrs as $attr) {
					$attr_arr = explode("|", $attr);
					if ($attr_arr[1] == 'all') {
						$values = get_terms('pa_' . $attr_arr[0], array('fields' => 'slugs'));
					} else {
						if (isset($attributes[$attr_arr[0]])) {
							if (!in_array($attr_arr[1], $attributes[$attr_arr[0]])) {
								$values = array_push($attributes[$attr_arr[0]], $attr_arr[1]);
							} else {
								$values = false;
							}
						} else {
							$values = [$attr_arr[1]];
						}
					}
					if ($values) {
						$attributes[$attr_arr[0]] = $values;
					}
				}
			}
		}
	}

	if ($params['select_products_tags'] == '1') {
		$taxonomy_filter_current['product_tag'] = explode(",", $params['content_products_tags']);
	}

	$post__in = null;
	if ($params['select_products'] == '1') {
		$post__in = explode(",", $params['content_products']);
	}

	if ($params['caption_position'] == 'image') {
		$hover_effect = $params['image_hover_effect_image'];
	} else if ($params['caption_position'] == 'page') {
		$hover_effect = $params['image_hover_effect_page'];
	} else {
		$hover_effect = $params['image_hover_effect_hover'];
	}

	wp_enqueue_style('thegem-hovers-' . $hover_effect);

	if ($params['quick_view'] == '1') {
		wp_enqueue_script('wc-single-product');
		wp_enqueue_script('wc-add-to-cart-variation');
		wp_enqueue_script('thegem-product-quick-view');
		wp_enqueue_script('thegem-quick-view');
		wp_enqueue_style('thegem-quick-view');

		if(thegem_get_option('product_page_layout') == 'default') {
			if(thegem_get_option('product_page_button_add_to_cart_icon') && thegem_get_option('product_page_button_add_to_cart_icon_pack')) {
				wp_enqueue_style('icons-'.thegem_get_option('product_page_button_add_to_cart_icon_pack'));
			}
			if(thegem_get_option('product_page_button_add_to_wishlist_icon') && thegem_get_option('product_page_button_add_to_wishlist_icon_pack')) {
				wp_enqueue_style('icons-'.thegem_get_option('product_page_button_add_to_wishlist_icon_pack'));
			}
			if(thegem_get_option('product_page_button_added_to_wishlist_icon') && thegem_get_option('product_page_button_added_to_wishlist_icon_pack')) {
				wp_enqueue_style('icons-'.thegem_get_option('product_page_button_added_to_wishlist_icon_pack'));
			}
		}
		if (thegem_get_option('product_gallery') != 'legacy') {
			wp_enqueue_style('thegem-product-gallery');
		} else {
			wp_enqueue_style('thegem-hovers');
		}
	}

	if ($params['loading_animation'] === '1') {
		wp_enqueue_style('thegem-animations');
		wp_enqueue_script('thegem-items-animations');
		wp_enqueue_script('thegem-scroll-monitor');
	}

	if ($params['slider_scroll_init'] === '1') {
		wp_enqueue_script('thegem-scroll-monitor');
	}

	$items_per_page = $params['items_per_page'] ? intval($params['items_per_page']) : 8;

	$page = 1;

	$orderby = $params['orderby'];
	$order = $params['order'];

	$featured_only = $params['featured_only'] == '1';
	$sale_only = $params['sale_only'] == '1';
	$stock_only = $params['stock_only'] == '1';
	$recently_viewed_only = $params['recently_viewed_only'] == '1';
	$new_only = $params['new_only'] == '1';

	if ($params['exclude_type'] == 'current') {
		$params['exclude_products'] = [get_the_ID()];
	} else if ($params['exclude_type'] == 'term') {
		$params['exclude_products'] = thegem_get_posts_query_section_exclude_ids($params['exclude_product_terms'], 'product');
	} else if (!empty($params['exclude_products'])) {
		$params['exclude_products'] = explode(',', $params['exclude_products']);
	}

	$product_loops = [];
	$active_tab = 1;
	if ($params['product_show_tabs'] == '1') {
		if (isset($_GET[$grid_uid . '-tab'])) {
			$active_tab = intval($_GET[$grid_uid . '-tab']);
		}
		$filter_tabs = vc_param_group_parse_atts($params['filters_tabs_tabs']);
		if (!empty($filter_tabs)) {
			foreach ($filter_tabs as $item) {
				if ($item['filters_tabs_tab_filter_by'] == 'featured') {
					$product_loops[] = thegem_extended_products_get_posts($page, $items_per_page, $orderby, $order, true, $sale_only, $stock_only, $recently_viewed_only, $new_only, $taxonomy_filter_current, [], $attributes, null, null, null, $post__in, $params['offset'], $params['exclude_products']);
				} else if ($item['filters_tabs_tab_filter_by'] == 'sale') {
					$product_loops[] = thegem_extended_products_get_posts($page, $items_per_page, $orderby, $order, $featured_only, true, $stock_only, $recently_viewed_only, $new_only, $taxonomy_filter_current, [], $attributes, null, null, null, $post__in, $params['offset'], $params['exclude_products']);
				} else if ($item['filters_tabs_tab_filter_by'] == 'recent') {
					$product_loops[] = thegem_extended_products_get_posts($page, $items_per_page, 'date', 'desc', $featured_only, $sale_only, $stock_only, $recently_viewed_only, $new_only, $taxonomy_filter_current, [], $attributes, null, null, null, $post__in, $params['offset'], $params['exclude_products']);
				} else if ($item['filters_tabs_tab_filter_by'] == 'categories' && !empty($item['filters_tabs_tab_products_cat'])) {
					$product_loops[] = thegem_extended_products_get_posts($page, $items_per_page, $orderby, $order, $featured_only, $sale_only, $stock_only, $recently_viewed_only, $new_only, ['product_cat' => [$item['filters_tabs_tab_products_cat']]], [], $attributes, null, null, null, $post__in, $params['offset'], $params['exclude_products']);
				}
			}
		}
	} else {
		$is_related_upsell = false;
		if ($params['source_type'] == 'related_upsell') {
			$product = thegem_templates_init_product();
			if (empty($product)) {
				$post = $portfolio_posttemp;
				return;
			}
			$is_related_upsell = true;
			if ($params['related_upsell_source'] == 'related') {
				$related_products = wc_get_related_products($product->get_id(), -1);
				$post__in = $related_products;
			} else {
				global $thegem_product_data;
				$upsells = $product->get_upsell_ids();
				if(intval($thegem_product_data['product_page_elements_upsell_items']) > -1) {
					$upsells = array_slice($upsells, 0, intval($thegem_product_data['product_page_elements_upsell_items']));
				}
				$post__in = $upsells;
			}

			if (empty($post__in) && !is_admin()) {
				$post = $portfolio_posttemp;
				return;
			}
		} else if ($params['source_type'] == 'cross_sell') {
			$cross_sells_ids_in_cart = array();

			if (WC()->cart) {
				foreach ( WC()->cart->get_cart() as $cart_item ) {
					if ( $cart_item['quantity'] > 0 ) {
						$cross_sells_ids_in_cart = array_merge( $cart_item['data']->get_cross_sell_ids(), $cross_sells_ids_in_cart );
					}
				}
			}

			$cross_sells = wp_parse_id_list( $cross_sells_ids_in_cart );
			$post__in = $cross_sells;

			if (empty($post__in) && !is_admin()) {
				$post = $portfolio_posttemp;
				return;
			}
		}

		$product_loops[] = thegem_extended_products_get_posts($page, $items_per_page, $orderby, $order, $featured_only, $sale_only, $stock_only, $recently_viewed_only, $new_only, $taxonomy_filter_current, [], $attributes, null, null, null, $post__in, $params['offset'], $params['exclude_products']);
	}

	if ($params['product_show_tabs'] == '1' || (!empty($product_loops[0]) && $product_loops[0]->have_posts())) :
		$params['layout'] = 'justified';
		$params['ignore_highlights'] = '1';

		echo thegem_extended_products_render_styles($params, true);

		$item_classes = get_thegem_portfolio_render_item_classes($params);
		$item_classes[] = 'owl-item';
		$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params);

		if ($params['disable_preloader'] == '') {
			if ($params['columns_desktop'] == '100%') {
				echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader"><div class="preloader-spin"></div></div>');
			} else { ?>
				<div id="style-preloader-<?php echo esc_attr($style_uid); ?>" class="preloader skeleton-carousel">
					<div class="skeleton">
						<div class="skeleton-posts row portfolio-row">
							<?php for ($x = 0; $x < $product_loops[0]->post_count; $x++) {
								echo thegem_extended_products_render_item($params, $item_classes);
							} ?>
						</div>
					</div>
				</div>
			<?php }
		} ?>

		<div class="portfolio-preloader-wrapper">

			<?php
			if ($params['caption_position'] == 'hover') {
				$title_on = 'hover';
			} else {
				$title_on = 'page';
			}

			$portfolio_classes = array(
				'portfolio portfolio-grid extended-portfolio-grid extended-products-grid extended-products-grid-carousel extended-carousel-grid',
				'woocommerce',
				'products',
				'no-padding',
				'disable-isotope',
				'portfolio-preset-' . $params['extended_grid_skin'],
				'portfolio-style-justified',
				'background-style-' . $params['caption_container_preset'],
				(($params['caption_position'] == 'hover' && ($params['image_hover_effect_hover'] == 'slide' || $params['image_hover_effect_hover'] == 'fade')) || $params['caption_position'] == 'image') ? 'caption-container-preset-' . $params['caption_container_preset_hover'] : '',
				(($params['caption_position'] == 'hover' && ($params['image_hover_effect_hover'] == 'slide' || $params['image_hover_effect_hover'] == 'fade')) || $params['caption_position'] == 'image') ? 'caption-alignment-' . $params['caption_container_alignment_hover'] : '',
				'caption-position-' . $params['caption_position'],
				'hover-' . $hover_effect,
				'title-on-' . $title_on,
				'arrows-position-' . $params['arrows_navigation_position'],
				(!isset($params['image_size']) || $params['image_size'] == 'default' ? 'aspect-ratio-' . $params['image_aspect_ratio'] : ''),
				($params['loading_animation'] == '1' ? 'loading-animation' : ''),
				($params['loading_animation'] == '1' && $params['animation_effect'] ? 'item-animation-' . $params['animation_effect'] : ''),
				($params['image_gaps'] == 0 ? 'no-gaps' : ''),
				($params['shadowed_container'] == '1' ? 'shadowed-container' : ''),
				($params['columns_desktop'] == '100%' ? 'fullwidth-columns fullwidth-columns-desktop-' . $params['columns_100'] : ''),
				($params['columns_desktop'] == '100%' && ($params['image_gaps'] < 24 || $params['product_separator'] == '1') ? 'prevent-arrows-outside' : ''),
				($params['caption_position'] == 'image' && $params['image_hover_effect_image'] == 'gradient' ? 'hover-gradient-title' : ''),
				($params['caption_position'] == 'image' && $params['image_hover_effect_image'] == 'circular' ? 'hover-circular-title' : ''),
				($params['caption_position'] == 'hover' || $params['caption_position'] == 'image' ? 'hover-title' : ''),
				($params['social_sharing'] != '1' ? 'portfolio-disable-socials' : ''),
				($params['columns_desktop'] != '100%' ? 'columns-desktop-' . $params['columns_desktop'] : 'columns-desktop-' . $params['columns_100']),
				('columns-tablet-' . $params['columns_tablet']),
				('columns-mobile-' . $params['columns_mobile']),
				($params['product_separator'] == '1' ? 'item-separator' : ''),
				($params['arrows_navigation_visibility'] == 'hover' ? 'arrows-hover' : ''),
				($params['slider_scroll_init'] === '1' || $params['slider_loop'] == '1' ? 'carousel-scroll-init' : ''),
				((isset($params['image_size']) && $params['image_size'] == 'full' && empty($params['image_ratio_full']) || !in_array($params['image_size'], ['full', 'default'])) ? 'full-image' : 'aspect-ratio-custom'),
			);
			?>

			<div class="<?php echo esc_attr(implode(' ', $portfolio_classes)) ?>"
				 id="style-<?php echo esc_attr($style_uid); ?>"
				 data-style-uid="<?php echo esc_attr($style_uid); ?>"
				 data-portfolio-uid="<?php echo esc_attr($grid_uid) ?>"
				 data-columns-mobile="<?php echo esc_attr(str_replace("x", "", $params['columns_mobile'])); ?>"
				 data-columns-tablet="<?php echo esc_attr(str_replace("x", "", $params['columns_tablet'])); ?>"
				 data-columns-desktop="<?php echo $params['columns_desktop'] != '100%' ? esc_attr(str_replace("x", "", $params['columns_desktop'])) : esc_attr($params['columns_100']); ?>"
				 data-margin-mobile="<?php echo esc_attr($params['image_gaps_mobile']); ?>"
				 data-margin-tablet="<?php echo esc_attr($params['image_gaps_tablet']); ?>"
				 data-margin-desktop="<?php echo esc_attr($params['image_gaps']); ?>"
				 data-hover="<?php echo esc_attr($hover_effect); ?>"
				 data-dots="<?php echo esc_attr($params['show_dots_navigation']); ?>"
				 data-arrows="<?php echo esc_attr($params['show_arrows_navigation']); ?>"
				 data-loop="<?php echo esc_attr($params['slider_loop']); ?>"
				 data-sliding-animation="<?php echo esc_attr($params['sliding_animation']); ?>"
				 data-autoscroll-speed="<?php echo $params['autoscroll'] ? esc_attr($params['autoscroll_speed']) : '0'; ?>">

				<?php if ($params['source_type'] == 'cross_sell' && $params['show_cross_sell_title']) {
					$text_styled_class = implode(' ', array($params['cross_sell_title_style'], $params['cross_sell_title_weight'])); ?>
					<<?php echo $params['cross_sell_title_tag']; ?> class="cross-sell-title <?php echo $text_styled_class; ?>">
						<?php echo $params['cross_sell_title_text']; ?>
					</<?php echo $params['cross_sell_title_tag']; ?>>
				<?php } ?>

				<div class="portfolio-row-outer <?php if ($params['columns_desktop'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
					<?php if ($params['product_show_tabs'] == '1') : ?>
						<div class="portfolio-top-panel">
							<div class="portfolio-filter-tabs style-<?php echo $params['filters_tabs_style']; ?> alignment-<?php echo $params['product_tabs_alignment']; ?> <?php echo $params['filters_tabs_title_separator'] == '1' ? 'separator' : ''; ?>">
								<?php if ($params['filters_tabs_title_text'] != '') { ?>
									<div class="title-h4 portfolio-filter-tabs-title <?php echo $params['filters_tabs_title_style_preset'] == 'thin' ? 'light' : ''; ?>"><?php echo $params['filters_tabs_title_text']; ?></div>
								<?php }
								$filter_tabs = vc_param_group_parse_atts($params['filters_tabs_tabs']);
								if (!empty($filter_tabs)) { ?>
									<ul class="portfolio-filter-tabs-list">
										<?php foreach ($filter_tabs as $index => $item) {
											if (!empty($item['filters_tabs_tab_title'])) { ?>
												<li class="portfolio-filter-tabs-list-tab <?php echo $index == $active_tab - 1 ? 'active' : ''; ?>"
													data-num="<?php echo $index + 1; ?>">
													<?php echo $item['filters_tabs_tab_title'] ?>
												</li>
											<?php }
										} ?>
									</ul>
								<?php } ?>
							</div>
						</div>
					<?php endif; ?>
					<div class="portfolio-filter-tabs-content">
						<?php foreach ($product_loops as $index => $product_loop) { ?>
							<div class="extended-products-grid-carousel-item <?php echo $index == $active_tab - 1 ? 'active' : ''; ?>"
								 data-num="<?php echo $index + 1; ?>">
								<div class="portfolio-row clearfix">
									<div class="portfolio-set">
										<div class="extended-carousel-wrap">
											<div class="extended-carousel-item owl-carousel owl-theme owl-loaded">
												<div class="owl-stage-outer">
													<div class="owl-stage">
														<?php
														if ($product_loop->have_posts()) {
															remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
															remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
															remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
															remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
															remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
															remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
															remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
															remove_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_link', 15);
															remove_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_wishlist', 20);

															while ($product_loop->have_posts()) : $product_loop->the_post(); ?>
																<?php echo thegem_extended_products_render_item($params, $item_classes, $thegem_sizes, get_the_ID()); ?>
															<?php
															endwhile;
															wp_reset_postdata();
														} else { ?>
															<div class="portfolio-item not-found">
																<div class="found-wrap">
																	<div class="image-inner empty"></div>
																	<div class="msg">
																		<?php echo wp_kses($params['not_found_text'], 'post'); ?>
																	</div>
																</div>
															</div>
														<?php } ?>
													</div>
												</div>
											</div>
										</div>

									</div><!-- .portflio-set -->
								</div><!-- .row-->
								<?php if ($params['show_arrows_navigation']): ?>
									<div class="slider-prev-icon position-<?php echo $params['arrows_navigation_position']; ?>">
										<i class="default"></i>
									</div>
									<div class="slider-next-icon position-<?php echo $params['arrows_navigation_position']; ?>">
										<i class="default"></i>
									</div>
								<?php endif; ?>
							</div>
						<?php } ?>
					</div>
					<?php thegem_woocommerce_product_page_ajax_notification($params); ?>
				</div><!-- .full-width -->
			</div><!-- .portfolio-->
		</div><!-- .portfolio-preloader-wrapper-->
	<?php

	else: ?>
		<?php if (!$is_related_upsell && !$recently_viewed_only) { ?>
			<div class="bordered-box centered-box styled-subtitle">
				<?php echo esc_html__('Please select products in "Products" section', 'thegem') ?>
			</div>
		<?php } ?>
	<?php
	endif;
	$post = $portfolio_posttemp;

	if (thegem_is_plugin_active('js_composer/js_composer.php')) {
		global $vc_manager;
		if ($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode() == 'page_editable') { ?>
			<script type="text/javascript">
				(function ($) {
					setTimeout(function () {
						$('#style-<?php echo esc_attr($style_uid); ?>.extended-products-grid-carousel').initProductGalleries();
					}, 1000);
				})(jQuery);
			</script>
		<?php }
	}
}

// Print Products Grid Categories
function thegem_products_grid_categories($params) {
	global $post;
	$portfolio_posttemp = $post;
	$grid_uid = $params['portfolio_uid'];
	$style_uid = substr(md5(rand()), 0, 7);
	$params['style_uid'] = $style_uid;

	wp_enqueue_style('thegem-products-categories-styles');
	wp_enqueue_script('thegem-products-categories-scripts');

	$categories = ['0'];
	if ($params['source'] == 'subcategories') {
		if (is_tax( 'product_cat' )) {
			$categories = get_terms('product_cat', array('fields' => 'slugs', 'parent' => get_queried_object()->term_id ));
		}
	} else {
		if (!empty($params['content_products_cat'])) {
			$categories = explode(", ", $params['content_products_cat']);
		}
	}

	$cat_args = [
		'taxonomy' => 'product_cat',
		'hide_empty' => $params['hide_empty'] == '1' ? true : false,
		'orderby' => $params['orderby'],
		'order' => $params['order'],
	];

	if ( 'order' === $params['orderby'] ) {
		$cat_args['orderby'] = 'meta_value_num';
		$cat_args['meta_key'] = 'order';
	}

	$sorted_categories = get_terms($cat_args);

	if (in_array('0', $categories)) {
		$categories = get_terms('product_cat', array('fields' => 'slugs'));
	}

	if ($params['layout_type'] === 'creative' && ($params['columns_desktop'] == '1x' || $params['columns_desktop'] == '2x')) {
		$params['layout_type'] = 'grid';
	}

	if ($params['layout_type'] === 'carousel') {
		wp_enqueue_style('owl');
		wp_enqueue_script('owl');
	}

	if ($params['loading_animation'] === '1') {
		wp_enqueue_style('thegem-animations');
		wp_enqueue_script('thegem-items-animations');
		wp_enqueue_script('thegem-scroll-monitor');
	}

	if ($params['slider_scroll_init'] === '1') {
		wp_enqueue_script('thegem-scroll-monitor');
	}

	if (!empty($categories)) :
		echo thegem_products_grid_categories_render_styles($params, $params['layout_type'] == 'carousel');

		$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params);
		$item_classes = get_thegem_portfolio_render_item_classes($params);
		if ($params['columns_desktop'] == '100%') {
			echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="preloader-spin"></div></div>');
		} else if (($params['layout_type'] == 'carousel' && $params['disable_preloader'] == '') || $params['skeleton_loader'] == '1') { ?>
			<div id="style-preloader-<?php echo esc_attr($style_uid); ?>" class="preloader <?php echo $params['layout_type'] == 'carousel' ? 'skeleton-carousel' : 'save-space'; ?>">
				<div class="skeleton">
					<div class="skeleton-posts row categories-row caption-position-<?php echo $params['caption_position']; ?> aspect-ratio-<?php echo $params['image_aspect_ratio']; ?>">
						<?php for ($x = 0; $x < sizeof($categories); $x++) { ?>
							<div class="products-category-item <?php echo implode(" ", $item_classes); ?>"></div>
							<?php
							if (!empty($params['items_count']) && $x == $params['items_count']) {
								break;
							}
						} ?>
					</div>
				</div>
			</div>
		<?php } ?>

		<div class="portfolio-preloader-wrapper">

			<?php

			$categories_classes = array(
				'products-categories-widget',
				'layout-type-' . $params['layout_type'],
				'portfolio-preset-' . $params['product_grid_categories_skin'],
				'caption-position-' . $params['caption_position'],
				'counts-visible-' . $params['product_counts'],
				($params['image_size'] == 'default' ? 'aspect-ratio-' . $params['image_aspect_ratio'] : ''),
				($params['caption_position'] == 'image' ? 'caption-container-preset-' . $params['caption_container_preset'] : 'caption-container-preset-' . $params['caption_container_preset_below']),
				($params['caption_position'] == 'image' ? 'caption-container-preset-color-' . $params['caption_container_preset_color'] : ''),
				($params['caption_position'] == 'image' ? 'caption-container-vertical-position-' . $params['caption_container_vertical_position'] : ''),
				($params['layout_type'] == 'carousel' ? 'extended-carousel-grid arrows-position-' . $params['arrows_navigation_position'] : ''),
				($params['loading_animation'] == '1' ? 'loading-animation' : ''),
				($params['loading_animation'] == '1' && $params['animation_effect'] ? 'item-animation-' . $params['animation_effect'] : ''),
				($params['image_gaps'] == 0 ? 'no-gaps' : ''),
				($params['shadowed_container'] == '1' ? 'shadowed-container' : ''),
				($params['columns_desktop'] == '100%' ? 'fullwidth-columns fullwidth-columns-desktop-' . $params['columns_100'] : ''),
				($params['columns_desktop'] != '100%' ? 'columns-desktop-' . str_replace("x", "", $params['columns_desktop']) : 'columns-desktop-' . $params['columns_100']),
				($params['columns_desktop'] == '100%' && ($params['image_gaps'] < 24 || $params['product_separator'] == '1') ? 'prevent-arrows-outside' : ''),
				('columns-tablet-' . str_replace("x", "", $params['columns_tablet'])),
				('columns-mobile-' . str_replace("x", "", $params['columns_mobile'])),
				($params['product_separator'] == '1' ? 'item-separator' : ''),
				($params['arrows_navigation_visibility'] == 'hover' ? 'arrows-hover' : ''),
				($params['slider_scroll_init'] === '1' || $params['slider_loop'] == '1' ? 'carousel-scroll-init' : ''),
				($params['layout_type'] === 'creative' && $params['scheme_apply_mobiles'] !== '1' ? 'creative-disable-mobile' : ''),
				($params['layout_type'] === 'creative' && $params['scheme_apply_tablets'] !== '1' ? 'creative-disable-tablet' : ''),
				($params['image_size'] == 'full' && empty($params['image_ratio_full']['size']) ? 'full-image' : 'aspect-ratio-custom'),
			);
			?>

			<div class="<?php echo esc_attr(implode(' ', $categories_classes)) ?>"
				 id="style-<?php echo esc_attr($style_uid); ?>"
				 data-style-uid="<?php echo esc_attr($style_uid); ?>"
				 data-portfolio-uid="<?php echo esc_attr($grid_uid) ?>"
				 data-columns-mobile="<?php echo esc_attr(str_replace("x", "", $params['columns_mobile'])) ?>"
				 data-columns-tablet="<?php echo esc_attr(str_replace("x", "", $params['columns_tablet'])) ?>"
				 data-columns-desktop="<?php echo $params['columns_desktop'] != '100%' ? esc_attr(str_replace("x", "", $params['columns_desktop'])) : esc_attr($params['columns_100']) ?>"
				 data-margin-mobile="<?php echo esc_attr($params['image_gaps_mobile']) ?>"
				 data-margin-tablet="<?php echo esc_attr($params['image_gaps_tablet']) ?>"
				 data-margin-desktop="<?php echo esc_attr($params['image_gaps']) ?>"
				 data-dots='<?php echo $params['show_dots_navigation'] == '1' ? '1' : '0' ?>'
				 data-arrows='<?php echo $params['show_arrows_navigation'] == '1' ? '1' : '0' ?>'
				 data-loop='<?php echo $params['slider_loop'] == '1' ? '1' : '0' ?>'
				 data-sliding-animation='<?php echo esc_attr($params['sliding_animation']) ?>'
				 data-autoscroll-speed='<?php echo $params['autoscroll'] == '1' ? esc_attr($params['autoscroll_speed']) : '0' ?>'>

				<div class="categories-row-outer <?php if ($params['columns_desktop'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
					<div class="categories-row clearfix">
						<div class="categories-set">
							<?php
							if ($params['layout_type'] == 'creative') {
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

								$columns = $params['columns_desktop'] != '100%' ? str_replace("x", "", $params['columns_desktop']) : $params['columns_100'];
								$items_sizes = $creative_categories_schemes_list[$columns][$params['layout_scheme_' . $columns . 'x']];
								$items_count = $items_sizes['count'];
							} else if ($params['layout_type'] == 'carousel') { ?>
							<div class="extended-carousel-wrap">
								<div class="extended-carousel-item owl-carousel owl-theme owl-loaded">
									<div class="owl-stage-outer">
										<div class="owl-stage">
											<?php }

											$i = 0;
											foreach ($sorted_categories as $category) {
												if (in_array($category->slug, $categories)) {

													$thegem_highlight_type = 'disabled';
													if ($params['layout_type'] == 'creative') {
														$item_num = $i % $items_count;
														if (isset($items_sizes[$item_num])) {
															$thegem_highlight_type = $items_sizes[$item_num];
														}
													}

													thegem_category_render_item($params, $thegem_sizes, $category, $thegem_highlight_type);

													$i++;

													if (!empty($params['items_count']) && $i == $params['items_count']) {
														break;
													}
												}
											}
											if ($params['layout_type'] == 'creative') {
												thegem_category_render_item($params, $thegem_sizes);
											} else if ($params['layout_type'] == 'carousel') { ?>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
						</div><!-- .portflio-set -->
					</div><!-- .row-->
					<?php if ($params['show_arrows_navigation'] == '1'): ?>
						<div class="slider-prev-icon position-<?php echo $params['arrows_navigation_position']; ?>">
							<i class="default"></i>
						</div>
						<div class="slider-next-icon position-<?php echo $params['arrows_navigation_position']; ?>">
							<i class="default"></i>
						</div>
					<?php endif; ?>
				</div><!-- .full-width -->
			</div><!-- .portfolio-->
		</div><!-- .portfolio-preloader-wrapper-->
	<?php

	else: ?>
		<div class="bordered-box centered-box styled-subtitle">
			<?php echo esc_html__('Please select product categories in "Categories" section', 'thegem') ?>
		</div>
	<?php endif;
	$post = $portfolio_posttemp;

	if (thegem_is_plugin_active('js_composer/js_composer.php')) {
		global $vc_manager;
		if ($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode() == 'page_editable') { ?>
			<script type="text/javascript">
				(function ($) {
					setTimeout(function () {
						$('#style-<?php echo esc_attr($style_uid); ?>.products-categories-widget').initCategoriesGalleries();
						$('#style-<?php echo esc_attr($style_uid); ?>.products-categories-widget.layout-type-carousel').updateCategoriesGalleries();
					}, 1000);
				})(jQuery);
			</script>
		<?php }
	}
}

function thegem_category_render_item($params, $thegem_sizes, $category = null, $thegem_highlight_type = 'disabled') {

	$thegem_classes = array('products-category-item');

	if (!$category) {
		$thegem_classes[] = 'size-item';

		$category = (object)array(
			'term_id' => ' ',
			'name' => 'Size',
			'count' => '0',
		);
	}

	if ($thegem_highlight_type != 'disabled') {
		$thegem_classes[] = 'double-item-' . $thegem_highlight_type;

		$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params, $thegem_highlight_type);
	}

	if ($params['loading_animation'] === '1') {
		$thegem_classes[] = 'item-animations-not-inited';
	}

	if ($params['layout_type'] == 'carousel') {
		$thegem_classes[] = 'owl-item';
	}

	include(locate_template(array('gem-templates/products-categories/content-product-category-item.php')));

}

function thegem_products_grid_categories_render_styles($params, $carousel = false) {
	$wrapper = '.products-categories-widget#style-' . $params['style_uid'];
	$wrapper_skeleton = '.preloader#style-preloader-' . $params['style_uid'];

	$image_gaps = $params['image_gaps'];
	$image_gaps_tablet = $params['image_gaps_tablet'];
	$image_gaps_mobile = $params['image_gaps_mobile'];

	$style = "<style>";

	if ($carousel) {

		$style .= $wrapper . ".item-separator .products-category-item { padding: calc(" . $image_gaps_mobile . "px/2) !important; }".
			$wrapper . ":not(.item-separator) .fullwidth-block { padding: 0 calc(" . $image_gaps_mobile . "px) !important; }".
			$wrapper . " .owl-carousel .owl-stage-outer { padding: calc(" . $image_gaps_mobile . "px/2) !important; margin: calc(-" . $image_gaps_mobile . "px/2) !important; }".
			$wrapper . ".layout-type-carousel:not(.inited) .owl-stage { margin: 0 calc(-" . $image_gaps_mobile . "px/2); }" .
			$wrapper . ".layout-type-carousel:not(.inited) .owl-stage .products-category-item { padding-left: calc(" . $image_gaps_mobile . "px/2); padding-right: calc(" . $image_gaps_mobile . "px/2); }".
			$wrapper_skeleton . " .products-category-item { padding: calc(" . $image_gaps_mobile . "px/2); }" .
			$wrapper_skeleton . " .skeleton-posts.portfolio-row { margin: calc(-" . $image_gaps_mobile . "px/2); }" ;

		$style .= "@media (min-width: 768px) { " . $wrapper . ".item-separator .products-category-item { padding: calc(" . $image_gaps_tablet . "px/2) !important; }".
			$wrapper . ":not(.item-separator) .fullwidth-block { padding: 0 calc(" . $image_gaps_tablet . "px) !important; }".
			$wrapper . " .owl-carousel .owl-stage-outer { padding: calc(" . $image_gaps_tablet . "px/2) !important; margin: calc(-" . $image_gaps_tablet . "px/2) !important; }".
			$wrapper . ".layout-type-carousel:not(.inited) .owl-stage { margin: 0 calc(-" . $image_gaps_tablet . "px/2); }" .
			$wrapper . ".layout-type-carousel:not(.inited) .owl-stage .products-category-item { padding-left: calc(" . $image_gaps_tablet . "px/2); padding-right: calc(" . $image_gaps_mobile . "px/2); }".
			$wrapper_skeleton . " .products-category-item { padding: calc(" . $image_gaps_tablet . "px/2); }" .
			$wrapper_skeleton . " .skeleton-posts.portfolio-row { margin: calc(-" . $image_gaps_tablet . "px/2); } }";

		$style .= "@media (min-width: 992px) { " . $wrapper . ".item-separator .products-category-item { padding: calc(" . $image_gaps . "px/2) !important; }".
			$wrapper . ":not(.item-separator) .fullwidth-block { padding: 0 calc(" . $image_gaps . "px) !important; }".
			$wrapper . " .owl-carousel .owl-stage-outer { padding: calc(" . $image_gaps . "px/2) !important; margin: calc(-" . $image_gaps . "px/2) !important; }".
			$wrapper . ".layout-type-carousel:not(.inited) .owl-stage { margin: 0 calc(-" . $image_gaps . "px/2); }" .
			$wrapper . ".layout-type-carousel:not(.inited) .owl-stage .products-category-item { padding-left: calc(" . $image_gaps . "px/2); padding-right: calc(" . $image_gaps_mobile . "px/2); }".
			$wrapper_skeleton . " .products-category-item { padding: calc(" . $image_gaps . "px/2); }" .
			$wrapper_skeleton . " .skeleton-posts.portfolio-row { margin: calc(-" . $image_gaps . "px/2); } }";

	} else {

		$style .= $wrapper . " .products-category-item," .
			$wrapper_skeleton . " .skeleton-posts .products-category-item { padding: calc(" . $image_gaps_mobile . "px/2); }" .
			$wrapper . ":not(.item-separator) .categories-row," .
			$wrapper_skeleton . " .skeleton-posts.categories-row { margin: calc(-" . $image_gaps_mobile . "px/2); }" .
			$wrapper . ".item-separator .categories-row { margin: 0 calc(-" . $image_gaps_mobile . "px/2); }" .
			$wrapper . ".extended-carousel-grid .categories-row { margin: 0 calc(-" . $image_gaps_mobile . "px/2); }" .
			$wrapper . ".extended-carousel-grid:not(.item-separator) .owl-stage-outer { margin: calc(-" . $image_gaps_mobile . "px/2) 0; }" .

			$wrapper . ".fullwidth-columns:not(.item-separator) .categories-row { margin: calc(-" . $image_gaps_mobile . "px/2) 0; }" .
			$wrapper . ".fullwidth-columns.item-separator .categories-row { margin: 0; }" .
			$wrapper . " .fullwidth-block .categories-row { padding-left: calc(" . $image_gaps_mobile . "px/2); padding-right: calc(" . $image_gaps_mobile . "px/2); }";

		$style .= "@media (min-width: 768px) { " . $wrapper . " .products-category-item," .
			$wrapper_skeleton . " .skeleton-posts .products-category-item { padding: calc(" . $image_gaps_tablet . "px/2); }" .
			$wrapper . ":not(.item-separator) .categories-row," .
			$wrapper_skeleton . " .skeleton-posts.categories-row { margin: calc(-" . $image_gaps_tablet . "px/2); }" .
			$wrapper . ".item-separator .categories-row { margin: 0 calc(-" . $image_gaps_tablet . "px/2); }" .
			$wrapper . ".extended-carousel-grid .categories-row { margin: 0 calc(-" . $image_gaps_tablet . "px/2); }" .
			$wrapper . ".extended-carousel-grid:not(.item-separator) .owl-stage-outer { margin: calc(-" . $image_gaps_tablet . "px/2) 0; }" .

			$wrapper . ".fullwidth-columns:not(.item-separator) .categories-row { margin: calc(-" . $image_gaps_tablet . "px/2) 0; }" .
			$wrapper . ".fullwidth-columns.item-separator .categories-row { margin: 0; }" .
			$wrapper . " .fullwidth-block .categories-row { padding-left: calc(" . $image_gaps_tablet . "px/2); padding-right: calc(" . $image_gaps_tablet . "px/2); } }";

		$style .= "@media (min-width: 992px) { " . $wrapper . " .products-category-item," .
			$wrapper_skeleton . " .skeleton-posts .products-category-item { padding: calc(" . $image_gaps . "px/2); }" .
			$wrapper . ":not(.item-separator) .categories-row," .
			$wrapper_skeleton . " .skeleton-posts.categories-row { margin: calc(-" . $image_gaps . "px/2); }" .
			$wrapper . ".item-separator .categories-row { margin: 0 calc(-" . $image_gaps . "px/2); }" .
			$wrapper . ".extended-carousel-grid .categories-row { margin: 0 calc(-" . $image_gaps . "px/2); }" .
			$wrapper . ".extended-carousel-grid:not(.item-separator) .owl-stage-outer { margin: calc(-" . $image_gaps . "px/2) 0; }" .

			$wrapper . ".fullwidth-columns:not(.item-separator) .categories-row { margin: calc(-" . $image_gaps . "px/2) 0; }" .
			$wrapper . ".fullwidth-columns.item-separator .categories-row { margin: 0; }" .
			$wrapper . " .fullwidth-block .categories-row { padding-left: calc(" . $image_gaps . "px/2); padding-right: calc(" . $image_gaps . "px/2); } } ";

	}

	if (isset($params['product_separator']) && $params['product_separator'] === '1') {
		$style .= $wrapper . ".item-separator .products-category-item:before," .
			$wrapper . ".item-separator .products-category-item:after," .
			$wrapper . ".item-separator .products-category-item .item-separator-box:before," .
			$wrapper . ".item-separator .products-category-item .item-separator-box:after { 
			border-width: " . $params['product_separator_width'] . "px; 
			border-color: " . $params['product_separator_color'] . "; }";

		$style .= $wrapper . ".item-separator .products-category-item .item-separator-box:before," .
			$wrapper . ".item-separator .products-category-item .item-separator-box:after { 
			width: calc(100% + " . $params['product_separator_width'] . "px);
			left: calc(-" . $params['product_separator_width'] . "px/2);};";

		$style .= $wrapper . ".item-separator .products-category-item:before," .
			$wrapper . ".item-separator .products-category-item:after { 
			height: calc(100% + " . $params['product_separator_width'] . "px);
			top: calc(-" . $params['product_separator_width'] . "px/2);}";

		if ($carousel) {
			$style .= $wrapper . ".item-separator .owl-carousel .owl-stage-outer { 
			padding: calc(" . $params['product_separator_width'] . "px/2);
			width: calc(100% + " . $params['product_separator_width'] . "px);
			margin-left: calc(-" . $params['product_separator_width'] . "px/2);}";
		}

		if (intval($params['product_separator_width']) % 2 !== 0) {
			$floor = floor(intval($params['product_separator_width']) / 2);
			$ceil = ceil(intval($params['product_separator_width']) / 2);

			$style .= $wrapper . ".item-separator .products-category-item:before { 
				transform: translateX(-" . $floor . "px) !important;
				top: -" . $floor . "px !important;}";

			$style .= $wrapper . ".item-separator .products-category-item:after { 
				transform: translateX(" . $ceil . "px) !important;
				top: -" . $floor . "px !important;}";

			$style .= $wrapper . ".item-separator .products-category-item .item-separator-box:before { 
				transform: translateY(-" . $floor . "px) !important;
				left: -" . $floor . "px !important;}";

			$style .= $wrapper . " .products-category-item .item-separator-box:after { 
				transform: translateY(" . $ceil . "px) !important;
				left: -" . $floor . "px !important;}";

			if ($carousel) {
				$style .= $wrapper . ".item-separator .owl-carousel .owl-stage-outer {
				padding: " . $ceil . "px !important;
				width: calc(100% + " . $ceil . "px * 2) !important;
				margin-left: calc(-" . $ceil . "px) !important; }";
			}
		}
	}

	if (isset($params['image_border_width']) && $params['image_border_width'] != '') {
		if (isset($params['border_caption_container']) && $params['border_caption_container'] === '1') {
			$style .= $wrapper . " .products-category-item .wrap .category-thumbnail { border-width: " . $params['image_border_width'] . "px; border-style: solid; border-bottom: 0; }";
			$style .= $wrapper . " .products-category-item .wrap .category-overlay { border-width: " . $params['image_border_width'] . "px; border-style: solid; border-top: 0; }";
		} else {
			$style .= $wrapper . " .products-category-item .wrap .category-thumbnail { border-width: " . $params['image_border_width'] . "px; border-style: solid; }";
		}

		if (isset($params['image_border_color']) && $params['image_border_color'] != '') {
			$style .= $wrapper . " .products-category-item .wrap .category-thumbnail, " .
				$wrapper . " .products-category-item .wrap .category-overlay { border-color: " . $params['image_border_color'] . "; }";
		}

		if (isset($params['image_border_color_hover']) && $params['image_border_color_hover'] != '') {
			$style .= $wrapper . ":hover .products-category-item .wrap .category-thumbnail, " .
				$wrapper . ":hover .products-category-item .wrap .category-overlay { border-color: " . $params['image_border_color_hover'] . "; }";
		}
	}

	if (isset($params['image_border_radius']) && $params['image_border_radius'] != '') {
		if (isset($params['border_caption_container']) && $params['border_caption_container'] === '1') {
			$style .= $wrapper . " .products-category-item .wrap { border-radius: " . $params['image_border_radius'] . "px; }";
			$style .= $wrapper . " .products-category-item .wrap .category-thumbnail {
			 border-top-left-radius: " . $params['image_border_radius'] . "px; 
			 border-top-right-radius: " . $params['image_border_radius'] . "px; }";
			$style .= $wrapper . " .products-category-item .wrap .category-overlay {
			 border-bottom-left-radius: " . $params['image_border_radius'] . "px; 
			 border-bottom-right-radius: " . $params['image_border_radius'] . "px; }";
		} else {
			$style .= $wrapper . ".caption-position-image .products-category-item .wrap, " .
				$wrapper . ".caption-position-below .products-category-item .wrap .category-overlay { border-radius: " . $params['image_border_radius'] . "px }";
			$style .= $wrapper . ".caption-position-below .products-category-item .wrap { border-top-left-radius: " . $params['image_border_radius'] . "px; border-top-right-radius: " . $params['image_border_radius'] . "px; }";
		}
	}

	if ( !empty( $params['enable_shadow'] ) ) {
		$shadow_position = '';
		if ( $params['shadow_position'] == 'inset' ) {
			$shadow_position = 'inset';
		}
		$shadow_horizontal = $params['shadow_horizontal'] ?: 0;
		$shadow_vertical   = $params['shadow_vertical'] ?: 0;
		$shadow_blur       = $params['shadow_blur'] ?: 0;
		$shadow_spread     = $params['shadow_spread'] ?: 0;
		$shadow_color      = $params['shadow_color'] ?: '#000';

		$style .= $wrapper . ".caption-position-image .products-category-item .wrap { box-shadow: " . $shadow_position . " " . $shadow_horizontal . "px " . $shadow_vertical . "px " . $shadow_blur . "px " . $shadow_spread . "px " . $shadow_color . "; }";
		if ( !empty( $params['shadowed_container'] ) ) {
			$style .= $wrapper . ".caption-position-below.shadowed-container .products-category-item .wrap { box-shadow: " . $shadow_position . " " . $shadow_horizontal . "px " . $shadow_vertical . "px " . $shadow_blur . "px " . $shadow_spread . "px " . $shadow_color . "; }";
		} else {
			$style .= $wrapper . ".caption-position-below:not(.shadowed-container) .products-category-item .category-thumbnail { box-shadow: " . $shadow_position . " " . $shadow_horizontal . "px " . $shadow_vertical . "px " . $shadow_blur . "px " . $shadow_spread . "px " . $shadow_color . " !important; }";
		}
	}

	if (isset($params['image_overlay_normal']) && $params['image_overlay_normal'] != '') {
		$style .= $wrapper . " .products-category-item .category-thumbnail:after { background: " . $params['image_overlay_normal'] . " !important; }";
	}

	if (isset($params['image_overlay_hover']) && $params['image_overlay_hover'] != '') {
		$style .= $wrapper . " .products-category-item:hover .category-thumbnail:after { background: " . $params['image_overlay_hover'] . " !important; }";
	}

	if (isset($params['caption_container_vertical_position']) && $params['caption_container_vertical_position'] != '') {
		if ($params['caption_container_vertical_position'] == 'top') {
			$align = 'flex-start';
		} else if ($params['caption_container_vertical_position'] == 'bottom') {
			$align = 'flex-end';
		} else {
			$align = 'center';
		}
		$style .= $wrapper . ".caption-position-image .products-category-item .wrap .category-overlay { align-items: " . $align . " }";
	}

	if (isset($params['caption_container_alignment']) && $params['caption_container_alignment'] != '' && $params['caption_position'] == 'image') {
		if ($params['caption_container_alignment'] == 'left') {
			$align = 'flex-start';
		} else if ($params['caption_container_alignment'] == 'right') {
			$align = 'flex-end';
		} else {
			$align = 'center';
		}
		$style .= $wrapper . " .products-category-item .wrap .category-overlay .category-overlay-inner-inside { align-items: " . $align . "; text-align: " . $params['caption_container_alignment'] . " }";
	}

	if (isset($params['caption_container_background_color_normal']) && $params['caption_container_background_color_normal'] != '' && $params['caption_position'] == 'image') {
		$style .= $wrapper . ".caption-position-image.caption-container-preset-solid .products-category-item .wrap .category-overlay .category-overlay-inner { background-color: " . $params['caption_container_background_color_normal'] . " }";
	}

	if (isset($params['caption_container_background_color_hover']) && $params['caption_container_background_color_hover'] != '' && $params['caption_position'] == 'image') {
		$style .= $wrapper . ".caption-position-image.caption-container-preset-solid .products-category-item:hover .wrap .category-overlay .category-overlay-inner { background-color: " . $params['caption_container_background_color_hover'] . " }";
	}

	if (isset($params['categories_title_color_normal']) && $params['categories_title_color_normal'] != '') {
		$style .= $wrapper . " .products-category-item .wrap .category-overlay .category-title { color: " . $params['categories_title_color_normal'] . " }";
	}

	if (isset($params['categories_title_color_hover']) && $params['categories_title_color_hover'] != '') {
		$style .= $wrapper . " .products-category-item:hover .wrap .category-overlay .category-title { color: " . $params['categories_title_color_hover'] . " }";
	}

	if (isset($params['categories_counts_color_normal']) && $params['categories_counts_color_normal'] != '') {
		$style .= $wrapper . " .products-category-item .wrap .category-overlay .category-count { color: " . $params['categories_counts_color_normal'] . " }";
	}

	if (isset($params['categories_counts_color_hover']) && $params['categories_counts_color_hover'] != '') {
		$style .= $wrapper . " .products-category-item:hover .wrap .category-overlay .category-count { color: " . $params['categories_counts_color_hover'] . " }";
	}

	if (isset($params['caption_separator_color_normal']) && $params['caption_separator_color_normal'] != '') {
		$style .= $wrapper . " .products-category-item .wrap .category-overlay .category-overlay-separator { background-color: " . $params['caption_separator_color_normal'] . " }";
	}

	if (isset($params['caption_separator_color_hover']) && $params['caption_separator_color_hover'] != '') {
		$style .= $wrapper . " .products-category-item:hover .wrap .category-overlay .category-overlay-separator { background-color: " . $params['caption_separator_color_hover'] . " }";
	}

	if (isset($params['caption_container_alignment_below']) && $params['caption_container_alignment_below'] != '' && $params['caption_position'] == 'below') {
		if ($params['caption_container_alignment_below'] == 'left') {
			$align = 'flex-start';
		} else if ($params['caption_container_alignment_below'] == 'right') {
			$align = 'flex-end';
		} else {
			$align = 'center';
		}
		$style .= $wrapper . " .products-category-item .wrap .category-overlay .category-overlay-inner-inside { align-items: " . $align . "; text-align: " . $params['caption_container_alignment_below'] . " }";
	}

	if (isset($params['caption_container_background_below_normal']) && $params['caption_container_background_below_normal'] != '' && $params['caption_position'] == 'below') {
		$style .= $wrapper . " .products-category-item .wrap .category-overlay .category-overlay-inner { background-color: " . $params['caption_container_background_below_normal'] . " }";
	}

	if (isset($params['caption_container_background_below_hover']) && $params['caption_container_background_below_hover'] != '' && $params['caption_position'] == 'below') {
		$style .= $wrapper . " .products-category-item:hover .wrap .category-overlay .category-overlay-inner { background-color: " . $params['caption_container_background_below_hover'] . " }";
	}

	if (isset($params['navigation_arrows_icon_color_normal']) && $params['navigation_arrows_icon_color_normal'] != '') {
		$style .= $wrapper . " .product-gallery-slider .owl-nav .owl-prev div, " .
			$wrapper . " .product-gallery-slider .owl-nav .owl-next div { color: " . $params['navigation_arrows_icon_color_normal'] . " }";
	}

	if (isset($params['navigation_arrows_icon_color_hover']) && $params['navigation_arrows_icon_color_hover'] != '') {
		$style .= $wrapper . " .product-gallery-slider .owl-nav .owl-prev:hover div, " .
			$wrapper . " .product-gallery-slider .owl-nav .owl-next:hover div { color: " . $params['navigation_arrows_icon_color_hover'] . " }";
	}

	if (isset($params['navigation_arrows_border_width']) && $params['navigation_arrows_border_width'] != '') {
		$style .= $wrapper . " .product-gallery-slider .owl-nav .owl-prev, " .
			$wrapper . " .product-gallery-slider .owl-nav .owl-next { border-width: " . $params['navigation_arrows_border_width'] . "px }";
	}

	if (isset($params['navigation_arrows_border_radius']) && $params['navigation_arrows_border_radius'] != '') {
		$style .= $wrapper . " .product-gallery-slider .owl-nav .owl-prev, " .
			$wrapper . " .product-gallery-slider .owl-nav .owl-next { border-radius: " . $params['navigation_arrows_border_radius'] . "px }";
	}

	if (isset($params['navigation_arrows_border_color_normal']) && $params['navigation_arrows_border_color_normal'] != '') {
		$style .= $wrapper . " .product-gallery-slider .owl-nav .owl-prev, " .
			$wrapper . " .product-gallery-slider .owl-nav .owl-next { border-color: " . $params['navigation_arrows_border_color_normal'] . " }";
	}

	if (isset($params['navigation_arrows_border_color_hover']) && $params['navigation_arrows_border_color_hover'] != '') {
		$style .= $wrapper . " .product-gallery-slider .owl-nav .owl-prev:hover, " .
			$wrapper . " .product-gallery-slider .owl-nav .owl-next:hover { border-color: " . $params['navigation_arrows_border_color_hover'] . " }";
	}

	if (isset($params['navigation_arrows_background_color_normal']) && $params['navigation_arrows_background_color_normal'] != '') {
		$style .= $wrapper . " .product-gallery-slider .owl-nav .owl-prev div.position-on, " .
			$wrapper . " .product-gallery-slider .owl-nav .owl-next div.position-on { background-color: " . $params['navigation_arrows_background_color_normal'] . " }";
	}

	if (isset($params['navigation_arrows_background_color_hover']) && $params['navigation_arrows_background_color_hover'] != '') {
		$style .= $wrapper . " .product-gallery-slider .owl-nav .owl-prev:hover div.position-on, " .
			$wrapper . " .product-gallery-slider .owl-nav .owl-next:hover div.position-on { background-color: " . $params['navigation_arrows_background_color_hover'] . " }";
	}

	if (isset($params['navigation_dots_spacing']) && $params['navigation_dots_spacing'] != '') {
		$style .= $wrapper . " .owl-dots { margin-top: " . $params['navigation_dots_spacing'] . "px }";
	}

	if (isset($params['navigation_dots_border_width']) && $params['navigation_dots_border_width'] != '') {
		$style .= $wrapper . " .owl-dots .owl-dot span { border-width: " . $params['navigation_dots_border_width'] . "px }";
	}

	if (isset($params['navigation_dots_border_color_normal']) && $params['navigation_dots_border_color_normal'] != '') {
		$style .= $wrapper . " .owl-dots .owl-dot span { border-color: " . $params['navigation_dots_border_color_normal'] . " }";
	}

	if (isset($params['navigation_dots_border_color_active']) && $params['navigation_dots_border_color_active'] != '') {
		$style .= $wrapper . " .owl-dots .owl-dot.active span { border-color: " . $params['navigation_dots_border_color_active'] . " }";
	}

	if (isset($params['navigation_dots_background_color_normal']) && $params['navigation_dots_background_color_normal'] != '') {
		$style .= $wrapper . " .owl-dots .owl-dot span { background-color: " . $params['navigation_dots_background_color_normal'] . " }";
	}

	if (isset($params['navigation_dots_background_color_active']) && $params['navigation_dots_background_color_active'] != '') {
		$style .= $wrapper . " .owl-dots .owl-dot.active span { background-color: " . $params['navigation_dots_background_color_active'] . " }";
	}

	if (isset($params['image_size']) && $params['image_size'] == 'full' && !empty($params['image_ratio_full'])) {
		$style .= $wrapper . " .products-category-item:not(.double-item) .category-thumbnail { aspect-ratio: " . $params['image_ratio_full'] . " !important; height: auto; padding: 0; }";
	}

	if (isset($params['image_size']) && $params['image_size'] == 'default' && $params['image_aspect_ratio'] == 'custom' && !empty($params['image_ratio_custom'])) {
		$style .= $wrapper . " .products-category-item:not(.double-item) .category-thumbnail { aspect-ratio: " . $params['image_ratio_custom'] . " !important; height: auto; padding: 0; }";
	}

	$style .= "</style>";

	return $style;

}