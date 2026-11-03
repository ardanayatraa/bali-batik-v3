/* Bali Batik V3 — front-end behaviour. No dependencies. */
(function () {
	'use strict';

	var cfg = window.bb3 || {};
	var t = cfg.i18n || {};
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var $ = function (s, c) { return (c || document).querySelector(s); };
	var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };
	var focusables = 'a[href],button:not([disabled]),input:not([disabled]),select,textarea,summary,[tabindex]:not([tabindex="-1"])';
	/* Run a component once per element (sections can be re-rendered by the Customizer). */
	var each = function (sel, root, fn) {
		$$(sel, root).concat(root && root.matches && root.matches(sel) ? [root] : []).forEach(function (el) {
			if (el.dataset.bb3Ready) return;
			el.dataset.bb3Ready = '1';
			fn(el);
		});
	};

	/* ── Header: dark once the page scrolls past the hero top ───────── */
	var header = $('[data-bb3-header]');
	if (header && header.classList.contains('bb3-header--overlay')) {
		var onScroll = function () {
			header.classList.toggle('is-scrolled', window.scrollY > 90);
		};
		window.addEventListener('scroll', onScroll, { passive: true });
		if (window.scrollY > 0) onScroll();
	}

	/* ── Search panel ──────────────────────────────────────────────── */
	var searchBtn = $('[data-bb3-search-toggle]');
	var searchPanel = $('#bb3-search-panel');
	function setSearch(open) {
		searchPanel.hidden = !open;
		searchBtn.setAttribute('aria-expanded', String(open));
		if (header) header.classList.toggle('is-searching', open);
		if (open) {
			var input = $('input', searchPanel);
			if (input) input.focus();
		}
	}
	if (searchBtn && searchPanel) {
		searchBtn.addEventListener('click', function () { setSearch(searchPanel.hidden); });
		searchPanel.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') { setSearch(false); searchBtn.focus(); }
		});
	}

	/* ── Mobile drawer ─────────────────────────────────────────────── */
	var drawer = $('#bb3-drawer');
	var drawerBtn = $('[data-bb3-drawer-open]');
	function closeDrawer(restore) {
		if (!drawer || drawer.hidden) return;
		drawer.hidden = true;
		document.body.style.overflow = '';
		drawerBtn.setAttribute('aria-expanded', 'false');
		if (restore !== false) drawerBtn.focus();
	}
	if (drawer && drawerBtn) {
		drawerBtn.addEventListener('click', function () {
			drawer.hidden = false;
			document.body.style.overflow = 'hidden';
			drawerBtn.setAttribute('aria-expanded', 'true');
			var first = $('[data-bb3-drawer-close]', drawer);
			if (first) first.focus();
		});
		$('[data-bb3-drawer-close]', drawer).addEventListener('click', function () { closeDrawer(); });
		drawer.addEventListener('click', function (e) { if (e.target === drawer) closeDrawer(); });
		drawer.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') { closeDrawer(); return; }
			if (e.key !== 'Tab') return;
			var items = $$(focusables, drawer).filter(function (el) { return el.offsetParent !== null; });
			if (!items.length) return;
			var first = items[0], last = items[items.length - 1];
			if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
			else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
		});
	}

	/* ── Announcement bar ──────────────────────────────────────────── */
	function initAnnounce(root) {
		each('[data-bb3-announce]', root, function (box) {
			var items = $$('p', box);
			if (items.length < 2) return;
			var i = 0, timer = null;
			var show = function (n) {
				items[i].hidden = true;
				i = (n + items.length) % items.length;
				items[i].hidden = false;
			};
			var start = function () {
				clearInterval(timer);
				if (!reduce) timer = setInterval(function () { show(i + 1); }, 6000);
			};
			$$('button', box).forEach(function (b) {
				b.addEventListener('click', function () { show(i + Number(b.dataset.dir)); start(); });
			});
			box.addEventListener('mouseenter', function () { clearInterval(timer); });
			box.addEventListener('mouseleave', start);
			box.addEventListener('focusin', function () { clearInterval(timer); });
			start();
		});
	}

	/* ── Hero slides (manual) ──────────────────────────────────────── */
	function initHero(root) {
		each('[data-bb3-hero]', root, function (hero) {
			var slides = $$('.bb3-hero__slide', hero);
			if (slides.length < 2) return;
			var cur = 0;
			var go = function (n) {
				slides[cur].classList.remove('is-active', 'is-entering');
				slides[cur].inert = true;
				cur = (n + slides.length) % slides.length;
				slides[cur].classList.add('is-active', 'is-entering');
				slides[cur].inert = false;
			};
			$$('.bb3-hero__arrow', hero).forEach(function (b) {
				b.addEventListener('click', function () { go(cur + Number(b.dataset.dir)); });
			});
			hero.addEventListener('keydown', function (e) {
				if (e.key === 'ArrowLeft') go(cur - 1);
				if (e.key === 'ArrowRight') go(cur + 1);
			});
			var x0 = null;
			hero.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
			hero.addEventListener('touchend', function (e) {
				if (x0 === null) return;
				var dx = e.changedTouches[0].clientX - x0;
				if (Math.abs(dx) > 50) go(cur + (dx < 0 ? 1 : -1));
				x0 = null;
			});
			// Fetch the other slide photos once the page has settled.
			var warm = function () {
				setTimeout(function () {
					$$('.bb3-hero__slide img[loading="lazy"]', hero).forEach(function (img) { img.loading = 'eager'; });
				}, 2500);
			};
			if (document.readyState === 'complete') warm(); else window.addEventListener('load', warm);
		});
	}

	/* ── Moving category row ───────────────────────────────────────── */
	function initMarquee(root) {
		each('[data-bb3-marquee]', root, function (vp) {
			var track = $('.bb3-cats__track', vp);
			var controls = vp.parentNode.querySelector('[data-bb3-marquee-controls]');
			var items = Array.prototype.slice.call(track.children);
			var speed = Number(vp.dataset.speed || cfg.speed || 27);
			if (items.length < 3 || track.scrollWidth < vp.clientWidth * 1.15) return; // short list stays static

			items.forEach(function (li) {
				var clone = li.cloneNode(true);
				clone.setAttribute('aria-hidden', 'true');
				clone.inert = true;
				$$('a', clone).forEach(function (a) { a.tabIndex = -1; });
				track.appendChild(clone);
			});
			vp.classList.add('is-looping');
			if (controls) controls.hidden = false;

			var period = 0, pos = 0, last = 0;
			var state = { user: reduce || speed <= 0, hover: false, focus: false, touch: false, tab: false, off: false, busy: false };
			var measure = function () { period = track.children[items.length].offsetLeft - items[0].offsetLeft; };
			var running = function () { return !state.user && !state.hover && !state.focus && !state.touch && !state.tab && !state.off && !state.busy; };
			var wrap = function () {
				if (vp.scrollLeft >= period) vp.scrollLeft -= period;
				else if (vp.scrollLeft <= 0) vp.scrollLeft += period;
				pos = vp.scrollLeft;
			};
			measure();
			vp.scrollLeft = pos = 1;

			var frame = function (now) {
				requestAnimationFrame(frame);
				var dt = last ? Math.min(0.05, (now - last) / 1000) : 0;
				last = now;
				if (!running() || !period) return;
				pos += Math.max(speed, 5) * dt;
				if (pos >= period) pos -= period;
				vp.scrollLeft = pos;
			};
			requestAnimationFrame(frame);

			vp.addEventListener('scroll', function () {
				if (!running() && !state.busy) wrap();
			}, { passive: true });
			vp.addEventListener('mouseenter', function () { state.hover = true; });
			vp.addEventListener('mouseleave', function () { state.hover = false; pos = vp.scrollLeft; });
			vp.addEventListener('focusin', function () { state.focus = true; });
			vp.addEventListener('focusout', function () { state.focus = false; pos = vp.scrollLeft; });
			vp.addEventListener('touchstart', function () { state.touch = true; }, { passive: true });
			vp.addEventListener('touchend', function () {
				setTimeout(function () { state.touch = false; wrap(); }, 1500);
			}, { passive: true });
			document.addEventListener('visibilitychange', function () { state.tab = document.hidden; last = 0; });
			if ('IntersectionObserver' in window) {
				new IntersectionObserver(function (entries) { state.off = !entries[0].isIntersecting; last = 0; }).observe(vp);
			}
			window.addEventListener('resize', function () { measure(); wrap(); });

			if (!controls) return;
			var step = function (dir) {
				state.busy = true;
				var size = items[0].getBoundingClientRect().width + parseFloat(getComputedStyle(track).columnGap || 20);
				var target = vp.scrollLeft + dir * size;
				if (target < 0) { vp.scrollLeft += period; target += period; }
				if (target > period * 1.5) { vp.scrollLeft -= period; target -= period; }
				vp.scrollTo({ left: target, behavior: reduce ? 'auto' : 'smooth' });
				setTimeout(function () { state.busy = false; wrap(); }, reduce ? 50 : 650);
			};
			var toggle = $('[data-action="toggle"]', controls);
			var paint = function () {
				toggle.setAttribute('aria-pressed', String(state.user));
				$('[data-icon-pause]', toggle).hidden = state.user;
				$('[data-icon-play]', toggle).hidden = !state.user;
				$('[data-label]', toggle).textContent = state.user ? t.play : t.pause;
			};
			$('[data-action="prev"]', controls).addEventListener('click', function () { step(-1); });
			$('[data-action="next"]', controls).addEventListener('click', function () { step(1); });
			toggle.addEventListener('click', function () { state.user = !state.user; pos = vp.scrollLeft; last = 0; paint(); });
			paint();
		});
	}

	/* ── New-arrivals tabs ─────────────────────────────────────────── */
	function initTabs(root) {
		each('[data-bb3-tabs]', root, function (box) {
			var buttons = $$('.bb3-tabs button', box);
			var cards = $$('.bb3-card', box);
			var empty = $('[data-bb3-tabs-empty]', box);
			buttons.forEach(function (b) {
				b.addEventListener('click', function () {
					var f = b.dataset.filter, shown = 0;
					buttons.forEach(function (x) {
						x.classList.toggle('is-active', x === b);
						x.setAttribute('aria-pressed', String(x === b));
					});
					cards.forEach(function (c) {
						var ok = f === '*' || (' ' + c.dataset.cats + ' ').indexOf(' ' + f + ' ') > -1;
						c.hidden = !ok;
						if (ok) shown++;
					});
					if (empty) empty.hidden = shown > 0;
				});
			});
		});
	}

	/* ── Favorites (this browser only) ─────────────────────────────── */
	var KEY = cfg.favKey || 'bb3_favorites';
	var load = function () {
		try {
			var v = JSON.parse(window.localStorage.getItem(KEY) || '[]');
			return Array.isArray(v) ? v.filter(function (x) { return x && x.id; }) : [];
		} catch (e) { return []; }
	};
	var store = function (list) {
		try { window.localStorage.setItem(KEY, JSON.stringify(list)); return true; } catch (e) { return false; }
	};
	var favs = load();
	var has = function (id) { return favs.some(function (f) { return String(f.id) === String(id); }); };
	var sync = function () {
		$$('[data-bb3-fav]').forEach(function (b) {
			var on = has(b.dataset.bb3Fav);
			b.setAttribute('aria-pressed', String(on));
			var label = $('[data-bb3-fav-label]', b);
			if (label) label.textContent = on ? t.saved : t.save;
		});
		$$('[data-bb3-fav-count]').forEach(function (c) {
			c.textContent = String(favs.length);
			c.hidden = !favs.length;
		});
	};
	document.addEventListener('click', function (e) {
		var b = e.target.closest('[data-bb3-fav]');
		if (!b) return;
		e.preventDefault();
		var id = b.dataset.bb3Fav;
		if (has(id)) {
			favs = favs.filter(function (f) { return String(f.id) !== String(id); });
		} else {
			favs.unshift({ id: Number(id), title: b.dataset.title, url: b.dataset.url, image: b.dataset.image, price: b.dataset.price });
			favs = favs.slice(0, 60);
		}
		if (!store(favs)) window.alert(t.storage);
		sync();
	});
	window.addEventListener('storage', function (e) { if (e.key === KEY) { favs = load(); sync(); } });

	var dlg = $('#bb3-favorites');
	var list = dlg ? $('[data-bb3-fav-list]', dlg) : null;
	var trigger = null;
	var el = function (tag, cls, text) {
		var n = document.createElement(tag);
		if (cls) n.className = cls;
		if (text) n.textContent = text;
		return n;
	};
	var render = function () {
		list.textContent = '';
		if (!favs.length) { list.appendChild(el('p', 'bb3-fav__empty', t.empty)); return; }
		var ul = el('ul');
		favs.forEach(function (f) {
			var li = el('li', 'bb3-fav__item' + (f.gone ? ' is-gone' : ''));
			var body = el(f.gone ? 'div' : 'a');
			if (!f.gone) body.href = f.url;
			if (f.gone) body.style.cssText = 'display:flex;align-items:center;gap:14px;flex:1';
			if (f.image) {
				var img = el('img');
				img.src = f.image; img.alt = ''; img.width = 64; img.height = 84; img.loading = 'lazy';
				body.appendChild(img);
			} else { body.appendChild(el('span', 'bb3-fav__ph')); }
			var text = el('span');
			text.appendChild(el('strong', '', f.title || ''));
			text.appendChild(el('small', '', f.gone ? t.unavailable : (f.price || '')));
			body.appendChild(text);
			li.appendChild(body);
			var rm = el('button', 'bb3-fav__remove', t.remove);
			rm.type = 'button';
			rm.addEventListener('click', function () {
				favs = favs.filter(function (x) { return String(x.id) !== String(f.id); });
				store(favs); sync(); render();
				var next = $('button', list) || $('[data-bb3-fav-close]', dlg);
				if (next) next.focus();
			});
			li.appendChild(rm);
			ul.appendChild(li);
		});
		list.appendChild(ul);
	};
	var refresh = function () {
		if (!favs.length || !cfg.rest || !window.fetch) return;
		var url = new URL(cfg.rest, window.location.href);
		url.searchParams.set('ids', favs.map(function (f) { return f.id; }).join(','));
		fetch(url.toString(), { credentials: 'same-origin' })
			.then(function (r) { if (!r.ok) throw new Error(r.status); return r.json(); })
			.then(function (data) {
				var map = {};
				data.forEach(function (d) { map[d.id] = d; });
				favs = favs.map(function (f) {
					var d = map[f.id];
					return d ? { id: d.id, title: d.title, url: d.url, image: d.image, price: d.price } : { id: f.id, title: f.title, image: f.image, gone: true };
				});
				store(favs); sync();
				if (dlg.open) render();
			})
			.catch(function () { /* keep the saved snapshot */ });
	};
	if (dlg) {
		document.addEventListener('click', function (e) {
			var open = e.target.closest('[data-bb3-fav-open]');
			if (open) {
				trigger = drawer && !drawer.hidden ? drawerBtn : open;
				closeDrawer(false);
				render();
				if (typeof dlg.showModal === 'function') dlg.showModal(); else dlg.setAttribute('open', '');
				refresh();
			}
			if (e.target.closest('[data-bb3-fav-close]')) dlg.close();
		});
		dlg.addEventListener('click', function (e) { if (e.target === dlg) dlg.close(); });
		dlg.addEventListener('close', function () { if (trigger) trigger.focus(); });
	}

	/* ── Product gallery + lightbox ────────────────────────────────── */
	function initGallery(root) {
		each('[data-bb3-gallery]', root, function (gallery) {
			var gTrack = $('[data-bb3-gallery-track]', gallery);
			if (!gTrack) return;
			var gSlides = $$('li', gTrack);
			var thumbs = $$('.bb3-pgallery__thumbs button', gallery);
			var counter = $('[data-bb3-gallery-current]', gallery);
			var gi = 0;
			var setCurrent = function (n) {
				gi = n;
				thumbs.forEach(function (b, i) {
					if (i === n) b.setAttribute('aria-current', 'true'); else b.removeAttribute('aria-current');
				});
				if (counter) counter.textContent = String(n + 1);
			};
			var show = function (n, smooth) {
				n = (n + gSlides.length) % gSlides.length;
				gTrack.scrollTo({ left: gSlides[n].offsetLeft, behavior: smooth && !reduce ? 'smooth' : 'auto' });
				setCurrent(n);
			};
			var timer = null;
			gTrack.addEventListener('scroll', function () {
				clearTimeout(timer);
				timer = setTimeout(function () {
					var n = Math.round(gTrack.scrollLeft / gTrack.clientWidth);
					if (n !== gi) setCurrent(n);
				}, 80);
			}, { passive: true });
			thumbs.forEach(function (b, i) { b.addEventListener('click', function () { show(i, true); }); });
			$$('.bb3-pgallery__nav', gallery).forEach(function (b) {
				b.addEventListener('click', function () { show(gi + Number(b.dataset.dir), true); });
			});

			var lb = $('[data-bb3-lightbox]');
			if (!lb || typeof lb.showModal !== 'function') return;
			var lbImg = $('[data-bb3-lightbox-img]', lb);
			var lbCap = $('[data-bb3-lightbox-caption]', lb);
			var zooms = $$('.bb3-pgallery__zoom', gallery);
			var li = 0;
			var paint = function (n) {
				li = (n + zooms.length) % zooms.length;
				lbImg.src = zooms[li].dataset.full;
				lbImg.alt = zooms[li].dataset.alt || '';
				lbCap.textContent = (t.image || '%1$s / %2$s').replace('%1$s', li + 1).replace('%2$s', zooms.length);
			};
			zooms.forEach(function (z, i) {
				z.addEventListener('click', function () { paint(i); lb.showModal(); });
			});
			$('[data-bb3-lightbox-close]', lb).addEventListener('click', function () { lb.close(); });
			$$('.bb3-lightbox__nav', lb).forEach(function (b) {
				b.addEventListener('click', function () { paint(li + Number(b.dataset.dir)); });
			});
			lb.addEventListener('keydown', function (e) {
				if (e.key === 'ArrowLeft') paint(li - 1);
				if (e.key === 'ArrowRight') paint(li + 1);
			});
			lb.addEventListener('click', function (e) { if (e.target === lb) lb.close(); });
			lb.addEventListener('close', function () { show(li, false); zooms[li].focus(); });
		});
	}

	/* ── Promotion countdown ───────────────────────────────────────── */
	function initCountdown(root) {
		each('[data-bb3-countdown]', root, function (box) {
			var end = Date.parse(box.dataset.bb3Countdown);
			if (!end) return;
			var pad = function (n) { return (n < 10 ? '0' : '') + n; };
			var id = null;
			var tick = function () {
				var s = Math.max(0, Math.floor((end - Date.now()) / 1000));
				if (!s) { box.hidden = true; clearInterval(id); return; }
				box.textContent = Math.floor(s / 86400) + ' ' + t.days + ' ' + pad(Math.floor(s % 86400 / 3600)) + ':' + pad(Math.floor(s % 3600 / 60)) + ':' + pad(s % 60);
			};
			id = setInterval(tick, 1000);
			tick();
		});
	}

	/* ── Catalogue: sort submits itself, category panel on phones ──── */
	$$('[data-bb3-autosubmit]').forEach(function (s) {
		s.addEventListener('change', function () { s.form.submit(); });
	});
	var filterBtn = $('[data-bb3-filter-toggle]');
	var side = $('#bb3-catalog-filters');
	if (filterBtn && side) {
		filterBtn.addEventListener('click', function () {
			var open = !side.classList.contains('is-open');
			side.classList.toggle('is-open', open);
			filterBtn.setAttribute('aria-expanded', String(open));
		});
	}

	/* Public hook so the Customizer preview can re-run components on new markup. */
	window.bb3Init = function (root) {
		root = root || document;
		initAnnounce(root);
		initHero(root);
		initTabs(root);
		initCountdown(root);
		initGallery(root);
		initMarquee(root);
		sync();
	};

	initAnnounce(document);
	initHero(document);
	initTabs(document);
	initCountdown(document);
	initGallery(document);
	sync();
	// The moving row starts after load so it never competes with first paint.
	if (document.readyState === 'complete') initMarquee(document);
	else window.addEventListener('load', function () { requestAnimationFrame(function () { initMarquee(document); }); });
})();
