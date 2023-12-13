<?php

class TheGem_Template_Element_Portfolio_Gallery extends TheGem_Portfolio_Item_Template_Element
{

	public function __construct()
	{
		if (!defined('THEGEM_TE_PORTFOLIO_GALLERY_DIR')) {
			define('THEGEM_TE_PORTFOLIO_GALLERY_DIR', rtrim(__DIR__, ' /\\'));
		}

		if (!defined('THEGEM_TE_PORTFOLIO_GALLERY_URL')) {
			define('THEGEM_TE_PORTFOLIO_GALLERY_URL', rtrim(plugin_dir_url(__FILE__), ' /\\'));
		}

		wp_register_style('thegem-te-portfolio-gallery', THEGEM_TE_PORTFOLIO_GALLERY_URL . '/css/portfolio-gallery.css', false);

		wp_register_script('thegem-te-portfolio-carousel', THEGEM_TE_PORTFOLIO_GALLERY_URL . '/js/portfolio-gallery.js', array('jquery', 'owl'), false, true);
		wp_register_style('thegem-te-portfolio-carousel', THEGEM_TE_PORTFOLIO_GALLERY_URL . '/css/portfolio-gallery.css', array('owl'));
	}

	public function get_name()
	{
		return 'thegem_te_portfolio_gallery';
	}

	public function shortcode_activate($shortcodes) {
		global $pagenow;
		if((is_admin() && in_array($pagenow, array('post-new.php', 'post.php', 'admin-ajax.php'))) || (!is_admin() && in_array($pagenow, array('index.php')))) {
			$activate = 0;
			if($pagenow === 'post-new.php' && !empty($_REQUEST['post_type']) && $_REQUEST['post_type'] === 'thegem_templates') {
				$activate = true;
			}
			if($pagenow === 'post.php' && !empty($_REQUEST['post']) && get_post_type($_REQUEST['post']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post']) == 'portfolio') {
				$activate = true;
			}
			if($pagenow === 'post.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post_id']) == 'portfolio') {
				$activate = true;
			}
			if($pagenow === 'admin-ajax.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post_id']) == 'portfolio') {
				$activate = true;
			}
			if($pagenow === 'index.php' && !empty($_REQUEST['vc_post_id']) && get_post_type($_REQUEST['vc_post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['vc_post_id']) == 'portfolio') {
				$activate = true;
			}
			if($pagenow === 'post.php' && !empty($_REQUEST['post']) && get_post_type($_REQUEST['post']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post']) == 'single-post') {
				$activate = true;
			}
			if($pagenow === 'post.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post_id']) == 'single-post') {
				$activate = true;
			}
			if($pagenow === 'admin-ajax.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post_id']) == 'single-post') {
				$activate = true;
			}
			if($pagenow === 'index.php' && !empty($_REQUEST['vc_post_id']) && get_post_type($_REQUEST['vc_post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['vc_post_id']) == 'single-post') {
				$activate = true;
			}
			if($pagenow === 'post-new.php' && !empty($_REQUEST['post_type']) && $_REQUEST['post_type'] !== 'thegem_templates') {
				$activate = true;
			}
			if($pagenow === 'post.php' && !empty($_REQUEST['post']) && get_post_type($_REQUEST['post']) !== 'thegem_templates') {
				$activate = true;
			}
			if($pagenow === 'post.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) !== 'thegem_templates') {
				$activate = true;
			}
			if($pagenow === 'admin-ajax.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) !== 'thegem_templates') {
				$activate = true;
			}
			if($pagenow === 'index.php' && !empty($_REQUEST['vc_post_id']) && get_post_type($_REQUEST['vc_post_id']) !== 'thegem_templates') {
				$activate = true;
			}
			if(!empty($_REQUEST['action']) && !empty($_REQUEST['post_ID']) && $_REQUEST['action'] === 'editpost') {
				$activate = true;
			}
			if($activate) {
				$shortcodes[$this->get_name()] = $this->shortcode_settings();
			}
		}
		return $shortcodes;
	}

	public function is_template($type = 'portfolio') {
		global $pagenow;
		if($pagenow === 'post.php' && !empty($_REQUEST['post']) && get_post_type($_REQUEST['post']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post']) == $type) {
			return true;
		}
		if($pagenow === 'post.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post_id']) == $type) {
			return true;
		}
		if($pagenow === 'admin-ajax.php' && !empty($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['post_id']) == $type) {
			return true;
		}
		if($pagenow === 'index.php' && !empty($_REQUEST['vc_post_id']) && get_post_type($_REQUEST['vc_post_id']) === 'thegem_templates' && thegem_get_template_type($_REQUEST['vc_post_id']) == $type) {
			return true;
		}
		return false;
	}

	public function get_images_params($item, $id, $param)
	{
		$result = '';

		switch ($param) {
			case 'alt':
				$alt_custom = get_post_meta($id, '_wp_attachment_image_alt', true);
				$alt_default = $item->title;
				$result = !empty($alt_custom) ? esc_html($alt_custom) : esc_html($alt_default);
				break;
			case 'title':
				$title_custom = get_the_title($id);
				$title_default = $item->title;
				$result = !empty($title_custom) ? esc_html($title_custom) : esc_html($title_default);
				break;
		}

		return $result;
	}

	public function is_admin_mode()
	{
		return function_exists('vc_is_page_editable') && vc_is_page_editable();
	}

	public function shortcode_output($atts, $content = '')
	{
		// General params
		$params = shortcode_atts(array_merge(array(
			'type' => 'carousel',
			'carousel_columns_desktop' => '4',
			'carousel_columns_tablet' => '3',
			'carousel_columns_mobile' => '2',
			'carousel_gaps_desktop' => '',
			'carousel_gaps_tablet' => '',
			'carousel_gaps_mobile' => '',
			'grid_columns_desktop' => '4',
			'grid_columns_tablet' => '3',
			'grid_columns_mobile' => '2',
			'grid_gaps_desktop' => '',
			'grid_gaps_tablet' => '',
			'grid_gaps_mobile' => '',
			'grid_image_count_desktop' => '',
			'grid_image_count_tablet' => '',
			'grid_image_count_mobile' => '',
			'image_ratio' => '1',
			'lightbox' => '1',
			'autoplay' => '0',
			'autoplay_speed' => '5000',
			'include_featured_image' => '',
			'image_height_desktop' => '',
			'image_height_tablet' => '',
			'image_height_mobile' => '',
			'image_radius_desktop' => '',
			'image_radius_tablet' => '',
			'image_radius_mobile' => '',
			'image_shadow' => '',
			'image_shadow_color' => 'rgba(0,0,0,0.15)',
			'image_shadow_position' => 'outline',
			'image_shadow_horizontal' => '0',
			'image_shadow_vertical' => '5',
			'image_shadow_blur' => '5',
			'image_shadow_spread' => '-5',
			'arrows' => '1',
			'arrows_type' => 'simple',
			'arrows_icons' => '',
			'arrows_prev_icon_pack' => 'elegant',
			'arrows_prev_icon_elegant' => '',
			'arrows_prev_icon_material' => '',
			'arrows_prev_icon_fontawesome' => '',
			'arrows_prev_icon_thegemdemo' => '',
			'arrows_prev_icon_thegemheader' => '',
			'arrows_prev_icon_userpack' => '',
			'arrows_prev_icon_size' => '',
			'arrows_next_icon_pack' => 'elegant',
			'arrows_next_icon_elegant' => '',
			'arrows_next_icon_material' => '',
			'arrows_next_icon_fontawesome' => '',
			'arrows_next_icon_thegemdemo' => '',
			'arrows_next_icon_thegemheader' => '',
			'arrows_next_icon_userpack' => '',
			'arrows_next_icon_size' => '',
			'arrows_position' => 'space-between',
			'arrows_top_spacing' => '',
			'arrows_bottom_spacing' => '',
			'arrows_side_spacing' => '',
			'arrows_border_width' => '',
			'arrows_border_radius' => '',
			'arrows_width' => '',
			'arrows_height' => '',
			'arrows_color' => '',
			'arrows_color_hover' => '',
			'arrows_background_color' => '',
			'arrows_background_color_hover' => '',
			'arrows_border_color' => '',
			'arrows_border_color_hover' => '',
			'dots' => '0',
			'dots_size' => '',
			'dots_top_spacing' => '',
			'dots_side_spacing' => '',
			'dots_color' => '',
			'dots_color_hover' => '',
			'dots_color_active' => '',
			'loop' => '',
		),
			thegem_templates_extra_options_extract(),
			thegem_templates_responsive_options_extract()
		), $atts, 'thegem_te_portfolio_gallery');

		// Init Gallery
		ob_start();
		$uniqid = uniqid('thegem-custom-') . rand(1, 9999);
		$portfolio = thegem_templates_init_portfolio();
		if (!empty($portfolio)) {
			$attachment_ids = explode(',', get_post_meta($portfolio->ID, 'thegem_portfolio_item_gallery_images', true));
			if($portfolio->post_type !== 'thegem_pf_item') {
				$attachment_ids = explode(',', get_post_meta($portfolio->ID, 'thegem_post_item_gallery_images', true));
			}
			if($params['include_featured_image'] && has_post_thumbnail($portfolio->ID)) {
				$attachment_ids = array_merge(array(get_post_thumbnail_id($portfolio->ID)), $attachment_ids);
			}
		}

		if (empty($portfolio) || empty($attachment_ids) || empty($attachment_ids[0])) {
			ob_end_clean();
			return thegem_templates_close_portfolio($this->get_name(), $this->shortcode_settings(), '');
		}

		// Enqueue Scripts/Styles
		if ($params['type'] == 'carousel') {
			wp_enqueue_script('thegem-te-portfolio-carousel');
			wp_enqueue_style('thegem-te-portfolio-carousel');
		}
		wp_enqueue_style('thegem-te-portfolio-gallery');

		// Get images
		$attachments_count = count($attachment_ids);
		$items_output = $thumb_output = '';
		if ($attachment_ids) {
			foreach ($attachment_ids as $id) {
				$attachment = wp_get_attachment_image($id);

				if (empty($attachment)) continue;

				$image_url = wp_get_attachment_url($id);
				$image_args = array(
					'class' => 'img-responsive',
					'alt' => $this->get_images_params($portfolio, $id, 'alt'),
				);

				ob_start();
				echo wp_get_attachment_image($id, 'full', false, $image_args);
				$thumb_output = '<div class="image-inner">' . ob_get_clean() . '</div>';

				if (!empty($params['lightbox'])) {
					$image_output =
						'<a class="lightbox item-inner" href="' . esc_url($image_url) . '"  data-fancybox="portfolio-gallery-' . esc_attr($uniqid) . '" data-fancybox-group="portfolio-gallery-' . esc_attr($uniqid) . '" data-full-image-url="' . esc_url($image_url) . '">' . $thumb_output . '</a>';
				} else {
					$image_output = '<div class="item-inner">' . $thumb_output . '</div>';
				}

				$items_output .= '<div class="item" data-id="' . esc_attr($id) . '">' . $image_output . '</div>';
			}
		}

		// Set carousel navigation
		$carousel_nav_output = $carousel_nav_prev = $carousel_nav_next = '';
		if (!empty($params['arrows_icons']) && $params['arrows_icons'] == 'custom' && isset($params['arrows_prev_icon_pack']) && !empty($params['arrows_prev_icon_' . str_replace("-", "", $params['arrows_prev_icon_pack'])])) {
			wp_enqueue_style('icons-' . $params['arrows_prev_icon_pack']);
			$carousel_nav_prev = '<a href="javascript;" class="icon">' . thegem_build_icon($params['arrows_prev_icon_pack'], $params['arrows_prev_icon_' . str_replace("-", "", $params['arrows_prev_icon_pack'])]) . '</a>';
		} else {
			$carousel_nav_prev = '<a href="javascript;" class="icon"><i class="icon-default"></i></a>';
		}

		if (!empty($params['arrows_icons']) && $params['arrows_icons'] == 'custom' && isset($params['arrows_next_icon_pack']) && !empty($params['arrows_next_icon_' . str_replace("-", "", $params['arrows_next_icon_pack'])])) {
			wp_enqueue_style('icons-' . $params['arrows_next_icon_pack']);
			$carousel_nav_next = '<a href="javascript;" class="icon">' . thegem_build_icon($params['arrows_next_icon_pack'], $params['arrows_next_icon_' . str_replace("-", "", $params['arrows_next_icon_pack'])]) . '</a>';
		} else {
			$carousel_nav_next = '<a href="javascript;" class="icon"><i class="icon-default"></i></a>';
		}

		$carousel_nav_output .= '<div class="nav-item nav-prev">' . $carousel_nav_prev . '</div>';
		$carousel_nav_output .= '<div class="nav-item nav-next">' . $carousel_nav_next . '</div>';
		$carousel_nav_class = 'carousel-nav--' . $params['arrows_type'] . ' ' . 'carousel-nav--' . $params['arrows_position'];

		$aspect_ratio = !empty($params['image_ratio']) ? 'image-aspect-ratio' : '';
		$params['element_class'] = implode(' ', array($params['element_class'], $aspect_ratio));
		?>

		<div <?php if (!empty($params['element_id'])): ?>id="<?= esc_attr($params['element_id']); ?>"<?php endif; ?>
		     class="thegem-te-portfolio-gallery <?= esc_attr($params['element_class']); ?> <?= esc_attr($uniqid); ?>">
			<?php if ($params['type'] == 'carousel'): ?>
				<div class="portfolio-carousel owl-carousel"
					data-items-desktop="<?= $params['carousel_columns_desktop'] ?>"
					data-items-tablet="<?= $params['carousel_columns_tablet'] ?>"
					data-items-mobile="<?= $params['carousel_columns_mobile'] ?>"
					data-margin-desktop="<?= $params['carousel_gaps_desktop'] ?>"
					data-margin-tablet="<?= $params['carousel_gaps_tablet'] ?>"
					data-margin-mobile="<?= $params['carousel_gaps_mobile'] ?>"
					data-dots="<?= $params['dots'] ?>"
					data-loop="<?= $params['loop'] ?>"
					data-length="<?= $attachments_count ?>"
					data-autoplay="<?= $params['autoplay'] ?>"
					data-autoplay-speed="<?= $params['autoplay_speed'] ?>">

					<?= $items_output ?>
				</div>

			<?php if (!empty($params['arrows'])): ?>
				<div class="portfolio-carousel-nav <?= $carousel_nav_class ?>">
					<?= $carousel_nav_output ?>
				</div>
			<?php endif; ?>

			<?php if ($this->is_admin_mode()): ?>
				<script>
                    (function ($) {
                        setTimeout(function () {
                            $('.thegem-te-portfolio-gallery.<?= $uniqid ?> .portfolio-carousel').initPortfolioGallery();
                        }, 100);
                    })(jQuery);
				</script>
			<?php endif; ?>
			<?php endif; ?>

			<?php if ($params['type'] == 'grid'): ?>
				<div class="portfolio-grid">
					<?= $items_output ?>
				</div>
			<?php endif; ?>
		</div>

		<?php

		//Custom Styles
		$custom_css = '';
		$customize = '.thegem-te-portfolio-gallery.' . $uniqid;
		$resolution = array('desktop', 'tablet', 'mobile');
		$directions = array('top', 'bottom', 'left', 'right');

		// Images
		foreach ($resolution as $res) {
			if (!empty($params['image_height_' . $res]) || strcmp($params['image_height_' . $res], '0') === 0) {
				$result = str_replace(' ', '', $params['image_height_' . $res]);
				$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
				if ($res == 'desktop') {
					$custom_css .= $customize . ' .item-inner {max-height:' . $result . $unit . ';}';
				} else {
					$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
					$custom_css .= '@media screen and (' . $width . ') {' . $customize . ' .item-inner {max-height:' . $result . $unit . ';}}';
				}
			}

			if (!empty($params['image_radius_' . $res]) || strcmp($params['image_radius_' . $res], '0') === 0) {
				$result = str_replace(' ', '', $params['image_radius_' . $res]);
				$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px';
				if ($res == 'desktop') {
					$custom_css .= $customize . ' .item-inner {border-radius:' . $result . $unit . ';}';
				} else {
					$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
					$custom_css .= '@media screen and (' . $width . ') {' . $customize . ' .item-inner {border-radius:' . $result . $unit . ';}}';
				}
			}
		}
		if (!empty($params['image_shadow'])) {
			$horizontal = !empty($params['image_shadow_horizontal']) ? $params['image_shadow_horizontal'] . 'px' : '0px';
			$vertical = !empty($params['image_shadow_vertical']) ? $params['image_shadow_vertical'] . 'px' : '0px';
			$blur = !empty($params['image_shadow_blur']) ? $params['image_shadow_blur'] . 'px' : '0px';
			$spread = !empty($params['image_shadow_spread']) ? $params['image_shadow_spread'] . 'px' : '0px';
			$color = !empty($params['image_shadow_color']) ? $params['image_shadow_color'] : 'transparent';
			$position = $params['image_shadow_position'] == 'inset' ? 'inset' : null;

			if ($position == 'inset') {
				$custom_css .= $customize . ' .item-inner:before {content: ""; box-shadow:' . $horizontal . ' ' . $vertical . ' ' . $blur . ' ' . $spread . ' ' . $color . ' ' . $position . ';}';
			} else {
				$custom_css .= $customize . ' .item-inner {box-shadow:' . $horizontal . ' ' . $vertical . ' ' . $blur . ' ' . $spread . ' ' . $color . ';}';
			}
		}
		if (!empty($params['image_ratio'])) {
			$custom_css .= $customize . ' .item-inner .image-inner {aspect-ratio: ' . $params['image_ratio'] . '!important;}';
		}

		// Arrows
		if (!empty($params['arrows_top_spacing'])) {
			$custom_css .= $customize . ' .portfolio-carousel-nav.carousel-nav--space-between .nav-item {margin-top: ' . $params['arrows_top_spacing'] . 'px;}';
		}
		if (!empty($params['arrows_bottom_spacing'])) {
			$custom_css .= $customize . ' .portfolio-carousel-nav.carousel-nav--bottom-centered {bottom: ' . $params['arrows_bottom_spacing'] . 'px;}';
		}
		if (!empty($params['arrows_side_spacing'])) {
			$custom_css .= $customize . ' .portfolio-carousel-nav.carousel-nav--space-between .nav-prev {left: ' . $params['arrows_side_spacing'] . 'px;}';
			$custom_css .= $customize . ' .portfolio-carousel-nav.carousel-nav--space-between .nav-next {right: ' . $params['arrows_side_spacing'] . 'px;}';
			$custom_css .= $customize . ' .portfolio-carousel-nav.carousel-nav--bottom-centered .nav-prev {margin-right: ' . round($params['arrows_side_spacing'] / 2, 2) . 'px;}';
			$custom_css .= $customize . ' .portfolio-carousel-nav.carousel-nav--bottom-centered .nav-next {margin-left: ' . round($params['arrows_side_spacing'] / 2, 2) . 'px;}';
		}
		if (!empty($params['arrows_prev_icon_size']) || $params['arrows_prev_icon_size'] == '0') {
			$custom_css .= $customize . ' .portfolio-carousel-nav .nav-prev i {font-size: ' . $params['arrows_prev_icon_size'] . 'px !important;}';
		}
		if (!empty($params['arrows_next_icon_size']) || $params['arrows_next_icon_size'] == '0') {
			$custom_css .= $customize . ' .portfolio-carousel-nav .nav-next i {font-size: ' . $params['arrows_next_icon_size'] . 'px !important;}';
		}
		if (!empty($params['arrows_width']) || $params['arrows_width'] == '0') {
			$custom_css .= $customize . ' .portfolio-carousel-nav .icon {min-width: ' . $params['arrows_width'] . 'px !important;}';
		}
		if (!empty($params['arrows_height']) || $params['arrows_height'] == '0') {
			$custom_css .= $customize . ' .portfolio-carousel-nav .icon {min-height: ' . $params['arrows_height'] . 'px !important;}';
		}
		if (!empty($params['arrows_border_width']) || $params['arrows_border_width'] == '0') {
			$custom_css .= $customize . ' .portfolio-carousel-nav .icon {border-width: ' . $params['arrows_border_width'] . 'px !important;}';
		}
		if (!empty($params['arrows_border_radius']) || $params['arrows_border_radius'] == '0') {
			$custom_css .= $customize . ' .portfolio-carousel-nav .icon {border-radius: ' . $params['arrows_border_radius'] . 'px !important;}';
		}
		if (!empty($params['arrows_color'])) {
			$custom_css .= $customize . ' .portfolio-carousel-nav .icon {color: ' . $params['arrows_color'] . ' !important;}';
		}
		if (!empty($params['arrows_color_hover'])) {
			$custom_css .= $customize . ' .portfolio-carousel-nav .icon:hover  {color: ' . $params['arrows_color_hover'] . ' !important;}';
		}
		if (!empty($params['arrows_background_color'])) {
			$custom_css .= $customize . ' .portfolio-carousel-nav .icon {background-color: ' . $params['arrows_background_color'] . ' !important;}';
		}
		if (!empty($params['arrows_background_color_hover'])) {
			$custom_css .= $customize . ' .portfolio-carousel-nav .icon:hover {background-color: ' . $params['arrows_background_color_hover'] . ' !important;}';
		}
		if (!empty($params['arrows_border_color'])) {
			$custom_css .= $customize . ' .portfolio-carousel-nav .icon {border-color: ' . $params['arrows_border_color'] . ' !important;}';
		}
		if (!empty($params['arrows_border_color_hover'])) {
			$custom_css .= $customize . ' .portfolio-carousel-nav .icon:hover {border-color: ' . $params['arrows_border_color_hover'] . ' !important;}';
		}

		// Dots
		if (!empty($params['dots_size'])) {
			$custom_css .= $customize . ' .portfolio-carousel .owl-dots .owl-dot {width: ' . $params['dots_size'] . 'px; height: ' . $params['dots_size'] . 'px;}';
		}
		if (!empty($params['dots_top_spacing'])) {
			$custom_css .= $customize . ' .portfolio-carousel .owl-dots {margin-top: ' . $params['dots_top_spacing'] . 'px;}';
		}
		if (!empty($params['dots_side_spacing'])) {
			$custom_css .= $customize . ' .portfolio-carousel .owl-dots .owl-dot {margin-left: ' . round($params['dots_side_spacing'] / 2, 2) . 'px;}';
			$custom_css .= $customize . ' .portfolio-carousel .owl-dots .owl-dot {margin-right: ' . round($params['dots_side_spacing'] / 2, 2) . 'px;}';
		}
		if (!empty($params['dots_color'])) {
			$custom_css .= $customize . ' .portfolio-carousel .owl-dots .owl-dot {background-color: ' . $params['dots_color'] . ';}';
		}
		if (!empty($params['dots_color_hover'])) {
			$custom_css .= $customize . ' .portfolio-carousel .owl-dots .owl-dot:hover {background-color: ' . $params['dots_color_hover'] . ';}';
		}
		if (!empty($params['dots_color_active'])) {
			$custom_css .= $customize . ' .portfolio-carousel .owl-dots .owl-dot.active {background-color: ' . $params['dots_color_active'] . ';}';
		}

		// Grid
		foreach ($resolution as $res) {
			if (!empty($params['grid_columns_' . $res]) && !empty($params['grid_gaps_' . $res])) {
				if ($res == 'desktop') {
					$custom_css .= $customize . ' .portfolio-grid {margin: -' . round($params['grid_gaps_' . $res] / 2) . 'px;}';
				} else {
					$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
					$custom_css .= '@media screen and (' . $width . ') {' . $customize . ' .portfolio-grid {margin: -' . round($params['grid_gaps_' . $res] / 2) . 'px;}}';
				}
			}

			if (!empty($params['grid_columns_' . $res])) {
				if ($res == 'desktop') {
					$custom_css .= $customize . ' .portfolio-grid .item {width: ' . round(100 / mb_substr($params['grid_columns_' . $res], 0, 1), 4) . '%;}';
				} else {
					$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
					$custom_css .= '@media screen and (' . $width . ') {' . $customize . ' .portfolio-grid .item {width: ' . round(100 / mb_substr($params['grid_columns_' . $res], 0, 1), 4) . '%;}}';
				}
			}

			if (!empty($params['grid_gaps_' . $res])) {
				if ($res == 'desktop') {
					$custom_css .= $customize . ' .portfolio-grid .item {padding: ' . round($params['grid_gaps_' . $res] / 2) . 'px;}';
				} else {
					$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
					$custom_css .= '@media screen and (' . $width . ') {' . $customize . ' .portfolio-grid .item {padding: ' . round($params['grid_gaps_' . $res] / 2) . 'px;}}';
				}
			}

			if (!empty($params['grid_image_count_' . $res])) {
				if ($res == 'desktop') {
					$custom_css .= $customize . ' .portfolio-grid .item:nth-child(n+' . ($params['grid_image_count_' . $res] + 1) . ') {display: none !important;}';
				} else {
					$width = ($res == 'tablet' ? 'max-width: 1023px' : 'max-width: 767px');
					$custom_css .= '@media screen and (' . $width . ') {' . $customize . ' .portfolio-grid .item:nth-child(n+' . ($params['grid_image_count_' . $res] + 1) . ') {display: none !important;}}';
				}
			}
		}

		$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));

		// Print custom css
		$css_output = '';
		if (!empty($custom_css)) {
			$css_output = '<style>' . $custom_css . '</style>';
		}

		$return_html = $css_output . $return_html;
		return thegem_templates_close_portfolio($this->get_name(), $this->shortcode_settings(), $return_html);
	}

	public function set_layout_params()
	{
		$result = array();
		$group = __('General', 'thegem');
		$resolutions = array('desktop', 'tablet', 'mobile');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Layout', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Gallery Type', 'thegem'),
			'param_name' => 'type',
			'value' => array_merge(array(
					__('Carousel Grid', 'thegem') => 'carousel',
					__('Grid', 'thegem') => 'grid')
			),
			'std' => 'carousel',
			'dependency' => array(
				'callback' => 'thegem_te_portfolio_gallery_callback'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('Columns', 'thegem'),
			'param_name' => 'delimiter_heading_two_level_panel',
			'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
			'group' => $group
		);
		foreach ($resolutions as $res) {
			$std = '';
			if ($res == 'desktop') {
				$std = '4';
			} else if ($res == 'tablet') {
				$std = '3';
			} else {
				$std = '2';
			}

			$result[] = array(
				'type' => 'dropdown',
				'heading' => __(ucfirst($res), 'thegem'),
				'param_name' => 'carousel_columns_' . $res,
				'value' => array_merge(array(
					__('1x columns', 'thegem') => '1',
					__('2x columns', 'thegem') => '2',
					__('3x columns', 'thegem') => '3',
					__('4x columns', 'thegem') => '4',
					__('5x columns', 'thegem') => '5',
					__('6x columns', 'thegem') => '6',
				)),
				'std' => $std,
				'dependency' => array(
					'element' => 'type',
					'value' => array('carousel')
				),
				'edit_field_class' => 'vc_column vc_col-sm-4',
				'group' => $group
			);

			$result[] = array(
				'type' => 'dropdown',
				'heading' => __(ucfirst($res), 'thegem'),
				'param_name' => 'grid_columns_' . $res,
				'value' => array_merge(array(
					__('1x columns', 'thegem') => '1',
					__('2x columns', 'thegem') => '2',
					__('3x columns', 'thegem') => '3',
					__('4x columns', 'thegem') => '4',
					__('5x columns', 'thegem') => '5',
					__('6x columns', 'thegem') => '6',
				)),
				'std' => $std,
				'dependency' => array(
					'element' => 'type',
					'value' => array('grid')
				),
				'edit_field_class' => 'vc_column vc_col-sm-4',
				'group' => $group
			);
		}

		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('Gaps', 'thegem'),
			'param_name' => 'delimiter_heading_two_level_panel',
			'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
			'group' => $group
		);
		foreach ($resolutions as $res) {
			$result[] = array(
				"type" => "textfield",
				'heading' => __(ucfirst($res), 'thegem'),
				'param_name' => 'carousel_gaps_' . $res,
				'dependency' => array(
					'element' => 'type',
					'value' => array('carousel')
				),
				'std' => '',
				"edit_field_class" => "vc_column vc_col-sm-4",
				'group' => $group
			);

			$result[] = array(
				"type" => "textfield",
				'heading' => __(ucfirst($res), 'thegem'),
				'param_name' => 'grid_gaps_' . $res,
				'std' => '',
				'dependency' => array(
					'element' => 'type',
					'value' => array('grid')
				),
				"edit_field_class" => "vc_column vc_col-sm-4",
				'group' => $group
			);
		}

		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('Max. Number of Images', 'thegem'),
			'param_name' => 'delimiter_heading_number_of_image',
			'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
			'dependency' => array(
				'element' => 'type',
				'value' => array('grid')
			),
			'group' => $group
		);
		foreach ($resolutions as $res) {
			$result[] = array(
				"type" => "textfield",
				'heading' => __(ucfirst($res), 'thegem'),
				'param_name' => 'grid_image_count_' . $res,
				'dependency' => array(
					'element' => 'type',
					'value' => array('grid')
				),
				"edit_field_class" => "vc_column vc_col-sm-4",
				'group' => $group
			);
		}

		$result[] = array(
			"type" => "textfield",
			'heading' => __('Image Ratio', 'thegem'),
			'param_name' => 'image_ratio',
			'std' => '1',
			"edit_field_class" => "vc_column vc_col-sm-12",
			'description' => __('Specify a decimal value, i.e. instead of 3:4, specify 0.75. Use a dot as a decimal separator. Leave blank to show the original image ratio.', 'thegem'),
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Lightbox Gallery', 'thegem'),
			'param_name' => 'lightbox',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Autoplay', 'thegem'),
			'param_name' => 'autoplay',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '0',
			'dependency' => array(
				'element' => 'type',
				'value' => array('carousel')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			"type" => "textfield",
			'heading' => __('Autoplay Speed (ms)', 'thegem'),
			'param_name' => 'autoplay_speed',
			'dependency' => array(
				'element' => 'autoplay',
				'value' => '1'
			),
			'std' => '5000',
			"edit_field_class" => "vc_column vc_col-sm-6",
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Include Featured Image', 'thegem'),
			'param_name' => 'include_featured_image',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '0',
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Slider Loop', 'thegem'),
			'param_name' => 'loop',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '0',
			'group' => $group
		);

		return $result;
	}

	public function set_image_params()
	{
		$result = array();
		$group = __('General', 'thegem');
		$resolutions = array('desktop', 'tablet', 'mobile');
		$directions = array('top', 'bottom', 'left', 'right');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Image Container', 'thegem'),
			'param_name' => 'layout_delim_head',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);

		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('Height', 'thegem'),
			'param_name' => 'delimiter_heading_two_level_panel',
			'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
			'group' => $group
		);
		foreach ($resolutions as $res) {
			$result[] = array(
				'type' => 'textfield',
				'heading' => __(ucfirst($res), 'thegem'),
				'param_name' => 'image_height_' . $res,
				'value' => '',
				'edit_field_class' => 'vc_column vc_col-sm-4',
				'group' => $group
			);
		}

		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('Radius', 'thegem'),
			'param_name' => 'delimiter_heading_two_level_panel',
			'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
			'group' => $group
		);
		foreach ($resolutions as $res) {
			$result[] = array(
				'type' => 'textfield',
				'heading' => __(ucfirst($res), 'thegem'),
				'param_name' => 'image_radius_' . $res,
				'value' => '',
				'edit_field_class' => 'vc_column vc_col-sm-4',
				'group' => $group
			);
		}

		$result[] = array(
			'type' => 'thegem_delimeter_heading_two_level',
			'heading' => __('Shadow', 'thegem'),
			'param_name' => 'delimiter_heading_two_level_panel',
			'edit_field_class' => 'thegem-param-delimeter-heading-two-level vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Enable Shadow', 'thegem'),
			'param_name' => 'image_shadow',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '0',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Shadow color', 'thegem'),
			'param_name' => 'image_shadow_color',
			'dependency' => array(
				'element' => 'image_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'std' => 'rgba(0, 0, 0, 0.15)',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Position', 'thegem'),
			'param_name' => 'image_shadow_position',
			'value' => array(
				__('Outline', 'thegem') => 'outline',
				__('Inset', 'thegem') => 'inset'
			),
			'dependency' => array(
				'element' => 'image_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'std' => 'outline',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Horizontal', 'thegem'),
			'param_name' => 'image_shadow_horizontal',
			'dependency' => array(
				'element' => 'image_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-3',
			'std' => '0',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Vertical', 'thegem'),
			'param_name' => 'image_shadow_vertical',
			'dependency' => array(
				'element' => 'image_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-3',
			'std' => '5',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Blur', 'thegem'),
			'param_name' => 'image_shadow_blur',
			'dependency' => array(
				'element' => 'image_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-3',
			'std' => '5',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Spread', 'thegem'),
			'param_name' => 'image_shadow_spread',
			'dependency' => array(
				'element' => 'image_shadow',
				'not_empty' => true,
			),
			'edit_field_class' => 'vc_column vc_col-sm-3',
			'std' => '-5',
			'group' => $group
		);

		return $result;
	}

	public function set_arrows_params()
	{
		$result = array();
		$group = __('General', 'thegem');
		$arrows = array('prev', 'next');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Arrows', 'thegem'),
			'param_name' => 'layout_delim_head_arrows',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Arrows', 'thegem'),
			'param_name' => 'arrows',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '1',
			'dependency' => array(
				'element' => 'type',
				'value' => 'carousel'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Arrows Type', 'thegem'),
			'param_name' => 'arrows_type',
			'value' => array(
				__('Simple', 'thegem') => 'simple',
				__('Round Border', 'thegem') => 'round',
				__('Square Border', 'thegem') => 'square',
			),
			'std' => 'simple',
			'dependency' => array(
				'element' => 'arrows',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Arrows Icons', 'thegem'),
			'param_name' => 'arrows_icons',
			'value' => array(
				__('Default', 'thegem') => '',
				__('Custom', 'thegem') => 'custom',
			),
			'dependency' => array(
				'element' => 'arrows',
				'value' => '1'
			),
			'std' => 'default',
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		foreach ($arrows as $arrow) {
			$result[] = array(
				'type' => 'dropdown',
				'heading' => __('Arrow ' . ucfirst($arrow) . ' Icon Pack', 'thegem'),
				'param_name' => 'arrows_' . $arrow . '_icon_pack',
				'value' => array_merge(array(
					__('Elegant', 'thegem') => 'elegant',
					__('Material Design', 'thegem') => 'material',
					__('FontAwesome', 'thegem') => 'fontawesome',
					__('Header Icons', 'thegem') => 'thegem-header',
					__('Additional', 'thegem') => 'thegemdemo'),
					thegem_userpack_to_dropdown()
				),
				'std' => 'elegant',
				'dependency' => array(
					'element' => 'arrows_icons',
					'value' => 'custom'
				),
				'edit_field_class' => 'vc_column vc_col-sm-12',
				'group' => $group
			);
			$result[] = array(
				'type' => 'thegem_icon',
				'heading' => __('Arrow ' . ucfirst($arrow) . ' Icon', 'thegem'),
				'param_name' => 'arrows_' . $arrow . '_icon_elegant',
				'icon_pack' => 'elegant',
				'dependency' => array(
					'element' => 'arrows_' . $arrow . '_icon_pack',
					'value' => array('elegant')
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			$result[] = array(
				'type' => 'thegem_icon',
				'heading' => __('Arrow ' . ucfirst($arrow) . ' Icon', 'thegem'),
				'param_name' => 'arrows_' . $arrow . '_icon_material',
				'icon_pack' => 'material',
				'dependency' => array(
					'element' => 'arrows_' . $arrow . '_icon_pack',
					'value' => array('material')
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			$result[] = array(
				'type' => 'thegem_icon',
				'heading' => __('Arrow ' . ucfirst($arrow) . ' Icon', 'thegem'),
				'param_name' => 'arrows_' . $arrow . '_icon_fontawesome',
				'icon_pack' => 'fontawesome',
				'dependency' => array(
					'element' => 'arrows_' . $arrow . '_icon_pack',
					'value' => array('fontawesome')
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			$result[] = array(
				'type' => 'thegem_icon',
				'heading' => __('Arrow ' . ucfirst($arrow) . ' Icon', 'thegem'),
				'param_name' => 'arrows_' . $arrow . '_icon_thegemdemo',
				'icon_pack' => 'thegemdemo',
				'dependency' => array(
					'element' => 'arrows_' . $arrow . '_icon_pack',
					'value' => array('thegemdemo')
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			$result[] = array(
				'type' => 'thegem_icon',
				'heading' => __('Arrow ' . ucfirst($arrow) . ' Icon', 'thegem'),
				'param_name' => 'arrows_' . $arrow . '_icon_thegemheader',
				'icon_pack' => 'thegem-header',
				'dependency' => array(
					'element' => 'arrows_' . $arrow . '_icon_pack',
					'value' => array('thegem-header')
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
			if (thegem_icon_userpack_enabled()) {
				$result[] = thegem_userpack_to_shortcode(array(
					'type' => 'thegem_icon',
					'heading' => __('Arrow ' . ucfirst($arrow) . ' Icon', 'thegem'),
					'param_name' => 'arrows_' . $arrow . '_icon_userpack',
					'icon_pack' => 'userpack',
					'dependency' => array(
						'element' => 'arrows_' . $arrow . '_icon_pack',
						'value' => array('userpack')
					),
					'edit_field_class' => 'vc_column vc_col-sm-6',
					'group' => $group
				));
			}
			$result[] = array(
				'type' => 'textfield',
				'heading' => __('Arrow ' . ucfirst($arrow) . ' Size', 'thegem'),
				'param_name' => 'arrows_' . $arrow . '_icon_size',
				'dependency' => array(
					'element' => 'arrows_icons',
					'value' => 'custom'
				),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'group' => $group
			);
		}

		$result[] = array(
			'type' => 'dropdown',
			'heading' => __('Position', 'thegem'),
			'param_name' => 'arrows_position',
			'value' => array(
				__('Left & Right', 'thegem') => 'space-between',
				__('Bottom Centered', 'thegem') => 'bottom-centered',
			),
			'std' => 'space-between',
			'dependency' => array(
				'element' => 'arrows',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-12',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Top Spacing', 'thegem'),
			'param_name' => 'arrows_top_spacing',
			'dependency' => array(
				'element' => 'arrows_position',
				'value' => 'space-between'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Bottom Spacing', 'thegem'),
			'param_name' => 'arrows_bottom_spacing',
			'dependency' => array(
				'element' => 'arrows_position',
				'value' => 'bottom-centered'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Side Spacing', 'thegem'),
			'param_name' => 'arrows_side_spacing',
			'dependency' => array(
				'element' => 'arrows',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Width', 'thegem'),
			'param_name' => 'arrows_width',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Height', 'thegem'),
			'param_name' => 'arrows_height',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Width', 'thegem'),
			'param_name' => 'arrows_border_width',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Border Radius', 'thegem'),
			'param_name' => 'arrows_border_radius',
			'value' => '',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Color', 'thegem'),
			'param_name' => 'arrows_color',
			'dependency' => array(
				'element' => 'arrows',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Color on Hover', 'thegem'),
			'param_name' => 'arrows_color_hover',
			'dependency' => array(
				'element' => 'arrows',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color', 'thegem'),
			'param_name' => 'arrows_background_color',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Background Color on Hover', 'thegem'),
			'param_name' => 'arrows_background_color_hover',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color', 'thegem'),
			'param_name' => 'arrows_border_color',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Border Color on Hover', 'thegem'),
			'param_name' => 'arrows_border_color_hover',
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'dependency' => array(
				'element' => 'arrows_type',
				'value' => array('round', 'square')
			),
			'group' => $group
		);

		return $result;
	}

	public function set_dots_params()
	{
		$result = array();
		$group = __('General', 'thegem');

		$result[] = array(
			'type' => 'thegem_delimeter_heading',
			'heading' => __('Dots Navigation', 'thegem'),
			'param_name' => 'layout_delim_head_dots',
			'edit_field_class' => 'thegem-param-delimeter-heading no-top-padding margin-top vc_column vc_col-sm-12 capitalize',
			'group' => $group
		);

		$result[] = array(
			'type' => 'checkbox',
			'heading' => __('Dots Navigation', 'thegem'),
			'param_name' => 'dots',
			'value' => array(__('Yes', 'thegem') => '1'),
			'std' => '0',
			'dependency' => array(
				'element' => 'type',
				'value' => 'carousel'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Dots Size', 'thegem'),
			'param_name' => 'dots_size',
			'dependency' => array(
				'element' => 'dots',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Top Spacing', 'thegem'),
			'param_name' => 'dots_top_spacing',
			'dependency' => array(
				'element' => 'dots',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'textfield',
			'heading' => __('Side Spacing', 'thegem'),
			'param_name' => 'dots_side_spacing',
			'dependency' => array(
				'element' => 'dots',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Dots Normal Color', 'thegem'),
			'param_name' => 'dots_color',
			'dependency' => array(
				'element' => 'dots',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Dots Hover Color', 'thegem'),
			'param_name' => 'dots_color_hover',
			'dependency' => array(
				'element' => 'dots',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);

		$result[] = array(
			'type' => 'colorpicker',
			'heading' => __('Dots Active Color', 'thegem'),
			'param_name' => 'dots_color_active',
			'dependency' => array(
				'element' => 'dots',
				'value' => '1'
			),
			'edit_field_class' => 'vc_column vc_col-sm-4',
			'group' => $group
		);

		return $result;
	}

	public function shortcode_settings()
	{
		$category = __('Single post', 'thegem');
		$description = __('Post Gallery (Single Post Builder)', 'thegem');
		if($this->is_template()) {
			$category = __('Portfolio Page Builder', 'thegem');
			$description = __('Post Gallery (Portfolio Page Builder)', 'thegem');
		} elseif($this->is_template('single-post')) {
			$category = __('Single Post Builder', 'thegem');
		}
		return array(
			'name' => __('Post Gallery', 'thegem'),
			'base' => 'thegem_te_portfolio_gallery',
			'icon' => 'thegem-icon-wpb-ui-element-portfolio-gallery',
			'category' => $category,
			'description' => $description,
			'params' => array_merge(

				/* General - Layout */
				$this->set_layout_params(),

				/* Style - Image Container */
				$this->set_image_params(),

				/* Style - Arrows */
				$this->set_arrows_params(),

				/* Style - Dots */
				$this->set_dots_params(),

				/* Extra Options */
				thegem_set_elements_extra_options(),

				/* Responsive Options */
				thegem_set_elements_responsive_options()
			),
		);
	}
}

$templates_elements['thegem_te_portfolio_gallery'] = new TheGem_Template_Element_Portfolio_Gallery();
$templates_elements['thegem_te_portfolio_gallery']->init();
