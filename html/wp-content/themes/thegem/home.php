<?php

get_header();

$thegem_output_settings = thegem_get_output_page_settings(0, thegem_theme_options_get_page_settings('blog'), 'blog');
$thegem_page_data = array(
	'title' => $thegem_output_settings,
	'effects' => $thegem_output_settings,
	'slideshow' => $thegem_output_settings,
	'sidebar' => $thegem_output_settings
);

$thegem_page_effects = $thegem_page_data['effects'];

$thegem_no_margins_block = '';
if ($thegem_page_effects['effects_no_bottom_margin'] || $thegem_page_effects['content_padding_bottom'] === 0) {
	$thegem_no_margins_block .= ' no-bottom-margin';
}
if ($thegem_page_effects['effects_no_top_margin'] || $thegem_page_effects['content_padding_top'] === 0) {
	$thegem_no_margins_block .= ' no-top-margin';
}

$thegem_panel_classes = array('panel', 'row');
$thegem_center_classes = 'panel-center';
$thegem_sidebar_classes = '';
if (is_active_sidebar('page-sidebar') && $thegem_page_data['sidebar']['sidebar_show'] && $thegem_page_data['sidebar']['sidebar_position']) {
	$thegem_panel_classes[] = 'panel-sidebar-position-' . $thegem_page_data['sidebar']['sidebar_position'];
	$thegem_panel_classes[] = 'with-sidebar';
	$thegem_center_classes .= ' col-lg-9 col-md-9 col-sm-12';
	if ($thegem_page_data['sidebar']['sidebar_position'] == 'left') {
		$thegem_center_classes .= ' col-md-push-3 col-sm-push-0';
		$thegem_sidebar_classes .= ' col-md-pull-9 col-sm-pull-0';
	}
} else {
	$thegem_center_classes .= ' col-xs-12';
}
if ($thegem_page_data['sidebar']['sidebar_sticky']) {
	$thegem_panel_classes[] = 'panel-sidebar-sticky';
	wp_enqueue_script('thegem-sticky');
}

$post_type_name = 'post';

?>

<div id="main-content" class="main-content">

<?php

if(thegem_get_option('home_content_enabled')) :

	thegem_home_content_builder();

else :
	echo thegem_page_title();
	$archive_template_id = thegem_blog_archive_template();
	if ( $archive_template_id && defined('WPB_VC_VERSION') ) { ?>
		<div class="block-content">
			<div class="fullwidth-content">
				<div class="thegem-template-wrapper thegem-template-blog-archive thegem-template-<?php echo esc_attr($archive_template_id); ?>">
					<?php
					$template_custom_css = get_post_meta($archive_template_id, '_wpb_shortcodes_custom_css', true) . get_post_meta($archive_template_id, '_wpb_post_custom_css', true);
					if($template_custom_css) {
						echo '<style>' . $template_custom_css . '</style>';
					}
					$template = get_post($archive_template_id);
					$template->post_content = str_replace(array('<p>[', ']</p>'), array('[', ']'), $template->post_content);
					$template->post_content = str_replace(array('[vc_row ', '[vc_row]', '[vc_column ', '[vc_column]', '[vc_column_inner'), array('[vc_row template_fw="1" ', '[vc_row template_fw="1"]', '[vc_column template_flex="1" ', '[vc_column template_flex="1"]', '[vc_column_inner template_flex="1"'), $template->post_content);
					$GLOBALS['thegem_template_type'] = 'blog-archive';
					echo do_shortcode($template->post_content);
					unset($GLOBALS['thegem_template_type']);
					?>
				</div>
			</div><!-- .container -->
		</div><!-- .block-content -->
	<?php } else {
		include(locate_template('gem-templates/blog/content-blog-content-block.php'));
	}

endif; ?>

</div><!-- #main-content -->

<?php

get_footer();
