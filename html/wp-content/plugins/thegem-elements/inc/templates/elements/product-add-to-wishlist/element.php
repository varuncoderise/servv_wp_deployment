<?php

class TheGem_Template_Element_Product_Add_To_Wishlist extends TheGem_Product_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_product_add_to_wishlist';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'alignment' => 'left',
			'icon' => '1',
			'wishlist_add_icon_pack' => 'elegant',
			'wishlist_add_icon_elegant' => '',
			'wishlist_add_icon_material' => '',
			'wishlist_add_icon_fontawesome' => '',
			'wishlist_add_icon_thegemdemo' => '',
			'wishlist_add_icon_thegemheader' => '',
			'wishlist_add_icon_userpack' => '',
			'wishlist_added_icon_pack' => 'elegant',
			'wishlist_added_icon_elegant' => '',
			'wishlist_added_icon_material' => '',
			'wishlist_added_icon_fontawesome' => '',
			'wishlist_added_icon_thegemdemo' => '',
			'wishlist_added_icon_thegemheader' => '',
			'wishlist_added_icon_userpack' => '',
			'icon_horizontal_align' => 'left',
			'icon_vertical_align' => 'center',
			'icon_size' => 'custom',
			'icon_size_custom' => '24',
			'text' => '0',
			'text_custom' => 'Add to Wishlist',
			'text_remove' => 'Remove from Wishlist',
			'text_style' => 'title-default',
			'text_font_weight' => '',
			'text_font_style' => '',
			'text_letter_spacing' => '',
			'text_transform' => '',
			'icon_color' => '',
			'icon_color_hover' => '',
			'text_color' => '',
			'text_color_hover' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_design_options_extract('single-product')
        ), $atts, 'thegem_te_product_add_to_wishlist');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-product-add-to-wishlist', $params);
		
		// Init Icon Fonts
		wp_enqueue_style('icons-' . $params['wishlist_add_icon_pack']);
		wp_enqueue_style('icons-' . $params['wishlist_added_icon_pack']);
		
		// Init Wishlist
		ob_start();
		$product = thegem_templates_init_product();
  
		if (empty($product) || !thegem_is_plugin_active('yith-woocommerce-wishlist/init.php')) {
			ob_end_clean();
			return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), '');
		}
		
		$text_styled_class = thegem_te_product_text_styled($params);

		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-product-add-to-wishlist <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
			<?= thegem_data_editor_attribute($uniqid . '-editor') ?>>
   
			<div class="product-add-to-wishlist">
				<?= do_shortcode( '[yith_wcwl_add_to_wishlist thegem_template="1" thegem_product_page="1"]' ); ?>

                <?php if (!empty($params['text']) && $params['text_custom'] != '') : ?>
                    <div class="product-add-to-wishlist-text">
                        <span class="<?= $text_styled_class ?>" data-add-text="<?= esc_html__($params['text_custom'], 'thegem'); ?>" data-remove-text="<?= esc_html__($params['text_remove'], 'thegem'); ?>">
                            <?= esc_html__($params['text_custom'], 'thegem'); ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

			<?= thegem_woocommerce_product_page_ajax_notification() ?>
		</div>

        <script type="text/javascript">
            (function() {
                const wishlistAddIcon = document.querySelector('.thegem-te-product-add-to-wishlist.<?= $uniqid; ?> .yith-wcwl-add-button a i');
                if(wishlistAddIcon) {
                    const wlText = document.querySelector('.thegem-te-product-add-to-wishlist.<?= $uniqid; ?> .product-add-to-wishlist-text span');
                    if(wlText) {
                        wlText.innerHTML = wlText.dataset.addText;
                    }
                }
                const wishlistRemoveIcon = document.querySelector('.thegem-te-product-add-to-wishlist.<?= $uniqid; ?> .yith-wcwl-wishlistexistsremove a i');
                if(wishlistRemoveIcon) {
                    const wlText = document.querySelector('.thegem-te-product-add-to-wishlist.<?= $uniqid; ?> .product-add-to-wishlist-text span');
                    if(wlText) {
                        wlText.innerHTML = wlText.dataset.removeText;
                    }
                }
            })();
        </script>

		<?php
		//Custom Styles
		$customize = '.thegem-te-product-add-to-wishlist.'.$uniqid;
		
		// Layout Styles
		if (!empty($params['alignment'])) {
			$custom_css .= $customize.' {justify-content: ' . $params['alignment'] . '; text-align: ' . $params['alignment'] . ';}';
		}
		
		// Icon Styles
		$add_icon = $add_icon_font = $added_icon = $added_icon_font = $icon_size = '';
		$fonts = array(
			'elegant' => 'ElegantIcons',
			'material' => 'MaterialDesignIcons',
			'fontawesome' => 'FontAwesome',
			'thegemdemo' => 'TheGemDemoIcons',
			'thegemheader' => 'TheGem Header',
			'userpack' => 'UserPack',
		);
		switch ($params['icon_size']) {
			case 'tiny':
				$icon_size = '16px'; break;
			case 'small':
				$icon_size = '24px'; break;
			case 'medium':
				$icon_size = '48px'; break;
			case 'large':
				$icon_size = '96px'; break;
			case 'xlarge':
				$icon_size = '144px'; break;
			default:
				$icon_size = $params['icon_size_custom'].'px';
		}
		
		foreach ($fonts as $key => $font) {
			if (!empty($params['wishlist_add_icon_pack']) && !empty($params['wishlist_add_icon_'.$key])){
				$add_icon = "\\".$params['wishlist_add_icon_'.$key];
				$add_icon_font = $font;
			}
			if (!empty($params['wishlist_added_icon_pack']) && !empty($params['wishlist_added_icon_'.$key])){
				$added_icon = "\\".$params['wishlist_added_icon_'.$key];
				$added_icon_font = $font;
			}
		}
		if (!empty($add_icon_font) && !empty($add_icon)) {
			$custom_css .= $customize.' .gem-icon-add-to-wishlist:before {content: "'.$add_icon.'"; font-family: "'.$add_icon_font.'";}';
		}
		if (!empty($added_icon_font) && !empty($added_icon)) {
			$custom_css .= $customize.' .gem-icon-added-to-wishlist:before {content: "'.$added_icon.'"; font-family: "'.$added_icon_font.'";}';
		}
		if (!empty($params['icon_size'])) {
			$custom_css .= $customize.' .gem-icon-add-to-wishlist:before {font-size: '.$icon_size.' !important;}';
			$custom_css .= $customize.' .gem-icon-added-to-wishlist:before {font-size: '.$icon_size.' !important;}';
		}
		if ($params['icon_horizontal_align'] == 'left') {
			$custom_css .= $customize.' .product-add-to-wishlist .yith-wcwl-add-to-wishlist a {text-align: left;}';
			$custom_css .= $customize.' .product-add-to-wishlist-text {margin-left: calc('.$icon_size.' + 5px);}';
		}
		if ($params['icon_horizontal_align'] == 'right') {
			$custom_css .= $customize.' .product-add-to-wishlist .yith-wcwl-add-to-wishlist a {text-align: right;}';
			$custom_css .= $customize.' .product-add-to-wishlist-text {margin-right: calc('.$icon_size.' + 5px);}';
		}
		if ($params['icon_horizontal_align'] == 'top') {
			$custom_css .= $customize.' .product-add-to-wishlist .yith-wcwl-add-to-wishlist {top: 0;}';
			$custom_css .= $customize.' .product-add-to-wishlist .yith-wcwl-add-to-wishlist a {text-align: center;}';
			$custom_css .= $customize.' .product-add-to-wishlist-text {margin-top: calc('.$icon_size.' + 5px);}';
		}
		if ($params['icon_horizontal_align'] == 'bottom') {
			$custom_css .= $customize.' .product-add-to-wishlist .yith-wcwl-add-to-wishlist {bottom: 0;}';
			$custom_css .= $customize.' .product-add-to-wishlist .yith-wcwl-add-to-wishlist a {text-align: center;}';
			$custom_css .= $customize.' .product-add-to-wishlist-text {margin-bottom: calc('.$icon_size.' + 5px);}';
		}
		if ($params['icon_vertical_align'] == 'top') {
			$custom_css .= $customize.' .product-add-to-wishlist {align-items: flex-start;}';
		}
		if ($params['icon_vertical_align'] == 'bottom') {
			$custom_css .= $customize.' .product-add-to-wishlist {align-items: flex-end;}';
		}
		
		if (empty($params['icon'])) {
			$custom_css .= $customize.' .product-add-to-wishlist .add_to_wishlist > i {opacity: 0;}';
			$custom_css .= $customize.' .product-add-to-wishlist .remove_from_wishlist > i {opacity: 0;}';
			$custom_css .= $customize.' .product-add-to-wishlist-text {margin: 0;}';
		}
        
        // Text style
		if (!empty($params['text'])) {
			$custom_css .= $customize.' .product-add-to-wishlist .yith-wcwl-add-to-wishlist {position: absolute; width: 100%; z-index: 1;}';
		}
		if ($params['text_letter_spacing'] != '') {
			$custom_css .= $customize.' .product-add-to-wishlist .product-add-to-wishlist-text span {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
		}
		
		// Appearance Styles
		if (!empty($params['icon_color'])) {
			$custom_css .= $customize.' .product-add-to-wishlist .add_to_wishlist {color: ' . $params['icon_color'] . ' !important; }';
			$custom_css .= $customize.' .product-add-to-wishlist .remove_from_wishlist {color: ' . $params['icon_color'] . ' !important; }';
		}
		if (!empty($params['icon_color_hover'])) {
			$custom_css .= $customize.' .product-add-to-wishlist:hover .add_to_wishlist {color: ' . $params['icon_color_hover'] . ' !important; }';
			$custom_css .= $customize.' .product-add-to-wishlist:hover .remove_from_wishlist {color: ' . $params['icon_color_hover'] . ' !important; }';
		}
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .product-add-to-wishlist .product-add-to-wishlist-text span {color: ' . $params['text_color'] . ' !important; }';
		}
		if (!empty($params['text_color_hover'])) {
			$custom_css .= $customize.' .product-add-to-wishlist:hover .product-add-to-wishlist-text span {color: ' . $params['text_color_hover'] . ' !important; }';
		}

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}

		$return_html = $css_output.$return_html;
		return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), $return_html);
	}
    
    public function set_layout_params() {
	    $result = array();
	
	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('Layout', 'thegem'),
		    'param_name' => 'layout_delim_head',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
		    'group' => __('General', 'thegem')
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
            'group' => 'General',
        );
	
	    return $result;
    }
	
	public function set_icon_params() {
		$result = array();
		$packs = array(
            'wishlist_add' => 'Wishlist Add',
            'wishlist_added' => 'Wishlist Remove'
        );
        $group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Wishlist Icon', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
			'group' => $group
		);
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Wishlist Icon', 'thegem'),
			'param_name' => 'icon',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		
		foreach ($packs as $key => $pack) {
			$result[] = array(
				'type' => 'dropdown',
				'heading' => __($pack.' Icon pack', 'thegem'),
				'param_name' => $key.'_icon_pack',
				'value' => array_merge(array(
					__('Elegant', 'thegem') => 'elegant',
					__('Material Design', 'thegem') => 'material',
					__('FontAwesome', 'thegem') => 'fontawesome',
					__('Header Icons', 'thegem') => 'thegem-header',
					__('Additional', 'thegem') => 'thegemdemo'),
					thegem_userpack_to_dropdown()
				),
				'dependency' => array(
					'element' => 'icon',
					'value' => '1',
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			$result[] = array(
				'type' => 'thegem_icon',
				'heading' => __($pack.' Icon', 'thegem'),
				'param_name' => $key.'_icon_elegant',
				'icon_pack' => 'elegant',
				'dependency' => array(
					'element' => $key.'_icon_pack',
					'value' => 'elegant'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			$result[] = array(
				'type' => 'thegem_icon',
				'heading' => __($pack.' Icon', 'thegem'),
				'param_name' => $key.'_icon_material',
				'icon_pack' => 'material',
				'dependency' => array(
					'element' => $key.'_icon_pack',
					'value' => 'material'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			$result[] = array(
				'type' => 'thegem_icon',
				'heading' => __($pack.' Icon', 'thegem'),
				'param_name' => $key.'_icon_fontawesome',
				'icon_pack' => 'fontawesome',
				'dependency' => array(
					'element' => $key.'_icon_pack',
					'value' => 'fontawesome'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			$result[] = array(
				'type' => 'thegem_icon',
				'heading' => __($pack.' Icon', 'thegem'),
				'param_name' => $key.'_icon_thegemdemo',
				'icon_pack' => 'thegemdemo',
				'dependency' => array(
					'element' => $key.'_icon_pack',
					'value' => 'thegemdemo'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			$result[] = array(
				'type' => 'thegem_icon',
				'heading' => __($pack.' Icon', 'thegem'),
				'param_name' => $key.'_icon_thegemheader',
				'icon_pack' => 'thegem-header',
				'dependency' => array(
					'element' => $key.'_icon_pack',
					'value' => 'thegem-header'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			$result[] = array(
				'type' => 'thegem_icon',
				'heading' => __($pack.' Icon', 'thegem'),
				'param_name' => $key.'_icon_userpack',
				'icon_pack' => 'userpack',
				'dependency' => array(
					'element' => $key.'_icon_pack',
					'value' => 'userpack'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
        }
        
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Position to Text', 'thegem'),
			'param_name' => 'icon_horizontal_align',
			'value' => array(
				__('Left', 'thegem') => 'left',
				__('Top', 'thegem') => 'top',
				__('Right', 'thegem') => 'right',
				__('Bottom', 'thegem') => 'bottom',
			),
			'std' => 'left',
			'dependency' => array(
				'element' => 'icon',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Vertical Align', 'thegem'),
			'param_name' => 'icon_vertical_align',
			'value' => array(
				__('Top', 'thegem') => 'top',
				__('Center', 'thegem') => 'center',
				__('Bottom', 'thegem') => 'bottom',
			),
			'std' => 'center',
			'dependency' => array(
				'element' => 'icon',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
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
				__('Large', 'thegem') => 'large',
				__('Extra Large', 'thegem') => 'xlarge',
				__('Custom', 'thegem') => 'custom'
			),
			'std' => 'custom',
			'dependency' => array(
				'element' => 'icon',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Custom size', 'thegem'),
			'param_name' => 'icon_size_custom',
			'dependency' => array(
				'element' => 'icon_size',
				'value' => 'custom'
			),
			'std' => '24',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		return $result;
	}
	
	public function set_text_params () {
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Wishlist Text', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Wishlist Text', 'thegem'),
			'param_name' => 'text',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '0',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('"Add to wishlist" Text', 'thegem'),
			'param_name' => 'text_custom',
			'value' => 'Add to Wishlist',
			'dependency' => array(
				'element' => 'text',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('"Remove from Wishlist" Text', 'thegem'),
			'param_name' => 'text_remove',
			'value' => 'Remove from Wishlist',
			'dependency' => array(
				'element' => 'text',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Style', 'thegem'),
			'param_name' => 'text_style',
			'value' => array(
				__('Default', 'thegem') => 'title-default',
				__('Title H1', 'thegem') => 'title-h1',
				__('Title H2', 'thegem') => 'title-h2',
				__('Title H3', 'thegem') => 'title-h3',
				__('Title H4', 'thegem') => 'title-h4',
				__('Title H5', 'thegem') => 'title-h5',
				__('Title H6', 'thegem') => 'title-h6',
				__('Title xLarge', 'thegem') => 'title-xlarge',
				__('Styled Subtitle', 'thegem') => 'styled-subtitle',
				__('Main Menu', 'thegem') => 'title-main-menu',
				__('Body', 'thegem') => 'title-text-body',
				__('Tiny Body', 'thegem') => 'title-text-body-tiny',
			),
			'dependency' => array(
				'element' => 'text',
				'value' => '1',
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
			'dependency' => array(
				'element' => 'text',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Letter Spacing', 'thegem'),
			'param_name' => 'text_letter_spacing',
			'dependency' => array(
				'element' => 'text',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Text Transform', 'thegem'),
			'param_name' => 'text_transform',
			'value' => array(
				__('Default', 'thegem') => '',
				__('None', 'thegem') => 'transform-none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'dependency' => array(
				'element' => 'text',
				'value' => '1',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		return $result;
	}
    
    public function set_appearance_params() {
	    $result = array();
	    $group = __('General', 'thegem');
	
	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('Appearance', 'thegem'),
		    'param_name' => 'layout_delim_head',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
		    'group' => $group
	    );
	    $result[] = array(
            'type' => 'colorpicker',
            'heading' => __('Icon Color', 'thegem'),
            'param_name' => 'icon_color',
            'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
        );
	    $result[] = array(
            'type' => 'colorpicker',
            'heading' => __('Icon Color Hover', 'thegem'),
            'param_name' => 'icon_color_hover',
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => 'General'
        );
	    $result[] = array(
            'type' => 'colorpicker',
            'heading' => __('Text Color', 'thegem'),
            'param_name' => 'text_color',
            'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
        );
	    $result[] = array(
            'type' => 'colorpicker',
            'heading' => __('Text Color Hover', 'thegem'),
            'param_name' => 'text_color_hover',
            'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
        );
        
	    return $result;
    }

	public function shortcode_settings() {

		return array(
			'name' => __('Product Add To Wishlist', 'thegem'),
			'base' => 'thegem_te_product_add_to_wishlist',
			'icon' => 'thegem-icon-wpb-ui-element-product-add-to-wishlist',
			'category' => __('Single Product Builder', 'thegem'),
			'description' => __('Product Add To Wishlist (Product Builder)', 'thegem'),
			'params' => array_merge(

			    /* General - Layout */
                $this->set_layout_params(),
				
				/* General - Icon */
				$this->set_icon_params(),
				
				/* Content -- Text */
				$this->set_text_params(),

				/* General - Appearance */
				$this->set_appearance_params(),
				
				/* Extra Options */
				thegem_set_elements_extra_options(),
				
				/* Flex Options */
				thegem_set_elements_design_options('single-product')
			),
		);
	}
}

$templates_elements['thegem_te_product_add_to_wishlist'] = new TheGem_Template_Element_Product_Add_To_Wishlist();
$templates_elements['thegem_te_product_add_to_wishlist']->init();
