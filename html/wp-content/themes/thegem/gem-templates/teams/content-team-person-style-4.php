<?php
	$thegem_item_data = thegem_get_sanitize_team_person_data(get_the_ID());
	$thegem_link_start = '';
	$thegem_link_end = '';
	$thegem_image_start = '';
	$thegem_image_end = '';
	if($thegem_link = thegem_get_data($thegem_item_data, 'link')) {
		$thegem_link_start = '<a class="team-person-link" href="'.esc_url($thegem_link).'" target="'.esc_attr(thegem_get_data($thegem_item_data, 'link_target')).'">';
		$thegem_link_end = '</a>';
		$thegem_image_start = '<span>';
		$thegem_image_end = '</span>';
	}
	$thegem_grid_class = '';
	if($params['columns'] == '1') {
		$thegem_grid_class = 'col-xs-12';
	} elseif($params['columns'] == '2') {
		$thegem_grid_class = 'col-sm-6 col-xs-12';
	} elseif($params['columns'] == '3') {
		$thegem_grid_class = 'col-md-4 col-sm-6 col-xs-12';
	} else {
		$thegem_grid_class = 'col-md-3 col-sm-6 col-xs-12';
	}
	$thegem_email_link = thegem_get_data($thegem_item_data, 'email', '', '<div class="team-person-email date-color"><a '.($params['mail_color'] ? 'style="color: '.esc_attr($params['mail_color']).'"' : '').'  class="date-color" href="mailto:', '"></a></div>');
	if($thegem_item_data['hide_email']) {
		$thegem_email = explode('@', $thegem_item_data['email']);
		if(count($thegem_email) == 2) {
			$thegem_email_link = '<div class="team-person-email"><a '.($params['mail_color'] ? 'style="color: '.esc_attr($params['mail_color']).'"' : '').'  href="#" class="hidden-email date-color" data-name="'.$thegem_email[0].'" data-domain="'.$thegem_email[1].'"></a></div>';
		}
	}
	$thegem_socials_block = '';
	foreach(thegem_team_person_socials_list() as $thegem_key => $thegem_value) {
		if($thegem_item_data['social_link_'.$thegem_key]) {
			$protocol = $thegem_key === 'skype' ? array('skype') : '';
			thegem_additionals_socials_enqueue_style($thegem_key);
			$thegem_socials_block .= '<a '.($params['socials_color'] ? 'style="color: '.esc_attr($params['socials_color']).'"' : '').'  title="'.esc_attr($thegem_value).'" target="_blank" href="'.esc_url($thegem_item_data['social_link_'.$thegem_key], $protocol).'" class="socials-item"><i class="socials-item-icon social-item-rounded '.esc_attr($thegem_key).'"></i></a>';
		}
	}
	$socials_list = thegem_socials_icons_list();
	foreach($thegem_item_data['additional_social_links'] as $thegem_social) {
		$thegem_socials_block .= '<a '.($params['socials_color'] ? 'style="color: '.esc_attr($params['socials_color']).'"' : '').' title="'.esc_attr($socials_list[$thegem_social['social']]).'" target="_blank" href="'.esc_url($thegem_social['link']).'" class="socials-item"><i class="socials-item-icon social-item-rounded '.esc_attr($thegem_social['social']).'"></i></a>';
	}
?>

<div class="<?php echo esc_attr($thegem_grid_class); ?> inline-column">
	<div id="post-<?php the_ID(); ?>" <?php post_class(array('team-person', 'bordered-box',($params['centered'] ? 'centered-box' : ''))); ?> style="<?php if($params['background_color']) : ?>background-color: <?php echo $params['background_color'] ?>;<?php endif; ?> <?php if($params['border_color']) : ?>border-color: <?php echo $params['border_color'] ?>;<?php endif; ?> <?php if($params['bottom_border_color']) : ?>border-bottom-color: <?php echo $params['bottom_border_color'] ?>;<?php endif; ?>">
		<?php if(has_post_thumbnail()) : ?><div class="team-person-image"><?php echo $thegem_image_start; thegem_post_thumbnail('thegem-person-160', false, 'img-responsive', array('srcset' => array('2x' => 'thegem-person'))); echo $thegem_image_end; ?></div><?php endif; ?>
		<div class="team-person-info clearfix">
			<?php echo thegem_get_data($thegem_item_data, 'name', '', '<div class="team-person-name styled-subtitle" '.($params['name_color'] ? 'style="color: '.esc_attr($params['name_color']).'"' : '').'>', '</div>'); ?>
			<?php echo thegem_get_data($thegem_item_data, 'position', '', '<div class="team-person-position date-color" '.($params['position_color'] ? 'style="color: '.esc_attr($params['position_color']).'"' : '').'>', '</div>'); ?>
			<?php if(!empty($thegem_item_data['phone'])) : ?>
				<div class="gem-styled-color-1"><div class="team-person-phone title-h5"><a href="<?php echo esc_url('tel:'.$thegem_item_data['phone']); ?>"<?php echo ($params['tel_color'] ? ' style="color: '.esc_attr($params['tel_color']).'"' : ''); ?>><?php echo esc_html($thegem_item_data['phone']); ?></a></div></div>
			<?php endif; ?>
			<?php if($thegem_socials_block) : ?><div class="socials team-person-socials socials-colored-hover"><?php echo $thegem_socials_block; ?></div><?php endif; ?>
			<?php echo $thegem_email_link; ?>
		</div>
		<?php echo $thegem_link_start.$thegem_link_end; ?>
	</div>
</div>
