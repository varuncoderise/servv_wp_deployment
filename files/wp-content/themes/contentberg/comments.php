<?php 
/**
 * Template to output comment form - called via single.php
 * 
 * @see comments_template()
 */

// Bail early for password protected
if (post_password_required()) {
	return;
}

// Comments not supported or closed
if (!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) {
	return;
}

?>
<div class="comments">
	<div id="comments" class="comments-area">

	<?php if (have_comments()) : ?>
	
	<div class="comments-wrap">
		<h4 class="section-head cf">
			<span class="title">
			<?php
			
			comments_number(
				esc_html__('Comments', 'contentberg'), 
				sprintf(esc_html__('%s Comment', 'contentberg'), '<span class="number">1</span>'),
				sprintf(esc_html__('%s Comments', 'contentberg'), '<span class="number">%</span>')
			);

			?>
			</span>
		</h4>
	
		<ol class="comments-list add-separator">
			<?php
				get_template_part('partials/comment');
				wp_list_comments(array('callback' => 'contentberg_comment', 'max-depth' => 4));
			?>
		</ol>

		
		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')): // are there comments to navigate through ?>
		
		<nav class="main-pagination comment-nav cf">
			<?php // Next/previous concept is swapped for comments ?>
			<div class="next"><?php previous_comments_link('<i class="icon icon-arrow-prev"></i>' . esc_html__('Previous', 'contentberg')); ?></div>
			<div class="previous"><?php next_comments_link(esc_html__('Next', 'contentberg') . '<i class="icon icon-arrow-next"></i>'); ?></div>
		</nav>
		
		<?php endif; // check for comment navigation ?>
	</div>
		
	<?php endif; ?>
	
	
	<?php 
	
	/**
	 * Configure and output the comment form
	 */
	
	$commenter = wp_get_current_commenter();
	
	// Comment field HTML
	$comment_field = '
			<div class="reply-field cf">
				<textarea name="comment" id="comment" cols="45" rows="7" placeholder="'. esc_attr_x('Enter your comment here..', 'Comment Form', 'contentberg') .'" aria-required="true" required></textarea>
			</div>
	';
	
	// Logged in user message and links
	$logged_in = sprintf(
			esc_html__('Logged in as %1$s. %2$s', 'contentberg'),
			'<a href="'. esc_url(admin_url('profile.php')) .'">'. esc_html($user_identity)  .'</a>',
			'<a href="'. esc_url(wp_logout_url(get_permalink()))  .'" title="'. esc_attr__('Log out of this account', 'contentberg') .'">'. esc_html__('Log out?', 'contentberg') .'</a>'
	);

	$consent  = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';

	$fields = array(
		'author' => '
			<div class="inline-field"> 
				<input name="author" id="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" aria-required="true" placeholder="' 
					. esc_html_x('Name', 'Comment Form', 'contentberg') . '" required />
			</div>',

		'email' => '
			<div class="inline-field"> 
				<input name="email" id="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" aria-required="true" placeholder="' 
					. esc_html_x('Email', 'Comment Form', 'contentberg') . '" required />
			</div>
		',

		'url' => '
			<div class="inline-field"> 
				<input name="url" id="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" placeholder="' 
					. esc_html_x('Website', 'Comment Form', 'contentberg') . '" />
			</div>
		',

		'cookies' => '
			<p class="comment-form-cookies-consent">
				<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />
				<label for="wp-comment-cookies-consent">' 
					. esc_html__('Save my name, email, and website in this browser for the next time I comment.', 'contentberg') .'
				</label>
			</p>',
	);

	// Not supported before 4.9.6
	if (version_compare($GLOBALS['wp_version'], '4.9.6', '<')) {
		unset($fields['cookies']);
	}

	// Apply default filter
	$fields = apply_filters('comment_form_default_fields', $fields);
	

	/**
	 * Output comment form
	 */
	comment_form(array(
		'title_reply' => '<span class="section-head"><span class="title">' . esc_html__('Write A Comment', 'contentberg') . '</span></span>',
		'title_reply_to' => '<span class="section-head cf">' . esc_html__('Reply To %s', 'contentberg') . '</span>',
		'comment_notes_before' => '',
		'comment_notes_after'  => '',
		'format' => '',

		'logged_in_as' => '<p class="logged-in-as">' . $logged_in. '</p>',
	
		'comment_field' => $comment_field,
	
		'id_submit' => 'comment-submit',
		'label_submit' => esc_html_x('Post Comment', 'Comment Form', 'contentberg'),
	
		'cancel_reply_link' => esc_html_x('Cancel Reply', 'Comment Form', 'contentberg'),

		'fields' => $fields,
		
	)); ?>

	</div><!-- #comments -->
</div>