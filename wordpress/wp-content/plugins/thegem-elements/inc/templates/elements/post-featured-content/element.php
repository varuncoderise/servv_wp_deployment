<?php

class TheGem_Template_Element_Post_Featured_Content extends TheGem_Single_Post_Template_Element {
	public $show_in_posts = false;
	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_post_featured_content';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'size' => 'default',
			'alignment' => 'left',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_post_featured_content');

		// Init Content
		ob_start();
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$single_post = thegem_templates_init_post();
        $featured_content = thegem_get_post_featured_content($single_post->ID);

		if (empty($single_post) || empty($featured_content)) {
			ob_end_clean();
			return thegem_templates_close_single_post($this->get_name(), $this->shortcode_settings(), '');
		}

		$format = get_post_format($single_post->ID);
        $isImage = ($format == '' || $format == 'image');

		$size = !empty($params['size']) && $isImage ? 'featured-image--'.$params['size'] : null;
		$alignment = !empty($params['alignment']) && $isImage ? 'featured-image--'.$params['alignment'] : null;
		$params['element_class'] = implode(' ', array(
			$size,
			$alignment,
			$params['element_class'],
			thegem_templates_responsive_options_output($params)
		));

		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-post-featured-content <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
            <?= $featured_content ?>
        </div>

		<?php
		//Custom Styles
		$customize = '.thegem-te-post-featured-content.'.$uniqid;
		$custom_css = '';

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}

		$return_html = $css_output.$return_html;
		return thegem_templates_close_single_post($this->get_name(), $this->shortcode_settings(), $return_html);
	}

	public function set_layout_params() {
		$result = array();
		$group = __('General', 'thegem');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Image', 'thegem'),
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
				)
			),
			'std' => 'default',
			'edit_field_class' => 'vc_column vc_col-sm-12',
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

		return $result;
	}

	public function shortcode_settings() {

		return array(
			'name' => __('Featured Content', 'thegem'),
			'base' => 'thegem_te_post_featured_content',
			'icon' => 'thegem-icon-wpb-ui-element-post-featured-content',
			'category' => __('Single Post Builder', 'thegem'),
			'description' => __('Post Featured Content (Single Post Builder)', 'thegem'),
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

$templates_elements['thegem_te_post_featured_content'] = new TheGem_Template_Element_Post_Featured_Content();
$templates_elements['thegem_te_post_featured_content']->init();
