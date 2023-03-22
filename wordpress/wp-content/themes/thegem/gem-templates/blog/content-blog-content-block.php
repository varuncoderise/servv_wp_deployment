<div class="block-content<?php echo esc_attr($thegem_no_margins_block); ?>">
	<div class="container">
		<div class="<?php echo esc_attr(implode(' ', $thegem_panel_classes)); ?>">
			<div class="<?php echo esc_attr($thegem_center_classes); ?>">
				<?php
				if ( have_posts() ) {

					$queried_object = get_queried_object();

					if ((is_archive() || is_home()) && thegem_get_option('blog_layout_type') == 'grid') {

						$params = array(
							'layout' => thegem_get_option('blog_layout_type_grid'),
							'categories' => array('0'),
							'columns' => thegem_get_option('blog_layout_columns_desktop'), // blog_layout_columns_tablet: '3x', blog_layout_columns_mobile: '2x',
							'columns_100' => thegem_get_option('blog_layout_columns_100'),
							'display_titles' => thegem_get_option('blog_layout_caption_position') == 'bellow' ? 'page' : 'hover',
							'version' => thegem_get_option('blog_layout_skin') == 'classic' ? 'default' : 'new',
							'gaps_size' => thegem_get_option('blog_layout_gaps_desktop'),
							'gaps_size_tablet' => thegem_get_option('blog_layout_gaps_tablet'),
							'gaps_size_mobile' => thegem_get_option('blog_layout_gaps_mobile'),
							'icon_hover_show' => thegem_get_option('blog_layout_icon_on_hover'),
							'blog_show_sorting' => thegem_get_option('blog_layout_sorting'),
							'hover' => str_replace("_", "-", thegem_get_option('blog_layout_hover_effect')),
							'hide_featured_image' => !thegem_get_option('blog_layout_caption_featured_image'),
							'blog_show_title' => thegem_get_option('blog_layout_caption_title'),
							'title_preset' => 'title-'.thegem_get_option('blog_layout_caption_title_preset'),
							'blog_show_description' => thegem_get_option('blog_layout_caption_description'),
							'hide_date' => !thegem_get_option('blog_layout_caption_date'),
							'hide_categories' => !thegem_get_option('blog_layout_caption_categories'),
							'hide_author' => !thegem_get_option('blog_layout_caption_author'),
							'hide_author_avatar' => !thegem_get_option('blog_layout_caption_author_avatar'),
							'by_text' => __('By', 'thegem'),
							'hide_comments' => !thegem_get_option('blog_layout_caption_comments'),
							'likes' => thegem_get_option('blog_layout_caption_likes'),
							'disable_socials' => !thegem_get_option('blog_layout_caption_socials'),
							'caption_container_alignment' => thegem_get_option('blog_layout_caption_content_alignment_desktop'),
							'caption_container_alignment_tablet' => thegem_get_option('blog_layout_caption_content_alignment_tablet'),
							'caption_container_alignment_mobile' => thegem_get_option('blog_layout_caption_content_alignment_mobile'),
							'background_style' => thegem_get_option('blog_layout_caption_container_preset'),
							'show_bottom_border' => thegem_get_option('blog_layout_caption_bottom_border'),
							'show_pagination' => thegem_get_option('blog_layout_pagination'),
							'items_per_page' => thegem_get_option('blog_layout_pagination_items_per_page'),
							'pagination_type' => thegem_get_option('blog_layout_pagination_type') == 'loadmore' ? 'more' : thegem_get_option('blog_layout_pagination_type'),
							'more_button_text' => thegem_get_option('blog_layout_load_more_text'),
							'more_icon_pack' => thegem_get_option('blog_layout_load_more_icon_pack'),
							'more_icon_' . thegem_get_option('blog_layout_load_more_icon_pack') => thegem_get_option('blog_layout_load_more_icon'),
							'more_stretch_full_width' => thegem_get_option('blog_layout_load_more_stretch'),
							'more_show_separator' => (!thegem_get_option('blog_layout_load_more_stretch') && thegem_get_option('blog_layout_load_more_separator')) ? 1 : '',
							'load_more_spacing' => thegem_get_option('blog_layout_load_more_spacing_desktop'),
							'load_more_spacing_tablet' => thegem_get_option('blog_layout_load_more_spacing_tablet'),
							'load_more_spacing_mobile' => thegem_get_option('blog_layout_load_more_spacing_mobile'),
							'pagination_more_button_type' => thegem_get_option('blog_layout_load_more_btn_type'),
							'pagination_more_button_size' => thegem_get_option('blog_layout_load_more_btn_size'),
							'loading_animation' => thegem_get_option('blog_layout_loading_animation') ? 1 : 'disabled',
							'animation_effect' => thegem_get_option('blog_layout_animation_effect'),
							'ignore_highlights' => thegem_get_option('blog_layout_ignore_highlights'),
							'skeleton_loader' => thegem_get_option('blog_layout_skeleton_loader'),
							'fullwidth_section_images' => '',
							'title_font_weight' => '',
						);

						if ($params['hide_featured_image'] && $params['layout'] == 'metro') {
							$params['layout'] = 'justified';
						}

						wp_enqueue_style('thegem-news-grid');
						wp_enqueue_script('thegem-portfolio-grid-extended');
						$grid_uid = 'blog_grid';

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

						$layout_columns_count = -1;
						if ($params['columns'] == '2x')
							$layout_columns_count = 2;
						if ($params['columns'] == '3x')
							$layout_columns_count = 3;
						if ($params['columns'] == '4x')
							$layout_columns_count = 4;

						$items_per_page = $params['items_per_page'] ? intval($params['items_per_page']) : intval(get_option( 'posts_per_page' ));

						$page = get_query_var('paged') ?: 1;
						$next_page = 0;

						if ($layout_columns_count == -1)
							$layout_columns_count = 5;

						global $wp_query;
						$max_page = $wp_query->max_num_pages;

						if ($max_page > $page)
							$next_page = $page + 1;
						else
							$next_page = 0;

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

						echo '<style>'.$style.'</style>';

						if ($params['columns'] == '100%' || ((!$params['ignore_highlights'] || $params['layout'] !== 'justified') && !$params['skeleton_loader'])) {
							echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="preloader-spin"></div></div>');
						} else if ($params['skeleton_loader']) { ?>
							<div class="preloader save-space">
								<div class="skeleton">
									<div class="skeleton-posts portfolio-row">
										<?php while (have_posts()) : the_post();
											echo thegem_extended_blog_render_item($params, $item_classes);
										endwhile; ?>
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
							     data-portfolio-filter="<?php echo $queried_object ? esc_attr($queried_object->slug) : ''; ?>">
								<?php if ($params['blog_show_sorting']): ?>
									<div class="portfilio-top-panel<?php if ($params['columns'] == '100%'): ?> fullwidth-block<?php endif; ?>">
										<div class="portfilio-top-panel-row">
											<div class="portfilio-top-panel-left"></div>
											<div class="portfilio-top-panel-right">
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
											</div>
										</div>
									</div>
								<?php endif; ?>
								<div class="portfolio-row-outer <?php if ($params['columns'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
									<div class="row portfolio-row">
										<div class="portfolio-set clearfix"
										     data-max-row-height="">
											<?php while (have_posts()) : the_post();
												echo thegem_extended_blog_render_item($params, $item_classes, $thegem_sizes, get_the_ID());
											endwhile; ?>
										</div><!-- .portflio-set -->
										<?php if ($params['columns'] != '1x'): ?>
											<div class="portfolio-item-size-container">
												<?php echo thegem_extended_blog_render_item($params, $item_classes); ?>
											</div>
										<?php endif; ?>
									</div><!-- .row-->
									<?php
									if ($params['show_pagination']) {
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
									} ?>
								</div><!-- .full-width -->
							</div><!-- .portfolio-->
						</div><!-- .portfolio-preloader-wrapper-->

						<?php

					} else {
						if (!is_singular()) {
							wp_enqueue_style('thegem-blog');
							wp_enqueue_style('thegem-additional-blog');
							wp_enqueue_style('thegem-blog-timeline-new');
							wp_enqueue_script('thegem-scroll-monitor');
							wp_enqueue_script('thegem-items-animations');
							wp_enqueue_script('thegem-blog');
							wp_enqueue_script('thegem-gallery');
							echo '<div class="blog blog-style-default">';
						}

						while (have_posts()) : the_post();

							get_template_part('content', 'blog-item');

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