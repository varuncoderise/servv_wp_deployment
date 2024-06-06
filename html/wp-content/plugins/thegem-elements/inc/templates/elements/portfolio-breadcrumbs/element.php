<?php

class TheGem_Template_Element_Portfolio_Breadcrumbs extends TheGem_Portfolio_Item_Template_Element {
	
	public function __construct() {
	}
	
	public function get_name() {
		return 'thegem_te_portfolio_breadcrumbs';
	}
	
	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'alignment' => 'left',
			'color' => '',
			'color_hover' => '',
			'color_active' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_portfolio_breadcrumbs');
		
		// Init Breadcrumbs
		ob_start();
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$portfolio = thegem_templates_init_portfolio();
		
		if (empty($portfolio)) {
			ob_end_clean();
			return thegem_templates_close_portfolio($this->get_name(), $this->shortcode_settings(), '');
		}
		
		$params['element_class'] = implode(' ', array($params['element_class'], thegem_templates_responsive_options_output($params)));
		
		?>
		
		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?> class="thegem-te-portfolio-breadcrumbs <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
            <div class="portfolio-breadcrumbs"><?= gem_breadcrumbs(true) ?></div>
		</div>
		
		<?php
		//Custom Styles
		$customize = '.thegem-te-portfolio-breadcrumbs.'.$uniqid;
		$custom_css = '';
		
		if (!empty($params['alignment'])) {
			$custom_css .= $customize.' .portfolio-breadcrumbs {justify-content: ' . $params['alignment'] . '; text-align: ' . $params['alignment'] . ';}';
			$custom_css .= $customize.' .portfolio-breadcrumbs ul {justify-content: ' . $params['alignment'] . '; text-align: ' . $params['alignment'] . ';}';
		}
		if (!empty($params['color'])) {
			$custom_css .= $customize.' .portfolio-breadcrumbs ul li {color: ' . $params['color'] . ';}';
			$custom_css .= $customize.' .portfolio-breadcrumbs ul li a {color: ' . $params['color'] . ';}';
		}
		if (!empty($params['color_hover'])) {
			$custom_css .= $customize.' .portfolio-breadcrumbs ul li a:hover {color: ' . $params['color_hover'] . ';}';
		}
		if (!empty($params['color_active'])) {
			$custom_css .= $customize.' .portfolio-breadcrumbs ul li:last-child {color: ' . $params['color_active'] . ';}';
		}
		
		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
		
		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}
		
		$return_html = $css_output.$return_html;
		return thegem_templates_close_portfolio($this->get_name(), $this->shortcode_settings(), $return_html);
	}
	
	public function shortcode_settings() {
		
		return array(
			'name' => __('Breadcrumbs', 'thegem'),
			'base' => 'thegem_te_portfolio_breadcrumbs',
			'icon' => 'thegem-icon-wpb-ui-element-portfolio-breadcrumbs',
			'category' => __('Portfolio Page Builder', 'thegem'),
			'description' => __('Breadcrumbs (Portfolio Page Builder)', 'thegem'),
			'params' => array_merge(
			
			    /* General - Layout */
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Layout', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Alignment', 'thegem'),
						'param_name' => 'alignment',
						'value' => array_merge(array(
								__('Left', 'thegem') => 'left',
								__('Center', 'thegem') => 'center',
								__('Right', 'thegem') => 'right',
							)
						),
						'std' => 'left',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal Color', 'thegem'),
						'param_name' => 'color',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover Color', 'thegem'),
						'param_name' => 'color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active Color', 'thegem'),
						'param_name' => 'color_active',
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => 'General'
					),
				),
				
				/* Extra Options */
				thegem_set_elements_extra_options(),
				
				/* Responsive Options */
				thegem_set_elements_responsive_options()
			),
		);
	}
}

$templates_elements['thegem_te_portfolio_breadcrumbs'] = new TheGem_Template_Element_Portfolio_Breadcrumbs();
$templates_elements['thegem_te_portfolio_breadcrumbs']->init();
