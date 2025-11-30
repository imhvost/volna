'use strict';

/* rem */

function volnaGetPxCssVar(variable, el) {
	el = el || document.documentElement;
	const cssVar = parseFloat(getComputedStyle(el).getPropertyValue(variable)) || 0;
	return cssVar;
}

function volnaConvertPxToRem(px) {
	const rem = parseFloat(getComputedStyle(document.documentElement).fontSize);
	return (px / rem).toFixed(3);
}

/* modal */

const volnaFixedElements = '.volna-header, #wpadminbar, .volna-home-slider';

const volnaModal = new AccessibleMinimodal({
	disableScroll: {
		jumpingElements: volnaFixedElements,
	},
	classes: {
		modal: 'volna-modal',
		wrapp: 'volna-modal-wrapp',
		body: 'volna-modal-body',
		closeBtn: 'volna-modal-btn-close',
		active: 'volna-active',
		open: 'volna-open',
		close: 'volna-close',
	},
	on: {
		beforeOpen: instance => {
			if (instance.openBtn && instance.modal) {
				const modal = $(instance.modal);
				const btn = $(instance.openBtn);
				const fields = ['subject', 'post_type', 'post_id'];
				fields.forEach(el => {
					modal.find(`[name="${el}"]`).val('');
					if (btn.data(el)) {
						modal.find(`[name="${el}"]`).val(btn.data(el));
					}
				});
			}
		},
	},
	multiple: {
		use: true,
	},
});

/* header */

volnaFixedHeader();
$(window).on('load scroll resize', volnaFixedHeader);

function volnaFixedHeader() {
	const header = $('.volna-header-menu-wrapp');
	const barHeight = volnaGetPxCssVar('--wp-admin--admin-bar--height') || 0;
	if ($(window).scrollTop() >= header.offset()?.top - barHeight) {
		header.addClass('volna-fixed');
	} else {
		header.removeClass('volna-fixed');
	}
}

/* header-menu */

$(document).on('click', '.volna-header-menu .menu-item-has-children>a', function () {
	if (window.matchMedia('(min-width:1024px)').matches) {
		return false;
	}
	const li = $(this).closest('li');
	const subMenu = li.children('.sub-menu');
	if (li.hasClass('volna-active')) {
		closeMenu();
	} else {
		closeMenu();
		li.addClass('volna-active');
		subMenu.stop().slideDown(400);
	}
	function closeMenu() {
		const lis = li.parent().children('.menu-item-has-children');
		lis.removeClass('volna-active');
		lis.children('.sub-menu').stop().slideUp(400);
	}
	return false;
});

/* scroll */

$(document).on('click', '.volna-header-menu a', function () {
	const t = $(this);
	if (t.attr('href').startsWith('/#')) {
		const sectionName = t.attr('href').replace('/#', '');
		const section = $(`.volna-section-${sectionName}`)[0];
		if (section) {
			section.scrollIntoView({ behavior: 'smooth' });
		}
		return false;
	}
});

/* volna-accordion */

$(document).on('click', '.volna-accordion-item-toggle', function () {
	const item = $(this).closest('.volna-accordion-item');
	const accordion = item.closest('.volna-accordion');

	if (item.hasClass('volna-active')) {
		accordion.find('.volna-accordion-item').removeClass('volna-active');
	} else {
		accordion.find('.volna-accordion-item').not(item).removeClass('volna-active');
		item.addClass('volna-active');
	}
	return false;
});

/* sliders */

$('.volna-hero-slider').each(function () {
	const wrapp = $(this).closest('.volna-hero');
	new Swiper(this, {
		speed: 400,
		threshold: 8,
		effect: 'fade',
		fadeEffect: {
			crossFade: true,
		},
		// autoplay: {
		// 	delay: 6000,
		// 	disableOnInteraction: false,
		// },
		pagination: {
			el: wrapp.find('.volna-hero-slider-pagination')[0],
			clickable: true,
			renderBullet: (index, className) => `<span class="${className}">${index + 1}<i></i></span>`,
		},
		on: {
			autoplayTimeLeft(s, time, progress) {
				wrapp.css('--autoplay-progress', 1 - progress);
			},
		},
	});
});
