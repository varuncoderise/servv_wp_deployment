<?php
function portfolio_grid_more_callback() {
	$settings = isset($_POST['data']) ? json_decode(stripslashes($_POST['data']), true) : array();
	ob_start();
	$response = array('status' => 'success');
	$page = isset($settings['more_page']) ? intval($settings['more_page']) : 1;
	if ($page == 0)
		$page = 1;
	$portfolio_loop = thegem_get_portfolio_posts($settings['content_portfolios_cat'], $page, $settings['items_per_page'], $settings['orderby'], $settings['order']);
	if ($portfolio_loop->max_num_pages > $page)
		$next_page = $page + 1;
	else
		$next_page = 0;

	$item_classes = get_thegem_portfolio_render_item_classes($settings);
	$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($settings);
	?>
	<div data-page="<?php echo esc_attr($page); ?>" data-next-page="<?php echo esc_attr($next_page); ?>" data-pages-count="<?php echo esc_attr($portfolio_loop->max_num_pages); ?>">
		<?php
		if ($settings['layout'] == 'creative') {
			$creative_blog_schemes_list = [
				'6' => [
					'6a' => [
						'count' => 9,
						0 => 'squared',
					],
					'6b' => [
						'count' => 7,
						0 => 'squared',
						1 => 'horizontal',
						6 => 'horizontal',
					],
					'6c' => [
						'count' => 9,
						0 => 'horizontal',
						3 => 'horizontal',
						6 => 'horizontal',
					],
					'6d' => [
						'count' => 9,
						0 => 'horizontal',
						1 => 'horizontal',
						2 => 'horizontal',
					],
					'6e' => [
						'count' => 6,
						0 => 'squared',
						1 => 'squared',
					]
				],
				'5' => [
					'5a' => [
						'count' => 7,
						0 => 'squared',
					],
					'5b' => [
						'count' => 8,
						0 => 'horizontal',
						4 => 'horizontal',
					],
					'5c' => [
						'count' => 6,
						0 => 'horizontal',
						1 => 'horizontal',
						4 => 'horizontal',
						5 => 'horizontal',
					],
					'5d' => [
						'count' => 4,
						0 => 'squared',
						1 => 'vertical',
						2 => 'horizontal',
						3 => 'horizontal',
					]
				],
				'4' => [
					'4a' => [
						'count' => 5,
						0 => 'squared',
					],
					'4b' => [
						'count' => 4,
						0 => 'squared',
						1 => 'horizontal',
					],
					'4c' => [
						'count' => 4,
						0 => 'squared',
						1 => 'vertical',
					],
					'4d' => [
						'count' => 7,
						0 => 'vertical',
					],
					'4e' => [
						'count' => 4,
						0 => 'vertical',
						1 => 'vertical',
						2 => 'horizontal',
						3 => 'horizontal',
					],
					'4f' => [
						'count' => 6,
						0 => 'horizontal',
						5 => 'horizontal',
					]
				],
				'3' => [
					'3a' => [
						'count' => 4,
						0 => 'vertical',
						1 => 'vertical',
					],
					'3b' => [
						'count' => 4,
						1 => 'horizontal',
						2 => 'horizontal',
					],
					'3c' => [
						'count' => 5,
						0 => 'vertical',
					],
					'3d' => [
						'count' => 5,
						0 => 'horizontal',
					],
					'3e' => [
						'count' => 3,
						0 => 'squared',
					],
					'3f' => [
						'count' => 4,
						0 => 'horizontal',
						1 => 'vertical',
					],
					'3g' => [
						'count' => 4,
						0 => 'vertical',
						3 => 'horizontal',
					],
					'3h' => [
						'count' => 5,
						2 => 'vertical',
					]
				],
				'2' => [
					'2a' => [
						'count' => 5,
						0 => 'vertical',
					],
					'2b' => [
						'count' => 5,
						3 => 'vertical',
					],
					'2c' => [
						'count' => 4,
						0 => 'vertical',
						2 => 'vertical',
					],
					'2d' => [
						'count' => 4,
						0 => 'horizontal',
						1 => 'vertical',
					],
					'2e' => [
						'count' => 5,
						0 => 'horizontal',
					],
					'2f' => [
						'count' => 4,
						0 => 'horizontal',
						1 => 'horizontal',
					],
					'2g' => [
						'count' => 5,
						2 => 'horizontal',
					],
					'2h' => [
						'count' => 4,
						0 => 'horizontal',
						3 => 'horizontal',
					],
				]
			];
			$columns = $settings['columns'] != '100%' ? str_replace("x", "", $settings['columns']) : $settings['columns_100'];
			$items_sizes = $creative_blog_schemes_list[$columns][$settings['layout_scheme_' . $columns . 'x']];
			$items_count = $items_sizes['count'];
		}
		$i = 0;
		while ($portfolio_loop->have_posts()) : $portfolio_loop->the_post();
			$thegem_highlight_type_creative = null;
			if ($settings['layout'] == 'creative') {
				$thegem_highlight_type_creative = 'disabled';
				$item_num = $i % $items_count;
				if (isset($items_sizes[$item_num])) {
					$thegem_highlight_type_creative = $items_sizes[$item_num];
				}
			}
			echo thegem_portfolio_grid_render_item($settings, $item_classes, $thegem_sizes, get_the_ID(), $thegem_highlight_type_creative);
			if ($settings['layout'] == 'creative' && $i == 0) {
				echo thegem_portfolio_grid_render_item($settings, ['size-item'], $thegem_sizes);
			}
			$i++;
		endwhile; ?>
	</div>
	<?php $response['html'] = trim(preg_replace('/\s\s+/', '', ob_get_clean()));
	$response = json_encode($response);
	header("Content-Type: application/json");
	echo $response;
	exit;
}
add_action('wp_ajax_portfolio_grid_load_more', 'portfolio_grid_more_callback');
add_action('wp_ajax_nopriv_portfolio_grid_load_more', 'portfolio_grid_more_callback');

function thegem_get_portfolio_posts($portfolios_cat, $page = 1, $ppp = -1, $orderby = 'menu_order ID', $order = 'ASC', $offset = false, $exclude = false) {
	if (empty($portfolios_cat)) {
		return null;
	}

	$args = array(
		'post_type' => 'thegem_pf_item',
		'post_status' => 'publish',
		'orderby' => $orderby,
		'order' => $order,
		'posts_per_page' => $ppp,
	);

	if (!in_array('0', $portfolios_cat, true)) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'thegem_portfolios',
				'field' => 'slug',
				'terms' => $portfolios_cat
			)
		);
	}

	if (!empty($offset)) {
		$args['offset'] = $ppp * ($page - 1) + $offset;
	} else {
		$args['paged'] = $page;
	}

	if (!empty($exclude)) {
		$args['post__not_in'] = $exclude;
	}

	$portfolio_loop = new WP_Query($args);

	return $portfolio_loop;
}