<?php 
/**
 * Site logo
 */
?>
	<div class="title">
		
		<a href="https://coderise.io" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
		
		<?php if (Bunyad::options()->image_logo): // custom logo ?>
			
			<?php Bunyad::get('helpers')->mobile_logo(); ?>
			
			<img <?php
				/**
				 * Get escaped attributes and add optionally add srcset for retina
				 */ 
				Bunyad::markup()->attribs('image-logo', array(
					'src'    => Bunyad::options()->image_logo,
					'class'  => 'logo-image',
					'alt'    => get_bloginfo('name', 'display'),
					'srcset' => array(Bunyad::options()->image_logo => '', Bunyad::options()->image_logo_2x => '2x')
				)); ?> />

		<?php else: ?>
			
			<span class="text-logo"><?php echo esc_html(get_bloginfo('name', 'display')); ?></span>
			
		<?php endif; ?>
		
		</a>
	
	</div>