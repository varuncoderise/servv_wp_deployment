<?php
/**
 * Partial: Footer Stylish Dark - Alt layout
 */
?>

	
	<footer class="main-footer dark stylish stylish-b">
	
		
		<?php if (is_active_sidebar('contentberg-instagram')): ?>
		
		<section class="mid-footer cf">
			<?php dynamic_sidebar('contentberg-instagram'); ?>
		</section>
		
		<?php endif; ?>
		
		<div class="bg-wrap">

			<?php if (Bunyad::options()->footer_upper): ?>	
			
			<section class="upper-footer">
			
				<div class="wrap">
					<?php if (is_active_sidebar('contentberg-footer')): ?>
					
					<ul class="widgets ts-row cf">
						<?php dynamic_sidebar('contentberg-footer'); ?>
					</ul>
					
					<?php endif; ?>
				</div>
			</section>
			
			<?php endif; ?>

		
			<div class="social-strip">
				<ul class="social-icons">
					
					<?php 
						
						/**
						 * Show Social icons
						 */
						$services = Bunyad::get('social')->get_services();
						$links    = Bunyad::options()->social_profiles;
						
						foreach ( (array) Bunyad::options()->footer_social as $icon):
							$social = $services[$icon];
							$url    = !empty($links[$icon]) ? $links[$icon] : '#';
						?>
							<li>
								<a href="<?php echo esc_url($url); ?>" class="social-link" target="_blank"><i class="fa fa-<?php echo esc_attr($social['icon']); ?>"></i>
									<span class="label"><?php echo esc_html($social['label']); ?></span></a>
							</li>
						
					<?php
						endforeach;
					?>		
				</ul>
			</div>
	
			
	
			<?php if (Bunyad::options()->footer_lower): ?>
			
			<section class="lower-footer cf">
				<div class="wrap">			
				
				<?php if (Bunyad::options()->footer_logo): ?>
					<div class="footer-logo">
						<img <?php
							/**
							 * Get escaped attributes and add optionally add srcset for retina
							 */ 
							Bunyad::markup()->attribs('footer-logo', array(
								'src'    => Bunyad::options()->footer_logo,
								'class'  => 'logo',
								'alt'    => get_bloginfo('name', 'display'),
								'srcset' => array(Bunyad::options()->footer_logo => '', Bunyad::options()->footer_logo_2x => '2x')
							)); ?> />
					</div>
						
				<?php endif;?>
				
					<div class="bottom cf">
						<p class="copyright"><?php 
							echo do_shortcode(
								wp_kses_post(Bunyad::options()->footer_copyright) 
							); ?>
						</p>

						
						<?php if (Bunyad::options()->footer_back_top): ?>
							<div class="to-top">
								<a href="#" class="back-to-top"><i class="fa fa-angle-up"></i> <?php esc_html_e('Top', 'contentberg'); ?></a>
							</div>
						<?php endif; ?>
						
					</div>
				</div>
			</section>
			
			<?php endif; ?>
		
		</div>
		
	</footer>