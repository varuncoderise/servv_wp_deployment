<div class="portfolio-filter-item portfolio-selected-filters <?php if (isset($params['duplicate_selected_in_sidebar']) && !$params['duplicate_selected_in_sidebar'] && $params['duplicate_selected_in_sidebar'] != '1') {
	echo 'hide-on-sidebar';
} ?> <?php echo esc_attr($params['filter_buttons_standard_alignment']); ?>">
	<div class="portfolio-selected-filter-item clear-filters">
		<?php echo esc_attr($params['filters_text_labels_clear_text']); ?>
	</div>
	<?php if (!empty($attributes_filter)) {
		foreach ($attributes_filter as $key => $value) {
			if (strpos($key, "__range") > 0) {
				$key = str_replace("__range","", $key);
				$prefix = $suffix = '';
				if (isset($filter_attr_numeric) && $filter_attr_numeric[$key]) {
					$item = $filter_attr_numeric[$key];
					$prefix = $item['attribute_price_format_prefix'];
					$suffix = $item['attribute_price_format_suffix'];
				} ?>
				<div class="portfolio-selected-filter-item price" data-attr="<?php echo esc_attr($key); ?>">
					<?php echo('<div>' . $prefix . '<span>' . $value[0] . '</span>' . $suffix . ' - ' . $prefix . '<span>' . $value[1] . '</span>' . $suffix . '</div>'); ?>
					<i class="delete-filter"></i>
				</div>
			<?php } else {
				foreach ($value as $attr_value) { ?>
					<div class="portfolio-selected-filter-item attribute" data-attr="<?php echo esc_attr($key); ?>"
						 data-filter="<?php echo esc_attr($attr_value); ?>">
						<?php if (strpos($key, 'tax_') === 0) {
							$term = get_term_by('slug', $attr_value,  str_replace("tax_","", $key));
							echo esc_html($term->name);
						} else {
							echo esc_html($attr_value);
						} ?><i class="delete-filter"></i>
					</div>
				<?php }
			}
		}
	}
	if (!empty($search_current)) { ?>
		<div class="portfolio-selected-filter-item search"><?php echo esc_html($search_current); ?><i
					class="delete-filter"></i></div>
	<?php } ?>
</div>