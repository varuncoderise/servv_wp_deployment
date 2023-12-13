<?php

class TheGem_Template_Element_Loop_Featured_Media extends TheGem_Single_Post_Template_Element {
	public $show_in_loop = true;
	public $show_in_posts = false;
	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_loop_featured_media';
	}

	public function is_loop_builder_template() {
		if ((!empty($GLOBALS['thegem_template_type']) && $GLOBALS['thegem_template_type'] == 'loop-item') || thegem_is_template_post('loop-item')) {
			return true;
		}

		return false;
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'thumbnail' => 'justify',
			'image_ratio' => '1',
			'ignore_post_format_media' => '',
			'image_width' => '',
			'image_max_width' => '',
			'image_height' => '',
			'image_position' => 'left',
			'better_thumbs_quality' => '',
			'image_opacity_normal' => '100',
			'image_opacity_hover' => '100',
			'image_background_normal' => 'rgba(0, 0, 0, 0)',
			'image_background_hover' => 'rgba(0, 0, 0, 0.3)',
			'image_gradient_background' => '',
			'image_gradient_background_from' => '',
			'image_gradient_background_to' => '',
			'image_gradient_background_start' => '',
			'image_gradient_background_end' => '',
			'image_gradient_background_hover_from' => '',
			'image_gradient_background_hover_to' => '',
			'image_gradient_background_hover_start' => '',
			'image_gradient_background_hover_end' => '',
			'image_gradient_background_style' => 'linear',
			'image_gradient_background_angle' => 'to bottom',
			'image_gradient_background_custom_deg' => '',
			'image_gradient_radial_background_position' => 'at top',
			'image_gradient_radial_swap_colors' => '',
			'image_radius_normal_top_left' => '',
			'image_radius_normal_top_right' => '',
			'image_radius_normal_bottom_left' => '',
			'image_radius_normal_bottom_right' => '',
			'image_radius_hover_top_left' => '',
			'image_radius_hover_top_right' => '',
			'image_radius_hover_bottom_left' => '',
			'image_radius_hover_bottom_right' => '',
			'image_enable_shadow' => '',
			'image_shadow_color' => 'rgba(0, 0, 0, 0.15)',
			'image_shadow_color_hover' => 'rgba(0, 0, 0, 0.15)',
			'image_shadow_position' => 'outline',
			'image_shadow_horizontal' => '0',
			'image_shadow_vertical' => '5',
			'image_shadow_blur' => '5',
			'image_shadow_spread' => '-5',
			'container_gradient_background' => '',
			'container_gradient_background_from' => '',
			'container_gradient_background_to' => '',
			'container_gradient_background_start' => '',
			'container_gradient_background_end' => '',
			'container_gradient_background_hover_from' => '',
			'container_gradient_background_hover_to' => '',
			'container_gradient_background_style' => 'linear',
			'container_gradient_background_angle' => 'to bottom',
			'container_gradient_background_custom_deg' => '',
			'container_gradient_radial_background_position' => 'at top',
			'container_gradient_radial_swap_colors' => '',
			'container_enable_shadow' => '',
			'container_shadow_color' => 'rgba(0, 0, 0, 0.15)',
			'container_shadow_position' => 'outline',
			'container_shadow_horizontal' => '0',
			'container_shadow_vertical' => '5',
			'container_shadow_blur' => '5',
			'container_shadow_spread' => '-5',
			'container_layout_tablet_padding_top' => '',
			'container_layout_tablet_padding_bottom' => '',
			'container_layout_tablet_padding_left' => '',
			'container_layout_tablet_padding_right' => '',
			'container_layout_mobile_padding_top' => '',
			'container_layout_mobile_padding_bottom' => '',
			'container_layout_mobile_padding_left' => '',
			'container_layout_mobile_padding_right' => '',
			'container_layout_tablet_margin_top' => '',
			'container_layout_tablet_margin_bottom' => '',
			'container_layout_tablet_margin_left' => '',
			'container_layout_tablet_margin_right' => '',
			'container_layout_mobile_margin_top' => '',
			'container_layout_mobile_margin_bottom' => '',
			'container_layout_mobile_margin_left' => '',
			'container_layout_mobile_margin_right' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract(),
			$this->is_loop_builder_template() ? thegem_templates_dynamic_link_options_extract() : array()
		), $atts, 'thegem_te_loop_featured_media');

		// Init Shortcode
		ob_start();
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$single_post = thegem_templates_init_post();

		if (empty($single_post)) {
			ob_end_clean();
			return thegem_templates_close_single_post($this->get_name(), $this->shortcode_settings(), '');
		}

		// Post Data
		if (get_post_type($single_post->ID) == 'post' || get_post_type($single_post->ID) == 'page' || get_post_type($single_post->ID) == 'product') {
			$post_item_data = thegem_get_sanitize_post_data($single_post->ID);
		} else if ($single_post->post_type !== 'thegem_pf_item') {
			$post_item_data = thegem_get_sanitize_cpt_item_data($single_post->ID);
		}

		$post_format = get_post_format($single_post->ID);
		$is_ignore_post_format = !empty($params['ignore_post_format_media']);

		if ($single_post->post_type == 'thegem_pf_item') {
			$post_item_data = thegem_get_sanitize_pf_item_data($single_post->ID);

			switch ($post_item_data['grid_appearance_type']) {
				case 'featured_image':
					$post_format = 'image';
					break;
				case 'animated_gif':
					$post_format = 'gif';
					$post_item_data['gif'] = $post_item_data['grid_appearance_gif'];
					$post_item_data['gif_start'] = $post_item_data['grid_appearance_gif_start'];
					$post_item_data['gif_poster'] = $post_item_data['grid_appearance_gif_poster'];
					$post_item_data['gif_preload'] = $post_item_data['grid_appearance_gif_preload'];
					break;
				case 'video':
					$post_format = 'video';
					$post_item_data['video'] = $post_item_data['grid_appearance_video'];
					$post_item_data['video_type'] = $post_item_data['grid_appearance_video_type'];
					$post_item_data['video_aspect_ratio'] = $post_item_data['grid_appearance_video_aspect_ratio'];
					$post_item_data['video_play_on_mobile'] = $post_item_data['grid_appearance_video_play_on_mobile'];
					$post_item_data['video_overlay'] = $post_item_data['grid_appearance_video_overlay'];
					$post_item_data['video_poster'] = $post_item_data['grid_appearance_video_poster'];
					$post_item_data['video_start'] = $post_item_data['grid_appearance_video_start'];
					break;
				case 'gallery':
					$post_format = 'gallery';
					$post_item_data['gallery_autoscroll'] = $post_item_data['grid_appearance_gallery_autoscroll'];
					$post_item_data['gallery_autoscroll_speed'] = $post_item_data['grid_appearance_gallery_autoscroll_speed'];
					break;
			}
		}

		// Image thumbnails
		$thumbnail_settings = [];
		$thumbnail_id = get_post_thumbnail_id($single_post->ID);
		$is_post_thumbnail = has_post_thumbnail($single_post->ID);
		switch ($params['thumbnail']) {
			case 'justify':
				$ratio = !empty($params['better_thumbs_quality']) ? 'square-double' : 'square';
				$thumbnail_settings = ['layout' => 'justify', 'columns_desktop' => '2x', 'columns_tablet' => '2x', 'columns_mobile' => '1x', 'image_aspect_ratio' => $ratio];
				break;
			case 'masonry':
				$thumbnail_settings = ['layout' => 'masonry', 'columns_desktop' => '2x', 'columns_tablet' => '2x', 'columns_mobile' => '1x'];
				break;
			case 'full':
				$thumbnail_settings = ['image_size' => 'full'];
				break;
		}
		$thumbnail_sources = get_thegem_portfolio_render_item_image_sizes($thumbnail_settings);

		// Gif data
		$gif_image_class = '';
		if ($post_format == 'gif') {
			$gif_preload = true;
			if ($post_item_data['gif_start'] == 'play_on_hover' && empty($post_item_data['gif_preload'])) {
				$gif_preload = false;
				$gif_image_class = 'gif-load-on-hover';
			}
		}

		// Video data
		$video_ratio_class = '';
		if ($post_format == 'video') {
			$video_ratio = !empty($post_item_data['video_aspect_ratio']) ? $post_item_data['video_aspect_ratio'] : '';
			$video_ratio_arr = explode(":", $video_ratio);
			if (!empty($video_ratio_arr[0]) && !empty($video_ratio_arr[1])) {
				$video_aspect_ratio = $video_ratio_arr[0] / $video_ratio_arr[1];
				if ($post_item_data['video_start'] !== 'open_in_lightbox') {
					$video_ratio_class = 'custom-video-ratio';
				}
			}
		}

		// Dynamic Link Output
		$is_link = false;
		$output_dynamic_link = $output_dynamic_link_target = $output_dynamic_link_title = $output_dynamic_link_rel = '';
		if (!empty($params['dynamic_link_type'])) {
			switch ($params['dynamic_link_type']) {
				case 'post':
					$is_link = true;
					$output_dynamic_link = get_permalink($single_post);
					$output_dynamic_link_target = '_self';
					break;
				case 'custom':
					$dynamic_link = vc_build_link($params['dynamic_link_custom']);
					if (!empty($dynamic_link['url'])) {
						$is_link = true;
						$output_dynamic_link = $dynamic_link['url'];
						$output_dynamic_link_target = $dynamic_link['target'];
						$output_dynamic_link_title = $dynamic_link['title'];
						$output_dynamic_link_rel = $dynamic_link['rel'];
					}
					break;
			}
		}
		$output_link_attributes = 'href='.esc_url($output_dynamic_link).' target='.esc_attr($output_dynamic_link_target).'';
		$output_link_attributes .= !empty($output_dynamic_link_title) ? ' title='.$output_dynamic_link_title.'' : '';
		$output_link_attributes .= !empty($output_dynamic_link_rel) ? ' rel='.$output_dynamic_link_rel.'' : '';

		// Custom classes
		$image_ratio = !empty($params['image_ratio']) ? 'image-aspect-ratio' : '';
		$hover_effect = !empty($params['dynamic_link_hover_effect']) ? 'image-hover-effect' : '';
		$image_position = !empty($params['image_position']) ? 'image-position-' . $params['image_position'] : '';
		$without_image = !$is_post_thumbnail ? 'without-image' : '';
		$appearance_type = !empty($post_format) && !$is_ignore_post_format ? 'appearance-type-' . $post_format : 'appearance-type-image';
		$loop_item_type = !empty($params['thumbnail']) ? 'loop-item-type-' . $params['thumbnail'] : '';

		$params['element_class'] = implode(' ', array(
			$image_ratio,
			$video_ratio_class,
			$gif_image_class,
			$hover_effect,
			$image_position,
			$without_image,
			$appearance_type,
			$loop_item_type,
			$params['element_class'],
			thegem_templates_responsive_options_output($params),
		));

		// Layout Design Options
		$featured_media_wrap_class = '';
		if (!empty($atts['container_layout'])) {
			$custom_css_class = vc_shortcode_custom_css_class($atts['container_layout']);
			$featured_media_wrap_class = ' '.$custom_css_class;
			$featured_media_wrap_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $featured_media_wrap_class, 'thegem_te_loop_featured_media', $atts );
		}

		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?= esc_attr($params['element_id']); ?>"<?php endif; ?> class="thegem-te-loop-featured-media <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
			<?php if (($is_link && $post_format != 'gallery') || ($is_link && $is_ignore_post_format)): ?><a <?= $output_link_attributes ?>><?php endif; ?>
				<div class="featured-media <?= $featured_media_wrap_class ?>">
					<div class="media-inner-wrap">
						<?php if ($post_format == '' || $post_format == 'image' || !empty($params['ignore_post_format_media'])): ?>
							<?= thegem_generate_picture($thumbnail_id, $thumbnail_sources[0], $thumbnail_sources[1], array('alt' => get_the_title())); ?>
						<?php else: ?>
							<?php if ($post_format == 'gif'):
								if (!empty($post_item_data['gif'])) {
									$gif_src = wp_get_attachment_image_src($post_item_data['gif'], 'full');
									if ($gif_src) { ?>
										<img width="<?php echo $gif_src[1]; ?>" height="<?php echo $gif_src[2]; ?>" class="gem-gif-portfolio" src="<?php if ($gif_preload) { echo $gif_src[0]; } ?>" <?php if (!$gif_preload) { echo 'data-src="' . $gif_src[0] . '"'; } ?>>
									<?php }
								} else {
									thegem_generate_picture($thumbnail_id, $thumbnail_sources[0], array(), array('alt' => get_the_title()));
								}

								if ($post_item_data['gif_start'] == 'play_on_hover') {
									if (!empty($post_item_data['gif_poster'])) { ?>
										<img class="gem-gif-poster" src="<?php echo $post_item_data['gif_poster']; ?>">
									<?php } else {
										thegem_generate_picture($post_item_data['gif'], $thumbnail_sources[0], $thumbnail_sources[1], array("class" => "gem-gif-poster"));
									}
								}
							endif; ?>

							<?php if ($post_format == 'video'):
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
								} ?>
								<?php if ($post_item_data['video_start'] == 'open_in_lightbox') {
									if ($post_item_data['video_type'] == 'self' && !empty($post_item_data['video_poster'])) {
										$thumbnail_id = attachment_url_to_postid($post_item_data['video_poster']); ?>
										<div class="portfolio-item-link">
											<?= thegem_generate_picture($thumbnail_id, $thumbnail_sources[0], $thumbnail_sources[1], array('alt' => get_the_title())); ?>
										</div>
									<?php } else if ($is_post_thumbnail) { ?>
										<div class="portfolio-item-link">
											<?= thegem_generate_picture($thumbnail_id, $thumbnail_sources[0], $thumbnail_sources[1], array('alt' => get_the_title())); ?>
										</div>
									<?php } ?>
									<a href="<?php echo esc_url($thegem_video_link); ?>" class="portfolio-video-icon <?php echo $thegem_video_class; ?>" <?php if (isset($aspect_ratio)) { ?>data-ratio="<?php echo $aspect_ratio; ?>"<?php } ?> data-fancybox="thegem-portfolio" data-elementor-open-lightbox="no"></a>
								<?php } else {
									$play_on_mobile = true;
									if (!$post_item_data['video_play_on_mobile']) {
										echo thegem_generate_picture($thumbnail_id, $thumbnail_sources[0], $thumbnail_sources[1], array('alt' => get_the_title(), "class" => "video-image-mobile"));
										$play_on_mobile = false;
									}
									$autoplay = $post_item_data['video_start'] == 'autoplay'; ?>

									<div class="gem-video-portfolio type-<?= $post_item_data['video_type']; ?> <?= $autoplay ? 'autoplay' : 'play-on-hover'; ?> <?= $play_on_mobile ? '' : 'hide-on-mobile'; ?> <?= !$autoplay || $play_on_mobile ? 'run-embed-video' : ''; ?>" data-video-type="<?= $post_item_data['video_type']; ?>" data-video-id="<?= $video_id; ?>" <?php if (isset($video_aspect_ratio)) { ?>style="aspect-ratio: <?php echo $video_aspect_ratio; ?>"<?php } ?>>
										<?php echo thegem_portfolio_video_background(
											$post_item_data['video_type'],
											$video_id,
											$post_item_data['video_overlay'],
											$post_item_data['video_poster'],
											$autoplay,
											$play_on_mobile
										); ?>
									</div>
								<?php } ?>
							<?php endif; ?>

							<?php if ($post_format == 'audio'):
								echo thegem_get_post_featured_content($single_post->ID, '', false, [], true);
							endif; ?>

							<?php if ($post_format == 'quote'):
								echo thegem_get_post_featured_content($single_post->ID, '', false, [], false);
							endif; ?>

							<?php if ($post_format == 'gallery'):
								$thegem_gallery_images_urls = [];
								if ($single_post->post_type == 'thegem_pf_item') {
									$thegem_gallery_images_ids = get_post_meta(get_the_ID(), 'thegem_portfolio_item_gallery_images', true);
								} else {
									/*if (metadata_exists('post', $post_item_data['gallery'], 'thegem_gallery_images')) {
										$thegem_gallery_images_ids = get_post_meta($post_item_data['gallery'], 'thegem_gallery_images', true);
									} else {
										$attachments_ids = get_posts('post_parent=' . $post_item_data['gallery'] . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids');
										$thegem_gallery_images_ids = implode(',', $attachments_ids);
									}*/


									$post_item_data = thegem_get_sanitize_post_data(get_the_ID());
									if(in_array(get_post_type(), thegem_get_available_po_custom_post_types(), true)) {
										$post_item_data = thegem_get_sanitize_cpt_item_data(get_the_ID());
									}
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

								if ($thegem_gallery_images_ids) {
									$attachments_ids = array_filter(explode(',', $thegem_gallery_images_ids)); ?>
									<div class="portfolio-image-slider <?php if (!empty($post_item_data['gallery_autoscroll'])) { echo 'autoscroll'; } ?>" <?php if (!empty($post_item_data['gallery_autoscroll'])) { ?> data-interval="<?php echo isset($post_item_data['gallery_autoscroll_speed']) ? $post_item_data['gallery_autoscroll_speed'] : $post_item_data['gallery_autoscroll']; ?>"<?php } ?>>
										<?php foreach ($attachments_ids as $i => $slide) {
											$slide_image = wp_get_attachment_image($slide, $thumbnail_sources[0]);
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
								<?php } ?>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			<?php if (($is_link && $post_format != 'gallery') || ($is_link && $is_ignore_post_format)): ?></a><?php endif; ?>
		</div>

		<?php
		//Custom Styles
		$customize = '.thegem-te-loop-featured-media.'.$uniqid;
		$custom_css = '';
		$resolution = array('tablet', 'mobile');
		$behavior = array('normal', 'hover');
		$indents = array('padding', 'margin');
		$directions = array('top', 'bottom', 'left', 'right');

		// Layout Styles
		if (!empty($params['image_ratio'])) {
			$custom_css .= $customize.':not(.custom-video-ratio).image-aspect-ratio .media-inner-wrap {aspect-ratio: ' . $params['image_ratio'] . ';}';
		}

		// Image Sizes
		if (!empty($params['image_width']) || strcmp($params['image_width'], '0') === 0) {
			$result = str_replace(' ', '', $params['image_width']);
			$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
			$custom_css .= $customize . ' .media-inner-wrap {width:' . $result . $unit . ';}';
		}
		if (!empty($params['image_max_width']) || strcmp($params['image_max_width'], '0') === 0) {
			$result = str_replace(' ', '', $params['image_max_width']);
			$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
			$custom_css .= $customize . ' .media-inner-wrap {max-width:' . $result . $unit . ';}';
		}
		if (!empty($params['image_height']) || strcmp($params['image_height'], '0') === 0) {
			$result = str_replace(' ', '', $params['image_height']);
			$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
			$custom_css .= $customize . ' .media-inner-wrap {height:' . $result . $unit . ';}';
		}

		// Image Overlay
		if (!empty($params['image_opacity_normal'])) {
			$custom_css .= $customize . ' .media-inner-wrap img {opacity: calc(' . $params['image_opacity_normal'] . '/100);}';
		}
		if (!empty($params['image_opacity_hover'])) {
			$custom_css .= $customize . '.image-hover-effect a:hover .media-inner-wrap img {opacity: calc(' . $params['image_opacity_hover'] . '/100);}';
		}
		if (!empty($params['image_background_normal'])) {
			$custom_css .= $customize . ' .media-inner-wrap:before {background-color: ' . $params['image_background_normal'] . ';}';
			$custom_css .= $customize . ' .media-inner-wrap .portfolio-image-slider:before {background-color: ' . $params['image_background_normal'] . ';}';
		}
		if (!empty($params['image_background_hover'])) {
			$custom_css .= $customize . '.image-hover-effect a:hover .media-inner-wrap:before {background-color: ' . $params['image_background_hover'] . ';}';
		}
		if (!empty($params['image_gradient_background'])) {
			$color_from = !empty($params['image_gradient_background_from']) ? $params['image_gradient_background_from'] : 'rgba(0,0,0,0)';
			$color_to = !empty($params['image_gradient_background_to']) ? $params['image_gradient_background_to'] : 'rgba(0,0,0,0)';
			$color_start = $params['image_gradient_background_start'] !== '' ? floatval($params['image_gradient_background_start']) : 0;
			$color_end = $params['image_gradient_background_end'] !== '' ? floatval($params['image_gradient_background_end']) : 100;
			$color_from_hover = !empty($params['image_gradient_background_hover_from']) ? $params['image_gradient_background_hover_from'] : 'rgba(0,0,0,0)';
			$color_to_hover = !empty($params['image_gradient_background_hover_to']) ? $params['image_gradient_background_hover_to'] : 'rgba(0,0,0,0)';
			$color_start_hover = $params['image_gradient_background_hover_start'] !== '' ? floatval($params['image_gradient_background_hover_start']) : 0;
			$color_end_hover = $params['image_gradient_background_hover_end'] !== '' ? floatval($params['image_gradient_background_hover_end']) : 100;


			if (!empty($params['image_gradient_background_style']) && $params['image_gradient_background_style'] == 'linear') {
				$angle = $params['image_gradient_background_angle'];
				if ($params['image_gradient_background_angle'] == 'custom_deg' && !empty($params['image_gradient_background_custom_deg'])) {
					$angle = $params['image_gradient_background_custom_deg'].'deg';
				}

				if (!empty($color_from) || !empty($color_to)) {
					$custom_css .= $customize . ' .media-inner-wrap:before {background: linear-gradient(' . $angle . ', ' . $color_from . ' ' . $color_start . '%, ' . $color_to . ' ' . $color_end . '%);}';
					$custom_css .= $customize . ' .media-inner-wrap .portfolio-image-slider:before {background: linear-gradient(' . $angle . ', ' . $color_from . ' ' . $color_start . '%, ' . $color_to . ' ' . $color_end . '%);}';
				}

				if (!empty($color_from_hover) || !empty($color_to_hover)) {
					$custom_css .= $customize . '.image-hover-effect a:hover .media-inner-wrap:before {background: linear-gradient(' . $angle . ', ' . $color_from_hover . ' ' . $color_start_hover . '%, ' . $color_to_hover . ' ' . $color_end_hover . '%);}';
				}
			}

			if (!empty($params['image_gradient_background_style']) && $params['image_gradient_background_style'] == 'radial') {
				$angle = $params['image_gradient_radial_background_position'];
				if (!empty($color_from) || !empty($color_to)) {
					$custom_css .= $customize . ' .media-inner-wrap:before {background: radial-gradient(' .$angle . ', ' . $color_from . ' ' . $color_start . '%, ' . $color_to . ' ' . $color_start . '%);}';
					$custom_css .= $customize . ' .media-inner-wrap .portfolio-image-slider:before {background: radial-gradient(' .$angle . ', ' . $color_from . ' ' . $color_start . '%, ' . $color_to . ' ' . $color_start . '%);}';
				}

				if (!empty($color_from_hover) || !empty($color_to_hover)) {
					$custom_css .= $customize . '.image-hover-effect a:hover .media-inner-wrap:before {background: radial-gradient(' . $angle . ', ' . $color_from_hover . ' ' . $color_start_hover . '%, ' . $color_to_hover . ' ' . $color_end_hover . '%);}';
				}
			}
		}

		// Image Radius
		foreach ($behavior as $beh) {
			if (!empty($params['image_radius_'.$beh.'_top_left']) || strcmp($params['image_radius_'.$beh.'_top_left'], '0') === 0) {
				$result = str_replace(' ', '', $params['image_radius_'.$beh.'_top_left']);
				$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';

				if ($beh == 'normal') {
					$custom_css .= $customize . ' .media-inner-wrap {border-top-left-radius:' . $result . $unit . ';}';
				} else {
					$custom_css .= $customize . '.image-hover-effect a:hover .media-inner-wrap {border-top-left-radius:' . $result . $unit . ';}';
				}
			}
			if (!empty($params['image_radius_'.$beh.'_top_right']) || strcmp($params['image_radius_'.$beh.'_top_right'], '0') === 0) {
				$result = str_replace(' ', '', $params['image_radius_'.$beh.'_top_right']);
				$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';

				if ($beh == 'normal') {
					$custom_css .= $customize . ' .media-inner-wrap {border-top-right-radius:' . $result . $unit . ';}';
				} else {
					$custom_css .= $customize . '.image-hover-effect a:hover .media-inner-wrap {border-top-right-radius:' . $result . $unit . ';}';
				}
			}
			if (!empty($params['image_radius_'.$beh.'_bottom_left']) || strcmp($params['image_radius_'.$beh.'_bottom_left'], '0') === 0) {
				$result = str_replace(' ', '', $params['image_radius_'.$beh.'_bottom_left']);
				$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';

				if ($beh == 'normal') {
					$custom_css .= $customize . ' .media-inner-wrap {border-bottom-left-radius:' . $result . $unit . ';}';
				} else {
					$custom_css .= $customize . '.image-hover-effect a:hover .media-inner-wrap {border-bottom-left-radius:' . $result . $unit . ';}';
				}
			}
			if (!empty($params['image_radius_'.$beh.'_bottom_right']) || strcmp($params['image_radius_'.$beh.'_bottom_right'], '0') === 0) {
				$result = str_replace(' ', '', $params['image_radius_'.$beh.'_bottom_right']);
				$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';

				if ($beh == 'normal') {
					$custom_css .= $customize . ' .media-inner-wrap {border-bottom-right-radius:' . $result . $unit . ';}';
				} else {
					$custom_css .= $customize . '.image-hover-effect a:hover .media-inner-wrap {border-bottom-right-radius:' . $result . $unit . ';}';
				}
			}
		}

		// Image Shadow
		if (!empty($params['image_enable_shadow'])) {
			$color = !empty($params['image_shadow_color']) ? $params['image_shadow_color'] : '';
			$color_hover = !empty($params['image_shadow_color_hover']) ? $params['image_shadow_color_hover'] : '';
			$horizontal = !empty($params['image_shadow_horizontal']) ? $params['image_shadow_horizontal'] : '0';
			$vertical = !empty($params['image_shadow_vertical']) ? $params['image_shadow_vertical'] : '0';
			$blur = !empty($params['image_shadow_blur']) ? $params['image_shadow_blur'] : '0';
			$spread = !empty($params['image_shadow_spread']) ? $params['image_shadow_spread'] : '0';

			if (!empty($params['image_shadow_position']) && $params['image_shadow_position'] == 'outline') {
				if (!empty($color)) {
					$custom_css .= $customize . ' .media-inner-wrap {box-shadow: ' . $horizontal . 'px ' . $vertical . 'px ' . $blur . 'px ' . $spread . 'px ' . $color . ';}';
				}

				if (!empty($color_hover)) {
					$custom_css .= $customize . '.image-hover-effect a:hover .media-inner-wrap {box-shadow: ' . $horizontal . 'px ' . $vertical . 'px ' . $blur . 'px ' . $spread . 'px ' . $color_hover . ';}';
				}
			}

			if (!empty($params['image_shadow_position']) && $params['image_shadow_position'] == 'inset') {
				if (!empty($color)) {
					$custom_css .= $customize . ' .media-inner-wrap {box-shadow: inset ' . $horizontal . 'px ' . $vertical . 'px ' . $blur . 'px ' . $spread . 'px ' . $color . ';}';
				}

				if (!empty($color_hover)) {
					$custom_css .= $customize . '.image-hover-effect a:hover .media-inner-wrap {box-shadow: inset ' . $horizontal . 'px ' . $vertical . 'px ' . $blur . 'px ' . $spread . 'px ' . $color_hover . ';}';
				}
			}
		}

		// Container Layout Responsive
		foreach ($resolution as $res) {
			foreach ($indents as $ind) {
				foreach ($directions as $dir) {
					if (!empty($params['container_layout_'.$res.'_'.$ind.'_'.$dir]) || strcmp($params['container_layout_'.$res.'_'.$ind.'_'.$dir], '0') === 0) {
						$result = str_replace(' ', '', $params['container_layout_'.$res.'_'.$ind.'_'.$dir]);
						$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
						$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' .featured-media {'.$ind.'-'.$dir.':'.$result.$unit.' !important;}}';
					}
				}
			}
		}

		// Container Gradient
		if (!empty($params['container_gradient_background'])) {
			$color_from = !empty($params['container_gradient_background_from']) ? $params['container_gradient_background_from'] : 'rgba(0,0,0,0)';
			$color_to = !empty($params['container_gradient_background_to']) ? $params['container_gradient_background_to'] : 'rgba(0,0,0,0)';
			$color_start = $params['container_gradient_background_start'] !== '' ? floatval($params['container_gradient_background_start']) : 0;
			$color_end = $params['container_gradient_background_end'] !== '' ? floatval($params['container_gradient_background_end']) : 100;

			if (!empty($params['container_gradient_background_style']) && $params['container_gradient_background_style'] == 'linear') {
				$angle = $params['container_gradient_background_angle'];
				if ($params['container_gradient_background_angle'] == 'custom_deg' && !empty($params['container_gradient_background_custom_deg'])) {
					$angle = $params['container_gradient_background_custom_deg'].'deg';
				}

				if (!empty($color_from) || !empty($color_to)) {
					$custom_css .= $customize . ' .featured-media {background: linear-gradient(' . $angle . ', ' . $color_from . ' ' . $color_start . '%, ' . $color_to . ' ' . $color_end . '%) !important;}';
				}
			}

			if (!empty($params['container_gradient_background_style']) && $params['container_gradient_background_style'] == 'radial') {
				$angle = $params['container_gradient_radial_background_position'];
				if (!empty($color_from) || !empty($color_to)) {
					$custom_css .= $customize . ' .featured-media {background: radial-gradient(' .$angle . ', ' . $color_from . ', ' . $color_to . ') !important;}';
				}
			}
		}

		// Container Shadow
		if (!empty($params['container_enable_shadow'])) {
			$color = !empty($params['container_shadow_color']) ? $params['container_shadow_color'] : '';
			$horizontal = !empty($params['container_shadow_horizontal']) ? $params['container_shadow_horizontal'] : '0';
			$vertical = !empty($params['container_shadow_vertical']) ? $params['container_shadow_vertical'] : '0';
			$blur = !empty($params['container_shadow_blur']) ? $params['container_shadow_blur'] : '0';
			$spread = !empty($params['container_shadow_spread']) ? $params['container_shadow_spread'] : '0';

			if (!empty($params['container_shadow_position']) && $params['container_shadow_position'] == 'outline') {
				if (!empty($color)) {
					$custom_css .= $customize . ' .featured-media {box-shadow: ' . $horizontal . 'px ' . $vertical . 'px ' . $blur . 'px ' . $spread . 'px ' . $color . ' !important;}';
				}
			}

			if (!empty($params['container_shadow_position']) && $params['container_shadow_position'] == 'inset') {
				if (!empty($color)) {
					$custom_css .= $customize . ' .featured-media {box-shadow: inset ' . $horizontal . 'px ' . $vertical . 'px ' . $blur . 'px ' . $spread . 'px ' . $color . ' !important;}';
				}
			}
		}

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}

		$return_html = $css_output.$return_html;
		return thegem_templates_close_single_post($this->get_name(), $this->shortcode_settings(), $return_html);
	}

	public function set_layout_params() {
		$result = [];
		$group = __('General', 'thegem');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('General', 'thegem'),
			'param_name' => 'layout_delim_head_general',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Image Size', 'thegem'),
			'param_name' => 'thumbnail',
			'value' => array_merge(array(
					__('Thumbnail for Justified Grids', 'thegem') => 'justify',
					__('Thumbnail for Masonry Grids', 'thegem') => 'masonry',
					__('Full Size', 'thegem') => 'full',
				)
			),
			'std' => 'justify',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Image Ratio', 'thegem'),
			'param_name' => 'image_ratio',
			'std' => '1',
			'dependency' => array(
				'element' => 'thumbnail',
				'value' => ['justify', 'full']
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'description' => __('Specify a decimal value, i.e. instead of 3:4, specify 0.75. Use a dot as a decimal separator. Leave blank to show the original image ratio', 'thegem'),
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Ignore Post Format Media', 'thegem'),
			'param_name' => 'ignore_post_format_media',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'description' => __('If activated, only posts featured images will be displayed. All other post format media (like videos or galleries) will be ignored.', 'thegem'),
			'group' => $group
		);

		return $result;
	}

	public function set_image_style_params() {
		$result = array();
		$group = __('Image Style', 'thegem');
		$resolutions = array('desktop', 'tablet', 'mobile');
		$behavior = array('normal', 'hover');
		$directions = array('top', 'bottom', 'left', 'right');

		// Image Sizes
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Width', 'thegem'),
			'param_name' => 'image_width',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Max Width', 'thegem'),
			'param_name' => 'image_max_width',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Height', 'thegem'),
			'param_name' => 'image_height',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'dependency' => array(
				'element' => 'thumbnail',
				'value' => array('justify', 'full')
			),
			'group' => $group
		);

		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('', 'thegem'),
			'param_name' => 'image_size_description_sub_delim_head',
			'edit_field_class' => 'vc_column vc_col-sm-12 no-top-padding',
			'description' => __('Note: px units are used by default. For % units add values with %, eg. 10%', 'thegem'),
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Position', 'thegem'),
			'param_name' => 'image_position',
			'value' => array(
				__('Left', 'thegem') => 'left',
				__('Center', 'thegem') => 'centered',
				__('Right', 'thegem') => 'right',
			),
			'std' => 'left',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Better Thumbnails Quality', 'thegem'),
			'param_name' => 'better_thumbs_quality',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '',
			'dependency' => array(
				'element' => 'thumbnail',
				'value' => array('justify', 'full')
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		// Image Opacity
		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('Opacity', 'thegem'),
			'param_name' => 'opacity_sub_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Opacity Normal', 'thegem'),
			'param_name' => 'image_opacity_normal',
			'std' => '100',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Opacity Hover', 'thegem'),
			'param_name' => 'image_opacity_hover',
			'std' => '100',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('', 'thegem'),
			'param_name' => 'image_opacity_description_sub_delim_head',
			'edit_field_class' => 'vc_column vc_col-sm-12 no-top-padding',
			'description' => __('Note: % units are used by default.', 'thegem'),
			'group' => $group
		);

		// Image Background
		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('Overlay', 'thegem'),
			'param_name' => 'background_sub_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Overlay Background Normal', 'thegem'),
			'param_name' => 'image_background_normal',
			'std' => 'rgba(0, 0, 0, 0)',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Overlay Background Hover', 'thegem'),
			'param_name' => 'image_background_hover',
			'std' => 'rgba(0, 0, 0, 0.3)',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Use Gradient Overlay', 'thegem'),
			'param_name' => 'image_gradient_background',
			'value' => array(__('Yes', 'thegem') => '1'),
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Color 1', 'thegem'),
			'param_name' => 'image_gradient_background_from',
			'dependency' => array(
				'element' => 'image_gradient_background',
				'value' => '1'
			),
			"edit_field_class" => "vc_col-sm-5 vc_column",
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Location (0-100)', 'thegem'),
			'param_name' => 'image_gradient_background_start',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'dependency' => array(
				'element' => 'image_gradient_background',
				'value' => '1'
			),
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Color 2', 'thegem'),
			'param_name' => 'image_gradient_background_to',
			'dependency' => array(
				'element' => 'image_gradient_background',
				'value' => '1'
			),
			"edit_field_class" => "vc_col-sm-5 vc_column",
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Location (0-100)', 'thegem'),
			'param_name' => 'image_gradient_background_end',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'dependency' => array(
				'element' => 'image_gradient_background',
				'value' => '1'
			),
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Hover Color 1', 'thegem'),
			'param_name' => 'image_gradient_background_hover_from',
			'dependency' => array(
				'element' => 'image_gradient_background',
				'value' => '1'
			),
			"edit_field_class" => "vc_col-sm-5 vc_column",
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Location (0-100)', 'thegem'),
			'param_name' => 'image_gradient_background_hover_start',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'dependency' => array(
				'element' => 'image_gradient_background',
				'value' => '1'
			),
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Hover Color 2', 'thegem'),
			'param_name' => 'image_gradient_background_hover_to',
			'dependency' => array(
				'element' => 'image_gradient_background',
				'value' => '1'
			),
			"edit_field_class" => "vc_col-sm-5 vc_column",
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Location (0-100)', 'thegem'),
			'param_name' => 'image_gradient_background_hover_end',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'dependency' => array(
				'element' => 'image_gradient_background',
				'value' => '1'
			),
			'group' => $group
		);

		$result[] = array(
			"type" => "dropdown",
			'heading' => __('Style', 'thegem'),
			'param_name' => 'image_gradient_background_style',
			"value" => array(
				__('Linear', "thegem") => "linear",
				__('Radial', "thegem") => "radial",
			),
			"std" => 'linear',
			'dependency' => array(
				'element' => 'image_gradient_background',
				'value' => '1'
			),
			"edit_field_class" => "vc_col-sm-4 vc_column",
			'group' => $group
		);

		$result[] = array(
			"type" => "dropdown",
			'heading' => __('Gradient Position', 'thegem'),
			'param_name' => 'image_gradient_radial_background_position',
			"value" => array(
				__('Top', "thegem") => "at top",
				__('Bottom', "thegem") => "at bottom",
				__('Right', "thegem") => "at right",
				__('Left', "thegem") => "at left",
				__('Center', "thegem") => "at center",
			),
			'dependency' => array(
				'element' => 'image_gradient_background_style',
				'value' => 'radial'
			),
			"edit_field_class" => "vc_col-sm-4 vc_column",
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Swap Colors', 'thegem'),
			'param_name' => 'image_gradient_radial_swap_colors',
			'value' => array(__('Yes', 'thegem') => '1'),
			'dependency' => array(
				'element' => 'image_gradient_background_style',
				'value' => 'radial'
			),
			"edit_field_class" => "vc_col-sm-4 vc_column",
			'group' => $group
		);

		$result[] = array(
			"type" => "dropdown",
			'heading' => __('Custom Angle', 'thegem'),
			'param_name' => 'image_gradient_background_angle',
			"value" => array(
				__('Vertical to bottom ↓', "thegem") => "to bottom",
				__('Vertical to top ↑', "thegem") => "to top",
				__('Horizontal to left →', "thegem") => "to right",
				__('Horizontal to right ←', "thegem") => "to left",
				__('Diagonal from left to bottom ↘', "thegem") => "to bottom right",
				__('Diagonal from left to top ↗', "thegem") => "to top right",
				__('Diagonal from right to bottom ↙', "thegem") => "to bottom left",
				__('Diagonal from right to top ↖', "thegem") => "to top left",
				__('Custom', "thegem") => "custom_deg",
			),
			'dependency' => array(
				'element' => 'image_gradient_background_style',
				'value' => 'linear'
			),
			"edit_field_class" => "vc_col-sm-4 vc_column",
			'group' => $group
		);

		$result[] = array(
			"type" => "textfield",
			'heading' => __('Angle', 'thegem'),
			'param_name' => 'image_gradient_background_custom_deg',
			'description' => __('Set value in DG 0-360', 'thegem'),
			'dependency' => array(
				'element' => 'image_gradient_background_angle',
				'value' => 'custom_deg'
			),
			"edit_field_class" => "vc_col-sm-4 vc_column",
			'group' => $group
		);

		// Image Radius
		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('Radius', 'thegem'),
			'param_name' => 'radius_sub_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
			'group' => $group
		);

		foreach ($behavior as $beh) {
			$result[] = array(
				'type' => 'textfield',
				'heading' => __('Top Left Radius ' . ucfirst($beh), 'thegem'),
				'param_name' => 'image_radius_'.$beh.'_top_left',
				'edit_field_class' => 'vc_column vc_col-sm-3',
				'group' => $group
			);

			$result[] = array(
				'type' => 'textfield',
				'heading' => __('Top Right Radius ' . ucfirst($beh), 'thegem'),
				'param_name' => 'image_radius_'.$beh.'_top_right',
				'edit_field_class' => 'vc_column vc_col-sm-3',
				'group' => $group
			);

			$result[] = array(
				'type' => 'textfield',
				'heading' => __('Bottom Left Radius ' . ucfirst($beh), 'thegem'),
				'param_name' => 'image_radius_'.$beh.'_bottom_left',
				'edit_field_class' => 'vc_column vc_col-sm-3',
				'group' => $group
			);

			$result[] = array(
				'type' => 'textfield',
				'heading' => __('Bottom Right Radius ' . ucfirst($beh), 'thegem'),
				'param_name' => 'image_radius_'.$beh.'_bottom_right',
				'edit_field_class' => 'vc_column vc_col-sm-3',
				'group' => $group
			);
		}

		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('', 'thegem'),
			'param_name' => 'image_radius_description_sub_delim_head',
			'edit_field_class' => 'vc_column vc_col-sm-12 no-top-padding',
			'description' => __('Note: px units are used by default. For % units add values with %, eg. 10%', 'thegem'),
			'group' => $group
		);

		// Image Shadow
		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('Shadow', 'thegem'),
			'param_name' => 'shadow_sub_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading-two-level border-top margin-top vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Enable Shadow', 'thegem'),
			'param_name' => 'image_enable_shadow',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Shadow Color Normal', 'thegem'),
			'param_name' => 'image_shadow_color',
			'std' => 'rgba(0, 0, 0, 0.15)',
			'dependency' => array(
				'element' => 'image_enable_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Shadow Color Hover', 'thegem'),
			'param_name' => 'image_shadow_color_hover',
			'std' => 'rgba(0, 0, 0, 0.15)',
			'dependency' => array(
				'element' => 'image_enable_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Position', 'thegem'),
			'param_name' => 'image_shadow_position',
			'value' => array(
				__('Outline', 'thegem') => 'outline',
				__('Inset', 'thegem') => 'inset'
			),
			'std' => 'outline',
			'dependency' => array(
				'element' => 'image_enable_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Horizontal', 'thegem'),
			'param_name' => 'image_shadow_horizontal',
			'std' => '0',
			'dependency' => array(
				'element' => 'image_enable_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-3',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Vertical', 'thegem'),
			'param_name' => 'image_shadow_vertical',
			'std' => '5',
			'dependency' => array(
				'element' => 'image_enable_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-3',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Blur', 'thegem'),
			'param_name' => 'image_shadow_blur',
			'std' => '5',
			'dependency' => array(
				'element' => 'image_enable_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-3',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Spread', 'thegem'),
			'param_name' => 'image_shadow_spread',
			'std' => '-5',
			'dependency' => array(
				'element' => 'image_enable_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-3',
			'group' => $group
		);

		return $result;
	}

	public function set_container_style_params() {
		$result = array();
		$group = __('Container Style', 'thegem');
		$resolution = array('tablet', 'mobile');
		$indents = array('padding', 'margin');
		$directions = array('top', 'bottom', 'left', 'right');

		// Design Options
		$result[] = array(
			'type' => 'css_editor',
			'heading' => __('CSS box', 'thegem'),
			'param_name' => 'container_layout',
			'group' => $group
		);

		foreach ($resolution as $res) {
			$result[] = array(
				'type' => 'thegem_delimeter_heading',
				'heading' => __(ucfirst($res), 'thegem'),
				'param_name' => $res.'_container_layout_heading',
				'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
				'group' => __($group, 'thegem')
			);

			foreach ($indents as $ind) {
				$result[] = array(
					'type' => 'thegem_delimeter_heading_two_level',
					'heading' => __($ind, 'thegem'),
					'param_name' => $res.'_'.$ind.'_container_layout_sub_heading',
					'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12 capitalize',
					'group' => __($group, 'thegem')
				);
				foreach ($directions as $dir) {
					$result[] = array(
						'type' => 'textfield',
						'heading' => __(ucfirst($dir), 'thegem'),
						'param_name' => 'container_layout_' . $res.'_'.$ind.'_'.$dir,
						'value' => '',
						'edit_field_class' => 'vc_column vc_col-sm-3 capitalize',
						'group' => __($group, 'thegem')
					);
				}
			}

			$result[] = array(
				'type' => 'thegem_delimeter_heading_two_level',
				'heading' => __('', 'thegem'),
				'param_name' => $res.'_description_layout_sub_heading',
				'edit_field_class' => 'vc_column vc_col-sm-12 no-top-padding',
				'description' => __('Note: px units are used by default. For % units add values with %, eg. 10%', 'thegem'),
				'group' => __($group, 'thegem')
			);
		}

		// Gradient
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Gradient', 'thegem'),
			'param_name' => 'gradient_sub_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => __($group, 'thegem')
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Use Gradient Background', 'thegem'),
			'param_name' => 'container_gradient_background',
			'value' => array(__('Yes', 'thegem') => '1'),
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Color 1', 'thegem'),
			'param_name' => 'container_gradient_background_from',
			'dependency' => array(
				'element' => 'container_gradient_background',
				'value' => '1'
			),
			"edit_field_class" => "vc_col-sm-5 vc_column",
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Location (0-100)', 'thegem'),
			'param_name' => 'container_gradient_background_start',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'dependency' => array(
				'element' => 'container_gradient_background',
				'value' => '1'
			),
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Color 2', 'thegem'),
			'param_name' => 'container_gradient_background_to',
			'dependency' => array(
				'element' => 'container_gradient_background',
				'value' => '1'
			),
			"edit_field_class" => "vc_col-sm-5 vc_column",
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Location (0-100)', 'thegem'),
			'param_name' => 'container_gradient_background_end',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'dependency' => array(
				'element' => 'container_gradient_background',
				'value' => '1'
			),
			'group' => $group
		);

		$result[] = array(
			"type" => "dropdown",
			'heading' => __('Style', 'thegem'),
			'param_name' => 'container_gradient_background_style',
			"value" => array(
				__('Linear', "thegem") => "linear",
				__('Radial', "thegem") => "radial",
			),
			"std" => 'linear',
			'dependency' => array(
				'element' => 'container_gradient_background',
				'value' => '1'
			),
			"edit_field_class" => "vc_col-sm-4 vc_column",
			'group' => $group
		);

		$result[] = array(
			"type" => "dropdown",
			'heading' => __('Gradient Position', 'thegem'),
			'param_name' => 'container_gradient_radial_background_position',
			"value" => array(
				__('Top', "thegem") => "at top",
				__('Bottom', "thegem") => "at bottom",
				__('Right', "thegem") => "at right",
				__('Left', "thegem") => "at left",
				__('Center', "thegem") => "at center",
			),
			'dependency' => array(
				'element' => 'container_gradient_background_style',
				'value' => 'radial'
			),
			"edit_field_class" => "vc_col-sm-4 vc_column",
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Swap Colors', 'thegem'),
			'param_name' => 'container_gradient_radial_swap_colors',
			'value' => array(__('Yes', 'thegem') => '1'),
			'dependency' => array(
				'element' => 'container_gradient_background_style',
				'value' => 'radial'
			),
			"edit_field_class" => "vc_col-sm-4 vc_column",
			'group' => $group
		);

		$result[] = array(
			"type" => "dropdown",
			'heading' => __('Custom Angle', 'thegem'),
			'param_name' => 'container_gradient_background_angle',
			"value" => array(
				__('Vertical to bottom ↓', "thegem") => "to bottom",
				__('Vertical to top ↑', "thegem") => "to top",
				__('Horizontal to left →', "thegem") => "to right",
				__('Horizontal to right ←', "thegem") => "to left",
				__('Diagonal from left to bottom ↘', "thegem") => "to bottom right",
				__('Diagonal from left to top ↗', "thegem") => "to top right",
				__('Diagonal from right to bottom ↙', "thegem") => "to bottom left",
				__('Diagonal from right to top ↖', "thegem") => "to top left",
				__('Custom', "thegem") => "custom_deg",
			),
			'dependency' => array(
				'element' => 'container_gradient_background_style',
				'value' => 'linear'
			),
			"edit_field_class" => "vc_col-sm-4 vc_column",
			'group' => $group
		);

		$result[] = array(
			"type" => "textfield",
			'heading' => __('Angle', 'thegem'),
			'param_name' => 'container_gradient_background_custom_deg',
			'description' => __('Set value in DG 0-360', 'thegem'),
			'dependency' => array(
				'element' => 'container_gradient_background_angle',
				'value' => 'custom_deg'
			),
			"edit_field_class" => "vc_col-sm-4 vc_column",
			'group' => $group
		);

		// Shadow
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Shadow', 'thegem'),
			'param_name' => 'shadow_sub_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => __($group, 'thegem')
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Enable Shadow', 'thegem'),
			'param_name' => 'container_enable_shadow',
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Shadow color', 'thegem'),
			'param_name' => 'container_shadow_color',
			'std' => 'rgba(0, 0, 0, 0.15)',
			'dependency' => array(
				'element' => 'container_enable_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Position', 'thegem'),
			'param_name' => 'container_shadow_position',
			'value' => array(
				__('Outline', 'thegem') => 'outline',
				__('Inset', 'thegem') => 'inset'
			),
			'std' => 'outline',
			'dependency' => array(
				'element' => 'container_enable_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Horizontal', 'thegem'),
			'param_name' => 'container_shadow_horizontal',
			'std' => '0',
			'dependency' => array(
				'element' => 'container_enable_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-3',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Vertical', 'thegem'),
			'param_name' => 'container_shadow_vertical',
			'std' => '5',
			'dependency' => array(
				'element' => 'container_enable_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-3',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Blur', 'thegem'),
			'param_name' => 'container_shadow_blur',
			'std' => '5',
			'dependency' => array(
				'element' => 'container_enable_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-3',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Spread', 'thegem'),
			'param_name' => 'container_shadow_spread',
			'std' => '-5',
			'dependency' => array(
				'element' => 'container_enable_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-3',
			'group' => $group
		);

		return $result;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Featured Media', 'thegem'),
			'base' => 'thegem_te_loop_featured_media',
			'icon' => 'thegem-icon-wpb-ui-element-post-featured-content',
			'category' => __('Single Post Builder', 'thegem'),
			'description' => __('Featured Media (Single Post Builder)', 'thegem'),
			'params' => array_merge(

				/* General - Layout */
				$this->set_layout_params(),

				/* Style - Image */
				$this->set_image_style_params(),

				/* Style - Container */
				$this->set_container_style_params(),

				/* Dynamic Link */
				thegem_set_elements_dynamic_link(['hover' => true]),

				/* Extra Options */
				thegem_set_elements_extra_options( ),

				/* Responsive Options */
				thegem_set_elements_responsive_options()
			),
		);
	}
}

$templates_elements['thegem_te_loop_featured_media'] = new TheGem_Template_Element_Loop_Featured_Media();
$templates_elements['thegem_te_loop_featured_media']->init();
