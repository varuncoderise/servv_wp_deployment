<?php if (!function_exists('thegem_featured_post_author')) {
    function thegem_featured_post_author($hide_author_avatar) { ?>
        <div class="author">
            <?php if (!$hide_author_avatar): ?>
                <span class="author-avatar"><?php echo get_avatar(get_the_author_meta('ID'), 50) ?></span>
            <?php endif; ?>
            <span class="author-name"><?php printf(esc_html__("by %s", "thegem"), get_the_author_link()) ?></span>
        </div>
    <?php }
} ?>

<?php if (!function_exists('thegem_featured_post_background_color')) {
    function thegem_featured_post_background_color($color, $opacity = null) {
        $rgb_color = hex_to_rgb($color);
        $opacity = floatval($opacity);

        if (is_array($rgb_color)) {
            return 'rgba('.implode(",", hex_to_rgb($color)).','.($opacity >= 0 && $opacity <= 1 ? $opacity : 0).')';
        }

        return false;
    }
}

$thegem_post_data = thegem_get_sanitize_page_title_data(get_the_ID());
$thegem_size = 'thegem-featured-post-slide';

if ($params['layout']!='container' ) {
    $thegem_size = $thegem_size.'-'.$params['layout'];
}

$featured_image_src = null;
$featured_image_width = null;
$featured_image_height = null;

if (has_post_thumbnail()) {
    list($featured_image_src, $featured_image_width, $featured_image_height) = thegem_generate_thumbnail_src(get_post_thumbnail_id(), $thegem_size);
}

$article_style = array();

if ($params['fullheight']) {
    $article_style[] = 'height: 100vh';
} else {
    $article_style[] = 'height: '.esc_attr($params['max_height']).'px';
}
$background = '';
if (!empty($featured_image_src)) {
    $background = 'url('.esc_url($featured_image_src).')';
    if ($slide_num == 0) {
		$article_style[] = 'background-image: url('.esc_url($featured_image_src).')';
	}
}
$slide_num++;

$article_style = implode(';', $article_style);

?>

<article id="post-<?php the_ID(); ?>" class="slide-item portfolio-item" <?php if (!empty($article_style)) echo 'style="'.$article_style.'"'; ?> data-background="<?php echo $background; ?>">
    <?php if ($params['use_background_overlay']): ?>
        <div class="gem-featured-posts-slide-overlay" style="background-color: <?php echo thegem_featured_post_background_color($params['overlay_color'], $params['overlay_opacity']) ?>"></div>
    <?php endif; ?>
    <div class="gem-featured-posts-slide-item">
        <?php if ($params['style'] == 'default' && !$params['hide_date']): ?><div class="gem-featured-post-date" <?php if (!empty($params['post_date_color'])) { echo 'style="color: '.esc_attr($params['post_date_color']).'"'; } ?>><?php echo get_the_date('F d, Y'); ?></div><?php endif; ?>
        <?php if ($params['style'] == 'new'):
            thegem_get_additional_meta($params);
        endif; ?>

        <?php if (get_the_title()):
            $title_tag = isset($params['title_tag']) ? $params['title_tag'] : 'div';
            $title_class = '';
            if (isset($params['title_preset']) && $params['title_preset'] != 'default') {
                $title_class = $params['title_preset'];
            }
            if (isset($params['title_font_weight'])) {
                $title_class .= ' ' . $params['title_font_weight'];
            } ?>
            <<?php echo $title_tag; ?> class="gem-featured-post-title <?php echo $title_class; ?>" <?php if (!empty($params['post_title_color'])) { echo 'style="color: '.esc_attr($params['post_title_color']).'"'; } ?>><?php the_title(); ?></<?php echo $title_tag; ?>>
        <?php endif; ?>

        <?php if (get_the_excerpt() && !$params['hide_excerpt']):
            $description_preset = '';
            if (isset($params['description_preset']) && $params['description_preset'] != 'default') {
                $description_preset = $params['description_preset'];
            }  ?>
            <div class="gem-featured-post-excerpt styled-subtitle <?php echo $description_preset; ?>" <?php if (!empty($params['post_excerpt_color'])) { echo 'style="color: '.esc_attr($params['post_excerpt_color']).'"'; } ?>>
				<div>
					<?php if ( !has_excerpt() && !empty( $thegem_post_data['title_excerpt'] ) ): ?>
						<?php echo $thegem_post_data['title_excerpt']; ?>
					<?php else: ?>
						<?php echo preg_replace('%&#x[a-fA-F0-9]+;%', '', apply_filters('the_excerpt', get_the_excerpt())); ?>
					<?php endif; ?>
				</div>
			</div>
        <?php endif;

        thegem_get_details_custom_fields($params); ?>

        <?php if ($params['style'] == 'default'):
            thegem_get_additional_meta($params);
        endif; ?>
        <?php if ($params['style'] == 'new' && !$params['hide_date']): ?><div class="gem-featured-post-date" <?php if (!empty($params['post_date_color'])) { echo 'style="color: '.esc_attr($params['post_date_color']).'"'; } ?>><?php echo get_the_date('F d, Y'); ?></div><?php endif; ?>
        <?php if (!$params['hide_author']): ?><div class="gem-featured-post-meta-author" <?php if (!empty($params['post_author_color'])) { echo 'style="color: '.esc_attr($params['post_author_color']).'"'; } ?>><?php thegem_featured_post_author($params['hide_author_avatar']); ?></div><?php endif; ?>

        <?php if ($params['show_readmore_button']):
            $link = !isset($params['readmore_button_link']) || $params['readmore_button_link'] == 'default' ? get_the_permalink() : $params['readmore_button_custom_link'];
            $id = isset($params['readmore_button_id']) ? $params['readmore_button_id'] : ''; ?>
            <div class="gem-featured-post-btn-box"><?php thegem_button(array_merge($params['button'], array('id' => $id, 'tag' => 'a', 'href' => $link)), 1) ?></div>
        <?php endif; ?>
    </div>
</article>


