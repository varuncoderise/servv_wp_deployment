<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

$thegem_cart_empty_template = thegem_cart_empty_template();
if($thegem_cart_empty_template) :
	thegem_woocommerce_cart_scripts();
	$template_custom_css = get_post_meta($thegem_cart_empty_template, '_wpb_shortcodes_custom_css', true) . get_post_meta($thegem_cart_empty_template, '_wpb_post_custom_css', true);
	if($template_custom_css) {
		echo '<style>' . $template_custom_css . '</style>';
	}
	$template = get_post($thegem_cart_empty_template);
	$GLOBALS['thegem_template_type'] = 'cart-empty';
	echo '<div class="wc-empty-cart-message"></div><div class="cart-empty"><div class="thegem-template-wrapper thegem-template-cart-empty thegem-template-'.$thegem_cart_empty_template.'">';
	echo do_shortcode($template->post_content);
	echo '</div></div>';
	unset($GLOBALS['thegem_template_type']);
else :

do_action( 'woocommerce_cart_is_empty' );

if(thegem_get_option('cart_layout', 'modern') == 'modern') : ?>
<div class="wc-empty-cart-message"></div>
<div class="woocommerce-empty-cart cart-empty centered-box">
	<div class="woocommerce-empty-cart__title title-h2"><?php echo wp_kses_post(apply_filters( 'wc_empty_cart_message', __( 'Your cart is currently empty.', 'woocommerce' ) )); ?></div>
	<?php if(thegem_get_option('cart_empty_text')) : ?>
		<div class="woocommerce-empty-cart__text"><?php echo wp_kses_post( nl2br(thegem_get_option('cart_empty_text')) ); ?></div>
	<?php endif; ?>
	<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
		<div class="woocommerce-empty-cart__button">
			<?php
				thegem_button(array(
					'tag' => 'a',
					'href' => esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ),
					'position' => 'center',
					'text' => esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'Return to shop', 'woocommerce' ) ) ),
					'style' => 'outline',
					'size' => 'small',
					'extra_class' => 'return-to-shop',
					'attributes' => array(
							'class' => 'button wc-backward'
					)
				), true);
			?>
		</div>
	<?php endif; ?>
</div>
<?php else :
if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
	<p class="return-to-shop">
		<a class="button wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'Return to shop', 'woocommerce' ) ) ); ?>
		</a>
	</p>
<?php endif;
endif;
endif;
