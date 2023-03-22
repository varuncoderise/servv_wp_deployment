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
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $thegem_product_data;
$dataLayout = thegem_get_option('product_page_layout');
$isLegacy = thegem_get_option('product_page_layout') === 'legacy';
$left_column_class = 'product-page__left-column';
$right_column_class = 'product-page__right-column';

?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	//do_action( 'woocommerce_before_single_product' );

	if ( post_password_required() ) {
		echo get_the_password_form();
		return;
	}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('product-page__wrapper', $product); ?>>

	<div class="single-product-content <?php if ($isLegacy):?>row<?php endif; ?>" data-layout="<?=$dataLayout?>" data-ajax-load="yes">
		<div class="single-product-content-left <?php if ($isLegacy):?>col-sm-5 col-xs-12<?php endif; ?>">
			<?php if (!$isLegacy): ?><div class="<?=$left_column_class?>"><?php endif; ?>
				<?php do_action('thegem_woocommerce_single_product_quick_view_left'); ?>
			<?php if (!$isLegacy): ?></div><?php endif; ?>
		</div>

		<div class="single-product-content-right <?php if ($isLegacy):?>col-sm-7 col-xs-12<?php endif; ?>">
			<?php if (!$isLegacy): ?><div class="<?=$right_column_class?>"><?php endif; ?>
				<?php do_action('thegem_woocommerce_single_product_quick_view_right'); ?>
			<?php if (!$isLegacy): ?></div><?php endif; ?>
		</div>
	</div>
	
</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action('thegem_woocommerce_single_product_quick_view_bottom'); ?>