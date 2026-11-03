/* Bali Batik V3 — Customizer preview: instant design changes, re-init refreshed sections. */
(function (api, maps) {
	'use strict';
	maps = maps || {};
	var root = document.documentElement;
	var set = function (name, value) { root.style.setProperty(name, value); };

	var shade = function (hex, amount) {
		hex = String(hex || '').replace('#', '');
		if (hex.length === 3) hex = hex.replace(/(.)/g, '$1$1');
		if (hex.length !== 6) return hex;
		return '#' + [0, 2, 4].map(function (i) {
			var v = parseInt(hex.substr(i, 2), 16);
			v = amount < 0 ? v * (1 + amount) : v + (255 - v) * amount;
			return ('0' + Math.round(Math.max(0, Math.min(255, v))).toString(16)).slice(-2);
		}).join('');
	};

	var bind = function (id, fn) { api('bb3_' + id, function (setting) { setting.bind(fn); }); };

	bind('color_accent', function (v) { set('--bb3-green', v); set('--bb3-green-dark', shade(v, -0.2)); });
	bind('color_dark', function (v) { set('--bb3-dark', v); });
	bind('color_bar', function (v) { set('--bb3-bar', v); });
	bind('color_ticker', function (v) { set('--bb3-teal', v); });
	bind('color_soft', function (v) { set('--bb3-soft', v); });
	bind('radius', function (v) { set('--bb3-radius', parseInt(v, 10) + 'px'); });
	bind('hero_shade', function (v) { set('--bb3-shade', String(parseInt(v, 10) / 100)); });
	bind('catalog_cols', function (v) { set('--bb3-catalog-cols', v); });
	bind('container', function (v) { set('--bb3-container', parseInt(v, 10) + 'px'); });
	bind('hero_font', function (v) { if (maps.hero_font && maps.hero_font[v]) set('--bb3-serif', maps.hero_font[v]); });
	bind('heading_font', function (v) { if (maps.heading_font && maps.heading_font[v]) set('--bb3-heading-font', maps.heading_font[v]); });
	bind('button_shape', function (v) { if (maps.button_shape && maps.button_shape[v]) set('--bb3-hero-btn-radius', maps.button_shape[v]); });
	bind('card_ratio', function (v) { if (maps.card_ratio && maps.card_ratio[v]) set('--bb3-card-ratio', maps.card_ratio[v]); });
	bind('hero_height', function (v) {
		if (!maps.hero_height || !maps.hero_height[v]) return;
		set('--bb3-hero-h', maps.hero_height[v][0]);
		set('--bb3-hero-h-m', maps.hero_height[v][1]);
	});

	api.bind('preview-ready', function () {
		if (!api.selectiveRefresh) return;
		api.selectiveRefresh.bind('partial-content-rendered', function (placement) {
			var el = placement && placement.container && placement.container[0];
			if (el && window.bb3Init) window.bb3Init(el);
		});
	});
})(wp.customize, window.bb3Design);
