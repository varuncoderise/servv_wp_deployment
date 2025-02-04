<?php
/**
 * Single product short description
 *
 * This template overrides woocommerce/templates/single-product/short-description.php.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @version     3.3.0
 */

$post = get_post();
$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

if ( ! $short_description ) {
	return;
}


?>
<div itemprop="description" class="text description entry-content">
	<?php echo $short_description; // WPCS: XSS ok. ?>
</div>