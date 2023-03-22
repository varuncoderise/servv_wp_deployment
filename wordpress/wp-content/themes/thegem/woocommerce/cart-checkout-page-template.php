<?php get_header(); ?>

<div id="main-content" class="main-content">

<?php while ( have_posts() ) : the_post(); ?>
<?php
$thegem_panel_classes = array('panel', 'row');
$thegem_center_classes = 'panel-center';
$content_class = ' no-top-margin no-bottom-margin';
if(is_checkout() && is_wc_endpoint_url('order-received') && !thegem_checkout_thanks_template()) {
	$content_class = '';
}
if(is_cart() && WC()->cart->is_empty() && !thegem_cart_empty_template()) {
	$content_class = '';
}
?>

<div class="block-content<?php echo esc_attr($content_class); ?>">
	<div class="container">
		<div class="<?php echo esc_attr(implode(' ', $thegem_panel_classes)); ?>">

			<div class="<?php echo esc_attr($thegem_center_classes); ?>">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content post-content">
						<?php
							if (defined('WC_PLUGIN_FILE')) {
								if(is_cart() && thegem_cart_template()) {
									echo do_shortcode('[woocommerce_cart]');
								}
								if(is_checkout() && thegem_checkout_template()) {
									echo do_shortcode('[woocommerce_checkout]');
								}
							}
						?>
					</div><!-- .entry-content -->
				</article><!-- #post-## -->

			</div>
		</div>

	</div>
</div><!-- .block-content -->

<?php endwhile; ?>

</div><!-- #main-content -->

<?php
get_footer();