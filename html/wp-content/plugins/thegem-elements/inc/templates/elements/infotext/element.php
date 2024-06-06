<?php

class TheGem_Template_Element_Infotext extends TheGem_Template_Element {

	public function __construct() {
		if ( !defined('THEGEM_TE_INFOTEXT_DIR' )) {
			define('THEGEM_TE_INFOTEXT_DIR', rtrim(__DIR__, ' /\\'));
		}

		if ( !defined('THEGEM_TE_INFOTEXT_URL') ) {
			define('THEGEM_TE_INFOTEXT_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}

		wp_register_style('thegem-te-infotext', THEGEM_TE_INFOTEXT_URL . '/css/infotext.css');
	}

	public function get_name() {
		return 'thegem_te_infotext';
	}

	public function head_scripts($attr) {
		$attr = shortcode_atts(array('pack' => 'thegem-header'), $attr, 'thegem_te_infotext');
		wp_enqueue_style('icons-'.$attr['pack']);
		wp_enqueue_style('thegem-te-infotext');
	}

	public function front_editor_scripts($attr) {
		wp_enqueue_style('thegem-te-infotext');
	}

	public function shortcode_output($atts, $content = '') {
		$params = shortcode_atts(array_merge(array(
			//General
			'preset' => 'tiny',
			'infotext_alignment' => 'left',
			'element_id' => '',
			'extra_class' => '',
			'element_link' => '',
			'icon_position_to_text' => 'left',
			'icon_horizontal_align' => 'left',
			'icon_vertical_align' => 'center',
			//Icon
			'icon_image' => 'icon',
			'image' => '',
			'pack' => 'thegem-header',
			'icon_elegant' => '',
			'icon_material' => '',
			'icon_fontawesome' => '',
			'icon_thegemdemo' => '',
			'icon_userpack' => '',
			'icon_thegem_header' => 'e622',
			'shape' => 'simple',
			'style' => '',
			'color' => '',
			'hover_color' => '',
			'gradient_color_style' => 'linear',
			'color_2' => '',
			'hover_color_2' => '',
			'gradient_radial_color_position' => 'at top',
			'gradient_color_angle' => 'to bottom',
			'gradient_color_cusotom_deg' => '',
			'background_color' => '',
			'hover_background_color' => '',
			'border_color' => '',
			'hover_border_color' => '',
			'gradient_backgound' => '',
			'gradient_backgound_from' => '',
			'gradient_backgound_to' => '',
			'gradient_backgound_style' => 'linear',
			'gradient_radial_backgound_position' => 'at top',
			'gradient_backgound_angle' => 'to bottom',
			'gradient_backgound_cusotom_deg' => '',
			'size' => 'custom',
			'icon_size' => '16',
			'link' => '',
			'icon_desktop_spacing_top' => '-2',
			'icon_desktop_spacing_bottom' => '',
			'icon_desktop_spacing_left' => '',
			'icon_desktop_spacing_right' => '8',
			'icon_tablet_spacing_top' => '',
			'icon_tablet_spacing_bottom' => '',
			'icon_tablet_spacing_left' => '',
			'icon_tablet_spacing_right' => '',
			'icon_mobile_spacing_top' => '',
			'icon_mobile_spacing_bottom' => '',
			'icon_mobile_spacing_left' => '',
			'icon_mobile_spacing_right' => '',
			//Title
			'title' => '',
			'title_text_style' => 'title-default',
			'title_font_weight' => '',
			'title_font_style' => '',
			'title_text_align' => '',
			'title_width' => '',
			'title_text_color' => '',
			'title_text_color_hover' => '',
			'title_custom_font_options' => '',
			'title_font_size' => '',
			'title_line_height' => '',
			'title_letter_spacing' => '',
			'title_text_transform' => 'none',
			'title_responsive_font_options' => '',
			'title_tablet_font_size' => '',
			'title_tablet_line_height' => '',
			'title_tablet_letter_spacing' => '',
			'title_mobile_font_size' => '',
			'title_mobile_line_height' => '',
			'title_mobile_letter_spacing' => '',
			'title_google_font_options' => '',
			'title_google_font' => '',
			'title_desktop_spacing_top' => '',
			'title_desktop_spacing_bottom' => '',
			'title_tablet_spacing_top' => '',
			'title_tablet_spacing_bottom' => '',
			'title_mobile_spacing_top' => '',
			'title_mobile_spacing_bottom' => '',
			//Subtitle
			'subtitle' => '',
			'subtitle_text_style' => 'styled-subtitle',
			'subtitle_font_weight' => '',
			'subtitle_font_style' => '',
			'subtitle_text_align' => '',
			'subtitle_width' => '',
			'subtitle_text_color' => '',
			'subtitle_text_color_hover' => '',
			'subtitle_custom_font_options' => '',
			'subtitle_font_size' => '',
			'subtitle_line_height' => '',
			'subtitle_letter_spacing' => '',
			'subtitle_text_transform' => 'none',
			'subtitle_responsive_font_options' => '',
			'subtitle_tablet_font_size' => '',
			'subtitle_tablet_line_height' => '',
			'subtitle_tablet_letter_spacing' => '',
			'subtitle_mobile_font_size' => '',
			'subtitle_mobile_line_height' => '',
			'subtitle_mobile_letter_spacing' => '',
			'subtitle_google_font_options' => '',
			'subtitle_google_font' => '',
			'subtitle_desktop_spacing_top' => '',
			'subtitle_desktop_spacing_bottom' => '',
			'subtitle_tablet_spacing_top' => '',
			'subtitle_tablet_spacing_bottom' => '',
			'subtitle_mobile_spacing_top' => '',
			'subtitle_mobile_spacing_bottom' => '',
			//Description
			'description' => '+1 916-85-2235',
			'description_text_style' => 'text-body-tiny',
			'description_font_weight' => '',
			'description_font_style' => '',
			'description_text_align' => '',
			'description_width' => '',
			'description_text_color' => '',
			'description_text_color_hover' => '',
			'description_custom_font_options' => '',
			'description_font_size' => '',
			'description_line_height' => '',
			'description_letter_spacing' => '',
			'description_text_transform' => 'none',
			'description_responsive_font_options' => '',
			'description_tablet_font_size' => '',
			'description_tablet_line_height' => '',
			'description_tablet_letter_spacing' => '',
			'description_mobile_font_size' => '',
			'description_mobile_line_height' => '',
			'description_mobile_letter_spacing' => '',
			'description_google_font_options' => '',
			'description_google_font' => '',
			'description_desktop_spacing_top' => '',
			'description_desktop_spacing_bottom' => '',
			'description_tablet_spacing_top' => '',
			'description_tablet_spacing_bottom' => '',
			'description_mobile_spacing_top' => '',
			'description_mobile_spacing_bottom' => '',
		), thegem_templates_design_options_extract(), thegem_templates_extra_options_extract()), $atts, 'thegem_te_infotext');

		$return_html = $custom_css = $output = $link_atts = $class = $icon = '';
		$css_style_icon_1_hover = $css_style_icon_2_hover = $css_style_icon_3_hover = $css_style_icon_background_hover = $css_style_icon_hover = '';
		$uniqid = uniqid('thegem-custom-').rand(1,9999);

		// Icon customize
		if ($params['icon_image'] == 'icon') {
			if ($params['pack'] == 'elegant' && empty($icon) && $params['icon_elegant']) {
				$icon = $params['icon_elegant'];
			}
			if ($params['pack'] == 'material' && empty($icon) && $params['icon_material']) {
				$icon = $params['icon_material'];
			}
			if ($params['pack'] == 'fontawesome' && empty($icon) && $params['icon_fontawesome']) {
				$icon = $params['icon_fontawesome'];
			}
			if ($params['pack'] == 'thegemdemo' && empty($icon) && $params['icon_thegemdemo']) {
				$icon = $params['icon_thegemdemo'];
			}
			if ($params['pack'] == 'userpack' && empty($icon) && $params['icon_userpack']) {
				$icon = $params['icon_userpack'];
			}
			if($params['pack'] =='thegem-header' && empty($icon) && $params['icon_thegem_header']) {
				$icon = $params['icon_thegem_header'];
			}
		}

		$params['shape'] = thegem_check_array_value(array('simple', 'circle', 'square'), $params['shape'], 'simple');
		$params['style'] = thegem_check_array_value(array('', 'angle-45deg-r', 'angle-45deg-l', 'angle-90deg', 'gradient'), $params['style'], '');
		$params['size'] = thegem_check_array_value(array('tiny', 'small', 'medium', 'large', 'xlarge', 'custom'), $params['size'], 'custom');
		$css_style_icon = '';
		$css_style_icon_background = '';
		$css_style_icon_1 = '';
		$css_style_icon_2 = '';
		$css_style_icon_3 = '';

		if ($params['gradient_backgound_angle'] == 'cusotom_deg') {
			$params['gradient_backgound_angle'] = $params['gradient_backgound_cusotom_deg'].'deg';
		}
		if($params['gradient_backgound'] and $params['gradient_backgound_style'] == 'linear') {
			$css_style_icon_background .= 'background: linear-gradient('.$params['gradient_backgound_angle'].', '.$params['gradient_backgound_from'].', '.$params['gradient_backgound_to'].');';
		}
		if($params['gradient_backgound'] and $params['gradient_backgound_style'] == 'radial') {
			$css_style_icon_background .= 'background: radial-gradient('.$params['gradient_radial_backgound_position'].', '.$params['gradient_backgound_from'].', '.$params['gradient_backgound_to'].');';
		}

		if($params['background_color'] && $params['shape'] != 'simple') {
			$css_style_icon_background .= 'background-color: '.$params['background_color'].';';
			if(!$params['border_color'] || !$params['style'] == 'gradient') {
				$css_style_icon .= 'border-color: '.$params['background_color'].';';
			}
		}

		if($params['hover_background_color'] && $params['shape'] != 'simple') {
			$css_style_icon_background_hover .= 'background-color: '.$params['hover_background_color'].';';
			if(!$params['hover_border_color'] || !$params['style'] == 'gradient') {
				$css_style_icon_hover .= 'border-color: '.$params['hover_background_color'].';';
			}
		}

		if($params['border_color'] && $params['shape'] != 'simple') {
			$css_style_icon .= 'border-color: '.$params['border_color'].';';
		}

		if($params['hover_border_color'] && $params['shape'] != 'simple') {
			$css_style_icon_hover .= 'border-color: '.$params['hover_border_color'].';';
		}

		if(!($params['background_color'] || $params['border_color'] || $params['gradient_backgound'])) {
			$class .= ' gem-simple-icon';
		}

		if($color = $params['color']) {
			$css_style_icon_1 = 'color: '.$color.';';
			if(($color_2 = $params['color_2']) && $params['style']) {
				$css_style_icon_2 = 'color: '.$color_2.';';
			} else {
				$css_style_icon_2 = 'color: '.$color.';';
			}

			if ($params['gradient_color_angle'] == 'cusotom_deg') {
				$gradient_color_angle = $params['gradient_color_cusotom_deg'].'deg';
			}

			if( $params['gradient_color_style'] == 'linear') {
				$css_style_icon_3 .= 'background: linear-gradient( '.$params['gradient_color_angle'].', '.$color.', '.$color_2.'); -webkit-text-fill-color: transparent; -webkit-background-clip: text;';
			}
			if ($params['gradient_color_style'] == 'radial') {
				$css_style_icon_3 .= 'background: radial-gradient( '.$params['gradient_radial_color_position'].', '.$color.', '.$color_2.'); -webkit-text-fill-color: transparent; -webkit-background-clip: text;';
			}
		}

		if($hover_color = $params['hover_color']) {
			$css_style_icon_1_hover = 'color: '.$hover_color.' !important;';
			if(($hover_color_2 = $params['hover_color_2']) && $params['style']) {
				$css_style_icon_2_hover = 'color: '.$hover_color_2.' !important;';
			} else {
				$css_style_icon_2_hover = 'color: '.$hover_color.' !important;';
			}
			if($params['gradient_color_style'] == 'linear') {
				$css_style_icon_3_hover .= 'background: linear-gradient( '.$params['gradient_color_angle'].', '.$hover_color.', '.$hover_color_2.'); -webkit-text-fill-color: transparent; -webkit-background-clip: text;';
			}
			if ($params['gradient_color_style'] == 'radial') {
				$css_style_icon_3_hover .= 'background: radial-gradient( '.$params['gradient_radial_color_position'].', '.$hover_color.', '.$hover_color_2.'); -webkit-text-fill-color: transparent; -webkit-background-clip: text;';
			}
		}

        if (!empty($icon)) {
	        if ($params['style'] != 'gradient') {
		        $output = '<span class="gem-icon-half-1"><span class="back-angle">&#x' . $icon . ';</span></span>'.
			        '<span class="gem-icon-half-2"><span class="back-angle">&#x' . $icon . ';</span></span>';
	        } else {
		        $output = '<span class="gem-icon-style-gradient"><span class="back-angle">&#x' . $icon . ';</span></span>';
	        }
        }

		if ($params['icon_image'] == 'image' && !empty($params['image'])) {
			$image_src = thegem_attachment_url($params['image']);
			$output = '<img class="img-responsive" src="'.$image_src.'">';
		}

		if ($params['icon_image'] == 'icon' && !empty($params['pack'])) {
			$class .= ' gem-icon-pack-'.$params['pack'];
		}
		if(!empty($params['size'])) {
			$class .= ' gem-icon-size-'.$params['size'];
		}
		if(!empty($params['style'])) {
			$class .= ' '.$params['style'];
		}
		if($params['shape'] != 'simple') {
			$class .= ' gem-icon-shape-'.$params['shape'];
		}

		// Text general customize
		$el_id = $el_class = $el_width = $link = $el_link = $el_link_target = $el_link_title = $el_link_rel = '';
		if (!empty($params['element_id'])){ $el_id = $params['element_id']; }
		if (!empty($params['element_class'])){ $el_class = $params['element_class']; }
		if (!empty($params['element_width'])){ $el_width = $params['element_width']; }
		if (!empty($params['element_link'])){
			$link = vc_build_link($params['element_link']);

			$el_link = !empty($link['url']) ? $link['url'] : '';
			$el_link_target = !empty($link['target']) ? $link['target'] : '_self';
			$el_link_title = !empty($link['title']) ? $link['title'] : '';
			$el_link_rel = !empty($link['rel']) ? $link['rel'] : '';
		}

		// Text default class
		$title_text_class = $subtitle_text_class = $description_text_class = '';
		$title_text_class .= !empty($params['title_text_style']) ? $params['title_text_style'] : '';
		$title_text_class .= !empty($params['title_font_weight']) ? ' '.$params['title_font_weight'] : '';
		$subtitle_text_class .= !empty($params['subtitle_text_style']) ? $params['subtitle_text_style'] : '';
		$subtitle_text_class .=  !empty($params['subtitle_font_weight']) ? ' '.$params['subtitle_font_weight'] : '';
		$description_text_class .= !empty($params['description_text_style']) ? $params['description_text_style'] : '';
		$description_text_class .= !empty($params['description_font_weight']) ? ' '.$params['description_font_weight'] : '';

		// Text default style
		$title_text_style = $subtitle_text_style = $description_text_style = '';
		$title_text_style .= !empty($params['title_font_style']) ? 'font-style:'.$params['title_font_style'].'; ' : '';
		$title_text_style .= !empty($params['title_text_align']) ? 'text-align:'.$params['title_text_align'].';' : '';
		$title_text_style .= !empty($params['title_width']) ? 'max-width:'.$params['title_width'].'px;' : '';
		$subtitle_text_style .= !empty($params['subtitle_font_style']) ? 'font-style:'.$params['subtitle_font_style'].'; ' : '';
		$subtitle_text_style .= !empty($params['subtitle_text_align']) ? 'text-align:'.$params['subtitle_text_align'].';' : '';
		$subtitle_text_style .= !empty($params['subtitle_width']) ? 'max-width:'.$params['subtitle_width'].'px;' : '';
		$description_text_style .= !empty($params['description_font_style']) ? 'font-style:'.$params['description_font_style'].'; ' : '';
		$description_text_style .= !empty($params['description_text_align']) ? 'text-align:'.$params['description_text_align'].';' : '';
		$description_text_style .= !empty($params['description_width']) ? 'max-width:'.$params['description_width'].'px;' : '';

		$el_class .= ' alignment-'.$params['infotext_alignment'];

		// Init Infotext
		ob_start(); ?>

		<div <?php if ($el_id): ?>id="<?=esc_attr($el_id); ?>"<?php endif;?> class="thegem-te-infotext <?= esc_attr($el_class); ?> <?= esc_attr($uniqid); ?>" <?=thegem_data_editor_attribute($uniqid . '-editor')?>>

			<?php if (!empty($el_link)): ?>
			<a href="<?=esc_url($el_link); ?>" target="<?=esc_attr($el_link_target); ?>" title="<?=esc_attr($el_link_title); ?>" rel="<?=esc_attr($el_link_rel); ?>"
			   class="thegem-te-infotext-wrap position--<?=$params['icon_position_to_text']?>
			   <?php if ($params['icon_position_to_text'] == 'top' || $params['icon_position_to_text'] == 'bottom'): ?>horizonal--<?=$params['icon_horizontal_align']?><?php endif;?>
			   <?php if ($params['icon_position_to_text'] == 'left' || $params['icon_position_to_text'] == 'right'): ?>vertical--<?=$params['icon_vertical_align']?><?php endif;?>"
			   <?php if ($el_width): ?>style="max-width: <?=esc_attr($el_width); ?>px;"<?php endif;?>>
			<?php else: ?>
			<div class="thegem-te-infotext-wrap position--<?=$params['icon_position_to_text']?>
			   <?php if ($params['icon_position_to_text'] == 'top' || $params['icon_position_to_text'] == 'bottom'): ?>horizonal--<?=$params['icon_horizontal_align']?><?php endif;?>
			   <?php if ($params['icon_position_to_text'] == 'left' || $params['icon_position_to_text'] == 'right'): ?>vertical--<?=$params['icon_vertical_align']?><?php endif;?>">
			<?php endif;?>
			
            <?php if (!empty($output)): ?>
                <div class="thegem-te-info-icon">
                    <div class="gem-icon <?= esc_attr($class); ?>">
                        <div class="gem-icon-inner"><?= $output ?></div>
                    </div>
                </div>
            <?php endif;?>
        
            <?php if (!empty($params['title']) || !empty($params['subtitle']) || !empty($params['description'])): ?>
                <div class="thegem-te-info-text">
                    <?php if (!empty($params['title'])): ?>
                        <div class="thegem-te-info-text__title">
                            <div class="title-customize <?=esc_attr($title_text_class)?>" style="<?=esc_attr($title_text_style)?>">
                                <?= $params['title'] ?>
                            </div>
                        </div>
                    <?php endif;?>
                    
                    <?php if (!empty($params['subtitle'])): ?>
                        <div class="thegem-te-info-text__subtitle">
                            <div class="subtitle-customize <?=esc_attr($subtitle_text_class)?>" style="<?=esc_attr($subtitle_text_style)?>">
                                <?= $params['subtitle'] ?>
                            </div>
                        </div>
                    <?php endif;?>
                    
                    <?php if (!empty($params['description'])): ?>
                        <div class="thegem-te-info-text__description">
                            <div class="description-customize <?=esc_attr($description_text_class)?>" style="<?=esc_attr($description_text_style)?>">
                                <?= $params['description'] ?>
                            </div>
                        </div>
                    <?php endif;?>
                </div>
            <?php endif;?>
	
			<?php if (!empty($el_link)): ?>
			</a>
			<?php else: ?>
			</div>
			<?php endif;?>
	
		</div>

		<?php

		// Init Design Options Params
		$custom_css = thegem_templates_element_design_options($uniqid, '.thegem-te-infotext', $params);

		// Icon Custom Styles
		if(!empty($css_style_icon_background)) {
			$custom_css .= '.'.$uniqid.' .gem-icon-inner {'.$css_style_icon_background.'}';
		}
		if(!empty($css_style_icon_1)) {
			$custom_css .= '.'.$uniqid.' .gem-icon-half-1 {'.$css_style_icon_1.'}';
		}
		if(!empty($css_style_icon_2)) {
			$custom_css .= '.'.$uniqid.' .gem-icon-half-2 {'.$css_style_icon_2.'}';
		}
		if(!empty($css_style_icon_3)) {
			$custom_css .= '.'.$uniqid.' .gem-icon-style-gradient .back-angle {'.$css_style_icon_3.'}';
		}
		if(!empty($css_style_icon)) {
			$custom_css .= '.'.$uniqid.' .gem-icon  {'.$css_style_icon.'}';
		}
		if($params['size'] == 'custom' && !empty($params['icon_size'])) {
			$custom_css .= '.'.$uniqid.' .gem-icon  {font-size: '.esc_attr($params['icon_size']).'px;}';
			$custom_css .= '.'.$uniqid.' .gem-icon:not(.gem-simple-icon) .gem-icon-inner {width: '. $params['icon_size'] * 1.5 .'px; height: '. $params['icon_size'] * 1.5 .'px; line-height: '. $params['icon_size'] * 1.5 .'px;}';
			$custom_css .= '.'.$uniqid.' .gem-icon.gem-simple-icon {width: '. $params['icon_size'] .'px; height: '. $params['icon_size'] .'px; line-height: '. $params['icon_size'] .'px;}';
		}
		if(!empty($css_style_icon_1_hover)) {
			$custom_css .= '.'.$uniqid.' a:hover .thegem-te-info-icon .gem-icon-half-1 {'.$css_style_icon_1_hover.'}';
		}
		if(!empty($css_style_icon_2_hover)) {
			$custom_css .= '.'.$uniqid.' a:hover .thegem-te-info-icon .gem-icon-half-2 {'.$css_style_icon_2_hover.'}';
		}
		if(!empty($css_style_icon_3_hover)) {
			$custom_css .= '.'.$uniqid.' a:hover .thegem-te-info-icon .gem-icon-style-gradient .back-angle {'.$css_style_icon_3_hover.'}';
		}
		if(!empty($css_style_icon_background_hover)) {
			$custom_css .= '.'.$uniqid.' a:hover .thegem-te-info-icon .gem-icon-inner {'.$css_style_icon_background_hover.'}';
		}
		if(!empty($css_style_icon_hover)) {
			$custom_css .= '.'.$uniqid.' a:hover .thegem-te-info-icon .gem-icon  {'.$css_style_icon_hover.'}';
		}

		// Text Custom Styles
		$customize = '.thegem-te-infotext.'.$uniqid;
		$elements = array('title', 'subtitle', 'description');
		$resolution = array('desktop', 'tablet', 'mobile');
		$gaps_dir = array('top', 'bottom', 'left', 'right');
		$gaps_vertical_dir = array('top', 'bottom');
		$gap = 'margin';
		$unit = 'px';

		foreach ($resolution as $res) {
			$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');

			foreach ($gaps_dir as $dir) {
				if (!empty($params['icon_'.$res.'_spacing_'.$dir]) || strcmp($params['icon_'.$res.'_spacing_'.$dir], '0') === 0) {
					$result = str_replace(' ', '', $params['icon_'.$res.'_spacing_'.$dir]);
					$last_result = substr($result, -1);
					if ($last_result == '%') {
						$result = str_replace('%', '', $result);
						$unit = $last_result;
					}
					if ($res == 'desktop') {
						$custom_css .= $customize.' '.'.thegem-te-info-icon {'.$gap.'-'.$dir.':'.$result.$unit.' !important;}';
					} else {
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' '.'.thegem-te-info-icon {'.$gap.'-'.$dir.':'.$result.$unit.' !important;}'.'}';
					}
				}
			}
		}

		foreach ($elements as $item) {
			if (!empty($params[$item.'_text_color'])) {
				$custom_css .= $customize.' '.'.'.$item.'-customize {color:'.$params[$item.'_text_color'].' !important;}';
			}

			if (!empty($params[$item.'_text_color_hover'])) {
				$custom_css .= $customize.' a:hover .thegem-te-info-text .'.$item.'-customize {color:'.$params[$item.'_text_color_hover'].' !important;}';
			}

			if (!empty($params[$item.'_custom_font_options'])) {
				if (!empty($params[$item.'_font_size'])) {
					$custom_css .= $customize.' '.'.'.$item.'-customize {font-size:'.$params[$item.'_font_size'].$unit.' !important;}';
				}
				if (!empty($params[$item.'_line_height'])) {
					$custom_css .= $customize.' '.'.'.$item.'-customize {line-height:'.$params[$item.'_line_height'].$unit.' !important;}';
				}
				if (isset($params[$item.'_letter_spacing'])) {
					$custom_css .= $customize.' '.'.'.$item.'-customize {letter-spacing:'.$params[$item.'_letter_spacing'].$unit.' !important;}';
				}
				if (!empty($params[$item.'_text_transform'])) {
					$custom_css .= $customize.' '.'.'.$item.'-customize {text-transform:'.$params[$item.'_text_transform'].' !important;}';
				}
			}

			if (!empty($params[$item.'_responsive_font_options'])) {
				foreach ($resolution as $res) {
					$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');

					if (!empty($params[$item.'_'.$res.'_font_size'])) {
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' '.'.'.$item.'-customize {font-size:'.$params[$item.'_'.$res.'_font_size'].$unit.' !important;}'.'}';
					}
					if (!empty($params[$item.'_'.$res.'_line_height'])) {
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' '.'.'.$item.'-customize {line-height:'.$params[$item.'_'.$res.'_line_height'].$unit.' !important;}'.'}';
					}
					if (isset($params[$item.'_'.$res.'_letter_spacing'])) {
						$custom_css .= '@media screen and ('.$width.') {'.$customize.' '.'.'.$item.'-customize {letter-spacing:'.$params[$item.'_'.$res.'_letter_spacing'].$unit.' !important;}'.'}';
					}
				}
			}

			if (!empty($params[$item.'_google_font_options'])) {
				if (!empty($params[$item.'_google_font'])) {
					$font = thegem_font_parse($params[$item.'_google_font']);
					$custom_css .= $customize.' '.'.'.$item.'-customize {'.esc_attr($font).'}';
				}
			}

			foreach ($resolution as $res) {
				$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');

				foreach ($gaps_vertical_dir as $dir) {
					if (!empty($params[$item.'_'.$res.'_spacing_'.$dir]) || strcmp($params[$item.'_'.$res.'_spacing_'.$dir], '0') === 0) {
						$result = str_replace(' ', '', $params[$item.'_'.$res.'_spacing_'.$dir]);
						$last_result = substr($result, -1);
						if ($last_result == '%') {
							$result = str_replace('%', '', $result);
							$unit = $last_result;
						}
						if ($res == 'desktop') {
							$custom_css .= $customize.' '.'.'.$item.'-customize {'.$gap.'-'.$dir.':'.$result.$unit.' !important;}';
						} else {
							$custom_css .= '@media screen and ('.$width.') {'.$customize.' '.'.'.$item.'-customize {'.$gap.'-'.$dir.':'.$result.$unit.' !important;}'.'}';
						}
					}
				}
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
	
    public function set_presets_params() {
	    $result = array();
	
	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('Presets', 'thegem'),
		    'param_name' => 'layout_delim_head',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding top-margin vc_column vc_col-sm-12',
		    'group' => __('Content', 'thegem')
	    );
	    $result[] = array(
            'type' => 'dropdown',
            'heading' => __('Preset', 'thegem'),
            'param_name' => 'preset',
            'value' => array_merge(array(
                __('Tiny', 'thegem') => 'tiny',
                __('Highlighted', 'thegem') => 'highlighted',
                __('Classic', 'thegem') => 'classic',
                __('Right Icon (Classic)', 'thegem') => 'right_icon_classic',
                __('Right Icon (Tiny)', 'thegem') => 'right_icon_tiny')
            ),
            'std' => 'tiny',
            'dependency' => array(
                'callback' => 'thegem_templates_infotext_presets_callback'
            ),
            'edit_field_class' => 'vc_column vc_col-sm-12',
            'group' => 'Content',
        );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Alignment', 'thegem'),
		    'param_name' => 'infotext_alignment',
		    'value' => array(
			    __('Left', 'thegem') => 'left',
			    __('Centered', 'thegem') => 'center',
			    __('Right', 'thegem') => 'right',
		    ),
		    'std' => 'left',
		    'group' => __('Content', 'thegem')
	    );
	
	    return $result;
    }
    
    public function set_icon_params() {
	    $result = array();
     
	    $result[] = array(
		    'type' => 'thegem_delimeter_heading',
		    'heading' => __('Icon / Image', 'thegem'),
		    'param_name' => 'layout_delim_head',
		    'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
		    'group' => __('Content', 'thegem')
	    );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Icon / Image', 'thegem'),
		    'param_name' => 'icon_image',
		    'value' => array(
			    __('Icon', 'thegem') => 'icon',
			    __('Image', 'thegem') => 'image',
			    __('None', 'thegem') => 'none',
		    ),
		    'std' => 'icon',
		    'group' => 'Content',
	    );
	    $result[] = array(
		    'type' => 'attach_image',
		    'heading' => esc_html__( 'Image', 'thegem' ),
		    'param_name' => 'image',
		    'value' => '',
		    'description' => esc_html__( 'Select image from media library.', 'thegem' ),
		    'dependency' => array(
			    'element' => 'icon_image',
			    'value' => array('image')
		    ),
		    'group' => 'Content',
	    );
	    $result[] = array(
            'type' => 'dropdown',
            'heading' => __('Icon pack', 'thegem'),
            'param_name' => 'pack',
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
	            'element' => 'icon_image',
	            'value' => array('icon')
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => 'Content',
        );
	    $result[] = array(
            'type' => 'thegem_icon',
            'heading' => __('Icon', 'thegem'),
            'param_name' => 'icon_elegant',
            'icon_pack' => 'elegant',
            'dependency' => array(
                'element' => 'pack',
                'value' => array('elegant')
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => 'Content'
        );
	    $result[] = array(
            'type' => 'thegem_icon',
            'heading' => __('Icon', 'thegem'),
            'param_name' => 'icon_material',
            'icon_pack' => 'material',
            'dependency' => array(
                'element' => 'pack',
                'value' => array('material')
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => 'Content'
        );
	    $result[] = array(
            'type' => 'thegem_icon',
            'heading' => __('Icon', 'thegem'),
            'param_name' => 'icon_fontawesome',
            'icon_pack' => 'fontawesome',
            'dependency' => array(
                'element' => 'pack',
                'value' => array('fontawesome')
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => 'Content'
        );
	    $result[] = array(
            'type' => 'thegem_icon',
            'heading' => __('Icon', 'thegem'),
            'param_name' => 'icon_thegemdemo',
            'icon_pack' => 'thegemdemo',
            'dependency' => array(
                'element' => 'pack',
                'value' => array('thegemdemo')
            ),
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => 'Content'
        );
	    $result[] = array(
            'type' => 'thegem_icon',
            'heading' => __('Icon', 'thegem'),
            'param_name' => 'icon_thegem_header',
            'icon_pack' => 'thegem-header',
            'dependency' => array(
                'element' => 'pack',
                'value' => array('thegem-header')
            ),
            'std' => 'e622',
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => 'Content'
        );
	    $result[] = array(
		    'type' => 'thegem_icon',
		    'heading' => __('Icon', 'thegem'),
		    'param_name' => 'icon_userpack',
		    'icon_pack' => 'userpack',
		    'dependency' => array(
			    'element' => 'pack',
			    'value' => array('userpack')
		    ),
		    'edit_field_class' => 'vc_column vc_col-sm-6',
		    'group' => 'Content'
	    );
	    $result[] = array(
            'type' => 'dropdown',
            'heading' => __('Position to Text', 'thegem'),
            'param_name' => 'icon_position_to_text',
            'value' => array(
                __('Left', 'thegem') => 'left',
                __('Top', 'thegem') => 'top',
                __('Right', 'thegem') => 'right',
                __('Bottom', 'thegem') => 'bottom',
            ),
		    'std' => 'left',
            'group' => 'Content',
            'edit_field_class' => 'vc_column vc_col-sm-6',
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
		    'dependency' => array(
			    'element' => 'icon_position_to_text',
			    'value' => array('left', 'right')
		    ),
		    'std' => 'center',
            'group' => 'Content',
            'edit_field_class' => 'vc_column vc_col-sm-6',
        );
	    $result[] = array(
		    'type' => 'dropdown',
		    'heading' => __('Horizontal Align', 'thegem'),
		    'param_name' => 'icon_horizontal_align',
		    'value' => array(
			    __('Left', 'thegem') => 'left',
			    __('Center', 'thegem') => 'center',
			    __('Right', 'thegem') => 'right',
		    ),
		    'std' => 'left',
		    'dependency' => array(
			    'element' => 'icon_position_to_text',
			    'value' => array('top', 'bottom')
		    ),
		    'group' => 'Content',
		    'edit_field_class' => 'vc_column vc_col-sm-6',
	    );
	    $result[] = array(
            'type' => 'dropdown',
            'heading' => __('Size', 'thegem'),
            'param_name' => 'size',
            'value' => array(
                __('Tiny', 'thegem') => 'tiny',
                __('Small', 'thegem') => 'small',
                __('Medium', 'thegem') => 'medium',
                __('Large', 'thegem') => 'large',
                __('Extra Large', 'thegem') => 'xlarge',
                __('Custom', 'thegem') => 'custom'
            ),
            'std' => 'custom',
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => 'Content'
        );
	    $result[] = array(
            'type' => 'textfield',
            'heading' => __('Custom size', 'thegem'),
            'param_name' => 'icon_size',
            'dependency' => array(
                'element' => 'size',
                'value' => array('custom')
            ),
            'std' => '16',
            'edit_field_class' => 'vc_column vc_col-sm-6',
            'group' => 'Content'
        );
	
	    return $result;
    }
    
	public function set_headings_params () {
		$heading_items = array('title', 'subtitle', 'description');
		$heading_options = array();
		
		foreach ($heading_items as $item) {
			// Text Style Default
			$text_style_default = '';
			if ($item == 'title') {
				$text_style_default = 'title-default';
			}
			if ($item == 'subtitle') {
				$text_style_default = 'styled-subtitle';
			}
			if ($item == 'description') {
				$text_style_default = 'text-body-tiny';
			}
			
			$heading_options[] = array(
				'type' => 'thegem_delimeter_heading',
				'heading' => __($item, 'thegem'),
				'param_name' => 'layout_delim_head',
				'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
				'group' => __('Content', 'thegem')
			);
			$heading_options[] = array(
				'type' => 'textarea',
				'heading' => __('Text', 'thegem'),
				'param_name' => $item,
				'std' => $item === 'description' ? '+1 916-85-2235' : '',
				'edit_field_class' => 'vc_column vc_col-sm-8',
				'group' => __('Content', 'thegem')
			);
			$heading_options[] = array(
				'type' => 'dropdown',
				'heading' => __('Text Style', 'thegem'),
				'param_name' => $item.'_text_style',
				'value' => array(
					__('Default', 'thegem') => $text_style_default,
					__('Header Default', 'thegem') => 'title-default',
					__('Title H1', 'thegem') => 'title-h1',
					__('Title H2', 'thegem') => 'title-h2',
					__('Title H3', 'thegem') => 'title-h3',
					__('Title H4', 'thegem') => 'title-h4',
					__('Title H5', 'thegem') => 'title-h5',
					__('Title H6', 'thegem') => 'title-h6',
					__('Title xLarge', 'thegem') => 'title-xlarge',
					__('Styled Subtitle', 'thegem') => 'styled-subtitle',
					__('Body', 'thegem') => 'text-body',
					__('Tiny Body', 'thegem') => 'text-body-tiny',
				),
				'edit_field_class' => 'vc_column vc_col-sm-4',
				'group' => __('Content', 'thegem')
			);
			$heading_options[] = array(
				'type' => 'dropdown',
				'heading' => __('Font weight', 'thegem'),
				'param_name' => $item.'_font_weight',
				'value' => array(
					__('Default', 'thegem') => '',
					__('Thin', 'thegem') => 'light',
				),
				'edit_field_class' => 'vc_column vc_col-sm-4',
				'group' => __('Content', 'thegem')
			);
			$heading_options[] = array(
				'type' => 'dropdown',
				'heading' => __('Font style', 'thegem'),
				'param_name' => $item.'_font_style',
				'value' => array(
					__('Default', 'thegem') => '',
					__('Italic', 'thegem') => 'italic',
				),
				'edit_field_class' => 'vc_column vc_col-sm-4',
				'group' => __('Content', 'thegem')
			);
			$heading_options[] = array(
				'type' => 'dropdown',
				'heading' => __('Text align', 'thegem'),
				'param_name' => $item.'_text_align',
				'value' => array(
					__('Left', 'thegem') => 'left',
					__('Right', 'thegem') => 'right',
					__('Center', 'thegem') => 'center',
					__('Justify', 'thegem') => 'justify'
				),
				'edit_field_class' => 'vc_column vc_col-sm-4',
				'group' => __('Content', 'thegem')
			);
			$heading_options[] = array(
				'type' => 'textfield',
				'heading' => __('Max width', 'thegem'),
				'param_name' => $item.'_width',
				'edit_field_class' => 'vc_column vc_col-sm-12',
				'group' => __('Content', 'thegem')
			);
		}
		
		return $heading_options;
	}
	
	public function set_icon_styled_params() {
		$result = array();
		
		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Styled Options', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
			'group' => __('Icon/Image Style', 'thegem'),
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Shape', 'thegem'),
			'param_name' => 'shape',
			'value' => array(
				__('Simple', 'thegem') => 'simple',
				__('Square', 'thegem') => 'square',
				__('Circle', 'thegem') => 'circle'
			),
			'group' => __('Icon/Image Style', 'thegem'),
			'edit_field_class' => 'vc_column vc_col-sm-6',
		);
		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Color Split', 'thegem'),
			'param_name' => 'style',
			'value' => array(
				__('Disabled', 'thegem') => '',
				__('Gradient', 'thegem') => 'gradient',
				__('45 degree Right', 'thegem') => 'angle-45deg-r',
				__('45 degree Left', 'thegem') => 'angle-45deg-l',
				__('90 degree', 'thegem') => 'angle-90deg'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => __('Icon/Image Style', 'thegem'),
		);
		$result[] = array(
			"type" => "dropdown",
			'heading' => __('Gradient Style', 'thegem'),
			'param_name' => 'gradient_color_style',
			"value" => array(
				__('Linear', "thegem") => "linear",
				__('Radial', "thegem") => "radial",
			) ,
			"std" => 'linear',
			'dependency' => array(
				'element' => 'style',
				'value' => array('gradient')
			),
			"edit_field_class" => "vc_col-sm-6 vc_column",
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			"type" => "dropdown",
			'heading' => __('Gradient Position', 'thegem'),
			'param_name' => 'gradient_radial_color_position',
			"value" => array(
				__('Top', "thegem") => "at top",
				__('Bottom', "thegem") => "at bottom",
				__('Right', "thegem") => "at right",
				__('Left', "thegem") => "at left",
				__('Center', "thegem") => "at center",
			),
			'dependency' => array(
				'element' => 'gradient_color_style',
				'value' => array('radial')
			),
			"edit_field_class" => "vc_col-sm-6 vc_column",
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			"type" => "dropdown",
			'heading' => __('Gradient Angle', 'thegem'),
			'param_name' => 'gradient_color_angle',
			"value" => array(
				__('Vertical to bottom ↓', "thegem") => "to bottom",
				__('Vertical to top ↑', "thegem") => "to top",
				__('Horizontal to left  →', "thegem") => "to right",
				__('Horizontal to right ←', "thegem") => "to left",
				__('Diagonal from left to bottom ↘', "thegem") => "to bottom right",
				__('Diagonal from left to top ↗', "thegem") => "to top right",
				__('Diagonal from right to bottom ↙', "thegem") => "to bottom left",
				__('Diagonal from right to top ↖', "thegem") => "to top left",
				__('Custom', "thegem") => "cusotom_deg",
			) ,
			'dependency' => array(
				'element' => 'gradient_color_style',
				'value' => array('linear')
			),
			"edit_field_class" => "vc_col-sm-6 vc_column",
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			"type" => "textfield",
			'heading' => __('Custom Angle', 'thegem'),
			'param_name' => 'gradient_color_cusotom_deg',
			'dependency' => array(
				'element' => 'gradient_color_angle',
				'value' => array('cusotom_deg')
			),
			"edit_field_class" => "vc_col-sm-12 vc_col-sm-offset-6 vc_column",
			'description' => __('Set value in DG 0-360', 'thegem'),
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Color', 'thegem'),
			'param_name' => 'color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Hover color', 'thegem'),
			'param_name' => 'hover_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Color 2', 'thegem'),
			'param_name' => 'color_2',
			'dependency' => array(
				'element' => 'style',
				'not_empty' => true
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Hover color 2', 'thegem'),
			'param_name' => 'hover_color_2',
			'dependency' => array(
				'element' => 'style',
				'not_empty' => true
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color', 'thegem'),
			'param_name' => 'background_color',
			'dependency' => array(
				'element' => 'shape',
				'value' => array('square', 'circle')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => __('Icon/Image Style', 'thegem'),
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Hover Background Color', 'thegem'),
			'param_name' => 'hover_background_color',
			'dependency' => array(
				'element' => 'shape',
				'value' => array('square', 'circle')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color', 'thegem'),
			'param_name' => 'border_color',
			'dependency' => array(
				'element' => 'shape',
				'value' => array('square', 'circle')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Hover Border Color', 'thegem'),
			'param_name' => 'hover_border_color',
			'dependency' => array(
				'element' => 'shape',
				'value' => array('square', 'circle')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Use Gradient Backgound', 'thegem'),
			'param_name' => 'gradient_backgound',
			'value' => array(__('Yes', 'thegem') => '1'),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('From', 'thegem'),
			'param_name' => 'gradient_backgound_from',
			'dependency' => array(
				'element' => 'gradient_backgound',
				'value' => array('1')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('To', 'thegem'),
			'param_name' => 'gradient_backgound_to',
			'dependency' => array(
				'element' => 'gradient_backgound',
				'value' => array('1')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			"type" => "dropdown",
			'heading' => __('Style', 'thegem'),
			'param_name' => 'gradient_backgound_style',
			"value" => array(
				__('Linear', "thegem") => "linear",
				__('Radial', "thegem") => "radial",
			) ,
			"std" => 'linear',
			'dependency' => array(
				'element' => 'gradient_backgound',
				'value' => array('1')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			"type" => "dropdown",
			'heading' => __('Gradient Position', 'thegem'),
			'param_name' => 'gradient_radial_backgound_position',
			"value" => array(
				__('Top', "thegem") => "at top",
				__('Bottom', "thegem") => "at bottom",
				__('Right', "thegem") => "at right",
				__('Left', "thegem") => "at left",
				__('Center', "thegem") => "at center",
			
			) ,
			'dependency' => array(
				'element' => 'gradient_backgound_style',
				'value' => array('radial')
			),
			"edit_field_class" => "vc_col-sm-6 vc_column",
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			"type" => "dropdown",
			'heading' => __('Angle', 'thegem'),
			'param_name' => 'gradient_backgound_angle',
			"value" => array(
				__('Vertical to bottom ↓', "thegem") => "to bottom",
				__('Vertical to top ↑', "thegem") => "to top",
				__('Horizontal to left  →', "thegem") => "to right",
				__('Horizontal to right ←', "thegem") => "to left",
				__('Diagonal from left to bottom ↘', "thegem") => "to bottom right",
				__('Diagonal from left to top ↗', "thegem") => "to top right",
				__('Diagonal from right to bottom ↙', "thegem") => "to bottom left",
				__('Diagonal from right to top ↖', "thegem") => "to top left",
				__('Custom', "thegem") => "cusotom_deg",
			) ,
			'dependency' => array(
				'element' => 'gradient_backgound_style',
				'value' => array('linear')
			),
			"edit_field_class" => "vc_col-sm-6 vc_column",
			'group' => __('Icon/Image Style', 'thegem')
		);
		$result[] = array(
			"type" => "textfield",
			'heading' => __('Custom Angle', 'thegem'),
			'param_name' => 'gradient_backgound_cusotom_deg',
			'description' => __('Set value in DG 0-360', 'thegem'),
			'dependency' => array(
				'element' => 'gradient_backgound_angle',
				'value' => array('cusotom_deg')
			),
			"edit_field_class" => "vc_col-sm-12 vc_col-sm-offset-6 vc_column",
			'group' => __('Icon/Image Style', 'thegem')
		);
		
		return $result;
	}
	
	public function set_icon_styled_position_params () {
		$icon_gaps = array('spacing');
		$gaps_resolution = array('desktop', 'tablet', 'mobile');
		$icon_gaps_dir = array('top', 'bottom', 'left', 'right');
		$icon_gaps_option = array();
		
		$icon_gaps_option[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Position Options', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
			'group' => __('Icon/Image Style', 'thegem'),
		);
		foreach ($icon_gaps as $gap) {
			
			foreach ($gaps_resolution as $res) {
				$icon_gaps_option[] = array(
					'type' => 'thegem_delimeter_heading_two_level',
					'heading' => __($res, 'thegem'),
					'param_name' => 'icon_'.$gap.'_sub_head',
					'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12 capitalize',
					'group' => __('Icon/Image Style', 'thegem'),
				);
				
				foreach ($icon_gaps_dir as $dir) {
					$icon_gaps_option[] = array(
						'type' => 'textfield',
						'heading' => __($dir.' Spacing', 'thegem'),
						'param_name' => 'icon_'.$res.'_'.$gap.'_'.$dir,
						'value' => '',
                        'std' => ' ',
						'edit_field_class' => 'vc_column vc_col-sm-3 capitalize',
						'group' => __('Icon/Image Style', 'thegem'),
					);
				}
			}
		}
		
		return $icon_gaps_option;
	}
	
	public function set_headings_styled_params () {
		$heading_items = array('title', 'subtitle', 'description');
		$text_resolution = array('tablet', 'mobile');
		$gaps_resolution = array('desktop', 'tablet', 'mobile');
		$heading_options = array();
		
		foreach ($heading_items as $item) {
			$group = ucfirst($item).' '.'Style';
			
			$heading_options[] = array(
				'type' => 'thegem_delimeter_heading',
				'heading' => __('Styled Options', 'thegem'),
				'param_name' => 'layout_delim_head',
				'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12',
				'group' => __($group, 'thegem')
			);
			$heading_options[] = array(
				'type' => 'colorpicker',
				'heading' => __('Text color', 'thegem'),
				'param_name' => $item.'_text_color',
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __($group, 'thegem')
			);
			$heading_options[] = array(
				'type' => 'colorpicker',
				'heading' => __('Text hover color', 'thegem'),
				'param_name' => $item.'_text_color_hover',
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __($group, 'thegem')
			);
			$heading_options[] = array(
				'type' => 'checkbox',
				'heading' => __('Use custom font size?', 'thegem'),
				'param_name' => $item.'_custom_font_options',
				'value' => array(__('Yes', 'thegem') => '1'),
				'std' => 0,
				'group' => __($group, 'thegem')
			);
			$heading_options[] = array(
				'type' => 'textfield',
				'heading' => __('Font size', 'thegem'),
				'param_name' => $item.'_font_size',
				'dependency' => array(
					'element' => $item.'_custom_font_options',
					'value' => '1'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __($group, 'thegem')
			);
			$heading_options[] = array(
				'type' => 'textfield',
				'heading' => __('Line height', 'thegem'),
				'param_name' => $item.'_line_height',
				'dependency' => array(
					'element' => $item.'_custom_font_options',
					'value' => '1'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __($group, 'thegem')
			);
			$heading_options[] = array(
				'type' => 'textfield',
				'heading' => __('Letter spacing', 'thegem'),
				'param_name' => $item.'_letter_spacing',
				'dependency' => array(
					'element' => $item.'_custom_font_options',
					'value' => '1'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __($group, 'thegem')
			);
			$heading_options[] = array(
				'type' => 'dropdown',
				'heading' => __('Text transform', 'thegem'),
				'param_name' => $item.'_text_transform',
				'value' => array(
					__('None', 'thegem') => 'none',
					__('Capitalize', 'thegem') => 'capitalize',
					__('Lowercase', 'thegem') => 'lowercase',
					__('Uppercase', 'thegem') => 'uppercase'
				),
				'std' => 'none',
				'dependency' => array(
					'element' => $item.'_custom_font_options',
					'value' => '1'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => __($group, 'thegem')
			);
			$heading_options[] = array(
				'type' => 'checkbox',
				'heading' => __('Responsive font size options', 'thegem'),
				'param_name' => $item.'_responsive_font_options',
				'value' => array(__('Yes', 'thegem') => '1'),
				'group' => __($group, 'thegem')
			);
			
			foreach ($text_resolution as $res) {
				$heading_options[] = array(
					'type' => 'thegem_delimeter_heading_two_level',
					'heading' => __($res, 'thegem'),
					'param_name' => $item.'_font_options_sub_head_'.$res,
					'dependency' => array(
						'element' => $item.'_responsive_font_options',
						'value' => '1'
					),
					'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12 capitalize',
					'group' => __($group, 'thegem')
				);
				$heading_options[] = array(
					'type' => 'textfield',
					'heading' => __('Font size', 'thegem'),
					'param_name' => $item.'_'.$res.'_font_size',
					'dependency' => array(
						'element' => $item.'_responsive_font_options',
						'value' => '1'
					),
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __($group, 'thegem')
				);
				$heading_options[] = array(
					'type' => 'textfield',
					'heading' => __('Line height', 'thegem'),
					'param_name' => $item.'_'.$res.'_line_height',
					'dependency' => array(
						'element' => $item.'_responsive_font_options',
						'value' => '1'
					),
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __($group, 'thegem')
				);
				$heading_options[] = array(
					'type' => 'textfield',
					'heading' => __('Letter spacing', 'thegem'),
					'param_name' => $item.'_'.$res.'_letter_spacing',
					'dependency' => array(
						'element' => $item.'_responsive_font_options',
						'value' => '1'
					),
					'edit_field_class' => 'vc_column vc_col-sm-4',
					'group' => __($group, 'thegem')
				);
			}
			
			$heading_options[] = array(
				'type' => 'checkbox',
				'heading' => __('Use Google fonts?', 'thegem'),
				'param_name' => $item.'_google_font_options',
				'value' => array(__('Yes', 'thegem') => '1'),
				'group' => __($group, 'thegem')
			);
			$heading_options[] = array(
				'type' => 'google_fonts',
				'param_name' => $item.'_google_font',
				'value' => '',
				'settings' => array(
					'fields' => array(
						'font_family_description' => esc_html__('Select font family.', 'thegem'),
						'font_style_description' => esc_html__('Select font styling.', 'thegem'),
					),
				),
				'dependency' => array(
					'element' => $item.'_google_font_options',
					'value' => '1'
				),
				'group' => __($group, 'thegem')
			);
			$heading_options[] = array(
				'type' => 'thegem_delimeter_heading',
				'heading' => __('Position Options', 'thegem'),
				'param_name' => 'layout_delim_head',
				'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12',
				'group' => __($group, 'thegem')
			);
			
			foreach ($gaps_resolution as $res) {
				$heading_options[] = array(
					'type' => 'thegem_delimeter_heading_two_level',
					'heading' => __($res, 'thegem'),
					'param_name' => $item.'_spacing_sub_head_'.$res,
					'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12 capitalize',
					'group' => __($group, 'thegem')
				);
				$heading_options[] = array(
					'type' => 'textfield',
					'heading' => __('Top Spacing', 'thegem'),
					'param_name' => $item.'_'.$res.'_spacing_top',
					'edit_field_class' => 'vc_column vc_col-sm-6',
					'group' => __($group, 'thegem')
				);
				$heading_options[] = array(
					'type' => 'textfield',
					'heading' => __('Bottom Spacing', 'thegem'),
					'param_name' => $item.'_'.$res.'_spacing_bottom',
					'edit_field_class' => 'vc_column vc_col-sm-6',
					'group' => __($group, 'thegem')
				);
			}
		}
		
		return $heading_options;
	}

	public function shortcode_settings() {
        
        return array(
            'name' => __('Infotext', 'thegem'),
            'base' => 'thegem_te_infotext',
            'icon' => 'thegem-icon-wpb-ui-element-infotext',
            'category' => __('Header Builder', 'thegem'),
            'description' => __('Infotext with Icon (Header Builder)', 'thegem'),
            'params' => array_merge(

                /* Content - Presets */
                $this->set_presets_params(),
	
	            /* Flex Options */
	            thegem_set_elements_design_options(),
    
                /* Content - Icon */
                $this->set_icon_params(),
    
                /* Content -- Title / Subtitle / Description */
                $this->set_headings_params(),
                
                /* Icon - Style */
                $this->set_icon_styled_params(),
    
                /* Icon - Style Gaps */
                $this->set_icon_styled_position_params(),
    
                /* Title Subtitle Description Style */
                $this->set_headings_styled_params(),
        
                /* Extra Options */
                thegem_set_elements_extra_options(true, 'Content')
            )
        );
	}
}

$templates_elements['thegem_te_infotext'] = new TheGem_Template_Element_Infotext();
$templates_elements['thegem_te_infotext']->init();
