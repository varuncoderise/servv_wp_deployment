<?php

class TheGem_Template_Element_Countdown extends TheGem_Template_Element {

	public function __construct() {
		if ( !defined('THEGEM_TE_COUNTDOWN_URL') ) {
			define('THEGEM_TE_COUNTDOWN_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}
		wp_register_script('thegem-te-countdown', THEGEM_TE_COUNTDOWN_URL . '/js/countdown.js', array( 'jquery', 'odometr' ), '', false );
		wp_register_style('thegem-te-countdown', THEGEM_TE_COUNTDOWN_URL . '/css/countdown.css');
	}

	public function get_name() {
		return 'thegem_te_countdown';
	}

	public function head_scripts() {
		wp_enqueue_script('thegem-te-countdown');
		wp_enqueue_style('thegem-te-countdown');
		wp_add_inline_script('thegem-te-countdown', '
function thegem_te_countdown_init_time(timestamp){
	var now = new Date();
	var currentTime = now.getTime();
	var eventTime = timestamp * 1000;
	if((eventTime - currentTime) < 0){
		eventTime = currentTime;
	}
	var remTime = eventTime - currentTime;
	var s = Math.floor(remTime / 1000);
	var m = Math.floor(s / 60);
	var h = Math.floor(m / 60);
	var d = Math.floor(h / 24);
	h %= 24;
	m %= 60;
	s %= 60;
	h = h;
	m = m;
	s = s;
	return [d, h, m, s];
}
function thegem_te_countdown_update_numbers(elem){
	var elEventDate = elem.getAttribute("data-eventdate"),
		initTime = thegem_te_countdown_init_time(elEventDate),
		elD = initTime[0],
		elH = initTime[1],
		elM = initTime[2],
		elS = initTime[3];
/*		elem.querySelector(".countdown-days").innerHTML = elD;
		elem.querySelector(".countdown-hours").innerHTML = elH.toString().padStart(2, "0");
		elem.querySelector(".countdown-minutes").innerHTML = elM.toString().padStart(2, "0");
		elem.querySelector(".countdown-seconds").innerHTML = elS.toString().padStart(2, "0");*/
		if (typeof Odometer !== "undefined") {
			var odometerDays = new Odometer({ auto: false, value: elD, el: elem.querySelector(".countdown-days"), duration: 1000, theme: "minimal"});
			var odometerHours = new Odometer({ auto: false, value: elH+100, el: elem.querySelector(".countdown-hours"), duration: 1000, theme: "minimal" });
			var odometerMinutes = new Odometer({ auto: false, value: elM+100, el: elem.querySelector(".countdown-minutes"), duration: 1000, theme: "minimal" });
			var odometerSeconds = new Odometer({ auto: false, value: elS+100, el: elem.querySelector(".countdown-seconds"), duration: 1000, theme: "minimal" });
		}
}
		', 'before');
	}

	public function front_editor_scripts() {
		wp_enqueue_script('thegem-te-countdown');
		wp_enqueue_style('thegem-te-countdown');
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		extract($extract = shortcode_atts(array_merge(array(
			'eventdate' => date('d-m-Y', (time() + 84900)),
			'days_label' => '',
			'hours_label' => '',
			'minutes_label' => '',
			'seconds_label' => '',
			'style' => 'elegant_tiny',
			'weight_number' => 'default',
			'color_number' => '',
			'color_text' => '',
			'color_border' => '',
			'color_background' => '',
			'extraclass' => ''
		), thegem_templates_design_options_extract()), $atts, 'thegem_te_countdown'));

		$params = array_merge(array(
			'eventdate' => $eventdate,
			'days_label' => $days_label,
			'hours_label' => $hours_label,
			'minutes_label' => $minutes_label,
			'seconds_label' => $seconds_label,
			'style' => $style,
			'weight_number' => $weight_number,
			'color_number' => $color_number,
			'color_text' => $color_text,
			'color_border' => $color_border,
			'color_background' => $color_background,
			'extraclass' => $extraclass
		), thegem_templates_design_options_output($extract));

		$return_html = $custom_css = '';

		$uniqid = uniqid('thegem-te-countdown-').rand(1,9999);

		$eventdate_timestamp = thegem_datepickerTimeToTimestamp($eventdate);

		$font_class = 'title-h5';
		if($weight_number == 'thin') {
			$font_class .= ' countdown-title-thin';
		}

		$return_html .= '<div class="'.esc_attr($uniqid).'" '.thegem_data_editor_attribute($uniqid . '-editor').'>';
			$return_html .= '<div data-eventdate="'.esc_attr($eventdate_timestamp).'" class="thegem-te-countdown '.esc_attr($extraclass).'">';
				$return_html .= '<div class="countdown-wrapper countdown-info">';
					$return_html .= '<div class="countdown-item count-1"><div class="wrap"><span class="item-count countdown-days '. esc_attr($font_class).'">00</span><span class="item-title">' . ( !empty($days_label) ? $days_label : __('Days', 'thegem') ) . '</span></div></div>';
					$return_html .= '<div class="countdown-item count-2"><div class="wrap"><span class="item-count countdown-hours '. esc_attr($font_class).'">00</span><span class="item-title">' . ( !empty($hours_label) ? $hours_label : __('Hrs', 'thegem') ) . '</span></div></div>';
					$return_html .= '<div class="countdown-item count-3"><div class="wrap"><span class="item-count countdown-minutes '. esc_attr($font_class).'">00</span><span class="item-title">' . ( !empty($minutes_label) ? $minutes_label : __('Min', 'thegem') ) . '</span></div></div>';
					$return_html .= '<div class="countdown-item count-4"><div class="wrap"><span class="item-count countdown-seconds '. esc_attr($font_class).'">00</span><span class="item-title">' . ( !empty($seconds_label) ? $seconds_label : __('Sec', 'thegem') ) . '</span></div></div>';
				$return_html .= '</div>';
			$return_html .= '</div>';
			$return_html .= '<script type="text/javascript">if (typeof(thegem_te_countdown_update_numbers) == "function") { thegem_te_countdown_update_numbers(document.querySelector(".' . esc_attr($uniqid) . ' .thegem-te-countdown")); }</script>';
		$return_html .= '</div>';

		// Init Design Options Params
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-countdown', $params);

		if(!empty($color_number)) {
			$custom_css .= '.'.$uniqid.' .item-count {color: '.$color_number.';}';
		} else {
			$custom_css .= '.'.$uniqid.' .item-count {color: '.thegem_get_option('h6_color').';}';
		}
		if(!empty($color_text)) {
			$custom_css .= '.'.$uniqid.' .item-title {color: '.$color_text.';}';
		} else {
			$custom_css .= '.'.$uniqid.' .item-title {color: '.thegem_get_option('date_filter_subtitle_color').';}';
		}
		if(!empty($color_border)) {
			$custom_css .= '.'.$uniqid.' .countdown-item:not(:last-child) {border-right-color: '.$color_border.';}';
		} else {
			$custom_css .= '.'.$uniqid.' .countdown-item:not(:last-child) {border-right-color: '.thegem_get_option('divider_default_color').';}';
		}
		if(!empty($color_background)) {
			$custom_css .= '.'.$uniqid.' .thegem-te-countdown {background: '.$color_background.';}';
		} else {
			$custom_css .= '.'.$uniqid.' .countdown-item:first-child {padding-left: 0;}';
			$custom_css .= '.'.$uniqid.' .countdown-item:last-child {padding-right: 0;}';
		}

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
			'name' => __('Countdown', 'thegem'),
			'base' => 'thegem_te_countdown',
			'icon' => 'thegem-icon-wpb-ui-element-countdown',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('Elements - Countdown', 'thegem'),
			'params' => array_merge(
				array(
					array(
						'type' => 'thegem_datepicker_param',
						'heading' => __('Event Date', 'thegem'),
						'param_name' => 'eventdate',
						'description' => 'Date format : Day-Month-Year'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Days Label', 'thegem'),
						'param_name' => 'days_label'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Hours Label', 'thegem'),
						'param_name' => 'hours_label'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Minutes Label', 'thegem'),
						'param_name' => 'minutes_label'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Seconds Label', 'thegem'),
						'param_name' => 'seconds_label'
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Style', 'thegem'),
						'param_name' => 'style',
						'value' => array(
							__('Elegant Tiny', 'thegem') => 'elegant_tiny'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Numbers Weight', 'thegem'),
						'param_name' => 'weight_number',
						'value' => array(
							'Default' => 'default',
							'Thin' => 'thin'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6 no-top-padding',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Number color', 'thegem'),
						'param_name' => 'color_number',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Label color', 'thegem'),
						'param_name' => 'color_text',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Divider color', 'thegem'),
						'param_name' => 'color_border',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background color', 'thegem'),
						'param_name' => 'color_background',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('Style', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Extra class name', 'thegem'),
						'param_name' => 'extraclass',
					),
				),
				thegem_set_elements_design_options()
			)
		);
	}
}

$templates_elements['thegem_te_countdown'] = new TheGem_Template_Element_Countdown();
$templates_elements['thegem_te_countdown']->init();
