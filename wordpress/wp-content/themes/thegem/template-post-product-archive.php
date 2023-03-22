<?php
get_header(); ?>
<style>
@font-face {
	font-family: 'thegem-shortcodes';
	src:url('<?php echo THEGEM_THEME_URI; ?>/fonts/thegem-shortcodes.eot');
	src:url('<?php echo THEGEM_THEME_URI; ?>/fonts/thegem-shortcodes.eot?#iefix') format('embedded-opentype'),
		url('<?php echo THEGEM_THEME_URI; ?>/fonts/thegem-shortcodes.woff') format('woff'),
		url('<?php echo THEGEM_THEME_URI; ?>/fonts/thegem-shortcodes.ttf') format('truetype'),
		url('<?php echo THEGEM_THEME_URI; ?>/fonts/thegem-shortcodes.svg#thegem-shortcodes') format('svg');
	font-weight: normal;
	font-style: normal;
}
.template-product-empty-output:before {
	font-family: 'thegem-shortcodes';
	font-weight: normal;
	font-style: normal;
	font-size: 24px;
	line-height: 1;
	width: 24px;
	text-align: center;
	display: inline-block;
	vertical-align: top;
	margin-right: 5px;
}
.template-product-empty-output.thegem-te-product-archive-title:before {
    content: "\e643";
}
.template-product-empty-output.thegem-te-product-archive-description:before {
    content: "\e642";
}
.template-product-empty-output.thegem-te-product-archive-breadcrumbs:before {
    content: "\e645";
}
</style>
<div id="main-content" class="main-content">
	<div class="block-content">
		<div class="<?php echo (defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()) ? 'container' : 'fullwidth-content'); ?>">
			<div class="thegem-template-wrapper thegem-template-product-archive thegem-template-<?php the_ID(); ?>">
				<?php
					while ( have_posts() ) : the_post();
						if(!(defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()))) {
							global $post;
							$post->post_content = str_replace(array('[vc_row ', '[vc_row]'), array('[vc_row template_fw="1" ', '[vc_row template_fw="1"]'), $post->post_content);
							setup_postdata($GLOBALS['post'] =& $post);
						}
						$GLOBALS['thegem_template_type'] = 'product-archive';
						the_content();
						unset($GLOBALS['thegem_template_type']);
					endwhile;
				?>
			</div>
		</div>
	</div><!-- .block-content -->
</div><!-- #main-content -->

<?php
get_footer();
