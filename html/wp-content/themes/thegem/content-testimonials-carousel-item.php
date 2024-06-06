<?php
	$thegem_item_data = thegem_get_sanitize_testimonial_data(get_the_ID());

	$thegem_testimonial_size = 'thegem-person';
	switch ($params['image_size']) {
		case 'size-small':
			$thegem_testimonial_size .= '-80';
			break;

		case 'size-medium':
			$thegem_testimonial_size .= '-160';
			break;

		case 'size-large':
			$thegem_testimonial_size .= '-160';
			break;

		case 'size-xlarge':
			$thegem_testimonial_size .= '-240';
			break;
	}
?>

<?php

	$quote_block = '';
	if ($params['quote_color']) {
		$quote_block = '<span style="color: '.$params['quote_color'].' " class="custom-color-blockqute-mark">&#xe60c;</span>';
	}

	?>

<div id="post-<?php the_ID(); ?>" <?php post_class('gem-testimonial-item'); ?>>
	<?php if($thegem_item_data['link']) : ?><a href="<?php echo esc_url($thegem_item_data['link']); ?>" target="<?php echo esc_attr($thegem_item_data['link_target']); ?>"><?php endif; ?>
		<div class="gem-testimonial-wrapper  <?php if($params['quote_color']) : ?> quote-color-added <?php endif; ?>">
			<div class="gem-testimonial-image">
				<?php thegem_post_thumbnail($thegem_testimonial_size, false, 'img-responsive img-circle', array('srcset' => array('2x' => 'thegem-testimonial'))); ?>
			</div>
			<div class="gem-testimonial-content">

				<?php echo thegem_get_data($thegem_item_data, 'name', '', '<div class="gem-testimonial-name" '.($params['name_color'] ? 'style="color: '.esc_attr($params['name_color']).'"' : '').'>', '</div>'); ?>
				<?php echo thegem_get_data($thegem_item_data, 'company', '', '<div class="gem-testimonial-company" '.($params['company_color'] ? 'style="color: '.esc_attr($params['company_color']).'"' : '').'>', '</div>'); ?>
				<?php echo thegem_get_data($thegem_item_data, 'position', '', '<div class="gem-testimonial-position" '.($params['position_color'] ? 'style="color: '.esc_attr($params['position_color']).'"' : '').'>', '</div>'); ?>

				<div class="gem-testimonial-text" <?php if($params['text_color']) : ?>style="color: <?php echo $params['text_color'] ?>"<?php endif; ?>>
					<?php the_content(); ?>
					<?php if($params['style'] == 'style2' ) {echo $quote_block;}?>

				</div>
			</div>
			<?php if($params['style'] == 'style1' ) {echo $quote_block;}?>

	</div>

	<?php if($thegem_item_data['link']) : ?></a><?php endif; ?>
</div>

