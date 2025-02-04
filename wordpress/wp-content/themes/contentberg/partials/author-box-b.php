<?php 
/**
 * Partial template for Author Box - Style B 
 */

?>
	<div class="author-box author-box-b cf">
	
		<div class="image"><?php echo get_avatar(get_the_author_meta('user_email'), 96); ?></div>
		
		<div class="content">
		
			<span class="author">
				<?php the_author_posts_link(); ?>
			</span>
			
			<p class="text author-bio"><?php echo wp_kses_post(get_the_author_meta('description')); // sanitized html only ?></p>
			
			<ul class="social-icons">
			<?php 
			
				// author fields
				$fields = array(
					'url' => array('icon' => 'home', 'label' => esc_html__('Website', 'contentberg')),
					'bunyad_facebook' => array('icon' => 'facebook', 'label' => esc_html__('Facebook', 'contentberg')),
					'bunyad_twitter' => array('icon' => 'twitter', 'label' => esc_html__('Twitter', 'contentberg')),
					'bunyad_gplus' => array('icon' => 'google-plus', 'label' => esc_html__('Google+', 'contentberg')),
					'bunyad_pinterest' => array('icon' => 'pinterest-p', 'label' => esc_html__('Pinterest', 'contentberg')),
					'bunyad_instagram' => array('icon' => 'instagram', 'label' => esc_html__('Instagram', 'contentberg')),
					'bunyad_tumblr' => array('icon' => 'tumblr', 'label' => esc_html__('Tumblr', 'contentberg')),
					'bunyad_bloglovin' => array('icon' => 'heart', 'label' => esc_html__('BlogLovin', 'contentberg')),
					'bunyad_linkedin' => array('icon' => 'linkedin', 'label' => esc_html__('LinkedIn', 'contentberg')),
					'bunyad_dribbble' => array('icon' => 'dribbble', 'label' => esc_html__('Dribble', 'contentberg')),
				);
				
				$the_meta = '';
				foreach ($fields as $meta => $data): 
				
					if (!get_the_author_meta($meta)) {
						continue;
					}
					
					$type     = $data['icon'];
					$the_meta = get_the_author_meta($meta);
					
					if ($meta == 'bunyad_public_email') {
						$the_meta = 'mailto:' . $the_meta; // esc_url() below
					}
			?>
				
				<li>
					<a href="<?php echo esc_url($the_meta); ?>" class="fa fa-<?php echo esc_attr($type); ?>" title="<?php echo esc_attr($data['label']); ?>"> 
						<span class="visuallyhidden"><?php echo esc_html($data['label']); ?></span></a>				
				</li>
				
				
			<?php endforeach; ?>
			</ul>
			
		</div>
		
	</div>