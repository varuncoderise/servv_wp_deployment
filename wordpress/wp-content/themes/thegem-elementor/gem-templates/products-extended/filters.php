<?php $search_only = true;
if ($params['product_show_filter'] == 'yes' || $params['product_show_filter'] == '1' && (
		(($params['filter_by_categories'] == 'yes' || $params['filter_by_categories'] == '1') && count($terms) > 0) ||
		$params['filter_by_attribute'] == 'yes' || $params['filter_by_attribute'] == '1' ||
		$params['filter_by_price'] == 'yes' || $params['filter_by_price'] == '1' ||
		$params['filter_by_status'] == 'yes' || $params['filter_by_status'] == '1'
	)):
	$search_only = false;

	if (($params['filter_by_categories'] == 'yes' || $params['filter_by_categories'] == '1') &&
		($params['filter_by_attribute'] != 'yes' && $params['filter_by_attribute'] != '1') &&
		($params['filter_by_price'] != 'yes' && $params['filter_by_price'] != '1') &&
		($params['filter_by_status'] != 'yes' && $params['filter_by_status'] != '1') &&
		($params['filter_by_categories_hierarchy'] != 'yes' && $params['filter_by_categories_hierarchy'] != '1')) {
		$single_filter = true;
	} else {
		$single_filter = false;
	}

	if (($params['filter_by_categories'] == 'yes' || $params['filter_by_categories'] == '1') ||
		($params['filter_by_attribute'] == 'yes' || $params['filter_by_attribute'] == '1') ||
		($params['filter_by_status'] == 'yes' || $params['filter_by_status'] == '1')) {
		$counts = thegem_extended_products_get_counts($params, $featured_only, $sale_only, $stock_only, $categories_current, $attributes_current, $price_current, $search_current);
	}
	?>

	<div class="portfolio-filters-list sidebar normal style-<?php echo esc_attr($params['filters_style']); ?> <?php echo $single_filter ? 'single-filter' : ''; ?> <?php echo ($params['filters_scroll_top'] == 'yes' || $params['filters_scroll_top'] == '1') ? 'scroll-top' : ''; ?> <?php echo $has_right_panel ? 'has-right-panel' : ''; ?>">

		<div class="portfolio-show-filters-button <?php echo ($params['filter_buttons_hidden_show_icon'] == 'yes' || $params['filter_buttons_hidden_show_icon'] == '1') ? 'with-icon' : ''; ?>">
			<?php echo esc_html($params['filter_buttons_hidden_show_text']); ?>
			<?php if ($params['filter_buttons_hidden_show_icon'] == 'yes' || $params['filter_buttons_hidden_show_icon'] == '1') { ?>
				<span class="portfolio-show-filters-button-icon"></span>
			<?php } ?>
		</div>

		<div class="portfolio-filters-outer">
			<div class="portfolio-filters-area <?php if (isset($params['filter_buttons_hidden_sidebar_overlay_shadow']) && ($params['filter_buttons_hidden_sidebar_overlay_shadow'] == 'yes' || $params['filter_buttons_hidden_sidebar_overlay_shadow'] == '1')) {
				echo 'shadowed';
			} ?>">
				<div class="portfolio-filters-area-scrollable">
					<h2 class="light"><?php echo esc_html($params['filter_buttons_hidden_sidebar_title']); ?></h2>
					<div class="widget-area-wrap">
						<div class="page-sidebar widget-area" role="complementary">
							<?php if ($params['filter_by_search'] == 'yes' || $params['filter_by_search'] == '1') { ?>
								<form class="portfolio-search-filter" role="search" action="">
									<div class="portfolio-search-filter-form">
										<input type="search"
											   placeholder="<?php echo esc_attr($params['filters_text_labels_search_text']); ?>"
											   value="<?php echo esc_attr($search_current); ?>">
									</div>
									<div class="portfolio-search-filter-button"></div>
								</form>
							<?php } ?>
							<?php if (($params['filter_by_categories'] == 'yes' || $params['filter_by_categories'] == '1') && count($terms) > 0 && !is_tax('product_tag')) { ?>
								<div class="portfolio-filter-item cats <?php echo ((isset($queried->taxonomy) && $queried->taxonomy == 'product_cat')) ? 'reload' : ''; ?>">
									<h4 class="name widget-title"><span
												class="widget-title-by"><?php echo esc_html($params['filter_buttons_hidden_filter_by_text']); ?> </span><?php echo esc_html($params['filter_by_categories_title']);
										?><span class="widget-title-arrow"></span>
									</h4>
									<div class="portfolio-filter-item-list">
										<ul>
											<li>
												<a href="#" data-filter-type="category"
												   data-filter="*"
												   class="all <?php echo $active_cat == 'all' ? 'active' : '' ?>"
												   rel="nofollow">
													<span class="title"><?php echo esc_html($params['filters_text_labels_all_text']); ?></span>
												</a>
											</li>
											<?php
											$filter_by_categories_count = $params['filter_by_categories_count'] == 'yes' || $params['filter_by_categories_count'] == '1';
											$filter_by_categories_hierarchy = $params['filter_by_categories_hierarchy'] == 'yes' || $params['filter_by_categories_hierarchy'] == '1';
											thegem_print_terms_list($terms, false, $counts, $active_cat, $cat_args, $filter_by_categories_count, $filter_by_categories_hierarchy); ?>
										</ul>
									</div>
								</div>
							<?php } ?>

							<?php if ($params['filter_by_attribute'] == 'yes' || $params['filter_by_attribute'] == '1') {
								$filter_attr = $params['repeater_attributes'];
								foreach ($filter_attr as $index => $item) {
									if (empty($item['attribute_name'])) {
										continue;
									}
									$attribute_data = wc_get_attribute(wc_attribute_taxonomy_id_by_name('pa_'.$item['attribute_name']));
									if (!empty($attribute_data)) {
										$attribute_type_class = $attribute_data->type == 'color' || $attribute_data->type == 'label' ? ' attribute-type-'.$attribute_data->type : '';
										if (!empty($item['attribute_name'])) { ?>
											<div class="portfolio-filter-item attribute <?php echo esc_attr($item['attribute_name']) . ' '; ?><?php echo strtolower($item['attribute_query_type']) == 'and' ? 'multiple' : 'single'; ?><?php echo esc_attr($attribute_type_class); ?>">
												<h4 class="name widget-title"><span
															class="widget-title-by"><?php echo esc_html($params['filter_buttons_hidden_filter_by_text']); ?> </span><?php echo esc_html($item['attribute_title']);
													?><span class="widget-title-arrow"></span>
												</h4>
												<div class="portfolio-filter-item-list">
													<ul>
														<li<?php if($attribute_data->type == 'color' || $attribute_data->type == 'label') { echo ' style="display: none;"'; }?>>
															<a href="#"
															   data-filter-type="attribute"
															   data-attr="<?php echo esc_attr($item['attribute_name']); ?>"
															   data-filter="*"
															   class="all <?php echo !isset($attributes_url[$item['attribute_name']]) ? 'active' : '' ?>"
															   rel="nofollow">
																<span class="title"><?php echo esc_html($params['filters_text_labels_all_text']); ?></span>
															</a>
														</li>
														<?php
														if ($params['filter_by_attribute_hide_null'] == 'yes' || $params['filter_by_attribute_hide_null'] == '1') {
															$empty_params = array();
														} else {
															$empty_params = array(
																'hide_empty' => false,
															);
														}
														$terms = get_terms('pa_' . $item['attribute_name'], $empty_params);
														if (!empty($terms) && !is_wp_error($terms)) {
															foreach ($terms as $term) :
																if (isset($attributes[$item['attribute_name']])) {
																	$attr_arr = $attributes[$item['attribute_name']];
																	if (!empty($attr_arr) && !in_array('0', $attr_arr, true)) {
																		if (!in_array($term->slug, $attr_arr, true)) {
																			continue;
																		}
																	}
																};
																$count = isset($counts[$term->term_id]) ? $counts[$term->term_id] : 0;
																if (($params['filter_by_attribute_hide_null'] == 'yes' || $params['filter_by_attribute_hide_null'] == '1') && $count == 0) {
																	continue;
																}
																?>
																<li><a href="#"
																	   data-filter-type="attribute"
																	   data-attr="<?php echo esc_attr($item['attribute_name']); ?>"
																	   data-filter="<?php echo esc_attr($term->slug); ?>"
																	   data-filter-id="<?php echo esc_attr($term->term_id); ?>"
																	   class="<?php echo isset($attributes_url[$item['attribute_name']]) && in_array($term->slug, $attributes_url[$item['attribute_name']]) ? 'active' : '';
																       if ($count == 0) {
																	       echo ' disable';
																       } ?>"
																	   rel="nofollow">
																		<?php if($attribute_data->type == 'color') {
																			$attribute_color = get_term_meta( $term->term_id, 'thegem_color', true );
																			echo '<span class="color"' . (!empty($attribute_color) ? ' style="background-color: ' . esc_attr($attribute_color).';"' : '') . '></span>';
																		} ?>
																		<?php $term_title = $term->name;
																		if ($attribute_data->type == 'label') {
																			$attribute_label = get_term_meta( $term->term_id, 'thegem_label', true );
																			$term_title = !empty($attribute_label) ? $attribute_label : $term_title;
																		}
																		?>
																		<span class="title"><?php echo esc_html($term_title); ?></span>
																		<?php if ($params['filter_by_attribute_count'] == 'yes' || $params['filter_by_attribute_count'] == '1') { ?>
																			<span class="count"><?php echo esc_html($count); ?></span>
																		<?php } ?>
																	</a>
																</li>
															<?php endforeach;
														}
														?>
													</ul>
												</div>
											</div>
										<?php }
									}
								}
							} ?>

							<?php if ($params['filter_by_price'] == 'yes' || $params['filter_by_price'] == '1') {
								$price_range = thegem_extended_products_get_product_price_range($featured_only, $sale_only, $categories_current, $attributes_current); ?>
								<div class="portfolio-filter-item price">
									<h4 class="name widget-title"><span
												class="widget-title-by"><?php echo esc_html($params['filter_buttons_hidden_filter_by_text']); ?> </span><?php echo esc_html($params['filter_by_price_title']);
										?><span class="widget-title-arrow"></span>
									</h4>
									<div class="portfolio-filter-item-list">
										<div class="price-range-slider">
											<div class="slider-range"
												 data-min="<?php echo esc_attr($price_range['min']); ?>"
												 data-max="<?php echo esc_attr($price_range['max']); ?>"
												 data-currency="<?php echo esc_attr(get_woocommerce_currency_symbol()); ?>"
												 data-currency-position="<?php echo esc_attr(get_option('woocommerce_currency_pos')); ?>"
												 data-thousand-separator="<?php echo esc_attr(get_option('woocommerce_price_thousand_sep')); ?>"
												 data-decimal-separator="<?php echo esc_attr(get_option('woocommerce_price_decimal_sep')); ?>"></div>
											<div class="slider-amount"><span
														class="slider-amount-text"><?php echo esc_html__('Price:', 'thegem'); ?> </span><span
														class="slider-amount-value"></span></div>
										</div>
									</div>
								</div>
							<?php } ?>

							<?php if ($params['filter_by_status'] == 'yes' || $params['filter_by_status'] == '1') { ?>
								<div class="portfolio-filter-item status multiple">
									<h4 class="name widget-title"><span
												class="widget-title-by"><?php echo esc_html($params['filter_buttons_hidden_filter_by_text']); ?> </span><?php echo esc_html($params['filter_by_status_title']);
										?><span class="widget-title-arrow"></span>
									</h4>
									<div class="portfolio-filter-item-list">
										<ul>
											<li>
												<a href="#" data-filter="*"
												   data-filter-type="status"
												   class="all <?php echo ($sale_only || $stock_only) ? '' : 'active'; ?>"
												   rel="nofollow"><?php echo esc_html($params['filters_text_labels_all_text']); ?>
												</a>
											</li>
											<?php if ($params['filter_by_status_sale'] == 'yes' || $params['filter_by_status_sale'] == '1') {
												$count = $counts['sale']; ?>
												<li><a href="#"
													   data-filter-type="status"
													   data-filter="sale"
													   data-filter-id="sale"
													   class="<?php echo $sale_only ? 'active' : '';
												       if ($count == 0) {
													       echo ' disable';
												       } ?>"
													   rel="nofollow">
														<span class="title"><?php echo esc_html($params['filter_by_status_sale_text']); ?></span>
														<?php if ($params['filter_by_status_count'] == 'yes' || $params['filter_by_status_count'] == '1') { ?>
															<span class="count"><?php echo esc_html($count); ?></span>
														<?php } ?>
													</a>
												</li>
											<?php }
											if ($params['filter_by_status_stock'] == 'yes' || $params['filter_by_status_stock'] == '1') {
												$count = $counts['stock']; ?>
												<li><a href="#"
													   data-filter-type="status"
													   data-filter="stock"
													   data-filter-id="stock"
													   class="<?php echo $stock_only ? 'active' : '';
												       if ($count == 0) {
													       echo ' disable';
												       } ?>"
													   rel="nofollow">
														<span class="title"><?php echo esc_html($params['filter_by_status_stock_text']); ?></span>
														<?php if ($params['filter_by_status_count'] == 'yes' || $params['filter_by_status_count'] == '1') { ?>
															<span class="count"><?php echo esc_html($count); ?></span>
														<?php } ?>
													</a>
												</li>
											<?php } ?>
										</ul>
									</div>
								</div>
							<?php }

							$preset_path = __DIR__ . '/selected-filters.php';
							if (!empty($preset_path) && file_exists($preset_path)) {
								include($preset_path);
							}

							if ($show_widgets) {
								dynamic_sidebar('shop-sidebar');
							} ?>
						</div>
					</div>
				</div>
			</div>
			<div class="portfolio-close-filters"></div>
		</div>

	</div>

<?php endif;
