<?php

class TheGem_Template_Element_Post_Info extends TheGem_Single_Post_Template_Element {
	public $show_in_loop = true;
	public function __construct() {
	}

	public function get_name() {
		return 'thegem_te_post_info';
	}

	public function is_loop_builder_template() {
		if ((!empty($GLOBALS['thegem_template_type']) && $GLOBALS['thegem_template_type'] == 'loop-item') || thegem_is_template_post('loop-item')) {
			return true;
		}

		return false;
	}

	function get_post_types()
	{
		$post_types = array();
		foreach (get_post_types(array('public' => true), 'object') as $slug => $post_type) {
			if (!in_array($slug, array('product', 'thegem_news', 'thegem_footer', 'thegem_title', 'thegem_templates', 'attachment'), true)) {
				$post_types[] = $slug;
			}
		}

		return $post_types;
	}

	public function get_taxonomy_list()
	{
		$taxonomies = get_object_taxonomies( $this->get_post_types(), 'objects' );
		if (!empty($taxonomies)) {
			foreach ($taxonomies as $taxonomy) {
				$data[$taxonomy->label] = $taxonomy->name;
			}
		}

		return $data;
	}

	public function get_default_taxonomy()
	{
		$taxonomies = array_flip($this->get_taxonomy_list());
		if (empty($taxonomies)) return;

		return array_keys($taxonomies)[0];
	}

	public function build_terms_hierarchy($elements) {
		$parents_elements = array();
		foreach($elements as $el) {
			$parents = get_ancestors($el, get_term($el)->taxonomy);
			$is_parent = true;
			foreach($parents as $parent) {
				if(in_array($parent, $elements)) {
					$is_parent = false;
				}
			}
			if($is_parent) {
				$parents_elements[] = $el;
			}
		}
		$childrens = array_diff($elements, $parents_elements);
		$result = array();
		foreach($parents_elements as $parent_id) {
			$result[$parent_id] = array();
			foreach($childrens as $child_id) {
				if(term_is_ancestor_of($parent_id, $child_id, get_term($parent_id)->taxonomy)) {
					$result[$parent_id][] = $child_id;
				}
			}
			$result[$parent_id] = $this->build_terms_hierarchy($result[$parent_id]);
		}
		return $result;
	}

	public function terms_hierarchy_to_list($elements = array()) {
		$result = array();
		foreach($elements as $parent => $children) {
			$result[] = $parent;
			if(!empty($children)) {
				$result = array_merge($result, $this->terms_hierarchy_to_list($children));
			}
		}
		return $result;
	}

	public function shortcode_output($atts, $content = '') {
		// General params
		$params = shortcode_atts(array_merge(array(
			'skin' => 'classic',
			'layout' => 'horizontal',
			'info_content' => '',
			'list_alignment' => '',
			'list_divider' => '1',
			'list_divider_color' => '',
			'list_spacing_desktop' => '',
			'list_spacing_tablet' => '',
			'list_spacing_mobile' => '',
			'icon_color' => '',
			'icon_size' => '',
			'icon_spacing' => '',
			'text_style' => '',
			'text_font_weight' => '',
			'text_font_style' => '',
			'text_letter_spacing' => '',
			'text_transform' => '',
			'text_color' => '',
			'text_color_hover' => '',
			'cats_border' => 'solid',
			'cats_border_width' => '',
			'cats_border_radius' => '',
			'cats_text_color' => '',
			'cats_text_color_hover' => '',
			'cats_background_color' => '',
			'cats_background_color_hover' => '',
			'cats_border_color' => '',
			'cats_border_color_hover' => '',
			'terms_taxonomy_style' => 'default',
			'terms_taxonomy_delimiter' => ',',
			'terms_list_border' => '',
			'terms_list_border_width' => '',
			'terms_list_border_radius' => '',
			'terms_list_text_color' => '',
			'terms_list_text_color_hover' => '',
			'terms_list_background_color' => '',
			'terms_list_border_color' => '',
			'container_layout_tablet_padding_top' => '',
			'container_layout_tablet_padding_bottom' => '',
			'container_layout_tablet_padding_left' => '',
			'container_layout_tablet_padding_right' => '',
			'container_layout_mobile_padding_top' => '',
			'container_layout_mobile_padding_bottom' => '',
			'container_layout_mobile_padding_left' => '',
			'container_layout_mobile_padding_right' => '',
			'container_layout_tablet_margin_top' => '',
			'container_layout_tablet_margin_bottom' => '',
			'container_layout_tablet_margin_left' => '',
			'container_layout_tablet_margin_right' => '',
			'container_layout_mobile_margin_top' => '',
			'container_layout_mobile_margin_bottom' => '',
			'container_layout_mobile_margin_left' => '',
			'container_layout_mobile_margin_right' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_post_info');

		// Init Featured Image
		ob_start();
		$uniqid = uniqid('thegem-custom-').rand(1,9999);
		$single_post = thegem_templates_init_post();
		$info_content = vc_param_group_parse_atts($params['info_content']);

		if (empty($single_post) || empty($info_content)) {
			ob_end_clean();
			return thegem_templates_close_single_post($this->get_name(), $this->shortcode_settings(), '');
		}

		// Layout Design Options
		$params['layout_design_wrap_class'] = '';
		if (!empty($atts['container_layout'])) {
			$custom_css_class = vc_shortcode_custom_css_class($atts['container_layout']);
			$params['layout_design_wrap_class'] = ' '.$custom_css_class;
			$params['layout_design_wrap_class'] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $params['layout_design_wrap_class'], 'thegem_te_post_info', $atts );
		}

		$skin = 'post-info--'.$params['skin'];
		$layout = 'post-info--'.$params['layout'];
		$alignment = 'post-info--'.$params['list_alignment'];
		$divider = $params['skin'] == 'classic' && empty($params['list_divider']) ? 'post-info--divider-hide' : 'post-info--divider-show';
		$loop_item = $this->is_loop_builder_template() ? 'post-info--loop-item' : '';
		$params['element_class'] = implode(' ', array(
			$skin,
			$layout,
			$alignment,
			$divider,
			$loop_item,
			$params['element_class'],
			$params['layout_design_wrap_class'],
			thegem_templates_responsive_options_output($params)
		));
		if(empty($params['text_style']) && thegem_is_loop_builder_template()) {
			$params['text_style'] = 'text-body-tiny';
		}
		$text_styled = implode(' ', array($params['text_style'], $params['text_font_weight']));

		// Info Content output
		foreach ($info_content as $item){
			switch ($item['type']) {
				case 'cats':
					$cats = get_the_category();
					$cats_list = [];
					foreach ($cats as $cat) {
						$cats_list[] = '<a href="' . esc_url(get_category_link($cat->term_id)) . '" title="' . esc_attr(sprintf(__("View all posts in %s", "thegem"), $cat->name)) . '">' . $cat->cat_name . '</a>';
					}

					$cats_output = implode(' <span class="separator"></span> ', $cats_list);

					if (!empty($cats_output)) {
						echo '<div class="post-info-item post-info-item-' . $item['type'] . ' ' . $text_styled . '">' . $cats_output . '</div>';
					}

					break;
				case 'terms':
					$taxes = array();
					$post_terms_ids = wp_get_post_terms($single_post->ID, $item['terms_taxonomy'], array('fields' => 'ids'));
					$post_terms_hierarchy = $this->build_terms_hierarchy($post_terms_ids);
					$post_terms_ids_list = $this->terms_hierarchy_to_list($post_terms_hierarchy);

					if (!empty($item['terms_taxonomy_truncate']) && !empty($item['terms_taxonomy_truncate_number'])) {
						$post_terms_ids_list = array_slice($post_terms_ids_list, 0, $item['terms_taxonomy_truncate_number']);
					}
					foreach($post_terms_ids_list as $term_id) {
						$taxes[] = get_term($term_id);
					}

					$taxes_list = [];
					foreach ($taxes as $tax) {
						if (!empty($item['terms_link'])) {
							$taxes_list[] = '<a href="' . esc_url(get_category_link($tax->term_id)) . '">' . $tax->name . '</a>';
						} else {
							$taxes_list[] = '<span class="readonly">' . $tax->name . '</span>';
						}
					}

					if ($params['skin'] == 'classic' && !empty($params['terms_taxonomy_style']) && $params['terms_taxonomy_style'] == 'list') {
						$taxes_output = implode('<span class="separator">'.$params['terms_taxonomy_delimiter'].'</span>', $taxes_list);

						if (!empty($taxes_output)) {
							echo '<div class="post-info-item post-info-item-cats-list ' . $text_styled . '">' . $taxes_output . '</div>';
						}
					} else {
						$taxes_output = implode(' <span class="separator"></span> ', $taxes_list);

						if (!empty($taxes_output)) {
							echo '<div class="post-info-item post-info-item-cats ' . $text_styled . '">' . $taxes_output . '</div>';
						}
					}

					break;
				case 'author':
					$author_output = $author_label = '';

					if (!empty($item['author_avatar'])) {
						$size = !empty($item['author_avatar_size']) ? $item['author_avatar_size'] : 20;
						$author_output .= '<div class="avatar">'.get_avatar($single_post, $size ).'</div>';
					}

					if (!empty($item['author_label'])) {
						$author_label = esc_html($item['author_label']);
					}

					if (!empty($item['author_link'])) {
						$author_output .= '<div class="name">'.$author_label.' '.get_the_author_posts_link().'</div>';
					} else {
						$author_output .= '<div class="name">'.$author_label.' '.get_the_author_meta('display_name').'</div>';
					}

					if (!empty($author_output)) {
						echo '<div class="post-info-item post-info-item-'.$item['type'].' '.$text_styled.'">'.$author_output.'</div>';
					}

					break;
				case 'date':
					$date_output = $format = '';

					if (!empty($item['date_format'])) {
						if ($item['date_format'] == '1') $format = 'F j, Y';
						if ($item['date_format'] == '2') $format = 'Y-m-d';
						if ($item['date_format'] == '3') $format = 'm/d/Y';
						if ($item['date_format'] == '4') $format = 'd/m/Y';
						if ($item['date_format'] == '5') $format = 'd.m.Y';
					}

					if (!empty($item['date_icon']) && $item['date_icon'] == 'custom' && isset($item['date_icon_pack']) && !empty($item['date_icon_' . str_replace("-", "", $item['date_icon_pack'])])) {
						wp_enqueue_style('icons-' . $item['date_icon_pack']);
						$date_output .= '<div class="icon">'.thegem_build_icon($item['date_icon_pack'], $item['date_icon_' . str_replace("-", "", $item['date_icon_pack'])]).'</div>';
					} else if (!empty($item['date_icon']) && $item['date_icon'] == 'default') {
						$date_output .= '<div class="icon"><i class="icon-default"></i></div>';
					}

					if (!empty($item['date_label'])) {
						$date_output .= '<div class="label-before">'.esc_html($item['date_label']).'</div>';
					}

					if (!empty($item['date_link'])) {
						$year = get_the_time('Y');
						$month = get_the_time('m');
						$day = get_the_time('d');

						$date_output .= '<a class="date" href="'.get_day_link( $year, $month, $day).'">'.get_the_date($format, $single_post).'</a>';
					} else {
						$date_output .= '<div class="date">'.get_the_date($format, $single_post).'</div>';
					}

					if (!empty($date_output)) {
						echo '<div class="post-info-item post-info-item-'.$item['type'].' '.$text_styled.'">'.$date_output.'</div>';
					}

					break;
				case 'time':
					$time_output = $format = '';

					if (!empty($item['time_format'])) {
						if ($item['time_format'] == '1') $format = 'g:i a';
						if ($item['time_format'] == '2') $format = 'g:i A';
						if ($item['time_format'] == '3') $format = 'H:i';
					}

					if (!empty($item['time_icon']) && $item['time_icon'] == 'custom' && isset($item['time_icon_pack']) && !empty($item['time_icon_' . str_replace("-", "", $item['time_icon_pack'])])) {
						wp_enqueue_style('icons-' . $item['time_icon_pack']);
						$time_output .= '<div class="icon">'.thegem_build_icon($item['time_icon_pack'], $item['time_icon_' . str_replace("-", "", $item['time_icon_pack'])]).'</div>';
					} else if (!empty($item['time_icon']) && $item['time_icon'] == 'default') {
						$time_output .= '<div class="icon"><i class="icon-default"></i></div>';
					}

					if (!empty($item['time_label'])) {
						$time_output .= '<div class="label-before">'.esc_html($item['time_label']).'</div>';
					}

					$time_output .= '<div class="time">'.get_the_time($format, $single_post).'</div>';

					if (!empty($time_output)) {
						echo '<div class="post-info-item post-info-item-'.$item['type'].' '.$text_styled.'">'.$time_output.'</div>';
					}

					break;
				case 'comments':
					if ( !comments_open()) break;

					$comments_output = $comments_label = $comments_icon = '';
					if (!empty($item['comments_icon']) && $item['comments_icon'] == 'custom' && isset($item['comments_icon_pack']) && !empty($item['comments_icon_' . str_replace("-", "", $item['comments_icon_pack'])])) {
						wp_enqueue_style('icons-' . $item['comments_icon_pack']);
						$comments_icon = '<div class="icon">'.thegem_build_icon($item['comments_icon_pack'], $item['comments_icon_' . str_replace("-", "", $item['comments_icon_pack'])]).'</div>';
					} else if (!empty($item['comments_icon']) && $item['comments_icon'] == 'default') {
						$comments_icon = '<div class="icon"><i class="icon-default"></i></div>';
					}

					$comments_label = '<div class="count">'.$single_post->comment_count.'</div>';

					if (!empty($item['comments_label'])) {
						$comments_label .= '<div class="label-after">'.esc_html($item['comments_label']).'</div>';
					}

					if (!empty($item['comments_link'])) {
						$comments_output = $comments_icon.' '.'<a class="comments-link" href="'.get_permalink( $single_post ).'#respond">'.$comments_label.'</a>';
					} else{
						$comments_output = $comments_icon.' '.$comments_label;
					}

					if (!empty($comments_output)) {
						echo '<div class="post-info-item post-info-item-'.$item['type'].' '.$text_styled.'">'.$comments_output.'</div>';
					}

					break;
				case 'likes':
					if ( !function_exists('zilla_likes')) break;

					$likes_css = '';
					if (!empty($item['likes_icon']) && $item['likes_icon'] == 'custom' && isset($item['likes_icon_pack']) && !empty($item['likes_icon_' . str_replace("-", "", $item['likes_icon_pack'])])) {
						wp_enqueue_style('icons-' . $item['likes_icon_pack']);

						$cmz = '.thegem-te-post-info.'.$uniqid;
						if ($item['likes_icon_pack'] == 'elegant' && $item['likes_icon_elegant']) {
							$likes_css .= $cmz.' .zilla-likes:before {content: "\\'.$item['likes_icon_elegant'].'"; font-family: "ElegantIcons";}';
						}
						if ($item['likes_icon_pack'] == 'material' && $item['likes_icon_material']) {
							$likes_css .= $cmz.' .zilla-likes:before {content: "\\'.$item['likes_icon_material'].'"; font-family: "MaterialDesignIcons";}';
						}
						if ($item['likes_icon_pack'] == 'fontawesome' && $item['likes_icon_fontawesome']) {
							$likes_css .= $cmz.' .zilla-likes:before {content: "\\'.$item['likes_icon_fontawesome'].'"; font-family: "FontAwesome";}';
						}
						if ($item['likes_icon_pack'] == 'thegemdemo' && $item['likes_icon_thegemdemo']) {
							$likes_css .= $cmz.' .zilla-likes:before {content: "\\'.$item['likes_icon_thegemdemo'].'"; font-family: "TheGemDemoIcons";}';
						}
						if ($item['likes_icon_pack'] == 'userpack' && $item['likes_icon_userpack']) {
							$likes_css .= $cmz.' .zilla-likes:before {content: "\\'.$item['likes_icon_userpack'].'"; font-family: "Userpack";}';
						}
						if($item['likes_icon_pack'] == 'thegem-header' && $item['likes_icon_thegem_header']) {
							$likes_css .= $cmz.' .zilla-likes:before {content: "\\'.$item['likes_icon_thegem_header'].'"; font-family: "TheGem Header";}';
						}
					}

					ob_start();
					zilla_likes();
					$likes_output = '<div class="likes">'.ob_get_clean().'</div>';

					if (!empty($item['likes_label'])) {
						$likes_output .= '<div class="label-after">'.esc_html($item['likes_label']).'</div>';
					}

					$likes_css_output = '';
					if(!empty($likes_css)) {
						$likes_css_output = '<style>'.$likes_css.'</style>';
					}

					if (!empty($likes_output)) {
						echo '<div class="post-info-item post-info-item-'.$item['type'].' '.$text_styled.'">'.$likes_output.$likes_css_output.'</div>';
					}

					break;
			}
		}

		//Custom Styles
		$customize = '.thegem-te-post-info.'.$uniqid;
		$custom_css = '';
		$resolution = array('desktop', 'tablet', 'mobile');
		$resolution_responsive = array('tablet', 'mobile');
		$indents = array('padding', 'margin');
		$directions = array('top', 'bottom', 'left', 'right');
		$unit = 'px';

		// List Style
		if (!empty($params['list_divider_color'])) {
			$custom_css .= $customize.' .post-info-item:not(:last-child):after {background-color: '.$params['list_divider_color'].';}';
			$custom_css .= $customize.' .post-info-item-cats .separator {background-color: '.$params['list_divider_color'].';}';
		}
		foreach ($resolution as $res) {
			if (!empty($params['list_spacing_'.$res]) || strcmp($params['list_spacing_'.$res], '0') === 0) {
				$result = str_replace(' ', '', $params['list_spacing_'.$res]);
				if ($res == 'desktop') {
					if ($params['layout'] == 'horizontal') {
						$custom_css .= $customize.' .post-info-item:not(.post-info-item-cats-list) {margin-right:'.$result.$unit.'; padding-right:'.$result.$unit.';}';
						$custom_css .= $customize.' .post-info-item.post-info-item-cats-list {margin-right:'.$result.$unit.';}';
						$custom_css .= $customize.' .post-info-item-cats .separator {margin: 0 '.($result / 2).$unit.';}';
                    } else{
						$custom_css .= $customize.' .post-info-item {margin-top:'.$result.$unit.';}';
                    }
				} else {
					$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
					if ($params['layout'] == 'horizontal') {
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' .post-info-item:not(.post-info-item-cats-list) {margin-right:'.$result.$unit.'; padding-right:'.$result.$unit.';}}';
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' .post-info-item.post-info-item-cats-list {margin-right:'.$result.$unit.';}}';
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' .post-info-item-cats .separator {margin: 0 '.($result / 2).$unit.';}}';
                    } else{
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' .post-info-item {margin-top:'.$result.$unit.';}}';
                    }
				}
			}
		}

		// Icon Style
		if (!empty($params['icon_size'])) {
			$custom_css .= $customize.' .post-info-item .icon i {font-size: '.$params['icon_size'].'px; line-height: '.$params['icon_size'].'px;}';
			$custom_css .= $customize.' .post-info-item .zilla-likes:before {font-size: '.$params['icon_size'].'px; line-height: '.$params['icon_size'].'px;}';
		}
		if (!empty($params['icon_spacing'])) {
			$custom_css .= $customize.' .post-info-item .icon {margin-right: '.$params['icon_spacing'].'px;}';
			$custom_css .= $customize.' .post-info-item .zilla-likes:before {margin-right: '.$params['icon_spacing'].'px;}';
			$custom_css .= $customize.' .post-info-item .avatar {margin-right: '.$params['icon_spacing'].'px;}';
		}
		if (!empty($params['icon_color'])) {
			$custom_css .= $customize.' .post-info-item .icon {color: '.$params['icon_color'].';}';
			$custom_css .= $customize.' .post-info-item .zilla-likes {color: '.$params['icon_color'].' !important;}';
		}

		// Text Style
		if (!empty($params['text_letter_spacing']) || $params['text_letter_spacing'] == '0') {
			$custom_css .= $customize.' .post-info-item {letter-spacing: ' . $params['text_letter_spacing'] . 'px;}';
		}
		if (!empty($params['text_transform'])) {
			$custom_css .= $customize.' .post-info-item {text-transform: ' . $params['text_transform'] . ';}';
		}
		if (!empty($params['text_color'])) {
			$custom_css .= $customize.' .post-info-item {color: ' . $params['text_color'] . ';}';
			$custom_css .= $customize.' .post-info-item a {color: ' . $params['text_color'] . ';}';
		}
		if (!empty($params['text_color_hover'])) {
			$custom_css .= $customize.' .post-info-item a:hover {color: ' . $params['text_color_hover'] . ';}';
			$custom_css .= $customize.' .post-info-item a .icon {transition: color 0.3s;}';
			$custom_css .= $customize.' .post-info-item a:hover .icon {color: ' . $params['text_color_hover'] . ';}';
			$custom_css .= $customize.' .post-info-item .zilla-likes:hover {color: ' . $params['text_color_hover'] . ' !important;}';
		}

		// Terms default Style
		if (empty($params['cats_border'])) {
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats a {border: 0 !important;}';
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats span.readonly {border: 0 !important;}';
		}
		if (!empty($params['cats_border_width']) || $params['cats_border_width'] == '0') {
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats a {border-width: ' . $params['cats_border_width'] . 'px;}';
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats span.readonly {border-width: ' . $params['cats_border_width'] . 'px;}';
		}
		if (!empty($params['cats_border_radius']) || $params['cats_border_radius'] == '0') {
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats a {border-radius: ' . $params['cats_border_radius'] . 'px;}';
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats span.readonly {border-radius: ' . $params['cats_border_radius'] . 'px;}';
		}
		if (!empty($params['cats_text_color'])) {
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats a {color: ' . $params['cats_text_color'] . ';}';
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats span.readonly {color: ' . $params['cats_text_color'] . ';}';
		}
		if (!empty($params['cats_text_color_hover'])) {
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats a:not(.readonly):hover {color: ' . $params['cats_text_color_hover'] . ';}';
		}
		if (!empty($params['cats_background_color'])) {
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats a {background-color: ' . $params['cats_background_color'] . ';}';
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats span.readonly {background-color: ' . $params['cats_background_color'] . ';}';
		}
		if (!empty($params['cats_background_color_hover'])) {
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats a:not(.readonly):hover {background-color: ' . $params['cats_background_color_hover'] . ';}';
		}
		if (!empty($params['cats_border_color'])) {
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats a {border-color: ' . $params['cats_border_color'] . ';}';
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats span.readonly {border-color: ' . $params['cats_border_color'] . ';}';
		}
		if (!empty($params['cats_border_color_hover'])) {
			$custom_css .= $customize.'.post-info--modern .post-info-item-cats a:not(.readonly):hover {border-color: ' . $params['cats_border_color_hover'] . ';}';
		}

		// Terms list Style
		if (empty($params['terms_list_border'])) {
			$custom_css .= $customize.' .post-info-item-cats-list {border: 0 !important;}';
		}
		if (!empty($params['terms_list_border_width']) || $params['terms_list_border_width'] == '0') {
			$custom_css .= $customize.' .post-info-item-cats-list {border-width: ' . $params['terms_list_border_width'] . 'px;}';
		}
		if (!empty($params['terms_list_border_radius']) || $params['terms_list_border_radius'] == '0') {
			$custom_css .= $customize.' .post-info-item-cats-list {border-radius: ' . $params['terms_list_border_radius'] . 'px;}';
		}
		if (!empty($params['terms_list_text_color'])) {
			$custom_css .= $customize.' .post-info-item-cats-list {color: ' . $params['terms_list_text_color'] . ';}';
			$custom_css .= $customize.' .post-info-item-cats-list a {color: ' . $params['terms_list_text_color'] . ';}';
		}
		if (!empty($params['terms_list_text_color_hover'])) {
			$custom_css .= $customize.' .post-info-item-cats-list a:hover {color: ' . $params['terms_list_text_color_hover'] . ';}';
		}
		if (!empty($params['terms_list_background_color'])) {
			$custom_css .= $customize.' .post-info-item-cats-list {background-color: ' . $params['terms_list_background_color'] . ';}';
		}
		if (!empty($params['terms_list_border_color'])) {
			$custom_css .= $customize.' .post-info-item-cats-list {border-color: ' . $params['terms_list_border_color'] . ';}';
		}

		// Container Layout Responsive
		foreach ($resolution_responsive as $res) {
			foreach ($indents as $ind) {
				foreach ($directions as $dir) {
					if ( !empty($params['container_layout_'.$res.'_'.$ind.'_'.$dir]) || strcmp($params['container_layout_'.$res.'_'.$ind.'_'.$dir], '0') === 0) {
						$result = str_replace(' ', '', $params['container_layout_'.$res.'_'.$ind.'_'.$dir]);
						$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
						$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' {'.$ind.'-'.$dir.':'.$result.$unit.' !important;}}';
					}
				}
			}
		}

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		if (empty($return_html)) {
			return thegem_templates_close_single_post($this->get_name(), $this->shortcode_settings(), $return_html);
		}

		ob_start()

		?>
			<div <?php if (!empty($params['element_id'])): ?>id="<?=esc_attr($params['element_id']); ?>"<?php endif;?> class="thegem-te-post-info <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
				<div class="post-info"><?= $return_html ?></div>
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

		return thegem_templates_close_single_post($this->get_name(), $this->shortcode_settings(), $return_html);
	}

	public function set_layout_params() {
		$result = array();
		$group = __('General', 'thegem');

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Skin', 'thegem'),
			'param_name' => 'skin',
			'value' => array_merge(array(
					__('Classic', 'thegem') => 'classic',
					__('Modern', 'thegem') => 'modern',
				)
			),
			'std' => 'classic',
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
                        'heading' => __('Type', 'thegem'),
                        'param_name' => 'type',
                        'value' => array(
	                        __('Author', 'thegem') => 'author',
                            __('Terms', 'thegem') => 'terms',
                            __('Date', 'thegem') => 'date',
                            __('Time', 'thegem') => 'time',
                            __('Comments', 'thegem') => 'comments',
                            __('Likes', 'thegem') => 'likes',
                        ),
	                    'std' => 'author',
                    ),
                ),

                // Terms
				array(
					array(
						'type' => 'dropdown',
						'heading' => __('Select Taxonomy', 'thegem'),
						'param_name' => 'terms_taxonomy',
						'value' => $this->get_taxonomy_list(),
						'std' => $this->get_default_taxonomy(),
						'edit_field_class' => 'vc_column vc_col-sm-12',
						'dependency' => array(
							'element' => 'type',
							'value' => 'terms'
						),
						'group' => $group
					),
					array(
						'type' => 'checkbox',
						'heading' => __('Truncate Terms', 'thegem'),
						'param_name' => 'terms_taxonomy_truncate',
						'value' => array(__('Yes', 'thegem') => '1'),
						'std' => '',
						'dependency' => array(
							'element' => 'type',
							'value' => 'terms'
						),
						'edit_field_class' => 'vc_column vc_col-sm-12',
					),
					array(
						'type' => 'textfield',
						'heading' => __('Number of terms to show', 'thegem'),
						'param_name' => 'terms_taxonomy_truncate_number',
						'dependency' => array(
							'element' => 'terms_taxonomy_truncate',
							'value' => '1'
						),
						'std' => '',
						'edit_field_class' => 'vc_column vc_col-sm-12',
					),
	                array(
		                'type' => 'checkbox',
		                'heading' => __('Link', 'thegem'),
		                'param_name' => 'terms_link',
		                'value' => array(__('Yes', 'thegem') => '1'),
		                'std' => '1',
		                'dependency' => array(
			                'element' => 'type',
			                'value' => 'terms'
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-12',
	                ),
				),

                // Author
                array(
	                array(
		                'type' => 'textfield',
		                'heading' => __('Label', 'thegem'),
		                'param_name' => 'author_label',
		                'dependency' => array(
			                'element' => 'type',
			                'value' => 'author'
		                ),
		                'std' => 'By',
		                'edit_field_class' => 'vc_column vc_col-sm-12',
	                ),
	                array(
		                'type' => 'checkbox',
		                'heading' => __('Avatar', 'thegem'),
		                'param_name' => 'author_avatar',
		                'value' => array(__('Yes', 'thegem') => '1'),
		                'std' => '1',
		                'dependency' => array(
			                'element' => 'type',
			                'value' => 'author'
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-4',
	                ),
	                array(
		                'type' => 'textfield',
		                'heading' => __('Size', 'thegem'),
		                'param_name' => 'author_avatar_size',
		                'dependency' => array(
			                'element' => 'author_avatar',
			                'value' => '1'
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-8',
	                ),
	                array(
		                'type' => 'checkbox',
		                'heading' => __('Link', 'thegem'),
		                'param_name' => 'author_link',
		                'value' => array(__('Yes', 'thegem') => '1'),
		                'std' => $this->is_loop_builder_template() ? '1' : '0',
		                'dependency' => array(
			                'element' => 'type',
			                'value' => 'author'
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-12',
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
		                'std' => $this->is_loop_builder_template() ? '' : 'default',
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

                // Time
                array(
	                array(
		                'type' => 'textfield',
		                'heading' => __('Label', 'thegem'),
		                'param_name' => 'time_label',
		                'dependency' => array(
			                'element' => 'type',
			                'value' => 'time'
		                ),
		                'std' => '',
		                'edit_field_class' => 'vc_column vc_col-sm-12',
	                ),
	                array(
		                'type' => 'dropdown',
		                'heading' => __('Time Format', 'thegem'),
		                'param_name' => 'time_format',
		                'value' => array_merge(
			                array(
				                __('Default', 'thegem') => '',
				                __('3:31 pm (g:i a)', 'thegem') => '1',
				                __('3:31 PM (g:i A)', 'thegem') => '2',
				                __('15:31 (H:i)', 'thegem') => '3',
			                )
		                ),
		                'std' => '',
		                'dependency' => array(
			                'element' => 'type',
			                'value' => 'time'
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-12',
	                ),
	                array(
		                'type' => 'dropdown',
		                'heading' => __('Icon', 'thegem'),
		                'param_name' => 'time_icon',
		                'value' => array(
			                __('None', 'thegem') => '',
			                __('Default', 'thegem') => 'default',
			                __('Custom', 'thegem') => 'custom',
		                ),
		                'std' => $this->is_loop_builder_template() ? '' : 'default',
		                'dependency' => array(
			                'element' => 'type',
			                'value' => 'time'
		                ),
	                ),
	                array(
		                'type' => 'dropdown',
		                'heading' => __('Icon pack', 'thegem'),
		                'param_name' => 'time_icon_pack',
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
			                'element' => 'time_icon',
			                'value' => 'custom'
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-6',
	                ),
	                array(
		                'type' => 'thegem_icon',
		                'heading' => __('Icon', 'thegem'),
		                'param_name' => 'time_icon_elegant',
		                'icon_pack' => 'elegant',
		                'dependency' => array(
			                'element' => 'time_icon_pack',
			                'value' => array('elegant')
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-6',
	                ),
	                array(
		                'type' => 'thegem_icon',
		                'heading' => __('Icon', 'thegem'),
		                'param_name' => 'time_icon_material',
		                'icon_pack' => 'material',
		                'dependency' => array(
			                'element' => 'time_icon_pack',
			                'value' => array('material')
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-6',
	                ),
	                array(
		                'type' => 'thegem_icon',
		                'heading' => __('Icon', 'thegem'),
		                'param_name' => 'time_icon_fontawesome',
		                'icon_pack' => 'fontawesome',
		                'dependency' => array(
			                'element' => 'time_icon_pack',
			                'value' => array('fontawesome')
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-6',
	                ),
	                array(
		                'type' => 'thegem_icon',
		                'heading' => __('Icon', 'thegem'),
		                'param_name' => 'time_icon_thegemdemo',
		                'icon_pack' => 'thegemdemo',
		                'dependency' => array(
			                'element' => 'time_icon_pack',
			                'value' => array('thegemdemo')
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-6',
	                ),
	                array(
		                'type' => 'thegem_icon',
		                'heading' => __('Icon', 'thegem'),
		                'param_name' => 'time_icon_thegemheader',
		                'icon_pack' => 'thegem-header',
		                'dependency' => array(
			                'element' => 'time_icon_pack',
			                'value' => array('thegem-header')
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-6',
	                ),
                ),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'time_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'time_icon_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
				)),

                // Comments
                array(
	                array(
		                'type' => 'textfield',
		                'heading' => __('Label', 'thegem'),
		                'param_name' => 'comments_label',
		                'dependency' => array(
			                'element' => 'type',
			                'value' => 'comments'
		                ),
		                'std' => '',
		                'edit_field_class' => 'vc_column vc_col-sm-12',
	                ),
	                array(
		                'type' => 'dropdown',
		                'heading' => __('Icon', 'thegem'),
		                'param_name' => 'comments_icon',
		                'value' => array(
			                __('None', 'thegem') => '',
			                __('Default', 'thegem') => 'default',
			                __('Custom', 'thegem') => 'custom',
		                ),
		                'std' => 'default',
		                'dependency' => array(
			                'element' => 'type',
			                'value' => 'comments'
		                ),
	                ),
	                array(
		                'type' => 'dropdown',
		                'heading' => __('Icon pack', 'thegem'),
		                'param_name' => 'comments_icon_pack',
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
			                'element' => 'comments_icon',
			                'value' => 'custom'
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-6',
	                ),
	                array(
		                'type' => 'thegem_icon',
		                'heading' => __('Icon', 'thegem'),
		                'param_name' => 'comments_icon_elegant',
		                'icon_pack' => 'elegant',
		                'dependency' => array(
			                'element' => 'comments_icon_pack',
			                'value' => array('elegant')
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-6',
	                ),
	                array(
		                'type' => 'thegem_icon',
		                'heading' => __('Icon', 'thegem'),
		                'param_name' => 'comments_icon_material',
		                'icon_pack' => 'material',
		                'dependency' => array(
			                'element' => 'comments_icon_pack',
			                'value' => array('material')
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-6',
	                ),
	                array(
		                'type' => 'thegem_icon',
		                'heading' => __('Icon', 'thegem'),
		                'param_name' => 'comments_icon_fontawesome',
		                'icon_pack' => 'fontawesome',
		                'dependency' => array(
			                'element' => 'comments_icon_pack',
			                'value' => array('fontawesome')
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-6',
	                ),
	                array(
		                'type' => 'thegem_icon',
		                'heading' => __('Icon', 'thegem'),
		                'param_name' => 'comments_icon_thegemdemo',
		                'icon_pack' => 'thegemdemo',
		                'dependency' => array(
			                'element' => 'comments_icon_pack',
			                'value' => array('thegemdemo')
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-6',
	                ),
	                array(
		                'type' => 'thegem_icon',
		                'heading' => __('Icon', 'thegem'),
		                'param_name' => 'comments_icon_thegemheader',
		                'icon_pack' => 'thegem-header',
		                'dependency' => array(
			                'element' => 'comments_icon_pack',
			                'value' => array('thegem-header')
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-6',
	                ),
                ),
				thegem_userpack_to_shortcode(array(
					array(
						'type' => 'thegem_icon',
						'heading' => __('Icon', 'thegem'),
						'param_name' => 'comments_icon_userpack',
						'icon_pack' => 'userpack',
						'dependency' => array(
							'element' => 'comments_icon_pack',
							'value' => array('userpack')
						),
						'edit_field_class' => 'vc_column vc_col-sm-6',
					),
				)),
                array(
	                array(
		                'type' => 'checkbox',
		                'heading' => __('Link', 'thegem'),
		                'param_name' => 'comments_link',
		                'value' => array(__('Yes', 'thegem') => '1'),
		                'std' => '0',
		                'dependency' => array(
			                'element' => 'type',
			                'value' => 'comments'
		                ),
		                'edit_field_class' => 'vc_column vc_col-sm-12',
	                ),
                ),

                // Likes
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
            ),
			'group' => $group
		);

		return $result;
	}

	public function set_style_params() {
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
				'value' => array('classic')
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
				'heading' => __(''.ucfirst($res).'', 'thegem'),
				'param_name' => 'list_spacing_'.$res,
				'value' => '',
				'edit_field_class' => 'vc_column vc_col-sm-4',
				'group' => $group
			);
		}

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
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Spacing', 'thegem'),
			'param_name' => 'icon_spacing',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
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
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Text', 'thegem'),
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
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'text_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color on Hover', 'thegem'),
			'param_name' => 'text_color_hover',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		// Terms default style
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Terms', 'thegem'),
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

		// Terms list style
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Display as', 'thegem'),
			'param_name' => 'terms_taxonomy_style',
			'value' => array_merge(array(
					__('Simple List', 'thegem') => 'default',
					__('Label', 'thegem') => 'list',
				)
			),
			'std' => 'default',
			'dependency' => array(
				'element' => 'skin',
				'value' => 'classic'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('List Delimiter', 'thegem'),
			'param_name' => 'terms_taxonomy_delimiter',
			'value' => ',',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'dependency' => array(
				'element' => 'terms_taxonomy_style',
				'value' => 'list'
			),
			'group' => $group
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Border', 'thegem'),
			'param_name' => 'terms_list_border',
			'value' => array_merge(array(
					__('None', 'thegem') => '',
					__('Solid', 'thegem') => 'solid',
				)
			),
			'std' => '',
			'dependency' => array(
				'element' => 'terms_taxonomy_style',
				'value' => 'list'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Width', 'thegem'),
			'param_name' => 'terms_list_border_width',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'terms_list_border',
				'value' => array('solid', 'double', 'dotted', 'dashed', 'groove')
			),
			'group' => $group
		);
		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Radius', 'thegem'),
			'param_name' => 'terms_list_border_radius',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'terms_list_border',
				'value' => array('solid', 'double', 'dotted', 'dashed', 'groove')
			),
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color', 'thegem'),
			'param_name' => 'terms_list_text_color',
			'dependency' => array(
				'element' => 'terms_taxonomy_style',
				'value' => 'list'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Text Color on Hover', 'thegem'),
			'param_name' => 'terms_list_text_color_hover',
			'dependency' => array(
				'element' => 'terms_taxonomy_style',
				'value' => 'list'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color', 'thegem'),
			'param_name' => 'terms_list_background_color',
			'dependency' => array(
				'element' => 'terms_taxonomy_style',
				'value' => 'list'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color', 'thegem'),
			'param_name' => 'terms_list_border_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'terms_list_border',
				'value' => array('solid', 'double', 'dotted', 'dashed', 'groove')
			),
			'group' => $group
		);

		return $result;
    }

	public function set_design_options_params() {
		$result = array();
		$group = __('Design Options', 'thegem');

		$result[] = array(
			'type' => 'css_editor',
			'heading' => __('CSS box', 'thegem'),
			'param_name' => 'container_layout',
			'group' => $group
		);

		return $result;
	}

	public function extends_responsive_options_params() {
		$result = array();
		$group = __('Responsive Options', 'thegem');
		$resolution = array('tablet', 'mobile');
		$indents = array('padding', 'margin');
		$directions = array('top', 'bottom', 'left', 'right');

		foreach ($resolution as $res) {
			$result[] = array(
				'type' => 'thegem_delimeter_heading',
				'heading' => __(ucfirst($res), 'thegem'),
				'param_name' => $res.'_container_layout_heading',
				'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
				'group' => __($group, 'thegem')
			);

			foreach ($indents as $ind) {
				$result[] = array(
					'type' => 'thegem_delimeter_heading_two_level',
					'heading' => __($ind, 'thegem'),
					'param_name' => $res.'_'.$ind.'_container_layout_sub_heading',
					'edit_field_class' => 'thegem-param-delimeter-heading-two-level margin-top vc_column vc_col-sm-12 capitalize',
					'group' => __($group, 'thegem')
				);
				foreach ($directions as $dir) {
					$result[] = array(
						'type' => 'textfield',
						'heading' => __(ucfirst($dir), 'thegem'),
						'param_name' => 'container_layout_' . $res.'_'.$ind.'_'.$dir,
						'value' => '',
						'edit_field_class' => 'vc_column vc_col-sm-3 capitalize',
						'group' => __($group, 'thegem')
					);
				}
			}

			$result[] = array(
				'type' => 'thegem_delimeter_heading_two_level',
				'heading' => __('', 'thegem'),
				'param_name' => $res.'_description_layout_sub_heading',
				'edit_field_class' => 'vc_column vc_col-sm-12 no-top-padding',
				'description' => __('Note: px units are used by default. For % units add values with %, eg. 10%', 'thegem'),
				'group' => __($group, 'thegem')
			);
		}

		return $result;
	}

	public function shortcode_settings() {
		return array(
			'name' => __('Post Meta', 'thegem'),
			'base' => 'thegem_te_post_info',
			'icon' => 'thegem-icon-wpb-ui-element-post-info',
			'category' => $this->is_template() ? __('Single Post Builder', 'thegem') : __('Single post', 'thegem'),
			'description' => __('Post Meta (Single Post Builder)', 'thegem'),
			'params' => array_merge(

			    /* General - Layout */
				$this->set_layout_params(),

                /* General - Styles */
				$this->set_style_params(),

				/* Extra Options */
				thegem_set_elements_extra_options(),

				// Design Options
				$this->set_design_options_params(),

				/* Responsive Options */
				thegem_set_elements_responsive_options(),
				$this->extends_responsive_options_params()
			),
		);
	}
}

$templates_elements['thegem_te_post_info'] = new TheGem_Template_Element_Post_Info();
$templates_elements['thegem_te_post_info']->init();
