/**
 * Auto load post module front-end JS.
 * 
 * @copyright 2020 ThemeSphere.
 */
"use strict";

(function() {
	// Percentage reading to trigger the next.
	const TRIGGER_NEXT_FACTOR = 0.65;
	const isIframe = typeof BunyadIsIframe !== 'undefined' ? BunyadIsIframe : false;

	/** @type {!Array<Object>} */
	let postsToLoad = [];

	/** @type {HTMLElement} */
	let mainPostElement;

	/** @type {IntersectionObserver} */
	let inViewObserver;

	/**
	 * States and current refs.
	 */
	let isLoading = false;

	// All the loaded posts elements.
	let postElements = [];
	
	// Latest loaded post element.
	let postElement;

	/**
	 * Set up.
	 */
	function init() {

		setupChild();
		setupParent();

	}

	function setupParent() {
		if (isIframe) {
			return;
		}
		
		// Set posts on ready.
		callOnReady(() => {
			if (typeof SphereCore_AutoPosts !== 'undefined') {
				postsToLoad = SphereCore_AutoPosts;
			}
		});

		postElement     = document.querySelector('.main');
		mainPostElement = postElement;
		postElements.push(mainPostElement);

		Object.assign(mainPostElement.dataset, {
			title: document.title,
			url: window.location.href
		});

		document.addEventListener('scroll', () => {

			// isLoading is false once iframe is inserted but iframe's dataset.loading
			// is empty only once onload event has fired (on full load).
			if (isLoading || postElement.dataset.loading) {
				return;
			}

			let triggerLoad = postElement.offsetTop + (postElement.offsetHeight * TRIGGER_NEXT_FACTOR);
			if (window.scrollY > triggerLoad) {
				isLoading = true;
				requestAnimationFrame(loadPost);
			}
		});

		inViewObserver = new IntersectionObserver(observePostsInView, {
			root: null,
			rootMargin: '0px 0px -50%',
			threshold: 0
		});
	}

	/**
	 * Observe posts entering the viewport and update URL etc.
	 * 
	 * @param {Array} entries 
	 */
	function observePostsInView(entries) {

		let thePost;

		// Current visible post, that will be inactivated, if any.
		let currentPost;

		for (let element of entries) {
			if (element.intersectionRatio <= 0) {
				currentPost = element.target;
				continue;
			}
			
			thePost = element.target;
			break;
		}

		// Revert to previous post if available.
		if (!thePost) {
			const index    = postElements.findIndex(post => post === currentPost);
			const prevPost = postElements[index - 1];

			if (prevPost && prevPost.getBoundingClientRect().bottom >= 0) {
				thePost = prevPost;
			}
		}

		if (thePost && thePost.dataset.url) {
			window.history.pushState(null, thePost.dataset.title, thePost.dataset.url);
			document.title = thePost.dataset.title;
		}
	}

	/**
	 * Add a loader before the current post element/iframe.
	 */
	function addLoader(target) {

		target = target || postElement;
		const loader = document.createElement('div');
		Object.assign(loader, {
			className: 'spc-alp-loader ts-spinner'
		});

		target.before(loader);
	}

	/**
	 * Load the next post.
	 */
	function loadPost() {
		const post = postsToLoad.shift();

		if (!post) {
			return;
		}

		const addIframe = () => {
			const iframe = document.createElement('iframe');

			Object.assign(iframe.dataset, {
				url: post.url,
				title: post.title,
				id: post.id,
				loading: 1
			});

			Object.assign(iframe, {
				id: `spc-alp-iframe-${post.id}`,
				className: 'spc-auto-load-post',
				width: '100%',
				height: 0,
				src: `${post.url}#auto-load-post-${post.id}`,
				style: 'overflow: hidden; opacity: 0'
			});

			postElement.after(iframe);
			postElement = iframe;

			postElements.push(iframe);

			return iframe;
		};

		// Loading for the first time, add class to main element.
		if (mainPostElement === postElement) {
			mainPostElement.classList.add('spc-alp-main');
		}

		addIframe()
		addLoader();
		observeiFrameMessages();
	}

	/**
	 * Test if the iframe is on same domain and can be access.
	 * 
	 * @param {HTMLIFrameElement} iframe 
	 */
	function canAccessIframe(iframe) {
		if (isIframe) {
			return false;
		}

		try {
			return Boolean(iframe.contentDocument);
		}
		catch (e) {
			return false;
		}
	}

	/**
	 * Listen for postMessage from child. Mainly for resize.
	 */
	function observeiFrameMessages() {

		const getIframe = id => document.querySelector(`#spc-alp-iframe-${id}`);

		/**
		 * Iframe resize event.
		 * 
		 * @param {Number} id 
		 * @param {Number} height 
		 */
		const iframeResize = (id, height) => {
			const iframe = getIframe(id);
			requestAnimationFrame(() => {
				iframe.style.height = height + 'px';
			});
		};

		/**
		 * Iframe ready/DOMContentLoaded event.
		 * 
		 * @param {Number} id 
		 */
		const iframeReady = id => {
			const iframe = getIframe(id);
			iframe.style.opacity = 1;

			document.querySelectorAll('.spc-alp-loader').forEach(e => e.remove());
		};

		/**
		 * Iframe loaded event.
		 * 
		 * @param {Number} id 
		 */
		const iframeLoaded = id => {
			isLoading = false;
			
			const iframe = getIframe(id);
			iframe.dataset.loading = '';
			inViewObserver.observe(iframe);

			// Scroll to post if at footer / almost end of page.
			const doc = document.documentElement;
			if (doc.scrollHeight - doc.scrollTop <= doc.clientHeight + 75) {
				if (Bunyad.theme) {
					Bunyad.theme.stickyBarPause = true;
					setTimeout(() => Bunyad.theme.stickyBarPause = false, 5);
				}

				iframe.scrollIntoView();
			}

			document.dispatchEvent(new Event('spc-alp-iframe-ready'));

			// If iframe can be accessed, reinit lightbox.
			const theme = Bunyad.theme || Bunyad_Theme;
			if (canAccessIframe(iframe) && theme.lightbox) {
				theme.lightbox();
			}
		};

		/**
		 * Multiple actions are available via postMessage.
		 */
		window.addEventListener('message', e => {

			if (!e.data || !e.data.action) {
				return;
			}

			switch (e.data.action) {
				case 'iframe-resize':
					if (e.data.id && e.data.height) {
						iframeResize(e.data.id, e.data.height);
					}
					break;

				case 'iframe-loaded':
					iframeLoaded(e.data.id);
					break;

				case 'iframe-ready':
					iframeReady(e.data.id);
					break;
			}
		});
	}

	function setupChild() {
		if (!isIframe || !window.parent) {
			return;
		}

		const iframeId = parseInt(window.location.hash.replace(/#?auto-load-post-/, ''));

		const resizeParent = () => {
			window.parent.postMessage({
				action: 'iframe-resize', 
				height: document.documentElement.scrollHeight,
				id: iframeId
			}, '*');
		};

		callOnReady(() => {
			resizeParent();

			window.parent.postMessage({
				action: 'iframe-ready',
				id: iframeId
			}, '*');
		});

		// Fully loaded here. 
		window.onload = () => {
			resizeParent();

			window.parent.postMessage({
				action: 'iframe-loaded',
				id: iframeId
			}, '*');
		}

		if (ResizeObserver) {
			const resizer = new ResizeObserver(e => resizeParent());
			resizer.observe(document.body);
		}
	}

	function callOnReady(cb) {
		document.readyState !== 'loading' 
			? cb() 
			: document.addEventListener('DOMContentLoaded', cb);
	}

	init();

})();