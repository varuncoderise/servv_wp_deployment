<div class="block-content<?php echo esc_attr($thegem_no_margins_block); ?>">
	<div class="container">
		<div class="<?php echo esc_attr(implode(' ', $thegem_panel_classes)); ?>">
			<div class="<?php echo esc_attr($thegem_center_classes); ?>">
				<?php
				if ( have_posts() ) {

					$queried_object = get_queried_object();

					if ((is_archive() || is_home()) && thegem_get_option('blog_layout_type') == 'grid') {

						$settings = array(
							'layout' => thegem_get_option('blog_layout_type_grid'),
							'categories' => array('0'),
							'columns' => thegem_get_option('blog_layout_columns_desktop'), // blog_layout_columns_tablet: '3x', blog_layout_columns_mobile: '2x',
							'columns_100' => thegem_get_option('blog_layout_columns_100'),
							'caption_position' => thegem_get_option('blog_layout_caption_position') == 'bellow' ? 'page' : 'hover',
							'thegem_elementor_preset' => thegem_get_option('blog_layout_skin') == 'classic' ? 'default' : 'new',
							'image_gaps' => ['size' => thegem_get_option('blog_layout_gaps_desktop'), 'unit' => 'px'],
							'image_gaps_tablet' => ['size' => thegem_get_option('blog_layout_gaps_tablet')],
							'image_gaps_mobile' => ['size' => thegem_get_option('blog_layout_gaps_mobile')],
							'icon_hover_show' => thegem_get_option('blog_layout_icon_on_hover') == 1 ? 'yes' : '',
							'blog_show_sorting' => thegem_get_option('blog_layout_sorting') == 1 ? 'yes' : '',
							'image_hover_effect' => str_replace("_", "-", thegem_get_option('blog_layout_hover_effect')),
							'blog_show_featured_image' => thegem_get_option('blog_layout_caption_featured_image') == 1 ? 'yes' : '',
							'blog_show_title' => thegem_get_option('blog_layout_caption_title') == 1 ? 'yes' : '',
							'blog_title_preset' => 'title-'.thegem_get_option('blog_layout_caption_title_preset'),
							'blog_show_description' => thegem_get_option('blog_layout_caption_description') == 1 ? 'yes' : '',
							'blog_show_date' => thegem_get_option('blog_layout_caption_date') == 1 ? 'yes' : '',
							'blog_show_categories' => thegem_get_option('blog_layout_caption_categories') == 1 ? 'yes' : '',
							'blog_show_author' => thegem_get_option('blog_layout_caption_author') == 1 ? 'yes' : '',
							'blog_show_author_avatar' => thegem_get_option('blog_layout_caption_author_avatar') == 1 ? 'yes' : '',
							'by_text' => __('By', 'thegem'),
							'blog_show_comments' => thegem_get_option('blog_layout_caption_comments') == 1 ? 'yes' : '',
							'blog_show_likes' => thegem_get_option('blog_layout_caption_likes') == 1 ? 'yes' : '',
							'social_sharing' => thegem_get_option('blog_layout_caption_socials') == 1 ? 'yes' : '',
							'caption_container_alignment' => thegem_get_option('blog_layout_caption_content_alignment_desktop'),
							'caption_container_alignment_tablet' => thegem_get_option('blog_layout_caption_content_alignment_tablet'),
							'caption_container_alignment_mobile' => thegem_get_option('blog_layout_caption_content_alignment_mobile'),
							'caption_container_preset' => thegem_get_option('blog_layout_caption_container_preset'),
							'show_bottom_border' => thegem_get_option('blog_layout_caption_bottom_border') == 1 ? 'yes' : '',
							'show_pagination' => thegem_get_option('blog_layout_pagination') == 1 ? 'yes' : '',
							'items_per_page' => thegem_get_option('blog_layout_pagination_items_per_page'),
							'pagination_type' => thegem_get_option('blog_layout_pagination_type') == 'loadmore' ? 'more' : thegem_get_option('blog_layout_pagination_type'),
							'more_button_text' => thegem_get_option('blog_layout_load_more_text'),
							'more_icon_pack' => thegem_get_option('blog_layout_load_more_icon_pack'),
							'more_icon_' . thegem_get_option('blog_layout_load_more_icon_pack') => thegem_get_option('blog_layout_load_more_icon'),
							'more_stretch_full_width' => thegem_get_option('blog_layout_load_more_stretch') == 1 ? 'yes' : '',
							'more_show_separator' => (thegem_get_option('blog_layout_load_more_stretch') != 1 && thegem_get_option('blog_layout_load_more_separator') == 1) ? 'yes' : '',
							'load_more_spacing' => thegem_get_option('blog_layout_load_more_spacing_desktop'),
							'load_more_spacing_tablet' => thegem_get_option('blog_layout_load_more_spacing_tablet'),
							'load_more_spacing_mobile' => thegem_get_option('blog_layout_load_more_spacing_mobile'),
							'pagination_more_button_type' => thegem_get_option('blog_layout_load_more_btn_type'),
							'pagination_more_button_size' => thegem_get_option('blog_layout_load_more_btn_size'),
							'loading_animation' => thegem_get_option('blog_layout_loading_animation') == 1 ? 'yes' : '',
							'animation_effect' => thegem_get_option('blog_layout_animation_effect'),
							'ignore_highlights' => thegem_get_option('blog_layout_ignore_highlights') == 1 ? 'yes' : '',
							'skeleton_loader' => thegem_get_option('blog_layout_skeleton_loader') == 1 ? 'yes' : '',
							'fullwidth_section_images' => '',
							'title_weight' => '',
						);

						if ($settings['blog_show_featured_image'] == '' && $settings['layout'] == 'metro') {
							$settings['layout'] = 'justified';
						}

						wp_enqueue_style('thegem-news-grid');
						wp_enqueue_script('thegem-portfolio-grid-extended');
						$grid_uid = 'blog_grid';

						$localize = array(
							'data' => $settings,
							'action' => 'blog_grid_extended_load_more',
							'url' => admin_url('admin-ajax.php'),
							'nonce' => wp_create_nonce('portfolio_ajax-nonce')
						);
						wp_localize_script('thegem-portfolio-grid-extended', 'thegem_portfolio_ajax_'. $grid_uid, $localize );
						$settings['action'] = 'blog_grid_extended_load_more';

						switch ($settings['thegem_elementor_preset']) {
							case 'default':
								if ($settings['caption_position'] == 'hover') {
									$hover_effect = $settings['thegem_elementor_preset'] . '-' . $settings['image_hover_effect'];
									wp_enqueue_style('thegem-news-grid-version-default-hovers-' . $settings['image_hover_effect']);
								} else {
									$hover_effect = $settings['image_hover_effect'];
									wp_enqueue_style('thegem-hovers-' . $settings['image_hover_effect']);
									wp_enqueue_style('thegem-news-grid-hovers');
								}
								break;
							case 'new':
								$hover_effect = $settings['thegem_elementor_preset'] . '-' . $settings['image_hover_effect'];
								wp_enqueue_style('thegem-news-grid-version-new-hovers-' . $settings['image_hover_effect']);
								break;
						}

						if ($settings['loading_animation'] === 'yes') {
							wp_enqueue_style('thegem-animations');
							wp_enqueue_script('thegem-items-animations');
							wp_enqueue_script('thegem-scroll-monitor');
						}

						if ($settings['pagination_type'] == 'more') {
							wp_enqueue_style('thegem-button');
						} else if ($settings['pagination_type'] == 'scroll') {
							wp_enqueue_script('thegem-scroll-monitor');
						}

						if ($settings['layout'] !== 'justified' || $settings['ignore_highlights'] !== 'yes') {

							if ($settings['layout'] == 'metro') {
								wp_enqueue_script('thegem-isotope-metro');
							} else {
								wp_enqueue_script('thegem-isotope-masonry-custom');
							}
						}

						$layout_columns_count = -1;
						if ($settings['columns'] == '2x')
							$layout_columns_count = 2;
						if ($settings['columns'] == '3x')
							$layout_columns_count = 3;
						if ($settings['columns'] == '4x')
							$layout_columns_count = 4;

						$items_per_page = $settings['items_per_page'] ? intval($settings['items_per_page']) : 8;

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

						$item_classes = get_thegem_extended_blog_render_item_classes($settings);
						$thegem_sizes = get_thegem_extended_blog_render_item_image_sizes($settings);

						$style = '';

						if (!empty($settings['image_gaps']['size'])) {
							$gaps_size = $settings['image_gaps']['size'];

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

						if (!empty($settings['image_gaps_tablet']['size'])) {
							$gaps_size = $settings['image_gaps_tablet']['size'];

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

						if (!empty($settings['image_gaps_mobile']['size'])) {
							$gaps_size = $settings['image_gaps_mobile']['size'];

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

						if (!empty($settings['caption_container_alignment'])) {
							$style .= '.portfolio.news-grid.category-grid.title-on-page .wrap > .caption,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description { text-align: '.$settings['caption_container_alignment'].'; }
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item:hover .image .links .caption .grid-post-meta { justify-content: '.$settings['caption_container_alignment'].'; }';
						}

						if (!empty($settings['caption_container_alignment_tablet'])) {
							$style .= '@media (max-width: 991px) {
										.portfolio.news-grid.category-grid.title-on-page .wrap > .caption,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description { text-align: '.$settings['caption_container_alignment_tablet'].'; }
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item:hover .image .links .caption .grid-post-meta { justify-content: '.$settings['caption_container_alignment_tablet'].'; }
									}';
						}

						if (!empty($settings['caption_container_alignment_mobile'])) {
							$style .= '@media (max-width: 767px) {
										.portfolio.news-grid.category-grid.title-on-page .wrap > .caption,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description { text-align: '.$settings['caption_container_alignment_mobile'].'; }
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item:hover .image .links .caption .grid-post-meta { justify-content: '.$settings['caption_container_alignment_mobile'].'; }
									}';
						}

						if ($settings['caption_container_preset'] == 'transparent' && $settings['show_bottom_border'] == 'yes') {
							$style .= '.portfolio.news-grid.category-grid .portfolio-item .wrap > .caption {
									border-bottom-width: 1px !important;
								}';
						}

						if (!empty($settings['load_more_spacing'])) {
							$style .= '.portfolio.news-grid.category-grid .portfolio-load-more { margin-top: '.$settings['load_more_spacing'].'px; }';
						}

						if (!empty($settings['load_more_spacing_tablet'])) {
							$style .= '@media (max-width: 991px) {
										.portfolio.news-grid.category-grid .portfolio-load-more { margin-top: '.$settings['load_more_spacing_tablet'].'px; }
									}';
						}

						if (!empty($settings['load_more_spacing_mobile'])) {
							$style .= '@media (max-width: 767px) {
										.portfolio.news-grid.category-grid .portfolio-load-more { margin-top: '.$settings['load_more_spacing_mobile'].'px; }
									}';
						}

						echo '<style>'.$style.'</style>';

						if ($settings['columns'] == '100%' || (($settings['ignore_highlights'] !== 'yes' || $settings['layout'] !== 'justified') && $settings['skeleton_loader'] !== 'yes')) {
							echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="preloader-spin"></div></div>');
						} else if ($settings['skeleton_loader'] == 'yes') { ?>
							<div class="preloader save-space">
								<div class="skeleton">
									<div class="skeleton-posts portfolio-row">
										<?php while (have_posts()) : the_post();
											echo thegem_extended_blog_render_item($settings, $item_classes);
										endwhile; ?>
									</div>
								</div>
							</div>
						<?php } ?>
						<div class="portfolio-preloader-wrapper">

							<?php
							$blog_wrap_class = [
								'portfolio portfolio-grid news-grid category-grid no-padding',
								'portfolio-pagination-' . $settings['pagination_type'],
								'portfolio-style-' . $settings['layout'],
								'background-style-' . $settings['caption_container_preset'],
								'hover-' . $hover_effect,
								'title-on-' . $settings['caption_position'],
								'version-' . $settings['thegem_elementor_preset'],
								($settings['loading_animation'] == 'yes' ? 'loading-animation' : ''),
								($settings['loading_animation'] == 'yes' && $settings['animation_effect'] ? 'item-animation-' . $settings['animation_effect'] : ''),
								($settings['image_gaps']['size'] == 0 ? 'no-gaps' : ''),
								($settings['columns'] == '100%' ? 'fullwidth-columns fullwidth-columns-' . $settings['columns_100'] : ''),
								($settings['thegem_elementor_preset'] == 'new' || ($settings['thegem_elementor_preset'] == 'default' && $settings['caption_position'] == 'hover') ? 'hover-' . $settings['thegem_elementor_preset'] . '-' . $settings['image_hover_effect'] : 'hover-' . $settings['image_hover_effect']),
								($settings['caption_position'] == 'hover' ? 'hover-title' : ''),
								($settings['layout'] == 'masonry' && $settings['columns'] != '1x' ? 'portfolio-items-masonry' : ''),
								($layout_columns_count != -1 ? 'columns-' . intval($layout_columns_count) : ''),
								($settings['layout'] == 'justified' && $settings['ignore_highlights'] == 'yes' ? 'disable-isotope' : ''),
								($settings['blog_show_featured_image'] == '' && $settings['caption_position'] == 'page' ? 'without-image' : ''),
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
								<?php if ($settings['blog_show_sorting'] == 'yes'): ?>
									<div class="portfilio-top-panel<?php if ($settings['columns'] == '100%'): ?> fullwidth-block<?php endif; ?>">
										<div class="portfilio-top-panel-row">
											<div class="portfilio-top-panel-left"></div>
											<div class="portfilio-top-panel-right">
												<?php if ($settings['blog_show_sorting'] == 'yes'): ?>
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
												<?php endif; ?>
											</div>
										</div>
									</div>
								<?php endif; ?>
								<div class="portfolio-row-outer <?php if ($settings['columns'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
									<div class="row portfolio-row">
										<div class="portfolio-set clearfix"
											 data-max-row-height="">
											<?php while (have_posts()) : the_post();
												echo thegem_extended_blog_render_item($settings, $item_classes, $thegem_sizes, get_the_ID());
											endwhile; ?>
										</div><!-- .portflio-set -->
										<?php if ($settings['columns'] != '1x'): ?>
											<div class="portfolio-item-size-container">
												<?php echo thegem_extended_blog_render_item($settings, $item_classes); ?>
											</div>
										<?php endif; ?>
									</div><!-- .row-->
									<?php
									if ($settings['show_pagination'] == 'yes') {
										if ($settings['pagination_type'] == 'normal') {
											thegem_pagination();
										} else if ($settings['pagination_type'] == 'more' && $next_page > 0) {

											$separator_enabled = !empty($settings['more_show_separator']) ? true : false;

											// Container
											$classes_container = 'gem-button-container gem-widget-button ';

											if ($separator_enabled) {
												$classes_container .= 'gem-button-position-center gem-button-with-separator ';
											} else {
												if ('yes' === $settings['more_stretch_full_width']) {
													$classes_container .= 'gem-button-position-fullwidth ';
												}
											}

											// Separator
											$classes_separator = 'gem-button-separator gem-button-separator-type-single';

											if (!empty($settings['pagination_more_button_separator_style_active'])) {
												$classes_separator .= esc_attr($settings['pagination_more_button_separator_style_active']);
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

																<button class="load-more-button gem-button gem-button-size-<?php echo $settings['pagination_more_button_size']; ?> gem-button-style-<?php echo $settings['pagination_more_button_type']; ?> gem-button-icon-position-left gem-button-text-weight-normal">
																		<span class="gem-inner-wrapper-btn">
																			<?php if (isset($settings['more_icon_pack']) && $settings['more_icon_' . $settings['more_icon_pack']] != '') {
																				echo thegem_build_icon($settings['more_icon_pack'], $settings['more_icon_' . $settings['more_icon_pack']]);
																			} ?>
																			<span class="gem-text-button">
																				<?php echo '<span>' . wp_kses($settings['more_button_text'], 'post') . '</span>'; ?>
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
										} else if ($settings['pagination_type'] == 'scroll' && $next_page > 0) { ?>
											<div class="portfolio-scroll-pagination"></div>
										<?php }
									} ?>
								</div><!-- .full-width -->
							</div><!-- .portfolio-->
						</div><!-- .portfolio-preloader-wrapper-->

						<?php

					} else {
						if(!is_singular()) {
							wp_enqueue_style('thegem-blog');
							wp_enqueue_style('thegem-additional-blog');
							wp_enqueue_style('thegem-blog-timeline-new');
							wp_enqueue_script('thegem-scroll-monitor');
							wp_enqueue_script('thegem-items-animations');
							wp_enqueue_script('thegem-blog');
							wp_enqueue_script('thegem-gallery');
							echo '<div class="blog blog-style-default">';
						}

						while ( have_posts() ) : the_post();

							get_template_part( 'content', 'blog-item' );

						endwhile;

						if(!is_singular()) { thegem_pagination(); echo '</div>'; }
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