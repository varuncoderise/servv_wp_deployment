<?php
if (!function_exists('thegem_get_wishlist_share_icons')) {
	function thegem_get_wishlist_share_icons($params, $post_id) { ?>
		<!-- YITH -->
		<?php if (defined('YITH_WCWL') && $params['product_show_wishlist'] == '1'): ?>
			<span class="yith-icon">
				<?php
				if (isset($params['add_wishlist_icon_pack']) && !empty($params['add_wishlist_icon_' . str_replace("-", "", $params['add_wishlist_icon_pack'])])) {
					echo thegem_build_icon($params['add_wishlist_icon_pack'], $params['add_wishlist_icon_' . str_replace("-", "", $params['add_wishlist_icon_pack'])], 'add-wishlist-icon');
				} else { ?>
					<i class="add-wishlist-icon default"></i>
				<?php }
				if (isset($params['added_wishlist_icon_pack']) && !empty($params['added_wishlist_icon_' . str_replace("-", "", $params['added_wishlist_icon_pack'])])) {
					echo thegem_build_icon($params['added_wishlist_icon_pack'], $params['added_wishlist_icon_' . str_replace("-", "", $params['added_wishlist_icon_pack'])], 'added-wishlist-icon');
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

		if ($product->is_type('variable')) {
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
											if ($i == 0 && isset($item['attribute_show_name']) && ($item['attribute_show_name'] == 'yes' || $item['attribute_show_name'] == '1')) { ?>
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
		} else { ?>
			<div class="product-variations simple">
				<div class="variations" role="presentation">
					<?php
					foreach ($swatches_attr as $item) {
						$current_attribute_name = 'pa_' . $item['attribute_name'];
						$attribute_data = wc_get_attribute(wc_attribute_taxonomy_id_by_name($current_attribute_name));
						$terms = wc_get_product_terms(
							$product->get_id(),
							$current_attribute_name,
							array('fields' => 'all')
						);
						if (empty($terms)) continue; ?>
						<div id="<?php echo esc_attr($current_attribute_name); ?>"
							 class="gem-attribute-selector type-<?php echo esc_attr($attribute_data->type); ?>"
							 data-attribute_name="attribute_<?php echo esc_attr($current_attribute_name); ?>">
							<ul class="styled gem-attribute-options">
								<?php $i = 0;
								foreach ($terms as $term) {
									if ($i == 0 && isset($item['attribute_show_name']) && ($item['attribute_show_name'] == 'yes' || $item['attribute_show_name'] == '1')) { ?>
										<span class="attribute-name text-body-tiny"><?php echo $attribute_data->name; ?>:</span>
									<?php }
									if (isset($item['attribute_count']) && $i == $item['attribute_count']) { ?>
										<a class="more-variables text-body-tiny"
										   href="<?php echo $product->add_to_cart_url() ?>">+<?php echo count($terms) - $i; ?></a>
										<?php break;
									}
									$i++; ?>
									<li data-value="<?php echo $term->slug; ?>" class="text-body-tiny">
										<?php if ($attribute_data->type == 'color') {
											$attribute_color = get_term_meta($term->term_id, 'thegem_color', true); ?>
											<span class="color" <?php echo !empty($attribute_color) ? ' style="background-color: ' . esc_attr($attribute_color) . ';"' : ''; ?>></span>
											<span class="text"><?php echo $term->name; ?></span>
										<?php } else if ($attribute_data->type == 'label') {
											$label = get_term_meta($term->term_id, 'thegem_label', true);
											$label = empty($label) ? $term->name : $label; ?>
											<span class="label"><?php echo esc_html($label); ?></span>
											<span class="text"><?php echo esc_html(apply_filters('woocommerce_variation_option_name', $term->name, $term, $current_attribute_name, $product)); ?></span>
										<?php } else {
											echo esc_html(apply_filters('woocommerce_variation_option_name', $term->name, $term, $current_attribute_name, $product));
										} ?>
									</li>
								<?php } ?>
							</ul>
						</div>
						<?php
					} ?>
				</div>
			</div>
		<?php }

	}
}

if (!function_exists('thegem_get_add_to_cart_icon_text')) {
	function thegem_get_add_to_cart_icon_text($params, $type = 'simple') {
		$icon = '';
		if ($params['add_to_cart_type'] == 'icon' || $params['cart_button_show_icon'] == 'yes' || $params['cart_button_show_icon'] == '1') {
			$pack = $type == 'variable' ? 'select_options_icon_pack' : 'cart_button_icon_pack';
			$type_icon = $type == 'variable' ? 'select_options_icon_' : 'cart_button_icon_';
			if (isset($params[$pack]) && !empty($params[$type_icon . $params[$pack]])) {
				$icon = thegem_build_icon($params[$pack], $params[$type_icon . $params[$pack]]);
			} else {
				$icon = '<i class="default"></i>';
			}
			if ($params['add_to_cart_type'] == 'button') {
				$icon .= '<span class="space"></span>';
			}
		}
		$text = esc_html($type == 'variable' ? $params['select_options_button_text'] : $params['cart_button_text']);
		if ($params['add_to_cart_type'] == 'button') {
			$text = '<span>' .$text . '</span>';
		}

		return $icon . $text;
	}
}

if (!function_exists('thegem_get_add_to_cart_link')) {
	function thegem_get_add_to_cart_link($product, $params, $add_to_cart_args) {
		if (!isset($params['cart_hook']) || $params['cart_hook'] == '1' || $params['cart_hook'] == 'yes') {
			woocommerce_template_loop_add_to_cart($add_to_cart_args);
		} else {
			$defaults = array(
				'quantity'   => 1,
				'class'      => implode(
					' ',
					array_filter(
						array(
							'button',
							wc_wp_theme_get_element_class_name( 'button' ), // escaped in the template.
							'product_type_' . $product->get_type(),
							$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
							$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
						)
					)
				),
				'attributes' => array(
					'data-product_id'  => $product->get_id(),
					'data-product_sku' => $product->get_sku(),
					'aria-label'       => $product->add_to_cart_description(),
					'rel'              => 'nofollow',
				),
			);
			$args = wp_parse_args( $add_to_cart_args, $defaults );
			if (!empty($args['attributes']['aria-describedby'])) {
				$args['attributes']['aria-describedby'] = wp_strip_all_tags($args['attributes']['aria-describedby']);
			}
			if (isset($args['attributes']['aria-label'])) {
				$args['attributes']['aria-label'] = wp_strip_all_tags($args['attributes']['aria-label']);
			}
			echo sprintf(
				'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				isset( $args['text'] ) ? $args['text'] : esc_html($product->add_to_cart_text())
			);
		}
	}
}

if (!function_exists('thegem_get_add_to_cart')) {
	function thegem_get_add_to_cart($product, $add_to_cart_class, $params, $show_swatches) {
		$add_to_cart_args = [];
		$button_classes = implode(
			' ',
			array_filter(
				array(
					'cart',
					$params['add_to_cart_type'] == 'icon' ? 'icon' : 'type_button',
					$add_to_cart_class
				)
			)
		);

		if (in_array($product->get_type(), ['variable', 'external', 'grouped'])) {
			if ($product->get_type() === 'variable') {
				$add_to_cart_args['text'] = thegem_get_add_to_cart_icon_text($params, 'variable');
			} ?>
			<span class="variable-type-button <?php echo esc_attr($button_classes); ?>">
				<?php thegem_get_add_to_cart_link($product, $params, $add_to_cart_args); ?>
			</span>
			<?php if ($show_swatches) {
				$button_classes .= ' swatches-button';
			}
		}

		if ($product->get_type() === 'simple' || ($show_swatches && $product->get_type() === 'variable') || !in_array($product->get_type(), ['simple', 'variable', 'external', 'grouped'])) {
			$add_to_cart_args['text'] = thegem_get_add_to_cart_icon_text($params); ?>
			<span class="simple-type-button <?php echo esc_attr($button_classes); ?>">
				<?php thegem_get_add_to_cart_link($product, $params, $add_to_cart_args); ?>
			</span>
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
	$product_short_description = strip_shortcodes($product_short_description);
	$product_short_description = wp_strip_all_tags($product_short_description);
	$inline_list_layout = false;
	if ( isset($params['layout_type']) && $params['layout_type'] == 'list') {
		if (isset($params['caption_layout_list']) && $params['caption_layout_list'] == 'inline') {
			$inline_list_layout = true;
		}
	} else {
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
				} echo !has_post_thumbnail($post_id) ? ' without-image' : ''; ?>">
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
												<?php if ($product->is_in_stock()):
													thegem_get_add_to_cart($product, $add_to_cart_class, $params, $show_swatches);
												endif; ?>
											<?php } ?>

											<!-- YITH -->
											<?php if (defined('YITH_WCWL') && $params['product_show_wishlist'] == '1'): ?>
												<span class="icon yith-icon">
												<?php
												if (isset($params['add_wishlist_icon_pack']) && !empty($params['add_wishlist_icon_' . str_replace("-", "", $params['add_wishlist_icon_pack'])])) {
													echo thegem_build_icon($params['add_wishlist_icon_pack'], $params['add_wishlist_icon_' . str_replace("-", "", $params['add_wishlist_icon_pack'])], 'add-wishlist-icon');
												} else { ?>
													<i class="add-wishlist-icon default"></i>
												<?php }
												if (isset($params['added_wishlist_icon_pack']) && !empty($params['added_wishlist_icon_' . str_replace("-", "", $params['added_wishlist_icon_pack'])])) {
													echo thegem_build_icon($params['added_wishlist_icon_pack'], $params['added_wishlist_icon_' . str_replace("-", "", $params['added_wishlist_icon_pack'])], 'added-wishlist-icon');
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
									if ($show_swatches && ($product->is_type('variable') || (isset($params['attribute_swatches_simple']) && $params['attribute_swatches_simple'] == '1'))) {
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
										$title = isset($params['title_preset']) ? $params['title_preset'] : '';
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
											$price = isset($params['price_preset']) ? $params['price_preset'] : 'default'; ?>
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
											<?php if ($product->is_in_stock()):
												thegem_get_add_to_cart($product, $add_to_cart_class, $params, $show_swatches);
											endif; ?>
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

					<a class="product-link" href="<?php echo get_permalink(); ?>">
						<span class="screen-reader-text"><?php the_title(); ?></span>
					</a>
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
								$percentage = 0;
								if ($product->get_type() === 'variable') {
									$children = array_filter(array_map('wc_get_product', $product->get_children()), 'wc_products_array_filter_visible_grouped');
									foreach ($children as $child) {
										$regular_price = (float)$child->get_regular_price();
										$sale_price = (float)$child->get_sale_price();
										if (!empty($sale_price)) {
											$new_percentage = round(100 - ($sale_price / $regular_price * 100));
											if ($new_percentage > $percentage) {
												$percentage = $new_percentage;
											}
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
						<?php if ($params['product_show_new'] == '1' && thegem_product_need_new_label($product->get_id())) : ?>
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
							$title = isset($params['title_preset']) ? $params['title_preset'] : '';
							if (isset($params['title_weight'])) {
								$title .= ' ' . $params['title_weight'];
							} ?>
							<div class="title"><a class="<?php echo $title; ?>" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></div>
						<?php } ?>
						<?php if (isset($params['layout_type']) && $params['layout_type'] == 'list' && $params['product_show_description'] == '1') { ?>
							<div class="description">
								<?php if ($product_short_description) {
									if ( !empty( $params['description_preset'] ) && $params['description_preset'] != 'default' ) {
										$description = $params['description_preset'];
									} else {
										$description = 'text-body';
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

						<?php if ($show_swatches && ($product->is_type('variable') || (isset($params['attribute_swatches_simple']) && $params['attribute_swatches_simple'] == '1')) && $inline_list_layout) {
							thegem_get_swatches_attributes($product, $params['repeater_swatches']);
						} ?>
					</div>
					<div class="list-right">
						<?php if ($params['product_show_price'] == '1') {
							$price = isset($params['price_preset']) ? $params['price_preset'] : 'default'; ?>
							<div class="price-wrap <?php echo $price; ?>"><?php woocommerce_template_loop_price(); ?></div>
						<?php } ?>

						<div class="portfolio-icons product-bottom on-page-caption clearfix <?php echo $params['product_show_add_to_cart'] != '1' ? 'empty' : ''; ?> <?php echo $product_bottom_class; ?>">
							<?php if ($params['product_show_add_to_cart'] == '1') { ?>
								<!-- Add to cart -->
								<?php if ($product->is_in_stock()):
									thegem_get_add_to_cart($product, $add_to_cart_class, $params, $show_swatches);
								endif;
							}

							if ($params['product_show_add_to_cart'] == '1' && $params['add_to_cart_type'] != 'button') {
								thegem_get_wishlist_share_icons($params, $post_id);
							} ?>
						</div>

						<?php if ($show_swatches && ($product->is_type('variable') || (isset($params['attribute_swatches_simple']) && $params['attribute_swatches_simple'] == '1')) && !$inline_list_layout) {
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
