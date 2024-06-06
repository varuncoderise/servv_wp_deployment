<?php

function thegem_slideshow_block($params = array()) {
	$slider_editor_class = '';
	$slider_editor_block = '';
	if(defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable())) {
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
var thegemSlideshowError = thegemSlideshow.querySelector('.rs_error_message_content');
if(thegemSlideshow.hasChildNodes()) {
	thegemSlideshow.parentNode.insertBefore(thegemSlideshow, thegemSlideshowPreloader);
	thegemSlideshowPreloader.style.height = thegemSlideshow.clientHeight+'px';
	thegemSlideshow.parentNode.insertBefore(thegemSlideshowPreloader,thegemSlideshow);
} else {
	thegemSlideshowPreloader.remove();
	thegemSlideshow.remove();
}
if(thegemSlideshowError) {
	thegemSlideshowPreloader.remove();
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

/* QUICKFINDER BLOCK */
function thegem_quickfinder($params) {
	$params = is_array($params) ? $params : array();
	$params = array_merge(array(
		'quickfinders' => '',
		'style' => 'default',
		'columns' => 4,
		'alignment' => 'center',
		'icon_position' => 'top',
		'title_weight' => 'bold',
		'activate_button' => 0,
		'button_style' => 'flat',
		'button_text_weight' => 'normal',
		'button_corner' => 3,
		'button_text_color' => '',
		'button_background_color' => '',
		'button_border_color' => '',
		'hover_icon_color' => '',
		'hover_box_color' => '',
		'hover_border_color' => '',
		'hover_title_color' => '',
		'hover_description_color' => '',
		'hover_button_background_color' => '',
		'hover_button_text_color' => '',
		'hover_button_border_color' => '',
		'box_style' => 'solid',
		'box_background_color' => '',
		'box_border_color' => '',
		'connector_color' => '',
		'equal_height' => '',
		'effects_enabled' => '',
		'effects_enabled_delay' => '',
			/*interactions*/
		'interactions_enabled' => '',
		'vertical_scroll' => '',
		'vertical_scroll_direction' => 'vertical_scroll_direction_up',
		'vertical_scroll_speed' => '3',
		'vertical_scroll_viewport_bottom' => '0',
		'vertical_scroll_viewport_top' => '100',
		'horizontal_scroll' => '',
		'horizontal_scroll_direction' => 'horizontal_scroll_direction_left',
		'horizontal_scroll_speed' => '3',
		'horizontal_scroll_viewport_bottom' => '0',
		'horizontal_scroll_viewport_top' => '100',
		'mouse_effects' => '',
		'mouse_effects_direction' => 'mouse_effects_direction_opposite',
		'mouse_effects_speed' => '3',
		'disable_effects_desktop' => '',
		'disable_effects_tablet' => '',
		'disable_effects_mobile' => ''
			/*END interactions*/
	), $params);
	$params['style'] = thegem_check_array_value(array('default', 'classic', 'iconed', 'binded', 'binded-iconed', 'tag', 'vertical-1', 'vertical-2', 'vertical-3', 'vertical-4'), $params['style'], 'default');
	$params['columns'] = thegem_check_array_value(array(1,2,3,4,6), $params['columns'], 4);
	$params['alignment'] = thegem_check_array_value(array('center', 'left', 'right'), $params['alignment'], 'center');
	$params['icon_position'] = thegem_check_array_value(array('top', 'bottom', 'top-float', 'center-float'), $params['icon_position'], 'top');
	$params['title_weight'] = thegem_check_array_value(array('bold', 'thin'), $params['title_weight'], 'bold');
	$params['activate_button'] = $params['activate_button'] ? 1 : 0;
	$params['hover_icon_color'] = esc_attr($params['hover_icon_color']);
	$params['hover_box_color'] = esc_attr($params['hover_box_color']);
	$params['hover_border_color'] = esc_attr($params['hover_border_color']);
	$params['hover_title_color'] = esc_attr($params['hover_title_color']);
	$params['hover_description_color'] = esc_attr($params['hover_description_color']);
	$params['hover_button_background_color'] = esc_attr($params['hover_button_background_color']);
	$params['hover_button_text_color'] = esc_attr($params['hover_button_text_color']);
	$params['hover_button_border_color'] = esc_attr($params['hover_button_border_color']);
	$params['box_style'] = thegem_check_array_value(array('solid', 'soft-outlined', 'strong-outlined'), $params['box_style'], 'solid');
	$params['box_background_color'] = esc_attr($params['box_background_color']);
	$params['box_border_color'] = esc_attr($params['box_border_color']);
	$params['connector_color'] = esc_attr($params['connector_color']);
	$params['effects_enabled'] = $params['effects_enabled'] ? 1 : 0;
	$params['effects_enabled_delay'] = $params['effects_enabled_delay'] ? $params['effects_enabled_delay'] : 0;

	if ($params['effects_enabled']) {
		thegem_lazy_loading_enqueue();
	}

	$hover_data = '';
	if($params['hover_icon_color']) {
		$hover_data .= ' data-hover-icon-color="'.$params['hover_icon_color'].'"';
	}
	if($params['hover_box_color']) {
		$hover_data .= ' data-hover-box-color="'.$params['hover_box_color'].'"';
	}
	if($params['hover_border_color']) {
		$hover_data .= ' data-hover-border-color="'.$params['hover_border_color'].'"';
	}
	if($params['hover_title_color']) {
		$hover_data .= ' data-hover-title-color="'.$params['hover_title_color'].'"';
	}
	if($params['hover_description_color']) {
		$hover_data .= ' data-hover-description-color="'.$params['hover_description_color'].'"';
	}
	if($params['hover_button_background_color']) {
		$hover_data .= ' data-hover-button-background-color="'.$params['hover_button_background_color'].'"';
	}
	if($params['hover_button_text_color']) {
		$hover_data .= ' data-hover-button-text-color="'.$params['hover_button_text_color'].'"';
	}
	if($params['hover_button_border_color']) {
		$hover_data .= ' data-hover-button-border-color="'.$params['hover_button_border_color'].'"';
	}
	$binded = '';
	if($params['style'] == 'binded') {
		$params['style'] = 'classic';
		$binded = ' quickfinder-binded';
	}
	if($params['style'] == 'binded-iconed') {
		$params['style'] = 'iconed';
		$binded = ' quickfinder-binded';
	}

	$args = array(
		'post_type' => 'thegem_qf_item',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['quickfinders'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'thegem_quickfinders',
				'field' => 'slug',
				'terms' => explode(',', $params['quickfinders'])
			)
		);
	}
	$quickfinder_items = new WP_Query($args);

	$quickfinder_style = $params['style'];
	$quickfinder_item_rotation = 'odd';

	$connector_color = $params['connector_color'];
	if(($quickfinder_style == 'vertical-1' || $quickfinder_style == 'vertical-2' || $quickfinder_style == 'vertical-3' || $quickfinder_style == 'vertical-4') && !$connector_color) {
		$connector_color = '#b6c6c9';
	}
	$class = $interactions_data = '';
	if(isset($params['interactions_enabled']) && !empty($params['interactions_enabled'])) {
		$class = ' gem-interactions-enabled';
		$interactions_data = interactions_data_attr($params);
	}

	if(!empty($params['equal_height'])) {
		$class .= ' quickfinder-equal-height';
	}

	if($quickfinder_items->have_posts()) {
		wp_enqueue_style('thegem-quickfinders');
		if ($quickfinder_style == 'vertical-1' || $quickfinder_style == 'vertical-2' || $quickfinder_style == 'vertical-3' || $quickfinder_style == 'vertical-4') {
			wp_enqueue_style('thegem-quickfinders-vertical');
		}
		wp_enqueue_script('thegem-quickfinders-effects');
		echo '<div class="quickfinder quickfinder-style-'.$params['style'].$binded.' '.($quickfinder_style == 'vertical-1' || $quickfinder_style == 'vertical-2'  || $quickfinder_style == 'vertical-3' || $quickfinder_style == 'vertical-4' ? 'quickfinder-style-vertical' : 'row inline-row').' quickfinder-icon-position-'.$params['icon_position'].' quickfinder-alignment-'.$params['alignment'].' quickfinder-title-'.$params['title_weight'].''.$class.'"'.$hover_data.''.$interactions_data.'>';
		$i = 0;
		while($quickfinder_items->have_posts()) {
			$i++;
			$quickfinder_items->the_post();
			include(locate_template(array('gem-templates/quickfinders/content-quickfinder-item-'.$params['style'].'.php', 'gem-templates/quickfinders/content-quickfinder-item.php')));
			$quickfinder_item_rotation = $quickfinder_item_rotation == 'odd' ? 'even' : 'odd';
		}
		echo '</div>';
	}
	wp_reset_postdata();
}

function thegem_quickfinder_block($params) {
	echo '<div class="container">';
	thegem_quickfinder($params);
	echo '</div>';
}

// Print Team
function thegem_team($params) {
	$params = array_merge(array('team' => '', 'team_person' => '', 'style' => '', 'columns' => '2', 'centered' => '', 'hover_colors' => array()), $params);
	$args = array(
		'post_type' => 'thegem_team_person',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);

	if($params['style'] == 'style-5' && empty($params['hover_colors']['image_border_color'])) {
		$params['hover_colors']['image_border_color'] = thegem_get_option('styled_elements_background_color');
	}

	if($params['team'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'thegem_teams',
				'field' => 'slug',
				'terms' => explode(',', $params['team'])
			)
		);
	} elseif($params['team_person'] != '') {
		$args['p'] = $params['team_person'];
	}

	$interactions_data = $class = '';
	if(isset($params['interactions_enabled']) && !empty($params['interactions_enabled'])) {
		$interactions_data = interactions_data_attr($params);
		$class = ' gem-interactions-enabled';
	}

	if (!empty($params['equal_height'])) {
		$class = ' gem-team-equal-height';
	}

	$persons = new WP_Query($args);

	if($persons->have_posts()) {
		wp_enqueue_script('thegem-team-hover');
		echo '<div class="gem-team row inline-row'.$class.' gem-team-'.esc_attr($params['style']).'"'.(!empty($params['hover_colors']) ? ' data-hover-colors="'.esc_attr(json_encode($params['hover_colors'])).'"' : '').' '.$interactions_data.'>';
		while($persons->have_posts()) {
			$persons->the_post();
			include(locate_template(array('gem-templates/teams/content-team-person-'.$params['style'].'.php', 'gem-templates/teams/content-team-person.php')));
		}
		echo '</div>';
	}
	wp_reset_postdata();
}

// Print Gallery
function thegem_gallery($params) {
	wp_enqueue_style('thegem-hovers');

	$params = array_merge(array('gallery' => 0, 'hover' => 'default', 'layout' => 'fullwidth', 'no_thumbs' => 0, 'pagination' => 0, 'autoscroll' => 0), $params);

	ob_start();
	$gallery_items = [];

	if ($params['source_type'] == 'product-gallery') {

		$product = thegem_templates_init_product();
		if (!empty($product)) {
			$gallery_items = $product->get_gallery_image_ids();
			if ('variable' === $product->get_type()) {
				foreach ($product->get_available_variations() as $variation) {
					if (has_post_thumbnail($variation['variation_id'])) {
						$thumbnail_id = get_post_thumbnail_id($variation['variation_id']);
						if (!in_array($thumbnail_id, $gallery_items)) {
							$gallery_items[] = $thumbnail_id;
						}
					}
				}
			}
		}

	} else if ($params['source_type'] == 'portfolio-gallery') {

		$portfolio = thegem_templates_init_portfolio();
			if (!empty($portfolio)) {
				$gallery_items = get_post_meta($portfolio->ID, 'thegem_portfolio_item_gallery_images', true);
				$gallery_items = !empty($gallery_items) ? explode(',', $gallery_items) : array();
			}

	} else {

		$galleries = explode(',', $params['gallery']);
		foreach ($galleries as $gallery_index => $gallery) {

			if (metadata_exists('post', $gallery, 'thegem_gallery_images')) {
				$thegem_gallery_images_ids = get_post_meta($gallery, 'thegem_gallery_images', true);
				$galleries[$gallery_index] = get_the_title($gallery);
			} else {
				$attachments_ids = get_posts('post_parent=' . $gallery . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&post_status=publish');
				$thegem_gallery_images_ids = implode(',', $attachments_ids);
			}
			$thegem_gallery_images_ids = array_filter(explode(',', $thegem_gallery_images_ids));

			foreach ($thegem_gallery_images_ids as $item) {
				if (!in_array($item, $gallery_items, true)) {
					$gallery_items[] = $item;
				}
			}
		}

	}

	$gallery_uid = uniqid();

	if (!empty($gallery_items)) {
		wp_enqueue_script('thegem-gallery');
		wp_enqueue_style('thegem-animations'); ?>
		<div class="preloader"><div class="preloader-spin"></div></div>
		<div class="gem-gallery gem-gallery-hover-<?php echo esc_attr($params['hover']); ?><?php echo ($params['no_thumbs'] ? ' no-thumbs' : ''); ?><?php echo ($params['pagination'] ? ' with-pagination' : ''); ?>"<?php echo (intval($params['autoscroll']) ? ' data-autoscroll="'.intval($params['autoscroll']).'"' : ''); ?>>
		<?php foreach($gallery_items as $attachment_id) : ?>
			<?php
				$item = get_post($attachment_id);
				if($item) {
					$thumb_image_url = thegem_generate_thumbnail_src($item->ID, 'thegem-post-thumb-small');
					$preview_image_url = thegem_generate_thumbnail_src($item->ID, 'thegem-gallery-'.esc_attr($params['layout']));
					$full_image_url = wp_get_attachment_image_src($item->ID, 'full');
				}
			?>
			<?php if(!empty($thumb_image_url[0]) && $item) : ?>
				<div class="gem-gallery-item">
					<div class="gem-gallery-item-image">
						<a href="<?php echo $preview_image_url[0]; ?>" data-fancybox-group="gallery-<?php echo esc_attr($gallery_uid); ?>" data-full-image-url="<?php echo esc_attr($full_image_url[0]); ?>">
							<svg width="20" height="10"><path d="M 0,10 Q 9,9 10,0 Q 11,9 20,10" /></svg>
							<img src="<?php echo $thumb_image_url[0]; ?>" alt="<?php echo get_post_meta($attachment_id, '_wp_attachment_image_alt', true); ?>" class="img-responsive">
							<span class="gem-gallery-caption slide-info">
								<?php if($params['title_show'] && $item->post_excerpt) : ?><span class="gem-gallery-item-title "><?php echo apply_filters('the_excerpt', $item->post_excerpt); ?></span><?php endif; ?>
								<?php if($params['description_show'] && $item->post_content) : ?><span class="gem-gallery-item-description"><?php echo apply_filters('the_content', $item->post_content); ?></span><?php endif; ?>
							</span>
						</a>
						<span class="gem-gallery-line"></span>
					</div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
		</div>
	<?php } else {
		wp_reset_postdata();
		if (($params['source_type'] == 'product-gallery' && thegem_get_template_type(get_the_ID()) === 'single-product') ||
			($params['source_type'] == 'portfolio-gallery' && thegem_get_template_type(get_the_ID()) === 'portfolio')) { ?>
			<div class="no-elements-gallery-grid">
				<i class="eicon-gallery-justified" aria-hidden="true"></i>
			</div>
		<?php }
	}

	echo trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	wp_reset_postdata();
}

function thegem_simple_gallery($params) {
	wp_enqueue_style('thegem-hovers');

	$params = array_merge(array('gallery' => 0, 'thumb_size' => 'thegem-gallery-simple', 'autoscroll' => 0, 'responsive' => 0), $params);

	if(metadata_exists('post', $params['gallery'], 'thegem_gallery_images')) {
		$thegem_gallery_images_ids = get_post_meta($params['gallery'], 'thegem_gallery_images', true);
	} else {
		$attachments_ids = get_posts('post_parent=' . $params['gallery'] . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids');
		$thegem_gallery_images_ids = implode(',', $attachments_ids);
	}
	$attachments_ids = array_filter(explode(',', $thegem_gallery_images_ids));
	$gallery_uid = uniqid();
?>
<?php if(count($attachments_ids)) : wp_enqueue_script('thegem-gallery'); wp_enqueue_style('thegem-animations'); ?>
	<div class="preloader"><div class="preloader-spin"></div></div>
	<div class="gem-simple-gallery<?php echo ($params['responsive'] ? ' responsive' : ''); ?>"<?php echo (intval($params['autoscroll']) ? ' data-autoscroll="'.intval($params['autoscroll']).'"' : ''); ?>>
	<?php foreach($attachments_ids as $attachment_id) : ?>
		<?php
			$item = get_post($attachment_id);
			if($item) {
				$thumb_size = $params['thumb_size'];
				if ($thumb_size == 'thegem-blog-timeline-large') {
					$thumb_size = 'thegem-blog-default-large';
				}
				if ($thumb_size == 'thegem-blog-multi-author') {
					$thumb_size = 'thegem-blog-default-large';
				}
				if ($thumb_size == 'thegem-blog-masonry-100' || $thumb_size == 'thegem-blog-masonry-2x' || $thumb_size == 'thegem-blog-masonry-3x' || $thumb_size == 'thegem-blog-masonry-4x') {
					$thumb_size = 'thegem-blog-masonry';
				}
				if ($thumb_size == 'thegem-blog-justified-2x' || $thumb_size == 'thegem-blog-justified-3x' || $thumb_size == 'thegem-blog-justified-4x') {
					$thumb_size = 'thegem-blog-justified';
				}
				$thumb_image_url = thegem_generate_thumbnail_src($item->ID, $thumb_size);
				$full_image_url = wp_get_attachment_image_src($item->ID, 'full');

				$thegem_sources = array();
				if ($params['thumb_size'] == 'thegem-gallery-simple') {
					$thegem_sources = array(
						array('srcset' => array('1x' => 'thegem-gallery-simple-1x', '2x' => 'thegem-gallery-simple'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-timeline-large') {
					$thegem_sources = array(
						array('media' => '(max-width: 768px)', 'srcset' => array('' => 'thegem-blog-timeline-large')),
						array('media' => '(max-width: 1050px)', 'srcset' => array('' => 'thegem-blog-timeline-small')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-timeline')),
						array('srcset' => array('2x' => 'thegem-blog-default-large'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-multi-author') {
					if (is_active_sidebar('page-sidebar')) {
						$thegem_sources = array(
							array('media' => '(min-width: 992px) and (max-width: 1080px)', 'srcset' => array('' => 'thegem-blog-default-small')),
							array('media' => '(max-width: 992px), (min-width: 1080px) and (max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-default-medium')),
							array('srcset' => array('2x' => 'thegem-blog-default-large'))
						);
					} else {
						$thegem_sources = array(
							array('media' => '(max-width: 1075px)', 'srcset' => array('' => 'thegem-blog-default-medium')),
							array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-default-large')),
							array('srcset' => array('2x' => 'thegem-blog-default-large'))
						);
					}
				}
				if ($params['thumb_size'] == 'thegem-blog-masonry-100') {
					$thegem_sources = array(
						array('media' => '(max-width: 600px)', 'srcset' => array('' => 'thegem-blog-masonry')),
						array('media' => '(max-width: 992px)', 'srcset' => array('' => 'thegem-blog-masonry-100-medium')),
						array('media' => '(max-width: 1100px)', 'srcset' => array('' => 'thegem-blog-masonry-100-small')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-masonry-100')),
						array('srcset' => array('2x' => 'thegem-blog-masonry'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-masonry-2x') {
					$thegem_sources = array(
						array('media' => '(max-width: 600px)', 'srcset' => array('' => 'thegem-blog-masonry')),
						array('media' => '(max-width: 992px)', 'srcset' => array('' => 'thegem-blog-masonry-100-medium')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-masonry-100')),
						array('srcset' => array('2x' => 'thegem-blog-masonry'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-masonry-3x') {
					$thegem_sources = array(
						array('media' => '(max-width: 600px)', 'srcset' => array('' => 'thegem-blog-masonry')),
						array('media' => '(max-width: 992px)', 'srcset' => array('' => 'thegem-blog-masonry-100-medium')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-masonry-100')),
						array('srcset' => array('2x' => 'thegem-blog-masonry'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-masonry-4x') {
					$thegem_sources = array(
						array('media' => '(max-width: 600px)', 'srcset' => array('' => 'thegem-blog-masonry')),
						array('media' => '(max-width: 992px)', 'srcset' => array('' => 'thegem-blog-masonry-100-medium')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-masonry-4x')),
						array('srcset' => array('2x' => 'thegem-blog-masonry'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-justified-2x') {
					$thegem_sources = array(
						array('media' => '(max-width: 992px)', 'srcset' => array('' => 'thegem-blog-justified')),
						array('media' => '(max-width: 1100px)', 'srcset' => array('' => 'thegem-blog-justified-3x-small')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-justified-3x')),
						array('srcset' => array('2x' => 'thegem-blog-justified'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-justified-3x') {
					$thegem_sources = array(
						array('media' => '(max-width: 992px)', 'srcset' => array('' => 'thegem-blog-justified')),
						array('media' => '(max-width: 1100px)', 'srcset' => array('' => 'thegem-blog-justified-3x-small')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-justified-3x')),
						array('srcset' => array('2x' => 'thegem-blog-justified'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-justified-4x') {
					$thegem_sources = array(
						array('media' => '(max-width: 600px)', 'srcset' => array('' => 'thegem-blog-justified')),
						array('media' => '(max-width: 992px)', 'srcset' => array('' => 'thegem-blog-justified-4x')),
						array('media' => '(max-width: 1125px)', 'srcset' => array('' => 'thegem-blog-justified-3x-small')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-justified-4x')),
						array('srcset' => array('2x' => 'thegem-blog-justified'))
					);
				}
			}
		?>
		<?php if(!empty($thumb_image_url[0]) && $item) : ?>
			<div class="gem-gallery-item">
				<div class="gem-gallery-item-image">
					<a href="<?php echo esc_attr($full_image_url[0]); ?>" class="fancy-gallery" data-fancybox="gallery-<?php echo esc_attr($gallery_uid); ?>">
						<?php thegem_generate_picture($item->ID, $thumb_size, $thegem_sources, array('class' => 'img-responsive', 'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true))); ?>
					</a>
				</div>
				<div class="gem-gallery-caption">
					<?php if($item->post_excerpt) : ?><div class="gem-gallery-item-title"><?php echo apply_filters('the_excerpt', $item->post_excerpt); ?></div><?php endif; ?>
					<?php if($item->post_content) : ?><div class="gem-gallery-item-description"><?php echo apply_filters('the_content', $item->post_content); ?></div><?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php
}

function thegem_clients($params) {
	$params = array_merge(array(
		'clients_set' => '',
		'rows' => '3',
		'cols' => '3',
		'autoscroll' => '',
		'effects_enabled' => false,
		'disable_grayscale' => false,
		'widget' => false,
		'disable_carousel' => false,
	), $params);
	$args = array(
		'post_type' => 'thegem_client',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['clients_set'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'thegem_clients_sets',
				'field' => 'slug',
				'terms' => explode(',', $params['clients_set'])
			)
		);
	}

	$clients_items = new WP_Query($args);

	$rows = ((int)$params['rows']) ? (int)$params['rows'] : 3;
	$cols = ((int)$params['cols']) ? (int)$params['cols'] : 3;

	$items_per_slide = $rows * $cols;
	$params['autoscroll'] = intval($params['autoscroll']);

	if ($params['effects_enabled']) {
		thegem_lazy_loading_enqueue();
	}

	if($clients_items->have_posts()) {

		wp_enqueue_script('thegem-clients-grid-carousel');
		if(!$params['disable_carousel']) {
			echo '<div class="preloader"><div class="preloader-spin"></div></div>';
		}

		echo '<div class="gem-clients gem-clients-type-carousel-grid '.($params['disable_carousel'] ? 'carousel-disabled ' : '') . ($params['disable_grayscale'] ? 'disable-grayscale ' : '') . ($params['effects_enabled'] ? 'lazy-loading' : '') . '" '.(isset($params['effects_enabled_delay']) && !empty($params['effects_enabled_delay']) ? 'data-ll-item-delay="'.$params['effects_enabled_delay'].'"' : '').' data-autoscroll="'.esc_attr($params['autoscroll']).'">';
		echo '<div class="gem-clients-slide"><div class="gem-clients-slide-inner clearfix">';
		$i = 0;
		while($clients_items->have_posts()) {
			$clients_items->the_post();
			if($i == $items_per_slide) {
				echo '</div></div><div class="gem-clients-slide"><div class="gem-clients-slide-inner clearfix">';
				$i = 0;
			}
			include(locate_template('content-clients-carousel-grid-item.php'));
			$i++;
		}
		echo '</div></div>';
		echo '</div>';
	}
	wp_reset_postdata();
}

function thegem_testimonialss($params) {
	$params = array_merge(array('testimonials_set' => '', 'fullwidth' => '', 'autoscroll' => 0), $params);
	$args = array(
		'post_type' => 'thegem_testimonial',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['testimonials_set'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'thegem_testimonials_sets',
				'field' => 'slug',
				'terms' => explode(',', $params['testimonials_set'])
			)
		);
	}
	$testimonials_items = new WP_Query($args);

	if($testimonials_items->have_posts()) {
		wp_enqueue_style('thegem-testimonials');
		wp_enqueue_script('thegem-testimonials-carousel');
		echo '<div class="preloader"><div class="preloader-spin"></div></div>';
		echo '<div class="'.$params['image_size'].' '.$params['style'].' gem-testimonials'.( $params['fullwidth'] ? ' fullwidth-block' : '' ).'"'.( intval($params['autoscroll']) ? ' data-autoscroll="'.intval($params['autoscroll']).'"' : '' ).' style=background-color:'.$params['background_color'].'>';
		while($testimonials_items->have_posts()) {
			$testimonials_items->the_post();
			include(locate_template('content-testimonials-carousel-item.php'));
		}

		echo '<div class="testimonials_svg"><svg style="fill: '.$params['background_color'].'" width="100" height="50"><path d="M 0,-1 Q 45,5 50,50 Q 55,5 100,-1" /></svg></div>';

		echo '</div>';
	}
	wp_reset_postdata();
}

function portolios_cmp($term1, $term2) {
	$order1 = get_option('portfoliosets_' . $term1->term_id . '_order', 0);
	$order2 = get_option('portfoliosets_' . $term2->term_id . '_order', 0);
	if($order1 == $order2)
		return 0;
	return $order1 > $order2;
}

// Print Portfolio Block
function thegem_portfolio($params) {
	$params = array_merge(
		array(
			'portfolio_uid' => substr(md5(rand()), 0, 7),
			'portfolio' => '',
			'title' => '',
			'columns' => '3x',
			'layout_version' => 'fullwidth',
			'caption_position' => 'right',
			'layout' => 'justified',
			'gaps_size' => 42,
			'display_titles' => 'page',
			'background_style' => 'white',
			'title_style' => 'light',
			'hover' => '',
			'pagination' => 'normal',
			'loading_animation' => 'move-up',
			'items_per_page' => 8,
			'with_filter' => false,
			'show_info' => false,
			'is_ajax' => false,
			'disable_socials' => false,
			'columns_100' => '5',
			'likes' => false,
			'sorting' => false,
			'all_text' => __('Show All', 'thegem'),
			'orderby' => '',
			'order' => '',
			'button' => array(),
			'metro_max_row_height' => 380,
			'ignore_highlights' => '',
			'skeleton_loader' => '',
			'next_page_preloading' => '',
			'filters_preloading' => '',
		),
		$params
	);

	$params['button'] = array_merge(array(
		'text' => __('Load More', 'thegem'),
		'style' => 'flat',
		'size' => 'medium',
		'text_weight' => 'normal',
		'no_uppercase' => 0,
		'corner' => 25,
		'border' => 2,
		'text_color' => '',
		'background_color' => '#00bcd5',
		'border_color' => '',
		'hover_text_color' => '',
		'hover_background_color' => '',
		'hover_border_color' => '',
		'icon_pack' => 'elegant',
		'icon_elegant' => '',
		'icon_material' => '',
		'icon_fontawesome' => '',
		'icon_thegem_header' => '',
		'icon_userpack' => '',
		'icon_position' => 'left',
		'separator' => 'load-more',
	), $params['button']);

	$params['button']['icon'] = '';
	if ($params['button']['icon_elegant'] && $params['button']['icon_pack'] == 'elegant') {
		$params['button']['icon'] = $params['button']['icon_elegant'];
	}
	if ($params['button']['icon_material'] && $params['button']['icon_pack'] == 'material') {
		$params['button']['icon'] = $params['button']['icon_material'];
	}
	if ($params['button']['icon_fontawesome'] && $params['button']['icon_pack'] == 'fontawesome') {
		$params['button']['icon'] = $params['button']['icon_fontawesome'];
	}
	if ($params['button']['icon_thegem_header'] && $params['button']['icon_pack'] == 'thegem-header') {
		$params['button']['icon'] = $params['button']['icon_thegem_header'];
	}
	if ($params['button']['icon_userpack'] && $params['button']['icon_pack'] == 'userpack') {
		$params['button']['icon'] = $params['button']['icon_userpack'];
	}

	$params['loading_animation'] = thegem_check_array_value(array('disabled', 'bounce', 'move-up', 'fade-in', 'fall-perspective', 'scale', 'flip'), $params['loading_animation'], 'move-up');

	$gap_size = isset($params['gaps_size']) && $params['gaps_size'] != '' ? round(intval($params['gaps_size'])) : 0;
	$gap_size_tablet = isset($params['gaps_size_tablet']) && $params['gaps_size_tablet'] != '' ? round(intval($params['gaps_size_tablet'])) : null;
	$gap_size_mobile = isset($params['gaps_size_mobile']) && $params['gaps_size_mobile'] != '' ? round(intval($params['gaps_size_mobile'])) : null;

	if (empty($params['columns_100']))
		$params['columns_100'] = 5;

	if ($params['sorting'] == 'false')
		$params['sorting'] = false;

	if ($params['sorting'] == 'true')
		$params['sorting'] = true;

	wp_enqueue_style('thegem-portfolio');

	if ($params['layout_type'] == 'creative') {
		$params['layout'] = 'creative';
		$params['columns_desktop'] = $params['columns_desktop_creative'];
		$params['columns_100'] = $params['columns_100_creative'];
		$params['ignore_highlights'] = '1';
	}

	if ($params['layout'] !== 'creative' && ($params['layout'] !== 'justified' || $params['ignore_highlights'] !== '1')) {
		if ($params['layout']  == 'metro') {
			wp_enqueue_script('thegem-isotope-metro');
		} else {
			wp_enqueue_script('thegem-isotope-masonry-custom');
		}
	}

	if ($params['loading_animation'] !== 'disabled') {
		wp_enqueue_style('thegem-animations');
		wp_enqueue_script('thegem-scroll-monitor');
		wp_enqueue_script('thegem-items-animations');
	}

	wp_enqueue_script('thegem-portfolio-grid-extended');

	$single_post_id = thegem_templates_init_portfolio() ? thegem_templates_init_portfolio()->ID : get_the_ID();

	if ($params['exclude_type'] == 'current') {
		$params['exclude_portfolios'] = [$single_post_id];
	} else if ($params['exclude_type'] == 'term') {
		$params['exclude_portfolios'] = thegem_get_posts_query_section_exclude_ids($params['exclude_terms'], 'thegem_pf_item');
	} else {
		$params['exclude_portfolios'] = !empty($params['exclude_portfolios']) ? explode(',', $params['exclude_portfolios']) : [];
	}

	$taxonomy_filter = $portfolios_posts = [];

	if ($params['query_type'] == 'related') {

		$taxonomies = explode(',', $params['taxonomy_related']);
		if (!empty($taxonomies)) {
			foreach ($taxonomies as $tax) {
				$taxonomy_filter[$tax] = [];
				$tax_terms = get_the_terms($single_post_id, $tax);
				if (!empty($tax_terms) && !is_wp_error($tax_terms)) {
					foreach ($tax_terms as $term) {
						$taxonomy_filter[$tax][] = $term->slug;
					}
				}
			}
		}
		$params['related_tax_filter'] = $taxonomy_filter;

		if ($params['exclude_portfolios']) {
			$params['exclude_portfolios'][] = $single_post_id;
		} else {
			$params['exclude_portfolios'] = [$single_post_id];
		}
	} else {
		$params['source'] = explode(',', $params['source']);
		foreach ($params['source'] as $source) {
			if ($source == 'posts') {
				$params['content_portfolios_posts'] = explode(',', $params['content_portfolios_posts']);
				$portfolios_posts = $params['content_portfolios_posts'];
			} else if (!empty($params['content_portfolios_' . $source])) {
				$tax_terms = $params['content_portfolios_' . $source] = explode(',', $params['content_portfolios_' . $source]);
				if (!empty($tax_terms)) {
					$taxonomy_filter[$source] = $tax_terms;
				}
			}
		}
	}

	$grid_uid = $params['portfolio_uid'];
	$grid_uid_url = $grid_uid . '-';

	if ($params['sorting'] && $params['filter_type'] == 'default' && $params['filter_style'] == 'buttons') {
		$params['orderby'] = 'date';
	} else if (!isset($params['orderby']) || $params['orderby'] == 'default') {
		$params['orderby'] = 'menu_order ID';
	}

	if ($params['sorting'] && $params['filter_type'] == 'default' && $params['filter_style'] == 'buttons') {
		$params['order'] = 'desc';
	} else if (!isset($params['order']) || $params['order'] == 'default') {
		$params['order'] = 'asc';
	}

	if ($params['with_filter'] && $params['filter_type'] == 'default') {
		if (isset($taxonomy_filter[$params['filter_by']])) {
			$terms = $taxonomy_filter[$params['filter_by']];
			foreach ($terms as $key => $term) {
				$terms[$key] = get_term_by('slug', $term, $params['filter_by'] );
				if (!$terms[$key]) {
					unset($terms[$key]);
				}
			}
		} else {
			$terms = get_terms(array(
				'taxonomy' => $params['filter_by'],
				'orderby' => $params['attribute_order_by'],
			));
		}
		if ($params['filter_by'] == 'thegem_portfolios' && $params['attribute_order_by'] == 'term_order') {
			usort($terms, 'portolios_cmp');
		}
	}

	$taxonomy_filter_current = $taxonomy_filter;
	$categories_filter = [];
	if (isset($_GET[$grid_uid_url . 'category'])) {
		$categories_filter = explode(",", $_GET[$grid_uid_url . 'category']);
		$taxonomy_filter_current['thegem_portfolios'] = $categories_filter;
	} else if ($params['sorting'] && $params['filter_type'] == 'default' && !$params['filter_show_all'] && $params['filter_by'] == 'thegem_portfolios') {
		foreach ($terms as $term) {
			$categories_filter = [$term->slug];
			break;
		}
		$taxonomy_filter_current['thegem_portfolios'] = $categories_filter;
	}

	$style_uid = substr(md5(rand()), 0, 7);

	if ($params['columns_desktop'] != '1x') {
		$params['caption_position'] = $params['display_titles'];

		if ($params['caption_position'] == 'hover') {
			$params['hover'] = $params['hover_hover'];
		} else if ($params['caption_position'] == 'image') {
			if ($params['thegem_elementor_preset'] == 'alternative') {
				$params['hover'] = $params['hover_image_alternative'];
			} else {
				$params['hover'] = $params['hover_image'];
			}
		}
	} else {
		$params['background_style'] = $params['background_style_list'];
		$params['background_color'] = $params['background_color_list'];
		$params['background_color_hover'] = $params['background_color_hover_list'];

		if ($params['caption_position'] == 'hover') {
			$params['display_titles'] = 'hover';
			$params['hover'] = $params['image_hover_effect_hover'];
		} else {
			$params['hover'] = $params['image_hover_effect'];
		}
	}
	wp_enqueue_style('thegem-hovers-' . $params['hover']);

	$details_list = select_portfolio_details();
	$custom_fields_list = select_theme_options_custom_fields('portfolio');
	$meta_list = array_merge($details_list, $custom_fields_list);
	if (thegem_is_plugin_active('advanced-custom-fields/acf.php') || thegem_is_plugin_active('advanced-custom-fields-pro/acf.php')){
		foreach (thegem_cf_get_acf_plugin_groups() as $gr){
			$meta_list = array_merge($meta_list, thegem_cf_get_acf_plugin_fields_by_group($gr));
		}
	}
	$meta_list = array_flip($meta_list);

	if ($params['search_by'] == 'meta') {
		$params['search_by'] = array_keys($meta_list);
	}

	if (!empty($params['image_ratio_default'])) {
		$params['image_aspect_ratio'] = 'custom';
		$params['image_ratio_custom'] = $params['image_ratio_default'];
	}

	$localize = array(
		'data' => $params,
		'action' => 'portfolio_grid_load_more',
		'url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('portfolio_ajax-nonce')
	);
	wp_localize_script('thegem-portfolio-grid-extended', 'thegem_portfolio_ajax_' . $style_uid, $localize);

	$inlineStyles = '';
	$gridSelector = '.portfolio.portfolio-grid#style-' . $style_uid;
	$gridSelectorSkeleton = '.preloader#style-preloader-' . $style_uid;
	$itemSelector = $gridSelector . ' .portfolio-item';
	$captionSelector = $itemSelector . ' .caption';

	if (isset($gap_size)) {
		$inlineStyles .= $gridSelector . ' .portfolio-item:not(.size-item), ' . $gridSelectorSkeleton . ' .portfolio-item { padding: calc(' . $gap_size . 'px/2) !important; }' .
			$gridSelector . ' .portfolio-item.size-item { padding: 0 calc(' . $gap_size . 'px/2) !important; }' .
			$gridSelector . ' .portfolio-row, ' . $gridSelectorSkeleton . ' .skeleton-posts.portfolio-row { margin: calc(-' . $gap_size . 'px/2); }' .
			$gridSelector . '.fullwidth-columns .portfolio-row { margin: calc(-' . $gap_size . 'px/2) 0; }' .
			$gridSelector . ' .fullwidth-block:not(.no-paddings) { padding-left: ' . $gap_size . 'px; padding-right: ' . $gap_size . 'px; }' .
			$gridSelector . ' .fullwidth-block .portfolio-row { padding-left: calc(' . $gap_size . 'px/2); padding-right: calc(' . $gap_size . 'px/2); }' .
			$gridSelector . ' .fullwidth-block .portfolio-top-panel { padding-left: ' . $gap_size . 'px; padding-right: ' . $gap_size . 'px; }' .
			$gridSelector . '.fullwidth-columns .with-filter-sidebar .filter-sidebar { padding-left: ' . $gap_size . 'px; }';
	}
	if (isset($gap_size_tablet)) {
		$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .portfolio-item:not(.size-item), ' . $gridSelectorSkeleton . ' .portfolio-item { padding: calc(' . $gap_size_tablet . 'px/2) !important; }' .
			$gridSelector . ' .portfolio-item.size-item { padding: 0 calc(' . $gap_size_tablet . 'px/2) !important; }' .
			$gridSelector . ' .portfolio-row, ' . $gridSelectorSkeleton . ' .skeleton-posts.portfolio-row { margin: calc(-' . $gap_size_tablet . 'px/2); }' .
			$gridSelector . '.fullwidth-columns .portfolio-row { margin: calc(-' . $gap_size_tablet . 'px/2) 0; }' .
			$gridSelector . ' .fullwidth-block:not(.no-paddings) { padding-left: ' . $gap_size_tablet . 'px; padding-right: ' . $gap_size_tablet . 'px; }' .
			$gridSelector . ' .fullwidth-block .portfolio-row { padding-left: calc(' . $gap_size_tablet . 'px/2); padding-right: calc(' . $gap_size_tablet . 'px/2); }' .
			$gridSelector . ' .fullwidth-block .portfolio-top-panel { padding-left: ' . $gap_size_tablet . 'px; padding-right: ' . $gap_size_tablet . 'px; }' .
			$gridSelector . '.fullwidth-columns .with-filter-sidebar .filter-sidebar { padding-left: ' . $gap_size_tablet . 'px; }}';
	}
	if (isset($gap_size_mobile)) {
		$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .portfolio-item:not(.size-item), ' . $gridSelectorSkeleton . ' .portfolio-item { padding: calc(' . $gap_size_mobile . 'px/2) !important; }' .
			$gridSelector . ' .portfolio-item.size-item { padding: 0 calc(' . $gap_size_mobile . 'px/2) !important; }' .
			$gridSelector . ' .portfolio-row, ' . $gridSelectorSkeleton . ' .skeleton-posts.portfolio-row { margin: calc(-' . $gap_size_mobile . 'px/2); }' .
			$gridSelector . '.fullwidth-columns .portfolio-row { margin: calc(-' . $gap_size_mobile . 'px/2) 0; }' .
			$gridSelector . ' .fullwidth-block:not(.no-paddings) { padding-left: ' . $gap_size_mobile . 'px; padding-right: ' . $gap_size_mobile . 'px; }' .
			$gridSelector . ' .fullwidth-block .portfolio-row { padding-left: calc(' . $gap_size_mobile . 'px/2); padding-right: calc(' . $gap_size_mobile . 'px/2); }' .
			$gridSelector . ' .fullwidth-block .portfolio-top-panel { padding-left: ' . $gap_size_mobile . 'px; padding-right: ' . $gap_size_mobile . 'px; }' .
			$gridSelector . '.fullwidth-columns .with-filter-sidebar .filter-sidebar { padding-left: ' . $gap_size_mobile . 'px; }}';
	}
	if (($params['with_filter'] || $params['sorting']) && ($params['columns_desktop'] == '100%' || $params['fullwidth_section_sorting'])) {
		if (isset($gap_size) && $gap_size < 21) {
			$inlineStyles .= $gridSelector . ' .fullwidth-block .portfolio-top-panel:not(.gem-sticky-block), ' .
				$gridSelector . ' .portfolio-item.not-found .found-wrap { padding-left: 21px !important; padding-right: 21px !important; }' .
				$gridSelector . ' .with-filter-sidebar .filter-sidebar { padding-left: 21px !important; }';
		}
		if (isset($gap_size_tablet) && $gap_size_tablet < 21) {
			$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .fullwidth-block .portfolio-top-panel:not(.gem-sticky-block), ' .
				$gridSelector . ' .portfolio-item.not-found .found-wrap { padding-left: 21px !important; padding-right: 21px !important; }' .
				$gridSelector . ' .with-filter-sidebar .filter-sidebar { padding-left: 21px !important; }}';
		}
		if (isset($gap_size_mobile) && $gap_size_mobile < 21) {
			$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .fullwidth-block .portfolio-top-panel:not(.gem-sticky-block), ' .
				$gridSelector . ' .portfolio-item.not-found .found-wrap { padding-left: 21px !important; padding-right: 21px !important; }' .
				$gridSelector . ' .with-filter-sidebar .filter-sidebar { padding-left: 21px !important; }}';
		}
	}
	if (isset($params['image_size']) && $params['image_size'] == 'full' && !empty($params['image_ratio'])) {
		$inlineStyles .= $itemSelector . ':not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: ' . esc_attr($params['image_ratio']) . ' !important; height: auto; }';
	}
	if (isset($params['image_size']) && $params['image_size'] == 'default' && !empty($params['image_ratio_default'])) {
		$inlineStyles .= $itemSelector . ':not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: ' . esc_attr($params['image_ratio_default']) . ' !important; height: auto; }';
	}
	if (!empty($params['image_height']) && (!isset($params['image_size']) || $params['image_size'] == 'default')) {
		$inlineStyles .= $itemSelector . ':not(.double-item) .image-inner { height: ' . esc_attr($params['image_height']) . 'px !important; padding-bottom: 0 !important; aspect-ratio: initial !important; }';
		$inlineStyles .= $itemSelector . ':not(.double-item) .gem-simple-gallery .gem-gallery-item a { height: ' . esc_attr($params['image_height']) . 'px !important; }';
	}
	if (!empty($params['caption_container_alignment'])) {
		$inlineStyles .= $captionSelector . ' { text-align: ' . esc_attr($params['caption_container_alignment']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .caption-separator { margin-' . esc_attr($params['caption_container_alignment']) . ' : 0 !important; }';
		$inlineStyles .= $captionSelector . ' .portfolio-likes { text-align: -webkit-' . esc_attr($params['caption_container_alignment']) . ' !important; }';
	}
	if (!empty($params['background_color'])) {
		$inlineStyles .= $captionSelector . ' { background-color: ' . esc_attr($params['background_color']) . ' !important; }';
	}
	if (!empty($params['background_color_hover'])) {
		$inlineStyles .= $itemSelector . ':hover .caption { background-color: ' . esc_attr($params['background_color_hover']) . ' !important; }';
	}
	foreach (['top', 'bottom', 'left', 'right'] as $position) {
		if (isset($params['caption_padding_' . $position]) && $params['caption_padding_' . $position] != '') {
			$inlineStyles .= $captionSelector .' { padding-' . $position . ': ' . intval( $params['caption_padding_' . $position] ) . 'px !important; }';
		}
		if (isset($params['caption_padding_' . $position . '_tablet']) && $params['caption_padding_' . $position . '_tablet'] != '') {
			$inlineStyles .= '@media (max-width: 991px) { ' . $captionSelector .' { padding-' . $position . ': ' . intval( $params['caption_padding_' . $position . '_tablet'] ) . 'px !important; }}';
		}
		if (isset($params['caption_padding_' . $position . '_mobile']) && $params['caption_padding_' . $position . '_mobile'] != '') {
			$inlineStyles .= '@media (max-width: 767px) { ' . $captionSelector .' { padding-' . $position . ': ' . intval( $params['caption_padding_' . $position . '_mobile'] ) . 'px !important; }}';
		}
	}
	if (!empty($params['border_color'])) {
		$inlineStyles .= $captionSelector . ' { border-bottom-color: ' . esc_attr($params['border_color']) . ' !important; }';
	}
	if (!empty($params['title_transform'])) {
		$inlineStyles .= $captionSelector . ' .title span { text-transform: ' . esc_attr($params['title_transform']) . ' !important; }';
	}
	if (isset($params['title_letter_spacing'])) {
		$inlineStyles .= $captionSelector . ' .title span { letter-spacing: ' . esc_attr($params['title_letter_spacing']) . 'px !important; }';
	}
	if (!empty($params['title_color'])) {
		$inlineStyles .= $captionSelector . ' .title { color: ' . esc_attr($params['title_color']) . ' !important; }';
	}
	if (!empty($params['title_color_hover'])) {
		$inlineStyles .= $itemSelector . ':hover .caption .title { color: ' . esc_attr($params['title_color_hover']) . ' !important; }';
	}
	if (!empty($params['desc_color'])) {
		$inlineStyles .= $captionSelector . ' .subtitle { color: ' . esc_attr($params['desc_color']) . ' !important; }';
	}
	if (!empty($params['desc_color_hover'])) {
		$inlineStyles .= $itemSelector . ':hover .caption .subtitle { color: ' . esc_attr($params['desc_color_hover']) . ' !important; }';
	}
	if (!empty($params['separator_color'])) {
		$inlineStyles .= $captionSelector . ' .caption-separator { background-color: ' . esc_attr($params['separator_color']) . ' !important; }';
	}
	if (!empty($params['truncate_titles']) || $params['thegem_elementor_preset'] == 'alternative') {
		$truncate_titles = !empty($params['truncate_titles']) ? $params['truncate_titles'] : '2';
		$inlineStyles .= $captionSelector . ' .title span { white-space: initial; max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($truncate_titles) . '; line-clamp: ' . esc_attr($truncate_titles) . '; -webkit-box-orient: vertical; }';
	}
	if (!empty($params['truncate_info'])) {
		$inlineStyles .= $captionSelector . ' .info { white-space: initial; max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($params['truncate_info']) . '; line-clamp: ' . esc_attr($params['truncate_info']) . '; -webkit-box-orient: vertical; }';
	}
	if (!empty($params['truncate_description']) || $params['thegem_elementor_preset'] == 'alternative') {
		$truncate_description = !empty($params['truncate_description']) ? $params['truncate_description'] : '2';
		$inlineStyles .= $captionSelector . ' .subtitle { max-height: initial !important; }';
		$inlineStyles .= $captionSelector . ' .subtitle span { white-space: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($truncate_description) . '; line-clamp: ' . esc_attr($truncate_description) . '; -webkit-box-orient: vertical; }';
		$inlineStyles .= $captionSelector . ' .subtitle a, ' . $itemSelector . ' .caption .subtitle p { white-space: initial; overflow: initial; }';
	}
	if (!empty($params['categories_color'])) {
		$inlineStyles .= $captionSelector . ' .info a { color: ' . esc_attr($params['categories_color']) . '; }';
	}
	if (!empty($params['categories_color_hover'])) {
		$inlineStyles .= $captionSelector . ' .info a:hover { color: ' . esc_attr($params['categories_color_hover']) . '; }';
	}
	if (!empty($params['date_color'])) {
		$inlineStyles .= $captionSelector . ' .info { color: ' . esc_attr($params['date_color']) . '; }';
	}
	if (!empty($params['zoom_overlay_color'])) {
		$inlineStyles .= $gridSelector . '.hover-zoom-overlay .portfolio-item .image .overlay:before { background-color: ' . esc_attr($params['zoom_overlay_color']) . '; }';
	}

	if (!empty($inlineStyles)) {
		echo '<style>'.$inlineStyles.'</style>';
	}

	if ($params['with_filter'] && $params['filter_type'] == 'default') {
		$params['filters_sticky'] = $params['default_filters_sticky'];
		$params['filters_sticky_color'] = $params['default_filters_sticky_color'];
	}

	echo thegem_portfolio_news_render_styles($params, $gridSelector);

	$page = 1;

	if (isset($_GET[$grid_uid_url . 'page'])) {
		$page = $_GET[$grid_uid_url . 'page'];
	}

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

	$selected_orderby = $selected_order = 'default';

	if (!empty($_GET[$grid_uid_url . 'orderby'])) {
		$orderby = $_GET[$grid_uid_url . 'orderby'];
		$selected_orderby = $orderby;
	} else {
		$orderby = $params['orderby'];
	}

	if (!empty($_GET[$grid_uid_url . 'order'])) {
		$order = $_GET[$grid_uid_url . 'order'];
		if ($params['filter_type'] == 'extended' && $params['sorting_type'] == 'extended') {
			$selected_order = $order;
		} else {
			if ($selected_orderby != 'default') {
				$selected_orderby .= '-' . $order;
			}
		}
	} else {
		$order = $params['order'];
	}

	$taxonomies_list = array_flip(select_post_type_taxonomies('thegem_pf_item'));
	$portfolios_filters_tax_url = $portfolios_filters_meta_url = $meta_filter_current = [];
	foreach($_GET as $key => $value) {
		if (strpos($key, $grid_uid_url . 'filter_tax_') === 0) {
			$attr = str_replace($grid_uid_url . 'filter_tax_', '', $key);
			$portfolios_filters_tax_url['tax_' . $attr] = $taxonomy_filter_current[$attr] = explode(",", $value);
		} else if (strpos($key, $grid_uid_url . 'filter_meta_') === 0) {
			$attr = str_replace($grid_uid_url . 'filter_meta_', '', $key);
			$portfolios_filters_meta_url['meta_' . $attr] = $meta_filter_current[$attr] = explode(",", $value);
		}
	}
	if (empty($portfolios_filters_tax_url) && $params['with_filter'] && $params['filter_type'] == 'default' && !$params['filter_show_all'] && $params['filter_by'] != 'thegem_portfolios') {
		$active_tax = '';
		foreach ($terms as $term) {
			$active_tax = $term->slug;
			break;
		}
		$portfolios_filters_tax_url['tax_' . $params['filter_by']] = $taxonomy_filter_current[$params['filter_by']] = [$active_tax];
	}
	$attributes_filter = array_merge($portfolios_filters_tax_url, $portfolios_filters_meta_url);
	if (empty($attributes_filter)) { $attributes_filter = null; }

	$search_current = null;
	if (!empty($_GET[$grid_uid_url . 's'])) {
		$search_current = $_GET[$grid_uid_url . 's'];
	}

	$portfolio_title = $params['title'] ?: '';

	$portfolio_loop = thegem_get_portfolio_posts($portfolios_posts, $taxonomy_filter_current, $meta_filter_current, $search_current, $params['search_by'], $page, $items_on_load, $orderby, $order, intval($params['offset']), $params['exclude_portfolios']);

	if ($portfolio_loop && $portfolio_loop->have_posts() || !empty($categories_filter) || !empty($attributes_filter) || !empty($search_current)) {

		$max_page = ceil(($portfolio_loop->found_posts - intval($params['offset'])) / $items_per_page);

		if ($params['reduce_html_size']) {
			$next_page = $portfolio_loop->found_posts > $items_on_load ? 2 : 0;
			$next_page_pagination = $max_page > $page ? $page + 1 : 0;
		} else {
			$next_page = $next_page_pagination = $max_page > $page ? $page + 1 : 0;
		}

		$item_classes = get_thegem_portfolio_render_item_classes($params);
		$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params);

		if ($params['layout'] !== 'creative') {
			if ($params['columns_desktop'] == '100%' || (($params['ignore_highlights'] !== '1' || in_array($params['layout'], ['masonry', 'metro'])) && $params['skeleton_loader'] !== '1')) {
				$spin_class = 'preloader-spin';
				if ($params['ajax_preloader_type'] == 'minimal') {
					$spin_class = 'preloader-spin-new';
				}
				echo apply_filters('thegem_portfolio_preloader_html', '<div id="style-preloader-' . $style_uid . '" class="preloader save-space"><div class="' . $spin_class . '"></div></div>');
			} else if ($params['skeleton_loader'] == '1') { ?>
				<div id="style-preloader-<?php echo esc_attr($style_uid); ?>" class="preloader save-space">
					<div class="skeleton">
						<div class="skeleton-posts portfolio-row">
							<?php for ($x = 0; $x < $portfolio_loop->post_count; $x++) {
								echo thegem_portfolio_render_item($params, $item_classes);
							} ?>
						</div>
					</div>
				</div>
			<?php }
		} ?>
		<div class="portfolio-preloader-wrapper<?php echo !empty($params['sidebar_position']) ? ' panel-sidebar-position-' . $params['sidebar_position'] : ''; ?>">
			<?php if ($portfolio_title) { ?>
				<h3 class="title portfolio-title"><?php echo $portfolio_title; ?></h3>
			<?php } ?>

			<?php
			if ($params['display_titles'] == 'hover') {
				$title_on = 'hover';
			} else {
				$title_on = 'page';
			}
			$portfolio_classes = array(
				'portfolio portfolio-grid extended-portfolio-grid no-padding',
				'portfolio-pagination-' . $params['pagination'],
				'portfolio-style-' . $params['layout'],
				'background-style-' . $params['background_style'],
				'title-style-' . $params['title_style'],
				'hover-' . esc_attr($params['hover']),
				'title-on-' . $title_on,
				'hover-elements-size-' . $params['hover_elements_size'],
				($params['columns_desktop'] != '1x' ? 'version-' . $params['thegem_elementor_preset'] : ''),
				($params['caption_position'] ? 'caption-position-' . $params['caption_position'] : ''),
				($params['loading_animation'] !== 'disabled' ? 'loading-animation item-animation-' . $params['loading_animation'] : ''),
				($params['loading_animation'] !== 'disabled' && $params['loading_animation_mobile'] == '1' ? 'enable-animation-mobile' : ''),
				($gap_size == 0 ? 'no-gaps' : ''),
				($params['shadowed_container'] == '1' ? 'shadowed-container' : ''),
				($params['columns_desktop'] == '100%' || $params['fullwidth_section_sorting'] == '1' ? 'fullwidth-columns' : ''),
				($params['columns_desktop'] == '100%' ? 'fullwidth-columns-' . intval($params['columns_100']) : ''),
				($params['display_titles'] == 'image' && $params['hover'] == 'gradient' ? 'hover-gradient-title' : ''),
				($params['display_titles'] == 'image' && $params['hover'] == 'circular' ? 'hover-circular-title' : ''),
				($params['display_titles'] == 'hover' || $params['display_titles'] == 'image' ? 'hover-title' : ''),
				($params['layout'] == 'masonry' && $params['columns_desktop'] != '1x' ? 'portfolio-items-masonry' : ''),
				($params['columns_desktop'] != '100%' ? 'columns-' . str_replace("x", "", $params['columns_desktop']) : ''),
				(isset($params['columns_tablet']) ? 'columns-tablet-' . str_replace("x", "", $params['columns_tablet']) : ''),
				(isset($params['columns_mobile']) ? 'columns-mobile-' . str_replace("x", "", $params['columns_mobile']) : ''),
				($params['layout'] == 'creative' || ($params['layout'] == 'justified' && $params['ignore_highlights'] == '1') ? 'disable-isotope' : ''),
				($params['next_page_preloading'] == '1' ? 'next-page-preloading' : ''),
				($params['filter_type'] == 'default' && $params['filters_preloading'] == '1' ? 'filters-preloading' : ''),
				($params['layout'] == 'creative' && $params['scheme_apply_mobiles'] !== '1' ? 'creative-disable-mobile' : ''),
				($params['layout'] == 'creative' && $params['scheme_apply_tablets'] !== '1' ? 'creative-disable-tablet' : ''),
				($params['disable_bottom_margin'] == '1' ? 'disable-bottom-margin' : ''),
				(($params['layout'] == 'justified' && isset($params['image_size']) && (($params['image_size'] == 'full' && empty($params['image_ratio'])) || !in_array($params['image_size'], ['full', 'default']))) ? 'full-image' : ''),
				($params['ajax_preloader_type'] == 'minimal' ? 'minimal-preloader' : ''),
				($params['reduce_html_size'] ? 'reduce-size' : ''),
			);

			$portfolio_classes = apply_filters('portfolio_classes_filter', $portfolio_classes); ?>

			<div class="<?php echo implode(' ', $portfolio_classes); ?>"
				id="style-<?php echo esc_attr($style_uid); ?>"
				data-style-uid="<?php echo esc_attr($style_uid); ?>"
				data-portfolio-uid="<?php echo esc_attr($grid_uid); ?>"
				data-current-page="<?php echo esc_attr($page); ?>"
				data-per-page="<?php echo $items_per_page; ?>"
				data-next-page="<?php echo esc_attr($next_page); ?>"
				data-pages-count="<?php echo esc_attr($max_page); ?>"
				data-hover="<?php echo $params['hover']; ?>"
				data-portfolio-filter="<?php echo esc_attr(json_encode($categories_filter)); ?>"
				data-portfolio-filter-attributes="<?php echo esc_attr(json_encode($attributes_filter)); ?>"
				data-portfolio-filter-search="<?php echo esc_attr($search_current); ?>">
				<?php
				$search_right = $params['show_search'] && (!$params['with_filter'] || $params['filter_type'] == 'default' || $params['filters_style'] == 'standard');
				$has_right_panel = $params['filter_type'] == 'default' || $params['sorting'] || $search_right;
				$has_meta_filtering = false;
				$selected_shown = false;
				if (!empty($params['show_additional_meta'])) {
					$meta_type = isset($params['additional_meta_type']) ? $params['additional_meta_type'] : 'taxonomies';
					if ($meta_type == 'taxonomies') {
						$meta_taxonomies = isset($params['additional_meta_taxonomies']) ? $params['additional_meta_taxonomies'] : 'thegem_portfolios';
						if ($meta_taxonomies == 'thegem_portfolios') {
							$behavior = isset($params['additional_meta_click_behavior_meta']) ? $params['additional_meta_click_behavior_meta'] : 'filtering';
						} else {
							$behavior = isset($params['additional_meta_click_behavior']) ? $params['additional_meta_click_behavior'] : 'filtering';
						}
					} else {
						$behavior = $params['additional_meta_click_behavior_meta'];
					}
					if ($behavior == 'filtering') {
						$has_meta_filtering = true;
					}
				}
				$post_type = 'thegem_pf_item'; ?>

				<div class="portfolio-row-outer <?php if ($params['columns_desktop'] == '100%' || $params['fullwidth_section_sorting'] == '1'): ?>fullwidth-block no-paddings<?php endif; ?>">

					<?php if ($params['with_filter'] && $params['filter_type'] == 'extended' && $params['filters_style'] == 'sidebar') { ?>
						<div class="with-filter-sidebar <?php echo $params['sidebar_sticky'] ? 'sticky-sidebar' : ''; ?>">
							<?php if ($params['sidebar_sticky']) {
								wp_enqueue_script('thegem-sticky');
							} ?>
							<div class="filter-sidebar <?php echo $params['sorting'] ? 'left' : ''; ?>">
								<?php include(locate_template(array('gem-templates/portfolios/filters.php'))); ?>
							</div>
							<div class="content">
								<?php } ?>
								<?php if (($params['with_filter'] || $params['sorting'] || $has_meta_filtering)) { ?>
									<div class="portfolio-top-panel filter-type-<?php echo $params['filter_type'];
									echo $params['filter_type'] == 'extended' && $params['filters_style'] == 'sidebar' ? ' sidebar-filter' : '';
									echo (!$params['sorting'] && !$search_right && (!$params['with_filter'] || ($params['filter_type'] == 'extended' && $params['filters_style'] != 'standard'))) ? ' selected-only' : '';
									echo $search_right ? ' panel-with-search' : '';
									echo $params['filters_sticky'] ? ' filters-top-sticky' : '';
									echo $params['mobile_dropdown'] && $params['filter_style'] != 'buttons' ? ' filters-mobile-dropdown' : ''; ?>">
										<?php if ($params['filters_sticky']) {
											wp_enqueue_script('thegem-sticky');
										} ?>
											<div class="portfolio-top-panel-row filter-style-<?php echo $params['filter_style']; ?>">
											<?php if ($params['with_filter']) {
												if ($params['filter_type'] == 'default') {
													if ($params['filter_by'] != 'thegem_portfolios') {
														$categories_filter = isset($portfolios_filters_tax_url['tax_' . $params['filter_by']]) ? $portfolios_filters_tax_url['tax_' . $params['filter_by']] : [];
													}
													if (!is_wp_error($terms) && count($terms) > 0) { ?>
														<div class="portfolio-top-panel-left <?php echo strtolower($params['attribute_query_type']) == 'and' ? 'multiple' : 'single'; ?>" <?php if ($params['filter_by'] !== 'thegem_portfolios') { ?>
															data-filter-by="<?php echo 'tax_'.$params['filter_by']; ?>"
														<?php } ?>>
															<?php
															if ($params['mobile_dropdown'] && $params['filter_style'] != 'buttons') { ?>
																<div class="portfolio-filters portfolio-filters-mobile portfolio-filters-more">
																	<div class="portfolio-filters-more-button title-h6">
																		<div class="portfolio-filters-more-button-name"><?php echo $params['filters_mobile_show_button_text']; ?></div>
																		<span class="portfolio-filters-more-button-arrow"></span>
																	</div>
																	<div class="portfolio-filters-more-dropdown">
																		<?php if ($params['filter_show_all']) { ?>
																			<a href="#" data-filter="*"
																			   class="<?php echo empty($categories_filter) ? 'active' : ''; ?> all title-h6 <?php echo $params['hover_pointer'] ? 'hover-pointer' : ''; ?>">
																					<span <?php echo $params['filter_style'] == 'buttons' ? 'class="light"' : ''; ?>>
																						<?php echo $params['all_text']; ?>
																					</span>
																			</a>
																		<?php }
																		foreach ($terms as $term) {
																			if (isset($params['attribute_click_behavior']) && $params['attribute_click_behavior'] == 'archive_link') {
																				$link = get_term_link($term->slug, $params['filter_by']);
																			} else {
																				$link = '#';
																			} ?>
																			<a href="<?php echo $link; ?>" <?php if ($link == '#') { ?>
																			   data-filter=".<?php echo $term->slug; ?>"
																			   <?php } ?>class="<?php echo in_array($term->slug, $categories_filter) ? 'active' : ''; ?> title-h6 <?php echo $params['hover_pointer'] ? 'hover-pointer' : ''; ?>">
																				<span <?php echo $params['filter_style'] == 'buttons' ? 'class="light"' : ''; ?>>
																					<?php echo $term->name; ?>
																				</span>
																			</a>
																		<?php } ?>
																	</div>
																</div>
															<?php } ?>
															<div class="portfolio-filters filter-by-<?php echo $params['filter_by']; ?>">
																<?php if ($params['filter_show_all']) { ?>
																	<a href="#" data-filter="*" class="<?php echo empty($categories_filter) ? 'active' : ''; ?> all title-h6 <?php echo $params['hover_pointer'] ? 'hover-pointer' : ''; ?>">
																		<span <?php echo $params['filter_style'] == 'buttons' ? 'class="light"' : ''; ?>>
																			<?php echo $params['all_text']; ?>
																		</span>
																	</a>
																<?php }
																$i = 0;
																foreach ($terms as $term) {
																	if ($params['truncate_filters'] && $i == $params['truncate_filters_number']) { ?>
																		<div class="portfolio-filters-more">
																			<div class="portfolio-filters-more-button title-h6">
																				<div class="portfolio-filters-more-button-name <?php echo $params['filter_style'] == 'buttons' ? 'light' : ''; ?>"><?php echo $params['filters_more_button_text']; ?></div>
																				<?php if ($params['filters_more_button_arrow']) { ?>
																					<span class="portfolio-filters-more-button-arrow"></span>
																				<?php } ?>
																			</div>
																			<div class="portfolio-filters-more-dropdown">
																	<?php }
																	if (isset($params['attribute_click_behavior']) && $params['attribute_click_behavior'] == 'archive_link') {
																		$link = get_term_link($term->slug, $params['filter_by']);
																	} else {
																		$link = '#';
																	} ?>
																	<a href="<?php echo $link; ?>" <?php if ($link == '#') { ?>
																	   data-filter=".<?php echo $term->slug; ?>"
																	   <?php } ?>class="<?php echo in_array($term->slug, $categories_filter) ? 'active' : ''; ?> title-h6 <?php echo $params['hover_pointer'] ? 'hover-pointer' : ''; ?>">
																		<?php if (get_option('portfoliosets_' . $term->term_id . '_icon_pack') && get_option('portfoliosets_' . $term->term_id . '_icon')) {
																			echo thegem_build_icon(get_option('portfoliosets_' . $term->term_id . '_icon_pack'), get_option('portfoliosets_' . $term->term_id . '_icon'));
																		} ?>
																		<span <?php echo $params['filter_style'] == 'buttons' ? 'class="light"' : ''; ?>>
																			<?php echo $term->name; ?>
																		</span>
																	</a>
																	<?php if ($params['truncate_filters'] && sizeof($terms) > $params['truncate_filters_number'] && $i == sizeof($terms) - 1) { ?>
																			</div>
																		</div>
																	<?php }
																	$i++;
																} ?>
															</div>
															<?php if ($params['filter_style'] == 'buttons') {
																wp_enqueue_script('jquery-dlmenu'); ?>
																<div class="portfolio-filters-resp <?php echo strtolower($params['attribute_query_type']) == 'and' ? 'multiple' : 'single'; ?>">
																	<button class="menu-toggle dl-trigger"><?php _e('Portfolio filters', 'thegem'); ?><span class="menu-line-1"></span><span class="menu-line-2"></span><span class="menu-line-3"></span></button>
																	<ul class="dl-menu">
																		<?php if ($params['filter_show_all']) { ?>
																			<li>
																				<a href="#" data-filter="*">
																					<?php echo $params['all_text']; ?>
																				</a>
																			</li>
																		<?php } ?>
																		<?php foreach ($terms as $term) { ?>
																			<li>
																				<?php if (isset($params['attribute_click_behavior']) && $params['attribute_click_behavior'] == 'archive_link') {
																					$link = get_term_link($term->slug, $params['filter_by']);
																				} else {
																					$link = '#';
																				} ?>
																				<a href="<?php echo $link; ?>" <?php if ($link == '#') { ?>
																				   data-filter=".<?php echo $term->slug; ?>"<?php } ?>>
																					<?php echo $term->name; ?>
																				</a>
																			</li>
																		<?php } ?>
																	</ul>
																</div>
															<?php } ?>
														</div>
													<?php }
												} else { ?>
													<div class="portfolio-top-panel-left <?php echo esc_attr($params['filter_buttons_standard_alignment']); ?>">
														<?php if ($params['with_filter'] && $params['filters_style'] != 'sidebar') {
															include(locate_template(array('gem-templates/portfolios/filters.php')));
														}
														if (($params['with_filter'] && $params['filters_style'] == 'sidebar') || !$params['with_filter']) {
															$selected_shown = true;
															include(locate_template(array('gem-templates/portfolios/selected-filters.php')));
														} ?>
													</div>
												<?php }
											} else { ?>
												<div class="portfolio-top-panel-left"><?php
//													if ($has_meta_filtering) {
														$selected_shown = true;
														include(locate_template(array('gem-templates/portfolios/selected-filters.php')));
//													} ?>
												</div>
											<?php } ?>
											<?php if ($has_right_panel) { ?>
												<div class="portfolio-top-panel-right">
													<?php if ($params['sorting']) {
														if ($params['filter_type'] == 'default' && $params['filter_style'] == 'buttons') { ?>
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
														<?php } else {
															if ($params['filter_type'] == 'extended' && $params['sorting_type'] == 'extended') {
																$repeater_sort = vc_param_group_parse_atts($params['repeater_sort']);
																if (!empty($repeater_sort)) { ?>
																	<div class="portfolio-sorting-select open-dropdown-<?php
																	echo $params['sorting_dropdown_open']; ?>">
																		<div class="portfolio-sorting-select-current">
																			<div class="portfolio-sorting-select-name">
																				<?php
																				if ($selected_orderby == 'default') {
																					echo esc_html($params['sorting_extended_text']);
																				} else {
																					foreach ($repeater_sort as $item) {
																						if (in_array($item['attribute_type'], ['date', 'title', 'price', 'rating', 'popularity'])) {
																							$sort_by = $item['attribute_type'];
																						} else {
																							if ($item['attribute_type'] == 'details') {
																								$sort_by = isset($item['attribute_details']) ? $item['attribute_details'] : '';
																							} else if ($item['attribute_type'] == 'custom_fields') {
																								$sort_by = isset($item['attribute_custom_fields']) ? $item['attribute_custom_fields'] : '';
																							} else if ($item['attribute_type'] == 'manual_key') {
																								$sort_by = isset($item['manual_key_field']) ? $item['manual_key_field'] : '';
																							} else {
																								$sort_by = isset($item['attribute_custom_fields_acf_' . $item['attribute_type']]) ? $item['attribute_custom_fields_acf_' . $item['attribute_type']] : '';
																							}
																							if (empty($sort_by)) continue;
																							if (isset($item['field_type']) && $item['field_type'] == 'number') {
																								$sort_by = 'num_' . $sort_by;
																							}
																						}
																						if ($selected_orderby == $sort_by && $selected_order == $item['sort_order']) {
																							echo esc_html($item['title']);
																							break;
																						}
																					}
																				} ?>
																			</div>
																			<span class="portfolio-sorting-select-current-arrow"></span>
																		</div>
																		<ul>
																			<li class="default <?php echo $selected_orderby == 'default' ? 'portfolio-sorting-select-current' : ''; ?>"
																				data-orderby="default" data-order="default">
																				<?php echo esc_html($params['sorting_extended_text']); ?>
																			</li>
																			<?php foreach ($repeater_sort as $item) {
																				if (in_array($item['attribute_type'], ['date', 'title', 'price', 'rating', 'popularity'])) {
																					$sort_by = $item['attribute_type'];
																				} else {
																					if ($item['attribute_type'] == 'details') {
																						$sort_by = isset($item['attribute_details']) ? $item['attribute_details'] : '';
																					} else if ($item['attribute_type'] == 'custom_fields') {
																						$sort_by = isset($item['attribute_custom_fields']) ? $item['attribute_custom_fields'] : '';
																					} else if ($item['attribute_type'] == 'manual_key') {
																						$sort_by = isset($item['manual_key_field']) ? $item['manual_key_field'] : '';
																					} else {
																						$sort_by = isset($item['attribute_custom_fields_acf_' . $item['attribute_type']]) ? $item['attribute_custom_fields_acf_' . $item['attribute_type']] : '';
																					}
																					if (empty($sort_by)) continue;
																					if (isset($item['field_type']) && $item['field_type'] == 'number') {
																						$sort_by = 'num_' . $sort_by;
																					}
																				} ?>
																				<li class="<?php echo $selected_orderby == $sort_by && $selected_order == $item['sort_order'] ? 'portfolio-sorting-select-current' : ''; ?>"
																					data-orderby="<?php echo esc_attr($sort_by); ?>" data-order="<?php echo esc_attr($item['sort_order']); ?>">
																					<?php echo esc_html($item['title']); ?>
																				</li>
																			<?php } ?>
																		</ul>
																	</div>
																<?php }
															} else { ?>
																<div class="portfolio-sorting-select">
																	<div class="portfolio-sorting-select-current">
																		<div class="portfolio-sorting-select-name">
																			<?php
																			switch ($selected_orderby) {
																				case "title-asc":
																					echo esc_html($params['sorting_extended_dropdown_title_text']);
																					break;
																				case "title-desc":
																					echo esc_html($params['sorting_extended_dropdown_title_desc_text']);
																					break;
																				case "date-desc":
																					echo esc_html($params['sorting_extended_dropdown_latest_text']);
																					break;
																				case "date-asc":
																					echo esc_html($params['sorting_extended_dropdown_oldest_text']);
																					break;
																				default:
																					echo esc_html($params['sorting_extended_text']);
																			} ?>
																		</div>
																		<span class="portfolio-sorting-select-current-arrow"></span>
																	</div>
																	<ul>
																		<li class="default <?php echo $selected_orderby == 'default' ? 'portfolio-sorting-select-current' : ''; ?>"
																			data-orderby="<?php echo esc_attr($params['orderby']); ?>"
																			data-order="<?php echo esc_attr($params['order']); ?>"><?php echo esc_html($params['sorting_extended_text']); ?></li>
																		<li class="<?php echo $selected_orderby == 'title-asc' ? 'portfolio-sorting-select-current' : ''; ?>"
																			data-orderby="title" data-order="asc"><?php echo esc_html($params['sorting_extended_dropdown_title_text']); ?>
																		</li>
																		<li class="<?php echo $selected_orderby == 'title-desc' ? 'portfolio-sorting-select-current' : ''; ?>"
																			data-orderby="title" data-order="desc"><?php echo esc_html($params['sorting_extended_dropdown_title_desc_text']); ?>
																		</li>
																		<li class="<?php echo $selected_orderby == 'date-desc' ? 'portfolio-sorting-select-current' : ''; ?>"
																			data-orderby="date" data-order="desc"><?php echo esc_html($params['sorting_extended_dropdown_latest_text']); ?>
																		</li>
																		<li class="<?php echo $selected_orderby == 'date-asc' ? 'portfolio-sorting-select-current' : ''; ?>"
																			data-orderby="date" data-order="asc"><?php echo esc_html($params['sorting_extended_dropdown_oldest_text']); ?>
																		</li>
																	</ul>
																</div>
														<?php }
														}
													} ?>

													<?php if ($search_right) { ?>
														<span>&nbsp;</span>
														<form class="portfolio-search-filter<?php echo $params['filters_style'] != ' standard' ? ' mobile-visible' : '';
														echo $params['live_search'] ? ' live-search' : '';
														echo $params['search_reset_filters'] ? ' reset-filters' : '';
														echo $params['show_search_as'] == 'input' ? ' input-style' : ''; ?>"
														role="search" action="">
															<div class="portfolio-search-filter-form">
																<input type="search"
																	   placeholder="<?php echo esc_attr($params['filters_text_labels_search_text']); ?>"
																	   value="<?php echo esc_attr($search_current); ?>">
															</div>
															<div class="portfolio-search-filter-button"></div>
														</form>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
										<?php if ($params['filter_type'] == 'extended' && $params['with_filter']) {
											$selected_shown = true;
											include(locate_template(array('gem-templates/portfolios/selected-filters.php')));
										} ?>
									</div>
								<?php }
								if (!$selected_shown) { ?>
									<div class="portfolio-top-panel selected-only">
										<?php include(locate_template(array('gem-templates/portfolios/selected-filters.php'))); ?>
									</div>
								<?php } ?>

								<div class="row portfolio-row">
									<div class="portfolio-set clearfix" data-max-row-height="<?php echo floatval($params['metro_max_row_height']); ?>">
										<?php
										if ($params['layout'] == 'creative') {
											$creative_blog_schemes_list = [
												'6' => [
													'6a' => [
														'count' => 9,
														0 => 'squared',
													],
													'6b' => [
														'count' => 7,
														0 => 'squared',
														1 => 'horizontal',
														6 => 'horizontal',
													],
													'6c' => [
														'count' => 9,
														0 => 'horizontal',
														3 => 'horizontal',
														6 => 'horizontal',
													],
													'6d' => [
														'count' => 9,
														0 => 'horizontal',
														1 => 'horizontal',
														2 => 'horizontal',
													],
													'6e' => [
														'count' => 6,
														0 => 'squared',
														1 => 'squared',
													]
												],
												'5' => [
													'5a' => [
														'count' => 7,
														0 => 'squared',
													],
													'5b' => [
														'count' => 8,
														0 => 'horizontal',
														4 => 'horizontal',
													],
													'5c' => [
														'count' => 6,
														0 => 'horizontal',
														1 => 'horizontal',
														4 => 'horizontal',
														5 => 'horizontal',
													],
													'5d' => [
														'count' => 4,
														0 => 'squared',
														1 => 'vertical',
														2 => 'horizontal',
														3 => 'horizontal',
													]
												],
												'4' => [
													'4a' => [
														'count' => 5,
														0 => 'squared',
													],
													'4b' => [
														'count' => 4,
														0 => 'squared',
														1 => 'horizontal',
													],
													'4c' => [
														'count' => 4,
														0 => 'squared',
														1 => 'vertical',
													],
													'4d' => [
														'count' => 7,
														0 => 'vertical',
													],
													'4e' => [
														'count' => 4,
														0 => 'vertical',
														1 => 'vertical',
														2 => 'horizontal',
														3 => 'horizontal',
													],
													'4f' => [
														'count' => 6,
														0 => 'horizontal',
														5 => 'horizontal',
													]
												],
												'3' => [
													'3a' => [
														'count' => 4,
														0 => 'vertical',
														1 => 'vertical',
													],
													'3b' => [
														'count' => 4,
														1 => 'horizontal',
														2 => 'horizontal',
													],
													'3c' => [
														'count' => 5,
														0 => 'vertical',
													],
													'3d' => [
														'count' => 5,
														0 => 'horizontal',
													],
													'3e' => [
														'count' => 3,
														0 => 'squared',
													],
													'3f' => [
														'count' => 4,
														0 => 'horizontal',
														1 => 'vertical',
													],
													'3g' => [
														'count' => 4,
														0 => 'vertical',
														3 => 'horizontal',
													],
													'3h' => [
														'count' => 5,
														2 => 'vertical',
													]
												],
												'2' => [
													'2a' => [
														'count' => 5,
														0 => 'vertical',
													],
													'2b' => [
														'count' => 5,
														3 => 'vertical',
													],
													'2c' => [
														'count' => 4,
														0 => 'vertical',
														2 => 'vertical',
													],
													'2d' => [
														'count' => 4,
														0 => 'horizontal',
														1 => 'vertical',
													],
													'2e' => [
														'count' => 5,
														0 => 'horizontal',
													],
													'2f' => [
														'count' => 4,
														0 => 'horizontal',
														1 => 'horizontal',
													],
													'2g' => [
														'count' => 5,
														2 => 'horizontal',
													],
													'2h' => [
														'count' => 4,
														0 => 'horizontal',
														3 => 'horizontal',
													],
												]
											];
											$columns = $params['columns_desktop'] != '100%' ? str_replace("x", "", $params['columns_desktop']) : $params['columns_100'];
											$items_sizes = $creative_blog_schemes_list[$columns][$params['layout_scheme_' . $columns . 'x']];
											$items_count = $items_sizes['count'];
										}
										$i = 0;
										$eo_marker = false;
										if ($portfolio_loop->have_posts()) {
											while ($portfolio_loop->have_posts()) {
												$portfolio_loop->the_post();
												if ($params['columns_desktop'] == '1x') {
													echo thegem_portfolio_list_render_item(get_the_ID(), $params, $eo_marker);
												} else {
													$thegem_highlight_type_creative = null;
													if ($params['layout'] == 'creative') {
														$thegem_highlight_type_creative = 'disabled';
														$item_num = $i % $items_count;
														if (isset($items_sizes[$item_num])) {
															$thegem_highlight_type_creative = $items_sizes[$item_num];
														}
													}
													echo thegem_portfolio_render_item($params, $item_classes, $thegem_sizes, get_the_ID(), $thegem_highlight_type_creative);
													if ($params['layout'] == 'creative' && $i == 0) {
														echo thegem_portfolio_render_item($params, ['size-item'], $thegem_sizes);
													}
													$i++;
												}
												$eo_marker = !$eo_marker;
											}
										} else { ?>
											<div class="portfolio-item not-found">
												<div class="found-wrap">
													<div class="image-inner empty"></div>
													<div class="msg">
														<?php echo wp_kses($params['not_found_text'], 'post'); ?>
													</div>
												</div>
											</div>
										<?php } ?>

									</div><!-- .portflio-set -->
									<?php if ($params['layout'] !== 'creative' && $params['columns_desktop'] !== '1x'): ?>
										<div class="portfolio-item-size-container">
											<?php echo thegem_portfolio_render_item($params, $item_classes, $thegem_sizes); ?>
										</div>
									<?php endif; ?>
								</div><!-- .row-->

								<?php
								/** Pagination */
								if ($params['pagination'] == 'normal') { ?>
									<div class="portfolio-navigator gem-pagination"<?php if ($max_page < 2) { echo ' style="display:none;"'; } ?>>
										<a href="#" class="prev">
											<i class="default"></i>
										</a>
										<div class="pages"></div>
										<a href="#" class="next">
											<i class="default"></i>
										</a>
									</div>
								<?php } else if ($params['pagination'] == 'more' && $next_page_pagination > 0) { ?>
									<div class="portfolio-load-more">
										<div class="inner">
											<?php thegem_button(array_merge($params['button'], array('tag' => 'button')), 1); ?>
										</div>
									</div>
								<?php } else if ($params['pagination'] == 'scroll' && $next_page_pagination > 0) { ?>
									<div class="portfolio-scroll-pagination"></div>
								<?php } ?>

								<?php if ($params['with_filter'] && $params['filters_style'] == 'sidebar') { ?>
							</div>
						</div>
					<?php } ?>

				</div><!-- .full-width -->
			</div><!-- .portfolio-->
		</div><!-- .portfolio-preloader-wrapper-->

	<?php }

	thegem_templates_close_portfolio('gem_portfolio', ['name' => __('Portfolio', 'thegem')], $portfolio_loop->have_posts());
}
function thegem_portfolio_carousel($params) {

	$gap_size = isset($params['gaps_size']) && $params['gaps_size'] != '' ? round(intval($params['gaps_size'])) : 0;
	$gap_size_tablet = isset($params['gaps_size_tablet']) && $params['gaps_size_tablet'] != '' ? round(intval($params['gaps_size_tablet'])) : null;
	$gap_size_mobile = isset($params['gaps_size_mobile']) && $params['gaps_size_mobile'] != '' ? round(intval($params['gaps_size_mobile'])) : null;

	if (empty($params['columns_100']))
		$params['columns_100'] = 5;

	wp_enqueue_style('thegem-portfolio-carousel');
	wp_enqueue_script('thegem-portfolio-carousel');

	if ($params['loading_animation'] !== 'disabled') {
		wp_enqueue_style('thegem-animations');
		wp_enqueue_script('thegem-scroll-monitor');
		wp_enqueue_script('thegem-items-animations');
	}

	$single_post_id = thegem_templates_init_portfolio() ? thegem_templates_init_portfolio()->ID : get_the_ID();

	if ($params['exclude_type'] == 'current') {
		$params['exclude_portfolios'] = [$single_post_id];
	} else if ($params['exclude_type'] == 'term') {
		$params['exclude_portfolios'] = thegem_get_posts_query_section_exclude_ids($params['exclude_terms'], 'thegem_pf_item');
	} else {
		$params['exclude_portfolios'] = !empty($params['exclude_portfolios']) ? explode(',', $params['exclude_portfolios']) : [];
	}

	$taxonomy_filter = $portfolios_posts = [];

	if ($params['query_type'] == 'related') {

		$taxonomies = explode(',', $params['taxonomy_related']);
		if (!empty($taxonomies)) {
			foreach ($taxonomies as $tax) {
				$taxonomy_filter[$tax] = [];
				$tax_terms = get_the_terms($single_post_id, $tax);
				if (!empty($tax_terms) && !is_wp_error($tax_terms)) {
					foreach ($tax_terms as $term) {
						$taxonomy_filter[$tax][] = $term->slug;
					}
				}
			}
		}
		$params['related_tax_filter'] = $taxonomy_filter;

		if ($params['exclude_portfolios']) {
			$params['exclude_portfolios'][] = $single_post_id;
		} else {
			$params['exclude_portfolios'] = [$single_post_id];
		}
	} else {
		$params['source'] = explode(',', $params['source']);
		foreach ($params['source'] as $source) {
			if ($source == 'posts') {
				$params['content_portfolios_posts'] = explode(',', $params['content_portfolios_posts']);
				$portfolios_posts = $params['content_portfolios_posts'];
			} else if (!empty($params['content_portfolios_' . $source])) {
				$tax_terms = $params['content_portfolios_' . $source] = explode(',', $params['content_portfolios_' . $source]);
				if (!empty($tax_terms)) {
					$taxonomy_filter[$source] = $tax_terms;
				}
			}
		}
	}

	$grid_uid = $params['portfolio_uid'];
	$grid_uid_url = $grid_uid . '-';

	if (!isset($params['orderby']) || $params['orderby'] == 'default') {
		$params['orderby'] = 'menu_order ID';
	}

	if (!isset($params['order']) || $params['order'] == 'default') {
		$params['order'] = 'asc';
	}

	$taxonomy_filter_current = $taxonomy_filter;
	$categories_filter = [];
	if (isset($_GET[$grid_uid_url . 'category'])) {
		$categories_filter = explode(",", $_GET[$grid_uid_url . 'category']);
		$taxonomy_filter_current['thegem_portfolios'] = $categories_filter;
	}

	$style_uid = substr(md5(rand()), 0, 7);

	$params['caption_position'] = $params['display_titles'];

	if ($params['caption_position'] == 'hover') {
		$params['hover'] = $params['hover_hover'];
	} else if ($params['caption_position'] == 'image') {
		$params['hover'] = $params['hover_image'];
	}
	wp_enqueue_style('thegem-hovers-' . $params['hover']);

	$details_list = select_portfolio_details();
	$custom_fields_list = select_theme_options_custom_fields('portfolio');
	$meta_list = array_merge($details_list, $custom_fields_list);
	if (thegem_is_plugin_active('advanced-custom-fields/acf.php') || thegem_is_plugin_active('advanced-custom-fields-pro/acf.php')){
		foreach (thegem_cf_get_acf_plugin_groups() as $gr){
			$meta_list = array_merge($meta_list, thegem_cf_get_acf_plugin_fields_by_group($gr));
		}
	}
	$meta_list = array_flip($meta_list);

	if (!empty($params['image_ratio_default'])) {
		$params['image_aspect_ratio'] = 'custom';
		$params['image_ratio_custom'] = $params['image_ratio_default'];
	}

	$inlineStyles = '';
	$gridSelector = '.portfolio.portfolio-grid#style-' . $style_uid;
	$gridSelectorSkeleton = '.preloader#style-preloader-' . $style_uid;
	$itemSelector = $gridSelector . ' .portfolio-item';
	$captionSelector = $itemSelector . ' .caption';

	if (isset($gap_size)) {
		$inlineStyles .= $gridSelector . ':not(.inited) .portfolio-item, ' . $gridSelectorSkeleton . ' .portfolio-item { padding: calc(' . $gap_size . 'px/2) !important; }' .
			$gridSelector . ':not(.inited) .owl-stage, ' . $gridSelectorSkeleton . ' .skeleton-posts.portfolio-row { margin: calc(-' . $gap_size . 'px/2); }' .
			$gridSelector . ' .fullwidth-block:not(.no-paddings) { padding-left: ' . $gap_size . 'px; padding-right: ' . $gap_size . 'px; }' .
			$gridSelector . '.has-shadowed-items .owl-carousel .owl-stage-outer { padding: calc(' . $gap_size . 'px/2) !important; margin: calc(-' . $gap_size . 'px/2); }';
	}
	if (isset($gap_size_tablet)) {
		$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ':not(.inited) .portfolio-item, ' . $gridSelectorSkeleton . ' .portfolio-item { padding: calc(' . $gap_size_tablet . 'px/2) !important; }' .
			$gridSelector . ':not(.inited) .owl-stage, ' . $gridSelectorSkeleton . ' .skeleton-posts.portfolio-row { margin: calc(-' . $gap_size_tablet . 'px/2); }' .
			$gridSelector . ' .fullwidth-block:not(.no-paddings) { padding-left: ' . $gap_size_tablet . 'px; padding-right: ' . $gap_size_tablet . 'px; }' .
			$gridSelector . '.has-shadowed-items .owl-carousel .owl-stage-outer { padding: calc(' . $gap_size_tablet . 'px/2) !important; margin: calc(-' . $gap_size_tablet . 'px/2); }}';
	}
	if (isset($gap_size_mobile)) {
		$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ':not(.inited) .portfolio-item, ' . $gridSelectorSkeleton . ' .portfolio-item { padding: calc(' . $gap_size_mobile . 'px/2) !important; }' .
			$gridSelector . ':not(.inited) .owl-stage, ' . $gridSelectorSkeleton . ' .skeleton-posts.portfolio-row { margin: calc(-' . $gap_size_mobile . 'px/2); }' .
			$gridSelector . ' .fullwidth-block:not(.no-paddings) { padding-left: ' . $gap_size_mobile . 'px; padding-right: ' . $gap_size_mobile . 'px; }' .
			$gridSelector . '.has-shadowed-items .owl-carousel .owl-stage-outer { padding: calc(' . $gap_size_mobile . 'px/2) !important; margin: calc(-' . $gap_size_mobile . 'px/2); }}';
	}
	if (isset($params['image_size']) && $params['image_size'] == 'full' && !empty($params['image_ratio'])) {
		$inlineStyles .= $itemSelector . ':not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: ' . esc_attr($params['image_ratio']) . ' !important; height: auto; }';
	}
	if (isset($params['image_size']) && $params['image_size'] == 'default' && !empty($params['image_ratio_default'])) {
		$inlineStyles .= $itemSelector . ':not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: ' . esc_attr($params['image_ratio_default']) . ' !important; height: auto; }';
	}
	if (!empty($params['image_height']) && (!isset($params['image_size']) || $params['image_size'] == 'default')) {
		$inlineStyles .= $itemSelector . ':not(.double-item) .image-inner { height: ' . esc_attr($params['image_height']) . 'px !important; padding-bottom: 0 !important; aspect-ratio: initial !important; }';
		$inlineStyles .= $itemSelector . ':not(.double-item) .gem-simple-gallery .gem-gallery-item a { height: ' . esc_attr($params['image_height']) . 'px !important; }';
	}
	if (!empty($params['caption_container_alignment'])) {
		$inlineStyles .= $captionSelector . ' { text-align: ' . esc_attr($params['caption_container_alignment']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .caption-separator { margin-' . esc_attr($params['caption_container_alignment']) . ' : 0 !important; }';
		$inlineStyles .= $captionSelector . ' .portfolio-likes { text-align: -webkit-' . esc_attr($params['caption_container_alignment']) . ' !important; }';
	}
	if (!empty($params['background_color'])) {
		$inlineStyles .= $captionSelector . ' { background-color: ' . esc_attr($params['background_color']) . ' !important; }';
	}
	if (!empty($params['background_color_hover'])) {
		$inlineStyles .= $itemSelector . ':hover .caption { background-color: ' . esc_attr($params['background_color_hover']) . ' !important; }';
	}
	foreach (['top', 'bottom', 'left', 'right'] as $position) {
		if (isset($params['caption_padding_' . $position]) && $params['caption_padding_' . $position] != '') {
			$inlineStyles .= $captionSelector .' { padding-' . $position . ': ' . intval( $params['caption_padding_' . $position] ) . 'px !important; }';
		}
		if (isset($params['caption_padding_' . $position . '_tablet']) && $params['caption_padding_' . $position . '_tablet'] != '') {
			$inlineStyles .= '@media (max-width: 991px) { ' . $captionSelector .' { padding-' . $position . ': ' . intval( $params['caption_padding_' . $position . '_tablet'] ) . 'px !important; }}';
		}
		if (isset($params['caption_padding_' . $position . '_mobile']) && $params['caption_padding_' . $position . '_mobile'] != '') {
			$inlineStyles .= '@media (max-width: 767px) { ' . $captionSelector .' { padding-' . $position . ': ' . intval( $params['caption_padding_' . $position . '_mobile'] ) . 'px !important; }}';
		}
	}
	if (!empty($params['border_color'])) {
		$inlineStyles .= $captionSelector . ' { border-bottom-color: ' . esc_attr($params['border_color']) . ' !important; }';
	}
	if (!empty($params['title_transform'])) {
		$inlineStyles .= $captionSelector . ' .title span { text-transform: ' . esc_attr($params['title_transform']) . ' !important; }';
	}
	if (isset($params['title_letter_spacing'])) {
		$inlineStyles .= $captionSelector . ' .title span { letter-spacing: ' . esc_attr($params['title_letter_spacing']) . 'px !important; }';
	}
	if (!empty($params['title_color'])) {
		$inlineStyles .= $captionSelector . ' .title { color: ' . esc_attr($params['title_color']) . ' !important; }';
	}
	if (!empty($params['title_color_hover'])) {
		$inlineStyles .= $itemSelector . ':hover .caption .title { color: ' . esc_attr($params['title_color_hover']) . ' !important; }';
	}
	if (!empty($params['desc_color'])) {
		$inlineStyles .= $captionSelector . ' .subtitle { color: ' . esc_attr($params['desc_color']) . ' !important; }';
	}
	if (!empty($params['desc_color_hover'])) {
		$inlineStyles .= $itemSelector . ':hover .caption .subtitle { color: ' . esc_attr($params['desc_color_hover']) . ' !important; }';
	}
	if (!empty($params['separator_color'])) {
		$inlineStyles .= $captionSelector . ' .caption-separator { background-color: ' . esc_attr($params['separator_color']) . ' !important; }';
	}
	$truncate_titles = !empty($params['truncate_titles']) ? $params['truncate_titles'] : '2';
	$inlineStyles .= $captionSelector . ' .title span { white-space: initial; max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($truncate_titles) . '; line-clamp: ' . esc_attr($truncate_titles) . '; -webkit-box-orient: vertical; }';
	if (!empty($params['truncate_info'])) {
		$inlineStyles .= $captionSelector . ' .info { white-space: initial; max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($params['truncate_info']) . '; line-clamp: ' . esc_attr($params['truncate_info']) . '; -webkit-box-orient: vertical; }';
	}
	$truncate_description = !empty($params['truncate_description']) ? $params['truncate_description'] : '2';
	$inlineStyles .= $captionSelector . ' .subtitle { max-height: initial !important; }';
	$inlineStyles .= $captionSelector . ' .subtitle span { white-space: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($truncate_description) . '; line-clamp: ' . esc_attr($truncate_description) . '; -webkit-box-orient: vertical; }';
	$inlineStyles .= $captionSelector . ' .subtitle a, ' . $itemSelector . ' .caption .subtitle p { white-space: initial; overflow: initial; }';
	if (!empty($params['categories_color'])) {
		$inlineStyles .= $captionSelector . ' .info a { color: ' . esc_attr($params['categories_color']) . '; }';
	}
	if (!empty($params['categories_color_hover'])) {
		$inlineStyles .= $captionSelector . ' .info a:hover { color: ' . esc_attr($params['categories_color_hover']) . '; }';
	}
	if (!empty($params['date_color'])) {
		$inlineStyles .= $captionSelector . ' .info { color: ' . esc_attr($params['date_color']) . '; }';
	}
	if (!empty($params['zoom_overlay_color'])) {
		$inlineStyles .= $gridSelector . '.hover-zoom-overlay .portfolio-item .image .overlay:before { background-color: ' . esc_attr($params['zoom_overlay_color']) . '; }';
	}
	if (isset($params['navigation_arrows_icon_color_normal']) && $params['navigation_arrows_icon_color_normal'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev div, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next div { color: " . $params['navigation_arrows_icon_color_normal'] . " }";
	}
	if (isset($params['navigation_arrows_icon_color_hover']) && $params['navigation_arrows_icon_color_hover'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev:hover div, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next:hover div { color: " . $params['navigation_arrows_icon_color_hover'] . " }";
	}
	if (isset($params['navigation_arrows_border_width']) && $params['navigation_arrows_border_width'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next { border-width: " . $params['navigation_arrows_border_width'] . "px }";
	}
	if (isset($params['navigation_arrows_border_radius']) && $params['navigation_arrows_border_radius'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next { border-radius: " . $params['navigation_arrows_border_radius'] . "px }";
	}
	if (isset($params['navigation_arrows_border_color_normal']) && $params['navigation_arrows_border_color_normal'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next { border-color: " . $params['navigation_arrows_border_color_normal'] . " }";
	}
	if (isset($params['navigation_arrows_border_color_hover']) && $params['navigation_arrows_border_color_hover'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev:hover, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next:hover { border-color: " . $params['navigation_arrows_border_color_hover'] . " }";
	}
	if (isset($params['navigation_arrows_background_color_normal']) && $params['navigation_arrows_background_color_normal'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev div.position-on, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next div.position-on { background-color: " . $params['navigation_arrows_background_color_normal'] . " }";
	}
	if (isset($params['navigation_arrows_background_color_hover']) && $params['navigation_arrows_background_color_hover'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev:hover div.position-on, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next:hover div.position-on { background-color: " . $params['navigation_arrows_background_color_hover'] . " }";
	}
	if (isset($params['navigation_arrows_spacing']) && $params['navigation_arrows_spacing'] != '') {
		$inlineStyles .= $gridSelector . ".arrows-position-outside:not(.prevent-arrows-outside) .extended-carousel-item .owl-nav .owl-prev { transform: translate(calc(-100% - " . $params['navigation_arrows_spacing'] . "px), -50%); }";
		$inlineStyles .= $gridSelector . ".arrows-position-outside:not(.prevent-arrows-outside) .extended-carousel-item .owl-nav .owl-next { transform: translate(calc(100% + " . $params['navigation_arrows_spacing'] . "px), -50%); }";
		$inlineStyles .= $gridSelector . ".arrows-position-outside.prevent-arrows-outside .extended-carousel-item .owl-nav .owl-prev, " .
			$gridSelector . ".arrows-position-on .extended-carousel-item .owl-nav .owl-prev { left: " . $params['navigation_arrows_spacing'] . "px; }";
		$inlineStyles .= $gridSelector . ".arrows-position-outside.prevent-arrows-outside .extended-carousel-item .owl-nav .owl-next, " .
			$gridSelector . ".arrows-position-on .extended-carousel-item .owl-nav .owl-next { right: " . $params['navigation_arrows_spacing'] . "px; }";
	}
	if (isset($params['navigation_top_spacing']) && $params['navigation_top_spacing'] != '') {
		$value = $params['navigation_top_spacing'];
		$unit = 'px';
		$last_result = substr($value, -1);
		if ($last_result == '%') {
			$value = str_replace('%', '', $value);
			$unit = $last_result;
		}
		$inlineStyles .= $gridSelector . "  .extended-carousel-item .owl-nav .owl-prev, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next { top: " . $value . $unit . " !important; }";
	}
	if (isset($params['navigation_dots_spacing']) && $params['navigation_dots_spacing'] != '') {
		$inlineStyles .= $gridSelector . " .owl-dots { margin-top: " . $params['navigation_dots_spacing'] . "px }";
	}
	if (isset($params['navigation_dots_border_width']) && $params['navigation_dots_border_width'] != '') {
		$inlineStyles .= $gridSelector . " .owl-dots .owl-dot span { border-width: " . $params['navigation_dots_border_width'] . "px }";
	}
	if (isset($params['navigation_dots_border_color_normal']) && $params['navigation_dots_border_color_normal'] != '') {
		$inlineStyles .= $gridSelector . " .owl-dots .owl-dot span { border-color: " . $params['navigation_dots_border_color_normal'] . " }";
	}
	if (isset($params['navigation_dots_border_color_active']) && $params['navigation_dots_border_color_active'] != '') {
		$inlineStyles .= $gridSelector . " .owl-dots .owl-dot.active span { border-color: " . $params['navigation_dots_border_color_active'] . " }";
	}
	if (isset($params['navigation_dots_background_color_normal']) && $params['navigation_dots_background_color_normal'] != '') {
		$inlineStyles .= $gridSelector . " .owl-dots .owl-dot span { background-color: " . $params['navigation_dots_background_color_normal'] . " }";
	}
	if (isset($params['navigation_dots_background_color_active']) && $params['navigation_dots_background_color_active'] != '') {
		$inlineStyles .= $gridSelector . " .owl-dots .owl-dot.active span { background-color: " . $params['navigation_dots_background_color_active'] . " }";
	}

	if (!empty($inlineStyles)) {
		echo '<style>'.$inlineStyles.'</style>';
	}

	echo thegem_portfolio_news_render_styles($params, $gridSelector);

	$page = 1;

	if (isset($_GET[$grid_uid_url . 'page'])) {
		$page = $_GET[$grid_uid_url . 'page'];
	}

	$items_per_page = $params['items_per_page'] ? intval($params['items_per_page']) : 8;

	if (!empty($_GET[$grid_uid_url . 'orderby'])) {
		$orderby = $_GET[$grid_uid_url . 'orderby'];
	} else {
		$orderby = $params['orderby'];
	}

	if (!empty($_GET[$grid_uid_url . 'order'])) {
		$order = $_GET[$grid_uid_url . 'order'];
	} else {
		$order = $params['order'];
	}

	$portfolios_filters_tax_url = $portfolios_filters_meta_url = $meta_filter_current = [];
	foreach($_GET as $key => $value) {
		if (strpos($key, $grid_uid_url . 'filter_tax_') === 0) {
			$attr = str_replace($grid_uid_url . 'filter_tax_', '', $key);
			$portfolios_filters_tax_url['tax_' . $attr] = $taxonomy_filter_current[$attr] = explode(",", $value);
		} else if (strpos($key, $grid_uid_url . 'filter_meta_') === 0) {
			$attr = str_replace($grid_uid_url . 'filter_meta_', '', $key);
			$portfolios_filters_meta_url['meta_' . $attr] = $meta_filter_current[$attr] = explode(",", $value);
		}
	}
	$attributes_filter = array_merge($portfolios_filters_tax_url, $portfolios_filters_meta_url);
	if (empty($attributes_filter)) { $attributes_filter = null; }

	$search_current = null;
	if (!empty($_GET[$grid_uid_url . 's'])) {
		$search_current = $_GET[$grid_uid_url . 's'];
	}

	$portfolio_loop = thegem_get_portfolio_posts($portfolios_posts, $taxonomy_filter_current, $meta_filter_current, $search_current, 'content', $page, $items_per_page, $orderby, $order, intval($params['offset']), $params['exclude_portfolios']);

	if ($portfolio_loop && $portfolio_loop->have_posts() || !empty($categories_filter) || !empty($attributes_filter) || !empty($search_current)) {
		$params['thegem_elementor_preset'] = 'alternative';
		$params['layout'] = 'justified';
		$params['ignore_highlights'] = '1';

		$item_classes = get_thegem_portfolio_render_item_classes($params);
		$item_classes[] = 'owl-item';
		$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params);

		if (!$params['disable_preloader']) {
			if ($params['columns_desktop'] == '100%' || !$params['skeleton_loader']) {
				$spin_class = 'preloader-spin';
				if ($params['ajax_preloader_type'] == 'minimal') {
					$spin_class = 'preloader-spin-new';
				}
				echo apply_filters('thegem_portfolio_preloader_html', '<div id="style-preloader-' . $style_uid . '" class="preloader save-space"><div class="' . $spin_class . '"></div></div>');
			} else { ?>
				<div id="style-preloader-<?php echo esc_attr($style_uid); ?>" class="preloader skeleton-carousel">
					<div class="skeleton">
						<div class="skeleton-posts portfolio-row">
							<?php for ($x = 0; $x < $portfolio_loop->post_count; $x++) {
								echo thegem_portfolio_render_item($params, $item_classes);
							} ?>
						</div>
					</div>
				</div>
			<?php }
		} ?>
		<div class="portfolio-preloader-wrapper">

			<?php
			if ($params['display_titles'] == 'hover') {
				$title_on = 'hover';
			} else {
				$title_on = 'page';
			}
			$portfolio_classes = array(
				'portfolio portfolio-grid extended-portfolio-grid extended-portfolio-carousel extended-carousel-grid no-padding disable-isotope portfolio-style-justified',
				'portfolio-style-' . $params['layout'],
				'background-style-' . $params['background_style'],
				'hover-' . esc_attr($params['hover']),
				'title-on-' . $title_on,
				'hover-elements-size-' . $params['hover_elements_size'],
				'version-' . $params['thegem_elementor_preset'],
				'arrows-position-' . $params['arrows_navigation_position'],
				($params['caption_position'] ? 'caption-position-' . $params['caption_position'] : ''),
				($params['loading_animation'] !== 'disabled' ? 'loading-animation item-animation-' . $params['loading_animation'] : ''),
				($params['loading_animation'] !== 'disabled' && $params['loading_animation_mobile'] == '1' ? 'enable-animation-mobile' : ''),
				($gap_size == 0 ? 'no-gaps' : ''),
				($params['enable_shadow'] ? 'has-shadowed-items' : ''),
				($params['shadowed_container'] == '1' ? 'shadowed-container' : ''),
				($params['columns_desktop'] == '100%' ? 'fullwidth-columns fullwidth-columns-' . intval($params['columns_100']) : ''),
				($params['columns_desktop'] == '100%' && $params['gaps_size'] < 24 ? 'prevent-arrows-outside' : ''),
				($params['display_titles'] == 'image' && $params['hover'] == 'gradient' ? 'hover-gradient-title' : ''),
				($params['display_titles'] == 'image' && $params['hover'] == 'circular' ? 'hover-circular-title' : ''),
				($params['display_titles'] == 'hover' || $params['display_titles'] == 'image' ? 'hover-title' : ''),
				($params['columns_desktop'] != '100%' ? 'columns-' . str_replace("x", "", $params['columns_desktop']) : ''),
				(isset($params['columns_tablet']) ? 'columns-tablet-' . str_replace("x", "", $params['columns_tablet']) : ''),
				(isset($params['columns_mobile']) ? 'columns-mobile-' . str_replace("x", "", $params['columns_mobile']) : ''),
				($params['arrows_navigation_visibility'] == 'hover' ? 'arrows-hover' : ''),
				((isset($params['image_size']) && (($params['image_size'] == 'full' && empty($params['image_ratio'])) || !in_array($params['image_size'], ['full', 'default']))) ? 'full-image' : ''),
				($params['ajax_preloader_type'] == 'minimal' ? 'minimal-preloader' : ''),
			);

			$portfolio_classes = apply_filters('portfolio_classes_filter', $portfolio_classes); ?>

			<div class="<?php echo implode(' ', $portfolio_classes); ?>"
				 id="style-<?php echo esc_attr($style_uid); ?>"
				 data-style-uid="<?php echo esc_attr($style_uid); ?>"
				 data-portfolio-uid="<?php echo esc_attr($grid_uid); ?>"
				 data-columns-mobile="<?php echo esc_attr(str_replace("x", "", $params['columns_mobile'])); ?>"
				 data-columns-tablet="<?php echo esc_attr(str_replace("x", "", $params['columns_tablet'])); ?>"
				 data-columns-desktop="<?php echo $params['columns_desktop'] != '100%' ? esc_attr(str_replace("x", "", $params['columns_desktop'])) : esc_attr($params['columns_100']); ?>"
				 data-margin-mobile="<?php echo esc_attr($gap_size_mobile); ?>"
				 data-margin-tablet="<?php echo esc_attr($gap_size_tablet); ?>"
				 data-margin-desktop="<?php echo esc_attr($gap_size); ?>"
				 data-hover="<?php echo esc_attr($params['hover']); ?>"
				 data-dots="<?php echo esc_attr($params['show_dots_navigation']); ?>"
				 data-arrows="<?php echo esc_attr($params['show_arrows_navigation']); ?>"
				 data-loop="<?php echo esc_attr($params['slider_loop']); ?>"
				 data-sliding-animation="<?php echo esc_attr($params['sliding_animation']); ?>"
				 data-autoscroll-speed="<?php echo $params['autoscroll'] ? esc_attr($params['autoscroll_speed']) : '0'; ?>">

				<div class="portfolio-row-outer <?php if ($params['columns_desktop'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
					<div class="portfolio-row clearfix">
						<div class="portfolio-set">
							<div class="extended-carousel-wrap">
								<div class="extended-carousel-item owl-carousel owl-theme owl-loaded">
									<div class="owl-stage-outer">
										<div class="owl-stage">
											<?php
											if ($portfolio_loop->have_posts()) {
												while ($portfolio_loop->have_posts()) {
													$portfolio_loop->the_post();
													echo thegem_portfolio_render_item($params, $item_classes, $thegem_sizes, get_the_ID());
												}
											} else { ?>
												<div class="portfolio-item not-found">
													<div class="found-wrap">
														<div class="image-inner empty"></div>
														<div class="msg">
															<?php echo wp_kses($params['not_found_text'], 'post'); ?>
														</div>
													</div>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>

						</div><!-- .portflio-set -->
					</div><!-- .row-->
					<?php if ($params['show_arrows_navigation']): ?>
						<div class="slider-prev-icon position-<?php echo $params['arrows_navigation_position']; ?>">
							<i class="default"></i>
						</div>
						<div class="slider-next-icon position-<?php echo $params['arrows_navigation_position']; ?>">
							<i class="default"></i>
						</div>
					<?php endif; ?>

				</div><!-- .full-width -->
			</div><!-- .portfolio-->
		</div><!-- .portfolio-preloader-wrapper-->

	<?php }

	thegem_templates_close_portfolio('gem_portfolio', ['name' => __('Portfolio', 'thegem')], $portfolio_loop->have_posts());

	if (thegem_is_plugin_active('js_composer/js_composer.php')) {
		global $vc_manager;
		if ($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode() == 'page_editable') { ?>
			<script type="text/javascript">
				(function ($) {
					setTimeout(function () {
						$('#style-<?php echo esc_attr($style_uid); ?>.extended-portfolio-carousel').initPortfolioCarousels();
					}, 1000);
				})(jQuery);
			</script>
		<?php }
	}
}

function thegem_get_portfolio_posts($portfolios_posts, $portfolios_filters_tax, $portfolios_filters_meta, $search = null, $search_by = 'content', $page = 1, $ppp = -1, $orderby = 'menu_order ID', $order = 'ASC', $offset = 0, $exclude = false, $show_all = false) {

	$tax_query = $meta_query = [];

	if (!empty($portfolios_filters_tax)) {
		foreach ($portfolios_filters_tax as $tax => $tax_arr) {
			if (!empty($tax_arr) && !in_array('0', $tax_arr)) {
				$query_arr = array(
					'taxonomy' => $tax,
					'field' => 'slug',
					'terms' => $tax_arr,
				);
			} else {
				$query_arr = array(
					'taxonomy' => $tax,
					'operator' => 'EXISTS'
				);
			}
			$tax_query[] = $query_arr;
		}
	}

	if (!empty($portfolios_filters_meta)) {
		foreach ($portfolios_filters_meta as $meta => $meta_arr) {
			if (!empty($meta_arr)) {
				if (strpos($meta, "__range") > 0) {
					$meta = str_replace("__range","", $meta);
					$query_arr = array(
						'key' => $meta,
						'value' => $meta_arr,
						'compare'   => 'BETWEEN',
						'type'   => 'NUMERIC',
					);
				} else if (strpos($meta, "__check") > 0) {
					$meta = str_replace("__check","", $meta);
					$check_meta_query = array(
						'relation' => 'OR',
					);
					foreach ($meta_arr as $value) {
						$check_meta_query[] = array(
							'key' => $meta,
							'value' => sprintf('"%s"', $value),
							'compare' => 'LIKE',
						);
					}
					$query_arr = $check_meta_query;
				} else {
					$query_arr = array(
						'key' => $meta,
						'value' => $meta_arr,
						'compare' => 'IN',
					);
				}
				$meta_query[] = $query_arr;
			}
		}
	}

	if (!empty($search) && $search_by != 'content') {
		$search_meta_query = array(
			'relation' => 'OR',
		);
		foreach ($search_by as $key) {
			$search_meta_query[] = array(
				'key' => $key,
				'value' => $search,
				'compare' => 'LIKE'
			);
		}
		$meta_query[] = $search_meta_query;
	}

	$args = array(
		'post_type' => 'thegem_pf_item',
		'post_status' => 'publish',
		'posts_per_page' => $ppp,
	);

	if ($orderby == 'default') {
		$args['orderby'] = 'menu_order ID';
	} else if (!empty($orderby)) {
		$args['orderby'] = $orderby;
		if (!in_array($orderby, ['date', 'id', 'author', 'title', 'name', 'modified', 'comment_count', 'rand', 'menu_order ID'])) {
			if (strpos($orderby, 'num_') === 0) {
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = str_replace('num_', '', $orderby);
			} else {
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = $orderby;
			}
		}
	}

	if ($orderby == 'default') {
		$args['order'] = 'ASC';
	} else if (!empty($order)) {
		$args['order'] = $order;
	}

	if (!empty($tax_query)) {
		$args['tax_query'] = $tax_query;
	}

	if (!empty($meta_query)) {
		$args['meta_query'] = $meta_query;
	}

	if (!empty($portfolios_posts)) {
		$args['post__in'] = $portfolios_posts;
	}

	if (!empty($offset) || $show_all) {
		$args['offset'] = $ppp * ($page - 1) + $offset;
	} else {
		$args['paged'] = $page;
	}

	if ($show_all) {
		$args['posts_per_page'] = 999;
	}

	if (!empty($exclude)) {
		$args['post__not_in'] = $exclude;
	}

	if (!empty($search) && $search_by == 'content') {
		$args['s'] = $search;
	}

	$portfolio_loop = new WP_Query($args);

	return $portfolio_loop;
}

function thegem_portfolio_list_render_item($post_id, $params, $eo_marker = false) {
	$slugs = wp_get_object_terms($post_id, 'thegem_portfolios', array('fields' => 'slugs'));

	$thegem_classes = array('portfolio-item');
	$thegem_classes = array_merge($thegem_classes, $slugs);

	if ($eo_marker) {
		$thegem_classes[] = 'item-even';
	}

	$thegem_image_classes = array('image');
	$thegem_caption_classes = array('caption');

	$thegem_portfolio_item_data = thegem_get_sanitize_pf_item_data(get_the_ID());
	$thegem_title_data = thegem_get_sanitize_page_title_data(get_the_ID());

	if (empty($thegem_portfolio_item_data['types']))
		$thegem_portfolio_item_data['types'] = array();

	$thegem_classes = array_merge($thegem_classes, array('col-xs-12'));

	if($params['display_titles'] == 'page' && $params['hover'] != 'gradient' && $params['hover'] != 'circular') {
		if ($params['layout_version'] == 'fullwidth') {
			$thegem_image_classes = array_merge($thegem_image_classes, array('col-md-8', 'col-xs-12'));
			$thegem_caption_classes = array_merge($thegem_caption_classes, array('col-md-4', 'col-xs-12'));
			if ($params['caption_position'] == 'left') {
				$thegem_image_classes = array_merge($thegem_image_classes, array('col-md-push-4'));
				$thegem_caption_classes = array_merge($thegem_caption_classes, array('col-md-pull-8'));
			}
		} else {
			$thegem_image_classes = array_merge($thegem_image_classes, array('col-md-7', 'col-xs-12'));
			$thegem_caption_classes = array_merge($thegem_caption_classes, array('col-md-5', 'col-xs-12'));
			if ($params['caption_position'] == 'left') {
				$thegem_image_classes = array_merge($thegem_image_classes, array('col-md-push-5'));
				$thegem_caption_classes = array_merge($thegem_caption_classes, array('col-md-pull-7'));
			}
		}
	}

	$thegem_size = 'thegem-portfolio-1x';
	if($params['display_titles'] == 'hover' || $params['hover'] == 'gradient' || $params['hover'] == 'circular') {
		$thegem_size .= '-hover';
	} else {
		$thegem_size .= $params['layout_version'] == 'sidebar' ? '-sidebar' : '';
	}

	$thegem_small_image_url = thegem_generate_thumbnail_src(get_post_thumbnail_id(), $thegem_size);
	$thegem_large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
	$thegem_self_video = '';

	$thegem_bottom_line = false;
	$thegem_portfolio_button_link = '';
	if ($thegem_portfolio_item_data['project_link'] || !$params['disable_socials']) {
		$thegem_bottom_line = true;
	}

	if ($params['loading_animation'] !== 'disabled') {
		$thegem_classes[] = 'item-animations-not-inited';
	}

	$gap_size = round(intval($params['gaps_size']) / 2);

	include(locate_template(array('gem-templates/portfolios/content-portfolio-item-1x.php')));
}

function portfolio_grid_more_callback() {
	$settings = isset($_POST['data']) ? json_decode(stripslashes($_POST['data']), true) : array();
	ob_start();
	$response = array('status' => 'success');

	$portfolios_posts = $portfolios_filters_tax = $portfolios_filters_meta = [];

	if ($settings['query_type'] == 'related') {
		$portfolios_filters_tax = $settings['related_tax_filter'];
	} else {
		foreach ($settings['source'] as $source) {
			if ($source == 'posts') {
				$portfolios_posts = $settings['content_portfolios_posts'];
			} else {
				$tax_terms = $settings['content_portfolios_' . $source];
				if (!empty($tax_terms)) {
					$portfolios_filters_tax[$source] = $tax_terms;
				}
			}
		}
	}

	if (!empty($settings['has_categories_filter']) && !empty($settings['content_portfolios_cat'])) {
		$portfolios_filters_tax['thegem_portfolios'] = $settings['content_portfolios_cat'];
	}

	if (!empty($settings['has_attributes_filter'])) {
		$attrs = explode(",", $settings['filters_attr']);
		foreach ($attrs as $attr) {
			$values = explode(",", $settings['filters_attr_val_' . $attr]);
			if (!empty($values)) {
				if (strpos($attr, "tax_") === 0) {
					$portfolios_filters_tax[str_replace("tax_","", $attr)] = $values;
				} else {
					$portfolios_filters_meta[str_replace("meta_","", $attr)] = $values;
				}
			}
		}
	}

	$search = isset($settings['portfolio_search_filter']) && $settings['portfolio_search_filter'] != '' ? $settings['portfolio_search_filter'] : null;

	$page = isset($settings['more_page']) ? intval($settings['more_page']) : 1;
	if ($page == 0)
		$page = 1;

	$show_all = $settings['load_more_show_all'] && $page != 1;
	$items_per_page = $settings['items_per_page'] ? intval($settings['items_per_page']) : 8;

	$portfolio_loop = thegem_get_portfolio_posts($portfolios_posts, $portfolios_filters_tax, $portfolios_filters_meta, $search, $settings['search_by'], $page, $items_per_page, $settings['orderby'], $settings['order'], intval($settings['offset']), $settings['exclude_portfolios'], $show_all);

	$max_page = ceil(($portfolio_loop->found_posts - intval($settings['offset'])) / $items_per_page);
	$next_page = $max_page > $page ? $page + 1 : 0;
	if ($show_all) {
		$next_page = 0;
	}

	if ($portfolio_loop->have_posts()) {

		$item_classes = get_thegem_portfolio_render_item_classes($settings);
		$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($settings); ?>

		<div data-page="<?php echo esc_attr($page); ?>" data-next-page="<?php echo esc_attr($next_page); ?>" data-pages-count="<?php echo esc_attr($portfolio_loop->max_num_pages); ?>">
			<?php
			if ($settings['layout'] == 'creative') {
				$creative_blog_schemes_list = [
					'6' => [
						'6a' => [
							'count' => 9,
							0 => 'squared',
						],
						'6b' => [
							'count' => 7,
							0 => 'squared',
							1 => 'horizontal',
							6 => 'horizontal',
						],
						'6c' => [
							'count' => 9,
							0 => 'horizontal',
							3 => 'horizontal',
							6 => 'horizontal',
						],
						'6d' => [
							'count' => 9,
							0 => 'horizontal',
							1 => 'horizontal',
							2 => 'horizontal',
						],
						'6e' => [
							'count' => 6,
							0 => 'squared',
							1 => 'squared',
						]
					],
					'5' => [
						'5a' => [
							'count' => 7,
							0 => 'squared',
						],
						'5b' => [
							'count' => 8,
							0 => 'horizontal',
							4 => 'horizontal',
						],
						'5c' => [
							'count' => 6,
							0 => 'horizontal',
							1 => 'horizontal',
							4 => 'horizontal',
							5 => 'horizontal',
						],
						'5d' => [
							'count' => 4,
							0 => 'squared',
							1 => 'vertical',
							2 => 'horizontal',
							3 => 'horizontal',
						]
					],
					'4' => [
						'4a' => [
							'count' => 5,
							0 => 'squared',
						],
						'4b' => [
							'count' => 4,
							0 => 'squared',
							1 => 'horizontal',
						],
						'4c' => [
							'count' => 4,
							0 => 'squared',
							1 => 'vertical',
						],
						'4d' => [
							'count' => 7,
							0 => 'vertical',
						],
						'4e' => [
							'count' => 4,
							0 => 'vertical',
							1 => 'vertical',
							2 => 'horizontal',
							3 => 'horizontal',
						],
						'4f' => [
							'count' => 6,
							0 => 'horizontal',
							5 => 'horizontal',
						]
					],
					'3' => [
						'3a' => [
							'count' => 4,
							0 => 'vertical',
							1 => 'vertical',
						],
						'3b' => [
							'count' => 4,
							1 => 'horizontal',
							2 => 'horizontal',
						],
						'3c' => [
							'count' => 5,
							0 => 'vertical',
						],
						'3d' => [
							'count' => 5,
							0 => 'horizontal',
						],
						'3e' => [
							'count' => 3,
							0 => 'squared',
						],
						'3f' => [
							'count' => 4,
							0 => 'horizontal',
							1 => 'vertical',
						],
						'3g' => [
							'count' => 4,
							0 => 'vertical',
							3 => 'horizontal',
						],
						'3h' => [
							'count' => 5,
							2 => 'vertical',
						]
					],
					'2' => [
						'2a' => [
							'count' => 5,
							0 => 'vertical',
						],
						'2b' => [
							'count' => 5,
							3 => 'vertical',
						],
						'2c' => [
							'count' => 4,
							0 => 'vertical',
							2 => 'vertical',
						],
						'2d' => [
							'count' => 4,
							0 => 'horizontal',
							1 => 'vertical',
						],
						'2e' => [
							'count' => 5,
							0 => 'horizontal',
						],
						'2f' => [
							'count' => 4,
							0 => 'horizontal',
							1 => 'horizontal',
						],
						'2g' => [
							'count' => 5,
							2 => 'horizontal',
						],
						'2h' => [
							'count' => 4,
							0 => 'horizontal',
							3 => 'horizontal',
						],
					]
				];
				$columns = $settings['columns_desktop'] != '100%' ? str_replace("x", "", $settings['columns_desktop']) : $settings['columns_100'];
				$items_sizes = $creative_blog_schemes_list[$columns][$settings['layout_scheme_' . $columns . 'x']];
				$items_count = $items_sizes['count'];
			}
			$i = 0;
			$eo_marker = false;
			while ($portfolio_loop->have_posts()) {
				$portfolio_loop->the_post();
				if ($settings['columns_desktop'] == '1x') {
					echo thegem_portfolio_list_render_item(get_the_ID(), $settings, $eo_marker);
				} else {
					$thegem_highlight_type_creative = null;
					if ($settings['layout'] == 'creative') {
						$thegem_highlight_type_creative = 'disabled';
						$item_num = $i % $items_count;
						if (isset($items_sizes[$item_num])) {
							$thegem_highlight_type_creative = $items_sizes[$item_num];
						}
					}
					echo thegem_portfolio_render_item($settings, $item_classes, $thegem_sizes, get_the_ID(), $thegem_highlight_type_creative);
					if ($settings['layout'] == 'creative' && $i == 0) {
						echo thegem_portfolio_render_item($settings, ['size-item'], $thegem_sizes);
					}
					$i++;
				}
				$eo_marker = !$eo_marker;
			} ?>
		</div>
	<?php } else { ?>
		<div data-page="1" data-next-page="0" data-pages-count="1">
			<div class="portfolio-item not-found">
				<div class="found-wrap">
					<div class="image-inner empty"></div>
					<div class="msg">
						<?php echo wp_kses($settings['not_found_text'], 'post'); ?>
					</div>
				</div>
			</div>
		</div>
	<?php }

	$response['html'] = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	$response = json_encode($response);
	header("Content-Type: application/json");
	echo $response;
	exit;
}
add_action('wp_ajax_portfolio_grid_load_more', 'portfolio_grid_more_callback');
add_action('wp_ajax_nopriv_portfolio_grid_load_more', 'portfolio_grid_more_callback');

if (!function_exists('thegem_portfolio_news_render_styles')) {
	function thegem_portfolio_news_render_styles($params, $wrapper, $skeleton = false) {

		$style = "<style>";

		if (!empty($params['filter_buttons_position'])) {
			if ($params['filter_buttons_position'] == 'default') {
				if ($params['filter_type'] == 'default' && !$params['sorting'] && !$params['show_search']) {
					$style .= $wrapper . " .portfolio-filters { text-align: center; }";
				}
			} else {
				$style .= $wrapper . " .portfolio-filters { text-align: " . $params['filter_buttons_position'] . " }";
			}
		}

		if (isset($params['default_filter_text_color_normal']) && $params['default_filter_text_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-top-panel.filter-type-default .portfolio-filters a { color: " . $params['default_filter_text_color_normal'] . " }";
		}

		if (isset($params['default_filter_text_color_hover']) && $params['default_filter_text_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-top-panel.filter-type-default .portfolio-filters a:hover { color: " . $params['default_filter_text_color_hover'] . " }";
		}

		if (isset($params['default_filter_text_color_active']) && $params['default_filter_text_color_active'] != '') {
			$style .= $wrapper . " .portfolio-top-panel.filter-type-default .portfolio-filters a.active { color: " . $params['default_filter_text_color_active'] . " }";
		}

		if (isset($params['default_filter_background_color_normal']) && $params['default_filter_background_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-top-panel.filter-type-default .portfolio-filters a { background-color: " . $params['default_filter_background_color_normal'] . " }";
		}

		if (isset($params['default_filter_background_color_hover']) && $params['default_filter_background_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-top-panel.filter-type-default .portfolio-filters a:hover { background-color: " . $params['default_filter_background_color_hover'] . " }";
		}

		if (isset($params['default_filter_background_color_active']) && $params['default_filter_background_color_active'] != '') {
			$style .= $wrapper . " .portfolio-top-panel.filter-type-default .portfolio-filters a.active { background-color: " . $params['default_filter_background_color_active'] . " }";
		}

		if (isset($params['filter_buttons_standard_color']) && $params['filter_buttons_standard_color'] != '') {
			$style .= $wrapper . " .portfolio-filters-list.style-standard .portfolio-filter-item .name," .
				$wrapper . " .portfolio-filters-list .portfolio-show-filters-button { color: " . $params['filter_buttons_standard_color'] . " }";
		}

		if (isset($params['filter_buttons_standard_background_color']) && $params['filter_buttons_standard_background_color'] != '') {
			$style .= $wrapper . " .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .name," .
				$wrapper . " .portfolio-filters-list .portfolio-show-filters-button { background: " . $params['filter_buttons_standard_background_color'] . " }";
		}

		if (isset($params['filter_buttons_standard_border_width']) && $params['filter_buttons_standard_border_width'] != '') {
			$style .= $wrapper . " .portfolio-filters-list.style-standard .portfolio-filter-item .name { border-width: " . $params['filter_buttons_standard_border_width'] . "px }";
		}

		if (isset($params['filter_buttons_standard_border_radius']) && $params['filter_buttons_standard_border_radius'] != '') {
			$style .= $wrapper . " .portfolio-filters-list.style-standard .portfolio-filter-item .name { border-radius: " . $params['filter_buttons_standard_border_radius'] . "px }";
		}

		if (isset($params['filter_buttons_standard_bottom_spacing']) && $params['filter_buttons_standard_bottom_spacing'] != '') {
			$style .= $wrapper . " .portfolio-top-panel { margin-bottom: " . $params['filter_buttons_standard_bottom_spacing'] . "px }";
		}

		if (isset($params['filter_buttons_standard_dropdown_text_color_normal']) && $params['filter_buttons_standard_dropdown_text_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-filter-item .portfolio-filter-item-list ul li a," .
				$wrapper . " .portfolio-filters-list .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount," .
				$wrapper . " .portfolio-filters-list .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount .slider-amount-text { color: " . $params['filter_buttons_standard_dropdown_text_color_normal'] . " }";
		}

		if (isset($params['filter_buttons_standard_dropdown_text_color_hover']) && $params['filter_buttons_standard_dropdown_text_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-filter-item .portfolio-filter-item-list ul li a:hover { color: " . $params['filter_buttons_standard_dropdown_text_color_hover'] . " }";
		}

		if (isset($params['filter_buttons_standard_dropdown_text_color_active']) && $params['filter_buttons_standard_dropdown_text_color_active'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-filter-item .portfolio-filter-item-list ul li a.active { color: " . $params['filter_buttons_standard_dropdown_text_color_active'] . " }";
			$style .= $wrapper . " .portfolio-filters-list .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-range," .
				$wrapper . " .portfolio-filters-list .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-handle + span," .
				$wrapper . " .portfolio-filters-list .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-handle { background-color: " . $params['filter_buttons_standard_dropdown_text_color_active'] . " }";
		}

		if (isset($params['filter_buttons_standard_dropdown_background_color']) && $params['filter_buttons_standard_dropdown_background_color'] != '') {
			$style .= $wrapper . " .portfolio-filters-list.style-standard:not(.single-filter, .style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list { background-color: " . $params['filter_buttons_standard_dropdown_background_color'] . " }";
		}

		if (isset($params['filter_buttons_standard_dropdown_price_range_background_color_normal']) && $params['filter_buttons_standard_dropdown_price_range_background_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount { background-color: " . $params['filter_buttons_standard_dropdown_price_range_background_color_normal'] . " }";
		}

		if (isset($params['filter_buttons_standard_dropdown_price_range_background_color_hover']) && $params['filter_buttons_standard_dropdown_price_range_background_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount:hover { background-color: " . $params['filter_buttons_standard_dropdown_price_range_background_color_hover'] . " }";
		}

		if (isset($params['filter_buttons_standard_dropdown_price_range_background_color_active']) && $params['filter_buttons_standard_dropdown_price_range_background_color_active'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount.active { background-color: " . $params['filter_buttons_standard_dropdown_price_range_background_color_active'] . " }";
		}

		if (isset($params['items_list_max_height']) && $params['items_list_max_height'] != '') {
			$style .= $wrapper . " .portfolio-filter-item-list { max-height: " . $params['items_list_max_height'] . "px; padding-right: 10px; }";
		}

		if (isset($params['filter_buttons_hidden_sidebar_separator_width']) && $params['filter_buttons_hidden_sidebar_separator_width'] != '') {
			$style .= $wrapper . " .portfolio-filters-list.style-hidden .portfolio-filter-item, " .
				$wrapper . " .portfolio-filters-list.style-sidebar .portfolio-filter-item { border-width: " . $params['filter_buttons_hidden_sidebar_separator_width'] . "px }";
			$style .=  "@media (max-width: 991px) { " . $wrapper . " .portfolio-filters-list.style-standard .portfolio-filter-item { border-width: " . $params['filter_buttons_hidden_sidebar_separator_width'] . "px }}";
		}

		if (isset($params['filter_buttons_hidden_sidebar_separator_color']) && $params['filter_buttons_hidden_sidebar_separator_color'] != '') {
			$style .= $wrapper . " .portfolio-filters-list.style-hidden .portfolio-filter-item, " .
				$wrapper . " .portfolio-filters-list.style-sidebar .portfolio-filter-item { border-color: " . $params['filter_buttons_hidden_sidebar_separator_color'] . " }";
			$style .= "@media (max-width: 991px) { " . $wrapper . " .portfolio-filters-list.style-standard .portfolio-filter-item { border-color: " . $params['filter_buttons_hidden_sidebar_separator_color'] . " }}";
		}

		if (isset($params['filter_buttons_standard_selected_border_radius']) && $params['filter_buttons_standard_selected_border_radius'] != '') {
			$style .= $wrapper . " .portfolio-selected-filters .portfolio-selected-filter-item { border-radius: " . $params['filter_buttons_standard_selected_border_radius'] . "px }";
		}

		if (isset($params['filter_buttons_standard_selected_text_color_normal']) && $params['filter_buttons_standard_selected_text_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-selected-filters .portfolio-selected-filter-item { color: " . $params['filter_buttons_standard_selected_text_color_normal'] . " }";
		}

		if (isset($params['filter_buttons_standard_selected_text_color_hover']) && $params['filter_buttons_standard_selected_text_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-selected-filters .portfolio-selected-filter-item:hover { color: " . $params['filter_buttons_standard_selected_text_color_hover'] . " }";
		}

		if (isset($params['filter_buttons_standard_selected_background_color_normal']) && $params['filter_buttons_standard_selected_background_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-selected-filters .portfolio-selected-filter-item { background-color: " . $params['filter_buttons_standard_selected_background_color_normal'] . " }";
		}

		if (isset($params['filter_buttons_standard_selected_background_color_hover']) && $params['filter_buttons_standard_selected_background_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-selected-filters .portfolio-selected-filter-item:hover { background-color: " . $params['filter_buttons_standard_selected_background_color_hover'] . " }";
		}

		if (isset($params['filter_buttons_standard_selected_padding_top']) && $params['filter_buttons_standard_selected_padding_top'] != '') {
			$style .= $wrapper . " .portfolio-selected-filters .portfolio-selected-filter-item { padding-top: " . $params['filter_buttons_standard_selected_padding_top'] . "px; }";
		}

		if (isset($params['filter_buttons_standard_selected_padding_bottom']) && $params['filter_buttons_standard_selected_padding_bottom'] != '') {
			$style .= $wrapper . " .portfolio-selected-filters .portfolio-selected-filter-item { padding-bottom: " . $params['filter_buttons_standard_selected_padding_bottom'] . "px; }";
		}

		if (isset($params['filter_buttons_standard_selected_padding_left']) && $params['filter_buttons_standard_selected_padding_left'] != '') {
			$style .= $wrapper . " .portfolio-selected-filters .portfolio-selected-filter-item { padding-left: " . $params['filter_buttons_standard_selected_padding_left'] . "px; }";
		}

		if (isset($params['filter_buttons_standard_selected_padding_right']) && $params['filter_buttons_standard_selected_padding_right'] != '') {
			$style .= $wrapper . " .portfolio-selected-filters .portfolio-selected-filter-item { padding-right: " . $params['filter_buttons_standard_selected_padding_right'] . "px; }";
		}

		if (isset($params['filter_buttons_sidebar_color']) && $params['filter_buttons_sidebar_color'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-show-filters-button { color: " . $params['filter_buttons_sidebar_color'] . " }";
		}

		if (isset($params['filter_buttons_sidebar_border_radius']) && $params['filter_buttons_sidebar_border_radius'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-show-filters-button { border-radius: " . $params['filter_buttons_sidebar_border_radius'] . "px }";
		}

		if (isset($params['filter_buttons_sidebar_border_width']) && $params['filter_buttons_sidebar_border_width'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-show-filters-button { border-width: " . $params['filter_buttons_sidebar_border_width'] . "px}";
		}

		if (isset($params['filter_buttons_standard_background']) && $params['filter_buttons_standard_background'] != '') {
			$style .= $wrapper . " .portfolio-filters-list.style-hidden .portfolio-filters-outer .portfolio-filters-area { background-color: " . $params['filter_buttons_standard_background'] . " }";
			$style .= "@media (max-width: 991px) { " . $wrapper . " .portfolio-filters-list.style-standard .portfolio-filters-outer .portfolio-filters-area," .
				$wrapper . " .portfolio-filters-list.style-sidebar .portfolio-filters-outer .portfolio-filters-area { background-color: " . $params['filter_buttons_standard_background'] . " }}";
		}

		if (isset($params['filter_buttons_standard_overlay_color']) && $params['filter_buttons_standard_overlay_color'] != '') {
			$style .= $wrapper . " .portfolio-filters-list.style-hidden .portfolio-filters-outer { background-color: " . $params['filter_buttons_standard_overlay_color'] . " }";
			$style .= "@media (max-width: 991px) { " . $wrapper . " .portfolio-filters-list.style-standard .portfolio-filters-outer," .
				$wrapper . " .portfolio-filters-list.style-sidebar .portfolio-filters-outer { background-color: " . $params['filter_buttons_standard_overlay_color'] . " }}";
		}

		if (isset($params['filter_buttons_standard_close_icon_color']) && $params['filter_buttons_standard_close_icon_color'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-close-filters { color: " . $params['filter_buttons_standard_close_icon_color'] . " }";
		}

		if (isset($params['filter_buttons_standard_search_icon_color']) && $params['filter_buttons_standard_search_icon_color'] != '') {
			$style .= $wrapper . " .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-button," .
				$wrapper . " .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter .portfolio-search-filter-button { color: " . $params['filter_buttons_standard_search_icon_color'] . " }";
		}

		if (isset($params['filter_buttons_standard_search_input_border_radius']) && $params['filter_buttons_standard_search_input_border_radius'] != '') {
			$style .= $wrapper . " .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-form input," .
				$wrapper . " .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input { border-radius: " . $params['filter_buttons_standard_search_input_border_radius'] . "px }";
		}

		if (isset($params['filter_buttons_standard_search_input_color']) && $params['filter_buttons_standard_search_input_color'] != '') {
			$style .= $wrapper . " .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-form input," .
				$wrapper . " .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input { color: " . $params['filter_buttons_standard_search_input_color'] . " }";
		}

		if (isset($params['filter_buttons_standard_search_input_background_color']) && $params['filter_buttons_standard_search_input_background_color'] != '') {
			$style .= $wrapper . " .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-form input," .
				$wrapper . " .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input { background-color: " . $params['filter_buttons_standard_search_input_background_color'] . " }";
		}

		if (!empty($params['sorting_extended_text_color'])) {
			$style .= $wrapper . " .portfolio-sorting-select div.portfolio-sorting-select-current { color: " . $params['sorting_extended_text_color'] . " }";
		}

		if (!empty($params['sorting_extended_background_color'])) {
			$style .= $wrapper . " .portfolio-sorting-select div.portfolio-sorting-select-current { background-color: " . $params['sorting_extended_background_color'] . "; }";
		}

		if (!empty($params['sorting_extended_border_color'])) {
			$style .= $wrapper . " .portfolio-sorting-select div.portfolio-sorting-select-current { border-color: " . $params['sorting_extended_border_color'] . "; }";
		}

		if (isset($params['sorting_extended_border_radius']) && $params['sorting_extended_border_radius'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select div.portfolio-sorting-select-current { border-radius: " . $params['sorting_extended_border_radius'] . "px }";
		}

		if (isset($params['sorting_extended_border_width']) && $params['sorting_extended_border_width'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select div.portfolio-sorting-select-current { border-width: " . $params['sorting_extended_border_width'] . "px }";
		}

		if (isset($params['sorting_extended_bottom_spacing']) && $params['sorting_extended_bottom_spacing'] != '') {
			$style .= $wrapper . " .portfolio-top-panel { margin-bottom: " . $params['sorting_extended_bottom_spacing'] . "px }";
		}

		if (isset($params['sorting_extended_padding_top']) && $params['sorting_extended_padding_top'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select div.portfolio-sorting-select-current { padding-top: " . $params['sorting_extended_padding_top'] . "px; }";
		}

		if (isset($params['sorting_extended_padding_bottom']) && $params['sorting_extended_padding_bottom'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select div.portfolio-sorting-select-current { padding-bottom: " . $params['sorting_extended_padding_bottom'] . "px; }";
		}

		if (isset($params['sorting_extended_padding_left']) && $params['sorting_extended_padding_left'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select div.portfolio-sorting-select-current { padding-left: " . $params['sorting_extended_padding_left'] . "px; }";
		}

		if (isset($params['sorting_extended_padding_right']) && $params['sorting_extended_padding_right'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select div.portfolio-sorting-select-current { padding-right: " . $params['sorting_extended_padding_right'] . "px; }";
		}

		if (isset($params['sorting_extended_dropdown_text_color_normal']) && $params['sorting_extended_dropdown_text_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select ul li { color: " . $params['sorting_extended_dropdown_text_color_normal'] . " }";
		}

		if (isset($params['sorting_extended_dropdown_text_color_hover']) && $params['sorting_extended_dropdown_text_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select ul li:hover { color: " . $params['sorting_extended_dropdown_text_color_hover'] . " }";
		}

		if (isset($params['sorting_extended_dropdown_text_color_active']) && $params['sorting_extended_dropdown_text_color_active'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select ul li.portfolio-sorting-select-current { color: " . $params['sorting_extended_dropdown_text_color_active'] . " }";
		}

		if (isset($params['sorting_extended_dropdown_background_color']) && $params['sorting_extended_dropdown_background_color'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select ul { background-color: " . $params['sorting_extended_dropdown_background_color'] . " }";
		}

		if (!empty($params['show_details'])) {

			if (!empty($params['details_divider_color'])) {
				$style .= $wrapper . " .portfolio-item .details.layout-vertical.with-divider .details-item { border-color: " . $params['details_divider_color'] . " }";
			}

			if (!empty($params['details_label_transform'])) {
				$style .= $wrapper . " .portfolio-item .details .details-item .label { text-transform: " . $params['details_label_transform'] . " }";
			}

			if (isset($params['details_label_letter_spacing'])) {
				$style .= $wrapper . " .portfolio-item .details .details-item .label { letter-spacing: " . $params['details_label_letter_spacing'] . "px }";
			}

			if (!empty($params['details_label_color'])) {
				$style .= $wrapper . " .portfolio-item .details .details-item .label { color: " . $params['details_label_color'] . " }";
			}

			if (!empty($params['details_icon_size'])) {
				$style .= $wrapper . " .portfolio-item .details .details-item .label i { font-size: " . $params['details_icon_size'] . "px; line-height: " . $params['details_icon_size'] . "px; }";
			}

			if (!empty($params['details_value_transform'])) {
				$style .= $wrapper . " .portfolio-item .details .details-item .value { text-transform: " . $params['details_value_transform'] . " }";
			}

			if (isset($params['details_value_letter_spacing'])) {
				$style .= $wrapper . " .portfolio-item .details .details-item .value { letter-spacing: " . $params['details_value_letter_spacing'] . "px }";
			}

			if (!empty($params['details_value_color'])) {
				$style .= $wrapper . " .portfolio-item .details .details-item .value { color: " . $params['details_value_color'] . " }";
			}

			if (!empty($params['details_value_border_color'])) {
				$style .= $wrapper . " .portfolio-item .details .details-item { border-color: " . $params['details_value_border_color'] . " }";
			}

			if (!empty($params['details_icon_size_value'])) {
				$style .= $wrapper . " .portfolio-item .details .details-item .value i { font-size: " . $params['details_icon_size_value'] . "px; line-height: 1.2; }";
			}
		}

		if ($params['show_readmore_button']) {
			if (!empty($params['readmore_button_corner'])) {
				$style .= $wrapper . ' .portfolio-item .read-more-button .gem-button { border-radius: ' . esc_attr($params['readmore_button_corner']) . 'px; }';
			}
			if (!empty($params['readmore_button_border'])) {
				$style .= $wrapper . ' .portfolio-item .read-more-button .gem-button { border-width: ' . esc_attr($params['readmore_button_border']) . 'px; }';
			}
			if (!empty($params['readmore_button_text_color'])) {
				$style .= $wrapper . ' .portfolio-item .read-more-button .gem-button { color: ' . esc_attr($params['readmore_button_text_color']) . '; }';
			}
			if (!empty($params['readmore_button_hover_text_color'])) {
				$style .= $wrapper . ' .portfolio-item .read-more-button .gem-button:hover { color: ' . esc_attr($params['readmore_button_hover_text_color']) . '; }';
			}
			if (!empty($params['readmore_button_background_color'])) {
				$style .= $wrapper . ' .portfolio-item .read-more-button .gem-button { background-color: ' . esc_attr($params['readmore_button_background_color']) . '; }';
			}
			if (!empty($params['readmore_button_hover_background_color'])) {
				$style .= $wrapper . ' .portfolio-item .read-more-button .gem-button:hover { background-color: ' . esc_attr($params['readmore_button_hover_background_color']) . '; }';
			}
			if (!empty($params['readmore_button_border_color'])) {
				$style .= $wrapper . ' .portfolio-item .read-more-button .gem-button { border-color: ' . esc_attr($params['readmore_button_border_color']) . '; }';
			}
			if (!empty($params['readmore_button_hover_border_color'])) {
				$style .= $wrapper . ' .portfolio-item .read-more-button .gem-button:hover { border-color: ' . esc_attr($params['readmore_button_hover_border_color']) . '; }';
			}
		}

		if (!empty($params['filters_sticky_color'])) {
			$style .= $wrapper . ' .portfolio-top-panel.sticky-fixed .portfolio-top-panel { background: ' . esc_attr($params['filters_sticky_color']) . '; }';
		}

		if ($skeleton && !empty($params['minimal_preloader_color'])) {
			$style .= $skeleton . ' .preloader-spin-new { border-color: ' . esc_attr($params['minimal_preloader_color']) . '; }';
		}

		if (isset($params['image_border_radius']) && $params['image_border_radius'] != '') {
			if (isset($params['border_caption_container']) && $params['border_caption_container'] === '1') {
				$style .= $wrapper . " .portfolio-item .wrap { border-radius: " . $params['image_border_radius'] . "px; }";
				$style .= $wrapper . " .portfolio-item .wrap .image," .
					$wrapper . " .portfolio-item .wrap .image-inner {
							 border-top-left-radius: " . $params['image_border_radius'] . "px;
							 border-top-right-radius: " . $params['image_border_radius'] . "px; }";
				$style .= $wrapper . " .portfolio-item .wrap .caption {
			 border-bottom-left-radius: " . $params['image_border_radius'] . "px;
			 border-bottom-right-radius: " . $params['image_border_radius'] . "px; }";
			} else {
				$style .= $wrapper . " .portfolio-item .image," .
					$wrapper . " .portfolio-item .image .image-inner," .
					$wrapper . " .portfolio-item .image .overlay," .
					$wrapper . ".caption-position-hover .portfolio-item .wrap," .
					$wrapper . ".caption-position-image .portfolio-item .wrap { border-radius: " . $params['image_border_radius'] . "px }";
				$style .= $wrapper . ".caption-position-page .portfolio-item .wrap {  border-radius: " . $params['image_border_radius'] . "px " . $params['image_border_radius'] . "px 0 0 }";
			}
		}

		if (!empty($params['enable_shadow'])) {
			$shadow_position = '';
			if ($params['shadow_position'] == 'inset') {
				$shadow_position = 'inset';
			}
			$shadow_horizontal = $params['shadow_horizontal'] ?: 0;
			$shadow_vertical = $params['shadow_vertical'] ?: 0;
			$shadow_blur = $params['shadow_blur'] ?: 0;
			$shadow_spread = $params['shadow_spread'] ?: 0;
			$shadow_color = $params['shadow_color'] ?: '#000';

			if (!empty($params['shadowed_container'])) {
				$style .= $wrapper . ".shadowed-container .portfolio-item .wrap, ".$wrapper." .portfolio-item .thegem-template-wrapper { box-shadow: " . $shadow_position . " " . $shadow_horizontal . "px " . $shadow_vertical . "px " . $shadow_blur . "px " . $shadow_spread . "px " . $shadow_color . "; }";
			} else {
				$style .= $wrapper . ":not(.shadowed-container) .portfolio-item .image, ".$wrapper." .portfolio-item .thegem-template-wrapper { box-shadow: " . $shadow_position . " " . $shadow_horizontal . "px " . $shadow_vertical . "px " . $shadow_blur . "px " . $shadow_spread . "px " . $shadow_color . " !important; }";
			}
		}

		$style .= "</style>";

		return $style;
	}
}

function thegem_portfolio_block($params = array()) {
	echo '<div class="block-content clearfix">';
	thegem_portfolio_slider($params);
	echo '</div>';
}

// Print Portfolio Slider
function thegem_portfolio_slider($params) {

	$params['is_slider'] = true;

	$gap_size = round(intval($params['gaps_size'])/2);

	if (empty($params['fullwidth_columns']))
		$params['fullwidth_columns'] = 5;

	wp_enqueue_style('thegem-portfolio');
	wp_enqueue_style('thegem-portfolio-slider');
	wp_enqueue_style('thegem-hovers-' . $params['hover']);

	wp_enqueue_script('thegem-juraSlider');

	$layout_columns_count = -1;
	if($params['layout'] == '3x')
		$layout_columns_count = 3;

	$layout_fullwidth = false;
	if($params['layout'] == '100%')
		$layout_fullwidth = true;

	$taxonomy_filter = $portfolios_posts = [];
	$single_post_id = thegem_templates_init_portfolio() ? thegem_templates_init_portfolio()->ID : get_the_ID();

	if ($params['query_type'] == 'related') {

		$taxonomies = explode(',', $params['taxonomy_related']);
		if (!empty($taxonomies)) {
			foreach ($taxonomies as $tax) {
				$taxonomy_filter[$tax] = [];
				$tax_terms = get_the_terms($single_post_id, $tax);
				if (!empty($tax_terms) && !is_wp_error($tax_terms)) {
					foreach ($tax_terms as $term) {
						$taxonomy_filter[$tax][] = $term->slug;
					}
				}
			}
		}
		$params['related_tax_filter'] = $taxonomy_filter;

		if ($params['exclude_portfolios']) {
			$params['exclude_portfolios'][] = $single_post_id;
		} else {
			$params['exclude_portfolios'] = [$single_post_id];
		}
	} else {
		$params['source'] = explode(',', $params['source']);
		foreach ($params['source'] as $source) {
			if ($source == 'posts') {
				$params['content_portfolios_posts'] = explode(',', $params['content_portfolios_posts']);
				$portfolios_posts = $params['content_portfolios_posts'];
			} else if (!empty($params['content_portfolios_' . $source])) {
				$tax_terms = $params['content_portfolios_' . $source] = explode(',', $params['content_portfolios_' . $source]);
				if (!empty($tax_terms)) {
					$taxonomy_filter[$source] = $tax_terms;
				}
			}
		}
	}

	if (isset($params['order_by']) && $params['order_by'] != 'default') {
		$params['orderby'] = $params['order_by'];
	} else {
		$params['orderby'] = 'menu_order ID';
	}

	if (!isset($params['order']) || $params['order'] == 'default') {
		$params['order'] = 'ASC';
	}

	$portfolio_loop = thegem_get_portfolio_posts($portfolios_posts, $taxonomy_filter, [], false, false, 1, -1, $params['orderby'], $params['order']);

	$terms = array();

	$portfolio_title = __('Portfolio', 'thegem');
	if (!empty($params['content_portfolios_thegem_portfolios']) && sizeof($params['content_portfolios_thegem_portfolios']) == 1 && $portfolio_set = get_term_by('slug', $params['content_portfolios_thegem_portfolios'][0], 'thegem_portfolios')) {
		$portfolio_title = $portfolio_set->name;
	}
	$portfolio_title = $params['title'] ? $params['title'] : $portfolio_title;

	$params['style'] = 'justified';

	$classes = array('portfolio', 'portfolio-slider', 'clearfix', 'no-padding', 'col-lg-12', 'col-md-12', 'col-sm-12', 'hover-'.$params['hover']);
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
	if($params['gaps_size'] == 0)
		$classes[] = 'without-padding';
	if($params['layout'] == '100%')
		$classes[] = 'fullwidth-columns-'.$params['fullwidth_columns'];

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

	$classes[] = 'title-on-' . $params['display_titles'];
	$classes[] = 'gem-slider-animation-' . $params['slider_animation'];


?>

	<?php if($portfolio_loop->have_posts()) : ?>
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

			<?php
				if (!empty($params['content_portfolios_thegem_portfolios'])) {
					$terms = $params['content_portfolios_thegem_portfolios'];
					foreach($terms as $key => $term) {
						$terms[$key] = get_term_by('slug', $term, 'thegem_portfolios');
						if(!$terms[$key]) {
							unset($terms[$key]);
						}
					}
				} else {
					$terms = get_terms('thegem_portfolios', array('hide_empty' => false));
				}
				$thegem_terms_set = array();
				foreach ($terms as $term)
					$thegem_terms_set[$term->slug] = $term;
			?>

			<div class="portolio-slider-content">
				<div class="portolio-slider-center">
					<div class="<?php if($params['layout'] == '100%'): ?>fullwidth-block<?php endif; ?>">
						<div style="margin: -<?php echo $gap_size; ?>px;">
							<div class="portfolio-set clearfix" <?php if(intval($params['autoscroll'])) { echo 'data-autoscroll="'.intval($params['autoscroll']).'"'; } ?>>
								<?php while ($portfolio_loop->have_posts()) : $portfolio_loop->the_post(); ?>
									<?php $slugs = wp_get_object_terms(get_the_ID(), 'thegem_portfolios', array('fields' => 'slugs')); ?>
									<?php include(locate_template('gem-templates/portfolios/content-portfolio-carusel-item.php')); ?>
								<?php endwhile; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif;

thegem_templates_close_portfolio('gem_portfolio_slider', ['name' => __('Portfolio slider', 'thegem')], $portfolio_loop->have_posts());
}

// Print Gallery Block
function thegem_gallery_block($params) {
	$params = array_merge(
		array(
			'ids' => array(),
			'source_type' => 'custom',
			'gallery' => '',
			'type' => 'slider',
			'columns_desktop' => '3x',
			'columns_100' => '5',
			'layout' => 'justified',
			'no_gaps' => false,
			'hover' => 'default',
			'item_style' => '',
			'title' => '',
			'gaps_size' => '',
			'effects_enabled' => '',
			'loading_animation' => 'move-up',
			'metro_max_row_height' => 380,
			'image_size' => 'default',
			'image_ratio' => '1',
			'fullwidth_section_images' => '',
		),
		$params
	);
	wp_enqueue_style('thegem-hovers-' . $params['hover']);
	wp_enqueue_style('thegem-gallery');

	if ($params['layout'] !== 'justified' || $params['ignore_highlights'] !== '1') {
		if ($params['layout']  == 'metro') {
			wp_enqueue_script('thegem-isotope-metro');
		} else {
			wp_enqueue_script('thegem-isotope-masonry-custom');
		}
	}

	if ($params['loading_animation'] !== 'disabled') {
		wp_enqueue_style('thegem-animations');
		wp_enqueue_script('thegem-items-animations');
		wp_enqueue_script('thegem-scroll-monitor');
	}

	wp_enqueue_script('thegem-gallery');

	if (empty($params['columns_100']))
		$params['columns_100'] = 5;

	$params['loading_animation'] = thegem_check_array_value(array('disabled', 'bounce', 'move-up', 'fade-in', 'fall-perspective', 'scale', 'flip'), $params['loading_animation'], 'move-up');

	if ($params['effects_enabled']) {
		thegem_lazy_loading_enqueue();
	}

	ob_start();
	$gallery_items = [];

	if (!empty($params['ids'])) {
		$gallery_items = $params['ids'];
	} else {
		if ($params['source_type'] == 'product-gallery') {

			$product = thegem_templates_init_product();
			if (!empty($product)) {
				$gallery_items = $product->get_gallery_image_ids();
				if ('variable' === $product->get_type()) {
					foreach ($product->get_available_variations() as $variation) {
						if (has_post_thumbnail($variation['variation_id'])) {
							$thumbnail_id = get_post_thumbnail_id($variation['variation_id']);
							if (!in_array($thumbnail_id, $gallery_items)) {
								$gallery_items[] = $thumbnail_id;
							}
						}
					}
				}
			}

		} else if ($params['source_type'] == 'portfolio-gallery') {

			$portfolio = thegem_templates_init_portfolio();
			if (!empty($portfolio)) {
				$gallery_items = explode(',', get_post_meta($portfolio->ID, 'thegem_portfolio_item_gallery_images', true));
			}

		} else {

			$galleries = explode(',', $params['gallery']);
			$gallery_items_filter_classes = [];

			foreach ($galleries as $gallery_index => $gallery) {

				if (metadata_exists('post', $gallery, 'thegem_gallery_images')) {
					$thegem_gallery_images_ids = get_post_meta($gallery, 'thegem_gallery_images', true);
					$galleries[$gallery_index] = get_the_title($gallery);
				} else {
					$attachments_ids = get_posts('post_parent=' . $gallery . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&post_status=publish');
					$thegem_gallery_images_ids = implode(',', $attachments_ids);
				}
				$thegem_gallery_images_ids = array_filter(explode(',', $thegem_gallery_images_ids));

				foreach ($thegem_gallery_images_ids as $item) {
					if (!in_array($item, $gallery_items, true)) {
						$gallery_items[] = $item;
					}
					if (!isset($gallery_items_filter_classes[$item])) {
						$gallery_items_filter_classes[$item] = ['gallery-'.$gallery_index];
					} else {
						$gallery_items_filter_classes[$item][] = 'gallery-'.$gallery_index;
					}
				}
			}
		}

	}

	if (!empty($params['image_ratio_default'])) {
		$params['image_aspect_ratio'] = 'custom';
		$params['image_ratio_custom'] = $params['image_ratio_default'];
	}

	$gallery_uid = !empty($params['gallery_uid']) ? $params['gallery_uid'] : uniqid();
	$gallery_uid_url = $gallery_uid . '-';

	$inlineStyles = '';
	$gridSelector = '.gem-gallery-grid#style-' . $gallery_uid;

	$gap_size = isset($params['gaps_size']) && $params['gaps_size'] != '' ? round(intval($params['gaps_size'])) : 0;
	$gap_size_tablet = isset($params['gaps_size_tablet']) && $params['gaps_size_tablet'] != '' ? round(intval($params['gaps_size_tablet'])) : null;
	$gap_size_mobile = isset($params['gaps_size_mobile']) && $params['gaps_size_mobile'] != '' ? round(intval($params['gaps_size_mobile'])) : null;

	if (isset($gap_size)) {
		$inlineStyles .= $gridSelector . ' .gallery-item { padding: calc(' . $gap_size . 'px/2) !important; }' .
			$gridSelector . ' .gallery-set { margin-top: calc(-' . $gap_size . 'px/2); margin-bottom: calc(-' . $gap_size . 'px/2); }' .
			$gridSelector . ' .not-fullwidth-block .gallery-set, ' .
			$gridSelector . ' .not-fullwidth-block .portfolio-item-size-container { margin-left: calc(-' . $gap_size . 'px/2); margin-right: calc(-' . $gap_size . 'px/2); }' .
			$gridSelector . ' .fullwidth-block { padding-left: calc(' . $gap_size . 'px/2); padding-right: calc(' . $gap_size . 'px/2); }';
	}
	if (isset($gap_size_tablet)) {
		$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .gallery-item { padding: calc(' . $gap_size_tablet . 'px/2) !important; }' .
			$gridSelector . ' .gallery-set { margin-top: calc(-' . $gap_size_tablet . 'px/2); margin-bottom: calc(-' . $gap_size_tablet . 'px/2); }' .
			$gridSelector . ' .not-fullwidth-block .gallery-set, ' .
			$gridSelector . ' .not-fullwidth-block .portfolio-item-size-container { margin-left: calc(-' . $gap_size_tablet . 'px/2); margin-right: calc(-' . $gap_size_tablet . 'px/2); }' .
			$gridSelector . ' .fullwidth-block { padding-left: calc(' . $gap_size_tablet . 'px/2); padding-right: calc(' . $gap_size_tablet . 'px/2); }}';
	}
	if (isset($gap_size_mobile)) {
		$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .gallery-item { padding: calc(' . $gap_size_mobile . 'px/2) !important; }' .
			$gridSelector . ' .gallery-set { margin-top: calc(-' . $gap_size_mobile . 'px/2); margin-bottom: calc(-' . $gap_size_mobile . 'px/2); }' .
			$gridSelector . ' .not-fullwidth-block .gallery-set, ' .
			$gridSelector . ' .not-fullwidth-block .portfolio-item-size-container { margin-left: calc(-' . $gap_size_mobile . 'px/2); margin-right: calc(-' . $gap_size_mobile . 'px/2); }' .
			$gridSelector . ' .fullwidth-block { padding-left: calc(' . $gap_size_mobile . 'px/2); padding-right: calc(' . $gap_size_mobile . 'px/2); }}';
	}

	if ($params['columns_desktop'] == '100%' || !empty($params['fullwidth_section_sorting'])) {
		if ($gap_size < 21) {
			$inlineStyles .= $gridSelector . " .fullwidth-block .portfolio-top-panel:not(.gem-sticky-block), " .
				$gridSelector . " .portfolio-item.not-found .found-wrap { padding-left: 21px; padding-right: 21px; }";
		}
		if ($gap_size_tablet < 21) {
			$inlineStyles .= "@media (min-width: 768px) and (max-width: 991px) { " . $gridSelector . " .fullwidth-block .portfolio-top-panel:not(.gem-sticky-block), " .
				$gridSelector . " .portfolio-item.not-found .found-wrap { padding-left: 21px; padding-right: 21px; }}";
		}
		if ($gap_size_mobile < 21) {
			$inlineStyles .= "@media (max-width: 767px) { " . $gridSelector . " .fullwidth-block .portfolio-top-panel:not(.gem-sticky-block), " .
				$gridSelector . " .portfolio-item.not-found .found-wrap { padding-left: 21px; padding-right: 21px; }}";
		}
	}

	if (!empty($params['filter_buttons_position'])) {
		$inlineStyles .= $gridSelector . " .portfolio-filters { text-align: " . $params['filter_buttons_position'] . " }";
	}

	if (!empty($params['filters_sticky_color'])) {
		$inlineStyles .= $gridSelector . ' .portfolio-top-panel.sticky-fixed .portfolio-top-panel { background: ' . esc_attr($params['filters_sticky_color']) . '; }';
	}

	if (!empty($params['minimal_preloader_color'])) {
		$inlineStyles .= '#style-preloader-' . $gallery_uid . ' .preloader-spin-new { border-color: ' . esc_attr($params['minimal_preloader_color']) . '; }';
	}

	if (!empty($inlineStyles)) {
		echo '<style>'.$inlineStyles.'</style>';
	}

	$active_filter = [];
	if (!empty($params['with_filter'])) {
		if (isset($_GET[$gallery_uid_url . 'filter'])) {
			$active_filter = explode(",", $_GET[$gallery_uid_url . 'filter']);
		} else if (!$params['filter_show_all']) {
			$active_filter = ['gallery-0'];
		}
	}

	$gallery_grid_classes = array(
		'gem-gallery-grid col-lg-12 col-md-12 col-sm-12',
		'gallery-style-' . $params['layout'],
		'hover-' . $params['hover'],
		($params['loading_animation'] !== 'disabled' ? 'loading-animation' : ''),
		($params['loading_animation'] !== 'disabled' ? 'item-animation-' . $params['loading_animation'] : ''),
		($params['columns_desktop'] == '100%' ? 'fullwidth-columns fullwidth-columns-' . intval($params['columns_100']) : ''),
		($params['layout'] == 'masonry' ? 'gallery-items-masonry' : ''),
		($params['layout'] == 'metro' ? 'metro' : ''),
		($params['no_gaps'] ? 'without-padding' : ''),
		($params['columns_desktop'] != '100%' ? 'columns-' . str_replace("x", "", $params['columns_desktop']) : ''),
		'columns-tablet-' . str_replace("x", "", $params['columns_tablet']),
		'columns-mobile-' . str_replace("x", "", $params['columns_mobile']),
		($params['gaps_size'] && $params['layout'] != 'metro' ? 'gaps-margin' : ''),
		($params['gaps_size'] && $params['layout'] == 'metro' ? 'without-padding' : ''),
		($params['layout'] == 'metro' && $params['item_style'] ? 'metro-item-style-'.$params['item_style'] : ''),
		($params['layout'] == 'justified' && $params['ignore_highlights'] ? 'disable-isotope' : ''),
		((isset($params['image_size']) && (($params['image_size'] == 'full' && empty($params['image_ratio'])) || !in_array($params['image_size'], ['full', 'default']))) ? 'full-image' : ''),
	);

	$item_classes = get_thegem_portfolio_render_item_classes($params);
	$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params);

	if (!empty($gallery_items)) {

		if ($params['columns_desktop'] == '100%' || !$params['ignore_highlights'] || $params['layout'] !== 'justified') {
			$spin_class = 'preloader-spin';
			if ($params['ajax_preloader_type'] == 'minimal') {
				$spin_class = 'preloader-spin-new';
			}
			echo apply_filters('thegem_portfolio_preloader_html', '<div id="style-preloader-' . $gallery_uid . '" class="preloader save-space"><div class="' . $spin_class . '"></div></div>');
		} ?>
		<div class="gallery-preloader-wrapper">
			<?php if($params['title']): ?>
				<h3 style="text-align: center;"><?php echo $params['title']; ?></h3>
			<?php endif; ?>
			<div class="row">

				<div id="style-<?php echo $gallery_uid; ?>"
					 class="<?php echo implode(' ', $gallery_grid_classes); ?>"
					 data-hover="<?php echo esc_attr($params['hover']); ?>"
					 data-uid="<?php echo esc_attr($gallery_uid); ?>"
					 data-filter="<?php echo esc_attr(json_encode($active_filter)); ?>">

					<div class="portfolio <?php echo $params['columns_desktop'] == '100%' ? 'fullwidth-block' : 'not-fullwidth-block'; ?> ">

						<?php if (!empty($params['with_filter'])) {
							wp_enqueue_style('thegem-portfolio-filters-list');
							?>
							<div class="portfolio-top-panel filter-type-default<?php
							echo $params['filters_sticky'] ? ' filters-top-sticky' : '';
							echo $params['mobile_dropdown'] ? ' filters-mobile-dropdown' : ''; ?>">
								<?php if ($params['filters_sticky']) {
									wp_enqueue_script('thegem-sticky');
								} ?>
								<div class="portfolio-top-panel-row filter-style-<?php echo $params['filter_style']; ?>">
									<div class="portfolio-top-panel-left <?php echo strtolower($params['filter_query_type']) == 'and' ? 'multiple' : 'single'; ?>">
										<?php if ($params['mobile_dropdown']) { ?>
											<div class="portfolio-filters portfolio-filters-mobile portfolio-filters-more">
												<div class="portfolio-filters-more-button title-h6">
													<div class="portfolio-filters-more-button-name"><?php echo $params['filters_mobile_show_button_text']; ?></div>
													<span class="portfolio-filters-more-button-arrow"></span>
												</div>
												<div class="portfolio-filters-more-dropdown">
													<?php if ($params['filter_show_all']) { ?>
														<a href="#" data-filter="*" class="<?php echo empty($active_filter) ? 'active' : ''; ?> all title-h6 <?php echo $params['hover_pointer'] == 'yes' ? 'hover-pointer' : ''; ?>">
															<span <?php echo $params['filter_style'] == 'buttons' ? 'class="light"' : ''; ?>>
																<?php echo $params['all_text']; ?>
															</span>
														</a>
													<?php }
													foreach ($galleries as $i => $gallery) { ?>
														<a href="#" data-filter="gallery-<?php echo $i; ?>"
														   class="<?php echo in_array('gallery-' . $i, $active_filter) ? 'active' : ''; ?> title-h6 <?php echo $params['hover_pointer'] == 'yes' ? 'hover-pointer' : ''; ?>">
															<span <?php echo $params['filter_style'] == 'buttons' ? 'class="light"' : ''; ?>>
																<?php echo $gallery; ?>
															</span>
														</a>
													<?php } ?>
												</div>
											</div>
										<?php } ?>
										<div class="portfolio-filters">
											<?php if ($params['filter_show_all']) { ?>
												<a href="#" data-filter="*" class="<?php echo empty($active_filter) ? 'active' : ''; ?> all title-h6 <?php echo $params['hover_pointer'] == 'yes' ? 'hover-pointer' : ''; ?>">
													<span <?php echo $params['filter_style'] == 'buttons' ? 'class="light"' : ''; ?>>
														<?php echo $params['all_text']; ?>
													</span>
												</a>
											<?php }
											foreach ($galleries as $i => $gallery) {
												if ($params['truncate_filters'] && $i == $params['truncate_filters_number']) { ?>
													<div class="portfolio-filters-more">
													<div class="portfolio-filters-more-button title-h6">
														<div
															class="portfolio-filters-more-button-name <?php echo $params['filter_style'] == 'buttons' ? 'light' : ''; ?>"><?php echo $params['filters_more_button_text']; ?></div>
														<?php if ($params['filters_more_button_arrow']) { ?>
															<span
																class="portfolio-filters-more-button-arrow"></span>
														<?php } ?>
													</div>
													<div class="portfolio-filters-more-dropdown">
												<?php } ?>
												<a href="#" data-filter="gallery-<?php echo $i; ?>"
												   class="<?php echo in_array('gallery-' . $i, $active_filter) ? 'active' : ''; ?> title-h6 <?php echo $params['hover_pointer'] ? 'hover-pointer' : ''; ?>">
														<span <?php echo $params['filter_style'] == 'buttons' ? 'class="light"' : ''; ?>>
															<?php echo $gallery; ?>
														</span>
												</a>
												<?php if ($params['truncate_filters'] && sizeof($galleries) > $params['truncate_filters_number'] && $i == sizeof($galleries) - 1) { ?>
													</div>
													</div>
												<?php }
											} ?>
										</div>
										<?php if ($params['filter_style'] == 'buttons') {
											wp_enqueue_script('jquery-dlmenu'); ?>
											<div class="portfolio-filters-resp <?php echo strtolower($params['filter_query_type']) == 'and' ? 'multiple' : 'single'; ?>">
												<button class="menu-toggle dl-trigger">
													<?php _e('Portfolio filters', 'thegem'); ?>
													<span class="menu-line-1"></span>
													<span class="menu-line-2"></span>
													<span class="menu-line-3"></span>
												</button>
												<ul class="dl-menu">
													<?php if ($params['filter_show_all']) { ?>
														<li>
															<a href="#" data-filter="*">
																<?php echo $params['all_text']; ?>
															</a>
														</li>
													<?php }
													foreach ($galleries as $i => $gallery) { ?>
														<li>
															<a href="#" data-filter="gallery-<?php echo $i; ?>">
																<?php echo $gallery; ?>
															</a>
														</li>
													<?php } ?>
												</ul>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						<?php } ?>

						<ul class="gallery-set clearfix" data-max-row-height="<?php echo floatval($params['metro_max_row_height']); ?>">
							<?php $is_size_item = false;
							foreach($gallery_items as $attachment_id) :
								include(locate_template('content-gallery-item.php'));
							endforeach; ?>
						</ul>

						<?php if ($params['layout'] !== 'justified' || !$params['ignore_highlights']) {
							$is_size_item = true; ?>
							<div class="portfolio-item-size-container">
								<?php include(locate_template('content-gallery-item.php')); ?>
							</div>
						<?php } ?>

					</div>
				</div>
			</div>
		</div>
	<?php } else {
		wp_reset_postdata();
		if (($params['source_type'] == 'product-gallery' && thegem_get_template_type(get_the_ID()) === 'single-product') ||
			($params['source_type'] == 'portfolio-gallery' && thegem_get_template_type(get_the_ID()) === 'portfolio')) { ?>
			<div class="no-elements-gallery-grid">
				<i class="eicon-gallery-justified" aria-hidden="true"></i>
			</div>
		<?php }
	}

	echo trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	wp_reset_postdata();

}

function thegem_news_block($params) {
	echo '<div class="block-content"><div class="container">';
	thegem_newss($params);
	echo '</div></div>';
}

function thegem_newss($params) {
	$params = array_merge(array('news_set' => '', 'effects_enabled' => false), $params);
	$args = array(
		'post_type' => 'thegem_news',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['news_set'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'thegem_news_sets',
				'field' => 'slug',
				'terms' => explode(',', $params['news_set'])
			)
		);
	}

	if ($params['effects_enabled']) {
		thegem_lazy_loading_enqueue();
	}

	$news_items = new WP_Query($args);
	if($news_items->have_posts()) {
		wp_enqueue_script('thegem-news-carousel');
		echo '<div class="preloader"><div class="preloader-spin"></div></div>';
		echo '<div class="gem-news gem-news-type-carousel clearfix">';
		while($news_items->have_posts()) {
			$news_items->the_post();
			include(locate_template('content-news-carousel-item.php'));
		}
		echo '</div>';
	}
	wp_reset_postdata();
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
					<div class="portfolio-top-panel<?php if($params['layout'] == '100%'): ?> fullwidth-block<?php endif; ?>" <?php if ($gap_size && $params['layout'] == '100%'): ?>style="padding-left: <?php echo 2*$gap_size; ?>px; padding-right: <?php echo 2*$gap_size; ?>px;"<?php endif; ?>><div class="portfolio-top-panel-row">
						<div class="portfolio-top-panel-left">
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
						<div class="portfolio-top-panel-right">
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

// Print Products Grid
function thegem_products_grid($params) {
	$params = array_merge(
		array(
			'categories' => '',
			'title' => '',
			'layout' => '2x',
			'layout_version' => 'fullwidth',
			'caption_position' => 'right',
			'style' => 'justified',
			'gaps_size' => 42,
			'display_titles' => 'page',
			'background_style' => 'white',
			'title_style' => 'light',
			'hover' => '',
			'pagination' => 'normal',
			'loading_animation' => 'move-up',
			'items_per_page' => 8,
			'with_filter' => false,
			'gem_product_grid_featured_products' => 0,
			'gem_product_grid_featured_products_hide_label' => 0,
			'gem_product_grid_onsale_products' => 0,
			'gem_product_grid_onsale_products_hide_label' => 0,
			'is_ajax' => false,
			'item_separator' => false,
			'disable_socials' => false,
			'fullwidth_columns' => '5',
			'sorting' => false,
			'orderby' => '',
			'order' => '',
			'button' => array(),
			'metro_max_row_height' => 380
		),
		$params
	);

	$params['button'] = array_merge(array(
		'text' => __('Load More', 'thegem'),
		'style' => 'flat',
		'size' => 'medium',
		'text_weight' => 'normal',
		'no_uppercase' => 0,
		'corner' => 25,
		'border' => 2,
		'text_color' => '',
		'background_color' => '#00bcd5',
		'border_color' => '',
		'hover_text_color' => '',
		'hover_background_color' => '',
		'hover_border_color' => '',
		'icon_pack' => 'elegant',
		'icon_elegant' => '',
		'icon_material' => '',
		'icon_fontawesome' => '',
		'icon_thegem_header' => '',
		'icon_userpack' => '',
		'icon_position' => 'left',
		'separator' => 'load-more',
	), $params['button']);

	$params['button']['icon'] = '';
	if($params['button']['icon_elegant'] && $params['button']['icon_pack'] == 'elegant') {
		$params['button']['icon'] = $params['button']['icon_elegant'];
	}
	if($params['button']['icon_material'] && $params['button']['icon_pack'] == 'material') {
		$params['button']['icon'] = $params['button']['icon_material'];
	}
	if($params['button']['icon_fontawesome'] && $params['button']['icon_pack'] == 'fontawesome') {
		$params['button']['icon'] = $params['button']['icon_fontawesome'];
	}
	if($params['button']['icon_thegem_header'] && $params['button']['icon_pack'] == 'thegem-header') {
		$params['button']['icon'] = $params['button']['icon_thegem_header'];
	}
	if($params['button']['icon_userpack'] && $params['button']['icon_pack'] == 'userpack') {
		$params['button']['icon'] = $params['button']['icon_userpack'];
	}

	$params['loading_animation'] = thegem_check_array_value(array('disabled', 'bounce', 'move-up', 'fade-in', 'fall-perspective', 'scale', 'flip'), $params['loading_animation'], 'move-up');

	if (empty($params['fullwidth_columns'])) {
		$params['fullwidth_columns'] = 5;
	}

	if ($params['sorting'] == 'false') {
		$params['sorting'] = false;
	}

	if ($params['sorting'] == 'true') {
		$params['sorting'] = true;
	}

	$params['orderby'] = $params['orderby'] ? $params['orderby'] : ($params['sorting'] ? 'date' :'menu_order');
	$params['order'] = $params['order'] ? $params['order'] : ($params['sorting'] ? 'DESC' :'ASC');

	switch ($params['layout']) {
		case '1x':
			$params['layout_columns'] = 1;
			break;
		case '2x':
			$params['layout_columns'] = 2;
			break;
		case '3x':
			$params['layout_columns'] = 3;
			break;
		case '4x':
			$params['layout_columns'] = 4;
			break;
		default:
			$params['layout_columns'] = -1;
	}

	$params['items_per_page'] = $params['items_per_page'] ? intval($params['items_per_page']) : 8;
	$params['grid_page'] = 1;

	if ($params['pagination'] == 'more' || $params['pagination'] == 'scroll') {
		if(isset($params['more_count'])) {
			$params['items_per_page'] = intval($params['more_count']);
		}
		if($params['layout_columns'] == -1) {
			$params['layout_columns'] = 5;
		}
		if($params['items_per_page'] == 0) {
			$params['items_per_page'] = $params['layout_columns'] * 2;
		}
		$params['grid_page'] = isset($params['more_page']) ? intval($params['more_page']) : 1;
		if ($params['grid_page'] == 0) {
			$params['grid_page'] = 1;
		}
	}

	wp_enqueue_style('thegem-portfolio');
	wp_enqueue_style('thegem-portfolio-products');
	wp_enqueue_style('thegem-hovers-' . $params['hover']);
	wp_enqueue_style('thegem-animations');

	if ($params['with_filter']) {
		wp_enqueue_script('jquery-dlmenu');
	}

	wp_enqueue_script('isotope-js');
	if ($params['style']  == 'metro') {
		wp_enqueue_script('thegem-isotope-metro');
	} else {
		wp_enqueue_script('thegem-isotope-masonry-custom');
	}
	wp_enqueue_script('thegem-scroll-monitor');
	wp_enqueue_script('thegem-items-animations');
	wp_enqueue_script('thegem-portfolio');
	wp_enqueue_script('thegem-woocommerce');

	$params['portfolio_uid'] = substr( md5(rand()), 0, 7);
	$localize = array_merge(
		array('data' => $params, 'action' => 'product_grid_load_more'),
		array(
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('product_grid_ajax-nonce')
		)
	);
	wp_localize_script('thegem-portfolio', 'portfolio_ajax_'.$params['portfolio_uid'], $localize);

	echo do_shortcode('[product_category_gem per_page="' . $params['items_per_page'] . '" columns="' . $params['layout_columns'] . '" orderby="' . $params['orderby'] . '" category="' . $params['categories'] . '" order="' . $params['order'] . '" thegem_grid_params="' . htmlspecialchars(serialize($params)) . '"]');
}

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
				if($play_on_mobile && !vc_is_page_editable()) {
					$video_data = ' data-settings=\'{"url": "https://www.youtube.com/watch?v='.$video.'", "play_on_mobile": true, "background_play_once": false }\'';
				} else {
					$link = '//www.youtube.com/embed/'.$video.'?playlist='.$video.'&autoplay=1&mute=1&controls=0&playsinline=1&enablejsapi=1&loop=1&fs=0&showinfo=0&autohide=1&iv_load_policy=3&rel=0&disablekb=1&wmode=transparent';
					$video_block = '<iframe id="'.$uniqid.'" class="gem-video-background-iframe" src="'.esc_url($link).'" frameborder="0" muted="muted"></iframe>';
				}
			}
			if($video_type == 'vimeo') {
				if($play_on_mobile && !vc_is_page_editable()) {
					$video_data = ' data-settings=\'{"url": "https://vimeo.com/'.$video.'", "play_on_mobile": true, "background_play_once": false }\'';
				} elseif(empty($play_on_mobile) && !vc_is_page_editable() && !empty(isMobile())) {
					$link = '//player.vimeo.com/video/'.$video.'?autoplay=0&muted=1&controls=0&loop=1&title=0&badge=0&byline=0&autopause=0';
					$video_block = '<iframe id="'.$uniqid.'" class="gem-video-background-iframe" src="'.esc_url($link).'" frameborder="0" muted="muted"></iframe>';
				} else {
					$link = '//player.vimeo.com/video/'.$video.'?autoplay=1&muted=1&controls=0&loop=1&title=0&badge=0&byline=0&autopause=0';
					$video_block = '<iframe id="'.$uniqid.'" class="gem-video-background-iframe" src="'.esc_url($link).'" frameborder="0" muted="muted"></iframe>';
				}
			}
		} else {
			if($play_on_mobile && !vc_is_page_editable()) {
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

/* Acoordion Script Reaplace */
function thegem_vc_base_register_front_js() {
	wp_deregister_script('vc_accordion_script');
	wp_register_script('vc_accordion_script', THEGEM_THEME_URI . '/js/vc-accordion.js', array('jquery'), WPB_VC_VERSION, true);
	wp_register_script('thegem_tabs_script', THEGEM_THEME_URI . '/js/vc-tabs.min.js', array('jquery', 'vc_accordion_script'), WPB_VC_VERSION, true);
	wp_register_style( 'vc_tta_style', vc_asset_url( 'css/js_composer_tta.min.css' ), false, WPB_VC_VERSION );
}
add_action('vc_base_register_front_js', 'thegem_vc_base_register_front_js');

function thegem_instagram_gallery($params) {
	$params = array_merge(
		array(
			'instagram' => '',
			'title' => '',
			'layout' => '3x',
			'fullwidth_columns' => 5,
			'no_gaps' => '',
			'autoscroll' => false,
			'hover' => 'default',
			'effects_enabled' => false,
			'gaps_size' => 42,
			'arrow' => 'portfolio_slider_arrow_small',
		),$params);

	$gap_size = round(intval($params['gaps_size'])/2);

	if (empty($params['fullwidth_columns']))
		$params['fullwidth_columns'] = 5;

	wp_enqueue_style('thegem-portfolio');
	wp_enqueue_style('thegem-hovers-' . $params['hover']);

	wp_enqueue_script('thegem-juraSlider');

	$layout_columns_count = -1;
	if($params['layout'] == '3x')
		$layout_columns_count = 3;

	$layout_fullwidth = false;
	if($params['layout'] == '100%')
		$layout_fullwidth = true;

	$images = thegem_scrape_instagram($params['instagram']);

	$classes = array('gem-instagram-gallery', 'portfolio', 'portfolio-slider', 'hover-title', 'clearfix', 'no-padding', 'col-lg-12', 'col-md-12', 'col-sm-12', 'hover-'.$params['hover']);
	if($layout_fullwidth)
		$classes[] = 'full';
	if($params['no_gaps'])
		$classes[] = 'without-padding';
	if($params['layout'] == '100%')
		$classes[] = 'fullwidth-columns-'.$params['fullwidth_columns'];

	if ($params['effects_enabled']) {
		$classes[] = 'lazy-loading';
		thegem_lazy_loading_enqueue();
	}

	if ($params['arrow'])
		$classes[] = $params['arrow'];
	$classes[] = 'gem-slider-animation-dynamic';
?>

	<?php if(is_array($images) && count($images)) : ?>
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
								<?php foreach ($images as $i_image) : ?>
									<?php include(locate_template('content-instagram-gallery-carousel-item.php')); ?>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<?php
}

function thegem_scrape_instagram( $username ) {

	$username = strtolower( $username );
	$username = str_replace( '@', '', $username );

	if ( false === ( $instagram = get_transient( 'instagram-a5-'.sanitize_title_with_dashes( $username ) ) ) ) {

		$remote = wp_remote_get( 'https://www.instagram.com/'.trim( $username ));
		if ( is_wp_error( $remote ) )
		return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'thegem' ) );

		if ( 200 != wp_remote_retrieve_response_code( $remote ) )
		return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'thegem' ) );

		$shards = explode( 'window._sharedData = ', $remote['body'] );
		$insta_json = explode( ';</script>', $shards[1] );
		$insta_array = json_decode( $insta_json[0], TRUE );

		if ( ! $insta_array )
		return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'thegem' ) );

		if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
			$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
		} else {
		return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'thegem' ) );
		}

		if ( ! is_array( $images ) )
		return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'thegem' ) );

		$instagram = array();

		foreach ( $images as $image_node ) {
		$image = $image_node['node'];
		$image['thumbnail_src'] = preg_replace( '/^https?\:/i', '', $image['thumbnail_src'] );
		$image['display_url'] = preg_replace( '/^https?\:/i', '', $image['display_url'] );

		// handle both types of CDN url
		if ( ( strpos( $image['thumbnail_src'], 's640x640' ) !== false ) ) {
			$image['thumbnail'] = str_replace( 's640x640', 's160x160', $image['thumbnail_src'] );
			$image['small'] = str_replace( 's640x640', 's320x320', $image['thumbnail_src'] );
		} else {
			$urlparts = wp_parse_url( $image['thumbnail_src'] );
			$pathparts = explode( '/', $urlparts['path'] );
			array_splice( $pathparts, 3, 0, array( 's160x160' ) );
			$image['thumbnail'] = '//' . $urlparts['host'] . implode( '/', $pathparts );
			$pathparts[3] = 's320x320';
			$image['small'] = '//' . $urlparts['host'] . implode( '/', $pathparts );
		}

		$image['large'] = $image['thumbnail_src'];

		if ( $image['is_video'] == true ) {
			$type = 'video';
		} else {
			$type = 'image';
		}

		$caption = __( 'Instagram Image', 'thegem' );
		if ( ! empty( $image['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
			$caption = $image['edge_media_to_caption']['edges'][0]['node']['text'];
		}

		$instagram[] = array(
			'description' => $caption,
			'time' => $image['taken_at_timestamp'],
			'comments' => $image['edge_media_to_comment']['count'],
			'likes' => $image['edge_media_preview_like']['count'],
			'thumbnail' => $image['thumbnail'],
			'small' => $image['small'],
			'large' => $image['large'],
			'original' => $image['display_url'],
			'type' => $type
		);
		}

		// do not set an empty transient - should help catch private or empty accounts
		if ( ! empty( $instagram ) ) {
		$instagram = base64_encode( serialize( $instagram ) ); //100% safe - ignore theme check nag
		set_transient( 'instagram-a5-'.sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS*2 ) );
		}
	}

	if ( ! empty( $instagram ) ) {
		return unserialize( base64_decode( $instagram ) );
	} else {
		return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'thegem' ) );
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

	$classes = array('portfolio', 'portfolio-slider', 'products-grid', 'products-slider', 'products', 'clearfix', 'no-padding', 'col-lg-12', 'col-md-12', 'col-sm-12', 'hover-'.$params['hover']);
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

function thegem_product_slider($params) {
	$params = array_merge(
		array(
			'categories' => '',
			'title' => '',
			'layout' => '3x',
			'no_gaps' => false,
			'display_titles' => 'page',
			'hover' => '',
			'background_style' => 'white',
			'title_style' => 'light',
			'item_separator' => false,
			'disable_socials' => false,
			'fullwidth_columns' => '5',
			'effects_enabled' => false,
			'gaps_size' => 42,
			'animation' => 'dynamic',
			'autoscroll' => false,
			'slider_arrow' => 'portfolio_slider_arrow_big',
		),
		$params
	);

	if (empty($params['fullwidth_columns']))
		$params['fullwidth_columns'] = 5;

	wp_enqueue_style('thegem-portfolio');
	wp_enqueue_style('thegem-portfolio-slider');
	wp_enqueue_style('thegem-portfolio-products');
	wp_enqueue_style('thegem-hovers-' . $params['hover']);

	wp_enqueue_script('thegem-juraSlider');
	wp_enqueue_script('thegem-woocommerce');

	if ($params['effects_enabled']) {
		thegem_lazy_loading_enqueue();
	}

	echo do_shortcode('[product_category_gem per_page="-1" columns="4" category="' . $params['categories'] . '" thegem_slider_params="' . htmlspecialchars(serialize($params)) . '"]');
}

function thegem_posts_query_section_render($params, $featured = false) {
	$post_type = $params['query_type'];
	$taxonomy_filter = $manual_selection = $blog_authors = $date_query = [];
	$single_post_id = thegem_templates_init_post() ? thegem_templates_init_post()->ID : get_the_ID();

	if ($params['query_type'] == 'post') {

		if ($featured) {
			if ($params['source'] == 'categories' && !empty($params['select_blog_cat'])) {
				$taxonomy_filter['category'] = $terms = explode(',', $params['select_blog_cat']);
			} else if ($params['source'] == 'tags' && !empty($params['select_blog_tags'])) {
				$taxonomy_filter['post_tag'] = explode(",", $params['select_blog_tags']);
			} else if ($params['source'] == 'posts' && !empty($params['select_blog_posts'])) {
				$manual_selection = explode(",", $params['select_blog_posts']);
			} else if ($params['source'] == 'authors' && !empty($params['select_blog_authors'])) {
				$blog_authors = explode(",", $params['select_blog_authors']);
			} else if ($params['source'] == 'featured' && !empty($params['categories'])) {
				$taxonomy_filter['category'] = $terms = explode(",", $params['categories']);
			}
		} else {
			if ($params['select_blog_categories'] && !empty($params['categories']) && !in_array('0', $params['categories'])) {
				$taxonomy_filter['category'] = $params['categories'];
			}
			if ($params['select_blog_tags'] && !empty($params['tags'])) {
				$taxonomy_filter['post_tag'] = $params['tags'];
			}
			if ($params['select_blog_posts'] && !empty($params['posts'])) {
				$manual_selection = $params['posts'];
			}
			if ($params['select_blog_authors'] && !empty($params['authors'])) {
				$blog_authors = $params['authors'];
			}
		}

		if ($params['exclude_blog_posts_type'] == 'current') {
			$params['exclude_blog_posts'] = [$single_post_id];
		} else if ($params['exclude_blog_posts_type'] == 'term') {
			$params['exclude_blog_posts'] = thegem_get_posts_query_section_exclude_ids($params['exclude_post_terms'], $post_type);
		} else {
			$params['exclude_blog_posts'] = !empty($params['exclude_blog_posts']) ? explode(',', $params['exclude_blog_posts']) : [];
		}
		$exclude = isset($params['exclude_blog_posts']) ? $params['exclude_blog_posts'] : [];

	} else if ($params['query_type'] == 'related'|| $params['query_type'] == 'archive' || $params['query_type'] == 'manual') {

		if ($params['query_type'] == 'related') {
			$post_type = isset($params['taxonomy_related_post_type']) ? $params['taxonomy_related_post_type'] : 'any';
			$taxonomies = $params['taxonomy_related'] = !empty($params['taxonomy_related']) ? explode(',', $params['taxonomy_related']) : [];
			if (!empty($taxonomies)) {
				foreach ($taxonomies as $tax) {
					if ($tax == 'authors') {
						$blog_authors = $params['select_blog_authors'] = array(get_the_author_meta('ID'));
					} else {
						$tax_terms = get_the_terms($single_post_id, $tax);
						if (!empty($tax_terms) && !is_wp_error($tax_terms)) {
							$taxonomy_filter[$tax] = [];
							foreach ($tax_terms as $term) {
								$taxonomy_filter[$tax][] = $term->slug;
							}
						}
					}
				}
			}
			$params['related_tax_filter'] = $taxonomy_filter;
		} else if ($params['query_type'] == 'archive') {
			$post_type = $params['archive_post_type'] = get_post_type() == 'thegem_templates' ? 'post' : get_post_type();

			if(get_post_type() == 'thegem_templates') {
				$post_id = get_the_ID();
				$editor_post_id = $post_id;
				$preview_settings = get_post_meta($editor_post_id, 'thegem_template_preview_settings', true);
				if(!empty($preview_settings)) {
					$preview_tax = empty($preview_settings['demo_tax']) ? 'category' : $preview_settings['demo_tax'];
					if(!empty($preview_settings['demo_term_id'])) {
						$preview_term_id = $preview_settings['demo_term_id'];
						$preview_term = get_term($preview_term_id);
						if(!empty($preview_term) && !is_wp_error($preview_term)) {
							$obj = $preview_term;
							$taxonomy_filter[$preview_tax] = array($obj->slug);
							$preview_taxonomy = get_taxonomy($preview_tax);
							$post_type = !empty($preview_taxonomy->object_type) ? $preview_taxonomy->object_type[0] : $post_type;
						}
					}
				}
			}

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
		} else {
			$post_type = 'any';
			$manual_selection = $params['select_posts_manual'] = !empty($params['select_posts_manual']) ? explode(',', $params['select_posts_manual']) : [];
		}

		if ($params['exclude_posts_manual_type'] == 'current') {
			$params['exclude_posts_manual'] = [$single_post_id];
		} else if ($params['exclude_posts_manual_type'] == 'term') {
			$params['exclude_posts_manual'] = thegem_get_posts_query_section_exclude_ids($params['exclude_any_terms'], $post_type);
		} else {
			$params['exclude_posts_manual'] = !empty($params['exclude_posts_manual']) ? explode(',', $params['exclude_posts_manual']) : [];
		}

		$exclude = $params['exclude_posts_manual'];
		$params['with_filter'] = '';

	} else {

		$source_post_type = $params['source_post_type_' . $post_type] = !empty($params['source_post_type_' . $post_type]) ? explode(',', $params['source_post_type_' . $post_type]) : [];
		foreach ($source_post_type as $source) {
			if ($source == 'all') {

			} else if ($source == 'manual') {
				$manual_selection = $params['source_post_type_' . $post_type . '_manual'] = !empty($params['source_post_type_' . $post_type . '_manual']) ? explode(',', $params['source_post_type_' . $post_type . '_manual']) : [];
			} else {
				$tax_terms = $params['source_post_type_' . $post_type . '_tax_' . $source] = !empty($params['source_post_type_' . $post_type . '_tax_' . $source]) ? explode(',', $params['source_post_type_' . $post_type . '_tax_' . $source]) : [];
				if (!empty($tax_terms)) {
					$taxonomy_filter[$source] = $tax_terms;
				}
			}
		}

		if (isset($params['exclude_' . $post_type . '_manual_type']) && $params['exclude_' . $post_type . '_manual_type'] == 'current') {
			$params['source_post_type_' . $post_type . '_exclude'] = [$single_post_id];
		} else if (isset($params['exclude_' . $post_type . '_manual_type']) && $params['exclude_' . $post_type . '_manual_type'] == 'term') {
			$params['source_post_type_' . $post_type . '_exclude'] = thegem_get_posts_query_section_exclude_ids($params['exclude_' . $post_type . '_terms'], $post_type);
		} else {
			$params['source_post_type_' . $post_type . '_exclude'] = !empty($params['source_post_type_' . $post_type . '_exclude']) ? explode(',', $params['source_post_type_' . $post_type . '_exclude']) : [];
		}

		$exclude = $params['source_post_type_' . $post_type . '_exclude'];
	}

	return [
		"params" => $params,
		"post_type" => $post_type,
		"taxonomy_filter" => $taxonomy_filter,
		"manual_selection" => $manual_selection,
		"blog_authors" => $blog_authors,
		"date_query" => $date_query,
		"exclude" => $exclude,
	];
}

if (!function_exists('thegem_get_posts_query_section_exclude_ids')) {
	function thegem_get_posts_query_section_exclude_ids($terms, $post_type) {
		$exclude_ids = [];
		if (!empty($terms)) {
			$exclude_terms = explode(',', $terms);
			foreach ($exclude_terms as $id) {
				$id = str_replace(' ', '', $id);
				$arr = explode("|", $id);
				$term = get_term_by('id', $arr[1], $arr[0]);

				$args = array(
					'post_type' => $post_type,
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'fields' => 'ids',
					'tax_query' => array(
						array(
							'taxonomy' => $term->taxonomy,
							'field' => 'term_id',
							'terms' => $term->term_id,
						),
					),
				);
				$wp_query_result = new WP_Query($args);
				$post_ids = !empty($wp_query_result->posts) ? $wp_query_result->posts : array();
				$exclude_ids = array_unique(array_merge($exclude_ids, $post_ids));
			}
		}

		return $exclude_ids;
	}
}

// Print Blog & News Extended Grid
function thegem_news_grid($params) {

	if($params['skin_source'] === 'builder') {
		if(empty($params['loop_builder'])) {
			echo '<div class="bordered-box centered-box styled-subtitle">'.esc_html__('Please select loop item template', 'thegem').'</div>';
			return ;
		}
		$params['layout'] = isset($params['layout_builder']) ? $params['layout_builder'] : '';
		$params['columns_desktop'] = isset($params['columns_desktop_builder']) ? $params['columns_desktop_builder'] : '';
		$params['columns_tablet'] = isset($params['columns_tablet_builder']) ? $params['columns_tablet_builder'] : '';
		$params['columns_mobile'] = isset($params['columns_mobile_builder']) ? $params['columns_mobile_builder'] : '';
		$params['ignore_highlights'] = '1';
		if($params['layout_builder'] === 'list') {
			$params['version'] = 'list';
			$params['columns_desktop'] = '1x';
			$params['columns_tablet'] = '1x';
			$params['columns_mobile'] = '1x';
		}
		$equal_height = !empty($params['loop_equal_height']) && $params['layout_builder'] === 'justified';
	}

	if ($params['disable_socials'] || !thegem_get_option('show_social_icons')) {
		$params['disable_socials'] = true;
	}

	if ($params['display_titles'] == 'hover') {
		$params['item_background_color'] = '';
		$params['item_post_bottom_border_color'] = '';
	}

	if ($params['version'] == 'default' || ($params['display_titles'] == 'hover' && ($params['hover'] == 'horizontal-sliding' || $params['hover'] == 'gradient' || $params['hover'] == 'circular'))) {
		$params['item_post_categories_background_color'] = '';
	}

	$params['button']['icon'] = '';
	if (isset($params['button']['icon_elegant']) && $params['button']['icon_pack'] == 'elegant') {
		$params['button']['icon'] = $params['button']['icon_elegant'];
	}
	if (isset($params['button']['icon_material']) && $params['button']['icon_pack'] == 'material') {
		$params['button']['icon'] = $params['button']['icon_material'];
	}
	if (isset($params['button']['icon_fontawesome']) && $params['button']['icon_pack'] == 'fontawesome') {
		$params['button']['icon'] = $params['button']['icon_fontawesome'];
	}
	if (isset($params['button']['icon_thegem_header']) && $params['button']['icon_pack'] == 'thegem-header') {
		$params['button']['icon'] = $params['button']['icon_thegem_header'];
	}
	if (isset($params['button']['icon_userpack']) && $params['button']['icon_pack'] == 'userpack') {
		$params['button']['icon'] = $params['button']['icon_userpack'];
	}

	$params['loading_animation'] = thegem_check_array_value(array('disabled', 'bounce', 'move-up', 'fade-in', 'fall-perspective', 'scale', 'flip'), $params['loading_animation'], 'move-up');
	$params['pagination'] = thegem_check_array_value(array('normal', 'more', 'scroll', 'disable'), $params['pagination'], 'normal');

	$gap_size = isset($params['gaps_size']) && $params['gaps_size'] != '' ? round(intval($params['gaps_size'])) : 0;
	$gap_size_tablet = isset($params['gaps_size_tablet']) && $params['gaps_size_tablet'] != '' ? round(intval($params['gaps_size_tablet'])) : null;
	$gap_size_mobile = isset($params['gaps_size_mobile']) && $params['gaps_size_mobile'] != '' ? round(intval($params['gaps_size_mobile'])) : null;

	if (empty($params['columns_100']))
		$params['columns_100'] = 5;

	if ($params['sorting'] == 'false')
		$params['sorting'] = false;

	if ($params['sorting'] == 'true')
		$params['sorting'] = true;

	wp_enqueue_style('thegem-portfolio');
	wp_enqueue_style('thegem-news-grid');

	$version = $params['version'];
	if ($params['version'] == 'list') {
		$version = 'new';
		$params['layout'] = 'justified';
		$params['columns_desktop'] = $params['columns_desktop_list'];
		$params['columns_tablet'] = $params['columns_tablet_list'];
		$params['display_titles'] = 'page';
		$params['ignore_highlights'] = '1';
	}

	if ($params['layout'] == 'creative') {
		$params['ignore_highlights'] = '1';
	}

	switch ($params['version']) {
		case 'default':
			if ($params['display_titles'] == 'hover') {
				$hover_effect = 'default-' . $params['hover'];
				wp_enqueue_style('thegem-news-grid-version-default-hovers-' . $params['hover']);
			} else {
				$hover_effect = $params['hover'];
				wp_enqueue_style('thegem-hovers-' . $params['hover']);
				wp_enqueue_style('thegem-news-grid-hovers');
			}
			break;

		case 'new':
			$hover_effect = 'new-' . $params['hover'];
			wp_enqueue_style('thegem-news-grid-version-new-hovers-' . $params['hover']);
			break;

		case 'list':
			$hover_effect = 'list-' . $params['image_hover_effect_list'];
			wp_enqueue_style('thegem-news-grid-version-list-hovers-' . $params['image_hover_effect_list']);
			break;
	}

	if ($params['layout'] !== 'creative' && ($params['layout'] !== 'justified' || $params['ignore_highlights'] !== '1')) {
		if ($params['layout']  == 'metro') {
			wp_enqueue_script('thegem-isotope-metro');
		} else {
			wp_enqueue_script('thegem-isotope-masonry-custom');
		}
	}

	if ($params['loading_animation'] !== 'disabled') {
		wp_enqueue_style('thegem-animations');
		wp_enqueue_script('thegem-scroll-monitor');
		wp_enqueue_script('thegem-items-animations');
	}

	wp_enqueue_script('thegem-portfolio-grid-extended');

	$widget_uid = $params['portfolio_uid'];

	if (!empty($params['categories'])) {
		$params['categories'] = explode(',', $params['categories']);
	} else {
		$params['categories'] = ['0'];
	}
	if (!empty($params['tags'])) {
		$params['tags'] = explode(',', $params['tags']);
	}
	if (!empty($params['posts'])) {
		$params['posts'] = explode(',', $params['posts']);
	}
	if (!empty($params['authors'])) {
		$params['authors'] = explode(',', $params['authors']);
	}

	$is_posts_archive = $params['query_type'] == 'archive' && (thegem_get_template_type(get_the_ID()) === 'blog-archive' || is_category() || is_tag() || is_author()  || is_tax() || is_post_type_archive() || is_search());

	extract(thegem_posts_query_section_render($params));

	if(is_search()) {
		$post_type = thegem_get_search_post_types_array();
		$settings['search_page'] = 1;
	}

	$grid_uid = $is_posts_archive ? '' : $widget_uid;
	$grid_uid_url = $is_posts_archive ? '' : $widget_uid.'-';

	$style_uid = substr(md5(rand()), 0, 7);

	if ($params['sorting'] && $params['filter_type'] == 'default' && $params['filter_style'] == 'buttons') {
		$params['orderby'] = 'date';
	} else if ($params['orderby'] == 'default') {
		if ($params['query_type'] == 'archive') {
			$params['orderby'] = '';
		} else if ($post_type == 'post') {
			$params['orderby'] = 'menu_order date';
		} else {
			$params['orderby'] = 'date';
		}
	}

	if ($params['sorting'] && $params['filter_type'] == 'default' && $params['filter_style'] == 'buttons') {
		$params['order'] = 'desc';
	} else if ($params['order'] == 'default') {
		if ($params['query_type'] == 'archive') {
			$params['order'] = '';
		} else {
			$params['order'] = 'desc';
		}
	}

	if ($post_type == 'any' || is_array($post_type)) {
		$meta_list = select_theme_options_custom_fields_all();
	} else {
		$meta_list = select_theme_options_custom_fields($post_type);
	}
	if (thegem_is_plugin_active('advanced-custom-fields/acf.php') || thegem_is_plugin_active('advanced-custom-fields-pro/acf.php')){
		foreach (thegem_cf_get_acf_plugin_groups() as $gr){
			$meta_list = array_merge($meta_list, thegem_cf_get_acf_plugin_fields_by_group($gr));
		}
	}
	$meta_list = array_flip($meta_list);

	if ($params['search_by'] == 'meta') {
		$params['search_by'] = array_keys($meta_list);
	}

	if (!empty($params['image_ratio_default'])) {
		$params['image_aspect_ratio'] = 'custom';
		$params['image_ratio_custom'] = $params['image_ratio_default'];
	}

	$localize = array(
		'data' => $params,
		'action' => 'blog_grid_extended_load_more',
		'url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('portfolio_ajax-nonce')
	);
	wp_localize_script('thegem-portfolio-grid-extended', 'thegem_portfolio_ajax_' . $style_uid, $localize);

	$inlineStyles = '';
	$gridSelector = '.portfolio.news-grid#style-' . $style_uid;
	$gridSelectorSkeleton = '.preloader#style-preloader-' . $style_uid;
	$itemSelector = $gridSelector . ' .portfolio-item';
	$captionSelector = $itemSelector . ' .caption';
	$onPageCaptionSelector = $itemSelector . ' .wrap > .caption';
	$onHoverCaptionSelector = $itemSelector . ' .image .overlay .caption';

	if (isset($gap_size)) {
		$inlineStyles .= $gridSelector . ' .portfolio-item:not(.size-item), ' . $gridSelectorSkeleton . ' .portfolio-item { padding: calc(' . $gap_size . 'px/2) !important; }' .
			$gridSelector . ' .portfolio-item.size-item { padding: 0 calc(' . $gap_size . 'px/2) !important; }' .
			$gridSelector . ' .portfolio-row, ' . $gridSelectorSkeleton . ' .skeleton-posts.portfolio-row { margin: calc(-' . $gap_size . 'px/2); }' .
			$gridSelector . '.fullwidth-columns .portfolio-row { margin: calc(-' . $gap_size . 'px/2) 0; }' .
			$gridSelector . ' .fullwidth-block:not(.no-paddings) { padding-left: ' . $gap_size . 'px; padding-right: ' . $gap_size . 'px; }' .
			$gridSelector . ' .fullwidth-block .portfolio-row { padding-left: calc(' . $gap_size . 'px/2); padding-right: calc(' . $gap_size . 'px/2); }' .
			$gridSelector . ' .fullwidth-block .portfolio-top-panel { padding-left: ' . $gap_size . 'px; padding-right: ' . $gap_size . 'px; }' .
			$gridSelector . '.fullwidth-columns .with-filter-sidebar .filter-sidebar { padding-left: ' . $gap_size . 'px; }' .
			$gridSelector . '.list-style.with-divider .portfolio-item .wrap:before { top: calc(-' . $gap_size . 'px/2); }';
	}
	if (isset($gap_size_tablet)) {
		$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .portfolio-item:not(.size-item), ' . $gridSelectorSkeleton . ' .portfolio-item { padding: calc(' . $gap_size_tablet . 'px/2) !important; }' .
			$gridSelector . ' .portfolio-item.size-item { padding: 0 calc(' . $gap_size_tablet . 'px/2) !important; }' .
			$gridSelector . ' .portfolio-row, ' . $gridSelectorSkeleton . ' .skeleton-posts.portfolio-row { margin: calc(-' . $gap_size_tablet . 'px/2); }' .
			$gridSelector . '.fullwidth-columns .portfolio-row { margin: calc(-' . $gap_size_tablet . 'px/2) 0; }' .
			$gridSelector . ' .fullwidth-block:not(.no-paddings) { padding-left: ' . $gap_size_tablet . 'px; padding-right: ' . $gap_size_tablet . 'px; }' .
			$gridSelector . ' .fullwidth-block .portfolio-row { padding-left: calc(' . $gap_size_tablet . 'px/2); padding-right: calc(' . $gap_size_tablet . 'px/2); }' .
			$gridSelector . ' .fullwidth-block .portfolio-top-panel { padding-left: ' . $gap_size_tablet . 'px; padding-right: ' . $gap_size_tablet . 'px; }' .
			$gridSelector . '.fullwidth-columns .with-filter-sidebar .filter-sidebar { padding-left: ' . $gap_size_tablet . 'px; }' .
			$gridSelector . '.list-style.with-divider .portfolio-item .wrap:before { top: calc(-' . $gap_size_tablet . 'px/2); }}';
	}
	if (isset($gap_size_mobile)) {
		$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .portfolio-item:not(.size-item), ' . $gridSelectorSkeleton . ' .portfolio-item { padding: calc(' . $gap_size_mobile . 'px/2) !important; }' .
			$gridSelector . ' .portfolio-item.size-item { padding: 0 calc(' . $gap_size_mobile . 'px/2) !important; }' .
			$gridSelector . ' .portfolio-row, ' . $gridSelectorSkeleton . ' .skeleton-posts.portfolio-row { margin: calc(-' . $gap_size_mobile . 'px/2); }' .
			$gridSelector . '.fullwidth-columns .portfolio-row { margin: calc(-' . $gap_size_mobile . 'px/2) 0; }' .
			$gridSelector . ' .fullwidth-block:not(.no-paddings) { padding-left: ' . $gap_size_mobile . 'px; padding-right: ' . $gap_size_mobile . 'px; }' .
			$gridSelector . ' .fullwidth-block .portfolio-row { padding-left: calc(' . $gap_size_mobile . 'px/2); padding-right: calc(' . $gap_size_mobile . 'px/2); }' .
			$gridSelector . ' .fullwidth-block .portfolio-top-panel { padding-left: ' . $gap_size_mobile . 'px; padding-right: ' . $gap_size_mobile . 'px; }' .
			$gridSelector . '.fullwidth-columns .with-filter-sidebar .filter-sidebar { padding-left: ' . $gap_size_mobile . 'px; }' .
			$gridSelector . '.list-style.with-divider .portfolio-item .wrap:before { top: calc(-' . $gap_size_mobile . 'px/2); }}';
	}
	if (($params['with_filter'] || $params['sorting']) && ($params['columns_desktop'] == '100%' || $params['fullwidth_section_sorting'])) {
		if (isset($gap_size) && $gap_size < 21) {
			$inlineStyles .= $gridSelector . ' .fullwidth-block .portfolio-top-panel:not(.gem-sticky-block), ' .
				$gridSelector . ' .portfolio-item.not-found .found-wrap { padding-left: 21px !important; padding-right: 21px !important; }' .
				$gridSelector . ' .with-filter-sidebar .filter-sidebar { padding-left: 21px !important; }';
		}
		if (isset($gap_size_tablet) && $gap_size_tablet < 21) {
			$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .fullwidth-block .portfolio-top-panel:not(.gem-sticky-block), ' .
				$gridSelector . ' .portfolio-item.not-found .found-wrap { padding-left: 21px !important; padding-right: 21px !important; }' .
				$gridSelector . ' .with-filter-sidebar .filter-sidebar { padding-left: 21px !important; }}';
		}
		if (isset($gap_size_mobile) && $gap_size_mobile < 21) {
			$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .fullwidth-block .portfolio-top-panel:not(.gem-sticky-block), ' .
				$gridSelector . ' .portfolio-item.not-found .found-wrap { padding-left: 21px !important; padding-right: 21px !important; }' .
				$gridSelector . ' .with-filter-sidebar .filter-sidebar { padding-left: 21px !important; }}';
		}
	}
	if (isset($params['image_size']) && $params['image_size'] == 'full' && !empty($params['image_ratio'])) {
		$inlineStyles .= $itemSelector . ':not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: ' . esc_attr($params['image_ratio']) . ' !important; height: auto; }';
	}
	if (isset($params['image_size']) && $params['image_size'] == 'default' && !empty($params['image_ratio_default'])) {
		$inlineStyles .= $itemSelector . ':not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: ' . esc_attr($params['image_ratio_default']) . ' !important; height: auto; }';
	}
	if (!empty($params['image_height']) && (!isset($params['image_size']) || $params['image_size'] == 'default')) {
		$inlineStyles .= $itemSelector . ':not(.double-item) .image-inner { height: ' . esc_attr($params['image_height']) . 'px !important; padding-bottom: 0 !important; aspect-ratio: initial !important; }';
		$inlineStyles .= $itemSelector . ':not(.double-item) .gem-simple-gallery .gem-gallery-item a { height: ' . esc_attr($params['image_height']) . 'px !important; }';
	}
	if (!empty($params['item_hover_background_color'])) {
		$inlineStyles .= $itemSelector . ' .image .overlay:before { background: ' . esc_attr($params['item_hover_background_color']) . ' !important; }';
		$inlineStyles .= $itemSelector . ' .image .overlay-circle { background: ' . esc_attr($params['item_hover_background_color']) . ' !important; }';
		$inlineStyles .= $itemSelector . ' .image .gem-simple-gallery .gem-gallery-item a:before { background: ' . esc_attr($params['item_hover_background_color']) . ' !important; }';
	}
	if (!empty($params['item_background_color'])) {
		$inlineStyles .= $onPageCaptionSelector . ' { background-color: ' . esc_attr($params['item_background_color']) . ' !important; }';
		$inlineStyles .= $itemSelector . ' .wrap { background-color: ' . esc_attr($params['item_background_color']) . ' !important; }';
	}
	if (!empty($params['title_transform'])) {
		$inlineStyles .= $captionSelector . ' .title span { text-transform: ' . esc_attr($params['title_transform']) . ' !important; }';
	}
	if (isset($params['title_letter_spacing'])) {
		$inlineStyles .= $captionSelector . ' .title span { letter-spacing: ' . esc_attr($params['title_letter_spacing']) . 'px !important; }';
	}
	if (!empty($params['item_post_title_color'])) {
		$inlineStyles .= $captionSelector . ' .title { color: ' . esc_attr($params['item_post_title_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .title > * { color: ' . esc_attr($params['item_post_title_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .title a { color: ' . esc_attr($params['item_post_title_color']) . ' !important; }';
	}
	if (!empty($params['item_post_title_color_hover'])) {
		$inlineStyles .= $gridSelector . ':not(.disabled-hover) .portfolio-item:hover .title { color: ' . esc_attr($params['item_post_title_color_hover']) . ' !important; }';
		$inlineStyles .= $gridSelector . ':not(.disabled-hover) .portfolio-item:hover .title > * { color: ' . esc_attr($params['item_post_title_color_hover']) . ' !important; }';
		$inlineStyles .= $gridSelector . ':not(.disabled-hover) .portfolio-item:hover .title a { color: ' . esc_attr($params['item_post_title_color_hover']) . ' !important; }';
		$inlineStyles .= $gridSelector . '.disabled-hover .title a:hover { color: ' . esc_attr($params['item_post_title_color_hover']) . ' !important; }';
	}
	if (!empty($params['item_post_author_color'])) {
		$inlineStyles .= $captionSelector . ' .author .author-name { color: ' . esc_attr($params['item_post_author_color']) . ' !important; }';
	}
	if (!empty($params['item_post_date_color'])) {
		$inlineStyles .= $captionSelector . ' .post-author-date-separator { color: ' . esc_attr($params['item_post_date_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .post-date { color: ' . esc_attr($params['item_post_date_color']) . ' !important; }';
	}
	if (!empty($params['item_post_categories_background_color'])) {
		$inlineStyles .= $onHoverCaptionSelector . ' .info { background-color: ' . esc_attr($params['item_post_categories_background_color']) . ' !important; }';
	}
	if (!empty($params['item_post_categories_color'])) {
		$inlineStyles .= $captionSelector . ' .info .sep { color: ' . esc_attr($params['item_post_categories_color']) . ' !important; border-left-color: ' . esc_attr($params['item_post_categories_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .info a { color: ' . esc_attr($params['item_post_categories_color']) . ' !important; }';
	}
	if (!empty($params['item_post_excerpt_color'])) {
		$inlineStyles .= $captionSelector . ' .description { color: ' . esc_attr($params['item_post_excerpt_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .description .subtitle { color: ' . esc_attr($params['item_post_excerpt_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .grid-post-meta .comments-link a { color: ' . esc_attr($params['item_post_excerpt_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .grid-post-meta .zilla-likes { color: ' . esc_attr($params['item_post_excerpt_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .grid-post-meta .comments-link + .post-meta-likes { border-left-color: ' . esc_attr($params['item_post_excerpt_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .grid-post-meta .grid-post-share + .grid-post-meta-comments-likes .comments-link { border-left-color: ' . esc_attr($params['item_post_excerpt_color']) . ' !important; }';
	}
	if (!empty($params['item_post_comments_icon_color'])) {
		$inlineStyles .= $captionSelector . ' .grid-post-meta .comments-link a i { color: ' . esc_attr($params['item_post_comments_icon_color']) . ' !important; }';
	}
	if (!empty($params['item_post_likes_icon_color'])) {
		$inlineStyles .= $captionSelector . ' .grid-post-meta .zilla-likes i { color: ' . esc_attr($params['item_post_likes_icon_color']) . ' !important; }';
	}
	if (!empty($params['item_post_share_icon_color'])) {
		$inlineStyles .= $captionSelector . ' .grid-post-share .icon { color: ' . esc_attr($params['item_post_share_icon_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .grid-post-share .icon i { color: ' . esc_attr($params['item_post_share_icon_color']) . ' !important; }';
	}
	if (!empty($params['item_post_share_social_icon_color'])) {
		$inlineStyles .= $captionSelector . ' .socials-sharing a.socials-item .socials-item-icon { color: ' . esc_attr($params['item_post_share_social_icon_color']) . ' !important; }';
	}
	if (!empty($params['item_post_hover_icon_color'])) {
		$inlineStyles .= $captionSelector . ' .portfolio-icons a i { color: ' . esc_attr($params['item_post_hover_icon_color']) . ' !important; }';
		$inlineStyles .= $itemSelector . ' .image .gem-simple-gallery .gem-gallery-item a:after { color: ' . esc_attr($params['item_post_hover_icon_color']) . ' !important; }';
	}
	if (!empty($params['item_post_bottom_border_color'])) {
		$inlineStyles .= $itemSelector . ' .wrap > .caption { border-bottom-color: ' . esc_attr($params['item_post_bottom_border_color']) . ' !important; }';
	}
	if (!empty($params['truncate_titles'])) {
		$inlineStyles .= $captionSelector . ' .title span, '. $captionSelector . ' .title a { white-space: initial; max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($params['truncate_titles']) . '; line-clamp: ' . esc_attr($params['truncate_titles']) . '; -webkit-box-orient: vertical; }';
	}
	if (!empty($params['truncate_description'])) {
		$inlineStyles .= $itemSelector . ' .caption .description { max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($params['truncate_description']) . '; line-clamp: ' . esc_attr($params['truncate_description']) . '; -webkit-box-orient: vertical; }';
	}
	if (!empty($params['caption_container_alignment'])) {
		$inlineStyles .= $captionSelector . ' { text-align: ' . esc_attr($params['caption_container_alignment']) . ' !important; }';
	}

	if ($params['version'] == 'list') {
		if (!empty($params['caption_column_width'])) {
			$inlineStyles .= $gridSelector .'.list-style .portfolio-set .portfolio-item .wrap > .caption { width: ' . esc_attr($params['caption_column_width']) . '%; }';
		}
		if (!empty($params['caption_horizontal_alignment'])) {
			$inlineStyles .= $itemSelector . ' .wrap > .caption { text-align: ' . esc_attr($params['caption_horizontal_alignment']) . ' !important; }';
		}
		if (!empty($params['caption_vertical_alignment'])) {
			$inlineStyles .= $itemSelector . ' .wrap > .caption { justify-content: ' . esc_attr($params['caption_vertical_alignment']) . ' !important; }';
		}
		if (!empty($params['item_background_color_list'])) {
			$inlineStyles .= $itemSelector . ' .wrap > .caption { background-color: ' . esc_attr($params['item_background_color_list']) . ' !important; }';
		}
		if (!empty($params['item_divider_color_list'])) {
			$inlineStyles .= $gridSelector . '.with-divider .portfolio-item .wrap:before { border-color: ' . esc_attr($params['item_divider_color_list']) . ' !important; }';
		}
	}
	foreach (['top', 'bottom', 'left', 'right'] as $position) {
		if (isset($params['caption_padding_' . $position]) && $params['caption_padding_' . $position] != '') {
			$inlineStyles .= $gridSelector .' .portfolio-set .portfolio-item .wrap > .caption { padding-' . $position . ': ' . intval( $params['caption_padding_' . $position] ) . 'px !important; }';
		}
		if (isset($params['caption_padding_' . $position . '_tablet']) && $params['caption_padding_' . $position . '_tablet'] != '') {
			$inlineStyles .= '@media (max-width: 991px) { ' . $gridSelector .' .portfolio-set .portfolio-item .wrap > .caption { padding-' . $position . ': ' . intval( $params['caption_padding_' . $position . '_tablet'] ) . 'px !important; }}';
		}
		if (isset($params['caption_padding_' . $position . '_mobile']) && $params['caption_padding_' . $position . '_mobile'] != '') {
			$inlineStyles .= '@media (max-width: 767px) { ' . $gridSelector .' .portfolio-set .portfolio-item .wrap > .caption { padding-' . $position . ': ' . intval( $params['caption_padding_' . $position . '_mobile'] ) . 'px !important; }}';
		}
	}

	if (!empty($inlineStyles)) {
		echo '<style>'.$inlineStyles.'</style>';
	}

	if ($params['with_filter'] && $params['filter_type'] == 'default') {
		$params['filters_sticky'] = $params['default_filters_sticky'];
		$params['filters_sticky_color'] = $params['default_filters_sticky_color'];

		if (isset($taxonomy_filter[$params['filter_by']])) {
			$terms = $taxonomy_filter[$params['filter_by']];
			foreach ($terms as $key => $term) {
				$terms[$key] = get_term_by('slug', $term, $params['filter_by'] );
				if (!$terms[$key]) {
					unset($terms[$key]);
				}
			}
		} else {
			$terms = get_terms(array(
				'taxonomy' => $params['filter_by'],
				'orderby' => $params['attribute_order_by'],
			));
		}
	}

	echo thegem_portfolio_news_render_styles($params, $gridSelector, $gridSelectorSkeleton);

	$taxonomy_filter_current = $taxonomy_filter;
	$categories_filter = [];
	$taxonomies_list = array_flip(select_post_type_taxonomies($post_type));
	if (isset($_GET[$grid_uid_url . 'category'])) {
		$categories_filter = explode(",", $_GET[$grid_uid_url . 'category']);
		$taxonomy_filter_current['category'] = $categories_filter;
	} else if ($params['sorting'] && $params['filter_type'] == 'default' && !$params['filter_show_all'] && $params['filter_by'] == 'category' && array_key_exists($params['filter_by'], $taxonomies_list)) {
		foreach ($terms as $term) {
			$categories_filter = [$term->slug];
			break;
		}
		$taxonomy_filter_current['category'] = $categories_filter;
	}

	$page = 1;
	$next_page = 0;

	if (isset($_GET[$grid_uid_url . 'page'])) {
		$page = $_GET[$grid_uid_url . 'page'];
	}

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

	$selected_orderby = $selected_order = 'default';

	if (!empty($_GET[$grid_uid_url . 'orderby'])) {
		$orderby = $_GET[$grid_uid_url . 'orderby'];
		$selected_orderby = $orderby;
	} else {
		$orderby = $params['orderby'];
	}

	if (!empty($_GET[$grid_uid_url . 'order'])) {
		$order = $_GET[$grid_uid_url . 'order'];
		if ($params['filter_type'] == 'extended' && $params['sorting_type'] == 'extended') {
			$selected_order = $order;
		} else {
			if ($selected_orderby != 'default') {
				$selected_orderby .= '-' . $order;
			}
		}
	} else {
		$order = $params['order'];
	}

	$portfolios_filters_tax_url = $portfolios_filters_meta_url = $meta_filter_current = [];
	foreach($_GET as $key => $value) {
		if (strpos($key, $grid_uid_url . 'filter_tax_') === 0) {
			$attr = str_replace($grid_uid_url . 'filter_tax_', '', $key);
			$portfolios_filters_tax_url['tax_' . $attr] = $taxonomy_filter_current[$attr] = explode(",", $value);
		} else if (strpos($key, $grid_uid_url . 'filter_meta_') === 0) {
			$attr = str_replace($grid_uid_url . 'filter_meta_', '', $key);
			$portfolios_filters_meta_url['meta_' . $attr] = $meta_filter_current[$attr] = explode(",", $value);
		}
	}
	if (empty($portfolios_filters_tax_url) && $params['with_filter'] && $params['filter_type'] == 'default' && !$params['filter_show_all'] && $params['filter_by'] != 'category' && array_key_exists($params['filter_by'], $taxonomies_list)) {
		$active_tax = '';
		foreach ($terms as $term) {
			$active_tax = $term->slug;
			break;
		}
		$portfolios_filters_tax_url['tax_' . $params['filter_by']] = $taxonomy_filter_current[$params['filter_by']] = [$active_tax];
	}
	$attributes_filter = array_merge($portfolios_filters_tax_url, $portfolios_filters_meta_url);
	if (empty($attributes_filter)) { $attributes_filter = null; }

	$search_current = null;
	if (!empty($_GET[$grid_uid_url . 's'])) {
		$search_current = $_GET[$grid_uid_url . 's'];
	}

	$news_grid_title = $params['title'] ?: '';

	$news_grid_loop = get_thegem_extended_blog_posts($post_type, $taxonomy_filter_current, $meta_filter_current, $manual_selection, $exclude, $blog_authors, $page, $items_on_load, $orderby, $order, $params['offset'], $params['ignore_sticky_posts'], $search_current, $params['search_by'], $date_query);

	if ($news_grid_loop && $news_grid_loop->have_posts() || !empty($categories_filter) || !empty($attributes_filter) || !empty($search_current)) {

		$max_page = ceil(($news_grid_loop->found_posts - intval($params['offset'])) / $items_per_page);

		if ($params['reduce_html_size']) {
			$next_page = $news_grid_loop->found_posts > $items_on_load ? 2 : 0;
			$next_page_pagination = $max_page > $page ? $page + 1 : 0;
		} else {
			$next_page = $next_page_pagination = $max_page > $page ? $page + 1 : 0;
		}

		$item_classes = get_thegem_portfolio_render_item_classes($params);
		$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params);

		if ($params['columns_desktop'] == '100%' || (($params['ignore_highlights'] !== '1' || in_array($params['layout'], ['masonry', 'metro'])) && $params['skeleton_loader'] !== '1')) {
			$spin_class = 'preloader-spin';
			if ($params['ajax_preloader_type'] == 'minimal') {
				$spin_class = 'preloader-spin-new';
			}
			echo apply_filters('thegem_portfolio_preloader_html', '<div id="style-preloader-' . $style_uid . '" class="preloader save-space"><div class="' . $spin_class . '"></div></div>');
		} else if ($params['skeleton_loader'] == '1') { ?>
			<div id="style-preloader-<?php echo esc_attr($style_uid); ?>" class="preloader save-space">
				<div class="skeleton">
					<div class="skeleton-posts portfolio-row">
						<?php for ($x = 0; $x < $news_grid_loop->post_count; $x++) {
							echo thegem_extended_blog_render_item($params, $item_classes);
						} ?>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="portfolio-preloader-wrapper<?php echo !empty($params['sidebar_position']) ? ' panel-sidebar-position-' . $params['sidebar_position'] : ''; ?>">
			<?php if ($news_grid_title) { ?>
				<h3 class="title portfolio-title"><?php echo $news_grid_title; ?></h3>
			<?php } ?>

			<?php
			$news_grid_classes = array(
				'portfolio portfolio-grid extended-portfolio-grid news-grid no-padding',
				'portfolio-pagination-' . $params['pagination'],
				'portfolio-style-' . $params['layout'],
				'background-style-' . $params['background_style'],
				'title-style-' . $params['title_style'],
				'hover-' . $hover_effect,
				'title-on-' . $params['display_titles'],
				'caption-position-' . $params['display_titles'],
				'version-' . $version,
				($params['version'] == 'list' ? 'list-style caption-position-'.$params['caption_position_list'] : ''),
				($params['loading_animation'] !== 'disabled' ? 'loading-animation item-animation-' . $params['loading_animation'] : ''),
				($params['loading_animation'] !== 'disabled' && $params['loading_animation_mobile'] == '1' ? 'enable-animation-mobile' : ''),
				($gap_size == 0 ? 'no-gaps' : ''),
				($params['shadowed_container'] == '1' ? 'shadowed-container' : ''),
				($params['columns_desktop'] == '100%' || $params['fullwidth_section_sorting'] == '1' ? 'fullwidth-columns' : ''),
				($params['columns_desktop'] == '100%' ? 'fullwidth-columns-' . $params['columns_100'] : ''),
				($params['display_titles'] == 'hover' ? 'hover-title' : ''),
				($params['layout'] == 'masonry' && $params['columns_desktop'] != '1x' ? 'portfolio-items-masonry' : ''),
				($params['columns_desktop'] != '100%' ? 'columns-' . str_replace("x", "", $params['columns_desktop']) : ''),
				(isset($params['columns_tablet']) ? 'columns-tablet-' . str_replace("x", "", $params['columns_tablet']) : ''),
				(isset($params['columns_mobile']) ? 'columns-mobile-' . str_replace("x", "", $params['columns_mobile']) : ''),
				($params['version'] == 'list' || $params['layout'] == 'creative' || ($params['layout'] == 'justified' && $params['ignore_highlights'] == '1') ? 'disable-isotope' : ''),
				($params['next_page_preloading'] == '1' ? 'next-page-preloading' : ''),
				($params['filter_type'] == 'default' && $params['filters_preloading'] == '1' ? 'filters-preloading' : ''),
				($params['layout'] == 'creative' && $params['scheme_apply_mobiles'] !== '1' ? 'creative-disable-mobile' : ''),
				($params['layout'] == 'creative' && $params['scheme_apply_tablets'] !== '1' ? 'creative-disable-tablet' : ''),
				($params['version'] == 'list' && $params['disable_hover'] == '1' ? 'disabled-hover' : ''),
				($params['version'] == 'list' && $params['blog_show_divider'] == '1' ? 'with-divider' : ''),
				($params['disable_bottom_margin'] == '1' ? 'disable-bottom-margin' : ''),
				(($params['layout'] == 'justified' && isset($params['image_size']) && (($params['image_size'] == 'full' && empty($params['image_ratio'])) || !in_array($params['image_size'], ['full', 'default']))) ? 'full-image' : ''),
				($params['ajax_preloader_type'] == 'minimal' ? 'minimal-preloader' : ''),
				($params['reduce_html_size'] ? 'reduce-size' : ''),
				(!empty($equal_height) ? 'loop-equal-height' : ''),
			);

			$news_grid_classes = apply_filters('news_grid_classes_filter', $news_grid_classes);
			$fw_uniqid = uniqid('fullwidth-block-'); ?>

			<div class="<?php echo implode(' ', $news_grid_classes); ?>"
				 id="style-<?php echo esc_attr($style_uid); ?>"
				 data-style-uid="<?php echo esc_attr($style_uid); ?>"
				 data-portfolio-uid="<?php echo esc_attr($grid_uid); ?>"
				 data-current-page="<?php echo esc_attr($page); ?>"
				 data-per-page="<?php echo $items_per_page; ?>"
				 data-next-page="<?php echo esc_attr($next_page); ?>"
				 data-pages-count="<?php echo esc_attr($max_page); ?>"
				 data-hover="<?php echo $params['hover']; ?>"
				 data-portfolio-filter="<?php echo esc_attr(json_encode($categories_filter)); ?>"
				 data-portfolio-filter-attributes="<?php echo esc_attr(json_encode($attributes_filter)); ?>"
				 data-portfolio-filter-search="<?php echo esc_attr($search_current); ?>">
				<?php
				$search_right = $params['show_search'] && (!$params['with_filter'] || $params['filter_type'] == 'default' || $params['filters_style'] == 'standard');
				$has_right_panel = $params['filter_type'] == 'default' || $params['sorting'] || $search_right;
				$has_meta_filtering = false;
				$selected_shown = false;
				if (!empty($params['show_additional_meta'])) {
					$meta_type = isset($params['additional_meta_type']) ? $params['additional_meta_type'] : 'taxonomies';
					if ($meta_type == 'taxonomies') {
						$meta_taxonomies = isset($params['additional_meta_taxonomies']) ? $params['additional_meta_taxonomies'] : 'category';
						$behavior = isset($params['additional_meta_click_behavior']) ? $params['additional_meta_click_behavior'] : 'filtering';
						$has_tax = array_key_exists($meta_taxonomies, $taxonomies_list);
					} else {
						if ($meta_type == 'details') {
							$term_name = $params['additional_meta_details'];
						} else if ($meta_type == 'custom_fields') {
							$term_name = $params['additional_meta_custom_fields'];
						} else {
							$term_name = $params['additional_meta_custom_fields_acf_' . $meta_type];
						}
						$behavior = $params['additional_meta_click_behavior_meta'];
						$has_tax = array_key_exists($term_name, $meta_list);
					}
					if ($behavior == 'filtering' && $has_tax) {
						$has_meta_filtering = true;
					}
				}
				if ($params['with_filter']) {
					if ($params['filter_type'] == 'default') {
						if (!array_key_exists($params['filter_by'], $taxonomies_list)) {
							$params['with_filter'] = '';
						}
					} else {
						$params['with_filter'] = '';
						$filter_attr = vc_param_group_parse_atts($params['repeater_attributes']);
						foreach ($filter_attr as $index => $item) {
							if (empty($item['attribute_title'])) continue;
							if ($item['attribute_type'] == 'taxonomies') {
								if (empty($item['attribute_taxonomies']) || !array_key_exists($item['attribute_taxonomies'], $taxonomies_list)) continue;
							} else {
								if ($item['attribute_type'] == 'details') {
									$attribute_name = isset($item['attribute_details']) ? $item['attribute_details'] : '';
								} else if ($item['attribute_type'] == 'custom_fields') {
									$attribute_name = isset($item['attribute_custom_fields']) ? $item['attribute_custom_fields'] : '';
								} else {
									$attribute_name = isset($item['attribute_custom_fields_acf_' . $item['attribute_type']]) ? $item['attribute_custom_fields_acf_' . $item['attribute_type']] : '';
								}
								if (empty($attribute_name) || !array_key_exists($attribute_name, $meta_list)) continue;
							}
							$params['with_filter'] = '1';
							break;
						}
					}
				} ?>

				<div class="portfolio-row-outer <?php if ($params['columns_desktop'] == '100%' || $params['fullwidth_section_sorting'] == '1'): ?>fullwidth-block <?= $fw_uniqid; ?> no-paddings<?php endif; ?>">

					<?php if ($params['with_filter'] && $params['filter_type'] == 'extended' && $params['filters_style'] == 'sidebar') { ?>
						<div class="with-filter-sidebar <?php echo $params['sidebar_sticky'] ? 'sticky-sidebar' : ''; ?>">
							<?php if ($params['sidebar_sticky']) {
								wp_enqueue_script('thegem-sticky');
							} ?>
							<div class="filter-sidebar <?php echo $params['sorting'] ? 'left' : ''; ?>">
								<?php include(locate_template(array('gem-templates/portfolios/filters.php'))); ?>
							</div>
							<div class="content">
								<?php } ?>
								<?php if (($params['with_filter'] || $params['sorting'] || $has_meta_filtering)) { ?>
									<div class="portfolio-top-panel filter-type-<?php echo $params['filter_type'];
									echo $params['filter_type'] == 'extended' && $params['filters_style'] == 'sidebar' ? ' sidebar-filter' : '';
									echo (!$params['sorting'] && !$search_right && (!$params['with_filter'] || ($params['filter_type'] == 'extended' && $params['filters_style'] == 'sidebar'))) ? ' selected-only' : '';
									echo $search_right ? ' panel-with-search' : '';
									echo $params['filters_sticky'] ? ' filters-top-sticky' : '';
									echo $params['mobile_dropdown'] && $params['filter_style'] != 'buttons' ? ' filters-mobile-dropdown' : ''; ?>">
										<?php if ($params['filters_sticky']) {
											wp_enqueue_script('thegem-sticky');
										} ?>
										<div class="portfolio-top-panel-row filter-style-<?php echo $params['filter_style']; ?>">
											<?php if ($params['with_filter']) {
												if ($params['filter_type'] == 'default') {
													if (array_key_exists($params['filter_by'], $taxonomies_list)) {
														if ($params['filter_by'] != 'category') {
															$categories_filter = isset($portfolios_filters_tax_url['tax_' . $params['filter_by']]) ? $portfolios_filters_tax_url['tax_' . $params['filter_by']] : [];
														}
														if (!is_wp_error($terms) && count($terms) > 0) { ?>
															<div class="portfolio-top-panel-left <?php echo strtolower($params['attribute_query_type']) == 'and' ? 'multiple' : 'single'; ?>" <?php if ($params['filter_by'] !== 'category') { ?>
																data-filter-by="<?php echo 'tax_' . $params['filter_by']; ?>"
															<?php } ?>>
																<?php
																if ($params['mobile_dropdown'] && $params['filter_style'] !='buttons') { ?>
																	<div class="portfolio-filters portfolio-filters-mobile portfolio-filters-more">
																		<div class="portfolio-filters-more-button title-h6">
																			<div class="portfolio-filters-more-button-name"><?php echo $params['filters_mobile_show_button_text']; ?></div>
																			<span class="portfolio-filters-more-button-arrow"></span>
																		</div>
																		<div class="portfolio-filters-more-dropdown">
																			<?php if ($params['filter_show_all']) { ?>
																				<a href="#" data-filter="*"
																				   class="<?php echo empty($categories_filter) ? 'active' : ''; ?> all title-h6 <?php echo $params['hover_pointer'] ? 'hover-pointer' : ''; ?>">
																					<span <?php echo $params['filter_style'] == 'buttons' ? 'class="light"' : ''; ?>>
																						<?php echo $params['all_text']; ?>
																					</span>
																				</a>
																			<?php }
																			foreach ($terms as $term) {
																				if (isset($params['attribute_click_behavior']) && $params['attribute_click_behavior'] == 'archive_link') {
																					$link = get_term_link($term->slug, $params['filter_by']);
																				} else {
																					$link = '#';
																				} ?>
																				<a href="<?php echo $link; ?>" <?php if ($link == '#') { ?>
																				   data-filter=".<?php echo $term->slug; ?>"
																				   <?php } ?>class="<?php echo in_array($term->slug, $categories_filter) ? 'active' : ''; ?> title-h6 <?php echo $params['hover_pointer'] ? 'hover-pointer' : ''; ?>">
																				<span <?php echo $params['filter_style'] == 'buttons' ? 'class="light"' : ''; ?>>
																					<?php echo $term->name; ?>
																				</span>
																				</a>
																			<?php } ?>
																		</div>
																	</div>
																<?php } ?>
																<div class="portfolio-filters filter-by-<?php echo $params['filter_by']; ?>">
																	<?php if ($params['filter_show_all']) { ?>
																		<a href="#" data-filter="*"
																		   class="<?php echo empty($categories_filter) ? 'active' : ''; ?> all title-h6 <?php echo $params['hover_pointer'] ? 'hover-pointer' : ''; ?>">
																				<span <?php echo $params['filter_style'] == 'buttons' ? 'class="light"' : ''; ?>>
																					<?php echo $params['all_text']; ?>
																				</span>
																		</a>
																	<?php }
																	$i = 0;
																	foreach ($terms as $term) {
																	if ($params['truncate_filters'] && $i == $params['truncate_filters_number']) { ?>
																		<div class="portfolio-filters-more">
																			<div class="portfolio-filters-more-button title-h6">
																				<div class="portfolio-filters-more-button-name <?php echo $params['filter_style'] == 'buttons' ? 'light' : ''; ?>"><?php echo $params['filters_more_button_text']; ?></div>
																				<?php if ($params['filters_more_button_arrow']) { ?>
																					<span class="portfolio-filters-more-button-arrow"></span>
																				<?php } ?>
																			</div>
																			<div class="portfolio-filters-more-dropdown">
																		<?php }
																		if (isset($params['attribute_click_behavior']) && $params['attribute_click_behavior'] == 'archive_link') {
																			$link = get_term_link($term->slug, $params['filter_by']);
																		} else {
																			$link = '#';
																		} ?>
																		<a href="<?php echo $link; ?>" <?php if ($link == '#') { ?>
																		   data-filter=".<?php echo $term->slug; ?>"
																		   <?php } ?>class="<?php echo in_array($term->slug, $categories_filter) ? 'active' : ''; ?> title-h6 <?php echo $params['hover_pointer'] ? 'hover-pointer' : ''; ?>">
																			<?php if (get_option('portfoliosets_' . $term->term_id . '_icon_pack') && get_option('portfoliosets_' . $term->term_id . '_icon')) {
																				echo thegem_build_icon(get_option('portfoliosets_' . $term->term_id . '_icon_pack'), get_option('portfoliosets_' . $term->term_id . '_icon'));
																			} ?>
																			<span <?php echo $params['filter_style'] == 'buttons' ? 'class="light"' : ''; ?>>
																			<?php echo $term->name; ?>
																		</span>
																		</a>
																		<?php if ($params['truncate_filters'] && sizeof($terms) > $params['truncate_filters_number'] && $i == sizeof($terms) - 1) { ?>
																				</div>
																			</div>
																		<?php }
																		$i++;
																	} ?>
																</div>
																<?php if ($params['filter_style'] == 'buttons') {
																	wp_enqueue_script('jquery-dlmenu'); ?>
																	<div class="portfolio-filters-resp <?php echo strtolower($params['attribute_query_type']) == 'and' ? 'multiple' : 'single'; ?>">
																		<button class="menu-toggle dl-trigger"><?php _e('Portfolio filters', 'thegem'); ?><span class="menu-line-1"></span><span class="menu-line-2"></span><span class="menu-line-3"></span></button>
																		<ul class="dl-menu">
																			<?php if ($params['filter_show_all']) { ?>
																				<li>
																					<a href="#" data-filter="*">
																						<?php echo $params['all_text']; ?>
																					</a>
																				</li>
																			<?php } ?>
																			<?php foreach ($terms as $term) { ?>
																				<li>
																					<?php
																					if (isset($params['attribute_click_behavior']) && $params['attribute_click_behavior'] == 'archive_link') {
																						$link = get_term_link($term->slug, $params['filter_by']);
																					} else {
																						$link = '#';
																					} ?>
																					<a href="<?php echo $link; ?>" <?php if ($link == '#') { ?>
																						data-filter=".<?php echo $term->slug; ?>"<?php } ?>>
																						<?php echo $term->name; ?>
																					</a>
																				</li>
																			<?php } ?>
																		</ul>
																	</div>
																<?php }
																if ($has_meta_filtering) {
																	$selected_shown = true;
																	include(locate_template(array('gem-templates/portfolios/selected-filters.php')));
																} ?>
															</div>
														<?php }
													} else { ?>
														<div class="portfolio-top-panel-left"><?php
															if ($has_meta_filtering) {
																$selected_shown = true;
																include(locate_template(array('gem-templates/portfolios/selected-filters.php')));
															} ?>
														</div>
													<?php }
												} else { ?>
													<div class="portfolio-top-panel-left <?php echo esc_attr($params['filter_buttons_standard_alignment']); ?>">
														<?php if ($params['with_filter'] && $params['filters_style'] != 'sidebar') {
															include(locate_template(array('gem-templates/portfolios/filters.php')));
														}
														if (($params['with_filter'] && $params['filters_style'] == 'sidebar') || !$params['with_filter']) {
															$selected_shown = true;
															include(locate_template(array('gem-templates/portfolios/selected-filters.php')));
														} ?>
													</div>
												<?php }
											} else { ?>
												<div class="portfolio-top-panel-left"><?php
//													if ($has_meta_filtering) {
														$selected_shown = true;
														include(locate_template(array('gem-templates/portfolios/selected-filters.php')));
//													} ?>
												</div>
											<?php } ?>
											<?php if ($has_right_panel) { ?>
												<div class="portfolio-top-panel-right">
													<?php if ($params['sorting']) {
														if ($params['filter_type'] == 'default' && $params['filter_style'] == 'buttons') { ?>
															<div class="portfolio-sorting title-h6">
																<div class="orderby light">
																	<label for=""
																		   data-value="date"><?php _e('Date', 'thegem') ?></label>
																	<a href="javascript:void(0);" class="sorting-switcher"
																	   data-current="date"></a>
																	<label for=""
																		   data-value="name"><?php _e('Name', 'thegem') ?></label>
																</div>
																<div class="portfolio-sorting-sep"></div>
																<div class="order light">
																	<label for=""
																		   data-value="DESC"><?php _e('Desc', 'thegem') ?></label>
																	<a href="javascript:void(0);" class="sorting-switcher"
																	   data-current="DESC"></a>
																	<label for=""
																		   data-value="ASC"><?php _e('Asc', 'thegem') ?></label>
																</div>
															</div>
														<?php } else {
															if ($params['filter_type'] == 'extended' && $params['sorting_type'] == 'extended') {
																$repeater_sort = vc_param_group_parse_atts($params['repeater_sort']);
																if (!empty($repeater_sort)) { ?>
																	<div class="portfolio-sorting-select open-dropdown-<?php
																	echo $params['sorting_dropdown_open']; ?>">
																		<div class="portfolio-sorting-select-current">
																			<div class="portfolio-sorting-select-name">
																				<?php
																				if ($selected_orderby == 'default') {
																					echo esc_html($params['sorting_extended_text']);
																				} else {
																					foreach ($repeater_sort as $item) {
																						if (in_array($item['attribute_type'], ['date', 'title', 'price', 'rating', 'popularity'])) {
																							$sort_by = $item['attribute_type'];
																						} else {
																							if ($item['attribute_type'] == 'details') {
																								$sort_by = isset($item['attribute_details']) ? $item['attribute_details'] : '';
																							} else if ($item['attribute_type'] == 'custom_fields') {
																								$sort_by = isset($item['attribute_custom_fields']) ? $item['attribute_custom_fields'] : '';
																							} else if ($item['attribute_type'] == 'manual_key') {
																								$sort_by = isset($item['manual_key_field']) ? $item['manual_key_field'] : '';
																							} else {
																								$sort_by = isset($item['attribute_custom_fields_acf_' . $item['attribute_type']]) ? $item['attribute_custom_fields_acf_' . $item['attribute_type']] : '';
																							}
																							if (empty($sort_by)) continue;
																							if (isset($item['field_type']) && $item['field_type'] == 'number') {
																								$sort_by = 'num_' . $sort_by;
																							}
																						}
																						if ($selected_orderby == $sort_by && $selected_order == $item['sort_order']) {
																							echo esc_html($item['title']);
																							break;
																						}
																					}
																				} ?>
																			</div>
																			<span class="portfolio-sorting-select-current-arrow"></span>
																		</div>
																		<ul>
																			<li class="default <?php echo $selected_orderby == 'default' ? 'portfolio-sorting-select-current' : ''; ?>"
																				data-orderby="default" data-order="default">
																				<?php echo esc_html($params['sorting_extended_text']); ?>
																			</li>
																			<?php foreach ($repeater_sort as $item) {
																				if (in_array($item['attribute_type'], ['date', 'title', 'price', 'rating', 'popularity'])) {
																					$sort_by = $item['attribute_type'];
																				} else {
																					if ($item['attribute_type'] == 'details') {
																						$sort_by = isset($item['attribute_details']) ? $item['attribute_details'] : '';
																					} else if ($item['attribute_type'] == 'custom_fields') {
																						$sort_by = isset($item['attribute_custom_fields']) ? $item['attribute_custom_fields'] : '';
																					} else if ($item['attribute_type'] == 'manual_key') {
																						$sort_by = isset($item['manual_key_field']) ? $item['manual_key_field'] : '';
																					} else {
																						$sort_by = isset($item['attribute_custom_fields_acf_' . $item['attribute_type']]) ? $item['attribute_custom_fields_acf_' . $item['attribute_type']] : '';
																					}
																					if (empty($sort_by)) continue;
																					if (isset($item['field_type']) && $item['field_type'] == 'number') {
																						$sort_by = 'num_' . $sort_by;
																					}
																				} ?>
																				<li class="<?php echo $selected_orderby == $sort_by && $selected_order == $item['sort_order'] ? 'portfolio-sorting-select-current' : ''; ?>"
																					data-orderby="<?php echo esc_attr($sort_by); ?>" data-order="<?php echo esc_attr($item['sort_order']); ?>">
																					<?php echo esc_html($item['title']); ?>
																				</li>
																			<?php } ?>
																		</ul>
																	</div>
																<?php }
															} else { ?>
																<div class="portfolio-sorting-select">
																	<div class="portfolio-sorting-select-current">
																		<div class="portfolio-sorting-select-name">
																			<?php
																			switch ($selected_orderby) {
																				case "title-asc":
																					echo esc_html($params['sorting_extended_dropdown_title_text']);
																					break;
																				case "title-desc":
																					echo esc_html($params['sorting_extended_dropdown_title_desc_text']);
																					break;
																				case "date-desc":
																					echo esc_html($params['sorting_extended_dropdown_latest_text']);
																					break;
																				case "date-asc":
																					echo esc_html($params['sorting_extended_dropdown_oldest_text']);
																					break;
																				default:
																					echo esc_html($params['sorting_extended_text']);
																			} ?>
																		</div>
																		<span class="portfolio-sorting-select-current-arrow"></span>
																	</div>
																	<ul>
																		<li class="default <?php echo $selected_orderby == 'default' ? 'portfolio-sorting-select-current' : ''; ?>"
																			data-orderby="<?php echo esc_attr($params['orderby']); ?>"
																			data-order="<?php echo esc_attr($params['order']); ?>"><?php echo esc_html($params['sorting_extended_text']); ?></li>
																		<li class="<?php echo $selected_orderby == 'title-asc' ? 'portfolio-sorting-select-current' : ''; ?>"
																			data-orderby="title" data-order="asc"><?php echo esc_html($params['sorting_extended_dropdown_title_text']); ?>
																		</li>
																		<li class="<?php echo $selected_orderby == 'title-desc' ? 'portfolio-sorting-select-current' : ''; ?>"
																			data-orderby="title" data-order="desc"><?php echo esc_html($params['sorting_extended_dropdown_title_desc_text']); ?>
																		</li>
																		<li class="<?php echo $selected_orderby == 'date-desc' ? 'portfolio-sorting-select-current' : ''; ?>"
																			data-orderby="date" data-order="desc"><?php echo esc_html($params['sorting_extended_dropdown_latest_text']); ?>
																		</li>
																		<li class="<?php echo $selected_orderby == 'date-asc' ? 'portfolio-sorting-select-current' : ''; ?>"
																			data-orderby="date" data-order="asc"><?php echo esc_html($params['sorting_extended_dropdown_oldest_text']); ?>
																		</li>
																	</ul>
																</div>
														<?php }
														}
													} ?>

													<?php if ($search_right) { ?>
														<span>&nbsp;</span>
														<form class="portfolio-search-filter<?php echo $params['filters_style'] != ' standard' ? ' mobile-visible' : '';
														echo $params['live_search'] ? ' live-search' : '';
														echo $params['search_reset_filters'] ? ' reset-filters' : '';
														echo $params['show_search_as'] == 'input' ? ' input-style' : ''; ?>"
														role="search" action="">
															<div class="portfolio-search-filter-form">
																<input type="search"
																	   placeholder="<?php echo esc_attr($params['filters_text_labels_search_text']); ?>"
																	   value="<?php echo esc_attr($search_current); ?>">
															</div>
															<div class="portfolio-search-filter-button"></div>
														</form>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
										<?php if ($params['filter_type'] == 'extended' && $params['with_filter']) {
											$selected_shown = true;
											include(locate_template(array('gem-templates/portfolios/selected-filters.php')));
										} ?>
									</div>
								<?php }
								if (!$selected_shown && !is_search()) { ?>
									<div class="portfolio-top-panel selected-only">
										<?php include(locate_template(array('gem-templates/portfolios/selected-filters.php'))); ?>
									</div>
								<?php } ?>

								<div class="row portfolio-row">
										<div class="portfolio-set clearfix"
											 data-max-row-height="<?php echo floatval($params['metro_max_row_height']); ?>">
											<?php
											if ($params['layout'] == 'creative') {
												$creative_blog_schemes_list = [
													'6' => [
														'6a' => [
															'count' => 9,
															0 => 'squared',
														],
														'6b' => [
															'count' => 7,
															0 => 'squared',
															1 => 'horizontal',
															6 => 'horizontal',
														],
														'6c' => [
															'count' => 9,
															0 => 'horizontal',
															3 => 'horizontal',
															6 => 'horizontal',
														],
														'6d' => [
															'count' => 9,
															0 => 'horizontal',
															1 => 'horizontal',
															2 => 'horizontal',
														],
														'6e' => [
															'count' => 6,
															0 => 'squared',
															1 => 'squared',
														]
													],
													'5' => [
														'5a' => [
															'count' => 7,
															0 => 'squared',
														],
														'5b' => [
															'count' => 8,
															0 => 'horizontal',
															4 => 'horizontal',
														],
														'5c' => [
															'count' => 6,
															0 => 'horizontal',
															1 => 'horizontal',
															4 => 'horizontal',
															5 => 'horizontal',
														],
														'5d' => [
															'count' => 4,
															0 => 'squared',
															1 => 'vertical',
															2 => 'horizontal',
															3 => 'horizontal',
														]
													],
													'4' => [
														'4a' => [
															'count' => 5,
															0 => 'squared',
														],
														'4b' => [
															'count' => 4,
															0 => 'squared',
															1 => 'horizontal',
														],
														'4c' => [
															'count' => 4,
															0 => 'squared',
															1 => 'vertical',
														],
														'4d' => [
															'count' => 7,
															0 => 'vertical',
														],
														'4e' => [
															'count' => 4,
															0 => 'vertical',
															1 => 'vertical',
															2 => 'horizontal',
															3 => 'horizontal',
														],
														'4f' => [
															'count' => 6,
															0 => 'horizontal',
															5 => 'horizontal',
														]
													],
													'3' => [
														'3a' => [
															'count' => 4,
															0 => 'vertical',
															1 => 'vertical',
														],
														'3b' => [
															'count' => 4,
															1 => 'horizontal',
															2 => 'horizontal',
														],
														'3c' => [
															'count' => 5,
															0 => 'vertical',
														],
														'3d' => [
															'count' => 5,
															0 => 'horizontal',
														],
														'3e' => [
															'count' => 3,
															0 => 'squared',
														],
														'3f' => [
															'count' => 4,
															0 => 'horizontal',
															1 => 'vertical',
														],
														'3g' => [
															'count' => 4,
															0 => 'vertical',
															3 => 'horizontal',
														],
														'3h' => [
															'count' => 5,
															2 => 'vertical',
														]
													],
													'2' => [
														'2a' => [
															'count' => 5,
															0 => 'vertical',
														],
														'2b' => [
															'count' => 5,
															3 => 'vertical',
														],
														'2c' => [
															'count' => 4,
															0 => 'vertical',
															2 => 'vertical',
														],
														'2d' => [
															'count' => 4,
															0 => 'horizontal',
															1 => 'vertical',
														],
														'2e' => [
															'count' => 5,
															0 => 'horizontal',
														],
														'2f' => [
															'count' => 4,
															0 => 'horizontal',
															1 => 'horizontal',
														],
														'2g' => [
															'count' => 5,
															2 => 'horizontal',
														],
														'2h' => [
															'count' => 4,
															0 => 'horizontal',
															3 => 'horizontal',
														],
													]
												];
												$columns = $params['columns_desktop'] != '100%' ? str_replace("x", "", $params['columns_desktop']) : $params['columns_100_creative'];
												$items_sizes = $creative_blog_schemes_list[$columns][$params['layout_scheme_' . $columns . 'x']];
												$items_count = $items_sizes['count'];
											}
											$i = 0;
											if ($news_grid_loop->have_posts()) {
												while ($news_grid_loop->have_posts()) {
													$news_grid_loop->the_post();
													$thegem_highlight_type_creative = null;
													if ($params['layout'] == 'creative') {
														$thegem_highlight_type_creative = 'disabled';
														$item_num = $i % $items_count;
														if (isset($items_sizes[$item_num])) {
															$thegem_highlight_type_creative = $items_sizes[$item_num];
														}
													}
													echo thegem_extended_blog_render_item($params, $item_classes, $thegem_sizes, get_the_ID(), $thegem_highlight_type_creative);
													if ($params['layout'] == 'creative' && $i == 0) {
														echo thegem_extended_blog_render_item($params, ['size-item'], $thegem_sizes);
													}
													$i++;
												}
											} elseif(is_search()) {
												echo '<div class="col-xs-12" style="max-width: 800px;">';
												get_template_part( 'content', 'none' );
												echo '</div>';
											} else { ?>
												<div class="portfolio-item not-found">
													<div class="found-wrap">
														<div class="image-inner empty"></div>
														<div class="msg">
															<?php echo wp_kses($params['not_found_text'], 'post'); ?>
														</div>
													</div>
												</div>
											<?php } ?>
										</div><!-- .portflio-set -->
										<?php if ($params['columns_desktop'] != '1x' && $params['layout'] != 'creative' && $params['version'] != 'list'): ?>
											<div class="portfolio-item-size-container">
												<?php echo thegem_extended_blog_render_item($params, $item_classes, $thegem_sizes); ?>
											</div>
										<?php endif; ?>
									</div><!-- .row-->

								<?php if ($params['pagination'] == 'normal') { ?>
									<div class="portfolio-navigator gem-pagination"<?php if ($max_page < 2) { echo ' style="display:none;"'; } ?>>
										<a href="#" class="prev">
											<i class="default"></i>
										</a>
										<div class="pages"></div>
										<a href="#" class="next">
											<i class="default"></i>
										</a>
									</div>
								<?php } else if ($params['pagination'] == 'more' && $next_page_pagination > 0) { ?>
									<div class="portfolio-load-more">
										<div class="inner">
											<?php thegem_button(array_merge($params['button'], array('tag' => 'button')), 1); ?>
										</div>
									</div>
								<?php } else if ($params['pagination'] == 'scroll' && $next_page_pagination > 0) { ?>
									<div class="portfolio-scroll-pagination"></div>
								<?php } ?>

								<?php if ($params['with_filter'] && $params['filters_style'] == 'sidebar') { ?>
							</div>
						</div>
					<?php } ?>

					<?php if ($params['columns_desktop'] == '100%') {
						echo '<script type="text/javascript">if (typeof(gem_fix_fullwidth_position) == "function") { gem_fix_fullwidth_position(document.querySelector(".portfolio-row-outer.' . $fw_uniqid . '")); }</script>';
					} ?>

				</div><!-- .full-width -->
			</div><!-- .portfolio-->
		</div><!-- .portfolio-preloader-wrapper-->

	<?php }

	thegem_templates_close_post('gem_news_grid', ['name' => __('Posts Extended Grid', 'thegem')], $news_grid_loop->have_posts());
}

function thegem_news_carousel($params) {

	if ($params['disable_socials'] || !thegem_get_option('show_social_icons')) {
		$params['disable_socials'] = true;
	}

	if ($params['display_titles'] == 'hover') {
		$params['item_background_color'] = '';
		$params['item_post_bottom_border_color'] = '';
	}

	if ($params['display_titles'] == 'hover' && ($params['hover'] == 'horizontal-sliding' || $params['hover'] == 'gradient' || $params['hover'] == 'circular')) {
		$params['item_post_categories_background_color'] = '';
	}

	$params['loading_animation'] = thegem_check_array_value(array('disabled', 'bounce', 'move-up', 'fade-in', 'fall-perspective', 'scale', 'flip'), $params['loading_animation'], 'move-up');

	$gap_size = isset($params['gaps_size']) && $params['gaps_size'] != '' ? round(intval($params['gaps_size'])) : 0;
	$gap_size_tablet = isset($params['gaps_size_tablet']) && $params['gaps_size_tablet'] != '' ? round(intval($params['gaps_size_tablet'])) : null;
	$gap_size_mobile = isset($params['gaps_size_mobile']) && $params['gaps_size_mobile'] != '' ? round(intval($params['gaps_size_mobile'])) : null;

	wp_enqueue_style('thegem-news-grid');
	wp_enqueue_style('thegem-portfolio-carousel');
	wp_enqueue_script('thegem-portfolio-carousel');

	$version = 'new';

	$hover_effect = 'new-' . $params['hover'];
	wp_enqueue_style('thegem-news-grid-version-new-hovers-' . $params['hover']);

	if ($params['loading_animation'] !== 'disabled') {
		wp_enqueue_style('thegem-animations');
		wp_enqueue_script('thegem-scroll-monitor');
		wp_enqueue_script('thegem-items-animations');
	}

	$widget_uid = $params['portfolio_uid'];

	if (!empty($params['categories'])) {
		$params['categories'] = explode(',', $params['categories']);
	} else {
		$params['categories'] = ['0'];
	}
	if (!empty($params['tags'])) {
		$params['tags'] = explode(',', $params['tags']);
	}
	if (!empty($params['posts'])) {
		$params['posts'] = explode(',', $params['posts']);
	}
	if (!empty($params['authors'])) {
		$params['authors'] = explode(',', $params['authors']);
	}

	$is_posts_archive = $params['query_type'] == 'archive' && (thegem_get_template_type(get_the_ID()) === 'blog-archive' || is_category() || is_tag() || is_author()  || is_tax() || is_post_type_archive() || is_search());

	extract(thegem_posts_query_section_render($params));

	if(is_search()) {
		$post_type = thegem_get_search_post_types_array();
	}

	$grid_uid = $is_posts_archive ? '' : $widget_uid;
	$grid_uid_url = $is_posts_archive ? '' : $widget_uid.'-';

	$params['portfolio_uid'] = $widget_uid;

	$style_uid = substr(md5(rand()), 0, 7);

	if ($params['orderby'] == 'default') {
		if ($params['query_type'] == 'archive') {
			$params['orderby'] = '';
		} else if ($post_type == 'post') {
			$params['orderby'] = 'menu_order date';
		} else {
			$params['orderby'] = 'date';
		}
	}

	if ($params['order'] == 'default') {
		if ($params['query_type'] == 'archive') {
			$params['order'] = '';
		} else {
			$params['order'] = 'desc';
		}
	}

	if ($post_type == 'any' || is_array($post_type)) {
		$meta_list = select_theme_options_custom_fields_all();
	} else {
		$meta_list = select_theme_options_custom_fields($post_type);
	}
	if (thegem_is_plugin_active('advanced-custom-fields/acf.php') || thegem_is_plugin_active('advanced-custom-fields-pro/acf.php')){
		foreach (thegem_cf_get_acf_plugin_groups() as $gr){
			$meta_list = array_merge($meta_list, thegem_cf_get_acf_plugin_fields_by_group($gr));
		}
	}
	$meta_list = array_flip($meta_list);

	if (!empty($params['image_ratio_default'])) {
		$params['image_aspect_ratio'] = 'custom';
		$params['image_ratio_custom'] = $params['image_ratio_default'];
	}

	$inlineStyles = '';
	$gridSelector = '.portfolio.extended-carousel-grid#style-' . $style_uid;
	$gridSelectorSkeleton = '.preloader#style-preloader-' . $style_uid;
	$itemSelector = $gridSelector . ' .portfolio-item';
	$captionSelector = $itemSelector . ' .caption';
	$onPageCaptionSelector = $itemSelector . ' .wrap > .caption';
	$onHoverCaptionSelector = $itemSelector . ' .image .overlay .caption';

	if (isset($gap_size)) {
		$inlineStyles .= $gridSelector . ':not(.inited) .portfolio-item, ' . $gridSelectorSkeleton . ' .portfolio-item { padding: calc(' . $gap_size . 'px/2) !important; }' .
			$gridSelector . ':not(.inited) .owl-stage, ' . $gridSelectorSkeleton . ' .skeleton-posts.portfolio-row { margin: calc(-' . $gap_size . 'px/2); }' .
			$gridSelector . ' .fullwidth-block:not(.no-paddings) { padding-left: ' . $gap_size . 'px; padding-right: ' . $gap_size . 'px; }' .
			$gridSelector . '.has-shadowed-items .owl-carousel .owl-stage-outer { padding: calc(' . $gap_size . 'px/2) !important; margin: calc(-' . $gap_size . 'px/2); }';
	}
	if (isset($gap_size_tablet)) {
		$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ':not(.inited) .portfolio-item, ' . $gridSelectorSkeleton . ' .portfolio-item { padding: calc(' . $gap_size_tablet . 'px/2) !important; }' .
			$gridSelector . ':not(.inited) .owl-stage, ' . $gridSelectorSkeleton . ' .skeleton-posts.portfolio-row { margin: calc(-' . $gap_size_tablet . 'px/2); }' .
			$gridSelector . ' .fullwidth-block:not(.no-paddings) { padding-left: ' . $gap_size_tablet . 'px; padding-right: ' . $gap_size_tablet . 'px; }' .
			$gridSelector . '.has-shadowed-items .owl-carousel .owl-stage-outer { padding: calc(' . $gap_size_tablet . 'px/2) !important; margin: calc(-' . $gap_size_tablet . 'px/2); }}';
	}
	if (isset($gap_size_mobile)) {
		$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ':not(.inited) .portfolio-item, ' . $gridSelectorSkeleton . ' .portfolio-item { padding: calc(' . $gap_size_mobile . 'px/2) !important; }' .
			$gridSelector . ':not(.inited) .owl-stage, ' . $gridSelectorSkeleton . ' .skeleton-posts.portfolio-row { margin: calc(-' . $gap_size_mobile . 'px/2); }' .
			$gridSelector . ' .fullwidth-block:not(.no-paddings) { padding-left: ' . $gap_size_mobile . 'px; padding-right: ' . $gap_size_mobile . 'px; }' .
			$gridSelector . '.has-shadowed-items .owl-carousel .owl-stage-outer { padding: calc(' . $gap_size_mobile . 'px/2) !important; margin: calc(-' . $gap_size_mobile . 'px/2); }}';
	}
	if (isset($params['image_size']) && $params['image_size'] == 'full' && !empty($params['image_ratio'])) {
		$inlineStyles .= $itemSelector . ':not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: ' . esc_attr($params['image_ratio']) . ' !important; height: auto; }';
	}
	if (isset($params['image_size']) && $params['image_size'] == 'default' && !empty($params['image_ratio_default'])) {
		$inlineStyles .= $itemSelector . ':not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: ' . esc_attr($params['image_ratio_default']) . ' !important; height: auto; }';
	}
	if (!empty($params['image_height']) && (!isset($params['image_size']) || $params['image_size'] == 'default')) {
		$inlineStyles .= $itemSelector . ':not(.double-item) .image-inner { height: ' . esc_attr($params['image_height']) . 'px !important; padding-bottom: 0 !important; aspect-ratio: initial !important; }';
		$inlineStyles .= $itemSelector . ':not(.double-item) .gem-simple-gallery .gem-gallery-item a { height: ' . esc_attr($params['image_height']) . 'px !important; }';
	}
	if (!empty($params['item_hover_background_color'])) {
		$inlineStyles .= $itemSelector . ' .image .overlay:before { background: ' . esc_attr($params['item_hover_background_color']) . ' !important; }';
		$inlineStyles .= $itemSelector . ' .image .overlay-circle { background: ' . esc_attr($params['item_hover_background_color']) . ' !important; }';
		$inlineStyles .= $itemSelector . ' .image .gem-simple-gallery .gem-gallery-item a:before { background: ' . esc_attr($params['item_hover_background_color']) . ' !important; }';
	}
	if (!empty($params['item_background_color'])) {
		$inlineStyles .= $onPageCaptionSelector . ' { background-color: ' . esc_attr($params['item_background_color']) . ' !important; }';
		$inlineStyles .= $itemSelector . ' .wrap { background-color: ' . esc_attr($params['item_background_color']) . ' !important; }';
	}
	if (!empty($params['title_transform'])) {
		$inlineStyles .= $captionSelector . ' .title span { text-transform: ' . esc_attr($params['title_transform']) . ' !important; }';
	}
	if (isset($params['title_letter_spacing'])) {
		$inlineStyles .= $captionSelector . ' .title span { letter-spacing: ' . esc_attr($params['title_letter_spacing']) . 'px !important; }';
	}
	if (!empty($params['item_post_title_color'])) {
		$inlineStyles .= $captionSelector . ' .title { color: ' . esc_attr($params['item_post_title_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .title > * { color: ' . esc_attr($params['item_post_title_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .title a { color: ' . esc_attr($params['item_post_title_color']) . ' !important; }';
	}
	if (!empty($params['item_post_title_color_hover'])) {
		$inlineStyles .= $gridSelector . ':not(.disabled-hover) .portfolio-item:hover .title { color: ' . esc_attr($params['item_post_title_color_hover']) . ' !important; }';
		$inlineStyles .= $gridSelector . ':not(.disabled-hover) .portfolio-item:hover .title > * { color: ' . esc_attr($params['item_post_title_color_hover']) . ' !important; }';
		$inlineStyles .= $gridSelector . ':not(.disabled-hover) .portfolio-item:hover .title a { color: ' . esc_attr($params['item_post_title_color_hover']) . ' !important; }';
		$inlineStyles .= $gridSelector . '.disabled-hover .title a:hover { color: ' . esc_attr($params['item_post_title_color_hover']) . ' !important; }';
	}
	if (!empty($params['item_post_author_color'])) {
		$inlineStyles .= $captionSelector . ' .author .author-name { color: ' . esc_attr($params['item_post_author_color']) . ' !important; }';
	}
	if (!empty($params['item_post_date_color'])) {
		$inlineStyles .= $captionSelector . ' .post-author-date-separator { color: ' . esc_attr($params['item_post_date_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .post-date { color: ' . esc_attr($params['item_post_date_color']) . ' !important; }';
	}
	if (!empty($params['item_post_categories_background_color'])) {
		$inlineStyles .= $onHoverCaptionSelector . ' .info { background-color: ' . esc_attr($params['item_post_categories_background_color']) . ' !important; }';
	}
	if (!empty($params['item_post_categories_color'])) {
		$inlineStyles .= $captionSelector . ' .info .sep { color: ' . esc_attr($params['item_post_categories_color']) . ' !important; border-left-color: ' . esc_attr($params['item_post_categories_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .info a { color: ' . esc_attr($params['item_post_categories_color']) . ' !important; }';
	}
	if (!empty($params['item_post_excerpt_color'])) {
		$inlineStyles .= $captionSelector . ' .description { color: ' . esc_attr($params['item_post_excerpt_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .description .subtitle { color: ' . esc_attr($params['item_post_excerpt_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .grid-post-meta .comments-link a { color: ' . esc_attr($params['item_post_excerpt_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .grid-post-meta .zilla-likes { color: ' . esc_attr($params['item_post_excerpt_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .grid-post-meta .comments-link + .post-meta-likes { border-left-color: ' . esc_attr($params['item_post_excerpt_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .grid-post-meta .grid-post-share + .grid-post-meta-comments-likes .comments-link { border-left-color: ' . esc_attr($params['item_post_excerpt_color']) . ' !important; }';
	}
	if (!empty($params['item_post_comments_icon_color'])) {
		$inlineStyles .= $captionSelector . ' .grid-post-meta .comments-link a i { color: ' . esc_attr($params['item_post_comments_icon_color']) . ' !important; }';
	}
	if (!empty($params['item_post_likes_icon_color'])) {
		$inlineStyles .= $captionSelector . ' .grid-post-meta .zilla-likes i { color: ' . esc_attr($params['item_post_likes_icon_color']) . ' !important; }';
	}
	if (!empty($params['item_post_share_icon_color'])) {
		$inlineStyles .= $captionSelector . ' .grid-post-share .icon { color: ' . esc_attr($params['item_post_share_icon_color']) . ' !important; }';
		$inlineStyles .= $captionSelector . ' .grid-post-share .icon i { color: ' . esc_attr($params['item_post_share_icon_color']) . ' !important; }';
	}
	if (!empty($params['item_post_share_social_icon_color'])) {
		$inlineStyles .= $captionSelector . ' .socials-sharing a.socials-item .socials-item-icon { color: ' . esc_attr($params['item_post_share_social_icon_color']) . ' !important; }';
	}
	if (!empty($params['item_post_hover_icon_color'])) {
		$inlineStyles .= $captionSelector . ' .portfolio-icons a i { color: ' . esc_attr($params['item_post_hover_icon_color']) . ' !important; }';
		$inlineStyles .= $itemSelector . ' .image .gem-simple-gallery .gem-gallery-item a:after { color: ' . esc_attr($params['item_post_hover_icon_color']) . ' !important; }';
	}
	if (!empty($params['item_post_bottom_border_color'])) {
		$inlineStyles .= $itemSelector . ' .wrap > .caption { border-bottom-color: ' . esc_attr($params['item_post_bottom_border_color']) . ' !important; }';
	}
	if (!empty($params['truncate_titles'])) {
		$inlineStyles .= $captionSelector . ' .title span, '. $captionSelector . ' .title a { white-space: initial; max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($params['truncate_titles']) . '; line-clamp: ' . esc_attr($params['truncate_titles']) . '; -webkit-box-orient: vertical; }';
	}
	if (!empty($params['truncate_description'])) {
		$inlineStyles .= $itemSelector . ' .caption .description { max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($params['truncate_description']) . '; line-clamp: ' . esc_attr($params['truncate_description']) . '; -webkit-box-orient: vertical; }';
	}
	if (!empty($params['caption_container_alignment'])) {
		$inlineStyles .= $captionSelector . ' { text-align: ' . esc_attr($params['caption_container_alignment']) . ' !important; }';
	}
	foreach (['top', 'bottom', 'left', 'right'] as $position) {
		if (isset($params['caption_padding_' . $position]) && $params['caption_padding_' . $position] != '') {
			$inlineStyles .= $gridSelector .' .portfolio-set .portfolio-item .wrap > .caption { padding-' . $position . ': ' . intval( $params['caption_padding_' . $position] ) . 'px !important; }';
		}
		if (isset($params['caption_padding_' . $position . '_tablet']) && $params['caption_padding_' . $position . '_tablet'] != '') {
			$inlineStyles .= '@media (max-width: 991px) { ' . $gridSelector .' .portfolio-set .portfolio-item .wrap > .caption { padding-' . $position . ': ' . intval( $params['caption_padding_' . $position . '_tablet'] ) . 'px !important; }}';
		}
		if (isset($params['caption_padding_' . $position . '_mobile']) && $params['caption_padding_' . $position . '_mobile'] != '') {
			$inlineStyles .= '@media (max-width: 767px) { ' . $gridSelector .' .portfolio-set .portfolio-item .wrap > .caption { padding-' . $position . ': ' . intval( $params['caption_padding_' . $position . '_mobile'] ) . 'px !important; }}';
		}
	}
	if (isset($params['navigation_arrows_icon_color_normal']) && $params['navigation_arrows_icon_color_normal'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev div, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next div { color: " . $params['navigation_arrows_icon_color_normal'] . " }";
	}
	if (isset($params['navigation_arrows_icon_color_hover']) && $params['navigation_arrows_icon_color_hover'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev:hover div, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next:hover div { color: " . $params['navigation_arrows_icon_color_hover'] . " }";
	}
	if (isset($params['navigation_arrows_border_width']) && $params['navigation_arrows_border_width'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next { border-width: " . $params['navigation_arrows_border_width'] . "px }";
	}
	if (isset($params['navigation_arrows_border_radius']) && $params['navigation_arrows_border_radius'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next { border-radius: " . $params['navigation_arrows_border_radius'] . "px }";
	}
	if (isset($params['navigation_arrows_border_color_normal']) && $params['navigation_arrows_border_color_normal'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next { border-color: " . $params['navigation_arrows_border_color_normal'] . " }";
	}
	if (isset($params['navigation_arrows_border_color_hover']) && $params['navigation_arrows_border_color_hover'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev:hover, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next:hover { border-color: " . $params['navigation_arrows_border_color_hover'] . " }";
	}
	if (isset($params['navigation_arrows_background_color_normal']) && $params['navigation_arrows_background_color_normal'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev div.position-on, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next div.position-on { background-color: " . $params['navigation_arrows_background_color_normal'] . " }";
	}
	if (isset($params['navigation_arrows_background_color_hover']) && $params['navigation_arrows_background_color_hover'] != '') {
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev:hover div.position-on, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next:hover div.position-on { background-color: " . $params['navigation_arrows_background_color_hover'] . " }";
	}
	if (isset($params['navigation_arrows_spacing']) && $params['navigation_arrows_spacing'] != '') {
		$inlineStyles .= $gridSelector . ".arrows-position-outside:not(.prevent-arrows-outside) .extended-carousel-item .owl-nav .owl-prev { transform: translate(calc(-100% - " . $params['navigation_arrows_spacing'] . "px), -50%); }";
		$inlineStyles .= $gridSelector . ".arrows-position-outside:not(.prevent-arrows-outside) .extended-carousel-item .owl-nav .owl-next { transform: translate(calc(100% + " . $params['navigation_arrows_spacing'] . "px), -50%); }";
		$inlineStyles .= $gridSelector . ".arrows-position-outside.prevent-arrows-outside .extended-carousel-item .owl-nav .owl-prev, " .
			$gridSelector . ".arrows-position-on .extended-carousel-item .owl-nav .owl-prev { left: " . $params['navigation_arrows_spacing'] . "px; }";
		$inlineStyles .= $gridSelector . ".arrows-position-outside.prevent-arrows-outside .extended-carousel-item .owl-nav .owl-next, " .
			$gridSelector . ".arrows-position-on .extended-carousel-item .owl-nav .owl-next { right: " . $params['navigation_arrows_spacing'] . "px; }";
	}
	if (isset($params['navigation_top_spacing']) && $params['navigation_top_spacing'] != '') {
		$value = $params['navigation_top_spacing'];
		$unit = 'px';
		$last_result = substr($value, -1);
		if ($last_result == '%') {
			$value = str_replace('%', '', $value);
			$unit = $last_result;
		}
		$inlineStyles .= $gridSelector . " .extended-carousel-item .owl-nav .owl-prev, " .
			$gridSelector . " .extended-carousel-item .owl-nav .owl-next { top: " . $value . $unit . " !important; }";
	}
	if (isset($params['navigation_dots_spacing']) && $params['navigation_dots_spacing'] != '') {
		$inlineStyles .= $gridSelector . " .owl-dots { margin-top: " . $params['navigation_dots_spacing'] . "px }";
	}
	if (isset($params['navigation_dots_border_width']) && $params['navigation_dots_border_width'] != '') {
		$inlineStyles .= $gridSelector . " .owl-dots .owl-dot span { border-width: " . $params['navigation_dots_border_width'] . "px }";
	}
	if (isset($params['navigation_dots_border_color_normal']) && $params['navigation_dots_border_color_normal'] != '') {
		$inlineStyles .= $gridSelector . " .owl-dots .owl-dot span { border-color: " . $params['navigation_dots_border_color_normal'] . " }";
	}
	if (isset($params['navigation_dots_border_color_active']) && $params['navigation_dots_border_color_active'] != '') {
		$inlineStyles .= $gridSelector . " .owl-dots .owl-dot.active span { border-color: " . $params['navigation_dots_border_color_active'] . " }";
	}
	if (isset($params['navigation_dots_background_color_normal']) && $params['navigation_dots_background_color_normal'] != '') {
		$inlineStyles .= $gridSelector . " .owl-dots .owl-dot span { background-color: " . $params['navigation_dots_background_color_normal'] . " }";
	}
	if (isset($params['navigation_dots_background_color_active']) && $params['navigation_dots_background_color_active'] != '') {
		$inlineStyles .= $gridSelector . " .owl-dots .owl-dot.active span { background-color: " . $params['navigation_dots_background_color_active'] . " }";
	}

	if (!empty($inlineStyles)) {
		echo '<style>'.$inlineStyles.'</style>';
	}

	echo thegem_portfolio_news_render_styles($params, $gridSelector, $gridSelectorSkeleton);

	$taxonomy_filter_current = $taxonomy_filter;
	$categories_filter = [];
	$taxonomies_list = array_flip(select_post_type_taxonomies($post_type));
	if (isset($_GET[$grid_uid_url . 'category'])) {
		$categories_filter = explode(",", $_GET[$grid_uid_url . 'category']);
		$taxonomy_filter_current['category'] = $categories_filter;
	}

	$page = 1;

	if (isset($_GET[$grid_uid_url . 'page'])) {
		$page = $_GET[$grid_uid_url . 'page'];
	}

	$items_per_page = $params['items_per_page'] ? intval($params['items_per_page']) : 8;

	if (!empty($_GET[$grid_uid_url . 'orderby'])) {
		$orderby = $_GET[$grid_uid_url . 'orderby'];
	} else {
		$orderby = $params['orderby'];
	}

	if (!empty($_GET[$grid_uid_url . 'order'])) {
		$order = $_GET[$grid_uid_url . 'order'];
	} else {
		$order = $params['order'];
	}

	$portfolios_filters_tax_url = $portfolios_filters_meta_url = $meta_filter_current = [];
	foreach($_GET as $key => $value) {
		if (strpos($key, $grid_uid_url . 'filter_tax_') === 0) {
			$attr = str_replace($grid_uid_url . 'filter_tax_', '', $key);
			$portfolios_filters_tax_url['tax_' . $attr] = $taxonomy_filter_current[$attr] = explode(",", $value);
		} else if (strpos($key, $grid_uid_url . 'filter_meta_') === 0) {
			$attr = str_replace($grid_uid_url . 'filter_meta_', '', $key);
			$portfolios_filters_meta_url['meta_' . $attr] = $meta_filter_current[$attr] = explode(",", $value);
		}
	}
	$attributes_filter = array_merge($portfolios_filters_tax_url, $portfolios_filters_meta_url);
	if (empty($attributes_filter)) { $attributes_filter = null; }

	$search_current = null;
	if (!empty($_GET[$grid_uid_url . 's'])) {
		$search_current = $_GET[$grid_uid_url . 's'];
	}

	$news_grid_loop = get_thegem_extended_blog_posts($post_type, $taxonomy_filter_current, $meta_filter_current, $manual_selection, $exclude, $blog_authors, $page, $items_per_page, $orderby, $order, $params['offset'], $params['ignore_sticky_posts'], $search_current, 'content', $date_query);

	if ($news_grid_loop && $news_grid_loop->have_posts() || !empty($categories_filter) || !empty($attributes_filter) || !empty($search_current)) {
		$params['thegem_elementor_preset'] = 'new';
		$params['layout'] = 'justified';
		$params['ignore_highlights'] = '1';

		$item_classes = get_thegem_portfolio_render_item_classes($params);
		$item_classes[] = 'owl-item';
		$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params);

		if (!$params['disable_preloader']) {
			if ($params['columns_desktop'] == '100%' || !$params['skeleton_loader']) {
				$spin_class = 'preloader-spin';
				if ($params['ajax_preloader_type'] == 'minimal') {
					$spin_class = 'preloader-spin-new';
				}
				echo apply_filters('thegem_portfolio_preloader_html', '<div id="style-preloader-' . $style_uid . '" class="preloader save-space"><div class="' . $spin_class . '"></div></div>');
			} else { ?>
				<div id="style-preloader-<?php echo esc_attr($style_uid); ?>" class="preloader skeleton-carousel">
					<div class="skeleton">
						<div class="skeleton-posts portfolio-row">
							<?php for ($x = 0; $x < $news_grid_loop->post_count; $x++) {
								echo thegem_extended_blog_render_item($params, $item_classes);
							} ?>
						</div>
					</div>
				</div>
			<?php }
		} ?>
		<div class="portfolio-preloader-wrapper">

			<?php
			$news_grid_classes = array(
				'portfolio portfolio-grid extended-portfolio-grid news-grid extended-posts-carousel extended-carousel-grid no-padding disable-isotope portfolio-style-justified',
				'portfolio-style-' . $params['layout'],
				'background-style-' . $params['background_style'],
				'hover-' . $hover_effect,
				'title-on-' . $params['display_titles'],
				'caption-position-' . $params['display_titles'],
				'version-' . $version,
				'arrows-position-' . $params['arrows_navigation_position'],
				($params['loading_animation'] !== 'disabled' ? 'loading-animation item-animation-' . $params['loading_animation'] : ''),
				($params['loading_animation'] !== 'disabled' && $params['loading_animation_mobile'] == '1' ? 'enable-animation-mobile' : ''),
				($gap_size == 0 ? 'no-gaps' : ''),
				($params['enable_shadow'] ? 'has-shadowed-items' : ''),
				($params['shadowed_container'] == '1' ? 'shadowed-container' : ''),
				($params['columns_desktop'] == '100%' ? 'fullwidth-columns fullwidth-columns-' . $params['columns_100'] : ''),
				($params['columns_desktop'] == '100%' && $params['gaps_size'] < 24 ? 'prevent-arrows-outside' : ''),
				($params['display_titles'] == 'hover' ? 'hover-title' : ''),
				($params['columns_desktop'] != '100%' ? 'columns-' . str_replace("x", "", $params['columns_desktop']) : ''),
				(isset($params['columns_tablet']) ? 'columns-tablet-' . str_replace("x", "", $params['columns_tablet']) : ''),
				(isset($params['columns_mobile']) ? 'columns-mobile-' . str_replace("x", "", $params['columns_mobile']) : ''),
				($params['arrows_navigation_visibility'] == 'hover' ? 'arrows-hover' : ''),
				((isset($params['image_size']) && (($params['image_size'] == 'full' && empty($params['image_ratio'])) || !in_array($params['image_size'], ['full', 'default']))) ? 'full-image' : ''),
				($params['ajax_preloader_type'] == 'minimal' ? 'minimal-preloader' : ''),
			);

			$news_grid_classes = apply_filters('news_grid_classes_filter', $news_grid_classes);
			$fw_uniqid = uniqid('fullwidth-block-'); ?>

			<div class="<?php echo implode(' ', $news_grid_classes); ?>"
				 id="style-<?php echo esc_attr($style_uid); ?>"
				 data-style-uid="<?php echo esc_attr($style_uid); ?>"
				 data-portfolio-uid="<?php echo esc_attr($grid_uid); ?>"
				 data-columns-mobile="<?php echo esc_attr(str_replace("x", "", $params['columns_mobile'])); ?>"
				 data-columns-tablet="<?php echo esc_attr(str_replace("x", "", $params['columns_tablet'])); ?>"
				 data-columns-desktop="<?php echo $params['columns_desktop'] != '100%' ? esc_attr(str_replace("x", "", $params['columns_desktop'])) : esc_attr($params['columns_100']); ?>"
				 data-margin-mobile="<?php echo esc_attr($gap_size_mobile); ?>"
				 data-margin-tablet="<?php echo esc_attr($gap_size_tablet); ?>"
				 data-margin-desktop="<?php echo esc_attr($gap_size); ?>"
				 data-hover="<?php echo esc_attr($hover_effect); ?>"
				 data-dots="<?php echo esc_attr($params['show_dots_navigation']); ?>"
				 data-arrows="<?php echo esc_attr($params['show_arrows_navigation']); ?>"
				 data-loop="<?php echo esc_attr($params['slider_loop']); ?>"
				 data-sliding-animation="<?php echo esc_attr($params['sliding_animation']); ?>"
				 data-autoscroll-speed="<?php echo $params['autoscroll'] ? esc_attr($params['autoscroll_speed']) : '0'; ?>">

				<div class="portfolio-row-outer <?php if ($params['columns_desktop'] == '100%'): ?>fullwidth-block <?= $fw_uniqid; ?> no-paddings<?php endif; ?>">
					<div class="portfolio-row clearfix">
						<div class="portfolio-set">
							<div class="extended-carousel-wrap">
								<div class="extended-carousel-item owl-carousel owl-theme owl-loaded">
									<div class="owl-stage-outer">
										<div class="owl-stage">
											<?php
											if ($news_grid_loop->have_posts()) {
												while ($news_grid_loop->have_posts()) {
													$news_grid_loop->the_post();
													$thegem_highlight_type_creative = null;
													echo thegem_extended_blog_render_item($params, $item_classes, $thegem_sizes, get_the_ID(), $thegem_highlight_type_creative);
												}
											} else { ?>
												<div class="portfolio-item not-found">
													<div class="found-wrap">
														<div class="image-inner empty"></div>
														<div class="msg">
															<?php echo wp_kses($params['not_found_text'], 'post'); ?>
														</div>
													</div>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>

						</div><!-- .portflio-set -->
					</div><!-- .row-->
					<?php if ($params['show_arrows_navigation']): ?>
						<div class="slider-prev-icon position-<?php echo $params['arrows_navigation_position']; ?>">
							<i class="default"></i>
						</div>
						<div class="slider-next-icon position-<?php echo $params['arrows_navigation_position']; ?>">
							<i class="default"></i>
						</div>
					<?php endif; ?>

				</div><!-- .full-width -->
			</div><!-- .portfolio-->
		</div><!-- .portfolio-preloader-wrapper-->

	<?php }

	thegem_templates_close_post('gem_news_grid', ['name' => __('Posts Extended Grid', 'thegem')], $news_grid_loop->have_posts());

	if (thegem_is_plugin_active('js_composer/js_composer.php')) {
		global $vc_manager;
		if ($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode() == 'page_editable') { ?>
			<script type="text/javascript">
				(function ($) {
					setTimeout(function () {
						$('#style-<?php echo esc_attr($style_uid); ?>.extended-posts-carousel').initPortfolioCarousels();
					}, 1000);
				})(jQuery);
			</script>
		<?php }
	}
}

function thegem_extended_filter($params) {
	$style_uid = substr(md5(rand()), 0, 7);
	if ($params['grid_filter'] == 'id') {
		$grid_uid = $params['grid_id'];
		$grid_uid_url = $grid_uid . '-';
	} else {
		$grid_uid = $grid_uid_url = '';
	}

	wp_enqueue_style('thegem-portfolio-filters-list');
	wp_enqueue_script('thegem-portfolio-grid-extended');

	if ($params['filtering_type'] == 'with-button') {
		$params['filtering_type'] = 'button';
	}

	parse_str($_SERVER['QUERY_STRING'], $url_params);

	$portfolios_filters_tax_url = $portfolios_filters_meta_url = [];
	$sale_only = $stock_only = false;
	$search_current = null;
	if (!empty($url_params) && $grid_uid_url != '-') {
		foreach ($url_params as $name => $value) {
			if (str_contains($name, $grid_uid_url . 'filter_tax_')) {
				$portfolios_filters_tax_url[str_replace($grid_uid_url . 'filter_', '', $name)] = explode(",", $value);
			} else if (str_contains($name, $grid_uid_url . 'category')) {
				$portfolios_filters_tax_url['tax_product_cat'] = explode(",", $value);
			} else if (str_contains($name, $grid_uid_url . 'filter_')) {
				$portfolios_filters_meta_url[str_replace($grid_uid_url . 'filter_', '', $name)] = explode(",", $value);
			} else if (str_contains($name, $grid_uid_url . 'status')) {
				$status_current = explode(",", $value);
				if (in_array('sale', $status_current)) {
					$sale_only = true;
				}
				if (in_array('stock', $status_current)) {
					$stock_only = true;
				}
			} else if (str_contains($name, $grid_uid_url . 's')) {
				$search_current = $value;
			}
		}
	}

	ob_start();
	$filter_attr = vc_param_group_parse_atts($params['repeater_attributes']);
	$filter_attr_numeric = [];
	if (!empty($filter_attr)) {
		foreach ($filter_attr as $item) {
			$terms = false;
			if ($item['attribute_type'] == 'search') { ?>
				<div class="portfolio-filter-item display-type-dropdown">
					<?php if (!empty($item['show_title'])) { ?>
						<h4 class="name widget-title">
							<?php echo esc_html($item['attribute_title']); ?>
						</h4>
					<?php } ?>
					<form class="portfolio-search-filter<?php
					echo !empty($item['live_search']) ? ' live-search' : '';
					echo !empty($item['search_reset_filters']) ? ' reset-filters' : ''; ?>"
						  role="search" action="">
						<div class="portfolio-search-filter-form">
							<input type="search"
								   placeholder="<?php echo esc_attr($params['filters_text_labels_search_text']); ?>"
								   value="<?php echo esc_attr($search_current); ?>">
						</div>
						<div class="portfolio-search-filter-button"></div>
					</form>
				</div>
			<?php continue; }
			$attributes_url = $portfolios_filters_meta_url;
			if ($item['attribute_type'] == 'taxonomies') {
				if (empty($item['attribute_taxonomies'])) continue;
				$term_args = [
					'taxonomy' => $item['attribute_taxonomies'],
					'orderby' => $item['attribute_order_by'],
				];
				if (!empty($item['attribute_taxonomies_hierarchy'])) {
					$term_args['parent'] = 0;
				}
				$terms = get_terms($term_args);
				$attribute_name = 'tax_' . $item['attribute_taxonomies'];
				$attributes_url = $portfolios_filters_tax_url;
			} else {
				$item['attribute_order_by'] = $item['attribute_order_by_details'];
				$item['attribute_query_type'] = $item['attribute_query_type_details'];
				if ($item['attribute_type'] == 'products_attributes') {
					$attribute_name = $item['attribute_name_products'];
					$terms = get_terms('pa_' . $attribute_name);
				} else if ($item['attribute_type'] == 'products_price') {
					$attribute_name = 'price';
					$terms = true;
				} else if ($item['attribute_type'] == 'products_status') {
					$attribute_name = 'status';
					$terms = true;
				} else {
					if ($item['attribute_type'] == 'details') {
						$attribute_name = isset($item['attribute_details']) ? $item['attribute_details'] : '';
					} else if ($item['attribute_type'] == 'custom_fields') {
						$attribute_name = isset($item['attribute_custom_fields']) ? $item['attribute_custom_fields'] : '';
					} else if ($item['attribute_type'] == 'manual_key') {
						$attribute_name = isset($item['manual_key_field']) ? $item['manual_key_field'] : '';
					} else {
						$attribute_name = isset($item['attribute_custom_fields_acf_' . $item['attribute_type']]) ? $item['attribute_custom_fields_acf_' . $item['attribute_type']] : '';
						$group_fields = acf_get_fields($item['attribute_type']);
						$found_key = array_search($attribute_name, array_column($group_fields, 'name'));
						$checkbox_field = get_field_object($group_fields[$found_key]['key']);
						if (isset($checkbox_field['choices'])) {
							$terms = $checkbox_field['choices'];
							if ($checkbox_field['type'] == 'checkbox') {
								$attribute_name .= '__check';
							}
						}
						$item['attribute_type'] = 'acf_fields';
					}
					if (empty($attribute_name)) continue;
					if (empty($terms)) {
						$terms = get_post_type_meta_values($attribute_name, 'any');
					}
					$attribute_name = 'meta_' . $attribute_name;
				}
			}
			if (!empty($terms) && !is_wp_error($terms)) {
				$is_dropdown = isset($item['attribute_display_type']) && $item['attribute_display_type'] == 'dropdown';
				if ($item['attribute_type'] == 'products_price' || ($item['attribute_type'] != 'taxonomies' && $item['attribute_meta_type'] == 'number')) {
					wp_enqueue_script('jquery-touch-punch');
					wp_enqueue_script('jquery-ui-slider');
					if ($item['attribute_type'] == 'products_price') {
						$price_range = thegem_extended_products_get_product_price_range();
						$min = $price_range['min'];
						$max = $price_range['max'];
					} else {
						$terms = array_map('floatval', $terms);
						$filter_attr_numeric[$attribute_name] = $item;
						$min = min($terms);
						$max = max($terms);
					} ?>
					<div class="portfolio-filter-item price<?php
					echo $is_dropdown ? ' display-type-dropdown open-dropdown-' . $item['attribute_display_dropdown_open'] : ''; ?>">
						<?php if ((!empty($item['show_title']) && !empty($item['attribute_title'])) || (!$is_dropdown && $params['filters_style'] == 'standard')) { ?>
							<h4 class="name widget-title">
								<span class="widget-title-by">
									<?php echo esc_html($params['filter_buttons_hidden_filter_by_text']); ?>
								</span>
								<?php echo esc_html($item['attribute_title']); ?>
								<span class="widget-title-arrow"></span>
							</h4>
						<?php } ?>
						<?php if ($is_dropdown) { ?>
						<div class="dropdown-selector">
							<div class="selector-title">
									<span class="name">
										<?php if (empty($item['show_title'])) { ?>
											<span class="slider-amount-text"><?php echo esc_html($item['attribute_title']); ?>: </span>
										<?php } ?>
										<span class="slider-amount-value"></span>
									</span>
								<span class="widget-title-arrow"></span>
							</div>
							<?php } ?>
							<div class="portfolio-filter-item-list">
								<div class="price-range-slider">
									<div class="slider-range"
										 data-min="<?php echo esc_attr($min); ?>"
										 data-max="<?php echo esc_attr($max); ?>"
										<?php if ($item['attribute_type'] == 'products_price') { ?>
											data-currency="<?php echo esc_attr(get_woocommerce_currency_symbol()); ?>"
											data-currency-position="<?php echo esc_attr(get_option('woocommerce_currency_pos')); ?>"
											data-thousand-separator="<?php echo esc_attr(get_option('woocommerce_price_thousand_sep')); ?>"
											data-decimal-separator="<?php echo esc_attr(get_option('woocommerce_price_decimal_sep')); ?>"
										<?php } else { ?>
											data-attr="<?php echo esc_attr($attribute_name); ?>"
											data-prefix="<?php echo isset($item['attribute_price_format_prefix']) ? esc_attr($item['attribute_price_format_prefix']) : ''; ?>"
											data-suffix="<?php echo isset($item['attribute_price_format_suffix']) ? esc_attr($item['attribute_price_format_suffix']) : ''; ?>"
											<?php if ($item['attribute_price_format'] != 'disabled') { ?>data-locale="<?php echo esc_attr($item['attribute_price_format'] == 'wp_locale' ? get_locale() : $item['attribute_price_format_locale']); ?>"<?php }
										} ?>></div>
									<div class="slider-amount">
										<span class="slider-amount-text"><?php echo esc_html($item['attribute_title']); ?>: </span>
										<span class="slider-amount-value"></span>
									</div>
								</div>
							</div>
							<?php if ($is_dropdown) { ?>
						</div>
					<?php } ?>
					</div>
				<?php } else if ($item['attribute_type'] == 'products_status') { ?>
					<div class="portfolio-filter-item status multiple<?php
					echo $is_dropdown ? ' display-type-dropdown open-dropdown-' . $item['attribute_display_dropdown_open'] : ''; ?>">
						<?php if ((!empty($item['show_title']) && !empty($item['attribute_title'])) || (!$is_dropdown && $params['filters_style'] == 'standard')) { ?>
							<h4 class="name widget-title">
								<span class="widget-title-by">
									<?php echo esc_html($params['filter_buttons_hidden_filter_by_text']); ?>
								</span>
								<?php echo esc_html($item['attribute_title']); ?>
								<span class="widget-title-arrow"></span>
							</h4>
						<?php } ?>
						<?php if ($is_dropdown) { ?>
						<div class="dropdown-selector">
							<div class="selector-title">
								<?php $title = empty($item['show_title']) ? $item['attribute_title'] : str_replace('%ATTR%', $item['attribute_title'], $params['filters_text_labels_all_text']); ?>
								<span class="name" data-title="<?php echo esc_attr($title); ?>">
									<?php if (!$sale_only && !$stock_only) { ?>
										<span data-filter="*"><?php echo esc_html($title); ?></span>
									<?php } else {
										if ($sale_only) {
											echo '<span data-filter="sale">' . esc_html($item['filter_by_status_sale_text']) . '<span class="separator">, </span></span>';
										}
										if ($stock_only) {
											echo '<span data-filter="stock">' . esc_html($item['filter_by_status_stock_text']) . '<span class="separator">, </span></span>';
										}
									} ?>
								</span>
								<span class="widget-title-arrow"></span>
							</div>
							<?php } ?>
							<div class="portfolio-filter-item-list">
								<ul>
									<li>
										<a href="#" data-filter="*"
										   data-filter-type="status"
										   class="all <?php echo ($sale_only || $stock_only) ? '' : 'active'; ?>"
										   rel="nofollow"><span class="title"><?php echo esc_html(str_replace('%ATTR%', $item['attribute_title'], $params['filters_text_labels_all_text'])); ?></span>
										</a>
									</li>
									<?php if ($item['filter_by_status_sale']) { ?>
										<li>
											<a href="#"
											   data-filter-type="status"
											   data-filter="sale"
											   data-filter-id="sale"
											   class="<?php echo $sale_only ? 'active' : ''; ?>"
											   rel="nofollow">
												<span class="title"><?php echo esc_html($item['filter_by_status_sale_text']); ?></span>
											</a>
										</li>
									<?php }
									if ($item['filter_by_status_stock']) { ?>
										<li>
											<a href="#"
											   data-filter-type="status"
											   data-filter="stock"
											   data-filter-id="stock"
											   class="<?php echo $stock_only ? 'active' : ''; ?>"
											   rel="nofollow">
												<span class="title"><?php echo esc_html($item['filter_by_status_stock_text']); ?></span>
											</a>
										</li>
									<?php } ?>
								</ul>
							</div>
							<?php if ($is_dropdown) { ?>
						</div>
					<?php } ?>
					</div>
				<?php } else {
					$attribute_type_class = '';
					$attribute_data = false;
					if ($item['attribute_type'] == 'products_attributes') {
						$attribute_data = wc_get_attribute(wc_attribute_taxonomy_id_by_name('pa_' . $attribute_name));
						$attribute_type_class = $attribute_data->type == 'color' || $attribute_data->type == 'label' ? ' attribute-type-' . $attribute_data->type : '';
					}
					if ( $attribute_name == 'tax_product_cat') {
						$attribute_type = 'category';
					} else {
						$attribute_type = $item['attribute_type'];
					}
					$keys = array_keys($terms);
					$simple_arr = $keys == array_keys($keys);
					if ($item['attribute_order_by'] == 'name') {
						if ($simple_arr) {
							sort($terms);
						} else {
							asort($terms);
						}
					} ?>
					<div class="portfolio-filter-item attribute <?php
					echo esc_attr($attribute_name);
					echo strtolower($item['attribute_query_type']) == 'and' ? ' multiple' : ' single';
					echo $is_dropdown ? ' display-type-dropdown open-dropdown-' . $item['attribute_display_dropdown_open'] : '';
					echo esc_attr($attribute_type_class); ?>">
						<?php if ((!empty($item['show_title']) && !empty($item['attribute_title'])) || (!$is_dropdown && $params['filters_style'] == 'standard')) { ?>
							<h4 class="name widget-title">
								<span class="widget-title-by">
									<?php echo esc_html($params['filter_buttons_hidden_filter_by_text']); ?>
								</span>
								<?php echo esc_html($item['attribute_title']); ?>
								<span class="widget-title-arrow"></span>
							</h4>
						<?php } ?>
						<?php if ($is_dropdown) { ?>
						<div class="dropdown-selector">
							<div class="selector-title">
								<?php $title = empty($item['show_title']) ? $item['attribute_title'] : str_replace('%ATTR%', $item['attribute_title'], $params['filters_text_labels_all_text']); ?>
								<span class="name" data-title="<?php echo esc_attr($title); ?>">
									<?php if (!isset($attributes_url[$attribute_name])) { ?>
										<span data-filter="*"><?php echo esc_html($title); ?></span>
									<?php } else {
										foreach ($terms as $key => $term) {
											$term_slug = isset($term->slug) ? $term->slug : ($simple_arr ? $term : $key);
											$term_title = isset($term->name) ? $term->name : $term;
											if (in_array($term_slug, $attributes_url[$attribute_name])) {
												echo '<span data-filter="' . $term_slug . '">' . $term_title . '<span class="separator">, </span></span>';
											}
										}
									} ?>
								</span>
								<span class="widget-title-arrow"></span>
							</div>
							<?php } ?>
							<div class="portfolio-filter-item-list">
								<ul>
									<li<?php if ($attribute_data && ($attribute_data->type == 'color' || $attribute_data->type == 'label')) {
										echo ' style="display: none;"';
									} ?>>
										<a href="#"
										   data-filter-type="<?php echo esc_attr($attribute_type); ?>"
										   data-attr="<?php echo esc_attr($attribute_name); ?>"
										   data-filter="*"
										   class="all <?php echo !isset($attributes_url[$attribute_name]) ? 'active' : ''; ?>"
										   rel="nofollow">
											<?php if ($item['attribute_query_type'] == 'or') {
												echo '<span class="check"></span>';
											} ?>
											<span class="title"><?php echo esc_html(str_replace('%ATTR%', $item['attribute_title'], $params['filters_text_labels_all_text'])); ?></span>
										</a>
									</li>
									<?php thegem_print_attributes_list($terms, $item, $attribute_name, $attributes_url, $attribute_data); ?>
								</ul>
							</div>
							<?php if ($is_dropdown) { ?>
						</div>
					<?php } ?>
					</div>
				<?php }
			}
		}
	}
	$filters_list = ob_get_clean();

	if (!empty($filters_list) || $params['filtering_type'] == 'button') { ?>
		<div id="style-<?php echo esc_attr($style_uid); ?>" class="extended-posts-filter portfolio-top-panel filter-type-extended<?php
			echo $params['filters_style'] == 'sidebar' ? ' sidebar-filter' : '';
			echo !$params['filter_buttons_hidden_sidebar_separator_enable'] ? ' hide-separator' : ''; ?>"
			 data-portfolio-uid="<?php echo esc_attr($grid_uid); ?>"
			<?php if (!empty($params['grid_url'])) {?>
				data-url="<?php echo esc_attr($params['grid_url']); ?>"
			<?php } ?>>
			<div class="portfolio-top-panel-row">
				<div class="portfolio-top-panel-left <?php echo esc_attr($params['filter_buttons_standard_alignment']); ?>">
					<?php if (!empty($filter_attr) || $params['filtering_type'] == 'button') { ?>
						<div class="portfolio-filters-list style-<?php echo esc_attr($params['filters_style']);
						echo ' filtering-type-' . $params['filtering_type'];
						echo $params['filters_scroll_top'] ? ' scroll-top' : '';
						echo $params['filters_style_mobile'] !== 'hidden' ? ' prevent-hidden-mobile' : ''; ?>"
						data-breakpoint="<?php echo $params['filters_style_mobile'] == 'hidden' ? esc_attr($params['hidden_breakpoint']) : ''; ?>">
							<?php if ($params['filters_style'] == 'hidden' || $params['filters_style_mobile'] == 'hidden') { ?>
								<div class="portfolio-show-filters-button with-icon">
									<?php echo esc_html($params['filter_buttons_hidden_show_text']); ?>
									<?php if ($params['filter_buttons_hidden_show_icon']) { ?>
										<span class="portfolio-show-filters-button-icon"></span>
									<?php } ?>
								</div>
							<?php } ?>

							<div class="portfolio-filters-outer without-padding">
								<div class="portfolio-filters-area">
									<div class="portfolio-filters-area-scrollable">
										<?php if ($params['filters_style'] == 'hidden' || $params['filters_style_mobile'] == 'hidden') { ?>
											<h2 class="light"><?php echo esc_html($params['filter_buttons_hidden_sidebar_title']); ?></h2>
										<?php } ?>
										<div class="widget-area-wrap">
											<div class="portfolio-filters-extended widget-area">
												<?php  echo $filters_list;

												if ($params['filtering_type'] == 'button') { ?>
													<div class="portfolio-filter-item filters-apply-button">
														<a href="#" id="<?php echo esc_attr($params['apply_button_id']); ?>"
														   class="gem-button gem-button-size-<?php echo $params['apply_button_size']; ?>
															gem-button-style-<?php echo $params['apply_button_type']; ?>
															<?php echo esc_attr($params['apply_button_class']); ?>">
																<span class="gem-inner-wrapper-btn">
																	<span class="gem-text-button">
																		<?php echo esc_html($params['apply_button_text']); ?>
																	</span>
																</span>
														</a>
													</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>

								<?php if ($params['filters_style'] == 'hidden' || $params['filters_style_mobile'] == 'hidden') { ?>
									<div class="portfolio-close-filters"></div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<script>
			!function(s){function a(a,e){s(window).outerWidth()<e?a.hasClass("style-standard")?a.addClass("style-standard-mobile"):a.hasClass("style-sidebar")&&a.addClass("style-sidebar-mobile"):a.hasClass("style-standard")?a.removeClass("style-standard-mobile"):a.hasClass("style-sidebar")&&a.removeClass("style-sidebar-mobile")}s("#style-<?php echo esc_attr($style_uid); ?> .portfolio-filters-list").each(function(){if(!s(this).hasClass("style-hidden")&&!s(this).hasClass("prevent-hidden-mobile")){let e=s(this),t=e.data("breakpoint")?e.data("breakpoint"):992;a(s(this),t),s(window).on("resize",function(){a(e,t)})}})}(jQuery);
		</script>
		<?php if (thegem_is_plugin_active('js_composer/js_composer.php')) {
			global $vc_manager;
			if ($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode() == 'page_editable') { ?>
				<script type="text/javascript">
					(function ($) {
						setTimeout(function () {
							$('#style-<?php echo esc_attr($style_uid); ?>.extended-posts-filter').initPortfolioFiltersList();
						}, 1000);
					})(jQuery);
				</script>
			<?php }
		}

		$inlineStyles = '';
		$filterSelector = '#style-' . esc_attr($style_uid) . '.extended-posts-filter';

		if (isset($params['filter_buttons_width']) && $params['filter_buttons_width'] != '') {
			$value = $params['filter_buttons_width'];
			$unit = 'px';
			$last_result = substr($value, -1);
			if ($last_result == '%') {
				$value = str_replace('%', '', $value);
				$unit = $last_result;
			}
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list .portfolio-filter-item:not(.filters-apply-button) { width: ' . $value . $unit . '; }';
		}

		if (isset($params['filter_buttons_width_tablet']) && $params['filter_buttons_width_tablet'] != '') {
			$value = $params['filter_buttons_width_tablet'];
			$unit = 'px';
			$last_result = substr($value, -1);
			if ($last_result == '%') {
				$value = str_replace('%', '', $value);
				$unit = $last_result;
			}
			$inlineStyles .= '@media (max-width: 991px) {' . $filterSelector . ' .portfolio-filters-list .portfolio-filter-item:not(.filters-apply-button) { width: ' . $value . $unit . '; }}';
		}

		if (isset($params['filter_buttons_width_mobile']) && $params['filter_buttons_width_mobile'] != '') {
			$value = $params['filter_buttons_width_mobile'];
			$unit = 'px';
			$last_result = substr($value, -1);
			if ($last_result == '%') {
				$value = str_replace('%', '', $value);
				$unit = $last_result;
			}
			$inlineStyles .= '@media (max-width: 767px) {' . $filterSelector . ' .portfolio-filters-list .portfolio-filter-item:not(.filters-apply-button) { width: ' . $value . $unit . '; }}';
		}

		if (isset($params['filter_buttons_bottom_spacing']) && $params['filter_buttons_bottom_spacing'] != '') {
			$inlineStyles .= $filterSelector . ' { margin-bottom: ' . $params['filter_buttons_bottom_spacing'] . 'px; }';
		}

		if (isset($params['filter_buttons_bottom_spacing_tablet']) && $params['filter_buttons_bottom_spacing_tablet'] != '') {
			$inlineStyles .= '@media (max-width: 991px) {' . $filterSelector . ' { margin-bottom: ' . $params['filter_buttons_bottom_spacing_tablet'] . 'px; }}';
		}

		if (isset($params['filter_buttons_bottom_spacing_mobile']) && $params['filter_buttons_bottom_spacing_mobile'] != '') {
			$inlineStyles .= '@media (max-width: 767px) {' . $filterSelector . ' { margin-bottom: ' . $params['filter_buttons_bottom_spacing_mobile'] . 'px; }}';
		}

		if (isset($params['filter_buttons_space_between']) && $params['filter_buttons_space_between'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .widget-area { gap: ' . $params['filter_buttons_space_between'] . 'px; }' .
				$filterSelector . ' .portfolio-filters-list:is(.style-sidebar, .style-hidden, .style-standard-mobile) .portfolio-filter-item:not(:first-child) { padding-top: calc(' . $params['filter_buttons_space_between'] . 'px/2); }' .
				$filterSelector . ' .portfolio-filters-list:is(.style-sidebar, .style-hidden, .style-standard-mobile) .portfolio-filter-item:not(:last-child) { padding-bottom: calc(' . $params['filter_buttons_space_between'] . 'px/2); }';
		}

		if (isset($params['filter_buttons_space_between_tablet']) && $params['filter_buttons_space_between_tablet'] != '') {
			$inlineStyles .= '@media (max-width: 991px) {' . $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .widget-area { gap: ' . $params['filter_buttons_space_between_tablet'] . 'px; }' .
				$filterSelector . ' .portfolio-filters-list:is(.style-sidebar, .style-hidden, .style-standard-mobile) .portfolio-filter-item:not(:first-child) { padding-top: calc(' . $params['filter_buttons_space_between_tablet'] . 'px/2); }' .
				$filterSelector . ' .portfolio-filters-list:is(.style-sidebar, .style-hidden, .style-standard-mobile) .portfolio-filter-item:not(:last-child) { padding-bottom: calc(' . $params['filter_buttons_space_between_tablet'] . 'px/2); }}';
		}

		if (isset($params['filter_buttons_space_between_mobile']) && $params['filter_buttons_space_between_mobile'] != '') {
			$inlineStyles .= '@media (max-width: 767px) {' . $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .widget-area { gap: ' . $params['filter_buttons_space_between_mobile'] . 'px; }' .
				$filterSelector . ' .portfolio-filters-list:is(.style-sidebar, .style-hidden, .style-standard-mobile) .portfolio-filter-item:not(:first-child) { padding-top: calc(' . $params['filter_buttons_space_between_mobile'] . 'px/2); }' .
				$filterSelector . ' .portfolio-filters-list:is(.style-sidebar, .style-hidden, .style-standard-mobile) .portfolio-filter-item:not(:last-child) { padding-bottom: calc(' . $params['filter_buttons_space_between_mobile'] . 'px/2); }}';
		}

		if (!empty($params['filter_buttons_standard_alignment_vertical'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .widget-area { align-items: ' . $params['filter_buttons_standard_alignment_vertical'] . '; }';
		}

		if (!empty($params['apply_button_stretch_last_element'])) {
			$inlineStyles .= '@media (min-width: 992px) {' . $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item:last-child { flex: auto; }}';
		}

		if (!empty($params['apply_button_stretch_last_element_tablet'])) {
			$inlineStyles .= '@media (min-width: 768px) and (max-width: 991px) {' . $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item:last-child { flex: auto; }}';
		}

		if (!empty($params['apply_button_stretch_last_element_mobile'])) {
			$inlineStyles .= '@media (max-width: 767px) {' . $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item:last-child { flex: auto; }}';
		}

		if (!empty($params['filter_buttons_filterby_color'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list .portfolio-filter-item .widget-title { color: ' . $params['filter_buttons_filterby_color'] . '; }';
		}

		if (isset($params['filter_buttons_filterby_spacing']) && $params['filter_buttons_filterby_spacing'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list .portfolio-filter-item .widget-title { margin-bottom: ' . $params['filter_buttons_filterby_spacing'] . 'px; }';
		}

		if (isset($params['filter_buttons_filterby_spacing_tablet']) && $params['filter_buttons_filterby_spacing_tablet'] != '') {
			$inlineStyles .= '@media (max-width: 991px) {' . $filterSelector . ' .portfolio-filters-list .portfolio-filter-item .widget-title { margin-bottom: ' . $params['filter_buttons_filterby_spacing_tablet'] . 'px; }}';
		}

		if (isset($params['filter_buttons_filterby_spacing_mobile']) && $params['filter_buttons_filterby_spacing_mobile'] != '') {
			$inlineStyles .= '@media (max-width: 767px) {' . $filterSelector . ' .portfolio-filters-list .portfolio-filter-item .widget-title { margin-bottom: ' . $params['filter_buttons_filterby_spacing_mobile'] . 'px; }}';
		}

		if (!empty($params['filter_buttons_dropdown_selector_color'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) .name,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .selector-title,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter .portfolio-search-filter-button { color: ' . $params['filter_buttons_dropdown_selector_color'] . '; }';
		}

		if (!empty($params['filter_buttons_dropdown_selector_background_color'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) .name,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .selector-title,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input { background-color: ' . $params['filter_buttons_dropdown_selector_background_color'] . '; }';
		}

		if (isset($params['filter_buttons_dropdown_selector_border_radius']) && $params['filter_buttons_dropdown_selector_border_radius'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) .name,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .selector-title,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input { border-radius: ' . $params['filter_buttons_dropdown_selector_border_radius'] . 'px; }';
		}

		if (isset($params['filter_buttons_dropdown_selector_border_width']) && $params['filter_buttons_dropdown_selector_border_width'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) .name,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .selector-title,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input { border-width: ' . $params['filter_buttons_dropdown_selector_border_width'] . 'px; }';
		}

		if (!empty($params['filter_buttons_dropdown_selector_border_color'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) .name,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .selector-title,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input { border-color: ' . $params['filter_buttons_dropdown_selector_border_color'] . '; }';
		}

		if (isset($params['filter_buttons_dropdown_selector_padding_top']) && $params['filter_buttons_dropdown_selector_padding_top'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) .name,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .selector-title,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input { padding-top: ' . $params['filter_buttons_dropdown_selector_padding_top'] . 'px; }';
		}

		if (isset($params['filter_buttons_dropdown_selector_padding_bottom']) && $params['filter_buttons_dropdown_selector_padding_bottom'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) .name,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .selector-title,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input { padding-bottom: ' . $params['filter_buttons_dropdown_selector_padding_bottom'] . 'px; }';
		}

		if (isset($params['filter_buttons_dropdown_selector_padding_left']) && $params['filter_buttons_dropdown_selector_padding_left'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) .name,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .selector-title,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input { padding-left: ' . $params['filter_buttons_dropdown_selector_padding_left'] . 'px; }';
		}

		if (isset($params['filter_buttons_dropdown_selector_padding_right']) && $params['filter_buttons_dropdown_selector_padding_right'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) .name,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .selector-title,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input { padding-right: ' . $params['filter_buttons_dropdown_selector_padding_right'] . 'px; }';
		}

		if (!empty($params['filter_buttons_dropdown_background'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .portfolio-filter-item-list { background-color: ' . $params['filter_buttons_dropdown_background'] . '; }';
		}

		if (isset($params['filter_buttons_dropdown_border_radius']) && $params['filter_buttons_dropdown_border_radius'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .portfolio-filter-item-list { border-radius: ' . $params['filter_buttons_dropdown_border_radius'] . 'px; }';
		}

		if (!empty($params['filter_buttons_dropdown_text_color_normal'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list ul li a,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .portfolio-filter-item-list ul li a,' .
				$filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .portfolio-filter-item-list .price-range-slider .slider-amount,' .
				$filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount .slider-amount-text,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .portfolio-filter-item-list .price-range-slider .slider-amount .slider-amount-text { color: ' . $params['filter_buttons_dropdown_text_color_normal'] . '; }';
		}

		if (!empty($params['filter_buttons_dropdown_text_color_hover'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list ul li a:hover,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .portfolio-filter-item-list ul li a:hover { color: ' . $params['filter_buttons_dropdown_text_color_hover'] . '; }';
		}

		if (!empty($params['filter_buttons_dropdown_text_color_active'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list ul li a.active,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .portfolio-filter-item-list ul li a.active { color: ' . $params['filter_buttons_dropdown_text_color_active'] . '; }';
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-range,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-range,' .
				$filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-handle + span,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-handle + span,' .
				$filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-handle,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .portfolio-filter-item-list .price-range-slider .slider-range .ui-slider-handle { background-color: ' . $params['filter_buttons_dropdown_text_color_active'] . '; }';
		}

		if (!empty($params['filter_buttons_dropdown_price_range_background_color_normal'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .portfolio-filter-item-list .price-range-slider .slider-amount { background-color: ' . $params['filter_buttons_dropdown_price_range_background_color_normal'] . '; }';
		}

		if (!empty($params['filter_buttons_dropdown_price_range_background_color_hover'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount:hover,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .portfolio-filter-item-list .price-range-slider .slider-amount:hover { background-color: ' . $params['filter_buttons_dropdown_price_range_background_color_hover'] . '; }';
		}

		if (!empty($params['filter_buttons_list_text_color_normal'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list:is(.style-sidebar, .style-hidden, .style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) ul li a,' .
				$filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .portfolio-filter-item-list .price-range-slider .slider-amount,' .
				$filterSelector . ' .portfolio-filters-list.style-standard:not(.style-standard-mobile) .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount .slider-amount-text,' .
				$filterSelector . ' .portfolio-filters-list .portfolio-filter-item.display-type-dropdown .portfolio-filter-item-list .price-range-slider .slider-amount .slider-amount-text { color: ' . $params['filter_buttons_list_text_color_normal'] . '; }';
		}

		if (!empty($params['filter_buttons_list_text_color_hover'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list:is(.style-sidebar, .style-hidden, .style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) ul li a:hover { color: ' . $params['filter_buttons_list_text_color_hover'] . '; }';
		}

		if (!empty($params['filter_buttons_list_text_color_active'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list:is(.style-sidebar, .style-hidden, .style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) ul li a.active { color: ' . $params['filter_buttons_list_text_color_active'] . '; }';
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list:is(.style-sidebar, .style-hidden, .style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) .price-range-slider .slider-range .ui-slider-range,' .
				$filterSelector . ' .portfolio-filters-list:is(.style-sidebar, .style-hidden, .style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) .price-range-slider .slider-range .ui-slider-handle + span,' .
				$filterSelector . ' .portfolio-filters-list:is(.style-sidebar, .style-hidden, .style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) .price-range-slider .slider-range .ui-slider-handle { background-color: ' . $params['filter_buttons_list_text_color_active'] . '; }';
		}

		if (!empty($params['filter_buttons_list_price_range_background_color_normal'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list:is(.style-sidebar, .style-hidden, .style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) .price-range-slider .slider-amount { background-color: ' . $params['filter_buttons_list_price_range_background_color_normal'] . '; }';
		}

		if (!empty($params['filter_buttons_list_price_range_background_color_hover'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list:is(.style-sidebar, .style-hidden, .style-standard-mobile) .portfolio-filter-item:not(.display-type-dropdown) .price-range-slider .slider-amount:hover { background-color: ' . $params['filter_buttons_list_price_range_background_color_hover'] . '; }';
		}

		if (isset($params['items_list_max_height']) && $params['items_list_max_height'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filter-item-list { max-height: ' . $params['items_list_max_height'] . 'px; padding-right: 10px; }';
		}

		if (isset($params['filter_buttons_hidden_sidebar_separator_width']) && $params['filter_buttons_hidden_sidebar_separator_width'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list:is(.style-hidden, .style-sidebar, .style-standard-mobile) .portfolio-filter-item { border-width: ' . $params['filter_buttons_hidden_sidebar_separator_width'] . 'px; }';
		}

		if (!empty($params['filter_buttons_hidden_sidebar_separator_color'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list:is(.style-hidden, .style-sidebar, .style-standard-mobile) .portfolio-filter-item { border-color: ' . $params['filter_buttons_hidden_sidebar_separator_color'] . '; }';
		}

		if (!empty($params['filter_buttons_hidden_position'])) {

			$position = '';
			switch ($params['filter_buttons_hidden_position']) {
				case 'left':
					$position = 'margin-left: 0; margin-right: auto;';
					break;
				case 'center':
					$position = 'margin-left: auto; margin-right: auto;';
					break;
				case 'right':
					$position = 'margin-left: auto; margin-right: 0;';
					break;
				case 'justify':
					$position = 'width: 100%; margin-left: 0; margin-right: 0;';
					break;
			}
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list .portfolio-show-filters-button { ' . $position . ' }';
		}

		if (!empty($params['filter_buttons_hidden_color'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list .portfolio-show-filters-button { color: ' . $params['filter_buttons_hidden_color'] . '; }';
		}

		if (!empty($params['filter_buttons_hidden_background_color'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list .portfolio-show-filters-button { background-color: ' . $params['filter_buttons_hidden_background_color'] . '; }';
		}

		if (isset($params['filter_buttons_hidden_border_radius']) && $params['filter_buttons_hidden_border_radius'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list .portfolio-show-filters-button { border-radius: ' . $params['filter_buttons_hidden_border_radius'] . 'px; }';
		}

		if (isset($params['filter_buttons_hidden_border_width']) && $params['filter_buttons_hidden_border_width'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list .portfolio-show-filters-button { border-width: ' . $params['filter_buttons_hidden_border_width'] . 'px; }';
		}

		if (isset($params['filter_buttons_hidden_padding_top']) && $params['filter_buttons_hidden_padding_top'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list .portfolio-show-filters-button { padding-top: ' . $params['filter_buttons_hidden_padding_top'] . 'px; }';
		}

		if (isset($params['filter_buttons_hidden_padding_bottom']) && $params['filter_buttons_hidden_padding_bottom'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list .portfolio-show-filters-button { padding-bottom: ' . $params['filter_buttons_hidden_padding_bottom'] . 'px; }';
		}

		if (isset($params['filter_buttons_hidden_padding_left']) && $params['filter_buttons_hidden_padding_left'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list .portfolio-show-filters-button { padding-left: ' . $params['filter_buttons_hidden_padding_left'] . 'px; }';
		}

		if (isset($params['filter_buttons_hidden_padding_right']) && $params['filter_buttons_hidden_padding_right'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list .portfolio-show-filters-button { padding-right: ' . $params['filter_buttons_hidden_padding_right'] . 'px; }';
		}

		if (!empty($params['filter_buttons_hidden_enable_shadow'])) {
			$shadow_position = '';
			if ($params['filter_buttons_hidden_shadow_position'] == 'inset') {
				$shadow_position = 'inset';
			}
			$shadow_horizontal = $params['filter_buttons_hidden_shadow_horizontal'] ?: 0;
			$shadow_vertical = $params['filter_buttons_hidden_shadow_vertical'] ?: 0;
			$shadow_blur = $params['filter_buttons_hidden_shadow_blur'] ?: 0;
			$shadow_spread = $params['filter_buttons_hidden_shadow_spread'] ?: 0;
			$shadow_color = $params['filter_buttons_hidden_shadow_color'] ?: '#000';

			$inlineStyles .= $filterSelector . " .portfolio-filters-list .portfolio-show-filters-button { box-shadow: " . $shadow_position . " " . $shadow_horizontal . "px " . $shadow_vertical . "px " . $shadow_blur . "px " . $shadow_spread . "px " . $shadow_color . "; }";
		}

		if (!empty($params['filter_buttons_hidden_sidebar_background'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list:is(.style-hidden, .style-sidebar-mobile, .style-standard-mobile) .portfolio-filters-outer .portfolio-filters-area { background-color: ' . $params['filter_buttons_hidden_sidebar_background'] . '; }';
		}

		if (!empty($params['filter_buttons_hidden_sidebar_overlay_color'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list:is(.style-hidden, .style-sidebar-mobile, .style-standard-mobile) .portfolio-filters-outer { background-color: ' . $params['filter_buttons_hidden_sidebar_overlay_color'] . '; }';
		}

		if (!empty($params['filter_buttons_hidden_sidebar_close_icon_color'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-filters-list .portfolio-close-filters { color: ' . $params['filter_buttons_hidden_sidebar_close_icon_color'] . '; }';
		}

		if (!empty($params['apply_button_position'])) {
			$inlineStyles .= $filterSelector . ' .filters-apply-button { text-align: ' . $params['apply_button_position'] . '; }';
		}

		if (!empty($params['apply_button_stretch_full_width'])) {
			$inlineStyles .= $filterSelector . ' .filters-apply-button { flex: auto; }';
			$inlineStyles .= $filterSelector . ' .filters-apply-button .gem-button { width: 100%; }';
		}

		if (isset($params['apply_button_border_radius']) && $params['apply_button_border_radius'] != '') {
			$inlineStyles .= $filterSelector . ' .filters-apply-button .gem-button { border-radius: ' . $params['apply_button_border_radius'] . 'px; }';
		}

		if (isset($params['apply_button_border_width']) && $params['apply_button_border_width'] != '') {
			$inlineStyles .= $filterSelector . ' .filters-apply-button .gem-button { border-width: ' . $params['apply_button_border_width'] . 'px; }';
		}

		if (!empty($params['apply_button_text_color_normal'])) {
			$inlineStyles .= $filterSelector . ' .filters-apply-button .gem-button { color: ' . $params['apply_button_text_color_normal'] . '; }';
		}

		if (!empty($params['apply_button_text_color_hover'])) {
			$inlineStyles .= $filterSelector . ' .filters-apply-button .gem-button:hover { color: ' . $params['apply_button_text_color_hover'] . '; }';
		}

		if (!empty($params['apply_button_background_color_normal'])) {
			$inlineStyles .= $filterSelector . ' .filters-apply-button .gem-button { background-color: ' . $params['apply_button_background_color_normal'] . '; }';
		}

		if (!empty($params['apply_button_background_color_hover'])) {
			$inlineStyles .= $filterSelector . ' .filters-apply-button .gem-button:hover { background-color: ' . $params['apply_button_background_color_hover'] . '; }';
		}

		if (!empty($params['apply_button_border_color_normal'])) {
			$inlineStyles .= $filterSelector . ' .filters-apply-button .gem-button { border-color: ' . $params['apply_button_border_color_normal'] . '; }';
		}

		if (!empty($params['apply_button_border_color_hover'])) {
			$inlineStyles .= $filterSelector . ' .filters-apply-button .gem-button:hover { border-color: ' . $params['apply_button_border_color_hover'] . '; }';
		}

		if (isset($params['apply_button_padding_top']) && $params['apply_button_padding_top'] != '') {
			$inlineStyles .= $filterSelector . ' .filters-apply-button .gem-button { padding-top: ' . $params['apply_button_padding_top'] . 'px; }';
		}

		if (isset($params['apply_button_padding_bottom']) && $params['apply_button_padding_bottom'] != '') {
			$inlineStyles .= $filterSelector . ' .filters-apply-button .gem-button { padding-bottom: ' . $params['apply_button_padding_bottom'] . 'px; }';
		}

		if (isset($params['apply_button_padding_left']) && $params['apply_button_padding_left'] != '') {
			$inlineStyles .= $filterSelector . ' .filters-apply-button .gem-button { padding-left: ' . $params['apply_button_padding_left'] . 'px; }';
		}

		if (isset($params['apply_button_padding_right']) && $params['apply_button_padding_right'] != '') {
			$inlineStyles .= $filterSelector . ' .filters-apply-button .gem-button { padding-right: ' . $params['apply_button_padding_right'] . 'px; }';
		}

		if (!empty($params['apply_button_enable_shadow'])) {
			$shadow_position = '';
			if ($params['apply_button_shadow_position'] == 'inset') {
				$shadow_position = 'inset';
			}
			$shadow_horizontal = $params['apply_button_shadow_horizontal'] ?: 0;
			$shadow_vertical = $params['apply_button_shadow_vertical'] ?: 0;
			$shadow_blur = $params['apply_button_shadow_blur'] ?: 0;
			$shadow_spread = $params['apply_button_shadow_spread'] ?: 0;
			$shadow_color = $params['apply_button_shadow_color'] ?: '#000';

			$inlineStyles .= $filterSelector . " .filters-apply-button .gem-button { box-shadow: " . $shadow_position . " " . $shadow_horizontal . "px " . $shadow_vertical . "px " . $shadow_blur . "px " . $shadow_spread . "px " . $shadow_color . "; }";
		}

		if (!empty($inlineStyles)) {
			echo '<style>'.$inlineStyles.'</style>';
		}
	}
}

function thegem_extended_sorting($params) {
	$style_uid = substr(md5(rand()), 0, 7);
	if ($params['grid_filter'] == 'id') {
		$grid_uid = $params['grid_id'];
		$grid_uid_url = $grid_uid . '-';
	} else {
		$grid_uid = $grid_uid_url = '';
	}

	wp_enqueue_style('thegem-portfolio-filters-list');
	wp_enqueue_script('thegem-portfolio-grid-extended');

	parse_str($_SERVER['QUERY_STRING'], $url_params);

	$orderby = $order = 'default';

	if (!empty($url_params) && $grid_uid_url != '-') {
		foreach ($url_params as $name => $value) {
			if (str_contains($name, $grid_uid_url . 'orderby')) {
				$orderby = $value;
			}
			if (str_contains($name, $grid_uid_url . 'order')) {
				$order = $value;
			}
		}
	}

	$repeater_sort = vc_param_group_parse_atts($params['repeater_sort']);
	if (!empty($repeater_sort)) { ?>
		<div id="style-<?php echo esc_attr($style_uid); ?>" class="extended-posts-sorting" data-portfolio-uid="<?php echo esc_attr($grid_uid); ?>">
			<div class="portfolio-sorting-select open-dropdown-<?php
			echo $params['sorting_dropdown_open'];
			echo ' alignment-' . $params['sorting_horizontal_alignment'];
			echo !empty($params['sorting_scroll_top']) ? ' scroll-top' : ''; ?>">
				<div class="portfolio-sorting-select-current">
					<div class="portfolio-sorting-select-name">
						<?php
						if ($orderby == 'default') {
							echo esc_html($params['sorting_default_text']);
						} else {
							foreach ($repeater_sort as $item) {
								if (in_array($item['attribute_type'], ['date', 'title', 'price', 'rating', 'popularity'])) {
									$sort_by = $item['attribute_type'];
								} else {
									if ($item['attribute_type'] == 'details') {
										$sort_by = isset($item['attribute_details']) ? $item['attribute_details'] : '';
									} else if ($item['attribute_type'] == 'custom_fields') {
										$sort_by = isset($item['attribute_custom_fields']) ? $item['attribute_custom_fields'] : '';
									} else if ($item['attribute_type'] == 'manual_key') {
										$sort_by = isset($item['manual_key_field']) ? $item['manual_key_field'] : '';
									} else {
										$sort_by = isset($item['attribute_custom_fields_acf_' . $item['attribute_type']]) ? $item['attribute_custom_fields_acf_' . $item['attribute_type']] : '';
									}
									if (empty($sort_by)) continue;
									if (isset($item['field_type']) && $item['field_type'] == 'number') {
										$sort_by = 'num_' . $sort_by;
									}
								}
								if ($orderby == $sort_by && $order == $item['sort_order']) {
									echo esc_html($item['title']);
									break;
								}
							}
						} ?>
					</div>
					<span class="portfolio-sorting-select-current-arrow"></span>
				</div>
				<ul>
					<li class="default <?php echo $orderby == 'default' ? 'portfolio-sorting-select-current' : ''; ?>"
						data-orderby="default" data-order="default">
						<?php echo esc_html($params['sorting_default_text']); ?>
					</li>
					<?php foreach ($repeater_sort as $item) {
						if (in_array($item['attribute_type'], ['date', 'title', 'price', 'rating', 'popularity'])) {
							$sort_by = $item['attribute_type'];
						} else {
							if ($item['attribute_type'] == 'details') {
								$sort_by = isset($item['attribute_details']) ? $item['attribute_details'] : '';
							} else if ($item['attribute_type'] == 'custom_fields') {
								$sort_by = isset($item['attribute_custom_fields']) ? $item['attribute_custom_fields'] : '';
							} else if ($item['attribute_type'] == 'manual_key') {
								$sort_by = isset($item['manual_key_field']) ? $item['manual_key_field'] : '';
							} else {
								$sort_by = isset($item['attribute_custom_fields_acf_' . $item['attribute_type']]) ? $item['attribute_custom_fields_acf_' . $item['attribute_type']] : '';
							}
							if (empty($sort_by)) continue;
							if (isset($item['field_type']) && $item['field_type'] == 'number') {
								$sort_by = 'num_' . $sort_by;
							}
						} ?>
						<li class="<?php echo $orderby == $sort_by && $order == $item['sort_order'] ? 'portfolio-sorting-select-current' : ''; ?>"
							data-orderby="<?php echo esc_attr($sort_by); ?>" data-order="<?php echo esc_attr($item['sort_order']); ?>">
							<?php echo esc_html($item['title']); ?>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<?php if (thegem_is_plugin_active('js_composer/js_composer.php')) {
			global $vc_manager;
			if ($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode() == 'page_editable') { ?>
				<script type="text/javascript">
					(function ($) {
						setTimeout(function () {
							$('#style-<?php echo esc_attr($style_uid); ?>.extended-posts-filter').initPortfolioSorting();
						}, 1000);
					})(jQuery);
				</script>
			<?php }
		}

		$inlineStyles = '';
		$filterSelector = '#style-' . esc_attr($style_uid) . '.extended-posts-sorting';

		if (isset($params['sorting_width']) && $params['sorting_width'] != '') {
			$value = $params['sorting_width'];
			$unit = 'px';
			$last_result = substr($value, -1);
			if ($last_result == '%') {
				$value = str_replace('%', '', $value);
				$unit = $last_result;
			}
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select { width: ' . $value . $unit . '; }';
		}

		if (isset($params['sorting_width_tablet']) && $params['sorting_width_tablet'] != '') {
			$value = $params['sorting_width_tablet'];
			$unit = 'px';
			$last_result = substr($value, -1);
			if ($last_result == '%') {
				$value = str_replace('%', '', $value);
				$unit = $last_result;
			}
			$inlineStyles .= '@media (max-width: 991px) {' . $filterSelector . ' .portfolio-sorting-select { width: ' . $value . $unit . '; }}';
		}

		if (isset($params['sorting_width_mobile']) && $params['sorting_width_mobile'] != '') {
			$value = $params['sorting_width_mobile'];
			$unit = 'px';
			$last_result = substr($value, -1);
			if ($last_result == '%') {
				$value = str_replace('%', '', $value);
				$unit = $last_result;
			}
			$inlineStyles .= '@media (max-width: 767px) {' . $filterSelector . ' .portfolio-sorting-select { width: ' . $value . $unit . '; }}';
		}

		if (isset($params['sorting_extended_bottom_spacing']) && $params['sorting_extended_bottom_spacing'] != '') {
			$inlineStyles .= $filterSelector . ' { margin-bottom: ' . $params['sorting_extended_bottom_spacing'] . 'px; }';
		}

		if (isset($params['sorting_extended_bottom_spacing_tablet']) && $params['sorting_extended_bottom_spacing_tablet'] != '') {
			$inlineStyles .= '@media (max-width: 991px) {' . $filterSelector . ' { margin-bottom: ' . $params['sorting_extended_bottom_spacing_tablet'] . 'px; }}';
		}

		if (isset($params['sorting_extended_bottom_spacing_mobile']) && $params['sorting_extended_bottom_spacing_mobile'] != '') {
			$inlineStyles .= '@media (max-width: 767px) {' . $filterSelector . ' { margin-bottom: ' . $params['sorting_extended_bottom_spacing_mobile'] . 'px; }}';
		}

		if (!empty($params['sorting_extended_text_color'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select div.portfolio-sorting-select-current { color: ' . $params['sorting_extended_text_color'] . '; }';
		}

		if (!empty($params['sorting_extended_border_color'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select div.portfolio-sorting-select-current { border-color: ' . $params['sorting_extended_border_color'] . '; }';
		}

		if (!empty($params['sorting_extended_background_color'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select div.portfolio-sorting-select-current { background-color: ' . $params['sorting_extended_background_color'] . '; }';
		}

		if (isset($params['sorting_extended_border_radius']) && $params['sorting_extended_border_radius'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select div.portfolio-sorting-select-current { border-radius: ' . $params['sorting_extended_border_radius'] . 'px; }';
		}

		if (isset($params['sorting_extended_border_width']) && $params['sorting_extended_border_width'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select div.portfolio-sorting-select-current { border-width: ' . $params['sorting_extended_border_width'] . 'px; }';
		}

		if (isset($params['sorting_extended_padding_top']) && $params['sorting_extended_padding_top'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select div.portfolio-sorting-select-current { padding-top: ' . $params['sorting_extended_padding_top'] . 'px; }';
		}

		if (isset($params['sorting_extended_padding_bottom']) && $params['sorting_extended_padding_bottom'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select div.portfolio-sorting-select-current { padding-bottom: ' . $params['sorting_extended_padding_bottom'] . 'px; }';
		}

		if (isset($params['sorting_extended_padding_left']) && $params['sorting_extended_padding_left'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select div.portfolio-sorting-select-current { padding-left: ' . $params['sorting_extended_padding_left'] . 'px; }';
		}

		if (isset($params['sorting_extended_padding_right']) && $params['sorting_extended_padding_right'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select div.portfolio-sorting-select-current { padding-right: ' . $params['sorting_extended_padding_right'] . 'px; }';
		}

		if (!empty($params['sorting_extended_dropdown_text_color_normal'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select ul li { color: ' . $params['sorting_extended_dropdown_text_color_normal'] . '; }';
		}

		if (!empty($params['sorting_extended_dropdown_text_color_hover'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select ul li:hover { color: ' . $params['sorting_extended_dropdown_text_color_hover'] . '; }';
		}

		if (!empty($params['sorting_extended_dropdown_text_color_active'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select ul li.portfolio-sorting-select-current { color: ' . $params['sorting_extended_dropdown_text_color_active'] . '; }';
		}

		if (!empty($params['sorting_extended_dropdown_background_color'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select ul { background-color: ' . $params['sorting_extended_dropdown_background_color'] . '; }';
		}

		if (!empty($params['sorting_extended_dropdown_border_color'])) {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select ul { border-color: ' . $params['sorting_extended_dropdown_border_color'] . '; }';
		}

		if (isset($params['sorting_extended_dropdown_border_radius']) && $params['sorting_extended_dropdown_border_radius'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select ul { border-radius: ' . $params['sorting_extended_dropdown_border_radius'] . 'px; }';
		}

		if (isset($params['sorting_extended_dropdown_border_width']) && $params['sorting_extended_dropdown_border_width'] != '') {
			$inlineStyles .= $filterSelector . ' .portfolio-sorting-select ul { border-width: ' . $params['sorting_extended_dropdown_border_width'] . 'px; border-style: solid; }';
		}

		if (!empty($inlineStyles)) {
			echo '<style>'.$inlineStyles.'</style>';
		}
	}
}

if (!function_exists('get_thegem_extended_blog_posts')) {
	function get_thegem_extended_blog_posts($post_type, $taxonomy_filter, $meta_filter, $manual_selection, $exclude, $authors, $page = 1, $ppp = -1, $orderby = '', $order = '', $offset = false, $ignore_sticky_posts = false, $search = null, $search_by = 'content', $date_query = '', $show_all = false) {

		$args = array(
			'post_type' => $post_type,
			'post_status' => 'publish',
			'posts_per_page' => $ppp,
		);

		if ($orderby == 'default') {
			$args['orderby'] = 'menu_order date';
		} else if (!empty($orderby)) {
			$args['orderby'] = $orderby;
			if (!in_array($orderby, ['date', 'id', 'author', 'title', 'name', 'modified', 'comment_count', 'rand', 'menu_order date'])) {
				if (strpos($orderby, 'num_') === 0) {
					$args['orderby'] = 'meta_value_num';
					$args['meta_key'] = str_replace('num_', '', $orderby);
				} else {
					$args['orderby'] = 'meta_value';
					$args['meta_key'] = $orderby;
				}
			}
		}

		if (!empty($order) && $orderby !== 'default') {
			$args['order'] = $order;
		}

		$tax_query = $meta_query = [];

		if (!empty($taxonomy_filter)) {
			foreach ($taxonomy_filter as $tax => $tax_arr) {
				if (!empty($tax_arr) && !in_array('0', $tax_arr)) {
					$query_arr = array(
						'taxonomy' => $tax,
						'field' => 'slug',
						'terms' => $tax_arr,
					);
				} else {
					$query_arr = array(
						'taxonomy' => $tax,
						'operator' => 'EXISTS'
					);
				}
				$tax_query[] = $query_arr;
			}
		}

		if (!empty($meta_filter)) {
			foreach ($meta_filter as $meta => $meta_arr) {
				if (!empty($meta_arr)) {
					if (strpos($meta, "__range") > 0) {
						$meta = str_replace("__range","", $meta);
						$query_arr = array(
							'key' => $meta,
							'value' => $meta_arr,
							'compare'   => 'BETWEEN',
							'type'   => 'NUMERIC',
						);
					} else if (strpos($meta, "__check") > 0) {
						$meta = str_replace("__check","", $meta);
						$check_meta_query = array(
							'relation' => 'OR',
						);
						foreach ($meta_arr as $value) {
							$check_meta_query[] = array(
								'key' => $meta,
								'value' => sprintf('"%s"', $value),
								'compare' => 'LIKE',
							);
						}
						$query_arr = $check_meta_query;
					} else {
						$query_arr = array(
							'key' => $meta,
							'value' => $meta_arr,
							'compare' => 'IN',
						);
					}
					$meta_query[] = $query_arr;
				}
			}
		}

		if (!empty($search) && $search_by != 'content') {
			$search_meta_query = array(
				'relation' => 'OR',
			);
			foreach ($search_by as $key) {
				$search_meta_query[] = array(
					'key' => $key,
					'value' => $search,
					'compare' => 'LIKE'
				);
			}
			$meta_query[] = $search_meta_query;
		}

		if (!empty($tax_query)) {
			$args['tax_query'] = $tax_query;
		}

		if (!empty($meta_query)) {
			$args['meta_query'] = $meta_query;
		}

		if (!empty($manual_selection)) {
			$args['post__in'] = $manual_selection;
		}

		if (!empty($exclude)) {
			$args['post__not_in'] = $exclude;
		}

		if (!empty($authors)) {
			$args['author__in'] = $authors;
		}

		if ($ignore_sticky_posts) {
			$args['ignore_sticky_posts'] = 1;
		}

		if (!empty($date_query)) {
			$args['date_query'] = array($date_query);
		}

		if (!empty($offset) || $show_all) {
			$args['offset'] = $ppp * ($page - 1) + $offset;
		} else {
			$args['paged'] = $page;
		}

		if ($show_all) {
			$args['posts_per_page'] = 999;
		}

		if (!empty($search) && $search_by == 'content') {
			$args['s'] = $search;
		}

		return new WP_Query($args);
	}
}

function gem_featured_posts_slider($params) {

    if (empty($params['max_height'])) {
        $params['max_height'] = $params['layout']=='container' ? '628' : '758';
    }

	$params['button']['icon'] = '';
	if (!empty($params['button']['icon_elegant']) && $params['button']['icon_pack'] == 'elegant') {
		$params['button']['icon'] = $params['button']['icon_elegant'];
	}
	if (!empty($params['button']['icon_material']) && $params['button']['icon_pack'] == 'material') {
		$params['button']['icon'] = $params['button']['icon_material'];
	}
	if (!empty($params['button']['icon_fontawesome']) && $params['button']['icon_pack'] == 'fontawesome') {
		$params['button']['icon'] = $params['button']['icon_fontawesome'];
	}
	if (!empty($params['button']['icon_thegem_header']) && $params['button']['icon_pack'] == 'thegem-header') {
		$params['button']['icon'] = $params['button']['icon_thegem_header'];
	}
	if (!empty($params['button']['icon_userpack']) && $params['button']['icon_pack'] == 'userpack') {
		$params['button']['icon'] = $params['button']['icon_userpack'];
	}

    wp_enqueue_style('thegem-featured-posts-slider');
    wp_enqueue_style('icons-arrows');
    wp_enqueue_script('thegem-featured-posts-slider');

	$single_post_id = thegem_templates_init_post() ? thegem_templates_init_post()->ID : get_the_ID();
	extract(thegem_posts_query_section_render($params, true));
	$terms = isset($taxonomy_filter['category']) ? $taxonomy_filter['category'] : [];

	$query_args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
    );

	$tax_query = [];

	if (($params['query_type'] == 'custom' && $params['source'] == 'featured') || ($params['query_type'] == 'archive' && $params['featured_source'] == 'featured')) {
		$query_args['post__not_in'] = array($single_post_id);
		$query_args['meta_query'] = array(
			array(
				'key' => 'thegem_show_featured_posts_slider',
				'value' => 1
			)
		);
	}

	if (!empty($taxonomy_filter)) {
		foreach ($taxonomy_filter as $tax => $tax_arr) {
			if (!empty($tax_arr) && !in_array('0', $tax_arr)) {
				$query_arr = array(
					'taxonomy' => $tax,
					'field' => 'slug',
					'terms' => $tax_arr,
				);
			} else {
				$query_arr = array(
					'taxonomy' => $tax,
					'operator' => 'EXISTS'
				);
			}
			$tax_query[] = $query_arr;
		}
	}

	if (!empty($tax_query)) {
		$query_args['tax_query'] = $tax_query;
	}

	if (!empty($date_query)) {
		$query_args['date_query'] = array($date_query);
	}

	if (!empty($manual_selection)) {
		$query_args['post__in'] = $manual_selection;
	}

	if (!empty($exclude)) {
		$query_args['post__not_in'] = $exclude;
	}

	if (!empty($blog_authors)) {
		$query_args['author__in'] = $blog_authors;
	}

	if ($params['order_by'] == 'default' || $params['order_by'] == 'date_asc' || $params['order_by'] == 'date_desc') {
		$query_args['orderby'] = 'date';
		$query_args['order'] = $params['order_by'] == 'date_asc' ? 'ASC' : 'DESC';
	} else {
		$query_args['orderby'] = $params['order_by'];
	}

	if (isset($params['order']) && $params['order'] != 'default') {
		$query_args['order'] = $params['order'];
	}

	if (!empty($params['offset'])) {
		$query_args['offset'] = $params['offset'];
	}

    $query_args['posts_per_page'] = !empty($params['max_posts']) ? $params['max_posts'] : 3;
    $query = new WP_Query($query_args);

    $slider_classes = array();

    if ($params['layout']=='fullwidth') {
        $slider_classes[] = 'fullwidth-block';
    }

    if ($params['centered_captions']) {
        $slider_classes[] = 'centered-captions';
    }

    $slider_classes[] = 'style-'.esc_attr($params['style']);
    $slider_classes = implode(' ', $slider_classes);

    if ($params['paginator']['type'] == 'bullets') {
        $params['paginator']['color']=thegem_get_option('styled_elements_color_1');
    }
	$slider_uid = uniqid('gem-featured-posts-slider-');
	$slide_num = 0;

    if ($query->have_posts()) :
		$inlineStyles = '';

		if (isset($params['title_letter_spacing'])) {
			$inlineStyles .= '#' . $slider_uid . ' .gem-featured-post-title { letter-spacing: ' . esc_attr($params['title_letter_spacing']) . 'px !important; }';
		}

		if (!empty($params['post_categories_color']) && $params['style']=='new') {
			$inlineStyles .= '#' . $slider_uid . ' .set { background-color: ' . esc_attr($params['post_categories_color']) . '; }';
		}

		if (!empty($params['post_categories_color']) && $params['style']=='default') {
			$inlineStyles .= '#' . $slider_uid . ' .set { color: ' . esc_attr($params['post_categories_color']) . '; }';
		}

		if (!empty($inlineStyles)) {
			echo '<style>'.$inlineStyles.'</style>';
		}

		echo thegem_portfolio_news_render_styles($params, '#' . $slider_uid);

		if ($params['fullheight']) {
			$preloader_style = 'height: 100vh;';
		} else {
			$preloader_style = 'height: '.esc_attr($params['max_height']).'px;';
		} ?>
        <div class="preloader default-background <?php echo $params['layout']=='fullwidth' ? 'fullwidth-preloader' : ''; ?>" style="<?php echo $preloader_style; ?>"><div class="preloader-spin-new"></div></div>
        <div id="<?= esc_attr($slider_uid); ?>" class="gem-featured-posts-slider <?php echo $slider_classes ?>"
             data-paginator="<?php echo htmlspecialchars(json_encode($params['paginator'])); ?>"
             data-sliding-effect="<?php echo esc_attr($params['sliding_effect']); ?>"
             data-auto-scroll=<?php echo (intval($params['auto_scroll']) > 0  ? esc_attr(intval($params['auto_scroll'])) : 'false') ?>
        >
            <?php while($query->have_posts()) {
                $query->the_post();
                include(locate_template(array('gem-templates/blog/content-blog-item-featured-posts-slider.php')));
            } ?>

        </div>
    <?php endif;

	thegem_templates_close_post('gem_featured_posts_slider', ['name' => __('Featured Posts Slider', 'thegem')], $query->have_posts());

	if ($params['layout'] == 'fullwidth') {
		echo '<script type="text/javascript">if (typeof(gem_fix_fullwidth_position) == "function") { gem_fix_fullwidth_position(document.getElementById("' . esc_attr($slider_uid) . '")); }</script>';
	}
}

// Print Thegem Heading
function thegem_heading($params) {
	$text = $icon = $link_atts = $label = $class = $main_style = $data_attributes = $link_before = $link_after = $span_before = $span_after = '';

    $id = uniqid('thegem-heading-');
	$class .= 'thegem-heading';
	$uniq_interaction = uniqid('thegem-heading-interaction-');
	$post_id = thegem_cf_get_edit_template_post_id();

    if (!empty($params['element_id'])) {
        $id = esc_attr($params['element_id']);
    }

    $inline_css = '#'.$id.' {margin: 0;}';

	if (isset($params['heading_div_style']) && !empty($params['heading_div_style'])) {
		$class .= ' '.$params['heading_div_style'];
	}

	if (isset($params['heading_align']) && !empty($params['heading_align'])) {
		$main_style .= 'text-align: '.$params['heading_align'].';';
		if($params['heading_align'] == 'right') {
			$inline_css .= '#'.$id.' {margin-left: auto; margin-right: 0;}';
			$inline_css .= '#'.$id.' > * {justify-content: flex-end;}';
		} else if($params['heading_align'] == 'center') {
			$inline_css .= '#'.$id.' {margin-left: auto; margin-right: auto;}';
			$inline_css .= '#'.$id.' > * {justify-content: center;}';
		}
	}

	if (!empty($params['extra_class'])) {
		$class .= ' '.$params['extra_class'];
	}

	// Dynamic link options
	if (!empty($params['link']) && $params['link'] == 'post') {
		$link_atts .= 'href="'.get_permalink().'"';

		if (isset($params['link_target']) && !empty($params['link_target'])) {
			$link_atts .= ' target="_blank"';
		}

		$link_before = '<a '.$link_atts.'>';
		$link_after = '</a>';
	} elseif (!empty($params['link']) && $params['link'] == 'custom') {
		$link = !empty($params['heading_link']) ? vc_build_link($params['heading_link']) : null;

		if (isset($link['url']) && !empty($link['url'])) {
			$link_atts .= 'href="'.esc_attr($link['url']).'"';
		}

		if (isset($link['title']) && !empty($link['title'])) {
			$link_atts .= ' title="'.esc_attr($link['title']).'"';
		}

		if (isset($link['target']) && !empty($link['target'])) {
			$link_atts .= ' target="'.esc_attr(trim($link['target'])).'"';
		}

		if (isset($link['rel']) && !empty($link['rel'])) {
			$link_atts .= ' rel="'.esc_attr(trim($link['rel'])).'"';
		}

		if (!empty($link['url'])) {
			$link_before = '<a '.$link_atts.'>';
			$link_after = '</a>';
		}
	} elseif (!empty($params['link']) && $params['link'] == 'dynamic') {
		$dynamic_link = [
			'target' => !empty($params['link_target']) ? '_blank' : '_self',
			'rel' => !empty($params['link_nofollow']) ? 'nofollow' : '',
		];

		switch ($params['link_dynamic']) {
			case 'custom_fields':
				if (!empty($params['custom_fields_link_select'])) {
					$dynamic_link['url'] = get_post_meta($post_id, $params['custom_fields_link_select'], true);
				}

				break;
			case 'project_details':
				if (!empty($params['project_details_link_select'])) {
					$dynamic_link['url'] = get_post_meta($post_id, $params['project_details_link_select'], true);
				}

				break;
			default:
				if (!empty($params[$params['link_dynamic'].'_link_select'])) {
					$acf_link = get_post_meta($post_id, $params[$params['link_dynamic'].'_link_select'], true);

					if(is_array($acf_link)){
						$dynamic_link = [
							'url' => !empty($acf_link['url']) ? $acf_link['url'] : null,
							'target' => !empty($acf_link['target']) ? $acf_link['target'] : $dynamic_link['target'],
							'rel' => !empty($acf_link['rel']) ? $acf_link['rel'] : $dynamic_link['rel'],
						];
					} else{
						$dynamic_link['url'] = $acf_link;
					}
				}

				break;
		}

		if (isset($dynamic_link['url']) && !empty($dynamic_link['url'])) {
			$link_atts .= 'href="'.esc_attr($dynamic_link['url']).'"';
		}

		if (isset($dynamic_link['title']) && !empty($dynamic_link['title'])) {
			$link_atts .= ' title="'.esc_attr($dynamic_link['title']).'"';
		}

		if (isset($dynamic_link['target']) && !empty($dynamic_link['target'])) {
			$link_atts .= ' target="'.esc_attr(trim($dynamic_link['target'])).'"';
		}

		if (isset($dynamic_link['rel']) && !empty($dynamic_link['rel'])) {
			$link_atts .= ' rel="'.esc_attr(trim($dynamic_link['rel'])).'"';
		}

		if (!empty($dynamic_link['url'])) {
			$link_before = '<a '.$link_atts.'>';
			$link_after = '</a>';
		}
	}

	if (!empty($params['heading_inline'])) {
		$inline_css .= '#'.$uniq_interaction.', #'.$id.' {display: inline;}';
	}

	if (!empty($params['heading_custom_font_size'])) {
		if (isset($params['heading_font_size']) && !empty($params['heading_font_size'])) {
			$inline_css .= '#'.$id.' {font-size: '.esc_attr($params['heading_font_size']).'px;}';
		}

		if (isset($params['heading_line_height']) && !empty($params['heading_line_height'])) {
			$inline_css .= '#'.$id.' {line-height: '.esc_attr($params['heading_line_height']).'px;}';
		}

        if(isset($params['heading_letter_spacing']) && !empty($params['heading_letter_spacing']) || strcmp($params['heading_letter_spacing'], '0') === 0) {
			$inline_css .= '#'.$id.' {letter-spacing: '.esc_attr($params['heading_letter_spacing']).'px;}';
		}

		if (isset($params['heading_text_transform']) && !empty($params['heading_text_transform'])) {
			$inline_css .= '#'.$id.', #'.$id.' .light {text-transform: '.esc_attr($params['heading_text_transform']).';}';
		}
	}

	if (!empty($params['heading_responsive_font'])) {
		if (isset($params['heading_font_size_tablet']) && !empty($params['heading_font_size_tablet'])) {
			$inline_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {#'.$id.' {font-size: '.esc_attr($params['heading_font_size_tablet']).'px;}}';
		}

		if (isset($params['heading_line_height_tablet']) && !empty($params['heading_line_height_tablet'])) {
			$inline_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {#'.$id.' {line-height: '.esc_attr($params['heading_line_height_tablet']).'px;}}';
		}

        if(isset($params['heading_letter_spacing_tablet']) && !empty($params['heading_letter_spacing_tablet']) || strcmp($params['heading_letter_spacing_tablet'], '0') === 0 ) {
			$inline_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {#'.$id.' {letter-spacing: '.esc_attr($params['heading_letter_spacing_tablet']).'px;}}';
		}

		if (isset($params['heading_font_size_mobile']) && !empty($params['heading_font_size_mobile'])) {
			$inline_css .= '@media screen and (max-width: 767px) {#'.$id.' {font-size: '.esc_attr($params['heading_font_size_mobile']).'px;}}';
		}

		if (isset($params['heading_line_height_mobile']) && !empty($params['heading_line_height_mobile'])) {
			$inline_css .= '@media screen and (max-width: 767px) {#'.$id.' {line-height: '.esc_attr($params['heading_line_height_mobile']).'px;}}';
		}

        if(isset($params['heading_letter_spacing_mobile']) && !empty($params['heading_letter_spacing_mobile']) || strcmp($params['heading_letter_spacing_mobile'], '0') === 0) {
			$inline_css .= '@media screen and (max-width: 767px) {#'.$id.' {letter-spacing: '.esc_attr($params['heading_letter_spacing_mobile']).'px;}}';
		}
	}

    if (isset($params['heading_disable_desktop']) && !empty($params['heading_disable_desktop'])) {
        $inline_css .= '@media screen and (min-width: 1024px) {#'.$id.' {display: none;}}';
    }

    if (isset($params['heading_disable_tablet']) && !empty($params['heading_disable_tablet'])) {
        $inline_css .= '@media screen and (max-width: 1023px) and (min-width: 768px) {#'.$id.' {display: none;}}';
    }

    if (isset($params['heading_disable_mobile']) && !empty($params['heading_disable_mobile'])) {
        $inline_css .= '@media screen and (max-width: 767px) {#'.$id.' {display: none;}}';
    }

	$offset_name = array('padding', 'margin');
	$direction = array('top', 'bottom', 'left', 'right');
	foreach ($offset_name as $name) {
		foreach ($direction as $dir) {
			$unit = 'px';
			$result = $last_result = '';
			if(isset($params['tablet_'.$name.'_'.$dir]) && !empty($params['tablet_'.$name.'_'.$dir]) || strcmp($params['tablet_'.$name.'_'.$dir], '0') === 0) {
				$result = str_replace(' ', '', $params['tablet_'.$name.'_'.$dir]);
				$last_result = substr($result, -1);
				if($last_result == '%') {
					$result = str_replace('%', '', $result);
					$unit = $last_result;
				}
				$inline_css .= '@media screen and (max-width: 1023px) {#'.esc_attr($id).'.thegem-heading {'.$name.'-'.$dir.': '.$result.$unit.' !important;}}';
			}
			if(isset($params['mobile_'.$name.'_'.$dir]) && !empty($params['mobile_'.$name.'_'.$dir]) || strcmp($params['mobile_'.$name.'_'.$dir], '0') === 0) {
				$result = str_replace(' ', '', $params['mobile_'.$name.'_'.$dir]);
				$last_result = substr($result, -1);
				if($last_result == '%') {
					$result = str_replace('%', '', $result);
					$unit = $last_result;
				}
				$inline_css .= '@media screen and (max-width: 767px) {#'.esc_attr($id).'.thegem-heading {'.$name.'-'.$dir.': '.$result.$unit.' !important;}}';
			}
		}
	}

    if (isset($params['heading_width']) && !empty($params['heading_width']) || strcmp($params['heading_width'], '0') === 0) {
        $inline_css .= '#'.$id.' {max-width: '.esc_attr($params['heading_width']).'px;}';
    }

	if (!empty($params['heading_custom_fonts']) && !empty($params['heading_google_font'])) {
		$font = thegem_font_parse($params['heading_custom_fonts']);
		$inline_css .= '#'.$id.' .light, #'.$id.' {'.esc_attr($font).'}';
	}

	$interactions_before_html = $interactions_after_html = '';
	if(isset($params['interactions_enabled']) && !empty($params['interactions_enabled'])) {
		$interactions_before_html = '<div id="'.esc_attr($uniq_interaction).'" class="gem-interactions-enabled" '.interactions_data_attr($params).'>';
		$interactions_after_html = '</div>';
	}

    if (!empty($params['css'])) {
        $custom_css_class = vc_shortcode_custom_css_class($params['css']);

        if ($params['heading_animation'] == TheGemHeadingAnimation::ANIMATION_BACKGROUND_SLIDING) {
            $params['css'] = preg_replace_callback('/background-color:\s?#[A-Fa-f0-9]{6}\s?\!important?;/', function ($matches) use (&$params) {
                $params['css_background_color'] = $matches[0];
                return '';
            }, $params['css']);
        }

        $class .= ' '.$custom_css_class;
        $class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class, 'gem_heading', $params );
    }

    if ((!empty($params['css_animation']) && $params['css_animation']!='none')) {
        wp_enqueue_script( 'vc_waypoints' );
        wp_enqueue_style( 'vc_animate-css' );
        $class .= ' wpb_animate_when_almost_visible';
        $class .= ' ' . $params['css_animation'] . ' wpb_'.$params['css_animation'];

        $delay = '';
        if (isset($params['heading_animation_delay']) && !empty($params['heading_animation_delay'])) {
            $delay .= '-webkit-animation-delay: '.(int)$params['heading_animation_delay'].'ms;';
            $delay .= ' -moz-animation-delay: '.(int)$params['heading_animation_delay'].'ms;';
            $delay .= ' -o-animation-delay: '.(int)$params['heading_animation_delay'].'ms;';
            $delay .= ' animation-delay: '.(int)$params['heading_animation_delay'].'ms;';

            $inline_css .= '#'.$id.' {'.$delay.'}';
        }
    }

    if ($params['heading_animation_type'] == 'advanced' && $params['heading_animation']) {
		wp_enqueue_style('thegem-heading-animation');
		wp_enqueue_script('thegem-heading-main');
		wp_enqueue_script('thegem-heading-prepare-animation');
        TheGemHeadingAnimation::instance()->includeInlineJs();

        $class .= ' ' . $params['heading_animation'];
        $class .= ' thegem-heading-animate';

        if (isset($params['heading_animation_disable_desktop']) && $params['heading_animation_disable_desktop']=='1') {
            $class .= ' thegem-heading-animate-disable-desktop';
        }

        if (isset($params['heading_animation_disable_tablet']) && $params['heading_animation_disable_tablet']=='1') {
            $class .= ' thegem-heading-animate-disable-tablet';
        }

        if (isset($params['heading_animation_disable_mobile']) && $params['heading_animation_disable_mobile']=='1') {
            $class .= ' thegem-heading-animate-disable-mobile';
        }

        $animation_duration = !empty($params['heading_animation_duration']) ? (int)$params['heading_animation_duration'] : 0;
        $animation_delay = !empty($params['heading_animation_delay']) ? (int)$params['heading_animation_delay'] : 0;
        $animation_timing_function = !empty($params['heading_animation_timing_function']) ? $params['heading_animation_timing_function'] : null;
        $animation_interval = !empty($params['heading_animation_interval']) ? (int)$params['heading_animation_interval'] : TheGemHeadingAnimation::getDefaultInterval($params['heading_animation']);

        if (in_array($params['heading_animation'], [TheGemHeadingAnimation::ANIMATION_LINES_SLIDE_UP, TheGemHeadingAnimation::ANIMATION_LINES_SLIDE_UP_RANDOM])) {
            $data_attributes .= ' data-animation-name="'.$params['heading_animation'].'" ';

            if (isset($params['heading_animation_delay']) && !empty($params['heading_animation_delay'])) {
                $data_attributes .= ' data-animation-delay="'.$params['heading_animation_delay'].'" ';
            }

            $data_attributes .= ' data-animation-interval="'.$animation_interval.'" ';
        }

        if (in_array($params['heading_animation'], [TheGemHeadingAnimation::ANIMATION_WORDS_SLIDE_UP, TheGemHeadingAnimation::ANIMATION_WORDS_SLIDE_LEFT, TheGemHeadingAnimation::ANIMATION_WORDS_SLIDE_RIGHT, TheGemHeadingAnimation::ANIMATION_LINES_SLIDE_UP_RANDOM])) {
            if ($animation_duration > 0) {
                $inline_css .= '#'.$id.' .thegem-heading-word {animation-duration: '.$animation_duration.'ms;}';
            }

            if ($animation_timing_function) {
                $inline_css .= '#'.$id.' .thegem-heading-word {animation-timing-function: '.$animation_timing_function.';}';
            }
        }

        if (in_array($params['heading_animation'], [TheGemHeadingAnimation::ANIMATION_LINES_SLIDE_UP])) {
            if ($animation_duration > 0) {
                $inline_css .= '#'.$id.' .thegem-heading-line {animation-duration: '.$animation_duration.'ms;}';
            }

            if ($animation_timing_function) {
                $inline_css .= '#'.$id.' .thegem-heading-line {animation-timing-function: '.$animation_timing_function.';}';
            }
        }

        if (in_array($params['heading_animation'], [TheGemHeadingAnimation::ANIMATION_LETTERS_SLIDE_UP, TheGemHeadingAnimation::ANIMATION_TYPEWRITER, TheGemHeadingAnimation::ANIMATION_LETTERS_SCALE_OUT])) {
            if ($animation_duration > 0) {
                $inline_css .= '#'.$id.' .thegem-heading-letter {animation-duration: '.$animation_duration.'ms;}';
            }

            if ($animation_timing_function) {
                $inline_css .= '#'.$id.' .thegem-heading-letter {animation-timing-function: '.$animation_timing_function.';}';
            }
        }

        if ($params['heading_animation'] == TheGemHeadingAnimation::ANIMATION_BACKGROUND_SLIDING) {
            if (!empty($params['css_background_color'])) {
                $inline_css .= '#'.$id.' {background: none !important;}';
                $inline_css .= '#'.$id.':before {'.$params['css_background_color'].'}';
            }

            if ($animation_duration > 0) {
                $inline_css .= '#'.$id.':before {animation-duration: '.$animation_duration.'ms;}';
                $inline_css .= '#'.$id.'.thegem-heading-animated .thegem-heading-text-wrap {transition-duration: '.$animation_duration.'ms;}';
            }

            if ($animation_timing_function) {
                $inline_css .= '#'.$id.':before {animation-timing-function: '.$animation_timing_function.';}';
                $inline_css .= '#'.$id.'.thegem-heading-animated .thegem-heading-text-wrap {transition-timing-function: '.$animation_timing_function.'ms;}';
            }

            if ($animation_delay > 0) {
                $inline_css .= '#'.$id.':before {animation-delay: '.$animation_delay.'ms;}';
                $inline_css .= '#'.$id.'.thegem-heading-animated .thegem-heading-text-wrap {transition-delay: '.$animation_delay.'ms;}';
            }

            if (!empty($params['heading_inline'])) {
                $inline_css .= '#'.$id.' {display: inline-flex !important;}';
            }
        }

        if (in_array($params['heading_animation'], [TheGemHeadingAnimation::ANIMATION_BACKGROUND_SLIDING, TheGemHeadingAnimation::ANIMATION_FADE_TB, TheGemHeadingAnimation::ANIMATION_FADE_BT, TheGemHeadingAnimation::ANIMATION_FADE_LR, TheGemHeadingAnimation::ANIMATION_FADE_RL, TheGemHeadingAnimation::ANIMATION_FADE_SIMPLE])) {
            if ($animation_duration > 0) {
                $inline_css .= '#'.$id.' {animation-duration: '.$animation_duration.'ms;}';
            }

            if ($animation_timing_function) {
                $inline_css .= '#'.$id.' {animation-timing-function: '.$animation_timing_function.';}';
            }

            if ($animation_delay > 0) {
                $inline_css .= '#'.$id.' {animation-delay: '.$animation_delay.'ms;}';
            }
        }
    }

	if (isset($params['heading_text_color_hover']) && !empty($params['heading_text_color_hover'])) {
		$inline_css .= '#'.$id.':hover > span, #'.$id.':hover > a, #'.$id.':hover span.colored {color: '.$params['heading_text_color_hover'].' !important;}';
	} else {
		$inline_css .= '#'.$id.' a:hover, #'.$id.' a:hover span.colored {color: var(--thegem-to-menu-level1-color-hover, #00BCD4) !important;}';
	}

    $heading_animation_index = 0;

	if (!empty($main_style)) {
		$main_style = ' style="'.esc_attr($main_style).'"';
	}

	// Get Heading element data
	if ($params['text_source'] != 'custom_text') {
		$key = $cf_value = $cf_label = $label_output = $value_output = '';

		switch ($params['text_source']) {
			case 'page_title':
				$text = esc_html(thegem_title('', false));
				break;
			case 'page_excerpt':
				$text = esc_html(get_the_excerpt($post_id));
				break;
			case 'page_date':
				$text = esc_html(get_the_date('', $post_id));
				break;
			case 'page_time':
				$text = esc_html(get_the_time('', $post_id));
				break;
			case 'page_author':
				$text = esc_html(get_the_author_meta('display_name'));
				break;
			case 'page_comments':
				$text = esc_html(get_comments_number($post_id));
				break;
			case 'thegem_cf':
				if (!empty($params[$params['post_type'].'_select_field'])) {
					$key = $params[$params['post_type'].'_select_field'];
					$cf_label = array_flip(thegem_cf_get_fields_by_post_type($params['post_type']))[$key];
					$cf_value = get_post_meta($post_id, $key, true);
				}
				break;
			case 'project_details':
				if (!empty($params['project_details_select_field'])) {
					$key = $params['project_details_select_field'];
					$cf_label = array_flip(thegem_cf_get_project_details())[$key];
					$cf_value = get_post_meta($post_id, $key, true);
				}
				break;
			case 'manual_input':
				if (!empty($params['manual_input_select_field'])) {
					$key = $params['manual_input_select_field'];
					$cf_value = get_post_meta($post_id, $key, true);
				}
				break;
			default:
				if (!empty($params[$params['text_source'].'_select_field'])) {
					$plugin_type = thegem_cf_check_plugins_group($params['text_source']);
					$key = $params[$params['text_source'].'_select_field'];

					if ($plugin_type !== 'toolset') {
						$labels_array = array_flip(thegem_cf_get_acf_plugin_fields_by_group($params['text_source']));
						$cf_label = array_key_exists($key, $labels_array) ? $labels_array[$key] : '';
					} else {
						$labels_array = array_flip(thegem_cf_get_toolset_plugin_fields_by_group($params['text_source']));
						$cf_label = array_key_exists($key, $labels_array) ? $labels_array[$key] : '';
					}

					$cf_value = get_post_meta($post_id, $key, true);
				}

				break;
		}

		if (!empty($params['output_data']) && $params['output_data'] != 'value') {
			$label_output = esc_html($cf_label);
			if ($params['output_data'] == 'all') {
				$label_output .= ': ';
			}
		}

		if (!empty($params['output_data']) && $params['output_data'] != 'label') {
			if (!empty($params['field_type']) && $params['field_type'] == 'number') {
				$value_output = floatval($cf_value);
				$decimal = explode('.', $value_output);
				$decimal = isset($decimal[1]) ? strlen(($decimal[1])) : 0;
				$decimal = $decimal <= 3 ? $decimal : 3;

				if (!empty($params['field_format']) && $params['field_format'] == 'wp_locale') {
					$value_output = number_format_i18n($value_output, $decimal);
				}

				if (!empty($params['field_prefix'])) {
					$value_output = $params['field_prefix'] . '' . $value_output;
				}

				if (!empty($params['field_suffix'])) {
					$value_output = $value_output . '' . $params['field_suffix'];
				}
			} else {
				$value_output = $cf_value;
			}
		}

		$text = ($label_output || $value_output) ? $label_output . $value_output : $text;
		$text = TheGemHeadingAnimation::parse($text, $params, $heading_animation_index, '', '');

		if (isset($params['heading_page_text_weight']) && $params['heading_page_text_weight'] == 'light') {
			$text = '<span class="light">'.$text.'</span>';
		}

		if (isset($params['heading_page_text_style']) && !empty($params['heading_page_text_style'])) {
			$inline_css .= '#'.$id.', #'.$id.' .light {font-style: italic;}';
		}

		if (isset($params['heading_page_text_decoration']) && !empty($params['heading_page_text_decoration'])) {
			$inline_css .= '#'.$id.', #'.$id.' .light {text-decoration: underline;}';
		}

		if (isset($params['heading_page_text_color']) && !empty($params['heading_page_text_color'])) {
			$inline_css .= '#'.$id.', #'.$id.' .light {color: '.$params['heading_page_text_color'].';}';
		}
	} else {
		$text_content = vc_param_group_parse_atts($params['text_content']);
		$isRotatingEnabled = false;

		foreach ($text_content as $k=>$value) {
			if (isset($value['heading_text'])) {
				$value['heading_text'] = do_shortcode($value['heading_text']);
			}

		    if ($k>0) {
		        $text .= ' ';
            }

			$text_style = $inner_class = '';

			if (isset($value['heading_text_weight']) && $value['heading_text_weight'] == 'light') {
				$inner_class .= 'light';
			}

			if (isset($value['heading_text_style']) && !empty($value['heading_text_style'])) {
				$text_style .= ' font-style: italic;';
			}

			if (isset($value['heading_text_decoration']) && !empty($value['heading_text_decoration'])) {
				$text_style .= ' text-decoration: underline;';
			}

			if (isset($value['heading_text_color']) && !empty($value['heading_text_color'])) {
				$inner_class .= ' colored';
				$text_style .= ' color: '.$value['heading_text_color'].';';
			}

            if (!empty($value['rotating_text_enabled']) && !empty($value['rotating_text_items'])) {
                $isRotatingEnabled = true;
                $class .= ' thegem-heading-animate';
				wp_enqueue_style('thegem-heading-animation');
				wp_enqueue_script('thegem-heading-main');
				wp_enqueue_script('thegem-heading-rotating');
                TheGemHeadingAnimation::instance()->includeInlineJs();

                $rotating_text_items = vc_param_group_parse_atts($value['rotating_text_items']);
                array_unshift($rotating_text_items, ['text'=>$value['heading_text']]);

                $rotating_text = '';
                foreach ($rotating_text_items as $r_key=>$rotating_text_item) {
                    $rotating_text .= ' <span class="thegem-heading-rotating-text" '.($r_key!=0 ? 'style="opacity: 0; position: absolute;"' : '').'>'.nl2br(esc_html($rotating_text_item['text'])).'</span>';
                }

                $inner_class .= ' thegem-heading-rotating';
                $dataAttrs = '';

                if (!empty($value['rotating_animation_duration'])) {
                    $dataAttrs = 'data-duration="'.esc_attr($value['rotating_animation_duration']).'" ';
                }

                if (!empty($value['rotating_animation_name']) && $value['rotating_animation_name']!='default') {
                    $dataAttrs .= ' data-animation="'.esc_attr($value['rotating_animation_name']).'"';
                }

                $text .= ' <span class="'.esc_attr(trim($inner_class)).'"'.(!empty($text_style) ? ' style="'.esc_attr(trim($text_style)).'"' : '').' '.$dataAttrs.'>'.$rotating_text.'</span> ';
            } else {
                if ($params['heading_animation_type'] == 'advanced' && !$isRotatingEnabled) {
                    $text .= TheGemHeadingAnimation::parse(strip_tags($value['heading_text']), $params, $heading_animation_index, $inner_class, $text_style);
                } else {
                    $text .= '<span'.(!empty($inner_class) ? ' class="'.esc_attr(trim($inner_class)).'"' : '').(!empty($text_style) ? ' style="'.esc_attr(trim($text_style)).'"' : '').'>'.nl2br(esc_html($value['heading_text'])).'</span> ';
                }
            }
		}

        if ($params['heading_animation'] == TheGemHeadingAnimation::ANIMATION_BACKGROUND_SLIDING) {
            $text = '<span class="thegem-heading-text-wrap"><span class="thegem-heading-text">'.$text.'</span></span>';
        }
	}

	if ($params['heading_icon'] && isset($params['pack']) && $params['icon_' . str_replace("-", "_", $params['pack'])] != '') {
		wp_enqueue_style('icons-' . $params['pack']);
		$icon_style = '';
		if (!empty($params['heading_icon_color'])) {
			$icon_style = 'style="color:'.$params['heading_icon_color'].';"';
		}
		$icon = '<span class="icon" '.$icon_style.'>'.thegem_build_icon($params['pack'], $params['icon_' . str_replace("-", "_", $params['pack'])]).'</span>';
	}

	if (!empty($params['heading_label_text'])) {
		$label = '<span class="label title-h6">'.$params['heading_label_text'].'</span>';

		if (!empty($params['heading_label_color'])) {
			$inline_css .= '#'.$id . ' .label { color:' . $params['heading_label_color'] . '; }';
		}

		if (!empty($params['heading_label_background'])) {
			$inline_css .= '#'.$id . ' .label { background-color:' . $params['heading_label_background'] . '; }';
		}
	}

	if (!empty($icon) || !empty($label)) {
		$class .= ' with-label-icon';

		$span_before = '<span >';
		$span_after =  '</span>';

		if (empty($link_before)) {
			$link_before = '<span class="label-icon-wrap">';
			$link_after =  '</span>';
		}
	}

	$params['heading_tag'] = thegem_check_array_value(array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'), $params['heading_tag'], 'h1');
	$innerHtml = '<'.$params['heading_tag'].' id="'.$id.'" class="'.esc_attr($class).'"'.$main_style.' '.trim($data_attributes).'>'.$link_before.$icon.$span_before.trim($text).$span_after.$label.$link_after.'</'.$params['heading_tag'].'>';

    if ($params['heading_animation'] == TheGemHeadingAnimation::ANIMATION_BACKGROUND_SLIDING) {
        $heading_wrap_style = '';

        if (!empty($params['heading_align']) && in_array($params['heading_align'], ['right', 'center'])) {
            $heading_wrap_style .= 'display: block; text-align: '.$params['heading_align'];
        }

        $innerHtml = '<div class="thegem-heading-wrap"'.($heading_wrap_style ? 'style="'.$heading_wrap_style.'"' : '').'>'.$innerHtml.'</div>';
    }

	echo $interactions_before_html.$innerHtml.$interactions_after_html;
	echo '<style type="text/css">' .$inline_css. '</style>';
}

function thegem_yith_frontend_action_list($actions) {
	$actions[] = 'extended_products_grid_load_more';
	return $actions;
}
add_filter('yith_ywraq_frontend_action_list', 'thegem_yith_frontend_action_list');
add_filter('yith_woocompare_actions_to_check_frontend', 'thegem_yith_frontend_action_list');

if(!function_exists('thegem_get_svg_content')) {
function thegem_get_svg_content($file, $atts = array()) {
	$output = '';
	$filename = $file;
	if(get_attached_file($file)) {
		$filename = get_attached_file($file);
	}
	if(!empty($filename) && file_exists($filename)) {
		$content = file_get_contents($filename);
		$content = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $content);
		preg_match('/<\s*svg\s*(?<attr>[^>]*?)?>(?<content>.*?)?<\s*\/\s*svg\s*>/ims', $content, $svg);
		if(!empty($svg['content'])) {
			$svg_attrs = !empty($svg['attr']) ? wp_kses_hair($svg['attr'], wp_allowed_protocols()) : array();
			unset($svg_attrs['id']);
			$attrs = '';
			if(!empty($svg_attrs)) {
				foreach($svg_attrs as $a) {
					$val = $a['value'].(!empty($atts[$a['name']]) ? ' '.$atts[$a['name']] : '');
					unset($atts[$a['name']]);
					$attrs .= ' '.esc_attr($a['name']).'="'.esc_attr($val).'"';
				}
				foreach($atts as $k => $a) {
					$attrs .= ' '.esc_attr($k).'="'.esc_attr($a).'"';
				}
			}
			$output = '<svg'.$attrs.'>'.$svg['content'].'</svg>';
		}
	}
	return $output;
}
}

function thegem_vc_post_custom_layout_name($layout_name) {
	$layout_name = 'default';
	return $layout_name;
}
add_filter('vc_post_custom_layout_name', 'thegem_vc_post_custom_layout_name');

function thegem_vc_blank_template_path($template, $layout_name) {
	if($layout_name === 'blank') {
		$theme_template = locate_template('vc_templates/pages/layouts/blank.php');
		$template = empty($theme_template) ? $template : $theme_template;
	}
	return $template;
}
add_action('vc_post_custom_layout_template', 'thegem_vc_blank_template_path', 10, 2);