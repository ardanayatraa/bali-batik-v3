/* Bali Batik V3 — admin media pickers for product gallery, share image and category cover. */
jQuery(function ($) {
	'use strict';
	var t = window.bb3Admin || {};

	$('[data-bb3-gallery]').each(function () {
		var $box = $(this);
		var $input = $box.find('input[name="bb3_gallery"]');
		var $list = $box.find('.bb3-gallery-list');
		var frame;
		var save = function () {
			$input.val($list.children('li').map(function () { return $(this).data('id'); }).get().join(','));
		};
		$list.sortable({ update: save });
		$list.on('click', '.bb3-gallery-remove', function () { $(this).closest('li').remove(); save(); });
		$box.on('click', '.bb3-gallery-add', function (e) {
			e.preventDefault();
			if (!frame) {
				frame = wp.media({ title: t.galleryTitle, button: { text: t.useButton }, library: { type: 'image' }, multiple: 'add' });
				frame.on('open', function () {
					var selection = frame.state().get('selection');
					($input.val() || '').split(',').filter(Boolean).forEach(function (id) {
						var a = wp.media.attachment(id); a.fetch(); selection.add(a);
					});
				});
				frame.on('select', function () {
					$list.empty();
					frame.state().get('selection').toJSON().slice(0, 10).forEach(function (a) {
						var src = (a.sizes && a.sizes.thumbnail ? a.sizes.thumbnail.url : a.url);
						var $li = $('<li/>').attr('data-id', a.id).data('id', a.id);
						$li.append($('<img/>').attr({ src: src, alt: '' }));
						$li.append($('<button type="button" class="bb3-gallery-remove">×</button>').attr('aria-label', t.remove));
						$list.append($li);
					});
					save();
				});
			}
			frame.open();
		});
	});

	$(document).on('click', '[data-bb3-image] .bb3-image-pick', function (e) {
		e.preventDefault();
		var $box = $(this).closest('[data-bb3-image]');
		var frame = wp.media({ title: t.imageTitle, button: { text: t.useButton }, library: { type: 'image' }, multiple: false });
		frame.on('select', function () {
			var a = frame.state().get('selection').first().toJSON();
			var src = (a.sizes && a.sizes.thumbnail ? a.sizes.thumbnail.url : a.url);
			$box.find('input[type="hidden"]').val(a.id);
			$box.find('.bb3-image-preview').html($('<img/>').attr({ src: src, alt: '' }));
		});
		frame.open();
	});

	$(document).on('click', '[data-bb3-image] .bb3-image-clear', function (e) {
		e.preventDefault();
		var $box = $(this).closest('[data-bb3-image]');
		$box.find('input[type="hidden"]').val('');
		$box.find('.bb3-image-preview').empty();
	});
});
