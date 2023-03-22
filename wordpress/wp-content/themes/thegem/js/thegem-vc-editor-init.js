(function () {
	//'use strict';

	function thegemTEEnvents( model ) {
		var $shortcodeOutput = model.view.$el.children('[data-editor-container-class]').first();
		if($shortcodeOutput && $shortcodeOutput.data('editor-container-class')) {
			model.view.$el.addClass($shortcodeOutput.data('editor-container-class'));
		}
	}

	//window.vc.events.on( 'shortcodeView:ready:thegem_te_logo_test', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_logo', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_menu', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_cart', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_search', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_icon', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_button', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_socials', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_divider', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_wishlist', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_account', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_infotext', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_menu_secondary', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_countdown', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_search_form', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_switcher', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_language_switcher', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_currency_switcher', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_signin', thegemTEEnvents );

	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_add_to_cart', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_add_to_wishlist', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_attribute', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_breadcrumbs', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_categories', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_description', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_gallery', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_navigation', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_price', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_rating', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_sharing', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_size_guide', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_sku', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_tabs', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_tags', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:thegem_te_product_title', thegemTEEnvents );

	window.vc.events.on( 'shortcodeView:ready:vc_column_text', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:gem_heading', thegemTEEnvents );
	window.vc.events.on( 'shortcodeView:ready:gem_divider', thegemTEEnvents );

})();
