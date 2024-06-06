<?php

class TheGem_Template_Element_Account extends TheGem_Template_Element {

	public function __construct() {

		if ( !defined('THEGEM_TE_ACCOUNT_DIR' )) {
			define('THEGEM_TE_ACCOUNT_DIR', rtrim(__DIR__, ' /\\'));
		}

		if ( !defined('THEGEM_TE_ACCOUNT_URL') ) {
			define('THEGEM_TE_ACCOUNT_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}

		wp_register_script('thegem-te-account', THEGEM_TE_ACCOUNT_URL . '/js/account.js', array('jquery'), false, true);
		wp_register_style('thegem-te-account', THEGEM_TE_ACCOUNT_URL . '/css/account.css');
		wp_register_style('thegem-te-account-editor', THEGEM_TE_ACCOUNT_URL . '/css/account-editor.css');
	}

	public function get_name() {
		return 'thegem_te_account';
	}

	public function head_scripts($attr) {
		$attr = shortcode_atts(array('pack' => 'thegem-header'), $attr, 'thegem-te-account');
		wp_enqueue_style('icons-'.$attr['pack']);
		wp_enqueue_style('thegem-te-account');
	}

	public function front_editor_scripts($attr) {
		wp_enqueue_style('thegem-te-account');
	}

	public function shortcode_output($atts, $content = '') {
		$params = shortcode_atts(array_merge(array(
			'pack' => 'thegem-header',
			'icon_elegant' => '',
			'icon_material' => '',
			'icon_fontawesome' => '',
			'icon_thegemdemo' => '',
			'icon_userpack' => '',
			'icon_thegem_header' => '',
			'custom_link' => '',
			'icon_size' => 'small',
			'icon_size_custom' => '',
			'icon_color' => '',
			'icon_color_hover' => '',
			//Extra
			'element_id' => '',
			'element_class' => '',
			'element_link' => '',
		), thegem_templates_design_options_extract()), $atts, 'thegem_te_account');

		// Init Design Options Params
		$custom_css = $uniqid = $icon = '';
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-account', $params);

		if ($params['pack'] == 'elegant' && empty($icon) && $params['icon_elegant']) {
			$icon = $params['icon_elegant'];
		}
		if ($params['pack'] == 'material' && empty($icon) && $params['icon_material']) {
			$icon = $params['icon_material'];
		}
		if ($params['pack'] == 'fontawesome' && empty($icon) && $params['icon_fontawesome']) {
			$icon = $params['icon_fontawesome'];
		}
		if ($params['pack'] == 'thegemdemo' && empty($icon) && $params['icon_thegemdemo']) {
			$icon = $params['icon_thegemdemo'];
		}
		if ($params['pack'] == 'userpack' && empty($icon) && $params['icon_userpack']) {
			$icon = $params['icon_userpack'];
		}
		if($params['pack'] =='thegem-header' && empty($icon) && $params['icon_thegem_header']) {
			$icon = $params['icon_thegem_header'];
		}

		//General
		$el_id = $el_class = '';
		if (!empty($params['element_id'])){ $el_id = $params['element_id']; }
		if (!empty($params['element_class'])){ $el_class = $params['element_class']; }

		//Output Url
		$custom_link = vc_build_link($params['element_link']);
		$output_url = 'javascript:void(0)';
		$output_url_target = '_self';
		$output_url_title = $output_url_rel = '';

		if (!empty($custom_link['url'])) {
			$output_url = $custom_link['url'];
			$output_url_target = $custom_link['target'];
			$output_url_title = $custom_link['title'];
			$output_url_rel = $custom_link['rel'];
		}
		if(thegem_is_plugin_active('woocommerce/woocommerce.php') && empty($custom_link['url'])) {
			$output_url = get_permalink( wc_get_page_id( 'myaccount' ));
		}

		//Output Icon
		$output_icon = '<span class="gem-icon-half-1"><span class="back-angle">&#x' . $icon . ';</span></span>'.
					   '<span class="gem-icon-half-2"><span class="back-angle">&#x' . $icon . ';</span></span>';

		// Init wishlist
		ob_start(); ?>

		<div <?php if ($el_id): ?>id="<?=esc_attr($el_id); ?>"<?php endif;?> class="thegem-te-account <?= esc_attr($el_class); ?> <?= esc_attr($uniqid); ?>" <?=thegem_data_editor_attribute($uniqid . '-editor')?>>
			<a class="account-link" href="<?= esc_url($output_url) ?>" target="<?= esc_attr($output_url_target) ?>" <?php if ($output_url_title): ?>title="<?=$output_url_title?>"<?php endif;?> <?php if ($output_url_rel): ?>rel="<?=$output_url_rel?>"<?php endif;?>>
				<div class="gem-icon gem-simple-icon gem-icon-size-<?=$params['icon_size']?> gem-icon-pack-<?=$params['pack']?>">
					<?php if (!empty($icon)): ?>
						<div class="gem-icon-inner"><?= $output_icon ?></div>
					<?php else: ?>
						<div class="account-icon-default"></div>
					<?php endif; ?>
				</div>
			</a>
		</div>

		<?php

		// Icon Custom Styles
		$customize = '.thegem-te-account.'.$uniqid;

		if(!empty($params['icon_size_custom']) && $params['icon_size'] == 'custom') {
			$custom_size = $params['icon_size_custom'];
			$custom_css .= $customize.' .gem-icon {font-size: '.esc_attr($custom_size).'px;}';
			$custom_css .= $customize.' .gem-icon.gem-simple-icon {width: '. $custom_size .'px; height: '. $custom_size .'px; line-height: '. $custom_size .'px;}';
		}

		if(!empty($params['icon_color'])) {
			$custom_css .= $customize.' .account-link {color: '.$params['icon_color'].';}';
		}
		if(!empty($params['icon_color_hover'])) {
			$custom_css .= $customize.' .account-link:hover {color: '.$params['icon_color_hover'].';}';
		}

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}

		$return_html = $css_output.$return_html;
		return $return_html;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('My Account', 'thegem'),
			'base' => 'thegem_te_account',
			'icon' => 'thegem-icon-wpb-ui-element-account',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('My Account Icon (Header Builder)', 'thegem'),
			'params' => array_merge(
				/* General - Icon*/
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Icon pack', 'thegem'),
						'param_name' => 'pack',
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
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_thegem_header',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Color', 'thegem'),
						'param_name' => 'icon_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover color', 'thegem'),
						'param_name' => 'icon_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Size', 'thegem'),
						'param_name' => 'icon_size',
						'value' => array(
							__('Tiny', 'thegem') => 'tiny',
							__('Small', 'thegem') => 'small',
							__('Medium', 'thegem') => 'medium',
							__('Custom', 'thegem') => 'custom'
						),
						'std' => 'small',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Custom size', 'thegem'),
						'param_name' => 'icon_size_custom',
						'dependency' => array(
							'element' => 'icon_size',
							'value' => array('custom')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					)
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6 no-top-padding',
						'group' => __('General', 'thegem')
					),
				)),

				/* General - Extra */
				thegem_set_elements_extra_options(true),

				/* Design Options */
				thegem_set_elements_design_options()
			),
		);
	}
}

$templates_elements['thegem_te_account'] = new TheGem_Template_Element_Account();
$templates_elements['thegem_te_account']->init();