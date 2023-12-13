<?php
/**
 * Template Name: Woocommerce
 * The Woocommerce template file
 * @package TheGem
 */

	if (isset($_GET['thegem_products_ajax'])) {
		remove_all_actions('woocommerce_before_shop_loop');
		remove_all_actions('woocommerce_after_shop_loop');
		remove_all_actions('woocommerce_archive_description');

		echo '<div data-paged="' . get_query_var( 'paged' ) . '">';
		woocommerce_content();
		echo '</div>';
		exit;
	}

	$archive_template_id = thegem_archive_product_template();
	$thegem_grid_type = thegem_get_option('product_archive_type');

	$thegem_item_data = array(
		'sidebar_position' => '',
		'sidebar_sticky' => '',
		'effects_no_bottom_margin' => 0,
		'effects_no_top_margin' => 0,
		'slideshow_type' => '',
		'slideshow_slideshow' => '',
		'slideshow_layerslider' => '',
		'slideshow_revslider' => '',
	);
	$thegem_page_id = wc_get_page_id('shop');

	if(is_product()) {
		$thegem_page_id = get_the_ID();

		$GLOBALS['thegem_product_data'] = thegem_get_output_product_page_data($thegem_page_id);
		$thegem_product_data = $GLOBALS['thegem_product_data'];
	}

	$thegem_item_data = thegem_get_output_page_settings($thegem_page_id);
	if (!is_singular( 'product' ) && !$archive_template_id && $thegem_grid_type !== 'legacy') {
		$thegem_item_data = thegem_get_output_page_settings($thegem_page_id, array(), 'product_category');
	}
	if(is_tax()) {
		if (!$archive_template_id && $thegem_grid_type == 'legacy') {
			$thegem_item_data = thegem_get_output_page_settings(0, array(), 'product_category');
		}
		$thegem_term_id = get_queried_object()->term_id;
		$thegem_item_data = thegem_get_output_page_settings($thegem_term_id, array(), 'term');
	}

	$thegem_sidebar_sticky = $thegem_item_data['sidebar_sticky'] ? 1 : 0;
	$thegem_sidebar_position = thegem_check_array_value(array('', 'left', 'right'), $thegem_item_data['sidebar_position'], '');
	$thegem_panel_classes = array('panel', 'row');
	$thegem_center_classes = 'panel-center';
	$thegem_sidebar_classes = '';

	if(is_active_sidebar('shop-sidebar') && $thegem_item_data['sidebar_show'] && $thegem_sidebar_position && (is_singular( 'product' ) || $thegem_grid_type == 'legacy') && !$archive_template_id ) {
		$thegem_panel_classes[] = 'panel-sidebar-position-'.$thegem_sidebar_position;
		$thegem_panel_classes[] = 'with-sidebar';
		$thegem_center_classes .= ' col-lg-9 col-md-9 col-sm-12';
		if($thegem_sidebar_position == 'left') {
			$thegem_center_classes .= ' col-md-push-3 col-sm-push-0';
			$thegem_sidebar_classes .= ' col-md-pull-9 col-sm-pull-0';
		}
	} else {
		$thegem_center_classes .= ' col-xs-12';
		if ($thegem_item_data['sidebar_show']) {
			$thegem_center_classes .= ' panel-sidebar-position-'.$thegem_sidebar_position;
		}
	}

	if (isset($_GET['ajax_filters'])) {
		if (thegem_get_option('product_archive_filters_type') == 'normal') {
			unregister_widget( 'WC_Widget_Layered_Nav' );
			unregister_widget( 'WC_Widget_Price_Filter' );
			unregister_widget( 'WC_Widget_Product_Categories' );
			unregister_widget( 'WC_Widget_Product_Search' );
		}
		if ($archive_template_id) {
			$template = get_post($archive_template_id);
			$template->post_content = str_replace(array('<p>[', ']</p>'), array('[', ']'), $template->post_content);
			$template->post_content = str_replace(array('[vc_row ', '[vc_row]'), array('[vc_row template_fw="1" ', '[vc_row template_fw="1"]'), $template->post_content);
			$GLOBALS['thegem_template_type'] = 'product-archive';
			echo do_shortcode($template->post_content);
			unset($GLOBALS['thegem_template_type']);
		} else {
			thegem_woocommerce_grid_content(is_active_sidebar('shop-sidebar') && $thegem_item_data['sidebar_show'] && $thegem_sidebar_position, $thegem_sidebar_sticky);
		}
		exit;
	}

	get_header();

	if (!$archive_template_id && $thegem_grid_type !== 'legacy') {
		$thegem_sidebar_classes .= ' portfolio-filters-list native style-sidebar';
		if (!empty(thegem_get_option('categories_collapsible'))) {
			$thegem_sidebar_classes .= ' categories-widget-collapsible';
		} ?>
		<script>
			(function ($) {
				$(document).ready(function () {
					$('.portfolio-filters-list .widget_layered_nav, .portfolio-filters-list .widget_product_categories').find('.count').each(function () {
						$(this).html($(this).html().replace('(', '').replace(')', '')).css('opacity', 1);
					});
					if ($('.widget_product_categories').length && $('.portfolio-filters-list').hasClass('categories-widget-collapsible')) {
						$('<span class="filters-collapsible-arrow"></span>').insertBefore('.widget_product_categories ul.children');

						$('.cat-parent:not(.current-cat-parent, .current-cat)').addClass('collapsed').find('ul').css('display', 'none');

						$('.portfolio-filters-list.native .filters-collapsible-arrow').on('click', function (e) {
							e.preventDefault();
							e.stopPropagation();
							$(this).parent().toggleClass('collapsed');
							$(this).next().slideToggle('slow');
						});

						$('.portfolio-filters-list.categories-widget-collapsible').addClass('collapse-inited');
					}
				});
			})(jQuery);
		</script>
		<?php
	}
	if($thegem_sidebar_sticky) {
		$thegem_panel_classes[] = 'panel-sidebar-sticky';
		wp_enqueue_script('thegem-sticky');
	}
?>
<div id="main-content" class="main-content">
<?php
	if($thegem_item_data['title_show'] && $thegem_item_data['title_style'] == 3 && $thegem_item_data['slideshow_type'] && !is_search()) {
		thegem_slideshow_block(array('slideshow_type' => $thegem_item_data['slideshow_type'], 'slideshow' => $thegem_item_data['slideshow_slideshow'], 'lslider' => $thegem_item_data['slideshow_layerslider'], 'slider' => $thegem_item_data['slideshow_revslider'], 'preloader' => !empty($thegem_item_data['slideshow_preloader'])));
	}
	$isGridGalleryHideGap = '';
	if (is_singular( 'product' )){
		$isGridGallery = $thegem_product_data['product_gallery_type'] == 'grid';
		$isGridGalleryHideGap = $isGridGallery && $thegem_product_data['product_gallery_grid_gaps_hide'] ? $thegem_product_data['product_gallery_grid_gaps_hide'] : false;
	}
	$content_container_class = 'container';
	if(is_product()) {
		if(thegem_single_product_template()) {
			$content_container_class = 'fullwidth-content';
		} elseif($thegem_product_data['product_page_layout'] != 'legacy' && $thegem_product_data['product_page_layout_fullwidth']) {
			$content_container_class = 'container-fullwidth container-offset';
		}
	} else {
		if ($archive_template_id) {
			$content_container_class = 'fullwidth-content';
		} else {
			if($thegem_grid_type !== 'legacy' && strpos(thegem_get_option('product_archive_content_width'), 'fullwidth') !== false) {
				if(thegem_get_option('product_archive_content_width') === 'fullwidth-nogaps') {
					$content_container_class = 'fullwidth-content';
				} else {
					$content_container_class = 'container-fullwidth';
				}
			}
		}
	}
?>
	<?= thegem_page_title() ?>
	<div class="block-content">
		<div class="<?= $content_container_class ?>">
			<?php if($product_template_id = thegem_single_product_template()) : ?>
				<div class="thegem-template-wrapper thegem-template-single-product thegem-template-<?php echo esc_attr($product_template_id); ?> product">
					<?php
						$template_custom_css = get_post_meta($product_template_id, '_wpb_shortcodes_custom_css', true) . get_post_meta($product_template_id, '_wpb_post_custom_css', true);
						if($template_custom_css) {
							echo '<style>' . $template_custom_css . '</style>';
						}
						$template = get_post($product_template_id);
						$template->post_content = str_replace(array('<p>[', ']</p>'), array('[', ']'), $template->post_content);
						$template->post_content = str_replace(array('[vc_row ', '[vc_row]', '[vc_column ', '[vc_column]', '[vc_column_inner'), array('[vc_row template_fw="1" ', '[vc_row template_fw="1"]', '[vc_column template_flex="1" ', '[vc_column template_flex="1"]', '[vc_column_inner template_flex="1"'), $template->post_content);
						$GLOBALS['thegem_template_type'] = 'single-product';
						echo do_shortcode($template->post_content);
						unset($GLOBALS['thegem_template_type']);
					?>
				</div>
			<?php elseif ($archive_template_id) : ?>
				<div class="thegem-template-wrapper thegem-template-product-archive thegem-template-<?php $archive_template_id; ?>">
					<?php
					$template_custom_css = get_post_meta($archive_template_id, '_wpb_shortcodes_custom_css', true) . get_post_meta($archive_template_id, '_wpb_post_custom_css', true);
					if($template_custom_css) {
						echo '<style>' . $template_custom_css . '</style>';
					}
					$template = get_post($archive_template_id);
					$template->post_content = str_replace(array('<p>[', ']</p>'), array('[', ']'), $template->post_content);
					$template->post_content = str_replace(array('[vc_row ', '[vc_row]'), array('[vc_row template_fw="1" ', '[vc_row template_fw="1"]'), $template->post_content);
					$GLOBALS['thegem_template_type'] = 'product-archive';
					echo do_shortcode($template->post_content);
					unset($GLOBALS['thegem_template_type']);
					?>
				</div>
			<?php else : ?>
				<?php if (!is_post_type_archive('product') && $thegem_item_data['page_layout_breadcrumbs'] && !$isGridGalleryHideGap) : $bottomSpacing = $thegem_item_data['page_layout_breadcrumbs_bottom_spacing'];?>
					<div class="page-breadcrumbs page-breadcrumbs--<?=$thegem_item_data['page_layout_breadcrumbs_alignment']?>" <?php if ($bottomSpacing) : ?>style="margin-bottom: <?=esc_attr($bottomSpacing).'px'?>"<?php endif; ?>>
						<?= gem_breadcrumbs(true) ?>
					</div>
				<?php endif; ?>

				<div class="<?php echo esc_attr(implode(' ', $thegem_panel_classes)); ?>">
					<div class="<?php echo esc_attr($thegem_center_classes); ?>">
						<?php
						if (!is_singular( 'product' ) && $thegem_grid_type !== 'legacy') {
							if (thegem_get_option('product_archive_filters_type') == 'normal') {
								unregister_widget( 'WC_Widget_Layered_Nav' );
								unregister_widget( 'WC_Widget_Price_Filter' );
								unregister_widget( 'WC_Widget_Product_Categories' );
								unregister_widget( 'WC_Widget_Product_Search' );
							}
							thegem_woocommerce_grid_content(is_active_sidebar('shop-sidebar') && $thegem_item_data['sidebar_show'] && $thegem_sidebar_position, $thegem_sidebar_sticky);
						} else {
							woocommerce_content();
						}
						if (is_search() && thegem_get_option('search_layout_type') == 'grid' && count(thegem_get_search_post_types_array(true)) > 0) {
							thegem_search_grid_content(true);
						}
						?>
					</div>

					<?php
						if(is_active_sidebar('shop-sidebar') && $thegem_item_data['sidebar_show'] && $thegem_sidebar_position && (is_singular( 'product' ) || $thegem_grid_type == 'legacy')) {
							echo '<div class="sidebar col-lg-3 col-md-3 col-sm-12'.esc_attr($thegem_sidebar_classes).' '.esc_attr($thegem_sidebar_position).'" role="complementary"><div class="widget-area-wrap">';
							get_sidebar('shop');
							echo '</div></div><!-- .sidebar -->';
						}
					?>
				</div>

				<?php if(is_product()) {
					do_action( 'woocommerce_after_single_product_summary' );
					do_action( 'woocommerce_after_single_product' );
				} ?>
			<?php endif; ?>
		</div>
	</div>
	<?php get_sidebar('shop-bottom'); ?>
</div><!-- #main-content -->
<?php
get_footer();
