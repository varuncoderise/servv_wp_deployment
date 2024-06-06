<?php
get_header(); ?>

	<div id="main-content" class="main-content">
		<div class="block-content">
			<div class="container">
				<div class="panel row">
					<div class="col-xs-12">
						<?php
						if ((defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()))) {
							while ( have_posts() ) : the_post();
								$popup_item_data = thegem_get_sanitize_popup_item_data( get_the_ID() ); ?>
								<style>
									.block-content .container {
										max-width: 100%;
									}
									<?php if (!empty($popup_item_data['popup_width_desktop'])) {
										$result = str_replace(' ', '', $popup_item_data['popup_width_desktop']);
										$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px'; ?>
										.block-content {
											width: <?php echo $result.$unit; ?>;
											margin: auto;
										}
									<?php } ?>
									<?php if (!empty($popup_item_data['popup_width_tablet'])) {
										$result = str_replace(' ', '', $popup_item_data['popup_width_tablet']);
										$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px'; ?>
										@media (max-width: 991px) {
											.block-content {
												width: <?php echo $result.$unit; ?>;
												margin: auto;
											}
										}
									<?php } ?>
									<?php if (!empty($popup_item_data['popup_width_mobile'])) {
										$result = str_replace(' ', '', $popup_item_data['popup_width_mobile']);
										$unit = substr($result, -1) == '%' || substr($result, -2) == 'px' ? '' : 'px'; ?>
										@media (max-width: 767px) {
											.block-content {
												width: <?php echo $result.$unit; ?>;
												margin: auto;
											}
										}
									<?php } ?>
								</style>
							<div>
								<?php the_content(); ?>
							</div>
								<?php

							endwhile;
						} else { ?>
							<a id="show-popup" class="gem-button gem-button-size-tiny gem-button-style-flat gem-button-text-weight-normal" href="#" target="_self">Show Popup</a>
						<?php } ?>
					</div>
				</div>
			</div>
		</div><!-- .block-content -->
	</div><!-- #main-content -->

<?php
get_footer();