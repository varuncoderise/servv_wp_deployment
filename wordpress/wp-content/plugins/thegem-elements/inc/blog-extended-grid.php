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