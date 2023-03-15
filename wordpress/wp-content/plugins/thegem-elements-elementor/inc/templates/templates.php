<?php

function thegem_templates_post_type_init(){
	global $pagenow, $typenow;
	$name = __('TheGem Templates', 'thegem');
	if(is_admin() && 'edit.php' === $pagenow && !empty($_REQUEST['templates_type'])) {
		$types = thegem_templates_types();
		if(!empty($types[$_REQUEST['templates_type']])) {
			$name = $types[$_REQUEST['templates_type']];
		}
	}
	$labels = array(
		'name' => $name,
		'singular_name' => __('Templates', 'thegem'),
		'menu_name' => __('Templates', 'thegem'),
		'name_admin_bar' => __('TheGem Template', 'thegem'),
		'add_new' => __('Add New', 'thegem'),
		'add_new_item' => __('Add New Template', 'thegem'),
		'new_item' => __('New Template', 'thegem'),
		'edit_item' => __('Edit Template', 'thegem'),
		'view_item' => __('View Template', 'thegem'),
		'all_items' => __('All Templates', 'thegem'),
		'search_items' => __('Search Templates', 'thegem'),
		'not_found' => __('No templates found.', 'thegem'),
		'not_found_in_trash' => __('No templates found in Trash.', 'thegem')
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'exclude_from_search' => true,
		'publicly_queryable' => current_user_can('edit_posts'),
		'show_ui' => true,
		'query_var' => false,
		'hierarchical' => false,
		'supports' => array('title', 'editor'),
		'show_in_menu' => false,
		'show_in_admin_bar' => true,
		'rewrite' => false,
		'register_meta_box_cb' => 'thegem_template_popup_register_meta_box',
	);

	register_post_type('thegem_templates', $args);
}
add_action('init', 'thegem_templates_post_type_init', 5);

function thegem_templates_title_footer_migrate() {
	$thegem_theme = wp_get_theme(wp_get_theme()->get('Template'));
	if(thegem_get_option('thegem_templates_migrated') || version_compare($thegem_theme->get('Version'), '5.3.0') < 0) return ;
	$titles_list = get_posts(array(
		'post_type' => 'thegem_title',
		'numberposts' => -1,
		'post_status' => 'any'
	));
	foreach ($titles_list as $title) {
		set_post_type($title->ID, 'thegem_templates');
		update_post_meta($title->ID, 'thegem_template_type', 'title');
	}
	$footers_list = get_posts(array(
		'post_type' => 'thegem_footer',
		'numberposts' => -1,
		'post_status' => 'any'
	));
	foreach ($footers_list as $footer) {
		set_post_type($footer->ID, 'thegem_templates');
		update_post_meta($footer->ID, 'thegem_template_type', 'footer');
	}
	$theme_options = get_option('thegem_theme_options');
	$theme_options['thegem_templates_migrated'] = 1;
	update_option('thegem_theme_options', $theme_options);
}
add_action('init', 'thegem_templates_title_footer_migrate', 5);

function thegem_templates_menu() {
	add_submenu_page('thegem-dashboard-welcome',esc_html__('Templates Builder','thegem'), esc_html__('Templates Builder','thegem'), 'edit_theme_options', 'edit.php?post_type=thegem_templates', '', 2);
}
add_action('admin_menu', 'thegem_templates_menu', 50);

function thegem_templates_types($with_content = true) {
	$types = array(
		'header' => __('Header', 'thegem'),
		'title' => __('Title Area', 'thegem'),
		'footer' => __('Footer', 'thegem'),
		'megamenu' => __('Mega Menu', 'thegem'),
		'popup' => __('Popups', 'thegem'),
		'blog-archive' => __('Blog Archives', 'thegem'),
		'single-post' => __('Single Post', 'thegem'),
		'single-product' => __('Single Product', 'thegem'),
		'product-archive' => __('Product Archives', 'thegem'),
		'cart' => __('Cart', 'thegem'),
		'checkout' => __('Checkout', 'thegem'),
		'checkout-thanks' => __('Purchase Summary', 'thegem'),
	);
	if($with_content) {
		$types['content'] = __('Section', 'thegem');
	}
	if(!defined('WC_PLUGIN_FILE')) {
		unset($types['single-product']);
		unset($types['product-archive']);
		unset($types['cart']);
		unset($types['checkout']);
		unset($types['checkout-thanks']);
	}
	return apply_filters('thegem_templates_types', $types);
}

function thegem_get_template_type($post_id = '') {
	$post = get_post($post_id);
	$templates_types = thegem_templates_types();
	if($post && get_post_type($post) === 'thegem_templates') {
		$meta = get_post_meta( $post_id, 'thegem_template_type', true );
		if(isset($templates_types[$meta])) {
			return $meta;
		} else{
			return 'content';
		}
	}
	return false;
}

function thegem_get_templates($types = '') {
	$args = array(
		'post_type' => 'thegem_templates',
		'post_status' => 'any',
		'orderby' => 'title',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if(!empty($types)) {
		if(is_string($types)) {
			$types = array($types);
		}
		if(is_array($types)) {
			$args['meta_query'] = array(
				array(
					'key' => 'thegem_template_type',
					'value' => $types,
					'compare' => 'in',
				),
			);
			/*if(in_array('content', $types)) {
				$args['meta_query'] = array_merge($args['meta_query'], array(
					'relation' => 'OR',
					array(
						'key' => 'thegem_template_type',
						'compare' => 'NOT EXISTS',
					),
				));
			}*/
		}
	}
	$templates_query = new WP_Query;
	return $templates_query->query($args);
}

function thegem_templates_admin_print_tabs( $views ) {
	$current_type = '';
	$active_class = ' nav-tab-active';
	$templates_types = thegem_templates_types(true);

	if(!empty($_REQUEST['templates_type']) && isset($templates_types[$_REQUEST['templates_type']])) {
		$current_type = $_REQUEST['templates_type'];
		$active_class = '';
	}

	$baseurl = add_query_arg(array('post_type' => 'thegem_templates'), admin_url('edit.php'));

	if ( 1 >= count( $templates_types ) ) {
		return $views;
	}

	?>
	<div id="thegem-templates-tabs-wrapper" class="nav-tab-wrapper">
		<a class="nav-tab<?php echo $active_class; ?>" href="<?php echo $baseurl; ?>">
			<?php _e('All', 'thegem'); ?>
		</a>
		<?php
			foreach($templates_types as $type => $title) {
				$active_class = '';
				if($current_type === $type) {
					$active_class = ' nav-tab-active';
				}
				$type_url = add_query_arg(array('templates_type' => $type), $baseurl );
				echo '<a class="nav-tab'.$active_class.'" href="'.$type_url.'">'.$title.'</a>';
			}
		?>
	</div>
	<?php
	return $views;
}
add_filter( 'views_edit-thegem_templates', 'thegem_templates_admin_print_tabs');

function thegem_templates_admin_columns_headers( $posts_columns ) {
	$offset = 2;
	$posts_columns = array_slice( $posts_columns, 0, $offset, true ) + [
		'thegem_templates_type' => esc_html__( 'Type', 'thegem' ),
	] + array_slice( $posts_columns, $offset, null, true );
	return $posts_columns;
}
add_action( 'manage_thegem_templates_posts_columns', 'thegem_templates_admin_columns_headers' );

function thegem_templates_admin_columns_content( $column_name, $post_id ) {
	if ( 'thegem_templates_type' === $column_name ) {
		$templates_types = thegem_templates_types();
		$type = thegem_get_template_type($post_id);
		$url = add_query_arg(array('post_type' => 'thegem_templates', 'templates_type' => $type), admin_url('edit.php'));
		echo '<a href="'.$url.'">'.$templates_types[$type].'</a>';
	}
}
add_action( 'manage_thegem_templates_posts_custom_column', 'thegem_templates_admin_columns_content', 10, 2 );

function thegem_templates_admin_query_filter_types(WP_Query $query) {
	global $pagenow, $typenow;
	if(!('edit.php' === $pagenow && 'thegem_templates' === $typenow) || ! empty( $query->query_vars['meta_key'] ) || !$query->is_main_query()) {
		return;
	}
	$templates_types = thegem_templates_types();
	$current_type = '';
	if(!empty($_REQUEST['templates_type']) && isset($templates_types[$_REQUEST['templates_type']])) {
		$current_type = $_REQUEST['templates_type'];
	}
	if(empty($current_type)) {
		return;
	}
	if($current_type === 'content') {
		$meta_query[] = array(
			'relation' => 'OR',
			array(
				'key' => 'thegem_template_type',
				'value' => 'content',
				'compare' => 'LIKE',
			),
			array(
				'key' => 'thegem_template_type',
				'compare' => 'NOT EXISTS',
			),
		);
		$query->set('meta_query', $meta_query);
	} else {
		$query->query_vars['meta_key'] = 'thegem_template_type';
		$query->query_vars['meta_value'] = $current_type;
	}
}
add_action( 'parse_query', 'thegem_templates_admin_query_filter_types' );

function thegem_templates_new_init() {
	if ( 'edit-thegem_templates' !== get_current_screen()->id && 'thegem_templates' !== get_current_screen()->id) {
		return;
	}
	if('thegem_templates' === get_current_screen()->id && 'add' === get_current_screen()->action) {
		$redirect_link = add_query_arg(array('post_type' => 'thegem_templates'), admin_url( 'edit.php' )).'#open-modal';
		wp_redirect($redirect_link);
		die;
	}
	add_action( 'admin_head', 'thegem_templates_new_popup');
	add_action( 'admin_enqueue_scripts', 'thegem_templates_new_enqueue_scripts');
}
add_action( 'current_screen', 'thegem_templates_new_init' );

function thegem_import_templates() {
	require_once __DIR__.'/import-data.php';
	$output_templates = array();
	if (!empty($templates)) {
		foreach($templates as $template) {
			if(isset($template['woo']) && defined('WC_PLUGIN_FILE') && $template['woo'] == 2) continue;
			if(isset($template['woo']) && !defined('WC_PLUGIN_FILE') && $template['woo'] == 1) continue;
			if($template['type'] === 'megamenu' && !defined('WC_PLUGIN_FILE') && isset($template['categories']['shop'])) continue;
			$output_templates[$template['id']] = array_merge($template, array(
				'insert' => add_query_arg(array(
					'_wpnonce' => wp_create_nonce( 'thegem_templates_new' ),
					'action' => 'thegem_templates_new',
					'post_type' => 'thegem_templates',
					'template' => $template['id'],
				), admin_url( 'edit.php' ))
			));
			$output_templates[$template['id']]['pic'] = plugin_dir_url( __FILE__ ) . 'assets/img/previews/'.$output_templates[$template['id']]['pic'];
		}
	}
	return $output_templates;
}

function thegem_templates_new_popup() {
	$templates_types = thegem_templates_types(true);
	$import_templates = thegem_import_templates();
	$categories = array();
	$categories['header'] = array('*' => esc_html__('All', 'thegem'));
	$categories['footer'] = array('*' => esc_html__('All', 'thegem'));
	$categories['title'] = array('*' => esc_html__('All', 'thegem'));
	$categories['megamenu'] = array('*' => esc_html__('All', 'thegem'));
	$categories['single-product'] = array('*' => esc_html__('All', 'thegem'));
	$categories['single-post'] = array('*' => esc_html__('All', 'thegem'));
	$categories['product-archive'] = array('*' => esc_html__('All', 'thegem'));
	$categories['cart'] = array('*' => esc_html__('All', 'thegem'));
	$categories['checkout'] = array('*' => esc_html__('All', 'thegem'));
	$categories['checkout-thanks'] = array('*' => esc_html__('All', 'thegem'));
	$categories['blog-archive'] = array('*' => esc_html__('All', 'thegem'));
	$categories['popup'] = array('*' => esc_html__('All', 'thegem'));
	foreach($import_templates as $key => $template) {
		if(!empty($template['categories']) && is_array($template['categories'])) {
			$categories[$template['type']] = array_merge($categories[$template['type']], $template['categories']);
			$import_templates[$key]['data-cats'] = implode(' ', array_keys($template['categories']));
		}
	}
?>
<script type="text/template" id="thegem-templates-new-popup">
	<div class="thegem-templates-new-popup">
		<div class="thegem-templates-modal-title">
			<a href="javascript:void(0)" class="thegem-templates-modal-logo"><img src="<?= plugin_dir_url( __FILE__ ) . 'assets/img/logo.svg' ?>" alt="logo" /></a>
			<a href="javascript:void(0);" class="thegem-templates-modal-close"></a>
		</div>
		<div class="thegem-templates-new-welcome">
			<div class="thegem-templates-new-welcome-wrap">
				<div class="thegem-templates-new-welcome-info">
					<div class="title"><?php esc_html_e('Templates Builder', 'thegem'); ?></div>
					<div class="text"><?php esc_html_e('Templates help you to create and edit different parts of your website in one place and reuse this parts globally across your site with few clicks.', 'thegem'); ?></div>
				</div>
				<div class="thegem-templates-new-welcome-form">
					<div class="thegem-templates-new-welcome-form-wrap">
						<form id="thegem-templates-new-form" action="<?php esc_url( admin_url( '/edit.php' ) ); ?>">
							<input type="hidden" name="post_type" value="thegem_templates">
							<input type="hidden" name="action" value="thegem_templates_new">
							<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'thegem_templates_new' ); ?>">
							<div class="thegem-templates-new-field">
								<div class="thegem-templates-new-label"><?php esc_html_e('Select Template Type', 'thegem'); ?>:</div>
								<div class="thegem-templates-new-input">
									<select id="thegem-templates-new-type" name="template_type" required>
										<option value="" disabled selected><?php esc_html_e('Select...', 'thegem'); ?></option>
										<?php
										foreach ( $templates_types as $type => $title ) {
											$selected = !empty($_REQUEST['templates_type']) && $_REQUEST['templates_type'] == $type ? ' selected' : '';
											printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $type ), $selected, esc_html( $title ) );
										}
										?>
									</select>
								</div>
							</div>
							<div class="thegem-templates-new-field">
								<div class="thegem-templates-new-label"><?php esc_html_e('Specify Template Name', 'thegem'); ?>:</div>
								<div class="thegem-templates-new-input">
									<input type="text" placeholder="<?php echo esc_attr__( 'Enter Template Name (Optional)', 'thegem' ); ?>" id="thegem-templates-new-name" name="post_data[post_title]">
								</div>
							</div>
							<div class="thegem-templates-new-submit">
								<button class="btn-solid show-not-popup" id="thegem-templates-new-submit" type="submit"><?php echo esc_html__( 'Create Template', 'thegem' ); ?></button>
								<button class="btn-solid show-is-popup" id="thegem-templates-setting-submit" type="button" data-target-template-type="popup"><?php echo esc_html__( 'Proceed', 'thegem' ); ?></button>
								<div class="show-is-header"<?php echo (!empty($_REQUEST['templates_type']) && $_REQUEST['templates_type'] == 'header') ? '' : ' style="display: none;"'; ?>>
									<span class="separator">or</span>
									<a id="thegem-templates-import-link" class="btn-solid" href="javascript:void(0);" data-target-template-type="header"><?php echo esc_html__( 'Import Pre-Built Template', 'thegem' ); ?></a>
								</div>
								<div class="show-is-footer"<?php echo (!empty($_REQUEST['templates_type']) && $_REQUEST['templates_type'] == 'footer') ? '' : ' style="display: none;"'; ?>>
									<span class="separator">or</span>
									<a id="thegem-templates-import-link" class="btn-solid" href="javascript:void(0);" data-target-template-type="footer"><?php echo esc_html__( 'Import Pre-Built Template', 'thegem' ); ?></a>
								</div>
								<div class="show-is-title"<?php echo (!empty($_REQUEST['templates_type']) && $_REQUEST['templates_type'] == 'title') ? '' : ' style="display: none;"'; ?>>
									<span class="separator">or</span>
									<a id="thegem-templates-import-link" class="btn-solid" href="javascript:void(0);" data-target-template-type="title"><?php echo esc_html__( 'Import Pre-Built Template', 'thegem' ); ?></a>
								</div>
								<div class="show-is-megamenu"<?php echo (!empty($_REQUEST['templates_type']) && $_REQUEST['templates_type'] == 'megamenu') ? '' : ' style="display: none;"'; ?>>
									<span class="separator">or</span>
									<a id="thegem-templates-import-link" class="btn-solid" href="javascript:void(0);" data-target-template-type="megamenu"><?php echo esc_html__( 'Import Pre-Built Template', 'thegem' ); ?></a>
								</div>
								<div class="show-is-single-product"<?php echo (!empty($_REQUEST['templates_type']) && $_REQUEST['templates_type'] == 'single-product') ? '' : ' style="display: none;"'; ?>>
									<span class="separator">or</span>
									<a id="thegem-templates-import-link" class="btn-solid" href="javascript:void(0);" data-target-template-type="single-product"><?php echo esc_html__( 'Import Pre-Built Template', 'thegem' ); ?></a>
								</div>
								<div class="show-is-single-post"<?php echo (!empty($_REQUEST['templates_type']) && $_REQUEST['templates_type'] == 'single-post') ? '' : ' style="display: none;"'; ?>>
									<span class="separator">or</span>
									<a id="thegem-templates-import-link" class="btn-solid" href="javascript:void(0);" data-target-template-type="single-post"><?php echo esc_html__( 'Import Pre-Built Template', 'thegem' ); ?></a>
								</div>
								<div class="show-is-product-archive"<?php echo (!empty($_REQUEST['templates_type']) && $_REQUEST['templates_type'] == 'product-archive') ? '' : ' style="display: none;"'; ?>>
									<span class="separator">or</span>
									<a id="thegem-templates-import-link" class="btn-solid" href="javascript:void(0);" data-target-template-type="product-archive"><?php echo esc_html__( 'Import Pre-Built Template', 'thegem' ); ?></a>
								</div>
								<div class="show-is-cart"<?php echo (!empty($_REQUEST['templates_type']) && $_REQUEST['templates_type'] == 'cart') ? '' : ' style="display: none;"'; ?>>
									<span class="separator">or</span>
									<a id="thegem-templates-import-link" class="btn-solid" href="javascript:void(0);" data-target-template-type="cart"><?php echo esc_html__( 'Import Pre-Built Template', 'thegem' ); ?></a>
								</div>
								<div class="show-is-checkout"<?php echo (!empty($_REQUEST['templates_type']) && $_REQUEST['templates_type'] == 'checkout') ? '' : ' style="display: none;"'; ?>>
									<span class="separator">or</span>
									<a id="thegem-templates-import-link" class="btn-solid" href="javascript:void(0);" data-target-template-type="checkout"><?php echo esc_html__( 'Import Pre-Built Template', 'thegem' ); ?></a>
								</div>
								<div class="show-is-checkout-thanks"<?php echo (!empty($_REQUEST['templates_type']) && $_REQUEST['templates_type'] == 'checkout-thanks') ? '' : ' style="display: none;"'; ?>>
									<span class="separator">or</span>
									<a id="thegem-templates-import-link" class="btn-solid" href="javascript:void(0);" data-target-template-type="checkout-thanks"><?php echo esc_html__( 'Import Pre-Built Template', 'thegem' ); ?></a>
								</div>
								<div class="show-is-blog-archive"<?php echo (!empty($_REQUEST['templates_type']) && $_REQUEST['templates_type'] == 'blog-archive') ? '' : ' style="display: none;"'; ?>>
									<span class="separator">or</span>
									<a id="thegem-templates-import-link" class="btn-solid" href="javascript:void(0);" data-target-template-type="blog-archive"><?php echo esc_html__( 'Import Pre-Built Template', 'thegem' ); ?></a>
								</div>
								<div class="show-is-popup"<?php echo (!empty($_REQUEST['templates_type']) && $_REQUEST['templates_type'] == 'popup') ? '' : ' style="display: none;"'; ?>>
									<span class="separator">or</span>
									<a id="thegem-templates-import-link" class="btn-solid" href="javascript:void(0);" data-target-template-type="popup"><?php echo esc_html__( 'Import Pre-Built Template', 'thegem' ); ?></a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		<div>
	</div>
</script>
<script type="text/template" id="thegem-templates-import-popup" data-template-type="header">
	<div class="thegem-templates-import-popup">
		<div class="thegem-templates-modal-title">
			<a href="javascript:void(0);" class="thegem-templates-modal-back"><?php esc_html_e('Back', 'thegem'); ?></a>
			<span class="thegem-templates-modal-text"><?php esc_html_e('Select Template to Insert', 'thegem'); ?></span>
			<a href="javascript:void(0);" class="thegem-templates-modal-close"></a>
		</div>
		<div class="thegem-templates-import-grid loading">
			<div class="thegem-templates-import-nav">
				<ul>
					<?php $cat_active = true; foreach($categories['header'] as $key => $category) : ?>
						<li><a<?php echo ($cat_active ? ' class="active"' : ''); ?> href="javascript:void(0)" data-cat-slug="<?php echo esc_attr($key); ?>"><?php echo esc_html($category); ?></a></li>
					<?php  $cat_active = false; endforeach; ?>
				</ul>
			</div>
			<div class="thegem-templates-import-grid-wrap">
				<?php foreach($import_templates as $template) :
					if($template['type'] === 'header') : ?>
						<div class="template"<?php echo (!empty($template['data-cats']) ? ' data-categories="'.$template['data-cats'].'"' : ''); ?>>
							<div class="template-preview">
								<div class="template-preview-image">
									<img src="<?php echo $template['pic']; ?>" alt="#">
								</div>
								<div class="template-preview-actions">
									<a href="<?php echo $template['insert']; ?>" class="thegem-templates-insert-link"><?php esc_html_e('Insert', 'thegem'); ?></a>
									<a href="<?php echo $template['preview']; ?>" class="thegem-template-preview-link" target="_blank"><?php esc_html_e('Preview', 'thegem'); ?></a>
								</div>
							</div>
							<div class="template-info">
								<div class="template-info-title"><?php echo $template['title']; ?></div>
								<?php if(!empty($template['mark'])) : ?>
									<div class="template-info-mark <?php echo $template['mark']; ?>"><?php echo $template['mark']; ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif;
				endforeach; ?>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="thegem-templates-import-popup" data-template-type="footer">
	<div class="thegem-templates-import-popup">
		<div class="thegem-templates-modal-title">
			<a href="javascript:void(0);" class="thegem-templates-modal-back"><?php esc_html_e('Back', 'thegem'); ?></a>
			<span class="thegem-templates-modal-text"><?php esc_html_e('Select Template to Insert', 'thegem'); ?></span>
			<a href="javascript:void(0);" class="thegem-templates-modal-close"></a>
		</div>
		<div class="thegem-templates-import-grid loading">
			<?php /* 			<div class="thegem-templates-import-nav">
				<ul>
					<?php $cat_active = true; foreach($categories['footer'] as $key => $category) : ?>
						<li><a<?php echo ($cat_active ? ' class="active"' : ''); ?> href="javascript:void(0)" data-cat-slug="<?php echo esc_attr($key); ?>"><?php echo esc_html($category); ?></a></li>
					<?php  $cat_active = false; endforeach; ?>
				</ul>
			</div> */ ?>
			<div class="thegem-templates-import-grid-wrap grid">
				<?php foreach($import_templates as $template) :
					if($template['type'] === 'footer') : ?>
						<div class="template"<?php echo (!empty($template['data-cats']) ? ' data-categories="'.$template['data-cats'].'"' : ''); ?>>
							<div class="template-preview">
								<div class="template-preview-image">
									<img src="<?php echo $template['pic']; ?>" alt="#">
								</div>
								<div class="template-preview-actions">
									<a href="<?php echo $template['insert']; ?>" class="thegem-templates-insert-link"><?php esc_html_e('Insert', 'thegem'); ?></a>
									<a href="<?php echo $template['preview']; ?>" class="thegem-template-preview-link" target="_blank"><?php esc_html_e('Preview', 'thegem'); ?></a>
								</div>
							</div>
							<div class="template-info">
								<div class="template-info-title"><?php echo $template['title']; ?></div>
								<?php if(!empty($template['mark'])) : ?>
									<div class="template-info-mark <?php echo $template['mark']; ?>"><?php echo $template['mark']; ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif;
				endforeach; ?>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="thegem-templates-import-popup" data-template-type="title">
	<div class="thegem-templates-import-popup">
		<div class="thegem-templates-modal-title">
			<a href="javascript:void(0);" class="thegem-templates-modal-back"><?php esc_html_e('Back', 'thegem'); ?></a>
			<span class="thegem-templates-modal-text"><?php esc_html_e('Select Template to Insert', 'thegem'); ?></span>
			<a href="javascript:void(0);" class="thegem-templates-modal-close"></a>
		</div>
		<div class="thegem-templates-import-grid loading">
			<?php /* <div class="thegem-templates-import-nav">
				<ul>
					<?php $cat_active = true; foreach($categories['title'] as $key => $category) : ?>
						<li><a<?php echo ($cat_active ? ' class="active"' : ''); ?> href="javascript:void(0)" data-cat-slug="<?php echo esc_attr($key); ?>"><?php echo esc_html($category); ?></a></li>
					<?php  $cat_active = false; endforeach; ?>
				</ul>
			</div> */ ?>
			<div class="thegem-templates-import-grid-wrap grid">
				<?php foreach($import_templates as $template) :
					if($template['type'] === 'title') : ?>
						<div class="template"<?php echo (!empty($template['data-cats']) ? ' data-categories="'.$template['data-cats'].'"' : ''); ?>>
							<div class="template-preview">
								<div class="template-preview-image">
									<img src="<?php echo $template['pic']; ?>" alt="#">
								</div>
								<div class="template-preview-actions">
									<a href="<?php echo $template['insert']; ?>" class="thegem-templates-insert-link"><?php esc_html_e('Insert', 'thegem'); ?></a>
									<a href="<?php echo $template['preview']; ?>" class="thegem-template-preview-link" target="_blank"><?php esc_html_e('Preview', 'thegem'); ?></a>
								</div>
							</div>
							<div class="template-info">
								<div class="template-info-title"><?php echo $template['title']; ?></div>
								<?php if(!empty($template['mark'])) : ?>
									<div class="template-info-mark <?php echo $template['mark']; ?>"><?php echo $template['mark']; ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif;
				endforeach; ?>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="thegem-templates-import-popup" data-template-type="megamenu">
	<div class="thegem-templates-import-popup">
		<div class="thegem-templates-modal-title">
			<a href="javascript:void(0);" class="thegem-templates-modal-back"><?php esc_html_e('Back', 'thegem'); ?></a>
			<span class="thegem-templates-modal-text"><?php esc_html_e('Select Template to Insert', 'thegem'); ?></span>
			<a href="javascript:void(0);" class="thegem-templates-modal-close"></a>
		</div>
		<div class="thegem-templates-import-grid loading">
			<div class="thegem-templates-import-nav">
				<ul>
					<?php $cat_active = true; foreach($categories['megamenu'] as $key => $category) : ?>
						<li><a<?php echo ($cat_active ? ' class="active"' : ''); ?> href="javascript:void(0)" data-cat-slug="<?php echo esc_attr($key); ?>"><?php echo esc_html($category); ?></a></li>
					<?php  $cat_active = false; endforeach; ?>
				</ul>
			</div>
			<div class="thegem-templates-import-grid-wrap grid">
				<?php foreach($import_templates as $template) :
					if($template['type'] === 'megamenu') : ?>
						<div class="template"<?php echo (!empty($template['data-cats']) ? ' data-categories="'.$template['data-cats'].'"' : ''); ?>>
							<div class="template-preview">
								<div class="template-preview-image">
									<img src="<?php echo $template['pic']; ?>" alt="#">
								</div>
								<div class="template-preview-actions">
									<a href="<?php echo $template['insert']; ?>" class="thegem-templates-insert-link"><?php esc_html_e('Insert', 'thegem'); ?></a>
									<a href="<?php echo $template['preview']; ?>" class="thegem-template-preview-link" target="_blank"><?php esc_html_e('Preview', 'thegem'); ?></a>
								</div>
							</div>
							<div class="template-info">
								<div class="template-info-title"><?php echo $template['title']; ?></div>
								<?php if(!empty($template['mark'])) : ?>
									<div class="template-info-mark <?php echo $template['mark']; ?>"><?php echo $template['mark']; ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif;
				endforeach; ?>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="thegem-templates-import-popup" data-template-type="single-product">
	<div class="thegem-templates-import-popup">
		<div class="thegem-templates-modal-title">
			<a href="javascript:void(0);" class="thegem-templates-modal-back"><?php esc_html_e('Back', 'thegem'); ?></a>
			<span class="thegem-templates-modal-text"><?php esc_html_e('Select Template to Insert', 'thegem'); ?></span>
			<a href="javascript:void(0);" class="thegem-templates-modal-close"></a>
		</div>
		<div class="thegem-templates-import-grid loading">
			<?php /* <div class="thegem-templates-import-nav">
				<ul>
					<?php $cat_active = true; foreach($categories['single-product'] as $key => $category) : ?>
						<li><a<?php echo ($cat_active ? ' class="active"' : ''); ?> href="javascript:void(0)" data-cat-slug="<?php echo esc_attr($key); ?>"><?php echo esc_html($category); ?></a></li>
					<?php $cat_active = false; endforeach; ?>
				</ul>
			</div> */ ?>
			<div class="thegem-templates-import-grid-wrap grid">
				<?php foreach($import_templates as $template) :
					if($template['type'] === 'single-product') : ?>
						<div class="template"<?php echo (!empty($template['data-cats']) ? ' data-categories="'.$template['data-cats'].'"' : ''); ?>>
							<div class="template-preview">
								<div class="template-preview-image">
									<img src="<?php echo $template['pic']; ?>" alt="#">
								</div>
								<div class="template-preview-actions">
									<a href="<?php echo $template['insert']; ?>" class="thegem-templates-insert-link"><?php esc_html_e('Insert', 'thegem'); ?></a>
									<a href="<?php echo $template['preview']; ?>" class="thegem-template-preview-link" target="_blank"><?php esc_html_e('Preview', 'thegem'); ?></a>
								</div>
							</div>
							<div class="template-info">
								<div class="template-info-title"><?php echo $template['title']; ?></div>
								<?php if(!empty($template['mark'])) : ?>
									<div class="template-info-mark <?php echo $template['mark']; ?>"><?php echo $template['mark']; ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif;
				endforeach; ?>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="thegem-templates-import-popup" data-template-type="single-post">
	<div class="thegem-templates-import-popup">
		<div class="thegem-templates-modal-title">
			<a href="javascript:void(0);" class="thegem-templates-modal-back"><?php esc_html_e('Back', 'thegem'); ?></a>
			<span class="thegem-templates-modal-text"><?php esc_html_e('Select Template to Insert', 'thegem'); ?></span>
			<a href="javascript:void(0);" class="thegem-templates-modal-close"></a>
		</div>
		<div class="thegem-templates-import-grid loading">
			<div class="thegem-templates-import-nav">
				<ul>
					<?php $cat_active = true; foreach($categories['single-post'] as $key => $category) : ?>
						<li><a<?php echo ($cat_active ? ' class="active"' : ''); ?> href="javascript:void(0)" data-cat-slug="<?php echo esc_attr($key); ?>"><?php echo esc_html($category); ?></a></li>
					<?php $cat_active = false; endforeach; ?>
				</ul>
			</div>
			<div class="thegem-templates-import-grid-wrap grid">
				<?php foreach($import_templates as $template) :
					if($template['type'] === 'single-post') : ?>
						<div class="template"<?php echo (!empty($template['data-cats']) ? ' data-categories="'.$template['data-cats'].'"' : ''); ?>>
							<div class="template-preview">
								<div class="template-preview-image">
									<img src="<?php echo $template['pic']; ?>" alt="#">
								</div>
								<div class="template-preview-actions">
									<a href="<?php echo $template['insert']; ?>" class="thegem-templates-insert-link"><?php esc_html_e('Insert', 'thegem'); ?></a>
									<a href="<?php echo $template['preview']; ?>" class="thegem-template-preview-link" target="_blank"><?php esc_html_e('Preview', 'thegem'); ?></a>
								</div>
							</div>
							<div class="template-info">
								<div class="template-info-title"><?php echo $template['title']; ?></div>
								<?php if(!empty($template['mark'])) : ?>
									<div class="template-info-mark <?php echo $template['mark']; ?>"><?php echo $template['mark']; ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif;
				endforeach; ?>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="thegem-templates-import-popup" data-template-type="product-archive">
	<div class="thegem-templates-import-popup">
		<div class="thegem-templates-modal-title">
			<a href="javascript:void(0);" class="thegem-templates-modal-back"><?php esc_html_e('Back', 'thegem'); ?></a>
			<span class="thegem-templates-modal-text"><?php esc_html_e('Select Template to Insert', 'thegem'); ?></span>
			<a href="javascript:void(0);" class="thegem-templates-modal-close"></a>
		</div>
		<div class="thegem-templates-import-grid loading">
			<?php /* <div class="thegem-templates-import-nav">
				<ul>
					<?php $cat_active = true; foreach($categories['product-archive'] as $key => $category) : ?>
						<li><a<?php echo ($cat_active ? ' class="active"' : ''); ?> href="javascript:void(0)" data-cat-slug="<?php echo esc_attr($key); ?>"><?php echo esc_html($category); ?></a></li>
					<?php $cat_active = false; endforeach; ?>
				</ul>
			</div> */ ?>
			<div class="thegem-templates-import-grid-wrap grid">
				<?php foreach($import_templates as $template) :
					if($template['type'] === 'product-archive') : ?>
						<div class="template"<?php echo (!empty($template['data-cats']) ? ' data-categories="'.$template['data-cats'].'"' : ''); ?>>
							<div class="template-preview">
								<div class="template-preview-image">
									<img src="<?php echo $template['pic']; ?>" alt="#">
								</div>
								<div class="template-preview-actions">
									<a href="<?php echo $template['insert']; ?>" class="thegem-templates-insert-link"><?php esc_html_e('Insert', 'thegem'); ?></a>
									<a href="<?php echo $template['preview']; ?>" class="thegem-template-preview-link" target="_blank"><?php esc_html_e('Preview', 'thegem'); ?></a>
								</div>
							</div>
							<div class="template-info">
								<div class="template-info-title"><?php echo $template['title']; ?></div>
								<?php if(!empty($template['mark'])) : ?>
									<div class="template-info-mark <?php echo $template['mark']; ?>"><?php echo $template['mark']; ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif;
				endforeach; ?>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="thegem-templates-import-popup" data-template-type="cart">
	<div class="thegem-templates-import-popup">
		<div class="thegem-templates-modal-title">
			<a href="javascript:void(0);" class="thegem-templates-modal-back"><?php esc_html_e('Back', 'thegem'); ?></a>
			<span class="thegem-templates-modal-text"><?php esc_html_e('Select Template to Insert', 'thegem'); ?></span>
			<a href="javascript:void(0);" class="thegem-templates-modal-close"></a>
		</div>
		<div class="thegem-templates-import-grid loading">
			<?php /* <div class="thegem-templates-import-nav">
				<ul>
					<?php $cat_active = true; foreach($categories['cart'] as $key => $category) : ?>
						<li><a<?php echo ($cat_active ? ' class="active"' : ''); ?> href="javascript:void(0)" data-cat-slug="<?php echo esc_attr($key); ?>"><?php echo esc_html($category); ?></a></li>
					<?php $cat_active = false; endforeach; ?>
				</ul>
			</div> */ ?>
			<div class="thegem-templates-import-grid-wrap grid">
				<?php foreach($import_templates as $template) :
					if($template['type'] === 'cart') : ?>
						<div class="template"<?php echo (!empty($template['data-cats']) ? ' data-categories="'.$template['data-cats'].'"' : ''); ?>>
							<div class="template-preview">
								<div class="template-preview-image">
									<img src="<?php echo $template['pic']; ?>" alt="#">
								</div>
								<div class="template-preview-actions">
									<a href="<?php echo $template['insert']; ?>" class="thegem-templates-insert-link"><?php esc_html_e('Insert', 'thegem'); ?></a>
									<a href="<?php echo $template['preview']; ?>" class="thegem-template-preview-link" target="_blank"><?php esc_html_e('Preview', 'thegem'); ?></a>
								</div>
							</div>
							<div class="template-info">
								<div class="template-info-title"><?php echo $template['title']; ?></div>
								<?php if(!empty($template['mark'])) : ?>
									<div class="template-info-mark <?php echo $template['mark']; ?>"><?php echo $template['mark']; ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif;
				endforeach; ?>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="thegem-templates-import-popup" data-template-type="checkout">
	<div class="thegem-templates-import-popup">
		<div class="thegem-templates-modal-title">
			<a href="javascript:void(0);" class="thegem-templates-modal-back"><?php esc_html_e('Back', 'thegem'); ?></a>
			<span class="thegem-templates-modal-text"><?php esc_html_e('Select Template to Insert', 'thegem'); ?></span>
			<a href="javascript:void(0);" class="thegem-templates-modal-close"></a>
		</div>
		<div class="thegem-templates-import-grid loading">
			<?php /* <div class="thegem-templates-import-nav">
				<ul>
					<?php $cat_active = true; foreach($categories['checkout'] as $key => $category) : ?>
						<li><a<?php echo ($cat_active ? ' class="active"' : ''); ?> href="javascript:void(0)" data-cat-slug="<?php echo esc_attr($key); ?>"><?php echo esc_html($category); ?></a></li>
					<?php $cat_active = false; endforeach; ?>
				</ul>
			</div> */ ?>
			<div class="thegem-templates-import-grid-wrap grid">
				<?php foreach($import_templates as $template) :
					if($template['type'] === 'checkout') : ?>
						<div class="template"<?php echo (!empty($template['data-cats']) ? ' data-categories="'.$template['data-cats'].'"' : ''); ?>>
							<div class="template-preview">
								<div class="template-preview-image">
									<img src="<?php echo $template['pic']; ?>" alt="#">
								</div>
								<div class="template-preview-actions">
									<a href="<?php echo $template['insert']; ?>" class="thegem-templates-insert-link"><?php esc_html_e('Insert', 'thegem'); ?></a>
									<a href="<?php echo $template['preview']; ?>" class="thegem-template-preview-link" target="_blank"><?php esc_html_e('Preview', 'thegem'); ?></a>
								</div>
							</div>
							<div class="template-info">
								<div class="template-info-title"><?php echo $template['title']; ?></div>
								<?php if(!empty($template['mark'])) : ?>
									<div class="template-info-mark <?php echo $template['mark']; ?>"><?php echo $template['mark']; ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif;
				endforeach; ?>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="thegem-templates-import-popup" data-template-type="checkout-thanks">
	<div class="thegem-templates-import-popup">
		<div class="thegem-templates-modal-title">
			<a href="javascript:void(0);" class="thegem-templates-modal-back"><?php esc_html_e('Back', 'thegem'); ?></a>
			<span class="thegem-templates-modal-text"><?php esc_html_e('Select Template to Insert', 'thegem'); ?></span>
			<a href="javascript:void(0);" class="thegem-templates-modal-close"></a>
		</div>
		<div class="thegem-templates-import-grid loading">
			<?php /* <div class="thegem-templates-import-nav">
				<ul>
					<?php $cat_active = true; foreach($categories['checkout-thanks'] as $key => $category) : ?>
						<li><a<?php echo ($cat_active ? ' class="active"' : ''); ?> href="javascript:void(0)" data-cat-slug="<?php echo esc_attr($key); ?>"><?php echo esc_html($category); ?></a></li>
					<?php $cat_active = false; endforeach; ?>
				</ul>
			</div> */ ?>
			<div class="thegem-templates-import-grid-wrap grid">
				<?php foreach($import_templates as $template) :
					if($template['type'] === 'checkout-thanks') : ?>
						<div class="template"<?php echo (!empty($template['data-cats']) ? ' data-categories="'.$template['data-cats'].'"' : ''); ?>>
							<div class="template-preview">
								<div class="template-preview-image">
									<img src="<?php echo $template['pic']; ?>" alt="#">
								</div>
								<div class="template-preview-actions">
									<a href="<?php echo $template['insert']; ?>" class="thegem-templates-insert-link"><?php esc_html_e('Insert', 'thegem'); ?></a>
									<?php /* <a href="<?php echo $template['preview']; ?>" class="thegem-template-preview-link" target="_blank"><?php esc_html_e('Preview', 'thegem'); ?></a> */ ?>
								</div>
							</div>
							<div class="template-info">
								<div class="template-info-title"><?php echo $template['title']; ?></div>
								<?php if(!empty($template['mark'])) : ?>
									<div class="template-info-mark <?php echo $template['mark']; ?>"><?php echo $template['mark']; ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif;
				endforeach; ?>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="thegem-templates-import-popup" data-template-type="blog-archive">
	<div class="thegem-templates-import-popup">
		<div class="thegem-templates-modal-title">
			<a href="javascript:void(0);" class="thegem-templates-modal-back"><?php esc_html_e('Back', 'thegem'); ?></a>
			<span class="thegem-templates-modal-text"><?php esc_html_e('Select Template to Insert', 'thegem'); ?></span>
			<a href="javascript:void(0);" class="thegem-templates-modal-close"></a>
		</div>
		<div class="thegem-templates-import-grid loading">
			<div class="thegem-templates-import-nav">
				<ul>
					<?php $cat_active = true; foreach($categories['blog-archive'] as $key => $category) : ?>
						<li><a<?php echo ($cat_active ? ' class="active"' : ''); ?> href="javascript:void(0)" data-cat-slug="<?php echo esc_attr($key); ?>"><?php echo esc_html($category); ?></a></li>
					<?php $cat_active = false; endforeach; ?>
				</ul>
			</div>
			<div class="thegem-templates-import-grid-wrap grid">
				<?php foreach($import_templates as $template) :
					if($template['type'] === 'blog-archive') : ?>
						<div class="template"<?php echo (!empty($template['data-cats']) ? ' data-categories="'.$template['data-cats'].'"' : ''); ?>>
							<div class="template-preview">
								<div class="template-preview-image">
									<img src="<?php echo $template['pic']; ?>" alt="#">
								</div>
								<div class="template-preview-actions">
									<a href="<?php echo $template['insert']; ?>" class="thegem-templates-insert-link"><?php esc_html_e('Insert', 'thegem'); ?></a>
									<a href="<?php echo $template['preview']; ?>" class="thegem-template-preview-link" target="_blank"><?php esc_html_e('Preview', 'thegem'); ?></a>
								</div>
							</div>
							<div class="template-info">
								<div class="template-info-title"><?php echo $template['title']; ?></div>
								<?php if(!empty($template['mark'])) : ?>
									<div class="template-info-mark <?php echo $template['mark']; ?>"><?php echo $template['mark']; ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif;
				endforeach; ?>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="thegem-templates-settings-popup" data-template-type="popup">
	<div class="thegem-templates-new-popup">
		<div class="thegem-templates-modal-title">
			<a href="javascript:void(0)" class="thegem-templates-modal-logo"><img src="<?= plugin_dir_url( __FILE__ ) . 'assets/img/logo.svg' ?>" alt="logo" /></a>
			<a href="javascript:void(0);" class="thegem-templates-modal-close"></a>
		</div>
		<div class="thegem-templates-new-welcome">
			<div class="thegem-templates-new-welcome-wrap">
				<div class="thegem-templates-new-welcome-info">
					<div class="title"><?php esc_html_e('Templates Builder', 'thegem'); ?></div>
					<div class="text"><?php esc_html_e('Templates help you to create and edit different parts of your website in one place and reuse this parts globally across your site with few clicks.', 'thegem'); ?></div>
				</div>
				<div class="thegem-templates-new-welcome-form">
					<div class="thegem-templates-new-welcome-form-wrap">
						<form id="thegem-templates-settings-form" action="<?php esc_url( admin_url( '/edit.php' ) ); ?>">
							<input type="hidden" name="post_type" value="thegem_templates">
							<input type="hidden" name="post_data[post_title]" value="">
							<input type="hidden" name="template_type" value="popup">
							<input type="hidden" name="action" value="thegem_templates_new">
							<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'thegem_templates_new' ); ?>">
							<div class="thegem-templates-new-field">
								<div class="thegem-templates-new-label"><?php esc_html_e('Specify Popup Width (px)', 'thegem'); ?>:</div>
								<div class="thegem-templates-new-input">
									<input type="text" placeholder="<?php echo esc_attr__( 'Enter Popup Width (Optional)', 'thegem' ); ?>" name="popup_width">
								</div>
								<div class="thegem-templates-new-description"><?php esc_html_e('You can specify / change the popup width later in popup page options', 'thegem'); ?></div>
							</div>
							<div class="thegem-templates-new-submit">
								<button class="btn-solid show-not-popup" id="thegem-templates-new-submit" type="submit"><?php echo esc_html__( 'Create Template', 'thegem' ); ?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		<div>
	</div>
</script>
<script type="text/template" id="thegem-templates-import-popup" data-template-type="popup">
	<div class="thegem-templates-import-popup">
		<div class="thegem-templates-modal-title">
			<a href="javascript:void(0);" class="thegem-templates-modal-back"><?php esc_html_e('Back', 'thegem'); ?></a>
			<span class="thegem-templates-modal-text"><?php esc_html_e('Select Template to Insert', 'thegem'); ?></span>
			<a href="javascript:void(0);" class="thegem-templates-modal-close"></a>
		</div>
		<div class="thegem-templates-import-grid loading">
			<div class="thegem-templates-import-nav">
				<ul>
					<?php $cat_active = true; foreach($categories['popup'] as $key => $category) : ?>
						<li><a<?php echo ($cat_active ? ' class="active"' : ''); ?> href="javascript:void(0)" data-cat-slug="<?php echo esc_attr($key); ?>"><?php echo esc_html($category); ?></a></li>
					<?php  $cat_active = false; endforeach; ?>
				</ul>
			</div>
			<div class="thegem-templates-import-grid-wrap grid">
				<?php foreach($import_templates as $template) :
					if($template['type'] === 'popup') : ?>
						<div class="template"<?php echo (!empty($template['data-cats']) ? ' data-categories="'.$template['data-cats'].'"' : ''); ?>>
							<div class="template-preview">
								<div class="template-preview-image">
									<img src="<?php echo $template['pic']; ?>" alt="#">
								</div>
								<div class="template-preview-actions">
									<a href="<?php echo $template['insert']; ?>" class="thegem-templates-insert-link"><?php esc_html_e('Insert', 'thegem'); ?></a>
									<a href="<?php echo $template['preview']; ?>" class="thegem-template-preview-link" target="_blank"><?php esc_html_e('Preview', 'thegem'); ?></a>
								</div>
							</div>
							<div class="template-info">
								<div class="template-info-title"><?php echo $template['title']; ?></div>
								<?php if(!empty($template['mark'])) : ?>
									<div class="template-info-mark <?php echo $template['mark']; ?>"><?php echo $template['mark']; ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif;
				endforeach; ?>
			</div>
		</div>
	</div>
</script>
<?php /*<script type="text/template" id="thegem-templates-preview-popup">
	<div class="thegem-templates-preview-popup">
		<div class="thegem-templates-preview-title">
			<a href="javascript:void(0);" class="thegem-templates-import-back"><?php esc_html_e('Back', 'thegem'); ?></a>
			<a href="#" class="thegem-templates-import-link"><?php esc_html_e('Import Template', 'thegem'); ?></a>
			<a href="javascript:void(0);" class="thegem-templates-new-close"></a>
		</div>
		<div class="thegem-template-preview">

		</div>
	</div>
</script> */ ?>
<?php
}

function thegem_templates_new_create() {
		check_admin_referer( 'thegem_templates_new' );
		if(empty($_GET['post_type']) || $_GET['post_type'] !== 'thegem_templates') {
			return;
		}

		$post_type_object = get_post_type_object( 'thegem_templates' );
		if(!current_user_can( $post_type_object->cap->edit_posts)) {
			return;
		}

		$templates_types = thegem_templates_types();
		if ( empty( $_GET['template_type'] ) || !isset($templates_types[sanitize_text_field($_GET['template_type'])])) {
			$type = 'content';
		} else {
			$type = sanitize_text_field( $_GET['template_type'] );
		}

		$post_data = isset( $_GET['post_data'] ) ? $_GET['post_data'] : [];
		$post_data['post_type'] = 'thegem_templates';
		$post_data['post_status'] = 'publish';
		if(empty($post_data['post_title'])) {
			$post_data['post_title'] = __('Draft Template', 'thegem');
		}

		$content = '';
		$meta_data = [];

		if($type === 'popup') {
			$meta_data['thegem_popup_item_data'] = thegem_get_sanitize_popup_item_data(0);
			if(isset($_GET['popup_width']) && intval($_GET['popup_width']) > 0) {
				$meta_data['thegem_popup_item_data']['popup_width_desktop'] = intval($_GET['popup_width']);
			}
		}

		if(!empty($_GET['template'])) {
			$import_templates = thegem_import_templates();
			if(!empty($import_templates[$_GET['template']])) {
				$template = $import_templates[$_GET['template']];
				$type = $template['type'];
				$post_data['post_title'] = $template['title'];

				if (!get_option('elementor_unfiltered_files_upload')) {
					update_option('elementor_unfiltered_files_upload', 1);
					update_option('thegem_disable_elementor_unfiltered_files_upload_after_action', 1);
				}
				$template['content'] = preg_replace('%href=\\\\"https:\\\\/\\\\/democontent.codex-themes.com\\\\/thegem-(elementor-)?blocks(-pb)?%', 'href=\"'.addcslashes(site_url(), '/'), $template['content']);
				$content = wp_slash(thegem_template_import_content($template['content'], 'on_import'));

				if (get_option('thegem_disable_elementor_unfiltered_files_upload_after_action')) {
					update_option('elementor_unfiltered_files_upload', '');
					update_option('thegem_disable_elementor_unfiltered_files_upload_after_action', '');
				}

				if($type === 'header') {
					thegem_templates_import_menus();
				}
				if(!empty($template['metas']) && is_array($template['metas'])) {
					foreach($template['metas'] as $meta_key => $meta_value) {
						$meta_data[ $meta_key ] = $meta_value;
					}
				}
			}
		}

		$meta_data[ 'thegem_template_type' ] = $type;
		if(!empty($content)) {
			$meta_data[ '_elementor_data' ] = $content;
			$meta_data[ '_elementor_edit_mode' ] = 'builder';
			if(defined('ELEMENTOR_VERSION')) {
				$meta_data[ '_elementor_version' ] = ELEMENTOR_VERSION;
			}
		}
		$post_data['meta_input'] = $meta_data;

		$template_id = wp_insert_post( $post_data );
		$redirect_link = add_query_arg(array('post' => $template_id, 'action' => 'edit'), admin_url( 'post.php' ));
		if(defined('ELEMENTOR_VERSION')) {
			$redirect_link = add_query_arg(array('post' => $template_id, 'action' => 'elementor'), admin_url( 'post.php' ));
		}
		wp_redirect($redirect_link);

		die;
}
add_action( 'admin_action_thegem_templates_new', 'thegem_templates_new_create' );

function thegem_template_import_content($content, $method) {
	$obj = json_decode($content, true);
	$response_p = wp_remote_get(add_query_arg(array('code' => thegem_get_purchase(), 'info'=>thegem_get_activation_info(), 'site_url' => get_site_url(), 'type' => 'elementor'), 'http://democontent.codex-themes.com/av_validate_code'.(defined('ENVATO_HOSTED_SITE') ? '_envato' : '').'.php'), array('timeout' => 20));
	if (defined('ELEMENTOR_VERSION') && $obj) {
		$obj = \Elementor\Plugin::$instance->db->iterate_data(
			$obj, function( $element_data ) use ( $method ) {
				$element = \Elementor\Plugin::$instance->elements_manager->create_element_instance( $element_data );
				if ( ! $element ) {
					return null;
				}
				return thegem_template_element_import_content( $element, $method );
			}
		);
		thegem_template_import_post_elementor_dependencies_helper($obj);
		$content = json_encode($obj);
	}

	return $content;
}

function thegem_template_element_import_content( $element, $method ) {
	$element_data = $element->get_data();
	if ( method_exists( $element, $method ) ) {
		$element_data = $element->{$method}( $element_data );
	}
	foreach ( $element->get_controls() as $control ) {
		$control_class = \Elementor\Plugin::$instance->controls_manager->get_control( $control['type'] );
		if ( ! $control_class ) {
			return $element_data;
		}
		if ( method_exists( $control_class, $method ) ) {
			$element_data['settings'][ $control['name'] ] = $control_class->{$method}( $element->get_settings( $control['name'] ), $control );
		}
		if ( 'on_export' === $method && isset( $control['export'] ) && false === $control['export'] ) {
			unset( $element_data['settings'][ $control['name'] ] );
		}
	}
	return $element_data;
}

function thegem_templates_import_menus() {
	$demo_menu = wp_update_nav_menu_object(0, array('menu-name' => 'TheGem-Menu(Demo)'));
	if(!is_wp_error($demo_menu)) {
		$demo_menu_titles = array('Demos', 'Features', 'Elements', 'Pages', 'Shop', 'Blog', 'Portfolio');
		foreach($demo_menu_titles as $title) {
			wp_update_nav_menu_item($demo_menu, 0, array(
				'menu-item-title' => $title,
				'menu-item-url' => '#',
				'menu-item-status' => 'publish',
			));
		}
	}
	$demo_secondary_menu = wp_update_nav_menu_object(0, array('menu-name' => 'TheGem-Secondary Menu (Demo)'));
	if(!is_wp_error($demo_secondary_menu)) {
		$demo_secondary_menu_titles = array('My account', 'FAQ', 'Contact Us', 'Newsletter Signup', 'Shipping Terms');
		foreach($demo_secondary_menu_titles as $title) {
			wp_update_nav_menu_item($demo_secondary_menu, 0, array(
				'menu-item-title' => $title,
				'menu-item-url' => '#',
				'menu-item-status' => 'publish',
			));
		}
	}
	$demo_mini_menu = wp_update_nav_menu_object(0, array('menu-name' => 'TheGem-Menu-Mini(Demo)'));
	if(!is_wp_error($demo_mini_menu)) {
		$demo_mini_menu_titles = array('Demos', 'Elements', 'Pages', 'Shop', 'Blog');
		foreach($demo_mini_menu_titles as $title) {
			wp_update_nav_menu_item($demo_mini_menu, 0, array(
				'menu-item-title' => $title,
				'menu-item-url' => '#',
				'menu-item-status' => 'publish',
			));
		}
	}
}

function thegem_template_import_post_elementor_dependencies_helper(&$data) {
	if (isset($data['widgetType'])) {
		switch ($data['widgetType']) {
			case 'thegem-contact-form7':
				if ($data['settings']['form_id']) {
					$data['settings']['form_id'] = thegem_templates_import_contact_form($data['settings']['form_id']);
				}
				break;
			case 'thegem-mailchimp':
				if (isset($data['settings']['form_id'])) {
					$data['settings']['form_id'] = thegem_templates_import_mailchimp_form($data['settings']['form_id']);
				}
				break;
		}
	}

	if(is_array($data)) {
		foreach($data as &$v) {
			if (is_array($v)) {
				thegem_template_import_post_elementor_dependencies_helper($v);
			}
		}
	}
}

function thegem_templates_import_contact_form($id) {
	if (! defined( 'WPCF7_VERSION' )) {
		return $id;
	}

	require __DIR__.'/import-data.php';

	if(empty($contactForms)) return $id;

	$importContactForm = $id;

	$posts_forms = get_posts([
		'post_type' => 'wpcf7_contact_form',
		'posts_per_page' => -1,
		'orderby' => 'title',
		'order' => 'ASC',
	]);

	$formIds = [];
	foreach ($posts_forms as $itemForm) {
		$formIds[$itemForm->post_title] = $itemForm->ID;
	}

	$importForm = $contactForms[$id];

	if (empty($importForm)) {
		return $id;
	}

	if (!empty($formIds[$importForm['name']])) {
		$importContactForm = $formIds[$importForm['name']];
	} else {
		$post_data = array();
		$post_data['post_type'] = 'wpcf7_contact_form';
		$post_data['post_status'] = 'publish';
		$post_data['post_title'] = $importForm['name'];
		$post_data['meta_input'] = array(
			'_form' => $importForm['form']
		);
		$newFormId = wp_insert_post( $post_data );
		$importContactForm = $newFormId;
	}

	return $importContactForm;
}

function thegem_templates_import_mailchimp_form($id) {
	if (! defined( 'YIKES_MC_VERSION' )) {
		return $id;
	}
	if (empty($id)) return $id;

	require __DIR__.'/import-data.php';

	if(empty($mailChimpForms)) return $id;

	$importMailChimpForm = $id;
	$interface = yikes_easy_mailchimp_extender_get_form_interface();

	$formIds = [];
	foreach ($interface->get_all_forms() as $itemForm) {
		$formIds[$itemForm['unique_id']] = $itemForm;
	}

	$importForm = $mailChimpForms[$id];

	if (empty($importForm)) {
		return $id;
	}

	if (!empty($formIds[$id])) {
		$importMailChimpForm = $formIds[$id]['id'];
	} else {
		$newFormId = $interface->create_form($importForm);
		$importMailChimpForm = $newFormId;
	}

	return $importMailChimpForm;
}

function thegem_templates_new_enqueue_scripts() {
	wp_enqueue_script('thegem-templates-new', plugin_dir_url(__FILE__). '/assets/js/new-template.js', array('jquery', 'jquery-fancybox'), THEGEM_THEME_VERSION);
}

function thegem_force_header_type_private($post) {
	if ($post['post_type'] == 'thegem_templates' && $post['post_status'] != 'trash') {
		$post['post_status'] = 'private';
	}
	return $post;
}
//add_filter('wp_insert_post_data', 'thegem_force_header_type_private');

function thegem_templates_widgets_init() {
	if ( ! did_action( 'elementor/loaded' ) ) return;
//	if ( get_post_type() !== 'thegem_templates' ) return;
	foreach ( glob( __DIR__.'/elements/*/element.php' ) as $filename ) {
		if ( empty( $filename ) || ! is_readable( $filename ) ) {
			continue;
		}
		require $filename;
	}
}
add_action('elementor/widgets/register', 'thegem_templates_widgets_init', 5);

function thegem_add_section_settings($obj, $args) {
	if (get_post_type() !== 'thegem_templates' || get_post_meta( get_the_ID(), 'thegem_template_type', true ) !== 'header' ) return;

	$obj->add_control(
		'thegem_row_sticky',
		[
			'label' => __('Header Sticky Section', 'elementor'),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'separator' => 'before',
			'default' => '',
			'label_on' => 'Yes',
			'label_off' => 'No',
			'return_value' => 'yes',
			'description' => __('Enable to make this header section sticky while scrolling', 'thegem'),
		]
	);
}
add_action('elementor/element/section/section_layout/before_section_end', 'thegem_add_section_settings', 10, 2);

function thegem_elementor_background_transition_default($obj, $args) {
	if (get_post_type() !== 'thegem_templates' || get_post_meta( get_the_ID(), 'thegem_template_type', true ) !== 'title' ) return;

	$obj->update_control(
		'background_hover_transition',
		[
			'default' => [
				'size' => '',
			],
		]
	);
}
add_action('elementor/element/section/section_background/after_section_end', 'thegem_elementor_background_transition_default', 10, 2);

function thegem_section_add_sticky_class($obj) {
	if (get_post_type() !== 'thegem_templates' || get_post_meta( get_the_ID(), 'thegem_template_type', true ) !== 'header' || 'section' !== $obj->get_name()) {
		return;
	}

	$settings = $obj->get_settings_for_display();

	if (!empty($settings['thegem_row_sticky'])) {
		$obj->add_render_attribute(
			'_wrapper', 'class', 'header-sticky-row'
		);
	}
}
add_action('elementor/frontend/section/before_render', 'thegem_section_add_sticky_class');

function thegem_add_column_settings_inline($obj, $args) {
	if (get_post_type() !== 'thegem_templates' || get_post_meta( get_the_ID(), 'thegem_template_type', true ) !== 'header') return;

	$obj->add_control(
		'thegem_inline_elements',
		[
			'label' => __( 'Place Elements Inline', 'elementor' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'default' => 'yes',
			'label_on' => 'Yes',
			'label_off' => 'No',
			'return_value' => 'yes',
		]
	);

	$wrap_device_args = [
		'desktop' => [
			'options' => [
				'' => __('Default', 'thegem'),
				'nowrap' => __('Nowrap', 'thegem'),
				'wrap' => __('Wrap', 'thegem'),
				'wrap-reverse' => __('Wrap Reverse', 'thegem'),
			],
		],
		'tablet' => [
			'options' => [
				'' => __('Inherit from Desktop', 'thegem'),
				'nowrap' => __('Nowrap', 'thegem'),
				'wrap' => __('Wrap', 'thegem'),
				'wrap-reverse' => __('Wrap Reverse', 'thegem'),
			],
		],
		'mobile' => [
			'options' => [
				'' => __('Inherit from Tablet', 'thegem'),
				'nowrap' => __('Nowrap', 'thegem'),
				'wrap' => __('Wrap', 'thegem'),
				'wrap-reverse' => __('Wrap Reverse', 'thegem'),
			],
		]
	];
	$obj->add_responsive_control(
		'thegem_items_wrap',
		[
			'label' => __( 'Items Wrap', 'elementor' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'wrap',
			'tablet_default' => '',
			'mobile_default' => '',
			'device_args' => $wrap_device_args,
			'selectors' => [
				'{{WRAPPER}} .elementor-widget-wrap' => 'flex-wrap: {{VALUE}};',
			],
		]
	);
}
add_action('elementor/element/column/layout/before_section_end', 'thegem_add_column_settings_inline', 10, 2);

function thegem_column_add_inline_class($obj) {
	if('column' !== $obj->get_name()) return ;
	if(isset($GLOBALS['thegem_menu_template'])) return ;
	if(isset($GLOBALS['thegem_megamenu_template'])) return ;
	if((get_post_type() !== 'thegem_templates' || get_post_meta( get_the_ID(), 'thegem_template_type', true ) !== 'header') && (!isset($GLOBALS['thegem_template_type']) || $GLOBALS['thegem_template_type'] != 'header')) return;

	$settings = $obj->get_settings_for_display();

	if ( !isset( $settings['thegem_inline_elements']) || $settings['thegem_inline_elements'] == 'yes' ) {
		$obj->add_render_attribute(
			'_widget_wrapper', 'class', 'thegem-column-elements-inline'
		);
	}
}
add_action('elementor/frontend/column/before_render', 'thegem_column_add_inline_class');

function thegem_column_change_template_inline($template, $obj) {
	if ( get_post_type() !== 'thegem_templates' || get_post_meta( get_the_ID(), 'thegem_template_type', true ) !== 'header') return $template;
	if('column' === $obj->get_name()) {
		$old_template = '<div class="elementor-widget-wrap"></div>';
		ob_start();
?>
<#
view.addRenderAttribute( 'block_classes', 'class', 'elementor-widget-wrap' );
if ( 'yes' === settings.thegem_inline_elements) {
	view.addRenderAttribute( 'block_classes', 'class', 'thegem-column-elements-inline' );
}
var elementor_widget_wrap = '<div ' + view.getRenderAttributeString( 'block_classes' ) + '></div>';
print( elementor_widget_wrap );
#>
<?php
		$new_template = ob_get_clean();

		$template = str_replace( $old_template, $new_template, $template );
	}

	return $template;
}
add_filter( 'elementor/column/print_template', 'thegem_column_change_template_inline', 10, 2);

function add_thegem_flex_section($element, $section_id, $args) {
	if ((is_admin() && (get_post_type() !== 'thegem_templates' || thegem_get_template_type(get_the_id()) != 'header')) || $section_id !== '_section_style') return;

	$element->start_controls_section(
		'flex_section',
		[
			'label' => __('Flex Options', 'thegem'),
			'tab' => \Elementor\Controls_Manager::TAB_CONTENT
		]
	);

	$return_value_device_args = [
		'desktop' => [
			'return_value' => 'desktop',
		],
		'tablet' => [
			'return_value' => 'tablet',
		],
		'mobile' => [
			'return_value' => 'mobile',
		]
	];

	if (version_compare(ELEMENTOR_VERSION, '3.4.0', '>=')) {
		$return_value_device_args_hide = $return_value_device_args;
	} else {
		$return_value_device_args_hide = [
			'desktop' => [
				'return_value' => 'desktop',
			],
			'tablet' => [
				'return_value' => 'tablet',
			],
			'mobile' => [
				'return_value' => 'phone',
			]
		];
	}

	$element->add_responsive_control(
		'flex_hide_element',
		[
			'label' => __('Hide Element', 'thegem'),
			'default' => '',
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'render_type' => 'template',
			'label_on' => __('On', 'thegem'),
			'label_off' => __('Off', 'thegem'),
			'prefix_class' => 'elementor-hidden-',
			'device_args' => $return_value_device_args_hide,
			'description' => __('Responsive visibility will take effect only on preview or live page, and not while editing in Elementor', 'thegem'),
		]
	);

	$element->add_responsive_control(
		'flex_sort_order',
		[
			'label' => __('Appearance Order', 'thegem'),
			'type' => \Elementor\Controls_Manager::NUMBER,
			//'devices' => ['tablet', 'mobile'],
			'render_type' => 'template',
			'step' => 1,
			'description' => __('Appearance order of the header element in the column', 'thegem'),
			'selectors' => [
				'.thegem-template-header {{WRAPPER}}' => 'order: {{VALUE}};',
			],
		]
	);

	$element->add_responsive_control(
		'flex_absolute',
		[
			'label' => __('Position Absolute', 'thegem'),
			'default' => '',
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'render_type' => 'template',
			'label_on' => __('On', 'thegem'),
			'label_off' => __('Off', 'thegem'),
			'prefix_class' => 'flex-absolute-',
			'device_args' => $return_value_device_args,
		]
	);

	$horizontal_align_device_args = [
		'desktop' => [
			'options' => [
				'default' => __('Default', 'thegem'),
				'left' => __('Left', 'thegem'),
				'center' => __('Center', 'thegem'),
				'right' => __('Right', 'thegem'),
			],
		],
		'tablet' => [
			'options' => [
				'default' => __('Inherit from Desktop', 'thegem'),
				'unset' => __('Unset (not inherited)', 'thegem'),
				'left' => __('Left', 'thegem'),
				'center' => __('Center', 'thegem'),
				'right' => __('Right', 'thegem'),
			],
		],
		'mobile' => [
			'options' => [
				'default' => __('Inherit from Tablet', 'thegem'),
				'unset' => __('Unset (not inherited)', 'thegem'),
				'left' => __('Left', 'thegem'),
				'center' => __('Center', 'thegem'),
				'right' => __('Right', 'thegem'),
			],
		]
	];

	$element->add_responsive_control(
		'flex_horizontal_align_relative',
		[
			'label' => __('Horizontal Align', 'thegem'),
			'type' => \Elementor\Controls_Manager::SELECT,
			'devices' => ['desktop', 'tablet', 'mobile'],
			'default' => 'default',
			'tablet_default' => 'default',
			'mobile_default' => 'default',
			'device_args' => $horizontal_align_device_args,
			'prefix_class' => 'flex-horizontal-align%s-',
			'description' => __('Horizontal align of header element. Works only if "Place elements inline" setting is activated in the column.', 'thegem'),
		]
	);

	$vertical_align_device_args = [
		'desktop' => [
			'options' => [
				'default' => __('Default', 'thegem'),
				'top' => __('Top', 'thegem'),
				'center' => __('Middle', 'thegem'),
				'bottom' => __('Bottom', 'thegem'),
			],
		],
		'tablet' => [
			'options' => [
				'default' => __('Inherit from Desktop', 'thegem'),
				'unset' => __('Unset (not inherited)', 'thegem'),
				'top' => __('Top', 'thegem'),
				'center' => __('Middle', 'thegem'),
				'bottom' => __('Bottom', 'thegem'),
			],
		],
		'mobile' => [
			'options' => [
				'default' => __('Inherit from Tablet', 'thegem'),
				'unset' => __('Unset (not inherited)', 'thegem'),
				'top' => __('Top', 'thegem'),
				'center' => __('Middle', 'thegem'),
				'bottom' => __('Bottom', 'thegem'),
			],
		]
	];

	$element->add_responsive_control(
		'flex_vertical_align_relative',
		[
			'label' => __('Vertical Align', 'thegem'),
			'type' => \Elementor\Controls_Manager::SELECT,
			'devices' => ['desktop', 'tablet', 'mobile'],
			'default' => 'default',
			'tablet_default' => 'default',
			'mobile_default' => 'default',
			'device_args' => $vertical_align_device_args,
			'prefix_class' => 'flex-vertical-align%s-',
			'description' => __('Vertical align of header element. Works only if "Place elements inline" setting is activated in the column.', 'thegem'),
		]
	);

	$element->add_responsive_control(
		'flex_padding',
		[
			'label' => __('Flex Padding', 'thegem'),
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'render_type' => 'template',
			'size_units' => ['px', '%', 'rem', 'em'],
			'description' => __('Unlike padding in advanced tab, this setting applies to the flex container of the header element.', 'thegem'),
			'selectors' => [
				'.thegem-template-header {{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$element->add_responsive_control(
		'flex_margin',
		[
			'label' => __('Flex Margin', 'thegem'),
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'render_type' => 'template',
			'size_units' => ['px', '%', 'rem', 'em'],
			'description' => __('Unlike margin in advanced tab, this setting applies to the flex container of the header element.', 'thegem'),
			'selectors' => [
				'.thegem-template-header {{WRAPPER}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$element->end_controls_section();
}
add_action('elementor/element/before_section_start', 'add_thegem_flex_section', 10, 3);

add_action( 'elementor/element/social-icons/section_social_icon/before_section_end', function( $element, $args ) {

	if (get_post_type() !== 'thegem_templates' || get_post_meta( get_the_ID(), 'thegem_template_type', true ) !== 'header') return;

	$element->add_responsive_control (
		'icons_size',
		[
			'label' => __('Size Preset', 'thegem'),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'tiny',
			'options' => [
				'' => __('Select Size', 'thegem'),
				'tiny' => __('Tiny', 'thegem'),
				'small' => __('Small', 'thegem'),
				'medium' => __('Medium', 'thegem'),
				'large' => __('Large', 'thegem'),
				'xlarge' => __('Extra Large', 'thegem'),
			],
			'selectors_dictionary' => [
				'tiny' => '16px',
				'small' => '24px',
				'medium' => '48px',
				'large' => '96px',
				'xlarge' => '144px',
			],
			'selectors' => [
				'{{WRAPPER}}' => '--icon-size: {{VALUE}}',
			],
		]
	);
}, 10, 2 );

add_action( 'elementor/element/social-icons/section_social_style/before_section_end', function( $element, $args ) {

	if (get_post_type() !== 'thegem_templates' || get_post_meta( get_the_ID(), 'thegem_template_type', true ) !== 'header') return;

	$element->update_control(
		'icon_padding',
		[
			'default' => [
				'size' => 0,
			],
		]
	);

	$element->update_control(
		'icon_spacing',
		[
			'default' => [
				'size' => 20,
			],
		]
	);

	$element->update_control(
		'shape',
		[
			'default' => 'simple',
			'options' => [
				'simple' => esc_html__( 'Simple Shape', 'elementor' ),
				'rounded' => esc_html__( 'Rounded', 'elementor' ),
				'square' => esc_html__( 'Square', 'elementor' ),
				'circle' => esc_html__( 'Circle', 'elementor' ),
			],
		]
	);
}, 10, 2 );

function thegem_te_delay_class() {
	if(function_exists('thegem_is_wp_rocket_delay_js_active') && thegem_is_wp_rocket_delay_js_active()) {
		return ' detect-delay-click';
	}
	return '';
}

function thegem_deactivate_elementor_header_footer() {
	if (get_post_type() === 'thegem_templates' && (get_post_meta( get_the_ID(), 'thegem_template_type', true ) === 'header' || get_post_meta( get_the_ID(), 'thegem_template_type', true ) === 'footer')) {
		remove_action( 'get_header', [ \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_component( 'theme_support' ), 'get_header' ] );
		remove_action( 'get_footer', [ \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_component( 'theme_support' ), 'get_footer' ] );
	}
}
add_action( 'elementor/theme/register_locations', 'thegem_deactivate_elementor_header_footer', 100 );

function thegem_elementor_theme_templates_popup() {
	if ( 'edit-thegem_templates' !== get_current_screen()->id && 'thegem_templates' !== get_current_screen()->id) return;
	if(!defined('ELEMENTOR_PRO_VERSION')) return ;
	$templates_type = isset($_REQUEST['templates_type']) ? $_REQUEST['templates_type'] : 'all';
	$show_notice = false;
	$notece_message = '';
	$notice_template_type = '';
	$headers = \Elementor\Plugin::$instance->templates_manager->get_source( 'local' )->get_items(array('type' => 'header'));
	$footers = \Elementor\Plugin::$instance->templates_manager->get_source( 'local' )->get_items(array('type' => 'footer'));
	$archives = \Elementor\Plugin::$instance->templates_manager->get_source( 'local' )->get_items(array('type' => 'archive'));
	$single_posts = \Elementor\Plugin::$instance->templates_manager->get_source( 'local' )->get_items(array('type' => 'single-post'));
	$single_products = \Elementor\Plugin::$instance->templates_manager->get_source( 'local' )->get_items(array('type' => 'product'));
	$product_archives = \Elementor\Plugin::$instance->templates_manager->get_source( 'local' )->get_items(array('type' => 'product-archive'));
	if(empty($_COOKIE['thegem_elementor_theme_templates_header_footer']) && ($templates_type === 'all' || $templates_type === 'header' || $templates_type === 'footer') && (!empty($headers) || !empty($footers))) {
		$notece_message = wp_kses(__( 'TheGem has detected <b>header</b> and/or <b>footer</b> templates built with Elementor Pro on your website.<br>Please note: in case any of this Elementor Pro templates are activated for your website, they will override TheGem templates.<br>In order to use headers and/or footers built with TheGem please make sure that no Elementor Pro headers or footers are activated.', 'thegem' ), array('br' => array(), 'b' => array()) );
		$notice_template_type = 'header_footer';
		$show_notice = true;
	} elseif(empty($_COOKIE['thegem_elementor_theme_templates_blog_archive']) && ($templates_type === 'all' || $templates_type === 'blog-archive') && !empty($archives)) {
		$notece_message = wp_kses(__( 'TheGem has detected <b>archive</b> template built with Elementor Pro on your website.<br>Please note: in case any of this Elementor Pro templates are activated for your website, they will override TheGem templates.<br>In order to use archive template built with TheGem please make sure that no Elementor Pro archive template are activated.', 'thegem' ), array('br' => array(), 'b' => array()) );
		$notice_template_type = 'blog_archive';
		$show_notice = true;
	} elseif(empty($_COOKIE['thegem_elementor_theme_templates_single_post']) && ($templates_type === 'all' || $templates_type === 'single-post') && !empty($single_posts)) {
		$notece_message = wp_kses(__( 'TheGem has detected <b>single post</b> template built with Elementor Pro on your website.<br>Please note: in case any of this Elementor Pro templates are activated for your website, they will override TheGem templates.<br>In order to use single post template built with TheGem please make sure that no Elementor Pro single post template are activated.', 'thegem' ), array('br' => array(), 'b' => array()) );
		$notice_template_type = 'single_post';
		$show_notice = true;
	} elseif(empty($_COOKIE['thegem_elementor_theme_templates_single_product']) && ($templates_type === 'all' || $templates_type === 'single-product') && !empty($single_products)) {
		$notece_message = wp_kses(__( 'TheGem has detected <b>single product</b> template built with Elementor Pro on your website.<br>Please note: in case any of this Elementor Pro templates are activated for your website, they will override TheGem templates.<br>In order to use single product template built with TheGem please make sure that no Elementor Pro single product template are activated.', 'thegem' ), array('br' => array(), 'b' => array()) );
		$notice_template_type = 'single_product';
		$show_notice = true;
	} elseif(empty($_COOKIE['thegem_elementor_theme_templates_product_archive']) && ($templates_type === 'all' || $templates_type === 'product-archive') && !empty($product_archives)) {
		$notece_message = wp_kses(__( 'TheGem has detected <b>product archive</b> template built with Elementor Pro on your website.<br>Please note: in case any of this Elementor Pro templates are activated for your website, they will override TheGem templates.<br>In order to use product archive template built with TheGem please make sure that no Elementor Pro product archive template are activated.', 'thegem' ), array('br' => array(), 'b' => array()) );
		$notice_template_type = 'product_archive';
		$show_notice = true;
	}
	if(empty($show_notice)) return ;
	wp_enqueue_style('thegem-activation-google-fonts');
?>
<script type="text/javascript">
(function ( $ ) {
	var setCookie = function ( c_name, value, exdays ) {
		var exdate = new Date();
		exdate.setDate( exdate.getDate() + exdays );
		var c_value = encodeURIComponent( value ) + ((null === exdays) ? "" : "; expires=" + exdate.toUTCString());
		document.cookie = c_name + "=" + c_value;
	};
	$( document ).on( 'click.thegem-notice-dismiss', '#thegem_elementor_theme_templates-popup .thegem-notice-dismiss', function ( e ) {
		e.preventDefault();
		$.fancybox.close();
		setCookie( 'thegem_elementor_theme_templates_' + $(this).data('type'), '1', 30 );
	} );
	thegem_show_elementor_conflict_templates_popup = function() {
		var $popupContent = $('#thegem_elementor_theme_templates-popup');
		if($popupContent.length) {
			$.fancybox.open({
				src : '#thegem_elementor_theme_templates-popup',
				type : 'inline',
				modal: true
			});
		}
	}
	$(function() {
		thegem_show_elementor_conflict_templates_popup();
	})
})( window.jQuery );
</script>
<style>
#thegem_elementor_theme_templates-popup {
	width: 550px;
	text-align: center;
}
#thegem_elementor_theme_templates-popup p {
	font-family: "Source Sans Pro";
	font-weight: normal;
	font-size: 16px;
	line-height: 25px;
	margin-bottom: 30px;
	color: #5f727f;
}
#thegem_elementor_theme_templates-popup b {
	color: #000000;
	text-transform: uppercase;
}
#thegem_elementor_theme_templates-popup button {
	border: none;
	padding: 5px 15px;
	border-radius: 3px;
	font-size: 14px;
	font-weight: 400;
	font-family: 'Montserrat';
	text-transform: uppercase;
	text-align: center;
	min-width: 150px;
	margin: 0 12px;
	text-decoration: none;
	display: inline-block;
	vertical-align: middle;
	box-sizing: border-box;
	box-shadow: none;
	transition: all 0.3s linear;
	-moz-transition: all 0.3s linear;
	-webkit-transition: all 0.3s linear;
	cursor: pointer;
	background-color: #00bcd4;
	color: #fff;
	outline: 0 none;
}
#thegem_elementor_theme_templates-popup button:hover {
	background-color: #3c3950;
	color: #ffffff;
}
</style>
<?php
	echo '<div id="thegem_elementor_theme_templates-popup" style="display: none;"><p>' . $notece_message . '</p>' . '<button type="button" class="thegem-notice-dismiss button-primary" data-type="'.$notice_template_type.'">' . __( 'Dismiss this notice', 'default' ) . '</button></div>';
}
add_action('admin_footer', 'thegem_elementor_theme_templates_popup');

function thegem_get_section_templates_list() {
	$templates = array();
	$templates_list = thegem_get_templates('content');
	foreach ($templates_list as $template) {
		$templates[$template->ID] = $template->post_title . ' (ID = ' . $template->ID . ')';
	}
	return $templates;
}

function thegem_templates_init_post() {
	$pid = thegem_get_option('single_post_builder_preview_post');
	$bpid = get_post_meta(get_the_ID(), 'thegem_single_post_id', true);
	if(!empty($bpid) && get_post_type($bpid) === 'post') {
		$pid = $bpid;
	}
	$view_post = false;
	if(thegem_get_template_type(get_the_ID()) === 'single-post' || get_post_meta(get_the_ID(), 'thegem_is_single_post', true)) {
		if(!empty($pid)) {
			$view_post = get_post($pid);
		}
		if(empty($view_post) || get_post_type($view_post) !== 'post') {
			$args = array(
				'posts_per_page' => '1',
				'post_type' => 'post',
			);
			$test_post = new WP_Query($args);
			while ( $test_post->have_posts() ) {
				$test_post->the_post();
				$view_post = get_post();
			}
			wp_reset_postdata();
		}
	} else {
		$view_post = get_post();
	}
	global $post;
	if(empty($view_post)) return false;
	$GLOBALS['thegem_post_data'] = thegem_get_sanitize_post_data($pid);
	$post = $view_post;
	setup_postdata($post);
	return $post;
}

function thegem_templates_init_product() {
	$pid = thegem_get_option('product_builder_preview_product');
	if(!function_exists('wc_get_product')) return false;
	global $product, $post;
	if(thegem_get_template_type(get_the_ID()) === 'single-product' || get_post_meta(get_the_ID(), 'thegem_is_single_product', true)) {
		$product_id = 0;
		$args = array(
			'posts_per_page' => '1',
			'post_type'	  => 'product',
		);
		if(!empty($pid) && get_post_type($pid) === 'product') {
			$args['p'] = $pid;
		}
		$test_product = new WP_Query($args);
		while ( $test_product->have_posts() ) {
			$test_product->the_post();
			$product_id = get_the_ID();
		}
		wp_reset_postdata();
		$product = wc_get_product($product_id);
	} else {
		$product = wc_get_product();
	}
	if(empty($product)) return false;
	$GLOBALS['thegem_product_data'] = thegem_get_output_product_page_data($product->get_id());
	$GLOBALS['thegem_product_data']['product_page_layout'] = 'default';
	$post = get_post($product->get_id());
	setup_postdata($post);
	return $product;
}

function thegem_templates_close_post($class = '', $title = '', $html = '') {
	$output = $html;
	wp_reset_postdata();
	if(thegem_get_template_type(get_the_ID()) === 'single-post' && empty($html)) {
		$output = '<div class="'.esc_attr($class).' template-post-empty-output default-background">'.$title.'</div>';
	}
	return $output;
}

function thegem_templates_close_product($class = '', $title = '', $html = '') {
	$output = $html;
	wp_reset_postdata();
	if(thegem_get_template_type(get_the_ID()) === 'single-product' && empty($html)) {
		$output = '<div class="'.esc_attr($class).' template-product-empty-output default-background">'.$title.'</div>';
	}
	return $output;
}

function thegem_templates_close_product_archive($class = '', $title = '', $html = '') {
	$output = $html;
	wp_reset_postdata();
	if(thegem_get_template_type(get_the_ID()) === 'product-archive' && empty($html)) {
		$output = '<div class="'.esc_attr($class).' template-product-empty-output default-background">'.$title.'</div>';
	}
	return $output;
}

function thegem_templates_product_archive_source() {
	$obj = get_queried_object();
	if(thegem_get_template_type(get_the_ID()) === 'product-archive') {
		if($tid = thegem_get_option('product_archive_builder_preview')) {
			$term = get_term_by( 'term_id', $tid, 'product_cat');
			if($term) {
				$obj = $term;
			}
		}
	}
	if(is_singular('blocks')) {
		if($slug = get_post_meta(get_queried_object_id(), 'thegem_product_archive_slug', true)) {
			$term = get_term_by( 'slug', $slug, 'product_cat' );
			if($term) {
				$obj = $term;
			}
		}
	}
	return $obj;
}

function thegem_templates_close_blog_archive($class = '', $title = '', $html = '') {
	$output = $html;
	wp_reset_postdata();
	if(thegem_get_template_type(get_the_ID()) === 'blog-archive' && empty($html)) {
		$output = '<div class="'.esc_attr($class).' template-blog-empty-output default-background">'.$title.'</div>';
	}
	return $output;
}

function thegem_templates_blog_archive_source() {
	$obj = get_queried_object();
	if(thegem_get_template_type(get_the_ID()) === 'blog-archive') {
		if($tid = thegem_get_option('blog_archive_builder_preview')) {
			$term = get_term_by( 'term_id', $tid, 'category');
			if($term) {
				$obj = $term;
			}
		}
	}
	if(is_singular('blocks')) {
		if($slug = get_post_meta(get_queried_object_id(), 'thegem_blog_archive_slug', true)) {
			$term = get_term_by( 'slug', $slug, 'category');
			if($term) {
				$obj = $term;
			}
		}
	}
	return $obj;
}

function thegem_templates_close_cart($class = '', $title = '', $html = '') {
	$output = $html;
	wp_reset_postdata();
	if ((thegem_get_template_type(get_the_ID()) === 'cart' || thegem_get_template_type(get_the_ID()) === 'checkout') && empty($html)) {
		$output = '<div class="' . esc_attr($class) . ' template-cart-empty-output default-background">' . $title . '</div>';
	}
	return $output;
}

function thegem_templates_close_checkout_thanks($class = '', $title = '', $html = '') {
	$output = $html;
	wp_reset_postdata();
	if (thegem_get_template_type(get_the_ID()) === 'checkout-thanks' && empty($html)) {
		$output = '<div class="' . esc_attr($class) . ' template-checkout-thanks-empty-output default-background">' . $title . '</div>';
	}
	return $output;
}

function thegem_templates_single_product_page_content() {
	$qoid = get_queried_object_id();
	$editor = \Elementor\Plugin::$instance->editor;
	$is_edit_mode = $editor->is_edit_mode();
	$editor->set_edit_mode( false );
	$post_id = get_the_ID();
	$document = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( $post_id );

	if ( $document->is_built_with_elementor()  && $qoid != $post_id) { ?>
		<div class="product-content entry-content">
			<?php echo \Elementor\Plugin::$instance->frontend->get_builder_content( $post_id, $is_edit_mode ); // phpcs:ignore ?>
		</div>
	<?php } else { ?>
		<div class="product-content entry-content">
			<?php the_content(); ?>
		</div>
	<?php }
	
	\Elementor\Plugin::$instance->editor->set_edit_mode( $is_edit_mode );
}

function thegem_templates_product_tabs_callback($params) {
	global $product, $post;
	
	$tabs = array();
	$description_tab_callback = '';
	
	if($params['description_tab_source'] == 'page_builder') {
		$description_tab_callback = 'thegem_templates_single_product_page_content';
	} else {
		$description_tab_callback = 'woocommerce_product_description_tab';
	}
	
	if ( !empty($params['description']) ) {
		$tabs['description'] = array(
			'title' => esc_html__( $params['description_title'], 'woocommerce'),
			'priority' => 10,
			'callback' => $description_tab_callback
		);
	} else {
		unset( $tabs['description'] );
	}
	
	if ( !empty($params['additional']) ) {
		$tabs['additional_information'] = array(
			'title'	=> esc_html__( $params['additional_title'], 'woocommerce'),
			'priority' => 20,
			'callback' => 'woocommerce_product_additional_information_tab',
		);
	} elseif ( isset( $tabs['additional_information'] ) ) {
		unset( $tabs['additional_information'] );
	}
	
	if ( !empty($params['reviews']) )  {
		$tabs['reviews'] = array(
			'title'	=> $product->get_review_count() > 0 ? sprintf(esc_html__( $params['reviews_title'], 'woocommerce' ).' <sup>%d</sup>', $product->get_review_count()) : esc_html__( $params['reviews_title']),
			'priority' => 30,
			'callback' => 'comments_template',
		);
	} elseif ( isset( $tabs['reviews'] )) {
		unset( $tabs['reviews'] );
	}
	
	return $tabs;
}
add_filter( 'thegem_templates_product_tabs', 'thegem_templates_product_tabs_callback', 11 );

function thegem_te_product_text_styled($params) {
	if (!empty($params)) {
		$styles = [
			$params['text_style'],
			$params['text_weight'],
		];
		return implode(' ', $styles);
	}
	
	return false;
}

if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) {
	add_action(
		'init',
		function() {
			if ( function_exists( 'wc' ) ) {
				wc()->frontend_includes();
			}
		},
		5
	);
}

/* POPUP ITEM POST META BOX */

function thegem_template_popup_register_meta_box($post) {
	if (thegem_get_template_type(get_the_ID()) == 'popup') {
		add_meta_box('thegem_popup_item_settings', __('Popup Settings', 'thegem'), 'thegem_popup_item_settings_box', 'thegem_templates', 'normal', 'high');
	}
}

function thegem_popup_item_settings_box($post) {
	wp_nonce_field('thegem_popup_item_settings_box', 'thegem_popup_item_settings_box_nonce');
	wp_enqueue_script('color-picker');
	wp_enqueue_style('color-picker');
	$popup_item_data = thegem_get_sanitize_popup_item_data($post->ID);
	$animations_entrance = array(
		'' => __( 'None', 'thegem' ),
		'fade_in' => __( 'Fade In', 'thegem' ),
		'fade_in_down' => __( 'Fade In Down', 'thegem' ),
		'fade_in_left' => __( 'Fade In Left', 'thegem' ),
		'fade_in_right' => __( 'Fade In Right', 'thegem' ),
		'fade_in_up' => __( 'Fade In Up', 'thegem' ),
		'zoom_in' => __( 'Zoom In', 'thegem' ),
		'zoom_in_down' => __( 'Zoom In Down', 'thegem' ),
		'zoom_in_left' => __( 'Zoom In Left', 'thegem' ),
		'zoom_in_right' => __( 'Zoom In Right', 'thegem' ),
		'zoom_in_up' => __( 'Zoom In Up', 'thegem' ),
		'bounce_in' => __( 'Bounce In', 'thegem' ),
		'bounce_in_down' => __( 'Bounce In Down', 'thegem' ),
		'bounce_in_left' => __( 'Bounce In Left', 'thegem' ),
		'bounce_in_right' => __( 'Bounce In Right', 'thegem' ),
		'bounce_in_up' => __( 'Bounce In Up', 'thegem' ),
		'slide_in_down' => __( 'Slide In Down', 'thegem' ),
		'slide_in_left' => __( 'Slide In Left', 'thegem' ),
		'slide_in_right' => __( 'Slide In Right', 'thegem' ),
		'slide_in_up' => __( 'Slide In Up', 'thegem' ),
		'rotate_in' => __( 'Rotate In', 'thegem' ),
		'rotate_in_down_left' => __( 'Rotate In Down Left', 'thegem' ),
		'rotate_in_down_right' => __( 'Rotate In Down Right', 'thegem' ),
		'rotate_in_up_left' => __( 'Rotate In Up Left', 'thegem' ),
		'rotate_in_up_right' => __( 'Rotate In Up Right', 'thegem' ),
		'bounce' => __( 'Bounce', 'thegem' ),
		'flash' => __( 'Flash', 'thegem' ),
		'pulse' => __( 'Pulse', 'thegem' ),
		'rubber_band' => __( 'Rubber Band', 'thegem' ),
		'shake' => __( 'Shake', 'thegem' ),
		'head_shake' => __( 'Head Shake', 'thegem' ),
		'swing' => __( 'Swing', 'thegem' ),
		'tada' => __( 'Tada', 'thegem' ),
		'wobble' => __( 'Wobble', 'thegem' ),
		'jello' => __( 'Jello', 'thegem' ),
		'light_speed_in' => __( 'Light Speed In', 'thegem' ),
		'roll_in' => __( 'Roll In', 'thegem' ),
	);
	$animations_exit = array(
		'' => __( 'None', 'thegem' ),
		'fade_out' => __( 'Fade Out', 'thegem' ),
		'fade_out_down' => __( 'Fade Out Down', 'thegem' ),
		'fade_out_left' => __( 'Fade Out Left', 'thegem' ),
		'fade_out_right' => __( 'Fade Out Right', 'thegem' ),
		'fade_out_up' => __( 'Fade Out Up', 'thegem' ),
		'zoom_out' => __( 'Zoom Out', 'thegem' ),
		'zoom_out_down' => __( 'Zoom Out Down', 'thegem' ),
		'zoom_out_left' => __( 'Zoom Out Left', 'thegem' ),
		'zoom_out_right' => __( 'Zoom Out Right', 'thegem' ),
		'zoom_out_up' => __( 'Zoom Out Up', 'thegem' ),
		'bounce_out' => __( 'Bounce Out', 'thegem' ),
		'bounce_out_down' => __( 'Bounce Out Down', 'thegem' ),
		'bounce_out_left' => __( 'Bounce Out Left', 'thegem' ),
		'bounce_out_right' => __( 'Bounce Out Right', 'thegem' ),
		'bounce_out_up' => __( 'Bounce Out Up', 'thegem' ),
		'slide_out_down' => __( 'Slide Out Down', 'thegem' ),
		'slide_out_left' => __( 'Slide Out Left', 'thegem' ),
		'slide_out_right' => __( 'Slide Out Right', 'thegem' ),
		'slide_out_up' => __( 'Slide Out Up', 'thegem' ),
		'rotate_out' => __( 'Rotate Out', 'thegem' ),
		'rotate_out_down_left' => __( 'Rotate Out Down Left', 'thegem' ),
		'rotate_out_down_right' => __( 'Rotate Out Down Right', 'thegem' ),
		'rotate_out_up_left' => __( 'Rotate Out Up Left', 'thegem' ),
		'rotate_out_up_right' => __( 'Rotate Out Up Right', 'thegem' ),
	);
	?>
	<p class="meta-options">
	<div class="thegem-title-settings three-columns">
		<fieldset>
			<legend><?php _e('Popup Width, px', 'thegem'); ?></legend>
			<table class="settings-box-table" width="100%"><tbody><tr>
					<td>
						<label for="popup_width_desktop"><?php _e('Desktop', 'thegem'); ?>:</label><br />
						<input name="thegem_popup_item_data[popup_width_desktop]" type="text" id="popup_width_desktop" value="<?php echo esc_attr($popup_item_data['popup_width_desktop']); ?>" style="width: 100%;" />
					</td>
					<td>
						<label for="popup_width_desktop"><?php _e('Tablet', 'thegem'); ?>:</label><br />
						<input name="thegem_popup_item_data[popup_width_tablet]" type="text" id="popup_width_tablet" value="<?php echo esc_attr($popup_item_data['popup_width_tablet']); ?>" style="width: 100%;" />
					</td>
					<td>
						<label for="popup_width_desktop"><?php _e('Mobile', 'thegem'); ?>:</label><br />
						<input name="thegem_popup_item_data[popup_width_mobile]" type="text" id="popup_width_mobile" value="<?php echo esc_attr($popup_item_data['popup_width_mobile']); ?>" style="width: 100%;" />
					</td>
				</tr></tbody></table>
			<p class="description"><?php echo __('Note: px units are used by default. For % units add values with %, eg. 10%', 'thegem'); ?></p>
		</fieldset>
		<fieldset>
			<legend><?php _e('Popup Paddings, px', 'thegem'); ?></legend>

			<fieldset>
				<legend><?php _e('Top', 'thegem'); ?></legend>
				<table class="settings-box-table" width="100%"><tbody><tr>
						<td>
							<label for="popup_top_padding_desktop"><?php _e('Desktop', 'thegem'); ?>:</label><br />
							<input name="thegem_popup_item_data[popup_top_padding_desktop]" type="number" id="popup_top_padding_desktop" value="<?php echo esc_attr($popup_item_data['popup_top_padding_desktop']); ?>" style="width: 100%;" />
						</td>
						<td>
							<label for="popup_top_padding_tablet"><?php _e('Tablet', 'thegem'); ?>:</label><br />
							<input name="thegem_popup_item_data[popup_top_padding_tablet]" type="number" id="popup_top_padding_tablet" value="<?php echo esc_attr($popup_item_data['popup_top_padding_tablet']); ?>" style="width: 100%;" />
						</td>
						<td>
							<label for="popup_top_padding_mobile"><?php _e('Mobile', 'thegem'); ?>:</label><br />
							<input name="thegem_popup_item_data[popup_top_padding_mobile]" type="number" id="popup_top_padding_mobile" value="<?php echo esc_attr($popup_item_data['popup_top_padding_mobile']); ?>" style="width: 100%;" />
						</td>
					</tr></tbody></table>
			</fieldset>

			<fieldset>
				<legend><?php _e('Right', 'thegem'); ?></legend>
				<table class="settings-box-table" width="100%"><tbody><tr>
						<td>
							<label for="popup_right_padding_desktop"><?php _e('Desktop', 'thegem'); ?>:</label><br />
							<input name="thegem_popup_item_data[popup_right_padding_desktop]" type="number" id="popup_right_padding_desktop" value="<?php echo esc_attr($popup_item_data['popup_right_padding_desktop']); ?>" style="width: 100%;" />
						</td>
						<td>
							<label for="popup_right_padding_tablet"><?php _e('Tablet', 'thegem'); ?>:</label><br />
							<input name="thegem_popup_item_data[popup_right_padding_tablet]" type="number" id="popup_right_padding_tablet" value="<?php echo esc_attr($popup_item_data['popup_right_padding_tablet']); ?>" style="width: 100%;" />
						</td>
						<td>
							<label for="popup_right_padding_mobile"><?php _e('Mobile', 'thegem'); ?>:</label><br />
							<input name="thegem_popup_item_data[popup_right_padding_mobile]" type="number" id="popup_right_padding_mobile" value="<?php echo esc_attr($popup_item_data['popup_right_padding_mobile']); ?>" style="width: 100%;" />
						</td>
					</tr></tbody></table>
			</fieldset>

			<fieldset>
				<legend><?php _e('Bottom', 'thegem'); ?></legend>
				<table class="settings-box-table" width="100%"><tbody><tr>
						<td>
							<label for="popup_bottom_padding_desktop"><?php _e('Desktop', 'thegem'); ?>:</label><br />
							<input name="thegem_popup_item_data[popup_bottom_padding_desktop]" type="number" id="popup_bottom_padding_desktop" value="<?php echo esc_attr($popup_item_data['popup_bottom_padding_desktop']); ?>" style="width: 100%;" />
						</td>
						<td>
							<label for="popup_bottom_padding_tablet"><?php _e('Tablet', 'thegem'); ?>:</label><br />
							<input name="thegem_popup_item_data[popup_bottom_padding_tablet]" type="number" id="popup_bottom_padding_tablet" value="<?php echo esc_attr($popup_item_data['popup_bottom_padding_tablet']); ?>" style="width: 100%;" />
						</td>
						<td>
							<label for="popup_bottom_padding_mobile"><?php _e('Mobile', 'thegem'); ?>:</label><br />
							<input name="thegem_popup_item_data[popup_bottom_padding_mobile]" type="number" id="popup_bottom_padding_mobile" value="<?php echo esc_attr($popup_item_data['popup_bottom_padding_mobile']); ?>" style="width: 100%;" />
						</td>
					</tr></tbody></table>
			</fieldset>

			<fieldset>
				<legend><?php _e('Left', 'thegem'); ?></legend>
				<table class="settings-box-table" width="100%"><tbody><tr>
						<td>
							<label for="popup_left_padding_desktop"><?php _e('Desktop', 'thegem'); ?>:</label><br />
							<input name="thegem_popup_item_data[popup_left_padding_desktop]" type="number" id="popup_left_padding_desktop" value="<?php echo esc_attr($popup_item_data['popup_left_padding_desktop']); ?>" style="width: 100%;" />
						</td>
						<td>
							<label for="popup_left_padding_tablet"><?php _e('Tablet', 'thegem'); ?>:</label><br />
							<input name="thegem_popup_item_data[popup_left_padding_tablet]" type="number" id="popup_left_padding_tablet" value="<?php echo esc_attr($popup_item_data['popup_left_padding_tablet']); ?>" style="width: 100%;" />
						</td>
						<td>
							<label for="popup_left_padding_mobile"><?php _e('Mobile', 'thegem'); ?>:</label><br />
							<input name="thegem_popup_item_data[popup_left_padding_mobile]" type="number" id="popup_left_padding_mobile" value="<?php echo esc_attr($popup_item_data['popup_left_padding_mobile']); ?>" style="width: 100%;" />
						</td>
					</tr></tbody></table>
			</fieldset>
		</fieldset>
		<fieldset>
			<label for="background_color"><?php _e('Popup Background', 'thegem'); ?>:</label><br />
			<input name="thegem_popup_item_data[background_color]" type="text" id="background_color" value="<?php echo esc_attr($popup_item_data['background_color']); ?>" class="color-select" />
		</fieldset>
		<fieldset>
			<legend><?php _e('Horizontal Position', 'thegem'); ?></legend>
			<table class="settings-box-table" width="100%"><tbody><tr>
					<td>
						<label for="horizontal_position_desktop"><?php _e('Desktop', 'thegem'); ?>:</label><br />
						<?php thegem_print_select_input(array('left' => __('Left', 'thegem'), 'center' => __('Center', 'thegem'), 'right' => __('Right', 'thegem')), $popup_item_data['horizontal_position_desktop'], 'thegem_popup_item_data[horizontal_position_desktop]', 'horizontal_position_desktop'); ?><br />
					</td>
					<td>
						<label for="horizontal_position_tablet"><?php _e('Tablet', 'thegem'); ?>:</label><br />
						<?php thegem_print_select_input(array('left' => __('Left', 'thegem'), 'center' => __('Center', 'thegem'), 'right' => __('Right', 'thegem')), $popup_item_data['horizontal_position_tablet'], 'thegem_popup_item_data[horizontal_position_tablet]', 'horizontal_position_tablet'); ?><br />
					</td>
					<td>
						<label for="horizontal_position_mobile"><?php _e('Mobile', 'thegem'); ?>:</label><br />
						<?php thegem_print_select_input(array('left' => __('Left', 'thegem'), 'center' => __('Center', 'thegem'), 'right' => __('Right', 'thegem')), $popup_item_data['horizontal_position_mobile'], 'thegem_popup_item_data[horizontal_position_mobile]', 'horizontal_position_mobile'); ?><br />
					</td>
				</tr></tbody></table>
		</fieldset>
		<fieldset>
			<legend><?php _e('Vertical Position', 'thegem'); ?></legend>
			<table class="settings-box-table" width="100%"><tbody><tr>
					<td>
						<label for="vertical_position_desktop"><?php _e('Desktop', 'thegem'); ?>:</label><br />
						<?php thegem_print_select_input(array('top' => __('Top', 'thegem'), 'center' => __('Center', 'thegem'), 'bottom' => __('Bottom', 'thegem')), $popup_item_data['vertical_position_desktop'], 'thegem_popup_item_data[vertical_position_desktop]', 'vertical_position_desktop'); ?><br />
					</td>
					<td>
						<label for="vertical_position_tablet"><?php _e('Tablet', 'thegem'); ?>:</label><br />
						<?php thegem_print_select_input(array('top' => __('Top', 'thegem'), 'center' => __('Center', 'thegem'), 'bottom' => __('Bottom', 'thegem')), $popup_item_data['vertical_position_tablet'], 'thegem_popup_item_data[vertical_position_tablet]', 'vertical_position_tablet'); ?><br />
					</td>
					<td>
						<label for="vertical_position_mobile"><?php _e('Mobile', 'thegem'); ?>:</label><br />
						<?php thegem_print_select_input(array('top' => __('Top', 'thegem'), 'center' => __('Center', 'thegem'), 'bottom' => __('Bottom', 'thegem')), $popup_item_data['vertical_position_mobile'], 'thegem_popup_item_data[vertical_position_mobile]', 'vertical_position_mobile'); ?><br />
					</td>
				</tr></tbody></table>
		</fieldset>
		<fieldset>
			<legend><?php _e('Background Overlay', 'thegem'); ?></legend>
			<table class="settings-box-table" width="100%"><tbody><tr>
					<td>
						<input name="thegem_popup_item_data[background_overlay]" type="checkbox" id="background_overlay" value="1" <?php checked($popup_item_data['background_overlay'], 1); ?> />
						<label for="background_overlay"><?php _e('Enable', 'thegem'); ?></label>
					</td>
					<td id="background_overlay_color_wrap">
						<label for="background_overlay_color"><?php _e('Background Color', 'thegem'); ?>:</label><br />
						<input name="thegem_popup_item_data[background_overlay_color]" type="text" id="background_overlay_color" value="<?php echo esc_attr($popup_item_data['background_overlay_color']); ?>" class="color-select" />
					</td>
				</tr></tbody></table>
		</fieldset>
		<fieldset>
			<legend><?php _e('Close Icon', 'thegem'); ?></legend>
			<table class="settings-box-table" width="100%"><tbody><tr>
					<td>
						<label for="close_icon_position"><?php _e('Icon Position', 'thegem'); ?>:</label><br />
						<?php thegem_print_select_input(array('inside' => __('Inside', 'thegem'), 'outside' => __('Outside', 'thegem')), esc_attr($popup_item_data['close_icon_position']), 'thegem_popup_item_data[close_icon_position]', 'close_icon_position'); ?>
					</td>
					<td>
						<label for="close_icon_color"><?php _e('Icon Color', 'thegem'); ?>:</label><br />
						<input name="thegem_popup_item_data[close_icon_color]" type="text" id="close_icon_color" value="<?php echo esc_attr($popup_item_data['close_icon_color']); ?>" class="color-select" />
					</td>
				</tr></tbody></table>
		</fieldset>
		<fieldset>
			<legend><?php _e('Animations', 'thegem'); ?></legend>
			<table class="settings-box-table" width="100%"><tbody><tr>
					<td>
						<label for="animation_entrance"><?php _e('Entrance Animation', 'thegem'); ?>:</label><br />
						<?php thegem_print_select_input($animations_entrance, esc_attr($popup_item_data['animation_entrance']), 'thegem_popup_item_data[animation_entrance]', 'animation_entrance'); ?>
					</td>
					<td>
						<label for="animation_exit"><?php _e('Exit Animation', 'thegem'); ?>:</label><br />
						<?php thegem_print_select_input($animations_exit, esc_attr($popup_item_data['animation_exit']), 'thegem_popup_item_data[animation_exit]', 'animation_exit'); ?>
					</td>
				</tr></tbody></table>
		</fieldset>
	</div>
	<script type="text/javascript">
		(function($) {
			$(function() {
				$('#background_overlay').change(function() {
					if ($(this).is(":checked")) {
						$('#background_overlay_color_wrap').show();
					} else {
						$('#background_overlay_color_wrap').hide();
					}
				}).trigger('change');
			});
		})(jQuery);
	</script>
	</p>
	<?php
}

function thegem_popup_item_save_meta_box_data($post_id) {
	if (!isset($_POST['thegem_popup_item_settings_box_nonce'])) {
		return;
	}
	if (!wp_verify_nonce($_POST['thegem_popup_item_settings_box_nonce'], 'thegem_popup_item_settings_box')) {
		return;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (isset($_POST['post_type']) && 'thegem_templates' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return;
		}
	} else {
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}
	}

	if (!isset($_POST['thegem_popup_item_data']) || !is_array($_POST['thegem_popup_item_data'])) {
		return;
	}

	$popup_item_data = thegem_get_sanitize_popup_item_data(0, $_POST['thegem_popup_item_data']);
	update_post_meta($post_id, 'thegem_popup_item_data', $popup_item_data);
}
add_action('save_post', 'thegem_popup_item_save_meta_box_data');

if (!function_exists('thegem_get_sanitize_popup_item_data')) {
	function thegem_get_sanitize_popup_item_data($post_id = 0, $item_data = array()) {
		$popup_item_data = array(
			'popup_width_desktop' => '600',
			'popup_width_tablet' => '600',
			'popup_width_mobile' => '300',
			'popup_top_padding_desktop' => '0',
			'popup_top_padding_tablet' => '0',
			'popup_top_padding_mobile' => '0',
			'popup_right_padding_desktop' => '0',
			'popup_right_padding_tablet' => '0',
			'popup_right_padding_mobile' => '0',
			'popup_bottom_padding_desktop' => '0',
			'popup_bottom_padding_tablet' => '0',
			'popup_bottom_padding_mobile' => '0',
			'popup_left_padding_desktop' => '0',
			'popup_left_padding_tablet' => '0',
			'popup_left_padding_mobile' => '0',
			'background_color' => '#FFFFFF',
			'horizontal_position_desktop' => 'center',
			'horizontal_position_tablet' => 'center',
			'horizontal_position_mobile' => 'center',
			'vertical_position_desktop' => 'center',
			'vertical_position_tablet' => 'center',
			'vertical_position_mobile' => 'center',
			'background_overlay' => true,
			'background_overlay_color' => '#202225c4',
			'close_icon_position' => 'outside',
			'close_icon_color' => '#FFFFFF',
			'animation_entrance' => 'none',
			'animation_exit' => 'none',
		);
		$animations_entrance = array(
			'',
			'fade_in',
			'fade_in_down',
			'fade_in_left',
			'fade_in_right',
			'fade_in_up',
			'zoom_in',
			'zoom_in_down',
			'zoom_in_left',
			'zoom_in_right',
			'zoom_in_up',
			'bounce_in',
			'bounce_in_down',
			'bounce_in_left',
			'bounce_in_right',
			'bounce_in_up',
			'slide_in_down',
			'slide_in_left',
			'slide_in_right',
			'slide_in_up',
			'rotate_in',
			'rotate_in_down_left',
			'rotate_in_down_right',
			'rotate_in_up_left',
			'rotate_in_up_right',
			'bounce',
			'flash',
			'pulse',
			'rubber_band',
			'shake',
			'head_shake',
			'swing',
			'tada',
			'wobble',
			'jello',
			'light_speed_in',
			'roll_in'
		);
		$animations_exit = array(
			'',
			'fade_out',
			'fade_out_down',
			'fade_out_left',
			'fade_out_right',
			'fade_out_up',
			'zoom_out',
			'zoom_out_down',
			'zoom_out_left',
			'zoom_out_right',
			'zoom_out_up',
			'bounce_out',
			'bounce_out_down',
			'bounce_out_left',
			'bounce_out_right',
			'bounce_out_up',
			'slide_out_down',
			'slide_out_left',
			'slide_out_right',
			'slide_out_up',
			'rotate_out',
			'rotate_out_down_left',
			'rotate_out_down_right',
			'rotate_out_up_left',
			'rotate_out_up_right'
		);
		if (is_array($item_data) && !empty($item_data)) {
			if (!$item_data['background_overlay']) {
				$item_data['background_overlay'] = 0;
			}
			$popup_item_data = array_merge($popup_item_data, $item_data);
		} elseif ($post_id != 0 && function_exists('thegem_get_post_data')) {
			$popup_item_data = thegem_get_post_data($popup_item_data, 'popup_item', $post_id);
		}

		$popup_item_data['popup_width_desktop'] = sanitize_text_field($popup_item_data['popup_width_desktop']);
		$popup_item_data['popup_width_tablet'] = sanitize_text_field($popup_item_data['popup_width_tablet']);
		$popup_item_data['popup_width_mobile'] = sanitize_text_field($popup_item_data['popup_width_mobile']);
		$popup_item_data['popup_top_padding_desktop'] = sanitize_text_field($popup_item_data['popup_top_padding_desktop']);
		$popup_item_data['popup_top_padding_tablet'] = sanitize_text_field($popup_item_data['popup_top_padding_tablet']);
		$popup_item_data['popup_top_padding_mobile'] = sanitize_text_field($popup_item_data['popup_top_padding_mobile']);
		$popup_item_data['popup_right_padding_desktop'] = sanitize_text_field($popup_item_data['popup_right_padding_desktop']);
		$popup_item_data['popup_right_padding_tablet'] = sanitize_text_field($popup_item_data['popup_right_padding_tablet']);
		$popup_item_data['popup_right_padding_mobile'] = sanitize_text_field($popup_item_data['popup_right_padding_mobile']);
		$popup_item_data['popup_bottom_padding_desktop'] = sanitize_text_field($popup_item_data['popup_bottom_padding_desktop']);
		$popup_item_data['popup_bottom_padding_tablet'] = sanitize_text_field($popup_item_data['popup_bottom_padding_tablet']);
		$popup_item_data['popup_bottom_padding_mobile'] = sanitize_text_field($popup_item_data['popup_bottom_padding_mobile']);
		$popup_item_data['popup_left_padding_desktop'] = sanitize_text_field($popup_item_data['popup_left_padding_desktop']);
		$popup_item_data['popup_left_padding_tablet'] = sanitize_text_field($popup_item_data['popup_left_padding_tablet']);
		$popup_item_data['popup_left_padding_mobile'] = sanitize_text_field($popup_item_data['popup_left_padding_mobile']);

		$popup_item_data['background_color'] = sanitize_text_field($popup_item_data['background_color']);

		$popup_item_data['horizontal_position_desktop'] = thegem_check_array_value(array('left', 'center', 'right'), $popup_item_data['horizontal_position_desktop'], 'center');
		$popup_item_data['horizontal_position_tablet'] = thegem_check_array_value(array('left', 'center', 'right'), $popup_item_data['horizontal_position_tablet'], 'center');
		$popup_item_data['horizontal_position_mobile'] = thegem_check_array_value(array('left', 'center', 'right'), $popup_item_data['horizontal_position_mobile'], 'center');
		$popup_item_data['vertical_position_desktop'] = thegem_check_array_value(array('top', 'center', 'bottom'), $popup_item_data['vertical_position_desktop'], 'center');
		$popup_item_data['vertical_position_tablet'] = thegem_check_array_value(array('top', 'center', 'bottom'), $popup_item_data['vertical_position_tablet'], 'center');
		$popup_item_data['vertical_position_mobile'] = thegem_check_array_value(array('top', 'center', 'bottom'), $popup_item_data['vertical_position_mobile'], 'center');

		$popup_item_data['background_overlay'] = $popup_item_data['background_overlay'] ? 1 : 0;
		$popup_item_data['background_overlay_color'] = sanitize_text_field($popup_item_data['background_overlay_color']);

		$popup_item_data['close_icon_position'] = thegem_check_array_value(array('inside', 'outside'), $popup_item_data['close_icon_position'], 'center');
		$popup_item_data['close_icon_color'] = sanitize_text_field($popup_item_data['close_icon_color']);

		$popup_item_data['animation_entrance'] = thegem_check_array_value($animations_entrance, $popup_item_data['animation_entrance'], 'center');
		$popup_item_data['animation_exit'] = thegem_check_array_value($animations_exit, $popup_item_data['animation_exit'], 'center');

		return $popup_item_data;
	}
}

function thegem_elementor_preview_error_handler() {
	if(!(defined('ELEMENTOR_VERSION') && \Elementor\Plugin::$instance->preview->is_preview_mode())) return ;
	if(!is_singular()) return ;
	$template_id = 0;
	$current_post = get_queried_object();
	if(get_post_type($current_post) === 'product') {
		$template_id = thegem_single_product_template();
		$widget_title = esc_html__( 'Product Content', 'thegem' );
		$post_type = esc_html__( 'product', 'thegem' );
		$template_type = esc_html__( 'Single Product', 'thegem' );
		$widget_type = 'thegem-template-post-content';
	}
	if(in_array(get_post_type($current_post), array_merge(array('post', 'thegem_news'), thegem_get_available_po_custom_post_types()), true)) {
		$template_id = thegem_single_post_template();
		$widget_title = esc_html__( 'Post Content', 'thegem' );
		$post_type = esc_html__( 'post', 'thegem' );
		$template_type = esc_html__( 'Single Post', 'thegem' );
		$widget_type = 'thegem-template-product-content';
	}
	if(empty($template_id)) return ;
	$edit_url = add_query_arg(array('post' => $template_id, 'action' => 'elementor'), admin_url( 'post.php' ));;
	$has_the_content = false;
	$document = \Elementor\Plugin::$instance->documents->get( $template_id );
	$content = $document ? $document->get_elements_data() : [];
	\Elementor\Plugin::$instance->db->iterate_data( $content, function( $element ) use ( &$has_the_content, $widget_type ) {
		if ( isset( $element['widgetType'] ) && $widget_type === $element['widgetType'] ) {
			$has_the_content = true;
		}
	} );
	if($has_the_content) return ;
	wp_localize_script( 'elementor-frontend', 'elementorPreviewErrorArgs', [
		'headerMessage' => sprintf( esc_html__( 'Sorry, the %s element was not found in your template.', 'thegem' ), $widget_title ),
		'message' => sprintf( esc_html__( 'The template you have selected in TheGem Templates Builder and applied to this %s doesn\'t contain a %s element. Please edit your %s template and add a %s element or choose another template in order for Elementor to work on this page. ', 'thegem' ), $post_type, $widget_title, $template_type, $widget_title),
		'strings' => [
			'confirm' => esc_html__( 'Edit Template', 'thegem' ),
		],
		'className' => 'thegem-elementor-editor-preview-error',
		'confirmURL' => $edit_url,
	] );
}
add_action( 'wp_footer', 'thegem_elementor_preview_error_handler' );

function thegem_save_template_data_to_translation($post_id, $data, $job) {
	$origin_post = get_post($job->original_doc_id);
	if($job->original_doc_id && $origin_post && get_post_type($origin_post) == 'thegem_templates') {
		$template_type = get_post_meta( $job->original_doc_id, 'thegem_template_type', true );
		update_post_meta($post_id, 'thegem_template_type', $template_type);
	}
}
add_action( 'wpml_translation_job_saved', 'thegem_save_template_data_to_translation', 10, 3 );