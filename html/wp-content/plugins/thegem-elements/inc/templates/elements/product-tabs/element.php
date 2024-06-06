<?php

class TheGem_Template_Element_Product_Tabs extends TheGem_Product_Template_Element {

	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_product_tabs';
	}

    public function product_tabs_callback($params) {
		global $product, $post;

		// Get Additional Tabs Data
	    $additional_tabs = array();
	    $product_page_data = get_post_meta( $post->ID, 'thegem_product_page_data', true );
	    if (!empty($params['additional_tabs']) && !empty($product_page_data['product_page_additional_tabs'])){
		    if ($product_page_data['product_page_additional_tabs'] == 'default' && !empty(thegem_get_option('product_page_additional_tabs'))) {
			    $additional_tabs = json_decode(thegem_get_option('product_page_additional_tabs_data'));
		    } elseif($product_page_data['product_page_additional_tabs'] == 'custom'){
			    $additional_tabs = json_decode($product_page_data['product_page_additional_tabs_data']);
		    }
	    } elseif(!empty($params['additional_tabs'])) {
		    if (!empty(thegem_get_option('product_page_additional_tabs'))){
			    $additional_tabs = json_decode(thegem_get_option('product_page_additional_tabs_data'));
            }
	    }

		$tabs = array();
		$description_tab_callback = '';

		if($params['description_tab_source'] == 'page_builder') {
			$vc_show_content = false;
			if(thegem_is_plugin_active('js_composer/js_composer.php')) {
				global $vc_manager;
				if($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode()== 'page_editable') {
					$vc_show_content = true;
				}
			}
			if(get_the_content() || $vc_show_content) {
				$description_tab_callback = 'thegem_woocommerce_single_product_page_content';
			}
		} else {
			$description_tab_callback = 'woocommerce_product_description_tab';
		}

		if ( !empty($params['description']) ) {
			$tabs['description'] = array(
				'title' => esc_html__( $params['description_title'], 'woocommerce'),
				'priority' => 10,
				'callback' => $description_tab_callback
			);
		} else {
			unset( $tabs['description'] );
		}

		if ( !empty($params['additional']) ) {
			$tabs['additional_information'] = array(
				'title'	=> esc_html__( $params['additional_title'], 'woocommerce'),
				'priority' => 20,
				'callback' => 'woocommerce_product_additional_information_tab',
			);
		} elseif ( isset( $tabs['additional_information'] ) ) {
			unset( $tabs['additional_information'] );
		}

		if ( !empty($params['reviews']) )  {
			$tabs['reviews'] = array(
				'title'	=> $product->get_review_count() > 0 ? sprintf(esc_html__( $params['reviews_title'], 'woocommerce' ).' <sup>%d</sup>', $product->get_review_count()) : esc_html__( $params['reviews_title']),
				'priority' => 30,
				'callback' => 'comments_template',
			);
		} elseif ( isset( $tabs['reviews'] )) {
			unset( $tabs['reviews'] );
		}

		// Thegem Additional Tabs
		if (!empty($additional_tabs)) {
			foreach ($additional_tabs as $tab) {
				$key = str_replace('_', '-', sanitize_title($tab->title));
				$text_content = ($tab->type == 'text' && !empty($tab->text_content)) ? $tab->text_content : '';
				$section_content = ($tab->type == 'section' && !empty($tab->section_content) && function_exists('thegem_product_tabs_template_section')) ? thegem_product_tabs_template_section($tab->section_content) : '';
				$priority = !empty($tab->priority) ? intval($tab->priority) : 100;

				if ( !empty($key) )  {
					$tabs[$key] = array(
						'title' => esc_html__($tab->title, 'thegem'),
						'priority' => $priority,
						'type' => 'additional_tab',
						'text_content' => $text_content,
						'section_content' => $section_content
					);
				} elseif ( isset( $tabs[$key] )) {
					unset( $tabs[$key] );
				}
			}
		}

		// YITH WooCommerce Tab Manager
		if (thegem_is_plugin_active('yith-woocommerce-tab-manager/init.php') ||
			thegem_is_plugin_active('yith-woocommerce-tab-manager-premium/init.php')
		) {
			$ywtm_tabs = get_posts( array(
				'numberposts' => 100,
				'orderby' => 'date',
				'order' => 'ASC',
				'post_type' => 'ywtm_tab',
				'post_status'  => 'publish',
			));

			foreach ($ywtm_tabs as $tab) {
				$key = str_replace( '-', '_', sanitize_title($tab->post_title));
				$isActive = get_post_meta($tab->ID, '_ywtm_show_tab', true);

				if ( !empty($key) && $isActive )  {
					$ywtm_type = get_post_meta($tab->ID, '_ywtm_tab_type', true);
					$ywtm_product_ids = ($ywtm_type == 'product') ? get_post_meta($tab->ID, '_ywtm_tab_product', true) : '';
					$ywtm_data = get_post_meta($tab->ID, '_ywtm_text_tab', true);
					$ywtm_priority = get_post_meta($tab->ID, '_ywtm_order_tab', true);

					$tabs[$key] = array(
						'title'	=> $tab->post_title,
						'priority' => $ywtm_priority,
						'type' => 'ywtm_tab',
						'ywtm_tab' => array(
							'type' => $ywtm_type,
							'data' => $ywtm_data,
							'product_ids' => $ywtm_product_ids,
						)
					);
				} elseif ( isset( $key )) {
					unset( $tabs[$key] );
				}
			}
		}

		// Sorting tabs by priority
		uasort($tabs, function ($a, $b) {
			if ($a['priority'] == $b['priority']) {
				return 0;
			}

			return ($a['priority'] < $b['priority']) ? -1 : 1;
		});

		return $tabs;
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'layout' => 'tabs',
			'tabs_style' => 'horizontal',
			'tabs_align' => 'left',
			'accordion_height' => 'full-height',
			'stretch_background' => '',
			'tabs_titles_color' => '',
			'tabs_titles_divider_color' => '',

			'description' => '1',
			'description_title' => 'Description',
			'description_tab_source' => 'default',
			'description_back' => '#F4F6F7',
			'description_text_color' => '',
			'description_title_color' => '',
			'description_padding_desktop_top' => '60',
			'description_padding_desktop_bottom' => '60',
			'description_padding_desktop_left' => '',
			'description_padding_desktop_right' => '',
			'description_padding_tablet_top' => '',
			'description_padding_tablet_bottom' => '',
			'description_padding_tablet_left' => '',
			'description_padding_tablet_right' => '',
			'description_padding_mobile_top' => '',
			'description_padding_mobile_bottom' => '',
			'description_padding_mobile_left' => '',
			'description_padding_mobile_right' => '',

			'additional' => '1',
			'additional_title' => 'Additional Info',
			'additional_back' => '#FFFFFF',
			'additional_titles_color' => '',
			'additional_text_color' => '',
			'additional_padding_desktop_top' => '60',
			'additional_padding_desktop_bottom' => '60',
			'additional_padding_desktop_left' => '',
			'additional_padding_desktop_right' => '',
			'additional_padding_tablet_top' => '',
			'additional_padding_tablet_bottom' => '',
			'additional_padding_tablet_left' => '',
			'additional_padding_tablet_right' => '',
			'additional_padding_mobile_top' => '',
			'additional_padding_mobile_bottom' => '',
			'additional_padding_mobile_left' => '',
			'additional_padding_mobile_right' => '',

			'reviews' => '1',
			'reviews_title' => 'Reviews',
			'reviews_columns' => '2x',
			'reviews_back' => '#F4F6F7',
			'reviews_inner_title' => '1',
			'reviews_inner_title_add' => '1',
			'reviews_inner_title_style' => 'title-default',
			'reviews_inner_title_font_weight' => 'light',
			'reviews_inner_title_font_style' => '',
			'reviews_inner_title_letter_spacing' => '',
			'reviews_inner_title_text_transform' => '',
			'reviews_titles_color' => '',
			'reviews_text_color' => '',
			'reviews_btn_text_color' => '',
			'reviews_btn_text_color_hover' => '',
			'reviews_btn_background_color' => '',
			'reviews_btn_background_color_hover' => '',
			'reviews_stars_rated_color' => '',
			'reviews_stars_base_color' => '',
			'reviews_padding_desktop_top' => '60',
			'reviews_padding_desktop_bottom' => '60',
			'reviews_padding_desktop_left' => '',
			'reviews_padding_desktop_right' => '',
			'reviews_padding_tablet_top' => '',
			'reviews_padding_tablet_bottom' => '',
			'reviews_padding_tablet_left' => '',
			'reviews_padding_tablet_right' => '',
			'reviews_padding_mobile_top' => '',
			'reviews_padding_mobile_bottom' => '',
			'reviews_padding_mobile_left' => '',
			'reviews_padding_mobile_right' => '',

			'additional_tabs' => '1',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_design_options_extract('single-product')
        ), $atts, 'thegem_te_product_tabs');

		// Init Design Options Params
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-product-tabs', $params);

		// Init Tabs
		ob_start();
		$product = thegem_templates_init_product();
		if(empty($product)) { ob_end_clean(); return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), ''); }

		$tabs = $this->product_tabs_callback($params);

		// YITH WooCommerce Tab Manager pro (check if choose product)
		if (thegem_is_plugin_active('yith-woocommerce-tab-manager-premium/init.php')) {
			foreach ($tabs as $key => $tab) {
				if (isset($tab['type']) && $tab['type'] == 'ywtm_tab') {
					if (isset($tab['ywtm_tab']['type']) && $tab['ywtm_tab']['type'] == 'product' && !empty($tab['ywtm_tab']['product_ids'])){
						if (!in_array($product->get_id(), $tab['ywtm_tab']['product_ids'])) {
							unset( $tabs[$key] );
						}
					}
				}
			}
		}

		$tabs_count = count($tabs);

		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?>
             class="thegem-te-product-tabs <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>"
			<?= thegem_data_editor_attribute($uniqid . '-editor') ?>>

            <?php if ($params['layout'] == 'tabs'):
	            $tabs_class = 'product-tabs--'.$params['tabs_style'];
	            $tabs_class_position = 'product-tabs__nav--'.$params['tabs_align']
            ?>
                <div class="product-tabs <?=$tabs_class?>" data-type="<?= $params['tabs_style'] ?>">
                    <?php if ($tabs_count > 1) : ?>
                        <div class="product-tabs__nav <?= $tabs_class_position ?>">
                            <div class="product-tabs__nav-list">
                                <?php $is_first = true; foreach ( $tabs as $key => $tab ): ?>
                                    <div class="product-tabs__nav-item <?php if($is_first): ?>product-tabs__nav-item--active<?php $is_first = false; endif; ?>" data-id="<?= esc_attr( $key ); ?>">
                                        <span><?= wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="product-tabs__nav-line">
                                <div class="product-tabs__nav-slide" style="opacity: 0;"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="product-tabs__body" <?php if ($tabs_count == 1) : ?>style="max-width: 100%;"<?php endif; ?>>
                        <?php $is_first = true; foreach ( $tabs as $key => $tab ):
                            $short_class = (isset($tab['type']) && ($tab['type'] == 'ywtm_tab' || $tab['type'] == 'additional_tab')) ? 'description' : preg_replace("/\_.+/", "", $key);
                            $reviews_columns = ($short_class == 'reviews') ? 'reviews-column-'.$params['reviews_columns'] : null;
                        ?>
                            <div class="product-accordion__item">
                                <div class="product-accordion__item-title <?= esc_attr( $key ); ?> <?php if($is_first): ?>product-accordion__item--active<?php endif;?>" data-id="<?= esc_attr( $key ); ?>">
                                    <span><?= wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ); ?></span>
                                </div>
                                <div class="product-accordion__item-body <?= $short_class ?> <?= $reviews_columns ?>"
                                    <?php if($is_first): ?>style="display: block;"<?php endif; $is_first = false;?> data-id="<?= esc_attr( $key ); ?>">
                                    <?php
                                        if ( !empty( $tab['callback'] ) ) {
                                            $product = thegem_templates_init_product();
                                            call_user_func( $tab['callback'], $key, $tab );
                                        }

                                        if(isset($tab['type']) && $tab['type'] == 'additional_tab') {
                                            if (!empty($tab['text_content'])) {
                                                echo $tab['text_content'];
                                            }

                                            if (!empty($tab['section_content'])) {
                                                echo $tab['section_content'];
                                            }
                                        }

                                        if(isset($tab['type']) && $tab['type'] == 'ywtm_tab') {
                                            echo $tab['ywtm_tab']['data'];
                                        }
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

			<?php if ($params['layout'] == 'accordion'):
				$accordion_class = 'product-accordion--'.$params['accordion_height'];
            ?>
                <div class="product-accordion <?=$accordion_class?>">
					<?php $is_first = true; foreach ( $tabs as $key => $tab ):
						$short_class = (isset($tab['type']) && ($tab['type'] == 'ywtm_tab' || $tab['type'] == 'additional_tab')) ? 'description' : preg_replace("/\_.+/", "", $key);
						$reviews_columns = ($short_class == 'reviews') ? 'reviews-column-'.$params['reviews_columns'] : null;
                    ?>
                        <div class="product-accordion__item">
                            <div class="product-accordion__item-title <?= esc_attr( $key ); ?> <?php if($is_first):?>product-accordion__item--active<?php endif; ?>" data-id="<?= esc_attr( $key ); ?>">
                                <span><?= wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ); ?></span>
                            </div>
                            <div class="product-accordion__item-body <?= $short_class ?> <?= $reviews_columns ?>"
                                <?php if($is_first): ?>style="display: block;"<?php endif; $is_first = false;?> data-id="<?= esc_attr( $key ); ?>">
	                            <?php
                                    if ( !empty( $tab['callback'] ) ) {
                                        $product = thegem_templates_init_product();
                                        call_user_func( $tab['callback'], $key, $tab );
                                    }

                                    if(isset($tab['type']) && $tab['type'] == 'additional_tab') {
                                        if (!empty($tab['text_content'])) {
                                            echo $tab['text_content'];
                                        }

                                        if (!empty($tab['section_content'])) {
                                            echo $tab['section_content'];
                                        }
                                    }

                                    if(isset($tab['type']) && $tab['type'] == 'ywtm_tab') {
                                        echo $tab['ywtm_tab']['data'];
                                    }
                                ?>
                            </div>
                        </div>
					<?php endforeach; ?>
                </div>
			<?php endif; ?>

			<?php if ($params['layout'] == 'one_by_one'):
				$one_by_one_back['description'] = $params['description_back'];
				$one_by_one_back['additional_information'] = $params['additional_back'];
				$one_by_one_back['reviews'] = $params['reviews_back'];
				$isColorBack = !empty($one_by_one_back['description']) || !empty($one_by_one_back['additional_information']) || !empty($one_by_one_back['reviews']);
            ?>
                <div class="product-one-by-one <?php if ($params['stretch_background']): ?>stretch<?php endif;?>">
					<?php foreach ( $tabs as $key => $tab ):
						$short_class = (isset($tab['type']) && ($tab['type'] == 'ywtm_tab' || $tab['type'] == 'additional_tab')) ? 'description' : preg_replace("/\_.+/", "", $key);
						$reviews_columns = ($short_class == 'reviews') ? 'reviews-column-'.$params['reviews_columns'] : null;
                    ?>
                        <div class="product-one-by-one__item <?php if (!$isColorBack): ?>separator<?php endif;?>" <?php if (!empty($one_by_one_back[$key])): ?>style="background-color: <?=esc_attr($one_by_one_back[$key])?>;"<?php endif;?>>
                            <div class="product-one-by-one__container <?= $short_class ?> <?= $reviews_columns ?>">
								<?php if ($key != 'reviews'): ?>
                                    <div class="product-one-by-one__item-title">
                                        <h4 class="light"><?= wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ); ?></h4>
                                    </div>
								<?php endif; ?>
                                <div class="product-one-by-one__item-body">
									<?php
                                        if ( !empty( $tab['callback'] ) ) {
                                            $product = thegem_templates_init_product();
                                            call_user_func( $tab['callback'], $key, $tab );
                                        }

                                        if(isset($tab['type']) && $tab['type'] == 'additional_tab') {
                                            if (!empty($tab['text_content'])) {
                                                echo $tab['text_content'];
                                            }

                                            if (!empty($tab['section_content'])) {
                                                echo $tab['section_content'];
                                            }
                                        }

                                        if(isset($tab['type']) && $tab['type'] == 'ywtm_tab') {
                                            echo $tab['ywtm_tab']['data'];
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
					<?php endforeach; ?>
                </div>
			<?php endif; ?>
		</div>

        <script>
            (function ($) {
                const $wrap = $('.thegem-te-product-tabs .woocommerce-Reviews');
                const $titles = $('.woocommerce-Reviews-title, .woocommerce-Reviews-title span, .comment-reply-title, .comment-reply-title span', $wrap);
                const titleStyledClasses =
                    `<?= $params['reviews_inner_title_style'] ?>
                     <?= $params['reviews_inner_title_font_weight'] ?>
                     <?= $params['reviews_inner_title_font_style'] ?>
                     <?= $params['reviews_inner_title_text_transform'] ?>`;

                $titles.addClass(titleStyledClasses);

				<?php if ($params['reviews_inner_title_font_weight'] == 'bold'): ?>
                $titles.removeClass('light');
				<?php endif; ?>
            })(jQuery);
        </script>

		<?php
		//Custom Styles
		$customize = '.thegem-te-product-tabs.'.$uniqid;
		$tabs = array('description', 'additional', 'reviews');
		$gaps = array('padding');
		$resolution = array('desktop', 'tablet', 'mobile');
		$directions = array('top', 'bottom', 'left', 'right');

		foreach ($tabs as $tab) {
            foreach ($resolution as $res) {
	            foreach ($directions as $dir) {
		            foreach ($gaps as $gap) {
			            if (!empty($params[$tab.'_'.$gap.'_'.$res.'_'.$dir]) || strcmp($params[$tab.'_'.$gap.'_'.$res.'_'.$dir], '0') === 0) {
				            $result = str_replace(' ', '', $params[$tab.'_'.$gap.'_'.$res.'_'.$dir]);
				            $unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
				            if ($res == 'desktop') {
					            $custom_css .= $customize.' .product-accordion__item-body.'.$tab.' {'.$gap.'-'.$dir.':'.$result.$unit.';}';
					            $custom_css .= $customize.' .product-one-by-one__container.'.$tab.' {'.$gap.'-'.$dir.':'.$result.$unit.';}';
				            } else {
					            $width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
					            $custom_css .= '@media screen and ('.$width.') {'.$customize.' .product-accordion__item-body.'.$tab.' {'.$gap.'-'.$dir.':'.$result.$unit.';}}';
					            $custom_css .= '@media screen and ('.$width.') {'.$customize.' .product-one-by-one__container.'.$tab.' {'.$gap.'-'.$dir.':'.$result.$unit.';}}';
					            $custom_css .= '@media screen and ('.$width.') {'.$customize.' .product-accordion__item-body.'.$tab.' {'.$gap.'-'.$dir.':'.$result.$unit.';}}';
					            $custom_css .= '@media screen and ('.$width.') {'.$customize.' .product-one-by-one__container.'.$tab.' {'.$gap.'-'.$dir.':'.$result.$unit.';}}';
				            }
			            }
                    }
                }
            }
		}

		// Layout Styles
		if (!empty($params['tabs_titles_color'])) {
			$custom_css .= $customize.' .product-tabs__nav-item {color: '.$params['tabs_titles_color'].';}';
			$custom_css .= $customize.' .product-accordion__item-title {color: '.$params['tabs_titles_color'].';}';
			$custom_css .= $customize.' .product-one-by-one__item-title h4 {color: '.$params['tabs_titles_color'].';}';
			$custom_css .= $customize.' .product-tabs__nav-slide {background-color: '.$params['tabs_titles_color'].';}';
		}
        if (!empty($params['tabs_titles_divider_color'])) {
			$custom_css .= $customize.' .product-tabs__nav-line {background-color: '.$params['tabs_titles_divider_color'].';}';
	        $custom_css .= $customize.' .woocommerce-Reviews .comment-form input {border-color: '.$params['tabs_titles_divider_color'].';}';
	        $custom_css .= $customize.' .woocommerce-Reviews .comment-form textarea {border-color: '.$params['tabs_titles_divider_color'].';}';
	        $custom_css .= $customize.' .woocommerce-Reviews .comment-form .comment-form-rating select {border-color: '.$params['tabs_titles_divider_color'].';}';
	        $custom_css .= $customize.' .woocommerce-Reviews .comment-form .comment-form-cookies-consent .checkbox-sign {border-color: '.$params['tabs_titles_divider_color'].';}';
        }

        // Description Styles
		if (!empty($params['description_title_color'])) {
			$custom_css .= $customize.' .product-accordion__item-body.description h1,' .$customize.' .product-accordion__item-body.description .title-h1 {color: '.$params['description_title_color'].';}';
			$custom_css .= $customize.' .product-one-by-one__item-body.description h1,' .$customize.' .product-accordion__item-body.description .title-h1 {color: '.$params['description_title_color'].';}';
			$custom_css .= $customize.' .product-accordion__item-body.description h2,' .$customize.' .product-accordion__item-body.description .title-h2 {color: '.$params['description_title_color'].';}';
			$custom_css .= $customize.' .product-one-by-one__item-body.description h2,' .$customize.' .product-accordion__item-body.description .title-h2 {color: '.$params['description_title_color'].';}';
			$custom_css .= $customize.' .product-accordion__item-body.description h3,' .$customize.' .product-accordion__item-body.description .title-h3 {color: '.$params['description_title_color'].';}';
			$custom_css .= $customize.' .product-one-by-one__item-body.description h3,' .$customize.' .product-accordion__item-body.description .title-h3 {color: '.$params['description_title_color'].';}';
			$custom_css .= $customize.' .product-accordion__item-body.description h4,' .$customize.' .product-accordion__item-body.description .title-h4 {color: '.$params['description_title_color'].';}';
			$custom_css .= $customize.' .product-one-by-one__item-body.description h4,' .$customize.' .product-accordion__item-body.description .title-h4 {color: '.$params['description_title_color'].';}';
			$custom_css .= $customize.' .product-accordion__item-body.description h5,' .$customize.' .product-accordion__item-body.description .title-h5 {color: '.$params['description_title_color'].';}';
			$custom_css .= $customize.' .product-one-by-one__item-body.description h5,' .$customize.' .product-accordion__item-body.description .title-h5 {color: '.$params['description_title_color'].';}';
			$custom_css .= $customize.' .product-accordion__item-body.description h6,' .$customize.' .product-accordion__item-body.description .title-h6 {color: '.$params['description_title_color'].';}';
			$custom_css .= $customize.' .product-one-by-one__item-body.description h6,' .$customize.' .product-accordion__item-body.description .title-h6 {color: '.$params['description_title_color'].';}';
			$custom_css .= $customize.' .product-accordion__item-body.description .title-xlarge {color: '.$params['description_title_color'].';}';
			$custom_css .= $customize.' .product-one-by-one__item-body.description .title-xlarge {color: '.$params['description_title_color'].';}';
		}
		if (!empty($params['description_text_color'])) {
			$custom_css .= $customize.' .product-accordion__item-body.description {color: '.$params['description_text_color'].';}';
			$custom_css .= $customize.' .product-one-by-one__item-body.description {color: '.$params['description_text_color'].';}';
			$custom_css .= $customize.' .product-accordion__item-body.description .styled-subtitle {color: '.$params['description_text_color'].';}';
			$custom_css .= $customize.' .product-one-by-one__item-body.description .styled-subtitle {color: '.$params['description_text_color'].';}';
			$custom_css .= $customize.' .product-accordion__item-body.description .main-menu-item {color: '.$params['description_text_color'].';}';
			$custom_css .= $customize.' .product-one-by-one__item-body.description .main-menu-item {color: '.$params['description_text_color'].';}';
			$custom_css .= $customize.' .product-accordion__item-body.description .text-body {color: '.$params['description_text_color'].';}';
			$custom_css .= $customize.' .product-one-by-one__item-body.description .text-body {color: '.$params['description_text_color'].';}';
			$custom_css .= $customize.' .product-accordion__item-body.description .text-body-tiny {color: '.$params['description_text_color'].';}';
			$custom_css .= $customize.' .product-one-by-one__item-body.description .text-body-tiny {color: '.$params['description_text_color'].';}';
		}

        // Additional Styles
		if (!empty($params['additional_titles_color'])) {
			$custom_css .= $customize.' table.woocommerce-product-attributes .woocommerce-product-attributes-item__label {color: '.$params['additional_titles_color'].';}';
		}
		if (!empty($params['additional_text_color'])) {
			$custom_css .= $customize.' table.woocommerce-product-attributes .woocommerce-product-attributes-item__value {color: '.$params['additional_text_color'].';}';
		}

		// Reviews Styles
		if (empty($params['reviews_inner_title'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .woocommerce-Reviews-title {display: none;}';
		}
		if (empty($params['reviews_inner_title_add'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .comment-reply-title {display: none;}';
		}
		if ($params['reviews_inner_title_letter_spacing'] != '') {
			$custom_css .= $customize.' .woocommerce-Reviews .woocommerce-Reviews-title {letter-spacing: ' . $params['reviews_inner_title_letter_spacing'] . 'px;}';
			$custom_css .= $customize.' .woocommerce-Reviews .woocommerce-Reviews-title span {letter-spacing: ' . $params['reviews_inner_title_letter_spacing'] . 'px;}';
			$custom_css .= $customize.' .woocommerce-Reviews .comment-reply-title {letter-spacing: ' . $params['reviews_inner_title_letter_spacing'] . 'px;}';
			$custom_css .= $customize.' .woocommerce-Reviews .comment-reply-title span {letter-spacing: ' . $params['reviews_inner_title_letter_spacing'] . 'px;}';
		}
		if (!empty($params['reviews_titles_color'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .woocommerce-Reviews-title {color: '.$params['reviews_titles_color'].';}';
			$custom_css .= $customize.' .woocommerce-Reviews .comment-reply-title {color: '.$params['reviews_titles_color'].';}';
		}
		if (!empty($params['reviews_text_color'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .comment_container .meta strong {color: '.$params['reviews_text_color'].';}';
			$custom_css .= $customize.' .woocommerce-Reviews .comment_container .meta time {color: '.$params['reviews_text_color'].';}';
			$custom_css .= $customize.' .woocommerce-Reviews .comment-text .description {color: '.$params['reviews_text_color'].';}';
			$custom_css .= $customize.' .woocommerce-Reviews .comment-form label {color: '.$params['reviews_text_color'].';}';
			$custom_css .= $customize.' .woocommerce-Reviews .comment-form input {color: '.$params['reviews_text_color'].';}';
			$custom_css .= $customize.' .woocommerce-Reviews .comment-form textarea {color: '.$params['reviews_text_color'].';}';
			$custom_css .= $customize.' .woocommerce-Reviews .comment-form .comment-form-cookies-consent .checkbox-sign.checked::before {color: '.$params['reviews_text_color'].';}';
			$custom_css .= $customize.' .woocommerce-Reviews .woocommerce-noreviews {color: '.$params['reviews_text_color'].';}';
		}
		if (!empty($params['reviews_btn_text_color'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .comment-form .gem-button.submit {color: '.$params['reviews_btn_text_color'].';}';
			$custom_css .= $customize.' .product-tabs__nav-item sup {color: '.$params['reviews_btn_text_color'].';}';
		}
		if (!empty($params['reviews_btn_text_color_hover'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .comment-form .gem-button.submit:hover {color: '.$params['reviews_btn_text_color_hover'].';}';
		}
		if (!empty($params['reviews_btn_background_color'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .comment-form .gem-button.submit {background-color: '.$params['reviews_btn_background_color'].';}';
			$custom_css .= $customize.' .product-tabs__nav-item sup {background-color: '.$params['reviews_btn_background_color'].';}';
		}
		if (!empty($params['reviews_btn_background_color_hover'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .comment-form .gem-button.submit:hover {background-color: '.$params['reviews_btn_background_color_hover'].';}';
		}

		// Reviews Stars Styled
		if (!empty($params['reviews_stars_base_color'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .star-rating:before {color: ' . $params['reviews_stars_base_color'] . ';}';
		}
		if (!empty($params['reviews_stars_rated_color'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .star-rating > span:before {color: ' . $params['reviews_stars_rated_color'] . ';}';
		}
        if (!empty($params['reviews_stars_base_color'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .comment-form-rating .stars a:before {color: ' . $params['reviews_stars_base_color'] . ';}';
		}
		if (!empty($params['reviews_stars_rated_color'])) {
			$custom_css .= $customize.' .woocommerce-Reviews .comment-form-rating .stars a.rating-on:before {color: ' . $params['reviews_stars_rated_color'] . ';}';
		}

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		$custom_css .= get_post_meta(get_the_ID(), '_wpb_shortcodes_custom_css', true) . get_post_meta(get_the_ID(), '_wpb_post_custom_css', true);

		// Print custom css
		$css_output = '';
		if(!empty($custom_css)) {
			$css_output = '<style>'.$custom_css.'</style>';
		}

		$return_html = $css_output.$return_html;
		return thegem_templates_close_product($this->get_name(), $this->shortcode_settings(), $return_html);
	}

    public function set_general_params() {
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
		    'heading' => __('Layout', 'thegem'),
		    'param_name' => 'layout',
		    'value' => array_merge(array(
				    __('Tabs', 'thegem') => 'tabs',
				    __('Accordion', 'thegem') => 'accordion',
				    __('One by One', 'thegem') => 'one_by_one',
			    )
		    ),
		    'std' => 'tabs',
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Tabs Style', 'thegem'),
		    'param_name' => 'tabs_style',
		    'value' => array_merge(array(
				    __('Horizontal Tabs', 'thegem') => 'horizontal',
				    __('Vertical Tabs', 'thegem') => 'vertical',
			    )
		    ),
		    'std' => 'horizontal',
		    'dependency' => array(
			    'element' => 'layout',
			    'value' => array('tabs'),
			    'callback' => 'thegem_te_product_tabs_style_callback'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Tabs Alignment', 'thegem'),
		    'param_name' => 'tabs_align',
		    'value' => array_merge(array(
				    __('Left', 'thegem') => 'left',
				    __('Center', 'thegem') => 'center',
				    __('Right', 'thegem') => 'right',
				    __('Stretch', 'thegem') => 'stretch',
			    )
		    ),
		    'std' => 'left',
		    'dependency' => array(
			    'element' => 'layout',
			    'value' => array('tabs'),
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Accordion Item`s Height', 'thegem'),
		    'param_name' => 'accordion_height',
		    'value' => array_merge(array(
				    __('Full Height', 'thegem') => 'full-height',
				    __('Fixed Height', 'thegem') => 'fixed-height',
			    )
		    ),
		    'std' => 'full-height',
		    'dependency' => array(
			    'element' => 'layout',
			    'value' => array('accordion'),
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'checkbox',
		    'heading' => __('Stretch Background', 'thegem'),
		    'param_name' => 'stretch_background',
		    'value' => array(__('Show', 'thegem') => '1'),
		    'dependency' => array(
			    'element' => 'layout',
			    'value' => 'one_by_one'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Description Background', 'thegem'),
		    'param_name' => 'description_back',
		    'dependency' => array(
			    'element' => 'layout',
			    'value' => 'one_by_one'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Additional Info Background', 'thegem'),
		    'param_name' => 'additional_back',
		    'dependency' => array(
			    'element' => 'layout',
			    'value' => 'one_by_one'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Review Background', 'thegem'),
		    'param_name' => 'reviews_back',
		    'dependency' => array(
			    'element' => 'layout',
			    'value' => 'one_by_one'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-4',
		    'group' => $group
	    );

	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Tabs Titles Color', 'thegem'),
		    'param_name' => 'tabs_titles_color',
		    'std' => '',
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Tabs Titles Divider Color', 'thegem'),
		    'param_name' => 'tabs_titles_divider_color',
		    'std' => '',
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );

	    return $result;
    }

    public function set_descriptions_params() {
	    $resolutions = array('desktop', 'tablet', 'mobile');
	    $directions = array('top', 'bottom', 'left', 'right');
	    $result = array();
	    $group = __('General', 'thegem');

	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('"Description" Section', 'thegem'),
		    'param_name' => 'delimiter_heading_description',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
		    'group' => $group
	    );

	    $result[] = array(
		    'type' => 'checkbox',
		    'heading' => __('Description Section', 'thegem'),
		    'param_name' => 'description',
		    'value' => array(__('Show', 'thegem') => '1'),
		    'std' => '1',
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => $group
	    );

	    $result[] = array(
		    "type" => "textfield",
		    'heading' => __('Description Title', 'thegem'),
		    'param_name' => 'description_title',
		    'std' => 'Description',
		    'dependency' => array(
			    'element' => 'description',
			    'value' => '1'
		    ),
		    "edit_field_class" => "vc_column vc_col-sm-12",
		    'group' => $group
	    );

	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Description Tab Source', 'thegem'),
		    'param_name' => 'description_tab_source',
		    'value' => array_merge(array(
				    __('Product Extra Description', 'thegem') => 'default',
				    __('Product Content (Page Builder)', 'thegem') => 'page_builder',
			    )
		    ),
		    'std' => 'default',
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'description' => __(' "Product Extra Description": description tab will be populated by content added to "Product Extra Description" text area in a product; "Page Builder": description tab will be populated by content created in page builder in a product. ', 'thegem'),
		    'group' => $group
	    );

	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Titles Color', 'thegem'),
		    'param_name' => 'description_title_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'description',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );

	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Text Color', 'thegem'),
		    'param_name' => 'description_text_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'description',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );

	    foreach ($resolutions as $res) {
		    $result[] = array(
			    'type' => 'thegem_delimeter_heading_two_level',
			    'heading' => __(ucfirst($res).' Paddings', 'thegem'),
			    'param_name' => 'delimiter_heading_two_level_description',
			    'dependency' => array(
				    'element' => 'description',
				    'value' => '1',
			    ),
			    'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
			    'group' => $group
		    );
		    foreach ($directions as $dir) {
			    $result[] = array(
				    'type' => 'textfield',
				    'heading' => __(ucfirst($dir), 'thegem'),
				    'param_name' => 'description_padding_'.$res.'_'.$dir,
				    'value' => ($res == 'desktop' && ($dir == 'top' || $dir == 'bottom')) ? '60' : '',
				    'dependency' => array(
					    'element' => 'description',
					    'value' => '1',
				    ),
				    'edit_field_class' => 'vc_column vc_col-sm-3',
				    'group' => $group
			    );
		    }
	    }

	    $result[] = array(
		    'type' => 'thegem_delimeter_heading_two_level',
		    'heading' => __('', 'thegem'),
		    'param_name' => 'delimiter_heading_description_info',
		    'edit_field_class' => 'vc_column vc_col-sm-12 no-top-padding',
		    'dependency' => array(
			    'element' => 'description',
			    'value' => '1',
		    ),
		    'description' => __('Note: px units are used by default. For % units add values with %, eg. 10%', 'thegem'),
		    'group' => $group
	    );

	    return $result;
    }

    public function set_additional_params() {
	    $resolutions = array('desktop', 'tablet', 'mobile');
	    $directions = array('top', 'bottom', 'left', 'right');
	    $result = array();
	    $group = __('General', 'thegem');

	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('"Additional Info" Section', 'thegem'),
		    'param_name' => 'delimiter_heading_additional',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
		    'group' => $group
	    );

	    $result[] = array(
		    'type' => 'checkbox',
		    'heading' => __('Additional Info Section', 'thegem'),
		    'param_name' => 'additional',
		    'value' => array(__('Show', 'thegem') => '1'),
		    'std' => '1',
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => $group
	    );

	    $result[] = array(
		    "type" => "textfield",
		    'heading' => __('Additional Info Title', 'thegem'),
		    'param_name' => 'additional_title',
		    'std' => 'Additional Info',
		    'dependency' => array(
			    'element' => 'additional',
			    'value' => '1'
		    ),
		    "edit_field_class" => "vc_column vc_col-sm-12",
		    'group' => $group
	    );

	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Titles Color', 'thegem'),
		    'param_name' => 'additional_titles_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'additional',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Text Color', 'thegem'),
		    'param_name' => 'additional_text_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'additional',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );

	    foreach ($resolutions as $res) {
		    $result[] = array(
			    'type' => 'thegem_delimeter_heading_two_level',
			    'heading' => __(ucfirst($res).' Paddings', 'thegem'),
			    'param_name' => 'delimiter_heading_two_level_additional',
			    'dependency' => array(
				    'element' => 'additional',
				    'value' => '1',
			    ),
			    'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
			    'group' => 'General',
		    );
		    foreach ($directions as $dir) {
			    $result[] = array(
				    'type' => 'textfield',
				    'heading' => __(ucfirst($dir), 'thegem'),
				    'param_name' => 'additional_padding_'.$res.'_'.$dir,
				    'value' => ($res == 'desktop' && ($dir == 'top' || $dir == 'bottom')) ? '60' : '',
				    'dependency' => array(
					    'element' => 'additional',
					    'value' => '1',
				    ),
				    'edit_field_class' => 'vc_column vc_col-sm-3',
				    'group' => 'General',
			    );
		    }
	    }

	    $result[] = array(
		    'type' => 'thegem_delimeter_heading_two_level',
		    'heading' => __('', 'thegem'),
		    'param_name' => 'delimiter_heading_additional_info',
		    'edit_field_class' => 'vc_column vc_col-sm-12 no-top-padding',
		    'dependency' => array(
			    'element' => 'description',
			    'value' => '1',
		    ),
		    'description' => __('Note: px units are used by default. For % units add values with %, eg. 10%', 'thegem'),
		    'group' => 'General'
	    );

	    return $result;
    }

    public function set_reviews_params() {
	    $resolutions = array('desktop', 'tablet', 'mobile');
	    $directions = array('top', 'bottom', 'left', 'right');
	    $result = array();
	    $group = __('General', 'thegem');

	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('"Reviews" Section', 'thegem'),
		    'param_name' => 'delimiter_heading_reviews',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'checkbox',
		    'heading' => __('Reviews Section', 'thegem'),
		    'param_name' => 'reviews',
		    'value' => array(__('Show', 'thegem') => '1'),
		    'std' => '1',
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => $group
	    );
	    $result[] = array(
		    "type" => "textfield",
		    'heading' => __('Reviews Title', 'thegem'),
		    'param_name' => 'reviews_title',
		    'std' => 'Reviews',
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    "edit_field_class" => "vc_column vc_col-sm-12",
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Reviews Layout', 'thegem'),
		    'param_name' => 'reviews_columns',
		    'value' => array_merge(array(
			    __('One Column', 'thegem') => '1x',
			    __('Two Columns', 'thegem') => '2x',
		    )),
		    'std' => '2x',
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'checkbox',
		    'heading' => __('"Reviews" Title', 'thegem'),
		    'param_name' => 'reviews_inner_title',
		    'value' => array(__('Show', 'thegem') => '1'),
		    'std' => '1',
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'checkbox',
		    'heading' => __('"Add a review" Title', 'thegem'),
		    'param_name' => 'reviews_inner_title_add',
		    'value' => array(__('Show', 'thegem') => '1'),
		    'std' => '1',
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Text Style', 'thegem'),
		    'param_name' => 'reviews_inner_title_style',
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
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Font weight', 'thegem'),
		    'param_name' => 'reviews_inner_title_font_weight',
		    'value' => array(
			    __('Default', 'thegem') => 'light',
			    __('Bold', 'thegem') => 'bold',
		    ),
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'textfield',
		    'heading' => __('Letter Spacing', 'thegem'),
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'param_name' => 'reviews_inner_title_letter_spacing',
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Text Transform', 'thegem'),
		    'param_name' => 'reviews_inner_title_text_transform',
		    'value' => array(
			    __('Default', 'thegem') => '',
			    __('None', 'thegem') => 'transform-none',
			    __('Capitalize', 'thegem') => 'capitalize',
			    __('Lowercase', 'thegem') => 'lowercase',
			    __('Uppercase', 'thegem') => 'uppercase',
		    ),
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );

	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Titles Color', 'thegem'),
		    'param_name' => 'reviews_titles_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Text Color', 'thegem'),
		    'param_name' => 'reviews_text_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Button Text Color', 'thegem'),
		    'param_name' => 'reviews_btn_text_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Button Background Color', 'thegem'),
		    'param_name' => 'reviews_btn_background_color',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Button Text Color on Hover', 'thegem'),
		    'param_name' => 'reviews_btn_text_color_hover',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Button Background Color on Hover', 'thegem'),
		    'param_name' => 'reviews_btn_background_color_hover',
		    'std' => '',
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );

	    $result[] = array(
		    'type' => 'thegem_delimeter_heading_two_level',
		    'heading' => __('Rating Stars', 'thegem'),
		    'param_name' => 'delimiter_heading_two_level_reviews',
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'colorpicker',
		    'heading' => __('Rated Color', 'thegem'),
		    'param_name' => 'reviews_stars_rated_color',
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => $group
	    );
	    $result[] = array(
            'type' => 'colorpicker',
            'heading' => __('Base Color', 'thegem'),
            'param_name' => 'reviews_stars_base_color',
		    'dependency' => array(
			    'element' => 'reviews',
			    'value' => '1'
		    ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => $group
        );

	    foreach ($resolutions as $res) {
		    $result[] = array(
			    'type' => 'thegem_delimeter_heading_two_level',
			    'heading' => __(ucfirst($res).' Paddings', 'thegem'),
			    'param_name' => 'delimiter_heading_two_level_reviews',
			    'dependency' => array(
				    'element' => 'reviews',
				    'value' => '1',
			    ),
			    'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
			    'group' => $group
		    );
		    foreach ($directions as $dir) {
			    $result[] = array(
				    'type' => 'textfield',
				    'heading' => __(ucfirst($dir), 'thegem'),
				    'param_name' => 'reviews_padding_'.$res.'_'.$dir,
				    'value' => ($res == 'desktop' && ($dir == 'top' || $dir == 'bottom')) ? '60' : '',
				    'dependency' => array(
					    'element' => 'reviews',
					    'value' => '1',
				    ),
				    'edit_field_class' => 'vc_column vc_col-sm-3',
				    'group' => $group
			    );
		    }
	    }

	    $result[] = array(
		    'type' => 'thegem_delimeter_heading_two_level',
		    'heading' => __('', 'thegem'),
		    'param_name' => 'delimiter_heading_reviews_info',
		    'edit_field_class' => 'vc_column vc_col-sm-12 no-top-padding',
		    'dependency' => array(
			    'element' => 'description',
			    'value' => '1',
		    ),
		    'description' => __('Note: px units are used by default. For % units add values with %, eg. 10%', 'thegem'),
		    'group' => $group
	    );

	    return $result;
    }

    public function set_additional_tabs_params() {
	    $result = array();
	    $group = __('General', 'thegem');

	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('Additional Tabs', 'thegem'),
		    'param_name' => 'delimiter_heading_additional_tabs',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
		    'group' => $group
	    );
	    $result[] = array(
		    'type' => 'checkbox',
		    'heading' => __('Additional Tabs', 'thegem'),
		    'param_name' => 'additional_tabs',
		    'value' => array(__('Show', 'thegem') => '1'),
		    'std' => '1',
		    'edit_field_class' => 'vc_column vc_col-sm-12',
		    'group' => $group
	    );

        return $result;
    }

	public function shortcode_settings() {

		return array(
			'name' => __('Product Tabs', 'thegem'),
			'base' => 'thegem_te_product_tabs',
			'icon' => 'thegem-icon-wpb-ui-element-product-tabs',
			'category' => __('Single Product Builder', 'thegem'),
			'description' => __('Product Tabs (Product Builder)', 'thegem'),
			'params' => array_merge(

			    /* General - Layout */
				$this->set_general_params(),

				/* General - Description Tab */
				$this->set_descriptions_params(),

				/* General - Additional Info Tab */
				$this->set_additional_params(),

				/* General - Reviews Tab */
				$this->set_reviews_params(),

				/* General - Additional Tabs */
				$this->set_additional_tabs_params(),

				/* Extra Options */
				thegem_set_elements_extra_options(),

				/* Flex Options */
				thegem_set_elements_design_options('single-product')
			),
		);
	}
}

$templates_elements['thegem_te_product_tabs'] = new TheGem_Template_Element_Product_Tabs();
$templates_elements['thegem_te_product_tabs']->init();
