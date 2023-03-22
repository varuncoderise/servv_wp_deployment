<div <?php post_class($thegem_classes); ?> data-sort-date="<?php echo get_the_date('U'); ?>">
	<div class="wrap clearfix">
		<div <?php post_class($thegem_image_classes); ?>>
			<div class="image-inner">
				<?php if (has_post_thumbnail()) : ?>
					<img src="<?php echo esc_url($thegem_small_image_url[0]); ?>" width="<?php echo esc_attr($thegem_small_image_url[1]); ?>" height="<?php echo esc_attr($thegem_small_image_url[2]); ?>" alt="<?php the_title(); ?>" />
				<?php endif; ?>
			</div>
			<div class="overlay">
				<div class="overlay-circle"></div>
				<?php if (count($thegem_portfolio_item_data['types']) == 1 && $params['disable_socials']): ?>
					<?php
						$thegem_ptype = reset($thegem_portfolio_item_data['types']);
						if($thegem_ptype['type'] == 'full-image') {
							$thegem_link = $thegem_large_image_url[0];
						} elseif($thegem_ptype['type'] == 'self-link') {
							$thegem_link = get_permalink();
							$thegem_bottom_line = true;
							$thegem_portfolio_button_link = $thegem_link;
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
						<div class="portfolio-icons">
							<?php foreach($thegem_portfolio_item_data['types'] as $thegem_ptype): ?>
								<?php
									if($thegem_ptype['type'] == 'full-image') {
										$thegem_link = $thegem_large_image_url[0];
									} elseif($thegem_ptype['type'] == 'self-link') {
										$thegem_link = get_permalink();
										$thegem_bottom_line = true;
										$thegem_portfolio_button_link = $thegem_link;
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
						<?php if($params['display_titles'] == 'hover' || $params['hover'] == 'gradient' || $params['hover'] == 'circular'): ?>
							<div class="caption">
								<div class="title title-h4">
									<span class="<?php if ($params['hover'] != 'default' && $params['hover'] != 'gradient' && $params['hover'] != 'circular') { echo 'light'; } ?>">
										<?php if(!empty($thegem_portfolio_item_data['overview_title'])) : ?>
											<?php echo $thegem_portfolio_item_data['overview_title']; ?>
										<?php else : ?>
											<?php the_title(); ?>
										<?php endif; ?>
									</span>
								</div>
								<div class="description">
									<?php if(has_excerpt()) : ?><div class="subtitle"><span><?php the_excerpt(); ?></span></div><?php endif; ?>
									<?php if($params['show_info']): ?>
										<div class="info">
											<?php if($params['layout'] == '1x'): ?>
												<?php echo get_the_date('j F, Y'); ?>&nbsp;
												<?php
													foreach ($slugs as $thegem_k => $thegem_slug)
														if (isset($thegem_terms_set[$thegem_slug]))
															echo '<a data-slug="'.$thegem_terms_set[$thegem_slug]->slug.'">'.$thegem_terms_set[$thegem_slug]->name.'</a>';
												?>
											<?php else: ?>
												<?php echo get_the_date('j F, Y'); ?> <?php if(count($slugs) > 0) { echo $params['categories_in_text']; } ?>&nbsp;
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
		<?php if($params['display_titles'] == 'page' && $params['hover'] != 'gradient' && $params['hover'] != 'circular'): ?>
			<div <?php post_class($thegem_caption_classes); ?> style="<?php if ($params['background_color']): ?>background-color: <?php echo esc_attr($params['background_color']) ?>;<?php endif; ?> <?php if ($params['border_color']): ?>border-color: <?php echo esc_attr($params['border_color']) ?>;<?php endif; ?>">
				<div class="caption-sizable-content<?php echo ($thegem_bottom_line ? ' with-bottom-line' : ''); ?>">
					<div class="title title-h3">
						<span class="light" <?php if ($params['title_color']): ?>style="color: <?php echo esc_attr($params['title_color']) ?>"<?php endif; ?>>
							<?php if(!empty($thegem_portfolio_item_data['overview_title'])) : ?>
								<?php echo $thegem_portfolio_item_data['overview_title']; ?>
							<?php else : ?>
								<?php the_title(); ?>
							<?php endif; ?>
						</span>
					</div>
					<?php if($params['show_info']): ?>
						<div class="info clearfix">
							<div class="caption-separator-line"><?php echo get_the_date('j F, Y'); ?></div><!--
							<?php if($params['likes'] && $params['likes'] != 'false' && function_exists('zilla_likes') ) { echo '--><div class="caption-separator-line-hover"> <span class="sep"></span> <span class="post-meta-likes portfolio-list-likes"><i class="default"></i>';zilla_likes();echo '</span></div><!--'; } ?>
						--></div>
					<?php endif; ?>
					<?php if(has_excerpt()) : ?>
						<div class="subtitle" <?php if ($params['desc_color']): ?>style="color: <?php echo esc_attr($params['desc_color']) ?>"<?php endif; ?>><span><?php the_excerpt(); ?></span></div>
					<?php elseif($thegem_title_data['title_excerpt']) : ?>
						<div class="subtitle" <?php if ($params['desc_color']): ?>style="color: <?php echo esc_attr($params['desc_color']) ?>"<?php endif; ?>><?php echo nl2br($thegem_page_data['title_excerpt']); ?></div>
					<?php endif; ?>
					<?php if($params['show_info']): ?>
						<div class="info">
							<?php
								if(count($slugs) > 0) { echo $params['categories_in_text']; }
								$thegem_index = 0;
								foreach ($slugs as $thegem_k => $thegem_slug) {
									if (isset($thegem_terms_set[$thegem_slug])) {
										echo ($thegem_index > 0 ? '<span class="portfolio-set-comma">,</span> ': '').' <a data-slug="'.$thegem_terms_set[$thegem_slug]->slug.'">'.$thegem_terms_set[$thegem_slug]->name.'</a>';
										$thegem_index++;
									}
								}
								?>
						</div>
					<?php endif; ?>
					<?php if($params['background_color']): ?>
						<div class="after-overlay" <?php if ($params['background_color']): ?>style="box-shadow: 0 0 30px 75px <?php echo esc_attr($params['background_color']) ?>;"<?php endif; ?>></div>
					<?php endif; ?>
				</div>
				<div class="caption-bottom-line">
					<?php if($thegem_portfolio_item_data['project_link']) { thegem_button(array('size' => 'tiny', 'href' => $thegem_portfolio_item_data['project_link'] , 'text' => ($thegem_portfolio_item_data['project_text'] ? $thegem_portfolio_item_data['project_text'] : __('Launch', 'thegem')), 'extra_class' => 'project-button'), 1); } ?>
					<?php if($thegem_portfolio_button_link) { thegem_button(array('size' => 'tiny', 'text' => __('Details', 'thegem'), 'style' => 'outline', 'href' => get_permalink()), 1); } ?>
					<?php if(!$params['disable_socials']): ?>
						<div class="post-footer-sharing"><?php thegem_button(array('icon' => 'share', 'size' => 'tiny'), 1); ?><div class="sharing-popup"><?php thegem_socials_sharing(); ?><svg class="sharing-styled-arrow"><use xlink:href="<?php echo esc_url(THEGEM_THEME_URI . '/css/post-arrow.svg'); ?>#dec-post-arrow"></use></svg></div></div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
