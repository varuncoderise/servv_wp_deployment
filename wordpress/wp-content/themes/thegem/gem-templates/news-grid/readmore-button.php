<?php
if (!defined('ABSPATH')) exit;
if ($params['readmore_button_size'] == 'default') {
	$size = $params['columns'] == '1x' ? 'small' : 'tiny';
} else {
	$size = $params['readmore_button_size'];
} ?>

<div class="gem-button-container gem-widget-button gem-button-position-inline">
	<a class="gem-button gem-button-size-<?php echo esc_attr( $size ); ?> gem-button-style-<?php echo esc_attr( $params['readmore_button_style'] ); ?>"
	   href="<?php echo get_the_permalink(); ?>">
			<span class="gem-inner-wrapper-btn">
				<span class="gem-text-button">
					<?php echo wp_kses( $params['readmore_button_text'], 'post' ); ?>
				</span>
			</span>
	</a>
</div>