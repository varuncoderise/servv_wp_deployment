<?php
$thegem_size = $thegem_sizes[0];
$thegem_sources = $thegem_sizes[1]; ?>
<div <?php wc_product_cat_class($thegem_classes, $category); ?>>
	<?php if ($params['product_separator'] == '1') { ?>
		<div class="item-separator-box"></div>
	<?php } ?>
	<div class="wrap-out">
		<div class="wrap clearfix">
			<?php
			$image_style = '';
			if (!empty($params['custom_images_height']) && $params['custom_images_height'] == 1 && !empty($params['images_height'])) {
				$image_style = 'style="height: '.esc_html($params['images_height']).'px; padding: 0;"';
			} ?>
			<div class="category-thumbnail" <?php echo $image_style; ?>>
				<div class="category-thumbnail-inner <?php echo $thegem_size; ?>">
					<?php
					$thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
					if ($thumbnail_id) {
						thegem_generate_picture($thumbnail_id, $thegem_size, $thegem_sources, array('alt' => $category->name), true);
					} else { ?>
						<span class="product-dummy"></span>
					<?php } ?>
				</div>
				<?php
				if ($params['caption_position'] == 'below') { ?>
					<a class="category-link" href="<?php echo esc_url(get_term_link($category->term_id, 'product_cat')) ?>"></a>
				<?php } ?>
			</div>
			<a class="category-overlay" href="<?php echo $category->term_id > 0 ? esc_url(get_term_link($category->term_id, 'product_cat')) : '#'; ?>">
				<div class="category-overlay-inner">
					<div class="category-overlay-inner-inside">
						<?php if ($params['caption_separator'] == '1') { ?>
							<div class="category-overlay-separator"></div>
						<?php } ?>
						<h6 class="category-title"><?php echo $category->name; ?></h6>
						<?php if ($params['product_counts'] !== 'hidden') { ?>
							<div class="category-count visible-<?php echo $params['product_counts']; ?>">
								<div class="category-count-inside">
									<?php echo sprintf(esc_html(_n('%s product', '%s products', $category->count, 'thegem')), $category->count); ?>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</a>
		</div>
	</div>
</div>
