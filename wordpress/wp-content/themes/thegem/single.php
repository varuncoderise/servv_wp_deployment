<?php

get_header(); ?>

<div id="main-content" class="main-content">

<?php
	$thegem_post_template_id = in_array(get_post_type(), array('post', 'thegem_news'), true) ? thegem_single_post_template() : 0;
	$thegem_protfolio_template_id = get_post_type() === 'thegem_pf_item' ? thegem_portfolio_template() : 0;
	$thegem_cpt_template_id = in_array(get_post_type(), thegem_get_available_po_custom_post_types(), true) ? thegem_cpt_template() : 0;
	while ( have_posts() ) : the_post();
		if(in_array(get_post_type(), array('post', 'thegem_news'), true) && $thegem_post_template_id) : ?>
			<?php echo thegem_page_title(); ?>
			<div class="block-content">
				<div class="fullwidth-content">
					<div class="thegem-template-wrapper thegem-template-single-post thegem-template-<?php echo esc_attr($thegem_post_template_id); ?>">
						<?php
							$template_custom_css = get_post_meta($thegem_post_template_id, '_wpb_shortcodes_custom_css', true) . get_post_meta($thegem_post_template_id, '_wpb_post_custom_css', true);
							if($template_custom_css) {
								echo '<style>' . $template_custom_css . '</style>';
							}
							$template = get_post($thegem_post_template_id);
							$template->post_content = str_replace(array('<p>[', ']</p>'), array('[', ']'), $template->post_content);
							$template->post_content = str_replace(array('[vc_row ', '[vc_row]', '[vc_section ', '[vc_section]'), array('[vc_row template_fw="1" ', '[vc_row template_fw="1"]','[vc_section template_fw="1" ', '[vc_section template_fw="1"]'), $template->post_content);
							$GLOBALS['thegem_template_type'] = 'single-post';
							echo do_shortcode($template->post_content);
							unset($GLOBALS['thegem_template_type']);
						?>
					</div>
				</div><!-- .container -->
			</div><!-- .block-content -->
		<?php elseif(get_post_type() === 'thegem_pf_item' && $thegem_protfolio_template_id) : ?>
			<?php echo thegem_page_title(); ?>
			<div class="block-content">
				<div class="fullwidth-content">
					<div class="thegem-template-wrapper thegem-template-portfolio thegem-template-<?php echo esc_attr($thegem_protfolio_template_id); ?>">
						<?php
							$template_custom_css = get_post_meta($thegem_protfolio_template_id, '_wpb_shortcodes_custom_css', true) . get_post_meta($thegem_protfolio_template_id, '_wpb_post_custom_css', true);
							if($template_custom_css) {
								echo '<style>' . $template_custom_css . '</style>';
							}
							$template = get_post($thegem_protfolio_template_id);
							$template->post_content = str_replace(array('<p>[', ']</p>'), array('[', ']'), $template->post_content);
							$template->post_content = str_replace(array('[vc_row ', '[vc_row]', '[vc_section ', '[vc_section]'), array('[vc_row template_fw="1" ', '[vc_row template_fw="1"]','[vc_section template_fw="1" ', '[vc_section template_fw="1"]'), $template->post_content);
							$GLOBALS['thegem_template_type'] = 'portfolio';
							echo do_shortcode($template->post_content);
							unset($GLOBALS['thegem_template_type']);
						?>
					</div>
				</div><!-- .container -->
			</div><!-- .block-content -->
		<?php elseif(in_array(get_post_type(), thegem_get_available_po_custom_post_types(), true) && $thegem_cpt_template_id) : ?>
			<?php echo thegem_page_title(); ?>
			<div class="block-content">
				<div class="fullwidth-content">
					<div class="thegem-template-wrapper thegem-template-cpt thegem-template-<?php echo esc_attr($thegem_cpt_template_id); ?>">
						<?php
							$template_custom_css = get_post_meta($thegem_cpt_template_id, '_wpb_shortcodes_custom_css', true) . get_post_meta($thegem_cpt_template_id, '_wpb_post_custom_css', true);
							if($template_custom_css) {
								echo '<style>' . $template_custom_css . '</style>';
							}
							$template = get_post($thegem_cpt_template_id);
							$template->post_content = str_replace(array('<p>[', ']</p>'), array('[', ']'), $template->post_content);
							$template->post_content = str_replace(array('[vc_row ', '[vc_row]', '[vc_section ', '[vc_section]'), array('[vc_row template_fw="1" ', '[vc_row template_fw="1"]','[vc_section template_fw="1" ', '[vc_section template_fw="1"]'), $template->post_content);
							$GLOBALS['thegem_template_type'] = 'cpt';
							echo do_shortcode($template->post_content);
							unset($GLOBALS['thegem_template_type']);
						?>
					</div>
				</div><!-- .container -->
			</div><!-- .block-content -->
		<?php else : if(in_array(get_post_type(), array_merge(array('post', 'thegem_pf_item', 'thegem_news'), thegem_get_available_po_custom_post_types()), true)) {
			get_template_part( 'content', 'page' );
		} else {
			get_template_part( 'content', get_post_format() );
		}
		endif;
	endwhile;
?>

</div><!-- #main-content -->

<?php
get_footer();
