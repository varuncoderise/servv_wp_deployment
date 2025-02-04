<?php 
/**
 * The main loop used to list posts in the category, tag, author archives etc.
 */

$query = !isset($query) ? $wp_query : $query;

?>

<div class="posts-container posts-large cf">

<?php if ($query->have_posts()): ?>

	<div class="posts-wrap">
	
	<?php while ($query->have_posts()) : $query->the_post(); ?>
		
		<?php
			// Get content-large.php or content-large-b.php
			get_template_part('content-' . sanitize_file_name(Bunyad::options()->post_large_style), get_post_format()); 
		?>
		
	<?php endwhile; ?>

	</div>
	
	<?php Bunyad::core()->partial('partials/pagination', compact('query', 'pagination', 'pagination_type')); ?>
	
<?php else: ?>

	<?php get_template_part('content-none'); ?>

<?php endif; ?>

</div>