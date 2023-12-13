<?php

ob_start();
$filter_attr = vc_param_group_parse_atts($params['repeater_attributes']);
$filter_attr_numeric = [];
foreach ($filter_attr as $index => $item) {
	$terms = false;
	$attributes_url = $portfolios_filters_meta_url;
	if ($item['attribute_type'] == 'taxonomies') {
		if (empty($item['attribute_taxonomies']) || !array_key_exists($item['attribute_taxonomies'], $taxonomies_list)) continue;
		if (isset($taxonomy_filter[$item['attribute_taxonomies']])) {
			$terms = $taxonomy_filter[$item['attribute_taxonomies']];
			foreach ($terms as $key => $term) {
				$terms[$key] = get_term_by('slug', $term, $item['attribute_taxonomies'] );
				if (!$terms[$key]) {
					unset($terms[$key]);
				}
			}
		} else {
			$term_args = [
				'taxonomy' => $item['attribute_taxonomies'],
				'orderby' => $item['attribute_order_by'],
			];
			if (!empty($item['attribute_taxonomies_hierarchy'])) {
				$term_args['parent'] = 0;
			}
			$terms = get_terms($term_args);
		}
		$attribute_name = 'tax_' . $item['attribute_taxonomies'];
		$attributes_url = $portfolios_filters_tax_url;
	} else {
		$item['attribute_order_by'] = $item['attribute_order_by_details'];
		$item['attribute_query_type'] = $item['attribute_query_type_details'];
		if ($item['attribute_type'] == 'details') {
			if (empty($item['attribute_details'])) continue;
			$attribute_name = $item['attribute_details'];
		} else if ($item['attribute_type'] == 'custom_fields') {
			if (empty($item['attribute_custom_fields'])) continue;
			$attribute_name = $item['attribute_custom_fields'];
		} else {
			if (empty($item['attribute_custom_fields_acf_' . $item['attribute_type']])) continue;
			$attribute_name = $item['attribute_custom_fields_acf_' . $item['attribute_type']];
			$group_fields = acf_get_fields($item['attribute_type']);
			$found_key = array_search($attribute_name, array_column($group_fields, 'name'));
			$checkbox_field = get_field_object($group_fields[$found_key]['key']);
			if (isset($checkbox_field['choices'])) {
				$terms = $checkbox_field['choices'];
				if ($checkbox_field['type'] == 'checkbox') {
					$attribute_name .= '__check';
				}
			}
			$item['attribute_type'] = 'acf_fields';
		}
		if (empty($attribute_name) || !array_key_exists(str_replace('__check', '', $attribute_name), $meta_list)) continue;
		if (empty($terms)) {
			$terms = get_post_type_meta_values($attribute_name, $post_type);
		}
		$attribute_name = 'meta_' . $attribute_name;
	}
	if (!empty($terms) && !is_wp_error($terms)) {
		$is_dropdown = $params['filters_style'] !== 'standard' && isset($item['attribute_display_type']) && $item['attribute_display_type'] == 'dropdown';
		if ($item['attribute_type'] != 'taxonomies' && $item['attribute_meta_type'] == 'number') {
			wp_enqueue_script('jquery-touch-punch');
			wp_enqueue_script('jquery-ui-slider');
			$terms = array_map('floatval', $terms);
			$filter_attr_numeric[$attribute_name] = $item; ?>
			<div class="portfolio-filter-item price<?php
				echo $is_dropdown ? ' display-type-dropdown open-dropdown-' . $item['attribute_display_dropdown_open'] : ''; ?>">
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
							<span class="name">
								<?php if (empty($item['show_title'])) { ?>
									<span class="slider-amount-text"><?php echo esc_html($item['attribute_title']); ?>: </span>
								<?php } ?>
								<span class="slider-amount-value"></span>
							</span>
							<span class="widget-title-arrow"></span>
						</div>
				<?php } ?>
						<div class="portfolio-filter-item-list">
							<div class="price-range-slider">
								<div class="slider-range"
									 data-attr="<?php echo esc_attr($attribute_name); ?>"
									 data-min="<?php echo esc_attr(min($terms)); ?>"
									 data-max="<?php echo esc_attr(max($terms)); ?>"
									 data-prefix="<?php echo isset($item['attribute_price_format_prefix']) ? esc_attr($item['attribute_price_format_prefix']) : ''; ?>"
									 data-suffix="<?php echo isset($item['attribute_price_format_suffix']) ? esc_attr($item['attribute_price_format_suffix']) : ''; ?>"
									 <?php if ($item['attribute_price_format'] != 'disabled') { ?>data-locale="<?php echo esc_attr($item['attribute_price_format'] == 'wp_locale' ? get_locale() : $item['attribute_price_format_locale']); ?>"<?php }?>></div>
								<div class="slider-amount">
									<span class="slider-amount-text"><?php echo esc_html($item['attribute_title']); ?>: </span>
									<span class="slider-amount-value"></span>
								</div>
							</div>
						</div>
				<?php if ($is_dropdown) { ?>
					</div>
				<?php } ?>
			</div>
		<?php } else {
			$keys = array_keys($terms);
			$simple_arr = $keys == array_keys($keys);
			if ($item['attribute_order_by'] == 'name') {
				if ($simple_arr) {
					sort($terms);
				} else {
					asort($terms);
				}
			} ?>
			<div class="portfolio-filter-item attribute <?php
			echo esc_attr($attribute_name);
			echo strtolower($item['attribute_query_type']) == 'and' ? ' multiple' : ' single';
			echo $is_dropdown ? ' display-type-dropdown open-dropdown-' . $item['attribute_display_dropdown_open'] : ''; ?>">
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
								<?php if (!isset($attributes_url[$attribute_name])) { ?>
									<span data-filter="*"><?php echo esc_html($title); ?></span>
								<?php } else {
									foreach ($terms as $key => $term) {
										$term_slug = isset($term->slug) ? $term->slug : ($simple_arr ? $term : $key);
										$term_title = isset($term->name) ? $term->name : $term;
										if (in_array($term_slug, $attributes_url[$attribute_name])) {
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
								<li>
									<a href="#"
									   data-filter-type="<?php echo esc_attr($item['attribute_type']); ?>"
									   data-attr="<?php echo esc_attr($attribute_name); ?>"
									   data-filter="*"
									   class="all <?php echo !isset($attributes_url[$attribute_name]) ? 'active' : '' ?>"
									   rel="nofollow">
										<?php if ($item['attribute_query_type'] == 'or') {
											echo '<span class="check"></span>';
										} ?>
										<span class="title"><?php echo $params['filters_text_labels_all_text']; ?></span>
									</a>
								</li>
								<?php thegem_print_attributes_list($terms, $item, $attribute_name, $attributes_url); ?>
							</ul>
						</div>
				<?php if ($is_dropdown) { ?>
					</div>
				<?php } ?>
			</div>
		<?php }
	}
}

$filters_list = ob_get_clean();
if (!empty($filters_list) || $params['show_search']) { ?>

<div class="portfolio-filters-list style-<?php echo esc_attr($params['filters_style']); ?> <?php echo $params['filters_scroll_top'] ? 'scroll-top' : ''; ?> <?php echo $has_right_panel ? 'has-right-panel' : ''; ?>">
	<div class="portfolio-show-filters-button with-icon">
		<?php echo esc_html($params['filter_buttons_hidden_show_text']); ?>
		<?php if ($params['filter_buttons_hidden_show_icon']) { ?>
			<span class="portfolio-show-filters-button-icon"></span>
		<?php } ?>
	</div>

	<div class="portfolio-filters-outer">
		<div class="portfolio-filters-area">
			<div class="portfolio-filters-area-scrollable">
				<h2 class="light"><?php echo esc_html($params['filter_buttons_hidden_sidebar_title']); ?></h2>
				<div class="widget-area-wrap">
					<div class="portfolio-filters-extended widget-area">
						<?php if ($params['show_search']) { ?>
							<div class="portfolio-filter-item with-search-filter">
								<form class="portfolio-search-filter<?php
								echo !empty($params['live_search']) ? ' live-search' : '';
								echo $params['search_reset_filters'] ? ' reset-filters' : ''; ?>"
									role="search" action="">
									<div class="portfolio-search-filter-form">
										<input type="search"
											   placeholder="<?php echo esc_attr($params['filters_text_labels_search_text']); ?>"
											   value="<?php echo esc_attr($search_current); ?>">
									</div>
									<div class="portfolio-search-filter-button"></div>
								</form>
							</div>
						<?php }

						echo $filters_list;

						$preset_path = __DIR__ . '/selected-filters.php';
						if (!empty($preset_path) && file_exists($preset_path)) {
							include($preset_path);
						} ?>
					</div>
				</div>
			</div>
		</div>
		<div class="portfolio-close-filters"></div>
	</div>
</div>
<?php }
