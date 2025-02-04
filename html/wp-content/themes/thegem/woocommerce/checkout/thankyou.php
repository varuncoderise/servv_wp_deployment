<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined('ABSPATH') || exit;

$thegem_checkout_type = thegem_checkout_get_type();

?>

<div class="woocommerce-order">
	<?php if ($order) :

		$thegem_checkout_thanks_template = thegem_checkout_thanks_template();
		if ($thegem_checkout_thanks_template) :
			$template_custom_css = get_post_meta($thegem_checkout_thanks_template, '_wpb_shortcodes_custom_css', true) . get_post_meta($thegem_checkout_thanks_template, '_wpb_post_custom_css', true);
			if ($template_custom_css) {
				echo '<style>' . $template_custom_css . '</style>';
			}
			$template = get_post($thegem_checkout_thanks_template);
			$template->post_content = str_replace(array('<p>[', ']</p>'), array('[', ']'), $template->post_content);
			$GLOBALS['thegem_template_type'] = 'checkout-thanks';
			echo '<div class="thegem-template-wrapper thegem-template-checkout-thanks thegem-template-' . $thegem_checkout_thanks_template . '">';
			echo do_shortcode($template->post_content);
			echo '</div>';
			unset($GLOBALS['thegem_template_type']);
		else :

			do_action('woocommerce_before_thankyou', $order->get_id());
			?>
			<?php echo !empty(thegem_get_option('checkout_thank_you_extra')) ? wp_kses_post(thegem_get_option('checkout_thank_you_extra')) : ''; ?>
			<?php if (thegem_get_option('checkout_thank_you_default')) : ?>

			<?php if ($order->has_status('failed')) : ?>

				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed<?php echo($thegem_checkout_type == 'one-page-modern' ? ' default-background styled-subtitle centered-box' : ''); ?>"><?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?></p>

				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
					<?php if (is_user_logged_in()) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
					<?php endif; ?>
				</p>

			<?php else : ?>

				<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received<?php echo($thegem_checkout_type == 'one-page-modern' ? ' default-background styled-subtitle centered-box' : ''); ?>"><?php echo apply_filters('woocommerce_thankyou_order_received_text', esc_html__('Thank you. Your order has been received.', 'woocommerce'), $order); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

				<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details<?php echo($thegem_checkout_type == 'one-page-modern' ? ' body-small' : ''); ?>">

					<li class="woocommerce-order-overview__order order">
						<?php esc_html_e('Order number:', 'woocommerce'); ?>
						<strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>

					<li class="woocommerce-order-overview__date date">
						<?php esc_html_e('Date:', 'woocommerce'); ?>
						<strong><?php echo wc_format_datetime($order->get_date_created()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>
					
					<?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email()) : ?>
						<li class="woocommerce-order-overview__email email">
							<?php esc_html_e('Email:', 'woocommerce'); ?>
							<strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
						</li>
					<?php endif; ?>

					<li class="woocommerce-order-overview__total total">
						<?php esc_html_e('Total:', 'woocommerce'); ?>
						<strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>
					
					<?php if ($order->get_payment_method_title()) : ?>
						<li class="woocommerce-order-overview__payment-method method">
							<?php esc_html_e('Payment method:', 'woocommerce'); ?>
							<strong><?php echo wp_kses_post($order->get_payment_method_title()); ?></strong>
						</li>
					<?php endif; ?>

				</ul>

			<?php endif; ?>
			
			<?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
			<?php do_action('woocommerce_thankyou', $order->get_id()); ?>

		<?php endif; ?>
		<?php endif; ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received<?php echo($thegem_checkout_type == 'one-page-modern' ? ' default-background styled-subtitle centered-box' : ''); ?>"><?php echo apply_filters('woocommerce_thankyou_order_received_text', esc_html__('Thank you. Your order has been received.', 'woocommerce'), null); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>

</div>
