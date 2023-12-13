<?php

class TheGem_Template_Element_Post_Tags extends TheGem_Single_Post_Template_Element {
	public $show_in_posts = false;
	public $show_in_loop = true;
	public function __construct() {
	}
	
	public function get_name() {
		return 'thegem_te_post_tags';
	}
	
	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'alignment' => 'left',
			'title' => 'Tags:',
			'text_style' => '',
			'text_font_weight' => '',
			'text_font_style' => '',
			'text_letter_spacing' => '',
			'text_transform' => '',
			'title_color' => '',
			'link_color' => '',
			'link_color_hover' => '',
			'link_background_color' => '',
			'link_background_color_hover' => '',
			'link_border_color' => '',
			'link_border_color_hover' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_post_tags');
  
		// Init Title
		ob_start();
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$single_post = thegem_templates_init_post();
		$tags = get_the_tags();
		
		if (empty($single_post) || empty($tags)) {
			ob_end_clean();
			return thegem_templates_close_single_post($this->get_name(), $this->shortcode_settings(), '');
		}
		
        $alignment = !empty($params['alignment']) ? 'post-tags--'.$params['alignment'] : '';
		$params['element_class'] = implode(' ', array(
			$alignment,
			$params['element_class'],
            thegem_templates_responsive_options_output($params)
		));
		$text_styled = implode(' ', array($params['text_style'], $params['text_font_weight']));
		
		$tags_output = '';
		foreach ($tags as $tag) {
			$tag_link = get_tag_link($tag->term_id);
			$tags_output .= '<li><a href="' . $tag_link . '" title="' . $tag->name . '" class="' . $text_styled . '">' . $tag->name . '</a></li>';
		}
		
		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?= esc_attr($params['element_id']); ?>"<?php endif; ?> class="thegem-te-post-tags <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
            <div class="post-tags">
                <?php if (!empty($params['title'])): ?>
                    <div class="post-tags__title">
                        <span class="<?= $text_styled ?>"><?= esc_html($params['title']) ?></span>
                    </div>
                <?php endif; ?>

                <div class="post-tags__list">
                    <ul><?= $tags_output ?></ul>
                </div>
            </div>
        </div>
		
		<?php
		//Custom Styles
		$customize = '.thegem-te-post-tags.'.$uniqid;
		$custom_css = '';
		
		// Layout Styles
		
		// Text Styles
		if ($params['text_letter_spacing'] != '') {
			$custom_css .= $customize.' .post-tags__title {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
			$custom_css .= $customize.' .post-tags__list a {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
		}
		if ($params['text_transform'] != '') {
			$custom_css .= $customize.' .post-tags__title {text-transform: ' . $params['text_transform'] . ';}';
			$custom_css .= $customize.' .post-tags__list a {text-transform: ' . $params['text_transform'] . ';}';
		}
		if (!empty($params['title_color'])) {
			$custom_css .= $customize.' .post-tags__title {color: ' . $params['title_color'] . ';}';
		}
		if (!empty($params['link_color'])) {
			$custom_css .= $customize.' .post-tags__list a {color: ' . $params['link_color'] . ';}';
		}
		if (!empty($params['link_color_hover'])) {
			$custom_css .= $customize.' .post-tags__list a:hover {color: ' . $params['link_color_hover'] . ';}';
		}
		if (!empty($params['link_background_color'])) {
			$custom_css .= $customize.' .post-tags__list a {background-color: ' . $params['link_background_color'] . ';}';
		}
		if (!empty($params['link_background_color_hover'])) {
			$custom_css .= $customize.' .post-tags__list a:hover {background-color: ' . $params['link_background_color_hover'] . ';}';
		}
		if (!empty($params['link_border_color'])) {
			$custom_css .= $customize.' .post-tags__list a {border-color: ' . $params['link_border_color'] . ';}';
		}
		if (!empty($params['link_border_color_hover'])) {
			$custom_css .= $customize.' .post-tags__list a:hover {border-color: ' . $params['link_border_color_hover'] . ';}';
		}
  
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
			'heading' => __('General', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
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
					__('Justified', 'thegem') => 'justified',
				)
			),
			'std' => 'left',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			"type" => "textfield",
			'heading' => __('Title', 'thegem'),
			'param_name' => 'title',
			'std' => 'Tags:',
			"edit_field_class" => "vc_column vc_col-sm-12",
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'text_style',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Title H1', 'thegem') => 'title-h1',
				__('Title H2', 'thegem') => 'title-h2',
				__('Title H3', 'thegem') => 'title-h3',
				__('Title H4', 'thegem') => 'title-h4',
				__('Title H5', 'thegem') => 'title-h5',
				__('Title H6', 'thegem') => 'title-h6',
				__('Title xLarge', 'thegem') => 'title-xlarge',
				__('Styled Subtitle', 'thegem') => 'styled-subtitle',
				__('Main Menu', 'thegem') => 'title-main-menu',
				__('Body', 'thegem') => 'text-body',
				__('Tiny Body', 'thegem') => 'text-body-tiny',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'text_font_weight',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Thin', 'thegem') => 'light',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'text_letter_spacing',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Transform', 'thegem'),
			'param_name' => 'text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Title Color', 'thegem'),
			'param_name' => 'title_color',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Link Color', 'thegem'),
			'param_name' => 'link_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Link Color on Hover', 'thegem'),
			'param_name' => 'link_color_hover',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Link Background Color', 'thegem'),
			'param_name' => 'link_background_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Link Background Color on Hover', 'thegem'),
			'param_name' => 'link_background_color_hover',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Link Border Color', 'thegem'),
			'param_name' => 'link_border_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Link Border Color on Hover', 'thegem'),
			'param_name' => 'link_border_color_hover',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		return $result;
	}
	
	public function shortcode_settings() {
		
		return array(
			'name' => __('Post Tags', 'thegem'),
			'base' => 'thegem_te_post_tags',
			'icon' => 'thegem-icon-wpb-ui-element-post-tags',
			'category' => __('Single Post Builder', 'thegem'),
			'description' => __('Post Tags (Single Post Builder)', 'thegem'),
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

$templates_elements['thegem_te_post_tags'] = new TheGem_Template_Element_Post_Tags();
$templates_elements['thegem_te_post_tags']->init();
