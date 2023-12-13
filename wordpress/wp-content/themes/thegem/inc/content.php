<?php

function thegem_home_content_builder() {
	$home_content = thegem_get_option('home_content') ? json_decode(stripslashes(thegem_get_option('home_content')), TRUE) : array();
	$block_number = 1;
	if(count($home_content)) {
		foreach($home_content as $block) {
			$block_function = 'thegem_'.$block['block_type'].'_block';
			if(function_exists($block_function)) {
				echo '<section id="'.esc_attr(!empty($block['block_id']) ? $block['block_id'] : 'home-content-block-'.$block_number).'" class="home-content-block block-'.esc_attr($block['block_type']).'">';
				$block_function($block);
				echo '</section>';
				$block_number++;
			}
		}
	} else {
?>
	<div class="block-content">
		<div class="container">
			<h1 class="page-title"><?php esc_html_e('TheGem Theme', 'thegem') ?></h1>
			<div class="inner">
				<p><?php printf(wp_kses(__('Log in to <a href="%s">wordpress</a> admin and set up your starting page using <a href="%s">Home Constructor</a>.', 'thegem'), array('a' => array('href' => array()))), esc_url(admin_url('/')), esc_url(admin_url('admin.php?page=thegem-theme-options#home_constructor'))); ?></p>
				<p><?php esc_html_e('Please refer to TheGem documentation <b>(Getting Started &mdash; Setting Up Homepage)</b> in order to learn how to use Home Constructor.', 'thegem'); ?></p>
				<p><?php esc_html_e('Additionally you can use demo content included in TheGem to quickly setup a demo of your starting page.', 'thegem'); ?></p>
			</div>
		</div>
	</div>
<?php
	}
}


function thegem_content_block($params = array()) {
	$content_block_query = new WP_Query('page_id=' . $params['page']);
	if($content_block_query->have_posts()) {
		while ($content_block_query->have_posts()) {
			$content_block_query->the_post();
			get_template_part('content', 'page-content-block');
		}
	}
	wp_reset_postdata();
}

function thegem_pw_filter_widgets($sidebars_widgets) {
	if(!thegem_is_plugin_active('wp-page-widget/wp-page-widgets.php')) {
		return $sidebars_widgets;
	}
	global $post, $pagenow;
	$objTaxonomy = getTaxonomyAccess();
	if(
			(is_admin()
			&& !in_array($pagenow, array('post-new.php', 'post.php', 'edit-tags.php'))
			&& (!in_array($pagenow, array('admin.php')) && (isset($_GET['page']) && ($_GET['page'] == 'pw-front-page') || isset($_GET['page']) && $_GET['page'] == 'pw-search-page'))
			)
			|| (!is_admin() && !is_singular() && !is_search() && empty($objTaxonomy['taxonomy']) && !(is_home() && is_object($post) && $post->post_type == 'page'))
	) {

		return $sidebars_widgets;
	}


	// Search page
	if(is_search() || (is_admin() && (isset($_GET['page']) && $_GET['page'] == 'pw-search-page'))) {
		$enable_customize = get_option('_pw_search_page', true);
		$_sidebars_widgets = get_option('_search_page_sidebars_widgets', true);
	}


	// Post page
	elseif(empty($objTaxonomy['taxonomy'])) {
		//if admin alway use query string post = ID
		//Fix conflic when other plugins use query post after load editing post!

		if(is_object($post) && isset($_GET['post'])) {
			$postID = $_GET['post'];
		}
		if(is_admin() && isset($postID)) {
			if(!is_object($post)) $post = new stdClass();
				$post->ID = $postID;
		}
		if(isset($post->ID)) {
		$enable_customize = get_post_meta($post->ID, '_customize_sidebars', true);
		$_sidebars_widgets = get_post_meta($post->ID, '_sidebars_widgets', true); }
	}

	// Taxonomy page
	else {

		$taxonomyMetaData = getTaxonomyMetaData($objTaxonomy['taxonomy'], $objTaxonomy['term_id']);
		$enable_customize = $taxonomyMetaData['_customize_sidebars'];
		$_sidebars_widgets = $taxonomyMetaData['_sidebars_widgets'];
	}

	if(isset($enable_customize) && $enable_customize == 'yes' && !empty($_sidebars_widgets)) {
		if(is_array($_sidebars_widgets) && isset($_sidebars_widgets['array_version']))
			unset($_sidebars_widgets['array_version']);

		$sidebars_widgets = wp_parse_args($_sidebars_widgets, $sidebars_widgets);
	}
	global $wp_registered_widgets;
	foreach($sidebars_widgets as $sid => $sidebar) {
		if(is_array($sidebar)) {
			foreach($sidebar as $wid => $widget) {
				if(!isset($wp_registered_widgets[$widget])) {
					unset($sidebars_widgets[$sid][$wid]);
				}
			}
		}
	}
	return $sidebars_widgets;
}
//add_filter('sidebars_widgets', 'thegem_pw_filter_widgets');
if (!function_exists('thegem_contacts')) {
	function thegem_contacts()
	{
		$output = '';
		if (locate_template('contacts-widget.php') != '') {
			ob_start();
			get_template_part('contacts', 'widget');
			$output = ob_get_clean();
			return $output;
		}
		if (thegem_get_option('contacts_address')) {
			$output .= '<div class="gem-contacts-item gem-contacts-address">' . esc_html__('Address:', 'thegem') . '</br> ' . stripslashes(thegem_get_option('contacts_address')) . '</div>';
		}
		if (thegem_get_option('contacts_phone')) {
			$output .= '<div class="gem-contacts-item gem-contacts-phone">' . esc_html__('Phone:', 'thegem') . ' <a href="tel:' . esc_attr(stripslashes(thegem_get_option('contacts_phone'))) . '">' . esc_html(stripslashes(thegem_get_option('contacts_phone'))) . '</a></div>';
		}
		if (thegem_get_option('contacts_fax')) {
			$output .= '<div class="gem-contacts-item gem-contacts-fax">' . esc_html__('Fax:', 'thegem') . ' ' . esc_html(stripslashes(thegem_get_option('contacts_fax'))) . '</div>';
		}
		if (thegem_get_option('contacts_email')) {
			$output .= '<div class="gem-contacts-item gem-contacts-email">' . esc_html__('Email:', 'thegem') . ' <a href="' . esc_url('mailto:' . sanitize_email(thegem_get_option('contacts_email'))) . '">' . sanitize_email(thegem_get_option('contacts_email')) . '</a></div>';
		}
		if (thegem_get_option('contacts_website')) {
			$output .= '<div class="gem-contacts-item gem-contacts-website">' . esc_html__('Website:', 'thegem') . ' <a href="' . esc_url(thegem_get_option('contacts_website')) . '">' . esc_html(thegem_get_option('contacts_website')) . '</a></div>';
		}
		if ($output) {
			return '<div class="gem-contacts">' . $output . '</div>';
		}
		return;
	}
}
if (!function_exists('thegem_top_area_contacts')) {
	function thegem_top_area_contacts()
	{
		$output = '';
		if (locate_template('contacts-top-area.php') != '') {
			ob_start();
			get_template_part('contacts', 'top-area');
			$output = ob_get_clean();
			return $output;
		}
		if (thegem_get_option('top_area_contacts_address')) {
			wp_enqueue_style('icons-' . thegem_get_option('top_area_contacts_address_icon_pack'));
			$output .= '<div class="gem-contacts-item gem-contacts-address">' . esc_html(stripslashes(thegem_get_option('top_area_contacts_address'))) . '</div>';
		}
		if (thegem_get_option('top_area_contacts_phone')) {
			wp_enqueue_style('icons-' . thegem_get_option('top_area_contacts_phone_icon_pack'));
			$output .= '<div class="gem-contacts-item gem-contacts-phone"><a href="tel:' . esc_attr(stripslashes(thegem_get_option('top_area_contacts_phone'))) . '">' . esc_html(stripslashes(thegem_get_option('top_area_contacts_phone'))) . '</a></div>';
		}
		if (thegem_get_option('top_area_contacts_fax')) {
			wp_enqueue_style('icons-' . thegem_get_option('top_area_contacts_fax_icon_pack'));
			$output .= '<div class="gem-contacts-item gem-contacts-fax">' . esc_html(stripslashes(thegem_get_option('top_area_contacts_fax'))) . '</div>';
		}
		if (thegem_get_option('top_area_contacts_email')) {
			wp_enqueue_style('icons-' . thegem_get_option('top_area_contacts_email_icon_pack'));
			$output .= '<div class="gem-contacts-item gem-contacts-email"><a href="' . esc_url('mailto:' . sanitize_email(thegem_get_option('top_area_contacts_email'))) . '">' . sanitize_email(thegem_get_option('top_area_contacts_email')) . '</a></div>';
		}
		if (thegem_get_option('top_area_contacts_website')) {
			wp_enqueue_style('icons-' . thegem_get_option('top_area_contacts_website_icon_pack'));
			$output .= '<div class="gem-contacts-item gem-contacts-website"><a href="' . esc_url(thegem_get_option('top_area_contacts_website')) . '">' . esc_html(thegem_get_option('top_area_contacts_website')) . '</a></div>';
		}
		if ($output) {
			return '<div class="gem-contacts inline-inside">' . $output . '</div>';
		}
		return;
	}
}

function thegem_related_posts() {
	$post_tags = wp_get_post_tags(get_the_ID());
	$post_tags_ids = array();
	foreach($post_tags as $individual_tag) {
		$post_tags_ids[] = $individual_tag->term_id;
	}
	if($post_tags_ids) {
		$args=array(
			'tag__in' => $post_tags_ids,
			'post__not_in' => array(get_the_ID()),
			'posts_per_page' => 15,
			'orderby' => 'rand'
		);
		$related_query = new WP_Query($args);
		if($related_query->have_posts()) {
			wp_enqueue_script('thegem-related-posts-carousel');
?>
	<div class="post-related-posts">
		<h2><?php esc_html_e('Related Posts', 'thegem'); ?></h2>
		<div class="post-related-posts-block clearfix">
			<div class="preloader"><div class="preloader-spin"></div></div>
			<div class="related-posts-carousel">
				<?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
					<div class="related-element">
						<a href="<?php echo esc_url(get_permalink()); ?>"><?php thegem_post_thumbnail('thegem-post-thumb', true, '', array('srcset' => array('1x' => 'thegem-post-thumb-small', '2x' => 'thegem-post-thumb-large'))); ?></a>
						<div class="related-element-info clearfix">
							<div class="related-element-info-conteiner">
								<?php the_title('<a href="'.esc_url(get_permalink()).'">', '</a>'); ?>
								<div class='related-element-info-excerpt'>
									<?php the_excerpt(); ?>
								</div>
							</div>
							<div class="post-meta date-color">
								<div class="entry-meta clearfix">
									<div class="post-meta-right">
										<?php if(comments_open()): ?>
											<span class="comments-link"><?php comments_popup_link(0, 1, '%'); ?></span>
										<?php endif; ?>
										<?php if(comments_open() && function_exists('zilla_likes')): ?><?php endif; ?>
										<?php if( function_exists('zilla_likes') ) { echo '<span class="post-meta-likes">';zilla_likes();echo '</span>'; } ?>
									</div>
									<div class="post-meta-left">
										<span class="post-meta-date gem-post-date gem-date-color small-body"><?php the_date('d M Y'); ?></span>
									</div>
								</div><!-- .entry-meta -->
							</div>
						</div>
					</div>
				<?php endwhile; wp_reset_postdata() ?>
			</div>

		</div>
	</div>
<?php
		}
	}
}

function thegem_comment_form_before_fields() {
	echo '<div class="row comment-form-fields">';
}
add_action( 'comment_form_before_fields', 'thegem_comment_form_before_fields' );

function thegem_comment_form_after_fields() {
	echo '</div>';
}
add_action( 'comment_form_after_fields', 'thegem_comment_form_after_fields' );

function thegem_comment($comment, $args, $depth) {
		if('div' == $args['style']) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
?>
		<<?php echo $tag; ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
		<?php if('div' != $args['style']) : ?>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
		<?php endif; ?>
		<div class="comment-inner <?php echo ($depth == 1 ? 'default-background' : 'bordered-box'); ?>">
			<div class="comment-header clearfix">
				<div class="comment-author vcard">
					<?php if(0 != $args['avatar_size']) echo get_avatar($comment, $args['avatar_size']); ?>
					<?php printf(wp_kses(__('<div class="fn title-h6">%s</div>', 'thegem'), array('div' => array('class' => array()))), get_comment_author_link()); ?>
					<div class="comment-meta commentmetadata date-color"><a href="<?php echo esc_url(get_comment_link($comment->comment_ID, $args)); ?>">
						<?php
							/* translators: 1: date, 2: time */
							printf(esc_html__('%1$s at %2$s', 'thegem'), get_comment_date(),  get_comment_time()); ?></a><?php edit_comment_link(esc_html__('(Edit)', 'thegem'), '&nbsp;&nbsp;', '');
						?>
					</div>
				</div>
				<div class="reply">
					<?php echo str_replace('comment-reply-link', 'comment-reply-link gem-button gem-button-style-outline gem-button-size-tiny', get_comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'])))); ?>
				</div>
			</div>
			<?php if('0' == $comment->comment_approved) : ?>
			<div class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'thegem') ?></div>
			<?php endif; ?>

			<div class="comment-text"><?php comment_text(get_comment_id(), array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?></div>

			<?php if('div' != $args['style']) : ?>
			</div>
			<?php endif; ?>
		</div>
<?php
}

function thegem_shortcode_comment($comment, $args, $depth) {
?>
    <div id="comment-<?= comment_ID(); ?>" <?= comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>
        <div class="post-comment__wrap">
            <?php if(!empty($args['avatar'])): ?>
                <div class="post-comment__avatar"><?= get_avatar($comment, $args['avatar_size']); ?></div>
            <?php endif; ?>
            
            <div class="post-comment__info">
                <div class="post-comment__name">
	                <?php if (!empty($args['name'])): ?>
                        <?php if (!empty($args['name_link'])): ?>
                            <?php printf(wp_kses(__('<div class="post-comment__name-author '.$args['name_styled'].'">%s</div>', 'thegem'), array('div' => array('class' => array()))), get_comment_author_link()); ?>
                        <?php else: ?>
                            <?php printf(wp_kses(__('<div class="post-comment__name-author '.$args['name_styled'].'">%s</div>', 'thegem'), array('div' => array('class' => array()))), get_comment_author()); ?>
                        <?php endif; ?>
	                <?php endif; ?>
	
	                <?php if (!empty($args['reply'])): ?>
                        <div class="post-comment__name-reply <?=$args['reply_styled']?>">
	                        <?= get_comment_reply_link(array_merge($args, array('reply_text' => $args['reply_label'], 'add_below' => false, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                        </div>
	                <?php endif; ?>
                </div>
    
	            <?php if (!empty($args['date'])): ?>
                    <div class="post-comment__date <?=$args['date_styled']?>">
	                    <?php if (!empty($args['date_link'])): ?>
                            <a href="<?= esc_url(get_comment_link($comment->comment_ID, $args)); ?>"><?php printf(esc_html__('%1$s at %2$s', 'thegem'), get_comment_date(),  get_comment_time()); ?></a>
	                    <?php else: ?>
                            <?php printf(esc_html__('%1$s at %2$s', 'thegem'), get_comment_date(),  get_comment_time()); ?>
	                    <?php endif; ?>
                        
                        <?php edit_comment_link(esc_html__('(Edit)', 'thegem'), '&nbsp;&nbsp;', ''); ?>
                    </div>
	            <?php endif; ?>
             
	            <?php if('0' == $comment->comment_approved) : ?>
                    <div class="post-comment__approved <?=$args['desc_styled']?>">
                        <?php esc_html_e('Your comment is awaiting moderation.', 'thegem') ?>
                    </div>
	            <?php endif; ?>
	
	            <?php if (!empty($args['desc'])): ?>
                    <div class="post-comment__desc <?=$args['desc_styled']?>">
                        <?php comment_text(get_comment_id(), array_merge($args, array('add_below' => false, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                    </div>
	            <?php endif; ?>
            </div>
        </div>
    </div>
<?php
}

function thegem_toparea_search_form() {
?>
<form role="search" method="get" id="top-area-searchform" class="searchform" action="<?php echo esc_url(home_url('/')); ?>">
	<div>
		<input type="text" value="<?php echo get_search_query(); ?>" name="s" id="top-area-s" />
		<button type="submit" id="top-area-searchsubmit" value="<?php echo esc_attr_x('Search', 'submit button', 'thegem'); ?>"></button>
	</div>
</form>
<?php
}

function thegem_author_info($post_id, $full = FALSE) {
	$post = get_post($post_id);
	$user_id = $post->post_author;
	$user_data = get_userdata( $user_id );
	$thegem_post_elements = thegem_get_output_post_elements_data(get_the_ID());
	$show = TRUE;
	if(!$thegem_post_elements['show_author']) {
		$show = FALSE;
	}
	?>
	<?php if ($show): ?>
		<div class="post-author-block rounded-corners clearfix">
			<?php if ( get_the_author_meta('url', $user_id) ) : ?>
				<a href="<?php echo esc_url( get_the_author_meta('url', $user_id) ); ?>" class="post-author-avatar"><?php echo get_avatar( $user_id, 100 ); ?></a>
			<?php else : ?>
				<div class="post-author-avatar"><?php echo get_avatar( $user_id, 100 ); ?></div>
			<?php endif; ?>
			<div class="post-author-info">
				<div class="name title-h5"><?php the_author_meta('display_name', $user_id); ?> <span class="light"><?php esc_html_e('/ About Author', 'thegem'); ?></span></div>
				<div class="post-author-description"><?php echo do_shortcode(nl2br(get_the_author_meta('description', $user_id))); ?></div>
				<div class="post-author-posts-link"><a href="<?php echo esc_url(get_author_posts_url( $user_id )); ?>"><?php printf(esc_html__('More posts by %s', 'thegem'), $user_data->data->display_name); ?></a></div>
			</div>
		</div>
	<?php endif; ?>
<?php
}

function thegem_socials_sharing() {
	get_template_part('socials', 'sharing');
}

function thegem_post_tags() {
	$post_tags = wp_get_post_tags(get_the_ID());
	$post_tags_ids = array();
	foreach($post_tags as $individual_tag) {
		$post_tags_ids[] = $individual_tag->term_id;
	}
	if ($post_tags_ids) {
		$args=array(
			'tag__in' => $post_tags_ids,
			'post__not_in' => array(get_the_ID()),
			'posts_per_page'=>3,
			'orderby' => 'rand'
		);
		$related_query = new WP_Query( $args );
	}

	echo '<div class="block-tags">';
	echo '<div class="block-date">';
	echo get_the_date();
	echo '</div>';

	if ($post_tags_ids) {
		echo '<span class="sep"></span>';
	}
	$tag_list = get_the_tag_list( '', wp_kses(__( '<span class="sep"></span>', 'thegem' ), array('span' => array('class' => array()))) );
	if ( $tag_list ) {
		echo '<span class="tags-links">' . $tag_list . '</span>';
	}
	echo '</div>';
}

if (!function_exists('thegem_blog')) {
function thegem_blog($params = array()) {

	$params['button']['icon'] = '';
	if (!empty($params['button']['icon_elegant']) && $params['button']['icon_pack'] == 'elegant') {
		$params['button']['icon'] = $params['button']['icon_elegant'];
	}
	if (!empty($params['button']['icon_material']) && $params['button']['icon_pack'] == 'material') {
		$params['button']['icon'] = $params['button']['icon_material'];
	}
	if (!empty($params['button']['icon_fontawesome']) && $params['button']['icon_pack'] == 'fontawesome') {
		$params['button']['icon'] = $params['button']['icon_fontawesome'];
	}
	if (!empty($params['button']['icon_thegem_header']) && $params['button']['icon_pack'] == 'thegem-header') {
		$params['button']['icon'] = $params['button']['icon_thegem_header'];
	}
	if (!empty($params['button']['icon_userpack']) && $params['button']['icon_pack'] == 'userpack') {
		$params['button']['icon'] = $params['button']['icon_userpack'];
	}

	$params['justified_style'] = thegem_check_array_value(array('justified-style-1', 'justified-style-2'), $params['justified_style'], 'justified-style-1');
	$params['slider_style'] = thegem_check_array_value(array('fullwidth', 'halfwidth'), $params['slider_style'], 'fullwidth');
	$is_compact = false;
	if (in_array($params['style'], ['compact-tiny-1', 'compact-tiny-2', 'compact-tiny-3', 'classic-tiny'])) {
		$params['loading_animation'] = 'disabled';
		$params['post_pagination'] = $params['compact_post_pagination'];
		$is_compact = true;
	} else {
		$params['post_pagination'] = thegem_check_array_value(array('normal', 'more', 'scroll', 'disable'), $params['post_pagination'], 'normal');
		$params['loading_animation'] = thegem_check_array_value(array('disabled', 'bounce', 'move-up', 'fade-in', 'fall-perspective', 'scale', 'flip'), $params['loading_animation'], 'move-up');
	}

	if ($params['post_pagination'] == 'scroll' && $params['style'] != 'grid_carousel' && $params['style'] != 'slider') {
		$params['effects_enabled'] = true;
	}

	$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
	if ($params['post_pagination'] == 'disable' || $params['style'] == 'grid_carousel'|| $params['style'] == 'slider')
		$paged = 1;

	if (isset($params['paged']) && $params['paged'] != -1)
		$paged = $params['paged'];

	$params['style'] = thegem_check_array_value(array('default', 'timeline', 'timeline_new', '2x', '3x', '4x', 'justified-2x', 'justified-3x', 'justified-4x', '100%', 'grid_carousel', 'styled_list1', 'styled_list2', 'multi-author', 'compact', 'compact-2', 'slider', 'compact-tiny-1', 'compact-tiny-2', 'compact-tiny-3', 'classic-tiny'), $params['style'], 'default');
	$params['post_per_page'] = intval($params['post_per_page']) > 0 ? intval($params['post_per_page']) : 5;

	if (!empty($params['categories'])) {
		$params['categories'] = explode(',', $params['categories']);
	} else {
		$params['categories'] = ['0'];
	}
	if (!empty($params['tags'])) {
		$params['tags'] = explode(',', $params['tags']);
	}
	if (!empty($params['posts'])) {
		$params['posts'] = explode(',', $params['posts']);
	}
	if (!empty($params['authors'])) {
		$params['authors'] = explode(',', $params['authors']);
	}

	$single_post_id = function_exists('thegem_templates_init_post') && thegem_templates_init_post() ? thegem_templates_init_post()->ID : get_the_ID();
	$post_type = !empty($params['query_type']) ? $params['query_type'] : 'post';
	$taxonomy_filter = $manual_selection = $blog_authors = $date_query = [];

	if ($params['query_type'] == 'post') {

		if ($params['select_blog_categories'] && !empty($params['categories']) && !in_array('0', $params['categories'])) {
			$taxonomy_filter['category'] = $params['categories'];
		}
		if ($params['select_blog_tags'] && !empty($params['tags'])) {
			$taxonomy_filter['post_tag'] = $params['tags'];
		}
		if ($params['select_blog_posts'] && !empty($params['posts'])) {
			$manual_selection = $params['posts'];
		}
		if ($params['select_blog_authors'] && !empty($params['authors'])) {
			$blog_authors = $params['authors'];
		}

		if ($params['exclude_blog_posts_type'] == 'current') {
			$params['exclude_blog_posts'] = [$single_post_id];
		} else if ($params['exclude_blog_posts_type'] == 'term') {
			$params['exclude_blog_posts'] = thegem_get_posts_query_section_exclude_ids($params['exclude_post_terms'], $post_type);
		} else {
			$params['exclude_blog_posts'] = !empty($params['exclude_blog_posts']) ? explode(',', $params['exclude_blog_posts']) : [];
		}
		$exclude = isset($params['exclude_blog_posts']) ? $params['exclude_blog_posts'] : [];

	} else if ($params['query_type'] == 'related'|| $params['query_type'] == 'archive' || $params['query_type'] == 'manual') {

		if ($params['query_type'] == 'related') {
			$post_type = isset($params['taxonomy_related_post_type']) ? $params['taxonomy_related_post_type'] : 'any';
			$taxonomies = $params['taxonomy_related'] = !empty($params['taxonomy_related']) ? explode(',', $params['taxonomy_related']) : [];
			if (!empty($taxonomies)) {
				foreach ($taxonomies as $tax) {
					if ($tax == 'authors') {
						$blog_authors = $params['select_blog_authors'] = array(get_the_author_meta('ID'));
					} else {
						$tax_terms = get_the_terms($single_post_id, $tax);
						if (!empty($tax_terms) && !is_wp_error($tax_terms)) {
							$taxonomy_filter[$tax] = [];
							foreach ($tax_terms as $term) {
								$taxonomy_filter[$tax][] = $term->slug;
							}
						}
					}
				}
			}
			$params['related_tax_filter'] = $taxonomy_filter;
		} else if ($params['query_type'] == 'archive') {
			$post_type = $params['archive_post_type'] = get_post_type() == 'thegem_templates' ? 'post' : get_post_type();

			if(get_post_type() == 'thegem_templates') {
				$post_id = get_the_ID();
				$editor_post_id = $post_id;
				$preview_settings = get_post_meta($editor_post_id, 'thegem_template_preview_settings', true);
				if(!empty($preview_settings)) {
					$preview_tax = empty($preview_settings['demo_tax']) ? 'category' : $preview_settings['demo_tax'];
					if(!empty($preview_settings['demo_term_id'])) {
						$preview_term_id = $preview_settings['demo_term_id'];
						$preview_term = get_term($preview_term_id);
						if(!empty($preview_term) && !is_wp_error($preview_term)) {
							$obj = $preview_term;
							$taxonomy_filter[$preview_tax] = array($obj->slug);
							$preview_taxonomy = get_taxonomy($preview_tax);
							$post_type = !empty($preview_taxonomy->object_type) ? $preview_taxonomy->object_type[0] : $post_type;
						}
					}
				}
			}

			if (is_author()) {
				$blog_authors = $params['select_blog_authors'] = array(get_queried_object()->ID);
			} else if (is_category() || is_tag() || is_tax()) {
				$taxonomy_filter[get_queried_object()->taxonomy] = array(get_queried_object()->slug);
				$params['archive_tax_filter'] = $taxonomy_filter;
			} else if (is_date()) {
				if (!empty(get_query_var('year'))) {
					$date_query['year'] = get_query_var('year');
				}
				if (!empty(get_query_var('monthnum'))) {
					$date_query['month'] = get_query_var('monthnum');
				}
				if (!empty(get_query_var('day'))) {
					$date_query['day'] = get_query_var('day');
				}
				$params['date_query'] = $date_query;
			}
		} else {
			$post_type = 'any';
			$manual_selection = $params['select_posts_manual'] = !empty($params['select_posts_manual']) ? explode(',', $params['select_posts_manual']) : [];
		}

		if ($params['exclude_posts_manual_type'] == 'current') {
			$params['exclude_posts_manual'] = [$single_post_id];
		} else if ($params['exclude_posts_manual_type'] == 'term') {
			$params['exclude_posts_manual'] = thegem_get_posts_query_section_exclude_ids($params['exclude_any_terms'], $post_type);
		} else {
			$params['exclude_posts_manual'] = !empty($params['exclude_posts_manual']) ? explode(',', $params['exclude_posts_manual']) : [];
		}

		$exclude = $params['exclude_posts_manual'];
		$params['with_filter'] = '';

	} else {

		$source_post_type = $params['source_post_type_' . $post_type] = !empty($params['source_post_type_' . $post_type]) ? explode(',', $params['source_post_type_' . $post_type]) : [];
		foreach ($source_post_type as $source) {
			if ($source == 'all') {

			} else if ($source == 'manual') {
				$manual_selection = $params['source_post_type_' . $post_type . '_manual'] = !empty($params['source_post_type_' . $post_type . '_manual']) ? explode(',', $params['source_post_type_' . $post_type . '_manual']) : [];
			} else {
				$tax_terms = $params['source_post_type_' . $post_type . '_tax_' . $source] = !empty($params['source_post_type_' . $post_type . '_tax_' . $source]) ? explode(',', $params['source_post_type_' . $post_type . '_tax_' . $source]) : [];
				if (!empty($tax_terms)) {
					$taxonomy_filter[$source] = $tax_terms;
				}
			}
		}

		if ($params['exclude_' . $post_type . '_manual_type'] == 'current') {
			$params['source_post_type_' . $post_type . '_exclude'] = [$single_post_id];
		} else if ($params['exclude_' . $post_type . '_manual_type'] == 'term') {
			$params['source_post_type_' . $post_type . '_exclude'] = thegem_get_posts_query_section_exclude_ids($params['exclude_' . $post_type . '_terms'], $post_type);
		} else {
			$params['source_post_type_' . $post_type . '_exclude'] = !empty($params['source_post_type_' . $post_type . '_exclude']) ? explode(',', $params['source_post_type_' . $post_type . '_exclude']) : [];
		}

		$exclude = $params['source_post_type_' . $post_type . '_exclude'];
	}

	if ($params['style'] == 'timeline_new') {
		$params['ignore_sticky'] = 1;
	}

	$orderby = $order = '';
	if (isset($params['order_by']) && $params['order_by'] != 'default') {
		$orderby = $params['order_by'];
	}
	if (isset($params['order']) && $params['order'] != 'default') {
		$order = $params['order'];
	}

	$posts = get_thegem_extended_blog_posts($post_type, $taxonomy_filter, [], $manual_selection, $exclude, $blog_authors, $paged, $params['post_per_page'], $orderby, $order, $params['offset'], $params['ignore_sticky'], false, false, $date_query);

	$max_page = ceil(($posts->found_posts - intval($params['offset'])) / $params['post_per_page']);

	if ($max_page > $paged)
		$next_page = $paged + 1;
	else
		$next_page = 0;

	$blog_style = $params['style'];

	wp_enqueue_style('thegem-blog');
	wp_enqueue_style('thegem-additional-blog');

	if ($blog_style == 'timeline' || $blog_style == 'timeline_new') {
		wp_enqueue_style('thegem-blog-timeline-new');
	}
	if ($params['loading_animation'] !== 'disabled') {
		wp_enqueue_style('thegem-animations');
	}

	if (!$is_compact) {
		wp_enqueue_style('thegem-additional-blog');

		if ($params['effects_enabled']) {
			thegem_lazy_loading_enqueue();
		}
	}
	if ($blog_style == '2x' || $blog_style == '3x' || $blog_style == '4x' || $blog_style == '100%' || $blog_style == 'timeline_new') {
		$enqueued_stript = 'thegem-blog-isotope';
	} else if (!$is_compact || $params['post_pagination'] == 'normal') {
		$enqueued_stript = 'thegem-blog';
	}
	$style_uid = substr(md5(rand()), 0, 7);

	if (!empty($enqueued_stript)) {
		wp_enqueue_script($enqueued_stript);

		$localize = array_merge(
			array('data' => $params),
			array(
				'url' => esc_url(admin_url('admin-ajax.php')),
				'nonce' => wp_create_nonce('blog_ajax-nonce')
			)
		);
		if ($params['post_pagination'] == 'more' || $params['post_pagination'] == 'scroll' || ($is_compact && $params['post_pagination'] == 'normal')) {
			wp_localize_script($enqueued_stript, 'thegem_blog_ajax_'.$style_uid, $localize);
		}
	}

	if($posts->have_posts()) {
		if ($params['style'] == 'grid_carousel') {
			wp_enqueue_script('thegem-news-carousel');
			echo '<div class="preloader"><div class="preloader-spin"></div></div>';
			echo '<div class="gem-news gem-news-type-carousel clearfix ' . ($params['effects_enabled'] ? 'lazy-loading' : '') . '" ' . ($params['effects_enabled'] ? 'data-ll-item-delay="0"' : '') . '>';
			while($posts->have_posts()) {
				$posts->the_post();
				include(locate_template('content-news-carousel-item.php'));
			}
			echo '</div>';
		} elseif ($params['style'] == 'slider') {
			$thegem_slider_style = $params['slider_style'];
			wp_enqueue_script('thegem-news-carousel');
			echo '<div class="preloader default-background gem-blog-slider-preloader"><div class="preloader-spin-new"></div></div>';
			echo '<div class="gem-blog-slider gem-blog-slider-style-'.$thegem_slider_style.' clearfix"'.(intval($params['slider_autoscroll']) ? ' data-autoscroll="'.intval($params['slider_autoscroll']).'"' : '').'>';
			while($posts->have_posts()) {
				$posts->the_post();
				include(locate_template('gem-templates/blog/content-blog-item-slider.php'));
			}
			echo '</div>';
		} else {
			$gridSelector = '.blog#style-' . $style_uid;
			if (!empty($params['is_ajax'])) {
				echo '<div data-page="' . $paged . '" data-next-page="' . $next_page . '">';
			} else {
				if ($blog_style == '2x' || $blog_style == '3x' || $blog_style == '4x' || $blog_style == '100%')
					echo '<div class="preloader"><div class="preloader-spin"></div></div>';
				if ($blog_style == 'timeline_new') {
					echo '<div class="timeline_new-wrapper"><div class="timeline-new-line"'.(!empty($params['item_colors']['time_line_color']) ? ' style="background-color: '.esc_attr($params['item_colors']['time_line_color']).'"' : '').'></div>';
				}
				if (!empty($params['hover_overlay_color'])) { ?>
					<style>
						<?php echo esc_attr($gridSelector); ?> article a.default:before,
						<?php echo esc_attr($gridSelector); ?> article .post-featured-content > a:before {
							background-color: <?php echo esc_attr($params['hover_overlay_color']); ?>;
						}
					</style>
				<?php }
				echo '<div id="style-'.esc_attr($style_uid).'" data-style-uid="'.esc_attr($style_uid).'" class="blog blog-style-'.str_replace('%', '', $blog_style) . ($blog_style == 'timeline_new' ? ' blog-style-timeline' : '').' '. ($blog_style == 'justified-2x' || $blog_style == 'justified-3x' || $blog_style == 'justified-4x' && $params['justified_style'] ? $params['justified_style'].' inline-row' : '').' clearfix '.($blog_style == '2x' || $blog_style == '3x' || $blog_style == '4x' || $blog_style == '100%' ? 'blog-style-masonry ' : '').($blog_style == '100%' ? 'fullwidth-block' : '') . ' item-animation-' . $params['loading_animation'] . ' pagination-' . $params['post_pagination'] . '" data-next-page="' . $next_page . '" data-pages-count="' . $max_page . '">';
			}

			$last_post_date = '';
			while($posts->have_posts()) {
				$posts->the_post();
				if($blog_style == '2x' || $blog_style == '3x' || $blog_style == '4x' || $blog_style == '100%') {
					include(locate_template(array('gem-templates/blog/content-blog-item-masonry.php', 'content-blog-item.php')));
				} elseif($blog_style == 'justified-2x' || $blog_style == 'justified-3x' || $blog_style == 'justified-4x') {
					include(locate_template(array('gem-templates/blog/content-blog-item-justified.php', 'content-blog-item.php')));
				} else {
					include(locate_template(array('gem-templates/blog/content-blog-item-'.$blog_style.'.php', 'content-blog-item.php')));
				}
				$last_post_date = get_the_date("M Y");
			}
			echo '</div>';
			if (empty($params['is_ajax']) && $blog_style == 'timeline_new') {
				echo "</div>";
			}
			if ($params['post_pagination'] == 'normal' && empty($params['is_ajax']))
				if (in_array($params['style'], ['compact-tiny-1', 'compact-tiny-2', 'compact-tiny-3', 'classic-tiny'])) {
					if ($max_page > 1) { ?>
						<div class="portfolio-navigator gem-pagination">
							<a href="#" class="prev" style="display: none;">
								<i class="default"></i>
							</a>
							<div class="pages">
								<?php for ($i = 0; $i < $max_page; $i++) { ?>
									<a href="#" data-page="<?php echo $i + 1; ?>" <?php echo $i == 0 ? 'class="current"' : ''; ?>>
										<?php echo $i + 1; ?>
									</a>
								<?php } ?>
							</div>
							<a href="#" class="next">
								<i class="default"></i>
							</a>
						</div>
					<?php }
				} else {
					thegem_pagination($posts);
				}
			?>

			<?php if($params['post_pagination'] == 'more' && empty($params['is_ajax']) && $max_page > $paged): ?>
				<div class="blog-load-more <?php if ($blog_style == 'timeline_new') echo 'blog-load-more-style-timeline-new'?>">
					<div class="inner">
						<?php thegem_button(array_merge($params['button'], array('tag' => 'button', 'position' => 'center')), 1); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if($params['post_pagination'] == 'scroll' && empty($params['is_ajax']) && $max_page > $paged): ?>
				<div class="blog-scroll-pagination"></div>
			<?php endif; ?>

			<?php
		}
	}

	if (function_exists('thegem_templates_close_post')) {
		thegem_templates_close_post();
	}
}
}

if (!function_exists('thegem_get_posts_query_section_exclude_ids')) {
	function thegem_get_posts_query_section_exclude_ids($terms, $post_type) {
		$exclude_ids = [];
		if (!empty($terms)) {
			$exclude_terms = explode(',', $terms);
			foreach ($exclude_terms as $id) {
				$id = str_replace(' ', '', $id);
				$arr = explode("|", $id);
				$term = get_term_by('id', $arr[1], $arr[0]);

				$args = array(
					'post_type' => $post_type,
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'fields' => 'ids',
					'tax_query' => array(
						array(
							'taxonomy' => $term->taxonomy,
							'field' => 'term_id',
							'terms' => $term->term_id,
						),
					),
				);
				$wp_query_result = new WP_Query($args);
				$post_ids = !empty($wp_query_result->posts) ? $wp_query_result->posts : array();
				$exclude_ids = array_unique(array_merge($exclude_ids, $post_ids));
			}
		}

		return $exclude_ids;
	}
}

function thegem_get_search_form($form) {$product_search = '';
	if (thegem_is_plugin_active('woocommerce/woocommerce.php') && thegem_get_option('website_search_post_type_products') == '1') {
		$product_search = '<input type="hidden" name="post_type" value="product" />';
	}
	$form = '<form role="search" method="get" id="searchform" class="searchform" action="' . esc_url(home_url('/')) . '">
				<div>
					<input type="text" value="' . get_search_query() . '" name="s" id="s" />'.$product_search.'
					 <button class="gem-button" type="submit" id="searchsubmit" value="' . esc_attr_x('Search', 'submit button', 'thegem') . '">'.esc_attr_x('Search', 'submit button', 'thegem').'</button>
				</div>
			</form>';
	return $form;
}
add_filter('get_search_form', 'thegem_get_search_form');

if(!function_exists('thegem_video_background')) {
function thegem_video_background($video_type, $video, $aspect_ratio = '16:9', $headerUp = false, $color = '', $opacity = '', $poster='', $play_on_mobile = '', $background_fallback = '', $background_style = '', $background_position_horizontal = 'center', $background_position_vertical = 'top') {
	$output = $link = $uniqid = $video_class = $mobile = '';
	$uniqid = uniqid('thegem-video-frame-').rand(1,9999);
	$video_type = thegem_check_array_value(array('', 'youtube', 'vimeo', 'self'), $video_type, '');
	if($video_type && $video) {
		$video_block = $overlay_css = $fallback_css = $video_css = $video_data = '';
		if(!function_exists('isMobile')) {
			function isMobile() {
				return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
			}
		}
		if($video_type == 'youtube' || $video_type == 'vimeo') {
			if($play_on_mobile) {
				wp_enqueue_script('thegem-video');
				$video_class = ' background-video-container';
				$video_block = '<div class="background-video-embed"></div>';
			} elseif($background_fallback && !$play_on_mobile) {
				$fallback_css .= 'style="';
					$fallback_css .= 'background-image: url('.esc_url($background_fallback).');';
					if(!empty($background_style)) {
						$fallback_css .= 'background-size: '.esc_attr($background_style).';';
					} else {
						$fallback_css .= 'background-size: cover;';
					}
					$fallback_css .= 'background-position: '.$background_position_horizontal.' '.$background_position_vertical.';';
				$fallback_css .= '"';
				$output .= '<script type="text/javascript">
								(function($) {
									$("head").append("<style>@media (max-width: 767px) {#'.esc_attr($uniqid).' {display: none;}}</style>");
								})(jQuery);
							</script>';
			}
			if($video_type == 'youtube') {
				if($play_on_mobile && !vc_is_page_editable()) {
					$video_data = ' data-settings=\'{"url": "https://www.youtube.com/watch?v='.$video.'", "play_on_mobile": true, "background_play_once": false }\'';
				} else {
					$link = '//www.youtube.com/embed/'.$video.'?playlist='.$video.'&autoplay=1&mute=1&controls=0&playsinline=1&enablejsapi=1&loop=1&fs=0&showinfo=0&autohide=1&iv_load_policy=3&rel=0&disablekb=1&wmode=transparent';
					$video_block = '<iframe id="'.$uniqid.'" class="gem-video-background-iframe" src="'.esc_url($link).'" frameborder="0" muted="muted"></iframe>';
				}
			}
			if($video_type == 'vimeo') {
				if($play_on_mobile && !vc_is_page_editable()) {
					$video_data = ' data-settings=\'{"url": "https://vimeo.com/'.$video.'", "play_on_mobile": true, "background_play_once": false }\'';
				} elseif(empty($play_on_mobile) && !vc_is_page_editable() && !empty(isMobile())) {
					$link = '//player.vimeo.com/video/'.$video.'?autoplay=0&muted=1&controls=0&loop=1&title=0&badge=0&byline=0&autopause=0';
					$video_block = '<iframe id="'.$uniqid.'" class="gem-video-background-iframe" src="'.esc_url($link).'" frameborder="0" muted="muted"></iframe>';
				} else {
					$link = '//player.vimeo.com/video/'.$video.'?autoplay=1&muted=1&controls=0&loop=1&title=0&badge=0&byline=0&autopause=0';
					$video_block = '<iframe id="'.$uniqid.'" class="gem-video-background-iframe" src="'.esc_url($link).'" frameborder="0" muted="muted"></iframe>';
				}
			}
		} else {
			if($play_on_mobile && !vc_is_page_editable()) {
				wp_enqueue_script('thegem-video');
				$video_class = ' background-video-container';
				$video_data = ' data-settings=\'{"url": "'.$video.'", "play_on_mobile": true, "background_play_once": false }\'';
				$video_block = '<video id="'.$uniqid.'" class="background-video-hosted html5-video" autoplay muted playsinline loop'.($poster ? ' poster="'.esc_url($poster).'"' : '').'></video>';
			} elseif($background_fallback && !$play_on_mobile) {
				$fallback_css .= 'style="';
					$fallback_css .= 'background-image: url('.esc_url($background_fallback).');';
					if(!empty($background_style)) {
						$fallback_css .= 'background-size: '.esc_attr($background_style).';';
					} else {
						$fallback_css .= 'background-size: cover;';
					}
					$fallback_css .= 'background-position: '.$background_position_horizontal.' '.$background_position_vertical.';';
				$fallback_css .= '"';
				$output .= '<script type="text/javascript">
								(function($) {
									$("head").append("<style>@media (max-width: 767px) {#'.esc_attr($uniqid).' {display: none;}}</style>");
								})(jQuery);
							</script>';
				$video_block = '<video id="'.$uniqid.'" autoplay="autoplay" loop="loop" src="'.$video.'" muted="muted"'.($poster ? ' poster="'.esc_url($poster).'"' : '').'></video>';
			} else {
				$video_block = '<video id="'.$uniqid.'" autoplay="autoplay" loop="loop" src="'.$video.'" muted="muted"'.($poster ? ' poster="'.esc_url($poster).'"' : '').'></video>';
			}
		}
		
		if($color) {
			$overlay_css .= 'background-color: '.$color.'; opacity: '.floatval($opacity).';';
		}
		
		$output .= '<div class="gem-video-background" data-aspect-ratio="'.esc_attr($aspect_ratio).'"'.($headerUp ? ' data-headerup="1"' : '').''.$fallback_css.'>';
			$output .= '<div class="gem-video-background-inner'.$video_class.'"'.$video_data.'>'.$video_block.'</div>';
			$output .= '<div class="gem-video-background-overlay" style="'.$overlay_css.'"></div>';
		$output .= '</div>';
	}

	if (class_exists('TheGemGdpr')) {
		$type = null;
		switch ($video_type) {
			case 'youtube':
				$type = TheGemGdpr::CONSENT_NAME_YOUTUBE;
				break;
			case 'vimeo':
				$type = TheGemGdpr::CONSENT_NAME_VIMEO;
				break;
		}

		if (!empty($type)) {
			return TheGemGdpr::getInstance()->replace_disallowed_content($output, $type);
		}
	}


	return $output;
}
}

function thegem_aspect_ratio_to_percents($aspect_ratio) {
	if($aspect_ratio) {
		$aspect_ratio = explode(':', $aspect_ratio);
		if(count($aspect_ratio) > 1 && intval($aspect_ratio[0]) > 0 && intval($aspect_ratio[1]) > 0) {
			return round(intval($aspect_ratio[1])/intval($aspect_ratio[0]), 4)*100;
		}
	}
	return '56.25';
}

if(!function_exists('thegem_button')) {
function thegem_button($params = array(), $echo = false) {
	$params = array_merge(array(
		'tag' => 'a',
		'text' => '',
		'href' => '#',
		'target' => '_self',
		'title' => '',
		'style' => 'flat',
		'size' => 'small',
		'size_tablet' => '',
		'size_mobile' => '',
		'text_style' => '',
		'text_weight' => 'normal',
		'no-uppercase' => 0,
		'corner' => 3,
		'border' => 2,
		'position' => 'inline',
		'text_color' => '',
		'background_color' => '',
		'border_color' => '',
		'hover_text_color' => '',
		'hover_background_color' => '',
		'hover_border_color' => '',
		'icon_source' => '',
		'icon' => '',
		'icon_pack' => '',
		'icon_position' => 'left',
		'icon_image_file' => '',
		'separator' => '',
		'separator_weight' => '',
		'extra_class' => '',
		'id' => '',
		'attributes' => array(),

		'effects_enabled' => false,
		'effects_enabled_name' => 'default',
		'effects_enabled_duration' => '',
		'effects_enabled_delay' => '',
		'effects_enabled_timing_function' => '',

		'gradient_backgound' => '',
		'gradient_backgound_from' => '',
		'gradient_backgound_to' => '',
		'gradient_backgound_hover_from' => '',
		'gradient_backgound_hover_to' => '',
		'gradient_backgound_style' => 'linear',
		'gradient_backgound_angle' => 'to bottom',
		'gradient_backgound_cusotom_deg' => '180',
		'gradient_radial_backgound_position' => 'at top',
		'gradient_radial_swap_colors' => '',

		'text_size' => '',
		'text_size_tablet' => '',
		'text_size_mobile' => '',
		'padding_left' => '',
		'padding_left_tablet' => '',
		'padding_left_mobile' => '',
		'padding_right' => '',
		'padding_right_tablet' => '',
		'padding_right_mobile' => '',
		'margin_left' => '',
		'margin_left_tablet' => '',
		'margin_left_mobile' => '',
		'margin_right' => '',
		'margin_right_tablet' => '',
		'margin_right_mobile' => '',
		'margin_top' => '',
		'margin_top_tablet' => '',
		'margin_top_mobile' => '',
		'margin_bottom' => '',
		'margin_bottom_tablet' => '',
		'margin_bottom_mobile' => '',
		'letter_spacing' => '',
		'text_transform' => '',

	), $params);

	$params['tag'] = thegem_check_array_value(array('a', 'button', 'input'), $params['tag'], 'a');
	$params['text'] = esc_html($params['text']);
	if($params['href'] === 'post_link') {
		$params['href'] = '{{ post_link_url }}';
	} else {
		$params['href'] = esc_url($params['href']);
	}
	$params['target'] = thegem_check_array_value(array('_self', '_blank'), trim($params['target']), '_self');
	$params['title'] = esc_attr($params['title']);
	$params['style'] = thegem_check_array_value(array('flat', 'outline'), $params['style'], 'flat');
	$params['size'] = thegem_check_array_value(array('tiny', 'small', 'medium', 'large', 'giant'), $params['size'], 'small');
	$params['size_tablet'] = thegem_check_array_value(array('', 'tiny', 'small', 'medium', 'large', 'giant'), $params['size_tablet'], '');
	$params['size_mobile'] = thegem_check_array_value(array('', 'tiny', 'small', 'medium', 'large', 'giant'), $params['size_mobile'], '');
	$params['text_style'] = thegem_check_array_value(array('', 'title-h1', 'title-h2', 'title-h3', 'title-h4', 'title-h5', 'title-h6', 'title-xlarge', 'styled-subtitle', 'title-main-menu', 'text-body', 'text-body-tiny'), $params['text_style'], '');
	$params['text_weight'] = thegem_check_array_value(array('normal', 'thin'), $params['text_weight'], 'normal');
	$params['no-uppercase'] = $params['no-uppercase'] ? 1 : 0;
	$params['text_transform'] = thegem_check_array_value(array('', 'capitalize', 'lowercase', 'uppercase', 'none'), $params['text_transform'], '');
	$params['corner'] = intval($params['corner']) >= 0 ? intval($params['corner']) : 3;
	$params['border'] = thegem_check_array_value(array('1', '2', '3', '4', '5', '6'), $params['border'], '2');
	$params['position'] = thegem_check_array_value(array('inline', 'left', 'right', 'center', 'fullwidth'), $params['position'], 'inline');
	$params['text_color'] = esc_attr($params['text_color']);
	$params['background_color'] = esc_attr($params['background_color']);
	$params['border_color'] = esc_attr($params['border_color']);
	$params['hover_text_color'] = esc_attr($params['hover_text_color']);
	$params['hover_background_color'] = esc_attr($params['hover_background_color']);
	$params['hover_border_color'] = esc_attr($params['hover_border_color']);
	$params['icon'] = esc_attr($params['icon']);
	$params['icon_pack'] = thegem_check_array_value(array('thegem-icons', 'elegant', 'material', 'fontawesome', 'thegem-header', 'userpack'), $params['icon_pack'], 'thegem-icons');
	$params['icon_position'] = thegem_check_array_value(array('left', 'right'), $params['icon_position'], 'left');
	$params['separator'] = thegem_check_array_value(array('', 'single', 'square', 'soft-double', 'strong-double', 'load-more'), $params['separator'], '');
	$params['extra_class'] = esc_attr($params['extra_class']);
	$params['id'] = sanitize_title($params['id']);
	$params['gradient_backgound'] = $params['gradient_backgound'] ? 1 : 0;
	$params['gradient_radial_swap_colors'] = $params['gradient_radial_swap_colors'] ? 1 : 0;
	$params['gradient_backgound_from'] = esc_attr($params['gradient_backgound_from']);
	$params['gradient_backgound_to'] = esc_attr($params['gradient_backgound_to']);
	$params['gradient_backgound_hover_from'] = esc_attr($params['gradient_backgound_hover_from']);
	$params['gradient_backgound_hover_to'] = esc_attr($params['gradient_backgound_hover_to']);
	$params['gradient_backgound_style'] = thegem_check_array_value(array('linear', 'radial'), $params['gradient_backgound_style']);
	$params['gradient_backgound_angle'] = thegem_check_array_value(array('to bottom', 'to top','to right', 'to left', 'to bottom right', 'to top right', 'to bottom left', 'to top left', 'cusotom_deg'), $params['gradient_backgound_angle']);
	$params['gradient_backgound_cusotom_deg'] = esc_attr($params['gradient_backgound_cusotom_deg']);
	$params['gradient_radial_backgound_position'] = thegem_check_array_value(array('at top', 'at bottom', 'at right', 'at left', 'at center'), $params['gradient_radial_backgound_position']);
	$params['effects_enabled_name'] = thegem_check_array_value(array('default', 'slide-up', 'slide-down', 'slide-left', 'slide-right', 'fade-down', 'fade-up', 'fade-left', 'fade-right', 'fade'), $params['effects_enabled_name']);

	if(empty($params['attributes']['class'])) {
		$params['attributes']['class'] = '';
	}
	if(!empty($params['size_tablet'])) {
		$params['attributes']['class'] .= ' gem-button-tablet-size-'.$params['size_tablet'];
	}
	if(!empty($params['size_mobile'])) {
		$params['attributes']['class'] .= ' gem-button-mobile-size-'.$params['size_mobile'];
	}

	$is_effects_enabled = $params['effects_enabled'];
	$is_enabled_button_animation = false;
	$effects_enabled_delay = null;

	if ($is_effects_enabled) {
		$is_enabled_button_animation = $params['effects_enabled_name'] != 'default';
		$effects_enabled_delay = !empty($params['effects_enabled_delay']) ? (int)$params['effects_enabled_delay'] : null;
	}

	if ($is_effects_enabled) {
		if ($is_enabled_button_animation && class_exists('TheGemButtonAnimation')) {
			TheGemButtonAnimation::instance()->includeInlineJs();
		} else {
			thegem_lazy_loading_enqueue();
		}
	}

	$sep = '';
	if($params['separator']) {
		$params['position'] = 'center';
		if($params['style'] == 'flat') {
			$sep_color = $params['background_color'] ? $params['background_color'] : thegem_get_option('button_background_basic_color');
		} else {
			$sep_color = $params['border_color'] ? $params['border_color'] : thegem_get_option('button_outline_border_basic_color');
		}
		if($params['separator'] == 'load-more') {
			$sep_color = thegem_get_option('box_border_color');
		}
		if($params['separator'] == 'square') {
			$sep.= '<div class="gem-button-separator-line"><svg width="100%" height="8px"><line x1="4" x2="100%" y1="4" y2="4" stroke="'.esc_attr($sep_color).'" stroke-width="8" stroke-linecap="square" stroke-dasharray="0, 15"/></svg></div>';
		} else {
			$weight = '';
			if (!empty($params['separator_weight'])) {
				if ($params['separator'] == 'single') {
					$weight = 'border-top-width:'.$params['separator_weight'].'px; border-bottom-width: 0;';
				} else if (in_array($params['separator'], ['soft-double', 'strong-double'])) {
					$weight = 'border-top-width:'.$params['separator_weight'].'px; border-bottom-width:'.$params['separator_weight'].'px;';
				}
			}
			$sep.= '<div class="gem-button-separator-holder"><div class="gem-button-separator-line" style="border-color: '.esc_attr($sep_color).';'.esc_attr($weight).'"></div></div>';
		}
	}

	$output = $delay = $interactions_data = '';

	if (isset($params['interactions_data']) && !empty($params['interactions_data'])) {
		$interactions_data = $params['interactions_data'];
	}

	if ($is_effects_enabled && !$is_enabled_button_animation && $effects_enabled_delay) {
		$delay = ' data-ll-item-delay="'.$effects_enabled_delay.'"';
	}

	$id = '';
	$unique = uniqid('thegem-button-').rand(1,9999);
	$el_class = ' '.$unique;
	if($params['id']) {
		$id = ' id="'.esc_attr($params['id']).'"';
	}
	if($params['extra_class']) {
		$el_class .= ' '.$params['extra_class'];
	}
	if($params['separator']) {
		$el_class .= ' gem-button-with-separator';
	}

	if ($is_effects_enabled && $is_enabled_button_animation) {
		$effects_enabled_duration = !empty($params['effects_enabled_duration']) ? (int)$params['effects_enabled_duration'] : null;
		$effects_enabled_timing_function = !empty($params['effects_enabled_timing_function']) ? esc_attr($params['effects_enabled_timing_function']) : null;

		$el_class .= ' thegem-button-animate thegem-button-animation-'.esc_attr($params['effects_enabled_name']);
		$button_animation_params[] = 'opacity:0';

		if ($effects_enabled_duration) {
			$button_animation_params[] = 'animation-duration:'.$effects_enabled_duration.'ms !important';
		}

		if ($effects_enabled_delay) {
			$button_animation_params[] = 'animation-delay:'.$effects_enabled_delay.'ms !important';
		}

		if ($effects_enabled_timing_function) {
			$button_animation_params[] = 'animation-timing-function:'.$effects_enabled_timing_function.' !important';
		}

		if (!empty($button_animation_params)) {
			$output .= '<style type="text/css">.'.$unique.' .gem-button {'.implode(';', $button_animation_params).'}</style>';
		}

	} elseif($is_effects_enabled) {
		$el_class .= ' lazy-loading lazy-loading-before-start-animation';
		$output .= '<style type="text/css">.'.$unique.'.lazy-loading-before-start-animation .lazy-loading-item {opacity: 0;} body.thegem-effects-disabled .'.$unique.'.lazy-loading-before-start-animation .lazy-loading-item {opacity: 1;}</style>';
	}

	$responsive_styles = '';
	$responsive_tablet_styles = '';
	$responsive_mobile_styles = '';
	if(intval($params['text_size']) > 0 || $params['text_size'] === '0') {
		$responsive_styles .= 'font-size: '.intval($params['text_size']).'px;';
	}
	if(intval($params['text_size_tablet']) > 0 || $params['text_size_tablet'] === '0') {
		$responsive_tablet_styles .= 'font-size: '.intval($params['text_size_tablet']).'px;';
	}
	if(intval($params['text_size_mobile']) > 0 || $params['text_size_mobile'] === '0') {
		$responsive_mobile_styles .= 'font-size: '.intval($params['text_size_mobile']).'px;';
	}
	if(intval($params['padding_left']) > 0 || $params['padding_left'] === '0') {
		$responsive_styles .= 'padding-left: '.intval($params['padding_left']).'px;';
	}
	if(intval($params['padding_right']) > 0 || $params['padding_right'] === '0') {
		$responsive_styles .= 'padding-right: '.intval($params['padding_right']).'px;';
	}
	if(intval($params['padding_left_tablet']) > 0 || $params['padding_left_tablet'] === '0') {
		$responsive_tablet_styles .= 'padding-left: '.intval($params['padding_left_tablet']).'px;';
	}
	if(intval($params['padding_right_tablet']) > 0 || $params['padding_right_tablet'] === '0') {
		$responsive_tablet_styles .= 'padding-right: '.intval($params['padding_right_tablet']).'px;';
	}
	if(intval($params['padding_left_mobile']) > 0 || $params['padding_left_mobile'] === '0') {
		$responsive_mobile_styles .= 'padding-left: '.intval($params['padding_left_mobile']).'px;';
	}
	if(intval($params['padding_right_mobile']) > 0 || $params['padding_right_mobile'] === '0') {
		$responsive_mobile_styles .= 'padding-right: '.intval($params['padding_right_mobile']).'px;';
	}
	foreach(array('left', 'right', 'top', 'bottom') as $margin_dir) {
		if(intval($params['margin_'.$margin_dir]) > 0 || $params['margin_'.$margin_dir] === '0') {
			$responsive_styles .= 'margin-'.$margin_dir.': '.intval($params['margin_'.$margin_dir]).'px;';
		}
		if(intval($params['margin_'.$margin_dir.'_tablet']) > 0 || $params['margin_'.$margin_dir.'_tablet'] === '0') {
			$responsive_tablet_styles .= 'margin-'.$margin_dir.': '.intval($params['margin_'.$margin_dir.'_tablet']).'px;';
		}
		if(intval($params['margin_'.$margin_dir.'_mobile']) > 0 || $params['margin_'.$margin_dir.'_mobile'] === '0') {
			$responsive_mobile_styles .= 'margin-'.$margin_dir.': '.intval($params['margin_'.$margin_dir.'_mobile']).'px;';
		}
	}
	$button_custom_css ='';
	if(!empty($responsive_styles)) {
		$button_custom_css .='.'.$unique.' .gem-button {'.$responsive_styles.'}';
	}
	if(!empty($responsive_tablet_styles)) {
		$button_custom_css .='@media (max-width: 992px) {.'.$unique.' .gem-button {'.$responsive_tablet_styles.'}}';
	}
	if(!empty($responsive_mobile_styles)) {
		$button_custom_css .='@media (max-width: 767px) {.'.$unique.' .gem-button {'.$responsive_mobile_styles.'}}';
	}
	if(floatval($params['letter_spacing']) > 0 || $params['letter_spacing'] === '0') {
		$button_custom_css .='.'.$unique.' .gem-button {letter-spacing: '.floatval($params['letter_spacing']).'px;}';
	}
	if(!empty($params['text_transform'])) {
		$button_custom_css .='.'.$unique.' .gem-button {text-transform: '.$params['text_transform'].';}';
	}
	if($params['text_color']) {
		$button_custom_css .='.'.$unique.' .gem-button svg {fill: '.$params['text_color'].';}';
	}
	if($params['hover_text_color']) {
		$button_custom_css .='.'.$unique.' .gem-button:hover svg {fill: '.$params['hover_text_color'].';}';
	}
	if(!empty($button_custom_css)) {
		$output .= '<style type="text/css">'.$button_custom_css.'</style>';
	}

	$editor_attr = thegem_data_editor_attribute('gem-button-position-'.$params['position']);

	if(!empty($params['text_style'])) {
		$params['text'] = '<span class="gem-button-text '.$params['text_style'].($params['text_weight'] === 'thin' ? ' light' : '').'">'.$params['text'].'</span>';
		$params['attributes']['class'] .= ' gem-button-flex';
	}
	if(($params['icon_source'] === 'image' || $params['icon_source'] === 'svg') && !empty($params['icon_image_file'])) {
		$params['attributes']['class'] .= ' gem-button-flex';
	}

	$output .= '<div'.$id.' class="'.esc_attr('gem-button-container gem-button-position-'.$params['position'].$el_class).'" '.$interactions_data.' '.$delay.$editor_attr.'>';
	if($params['separator']) {
		$output .= '<div class="gem-button-separator gem-button-separator-type-'.esc_attr($params['separator']).'">'.$sep.'<div class="gem-button-separator-button">';
	}
	$output .= '<'.$params['tag'];
	if($params['title']) {
		$output .= ' title="'.esc_attr($params['title']).'"';
	}
	$output .= ' class="'.esc_attr('gem-button gem-button-size-'.$params['size'].' gem-button-style-'.$params['style'].' gem-button-text-weight-'.$params['text_weight'].($params['style'] == 'outline' ? ' gem-button-border-'.$params['border'] : '').($params['text'] == '' ? ' gem-button-empty' : '').((($params['icon'] && $params['icon_source'] !== 'image' && $params['icon_source'] !== 'svg') || (($params['icon_source'] === 'image' || $params['icon_source'] === 'svg') && !empty($params['icon_image_file']))) && $params['text'] != '' ? ' gem-button-icon-position-'.$params['icon_position'] : '').($params['no-uppercase'] ? ' gem-button-no-uppercase' : '').(empty($params['attributes']['class']) ? '' : ' '.$params['attributes']['class']) . ($is_effects_enabled && !$is_enabled_button_animation ? ' lazy-loading-item' : '')) .'"';
	$output .= !$is_enabled_button_animation ? ' data-ll-effect="drop-right-without-wrap"' : '';
	$output .= ' style="';
	$output .= 'border-radius: '.esc_attr($params['corner']).'px;';
	if($params['style'] == 'outline' && $params['border_color']) {
		$output .= 'border-color: '.esc_attr($params['border_color']).';';
	}
	if($params['style'] == 'flat' && $params['background_color']) {
		$output .= 'background-color: '.esc_attr($params['background_color']).';';
	}
	if ($params['gradient_backgound_angle'] == 'cusotom_deg') {
		$params['gradient_backgound_angle'] = $params['gradient_backgound_cusotom_deg'].'deg';
	}

	if($params['gradient_backgound'] == 1 && $params['gradient_backgound_style'] == 'linear') {
		$output .= 'background: linear-gradient('.$params['gradient_backgound_angle'].', '.$params['gradient_backgound_from'].', '.$params['gradient_backgound_to'].');';
	}
	if($params['gradient_backgound'] == 1 && $params['gradient_backgound_style'] == 'radial') {
		$output .= 'background: radial-gradient('.$params['gradient_radial_backgound_position'].', '.$params['gradient_backgound_from'].', '.$params['gradient_backgound_to'].');';
	}
	if($params['gradient_backgound'] == 1 && $params['gradient_backgound_style'] == 'radial' && $params['gradient_radial_swap_colors'] == 1 )  {
		$output .= 'background: radial-gradient('.$params['gradient_radial_backgound_position'].', '.$params['gradient_backgound_to'].', '.$params['gradient_backgound_from'].');';
	}

	if($params['text_color']) {
		$output .= 'color: '.esc_attr($params['text_color']).';';
	}
	$output .= '"';
	$output .= ' onmouseleave="';
	if($params['style'] == 'outline' && $params['border_color']) {
		$output .= 'this.style.borderColor=\''.esc_attr($params['border_color']).'\';';
	}
	if($params['style'] == 'flat' && $params['background_color']) {
		$output .= 'this.style.backgroundColor=\''.esc_attr($params['background_color']).'\';';
	}
	if($params['style'] == 'outline' && $params['hover_background_color']) {
		$output .= 'this.style.backgroundColor=\'transparent\';';
	}
	if($params['text_color']) {
		$output .= 'this.style.color=\''.esc_attr($params['text_color']).'\';';
	}
	if($params['gradient_backgound'] == 1 && $params['gradient_backgound_style'] == 'linear') {
		$output .= 'this.style.background=\'linear-gradient(' . $params['gradient_backgound_angle'] .' , '.  $params['gradient_backgound_from'] .' , '. $params['gradient_backgound_to'].')\';';
	}
	if($params['gradient_backgound'] == 1 && $params['gradient_backgound_style'] == 'radial') {
		$output .= 'this.style.background=\'radial-gradient(' . $params['gradient_radial_backgound_position'] .' , '.  $params['gradient_backgound_from'] .' , '. $params['gradient_backgound_to'].')\';';
	}
	if($params['gradient_backgound'] == 1 && $params['gradient_backgound_style'] == 'radial' && $params['gradient_radial_swap_colors'] == 1 )  {
		$output .= 'this.style.background=\'radial-gradient(' . $params['gradient_radial_backgound_position'] .' , '.  $params['gradient_backgound_to'] .' , '. $params['gradient_backgound_from'].')\';';
	}
	$output .= '"';
	$output .= ' onmouseenter="';
	if($params['hover_border_color']) {
		$output .= 'this.style.borderColor=\''.esc_attr($params['hover_border_color']).'\';';
	}
	if($params['hover_background_color']) {
		$output .= 'this.style.backgroundColor=\''.esc_attr($params['hover_background_color']).'\';';
	}
	if($params['hover_text_color']) {
		$output .= 'this.style.color=\''.esc_attr($params['hover_text_color']).'\';';
	}
	if($params['gradient_backgound'] == 1 && $params['gradient_backgound_style'] == 'linear') {
		$output .= 'this.style.background=\'linear-gradient(' . $params['gradient_backgound_angle'] .' , '.  $params['gradient_backgound_hover_from'] .' , '. $params['gradient_backgound_hover_to'].')\';';
	}
	if($params['gradient_backgound'] == 1 && $params['gradient_backgound_style'] == 'radial') {
		$output .= 'this.style.background=\'radial-gradient(' . $params['gradient_radial_backgound_position'] .' , '.  $params['gradient_backgound_hover_from'] .' , '. $params['gradient_backgound_hover_to'].')\';';
	}
	if($params['gradient_backgound'] == 1 && $params['gradient_backgound_style'] == 'radial' && $params['gradient_radial_swap_colors'] == 1 ) {
		$output .= 'this.style.background=\'radial-gradient(' . $params['gradient_radial_backgound_position'] .' , '.  $params['gradient_backgound_hover_to'] .' , '. $params['gradient_backgound_hover_from'].')\';';

	}
	$output .= '"';
	if($params['tag'] == 'a') {
		$output .= ' href="'.$params['href'].'"';
		$output .= ' target="'.esc_attr($params['target']).'"';
	}
	if(!empty($params['attributes']) && is_array($params['attributes'])) {
		foreach($params['attributes'] as $param => $value) {
			if($param != 'class') {
				$output .= ' '.esc_attr($param).'="'.esc_attr($value).'"';
			}
		}
	}
	if($params['tag'] != 'input') {
		$output .= '>';
		if(($params['icon'] && $params['icon_source'] !== 'image' && $params['icon_source'] !== 'svg') || (($params['icon_source'] === 'image' || $params['icon_source'] === 'svg') && !empty($params['icon_image_file']))) {
			if($params['icon_position'] == 'left') {
				$output .= thegem_build_icon($params['icon_pack'], $params['icon'], '', $params['icon_source'], $params['icon_image_file']).$params['text'];
			} else {
				$output .= $params['text'].thegem_build_icon($params['icon_pack'], $params['icon'], '', $params['icon_source'], $params['icon_image_file']);
			}
		} else {
			$output .= $params['text'];
		}
		$output .= '</'.$params['tag'].'>';
	} else {
		$output .= ' />';
	}
	if($params['separator']) {
		$output .= '</div>'.$sep.'</div>';
	}
	$output .= '</div> ';
	if($echo) {
		echo $output;
	} else {
		return $output;
	}
}
}

if(!function_exists('thegem_build_icon')) {
function thegem_build_icon($pack, $icon, $class = '', $source ='', $image = '') {
	if($icon && $source !== 'image') {
		if(in_array($pack, array('elegant', 'material', 'fontawesome', 'userpack', 'thegemdemo', 'thegem-header'))) {
			wp_enqueue_style('icons-'.$pack);
			return '<i class="gem-print-icon gem-icon-pack-'.esc_attr($pack).' '.esc_attr($class).'">&#x'.$icon.';</i>';
		} else {
			return '<i class="gem-print-icon gem-icon-pack-'.esc_attr($pack).' gem-icon-'.esc_attr($icon).' '.esc_attr($class).'"></i>';
		}
	} elseif($source === 'image' && !empty($image)) {
		return '<img class="gem-print-icon" src="' . thegem_attachment_url($image) . '" alt="#" />';
	} elseif($source === 'svg' && !empty($image)) {
		return thegem_get_svg_content($image, array('class' => 'gem-print-icon'));
	}
}
}

if(!function_exists('thegem_get_svg_content')) {
function thegem_get_svg_content($file, $atts = array()) {
	$output = '';
	$filename = $file;
	if(get_attached_file($file)) {
		$filename = get_attached_file($file);
	}
	if(!empty($filename) && file_exists($filename)) {
		$content = file_get_contents($filename);
		$content = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $content);
		preg_match('/<\s*svg\s*(?<attr>[^>]*?)?>(?<content>.*?)?<\s*\/\s*svg\s*>/ims', $content, $svg);
		if(!empty($svg['content'])) {
			$svg_attrs = !empty($svg['attr']) ? wp_kses_hair($svg['attr'], wp_allowed_protocols()) : array();
			unset($svg_attrs['id']);
			$attrs = '';
			if(!empty($svg_attrs)) {
				foreach($svg_attrs as $a) {
					$val = $a['value'].(!empty($atts[$a['name']]) ? ' '.$atts[$a['name']] : '');
					unset($atts[$a['name']]);
					$attrs .= ' '.esc_attr($a['name']).'="'.esc_attr($val).'"';
				}
				foreach($atts as $k => $a) {
					$attrs .= ' '.esc_attr($k).'="'.esc_attr($a).'"';
				}
			}
			$output = '<svg'.$attrs.'>'.$svg['content'].'</svg>';
		}
	}
	return $output;
}
}

function thegem_get_post_featured_content($post_id, $thumb_size = 'thegem-blog-default-large', $single = false, $picture_sources=array(), $audio_with_thumb = false) {
	$format = get_post_format($post_id);
	if (get_post_type($post_id) == 'thegem_pf_item') {
		$post_item_data = thegem_get_sanitize_pf_item_data($post_id);
		foreach ($post_item_data as $key => $value) {
			if (strpos($key, 'grid_appearance_') !== false) {
				$post_item_data[str_replace('grid_appearance_','', $key)] = $value;
				unset($post_item_data[$key]);
			}
		}
	} else if (get_post_type($post_id) == 'post' || get_post_type($post_id) == 'page' || get_post_type($post_id) == 'product') {
		$post_item_data = thegem_get_sanitize_post_data($post_id);
	} else {
		$post_item_data = thegem_get_sanitize_cpt_item_data($post_id);
	}
	$output = '';
	if(!empty($post_item_data['show_featured_content']) || !$single) {
		if($format == 'video' && $post_item_data['video']) {
			$aspect_percents = thegem_aspect_ratio_to_percents($post_item_data['video_aspect_ratio']);
			$video_block = '';
			if($post_item_data['video_type'] == 'youtube') {
				$video_block = '<iframe frameborder="0" allowfullscreen="allowfullscreen" scrolling="no" marginheight="0" marginwidth="0" src="'.esc_url('//www.youtube.com/embed/'.$post_item_data['video'].'?rel=0&amp;wmode=opaque').'"></iframe>';
				if (class_exists('TheGemGdpr')) {
					$video_block = TheGemGdpr::getInstance()->replace_disallowed_content($video_block, TheGemGdpr::CONSENT_NAME_YOUTUBE);
				}
			} elseif($post_item_data['video_type'] == 'vimeo') {
				$video_block = '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.esc_url('//player.vimeo.com/video/'.$post_item_data['video'].'?title=0&amp;byline=0&amp;portrait=0').'"></iframe>';
				if (class_exists('TheGemGdpr')) {
					$video_block = TheGemGdpr::getInstance()->replace_disallowed_content($video_block, TheGemGdpr::CONSENT_NAME_VIMEO);
				}
			} else {
				wp_enqueue_style('wp-mediaelement');
				wp_enqueue_script('thegem-mediaelement');
				$img = thegem_generate_thumbnail_src(get_post_thumbnail_id($post_id), $thumb_size);
				$poster = $img ? esc_url($img[0]) : '';
				$video_block = '<video width="100%" height="100%" controls="controls" src="'.esc_url($post_item_data['video']).'" '.(has_post_thumbnail() ? ' poster="'.$poster.'"' : '').' preload="none"></video>';
			}
			$output = '<div class="video-block" style="padding-top: '.esc_attr($aspect_percents).'%;">'.$video_block.'</div>';
		} elseif($format == 'audio') {
			$output = '';
			if ($audio_with_thumb && has_post_thumbnail()) {
				ob_start();
				thegem_generate_picture(get_post_thumbnail_id($post_id), $thumb_size, $picture_sources, array('class' => 'img-responsive', 'alt' => get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true)));
				$image = ob_get_clean();
				if($single) {
					$output .= $image;
				} else {
					$output .= '<a href="'.esc_url(get_permalink($post_id)).'">'.$image.'</a>';
				}
			}
			if ($post_item_data['audio']) {
				$output .= '<div class="audio-block"><audio width="100%" controls="controls" src="'.esc_url($post_item_data['audio']).'" preload="none"></audio></div>';
			}
		} elseif($format == 'gallery' && thegem_is_plugin_active('thegem-elements/thegem-elements.php') && $post_item_data['gallery']) {
			ob_start();
			thegem_simple_gallery(array('gallery' => $post_item_data['gallery'], 'thumb_size' => $thumb_size, 'autoscroll' => $post_item_data['gallery_autoscroll'], 'responsive' => 1));
			$output = ob_get_clean();
		} elseif($format == 'quote' && $post_item_data['quote_text']) {
			$output = '<blockquote'.($post_item_data['quote_background'] ? ' style="background-color: '.$post_item_data['quote_background'].';"' : '').'>'.$post_item_data['quote_text'];
			if($post_item_data['quote_author'] || !$single) {
				$quote_author = $post_item_data['quote_author'] ? '<div class="quote-author"'.($post_item_data['quote_author_color'] ? ' style="color: '.$post_item_data['quote_author_color'].';"' : '').'>'.$post_item_data['quote_author'].'</div>' : '';
				$quote_link = !$single ? '<div class="quote-link"'.($post_item_data['quote_author_color'] ? ' style="color: '.$post_item_data['quote_author_color'].';"' : '').'><a href="'.esc_url(get_permalink($post_id)).'"></a></div>' : '';
				$output .= '<div class="quote-bottom clearfix">'.$quote_author.$quote_link.'</div>';
			}
			$output .= '</blockquote>';
		} elseif(has_post_thumbnail()) {
			ob_start();
			thegem_generate_picture(get_post_thumbnail_id($post_id), $thumb_size, $picture_sources, array('class' => 'img-responsive', 'alt' => get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true)));
			$image = ob_get_clean();
			if($single) {
				$output = $image;
			} else {
				$output = '<a href="'.esc_url(get_permalink($post_id)).'">'.$image.'</a>';
			}
		}
		$output = $output ? '<div class="post-featured-content">'.$output.'</div>' : '';
	}
	return $output;
}

if(!function_exists('thegem_add_srcset_rule')) {
	function thegem_add_srcset_rule(&$srcset, $condition, $size, $id=false) {
		if (!$id) {
			$id = get_post_thumbnail_id();
		}
		$im = thegem_generate_thumbnail_src($id, $size);
		$srcset[$condition] = $im[0];
	}
}

if(!function_exists('thegem_srcset_list_to_string')) {
	function thegem_srcset_list_to_string($srcset) {
		if (count($srcset) == 0) {
			return '';
		}
		$srcset_condtions = array();
		foreach ($srcset as $condition => $url) {
			$srcset_condtions[] = $url . ' ' . $condition;
		}
		return implode(', ', $srcset_condtions);
	}
}

if(!function_exists('thegem_quickfinder_srcset')) {
	function thegem_quickfinder_srcset($thegem_item_data) {
		$attr = array('srcset' => array());

		switch ($thegem_item_data['icon_size']) {
			case 'small':
			case 'medium':
				$attr['srcset']['1x'] = 'thegem-person-80';
				$attr['srcset']['2x'] = 'thegem-person-160';
				break;

			case 'large':
				$attr['srcset']['1x'] = 'thegem-person-160';
				$attr['srcset']['2x'] = 'thegem-person';
				break;

			case 'xlarge':
				$attr['srcset']['1x'] = 'thegem-person-240';
				$attr['srcset']['2x'] = 'thegem-person';
				break;
		}

		return $attr;
	}
}

if(!function_exists('thegem_post_picture')) {
	function thegem_post_picture($default_size, $sources=array(), $attrs=array(), $dummy = true) {
		if (has_post_thumbnail()) {
			thegem_generate_picture(get_post_thumbnail_id(), $default_size, $sources, $attrs);
		} elseif ($dummy) {
			if (empty($attrs['class'])) {
				$attrs['class'] = 'gem-dummy';
			} else {
				$attrs['class'] .= ' gem-dummy';
			}
			echo '<span class="' . esc_attr($attrs['class']) . '"></span>';
		}
	}
}

if(!function_exists('thegem_generate_picture')) {
	function thegem_generate_picture($attachment_id, $default_size, $sources=array(), $attrs=array(), $return_info=false) {
		if (!$attachment_id || (!in_array($default_size, ['full', 'woocommerce_thumbnail', 'woocommerce_single', 'thumbnail', 'medium', 'medium_large', 'large', '1536x1536', '2048x2048']) && !in_array($default_size, array_keys(thegem_image_sizes())))) {
			return '';
		}
		$default_image = thegem_generate_thumbnail_src($attachment_id, $default_size);
		if (!$default_image) {
			return '';
		}
		list($src, $width, $height) = $default_image;
		$hwstring = image_hwstring($width, $height);

		$default_attrs = array('class' => "attachment-$default_size");
		if (empty($attrs['alt'])) {
			$attachment = get_post($attachment_id);
			$attrs['alt'] = trim(strip_tags(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)));
			if ($attachment) {
				if(empty($default_attr['alt']))
					$attrs['alt'] = trim(strip_tags($attachment->post_excerpt));
				if(empty($default_attr['alt']))
					$attrs['alt'] = trim(strip_tags($attachment->post_title));
			}
		}

		$attrs = wp_parse_args($attrs, $default_attrs);
		$attrs = array_map('esc_attr', $attrs);
		$attrs_set = array();
		foreach ($attrs as $attr_key => $attr_value) {
			$attrs_set[] = $attr_key . '="' . $attr_value . '"';
		}
		?>
		<picture>
			<?php thegem_generate_picture_sources($attachment_id, $sources); ?>
			<img src="<?php echo $src; ?>" <?php echo $hwstring; ?> <?php echo implode(' ', $attrs_set); ?> />
		</picture>
		<?php
		if ($return_info) {
			return array(
				'default' => $default_image
			);
		}
	}
}

if(!function_exists('thegem_generate_picture_sources')) {
	function thegem_generate_picture_sources($attachment_id, $sources) {
		if (!$sources) {
			return '';
		}
		?>
		<?php foreach ($sources as $source): ?>
			<?php
				if(!thegem_get_option('mobile_thumbnails_enabled') && !empty($source['media'])) {
					continue;
				}
				$srcset = thegem_srcset_generate_urls($attachment_id, $source['srcset']);
				if (!$srcset) {
					continue;
				}
			?>
			<source srcset="<?php echo thegem_srcset_list_to_string($srcset); ?>" <?php if(!empty($source['media'])): ?>media="<?php echo esc_attr($source['media']); ?>"<?php endif; ?> <?php if(!empty($source['type'])): ?>type="<?php echo esc_attr($source['type']); ?>"<?php endif; ?> sizes="<?php echo !empty($source['sizes']) ? esc_attr($source['sizes']) : '100vw'; ?>">
		<?php endforeach; ?>
		<?php
	}
}

if(!function_exists('thegem_srcset_generate_urls')) {
	function thegem_srcset_generate_urls($attachment_id, $srcset) {
		$result = array();
		$thegem_sizes = array_keys(thegem_image_sizes());
		foreach ($srcset as $condition => $size) {
			if (!in_array($size, $thegem_sizes)) {
				continue;
			}
			if(!thegem_get_option('retina_thumbnails_enabled') && $condition !== '1x') {
				continue;
			}
			$im = thegem_generate_thumbnail_src($attachment_id, $size);
			if ($im) {
				$result[$condition] = esc_url($im[0]);
			}
		}
		return $result;
	}
}

function thegem_page_options_get_post_data($page_data, $post_id, $item_data, $type) {
	if($post_id == 0) {
		if(is_search() && $type != 'search') {
			$page_data = thegem_theme_options_get_page_settings('search');
		}
		if(is_home() && $type != 'blog') {
			$page_data = thegem_theme_options_get_page_settings('blog');
		}
	}
	return $page_data;
}
add_filter('thegem_page_title_data', 'thegem_page_options_get_post_data', 10, 4);
add_filter('thegem_page_header_data', 'thegem_page_options_get_post_data', 10, 4);
add_filter('thegem_page_effects_data', 'thegem_page_options_get_post_data', 10, 4);
add_filter('thegem_page_preloader_data', 'thegem_page_options_get_post_data', 10, 4);
add_filter('thegem_page_slideshow_data', 'thegem_page_options_get_post_data', 10, 4);
add_filter('thegem_page_sidebar_data', 'thegem_page_options_get_post_data', 10, 4);

function thegem_remove_hentry_class($classes) {
	$classes = array_diff($classes, array('hentry'));
	return $classes;
}
add_filter('post_class', 'thegem_remove_hentry_class');

function thegem_get_attached_file_filter($file, $attachment_id) {
	if ($attachment_id === 'THEGEM_TRANSPARENT_IMAGE') {
		return get_template_directory() . '/images/dummy/transparent.png';
	}

	return $file;
}
add_filter('get_attached_file', 'thegem_get_attached_file_filter', 10, 2);

function thegem_attachment_thumbnail_path_filter($thumb_path, $attachment_id) {
	if ($attachment_id === 'THEGEM_TRANSPARENT_IMAGE') {
		$uploads = wp_upload_dir();
		return $uploads['path'] . '/' . basename($thumb_path);
	}

	return $thumb_path;
}
add_filter('thegem_attachment_thumbnail_path', 'thegem_attachment_thumbnail_path_filter', 10, 2);

function thegem_wpcf7_form_class_attr($class) {
	if(substr_count($class, 'gem-contact-form-white')) {
		$GLOBALS['thegem_wpcf_style'] = 'white';
	}
	if(substr_count($class, 'gem-contact-form-dark')) {
		$GLOBALS['thegem_wpcf_style'] = 'dark';
	}
	return $class;
}
add_filter( 'wpcf7_form_class_attr', 'thegem_wpcf7_form_class_attr');

function thegem_wpcf7_form_response_output($output) {
	if(!empty($GLOBALS['thegem_wpcf_style'])) {
		unset($GLOBALS['thegem_wpcf_style']);
	}
	return $output;
}
add_filter( 'wpcf7_form_response_output', 'thegem_wpcf7_form_response_output');

remove_action( 'wpcf7_init', 'wpcf7_add_form_tag_submit' );
add_action( 'wpcf7_init', 'thegem_wpcf7_add_form_tag_submit' );
function thegem_wpcf7_add_form_tag_submit() {
	wpcf7_add_form_tag( 'submit', 'thegem_wpcf7_submit_form_tag_handler' );
}

function thegem_wpcf7_submit_form_tag_handler( $tag ) {
	$class = wpcf7_form_controls_class( $tag->type, 'has-spinner' );

	$atts = array();

	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_id_option();
	$atts['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );

	$value = isset( $tag->values[0] ) ? $tag->values[0] : '';

	if ( empty( $value ) ) {
		$value = __( 'Send', 'contact-form-7' );
	}

	$atts['type'] = 'submit';
	$atts['value'] = $value;

	if(isset($GLOBALS['thegem_wpcf_style']) && ($GLOBALS['thegem_wpcf_style'] == 'white' || $GLOBALS['thegem_wpcf_style'] == 'dark')) {
		$form_style = $GLOBALS['thegem_wpcf_style'] == 'white' ? 'light' : 'dark';
		if(thegem_get_option('contact_form_'.$form_style.'_custom_styles')) {
			$atts['class'] .= ' gem-button-wpcf-custom';
			$html = thegem_button(array(
				'tag' => 'input',
				'text' => $value,
				'style' => thegem_get_option('contact_form_'.$form_style.'_button_style'),
				'size' => thegem_get_option('contact_form_'.$form_style.'_button_size'),
				'text_weight' => thegem_get_option('contact_form_'.$form_style.'_button_text_weight'),
				'no-uppercase' => thegem_get_option('contact_form_'.$form_style.'_button_no_uppercase'),
				'corner' => thegem_get_option('contact_form_'.$form_style.'_button_corner'),
				'border' => thegem_get_option('contact_form_'.$form_style.'_button_border'),
				'position' => thegem_get_option('contact_form_'.$form_style.'_button_position'),
				'text_color' => thegem_get_option('contact_form_'.$form_style.'_button_text_color'),
				'background_color' => thegem_get_option('contact_form_'.$form_style.'_button_background_color'),
				'border_color' => thegem_get_option('contact_form_'.$form_style.'_button_border_color'),
				'hover_text_color' => thegem_get_option('contact_form_'.$form_style.'_button_hover_text_color'),
				'hover_background_color' => thegem_get_option('contact_form_'.$form_style.'_button_hover_background_color'),
				'hover_border_color' => thegem_get_option('contact_form_'.$form_style.'_button_hover_border_color'),
				'attributes' => $atts,
			));
			return $html;
		}
	}

	$atts = wpcf7_format_atts( $atts );
	$html = sprintf( '<input %1$s />', $atts );

	return $html;
}

function thegem_yikes_mailchimp_form_submit_button_classes($classes) {
	return $classes.' gem-button';
}
add_filter('yikes-mailchimp-form-submit-button-classes', 'thegem_yikes_mailchimp_form_submit_button_classes');

function thegem_tribe_events_views_v2_bootstrap_html($html, $context, $view_slug, $query) {
	$thegem_page_id = is_singular() ? get_the_ID() : 0;
	$thegem_shop_page = 0;
	$thegem_page_data = thegem_get_output_page_settings($thegem_page_id);
	if(is_tax()) {
		$thegem_term_id = get_queried_object()->term_id;
		$thegem_page_data = thegem_get_output_page_settings($thegem_term_id, array(), 'term');
	}
	$thegem_no_margins_block = '';
	if ($thegem_page_data['effects_no_bottom_margin'] || $thegem_page_data['content_padding_bottom'] === 0) {
		$thegem_no_margins_block .= ' no-bottom-margin';
	}
	if ($thegem_page_data['effects_no_top_margin'] || $thegem_page_data['content_padding_top'] === 0) {
		$thegem_no_margins_block .= ' no-top-margin';
	}

	$thegem_panel_classes = array('panel', 'row');
	$thegem_center_classes = 'panel-center';
	$thegem_sidebar_classes = '';
	if (is_active_sidebar('page-sidebar') && $thegem_page_data['sidebar_show'] && $thegem_page_data['sidebar_position']) {
		$thegem_panel_classes[] = 'panel-sidebar-position-' . $thegem_page_data['sidebar_position'];
		$thegem_panel_classes[] = 'with-sidebar';
		$thegem_center_classes .= ' col-lg-9 col-md-9 col-sm-12';
		if ($thegem_page_data['sidebar_position'] == 'left') {
			$thegem_center_classes .= ' col-md-push-3 col-sm-push-0';
			$thegem_sidebar_classes .= ' col-md-pull-9 col-sm-pull-0';
		}
	} else {
		$thegem_center_classes .= ' col-xs-12';
	}
	if ($thegem_page_data['sidebar_sticky']) {
		$thegem_panel_classes[] = 'panel-sidebar-sticky';
		wp_enqueue_script('thegem-sticky');
	}
	$thegem_pf_data = array();
	if (get_post_type() == 'thegem_pf_item') {
		$thegem_pf_data = thegem_get_sanitize_pf_item_data(get_the_ID());
	}
	if ($thegem_page_data['title_show'] && $thegem_page_data['title_style'] == 3 && $thegem_page_data['slideshow_type']) {
		thegem_slideshow_block(array('slideshow_type' => $thegem_page_data['slideshow_type'], 'slideshow' => $thegem_page_data['slideshow_slideshow'], 'lslider' => $thegem_page_data['slideshow_layerslider'], 'slider' => $thegem_page_data['slideshow_revslider'], 'preloader' => !empty($thegem_page_data['slideshow_preloader'])));
	}

	$html_before = '<div id="main-content" class="main-content">'.
		'<div class="block-content'.esc_attr($thegem_no_margins_block).'">'.
			'<div class="container"><div class="'.esc_attr(implode(' ', $thegem_panel_classes)).'">'.
				'<div class="'.esc_attr($thegem_center_classes).'">';

	$html_after = '</div>';
	if (is_active_sidebar('page-sidebar') && $thegem_page_data['sidebar_show'] && $thegem_page_data['sidebar_position']) {
		ob_start();
		echo '<div class="sidebar col-lg-3 col-md-3 col-sm-12' . esc_attr($thegem_sidebar_classes) . '" role="complementary">';
		get_sidebar('page');
		echo '</div><!-- .sidebar -->';
		$html_after .= ob_get_clean();
	}
	$html_after .= '</div></div></div><!-- .block-content -->';

	return thegem_page_title().$html_before.$html.$html_after;
}
add_filter('tribe_events_views_v2_bootstrap_html', 'thegem_tribe_events_views_v2_bootstrap_html', 10, 4);

function thegem_single_post_template() {
	if(!function_exists('thegem_get_template_type') || !is_singular()) return false;
	$post_id = get_the_ID();
	$post_data = thegem_get_sanitize_post_data($post_id);
	if($post_data['post_layout_source'] !== 'builder') return false;
	$template_id = intval($post_data['post_builder_template']);
	if($template_id < 1) return false;
	$template = get_post($template_id);
	if($template && thegem_get_template_type($template_id) == 'single-post') {
		return $template_id;
	}
	return false;
}

function thegem_page_template() {
	if(!function_exists('thegem_get_template_type') || !is_page()) return false;
	$page_id = get_the_ID();
	$page_data = thegem_get_sanitize_page_item_data($page_id);
	if($page_data['page_layout_source'] !== 'builder') return false;
	$template_id = intval($page_data['page_builder_template']);
	if($template_id < 1) return false;
	$template = get_post($template_id);
	if($template && thegem_get_template_type($template_id) == 'single-post') {
		return $template_id;
	}
	return false;
}

function thegem_portfolio_template() {
	if(!function_exists('thegem_get_template_type') || !is_singular('thegem_pf_item')) return false;
	$post_id = get_the_ID();
	$portfolio_data = thegem_get_sanitize_pf_item_data($post_id);
	if(!isset($portfolio_data['portfolio_layout_source']) || $portfolio_data['portfolio_layout_source'] !== 'builder') return false;
	$template_id = intval($portfolio_data['portfolio_builder_template']);
	if($template_id < 1) return false;
	$template = get_post($template_id);
	if($template && thegem_get_template_type($template_id) == 'portfolio') {
		return $template_id;
	}
	return false;
}

function thegem_cpt_template() {
	if(!function_exists('thegem_get_template_type') || !is_singular() || !in_array(get_post_type(), thegem_get_available_po_custom_post_types(), true)) return false;
	$post_id = get_the_ID();
	$cpt_data = thegem_get_sanitize_cpt_item_data($post_id);
	if(!isset($cpt_data['layout_source']) || $cpt_data['layout_source'] !== 'builder') return false;
	$template_id = intval($cpt_data['builder_template']);
	if($template_id < 1) return false;
	$template = get_post($template_id);
	if($template && thegem_get_template_type($template_id) == 'single-post') {
		return $template_id;
	}
	return false;
}

function thegem_disable_cache() {
	//if(!(get_option('thegem_enabled_wpsupercache_autoptimize')) || !function_exists('wpsc_init')) return ;
	$disable_cache = false;
	$thegem_page_id = is_singular() ? get_the_ID() : 0;
	$thegem_page_data = thegem_get_output_page_settings($thegem_page_id);
	if(!empty($thegem_page_data['disable_cache'])) {
		$disable_cache = true;
	}
	if(function_exists('is_cart') && is_cart()) {
		$disable_cache = true;
	}
	if(function_exists('is_checkout') && is_checkout()) {
		$disable_cache = true;
	}
	if((function_exists('yith_wcwl_is_wishlist') && yith_wcwl_is_wishlist()) || (function_exists('yith_wcwl_is_wishlist_page') && yith_wcwl_is_wishlist_page())) {
		$disable_cache = true;
	}
	if($disable_cache && !defined('DONOTCACHEPAGE')) {
		define('DONOTCACHEPAGE', 1);
	}
}
add_action('wp_head', 'thegem_disable_cache');

add_filter('wpcf7_autop_or_not', '__return_false');

function thegem_clean_contact_forms() {
	$cf7 = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );
	if($cf7) {
		foreach($cf7 as $cform) {
			$mail = get_post_meta($cform->ID, '_mail', true);
			if(!empty($mail) && !empty($mail['body'])) {
				$mail['body'] = str_replace(array('http://democontent.codex-themes.com', 'https://democontent.codex-themes.com'), site_url(), $mail['body']);
				update_post_meta($cform->ID, '_mail', $mail);
			}
			$mail = get_post_meta($cform->ID, '_mail_2', true);
			if(!empty($mail) && !empty($mail['body'])) {
				$mail['body'] = str_replace(array('http://democontent.codex-themes.com', 'https://democontent.codex-themes.com'), site_url(), $mail['body']);
				update_post_meta($cform->ID, '_mail_2', $mail);
			}
		}
	}
}

function thegem_fix_wpml_missing_language() {
	if(get_option('thegem_fix_wpml_missing_language') && defined('ICL_SITEPRESS_VERSION')) {
		global $iclTranslationManagement;
		$iclTranslationManagement->add_missing_language_information();
		delete_option('thegem_fix_wpml_missing_language');
	}
}
add_action('init', 'thegem_fix_wpml_missing_language', 10);

/* Content for Off-Canvas Area */

function theged_add_off_canvas_content_to_menu_start() {
	if(thegem_get_option('header_layout') == 'fullwidth_hamburger' || thegem_get_option('header_layout') == 'overlay') {
		if(thegem_get_option('header_content_offcanvas_area') == 'global_section' && thegem_get_option('header_content_offcanvas_global_section')) {
			$GLOBALS['thegem_menu_template'] = thegem_get_option('header_content_offcanvas_global_section');
			add_filter('wp_nav_menu_items', 'thegem_menu_insert_template', 10, 2);
			add_filter('thegem_nav_menu_class', 'thegem_menu_off_canvas_content_class', 10, 2);
			//$settings['menu_class'] .= ' hamburger-with-template';
		}
	}
}
add_action('thegem_before_nav_menu', 'theged_add_off_canvas_content_to_menu_start');

function theged_add_off_canvas_content_to_menu_end() {
	remove_filter('wp_nav_menu_items', 'thegem_menu_insert_template');
	remove_filter('thegem_nav_menu_class', 'thegem_menu_off_canvas_content_class');
}
add_action('thegem_after_nav_menu', 'theged_add_off_canvas_content_to_menu_end');

function thegem_menu_insert_template($items, $args) {
	$template_id = $GLOBALS['thegem_menu_template'];
	$items .= '<li class="menu-item menu-item-type-template" style="display: none">';
	$template_id = intval($template_id);
	if($template_id > 0 && $template = get_post($template_id)) {
		$items .= do_shortcode('[gem_template id="'.intval($template).'"]');
	}
	$items .= '</li>';
	unset($GLOBALS['thegem_menu_template']);

	return $items;
}
function thegem_menu_off_canvas_content_class($classes) {
	$classes .= ' hamburger-with-template';
	return $classes;
}

function thegem_acf_google_map_api( $api ){
	$api['key'] = thegem_get_option('google_map_api_key');
	return $api;
}
add_filter('acf/fields/google_map/api', 'thegem_acf_google_map_api');
