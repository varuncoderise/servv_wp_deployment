<?php
/**
 * Add to wishlist button template - Browse list
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 3.0.0
 */

/**
 * Template variables:
 *
 * @var $wishlist_url              string Url to wishlist page
 * @var $exists                    bool Whether current product is already in wishlist
 * @var $show_exists               bool Whether to show already in wishlist link on multi wishlist
 * @var $product_id                int Current product id
 * @var $product_type              string Current product type
 * @var $label                     string Button label
 * @var $browse_wishlist_text      string Browse wishlist text
 * @var $already_in_wishslist_text string Already in wishlist text
 * @var $product_added_text        string Product added text
 * @var $icon                      string Icon for Add to Wishlist button
 * @var $link_classes              string Classed for Add to Wishlist button
 * @var $available_multi_wishlist  bool Whether add to wishlist is available or not
 * @var $disable_wishlist          bool Whether wishlist is disabled or not
 * @var $template_part             string Template part
 * @var $loop_position             string Loop position
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly

global $product, $thegem_product_data;

$thegem_product_data = thegem_get_output_product_page_data( $product_id );

if(isset($thegem_template) && $thegem_template) {
	$thegem_product_data['product_page_layout'] = 'default';
}

$isLegacy               = $thegem_product_data['product_page_layout'] == 'legacy';
$wishlistIconAdded      = thegem_get_option('product_page_button_added_to_wishlist_icon') ? thegem_get_option('product_page_button_added_to_wishlist_icon') : 'added-to-wishlist';
$wishlistIconAddedPack  = thegem_get_option('product_page_button_added_to_wishlist_icon_pack');
$wishlistIconAddedColor = thegem_get_option( 'product_page_button_add_to_wishlist_color_filled' ) ? thegem_get_option( 'product_page_button_add_to_wishlist_color_filled' ) : thegem_get_option( 'system_icons_font_2' );

?>

<!-- BROWSE WISHLIST MESSAGE -->
<?php if ( isset( $thegem_product_page ) && $thegem_product_page ) : ?>
	<?php
	if ( ! $isLegacy ) {
		thegem_button( array(
			'href'                   => esc_url( $wishlist_url ),
			'text_color'             => $wishlistIconAddedColor,
			'background_color'       => 'transparent',
			'hover_background_color' => 'transparent',
			'icon'                   => $wishlistIconAdded,
			'icon_pack'              => $wishlistIconAddedPack,
			'attributes'             => array(
				'rel' => 'nofollow',
			),
			'extra_class'            => 'yith-wcwl-wishlistexistsbrowse',
		), 1 );
	} else {
		thegem_button( array(
			'style'                  => 'outline',
			'text'                   => wp_kses_post( apply_filters( 'yith-wcwl-browse-wishlist-label', $browse_wishlist_text, $product_id, $icon ) ),
			'href'                   => esc_url( $wishlist_url ),
			'text_color'             => thegem_get_option( 'button_background_basic_color' ),
			'border_color'           => thegem_get_option( 'button_background_basic_color' ),
			'hover_text_color'       => thegem_get_option( 'button_outline_text_hover_color' ),
			'hover_background_color' => thegem_get_option( 'button_background_basic_color' ),
			'hover_border_color'     => thegem_get_option( 'button_background_basic_color' ),
			'icon'                   => 'browse-wishlist',
			'attributes'             => array(
				'rel' => 'nofollow',
			),
			'extra_class'            => 'yith-wcwl-wishlistexistsbrowse',
		), 1 );
	}
	?>
<?php else : ?>
	<div class="yith-wcwl-wishlistexistsbrowse">
		<span class="feedback">
			<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo wp_kses_post( $already_in_wishslist_text ); ?>
		</span>
		<a href="<?php echo esc_url( $wishlist_url ); ?>" rel="nofollow"
		   data-title="<?php echo esc_attr( $browse_wishlist_text ); ?>">
			<?php echo ( ! $is_single && 'before_image' === $loop_position ) ? $icon : false; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo wp_kses_post( apply_filters( 'yith-wcwl-browse-wishlist-label', $browse_wishlist_text, $product_id, $icon ) ); ?>
		</a>
	</div>
<?php endif; ?>
