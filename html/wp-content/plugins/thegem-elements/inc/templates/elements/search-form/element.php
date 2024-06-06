<?php

class TheGem_Template_Element_Search_Form extends TheGem_Template_Element {

	public function __construct() {

		if (!defined('THEGEM_TE_SEARCHFORM_DIR')) {
			define('THEGEM_TE_SEARCHFORM_DIR', rtrim(__DIR__, ' /\\'));
		}

		if (!defined('THEGEM_TE_SEARCHFORM_URL')) {
			define('THEGEM_TE_SEARCHFORM_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}

		wp_register_style('thegem-te-search-form', THEGEM_TE_SEARCHFORM_URL . '/css/search.css', array(), null);
		wp_register_script('thegem-te-search-form', THEGEM_TE_SEARCHFORM_URL . '/js/search.js', array('jquery'), null, true);
		wp_localize_script('thegem-te-search-form', 'thegem_search_form_data', array(
			'ajax_url' => esc_url(admin_url('admin-ajax.php')),
			'ajax_nonce' => wp_create_nonce('ajax_security'),
		));
	}

	public function get_name() {
		return 'thegem_te_search_form';
	}

	public function head_scripts($attr) {
		$attr = shortcode_atts(array(
			'search_icon_pack' => 'thegem-header',
			'close_icon_pack' => 'thegem-header',
		), $attr, 'thegem_te_search_form');
		wp_enqueue_style('icons-' . $attr['search_icon_pack']);
		wp_enqueue_style('icons-' . $attr['close_icon_pack']);
		wp_enqueue_style('thegem-te-search-form');
		wp_enqueue_script('thegem-te-search-form');
	}

	public function front_editor_scripts($attr) {
		$attr = shortcode_atts(array(
			'search_icon_pack' => 'thegem-header',
			'close_icon_pack' => 'thegem-header',
		), $attr, 'thegem_te_search_form');
		wp_enqueue_style('icons-' . $attr['search_icon_pack']);
		wp_enqueue_style('icons-' . $attr['close_icon_pack']);
		wp_enqueue_style('thegem-te-search-form');
		wp_enqueue_script('thegem-te-search-form');
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'layout' => 'fullscreen',
			'placeholder_text' => __('Search...', 'thegem'),
			'layout_fullscreen_placeholder_text' => __('Start typing to search...', 'thegem'),
			'post_type_products' => '1',
			'product_categories_dropdown' => '1',
			'product_categories_dropdown_tablet' => '1',
			'product_categories_dropdown_mobile' => '1',
			'product_categories_placeholder_text' => 'All Categories',
			'post_type_posts' => '1',
			'post_type_pages' => '1',
			'post_type_portfolio' => '1',
			'search_ajax' => '1',
			'products_auto_suggestions' => '16',
			'posts_auto_suggestions' => '8',
			'pages_auto_suggestions' => '8',
			'portfolio_auto_suggestions' => '8',
			'products_result_title' => __('Product', 'thegem'),
			'posts_result_title' => __('Blog', 'thegem'),
			'pages_result_title' => __('Pages', 'thegem'),
			'portfolio_result_title' => __('Portfolio', 'thegem'),
			'view_results_button_text' => __('View all search results', 'thegem'),
			'show_search_icon' => '1',
			'search_icon_pack' => 'thegem-header',
			'search_icon_elegant' => '',
			'search_icon_material' => '',
			'search_icon_fontawesome' => '',
			'search_icon_thegemheader' => '',
			'search_icon_thegemdemo' => '',
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
			'form_width' => 'custom',
			'form_width_mobile' => '310',
			'form_width_tablet' => '310',
			'form_width_desktop' => '310',
			'form_height' => '34',
			'form_input_padding' => '',
			'form_border_radius' => '',
			'form_bg_color' => '',
			'form_focus_bg_color' => '',
			'form_border_width' => '',
			'form_focus_border_width' => '',
			'form_border_color' => '',
			'form_focus_border_color' => '',
			'input_results_color_normal' => '',
			'input_results_color_hover' => '',
			'input_placeholder_color' => '',
			'results_title_color' => '',
		), thegem_templates_design_options_extract(), thegem_templates_extra_options_extract()), $atts, 'thegem_te_search_form');

		if (!defined('WC_PLUGIN_FILE')) {
			$params['post_type_products'] = '';
		}

		$uniqid = uniqid('thegem-custom-') . rand(1, 9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-search-form', $params);

		if ($params['icon_size'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .search-submit {font-size: ' . $params['icon_size'] . 'px; width: ' . $params['icon_size'] . 'px; height: ' . $params['icon_size'] . 'px;}';
		}
		if ($params['icon_color_normal'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .search-submit {color: ' . $params['icon_color_normal'] . ';}';
		}
		if ($params['icon_color_hover'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .search-submit:hover {color: ' . $params['icon_color_hover'] . ';}';
		}
		if ($params['form_width'] == 'full') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ', .' . esc_attr($uniqid) . '-editor {width: 100%;}';
		} else {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' {width: ' . $params['form_width_mobile'] . 'px;}';
			$custom_css .= '@media screen and (min-width: 768px) { .thegem-te-search-form.' . esc_attr($uniqid) . ' {width: ' . $params['form_width_tablet'] . 'px;}}';
			$custom_css .= '@media screen and (min-width: 992px) { .thegem-te-search-form.' . esc_attr($uniqid) . ' {width: ' . $params['form_width_desktop'] . 'px;}}';
		}
		if ($params['form_height'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .search-field, .thegem-te-search-form.' . esc_attr($uniqid) . ' .search-submit, .thegem-te-search-form.' . esc_attr($uniqid) . ' .search-submit i { height: ' . $params['form_height'] . 'px; line-height: ' . $params['form_height'] . 'px;}';
		}
		if ($params['form_input_padding'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .search-field {padding-left: ' . $params['form_input_padding'] . 'px;}';
		}
		if ($params['form_border_radius'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .search-field {border-radius: ' . $params['form_border_radius'] . 'px;}';
		}
		if ($params['form_bg_color'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .search-field, .thegem-te-search-form.' . esc_attr($uniqid) . ' .ajax-search-results, .thegem-te-search-form.' . esc_attr($uniqid) . ' .select-category .select {background-color: ' . $params['form_bg_color'] . ';}';
		}
		if ($params['form_focus_bg_color'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .search-field:focus {background-color: ' . $params['form_focus_bg_color'] . ';}';
		}
		if ($params['form_border_width'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .search-field {border-width: ' . $params['form_border_width'] . 'px;}';
		}
		if ($params['form_focus_border_width'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .search-field:focus {border-width: ' . $params['form_focus_border_width'] . 'px;}';
		}
		if ($params['form_border_color'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .search-field {border-color: ' . $params['form_border_color'] . ';}';
		}
		if ($params['form_focus_border_color'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .search-field:focus {border-color: ' . $params['form_focus_border_color'] . ';}';
		}
		if ($params['input_results_color_normal'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' {color: ' . $params['input_results_color_normal'] . ';}';
		}
		if ($params['input_results_color_hover'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .ajax-search-results .ajax-search-item a:hover, .thegem-te-search-form.' . esc_attr($uniqid) . ' .select-category .select .term.active, .thegem-te-search-form.' . esc_attr($uniqid) . ' .select-category .select .term:hover {color: ' . $params['input_results_color_hover'] . ';}';
		}
		if ($params['input_placeholder_color'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .search-field::-webkit-input-placeholder, .thegem-te-search-form.' . esc_attr($uniqid) . ' .search-field::placeholder {color: ' . $params['input_placeholder_color'] . '; opacity: 1;}';
		}
		if ($params['results_title_color'] !== '') {
			$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .title {color: ' . $params['results_title_color'] . ';}';
		}
		if ($params['post_type_products'] == '1') {
			if ($params['product_categories_dropdown_mobile'] == '1') {
				$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .select-category {display: flex;}';
			} else {
				$custom_css .= '.thegem-te-search-form.' . esc_attr($uniqid) . ' .select-category {display: none;}';
			}
			if ($params['product_categories_dropdown_tablet'] == '1') {
				$custom_css .= '@media screen and (min-width: 768px) { .thegem-te-search-form.' . esc_attr($uniqid) . ' .select-category {display: flex;}}';
			} else {
				$custom_css .= '@media screen and (min-width: 768px) {.thegem-te-search-form.' . esc_attr($uniqid) . ' .select-category {display: none;}}';
			}
			if ($params['product_categories_dropdown'] == '1') {
				$custom_css .= '@media screen and (min-width: 992px) { .thegem-te-search-form.' . esc_attr($uniqid) . ' .select-category {display: flex;}}';
			} else {
				$custom_css .= '@media screen and (min-width: 992px) {.thegem-te-search-form.' . esc_attr($uniqid) . ' .select-category {display: none;}}';
			}
		}

		ob_start();

		$search_item_class = '';
		if ($params['search_ajax'] == '1') {
			$search_item_class = 'ajax-search-form';
			if ($params['post_type_products'] == '1' && ($params['product_categories_dropdown'] == '1' || $params['product_categories_dropdown_tablet'] == '1' || $params['product_categories_dropdown_mobile'] == '1')) {
				$search_item_class .= ' with-categories';
			}
		} ?>

		<div class="thegem-te-search-form <?php echo esc_attr($uniqid); ?> <?php echo esc_html($search_item_class); ?>" <?= thegem_data_editor_attribute($uniqid . '-editor') ?>>
			<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
				<input class="search-field" type="search" name="s"
					   placeholder="<?php echo $params['placeholder_text']; ?>"/>
				<?php if ($params['search_ajax'] == '1') { ?>
					<div class="ajax-search-results-wrap"><div class="ajax-search-results"></div></div>
					<div class="search-buttons">
						<?php if ($params['post_type_products'] == '1' && ($params['product_categories_dropdown'] == '1' || $params['product_categories_dropdown_tablet'] == '1' || $params['product_categories_dropdown_mobile'] == '1')) { ?>
							<div class="select-category">
								<div class="current">
								<span class="text"
									  data-term=""><?php echo esc_html($params['product_categories_placeholder_text']) ?></span>
									<span class="arrow-down"></span>
								</div>
								<div class="select">
									<div class="scroll-block">
										<?php $this->termLoop($params['product_categories_placeholder_text']); ?>
									</div>
								</div>
							</div>
						<?php } ?>
						<?php if ($params['show_search_icon']) { ?>
							<div class="search-submit">
								<span class="open">
									<?php if (isset($params['search_icon_pack']) && $params['search_icon_' . str_replace("-", "", $params['search_icon_pack'])] != '') {
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
							</div>
						<?php } ?>
					</div>
					<?php
					$post_types_arr = [];
					$post_types_ppp_arr = [];
					$result_title_arr = [];
					if ($params['post_type_products'] == '1') {
						array_push($post_types_arr, 'product');
						array_push($post_types_ppp_arr, $params['products_auto_suggestions']);
						array_push($result_title_arr, $params['products_result_title']);
					}
					if ($params['post_type_posts'] == '1') {
						array_push($post_types_arr, 'post');
						array_push($post_types_ppp_arr, $params['posts_auto_suggestions']);
						array_push($result_title_arr, $params['posts_result_title']);
					}
					if ($params['post_type_portfolio'] == '1') {
						array_push($post_types_arr, 'thegem_pf_item');
						array_push($post_types_ppp_arr, $params['portfolio_auto_suggestions']);
						array_push($result_title_arr, $params['portfolio_result_title']);
					}
					if ($params['post_type_pages'] == '1') {
						array_push($post_types_arr, 'page');
						array_push($post_types_ppp_arr, $params['pages_auto_suggestions']);
						array_push($result_title_arr, $params['pages_result_title']);
					}
					$post_types = json_encode($post_types_arr);
					$post_types_ppp = json_encode($post_types_ppp_arr);
					$result_title = json_encode($result_title_arr);
					$ajax_data = 'data-post-types="' . esc_attr($post_types) . '"
					data-post-types-ppp="' . esc_attr($post_types_ppp) . '"
					data-result-title="' . esc_attr($result_title) . '"
					data-show-all="' . esc_attr($params['view_results_button_text']) . '"';

					if ($params['post_type_products'] == '1') { ?>
						<input type="hidden" name="post_type" value="product" />
					<?php } ?>

					<div class="ajax-search-params" <?php echo $ajax_data; ?>></div>
				<?php } else if ($params['show_search_icon']) { ?>
					<div class="search-buttons">
						<button class="search-submit" type="submit">
							<span class="open">
								<?php if (isset($params['search_icon_pack']) && $params['search_icon_' . str_replace("-", "", $params['search_icon_pack'])] != '') {
									echo thegem_build_icon($params['search_icon_pack'], $params['search_icon_' . str_replace("-", "", $params['search_icon_pack'])]);
								} else { ?>
									<i class="default"></i>
								<?php } ?>
							</span>
						</button>
					</div>
				<?php } ?>
			</form>
		</div>

		<?php
		if (thegem_is_plugin_active('js_composer/js_composer.php')) {
			global $vc_manager;
			if ($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode() == 'page_editable') { ?>
				<script type="text/javascript">
					(function ($) {
						setTimeout(function () {
							$('.<?php echo esc_attr($uniqid . '-editor') ?>.thegem-te-search-form.ajax-search-form').initSearchForms();
						}, 1000);
					})(jQuery);
				</script>
			<?php }
		}

		// Print custom css
		if (!empty($custom_css)) { ?>
			<style><?php echo esc_js($custom_css); ?></style>
		<?php }

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
		return $return_html;
	}

	function termLoop($all_text = false, $parent = 0) {
		$terms = get_terms('product_cat', ['parent' => $parent]);

		if (!empty($terms) && !is_wp_error($terms)) { ?>
			<ul>
				<?php
				if ($all_text) { ?>
					<li>
						<div class="term active" data-term="">
							<?php echo esc_html($all_text) ?>
						</div>
					</li>
				<?php }
				foreach ($terms as $term) { ?>
					<li>
						<div class="term" data-term="<?php echo $term->slug; ?>">
							<?php echo $term->name; ?>
						</div>
						<?php
						$this->termLoop(false, $term->term_id);
						?>
					</li>
				<?php } ?>
			</ul>
		<?php }
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Search Form', 'thegem'),
			'base' => 'thegem_te_search_form',
			'icon' => 'thegem-icon-wpb-ui-element-search-form',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('Elements - Search Form', 'thegem'),
			'params' => array_merge(
				array(
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
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Post Types', 'thegem'),
						'param_name' => 'post_types_header',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Layout', 'thegem'),
						'dependency' => array(
							'element' => 'search_ajax',
							'not_empty' => true
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Products', 'thegem'),
						'param_name' => 'post_type_products',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem'),
						'dependency' => array(
							'element' => 'search_ajax',
							'not_empty' => true
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Blog Posts', 'thegem'),
						'param_name' => 'post_type_posts',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem'),
						'dependency' => array(
							'element' => 'search_ajax',
							'not_empty' => true
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Pages', 'thegem'),
						'param_name' => 'post_type_pages',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem'),
						'dependency' => array(
							'element' => 'search_ajax',
							'not_empty' => true
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Portfolio', 'thegem'),
						'param_name' => 'post_type_portfolio',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem'),
						'dependency' => array(
							'element' => 'search_ajax',
							'not_empty' => true
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Product Categories List', 'thegem'),
						'param_name' => 'product_categories_dropdown',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'post_type_products',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Product Categories List Tablet', 'thegem'),
						'param_name' => 'product_categories_dropdown_tablet',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'post_type_products',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Product Categories List Mobile', 'thegem'),
						'param_name' => 'product_categories_dropdown_mobile',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'post_type_products',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Categories Placeholder Text', 'thegem'),
						'param_name' => 'product_categories_placeholder_text',
						'value' => __('All Categories', 'thegem'),
						'dependency' => array(
							'element' => 'post_type_products',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Live Search', 'thegem'),
						'param_name' => 'results_header',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('AJAX Live Search', 'thegem'),
						'param_name' => 'search_ajax',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Products Auto-Suggestions', 'thegem'),
						'param_name' => 'products_auto_suggestions',
						'dependency' => array(
							'element' => 'search_ajax',
							'not_empty' => true
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
							'element' => 'search_ajax',
							'not_empty' => true
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
							'element' => 'search_ajax',
							'not_empty' => true
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
							'element' => 'search_ajax',
							'not_empty' => true
						),
						'std' => '8',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Products Results Title', 'thegem'),
						'param_name' => 'products_result_title',
						'value' => __('Products', 'thegem'),
						'dependency' => array(
							'element' => 'search_ajax',
							'not_empty' => true
						),
						'group' => __('Layout', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Posts Results Title', 'thegem'),
						'param_name' => 'posts_result_title',
						'value' => __('Blog', 'thegem'),
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
						'value' => __('Pages', 'thegem'),
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
						'value' => __('Portfolio', 'thegem'),
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
				),
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Search Icon', 'thegem'),
						'param_name' => 'search_icon_header',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'show_search_icon',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-12',
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
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'show_search_icon',
							'not_empty' => true
						)
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
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'show_search_icon',
							'not_empty' => true
						)
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
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'show_search_icon',
							'not_empty' => true
						)
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icon Color Normal', 'thegem'),
						'param_name' => 'icon_color_normal',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'show_search_icon',
							'not_empty' => true
						)
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Icon Color Hover', 'thegem'),
						'param_name' => 'icon_color_hover',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'show_search_icon',
							'not_empty' => true
						)
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Search Form', 'thegem'),
						'param_name' => 'search_form_header',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Width', 'thegem'),
						'param_name' => 'form_width',
						'value' => array(
							__('Custom Width', 'thegem') => 'custom',
							__('Full Width', 'thegem') => 'full',
						),
						'std' => 'custom',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Width Desktop', 'thegem'),
						'param_name' => 'form_width_desktop',
						'std' => 310,
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'form_width',
							'value' => 'custom'
						)
					),
					array(
						'type' => 'textfield',
						'heading' => __('Width Tablet', 'thegem'),
						'param_name' => 'form_width_tablet',
						'std' => 310,
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'form_width',
							'value' => 'custom'
						)
					),
					array(
						'type' => 'textfield',
						'heading' => __('Width Mobile', 'thegem'),
						'param_name' => 'form_width_mobile',
						'std' => 310,
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
						'dependency' => array(
							'element' => 'form_width',
							'value' => 'custom'
						)
					),
					array(
						'type' => 'textfield',
						'heading' => __('Height', 'thegem'),
						'param_name' => 'form_height',
						'std' => 34,
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left Spacing', 'thegem'),
						'param_name' => 'form_input_padding',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right Spacing', 'thegem'),
						'param_name' => 'form_buttons_right',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Form Border Radius', 'thegem'),
						'param_name' => 'form_border_radius',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'form_bg_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Focus Background Color', 'thegem'),
						'param_name' => 'form_focus_bg_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Border Width', 'thegem'),
						'param_name' => 'form_border_width',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Focus Border Width', 'thegem'),
						'param_name' => 'form_focus_border_width',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Border Color', 'thegem'),
						'param_name' => 'form_border_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Focus Border Color', 'thegem'),
						'param_name' => 'form_focus_border_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Input & Results', 'thegem'),
						'param_name' => 'input_results_heading',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color Normal', 'thegem'),
						'param_name' => 'input_results_color_normal',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Text Color Hover', 'thegem'),
						'param_name' => 'input_results_color_hover',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Placeholder Color', 'thegem'),
						'param_name' => 'input_placeholder_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Results Title Color', 'thegem'),
						'param_name' => 'results_title_color',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group' => __('Appearance', 'thegem')
					),
				),
				thegem_set_elements_design_options()
			),
		);
	}
}

$templates_elements['thegem_te_search_form'] = new TheGem_Template_Element_Search_Form();
$templates_elements['thegem_te_search_form']->init();