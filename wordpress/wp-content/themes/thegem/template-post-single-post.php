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
    .template-post-empty-output:before {
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
    .template-post-empty-output.thegem-te-post-title:before {
        content: "\e65e";
    }
    .template-post-empty-output.thegem-te-post-excerpt:before {
        content: "\e666";
    }
    .template-post-empty-output.thegem-te-post-breadcrumbs:before {
        content: "\e660";
    }
    .template-post-empty-output.thegem-te-post-featured-image:before {
        content: "\e661";
    }
    .template-post-empty-output.thegem-te-post-comments:before {
        content: "\e662";
    }
    .template-post-empty-output.thegem-te-post-content:before {
        content: "\e663";
    }
    .template-post-empty-output.thegem-te-post-author:before {
        content: "\e664";
    }
    .template-post-empty-output.thegem-te-post-info:before {
        content: "\e665";
    }
    .template-post-empty-output.thegem-te-post-featured-content:before {
        content: "\e667";
    }
    .template-post-empty-output.thegem-te-post-navigation:before {
        content: "\e668";
    }
    .template-post-empty-output.thegem-te-post-tags:before {
        content: "\e651";
    }
</style>
<div id="main-content" class="main-content">
	<div class="block-content">
		<div class="<?php echo (defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()) ? 'container' : 'fullwidth-content'); ?>">
			<div class="thegem-template-wrapper thegem-template-single-post thegem-template-<?php the_ID(); ?>">
				<?php
					while ( have_posts() ) : the_post();
						if(!(defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()))) {
							global $post;
							$post->post_content = str_replace(array('[vc_row ', '[vc_row]', '[vc_section ', '[vc_section]'), array('[vc_row template_fw="1" ', '[vc_row template_fw="1"]','[vc_section template_fw="1" ', '[vc_section template_fw="1"]'), $post->post_content);
							setup_postdata($GLOBALS['post'] =& $post);
						}
						$GLOBALS['thegem_template_type'] = 'single-post';
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
