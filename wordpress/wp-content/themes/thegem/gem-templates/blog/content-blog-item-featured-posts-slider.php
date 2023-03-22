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

<?php if (!function_exists('thegem_featured_post_categories')) {
    function thegem_featured_post_categories($params) {
        $style = '';
        if (!empty($params['post_categories_color']) && $params['style']=='new') {
            $style = 'style="background-color: '.esc_attr($params['post_categories_color']).'"';
        }

        if (!empty($params['post_categories_color']) && $params['style']=='default') {
            $style = 'style="color: '.esc_attr($params['post_categories_color']).'"';
        }
        ?>

        <span <?php if (!empty($style)) { echo $style; }?>><?php the_category(', ') ?></span>
    <?php }
} ?>

<?php if (!function_exists('thegem_featured_post_title_class')) {
    function thegem_featured_post_title_class($params) {
        $class = [];

        switch ($params['title_style']) {
            case 'small':
                $class[]='title-h4';
                break;
            case 'normal':
                $class[]='title-h2';
                break;
            case 'big':
                $class[]='title-h1';
                break;
            case 'large':
                $class[]='title-xlarge';
                break;
        }

        return implode(' ', $class);
    }
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
}?>

<?php

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

<article id="post-<?php the_ID(); ?>" <?php if (!empty($article_style)) echo 'style="'.$article_style.'"'; ?> data-background="<?php echo $background; ?>">
    <?php if ($params['use_background_overlay']): ?>
        <div class="gem-featured-posts-slide-overlay" style="background-color: <?php echo thegem_featured_post_background_color($params['overlay_color'], $params['overlay_opacity']) ?>"></div>
    <?php endif; ?>
    <div class="gem-featured-posts-slide-item">
        <?php if ($params['style'] == 'default' && !$params['hide_date']): ?><div class="gem-featured-post-date" <?php if (!empty($params['post_date_color'])) { echo 'style="color: '.esc_attr($params['post_date_color']).'"'; } ?>><?php echo get_the_date('F d, Y'); ?></div><?php endif; ?>
        <?php if ($params['style'] == 'new' && !$params['hide_categories']): ?><div class="gem-featured-post-meta-categories"><?php thegem_featured_post_categories($params); ?></div><?php endif; ?>

        <?php if (get_the_title()): ?>
            <div class="gem-featured-post-title <?php echo thegem_featured_post_title_class($params); ?>" <?php if (!empty($params['post_title_color'])) { echo 'style="color: '.esc_attr($params['post_title_color']).'"'; } ?>><?php the_title(); ?></div>
        <?php endif; ?>

        <?php if (get_the_excerpt() && !$params['hide_excerpt']): ?>
            <div class="gem-featured-post-excerpt styled-subtitle" <?php if (!empty($params['post_excerpt_color'])) { echo 'style="color: '.esc_attr($params['post_excerpt_color']).'"'; } ?>><?php the_excerpt(); ?></div>
        <?php endif; ?>

        <?php if ($params['style'] == 'default' && !$params['hide_categories']): ?><div class="gem-featured-post-meta-categories"><?php thegem_featured_post_categories($params); ?></div><?php endif; ?>
        <?php if ($params['style'] == 'new' && !$params['hide_date']): ?><div class="gem-featured-post-date" <?php if (!empty($params['post_date_color'])) { echo 'style="color: '.esc_attr($params['post_date_color']).'"'; } ?>><?php echo get_the_date('F d, Y'); ?></div><?php endif; ?>
        <?php if (!$params['hide_author']): ?><div class="gem-featured-post-meta-author" <?php if (!empty($params['post_author_color'])) { echo 'style="color: '.esc_attr($params['post_author_color']).'"'; } ?>><?php thegem_featured_post_author($params['hide_author_avatar']); ?></div><?php endif; ?>

        <?php if (!$params['hide_button']): ?><div class="gem-featured-post-btn-box"><?php thegem_button(array_merge($params['button'], array('tag' => 'a', 'href' => get_the_permalink())), 1) ?></div><?php endif; ?>
    </div>
</article>


