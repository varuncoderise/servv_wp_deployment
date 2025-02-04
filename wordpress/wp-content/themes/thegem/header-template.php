<?php
	$thegem_page_id = is_singular() ? get_the_ID() : 0;
	$thegem_shop_page = 0;
	if(is_404() && get_post(thegem_get_option('404_page'))) {
		$thegem_page_id = thegem_get_option('404_page');
	}
	if(is_post_type_archive('product') && function_exists('wc_get_page_id')) {
		$thegem_page_id = wc_get_page_id('shop');
		$thegem_shop_page = 1;
	}
	$thegem_header_params = $thegem_effects_params = thegem_get_output_page_settings($thegem_page_id);
	if((is_archive() || is_home()) && !$thegem_shop_page) {
		if(is_tax('product_cat') || is_tax('product_tag')) {
			$thegem_header_params = $thegem_effects_params = thegem_get_output_page_settings(0, thegem_theme_options_get_page_settings('product_categories'), 'product_category');
		} else {
			$thegem_header_params = $thegem_effects_params = thegem_get_output_page_settings(0, thegem_theme_options_get_page_settings('blog'), 'blog');
		}
	}
	if(is_tax() || is_category() || is_tag()) {
		$thegem_term_id = get_queried_object()->term_id;
		if(get_term_meta($thegem_term_id , 'thegem_taxonomy_custom_page_options', true)) {
			$thegem_header_params = $thegem_effects_params = thegem_get_output_page_settings($thegem_term_id, array(), 'term');
		}
	}
	if (is_search()) {
		$thegem_header_params = $thegem_effects_params = thegem_get_output_page_settings(0, thegem_theme_options_get_page_settings('search'), 'search');
	}
	if($thegem_effects_params['effects_page_scroller']) {
		$thegem_header_params['header_hide_top_area'] = true;
		$thegem_header_params['header_hide_top_area_tablet'] = true;
		$thegem_header_params['header_hide_top_area_mobile'] = true;
		$thegem_header_params['header_transparent'] = true;
	}
	$thegem_header_light = $thegem_header_params['header_menu_logo_light'] ? '_light' : '';
	$hide_top_area = $thegem_header_params['header_hide_top_area'] && $thegem_header_params['header_hide_top_area_tablet'] && $thegem_header_params['header_hide_top_area_mobile'];
	if(thegem_get_option('header_layout') == 'vertical') {
		$thegem_header_params['header_transparent'] = false;
	}
	$logo_position = thegem_get_option('logo_position', 'left');
	if(thegem_get_option('logo_position', 'left') == 'menu_center' && !((has_nav_menu('primary') || $thegem_header_params['header_custom_menu']) && $thegem_header_params['menu_show'])) {
		$logo_position = 'center';
	}
	wp_enqueue_style('thegem_js_composer_front');
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<?php wp_head(); ?>
	<?php
		if (thegem_get_option('font_preload_enabled')) {
			$fonts = thegem_get_option('font_preload_preloaded_fonts');
			$additionalFonts = thegem_additionals_fonts();
			$sysFontUri = THEGEM_THEME_URI.'/fonts/';

			$sysFonts = array(
				'Thegem Icons' => $sysFontUri.'thegem-icons.woff',
				'Elegant Icons' => $sysFontUri.'elegant/ElegantIcons.woff',
				'Materialdesign Icons' => $sysFontUri.'material/materialdesignicons.woff',
				'Fontawesome Icons' => $sysFontUri.'fontawesome/fontawesome-webfont.woff',
				'Header Icons' => $sysFontUri.'thegem-header/thegem-header.woff',
				'Thegem Socials' => $sysFontUri.'thegem-socials.woff',
			);

			foreach(explode(',', $fonts) as $font) {
				$url = isset($sysFonts[$font]) ? $sysFonts[$font]:'';
				if (!$url) {
					foreach($additionalFonts as $additionalFont) {
						if ($additionalFont['font_name'] == $font && isset($additionalFont['font_url_woff'])) {
							$url = $additionalFont['font_url_woff'];
							break;
						}
					}
				}

				if ($url) {
					echo '<link rel="preload" as="font" crossorigin="anonymous" type="font/woff" href="'.$url."\">\n";
				}
			}
		}
	?>
	<style>.header-background:before {opacity: 1;}</style>
</head>

<?php
	$thegem_preloader_data = $thegem_header_params;
?>

<body <?php body_class(); ?>>

<?php do_action('gem_before_page_content'); ?>

<div id="page" class="layout-<?php echo esc_attr(thegem_get_option('page_layout_style', 'fullwidth')); ?><?php echo esc_attr(thegem_get_option('header_layout') == 'vertical' ? ' vertical-header' : '') ; ?> header-style-<?php echo esc_attr(thegem_get_option('header_layout') == 'vertical' || thegem_get_option('header_layout') == 'fullwidth_hamburger' ? 'vertical' : thegem_get_option('header_style')); ?>">

	<header id="site-header" class="site-header">
		<div class="header-wrapper"><div class="header-background">
			<div class="<?php echo (defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()) ? 'container' : 'fullwidth-content'); ?>">
				<div class="thegem-template-wrapper thegem-template-header thegem-template-<?php the_ID(); ?>">
					<?php
						while ( have_posts() ) : the_post();
							if(!(defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()))) {
								global $post;
								$post->post_content = str_replace(array('[vc_row ', '[vc_row]', '[vc_column ', '[vc_column]', '[vc_column_inner'), array('[vc_row template_fw="1" ', '[vc_row template_fw="1"]', '[vc_column template_flex="1" ', '[vc_column template_flex="1"]', '[vc_column_inner template_flex="1"'), $post->post_content);
								setup_postdata($GLOBALS['post'] =& $post);
							}
							$GLOBALS['thegem_template_type'] = 'header';
							the_content();
							unset($GLOBALS['thegem_template_type']);
						endwhile;
					?>
				</div>
			</div>
		</div></div>
	</header>
	<div id="main" class="site-main page__top-shadow visible">
