<?php

class TheGem_Template_Element_Search extends TheGem_Template_Element {

	public function __construct() {

		if (!defined('THEGEM_TE_SEARCH_DIR')) {
			define('THEGEM_TE_SEARCH_DIR', rtrim(__DIR__, ' /\\'));
		}

		if (!defined('THEGEM_TE_SEARCH_URL')) {
			define('THEGEM_TE_SEARCH_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}

		wp_register_style('thegem-te-search', THEGEM_TE_SEARCH_URL . '/css/search.css');
		wp_register_style('thegem-te-search-fullscreen', THEGEM_TE_SEARCH_URL . '/css/thegem-fullscreen-search.css');
		wp_register_script('thegem-te-search', THEGEM_TE_SEARCH_URL . '/js/search.js');
	}

	public function get_name() {
		return 'thegem_te_search';
	}

	public function head_scripts($attr) {
		$attr = shortcode_atts(
			array(
				'layout' => 'fullscreen',
				'search_icon_pack' => 'thegem-header',
				'close_icon_pack' => 'thegem-header',
			),
			$attr,
			'thegem_te_search'
		);

		wp_enqueue_style('thegem-te-search');
		wp_enqueue_script('thegem-te-search');

		if (isset($attr['layout']) && $attr['layout'] == 'fullscreen') {
			wp_enqueue_style('thegem-te-search-fullscreen');
		}

		wp_enqueue_style('icons-' . $attr['search_icon_pack']);
		wp_enqueue_style('icons-' . $attr['close_icon_pack']);
	}

	public function front_editor_scripts($attr) {
		$attr = shortcode_atts(
			array(
				'layout' => 'fullscreen',
				'search_icon_pack' => 'thegem-header',
				'close_icon_pack' => 'thegem-header',
			),
			$attr,
			'thegem_te_search'
		);
		wp_enqueue_style('thegem-te-search');
		wp_enqueue_script('thegem-te-search');
		wp_enqueue_style('thegem-te-search-fullscreen');

		wp_enqueue_style('icons-' . $attr['search_icon_pack']);
		wp_enqueue_style('icons-' . $attr['close_icon_pack']);
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		extract($extract = shortcode_atts(array_merge(array(
			'layout' => 'fullscreen',
			'placeholder_text' => __('Search...', 'thegem'),
			'layout_fullscreen_placeholder_text' => __('Start typing to search...', 'thegem'),
			'post_type_products' => '1',
			'post_type_posts' => '1',
			'post_type_pages' => '1',
			'post_type_portfolio' => '1',
			'search_ajax' => '1',
			'products_auto_suggestions' => '16',
			'posts_auto_suggestions' => '8',
			'pages_auto_suggestions' => '8',
			'portfolio_auto_suggestions' => '8',
			'posts_result_title' => __('Results from Blog', 'thegem'),
			'pages_result_title' => __('Results from Pages', 'thegem'),
			'portfolio_result_title' => __('Results from Portfolio', 'thegem'),
			'view_results_button_text' => __('View all search results', 'thegem'),
			'popular' => '',
			'popular_title' => __('Top Searches:', 'thegem'),
			'select_terms_data' => '',
			'search_icon_pack' => 'thegem-header',
			'search_icon_elegant' => '',
			'search_icon_material' => '',
			'search_icon_fontawesome' => '',
			'search_icon_thegemdemo' => '',
			'search_icon_thegemheader' => '',
			'search_icon_userpack' => '',
			'close_icon_pack' => 'thegem-header',
			'close_icon_elegant' => '',
			'close_icon_material' => '',
			'close_icon_fontawesome' => '',
			'close_icon_thegemdemo' => '',
			'close_icon_thegemheader' => '',
			'close_icon_userpack' => '',
			'icon_size' => '',
			'icon_color_normal' => '',
			'icon_color_hover' => '',
		), thegem_templates_design_options_extract()), $atts, 'thegem_te_search'));

		$params = array_merge(array(
			'layout' => $layout,
			'placeholder_text' => $placeholder_text,
			'layout_fullscreen_placeholder_text' => $layout_fullscreen_placeholder_text,
			'post_type_products' => $post_type_products,
			'post_type_posts' => $post_type_posts,
			'post_type_pages' => $post_type_pages,
			'post_type_portfolio' => $post_type_portfolio,
			'search_ajax' => $search_ajax,
			'products_auto_suggestions' => $products_auto_suggestions,
			'posts_auto_suggestions' => $posts_auto_suggestions,
			'pages_auto_suggestions' => $pages_auto_suggestions,
			'portfolio_auto_suggestions' => $portfolio_auto_suggestions,
			'posts_result_title' => $posts_result_title,
			'pages_result_title' => $pages_result_title,
			'portfolio_result_title' => $portfolio_result_title,
			'view_results_button_text' => $view_results_button_text,
			'popular' => $popular,
			'popular_title' => $popular_title,
			'select_terms_data' => $select_terms_data,
			'search_icon_pack' => $search_icon_pack,
			'search_icon_elegant' => $search_icon_elegant,
			'search_icon_material' => $search_icon_material,
			'search_icon_fontawesome' => $search_icon_fontawesome,
			'search_icon_thegemdemo' => $search_icon_thegemdemo,
			'search_icon_thegemheader' => $search_icon_thegemheader,
			'search_icon_userpack' => $search_icon_userpack,
			'close_icon_pack' => $close_icon_pack,
			'close_icon_elegant' => $close_icon_elegant,
			'close_icon_material' => $close_icon_material,
			'close_icon_fontawesome' => $close_icon_fontawesome,
			'close_icon_thegemdemo' => $close_icon_thegemdemo,
			'close_icon_thegemheader' => $close_icon_thegemheader,
			'close_icon_userpack' => $close_icon_userpack,
			'icon_size' => $icon_size,
			'icon_color_normal' => $icon_color_normal,
			'icon_color_hover' => $icon_color_hover,
		), thegem_templates_design_options_output($extract));

		if (!defined('WC_PLUGIN_FILE')) {
			$params['post_type_products'] = '';
		}

		$uniqid = uniqid('thegem-custom-') . rand(1, 9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-search', $params);

		if ($params['icon_size'] !== '') {
			$custom_css .= '.thegem-te-search.' . esc_attr($uniqid) . ' a {font-size: ' . $params['icon_size'] . 'px; width: ' . $params['icon_size'] . 'px; height: ' . $params['icon_size'] . 'px;}';
		}
		if ($params['icon_color_normal'] !== '') {
			$custom_css .= '.thegem-te-search.' . esc_attr($uniqid) . ' a {color: ' . $params['icon_color_normal'] . ';}';
		}
		if ($params['icon_color_hover'] !== '') {
			$custom_css .= '.thegem-te-search.' . esc_attr($uniqid) . ' a:hover {color: ' . $params['icon_color_hover'] . ';}';
		}
		ob_start();

		$search_item_class = '';
		if ($params['layout'] == 'fullscreen') {
			$search_item_class = 'te-menu-item-fullscreen-search';
		} ?>

		<div class="thegem-te-search <?php echo esc_attr($uniqid); ?>" <?= thegem_data_editor_attribute($uniqid . '-editor') ?>>
			<div class="thegem-te-search__item <?php echo esc_html($search_item_class); ?>">
				<a href="#" class="<?= thegem_te_delay_class() ?>">
					<span class="open">
						<?php
						if (isset($params['search_icon_pack']) && $params['search_icon_' . str_replace("-", "", $params['search_icon_pack'])] != '') {
							echo thegem_build_icon($params['search_icon_pack'], $params['search_icon_' . str_replace("-", "", $params['search_icon_pack'])]);
						} else { ?>
							<i class="default"></i>
						<?php } ?>
					</span>
					<span class="close">
						<?php if (isset($params['close_icon_pack']) && $params['close_icon_' . str_replace("-", "", $params['close_icon_pack'])] != '') {
							echo thegem_build_icon($params['close_icon_pack'], $params['close_icon_' . str_replace("-", "", $params['close_icon_pack'])]);
						} else { ?>
							<i class="default"></i>
						<?php } ?>
					</span>
				</a>
				<div class="thegem-te-search-hide" style="display: none">
					<?php if ($params['layout'] == 'dropdown') { ?>
						<div class="minisearch">
							<form role="search" id="searchform" class="sf"
								  action="<?php echo esc_url(home_url('/')); ?>"
								  method="GET">
								<input id="searchform-input" class="sf-input" type="text"
									   placeholder="<?php echo esc_html($params['placeholder_text']); ?>" name="s">
								<?php if ($params['post_type_products'] == '1') { ?>
									<input type="hidden" name="post_type" value="product" />
								<?php } ?>
								<span class="sf-submit-icon"></span>
								<input id="searchform-submit" class="sf-submit" type="submit" value="s">
							</form>
						</div>
					<?php } else {
						$params['search_id'] = $uniqid;
						$params['select_terms_data'] = vc_param_group_parse_atts($params['select_terms_data']);

						thegem_fullscreen_search_layout($params);
					} ?>
				</div>

			</div>
		</div>

		<?php

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if (!empty($custom_css)) {
			$css_output = '<style>' . $custom_css . '</style>';
		}

		$return_html = $css_output . $return_html;
		return $return_html;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Search Icon', 'thegem'),
			'base' => 'thegem_te_search',
			'icon' => 'thegem-icon-wpb-ui-element-search',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('Search Icon (Header Builder)', 'thegem'),
			'params' => array_merge(
				array(
					array(
						'type' => 'dropdown',
						'heading' => __('Search Layout', 'thegem'),
						'param_name' => 'layout',
						'value' => array(
							__('Dropdown', 'thegem') => 'dropdown',
							__('Fullscreen Overlay', 'thegem') => 'fullscreen',
						),
						'std' => 'fullscreen',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Placeholder Text', 'thegem'),
						'param_name' => 'placeholder_text',
						'value' => __('Search...', 'thegem'),
						'dependency' => array(
							'element' => 'layout',
							'value' => array('dropdown')
						),
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Placeholder Text', 'thegem'),
						'param_name' => 'layout_fullscreen_placeholder_text',
						'value' => __('Start typing to search...', 'thegem'),
						'dependency' => array(
							'element' => 'layout',
							'value' => array('fullscreen')
						),
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Post Types', 'thegem'),
						'param_name' => 'post_types_header',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Products', 'thegem'),
						'param_name' => 'post_type_products',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Blog Posts', 'thegem'),
						'param_name' => 'post_type_posts',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Pages', 'thegem'),
						'param_name' => 'post_type_pages',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Portfolio', 'thegem'),
						'param_name' => 'post_type_portfolio',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Live Search', 'thegem'),
						'param_name' => 'results_header',
						'dependency' => array(
							'element' => 'layout',
							'value' => array('fullscreen')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('AJAX Live Search', 'thegem'),
						'param_name' => 'search_ajax',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'layout',
							'value' => array('fullscreen')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Products Auto-Suggestions', 'thegem'),
						'param_name' => 'products_auto_suggestions',
						'dependency' => array(
							'element' => 'layout',
							'value' => array('fullscreen')
						),
						'std' => '16',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Posts Auto-Suggestions', 'thegem'),
						'param_name' => 'posts_auto_suggestions',
						'dependency' => array(
							'element' => 'layout',
							'value' => array('fullscreen')
						),
						'std' => '8',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Pages Auto-Suggestions', 'thegem'),
						'param_name' => 'pages_auto_suggestions',
						'dependency' => array(
							'element' => 'layout',
							'value' => array('fullscreen')
						),
						'std' => '8',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Portfolio Auto-Suggestions', 'thegem'),
						'param_name' => 'portfolio_auto_suggestions',
						'dependency' => array(
							'element' => 'layout',
							'value' => array('fullscreen')
						),
						'std' => '8',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Posts Results Title', 'thegem'),
						'param_name' => 'posts_result_title',
						'value' => __('Results from Blog', 'thegem'),
						'dependency' => array(
							'element' => 'search_ajax',
							'not_empty' => true
						),
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Pages Results Title', 'thegem'),
						'param_name' => 'pages_result_title',
						'value' => __('Results from Pages', 'thegem'),
						'dependency' => array(
							'element' => 'search_ajax',
							'not_empty' => true
						),
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Portfolio Results Title', 'thegem'),
						'param_name' => 'portfolio_result_title',
						'value' => __('Results from Portfolio', 'thegem'),
						'dependency' => array(
							'element' => 'search_ajax',
							'not_empty' => true
						),
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('"View Results" Button Text', 'thegem'),
						'param_name' => 'view_results_button_text',
						'value' => __('View all search results', 'thegem'),
						'dependency' => array(
							'element' => 'search_ajax',
							'not_empty' => true
						),
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Popular Searches', 'thegem'),
						'param_name' => 'popular_header',
						'dependency' => array(
							'element' => 'layout',
							'value' => array('fullscreen')
						),
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Popular Searches', 'thegem'),
						'param_name' => 'popular',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'dependency' => array(
							'element' => 'layout',
							'value' => array('fullscreen')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Popular Searches Title', 'thegem'),
						'param_name' => 'popular_title',
						'value' => __('Top Searches:', 'thegem'),
						'dependency' => array(
							'element' => 'popular',
							'not_empty' => true
						),
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'param_group',
						'heading' => __('Select Search Terms', 'thegem'),
						'param_name' => 'select_terms_data',
						'params' => array_merge(array(
							array(
								'type' => 'textfield',
								'heading' => __('Title', 'thegem'),
								'param_name' => 'title',
								'value' => __('Title', 'thegem'),
								'edit_field_class' => 'vc_column vc_col-sm-4',
							),
						)),
						'dependency' => array(
							'element' => 'popular',
							'not_empty' => true
						),
						'group' => __('Layout', 'thegem')
					),
				),
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Search Icon', 'thegem'),
						'param_name' => 'search_icon_header',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Search Icon Pack', 'thegem'),
						'param_name' => 'search_icon_pack',
						'value' => array_merge(array(
							__('Elegant', 'thegem') => 'elegant',
							__('Material Design', 'thegem') => 'material',
							__('FontAwesome', 'thegem') => 'fontawesome',
							__('Header Icons', 'thegem') => 'thegem-header',
							__('Additional', 'thegem') => 'thegemdemo'),
							thegem_userpack_to_dropdown()
						),
						'std' => 'thegem-header',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Close Icon Pack', 'thegem'),
						'param_name' => 'close_icon_pack',
						'value' => array_merge(array(
							__('Elegant', 'thegem') => 'elegant',
							__('Material Design', 'thegem') => 'material',
							__('FontAwesome', 'thegem') => 'fontawesome',
							__('Header Icons', 'thegem') => 'thegem-header',
							__('Additional', 'thegem') => 'thegemdemo'),
							thegem_userpack_to_dropdown()
						),
						'std' => 'thegem-header',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'search_icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'search_icon_pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'search_icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'search_icon_pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'search_icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'search_icon_pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'search_icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'search_icon_pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'search_icon_thegemheader',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'search_icon_pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'search_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'search_icon_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				)),
				array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'close_icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'close_icon_pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'close_icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'close_icon_pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'close_icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'close_icon_pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'close_icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'close_icon_pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'close_icon_thegemheader',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'close_icon_pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'close_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'close_icon_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Appearance', 'thegem')
					),
				)),
				array(
					array(
						'type' => 'textfield',
						'heading' => __('Icon Size', 'thegem'),
						'param_name' => 'icon_size',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icon Color Normal', 'thegem'),
						'param_name' => 'icon_color_normal',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icon Color Hover', 'thegem'),
						'param_name' => 'icon_color_hover',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
				),
				thegem_set_elements_design_options()
			),
		);
	}
}

$templates_elements['thegem_te_search'] = new TheGem_Template_Element_Search();
$templates_elements['thegem_te_search']->init();