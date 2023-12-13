<?php

$thegem_post_data = thegem_get_sanitize_page_title_data(get_the_ID());

$item_colors = isset($params['item_colors']) ? $params['item_colors'] : array();

$thegem_classes = array();

$is_sticky = is_sticky() && empty($params['ignore_sticky']) && !is_paged();
if ($is_sticky) {
	$thegem_classes = array_merge($thegem_classes, array('sticky'));
}

$thegem_featured_content = thegem_get_post_featured_content(get_the_ID());
if (empty($thegem_featured_content) || $params['hide_featured']) {
	$thegem_classes[] = 'no-image';
}

if ($params['show_separator']) {
	$thegem_classes[] = 'with-separator';
}

if (empty($params['icon_on_hover'])) {
	$thegem_classes[] = 'without-hover-icon';
}

$thegem_classes[] = 'clearfix';

if ($params['title_preset'] == 'default') {
	$params['title_preset'] = 'main-menu-item';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($thegem_classes); ?>
		 style="<?php if (!empty($item_colors['border_color'])) {
			 echo 'border-color:' . $item_colors['border_color'] . ';';
		 } ?><?php if (isset($params['bottom_gap'])) {
			 echo 'margin-bottom:' . $params['bottom_gap'] . 'px;';
		 } ?><?php if (isset($params['bottom_gap']) && $params['show_separator']) {
			 echo 'padding-bottom:' . $params['bottom_gap'] . 'px;';
		 } ?>">
	<?php if (get_post_format() == 'quote' && $thegem_featured_content) : ?>
		<?php echo $thegem_featured_content; ?>
	<?php else : ?>

		<?php if ($thegem_featured_content && !$params['hide_featured']) : ?>
			<div class="post-image gem-compact-tiny-item-image" <?php if (isset($params['image_border_radius'])) {
				echo 'style="border-radius:' . $params['image_border_radius'] . 'px"';
			} ?>>
				<?php echo $thegem_featured_content; ?>
				<?php if ($params['show_category'] && get_post_format() !== 'audio') {
					$categories = wp_get_object_terms(get_the_ID(), 'category');
					$separator = ', ';
					$output = '';
					if (!empty($categories)) {
						foreach ($categories as $category) {
							$output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '" alt="' . esc_attr(sprintf(__('View all posts in %s', 'textdomain'), $category->name)) . '">' . esc_html($category->name) . '</a>' . $separator;
						}
						echo '<div class="categories">' . trim($output, $separator) . '</div>';
					}
				} ?>
			</div>
		<?php endif; ?>

		<div class="gem-compact-caption">
			<div class="gem-compact-item-content">
				<?php if ($params['show_title']) {
					$title_style = '';
					if (!empty($item_colors['post_title_color'])) {
						$title_style .= 'color: ' . esc_attr($item_colors['post_title_color']) . ';';
					}
					if (!empty($params['title_transform'])) {
						$title_style .= 'text-transform: ' . esc_attr($params['title_transform']) . ';';
					}
					$color_class = '';
					if ($params['title_preset'] !== 'main-menu-item') {
						$color_class = 'reverse-link-color ';
					}

					$title = get_the_title();
					if ($params['truncate_title']) {
						$title = thegem_truncate_by_words($title, $params['title_size']);
					}
					echo('<div class="gem-news-item-title '. esc_attr($params['title_preset']) .'"><a class="' . $color_class . '" href="' . esc_url(get_permalink()) . '" rel="bookmark" style="' . $title_style . '"' . (!empty($item_colors['post_title_hover_color']) ? ' onmouseenter="jQuery(this).data(\'color\', this.style.color);this.style.color=\'' . esc_attr($item_colors['post_title_hover_color']) . '\';" onmouseleave="this.style.color=jQuery(this).data(\'color\');"' : '') . '>'.$title.'</a></div>');
				} ?>
				<?php if ($params['show_description']) { ?>
					<div class="post-text">
						<div class="summary <?php echo esc_attr($params['post_excerpt_preset']); ?>"<?php echo(!empty($item_colors['post_excerpt_color']) ? ' style="color: ' . esc_attr($item_colors['post_excerpt_color']) . '"' : ''); ?>>
							<?php if (!has_excerpt() && !empty($thegem_post_data['title_excerpt'])): ?>
								<?php $excerpt = $thegem_post_data['title_excerpt']; ?>
							<?php else: ?>
								<?php $excerpt = preg_replace('%&#x[a-fA-F0-9]+;%', '', apply_filters('the_excerpt', get_the_excerpt())); ?>
							<?php endif; ?>
							<?php if ($params['truncate_description']) {
								echo thegem_truncate_by_words($excerpt, $params['description_size']);
							} else {
								echo $excerpt;
							} ?>
						</div>
					</div>
				<?php } ?>
			</div>
			<div class="post-meta">
				<div class="entry-meta clearfix text-body-tiny">
					<div class="post-meta-left gem-news-item-date date-color" <?php echo(!empty($item_colors['date_color']) ? ' style="color: ' . esc_attr($item_colors['date_color']) . '"' : ''); ?>>
						<?php if (!$params['hide_date']) : ?><span
								class="post-meta-date"><?php echo get_the_date() ?></span><?php endif; ?>
						<?php if (!$params['hide_author']) : ?><span
								class="post-meta-author"><?php printf(esc_html__("by %s", "thegem"), get_the_author_link()) ?></span><?php endif; ?>
					</div>
					<div class="post-meta-right">
						<?php if (comments_open() && !$params['hide_comments']): ?>
							<span class="comments-link"><?php comments_popup_link(0, 1, '%'); ?></span>
						<?php endif; ?>
					</div>
				</div><!-- .entry-meta -->
			</div>
		</div>
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
