<?php

get_header(); ?>

<div id="main-content" class="main-content">

<?php
	$thegem_output_settings = thegem_get_output_page_settings(0, thegem_theme_options_get_page_settings('search'), 'search');
	$thegem_page_data = array(
		'title' => $thegem_output_settings,
		'effects' => $thegem_output_settings,
		'slideshow' => $thegem_output_settings,
		'sidebar' => $thegem_output_settings,
	);
	/*if($thegem_page_data['effects']['effects_page_scroller']) {
		wp_enqueue_script('thegem-page-scroller');
		$thegem_page_data['effects']['effects_no_bottom_margin'] = true;
		$thegem_page_data['effects']['effects_no_top_margin'] = true;
	}*/
	$thegem_no_margins_block = '';
	if($thegem_page_data['effects']['effects_no_bottom_margin']) {
		$thegem_no_margins_block .= ' no-bottom-margin';
	}
	if($thegem_page_data['effects']['effects_no_top_margin']) {
		$thegem_no_margins_block .= ' no-top-margin';
	}

	$thegem_panel_classes = array('panel', 'row');
	$thegem_center_classes = 'panel-center';
	$thegem_sidebar_classes = '';
	if(is_active_sidebar('page-sidebar') && $thegem_page_data['sidebar']['sidebar_show'] && $thegem_page_data['sidebar']['sidebar_position']) {
		$thegem_panel_classes[] = 'panel-sidebar-position-'.$thegem_page_data['sidebar']['sidebar_position'];
		$thegem_panel_classes[] = 'with-sidebar';
		$thegem_center_classes .= ' col-lg-9 col-md-9 col-sm-12';
		if($thegem_page_data['sidebar']['sidebar_position'] == 'left') {
			$thegem_center_classes .= ' col-md-push-3 col-sm-push-0';
			$thegem_sidebar_classes .= ' col-md-pull-9 col-sm-pull-0';
		}
	} else {
		$thegem_center_classes .= ' col-xs-12';
	}
	if($thegem_page_data['sidebar']['sidebar_sticky']) {
		$thegem_panel_classes[] = 'panel-sidebar-sticky';
		wp_enqueue_script('thegem-sticky');
	}
	if($thegem_page_data['title']['title_show'] && $thegem_page_data['title']['title_style'] == 3 && $thegem_page_data['slideshow']['slideshow_type']) {
		thegem_slideshow_block(array('slideshow_type' => $thegem_page_data['slideshow']['slideshow_type'], 'slideshow' => $thegem_page_data['slideshow']['slideshow_slideshow'], 'lslider' => $thegem_page_data['slideshow']['slideshow_layerslider'], 'slider' => $thegem_page_data['slideshow']['slideshow_revslider'], 'preloader' => !empty($thegem_page_data['slideshow']['slideshow_preloader'])));
	}
	echo thegem_page_title();
?>

	<div class="block-content<?php echo esc_attr($thegem_no_margins_block); ?>">
		<div class="container">
			<div class="<?php echo esc_attr(implode(' ', $thegem_panel_classes)); ?>">
				<div class="<?php echo esc_attr($thegem_center_classes); ?>">
				<?php
					if ( have_posts() ) {

						if (thegem_get_option('search_layout_type') == 'grid') {
							thegem_search_grid_content();
						} else {
							if(!is_singular()) {
								$blog_style = '3x';
								$params = array(
									'hide_author' => false,
									'hide_date' => true,
									'hide_comments' => true,
									'hide_likes' => true,
									'hide_social_sharing' => true
								);
								wp_enqueue_style('thegem-blog');
								wp_enqueue_style('thegem-additional-blog');
								wp_enqueue_style('thegem-animations');
								wp_enqueue_script('thegem-blog-isotope');
								echo '<div class="preloader"><div class="preloader-spin"></div></div>';
								echo '<div class="blog blog-style-3x blog-style-masonry">';
							}

							while ( have_posts() ) : the_post();

								include(locate_template(array('gem-templates/blog/content-blog-item-masonry.php', 'content-blog-item.php')));

							endwhile;

							if(!is_singular()) { echo '</div>'; thegem_pagination(); }
						}

					} else {
						get_template_part( 'content', 'none' );
					}
				?>
				</div>
				<?php
					if(is_active_sidebar('page-sidebar') && $thegem_page_data['sidebar']['sidebar_show'] && !empty($thegem_page_data['sidebar']['sidebar_position'])) {
						echo '<div class="sidebar col-lg-3 col-md-3 col-sm-12'.esc_attr($thegem_sidebar_classes).'" role="complementary">';
						get_sidebar('page');
						echo '</div><!-- .sidebar -->';
					}
				?>
			</div>
		</div><!-- .container -->
	</div><!-- .block-content -->
</div><!-- #main-content -->

<?php
get_footer();
