<?php
/**
 * Plugin Template: for Alternate Social share buttons on single page
 */

// Post and media URL
$services = Bunyad::get('cb_social')->share_services();
$active   = apply_filters(
	'bunyad_social_share_float_active',
	array('facebook', 'twitter', 'pinterest', 'email')
);

$services['pinterest']['icon'] = 'pinterest-p';

?>

<?php if (is_single()): ?>
	
	<div class="post-share-float share-float-a is-hidden cf">
	
		<?php if (Bunyad::options()->share_float_text): ?>
			<span class="share-text"><?php echo esc_html(Bunyad::options()->share_float_text); ?></span>
		<?php endif; ?>

		<div class="services">
		
		<?php 
			foreach ($active as $key): 

				if (!isset($services[$key])) {
					continue;
				}

				$service = $services[$key];
		?>
		
			<a href="<?php echo $service['url']; ?>" class="cf service <?php echo esc_attr($key); ?>" target="_blank" title="<?php echo esc_attr($service['label'])?>">
				<i class="fa fa-<?php echo esc_attr($service['icon']); ?>"></i>
				<span class="label"><?php echo esc_html($service['label']); ?></span>
			</a>
				
		<?php endforeach; ?>
		
		</div>
		
	</div>
	
<?php endif; ?>