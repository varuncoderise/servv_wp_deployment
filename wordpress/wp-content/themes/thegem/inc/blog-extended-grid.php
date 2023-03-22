<?php
if (!function_exists('get_thegem_extended_blog_render_item_classes')) {
	function get_thegem_extended_blog_render_item_classes($settings, $thegem_highlight_type = 'disabled') {
		$thegem_classes = [];

		if ($settings['columns'] == '1x') {
			$thegem_classes = array_merge($thegem_classes, array('col-xs-12'));
		}

		if ($settings['columns'] == '2x') {
			if ($thegem_highlight_type != 'disabled' && $thegem_highlight_type != 'vertical')
				$thegem_classes = array_merge($thegem_classes, array('col-xs-12'));
			else
				$thegem_classes = array_merge($thegem_classes, array('col-sm-6', 'col-xs-12'));
		}

		if ($settings['columns'] == '3x') {
			if ($thegem_highlight_type != 'disabled' && $thegem_highlight_type != 'vertical')
				$thegem_classes = array_merge($thegem_classes, array('col-md-8', 'col-xs-8'));
			else
				$thegem_classes = array_merge($thegem_classes, array('col-md-4', 'col-xs-4'));
		}

		if ($settings['columns'] == '4x') {
			if ($thegem_highlight_type != 'disabled' && $thegem_highlight_type != 'vertical')
				$thegem_classes = array_merge($thegem_classes, array('col-md-6', 'col-sm-8', 'col-xs-8'));
			else
				$thegem_classes = array_merge($thegem_classes, array('col-md-3', 'col-sm-4', 'col-xs-4'));
		}
		return $thegem_classes;
	}
}

if (!function_exists('get_thegem_extended_blog_render_item_image_sizes')) {
	function get_thegem_extended_blog_render_item_image_sizes($settings, $thegem_highlight_type = 'disabled') {
		$thegem_size = 'thegem-portfolio-justified';
		$thegem_sizes = thegem_image_sizes();

		if (!empty($settings['fullwidth_section_images'])) {
			$thegem_highlight_type = 'squared';
			$settings['caption_position'] = 'hover';
		}

		if ($settings['columns'] == '5x' || $settings['columns'] == '6x') {
			$settings['columns'] = '4x';
		}

		if ($settings['version'] == 'list') {
			switch ($settings['columns']) {
				case '1x':
					$settings['columns'] = '2x';
					break;
				case '2x':
					$settings['columns'] = '3x';
					break;
				default:
					$settings['columns'] = '4x';
					break;
			}
		}

		if ($settings['columns'] != '1x') {
			if ($settings['layout'] == 'masonry') {
				$thegem_size = 'thegem-portfolio-masonry';
				if ($thegem_highlight_type != 'disabled')
					$thegem_size = 'thegem-portfolio-masonry-double';
			} elseif ($settings['layout'] == 'metro') {
				$thegem_size = 'thegem-portfolio-metro';
			} else {
				if ($thegem_highlight_type != 'disabled') {
					$thegem_size = 'thegem-portfolio-double-' . str_replace('%', '', $settings['columns']);

					if ($settings['display_titles'] == 'hover' && isset($thegem_sizes[$thegem_size . '-hover'])) {
						$thegem_size .= '-hover';
					}

					if ($settings['columns'] == '100%' && $settings['display_titles'] == 'page') {
						$thegem_size .= '-page';
					}
				}
			}

			if ($thegem_highlight_type != 'disabled' && $settings['layout'] != 'metro' && $thegem_highlight_type != 'squared') {
				$thegem_size .= '-' . $thegem_highlight_type;
			}
		} else {
			$thegem_size = 'thegem-portfolio-1x';
		}

		$thegem_size = apply_filters('portfolio_size_filter', $thegem_size);

		$thegem_sources = array();

		if ($settings['layout'] == 'metro') {
			$thegem_sources = array(
				array('media' => '(min-width: 550px) and (max-width: 1100px)', 'srcset' => array('1x' => 'thegem-portfolio-metro-medium', '2x' => 'thegem-portfolio-metro-retina'))
			);
		}

		if ($thegem_highlight_type == 'disabled' ||
			($settings['layout'] == 'masonry' && $thegem_highlight_type != 'disabled') && $thegem_highlight_type == 'vertical') {

			$retina_size = $settings['layout'] == 'justified' ? $thegem_size : 'thegem-portfolio-masonry-double';

			if ($settings['columns'] == '100%') {
				if ($settings['layout'] == 'justified' || $settings['layout'] == 'masonry') {
					switch ($settings['columns_100']) {
						case '4':
							$thegem_sources = array(
								array('media' => '(max-width: 550px)', 'srcset' => array('1x' => 'thegem-portfolio-' . $settings['layout'] . '-2x-500', '2x' => $retina_size)),
								array('media' => '(min-width: 1280px) and (max-width: 1495px)', 'srcset' => array('1x' => 'thegem-portfolio-' . $settings['layout'] . '-fullwidth-5x', '2x' => $retina_size)),
								array('media' => '(max-width: 1920px)', 'srcset' => array('1x' => 'thegem-portfolio-' . $settings['layout'] . '-fullwidth-4x', '2x' => $retina_size))
							);
							break;

						case '5':
							$thegem_sources = array(
								array('media' => '(max-width: 550px)', 'srcset' => array('1x' => 'thegem-portfolio-' . $settings['layout'] . '-2x-500', '2x' => $retina_size)),
								array('media' => '(min-width: 1495px) and (max-width: 1680px), (min-width: 550px) and (max-width: 1280px)', 'srcset' => array('1x' => 'thegem-portfolio-' . $settings['layout'] . '-fullwidth-4x', '2x' => $retina_size)),
								array('media' => '(min-width: 1680px) and (max-width: 1920px), (min-width: 1280px) and (max-width: 1495px)', 'srcset' => array('1x' => 'thegem-portfolio-' . $settings['layout'] . '-fullwidth-5x', '2x' => $retina_size))
							);
							break;
					}
				}
			} else {
				if ($settings['layout'] == 'justified' || $settings['layout'] == 'masonry') {
					switch ($settings['columns']) {
						case '2x':
							$thegem_sources = array(
								array('media' => '(max-width: 550px)', 'srcset' => array('1x' => 'thegem-portfolio-' . $settings['layout'] . '-2x-500', '2x' => $retina_size)),
								array('media' => '(max-width: 1920px)', 'srcset' => array('1x' => 'thegem-portfolio-' . $settings['layout'] . '-2x', '2x' => $retina_size))
							);
							break;

						case '3x':
							$thegem_sources = array(
								array('media' => '(max-width: 550px)', 'srcset' => array('1x' => 'thegem-portfolio-' . $settings['layout'] . '-2x-500', '2x' => $retina_size)),
								array('media' => '(max-width: 1920px)', 'srcset' => array('1x' => 'thegem-portfolio-' . $settings['layout'] . '-3x', '2x' => $retina_size))
							);
							break;

						case '4x':
							$thegem_sources = array(
								array('media' => '(max-width: 550px)', 'srcset' => array('1x' => 'thegem-portfolio-' . $settings['layout'] . '-2x-500', '2x' => $retina_size)),
								array('media' => '(max-width: 1100px)', 'srcset' => array('1x' => 'thegem-portfolio-' . $settings['layout'] . '-3x', '2x' => $retina_size)),
								array('media' => '(max-width: 1920px)', 'srcset' => array('1x' => 'thegem-portfolio-' . $settings['layout'] . '-4x', '2x' => $retina_size))
							);
							break;
					}
				}
			}
		}

		return array($thegem_size, $thegem_sources);
	}
}

if (!function_exists('thegem_extended_blog_render_item')) {
	function thegem_extended_blog_render_item($params, $item_classes, $thegem_sizes = null, $post_id = false, $thegem_highlight_type_creative = null) {

		$terms = ['0'];
		if (!isset($params['source']) || $params['source'] == 'categories') {
			$terms = $params['categories'];
		}

		if (in_array('0', $terms)) {
			$terms = get_terms('category', array('hide_empty' => false));
		} else {
			foreach ($terms as $key => $term) {
				$terms[$key] = get_term_by('slug', $term, 'category');
				if (!$terms[$key]) {
					unset($terms[$key]);
				}
			}
		}

		$thegem_terms_set = array();
		foreach ($terms as $term) {
			$thegem_terms_set[$term->slug] = $term;
		}

		$taxonomies = array('category');
		if (taxonomy_exists('thegem_news_sets')) {
			$taxonomies[] = 'thegem_news_sets';
		}

		if ($post_id) {
			$slugs = wp_get_object_terms($post_id, $taxonomies, array('fields' => 'slugs'));

			$thegem_post_data = thegem_get_sanitize_page_title_data(get_the_ID());
			$post_item_data = thegem_get_sanitize_post_data(get_the_ID());

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

			$post_format = get_post_format(get_the_ID());
			if ($params['version'] == 'list' && $post_format == 'quote') {
				$post_format = '';
			}
		} else {
			$slugs = array();
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
				$thegem_classes = array_merge($thegem_classes, get_thegem_extended_blog_render_item_classes($params, $thegem_highlight_type));
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
			if ($params['columns'] == '1x') {
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
			$thegem_sizes = get_thegem_extended_blog_render_item_image_sizes($params, $thegem_highlight_type);
		}

		$thegem_has_post_thumbnail = has_post_thumbnail(get_the_ID());

		if ($params['loading_animation'] !== 'disabled') {
			$thegem_classes[] = 'item-animations-not-inited';
		}

		if ($params['hide_categories']) {
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

		if (!$params['hide_categories']) {
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

		include(locate_template(array('gem-templates/news-grid/content-item.php')));
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

		$blog_categories = $blog_tags = $blog_posts = $blog_authors = [];
		if ($settings['select_blog_categories'] && !empty($settings['categories'])) {
			$blog_categories = $settings['categories'];
		}
		if ($settings['select_blog_tags'] && !empty($settings['tags'])) {
			$blog_tags = $settings['tags'];
		}
		if ($settings['select_blog_posts'] && !empty($settings['posts'])) {
			$blog_posts = $settings['posts'];
		}
		if ($settings['select_blog_authors'] && !empty($settings['authors'])) {
			$blog_authors = $settings['authors'];
		}

		$news_grid_loop = get_thegem_extended_blog_posts($blog_categories, $blog_tags, $blog_posts, $blog_authors, $page, $settings['items_per_page'], $settings['orderby'], $settings['order'], $settings['offset'], $settings['exclude_blog_posts']);
		$max_page = ceil(($news_grid_loop->found_posts - intval($settings['offset'])) / $settings['items_per_page']);
		if ($max_page > $page)
			$next_page = $page + 1;
		else
			$next_page = 0;
		?>

		<div data-page="<?php echo $page; ?>" data-next-page="<?php echo $next_page; ?>"
			 data-pages-count="<?php echo esc_attr($max_page); ?>">
			<?php
			$item_classes = get_thegem_extended_blog_render_item_classes($settings);
			$thegem_sizes = get_thegem_extended_blog_render_item_image_sizes($settings);

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
				$columns = $settings['columns'] != '100%' ? str_replace("x", "", $settings['columns']) : $settings['columns_100'];
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
		<?php $response['html'] = trim(preg_replace('/\s\s+/', '', ob_get_clean()));
		$response = json_encode($response);
		header("Content-Type: application/json");
		echo $response;
		exit;
	}

	add_action('wp_ajax_blog_grid_extended_load_more', 'blog_grid_extended_more_callback');
	add_action('wp_ajax_nopriv_blog_grid_extended_load_more', 'blog_grid_extended_more_callback');
}

if (!function_exists('get_thegem_extended_blog_posts')) {
	function get_thegem_extended_blog_posts($blog_categories, $blog_tags, $blog_posts, $blog_authors, $page = 1, $ppp = -1, $orderby = 'menu_order date', $order = 'DESC', $offset = false, $exclude = false, $ignore_sticky_posts = false) {

		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'orderby' => $orderby,
			'order' => $order,
			'posts_per_page' => $ppp,
		);

		if ($ignore_sticky_posts) {
			$args['ignore_sticky_posts'] = 1;
		}

		$tax_query = [];

		if (!empty($blog_categories) && !in_array('0', $blog_categories, true)) {
			$tax_query[] = array(
				'taxonomy' => 'category',
				'field' => 'slug',
				'terms' => $blog_categories
			);
		}

		if (!empty($blog_tags)) {
			$tax_query[] = array(
				'taxonomy' => 'post_tag',
				'field' => 'slug',
				'terms' => $blog_tags
			);
		}

		if (!empty($tax_query)) {
			$args['tax_query'] = $tax_query;
		}

		if (!empty($blog_posts)) {
			$args['post__in'] = $blog_posts;
		}

		if (!empty($blog_authors)) {
			$args['author__in'] = $blog_authors;
		}

		if (!empty($offset)) {
			$args['offset'] = $ppp * ($page - 1) + $offset;
		} else {
			$args['paged'] = $page;
		}

		if (!empty($exclude)) {
			$args['post__not_in'] = $exclude;
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
			'layout' => thegem_get_option('search_layout_type_grid'),
			'categories' => ['0'],
			'search' => empty(get_search_query()) ? get_query_var('p') : get_search_query(),
			'post_type' => $post_types_arr,
			'columns' => thegem_get_option('search_layout_columns_desktop'), // search_layout_columns_tablet: '3x', search_layout_columns_mobile: '2x',
			'columns_100' => thegem_get_option('search_layout_columns_100'),
			'display_titles' => thegem_get_option('search_layout_caption_position') == 'bellow' ? 'page' : 'hover',
			'version' => thegem_get_option('search_layout_skin') == 'classic' ? 'default' : 'new',
			'gaps_size' => thegem_get_option('search_layout_gaps_desktop'),
			'gaps_size_tablet' => thegem_get_option('search_layout_gaps_tablet'),
			'gaps_size_mobile' => thegem_get_option('search_layout_gaps_mobile'),
			'icon_hover_show' => thegem_get_option('search_layout_icon_on_hover'),
			'blog_show_sorting' => thegem_get_option('search_layout_sorting'),
			'post_type_indication' => thegem_get_option('search_layout_post_type_indication'),
			'hover' => str_replace("_", "-", thegem_get_option('search_layout_hover_effect')),
			'hide_featured_image' => !thegem_get_option('search_layout_caption_featured_image'),
			'blog_show_title' => thegem_get_option('search_layout_caption_title'),
			'title_preset' => 'title-'.thegem_get_option('search_layout_caption_title_preset'),
			'blog_show_description' => thegem_get_option('search_layout_caption_description'),
			'hide_date' => !thegem_get_option('search_layout_caption_date'),
			'hide_categories' => !thegem_get_option('search_layout_caption_categories'),
			'hide_author' => !thegem_get_option('search_layout_caption_author'),
			'hide_author_avatar' => !thegem_get_option('search_layout_caption_author_avatar'),
			'by_text' => __('By', 'thegem'),
			'hide_comments' => '1',
			'likes' => '',
			'disable_socials' => '1',
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
		);

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

		$layout_columns_count = -1;
		if ($params['columns'] == '2x')
			$layout_columns_count = 2;
		if ($params['columns'] == '3x')
			$layout_columns_count = 3;
		if ($params['columns'] == '4x')
			$layout_columns_count = 4;

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

			if (is_numeric($params['search']) && get_post_status(absint($params['search']))) {
				$args['p'] = $params['search'];
			} else {
				$args['s'] = $params['search'];
			}

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

		if ($layout_columns_count == -1)
			$layout_columns_count = 5;

		$item_classes = get_thegem_extended_blog_render_item_classes($params);
		$thegem_sizes = get_thegem_extended_blog_render_item_image_sizes($params);

		$style = '';

		if (!empty($params['gaps_size'])) {
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

		if (!empty($params['gaps_size_tablet'])) {
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

		if (!empty($params['gaps_size_mobile'])) {
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

		if ($params['columns'] == '100%' || ((!$params['ignore_highlights'] || $params['layout'] !== 'justified') && !$params['skeleton_loader'])) {
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
				'portfolio portfolio-grid news-grid category-grid no-padding',
				'portfolio-pagination-' . $params['pagination_type'],
				'portfolio-style-' . $params['layout'],
				'background-style-' . $params['background_style'],
				'hover-' . $hover_effect,
				'title-on-' . $params['display_titles'],
				'version-' . $params['version'],
				($params['loading_animation'] !== 'disabled' ? 'loading-animation' : ''),
				($params['loading_animation'] !== 'disabled' ? 'item-animation-' . $params['animation_effect'] : ''),
				($params['gaps_size'] == 0 ? 'no-gaps' : ''),
				($params['columns'] == '100%' ? 'fullwidth-columns fullwidth-columns-' . $params['columns_100'] : ''),
				($params['version'] == 'new' || ($params['version'] == 'default' && $params['display_titles'] == 'hover') ? 'hover-' . $params['version'] . '-' . $params['hover'] : 'hover-' . $params['hover']),
				($params['display_titles'] == 'hover' ? 'hover-title' : ''),
				($params['layout'] == 'masonry' && $params['columns'] != '1x' ? 'portfolio-items-masonry' : ''),
				($layout_columns_count != -1 ? 'columns-' . intval($layout_columns_count) : ''),
				($params['layout'] == 'justified' && $params['ignore_highlights'] ? 'disable-isotope' : ''),
				($params['hide_featured_image'] && $params['display_titles'] == 'page' ? 'without-image' : ''),
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
					<div class="portfilio-top-panel<?php if ($params['columns'] == '100%'): ?> fullwidth-block<?php endif; ?>">
						<div class="portfilio-top-panel-row">
							<div class="portfilio-top-panel-left"></div>
							<div class="portfilio-top-panel-right">
								<?php if ($params['blog_show_sorting']): ?>
									<div class="portfolio-sorting title-h6">
										<div class="orderby light">
											<?php
											$query = $_GET;
											$orderby = 'date';
											if (isset($query['orderby']) && $query['orderby'] == 'title') {
												$orderby = 'title';
												$query['orderby'] = '';
											} else {
												$query['orderby'] = 'title';
											}
											if (isset($query['order']) && $query['orderby'] == 'asc') {
												$query['order'] = 'asc';
											} else {
												$query['order'] = 'desc';
											}
											// rebuild url
											$query_result_orderby = http_build_query($query);

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
											// rebuild url
											$query_result_order = http_build_query($query);

											$url = strtok($_SERVER["REQUEST_URI"], '?'); ?>

											<label for="" data-value="date"><?php _e('Date', 'thegem') ?></label>
											<a href="<?php echo $url; ?>?<?php echo $query_result_orderby; ?>" class="sorting-switcher <?php echo $orderby == 'title' ? 'right' : ''; ?>" data-current="<?php echo $orderby; ?>"></a>
											<label for="" data-value="name"><?php _e('Name', 'thegem') ?></label>
										</div>
										<div class="portfolio-sorting-sep"></div>
										<div class="order light">
											<label for="" data-value="DESC"><?php _e('Desc', 'thegem') ?></label>
											<a href="<?php echo $url; ?>?<?php echo $query_result_order; ?>" class="sorting-switcher <?php echo $order == 'asc' ? 'right' : ''; ?>" data-current="<?php echo $order; ?>"></a>
											<label for="" data-value="ASC"><?php _e('Asc', 'thegem') ?></label>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<div class="portfolio-row-outer <?php if ($params['columns'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
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
						<?php if ($params['columns'] != '1x'): ?>
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

											<a href="<?php echo get_site_url(); ?>?s=<?php echo esc_attr($params['search']); ?>" class="load-more-button gem-button gem-button-size-<?php echo $params['mixed_grids_show_all_button_size']; ?> gem-button-style-<?php echo $params['mixed_grids_show_all_button_type']; ?> gem-button-icon-position-left gem-button-text-weight-normal">
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
		if(!function_exists('thegem_get_template_type') || !(thegem_get_template_type( get_the_ID() ) === 'blog-archive' || is_category() || is_tag() || is_author() || is_date() || is_post_type_archive( 'post' ))) return false;
		$term_id = isset(get_queried_object()->term_id) ? get_queried_object()->term_id : 0;
		$blog_archive_data = thegem_get_output_blog_archive_data($term_id);
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