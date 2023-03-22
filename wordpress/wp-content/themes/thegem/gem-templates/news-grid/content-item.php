<?php

if (!function_exists('thegem_news_grid_item_author')) {
	function thegem_news_grid_item_author($params) {
		global $post;

		if ($params['hide_author']) return;

		?>

		<div class="author">
			<?php if (!$params['hide_author_avatar']): ?>
				<span class="author-avatar"><?php echo get_avatar(get_the_author_meta('ID'), 50) ?></span>
			<?php endif; ?>
			<span class="author-name"><?php printf( esc_html__( "By %s", "thegem" ), get_the_author_link() ) ?></span>
		</div>

		<?php
	}
}

if (!function_exists('thegem_news_grid_item_meta')) {
	function thegem_news_grid_item_meta($params, $has_comments, $has_likes) {
		global $post;

		if (!$has_comments && !$has_likes && $params['disable_socials']) return;

		?>

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

if (isset($thegem_sizes)) {
	$thegem_size = $thegem_sizes[0];
	$thegem_sources = $thegem_sizes[1];
}
$version = $params['version'];
if ($params['version'] == 'list') {
	$version = 'new';
}
if (!isset($portfolio_item_size)):
	$thegem_has_post_thumbnail = has_post_thumbnail(get_the_ID()); ?>
	<div <?php post_class($thegem_classes); ?> data-default-sort="<?php echo intval(get_post()->menu_order); ?>" data-sort-date="<?php echo get_the_date('U'); ?>">
		<?php if ( $params['layout'] == 'creative' ) { ?>
		<div class="wrap-out">
			<?php } ?>
			<?php if ($alternative_highlight_style_enabled): ?>
			<div class="highlight-item-alternate-box">
				<div class="highlight-item-alternate-box-content caption">
					<div class="highlight-item-alternate-box-content-inline">
						<?php if (!$params['hide_date']): ?>
							<div class="post-date"><?php echo get_the_date(); ?></div>
						<?php endif; ?>

						<?php if (!isset($params['blog_show_title']) || $params['blog_show_title']) {
							if (isset($params['title_preset']) && $params['title_preset'] != 'default') {
								$title = $params['title_preset'];
							} else if ($params['version'] == 'new' || ($params['version'] == 'list' && $params['columns'] == '1x')) {
								$title = 'title-h4';
							} else if ($params['version'] == 'list' && in_array($params['columns'], ['3x', '4x'])) {
								$title = 'main-menu-item';
							} else {
								$title = 'title-h6';
							}
							if (isset($params['title_font_weight'])) {
								$title .= ' ' . $params['title_font_weight'];
							} ?>
							<div class="title">
								<?php the_title('<div class="' . $title . '">', '</div>'); ?>
							</div>
						<?php } ?>

						<?php if ($params['version'] == 'default' && !$params['hide_categories']): ?>
							<div class="info">
								<?php
								$thegem_index = 0;
								foreach ($slugs as $thegem_k => $thegem_slug)
									if (isset($thegem_terms_set[$thegem_slug])) {
										echo ($thegem_index > 0 ? '<span class="sep"></span> ': '').'<a data-slug="'.$thegem_terms_set[$thegem_slug]->slug.'">'.$thegem_terms_set[$thegem_slug]->name.'</a>';
										$thegem_index++;
									}
								?>
							</div>
						<?php endif; ?>

						<a href="<?php echo esc_url(get_permalink()); ?>" class="portfolio-item-link"></a>
					</div>
				</div>
			</div>
			<style>
				<?php if (!empty($post_item_data['highlight_title_left_background'])): ?>
				.news-grid .portfolio-item.post-<?php echo get_the_ID(); ?>.double-item-style-alternative .highlight-item-alternate-box {
					background-color: <?php echo $post_item_data['highlight_title_left_background']; ?>;
				}
				<?php endif; ?>

				<?php if (!empty($post_item_data['highlight_title_left_color'])): ?>
				.news-grid .portfolio-item.post-<?php echo get_the_ID(); ?>.double-item-style-alternative .highlight-item-alternate-box .caption .title,
				.news-grid .portfolio-item.post-<?php echo get_the_ID(); ?>.double-item-style-alternative .highlight-item-alternate-box .caption .title > *,
				.news-grid .portfolio-item.post-<?php echo get_the_ID(); ?>.double-item-style-alternative .highlight-item-alternate-box .caption .post-date,
				.news-grid .portfolio-item.post-<?php echo get_the_ID(); ?>.double-item-style-alternative .highlight-item-alternate-box .caption .info a,
				.news-grid .portfolio-item.post-<?php echo get_the_ID(); ?>.double-item-style-alternative .highlight-item-alternate-box .caption .info .sep {
					color: <?php echo $post_item_data['highlight_title_left_color']; ?> !important;
				}

				.news-grid .portfolio-item.post-<?php echo get_the_ID(); ?>.double-item-style-alternative .highlight-item-alternate-box .caption .info .sep {
					border-left-color: <?php echo $post_item_data['highlight_title_left_color']; ?>;
				}
				<?php endif; ?>

				<?php if (!empty($post_item_data['highlight_title_right_background'])): ?>
				.news-grid .portfolio-item.post-<?php echo get_the_ID(); ?>.double-item-style-alternative.right-item .highlight-item-alternate-box {
					background-color: <?php echo $post_item_data['highlight_title_right_background']; ?>;
				}
				<?php endif; ?>

				<?php if (!empty($post_item_data['highlight_title_right_color'])): ?>
				.news-grid .portfolio-item.post-<?php echo get_the_ID(); ?>.double-item-style-alternative.right-item .highlight-item-alternate-box .caption .title,
				.news-grid .portfolio-item.post-<?php echo get_the_ID(); ?>.double-item-style-alternative.right-item .highlight-item-alternate-box .caption .title > *,
				.news-grid .portfolio-item.post-<?php echo get_the_ID(); ?>.double-item-style-alternative.right-item .highlight-item-alternate-box .caption .post-date,
				.news-grid .portfolio-item.post-<?php echo get_the_ID(); ?>.double-item-style-alternative.right-item .highlight-item-alternate-box .caption .info a,
				.news-grid .portfolio-item.post-<?php echo get_the_ID(); ?>.double-item-style-alternative.right-item .highlight-item-alternate-box .caption .info .sep {
					color: <?php echo $post_item_data['highlight_title_right_color']; ?> !important;
				}

				.news-grid .portfolio-item.post-<?php echo get_the_ID(); ?>.double-item-style-alternative.right-item .highlight-item-alternate-box .caption .info .sep {
					border-left-color: <?php echo $post_item_data['highlight_title_right_color']; ?>;
				}
				<?php endif; ?>
			</style>
		<?php endif; ?>

		<div class="wrap clearfix">
			<?php if (isset($params['post_type_indication']) && $params['post_type_indication']) {
				$postType = get_post_type() == 'thegem_pf_item' ? 'Portfolio' : get_post_type(); ?>
				<div class="post-type title-h6"><span><?php echo $postType; ?></span></div>
			<?php } ?>
			<?php if ($params['display_titles'] == 'hover' || !$params['hide_featured_image']) { ?>
				<div <?php post_class($thegem_image_classes); ?>>
					<div class="image-inner <?php echo $thegem_has_post_thumbnail ? '' : 'without-image'; ?>">

						<?php if ($params['layout'] == 'justified' || $params['layout'] == 'creative' || (!$thegem_has_post_thumbnail && isset($params['search']) && !in_array($post_format, array('quote', 'audio', 'video'))) ): ?>
							<?php thegem_generate_picture('THEGEM_TRANSPARENT_IMAGE', $thegem_size, array(), array('alt' => get_the_title(), 'style' => 'max-width: 110%')); ?>
						<?php endif; ?>

						<?php if ($params['layout'] == 'metro' && ($post_format == 'video' || $post_format == 'audio')): ?>
							<?php thegem_generate_picture('THEGEM_TRANSPARENT_IMAGE', 'thegem-news-grid-metro-video', array(), array('alt' => get_the_title(), 'style' => 'max-width: 110%')); ?>
						<?php endif; ?>

						<?php if ($params['layout'] == 'metro' && $post_format == 'quote'): ?>
							<?php thegem_generate_picture('THEGEM_TRANSPARENT_IMAGE', 'thegem-portfolio-metro-retina', array(), array('alt' => get_the_title(), 'style' => 'max-width: 110%')); ?>
						<?php endif; ?>

						<?php
						if (!isset($portfolio_item_size)) {
							$audio_with_thumb = false;
							if ($post_format == 'audio' && isset($params['search_post'] ) ) {
								$audio_with_thumb = true;
							}
							if ($post_format == 'video' && $thegem_has_post_thumbnail) {
								echo '<div class="post-featured-content"><a href="' . esc_url(get_permalink(get_the_ID())) . '">';
								thegem_post_picture($thegem_size, $thegem_sources, array('class' => 'img-responsive', 'alt' => get_post_meta(get_post_thumbnail_id(get_the_ID()), '_wp_attachment_image_alt', true), 'style' => 'max-width: 110%'));
								echo '</a></div>';
							} else {
								echo thegem_get_post_featured_content(get_the_ID(), $thegem_size, false, $thegem_sources, $audio_with_thumb);
							}
						}
						?>
						<?php if (!$thegem_has_post_thumbnail && isset($params['search']) && !in_array($post_format, array('quote', 'audio', 'video'))) { ?>
							<div class="post-featured-content">
								<a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>">
									<svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m14 17h-7v-2h7m3-2h-10v-2h10m0-2h-10v-2h10m2-4h-14c-1.11 0-2 .89-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-14c0-1.11-.9-2-2-2z"/></svg>
								</a>
							</div>
						<?php } ?>
					</div>

					<?php if (($post_format != 'video' || $thegem_has_post_thumbnail) && $post_format != 'audio' && $post_format != 'quote' && $post_format != 'gallery' && $params['hover'] != 'simple-zoom'): ?>
						<div class="overlay">
							<div class="overlay-circle"></div>
							<?php if (!isset($portfolio_item_size) && $post_format == 'video' && $thegem_has_post_thumbnail && !empty($post_item_data['video'])): ?>
								<?php
								switch ($post_item_data['video_type']) {
									case 'youtube':
										$thegem_video_link = '//www.youtube.com/embed/' . $post_item_data['video'] . '?autoplay=1';
										$thegem_video_class = 'youtube';
										break;

									case 'vimeo':
										$thegem_video_link = '//player.vimeo.com/video/' . $post_item_data['video'] . '?autoplay=1';
										$thegem_video_class = 'vimeo';
										break;

									default:
										$thegem_video_link = $post_item_data['video'];
										$thegem_video_class = 'self_video';
								}
								?>
								<a href="<?php echo esc_url($thegem_video_link); ?>" class="news-video-icon <?php echo $thegem_video_class; ?>"></a>
							<?php endif; ?>

							<div class="links-wrapper">
								<div class="links">
									<div class="caption">
										<a href="<?php echo esc_url(get_permalink()); ?>" class="portfolio-item-link"></a>

										<?php if ($post_format != 'video'): ?>
											<?php if ($params['display_titles'] == 'page' && $version == 'new'):
												if (isset($params['hover']) && $params['version'] == 'new'): ?>
													<div class="portfolio-icons">
														<a href="javascript: void(0);" class="icon self-link"><i class="default"></i></a>
													</div>
												<?php endif; ?>

												<?php if (!$params['hide_categories'] && $post_format != 'quote'): ?>
													<div class="info">
														<?php
														$thegem_index = 0;
														foreach ($slugs as $thegem_k => $thegem_slug)
															if (isset($thegem_terms_set[$thegem_slug])) {
																echo ($thegem_index > 0 ? '<span class="sep"></span> ': '').'<a data-slug="'.$thegem_terms_set[$thegem_slug]->slug.'">'.$thegem_terms_set[$thegem_slug]->name.'</a>';
																$thegem_index++;
															}
														?>
													</div>
												<?php endif; ?>
											<?php endif; ?>

											<?php if ($params['version'] == 'default' && $params['display_titles'] == 'page'): ?>
												<?php if (!$alternative_highlight_style_enabled && ($params['hover'] == 'gradient' || $params['hover'] == 'circular')): ?>
													<div class="gradient-top-box">
												<?php endif; ?>

												<?php if ($has_comments || $has_likes): ?>
													<div class="grid-post-meta <?php if ( !$has_likes): ?>without-likes<?php endif; ?>">
														<?php if ($has_comments) : ?>
															<span class="comments-link"><i class="default"></i><?php comments_popup_link(0, 1, '%'); ?></span>
														<?php endif; ?>
														<?php if( $has_likes ) { echo '<span class="post-meta-likes"><i class="default"></i>';zilla_likes();echo '</span>'; } ?>
													</div>
												<?php endif; ?>

												<div class="description <?php if ( empty($post_excerpt) || (isset($params['blog_show_description']) && !$params['blog_show_description'])): ?>empty-excerpt<?php endif; ?>">
													<?php if (!empty($post_excerpt) && (!isset($params['blog_show_description']) || $params['blog_show_description'])):
														$description_preset = '';
														if (isset($params['blog_description_preset']) && $params['blog_description_preset'] != 'default') {
															$description_preset = $params['blog_description_preset'];
														} else if ($params['version'] == 'list' && in_array($params['columns'], ['3x', '4x'])) {
															$description_preset = 'text-body-tiny';
														} ?>
														<div class="subtitle <?php echo $description_preset; ?>">
															<?php echo $post_excerpt; ?>
														</div>
													<?php endif; ?>
												</div>

												<div class="post-author-outer">
													<?php thegem_news_grid_item_author($params); ?>
												</div>

												<?php if (!$alternative_highlight_style_enabled && ($params['hover'] == 'gradient' || $params['hover'] == 'circular')): ?>
													</div>
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>

										<?php if ($params['version'] == 'default' && $params['display_titles'] == 'hover'): ?>
											<div class="slide-content">
												<div class="slide-content-visible">
													<?php if ($params['hover'] == 'vertical-sliding'): ?>
														<?php thegem_news_grid_item_meta($params, $has_comments, $has_likes); ?>
													<?php endif; ?>

													<?php if (($params['hover'] == 'gradient' || $params['hover'] == 'vertical-sliding') && !$params['hide_date']): ?>
														<div class="post-date"><?php echo get_the_date(); ?></div>
													<?php endif; ?>

													<?php if (!isset($params['blog_show_title']) || $params['blog_show_title']) {
														if (isset($params['title_preset']) && $params['title_preset'] != 'default') {
															$title = $params['title_preset'];
														} else {
															$title = isset($post_item_data['highlight']) && $post_item_data['highlight'] && $params['layout'] != 'metro' && empty($params['is_slider']) && $thegem_highlight_type == 'squared' ? 'title-h4' : 'title-h5';
														}
														if (isset($params['title_font_weight'])) {
															$title .= ' ' . $params['title_font_weight'];
														} ?>
														<div class="title">
															<?php the_title('<div class="' . $title .'">', '</div>'); ?>
														</div>
													<?php } ?>

													<?php if ($params['hover'] == 'zooming-blur'): ?>
														<?php thegem_news_grid_item_author($params); ?>
													<?php endif; ?>

													<?php if ($params['hover'] == 'zooming-blur'): ?>
														<?php if (!empty($post_excerpt) && (!isset($params['blog_show_description']) || $params['blog_show_description'])):
															$description_preset = '';
															if (isset($params['blog_description_preset']) && $params['blog_description_preset'] != 'default') {
																$description_preset = $params['blog_description_preset'];
															} else if ($params['version'] == 'list' && in_array($params['columns'], ['3x', '4x'])) {
																$description_preset = 'text-body-tiny';
															} ?>
															<div class="description">
																<div class="subtitle <?php echo $description_preset; ?>">
																	<?php echo $post_excerpt; ?>
																</div>
															</div>
														<?php endif; ?>
													<?php endif; ?>

													<?php if (!$params['hide_categories'] && ($params['hover'] == 'circular' || $params['hover'] == 'zooming-blur' || $params['hover'] == 'vertical-sliding')): ?>
														<div class="info">
															<?php
															$thegem_index = 0;
															foreach ($slugs as $thegem_k => $thegem_slug)
																if (isset($thegem_terms_set[$thegem_slug])) {
																	echo ($thegem_index > 0 ? '<span class="sep"></span> ': '').'<a data-slug="'.$thegem_terms_set[$thegem_slug]->slug.'">'.$thegem_terms_set[$thegem_slug]->name.'</a>';
																	$thegem_index++;
																}
															?>
														</div>
													<?php endif; ?>

													<?php if (($params['hover'] == 'default' || $params['hover'] == 'circular' || $params['hover'] == 'horizontal-sliding') && !$params['hide_date']): ?>
														<div class="post-date"><?php echo get_the_date(); ?></div>
													<?php endif; ?>

													<?php if ($params['hover'] == 'default' || $params['hover'] == 'horizontal-sliding'): ?>
														<?php thegem_news_grid_item_meta($params, $has_comments, $has_likes); ?>
													<?php endif; ?>
												</div>

												<div class="slide-content-hidden">
													<?php if ($params['hover'] == 'default' || $params['hover'] == 'horizontal-sliding'): ?>
														<?php thegem_news_grid_item_author($params); ?>
													<?php endif; ?>

													<?php if ($params['hover'] == 'gradient' || $params['hover'] == 'circular' || $params['hover'] == 'zooming-blur'): ?>
														<?php thegem_news_grid_item_meta($params, $has_comments, $has_likes); ?>
													<?php endif; ?>

													<?php if (($params['hover'] == 'zooming-blur') && !$params['hide_date']): ?>
														<div class="post-date"><?php echo get_the_date(); ?></div>
													<?php endif; ?>

													<?php if ($params['hover'] != 'zooming-blur'): ?>
														<?php if (!empty($post_excerpt) && (!isset($params['blog_show_description']) || $params['blog_show_description'])):
															$description_preset = '';
															if (isset($params['blog_description_preset']) && $params['blog_description_preset'] != 'default') {
																$description_preset = $params['blog_description_preset'];
															} else if ($params['version'] == 'list' && in_array($params['columns'], ['3x', '4x'])) {
																$description_preset = 'text-body-tiny';
															} ?>
															<div class="description">
																<div class="subtitle <?php echo $description_preset; ?>">
																	<?php echo $post_excerpt; ?>
																</div>
															</div>
														<?php endif; ?>
													<?php endif; ?>

													<?php if (!$params['hide_categories'] && ($params['hover'] == 'default' || $params['hover'] == 'gradient' || $params['hover'] == 'horizontal-sliding')): ?>
														<div class="info">
															<?php
															$thegem_index = 0;
															foreach ($slugs as $thegem_k => $thegem_slug)
																if (isset($thegem_terms_set[$thegem_slug])) {
																	echo ($thegem_index > 0 ? '<span class="sep"></span> ': '').'<a data-slug="'.$thegem_terms_set[$thegem_slug]->slug.'">'.$thegem_terms_set[$thegem_slug]->name.'</a>';
																	$thegem_index++;
																}
															?>
														</div>
													<?php endif; ?>

													<?php if ($params['hover'] == 'gradient' || $params['hover'] == 'circular' || $params['hover'] == 'vertical-sliding'): ?>
														<?php thegem_news_grid_item_author($params); ?>
													<?php endif; ?>
												</div>
											</div>
										<?php endif; ?>

										<?php if ($params['version'] == 'new' && $params['display_titles'] == 'hover'): ?>
											<div class="slide-content">
												<div class="slide-content-visible">
													<?php if (($params['hover'] == 'default' || $params['hover'] == 'gradient' || $params['hover'] == 'circular') && !$params['hide_date']): ?>
														<div class="post-date"><?php echo get_the_date(); ?></div>
													<?php endif; ?>

													<?php if ($params['hover'] == 'zooming-blur'): ?>
														<?php thegem_news_grid_item_author($params); ?>
														<?php thegem_news_grid_item_meta($params, $has_comments, $has_likes); ?>
													<?php endif; ?>

													<?php if ($params['hover'] == 'vertical-sliding' || $params['hover'] == 'horizontal-sliding'): ?>
														<?php thegem_news_grid_item_author($params); ?>
													<?php endif; ?>

													<?php if (!isset($params['blog_show_title']) || $params['blog_show_title']) {
														if (isset($params['title_preset']) && $params['title_preset'] != 'default') {
															$title = $params['title_preset'];
														} else {
															$title = isset($post_item_data['highlight']) && $post_item_data['highlight'] && $params['layout'] != 'metro' && empty($params['is_slider']) && $thegem_highlight_type == 'squared' ? 'title-h4' : 'title-h5';
														}
														if (isset($params['title_font_weight'])) {
															$title .= ' ' . $params['title_font_weight'];
														} ?>
														<div class="title">
															<?php the_title('<div class="' . $title .'">', '</div>'); ?>
														</div>
													<?php } ?>

													<?php if ($params['hover'] == 'default'): ?>
														<?php thegem_news_grid_item_author($params); ?>
													<?php endif; ?>

													<?php if (!$params['hide_categories'] && ($params['hover'] == 'gradient' || $params['hover'] == 'circular')): ?>
														<div class="info">
															<?php
															$thegem_index = 0;
															foreach ($slugs as $thegem_k => $thegem_slug)
																if (isset($thegem_terms_set[$thegem_slug])) {
																	echo ($thegem_index > 0 ? '<span class="sep"></span> ': '').'<a data-slug="'.$thegem_terms_set[$thegem_slug]->slug.'">'.$thegem_terms_set[$thegem_slug]->name.'</a>';
																	$thegem_index++;
																}
															?>
														</div>
													<?php endif; ?>
												</div>

												<div class="slide-content-hidden">
													<?php if ($params['hover'] == 'gradient' || $params['hover'] == 'circular'): ?>
														<?php thegem_news_grid_item_author($params); ?>
													<?php endif; ?>

													<?php if ($params['hover'] == 'vertical-sliding'): ?>
														<?php thegem_news_grid_item_author($params); ?>

														<?php if (!$params['hide_author']): ?>
															<div class="overlay-line"></div>
														<?php endif; ?>

														<?php thegem_news_grid_item_meta($params, $has_comments, $has_likes); ?>
													<?php endif; ?>

													<?php if ($params['hover'] == 'horizontal-sliding'): ?>
														<?php if (!$params['hide_author']): ?>
															<div class="overlay-line"></div>
														<?php endif; ?>
													<?php endif; ?>

													<?php if (!$params['hide_categories'] && $params['hover'] == 'horizontal-sliding'): ?>
														<div class="info">
															<?php
															$thegem_index = 0;
															foreach ($slugs as $thegem_k => $thegem_slug)
																if (isset($thegem_terms_set[$thegem_slug])) {
																	echo ($thegem_index > 0 ? '<span class="sep"></span> ': '').'<a data-slug="'.$thegem_terms_set[$thegem_slug]->slug.'">'.$thegem_terms_set[$thegem_slug]->name.'</a>';
																	$thegem_index++;
																}
															?>
														</div>
													<?php endif; ?>

													<?php if (!empty($post_excerpt) && (!isset($params['blog_show_description']) || $params['blog_show_description'])):
														$description_preset = '';
														if (isset($params['blog_description_preset']) && $params['blog_description_preset'] != 'default') {
															$description_preset = $params['blog_description_preset'];
														} else if ($params['version'] == 'list' && in_array($params['columns'], ['3x', '4x'])) {
															$description_preset = 'text-body-tiny';
														} ?>
														<div class="description">
															<div class="subtitle <?php echo $description_preset; ?>">
																<?php echo $post_excerpt; ?>
															</div>
														</div>
													<?php endif; ?>

													<?php if (($params['hover'] == 'zooming-blur' || $params['hover'] == 'vertical-sliding') && !$params['hide_date']): ?>
														<div class="post-date"><?php echo get_the_date(); ?></div>
													<?php endif; ?>

													<?php if ($params['hover'] == 'gradient' || $params['hover'] == 'circular' || $params['hover'] == 'horizontal-sliding'): ?>
														<?php thegem_news_grid_item_meta($params, $has_comments, $has_likes); ?>
													<?php endif; ?>
												</div>
											</div>

											<?php if (!$params['hide_categories'] && ($params['hover'] != 'horizontal-sliding' && $params['hover'] != 'gradient'  && $params['hover'] != 'circular')): ?>
												<div class="info">
													<?php
													$thegem_index = 0;
													foreach ($slugs as $thegem_k => $thegem_slug)
														if (isset($thegem_terms_set[$thegem_slug])) {
															echo ($thegem_index > 0 ? '<span class="sep"></span> ': '').'<a data-slug="'.$thegem_terms_set[$thegem_slug]->slug.'">'.$thegem_terms_set[$thegem_slug]->name.'</a>';
															$thegem_index++;
														}
													?>
												</div>
											<?php endif; ?>

											<?php if ($params['hover'] == 'default'): ?>
												<?php thegem_news_grid_item_meta($params, $has_comments, $has_likes); ?>
											<?php endif; ?>

											<?php if ($params['hover'] == 'horizontal-sliding' && !$params['hide_date']): ?>
												<div class="post-date"><?php echo get_the_date(); ?></div>
											<?php endif; ?>

										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php } else { ?>
				<div class="image-inner"></div>
			<?php } ?>

			<?php if ( $params['display_titles'] == 'page' && $post_format != 'quote'): ?>
				<div <?php post_class($thegem_caption_classes); ?>>

					<?php if ($version == 'new' && (!$params['hide_date'] || !$params['hide_author'])): ?>
					<div class="post-author-date">
						<?php thegem_news_grid_item_author($params); ?>
						<?php endif; ?>

						<?php if (!$params['hide_date']): ?>
							<?php if ($version == 'new' && !$params['hide_author']): ?>
								<div class="post-author-date-separator">&nbsp;-&nbsp;</div>
							<?php endif; ?>
							<div class="post-date"><?php echo get_the_date(); ?></div>
						<?php endif; ?>

						<?php if ($version == 'new' && (!$params['hide_date'] || !$params['hide_author'])): ?>
					</div>
				<?php endif; ?>

					<?php if (!isset($params['blog_show_title']) || $params['blog_show_title']) {
						if (isset($params['title_preset']) && $params['title_preset'] != 'default') {
							$title = $params['title_preset'];
						} else if (isset($params['search_post']) ) {
							$title = 'title-h5';
						} else if ($params['version'] == 'new' || ($params['version'] == 'list' && $params['columns'] == '1x')) {
							$title = 'title-h4';
						} else if ($params['version'] == 'list' && in_array($params['columns'], ['3x', '4x'])) {
							$title = 'main-menu-item';
						} else {
							$title = 'title-h6';
						}
						if (isset($params['title_font_weight'])) {
							$title .= ' ' . $params['title_font_weight'];
						} ?>
						<div class="title">
							<?php the_title('<div class="' . $title . '"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></div>'); ?>
						</div>
					<?php } ?>

					<?php if ($params['version'] == 'default' && !$params['hide_categories'] && $post_format != 'quote'): ?>
						<div class="info">
							<?php
							$thegem_index = 0;
							foreach ($slugs as $thegem_k => $thegem_slug)
								if (isset($thegem_terms_set[$thegem_slug])) {
									echo ($thegem_index > 0 ? '<span class="sep"></span> ': '').'<a data-slug="'.$thegem_terms_set[$thegem_slug]->slug.'">'.$thegem_terms_set[$thegem_slug]->name.'</a>';
									$thegem_index++;
								}
							?>
						</div>
					<?php endif; ?>

					<?php if ($version == 'new' && (!empty($post_excerpt) || $has_comments || $has_likes || !$params['disable_socials'])): ?>
						<?php if (!empty($post_excerpt) && (!isset($params['blog_show_description']) || $params['blog_show_description'])):
							$description_preset = '';
							if (isset($params['blog_description_preset']) && $params['blog_description_preset'] != 'default') {
								$description_preset = $params['blog_description_preset'];
							} else if ($params['version'] == 'list' && in_array($params['columns'], ['3x', '4x'])) {
								$description_preset = 'text-body-tiny';
							} ?>
							<div class="description <?php echo $description_preset; ?>">
								<?php echo $post_excerpt; ?>
							</div>
						<?php endif; ?>

						<?php if ($params['version'] == 'list' && $params['blog_show_readmore_button'] == '1') { ?>
							<div class="read-more-button"><?php include 'readmore-button.php'; ?></div>
						<?php } ?>

						<?php if ($has_comments || $has_likes || !$params['disable_socials']): ?>
							<div class="grid-post-meta clearfix <?php if ( !$has_likes): ?>without-likes<?php endif; ?>">
								<div class="grid-post-meta-inner">
									<?php if (!$params['disable_socials']): ?>
										<div class="grid-post-share">
											<a href="javascript: void(0);" class="icon share"><i class="default"></i></a>
										</div>
										<div class="portfolio-sharing-pane"><?php thegem_socials_sharing(); ?></div>
									<?php endif; ?>

									<div class="grid-post-meta-comments-likes">
										<?php if ($has_comments) { ?>
											<span class="comments-link"><i class="default"></i><?php comments_popup_link(0, 1, '%'); ?></span>
										<?php } ?>

										<?php if ( $has_likes ) { ?>
											<span class="post-meta-likes"><i class="default"></i><?php zilla_likes(); ?></span>
										<?php } ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $params['layout'] == 'creative' ) { ?>
		</div>
	<?php } ?>
	</div>
<?php else: ?>
	<div <?php post_class( $thegem_classes ); ?>>
		<?php if ($params['layout'] == 'creative') { ?>
			<div class="wrap-out">
				<div class="wrap clearfix">
					<?php if ( isset( $params['post_type_indication'] ) && $params['post_type_indication']) { ?>
						<div class="post-type title-h6"><span>Post Type</span></div>
					<?php } ?>
					<?php if ($params['display_titles'] == 'hover' || !$params['hide_featured_image']) { ?>
						<div <?php post_class($thegem_image_classes); ?>>
							<div class="image-inner">
								<?php thegem_generate_picture('THEGEM_TRANSPARENT_IMAGE', $thegem_size, array(), array('alt' => get_the_title(), 'style' => 'max-width: 110%')); ?>
							</div>
						</div>
					<?php } else { ?>
						<div class="image-inner"></div>
					<?php } ?>

					<?php if ( $params['display_titles'] == 'page' ): ?>
						<div class="caption">
							<?php if ($version == 'new' && (!$params['hide_date'] || !$params['hide_author'])): ?>
							<div class="post-author-date">
								Author
								<?php endif; ?>

								<?php if ( !$params['hide_date'] ): ?>
									<?php if ( $version == 'new' && !$params['hide_author'] ): ?>
										<div class="post-author-date-separator">&nbsp;-&nbsp;</div>
									<?php endif; ?>
									<div class="post-date">Date</div>
								<?php endif; ?>

								<?php if ( $version == 'new' && ( !$params['hide_date'] || !$params['hide_author'] ) ): ?>
							</div>
						<?php endif; ?>

							<?php if (!isset($params['blog_show_title']) || $params['blog_show_title']) {
								if (isset($params['title_preset']) && $params['title_preset'] != 'default') {
									$title = $params['title_preset'];
								} else if (isset($params['search_post']) ) {
									$title = 'title-h5';
								} else if ($params['version'] == 'new' || ($params['version'] == 'list' && $params['columns'] == '1x')) {
									$title = 'title-h4';
								} else if ($params['version'] == 'list' && in_array($params['columns'], ['3x', '4x'])) {
									$title = 'main-menu-item';
								} else {
									$title = 'title-h6';
								}
								if (isset($params['title_font_weight'])) {
									$title .= ' ' . $params['title_font_weight'];
								} ?>
								<div class="title">
									<div class="<?php echo $title; ?>"><a href="#" rel="bookmark">Title</a></div>
								</div>
							<?php } ?>

							<?php if ( $params['version'] == 'default' && !$params['hide_categories'] ): ?>
								<div class="info">Categories</div>
							<?php endif; ?>

							<?php if ( $version == 'new' && ( $params['blog_show_description'] || !$params['disable_socials'] ) ): ?>
								<?php if ( $params['blog_show_description'] ):
									$description_preset = '';
									if (isset($params['blog_description_preset']) && $params['blog_description_preset'] != 'default') {
										$description_preset = $params['blog_description_preset'];
									} else if ($params['version'] == 'list' && in_array($params['columns'], ['3x', '4x'])) {
										$description_preset = 'text-body-tiny';
									} ?>
									<div class="description <?php echo $description_preset; ?>">Description</div>
								<?php endif; ?>

								<?php if ( !$params['disable_socials'] ): ?>
									<div class="grid-post-meta clearfix without-likes">
										<div class="grid-post-meta-inner">
											<?php if ( !$params['disable_socials'] ): ?>
												<div class="grid-post-share">
													<a href="#" class="icon share"><i class="default"></i></a>
												</div>
											<?php endif; ?>

											<div class="grid-post-meta-comments-likes">
												<span class="comments-link"><i class="default"></i></span>
												<span class="post-meta-likes"><i class="default"></i></span>
											</div>
										</div>
									</div>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php } ?>
	</div>
<?php endif; ?>
