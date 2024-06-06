<?php
get_header(); ?>

<div id="main-content" class="main-content">
	<div class="block-content">
		<div class="container">
			<div class="panel row">
				<div class="col-xs-12">
					<?php
						while ( have_posts() ) : the_post();
							the_content();
						endwhile;
					?>
				</div>
			</div>
		</div>
	</div><!-- .block-content -->
</div><!-- #main-content -->

<?php
get_footer();