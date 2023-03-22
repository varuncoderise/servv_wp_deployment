<?php

class TheGem_Template_Element_Product_Reviews extends TheGem_Product_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_product_reviews';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'columns' => '2x',
			'inner_title' => '1',
			'inner_title_add' => '1',
			'text_style' => 'title-default',
			'text_font_weight' => 'light',
			'text_font_style' => '',
			'text_letter_spacing' => '',
			'text_transform' => '',
			'stars_rated_color' => '',
			'stars_base_color' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_design_options_extract('single-product')
        ), $atts, 'thegem_te_product_reviews');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-product-reviews', $params);
		
		// Init Reviews
		ob_start();
		$product = thegem_templates_init_product();
		if(empty($product)) { ob_end_clean(); return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), ''); }
		
		?>
  
		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-product-reviews <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
			<?= thegem_data_editor_attribute($uniqid . '-editor') ?>>
            
            <div class="product-reviews reviews-column-<?= $params['columns'] ?>">
	            <?= comments_template() ?>
            </div>
		</div>

        <script>
            (function ($) {
                const $wrap = $('.thegem-te-product-reviews .woocommerce-Reviews');
                const $titles = $('.woocommerce-Reviews-title, .woocommerce-Reviews-title span, .comment-reply-title, .comment-reply-title span', $wrap);
                const titleStyledClasses =
                    `<?= $params['text_style'] ?>
                     <?= $params['text_font_weight'] ?>
                     <?= $params['text_font_style'] ?>
                     <?= $params['text_transform'] ?>`;

                $titles.addClass(titleStyledClasses);
				
				<?php if ($params['text_font_weight'] == 'bold'): ?>
                $titles.removeClass('light');
				<?php endif; ?>
            })(jQuery);
        </script>

		<?php
		//Custom Styles
		$customize = '.thegem-te-product-reviews.'.$uniqid;
		
		// General Styles
		if (empty($params['inner_title'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .woocommerce-Reviews-title {display: none;}';
		}
		if (empty($params['inner_title_add'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .comment-reply-title {display: none;}';
		}
  
		// Text Styles
		if ($params['text_letter_spacing'] != '') {
			$custom_css .= $customize.' .woocommerce-Reviews .woocommerce-Reviews-title {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
			$custom_css .= $customize.' .woocommerce-Reviews .woocommerce-Reviews-title span {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
			$custom_css .= $customize.' .woocommerce-Reviews .comment-reply-title {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
			$custom_css .= $customize.' .woocommerce-Reviews .comment-reply-title span {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
		}
  
		// Reviews Stars Styled
		if (!empty($params['stars_base_color'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .star-rating:before {color: ' . $params['stars_base_color'] . ';}';
		}
		if (!empty($params['stars_rated_color'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .star-rating > span:before {color: ' . $params['stars_rated_color'] . ';}';
		}
		if (!empty($params['stars_base_color'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .comment-form-rating .stars a:before {color: ' . $params['stars_base_color'] . ';}';
		}
		if (!empty($params['stars_rated_color'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .comment-form-rating .stars a.rating-on:before {color: ' . $params['stars_rated_color'] . ';}';
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
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('General', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Columns', 'thegem'),
			'param_name' => 'columns',
			'value' => array_merge(array(
				__('One Column', 'thegem') => '1x',
				__('Two Columns', 'thegem') => '2x',
			)),
			'std' => '2x',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('"Reviews" Title', 'thegem'),
			'param_name' => 'inner_title',
			'value' => array(__('Show', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
            'type' => 'checkbox',
            'heading' => __('"Add a review" Title', 'thegem'),
            'param_name' => 'inner_title_add',
            'value' => array(__('Show', 'thegem') => '1'),
            'std' => '1',
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => 'General',
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
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Font weight', 'thegem'),
			'param_name' => 'text_font_weight',
			'value' => array(
				__('Default', 'thegem') => 'light',
				__('Bold', 'thegem') => 'bold',
			),
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
				__('None', 'thegem') => 'transform-none',
				__('Capitalize', 'thegem') => 'capitalize',
				__('Lowercase', 'thegem') => 'lowercase',
				__('Uppercase', 'thegem') => 'uppercase',
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		return $result;
	}
 
	public function set_stars_styled_params() {
		$result = array();
		$group = __('General', 'thegem');
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Rating Stars', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Rated Color', 'thegem'),
			'param_name' => 'stars_rated_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Base Color', 'thegem'),
			'param_name' => 'stars_base_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		
		return $result;
	}
 
	public function shortcode_settings() {

		return array(
			'name' => __('Product Reviews', 'thegem'),
			'base' => 'thegem_te_product_reviews',
			'icon' => 'thegem-icon-wpb-ui-element-product-reviews',
			'category' => __('Single Product Builder', 'thegem'),
			'description' => __('Product Reviews (Product Builder)', 'thegem'),
			'params' => array_merge(

			    /* General - Layout */
				$this->set_layout_params(),
                
                /* General - Stars */
				$this->set_stars_styled_params(),
				
				/* Extra Options */
				thegem_set_elements_extra_options(),
				
				/* Flex Options */
				thegem_set_elements_design_options('single-product')
			),
		);
	}
}

$templates_elements['thegem_te_product_reviews'] = new TheGem_Template_Element_Product_Reviews();
$templates_elements['thegem_te_product_reviews']->init();
