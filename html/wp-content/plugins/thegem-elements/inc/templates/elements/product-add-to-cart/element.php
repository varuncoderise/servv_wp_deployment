<?php

class TheGem_Template_Element_Product_Add_To_Cart extends TheGem_Product_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_product_add_to_cart';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'general_layout' => 'column',
			'add_to_cart_section_layout' => 'row',
			'attributes_section_layout' => 'column',
			'horizontal_alignment' => 'left',
			'add_to_cart_btn' => '1',
			'add_to_cart_btn_ajax' => '1',
			'add_to_cart_btn_width' => 'default',
			'add_to_cart_btn_width_custom_desktop' => '',
			'add_to_cart_btn_width_custom_tablet' => '',
			'add_to_cart_btn_width_custom_mobile' => '',
			'add_to_cart_btn_border_width' => '',
			'add_to_cart_btn_border_radius' => '',
			'add_to_cart_btn_text_color' => '',
			'add_to_cart_btn_text_color_hover' => '',
			'add_to_cart_btn_background_color' => '',
			'add_to_cart_btn_background_color_hover' => '',
			'add_to_cart_btn_border_color' => '',
			'add_to_cart_btn_border_color_hover' => '',
			'add_to_wishlist_btn' => '1',
			'add_to_wishlist_btn_color' => '',
			'add_to_wishlist_btn_color_hover' => '',
			'add_to_wishlist_btn_color_filled' => '',
			'amount_control' => '1',
			'amount_control_width' => 'default',
			'amount_control_width_custom_desktop' => '',
			'amount_control_width_custom_tablet' => '',
			'amount_control_width_custom_mobile' => '',
			'amount_control_text_color' => '',
			'amount_control_background_color' => '',
			'amount_control_border_color' => '',
			'amount_control_separator_color' => '',
			'attributes' => '1',
			'attribute_label' => 'side',
			'attribute_label_width' => '',
			'attribute_label_color' => '',
			'attribute_element_text_color' => '',
			'attribute_element_border_color' => '',
			'attribute_element_background_color' => '',
			'attribute_element_text_color_active' => '',
			'attribute_element_border_color_active' => '',
			'attribute_element_background_color_active' => '',
			'price_color' => '',
			'price_color_old' => '',
			'price_color_suffix' => '',
			'in_stock_amount' => '1',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_design_options_extract('single-product')
        ), $atts, 'thegem_te_product_add_to_cart');

		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-product-add-to-cart', $params);

		// Init Add_To_Cart
		ob_start();
		$product = thegem_templates_init_product();

		if (empty($product)) {
			ob_end_clean();
			return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), '');
		}

        $isAjaxLoad = $product->get_type() != 'external' && $product->get_type() != 'grouped';

		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-product-add-to-cart <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
             <?=thegem_data_editor_attribute($uniqid.'-editor')?>
             <?php if ($isAjaxLoad):?>data-ajax-load="<?=$params['add_to_cart_btn_ajax']?>"<?php endif; ?>>

            <?= woocommerce_template_single_add_to_cart() ?>
            <?= thegem_woocommerce_product_page_ajax_notification() ?>
		</div>

		<?php
		// Custom Styles
		$customize = '.thegem-te-product-add-to-cart.'.$uniqid;
		$resolution = array('desktop', 'tablet', 'mobile');
		$unit = 'px';

		// General Layout
		if (!empty($params['general_layout'])) {
			$custom_css .= $customize.' form.cart {flex-direction: ' . $params['general_layout'] . '; }';
		}
		if ($params['general_layout'] == 'row') {
			$custom_css .= $customize.' form.cart table.variations {width: auto;}';
			$custom_css .= $customize.' form.cart .single_variation_wrap {margin-top: 0px;}';
        }

		// Attributes Layout
		if (!empty($params['attributes_section_layout'])) {
			$custom_css .= $customize.' form.cart table.variations tbody {flex-direction: ' . $params['attributes_section_layout'] . ';}';
		}
		if ($params['attributes_section_layout'] == 'row') {
			$custom_css .= $customize.' form.cart table.variations {width: auto; flex: auto;}';
			$custom_css .= $customize.' form.cart table.variations tbody tr {margin-right: 20px;}';
			$custom_css .= $customize.' form.cart table.variations .gem-attribute-options {display: flex; flex-wrap: wrap;}';
		}

		// Add to cart Layout
		if (!empty($params['add_to_cart_section_layout'])) {
			$custom_css .= $customize.' form:not(.variations_form) {flex-direction: ' . $params['add_to_cart_section_layout'] . '; }';
			$custom_css .= $customize.' form.cart .woocommerce-variation-add-to-cart {flex-direction: ' . $params['add_to_cart_section_layout'] . '; }';
		}
		if ($params['add_to_cart_section_layout'] == 'column') {
			$custom_css .= $customize.' form.cart .quantity {margin-right: 0px; margin-bottom: 20px;}';
			$custom_css .= $customize.' form.cart .single_add_to_cart_button {margin-right: 0px !important;}';
			$custom_css .= $customize.' form.cart .yith-wcwl-add-to-wishlist {margin-left: 0;}';
			$custom_css .= $customize.' form.cart .yith-wcwl-add-to-wishlist a {width: auto !important;}';
		}

		// Horizontal Alignment
		if ($params['horizontal_alignment'] == 'center') {
			$custom_css .= $customize.' form.cart {justify-content: center;}';
			$custom_css .= $customize.' form.cart table.variations tbody {justify-content: center;}';
			$custom_css .= $customize.', '.$customize.' form.cart table.variations th.label {text-align: center;}';
			$custom_css .= $customize.' form.cart .single_variation_wrap {justify-content: center;}';
			$custom_css .= $customize.' form.cart .single_variation_wrap .woocommerce-variation {justify-content: center;}';
			$custom_css .= $customize.' form.cart .woocommerce-variation-add-to-cart {justify-content: center;}';
			$custom_css .= $customize.' form.cart .product-page__reset-variations {justify-content: center;}';
		}
		if ($params['general_layout'] == 'row' && $params['horizontal_alignment'] == 'center') {
			$custom_css .= $customize.' form.cart {align-items: flex-start;}';
        }
        if ($params['attributes_section_layout'] == 'column' && $params['horizontal_alignment'] == 'center') {
			$custom_css .= $customize.' form.cart table.variations tbody {align-items: center;}';
        }
		if ($params['add_to_cart_section_layout'] == 'column' && $params['horizontal_alignment'] == 'center') {
			$custom_css .= $customize.' form:not(.variations_form) {align-items: center;}';
			$custom_css .= $customize.' form.cart .single_variation_wrap {align-items: center;}';
			$custom_css .= $customize.' form.cart .single_variation_wrap .woocommerce-variation {align-items: center;}';
			$custom_css .= $customize.' form.cart .woocommerce-variation-add-to-cart {align-items: center;}';
		}
		if ($params['horizontal_alignment'] == 'right') {
			$custom_css .= $customize.' form.cart table.variations tbody {justify-content: flex-end;}';
			$custom_css .= $customize.', '.$customize.' form.cart table.variations th.label {text-align: right;}';
			$custom_css .= $customize.' form.cart .single_variation_wrap {justify-content: flex-end;}';
			$custom_css .= $customize.' form.cart .single_variation_wrap .woocommerce-variation {justify-content: flex-end;}';
			$custom_css .= $customize.' form.cart .woocommerce-variation-add-to-cart {justify-content: flex-end;}';
			$custom_css .= $customize.' form.cart .product-page__reset-variations {justify-content: flex-end;}';
		}
		if ($params['general_layout'] == 'row' && $params['horizontal_alignment'] == 'right') {
			$custom_css .= $customize.' form.cart {align-items: flex-start;}';
		}
		if ($params['attributes_section_layout'] == 'column' && $params['horizontal_alignment'] == 'right') {
			$custom_css .= $customize.' form.cart table.variations tbody {align-items: flex-end;}';
		}
		if ($params['add_to_cart_section_layout'] == 'column' && $params['horizontal_alignment'] == 'right') {
			$custom_css .= $customize.' form.cart .single_variation_wrap {align-items: flex-end;}';
			$custom_css .= $customize.' form.cart .single_variation_wrap .woocommerce-variation {align-items: flex-end;}';
			$custom_css .= $customize.' form.cart .woocommerce-variation-add-to-cart {align-items: flex-end;}';
		}

		// Amount control styled
		if (empty($params['amount_control'])) {
			$custom_css .= $customize.' form.cart .quantity {display: none !important;}';
		}
		if ($params['amount_control_width'] == 'fullwidth') {
			$custom_css .= $customize.' form.cart .single_variation_wrap {width: 100%;}';
			$custom_css .= $customize.' form.cart .quantity {width: 100%; max-width: none; margin: 0 0 5px 0;}';
			$custom_css .= $customize.' form.cart .quantity input {width: 100%;}';
		}
		if ($params['amount_control_width'] == 'custom') {
			foreach ($resolution as $res) {
				if (!empty($params['amount_control_width_custom_'.$res]) || strcmp($params['amount_control_width_custom_'.$res], '0') === 0) {
					$result = str_replace(' ', '', $params['amount_control_width_custom_'.$res]);
					if ($res == 'desktop') {
						$custom_css .= $customize.' form.cart .quantity {width:'.$result.$unit.'; max-width: none;}';
						$custom_css .= $customize.' form.cart .quantity input {width: calc('.$result.$unit.' - 80px);}';
					} else {
						$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' form.cart .quantity {width:'.$result.$unit.'; max-width: none;}}';
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' form.cart .quantity input {width: calc('.$result.$unit.' - 80px);}}';
					}
				}
			}
		}
		if (!empty($params['amount_control_text_color'])) {
			$custom_css .= $customize.' form.cart .quantity button {color: '.$params['amount_control_text_color'].';}';
			$custom_css .= $customize.' form.cart .quantity input {color: '.$params['amount_control_text_color'].';}';
		}
		if (!empty($params['amount_control_background_color'])) {
			$custom_css .= $customize.' form.cart .quantity {background-color: '.$params['amount_control_background_color'].';}';
		}
		if (!empty($params['amount_control_border_color'])) {
			$custom_css .= $customize.' form.cart .quantity {border-color: '.$params['amount_control_border_color'].';}';
		}
		if (!empty($params['amount_control_separator_color'])) {
			$custom_css .= $customize.' form.cart .quantity button:before {background-color: '.$params['amount_control_separator_color'].';}';
		}

		// Add to cart styled
		if (empty($params['add_to_cart_btn'])) {
			$custom_css .= $customize.' form:not(.variations_form) > div {display: none !important;}';
			$custom_css .= $customize.' form.cart .single_variation_wrap {display: none !important;}';
		}
        if ($params['add_to_cart_btn_width'] == 'fullwidth') {
			$custom_css .= $customize.' form.cart .single_variation_wrap {width: 100%;}';
			$custom_css .= $customize.' form.cart .single_add_to_cart_button {width: 100%; margin: 0 !important;}';
		}
		if ($params['add_to_cart_btn_width'] == 'custom') {
			foreach ($resolution as $res) {
				if (!empty($params['add_to_cart_btn_width_custom_'.$res]) || strcmp($params['add_to_cart_btn_width_custom_'.$res], '0') === 0) {
					$result = str_replace(' ', '', $params['add_to_cart_btn_width_custom_'.$res]);
					if ($res == 'desktop') {
						$custom_css .= $customize.' form.cart .single_add_to_cart_button {width:'.$result.$unit.';}';
					} else {
						$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' form.cart .single_add_to_cart_button {width:'.$result.$unit.';}}';
					}
				}
			}
		}
		if (!empty($params['add_to_cart_btn_border_width'])) {
            $add_to_cart_btn_line_height = intval(40 - $params['add_to_cart_btn_border_width']*2);
			$custom_css .= $customize.' form.cart .single_add_to_cart_button {border-width:'.$params['add_to_cart_btn_border_width'].'px !important;}';
			$custom_css .= $customize.' form.cart .single_add_to_cart_button {line-height: '.$add_to_cart_btn_line_height.'px !important;}';
		}
		if (!empty($params['add_to_cart_btn_border_radius'])) {
			$custom_css .= $customize.' form.cart .single_add_to_cart_button {border-radius:'.$params['add_to_cart_btn_border_radius'].'px !important;}';
		}
		if (!empty($params['add_to_cart_btn_text_color'])) {
			$custom_css .= $customize.' form.cart .single_add_to_cart_button {color:'.$params['add_to_cart_btn_text_color'].'!important;}';
		}
		if (!empty($params['add_to_cart_btn_text_color_hover'])) {
			$custom_css .= $customize.' form.cart .single_add_to_cart_button:hover {color:'.$params['add_to_cart_btn_text_color_hover'].'!important;}';
		}
		if (!empty($params['add_to_cart_btn_background_color'])) {
			$custom_css .= $customize.' form.cart .single_add_to_cart_button {background-color:'.$params['add_to_cart_btn_background_color'].'!important;}';
		}
		if (!empty($params['add_to_cart_btn_background_color_hover'])) {
			$custom_css .= $customize.' form.cart .single_add_to_cart_button:hover {background-color:'.$params['add_to_cart_btn_background_color_hover'].'!important;}';
		}
		if (!empty($params['add_to_cart_btn_border_color'])) {
			$custom_css .= $customize.' form.cart .single_add_to_cart_button {border-color:'.$params['add_to_cart_btn_border_color'].'!important;}';
		}
		if (!empty($params['add_to_cart_btn_border_color_hover'])) {
			$custom_css .= $customize.' form.cart .single_add_to_cart_button:hover {border-color:'.$params['add_to_cart_btn_border_color_hover'].'!important;}';
		}

		// Add to wishlist styled
		if (empty($params['add_to_wishlist_btn'])) {
			$custom_css .= $customize.' form.cart .yith-wcwl-add-to-wishlist {display: none;}';
		}
		if (!empty($params['add_to_wishlist_btn_color'])) {
			$custom_css .= $customize.' form.cart .add_to_wishlist {color:'.$params['add_to_wishlist_btn_color'].'!important;}';
		}
		if (!empty($params['add_to_wishlist_btn_color_hover'])) {
			$custom_css .= $customize.' form.cart .add_to_wishlist:hover {color:'.$params['add_to_wishlist_btn_color_hover'].'!important;}';
		}
		if (!empty($params['add_to_wishlist_btn_color_filled'])) {
			$custom_css .= $customize.' form.cart .remove_from_wishlist {color:'.$params['add_to_wishlist_btn_color_filled'].'!important;}';
		}

		//  Attributes styled
		if (empty($params['attributes'])) {
			$custom_css .= $customize.' form.cart table.variations {display: none;}';
		}
		if ($params['attribute_label'] == 'top') {
			$custom_css .= $customize.' form.cart table.variations tr {display: flex; flex-direction: column;}';
			$custom_css .= $customize.' form.cart table.variations th.label {max-width: none;}';
		}
		if ($params['attribute_label'] == 'top' && $params['horizontal_alignment'] == 'center') {
			$custom_css .= $customize.' form.cart table.variations .gem-attribute-options {display: flex; flex-wrap: wrap; justify-content: center;}';
			$custom_css .= $customize.' form.cart table.variations th.label {padding-right: 0;}';
        }
		if ($params['attribute_label'] == 'hide') {
			$custom_css .= $customize.' form.cart table.variations th.label {display: none;}';
		}
        if (!empty($params['attribute_label_width'])) {
			$custom_css .= $customize.' form.cart table.variations th.label {min-width:'.$params['attribute_label_width'].'px;}';
		}
		if (!empty($params['attribute_label_color'])) {
			$custom_css .= $customize.' form.cart table.variations th.label {color:'.$params['attribute_label_color'].'}';
			$custom_css .= $customize.' form.cart table.variations td.label {color:'.$params['attribute_label_color'].'}';
		}
		if (!empty($params['attribute_element_text_color'])) {
			$custom_css .= $customize.' form.cart .thegem-combobox-wrap .thegem-combobox__trigger {color:'.$params['attribute_element_text_color'].';}';
			$custom_css .= $customize.' form.cart .thegem-combobox-wrap .thegem-combobox__options-item {color:'.$params['attribute_element_text_color'].';}';
			$custom_css .= $customize.' form.cart .gem-attribute-selector.type-label .gem-attribute-options li .label {color:'.$params['attribute_element_text_color'].';}';
		}
		if (!empty($params['attribute_element_border_color'])) {
			$custom_css .= $customize.' form.cart .thegem-combobox-wrap .thegem-combobox__trigger {border-color:'.$params['attribute_element_border_color'].';}';
			$custom_css .= $customize.' form.cart .thegem-combobox-wrap .thegem-combobox__options {border-color:'.$params['attribute_element_border_color'].';}';
			$custom_css .= $customize.' form.cart .gem-attribute-selector .gem-attribute-options li:not(.selected) {border-color:'.$params['attribute_element_border_color'].';}';
		}
		if (!empty($params['attribute_element_background_color'])) {
			$custom_css .= $customize.' form.cart .thegem-combobox-wrap .thegem-combobox__trigger {background-color:'.$params['attribute_element_background_color'].';}';
			$custom_css .= $customize.' form.cart .thegem-combobox-wrap .thegem-combobox__options-item {background-color:'.$params['attribute_element_background_color'].';}';
			$custom_css .= $customize.' form.cart .gem-attribute-selector.type-label .gem-attribute-options li:not(.selected) {background-color:'.$params['attribute_element_background_color'].';}';
		}
		if (!empty($params['attribute_element_text_color_active'])) {
			$custom_css .= $customize.' form.cart .thegem-combobox-wrap .thegem-combobox__options-item:hover {color:'.$params['attribute_element_text_color_active'].';}';
			$custom_css .= $customize.' form.cart .gem-attribute-selector.type-label .gem-attribute-options li.selected .label {color:'.$params['attribute_element_text_color_active'].';}';
		}
		if (!empty($params['attribute_element_border_color_active'])) {
			$custom_css .= $customize.' form.cart .gem-attribute-selector .gem-attribute-options li.selected {border-color:'.$params['attribute_element_border_color_active'].';}';
		}
		if (!empty($params['attribute_element_background_color_active'])) {
			$custom_css .= $customize.' form.cart .thegem-combobox-wrap .thegem-combobox__options-item:hover {background-color:'.$params['attribute_element_background_color_active'].';}';
			$custom_css .= $customize.' form.cart .gem-attribute-selector.type-label .gem-attribute-options li.selected {background-color:'.$params['attribute_element_background_color_active'].';}';
		}

		//  Price styled
		if (!empty($params['price_color'])) {
			$custom_css .= $customize.' form.cart .woocommerce-variation-price .price {color: '.$params['price_color'].';}';
		}
		if (!empty($params['price_color_old'])) {
			$custom_css .= $customize.' form.cart .woocommerce-variation-price .price del {color: '.$params['price_color_old'].';}';
			$custom_css .= $customize.' form.cart .woocommerce-variation-price .price del:before {background-color: '.$params['price_color_old'].';}';
		}
		if (!empty($params['price_color_suffix'])) {
			$custom_css .= $customize.' form.cart .woocommerce-variation-price .woocommerce-Price-currencySymbol {color: '.$params['price_color_suffix'].';}';
		}
        if (empty($params['in_stock_amount'])) {
			$custom_css .= $customize.' form.cart .woocommerce-variation-availability {display: none;}';
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

    public function set_general_params () {
	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('Layout', 'thegem'),
		    'param_name' => 'layout_delim_head',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
		    'group' => __('General', 'thegem')
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('General layout', 'thegem'),
		    'param_name' => 'general_layout',
		    'value' => array_merge(array(
				    __('Vertical', 'thegem') => 'column',
				    __('Inline', 'thegem') => 'row',
			    )
		    ),
		    'std' => 'column',
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => 'General',
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('"Add to cart" section layout', 'thegem'),
		    'param_name' => 'add_to_cart_section_layout',
		    'value' => array_merge(array(
				    __('Vertical', 'thegem') => 'column',
				    __('Inline', 'thegem') => 'row',
			    )
		    ),
		    'std' => 'row',
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => 'General',
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('"Attributes" section layout', 'thegem'),
		    'param_name' => 'attributes_section_layout',
		    'value' => array_merge(array(
				    __('Vertical', 'thegem') => 'column',
				    __('Inline', 'thegem') => 'row',
			    )
		    ),
		    'std' => 'column',
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => 'General',
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Horizontal Alignment', 'thegem'),
		    'param_name' => 'horizontal_alignment',
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

    public function set_add_to_cart_btn_params() {
	    $resolutions = array('desktop', 'tablet', 'mobile');
	    $group = __('General', 'thegem');

	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('"Add to cart" button', 'thegem'),
		    'param_name' => 'layout_delim_head',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'checkbox',
		    'heading' => __('Button', 'thegem'),
		    'param_name' => 'add_to_cart_btn',
		    'value' => array(__('Show', 'thegem') => '1'),
		    'std' => '1',
		    'dependency' => array(
			    'callback' => 'thegem_te_product_add_to_cart_callback'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group,
	    );
	    $result[] = array(
		    'type' => 'checkbox',
		    'heading' => __('AJAX Add to Cart', 'thegem'),
		    'param_name' => 'add_to_cart_btn_ajax',
		    'value' => array(__('Enable', 'thegem') => '1'),
		    'std' => '1',
		    'dependency' => array(
			    'element' => 'add_to_cart_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group,
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Button width', 'thegem'),
		    'param_name' => 'add_to_cart_btn_width',
		    'value' => array_merge(array(
				    __('Default', 'thegem') => 'default',
				    __('Fullwidth', 'thegem') => 'fullwidth',
				    __('Custom width', 'thegem') => 'custom',
			    )
		    ),
		    'std' => 'default',
		    'dependency' => array(
			    'element' => 'add_to_cart_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => $group,
	    );

	    foreach ($resolutions as $res) {
		    $result[] = array(
			    'type' => 'textfield',
			    'heading' => __(ucfirst($res).' (px)', 'thegem'),
			    'param_name' => 'add_to_cart_btn_width_custom_'.$res,
			    'value' => '',
			    'dependency' => array(
				    'element' => 'add_to_cart_btn_width',
				    'value' => 'custom',
			    ),
			    'edit_field_class' => 'vc_column vc_col-sm-4',
			    'group' => $group,
		    );
	    }

	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Border Width', 'thegem'),
		    'param_name' => 'add_to_cart_btn_border_width',
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
		    'dependency' => array(
			    'element' => 'add_to_cart_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group,
	    );
	    $result[] = array(
		    'type' => 'textfield',
		    'heading' => __('Border Radius', 'thegem'),
		    'param_name' => 'add_to_cart_btn_border_radius',
		    'value' => '',
		    'dependency' => array(
			    'element' => 'add_to_cart_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Text Color', 'thegem'),
		    'param_name' => 'add_to_cart_btn_text_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'add_to_cart_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Text Color on Hover', 'thegem'),
		    'param_name' => 'add_to_cart_btn_text_color_hover',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'add_to_cart_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Background Color', 'thegem'),
		    'param_name' => 'add_to_cart_btn_background_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'add_to_cart_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Background Color on Hover', 'thegem'),
		    'param_name' => 'add_to_cart_btn_background_color_hover',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'add_to_cart_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Border Color', 'thegem'),
		    'param_name' => 'add_to_cart_btn_border_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'add_to_cart_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Border Color on Hover', 'thegem'),
		    'param_name' => 'add_to_cart_btn_border_color_hover',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'add_to_cart_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );


        /*
	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'param_name' => 'layout_delim_head',
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'description' => __('To style "Add to cart" button go to <a href="'.get_site_url().'/wp-admin/admin.php?page=thegem-theme-options#/woocommerce/product-styles" target="_blank">Theme Options → WooCommerce → Elements & Styles → Buttons</a>.', 'thegem'),
		    'group' => 'General',
	    );
        */

	    return $result;
    }

    public function set_wishlist_params() {
	    $group = __('General', 'thegem');

	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('"Add to wishlist" button', 'thegem'),
		    'param_name' => 'layout_delim_head_wishlist',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
		    'group' => $group
	    );

	    $result[] = array(
		    'type' => 'checkbox',
		    'heading' => __('Button', 'thegem'),
		    'param_name' => 'add_to_wishlist_btn',
		    'value' => array(__('Show', 'thegem') => '1'),
		    'std' => '1',
		    'dependency' => array(
			    'element' => 'add_to_cart_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'description' => __('This works only if YITH WooCommerce Wishlist plugin is active', 'thegem'),
		    'group' => $group
	    );

	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Normal Color', 'thegem'),
		    'param_name' => 'add_to_wishlist_btn_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'add_to_wishlist_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Hover Color', 'thegem'),
		    'param_name' => 'add_to_wishlist_btn_color_hover',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'add_to_wishlist_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Filled Color', 'thegem'),
		    'param_name' => 'add_to_wishlist_btn_color_filled',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'add_to_wishlist_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
	    );

	    return $result;
    }

    public function set_amount_params() {
	    $resolutions = array('desktop', 'tablet', 'mobile');
	    $result = array();
	    $group = __('General', 'thegem');

	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('"Amount" control', 'thegem'),
		    'param_name' => 'layout_delim_head_amount',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'checkbox',
		    'heading' => __('"Amount" control', 'thegem'),
		    'param_name' => 'amount_control',
		    'value' => array(__('Show', 'thegem') => '1'),
		    'std' => '1',
		    'dependency' => array(
			    'element' => 'add_to_cart_btn',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('"Amount" control width', 'thegem'),
		    'param_name' => 'amount_control_width',
		    'value' => array_merge(array(
				    __('Default', 'thegem') => 'default',
				    __('Fullwidth', 'thegem') => 'fullwidth',
				    __('Custom width', 'thegem') => 'custom',
			    )
		    ),
		    'std' => 'default',
		    'dependency' => array(
			    'element' => 'amount_control',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => $group
	    );

	    foreach ($resolutions as $res) {
		    $result[] = array(
			    'type' => 'textfield',
			    'heading' => __(ucfirst($res).' (px)', 'thegem'),
			    'param_name' => 'amount_control_width_custom_'.$res,
			    'value' => '',
			    'dependency' => array(
				    'element' => 'amount_control_width',
				    'value' => 'custom',
			    ),
			    'edit_field_class' => 'vc_column vc_col-sm-4',
			    'group' => $group
		    );
	    }

	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('"Amount" control text color', 'thegem'),
		    'param_name' => 'amount_control_text_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'amount_control',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );

	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('"Amount" control background color', 'thegem'),
		    'param_name' => 'amount_control_background_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'amount_control',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );

	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('"Amount" control border color', 'thegem'),
		    'param_name' => 'amount_control_border_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'amount_control',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );

	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('"Amount" control separator color', 'thegem'),
		    'param_name' => 'amount_control_separator_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'amount_control',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );

	    return $result;
    }

    public function set_attributes_params() {
	    $result = array();
	    $group = __('General', 'thegem');

	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('"Attributes" section', 'thegem'),
		    'param_name' => 'layout_delim_head',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'checkbox',
		    'heading' => __('Attributes', 'thegem'),
		    'param_name' => 'attributes',
		    'value' => array(__('Show', 'thegem') => '1'),
		    'std' => '1',
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Attribute label', 'thegem'),
		    'param_name' => 'attribute_label',
		    'value' => array_merge(array(
				    __('Side', 'thegem') => 'side',
				    __('Top', 'thegem') => 'top',
				    __('Hide', 'thegem') => 'hide',
			    )
		    ),
		    'std' => 'side',
		    'dependency' => array(
			    'element' => 'attributes',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'textfield',
		    'heading' => __('Labels column width:', 'thegem'),
		    'param_name' => 'attribute_label_width',
		    'value' => '',
		    'dependency' => array(
			    'element' => 'attributes',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'description' => __('Set custom width for the labels column to align attribute values.', 'thegem'),
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Label Text Color', 'thegem'),
		    'param_name' => 'attribute_label_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'attributes',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Element Text Color', 'thegem'),
		    'param_name' => 'attribute_element_text_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'attributes',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Element Border Color', 'thegem'),
		    'param_name' => 'attribute_element_border_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'attributes',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Element Background Color', 'thegem'),
		    'param_name' => 'attribute_element_background_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'attributes',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Element Text Color Active', 'thegem'),
		    'param_name' => 'attribute_element_text_color_active',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'attributes',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Element Border Color Active', 'thegem'),
		    'param_name' => 'attribute_element_border_color_active',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'attributes',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Element Background Color Active', 'thegem'),
		    'param_name' => 'attribute_element_background_color_active',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'attributes',
			    'value' => '1',
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
	    );

	    return $result;
    }

    public function set_price_params() {
	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('"Variation" price', 'thegem'),
		    'param_name' => 'layout_delim_head',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
		    'group' => __('General', 'thegem')
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Price color', 'thegem'),
		    'param_name' => 'price_color',
		    'std' => '',
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => 'General'
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Old price color', 'thegem'),
		    'param_name' => 'price_color_old',
		    'std' => '',
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => 'General'
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Suffix color', 'thegem'),
		    'param_name' => 'price_color_suffix',
		    'std' => '',
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => 'General'
	    );
	    $result[] = array(
		    'type' => 'checkbox',
		    'heading' => __('In Stock Amount', 'thegem'),
		    'param_name' => 'in_stock_amount',
		    'value' => array(__('Show', 'thegem') => '1'),
		    'std' => '1',
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => 'General',
	    );

	    return $result;
    }

	public function shortcode_settings() {

		return array(
			'name' => __('Product Add To Cart', 'thegem'),
			'base' => 'thegem_te_product_add_to_cart',
			'icon' => 'thegem-icon-wpb-ui-element-product-add-to-cart',
			'category' => __('Single Product Builder', 'thegem'),
			'description' => __('Product Add To Cart (Product Builder)', 'thegem'),
			'params' => array_merge(

			    /* General - Layout */
				$this->set_general_params(),

				/* General - "Add to cart" button */
				$this->set_add_to_cart_btn_params(),

                /* General - "Add to wishlist" button */
				$this->set_wishlist_params(),

                /* General - "Amount" control */
				$this->set_amount_params(),

				/* General - "Attributes" section */
				$this->set_attributes_params(),

                /* General - "Variation" price */
				$this->set_price_params(),

				/* Extra Options */
				thegem_set_elements_extra_options(),

				/* Flex Options */
				thegem_set_elements_design_options('single-product')
			),
		);
	}
}

$templates_elements['thegem_te_product_add_to_cart'] = new TheGem_Template_Element_Product_Add_To_Cart();
$templates_elements['thegem_te_product_add_to_cart']->init();
