<?php

function thegem_slideshow_block($params = array()) {
	$slider_editor_class = '';
	$slider_editor_block = '';
	if(class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->preview->is_preview_mode()) {
		$slider_editor_class = ' gem-slideshow-editor';
		$slider_editor_block = '<div class="edit-template-overlay"><div class="buttons"><a href="%s" target="_blank">'.esc_html__('Edit Slider', 'thegem').'</a></div></div>';
	}
	$slider_start = '<div class="gem-slideshow'.$slider_editor_class.'">';
	if(!empty($params['preloader'])) {
		$slider_start = '<div class="preloader slideshow-preloader"><div class="preloader-spin"></div></div><div class="gem-slideshow gem-slideshow-with-preloader gem-slideshow-rs'.$slider_editor_class.'">';
	}
	if($params['slideshow_type'] == 'LayerSlider') {
		if($params['lslider']) {
			echo '<div class="preloader slideshow-preloader"><div class="preloader-spin"></div></div><div class="gem-slideshow gem-slideshow-with-preloader'.$slider_editor_class.'">';
			echo do_shortcode('[layerslider id="'.$params['lslider'].'"]');
			echo sprintf($slider_editor_block, admin_url('admin.php?page=layerslider&action=edit&id='.(int)$params['lslider']));
			echo '</div>';
		}
	} elseif($params['slideshow_type'] == 'revslider' && class_exists('RevSliderSlider')) {
		if($params['slider']) {
			echo $slider_start;
			echo do_shortcode('[rev_slider alias="'.$params['slider'].'"]');
			$slider = new RevSliderSlider();
			$slider->init_by_alias($params['slider'], false);
			$slides = $slider->get_slides();
			$slide_id = '0';
			if(!empty($slides)){
				foreach($slides as $slide){
					$slide_id = $slide->get_id();
					break;
				}
			}
			echo sprintf($slider_editor_block, admin_url('admin.php?page=revslider&view=slide&id='.$slide_id));
			echo '</div>';
			if(!empty($params['preloader'])) {
?>
<script type="text/javascript">
var thegemSlideshowPreloader = document.querySelector('.slideshow-preloader');
var thegemSlideshow = document.querySelector('.gem-slideshow');
if(thegemSlideshow.hasChildNodes()) {
	thegemSlideshow.parentNode.insertBefore(thegemSlideshow, thegemSlideshowPreloader);
	thegemSlideshowPreloader.style.height = thegemSlideshow.clientHeight+'px';
	thegemSlideshow.parentNode.insertBefore(thegemSlideshowPreloader,thegemSlideshow);
} else {
	thegemSlideshowPreloader.remove();
	thegemSlideshow.remove();
}
</script>
<?php
			}
		}
	} elseif($params['slideshow_type'] == 'NivoSlider') {
		echo '<div class="preloader slideshow-preloader"><div class="preloader-spin"></div></div><div class="gem-slideshow gem-slideshow-with-preloader">';
		thegem_nivoslider($params);
		echo '</div>';
	}
}

function thegem_revslider_preloader_fix() {
?>
(function() {
	jQuery(document).ready(function() {
		jQuery('.gem-slideshow-with-preloader.gem-slideshow-rs').each(function() {
			var slideshow = jQuery(this);
			slideshow.trigger('thegem-preloader-loaded');
		});
	});
	var revapi = jQuery(document).ready(function() {});
	revapi.one('revolution.slide.onloaded', function() {
		jQuery('.gem-slideshow').prev('.slideshow-preloader').remove();
	});
})();
<?php
}
add_action('revslider_fe_javascript_output', 'thegem_revslider_preloader_fix', 101);

function portolios_cmp($term1, $term2) {
	$order1 = get_option('portfoliosets_' . $term1->term_id . '_order', 0);
	$order2 = get_option('portfoliosets_' . $term2->term_id . '_order', 0);
	if($order1 == $order2)
		return 0;
	return $order1 > $order2;
}

function thegem_nivoslider($params = array()) {
	$params = array_merge(array('slideshow' => ''), $params);
	$args = array(
		'post_type' => 'thegem_slide',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['slideshow']) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'thegem_slideshows',
				'field' => 'slug',
				'terms' => explode(',', $params['slideshow'])
			)
		);
	}
	$slides = new WP_Query($args);

	if($slides->have_posts()) {

		wp_enqueue_style('nivo-slider');
		wp_enqueue_script('thegem-nivoslider-init-script');

		echo '<div class="preloader"><div class="preloader-spin"></div></div>';
		echo '<div class="gem-nivoslider">';
		while($slides->have_posts()) {
			$slides->the_post();
			if(has_post_thumbnail()) {
				$item_data = thegem_get_sanitize_slide_data(get_the_ID());
?>
	<?php if($item_data['link']) : ?>
		<a href="<?php echo esc_url($item_data['link']); ?>" target="<?php echo esc_attr($item_data['link_target']); ?>" class="gem-nivoslider-slide">
	<?php else : ?>
		<div class="gem-nivoslider-slide">
	<?php endif; ?>
	<?php thegem_post_thumbnail('full', false, ''); ?>
	<?php if($item_data['text_position']) : ?>
		<div class="gem-nivoslider-caption" style="display: none;">
			<div class="caption-<?php echo esc_attr($item_data['text_position']); ?>">
				<div class="gem-nivoslider-title"><?php the_title(); ?></div>
				<div class="clearboth"></div>
				<div class="gem-nivoslider-description"><?php the_excerpt(); ?></div>
			</div>
		</div>
	<?php endif; ?>
	<?php if($item_data['link']) : ?>
		</a>
	<?php else : ?>
		</div>
	<?php endif; ?>
<?php
			}
		}
		echo '</div>';
	}
	wp_reset_postdata();
}

function thegem_atts_product_category_grid($out, $pairs, $atts, $shortcode) {
	if (isset($atts['thegem_grid_params'])) {
		$out['thegem_grid_params'] = unserialize(htmlspecialchars_decode($atts['thegem_grid_params']));
	}
	return $out;
}
add_filter('shortcode_atts_product_category', 'thegem_atts_product_category_grid', 10, 4);

function thegem_query_product_category_grid($query_args, $atts, $loop_name) {
	if (($loop_name == 'product_cat' || $loop_name == 'product_category') && isset($atts['thegem_grid_params'])) {
		$query_args['orderby'] = $atts['thegem_grid_params']['orderby'];
		$query_args['order'] = $atts['thegem_grid_params']['order'];

		if ($atts['thegem_grid_params']['pagination'] == 'more' || $atts['thegem_grid_params']['pagination'] == 'scroll') {
			$query_args['paged'] = $atts['thegem_grid_params']['grid_page'];
			$query_args['no_found_rows'] = false;
		} else {
			$query_args['posts_per_page'] = -1;
		}
	}
	return $query_args;
}
add_filter('woocommerce_shortcode_products_query', 'thegem_query_product_category_grid', 10, 3);

function thegem_product_category_grid_before_loop($atts) {
	if (isset($GLOBALS['thegem_grid_params'])) {
		unset($GLOBALS['thegem_grid_params']);
	}
	if (!isset($atts['thegem_grid_params'])) {
		return;
	}
	$GLOBALS['thegem_grid_params'] = $atts['thegem_grid_params'];
}
add_action('woocommerce_shortcode_before_product_cat_loop', 'thegem_product_category_grid_before_loop');
add_action('woocommerce_shortcode_before_product_category_loop', 'thegem_product_category_grid_before_loop');

function thegem_product_category_grid_loop_start($wp_query) {
	if (!isset($GLOBALS['thegem_grid_params'])) {
		return;
	}
	$params = $GLOBALS['thegem_grid_params'];

	$terms = explode(',', $params['categories']);
	foreach($terms as $key => $term) {
		$terms[$key] = get_term_by('slug', $term, 'product_cat');
		if(!$terms[$key]) {
			unset($terms[$key]);
		}
	}

	$thegem_terms_set = array();
	foreach ($terms as $term) {
		$thegem_terms_set[$term->slug] = $term;
	}

	$gap_size = round(intval($params['gaps_size'])/2);

	( isset($params['gem_product_grid_featured_products_hide_label']) && $params['gem_product_grid_featured_products_hide_label'] == 1 ) ? $class_hide_label_new = 'hide_label_new' : $class_hide_label_new = '';
	( isset($params['gem_product_grid_onsale_products_hide_label']) && $params['gem_product_grid_onsale_products_hide_label'] == 1 ) ? $class_hide_label_sale = 'hide_label_onsale' : $class_hide_label_sale = '';

	$next_page = 0;
	if ($wp_query->max_num_pages > $params['grid_page']) {
		$next_page = $params['grid_page'] + 1;
	} else {
		$next_page = 0;
	}
	$GLOBALS['thegem_grid_params']['next_page'] = $next_page;
?>

	<?php if(!$params['is_ajax']) : ?>
		<?php echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader"><div class="preloader-spin"></div></div>'); ?>
		<div class="portfolio-preloader-wrapper">
		<?php if($params['title']): ?>
			<h3 class="title portfolio-title"><?php echo $params['title']; ?></h3>
		<?php endif; ?>

		<?php

			$portfolio_classes = array(
				'portfolio',
				'products-grid',
				'products',
				'no-padding',
				'portfolio-pagination-' . $params['pagination'],
				'portfolio-style-' . $params['style'],
				'background-style-' . $params['background_style'],
				'title-style-' . $params['title_style'],
				'hover-' . esc_attr($params['hover']),
				'item-animation-' . $params['loading_animation'],
				'title-on-' . $params['display_titles'],
				$class_hide_label_new,
				$class_hide_label_sale,
			);

			if ($params['layout_columns'] == '1x') {
				$portfolio_classes[] = 'caption-position-' . $params['caption_position'];
			}

			if ($gap_size == 0) {
				$portfolio_classes[] = 'no-gaps';
			}

			if ($params['layout'] == '100%') {
				$portfolio_classes[] = 'fullwidth-columns-' . intval($params['fullwidth_columns']);
			}

			if ($params['display_titles'] == 'page' && $params['hover'] == 'gradient') {
				$portfolio_classes[] = 'hover-gradient-title';
			}

			if ($params['display_titles'] == 'page' && $params['hover'] == 'circular') {
				$portfolio_classes[] = 'hover-circular-title';
			}

			if ($params['display_titles'] == 'hover') {
				$portfolio_classes[] = 'hover-title';
			}

			if ($params['style'] == 'masonry' && $params['layout'] != '1x') {
				$portfolio_classes[] = 'portfolio-items-masonry';
			}

			if ($params['layout_columns'] != -1) {
				$portfolio_classes[] = 'columns-' . intval($params['layout_columns']);
			}

			if ( $params['item_separator'] && ( $params['display_titles'] == 'hover' || ($params['display_titles'] == 'page' && ( $params['hover'] == 'gradient' || $params['hover'] == 'circular' ) ) ) ) {
				$portfolio_classes[] = 'item-separator';
			}

			if ($params['disable_socials']) {
				$portfolio_classes[] = 'portfolio-disable-socials';
			}

			$portfolio_classes = apply_filters('portfolio_classes_filter', $portfolio_classes);

			$row_styles = '';
			if ($params['layout'] == '100%') {
				$row_styles .= 'margin: 0;';
				if ($gap_size) {
					if (thegem_get_option('page_padding_left')) {
						$row_styles .= 'margin-left: -' . $gap_size . 'px;';
					} else {
						$row_styles .= 'padding-left: ' . $gap_size . 'px;';
					}

					if (thegem_get_option('page_padding_right')) {
						$row_styles .= 'margin-right: -' . $gap_size . 'px;';
					} else {
						$row_styles .= 'padding-right: ' . $gap_size . 'px;';
					}
				}
			} else {
				if ($gap_size) {
					$row_styles .= 'margin: -' . $gap_size . 'px;';
				} else {
					$row_styles .= 'margin: 0;';
				}
			}
		?>

			<div data-per-page="<?php echo $params['items_per_page']; ?>" data-portfolio-uid="<?php echo esc_attr($params['portfolio_uid']); ?>" class="<?php echo implode(' ', $portfolio_classes); ?>" data-hover="<?php echo $params['hover']; ?>" <?php if($params['pagination'] == 'more' || $params['pagination'] == 'scroll'): ?>data-next-page="<?php echo esc_attr($next_page); ?>"<?php endif; ?>>
				<?php if(($params['with_filter'] && count($terms) > 0) || $params['sorting']): ?>
					<div class="portfilio-top-panel<?php if($params['layout'] == '100%'): ?> fullwidth-block<?php endif; ?>" <?php if ($gap_size && $params['layout'] == '100%'): ?>style="padding-left: <?php echo 2*$gap_size; ?>px; padding-right: <?php echo 2*$gap_size; ?>px;"<?php endif; ?>><div class="portfilio-top-panel-row">
						<div class="portfilio-top-panel-left">
						<?php if($params['with_filter'] && count($terms) > 0): ?>


							<div <?php if(!$params['sorting']): ?> style="text-align: center;"<?php endif; ?>  class="portfolio-filters">
								<a href="#" data-filter="*" class="active all title-h6"><?php echo thegem_build_icon('thegem-icons', 'portfolio-show-all'); ?><span class="light"><?php echo apply_filters('portfolio_show_all_filter', __('All', 'thegem')); ?></span></a>
								<?php foreach($terms as $term) : ?>
									<a href="#" data-filter=".<?php echo $term->slug; ?>" class="title-h6"><span class="light"><?php echo $term->name; ?></span></a>
								<?php endforeach; ?>
							</div>
							<div class="portfolio-filters-resp">
								<button class="menu-toggle dl-trigger"><?php _e('Portfolio filters', 'thegem'); ?><span class="menu-line-1"></span><span class="menu-line-2"></span><span class="menu-line-3"></span></button>
								<ul class="dl-menu">
									<li><a href="#" data-filter="*"></span><?php _e('Show All', 'thegem'); ?></a></li>
									<?php foreach($terms as $term) : ?>
										<li><a href="#" data-filter=".<?php echo esc_attr($term->slug); ?>"><?php echo $term->name; ?></a></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
						</div>
						<div class="portfilio-top-panel-right">
						<?php if($params['sorting']): ?>
							<div class="portfolio-sorting title-h6">
								<div class="orderby light">
									<label for="" data-value="date"><?php _e('Date', 'thegem') ?></label>
									<a href="javascript:void(0);" class="sorting-switcher" data-current="date"></a>
									<label for="" data-value="name"><?php _e('Name', 'thegem') ?></label>
								</div>
								<div class="portfolio-sorting-sep"></div>
								<div class="order light">
									<label for="" data-value="DESC"><?php _e('Desc', 'thegem') ?></label>
									<a href="javascript:void(0);" class="sorting-switcher" data-current="DESC"></a>
									<label for="" data-value="ASC"><?php _e('Asc', 'thegem') ?></label>
								</div>
							</div>

						<?php endif; ?>
						</div>
					</div></div>
				<?php endif; ?>
				<div class="<?php if($params['layout'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
				<div class="row" style="<?php echo $row_styles; ?>">
				<div class="portfolio-set clearfix" data-max-row-height="<?php echo floatval($params['metro_max_row_height']); ?>">
	<?php else: ?>
		<div data-page="<?php echo $params['grid_page']; ?>" data-next-page="<?php echo $next_page; ?>">
	<?php endif; ?>

<?php
}
add_action('loop_start', 'thegem_product_category_grid_loop_start');
add_action('thegem_products_loop_start', 'thegem_product_category_grid_loop_start');

function thegem_product_category_grid_after_loop($atts) {
	if (!isset($atts['thegem_grid_params']) || !isset($GLOBALS['thegem_grid_params'])) {
		return;
	}
	$params = $GLOBALS['thegem_grid_params'];
	$next_page = $params['next_page'];
	unset($GLOBALS['thegem_grid_params']);
?>
	<?php if(!$params['is_ajax']) : ?>
				</div><!-- .portflio-set -->
				<?php if ($params['layout'] != '1x'): ?>
					<div class="portfolio-item-size-container">
						<?php $product_grid_item_size = true; ?>
						<?php include(locate_template(array('woocommerce/content-product-grid-item.php'))); ?>
					</div>
				<?php endif; ?>
				</div><!-- .row-->
				<?php if($params['pagination'] == 'normal'): ?>
					<div class="portfolio-navigator gem-pagination">
					</div>
				<?php endif; ?>
				<?php if($params['pagination'] == 'more' && $next_page > 0): ?>
					<div class="portfolio-load-more">
						<div class="inner">
							<?php thegem_button(array_merge($params['button'], array('tag' => 'button')), 1); ?>
						</div>
					</div>
				<?php endif; ?>
				<?php if($params['pagination'] == 'scroll' && $next_page > 0): ?>
					<div class="portfolio-scroll-pagination"></div>
				<?php endif; ?>
			</div><!-- .full-width -->
		</div><!-- .portfolio-->
	</div><!-- .portfolio-preloader-wrapper-->
	<?php else: ?>
	</div>
	<?php endif; ?>
<?php
}
add_action('woocommerce_shortcode_after_product_cat_loop', 'thegem_product_category_grid_after_loop');
add_action('woocommerce_shortcode_after_product_category_loop', 'thegem_product_category_grid_after_loop');


if(!function_exists('thegem_video_background')) {
function thegem_video_background($video_type, $video, $aspect_ratio = '16:9', $headerUp = false, $color = '', $opacity = '', $poster='', $play_on_mobile = '', $background_fallback = '', $background_style = '', $background_position_horizontal = 'center', $background_position_vertical = 'top') {
	$output = $link = $uniqid = $video_class = $mobile = '';
	$uniqid = uniqid('thegem-video-frame-').rand(1,9999);
	$video_type = thegem_check_array_value(array('', 'youtube', 'vimeo', 'self'), $video_type, '');
	if($video_type && $video) {
		$video_block = $overlay_css = $fallback_css = $video_css = $video_data = '';
		if(!function_exists('isMobile')) {
			function isMobile() {
				return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
			}
		}
		if($video_type == 'youtube' || $video_type == 'vimeo') {
			if($play_on_mobile) {
				wp_enqueue_script('thegem-video');
				$video_class = ' background-video-container';
				$video_block = '<div class="background-video-embed"></div>';
			} elseif($background_fallback && !$play_on_mobile) {
				$fallback_css .= 'style="';
					$fallback_css .= 'background-image: url('.esc_url($background_fallback).');';
					if(!empty($background_style)) {
						$fallback_css .= 'background-size: '.esc_attr($background_style).';';
					} else {
						$fallback_css .= 'background-size: cover;';
					}
					$fallback_css .= 'background-position: '.$background_position_horizontal.' '.$background_position_vertical.';';
				$fallback_css .= '"';
				$output .= '<script type="text/javascript">
								(function($) {
									$("head").append("<style>@media (max-width: 767px) {#'.esc_attr($uniqid).' {display: none;}}</style>");
								})(jQuery);
							</script>';
			}
			if($video_type == 'youtube') {
				if($play_on_mobile && !\Elementor\Plugin::$instance->preview->is_preview_mode()) {
					$video_data = ' data-settings=\'{"url": "https://www.youtube.com/watch?v='.$video.'", "play_on_mobile": true, "background_play_once": false }\'';
				} else {
					$link = '//www.youtube.com/embed/'.$video.'?playlist='.$video.'&autoplay=1&mute=1&controls=0&playsinline=1&enablejsapi=1&loop=1&fs=0&showinfo=0&autohide=1&iv_load_policy=3&rel=0&disablekb=1&wmode=transparent';
					$video_block = '<iframe id="'.$uniqid.'" class="gem-video-background-iframe" src="'.esc_url($link).'" frameborder="0" muted="muted"></iframe>';
				}
			}
			if($video_type == 'vimeo') {
				if($play_on_mobile && !\Elementor\Plugin::$instance->preview->is_preview_mode()) {
					$video_data = ' data-settings=\'{"url": "https://vimeo.com/'.$video.'", "play_on_mobile": true, "background_play_once": false }\'';
				} elseif(empty($play_on_mobile) && !\Elementor\Plugin::$instance->preview->is_preview_mode() && !empty(isMobile())) {
					$link = '//player.vimeo.com/video/'.$video.'?autoplay=0&muted=1&controls=0&loop=1&title=0&badge=0&byline=0&autopause=0';
					$video_block = '<iframe id="'.$uniqid.'" class="gem-video-background-iframe" src="'.esc_url($link).'" frameborder="0" muted="muted"></iframe>';
				} else {
					$link = '//player.vimeo.com/video/'.$video.'?autoplay=1&muted=1&controls=0&loop=1&title=0&badge=0&byline=0&autopause=0';
					$video_block = '<iframe id="'.$uniqid.'" class="gem-video-background-iframe" src="'.esc_url($link).'" frameborder="0" muted="muted"></iframe>';
				}
			}
		} else {
			if($play_on_mobile && !\Elementor\Plugin::$instance->preview->is_preview_mode()) {
				wp_enqueue_script('thegem-video');
				$video_class = ' background-video-container';
				$video_data = ' data-settings=\'{"url": "'.$video.'", "play_on_mobile": true, "background_play_once": false }\'';
				$video_block = '<video id="'.$uniqid.'" class="background-video-hosted html5-video" autoplay muted playsinline loop'.($poster ? ' poster="'.esc_url($poster).'"' : '').'></video>';
			} elseif($background_fallback && !$play_on_mobile) {
				$fallback_css .= 'style="';
					$fallback_css .= 'background-image: url('.esc_url($background_fallback).');';
					if(!empty($background_style)) {
						$fallback_css .= 'background-size: '.esc_attr($background_style).';';
					} else {
						$fallback_css .= 'background-size: cover;';
					}
					$fallback_css .= 'background-position: '.$background_position_horizontal.' '.$background_position_vertical.';';
				$fallback_css .= '"';
				$output .= '<script type="text/javascript">
								(function($) {
									$("head").append("<style>@media (max-width: 767px) {#'.esc_attr($uniqid).' {display: none;}}</style>");
								})(jQuery);
							</script>';
				$video_block = '<video id="'.$uniqid.'" autoplay="autoplay" loop="loop" src="'.$video.'" muted="muted"'.($poster ? ' poster="'.esc_url($poster).'"' : '').'></video>';
			} else {
				$video_block = '<video id="'.$uniqid.'" autoplay="autoplay" loop="loop" src="'.$video.'" muted="muted"'.($poster ? ' poster="'.esc_url($poster).'"' : '').'></video>';
			}
		}
		
		if($color) {
			$overlay_css .= 'background-color: '.$color.'; opacity: '.floatval($opacity).';';
		}
		
		$output .= '<div class="gem-video-background" data-aspect-ratio="'.esc_attr($aspect_ratio).'"'.($headerUp ? ' data-headerup="1"' : '').''.$fallback_css.'>';
			$output .= '<div class="gem-video-background-inner'.$video_class.'"'.$video_data.'>'.$video_block.'</div>';
			$output .= '<div class="gem-video-background-overlay" style="'.$overlay_css.'"></div>';
		$output .= '</div>';
	}

	if (class_exists('TheGemGdpr')) {
		$type = null;
		switch ($video_type) {
			case 'youtube':
				$type = TheGemGdpr::CONSENT_NAME_YOUTUBE;
				break;
			case 'vimeo':
				$type = TheGemGdpr::CONSENT_NAME_VIMEO;
				break;
		}

		if (!empty($type)) {
			return TheGemGdpr::getInstance()->replace_disallowed_content($output, $type);
		}
	}


	return $output;
}
}


// Print Product Slider
function thegem_atts_product_category_slider($out, $pairs, $atts, $shortcode) {
	if (isset($atts['thegem_slider_params'])) {
		$out['thegem_slider_params'] = unserialize(htmlspecialchars_decode($atts['thegem_slider_params']));
	}
	return $out;
}
add_filter('shortcode_atts_product_category', 'thegem_atts_product_category_slider', 10, 4);

function thegem_product_category_slider_before_loop($atts) {
	if (isset($GLOBALS['thegem_slider_params'])) {
		unset($GLOBALS['thegem_slider_params']);
	}
	if (!isset($atts['thegem_slider_params'])) {
		return;
	}
	$GLOBALS['thegem_slider_params'] = $atts['thegem_slider_params'];
}
add_action('woocommerce_shortcode_before_product_cat_loop', 'thegem_product_category_slider_before_loop');
add_action('woocommerce_shortcode_before_product_category_loop', 'thegem_product_category_slider_before_loop');

function thegem_product_category_slider_loop_start($wp_query) {
	if (!isset($GLOBALS['thegem_slider_params'])) {
		return;
	}
	$params = $GLOBALS['thegem_slider_params'];

	$gap_size = round(intval($params['gaps_size'])/2);

	$layout_columns_count = -1;
	if ($params['layout'] == '3x')
		$layout_columns_count = 3;
	if ($params['layout'] == '2x')
		$layout_columns_count = 2;

	$layout_fullwidth = false;
	if ($params['layout'] == '100%')
		$layout_fullwidth = true;

	$classes = array('portfolio', 'portfolio-slider', 'products-slider', 'products', 'clearfix', 'no-padding', 'col-lg-12', 'col-md-12', 'col-sm-12', 'hover-'.$params['hover']);
	if($layout_fullwidth)
		$classes[] = 'full';
	if( ($params['display_titles'] == 'hover' && $params['layout'] != '1x') || $params['hover'] == 'gradient' || $params['hover'] == 'circular' )
		$classes[] = 'hover-title';
	if ($params['display_titles'] == 'page' && $params['hover'] == 'gradient')
		$classes[] = 'hover-gradient-title';
	if ($params['display_titles'] == 'page' && $params['hover'] == 'circular')
		$classes[] = 'hover-circular-title';
	if($layout_columns_count != -1)
		$classes[] = 'columns-'.$layout_columns_count;
	if($params['no_gaps'])
		$classes[] = 'without-padding';
	if($params['layout'] == '100%')
		$classes[] = 'fullwidth-columns-'.$params['fullwidth_columns'];

	$classes[] = 'portfolio-items-' . $params['style'];

	if ($params['effects_enabled']) {
		$classes[] = 'lazy-loading';
		thegem_lazy_loading_enqueue();
	}

	if ($params['disable_socials'])
		$classes[] = 'disable-socials';
	if ($params['slider_arrow'])
		$classes[] = $params['slider_arrow'];
	if ($params['background_style'])
		$classes[] = 'background-style-'.$params['background_style'];
	if ($params['title_style'])
		$classes[] = 'title-style-'.$params['title_style'];
	if ( $params['item_separator'] && ( $params['display_titles'] == 'hover' || ($params['display_titles'] == 'page' && ( $params['hover'] == 'gradient' || $params['hover'] == 'circular' ) ) ) ) {
		$classes[] = 'item-separator';
	}
	if ($params['disable_socials']) {
		$classes[] = 'portfolio-disable-socials';
	}

	$classes[] = 'title-on-' . $params['display_titles'];
	$classes[] = 'gem-slider-animation-' . $params['animation'];

	?>

	<div class="preloader"><div class="preloader-spin"></div></div>
	<div <?php post_class($classes); ?> <?php if($params['effects_enabled']): ?>data-ll-item-delay="0"<?php endif;?> data-hover="<?php echo esc_attr($params['hover']); ?>">
		<div class="navigation <?php if($layout_fullwidth): ?>fullwidth-block<?php endif; ?>">
			<?php if($params['title']): ?>
				<h3 class="title <?php if($params['effects_enabled']): ?>lazy-loading-item<?php endif;?>" <?php if($params['effects_enabled']): ?>data-ll-effect="fading"<?php endif;?>><?php echo $params['title']; ?></h3>
			<?php endif; ?>
			<div class="portolio-slider-prev">
				<span>&#xe603;</span>
			</div>

			<div class="portolio-slider-next">
				<span>&#xe601;</span>
			</div>

			<div class="portolio-slider-content">
				<div class="portolio-slider-center">
					<div class="<?php if($params['layout'] == '100%'): ?>fullwidth-block<?php endif; ?>">
						<div style="margin: -<?php echo $gap_size; ?>px;">
							<div class="portfolio-set clearfix" <?php if(intval($params['autoscroll'])) { echo 'data-autoscroll="'.intval($params['autoscroll']).'"'; } ?>>
	<?php
}
add_action('loop_start', 'thegem_product_category_slider_loop_start');
add_action('thegem_products_loop_start', 'thegem_product_category_slider_loop_start');

function thegem_product_category_slider_after_loop($atts) {
	if (!isset($atts['thegem_slider_params']) || !isset($GLOBALS['thegem_slider_params'])) {
		return;
	}
	unset($GLOBALS['thegem_slider_params']);

	?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
add_action('woocommerce_shortcode_after_product_cat_loop', 'thegem_product_category_slider_after_loop');
add_action('woocommerce_shortcode_after_product_category_loop', 'thegem_product_category_slider_after_loop');

function thegem_tag_cloud_args($args){
	$args['smallest'] = 12;
	$args['largest'] = 30;
	$args['unit'] = 'px';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'thegem_tag_cloud_args');
add_filter( 'woocommerce_product_tag_cloud_widget_args', 'thegem_tag_cloud_args');

function thegem_yith_frontend_action_list($actions) {
	$actions[] = 'extended_products_grid_load_more';
	return $actions;
}
add_filter('yith_ywraq_frontend_action_list', 'thegem_yith_frontend_action_list');
add_filter('yith_woocompare_actions_to_check_frontend', 'thegem_yith_frontend_action_list');

if (!function_exists('get_thegem_select_blog_categories')) {
	function get_thegem_select_blog_categories() {
		$out = ['0' => __('All', 'thegem')];

		$terms = get_terms( [
			'taxonomy' => 'category',
			'hide_empty' => true,
		] );

		if (empty($terms) || is_wp_error($terms)) {
			return $out;
		}

		foreach ((array)$terms as $term) {
			if (!empty($term->name)) {
				$out[$term->slug] = $term->name;
			}
		}

		return $out;
	}
}

if (!function_exists('get_thegem_select_blog_tags')) {
	function get_thegem_select_blog_tags() {
		$out   = [];
		$terms = get_terms( [
			'taxonomy' => 'post_tag',
			'hide_empty' => true,
		] );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return $out;
		}

		foreach ( (array) $terms as $term ) {
			if ( ! empty( $term->name ) ) {
				$out[ $term->slug ] = $term->name;
			}
		}

		return $out;
	}
}

if (!function_exists('get_thegem_select_blog_posts')) {
	function get_thegem_select_blog_posts() {
		$out   = [];
		$posts = get_posts( [
			'post_type' => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => -1
		] );

		if ( empty( $posts ) || is_wp_error( $posts ) ) {
			return $out;
		}

		foreach ( (array) $posts as $post ) {
			if ( ! empty( $post->post_title ) ) {
				$out[ $post->ID ] = $post->post_title . ' (' . $post->ID . ')';
			}
		}

		return $out;
	}
}

if (!function_exists('get_thegem_select_blog_authors')) {
	function get_thegem_select_blog_authors() {
		$out   = [];
		$authors = get_users( array( 'has_published_posts' => array( 'post' ) ) );

		if ( empty( $authors ) || is_wp_error( $authors ) ) {
			return $out;
		}

		foreach ( (array) $authors as $author ) {
			if ( ! empty( $author->data->display_name ) ) {
				$out[ $author->ID ] = $author->data->display_name;
			}
		}

		return $out;
	}
}
