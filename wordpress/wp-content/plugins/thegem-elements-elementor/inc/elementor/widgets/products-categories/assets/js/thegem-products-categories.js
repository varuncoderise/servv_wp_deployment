(function ($) {
	function initCategoriesGallery() {
		if (window.tgpLazyItems !== undefined) {
			var isShowed = window.tgpLazyItems.checkGroupShowed(this, function (node) {
				initCategoriesGallery.call(node);
			});
			if (!isShowed) {
				return;
			}
		}

		var $widgetItem = $(this);

		if ($widgetItem.hasClass('inited')) {
			return;
		}

		if ($(this).hasClass('layout-type-carousel')) {

			var $galleryElement = $('.categories-set', $widgetItem);

			//Init preview slider
			var $previewItems = $('.products-category-item', $galleryElement);
			var $galleryPreview;

			var $galleryPreviewWrap = $('<div class="extended-products-carousel-wrap"/>').appendTo($galleryElement);

			$galleryPreview = $('<div class="extended-products-carousel owl-carousel"/>').appendTo($galleryElement);
			if ($widgetItem.attr("data-dots") === '1') {
				$galleryPreview.addClass('dots');
			}

			$galleryPreview.appendTo($galleryPreviewWrap);
			$previewItems.appendTo($galleryPreview);
		} else {
			var $gridElement = $('.categories-set', $widgetItem);
			$gridElement.closest('.portfolio-preloader-wrapper').prev('.preloader').remove();

			if ($widgetItem.hasClass('loading-animation')) {
				var itemsAnimations = $widgetItem.itemsAnimations({
					itemSelector: '.products-category-item',
					scrollMonitor: true
				});

				itemsAnimations.show();
			}
		}

		$widgetItem.addClass('inited');

		if ($widgetItem.hasClass('counts-visible-hover') && !$widgetItem.hasClass('caption-container-preset-bold')) {

			$('.products-category-item', $widgetItem).hover(function () {
				var countHeight = $(this).find('.category-count-inside').outerHeight();
				if ($widgetItem.hasClass('caption-container-preset-transparent') && $widgetItem.hasClass('caption-container-vertical-position-bottom')) {
					$(this).find('.category-overlay-inner-inside .category-overlay-separator').css("transform", "translateY(-" + countHeight + "px)");
					$(this).find('.category-overlay-inner-inside .category-title').css("transform", "translateY(-" + countHeight + "px)");
					$(this).find('.category-overlay-inner-inside .category-count').css("transform", "translateY(0)");
				} else if (!($widgetItem.hasClass('caption-container-preset-transparent') && $widgetItem.hasClass('caption-container-vertical-position-top'))) {
					$(this).find('.category-overlay-inner-inside .category-overlay-separator').css("transform", "translateY(-" + countHeight / 2 + "px)");
					$(this).find('.category-overlay-inner-inside .category-title').css("transform", "translateY(-" + countHeight / 2 + "px)");
					$(this).find('.category-overlay-inner-inside .category-count').css("transform", "translateY(" + countHeight / 2 + "px)");
				}
			}, function () {
				if (!$widgetItem.hasClass('caption-container-preset-bold')) {
					$(this).find('.category-overlay-inner-inside > *').css("transform", "");
				}
			});
		}
	}

	function updateCategoriesGallery() {
		if (window.tgpLazyItems !== undefined) {
			var isShowed = window.tgpLazyItems.checkGroupShowed(this, function (node) {
				updateCategoriesGallery.call(node);
			});
			if (!isShowed) {
				return;
			}
		}

		var $carouselItem = $(this);
		var $galleryElement = $('.categories-set', $carouselItem);

		$galleryElement.thegemPreloader(function () {
			var isTouch = window.gemSettings.isTouch,
				autoplay = true,
				animationSpeed = $carouselItem.attr("data-autoscroll-speed"),
				slideBy = 'page',
				animationEffect = $carouselItem.attr("data-sliding-animation"),
				isArrows = $carouselItem.attr("data-arrows") === '1',
				isDots = $carouselItem.attr("data-dots") === '1',
				isLoop = $carouselItem.attr("data-loop") === '1',
				$galleryPreviewCarousel = $('.extended-products-carousel', $galleryElement);

			if (animationSpeed == '0') {
				autoplay = false
			}

			if (animationEffect == 'one-by-one') {
				slideBy = 1
			}

			//Init preview carousel
			$galleryPreviewCarousel.owlCarousel({
				loop: isLoop,
				items: 1,
				rewind: false,
				mouseDrag: true,
				autoplay: autoplay,
				autoplayTimeout: animationSpeed,
				slideTransition: 'ease',
				slideBy: slideBy,
				dots: isDots,
				nav: isArrows,
				responsive: {
					0: {
						items: $carouselItem.data('columns-mobile'),
						margin: $carouselItem.hasClass("item-separator") ? 0 : $carouselItem.data("margin-mobile"),
					},
					768: {
						items: $carouselItem.data('columns-tablet'),
						margin: $carouselItem.hasClass("item-separator") ? 0 : $carouselItem.data("margin-tablet"),
					},
					992: {
						items: $carouselItem.data('columns-desktop'),
						margin: $carouselItem.hasClass("item-separator") ? 0 : $carouselItem.data("margin-desktop"),
					},
				},
				onInitialized: function () {
					changedArrows();

					$galleryElement.closest('.portfolio-preloader-wrapper').prev('.preloader').remove();
					if ($carouselItem.hasClass('loading-animation')) {
						var itemsAnimations = $carouselItem.itemsAnimations({
							itemSelector: '.products-category-item',
							scrollMonitor: true
						});
						itemsAnimations.show();
					}

					if (window.tgpLazyItems !== undefined) {
						window.tgpLazyItems.scrollHandle();
					}
				},
				onChange: function () {
					if (window.tgpLazyItems !== undefined) {
						window.tgpLazyItems.scrollHandle();
					}
				}
			});

			// Changed arrows
			function changedArrows() {
				$('.slider-prev-icon', $carouselItem).appendTo($('.owl-nav .owl-prev', $galleryElement));
				$('.slider-next-icon', $carouselItem).appendTo($('.owl-nav .owl-next', $galleryElement));

				var dotsHeight = $('.owl-dots', $carouselItem).outerHeight() + parseInt($('.owl-dots', $carouselItem).css('marginTop'));
				$('.owl-nav .owl-prev', $galleryElement).css('top', 'calc(50% - '+dotsHeight+'px/2)');
				$('.owl-nav .owl-next', $galleryElement).css('top', 'calc(50% - '+dotsHeight+'px/2)');
			}

			// Resize and orientation changes
			window.addEventListener("resize", function () {
				isTouch = window.gemSettings.isTouch;

				$galleryPreviewCarousel.trigger('refresh.owl.carousel');

			}, false);

		});
	}

	$.fn.initCategoriesGalleries = function () {
		$(this).each(initCategoriesGallery);
	};

	$.fn.updateCategoriesGalleries = function () {
		$(this).each(updateCategoriesGallery);
	};

	$(document).ready(function () {
		$('body:not(.elementor-editor-active) .products-categories-widget').initCategoriesGalleries();
		$('body:not(.elementor-editor-active) .products-categories-widget.layout-type-carousel').updateCategoriesGalleries();

		$('body').on('touchstart', function (e) {
			$('.products-categories-widget .products-category-item').removeClass('hover-effect');
		});

		$('.products-categories-widget .products-category-item').on('touchstart', function (e) {
			$('.products-categories-widget .products-category-item').not(this).removeClass('hover-effect');
			$(this).addClass('hover-effect');
		});
	});

})(jQuery);