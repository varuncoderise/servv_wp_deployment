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
.template-product-empty-output.thegem-te-product-extra-description:before {
	content: "\e63e";
}
.template-product-empty-output.thegem-te-product-categories:before {
	content: "\e63f";
}
.template-product-empty-output.thegem-te-product-content:before {
	content: "\e640";
}
.template-product-empty-output.thegem-te-product-meta-value:before {
	content: "\e641";
}
.template-product-empty-output.thegem-te-product-description:before {
	content: "\e642";
}
.template-product-empty-output.thegem-te-product-title:before {
	content: "\e643";
}
.template-product-empty-output.thegem-te-product-tabs:before {
	content: "\e644";
}
.template-product-empty-output.thegem-te-product-breadcrumbs:before {
	content: "\e645";
}
.template-product-empty-output.thegem-te-product-navigation:before {
	content: "\e646";
}
.template-product-empty-output.thegem-te-product-gallery:before {
	content: "";
}
.template-product-empty-output.thegem-te-product-gallery {
	font-size: 0;
	line-height: 0;
	height: 0;
	width: 100%;
	padding-bottom: 125%;
	background: url('<?php echo THEGEM_THEME_URI; ?>/images/dummy.png') no-repeat 0% 50%;
	background-size: cover;
}
.template-product-empty-output.thegem-te-product-price:before {
	content: "\e648";
}
.template-product-empty-output.thegem-te-product-rating:before {
	content: "\e649";
}
.template-product-empty-output.thegem-te-product-add-to-wishlist:before {
	content: "\e64a";
}
.template-product-empty-output.thegem-te-product-additional-info:before {
	content: "\e64b";
}
.template-product-empty-output.thegem-te-product-reviews:before {
	content: "\e64c";
}
.template-product-empty-output.thegem-te-product-sharing:before {
	content: "\e64d";
}
.template-product-empty-output.thegem-te-product-sku:before {
	content: "\e64e";
}
.template-product-empty-output.thegem-te-product-add-to-cart:before {
	content: "\e64f";
}
.template-product-empty-output.thegem-te-product-attribute:before {
	content: "\e650";
}
.template-product-empty-output.thegem-te-product-tags:before {
	content: "\e651";
}
.template-product-empty-output.thegem-te-product-size-guide:before {
	content: "\e652";
}
</style>
<div id="main-content" class="main-content">
	<div class="block-content">
		<div class="<?php echo (defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()) ? 'container' : 'fullwidth-content'); ?>">
			<div class="thegem-template-wrapper thegem-template-single-product thegem-template-<?php the_ID(); ?>">
				<?php
					while ( have_posts() ) : the_post();
						if(!(defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()))) {
							global $post;
							$post->post_content = str_replace(array('[vc_row ', '[vc_row]', '[vc_column ', '[vc_column]', '[vc_column_inner'), array('[vc_row template_fw="1" ', '[vc_row template_fw="1"]', '[vc_column template_flex="1" ', '[vc_column template_flex="1"]', '[vc_column_inner template_flex="1"'), $post->post_content);
							setup_postdata($GLOBALS['post'] =& $post);
						}
						$GLOBALS['thegem_template_type'] = 'single-product';
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