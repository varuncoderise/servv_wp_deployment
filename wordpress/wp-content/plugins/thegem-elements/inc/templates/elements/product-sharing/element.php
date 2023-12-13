<?php

class TheGem_Template_Element_Product_Sharing extends TheGem_Product_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_product_sharing';
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'alignment' => 'left',
			'title' => 'Share',
			'text_style' => 'title-default',
			'text_font_weight' => '',
			'text_font_style' => '',
			'text_letter_spacing' => '',
			'text_transform' => '',
			'title_color' => '',
			'facebook' => '1',
			'twitter' => '1',
			'pinterest' => '1',
			'tumblr' => '1',
			'linkedin' => '1',
			'reddit' => '1',
			'whatsapp' => '',
			'icons_color' => '',
			'icons_color_hover' => '',
			'icons_size' => 'tiny',
			'icons_size_custom' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_design_options_extract('single-product')
        ), $atts, 'thegem_te_product_sharing');

		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-product-sharing', $params);

		// Init Sharing
		ob_start();
		$product = thegem_templates_init_product();

		if (empty($product)) {
			ob_end_clean();
			return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), '');
		}

		$titleClass = thegem_te_product_text_styled($params);


		$post_image = '';
		$attachment_id = get_post_thumbnail_id(get_the_ID());
		if ($attachment_id) {
			$post_image = thegem_generate_thumbnail_src($attachment_id, 'thegem-blog-timeline-large');
			if ($post_image && $post_image[0]) {
				$post_image = $post_image[0];
			}
		}

		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-product-sharing <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
			<?= thegem_data_editor_attribute($uniqid . '-editor') ?>>

            <div class="product-sharing socials-sharing socials socials-colored-hover">
				<?php if ($params['title']): ?>
                    <div class="product-sharing__title">
                        <span <?php if (!empty($titleClass)): ?>class="<?= $titleClass ?>"<?php endif; ?>>
                            <?=esc_html_e( $params['title'])?>:
                        </span>
                    </div>
				<?php endif; ?>
                <div class="product-sharing__list size--<?= $params['icons_size'] ?>">
                    <?php if ($params['facebook']): ?>
                        <a class="socials-item" target="_blank" href="<?= esc_url('https://www.facebook.com/sharer/sharer.php?u='.urlencode(get_permalink())); ?>" title="Facebook"><i class="socials-item-icon facebook"></i></a>
                    <?php endif; ?>
	                <?php if ($params['twitter']): ?>
                        <a class="socials-item" target="_blank" href="<?= esc_url('https://twitter.com/intent/tweet?text='.urlencode(get_the_title()).'&amp;url='.urlencode(get_permalink())); ?>" title="Twitter"><i class="socials-item-icon twitter"></i></a>
	                <?php endif; ?>
	                <?php if ($params['pinterest']): ?>
                        <a class="socials-item" target="_blank" href="<?= esc_url('https://pinterest.com/pin/create/button/?url='.urlencode(get_permalink()).'&amp;description='.urlencode(get_the_title())); ?>" title="Pinterest"><i class="socials-item-icon pinterest"></i></a>
	                <?php endif; ?>
	                <?php if ($params['tumblr']): ?>
                        <a class="socials-item" target="_blank" href="<?= esc_url('https://tumblr.com/widgets/share/tool?canonicalUrl='.urlencode(get_permalink())); ?>" title="Tumblr"><i class="socials-item-icon tumblr"></i></a>
	                <?php endif; ?>
	                <?php if ($params['linkedin']): ?>
                        <a class="socials-item" target="_blank" href="<?= esc_url('https://www.linkedin.com/shareArticle?mini=true&url='.urlencode(get_permalink()).'&amp;title='.urlencode(get_the_title())); ?>&amp;summary=<?php echo urlencode(get_the_excerpt()); ?>" title="LinkedIn"><i class="socials-item-icon linkedin"></i></a>
	                <?php endif; ?>
	                <?php if ($params['reddit']): ?>
                        <a class="socials-item" target="_blank" href="<?= esc_url('https://www.reddit.com/submit?url='.urlencode(get_permalink()).'&amp;title='.urlencode(get_the_title())); ?>" title="Reddit"><i class="socials-item-icon reddit"></i></a>
	                <?php endif; ?>
	                <?php if ($params['whatsapp']): ?>
		                <a class="socials-item" target="_blank" href="<?= esc_url('https://wa.me/?text='.urlencode(get_permalink())); ?>" title="WhatsApp"><i class="socials-item-icon whatsapp"></i></a>
	                <?php endif; ?>
                </div>
            </div>
		</div>

		<?php
		//Custom Styles
		$customize = '.thegem-te-product-sharing.'.$uniqid;

		// Layout Styles
		if (!empty($params['alignment'])) {
			$custom_css .= $customize.' .product-sharing {justify-content: ' . $params['alignment'] . '; text-align: ' . $params['alignment'] . ';}';
		}
		if ($params['text_letter_spacing'] != '') {
			$custom_css .= $customize.' .product-sharing .product-sharing__title span {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
		}
		if (!empty($params['title_color'])) {
			$custom_css .= $customize.' .product-sharing .product-sharing__title span {color: ' . $params['title_color'] . ';}';
		}

		// Icons Styles
		if (!empty($params['icons_color'])) {
			$custom_css .= $customize.' .product-sharing .product-sharing__list a > i {color: ' . $params['icons_color'] . ';}';
		}
		if (!empty($params['icons_color_hover'])) {
			$custom_css .= $customize.' .product-sharing .product-sharing__list a:hover > i {color: ' . $params['icons_color_hover'] . ';}';
		}
		if ($params['icons_size'] == 'custom' && !empty($params['icons_size_custom'])) {
			$custom_css .= $customize.' .product-sharing .product-sharing__list a > i {font-size: ' . $params['icons_size_custom'] . 'px;}';
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
			'group' => $group
		);
		$result[] = array(
			"type" => "textfield",
			'heading' => __('Share Icons Title', 'thegem'),
			'param_name' => 'title',
			'std' => 'Share',
			"edit_field_class" => "vc_column vc_col-sm-12",
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
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Title Color', 'thegem'),
			'param_name' => 'title_color',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		return $result;
	}

    public function set_socials_params() {
	    $result = array();
	    $group = __('General', 'thegem');

	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('Icons', 'thegem'),
		    'param_name' => 'layout_delim_head',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'checkbox',
		    'heading' => __('Facebook', 'thegem'),
		    'param_name' => 'facebook',
		    'value' => array(__('Yes', 'thegem') => '1'),
		    'std' => '1',
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
	    );
	    $result[] = array(
            'type' => 'checkbox',
            'heading' => __('Twitter (X)', 'thegem'),
            'param_name' => 'twitter',
            'value' => array(__('Yes', 'thegem') => '1'),
            'std' => '1',
            'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
        );
	    $result[] = array(
            'type' => 'checkbox',
            'heading' => __('Pinterest', 'thegem'),
            'param_name' => 'pinterest',
            'value' => array(__('Yes', 'thegem') => '1'),
            'std' => '1',
            'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
        );
	    $result[] = array(
            'type' => 'checkbox',
            'heading' => __('Tumblr', 'thegem'),
            'param_name' => 'tumblr',
            'value' => array(__('Yes', 'thegem') => '1'),
            'std' => '1',
            'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
        );
	    $result[] = array(
            'type' => 'checkbox',
            'heading' => __('LinkedIn', 'thegem'),
            'param_name' => 'linkedin',
            'value' => array(__('Yes', 'thegem') => '1'),
            'std' => '1',
            'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
        );
	    $result[] = array(
            'type' => 'checkbox',
            'heading' => __('Reddit', 'thegem'),
            'param_name' => 'reddit',
            'value' => array(__('Yes', 'thegem') => '1'),
            'std' => '1',
            'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
        );
	    $result[] = array(
            'type' => 'checkbox',
            'heading' => __('Whatsapp', 'thegem'),
            'param_name' => 'whatsapp',
            'value' => array(__('Yes', 'thegem') => '1'),
            'std' => '',
            'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
        );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Icons Color', 'thegem'),
		    'param_name' => 'icons_color',
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Icons Color Hover', 'thegem'),
		    'param_name' => 'icons_color_hover',
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Icons Size', 'thegem'),
		    'param_name' => 'icons_size',
		    'value' => array(
			    __('Tiny', 'thegem') => 'tiny',
			    __('Small', 'thegem') => 'small',
			    __('Medium', 'thegem') => 'medium',
			    __('Large', 'thegem') => 'large',
			    __('Extra Large', 'thegem') => 'xlarge',
			    __('Custom', 'thegem') => 'custom'
		    ),
		    'std' => 'tiny',
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'textfield',
		    'heading' => __('Icons Custom Size', 'thegem'),
		    'param_name' => 'icons_size_custom',
		    'dependency' => array(
			    'element' => 'icons_size',
			    'value' => 'custom'
		    ),
		    'std' => '16',
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );

        return $result;
    }

	public function shortcode_settings() {

		return array(
			'name' => __('Product Sharing', 'thegem'),
			'base' => 'thegem_te_product_sharing',
			'icon' => 'thegem-icon-wpb-ui-element-product-sharing',
			'category' => __('Single Product Builder', 'thegem'),
			'description' => __('Product Sharing (Product Builder)', 'thegem'),
			'params' => array_merge(

			    /* General - Layout */
				$this->set_layout_params(),

				/* General - Socials */
				$this->set_socials_params(),

				/* Extra Options */
				thegem_set_elements_extra_options(),

				/* Flex Options */
				thegem_set_elements_design_options('single-product')
			),
		);
	}
}

$templates_elements['thegem_te_product_sharing'] = new TheGem_Template_Element_Product_Sharing();
$templates_elements['thegem_te_product_sharing']->init();
