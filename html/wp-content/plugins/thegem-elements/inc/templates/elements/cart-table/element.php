<?php

class TheGem_Template_Element_Cart_Table extends TheGem_Cart_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_cart_table';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = array_merge(
			array(
				'layout' => '',
				'thumbnail_size' => 'small',
				'column_headers' => 1,
				'column_headers_text_style' => '',
				'column_headers_font_weight' => 'light',
				'content_vertical_align' => 'middle',
				'dividers' => 1,
				'apply_coupon' => 1,
				'coupon_placeholder_text' => __( 'Coupon code', 'woocommerce' ),
				'apply_coupon_btn_text_weight' => 'normal',
				'update_cart_btn_text_weight' => 'normal',
				'apply_coupon_btn_text' => __( 'Apply coupon', 'woocommerce' ),
				'update_cart_btn_text' => __( 'Update cart', 'woocommerce' ),
				'update_cart_automatically' => 0,
				'coupon_border_radius' => '',
				'qty_border_radius' => '',
				'apply_coupon_btn_border_radius' => '',
				'update_cart_btn_border_radius' => '',
			),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		);
		if(is_array($atts)) {
			$params = array_merge($params, $atts);
		}

		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		ob_start();
  
		$headers_classes = implode(' ', array($params['column_headers_text_style'], $params['column_headers_font_weight']));
		$params['element_class'] = implode(' ', array($params['element_class'], thegem_templates_responsive_options_output($params)));
		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
			 class="thegem-te-cart-table <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
			<form class="woocommerce-cart-form <?php echo(!empty($params['update_cart_automatically']) ? ' update-cart-automatically' : ''); ?><?php echo(!empty($params['layout'] === 'compact') ? ' compact' : ''); ?>" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
				<?php do_action( 'woocommerce_before_cart_table' ); ?>

				<div class="gem-table"><table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents<?php echo(empty($params['dividers']) ? ' no-dividers' : ''); ?>" cellspacing="0">
					<?php if(!empty($params['column_headers'])) : ?>
					<thead>
						<tr>
							<th class="product-name" colspan="3"><span class="<?= esc_attr($headers_classes); ?>"><?php esc_html_e( 'Product', 'woocommerce' ); ?></span></th>
							<th class="product-quantity"><span class="<?= esc_attr($headers_classes); ?>"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></span></th>
							<th class="product-subtotal"><span class="<?= esc_attr($headers_classes); ?>"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span></th>
						</tr>
					</thead>
					<?php endif; ?>
					<tbody>
						<?php do_action( 'woocommerce_before_cart_contents' ); ?>

						<?php
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
								?>
								<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

									<td class="product-remove">
										<?php
											echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												'woocommerce_cart_item_remove_link',
												sprintf(
													'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"></a>',
													esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
													esc_html__( 'Remove this item', 'woocommerce' ),
													esc_attr( $product_id ),
													esc_attr( $_product->get_sku() )
												),
												$cart_item_key
											);
										?>
									</td>

									<td class="product-thumbnail">
									<?php
									$product_image = $params['thumbnail_size'] == 'medium' ? get_the_post_thumbnail($_product->get_id(), 'thegem-product-thumbnail-vertical-2x') : $_product->get_image();
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $product_image, $cart_item, $cart_item_key );

									if ( ! $product_permalink ) {
										echo $thumbnail; // PHPCS: XSS ok.
									} else {
										printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
									}
									?>
									</td>

									<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
									<?php
									if ( ! $product_permalink ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
									} else {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
									}

									do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

									// Meta data.
									echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

									// Backorder notification.
									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
									}

									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									?>
									</td>


									<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
									<?php
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'	=> $_product->get_max_purchase_quantity(),
												'min_value'	=> '0',
												'product_name' => $_product->get_name(),
											),
											$_product,
											false
										);
									}

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
									?>
									</td>

									<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
										<?php
											echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
										?>
									</td>
								</tr>
								<?php
							}
						}
						?>

						<?php do_action( 'woocommerce_cart_contents' ); ?>

						<tr>
							<td colspan="6" class="actions"><div class="actions-inner">

								<?php if ( wc_coupons_enabled() && !empty($params['apply_coupon']) ) : ?>
									<div class="coupon">
										<input type="text" name="coupon_code" class="input-text coupon-code" id="coupon_code" value="" placeholder="<?= esc_attr($params['coupon_placeholder_text']); ?>" />
										<?php
											thegem_button(array(
												'tag' => 'button',
												'text' => esc_html($params['apply_coupon_btn_text']),
												'style' => 'outline',
												'size' => 'small',
												'text_weight' => $params['apply_coupon_btn_text_weight'],
												'attributes' => array(
													'name' => 'apply_coupon',
													'value' => esc_attr($params['apply_coupon_btn_text']),
													'type' => 'submit',
													'class' => 'button gem-button-tablet-size-small'
												)
											), true);
										?>
										<?php do_action( 'woocommerce_cart_coupon' ); ?>
									</div>
								<?php endif; ?>


								<div class="submit-buttons"<?php echo ($params['update_cart_automatically'] ? ' style="display: none;"' : ''); ?>>
									<?php
										thegem_button(array(
											'tag' => 'button',
											'text' => esc_html($params['update_cart_btn_text']),
											'size' => 'small',
											'extra_class' => 'update-cart',
											'text_weight' => $params['update_cart_btn_text_weight'],
											'attributes' => array(
												'name' => 'update_cart',
												'value' => esc_attr($params['update_cart_btn_text']),
												'type' => 'submit',
												'class' => 'button gem-button-tablet-size-small'
											)
										), true);
									?>

									<?php do_action( 'woocommerce_cart_actions' ); ?>
								</div>

								<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
							</div></td>
						</tr>

						<?php do_action( 'woocommerce_after_cart_contents' ); ?>
					</tbody>
				</table></div>
				<?php do_action( 'woocommerce_after_cart_table' ); ?>
			</form>
			<script type="text/javascript">(function($){$('form:not(.cart) div.quantity:not(.buttons_added)').addClass('buttons_added').append('<button type="button" class="plus" >+</button>').prepend('<button type="button" class="minus" >-</button>');})(jQuery);</script>
		</div>

		<?php
		//Custom Styles
		$customize = '.thegem-te-cart-table.'.$uniqid;
		$custom_css = '';

		if (!empty($params['column_headers_letter_spacing'])) {
			$custom_css .= $customize.' .shop_table thead tr th span {letter-spacing: ' . $params['column_headers_letter_spacing'] . 'px;}';
		}
		if (!empty($params['column_headers_text_transform'])) {
			$custom_css .= $customize.' .shop_table thead tr th span {text-transform: ' . $params['column_headers_text_transform'] . ';}';
		}
		if ($params['layout'] !== 'compact') {
			$custom_css .= $customize.' .shop_table tr td {vertical-align: ' . $params['content_vertical_align'] . ';}';
		}
		if (!empty($params['apply_coupon_btn_text_transform'])) {
			$custom_css .= $customize.' .woocommerce-cart-form .actions .coupon button {text-transform: ' . $params['apply_coupon_btn_text_transform'] . ';}';
		}
		if (!empty($params['update_cart_btn_text_transform'])) {
			$custom_css .= $customize.' .actions .submit-buttons button.button {text-transform: ' . $params['apply_coupon_btn_text_transform'] . ';}';
		}
		if (!empty($params['column_headers_text_color'])) {
			$custom_css .= $customize.' .shop_table thead tr th span {color: ' . $params['column_headers_text_color'] . ' !important;}';
		}
		if (!empty($params['content_text_color'])) {
			$custom_css .= $customize.' .shop_table tbody td {color: ' . $params['content_text_color'] . ' !important;}';
			$custom_css .= $customize.' .shop_table tbody td input[type="number"] {color: ' . $params['content_text_color'] . ' !important;}';
			$custom_css .= $customize.' .shop_table #coupon_code {color: ' . $params['content_text_color'] . ' !important;}';
		}
		if (!empty($params['content_links_text_color'])) {
			$custom_css .= $customize.' .shop_table tbody td a {color: ' . $params['content_links_text_color'] . ' !important;}';
		}
		if (!empty($params['content_links_hover_color'])) {
			$custom_css .= $customize.' .shop_table tbody td a:hover {color: ' . $params['content_links_hover_color'] . ' !important;}';
		}
		if (!empty($params['content_subtotal_color'])) {
			$custom_css .= $customize.' .shop_table tbody td.product-subtotal {color: ' . $params['content_subtotal_color'] . ' !important;}';
		}
		if (!empty($params['dividers_color'])) {
			$custom_css .= $customize.' .shop_table tbody td {border-color: ' . $params['dividers_color'] . ' !important;}';
			$custom_css .= $customize.' .shop_table tbody td input[type="number"] {border-color: ' . $params['dividers_color'] . ' !important;}';
			$custom_css .= $customize.' .shop_table tbody td .quantity {border-color: ' . $params['dividers_color'] . ' !important;}';
			$custom_css .= $customize.' form.woocommerce-cart-form.compact table.shop_table_responsive.shop_table.woocommerce-cart-form__contents tbody tr + tr {border-color: ' . $params['dividers_color'] . ' !important;}';
			$custom_css .= $customize.' .shop_table tbody td .quantity button:before {background-color: ' . $params['dividers_color'] . ' !important;}';
			$custom_css .= $customize.' .shop_table #coupon_code {border-color: ' . $params['dividers_color'] . ' !important;}';
			$custom_css .= $customize.' .shop_table tr td.product-remove .remove {border-color: ' . $params['dividers_color'] . ' !important;}';
			$custom_css .= $customize.' .shop_table tr td.product-remove .remove:before, '.$customize.' .shop_table tr td.product-remove .remove:after {background-color: ' . $params['dividers_color'] . ' !important;}';
		}
		if (!empty($params['coupon_placeholder_color'])) {
			$custom_css .= $customize.' .shop_table #coupon_code::placeholder {color: ' . $params['coupon_placeholder_color'] . ' !important;}';
		}
		if (!empty($params['coupon_border_radius']) || $params['coupon_border_radius'] === '0') {
			$custom_css .= $customize.' .shop_table #coupon_code {border-radius: ' . $params['coupon_border_radius'] . 'px !important;}';
		}
		if (!empty($params['qty_border_radius']) || $params['qty_border_radius'] === '0') {
			$custom_css .= $customize.' .shop_table .product-quantity .quantity {border-radius: ' . $params['qty_border_radius'] . 'px !important;}';
		}
		if (!empty($params['apply_coupon_btn_border_width'])) {
			$custom_css .= $customize.' .woocommerce-cart-form .actions .coupon button {border-width:'.$params['apply_coupon_btn_border_width'].'px !important;}';
		}
		if (!empty($params['apply_coupon_btn_border_radius']) || $params['apply_coupon_btn_border_radius'] === '0') {
			$custom_css .= $customize.' .woocommerce-cart-form .actions .coupon button {border-radius:'.$params['apply_coupon_btn_border_radius'].'px !important;}';
		}
		if (!empty($params['apply_coupon_btn_text_color'])) {
			$custom_css .= $customize.' .woocommerce-cart-form .actions .coupon button {color:'.$params['apply_coupon_btn_text_color'].'!important;}';
		}
		if (!empty($params['apply_coupon_btn_text_color_hover'])) {
			$custom_css .= $customize.' .woocommerce-cart-form .actions .coupon button:hover {color:'.$params['apply_coupon_btn_text_color_hover'].'!important;}';
		}
		if (!empty($params['apply_coupon_btn_background_color'])) {
			$custom_css .= $customize.' .woocommerce-cart-form .actions .coupon button {background-color:'.$params['apply_coupon_btn_background_color'].'!important;}';
		}
		if (!empty($params['apply_coupon_btn_background_color_hover'])) {
			$custom_css .= $customize.' .woocommerce-cart-form .actions .coupon button:hover {background-color:'.$params['apply_coupon_btn_background_color_hover'].'!important;}';
		}
		if (!empty($params['apply_coupon_btn_border_color'])) {
			$custom_css .= $customize.' .woocommerce-cart-form .actions .coupon button {border-color:'.$params['apply_coupon_btn_border_color'].'!important;}';
		}
		if (!empty($params['apply_coupon_btn_border_color_hover'])) {
			$custom_css .= $customize.' .woocommerce-cart-form .actions .coupon button:hover {border-color:'.$params['apply_coupon_btn_border_color_hover'].'!important;}';
		}
		if (!empty($params['update_cart_btn_border_width'])) {
			$custom_css .= $customize.' .actions .submit-buttons button.button {border-width:'.$params['update_cart_btn_border_width'].'px !important;}';
		}
		if (!empty($params['update_cart_btn_border_radius']) || $params['update_cart_btn_border_radius'] === '0') {
			$custom_css .= $customize.' .actions .submit-buttons button.button {border-radius:'.$params['update_cart_btn_border_radius'].'px !important;}';
		}
		if (!empty($params['update_cart_btn_text_color'])) {
			$custom_css .= $customize.' .actions .submit-buttons button.button {color:'.$params['update_cart_btn_text_color'].'!important;}';
		}
		if (!empty($params['update_cart_btn_text_color_hover'])) {
			$custom_css .= $customize.' .actions .submit-buttons button.button:hover {color:'.$params['update_cart_btn_text_color_hover'].'!important;}';
		}
		if (!empty($params['update_cart_btn_background_color'])) {
			$custom_css .= $customize.' .actions .submit-buttons button.button {background-color:'.$params['update_cart_btn_background_color'].'!important;}';
		}
		if (!empty($params['update_cart_btn_background_color_hover'])) {
			$custom_css .= $customize.' .actions .submit-buttons button.button:hover {background-color:'.$params['update_cart_btn_background_color_hover'].'!important;}';
		}
		if (!empty($params['update_cart_btn_border_color'])) {
			$custom_css .= $customize.' .actions .submit-buttons button.button {border-color:'.$params['update_cart_btn_border_color'].'!important;}';
		}
		if (!empty($params['update_cart_btn_border_color_hover'])) {
			$custom_css .= $customize.' .actions .submit-buttons button.button:hover {border-color:'.$params['update_cart_btn_border_color_hover'].'!important;}';
		}
		if (!empty($params['loading_overlay_color'])) {
			$custom_css .= $customize.' .blockOverlay {background-color: ' . $params['loading_overlay_color'] . ' !important;}';
		}

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}

		$return_html = $css_output.$return_html;
		return $return_html;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Cart Table', 'thegem'),
			'base' => 'thegem_te_cart_table',
			'icon' => 'thegem-icon-wpb-ui-element-cart-table',
			'category' => __('Cart Builder', 'thegem'),
			'description' => __('Cart Table (Product Builder)', 'thegem'),
			'params' => array_merge(
				array(
					array(
						'type' => 'dropdown',
						'heading' => __('Layout', 'thegem'),
						'param_name' => 'layout',
						'value' => array_merge(array(
								__('Default', 'thegem') => '',
								__('Compact', 'thegem') => 'compact',
							)
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Thumbnail Size', 'thegem'),
						'param_name' => 'thumbnail_size',
						'value' => array_merge(array(
								__('Small', 'thegem') => 'small',
								__('Medium', 'thegem') => 'medium',
							)
						),
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Table Headers', 'thegem'),
						'param_name' => 'table_headers_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Column Headers', 'thegem'),
						'param_name' => 'column_headers',
						'value' => array(__('Show', 'thegem') => '1'),
						'std' => '1',
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Text Style', 'thegem'),
						'param_name' => 'column_headers_text_style',
						'value' => array(
							__('Default', 'thegem') => '',
							__('Title H1', 'thegem') => 'title-h1',
							__('Title H2', 'thegem') => 'title-h2',
							__('Title H3', 'thegem') => 'title-h3',
							__('Title H4', 'thegem') => 'title-h4',
							__('Title H5', 'thegem') => 'title-h5',
							__('Title H6', 'thegem') => 'title-h6',
							__('Title xLarge', 'thegem') => 'title-xlarge',
							__('Styled Subtitle', 'thegem') => 'styled-subtitle',
							__('Main Menu', 'thegem') => 'title-main-menu',
							__('Body', 'thegem') => 'text-body',
							__('Tiny Body', 'thegem') => 'text-body-tiny',
						),
						'std' => '',
						'dependency' => array(
							'element' => 'column_headers',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Font weight', 'thegem'),
						'param_name' => 'column_headers_font_weight',
						'value' => array(
							__('Default', 'thegem') => '',
							__('Thin', 'thegem') => 'light',
						),
						'std' => 'light',
						'dependency' => array(
							'element' => 'column_headers',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Letter Spacing', 'thegem'),
						'param_name' => 'column_headers_letter_spacing',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'dependency' => array(
							'element' => 'column_headers',
							'value' => '1'
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Text Transform', 'thegem'),
						'param_name' => 'column_headers_text_transform',
						'value' => array(
							__('Default', 'thegem') => '',
							__('None', 'thegem') => 'none',
							__('Capitalize', 'thegem') => 'capitalize',
							__('Lowercase', 'thegem') => 'lowercase',
							__('Uppercase', 'thegem') => 'uppercase',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'dependency' => array(
							'element' => 'column_headers',
							'value' => '1'
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'column_headers_text_color',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'dependency' => array(
							'element' => 'column_headers',
							'value' => '1'
						),
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Table Content', 'thegem'),
						'param_name' => 'table_content_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Vertical Align', 'thegem'),
						'param_name' => 'content_vertical_align',
						'value' => array(
							__('Top', 'thegem') => 'top',
							__('Middle', 'thegem') => 'middle',
							__('Bottom', 'thegem') => 'bottom',
						),
						'std' => 'middle',
						'dependency' => array(
							'element' => 'layout',
							'value' => array('')
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'content_text_color',
						'edit_field_class' => 'vc_column vc_col-sm-3',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Links Color', 'thegem'),
						'param_name' => 'content_links_text_color',
						'edit_field_class' => 'vc_column vc_col-sm-3',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Links Hover Color', 'thegem'),
						'param_name' => 'content_links_hover_color',
						'edit_field_class' => 'vc_column vc_col-sm-3',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Subtotal Color', 'thegem'),
						'param_name' => 'content_subtotal_color',
						'edit_field_class' => 'vc_column vc_col-sm-3',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Loading Overlay Color', 'thegem'),
						'param_name' => 'loading_overlay_color',
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Dividers & Forms', 'thegem'),
						'param_name' => 'dividers_forms_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Dividers', 'thegem'),
						'param_name' => 'dividers',
						'value' => array(__('Show', 'thegem') => '1'),
						'std' => '1',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Dividers & Borders Color', 'thegem'),
						'param_name' => 'dividers_color',
						'edit_field_class' => 'vc_column vc_col-sm-12',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Quantity Control Border Radius', 'thegem'),
						'param_name' => 'qty_border_radius',
						'value' => '',
						'edit_field_class' => 'vc_column vc_col-sm-12',
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Apply Coupon', 'thegem'),
						'param_name' => 'apply_coupon_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Apply Coupon Show', 'thegem'),
						'param_name' => 'apply_coupon',
						'value' => array(__('Show', 'thegem') => '1'),
						'std' => '1',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Placeholder Text', 'thegem'),
						'param_name' => 'coupon_placeholder_text',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'apply_coupon',
							'value' => '1'
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Input Placeholder Color', 'thegem'),
						'param_name' => 'coupon_placeholder_color',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'apply_coupon',
							'value' => '1'
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Input Border Radius', 'thegem'),
						'param_name' => 'coupon_border_radius',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'apply_coupon',
							'value' => '1'
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Apply Coupon Button', 'thegem'),
						'param_name' => 'apply_coupon_btn_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'dependency' => array(
							'element' => 'apply_coupon',
							'value' => '1'
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Button Text', 'thegem'),
						'param_name' => 'apply_coupon_btn_text',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'apply_coupon',
							'value' => '1'
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'apply_coupon_btn_border_width',
						'value' => array_merge(array(
								__('Default', 'thegem') => '',
								__('1', 'thegem') => '1',
								__('2', 'thegem') => '2',
								__('3', 'thegem') => '3',
								__('4', 'thegem') => '4',
								__('5', 'thegem') => '5',
								__('6', 'thegem') => '6',
							)
						),
						'std' => '',
						'dependency' => array(
							'element' => 'apply_coupon',
							'value' => '1',
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Radius', 'thegem'),
						'param_name' => 'apply_coupon_btn_border_radius',
						'value' => '',
						'dependency' => array(
							'element' => 'apply_coupon',
							'value' => '1',
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'apply_coupon_btn_text_color',
						'std' => '',
						'dependency' => array(
							'element' => 'apply_coupon',
							'value' => '1',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color on Hover', 'thegem'),
						'param_name' => 'apply_coupon_btn_text_color_hover',
						'std' => '',
						'dependency' => array(
							'element' => 'apply_coupon',
							'value' => '1',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'apply_coupon_btn_background_color',
						'std' => '',
						'dependency' => array(
							'element' => 'apply_coupon',
							'value' => '1',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color on Hover', 'thegem'),
						'param_name' => 'apply_coupon_btn_background_color_hover',
						'std' => '',
						'dependency' => array(
							'element' => 'apply_coupon',
							'value' => '1',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'apply_coupon_btn_border_color',
						'std' => '',
						'dependency' => array(
							'element' => 'apply_coupon',
							'value' => '1',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color on Hover', 'thegem'),
						'param_name' => 'apply_coupon_btn_border_color_hover',
						'std' => '',
						'dependency' => array(
							'element' => 'apply_coupon',
							'value' => '1',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Update Cart', 'thegem'),
						'param_name' => 'update_cart_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Update Cart Automatically', 'thegem'),
						'param_name' => 'update_cart_automatically',
						'value' => array(
							__('Disabled', 'thegem') => '0',
							__('Enabled', 'thegem') => '1',
						),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Update Cart Button', 'thegem'),
						'param_name' => 'update_cart_btn_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'dependency' => array(
							'element' => 'update_cart_automatically',
							'value' => '0',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Button Text', 'thegem'),
						'param_name' => 'update_cart_btn_text',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'update_cart_automatically',
							'value' => '0',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'update_cart_btn_border_width',
						'value' => array_merge(array(
								__('Default', 'thegem') => '',
								__('1', 'thegem') => '1',
								__('2', 'thegem') => '2',
								__('3', 'thegem') => '3',
								__('4', 'thegem') => '4',
								__('5', 'thegem') => '5',
								__('6', 'thegem') => '6',
							)
						),
						'std' => '',
						'dependency' => array(
							'element' => 'update_cart_automatically',
							'value' => '0',
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Radius', 'thegem'),
						'param_name' => 'update_cart_btn_border_radius',
						'value' => '',
						'dependency' => array(
							'element' => 'update_cart_automatically',
							'value' => '0',
						),
						'edit_field_class' => 'vc_column vc_col-sm-4',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'update_cart_btn_text_color',
						'std' => '',
						'dependency' => array(
							'element' => 'update_cart_automatically',
							'value' => '0',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color on Hover', 'thegem'),
						'param_name' => 'update_cart_btn_text_color_hover',
						'std' => '',
						'dependency' => array(
							'element' => 'update_cart_automatically',
							'value' => '0',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'update_cart_btn_background_color',
						'std' => '',
						'dependency' => array(
							'element' => 'update_cart_automatically',
							'value' => '0',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color on Hover', 'thegem'),
						'param_name' => 'update_cart_btn_background_color_hover',
						'std' => '',
						'dependency' => array(
							'element' => 'update_cart_automatically',
							'value' => '0',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'update_cart_btn_border_color',
						'std' => '',
						'dependency' => array(
							'element' => 'update_cart_automatically',
							'value' => '0',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color on Hover', 'thegem'),
						'param_name' => 'update_cart_btn_border_color_hover',
						'std' => '',
						'dependency' => array(
							'element' => 'update_cart_automatically',
							'value' => '0',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
				),

				/* Responsive Options */
				thegem_set_elements_responsive_options()
			),
		);
	}
}

$templates_elements['thegem_te_cart_table'] = new TheGem_Template_Element_Cart_Table();
$templates_elements['thegem_te_cart_table']->init();
