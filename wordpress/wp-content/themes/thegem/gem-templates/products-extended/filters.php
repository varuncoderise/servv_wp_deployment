<?php $search_only = true;
if ($params['product_show_filter'] == '1' && (
		($params['filter_by_categories'] == '1' && count($terms) > 0) ||
		$params['filter_by_attribute'] == '1' ||
		$params['filter_by_price'] == '1' ||
		$params['filter_by_status'] == '1'
	)):
	$search_only = false;

	if ($params['filter_by_categories'] == '1' && $params['filter_by_attribute'] != '1' && $params['filter_by_price'] != '1' && $params['filter_by_status'] != '1' && $params['filter_by_categories_hierarchy'] != '1') {
		$single_filter = true;
	} else {
		$single_filter = false;
	}

	if ($params['filter_by_categories'] == '1' || $params['filter_by_attribute'] == '1' || $params['filter_by_status'] == '1') {
		$counts = thegem_extended_products_get_counts($params, $featured_only, $sale_only, $stock_only, $taxonomy_filter_current['product_cat'], $attributes_current, $price_current, $search_current);
	}
	?>

	<div class="portfolio-filters-list sidebar normal style-<?php echo esc_attr($params['filters_style']); ?> <?php echo $single_filter ? 'single-filter' : ''; ?> <?php echo $params['filters_scroll_top'] == '1' ? 'scroll-top' : ''; ?> <?php echo $has_right_panel ? 'has-right-panel' : ''; ?>">

		<div class="portfolio-show-filters-button <?php echo $params['filter_buttons_hidden_show_icon'] == '1' ? 'with-icon' : ''; ?>">
			<?php echo esc_html($params['filter_buttons_hidden_show_text']); ?>
			<?php if ($params['filter_buttons_hidden_show_icon'] == '1') { ?>
				<span class="portfolio-show-filters-button-icon"></span>
			<?php } ?>
		</div>

		<div class="portfolio-filters-outer">
			<div class="portfolio-filters-area <?php if (isset($params['filter_buttons_hidden_sidebar_overlay_shadow']) && $params['filter_buttons_hidden_sidebar_overlay_shadow'] == '1') {
				echo 'shadowed';
			} ?>">
				<div class="portfolio-filters-area-scrollable">
					<h2 class="light"><?php echo esc_html($params['filter_buttons_hidden_sidebar_title']); ?></h2>
					<div class="widget-area-wrap">
						<div class="page-sidebar widget-area" role="complementary">
						<?php if ($params['filter_by_search'] == '1') { ?>
							<div class="portfolio-filter-item with-search-filter">
								<form class="portfolio-search-filter" role="search" action="">
									<div class="portfolio-search-filter-form">
										<input type="search"
											   placeholder="<?php echo esc_attr($params['filters_text_labels_search_text']); ?>"
											   value="<?php echo esc_attr($search_current); ?>">
									</div>
									<div class="portfolio-search-filter-button"></div>
								</form>
							</div>
						<?php }
						$filters_arr = [];

						if ($params['filter_by_categories'] == '1' && count($terms) > 0 && !is_tax('product_tag')) {
							$is_dropdown = $params['filters_style'] !== 'standard' && isset($params['filter_by_categories_display_type']) && $params['filter_by_categories_display_type'] == 'dropdown';
							ob_start(); ?>
							<div class="portfolio-filter-item cats<?php echo ((isset($queried->taxonomy) && $queried->taxonomy == 'product_cat')) ? ' reload' : '';
							echo $is_dropdown ? ' display-type-dropdown open-dropdown-' . $params['filter_by_categories_display_dropdown_open'] : ''; ?>">
								<?php if ((isset($params['filter_by_categories_show_title']) && $params['filter_by_categories_show_title'] == '1') || (!$is_dropdown && $params['filters_style'] == 'standard')) { ?>
									<h4 class="name widget-title">
										<span class="widget-title-by">
											<?php echo esc_html($params['filter_buttons_hidden_filter_by_text']); ?>
										</span>
										<?php echo esc_html($params['filter_by_categories_title']); ?>
										<span class="widget-title-arrow"></span>
									</h4>
								<?php } ?>
								<?php if ($is_dropdown) { ?>
									<div class="dropdown-selector">
										<div class="selector-title">
											<?php $title = empty($params['filter_by_categories_show_title']) ? $params['filter_by_categories_title'] : str_replace('%ATTR%', $params['filter_by_categories_title'], $params['filters_text_labels_all_text']); ?>
											<span class="name" data-title="<?php echo esc_attr($title); ?>">
												<?php if ($active_cat == 'all') { ?>
													<span data-filter="*"><?php echo esc_html($title); ?></span>
												<?php } else {
													$active_cat_term = get_term_by('slug', $active_cat, 'product_cat'); ?>
													<span data-filter="<?php echo esc_attr($active_cat); ?>"><?php echo $active_cat_term->name; ?></span>
												<?php } ?>
											</span>
											<span class="widget-title-arrow"></span>
										</div>
								<?php } ?>
										<?php $filter_by_categories_count = $params['filter_by_categories_count'] == '1';
										$filter_by_categories_hierarchy = $params['filter_by_categories_hierarchy'] == '1';
										$filter_by_categories_collapsible = $params['filter_by_categories_collapsible'] == '1'; ?>
										<div class="portfolio-filter-item-list<?php echo $filter_by_categories_collapsible ? ' portfolio-filter-item-collapsible' : ''; ?>">
											<ul>
												<li>
													<a href="#" data-filter-type="category"
													   data-filter="*"
													   class="all <?php echo $active_cat == 'all' ? 'active' : '' ?>"
													   rel="nofollow">
														<span class="title"><?php echo esc_html($params['filters_text_labels_all_text']); ?></span>
													</a>
												</li>
												<?php thegem_print_terms_list($terms, false, $counts, $active_cat, $cat_args, $filter_by_categories_count, $filter_by_categories_hierarchy, $filter_by_categories_collapsible); ?>
											</ul>
										</div>
								<?php if ($is_dropdown) { ?>
									</div>
								<?php } ?>
							</div>
						<?php
							$order = isset($params['filter_by_categories_order']) && $params['filter_by_categories_order'] != '' ? $params['filter_by_categories_order'] : 0;
							$filters_arr[$order] = ob_get_clean();
						}

						if ($params['filter_by_attribute'] == '1') {
							ob_start();
							$filter_attr = vc_param_group_parse_atts($params['repeater_attributes']);
							foreach ($filter_attr as $index => $item) {
								if (empty($item['attribute_name'])) {
									continue;
								}
								$attribute_data = wc_get_attribute(wc_attribute_taxonomy_id_by_name('pa_'.$item['attribute_name']));
								if (!empty($attribute_data)) {
									$has_attribute_type = $attribute_data->type == 'color' || $attribute_data->type == 'label';
									$attribute_type_class = $has_attribute_type ? ' attribute-type-'.$attribute_data->type : '';
									if ($params['filter_by_attribute_hide_null']) {
										$empty_params = array();
									} else {
										$empty_params = array(
											'hide_empty' => false,
										);
									}
									$terms = get_terms('pa_' . $item['attribute_name'], $empty_params);
									$is_dropdown = $params['filters_style'] !== 'standard' && isset($item['attribute_display_type']) && $item['attribute_display_type'] == 'dropdown'; ?>
										<div class="portfolio-filter-item attribute <?php
										echo esc_attr($item['attribute_name']);
										echo strtolower($item['attribute_query_type']) == 'and' ? ' multiple' : ' single';
										echo $is_dropdown ? ' display-type-dropdown open-dropdown-' . $item['attribute_display_dropdown_open'] : '';
										echo esc_attr($attribute_type_class); ?>">
										<?php if ((!empty($item['show_title']) && !empty($item['attribute_title'])) || (!$is_dropdown && $params['filters_style'] == 'standard')) { ?>
											<h4 class="name widget-title">
												<span class="widget-title-by">
													<?php echo esc_html($params['filter_buttons_hidden_filter_by_text']); ?>
												</span>
												<?php echo esc_html($item['attribute_title']); ?>
												<span class="widget-title-arrow"></span>
											</h4>
										<?php } ?>
										<?php if ($is_dropdown) { ?>
											<div class="dropdown-selector">
												<div class="selector-title">
													<?php $title = empty($item['show_title']) ? $item['attribute_title'] : str_replace('%ATTR%', $item['attribute_title'], $params['filters_text_labels_all_text']); ?>
													<span class="name" data-title="<?php echo esc_attr($title); ?>">
														<?php if (!isset($filters_attributes_url[$item['attribute_name']])) { ?>
															<span data-filter="*"><?php echo esc_html($title); ?></span>
														<?php } else {
															foreach ($terms as $term) {
																$term_slug = isset($term->slug) ? $term->slug : $term;
																$term_title = isset($term->name) ? $term->name : $term;
																if (in_array($term_slug, $filters_attributes_url[$item['attribute_name']])) {
																	echo '<span data-filter="' . $term_slug . '">' . $term_title . '<span class="separator">, </span></span>';
																}
															}
														} ?>
													</span>
													<span class="widget-title-arrow"></span>
												</div>
												<?php } ?>
												<div class="portfolio-filter-item-list">
												<ul>
													<li<?php if ($has_attribute_type) { echo ' style="display: none;"'; }?>>
														<a href="#"
														   data-filter-type="attribute"
														   data-attr="<?php echo esc_attr($item['attribute_name']); ?>"
														   data-filter="*"
														   class="all <?php echo !isset($filters_attributes_url[$item['attribute_name']]) ? 'active' : '' ?>"
														   rel="nofollow">
															<?php if ($item['attribute_query_type'] == 'or' && !$has_attribute_type) {
																echo '<span class="check"></span>';
															} ?>
															<span class="title"><?php echo esc_html($params['filters_text_labels_all_text']); ?></span>
														</a>
													</li>
													<?php
													if (!empty($terms) && !is_wp_error($terms)) {
														foreach ($terms as $term) :
															if (isset($attributes[$item['attribute_name']])) {
																$attr_arr = $attributes[$item['attribute_name']];
																if (!in_array('0', $attr_arr, true) && !empty($attr_arr)) {
																	if (!in_array($term->slug, $attr_arr, true)) {
																		continue;
																	}
																}
															};
															$count = isset($counts[$term->term_id]) ? $counts[$term->term_id] : 0;
															if ($params['filter_by_attribute_hide_null'] == '1' && $count == 0) {
																continue;
															}
															?>
															<li><a href="#"
																   data-filter-type="attribute"
																   data-attr="<?php echo esc_attr($item['attribute_name']); ?>"
																   data-filter="<?php echo esc_attr($term->slug); ?>"
																   data-filter-id="<?php echo esc_attr($term->term_id); ?>"
																   class="<?php echo isset($filters_attributes_url[$item['attribute_name']]) && in_array($term->slug, $filters_attributes_url[$item['attribute_name']]) ? 'active' : '';
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
																	<?php if ($item['attribute_query_type'] == 'or' && !$has_attribute_type) {
																		echo '<span class="check"></span>';
																	} ?>
																	<span class="title"><?php echo esc_html($term_title); ?></span>
																	<?php if ($params['filter_by_attribute_count'] == '1') { ?>
																		<span class="count"><?php echo esc_html($count); ?></span>
																	<?php } ?>
																</a>
															</li>
														<?php endforeach;
													}
													?>
												</ul>
											</div>
										<?php if ($is_dropdown) { ?>
											</div>
										<?php } ?>
										</div>
									<?php }
							}
							$order = isset($params['filter_by_attribute_order']) && $params['filter_by_attribute_order'] != '' ? $params['filter_by_attribute_order'] : 0;
							if (isset($filters_arr[$order])) {
								$filters_arr[$order] .= ob_get_clean();
							} else {
								$filters_arr[$order] = ob_get_clean();
							}
						}

						if ($params['filter_by_price'] == '1') {
							$is_dropdown = $params['filters_style'] !== 'standard' && isset($params['filter_by_price_display_type']) && $params['filter_by_price_display_type'] == 'dropdown';
							ob_start();
							$price_range = thegem_extended_products_get_product_price_range($product_loop); ?>
							<div class="portfolio-filter-item price<?php echo $is_dropdown ? ' display-type-dropdown open-dropdown-' . $params['filter_by_price_display_dropdown_open'] : ''; ?>">
								<?php if ((isset($params['filter_by_price_show_title']) && $params['filter_by_price_show_title'] == '1') || (!$is_dropdown && $params['filters_style'] == 'standard')) { ?>
									<h4 class="name widget-title">
										<span class="widget-title-by">
											<?php echo esc_html($params['filter_buttons_hidden_filter_by_text']); ?>
										</span>
										<?php echo esc_html($params['filter_by_price_title']); ?>
										<span class="widget-title-arrow"></span>
									</h4>
								<?php } ?>
								<?php if ($is_dropdown) { ?>
									<div class="dropdown-selector">
										<div class="selector-title">
											<span class="name">
												<?php if (!isset($params['filter_by_price_show_title']) || $params['filter_by_price_show_title'] !== '1') { ?>
													<span class="slider-amount-text"><?php echo esc_html($params['filter_by_price_title']); ?>: </span>
												<?php } ?>
												<span class="slider-amount-value"></span>
											</span>
											<span class="widget-title-arrow"></span>
										</div>
								<?php } ?>
										<div class="portfolio-filter-item-list">
											<div class="price-range-slider">
												<div class="slider-range"
													 data-min="<?php echo esc_attr($price_range['min']); ?>"
													 data-max="<?php echo esc_attr($price_range['max']); ?>"
													 data-currency="<?php echo esc_attr(get_woocommerce_currency_symbol()); ?>"
													 data-currency-position="<?php echo esc_attr(get_option('woocommerce_currency_pos')); ?>"
													 data-thousand-separator="<?php echo esc_attr(get_option('woocommerce_price_thousand_sep')); ?>"
													 data-decimal-separator="<?php echo esc_attr(get_option('woocommerce_price_decimal_sep')); ?>"></div>
												<div class="slider-amount">
													<span class="slider-amount-text"><?php echo esc_html__('Price:', 'thegem'); ?> </span>
													<span class="slider-amount-value"></span>
												</div>
											</div>
										</div>
								<?php if ($is_dropdown) { ?>
									</div>
								<?php } ?>
							</div>
						<?php
							$order = isset($params['filter_by_price_order']) && $params['filter_by_price_order'] != '' ? $params['filter_by_price_order'] : 0;
							if (isset($filters_arr[$order])) {
								$filters_arr[$order] .= ob_get_clean();
							} else {
								$filters_arr[$order] = ob_get_clean();
							}
						}

						if ($params['filter_by_status'] == '1') {
							$is_dropdown = $params['filters_style'] !== 'standard' && isset($params['filter_by_status_display_type']) && $params['filter_by_status_display_type'] == 'dropdown';
							ob_start(); ?>
							<div class="portfolio-filter-item status multiple<?php echo $is_dropdown ? ' display-type-dropdown open-dropdown-' . $params['filter_by_status_display_dropdown_open'] : ''; ?>">
								<?php if ((isset($params['filter_by_status_show_title']) && $params['filter_by_status_show_title'] == '1') || (!$is_dropdown && $params['filters_style'] == 'standard')) { ?>
									<h4 class="name widget-title">
										<span class="widget-title-by">
											<?php echo esc_html($params['filter_buttons_hidden_filter_by_text']); ?>
										</span>
										<?php echo esc_html($params['filter_by_status_title']); ?>
										<span class="widget-title-arrow"></span>
									</h4>
								<?php } ?>
								<?php if ($is_dropdown) { ?>
									<div class="dropdown-selector">
										<div class="selector-title">
											<?php $title = empty($params['filter_by_status_show_title']) ? $params['filter_by_status_title'] : str_replace('%ATTR%', $params['filter_by_status_title'], $params['filters_text_labels_all_text']); ?>
											<span class="name" data-title="<?php echo esc_attr($title); ?>">
												<?php if (!$sale_only && !$stock_only) { ?>
													<span data-filter="*"><?php echo esc_html($title); ?></span>
												<?php } else {
													if ($sale_only) {
														echo '<span data-filter="sale">' . esc_html($params['filter_by_status_sale_text']) . '<span class="separator">, </span></span>';
													}
													if ($stock_only) {
														echo '<span data-filter="stock">' . esc_html($params['filter_by_status_stock_text']) . '<span class="separator">, </span></span>';
													}
												} ?>
											</span>
											<span class="widget-title-arrow"></span>
										</div>
								<?php } ?>
										<div class="portfolio-filter-item-list">
											<ul>
												<li>
													<a href="#" data-filter="*"
													   data-filter-type="status"
													   class="all <?php echo ($sale_only || $stock_only) ? '' : 'active'; ?>"
													   rel="nofollow">
														<span class="title"><?php echo esc_html($params['filters_text_labels_all_text']); ?></span>
													</a>
												</li>
												<?php if ($params['filter_by_status_sale'] == '1') {
													$count = $counts['sale']; ?>
													<li>
														<a href="#"
														   data-filter-type="status"
														   data-filter="sale"
														   data-filter-id="sale"
														   class="<?php echo $sale_only ? 'active' : '';
														   if ($count == 0) { echo ' disable'; } ?>"
														   rel="nofollow">
															<span class="title"><?php echo esc_html($params['filter_by_status_sale_text']); ?></span>
															<?php if ($params['filter_by_status_count'] == '1') { ?>
																<span class="count"><?php echo esc_html($count); ?></span>
															<?php } ?>
														</a>
													</li>
												<?php }
												if ($params['filter_by_status_stock'] == '1') {
													$count = $counts['stock']; ?>
													<li>
														<a href="#"
														   data-filter-type="status"
														   data-filter="stock"
														   data-filter-id="stock"
														   class="<?php echo $stock_only ? 'active' : '';
														   if ($count == 0) { echo ' disable'; } ?>"
														   rel="nofollow">
															<span class="title"><?php echo esc_html($params['filter_by_status_stock_text']); ?></span>
															<?php if ($params['filter_by_status_count'] == '1') { ?>
																<span class="count"><?php echo esc_html($count); ?></span>
															<?php } ?>
														</a>
													</li>
												<?php } ?>
											</ul>
										</div>
								<?php if ($is_dropdown) { ?>
									</div>
								<?php } ?>
							</div>
						<?php
							$order = isset($params['filter_by_status_order']) && $params['filter_by_status_order'] != '' ? $params['filter_by_status_order'] : 0;
							if (isset($filters_arr[$order])) {
								$filters_arr[$order] .= ob_get_clean();
							} else {
								$filters_arr[$order] = ob_get_clean();
							}
						}

						if (!empty($filters_arr)) {
							ksort($filters_arr);

							foreach ($filters_arr as $filter) {
								echo $filter;
							}
						}

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