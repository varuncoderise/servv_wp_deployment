<?php

/**
 * Template Name: TheGem Full Width
 * Template Post Type: post,thegem_pf_item
 *
 * @package TheGem
 */

get_header(); ?>

<div id="main-content" class="main-content">

<?php
	$thegem_post_template_id = thegem_single_post_template();
	while ( have_posts() ) : the_post();
		if(in_array(get_post_type(), array('post', 'thegem_news'), true) && $thegem_post_template_id && defined('ELEMENTOR_VERSION')) : ?>
			<?php echo thegem_page_title(); ?>
			<div class="block-content">
				<div class="fullwidth-content">
					<div class="thegem-template-wrapper thegem-template-single-post thegem-template-<?php echo esc_attr($thegem_post_template_id); ?>">
						<?php
								$GLOBALS['thegem_template_type'] = 'single-post';
								echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($thegem_post_template_id);
								unset($GLOBALS['thegem_template_type']);
						?>
					</div>
				</div><!-- .container -->
			</div><!-- .block-content -->
		<?php else : if(in_array(get_post_type(), array_merge(array('post', 'thegem_pf_item', 'thegem_news'), thegem_get_available_po_custom_post_types()), true)) {
			get_template_part( 'content', 'page-fullwidth' );
		} else {
			get_template_part( 'content', get_post_format() );
		}
		endif;
	endwhile;
?>

</div><!-- #main-content -->

<?php
get_footer();
