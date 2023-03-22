<?php
/**
 * The Template for displaying all single posts.
 *
 * @package dokan
 * @package dokan - 2014 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info   = $store_user->get_shop_info();
$map_location = $store_user->get_location();
$layout       = get_theme_mod( 'store_layout', 'left' );

get_header( 'shop' );

if ( function_exists( 'yoast_breadcrumb' ) ) {
	yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
}
?>
<?php do_action( 'woocommerce_before_main_content' ); ?>

<div class="container"><div class="panel row"><div class="panel-center col-xs-12">
<div class="dokan-store-wrap layout-<?php echo esc_attr( $layout ); ?>">
	<?php if ( 'left' === $layout ) { ?>
		<?php
		dokan_get_template_part(
			'store', 'sidebar', [
				'store_user'   => $store_user,
				'store_info'   => $store_info,
				'map_location' => $map_location,
			]
		);
		?>
	<?php } ?>

	<div id="dokan-primary" class="dokan-single-store">
		<div id="dokan-content" class="store-page-wrap woocommerce" role="main">

			<?php dokan_get_template_part( 'store-header' ); ?>

			<?php do_action( 'dokan_store_profile_frame_after', $store_user->data, $store_info ); ?>

			<?php if ( have_posts() ) { ?>

				<div class="seller-items">

					<?php
						$args = array(
							'columns_desktop' => thegem_get_option('product_archive_columns_desktop'),
							'columns_tablet' => thegem_get_option('product_archive_columns_tablet'),
							'columns_mobile' => thegem_get_option('product_archive_columns_mobile'),
							'columns_100' => thegem_get_option('product_archive_columns_100'),
							'quick_view' => thegem_get_option('product_archive_quick_view')
						);
						$products = array();
						while ( have_posts() ) : the_post();
							$products[] = wc_get_product(get_the_ID());
						endwhile;
						thegem_woocommerce_short_grid_content($products, $args);
					?>

				</div>

				<?php dokan_content_nav( 'nav-below' ); ?>

			<?php } else { ?>

				<p class="dokan-info"><?php esc_html_e( 'No products were found of this vendor!', 'dokan-lite' ); ?></p>

			<?php } ?>
		</div>

	</div><!-- .dokan-single-store -->

	<?php if ( 'right' === $layout ) { ?>
		<?php
		dokan_get_template_part(
			'store', 'sidebar', [
				'store_user'   => $store_user,
				'store_info'   => $store_info,
				'map_location' => $map_location,
			]
		);
		?>
	<?php } ?>
</div><!-- .dokan-store-wrap -->
</div></div></div><!-- .container -->
<?php do_action( 'woocommerce_after_main_content' ); ?>

<?php get_footer( 'shop' ); ?>
