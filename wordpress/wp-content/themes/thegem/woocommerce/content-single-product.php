<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 		https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product, $thegem_product_data;

$page_data = thegem_get_output_page_settings(get_the_ID());
$left_classes = 'col-sm-6 col-xs-12';
$right_classes = 'col-sm-6 col-xs-12';
if(is_active_sidebar('shop-sidebar') && $page_data['sidebar_show']) {
	$left_classes = 'col-sm-5 col-xs-12';
	$right_classes = 'col-sm-7 col-xs-12';
}

if($thegem_product_data['product_gallery'] == 'disabled') {
	$right_classes = 'col-xs-12';
}

$left_column_width_preset = 0;
switch ($thegem_product_data['product_page_layout_preset']) {
	case 'col-40-60':
		$left_column_width_preset = 40;
		break;
	case 'col-50-50':
		$left_column_width_preset = 50;
		break;
	case 'col-60-40':
		$left_column_width_preset = 60;
		break;
	case 'col-100':
		$left_column_width_preset = 100;
		break;
}

$left_column_style = '';
$right_column_style = '';
$left_column_class = 'product-page__left-column';
$bottom_column_class = 'product-page__bottom-column';
$right_column_class = 'product-page__right-column';
$right_column_centered_class = 'product-page__right-column-centered';
$isLegacy = $thegem_product_data['product_page_layout'] == 'legacy';
$isSticky = $thegem_product_data['product_page_layout_sticky'];
$stickyOffset = $thegem_product_data['product_page_layout_sticky_offset'];
$isGalleryNative = $thegem_product_data['product_gallery'] == 'native';
$isGalleryDisabled = $thegem_product_data['product_gallery'] == 'disabled';
$isAccordion = $thegem_product_data['product_page_desc_review_layout'] == 'accordion';
$isAccordionNextToGallery = $thegem_product_data['product_page_desc_review_layout_acc_position'] == 'next_to_gallery';
$isOneByOne = $thegem_product_data['product_page_desc_review_layout'] == 'one_by_one';
$isReview = $thegem_product_data['product_page_desc_review_reviews'];
$isFullWidth = $thegem_product_data['product_page_layout_fullwidth'];
$isAjaxLoad = thegem_get_option('product_page_ajax_add_to_cart');
$reviewLayout = $thegem_product_data['product_page_desc_review_layout'];
$isGridGallery = $thegem_product_data['product_gallery_type'] == 'grid';
$isGridGalleryNoGaps = $thegem_product_data['product_gallery_grid_gaps_hide'];
$isBreadcrumbs = $page_data['page_layout_breadcrumbs'];
$isHeaderTransparent = $page_data['header_transparent'];
$isCenteredMode = $thegem_product_data['product_page_layout_centered'];
$centeredModeClass = $isFullWidth ? 'centered-mode centered-mode--full' : 'centered-mode';
$centeredModeGap = $thegem_product_data['product_page_layout_centered_top_margin'];
$isCenteredModeBoxed = $thegem_product_data['product_page_layout_centered_boxed'];
$isCenteredModeBoxedBackground = $thegem_product_data['product_page_layout_centered_boxed_color'];
$isLayoutBackground = $thegem_product_data['product_page_layout_background'] != '';
$layoutBackgroundColor = $thegem_product_data['product_page_layout_background'];
$isMenuVertical = thegem_get_option('header_layout') == 'vertical';

if (!$isLegacy) {
	$left_column_width_dynamic = $thegem_product_data['product_gallery_column_width'];
	$left_column_width = ($left_column_width_preset === $left_column_width_dynamic) ? $left_column_width_preset : $left_column_width_dynamic;
	$left_column_position = $thegem_product_data['product_gallery_column_position'];
	$left_column_order = ($thegem_product_data['product_gallery_column_position'] == 'left') ? '0' : '1';
	$left_column_style = 'width: '.esc_attr($left_column_width.'%').'; float: '.esc_attr($left_column_position).'; order: '.esc_attr($left_column_order).';' ;

	$right_column_width = 100 - $left_column_width.'%';
	$right_column_position = ($left_column_position == 'left') ? 'right' : 'left';
	$right_column_order = ($left_column_order == '0') ? '1' : '0';
	$right_column_padding = (!wp_is_mobile() && $isGridGallery) ? $thegem_product_data['product_gallery_grid_top_margin'] : 0;
	$right_column_style = 'width: '.esc_attr($right_column_width != '0%' ? $right_column_width : '100%').'; float: '.esc_attr($right_column_position).'; order: '.esc_attr($right_column_order).'; margin-top: '.esc_attr($right_column_width != '0%' ? '0' : '70px').'; padding-top: '.esc_attr($right_column_padding).'px; z-index: 1;';

	$centeredModeBoxedStyle = 'background-color: '.esc_attr($isCenteredModeBoxedBackground).'; box-shadow: 0 0 5px rgba(0, 0, 0, 0.05); padding: 70px 40px;';
}

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('product-page__wrapper', $product); ?>>

	<div class="single-product-content <?=$isLayoutBackground ? 'single-product-content__back' : null?> row <?=thegem_get_option('catalog_view') ? 'catalog-view' : null?>" data-sticky="<?=$isSticky ? 'yes' : 'no'?>" data-sticky-offset="<?=$stickyOffset?>" data-ajax-load="<?=$isAjaxLoad ? 'yes' : 'no'?>" data-menu-vertical="<?=$isMenuVertical ? 'yes' : 'no'?>">
		<?php if ( $isLayoutBackground ): ?>
			<div class="single-product-content__overlay" style="background-color: <?=$layoutBackgroundColor?>"></div>
		<?php endif; ?>
		<?php if (!$isGalleryDisabled): ?>
			<div class="single-product-content-left <?=$left_classes?> <?=$isGalleryNative ? 'default-gallery' : null?>" <?=$left_column_style ? 'style="'.$left_column_style.'"' : null?>>
				<?php if (!$isLegacy): ?><div class="<?=$left_column_class?>"><?php endif; ?>
					<?php if ($isGalleryNative): ?>
						<?php do_action('woocommerce_before_single_product_summary'); ?>
					<?php else : ?>
						<?php do_action('thegem_woocommerce_single_product_left'); ?>
					<?php endif; ?>
				<?php if (!$isLegacy): ?></div><?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="single-product-content-right <?php echo $right_classes; ?>" <?=$right_column_style && !$isGalleryDisabled ? 'style="'.$right_column_style.'"' : null?>>
			<?php if (!$isLegacy): ?>
				<?php if ($thegem_product_data['product_page_skeleton_loader']): ?>
					<div class="preloader skeleton product-right-column-skeleton <?php if ($isCenteredMode) :?>centered-skeleton<?php endif;?>" <?php if ($isBreadcrumbs && $isGridGallery && $isGridGalleryNoGaps):?>style="margin-top: <?=$isHeaderTransparent ? '40px' : '70px'?>;"<?php endif;?>></div>
				<?php endif; ?>
				<div class="<?=$right_column_class?> <?php if ($isCenteredMode): ?><?=$centeredModeClass?><?php endif;?>" <?php if ($isCenteredMode && $centeredModeGap > 0): ?>style="padding-top: <?=$centeredModeGap?>px;"<?php endif; ?>>
					<?php if ($isCenteredMode): ?><div class="<?=$right_column_centered_class?>" <?php if ($isCenteredModeBoxed && $isCenteredModeBoxedBackground) : ?>style="<?=$centeredModeBoxedStyle?>"<?php endif; ?>><?php endif; ?>
			<?php endif; ?>

			<?php do_action('woocommerce_single_product_summary'); ?>

			<?php if ($isGalleryDisabled): ?>
				<?php do_action('thegem_woocommerce_single_product_left'); ?>
			<?php endif; ?>

			<?php if (!$isLegacy): ?><?php if ($isCenteredMode): ?></div><?php endif; ?></div><?php endif; ?>
		</div>
	</div>

	<div class="single-product-content-bottom <?=!$isLegacy ? $bottom_column_class : null?>" <?php if ($reviewLayout): ?>data-review-layout="<?=$reviewLayout?>"<?php endif; ?>>
		<?php if (!$isLegacy && $isReview && $isAccordion && $isAccordionNextToGallery): ?>
			<?php if (!$isFullWidth): ?>
				<div id="thegem-reviews"><?php comments_template() ?></div>
			<?php endif; ?>
			<?php do_action( 'thegem_woocommerce_single_product_bottom' ); ?>
		<?php else: ?>
			<?php do_action( 'thegem_woocommerce_single_product_bottom' ); ?>
		<?php endif; ?>
	</div>

</div><!-- #product-<?php the_ID(); ?> -->
