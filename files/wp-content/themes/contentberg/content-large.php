<?php
/**
 * Content template to be used for large posts in listings - called via loop.php
 */

$show_full   = (is_single() OR Bunyad::options()->post_body == 'full');
$extra_class = !$show_full ? 'post-excerpt' : 'entry-content';

?>

<article <?php
	// Setup article attributes
	Bunyad::markup()->attribs('large-post-wrapper', array(
		'id'     => 'post-' . get_the_ID(),
		'class'  => join(' ', get_post_class('post-main large-post')), 
	)); ?>>
	
	<header class="post-header cf">

		<?php get_template_part('partials/content/featured'); ?>
		
		<?php Bunyad::get('helpers')->post_meta(null, array('enable_cat' => 1)); ?>
		
	</header><!-- .post-header -->

	<div <?php Bunyad::markup()->attribs('post-content', array('class' => array('post-content description cf', $extra_class))); ?>>
		
		<?php

		// Excerpts or main content?
		if ($show_full):

			/**
			 * A wrapper for the_content() for some of our magic.
			 * 
			 * Note: the_content filter is applied.
			 * 
			 * @see the_content()
			 */
			Bunyad::posts()->the_content(null, true);
			
		else:

			// Show the excerpt,  always add Keep Reading button (more button), and respect <!--more--> (teaser) 
			echo Bunyad::posts()->excerpt(
				null, 
				Bunyad::options()->post_excerpt_blog, 
				array('add_more' => false, 'use_teaser' => true)
			);
		
		endif;
		
		?>
		
		<?php 
			/**
			 * Add read more here when it's forced in settings, but the post footer is disabled
			 */
			if (Bunyad::options()->post_footer_read_more && !Bunyad::options()->post_footer_blog): ?>
		
			<div class="read-more"><a href="<?php the_permalink(); ?>"><span><?php echo esc_html(Bunyad::posts()->more_text); ?></span></a></div>
			
		<?php endif; ?>

	</div><!-- .post-content -->
	
	
	<?php if (Bunyad::options()->post_footer_blog): ?>
			
	<div class="post-footer">
	
		<?php if (Bunyad::options()->post_footer_author): ?>
			<div class="col col-4 author"><?php printf(esc_html_x('by %s', 'Post Meta', 'contentberg'), get_the_author_posts_link()); ?></div>
		<?php endif; ?>
		
		<?php if (Bunyad::options()->post_footer_read_more): ?>
			<div class="col col-4 read-more"><a href="<?php the_permalink(); ?>" class="read-more-link"><?php echo esc_html(Bunyad::posts()->more_text); ?></a></div>
		<?php endif; ?>
		
		<?php if (Bunyad::options()->post_footer_social): ?>
			<div class="col col-4 social-icons">
		
			<?php if (class_exists('ContentBerg_Core')): ?>
				<?php 
					// See plugins/contentberg-core/social-share/views/social-share-inline.php
					Bunyad::get('cb_social')->render('social-share-inline'); 
				?>
			<?php endif;?>
		
			</div>
		<?php endif; ?>
		
	</div>
	
	<?php endif; ?>
		
</article>
