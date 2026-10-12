/**
 * Via Adlucem — front-end behaviour (no dependencies).
 * Widgets init on page load and again when Elementor's editor re-renders them.
 */
(function () {
	'use strict';

	document.documentElement.classList.add('va-js');

	var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* ---------- Header: shrink on scroll ---------- */
	function initHeader() {
		var header = document.getElementById('va-header');
		if (!header) return;
		var update = function () {
			header.classList.toggle('is-scrolled', window.scrollY > 40);
		};
		update();
		window.addEventListener('scroll', update, { passive: true });
	}

	/* ---------- Mobile menu ---------- */
	function initMobileMenu() {
		var burger = document.querySelector('.va-burger');
		var panel = document.getElementById('va-mobile');
		if (!burger || !panel) return;

		function setOpen(open) {
			burger.setAttribute('aria-expanded', open ? 'true' : 'false');
			document.body.classList.toggle('va-menu-open', open);
			if (open) {
				panel.hidden = false;
				requestAnimationFrame(function () { panel.classList.add('is-visible'); });
			} else {
				panel.classList.remove('is-visible');
				setTimeout(function () { if (!panel.classList.contains('is-visible')) panel.hidden = true; }, 350);
			}
		}

		burger.addEventListener('click', function () {
			setOpen(burger.getAttribute('aria-expanded') !== 'true');
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && document.body.classList.contains('va-menu-open')) {
				setOpen(false);
				burger.focus();
			}
		});
		window.addEventListener('resize', function () {
			if (window.innerWidth > 1024 && document.body.classList.contains('va-menu-open')) setOpen(false);
		});
	}

	/* ---------- Hero slider ---------- */
	function initHero(root) {
		if (root.dataset.vaReady) return;
		root.dataset.vaReady = '1';

		var slides = root.querySelectorAll('.va-hero__slide');
		var current = root.querySelector('.va-hero__current');
		var seconds = parseInt(root.dataset.autoplay, 10) || 0;
		var index = 0;
		var timer = null;
		if (slides.length < 2) return;

		root.style.setProperty('--va-hero-duration', seconds + 's');

		function go(to) {
			slides[index].classList.remove('is-active');
			slides[index].setAttribute('aria-hidden', 'true');
			index = (to + slides.length) % slides.length;
			slides[index].classList.add('is-active');
			slides[index].setAttribute('aria-hidden', 'false');
			if (current) current.textContent = String(index + 1).padStart(2, '0');
			restart();
		}

		function restart() {
			clearTimeout(timer);
			root.classList.remove('is-playing');
			if (!seconds || reduceMotion) return;
			void root.offsetWidth; // restart the progress bar animation
			root.classList.add('is-playing');
			timer = setTimeout(function () { go(index + 1); }, seconds * 1000);
		}

		var prev = root.querySelector('.va-hero__arrow--prev');
		var next = root.querySelector('.va-hero__arrow--next');
		if (prev) prev.addEventListener('click', function () { go(index - 1); });
		if (next) next.addEventListener('click', function () { go(index + 1); });

		// Swipe
		var startX = null;
		root.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
		root.addEventListener('touchend', function (e) {
			if (startX === null) return;
			var dx = e.changedTouches[0].clientX - startX;
			if (Math.abs(dx) > 50) go(index + (dx < 0 ? 1 : -1));
			startX = null;
		});

		// Pause while the tab is hidden
		document.addEventListener('visibilitychange', function () {
			if (document.hidden) { clearTimeout(timer); root.classList.remove('is-playing'); } else { restart(); }
		});

		restart();
	}

	/* ---------- Projects slider ---------- */
	function initProjects(root) {
		if (root.dataset.vaReady) return;
		root.dataset.vaReady = '1';

		var track = root.querySelector('.va-projects__track');
		var prev = root.querySelector('.va-projects__arrow--prev');
		var next = root.querySelector('.va-projects__arrow--next');
		if (!track) return;

		function step() {
			var card = track.querySelector('.va-project');
			return card ? card.getBoundingClientRect().width + 30 : track.clientWidth * 0.8;
		}
		function updateArrows() {
			var max = track.scrollWidth - track.clientWidth - 2;
			if (prev) prev.disabled = track.scrollLeft <= 2;
			if (next) next.disabled = track.scrollLeft >= max;
		}
		if (prev) prev.addEventListener('click', function () { track.scrollBy({ left: -step(), behavior: 'smooth' }); });
		if (next) next.addEventListener('click', function () { track.scrollBy({ left: step(), behavior: 'smooth' }); });
		track.addEventListener('scroll', updateArrows, { passive: true });
		window.addEventListener('resize', updateArrows);
		updateArrows();

		// Drag with the mouse
		var down = false, moved = false, startX = 0, startScroll = 0;
		track.addEventListener('mousedown', function (e) {
			down = true; moved = false; startX = e.pageX; startScroll = track.scrollLeft;
		});
		window.addEventListener('mousemove', function (e) {
			if (!down) return;
			var dx = e.pageX - startX;
			if (Math.abs(dx) > 5) { moved = true; track.classList.add('is-dragging'); }
			track.scrollLeft = startScroll - dx;
		});
		window.addEventListener('mouseup', function () {
			if (!down) return;
			down = false;
			track.classList.remove('is-dragging');
		});
		track.addEventListener('click', function (e) {
			if (moved) { e.preventDefault(); moved = false; }
		}, true);
	}

	/* ---------- Accordion ---------- */
	function initAccordion(root) {
		if (root.dataset.vaReady) return;
		root.dataset.vaReady = '1';

		root.querySelectorAll('.va-accordion__toggle').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var item = btn.closest('.va-accordion__item');
				var open = !item.classList.contains('is-open');
				root.querySelectorAll('.va-accordion__item.is-open').forEach(function (other) {
					if (other === item) return;
					other.classList.remove('is-open');
					other.querySelector('.va-accordion__toggle').setAttribute('aria-expanded', 'false');
					other.querySelector('.va-accordion__panel').hidden = true;
				});
				item.classList.toggle('is-open', open);
				btn.setAttribute('aria-expanded', open ? 'true' : 'false');
				item.querySelector('.va-accordion__panel').hidden = !open;
			});
		});
	}

	/* ---------- Reveal on scroll ---------- */
	function initReveal(scope) {
		var items = (scope || document).querySelectorAll('.va-reveal:not(.is-revealed)');
		if (!items.length) return;
		if (reduceMotion || !('IntersectionObserver' in window)) {
			items.forEach(function (el) { el.classList.add('is-revealed'); });
			return;
		}
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) return;
				entry.target.classList.add('is-revealed');
				io.unobserve(entry.target);
			});
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.05 });
		items.forEach(function (el) { io.observe(el); });
	}

	function initWidgets(scope) {
		scope = scope || document;
		scope.querySelectorAll('.va-hero').forEach(initHero);
		scope.querySelectorAll('.va-projects').forEach(initProjects);
		scope.querySelectorAll('.va-accordion').forEach(initAccordion);
	}

	function boot() {
		initHeader();
		initMobileMenu();
		initWidgets(document);
		initReveal(document);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}

	// Elementor editor: widgets are re-rendered on every change.
	var hooked = false;
	function hookElementor() {
		if (hooked || !window.elementorFrontend || !window.elementorFrontend.hooks) return;
		hooked = true;
		['va-hero-slider', 'va-projects-slider', 'va-accordion'].forEach(function (name) {
			window.elementorFrontend.hooks.addAction('frontend/element_ready/' + name + '.default', function ($scope) {
				initWidgets($scope[0]);
			});
		});
	}
	hookElementor();
	if (window.jQuery) window.jQuery(window).on('elementor/frontend/init', hookElementor);
	window.addEventListener('elementor/frontend/init', hookElementor);
})();
