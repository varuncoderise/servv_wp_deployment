<?php

class TheGem_Template_Element_Post_Author extends TheGem_Single_Post_Template_Element {
	
	public function __construct() {
	}
	
	public function get_name() {
		return 'thegem_te_post_author';
	}
	
	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'alignment' => 'left',
			'panel_padding_desktop_top' => '',
			'panel_padding_desktop_bottom' => '',
			'panel_padding_desktop_left' => '',
			'panel_padding_desktop_right' => '',
			'panel_padding_tablet_top' => '',
			'panel_padding_tablet_bottom' => '',
			'panel_padding_tablet_left' => '',
			'panel_padding_tablet_right' => '',
			'panel_padding_mobile_top' => '',
			'panel_padding_mobile_bottom' => '',
			'panel_padding_mobile_left' => '',
			'panel_padding_mobile_right' => '',
			'panel_border_width' => '',
			'panel_border_radius' => '',
			'panel_background_color' => '',
			'panel_border_color' => '',
			'avatar' => '1',
			'avatar_size' => '',
			'author_link' => '1',
			'name' => '1',
			'name_font_style' => '',
			'name_font_weight' => '',
			'name_letter_spacing' => '',
			'name_text_transform' => '',
			'name_color' => '',
			'desc' => '1',
			'desc_max_width' => '',
			'desc_font_style' => '',
			'desc_font_weight' => '',
			'desc_letter_spacing' => '',
			'desc_text_transform' => '',
			'desc_color' => '',
			'link' => '1',
			'link_label' => 'More posts by',
			'link_font_style' => '',
			'link_font_weight' => '',
			'link_letter_spacing' => '',
			'link_text_transform' => '',
			'link_color' => '',
			'link_color_hover' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_post_author');
  
		// Init Title
		ob_start();
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$single_post = thegem_templates_init_post();
  
		$user_id = $single_post->post_author;
		$user_data = get_userdata( $user_id );
		
		if (empty($single_post) || empty($user_data)) {
			ob_end_clean();
			return thegem_templates_close_single_post($this->get_name(), $this->shortcode_settings(), '');
		}
  
		$alignment = 'post-author--'.$params['alignment'];
		$params['element_class'] = implode(' ', array(
			$alignment,
			$params['element_class'],
			thegem_templates_responsive_options_output($params)
		));
		$name_styled = implode(' ', array($params['name_font_style'], $params['name_font_weight']));
		$desc_styled = implode(' ', array($params['desc_font_style'], $params['desc_font_weight']));
		$link_styled = implode(' ', array($params['link_font_style'], $params['link_font_weight']));
		
		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?= esc_attr($params['element_id']); ?>"<?php endif; ?> class="thegem-te-post-author <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
            <div class="post-author">
                <?php if (!empty($params['avatar'])):
	                $size = !empty($params['avatar_size']) ? $params['avatar_size'] : 100;
                ?>
                    <?php if ( !empty($params['author_link']) && get_the_author_meta('url', $user_id) ) : ?>
                        <a href="<?= esc_url( get_the_author_meta('url', $user_id) ); ?>" class="post-author__avatar">
                            <?= get_avatar( $user_id, $size ); ?>
                        </a>
                    <?php else : ?>
                        <div class="post-author__avatar"><?= get_avatar( $user_id, $size ); ?></div>
                    <?php endif; ?>
		        <?php endif; ?>
                
                <div class="post-author__info">
                    <?php if (!empty($params['name'])): ?>
                        <div class="post-author__name">
                            <span class="<?= $name_styled ?>"><?= get_the_author_meta('display_name', $user_id) ?></span>
                        </div>
                    <?php endif; ?>
		
		            <?php if (!empty($params['desc'])): ?>
                        <div class="post-author__desc">
                            <p class="<?= $desc_styled ?>"><?= do_shortcode(nl2br(get_the_author_meta('description', $user_id))); ?></p>
                        </div>
		            <?php endif; ?>
		
		            <?php if (!empty($params['link'])): ?>
                        <div class="post-author__link">
                            <a href="<?= esc_url(get_author_posts_url( $user_id )); ?>" class="<?= $link_styled ?>">
	                            <?php printf(esc_html__(''.$params['link_label'].' %s', 'thegem'), $user_data->data->display_name); ?>
                            </a>
                        </div>
		            <?php endif; ?>
                </div>
            </div>
        </div>
		
		<?php
		//Custom Styles
		$customize = '.thegem-te-post-author.'.$uniqid;
		$custom_css = '';
		$resolution = array('desktop', 'tablet', 'mobile');
		$directions = array('top', 'bottom', 'left', 'right');
		
		// Layout Styles
		if (!empty($params['panel_border_width']) || $params['panel_border_width'] == 0) {
			$custom_css .= $customize.' .post-author {border-width: ' . $params['panel_border_width'] . 'px;}';
		}
		if (!empty($params['panel_border_radius']) || $params['panel_border_radius'] == 0) {
			$custom_css .= $customize.' .post-author {border-radius: ' . $params['panel_border_radius'] . 'px;}';
		}
		if (!empty($params['panel_background_color'])) {
			$custom_css .= $customize.' .post-author {background-color: ' . $params['panel_background_color'] . ';}';
		}
		if (!empty($params['panel_border_color'])) {
			$custom_css .= $customize.' .post-author {border-color: ' . $params['panel_border_color'] . ';}';
		}
		foreach ($resolution as $res) {
			foreach ($directions as $dir) {
				if (!empty($params['panel_padding'.'_'.$res.'_'.$dir]) || strcmp($params['panel_padding'.'_'.$res.'_'.$dir], '0') === 0) {
					$result = str_replace(' ', '', $params['panel_padding'.'_'.$res.'_'.$dir]);
					$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
					if ($res == 'desktop') {
						$custom_css .= $customize.' .post-author {padding-'.$dir.':'.$result.$unit.';}';
					} else {
						$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' .post-author {padding-'.$dir.':'.$result.$unit.';}}';
					}
				}
			}
		}
		
		// Avatar Styles
		if (!empty($params['avatar_size'])) {
			$custom_css .= $customize.'.post-author--center .post-author__avatar {top: -' . round($params['avatar_size'] / 2, 2) . 'px;}';
		}
		
		// Name Styles
		if ($params['name_letter_spacing'] != '') {
			$custom_css .= $customize.' .post-author__name span {letter-spacing: ' . $params['name_letter_spacing'] . 'px;}';
		}
		if ($params['name_text_transform'] != '') {
			$custom_css .= $customize.' .post-author__name span {text-transform: ' . $params['name_text_transform'] . ';}';
		}
		if (!empty($params['name_color'])) {
			$custom_css .= $customize.' .post-author__name span {color: ' . $params['name_color'] . ';}';
		}
  
		// Description Styles
		if (!empty($params['desc_max_width'])) {
			$custom_css .= $customize.' .post-author__desc {max-width: ' . $params['desc_max_width'] . 'px;}';
		}
		if ($params['desc_letter_spacing'] != '') {
			$custom_css .= $customize.' .post-author__desc p {letter-spacing: ' . $params['desc_letter_spacing'] . 'px;}';
		}
		if ($params['desc_text_transform'] != '') {
			$custom_css .= $customize.' .post-author__desc p {text-transform: ' . $params['desc_text_transform'] . ';}';
		}
		if (!empty($params['desc_color'])) {
			$custom_css .= $customize.' .post-author__desc p {color: ' . $params['desc_color'] . ';}';
		}
		
		// Link Styles
		if ($params['link_letter_spacing'] != '') {
			$custom_css .= $customize.' .post-author__link a {letter-spacing: ' . $params['link_letter_spacing'] . 'px;}';
		}
		if ($params['link_text_transform'] != '') {
			$custom_css .= $customize.' .post-author__link a {text-transform: ' . $params['link_text_transform'] . ';}';
		}
		if (!empty($params['link_color'])) {
			$custom_css .= $customize.' .post-author__link a {color: ' . $params['link_color'] . ';}';
		}
		if (!empty($params['link_color_hover'])) {
			$custom_css .= $customize.' .post-author__link a:hover {color: ' . $params['link_color_hover'] . ';}';
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
		$resolutions = array('desktop', 'tablet', 'mobile');
		$directions = array('top', 'bottom', 'left', 'right');
		
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
				)
			),
			'std' => 'left',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			"type" => "textfield",
			'heading' => __('Border Width', 'thegem'),
			'param_name' => 'panel_border_width',
			"edit_field_class" => "vc_column vc_col-sm-6",
			'group' => $group
		);
		
		$result[] = array(
			"type" => "textfield",
			'heading' => __('Border Radius', 'thegem'),
			'param_name' => 'panel_border_radius',
			"edit_field_class" => "vc_column vc_col-sm-6",
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color', 'thegem'),
			'param_name' => 'panel_background_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color', 'thegem'),
			'param_name' => 'panel_border_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		foreach ($resolutions as $res) {
			$result[] = array(
				'type' => 'thegem_delimeter_heading_two_level',
				'heading' => __(''.ucfirst($res).' '.'Paddings', 'thegem'),
				'param_name' => 'delimiter_heading_two_level_panel',
				'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
				'group' => $group
			);
			foreach ($directions as $dir) {
				$result[] = array(
					'type' => 'textfield',
					'heading' => __(ucfirst($dir), 'thegem'),
					'param_name' => 'panel_padding_'.$res.'_'.$dir,
					'value' => '',
					'edit_field_class' => 'vc_column vc_col-sm-3',
					'group' => $group
				);
			}
		}
		
		return $result;
	}
 
	public function set_author_params() {
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Avatar', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Avatar', 'thegem'),
			'param_name' => 'avatar',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
  
		$result[] = array(
            'type' => 'textfield',
            'heading' => __('Size', 'thegem'),
            'param_name' => 'avatar_size',
            'dependency' => array(
                'element' => 'avatar',
                'value' => '1'
            ),
            'edit_field_class' => 'vc_column vc_col-sm-8',
			'group' => $group
        );
		
		$result[] = array(
            'type' => 'checkbox',
            'heading' => __('Link', 'thegem'),
            'param_name' => 'author_link',
            'value' => array(__('Yes', 'thegem') => '1'),
            'std' => '1',
			'dependency' => array(
				'element' => 'avatar',
				'value' => '1'
			),
            'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
        );
		
		return $result;
	}
	
	public function set_name_params() {
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Name', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Name', 'thegem'),
			'param_name' => 'name',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'name_font_style',
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
				'element' => 'name',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'name_font_weight',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Thin', 'thegem') => 'light',
			),
			'dependency' => array(
				'element' => 'name',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'name_letter_spacing',
			'dependency' => array(
				'element' => 'name',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Transform', 'thegem'),
			'param_name' => 'name_text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'dependency' => array(
				'element' => 'name',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'name_color',
			'dependency' => array(
				'element' => 'name',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		return $result;
	}
 
	public function set_desc_params() {
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Description', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Description', 'thegem'),
			'param_name' => 'desc',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Max Width', 'thegem'),
			'param_name' => 'desc_max_width',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'desc',
				'value' => '1'
			),
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'desc_font_style',
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
				'element' => 'desc',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'desc_font_weight',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Thin', 'thegem') => 'light',
			),
			'dependency' => array(
				'element' => 'desc',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'desc_letter_spacing',
			'dependency' => array(
				'element' => 'desc',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Transform', 'thegem'),
			'param_name' => 'desc_text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'dependency' => array(
				'element' => 'desc',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'desc_color',
			'dependency' => array(
				'element' => 'desc',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		return $result;
	}
	
	public function set_link_params() {
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Link', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Link', 'thegem'),
			'param_name' => 'link',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Label', 'thegem'),
			'param_name' => 'link_label',
			'edit_field_class' => 'vc_column vc_col-sm-6',
            'std' => 'More posts by',
			'dependency' => array(
				'element' => 'link',
				'value' => '1'
			),
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'link_font_style',
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
				'element' => 'link',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'link_font_weight',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Thin', 'thegem') => 'light',
			),
			'dependency' => array(
				'element' => 'link',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'link_letter_spacing',
			'dependency' => array(
				'element' => 'link',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Transform', 'thegem'),
			'param_name' => 'link_text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'dependency' => array(
				'element' => 'link',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'link_color',
			'dependency' => array(
				'element' => 'link',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color on Hover', 'thegem'),
			'param_name' => 'link_color_hover',
			'dependency' => array(
				'element' => 'link',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		return $result;
	}
	
	public function shortcode_settings() {

		return array(
			'name' => __('Author Box', 'thegem'),
			'base' => 'thegem_te_post_author',
			'icon' => 'thegem-icon-wpb-ui-element-post-author',
			'category' => $this->is_template() ? __('Single Post Builder', 'thegem') : __('Single post', 'thegem'),
			'description' => __('Author Box (Single Post Builder)', 'thegem'),
			'params' => array_merge(
			
			    /* General - Layout */
				$this->set_layout_params(),
                
                /* General - Author */
				$this->set_author_params(),
                
                /* General - Name */
				$this->set_name_params(),
				
				/* General - Description */
				$this->set_desc_params(),
				
				/* General - Link */
				$this->set_link_params(),
				
				/* Extra Options */
				thegem_set_elements_extra_options(),
				
				/* Responsive Options */
				thegem_set_elements_responsive_options()
			),
		);
	}
}

$templates_elements['thegem_te_post_author'] = new TheGem_Template_Element_Post_Author();
$templates_elements['thegem_te_post_author']->init();
