<?php

class TheGem_Template_Element_Signin extends TheGem_Template_Element {

	public function __construct() {

		if ( !defined('THEGEM_TE_SIGNIN_DIR' )) {
			define('THEGEM_TE_SIGNIN_DIR', rtrim(__DIR__, ' /\\'));
		}

		if ( !defined('THEGEM_TE_SIGNIN_URL') ) {
			define('THEGEM_TE_SIGNIN_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}
  
		wp_register_style('thegem-te-signin', THEGEM_TE_SIGNIN_URL . '/css/signin.css');
	}

	public function get_name() {
		return 'thegem_te_signin';
	}

	public function head_scripts($attr) {
		$attr = shortcode_atts(array(
			'signin_icon_pack' => 'thegem-header',
			'signout_icon_pack' => 'thegem-header',
		), $attr, 'thegem_te_signin');
		wp_enqueue_style('icons-' . $attr['signin_icon_pack']);
		wp_enqueue_style('icons-' . $attr['signout_icon_pack']);
		wp_enqueue_style('thegem-te-signin');
	}

	public function front_editor_scripts($attr) {
		$attr = shortcode_atts(array(
			'signin_icon_pack' => 'thegem-header',
			'signout_icon_pack' => 'thegem-header',
		), $attr, 'thegem_te_signin');
		wp_enqueue_style('icons-' . $attr['signin_icon_pack']);
		wp_enqueue_style('icons-' . $attr['signout_icon_pack']);
		wp_enqueue_style('thegem-te-signin');
	}
    
    public function is_my_account_page_exist() {
        if (thegem_is_plugin_active('woocommerce/woocommerce.php') && wc_get_page_id( 'myaccount' ) != '-1') {
	        $post = get_post( wc_get_page_id( 'myaccount' ) );
            
            return $post->post_status == 'publish';
        }
	    
        return false;
    }

	public function shortcode_output($atts, $content = '') {
		$params = shortcode_atts(array_merge(array(
			'layout_type' => 'link',
			'signin_link_type' => 'signin',
			'signin_link_custom' => '',
			'signin_icon' => '0',
			'signin_icon_pack' => 'thegem-header',
			'signin_icon_elegant' => '',
			'signin_icon_material' => '',
			'signin_icon_fontawesome' => '',
			'signin_icon_thegemdemo' => '',
			'signin_icon_userpack' => '',
			'signin_icon_thegemheader' => '',
			'signin_text' => '1',
			'signin_text_value' => 'Sign In',
			'signout_link_type' => 'signout',
			'signout_link_custom' => '',
			'signout_icon' => '0',
			'signout_icon_pack' => 'thegem-header',
			'signout_icon_elegant' => '',
			'signout_icon_material' => '',
			'signout_icon_fontawesome' => '',
			'signout_icon_thegemdemo' => '',
			'signout_icon_userpack' => '',
			'signout_icon_thegemheader' => '',
			'signout_text' => '1',
			'signout_text_value' => 'Sign Out',
			'icon_size' => 'small',
			'icon_size_custom' => '',
			'icon_color' => '',
			'icon_color_hover' => '',
			'text_style' => '',
			'text_font_weight' => '',
			'text_letter_spacing' => '',
			'text_transform' => '',
			'text_max_width' => '',
			'text_color' => '',
			'text_color_hover' => '',
			'btn_border_width' => '',
			'btn_border_radius' => '',
			'btn_background_color' => '',
			'btn_background_color_hover' => '',
			'btn_border_color' => '',
			'btn_border_color_hover' => '',
		),
            thegem_templates_design_options_extract(),
            thegem_templates_extra_options_extract()
        ), $atts, 'thegem_te_signin');

		// Init Design Options Params
		$custom_css = $uniqid = $signin_icon = $signout_icon = '';
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-signin', $params);

		//General
		$el_id = $el_class = '';
		if (!empty($params['element_id'])){ $el_id = $params['element_id']; }
		if (!empty($params['element_class'])){ $el_class = $params['element_class']; }
		
		//Output SignIn Url
		$output_signin_url = wp_login_url(get_permalink());
		if($this->is_my_account_page_exist()) {
			$output_signin_url = get_permalink( wc_get_page_id( 'myaccount' ));
		}
		$output_signin_url_target = '_self';
		$output_signin_url_title = $output_signin_url_rel = '';
		$custom_signin_link = vc_build_link($params['signin_link_custom']);
		if (!empty($custom_signin_link['url'])) {
			$output_signin_url = $custom_signin_link['url'];
			$output_signin_url_target = $custom_signin_link['target'];
			$output_signin_url_title = $custom_signin_link['title'];
			$output_signin_url_rel = $custom_signin_link['rel'];
		}
		
		//Output SignOut Url
		$output_signout_url = wp_logout_url(home_url());
		if($this->is_my_account_page_exist()) {
			$output_signout_url = wp_logout_url(get_permalink( wc_get_page_id( 'myaccount' )));
		}
		$output_signout_url_target = '_self';
		$output_signout_url_title = $output_signout_url_rel = '';
		$custom_signout_link = vc_build_link($params['signout_link_custom']);
		if (!empty($custom_signout_link['url'])) {
			$output_signout_url = $custom_signout_link['url'];
			$output_signout_url_target = $custom_signout_link['target'];
			$output_signout_url_title = $custom_signout_link['title'];
			$output_signout_url_rel = $custom_signout_link['rel'];
		}
		
		$text_styled_class = implode(' ', array($params['text_style'], $params['text_font_weight']));

		// Init wishlist
		ob_start(); ?>

		<div <?php if ($el_id): ?>id="<?=esc_attr($el_id); ?>"<?php endif;?>
             class="thegem-te-signin <?= esc_attr($el_class); ?>
             <?= esc_attr($uniqid); ?>"
            <?=thegem_data_editor_attribute($uniqid . '-editor')?>>
            
			<?php if (is_user_logged_in()) : ?>
                <a class="signin-link signin-link-type--<?=$params['layout_type']?>"
                    href="<?= $output_signout_url ?>"
                    target="<?=$output_signout_url_target?>"
                    <?php if (!empty($output_signout_url_title)): ?>title="<?=$output_signout_url_title?>"<?php endif; ?>
                    <?php if (!empty($output_signout_url_rel)): ?>rel="<?=$output_signout_url_rel?>"<?php endif; ?>>
                    
                    <?php if (!empty($params['signout_icon'])): ?>
                        <span class="signin-link-icon signin-link-icon--<?=$params['icon_size']?>">
                            <?php if (isset($params['signout_icon_pack']) && $params['signout_icon_' . str_replace("-", "", $params['signout_icon_pack'])] != '') {
                                echo thegem_build_icon($params['signout_icon_pack'], $params['signout_icon_' . str_replace("-", "", $params['signout_icon_pack'])]);
                            } ?>
                        </span>
                    <?php endif; ?>
			
			        <?php if (!empty($params['signout_text'])): ?>
                        <span class="signin-link-text <?=$text_styled_class ?>">
                            <?= $params['signout_text_value'] ?>
                        </span>
                    <?php endif; ?>
                </a>
			<?php else : ?>
                <a class="signin-link signin-link-type--<?=$params['layout_type']?>"
                    href="<?= $output_signin_url ?>"
                    target="<?=$output_signin_url_target?>"
                    <?php if (!empty($output_signin_url_title)): ?>title="<?=$output_signin_url_title?>"<?php endif; ?>
                    <?php if (!empty($output_signin_url_rel)): ?>rel="<?=$output_signin_url_rel?>"<?php endif; ?>>
	
	                <?php if (!empty($params['signin_icon'])): ?>
                        <span class="signin-link-icon signin-link-icon--<?=$params['icon_size']?>">
                            <?php if (isset($params['signin_icon_pack']) && $params['signin_icon_' . str_replace("-", "", $params['signin_icon_pack'])] != '') {
	                            echo thegem_build_icon($params['signin_icon_pack'], $params['signin_icon_' . str_replace("-", "", $params['signin_icon_pack'])]);
                            } ?>
                        </span>
	                <?php endif; ?>
	
	                <?php if (!empty($params['signin_text'])): ?>
                        <span class="signin-link-text <?=$text_styled_class ?>">
                            <?= $params['signin_text_value'] ?>
                        </span>
	                <?php endif; ?>
                </a>
			<?php endif;?>
   
		</div>

		<?php
  
		$customize = '.thegem-te-signin.'.$uniqid;
		
		// Text Styles
		if ($params['text_letter_spacing'] != '') {
			$custom_css .= $customize.' .signin-link-text {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
		}
		if ($params['text_transform'] != '') {
			$custom_css .= $customize.' .signin-link-text {text-transform: ' . $params['text_transform'] . ';}';
		}
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .signin-link-text {color: ' . $params['text_color'] . ';}';
		}
		if (!empty($params['text_color_hover'])) {
			$custom_css .= $customize.' .signin-link:hover .signin-link-text {color: ' . $params['text_color_hover'] . ';}';
		}
		if (!empty($params['text_max_width'])) {
			$custom_css .= $customize.' .signin-link-text {max-width: ' . $params['text_max_width'] . 'px;}';
		}
  
		// Icon Styles
		if(!empty($params['icon_size_custom']) && $params['icon_size'] == 'custom') {
			$custom_css .= $customize.' .signin-link-icon i {font-size: '.esc_attr($params['icon_size_custom']).'px; line-height: '. $params['icon_size_custom'] .'px;}';
		}
		if(!empty($params['icon_color'])) {
			$custom_css .= $customize.' .signin-link-icon {color: '.$params['icon_color'].';}';
		}
		if(!empty($params['icon_color_hover'])) {
			$custom_css .= $customize.' .signin-link:hover .signin-link-icon {color: '.$params['icon_color_hover'].';}';
		}
		
		// Button Styles
        if ($params['layout_type'] == 'btn') {
	        if(!empty($params['btn_border_width'])) {
		        $custom_css .= $customize.' .signin-link.signin-link-type--btn {border-width: '.$params['btn_border_width'].'px;}';
	        }
	        if(!empty($params['btn_border_radius']) || $params['btn_border_radius'] == 0) {
		        $custom_css .= $customize.' .signin-link.signin-link-type--btn {border-radius: '.$params['btn_border_radius'].'px;}';
	        }
	        if(!empty($params['btn_background_color'])) {
		        $custom_css .= $customize.' .signin-link.signin-link-type--btn {background-color: '.$params['btn_background_color'].';}';
	        }
	        if(!empty($params['btn_background_color_hover'])) {
		        $custom_css .= $customize.' .signin-link.signin-link-type--btn:hover {background-color: '.$params['btn_background_color_hover'].';}';
	        }
	        if(!empty($params['btn_border_color'])) {
		        $custom_css .= $customize.' .signin-link.signin-link-type--btn {border-color: '.$params['btn_border_color'].';}';
	        }
	        if(!empty($params['btn_border_color_hover'])) {
		        $custom_css .= $customize.' .signin-link.signin-link-type--btn:hover {border-color: '.$params['btn_border_color_hover'].';}';
	        }
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
	
	public function set_layout_params() {
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Layout', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Type', 'thegem'),
			'param_name' => 'layout_type',
			'value' => array(
                __('Icon/Link', 'thegem') => 'link',
                __('Button', 'thegem') => 'btn',
            ),
			'std' => 'link',
			'dependency' => array(
				'callback' => 'thegem_te_signin_callback'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		return $result;
	}
 
	public function set_signin_params() {
		$result = array();
		$group = __('General', 'thegem');
        $link_type_title = $this->is_my_account_page_exist() ? 'Sign In / My Account' : 'Sign In';
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Sign In User', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Link Type', 'thegem'),
			'param_name' => 'signin_link_type',
			'value' => array(
				__($link_type_title, 'thegem') => 'signin',
				__('Custom Selection', 'thegem') => 'custom',
			),
			'std' => 'signin',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'vc_link',
			'heading' => __( 'URL (Link)', 'thegem' ),
			'param_name' => 'signin_link_custom',
			'dependency' => array(
				'element' => 'signin_link_type',
				'value' => 'custom'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'description' => __('Add custom link.', 'thegem'),
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Sign In Icon', 'thegem'),
			'param_name' => 'signin_icon',
			'value' => array(__('Show', 'thegem') => '1'),
			'std' => '0',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Icon pack', 'thegem'),
			'param_name' => 'signin_icon_pack',
			'value' => array_merge(array(
				__('Elegant', 'thegem') => 'elegant',
				__('Material Design', 'thegem') => 'material',
				__('FontAwesome', 'thegem') => 'fontawesome',
				__('Header Icons', 'thegem') => 'thegem-header',
				__('Additional', 'thegem') => 'thegemdemo')
			),
			'std' => 'thegem-header',
			'dependency' => array(
				'element' => 'signin_icon',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'thegem_icon',
			'heading' => __('Icon', 'thegem'),
			'param_name' => 'signin_icon_elegant',
			'icon_pack' => 'elegant',
			'dependency' => array(
				'element' => 'signin_icon_pack',
				'value' => array('elegant')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
            'type' => 'thegem_icon',
            'heading' => __('Icon', 'thegem'),
            'param_name' => 'signin_icon_material',
            'icon_pack' => 'material',
            'dependency' => array(
                'element' => 'signin_icon_pack',
                'value' => array('material')
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
        );
		
		$result[] = array(
            'type' => 'thegem_icon',
            'heading' => __('Icon', 'thegem'),
            'param_name' => 'signin_icon_fontawesome',
            'icon_pack' => 'fontawesome',
            'dependency' => array(
                'element' => 'signin_icon_pack',
                'value' => array('fontawesome')
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
        );
		
		$result[] = array(
            'type' => 'thegem_icon',
            'heading' => __('Icon', 'thegem'),
            'param_name' => 'signin_icon_thegemdemo',
            'icon_pack' => 'thegemdemo',
            'dependency' => array(
                'element' => 'signin_icon_pack',
                'value' => array('thegemdemo')
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
        );
		
		$result[] = array(
            'type' => 'thegem_icon',
            'heading' => __('Icon', 'thegem'),
            'param_name' => 'signin_icon_thegemheader',
            'icon_pack' => 'thegem-header',
            'dependency' => array(
                'element' => 'signin_icon_pack',
                'value' => array('thegem-header')
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
        );
		
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Sign In Text', 'thegem'),
			'param_name' => 'signin_text',
			'value' => array(__('Show', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textarea',
			'heading' => __('Text', 'thegem'),
			'param_name' => 'signin_text_value',
			'std' => '',
			'dependency' => array(
				'element' => 'signin_text',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		return $result;
	}
 
	public function set_signout_params() {
		$result = array();
		$group = __('General', 'thegem');
		$link_type_title = $this->is_my_account_page_exist() ? 'Sign Out / My Account' : 'Sign Out';
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Sign Out User', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Link Type', 'thegem'),
			'param_name' => 'signout_link_type',
			'value' => array(
				__($link_type_title, 'thegem') => 'signout',
				__('Custom Selection', 'thegem') => 'custom',
			),
			'std' => 'signout',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'vc_link',
			'heading' => __( 'URL (Link)', 'thegem' ),
			'param_name' => 'signout_link_custom',
			'dependency' => array(
				'element' => 'signout_link_type',
				'value' => 'custom'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'description' => __('Add custom link.', 'thegem'),
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Sign Out Icon', 'thegem'),
			'param_name' => 'signout_icon',
			'value' => array(__('Show', 'thegem') => '1'),
			'std' => '0',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Icon pack', 'thegem'),
			'param_name' => 'signout_icon_pack',
			'value' => array_merge(array(
				__('Elegant', 'thegem') => 'elegant',
				__('Material Design', 'thegem') => 'material',
				__('FontAwesome', 'thegem') => 'fontawesome',
				__('Header Icons', 'thegem') => 'thegem-header',
				__('Additional', 'thegem') => 'thegemdemo')
			),
			'std' => 'thegem-header',
			'dependency' => array(
				'element' => 'signout_icon',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'thegem_icon',
			'heading' => __('Icon', 'thegem'),
			'param_name' => 'signout_icon_elegant',
			'icon_pack' => 'elegant',
			'dependency' => array(
				'element' => 'signout_icon_pack',
				'value' => array('elegant')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
            'type' => 'thegem_icon',
            'heading' => __('Icon', 'thegem'),
            'param_name' => 'signout_icon_material',
            'icon_pack' => 'material',
            'dependency' => array(
                'element' => 'signout_icon_pack',
                'value' => array('material')
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
        );
		
		$result[] = array(
            'type' => 'thegem_icon',
            'heading' => __('Icon', 'thegem'),
            'param_name' => 'signout_icon_fontawesome',
            'icon_pack' => 'fontawesome',
            'dependency' => array(
                'element' => 'signout_icon_pack',
                'value' => array('fontawesome')
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
        );
		
		$result[] = array(
            'type' => 'thegem_icon',
            'heading' => __('Icon', 'thegem'),
            'param_name' => 'signout_icon_thegemdemo',
            'icon_pack' => 'thegemdemo',
            'dependency' => array(
                'element' => 'signout_icon_pack',
                'value' => array('thegemdemo')
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
        );
		
		$result[] = array(
            'type' => 'thegem_icon',
            'heading' => __('Icon', 'thegem'),
            'param_name' => 'signout_icon_thegemheader',
            'icon_pack' => 'thegem-header',
            'dependency' => array(
                'element' => 'signout_icon_pack',
                'value' => array('thegem-header')
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
        );
		
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Sign Out Text', 'thegem'),
			'param_name' => 'signout_text',
			'value' => array(__('Show', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textarea',
			'heading' => __('Text', 'thegem'),
			'param_name' => 'signout_text_value',
			'std' => '',
			'dependency' => array(
				'element' => 'signout_text',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		return $result;
	}
 
	public function set_style_params() {
		$result = array();
		$group = __('Style', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Button Style', 'thegem'),
			'param_name' => 'style_delim_head_signin_btn',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Width', 'thegem'),
			'param_name' => 'btn_border_width',
			'dependency' => array(
				'element' => 'layout_type',
				'value' => 'btn'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
  
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Radius', 'thegem'),
			'param_name' => 'btn_border_radius',
			'dependency' => array(
				'element' => 'layout_type',
				'value' => 'btn'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color', 'thegem'),
			'param_name' => 'btn_background_color',
			'dependency' => array(
				'element' => 'layout_type',
				'value' => 'btn'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color Hover', 'thegem'),
			'param_name' => 'btn_background_color_hover',
			'dependency' => array(
				'element' => 'layout_type',
				'value' => 'btn'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color', 'thegem'),
			'param_name' => 'btn_border_color',
			'dependency' => array(
				'element' => 'layout_type',
				'value' => 'btn'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color Hover', 'thegem'),
			'param_name' => 'btn_border_color_hover',
			'dependency' => array(
				'element' => 'layout_type',
				'value' => 'btn'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Icon Style', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Size', 'thegem'),
			'param_name' => 'icon_size',
			'value' => array(
				__('Tiny', 'thegem') => 'tiny',
				__('Small', 'thegem') => 'small',
				__('Medium', 'thegem') => 'medium',
				__('Custom', 'thegem') => 'custom'
			),
			'std' => 'small',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Custom size', 'thegem'),
			'param_name' => 'icon_size_custom',
			'dependency' => array(
				'element' => 'icon_size',
				'value' => array('custom')
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
            'type' => 'colorpicker',
            'heading' => __('Color', 'thegem'),
            'param_name' => 'icon_color',
            'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
        );
		
		$result[] = array(
            'type' => 'colorpicker',
            'heading' => __('Hover color', 'thegem'),
            'param_name' => 'icon_color_hover',
            'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
        );
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
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
			'std' => '',
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
			'std' => '',
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
			'type' => 'textfield',
			'heading' => __('Max Width', 'thegem'),
			'param_name' => 'text_max_width',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Color', 'thegem'),
			'param_name' => 'text_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Hover color', 'thegem'),
			'param_name' => 'text_color_hover',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		return $result;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Sign In / Sign Out', 'thegem'),
			'base' => 'thegem_te_signin',
			'icon' => 'thegem-icon-wpb-ui-element-signin',
			'category' => __('Header Builder', 'thegem'),
			'description' => __('Sign In / Sign Out (Header Builder)', 'thegem'),
			'params' => array_merge(
			
			    /* General - Layout */
				$this->set_layout_params(),
    
				/* General - Sign In*/
                $this->set_signin_params(),
				
				/* General - Sign Out*/
				$this->set_signout_params(),
				
				/* Style - Text / Icon*/
                $this->set_style_params(),

				/* General - Extra */
				thegem_set_elements_extra_options(),

				/* Design Options */
				thegem_set_elements_design_options()
			),
		);
	}
}

$templates_elements['thegem_te_signin'] = new TheGem_Template_Element_Signin();
$templates_elements['thegem_te_signin']->init();
