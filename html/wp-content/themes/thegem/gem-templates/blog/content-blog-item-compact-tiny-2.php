<?php

$thegem_post_data = thegem_get_sanitize_page_title_data(get_the_ID());

$item_colors = isset($params['item_colors']) ? $params['item_colors'] : array();

$thegem_classes = array();

$is_sticky = is_sticky() && empty($params['ignore_sticky']) && !is_paged();
if ($is_sticky) {
	$thegem_classes = array_merge($thegem_classes, array('sticky'));
}

if (!has_post_thumbnail() || $params['hide_featured']) {
	$thegem_classes[] = 'no-image';
}

if ($params['show_separator']) {
	$thegem_classes[] = 'with-separator';
}

$thegem_classes[] = 'clearfix';

if ($params['title_preset'] == 'default') {
	$params['title_preset'] = 'text-body-tiny';
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
	<?php if (!$params['hide_featured']) { ?>
		<div class="gem-compact-tiny-left">
			<?php
			$image_style = '';
			if (!empty($params['image_size'])) {
				$image_style .= 'width:' . $params['image_size'] . 'px; height:' . $params['image_size'] . 'px; ';
			}
			if (isset($params['image_border_radius'])) {
				$image_style .= 'border-radius:' . $params['image_border_radius'] . 'px;';
			} ?>
			<div class="gem-news-item-image" style="<?php echo esc_attr($image_style); ?>">
				<a href="<?php echo esc_url(get_permalink()); ?>"><?php thegem_post_thumbnail('thegem-news-carousel', true, 'img-responsive'); ?></a>
			</div>
		</div>
	<?php } ?>

	<div class="gem-compact-tiny-right">
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
		<div class="post-meta">
			<div class="entry-meta clearfix text-body-tiny">
				<div class="post-meta-left gem-news-item-date date-color" <?php echo(!empty($item_colors['date_color']) ? 'style="color: ' . esc_attr($item_colors['date_color']) . '"' : ''); ?>>
					<?php if (!$params['hide_author']) : ?><span
							class="post-meta-author"><?php printf(esc_html__("By %s", "thegem"), get_the_author_link()) ?></span>
						<br><?php endif; ?>
					<?php if (!$params['hide_date']) : ?><span
							class="post-meta-date"><?php echo get_the_date() ?></span><?php endif; ?>
				</div>
				<div class="post-meta-right">
					<?php if (comments_open() && !$params['hide_comments']): ?>
						<span class="comments-link"><?php comments_popup_link(0, 1, '%'); ?></span>
					<?php endif; ?>
				</div>
			</div><!-- .entry-meta -->
		</div>

	</div>
</article><!-- #post-<?php the_ID(); ?> -->
