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