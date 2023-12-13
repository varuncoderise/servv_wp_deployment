<?php


function thegem_news_post_type_init() {
	if(!thegem_get_option('activate_news_posttype')) return ;
	$labels = array(
		'name'               => __('News', 'thegem'),
		'singular_name'      => __('News', 'thegem'),
		'menu_name'          => __('News', 'thegem'),
		'name_admin_bar'     => __('News', 'thegem'),
		'add_new'            => __('Add New', 'thegem'),
		'add_new_item'       => __('Add New News', 'thegem'),
		'new_item'           => __('New News', 'thegem'),
		'edit_item'          => __('Edit News', 'thegem'),
		'view_item'          => __('View News', 'thegem'),
		'all_items'          => __('All News', 'thegem'),
		'search_items'       => __('Search News', 'thegem'),
		'not_found'          => __('No news found.', 'thegem'),
		'not_found_in_trash' => __('No news found in Trash.', 'thegem')
	);


	$args = array(
		'labels'               => $labels,
		'public'               => true,
		'publicly_queryable'   => true,
		'show_ui'              => true,
		'query_var'            => true,
		'hierarchical'         => false,
		//'register_meta_box_cb' => 'thegem_post_items_register_meta_box',
		'taxonomies'           => array('thegem_news_sets'),
		'rewrite' => array('slug' => apply_filters('thegem_news_rewrite_slug', 'news'), 'with_front' => false),
		'capability_type' => 'post',
		'supports'             => array('title', 'author', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'comments', 'post-formats'),
		'menu_position'        => 38
	);

	register_post_type('thegem_news', $args);

	$labels = array(
		'name'                       => __('News Sets', 'thegem'),
		'singular_name'              => __('News Set', 'thegem'),
		'search_items'               => __('Search News Sets', 'thegem'),
		'popular_items'              => __('Popular News Sets', 'thegem'),
		'all_items'                  => __('All News Sets', 'thegem'),
		'edit_item'                  => __('Edit News Set', 'thegem'),
		'update_item'                => __('Update News Set', 'thegem'),
		'add_new_item'               => __('Add New News Set', 'thegem'),
		'new_item_name'              => __('New News Set Name', 'thegem'),
		'separate_items_with_commas' => __('Separate News Sets with commas', 'thegem'),
		'add_or_remove_items'        => __('Add or remove News Sets', 'thegem'),
		'choose_from_most_used'      => __('Choose from the most used News Sets', 'thegem'),
		'not_found'                  => __('No news sets found.', 'thegem'),
		'menu_name'                  => __('News Sets', 'thegem'),
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array('slug' => 'news_sets'),
	);

	register_taxonomy('thegem_news_sets', 'thegem_news', $args);
}
add_action('init', 'thegem_news_post_type_init', 5);

function thegem_options_rewrite_slug($value) {
	if(sanitize_title(thegem_get_option('news_rewrite_slug'))) {
		$value = sanitize_title(thegem_get_option('news_rewrite_slug'));
	}
	return $value;
}
add_filter('thegem_news_rewrite_slug', 'thegem_options_rewrite_slug');

function thegem_post_items_register_meta_box($post) {
	add_meta_box('thegem_news_item_settings', __('News Item Settings', 'thegem'), 'thegem_post_item_settings_box', 'thegem_news', 'normal', 'high');
}

add_action('wp_ajax_blog_load_more', 'blog_load_more_callback');
add_action('wp_ajax_nopriv_blog_load_more', 'blog_load_more_callback');
function blog_load_more_callback() {
	$params = isset($_POST['data']) ? $_POST['data'] : array();
	$response = array( 'status' => 'success' );

	add_filter('the_content', 'thegem_run_shortcode', 7);
	add_filter('the_excerpt', 'thegem_run_shortcode', 7);
	if(class_exists('WPBMap')) {
		WPBMap::addAllMappedShortcodes();
	}

	ob_start();

	$taxonomy_filter = $manual_selection = $blog_authors = $date_query = [];

	if ($params['query_type'] == 'post') {

		$post_type = 'post';
		if ($params['select_blog_categories'] && !empty($params['categories']) && !in_array('0', $params['categories'])) {
			$taxonomy_filter['category'] = $params['categories'];
		}
		if ($params['select_blog_tags'] && !empty($params['tags'])) {
			$taxonomy_filter['post_tag'] = $params['tags'];
		}
		if ($params['select_blog_posts'] && !empty($params['posts'])) {
			$manual_selection = $params['posts'];
		}
		if ($params['select_blog_authors'] && !empty($params['authors'])) {
			$blog_authors = $params['authors'];
		}
		$exclude = $params['exclude_blog_posts'];

	} else if ($params['query_type'] == 'related') {

		$post_type = isset($params['taxonomy_related_post_type']) ? $params['taxonomy_related_post_type'] : 'any';
		$taxonomy_filter = $params['related_tax_filter'];
		$exclude = $params['exclude_posts_manual'];

	} else if ($params['query_type'] == 'archive') {

		$post_type = $params['archive_post_type'];
		if (!empty($params['select_blog_authors'])) {
			$blog_authors = $params['select_blog_authors'];
		} else if (!empty($params['archive_tax_filter'])) {
			$taxonomy_filter = $params['archive_tax_filter'];
		} else if (!empty($params['date_query'])) {
			$date_query = $params['date_query'];
		}
		$exclude = $params['exclude_posts_manual'];

	} else if ($params['query_type'] == 'manual') {

		$post_type = 'any';
		$manual_selection = $params['select_posts_manual'];
		$exclude = $params['exclude_posts_manual'];

	} else {

		$post_type = $params['query_type'];
		foreach ($params['source_post_type_' . $post_type] as $source) {
			if ($source == 'all') {

			} else if ($source == 'manual') {
				$manual_selection = $params['source_post_type_' . $post_type . '_manual'];
			} else {
				$tax_terms = $params['source_post_type_' . $post_type . '_tax_' . $source];
				if (!empty($tax_terms)) {
					$taxonomy_filter[$source] = $tax_terms;
				}
			}
		}
		$exclude = $params['source_post_type_' . $post_type . '_exclude'];

	}

	$orderby = $order = '';
	if (isset($params['order_by']) && $params['order_by'] != 'default') {
		$orderby = $params['order_by'];
	}
	if (isset($params['order']) && $params['order'] != 'default') {
		$order = $params['order'];
	}

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$paged = $params['paged'];

	$posts = get_thegem_extended_blog_posts($post_type, $taxonomy_filter, [], $manual_selection, $exclude, $blog_authors, $paged, $params['post_per_page'], $orderby, $order, $params['offset'], $params['ignore_sticky'], false, false, $date_query);

	$next_page = 0;
	if( $posts->max_num_pages > $paged ) {
		$next_page = $paged + 1;
	} else {
		$next_page = 0;
	}
	?>

	<div data-page="<?php echo esc_attr( $paged ); ?>" data-paged="<?php echo esc_attr( $paged ); ?>" data-next-page="<?php echo esc_attr( $next_page ); ?>">
		<?php

		if ( $posts->have_posts() ) {
			$last_post_date = '';
			$blog_style = $params['style'];
			while ( $posts->have_posts() ) {
				$posts->the_post();
				if ($blog_style == '2x' || $blog_style == '3x' || $blog_style == '4x' || $blog_style == '100%') {
					include(locate_template(array('gem-templates/blog/content-blog-item-masonry.php', 'content-blog-item.php')));
				} elseif ($blog_style == 'justified-2x' || $blog_style == 'justified-3x' || $blog_style == 'justified-4x') {
					include(locate_template(array('gem-templates/blog/content-blog-item-justified.php', 'content-blog-item.php')));
				} else {
					include(locate_template(array('gem-templates/blog/content-blog-item-' . $blog_style . '.php', 'content-blog-item.php')));
				}
				$last_post_date = get_the_date("M Y");
			}
		}
		?>
	</div>

	<?php
	$response['html'] = trim( preg_replace( '/\s\s+/', ' ', ob_get_clean() ) );
	$response = json_encode( $response );
	header('Content-Type: application/json');
	echo $response;
	die;
}

function news_grid_load_more_callback() {
	$response = array();
	$data = isset($_POST['data']) ? $_POST['data'] : array();
	$data['is_ajax'] = true;
	$response = array('status' => 'success');
	add_filter('the_content', 'thegem_run_shortcode', 7);
	add_filter('the_excerpt', 'thegem_run_shortcode', 7);
	if(class_exists('WPBMap')) {
		WPBMap::addAllMappedShortcodes();
	}
	ob_start();
	thegem_news_grid($data);
	$response['html'] = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	$response = json_encode($response);
	header( "Content-Type: application/json" );
	echo $response;
	exit;
}
add_action('wp_ajax_news_grid_load_more', 'news_grid_load_more_callback');
add_action('wp_ajax_nopriv_news_grid_load_more', 'news_grid_load_more_callback');
