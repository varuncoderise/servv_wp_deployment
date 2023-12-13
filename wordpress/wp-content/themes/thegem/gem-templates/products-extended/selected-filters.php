<div class="portfolio-filter-item portfolio-selected-filters <?php if (isset($params['duplicate_selected_in_sidebar']) && $params['duplicate_selected_in_sidebar'] != '1') {
	echo 'hide-on-sidebar';
} ?> <?php echo esc_attr($params['filter_buttons_standard_alignment']); ?>">
	<div class="portfolio-selected-filter-item clear-filters">
		<?php echo esc_html($params['filters_text_labels_clear_text']); ?>
	</div>
	<?php if (isset($_GET[$grid_uid_url . 'category'])) { ?>
		<div class="portfolio-selected-filter-item category"
			 data-filter="<?php echo esc_attr($active_cat); ?>"><?php $category = get_term_by('slug', $active_cat, 'product_cat');
			echo esc_html($category->name); ?> <i class="delete-filter"></i></div>
	<?php }
	if (!empty($attributes_filter)) {
		foreach ($attributes_filter as $key => $value) {
			if (strpos($key, "__range") > 0) {
				$key = str_replace("__range","", $key); ?>
				<div class="portfolio-selected-filter-item price" data-attr="<?php echo esc_attr($key); ?>">
					<?php echo('<div>' . $value[0] . ' - ' . $value[1] . '</div>'); ?>
					<i class="delete-filter"></i>
				</div>
			<?php } else {
				foreach ($value as $attr_value) { ?>
					<div class="portfolio-selected-filter-item attribute" data-attr="<?php echo esc_attr($key); ?>"
						 data-filter="<?php echo esc_attr($attr_value); ?>">
						<?php if (strpos($key, 'tax_') === 0) {
							$term = get_term_by('slug', $attr_value,  str_replace("tax_","", $key));
							echo esc_html($term->name);
						} else if (strpos($key, 'meta_') === 0) {
							echo esc_html($attr_value);
						} else {
							$term = get_term_by('slug', $attr_value, 'pa_' . $key);
							echo esc_html($term->name);
						} ?><i class="delete-filter"></i>
					</div>
				<?php }
			}
		}
	}
	if (isset($_GET[$grid_uid_url . 'status'])) {
		if (in_array('sale', $status_current)) { ?>
			<div class="portfolio-selected-filter-item status" data-filter="sale"><?php echo esc_html($params['filter_by_status_sale_text']); ?><i
						class="delete-filter"></i></div>
		<?php }
		if (in_array('stock', $status_current)) { ?>
			<div class="portfolio-selected-filter-item status" data-filter="stock"><?php echo esc_html($params['filter_by_status_stock_text']); ?>
				<i class="delete-filter"></i></div>
			<?php
		}
	}
	if (isset($_GET[$grid_uid_url . 'min_price']) || isset($_GET[$grid_uid_url . 'max_price'])) {
		$currency_pos = get_option('woocommerce_currency_pos');
		$space = '';
		$min_price = number_format($current_min_price, 0, get_option('woocommerce_price_decimal_sep') ,get_option('woocommerce_price_thousand_sep'));
		$max_price = number_format($current_max_price, 0, get_option('woocommerce_price_decimal_sep') ,get_option('woocommerce_price_thousand_sep'));
		if ($currency_pos == 'left' || $currency_pos == 'left_space') {
			if ($currency_pos == 'left_space') {
				$space = ' ';
			}
			$price = get_woocommerce_currency_symbol() . $space . $min_price . ' - ' . get_woocommerce_currency_symbol() . $space . $max_price;
		} else {
			if ($currency_pos == 'right_space') {
				$space = ' ';
			}
			$price =  $min_price . $space . get_woocommerce_currency_symbol() . ' - ' . $max_price . $space . get_woocommerce_currency_symbol();
		} ?>
		<div class="portfolio-selected-filter-item price"><?php echo esc_html($price); ?>
			<i class="delete-filter"></i></div>
		<?php
	}
	if (isset($_GET[$grid_uid_url . 's'])) { ?>
		<div class="portfolio-selected-filter-item search"><?php echo esc_html($search_current); ?><i class="delete-filter"></i></div>
	<?php } ?>
</div>