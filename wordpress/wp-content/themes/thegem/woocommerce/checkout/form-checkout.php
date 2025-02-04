<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$thegem_checkout_type = thegem_checkout_get_type();
$checkout_form_class = 'woocommerce-checkout-'.$thegem_checkout_type;
if($thegem_checkout_type == 'one-page-modern') {
	$checkout_form_class .= ' woocommerce-checkout-one-page';
}
$thegem_checkout_template = thegem_checkout_template();
if($thegem_checkout_template) {
	$checkout_form_class .= ' woocommerce-checkout-with-template';
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout <?php echo esc_attr($checkout_form_class); ?>" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

<?php

if($thegem_checkout_template) :
	$template_custom_css = get_post_meta($thegem_checkout_template, '_wpb_shortcodes_custom_css', true) . get_post_meta($thegem_checkout_template, '_wpb_post_custom_css', true);
	if($template_custom_css) {
		echo '<style>' . $template_custom_css . '</style>';
	}
	$template = get_post($thegem_checkout_template);
	$template->post_content = str_replace(array('<p>[', ']</p>'), array('[', ']'), $template->post_content);
	$GLOBALS['thegem_template_type'] = 'checkout';
	echo '<div class="thegem-template-wrapper thegem-template-checkout thegem-template-'.$thegem_checkout_template.'">';
	echo do_shortcode($template->post_content);
	echo '</div>';
	unset($GLOBALS['thegem_template_type']);
else :

?>

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set row" id="customer_details">
			<div class="col-1 col-sm-6 col-xs-12">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2 col-sm-6 col-xs-12">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>

	<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

	<<?php echo ($thegem_checkout_type == 'one-page-modern' ? 'h3' : 'h2'); ?> id="order_review_heading" class="light"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></<?php echo ($thegem_checkout_type == 'one-page-modern' ? 'h3' : 'h2');?>>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

	<?php if($thegem_checkout_type == 'one-page-modern') : ?>
	<div class="clear"></div>
	<?php endif; ?>
<?php endif; ?>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
