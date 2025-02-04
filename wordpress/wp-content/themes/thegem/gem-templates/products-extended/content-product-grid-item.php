<?php
if (!function_exists('thegem_get_wishlist_share_icons')) {
	function thegem_get_wishlist_share_icons($params, $post_id) { ?>
		<!-- YITH -->
		<?php if (defined('YITH_WCWL') && $params['product_show_wishlist'] == '1'): ?>
			<span class="yith-icon">
				<?php
				if (isset($params['add_wishlist_icon_pack']) && $params['wishlist_icon_' . $params['add_wishlist_icon_pack']] != '') {
					echo thegem_build_icon($params['add_wishlist_icon_pack'], $params['wishlist_icon_' . $params['add_wishlist_icon_pack']], 'add-wishlist-icon');
				} else { ?>
					<i class="add-wishlist-icon default"></i>
				<?php }
				if (isset($params['added_wishlist_icon_pack']) && $params['added_wishlist_icon_' . $params['added_wishlist_icon_pack']] != '') {
					echo thegem_build_icon($params['added_wishlist_icon_pack'], $params['added_wishlist_icon_' . $params['added_wishlist_icon_pack']], 'added-wishlist-icon');
				} else { ?>
					<i class="added-wishlist-icon default"></i>
				<?php } ?>
				<?php echo do_shortcode("[yith_wcwl_add_to_wishlist icon='']"); ?>
			</span>
		<?php endif; ?>

		<!-- Sharing -->
		<?php if ($params['social_sharing'] == '1'): ?>
			<div class="post-footer-sharing">
				<a href="javascript: void(0);" class="icon share">
					<i class="default"></i>
				</a>
				<div class="sharing-popup"><?php include 'socials-sharing.php'; ?>
					<svg class="sharing-styled-arrow">
						<use xlink:href="<?php echo esc_url(THEGEM_THEME_URI . '/css/post-arrow.svg'); ?>#dec-post-arrow"></use>
					</svg>
				</div>
			</div>
		<?php endif; ?>
	<?php }
}

if (!function_exists('thegem_get_swatches_attributes')) {
	function thegem_get_swatches_attributes($product, $swatches_attr) {
		$get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
		$available_variations = $get_variations ? $product->get_available_variations() : false;
		$attributes = $product->get_variation_attributes();
		$variations_json = wp_json_encode( $available_variations );
		$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

		if ( !empty( $available_variations ) || !$available_variations ) { ?>

			<form class="product-variations variations_form cart"
				  action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
				  method="post" enctype='multipart/form-data'
				  data-product_id="<?php echo absint( $product->get_id() ); ?>"
				  data-product_variations="<?php echo $variations_attr; ?>">
				<div class="variations" role="presentation">
					<?php
					foreach ($swatches_attr as $item) {
						$current_attribute_name = '';
						foreach ( $attributes as $attribute_name => $options ) {
							if (isset($item['attribute_name']) && 'pa_'.$item['attribute_name'] == $attribute_name) {
								$current_attribute_name = $attribute_name;
								break;
							}
						}
						if (empty($current_attribute_name)) continue;
						$attribute_data = wc_get_attribute(wc_attribute_taxonomy_id_by_name($current_attribute_name));
						$terms = wc_get_product_terms(
							$product->get_id(),
							$current_attribute_name,
							array('fields' => 'all')
						); ?>
						<div id="<?php echo esc_attr($current_attribute_name); ?>"
							 class="gem-attribute-selector type-<?php echo esc_attr($attribute_data->type); ?>"
							 data-attribute_name="attribute_<?php echo esc_attr($current_attribute_name); ?>">
							<div class="field-input">
								<select id="<?php echo esc_attr($current_attribute_name); ?>" class="thegem-select" name="attribute_<?php echo esc_attr($current_attribute_name); ?>">
									<option value="">Choose an option</option>
									<?php foreach ($terms as $term) {
										if ( in_array( $term->slug, $attributes[$current_attribute_name], true ) ) { ?>
											<option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
										<?php }
									} ?>
								</select>
							</div>
							<ul class="styled gem-attribute-options">
								<?php $i = 0;
								foreach ($terms as $term) {
									if ( in_array( $term->slug, $attributes[$current_attribute_name], true ) ) {
										if ($i == 0 && isset($item['attribute_show_name']) && $item['attribute_show_name'] == '1') { ?>
											<span class="attribute-name text-body-tiny"><?php echo $attribute_data->name; ?>:</span>
										<?php }
										if (isset($item['attribute_count']) && $i == $item['attribute_count']) { ?>
											<a class="more-variables text-body-tiny" href="<?php echo $product->add_to_cart_url() ?>">+<?php echo count($terms) - $i; ?></a>
											<?php break;
										}
										$i++;?>
										<li data-value="<?php echo $term->slug; ?>" class="text-body-tiny">
											<?php if ($attribute_data->type == 'color') {
												$attribute_color = get_term_meta( $term->term_id, 'thegem_color', true ); ?>
												<span class="color" <?php echo !empty($attribute_color) ? ' style="background-color: ' . esc_attr($attribute_color).';"' : ''; ?>></span>
												<span class="text"><?php echo $term->name; ?></span>
											<?php } else if ($attribute_data->type == 'label') {
												$label = get_term_meta( $term->term_id, 'thegem_label', true );
												$label = empty($label) ? $term->name : $label; ?>
												<span class="label"><?php echo esc_html($label); ?></span>
												<span class="text"><?php echo esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $current_attribute_name, $product ) ); ?></span>
											<?php } else {
												echo esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $current_attribute_name, $product ) );
											} ?>
										</li>
										<?php
									}
								} ?>
							</ul>
						</div>
						<?php
					} ?>
				</div>
				<div class="single_variation_wrap" style="display: none">
					<div class="woocommerce-variation single_variation"></div>
				</div>
			</form>

		<?php }
	}
}

if (!isset($product_grid_item_size)):
	$thegem_size = $thegem_sizes[0];
	$thegem_sources = $thegem_sizes[1];

	$show_swatches = isset($params['attribute_swatches']) && ($params['attribute_swatches'] == '1' || $params['attribute_swatches_tablet'] == '1' || $params['attribute_swatches_mobile'] == '1');

	if ($params['caption_position'] == 'image') {
		$hover_effect = $params['image_hover_effect_image'];
	} else if ($params['caption_position'] == 'page') {
		$hover_effect = $params['image_hover_effect_page'];
	} else {
		$hover_effect = $params['image_hover_effect_hover'];
	}

	$product_short_description = $product->get_short_description();
	$inline_list_layout = false;
	if ( isset($params['layout_type']) && $params['layout_type'] == 'list') {
		if (isset($params['caption_layout_list']) && $params['caption_layout_list'] == 'inline') {
			$inline_list_layout = true;
		}
	} else {
		$product_short_description = strip_shortcodes($product_short_description);
		$product_short_description = wp_strip_all_tags($product_short_description);
		$product_short_description_length = apply_filters( 'excerpt_length', 20 );
		$product_short_description_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
		$product_short_description = wp_trim_words($product_short_description, $product_short_description_length, $product_short_description_more);
	}

	$product_hover_image_id = 0;
	if (($params['caption_position'] == 'page' && $hover_effect !== 'disabled') || ($hover_effect == 'slide' || $hover_effect == 'fade')) {
		$gallery = $product->get_gallery_image_ids();
		$has_product_hover = get_post_meta($post_id, 'thegem_product_disable_hover', true);
		if (isset($gallery[0]) && !$has_product_hover) {
			$product_hover_image_id = $gallery[0];
			$thegem_classes[] = 'image-hover';
		}
	}

	$rating_count = $product->get_rating_count();
	if ($rating_count > 0) {
		$thegem_classes[] = 'has-rating';
	} ?>
	<div <?php post_class($thegem_classes, $post_id); ?>>
		<div class="item-separator-box"></div>
		<div class="actions woocommerce_before_shop_loop_item">
			<?php do_action('woocommerce_before_shop_loop_item'); ?>
		</div>
		<div class="wrap clearfix">
			<div <?php post_class(array('image'), $post_id); ?>>
				<div class="image-inner <?php if (!$product_hover_image_id) {
					echo esc_attr('fallback-' . $params['image_hover_effect_fallback']);
				} ?>">
					<?php if (has_post_thumbnail($post_id) || has_post_thumbnail($product->get_parent_id())) {
						if (has_post_thumbnail($post_id)) {
							$thumbnail_id = get_post_thumbnail_id($post_id);
						} else {
							$thumbnail_id = get_post_thumbnail_id($product->get_parent_id());
						}
						$picture_info = thegem_generate_picture($thumbnail_id, $thegem_size, $thegem_sources, array('alt' => get_the_title($post_id), /*'style' => 'max-width: 110%'*/), true);
						if ($picture_info && !empty($picture_info['default']) && !empty($picture_info['default'][0]) && $product_hover_image_id) {
							$thegem_hover_size = $thegem_size;
							if ($params['layout'] == 'masonry') {
								$thegem_hover_size = $thegem_size . '-' . $picture_info['default'][1] . '-' . $picture_info['default'][2];
								global $thegem_size_template_global, $picture_info_template_global, $thegem_hover_size_template_global;
								$thegem_size_template_global = $thegem_size;
								$picture_info_template_global = $picture_info;
								$thegem_hover_size_template_global = $thegem_hover_size;
								add_filter('thegem_image_sizes', function ($sizes) {
									global $thegem_size_template_global, $picture_info_template_global, $thegem_hover_size_template_global;
									$size = $sizes[$thegem_size_template_global];
									$size[1] = $picture_info_template_global['default'][2];
									$size[2] = true;
									$sizes[$thegem_hover_size_template_global] = $size;
									return $sizes;
								});
								$thegem_sources = array();
							}
							if ($params['layout'] == 'metro') {
								$thegem_hover_size = $thegem_size . '-' . $picture_info['default'][1] . '-' . $picture_info['default'][2];
								global $thegem_size_template_global, $picture_info_template_global, $thegem_hover_size_template_global;
								$thegem_size_template_global = $thegem_size;
								$picture_info_template_global = $picture_info;
								$thegem_hover_size_template_global = $thegem_hover_size;
								add_filter('thegem_image_sizes', function ($sizes) {
									global $thegem_size_template_global, $picture_info_template_global, $thegem_hover_size_template_global;
									$size = $sizes[$thegem_size_template_global];
									$size[0] = $picture_info_template_global['default'][1];
									$size[2] = true;
									$sizes[$thegem_hover_size_template_global] = $size;
									return $sizes;
								});
								$thegem_sources = array();
							}
							thegem_generate_picture($product_hover_image_id, $thegem_hover_size, $thegem_sources, array(
								'alt' => get_the_title(),
								'class' => 'image-hover hover-' . $hover_effect,
							));
						}
					} else {
						thegem_generate_picture('THEGEM_TRANSPARENT_IMAGE', $thegem_size, $thegem_sources, array('alt' => get_the_title($post_id)), true);
					} ?>
					<?php if ($show_swatches && $product->is_type('variable')) { ?>
						<picture><img class="variation-image" src="" alt="" style="display: none"></picture>
					<?php } ?>
				</div>
				<div class="overlay">
					<div class="overlay-circle"></div>
					<?php if ($params['caption_position'] != 'page') { ?>
						<div class="links-wrapper">
							<div class="links">
								<?php if ($hover_effect !== 'disabled') { ?>
									<div class="portfolio-icons product-bottom">
										<div class="portfolio-icons-inner clearfix">
											<?php if ($params['quick_view'] == '1') {
												echo '<a class="icon quick-view-button" data-product-id="' . $post->ID . '"><i class="default"></i></a>';
											} ?>
											<?php if ($params['product_show_add_to_cart'] == '1' && $params['add_to_cart_type'] == 'icon') { ?>
												<!-- Add to cart -->
												<?php if ($product->is_in_stock()): ?>
													<?php if ($product->get_type() === 'variable') { ?>
														<a href="<?php echo $product->add_to_cart_url() ?>"
														   data-quantity="1"
														   class="icon cart button product_type_<?php echo $product->get_type(); ?> add_to_cart_button <?php echo $add_to_cart_class; ?>"
														   rel="nofollow">
															<?php
															if (isset($params['select_options_pack']) && $params['select_options_icon_' . $params['select_options_pack']] != '') {
																echo thegem_build_icon($params['select_options_pack'], $params['select_options_icon_' . $params['select_options_pack']]);
															} else { ?>
																<i class="default"></i>
															<?php } ?>
															<?php echo esc_html($product->add_to_cart_text()) ?>
														</a>
													<?php }

													if ($product->get_type() === 'simple' || $show_swatches) { ?>
														<a href="<?php echo $product->add_to_cart_url() ?>"
														   data-quantity="1"
														   class="icon cart button product_type_simple add_to_cart_button ajax_add_to_cart <?php echo $add_to_cart_class; ?>"
														   data-product_id="<?php echo $product->get_id(); ?>"
														   data-product_sku="<?php echo $product->get_sku(); ?>"
														   aria-label="<?php echo 'Add ' . $product->get_name() . ' to your cart'; ?>"
														   rel="nofollow">
															<?php
															if (isset($params['cart_button_pack']) && $params['cart_icon_' . $params['cart_button_pack']] != '') {
																echo thegem_build_icon($params['cart_button_pack'], $params['cart_icon_' . $params['cart_button_pack']]);
															} else { ?>
																<i class="default"></i>
															<?php } ?>
															<?php echo esc_html($product->add_to_cart_text()) ?>
														</a>
													<?php } ?>
												<?php endif; ?>
											<?php } ?>

											<!-- YITH -->
											<?php if (defined('YITH_WCWL') && $params['product_show_wishlist'] == '1'): ?>
												<span class="icon yith-icon">
												<?php
												if (isset($params['add_wishlist_icon_pack']) && $params['wishlist_icon_' . $params['add_wishlist_icon_pack']] != '') {
													echo thegem_build_icon($params['add_wishlist_icon_pack'], $params['wishlist_icon_' . $params['add_wishlist_icon_pack']], 'add-wishlist-icon');
												} else { ?>
													<i class="add-wishlist-icon default"></i>
												<?php }
												if (isset($params['added_wishlist_icon_pack']) && $params['added_wishlist_icon_' . $params['added_wishlist_icon_pack']] != '') {
													echo thegem_build_icon($params['added_wishlist_icon_pack'], $params['added_wishlist_icon_' . $params['added_wishlist_icon_pack']], 'added-wishlist-icon');
												} else { ?>
													<i class="added-wishlist-icon default"></i>
												<?php } ?>

													<?php echo do_shortcode("[yith_wcwl_add_to_wishlist icon='']"); ?>
											</span>
											<?php endif; ?>

											<?php if ($params['social_sharing'] == '1'): ?>
												<a href="javascript: void(0);" class="icon share">
													<i class="default"></i>
												</a>
											<?php endif; ?>
										</div>
										<div class="overlay-line"></div>
										<?php if ($params['social_sharing'] == '1'): ?>
											<div class="portfolio-sharing-pane">
												<?php include 'socials-sharing.php'; ?>
												<?php if (($params['caption_position'] == 'image' && ($params['image_hover_effect_image'] == 'slide' || $params['image_hover_effect_image'] == 'fade')) ||
												          ($params['caption_position'] == 'hover' && ($params['image_hover_effect_hover'] == 'slide' || $params['image_hover_effect_hover'] == 'fade'))) { ?>
												<?php } ?>
											</div>
										<?php endif; ?>
									</div>
								<?php } ?>

								<div class="caption">
									<?php
									if ($show_swatches && $product->is_type('variable')) {
										thegem_get_swatches_attributes($product, $params['repeater_swatches']);
									}
									if (($params['product_show_categories'] == '1' ||
											(isset($params['product_show_categories_tablet']) && $params['product_show_categories_tablet'] == '1') ||
											(isset($params['product_show_categories_mobile']) && $params['product_show_categories_mobile'] == '1')) && (
											($params['caption_position'] == 'image' && ($params['image_hover_effect_image'] == 'slide' || $params['image_hover_effect_image'] == 'fade')) ||
											($params['caption_position'] == 'hover' && ($params['image_hover_effect_hover'] == 'slide' || $params['image_hover_effect_hover'] == 'fade')))) {
										if ($product->post_type == 'product_variation') {
											$terms = get_the_terms($product->get_parent_id(), 'product_cat');
										} else {
											$terms = get_the_terms($product->get_id(), 'product_cat');
										}
										if ($terms) {
											foreach ($terms as $term) {
												$term_links[] = '<a href="#" data-filter-type="category" data-filter="' . $term->slug . '">' . $term->name . '</a>';
											}
											if (!empty($term_links)) {
												echo '<div class="categories">' . implode(', ', $term_links) . '</div>';
											}
										}
									} ?>
									<div class="actions woocommerce_before_shop_loop_item_title">
										<?php do_action('woocommerce_before_shop_loop_item_title'); ?>
									</div>
									<?php if ($params['product_show_title'] == '1') {
										$title = $params['title_preset'] ?? '';
										if (isset($params['title_weight'])) {
											$title .= ' ' . $params['title_weight'];
										} ?>
										<div class="title title-h4">
											<a class="<?php echo $title; ?>" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
										</div>
									<?php } ?>

									<div class="product-info clearfix <?php if ($params['product_show_add_to_cart'] == '1' && $params['add_to_cart_type'] == 'button') {
										echo 'with-button';
									} ?>">
										<div class="actions woocommerce_shop_loop_item_title">
											<?php do_action('woocommerce_shop_loop_item_title'); ?>
										</div>
										<?php if ($params['product_show_price'] == '1') {
											$price = $params['price_preset'] ?? 'default'; ?>
											<div class="price-wrap <?php echo $price; ?>"><?php woocommerce_template_loop_price(); ?></div>
										<?php } ?>
										<div class="actions woocommerce_after_shop_loop_item_title">
											<?php do_action('woocommerce_after_shop_loop_item_title'); ?>
										</div>
										<?php if (($params['product_show_categories'] == '1' ||
												(isset($params['product_show_categories_tablet']) && $params['product_show_categories_tablet'] == '1') ||
												(isset($params['product_show_categories_mobile']) && $params['product_show_categories_mobile'] == '1')) && (
												($params['caption_position'] == 'hover' && $params['image_hover_effect_hover'] != 'slide' && $params['image_hover_effect_hover'] != 'fade') ||
												($params['caption_position'] == 'image' && $params['image_hover_effect_image'] != 'slide' && $params['image_hover_effect_image'] != 'fade'))) {
											if ($product->post_type == 'product_variation') {
												$terms = get_the_terms($product->get_parent_id(), 'product_cat');
											} else {
												$terms = get_the_terms($product->get_id(), 'product_cat');
											}
											if ($terms) {
												foreach ($terms as $term) {
													$term_links[] = '<a href="#" data-filter-type="category" data-filter="' . $term->slug . '">' . $term->name . '</a>';
												}
												if (!empty($term_links)) {
													echo '<div class="categories">' . implode(', ', $term_links) . '</div>';
												}
											}
										} ?>
										<?php if ($params['product_show_add_to_cart'] == '1' && $params['add_to_cart_type'] == 'button') { ?>
											<!-- Add to cart -->
											<?php if ($product->is_in_stock()): ?>
												<?php if ($product->get_type() === 'variable') { ?>
													<a href="<?php echo $product->add_to_cart_url() ?>"
													   data-quantity="1"
													   class="cart button product_type_<?php echo $product->get_type(); ?> add_to_cart_button type_button <?php echo $add_to_cart_class; ?>"
													   rel="nofollow">
														<?php
														if ($params['cart_button_show_icon'] == '1') {
															if (isset($params['select_options_pack']) && $params['select_options_icon_' . $params['select_options_pack']] != '') {
																echo thegem_build_icon($params['select_options_pack'], $params['select_options_icon_' . $params['select_options_pack']]);
															} else { ?>
																<i class="default"></i>
															<?php }
															echo '<span class="space"></span>';
														} ?>
														<span><?php echo esc_html($params['select_options_button_text']); ?></span>
													</a>
												<?php }

												if ($product->get_type() === 'simple' || $show_swatches) { ?>
													<a href="<?php echo $product->add_to_cart_url() ?>"
													   data-quantity="1"
													   class="cart button product_type_simple add_to_cart_button ajax_add_to_cart type_button <?php echo $add_to_cart_class; ?>"
													   data-product_id="<?php echo $product->get_id(); ?>"
													   data-product_sku="<?php echo $product->get_sku(); ?>"
													   aria-label="<?php echo 'Add ' . $product->get_name() . ' to your cart'; ?>"
													   rel="nofollow">
														<?php
														if ($params['cart_button_show_icon'] == '1') {
															if (isset($params['cart_button_pack']) && $params['cart_icon_' . $params['cart_button_pack']] != '') {
																echo thegem_build_icon($params['cart_button_pack'], $params['cart_icon_' . $params['cart_button_pack']]);
															} else { ?>
																<i class="default"></i>
															<?php }
															echo '<span class="space"></span>';
														} ?>
														<span><?php echo esc_html($params['cart_button_text']); ?></span>
													</a>
												<?php } ?>
											<?php endif; ?>
										<?php } ?>
										<?php if ($params['product_show_reviews'] == '1' ||
											(isset($params['product_show_reviews_tablet']) && $params['product_show_reviews_tablet'] == '1') ||
											(isset($params['product_show_reviews_mobile']) && $params['product_show_reviews_mobile'] == '1')) { ?>
											<div class="reviews"><?php woocommerce_template_loop_rating(); ?></div>
										<?php } ?>
										<div class="actions woocommerce_after_shop_loop_item">
											<?php do_action('woocommerce_after_shop_loop_item'); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

					<a class="product-link" href="<?php echo get_permalink(); ?>"></a>
					<?php if ($params['quick_view'] == '1' && $params['caption_position'] == 'page') {
						echo '<span class="quick-view-button title-h6" data-product-id="' . $post->ID . '">' . $params["quick_view_text"] . '</span>';
					} ?>
				</div>

				<?php if ($hover_effect !== 'disabled' && $params['caption_position'] == 'page' && (($params['product_show_add_to_cart'] != 'yes' && $params['product_show_add_to_cart'] != '1') || $params['add_to_cart_type'] == 'button')) { ?>
					<div class="portfolio-icons product-bottom on-page-caption empty">
						<div class="icons-top">
							<?php thegem_get_wishlist_share_icons($params, $post_id); ?>
						</div>
					</div>
				<?php } ?>

				<div class="labels-outer <?php echo ($params['caption_position'] == 'page' && $params['labels_design'] == '2') ? 'relative' : ''; ?>">
					<div class="product-labels style-<?php echo esc_attr($params['labels_design']); ?>">
						<?php
						$svg = '';
						if ($params['labels_design'] == 4) {
							$svg = '<svg height="100%" viewBox="0 0 4 19" preserveAspectRatio="none" shape-rendering="geometricPrecision"><polygon points="0,0 0,19 4,0 "/></svg>';
						} ?>
						<?php if ($params['product_show_out'] == '1' && !$product->is_in_stock()) : ?>
							<?php echo apply_filters('thegem_woocommerce_out_of_stock_flash', '<span class="label out-of-stock-label title-h6"><span class="rotate-back"><span class="text">' . $params['out_label_text'] . '</span></span>'.$svg.'</span>', $post, $product); ?>
						<?php endif; ?>
						<?php if ($params['product_show_sale'] == '1' && $product->is_on_sale()) : ?>
							<?php if ($params['sale_label_type'] == 'percentage') {
								$percentage = '';
								if ($product->get_type() === 'variable') {
									$children = array_filter(array_map('wc_get_product', $product->get_children()), 'wc_products_array_filter_visible_grouped');
									foreach ($children as $child) {
										$regular_price = (float)$child->get_regular_price();
										$sale_price = (float)$child->get_sale_price();
										if (!empty($sale_price)) {
											$percentage = round(100 - ($sale_price / $regular_price * 100));
											continue;
										}
									}
								} else {
									$regular_price = (float)$product->get_regular_price();
									$sale_price = (float)$product->get_sale_price();
									if (!empty($sale_price) && $sale_price != 0) {
										$percentage = round(100 - ($sale_price / $regular_price * 100));
									}

								}
								$sale_text = $params['sale_label_prefix'] . $percentage . $params['sale_label_suffix'];
							} else {
								$sale_text = $params['sale_label_text'];
							} ?>
							<?php echo apply_filters('woocommerce_sale_flash', '<span class="label onsale title-h6"><span class="rotate-back"><span class="text">' . $sale_text . '</span></span>'.$svg.'</span>', $post, $product); ?>
						<?php endif; ?>
						<?php if ($params['product_show_new'] == '1' && $product->is_featured()) : ?>
							<?php echo apply_filters('thegem_woocommerce_featured_flash', '<span class="label new-label title-h6"><span class="rotate-back"><span class="text">' . $params['new_label_text'] . '</span></span>'.$svg.'</span>', $post, $product); ?>
						<?php endif; ?>
					</div>
				</div>

				<?php if ($show_swatches && $product->is_type('variable')) { ?>
					<div class="variations-notification text-body-tiny"></div>
				<?php } ?>
			</div>

			<?php if (($params['caption_position'] == 'page')): ?>
				<div <?php post_class(array('caption')); ?>>
					<div class="product-info clearfix">
						<div class="actions woocommerce_before_shop_loop_item_title">
							<?php do_action('woocommerce_before_shop_loop_item_title'); ?>
						</div>
						<?php if ($params['product_show_reviews'] == '1' ||
							(isset($params['product_show_reviews_tablet']) && $params['product_show_reviews_tablet'] == '1') ||
							(isset($params['product_show_reviews_mobile']) && $params['product_show_reviews_mobile'] == '1')) { ?>
							<div class="reviews"><?php woocommerce_template_loop_rating(); ?></div>
						<?php } ?>
						<?php if ($params['product_show_categories'] == '1' ||
							(isset($params['product_show_categories_tablet']) && $params['product_show_categories_tablet'] == '1') ||
							(isset($params['product_show_categories_mobile']) && $params['product_show_categories_mobile'] == '1')) {
							if ($product->post_type == 'product_variation') {
								$terms = get_the_terms($product->get_parent_id(), 'product_cat');
							} else {
								$terms = get_the_terms($product->get_id(), 'product_cat');
							}
							if ($terms) {
								foreach ($terms as $term) {
									$term_links[] = '<a href="#" data-filter-type="category" data-filter="' . $term->slug . '">' . $term->name . '</a>';
								}
								if (!empty($term_links)) {
									echo '<div class="categories">' . implode(', ', $term_links) . '</div>';
								}
							}
						} ?>
						<?php if ($params['product_show_title'] == '1') {
							$title = $params['title_preset'] ?? '';
							if (isset($params['title_weight'])) {
								$title .= ' ' . $params['title_weight'];
							} ?>
							<div class="title"><a class="<?php echo $title; ?>" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></div>
						<?php } ?>
						<?php if (isset($params['layout_type']) && $params['layout_type'] == 'list' && $params['product_show_description'] == '1') { ?>
							<div class="description">
								<?php if ($product_short_description) {
									if ( isset( $params['description_preset'] ) && $params['description_preset'] != 'default' ) {
										$description = $params['description_preset'];
									} else {
										$description = '';
									} ?>
									<div class="subtitle"><span class="<?php echo $description; ?>"><?php echo $product_short_description; ?></span></div>
								<?php } ?>
							</div>
						<?php } ?>
						<div class="actions woocommerce_shop_loop_item_title">
							<?php do_action('woocommerce_shop_loop_item_title'); ?>
						</div>
						<div class="actions woocommerce_after_shop_loop_item_title">
							<?php do_action('woocommerce_after_shop_loop_item_title'); ?>
						</div>

						<?php if ($show_swatches && $product->is_type('variable') && $inline_list_layout) {
							thegem_get_swatches_attributes($product, $params['repeater_swatches']);
						} ?>
					</div>
					<div class="list-right">
						<?php if ($params['product_show_price'] == '1') {
							$price = $params['price_preset'] ?? 'default'; ?>
							<div class="price-wrap <?php echo $price; ?>"><?php woocommerce_template_loop_price(); ?></div>
						<?php } ?>

						<div class="portfolio-icons product-bottom on-page-caption clearfix <?php echo $params['product_show_add_to_cart'] != '1' ? 'empty' : ''; ?> <?php echo $product_bottom_class; ?>">
							<?php if ($params['product_show_add_to_cart'] == '1') {
								if ($params['add_to_cart_type'] == 'icon') { ?>
									<!-- Add to cart -->
									<?php if ($product->is_in_stock()): ?>
										<?php if ($product->get_type() === 'variable') { ?>
											<a href="<?php echo $product->add_to_cart_url() ?>"
											   data-quantity="1"
											   class="icon cart button product_type_<?php echo $product->get_type(); ?> add_to_cart_button <?php echo $add_to_cart_class; ?>"
											   rel="nofollow">
												<?php
												if (isset($params['select_options_pack']) && $params['select_options_icon_' . $params['select_options_pack']] != '') {
													echo thegem_build_icon($params['select_options_pack'], $params['select_options_icon_' . $params['select_options_pack']]);
												} else { ?>
													<i class="default"></i>
												<?php } ?>
												<?php echo esc_html($product->add_to_cart_text()) ?>
											</a>
										<?php }

										if ($product->get_type() === 'simple' || $show_swatches) { ?>
											<a href="<?php echo $product->add_to_cart_url() ?>"
											   data-quantity="1"
											   class="icon cart button product_type_simple add_to_cart_button ajax_add_to_cart <?php echo $add_to_cart_class; ?>"
											   data-product_id="<?php echo $product->get_id(); ?>"
											   data-product_sku="<?php echo $product->get_sku(); ?>"
											   aria-label="<?php echo 'Add ' . $product->get_name() . ' to your cart'; ?>"
											   rel="nofollow">
												<?php
												if (isset($params['cart_button_pack']) && $params['cart_icon_' . $params['cart_button_pack']] != '') {
													echo thegem_build_icon($params['cart_button_pack'], $params['cart_icon_' . $params['cart_button_pack']]);
												} else { ?>
													<i class="default"></i>
												<?php } ?>
												<?php echo esc_html($product->add_to_cart_text()) ?>
											</a>
										<?php } ?>
									<?php endif; ?>
								<?php } else if ($params['add_to_cart_type'] == 'button') { ?>

									<!-- Add to cart -->
									<?php if ($product->is_in_stock()): ?>
										<?php if ($product->get_type() === 'variable') { ?>
											<a href="<?php echo $product->add_to_cart_url() ?>"
											   data-quantity="1"
											   class="cart button product_type_<?php echo $product->get_type(); ?> add_to_cart_button type_button <?php echo $add_to_cart_class; ?>"
											   rel="nofollow">
												<?php
												if ($params['cart_button_show_icon'] == '1') {
													if (isset($params['select_options_pack']) && $params['select_options_icon_' . $params['select_options_pack']] != '') {
														echo thegem_build_icon($params['select_options_pack'], $params['select_options_icon_' . $params['select_options_pack']]);
													} else { ?>
														<i class="default"></i>
													<?php }
													echo '<span class="space"></span>';
												} ?>
												<span><?php echo esc_html($params['select_options_button_text']); ?></span>
											</a>
										<?php }

										if ($product->get_type() === 'simple' || $show_swatches) { ?>
											<a href="<?php echo $product->add_to_cart_url() ?>"
											   data-action="woocommerce_ajax_add_to_cart"
											   data-quantity="1"
											   class="cart button product_type_simple add_to_cart_button ajax_add_to_cart type_button <?php echo $add_to_cart_class; ?>"
											   data-product_id="<?php echo $product->get_id(); ?>"
											   data-product_sku="<?php echo $product->get_sku(); ?>"
											   aria-label="<?php echo 'Add ' . $product->get_name() . ' to your cart'; ?>"
											   rel="nofollow">
												<?php
												if ($params['cart_button_show_icon'] == '1') {
													if (isset($params['cart_button_pack']) && $params['cart_icon_' . $params['cart_button_pack']] != '') {
														echo thegem_build_icon($params['cart_button_pack'], $params['cart_icon_' . $params['cart_button_pack']]);
													} else { ?>
														<i class="default"></i>
													<?php }
													echo '<span class="space"></span>';
												} ?>
												<span><?php echo esc_html($params['cart_button_text']); ?></span>
											</a>
										<?php } ?>
									<?php endif;
								}
							}

							if ($params['product_show_add_to_cart'] == '1' && $params['add_to_cart_type'] != 'button') {
								thegem_get_wishlist_share_icons($params, $post_id);
							} ?>
						</div>

						<?php if ($show_swatches && $product->is_type('variable') && !$inline_list_layout) {
							thegem_get_swatches_attributes($product, $params['repeater_swatches']);
						} ?>

						<div class="actions woocommerce_after_shop_loop_item">
							<?php do_action('woocommerce_after_shop_loop_item'); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
<?php else:
	array_push($thegem_classes, 'size-item'); ?>
	<div <?php post_class($thegem_classes); ?>>
	</div>
<?php endif; ?>
