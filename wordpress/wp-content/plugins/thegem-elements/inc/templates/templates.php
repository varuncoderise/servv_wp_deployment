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
	if(!('edit.php' === $pagenow && 'thegem_templates' === $typenow) || ! empty( $query->query_vars['meta_key'] )) {
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
				$post_data['post_content'] = thegem_template_import_content($template);
				$post_data['post_content'] = preg_replace('%https://democontent.codex-themes.com/thegem-(elementor-)?blocks(-pb)?%', site_url(), $post_data['post_content']);
				if($type === 'header') {
					thegem_templates_import_menus();
				}
				if(!empty($template['wpb_css'])) {
					$meta_data[ '_wpb_post_custom_css' ] = $template['wpb_css'];
				}
				if(!empty($template['metas']) && is_array($template['metas'])) {
					foreach($template['metas'] as $meta_key => $meta_value) {
						$meta_data[ $meta_key ] = $meta_value;
					}
				}
			}
		}

		$meta_data[ 'thegem_template_type' ] = $type;
		$post_data['meta_input'] = $meta_data;

		$template_id = wp_insert_post( $post_data );
		$redirect_link = add_query_arg(array('post' => $template_id, 'action' => 'edit'), admin_url( 'post.php' ));
		if(defined('WPB_VC_VERSION')) {
			$redirect_link = vc_frontend_editor()->getInlineUrl('', $template_id);
		}
		wp_redirect($redirect_link);

		die;
}
add_action( 'admin_action_thegem_templates_new', 'thegem_templates_new_create' );

function thegem_template_import_content($template) {
	$import_attachments = thegem_template_uploadDummyAttachments($template['attachment_ids']);
	$import_cf = array();
	$import_mcf = array();
	if(!empty($template['cf_ids'])) {
		$import_cf = thegem_templates_importContactForms($template['cf_ids']);
	}
	if(!empty($template['mcf_ids'])) {
		$import_mcf = thegem_templates_importMailChimpForms($template['mcf_ids']);
	}
	$content = thegem_template_replaceImportData($template['content'], $import_attachments, $import_cf, $import_mcf);
	return $content;
}

function thegem_template_uploadDummyAttachments($attachment_ids) {
	if (empty($attachment_ids)) return [];

	$items = [];

	require __DIR__.'/import-data.php';

	foreach ($attachment_ids as $postId) {
		if (!empty($dummyList) && $dummyList[$postId]) {
			$filename = $dummyList[$postId];
			$prefixedFilename = 'thegem_template_image_'.$filename;
			$attachmentId = thegem_template_getAttachmentIdByFilename($prefixedFilename);

			if ($attachmentId) {
				$items[$postId] = $attachmentId;
			} else {
				$file = __DIR__ . '/assets/img/data/' . $filename;

				if (file_exists(wp_upload_dir()['path'].'/'.$prefixedFilename)) {
					unlink(wp_upload_dir()['path'].'/'.$prefixedFilename);
				}

				$tmpFile = wp_upload_dir()['basedir'].'/'.$prefixedFilename;

				if (!copy($file, $tmpFile)) {
					continue;
				}

				$file_array = [
					'name' => $prefixedFilename,
					'tmp_name' => $tmpFile,
					'error' => 0,
					'size' => filesize($tmpFile),
				];

				$items[$postId] = media_handle_sideload($file_array);
			}
		}
	}

	return $items;
}

function thegem_template_replaceImportData($content, $attachments, $import_cf, $import_mcf) {
	global $thegem_template_import_attachments, $thegem_template_import_import_cf, $thegem_template_import_import_mcf;
	$thegem_template_import_attachments = $attachments;
	$thegem_template_import_import_cf = $import_cf;
	$thegem_template_import_import_mcf = $import_mcf;

	// replace attachment ids
	$content = preg_replace_callback("/{{IMG_ID=\d+}}/", function ($matches) {
		global $thegem_template_import_attachments;
		$id = preg_replace('/[^0-9]/', '', $matches[0]);
		return !empty($thegem_template_import_attachments[$id]) ? $thegem_template_import_attachments[$id] : $id;
	}, $content);

	$content = preg_replace_callback("/{{IMG_URL=\d+}}/", function ($matches) {
		global $thegem_template_import_attachments;
		$id = preg_replace('/[^0-9]/', '', $matches[0]);
		$newId = !empty($thegem_template_import_attachments[$id]) ? $thegem_template_import_attachments[$id] : $id;
		return wp_get_attachment_url($newId).'?id='.$newId;
	}, $content);

		$content = preg_replace_callback("/{{CF_ID=\d+}}/", function ($matches) {
			global $thegem_template_import_import_cf;
			$id = preg_replace('/[^0-9]/', '', $matches[0]);
			return !empty($thegem_template_import_import_cf[$id]) ? $thegem_template_import_import_cf[$id] : $id;
		}, $content);

		$content = preg_replace_callback("/{{MF_ID=\d+}}/", function ($matches) {
			global $thegem_template_import_import_mcf;
			$id = preg_replace('/[^0-9]/', '', $matches[0]);
			return !empty($thegem_template_import_import_mcf[$id]) ? $thegem_template_import_import_mcf[$id] : $id;
		}, $content);

	return $content;
}

function thegem_template_getAttachmentIdByFilename($filename) {
	$name = preg_replace('/\.(jpg|png|gif|bmp|svg|jpeg)/', '', $filename);
	$posts = get_posts(['post_type'=>'attachment', 'name'=>$name, 'post_mime_type' => 'image', 'posts_per_page'=>-1]);

	return !empty($posts) ? $posts[0]->ID : false;
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

function thegem_templates_importContactForms($ids=[]) {
	if (! defined( 'WPCF7_VERSION' )) {
		return [];
	}
	if (empty($ids)) return [];

	require __DIR__.'/import-data.php';

	if(empty($contactForms)) return ;

	$importContactForms = array();

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

	foreach ($ids as $id) {
		$importForm = $contactForms[$id];

		if (empty($importForm)) {
			continue;
		}

		if (!empty($formIds[$importForm['name']])) {
			$importContactForms[$id] = $formIds[$importForm['name']];
		} else {
			$post_data = array();
			$post_data['post_type'] = 'wpcf7_contact_form';
			$post_data['post_status'] = 'publish';
			$post_data['post_title'] = $importForm['name'];
			$post_data['meta_input'] = array(
				'_form' => $importForm['form']
			);
			$newFormId = wp_insert_post( $post_data );
			$importContactForms[$id] = $newFormId;
		}
	}

	return $importContactForms;
}

function thegem_templates_importMailChimpForms($ids=[]) {
	if (! defined( 'YIKES_MC_VERSION' )) {
		return [];
	}
	if (empty($ids)) return [];

	require __DIR__.'/import-data.php';

	if(empty($mailChimpForms)) return ;

	$importMailChimpForms = array();
	$interface = yikes_easy_mailchimp_extender_get_form_interface();

	$formIds = [];
	foreach ($interface->get_all_forms() as $itemForm) {
		$formIds[$itemForm['unique_id']] = $itemForm;
	}

	foreach ($ids as $id) {
		$importForm = $mailChimpForms[$id];

		if (empty($importForm)) {
			continue;
		}

		if (!empty($formIds[$id])) {
			$importMailChimpForms[$id] = $formIds[$id]['id'];
		} else {
			$newFormId = $interface->create_form($importForm);
			$importMailChimpForms[$id] = $newFormId;
		}
	}

	return $importMailChimpForms;
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

function thegem_templates_shortcodes_init() {
	$templates_elements = array();
	foreach ( glob( __DIR__.'/elements/*/element.php' ) as $filename ) {
		if ( empty( $filename ) || ! is_readable( $filename ) ) {
			continue;
		}
		require $filename;
	}
}
add_action('init', 'thegem_templates_shortcodes_init', 5);

function thegem_vc_add_element_categories_templates($tabs) {
	$builder_tab = false;
	$builder_tab_key = -1;
	foreach($tabs as $key => $tab) {
		if (
            $tab['name'] === __('Header Builder', 'thegem') ||
            $tab['name'] === __('Single Product Builder', 'thegem') ||
            $tab['name'] === __('Mega Menu Builder', 'thegem') ||
            $tab['name'] === __('Cart Builder', 'thegem') ||
            $tab['name'] === __('Checkout Builder', 'thegem') ||
            $tab['name'] === __('Purchase Summary Builder', 'thegem') ||
            $tab['name'] === __('Archive Product Builder', 'thegem') ||
            $tab['name'] === __('Archive Blog Builder', 'thegem') ||
            $tab['name'] === __('Single Post Builder', 'thegem') ||
            $tab['name'] === __('Popups Builder', 'thegem')
        ) {
			$builder_tab = $tab;
			$builder_tab_key = $key;
		}
	}
	if($builder_tab_key > -1) {
		unset($tabs[$builder_tab_key]);
		$builder_tab['active'] = 1;
		foreach($tabs as $key => $tab) {
			if($tab['active']) {
				$tabs[$key]['active'] = false;
			}
		}
		$tabs = array_merge(array($builder_tab), $tabs);
	}
	return $tabs;
}
add_filter('vc_add_element_categories', 'thegem_vc_add_element_categories_templates');

function thegem_templates_vc_row_column_init() {
	global $pagenow;
	if((is_admin() && in_array($pagenow, array('post-new.php', 'post.php', 'admin-ajax.php'))) || (!is_admin() && in_array($pagenow, array('index.php')))) {
		$activate = 0;
		if($pagenow === 'post-new.php' && !empty($_REQUEST['post_type']) && $_REQUEST['post_type'] === 'thegem_templates') {
			$activate = true;
		}
		if($pagenow === 'post.php' && !empty($_REQUEST['post']) && get_post_type($_REQUEST['post']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post']) == 'header') {
			$activate = true;
		}
		if($pagenow === 'post.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post_id']) == 'header') {
			$activate = true;
		}
		if($pagenow === 'admin-ajax.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post_id']) == 'header') {
			$activate = true;
		}
		if($pagenow === 'index.php' && !empty($_REQUEST['vc_post_id']) && get_post_type($_REQUEST['vc_post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['vc_post_id']) == 'header') {
			$activate = true;
		}
		if($activate && defined('WPB_VC_VERSION')) {
			vc_add_param('vc_row', array(
				'type' => 'checkbox',
				'heading' => __('Header Sticky Row', 'thegem'),
				'param_name' => 'header_sticky_row',
				'weight' => 10,
				'value' => array(__('Yes', 'thegem') => '1'),
				'description' => __('If checked the row will be set to sticky', 'thegem'),
			));
			vc_add_param('vc_row', array(
				'type' => 'checkbox',
				'heading' => __('Shrink sticky row', 'thegem'),
				'param_name' => 'header_shrink_row',
				'weight' => 10,
				'value' => array(__('Yes', 'thegem') => '1'),
				'dependency' => array(
					'element' => 'header_sticky_row',
					'not_empty' => true
				),
			));
			$param = WPBMap::getParam( 'vc_row', 'full_width' );
			$param['std'] = 'stretch_row';
			$param['save_always'] = true;
			vc_update_shortcode_param( 'vc_row', $param );
			$param = WPBMap::getParam( 'vc_column', 'offset' );
			$param['std'] = 'vc_col-xs-12';
			$param['save_always'] = true;
			vc_update_shortcode_param( 'vc_column', $param );

			$param_equal = array(__('Yes', 'thegem') => 'yes');
			$param = WPBMap::getParam( 'vc_row', 'equal_height' );
			$param['value'] = array_merge($param_equal, $param['value']);
			$param['std'] = 'yes';
			$param['save_always'] = true;
			vc_update_shortcode_param( 'vc_row', $param );

			$param = WPBMap::getParam( 'vc_row', 'content_placement' );
			$param['std'] = 'middle';
			$param['save_always'] = true;
			vc_update_shortcode_param( 'vc_row', $param );

			vc_remove_param( 'gem_heading', "css" );
			vc_remove_param( 'gem_heading', "heading_disable_desktop" );
			vc_remove_param( 'gem_heading', "heading_disable_tablet" );
			vc_remove_param( 'gem_heading', "heading_disable_mobile" );

			vc_remove_param( 'vc_column_text', "css" );

			add_action('vc_backend_editor_enqueue_js_css', 'thegem_tamplates_editor_script', 11);
			add_action('vc_frontend_editor_enqueue_js_css', 'thegem_tamplates_editor_script', 11);
			add_action('vc_load_iframe_jscss', 'thegem_tamplates_editor_script', 11);
		}
	}
}
add_action('init', 'thegem_templates_vc_row_column_init', 11);

function thegem_templates_single_product_heading_init() {
	global $pagenow;
	if((is_admin() && in_array($pagenow, array('post-new.php', 'post.php', 'admin-ajax.php'))) || (!is_admin() && in_array($pagenow, array('index.php')))) {
		$activate = 0;
		if($pagenow === 'post-new.php' && !empty($_REQUEST['post_type']) && $_REQUEST['post_type'] === 'thegem_templates') {
			$activate = true;
		}
		if($pagenow === 'post.php' && !empty($_REQUEST['post']) && get_post_type($_REQUEST['post']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post']) == 'single-product') {
			$activate = true;
		}
		if($pagenow === 'post.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post_id']) == 'single-product') {
			$activate = true;
		}
		if($pagenow === 'admin-ajax.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post_id']) == 'single-product') {
			$activate = true;
		}
		if($pagenow === 'index.php' && !empty($_REQUEST['vc_post_id']) && get_post_type($_REQUEST['vc_post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['vc_post_id']) == 'single-product') {
			$activate = true;
		}
		if($activate && defined('WPB_VC_VERSION')) {
			/*vc_remove_param( 'gem_heading', "css" );
			vc_remove_param( 'gem_heading', "heading_disable_desktop" );
			vc_remove_param( 'gem_heading', "heading_disable_tablet" );
			vc_remove_param( 'gem_heading', "heading_disable_mobile" );
			
			vc_remove_param( 'vc_column_text', "css" );*/
			
			add_action('vc_backend_editor_enqueue_js_css', 'thegem_tamplates_editor_script', 11);
			add_action('vc_frontend_editor_enqueue_js_css', 'thegem_tamplates_editor_script', 11);
			add_action('vc_load_iframe_jscss', 'thegem_tamplates_editor_script', 11);
		}
	}
}
add_action('init', 'thegem_templates_single_product_heading_init', 11);

function thegem_templates_shortcode_atts_vc_row($out, $pairs, $atts, $shortcode) {
	if(thegem_is_header_builder_front()) {
		$out['header_sticky_row'] = isset($atts['header_sticky_row']) ? $atts['header_sticky_row'] : '';
		$out['header_shrink_row'] = isset($atts['header_shrink_row']) ? $atts['header_shrink_row'] : '';
	}
	return $out;
}
add_filter( 'shortcode_atts_vc_row', 'thegem_templates_shortcode_atts_vc_row', 10, 4 );

function thegem_templates_shortcode_atts_vc_column($out, $pairs, $atts, $shortcode) {
	global $pagenow;
	if($pagenow === 'index.php' && !empty($_REQUEST['vc_post_id']) && get_post_type($_REQUEST['vc_post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['vc_post_id']) == 'header') {
		$out['template_flex'] = 1;
	}
	if($pagenow === 'index.php' && !empty($_REQUEST['vc_post_id']) && get_post_type($_REQUEST['vc_post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['vc_post_id']) == 'single-product') {
		$out['template_flex'] = 1;
	}
	return $out;
}
add_filter( 'shortcode_atts_vc_column', 'thegem_templates_shortcode_atts_vc_column', 10, 4 );
add_filter( 'shortcode_atts_vc_column_inner', 'thegem_templates_shortcode_atts_vc_column', 10, 4 );

function thegem_is_header_builder_front() {
	return (get_post_type() === 'thegem_templates' && thegem_get_template_type(get_the_id()) === 'header') || get_post_type() === 'blocks';
}

function thegem_is_product_builder_front() {
	return (get_post_type() === 'thegem_templates' && thegem_get_template_type(get_the_id()) === 'single-product') || get_post_type() === 'blocks';
}

function thegem_tamplates_editor_script() {
	if (isset($_REQUEST['post'])) {
		$type = thegem_get_template_type($_REQUEST['post']);
	}

	if (isset($_REQUEST['post_id'])) {
		$type = thegem_get_template_type($_REQUEST['post_id']);
	}

	if (isset($_REQUEST['vc_post_id'])) {
		$type = thegem_get_template_type($_REQUEST['vc_post_id']);
	}

	wp_enqueue_script('thegem_templates_editor', plugin_dir_url(__FILE__).'assets/js/editor.js', array('jquery'));
	wp_localize_script('thegem_templates_editor', 'TheGemTemplatesEditorTexts', array(
		'texts' => array(
			'vc_welcome_header' => $type != 'header' ? esc_html__('you have blank page. start adding elements, pre-made blocks or saved templates.', 'thegem') : __('You have blank header template. Start adding elements or pre-made headers. <span class="note">One column per one row layout with inline elements is recommended.</span>', 'thegem'),
		)
	));
	wp_enqueue_style('icons-elegant', THEGEM_THEME_URI . '/css/icons-elegant.css', array(), THEGEM_THEME_VERSION);
	wp_enqueue_style('icons-material', THEGEM_THEME_URI . '/css/icons-material.css', array(), THEGEM_THEME_VERSION);
	wp_enqueue_style('icons-fontawesome', THEGEM_THEME_URI . '/css/icons-fontawesome.css', array(), THEGEM_THEME_VERSION);
	wp_enqueue_style('icons-thegemdemo', THEGEM_THEME_URI . '/css/icons-thegemdemo.css', array(), THEGEM_THEME_VERSION);
	wp_enqueue_style('icons-thegem-header', THEGEM_THEME_URI . '/css/icons-thegem-header.css', array(), THEGEM_THEME_VERSION);
	if(thegem_icon_userpack_enabled()) {
		wp_enqueue_style('icons-userpack', get_stylesheet_directory_uri() . '/css/icons-userpack.css', array(), THEGEM_THEME_VERSION);
	}
}

function thegem_data_editor_attribute($value) {
	if(function_exists('vc_is_page_editable') && vc_is_page_editable()) {
		return ' data-editor-container-class="'.esc_attr($value).'"';
	}
	return '';
}

function thegem_get_menu_list (){
	$menus = get_terms('nav_menu');
	$menus_list = [];
	foreach($menus as $menu){
		$menus_list[] = [
			$menu->value => $menu->slug,
			$menu->name => $menu->name,
		];
	}

	return $menus_list;
}

function thegem_add_menu_item_split_logo($items) {
	$items .= '<li class="menu-item menu-item-type-split-logo">';
	$items .= '<div class="logo-fullwidth-block" style="display: flex;justify-content: center;z-index:0;">';
	$items .= '</div>';
	$items .= '</li>';

	return $items;
}

function thegem_templates_menu_search_widget($items){
	$items .= '<li class="menu-item menu-item-widget menu-item-type-search-widget" style="display: none">';
	$items .= '<a href="#"></a>';
	$items .= '<div class="minisearch">';
	$items .= '<form role="search" class="sf" action="'.esc_url( home_url( '/' ) ).'" method="GET">';
	$items .= '<input class="sf-input" type="text" placeholder="'.esc_html__('Search...', 'thegem').'" name="s">';
	$items .= '<span class="sf-submit-icon"></span>';
	$items .= '<input class="sf-submit" type="submit" value="">';
	$items .= '</form>';
	$items .= '</div>';
	$items .= '</li>';

	return $items;
}

function thegem_templates_menu_socials_widget($items, $args){
	ob_start();
	thegem_print_socials('rounded');
	$socials = ob_get_clean();

	$items .= '<li class="menu-item menu-item-widget menu-item-type-socials-widget" style="display: none">';
	$items .= '<div class="menu-item-socials socials-colored">'.$socials.'</div>';
	$items .= '</li>';

	return $items;
}

function thegem_templates_menu_mobile_socials_widget($items, $args){
	ob_start();
	thegem_print_socials();
	$socials = ob_get_clean();

	$items .= '<li class="menu-item menu-item-widget menu-item-type-socials-widget" style="display: none">';
	$items .= '<div class="menu-item-socials">'.$socials.'</div>';
	$items .= '</li>';

	return $items;
}
add_filter('wp_nav_menu_items', 'thegem_mobile_menu_item_widget', 100, 2);

function thegem_templates_menu_insert_template($items, $args){
	$template = $GLOBALS['thegem_menu_template'];
	$items .= '<li class="menu-item menu-item-type-template" style="display: none">';
	if(!empty($GLOBALS['thegem_menu_template_container'])) {
		$items .= '<div class="container">';
	}
	$items .= do_shortcode('[gem_template id="'.intval($template).'"]');
	if(!empty($GLOBALS['thegem_menu_template_container'])) {
		$items .= '</div>';
		unset($GLOBALS['thegem_menu_template_container']);
	}
	$items .= '</li>';
	unset($GLOBALS['thegem_menu_template']);

	return $items;
}

function thegem_templates_extra_options_extract() {

	return array(
		'element_id' => '',
		'element_class' => '',
		'element_link' => '',
	);
}

function thegem_templates_design_options_extract($param = 'default') {
 
	switch ($param) {
		case 'single-product':
			$custom_gap = '';
			break;
		default:
			$custom_gap = '5';
	}
	
	return array(
		'desktop_disable' => '',
		'tablet_disable' => '',
		'mobile_disable' => '',
		'desktop_absolute' => '',
		'tablet_absolute' => '',
		'mobile_absolute' => '',
		'desktop_order' => '',
		'tablet_order' => '',
		'mobile_order' => '',
		'desktop_horizontal' => '',
		'tablet_horizontal' => '',
		'mobile_horizontal' => '',
		'desktop_vertical' => '',
		'tablet_vertical' => '',
		'mobile_vertical' => '',
		'desktop_padding_top' => '',
		'desktop_padding_bottom' => '',
		'desktop_padding_left' => $custom_gap,
		'desktop_padding_right' => $custom_gap,
		'tablet_padding_top' => '',
		'tablet_padding_bottom' => '',
		'tablet_padding_left' => $custom_gap,
		'tablet_padding_right' => $custom_gap,
		'mobile_padding_top' => '',
		'mobile_padding_bottom' => '',
		'mobile_padding_left' => $custom_gap,
		'mobile_padding_right' => $custom_gap,
		'desktop_margin_top' => '',
		'desktop_margin_bottom' => '',
		'desktop_margin_left' => '',
		'desktop_margin_right' => '',
		'tablet_margin_top' => '',
		'tablet_margin_bottom' => '',
		'tablet_margin_left' => '',
		'tablet_margin_right' => '',
		'mobile_margin_top' => '',
		'mobile_margin_bottom' => '',
		'mobile_margin_left' => '',
		'mobile_margin_right' => '',
	);
}

function thegem_templates_design_options_output($ext) {

	return array(
		'desktop_disable' => isset($ext['desktop_disable']) ? $ext['desktop_disable'] : '',
		'tablet_disable' => isset($ext['tablet_disable']) ? $ext['tablet_disable'] : '',
		'mobile_disable' => isset($ext['mobile_disable']) ? $ext['mobile_disable'] : '',
		'desktop_absolute' => isset($ext['desktop_absolute']) ? $ext['desktop_absolute'] : '',
		'tablet_absolute' => isset($ext['tablet_absolute']) ? $ext['tablet_absolute'] : '',
		'mobile_absolute' => isset($ext['mobile_absolute']) ? $ext['mobile_absolute'] : '',
		'desktop_order' => isset($ext['desktop_order']) ? $ext['desktop_order'] : '',
		'tablet_order' => isset($ext['tablet_order']) ? $ext['tablet_order'] : '',
		'mobile_order' => isset($ext['mobile_order']) ? $ext['mobile_order'] : '',
		'desktop_horizontal' => isset($ext['desktop_horizontal']) ? $ext['desktop_horizontal'] : '',
		'tablet_horizontal' => isset($ext['tablet_horizontal']) ? $ext['tablet_horizontal'] : '',
		'mobile_horizontal' => isset($ext['mobile_horizontal']) ? $ext['mobile_horizontal'] : '',
		'desktop_vertical' => isset($ext['desktop_vertical']) ? $ext['desktop_vertical'] : '',
		'tablet_vertical' => isset($ext['tablet_vertical']) ? $ext['tablet_vertical'] : '',
		'mobile_vertical' => isset($ext['mobile_vertical']) ? $ext['mobile_vertical'] : '',
		'desktop_padding_top' => isset($ext['desktop_padding_top']) ? $ext['desktop_padding_top'] : '',
		'desktop_padding_bottom' => isset($ext['desktop_padding_bottom']) ? $ext['desktop_padding_bottom'] : '',
		'desktop_padding_left' => isset($ext['desktop_padding_left']) ? $ext['desktop_padding_left'] : '',
		'desktop_padding_right' => isset($ext['desktop_padding_right']) ? $ext['desktop_padding_right'] : '',
		'tablet_padding_top' => isset($ext['tablet_padding_top']) ? $ext['tablet_padding_top'] : '',
		'tablet_padding_bottom' => isset($ext['tablet_padding_bottom']) ? $ext['tablet_padding_bottom'] : '',
		'tablet_padding_left' => isset($ext['tablet_padding_left']) ? $ext['tablet_padding_left'] : '',
		'tablet_padding_right' => isset($ext['tablet_padding_right']) ? $ext['tablet_padding_right'] : '',
		'mobile_padding_top' => isset($ext['mobile_padding_top']) ? $ext['mobile_padding_top'] : '',
		'mobile_padding_bottom' => isset($ext['mobile_padding_bottom']) ? $ext['mobile_padding_bottom'] : '',
		'mobile_padding_left' => isset($ext['mobile_padding_left']) ? $ext['mobile_padding_left'] : '',
		'mobile_padding_right' => isset($ext['mobile_padding_right']) ? $ext['mobile_padding_right'] : '',
		'desktop_margin_top' => isset($ext['desktop_margin_top']) ? $ext['desktop_margin_top'] : '',
		'desktop_margin_bottom' => isset($ext['desktop_margin_bottom']) ? $ext['desktop_margin_bottom'] : '',
		'desktop_margin_left' => isset($ext['desktop_margin_left']) ? $ext['desktop_margin_left'] : '',
		'desktop_margin_right' => isset($ext['desktop_margin_right']) ? $ext['desktop_margin_right'] : '',
		'tablet_margin_top' => isset($ext['tablet_margin_top']) ? $ext['tablet_margin_top'] : '',
		'tablet_margin_bottom' => isset($ext['tablet_margin_bottom']) ? $ext['tablet_margin_bottom'] : '',
		'tablet_margin_left' => isset($ext['tablet_margin_left']) ? $ext['tablet_margin_left'] : '',
		'tablet_margin_right' => isset($ext['tablet_margin_right']) ? $ext['tablet_margin_right'] : '',
		'mobile_margin_top' => isset($ext['mobile_margin_top']) ? $ext['mobile_margin_top'] : '',
		'mobile_margin_bottom' => isset($ext['mobile_margin_bottom']) ? $ext['mobile_margin_bottom'] : '',
		'mobile_margin_left' => isset($ext['mobile_margin_left']) ? $ext['mobile_margin_left'] : '',
		'mobile_margin_right' => isset($ext['mobile_margin_right']) ? $ext['mobile_margin_right'] : '',
	);
}

function thegem_templates_responsive_options_extract() {
	
	return array(
		'element_hide_desktop' => '0',
		'element_hide_tablet' => '0',
		'element_hide_mobile' => '0',
	);
}

function thegem_templates_responsive_options_output($params) {
	if (!empty($params)) {
		$styles = [
			!empty($params['element_hide_desktop']) ? 'vc-element-hide--desktop' : null,
			!empty($params['element_hide_tablet']) ? 'vc-element-hide--tablet' : null,
			!empty($params['element_hide_mobile']) ? 'vc-element-hide--mobile' : null,
		];
		return implode(' ', $styles);
	}
	
	return false;
}

function thegem_te_delay_class() {
	if(function_exists('thegem_is_wp_rocket_delay_js_active') && thegem_is_wp_rocket_delay_js_active()) {
		return ' detect-delay-click';
	}
	if(function_exists('thegem_delay_js_active') && thegem_delay_js_active()) {
		return ' detect-delay-click';
	}
	return '';
}

function thegem_templates_get_wc_attributes() {
	global $wpdb;
	$data = array(array('value' => '0', 'label' => __('Please select', 'thegem'), 'disabled' => true));

	if (class_exists('WooCommerce')) {
		$table_name = $wpdb->prefix . "woocommerce_attribute_taxonomies";
		$query_results = $wpdb->get_results("SELECT * FROM $table_name order by attribute_label");
		foreach($query_results as $query_result) {
			$data[] = array('value' => $query_result->attribute_name, 'label' => $query_result->attribute_label);
		}
	}

	return $data;
}

function thegem_templates_product_tabs_callback($params) {
	global $product, $post;

	$tabs = array();
	$description_tab_callback = '';

	if($params['description_tab_source'] == 'page_builder') {
		$vc_show_content = false;
		if(thegem_is_plugin_active('js_composer/js_composer.php')) {
			global $vc_manager;
			if($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode()== 'page_editable') {
				$vc_show_content = true;
			}
		}
		if(get_the_content() || $vc_show_content) {
			$description_tab_callback = 'thegem_woocommerce_single_product_page_content';
		}
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
	$bpid = get_post_meta(get_the_ID(), 'thegem_single_product_id', true);
	if(!empty($bpid) && get_post_type($bpid) === 'product') {
		$pid = $bpid;
	}
	if(!function_exists('wc_get_product')) return false;
	global $product, $post;
	if(thegem_get_template_type(get_the_ID()) === 'single-product' || get_post_meta(get_the_ID(), 'thegem_is_single_product', true)) {
		$product_id = 0;
		$args = array(
			'posts_per_page' => '1',
			'post_type' => 'product',
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
	$post = get_post($product->get_id());
	setup_postdata($post);
	return $product;
}

function thegem_templates_close_post($name = '', $settings = '', $html = '') {
	$output = $html;
	wp_reset_postdata();
	if(thegem_get_template_type(get_the_ID()) === 'single-post' && empty($html)) {
		$class = str_replace('_', '-', $name);
		$title = $settings['name'];
		$output = '<div class="'.esc_attr($class).' template-post-empty-output default-background">'.$title.'</div>';
	}
	return $output;
}

function thegem_templates_close_product($name = '', $settings = '', $html = '') {
	$output = $html;
	wp_reset_postdata();
	if(thegem_get_template_type(get_the_ID()) === 'single-product' && empty($html)) {
		$class = str_replace('_', '-', $name);
		$title = $settings['name'];
		$output = '<div class="'.esc_attr($class).' template-product-empty-output default-background">'.$title.'</div>';
	}
	return $output;
}

function thegem_templates_close_product_archive($name = '', $settings = '', $html = '') {
	$output = $html;
	wp_reset_postdata();
	if(thegem_get_template_type(get_the_ID()) === 'product-archive' && empty($html)) {
		$class = str_replace('_', '-', $name);
		$title = $settings['name'];
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

function thegem_templates_close_blog_archive($name = '', $settings = '', $html = '') {
	$output = $html;
	wp_reset_postdata();
	if(thegem_get_template_type(get_the_ID()) === 'blog-archive' && empty($html)) {
		$class = str_replace('_', '-', $name);
		$title = $settings['name'];
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

function thegem_templates_close_cart($name = '', $settings = '', $html = '') {
	$output = $html;
	wp_reset_postdata();
	if(thegem_get_template_type(get_the_ID()) === 'cart' && empty($html)) {
		$class = str_replace('_', '-', $name);
		$title = $settings['name'];
		$output = '<div class="'.esc_attr($class).' template-cart-empty-output default-background">'.$title.'</div>';
	}
	return $output;
}

function thegem_templates_close_checkout_thanks($name = '', $settings = '', $html = '') {
	$output = $html;
	wp_reset_postdata();
	if(thegem_get_template_type(get_the_ID()) === 'checkout-thanks' && empty($html)) {
		$class = str_replace('_', '-', $name);
		$title = $settings['name'];
		$output = '<div class="'.esc_attr($class).' template-checkout-thanks-empty-output default-background">'.$title.'</div>';
	}
	return $output;
}

function thegem_templates_close_single_post($name = '', $settings = '', $html = '') {
	$output = $html;
	wp_reset_postdata();
	if(thegem_get_template_type(get_the_ID()) === 'single-post' && empty($html)) {
		$class = str_replace('_', '-', $name);
		$title = $settings['name'];
		$output = '<div class="'.esc_attr($class).' template-post-empty-output default-background">'.$title.'</div>';
	}
	return $output;
}

function thegem_mega_menu_shortcodes_category($shortcodes) {
	if(thegem_is_template_post('megamenu')) {
		$shortcodes_list = array(
			'gem_heading',
			'gem_divider',
			'gem_custom_menu',
			'gem_image',
			'product_compact_grid',
			'gem_news',
			'gem_infotext',
			'gem_button',
		);
		foreach($shortcodes_list as $sc) {
			if(isset($shortcodes[$sc])) {
				$categories = $shortcodes[$sc]['category'];
				$categories_array = array();
				if(is_array($categories)) {
					$categories_array = $categories;
				} else {
					$categories_array[] = $categories;
				}
				$categories_array[] = __('Mega Menu Builder', 'thegem');
				$shortcodes[$sc]['category'] = $categories_array;
			}
		}
	}
	return $shortcodes;
}
add_filter('thegem_shortcodes_array', 'thegem_mega_menu_shortcodes_category', 20);

function thegem_blog_archive_shortcodes_category($shortcodes) {
	if(thegem_is_template_post('blog-archive')) {
		$shortcodes_list = array(
			'gem_news',
			'gem_news_grid',
			'gem_featured_posts_slider',
		);
		foreach($shortcodes_list as $sc) {
			if(isset($shortcodes[$sc])) {
				$categories = $shortcodes[$sc]['category'];
				$categories_array = array();
				if(is_array($categories)) {
					$categories_array = $categories;
				} else {
					$categories_array[] = $categories;
				}
				$categories_array[] = __('Archive Blog Builder', 'thegem');
				$shortcodes[$sc]['category'] = $categories_array;
			}
		}
	}
	return $shortcodes;
}
add_filter('thegem_shortcodes_array', 'thegem_blog_archive_shortcodes_category', 20);

function thegem_te_product_text_styled($params) {
	if (!empty($params)) {
		$styles = [
			$params['text_style'],
			$params['text_font_weight'],
			$params['text_font_style'],
			$params['text_transform'],
		];
		return implode(' ', $styles);
	}
	
	return false;
}

require_once(plugin_dir_path( __FILE__ ) . 'class-element.php');


function thegem_temlates_button_shortcode($shortcodes) {
	if(thegem_is_template_post('checkout') || thegem_is_template_post('cart')) {
		$interactions = thegem_get_interactions_options();
		$responsive = thegem_set_elements_responsive_options();
		$shortcode_category = [__('TheGem', 'thegem')];
		if(thegem_is_template_post('checkout')) {
			$shortcode_category[] = __('Checkout Builder', 'thegem');
			$button_link_type_param = array(
				'type' => 'dropdown',
				'heading' => __('Link Type', 'thegem'),
				'param_name' => 'link_type',
				'value' => array(__('Place Order', 'thegem') => 'place_order', __('Cart Page', 'thegem') => 'cart_page', __('Custom URL', 'thegem') => 'custom_url'),
			);
		} else {
			$shortcode_category[] = __('Cart Builder', 'thegem');
			$button_link_type_param = array(
				'type' => 'dropdown',
				'heading' => __('Link Type', 'thegem'),
				'param_name' => 'link_type',
				'value' => array(__('Checkout Page', 'thegem') => 'checkout_page', __('Custom URL', 'thegem') => 'custom_url'),
			);
		}
		$button_link_param = array(
			'type' => 'vc_link',
			'heading' => __( 'URL (Link)', 'thegem' ),
			'param_name' => 'link',
			'description' => __( 'Add link to button.', 'thegem' ),
			'dependency' => array(
				'element' => 'link_type',
				'value' => array('custom_url')
			),
		);
		$shortcodes['gem_button'] = array(
			'name' => __('Button', 'thegem'),
			'base' => 'gem_button',
			'icon' => 'thegem-icon-wpb-ui-button',
			'category' => $shortcode_category,
			'description' => __('Styled button element', 'thegem'),
			'params' => array_merge(array(
				array(
					'type' => 'textfield',
					'heading' => __('Button Text', 'thegem'),
					'param_name' => 'text',
					'std' => 'Button'
				),
				$button_link_type_param,
				$button_link_param,
				array(
					'type' => 'dropdown',
					'heading' => __('Position', 'thegem'),
					'param_name' => 'position',
					'value' => array(__('Inline', 'thegem') => 'inline', __('Left', 'thegem') => 'left', __('Right', 'thegem') => 'right', __('Center', 'thegem') => 'center', __('Fullwidth', 'thegem') => 'fullwidth')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Button Style Preset', 'thegem'),
					'param_name' => 'style',
					'value' => array(__('Flat', 'thegem') => 'flat', __('Outline', 'thegem') => 'outline')
				),
				array(
					'type' => 'textfield',
					'heading' => __('Border radius', 'thegem'),
					'param_name' => 'corner',
					'value' => 3,
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'thegem_delimeter_heading',
					'heading' => __('Button Size', 'thegem'),
					'param_name' => 'size_head',
					'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Size on Desktop', 'thegem'),
					'param_name' => 'size',
					'value' => array(__('Tiny', 'thegem') => 'tiny', __('Small', 'thegem') => 'small', __('Medium', 'thegem') => 'medium', __('Large', 'thegem') => 'large', __('Giant', 'thegem') => 'giant'),
					'std' => 'small',
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Size on Tablet', 'thegem'),
					'param_name' => 'size_tablet',
					'value' => array(__('Default', 'thegem') => '', __('Tiny', 'thegem') => 'tiny', __('Small', 'thegem') => 'small', __('Medium', 'thegem') => 'medium', __('Large', 'thegem') => 'large', __('Giant', 'thegem') => 'giant'),
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Size on Mobile', 'thegem'),
					'param_name' => 'size_mobile',
					'value' => array(__('Default', 'thegem') => '', __('Tiny', 'thegem') => 'tiny', __('Small', 'thegem') => 'small', __('Medium', 'thegem') => 'medium', __('Large', 'thegem') => 'large', __('Giant', 'thegem') => 'giant'),
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'thegem_delimeter_heading',
					'heading' => __('Button Text', 'thegem'),
					'param_name' => 'text_head',
					'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Text weight', 'thegem'),
					'param_name' => 'text_weight',
					'value' => array(__('Normal', 'thegem') => 'normal', __('Thin', 'thegem') => 'thin'),
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Letter Spacing', 'thegem'),
					'param_name' => 'letter_spacing',
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Text Transform', 'thegem'),
					'param_name' => 'text_transform',
					'value' => array(
						__('Default', 'thegem') => '',
						__('Capitalize', 'thegem') => 'capitalize',
						__('Lowercase', 'thegem') => 'lowercase',
						__('Uppercase', 'thegem') => 'uppercase',
						__('None', 'thegem') => 'none',
					),
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'thegem_delimeter_heading_two_level',
					'heading' => __('Text Size', 'thegem'),
					'param_name' => 'text_size_head',
					'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Size on Desktop (px)', 'thegem'),
					'param_name' => 'text_size',
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Size on Tablet (px)', 'thegem'),
					'param_name' => 'text_size_tablet',
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Size on Mobile (px) ', 'thegem'),
					'param_name' => 'text_size_mobile',
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('No uppercase', 'thegem'),
					'param_name' => 'no_uppercase',
					'value' => array(__('Yes', 'thegem') => '1')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Border width', 'thegem'),
					'param_name' => 'border',
					'value' => array(1, 2, 3, 4, 5, 6),
					'std' => 2,
					'dependency' => array(
						'element' => 'style',
						'value' => array('outline')
					),
				),
				array(
					'type' => 'thegem_delimeter_heading',
					'heading' => __('Button Colors', 'thegem'),
					'param_name' => 'colors_head',
					'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Text color', 'thegem'),
					'param_name' => 'text_color',
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Background color', 'thegem'),
					'param_name' => 'background_color',
					'dependency' => array(
						'element' => 'style',
						'value' => array('flat')
					),
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Border color', 'thegem'),
					'param_name' => 'border_color',
					'dependency' => array(
						'element' => 'style',
						'value' => array('outline')
					),
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'thegem_delimeter_heading_two_level',
					'heading' => __('Hover Colors', 'thegem'),
					'param_name' => 'hover_colors_head',
					'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Text color', 'thegem'),
					'param_name' => 'hover_text_color',
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Background color', 'thegem'),
					'param_name' => 'hover_background_color',
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Border color', 'thegem'),
					'param_name' => 'hover_border_color',
					'dependency' => array(
						'element' => 'style',
						'value' => array('outline')
					),
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'thegem_delimeter_heading_two_level',
					'heading' => __('Gradient Colors', 'thegem'),
					'param_name' => 'gradient_colors_head',
					'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Use Gradient Backgound Colors', 'thegem'),
					'param_name' => 'gradient_backgound',
					'value' => array(__('Yes', 'thegem') => '1'),
					'group' => __('Style', 'thegem'),
				),

				array(
					'type' => 'colorpicker',
					'heading' => __('Background From', 'thegem'),
					"edit_field_class" => "vc_col-sm-5 vc_column",
					'param_name' => 'gradient_backgound_from',
					'dependency' => array(
						'element' => 'gradient_backgound',
						'value' => array('1')
					),
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Background To', 'thegem'),
					"edit_field_class" => "vc_col-sm-5 vc_column",
					'param_name' => 'gradient_backgound_to',
					'dependency' => array(
						'element' => 'gradient_backgound',
						'value' => array('1')
					),
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Hover Background From', 'thegem'),
					"edit_field_class" => "vc_col-sm-5 vc_column",
					'param_name' => 'gradient_backgound_hover_from',
					'dependency' => array(
						'element' => 'gradient_backgound',
						'value' => array('1')
					),
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Hover Background To', 'thegem'),
					"edit_field_class" => "vc_col-sm-5 vc_column",
					'param_name' => 'gradient_backgound_hover_to',
					'dependency' => array(
						'element' => 'gradient_backgound',
						'value' => array('1')
					),
					'group' => __('Style', 'thegem'),
				),
				array(
					"type" => "dropdown",
					'heading' => __('Style', 'thegem'),
					'param_name' => 'gradient_backgound_style',
					"edit_field_class" => "vc_col-sm-4 vc_column",
					"value" => array(
						__('Linear', "thegem") => "linear",
						__('Radial', "thegem") => "radial",
					) ,
					"std" => 'linear',
					'dependency' => array(
						'element' => 'gradient_backgound',
						'value' => array('1')
					),
					'group' => __('Style', 'thegem'),
				),
				array(
					"type" => "dropdown",
					'heading' => __('Gradient Position', 'thegem'),
					'param_name' => 'gradient_radial_backgound_position',
					"edit_field_class" => "vc_col-sm-4 vc_column",
					"value" => array(
						__('Top', "thegem") => "at top",
						__('Bottom', "thegem") => "at bottom",
						__('Right', "thegem") => "at right",
						__('Left', "thegem") => "at left",
						__('Center', "thegem") => "at center",
					) ,
					'dependency' => array(
						'element' => 'gradient_backgound_style',
						'value' => array(
							'radial',
						)
					),
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Swap Colors', 'thegem'),
					'param_name' => 'gradient_radial_swap_colors',
					"edit_field_class" => "vc_col-sm-4 vc_column",
					'value' => array(__('Yes', 'thegem') => '1'),
					'dependency' => array(
						'element' => 'gradient_backgound_style',
						'value' => array(
							'radial',
						)
					),
					'group' => __('Style', 'thegem'),
				),
				array(
					"type" => "dropdown",
					'heading' => __('Custom Angle', 'thegem'),
					'param_name' => 'gradient_backgound_angle',
					"edit_field_class" => "vc_col-sm-4 vc_column",
					"value" => array(
						__('Vertical to bottom ↓', "thegem") => "to bottom",
						__('Vertical to top ↑', "thegem") => "to top",
						__('Horizontal to left  →', "thegem") => "to right",
						__('Horizontal to right ←', "thegem") => "to left",
						__('Diagonal from left to bottom ↘', "thegem") => "to bottom right",
						__('Diagonal from left to top ↗', "thegem") => "to top right",
						__('Diagonal from right to bottom ↙', "thegem") => "to bottom left",
						__('Diagonal from right to top ↖', "thegem") => "to top left",
						__('Custom', "thegem") => "cusotom_deg",
					),
					'dependency' => array(
						'element' => 'gradient_backgound_style',
						'value' => array(
							'linear',
						)
					),
					'group' => __('Style', 'thegem'),
				),
				array(
					"type" => "textfield",
					'heading' => __('Angle', 'thegem'),
					'param_name' => 'gradient_backgound_cusotom_deg',
					"edit_field_class" => "vc_col-sm-4 vc_column",
					'description' => __('Set value in DG 0-360', 'thegem'),
					'dependency' => array(
						'element' => 'gradient_backgound_angle',
						'value' => array(
							'cusotom_deg',
						)
					),
					'group' => __('Style', 'thegem'),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Separator Style', 'thegem'),
					'param_name' => 'separator',
					'value' => array(
						__('None', 'thegem') => '',
						__('Single', 'thegem') => 'single',
						__('Square', 'thegem') => 'square',
						__('Soft Double', 'thegem') => 'soft-double',
						__('Strong Double', 'thegem') => 'strong-double'
					),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Separator Weight', 'thegem'),
					'param_name' => 'separator_weight',
					'dependency' => array(
						'element' => 'separator',
						'value' => array( 'single', 'soft-double', 'strong-double' )
					)
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'thegem' ),
					'param_name' => 'extra_class',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'ID name', 'thegem' ),
					'param_name' => 'id',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Icon pack', 'thegem'),
					'param_name' => 'icon_pack',
					'value' => array_merge(array(__('Elegant', 'thegem') => 'elegant', __('Material Design', 'thegem') => 'material', __('FontAwesome', 'thegem') => 'fontawesome', __('Header Icons', 'thegem') => 'thegem-header'), thegem_userpack_to_dropdown()),
					'std' => 2,
				),
				array(
					'type' => 'thegem_icon',
					'heading' => __('Icon', 'thegem'),
					'param_name' => 'icon_elegant',
					'icon_pack' => 'elegant',
					'dependency' => array(
						'element' => 'icon_pack',
						'value' => array('elegant')
					),
				),
				array(
					'type' => 'thegem_icon',
					'heading' => __('Icon', 'thegem'),
					'param_name' => 'icon_material',
					'icon_pack' => 'material',
					'dependency' => array(
						'element' => 'icon_pack',
						'value' => array('material')
					),
				),
				array(
					'type' => 'thegem_icon',
					'heading' => __('Icon', 'thegem'),
					'param_name' => 'icon_fontawesome',
					'icon_pack' => 'fontawesome',
					'dependency' => array(
						'element' => 'icon_pack',
						'value' => array('fontawesome')
					),
				),
				array(
					'type' => 'thegem_icon',
					'heading' => __('Icon', 'thegem'),
					'param_name' => 'icon_thegem_header',
					'icon_pack' => 'thegem-header',
					'dependency' => array(
						'element' => 'icon_pack',
						'value' => array('thegem-header')
					),
				),
			),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'icon_pack',
							'value' => array('userpack')
						),
					),
				)),
				array(
					array(
						'type' => 'dropdown',
						'heading' => __( 'Icon position', 'thegem' ),
						'param_name' => 'icon_position',
						'value' => array(__( 'Left', 'thegem' ) => 'left', __( 'Right', 'thegem' ) => 'right'),
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Padding', 'thegem'),
						'param_name' => 'padding_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
						'group' => __('Style', 'thegem'),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'padding_left_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Desktop', 'thegem' ),
						'param_name' => 'padding_left',
						"edit_field_class" => "vc_col-sm-4 vc_column",
						'group' => __('Style', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Tablet', 'thegem' ),
						'param_name' => 'padding_left_tablet',
						"edit_field_class" => "vc_col-sm-4 vc_column",
						'group' => __('Style', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Mobile', 'thegem' ),
						'param_name' => 'padding_left_mobile',
						"edit_field_class" => "vc_col-sm-4 vc_column",
						'group' => __('Style', 'thegem'),
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'padding_right_head',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Style', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Desktop', 'thegem' ),
						'param_name' => 'padding_right',
						"edit_field_class" => "vc_col-sm-4 vc_column",
						'group' => __('Style', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Tablet', 'thegem' ),
						'param_name' => 'padding_right_tablet',
						"edit_field_class" => "vc_col-sm-4 vc_column",
						'group' => __('Style', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Mobile', 'thegem' ),
						'param_name' => 'padding_right_mobile',
						"edit_field_class" => "vc_col-sm-4 vc_column",
						'group' => __('Style', 'thegem'),
					),
				),

				array(
					array(
						'type' => 'checkbox',
						'heading' => __('Animation enabled', 'thegem'),
						'param_name' => 'effects_enabled',
						'value' => array(__('Yes', 'thegem') => '1'),
						'group' => __('Animations', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Animation Type', 'thegem'),
						'param_name' => 'effects_enabled_name',
						'value' =>
							array_merge(array(__('Default', 'thegem') => 'default'), array_flip(TheGemButtonAnimation::getAnimationList())
						),
						'dependency' => array('element' => 'effects_enabled', 'not_empty' => true),
						'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'thegem' ),
						'group' => __('Animations', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Animation Speed (ms)', 'thegem'),
						'param_name' => 'effects_enabled_duration',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'effects_enabled_name',
							'value' => array('slide-up', 'slide-down', 'slide-left', 'slide-right', 'fade-down', 'fade-up', 'fade-left', 'fade-right', 'fade')
						),
						'group' => __('Animations', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Animation delay (ms)', 'thegem'),
						'param_name' => 'effects_enabled_delay',
						'dependency' => array('element' => 'effects_enabled', 'not_empty' => true),
						'group' => __('Animations', 'thegem'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Timing Function', 'thegem'),
						'param_name' => 'effects_enabled_timing_function',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'dependency' => array(
							'element' => 'effects_enabled_name',
							'value' => array('slide-up', 'slide-down', 'slide-left', 'slide-right', 'fade-down', 'fade-up', 'fade-left', 'fade-right', 'fade')
						),
						'group' => __('Animations', 'thegem'),
						'description' => sprintf( __( '(Please refer to this %s)', 'thegem' ), '<a href="https://www.w3schools.com/cssref/css3_pr_animation-timing-function.asp" target="_blank">article</a>' ),
					)
				),
				
				// Init interactions controls
				$interactions,
				
				// Init responsive controls
				$responsive
			),
		);
	}
	return $shortcodes;
}
add_filter('thegem_shortcodes_array', 'thegem_temlates_button_shortcode', 20);

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

function thegem_single_post_page_content() {
	$vc_show_content = false;
	if (defined('WPB_VC_VERSION')) {
		global $vc_manager;
		if ($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode()== 'page_editable') {
			$vc_show_content = true;
		}
	}

	if (get_the_content() || $vc_show_content) {
		the_content();
	}
}

function thegem_save_template_data_to_translation($post_id, $data, $job) {
	$origin_post = get_post($job->original_doc_id);
	if($job->original_doc_id && $origin_post && get_post_type($origin_post) == 'thegem_templates') {
		$template_type = get_post_meta( $job->original_doc_id, 'thegem_template_type', true );
		update_post_meta($post_id, 'thegem_template_type', $template_type);
	}
}
add_action( 'wpml_translation_job_saved', 'thegem_save_template_data_to_translation', 10, 3 );