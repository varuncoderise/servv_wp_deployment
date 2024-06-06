<div class="block-content<?php echo esc_attr($thegem_no_margins_block); ?>">
	<div class="container">
		<div class="<?php echo esc_attr(implode(' ', $thegem_panel_classes)); ?>">
			<div class="<?php echo esc_attr($thegem_center_classes); ?>">
				<?php
				if ( have_posts() ) {

					$is_blog = !isset($post_type_name) || $post_type_name == 'post';

					if (!$is_blog) {
						if(!empty($thegem_term_id)) {
							$content_settings = thegem_get_output_page_settings(0, thegem_theme_options_get_page_settings($post_type_name.'_archive'), 'cpt_archive');
						} else {
							$content_settings = $thegem_output_settings;
						}
					}

					if ((is_archive() || is_home()) && ((!$is_blog && $content_settings['archive_layout_type'] == 'grid') || ($is_blog && thegem_get_option('blog_layout_type') == 'grid'))) {

						if ($is_blog) {
							$params = array(
								'query_type' => 'archive',
								'archive_post_type' => 'any',
								'layout' => thegem_get_option('blog_layout_type_grid'),
								'categories' => array('0'),
								'orderby' => thegem_get_option('blog_layout_sorting_default_orderby') != 'default' ? thegem_get_option('blog_layout_sorting_default_orderby') : '',
								'order' => thegem_get_option('blog_layout_sorting_default_order') != 'default' ? thegem_get_option('blog_layout_sorting_default_order') : '',
								'columns_desktop' => thegem_get_option('blog_layout_columns_desktop'),
								'columns_tablet' => thegem_get_option('blog_layout_columns_tablet'),
								'columns_mobile' => thegem_get_option('blog_layout_columns_mobile'),
								'columns_100' => thegem_get_option('blog_layout_columns_100'),
								'display_titles' => thegem_get_option('blog_layout_caption_position') == 'bellow' ? 'page' : 'hover',
								'version' => thegem_get_option('blog_layout_skin') == 'classic' ? 'default' : 'new',
								'gaps_size' => thegem_get_option('blog_layout_gaps_desktop'),
								'gaps_size_tablet' => thegem_get_option('blog_layout_gaps_tablet'),
								'gaps_size_mobile' => thegem_get_option('blog_layout_gaps_mobile'),
								'image_size' => thegem_get_option('blog_layout_image_size'),
								'image_ratio_full' => thegem_get_option('blog_layout_image_ratio_full'),
								'image_ratio_default' => thegem_get_option('blog_layout_image_ratio_default'),
								'icon_hover_show' => thegem_get_option('blog_layout_icon_on_hover'),
								'blog_show_sorting' => thegem_get_option('blog_layout_sorting'),
								'hover' => str_replace("_", "-", thegem_get_option('blog_layout_hover_effect')),
								'hide_featured_image' => !thegem_get_option('blog_layout_caption_featured_image'),
								'blog_show_title' => thegem_get_option('blog_layout_caption_title'),
								'title_preset' => 'title-' . thegem_get_option('blog_layout_caption_title_preset'),
								'truncate_titles' => thegem_get_option('blog_layout_caption_truncate_titles'),
								'blog_show_description' => thegem_get_option('blog_layout_caption_description'),
								'truncate_description' => thegem_get_option('blog_layout_caption_truncate_description'),
								'hide_date' => !thegem_get_option('blog_layout_caption_date'),
								'show_additional_meta' => thegem_get_option('blog_layout_caption_categories'),
								'additional_meta_taxonomies' => 'category',
								'additional_meta_click_behavior' => 'archive_link',
								'hide_author' => !thegem_get_option('blog_layout_caption_author'),
								'hide_author_avatar' => !thegem_get_option('blog_layout_caption_author_avatar'),
								'by_text' => __('By', 'thegem'),
								'hide_comments' => !thegem_get_option('blog_layout_caption_comments'),
								'likes' => thegem_get_option('blog_layout_caption_likes'),
								'disable_socials' => !thegem_get_option('blog_layout_caption_socials'),
								'show_readmore_button' => thegem_get_option('blog_layout_caption_read_more'),
								'readmore_button_text' => thegem_get_option('blog_layout_caption_read_more_text'),
								'caption_container_alignment' => thegem_get_option('blog_layout_caption_content_alignment_desktop'),
								'caption_container_alignment_tablet' => thegem_get_option('blog_layout_caption_content_alignment_tablet'),
								'caption_container_alignment_mobile' => thegem_get_option('blog_layout_caption_content_alignment_mobile'),
								'background_style' => thegem_get_option('blog_layout_caption_container_preset'),
								'show_bottom_border' => thegem_get_option('blog_layout_caption_bottom_border'),
								'show_pagination' => thegem_get_option('blog_layout_pagination'),
								'items_per_page' => thegem_get_option('blog_layout_pagination_items_per_page'),
								'pagination_type' => thegem_get_option('blog_layout_pagination_type') == 'loadmore' ? 'more' : thegem_get_option('blog_layout_pagination_type'),
								'reduce_html_size' => thegem_get_option('blog_layout_pagination_reduce_html'),
								'items_on_load' => thegem_get_option('blog_layout_pagination_reduce_html_items_count'),
								'more_button_text' => thegem_get_option('blog_layout_load_more_text'),
								'more_icon_pack' => thegem_get_option('blog_layout_load_more_icon_pack'),
								'more_icon_' . thegem_get_option('blog_layout_load_more_icon_pack') => thegem_get_option('blog_layout_load_more_icon'),
								'more_stretch_full_width' => thegem_get_option('blog_layout_load_more_stretch'),
								'more_show_separator' => !thegem_get_option('blog_layout_load_more_stretch') && thegem_get_option('blog_layout_load_more_separator'),
								'load_more_spacing' => thegem_get_option('blog_layout_load_more_spacing_desktop'),
								'load_more_spacing_tablet' => thegem_get_option('blog_layout_load_more_spacing_tablet'),
								'load_more_spacing_mobile' => thegem_get_option('blog_layout_load_more_spacing_mobile'),
								'pagination_more_button_type' => thegem_get_option('blog_layout_load_more_btn_type'),
								'pagination_more_button_size' => thegem_get_option('blog_layout_load_more_btn_size'),
								'loading_animation' => thegem_get_option('blog_layout_loading_animation') ? 1 : 'disabled',
								'animation_effect' => thegem_get_option('blog_layout_animation_effect'),
								'ignore_highlights' => thegem_get_option('blog_layout_ignore_highlights'),
								'skeleton_loader' => thegem_get_option('blog_layout_skeleton_loader'),
								'ajax_preloader_type' => thegem_get_option('blog_layout_ajax_preloader_type'),
								'fullwidth_section_images' => '',
								'title_font_weight' => '',
								'skin_source' => thegem_get_option('blog_skin_source'),
								'loop_builder' => thegem_get_option('blog_archive_item_builder_template'),
								'equal_height' => !thegem_get_option('blog_archive_items_equal_height_disabled'),
							);

						} else {

							$params = array(
								'query_type' => 'archive',
								'archive_post_type' => 'any',
								'layout' => $content_settings['archive_layout_type_grid'],
								'categories' => array('0'),
								'orderby' => $content_settings['archive_layout_sorting_default_orderby'] != 'default' ? $content_settings['archive_layout_sorting_default_orderby'] : '',
								'order' => $content_settings['archive_layout_sorting_default_order'] != 'default' ? $content_settings['archive_layout_sorting_default_order'] : '',
								'columns_desktop' => $content_settings['archive_layout_columns_desktop'],
								'columns_tablet' => $content_settings['archive_layout_columns_tablet'],
								'columns_mobile' => $content_settings['archive_layout_columns_mobile'],
								'columns_100' => $content_settings['archive_layout_columns_100'],
								'display_titles' => $content_settings['archive_layout_caption_position'] == 'bellow' ? 'page' : 'hover',
								'version' => $content_settings['archive_layout_skin'] == 'classic' ? 'default' : 'new',
								'gaps_size' => $content_settings['archive_layout_gaps_desktop'],
								'gaps_size_tablet' => $content_settings['archive_layout_gaps_tablet'],
								'gaps_size_mobile' => $content_settings['archive_layout_gaps_mobile'],
								'image_size' => $content_settings['archive_layout_image_size'],
								'image_ratio_full' => $content_settings['archive_layout_image_ratio_full'],
								'image_ratio_default' => $content_settings['archive_layout_image_ratio_default'],
								'icon_hover_show' => $content_settings['archive_layout_icon_on_hover'],
								'blog_show_sorting' => $content_settings['archive_layout_sorting'],
								'hover' => str_replace("_", "-", $content_settings['archive_layout_hover_effect']),
								'hide_featured_image' => !$content_settings['archive_layout_caption_featured_image'],
								'blog_show_title' => $content_settings['archive_layout_caption_title'],
								'title_preset' => 'title-'.$content_settings['archive_layout_caption_title_preset'],
								'truncate_titles' => $content_settings['archive_layout_caption_truncate_titles'],
								'blog_show_description' => $content_settings['archive_layout_caption_description'],
								'truncate_description' => $content_settings['archive_layout_caption_truncate_description'],
								'hide_date' => !$content_settings['archive_layout_caption_date'],
								'show_additional_meta' => $content_settings['archive_layout_caption_categories'],
								'additional_meta_taxonomies' => 'category',
								'additional_meta_click_behavior' => 'archive_link',
								'hide_author' => !$content_settings['archive_layout_caption_author'],
								'hide_author_avatar' => !$content_settings['archive_layout_caption_author_avatar'],
								'by_text' => __('By', 'thegem'),
								'hide_comments' => !$content_settings['archive_layout_caption_comments'],
								'likes' => $content_settings['archive_layout_caption_likes'],
								'disable_socials' => !$content_settings['archive_layout_caption_socials'],
								'show_readmore_button' => $content_settings['archive_layout_caption_read_more'],
								'readmore_button_text' => $content_settings['archive_layout_caption_read_more_text'],
								'caption_container_alignment' => $content_settings['archive_layout_caption_content_alignment_desktop'],
								'caption_container_alignment_tablet' => $content_settings['archive_layout_caption_content_alignment_tablet'],
								'caption_container_alignment_mobile' => $content_settings['archive_layout_caption_content_alignment_mobile'],
								'background_style' => $content_settings['archive_layout_caption_container_preset'],
								'show_bottom_border' => $content_settings['archive_layout_caption_bottom_border'],
								'show_pagination' => $content_settings['archive_layout_pagination'],
								'items_per_page' => $content_settings['archive_layout_pagination_items_per_page'],
								'pagination_type' => $content_settings['archive_layout_pagination_type'] == 'loadmore' ? 'more' : $content_settings['archive_layout_pagination_type'],
								'reduce_html_size' => $content_settings['archive_layout_pagination_reduce_html'],
								'items_on_load' => $content_settings['archive_layout_pagination_reduce_html_items_count'],
								'more_button_text' => $content_settings['archive_layout_load_more_text'],
								'more_icon_pack' => $content_settings['archive_layout_load_more_icon_pack'],
								'more_icon_' . $content_settings['archive_layout_load_more_icon_pack'] => $content_settings['archive_layout_load_more_icon'],
								'more_stretch_full_width' => $content_settings['archive_layout_load_more_stretch'],
								'more_show_separator' => !$content_settings['archive_layout_load_more_stretch'] && $content_settings['archive_layout_load_more_separator'],
								'load_more_spacing' => $content_settings['archive_layout_load_more_spacing_desktop'],
								'load_more_spacing_tablet' => $content_settings['archive_layout_load_more_spacing_tablet'],
								'load_more_spacing_mobile' => $content_settings['archive_layout_load_more_spacing_mobile'],
								'pagination_more_button_type' => $content_settings['archive_layout_load_more_btn_type'],
								'pagination_more_button_size' => $content_settings['archive_layout_load_more_btn_size'],
								'loading_animation' => $content_settings['archive_layout_loading_animation'],
								'animation_effect' => $content_settings['archive_layout_animation_effect'],
								'ignore_highlights' => $content_settings['archive_layout_ignore_highlights'],
								'skeleton_loader' => $content_settings['archive_layout_skeleton_loader'],
								'ajax_preloader_type' => $content_settings['archive_layout_ajax_preloader_type'],
								'fullwidth_section_images' => '',
								'title_font_weight' => '',
								'skin_source' => $content_settings['archive_skin_source'],
								'loop_builder' => $content_settings['archive_item_builder_template'],
								'equal_height' => !$content_settings['archive_items_equal_height_disabled'],
							);

						}

						if($params['skin_source'] === 'builder') {
							$params['ignore_highlights'] = 1;
							$equal_height = !empty($params['equal_height']) && $params['layout'] === 'justified';
						}

						$taxonomy_filter = $blog_authors = $date_query = [];

						if (is_author()) {
							$blog_authors = $params['select_blog_authors'] = array(get_queried_object()->ID);
						} else if (is_category() || is_tag() || is_tax()) {
							$taxonomy_filter[get_queried_object()->taxonomy] = array(get_queried_object()->slug);
							$params['archive_tax_filter'] = $taxonomy_filter;
						} else if (is_date()) {
							if (!empty(get_query_var('year'))) {
								$date_query['year'] = get_query_var('year');
							}
							if (!empty(get_query_var('monthnum'))) {
								$date_query['month'] = get_query_var('monthnum');
							}
							if (!empty(get_query_var('day'))) {
								$date_query['day'] = get_query_var('day');
							}
							$params['date_query'] = $date_query;
						}

						if ($params['hide_featured_image'] && $params['layout'] == 'metro') {
							$params['layout'] = 'justified';
						}

						if (!empty($settings['image_ratio_default'])) {
							$settings['image_aspect_ratio'] = 'custom';
							$settings['image_ratio_custom'] = $settings['image_ratio_default'];
						}

						wp_enqueue_style('thegem-news-grid');
						wp_enqueue_script('thegem-portfolio-grid-extended');
						$grid_uid = 'blog_grid';
						$grid_uid_url = '';

						$localize = array(
							'data' => $params,
							'action' => 'blog_grid_extended_load_more',
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

						if ($params['pagination_type'] == 'more') {
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

						$page = get_query_var('paged') ?: 1;
						$next_page = 0;

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

						$orderby = $params['orderby'];
						$order = $params['order'];
						if ($params['blog_show_sorting']) {
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
							$url_result_order = add_query_arg($query);
						}

						$news_grid_loop = get_thegem_extended_blog_posts($post_type_name, $taxonomy_filter, [], [], [], $blog_authors, $page, $items_on_load, $orderby, $order, 0, '', '', '', $date_query);
						if ($params['reduce_html_size']) {
							$pagination_query = get_thegem_extended_blog_posts($post_type_name, $taxonomy_filter, [], [], [], $blog_authors, $page, $items_per_page, $orderby, $order, 0, '', '', '', $date_query);
						} else {
							$pagination_query = $news_grid_loop;
						}

						$max_page = ceil($news_grid_loop->found_posts / $items_per_page);

						if ($params['reduce_html_size']) {
							$next_page = $news_grid_loop->found_posts > $items_on_load ? 2 : 0;
						} else {
							$next_page = $max_page > $page ? $page + 1 : 0;
						}

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
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description { text-align: '.$params['caption_container_alignment'].'; }
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item:hover .image .links .caption .grid-post-meta { justify-content: '.$params['caption_container_alignment'].'; }';
						}

						if (!empty($params['caption_container_alignment_tablet'])) {
							$style .= '@media (max-width: 991px) {
										.portfolio.news-grid.category-grid.title-on-page .wrap > .caption,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description { text-align: '.$params['caption_container_alignment_tablet'].'; }
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item:hover .image .links .caption .grid-post-meta { justify-content: '.$params['caption_container_alignment_tablet'].'; }
									}';
						}

						if (!empty($params['caption_container_alignment_mobile'])) {
							$style .= '@media (max-width: 767px) {
										.portfolio.news-grid.category-grid.title-on-page .wrap > .caption,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description { text-align: '.$params['caption_container_alignment_mobile'].'; }
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

						if (!empty($params['truncate_titles'])) {
							$style .= '.portfolio.news-grid.category-grid .portfolio-item .caption .title span, 
							.portfolio.news-grid.category-grid .portfolio-item .caption .title a { white-space: initial; max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($params['truncate_titles']) . '; line-clamp: ' . esc_attr($params['truncate_titles']) . '; -webkit-box-orient: vertical; }';
						}

						if (!empty($params['truncate_description'])) {
							$style .= '.portfolio.news-grid.category-grid .portfolio-item .caption .description { max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($params['truncate_description']) . '; line-clamp: ' . esc_attr($params['truncate_description']) . '; -webkit-box-orient: vertical; }';
						}

						if (isset($params['image_size']) && $params['image_size'] == 'full' && !empty($params['image_ratio_full'])) {
							$style .= '.portfolio.news-grid.category-grid .portfolio-item:not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: ' . $params['image_ratio_full'] . ' !important; height: auto; }';
						}

						if (isset($params['image_size']) && $params['image_size'] == 'default' && !empty($params['image_ratio_default'])) {
							$style .= '.portfolio.news-grid.category-grid .portfolio-item:not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: ' . $params['image_ratio_default'] . ' !important; height: auto; }';
						}

						echo '<style>'.$style.'</style>';

						if ($params['columns_desktop'] == '100%' || ((!$params['ignore_highlights'] || $params['layout'] !== 'justified') && !$params['skeleton_loader'])) {
							$spin_class = 'preloader-spin';
							if ($params['ajax_preloader_type'] == 'minimal') {
								$spin_class = 'preloader-spin-new';
							}
							echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="' . $spin_class . '"></div></div>');
						} else if ($params['skeleton_loader']) { ?>
							<div class="preloader save-space">
								<div class="skeleton">
									<div class="skeleton-posts portfolio-row">
										<?php for ($x = 0; $x < $news_grid_loop->post_count; $x++) {
											echo thegem_extended_blog_render_item($params, $item_classes);
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
								(($params['image_size'] == 'full' && empty($params['image_ratio_full'])) || !in_array($params['image_size'], ['full', 'default']) ? 'full-image' : ''),
								($params['ajax_preloader_type'] == 'minimal' ? 'minimal-preloader' : ''),
								($params['reduce_html_size'] ? 'reduce-size' : ''),
								(!empty($equal_height) ? 'loop-equal-height' : ''),
							];
							?>

							<div class="<?php echo implode(" ", $blog_wrap_class); ?>"
							     data-portfolio-uid="<?php echo esc_attr($grid_uid); ?>"
							     data-current-page="<?php echo esc_attr($page); ?>"
							     data-per-page="<?php echo esc_attr($items_per_page); ?>"
							     data-next-page="<?php echo esc_attr($next_page); ?>"
							     data-pages-count="<?php echo esc_attr($max_page); ?>"
							     data-hover="<?php echo esc_attr($hover_effect); ?>">
								<?php if ($params['blog_show_sorting']): ?>
									<div class="portfolio-top-panel<?php if ($params['columns_desktop'] == '100%'): ?> fullwidth-block<?php endif; ?>">
										<div class="portfolio-top-panel-row">
											<div class="portfolio-top-panel-left"></div>
											<div class="portfolio-top-panel-right">
												<div class="portfolio-sorting title-h6">
													<div class="orderby light">
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
											<?php if ($news_grid_loop->have_posts()) {
												while ($news_grid_loop->have_posts()) {
													$news_grid_loop->the_post();
													echo thegem_extended_blog_render_item($params, $item_classes, $thegem_sizes, get_the_ID());
												}
											} ?>
										</div><!-- .portflio-set -->
										<?php if ($params['columns_desktop'] != '1x'): ?>
											<div class="portfolio-item-size-container">
												<?php echo thegem_extended_blog_render_item($params, $item_classes); ?>
											</div>
										<?php endif; ?>
									</div><!-- .row-->
									<?php
									if ($params['show_pagination']) {
										if ($params['pagination_type'] == 'normal') {
											thegem_pagination($pagination_query);
										} else if ($params['pagination_type'] == 'more' && $pagination_query->max_num_pages > 1) {

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
										} else if ($params['pagination_type'] == 'scroll' && $pagination_query->max_num_pages > 1) { ?>
											<div class="portfolio-scroll-pagination"></div>
										<?php }
									} ?>
								</div><!-- .full-width -->
							</div><!-- .portfolio-->
						</div><!-- .portfolio-preloader-wrapper-->

						<?php

					} else {
						if ($is_blog) {
							$params = array(
								'skin_source' => thegem_get_option('blog_skin_source'),
								'loop_builder' => thegem_get_option('blog_archive_item_builder_template'),
								'gaps_desktop' => thegem_get_option('blog_list_builder_gaps_desktop'),
								'gaps_tablet' => thegem_get_option('blog_list_builder_gaps_tablet'),
							);
						} else {
							$params = array(
								'skin_source' => $content_settings['archive_skin_source'],
								'loop_builder' => $content_settings['archive_item_builder_template'],
								'gaps_desktop' => $content_settings['archive_list_builder_gaps_desktop'],
								'gaps_tablet' => $content_settings['archive_list_builder_gaps_tablet'],
							);
						}
						if (!is_singular()) {
							wp_enqueue_style('thegem-blog');
							wp_enqueue_style('thegem-additional-blog');
							wp_enqueue_style('thegem-blog-timeline-new');
							wp_enqueue_script('thegem-scroll-monitor');
							wp_enqueue_script('thegem-items-animations');
							wp_enqueue_script('thegem-blog');
							wp_enqueue_script('thegem-gallery');
							if(!empty($params['skin_source']) && $params['skin_source'] === 'builder' && !empty($params['loop_builder'])) {
								$params['gaps_desktop'] = intval($params['gaps_desktop']) > 0 || $params['gaps_desktop'] === '0' ? intval($params['gaps_desktop']) : 42;
								$params['gaps_tablet'] = intval($params['gaps_tablet']) > 0 || $params['gaps_tablet'] === '0' ? intval($params['gaps_tablet']) : 42;
								echo '<style type="text/css">';
								echo thegem_generate_css(
									array('rules' => array(array(
										'selector' => '.blog .thegem-template-loop-item.thegem-template-'.esc_attr($params['loop_builder']),
										'styles' => array(
											'margin-bottom' => $params['gaps_desktop'].'px',
										)
									)))
								);
								echo thegem_generate_css(
									array('media' => '(max-width: 1023px)', 'rules' => array(array(
										'selector' => '.blog .thegem-template-loop-item.thegem-template-'.esc_attr($params['loop_builder']),
										'styles' => array(
											'margin-bottom' => $params['gaps_tablet'].'px',
										)
									)))
								);
								echo '</style>';
							}
							echo '<div class="blog blog-style-default">';
						}

						while (have_posts()) : the_post();

							if(!empty($params['skin_source']) && $params['skin_source'] === 'builder' && !empty($params['loop_builder'])) {
?>
<div <?php post_class(); ?> data-default-sort="<?php echo intval(get_post()->menu_order); ?>" data-sort-date="<?php echo get_the_date('U'); ?>">
	<div class="thegem-template-wrapper thegem-template-loop-item thegem-template-<?php echo esc_attr($params['loop_builder']); ?> thegem-loop-post-<?= esc_attr(get_the_id());?>">
		<?php
		$thegem_template_type_outer = isset($GLOBALS['thegem_template_type']) ? $GLOBALS['thegem_template_type'] : '';
		$GLOBALS['thegem_template_type'] = 'loop-item';
		$GLOBALS['thegem_loop_item_post'] = get_the_id();
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
								get_template_part('content', 'blog-item');
							}

						endwhile;

						if (!is_singular()) {
							thegem_pagination();
							echo '</div>';
						}
					}
				} else {
					get_template_part( 'content', 'none' );
				}
				?>
			</div>
			<?php
			if(is_active_sidebar('page-sidebar') && $thegem_page_data['sidebar']['sidebar_show'] && !empty($thegem_page_data['sidebar']['sidebar_position'])) {
				echo '<div class="sidebar col-lg-3 col-md-3 col-sm-12'.esc_attr($thegem_sidebar_classes).'" role="complementary">';
				get_sidebar('page');
				echo '</div><!-- .sidebar -->';
			}
			?>
		</div>
	</div><!-- .container -->
</div><!-- .block-content -->