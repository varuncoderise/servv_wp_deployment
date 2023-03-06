<?php
if (!defined('ABSPATH')) exit;
if ($settings['readmore_button_size'] == 'default') {
	$size = $settings['columns'] == '1x' ? 'small' : 'tiny';
} else {
	$size = $settings['readmore_button_size'];
} ?>

<div class="gem-button-container gem-widget-button gem-button-position-inline">
	<a class="gem-button gem-button-size-<?php echo esc_attr( $size ); ?> gem-button-style-<?php echo esc_attr( $settings['readmore_button_type'] ); ?>"
	   href="<?php echo get_the_permalink(); ?>">
			<span class="gem-inner-wrapper-btn">
				<span class="gem-text-button">
					<?php echo wp_kses( $settings['blog_readmore_button_text'], 'post' ); ?>
				</span>
			</span>
	</a>
</div>