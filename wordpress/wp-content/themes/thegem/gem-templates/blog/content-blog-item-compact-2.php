<?php

	$thegem_post_data = thegem_get_sanitize_page_title_data(get_the_ID());

	$params = isset($params) ? $params : array(
		'hide_author' => 0,
		'hide_comments' => 0,
		'hide_date' => 0,
	);

	$thegem_classes = array();

	if(is_sticky() && !is_paged()) {
		$thegem_classes = array_merge($thegem_classes, array('sticky'));
	}

	if(has_post_thumbnail()) {
		$thegem_classes[] = 'no-image';
	}

	$thegem_classes[] = 'item-animations-not-inited';
	$thegem_classes[] = 'clearfix';

?>

<article id="post-<?php the_ID(); ?>" <?php post_class($thegem_classes); ?>>
	<div class="gem-news-item-left">
		<div class="gem-news-item-image">
			<a href="<?php echo esc_url(get_permalink()); ?>"><?php thegem_post_thumbnail('thegem-news-carousel'); ?></a>
		</div>
	</div>


	<div class="gem-news-item-right">
		<div class="gem-news-item-right-conteiner">
		<?php the_title('<div class="gem-news-item-title"><a href="'.esc_url(get_permalink()).'">', '</a></div>'); ?>

			<?php if(has_excerpt()) : ?>
				<div class='gem-news_title-excerpt'>
					<?php the_excerpt(); ?>
				</div>
			<?php else : ?>
				<div class='gem-news_title_excerpt'>
					<?php
						$thegem_item_title_data = thegem_get_sanitize_page_title_data(get_the_ID());

						echo $thegem_item_title_data['title_excerpt'];
					?>
				</div>
			<?php endif; ?>
		</div>
		<div  class="gem-news-item-meta">
			<div class="gem-news-item-date small-body"><?php echo get_the_date(); ?></div>
			<div class="gem-news-zilla-likes">
				<?php if( function_exists('zilla_likes') && !$params['hide_likes'] ) { echo '<span class="post-meta-likes">';zilla_likes();echo '</span>'; } ?>
				<?php if(comments_open() && !$params['hide_comments'] ): ?>
					<span class="comments-link"><?php comments_popup_link(0, 1, '%'); ?></span>
				<?php endif; ?>
			</div>
		</div>

	</div>
</article><!-- #post-<?php the_ID(); ?> -->
