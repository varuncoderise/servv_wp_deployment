<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}
$thegem_checkout_template = thegem_checkout_template();
$thegem_use_template = empty($thegem_checkout_template) ? false : get_post($thegem_checkout_template);

?>
<?php if (!$thegem_use_template) : ?>
<div class="checkout-notice checkout-coupon-notice">
	<?php echo apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'woocommerce' ) . ' <a href="#" class="showcoupon">' . esc_html__( 'Click here to enter your code', 'woocommerce' ) . '</a>' ); ?>
</div>
<?php endif; ?>

<?php if ($thegem_use_template) : ?><div id="checkout-coupon-popup" class="woocommerce" style="display: none;"><div class="checkout-coupon"><?php endif; ?>
<form class="checkout_coupon woocommerce-form-coupon" method="post"<?php echo ($thegem_use_template ? '' : ' style="display:none"'); ?>>

	<input type="text" name="coupon_code" class="input-text coupon-code" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" />

	<?php
		thegem_button(array(
			'tag' => 'button',
			'text' => esc_html__( 'Apply coupon', 'woocommerce' ),
			'style' => 'outline',
			'size' => 'small',
			'attributes' => array(
				'name' => 'apply_coupon',
				'value' => esc_attr__( 'Apply coupon', 'woocommerce' ),
				'type' => 'submit',
			)
		), true);
	?>

	<div class="clear"></div>
</form>
<?php if ($thegem_use_template) : ?></div></div><?php endif; ?>
