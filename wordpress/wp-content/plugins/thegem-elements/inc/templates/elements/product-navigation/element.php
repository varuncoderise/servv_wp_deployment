<?php

class TheGem_Template_Element_Product_Navigation extends TheGem_Product_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_product_navigation';
	}
	
	public function get_taxonomy_list() {
		$data = array();
		$taxonomies = get_taxonomies(['object_type' => ['product']], 'objects');
		
		if (!empty($taxonomies)) {
			foreach ($taxonomies as $taxonomy) {
				$data[ucwords($taxonomy->label)] = $taxonomy->name;
			}
		}
		
		return $data;
	}
	
	public function get_default_taxonomy() {
		$taxonomies = array_flip($this->get_taxonomy_list());
		if (empty($taxonomies)) return;
		
		return array_keys($taxonomies)[1];
	}
	
	public function productPreviewOutput($id) {
		$product = wc_get_product($id);
		$preview_output = '';
		
		$preview_output .= '<div class="product-navigation__preview">';
		$preview_output .= '<div class="product-navigation__preview-image">' . get_the_post_thumbnail($id, 'thegem-product-thumbnail') . '</div>';
		$preview_output .= '<div class="product-navigation__preview-info">';
		$preview_output .= '<div class="product-navigation__preview-info-title">' . mb_strimwidth(get_the_title($id), '0', '20', '...') . '</div>';
		$preview_output .= '<div class="product-navigation__preview-info-price">' . $product->get_price_html() . '</div>';
		$preview_output .= '</div></div>';
		
		return $preview_output;
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'nav_elements' => '1',
			'navigate_by' => 'chronology',
			'taxonomy' => $this->get_default_taxonomy(),
			'preview_on_hover' => '1',
			'back_to_shop' => '1',
			'back_to_shop_link' => 'main_shop',
			'back_to_shop_link_custom' => '',
			'alignment' => '',
			'elements_color' => '',
			'elements_color_hover' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_design_options_extract('single-product')
        ), $atts, 'thegem_te_product_navigation');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-product-navigation', $params);
		
		// Init Navigation
		ob_start();
		$product = thegem_templates_init_product();
		if(empty($product)) { ob_end_clean(); return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), ''); }
  
		$isNavigate = $params['nav_elements'] || $params['back_to_shop'];
		$is_taxonomy = !empty($params['navigate_by']) && $params['navigate_by'] == 'taxonomy' ? true : false;
		$taxonomy = !empty($params['taxonomy']) ? $params['taxonomy'] : '';
		$back_to_shop_url = 'javascript:void(0);';
		switch ( $params['back_to_shop_link'] ) {
			case 'main_shop':
				$back_to_shop_url = get_permalink( wc_get_page_id( 'shop' ) );
				break;
			case 'category':
				$terms = get_the_terms( $product->get_id(), 'product_cat' );
				foreach ( $terms as $term ) {
					$product_cat_id   = $term->term_id;
					$back_to_shop_url = get_term_link( $product_cat_id, 'product_cat' );
					break;
				}
				break;
			case 'custom_url':
				$back_to_shop_url = esc_url( $params['back_to_shop_link_custom'] );
				break;
		}

		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-product-navigation <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
             <?=thegem_data_editor_attribute($uniqid.'-editor')?>>
            
            <?php if ( $isNavigate ): ?>
                <div class="product-navigation">
                    <ul class="product-navigation__list">
						<?php if (($post = get_previous_post($is_taxonomy, '', $taxonomy)) && $params['nav_elements']): ?>
                            <li>
                                <a class="product-navigation__list-prev" href="<?= get_permalink( $post->ID ) ?>">
				                    <?php if ( $params['preview_on_hover'] ): ?>
                                        <?= $this->productPreviewOutput($post->ID) ?>
				                    <?php endif; ?>
                                </a>
                            </li>
						<?php endif; wp_reset_postdata(); $product = thegem_templates_init_product(); ?>
						
						<?php if ( $params['back_to_shop'] ): ?>
                            <li>
                                <a class="product-navigation__list-back" href="<?= $back_to_shop_url ?>"></a>
                            </li>
						<?php endif; ?>
						
						<?php if (( $post = get_next_post($is_taxonomy, '', $taxonomy)) && $params['nav_elements']): ?>
                            <li>
                                <a class="product-navigation__list-next" href="<?= get_permalink( $post->ID ) ?>">
	                                <?php if ( $params['preview_on_hover'] ): ?>
		                                <?= $this->productPreviewOutput($post->ID) ?>
	                                <?php endif; ?>
                                </a>
                            </li>
						<?php endif; wp_reset_postdata(); $product = thegem_templates_init_product(); ?>
                    </ul>
                </div>
			<?php endif; ?>
		</div>

		<?php
		//Custom Styles
		$customize = '.thegem-te-product-navigation.'.$uniqid;
        
        // Alignment
		if ($params['alignment'] == 'center') {
			$custom_css .= $customize.' .product-navigation {justify-content: center;}';
		}
		if ($params['alignment'] == 'right') {
			$custom_css .= $customize.' .product-navigation {justify-content: flex-end;}';
		}
        
        // Appearance
        if (!empty($params['elements_color'])) {
			$custom_css .= $customize.' .product-navigation__list li a:before {color: ' . $params['elements_color'] . ';}';
		}
		if (!empty($params['elements_color_hover'])) {
			$custom_css .= $customize.' .product-navigation__list li a:hover:before {color: ' . $params['elements_color_hover'] . ';}';
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

	public function shortcode_settings() {

		return array(
			'name' => __('Product Navigation', 'thegem'),
			'base' => 'thegem_te_product_navigation',
			'icon' => 'thegem-icon-wpb-ui-element-product-navigation',
			'category' => __('Single Product Builder', 'thegem'),
			'description' => __('Product Navigation (Product Builder)', 'thegem'),
			'params' => array_merge(

			    /* General - Layout */
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('General', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Prev/Next Product', 'thegem'),
						'param_name' => 'nav_elements',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Navigate by', 'thegem'),
						'param_name' => 'navigate_by',
						'value' => array_merge(array(
								__('Chronology', 'thegem') => 'chronology',
								__('Same Taxonomy', 'thegem') => 'taxonomy',
							)
						),
						'std' => 'chronology',
						'dependency' => array(
							'element' => 'nav_elements',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
		
                    array(
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
	                    'group' => 'General',
                    ),
                    
                    array(
						'type' => 'checkbox',
						'heading' => __('Product Preview on Hover', 'thegem'),
						'param_name' => 'preview_on_hover',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
	                    'dependency' => array(
		                    'element' => 'nav_elements',
		                    'value' => '1'
	                    ),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
                    array(
						'type' => 'checkbox',
						'heading' => __('Back to Shop', 'thegem'),
						'param_name' => 'back_to_shop',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Back to Shop Link', 'thegem'),
						'param_name' => 'back_to_shop_link',
						'value' => array_merge(array(
								__('Main Shop', 'thegem') => 'main_shop',
								__('Category', 'thegem') => 'category',
								__('Custom Url', 'thegem') => 'custom_url',
                            )
						),
						'std' => 'main_shop',
						'dependency' => array(
							'element' => 'back_to_shop',
							'value' => '1'
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
					array(
						"type" => "textfield",
						'heading' => __('Custom Url', 'thegem'),
						'param_name' => 'back_to_shop_link_custom',
						'dependency' => array(
							'element' => 'back_to_shop_link',
							'value' => array('custom_url')
						),
						"edit_field_class" => "vc_column vc_col-sm-12",
						'group' => 'General'
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Alignment', 'thegem'),
						'param_name' => 'alignment',
						'value' => array_merge(array(
                            __('Left', 'thegem') => 'left',
                            __('Center', 'thegem') => 'center',
                            __('Right', 'thegem') => 'right',
                        )),
						'std' => 'left',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
				),
				
				/* General - Appearance */
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Appearance', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Elements Color', 'thegem'),
						'param_name' => 'elements_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Elements Color Hover', 'thegem'),
						'param_name' => 'elements_color_hover',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
				),
				
				/* Extra Options */
				thegem_set_elements_extra_options(),
				
				/* Flex Options */
				thegem_set_elements_design_options('single-product')
			),
		);
	}
}

$templates_elements['thegem_te_product_navigation'] = new TheGem_Template_Element_Product_Navigation();
$templates_elements['thegem_te_product_navigation']->init();
