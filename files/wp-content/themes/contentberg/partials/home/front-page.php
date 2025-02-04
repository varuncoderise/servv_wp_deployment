<?php 
/**
 * Home Partial - Static front page portion
 */

$classes = array('the-post', 'the-page');

?>
	<div class="ts-row cf">
		<div class="col-8 main-content cf">
		
		<?php while (have_posts()) : the_post(); ?>

		<article <?php
			// Setup article attributes 
			Bunyad::markup()->attribs('page-wrapper', array(
				'id'        => 'post-' . get_the_ID(),
				'class'     => join(' ', get_post_class($classes)),
			)); ?>>
			
			<header class="post-header the-post-header cf">
				<?php if (!Bunyad::posts()->meta('featured_disable')): ?>
				
				<div class="featured">
				
					<?php if (has_post_thumbnail()): ?>
					
						<?php 
							/**
							 * Normal featured image when no post format
							 */
							
							// Link to image
							$url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); 
							$url = $url[0];
							
							// Which image?
							if (!Bunyad::options()->featured_crop) {
								$image = 'large';
							}
							else if (Bunyad::posts()->meta('layout_style') == 'full' OR Bunyad::core()->get_sidebar() == 'none') {
								$image = 'contentberg-main-full';
							}
							else {
								$image = 'contentberg-main';
							}
						
						?>
					
						<a href="<?php echo esc_url($url); ?>" class="image-link"><?php the_post_thumbnail(
								$image,  // larger image if no sidebar
								array('title' => strip_tags(get_the_title()))
							); ?>
						</a>
						
					<?php endif; // normal featured image ?>
				</div>
				
				<?php endif; // featured check ?>
		
				<h1 class="post-title-alt the-page-title"><?php the_title(); ?></h1>
				
			</header><!-- .post-header -->			
		
			<div class="post-content entry-content cf">
				
				<?php
					/**
					 * A wrapper for the_content() for some of our magic.
					 * 
					 * Note: the_content filter is applied.
					 * 
					 * @see the_content()
					 */
					Bunyad::posts()->the_content(null, false);
				
				?>
					
			</div><!-- .post-content -->
				
			<?php if (comments_open()): ?>
		
			<div class="comments">
				<?php comments_template('', true); ?>
			</div>
			
			<?php endif;?>
				
		</article>
	
		<?php endwhile; // end of the loop. ?>

		</div>
		
		<?php Bunyad::core()->theme_sidebar(); ?>
		
	</div> <!-- .ts-row -->