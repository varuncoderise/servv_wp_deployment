<?php
$thegem_size = $thegem_sizes[0];
$thegem_sources = $thegem_sizes[1];
$thegem_large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
if (!isset($portfolio_item_size)): ?>
	<div <?php post_class($thegem_classes); ?> data-default-sort="<?php echo intval(get_post()->menu_order); ?>" data-sort-date="<?php echo get_the_date('U'); ?>">
		<?php if ( $params['layout'] == 'creative' ) { ?>
		<div class="wrap-out">
			<?php } ?>
			<div class="wrap clearfix">
			<div <?php post_class($thegem_image_classes); ?>>
				<div class="image-inner">
					<?php thegem_post_picture($thegem_size, $thegem_sources, array('alt' => get_the_title())); ?>
				</div>
				<div class="overlay">
					<div class="overlay-circle"></div>
					<?php if (count($thegem_portfolio_item_data['types']) == 1 && $params['disable_socials'] || isset($params['search_portfolio']) ): ?>
						<?php
							if (isset($params['search_portfolio'])) {
								$thegem_ptype['type'] = 'self-link';
								$thegem_ptype['link_target'] = '';
							} else {
								$thegem_ptype = reset($thegem_portfolio_item_data['types']);
							}
							if($thegem_ptype['type'] == 'full-image') {
								$thegem_link = $thegem_large_image_url[0];
							} elseif($thegem_ptype['type'] == 'self-link') {
								$thegem_link = get_permalink();
							} elseif($thegem_ptype['type'] == 'youtube') {
								$thegem_link = '//www.youtube.com/embed/'.$thegem_ptype['link'].'?autoplay=1';
							} elseif($thegem_ptype['type'] == 'vimeo') {
								$thegem_link = '//player.vimeo.com/video/'.$thegem_ptype['link'].'?autoplay=1';
							} else {
								$thegem_link = $thegem_ptype['link'];
							}
							if(!$thegem_link) {
								$thegem_link = '#';
							}
							if($thegem_ptype['type'] == 'self_video') {
								$thegem_self_video = $thegem_ptype['link'];
								wp_enqueue_style('wp-mediaelement');
								wp_enqueue_script('thegem-mediaelement');
							}

						?>
						<a href="<?php echo esc_url($thegem_link); ?>" target="<?php echo esc_attr($thegem_ptype['link_target']); ?>" class="portfolio-item-link <?php echo esc_attr($thegem_ptype['type']); ?> <?php if($thegem_ptype['type'] == 'full-image') echo 'fancy'; ?>"></a>
					<?php endif; ?>
					<div class="links-wrapper">
						<div class="links">
							<?php if (isset($params['hover'])): ?>
							<div class="portfolio-icons">
								<?php foreach($thegem_portfolio_item_data['types'] as $thegem_ptype): ?>
									<?php
										if($thegem_ptype['type'] == 'full-image') {
											$thegem_link = $thegem_large_image_url[0];
										} elseif($thegem_ptype['type'] == 'self-link') {
											$thegem_link = get_permalink();
										} elseif($thegem_ptype['type'] == 'youtube') {
											$thegem_link = '//www.youtube.com/embed/'.$thegem_ptype['link'].'?autoplay=1';
										} elseif($thegem_ptype['type'] == 'vimeo') {
											$thegem_link = '//player.vimeo.com/video/'.$thegem_ptype['link'].'?autoplay=1';
										} else {
											$thegem_link = $thegem_ptype['link'];
										}
										if(!$thegem_link) {
											$thegem_link = '#';
										}
										if($thegem_ptype['type'] == 'self_video') {
											$thegem_self_video = $thegem_ptype['link'];
											wp_enqueue_style('wp-mediaelement');
											wp_enqueue_script('thegem-mediaelement');
										}
									?>
									<a href="<?php echo esc_url($thegem_link); ?>" target="<?php echo esc_attr($thegem_ptype['link_target']); ?>" class="icon <?php echo esc_attr($thegem_ptype['type']); ?> <?php if($thegem_ptype['type'] == 'full-image' && (count($thegem_portfolio_item_data['types']) > 1 || !$params['disable_socials'])) echo 'fancy'; ?>"><i class="default"></i></a>
								<?php endforeach; ?>
								<?php if(!$params['disable_socials']): ?>
									<a href="javascript: void(0);" class="icon share"><i class="default"></i></a>
								<?php endif; ?>

								<div class="overlay-line"></div>
								<?php if(!$params['disable_socials']): ?>
									<div class="portfolio-sharing-pane"><?php thegem_socials_sharing(); ?></div>
								<?php endif; ?>
							</div>
							<?php endif; ?>
							<?php if( ($params['display_titles'] == 'hover' && $params['columns'] != '1x') || $params['hover'] == 'gradient' || $params['hover'] == 'circular'): ?>
								<div class="caption">
									<?php $title = isset($params['title_preset']) && $params['title_preset'] != 'default' ? $params['title_preset'] : '';
									if (isset($params['title_font_weight']) && $params['title_font_weight'] != 'default') {
										$title_font_weight = $params['title_font_weight'];
									} else {
										$title_font_weight = !in_array($params['hover'], ['default', 'gradient', 'circular', 'zoom-overlay' ]) ? 'light' : '';
									} ?>
									<div class="title <?php echo isset($params['title_preset']) && $params['title_preset'] == 'default' ? 'title-h4' : ''; ?>">
										<span class="<?php echo esc_attr($title.' '.$title_font_weight); ?>">
											<?php if(!empty($thegem_portfolio_item_data['overview_title'])) : ?>
												<?php echo $thegem_portfolio_item_data['overview_title']; ?>
											<?php else : ?>
												<?php the_title(); ?>
											<?php endif; ?>
										</span>
									</div>
									<div class="description">
										<?php if (has_excerpt() && $params['show_description']) :
											$description_preset = isset($params['description_preset']) && $params['description_preset'] != 'default' ? $params['description_preset'] : ''; ?>
											<div class="subtitle"><span class="<?php echo esc_attr($description_preset); ?>"><?php the_excerpt(); ?></span></div>
										<?php endif; ?>
										<?php if($params['show_info']): ?>
											<div class="info">
												<?php if($params['columns'] == '1x'): ?>
													<?php echo get_the_date('j F, Y'); ?>&nbsp;
													<?php
														foreach ($slugs as $thegem_k => $thegem_slug)
															if (isset($thegem_terms_set[$thegem_slug]))
																echo '<a data-slug="'.$thegem_terms_set[$thegem_slug]->slug.'">'.$thegem_terms_set[$thegem_slug]->name.'</a>';
													?>
												<?php else: ?>
													<?php echo get_the_date('j F, Y'); ?> <?php if(count($slugs) > 0): ?><?php echo $params['categories_in_text']; ?><?php endif; ?>&nbsp;
													<?php
														$thegem_index = 0;
														foreach ($slugs as $thegem_k => $thegem_slug)
															if (isset($thegem_terms_set[$thegem_slug])) {
																echo ($thegem_index > 0 ? '<span class="portfolio-set-comma">,</span> ': '').'<a data-slug="'.$thegem_terms_set[$thegem_slug]->slug.'">'.$thegem_terms_set[$thegem_slug]->name.'</a>';
																$thegem_index++;
															}
													?>
												<?php endif; ?>
											</div>
										<?php endif ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<?php if( ($params['display_titles'] == 'page' || $params['columns'] == '1x') && $params['hover'] != 'gradient' && $params['hover'] != 'circular'): ?>
				<div <?php post_class($thegem_caption_classes); ?>>
					<?php $title = isset($params['title_preset']) && $params['title_preset'] != 'default' ? $params['title_preset'] : '';
					$title_font_weight = isset($params['title_font_weight']) && $params['title_font_weight'] != 'default' ? $params['title_font_weight'] : ''; ?>
					<div class="title">
						<?php if (isset($params['search_portfolio'])) { echo '<a href="' . esc_url(get_permalink()) . '">'; } ?>
						<span class="<?php echo esc_attr($title.' '.$title_font_weight); ?>">
							<?php if(!empty($thegem_portfolio_item_data['overview_title'])) : ?>
								<?php echo $thegem_portfolio_item_data['overview_title']; ?>
							<?php else : ?>
								<?php the_title(); ?>
							<?php endif; ?>
						</span>
						<?php if (isset($params['search_portfolio'])) { echo '</a>'; } ?>
					</div>
					<div class="caption-separator"></div>
					<?php if (has_excerpt() && $params['show_description']) :
						$description_preset = isset($params['description_preset']) && $params['description_preset'] != 'default' ? $params['description_preset'] : ''; ?>
						<div class="subtitle"><span class="<?php echo esc_attr($description_preset); ?>"><?php the_excerpt(); ?></span></div>
					<?php endif; ?>
					<?php if($params['show_info']): ?>
						<div class="info">
							<?php if($params['columns'] == '1x'): ?>
								<?php echo get_the_date('j F, Y'); ?>&nbsp;
								<?php
									foreach ($slugs as $thegem_k => $thegem_slug)
										if (isset($thegem_terms_set[$thegem_slug]))
											echo '<span class="separator">|</span><a data-slug="'.$thegem_terms_set[$thegem_slug]->slug.'">'.$thegem_terms_set[$thegem_slug]->name.'</a>';
								?>
							<?php else: ?>
								<?php echo get_the_date('j F, Y'); ?> <?php if(count($slugs) > 0) {echo $params['categories_in_text']; } ?>&nbsp;
								<?php
									$thegem_index = 0;
									foreach ($slugs as $thegem_k => $thegem_slug)
										if (isset($thegem_terms_set[$thegem_slug])) {
											echo ($thegem_index > 0 ? '<span class="sep"></span> ': '').'<a data-slug="'.$thegem_terms_set[$thegem_slug]->slug.'">'.$thegem_terms_set[$thegem_slug]->name.'</a>';
											$thegem_index++;
										}
								?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if($params['likes'] && $params['likes'] != 'false' && function_exists('zilla_likes')) {
						echo '<div class="portfolio-likes'.($params['show_info'] ? '' : ' visible').'"><i class="default"></i>';
						zilla_likes();
						echo '</div>';
					} ?>
				</div>
			<?php endif; ?>
		</div>
			<?php if ( $params['layout'] == 'creative' ) { ?>
		</div>
	<?php } ?>
	</div>
<?php else: ?>
	<div <?php post_class($thegem_classes); ?>>
		<?php if ($params['layout'] == 'creative') { ?>
			<div class="wrap-out">
				<div class="wrap clearfix">
					<div <?php post_class($thegem_image_classes); ?>>
						<div class="image-inner">
							<?php thegem_generate_picture('THEGEM_TRANSPARENT_IMAGE', $thegem_size, array(), array('alt' => get_the_title())); ?>
						</div>
					</div>

					<?php if (($params['display_titles'] == 'page' || $params['columns'] == '1x') && $params['hover'] != 'gradient' && $params['hover'] != 'circular'): ?>
						<div class="caption">
							<?php $title = isset($params['title_preset']) && $params['title_preset'] != 'default' ? $params['title_preset'] : '';
							$title_font_weight = isset($params['title_font_weight']) && $params['title_font_weight'] != 'default' ? $params['title_font_weight'] : ''; ?>
							<div class="title">
								<a href="#" rel="bookmark"><span class="<?php echo esc_attr($title.' '.$title_font_weight); ?>">Title</span></a>
							</div>

							<div class="caption-separator"></div>
							<?php if (has_excerpt() && $params['show_description']) :
								$description_preset = isset($params['description_preset']) && $params['description_preset'] != 'default' ? $params['description_preset'] : ''; ?>
								<div class="subtitle"><span class="<?php echo esc_attr($description_preset); ?>">subtitle</span></div>
							<?php endif; ?>
							<?php if ($params['show_info']): ?>
								<div class="info">
									info
								</div>
							<?php endif; ?>
							<?php if ($params['likes'] && $params['likes'] != 'false' && function_exists('zilla_likes')) {
								echo '<div class="portfolio-likes'.($params['show_info'] ? '' : ' visible').'"><i class="default"></i></div>';
							} ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php } ?>
	</div>
<?php endif; ?>
