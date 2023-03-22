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
	<div class="gem-gallery gem-gallery-hover-<?php echo esc_attr($params['hover']); ?><?php echo ($params['no_thumbs'] ? ' no-thumbs' : ''); ?><?php echo ($params['pagination'] ? ' with-pagination' : ''); ?>"<?php echo (intval($params['autoscroll']) ? ' data-autoscroll="'.intval($params['autoscroll']).'"' : ''); ?>>
	<?php foreach($attachments_ids as $attachment_id) : ?>
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
							<?php if($item->post_excerpt) : ?><span class="gem-gallery-item-title "><?php echo apply_filters('the_excerpt', $item->post_excerpt); ?></span><?php endif; ?>
							<?php if($item->post_content) : ?><span class="gem-gallery-item-description"><?php echo apply_filters('the_content', $item->post_content); ?></span><?php endif; ?>
						</span>
					</a>
					<span class="gem-gallery-line"></span>
				</div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php
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

	$gap_size = isset($params['gaps_size']) ? round(intval($params['gaps_size']) / 2) : null;
	$gap_size_tablet = isset($params['gaps_size_tablet']) ? round(intval($params['gaps_size_tablet']) / 2) : null;
	$gap_size_mobile = isset($params['gaps_size_mobile']) ? round(intval($params['gaps_size_mobile']) / 2) : null;

	if (empty($params['columns_100']))
		$params['columns_100'] = 5;

	if ($params['sorting'] == 'false')
		$params['sorting'] = false;

	if ($params['sorting'] == 'true')
		$params['sorting'] = true;

	wp_enqueue_style('thegem-portfolio');
	wp_enqueue_style('thegem-hovers-' . $params['hover']);

	if ($params['layout_type'] == 'creative') {
		$params['layout'] = 'creative';
		$params['columns'] = $params['columns_desktop_creative'];
		$params['columns_tablet'] = $params['columns_tablet_creative'];
		$params['columns_mobile'] = $params['columns_mobile_creative'];
		$params['columns_100'] = $params['columns_100_creative'];
		$params['ignore_highlights'] = '1';
	}

	if ($params['with_filter']) {
		wp_enqueue_script('jquery-dlmenu');
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

	$grid_uid = $params['portfolio_uid'];

	if ($params['content_portfolios_cat'] && is_string($params['content_portfolios_cat'])) {
		$params['content_portfolios_cat'] = explode(',', $params['content_portfolios_cat']);
	} else {
		$params['content_portfolios_cat'] = array('0');
	}
	$terms = $params['content_portfolios_cat'];

	$categories_filter = null;
	if (isset($_GET[$grid_uid . '-category'])) {
		$active_cat = $_GET[$grid_uid . '-category'];
		$categories_current = [$active_cat];
		$categories_filter = $active_cat;
	} else {
		$active_cat = 'all';
		$categories_current = $terms;
	}

	$style_uid = substr(md5(rand()), 0, 7);

	$localize = array(
		'data' => $params,
		'action' => 'portfolio_grid_load_more',
		'url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('portfolio_ajax-nonce')
	);
	wp_localize_script('thegem-portfolio-grid-extended', 'thegem_portfolio_ajax_' . $style_uid, $localize);

	$inlineStyles = '';
	$gridSelector = '.portfolio.portfolio-grid[data-style-uid="' . $style_uid . '"]';
	$itemSelector = $gridSelector . ' .portfolio-item';
	$captionSelector = $itemSelector . ' .caption';

	if (isset($gap_size)) {
		$inlineStyles .= $itemSelector . ' { padding: ' . esc_attr($gap_size) . 'px; }';
		if ($params['columns'] == '100%') {
			$inlineStyles .= $gridSelector . ' .row { margin: 0; }';
			if (thegem_get_option('page_padding_left')) {
				$inlineStyles .= $gridSelector . ' .row { margin-left: -' . esc_attr($gap_size) . 'px; }';
			} else {
				$inlineStyles .= $gridSelector . ' .row { padding-left: ' . esc_attr($gap_size) . 'px; }';
			}
			if (thegem_get_option('page_padding_right')) {
				$inlineStyles .= $gridSelector . ' .row { margin-right: -' . esc_attr($gap_size) . 'px; }';
			} else {
				$inlineStyles .= $gridSelector . ' .row { padding-right: ' . esc_attr($gap_size) . 'px; }';
			}
		} else {
			$inlineStyles .= $gridSelector . ' .row { margin: -' . esc_attr($gap_size) . 'px; }';
		}
	}
	if (isset($params['gaps_size_tablet']) && $params['gaps_size_tablet'] !== '') {
		$inlineStyles .= '@media (max-width: 991px) { '. $itemSelector . ' { padding: ' . esc_attr($gap_size_tablet) . 'px; }}';
		if ($params['columns'] == '100%') {
			$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .row { margin: 0; }}';
			if (thegem_get_option('page_padding_left')) {
				$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .row { margin-left: -' . esc_attr($gap_size_tablet) . 'px; }}';
			} else {
				$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .row { padding-left: ' . esc_attr($gap_size_tablet) . 'px; }}';
			}
			if (thegem_get_option('page_padding_right')) {
				$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .row { margin-right: -' . esc_attr($gap_size_tablet) . 'px; }}';
			} else {
				$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .row { padding-right: ' . esc_attr($gap_size_tablet) . 'px; }}';
			}
		} else {
			$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .row { margin: -' . esc_attr($gap_size_tablet) . 'px; }}';
		}
	}
	if (isset($params['gaps_size_mobile']) && $params['gaps_size_mobile'] !== '') {
		$inlineStyles .= '@media (max-width: 767px) { '. $itemSelector . ' { padding: ' . esc_attr($gap_size_mobile) . 'px; }}';
		if ($params['columns'] == '100%') {
			$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .row { margin: 0; }}';
			if (thegem_get_option('page_padding_left')) {
				$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .row { margin-left: -' . esc_attr($gap_size_mobile) . 'px; }}';
			} else {
				$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .row { padding-left: ' . esc_attr($gap_size_mobile) . 'px; }}';
			}
			if (thegem_get_option('page_padding_right')) {
				$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .row { margin-right: -' . esc_attr($gap_size_mobile) . 'px; }}';
			} else {
				$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .row { padding-right: ' . esc_attr($gap_size_mobile) . 'px; }}';
			}
		} else {
			$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .row { margin: -' . esc_attr($gap_size_mobile) . 'px; }}';
		}
	}
	if (!empty($params['image_height'])) {
		$inlineStyles .= $itemSelector . ':not(.double-item) .image-inner { height: ' . esc_attr($params['image_height']) . 'px !important; padding-bottom: 0 !important; }';
		$inlineStyles .= $itemSelector . ':not(.double-item) .gem-simple-gallery .gem-gallery-item a { height: ' . esc_attr($params['image_height']) . 'px !important; }';
	}
	if (!empty($params['background_color'])) {
		$inlineStyles .= $captionSelector . ' { background-color: ' . esc_attr($params['background_color']) . ' !important; }';
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
	if (!empty($params['separator_color'])) {
		$inlineStyles .= $captionSelector . ' .caption-separator { background-color: ' . esc_attr($params['separator_color']) . ' !important; }';
	}
	if (!empty($params['truncate_titles'])) {
		$inlineStyles .= $captionSelector . ' .title span { white-space: initial; max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($params['truncate_titles']) . '; line-clamp: ' . esc_attr($params['truncate_titles']) . '; -webkit-box-orient: vertical; }';
	}
	if (!empty($params['truncate_info'])) {
		$inlineStyles .= $captionSelector . ' .info { white-space: initial; max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($params['truncate_info']) . '; line-clamp: ' . esc_attr($params['truncate_info']) . '; -webkit-box-orient: vertical; }';
	}
	if (!empty($params['truncate_description'])) {
		$inlineStyles .= $captionSelector . ' .subtitle { max-height: initial !important; }';
		$inlineStyles .= $captionSelector . ' .subtitle span { white-space: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($params['truncate_description']) . '; line-clamp: ' . esc_attr($params['truncate_description']) . '; -webkit-box-orient: vertical; }';
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

	$items_per_page = $params['items_per_page'] ? intval($params['items_per_page']) : 8;

	$page = 1;

	if (isset($_GET[$grid_uid . '-page'])) {
		$page = $_GET[$grid_uid . '-page'];
	}

	if ($params['sorting']) {
		$orderby = 'date';
	} else if (isset($params['orderby']) && $params['orderby'] != 'default') {
		$orderby = $params['orderby'];
	} else {
		$orderby = 'menu_order ID';
	}

	if ($params['sorting']) {
		$order = 'DESC';
	} else if (isset($params['order']) && $params['order'] != 'default') {
		$order = $params['order'];
	} else {
		$order = 'ASC';
	}

	if (!empty($params['exclude_portfolios'])) {
		$params['exclude_portfolios'] = explode(',', $params['exclude_portfolios']);
	}

	$portfolio_title = $params['title'] ? $params['title'] : '';
	global $post;
	$portfolio_posttemp = $post;

	$portfolio_loop = thegem_get_portfolio_posts($categories_current, $page, $items_per_page, $orderby, $order, $params['offset'], $params['exclude_portfolios']);

	if ($portfolio_loop->have_posts()) :

		$max_page = ceil(($portfolio_loop->found_posts - intval($params['offset'])) / $items_per_page);

		if ($max_page > $page)
			$next_page = $page + 1;
		else
			$next_page = 0;

		if (in_array('0', $terms)) {
			$terms = get_terms('thegem_portfolios');
		} else {
			foreach ($terms as $key => $term) {
				$terms[$key] = get_term_by('slug', $term, 'thegem_portfolios');
				if (!$terms[$key]) {
					unset($terms[$key]);
				}
			}
		}

		$terms = apply_filters('portfolio_terms_filter', $terms);

		usort($terms, 'portolios_cmp');

		$item_classes = get_thegem_portfolio_render_item_classes($params);
		$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params);

		if ($params['layout'] !== 'creative') {
			if ($params['columns'] == '100%' || (($params['ignore_highlights'] !== '1' || in_array($params['layout'], ['masonry', 'metro'])) && $params['skeleton_loader'] !== '1')) {
				echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="preloader-spin"></div></div>');
			} else if ($params['skeleton_loader'] == '1') { ?>
				<div class="preloader save-space">
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
		<?php if ($portfolio_title): ?>
		<h3 class="title portfolio-title"><?php echo $portfolio_title; ?></h3>
	<?php endif; ?>

		<?php
		$portfolio_classes = array(
			'portfolio portfolio-grid no-padding',
			'portfolio-pagination-' . $params['pagination'],
			'portfolio-style-' . $params['layout'],
			'background-style-' . $params['background_style'],
			'title-style-' . $params['title_style'],
			'hover-' . esc_attr($params['hover']),
			'title-on-' . $params['display_titles'],
			'hover-elements-size-' . $params['hover_elements_size'],
			($params['caption_position'] ? 'caption-position-' . $params['caption_position'] : ''),
			($params['loading_animation'] !== 'disabled' ? 'loading-animation item-animation-' . $params['loading_animation'] : ''),
			($params['loading_animation'] !== 'disabled' && $params['loading_animation_mobile'] == '1' ? 'enable-animation-mobile' : ''),
			($gap_size == 0 ? 'no-gaps' : ''),
			($params['columns'] == '100%' ? 'fullwidth-columns fullwidth-columns-' . intval($params['columns_100']) : ''),
			($params['display_titles'] == 'page' && $params['hover'] == 'gradient' ? 'hover-gradient-title' : ''),
			($params['display_titles'] == 'page' && $params['hover'] == 'circular' ? 'hover-circular-title' : ''),
			($params['display_titles'] == 'hover' || $params['hover'] == 'gradient' || $params['hover'] == 'circular' ? 'hover-title' : ''),
			($params['layout'] == 'masonry' && $params['columns'] != '1x' ? 'portfolio-items-masonry' : ''),
			($params['columns'] != '100%' ? 'columns-' . str_replace("x", "", $params['columns']) : ''),
			(isset($params['columns_tablet']) ? 'columns-tablet-' . str_replace("x", "", $params['columns_tablet']) : ''),
			(isset($params['columns_mobile']) ? 'columns-mobile-' . str_replace("x", "", $params['columns_mobile']) : ''),
			($params['layout'] == 'creative' || ($params['layout'] == 'justified' && $params['ignore_highlights'] == '1') ? 'disable-isotope' : ''),
			($params['next_page_preloading'] == '1' ? 'next-page-preloading' : ''),
			($params['filters_preloading'] == '1' ? 'filters-preloading' : ''),
			($params['layout'] == 'creative' && $params['scheme_apply_mobiles'] !== '1' ? 'creative-disable-mobile' : ''),
			($params['layout'] == 'creative' && $params['scheme_apply_tablets'] !== '1' ? 'creative-disable-tablet' : ''),
		);

		$portfolio_classes = apply_filters('portfolio_classes_filter', $portfolio_classes);
		?>

	<div class="<?php echo implode(' ', $portfolio_classes); ?>"
		 	data-style-uid="<?php echo esc_attr($style_uid); ?>"
			data-portfolio-uid="<?php echo esc_attr($grid_uid); ?>"
			data-current-page="<?php echo esc_attr($page); ?>"
			data-per-page="<?php echo $items_per_page; ?>"
			data-next-page="<?php echo esc_attr($next_page); ?>"
			data-pages-count="<?php echo esc_attr($max_page); ?>"
			data-hover="<?php echo $params['hover']; ?>"
			data-portfolio-filter="<?php echo esc_attr($categories_filter); ?>">
		<?php if (($params['with_filter'] && count($terms) > 0) || $params['sorting']): ?>
		<div class="portfilio-top-panel<?php if ($params['columns'] == '100%'): ?> fullwidth-block<?php endif; ?>"
			 <?php if ($gap_size && $params['columns'] == '100%'): ?>style="padding-left: <?php echo 2 * $gap_size; ?>px; padding-right: <?php echo 2 * $gap_size; ?>px;"<?php endif; ?>>
			<div class="portfilio-top-panel-row">
				<div class="portfilio-top-panel-left">
					<?php if ($params['with_filter'] && count($terms) > 0): ?>

						<div <?php if (!$params['sorting']): ?> style="text-align: center;"<?php endif; ?>
								class="portfolio-filters">
							<a href="#" data-filter="*"
							   class="<?php echo $active_cat == 'all' ? 'active' : ''; ?> all title-h6"><?php echo thegem_build_icon('thegem-icons', 'portfolio-show-all');
								?><span class="light"><?php echo $params['all_text']; ?></span></a>
							<?php foreach ($terms as $term) : ?>
								<a href="#" data-filter=".<?php echo $term->slug; ?>"
								   class="<?php echo $active_cat == $term->slug ? 'active' : ''; ?> title-h6"><?php if (get_option('portfoliosets_' . $term->term_id . '_icon_pack') && get_option('portfoliosets_' . $term->term_id . '_icon')) {
										echo thegem_build_icon(get_option('portfoliosets_' . $term->term_id . '_icon_pack'), get_option('portfoliosets_' . $term->term_id . '_icon'));
									} ?><span class="light"><?php echo $term->name; ?></span></a>
							<?php endforeach; ?>
						</div>
						<div class="portfolio-filters-resp">
							<button class="menu-toggle dl-trigger"><?php _e('Portfolio filters', 'thegem'); ?>
								<span class="menu-line-1"></span><span class="menu-line-2"></span><span
										class="menu-line-3"></span></button>
							<ul class="dl-menu">
								<li><a href="#" data-filter="*"><?php echo $params['all_text']; ?></a></li>
								<?php foreach ($terms as $term) : ?>
									<li><a href="#"
										   data-filter=".<?php echo esc_attr($term->slug); ?>"><?php echo $term->name; ?></a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
				<div class="portfilio-top-panel-right">
					<?php if ($params['sorting']): ?>
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
			</div>
		</div>
	<?php endif; ?>
	<div class="portfolio-row-outer <?php if ($params['columns'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
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
					$columns = $params['columns'] != '100%' ? str_replace("x", "", $params['columns']) : $params['columns_100'];
					$items_sizes = $creative_blog_schemes_list[$columns][$params['layout_scheme_' . $columns . 'x']];
					$items_count = $items_sizes['count'];
				}
				$i = 0;
				$eo_marker = false;
				while ($portfolio_loop->have_posts()) : $portfolio_loop->the_post();
					if ($params['columns'] == '1x') {
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
				endwhile; ?>

			</div><!-- .portflio-set -->
			<?php if ($params['layout'] !== 'creative' && $params['columns'] !== '1x'): ?>
				<div class="portfolio-item-size-container">
					<?php echo thegem_portfolio_render_item($params, $item_classes, $thegem_sizes); ?>
				</div>
			<?php endif; ?>
		</div><!-- .row-->

		<?php if ($params['pagination'] == 'normal'): ?>
			<div class="portfolio-navigator gem-pagination">
				<a href="#" class="prev">
					<i class="default"></i>
				</a>
				<div class="pages"></div>
				<a href="#" class="next">
					<i class="default"></i>
				</a>
			</div>
		<?php endif; ?>

		<?php if ($params['pagination'] == 'more' && $next_page > 0): ?>
			<div class="portfolio-load-more">
				<div class="inner">
					<?php thegem_button(array_merge($params['button'], array('tag' => 'button')), 1); ?>
				</div>
			</div>
		<?php endif; ?>
		<?php if ($params['pagination'] == 'scroll' && $next_page > 0): ?>
			<div class="portfolio-scroll-pagination"></div>
		<?php endif; ?>

	</div><!-- .full-width -->
	</div><!-- .portfolio-->
		</div><!-- .portfolio-preloader-wrapper-->

	<?php else: ?>
		<div data-page="<?php echo esc_attr($page); ?>" data-next-page="<?php echo esc_attr($next_page); ?>"></div>
	<?php endif; ?>

	<?php $post = $portfolio_posttemp;
	wp_reset_postdata();
}

function thegem_get_portfolio_posts($portfolios_cat, $page = 1, $ppp = -1, $orderby = 'menu_order ID', $order = 'ASC', $offset = false, $exclude = false) {
	if (empty($portfolios_cat)) {
		return null;
	}

	$args = array(
		'post_type' => 'thegem_pf_item',
		'post_status' => 'publish',
		'orderby' => $orderby,
		'order' => $order,
		'posts_per_page' => $ppp,
	);

	if (!in_array('0', $portfolios_cat, true)) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'thegem_portfolios',
				'field' => 'slug',
				'terms' => $portfolios_cat
			)
		);
	}

	if (!empty($offset)) {
		$args['offset'] = $ppp * ($page - 1) + $offset;
	} else {
		$args['paged'] = $page;
	}

	if (!empty($exclude)) {
		$args['post__not_in'] = $exclude;
	}

	$portfolio_loop = new WP_Query($args);

	return $portfolio_loop;
}

function thegem_portfolio_list_render_item($post_id, $params, $eo_marker = false) {
	$slugs = wp_get_object_terms($post_id, 'thegem_portfolios', array('fields' => 'slugs'));


	$terms = $params['content_portfolios_cat'];
	if (in_array('0', $terms)) {
		$terms = get_terms('thegem_portfolios');
	} else {
		foreach ($terms as $key => $term) {
			$terms[$key] = get_term_by('slug', $term, 'thegem_portfolios');
			if (!$terms[$key]) {
				unset($terms[$key]);
			}
		}
	}

	$terms = apply_filters('portfolio_terms_filter', $terms);
	usort($terms, 'portolios_cmp');

	$thegem_terms_set = array();
	foreach ($terms as $term) {
		$thegem_terms_set[$term->slug] = $term;
	}

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
	$page = isset($settings['more_page']) ? intval($settings['more_page']) : 1;
	if ($page == 0)
		$page = 1;

	$portfolio_loop = thegem_get_portfolio_posts($settings['content_portfolios_cat'], $page, $settings['items_per_page'], $settings['orderby'], $settings['order'], $settings['offset'], $settings['exclude_portfolios']);

	$max_page = ceil(($portfolio_loop->found_posts - intval($settings['offset'])) / $settings['items_per_page']);

	if ($max_page > $page)
		$next_page = $page + 1;
	else
		$next_page = 0;

	$item_classes = get_thegem_portfolio_render_item_classes($settings);
	$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($settings);
	?>
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
			$columns = $settings['columns'] != '100%' ? str_replace("x", "", $settings['columns']) : $settings['columns_100'];
			$items_sizes = $creative_blog_schemes_list[$columns][$settings['layout_scheme_' . $columns . 'x']];
			$items_count = $items_sizes['count'];
		}
		$i = 0;
		$eo_marker = false;
		while ($portfolio_loop->have_posts()) : $portfolio_loop->the_post();
			if ($settings['columns'] == '1x') {
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
		endwhile; ?>
	</div>
	<?php $response['html'] = trim(preg_replace('/\s\s+/', '', ob_get_clean()));
	$response = json_encode($response);
	header("Content-Type: application/json");
	echo $response;
	exit;
}
add_action('wp_ajax_portfolio_grid_load_more', 'portfolio_grid_more_callback');
add_action('wp_ajax_nopriv_portfolio_grid_load_more', 'portfolio_grid_more_callback');

function thegem_portfolio_block($params = array()) {
	echo '<div class="block-content clearfix">';
	thegem_portfolio_slider($params);
	echo '</div>';
}

// Print Portfolio Slider
function thegem_portfolio_slider($params) {
	$params = array_merge(
		array(
			'portfolio' => '',
			'title' => '',
			'layout' => '3x',
			'no_gaps' => false,
			'display_titles' => 'page',
			'hover' => '',
			'show_info' => false,
			'style' => 'justified',
			'is_slider' => true,
			'disable_socials' => false,
			'fullwidth_columns' => '5',
			'effects_enabled' => false,
			'background_style' => '',
			'title_style' => 'light',
			'animation' => 'dynamic',
			'autoscroll' => false,
			'gaps_size' => 42,
		),
		$params
	);

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

	$portfolio_loop = new WP_Query(array(
		'post_type' => 'thegem_pf_item',
		'tax_query' =>$params['portfolio'] ? array(
			array(
				'taxonomy' => 'thegem_portfolios',
				'field' => 'slug',
				'terms' => explode(',', $params['portfolio'])
			)
		) : array(),
		'post_status' => 'publish',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	));

	$terms = array();

	$portfolio_title = __('Portfolio', 'thegem');
	if($portfolio_set = get_term_by('slug', $params['portfolio'], 'thegem_portfolios')) {
		$portfolio_title = $portfolio_set->name;
	}
	$portfolio_title = $params['title'] ? $params['title'] : $portfolio_title;
	global $post;
	$portfolio_posttemp = $post;

	$classes = array('portfolio', 'portfolio-slider', 'clearfix', 'no-padding', 'col-lg-12', 'col-md-12', 'col-sm-12', 'hover-'.$params['hover']);
	if($layout_fullwidth)
		$classes[] = 'full';
	if( ($params['display_titles'] == 'hover' && $params['layout'] != '1x') || $params['hover'] == 'gradient' || $params['hover'] == 'circular' )
		$classes[] = 'hover-title';
	if ($params['display_titles'] == 'page' && $params['hover'] == 'gradient')
		$classes[] = 'hover-gradient-title';
	if ($params['display_titles'] == 'page' && $params['hover'] == 'circular')
		$classes[] = 'hover-circular-title';
	if($params['style'] == 'masonry')
		$classes[] = 'portfolio-items-masonry';
	if($layout_columns_count != -1)
		$classes[] = 'columns-'.$layout_columns_count;
	if($params['no_gaps'])
		$classes[] = 'without-padding';
	if($params['layout'] == '100%')
		$classes[] = 'fullwidth-columns-'.$params['fullwidth_columns'];

	if ($params['effects_enabled']) {
		$classes[] = 'lazy-loading';
		thegem_lazy_loading_enqueue();
	}

	if ($params['disable_socials'])
		$classes[] = 'disable-socials';
	if ($params['portfolio_arrow'])
		$classes[] = $params['portfolio_arrow'];
	if ($params['background_style'])
		$classes[] = 'background-style-'.$params['background_style'];
	if ($params['title_style'])
		$classes[] = 'title-style-'.$params['title_style'];

	$classes[] = 'title-on-' . $params['display_titles'];
	$classes[] = 'gem-slider-animation-' . $params['animation'];


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
				if($params['portfolio']) {
					$terms = explode(',', $params['portfolio']);
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
									<?php $slugs = wp_get_object_terms($post->ID, 'thegem_portfolios', array('fields' => 'slugs')); ?>
									<?php include(locate_template('gem-templates/portfolios/content-portfolio-carusel-item.php')); ?>
								<?php endwhile; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<?php $post = $portfolio_posttemp; wp_reset_postdata(); ?>
<?php
}

// Print Gallery Block
function thegem_gallery_block($params) {
	$params = array_merge(
		array(
			'ids' => array(),
			'gallery' => '',
			'type' => 'slider',
			'layout' => '3x',
			'fullwidth_columns' => '5',
			'style' => 'justified',
			'no_gaps' => false,
			'hover' => 'default',
			'item_style' => '',
			'title' => '',
			'gaps_size' => '',
			'effects_enabled' => '',
			'loading_animation' => 'move-up',
			'metro_max_row_height' => 380
		),
		$params
	);
	wp_enqueue_style('thegem-hovers-' . $params['hover']);
	wp_enqueue_style('thegem-gallery');

	if ($params['style'] !== 'justified' || $params['ignore_highlights'] !== '1') {
		if ($params['style']  == 'metro') {
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

	if (empty($params['fullwidth_columns']))
		$params['fullwidth_columns'] = 5;

	$params['loading_animation'] = thegem_check_array_value(array('disabled', 'bounce', 'move-up', 'fade-in', 'fall-perspective', 'scale', 'flip'), $params['loading_animation'], 'move-up');

	if ($params['effects_enabled']) {
		thegem_lazy_loading_enqueue();
	}

	$layout_columns_count = -1;
	if($params['layout'] == '2x')
		$layout_columns_count = 2;
	if($params['layout'] == '3x')
		$layout_columns_count = 3;
	if($params['layout'] == '4x')
		$layout_columns_count = 4;

	if(!empty($params['ids'])) {
		$thegem_gallery_images_ids = $params['ids'];
	} else {
		if(metadata_exists('post', $params['gallery'], 'thegem_gallery_images')) {
			$thegem_gallery_images_ids = get_post_meta($params['gallery'], 'thegem_gallery_images', true);
		} else {
			$attachments_ids = get_posts('post_parent=' . $params['gallery'] . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&post_status=publish');
			$thegem_gallery_images_ids = implode(',', $attachments_ids);
		}
		$attachments_ids = array_filter(explode(',', $thegem_gallery_images_ids));
	}
	$attachments_ids = array_filter(explode(',', $thegem_gallery_images_ids));
	$gallery_uid = uniqid();

	$gallery_grid_classes = array(
		'gem-gallery-grid col-lg-12 col-md-12 col-sm-12',
		'gallery-style-' . $params['style'],
		'hover-' . $params['hover'],
		($params['loading_animation'] !== 'disabled' ? 'loading-animation' : ''),
		($params['loading_animation'] !== 'disabled' ? 'item-animation-' . $params['loading_animation'] : ''),
		($params['layout'] == '100%' ? 'fullwidth-columns fullwidth-columns-' . intval($params['fullwidth_columns']) : ''),
		($params['style'] == 'masonry' ? 'gallery-items-masonry' : ''),
		($params['style'] == 'metro' ? 'metro' : ''),
		($params['no_gaps'] ? 'without-padding' : ''),
		($layout_columns_count != -1 ? 'columns-' . intval($layout_columns_count) : ''),
		($params['gaps_size'] && $params['style'] != 'metro' ? 'gaps-margin' : ''),
		($params['gaps_size'] && $params['style'] == 'metro' ? 'without-padding' : ''),
		($params['style'] == 'metro' && $params['item_style'] ? 'metro-item-style-'.$params['item_style'] : ''),
		($params['style'] == 'justified' && $params['ignore_highlights'] == '1' ? 'disable-isotope' : ''),
	);

?>


	<?php if ($params['layout'] == '100%' || $params['ignore_highlights'] !== '1' || $params['style'] !== 'justified') {
		echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="preloader-spin"></div></div>');
	} ?>
<div class="gallery-preloader-wrapper">
	<?php if($params['title']): ?>
		<h3 style="text-align: center;"><?php echo $params['title']; ?></h3>
	<?php endif; ?>
	<?php if(count($attachments_ids) > 0) : ?>
	<div class="row"
		<?php if ($params['gaps_size'] && ($params['style'])  == 'metro'):;?>style="margin-top: -<?php echo ($params['gaps_size'] / 2) ;?>px"<?php endif;?>
		<?php if ($params['gaps_size'] && ($params['style'])  != 'metro'):;?>style="margin-top: -<?php echo ($params['gaps_size'] / 2) ;?>px"<?php endif;?>>


		<div class="<?php echo implode(' ', $gallery_grid_classes); ?>" data-hover="<?php echo $params['hover']; ?>">

			<?php if ($params['type'] == 'grid' && $params['layout'] == '100%'):?>
				<div class="fullwidth-block" <?php if ($params['gaps_size']):;?> style="padding-left: <?php echo ($params['gaps_size'] / 2);?>px; padding-right: <?php echo ($params['gaps_size'] / 2);?>px;"<?php endif;?>>
			<?php endif;?>

				<ul
					<?php if ($params['type'] != 'grid' || $params['layout'] != '100%'):?>
						style="margin-left: -<?php echo ($params['gaps_size'] / 2);?>px; margin-right: -<?php echo ($params['gaps_size'] / 2);?>px;"
					<?php endif;?>

					class="gallery-set clearfix" data-max-row-height="<?php echo floatval($params['metro_max_row_height']); ?>">
					<?php foreach($attachments_ids as $attachment_id) : ?>
						<?php include(locate_template('content-gallery-item.php')); ?>
					<?php endforeach; ?>
				</ul>

			<?php if ($params['type'] == 'grid' && $params['layout'] == '100%'):?>
				</div>
			<?php endif; ?>
		</div>
	</div>



	<?php endif; ?>
</div>
<?php
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

	$params['items_per_page'] = intval($params['items_per_page']) ? intval($params['items_per_page']) : 8;
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

// Print Blog & News Extended Grid
function thegem_news_grid($params) {

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

	$gap_size = isset($params['gaps_size']) ? round(intval($params['gaps_size']) / 2) : null;
	$gap_size_tablet = isset($params['gaps_size_tablet']) ? round(intval($params['gaps_size_tablet']) / 2) : null;
	$gap_size_mobile = isset($params['gaps_size_mobile']) ? round(intval($params['gaps_size_mobile']) / 2) : null;

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
		$params['columns'] = $params['columns_desktop_list'];
		$params['columns_tablet'] = $params['columns_tablet_list'];
		$params['display_titles'] = 'page';
		$params['ignore_highlights'] = '1';
	}

	if ($params['layout'] == 'creative') {
		$params['columns'] = $params['columns_desktop_creative'];
		$params['columns_tablet'] = $params['columns_tablet_creative'];
		$params['columns_mobile'] = $params['columns_mobile_creative'];
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

	wp_enqueue_style('mediaelement');
	wp_enqueue_style('wp-mediaelement');

	wp_enqueue_script('thegem-mediaelement');
	wp_enqueue_script('thegem-gallery');

	if ($params['with_filter']) {
		wp_enqueue_script('jquery-dlmenu');
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

	if ($params['source_type'] == 'archive') {
		$params['select_blog_categories'] = '';
		$params['select_blog_tags'] = '';
		$params['select_blog_posts'] = '';
		$params['select_blog_authors'] = '';
		if ( is_category() ) {
			$params['select_blog_categories'] = '1';
			$params['categories'] = get_queried_object()->slug;
		} else if ( is_tag() ) {
			$params['select_blog_tags'] = '1';
			$params['tags'] = get_queried_object()->slug;
		} else if ( is_author() ) {
			$params['select_blog_authors'] = '1';
			$params['authors'] = get_queried_object()->ID;
		} else {
			$params['select_blog_categories'] = '1';
			$params['categories'] = '';
		}
	} else if ($params['source_type'] == 'related') {
		$params['select_blog_categories'] = '';
		$params['select_blog_tags'] = '';
		$params['select_blog_posts'] = '';
		$params['select_blog_authors'] = '';
		if (!empty($params['related_by_categories'])) {
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				$params['select_blog_categories'] = '1';
				$params['blog_categories'] = '';
				foreach( $categories as $category ) {
					$params['blog_categories'] .= empty($params['blog_categories']) ? $category->slug : ',' . $category->slug;
				}
			}
		}
		if (!empty($params['related_by_tags'])) {
			$tags = get_the_terms( get_the_ID(), 'post_tag' );
			if ( ! empty( $tags ) ) {
				$params['select_blog_tags'] = '1';
				$params['blog_tags'] = '';
				foreach( $tags as $tag ) {
					$params['blog_tags'] .= empty($params['blog_tags']) ? $tag->slug : ',' . $tag->slug;
				}
			}
		}
		if (!empty($params['related_by_author'])) {
			$params['select_blog_authors'] = '1';
			$params['blog_authors'] = get_the_author_meta( 'ID' );
		}
		if (!empty($params['blog_exclude_posts'])) {
			$params['blog_exclude_posts'] .= ',' . get_the_ID();
		} else {
			$params['blog_exclude_posts'] = get_the_ID();
		}
	}

	$is_blog_archive = $params['source_type'] == 'archive' && (thegem_get_template_type( get_the_ID() ) === 'blog-archive' || is_category() || is_tag() || is_author() || is_post_type_archive( 'post' ));

	$grid_uid = $is_blog_archive ? '' : $widget_uid;
	$grid_uid_url = $is_blog_archive ? '' : $widget_uid.'-';

	$params['portfolio_uid'] = $widget_uid;

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
	if (!empty($params['exclude_blog_posts'])) {
		$params['exclude_blog_posts'] = explode(',', $params['exclude_blog_posts']);
	}

	$terms = ['0'];
	$blog_categories = $blog_tags = $blog_posts = $blog_authors = [];
	if ($params['select_blog_categories'] && !empty($params['categories'])) {
		$blog_categories = $params['categories'];
		$terms = $params['categories'];
	}
	if ($params['select_blog_tags'] && !empty($params['tags'])) {
		$blog_tags = $params['tags'];
	}
	if ($params['select_blog_posts'] && !empty($params['posts'])) {
		$blog_posts = $params['posts'];
	}
	if ($params['select_blog_authors'] && !empty($params['authors'])) {
		$blog_authors = $params['authors'];
	}

	$style_uid = substr(md5(rand()), 0, 7);

	$localize = array(
		'data' => $params,
		'action' => 'blog_grid_extended_load_more',
		'url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('portfolio_ajax-nonce')
	);
	wp_localize_script('thegem-portfolio-grid-extended', 'thegem_portfolio_ajax_' . $style_uid, $localize);

	$inlineStyles = '';
	$gridSelector = '.portfolio.news-grid[data-style-uid="' . $style_uid . '"]';
	$itemSelector = $gridSelector . ' .portfolio-item';
	$captionSelector = $itemSelector . ' .caption';
	$onPageCaptionSelector = $itemSelector . ' .wrap > .caption';
	$onHoverCaptionSelector = $itemSelector . ' .image .overlay .caption';

	if (isset($gap_size)) {
		$inlineStyles .= $itemSelector . ' { padding: ' . esc_attr($gap_size) . 'px; }';
		$inlineStyles .= $gridSelector.'.list-style.with-divider .portfolio-item .wrap:before { top: -' . esc_attr($gap_size) . 'px; }';
		if ($params['columns'] == '100%') {
			$inlineStyles .= $gridSelector . ' .row { margin: 0; }';
			if (thegem_get_option('page_padding_left')) {
				$inlineStyles .= $gridSelector . ' .row { margin-left: -' . esc_attr($gap_size) . 'px; }';
			} else {
				$inlineStyles .= $gridSelector . ' .row { padding-left: ' . esc_attr($gap_size) . 'px; }';
			}
			if (thegem_get_option('page_padding_right')) {
				$inlineStyles .= $gridSelector . ' .row { margin-right: -' . esc_attr($gap_size) . 'px; }';
			} else {
				$inlineStyles .= $gridSelector . ' .row { padding-right: ' . esc_attr($gap_size) . 'px; }';
			}
		} else {
			$inlineStyles .= $gridSelector . ' .row { margin: -' . esc_attr($gap_size) . 'px; }';
		}
	}
	if (isset($gap_size_tablet)) {
		$inlineStyles .= '@media (max-width: 991px) { '. $itemSelector . ' { padding: ' . esc_attr($gap_size_tablet) . 'px; }}';
		$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector .'.list-style.with-divider .portfolio-item .wrap:before { top: -' . esc_attr($gap_size_tablet) . 'px; }}';
		if ($params['columns'] == '100%') {
			$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .row { margin: 0; }}';
			if (thegem_get_option('page_padding_left')) {
				$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .row { margin-left: -' . esc_attr($gap_size_tablet) . 'px; }}';
			} else {
				$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .row { padding-left: ' . esc_attr($gap_size_tablet) . 'px; }}';
			}
			if (thegem_get_option('page_padding_right')) {
				$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .row { margin-right: -' . esc_attr($gap_size_tablet) . 'px; }}';
			} else {
				$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .row { padding-right: ' . esc_attr($gap_size_tablet) . 'px; }}';
			}
		} else {
			$inlineStyles .= '@media (max-width: 991px) { '. $gridSelector . ' .row { margin: -' . esc_attr($gap_size_tablet) . 'px; }}';
		}
	}
	if (isset($gap_size_mobile)) {
		$inlineStyles .= '@media (max-width: 767px) { '. $itemSelector . ' { padding: ' . esc_attr($gap_size_mobile) . 'px; }}';
		$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector .'.list-style.with-divider .portfolio-item .wrap:before { top: -' . esc_attr($gap_size_mobile) . 'px; }}';
		if ($params['columns'] == '100%') {
			$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .row { margin: 0; }}';
			if (thegem_get_option('page_padding_left')) {
				$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .row { margin-left: -' . esc_attr($gap_size_mobile) . 'px; }}';
			} else {
				$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .row { padding-left: ' . esc_attr($gap_size_mobile) . 'px; }}';
			}
			if (thegem_get_option('page_padding_right')) {
				$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .row { margin-right: -' . esc_attr($gap_size_mobile) . 'px; }}';
			} else {
				$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .row { padding-right: ' . esc_attr($gap_size_mobile) . 'px; }}';
			}
		} else {
			$inlineStyles .= '@media (max-width: 767px) { '. $gridSelector . ' .row { margin: -' . esc_attr($gap_size_mobile) . 'px; }}';
		}
	}
	if (!empty($params['image_height'])) {
		$inlineStyles .= $itemSelector . ':not(.double-item) .image-inner { height: ' . esc_attr($params['image_height']) . 'px !important; padding-bottom: 0 !important; }';
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
		$inlineStyles .= $captionSelector . ' .title > div { text-transform: ' . esc_attr($params['title_transform']) . ' !important; }';
	}
	if (isset($params['title_letter_spacing'])) {
		$inlineStyles .= $captionSelector . ' .title > div { letter-spacing: ' . esc_attr($params['title_letter_spacing']) . 'px !important; }';
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
	if (!empty($params['truncate_description'])) {
		$inlineStyles .= $itemSelector . ' .caption .description { max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($params['truncate_description']) . '; line-clamp: ' . esc_attr($params['truncate_description']) . '; -webkit-box-orient: vertical; }';
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
		if ($params['blog_show_readmore_button'] == '1') {
			if (!empty($params['readmore_button_corner'])) {
				$inlineStyles .= $itemSelector . ' .read-more-button .gem-button { border-radius: ' . esc_attr($params['readmore_button_corner']) . 'px; }';
			}
			if (!empty($params['readmore_button_border'])) {
				$inlineStyles .= $itemSelector . ' .read-more-button .gem-button { border-width: ' . esc_attr($params['readmore_button_border']) . 'px; }';
			}
			if (!empty($params['readmore_button_text_color'])) {
				$inlineStyles .= $itemSelector . ' .read-more-button .gem-button { color: ' . esc_attr($params['readmore_button_text_color']) . '; }';
			}
			if (!empty($params['readmore_button_hover_text_color'])) {
				$inlineStyles .= $itemSelector . ' .read-more-button .gem-button:hover { color: ' . esc_attr($params['readmore_button_hover_text_color']) . '; }';
			}
			if (!empty($params['readmore_button_background_color'])) {
				$inlineStyles .= $itemSelector . ' .read-more-button .gem-button { background-color: ' . esc_attr($params['readmore_button_background_color']) . '; }';
			}
			if (!empty($params['readmore_button_hover_background_color'])) {
				$inlineStyles .= $itemSelector . ' .read-more-button .gem-button:hover { background-color: ' . esc_attr($params['readmore_button_hover_background_color']) . '; }';
			}
			if (!empty($params['readmore_button_border_color'])) {
				$inlineStyles .= $itemSelector . ' .read-more-button .gem-button { border-color: ' . esc_attr($params['readmore_button_border_color']) . '; }';
			}
			if (!empty($params['readmore_button_hover_border_color'])) {
				$inlineStyles .= $itemSelector . ' .read-more-button .gem-button:hover { border-color: ' . esc_attr($params['readmore_button_hover_border_color']) . '; }';
			}
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

	$categories_filter = null;
	if (isset($_GET[$grid_uid_url . 'category'])) {
		$active_cat = $_GET[$grid_uid_url . 'category'];
		$blog_categories = [$active_cat];
		$categories_filter = $active_cat;
	} else {
		$active_cat = 'all';
	}

	$items_per_page = $params['items_per_page'] ? intval($params['items_per_page']) : 8;

	$page = 1;
	$next_page = 0;

	if (isset($_GET[$grid_uid_url . 'page'])) {
		$page = $_GET[$grid_uid_url . 'page'];
	}

	if ($params['sorting']) {
		$orderby = 'date';
	} else if (isset($params['orderby']) && $params['orderby'] != 'default') {
		$orderby = $params['orderby'];
	} else {
		$orderby = 'menu_order date';
	}

	if (isset($params['order']) && $params['order'] != 'default') {
		$order = $params['order'];
	} else {
		$order = 'DESC';
	}

	$news_grid_title = $params['title'] ? $params['title'] : '';
	global $post;
	$news_grid_posttemp = $post;

	$news_grid_loop = get_thegem_extended_blog_posts($blog_categories, $blog_tags, $blog_posts, $blog_authors, $page, $items_per_page, $orderby, $order, $params['offset'], $params['exclude_blog_posts'], $params['ignore_sticky_posts']);

	if ($news_grid_loop->have_posts()) :

		$max_page = ceil(($news_grid_loop->found_posts - intval($params['offset'])) / $items_per_page);

		if ($max_page > $page)
			$next_page = $page + 1;
		else
			$next_page = 0;

		if (in_array('0', $terms)) {
			$terms = get_terms('category');
		} else {
			foreach ($terms as $key => $term) {
				$terms[$key] = get_term_by('slug', $term, 'category');
				if (!$terms[$key]) {
					unset($terms[$key]);
				}
			}
		}

		$terms = apply_filters('news_grid_terms_filter', $terms);

		$item_classes = get_thegem_extended_blog_render_item_classes($params);
		$thegem_sizes = get_thegem_extended_blog_render_item_image_sizes($params);

		if ($params['columns'] == '100%' || (($params['ignore_highlights'] !== '1' || in_array($params['layout'], ['masonry', 'metro'])) && $params['skeleton_loader'] !== '1')) {
			echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="preloader-spin"></div></div>');
		} else if ($params['skeleton_loader'] == '1') { ?>
			<div class="preloader save-space">
				<div class="skeleton">
					<div class="skeleton-posts portfolio-row">
						<?php for ($x = 0; $x < $news_grid_loop->post_count; $x++) {
							echo thegem_extended_blog_render_item($params, $item_classes);
						} ?>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="portfolio-preloader-wrapper">
			<?php if ($news_grid_title): ?>
				<h3 class="title portfolio-title"><?php echo $news_grid_title; ?></h3>
			<?php endif; ?>

			<?php

			$news_grid_classes = array(
				'portfolio portfolio-grid news-grid no-padding',
				'portfolio-pagination-' . $params['pagination'],
				'portfolio-style-' . $params['layout'],
				'background-style-' . $params['background_style'],
				'title-style-' . $params['title_style'],
				'hover-' . $hover_effect,
				'title-on-' . $params['display_titles'],
				'version-' . $version,
				($params['version'] == 'list' ? 'list-style caption-position-'.$params['caption_position_list'] : ''),
				($params['loading_animation'] !== 'disabled' ? 'loading-animation item-animation-' . $params['loading_animation'] : ''),
				($params['loading_animation'] !== 'disabled' && $params['loading_animation_mobile'] == '1' ? 'enable-animation-mobile' : ''),
				($gap_size == 0 ? 'no-gaps' : ''),
				(($params['layout'] == 'creative' && $params['columns'] == '100%') ? 'fullwidth-columns fullwidth-columns-' . $params['columns_100_creative'] : ''),
				(($params['layout'] != 'creative' && $params['columns'] == '100%') ? 'fullwidth-columns fullwidth-columns-' . $params['columns_100'] : ''),
				($params['display_titles'] == 'hover' ? 'hover-title' : ''),
				($params['layout'] == 'masonry' && $params['columns'] != '1x' ? 'portfolio-items-masonry' : ''),
				($params['columns'] != '100%' ? 'columns-' . str_replace("x", "", $params['columns']) : ''),
				(isset($params['columns_tablet']) ? 'columns-tablet-' . str_replace("x", "", $params['columns_tablet']) : ''),
				(isset($params['columns_mobile']) ? 'columns-mobile-' . str_replace("x", "", $params['columns_mobile']) : ''),
				($params['version'] == 'list' || $params['layout'] == 'creative' || ($params['layout'] == 'justified' && $params['ignore_highlights'] == '1') ? 'disable-isotope' : ''),
				($params['next_page_preloading'] == '1' ? 'next-page-preloading' : ''),
				($params['filters_preloading'] == '1' ? 'filters-preloading' : ''),
				($params['layout'] == 'creative' && $params['scheme_apply_mobiles'] !== '1' ? 'creative-disable-mobile' : ''),
				($params['layout'] == 'creative' && $params['scheme_apply_tablets'] !== '1' ? 'creative-disable-tablet' : ''),
				($params['version'] == 'list' && $params['disable_hover'] == '1' ? 'disabled-hover' : ''),
				($params['version'] == 'list' && $params['blog_show_divider'] == '1' ? 'with-divider' : ''),
				($params['disable_bottom_margin'] == '1' ? 'disable-bottom-margin' : ''),
			);

			$news_grid_classes = apply_filters('news_grid_classes_filter', $news_grid_classes);
			$fw_uniqid = uniqid('fullwidth-block-');

			?>

			<div class="<?php echo implode(' ', $news_grid_classes); ?>"
					data-style-uid="<?php echo esc_attr($style_uid); ?>"
					data-portfolio-uid="<?php echo esc_attr($grid_uid); ?>"
					data-current-page="<?php echo esc_attr($page); ?>"
					data-per-page="<?php echo $items_per_page; ?>"
					data-next-page="<?php echo esc_attr($next_page); ?>"
					data-pages-count="<?php echo esc_attr($max_page); ?>"
					data-hover="<?php echo $params['hover']; ?>"
					data-portfolio-filter="<?php echo esc_attr($categories_filter); ?>">
				<?php if (($params['with_filter'] && count($terms) > 0) || $params['sorting']): ?>
					<div class="portfilio-top-panel<?php if ($params['columns'] == '100%'): ?> fullwidth-block <?= $fw_uniqid; ?><?php endif; ?>"
						 <?php if ($gap_size && $params['layout'] == '100%'): ?>style="padding-left: <?php echo 2 * $gap_size; ?>px; padding-right: <?php echo 2 * $gap_size; ?>px;"<?php endif; ?>>
						<div class="portfilio-top-panel-row">
							<div class="portfilio-top-panel-left">
								<?php if ($params['with_filter'] && count($terms) > 0): ?>

									<div <?php if (!$params['sorting']): ?> style="text-align: center;"<?php endif; ?>
											class="portfolio-filters">
										<a href="#" data-filter="*"
										   class="<?php echo $active_cat == 'all' ? 'active' : ''; ?> all title-h6"><?php echo thegem_build_icon('thegem-icons', 'portfolio-show-all');
											?><span class="light"><?php echo $params['all_text']; ?></span></a>
										<?php foreach ($terms as $term) : ?>
											<a href="#" data-filter=".<?php echo $term->slug; ?>"
											   class="<?php echo $active_cat == $term->slug ? 'active' : ''; ?> title-h6"><?php if (get_option('portfoliosets_' . $term->term_id . '_icon_pack') && get_option('portfoliosets_' . $term->term_id . '_icon')) {
													echo thegem_build_icon(get_option('portfoliosets_' . $term->term_id . '_icon_pack'), get_option('portfoliosets_' . $term->term_id . '_icon'));
												} ?><span class="light"><?php echo $term->name; ?></span></a>
										<?php endforeach; ?>
									</div>
									<div class="portfolio-filters-resp">
										<button class="menu-toggle dl-trigger"><?php _e('News filters', 'thegem'); ?>
											<span class="menu-line-1"></span><span class="menu-line-2"></span><span
													class="menu-line-3"></span></button>
										<ul class="dl-menu">
											<li><a href="#" data-filter="*"><?php echo $params['all_text']; ?></a></li>
											<?php foreach ($terms as $term) : ?>
												<li><a href="#"
													   data-filter=".<?php echo esc_attr($term->slug); ?>"><?php echo $term->name; ?></a>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif; ?>
							</div>
							<div class="portfilio-top-panel-right">
								<?php if ($params['sorting']): ?>
									<div class="portfolio-sorting title-h6">
										<div class="orderby light">
											<label for="" data-value="date"><?php _e('Date', 'thegem') ?></label>
											<a href="javascript:void(0);" class="sorting-switcher"
											   data-current="date"></a>
											<label for="" data-value="name"><?php _e('Name', 'thegem') ?></label>
										</div>
										<div class="portfolio-sorting-sep"></div>
										<div class="order light">
											<label for="" data-value="DESC"><?php _e('Desc', 'thegem') ?></label>
											<a href="javascript:void(0);" class="sorting-switcher"
											   data-current="DESC"></a>
											<label for="" data-value="ASC"><?php _e('Asc', 'thegem') ?></label>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php if ($params['columns'] == '100%') {
						echo '<script type="text/javascript">if (typeof(gem_fix_fullwidth_position) == "function") { gem_fix_fullwidth_position(document.querySelector(".portfilio-top-panel.' . $fw_uniqid . '")); }</script><div></div>';
					} ?>
				<?php endif; ?>
				<div class="portfolio-row-outer <?php if ($params['columns'] == '100%'): ?>fullwidth-block <?= $fw_uniqid; ?> no-paddings<?php endif; ?>">
					<div class="row">
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
								$columns = $params['columns'] != '100%' ? str_replace("x", "", $params['columns']) : $params['columns_100_creative'];
								$items_sizes = $creative_blog_schemes_list[$columns][$params['layout_scheme_' . $columns . 'x']];
								$items_count = $items_sizes['count'];
							}
							$i = 0;
							while ($news_grid_loop->have_posts()) : $news_grid_loop->the_post();

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
							endwhile; ?>
						</div><!-- .portflio-set -->
						<?php if ($params['columns'] != '1x' && $params['layout'] != 'creative' && $params['version'] != 'list'): ?>
							<div class="portfolio-item-size-container">
								<?php echo thegem_extended_blog_render_item($params, $item_classes, $thegem_sizes); ?>
							</div>
						<?php endif; ?>
					</div><!-- .row-->

					<?php if ($params['pagination'] == 'normal' && $next_page > 0): ?>
						<div class="portfolio-navigator gem-pagination">
							<a href="#" class="prev">
								<i class="default"></i>
							</a>
							<div class="pages"></div>
							<a href="#" class="next">
								<i class="default"></i>
							</a>
						</div>
					<?php endif; ?>
					<?php if ($params['pagination'] == 'more' && $next_page > 0): ?>
						<div class="portfolio-load-more">
							<div class="inner">
								<?php thegem_button(array_merge($params['button'], array('tag' => 'button')), 1); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if ($params['pagination'] == 'scroll' && $next_page > 0): ?>
						<div class="portfolio-scroll-pagination"></div>
					<?php endif; ?>
				</div><!-- .full-width -->
				<?php if ($params['columns'] == '100%') {
					echo '<script type="text/javascript">if (typeof(gem_fix_fullwidth_position) == "function") { gem_fix_fullwidth_position(document.querySelector(".portfolio-row-outer.' . $fw_uniqid . '")); }</script>';
				} ?>
			</div><!-- .portfolio-->
		</div><!-- .portfolio-preloader-wrapper-->

	<?php else: ?>
		<div data-page="<?php echo esc_attr($page); ?>" data-next-page="<?php echo esc_attr($next_page); ?>"></div>
	<?php endif; ?>

	<?php $post = $news_grid_posttemp;
	wp_reset_postdata();
}


if (!function_exists('get_thegem_extended_blog_posts')) {
	function get_thegem_extended_blog_posts($blog_categories, $blog_tags, $blog_posts, $blog_authors, $page = 1, $ppp = -1, $orderby = 'menu_order date', $order = 'DESC', $offset = false, $exclude = false, $ignore_sticky_posts = false) {

		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'orderby' => $orderby,
			'order' => $order,
			'posts_per_page' => $ppp,
		);

		if ($ignore_sticky_posts) {
			$args['ignore_sticky_posts'] = 1;
		}

		$tax_query = [];

		if (!empty($blog_categories) && !in_array('0', $blog_categories, true)) {
			$tax_query[] = array(
				'taxonomy' => 'category',
				'field' => 'slug',
				'terms' => $blog_categories
			);
		}

		if (!empty($blog_tags)) {
			$tax_query[] = array(
				'taxonomy' => 'post_tag',
				'field' => 'slug',
				'terms' => $blog_tags
			);
		}

		if (!empty($tax_query)) {
			$args['tax_query'] = $tax_query;
		}

		if (!empty($blog_posts)) {
			$args['post__in'] = $blog_posts;
		}

		if (!empty($blog_authors)) {
			$args['author__in'] = $blog_authors;
		}

		if (!empty($offset)) {
			$args['offset'] = $ppp * ($page - 1) + $offset;
		} else {
			$args['paged'] = $page;
		}

		if (!empty($exclude)) {
			$args['post__not_in'] = $exclude;
		}

		return new WP_Query($args);
	}
}

function gem_featured_posts_slider($params) {
    $params = array_merge(
        array(
	        'source_type' => 'custom',
	        'related_by_categories' => '1',
	        'related_by_tags' => '',
	        'related_by_author' => '',
	        'source' => 'featured',
	        'featured_source' => 'featured',
	        'categories' => '',
	        'select_blog_cat' => '',
	        'select_blog_tags' => '',
	        'select_blog_posts' => '',
	        'select_blog_authors' => '',
	        'order_by' => 'default',
	        'order' => 'default',
	        'offset' => '',
	        'exclude_blog_posts' => '',
            'style' => 'default',
            'layout' => 'container',
            'max_height' => '',
            'fullheight' => 0,
            'centered_captions' => 0,
            'title_style' => 'big',
            'max_posts' => 3,
            'hide_button' => 0,
            'hide_date' => 0,
            'hide_excerpt' => 0,
            'hide_categories' => 0,
            'hide_author_avatar' => 0,
            'hide_author' => 0,
            'use_background_overlay' => 0,
            'overlay_color' => '#000000',
            'overlay_opacity' => 0.7,
            'post_title_color' => '',
            'post_author_color' => '',
            'post_date_color' => '',
            'post_excerpt_color' => '',
            'post_categories_color' => '',
            'paginator_type' => 'arrows',
            'paginator_size' => 'regular',
            'paginator_style' => 'light',
            'paginator_position' => 'left_right',
            'sliding_effect' => 'slide',
            'post_types' => post_type_exists('thegem_news') ? array('post', 'thegem_news') : array('post'),
            'button' => array(),
            'paginator' => array(),
        ),
        $params
    );

    $taxonomies = array('category');
    if(taxonomy_exists('thegem_news_sets')) {
        $taxonomies[] = 'thegem_news_sets';
    }

    if (empty($params['max_height'])) {
        $params['max_height'] = $params['layout']=='container' ? '628' : '758';
    }

    $params['button'] = array_merge(array(
        'text' => __('Read More', 'thegem'),
        'style' => 'outline',
        'size' => 'small',
        'text_weight' => 'thin',
        'no_uppercase' => 0,
        'corner' => '',
        'border' => 1,
        'text_color' => '#ffffff',
        'background_color' => '',
        'border_color' => '#ffffff',
        'hover_text_color' => '#000000',
        'hover_background_color' => '#ffffff',
        'hover_border_color' => '',
        'icon_pack' => 'elegant',
        'icon_elegant' => '',
        'icon_material' => '',
        'icon_fontawesome' => '',
        'icon_thegem_header' => '',
        'icon_userpack' => '',
        'icon_position' => 'left'
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

    $params['paginator'] = array_merge(array(
        'type' => 'arrows',
        'icon' => '1',
        'size' => 'regular',
        'style' => 'light',
        'position' => 'left_right',
    ), $params['paginator']);

    wp_enqueue_style('thegem-featured-posts-slider');
    wp_enqueue_style('icons-arrows');
    wp_enqueue_script('thegem-featured-posts-slider');

    global $post;
    $current_post = $post;

	if ( is_category() && $params['source_type'] == 'archive' ) {
		$params['source'] = 'categories';
		$params['select_blog_cat'] = get_queried_object()->slug;
	} else if ( is_tag() && $params['source_type'] == 'archive' ) {
		$params['source'] = 'tags';
		$params['select_blog_tags'] = get_queried_object()->slug;
	}

    $query_args = array(
        'post_type' => $params['post_types'],
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
    );

	if (($params['source_type'] == 'custom' && $params['source'] == 'featured') || ($params['source_type'] == 'archive' && $params['featured_source'] == 'featured')) {
		$query_args['post__not_in'] = array($current_post->ID);
		$query_args['meta_query'] = array(
			array(
				'key' => 'thegem_show_featured_posts_slider',
				'value' => 1
			)
		);
	}

	if ($params['source_type'] !== 'related' && $params['source'] == 'categories') {
		$params['categories'] = $params['select_blog_cat'];
	}

	if ($params['categories'] && is_string($params['categories'])) {
		$params['categories'] = explode(',', $params['categories']);
	} else if ($params['source_type'] == 'related' && !empty($params['related_by_categories'])) {
		$categories = get_the_category();
		if ( !empty( $categories ) ) {
			$params['categories'] = [];
			foreach( $categories as $category ) {
				$params['categories'][] = $category->slug;
			}
		}
	}

	if ($params['source_type'] !== 'related' && $params['source'] == 'tags' && !empty($params['select_blog_tags'])) {
		$query_args['tax_query'] = array(
			'relation' => 'OR',
			array(
				'taxonomy' => 'post_tag',
				'field' => 'slug',
				'terms' => explode(",", $params['select_blog_tags'])
			),
		);
	} else if ($params['source_type'] == 'related' && !empty($params['related_by_tags'])) {
		$tags = get_the_terms( get_the_ID(), 'post_tag' );
		if ( !empty( $tags ) ) {
			$blog_tags = [];
			foreach( $tags as $tag ) {
				$params['blog_tags'][] = $tag->slug;
			}
			$query_args['tax_query'] = array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'post_tag',
					'field' => 'slug',
					'terms' => $blog_tags
				),
			);
		}
	}
	if ($params['source_type'] !== 'related' && $params['source'] == 'posts' && !empty($params['select_blog_posts'])) {
		$query_args['post__in'] = explode(",", $params['select_blog_posts']);
		$query_args['ignore_sticky_posts'] = 1;
	}
	if ($params['source_type'] !== 'related' && $params['source'] == 'authors' && !empty($params['select_blog_authors'])) {
		$query_args['author__in'] = explode(",", $params['select_blog_authors']);
	} else if ($params['source_type'] == 'related' && !empty($params['related_by_author'])) {
		$query_args['author__in'] = get_the_author_meta( 'ID' );
	}
	if (!empty($params['categories']) && !in_array('--all--', $params['categories'])) {
        $query_args['tax_query'] = array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $params['categories']
            ),
        );

        if (taxonomy_exists('thegem_news_sets')) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'thegem_news_sets',
                'field' => 'slug',
                'terms' => $params['categories']
            );
        }
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

	if (!empty($params['exclude_blog_posts'])) {
		$post__not_in = explode(',', $params['exclude_blog_posts']);
	}
	if ($params['source_type'] == 'related') {
		if (!empty($post__not_in)) {
			$post__not_in[] = get_the_ID();
		} else {
			$post__not_in = get_the_ID();
		}
	}
	if (!empty($post__not_in)) {
		$query_args['post__not_in'] = $post__not_in;
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
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>

    <?php
		if ($params['layout']=='fullwidth') {
			echo '<script type="text/javascript">if (typeof(gem_fix_fullwidth_position) == "function") { gem_fix_fullwidth_position(document.getElementById("' . esc_attr($slider_uid) . '")); }</script>';
		}
}

// Print Thegem Heading
function thegem_heading($params) {
	$text = $icon = $link_atts = $label = $class = $main_style = $data_attributes = $link_before = $link_after = $span_before = $span_after = '';

    $id = uniqid('thegem-heading-');
	$class .= 'thegem-heading';
	$uniq_interaction = uniqid('thegem-heading-interaction-');

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
	
	if (isset($params['heading_link']) && !empty($params['heading_link'])) {
		$link = vc_build_link($params['heading_link']);

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

		$link_before = '<a '.$link_atts.'>';
		$link_after = '</a>';
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

	if ($params['text_source'] == 'page_title') {
		$text = esc_html(thegem_title('', false));
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
                    $rotating_text .= '<span class="thegem-heading-rotating-text" '.($r_key!=0 ? 'style="opacity: 0; position: absolute;"' : '').'>'.nl2br(esc_html($rotating_text_item['text'])).'</span>';
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