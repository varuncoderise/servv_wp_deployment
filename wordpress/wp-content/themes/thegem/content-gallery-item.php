<?php
if ($is_size_item) {
	$thegem_classes = array_merge(array('gallery-item', 'size-item'), $item_classes);
} else {
	$thegem_item = get_post($attachment_id);

	if (!$thegem_item) {
		return;
	}

	$thegem_full_image_url = wp_get_attachment_image_src($thegem_item->ID, 'full');

	$thegem_highlight_type = 'disabled';
	if (!$params['ignore_highlights']) {
		$thegem_highlight = (bool)get_post_meta($thegem_item->ID, 'highlight', true);
		if ($thegem_highlight) {
			$thegem_highlight_type = get_post_meta($thegem_item->ID, 'highligh_type', true);
			if (!$thegem_highlight_type) {
				$thegem_highlight_type = 'squared';
			}
		}
	} else {
		$thegem_highlight = false;
	}

	$thegem_attachment_link = get_post_meta($thegem_item->ID, 'attachment_link', true);
	$thegem_single_icon = true;

	if (!empty($thegem_attachment_link)) {
		$thegem_single_icon = false;
	}

	if ($thegem_highlight_type != 'disabled') {
		$item_thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params, $thegem_highlight_type);
	} else {
		$item_thegem_sizes = $thegem_sizes;
	}

	$thegem_size = $item_thegem_sizes[0];
	$thegem_sources = $item_thegem_sizes[1];

	$thegem_classes = array('gallery-item');

	if (isset($gallery_items_filter_classes[$attachment_id])) {
		foreach ($gallery_items_filter_classes[$attachment_id] as $filer_class) {
			$thegem_classes[] = $filer_class;
		}
	}

	if ($thegem_highlight_type != 'disabled' && $thegem_highlight_type != 'vertical') {
		$thegem_classes = array_merge($thegem_classes, get_thegem_portfolio_render_item_classes($params, $thegem_highlight_type));
	} else {
		$thegem_classes = array_merge($thegem_classes, $item_classes);
	}

	if ($params['layout'] != 'metro' && $thegem_highlight) {
		$thegem_classes[] = 'double-item';
	}

	if ($params['layout'] != 'metro' && $thegem_highlight) {
		$thegem_classes[] = 'double-item-' . $thegem_highlight_type;
	}

	if ($params['loading_animation'] !== 'disabled') {
		$thegem_classes[] = 'item-animations-not-inited';
	}

	if (empty($params['icon']['value'])) {
		$thegem_classes[] = 'single-icon';
	}
}

$thegem_wrap_classes = $params['item_style'];

?>
<li <?php post_class($thegem_classes); ?> style="<?php
if (!$is_size_item && $params['layout'] == 'justified' && $params['ignore_highlights'] && !empty($active_filter) && empty(array_intersect($active_filter, $thegem_classes))) { echo 'display: none;'; } ?>">
	<?php if (!$is_size_item) { ?>
		<div class="wrap <?php if($params['type'] == 'grid' && $params['item_style'] != ''): ?> gem-wrapbox-style-<?php echo esc_attr($thegem_wrap_classes); ?><?php endif; ?>">
			<?php if($params['type'] == 'grid' && $params['item_style'] == '11'): ?>
				<div class="gem-wrapbox-inner"><div class="shadow-wrap">
			<?php endif; ?>
			<div class="overlay-wrap">
				<div class="image-wrap <?php if($params['type'] == 'grid' && $params['item_style'] == '11'): ?>img-circle<?php endif; ?>"<?php
				if (isset($params['image_size']) && $params['image_size'] == 'full' && !empty($params['image_ratio'])) { echo ' style="aspect-ratio:' . $params['image_ratio'] . '"'; }
				if (isset($params['image_size']) && $params['image_size'] == 'default' && !empty($params['image_ratio_default'])) { echo ' style="aspect-ratio:' . $params['image_ratio_default'] . '"'; } ?>>
					<?php
						$thegem_attrs = array('alt' => get_post_meta($thegem_item->ID, '_wp_attachment_image_alt', true));
						if ($params['type'] == 'slider') {
							$thegem_attrs['data-thumb-url'] = esc_url($thegem_thumb_image_url[0]);
						}
						thegem_generate_picture($thegem_item->ID, $thegem_size, $thegem_sources, $thegem_attrs);
					?>
				</div>
				<div class="overlay <?php if($params['type'] == 'grid' && $params['item_style'] == '11'): ?>img-circle<?php endif; ?>">
					<div class="overlay-circle"></div>
					<?php if($thegem_single_icon): ?>
						<a href="<?php echo esc_url($thegem_full_image_url[0]); ?>" class="gallery-item-link fancy-gallery" data-fancybox="gallery-<?php echo esc_attr($gallery_uid); ?>">
							<span class="slide-info">
								<?php if(!empty($thegem_item->post_excerpt)) : ?>
									<span class="slide-info-title">
										<?php echo $thegem_item->post_excerpt; ?>
									</span>
									<?php if(!empty($thegem_item->post_content)) : ?>
										<span class="slide-info-summary">
											<?php echo $thegem_item->post_content; ?>
										</span>
									<?php endif; ?>
								<?php endif; ?>
							</span>
						</a>
					<?php endif; ?>
					<div class="overlay-content">
						<div class="overlay-content-center">
							<div class="overlay-content-inner">
								<?php if ($params['icon_show'] && $params['hover'] != 'zoom-overlay' && $params['hover'] != 'disabled') { ?>
									<a href="<?php echo esc_url($thegem_full_image_url[0]); ?>" class="icon photo <?php if(!$thegem_single_icon): ?>fancy-gallery<?php endif; ?>" <?php if(!$thegem_single_icon): ?>data-fancybox="gallery-<?php echo esc_attr($gallery_uid); ?>"<?php endif; ?> >
										<?php if(!$thegem_single_icon): ?>
											<span class="slide-info">
												<?php if ($params['title_show'] && !empty($thegem_item->post_excerpt)) : ?>
													<span class="slide-info-title ">
														<?php echo $thegem_item->post_excerpt; ?>
													</span>
													<?php if ($params['description_show'] && !empty($thegem_item->post_content)) : ?>
														<span class="slide-info-summary">
															<?php echo $thegem_item->post_content; ?>
														</span>
													<?php endif; ?>
												<?php endif; ?>
											</span>
										<?php endif; ?>
									</a>

									<?php if (!empty($thegem_attachment_link)): ?>
										<a href="<?php echo esc_url($thegem_attachment_link); ?>" target="_blank" class="icon link"></a>
									<?php endif; ?>
									<div class="overlay-line"></div>
								<?php } ?>
								<?php if ($params['title_show'] && !empty($thegem_item->post_excerpt)) : ?>
									<div class="title">
										<?php echo $thegem_item->post_excerpt; ?>
									</div>
								<?php endif; ?>
								<?php if ($params['description_show'] && !empty($thegem_item->post_content)) : ?>
									<div class="subtitle">
										<?php echo $thegem_item->post_content; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php if($params['type'] == 'grid' && $params['item_style'] == '11'): ?>
				</div></div>
			<?php endif; ?>
		</div>
		<?php if ($params['layout']  == 'metro' &&  $params['item_style']):?><?php endif;?>
	<?php } ?>
</li>
