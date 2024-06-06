<?php

	$thegem_post_data = thegem_get_sanitize_page_title_data(get_the_ID());

	$thegem_post_item_data = thegem_get_sanitize_post_data(get_the_ID());

	$thegem_post_date = get_the_date("M Y");

	$item_colors = isset($params['item_colors']) ? $params['item_colors'] : array();

	$thegem_categories = wp_get_object_terms(get_the_ID(), 'category');
	$thegem_categories_list = array();
	foreach($thegem_categories as $thegem_category) {
		$thegem_categories_list[] = '<a href="'.esc_url(get_category_link( $thegem_category->term_id )).'" title="'.esc_attr( sprintf( __( "View all posts in %s", "thegem" ), $thegem_category->name ) ).'">'.$thegem_category->name.'</a>';
	}

	$thegem_classes = array();

	$is_sticky = is_sticky() && empty($params['ignore_sticky']) && !is_paged();
	if ($is_sticky) {
		$thegem_classes = array_merge($thegem_classes, array('sticky', 'default-background'));
	}

	$has_content_gallery = get_post_format(get_the_ID()) == 'gallery';
	$thegem_post_sources = array();
	if (has_post_thumbnail() && !$has_content_gallery) {
		$thegem_post_sources = array(
			array('media' => '(max-width: 768px)', 'srcset' => array('1x' => 'thegem-blog-timeline-large', '2x' => 'thegem-blog-timeline-large')),
			array('media' => '(max-width: 1050px)', 'srcset' => array('1x' => 'thegem-blog-timeline-small', '2x' => 'thegem-blog-timeline-large')),
			array('media' => '(max-width: 1920px)', 'srcset' => array('1x' => 'thegem-blog-timeline', '2x' => 'thegem-blog-timeline-large')),
		);
	}

	$thegem_featured_content = thegem_get_post_featured_content(get_the_ID(), $has_content_gallery ? 'thegem-blog-timeline-large' : 'thegem-blog-default-large', false, $thegem_post_sources);
	if(empty($thegem_featured_content)) {
		$thegem_classes[] = 'no-image';
	}

	$thegem_classes[] = 'item-animations-not-inited';

?>

<article id="post-<?php the_ID(); ?>" <?php post_class($thegem_classes); ?> data-post-date="<?php echo esc_attr($thegem_post_date); ?>">
	<div class="item-post-container">
		<div class="post-timeline-elements">
			<div class="pointer"<?php echo (!empty($item_colors['time_line_color']) ? ' style="box-shadow: 0 0 0 3px '.esc_attr($item_colors['time_line_color']).'"' : ''); ?>><div><span class="date"<?php echo (!empty($item_colors['date_color']) ? ' style="color: '.esc_attr($item_colors['date_color']).'"' : ''); ?>><?php echo get_the_date('d M'); ?></span><span class="time"<?php echo (!empty($item_colors['time_color']) ? ' style="color: '.esc_attr($item_colors['time_color']).'"' : ''); ?>><?php echo get_the_date('H:i'); ?></span></div></div>
			<div class="pointer-right"<?php echo (!empty($item_colors['time_line_color']) ? ' style="box-shadow: 0 0 0 3px '.esc_attr($item_colors['time_line_color']).'"' : ''); ?>><div><span class="date"<?php echo (!empty($item_colors['date_color']) ? ' style="color: '.esc_attr($item_colors['date_color']).'"' : ''); ?>><?php echo get_the_date('d M'); ?></span><span class="time"<?php echo (!empty($item_colors['time_color']) ? ' style="color: '.esc_attr($item_colors['time_color']).'"' : ''); ?>><?php echo get_the_date('H:i'); ?></span></div></div>
			<?php if($thegem_post_date != $last_post_date): ?>
				<div class="timeline-date-title" style="<?php echo (!empty($item_colors['time_line_color']) ? 'border-color: '.esc_attr($item_colors['time_line_color']).';' : ''); ?><?php echo (!empty($item_colors['month_color']) ? 'color: '.esc_attr($item_colors['month_color']).';' : ''); ?>"><?php echo $thegem_post_date; ?></div>
				<div class="timeline-date-title-right" style="<?php echo (!empty($item_colors['time_line_color']) ? 'border-color: '.esc_attr($item_colors['time_line_color']).';' : ''); ?><?php echo (!empty($item_colors['month_color']) ? 'color: '.esc_attr($item_colors['month_color']).';' : ''); ?>"><?php echo $thegem_post_date; ?></div>
			<?php endif; ?>
		</div>

		<div class="post-item clearfix" style="<?php echo (!empty($item_colors['background_color']) ? 'background-color: '.esc_attr($item_colors['background_color']).';' : ''); ?><?php echo (!empty($item_colors['border_color']) ? 'border-color: '.esc_attr($item_colors['border_color']).';' : ''); ?>">
			<?php
				if(!is_single() && $is_sticky) {
					echo '<div class="sticky-label">&#xe61a;</div>';
				}
			?>
			<svg class="wrap-style" style="<?php echo (!empty($item_colors['background_color']) ? 'fill: '.esc_attr($item_colors['background_color']).';' : ''); ?><?php echo (!empty($item_colors['border_color']) ? 'stroke: '.esc_attr($item_colors['border_color']).';' : ''); ?>">
				<use xlink:href="<?php echo esc_url(THEGEM_THEME_URI . '/css/post-arrow.svg'); ?>#dec-post-arrow"></use>
			</svg>
			<svg class="wrap-style wrap-style-right" style="<?php echo (!empty($item_colors['background_color']) ? 'fill: '.esc_attr($item_colors['background_color']).';' : ''); ?><?php echo (!empty($item_colors['border_color']) ? 'stroke: '.esc_attr($item_colors['border_color']).';' : ''); ?>">
				<use xlink:href="<?php echo esc_url(THEGEM_THEME_URI . '/css/post-arrow.svg'); ?>#dec-post-arrow"></use>
			</svg>

			<?php if($thegem_featured_content): ?>
				<div class="post-image"><?php echo $thegem_featured_content; ?></div>
			<?php endif; ?>
			<?php if(get_post_format() != 'quote' || empty($thegem_featured_content)) : ?>
				<div class="post-text-wrap">
					<div class="post-meta date-color">
						<div class="entry-meta clearfix gem-post-date">
							<div class="post-meta-left">
								<?php if(!$params['hide_author']) : ?><span class="post-meta-author"><?php printf( esc_html__( "By %s", "thegem" ), get_the_author_link() ) ?></span><?php endif; ?>
							</div>
							<div class="post-meta-right">
							<?php  if(comments_open() && !$params['hide_comments']) : ?>
								<span class="comments-link"><?php comments_popup_link(0, 1, '%'); ?></span>
							<?php endif; ?>
							<?php if(comments_open() && !$params['hide_comments']  && function_exists('zilla_likes')): ?><span class="sep"></span><?php endif; ?>
							<?php if( function_exists('zilla_likes') && !$params['hide_likes'] ) { echo '<span class="post-meta-likes">';zilla_likes();echo '</span>'; } ?>
							</div>
						</div><!-- .entry-meta -->
					</div>
					<div class="post-title"><?php the_title('<'.($is_sticky ? 'h2' : 'h3').' class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark"'.(!empty($item_colors['post_title_color']) ? ' style="color: '.esc_attr($item_colors['post_title_color']).'"' : '').(!empty($item_colors['post_title_hover_color']) ? ' onmouseenter="jQuery(this).data(\'color\', this.style.color);this.style.color=\''.esc_attr($item_colors['post_title_hover_color']).'\';" onmouseleave="this.style.color=jQuery(this).data(\'color\');"' : '').'>', '</a></'.($is_sticky ? 'h2' : 'h3').'>'); ?></div>
					<div class="post-content"<?php echo (!empty($item_colors['post_excerpt_color']) ? ' style="color: '.esc_attr($item_colors['post_excerpt_color']).'"' : ''); ?>>
						<div class="summary">
							<?php if ( !has_excerpt() && !empty( $thegem_post_data['title_excerpt'] ) ): ?>
								<?php echo $thegem_post_data['title_excerpt']; ?>
							<?php else: ?>
								<?php echo preg_replace('%&#x[a-fA-F0-9]+;%', '', apply_filters('the_excerpt', get_the_excerpt())); ?>
							<?php endif; ?>
						</div>
					</div>
					<div class="post-misc">
						<div class="post-links">
                            <?php if(!$params['hide_social_sharing']) : ?>
                            <div class="post-footer-sharing"><?php thegem_button(array('icon' => 'share', 'size' => ($is_sticky ? 'medium' : 'tiny'), 'background_color' => (!empty($item_colors['sharing_button_color']) ? $item_colors['sharing_button_color'] : ''), 'text_color' => (!empty($item_colors['sharing_button_icon_color']) ? $item_colors['sharing_button_icon_color'] : '')), 1); ?><div class="sharing-popup"><?php thegem_socials_sharing(); ?><svg class="sharing-styled-arrow"><use xlink:href="<?php echo esc_url(THEGEM_THEME_URI . '/css/post-arrow.svg'); ?>#dec-post-arrow"></use></svg></div></div>
                            <?php endif; ?>
                            <div class="post-read-more"><?php thegem_button(array('href' => get_the_permalink(), 'style' => 'outline', 'text' => __('Read More', 'thegem'), 'size' => ($is_sticky ? 'medium' : 'tiny')), 1); ?></div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
