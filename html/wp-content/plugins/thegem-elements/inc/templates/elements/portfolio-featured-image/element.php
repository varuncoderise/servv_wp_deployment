<?php

class TheGem_Template_Element_Portfolio_Featured_Image extends TheGem_Portfolio_Item_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_portfolio_featured_image';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'size' => 'default',
			'alignment' => 'left',
			'width' => '',
			'height' => '',
			'alt' => '',
			'style' => '',
			'action' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_portfolio_featured_image');

		// Init Featured Image
		ob_start();
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$portfolio = thegem_templates_init_portfolio();
		$featured_image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $portfolio->ID ), "full" );

		if (empty($portfolio) || empty($featured_image_data)) {
			ob_end_clean();
			return thegem_templates_close_portfolio($this->get_name(), $this->shortcode_settings(), '');
		}

        $src = $featured_image_data[0];
		$alt = !empty($params['alt']) ? $params['alt'] : $portfolio->post_name;
		$width = !empty($params['width']) ? $params['width'] : $featured_image_data[1];
		$height = !empty($params['height']) ? $params['height'] : $featured_image_data[2];
		if (!empty($params['size']) && $params['size'] == 'custom') {
			$width = substr($width, -1) == '%' || substr($width, -2) == 'px' ? $width . '' : $width . 'px';
			$height = substr($height, -1) == '%' || substr($height, -2) == 'px' ? $height . '' : $height . 'px';
		}
		$size = !empty($params['size']) ? 'featured-image--'.$params['size'] : null;
		$alignment = !empty($params['alignment']) ? 'featured-image--'.$params['alignment'] : null;
		$style = !empty($params['style']) ? 'featured-image--'.$params['style'] : null;
		$params['element_class'] = implode(' ', array(
			$size,
			$alignment,
			$style,
			$params['element_class'],
            thegem_templates_responsive_options_output($params)
        ));

		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?> class="thegem-te-portfolio-featured-image <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
            <div class="portfolio-featured-image">
	            <?php if (!empty($params['action'])): ?><a href="<?= $src ?>" class="fancybox" rel="lightbox"><?php endif; ?>
                    <img src="<?= $src ?>"
                         width="<?= esc_attr($width) ?>"
                         height="<?= esc_attr($height) ?>"
                         class="img-responsive"
                         alt="<?= esc_attr($alt) ?>"
                         <?php if ($params['size'] == 'custom'): ?>style="width: <?= esc_attr($width) ?>; height: <?= esc_attr($height) ?>"<?php endif; ?>>
                <?php if (!empty($params['action'])): ?></a><?php endif; ?>
            </div>
        </div>

		<?php
		//Custom Styles
		$customize = '.thegem-te-portfolio-featured-image.'.$uniqid;
		$custom_css = '';

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}

		$return_html = $css_output.$return_html;
		return thegem_templates_close_portfolio($this->get_name(), $this->shortcode_settings(), $return_html);
	}

	public function set_layout_params() {
		$result = array();
		$group = __('General', 'thegem');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('General', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Size', 'thegem'),
			'param_name' => 'size',
			'value' => array_merge(array(
					__('Default', 'thegem') => 'default',
					__('Stretch', 'thegem') => 'stretch',
					__('Custom', 'thegem') => 'custom',
				)
			),
			'std' => 'default',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Width', 'thegem'),
			'param_name' => 'width',
			'dependency' => array(
				'element' => 'size',
				'value' => array('custom')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Height', 'thegem'),
			'param_name' => 'height',
			'dependency' => array(
				'element' => 'size',
				'value' => array('custom')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('', 'thegem'),
			'param_name' => 'custom_size_description_sub_delim_head',
			'edit_field_class' => 'vc_column vc_col-sm-12 no-top-padding',
			'description' => __('Note: px units are used by default. For % units add values with %, eg. 10%', 'thegem'),
			'dependency' => array(
				'element' => 'size',
				'value' => array('custom')
			),
			'group' => $group
		);

		$result[] = array(
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
			'dependency' => array(
				'element' => 'size',
				'value' => array('default', 'custom')
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Alt text', 'thegem'),
			'param_name' => 'alt',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Image style', 'thegem'),
			'param_name' => 'style',
			'value' => array_merge(array(
					__('Default', 'thegem') => '',
					__('8px & border', 'thegem') => 'border-8',
					__('16px & border', 'thegem') => 'border-16',
					__('8px outlined border', 'thegem') => 'border-outline-8',
					__('20px outlined border', 'thegem') => 'border-outline-20',
					__('20px border with shadow', 'thegem') => 'border-shadow-20',
					__('Combined border', 'thegem') => 'border-combined',
					__('20px border radius', 'thegem') => 'border-radius-20',
					__('55px border radius', 'thegem') => 'border-radius-55',
					__('Dashed inside', 'thegem') => 'dashed-inside',
					__('Dashed outside', 'thegem') => 'dashed-outside',
					__('Rounded with border', 'thegem') => 'rounded-with-border',
					__('Rounded without border', 'thegem') => 'rounded-without-border'
				)
			),
			'std' => '',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('On click action', 'thegem'),
			'param_name' => 'action',
			'value' => array_merge(array(
					__('None', 'thegem') => '',
					__('Open Lightbox', 'thegem') => 'lightbox',
				)
			),
			'std' => '',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		return $result;
	}

	public function shortcode_settings() {

		return array(
			'name' => __('Featured Image', 'thegem'),
			'base' => 'thegem_te_portfolio_featured_image',
			'icon' => 'thegem-icon-wpb-ui-element-portfolio-featured-image',
			'category' => __('Portfolio Page Builder', 'thegem'),
			'description' => __('Featured Image (Portfolio Page Builder)', 'thegem'),
			'params' => array_merge(

			    /* General - Layout */
				$this->set_layout_params(),

				/* Extra Options */
				thegem_set_elements_extra_options(),

				/* Responsive Options */
				thegem_set_elements_responsive_options()
			),
		);
	}
}

$templates_elements['thegem_te_portfolio_featured_image'] = new TheGem_Template_Element_Portfolio_Featured_Image();
$templates_elements['thegem_te_portfolio_featured_image']->init();
