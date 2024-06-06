<?php

class TheGem_Template_Element_Product_Rating extends TheGem_Product_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_product_rating';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'alignment' => 'left',
			'review_link' => '1',
			'stars_rated_color' => '',
			'stars_base_color' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_design_options_extract('single-product')
        ), $atts, 'thegem_te_product_rating');
		
		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-product-rating', $params);
		
		// Init Rating
		ob_start();
		$product = thegem_templates_init_product();
		
		if (empty($product) || !wc_review_ratings_enabled() || $product->get_rating_count() < 1) {
			ob_end_clean();
			return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), '');
		}
		
		$rating_count = $product->get_rating_count();
		$review_count = $product->get_review_count();
		$average = $product->get_average_rating();
		$isReviewText = thegem_get_option('product_page_elements_reviews_text');
		$reviewText = $isReviewText ? '%s '.esc_html(thegem_get_option('product_page_elements_reviews_text')) : '%s customer review';
		$reviewTexts = $isReviewText ? '%s '.esc_html(thegem_get_option('product_page_elements_reviews_text')) : '%s customer reviews';

		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-product-rating <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
			<?= thegem_data_editor_attribute($uniqid . '-editor') ?>>
            
			<div class="product-rating">
                <?= wc_get_rating_html( $average, $rating_count ); ?>

				<?php if (comments_open() && $params['review_link']) : ?>
                    <div class="rating-link">
                        <a href="javascript:void(0)" class="scroll-to-reviews" rel="nofollow">
                            <span class="title-text-body-tiny">
                                <?php printf( _n( $reviewText, $reviewTexts, $review_count, 'woocommerce' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>
                            </span>
                        </a>
                    </div>
				<?php endif ?>
            </div>
		</div>

		<?php
		//Custom Styles
		$customize = '.thegem-te-product-rating.'.$uniqid;
		
        // General Styled
		if (!empty($params['alignment'])) {
			$custom_css .= $customize.' .product-rating {justify-content: ' . $params['alignment'] . '; text-align: ' . $params['alignment'] . ';}';
		}
		
		// Stars Styled
		if (!empty($params['stars_base_color'])) {
			$custom_css .= $customize.' .product-rating .star-rating:before {color: ' . $params['stars_base_color'] . ';}';
		}
		if (!empty($params['stars_rated_color'])) {
			$custom_css .= $customize.' .product-rating .star-rating > span:before {color: ' . $params['stars_rated_color'] . ';}';
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
			'name' => __('Product Rating', 'thegem'),
			'base' => 'thegem_te_product_rating',
			'icon' => 'thegem-icon-wpb-ui-element-product-rating',
			'category' => __('Single Product Builder', 'thegem'),
			'description' => __('Product Rating (Product Builder)', 'thegem'),
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
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Customer Reviews Link', 'thegem'),
						'param_name' => 'review_link',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'group' => 'General',
					),
				),
				
				/* General - Stars */
				array(
					array(
						'type' => 'thegem_delimeter_heading',
						'heading' => __('Rating Stars', 'thegem'),
						'param_name' => 'layout_delim_head',
						'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
						'group' => __('General', 'thegem')
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Rated Color', 'thegem'),
						'param_name' => 'stars_rated_color',
						'edit_field_class' => 'vc_column vc_col-sm-6',
						'group' => 'General'
					),
					array(
						'type' => 'colorpicker',
						'heading' => __('Base Color', 'thegem'),
						'param_name' => 'stars_base_color',
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

$templates_elements['thegem_te_product_rating'] = new TheGem_Template_Element_Product_Rating();
$templates_elements['thegem_te_product_rating']->init();
