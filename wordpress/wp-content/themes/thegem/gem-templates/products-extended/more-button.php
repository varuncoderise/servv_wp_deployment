<?php
if (!defined('ABSPATH')) exit; ?>

<div class="<?php echo $classes_container; ?>">

	<?php if ($separator_enabled) { ?>
	<div class="<?php echo $classes_separator; ?>">
		<?php include 'more-button-separator.php'; ?>
		<div class="gem-button-separator-button">
			<?php } ?>

			<button class="<?php echo $classes_button; ?>">
				<span class="gem-inner-wrapper-btn">
					<?php
					if ($params['more_icon_' . $params['more_icon_pack']] != '') { ?>
						<span class="gem-button-icon">
							<?php echo thegem_build_icon($params['more_icon_pack'], $params['more_icon_' . $params['more_icon_pack']]); ?>
						</span>
					<?php } ?>
                	<span class="gem-text-button">
						<?php echo '<span>' . wp_kses($params['more_button_text'], 'post') . '</span>'; ?>
					</span>
				</span>
			</button>

			<?php if ($separator_enabled) { ?>
		</div>
		<?php include 'more-button-separator.php'; ?>
	</div>
<?php } ?>

</div>

















