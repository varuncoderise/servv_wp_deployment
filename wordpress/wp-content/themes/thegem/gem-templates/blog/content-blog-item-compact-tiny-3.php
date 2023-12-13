<?php

$thegem_post_data = thegem_get_sanitize_page_title_data(get_the_ID());

$item_colors = isset($params['item_colors']) ? $params['item_colors'] : array();

$thegem_classes = array();

$is_sticky = is_sticky() && empty($params['ignore_sticky']) && !is_paged();
if ($is_sticky) {
	$thegem_classes = array_merge($thegem_classes, array('sticky'));
}

if (!has_post_thumbnail()) {
	$thegem_classes[] = 'no-image';
}

if ($params['show_separator']) {
	$thegem_classes[] = 'with-separator';
}

$thegem_classes[] = 'clearfix';

if ($params['title_preset'] == 'default') {
	$params['title_preset'] = 'main-menu-item';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($thegem_classes); ?>
		 style="<?php if (isset($params['bottom_gap'])) {
			 echo 'margin-bottom:' . $params['bottom_gap'] . 'px;';
		 } ?>">
	<div class="wrap">

		<a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark" class="over-link"></a>

		<div class="post-image gem-compact-tiny-item-image" style="<?php if (!empty($params['image_height'])) {
			echo 'height:' . $params['image_height'] . 'px;';
			echo 'height:' . $params['image_height'] . 'px;';
		} ?>">
			<?php thegem_post_thumbnail('thegem-blog-default-large', true, 'img-responsive'); ?>
		</div>

		<?php if ($params['show_category']) {
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

		<div class="gem-compact-caption">
			<?php if (!$params['hide_author']) : ?>
				<div class="post-meta text-body-tiny" <?php echo(!empty($item_colors['date_color']) ? ' style="color: ' . esc_attr($item_colors['date_color']) . '"' : ''); ?>>
					<span class="post-meta-author"><?php printf(esc_html__("By %s", "thegem"), get_the_author_link()) ?></span>
				</div>
			<?php endif; ?>

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
					echo('<div class="gem-news-item-title ' . esc_attr($params['title_preset']) . '"><a class="' . $color_class . '" href="' . esc_url(get_permalink()) . '" rel="bookmark" style="' . $title_style . '"' . (!empty($item_colors['post_title_hover_color']) ? ' onmouseenter="jQuery(this).data(\'color\', this.style.color);this.style.color=\'' . esc_attr($item_colors['post_title_hover_color']) . '\';" onmouseleave="this.style.color=jQuery(this).data(\'color\');"' : '') . '>' . $title . '</a></div>');
				} ?>
			</div>
			<?php if (!$params['hide_date']) : ?>
				<div class="post-meta text-body-tiny date-color" <?php echo(!empty($item_colors['date_color']) ? ' style="color: ' . esc_attr($item_colors['date_color']) . '"' : ''); ?>>
					<span class="post-meta-date"><?php echo get_the_date() ?></span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
