<?php
	global $thegem_page_title_template_data;
	$thegem_use_custom = get_post($thegem_page_title_template_data['title_template']);
	$thegem_q = new WP_Query(array('p' => $thegem_page_title_template_data['title_template'], 'post_type' => array('thegem_title', 'thegem_templates'), 'post_status' => array('publish', 'private')));
?>
<div id="page-title" class="page-title-block custom-page-title">
	<div class="fullwidth-content">
		<?php if($thegem_page_title_template_data['title_template'] && $thegem_use_custom && $thegem_q->have_posts()) : $thegem_q->the_post(); ?>
			<?php
				global $post;
				$post->post_content = str_replace(array('[vc_row ', '[vc_row]'), array('[vc_row template_fw="1" ', '[vc_row template_fw="1"]'), $post->post_content);
				setup_postdata($GLOBALS['post'] =& $post);
				the_content();
			?>
		<?php wp_reset_postdata(); endif; ?>
	</div>
	<div class="page-title-alignment-<?php echo $thegem_page_title_template_data['title_alignment']; ?>"><?php echo $thegem_page_title_template_data['breadcrumbs_output']; ?></div>
</div>
<?php
	if(!empty($GLOBALS['thagem_page_404'])) {
		$thegem_q = new WP_Query(array('page_id' => $GLOBALS['thagem_page_404']));
		$thegem_q->the_post();
	}