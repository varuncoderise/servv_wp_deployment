(function ($) {

	'use strict';

	let $body, $header, $wrapper, $navigate, menuLockedTimeout;

	const pageOffset = $('#page').offset();
	const pageWidth = $('#page').width();

	const _helpers = {
		setMobMenuDefaultLeftPosition: (wrap, elem) => {
			let item = wrap.getBoundingClientRect();
			let itemLeftPosition = item.x + pageXOffset;

			$(elem).css({
				left: -itemLeftPosition
			});
		},
		setMobMenuDefaultMaxHeight: (elem) => {
			let headerHeight = $wrapper.outerHeight(),
				windowHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

			$(elem).css({
				maxHeight: windowHeight - headerHeight
			});
		},
		getScrollY: () => {
			return window.pageYOffset || document.documentElement.scrollTop;
		},
		setBodyLocked: (elem) => {
			$body.data('scroll-position', _helpers.getScrollY());
			clearTimeout(menuLockedTimeout);
			$body.addClass('menu-scroll-locked');

			let isNoStickyItem = $(elem).closest("#site-header").length;
			let $vcRow = $(elem).closest(".vc_row");

			if (isNoStickyItem) {
				$body.addClass('is-no-sticky');
			} else {
				$body.removeClass('is-no-sticky');
			}

			$vcRow.addClass('set-index');
		},
		unsetBodyLocked: () => {
			menuLockedTimeout = setTimeout(function () {
				$body.removeClass('menu-scroll-locked is-no-sticky');
				$('.vc_row', $wrapper).removeClass('set-index');
			}, 1000);

			if ($body.data('scroll-position')) {
				window.scrollTo(0, $body.data('scroll-position'))
			}
		},
		isMobMenuSlidingInit: ($item) => {
			let result = false;
			if ($item.hasClass('mobile-view')) {
				result = true;
			}

			return result;
		},
		isResponsiveMenuVisible: (elem) => {
			return elem.parents('.thegem-te-menu').find('.menu-toggle:not(.hamburger-toggle)').is(':visible');
		},
		isBody: (elem) => {
			return (/^(?:body|html)$/i).test(elem.tagName);
		},
		getScroll: () => {
			return {
				x: window.pageXOffset || document.documentElement.scrollLeft,
				y: window.pageYOffset || document.documentElement.scrollTop
			};
		},
		getScrolls: (elem) => {
			let element = elem.parentNode, position = {x: 0, y: 0};
			while (element && !_helpers.isBody(element)) {
				position.x += element.scrollLeft;
				position.y += element.scrollTop;
				element = element.parentNode;
			}
			return position;
		},
		styleString: (elem, style) => {
			return $(elem).css(style);
		},
		styleNumber: (elem, style) => {
			return parseInt(_helpers.styleString(elem, style)) || 0;
		},
		topBorder: (elem) => {
			return _helpers.styleNumber(elem, 'border-top-width');
		},
		leftBorder: (elem) => {
			return _helpers.styleNumber(elem, 'border-left-width');
		},
		getOffset: (elem) => {
			if (elem.getBoundingClientRect && window.gemBrowser.platform.name != 'ios') {
				let bound = elem.getBoundingClientRect(),
					html = elem.ownerDocument.documentElement,
					htmlScroll = _helpers.getScroll(html),
					elemScrolls = _helpers.getScrolls(elem),
					isFixed = (_helpers.styleString(elem, 'position') == 'fixed');
				return {
					x: bound.left + elemScrolls.x + ((isFixed) ? 0 : htmlScroll.x) - html.clientLeft,
					y: bound.top + elemScrolls.y + ((isFixed) ? 0 : htmlScroll.y) - html.clientTop
				};
			}

			let element = elem, position = {x: 0, y: 0};
			if (_helpers.isBody(elem)) return position;

			while (element && !_helpers.isBody(element)) {
				position.x += element.offsetLeft;
				position.y += element.offsetTop;

				if (window.gemBrowser.name == 'firefox') {
					if (!borderBox(element)) {
						position.x += _helpers.leftBorder(element);
						position.y += _helpers.topBorder(element);
					}
					let parent = element.parentNode;
					if (parent && _helpers.styleString(parent, 'overflow') != 'visible') {
						position.x += _helpers.leftBorder(parent);
						position.y += _helpers.topBorder(parent);
					}
				} else if (element != elem && window.gemBrowser.name == 'safari') {
					position.x += _helpers.leftBorder(element);
					position.y += _helpers.topBorder(element);
				}

				element = element.offsetParent;
			}
			if (window.gemBrowser.name == 'firefox' && !borderBox(elem)) {
				position.x -= _helpers.leftBorder(elem);
				position.y -= _helpers.topBorder(elem);
			}
			return position;
		},
		setTranslateValues: (elem) => {
			if (!elem.length) return;
			elem.css('transform', '').css('margin-left', '').css('margin-top', '');
			const style = window.getComputedStyle(elem[0]);
			const matrix = style['transform'] || style.webkitTransform || style.mozTransform;
			let x = 0, y = 0;

			// No transform property. Simply return 0 values.
			if (matrix === 'none' || typeof matrix === 'undefined') {
				return;
			}

			// Can either be 2d or 3d transform
			const matrixType = matrix.includes('3d') ? '3d' : '2d';
			const matrixValues = matrix.match(/matrix.*\((.+)\)/)[1].split(', ');

			// 2d matrices have 6 values
			// Last 2 values are X and Y.
			// 2d matrices does not have Z value.
			if (matrixType === '2d') {
				x = matrixValues[4];
				y = matrixValues[5];
			}

			// 3d matrices have 16 values
			// The 13th, 14th, and 15th values are X, Y, and Z
			if (matrixType === '3d') {
				x = matrixValues[12];
				y = matrixValues[13];
			}

			elem.css('transform', 'none').css('margin-left', x + 'px').css('margin-top', y + 'px');
		}
	};

	const menuScripts = {
		init: () => {
			$body = $('body');
			$header = $('#site-header');
			$wrapper = $('.thegem-template-header');
			$navigate = $('.thegem-te-menu > nav');

			menuScripts.setLayoutView();
			menuScripts.onResize();
			menuScripts.menuAbsoluteTranslate();
			menuScripts.menuDefault();
			menuScripts.menuOverlay();
			menuScripts.menuHamburger();
			menuScripts.mobMenuDefault();
			menuScripts.mobMenuSliding();
			menuScripts.menuMegaMenu();
			menuScripts.menuMegaMenuTemplate();

			$navigate.addClass('inited');
		},

		setLayoutView: () => {
			let viewportWidth = window.innerWidth;
			document.documentElement.style.setProperty('--scrollbar-width', -(window.innerWidth - document.body.clientWidth) + "px");

			$navigate.each(function (i, nav) {
				const tabletLandscapeMaxWidth = $(nav).data("desktop-breakpoint");
				const tabletLandscapeMinWidth = $(nav).data("tablet-breakpoint");
				const tabletPortraitMaxWidth = $(nav).data("tablet-breakpoint") - 1;
				const tabletPortraitMinWidth = $(nav).data("mobile-breakpoint");

				if ($(this).data("tablet-landscape") === 'default' && viewportWidth >= tabletLandscapeMinWidth && viewportWidth <= tabletLandscapeMaxWidth) {
					$(this).removeClass('mobile-view').addClass('desktop-view');
				} else if ($(this).data("tablet-portrait") === 'default' && viewportWidth >= tabletPortraitMinWidth && viewportWidth <= tabletPortraitMaxWidth) {
					$(this).removeClass('mobile-view').addClass('desktop-view');
				} else if (viewportWidth <= tabletLandscapeMaxWidth) {
					$(this).removeClass('desktop-view').addClass('mobile-view');
				} else {
					$(this).removeClass('mobile-view').addClass('desktop-view');
				}
			});
		},

		menuDefault: () => {
			let $menu = $('.thegem-te-menu__default');

			$('.nav-menu > li:not(.megamenu-template-enable)', $menu).hover(function () {
				let $items = $(this).find('ul');

				let getLevelULByMenu = (item) => {
					let parentUL = $(item).parent('li').parent('ul');
					let level = 0;

					while (!parentUL.is('.nav-menu')) {
						parentUL = parentUL.parent('li').parent('ul');
						level++;
					}

					return level;
				};

				let offset = $header.offset().top + $header.outerHeight() - $(this).offset().top - $(this).outerHeight();
				// $item.css('top', 'calc(100% + ' + offset + 'px)');

				$items.removeClass('invert');

				if (!$(this).hasClass('megamenu-enable')) {
					$items.css({top: ''});
				}

				if ($(this).hasClass('megamenu-enable') ||
					$(this).closest('.header-layout-overlay').length ||
					$(this).closest('.mobile-menu-layout-overlay').length && _helpers.isResponsiveMenuVisible($(this))) {
					return;
				}

				$items.each(function () {
					let $item = $(this), self = this, $parentList = $item.parent().closest('ul');

					let itemOffset = $item.offset(),
						itemOffsetLeft = itemOffset.left;

					let leftItemTranslate = 0;
					if ($item.css('transform')) {
						leftItemTranslate = parseInt(getComputedStyle(this).transform.split(',')[4]);
						let levelUL = getLevelULByMenu(self);
						if (levelUL > 0) {
							leftItemTranslate = leftItemTranslate * levelUL;
						}
					}
					if (isNaN(leftItemTranslate)) leftItemTranslate = 0;

					if ($parentList.hasClass('invert')) {
						if ($parentList.offset().left - $item.outerWidth() > pageOffset.left) {
							$item.addClass('invert');
						}
					} else {
						if (itemOffsetLeft - leftItemTranslate - pageOffset.left + $item.outerWidth() > pageWidth) {
							$item.addClass('invert');
						}
					}
				});
			});
		},

		menuOverlay: () => {
			let $menus = $('.thegem-te-menu__overlay, .thegem-te-menu-mobile__overlay');

			$menus.each(function (i, el) {
				let $menuItem = $(el);
				let $menuWrap = $('.overlay-menu-wrapper', $menuItem);
				let $overlay = $('.overlay-menu-back', $menuItem);
				let $toggle = $('.overlay-toggle', $menuItem);

				// Toggle overlay menu
				$menuItem.on('click', '.overlay-toggle', function (e) {
					e.preventDefault();

					if ($overlay.hasClass('active')) {
						_helpers.unsetBodyLocked();
						$overlay.removeClass('active');
						$menuItem.addClass('close');
						$menuItem.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function (e) {
							$menuItem.removeClass('overlay-active close');
							$menuWrap.removeClass('active');
						});
					} else {
						_helpers.setBodyLocked(this);
						$menuWrap.addClass('active');
						$overlay.addClass('active');
						$menuItem.off('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend');
						$menuItem.addClass('overlay-active').removeClass('close');
					}
				});

				// Close overlay menu ESC click
				$(document).on('keydown', function (event) {
					if (event.keyCode === 27) {
						$toggle.click();
					}
				});

				// Close overlay menu overlay click
				$overlay.on('click', function (e) {
					$toggle.click();
				});

				$menuWrap.on('click', function (e) {
					let $notClick = $menuWrap.find('li');
					if ($notClick.has(e.target).length === 0) {
						//$toggle.click();
					}
				});

				// Toggle overlay menu list
				$('.menu-item-parent-toggle', $menuWrap).on('click', function (e) {
					e.preventDefault();
					e.stopPropagation();

					let $itemLink = $(this);
					let $item = $itemLink.closest('li');

					if ($item.hasClass('menu-item-parent') && ($item.closest('ul').hasClass('nav-menu') || $item.parent().closest('li').hasClass('menu-overlay-item-open'))) {
						e.preventDefault();

						if ($item.hasClass('menu-overlay-item-open')) {
							$(' > ul, .menu-overlay-item-open > ul', $item).each(function () {
								$(this).css({height: $(this).outerHeight() + 'px'});
							});

							setTimeout(function () {
								$(' > ul, .menu-overlay-item-open > ul', $item).css({height: ''});
								$('.menu-overlay-item-open', $item).add($item).removeClass('menu-overlay-item-open');
							}, 50);
						} else {
							let $oldActive = $('.nav-menu .menu-overlay-item-open').not($item.parents());
							$('> ul', $oldActive).not($item.parents()).each(function () {
								$(this).css({height: $(this).outerHeight() + 'px'});
							});
							setTimeout(function () {
								$('> ul', $oldActive).not($item.parents()).css({height: ''});
								$oldActive.removeClass('menu-overlay-item-open');
							}, 50);

							$('> ul', $item).css({height: 'auto'});
							let itemHeight = $('> ul', $item).outerHeight();
							$('> ul', $item).css({height: ''});
							setTimeout(function () {
								$('> ul', $item).css({height: itemHeight + 'px'});
								$item.addClass('menu-overlay-item-open');
								$('> ul', $item).one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function () {
									$('> ul', $item).css({height: 'auto'});
								});
							}, 50);
						}
					}
				});

				// Toggle search widget
				$('.menu-item-type-search-widget', $menuWrap).on('click', 'a', function(e) {
					e.preventDefault();
					if ($(this).closest('.menu-item-fullscreen-search-mobile').length) return;

					if($(this).closest('.overlay-menu-wrapper.active').length) {
						let $primaryMenu = $('.nav-menu');
						$primaryMenu.addClass('overlay-search-form-show animated-minisearch');
						$('.sf-input', $primaryMenu).focus();

						setTimeout(function() {
							$(document).on('click.menu-item-search-close', 'body', function(e) {
								if(!$(e.target).is('.menu-item-type-search-widget .minisearch *')) {
									let $primaryMenu = $('.nav-menu');

									if ($primaryMenu.hasClass('animated-minisearch')) {
										$primaryMenu.removeClass('animated-minisearch');

										setTimeout(function() {
											$primaryMenu.removeClass('overlay-search-form-show');
											$(document).off('click.menu-item-search-close');
										}, 700);

									} else {
										$primaryMenu.removeClass('overlay-search-form-show');
										$(document).off('click.menu-item-search-close');
									}
								}
							});
						}, 500);
					} else {
						$('.menu-item-type-search-widget').toggleClass('active');
					}
				});

			});
		},

		menuHamburger: () => {
			let $menus = $('.thegem-te-menu__hamburger');

			$menus.each(function (i, el) {
				let $menuItem = $(el);
				let $overlay = $('.hamburger-menu-back', $menuItem);
				let $toggle = $('.hamburger-toggle', $menuItem);

				// Toggle hamburger menu
				$menuItem.on('click', '.hamburger-toggle', function (e) {
					e.preventDefault();

					if ($overlay.hasClass('active')) {
						_helpers.unsetBodyLocked();
						$menuItem.removeClass('hamburger-active');
						$overlay.removeClass('active');
					} else {
						_helpers.setBodyLocked(this);
						$menuItem.addClass('hamburger-active');
						$overlay.addClass('active');
					}
				});

				$(document).on('keydown', function (event) {
					if (event.keyCode === 27) {
						$toggle.click();
					}
				});

				$overlay.on('click', function () {
					$toggle.click();
				});
			});
		},

		mobMenuDefault: () => {
			//dl-menu support for css animations and transitions
			let supportsTransitions = () => {
				return getSupportedTransition() != '';
			}
			let getSupportedTransition = () => {
				let b = document.body || document.documentElement,
					s = b.style,
					p = 'transition';

				if (typeof s[p] == 'string') {
					return p;
				}

				// Tests for vendor specific prop
				let v = ['Moz', 'webkit', 'Webkit', 'Khtml', 'O', 'ms'];
				p = p.charAt(0).toUpperCase() + p.substr(1);

				for (let i = 0; i < v.length; i++) {
					if (typeof s[v[i] + p] == 'string') {
						return true;
					}
				}

				return '';
			}
			let supportsAnimations = () => {
				return getSupportedAnimation() != '';
			}
			let getSupportedAnimation = () => {
				let t, el = document.createElement("fakeelement");

				let animations = {
					"animation": "animationend",
					"OAnimation": "oAnimationEnd",
					"MozAnimation": "animationend",
					"WebkitAnimation": "webkitAnimationEnd",
					'msAnimation': 'MSAnimationEnd'
				};

				for (t in animations) {
					if (el.style[t] !== undefined) {
						return t;
					}
				}
				return '';
			}
			window.supportedTransition = getSupportedTransition();
			window.supportsTransitions = supportsTransitions();
			window.supportedAnimation = getSupportedAnimation();
			window.supportsAnimations = supportsAnimations();

			let $menus = $('.thegem-te-menu-mobile__default');
			$menus.each(function (i, el) {
				//dl-menu init
				let $menuItem = $(el);
				$menuItem.find('.sub-menu').addClass('dl-submenu');

				$menuItem.dlmenu({
					animationClasses: {
						classin: 'dl-animate-in',
						classout: 'dl-animate-out'
					},
					backLabel: thegem_menu_data.backLabel,
					showCurrentLabel: thegem_menu_data.showCurrentLabel
				});

				$('li:not(.menu-item-has-children):not(.dl-back)', $menuItem).on('click', 'a', function (e) {
					if (typeof $.fn.dlmenu === 'function') {
						$menuItem.dlmenu('closeMenu');
					}
				});

				//dl-menu navmenu/submenu set maxHeight && left position
				let navMenu = $menuItem.find('ul');
				_helpers.setMobMenuDefaultMaxHeight(navMenu);
				_helpers.setMobMenuDefaultLeftPosition(el, navMenu);

				//dl-menu resize
				$(window).on('resize', function (e) {
					setTimeout(function () {
						_helpers.setMobMenuDefaultMaxHeight(navMenu);
						_helpers.setMobMenuDefaultLeftPosition(el, navMenu);
					}, 1000);
				});
			});
		},

		mobMenuSliding: () => {
			let $menus = $('.thegem-te-menu-mobile__slide-horizontal, .thegem-te-menu-mobile__slide-vertical');
			window.isMobMenuSliding = _helpers.isMobMenuSlidingInit($menus);

			$menus.each(function (i, el) {
				let $menuItem = $(el);
				let $menuWrap = $('.mobile-menu-slide-wrapper', $menuItem);
				let $menuClose = $('.mobile-menu-slide-close', $menuItem);
				let $overlay = $('.mobile-menu-slide-back', $menuItem);
				let $toggle = $('.dl-trigger', $menuItem);

				$menuItem.on('click', '.dl-trigger', function (e) {
					e.preventDefault();

					//$header.removeClass('hidden');
					//$header.toggleClass('menu-slide-opened');

					if ($overlay.hasClass('active')) {
						$overlay.removeClass('active');
					} else {
						$overlay.addClass('active');
					}

					$menuWrap.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function (e) {
						$menuWrap.removeClass('animation');
					});
					$menuWrap.addClass('animation').toggleClass('opened');

					if ($menuWrap.hasClass('opened')) {
						_helpers.setBodyLocked(this);
					} else {
						_helpers.unsetBodyLocked();
					}
				});

				$('li.menu-item-current', $menuItem).each(function () {
					let $self = $(this);
					$self.addClass('opened');
					$('> ul', $self).show();
				});

				$menuClose.on('click', function () {
					$toggle.click();
				});

				$overlay.on('click', function () {
					$toggle.click();
				});

				$('.menu-item-parent-toggle', $menuWrap).on('click', function (e) {
					e.preventDefault();

					if (!window.isMobMenuSliding) return;

					let self = this;
					$(this).closest('li').toggleClass('opened');
					$(this).siblings('ul').slideToggle(200, function () {
						if (!$(self).closest('li').hasClass('opened')) {
							$(self).siblings('ul').find('li').removeClass('opened');
							$(self).siblings('ul').css('display', '');
							$(self).siblings('ul').find('ul').css('display', '');
						}
					});
				});
			});
		},

		onResize: () => {
			let resizeTimer;
			$(window).on('resize', function (e) {
				clearTimeout(resizeTimer);

				resizeTimer = setTimeout(function () {
					menuScripts.setLayoutView();
					menuScripts.menuAbsoluteTranslate();

					let $menuSliding = $('.thegem-te-menu-mobile__slide-horizontal, .thegem-te-menu-mobile__slide-vertical');
					window.isMobMenuSliding = _helpers.isMobMenuSlidingInit($menuSliding);
				}, 250);
			});
		},

		menuMegaMenu: () => {
			let $megaMenuItems = $('.thegem-te-menu .nav-menu > li.megamenu-enable');

			$megaMenuItems.each(function () {
				if ($(this).parents('.thegem-te-menu > nav').hasClass("thegem-te-menu__overlay")) {
					$(this).removeClass('megamenu-enable');
					return;
				}
				menuScripts.fixMegaMenuPosition($(this));
				let $item = $('> ul', $(this));
				if ($item.length == 0) return;
				$item.addClass('megamenu-item-inited');

				$(this).hover(
					function () {
						menuScripts.fixMegaMenuPosition($(this));
					},
					function () {
					}
				);
			});
		},

		fixMegaMenuPosition: (elem, containerWidthCallback) => {

			let isMenuVertical = false;
			let isMenuPerspective = false;
			let isMenuHamburger = elem.parents('.thegem-te-menu > nav').hasClass("thegem-te-menu__hamburger");

			if (!$('.megamenu-inited', elem).length && _helpers.isResponsiveMenuVisible(elem)) {
				return false;
			}

			let $item = $('> ul', elem);
			if ($item.length == 0) return;
			let self = $item.get(0);

			$item.addClass('megamenu-item-inited');

			let default_item_css = {
				width: 'auto',
				height: 'auto'
			};

			if (!isMenuVertical && !isMenuHamburger && !isMenuPerspective) {
				default_item_css.left = 0;
			}

			$item
				.removeClass('megamenu-masonry-inited megamenu-fullwidth')
				.css(default_item_css);

			$(' > li', $item).css({
				left: 0,
				top: 0
			}).each(function () {
				let old_width = $(this).data('old-width') || -1;
				if (old_width != -1) {
					$(this).width(old_width).data('old-width', -1);
				}
			});

			if (_helpers.isResponsiveMenuVisible(elem)) {
				return;
			}

			let $container = $item.closest('.vc_column-inner'),
				container_width,
				container_padding_left,
				container_padding_right,
				parent_width;
			if (containerWidthCallback !== undefined) {
				container_width = containerWidthCallback();
			} else if (isMenuVertical) {
				container_width = window.gemOptions.clientWidth - $('#site-header-wrapper').outerWidth();
			} else if (isMenuPerspective) {
				container_width = window.gemOptions.clientWidth - $('#primary-navigation').outerWidth();
			} else if (isMenuHamburger) {
				container_width = window.gemOptions.clientWidth - $('.thegem-te-menu .nav-menu').outerWidth();
			} else {
				container_width = $container.width();
				container_padding_left = parseInt($container.css('padding-left'));
				container_padding_right = parseInt($container.css('padding-right'));
				parent_width = $item.parent().outerWidth();
			}

			let megamenu_width = $item.outerWidth();

			if (megamenu_width > container_width) {
				megamenu_width = container_width;
				let new_megamenu_width = container_width - parseInt($item.css('padding-left')) - parseInt($item.css('padding-right')) - parseInt($item.css('border-left')) - parseInt($item.css('border-right'));
				let columns = $item.data('megamenu-columns') || 4;
				let margin = 0;
				$(' > li.menu-item', $item).each(function (index) {
					if (index < columns) {
						margin += parseInt($(this).css('margin-left'));
					}
				});
				let column_width = parseFloat(new_megamenu_width - margin) / columns;
				let column_width_int = parseInt(column_width);
				$(' > li', $item).each(function () {
					$(this).data('old-width', $(this).width()).css('width', column_width_int);
				});
				let columns_dif = (column_width - column_width_int) * columns;
				$(' > li.megamenu-first-element', $item).css('width', column_width_int + columns_dif);
				$item.addClass('megamenu-fullwidth').width(new_megamenu_width);
			}

			if (!isMenuVertical && !isMenuHamburger && !isMenuPerspective && containerWidthCallback === undefined) {
				let left = 0;
				if (megamenu_width > parent_width) {
					left = -(megamenu_width - parent_width) / 2;
				}

				let container_offset = _helpers.getOffset($container[0]);
				let megamenu_offset = _helpers.getOffset(self);

				if ((megamenu_offset.x - container_offset.x - container_padding_left + left) < 0) {
					left = -(megamenu_offset.x - container_offset.x - container_padding_left);
				}

				if ((megamenu_offset.x + megamenu_width + left) > (container_offset.x + $container.outerWidth() - container_padding_right)) {
					left -= (megamenu_offset.x + megamenu_width + left) - (container_offset.x + $container.outerWidth() - container_padding_right);
				}

				$item.css('left', left).css('left');
			}

			if ($item.hasClass('megamenu-masonry')) {
				let positions = {},
					max_bottom = 0;

				if (!$item.hasClass('megamenu-fullwidth')) {
					$item.width($item.width() - 1);
				}
				let new_row_height = $('.megamenu-new-row', $item).outerHeight() + parseInt($('.megamenu-new-row', $item).css('margin-bottom'));

				$('> li.menu-item', $item).each(function () {
					let pos = $(this).position(),
						top_position;
					if (positions[pos.left] != null && positions[pos.left] != undefined) {
						top_position = positions[pos.left];
					} else {
						top_position = pos.top;
					}
					positions[pos.left] = top_position + $(this).outerHeight() + new_row_height + parseInt($(this).css('margin-bottom'));
					if (positions[pos.left] > max_bottom)
						max_bottom = positions[pos.left];
					$(this).css({
						left: pos.left,
						top: top_position
					})
				});

				$item.height(max_bottom - new_row_height - parseInt($item.css('padding-top')));
				$item.addClass('megamenu-masonry-inited');
			}

			if ($item.hasClass('megamenu-empty-right')) {
				let mega_width = $item.width();
				let max_rights = {
					columns: [],
					position: -1
				};

				$('> li.menu-item', $item).removeClass('megamenu-no-right-border').each(function () {
					let pos = $(this).position();
					let column_right_position = pos.left + $(this).width();

					if (column_right_position > max_rights.position) {
						max_rights.position = column_right_position;
						max_rights.columns = [];
					}

					if (column_right_position == max_rights.position) {
						max_rights.columns.push($(this));
					}
				});

				if (max_rights.columns.length && max_rights.position >= (mega_width - 7)) {
					max_rights.columns.forEach(function ($li) {
						$li.addClass('megamenu-no-right-border');
					});
				}
			}

			if (isMenuVertical || isMenuHamburger || isMenuPerspective) {
				let clientHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
					itemOffset = $item.offset(),
					itemHeight = $item.outerHeight(),
					scrollTop = $(window).scrollTop();

				if (itemOffset.top - scrollTop + itemHeight > clientHeight) {
					$item.css({
						top: clientHeight - itemOffset.top + scrollTop - itemHeight - 20
					});
				}
			}

			$item.addClass('megamenu-inited');
		},

		menuMegaMenuTemplate: () => {
			let $megaMenuItems = $('.thegem-te-menu .nav-menu > li.megamenu-template-enable');

			$megaMenuItems.each(function () {
				if ($(this).parents('.thegem-te-menu > nav').hasClass("thegem-te-menu__overlay")) {
					$(this).removeClass('megamenu-template-enable');
					$(this).find('.megamenu-template').remove();
					return;
				}

				let $item = $('.megamenu-template', $(this));
				if ($item.length == 0) return;
				menuScripts.fixMegaMenuTemplatePosition($(this));

				if ($item.data('template')) {
					let templateID = $item.data('template');
					$.ajax({
						url: thegem_menu_data.ajax_url,
						data: {
							'action': 'get_megamenu_template',
							'id': templateID
						},
						dataType: 'json',
						method: 'POST',
						success: function (response) {
							if (response.status === 'success') {
								$item.html(response.data);
							}
						},
						error: function () {
							console.log('loading megamenu template ajax error');
						}
					});
				}
				$item.addClass('megamenu-template-item-inited');

				$(this).hover(
					function () {
						menuScripts.fixMegaMenuTemplatePosition($(this));
					},
					function () {
					}
				);
			});
		},

		fixMegaMenuTemplatePosition: (elem, containerWidthCallback) => {

			let isMenuVertical = false;
			let isMenuPerspective = false;
			let isMenuHamburger = elem.parents('.thegem-te-menu > nav').hasClass("thegem-te-menu__hamburger");

			if (!$('.megamenu-template-inited', elem).length && _helpers.isResponsiveMenuVisible(elem)) {
				return false;
			}

			let $item = $('.megamenu-template', elem);
			if ($item.length == 0) return;
			let self = $item.get(0);

			$item.addClass('megamenu-template-item-inited');
			let isWidth100 = $item.hasClass("template-width-fullwidth");

			let default_item_css = {
			// 	width: 'auto',
			// 	height: 'auto'
			};

			if (!isMenuVertical && !isMenuHamburger && !isMenuPerspective) {
				default_item_css.left = 0;
			}

			$item.css(default_item_css);

			if (_helpers.isResponsiveMenuVisible(elem)) {
				return;
			}

			let $container = $item.closest('.vc_column-inner'),
				container_width,
				container_padding_left,
				container_padding_right,
				parent_width;
			if (containerWidthCallback !== undefined) {
				container_width = containerWidthCallback();
			} else if (isMenuVertical) {
				container_width = window.gemOptions.clientWidth - $('#site-header-wrapper').outerWidth();
			} else if (isMenuPerspective) {
				container_width = window.gemOptions.clientWidth - $('#primary-navigation').outerWidth();
			} else if (isMenuHamburger) {
				container_width = window.gemOptions.clientWidth - $('.thegem-te-menu .nav-menu').outerWidth();
			} else {
				container_width = $container.width();
				container_padding_left = parseFloat($container.css('padding-left'));
				container_padding_right = parseFloat($container.css('padding-right'));
				parent_width = $item.parent().outerWidth();
			}

			let megamenu_width = $item.outerWidth();

			if (isWidth100 || megamenu_width > document.body.clientWidth) {
				$item.css('width', document.body.clientWidth);
			} else if ($item.hasClass("template-width-boxed") && !isMenuVertical && !isMenuHamburger && !isMenuPerspective) {
				$item.css('width', container_width);
			}
			megamenu_width = $item.outerWidth();

			if (!isMenuVertical && !isMenuHamburger && !isMenuPerspective && containerWidthCallback === undefined) {
				let left = 0;

				let container_offset = _helpers.getOffset($container[0]);
				let megamenu_offset = _helpers.getOffset(self);

				if (isWidth100) {
					left = -megamenu_offset.x;
				} else {
					if (megamenu_width > container_width) {
						left = container_offset.x - megamenu_offset.x - (megamenu_width - container_width)/2;
					} else if (megamenu_width === container_width) {
						left = container_offset.x - megamenu_offset.x + container_padding_left;
					} else {

						if (megamenu_width > parent_width) {
							left = -(megamenu_width - parent_width) / 2;
						}

						if ((megamenu_offset.x - container_offset.x - container_padding_left + left) < 0) {
							left = -(megamenu_offset.x - container_offset.x - container_padding_left);
						}

						if ((megamenu_offset.x + megamenu_width + left) > (container_offset.x + $container.outerWidth() - container_padding_right)) {
							left -= (megamenu_offset.x + megamenu_width + left) - (container_offset.x + $container.outerWidth() - container_padding_right);
						}
					}
				}

				$item.css('left', left).css('left');
			}

			if (isMenuVertical || isMenuHamburger || isMenuPerspective) {
				if (megamenu_width > container_width) {
					$item.css('width', container_width);
				}
				let clientHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
					itemOffset = $item.offset(),
					itemHeight = $item.outerHeight(),
					scrollTop = $(window).scrollTop();

				if (itemOffset.top - scrollTop + itemHeight > clientHeight) {
					$item.css({
						top: clientHeight - itemOffset.top + scrollTop - itemHeight - 20
					});
				}
			}

			$item.addClass('megamenu-template-inited');
		},

		menuAbsoluteTranslate: () => {
			$navigate.each(function (i, el) {
				let $menuWidget = $(this).parents('.elementor-widget');
				_helpers.setTranslateValues($menuWidget);
			});
		},
	};

	// Run the function
	$(function () {
		menuScripts.init();
	});
})(jQuery);
