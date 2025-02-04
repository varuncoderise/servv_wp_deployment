<?php

if (isset($thegem_sizes)) {
	$thegem_size = $thegem_sizes[0];
	$thegem_sources = $thegem_sizes[1];
}
$version = $params['version'];
if ($params['version'] == 'list') {
	$version = 'new';
	$params['hover'] = $params['image_hover_effect_list'];
}
$title_tag = isset($params['title_tag']) ? $params['title_tag'] : 'div';

$params['caption_position'] = $params['display_titles'];
if (!isset($portfolio_item_size)):
	if ($post_format == 'animated_gif') {
		$gif_preload = true;
		if ($post_item_data['gif_start'] == 'play_on_hover' && empty($post_item_data['gif_preload'])) {
			$gif_preload = false;
			$thegem_image_classes[] = 'gif-load-on-hover';
		}
	}
	$thegem_classes[] = 'appearance-type-' . $post_format;

	if ($post_format == 'video') {
		$ratio = !empty($post_item_data['video_aspect_ratio']) ? $post_item_data['video_aspect_ratio'] : '';
		$ratio_arr = explode(":", $ratio);
		if (!empty($ratio_arr[0]) && !empty($ratio_arr[1])) {
			$aspect_ratio = $ratio_arr[0] / $ratio_arr[1];
			if ($post_item_data['video_start'] !== 'open_in_lightbox') {
				$thegem_classes[] = 'custom-ratio';
			}
		}
		if (!isset($aspect_ratio) && isset($params['image_size']) && $params['image_size'] !== 'default') {
			$aspect_ratio = 1;
		}
		if ($post_item_data['video_start'] !== 'open_in_lightbox') {
			$thegem_classes[] = 'hide-overlay';
		}
	} ?>
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
							} else if ($params['version'] == 'new' || ($params['version'] == 'list' && $params['columns_desktop'] == '1x')) {
								$title = 'title-h4';
							} else if ($params['version'] == 'list' && in_array($params['columns_desktop'], ['3x', '4x'])) {
								$title = 'main-menu-item';
							} else {
								$title = 'title-h6';
							}
							if (isset($params['title_font_weight'])) {
								$title .= ' ' . $params['title_font_weight'];
							} ?>
							<<?php echo $title_tag; ?> class="title">
								<?php the_title('<span class="' . $title . '">', '</span>'); ?>
							</<?php echo $title_tag; ?>>
						<?php } ?>

						<?php if ($params['version'] == 'default') {
							thegem_get_additional_meta($params, true, '<span class="sep"></span> ');
						} ?>

						<a href="<?php echo esc_url(get_permalink()); ?>" class="portfolio-item-link">
							<span class="screen-reader-text"><?php the_title(); ?></span>
						</a>
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
			<?php if ($params['layout'] == 'metro' || $params['display_titles'] == 'hover' || !isset($params['hide_featured_image']) || !$params['hide_featured_image']) { ?>
				<div <?php post_class($thegem_image_classes); ?>>
					<div class="image-inner <?php echo $post_format != 'gallery' && $post_format != 'animated_gif' && $post_format != 'video' && !$thegem_has_post_thumbnail ? 'without-image' : ''; ?>">
						<?php
						
						if ($params['layout'] == 'metro' && $post_format == 'audio') {
							$thegem_size = 'thegem-news-grid-metro-video';
						}

						if ($params['layout'] == 'metro' && $post_format == 'quote') {
							$thegem_size = 'thegem-portfolio-metro-retina';
						}

						$audio_with_thumb = false;
						if ($post_format == 'audio' && isset($params['search_post'])) {
							$audio_with_thumb = true;
						}

						if ($post_format != 'gallery' && $post_format != 'animated_gif' && $post_format != 'video' &&
							((!$thegem_has_post_thumbnail && $params['layout'] == 'metro') ||
								($post_format == 'audio' && !isset($params['search_post']) && $params['layout'] == 'metro') ||
								((!isset($params['image_size']) || $params['image_size'] == 'default' || !$thegem_has_post_thumbnail) && ($params['layout'] == 'justified' || $params['layout'] == 'creative')) ||
								(!$thegem_has_post_thumbnail && isset($params['search']) && !in_array($post_format, array('quote', 'audio', 'video'))))) {
							thegem_generate_picture('THEGEM_TRANSPARENT_IMAGE', $thegem_size, array(), array('alt' => get_the_title()));
						}
						if ($post_format == 'video') {

							switch ($post_item_data['video_type']) {
								case 'youtube':
									$video_id = thegem_parcing_youtube_url($post_item_data['video']);
									$thegem_video_link = '//www.youtube.com/embed/' . $video_id . '?autoplay=1';
									$thegem_video_class = 'youtube';
									break;
								case 'vimeo':
									$video_id = thegem_parcing_vimeo_url($post_item_data['video']);
									$thegem_video_link = '//player.vimeo.com/video/' . $video_id . '?autoplay=1';
									$thegem_video_class = 'vimeo';
									break;
								default:
									$video_id = $thegem_video_link = $post_item_data['video'];
									$thegem_video_class = 'self_video';
							}
							if ($post_item_data['video_start'] == 'open_in_lightbox') {
								if ($post_item_data['video_type'] == 'self' && !empty($post_item_data['video_poster'])) { ?>
									<img src="<?php echo $post_item_data['video_poster']; ?>" alt="Video Poster">
								<?php } else if ($thegem_has_post_thumbnail) {
									thegem_post_picture($thegem_size, $thegem_sources, array('alt' => get_the_title()));
								} else if ($params['layout'] == 'metro' || $params['layout'] == 'masonry') {
									thegem_generate_picture('THEGEM_TRANSPARENT_IMAGE', $thegem_size, array(), array('alt' => get_the_title()));
								} ?>
								<a href="<?php echo esc_url($thegem_video_link); ?>"
								   class="portfolio-video-icon <?php echo $thegem_video_class; ?>"
								   <?php if (isset($aspect_ratio)) { ?>data-ratio="<?php echo $aspect_ratio; ?>"<?php } ?>
								   data-fancybox="thegem-portfolio"
								   data-elementor-open-lightbox="no">
								</a>
							<?php } else {
								$play_on_mobile = true;
								if (!$post_item_data['video_play_on_mobile']) {
									if ($thegem_has_post_thumbnail) {
										thegem_post_picture($thegem_size, $thegem_sources, array('alt' => get_the_title(), "class" => "video-image-mobile"));
									} else {
										thegem_generate_picture('THEGEM_TRANSPARENT_IMAGE', $thegem_size, array(), array('alt' => get_the_title(), "class" => "video-image-mobile"));
									}
									$play_on_mobile = false;
								} else if ($params['layout'] == 'metro' || ($params['layout'] == 'masonry' && !isset($aspect_ratio))) {
									thegem_generate_picture('THEGEM_TRANSPARENT_IMAGE', $thegem_size, array(), array('alt' => get_the_title(), 'style' => 'max-width: 110%'));
								}
								$autoplay = $post_item_data['video_start'] == 'autoplay'; ?>

								<div class="gem-video-portfolio type-<?php echo $post_item_data['video_type']; ?> <?php echo $autoplay ? 'autoplay' : 'play-on-hover'; ?> <?php echo $play_on_mobile ? '' : 'hide-on-mobile'; ?> <?php echo !$autoplay || $play_on_mobile ? 'run-embed-video' : ''; ?>"
									data-video-type="<?php echo $post_item_data['video_type']; ?>"
									data-video-id="<?php echo $video_id; ?>"
									<?php if (isset($aspect_ratio)) { ?>style="aspect-ratio: <?php echo $aspect_ratio; ?>"<?php } ?>>
									<?php if (function_exists('thegem_portfolio_video_background')) {
										echo thegem_portfolio_video_background(
											$post_item_data['video_type'],
											$video_id,
											$post_item_data['video_overlay'],
											$post_item_data['video_poster'],
											$autoplay,
											$play_on_mobile);
									} ?>
								</div>
								<?php
							}
						} else if ($post_format == 'animated_gif') {
							if (!empty($post_item_data['gif'])) {
								$gif_src = wp_get_attachment_image_src($post_item_data['gif'], 'full');
								if ($gif_src) { ?>
									<img width="<?php echo $gif_src[1]; ?>" height="<?php echo $gif_src[2]; ?>" class="gem-gif-portfolio" src="<?php if ($gif_preload) { echo $gif_src[0]; } ?>" <?php if (!$gif_preload) { echo 'data-src="' . $gif_src[0] . '"'; } ?>>
								<?php }
							} else {
								thegem_generate_picture('THEGEM_TRANSPARENT_IMAGE', $thegem_size, array(), array('alt' => get_the_title()));
							}

							if ($post_item_data['gif_start'] == 'play_on_hover') {
								if (!empty($post_item_data['gif_poster'])) { ?>
									<img class="gem-gif-poster" src="<?php echo $post_item_data['gif_poster']; ?>">
								<?php } else {
									thegem_generate_picture($post_item_data['gif'], $thegem_size, $thegem_sources, array("class" => "gem-gif-poster"));
								}
							}
						} else if ($post_format == 'gallery') {
							if (get_post_type() == 'thegem_pf_item') {
								$thegem_gallery_images_ids = get_post_meta(get_the_ID(), 'thegem_portfolio_item_gallery_images', true);
							} else {
								if (isset($post_item_data['gallery_type_for_grid']) && $post_item_data['gallery_type_for_grid'] == 'post') {
									if (empty(thegem_get_option(get_post_type() . '_post_gallery_disabled'))) {
										$thegem_gallery_images_ids = get_post_meta(get_the_ID(), 'thegem_post_item_gallery_images', true);
									} else {
										thegem_generate_picture(get_post_thumbnail_id(), $thegem_size, $thegem_sources, array('class' => 'img-responsive', 'alt' => get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)));
									}
								} else {
									if (metadata_exists('post', $post_item_data['gallery'], 'thegem_gallery_images')) {
										$thegem_gallery_images_ids = get_post_meta($post_item_data['gallery'], 'thegem_gallery_images', true);
									} else {
										$attachments_ids = get_posts('post_parent=' . $post_item_data['gallery'] . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids');
										$thegem_gallery_images_ids = implode(',', $attachments_ids);
									}
								}
							}
							$thegem_gallery_images_urls = [];
							if (!empty($thegem_gallery_images_ids)) {
								$attachments_ids = array_filter(explode(',', $thegem_gallery_images_ids)); ?>
								<div class="portfolio-image-slider <?php if (!empty($post_item_data['gallery_autoscroll'])) { echo 'autoscroll'; } ?>" <?php if (!empty($post_item_data['gallery_autoscroll'])) { ?> data-interval="<?php echo isset($post_item_data['gallery_autoscroll_speed']) ? $post_item_data['gallery_autoscroll_speed'] : $post_item_data['gallery_autoscroll']; ?>"<?php } ?>>
									<?php foreach ($attachments_ids as $i => $slide) {
										$slide_image = wp_get_attachment_image($slide, $thegem_size);
										if (empty($slide_image)) {
											continue;
										}
										$thegem_gallery_images_urls[] = wp_get_attachment_image_url($slide, 'full'); ?>
										<div class="slide <?php echo $slide; ?>"<?php if ($i != 0) {echo ' style="opacity: 0;"';} ?>>
											<a href="<?php echo esc_url(get_permalink()); ?>">
												<?php echo $slide_image; ?>
											</a>
										</div>
									<?php } ?>
									<button class="btn btn-next" data-direction="next"></button>
									<button class="btn btn-prev" data-direction="prev"></button>
								</div>
							<?php }
							if ($params['layout'] == 'metro') {
								thegem_generate_picture('THEGEM_TRANSPARENT_IMAGE', $thegem_size, array(), array('alt' => get_the_title(), 'style' => 'max-width: 110%'));
							}
						} else {
							echo thegem_get_post_featured_content(get_the_ID(), $thegem_size, false, $thegem_sources, $audio_with_thumb);
						}

						if (!$thegem_has_post_thumbnail && isset($params['search']) && !in_array($post_format, array('quote', 'audio', 'video', 'gallery', 'animated_gif', 'animated_gif'))) { ?>
							<div class="post-featured-content">
								<a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>">
									<svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m14 17h-7v-2h7m3-2h-10v-2h10m0-2h-10v-2h10m2-4h-14c-1.11 0-2 .89-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-14c0-1.11-.9-2-2-2z"/></svg>
								</a>
							</div>
						<?php } ?>
					</div>

					<?php if (($post_format != 'video' || $thegem_has_post_thumbnail) && $post_format != 'audio' && $post_format != 'quote' && $post_format != 'gallery' && $post_format != 'animated_gif' && $params['hover'] != 'simple-zoom'): ?>
						<div class="overlay">
							<div class="overlay-circle"></div>

							<div class="links-wrapper">
								<div class="links">
									<div class="caption">
										<a href="<?php echo esc_url(get_permalink()); ?>" class="portfolio-item-link">
											<span class="screen-reader-text"><?php the_title(); ?></span>
										</a>

										<?php if ($post_format != 'video'): ?>
											<?php if ($params['display_titles'] == 'page' && $version == 'new'):
												if (isset($params['hover']) && $params['version'] == 'new' && $params['hover'] != 'zoom-overlay' && $params['hover'] != 'disabled'): ?>
													<div class="portfolio-icons">
														<a href="javascript: void(0);" class="icon self-link"><i class="default"></i></a>
													</div>
												<?php endif; ?>

												<?php if ($post_format != 'quote') {
													thegem_get_additional_meta($params, true, '<span class="sep"></span> ');
												} ?>
											<?php endif; ?>

											<?php if ($params['version'] == 'default' && $params['display_titles'] == 'page' && $params['hover'] != 'zoom-overlay' && $params['hover'] != 'disabled'): ?>
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
														$description_preset = 'text-body';
														if (isset($params['blog_description_preset']) && $params['blog_description_preset'] != 'default') {
															$description_preset = $params['blog_description_preset'];
														} else if ($params['version'] == 'list' && in_array($params['columns_desktop'], ['3x', '4x'])) {
															$description_preset = 'text-body-tiny';
														} ?>
														<div class="subtitle">
															<span class="<?php echo $description_preset; ?>"><?php echo $post_excerpt; ?></span>
														</div>
													<?php endif; ?>
												</div>

												<div class="post-author-outer">
													<?php thegem_extended_blog_render_item_author($params); ?>
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
														<?php thegem_extended_blog_render_item_meta($params, $has_comments, $has_likes); ?>
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
														<<?php echo $title_tag; ?> class="title">
															<?php the_title('<span class="' . $title .'">', '</span>'); ?>
														</<?php echo $title_tag; ?>>
													<?php } ?>

													<?php if ($params['hover'] == 'zooming-blur'): ?>
														<?php thegem_extended_blog_render_item_author($params); ?>
													<?php endif; ?>

													<?php if ($params['hover'] == 'zooming-blur'): ?>
														<?php if (!empty($post_excerpt) && (!isset($params['blog_show_description']) || $params['blog_show_description'])):
															$description_preset = 'text-body';
															if (isset($params['blog_description_preset']) && $params['blog_description_preset'] != 'default') {
																$description_preset = $params['blog_description_preset'];
															} else if ($params['version'] == 'list' && in_array($params['columns_desktop'], ['3x', '4x'])) {
																$description_preset = 'text-body-tiny';
															} ?>
															<div class="description">
																<div class="subtitle">
																	<span class="<?php echo $description_preset; ?>"><?php echo $post_excerpt; ?></span>
																</div>
															</div>
														<?php endif;
														thegem_get_details_custom_fields($params); ?>
													<?php endif; ?>

													<?php if ($params['hover'] == 'circular' || $params['hover'] == 'zooming-blur' || $params['hover'] == 'vertical-sliding') {
														thegem_get_additional_meta($params, true, '<span class="sep"></span> ');
													} ?>

													<?php if (($params['hover'] == 'default' || $params['hover'] == 'circular' || $params['hover'] == 'horizontal-sliding') && !$params['hide_date']): ?>
														<div class="post-date"><?php echo get_the_date(); ?></div>
													<?php endif; ?>

													<?php if ($params['hover'] == 'default' || $params['hover'] == 'horizontal-sliding'): ?>
														<?php thegem_extended_blog_render_item_meta($params, $has_comments, $has_likes); ?>
													<?php endif; ?>
												</div>

												<div class="slide-content-hidden">
													<?php if ($params['hover'] == 'default' || $params['hover'] == 'horizontal-sliding'): ?>
														<?php thegem_extended_blog_render_item_author($params); ?>
													<?php endif; ?>

													<?php if ($params['hover'] == 'gradient' || $params['hover'] == 'circular' || $params['hover'] == 'zooming-blur'): ?>
														<?php thegem_extended_blog_render_item_meta($params, $has_comments, $has_likes); ?>
													<?php endif; ?>

													<?php if (($params['hover'] == 'zooming-blur') && !$params['hide_date']): ?>
														<div class="post-date"><?php echo get_the_date(); ?></div>
													<?php endif; ?>

													<?php if ($params['hover'] != 'zooming-blur'): ?>
														<?php if (!empty($post_excerpt) && (!isset($params['blog_show_description']) || $params['blog_show_description'])):
															$description_preset = 'text-body';
															if (isset($params['blog_description_preset']) && $params['blog_description_preset'] != 'default') {
																$description_preset = $params['blog_description_preset'];
															} else if ($params['version'] == 'list' && in_array($params['columns_desktop'], ['3x', '4x'])) {
																$description_preset = 'text-body-tiny';
															} ?>
															<div class="description">
																<div class="subtitle">
																	<span class="<?php echo $description_preset; ?>"><?php echo $post_excerpt; ?></span>
																</div>
															</div>
														<?php endif;
														thegem_get_details_custom_fields($params); ?>
													<?php endif; ?>

													<?php if ($params['hover'] == 'default' || $params['hover'] == 'gradient' || $params['hover'] == 'horizontal-sliding') {
														thegem_get_additional_meta($params, true, '<span class="sep"></span> ');
													} ?>

													<?php if ($params['hover'] == 'gradient' || $params['hover'] == 'circular' || $params['hover'] == 'vertical-sliding'): ?>
														<?php thegem_extended_blog_render_item_author($params); ?>
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
														<?php thegem_extended_blog_render_item_author($params); ?>
														<?php thegem_extended_blog_render_item_meta($params, $has_comments, $has_likes); ?>
													<?php endif; ?>

													<?php if ($params['hover'] == 'vertical-sliding' || $params['hover'] == 'horizontal-sliding'): ?>
														<?php thegem_extended_blog_render_item_author($params); ?>
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
														<<?php echo $title_tag; ?> class="title">
															<?php the_title('<span class="' . $title .'">', '</span>'); ?>
														</<?php echo $title_tag; ?>>
													<?php } ?>

													<?php if ($params['hover'] == 'default'): ?>
														<?php thegem_extended_blog_render_item_author($params); ?>
													<?php endif; ?>

													<?php if ($params['hover'] == 'gradient' || $params['hover'] == 'circular') {
														thegem_get_additional_meta($params, true, '<span class="sep"></span> ');
													} ?>
												</div>

												<div class="slide-content-hidden">
													<?php if ($params['hover'] == 'gradient' || $params['hover'] == 'circular'): ?>
														<?php thegem_extended_blog_render_item_author($params); ?>
													<?php endif; ?>

													<?php if ($params['hover'] == 'vertical-sliding'): ?>
														<?php thegem_extended_blog_render_item_author($params); ?>

														<?php if (!$params['hide_author']): ?>
															<div class="overlay-line"></div>
														<?php endif; ?>

														<?php thegem_extended_blog_render_item_meta($params, $has_comments, $has_likes); ?>
													<?php endif; ?>

													<?php if ($params['hover'] == 'horizontal-sliding'): ?>
														<?php if (!$params['hide_author']): ?>
															<div class="overlay-line"></div>
														<?php endif; ?>
													<?php endif; ?>

													<?php if ($params['hover'] == 'horizontal-sliding') {
														thegem_get_additional_meta($params, true, '<span class="sep"></span> ');
													} ?>

													<?php if (!empty($post_excerpt) && (!isset($params['blog_show_description']) || $params['blog_show_description'])):
														$description_preset = 'text-body';
														if (isset($params['blog_description_preset']) && $params['blog_description_preset'] != 'default') {
															$description_preset = $params['blog_description_preset'];
														} else if ($params['version'] == 'list' && in_array($params['columns_desktop'], ['3x', '4x'])) {
															$description_preset = 'text-body-tiny';
														} ?>
														<div class="description">
															<div class="subtitle">
																<span class="<?php echo $description_preset; ?>"><?php echo $post_excerpt; ?></span>
															</div>
														</div>
													<?php endif; ?>

													<?php thegem_get_details_custom_fields($params); ?>

													<?php if (($params['hover'] == 'zooming-blur' || $params['hover'] == 'vertical-sliding') && !$params['hide_date']): ?>
														<div class="post-date"><?php echo get_the_date(); ?></div>
													<?php endif; ?>

													<?php if ($params['hover'] == 'gradient' || $params['hover'] == 'circular' || $params['hover'] == 'horizontal-sliding'): ?>
														<?php thegem_extended_blog_render_item_meta($params, $has_comments, $has_likes); ?>
													<?php endif; ?>
												</div>
											</div>

											<?php if ($params['hover'] != 'horizontal-sliding' && $params['hover'] != 'gradient'  && $params['hover'] != 'circular' && $params['hover'] != 'zoom-overlay' && $params['hover'] != 'disabled') {
												thegem_get_additional_meta($params, true, '<span class="sep"></span> ');
											} ?>

											<?php if ($params['hover'] == 'default'): ?>
												<?php thegem_extended_blog_render_item_meta($params, $has_comments, $has_likes); ?>
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
				<div class="image-inner empty"></div>
			<?php } ?>

			<?php if ( $params['display_titles'] == 'page' && $post_format != 'quote'): ?>
				<div <?php post_class($thegem_caption_classes); ?>>

					<?php if ($version == 'new' && (!$params['hide_date'] || !$params['hide_author'])): ?>
						<div class="post-author-date">
							<?php thegem_extended_blog_render_item_author($params); ?>
							<?php endif; ?>

							<?php if (!$params['hide_date']): ?>
								<?php if ($version == 'new' && !$params['hide_author']): ?>
									<div class="post-author-date-separator">&nbsp;-&nbsp;</div>
								<?php endif; ?>
								<div class="post-date"><?php echo get_the_date(); ?></div>
							<?php endif; ?>

							<?php if ($version == 'new' && (!$params['hide_date'] || !$params['hide_author'])): ?>
						</div>
					<?php endif;

					if (!empty($params['show_details']) && ($params['details_layout'] == 'inline' && $params['details_position'] == 'top')) {
						thegem_get_details_custom_fields($params);
					}

					if (!isset($params['blog_show_title']) || $params['blog_show_title']) {
						if (isset($params['title_preset']) && $params['title_preset'] != 'default') {
							$title = $params['title_preset'];
						} else if (isset($params['search_post']) ) {
							$title = 'title-h5';
						} else if ($params['version'] == 'new' || ($params['version'] == 'list' && $params['columns_desktop'] == '1x')) {
							$title = 'title-h4';
						} else if ($params['version'] == 'list' && in_array($params['columns_desktop'], ['3x', '4x'])) {
							$title = 'main-menu-item';
						} else {
							$title = 'title-h6';
						}
						if (isset($params['title_font_weight'])) {
							$title .= ' ' . $params['title_font_weight'];
						} ?>
						<<?php echo $title_tag; ?> class="title">
							<?php the_title('<span class="' . $title . '"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></span>'); ?>
						</<?php echo $title_tag; ?>>
					<?php } ?>

					<?php if ($params['version'] == 'default' && $post_format != 'quote') {
						thegem_get_additional_meta($params, true, '<span class="sep"></span> ');
					} ?>

					<?php if ($version == 'new' && (!empty($post_excerpt) || $has_comments || $has_likes || !$params['disable_socials'])) { ?>
						<?php if (!empty($post_excerpt) && (!isset($params['blog_show_description']) || $params['blog_show_description'])):
							$description_preset = '';
							if (isset($params['blog_description_preset']) && $params['blog_description_preset'] != 'default') {
								$description_preset = $params['blog_description_preset'];
							} else if ($params['version'] == 'list' && in_array($params['columns_desktop'], ['3x', '4x'])) {
								$description_preset = 'text-body-tiny';
							} ?>
							<div class="description <?php echo $description_preset; ?>">
								<?php echo $post_excerpt; ?>
							</div>
						<?php endif;

						if (!empty($params['show_details']) && ($params['details_layout'] == 'vertical' || ($params['details_layout'] == 'inline' && $params['details_position'] == 'bottom'))) {
							thegem_get_details_custom_fields($params);
						}

						if (!empty($params['show_readmore_button'])) { ?>
							<div class="read-more-button"><?php include(locate_template(array('gem-templates/portfolios/readmore-button.php'))); ?></div>
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
					<?php } else {
						if (!empty($params['show_details']) && ($params['details_layout'] == 'vertical' || ($params['details_layout'] == 'inline' && $params['details_position'] == 'bottom'))) {
							thegem_get_details_custom_fields($params);
						}
						if (!empty($params['show_readmore_button'])) { ?>
							<div class="read-more-button"><?php include(locate_template(array('gem-templates/portfolios/readmore-button.php'))); ?></div>
						<?php }
					} ?>
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
					<?php if ($params['display_titles'] == 'hover' || !isset($params['hide_featured_image']) || !$params['hide_featured_image']) { ?>
						<div <?php post_class($thegem_image_classes); ?>>
							<div class="image-inner">
								<?php thegem_generate_picture('THEGEM_TRANSPARENT_IMAGE', $thegem_size, array(), array('alt' => get_the_title(), 'style' => 'max-width: 110%')); ?>
							</div>
						</div>
					<?php } else { ?>
						<div class="image-inner empty"></div>
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
								} else if ($params['version'] == 'new' || ($params['version'] == 'list' && $params['columns_desktop'] == '1x')) {
									$title = 'title-h4';
								} else if ($params['version'] == 'list' && in_array($params['columns_desktop'], ['3x', '4x'])) {
									$title = 'main-menu-item';
								} else {
									$title = 'title-h6';
								}
								if (isset($params['title_font_weight'])) {
									$title .= ' ' . $params['title_font_weight'];
								} ?>
								<<?php echo $title_tag; ?> class="title">
									<span class="<?php echo $title; ?>"><a href="#" rel="bookmark">Title</a></span>
								</<?php echo $title_tag; ?>>
							<?php } ?>

							<?php if ( $params['version'] == 'default' && $params['show_additional_meta'] ): ?>
								<div class="info">Categories</div>
							<?php endif; ?>

							<?php if ( $version == 'new' && ( $params['blog_show_description'] || !$params['disable_socials'] ) ): ?>
								<?php if ( $params['blog_show_description'] ):
									$description_preset = '';
									if (isset($params['blog_description_preset']) && $params['blog_description_preset'] != 'default') {
										$description_preset = $params['blog_description_preset'];
									} else if ($params['version'] == 'list' && in_array($params['columns_desktop'], ['3x', '4x'])) {
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
