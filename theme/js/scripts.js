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

function getFixedElementsSelector() {
	const selectors = ['#wpadminbar'];
	if (window.matchMedia('(min-width:1024px)').matches) {
		selectors.push('.volna-header.volna-fixed .volna-header-menu-body');
	} else {
		selectors.push('.volna-header');
	}
	return selectors.join(', ');
}

const volnaModal = new AccessibleMinimodal({
	disableScroll: {
		jumpingElements: getFixedElementsSelector(),
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
	focus: {
		use: false,
	},
});

$('.volna-modal').on('accessible-minimodal:after-close', e => {
	const target = e.currentTarget;
	if (target) {
		$(target).find('.volna-modal-wrapp').scrollTop(0);
	}

	const url = new URL(window.location.href);
	url.searchParams.delete('land');
	url.searchParams.delete('project');
	window.history.replaceState({}, '', url.toString());
});

/* header */

volnaFixedHeader();
$(window).on('load scroll resize', volnaFixedHeader);

function volnaFixedHeader() {
	const header = $('.volna-header');
	const target = window.matchMedia('(min-width:1024px)').matches ? $('.volna-header-menu-wrapp') : header;
	const barHeight = volnaGetPxCssVar('--wp-admin--admin-bar--height') || 0;
	if ($(window).scrollTop() >= target.offset()?.top - barHeight) {
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
		autoplay: {
			delay: 6000,
			disableOnInteraction: false,
		},
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

function volnaGetSliderNav(wrapp, navArr = []) {
	const nav = {};
	if (navArr.includes('navigation')) {
		nav.navigation = {
			nextEl: wrapp.find('.volna-slider-arrow-next')[0],
			prevEl: wrapp.find('.volna-slider-arrow-prev')[0],
		};
	}
	if (navArr.includes('pagination')) {
		nav.pagination = {
			el: wrapp.find('.volna-slider-pagination')[0],
			type: 'bullets',
			clickable: true,
		};
	}
	if (navArr.includes('scrollbar')) {
		nav.scrollbar = {
			el: wrapp.find('.volna-slider-scrollbar')[0],
			draggable: true,
		};
	}
	return nav;
}

$('.volna-neighbours-slider').each(function () {
	const wrapp = $(this).closest('.volna-slider-wrapp');
	new Swiper(this, {
		speed: 400,
		threshold: 8,
		spaceBetween: 20,
		slidesPerView: 'auto',
		...volnaGetSliderNav(wrapp, ['navigation', 'pagination']),
	});
});

$('.volna-gallery-slider').each(function () {
	const wrapp = $(this).closest('.volna-slider-wrapp');
	new Swiper(this, {
		speed: 400,
		threshold: 8,
		spaceBetween: 10,
		slidesPerView: 'auto',
		...volnaGetSliderNav(wrapp, ['navigation', 'pagination']),
	});
});

/* select */

$(document).on('click', '.volna-select-toggle', function (event) {
	event.preventDefault();
	const select = $(this).closest('.volna-select');
	if (select.hasClass('volna-active')) {
		$('.volna-select').removeClass('volna-active');
	} else {
		$('.volna-select').removeClass('volna-active');
		select.addClass('volna-active');
	}
});

$(document).on('change', '.volna-select-input', function (event) {
	const t = $(this);
	const select = t.closest('.volna-select');
	const value = t.val();
	const title = select.find('.volna-select-toggle-title');
	const titles = [];
	const multiple = select.hasClass('volna-select_multiple');
	if (!multiple) {
		select.find('.volna-select-input:checked').not(t).prop('checked', false);
		if (!t.prop('checked')) {
			t.prop('checked', true);
		}
	}
	select.find('.volna-select-input:checked').each(function () {
		titles.push($(this).closest('.volna-select-option').find('.volna-select-option-title').text());
	});

	title.text(titles.join(', '));

	if (multiple) {
		if (titles.length) {
			select.addClass('volna-value');
		} else {
			select.removeClass('volna-value');
		}
		return;
	}
	$('.volna-select').removeClass('volna-active');
	if (value === '*') {
		select.removeClass('volna-value');
		if (title.data('title')) {
			title.text(title.data('title'));
		}
	} else {
		select.addClass('volna-value');
	}
});

$(document).on('click', function (event) {
	if ($(event.target).closest('.volna-select.volna-active').length) {
		return;
	}
	$('.volna-select').removeClass('volna-active');
});

/* mask */

if (window.Maska) {
	const { MaskInput } = window.Maska;

	$('[type="tel"]').each(function () {
		const mask = new MaskInput(this, {
			mask: '+7 (###) ###-##-##',
		});
	});
}

/* tel input */

$(document).on('input', '[type="tel"]', function () {
	this.setCustomValidity('');
});

/* contacts-form */

$(document).on('submit', '.volna-contact-form', function (e) {
	e.preventDefault();

	if (!window.wp_ajax) {
		return;
	}

	const t = $(this);

	const telInput = t.find('[type="tel"]');

	if (telInput.length && window.Maska) {
		const { Mask } = window.Maska;

		const mask = new Mask({ mask: '+7 (###) ###-##-##' });
		const value = telInput.val();

		telInput[0].setCustomValidity('');

		if (!mask.completed(value)) {
			const alertMessage = telInput.data('alert');

			if (alertMessage) {
				telInput[0].setCustomValidity(alertMessage);
				telInput[0].reportValidity();
				return;
			} else {
				telInput.focus();
				return;
			}
		}
	}

	if (t.hasClass('volna-ajax-process')) {
		return;
	}
	t.addClass('volna-ajax-process');

	const formData = new FormData(t[0]);

	const url = new URL(window.location.href);

	formData.append('action', 'volna_contact_form');
	formData.append('nonce', wp_ajax.nonce);

	if (t.hasClass('volna-calculator-form')) {
		const calculator = {};

		t.find('[name^="_calculator"]:checked').each(function () {
			const input = $(this);
			const key = input.data('title');
			if (!calculator[key]) {
				calculator[key] = [];
			}
			calculator[key].push(input.val());
		});
		if (Object.keys(calculator).length) {
			formData.append('calculator', JSON.stringify(calculator));
		}
	}

	$.ajax({
		url: wp_ajax.url,
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		success: function (answer) {
			// console.log(answer);
			if (answer?.success) {
				volnaModal.closeAllModals();
				setTimeout(() => {
					volnaModal.openModal('volna-modal-sent');
					t.find('input.volna-input').val('');
				}, 400);
			}
			t.removeClass('volna-ajax-process');
		},
		error: error => {
			console.error(error);
			t.removeClass('volna-ajax-process');
		},
	});
});

/* volna-product-item */

function volnaGetProduct(t, postId, postType) {
	if (!window.wp_ajax) {
		return;
	}

	const url = t ? new URL(t.attr('href')) : '';

	const formData = new FormData();

	formData.append('target_post_id', t ? t.data('target-post-id') : postId);
	formData.append('target_post_type', t ? t.data('target-post-type') : postType);
	formData.append('action', 'volna_get_product');
	formData.append('nonce', wp_ajax.nonce);

	$.ajax({
		url: wp_ajax.url,
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		success: function (answer) {
			if (answer) {
				if (url) {
					window.history.replaceState({}, '', url);
				}
				$('#volna-modal-product .volna-modal-content').html(answer);
				volnaInitProductGallery();
				volnaModal.openModal('volna-modal-product');
			}
			if (t) {
				t.removeClass('volna-product-loading');
			}
		},
		error: error => {
			if (t) {
				t.removeClass('volna-product-loading');
			}
		},
	});
}

$(document).on('click', '.volna-product-item', function (e) {
	e.preventDefault();

	const t = $(this);

	if (t.find('volna-product-loading').length) {
		return;
	}
	t.addClass('volna-product-loading');

	volnaGetProduct(t);
});

$(document).ready(function () {
	const params = new URL(window.location.href).searchParams;
	if (params.get('land')) {
		volnaGetProduct(null, params.get('land'), 'volna-land');
	}
	if (params.get('project')) {
		volnaGetProduct(null, params.get('project'), 'volna-project');
	}
});

/* volna-product-gallery */

function volnaInitProductGallery() {
	$('.volna-product-gallery').each(function () {
		const t = $(this);
		const nav = t.find('.volna-product-gallery-nav');
		const navSlider = nav.length
			? new Swiper(nav[0], {
					speed: 400,
					slideToClickedSlide: true,
					watchSlidesProgress: true,
					slidesPerView: 'auto',
					spaceBetween: 10,
				})
			: null;
		const slider = new Swiper(t.find('.volna-product-gallery-slider')[0], {
			speed: 400,
			spaceBetween: 24,
			thumbs: {
				swiper: navSlider,
			},
			on: {
				slideChange: s => {
					if (!nav.length) {
						return;
					}

					const realIndex = s.realIndex;
					const activeSlide = t.find('.volna-product-gallery-nav-slide').eq(realIndex);
					const nextSlide = activeSlide.next();
					const prevSlide = activeSlide.prev();
					if (s.isEnd || (nextSlide.length && !nextSlide.hasClass('swiper-slide-visible'))) {
						s.thumbs.swiper.slideNext();
						return;
					}
					if (s.isBeginning || (prevSlide.length && !prevSlide.hasClass('swiper-slide-visible'))) {
						s.thumbs.swiper.slidePrev();
					}
				},
			},
		});
	});
}

/* calculator */

$('.volna-calculator-form-slider').each(function () {
	const form = $(this).closest('.volna-calculator-form');
	const pagination = form.find('.volna-calculator-form-pagination');
	const prevBtn = form.find('.volna-calculator-form-btn-prev');
	const submitBtn = form.find('.volna-calculator-form-btn-next');
	new Swiper(this, {
		speed: 400,
		threshold: 8,
		spaceBetween: 40,
		autoHeight: true,
		effect: 'fade',
		fadeEffect: {
			crossFade: true,
		},
		pagination: {
			el: pagination[0],
			type: 'fraction',
			renderFraction: (currentClass, totalClass) =>
				`${pagination.data('title')} <span class="${currentClass}"></span> ${pagination.data('divider')} <span class="${totalClass}"></span>`,
		},
		navigation: {
			prevEl: prevBtn[0],
		},
		on: {
			slideChange: s => {
				if (s.isEnd) {
					submitBtn.text(submitBtn.data('submit'));
				} else {
					submitBtn.text(submitBtn.data('title'));
					submitBtn.attr('type', 'button');
				}
				if (s.isBeginning) {
					prevBtn.removeClass('volna-active');
				} else {
					prevBtn.addClass('volna-active');
				}
			},
		},
	});
});

$(document).on('click', '.volna-calculator-form-btn-next', function (e) {
	const form = $(this).closest('.volna-calculator-form');
	const swiper = form.find('.volna-calculator-form-slider')[0]?.swiper;
	const submitBtn = form.find('.volna-calculator-form-btn-next');
	if (swiper.isEnd) {
		submitBtn.attr('type', 'submit');
	} else {
		swiper.slideNext();
	}
});

/* tabs */

$(document).on('click', '.volna-tab-btn', function (e) {
	e.preventDefault();
	const t = $(this);
	if (t.hasClass('volna-active')) {
		return;
	}
	volnaChangeTabs(t);
});

function volnaChangeTabs($tabBtn) {
	const tab = $tabBtn.data('volna-tab');
	const tabs = $tabBtn.data('volna-tabs');
	$(`.volna-tab-block[data-volna-tabs="${tabs}"], .volna-tab-btn[data-volna-tabs="${tabs}"]`)
		.removeClass('volna-active')
		.find('[data-required]')
		.removeAttr('required');
	$(`.volna-tab-block[data-volna-tab="${tab}"], .volna-tab-btn[data-volna-tab="${tab}"]`)
		.addClass('volna-active')
		.find('[data-required]')
		.attr('required', true);
}

$(document).on('change', '.volna-radio-input', function () {
	volnaChangeTabs($(this));
});

/* glightbox */

const lacLightbox = GLightbox({
	openEffect: 'fade',
	closeEffect: 'fade',
	videosWidth: 1600,
});

lacLightbox.on('open', () => $(getFixedElementsSelector()).addClass('gscrollbar-fixer'));

lacLightbox.on('close', () => $(getFixedElementsSelector()).removeClass('gscrollbar-fixer'));
