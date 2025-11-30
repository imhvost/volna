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

	$.ajax({
		url: wp_ajax.url,
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		success: function (answer) {
			// console.log(answer);
			if (answer?.success) {
				volnaModal.openModal('volna-modal-sent');
				t.find('input.volna-input').val('');
			}
			t.removeClass('volna-ajax-process');
		},
		error: error => {
			console.error(error);
			t.removeClass('volna-ajax-process');
		},
	});
});
