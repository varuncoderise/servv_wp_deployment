<?php
get_header();
$preview_width = '400px';
$preview_settings = get_post_meta(get_the_ID(), 'thegem_template_preview_settings', true);
if(!empty($preview_settings)) {
	$preview_settings_width = isset($preview_settings['demo_width']) ? $preview_settings['demo_width'] : '';
	if($preview_settings_width !== '') {
		$preview_width_is_percet = substr($preview_settings_width, -1) === '%';
		$preview_settings_width = floatval($preview_settings_width) . ($preview_width_is_percet ? '%' : 'px');
		$preview_width = $preview_settings_width;
	}
}
?>
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
	.thegem-template-wrapper {
		width: <?= $preview_width; ?>;
		max-width: 100%;
		margin: 0 auto;
	}
	#vc_no-content-helper.vc_not-empty {
		display: none;
	}
</style>
<div id="main-content" class="main-content">
	<div class="block-content">
		<div class="<?php echo (defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()) ? 'container' : 'fullwidth-content'); ?>">
			<div class="thegem-template-wrapper thegem-template-loop-item thegem-template-<?php the_ID(); ?>">
				<?php
					while ( have_posts() ) : the_post();
						if(!(defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()))) {
							global $post;
							setup_postdata($GLOBALS['post'] =& $post);
						}
						$thegem_template_type_outer = isset($GLOBALS['thegem_template_type']) ? $GLOBALS['thegem_template_type'] : '';
						$GLOBALS['thegem_template_type'] = 'loop-item';
						the_content();
						unset($GLOBALS['thegem_template_type']);
						if (!empty($thegem_template_type_outer)) {
							$GLOBALS['thegem_template_type'] = $thegem_template_type_outer;
						}
					endwhile;
				?>
			</div>
		</div>
	</div><!-- .block-content -->
</div><!-- #main-content -->

<?php
get_footer();
