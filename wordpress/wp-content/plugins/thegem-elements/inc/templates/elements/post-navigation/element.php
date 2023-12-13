<?php

class TheGem_Template_Element_Post_Navigation extends TheGem_Single_Post_Template_Element {
	
	public function __construct() {
	}
	
	public function get_name() {
		return 'thegem_te_post_navigation';
	}
    
    public function is_first_post() {
	    return empty(get_previous_post());
    }

    public function is_last_post() {
		return empty(get_next_post());
	}
	
	public function get_taxonomy_list() {
		$data = array(
			__('Categories', 'thegem') => 'category',
			__('Tags', 'thegem') => 'post_tag',
        );
        
        /*
		$taxonomies = get_taxonomies(['object_type' => ['post']], 'objects');
		
		if (!empty($taxonomies)) {
			foreach ($taxonomies as $taxonomy) {
				$data[$taxonomy->label] = $taxonomy->name;
			}
		}
        */
		
		return $data;
	}
	
	public function get_default_taxonomy() {
		$taxonomies = array_flip($this->get_taxonomy_list());
		if (empty($taxonomies)) return;
		
		return array_keys($taxonomies)[0];
	}
	
	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'navigate_by' => 'chronology',
			'taxonomy' => $this->get_default_taxonomy(),
			'alignment' => 'justified',
			'max_width' => '',
			'label' => '1',
			'label_prev' => 'Previous Post',
			'label_next' => 'Next Post',
			'label_text_style' => '',
			'label_text_font_weight' => '',
			'label_text_font_style' => '',
			'label_text_letter_spacing' => '',
			'label_text_transform' => '',
			'label_text_color' => '',
			'label_text_color_hover' => '',
            'title' => '1',
			'title_text_style' => '',
			'title_text_font_weight' => '',
			'title_text_font_style' => '',
			'title_text_letter_spacing' => '',
			'title_text_transform' => '',
			'title_text_color' => '',
			'title_text_color_hover' => '',
            'arrows' => '1',
            'arrow_prev' => '1',
            'arrow_next' => '1',
			'arrows_type' => 'simple',
			'arrows_spacing' => '',
			'arrows_border_width' => '',
			'arrows_border_radius' => '',
			'arrows_width' => '',
			'arrows_height' => '',
			'arrows_color' => '',
			'arrows_color_hover' => '',
			'arrows_background_color' => '',
			'arrows_background_color_hover' => '',
			'arrows_border_color' => '',
			'arrows_border_color_hover' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_post_navigation');
  
		// Init Title
		ob_start();
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$single_post = thegem_templates_init_post();
		
		if (empty($single_post)) {
			ob_end_clean();
			return thegem_templates_close_single_post($this->get_name(), $this->shortcode_settings(), '');
		}
		
		$is_taxonomy = !empty($params['navigate_by']) && $params['navigate_by'] == 'taxonomy' ? true : false;
		$taxonomy = !empty($params['taxonomy']) ? $params['taxonomy'] : '';
  
		$alignment = 'post-nav--'.$params['alignment'];
		$last_post = $this->is_first_post() || $this->is_last_post() ? 'post-nav--hide-gaps' : '';
		$label = empty($params['label']) ? 'post-label--hide' : '';
		$title = empty($params['title']) ? 'post-title--hide' : '';
		$arrows = empty($params['arrows']) ? 'post-arrows--hide' : '';
		$arrow_prev = empty($params['arrow_prev']) ? 'post-arrows--prev-hide' : '';
		$arrow_next = empty($params['arrow_next']) ? 'post-arrows--next-hide' : '';
		$arrows_type = 'post-arrows--'.$params['arrows_type'];
		$params['element_class'] = implode(' ', array(
			$alignment, $last_post, $label, $title, $arrows, $arrow_prev, $arrow_next, $arrows_type,
            $params['element_class'], thegem_templates_responsive_options_output($params)
        ));
		
		$prev_text_output = $next_text_output = '';
		$label_text_styled = implode(' ', array($params['label_text_style'], $params['label_text_font_weight']));
		$title_text_styled = implode(' ', array($params['title_text_style'], $params['title_text_font_weight']));
  
		$prev_text_output .= !empty($params['arrows']) && !empty($params['arrow_prev']) ? '<i class="meta-icon"></i>' : '';
		$prev_text_output .= !empty($params['label']) || !empty($params['title']) ? '<span class="meta-nav" aria-hidden="true">' : '';
		$prev_text_output .= !empty($params['label']) ? '<span class="post-label"><span class="'.$label_text_styled.'">'.$params['label_prev'].'</span></span>' : '';
		$prev_text_output .= !empty($params['title']) ? '<span class="post-title"><span class="'.$title_text_styled.'">%title</span></span>' : '';
		$prev_text_output .= !empty($params['label']) || !empty($params['title']) ?'</span>' : '';
  
		$next_text_output .= !empty($params['label']) || !empty($params['title']) ? '<span class="meta-nav" aria-hidden="true">' : '';
		$next_text_output .= !empty($params['label']) ? '<span class="post-label"><span class="'.$label_text_styled.'">'.$params['label_next'].'</span></span>' : '';
		$next_text_output .= !empty($params['title']) ? '<span class="post-title"><span class="'.$title_text_styled.'">%title</span></span>' : '';
		$next_text_output .= !empty($params['label']) || !empty($params['title']) ? '</span>' : '';
		$next_text_output .= !empty($params['arrows']) && !empty($params['arrow_next']) ? '<i class="meta-icon"></i>' : '';
		
		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?= esc_attr($params['element_id']); ?>"<?php endif; ?> class="thegem-te-post-navigation <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
            <?php
                the_post_navigation( array(
                    'next_text' => $next_text_output,
                    'prev_text' => $prev_text_output,
	                'in_same_term' => $is_taxonomy,
	                'taxonomy' => $taxonomy
                ) );
            ?>
        </div>
		
		<?php
		//Custom Styles
		$customize = '.thegem-te-post-navigation.'.$uniqid;
		$custom_css = '';
		
		// General
		if (!empty($params['max_width'])) {
			$custom_css .= $customize.' .post-navigation a {max-width: '.$params['max_width'].'px;}';
		}
		
		// Label Style
		if (!empty($params['label_text_style'])) {
			$custom_css .= $customize.' .post-navigation .post-label {line-height: normal;}';
		}
		if (!empty($params['label_text_letter_spacing']) || $params['label_text_letter_spacing'] == '0') {
			$custom_css .= $customize.' .post-navigation .post-label span {letter-spacing: ' . $params['label_text_letter_spacing'] . 'px;}';
		}
		if (!empty($params['label_text_transform'])) {
			$custom_css .= $customize.' .post-navigation .post-label span {text-transform: ' . $params['label_text_transform'] . ';}';
		}
		if (!empty($params['label_text_color'])) {
			$custom_css .= $customize.' .post-navigation .post-label span {color: ' . $params['label_text_color'] . ';}';
		}
		if (!empty($params['label_text_color_hover'])) {
			$custom_css .= $customize.' .post-navigation a .post-label span {transition: color 0.3s;}';
			$custom_css .= $customize.' .post-navigation a:hover .post-label span {color: ' . $params['label_text_color_hover'] . ';}';
		}
		
		// Title Style
		if (!empty($params['title_text_style'])) {
			$custom_css .= $customize.' .post-navigation .post-title {line-height: normal;}';
		}
		if (!empty($params['title_text_letter_spacing']) || $params['title_text_letter_spacing'] == '0') {
			$custom_css .= $customize.' .post-navigation .post-title span {letter-spacing: ' . $params['title_text_letter_spacing'] . 'px;}';
		}
		if (!empty($params['title_text_transform'])) {
			$custom_css .= $customize.' .post-navigation .post-title span {text-transform: ' . $params['title_text_transform'] . ';}';
		}
		if (!empty($params['title_text_color'])) {
			$custom_css .= $customize.' .post-navigation .post-title span {color: ' . $params['title_text_color'] . ';}';
		}
		if (!empty($params['title_text_color_hover'])) {
			$custom_css .= $customize.' .post-navigation a .post-title span {transition: color 0.3s;}';
			$custom_css .= $customize.' .post-navigation a:hover .post-title span {color: ' . $params['title_text_color_hover'] . ';}';
		}
		
		// Arrows Style
		if (!empty($params['arrows_spacing']) || $params['arrows_spacing'] == '0') {
            if (!empty($params['label']) || !empty($params['title'])) {
	            $custom_css .= $customize.' .post-navigation .nav-previous .meta-icon {margin-right: ' . $params['arrows_spacing'] . 'px;}';
	            $custom_css .= $customize.' .post-navigation .nav-next .meta-icon {margin-left: ' . $params['arrows_spacing'] . 'px;}';
            } else{
	            $custom_css .= $customize.' .post-navigation .nav-previous {margin-right: ' . $params['arrows_spacing'] . 'px;}';
	            $custom_css .= $customize.' .post-navigation .nav-next {margin-left: ' . $params['arrows_spacing'] . 'px;}';
            }
		}
		if (!empty($params['arrows_width']) || $params['arrows_width'] == '0') {
			$custom_css .= $customize.' .post-navigation .meta-icon {min-width: ' . $params['arrows_width'] . 'px;}';
		}
		if (!empty($params['arrows_height']) || $params['arrows_height'] == '0') {
			$custom_css .= $customize.' .post-navigation .meta-icon {min-height: ' . $params['arrows_height'] . 'px;}';
		}
		if (!empty($params['arrows_border_width']) || $params['arrows_border_width'] == '0') {
			$custom_css .= $customize.' .post-navigation .meta-icon {border-width: ' . $params['arrows_border_width'] . 'px;}';
		}
		if (!empty($params['arrows_border_radius']) || $params['arrows_border_radius'] == '0') {
			$custom_css .= $customize.' .post-navigation .meta-icon {border-radius: ' . $params['arrows_border_radius'] . 'px;}';
		}
		if (!empty($params['arrows_color'])) {
			$custom_css .= $customize.' .post-navigation .meta-icon {color: ' . $params['arrows_color'] . ';}';
		}
		if (!empty($params['arrows_color_hover'])) {
			$custom_css .= $customize.' .post-navigation a:hover .meta-icon {color: ' . $params['arrows_color_hover'] . ';}';
		}
		if (!empty($params['arrows_background_color'])) {
			$custom_css .= $customize.' .post-navigation .meta-icon {background-color: ' . $params['arrows_background_color'] . ';}';
		}
		if (!empty($params['arrows_background_color_hover'])) {
			$custom_css .= $customize.' .post-navigation a:hover .meta-icon {background-color: ' . $params['arrows_background_color_hover'] . ';}';
		}
		if (!empty($params['arrows_border_color'])) {
			$custom_css .= $customize.' .post-navigation .meta-icon {border-color: ' . $params['arrows_border_color'] . ';}';
		}
		if (!empty($params['arrows_border_color_hover'])) {
			$custom_css .= $customize.' .post-navigation a:hover .meta-icon {border-color: ' . $params['arrows_border_color_hover'] . ';}';
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
			'type' => 'dropdown',
			'heading' => __('Navigate by', 'thegem'),
			'param_name' => 'navigate_by',
			'value' => array_merge(array(
					__('Chronology', 'thegem') => 'chronology',
					__('Same Taxonomy', 'thegem') => 'taxonomy',
				)
			),
			'std' => 'chronology',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Select Taxonomy', 'thegem'),
			'param_name' => 'taxonomy',
			'value' => $this->get_taxonomy_list(),
			'std' => $this->get_default_taxonomy(),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'dependency' => array(
				'element' => 'navigate_by',
				'value' => 'taxonomy'
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
					__('Justified', 'thegem') => 'justified',
				)
			),
			'std' => 'justified',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Max Width', 'thegem'),
			'param_name' => 'max_width',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		return $result;
	}
 
	public function set_label_params() {
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Label', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
        
        $result[] = array(
	        'type' => 'checkbox',
	        'heading' => __('Label', 'thegem'),
	        'param_name' => 'label',
	        'value' => array(__('Yes', 'thegem') => '1'),
	        'std' => '1',
	        'edit_field_class' => 'vc_column vc_col-sm-12',
	        'group' => $group
        );
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Previous Label', 'thegem'),
			'param_name' => 'label_prev',
			'dependency' => array(
				'element' => 'label',
				'value' => '1'
			),
            'std' => 'Previous Post',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Next Label', 'thegem'),
			'param_name' => 'label_next',
			'dependency' => array(
				'element' => 'label',
				'value' => '1'
			),
			'std' => 'Next Post',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'label_text_style',
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
			'dependency' => array(
				'element' => 'label',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'label_text_font_weight',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Thin', 'thegem') => 'light',
			),
			'dependency' => array(
				'element' => 'label',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'label_text_letter_spacing',
			'dependency' => array(
				'element' => 'label',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Transform', 'thegem'),
			'param_name' => 'label_text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'dependency' => array(
				'element' => 'label',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'label_text_color',
			'dependency' => array(
				'element' => 'label',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color on Hover', 'thegem'),
			'param_name' => 'label_text_color_hover',
			'dependency' => array(
				'element' => 'label',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		return $result;
	}
 
	public function set_title_params() {
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Post Title', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
        
        $result[] = array(
	        'type' => 'checkbox',
	        'heading' => __('Post Title', 'thegem'),
	        'param_name' => 'title',
	        'value' => array(__('Yes', 'thegem') => '1'),
	        'std' => '1',
	        'edit_field_class' => 'vc_column vc_col-sm-12',
	        'group' => $group
        );
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'title_text_style',
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
			'dependency' => array(
				'element' => 'title',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'title_text_font_weight',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Thin', 'thegem') => 'light',
			),
			'dependency' => array(
				'element' => 'title',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'title_text_letter_spacing',
			'dependency' => array(
				'element' => 'title',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Transform', 'thegem'),
			'param_name' => 'title_text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'dependency' => array(
				'element' => 'title',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'title_text_color',
			'dependency' => array(
				'element' => 'title',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color on Hover', 'thegem'),
			'param_name' => 'title_text_color_hover',
			'dependency' => array(
				'element' => 'title',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		return $result;
	}
 
	public function set_arrows_params() {
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Arrows', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
        
        $result[] = array(
	        'type' => 'checkbox',
	        'heading' => __('Arrows', 'thegem'),
	        'param_name' => 'arrows',
	        'value' => array(__('Yes', 'thegem') => '1'),
	        'std' => '1',
	        'edit_field_class' => 'vc_column vc_col-sm-4',
	        'group' => $group
        );
		
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Previous Arrow', 'thegem'),
			'param_name' => 'arrow_prev',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'dependency' => array(
				'element' => 'arrows',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Next Arrow', 'thegem'),
			'param_name' => 'arrow_next',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'dependency' => array(
				'element' => 'arrows',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Arrows Type', 'thegem'),
			'param_name' => 'arrows_type',
			'value' => array(
				__('Simple', 'thegem') => 'simple',
				__('Round Border', 'thegem') => 'round',
				__('Square Border', 'thegem') => 'square',
			),
            'std' => 'simple',
			'dependency' => array(
				'element' => 'arrows',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Spacing', 'thegem'),
			'param_name' => 'arrows_spacing',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Width', 'thegem'),
			'param_name' => 'arrows_width',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Height', 'thegem'),
			'param_name' => 'arrows_height',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Width', 'thegem'),
			'param_name' => 'arrows_border_width',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Radius', 'thegem'),
			'param_name' => 'arrows_border_radius',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Color', 'thegem'),
			'param_name' => 'arrows_color',
			'dependency' => array(
				'element' => 'arrows',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Color on Hover', 'thegem'),
			'param_name' => 'arrows_color_hover',
			'dependency' => array(
				'element' => 'arrows',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color', 'thegem'),
			'param_name' => 'arrows_background_color',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color on Hover', 'thegem'),
			'param_name' => 'arrows_background_color_hover',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color', 'thegem'),
			'param_name' => 'arrows_border_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color on Hover', 'thegem'),
			'param_name' => 'arrows_border_color_hover',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'group' => $group
		);
		
		return $result;
	}
	
	public function shortcode_settings() {
		
		return array(
			'name' => __('Post Navigation', 'thegem'),
			'base' => 'thegem_te_post_navigation',
			'icon' => 'thegem-icon-wpb-ui-element-post-navigation',
			'category' => $this->is_template() ? __('Single Post Builder', 'thegem') : __('Single post', 'thegem'),
			'description' => __('Post Navigation (Single Post Builder)', 'thegem'),
			'params' => array_merge(
			
			    /* General - Layout */
				$this->set_layout_params(),
                
                /* General - Label */
				$this->set_label_params(),
                
                /* General - Title */
				$this->set_title_params(),
                
                /* General - Arrows */
				$this->set_arrows_params(),
				
				/* Extra Options */
				thegem_set_elements_extra_options(),
				
				/* Responsive Options */
				thegem_set_elements_responsive_options()
			),
		);
	}
}

$templates_elements['thegem_te_post_navigation'] = new TheGem_Template_Element_Post_Navigation();
$templates_elements['thegem_te_post_navigation']->init();
