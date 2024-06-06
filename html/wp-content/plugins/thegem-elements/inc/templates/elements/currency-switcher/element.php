<?php

class TheGem_Template_Element_Currency_Switcher extends TheGem_Template_Element {

	public function __construct() {
		if ( !defined('THEGEM_TE_CURRENCY_SWITCHER_DIR' )) {
			define('THEGEM_TE_CURRENCY_SWITCHER_DIR', rtrim(__DIR__, ' /\\'));
		}

		if ( !defined('THEGEM_TE_CURRENCY_SWITCHER_URL') ) {
			define('THEGEM_TE_CURRENCY_SWITCHER_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}

		wp_register_style('thegem-te-currency-switcher', THEGEM_TE_CURRENCY_SWITCHER_URL . '/css/currency-switcher.css');
		wp_register_style('thegem-te-currency-switcher-editor', THEGEM_TE_CURRENCY_SWITCHER_URL . '/css/currency-switcher-editor.css');
		wp_register_script('thegem-te-currency-switcher', THEGEM_TE_CURRENCY_SWITCHER_URL . '/js/currency-switcher.js', array('jquery'), false, true);
	}

	public function get_name() {
		return 'thegem_te_currency_switcher';
	}

	public function head_scripts($attr) {
		wp_enqueue_script('thegem-te-currency-switcher');
		wp_enqueue_style('thegem-te-currency-switcher');
	}

	public function front_editor_scripts() {
		wp_enqueue_style('thegem-te-currency-switcher');
	}
	
	public function is_woocommerce_exist() {
		return (thegem_is_plugin_active('woocommerce/woocommerce.php') && function_exists('icl_object_id') && thegem_is_plugin_active('sitepress-multilingual-cms/sitepress.php') && thegem_is_plugin_active('woocommerce-multilingual/wpml-woocommerce.php'));
	}

	public function shortcode_output($atts, $content = '') {
		if (!$this->is_woocommerce_exist()) return;
  
		$params = shortcode_atts(array_merge(array(
			'type' => 'dropdown',
			'dropdown_spacing' => '20',
			'symbol' => '',
			'currency_native_name' => '1',
			'currency_translated_name' => '',
			'show_current' => '1',
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
		), thegem_templates_design_options_extract(), thegem_templates_extra_options_extract()), $atts, 'thegem_te_currency_switcher');

		//General
		$extra_id = $extra_cls = '';
		if (!empty($params['element_id'])){ $extra_id = $params['element_id']; }
		if (!empty($params['element_class'])){ $extra_cls = $params['element_class']; }

		// Init Design Options Params
		$return_html = $custom_css = $uniqid = '';
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-currency-switcher', $params);
  
		ob_start();
        
        ?>
  
		<div <?php if ($extra_id): ?>id="<?=esc_attr($extra_id); ?>"<?php endif;?> class="thegem-te-currency-switcher currency-widget <?= esc_attr($extra_cls); ?> <?=esc_attr($uniqid)?>" <?=thegem_data_editor_attribute($uniqid.'-editor')?>>
            
            <?php if ( function_exists('icl_object_id') && thegem_is_plugin_active('sitepress-multilingual-cms/sitepress.php') && thegem_is_plugin_active('woocommerce-multilingual/wpml-woocommerce.php')): ?>
            
                <?php
                    global $woocommerce_wpml;
                   
                    $currencies = $woocommerce_wpml->multi_currency->get_currencies(true);
                    foreach ($currencies as $key => $curr) {
                        foreach (get_woocommerce_currencies() as $k => $name){
                            if ( $key == $k ) {
                                $currencies[$k]['code'] = $k;
                                $currencies[$k]['name'] = $k[0].strtolower($k[1].$k[2]);
                                $currencies[$k]['full_name'] = $name;
                                $currencies[$k]['symbol'] = get_woocommerce_currency_symbol($k);
                            }
                        }
                    }
                    
                    if (!empty( $currencies )) {
	                    $current_currency = $other_currencies = [];
                        $wc_currency = get_woocommerce_currency();
                        foreach ($currencies as $key => $curr) {
	                        if ( $wc_currency == $key ) {
		                        $current_currency = $curr;
                            } else {
		                        $other_currencies[] = $curr;
                            }
                        }
                    }
                ?>
            
                <?php if($params['type'] == 'dropdown'): ?>
                    <div class="thegem-te-currency-switcher-dropdown currency-dropdown-widget wcml_currency_switcher">
                        <?php if (!empty($current_currency)): ?>
                            <div class="dropdown-item">
                                <div class="dropdown-item__current">
                                    <span class="name <?php if ($params['capitalize_name']): ?>capitalize<?php endif; ?>">
                                          <?php if ($params['currency_native_name']): ?>
	                                          <?= esc_html( $current_currency['name'] ) ?>
                                          <?php endif; ?>

                                          <?php if ($params['currency_translated_name']): ?>
	                                          <?= esc_html( $current_currency['full_name'] ) ?>
                                          <?php endif; ?>

                                          <?php if ($params['symbol']): ?>
	                                          <?= esc_html( $current_currency['symbol'] ) ?>
                                          <?php endif; ?>
                                    </span>
                                </div>
                                
                                <?php if (!empty($other_currencies)): ?>
                                    <div class="dropdown-item__wrapper">
                                        <ul>
                                            <?php foreach( $other_currencies as $curr ): ?>
                                                <li>
                                                    <a href="" rel="<?= esc_html( $curr['code'] ) ?>">
                                                        <span class="name <?php if ($params['capitalize_name']): ?>capitalize<?php endif; ?>">
                                                           <?php if ($params['currency_native_name']): ?>
	                                                           <?= esc_html( $curr['name'] ) ?>
                                                           <?php endif; ?>

                                                           <?php if ($params['currency_translated_name']): ?>
	                                                           <?= esc_html( $curr['full_name'] ) ?>
                                                           <?php endif; ?>

                                                           <?php if ($params['symbol']): ?>
	                                                           <?= esc_html( $curr['symbol'] ) ?>
                                                           <?php endif; ?>
                                                        </span>
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
                    <div class="thegem-te-currency-switcher-list wcml-horizontal-list wcml_currency_switcher">
	                    <?php if (!empty($currencies)): ?>
                            <ul>
                                <?php if ($params['show_current']): ?>
                                    <li>
                                        <div class="switcher-list-item active">
                                            <span class="name <?php if ($params['capitalize_name']): ?>capitalize<?php endif; ?>">
                                                <?php if ($params['currency_native_name']): ?>
	                                                <?= esc_html( $current_currency['name'] ) ?>
						                        <?php endif; ?>
                                                
                                                <?php if ($params['currency_translated_name']): ?>
	                                                <?= esc_html( $current_currency['full_name'] ) ?>
                                                <?php endif; ?>
                    
							                    <?php if ($params['symbol']): ?>
	                                                <?= esc_html( $current_currency['symbol'] ) ?>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                
			                    <?php foreach( $other_currencies as $curr ): ?>
                                    <li>
                                        <a href="" rel="<?= esc_html( $curr['code'] ) ?>" class="switcher-list-item">
                                            <span class="name <?php if ($params['capitalize_name']): ?>capitalize<?php endif; ?>">
                                                 <?php if ($params['currency_native_name']): ?>
	                                                 <?= esc_html( $curr['name'] ) ?>
                                                 <?php endif; ?>

                                                 <?php if ($params['currency_translated_name']): ?>
	                                                 <?= esc_html( $curr['full_name'] ) ?>
                                                 <?php endif; ?>

                                                 <?php if ($params['symbol']): ?>
	                                                 <?= esc_html( $curr['symbol'] ) ?>
                                                 <?php endif; ?>
                                            </span>
                                        </a>
                                    </li>
			                    <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            
            <?php else:
                $site_currency = get_woocommerce_currency(); ?>
                <div class="thegem-te-currency-switcher-list">
                    <ul>
                        <li>
                            <div class="switcher-list-item active disable">
                                <span class="name"><?= esc_html(ucfirst($site_currency)) ?></span>
                            </div>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
            
		</div>

		<?php

		// Custom Styles
		$customize = '.thegem-te-currency-switcher.'.$uniqid;
		
		// General Colors
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .thegem-te-currency-switcher-dropdown .dropdown-item__wrapper ul li a {color: ' . $params['text_color'] . ';}';
			$custom_css .= $customize.' .thegem-te-currency-switcher-list .switcher-list-item:not(.disable) {color: ' . $params['text_color'] . ';}';
		}
		if (!empty($params['text_color_hover'])) {
			$custom_css .= $customize.' .thegem-te-currency-switcher-dropdown .dropdown-item__wrapper ul li a:hover {color: ' . $params['text_color_hover'] . ';}';
			$custom_css .= $customize.' .thegem-te-currency-switcher-list .switcher-list-item:not(.disable):hover {color: ' . $params['text_color_hover'] . ';}';
		}
		if (!empty($params['text_color_active'])) {
			$custom_css .= $customize.' .thegem-te-currency-switcher-dropdown .dropdown-item__current {color: ' . $params['text_color_active'] . ';}';
			$custom_css .= $customize.' .thegem-te-currency-switcher-dropdown .dropdown-item:after {color: ' . $params['text_color_active'] . ';}';
			$custom_css .= $customize.' .thegem-te-currency-switcher-list .switcher-list-item:not(.disable).active {color: ' . $params['text_color_active'] . ';}';
		}
		
		// Dropdown
		if (!empty($params['dropdown_spacing'])) {
			$custom_css .= $customize.' .thegem-te-currency-switcher-dropdown .dropdown-item__current {margin-bottom: -' . $params['dropdown_spacing'] . 'px; padding-bottom: ' . $params['dropdown_spacing'] . 'px;}';
		}
		if (!empty($params['dropdown_background_color'])) {
			$custom_css .= $customize.' .thegem-te-currency-switcher-dropdown .dropdown-item__wrapper { background-color: ' . $params['dropdown_background_color'] . ';}';
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
			$custom_css .= $customize.' .thegem-te-currency-switcher-dropdown .dropdown-item__wrapper {'
				. '-webkit-box-shadow: '.$shadow_position.' '.$params['dropdown_shadow_horizontal'].'px '.$params['dropdown_shadow_vertical'].'px '.$params['dropdown_shadow_blur'].'px '.$params['dropdown_shadow_spread'].'px '.$params['dropdown_shadow_color'].';'
				. '-moz-box-shadow: '.$shadow_position.' '.$params['dropdown_shadow_horizontal'].'px '.$params['dropdown_shadow_vertical'].'px '.$params['dropdown_shadow_blur'].'px '.$params['dropdown_shadow_spread'].'px '.$params['dropdown_shadow_color'].';;'
				. '-o-box-shadow: '.$shadow_position.' '.$params['dropdown_shadow_horizontal'].'px '.$params['dropdown_shadow_vertical'].'px '.$params['dropdown_shadow_blur'].'px '.$params['dropdown_shadow_spread'].'px '.$params['dropdown_shadow_color'].';;'
				. 'box-shadow: '.$shadow_position.' '.$params['dropdown_shadow_horizontal'].'px '.$params['dropdown_shadow_vertical'].'px '.$params['dropdown_shadow_blur'].'px '.$params['dropdown_shadow_spread'].'px '.$params['dropdown_shadow_color'].';;'
				. '}';
		}
		if (isset($params['dropdown_padding_top'])) {
			$custom_css .= $customize.' .thegem-te-currency-switcher-dropdown .dropdown-item__wrapper {padding-top: ' . $params['dropdown_padding_top'] . 'px;}';
		}
		if (isset($params['dropdown_padding_right'])) {
			$custom_css .= $customize.' .thegem-te-currency-switcher-dropdown .dropdown-item__wrapper {padding-right: ' . $params['dropdown_padding_right'] . 'px;}';
		}
		if (isset($params['dropdown_padding_bottom'])) {
			$custom_css .= $customize.' .thegem-te-currency-switcher-dropdown .dropdown-item__wrapper {padding-bottom: ' . $params['dropdown_padding_bottom'] . 'px;}';
		}
		if (isset($params['dropdown_padding_left'])) {
			$custom_css .= $customize.' .thegem-te-currency-switcher-dropdown .dropdown-item__wrapper {padding-left: ' . $params['dropdown_padding_left'] . 'px; margin-left: -' . $params['dropdown_padding_left'] . 'px; min-width: calc(100% + '. $params['dropdown_padding_left'] . 'px);}';
		}
		
		// List
		if(empty($params['list_arrow_prefix'])) {
			$custom_css .= $customize.' .thegem-te-currency-switcher-list .switcher-list-item:before {display: none;}';
		}
		if (!empty($params['list_item_space_between'])) {
			$custom_css .= $customize.' .thegem-te-currency-switcher-list ul {margin: 0 calc(-' . $params['list_item_space_between'] . 'px/2);}';
			$custom_css .= $customize.' .thegem-te-currency-switcher-list ul li {padding: 0 calc(' . $params['list_item_space_between'] . 'px/2);}';
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

    public function thegem_set_currency_switcer_options () {
		
		if ($this->is_woocommerce_exist()) {
			$switcher_options = array_merge(
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'description' => __('<div class="thegem-param-alert">Please refer to this <a href="https://wpml.org/documentation/related-projects/woocommerce-multilingual/multi-currency-support-woocommerce/" target="_blank">documentation</a> on configuring currencies.</div>', 'thegem'),
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Switcher Type', 'thegem'),
						'param_name' => 'type',
						'value' => array(
							__('Dropdown', 'thegem') => 'dropdown',
							__('List of currencies', 'thegem') => 'list',
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
						'heading' => __('Native name', 'thegem'),
						'param_name' => 'currency_native_name',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => 1,
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Translated name', 'thegem'),
						'param_name' => 'currency_translated_name',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Symbol', 'thegem'),
						'param_name' => 'symbol',
						'value' => array(__('Yes', 'thegem') => '1'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Show Current', 'thegem'),
						'param_name' => 'show_current',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => 1,
						'dependency' => array('element' => 'type', 'value' => 'list'),
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => __('General', 'thegem'),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Capitalize name', 'thegem'),
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
					'description' => __('<div class="thegem-param-alert">You need to install WooCommerce, WPML Multilingual CMS and WooCommerce Multilingual.<br/> <a href="'.get_site_url().'/wp-admin/plugins.php" target="_blank">Go to install plugins page.</a></div>', 'thegem'),
				)
			);
		}
		
		return $switcher_options;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Currency Switcher', 'thegem'),
			'base' => 'thegem_te_currency_switcher',
			'icon' => 'thegem-icon-wpb-ui-element-currency-switcher',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('Currency Switcher (Header Builder)', 'thegem'),
			'params' => $this->thegem_set_currency_switcer_options()
		);
	}
}

$templates_elements['thegem_te_currency_switcher'] = new TheGem_Template_Element_Currency_Switcher();
$templates_elements['thegem_te_currency_switcher']->init();
