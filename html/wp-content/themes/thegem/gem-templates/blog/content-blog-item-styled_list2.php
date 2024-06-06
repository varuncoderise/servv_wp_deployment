<?php

	$thegem_post_data = thegem_get_sanitize_page_title_data(get_the_ID());

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

	$thegem_link = get_permalink();
	if (!has_post_thumbnail())
		$thegem_classes[] = 'no-image';

	$thegem_classes[] = 'item-animations-not-inited';
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class($thegem_classes); ?>>
		<div class="item-post-container">
			<div class="post-item clearfix" style="<?php echo (!empty($item_colors['background_color']) ? 'background-color: '.esc_attr($item_colors['background_color']).';' : ''); ?><?php echo (!empty($item_colors['border_color']) ? 'border-color: '.esc_attr($item_colors['border_color']).';' : ''); ?>">
				<?php
					if(!is_single() && $is_sticky) {
						echo '<div class="sticky-label">&#xe61a;</div>';
					}
				?>
				<div class="post-info-wrap">
					<div class="post-info">
						<?php if(has_post_thumbnail()): ?>
							<div class="post-img"<?php echo (!empty($item_colors['border_color']) ? ' style="border-color: '.esc_attr($item_colors['border_color']).'"' : ''); ?>>
								<a href="<?php echo esc_url($thegem_link); ?>" class="default"><?php thegem_post_thumbnail('thegem-post-thumb-medium', true, 'img-responsive'); ?></a>
							</div>
						<?php else: ?>
							<div class="post-img"<?php echo (!empty($item_colors['border_color']) ? ' style="border-color: '.esc_attr($item_colors['border_color']).'"' : ''); ?>>
								<a href="<?php echo esc_url($thegem_link); ?>" class="default"><span class="dummy">&#xe640</span></a>
							</div>
						<?php endif; ?>
						<div class="post-date"<?php echo (!empty($item_colors['date_color']) ? ' style="color: '.esc_attr($item_colors['date_color']).'"' : ''); ?>><?php echo get_the_date('d F') ?></div>
						<div class="post-time"<?php echo (!empty($item_colors['time_color']) ? ' style="color: '.esc_attr($item_colors['time_color']).'"' : ''); ?>><?php echo get_the_date('H:i') ?></div>
					</div>
				</div>
				<svg class="wrap-style" style="<?php echo (!empty($item_colors['background_color']) ? 'fill: '.esc_attr($item_colors['background_color']).';' : ''); ?><?php echo (!empty($item_colors['border_color']) ? 'stroke: '.esc_attr($item_colors['border_color']).';' : ''); ?>">
					<use xlink:href="<?php echo esc_url(THEGEM_THEME_URI . '/css/post-arrow.svg'); ?>#dec-post-arrow"></use>
				</svg>
				<div class="post-text-wrap">
					<div class="post-title">
						<?php the_title('<'.($is_sticky ? 'h2' : 'h3').' class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark"'.(!empty($item_colors['post_title_color']) ? ' style="color: '.esc_attr($item_colors['post_title_color']).'"' : '').(!empty($item_colors['post_title_hover_color']) ? ' onmouseenter="jQuery(this).data(\'color\', this.style.color);this.style.color=\''.esc_attr($item_colors['post_title_hover_color']).'\';" onmouseleave="this.style.color=jQuery(this).data(\'color\');"' : '').'><span class="light">', '</span></a></'.($is_sticky ? 'h2' : 'h3').'>'); ?>
					</div>
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
						<div class="post-author">
							<?php if (!$params['hide_author']) : ?>
								<span class="post-meta-author"><?php printf( esc_html__( "By %s", "thegem" ), get_the_author_link() ); echo esc_html__( " in", "thegem" ); ?></span>
							<?php endif ?>
							<?php if($thegem_categories): ?>
								<span class="post-meta-categories"><?php echo implode('<span class="sep"></span>', $thegem_categories_list); ?></span>
							<?php endif ?>
						</div>
						<div class="post-soc-info">
							<?php if (comments_open() && !$params['hide_comments']): ?>
								<span class="post-comments">
									<span class="comments-link"><?php comments_popup_link(0, 1, '%'); ?></span>
									<?php if (!$params['hide_likes'] && function_exists('zilla_likes')): ?>
										<span class="sep"></span>
									<?php endif; ?>
								</span>
							<?php endif; ?>
							<?php if (!$params['hide_likes'] && function_exists('zilla_likes')): ?>
								<span class="post-likes">
									<span class="post-meta-likes">
										<?php zilla_likes(); ?>
									</span>
								</span>
							<?php endif ?>
						</div>
						<div class="post-links">
                            <?php if(!$params['hide_social_sharing']) : ?>
                            <div class="post-footer-sharing"><?php thegem_button(array('icon' => 'share', 'size' => ($is_sticky ? '' : 'tiny'), 'background_color' => (!empty($item_colors['sharing_button_color']) ? $item_colors['sharing_button_color'] : ''), 'text_color' => (!empty($item_colors['sharing_button_icon_color']) ? $item_colors['sharing_button_icon_color'] : '')), 1); ?><div class="sharing-popup"><?php thegem_socials_sharing(); ?><svg class="sharing-styled-arrow"><use xlink:href="<?php echo esc_url(THEGEM_THEME_URI . '/css/post-arrow.svg'); ?>#dec-post-arrow"></use></svg></div></div>
                            <?php endif; ?>
                            <div class="post-read-more"><?php thegem_button(array('href' => get_the_permalink(), 'style' => 'outline', 'text' => __('Read More', 'thegem'), 'size' => ($is_sticky ? '' : 'tiny')), 1); ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</article><!-- #post-<?php the_ID(); ?> -->
