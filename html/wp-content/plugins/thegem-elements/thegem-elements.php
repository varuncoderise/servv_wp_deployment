<?php
/*
Plugin Name: TheGem Theme Elements (for WPBakery)
Plugin URI: http://codex-themes.com/thegem/
Description: Extended features and functionality for TheGem theme, including rich collection of page elements for WPBakery Page Builder.
Author: Codex Themes
Version: 5.9.3
Author URI: http://codex-themes.com/thegem/
TextDomain: thegem
DomainPath: /languages
*/

if ( ! defined( 'THEGEM_THEME_URI' ) ) {
	define( 'THEGEM_THEME_URI', get_template_directory_uri() );
}
if ( ! defined( 'THEGEM_THEME_VERSION' ) ) {
	define( 'THEGEM_THEME_VERSION', wp_get_theme(wp_get_theme()->get('Template'))->get('Version') );
}
if ( ! defined( 'THEGEM_PAGE_EDITOR' ) ) {
	define( 'THEGEM_PAGE_EDITOR', 'wpbackery' );
}
if ( ! defined( 'THEGEM_ELEMENTS' ) ) {
	define( 'THEGEM_ELEMENTS', 1 );
}

add_action( 'plugins_loaded', 'thegem_load_textdomain' );
function thegem_load_textdomain() {
	load_plugin_textdomain( 'thegem', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

if(!function_exists('thegem_is_plugin_active')) {
	function thegem_is_plugin_active($plugin) {
		include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		return is_plugin_active($plugin);
	}
}

if(!function_exists('thegem_user_icons_info_link')) {
function thegem_user_icons_info_link($pack = '') {
	return esc_url(apply_filters('thegem_user_icons_info_link', THEGEM_THEME_URI.'/fonts/icons-list-'.$pack.'.html', $pack));
}
}

/* Get theme option*/
if(!function_exists('thegem_get_current_language')) {
function thegem_get_current_language() {
	static $result;

	if (isset($result)) {
		return $result;
	}

	if(thegem_is_plugin_active('sitepress-multilingual-cms/sitepress.php') && defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE) {
		$result = ICL_LANGUAGE_CODE;
		return $result;
	}
	if(defined( 'POLYLANG_VERSION' ) && function_exists('pll_current_language') && pll_current_language('slug')) {
		$result = pll_current_language('slug');
		return $result;
	}

	$result = false;
	return $result;
}
}

if(!function_exists('thegem_get_default_language')) {
function thegem_get_default_language() {
	static $result;

	if (isset($result)) {
		return $result;
	}

	if(thegem_is_plugin_active('sitepress-multilingual-cms/sitepress.php')) {
		global $sitepress;
		if(is_object($sitepress) && $sitepress->get_default_language()) {
			$result = $sitepress->get_default_language();
			return $result;
		}
	}
	if(thegem_is_plugin_active('polylang/polylang.php') && pll_default_language('slug')) {
		$result = pll_default_language('slug');
		return $result;
	}

	$result = false;
	return $result;
}
}

if (!function_exists('thegem_lazy_loading_enqueue')) {
	function thegem_lazy_loading_enqueue() {
		wp_enqueue_script('thegem-lazy-loading');
		wp_enqueue_style('thegem-lazy-loading-animations');
	}
}

if (!function_exists('thegem_translated_options')) {
	function thegem_translated_options() {
		return apply_filters('thegem_translated_options', array(
			'footer_html', 'top_area_button_text', 'top_area_button_link', 'contacts_address', 'contacts_phone', 'contacts_fax', 'contacts_email', 'contacts_website', 'top_area_contacts_address', 'top_area_contacts_phone', 'top_area_contacts_fax', 'top_area_contacts_email', 'top_area_contacts_website', 'custom_footer', 'header_builder', 'post_builder_template', 'logo', 'logo_light', 'small_logo', 'small_logo_light', 'portfolio_archive_page',
			'product_archive_quick_view_text', 'product_archive_cart_button_text', 'product_archive_select_options_button_text', 'product_archive_more_button_text', 'product_archive_filter_by_categories_title', 'product_archive_filter_by_price_title', 'product_archive_filter_by_status_title', 'product_archive_filter_by_status_sale_text', 'product_archive_filter_by_status_stock_text', 'product_archive_filters_text_labels_all_text', 'product_archive_filters_text_labels_clear_text', 'product_archive_filters_text_labels_search_text', 'product_archive_filter_buttons_hidden_show_text', 'product_archive_filter_buttons_hidden_sidebar_title', 'product_archive_filter_buttons_hidden_filter_by_text', 'product_archive_added_cart_text', 'product_archive_added_wishlist_text', 'product_archive_removed_wishlist_text', 'product_archive_view_cart_button_text', 'product_archive_checkout_button_text', 'product_archive_view_wishlist_button_text', 'product_archive_not_found_text',
			'product_page_desc_review_description_title', 'product_page_desc_review_additional_info_title', 'product_page_desc_review_reviews_title', 'product_page_button_add_to_cart_text', 'product_page_button_clear_attributes_text', 'product_page_elements_reviews_text', 'product_page_elements_sku_title', 'product_page_elements_categories_title', 'product_page_elements_tags_title', 'product_page_elements_share_title', 'product_page_elements_upsell_title', 'product_page_elements_related_title',
			'cart_empty_title', 'cart_empty_text', 'cart_elements_cross_sells_title', 'product_builder_template', 'product_archive_builder_template', 'cart_builder_template', 'cart_empty_builder_template', 'checkout_builder_template', 'search_layout_mixed_grids_title', 'search_layout_mixed_grids_show_all', 'product_archive_filter_by_attribute_data', 'size_guide_text', 'mini_cart_sidebar_title', 'mini_cart_sidebar_infotext', 'product_page_additional_tabs_data', 'blog_layout_caption_read_more_text', 'website_search_page_title', 'website_search_page_excerpt', 'mobile_menu_show_this_page_text', 'mobile_menu_back_text'
		));
	}
}

if(!function_exists('thegem_get_option')) {
function thegem_get_option($name, $default = false, $ml_full = false, $clearCache = false) {
	static $ref_options;
	static $cache = [];

	if ($clearCache) {
		$ref_options = null;
		$cache = [];
	}
	$cacheKey = $name.'_'.$default.'_'.$ml_full;

	if (isset($cache[$cacheKey])) {
		return $cache[$cacheKey];
	}

	if (!isset($ref_options)) {
		$ref_options = get_option('thegem_theme_options');
	}
	$options = $ref_options;

	if(isset($options[$name])) {
		$ml_options = thegem_translated_options();
		if(in_array($name, $ml_options) && is_array($options[$name]) && !$ml_full) {
			if(thegem_get_current_language()) {
				if(isset($options[$name][thegem_get_current_language()])) {
					$options[$name] = $options[$name][thegem_get_current_language()];
				} elseif(thegem_get_default_language() && isset($options[$name][thegem_get_default_language()])) {
					$options[$name] = $options[$name][thegem_get_default_language()];
				} else {
					$options[$name] = '';
				}
			}else {
				$options[$name] = reset($options[$name]);
			}
		}
		$result = apply_filters('thegem_option_'.$name, $options[$name]);
		$cache[$cacheKey] = $result;
		return $result;
	}
	$result = apply_filters('thegem_option_'.$name, $default);
	$cache[$cacheKey] = $result;
	return $result;
}
}

/* USER ICON PACK */

if(!function_exists('thegem_icon_userpack_enabled')) {
function thegem_icon_userpack_enabled() {
	return apply_filters('thegem_icon_userpack_enabled', false);
}
}

if(!function_exists('thegem_icon_packs_select_array')) {
function thegem_icon_packs_select_array() {
	$packs = array('elegant' => __('Elegant', 'thegem'), 'material' => __('Material Design', 'thegem'), 'fontawesome' => __('FontAwesome', 'thegem'), 'thegem-header' => __('Header Icons', 'thegem'), 'thegemdemo' => __('Additional', 'thegem'));
	if(thegem_icon_userpack_enabled()) {
		$packs['userpack'] = __('UserPack', 'thegem');
	}
	return $packs;
}
}

if(!function_exists('thegem_icon_packs_infos')) {
function thegem_icon_packs_infos() {
	ob_start();
?>
<?php _e('Enter icon code', 'thegem'); ?>.
<a class="gem-icon-info gem-icon-info-elegant" href="<?php echo thegem_user_icons_info_link('elegant'); ?>" onclick="tb_show('<?php _e('Icons info', 'thegem'); ?>', this.href+'?TB_iframe=true'); return false;"><?php _e('Show Elegant Icon Codes', 'thegem'); ?></a>
<a class="gem-icon-info gem-icon-info-material" href="<?php echo thegem_user_icons_info_link('material'); ?>" onclick="tb_show('<?php _e('Icons info', 'thegem'); ?>', this.href+'?TB_iframe=true'); return false;"><?php _e('Show Material Design Icon Codes', 'thegem'); ?></a>
<a class="gem-icon-info gem-icon-info-fontawesome" href="<?php echo thegem_user_icons_info_link('fontawesome'); ?>" onclick="tb_show('<?php _e('Icons info', 'thegem'); ?>', this.href+'?TB_iframe=true'); return false;"><?php _e('Show FontAwesome Icon Codes', 'thegem'); ?></a>
<a class="gem-icon-info gem-icon-info-thegemdemo" href="<?php echo thegem_user_icons_info_link('thegemdemo'); ?>" onclick="tb_show('<?php _e('Icons info', 'thegem'); ?>', this.href+'?TB_iframe=true'); return false;"><?php _e('Show TheGem Demo Icon Codes', 'thegem'); ?></a>

	<?php if(thegem_icon_userpack_enabled()) : ?>
<a class="gem-icon-info gem-icon-info-userpack" href="<?php echo thegem_user_icons_info_link('userpack'); ?>" onclick="tb_show('<?php _e('Icons info', 'thegem'); ?>', this.href+'?TB_iframe=true'); return false;"><?php _e('Show UserPack Icon Codes', 'thegem'); ?></a>
<?php endif; ?>
<?php
	return ob_get_clean();
}
}


/* META BOXES */

if(!function_exists('thegem_print_select_input')) {
function thegem_print_select_input($values = array(), $value = '', $name = '', $id = '') {
	if(!is_array($values)) {
		$values = array();
	}
?>
	<select name="<?php echo esc_attr($name) ?>" id="<?php echo esc_attr($id); ?>" class="thegem-combobox">
		<?php foreach($values as $key => $title) : ?>
			<option value="<?php echo esc_attr($key); ?>" <?php selected($key, $value); ?>><?php echo esc_html($title); ?></option>
		<?php endforeach; ?>
	</select>
<?php
}
}

if(!function_exists('thegem_print_checkboxes')) {
function thegem_print_checkboxes($values = array(), $value = array(), $name = '', $id_prefix = '', $after = '') {
	if(!is_array($values)) {
		$values = array();
	}
	if(!is_array($value)) {
		$value = array();
	}
?>
	<?php foreach($values as $key => $title) : ?>
		<input name="<?php echo esc_attr($name); ?>" type="checkbox" id="<?php echo esc_attr($id_prefix.'-'.$key); ?>" value="<?php echo esc_attr($key); ?>" <?php checked(in_array($key, $value), 1); ?> />
		<label for="<?php echo esc_attr($id_prefix.'-'.$key); ?>"><?php echo esc_html($title); ?></label>
		<?php echo $after; ?>
	<?php endforeach; ?>
<?php
}
}

if(!function_exists('thegem_add_srcset_rule')) {
	function thegem_add_srcset_rule(&$srcset, $condition, $size, $id=false) {
		if (!$id) {
			$id = get_post_thumbnail_id();
		}
		$im = thegem_generate_thumbnail_src($id, $size);
		$srcset[$condition] = $im[0];
	}
}

if(!function_exists('thegem_srcset_generate_urls')) {
	function thegem_srcset_generate_urls($attachment_id, $srcset) {
		$result = array();
		$thegem_sizes = array_keys(thegem_image_sizes());
		foreach ($srcset as $condition => $size) {
			if (!in_array($size, $thegem_sizes)) {
				continue;
			}
			if(!thegem_get_option('retina_thumbnails_enabled') && $condition !== '1x') {
				continue;
			}
			$im = thegem_generate_thumbnail_src($attachment_id, $size);
			if ($im) {
				$result[$condition] = esc_url($im[0]);
			}
		}
		return $result;
	}
}

if(!function_exists('thegem_srcset_list_to_string')) {
	function thegem_srcset_list_to_string($srcset) {
		if (count($srcset) == 0) {
			return '';
		}
		$srcset_condtions = array();
		foreach ($srcset as $condition => $url) {
			$srcset_condtions[] = $url . ' ' . $condition;
		}
		return implode(', ', $srcset_condtions);
	}
}

if(!function_exists('thegem_generate_picture_sources')) {
	function thegem_generate_picture_sources($attachment_id, $sources) {
		if (!$sources) {
			return '';
		}
		?>
		<?php foreach ($sources as $source): ?>
			<?php
				if(!thegem_get_option('mobile_thumbnails_enabled') && !empty($source['media'])) {
					continue;
				}
				$srcset = thegem_srcset_generate_urls($attachment_id, $source['srcset']);
				if (!$srcset) {
					continue;
				}
			?>
			<source srcset="<?php echo thegem_srcset_list_to_string($srcset); ?>" <?php if(!empty($source['media'])): ?>media="<?php echo esc_attr($source['media']); ?>"<?php endif; ?> <?php if(!empty($source['type'])): ?>type="<?php echo esc_attr($source['type']); ?>"<?php endif; ?> sizes="<?php echo !empty($source['sizes']) ? esc_attr($source['sizes']) : '100vw'; ?>">
		<?php endforeach; ?>
		<?php
	}
}

if(!function_exists('thegem_generate_picture')) {
	function thegem_generate_picture($attachment_id, $default_size, $sources=array(), $attrs=array(), $return_info=false) {
		if (!$attachment_id || (!in_array($default_size, ['full', 'woocommerce_thumbnail', 'woocommerce_single', 'thumbnail', 'medium', 'medium_large', 'large', '1536x1536', '2048x2048']) && !in_array($default_size, array_keys(thegem_image_sizes())))) {
			return '';
		}
		$default_image = thegem_generate_thumbnail_src($attachment_id, $default_size);
		if (!$default_image) {
			return '';
		}
		list($src, $width, $height) = $default_image;
		$hwstring = image_hwstring($width, $height);

		$default_attrs = array('class' => "attachment-$default_size");
		if (empty($attrs['alt'])) {
			$attachment = get_post($attachment_id);
			$attrs['alt'] = trim(strip_tags(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)));
			if ($attachment) {
				if(empty($default_attr['alt']))
					$attrs['alt'] = trim(strip_tags($attachment->post_excerpt));
				if(empty($default_attr['alt']))
					$attrs['alt'] = trim(strip_tags($attachment->post_title));
			}
		}

		$attrs = wp_parse_args($attrs, $default_attrs);
		$attrs = array_map('esc_attr', $attrs);
		$attrs_set = array();
		foreach ($attrs as $attr_key => $attr_value) {
			$attrs_set[] = $attr_key . '="' . $attr_value . '"';
		}
		?>
		<picture>
			<?php thegem_generate_picture_sources($attachment_id, $sources); ?>
			<img src="<?php echo $src; ?>" <?php echo $hwstring; ?> <?php echo implode(' ', $attrs_set); ?> />
		</picture>
		<?php
		if ($return_info) {
			return array(
				'default' => $default_image
			);
		}
	}
}

if(!function_exists('interactions_data_attr')) {
	function interactions_data_attr($attr) {
		$data_attr = ' ';
		wp_enqueue_script('thegem-interactions');

		if($attr['vertical_scroll']) {
			$data_attr .= 'data-vertical_scroll_enable="yes" ';
			if($attr['vertical_scroll_direction'] == 'vertical_scroll_direction_up') {
				$vertical_scroll_direction = 1;
			} else {
				$vertical_scroll_direction = -1;
			}
			if(empty($attr['vertical_scroll_speed'])) {
				$vertical_scroll_speed = 0;
				$data_attr .= 'data-vertical_scroll_speed="0" ';
			} else {
				$vertical_scroll_speed = $attr['vertical_scroll_speed'];
				$vertical_scroll_speed = str_replace ('-','',$vertical_scroll_speed);
				if($vertical_scroll_speed >= 10) {
					$vertical_scroll_speed = 10;
				}
				$data_attr .= 'data-vertical_scroll_speed="'.$vertical_scroll_speed * $vertical_scroll_direction.'" ';
			}
			if((int)$attr['vertical_scroll_viewport_bottom'] !== 0) {
				$vertical_scroll_viewport_bottom = (50 - (int)$attr['vertical_scroll_viewport_bottom']) * $vertical_scroll_speed * $vertical_scroll_direction;
				if($vertical_scroll_direction === 1) {
					$data_attr .= 'data-rellax-max-y="'.(int)$vertical_scroll_viewport_bottom.'" ';
				} else {
					$data_attr .= 'data-rellax-min-y="'.(int)$vertical_scroll_viewport_bottom.'" ';
				}
			}
			if((int)$attr['vertical_scroll_viewport_top'] !== 100) {
				$vertical_scroll_viewport_top = (50 - (int)$attr['vertical_scroll_viewport_top']) * $vertical_scroll_speed * $vertical_scroll_direction;
				if ($vertical_scroll_direction === 1) {
					$data_attr .= 'data-rellax-min-y="'.(int)$vertical_scroll_viewport_top.'" ';
				} else {
					$data_attr .= 'data-rellax-max-y="'.(int)$vertical_scroll_viewport_top.'" ';
				}
			}
		}

		if($attr['horizontal_scroll']) {
			$data_attr .= 'data-horizontal_scroll_enable="yes" ';
			if($attr['horizontal_scroll_direction'] == 'horizontal_scroll_direction_left') {
				$horizontal_scroll_direction = 1;
			} else {
				$horizontal_scroll_direction = -1;
			}
			if(empty($attr['horizontal_scroll_speed'])) {
				$horizontal_scroll_speed = 0;
				$data_attr .= 'data-horizontal_scroll_speed="0" ';
			} else {
				$horizontal_scroll_speed = $attr['horizontal_scroll_speed'];
				$horizontal_scroll_speed = str_replace ('-','',$horizontal_scroll_speed);
				if($horizontal_scroll_speed >= 10) {
					$horizontal_scroll_speed = 10;
				}
				$data_attr .= 'data-horizontal_scroll_speed="'.$horizontal_scroll_speed * $horizontal_scroll_direction.'" ';
			}
//			var_dump((int)$attr['horizontal_scroll_viewport_bottom']);die();
			if((int)$attr['horizontal_scroll_viewport_bottom'] !== 0) {
				$horizontal_scroll_viewport_bottom = (50 - (int)$attr['horizontal_scroll_viewport_bottom']) * (int)$horizontal_scroll_speed * (int)$horizontal_scroll_direction;
				if($horizontal_scroll_direction === 1) {
					$data_attr .= 'data-rellax-max-x="'.(int)$horizontal_scroll_viewport_bottom.'" ';
				} else {
					$data_attr .= 'data-rellax-min-x="'.(int)$horizontal_scroll_viewport_bottom.'" ';
				}
			}
			if((int)$attr['horizontal_scroll_viewport_top'] !== 100) {
				$horizontal_scroll_viewport_top = (50 - (int)$attr['horizontal_scroll_viewport_top']) * (int)$horizontal_scroll_speed * (int)$horizontal_scroll_direction;
				if($horizontal_scroll_direction === 1) {
					$data_attr .= 'data-rellax-min-x="'.(int)$horizontal_scroll_viewport_top.'" ';
				} else {
					$data_attr .= 'data-rellax-max-x="'.(int)$horizontal_scroll_viewport_top.'" ';
				}
			}
		}

		if($attr['mouse_effects']) {
			$data_attr .= 'data-mouse_effects="yes" ';
			if($attr['mouse_effects_direction'] == 'mouse_effects_direction_opposite') {
				$data_attr .= 'data-mouse_effects_direction="-1" ';
			} else {
				$data_attr .= 'data-mouse_effects_direction="1" ';
			}
			if(empty($attr['mouse_effects_speed'])) {
				$data_attr .= 'data-mouse_effects_speed="0" ';
			} else {
				$mouse_effects_speed = $attr['mouse_effects_speed'];
				$mouse_effects_speed = str_replace ('-','',$mouse_effects_speed);
				if($mouse_effects_speed >= 10) {
					$mouse_effects_speed = 10;
				}
				$data_attr .= 'data-mouse_effects_speed="'.$mouse_effects_speed.'" ';
			}
		}

		if($attr['disable_effects_desktop'] == '1') {
			$data_attr .= 'data-disable_effects_desktop="disable" ';
		}
		if($attr['disable_effects_tablet'] == '1') {
			$data_attr .= 'data-disable_effects_tablet="disable" ';
		}
		if($attr['disable_effects_mobile'] == '1') {
			$data_attr .= 'data-disable_effects_mobile="disable" ';
		}

		return $data_attr;
	}
}

if(!function_exists('thegem_templates_flex_options')) {
	function thegem_templates_flex_options($uniqid, $el_class, $params) {
		$custom_css = '';
		$shortcode = $el_class;
		$vertical = 'align_vertical';
		$horizontal = 'align_horizontal';
		$direction = 'direction';
		$wrap = 'wrap';
		if(isset($GLOBALS['thegem_template_type']) || thegem_is_template_post('header') || thegem_is_template_post('single-product')) {
			$direction_std_value = 'column';
			$params = shortcode_atts(array(
				'desktop_direction' => (isset($GLOBALS['thegem_template_type']) && $GLOBALS['thegem_template_type'] == 'header') || thegem_is_template_post('header') ? 'row' : 'column',
				'tablet_direction' => '',
				'mobile_direction' => '',
				'desktop_wrap' => 'wrap',
				'tablet_wrap' => '',
				'mobile_wrap' => '',
				'desktop_align_vertical' => 'center',
				'tablet_align_vertical' => '',
				'mobile_align_vertical' => '',
				'desktop_align_horizontal' => 'start',
				'tablet_align_horizontal' => '',
				'mobile_align_horizontal' => '',
				'desktop_column_align_vertical' => 'start',
				'tablet_column_align_vertical' => '',
				'mobile_column_align_vertical' => '',
				'desktop_column_align_horizontal' => 'stretch',
				'tablet_column_align_horizontal' => '',
				'mobile_column_align_horizontal' => '',
			), $params);
		}

		// Flex options default
		$custom_css .= $shortcode . '.' . esc_attr($uniqid) . '{display: flex !important;height: 100%;}';

		// Flex options direction
		if (isset($params['desktop_' . $direction])) {
			switch ($params['desktop_' . $direction]) {
				case 'row':
					$result = 'flex-direction: row !important;';
					break;
				case 'row-reverse':
					$result = 'flex-direction: row-reverse !important;';
					break;
				case 'column':
					$result = 'flex-direction: column !important;align-items: stretch !important;';
					break;
				case 'column-reverse':
					$result = 'flex-direction: column-reverse !important;align-items: stretch !important;';
					break;
				default:
					$result = 'flex-direction: row !important;';
			}
			$custom_css .= $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}';
		}
		if (isset($params['tablet_' . $direction]) && !empty($params['tablet_' . $direction])) {
			switch ($params['tablet_' . $direction]) {
				case 'row':
					$result = 'flex-direction: row !important;';
					break;
				case 'row-reverse':
					$result = 'flex-direction: row-reverse !important;';
					break;
				case 'column':
					$result = 'flex-direction: column !important;align-items: stretch !important;';
					break;
				case 'column-reverse':
					$result = 'flex-direction: column-reverse !important;align-items: stretch !important;';
					break;
				default:
					$result = 'flex-direction: row !important;';
			}
			$custom_css .= '@media screen and (max-width: 1023px) {' . $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}}';
		} elseif(isset($params['desktop_' . $direction])) {
			$params['tablet_' . $direction] = $params['desktop_' . $direction];
		}
		if (isset($params['mobile_' . $direction]) && !empty($params['mobile_' . $direction])) {
			switch ($params['mobile_' . $direction]) {
				case 'row':
					$result = 'flex-direction: row !important;';
					break;
				case 'row-reverse':
					$result = 'flex-direction: row-reverse !important;';
					break;
				case 'column':
					$result = 'flex-direction: column !important;align-items: stretch !important;';
					break;
				case 'column-reverse':
					$result = 'flex-direction: column-reverse !important;align-items: stretch !important;';
					break;
				default:
					$result = 'flex-direction: row !important;';
			}
			$custom_css .= '@media screen and (max-width: 767px) {' . $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}}';
		} elseif(isset($params['desktop_' . $direction])) {
			$params['mobile_' . $direction] = $params['tablet_' . $direction];
		}

		// Flex options wrap
		if (isset($params['desktop_' . $wrap])) {
			switch ($params['desktop_' . $wrap]) {
				case 'nowrap':
					$result = 'flex-wrap: nowrap !important;';
					break;
				case 'wrap':
					$result = 'flex-wrap: wrap !important;';
					break;
				case 'wrap-reverse':
					$result = 'flex-wrap: wrap-reverse !important;';
					break;
				default:
					$result = 'flex-wrap: nowrap !important;';
			}
			$custom_css .= $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}';
		}
		if (isset($params['tablet_' . $wrap]) && !empty($params['tablet_' . $wrap])) {
			switch ($params['tablet_' . $wrap]) {
				case 'nowrap':
					$result = 'flex-wrap: nowrap !important;';
					break;
				case 'wrap':
					$result = 'flex-wrap: wrap !important;';
					break;
				case 'wrap-reverse':
					$result = 'flex-wrap: wrap-reverse !important;';
					break;
				default:
					$result = 'flex-wrap: nowrap !important;';
			}
			$custom_css .= '@media screen and (max-width: 1023px) {' . $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}}';
		}
		if (isset($params['mobile_' . $wrap]) && !empty($params['mobile_' . $wrap])) {
			switch ($params['mobile_' . $wrap]) {
				case 'nowrap':
					$result = 'flex-wrap: nowrap !important;';
					break;
				case 'wrap':
					$result = 'flex-wrap: wrap !important;';
					break;
				case 'wrap-reverse':
					$result = 'flex-wrap: wrap-reverse !important;';
					break;
				default:
					$result = 'flex-wrap: nowrap !important;';
			}
			$custom_css .= '@media screen and (max-width: 767px) {' . $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}}';
		}

		// Flex options vertical align
		if(isset($params['desktop_' . $direction]) && !empty($params['desktop_' . $direction]) && strpos($params['desktop_' . $direction], 'row') === 0) {
			if (isset($params['desktop_' . $vertical])) {
				switch ($params['desktop_' . $vertical]) {
					case 'start':
						$result = 'align-items: flex-start !important;';
						break;
					case 'center':
						$result = 'align-items: center !important;';
						break;
					case 'end':
						$result = 'align-items: flex-end !important;';
						break;
					case 'baseline':
						$result = 'align-items: baseline !important;';
						break;
					case 'stretch':
						$result = 'align-items: stretch !important;';
						break;
					default:
						$result = 'align-items: center !important;';
				}
				$custom_css .= $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}';
			}
		} elseif(isset($params['desktop_' . $direction]) && !empty($params['desktop_' . $direction]) && strpos($params['desktop_' . $direction], 'column') === 0) {
			if (isset($params['desktop_column_' . $vertical])) {
				switch ($params['desktop_column_' . $vertical]) {
					case 'start':
						$result = 'justify-content: flex-start !important;';
						break;
					case 'center':
						$result = 'justify-content: center !important;';
						break;
					case 'end':
						$result = 'justify-content: flex-end !important;';
						break;
					case 'between':
						$result = 'justify-content: space-between !important;';
						break;
					case 'around':
						$result = 'justify-content: space-around !important;';
						break;
					case 'evenly':
						$result = 'justify-content: space-evenly !important;';
						break;
					default:
						$result = 'justify-content: flex-start !important;';
				}
				$custom_css .= $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}';
			}
		}
		if(isset($params['tablet_' . $direction]) && !empty($params['tablet_' . $direction]) && strpos($params['tablet_' . $direction], 'row') === 0) {
			if (isset($params['tablet_' . $vertical]) && !empty($params['tablet_' . $vertical])) {
				switch ($params['tablet_' . $vertical]) {
					case 'start':
						$result = 'align-items: flex-start !important;';
						break;
					case 'center':
						$result = 'align-items: center !important;';
						break;
					case 'end':
						$result = 'align-items: flex-end !important;';
						break;
					case 'baseline':
						$result = 'align-items: baseline !important;';
						break;
					case 'stretch':
						$result = 'align-items: stretch !important;';
						break;
					default:
						$result = 'align-items: center !important;';
				}
				$custom_css .= '@media screen and (max-width: 1023px) {' . $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}}';
			}
		} elseif(isset($params['tablet_' . $direction]) && !empty($params['tablet_' . $direction]) && strpos($params['tablet_' . $direction], 'column') === 0) {
			if (isset($params['tablet_column_' . $vertical]) && !empty($params['tablet_column_' . $vertical])) {
				switch ($params['tablet_column_' . $vertical]) {
					case 'start':
						$result = 'justify-content: flex-start !important;';
						break;
					case 'center':
						$result = 'justify-content: center !important;';
						break;
					case 'end':
						$result = 'justify-content: flex-end !important;';
						break;
					case 'between':
						$result = 'justify-content: space-between !important;';
						break;
					case 'around':
						$result = 'justify-content: space-around !important;';
						break;
					case 'evenly':
						$result = 'justify-content: space-evenly !important;';
						break;
					default:
						$result = 'justify-content: flex-start !important;';
				}
				$custom_css .= '@media screen and (max-width: 1023px) {' . $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}}';
			}
		}
		if(isset($params['mobile_' . $direction]) && !empty($params['mobile_' . $direction]) && strpos($params['mobile_' . $direction], 'row') === 0) {
			if (isset($params['mobile_' . $vertical]) && !empty($params['mobile_' . $vertical])) {
				switch ($params['mobile_' . $vertical]) {
					case 'start':
						$result = 'align-items: flex-start !important;';
						break;
					case 'center':
						$result = 'align-items: center !important;';
						break;
					case 'end':
						$result = 'align-items: flex-end !important;';
						break;
					case 'baseline':
						$result = 'align-items: baseline !important;';
						break;
					case 'stretch':
						$result = 'align-items: stretch !important;';
						break;
					default:
						$result = 'align-items: center !important;';
				}
				$custom_css .= '@media screen and (max-width: 767px) {' . $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}}';
			}
		} elseif(isset($params['mobile_' . $direction]) && !empty($params['mobile_' . $direction]) && strpos($params['mobile_' . $direction], 'column') === 0) {
			if (isset($params['mobile_column_' . $vertical]) && !empty($params['mobile_column_' . $vertical])) {
				switch ($params['mobile_column_' . $vertical]) {
					case 'start':
						$result = 'justify-content: flex-start !important;';
						break;
					case 'center':
						$result = 'justify-content: center !important;';
						break;
					case 'end':
						$result = 'justify-content: flex-end !important;';
						break;
					case 'between':
						$result = 'justify-content: space-between !important;';
						break;
					case 'around':
						$result = 'justify-content: space-around !important;';
						break;
					case 'evenly':
						$result = 'justify-content: space-evenly !important;';
						break;
					default:
						$result = 'justify-content: flex-start !important;';
				}
				$custom_css .= '@media screen and (max-width: 767px) {' . $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}}';
			}
		}

		// Flex options horizontal align
		if(isset($params['desktop_' . $direction]) && !empty($params['desktop_' . $direction]) && strpos($params['desktop_' . $direction], 'row') === 0) {
			if (isset($params['desktop_' . $horizontal])) {
				switch ($params['desktop_' . $horizontal]) {
					case 'start':
						$result = 'justify-content: flex-start !important;';
						break;
					case 'center':
						$result = 'justify-content: center !important;';
						break;
					case 'end':
						$result = 'justify-content: flex-end !important;';
						break;
					case 'between':
						$result = 'justify-content: space-between !important;';
						break;
					case 'around':
						$result = 'justify-content: space-around !important;';
						break;
					case 'evenly':
						$result = 'justify-content: space-evenly !important;';
						break;
					default:
						$result = 'justify-content: flex-start !important;';
				}
				$custom_css .= $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}';
			}
		} elseif(isset($params['desktop_' . $direction]) && !empty($params['desktop_' . $direction]) && strpos($params['desktop_' . $direction], 'column') === 0) {
			if (isset($params['desktop_column_' . $horizontal])) {
				switch ($params['desktop_column_' . $horizontal]) {
					case 'start':
						$result = 'align-items: flex-start !important;';
						break;
					case 'center':
						$result = 'align-items: center !important;';
						break;
					case 'end':
						$result = 'align-items: flex-end !important;';
						break;
					case 'baseline':
						$result = 'align-items: baseline !important;';
						break;
					case 'stretch':
						$result = 'align-items: stretch !important;';
						break;
					default:
						$result = 'align-items: center !important;';
				}
				$custom_css .= $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}';
			}
		}
		if(isset($params['tablet_' . $direction]) && !empty($params['tablet_' . $direction]) && strpos($params['tablet_' . $direction], 'row') === 0) {
			if (isset($params['tablet_' . $horizontal]) && !empty($params['tablet_' . $horizontal])) {
				switch ($params['tablet_' . $horizontal]) {
					case 'start':
						$result = 'justify-content: flex-start !important;';
						break;
					case 'center':
						$result = 'justify-content: center !important;';
						break;
					case 'end':
						$result = 'justify-content: flex-end !important;';
						break;
					case 'between':
						$result = 'justify-content: space-between !important;';
						break;
					case 'around':
						$result = 'justify-content: space-around !important;';
						break;
					case 'evenly':
						$result = 'justify-content: space-evenly !important;';
						break;
					default:
						$result = 'justify-content: flex-start !important;';
				}
				$custom_css .= '@media screen and (max-width: 1023px) {' . $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}}';
			}
		} elseif(isset($params['tablet_' . $direction]) && !empty($params['tablet_' . $direction]) && strpos($params['tablet_' . $direction], 'column') === 0) {
			if (isset($params['tablet_column_' . $horizontal]) && !empty($params['tablet_column_' . $horizontal])) {
				switch ($params['tablet_column_' . $horizontal]) {
					case 'start':
						$result = 'align-items: flex-start !important;';
						break;
					case 'center':
						$result = 'align-items: center !important;';
						break;
					case 'end':
						$result = 'align-items: flex-end !important;';
						break;
					case 'baseline':
						$result = 'align-items: baseline !important;';
						break;
					case 'stretch':
						$result = 'align-items: stretch !important;';
						break;
					default:
						$result = 'align-items: center !important;';
				}
				$custom_css .= '@media screen and (max-width: 1023px) {' . $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}}';
			}
		}
		if(isset($params['mobile_' . $direction]) && !empty($params['mobile_' . $direction]) && strpos($params['mobile_' . $direction], 'row') === 0) {
			if (isset($params['mobile_' . $horizontal]) && !empty($params['mobile_' . $horizontal])) {
				switch ($params['mobile_' . $horizontal]) {
					case 'start':
						$result = 'justify-content: flex-start !important;';
						break;
					case 'center':
						$result = 'justify-content: center !important;';
						break;
					case 'end':
						$result = 'justify-content: flex-end !important;';
						break;
					case 'between':
						$result = 'justify-content: space-between !important;';
						break;
					case 'around':
						$result = 'justify-content: space-around !important;';
						break;
					case 'evenly':
						$result = 'justify-content: space-evenly !important;';
						break;
					default:
						$result = 'justify-content: flex-start !important;';
				}
				$custom_css .= '@media screen and (max-width: 767px) {' . $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}}';
			}
		} elseif(isset($params['mobile_' . $direction]) && !empty($params['mobile_' . $direction]) && strpos($params['mobile_' . $direction], 'column') === 0) {
			if (isset($params['mobile_column_' . $horizontal]) && !empty($params['mobile_' . $horizontal])) {
				switch ($params['mobile_column_' . $horizontal]) {
					case 'start':
						$result = 'align-items: flex-start !important;';
						break;
					case 'center':
						$result = 'align-items: center !important;';
						break;
					case 'end':
						$result = 'align-items: flex-end !important;';
						break;
					case 'baseline':
						$result = 'align-items: baseline !important;';
						break;
					case 'stretch':
						$result = 'align-items: stretch !important;';
						break;
					default:
						$result = 'align-items: center !important;';
				}
				$custom_css .= '@media screen and (max-width: 767px) {' . $shortcode . '.' . esc_attr($uniqid) . '{' . $result . '}}';
			}
		}

		return $custom_css;
	}
}

if(!function_exists('thegem_templates_element_design_options')) {
	function thegem_templates_element_design_options($uniqid, $el_class, $params) {
		$custom_css = '';
		$shortcode = $el_class;
		$disable = 'disable';
		$absolute = 'absolute';
		$order = 'order';
		$horizontal = 'horizontal';
		$vertical = 'vertical';
		$gaps = array('padding', 'margin');
		$gaps_dir = array('top', 'bottom', 'left', 'right');

		$editor_suffix = '';
		if(function_exists('vc_is_page_editable') && vc_is_page_editable()) {
			$editor_suffix = '-editor';
			$shortcode = '';
		}

		// Design options visibility
		if (isset($params['desktop_' . $disable]) && !empty($params['desktop_' . $disable])) {
			$custom_css .= $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{display: none!important;}';
		}
		if (isset($params['tablet_' . $disable]) && !empty($params['tablet_' . $disable])) {
			$custom_css .= '@media screen and (max-width: 1023px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{display: none!important;}}';
		} else {
			$custom_css .= '@media screen and (max-width: 1023px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{display: block!important;}}';
		}
		if (isset($params['mobile_' . $disable]) && !empty($params['mobile_' . $disable])) {
			$custom_css .= '@media screen and (max-width: 767px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{display: none!important;}}';
		} else {
			$custom_css .= '@media screen and (max-width: 767px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{display: block!important;}}';
		}

		// Design options absolute
		$is_desktop_absolute = $is_tablet_absolute = $is_mobile_absolute = false;
		if (isset($params['desktop_' . $absolute]) && !empty($params['desktop_' . $absolute])) {
			$is_desktop_absolute = true;
			$custom_css .= $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{position: absolute !important;}';
		}
		if (isset($params['tablet_' . $absolute]) && !empty($params['tablet_' . $absolute])) {
			$is_tablet_absolute = true;
			$custom_css .= '@media screen and (max-width: 1023px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{position: absolute !important;}}';
		} else {
			$is_tablet_absolute = false;
			$custom_css .= '@media screen and (max-width: 1023px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{position: relative !important;}}';
		}
		if (isset($params['mobile_' . $absolute]) && !empty($params['mobile_' . $absolute])) {
			$is_mobile_absolute = true;
			$custom_css .= '@media screen and (max-width: 767px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{position: absolute !important;}}';
		} else {
			$is_mobile_absolute = false;
			$custom_css .= '@media screen and (max-width: 767px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{position: relative !important;}}';
		}

		// Design options order
		if (isset($params['desktop_' . $order]) && !empty($params['desktop_' . $order])) {
			$result = str_replace(' ', '', $params['desktop_' . $order]);
			$custom_css .= $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{' . $order . ': ' . $result . ' !important;}';
		}
		if (isset($params['tablet_' . $order]) && !empty($params['tablet_' . $order])) {
			$result = str_replace(' ', '', $params['tablet_' . $order]);
			$custom_css .= '@media screen and (max-width: 1023px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{' . $order . ': ' . $result . ' !important;}}';
		}
		if (isset($params['mobile_' . $order]) && !empty($params['mobile_' . $order])) {
			$result = str_replace(' ', '', $params['mobile_' . $order]);
			$custom_css .= '@media screen and (max-width: 767px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{' . $order . ': ' . $result . ' !important;}}';
		}

		// Design options horizontal align
		if (isset($params['desktop_' . $horizontal])) {
			switch ($params['desktop_' . $horizontal]) {
				case 'start':
					$result = $is_desktop_absolute ? 'left: 0; transform: none; right: auto;' : 'margin: 0; margin-right: auto; left:auto; right:auto; transform: none;';
					break;
				case 'center':
					$result = $is_desktop_absolute ? 'left: 50%; transform: translateX(-50%); right: auto;' : 'margin: 0 auto; left:auto; right:auto; transform: none;';
					break;
				case 'end':
					$result = $is_desktop_absolute ? 'right: 0; transform: none; left: auto;' : 'margin: 0; margin-left: auto; left:auto; right:auto; transform: none;';
					break;
				default: $result = 'margin: 0;';
			}
			$custom_css .= $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{' . $result . '}';
		}
		if (isset($params['tablet_' . $horizontal]) && !empty($params['tablet_' . $horizontal])) {
			switch ($params['tablet_' . $horizontal]) {
				case 'unset':
					$result = $is_tablet_absolute ? 'left:auto; right:auto; transform: none;' : 'margin: 0; left:auto; right:auto; transform: none;';
					break;
				case 'start':
					$result = $is_tablet_absolute ? 'left: 0; transform: none; right: auto;' : 'margin: 0; margin-right: auto; left:auto; right:auto; transform: none;';
					break;
				case 'center':
					$result = $is_tablet_absolute ? 'left: 50%; transform: translateX(-50%); right: auto;' : 'margin: 0 auto; left:auto; right:auto; transform: none;';
					break;
				case 'end':
					$result = $is_tablet_absolute ? 'right: 0; transform: none; left: auto;' : 'margin: 0; margin-left: auto; left:auto; right:auto; transform: none;';
					break;
				default: $result = 'margin: 0;';
			}
			$custom_css .= '@media screen and (max-width: 1023px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{' . $result . '}}';
		}
		if (isset($params['mobile_' . $horizontal]) && !empty($params['mobile_' . $horizontal])) {
			switch ($params['mobile_' . $horizontal]) {
				case 'unset':
					$result = $is_mobile_absolute ? 'left:auto; right:auto; transform: none;' : 'margin: 0; left:auto; right:auto; transform: none;';
					break;
				case 'start':
					$result = $is_mobile_absolute ? 'left: 0; transform: none; right: auto;' : 'margin: 0; margin-right: auto; left:auto; right:auto; transform: none;';
					break;
				case 'center':
					$result = $is_mobile_absolute ? 'left: 50%; transform: translateX(-50%); right: auto;' : 'margin: 0 auto; left:auto; right:auto; transform: none;';
					break;
				case 'end':
					$result = $is_mobile_absolute ? 'right: 0; transform: none; left: auto;' : 'margin: 0; margin-left: auto; left:auto; right:auto; transform: none;';
					break;
				default: $result = 'margin: 0;';
			}
			$custom_css .= '@media screen and (max-width: 767px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{' . $result . '}}';
		}

		// Design options vertical align
		$default_align = 'flex-start';
		if((isset($GLOBALS['thegem_template_type']) && $GLOBALS['thegem_template_type'] == 'header') || thegem_is_template_post('header')) {
			$default_align = 'center';
		}
		if((isset($GLOBALS['thegem_template_type']) && $GLOBALS['thegem_template_type'] == 'single-product') || thegem_is_template_post('single-product')) {
			$default_align = 'auto';
		}
		/*if((isset($GLOBALS['thegem_template_type']) && $GLOBALS['thegem_template_type'] == 'product-archive') || thegem_is_template_post('product-archive')) {
			$default_align = 'auto';
		}*/
		if (isset($params['desktop_' . $vertical])) {
			switch ($params['desktop_' . $vertical]) {
				case 'start':
					$result = $is_desktop_absolute ? 'top: 0; transform: none; bottom: auto;' : 'align-self: flex-start; top:auto; bottom:auto; transform: none;';
					break;
				case 'center':
					$result = $is_desktop_absolute ? 'top: 50%; transform: translateY(-50%); bottom: auto;' : 'align-self: center; top:auto; bottom:auto; transform: none;';
					break;
				case 'end':
					$result = $is_desktop_absolute ? 'bottom: 0; transform: none; top: auto;' : 'align-self: flex-end; top:auto; bottom:auto; transform: none;';
					break;
				default:
					$result = 'align-self: '.$default_align.';';
			}
			$custom_css .= $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{' . $result . '}';
		}
		if (isset($params['tablet_' . $vertical]) && !empty($params['tablet_' . $vertical])) {
			switch ($params['tablet_' . $vertical]) {
				case 'unset':
					$result = $is_tablet_absolute ? 'top:auto; bottom:auto; transform: none;' : 'align-self: center; top:auto; bottom:auto; transform: none;';
					break;
				case 'start':
					$result = $is_tablet_absolute ? 'top: 0; transform: none; bottom: auto;' : 'align-self: flex-start; top:auto; bottom:auto; transform: none;';
					break;
				case 'center':
					$result = $is_tablet_absolute ? 'top: 50%; transform: translateY(-50%); bottom: auto;' : 'align-self: center; top:auto; bottom:auto; transform: none;';
					break;
				case 'end':
					$result = $is_tablet_absolute ? 'bottom: 0; transform: none; top: auto;' : 'align-self: flex-end; top:auto; bottom:auto; transform: none;';
					break;
				default:
					$result = 'align-self: '.$default_align.';';
			}
			$custom_css .= '@media screen and (max-width: 1023px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{' . $result . '}}';
		}
		if (isset($params['mobile_' . $vertical]) && !empty($params['mobile_' . $vertical])) {
			switch ($params['mobile_' . $vertical]) {
				case 'unset':
					$result = $is_mobile_absolute ? 'top:auto; bottom:auto; transform: none;' : 'align-self: center; top:auto; bottom:auto; transform: none;';
					break;
				case 'start':
					$result = $is_mobile_absolute ? 'top: 0; transform: none; bottom: auto;' : 'align-self: flex-start; top:auto; bottom:auto; transform: none;';
					break;
				case 'center':
					$result = $is_mobile_absolute ? 'top: 50%; transform: translateY(-50%); bottom: auto;' : 'align-self: center; top:auto; bottom:auto; transform: none;';
					break;
				case 'end':
					$result = $is_mobile_absolute ? 'bottom: 0; transform: none; top: auto;' : 'align-self: flex-end; top:auto; bottom:auto; transform: none;';
					break;
				default:
					$result = 'align-self: '.$default_align.';';
			}
			$custom_css .= '@media screen and (max-width: 767px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{' . $result . '}}';
		}

		// Design options paddings/margins
		foreach ($gaps as $name) {
			foreach ($gaps_dir as $dir) {
				$unit = 'px';
				$result = $last_result = '';

				if (isset($params['desktop_' . $name . '_' . $dir]) && !empty($params['desktop_' . $name . '_' . $dir]) || strcmp($params['desktop_' . $name . '_' . $dir], '0') === 0) {
					$result = str_replace(' ', '', $params['desktop_' . $name . '_' . $dir]);
					$last_result = substr($result, -1);
					if ($last_result == '%') {
						$result = str_replace('%', '', $result);
						$unit = $last_result;
					}
					$custom_css .= $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{' . $name . '-' . $dir . ': ' . $result . $unit . ' !important;}';
				}
				if (isset($params['tablet_' . $name . '_' . $dir]) && !empty($params['tablet_' . $name . '_' . $dir]) || strcmp($params['tablet_' . $name . '_' . $dir], '0') === 0) {
					$result = str_replace(' ', '', $params['tablet_' . $name . '_' . $dir]);
					$last_result = substr($result, -1);
					if ($last_result == '%') {
						$result = str_replace('%', '', $result);
						$unit = $last_result;
					}
					$custom_css .= '@media screen and (max-width: 1023px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{' . $name . '-' . $dir . ': ' . $result . $unit . ' !important;}}';
				}
				if (isset($params['mobile_' . $name . '_' . $dir]) && !empty($params['mobile_' . $name . '_' . $dir]) || strcmp($params['mobile_' . $name . '_' . $dir], '0') === 0) {
					$result = str_replace(' ', '', $params['mobile_' . $name . '_' . $dir]);
					$last_result = substr($result, -1);
					if ($last_result == '%') {
						$result = str_replace('%', '', $result);
						$unit = $last_result;
					}
					$custom_css .= '@media screen and (max-width: 767px) {' . $shortcode . '.' . esc_attr($uniqid.$editor_suffix) . '{' . $name . '-' . $dir . ': ' . $result . $unit . ' !important;}}';
				}
			}
		}

		return $custom_css;
	}
}

if(!function_exists('thegem_font_parse')) {
	function thegem_font_parse($custom_fonts = false) {
		$parsed_param =  array();
		$parsed_array = '';

			if (class_exists('Vc_Google_Fonts')) {

				$google_fonts_obj  = new Vc_Google_Fonts();
				$google_fonts_data = strlen( $custom_fonts ) > 0 ? $google_fonts_obj->_vc_google_fonts_parse_attributes( array(), $custom_fonts ) : '';

				if($google_fonts_data != '') {
					$google_fonts_family = explode( ':', $google_fonts_data['values']['font_family'] );
					$parsed_array .= 'font-family:' . $google_fonts_family[0] . '; ';
					$google_fonts_styles = explode( ':', $google_fonts_data['values']['font_style'] );
					if(isset($google_fonts_styles[1]) && !empty($google_fonts_styles[1])) {
						$parsed_array .= 'font-weight:' . $google_fonts_styles[1] . '; ';
					}
					if(isset($google_fonts_styles[2]) && !empty($google_fonts_styles[2])) {
						$parsed_array .= 'font-style:' . $google_fonts_styles[2] . '; ';
					}

					$settings = get_option( 'wpb_js_google_fonts_subsets' );
					if ( is_array( $settings ) && ! empty( $settings ) ) {
						$subsets = '&subset=' . implode( ',', $settings );
					} else {
						$subsets = '';
					}

					if ( isset( $google_fonts_data['values']['font_family'] ) && function_exists('vc_build_safe_css_class') ) {
						wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
					}
				}
			}

			foreach ( $parsed_param as $key => $value ) {

				if ( strlen( $value ) > 0 ) {
					if ( 'font_style_italic' === $key ) {
						$parsed_array .= 'font-style: italic; ';
					} elseif ( 'font_style_bold' === $key ) {
						$parsed_array .= 'font-weight: bold; ';
					} elseif ( 'font_style_underline' === $key ) {
						$parsed_array .= 'text-decoration: underline; ';
					} elseif('font_family' === $key){
						$parsed_array .= 'font-family: '.$value.'; ';
					} elseif ( 'color' === $key ) {
						$value = str_replace( '%23', '#', $value );
						$value = str_replace( '%2C', ',', $value );
						$parsed_array .= $key . ': ' . $value . ' !important; ';
					} else {
						$parsed_array .= str_replace( '_', '-', $key ) . ': ' . $value . 'px; ';
					}
				}
			}

		return $parsed_array;
	}
}

add_filter('wp_lazy_loading_enabled', function() { return false; } );

/*if(!function_exists('thegem_check_array_value')) {
function thegem_check_array_value($array = array(), $value = '', $default = '') {
	if(in_array($value, $array)) {
		return $value;
	}
	return $default;
}
}*/

/* FONTS MANAGER */

function thegem_fonts_allowed_mime_types( $existing_mimes = array() ) {
	$existing_mimes['ttf'] = 'application/x-font-ttf';
	$existing_mimes['eot'] = 'application/vnd.ms-fontobject';
	$existing_mimes['woff'] = 'application/x-font-woff';
	$existing_mimes['svg'] = 'image/svg+xml';
	$existing_mimes['json'] = 'application/json';
	return $existing_mimes;
}
add_filter('upload_mimes', 'thegem_fonts_allowed_mime_types');

function thegem_modify_post_mime_types( $post_mime_types ) {
	$post_mime_types['application/x-font-ttf'] = array(esc_html__('TTF Font', 'thegem'), esc_html__( 'Manage TTFs', 'thegem' ), _n_noop( 'TTF <span class="count">(%s)</span>', 'TTFs <span class="count">(%s)</span>', 'thegem' ) );
	$post_mime_types['application/vnd.ms-fontobject'] = array(esc_html__('EOT Font', 'thegem'), esc_html__( 'Manage EOTs', 'thegem' ), _n_noop( 'EOT <span class="count">(%s)</span>', 'EOTs <span class="count">(%s)</span>', 'thegem' ) );
	$post_mime_types['application/x-font-woff'] = array(esc_html__('WOFF Font', 'thegem'), esc_html__( 'Manage WOFFs', 'thegem' ), _n_noop( 'WOFF <span class="count">(%s)</span>', 'WOFFs <span class="count">(%s)</span>', 'thegem' ) );
	$post_mime_types['image/svg+xml'] = array(esc_html__('SVG Font', 'thegem'), esc_html__( 'Manage SVGs', 'thegem' ), _n_noop( 'SVG <span class="count">(%s)</span>', 'SVGs <span class="count">(%s)</span>', 'thegem' ) );
	return $post_mime_types;
}
add_filter('post_mime_types', 'thegem_modify_post_mime_types');

/* SCRTIPTs & STYLES */

function thegem_elements_scripts() {
	wp_register_style('thegem-portfolio', THEGEM_THEME_URI . '/css/thegem-portfolio.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-portfolio-filters-list', THEGEM_THEME_URI . '/css/thegem-portfolio-filters-list.css', array('thegem-portfolio'), THEGEM_THEME_VERSION);
	wp_register_style('thegem-portfolio-slider', THEGEM_THEME_URI . '/css/thegem-portfolio-slider.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-portfolio-products', THEGEM_THEME_URI . '/css/thegem-portfolio-products.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-portfolio-carousel', THEGEM_THEME_URI . '/css/thegem-portfolio-carousel.css', array( 'thegem-portfolio', 'owl'), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid', THEGEM_THEME_URI . '/css/thegem-news-grid.css', array( 'thegem-portfolio'), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-hovers', THEGEM_THEME_URI . '/css/thegem-news-grid-hovers.css', array(), THEGEM_THEME_VERSION);

	wp_register_style('thegem-news-grid-version-new-hovers-default', THEGEM_THEME_URI . '/css/thegem-news-grid-version-new/default.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-version-new-hovers-zooming-blur', THEGEM_THEME_URI . '/css/thegem-news-grid-version-new/zooming-blur.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-version-new-hovers-horizontal-sliding', THEGEM_THEME_URI . '/css/thegem-news-grid-version-new/horizontal-sliding.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-version-new-hovers-vertical-sliding', THEGEM_THEME_URI . '/css/thegem-news-grid-version-new/vertical-sliding.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-version-new-hovers-gradient', THEGEM_THEME_URI . '/css/thegem-news-grid-version-new/gradient.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-version-new-hovers-circular', THEGEM_THEME_URI . '/css/thegem-news-grid-version-new/circular.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-version-new-hovers-zoom-overlay', THEGEM_THEME_URI . '/css/thegem-news-grid-version-new/zoom-overlay.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-version-new-hovers-disabled', THEGEM_THEME_URI . '/css/thegem-news-grid-version-new/disabled.css', array(), THEGEM_THEME_VERSION);

	wp_register_style('thegem-news-grid-version-default-hovers-default', THEGEM_THEME_URI . '/css/thegem-news-grid-version-default/default.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-version-default-hovers-zooming-blur', THEGEM_THEME_URI . '/css/thegem-news-grid-version-default/zooming-blur.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-version-default-hovers-horizontal-sliding', THEGEM_THEME_URI . '/css/thegem-news-grid-version-default/horizontal-sliding.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-version-default-hovers-vertical-sliding', THEGEM_THEME_URI . '/css/thegem-news-grid-version-default/vertical-sliding.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-version-default-hovers-gradient', THEGEM_THEME_URI . '/css/thegem-news-grid-version-default/gradient.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-version-default-hovers-circular', THEGEM_THEME_URI . '/css/thegem-news-grid-version-default/circular.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-version-default-hovers-zoom-overlay', THEGEM_THEME_URI . '/css/thegem-news-grid-version-default/zoom-overlay.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-news-grid-version-default-hovers-disabled', THEGEM_THEME_URI . '/css/thegem-news-grid-version-default/disabled.css', array(), THEGEM_THEME_VERSION);

	wp_register_style('thegem-news-grid-version-list-hovers-zoom-overlay', THEGEM_THEME_URI . '/css/thegem-news-grid-version-list/zoom-overlay.css', array(), THEGEM_THEME_VERSION);

	wp_register_style('thegem-gallery', THEGEM_THEME_URI . '/css/gallery.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-pricing-tables', THEGEM_THEME_URI . '/css/thegem-pricing-tables.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-testimonials', THEGEM_THEME_URI . '/css/thegem-testimonials.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('thegem-featured-posts-slider', THEGEM_THEME_URI . '/css/thegem-featured-posts-slider.css', array(), THEGEM_THEME_VERSION);
	wp_register_style('icons-arrows', THEGEM_THEME_URI . '/css/icons-arrows.css', array(), THEGEM_THEME_VERSION);

	wp_register_style('thegem-menu-custom', THEGEM_THEME_URI . '/css/thegem-menu-custom.css', array(), THEGEM_THEME_VERSION);

	wp_register_script('thegem-diagram-line', THEGEM_THEME_URI . '/js/diagram_line.js', array('jquery', 'jquery-easing'), THEGEM_THEME_VERSION, true);
	wp_register_script('raphael', THEGEM_THEME_URI . '/js/raphael.js', array('jquery'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-diagram-circle', THEGEM_THEME_URI . '/js/diagram_circle.js', array('jquery', 'raphael'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-news-carousel', THEGEM_THEME_URI . '/js/news-carousel.js', array('jquery', 'jquery-carouFredSel'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-clients-grid-carousel', THEGEM_THEME_URI . '/js/clients-grid-carousel.js', array('jquery', 'jquery-carouFredSel'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-portfolio-grid-carousel', THEGEM_THEME_URI . '/js/portfolio-grid-carousel.js', array('jquery', 'jquery-carouFredSel'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-testimonials-carousel', THEGEM_THEME_URI . '/js/testimonials-carousel.js', array('jquery', 'jquery-carouFredSel'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-portfolio-grid-extended', THEGEM_THEME_URI . '/js/thegem-portfolio-grid-extended.js', array('jquery'), THEGEM_THEME_VERSION, true);
	wp_register_script( 'thegem-portfolio-grid-extended-inline', '', [], '', true );
	wp_register_script('thegem-portfolio-carousel', THEGEM_THEME_URI . '/js/thegem-portfolio-carousel.js', array('thegem-portfolio-grid-extended', 'owl'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-widgets', THEGEM_THEME_URI . '/js/widgets.js', array('jquery', 'jquery-carouFredSel'), THEGEM_THEME_VERSION, true);
	wp_register_script('jquery-restable', THEGEM_THEME_URI . '/js/jquery.restable.js', array('jquery'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-quickfinders-effects', THEGEM_THEME_URI . '/js/quickfinders-effects.js', array('jquery'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-parallax-vertical', THEGEM_THEME_URI . '/js/jquery.parallaxVertical.js', array('jquery'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-parallax-horizontal', THEGEM_THEME_URI . '/js/jquery.parallaxHorizontal.js', array('jquery'), THEGEM_THEME_VERSION, true);
	wp_register_style('nivo-slider', THEGEM_THEME_URI . '/css/nivo-slider.css', array(), THEGEM_THEME_VERSION);
	wp_register_script('jquery-nivoslider', THEGEM_THEME_URI . '/js/jquery.nivo.slider.pack.js', array('jquery'), THEGEM_THEME_VERSION);
	wp_register_script('thegem-nivoslider-init-script', THEGEM_THEME_URI . '/js/nivoslider-init.js', array('jquery', 'jquery-nivoslider'), THEGEM_THEME_VERSION);
	wp_localize_script('thegem-nivoslider-init-script', 'thegem_nivoslider_options', array(
		'effect' => thegem_get_option('slider_effect') ? thegem_get_option('slider_effect') : 'random',
		'slices' => thegem_get_option('slider_slices') ? thegem_get_option('slider_slices') : 15,
		'boxCols' => thegem_get_option('slider_boxCols') ? thegem_get_option('slider_boxCols') : 8,
		'boxRows' => thegem_get_option('slider_boxRows') ? thegem_get_option('slider_boxRows') : 4,
		'animSpeed' => thegem_get_option('slider_animSpeed') ? thegem_get_option('slider_animSpeed')*100 : 500,
		'pauseTime' => thegem_get_option('slider_pauseTime') ? thegem_get_option('slider_pauseTime')*1000 : 3000,
		'directionNav' => thegem_get_option('slider_directionNav') ? true : false,
		'controlNav' => thegem_get_option('slider_controlNav') ? true : false,
	));
	wp_register_script('thegem-video', THEGEM_THEME_URI . '/js/thegem-video.js', array(), THEGEM_THEME_VERSION, true);

	wp_register_script('thegem-portfolio', THEGEM_THEME_URI . '/js/portfolio.js', array('jquery', 'thegem-scroll-monitor'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-juraSlider', THEGEM_THEME_URI . '/js/jquery.juraSlider.js', array('jquery', 'thegem-portfolio'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-countdown', THEGEM_THEME_URI . '/js/thegem-countdown.js', array( 'jquery', 'raphael', 'odometr' ), THEGEM_THEME_VERSION, true );
	wp_register_style('thegem-countdown', THEGEM_THEME_URI . '/css/thegem-countdown.css', array(), THEGEM_THEME_VERSION);
	wp_register_script('jquery-waypoints', THEGEM_THEME_URI . '/js/jquery.waypoints.js', array('jquery'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-stickyColumn', THEGEM_THEME_URI . '/js/thegem-stickyColumn.js', array('jquery', 'jquery-waypoints'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-team-hover', THEGEM_THEME_URI . '/js/thegem-team-hover.js', array('jquery'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-counters-effects', THEGEM_THEME_URI . '/js/counters-effects.js', array('jquery'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-counter', THEGEM_THEME_URI . '/js/thegem-counters.js', array( 'odometr', 'thegem-counters-effects', 'jquery' ), THEGEM_THEME_VERSION, true );
	wp_register_script('thegem-featured-posts-slider', THEGEM_THEME_URI . '/js/thegem-featured-posts-slider.js', array('jquery','jquery-carouFredSel'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-rellax', THEGEM_THEME_URI . '/js/rellax.min.js', array('jquery'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-interactions', THEGEM_THEME_URI . '/js/interactions.js', array('jquery', 'thegem-rellax'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-isotope-metro', THEGEM_THEME_URI . '/js/isotope_layout_metro.js', array('isotope-js'), THEGEM_THEME_VERSION, true);
	wp_register_script('thegem-isotope-masonry-custom', THEGEM_THEME_URI . '/js/isotope-masonry-custom.js', array('isotope-js'), THEGEM_THEME_VERSION, true);
	wp_register_style('thegem-infotext', THEGEM_THEME_URI . '/css/infotext.css', array(), THEGEM_THEME_VERSION);
	$gm_api_key = thegem_get_option('google_map_api_key');
	wp_register_script('thegem-googleapis-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $gm_api_key . '&callback=Function.prototype');
	wp_register_script('thegem-acf-google-maps', THEGEM_THEME_URI . '/js/acf-google-maps.js', array('jquery', 'thegem-googleapis-maps'));

	if (is_singular() || (function_exists('thegem_blog_archive_template') && thegem_blog_archive_template())) {
		$post_id = function_exists('thegem_blog_archive_template') && thegem_blog_archive_template() ? thegem_blog_archive_template() : get_the_ID();
		$post_id = function_exists('thegem_single_post_template') && in_array(get_post_type(), array_merge(array('post', 'thegem_news'), thegem_get_available_po_custom_post_types()), true) && thegem_single_post_template() ? thegem_single_post_template() : $post_id;
		$content = get_the_content(null, false, $post_id);
		$inlineFiltersScript = false;
		if (has_shortcode($content, 'gem_portfolio')) {
			wp_enqueue_style('thegem-portfolio');

			preg_match_all('/portfolio_with_filter="1"/', $content, $showFilter);
			preg_match_all('/portfolio_sorting="1"/', $content, $showSorting);

			if ($showFilter || $showSorting) {
				wp_enqueue_style('thegem-portfolio-filters-list');
				$inlineFiltersScript = true;
			}
		}
		if (has_shortcode($content, 'gem_news_grid')) {
			wp_enqueue_style('thegem-news-grid');

			preg_match_all('/news_grid_with_filter="1"/', $content, $showFilter);
			preg_match_all('/news_grid_sorting="1"/', $content, $showSorting);

			if ($showFilter || $showSorting) {
				wp_enqueue_style('thegem-portfolio-filters-list');
				$inlineFiltersScript = true;
			}
		}
		if (has_shortcode($content, 'gem_extended_filter')) {
			wp_enqueue_style('thegem-portfolio-filters-list');
		}
		if (has_shortcode($content, 'gem_gallery')) {
			wp_enqueue_style('thegem-gallery');
			preg_match_all('/with_filter="1"/', $content, $withFilter);
			if ($withFilter) {
				wp_enqueue_style('thegem-portfolio');
				wp_enqueue_style('thegem-portfolio-filters-list');
			}
		}
		if (has_shortcode($content, 'gem_custom_menu')) {
			wp_enqueue_style('thegem-menu-custom');
		}
		if (has_shortcode($content, 'gem_infotext')) {
			wp_enqueue_style('thegem-infotext');
		}
	}
	if(function_exists('thegem_blog_archive_template') && $template_id = thegem_blog_archive_template()) {
		if (has_shortcode(get_the_content(null, false, $template_id), 'gem_custom_menu')) {
			wp_enqueue_style('thegem-menu-custom');
		}
		if (has_shortcode(get_the_content(null, false, $template_id), 'gem_news_grid')) {
			wp_enqueue_style('thegem-news-grid');
		}
	}
	if(function_exists('thegem_single_post_template') && in_array(get_post_type(), array_merge(array('post', 'thegem_news'), thegem_get_available_po_custom_post_types()), true) && $thegem_post_template_id = thegem_single_post_template()) {
		if (has_shortcode(get_the_content(null, false, $thegem_post_template_id), 'gem_custom_menu')) {
			wp_enqueue_style('thegem-menu-custom');
		}
		if (has_shortcode(get_the_content(null, false, $thegem_post_template_id), 'gem_news_grid')) {
			wp_enqueue_style('thegem-news-grid');
		}
	}
}
add_action('wp_enqueue_scripts', 'thegem_elements_scripts', 6);

function thegem_nonce_life() {
	return 31536000;
}
add_filter('nonce_life', 'thegem_nonce_life');

function thegem_fix_pw_init() {
	if(defined('PAGE_WIDGET_VERSION')) {
		remove_action('admin_print_scripts', 'pw_print_scripts');
		add_action('admin_print_scripts', 'thegem_pw_print_scripts');
	}
}
add_filter('init', 'thegem_fix_pw_init');

function thegem_pw_print_scripts() {

	global $pagenow, $typenow;

	$plugins_url = plugins_url();

	if (function_exists('pw_backend_check_allow_continue_process') && pw_backend_check_allow_continue_process()) {

		do_action( 'admin_print_scripts-widgets.php' );

		/* Plugin support */

		// Image widget support
		if (is_plugin_active('image-widget/image-widget.php')) {
			wp_enqueue_script('tribe-image-widget', $plugins_url . '/image-widget/resources/js/image-widget.js', array('jquery', 'media-upload', 'media-views'), false, true);
			wp_localize_script( 'tribe-image-widget', 'TribeImageWidget', array(
			'frame_title' => __( 'Select an Image', 'image_widget' ),
			'button_title' => __( 'Insert Into Widget', 'image_widget' ),
			) );
		}

		// Simple Link List Widget plugin support/
		if (is_plugin_active('simple-link-list-widget/simple-link-list-widget.php')) {
			wp_enqueue_script( 'sllw-sort-js', $plugins_url .'/simple-link-list-widget/js/sllw-sort.js');
		}

		// Easy releated posts and Simple social icons support.
		if (
			is_plugin_active('easy-related-posts/easy_related_posts.php')
			||
			is_plugin_active('simple-social-icons/simple-social-icons.php')
		) {
			wp_enqueue_script( 'wp-color-picker');
		}


		wp_enqueue_script('pw-widgets', $plugins_url . '/wp-page-widget/assets/js/page-widgets.js', array('jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable'), rand(), true);


		/*
		* Add pwTextWidgets extend from wp.textWidgets
		* Add pwMediaWidgets extend from wp.mediaWidgets
		*/
		if( version_compare( get_bloginfo('version'), '4.7.9', '>' ) ) {
			wp_enqueue_script('pw-extend-text-widgets', $plugins_url . '/wp-page-widget/assets/js/pw-text-widgets-extend-wp-text-widgets.js', array('jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable'), PAGE_WIDGET_VERSION, true);
			wp_enqueue_script('pw-extend-media-widgets', $plugins_url . '/wp-page-widget/assets/js/pw-media-widgets-extend-wp-media-widgets.js', array('jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable'), PAGE_WIDGET_VERSION, true);
		}

		/*
		* Add pwCustomHTML extend from wp.customHtmlWidgets
		*/
		if( version_compare( get_bloginfo('version'), '4.8.5', '>' ) ) {
			wp_enqueue_script('pw-extend-custom-html', $plugins_url . '/wp-page-widget/assets/js/pw-custom-html-extend-wp-custom-html.js', array('jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable'), PAGE_WIDGET_VERSION, true);

			$settings = wp_enqueue_code_editor( array(
				'type' => 'text/html',
				'codemirror' => array(
					'indentUnit' => 2,
					'tabSize' => 2,
				),
			));

			if ( empty( $settings ) ) {
				$settings = array(
					'disabled' => true,
				);
			}
			wp_add_inline_script( 'pw-extend-custom-html', sprintf( 'pwCustomHTML.init( %s );', wp_json_encode( $settings ) ), 'after' );
		}

		wp_localize_script( 'pw-widgets', 'wp_page_widgets', array(
			'remove_inactive_widgets_text'  => __('Press the following button will remove all of these inactive widgets', 'wp-page-widgets'),
			'remove_inactive_widgets' => __( 'Remove inactive widgets', 'wp-page-widgets' ),
		) );
	}
}

function thegem_info_message_notice() {
	if ( !current_user_can('update_themes' ) )
		return false;

	$message_data = get_option('thegem_info_message', array());
	$notice_class = '';
	$notice_html = '';
	$notice_until = 0;
	if(is_array($message_data) and !empty($message_data['last_check'])) {
		$last_check = intval($message_data['last_check']);
	} else {
		$last_check = 0;
	}

	if((time()-$last_check)/3600 > 24) {
		$last_check = time();
		$response = wp_remote_get('http://democontent.codex-themes.com/plugins/thegem/theme/message.json', array('timeout' => 5));
		if ( is_wp_error( $response ) ) {
			update_option('thegem_info_message', array('last_check' => $last_check));
			return false;
		}
		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body, 1);
		if ( ! isset( $data['class'] ) ) {
			update_option('thegem_info_message', array('last_check' => $last_check));
			return false;
		}
		$notice_class = $data['class'];
		$notice_html = isset($data['html']) ? $data['html'] : '';
		$notice_until = isset($data['until']) ? intval($data['until']) : $last_check+24*60*60;
	} else {
		if(!empty($message_data['class']) && !empty($message_data['html'])) {
			$notice_class = $message_data['class'];
			$notice_html = $message_data['html'];
			$notice_until = isset($message_data['until']) ? intval($message_data['until']) : $last_check+24*60*60;
		}
	}

	update_option('thegem_info_message', array(
		'last_check' => $last_check,
		'class' => $notice_class,
		'html' => $notice_html,
		'until' => $notice_until,
	));

	if(!empty($notice_class) && !empty($notice_html) && $notice_until > time()) {
		echo '<div class="thegem-news-notice notice '.esc_attr($notice_class).' is-dismissible">';
		echo wp_kses_post($notice_html);
		echo '</div>';
	}
}

function thegem_plugin_pre_set_site_transient_update_themes( $transient ) {
	$theme = wp_get_theme('thegem');
	if ( version_compare( $theme->get( 'Version' ), '4.0.0', '<' ) ) {
		$response = wp_remote_get('http://democontent.codex-themes.com/plugins/thegem/theme/theme.json', array('timeout' => 5));
		if ( is_wp_error( $response ) ) {
			return $transient;
		}

		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body, 1);
		if ( ! isset( $data['new_version'] ) ) {
			return $transient;
		}

		$new_version = $data['new_version'];

		// Save update info if there are newer version.
		if ( version_compare( $theme->get( 'Version' ), $new_version, '<' ) ) {
			$transient->response[ 'thegem' ] = array(
				'theme' => 'thegem',
				'new_version' => $new_version,
				'url' => $data['changelog'],
				'package' => $data['package'],
			);
		}
	}

	return $transient;
}
add_filter('pre_set_site_transient_update_themes', 'thegem_plugin_pre_set_site_transient_update_themes', 10, 3);

function thegem_plugin_update_notice() {
	if ( !current_user_can('update_themes' ) )
		return false;
	if ( !isset($themes_update) )
		$themes_update = get_site_transient('update_themes');
	if ( isset($themes_update->response['thegem']) ) {
		$update = $themes_update->response['thegem'];
		$theme = wp_prepare_themes_for_js( array( wp_get_theme('thegem') ) );
		$details_url = add_query_arg(array(), $update['url']);
		$update_url = wp_nonce_url( admin_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( 'thegem' ) ), 'upgrade-theme_thegem' );
		if(isset($theme[0]) && isset($theme[0]['hasUpdate']) && $theme[0]['hasUpdate'] && version_compare( $theme[0]['version'], '4.0.0', '<' )) {
			wp_enqueue_script('jquery-fancybox');
			wp_enqueue_style('jquery-fancybox');
			echo '<div class="thegem-update-notice notice notice-warning is-dismissible">';
			echo '<p>'.sprintf(wp_kses(__('There is a new version of TheGem theme available. Your current version is <strong>%s</strong>. Update to <strong>%s</strong>.', 'thegem'), array('strong' => array())), $theme[0]['version'], $update['new_version']).'</p>';
			echo '<p>'.sprintf(wp_kses(__('<strong><a href="%s" class="thegem-view-details-link">View update details</a></strong> or <strong><a href="%s" class="thegem-update-link">Update now</a></strong>.', 'thegem'), array('strong' => array(), 'a' => array('href' => array(), 'class' => array()))), $details_url, $update_url).'</p>';
			echo '</div>';
		}
	}
}
add_action('admin_notices', 'thegem_plugin_update_notice');

function thegem_plugin_theme_update_notice() {
	if ( !current_user_can('update_themes' ) )
		return false;
	$thegem_theme = wp_get_theme('thegem');
	if($thegem_theme->exists() && version_compare($thegem_theme->get('Version'), '5.9.3', '<')) {
		echo '<div class="thegem-update-notice-new notice notice-error" style="display: flex; align-items: center;">';
		echo '<p style="margin: 5px 15px 0 10px;"><img src=" '.THEGEM_THEME_URI . '/images/alert-icon.svg'.' " width="40px" alt="thegem-blocks-logo"></p>';
		echo '<p><b style="display: block; font-size: 14px; padding-bottom: 5px">'.__('IMPORTANT:', 'thegem').'</b>'.__('Please update <strong>«TheGem Theme»</strong> to the latest version.', 'thegem').'</p>';
		echo '<p style="margin-left: auto;">'.sprintf(wp_kses(__('<a href="%s" class="button button-primary">Update now</a>', 'thegem'), array('strong' => array(), 'a' => array('href' => array(), 'class' => array()))), esc_url(admin_url('update-core.php'))).'</p>';
		echo '</div>';
	}
}
add_action('admin_notices', 'thegem_plugin_theme_update_notice');

function thegem_reset_wpb_license_errors() {
	update_option( 'wpb_license_errors', array() );
}
add_action('admin_notices', 'thegem_reset_wpb_license_errors', 1);

$thegem_plugin_file = __FILE__;

add_action('admin_notices', 'thegem_info_message_notice');

require_once(plugin_dir_path( __FILE__ ) . 'inc/content.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/remote_media_upload.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/diagram.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/additional.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/post-types/init.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/shortcodes/init.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/widgets/init.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/add_vc_icon_fonts.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/gdpr/gdpr.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/blocks-helper/index.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/product-grid.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/woo-swatches.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/templates/templates.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/blog-extended-grid.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/heading-animation/index.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/button-animation/index.php');
