<?php

class TheGem_Template_Element_Post_Comments extends TheGem_Single_Post_Template_Element {
	public $show_in_posts = false;
	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_post_comments';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'separator' => '1',
			'separator_weight' => '',
			'separator_color' => '',
			'panel_padding_desktop_top' => '',
			'panel_padding_desktop_bottom' => '',
			'panel_padding_tablet_top' => '',
			'panel_padding_tablet_bottom' => '',
			'panel_padding_mobile_top' => '',
			'panel_padding_mobile_bottom' => '',
			'title_list' => '1',
			'title_list_label' => 'Comments',
			'title_list_separator' => '',
			'title_form' => '1',
			'title_form_label' => 'Add Comment',
			'title_form_separator' => '',
			'title_font_style' => 'title-h4',
			'title_font_weight' => '',
			'title_letter_spacing' => '',
			'title_text_transform' => 'none',
			'title_color' => '',
			'avatar' => '1',
			'avatar_size' => '',
			'avatar_spacing' => '',
			'name' => '1',
			'name_font_style' => 'default-title',
			'name_font_weight' => '',
			'name_letter_spacing' => '',
			'name_text_transform' => '',
			'name_link' => '1',
			'name_color' => '',
			'name_color_hover' => '',
			'reply' => '1',
			'reply_label' => 'Reply',
			'reply_font_style' => 'default-title',
			'reply_font_weight' => '',
			'reply_letter_spacing' => '',
			'reply_text_transform' => '',
			'reply_color' => '',
			'reply_color_hover' => '',
			'date' => '1',
			'date_font_style' => 'default-title',
			'date_font_weight' => '',
			'date_letter_spacing' => '',
			'date_text_transform' => '',
			'date_link' => '1',
			'date_color' => '',
			'date_color_hover' => '',
			'desc' => '1',
			'desc_max_width' => '',
			'desc_font_style' => 'default-title',
			'desc_font_weight' => '',
			'desc_letter_spacing' => '',
			'desc_text_transform' => '',
			'desc_color' => '',
			'label_text_color' => '',
			'input_text_color' => '',
			'input_background_color' => '',
			'input_border_color' => '',
			'input_marker_color' => '',
			'input_placeholder_color' => '',
			'input_border_radius' => '',
			'input_checkbox_border_radius' => '',
			'send_btn_size' => 'small',
			'send_btn_alignment' => 'left',
			'send_btn_border_width' => '',
			'send_btn_border_radius' => '',
			'send_btn_text_color' => '',
			'send_btn_text_color_hover' => '',
			'send_btn_background_color' => '',
			'send_btn_background_color_hover' => '',
			'send_btn_border_color' => '',
			'send_btn_border_color_hover' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_post_comments');

		// Init Content
		ob_start();
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$single_post = thegem_templates_init_post();

		$comments_count = get_comments_number( $single_post );
		if (empty($single_post) || (function_exists('vc_is_page_editable') && vc_is_page_editable() && !comments_open() && $comments_count == '0')) {
			ob_end_clean();
			return thegem_templates_close_single_post($this->get_name(), $this->shortcode_settings(), '');
		}

		$separator = empty($params['separator']) ? 'post-comments--separator-hide' : '';
		$title_form = empty($params['title_form']) ? 'post-comments--title-hide' : '';
		$btn_alignment = !empty($params['send_btn_alignment']) ? 'post-comments-btn--'.$params['send_btn_alignment'] : '';
		$params['element_class'] = implode(' ', array(
			$separator, $title_form, $btn_alignment,
			$params['element_class'],
			thegem_templates_responsive_options_output($params)
		));

		$params['title_styled'] = implode(' ', array($params['title_font_style'], $params['title_font_weight']));
		$params['name_styled'] = implode(' ', array($params['name_font_style'], $params['name_font_weight']));
		$params['reply_styled'] = implode(' ', array($params['reply_font_style'], $params['reply_font_weight']));
		$params['date_styled'] = implode(' ', array($params['date_font_style'], $params['date_font_weight']));
		$params['desc_styled'] = implode(' ', array($params['desc_font_style'], $params['desc_font_weight']));

		global $thegem_comments_params;
		$thegem_comments_params = $params;

		?>

        <div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?> class="thegem-te-post-comments <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
            <?php comments_template('/comments.php', $thegem_comments_params); unset($thegem_comments_params ); ?>
        </div>

		<?php
		//Custom Styles
		$customize = '.thegem-te-post-comments.'.$uniqid;
		$custom_css = '';
		$resolution = array('desktop', 'tablet', 'mobile');
		$directions = array('top', 'bottom');

		// Layout Styles
		if (!empty($params['separator_weight'])) {
			$custom_css .= $customize.' .post-comments__list .comment {border-width: ' . $params['separator_weight'] . 'px;}';
			$custom_css .= $customize.' .comment-respond {border-width: ' . $params['separator_weight'] . 'px;}';
		}
		if (!empty($params['separator_color'])) {
			$custom_css .= $customize.' .post-comments__list .comment {border-color: ' . $params['separator_color'] . ';}';
			$custom_css .= $customize.' .comment-respond {border-color: ' . $params['separator_color'] . ';}';
		}
		foreach ($resolution as $res) {
			foreach ($directions as $dir) {
				if (!empty($params['panel_padding'.'_'.$res.'_'.$dir]) || strcmp($params['panel_padding'.'_'.$res.'_'.$dir], '0') === 0) {
					$result = str_replace(' ', '', $params['panel_padding'.'_'.$res.'_'.$dir]);
					$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
					if ($res == 'desktop') {
						$custom_css .= $customize.' .post-comment__wrap {padding-'.$dir.':'.$result.$unit.';}';
					} else {
						$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' .post-comment__wrap {padding-'.$dir.':'.$result.$unit.';}}';
					}
				}
			}
		}

		// Title Styles
		if ($params['title_letter_spacing'] != '') {
			$custom_css .= $customize.' h2.post-comments__title span {letter-spacing: ' . $params['title_letter_spacing'] . 'px;}';
			$custom_css .= $customize.' h3.comment-reply-title span {letter-spacing: ' . $params['title_letter_spacing'] . 'px;}';
			$custom_css .= $customize.' h3.comment-reply-title a {letter-spacing: ' . $params['title_letter_spacing'] . 'px;}';
		}
		if ($params['title_text_transform'] != '') {
			$custom_css .= $customize.' h2.post-comments__title span {text-transform: ' . $params['title_text_transform'] . ';}';
			$custom_css .= $customize.' h3.comment-reply-title span {text-transform: ' . $params['title_text_transform'] . ';}';
			$custom_css .= $customize.' h3.comment-reply-title a {text-transform: ' . $params['title_text_transform'] . ';}';
		}
		if (!empty($params['title_list_separator']) || $params['title_list_separator'] == '0') {
			$custom_css .= $customize.' h2.post-comments__title {padding-bottom: ' . $params['title_list_separator'] . 'px;}';
		}
		if (!empty($params['title_form_separator']) || $params['title_form_separator'] == '0') {
			$custom_css .= $customize.' h3.comment-reply-title {padding-bottom: ' . $params['title_form_separator'] . 'px;}';
		}
		if (!empty($params['title_color'])) {
			$custom_css .= $customize.' h2.post-comments__title span {color: ' . $params['title_color'] . ';}';
			$custom_css .= $customize.' h3.comment-reply-title span {color: ' . $params['title_color'] . ';}';
			$custom_css .= $customize.' h3.comment-reply-title a {color: ' . $params['title_color'] . ';}';
			$custom_css .= $customize.' .no-comments {color: ' . $params['title_color'] . ';}';
		}

		// Avatar Styles
		if (!empty($params['avatar_spacing'])) {
			$custom_css .= $customize.' .post-comment__avatar {margin-right: ' . $params['avatar_spacing'] . 'px;}';
		}

		// Name Styles
		if ($params['name_letter_spacing'] != '') {
			$custom_css .= $customize.' .post-comment__name-author {letter-spacing: ' . $params['name_letter_spacing'] . 'px;}';
		}
		if ($params['name_text_transform'] != '') {
			$custom_css .= $customize.' .post-comment__name-author {text-transform: ' . $params['name_text_transform'] . ';}';
		}
		if (!empty($params['name_color'])) {
			$custom_css .= $customize.' .post-comment__name-author {color: ' . $params['name_color'] . ';}';
			$custom_css .= $customize.' .post-comment__name-author a {color: ' . $params['name_color'] . ';}';
		}
		if (!empty($params['name_color_hover'])) {
			$custom_css .= $customize.' .post-comment__name-author a:hover {color: ' . $params['name_color_hover'] . ';}';
		}

		// Reply Styles
		if ($params['reply_letter_spacing'] != '') {
			$custom_css .= $customize.' .post-comment__name-reply {letter-spacing: ' . $params['reply_letter_spacing'] . 'px;}';
		}
		if ($params['reply_text_transform'] != '') {
			$custom_css .= $customize.' .post-comment__name-reply {text-transform: ' . $params['reply_text_transform'] . ';}';
		}
		if (!empty($params['reply_color'])) {
			$custom_css .= $customize.' .post-comment__name-reply a {color: ' . $params['reply_color'] . ';}';
		}
		if (!empty($params['reply_color_hover'])) {
			$custom_css .= $customize.' .post-comment__name-reply a:hover {color: ' . $params['reply_color_hover'] . ';}';
		}

		// Date Styles
		if ($params['date_letter_spacing'] != '') {
			$custom_css .= $customize.' .post-comment__date {letter-spacing: ' . $params['date_letter_spacing'] . 'px;}';
		}
		if ($params['date_text_transform'] != '') {
			$custom_css .= $customize.' .post-comment__date {text-transform: ' . $params['date_text_transform'] . ';}';
		}
		if (!empty($params['date_color'])) {
			$custom_css .= $customize.' .post-comment__date {color: ' . $params['date_color'] . ';}';
			$custom_css .= $customize.' .post-comment__date a {color: ' . $params['date_color'] . ';}';
		}
		if (!empty($params['date_color_hover'])) {
			$custom_css .= $customize.' .post-comment__date a:hover {color: ' . $params['date_color_hover'] . ';}';
		}

		// Description Styles
		if (!empty($params['desc_max_width'])) {
			$custom_css .= $customize.' .post-comment__desc {max-width: ' . $params['desc_max_width'] . 'px;}';
		}
		if ($params['desc_letter_spacing'] != '') {
			$custom_css .= $customize.' .post-comment__desc {letter-spacing: ' . $params['desc_letter_spacing'] . 'px;}';
			$custom_css .= $customize.' .post-comment__approved {letter-spacing: ' . $params['desc_letter_spacing'] . 'px;}';
		}
		if ($params['desc_text_transform'] != '') {
			$custom_css .= $customize.' .post-comment__desc {text-transform: ' . $params['desc_text_transform'] . ';}';
			$custom_css .= $customize.' .post-comment__approved {text-transform: ' . $params['desc_text_transform'] . ';}';
		}
		if (!empty($params['desc_color'])) {
			$custom_css .= $customize.' .post-comment__desc {color: ' . $params['desc_color'] . ';}';
			$custom_css .= $customize.' .post-comment__approved {color: ' . $params['desc_color'] . ';}';
		}

		// Form Styles
		if (!empty($params['label_text_color'])) {
			$custom_css .= $customize.' .comment-form label {color: ' . $params['label_text_color'] . ' !important;}';
		}
		if (!empty($params['input_text_color'])) {
			$custom_css .= $customize.' .comment-form input {color: ' . $params['input_text_color'] . ' !important;}';
			$custom_css .= $customize.' .comment-form textarea {color: ' . $params['input_text_color'] . ' !important;}';
		}
		if (!empty($params['input_background_color'])) {
			$custom_css .= $customize.' .comment-form input {background-color: ' . $params['input_background_color'] . ' !important;}';
			$custom_css .= $customize.' .comment-form textarea {background-color: ' . $params['input_background_color'] . ' !important;}';
			$custom_css .= $customize.' .comment-form .checkbox-sign {background-color: ' . $params['input_background_color'] . ' !important;}';
		}
		if (!empty($params['input_border_color'])) {
			$custom_css .= $customize.' .comment-form input {border-color: ' . $params['input_border_color'] . ' !important;}';
			$custom_css .= $customize.' .comment-form textarea {border-color: ' . $params['input_border_color'] . ' !important;}';
			$custom_css .= $customize.' .comment-form .checkbox-sign {border-color: ' . $params['input_border_color'] . ' !important;}';
		}
		if (!empty($params['input_marker_color'])) {
			$custom_css .= $customize.' .comment-form .checkbox-sign.checked::before {color: ' . $params['input_marker_color'] . ' !important;}';
		}
		if (!empty($params['input_border_radius']) || $params['input_border_radius'] == '0') {
			$custom_css .= $customize.' .comment-form input {border-radius: ' . $params['input_border_radius'] . 'px !important;}';
			$custom_css .= $customize.' .comment-form textarea {border-radius: ' . $params['input_border_radius'] . 'px !important;}';
		}
		if (!empty($params['input_checkbox_border_radius']) || $params['input_checkbox_border_radius'] == '0') {
			$custom_css .= $customize.' .comment-form .checkbox-sign {border-radius: ' . $params['input_checkbox_border_radius'] . 'px !important;}';
		}

		// Order Button Styles
		if (!empty($params['send_btn_border_width'])) {
			$add_to_cart_btn_line_height = intval(40 - $params['send_btn_border_width']*2);
			$custom_css .= $customize.' .form-submit .gem-button {border-width:'.$params['send_btn_border_width'].'px !important;}';
			$custom_css .= $customize.' .form-submit .gem-button {line-height: '.$add_to_cart_btn_line_height.'px !important;}';
		}
		if (!empty($params['send_btn_border_radius']) || $params['send_btn_border_radius'] == '0') {
			$custom_css .= $customize.' .form-submit .gem-button {border-radius:'.$params['send_btn_border_radius'].'px !important;}';
		}
		if (!empty($params['send_btn_text_color'])) {
			$custom_css .= $customize.' .form-submit .gem-button {color:'.$params['send_btn_text_color'].'!important;}';
		}
		if (!empty($params['send_btn_text_color_hover'])) {
			$custom_css .= $customize.' .form-submit .gem-button:hover {color:'.$params['send_btn_text_color_hover'].'!important;}';
		}
		if (!empty($params['send_btn_background_color'])) {
			$custom_css .= $customize.' .form-submit .gem-button {background-color:'.$params['send_btn_background_color'].'!important;}';
		}
		if (!empty($params['send_btn_background_color_hover'])) {
			$custom_css .= $customize.' .form-submit .gem-button:hover {background-color:'.$params['send_btn_background_color_hover'].'!important;}';
		}
		if (!empty($params['send_btn_border_color'])) {
			$custom_css .= $customize.' .form-submit .gem-button {border-color:'.$params['send_btn_border_color'].'!important;}';
		}
		if (!empty($params['send_btn_border_color_hover'])) {
			$custom_css .= $customize.' .form-submit .gem-button:hover {border-color:'.$params['send_btn_border_color_hover'].'!important;}';
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
		$directions = array('top', 'bottom');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('General', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Separator', 'thegem'),
			'param_name' => 'separator',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			"type" => "textfield",
			'heading' => __('Separator Weight', 'thegem'),
			'param_name' => 'separator_weight',
			'dependency' => array(
				'element' => 'separator',
				'value' => '1'
			),
			"edit_field_class" => "vc_column vc_col-sm-6",
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Separator Color', 'thegem'),
			'param_name' => 'separator_color',
			'dependency' => array(
				'element' => 'separator',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		foreach ($resolutions as $res) {
			$result[] = array(
				'type' => 'thegem_delimeter_heading_two_level',
				'heading' => __(''.ucfirst($res).' '.'Comment Paddings', 'thegem'),
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
					'edit_field_class' => 'vc_column vc_col-sm-6',
					'group' => $group
				);
			}
		}

		return $result;
	}

	public function set_title_params() {
		$result = array();
		$group = __('General', 'thegem');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Titles', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);

		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('Title Comments', 'thegem'),
			'param_name' => 'delimiter_heading_two_level_panel',
			'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Title Comments', 'thegem'),
			'param_name' => 'title_list',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Title Comments Text', 'thegem'),
			'param_name' => 'title_list_label',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'std' => 'Comments',
			'dependency' => array(
				'element' => 'title_list',
				'value' => '1'
			),
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Title Comments Spacing', 'thegem'),
			'param_name' => 'title_list_separator',
			'dependency' => array(
				'element' => 'title_list',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('Title Form', 'thegem'),
			'param_name' => 'delimiter_heading_two_level_panel',
			'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Title Form', 'thegem'),
			'param_name' => 'title_form',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Title Form Text', 'thegem'),
			'param_name' => 'title_form_label',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'std' => 'Add Comment',
			'dependency' => array(
				'element' => 'title_form',
				'value' => '1'
			),
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Title Form Spacing', 'thegem'),
			'param_name' => 'title_form_separator',
			'dependency' => array(
				'element' => 'title_form',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('Titles Presets', 'thegem'),
			'param_name' => 'delimiter_heading_two_level_panel',
			'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'title_font_style',
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
            'std' => 'title-h4',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'title_font_weight',
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
			'param_name' => 'title_letter_spacing',
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
            'std' => 'none',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'title_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

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
			'edit_field_class' => 'vc_column vc_col-sm-12',
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
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Spacing', 'thegem'),
			'param_name' => 'avatar_spacing',
			'dependency' => array(
				'element' => 'avatar',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
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
				__('Default', 'thegem') => 'default-title',
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
            'std' => 'default-title',
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
			'type' => 'checkbox',
			'heading' => __('Name Link', 'thegem'),
			'param_name' => 'name_link',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'dependency' => array(
				'element' => 'name',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
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
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color on Hover', 'thegem'),
			'param_name' => 'name_color_hover',
			'dependency' => array(
				'element' => 'name',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		return $result;
	}

	public function set_reply_params() {
		$result = array();
		$group = __('General', 'thegem');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Reply', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Reply', 'thegem'),
			'param_name' => 'reply',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Label', 'thegem'),
			'param_name' => 'reply_label',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'std' => 'Reply',
			'dependency' => array(
				'element' => 'reply',
				'value' => '1'
			),
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'reply_font_style',
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
				'element' => 'reply',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'reply_font_weight',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Thin', 'thegem') => 'light',
			),
			'dependency' => array(
				'element' => 'reply',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'reply_letter_spacing',
			'dependency' => array(
				'element' => 'reply',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Transform', 'thegem'),
			'param_name' => 'reply_text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'dependency' => array(
				'element' => 'reply',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'reply_color',
			'dependency' => array(
				'element' => 'reply',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color on Hover', 'thegem'),
			'param_name' => 'reply_color_hover',
			'dependency' => array(
				'element' => 'reply',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		return $result;
	}

	public function set_date_params() {
		$result = array();
		$group = __('General', 'thegem');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Date', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Date', 'thegem'),
			'param_name' => 'date',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'date_font_style',
			'value' => array(
				__('Default', 'thegem') => 'default-title',
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
            'std' => 'default-title',
			'dependency' => array(
				'element' => 'date',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'date_font_weight',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Thin', 'thegem') => 'light',
			),
			'dependency' => array(
				'element' => 'date',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'date_letter_spacing',
			'dependency' => array(
				'element' => 'date',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Transform', 'thegem'),
			'param_name' => 'date_text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'dependency' => array(
				'element' => 'date',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Date link', 'thegem'),
			'param_name' => 'date_link',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'dependency' => array(
				'element' => 'date',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'date_color',
			'dependency' => array(
				'element' => 'date',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color on Hover', 'thegem'),
			'param_name' => 'date_color_hover',
			'dependency' => array(
				'element' => 'date',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
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
				__('Default', 'thegem') => 'default-title',
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
			'std' => 'default-title',
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

	public function set_form_params() {
		$group = __('General', 'thegem');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Form', 'thegem'),
			'param_name' => 'delimiter_heading_description',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Label Text Color', 'thegem'),
			'param_name' => 'label_text_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Input Text Color', 'thegem'),
			'param_name' => 'input_text_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Input Background Color', 'thegem'),
			'param_name' => 'input_background_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Input Border Color', 'thegem'),
			'param_name' => 'input_border_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Input Marker Color', 'thegem'),
			'param_name' => 'input_marker_color',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Radius', 'thegem'),
			'param_name' => 'input_border_radius',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Checkbox Border Radius', 'thegem'),
			'param_name' => 'input_checkbox_border_radius',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		return $result;
	}

	public function set_form_btn_params() {
		$result = array();
		$group = __('General', 'thegem');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('"Send Comment" Button', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Button Size', 'thegem'),
			'param_name' => 'send_btn_size',
			'value' => array_merge(array(
					__('Tiny', 'thegem') => 'tiny',
					__('Small', 'thegem') => 'small',
					__('Medium', 'thegem') => 'medium',
					__('Large', 'thegem') => 'large',
					__('Giant', 'thegem') => 'giant',
				)
			),
			'std' => 'small',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Button Alignment', 'thegem'),
			'param_name' => 'send_btn_alignment',
			'value' => array_merge(array(
					__('Left', 'thegem') => 'left',
					__('Center', 'thegem') => 'center',
					__('Right', 'thegem') => 'right',
					__('Fullwidth', 'thegem') => 'fullwidth',
				)
			),
			'std' => 'left',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Border Width', 'thegem'),
			'param_name' => 'send_btn_border_width',
			'value' => array_merge(array(
					__('Default', 'thegem') => '',
					__('1', 'thegem') => '1',
					__('2', 'thegem') => '2',
					__('3', 'thegem') => '3',
					__('4', 'thegem') => '4',
					__('5', 'thegem') => '5',
					__('6', 'thegem') => '6',
				)
			),
			'std' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group,
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Radius', 'thegem'),
			'param_name' => 'send_btn_border_radius',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'send_btn_text_color',
			'std' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color on Hover', 'thegem'),
			'param_name' => 'send_btn_text_color_hover',
			'std' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color', 'thegem'),
			'param_name' => 'send_btn_background_color',
			'std' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color on Hover', 'thegem'),
			'param_name' => 'send_btn_background_color_hover',
			'std' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color', 'thegem'),
			'param_name' => 'send_btn_border_color',
			'std' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color on Hover', 'thegem'),
			'param_name' => 'send_btn_border_color_hover',
			'std' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		return $result;
	}

	public function shortcode_settings() {

		return array(
			'name' => __('Post Comments', 'thegem'),
			'base' => 'thegem_te_post_comments',
			'icon' => 'thegem-icon-wpb-ui-element-post-comments',
			'category' => __('Single Post Builder', 'thegem'),
			'description' => __('Post Comments (Single Post Builder)', 'thegem'),
			'params' => array_merge(

			    /* General - Layout */
				$this->set_layout_params(),

			    /* General - Title */
				$this->set_title_params(),

				/* General - Author */
				$this->set_author_params(),

				/* General - Name */
				$this->set_name_params(),

				/* General - Reply Link */
				$this->set_reply_params(),

                /* General - Date */
				$this->set_date_params(),

				/* General - Description */
				$this->set_desc_params(),

				/* General - Form */
				$this->set_form_params(),

				/* General - Form Btn */
				$this->set_form_btn_params(),

				/* Extra Options */
				thegem_set_elements_extra_options(),

				/* Responsive Options */
				thegem_set_elements_responsive_options()
			),
		);
	}
}

$templates_elements['thegem_te_post_comments'] = new TheGem_Template_Element_Post_Comments();
$templates_elements['thegem_te_post_comments']->init();
