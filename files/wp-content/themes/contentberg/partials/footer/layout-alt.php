<?php
/**
 * Partial: Alternate light footer
 */
?>
	<footer class="main-footer alt">
				
		<?php if (is_active_sidebar('contentberg-instagram')): ?>
		
		<section class="mid-footer mid-footer-six cf">
			<?php dynamic_sidebar('contentberg-instagram'); ?>
		</section>
		
		<?php endif; ?>
		
		
		<?php if (Bunyad::options()->footer_upper && is_active_sidebar('contentberg-footer')): ?>	
		
		<section class="upper-footer">
			<div class="wrap">
				
				<ul class="widgets ts-row cf">
					<?php dynamic_sidebar('contentberg-footer'); ?>
				</ul>

			</div>
		</section>
		
		<?php endif; ?>
		
		
		<?php if (Bunyad::options()->footer_lower): ?>
		
		<section class="lower-footer cf">
			<div class="wrap cf">
				<p class="copyright"><?php 
					echo do_shortcode(
						wp_kses_post(Bunyad::options()->footer_copyright) 
					); ?>
				</p>

				
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
		</section>
		
		<?php endif; ?>
	
	</footer>