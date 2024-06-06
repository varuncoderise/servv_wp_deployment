<?php
if (!function_exists('thegem_extended_blog_render_item')) {
	function thegem_extended_blog_render_item($params, $item_classes, $thegem_sizes = null, $post_id = false, $thegem_highlight_type_creative = null) {
		$slugs = array();

		if ($post_id) {
			if (!isset($params['query_type']) || $params['query_type'] == 'post') {
				$slugs = wp_get_object_terms($post_id, array('category'), array('fields' => 'slugs'));
			}

			$thegem_post_data = thegem_get_sanitize_page_title_data(get_the_ID());
			if (get_post_type() == 'thegem_pf_item') {
				$post_item_data = thegem_get_sanitize_pf_item_data(get_the_ID());
				$post_format = $post_item_data['grid_appearance_type'];
				foreach ($post_item_data as $key => $value) {
					if (strpos($key, 'grid_appearance_') !== false) {
						$post_item_data[str_replace('grid_appearance_','', $key)] = $value;
						unset($post_item_data[$key]);
					}
				}
			} else if (get_post_type() == 'post' || get_post_type() == 'page' || get_post_type() == 'product') {
				$post_item_data = thegem_get_sanitize_post_data(get_the_ID());
				$post_format = get_post_format(get_the_ID());
			} else {
				$post_item_data = thegem_get_sanitize_cpt_item_data(get_the_ID());
				$post_format = get_post_format(get_the_ID());
			}

			if ($thegem_highlight_type_creative) {
				$thegem_highlight_type = $thegem_highlight_type_creative;
			} else if (($params['ignore_highlights'] != '1') && !empty($post_item_data['highlight'])) {
				if (!empty($post_item_data['highlight_type'])) {
					$thegem_highlight_type = $post_item_data['highlight_type'];
				} else {
					$thegem_highlight_type = 'squared';
				}
			} else {
				$thegem_highlight_type = 'disabled';
			}

			if ($params['version'] == 'list' && $post_format == 'quote') {
				$post_format = '';
			}
		} else {
			$portfolio_item_size = true;
			$thegem_post_data = array();
			$post_item_data = array();
			$thegem_highlight_type = 'disabled';
		}

		$thegem_classes = array('portfolio-item');
		$thegem_classes = array_merge($thegem_classes, $slugs);

		$thegem_image_classes = array('image');
		$thegem_caption_classes = array('caption');

		if ($params['layout'] != 'metro' || isset($portfolio_item_size)) {
			if ($thegem_highlight_type != 'disabled' && $thegem_highlight_type != 'vertical') {
				$thegem_classes = array_merge($thegem_classes, get_thegem_portfolio_render_item_classes($params, $thegem_highlight_type));
			} else {
				$thegem_classes = array_merge($thegem_classes, $item_classes);
			}
		}

		if ($params['ignore_highlights'] == '1') {
			unset($post_item_data['highlight']);
			unset($post_item_data['highlight_type']);
			unset($post_item_data['highlight_style']);
		}


		if ($params['layout'] != 'metro') {
			if ($params['columns_desktop'] == '1x') {
				$thegem_image_classes = array_merge($thegem_image_classes, array('col-sm-5', 'col-xs-12'));
				$thegem_caption_classes = array_merge($thegem_caption_classes, array('col-sm-7', 'col-xs-12'));
			}
		}

		if ($thegem_highlight_type != 'disabled') {
			$thegem_classes[] = 'double-item';
			$thegem_classes[] = 'double-item-' . $thegem_highlight_type;
		}

		$alternative_highlight_style_enabled = isset($post_item_data['highlight']) && $post_item_data['highlight'] && $post_item_data['highlight_style'] == 'alternative' && $params['display_titles'] == 'hover';
		if ($alternative_highlight_style_enabled) {
			$thegem_classes[] = 'double-item-style-' . $post_item_data['highlight_style'];
			$thegem_classes[] = 'double-item-style-' . $post_item_data['highlight_style'] . '-' . $thegem_highlight_type;

			if ($thegem_highlight_type == 'squared') {
				$thegem_highlight_type = 'vertical';
			} else {
				$thegem_highlight_type = 'disabled';
			}
		}

		if ($thegem_highlight_type != 'disabled') {
			$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params, $thegem_highlight_type);
		}

		$thegem_has_post_thumbnail = has_post_thumbnail(get_the_ID());

		if ($params['loading_animation'] !== 'disabled') {
			$thegem_classes[] = 'item-animations-not-inited';
		}

		if (!$params['show_additional_meta']) {
			$thegem_classes[] = 'post-hide-categories';
		}

		if ($params['hide_date']) {
			$thegem_classes[] = 'post-hide-date';
		}

		$post_excerpt = !has_excerpt() && !empty($thegem_post_data['title_excerpt']) ? $thegem_post_data['title_excerpt'] : preg_replace('%&#x[a-fA-F0-9]+;%', '', apply_filters('the_excerpt', get_the_excerpt()));

		$has_comments = comments_open() && !$params['hide_comments'];

		$has_likes = function_exists('zilla_likes') && !empty($params['likes']);

		if ($params['version'] != 'default' && $params['display_titles'] == 'page' && $params['background_style'] == 'transparent' && ($has_likes || $has_comments || !$params['disable_socials'])) {
			$thegem_classes[] = 'show-caption-border';
		}

		if ($params['version'] == 'default' && $params['display_titles'] == 'page' && $params['background_style'] == 'transparent') {
			$thegem_classes[] = 'show-caption-border';
		}

		if (empty($post_excerpt)) {
			$thegem_classes[] = 'post-empty-excerpt';
		}

		if ($params['show_additional_meta']) {
			foreach ($slugs as $thegem_k => $thegem_slug) {
				if (isset($thegem_terms_set[$thegem_slug])) {
					$thegem_classes[] = 'post-has-sets';
					break;
				}
			}
		}

		if (!$params['hide_author']) {
			$thegem_classes[] = 'post-has-author';
		}

		$gap_size = round(intval($params['gaps_size']) / 2);

		if(!empty($params['skin_source']) && $params['skin_source'] === 'builder' && !empty($params['loop_builder']) && empty($portfolio_item_size)) {
?>
<div <?php post_class($thegem_classes); ?> data-default-sort="<?php echo intval(get_post()->menu_order); ?>" data-sort-date="<?php echo get_the_date('U'); ?>">
	<div class="thegem-template-wrapper thegem-template-loop-item thegem-template-<?php echo esc_attr($params['loop_builder']); ?> thegem-loop-post-<?= esc_attr($post_id);?>">
		<?php
		$thegem_template_type_outer = isset($GLOBALS['thegem_template_type']) ? $GLOBALS['thegem_template_type'] : '';
		$GLOBALS['thegem_template_type'] = 'loop-item';
		$GLOBALS['thegem_loop_item_post'] = $post_id;
		$template = get_post($params['loop_builder']);
		$template->post_content = str_replace(array('<p>[', ']</p>'), array('[', ']'), $template->post_content);
		$template_html = do_shortcode($template->post_content);
		$custom_css = get_post_meta($params['loop_builder'], '_wpb_shortcodes_custom_css', true) . get_post_meta($params['loop_builder'], '_wpb_post_custom_css', true);
		$custom_css = str_replace(array("\n", "\r"), '', $custom_css);
		if ($custom_css) {
			$template_html = '<style>' . esc_js($custom_css) . '</style>' . $template_html;
		}
		echo $template_html;
		unset($GLOBALS['thegem_template_type']);
		unset($GLOBALS['thegem_loop_item_post']);
		if (!empty($thegem_template_type_outer)) {
			$GLOBALS['thegem_template_type'] = $thegem_template_type_outer;
		}
		?>
	</div>
	<?php /*<a href="<?php echo get_the_permalink($post_id); ?>">Link</a>*/ ?>
</div>
<?php
		} else {
			include(locate_template(array('gem-templates/news-grid/content-item.php')));
		}
	}
}

if (!function_exists('thegem_extended_blog_render_item_author')) {
	function thegem_extended_blog_render_item_author($params) {
		if ($params['hide_author']) return; ?>

		<div class="author">
			<?php if (!$params['hide_author_avatar']): ?>
				<span class="author-avatar"><?php echo get_avatar(get_the_author_meta('ID'), 50) ?></span>
			<?php endif; ?>
			<span class="author-name"><?php printf(esc_html__( "By %s", "thegem" ), get_the_author_link()) ?></span>
		</div>
		<?php
	}
}

if (!function_exists('thegem_extended_blog_render_item_meta')) {
	function thegem_extended_blog_render_item_meta($params, $has_comments, $has_likes) {
		global $post;

		if (!$has_comments && !$has_likes && $params['disable_socials']) return; ?>

		<div class="grid-post-meta clearfix <?php if ( !$has_likes): ?>without-likes<?php endif; ?>">
			<div class="grid-post-meta-inner">
				<?php if (!$params['disable_socials']): ?>
					<div class="grid-post-share">
						<a href="javascript: void(0);" class="icon share"><i class="default"></i></a>
					</div>
				<?php endif; ?>

				<div class="grid-post-meta-comments-likes">
					<?php if ($has_comments) : ?>
						<span class="comments-link"><i class="default"></i><?php comments_popup_link(0, 1, '%'); ?></span>
					<?php endif; ?>

					<?php if( $has_likes ) { echo '<span class="post-meta-likes"><i class="default"></i>';zilla_likes();echo '</span>'; } ?>
				</div>

				<?php if (!$params['disable_socials']): ?>
					<div class="portfolio-sharing-pane"><?php thegem_socials_sharing(); ?></div>
				<?php endif; ?>
			</div>
		</div>

		<?php
	}
}

if (!function_exists('blog_grid_extended_more_callback')) {
	function blog_grid_extended_more_callback() {
		$settings = isset($_POST['data']) ? json_decode(stripslashes($_POST['data']), true) : array();
		ob_start();
		$response = array('status' => 'success');
		$page = isset($settings['more_page']) ? intval($settings['more_page']) : 1;
		if ($page == 0)
			$page = 1;

		$show_all = $settings['load_more_show_all'] && $page != 1;

		$taxonomy_filter = $meta_filter = $manual_selection = $blog_authors = $date_query = [];

		if ($settings['query_type'] == 'post') {

			$post_type = 'post';
			if ($settings['select_blog_categories'] && !empty($settings['categories']) && !in_array('0', $settings['categories'])) {
				$taxonomy_filter['category'] = $settings['categories'];
			}
			if ($settings['select_blog_tags'] && !empty($settings['tags'])) {
				$taxonomy_filter['post_tag'] = $settings['tags'];
			}
			if ($settings['select_blog_posts'] && !empty($settings['posts'])) {
				$manual_selection = $settings['posts'];
			}
			if ($settings['select_blog_authors'] && !empty($settings['authors'])) {
				$blog_authors = $settings['authors'];
			}
			$exclude = $settings['exclude_blog_posts'];

		} else if ($settings['query_type'] == 'related') {

			$post_type = isset($settings['taxonomy_related_post_type']) ? $settings['taxonomy_related_post_type'] : 'any';
			$taxonomy_filter = $settings['related_tax_filter'];
			$exclude = $settings['exclude_posts_manual'];

		} else if ($settings['query_type'] == 'archive') {

			$post_type = $settings['archive_post_type'];
			if (!empty($settings['select_blog_authors'])) {
				$blog_authors = $settings['select_blog_authors'];
			} else if (!empty($settings['archive_tax_filter'])) {
				$taxonomy_filter = $settings['archive_tax_filter'];
			} else if (!empty($settings['date_query'])) {
				$date_query = $settings['date_query'];
			}
			$exclude = $settings['exclude_posts_manual'];

		} else if ($settings['query_type'] == 'manual') {

			$post_type = 'any';
			$manual_selection = $settings['select_posts_manual'];
			$exclude = $settings['exclude_posts_manual'];

		} else {

			$post_type = $settings['query_type'];
			foreach ($settings['source_post_type_' . $post_type] as $source) {
				if ($source == 'all') {

				} else if ($source == 'manual') {
					$manual_selection = $settings['source_post_type_' . $post_type . '_manual'];
				} else {
					$tax_terms = $settings['source_post_type_' . $post_type . '_tax_' . $source];
					if (!empty($tax_terms)) {
						$taxonomy_filter[$source] = $tax_terms;
					}
				}
			}
			$exclude = $settings['source_post_type_' . $post_type . '_exclude'];

		}

		if (!empty($settings['has_categories_filter']) && !empty($settings['categories'])) {
			$taxonomy_filter['category'] = $settings['categories'];
		}

		if (!empty($settings['has_attributes_filter'])) {
			$attrs = explode(",", $settings['filters_attr']);
			foreach ($attrs as $attr) {
				$values = explode(",", $settings['filters_attr_val_' . $attr]);
				if (!empty($values)) {
					if (strpos($attr, "tax_") === 0) {
						$taxonomy_filter[str_replace("tax_","", $attr)] = $values;
					} else {
						$meta_filter[str_replace("meta_","", $attr)] = $values;
					}
				}
			}
		}

		$search = isset($settings['portfolio_search_filter']) && $settings['portfolio_search_filter'] != '' ? $settings['portfolio_search_filter'] : null;

		if(!empty($settings['search_page'])) {
			$post_type = thegem_get_search_post_types_array();
		}
		$items_per_page = $settings['items_per_page'] ? intval($settings['items_per_page']) : 8;

		$news_grid_loop = get_thegem_extended_blog_posts($post_type, $taxonomy_filter, $meta_filter, $manual_selection, $exclude, $blog_authors, $page, $items_per_page, $settings['orderby'], $settings['order'], $settings['offset'], $settings['ignore_sticky_posts'], $search, $settings['search_by'], $date_query, $show_all);
		$max_page = ceil(($news_grid_loop->found_posts - intval($settings['offset'])) / $items_per_page);
		$next_page = $max_page > $page ? $page + 1 : 0;
		if ($show_all) {
			$next_page = 0;
		}

		if ($news_grid_loop->have_posts()) {

			$item_classes = get_thegem_portfolio_render_item_classes($settings);
			$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($settings); ?>

			<div data-page="<?php echo $page; ?>" data-next-page="<?php echo $next_page; ?>" data-pages-count="<?php echo esc_attr($max_page); ?>">
				<?php
				if ($settings['layout'] == 'creative') {
					$creative_blog_schemes_list = [
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
						'2' => [
							'2a' => [
								'count' => 5,
								0 => 'vertical',
							],
							'2b' => [
								'count' => 5,
								3 => 'vertical',
							],
							'2c' => [
								'count' => 4,
								0 => 'vertical',
								2 => 'vertical',
							],
							'2d' => [
								'count' => 4,
								0 => 'horizontal',
								1 => 'vertical',
							],
							'2e' => [
								'count' => 5,
								0 => 'horizontal',
							],
							'2f' => [
								'count' => 4,
								0 => 'horizontal',
								1 => 'horizontal',
							],
							'2g' => [
								'count' => 5,
								2 => 'horizontal',
							],
							'2h' => [
								'count' => 4,
								0 => 'horizontal',
								3 => 'horizontal',
							],
						]
					];
					$columns = $settings['columns_desktop'] != '100%' ? str_replace("x", "", $settings['columns_desktop']) : $settings['columns_100'];
					$items_sizes = $creative_blog_schemes_list[$columns][$settings['layout_scheme_' . $columns . 'x']];
					$items_count = $items_sizes['count'];
				}

				$i = 0;
				while ($news_grid_loop->have_posts()) : $news_grid_loop->the_post();
					if (class_exists('WPBMap') && method_exists('WPBMap', 'addAllMappedShortcodes')) {
						WPBMap::addAllMappedShortcodes();
					}
					$thegem_highlight_type_creative = null;
					if ($settings['layout'] == 'creative') {
						$thegem_highlight_type_creative = 'disabled';
						$item_num = $i % $items_count;
						if (isset($items_sizes[$item_num])) {
							$thegem_highlight_type_creative = $items_sizes[$item_num];
						}
					}
					echo thegem_extended_blog_render_item($settings, $item_classes, $thegem_sizes, get_the_ID(), $thegem_highlight_type_creative);
					if ($settings['layout'] == 'creative' && $i == 0) {
						echo thegem_extended_blog_render_item($settings, ['size-item'], $thegem_sizes);
					}
					$i++;
				endwhile; ?>
			</div>
		<?php } else { ?>
			<div data-page="1" data-next-page="0" data-pages-count="1">
				<div class="portfolio-item not-found">
					<div class="found-wrap">
						<div class="image-inner empty"></div>
						<div class="msg">
							<?php echo wp_kses($settings['not_found_text'], 'post'); ?>
						</div>
					</div>
				</div>
			</div>
		<?php }

		$response['html'] = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
		$response = json_encode($response);
		header("Content-Type: application/json");
		echo $response;
		exit;
	}

	add_action('wp_ajax_blog_grid_extended_load_more', 'blog_grid_extended_more_callback');
	add_action('wp_ajax_nopriv_blog_grid_extended_load_more', 'blog_grid_extended_more_callback');
}

if (!function_exists('get_thegem_extended_blog_posts')) {
	function get_thegem_extended_blog_posts($post_type, $taxonomy_filter, $meta_filter, $manual_selection, $exclude, $authors, $page = 1, $ppp = -1, $orderby = '', $order = '', $offset = false, $ignore_sticky_posts = false, $search = null, $search_by = 'content', $date_query = '', $show_all = false) {

		$args = array(
			'post_type' => $post_type,
			'post_status' => 'publish',
			'posts_per_page' => $ppp,
		);

		if ($orderby == 'default') {
			$args['orderby'] = 'menu_order date';
		} else if (!empty($orderby)) {
			$args['orderby'] = $orderby;
			if (!in_array($orderby, ['date', 'id', 'author', 'title', 'name', 'modified', 'comment_count', 'rand', 'menu_order date'])) {
				if (strpos($orderby, 'num_') === 0) {
					$args['orderby'] = 'meta_value_num';
					$args['meta_key'] = str_replace('num_', '', $orderby);
				} else {
					$args['orderby'] = 'meta_value';
					$args['meta_key'] = $orderby;
				}
			}
		}

		if (!empty($order) && $orderby !== 'default') {
			$args['order'] = $order;
		}

		$tax_query = $meta_query = [];

		if (!empty($taxonomy_filter)) {
			foreach ($taxonomy_filter as $tax => $tax_arr) {
				if (!empty($tax_arr) && !in_array('0', $tax_arr)) {
					$query_arr = array(
						'taxonomy' => $tax,
						'field' => 'slug',
						'terms' => $tax_arr,
					);
				} else {
					$query_arr = array(
						'taxonomy' => $tax,
						'operator' => 'EXISTS'
					);
				}
				$tax_query[] = $query_arr;
			}
		}

		if (!empty($meta_filter)) {
			foreach ($meta_filter as $meta => $meta_arr) {
				if (!empty($meta_arr)) {
					if (strpos($meta, "__range") > 0) {
						$meta = str_replace("__range","", $meta);
						$query_arr = array(
							'key' => $meta,
							'value' => $meta_arr,
							'compare'   => 'BETWEEN',
							'type'   => 'NUMERIC',
						);
					} else if (strpos($meta, "__check") > 0) {
						$meta = str_replace("__check","", $meta);
						$check_meta_query = array(
							'relation' => 'OR',
						);
						foreach ($meta_arr as $value) {
							$check_meta_query[] = array(
								'key' => $meta,
								'value' => sprintf('"%s"', $value),
								'compare' => 'LIKE',
							);
						}
						$query_arr = $check_meta_query;
					} else {
						$query_arr = array(
							'key' => $meta,
							'value' => $meta_arr,
							'compare' => 'IN',
						);
					}
					$meta_query[] = $query_arr;
				}
			}
		}

		if (!empty($search) && $search_by != 'content') {
			$search_meta_query = array(
				'relation' => 'OR',
			);
			foreach ($search_by as $key) {
				$search_meta_query[] = array(
					'key' => $key,
					'value' => $search,
					'compare' => 'LIKE'
				);
			}
			$meta_query[] = $search_meta_query;
		}

		if (!empty($tax_query)) {
			$args['tax_query'] = $tax_query;
		}

		if (!empty($meta_query)) {
			$args['meta_query'] = $meta_query;
		}

		if (!empty($manual_selection)) {
			$args['post__in'] = $manual_selection;
		}

		if (!empty($exclude)) {
			$args['post__not_in'] = $exclude;
		}

		if (!empty($authors)) {
			$args['author__in'] = $authors;
		}

		if ($ignore_sticky_posts) {
			$args['ignore_sticky_posts'] = 1;
		}

		if (!empty($date_query)) {
			$args['date_query'] = array($date_query);
		}

		if (!empty($offset) || $show_all) {
			$args['offset'] = $ppp * ($page - 1) + $offset;
		} else {
			$args['paged'] = $page;
		}

		if ($show_all) {
			$args['posts_per_page'] = 999;
		}

		if (!empty($search) && $search_by == 'content') {
			$args['s'] = $search;
		}

		return new WP_Query($args);
	}
}

if (!function_exists('thegem_search_grid_content')) {
	function thegem_search_grid_content($mixed_grid = false) {

		if (get_query_var('post_type') != 'any' && !$mixed_grid) {
			$post_types_arr = get_query_var('post_type');
		} else {
			$post_types_arr = thegem_get_search_post_types_array();
		}

		$params = array(
			'query_type' => get_post_type(),
			'layout' => thegem_get_option('search_layout_type_grid'),
			'categories' => ['0'],
			'search' => empty(get_search_query()) ? get_query_var('p') : get_search_query(),
			'post_type' => $post_types_arr,
			'columns_desktop' => thegem_get_option('search_layout_columns_desktop'),
			'columns_tablet' => thegem_get_option('search_layout_columns_tablet'),
			'columns_mobile' => thegem_get_option('search_layout_columns_mobile'),
			'columns_100' => thegem_get_option('search_layout_columns_100'),
			'display_titles' => thegem_get_option('search_layout_caption_position') == 'bellow' ? 'page' : 'hover',
			'version' => thegem_get_option('search_layout_skin') == 'classic' ? 'default' : 'new',
			'gaps_size' => thegem_get_option('search_layout_gaps_desktop'),
			'gaps_size_tablet' => thegem_get_option('search_layout_gaps_tablet'),
			'gaps_size_mobile' => thegem_get_option('search_layout_gaps_mobile'),
			'image_size' => thegem_get_option('search_layout_image_size'),
			'image_ratio_full' => thegem_get_option('search_layout_image_ratio_full'),
			'image_ratio_default' => thegem_get_option('search_layout_image_ratio_default'),
			'icon_hover_show' => thegem_get_option('search_layout_icon_on_hover'),
			'blog_show_sorting' => thegem_get_option('search_layout_sorting'),
			'post_type_indication' => thegem_get_option('search_layout_post_type_indication'),
			'hover' => str_replace("_", "-", thegem_get_option('search_layout_hover_effect')),
			'hide_featured_image' => !thegem_get_option('search_layout_caption_featured_image'),
			'blog_show_title' => thegem_get_option('search_layout_caption_title'),
			'title_preset' => 'title-'.thegem_get_option('search_layout_caption_title_preset'),
			'blog_show_description' => thegem_get_option('search_layout_caption_description'),
			'hide_date' => !thegem_get_option('search_layout_caption_date'),
			'show_additional_meta' => thegem_get_option('search_layout_caption_categories'),
			'hide_author' => !thegem_get_option('search_layout_caption_author'),
			'hide_author_avatar' => !thegem_get_option('search_layout_caption_author_avatar'),
			'by_text' => __('By', 'thegem'),
			'hide_comments' => '1',
			'likes' => '',
			'disable_socials' => '1',
			'show_readmore_button' => '',
			'caption_container_alignment' => thegem_get_option('search_layout_caption_content_alignment_desktop'),
			'caption_container_alignment_tablet' => thegem_get_option('search_layout_caption_content_alignment_tablet'),
			'caption_container_alignment_mobile' => thegem_get_option('search_layout_caption_content_alignment_mobile'),
			'background_style' => thegem_get_option('search_layout_caption_container_preset'),
			'show_bottom_border' => thegem_get_option('search_layout_caption_bottom_border'),
			'show_pagination' => thegem_get_option('search_layout_pagination'),
			'items_per_page' => thegem_get_option('search_layout_pagination_items_per_page'),
			'pagination_type' => thegem_get_option('search_layout_pagination_type') == 'loadmore' ? 'more' : thegem_get_option('search_layout_pagination_type'),
			'more_button_text' => thegem_get_option('search_layout_load_more_text'),
			'more_icon_pack' => thegem_get_option('search_layout_load_more_icon_pack'),
			'more_icon_' . thegem_get_option('search_layout_load_more_icon_pack') => thegem_get_option('search_layout_load_more_icon'),
			'more_stretch_full_width' => thegem_get_option('search_layout_load_more_stretch'),
			'more_show_separator' => (thegem_get_option('search_layout_load_more_stretch') != 1 && thegem_get_option('search_layout_load_more_separator') == 1) ? '1' : '',
			'load_more_spacing' => thegem_get_option('search_layout_load_more_spacing_desktop'),
			'load_more_spacing_tablet' => thegem_get_option('search_layout_load_more_spacing_tablet'),
			'load_more_spacing_mobile' => thegem_get_option('search_layout_load_more_spacing_mobile'),
			'pagination_more_button_type' => thegem_get_option('search_layout_load_more_btn_type'),
			'pagination_more_button_size' => thegem_get_option('search_layout_load_more_btn_size'),
			'mixed_grids_per_page' => thegem_get_option('search_layout_mixed_grids_items'),
			'mixed_grids_show_all_button_text' => thegem_get_option('search_layout_mixed_grids_show_all'),
			'mixed_grids_show_all_icon_pack' => thegem_get_option('search_layout_mixed_grids_show_all_icon_pack'),
			'mixed_grids_show_all_icon_' . thegem_get_option('search_layout_mixed_grids_show_all_icon_pack') => thegem_get_option('search_layout_mixed_grids_show_all_icon'),
			'mixed_grids_show_all_stretch_full_width' => thegem_get_option('search_layout_mixed_grids_show_all_stretch'),
			'mixed_grids_show_all_show_separator' => (thegem_get_option('search_layout_mixed_grids_show_all_stretch') != 1 && thegem_get_option('search_layout_mixed_grids_show_all_separator') == 1) ? '1' : '',
			'mixed_grids_show_all_spacing' => thegem_get_option('search_layout_mixed_grids_show_all_spacing_desktop'),
			'mixed_grids_show_all_spacing_tablet' => thegem_get_option('search_layout_mixed_grids_show_all_spacing_tablet'),
			'mixed_grids_show_all_spacing_mobile' => thegem_get_option('search_layout_mixed_grids_show_all_spacing_mobile'),
			'mixed_grids_show_all_button_type' => thegem_get_option('search_layout_mixed_grids_show_all_btn_type'),
			'mixed_grids_show_all_button_size' => thegem_get_option('search_layout_mixed_grids_show_all_btn_size'),
			'loading_animation' => thegem_get_option('search_layout_loading_animation'),
			'animation_effect' => thegem_get_option('search_layout_animation_effect'),
			'ignore_highlights' => '1',
			'skeleton_loader' => thegem_get_option('search_layout_skeleton_loader'),
			'fullwidth_section_images' => '',
			'title_font_weight' => '',
			'skin_source' => thegem_get_option('search_skin_source'),
			'loop_builder' => thegem_get_option('search_item_builder_template'),
			'equal_height' => !thegem_get_option('search_items_equal_height_disabled'),
		);

		if($params['skin_source'] === 'builder') {
			if(empty($params['loop_builder'])) {
				echo '<div class="bordered-box centered-box styled-subtitle">'.esc_html__('Please select loop item template', 'thegem').'</div>';
				return ;
			}
			$params['ignore_highlights'] = '1';
			$equal_height = !empty($params['equal_height']) && $params['layout'] === 'justified';
		}

		if (!empty($params['hide_featured_image']) && $params['layout'] == 'metro') {
			$params['layout'] = 'justified';
		}

		wp_enqueue_style('thegem-news-grid');
		wp_enqueue_script('thegem-portfolio-grid-extended');
		$grid_uid = 'blog_grid';

		$localize = array(
			'data' => $params,
			'action' => 'search_grid_load_more',
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('portfolio_ajax-nonce')
		);
		wp_localize_script('thegem-portfolio-grid-extended', 'thegem_portfolio_ajax_'. $grid_uid, $localize );
		$params['action'] = 'blog_grid_extended_load_more';

		switch ($params['version']) {
			case 'default':
				if ($params['display_titles'] == 'hover') {
					$hover_effect = $params['version'] . '-' . $params['hover'];
					wp_enqueue_style('thegem-news-grid-version-default-hovers-' . $params['hover']);
				} else {
					$hover_effect = $params['hover'];
					wp_enqueue_style('thegem-hovers-' . $params['hover']);
					wp_enqueue_style('thegem-news-grid-hovers');
				}
				break;
			case 'new':
				$hover_effect = $params['version'] . '-' . $params['hover'];
				wp_enqueue_style('thegem-news-grid-version-new-hovers-' . $params['hover']);
				break;
		}

		if ($params['loading_animation'] !== 'disabled') {
			wp_enqueue_style('thegem-animations');
			wp_enqueue_script('thegem-items-animations');
			wp_enqueue_script('thegem-scroll-monitor');
		}

		if ($params['pagination_type'] == 'more' || $mixed_grid) {
			wp_enqueue_style('thegem-button');
		} else if ($params['pagination_type'] == 'scroll') {
			wp_enqueue_script('thegem-scroll-monitor');
		}

		if ($params['layout'] !== 'justified' || !$params['ignore_highlights']) {
			if ($params['layout'] == 'metro') {
				wp_enqueue_script('thegem-isotope-metro');
			} else {
				wp_enqueue_script('thegem-isotope-masonry-custom');
			}
		}

		if ($mixed_grid) {
			$items_per_page = $params['mixed_grids_per_page'] ? intval($params['mixed_grids_per_page']) : 12;

			$page = 1;
			if (($key = array_search('product', $post_types_arr)) !== false) {
				unset($post_types_arr[$key]);
			}

			$args = array(
				'post_type' => $post_types_arr,
				'post_status' => 'publish',
				'paged' => $page,
				'posts_per_page' => $items_per_page,
			);

			$args['s'] = $params['search'];

			$search_grid_loop = new WP_Query( $args );

			if (!$search_grid_loop->have_posts()) {
				return;
			}
			echo '<h3 class="light search-grid-title"><span>' . thegem_get_option('search_layout_mixed_grids_title') . '</span></h3>';

			$max_page = $search_grid_loop->max_num_pages;
		} else {
			$items_per_page = $params['items_per_page'] ? intval($params['items_per_page']) : 12;

			$page = get_query_var('paged') ?: 1;

			global $wp_query;
			$max_page = $wp_query->max_num_pages;
		}

		if ($max_page > $page)
			$next_page = $page + 1;
		else
			$next_page = 0;

		$item_classes = get_thegem_portfolio_render_item_classes($params);
		$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params);

		$style = '';

		if (isset($params['gaps_size']) && $params['gaps_size'] != '') {
			$gaps_size = $params['gaps_size'];

			$style .= '.portfolio.news-grid.category-grid .portfolio-item {
				padding: calc('.$gaps_size.'px/2) !important;
			}
			.portfolio.news-grid.category-grid .portfolio-row {
				margin: calc(-'.$gaps_size.'px/2);
			}
			.portfolio.news-grid.category-grid.fullwidth-columns .portfolio-row {
				margin: calc(-'.$gaps_size.'px/2) 0;
			}
			.portfolio.news-grid.category-grid .fullwidth-block:not(.no-paddings) {
				padding-left: '.$gaps_size.'px; padding-right: '.$gaps_size.'px;
			}
			.portfolio.news-grid.category-grid .fullwidth-block .portfolio-row {
				padding-left: calc('.$gaps_size.'px/2); padding-right: calc('.$gaps_size.'px/2);
			}';
		}

		if (isset($params['gaps_size_tablet']) && $params['gaps_size_tablet'] != '') {
			$gaps_size = $params['gaps_size_tablet'];

			$style .= '@media (max-width: 991px) {
				.portfolio.news-grid.category-grid .portfolio-item {
					padding: calc('.$gaps_size.'px/2) !important;
				}
				.portfolio.news-grid.category-grid .portfolio-row {
					margin: calc(-'.$gaps_size.'px/2);
				}
				.portfolio.news-grid.category-grid.fullwidth-columns .portfolio-row {
					margin: calc(-'.$gaps_size.'px/2) 0;
				}
				.portfolio.news-grid.category-grid .fullwidth-block:not(.no-paddings) {
					padding-left: '.$gaps_size.'px; padding-right: '.$gaps_size.'px;
				}
				.portfolio.news-grid.category-grid .fullwidth-block .portfolio-row {
					padding-left: calc('.$gaps_size.'px/2); padding-right: calc('.$gaps_size.'px/2);
				}
			}';
		}

		if (isset($params['gaps_size_mobile']) && $params['gaps_size_mobile'] != '') {
			$gaps_size = $params['gaps_size_mobile'];

			$style .= '@media (max-width: 767px) {
				.portfolio.news-grid.category-grid .portfolio-item {
					padding: calc('.$gaps_size.'px/2) !important;
				}
				.portfolio.news-grid.category-grid .portfolio-row {
					margin: calc(-'.$gaps_size.'px/2);
				}
				.portfolio.news-grid.category-grid.fullwidth-columns .portfolio-row {
					margin: calc(-'.$gaps_size.'px/2) 0;
				}
				.portfolio.news-grid.category-grid .fullwidth-block:not(.no-paddings) {
					padding-left: '.$gaps_size.'px; padding-right: '.$gaps_size.'px;
				}
				.portfolio.news-grid.category-grid .fullwidth-block .portfolio-row {
					padding-left: calc('.$gaps_size.'px/2); padding-right: calc('.$gaps_size.'px/2);
				}
			}';
		}

		if ($params['background_style'] == 'transparent' && $params['show_bottom_border']) {
			$style .= '.portfolio.news-grid.category-grid .portfolio-item .wrap > .caption {
				border-bottom-width: 1px !important;
			}';
		}

		if (!empty($params['caption_container_alignment'])) {
			$style .= '.portfolio.news-grid.category-grid.title-on-page .wrap > .caption,
			.portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content,
			.portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description,
			.portfolio.news-grid .portfolio-item .post-type { text-align: '.$params['caption_container_alignment'].'; }
			.portfolio.news-grid.category-grid.title-on-hover .portfolio-item:hover .image .links .caption .grid-post-meta { justify-content: '.$params['caption_container_alignment'].'; }';
		}

		if (!empty($params['caption_container_alignment_tablet'])) {
			$style .= '@media (max-width: 991px) {
				.portfolio.news-grid.category-grid.title-on-page .wrap > .caption,
				.portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content,
				.portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description,
				.portfolio.news-grid .portfolio-item .post-type { text-align: '.$params['caption_container_alignment_tablet'].'; }
				.portfolio.news-grid.category-grid.title-on-hover .portfolio-item:hover .image .links .caption .grid-post-meta { justify-content: '.$params['caption_container_alignment_tablet'].'; }
			}';
		}

		if (!empty($params['caption_container_alignment_mobile'])) {
			$style .= '@media (max-width: 767px) {
				.portfolio.news-grid.category-grid.title-on-page .wrap > .caption,
				.portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content,
				.portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description,
				.portfolio.news-grid .portfolio-item .post-type { text-align: '.$params['caption_container_alignment_mobile'].'; }
				.portfolio.news-grid.category-grid.title-on-hover .portfolio-item:hover .image .links .caption .grid-post-meta { justify-content: '.$params['caption_container_alignment_mobile'].'; }
			}';
		}

		if (!empty($params['load_more_spacing'])) {
			$style .= '.portfolio.news-grid.category-grid .portfolio-load-more { margin-top: '.$params['load_more_spacing'].'px; }';
		}

		if (!empty($params['load_more_spacing_tablet'])) {
			$style .= '@media (max-width: 991px) {
				.portfolio.news-grid.category-grid .portfolio-load-more { margin-top: '.$params['load_more_spacing_tablet'].'px; }
			}';
		}

		if (!empty($params['load_more_spacing_mobile'])) {
			$style .= '@media (max-width: 767px) {
				.portfolio.news-grid.category-grid .portfolio-load-more { margin-top: '.$params['load_more_spacing_mobile'].'px; }
			}';
		}

		if (!empty($params['mixed_grids_show_all_spacing'])) {
			$style .= '.portfolio.news-grid.category-grid .mixed-show-all { margin-top: '.$params['mixed_grids_show_all_spacing'].'px; }';
		}

		if (!empty($params['mixed_grids_show_all_spacing_tablet'])) {
			$style .= '@media (max-width: 991px) {
				.portfolio.news-grid.category-grid .mixed-show-all { margin-top: '.$params['mixed_grids_show_all_spacing_tablet'].'px; }
			}';
		}

		if (!empty($params['mixed_grids_show_all_spacing_mobile'])) {
			$style .= '@media (max-width: 767px) {
				.portfolio.news-grid.category-grid .mixed-show-all { margin-top: '.$params['mixed_grids_show_all_spacing_mobile'].'px; }
			}';
		}

		echo '<style>'.$style.'</style>';

		if ($params['columns_desktop'] == '100%' || ((!$params['ignore_highlights'] || $params['layout'] !== 'justified') && !$params['skeleton_loader'])) {
			echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="preloader-spin"></div></div>');
		} else if ($params['skeleton_loader']) { ?>
			<div class="preloader save-space">
				<div class="skeleton">
					<div class="skeleton-posts portfolio-row">
						<?php
						if ($mixed_grid) {
							while ($search_grid_loop->have_posts()) : $search_grid_loop->the_post();
								echo thegem_extended_blog_render_item($params, $item_classes);
							endwhile;
						} else {
							while (have_posts()) : the_post();
								echo thegem_extended_blog_render_item($params, $item_classes);
							endwhile;
						} ?>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="portfolio-preloader-wrapper">

			<?php
			$blog_wrap_class = [
				'portfolio portfolio-grid extended-portfolio-grid news-grid category-grid no-padding',
				'portfolio-pagination-' . $params['pagination_type'],
				'portfolio-style-' . $params['layout'],
				'background-style-' . $params['background_style'],
				'hover-' . $hover_effect,
				'title-on-' . $params['display_titles'],
				'version-' . $params['version'],
				($params['loading_animation'] !== 'disabled' ? 'loading-animation' : ''),
				($params['loading_animation'] !== 'disabled' ? 'item-animation-' . $params['animation_effect'] : ''),
				($params['gaps_size'] == 0 ? 'no-gaps' : ''),
				($params['columns_desktop'] == '100%' ? 'fullwidth-columns fullwidth-columns-' . $params['columns_100'] : ''),
				($params['version'] == 'new' || ($params['version'] == 'default' && $params['display_titles'] == 'hover') ? 'hover-' . $params['version'] . '-' . $params['hover'] : 'hover-' . $params['hover']),
				($params['display_titles'] == 'hover' ? 'hover-title' : ''),
				($params['layout'] == 'masonry' && $params['columns_desktop'] != '1x' ? 'portfolio-items-masonry' : ''),
				($params['columns_desktop'] != '100%' ? 'columns-' . str_replace("x", "", $params['columns_desktop']) : ''),
				'columns-tablet-' . str_replace("x", "", $params['columns_tablet']),
				'columns-mobile-' . str_replace("x", "", $params['columns_mobile']),
				($params['layout'] == 'justified' && $params['ignore_highlights'] ? 'disable-isotope' : ''),
				($params['hide_featured_image'] && $params['display_titles'] == 'page' ? 'without-image' : ''),
				(!empty($equal_height) ? 'loop-equal-height' : ''),
			];
			?>

			<div class="<?php echo implode(" ", $blog_wrap_class); ?>"
				 data-portfolio-uid="<?php echo esc_attr($grid_uid); ?>"
				 data-current-page="<?php echo esc_attr($page); ?>"
				 data-per-page="<?php echo esc_attr($items_per_page); ?>"
				 data-next-page="<?php echo esc_attr($next_page); ?>"
				 data-pages-count="<?php echo esc_attr($max_page); ?>"
				 data-hover="<?php echo esc_attr($hover_effect); ?>"
				 data-search="<?php echo esc_attr(get_search_query()); ?>"
				 data-post-types="<?php echo esc_attr(json_encode($post_types_arr)); ?>">
				<?php if (!$mixed_grid && $params['blog_show_sorting']): ?>
					<div class="portfolio-top-panel<?php if ($params['columns_desktop'] == '100%'): ?> fullwidth-block<?php endif; ?>">
						<div class="portfolio-top-panel-row">
							<div class="portfolio-top-panel-left"></div>
							<div class="portfolio-top-panel-right">
								<div class="portfolio-sorting title-h6">
									<div class="orderby light">
										<?php
										$query = $_GET;
										$orderby = 'date';
										if (isset($query['orderby']) && $query['orderby'] == 'title') {
											$orderby = 'title';
											$query['orderby'] = 'date';
										} else {
											$query['orderby'] = 'title';
										}
										if (isset($query['order']) && $query['orderby'] == 'asc') {
											$query['order'] = 'asc';
										} else {
											$query['order'] = 'desc';
										}
										$url_result_orderby = add_query_arg($query);

										$query = $_GET;
										$order = 'desc';
										if (isset($query['orderby']) && $query['orderby'] == 'title') {
											$query['orderby'] = 'title';
										} else {
											$query['orderby'] = 'date';
										}
										if (isset($query['order']) && $query['orderby'] == 'asc') {
											$order = 'asc';
											$query['order'] = 'desc';
										} else {
											$query['order'] = 'asc';
										}
										if (isset($_GET['order'])) {
											$order = $_GET['order'];
										}
										$url_result_order = add_query_arg($query); ?>

										<label for="" data-value="date"><?php _e('Date', 'thegem') ?></label>
										<a href="<?php echo esc_url($url_result_orderby); ?>" class="sorting-switcher <?php echo $orderby == 'title' ? 'right' : ''; ?>" data-current="<?php echo esc_attr($orderby); ?>"></a>
										<label for="" data-value="name"><?php _e('Name', 'thegem') ?></label>
									</div>
									<div class="portfolio-sorting-sep"></div>
									<div class="order light">
										<label for="" data-value="DESC"><?php _e('Desc', 'thegem') ?></label>
										<a href="<?php echo esc_url($url_result_order); ?>" class="sorting-switcher <?php echo $order == 'asc' ? 'right' : ''; ?>" data-current="<?php echo esc_attr($order); ?>"></a>
										<label for="" data-value="ASC"><?php _e('Asc', 'thegem') ?></label>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<div class="portfolio-row-outer <?php if ($params['columns_desktop'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
					<div class="row portfolio-row">
						<div class="portfolio-set clearfix"
							 data-max-row-height="">
							<?php
							if ($mixed_grid) {
								while ($search_grid_loop->have_posts()) : $search_grid_loop->the_post();
									echo thegem_extended_blog_render_item($params, $item_classes, $thegem_sizes, get_the_ID());
								endwhile;
							} else {
								while (have_posts()) : the_post();
									echo thegem_extended_blog_render_item($params, $item_classes, $thegem_sizes, get_the_ID());
								endwhile;
							} ?>
						</div><!-- .portflio-set -->
						<?php if ($params['columns_desktop'] != '1x'): ?>
							<div class="portfolio-item-size-container">
								<?php echo thegem_extended_blog_render_item($params, $item_classes); ?>
							</div>
						<?php endif; ?>
					</div><!-- .row-->
					<?php
					if (!$mixed_grid && $params['show_pagination']) {
						if ($params['pagination_type'] == 'normal') {
							thegem_pagination();
						} else if ($params['pagination_type'] == 'more' && $next_page > 0) {

							$separator_enabled = !empty($params['more_show_separator']) ? true : false;

							// Container
							$classes_container = 'gem-button-container gem-widget-button ';

							if ($separator_enabled) {
								$classes_container .= 'gem-button-position-center gem-button-with-separator ';
							} else {
								if ($params['more_stretch_full_width']) {
									$classes_container .= 'gem-button-position-fullwidth ';
								}
							}

							// Separator
							$classes_separator = 'gem-button-separator gem-button-separator-type-single';

							if (!empty($params['pagination_more_button_separator_style_active'])) {
								$classes_separator .= esc_attr($params['pagination_more_button_separator_style_active']);
							} ?>

							<div class="portfolio-load-more">
								<div class="inner">

									<div class="<?php echo esc_attr($classes_container); ?>">
										<?php if ($separator_enabled) { ?>
										<div class="<?php echo esc_attr($classes_separator); ?>">
											<div class="<?php echo esc_attr('gem-button-separator-holder') ?>">
												<div class="gem-button-separator-line"></div>
											</div>
											<div class="gem-button-separator-button">
												<?php } ?>

												<button class="load-more-button gem-button gem-button-size-<?php echo $params['pagination_more_button_size']; ?> gem-button-style-<?php echo $params['pagination_more_button_type']; ?> gem-button-icon-position-left gem-button-text-weight-normal">
													<span class="gem-inner-wrapper-btn">
														<?php if (isset($params['more_icon_pack']) && $params['more_icon_' . $params['more_icon_pack']] != '') {
															echo thegem_build_icon($params['more_icon_pack'], $params['more_icon_' . $params['more_icon_pack']]);
														} ?>
														<span class="gem-text-button">
															<?php echo '<span>' . wp_kses($params['more_button_text'], 'post') . '</span>'; ?>
														</span>
													</span>
												</button>

												<?php if ($separator_enabled) { ?>
											</div>
											<div class="<?php echo esc_attr('gem-button-separator-holder') ?>">
												<div class="gem-button-separator-line"></div>
											</div>
										</div>
									<?php } ?>

									</div>
								</div>
							</div>
							<?php
						} else if ($params['pagination_type'] == 'scroll' && $next_page > 0) { ?>
							<div class="portfolio-scroll-pagination"></div>
						<?php }
					}
					if ($mixed_grid) {
						$separator_enabled = !empty($params['mixed_grids_show_all_show_separator']) ? true : false;

						// Container
						$classes_container = 'gem-button-container gem-widget-button ';

						if ($separator_enabled) {
							$classes_container .= 'gem-button-position-center gem-button-with-separator ';
						} else {
							if ($params['mixed_grids_show_all_stretch_full_width']) {
								$classes_container .= 'gem-button-position-fullwidth ';
							}
						}

						// Separator
						$classes_separator = 'gem-button-separator gem-button-separator-type-single'; ?>

						<div class="mixed-show-all">
							<div class="inner">

								<div class="<?php echo esc_attr($classes_container); ?>">
									<?php if ($separator_enabled) { ?>
									<div class="<?php echo esc_attr($classes_separator); ?>">
										<div class="<?php echo esc_attr('gem-button-separator-holder') ?>">
											<div class="gem-button-separator-line"></div>
										</div>
										<div class="gem-button-separator-button">
											<?php } ?>

											<a href="<?php echo home_url(); ?>?s=<?php echo esc_attr($params['search']); ?>" class="load-more-button gem-button gem-button-size-<?php echo $params['mixed_grids_show_all_button_size']; ?> gem-button-style-<?php echo $params['mixed_grids_show_all_button_type']; ?> gem-button-icon-position-left gem-button-text-weight-normal">
												<span class="gem-inner-wrapper-btn">
													<?php if (isset($params['mixed_grids_show_all_icon_pack']) && $params['mixed_grids_show_all_icon_' . $params['mixed_grids_show_all_icon_pack']] != '') {
														echo thegem_build_icon($params['mixed_grids_show_all_icon_pack'], $params['mixed_grids_show_all_icon_' . $params['mixed_grids_show_all_icon_pack']]);
													} ?>
													<span class="gem-text-button">
														<?php echo '<span>' . wp_kses($params['mixed_grids_show_all_button_text'], 'post') . '</span>'; ?>
													</span>
												</span>
											</a>

											<?php if ($separator_enabled) { ?>
										</div>
										<div class="<?php echo esc_attr('gem-button-separator-holder') ?>">
											<div class="gem-button-separator-line"></div>
										</div>
									</div>
								<?php } ?>

								</div>
							</div>
						</div>
						<?php
					} ?>
				</div><!-- .full-width -->
			</div><!-- .portfolio-->
		</div><!-- .portfolio-preloader-wrapper-->

		<?php
	}
}

if (!function_exists('thegem_blog_archive_template')) {
	function thegem_blog_archive_template() {
		if(!function_exists('thegem_get_template_type') || !(thegem_get_template_type( get_the_ID() ) === 'blog-archive' || is_home() || is_category() || is_tag() || is_tax() || is_author() || is_date() || is_post_type_archive( 'post' ))) return false;
		$term_id = isset(get_queried_object()->term_id) ? get_queried_object()->term_id : 0;
		$blog_archive_data = thegem_get_output_blog_archive_data($term_id);
		if(is_tax()) {
			$term_archive_data = thegem_get_output_cpt_archive_data($term_id, 'post');
			if($term_archive_data['archive_layout_source'] === 'builder') {
				$blog_archive_data['blog_archive_layout_source'] = $term_archive_data['archive_layout_source'];
				$blog_archive_data['blog_archive_builder_template'] = $term_archive_data['archive_builder_template'];
			}
		}
		if($blog_archive_data['blog_archive_layout_source'] != 'builder') return false;
		$template_id = intval($blog_archive_data['blog_archive_builder_template']);
		if($template_id < 1) return false;
		$template = get_post($template_id);
		if($template && thegem_get_template_type($template_id) == 'blog-archive') {
			return $template_id;
		}
		return false;
	}
}

if (!function_exists('thegem_cpt_archive_template')) {
	function thegem_cpt_archive_template() {
		if(!function_exists('thegem_get_template_type') || !(thegem_get_template_type( get_the_ID() ) === 'blog-archive' || is_tax() || is_post_type_archive())) return false;
		$term_id = is_tax() ? get_queried_object()->term_id : 0;
		$post_type_name = is_post_type_archive() ? get_queried_object()->name  : 0;
		$cpt_archive_data = thegem_get_output_cpt_archive_data($term_id, $post_type_name);
		if($cpt_archive_data['archive_layout_source'] != 'builder') return false;
		$template_id = intval($cpt_archive_data['archive_builder_template']);
		if($template_id < 1) return false;
		$template = get_post($template_id);
		if($template && thegem_get_template_type($template_id) == 'blog-archive') {
			return $template_id;
		}
		return false;
	}
}

if (!function_exists('thegem_search_template')) {
	function thegem_search_template() {
		if(!function_exists('thegem_get_template_type') || !is_search()) return false;
		$search_data = array(
			'search_layout_source' => thegem_get_option('search_layout_source'),
			'search_builder_template' => thegem_get_option('search_builder_template'),
		);

		if($search_data['search_layout_source'] != 'builder') return false;
		$template_id = intval($search_data['search_builder_template']);
		if($template_id < 1) return false;
		$template = get_post($template_id);
		if($template && thegem_get_template_type($template_id) == 'blog-archive') {
			return $template_id;
		}
		return false;
	}
}
