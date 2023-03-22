<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

if(!class_exists('Thegem_Custom_params')) {
	class Thegem_Custom_params {
		function __construct() {
			if(function_exists('vc_add_shortcode_param')) {
				vc_add_shortcode_param('thegem_delimeter_heading' , array($this, 'thegem_delimeter_heading_callback'));
				vc_add_shortcode_param('thegem_delimeter_heading_two_level' , array($this, 'thegem_delimeter_heading_two_level_callback'));
			}
		}
	
		function thegem_delimeter_heading_callback($settings, $value) {
			$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
			$class = isset($settings['class']) ? $settings['class'] : '';
			$heading = isset($settings['heading']) ? $settings['heading'] : '';
			
			$html = '<input type="hidden"  class="wpb_vc_param_value ' . esc_attr($param_name . ' ' . $class) . '" name="' . esc_attr($param_name) . '" value="'.$value.'" />';
			return $html;
		}
		
		function thegem_delimeter_heading_two_level_callback($settings, $value) {
			$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
			$class = isset($settings['class']) ? $settings['class'] : '';
			$heading = isset($settings['heading']) ? $settings['heading'] : '';
			
			$html = '<input type="hidden"  class="wpb_vc_param_value ' . esc_attr($param_name . ' ' . $class) . '" name="' . esc_attr($param_name) . '" value="'.$value.'" />';
			return $html;
		}
	}
	
	$Thegem_Custom_params = new Thegem_Custom_params();
}
