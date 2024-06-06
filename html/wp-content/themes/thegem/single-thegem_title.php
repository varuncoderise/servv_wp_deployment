<?php

get_header(); ?>

<div id="main-content" class="main-content">
	<div id="page-title" class="page-title-block custom-page-title custom-page-title-editable">
		<div class="<?php echo (defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()) ? 'container' : 'fullwidth-content'); ?> custom-page-title-content">
			<?php
				while ( have_posts() ) : the_post();
					if(!(defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()))) {
						global $post;
						$post->post_content = str_replace(array('[vc_row ', '[vc_row]'), array('[vc_row template_fw="1" ', '[vc_row template_fw="1"]'), $post->post_content);
						setup_postdata($GLOBALS['post'] =& $post);
					}
					the_content();
				endwhile;
			?>
		</div>
	</div>
	<div class="block-content">
		<div class="container">
			<div class="panel row">
				<div class="col-xs-12">
					<p><?php _e('Content area. This is a dummy page content for custom title builder. This content will not be displayed anywhere, it serves for testing purposes only to showcase the appearance of your custom title template on some page.', 'thegem'); ?></p>
				</div>
			</div>
		</div><!-- .container -->
	</div><!-- .block-content -->
</div><!-- #main-content -->

<?php
get_footer();