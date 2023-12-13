<?php

class TheGem_Template_Element_Product_Gallery extends TheGem_Product_Template_Element {

	public function __construct() {
		if ( !defined('THEGEM_TE_PRODUCT_GALLERY_DIR' )) {
			define('THEGEM_TE_PRODUCT_GALLERY_DIR', rtrim(__DIR__, ' /\\'));
		}

		if ( !defined('THEGEM_TE_PRODUCT_GALLERY_URL') ) {
			define('THEGEM_TE_PRODUCT_GALLERY_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}

		wp_register_script('thegem-te-product-gallery', THEGEM_TE_PRODUCT_GALLERY_URL . '/js/product-gallery.js', array('jquery', 'owl', 'owl-zoom'), false, true);
		wp_register_script('thegem-te-product-gallery-grid', THEGEM_TE_PRODUCT_GALLERY_URL . '/js/product-gallery-grid.js', array('jquery', 'owl-zoom'), false, true);

		wp_register_style('thegem-te-product-gallery', THEGEM_TE_PRODUCT_GALLERY_URL . '/css/product-gallery.css', array('owl'));
	}

	public function get_name() {
		return 'thegem_te_product_gallery';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'type' => 'horizontal',
			'thumb_on_mobile' => '0',
			'thumbs_position' => 'left',
			'show_image' => 'hover',
			'product_image' => '1',
			'product_video' => '1',
			'zoom' => '1',
			'lightbox' => '1',
			'auto_height' => '1',
			'labels' => '1',
			'label_sale' => '1',
			'label_new' => '1',
			'label_out_stock' => '1',
			'elements_color' => '',
			'retina_ready' => '',
			'single_columns' => '1',
			'single_gaps' => '',
			'grid_columns' => '1x',
			'grid_gaps' => '42',
			'grid_image_count' => '',
			'image_ratio' => '',
			'grid_image_size' => 'default',
			'grid_image_ratio' => '',
			'video_autoplay' => '',
			'skeleton_loader' => '',
			'dots_color' => '',
			'dots_color_active' => '',
		),
            thegem_templates_extra_options_extract(),
            thegem_templates_design_options_extract('single-product')
        ), $atts, 'thegem_te_product_gallery');

		// Enqueue Scripts
		if ($params['type'] != 'grid') {
			wp_enqueue_script('thegem-te-product-gallery');
		} else {
			wp_enqueue_script('thegem-te-product-gallery-grid');
        }
		wp_enqueue_style('thegem-te-product-gallery');

		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-product-gallery', $params);

		// Init Gallery
		ob_start();
		$product = thegem_templates_init_product();
		global $post;

		if (empty($product)) {
			ob_end_clean();
			return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), '');
		}

		//Init grid params
		$grid_column_width = round( 100 / mb_substr( $params['grid_columns'], 0, 1 ), 4 );
		$grid_gaps_size = !empty($params['grid_gaps']) ? round( $params['grid_gaps'] / 2 ) : 0;
		$is_images_sizes = thegem_get_option( 'woocommerce_activate_images_sizes' );

		//Init images params
		$attachments_ids = array();
		if (has_post_thumbnail() && !empty($params['product_image'])) {
			$attachments_ids = array(get_post_thumbnail_id());
		}
		$attachments_ids = array_merge($attachments_ids, $product->get_gallery_image_ids());
		if ('variable' === $product->get_type()) {
			foreach ($product->get_available_variations() as $variation) {
				if (has_post_thumbnail($variation['variation_id'])) {
					$thumbnail_id = get_post_thumbnail_id($variation['variation_id']);
					if (!in_array($thumbnail_id, $attachments_ids)) {
						$attachments_ids[] = $thumbnail_id;
					}
				}
			}
		}
		if (empty($attachments_ids)) {
			{ ob_end_clean(); return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), ''); }
		}
		$gallery_uid = uniqid();

		$firstImagePath = wp_get_original_image_path($attachments_ids[0]);
		$isSingleImg = count($attachments_ids) < 2;
		$isSingleImgSkeleton = $isSingleImg ? ' product-gallery-skeleton-single' : '';
		$isSquareImg = '1';
		if ($firstImagePath) {
			$firstImageSize = wp_getimagesize($firstImagePath);
			$skeletonPadding = 100 * $firstImageSize[1] / $firstImageSize[0];
			if ($skeletonPadding > 100) {
				$isSquareImg = '';
			}
		}

		//Init skeleton params
		$isVertical = $params['type'] == 'vertical';
		$isVerticalSkeleton = $isVertical ? 'product-gallery-skeleton-vertical' : '';
		$isVerticalSkeleton .= $isSquareImg == '1' ? ' product-gallery-skeleton-vertical-square' : '';

        //Init video params
		$product_video_data = get_post_meta($post->ID, 'thegem_product_video', true);
		$product_video = thegem_get_sanitize_product_video_data($product_video_data);
		$video_type = $product_video['product_video_type'];
		$video = $product_video['product_video_id'];
		$video_self = $product_video['product_video_link'];
		$poster = $product_video['product_video_thumb'];
		$poster_id = attachment_url_to_postid($poster);

		if (!empty($video) && $video_type == 'youtube') {
			$youtube_id = thegem_parcing_youtube_url($video);
		}

		if (!empty($video) && $video_type == 'vimeo') {
			$vimeo_id = thegem_parcing_vimeo_url($video);
		}

		$link = '';
		if ($video_type == 'youtube' || $video_type == 'vimeo') {
			if ($video_type == 'youtube' && $youtube_id) {
				$link = '//www.youtube.com/embed/' . $youtube_id . '?playlist=' . $youtube_id . '&autoplay=1&mute=1&controls=1&loop=1&showinfo=0&autohide=1&iv_load_policy=3&rel=0&disablekb=1&wmode=transparent';

				if ($poster) {
					$video_block = '<iframe src="' . esc_url($link) . '" frameborder="0" muted="muted" allowfullscreen></iframe>';
				} else {
					$video_block = '<div id="productYoutubeVideo" data-yt-id="' . $youtube_id . '"></div>';
				}
			}
			if ($video_type == 'vimeo' && $vimeo_id) {
				$link = '//player.vimeo.com/video/' . $vimeo_id . '?autoplay=1&muted=1&controls=1&loop=1&title=0&badge=0&byline=0&autopause=0&autohide=1';

				if ($poster) {
					$video_block = '<iframe src="' . esc_url($link) . '" frameborder="0" muted="muted" allowfullscreen></iframe>';
				} else {
					$video_block = '<div id="productVimeoVideo" data-vm-id="' . $vimeo_id . '"></div>';
				}
			}
		} else if ($video_type == 'self') {
			$link = $video_self;
			$video_self_autoplay = $params['video_autoplay'] ? 'playsinline autoplay' : null;
			$video_block = '<video id="productSelfVideo" class="fancybox-video" style="opacity: 0;" controls disablePictureInPicture controlsList="nodownload" loop="loop" '.$video_self_autoplay.' src="' . $link . '" muted="muted"' . ( $poster ? ' poster="' . esc_url($poster) . '"' : '' ) . '></video>';
		}

		$type = 'product-gallery--'.$params['type'];
		$aspect_ratio = !empty($params['image_ratio']) || (!empty($params['grid_image_size']) && $params['grid_image_size'] == 'full' && !empty($params['grid_image_ratio'])) ? 'image-aspect-ratio' : '';
		$params['element_class'] = implode(' ', array($params['element_class'], $type, $aspect_ratio));

		?>

        <script>
            function firstImageLoaded() {
                (function ($) {
                    var $galleryElement = $('.product-gallery'),
                        isVertical = $galleryElement.attr("data-thumb") === 'vertical',
                        isTrueCount = $('.product-gallery-slider-item', $galleryElement).length > 1,
                        isMobile = $(window).width() < 768 && /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? true : false,
                        isDots = $galleryElement.attr("data-thumb") === 'dots';

                    if (isVertical && isTrueCount && !isMobile && !isDots) {
                        if ($galleryElement.data('square-img')) {
                            $galleryElement.css('height', $galleryElement.width() * 0.7411).css('overflow', 'hidden');
                        } else {
                            $galleryElement.css('height', $galleryElement.width() - 30).css('overflow', 'hidden');
                        }

                        if ($galleryElement.data("thumb-position") == 'right') {
                            $galleryElement.addClass('is-vertical-inited-right');
                        } else {
                            $galleryElement.addClass('is-vertical-inited');
                        }
                    }
                    $galleryElement.prev('.preloader').remove();
                })(jQuery);
            }

            function firstImageGridLoaded() {
                (function ($) {
                    let $image = $('.product-gallery-grid-item img');
                    let $video = $('.product-gallery-grid-item video');
                    $image.prev('.preloader').remove();
                    $video.prev('.preloader').remove();
                })(jQuery);
            }
        </script>

		<?php if(function_exists('vc_is_page_editable') && vc_is_page_editable() && $params['type'] != 'grid') { ?>
            <script>
                (function ($) {
                    $('body').updateProductGalleries();
                })(jQuery);

                firstImageGridLoaded();
            </script>
		<?php } ?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?> class="thegem-te-product-gallery <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>" <?=thegem_data_editor_attribute($uniqid.'-editor')?>>
			<?php if ($params['type'] == 'native'): ?>
				<!--Product gallery native output-->
				<?php
					remove_action( 'woocommerce_before_single_product_summary', 'thegem_socials_sharing', 30 );
					remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_single_meta', 35 );
					do_action('woocommerce_before_single_product_summary');
				?>
			<?php elseif ($params['type'] == 'grid'): ?>
				<!--Product gallery grid output-->
				<div class="product-gallery-grid col-<?=$params['grid_columns']?>"
				     data-gallery="<?=$params['type']?>"
				     data-fancy="<?=$params['lightbox']?>"
				     data-zoom="<?=$params['zoom']?>"
				     data-color="<?=$params['elements_color']?>">

					<div class="product-gallery-grid-wrap" style="margin: -<?=$grid_gaps_size?>px; display: flex; flex-wrap: wrap;">
						<!--Product gallery grid images output-->
						<?php
						$attachments_grid_ids = !empty($params['grid_image_count']) ? array_slice($attachments_ids, 0, $params['grid_image_count']) : $attachments_ids;
						foreach ( $attachments_grid_ids as $key => $attachments_id ) {
							if ( $is_images_sizes && $params['grid_columns'] != '1x' && $params['grid_image_size'] == 'default') {
								$thumb_image_url = thegem_generate_thumbnail_src( $attachments_id, 'thegem-product-single' );
								$thumb_image_url_2x = thegem_generate_thumbnail_src( $attachments_id, 'thegem-product-single-2x' );
							} else {
								$thumb_image_url = wp_get_attachment_image_src( $attachments_id, 'full' );
								$thumb_image_url_2x = wp_get_attachment_image_src( $attachments_id, 'full' );
							}
							$full_image_url = wp_get_attachment_image_src( $attachments_id, 'full' );
							?>

							<?php if ( $thumb_image_url || $full_image_url ): ?>
								<div class="product-gallery-grid-item"
								     data-image-id="<?= esc_attr( $attachments_id ); ?>"
								     style="width: <?=$grid_column_width?>%; padding: <?=$grid_gaps_size?>px;">

									<!--Product gallery grid labels output-->
									<?php if ( $params['labels'] && $key == 0): ?>
										<div class="product-gallery-grid-elements" style="opacity: 0;">
											<div class="product-gallery-labels"><?=thegem_woocommerce_single_product_gallery_labels()?></div>
										</div>
									<?php endif; ?>

									<div class="product-gallery-image <?php if ( $params['zoom'] ): ?>init-zoom<?php endif;?> <?php if ( $params['lightbox'] ): ?>init-fancy<?php endif;?>">
										<?php if ( $params['lightbox'] ): ?>
											<a href="<?= esc_url( $full_image_url[0] ); ?>"
											   class="fancy-product-gallery"
											   data-fancybox-group="product-gallery-<?= esc_attr( $gallery_uid ); ?>"
											   data-fancybox="product-gallery-<?= esc_attr( $gallery_uid ); ?>"
											   data-full-image-url="<?= esc_url( $full_image_url[0] ); ?>">
												<div class="image-inner">
													<i class="product-gallery-fancy" style="opacity: 0;"></i>
													<?php if ( $params['skeleton_loader'] ): ?>
														<span class="preloader skeleton product-grid-gallery-skeleton"></span>
													<?php endif; ?>
													<img
															src="<?= esc_url( $thumb_image_url[0] ); ?>"
														<?php if ( $params['retina_ready'] ): ?>
															srcset="<?= esc_url( $thumb_image_url_2x[0] ); ?> 2x"
														<?php endif; ?>
															class="img-responsive"
															width="<?=$thumb_image_url[1]?>" height="<?=$thumb_image_url[2]?>"
															alt="<?= thegem_gallery_get_alt_text( $attachments_id ) ?>"
															onload="if(this.previousElementSibling && this.previousElementSibling.classList.contains('preloader')) { this.previousElementSibling.remove(); }"
													/>
												</div>
											</a>
										<?php else: ?>
											<div class="image-inner">
												<?php if ( $params['skeleton_loader'] ): ?>
													<span class="preloader skeleton product-grid-gallery-skeleton"></span>
												<?php endif; ?>
												<img
														src="<?= esc_url( $thumb_image_url[0] ); ?>"
													<?php if ( $params['retina_ready'] ): ?>
														srcset="<?php echo esc_url( $thumb_image_url_2x[0] ); ?> 2x"
													<?php endif; ?>
														class="img-responsive"
														width="<?=$thumb_image_url[1]?>" height="<?=$thumb_image_url[2]?>"
														alt="<?= thegem_gallery_get_alt_text( $attachments_id ) ?>"
														onload="if(this.previousElementSibling && this.previousElementSibling.classList.contains('preloader')) { this.previousElementSibling.remove(); }"
												/>
											</div>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>

							<?php
						}

						if ( isset( $video_block ) && !empty($params['product_video']) ) { ?>
							<?php
							if ( $is_images_sizes && $params['grid_columns'] != '1x') {
								$thumb_image_url = thegem_generate_thumbnail_src( $poster_id, 'thegem-product-single' );
								$thumb_image_url_2x = thegem_generate_thumbnail_src( $poster_id, 'thegem-product-single-2x' );
							} else {
								$thumb_image_url = wp_get_attachment_image_src( $poster_id, 'full' );
								$thumb_image_url_2x = wp_get_attachment_image_src( $poster_id, 'full' );
							}
							?>
							<!--Product gallery grid video output-->
							<div class="product-gallery-grid-item <?php if ( !$poster || $video_type == 'self' ): ?>item--video<?php endif; ?>"
							     data-video-type="<?= $video_type ?>"
							     data-video-autoplay="<?= $params['video_autoplay'] ?>"
							     data-video-poster="<?= $poster_id ?>"
							     data-video-gaps="<?= $grid_gaps_size ?>"
							     style="width: <?=$grid_column_width?>%; padding: <?=$grid_gaps_size?>px; background-color: transparent; overflow: hidden;">
								<?php if ( $params['lightbox'] ): ?>
									<a href="<?= $link ?>"
									   class="fancy-product-gallery"
									   data-fancybox-group="product-gallery-<?= esc_attr( $gallery_uid ); ?>"
									   data-fancybox="product-gallery-<?= esc_attr( $gallery_uid ); ?>">
										<?php if ( $poster && $video_type != 'self' ): ?>
											<div class="image-inner">
												<i class="icon-play <?= $video_type ?>"></i>
												<?php if ( $params['skeleton_loader'] ): ?>
													<span class="preloader skeleton product-grid-gallery-skeleton"></span>
												<?php endif; ?>
												<img src="<?= esc_url( $thumb_image_url[0] ); ?>"
													<?php if ( $params['retina_ready'] ): ?>
														srcset="<?= esc_url( $thumb_image_url_2x[0] ); ?> 2x"
													<?php endif; ?>
													 class="img-responsive" style="width: 100%; height: auto;"
													 width="<?=$thumb_image_url[1]?>" height="<?=$thumb_image_url[2]?>"
													 alt="<?= thegem_gallery_get_alt_text( $poster_id ) ?>"
													 onload="if(this.previousElementSibling && this.previousElementSibling.classList.contains('preloader')) { this.previousElementSibling.remove(); }"
												/>
											</div>
										<?php else: ?>
											<span class="preloader skeleton product-grid-gallery-skeleton"></span>
											<?= $video_block ?>
										<?php endif; ?>
									</a>
								<?php else: ?>
									<span class="preloader skeleton product-grid-gallery-skeleton"></span>
									<?= $video_block ?>
								<?php endif; ?>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php else: ?>
				<!--Skeleton loader output-->
				<?php if (!empty($params['skeleton_loader'])) {
					echo '<div class="preloader skeleton product-gallery-skeleton ' . $isVerticalSkeleton . $isSingleImgSkeleton . '" >';
					echo '<div class="product-gallery-skeleton-image" style="padding-bottom:' . $skeletonPadding . '%"></div>';
					if (!$isSingleImg && ($params['type'] == 'horizontal' || $params['type'] == 'vertical')) {
						echo '<div class="product-gallery-skeleton-thumbs product-gallery-skeleton-thumbs-' . $params['type'] . '"></div>';
					}
					echo '</div>';
				} ?>
				<div class="product-gallery <?= $params['type'] ?>"
				     data-type="<?= esc_attr($params['show_image']); ?>"
				     data-thumb="<?= esc_attr($params['type']); ?>"
				     data-thumb-on-mobile="<?= esc_attr($params['thumb_on_mobile']); ?>"
				     data-thumb-position="<?= esc_attr($params['thumbs_position']); ?>"
				     data-fancy="<?= esc_attr($params['lightbox']); ?>"
				     data-zoom="<?= esc_attr($params['zoom']); ?>"
				     data-colors="<?= esc_attr($params['elements_color']); ?>"
				     data-auto-height="<?= esc_attr($params['auto_height']); ?>"
				     data-video-autoplay="<?= $params['video_autoplay'] ?>"
				     data-items-count="<?= esc_attr($params['single_columns']); ?>"
				     data-items-gaps="<?= esc_attr($params['single_gaps']); ?>"
				     data-square-img="<?= esc_attr($isSquareImg); ?>">

					<!--Preview gallery output-->
					<div class="product-gallery-slider-wrap <?= $params['lightbox'] ? 'init-fancy' : null; ?> <?= !empty($params['elements_color']) ? 'init-color' : null; ?>"
					     data-color="<?= esc_attr($params['elements_color']); ?>">
						<div class="product-gallery-slider owl-carousel <?= $params['type'] == 'dots' ? 'dots' : null; ?>">
							<?php
							// Preview gallery images output
							foreach ($attachments_ids as $i => $attachments_id) {
								$full_image_url = wp_get_attachment_image_src($attachments_id, 'full');
								if ($full_image_url): ?>
									<div class="product-gallery-slider-item"
									     <?php if ($params['type'] == 'single' && $params['single_columns'] > 1 && !vc_is_page_editable()): ?>style="opacity: 0"<?php endif; ?>
									     data-image-id="<?= esc_attr($attachments_id); ?>">
										<div class="product-gallery-image <?= $params['zoom'] ? 'init-zoom' : null ?>">
											<?php if ($params['lightbox']): ?>
												<a href="<?= esc_url($full_image_url[0]); ?>" class="fancy-product-gallery"
												   data-fancybox-group="product-gallery-<?= esc_attr($gallery_uid); ?>"
												   data-fancybox="product-gallery-<?= esc_attr($gallery_uid); ?>"
												   data-full-image-url="<?= esc_url($full_image_url[0]); ?>">
													<div class="image-inner">
														<img src="<?= esc_url($full_image_url[0]); ?>"
														     data-ww="<?php echo esc_url($full_image_url[0]); ?>"
														     alt="<?= thegem_gallery_get_alt_text($attachments_id) ?>"
														     class="img-responsive"
															<?php if ($i == 0 && !vc_is_page_editable()) { ?>
																onload="firstImageLoaded()"
															<?php } ?>
														/>
													</div>
												</a>
											<?php else: ?>
												<div class="image-inner">
													<img src="<?= esc_url($full_image_url[0]); ?>"
													     data-ww="<?php echo esc_url($full_image_url[0]); ?>"
													     alt="<?= thegem_gallery_get_alt_text($attachments_id) ?>"
													     class="img-responsive"
														<?php if ($i == 0 && !vc_is_page_editable()) { ?>
															onload="firstImageLoaded()"
														<?php } ?>
													/>
												</div>
											<?php endif; ?>
										</div>
									</div>
								<?php endif;
							}

							// Preview gallery video output
							if (isset($video_block) && !empty($params['product_video'])) { ?>
								<div class="product-gallery-slider-item <?php if (!$poster || $video_type == 'self'): ?>item--video<?php endif; ?>" data-video-type="<?= $video_type ?>">
									<?php if ($params['lightbox']): ?>
										<a href="<?= $link ?>"
										   class="fancy-product-gallery"
										   data-fancybox-group="product-gallery-<?= esc_attr($gallery_uid); ?>"
										   data-fancybox="product-gallery-<?= esc_attr($gallery_uid); ?>">
											<?php if ($poster && $video_type != 'self'): ?>
												<div class="image-inner">
													<img src="<?php echo esc_url($poster); ?>"
													     alt="<?= thegem_gallery_get_alt_text($poster_id) ?>"
													     class="img-responsive">
												</div>

												<i class="icon-play <?= $video_type ?>"></i>
											<?php else: ?>
												<?= $video_block ?>
											<?php endif; ?>
										</a>
									<?php else: ?>
										<?= $video_block ?>
									<?php endif; ?>
								</div>
							<?php } ?>
						</div>

						<?php
						// Preview gallery zoom icon output
						if ($params['lightbox']) {
							echo '<div class="product-gallery-fancy"></div>';
						}

						// Preview gallery labels output
						if ($params['labels']) { ?>
							<div class="product-gallery-labels">
								<?= thegem_woocommerce_single_product_gallery_labels($params['label_sale'], $params['label_new'], $params['label_out_stock']) ?>
							</div>
						<?php }
						?>
					</div>

					<!--Thumbnail gallery output-->
					<?php if (!$isSingleImg && ($params['type'] == 'horizontal' || $params['type'] == 'vertical')): ?>
						<div class="product-gallery-skeleton-thumbs product-gallery-skeleton-thumbs-<?= $params['type'] ?>"></div>
						<div class="product-gallery-thumbs-wrap <?= !empty($params['elements_color']) ? 'init-color' : null; ?>">
							<div class="product-gallery-thumbs owl-carousel">
								<?php
								// Thumbnail gallery images output
								foreach ($attachments_ids as $attachments_id) {
									if (thegem_get_option('woocommerce_activate_images_sizes')) {
										$thumb_image_url = thegem_generate_thumbnail_src($attachments_id, 'thegem-product-thumbnail');
										$thumb_image_url_2x = thegem_generate_thumbnail_src($attachments_id, 'thegem-product-thumbnail-2x');
										$thumb_vertical_image_url = thegem_generate_thumbnail_src($attachments_id, 'thegem-product-thumbnail-vertical');
										$thumb_vertical_image_url_2x = thegem_generate_thumbnail_src($attachments_id, 'thegem-product-thumbnail-vertical-2x');
									} else {
										$thumb_image_url = wp_get_attachment_image_src($attachments_id, apply_filters('single_product_small_thumbnail_size', 'woocommerce_gallery_thumbnail'));
										$thumb_image_url_2x = wp_get_attachment_image_src($attachments_id, apply_filters('single_product_small_thumbnail_size', 'woocommerce_gallery_thumbnail'));
										$thumb_vertical_image_url = wp_get_attachment_image_src($attachments_id, apply_filters('single_product_large_thumbnail_size', 'woocommerce_gallery_thumbnail'));
										$thumb_vertical_image_url_2x = wp_get_attachment_image_src($attachments_id, apply_filters('single_product_large_thumbnail_size', 'woocommerce_gallery_thumbnail'));
									}
									?>
									<?php if ($thumb_image_url || $thumb_vertical_image_url): ?>
										<div class="product-gallery-thumb-item"
										     data-image-id="<?= esc_attr($attachments_id); ?>">
											<div class="product-gallery-image">
												<img
													<?php if ($params['type'] == 'vertical'): ?>
														src="<?php echo esc_url($thumb_vertical_image_url[0]); ?>"
														<?php if ($params['retina_ready']): ?>
															srcset="<?php echo esc_url($thumb_vertical_image_url_2x[0]); ?> 2x"
														<?php endif; ?>
														data-ww="<?php echo esc_url($thumb_vertical_image_url[0]); ?>"
													<?php else: ?>
														src="<?php echo esc_url($thumb_image_url[0]); ?>"
														<?php if ($params['retina_ready']): ?>
															srcset="<?php echo esc_url($thumb_image_url_2x[0]); ?> 2x"
														<?php endif; ?>
													<?php endif; ?>
														alt="<?= thegem_gallery_get_alt_text($attachments_id) ?>"
														class="img-responsive"
												>
											</div>
										</div>
									<?php endif;
								}

								// Thumbnail gallery video output
								if (thegem_get_option('woocommerce_activate_images_sizes')) {
									$thumb_video_url = thegem_generate_thumbnail_src($poster_id, 'thegem-product-thumbnail');
									$thumb_video_url_2x = thegem_generate_thumbnail_src($poster_id, 'thegem-product-thumbnail-2x');
									$thumb_vertical_video_url = thegem_generate_thumbnail_src($poster_id, 'thegem-product-thumbnail-vertical');
									$thumb_vertical_video_url_2x = thegem_generate_thumbnail_src($poster_id, 'thegem-product-thumbnail-vertical-2x');
								} else {
									$thumb_video_url = wp_get_attachment_image_src($poster_id, apply_filters('single_product_small_thumbnail_size', 'woocommerce_gallery_thumbnail'));
									$thumb_video_url_2x = wp_get_attachment_image_src($poster_id, apply_filters('single_product_small_thumbnail_size', 'woocommerce_gallery_thumbnail'));
									$thumb_vertical_video_url = wp_get_attachment_image_src($poster_id, apply_filters('single_product_large_thumbnail_size', 'woocommerce_single'));
									$thumb_vertical_video_url_2x = wp_get_attachment_image_src($poster_id, apply_filters('single_product_large_thumbnail_size', 'woocommerce_single'));
								}

								if (isset($video_block) && !empty($params['product_video'])) { ?>
									<div class="product-gallery-thumb-item">
										<div class="product-gallery-image">
											<?php if ($poster): ?>
												<img
													<?php if ($params['type'] == 'vertical'): ?>
														src="<?php echo esc_url($thumb_vertical_video_url[0]); ?>"
														<?php if ($params['retina_ready']): ?>
															srcset="<?php echo esc_url($thumb_vertical_video_url_2x[0]); ?> 2x"
														<?php endif; ?>
														data-ww="<?php echo esc_url($thumb_vertical_video_url[0]); ?>"
													<?php else: ?>
														src="<?php echo esc_url($thumb_video_url[0]); ?>"
														<?php if ($params['retina_ready']): ?>
															srcset="<?php echo esc_url($thumb_video_url_2x[0]); ?> 2x"
														<?php endif; ?>
													<?php endif; ?>
														alt="<?= thegem_gallery_get_alt_text($poster_id) ?>"
														class="img-responsive"
												>
											<?php else: ?>
												<img src="<?= get_stylesheet_directory_uri() ?>/images/dummy/dummy.png"
												     alt="dummy"
												     class="img-responsive">
											<?php endif; ?>
											<i class="icon-play <?= $video_type ?>" style="color: <?= $poster ? '#ffffff' : '#dfe5e8' ?>"></i>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php

		//Custom Styles
		$customize = '.thegem-te-product-gallery.'.$uniqid;

		if (!empty($params['image_ratio'])) {
			$custom_css .= $customize . '.image-aspect-ratio .product-gallery-slider-item .image-inner {aspect-ratio: ' . $params['image_ratio'] . '!important;}';
		}

		if (!empty($params['grid_image_size']) && $params['grid_image_size'] == 'full' && !empty($params['grid_image_ratio'])){
			$custom_css .= $customize . '.image-aspect-ratio .product-gallery-grid-item .image-inner {aspect-ratio: ' . $params['grid_image_ratio'] . '!important;}';
		}

		if (!empty($params['dots_color'])) {
			$custom_css .= $customize.' .product-gallery .owl-dots .owl-dot span {background-color: ' . $params['dots_color'] . ';}';
		}
		if (!empty($params['dots_color_active'])) {
			$custom_css .= $customize.' .product-gallery .owl-dots .owl-dot.active span {background-color: ' . $params['dots_color_active'] . ';}';
		}

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}

		$return_html = $css_output.$return_html;
		return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), $return_html);
	}

	public function shortcode_settings() {

		return array(
			'name' => __('Product Gallery', 'thegem'),
			'base' => 'thegem_te_product_gallery',
			'icon' => 'thegem-icon-wpb-ui-element-product-gallery',
			'category' => __('Single Product Builder', 'thegem'),
			'description' => __('Product Gallery (Product Builder)', 'thegem'),
			'params' => array_merge(

				/* General - Layout */
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Layout', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Gallery Type', 'thegem'),
						'param_name' => 'type',
						'value' => array_merge(array(
							__('Horizontal Thumbnails', 'thegem') => 'horizontal',
							__('Vertical Thumbnails', 'thegem') => 'vertical',
							__('Dots Navigation', 'thegem') => 'dots',
							__('Carousel Grid', 'thegem') => 'single',
							__('Grid', 'thegem') => 'grid',
							__('WooCommerce Native', 'thegem') => 'native'
						)),
						'std' => 'horizontal',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Columns', 'thegem'),
						'param_name' => 'single_columns',
						'value' => array_merge(array(
							__('1x columns', 'thegem') => '1',
							__('2x columns', 'thegem') => '2',
							__('3x columns', 'thegem') => '3',
						)),
						'std' => '1x',
						'dependency' => array(
							'element' => 'type',
							'value' => array('single')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General',
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Thumbnails Bar Position', 'thegem'),
						'param_name' => 'thumbs_position',
						'value' => array_merge(array(
							__('Left', 'thegem') => 'left',
							__('Right', 'thegem') => 'right',
						)),
						'std' => 'left',
						'dependency' => array(
							'element' => 'type',
							'value' => array('vertical')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						"type" => "textfield",
						'heading' => __('Gaps', 'thegem'),
						'param_name' => 'single_gaps',
						'dependency' => array(
							'element' => 'type',
							'value' => array('single')
						),
						"edit_field_class" => "vc_column vc_col-sm-6",
						'group' => 'General'
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Columns', 'thegem'),
						'param_name' => 'grid_columns',
						'value' => array_merge(array(
							__('1x columns', 'thegem') => '1x',
							__('2x columns', 'thegem') => '2x',
							__('3x columns', 'thegem') => '3x',
							__('4x columns', 'thegem') => '4x',
							__('5x columns', 'thegem') => '5x',
							__('6x columns', 'thegem') => '6x',
						)),
						'std' => '1x',
						'dependency' => array(
							'element' => 'type',
							'value' => array('grid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => 'General',
					),
					array(
						"type" => "textfield",
						'heading' => __('Gaps', 'thegem'),
						'param_name' => 'grid_gaps',
						'std' => '42',
						'dependency' => array(
							'element' => 'type',
							'value' => array('grid')
						),
						"edit_field_class" => "vc_column vc_col-sm-4",
						'group' => 'General'
					),
					array(
						"type" => "textfield",
						'heading' => __('Max. Number of Images', 'thegem'),
						'param_name' => 'grid_image_count',
						'dependency' => array(
							'element' => 'type',
							'value' => array('grid')
						),
						"edit_field_class" => "vc_column vc_col-sm-4",
						'group' => 'General'
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Image Size', 'thegem'),
						'param_name' => 'grid_image_size',
						'value' => array_merge(array(
							__('As in Grid Layout (TheGem Thumbnails)', 'thegem') => 'default',
							__('Full Size', 'thegem') => 'full',
						)),
						'std' => 'default',
						'dependency' => array(
							'element' => 'type',
							'value' => array('grid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						"type" => "textfield",
						'heading' => __('Image Ratio', 'thegem'),
						'param_name' => 'grid_image_ratio',
						'std' => '',
						'dependency' => array(
							'element' => 'grid_image_size',
							'value' => array('full')
						),
						"edit_field_class" => "vc_column vc_col-sm-12",
						'description' => __('Specify a decimal value, i.e. instead of 3:4, specify 0.75. Use a dot as a decimal separator. Leave blank to show the original image ratio.', 'thegem'),
						'group' => 'General'
					),
					array(
						"type" => "textfield",
						'heading' => __('Image Ratio', 'thegem'),
						'param_name' => 'image_ratio',
						'std' => '',
						'dependency' => array(
							'element' => 'type',
							'value' => array('horizontal', 'vertical', 'dots', 'single')
						),
						"edit_field_class" => "vc_column vc_col-sm-12",
						'description' => __('Specify a decimal value, i.e. instead of 3:4, specify 0.75. Use a dot as a decimal separator. Leave blank to show the original image ratio.', 'thegem'),
						'group' => 'General'
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Show Main Image', 'thegem'),
						'param_name' => 'show_image',
						'value' => array_merge(array(
                            __('Click on Thumbnail', 'thegem') => 'click',
                            __('Hover on Thumbnail', 'thegem') => 'hover'
						)),
						'std' => 'hover',
	                    'dependency' => array(
                            'element' => 'type',
                            'value' => array('horizontal', 'vertical')
                        ),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Product Main Image', 'thegem'),
						'param_name' => 'product_image',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'type',
							'value' => array('horizontal', 'vertical', 'dots', 'single', 'grid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show Thumbnails on Mobiles', 'thegem'),
						'param_name' => 'thumb_on_mobile',
						'value' => array(__('Yes', 'thegem') => '1'),
						'dependency' => array(
							'element' => 'type',
							'value' => array('horizontal', 'vertical')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Product Video', 'thegem'),
						'param_name' => 'product_video',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'type',
							'value' => array('horizontal', 'vertical', 'dots', 'single', 'grid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Video Autoplay', 'thegem'),
						'param_name' => 'video_autoplay',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '0',
						'dependency' => array(
							'element' => 'type',
							'value' => array('horizontal', 'vertical', 'dots', 'single', 'grid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Zoom Magnifier', 'thegem'),
						'param_name' => 'zoom',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'type',
							'value' => array('horizontal', 'vertical', 'dots', 'single', 'grid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Lightbox Gallery', 'thegem'),
						'param_name' => 'lightbox',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'type',
							'value' => array('horizontal', 'vertical', 'dots', 'single', 'grid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Gallery Auto Height', 'thegem'),
						'param_name' => 'auto_height',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'type',
							'value' => array('horizontal', 'vertical', 'dots', 'single', 'grid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show Labels', 'thegem'),
						'param_name' => 'labels',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'type',
							'value' => array('horizontal', 'vertical', 'dots', 'single', 'grid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
                    array(
						'type' => 'checkbox',
						'heading' => __('"Sale" Label', 'thegem'),
						'param_name' => 'label_sale',
						'value' => array(__('Yes', 'thegem') => '1'),
	                    'std' => '1',
	                    'dependency' => array(
		                    'element' => 'labels',
		                    'value' => '1'
	                    ),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => 'General',
					),
					array(
						'type' => 'checkbox',
						'heading' => __('"New" Label', 'thegem'),
						'param_name' => 'label_new',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'labels',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => 'General',
					),
					array(
						'type' => 'checkbox',
						'heading' => __('"Out of stock" Label', 'thegem'),
						'param_name' => 'label_out_stock',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'labels',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => 'General',
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Retina-ready thumbnails', 'thegem'),
						'param_name' => 'retina_ready',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '0',
						'dependency' => array(
							'element' => 'type',
							'value' => array('horizontal', 'vertical', 'grid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
                    array(
						'type' => 'checkbox',
						'heading' => __('Skeleton Preloader', 'thegem'),
						'param_name' => 'skeleton_loader',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '0',
	                    'dependency' => array(
		                    'element' => 'type',
		                    'value' => array('horizontal', 'vertical', 'dots', 'single', 'grid')
	                    ),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Arrows and Icons Color', 'thegem'),
						'param_name' => 'elements_color',
						'dependency' => array(
							'element' => 'type',
							'value' => array('horizontal', 'vertical', 'dots', 'single', 'grid')
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal Dot Color', 'thegem'),
						'param_name' => 'dots_color',
						'dependency' => array(
							'element' => 'type',
							'value' => array('horizontal', 'vertical', 'dots')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active Dot Color', 'thegem'),
						'param_name' => 'dots_color_active',
						'dependency' => array(
							'element' => 'type',
							'value' => array('horizontal', 'vertical', 'dots')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
				),

				/* Extra Options */
				thegem_set_elements_extra_options(),

				/* Flex Options */
				thegem_set_elements_design_options('single-product')
			),
		);
	}
}

$templates_elements['thegem_te_product_gallery'] = new TheGem_Template_Element_Product_Gallery();
$templates_elements['thegem_te_product_gallery']->init();
