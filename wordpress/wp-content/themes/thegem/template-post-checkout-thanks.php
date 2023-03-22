<?php
thegem_enqueue_woocommerce_styles();
get_header(); ?>
<style>
    @font-face {
        font-family: 'thegem-shortcodes';
        src:url('<?php echo THEGEM_THEME_URI; ?>/fonts/thegem-shortcodes.eot');
        src:url('<?php echo THEGEM_THEME_URI; ?>/fonts/thegem-shortcodes.eot?#iefix') format('embedded-opentype'),
        url('<?php echo THEGEM_THEME_URI; ?>/fonts/thegem-shortcodes.woff') format('woff'),
        url('<?php echo THEGEM_THEME_URI; ?>/fonts/thegem-shortcodes.ttf') format('truetype'),
        url('<?php echo THEGEM_THEME_URI; ?>/fonts/thegem-shortcodes.svg#thegem-shortcodes') format('svg');
        font-weight: normal;
        font-style: normal;
    }
    .template-checkout-thanks-empty-output:before {
        font-family: 'thegem-shortcodes';
        font-weight: normal;
        font-style: normal;
        font-size: 24px;
        line-height: 1;
        width: 24px;
        text-align: center;
        display: inline-block;
        vertical-align: top;
        margin-right: 5px;
    }
    .template-checkout-thanks-empty-output.thegem-te-checkout-thanks-order-overview:before {
        content: "\e65a";
    }
    .template-checkout-thanks-empty-output.thegem-te-checkout-thanks-order-details:before {
        content: "\e654";
    }
    .template-checkout-thanks-empty-output.thegem-te-checkout-thanks-cart-totals:before {
        content: "\e655";
    }
    .template-checkout-thanks-empty-output.thegem-te-checkout-thanks-customer-details:before {
        content: "\e65d";
    }
    .template-checkout-thanks-empty-output.thegem-te-checkout-thanks-billing-details:before {
        content: "\e658";
    }
    .template-checkout-thanks-empty-output.thegem-te-checkout-thanks-shipping-details:before {
        content: "\e659";
    }
    .template-checkout-thanks-empty-output.thegem-te-checkout-thanks-notices:before {
        content: "\e657";
    }
</style>
<div id="main-content" class="main-content">
	<div class="block-content">
        <div class="container">
            <div class="thegem-template-wrapper thegem-template-checkout-thanks thegem-template-<?php the_ID(); ?>">
				<?php
                    while (have_posts()) : the_post();
                        if (!(defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()))) {
                            global $post;
                            setup_postdata($GLOBALS['post'] =& $post);
                        }
                        $GLOBALS['thegem_template_type'] = 'checkout-thanks';
                        the_content();
                        unset($GLOBALS['thegem_template_type']);
                    endwhile;
				?>
            </div>
        </div>
	</div><!-- .block-content -->
</div><!-- #main-content -->

<?php
get_footer();