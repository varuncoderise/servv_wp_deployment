<?php

class TheGem_Template_Element_Logo extends TheGem_Template_Element {

	public function __construct() {
		if ( !defined('THEGEM_TE_LOGO_DIR' )) {
			define('THEGEM_TE_LOGO_DIR', rtrim(__DIR__, ' /\\'));
		}

		if ( !defined('THEGEM_TE_LOGO_URL') ) {
			define('THEGEM_TE_LOGO_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}

		wp_register_script('thegem-te-logo', THEGEM_TE_LOGO_URL . '/js/logo.js', array('jquery'), false, true);
		wp_register_style('thegem-te-logo', THEGEM_TE_LOGO_URL . '/css/logo.css');
		wp_register_style('thegem-te-logo-editor', THEGEM_TE_LOGO_URL . '/css/logo-editor.css');
	}

	public function get_name() {
		return 'thegem_te_logo';
	}

	public function head_scripts($attr) {
		wp_enqueue_script('thegem-te-logo');
		wp_enqueue_style('thegem-te-logo');
	}

	public function front_editor_scripts() {
		wp_enqueue_style('thegem-te-logo');
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'desktop_logo' => 'desktop_logo_dark',
			'tablet_landscape_logo' => 'default',
			'tablet_portrait_logo' => 'mobile',
			'mobile_logo' => 'mobile_logo_dark',
			'use_light' => '',
			'view_type' => wp_is_mobile() ? 'mobile-view' : 'desktop-view',
		), thegem_templates_design_options_extract(), thegem_templates_extra_options_extract()), $atts, 'thegem_te_logo');

		//General
		$extra_id = $extra_cls = '';
		if (!empty($params['element_id'])){ $extra_id = $params['element_id']; }
		if (!empty($params['element_class'])){ $extra_cls = $params['element_class']; }

		// Output Desktop Logo
		$output_desktop_logo = $output_mobile_logo = '';
		$echo = false;
		if (empty($params['use_light']) && isset($params['desktop_logo']) && $params['desktop_logo'] == 'desktop_logo_dark') {
			$output_desktop_logo = thegem_get_logo_img(esc_url(thegem_get_option('logo')), intval(thegem_get_option('logo_width')), 'tgp-exclude default', $echo);
		}
		if((empty($params['use_light']) && isset($params['desktop_logo']) && $params['desktop_logo'] == 'desktop_logo_light') || !empty($params['use_light'])) {
			$output_desktop_logo = thegem_get_logo_img(esc_url(thegem_get_option('logo_light')), intval(thegem_get_option('logo_width')), 'tgp-exclude default light', $echo);
		}

		// Output Mobile Logo
		if (empty($params['use_light']) && isset($params['mobile_logo']) && $params['mobile_logo'] == 'mobile_logo_dark') {
			$output_mobile_logo = thegem_get_logo_img(esc_url(thegem_get_option('small_logo')), intval(thegem_get_option('small_logo_width')), 'tgp-exclude small', $echo);
		}
		if((empty($params['use_light']) && isset($params['mobile_logo']) && $params['mobile_logo'] == 'mobile_logo_light') || !empty($params['use_light'])) {
			$output_mobile_logo = thegem_get_logo_img(esc_url(thegem_get_option('small_logo_light')), intval(thegem_get_option('small_logo_width')), 'tgp-exclude small light', $echo);
		}

		// Output Sticky Logo
		if (isset($params['desktop_logo']) && $params['desktop_logo'] == 'sticky_logo_dark') {
			$output_desktop_logo = thegem_get_logo_img(esc_url(thegem_get_option('small_logo')), intval(thegem_get_option('small_logo_width')), 'tgp-exclude small', $echo);
		}
		if((isset($params['desktop_logo']) && $params['desktop_logo'] == 'sticky_logo_light')) {
			$output_desktop_logo = thegem_get_logo_img(esc_url(thegem_get_option('small_logo_light')), intval(thegem_get_option('small_logo_width')), 'tgp-exclude small light', $echo);
		}


		// Init Design Options Params
		$return_html = $custom_css = $uniqid = '';
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-logo', $params);
		
		//Output Url
		$custom_link = vc_build_link($params['element_link']);
		$output_url = esc_url(home_url('/'));
		$output_url_target = '_self';
		$output_url_rel = 'home';
		$output_url_title = '';
		
		if (!empty($custom_link['url'])) {
			$output_url = $custom_link['url'];
			$output_url_target = $custom_link['target'];
			$output_url_title = $custom_link['title'];
			$output_url_rel = $custom_link['rel'];
		}

		ob_start();
		?>

		<div <?php if ($extra_id): ?>id="<?=esc_attr($extra_id); ?>"<?php endif;?> class="thegem-te-logo <?= esc_attr($extra_cls); ?> <?= $params['view_type'] ?> <?= esc_attr($uniqid) ?>" <?=thegem_data_editor_attribute($uniqid . '-editor')?> data-tablet-landscape="<?=$params['tablet_landscape_logo']?>" data-tablet-portrait="<?=$params['tablet_portrait_logo']?>">
			<div class="site-logo">
				<a href="<?= esc_url($output_url) ?>" target="<?= esc_attr($output_url_target) ?>" <?php if ($output_url_title): ?>title="<?=$output_url_title?>"<?php endif;?> <?php if ($output_url_rel): ?>rel="<?=$output_url_rel?>"<?php endif;?>>
					<?php if (thegem_get_option('logo')) : ?>
						<span class="logo">
							<span class="logo desktop"> <?= $output_desktop_logo ?> </span>
							<span class="logo mobile"> <?= $output_mobile_logo ?> </span>
						</span>
					<?php else : ?>
						<?php bloginfo('name'); ?>
					<?php endif; ?>
				</a>
			</div>
		</div>

        <script type="text/javascript">
            (function($){
                let tabletLandscapeMaxWidth = 1212,
                    tabletLandscapeMinWidth = 980,
                    tabletPortraitMaxWidth = 979,
                    tabletPortraitMinWidth = 768,
                    viewportWidth = window.innerWidth;

                $('.thegem-te-logo').each(function (i, el) {
                    if ($(this).data("tablet-landscape") === 'default' && viewportWidth >= tabletLandscapeMinWidth && viewportWidth <= tabletLandscapeMaxWidth) {
                        $(this).removeClass('mobile-view').addClass('desktop-view');
                    } else if ($(this).data("tablet-portrait") === 'default' && viewportWidth >= tabletPortraitMinWidth && viewportWidth <= tabletPortraitMaxWidth) {
                        $(this).removeClass('mobile-view').addClass('desktop-view');
                    } else if (viewportWidth <= tabletLandscapeMaxWidth) {
                        $(this).removeClass('desktop-view').addClass('mobile-view');
                    } else {
                        $(this).removeClass('mobile-view').addClass('desktop-view');
                    }
                });
            })(jQuery);
        </script>
        
		<?php

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
			'name' => __('Logo', 'thegem'),
			'base' => 'thegem_te_logo',
			'icon' => 'thegem-icon-wpb-ui-element-logo',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('Site Logo (Header Builder)', 'thegem'),
			'params' => array_merge(
				array(
					array(
						'type' => 'dropdown',
						'heading' => __('Desktop', 'thegem'),
						'param_name' => 'desktop_logo',
						'value' => array(
							__('Desktop Logo (Dark)', 'thegem') => 'desktop_logo_dark',
							__('Desktop Logo (Light)', 'thegem') => 'desktop_logo_light',
							__('Sticky Header Logo (Dark)', 'thegem') => 'sticky_logo_dark',
							__('Sticky Header Logo (Light)', 'thegem') => 'sticky_logo_light',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6 no-top-padding',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Mobile', 'thegem'),
						'param_name' => 'mobile_logo',
						'value' => array(
							__('Mobile Logo (Dark)', 'thegem') => 'mobile_logo_dark',
							__('Mobile Logo (Light)', 'thegem') => 'mobile_logo_light',
						),
						'edit_field_class' => 'vc_column vc_col-sm-6 no-top-padding',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Tablet (Landscape)', 'thegem'),
						'param_name' => 'tablet_landscape_logo',
						'value' => array(
							__('As set for desktop', 'thegem') => 'default',
							__('As set for mobile', 'thegem') => 'mobile',
						),
						'std' => 'default',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Tablet (Portrait)', 'thegem'),
						'param_name' => 'tablet_portrait_logo',
						'value' => array(
							__('As set for desktop', 'thegem') => 'default',
							__('As set for mobile', 'thegem') => 'mobile',
						),
						'std' => 'mobile',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading',
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'description' => __('Go to <a href="'.get_site_url().'/wp-admin/admin.php?page=thegem-theme-options#/general/logo-and-identity" target="_blank">Theme Options</a> to manage different types of your website logo.', 'thegem'),
						'group' => __('General', 'thegem')
					),
				),

				/* General - Extra */
				thegem_set_elements_extra_options(true),

				/* Design Options */
				thegem_set_elements_design_options()
			),
		);
	}
}

$templates_elements['thegem_te_logo'] = new TheGem_Template_Element_Logo();
$templates_elements['thegem_te_logo']->init();
