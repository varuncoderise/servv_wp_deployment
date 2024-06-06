<?php

get_header(); ?>

<div id="main-content" class="main-content">

<?php
	$thegem_page_template_id = thegem_page_template();
	while ( have_posts() ) : the_post();
		if($thegem_page_template_id) : ?>
			<?php echo thegem_page_title(); ?>
			<div class="block-content">
				<div class="fullwidth-content">
					<div class="thegem-template-wrapper thegem-template-page thegem-template-<?php echo esc_attr($thegem_page_template_id); ?>">
						<?php
							$template_custom_css = get_post_meta($thegem_page_template_id, '_wpb_shortcodes_custom_css', true) . get_post_meta($thegem_page_template_id, '_wpb_post_custom_css', true);
							if($template_custom_css) {
								echo '<style>' . $template_custom_css . '</style>';
							}
							$template = get_post($thegem_page_template_id);
							$template->post_content = str_replace(array('<p>[', ']</p>'), array('[', ']'), $template->post_content);
							$template->post_content = str_replace(array('[vc_row ', '[vc_row]', '[vc_section ', '[vc_section]'), array('[vc_row template_fw="1" ', '[vc_row template_fw="1"]','[vc_section template_fw="1" ', '[vc_section template_fw="1"]'), $template->post_content);

							$GLOBALS['thegem_template_type'] = 'page';
							echo do_shortcode($template->post_content);
							unset($GLOBALS['thegem_template_type']);
						?>
					</div>
				</div><!-- .container -->
			</div><!-- .block-content -->
		<?php else :
			get_template_part( 'content', 'page' );
		endif;
	endwhile;
?>

</div><!-- #main-content -->

<?php
get_footer();
