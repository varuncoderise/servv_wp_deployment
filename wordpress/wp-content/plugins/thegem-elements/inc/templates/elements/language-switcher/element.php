<?php

class TheGem_Template_Element_Language_Switcher extends TheGem_Template_Element {

	public function __construct() {
		if ( !defined('THEGEM_TE_LANGUAGE_SWITCHER_DIR' )) {
			define('THEGEM_TE_LANGUAGE_SWITCHER_DIR', rtrim(__DIR__, ' /\\'));
		}

		if ( !defined('THEGEM_TE_LANGUAGE_SWITCHER_URL') ) {
			define('THEGEM_TE_LANGUAGE_SWITCHER_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}

		wp_register_style('thegem-te-language-switcher', THEGEM_TE_LANGUAGE_SWITCHER_URL . '/css/language-switcher.css');
		wp_register_script('thegem-te-language-switcher', THEGEM_TE_LANGUAGE_SWITCHER_URL . '/js/language-switcher.js', array('jquery'), false, true);
	}

	public function get_name() {
		return 'thegem_te_language_switcher';
	}

	public function head_scripts($attr) {
		wp_enqueue_script('thegem-te-language-switcher');
		wp_enqueue_style('thegem-te-language-switcher');
	}

	public function front_editor_scripts() {
		wp_enqueue_style('thegem-te-language-switcher');
	}
	
	public function is_wpml_exist() {
		return (function_exists('icl_object_id') && thegem_is_plugin_active('sitepress-multilingual-cms/sitepress.php'));
	}

	public function shortcode_output($atts, $content = '') {
		$params = shortcode_atts(array_merge(array(
			'type' => 'dropdown',
			'dropdown_spacing' => '20',
			'flag' => '1',
			'native_name' => '1',
			'translated_name' => '',
			'name_in_current' => '',
			'current_lang' => '',
			'capitalize_name' => '',
			'text_color' => '',
			'text_color_hover' => '',
			'text_color_active' => '',
			'dropdown_background_color' => '',
			'dropdown_shadow' => '',
			'dropdown_shadow_color' => 'rgba(0, 0, 0, 0.05)',
			'dropdown_shadow_position' => 'outline',
			'dropdown_shadow_horizontal' => '0',
			'dropdown_shadow_vertical' => '0',
			'dropdown_shadow_blur' => '20',
			'dropdown_shadow_spread' => '0',
			'dropdown_padding_top' => '',
			'dropdown_padding_right' => '',
			'dropdown_padding_bottom' => '',
			'dropdown_padding_left' => '',
			'list_arrow_prefix' => '1',
			'list_item_space_between' => '',
		), thegem_templates_design_options_extract(), thegem_templates_extra_options_extract()), $atts, 'thegem_te_language_switcher');

		//General
		$extra_id = $extra_cls = '';
		if (!empty($params['element_id'])){ $extra_id = $params['element_id']; }
		if (!empty($params['element_class'])){ $extra_cls = $params['element_class']; }

		// Init Design Options Params
		$return_html = $custom_css = $uniqid = '';
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-language-switcher', $params);
  
		// Init Menu
		ob_start(); ?>

		<div <?php if ($extra_id): ?>id="<?=esc_attr($extra_id); ?>"<?php endif;?> class="thegem-te-language-switcher <?= esc_attr($extra_cls); ?> <?=esc_attr($uniqid)?>" <?=thegem_data_editor_attribute($uniqid.'-editor')?>>
        <?php if ( $this->is_wpml_exist() ): ?>
            <?php
	        $languages = apply_filters('wpml_active_languages', NULL, NULL);
	        if (!empty( $languages )) {
		        $current_language = $not_current_languages = $all_languages = [];
		        foreach ($languages as $lang) {
			        $all_languages[] = $lang;
			        if (!$params['current_lang']) {
				        foreach($all_languages as $key => $item){
					        if ($item['active'] == 1){
						        unset($all_languages[$key]);
					        }
				        }
			        }
			
			        if ($lang['active']) {
				        $current_language = $lang;
			        } else {
				        $not_current_languages[] = $lang;
			        }
		        }
	        } elseif ((function_exists('vc_is_page_editable') && vc_is_page_editable()) || (get_post_type() === 'thegem_templates' && thegem_get_template_type(get_the_id()) === 'header')) {
		        global $sitepress;
		        $languages = $sitepress->get_active_languages();
		        $current_lang = $sitepress->get_current_language();
		
		        $current_language = $not_current_languages = $all_languages = [];
		        foreach ($languages as $i => $lang) {
			        $lang['country_flag_url'] = $sitepress->get_flag_url( $lang['code'] );
			        $all_languages[] = $lang;
			
			        if ($lang['code'] == $current_lang) {
				        $current_language = $lang;
			        } else {
				        $not_current_languages[] = $lang;
			        }
		        }
	        } ?>
        
            <?php if($params['type'] == 'dropdown'): ?>
                <div class="thegem-te-language-switcher-dropdown">
                    <?php if (!empty($current_language)): ?>
                        <?php
                            $native_name = $params['native_name'] ? $current_language['native_name'] : null;
                            $translated_name = $params['translated_name'] ? isset($current_language['translated_name']) ? $current_language['translated_name'] :  $current_language['english_name'] : null;
                            if($params['native_name'] && $params['translated_name']) {
                                $translated_name = ' (' . $translated_name . ')';
                            }
	                        $current_lang_name = ($native_name || $translated_name) ? $native_name.' '.$translated_name : null;
                            $is_capitalize = $params['capitalize_name'];
                        ?>
                        <div class="dropdown-item <?php if ($params['flag'] && empty($current_lang_name)): ?>flag-only<?php endif; ?>">
                            <div class="dropdown-item__current">
                                <?php if ($params['flag']): ?>
                                    <i class="flag"><img src="<?= esc_url($current_language['country_flag_url']) ?>" alt="<?= esc_html( $current_language['code'] ) ?>" /></i>
                                <?php endif; ?>
                                
				                <?php if (!empty($current_lang_name)): ?>
                                    <span class="name <?php if ($is_capitalize): ?>capitalize<?php endif; ?>"><?= esc_html( $current_lang_name ) ?></span>
				                <?php endif; ?>
                            </div>
                            
	                        <?php if (!empty($not_current_languages)): ?>
                                <div class="dropdown-item__wrapper">
                                    <ul>
				                        <?php foreach( $not_current_languages as $lng ): ?>
					                        <?php
                                                $native_name = $params['native_name'] ? $lng['native_name'] : null;
                                                $translated_name = $params['translated_name'] ? isset($lng['translated_name']) ? $lng['translated_name'] : $lng['english_name'] : null;
                                                if($params['native_name'] && $params['translated_name']) {
                                                    $translated_name = ' (' . $translated_name . ')';
                                                }
                                                $lang_name = ($native_name || $translated_name) ? $native_name.' '.$translated_name : null;
					                            $is_capitalize = $params['capitalize_name'];
					                        ?>
                                            <li>
                                                <a href="<?=esc_url( $lng['url'])?>">
						                            <?php if ($params['flag']): ?>
                                                        <i class="flag"><img src="<?= esc_url($lng['country_flag_url']) ?>" alt="<?= esc_html( $lng['code'] ) ?>" /></i>
						                            <?php endif; ?>
						
						                            <?php if (!empty($lang_name)): ?>
                                                        <span class="name <?php if ($is_capitalize): ?>capitalize<?php endif; ?>"><?= esc_html( $lang_name ) ?></span>
						                            <?php endif; ?>
                                                </a>
                                            </li>
				                        <?php endforeach; ?>
                                    </ul>
                                </div>
	                        <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
		
            <?php if($params['type'] == 'list'): ?>
                <div class="thegem-te-language-switcher-list">
                    <?php if (!empty($all_languages)): ?>
                        <ul>
                            <?php foreach( $all_languages as $lng ): ?>
	                            <?php
                                    $native_name = $params['native_name'] ? $lng['native_name'] : null;
                                    $translated_name = $params['translated_name'] ? isset($lng['translated_name']) ? $lng['translated_name'] : $lng['english_name'] : null;
                                    if($params['native_name'] && $params['translated_name']) {
                                        $translated_name = ' (' . $translated_name . ')';
                                    }
                                    $lang_name = ($native_name || $translated_name) ? $native_name.' '.$translated_name : null;
                                    $is_capitalize = $params['capitalize_name'];
	                            ?>
                                <li>
                                    <a href="<?=esc_url( $lng['url'])?>" class="<?php if ($lng['active']): ?>active<?php endif; ?> <?php if ($params['flag']): ?>flag-only<?php endif; ?>">
					                    <?php if ($params['flag']): ?>
                                            <i class="flag"><img src="<?= esc_url($lng['country_flag_url']) ?>" alt="<?= esc_html( $lng['code'] ) ?>" /></i>
					                    <?php endif; ?>
					
					                    <?php if (!empty($lang_name)): ?>
                                            <span class="name <?php if ($is_capitalize): ?>capitalize<?php endif; ?>"><?= esc_html( $lang_name ) ?></span>
					                    <?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else:
            $site_lang = explode('-', get_bloginfo('language')); ?>
            <div class="thegem-te-language-switcher-list">
                <ul>
                    <li>
                        <a href="" class="active flag-only">
                            <span class="name"><?= esc_html(ucfirst($site_lang[0])) ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
		</div>

		<?php

		// Icon Custom Styles
		$customize = '.thegem-te-language-switcher.'.$uniqid;
  
		// General Colors
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .thegem-te-language-switcher-dropdown .dropdown-item__wrapper ul li a {color: ' . $params['text_color'] . ';}';
			$custom_css .= $customize.' .thegem-te-language-switcher-list ul li a {color: ' . $params['text_color'] . ';}';
		}
		if (!empty($params['text_color_hover'])) {
			$custom_css .= $customize.' .thegem-te-language-switcher-dropdown .dropdown-item__wrapper ul li a:hover {color: ' . $params['text_color_hover'] . ';}';
			$custom_css .= $customize.' .thegem-te-language-switcher-list ul li a:hover {color: ' . $params['text_color_hover'] . ';}';
		}
		if (!empty($params['text_color_active'])) {
			$custom_css .= $customize.' .thegem-te-language-switcher-dropdown .dropdown-item__current {color: ' . $params['text_color_active'] . ';}';
			$custom_css .= $customize.' .thegem-te-language-switcher-dropdown .dropdown-item:after {color: ' . $params['text_color_active'] . ';}';
			$custom_css .= $customize.' .thegem-te-language-switcher-list ul li a.active {color: ' . $params['text_color_active'] . ';}';
		}
  
		// Dropdown
		if (!empty($params['dropdown_spacing'])) {
			$custom_css .= $customize.' .thegem-te-language-switcher-dropdown .dropdown-item__current {margin-bottom: -' . $params['dropdown_spacing'] . 'px; padding-bottom: ' . $params['dropdown_spacing'] . 'px;}';
		}
		if (!empty($params['dropdown_background_color'])) {
			$custom_css .= $customize.' .thegem-te-language-switcher-dropdown .dropdown-item__wrapper { background-color: ' . $params['dropdown_background_color'] . ';}';
		}
		if(!empty($params['dropdown_shadow'])) {
			$shadow_position = '';
			if($params['dropdown_shadow_position'] == 'inset') {
				$shadow_position = 'inset';
			}
			if(empty($params['dropdown_shadow_horizontal'])) {
				$params['dropdown_shadow_horizontal'] = 0;
			}
			if(empty($params['dropdown_shadow_vertical'])) {
				$params['dropdown_shadow_vertical'] = 0;
			}
			if(empty($params['dropdown_shadow_blur'])) {
				$params['dropdown_shadow_blur'] = 0;
			}
			if(empty($params['dropdown_shadow_spread'])) {
				$params['dropdown_shadow_spread'] = 0;
			}
			if(empty($params['dropdown_shadow_color'])) {
				$params['dropdown_shadow_color'] = '#000';
			}
			$custom_css .= $customize.' .thegem-te-language-switcher-dropdown .dropdown-item__wrapper {'
				. '-webkit-box-shadow: '.$shadow_position.' '.$params['dropdown_shadow_horizontal'].'px '.$params['dropdown_shadow_vertical'].'px '.$params['dropdown_shadow_blur'].'px '.$params['dropdown_shadow_spread'].'px '.$params['dropdown_shadow_color'].';'
				. '-moz-box-shadow: '.$shadow_position.' '.$params['dropdown_shadow_horizontal'].'px '.$params['dropdown_shadow_vertical'].'px '.$params['dropdown_shadow_blur'].'px '.$params['dropdown_shadow_spread'].'px '.$params['dropdown_shadow_color'].';;'
				. '-o-box-shadow: '.$shadow_position.' '.$params['dropdown_shadow_horizontal'].'px '.$params['dropdown_shadow_vertical'].'px '.$params['dropdown_shadow_blur'].'px '.$params['dropdown_shadow_spread'].'px '.$params['dropdown_shadow_color'].';;'
				. 'box-shadow: '.$shadow_position.' '.$params['dropdown_shadow_horizontal'].'px '.$params['dropdown_shadow_vertical'].'px '.$params['dropdown_shadow_blur'].'px '.$params['dropdown_shadow_spread'].'px '.$params['dropdown_shadow_color'].';;'
				. '}';
		}
		if (isset($params['dropdown_padding_top'])) {
			$custom_css .= $customize.' .thegem-te-language-switcher-dropdown .dropdown-item__wrapper {padding-top: ' . $params['dropdown_padding_top'] . 'px;}';
		}
		if (isset($params['dropdown_padding_right'])) {
			$custom_css .= $customize.' .thegem-te-language-switcher-dropdown .dropdown-item__wrapper {padding-right: ' . $params['dropdown_padding_right'] . 'px;}';
		}
		if (isset($params['dropdown_padding_bottom'])) {
			$custom_css .= $customize.' .thegem-te-language-switcher-dropdown .dropdown-item__wrapper {padding-bottom: ' . $params['dropdown_padding_bottom'] . 'px;}';
		}
		if (isset($params['dropdown_padding_left'])) {
			$custom_css .= $customize.' .thegem-te-language-switcher-dropdown .dropdown-item__wrapper {padding-left: ' . $params['dropdown_padding_left'] . 'px; margin-left: -' . $params['dropdown_padding_left'] . 'px; min-width: calc(100% + '. $params['dropdown_padding_left'] . 'px);}';
		}
		
		// List
		if(empty($params['list_arrow_prefix'])) {
			$custom_css .= $customize.' .thegem-te-language-switcher-list ul li a:before {display: none;}';
		}
		if (!empty($params['list_item_space_between'])) {
			$custom_css .= $customize.' .thegem-te-language-switcher-list ul {margin: 0 calc(-' . $params['list_item_space_between'] . 'px/2);}';
			$custom_css .= $customize.' .thegem-te-language-switcher-list ul li {padding: 0 calc(' . $params['list_item_space_between'] . 'px/2);}';
		}
  
  
		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}
		
		$return_html = $css_output.$return_html;
		
		return $return_html;
	}

    public function thegem_set_language_switcer_options () {
		
		if ( $this->is_wpml_exist() ) {
			$switcher_options = array_merge(
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'description' => __('<div class="thegem-param-alert">Please refer to this <a href="https://codex-themes.com/thegem/documentation/#wpml" target="_blank">documentation</a> on configuring WPML.</div>', 'thegem'),
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Switcher Type', 'thegem'),
						'param_name' => 'type',
						'value' => array(
							__('Dropdown', 'thegem') => 'dropdown',
							__('List of languages', 'thegem') => 'list',
						),
						'std' => 'dropdown',
						'dependency' => array(
							'callback' => 'thegem_templates_switcers_callback'
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Dropdown Spacing', 'thegem'),
						'param_name' => 'dropdown_spacing',
						'std' => 20,
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Flag', 'thegem'),
						'param_name' => 'flag',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => 1,
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Native language name', 'thegem'),
						'param_name' => 'native_name',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => 1,
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Translated language name', 'thegem'),
						'param_name' => 'translated_name',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Current language', 'thegem'),
						'param_name' => 'current_lang',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => 1,
						'dependency' => array('element' => 'type', 'value' => 'list'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Capitalize the language name', 'thegem'),
						'param_name' => 'capitalize_name',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('General', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Text Color', 'thegem'),
						'param_name' => 'sub_delim_head_menu_item_text_color',
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Normal', 'thegem'),
						'param_name' => 'text_color',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Hover', 'thegem'),
						'param_name' => 'text_color_hover',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Active', 'thegem'),
						'param_name' => 'text_color_active',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Dropdown', 'thegem'),
						'param_name' => 'layout_delim_head',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'thegem-param-delimeter-heading param--dropdown no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Background Color', 'thegem'),
						'param_name' => 'dropdown_background_color',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Box Shadow', 'thegem'),
						'param_name' => 'dropdown_shadow',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Shadow color', 'thegem'),
						'param_name' => 'dropdown_shadow_color',
						'dependency' => array('element' => 'dropdown_shadow', 'not_empty' => true),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'std' => 'rgba(0, 0, 0, 0.05)',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Position', 'thegem'),
						'param_name' => 'dropdown_shadow_position',
						'value' => array(
							__('Outline', 'thegem') => 'outline',
							__('Inset', 'thegem') => 'inset'
						),
						'dependency' => array('element' => 'dropdown_shadow', 'not_empty' => true),
						'edit_field_class' => 'vc_column vc_col-sm-4',
						'std' => 'outline',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Horizontal', 'thegem'),
						'param_name' => 'dropdown_shadow_horizontal',
						'dependency' => array('element' => 'dropdown_shadow', 'not_empty' => true),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '0',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Vertical', 'thegem'),
						'param_name' => 'dropdown_shadow_vertical',
						'dependency' => array('element' => 'dropdown_shadow', 'not_empty' => true),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '0',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Blur', 'thegem'),
						'param_name' => 'dropdown_shadow_blur',
						'dependency' => array('element' => 'dropdown_shadow', 'not_empty' => true),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '20',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'textfield',
						'heading' => __('Spread', 'thegem'),
						'param_name' => 'dropdown_shadow_spread',
						'dependency' => array('element' => 'dropdown_shadow', 'not_empty' => true),
						'edit_field_class' => 'vc_column vc_col-sm-3',
						'std' => '0',
						'group' => __('Appearance', 'thegem')
					),
					array(
						'type' => 'thegem_delimeter_heading_two_level',
						'heading' => __('Padding', 'thegem'),
						'param_name' => 'sub_delim_head_dropdown_padding',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Top', 'thegem'),
						'param_name' => 'dropdown_padding_top',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Right', 'thegem'),
						'param_name' => 'dropdown_padding_right',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Bottom', 'thegem'),
						'param_name' => 'dropdown_padding_bottom',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Left', 'thegem'),
						'param_name' => 'dropdown_padding_left',
						'dependency' => array('element' => 'type', 'value' => 'dropdown'),
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'group' => __('Appearance', 'thegem'),
					),
					
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('List', 'thegem'),
						'param_name' => 'layout_delim_head',
						'dependency' => array('element' => 'type', 'value' => 'list'),
						'edit_field_class' => 'thegem-param-delimeter-heading param--list no-top-padding margin-top vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Arrow Prefix', 'thegem'),
						'param_name' => 'list_arrow_prefix',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => 1,
						'dependency' => array('element' => 'type', 'value' => 'list'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Space Between', 'thegem'),
						'param_name' => 'list_item_space_between',
						'dependency' => array('element' => 'type', 'value' => 'list'),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => __('Appearance', 'thegem'),
					),
				),
				
				/* General - Extra */
				thegem_set_elements_extra_options(),
				
				/* Design Options */
				thegem_set_elements_design_options()
			);
		} else {
			$switcher_options = array(
				array(
					'type' => 'thegem_delimeter_heading',
					'param_name' => 'layout_delim_head',
					'edit_field_class' => 'vc_column vc_col-sm-12',
					'description' => __('<div class="thegem-param-alert">You need to install WPML Multilingual CMS.<br/> <a href="'.get_site_url().'/wp-admin/plugins.php" target="_blank">Go to install plugins page.</a></div>', 'thegem'),
				)
			);
		}
		
		return $switcher_options;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Language Switcher', 'thegem'),
			'base' => 'thegem_te_language_switcher',
			'icon' => 'thegem-icon-wpb-ui-element-language-switcher',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('Language Switcher (Header Builder)', 'thegem'),
			'params' => $this->thegem_set_language_switcer_options()
		);
	}
}

$templates_elements['thegem_te_language_switcher'] = new TheGem_Template_Element_Language_Switcher();
$templates_elements['thegem_te_language_switcher']->init();

