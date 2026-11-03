/* Bali Batik V3 — block editor UI. Text is typed directly on the canvas (RichText);
   the front end is rendered by PHP, so blocks never need "recovery". No build step. */
(function (wp) {
	'use strict';
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var registerBlockType = wp.blocks.registerBlockType;
	var be = wp.blockEditor;
	var RichText = be.RichText;
	var InspectorControls = be.InspectorControls;
	var BlockControls = be.BlockControls;
	var MediaUpload = be.MediaUpload;
	var MediaUploadCheck = be.MediaUploadCheck;
	var useBlockProps = be.useBlockProps;
	var c = wp.components;
	var ServerSideRender = wp.serverSideRender;
	var __ = wp.i18n.__;
	var data = window.bb3Blocks || { assets: '', sections: {}, categories: {}, customize: '' };

	var icon = function (d) {
		return el('svg', { viewBox: '0 0 24 24', width: 24, height: 24, fill: 'none', stroke: 'currentColor', strokeWidth: 1.75, strokeLinecap: 'round', strokeLinejoin: 'round' }, el('path', { d: d }));
	};
	var options = function (obj) {
		return Object.keys(obj).map(function (k) { return { value: k, label: obj[k] }; });
	};
	var imageSrc = function (a) {
		if (a.imageUrl) return a.imageUrl;
		return a.asset ? data.assets + a.asset : '';
	};

	/* Shared image picker (sidebar + toolbar). */
	var imagePanel = function (props, title) {
		var a = props.attributes;
		return el(c.PanelBody, { title: title, initialOpen: true },
			el(MediaUploadCheck, {},
				el(MediaUpload, {
					onSelect: function (m) { props.setAttributes({ imageId: m.id, imageUrl: (m.sizes && m.sizes.large ? m.sizes.large.url : m.url) }); },
					allowedTypes: ['image'],
					value: a.imageId,
					render: function (o) {
						return el('div', {},
							imageSrc(a) ? el('img', { src: imageSrc(a), alt: '', style: { width: '100%', height: 'auto', marginBottom: '8px', borderRadius: '4px' } }) : null,
							el(c.Button, { variant: 'secondary', onClick: o.open }, a.imageId || a.imageUrl ? __('Replace photo', 'bali-batik-v3') : __('Choose photo', 'bali-batik-v3'))
						);
					}
				})
			),
			a.imageId || a.imageUrl ? el(c.Button, { variant: 'link', isDestructive: true, onClick: function () { props.setAttributes({ imageId: 0, imageUrl: '' }); } }, __('Use default photo', 'bali-batik-v3')) : null
		);
	};
	var toolbarImage = function (props) {
		return el(BlockControls, {},
			el(MediaUploadCheck, {},
				el(MediaUpload, {
					onSelect: function (m) { props.setAttributes({ imageId: m.id, imageUrl: (m.sizes && m.sizes.large ? m.sizes.large.url : m.url) }); },
					allowedTypes: ['image'],
					value: props.attributes.imageId,
					render: function (o) {
						return el(c.ToolbarGroup, {}, el(c.ToolbarButton, { icon: 'format-image', label: __('Replace photo', 'bali-batik-v3'), onClick: o.open }));
					}
				})
			)
		);
	};
	var text = function (props, key, tag, className, placeholder) {
		return el(RichText, {
			tagName: tag,
			className: className,
			value: props.attributes[key],
			allowedFormats: ['core/bold', 'core/italic'],
			placeholder: placeholder,
			onChange: function (v) { var o = {}; o[key] = v; props.setAttributes(o); }
		});
	};

	/* ── Hero banner ───────────────────────────────────────────────── */
	registerBlockType('bali-batik/hero', {
		apiVersion: 3,
		title: __('Hero banner', 'bali-batik-v3'),
		description: __('Full-width photo with a serif title and the main button. Type directly on it.', 'bali-batik-v3'),
		category: 'bali-batik',
		icon: icon('M3 5h18v14H3zM7 14h6M7 10h9'),
		supports: { html: false, align: false },
		edit: function (props) {
			var a = props.attributes;
			var Tag = a.level === 'h2' ? 'h2' : 'h1';
			return el(Fragment, {},
				toolbarImage(props),
				el(InspectorControls, {},
					imagePanel(props, __('Background photo', 'bali-batik-v3')),
					el(c.PanelBody, { title: __('Settings', 'bali-batik-v3') },
						el(c.TextControl, { label: __('Button link', 'bali-batik-v3'), help: __('e.g. /koleksi/ or a full https:// address', 'bali-batik-v3'), value: a.buttonUrl, onChange: function (v) { props.setAttributes({ buttonUrl: v }); } }),
						el(c.SelectControl, { label: __('Photo focus on phones', 'bali-batik-v3'), value: a.focus, options: [
							{ value: '50% 50%', label: __('Center', 'bali-batik-v3') }, { value: '30% 50%', label: __('Left', 'bali-batik-v3') },
							{ value: '70% 50%', label: __('Right', 'bali-batik-v3') }, { value: '50% 30%', label: __('Top', 'bali-batik-v3') }, { value: '50% 70%', label: __('Bottom', 'bali-batik-v3') }
						], onChange: function (v) { props.setAttributes({ focus: v }); } }),
						el(c.SelectControl, { label: __('Title level', 'bali-batik-v3'), help: __('Use H1 only for the first hero on a page (good for SEO).', 'bali-batik-v3'), value: a.level, options: [{ value: 'h1', label: 'H1' }, { value: 'h2', label: 'H2' }], onChange: function (v) { props.setAttributes({ level: v }); } })
					)
				),
				el('div', useBlockProps({ className: 'bb3-sec' }),
					el('section', { className: 'bb3-hero bb3-block-hero' },
						el('div', { className: 'bb3-hero__slide is-active' },
							el('div', { className: 'bb3-hero__bg', style: { '--bb3-focus': a.focus } }, imageSrc(a) ? el('img', { src: imageSrc(a), alt: '' }) : null),
							el('div', { className: 'bb3-hero__caption' },
								text(props, 'eyebrow', 'p', 'bb3-hero__eyebrow', __('Small line above the title…', 'bali-batik-v3')),
								text(props, 'title', Tag, 'bb3-hero__title', __('Hero title…', 'bali-batik-v3')),
								el('div', { style: { marginTop: '40px' } }, text(props, 'buttonText', 'span', 'bb3-btn bb3-btn--hero', __('Button text…', 'bali-batik-v3')))
							)
						)
					)
				)
			);
		},
		save: function () { return null; }
	});

	/* ── Promo banner ──────────────────────────────────────────────── */
	registerBlockType('bali-batik/banner', {
		apiVersion: 3,
		title: __('Promo banner', 'bali-batik-v3'),
		description: __('Photo card with a small line, title and button. Put three in Columns.', 'bali-batik-v3'),
		category: 'bali-batik',
		icon: icon('M4 4h16v16H4zM8 16h8M8 12h5'),
		supports: { html: false },
		edit: function (props) {
			var a = props.attributes;
			return el(Fragment, {},
				toolbarImage(props),
				el(InspectorControls, {},
					imagePanel(props, __('Photo', 'bali-batik-v3')),
					el(c.PanelBody, { title: __('Link', 'bali-batik-v3') },
						el(c.TextControl, { label: __('Banner link', 'bali-batik-v3'), help: __('e.g. /koleksi/kategori/fabrics/', 'bali-batik-v3'), value: a.url, onChange: function (v) { props.setAttributes({ url: v }); } })
					)
				),
				el('div', useBlockProps({ className: 'bb3-promo' }),
					imageSrc(a) ? el('img', { src: imageSrc(a), alt: '' }) : null,
					el('div', { className: 'bb3-promo__text' },
						text(props, 'eyebrow', 'span', 'bb3-promo__eyebrow', __('Small line…', 'bali-batik-v3')),
						text(props, 'title', 'span', 'bb3-promo__title', __('Banner title…', 'bali-batik-v3')),
						text(props, 'buttonText', 'span', 'bb3-btn bb3-btn--light', __('Button…', 'bali-batik-v3'))
					)
				)
			);
		},
		save: function () { return null; }
	});

	/* ── Product grid ──────────────────────────────────────────────── */
	registerBlockType('bali-batik/products', {
		apiVersion: 3,
		title: __('Product grid', 'bali-batik-v3'),
		description: __('Newest products, Our Picks or one category. Heading and intro are edited on the canvas.', 'bali-batik-v3'),
		category: 'bali-batik',
		icon: icon('M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z'),
		supports: { html: false },
		edit: function (props) {
			var a = props.attributes;
			return el(Fragment, {},
				el(InspectorControls, {},
					el(c.PanelBody, { title: __('Products', 'bali-batik-v3') },
						el(c.SelectControl, { label: __('Show', 'bali-batik-v3'), value: a.source, options: [
							{ value: 'latest', label: __('Newest products', 'bali-batik-v3') },
							{ value: 'featured', label: __('Our Picks (marked products)', 'bali-batik-v3') },
							{ value: 'category', label: __('One category', 'bali-batik-v3') },
							{ value: 'order', label: __('Manual order', 'bali-batik-v3') }
						], onChange: function (v) { props.setAttributes({ source: v }); } }),
						a.source === 'category' ? el(c.SelectControl, { label: __('Category', 'bali-batik-v3'), value: a.category, options: options(data.categories), onChange: function (v) { props.setAttributes({ category: v }); } }) : null,
						el(c.RangeControl, { label: __('Number of products', 'bali-batik-v3'), min: 1, max: 24, value: a.count, onChange: function (v) { props.setAttributes({ count: v }); } }),
						el(c.RangeControl, { label: __('Columns on desktop', 'bali-batik-v3'), min: 2, max: 5, value: a.columns, onChange: function (v) { props.setAttributes({ columns: v }); } }),
						el(c.TextControl, { label: __('“View all” link text (optional)', 'bali-batik-v3'), value: a.linkText, onChange: function (v) { props.setAttributes({ linkText: v }); } })
					)
				),
				el('div', useBlockProps({ className: 'bb3-sec' }),
					el('div', { className: 'bb3-section bb3-shell', style: { marginBlock: '40px' } },
						el('div', { className: 'bb3-heading' },
							el('div', {},
								text(props, 'heading', 'h2', '', __('Section heading…', 'bali-batik-v3')),
								text(props, 'intro', 'p', '', __('Short intro (optional)…', 'bali-batik-v3'))
							),
							a.linkText ? el('span', { className: 'bb3-text-link' }, a.linkText + ' →') : null
						),
						el(ServerSideRender, { block: 'bali-batik/products', attributes: { source: a.source, category: a.category, count: a.count, columns: a.columns, gridOnly: true } })
					)
				)
			);
		},
		save: function () { return null; }
	});

	/* ── Any homepage section ──────────────────────────────────────── */
	registerBlockType('bali-batik/section', {
		apiVersion: 3,
		title: __('Homepage section', 'bali-batik-v3'),
		description: __('Insert a Bali Batik section: moving categories, story banner, spotlight, services, gallery…', 'bali-batik-v3'),
		category: 'bali-batik',
		icon: icon('M3 6h18M3 12h18M3 18h18'),
		supports: { html: false },
		edit: function (props) {
			var a = props.attributes;
			return el(Fragment, {},
				el(InspectorControls, {},
					el(c.PanelBody, { title: __('Section', 'bali-batik-v3') },
						el(c.SelectControl, { label: __('Section', 'bali-batik-v3'), value: a.section, options: options(data.sections), onChange: function (v) { props.setAttributes({ section: v }); } }),
						el('p', {}, __('The texts and photos of this section are shared with the theme homepage and are edited in Customize → Bali Batik (with live preview).', 'bali-batik-v3')),
						data.customize ? el(c.ExternalLink, { href: data.customize }, __('Open Customize', 'bali-batik-v3')) : null
					)
				),
				el('div', useBlockProps({ className: 'bb3-sec' }),
					el(c.Disabled, {}, el(ServerSideRender, { block: 'bali-batik/section', attributes: { section: a.section } }))
				)
			);
		},
		save: function () { return null; }
	});
})(window.wp);
