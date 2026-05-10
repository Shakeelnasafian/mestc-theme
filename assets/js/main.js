(function () {
	'use strict';

	var data = window.mestcData || {};

	document.addEventListener('DOMContentLoaded', init);

	function init() {
		initSlider();
		initMobileMenu();
		initFAQ();
		initSearch();
		initContactForm();
		initQuickQuote();
		initInquireModal();
		initScrollAnimations();
		initStickyHeader();
		initMegaMenu();
		initRfq();
	}

	/* ===== Hero slider ===== */
	function initSlider() {
		var track = document.getElementById('sliderTrack');
		if (!track) return;
		var slides = track.children;
		var total = slides.length;
		if (total < 2) return;

		var dots = document.querySelectorAll('.slider-dot');
		var prev = document.getElementById('sliderPrev');
		var next = document.getElementById('sliderNext');
		var current = 0;
		var paused = false;
		var auto;

		function goTo(n) {
			current = (n + total) % total;
			track.style.transform = 'translateX(-' + ((100 / total) * current) + '%)';
			for (var i = 0; i < dots.length; i++) {
				var on = i === current;
				dots[i].classList.toggle('active', on);
				dots[i].setAttribute('aria-selected', on ? 'true' : 'false');
			}
		}
		function play() {
			clearInterval(auto);
			auto = setInterval(function () { if (!paused) goTo(current + 1); }, 5500);
		}

		if (prev) prev.addEventListener('click', function () { goTo(current - 1); play(); });
		if (next) next.addEventListener('click', function () { goTo(current + 1); play(); });
		for (var i = 0; i < dots.length; i++) {
			(function (idx) { dots[idx].addEventListener('click', function () { goTo(idx); play(); }); })(i);
		}
		var slider = track.parentElement;
		slider.addEventListener('mouseenter', function () { paused = true; });
		slider.addEventListener('mouseleave', function () { paused = false; });
		document.addEventListener('keydown', function (e) {
			if (!isInView(slider)) return;
			if (e.key === 'ArrowLeft')  { goTo(current - 1); play(); }
			if (e.key === 'ArrowRight') { goTo(current + 1); play(); }
		});
		var startX = 0, deltaX = 0;
		track.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; deltaX = 0; }, { passive: true });
		track.addEventListener('touchmove',  function (e) { deltaX = e.touches[0].clientX - startX; }, { passive: true });
		track.addEventListener('touchend',   function () {
			if (Math.abs(deltaX) > 40) goTo(current + (deltaX < 0 ? 1 : -1));
			play();
		});
		play();
	}

	function isInView(el) {
		var r = el.getBoundingClientRect();
		return r.bottom > 0 && r.top < window.innerHeight;
	}

	/* ===== Mobile menu ===== */
	function initMobileMenu() {
		var toggle = document.getElementById('menuToggle');
		var menu = document.getElementById('primaryMenu');
		if (!toggle || !menu) return;
		toggle.addEventListener('click', function () {
			var open = menu.classList.toggle('is-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			document.body.classList.toggle('mestc-nav-open', open);
		});
		document.addEventListener('click', function (e) {
			if (!menu.classList.contains('is-open')) return;
			if (menu.contains(e.target) || toggle.contains(e.target)) return;
			menu.classList.remove('is-open');
			toggle.setAttribute('aria-expanded', 'false');
			document.body.classList.remove('mestc-nav-open');
		});
	}

	/* ===== FAQ accordion ===== */
	function initFAQ() {
		var btns = document.querySelectorAll('.faq-question');
		for (var i = 0; i < btns.length; i++) btns[i].addEventListener('click', toggleFaq);
	}
	function toggleFaq() {
		var btn = this;
		var expanded = btn.getAttribute('aria-expanded') === 'true';
		var panel = document.getElementById(btn.getAttribute('aria-controls'));
		var allBtns = document.querySelectorAll('.faq-question');
		for (var i = 0; i < allBtns.length; i++) {
			if (allBtns[i] !== btn) {
				allBtns[i].setAttribute('aria-expanded', 'false');
				var p = document.getElementById(allBtns[i].getAttribute('aria-controls'));
				if (p) p.hidden = true;
			}
		}
		btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
		if (panel) panel.hidden = expanded;
	}

	/* ===== AJAX search ===== */
	function initSearch() {
		var form = document.querySelector('.nav-search-form');
		if (!form) return;
		var input = form.querySelector('.nav-search');
		var box = document.getElementById('mestcSearchResults');
		if (!input || !box || !data.ajaxUrl) return;

		var debounce;
		input.addEventListener('input', function () {
			clearTimeout(debounce);
			var q = input.value.trim();
			if (q.length < 2) { box.classList.remove('is-visible'); box.innerHTML = ''; return; }
			box.innerHTML = '<div class="mestc-search-empty">' + (data.i18n && data.i18n.searching ? data.i18n.searching : '...') + '</div>';
			box.classList.add('is-visible');
			debounce = setTimeout(function () { runSearch(q, box); }, 220);
		});
		document.addEventListener('click', function (e) {
			if (!form.contains(e.target)) box.classList.remove('is-visible');
		});
		input.addEventListener('focus', function () {
			if (input.value.trim().length >= 2 && box.innerHTML) box.classList.add('is-visible');
		});
	}
	function runSearch(q, box) {
		var url = data.ajaxUrl + '?action=mestc_search&nonce=' + encodeURIComponent(data.searchNonce) + '&q=' + encodeURIComponent(q);
		fetch(url, { credentials: 'same-origin' })
			.then(function (r) { return r.json(); })
			.then(function (res) {
				if (!res || !res.success) { box.innerHTML = ''; return; }
				renderSearch(res.data, box);
			})
			.catch(function () { box.innerHTML = ''; });
	}
	function renderSearch(payload, box) {
		var items = payload.items || [];
		if (!items.length) {
			box.innerHTML = '<div class="mestc-search-empty">' + (data.i18n && data.i18n.noResults ? data.i18n.noResults : 'No results.') + '</div>';
			return;
		}
		var html = '';
		for (var i = 0; i < items.length; i++) {
			var it = items[i];
			html += '<a class="mestc-search-result" href="' + escAttr(it.url) + '">';
			if (it.thumb) html += '<img src="' + escAttr(it.thumb) + '" alt="" loading="lazy" />';
			else          html += '<span class="r-icon" aria-hidden="true">' + (it.type === 'product' ? '🛒' : '📰') + '</span>';
			html += '<span class="r-body"><span class="r-title">' + escHtml(it.title) + '</span>';
			html += '<span class="r-meta">' + escHtml(it.type) + (it.price ? ' · ' + escHtml(it.price) : '') + '</span></span>';
			html += '</a>';
		}
		if (payload.all_url) html += '<a class="mestc-search-all" href="' + escAttr(payload.all_url) + '">View all results →</a>';
		box.innerHTML = html;
	}

	/* ===== Contact form (homepage) ===== */
	function initContactForm() {
		var form = document.getElementById('mestcContactForm');
		if (!form || !data.ajaxUrl) return;
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var btn = form.querySelector('.form-submit');
			var msg = form.querySelector('.form-message--inline');
			if (btn) { btn.disabled = true; btn.dataset.original = btn.dataset.original || btn.textContent; btn.textContent = (data.i18n && data.i18n.sending) || '...'; }
			if (msg) { msg.className = 'form-message form-message--inline'; msg.textContent = ''; }
			var fd = new FormData(form);
			fd.set('action', 'mestc_contact');
			fd.set('nonce', data.contactNonce);
			fetch(data.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (res) {
					var ok = res && res.success;
					var text = (res && res.data && res.data.message) || ((ok ? data.i18n.sent : data.i18n.error) || '');
					if (msg) { msg.classList.add(ok ? 'is-ok' : 'is-err'); msg.textContent = text; }
					if (ok) form.reset();
				})
				.catch(function () { if (msg) { msg.classList.add('is-err'); msg.textContent = (data.i18n && data.i18n.error) || 'Error.'; } })
				.finally(function () { if (btn) { btn.disabled = false; btn.textContent = btn.dataset.original; } });
		});
	}

	/* ===== Quick quote ===== */
	function initQuickQuote() {
		var form = document.getElementById('mestcQuickQuote');
		if (!form || !data.ajaxUrl) return;
		var status = form.querySelector('.quote-band-status');
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var email = form.querySelector('input[name="email"]');
			if (!email || !email.value) return;
			if (status) { status.textContent = (data.i18n && data.i18n.sending) || ''; status.classList.remove('is-error'); }
			var fd = new FormData();
			fd.set('action', 'mestc_quick_quote');
			fd.set('nonce', data.contactNonce);
			fd.set('email', email.value);
			fetch(data.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (res) {
					var ok = res && res.success;
					var text = (res && res.data && res.data.message) || (ok ? data.i18n.sent : data.i18n.error);
					if (status) { status.textContent = text; status.classList.toggle('is-error', !ok); }
					if (ok) form.reset();
				})
				.catch(function () { if (status) { status.textContent = (data.i18n && data.i18n.error) || 'Error'; status.classList.add('is-error'); } });
		});
	}

	/* ===== Inquire modal =====
	 * Clicking an Inquire button now opens the user's email client directly
	 * with product details pre-filled (no modal). The modal markup stays
	 * intact for backward compatibility / programmatic use.
	 */
	function initInquireModal() {
		var modal = document.getElementById('mestcInquireModal');
		var form = modal && document.getElementById('mestcInquireForm');
		var status = form && form.querySelector('.mestc-inquire-form__status');
		var triggerEl = null;

		document.addEventListener('click', function (e) {
			var trigger = e.target.closest('.mestc-inquire-btn');
			if (trigger) {
				e.preventDefault();
				var ptitle = trigger.getAttribute('data-product-title') || '';
				var purl   = trigger.getAttribute('data-product-url') || '';
				window.location.href = buildMailto(ptitle, purl);
				return;
			}
			if (!modal) return;
			var closer = e.target.closest('[data-mestc-close]');
			if (closer && modal.contains(closer)) {
				closeModal();
			}
		});

		if (!modal) return;

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && !modal.hidden) closeModal();
		});

		function openModal(trigger) {
			triggerEl = trigger;
			var pid    = trigger.getAttribute('data-product-id') || '';
			var ptitle = trigger.getAttribute('data-product-title') || '';
			var purl   = trigger.getAttribute('data-product-url') || '';

			form.querySelector('[data-mestc-product-id]').value = pid;
			var prodWrap = modal.querySelector('[data-mestc-product]');
			if (ptitle) {
				prodWrap.hidden = false;
				modal.querySelector('[data-mestc-product-title]').textContent = ptitle;
				var link = modal.querySelector('[data-mestc-product-link]');
				if (purl) { link.href = purl; link.hidden = false; } else { link.hidden = true; }
			} else {
				prodWrap.hidden = true;
			}
			if (status) { status.className = 'mestc-inquire-form__status'; status.textContent = ''; }

			// Build a mailto: link with the product context so users can use their own email client.
			var mailto = modal.querySelector('[data-mestc-mailto]');
			if (mailto) {
				mailto.href = buildMailto(ptitle, purl);
			}

			modal.hidden = false;
			document.body.classList.add('mestc-modal-open');
			requestAnimationFrame(function () { modal.classList.add('is-open'); });

			var firstField = form.querySelector('input[name="name"]');
			if (firstField) setTimeout(function () { firstField.focus(); }, 50);
		}

		function buildMailto(ptitle, purl) {
			var to       = data.contactEmail || '';
			var site     = data.siteName || 'MESTC';
			var i18n     = data.i18n || {};
			var subject  = (i18n.mailtoSubject || 'Product Inquiry');
			if (ptitle) { subject += ' — ' + ptitle; }

			var lines = [];
			lines.push(i18n.mailtoIntro || 'Hello,');
			lines.push('');
			lines.push(i18n.mailtoBody || "I'd like to inquire about the following product:");
			lines.push('');
			if (ptitle) { lines.push('Product: ' + ptitle); }
			if (purl)   { lines.push('URL: ' + purl); }
			lines.push('');
			lines.push('Quantity: ');
			lines.push('Delivery location: ');
			lines.push('Required certifications: ');
			lines.push('');
			lines.push(i18n.mailtoSignoff || 'Thanks,');

			return 'mailto:' + encodeURIComponent(to)
				+ '?subject=' + encodeURIComponent(subject)
				+ '&body='    + encodeURIComponent(lines.join('\n'));
		}

		function closeModal() {
			modal.classList.remove('is-open');
			setTimeout(function () { modal.hidden = true; document.body.classList.remove('mestc-modal-open'); if (triggerEl) triggerEl.focus(); }, 250);
		}

		if (!form || !data.ajaxUrl) return;
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var btn = form.querySelector('.mestc-inquire-form__submit');
			if (btn) { btn.disabled = true; btn.dataset.original = btn.dataset.original || btn.textContent; btn.textContent = (data.i18n && data.i18n.sending) || '...'; }
			if (status) { status.className = 'mestc-inquire-form__status'; status.textContent = ''; }

			var fd = new FormData(form);
			fd.set('action', 'mestc_inquire');
			fd.set('nonce', data.inquireNonce);

			fetch(data.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (res) {
					var ok = res && res.success;
					var text = (res && res.data && res.data.message) || (ok ? data.i18n.sent : data.i18n.error);
					if (status) { status.classList.add(ok ? 'is-ok' : 'is-err'); status.textContent = text; }
					if (ok) {
						form.reset();
						setTimeout(closeModal, 1800);
					}
				})
				.catch(function () { if (status) { status.classList.add('is-err'); status.textContent = (data.i18n && data.i18n.error) || 'Error.'; } })
				.finally(function () { if (btn) { btn.disabled = false; btn.textContent = btn.dataset.original; } });
		});
	}

	/* ===== Scroll-triggered animations =====
	 * Only opt small grid items in to entrance animations, never whole sections.
	 * Anything that hasn't been intersected within 1500 ms is shown anyway, so
	 * users without scrolling (and SEO/screenshot tools) never see invisible content.
	 */
	function initScrollAnimations() {
		var prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		if (prefersReduced || !('IntersectionObserver' in window)) {
			return;
		}

		var selectors = [
			'.cat-card', '.mestc-product-card', '.ind-card',
			'.blog-card', '.brand-item', '.faq-item',
			'.stat-item', '.trust-item'
		].join(',');
		var els = document.querySelectorAll(selectors);
		if (!els.length) return;

		els.forEach(function (el, i) {
			el.classList.add('mestc-anim');
			el.style.transitionDelay = Math.min(i % 6 * 50, 250) + 'ms';
		});

		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					io.unobserve(entry.target);
				}
			});
		}, { threshold: 0.05, rootMargin: '0px 0px -20px 0px' });

		els.forEach(function (el) { io.observe(el); });

		// Safety net: reveal everything after 1.5s to avoid persistent invisibility
		// when IO never fires (programmatic capture, prerender, broken JS, etc.).
		setTimeout(function () {
			els.forEach(function (el) { el.classList.add('is-visible'); });
		}, 1500);
	}

	/* ===== Mega menu (Products hover/focus) ===== */
	function initMegaMenu() {
		var mega = document.getElementById('mestcMega');
		if (!mega) return;
		// Find any nav link that points to the shop / products page.
		var triggers = [];
		var menuLinks = document.querySelectorAll('.mestc-menu a');
		for (var i = 0; i < menuLinks.length; i++) {
			var href = menuLinks[i].getAttribute('href') || '';
			var text = (menuLinks[i].textContent || '').trim().toLowerCase();
			if (text === 'products' || /\/products\/?$|\/shop\/?$/i.test(href)) {
				triggers.push(menuLinks[i]);
			}
		}
		if (!triggers.length) return;

		var hideTimer;
		function open() {
			clearTimeout(hideTimer);
			mega.classList.add('is-open');
			mega.setAttribute('aria-hidden', 'false');
			triggers.forEach(function (t) { t.setAttribute('aria-expanded', 'true'); });
		}
		function close() {
			mega.classList.remove('is-open');
			mega.setAttribute('aria-hidden', 'true');
			triggers.forEach(function (t) { t.setAttribute('aria-expanded', 'false'); });
		}
		function scheduleClose() {
			clearTimeout(hideTimer);
			hideTimer = setTimeout(close, 180);
		}

		triggers.forEach(function (t) {
			t.setAttribute('aria-haspopup', 'true');
			t.setAttribute('aria-expanded', 'false');
			// Hide any WP-generated sub-menu — the mega panel replaces it.
			var li = t.parentElement;
			if (li) {
				var sub = li.querySelector('.sub-menu');
				if (sub) sub.style.display = 'none';
				li.classList.add('has-mega');
			}
			t.addEventListener('mouseenter', open);
			t.addEventListener('mouseleave', scheduleClose);
			t.addEventListener('focus', open);
			t.addEventListener('blur', scheduleClose);
		});
		mega.addEventListener('mouseenter', open);
		mega.addEventListener('mouseleave', scheduleClose);
		mega.addEventListener('focusin', open);
		mega.addEventListener('focusout', scheduleClose);

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && mega.classList.contains('is-open')) close();
		});
	}

	/* ===== Sticky header polish ===== */
	function initStickyHeader() {
		var nav = document.querySelector('.mestc-nav-wrap');
		if (!nav) return;
		var lastY = 0;
		function onScroll() {
			var y = window.pageYOffset;
			nav.classList.toggle('is-scrolled', y > 8);
			lastY = y;
		}
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
	}

	/* ===== Bulk RFQ basket =====
	 * Stores accumulated products in localStorage, renders a drawer with line
	 * items + customer form, and submits the whole thing as one inquiry.
	 */
	var RFQ_KEY = 'mestc_rfq_v1';

	function rfqRead() {
		try {
			var raw = localStorage.getItem(RFQ_KEY);
			var arr = raw ? JSON.parse(raw) : [];
			return Array.isArray(arr) ? arr : [];
		} catch (e) { return []; }
	}
	function rfqWrite(arr) {
		try { localStorage.setItem(RFQ_KEY, JSON.stringify(arr)); } catch (e) {}
		rfqRefresh();
	}
	function rfqAdd(item) {
		if (!item || !item.id) return;
		var arr = rfqRead();
		var existing = arr.find(function (x) { return String(x.id) === String(item.id); });
		if (existing) {
			existing.qty = (existing.qty || 1) + 1;
		} else {
			arr.push({
				id: String(item.id),
				title: item.title || '',
				url: item.url || '',
				thumb: item.thumb || '',
				qty: 1
			});
		}
		rfqWrite(arr);
	}
	function rfqRemove(id) {
		rfqWrite(rfqRead().filter(function (x) { return String(x.id) !== String(id); }));
	}
	function rfqUpdateQty(id, qty) {
		var arr = rfqRead();
		var line = arr.find(function (x) { return String(x.id) === String(id); });
		if (!line) return;
		line.qty = Math.max(1, parseInt(qty, 10) || 1);
		rfqWrite(arr);
	}
	function rfqClear() { rfqWrite([]); }

	function initRfq() {
		var drawer = document.getElementById('mestcRfq');
		var fab    = document.getElementById('mestcRfqFab');
		if (!drawer && !fab) return;

		// Open / close
		document.addEventListener('click', function (e) {
			var openTrigger = e.target.closest('[data-mestc-rfq-open]');
			if (openTrigger) {
				e.preventDefault();
				rfqOpen();
				return;
			}
			var closeTrigger = e.target.closest('[data-mestc-rfq-close]');
			if (closeTrigger && drawer && drawer.contains(closeTrigger)) {
				rfqClose();
				return;
			}
			// Add-to-RFQ button anywhere on the page
			var addTrigger = e.target.closest('.mestc-add-rfq');
			if (addTrigger) {
				e.preventDefault();
				rfqAdd({
					id:    addTrigger.getAttribute('data-product-id'),
					title: addTrigger.getAttribute('data-product-title'),
					url:   addTrigger.getAttribute('data-product-url'),
					thumb: addTrigger.getAttribute('data-product-thumb')
				});
				rfqOpen();
				rfqFlashAdded(addTrigger);
				return;
			}
			// Remove a single line
			var removeTrigger = e.target.closest('[data-mestc-rfq-remove]');
			if (removeTrigger) {
				rfqRemove(removeTrigger.getAttribute('data-mestc-rfq-remove'));
				return;
			}
		});

		// Qty changes inside the drawer
		if (drawer) {
			drawer.addEventListener('input', function (e) {
				if (e.target.matches('[data-mestc-rfq-qty]')) {
					rfqUpdateQty(e.target.getAttribute('data-mestc-rfq-qty'), e.target.value);
				}
			});
		}

		// Form submit
		var form = document.getElementById('mestcRfqForm');
		if (form && data.ajaxUrl) {
			form.addEventListener('submit', function (e) {
				e.preventDefault();
				rfqSubmit(form);
			});
		}

		// Esc closes
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && drawer && !drawer.hidden) rfqClose();
		});

		// Initial paint
		rfqRefresh();
	}

	function rfqRefresh() {
		var lines = rfqRead();
		var drawer = document.getElementById('mestcRfq');
		var fab    = document.getElementById('mestcRfqFab');
		var totalQty = lines.reduce(function (n, l) { return n + (parseInt(l.qty, 10) || 1); }, 0);

		// Counters everywhere
		var counts = document.querySelectorAll('[data-mestc-rfq-count]');
		for (var i = 0; i < counts.length; i++) { counts[i].textContent = lines.length; }

		// Header pill: hide when empty unless on shop pages.
		var pills = document.querySelectorAll('.mestc-rfq-pill');
		for (var p = 0; p < pills.length; p++) {
			pills[p].classList.toggle('has-items', lines.length > 0);
		}

		// Floating FAB visibility
		if (fab) {
			fab.hidden = lines.length === 0;
		}

		if (!drawer) return;

		var listEl  = drawer.querySelector('[data-mestc-rfq-lines]');
		var emptyEl = drawer.querySelector('[data-mestc-rfq-empty]');
		var formEl  = drawer.querySelector('[data-mestc-rfq-form]');
		var mailto  = drawer.querySelector('[data-mestc-rfq-mailto]');

		if (lines.length === 0) {
			if (listEl)  listEl.hidden  = true;
			if (formEl)  formEl.hidden  = true;
			if (emptyEl) emptyEl.hidden = false;
		} else {
			if (emptyEl) emptyEl.hidden = true;
			if (listEl)  { listEl.hidden = false; listEl.innerHTML = lines.map(rfqLineHtml).join(''); }
			if (formEl)  formEl.hidden = false;
			if (mailto && data.contactEmail) {
				mailto.href = rfqBuildMailto(lines);
			}
		}
	}

	function rfqLineHtml(line) {
		var thumb = line.thumb
			? '<img src="' + escAttr(line.thumb) + '" alt="" loading="lazy" />'
			: '<span class="mestc-rfq__ph" aria-hidden="true">📦</span>';
		return ''
			+ '<li class="mestc-rfq__line" data-id="' + escAttr(line.id) + '">'
			+ '<div class="mestc-rfq__line-thumb">' + thumb + '</div>'
			+ '<div class="mestc-rfq__line-body">'
			+   '<a class="mestc-rfq__line-title" href="' + escAttr(line.url) + '">' + escHtml(line.title) + '</a>'
			+   '<div class="mestc-rfq__line-actions">'
			+     '<label class="mestc-rfq__qty">'
			+       '<span>Qty</span>'
			+       '<input type="number" min="1" value="' + (parseInt(line.qty, 10) || 1) + '" data-mestc-rfq-qty="' + escAttr(line.id) + '" />'
			+     '</label>'
			+     '<button type="button" class="mestc-rfq__remove" data-mestc-rfq-remove="' + escAttr(line.id) + '" aria-label="Remove">×</button>'
			+   '</div>'
			+ '</div>'
			+ '</li>';
	}

	function rfqBuildMailto(lines) {
		var to = data.contactEmail || '';
		var subject = 'Bulk RFQ — ' + lines.length + ' items';
		var bodyParts = ['Hello MESTC team,', '', 'I would like to inquire about the following ' + lines.length + ' product(s):', ''];
		lines.forEach(function (l, i) {
			bodyParts.push((i + 1) + '. ' + l.title);
			bodyParts.push('   Quantity: ' + (l.qty || 1));
			bodyParts.push('   URL: ' + l.url);
			bodyParts.push('');
		});
		bodyParts.push('Please send pricing, lead times and stock availability.');
		bodyParts.push('');
		bodyParts.push('Thanks,');
		return 'mailto:' + encodeURIComponent(to)
			+ '?subject=' + encodeURIComponent(subject)
			+ '&body='    + encodeURIComponent(bodyParts.join('\n'));
	}

	function rfqOpen() {
		var d = document.getElementById('mestcRfq');
		if (!d) return;
		d.hidden = false;
		document.body.classList.add('mestc-rfq-open');
		requestAnimationFrame(function () { d.classList.add('is-open'); });
		d.setAttribute('aria-hidden', 'false');
	}
	function rfqClose() {
		var d = document.getElementById('mestcRfq');
		if (!d) return;
		d.classList.remove('is-open');
		document.body.classList.remove('mestc-rfq-open');
		setTimeout(function () { d.hidden = true; d.setAttribute('aria-hidden', 'true'); }, 250);
	}
	function rfqFlashAdded(btn) {
		btn.classList.add('is-added');
		var orig = btn.getAttribute('data-original-label');
		if (!orig) {
			btn.setAttribute('data-original-label', btn.textContent.trim());
		}
		btn.innerHTML = '<span aria-hidden="true">✓</span> Added';
		setTimeout(function () {
			btn.classList.remove('is-added');
			var label = btn.getAttribute('data-original-label');
			if (label) btn.textContent = label;
		}, 1400);
	}

	function rfqSubmit(form) {
		var lines = rfqRead();
		var status = form.querySelector('.mestc-rfq__status');
		var submit = form.querySelector('.mestc-rfq__submit');
		if (!lines.length) {
			if (status) { status.textContent = 'Your inquiry list is empty.'; status.className = 'mestc-rfq__status is-err'; }
			return;
		}
		if (submit) { submit.disabled = true; submit.dataset.original = submit.dataset.original || submit.textContent; submit.textContent = 'Sending…'; }
		if (status) { status.textContent = ''; status.className = 'mestc-rfq__status'; }

		var fd = new FormData(form);
		fd.set('action', 'mestc_rfq_submit');
		fd.set('nonce', data.inquireNonce);
		fd.set('lines', JSON.stringify(lines.map(function (l) {
			return { id: l.id, qty: parseInt(l.qty, 10) || 1, note: l.note || '' };
		})));

		fetch(data.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
			.then(function (r) { return r.json(); })
			.then(function (res) {
				var ok = res && res.success;
				var msg = (res && res.data && res.data.message) || (ok ? 'Sent.' : 'Error.');
				if (status) { status.textContent = msg; status.className = 'mestc-rfq__status ' + (ok ? 'is-ok' : 'is-err'); }
				if (ok) {
					rfqClear();
					form.reset();
					setTimeout(rfqClose, 1800);
				}
			})
			.catch(function () {
				if (status) { status.textContent = 'Network error. Please try again.'; status.className = 'mestc-rfq__status is-err'; }
			})
			.finally(function () {
				if (submit) { submit.disabled = false; submit.textContent = submit.dataset.original; }
			});
	}

	/* ===== Helpers ===== */
	function escHtml(s) {
		return String(s == null ? '' : s)
			.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;').replace(/'/g, '&#039;');
	}
	function escAttr(s) { return escHtml(s); }
})();
