<?php

class TheGem_Template_Element_Portfolio_Info extends TheGem_Portfolio_Item_Template_Element
{

	public function __construct()
	{
	}

	public function get_name()
	{
		return 'thegem_te_portfolio_info';
	}

	public function get_meta_data()
	{
		$details_status = thegem_get_option('portfolio_project_details');

		if (isset($details_status) && !empty($details_status)) {
			return json_decode(thegem_get_option('portfolio_project_details_data'), true);
		}

		return [];
	}

	public function shortcode_output($atts, $content = '')
	{
		// General params
		$params = shortcode_atts(array_merge(array(
			'skin' => 'modern',
			'layout' => 'horizontal',
			'info_content' => '',
			'list_alignment' => 'left',
			'list_divider' => '1',
			'list_divider_color' => '',
			'list_spacing_desktop' => '',
			'list_spacing_tablet' => '',
			'list_spacing_mobile' => '',
			'table_vertical_spacing' => '',
			'table_horizontal_spacing' => '',
			'icon_color' => '',
			'icon_size' => '',
			'icon_spacing' => '',
			'icon_spacing_vertical' => '',
			'text_layout' => 'inline',
			'label_text_spacing' => '',
			'label_text_style' => '',
			'label_text_font_weight' => '',
			'label_text_font_style' => '',
			'label_text_letter_spacing' => '',
			'label_text_transform' => '',
			'label_text_color' => '',
			'label_colon' => '1',
			'value_text_spacing' => '',
			'value_text_style' => '',
			'value_text_font_weight' => '',
			'value_text_font_style' => '',
			'value_text_letter_spacing' => '',
			'value_text_transform' => '',
			'value_text_color' => '',
			'value_text_color_hover' => '',
			'cats_border' => 'solid',
			'cats_border_width' => '',
			'cats_border_radius' => '',
			'cats_text_color' => '',
			'cats_text_color_hover' => '',
			'cats_background_color' => '',
			'cats_background_color_hover' => '',
			'cats_border_color' => '',
			'cats_border_color_hover' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_portfolio_info');

		// Init portfolio info
		ob_start();
		$uniqid = uniqid('thegem-custom-') . rand(1, 9999);
		$portfolio = thegem_templates_init_portfolio();
		$info_content = vc_param_group_parse_atts($params['info_content']);

		if (empty($portfolio) || empty($info_content)) {
			ob_end_clean();
			return thegem_templates_close_portfolio($this->get_name(), $this->shortcode_settings(), '');
		}

		$skin = 'portfolio-info--' . $params['skin'];
		$layout = 'portfolio-info--' . $params['layout'];
		$alignment = 'portfolio-info--' . $params['list_alignment'];
		$divider = !empty($params['list_divider']) ? 'portfolio-info--divider-show' : 'portfolio-info--divider-hide';
		$colon = !empty($params['label_colon']) ? 'portfolio-info--colon-show' : 'portfolio-info--colon-hide';
		$text_layout = 'portfolio-info--text-' . $params['text_layout'];
		$params['element_class'] = implode(' ', array(
			$skin,
			$layout,
			$alignment,
			$divider,
			$colon,
			$text_layout,
			$params['element_class'],
			thegem_templates_responsive_options_output($params)
		));
		$label_text_styled = implode(' ', array($params['label_text_style'], $params['label_text_font_weight']));
		$value_text_styled = implode(' ', array($params['value_text_style'], $params['value_text_font_weight']));

		// Info Content output
		foreach ($info_content as $item){
			switch ($item['type']) {
				case 'cats':
					$label_output = $value_output = '';
					$cats = get_the_terms(get_the_ID(), 'thegem_portfolios');
					$cats_list = array();
					if ($cats) {
						foreach ($cats as $cat) {
							if (!empty($item['cats_link'])) {
								//$cats_list[] = '<a href="' . esc_url(get_category_link($cat->name)) . '" title="' . esc_attr(sprintf(__("View all posts in %s", "thegem"), $cat->name)) . '">' . $cat->cat_name . '</a>';
							} else {
								$cats_list[] = '<a href="javascript:void(0)" class="readonly">' . $cat->name . '</a>';
							}
						}
					}

					$value_output = implode('<span class="separator"></span>', $cats_list);

					if ($params['skin'] == 'table') {
						$value_output = '<div class="item-label"></div><div class="item-value">' . implode('<span class="separator"></span>', $cats_list) . '</div>';
					}

					if (!empty($value_output)) {
						echo '<div class="portfolio-info-item portfolio-info-item-' . $item['type'] . ' ' . $value_text_styled . '">' . $value_output . '</div>';
					}

					break;
				case 'date':
					$label_output = $value_output = $format = '';

					if (!empty($item['date_format'])) {
						if ($item['date_format'] == '1')
							$format = 'F j, Y';
						if ($item['date_format'] == '2')
							$format = 'Y-m-d';
						if ($item['date_format'] == '3')
							$format = 'm/d/Y';
						if ($item['date_format'] == '4')
							$format = 'd/m/Y';
						if ($item['date_format'] == '5')
							$format = 'd.m.Y';
					}

					if (!empty($item['date_icon']) && $item['date_icon'] == 'custom' && isset($item['date_icon_pack']) && !empty($item['date_icon_' . str_replace("-", "", $item['date_icon_pack'])])) {
						wp_enqueue_style('icons-' . $item['date_icon_pack']);
						$label_output .= '<div class="icon">' . thegem_build_icon($item['date_icon_pack'], $item['date_icon_' . str_replace("-", "", $item['date_icon_pack'])]) . '</div>';
					} else if (!empty($item['date_icon']) && $item['date_icon'] == 'default') {
						$label_output .= '<div class="icon"><i class="icon-default"></i></div>';
					}

					if (!empty($item['date_label'])) {
						$label_output .= '<div class="label-before">' . esc_html($item['date_label']) . '<span class="colon">:</span></div>';
					}
					$label_output = '<div class="item-label ' . $label_text_styled . '">' . $label_output . '</div>';

					if (!empty($item['date_link'])) {
						$year = get_the_time('Y');
						$month = get_the_time('m');
						$day = get_the_time('d');

						$value_output .= '<a class="date" href="' . get_day_link($year, $month, $day) . '">' . get_the_date($format, $portfolio) . '</a>';
					} else {
						$value_output .= '<div class="date">' . get_the_date($format, $portfolio) . '</div>';
					}
					$value_output = '<div class="item-value ' . $value_text_styled . '">' . $value_output . '</div>';

					if (!empty($value_output)) {
						echo '<div class="portfolio-info-item portfolio-info-item-' . $item['type'] . '">' . $label_output . ' ' . $value_output . '</div>';
					}

					break;
				case 'likes':
					if (!function_exists('zilla_likes')) break;

					$likes_css = '';
					if (!empty($item['likes_icon']) && $item['likes_icon'] == 'custom' && isset($item['likes_icon_pack']) && !empty($item['likes_icon_' . str_replace("-", "", $item['likes_icon_pack'])])) {
						wp_enqueue_style('icons-' . $item['likes_icon_pack']);

						$cmz = '.thegem-te-portfolio-info.' . $uniqid;
						if ($item['likes_icon_pack'] == 'elegant' && $item['likes_icon_elegant']) {
							$likes_css .= $cmz . ' .zilla-likes:before {content: "\\' . $item['likes_icon_elegant'] . '"; font-family: "ElegantIcons";}';
						}
						if ($item['likes_icon_pack'] == 'material' && $item['likes_icon_material']) {
							$likes_css .= $cmz . ' .zilla-likes:before {content: "\\' . $item['likes_icon_material'] . '"; font-family: "MaterialDesignIcons";}';
						}
						if ($item['likes_icon_pack'] == 'fontawesome' && $item['likes_icon_fontawesome']) {
							$likes_css .= $cmz . ' .zilla-likes:before {content: "\\' . $item['likes_icon_fontawesome'] . '"; font-family: "FontAwesome";}';
						}
						if ($item['likes_icon_pack'] == 'thegemdemo' && $item['likes_icon_thegemdemo']) {
							$likes_css .= $cmz . ' .zilla-likes:before {content: "\\' . $item['likes_icon_thegemdemo'] . '"; font-family: "TheGemDemoIcons";}';
						}
						if ($item['likes_icon_pack'] == 'userpack' && $item['likes_icon_userpack']) {
							$likes_css .= $cmz . ' .zilla-likes:before {content: "\\' . $item['likes_icon_userpack'] . '"; font-family: "Userpack";}';
						}
						if ($item['likes_icon_pack'] == 'thegem-header' && $item['likes_icon_thegemheader']) {
							$likes_css .= $cmz . ' .zilla-likes:before {content: "\\' . $item['likes_icon_thegemheader'] . '"; font-family: "TheGem Header";}';
						}
					}

					ob_start();
					zilla_likes();
					$likes_output = '<div class="likes">' . ob_get_clean() . '</div>';

					if (!empty($item['likes_label'])) {
						$likes_output .= '<div class="label-after">' . esc_html($item['likes_label']) . '</div>';
					}

					if ($params['skin'] == 'table') {
						$likes_output = '<div class="item-label"></div><div class="item-value">' . $likes_output . '</div>';
					}

					$likes_css_output = '';
					if (!empty($likes_css)) {
						$likes_css_output = '<style>' . $likes_css . '</style>';
					}

					if (!empty($likes_output)) {
						echo '<div class="portfolio-info-item portfolio-info-item-' . $item['type'] . ' ' . $value_text_styled . '">' . $likes_output . $likes_css_output . '</div>';
					}

					break;
				default:
					if (empty($this->get_meta_data())) break;

					$meta_data = [];
					if (!empty($this->get_meta_data())) {
						foreach ($this->get_meta_data() as $meta) {
							$key = '_thegem_cf_' . str_replace('-', '_', sanitize_title($meta['title']));
							$title =  __($meta['title'], 'thegem');
							$meta_data[$key] = $title;
						}
					}

					$meta_output = $label_output = $value_output = '';
					$select_field = !empty($item['type']) ? strstr($item['type'], '_') : '';
					$meta_value = !empty($select_field) ? get_post_meta($portfolio->ID, $select_field, true) : '';
					$meta_label = (!empty($meta_data) && !empty($select_field) && isset($meta_data[$select_field])) ? $meta_data[$select_field] : '';

					if (!empty($meta_value)) {
						if (!empty($item['icon']) && $item['icon'] == 'custom' && isset($item['icon_pack']) && !empty($item['icon_' . str_replace("-", "", $item['icon_pack'])])) {
							wp_enqueue_style('icons-' . $item['icon_pack']);
							$label_output .= '<div class="icon">' . thegem_build_icon($item['icon_pack'], $item['icon_' . str_replace("-", "", $item['icon_pack'])]) . '</div>';
						}

						if (!empty($item['label'])) {
							$label_text = !empty($item['label_text']) ? $item['label_text'] : $meta_label;
							$label_output .= '<div class="label-before">' . esc_html($label_text) . '<span class="colon">:</span></div>';
						}
						$label_output = '<div class="item-label ' . $label_text_styled . '">' . $label_output . '</div>';

						if (!empty($select_field) && !empty($item['field_type'])){
							if ($item['field_type'] == 'number') {
								$meta_value = floatval($meta_value);
								$decimal = explode('.', $meta_value);
								$decimal = isset($decimal[1]) ? strlen(($decimal[1])) : 0;
								$decimal = $decimal <= 3 ? $decimal : 3;

								if (!empty($item['field_format']) && $item['field_format'] == 'wp_locale') {
									$meta_value = number_format_i18n($meta_value, $decimal);
								}

								if (!empty($item['field_prefix'])) {
									$meta_value = $item['field_prefix'] . '' . $meta_value;
								}

								if (!empty($item['field_suffix'])) {
									$meta_value = $meta_value . '' . $item['field_suffix'];
								}

								$value_output = '<div class="meta">' . $meta_value . '</div>';
							} else {
								$value_output = !is_array($meta_value) ? '<div class="meta">' . $meta_value . '</div>' : '';
							}
						}

						if (!empty($item['link'])) {
							if ($item['link'] == 'custom') {
								$custom_link = !empty($item['link_custom']) ? vc_build_link($item['link_custom']) : null;

								if (!empty($custom_link['url'])) {
									$value_output = '<a href="' . $custom_link['url'] . '" class="meta-link" target="' . $custom_link['target'] . '" title="' . $custom_link['title'] . '" rel="' . $custom_link['rel'] . '">' . $value_output . '</a>';
								}
							} else {
								$dynamic_link = [
									'target' => !empty($item['link_target']) ? '_blank' : '_self',
									'rel' => !empty($item['link_nofollow']) ? 'nofollow' : '',
								];

								switch ($item['link_dynamic']) {
									case 'custom_fields':
										if (!empty($item['custom_fields_link_select'])) {
											$dynamic_link['url'] = get_post_meta($portfolio->ID, $item['custom_fields_link_select'], true);
										}

										break;
									case 'project_details':
										if (!empty($item['project_details_link_select'])) {
											$dynamic_link['url'] = get_post_meta($portfolio->ID, $item['project_details_link_select'], true);
										}

										break;
									default:
										if (!empty($item[$item['link_dynamic'].'_link_select'])) {
											$acf_link = get_post_meta($portfolio->ID, $item[$item['link_dynamic'].'_link_select'], true);

											if(is_array($acf_link)){
												$dynamic_link = [
													'url' => !empty($acf_link['url']) ? $acf_link['url'] : null,
													'target' => !empty($acf_link['target']) ? $acf_link['target'] : $dynamic_link['target'],
													'rel' => !empty($acf_link['rel']) ? $acf_link['rel'] : $dynamic_link['rel'],
												];
											} else{
												$dynamic_link['url'] = $acf_link;
											}
										}

										break;
								}

								if (!empty($dynamic_link['url'])) {
									$value_output = '<a href="' . $dynamic_link['url'] . '" class="meta-link" target="' . $dynamic_link['target'] . '" rel="' . $dynamic_link['rel'] . '">' . $value_output . '</a>';
								}
							}
						}

						if (!empty($value_output)) {
							$value_output = '<div class="item-value ' . $value_text_styled . '">' . $value_output . '</div>';
							echo '<div class="portfolio-info-item portfolio-info-item-meta">' . $label_output . ' ' . $value_output . '</div>';
						}
					}

					break;
			}
		}

		//Custom Styles
		$customize = '.thegem-te-portfolio-info.' . $uniqid;
		$custom_css = '';
		$resolution = array('desktop', 'tablet', 'mobile');
		$unit = 'px';

		// List Style
		if (!empty($params['list_divider_color'])) {
			$custom_css .= $customize . ' .portfolio-info-item:not(:last-child):after {background-color: ' . $params['list_divider_color'] . ';}';
			$custom_css .= $customize . ' .portfolio-info-item-cats .separator {background-color: ' . $params['list_divider_color'] . ';}';
		}
		foreach ($resolution as $res) {
			if (!empty($params['list_spacing_' . $res]) || strcmp($params['list_spacing_' . $res], '0') === 0) {
				$result = str_replace(' ', '', $params['list_spacing_' . $res]);
				if ($res == 'desktop') {
					if ($params['layout'] == 'horizontal') {
						$custom_css .= $customize . ' .portfolio-info-item {margin-right:' . $result . $unit . '; padding-right:' . $result . $unit . ';}';
						$custom_css .= $customize . ' .portfolio-info-item-cats .separator {margin: 0 ' . $result . $unit . ';}';
					} else {
						$custom_css .= $customize . ' .portfolio-info-item {margin-top:' . $result . $unit . ';}';
					}
				} else {
					$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
					if ($params['layout'] == 'horizontal') {
						$custom_css .= '@media screen and (' . $width . ') {' . $customize . ' .portfolio-info-item {margin-right:' . $result . $unit . '; padding-right:' . $result . $unit . ';}}';
						$custom_css .= '@media screen and (' . $width . ') {' . $customize . ' .portfolio-info-item-cats .separator {margin: 0 ' . $result . $unit . ';}}';
					} else {
						$custom_css .= '@media screen and (' . $width . ') {' . $customize . ' .portfolio-info-item {margin-top:' . $result . $unit . ';}}';
					}
				}
			}
		}
		if (!empty($params['table_vertical_spacing']) || $params['table_vertical_spacing'] == '0') {
			$custom_css .= $customize . ' .portfolio-info-item .item-label {padding-top: ' . $params['table_vertical_spacing'] . 'px; padding-bottom: ' . $params['table_vertical_spacing'] . 'px;}';
			$custom_css .= $customize . ' .portfolio-info-item .item-value {padding-top: ' . $params['table_vertical_spacing'] . 'px; padding-bottom: ' . $params['table_vertical_spacing'] . 'px;}';
		}
		if (!empty($params['table_horizontal_spacing']) || $params['table_horizontal_spacing'] == '0') {
			$custom_css .= $customize . ' .portfolio-info-item .item-label {padding-right: ' . $params['table_horizontal_spacing'] . 'px;}';
		}

		// Icon Style
		if (!empty($params['icon_size'])) {
			$custom_css .= $customize . ' .portfolio-info-item .icon i {font-size: ' . $params['icon_size'] . 'px; line-height: ' . $params['icon_size'] . 'px;}';
			$custom_css .= $customize . ' .portfolio-info-item .zilla-likes:before {font-size: ' . $params['icon_size'] . 'px; line-height: ' . $params['icon_size'] . 'px;}';
		}
		if (!empty($params['icon_spacing'])) {
			$custom_css .= $customize . ' .portfolio-info-item .icon {margin-right: ' . $params['icon_spacing'] . 'px;}';
			$custom_css .= $customize . ' .portfolio-info-item .zilla-likes:before {margin-right: ' . $params['icon_spacing'] . 'px;}';
		}
		if (!empty($params['icon_spacing_vertical'])) {
			$custom_css .= $customize . ' .portfolio-info-item .icon {margin-top: ' . $params['icon_spacing_vertical'] . 'px;}';
			$custom_css .= $customize . ' .portfolio-info-item .zilla-likes:before {margin-top: ' . $params['icon_spacing_vertical'] . 'px;}';
		}
		if (!empty($params['icon_color'])) {
			$custom_css .= $customize . ' .portfolio-info-item .icon {color: ' . $params['icon_color'] . ';}';
			$custom_css .= $customize . ' .portfolio-info-item .zilla-likes {color: ' . $params['icon_color'] . ' !important;}';
		}

		// Text Style
		if (!empty($params['label_text_spacing']) || $params['label_text_spacing'] == '0') {
			$custom_css .= $customize . ' .portfolio-info-item .item-label {padding-right: ' . $params['label_text_spacing'] . 'px;}';
		}
		if (!empty($params['value_text_spacing']) || $params['value_text_spacing'] == '0') {
			$custom_css .= $customize . ' .portfolio-info-item .item-value {padding-left: ' . $params['value_text_spacing'] . 'px;}';
		}
		if (!empty($params['value_text_letter_spacing']) || $params['value_text_letter_spacing'] == '0') {
			$custom_css .= $customize . ' .portfolio-info-item .item-value {letter-spacing: ' . $params['value_text_letter_spacing'] . 'px;}';
			$custom_css .= $customize . ' .portfolio-info-item .label-after {letter-spacing: ' . $params['value_text_letter_spacing'] . 'px;}';
		}
		if (!empty($params['value_text_transform'])) {
			$custom_css .= $customize . ' .portfolio-info-item .item-value {text-transform: ' . $params['value_text_transform'] . ';}';
			$custom_css .= $customize . ' .portfolio-info-item .label-after {text-transform: ' . $params['value_text_transform'] . ';}';
		}
		if (!empty($params['value_text_color'])) {
			$custom_css .= $customize . ' .portfolio-info-item .item-value {color: ' . $params['value_text_color'] . ';}';
			$custom_css .= $customize . ' .portfolio-info-item a {color: ' . $params['value_text_color'] . ';}';
			$custom_css .= $customize . ' .portfolio-info-item .label-after {color: ' . $params['value_text_color'] . ';}';
		}
		if (!empty($params['value_text_color_hover'])) {
			$custom_css .= $customize . ' .portfolio-info-item a:hover {color: ' . $params['value_text_color_hover'] . ';}';
			$custom_css .= $customize . ' .portfolio-info-item a .icon {transition: color 0.3s;}';
			$custom_css .= $customize . ' .portfolio-info-item a:hover .icon {color: ' . $params['value_text_color_hover'] . ';}';
			$custom_css .= $customize . ' .portfolio-info-item .zilla-likes:hover {color: ' . $params['value_text_color_hover'] . ' !important;}';
		}
		if (!empty($params['label_text_letter_spacing']) || $params['label_text_letter_spacing'] == '0') {
			$custom_css .= $customize . ' .portfolio-info-item .item-label {letter-spacing: ' . $params['label_text_letter_spacing'] . 'px;}';
		}
		if (!empty($params['label_text_transform'])) {
			$custom_css .= $customize . ' .portfolio-info-item .item-label {text-transform: ' . $params['label_text_transform'] . ';}';
		}
		if (!empty($params['label_text_color'])) {
			$custom_css .= $customize . ' .portfolio-info-item .item-label {color: ' . $params['label_text_color'] . ';}';
		}

		// Cats Style
		if (empty($params['cats_border'])) {
			$custom_css .= $customize . '.portfolio-info--modern .portfolio-info-item-cats a {border: 0 !important;}';
		}
		if (!empty($params['cats_border_width']) || $params['cats_border_width'] == '0') {
			$custom_css .= $customize . '.portfolio-info--modern .portfolio-info-item-cats a {border-width: ' . $params['cats_border_width'] . 'px;}';
		}
		if (!empty($params['cats_border_radius']) || $params['cats_border_radius'] == '0') {
			$custom_css .= $customize . '.portfolio-info--modern .portfolio-info-item-cats a {border-radius: ' . $params['cats_border_radius'] . 'px;}';
		}
		if (!empty($params['cats_text_color'])) {
			$custom_css .= $customize . '.portfolio-info--modern .portfolio-info-item-cats a {color: ' . $params['cats_text_color'] . ';}';
		}
		if (!empty($params['cats_text_color_hover'])) {
			$custom_css .= $customize . '.portfolio-info--modern .portfolio-info-item-cats a:not(.readonly):hover {color: ' . $params['cats_text_color_hover'] . ';}';
		}
		if (!empty($params['cats_background_color'])) {
			$custom_css .= $customize . '.portfolio-info--modern .portfolio-info-item-cats a {background-color: ' . $params['cats_background_color'] . ';}';
		}
		if (!empty($params['cats_background_color_hover'])) {
			$custom_css .= $customize . '.portfolio-info--modern .portfolio-info-item-cats a:not(.readonly):hover {background-color: ' . $params['cats_background_color_hover'] . ';}';
		}
		if (!empty($params['cats_border_color'])) {
			$custom_css .= $customize . '.portfolio-info--modern .portfolio-info-item-cats a {border-color: ' . $params['cats_border_color'] . ';}';
		}
		if (!empty($params['cats_border_color_hover'])) {
			$custom_css .= $customize . '.portfolio-info--modern .portfolio-info-item-cats a:not(.readonly):hover {border-color: ' . $params['cats_border_color_hover'] . ';}';
		}

		// Output Widget
		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		if (empty($return_html)) {
			return thegem_templates_close_portfolio($this->get_name(), $this->shortcode_settings(), $return_html);
		}

		ob_start()

		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?= esc_attr($params['element_id']); ?>"<?php endif; ?> class="thegem-te-portfolio-info <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
			<div class="portfolio-info"><?= $return_html ?></div>
		</div>

		<?php

		// Print html
		$return_html = ob_get_clean();

		// Print custom css
		$css_output = '';
		if (!empty($custom_css)) {
			$css_output = '<style>' . $custom_css . '</style>';
		}

		$return_html = $css_output . $return_html;

		return thegem_templates_close_portfolio($this->get_name(), $this->shortcode_settings(), $return_html);
	}

	public function get_info_content_types()
	{
		$data = array(
			__('Date', 'thegem') => 'date',
			__('Categories', 'thegem') => 'cats',
			__('Likes', 'thegem') => 'likes',
		);

		$meta_data = $this->get_meta_data();
		if (!empty($meta_data)) {
			foreach ($meta_data as $meta) {
				$key = __($meta['title'], 'thegem');
				$value = 'meta_thegem_cf_' . str_replace('-', '_', sanitize_title($meta['title']));
				$data[$key] = $value;
			}
		}
		return $data;
	}

	public function set_meta_type_params()
	{
		$result = array();

		$meta_source = [];
		if (!empty($this->get_meta_data())) {
			foreach ($this->get_meta_data() as $source) {
				$meta_source[] = 'meta' . $source['key'];
			}
		}

		$link_sources = array_merge(
			array(
				__('Custom Fields (TheGem)', 'thegem') => 'custom_fields',
				__('Project Details', 'thegem') => 'project_details',
			),
			thegem_cf_get_acf_plugin_groups()
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Field Type', 'thegem'),
			'param_name' => 'field_type',
			'value' => array(
				__('Text', 'thegem') => 'text',
				__('Number', 'thegem') => 'number',
			),
			'std' => 'text',
			'dependency' => array(
				'element' => 'type',
				'value' => $meta_source
			),
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Number Format', 'thegem'),
			'param_name' => 'field_format',
			'value' => array(
				__('WP Locale', 'thegem') => 'wp_locale',
				__('Disabled', 'thegem') => '',
			),
			'std' => 'wp_locale',
			'dependency' => array(
				'element' => 'field_type',
				'value' => 'number'
			),
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Prefix', 'thegem'),
			'param_name' => 'field_prefix',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'field_type',
				'value' => 'number'
			),
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Suffix', 'thegem'),
			'param_name' => 'field_suffix',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'field_type',
				'value' => 'number'
			),
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Icon', 'thegem'),
			'param_name' => 'icon',
			'value' => array(
				__('None', 'thegem') => '',
				__('Custom', 'thegem') => 'custom',
			),
			'std' => 'default',
			'dependency' => array(
				'element' => 'type',
				'value' => $meta_source
			),
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Icon pack', 'thegem'),
			'param_name' => 'icon_pack',
			'value' => array_merge(array(
				__('Elegant', 'thegem') => 'elegant',
				__('Material Design', 'thegem') => 'material',
				__('FontAwesome', 'thegem') => 'fontawesome',
				__('Header Icons', 'thegem') => 'thegem-header',
				__('Additional', 'thegem') => 'thegemdemo'),
				thegem_userpack_to_dropdown()
			),
			'std' => 'thegem-header',
			'dependency' => array(
				'element' => 'icon',
				'value' => 'custom'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
		);
		$result[] = array(
			'type' => 'thegem_icon',
			'heading' => __('Icon', 'thegem'),
			'param_name' => 'icon_elegant',
			'icon_pack' => 'elegant',
			'dependency' => array(
				'element' => 'icon_pack',
				'value' => array('elegant')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
		);
		$result[] = array(
			'type' => 'thegem_icon',
			'heading' => __('Icon', 'thegem'),
			'param_name' => 'icon_material',
			'icon_pack' => 'material',
			'dependency' => array(
				'element' => 'icon_pack',
				'value' => array('material')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
		);
		$result[] = array(
			'type' => 'thegem_icon',
			'heading' => __('Icon', 'thegem'),
			'param_name' => 'icon_fontawesome',
			'icon_pack' => 'fontawesome',
			'dependency' => array(
				'element' => 'icon_pack',
				'value' => array('fontawesome')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
		);
		$result[] = array(
			'type' => 'thegem_icon',
			'heading' => __('Icon', 'thegem'),
			'param_name' => 'icon_thegemdemo',
			'icon_pack' => 'thegemdemo',
			'dependency' => array(
				'element' => 'icon_pack',
				'value' => array('thegemdemo')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
		);
		$result[] = array(
			'type' => 'thegem_icon',
			'heading' => __('Icon', 'thegem'),
			'param_name' => 'icon_thegemheader',
			'icon_pack' => 'thegem-header',
			'dependency' => array(
				'element' => 'icon_pack',
				'value' => array('thegem-header')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
		);
		if (thegem_icon_userpack_enabled()) {
			$result[] = thegem_userpack_to_shortcode(array(
				'type' => 'thegem_icon',
				'heading' => __('Icon', 'thegem'),
				'param_name' => 'icon_userpack',
				'icon_pack' => 'userpack',
				'dependency' => array(
					'element' => 'icon_pack',
					'value' => array('userpack')
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
			));
		}
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Label', 'thegem'),
			'param_name' => 'label',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'dependency' => array(
				'element' => 'type',
				'value' => $meta_source
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Label Text', 'thegem'),
			'param_name' => 'label_text',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'dependency' => array(
				'element' => 'label',
				'value' => '1'
			),
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Link Type', 'thegem'),
			'param_name' => 'link',
			'value' => array(
				__('None', 'thegem') => '',
				__('Custom Link', 'thegem') => 'custom',
				__('Dynamic', 'thegem') => 'dynamic',
			),
			'std' => '',
			'dependency' => array(
				'element' => 'type',
				'value' => $meta_source
			),
		);
		$result[] = array(
			'type' => 'vc_link',
			'heading' => __('URL (Link)', 'thegem'),
			'param_name' => 'link_custom',
			'dependency' => array(
				'element' => 'link',
				'value' => 'custom'
			),
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Dynamic Link Source', 'thegem'),
			'param_name' => 'link_dynamic',
			'value' => $link_sources,
			'std' => '',
			'dependency' => array(
				'element' => 'link',
				'value' => 'dynamic',
			),
		);
		foreach($link_sources as $source) {
			if (!empty(thegem_cf_get_fields_is_type_link($source))) {
				$result[] = array(
					'type' => 'dropdown',
					'heading' => __('Select Dynamic Link', 'thegem'),
					'param_name' => $source. '_link_select',
					'value' => thegem_cf_get_fields_is_type_link($source),
					'std' => '',
					'dependency' => array(
						'element' => 'link_dynamic',
						'value' => $source,
					),
				);
			}
		}
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Open link in a new tab', 'thegem'),
			'param_name' => 'link_target',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '0',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'link',
				'value' => 'dynamic',
			),
		);
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Add nofollow option to link', 'thegem'),
			'param_name' => 'link_nofollow',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '0',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'link',
				'value' => 'dynamic',
			),
		);

		return $result;
	}

	public function set_zilla_likes_params() {
		$result = array();

		if(thegem_is_plugin_active('zilla-likes/zilla-likes.php') && function_exists('zilla_likes')) {
			$result = array_merge(
				array(
					array(
						'type' => 'textfield',
						'heading' => __('Label', 'thegem'),
						'param_name' => 'likes_label',
						'dependency' => array(
							'element' => 'type',
							'value' => 'likes'
						),
						'std' => '',
						'edit_field_class' => 'vc_column vc_col-sm-12',
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'likes_icon',
						'value' => array(
							__('None', 'thegem') => '',
							__('Default', 'thegem') => 'default',
							__('Custom', 'thegem') => 'custom',
						),
						'std' => 'default',
						'dependency' => array(
							'element' => 'type',
							'value' => 'likes'
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Icon pack', 'thegem'),
						'param_name' => 'likes_icon_pack',
						'value' => array_merge(array(
							__('Elegant', 'thegem') => 'elegant',
							__('Material Design', 'thegem') => 'material',
							__('FontAwesome', 'thegem') => 'fontawesome',
							__('Header Icons', 'thegem') => 'thegem-header',
							__('Additional', 'thegem') => 'thegemdemo'),
							thegem_userpack_to_dropdown()
						),
						'std' => 'thegem-header',
						'dependency' => array(
							'element' => 'likes_icon',
							'value' => 'custom'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'likes_icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'likes_icon_pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'likes_icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'likes_icon_pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'likes_icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'likes_icon_pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'likes_icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'likes_icon_pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'likes_icon_thegemheader',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'likes_icon_pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'likes_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'likes_icon_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
				))
			);
		} else {
			$result = array(
				array(
					'type' => 'thegem_delimeter_heading',
					'param_name' => 'layout_delim_head',
					'edit_field_class' => 'vc_column vc_col-sm-12',
					'dependency' => array(
						'element' => 'type',
						'value' => 'likes'
					),
					'description' => __('<div class="thegem-param-alert">To show likes please install and activate <a href="'.get_site_url().'/wp-admin/admin.php?page=install-required-plugins" target="_blank">ZillaLikes plugin</a>.</div>', 'thegem'),
				)
			);
		}

		return $result;
	}

	public function set_layout_params()
	{
		$result = array();
		$group = __('General', 'thegem');

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Skin', 'thegem'),
			'param_name' => 'skin',
			'value' => array_merge(array(
					__('Classic', 'thegem') => 'classic',
					__('Modern', 'thegem') => 'modern',
					__('Table', 'thegem') => 'table',
				)
			),
			'std' => 'modern',
			'dependency' => array(
				'callback' => 'thegem_te_post_info_callback'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Layout', 'thegem'),
			'param_name' => 'layout',
			'value' => array_merge(array(
					__('Horizontal', 'thegem') => 'horizontal',
					__('Vertical', 'thegem') => 'vertical',
				)
			),
			'std' => 'horizontal',
			'dependency' => array(
				'element' => 'skin',
				'value' => array('classic', 'modern'),
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'param_group',
			'heading' => __('Items', 'thegem'),
			'param_name' => 'info_content',
			'params' => array_merge(
				array(
					array(
						'type' => 'dropdown',
						'heading' => __('Field', 'thegem'),
						'param_name' => 'type',
						'value' => $this->get_info_content_types(),
						'description' => __('Go to the <a href="' . get_site_url() . '/wp-admin/admin.php?page=thegem-theme-options#/single-pages/portfolio" target="_blank">Theme Options -> Portfolio Page</a> to manage your project details fields.', 'thegem'),
					),
				),

				// Date
				array(
					array(
						'type' => 'textfield',
						'heading' => __('Label', 'thegem'),
						'param_name' => 'date_label',
						'dependency' => array(
							'element' => 'type',
							'value' => 'date'
						),
						'std' => '',
						'edit_field_class' => 'vc_column vc_col-sm-12',
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Date Format', 'thegem'),
						'param_name' => 'date_format',
						'value' => array_merge(
							array(
								__('Default', 'thegem') => '',
								__('March 6, 2018 (F j, Y)', 'thegem') => '1',
								__('2018-03-06 (Y-m-d)', 'thegem') => '2',
								__('03/06/2018 (m/d/Y)', 'thegem') => '3',
								__('06/03/2018 (d/m/Y)', 'thegem') => '4',
								__('06.03.2018 (d.m.Y)', 'thegem') => '5',
							)
						),
						'std' => '',
						'dependency' => array(
							'element' => 'type',
							'value' => 'date'
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'date_icon',
						'value' => array(
							__('None', 'thegem') => '',
							__('Default', 'thegem') => 'default',
							__('Custom', 'thegem') => 'custom',
						),
						'std' => 'default',
						'dependency' => array(
							'element' => 'type',
							'value' => 'date'
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Icon pack', 'thegem'),
						'param_name' => 'date_icon_pack',
						'value' => array_merge(array(
							__('Elegant', 'thegem') => 'elegant',
							__('Material Design', 'thegem') => 'material',
							__('FontAwesome', 'thegem') => 'fontawesome',
							__('Header Icons', 'thegem') => 'thegem-header',
							__('Additional', 'thegem') => 'thegemdemo'),
							thegem_userpack_to_dropdown()
						),
						'std' => 'thegem-header',
						'dependency' => array(
							'element' => 'date_icon',
							'value' => 'custom'
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'date_icon_elegant',
						'icon_pack' => 'elegant',
						'dependency' => array(
							'element' => 'date_icon_pack',
							'value' => array('elegant')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'date_icon_material',
						'icon_pack' => 'material',
						'dependency' => array(
							'element' => 'date_icon_pack',
							'value' => array('material')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'date_icon_fontawesome',
						'icon_pack' => 'fontawesome',
						'dependency' => array(
							'element' => 'date_icon_pack',
							'value' => array('fontawesome')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'date_icon_thegemdemo',
						'icon_pack' => 'thegemdemo',
						'dependency' => array(
							'element' => 'date_icon_pack',
							'value' => array('thegemdemo')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'date_icon_thegemheader',
						'icon_pack' => 'thegem-header',
						'dependency' => array(
							'element' => 'date_icon_pack',
							'value' => array('thegem-header')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
				),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'date_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'date_icon_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
				)),
				array(
					array(
						'type' => 'checkbox',
						'heading' => __('Link', 'thegem'),
						'param_name' => 'date_link',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '0',
						'dependency' => array(
							'element' => 'type',
							'value' => 'date'
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
					),
				),

				//Sets
				/*
				array(
					array(
						'type' => 'checkbox',
						'heading' => __('Label', 'thegem'),
						'param_name' => 'cats_label',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'edit_field_class' => 'vc_column vc_col-sm-12',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Label Text', 'thegem'),
						'param_name' => 'cats_label_text',
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'dependency' => array(
							'element' => 'cats_label',
							'value' => '1'
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Link', 'thegem'),
						'param_name' => 'cats_link',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '1',
						'dependency' => array(
							'element' => 'type',
							'value' => 'cats'
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
					),
				),
				*/

				// Likes
				$this->set_zilla_likes_params(),

				// Meta details
				$this->set_meta_type_params()
			),
			'group' => $group
		);

		return $result;
	}

	public function set_style_params()
	{
		$result = array();
		$group = __('Style', 'thegem');
		$resolutions = array('desktop', 'tablet', 'mobile');
		$positions = array('top', 'right', 'bottom', 'left');

		// List
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('List', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Alignment', 'thegem'),
			'param_name' => 'list_alignment',
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
			'type' => 'checkbox',
			'heading' => __('Divider', 'thegem'),
			'param_name' => 'list_divider',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'dependency' => array(
				'element' => 'skin',
				'value' => array('classic', 'table')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Divider Color', 'thegem'),
			'param_name' => 'list_divider_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'list_divider',
				'value' => '1'
			),
			'group' => $group
		);
		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('Space Between', 'thegem'),
			'param_name' => 'delimiter_heading_two_level_panel',
			'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
			'group' => $group
		);
		foreach ($resolutions as $res) {
			$result[] = array(
				'type' => 'textfield',
				'heading' => __('' . ucfirst($res) . '', 'thegem'),
				'param_name' => 'list_spacing_' . $res,
				'value' => '',
				'dependency' => array(
					'element' => 'skin',
					'value' => array('classic', 'modern')
				),
				'edit_field_class' => 'vc_column vc_col-sm-4',
				'group' => $group
			);
		}
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Vertical Spacing', 'thegem'),
			'param_name' => 'table_vertical_spacing',
			'value' => '',
			'dependency' => array(
				'element' => 'skin',
				'value' => array('table')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Horizontal Spacing', 'thegem'),
			'param_name' => 'table_horizontal_spacing',
			'value' => '',
			'dependency' => array(
				'element' => 'skin',
				'value' => array('table')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		// Icon
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Icon', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Size', 'thegem'),
			'param_name' => 'icon_size',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Horizontal Spacing', 'thegem'),
			'param_name' => 'icon_spacing',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Vertical Spacing', 'thegem'),
			'param_name' => 'icon_spacing_vertical',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Color', 'thegem'),
			'param_name' => 'icon_color',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		// Text
		$texts = ['label', 'value'];
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Text', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Layout', 'thegem'),
			'param_name' => 'text_layout',
			'value' => array(
				__('Inline', 'thegem') => 'inline',
				__('Vertical', 'thegem') => 'vertical',
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Label Spacing', 'thegem'),
			'param_name' => 'label_text_spacing',
			'dependency' => array(
				'element' => 'skin',
				'value' => array('classic', 'modern')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Value Spacing', 'thegem'),
			'param_name' => 'value_text_spacing',
			'dependency' => array(
				'element' => 'skin',
				'value' => array('classic', 'modern')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		foreach ($texts as $txt) {
			$result[] = array(
				'type' => 'thegem_delimeter_heading_two_level',
				'heading' => __(ucfirst($txt), 'thegem'),
				'param_name' => 'padding_top_sub_delim_head',
				'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12',
				'group' => $group
			);
			$result[] = array(
				'type' => 'dropdown',
				'heading' => __(ucfirst($txt) . ' Text Style', 'thegem'),
				'param_name' => $txt . '_text_style',
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
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			$result[] = array(
				'type' => 'dropdown',
				'heading' => __(ucfirst($txt) . ' Font weight', 'thegem'),
				'param_name' => $txt . '_text_font_weight',
				'value' => array(
					__('Default', 'thegem') => '',
					__('Thin', 'thegem') => 'light',
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			$result[] = array(
				'type' => 'textfield',
				'heading' => __(ucfirst($txt) . ' Letter Spacing', 'thegem'),
				'param_name' => $txt . '_text_letter_spacing',
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			$result[] = array(
				'type' => 'dropdown',
				'heading' => __(ucfirst($txt) . ' Text Transform', 'thegem'),
				'param_name' => $txt . '_text_transform',
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
				'type' => 'colorpicker',
				'heading' => __(ucfirst($txt) . ' Text Color', 'thegem'),
				'param_name' => $txt . '_text_color',
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			if ($txt === 'label') {
				$result[] = array(
					'type' => 'checkbox',
					'heading' => __('Colon', 'thegem'),
					'param_name' => $txt . '_colon',
					'value' => array(__('Yes', 'thegem') => '1'),
					'std' => '1',
					'edit_field_class' => 'vc_column vc_col-sm-6',
					'group' => $group
				);
			}
			if ($txt === 'value') {
				$result[] = array(
					'type' => 'colorpicker',
					'heading' => __(ucfirst($txt) . ' Text Color on Hover', 'thegem'),
					'param_name' => $txt . '_text_color_hover',
					'edit_field_class' => 'vc_column vc_col-sm-6',
					'group' => $group
				);
			}
		}

		// Categories
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Categories', 'thegem'),
			'param_name' => 'style_delim_head_categories',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Border', 'thegem'),
			'param_name' => 'cats_border',
			'value' => array_merge(array(
					__('None', 'thegem') => '',
					__('Solid', 'thegem') => 'solid',
				)
			),
			'std' => 'solid',
			'dependency' => array(
				'element' => 'skin',
				'value' => array('modern')
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Width', 'thegem'),
			'param_name' => 'cats_border_width',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'cats_border',
				'value' => array('solid', 'double', 'dotted', 'dashed', 'groove')
			),
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Radius', 'thegem'),
			'param_name' => 'cats_border_radius',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'cats_border',
				'value' => array('solid', 'double', 'dotted', 'dashed', 'groove')
			),
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'cats_text_color',
			'dependency' => array(
				'element' => 'skin',
				'value' => array('modern')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color on Hover', 'thegem'),
			'param_name' => 'cats_text_color_hover',
			'dependency' => array(
				'element' => 'skin',
				'value' => array('modern')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color', 'thegem'),
			'param_name' => 'cats_background_color',
			'dependency' => array(
				'element' => 'skin',
				'value' => array('modern')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color on Hover', 'thegem'),
			'param_name' => 'cats_background_color_hover',
			'dependency' => array(
				'element' => 'skin',
				'value' => array('modern')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color', 'thegem'),
			'param_name' => 'cats_border_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'cats_border',
				'value' => array('solid', 'double', 'dotted', 'dashed', 'groove')
			),
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color on Hover', 'thegem'),
			'param_name' => 'cats_border_color_hover',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'cats_border',
				'value' => array('solid', 'double', 'dotted', 'dashed', 'groove')
			),
			'group' => $group
		);

		return $result;
	}

	public function shortcode_settings()
	{
		return array(
			'name' => __('Project Meta/Details', 'thegem'),
			'base' => 'thegem_te_portfolio_info',
			'icon' => 'thegem-icon-wpb-ui-element-portfolio-info',
			'category' => __('Portfolio Page Builder', 'thegem'),
			'description' => __('Project Meta/Details (Portfolio Page Builder)', 'thegem'),
			'params' => array_merge(

					/* General - Layout */
				$this->set_layout_params(),

				/* General - Styles */
				$this->set_style_params(),

				/* Extra Options */
				thegem_set_elements_extra_options(),

				/* Responsive Options */
				thegem_set_elements_responsive_options()
			),
		);
	}
}

$templates_elements['thegem_te_portfolio_info'] = new TheGem_Template_Element_Portfolio_Info();
$templates_elements['thegem_te_portfolio_info']->init();
