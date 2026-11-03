/* Bali Batik V3 — Customizer: sortable homepage sections. */
(function ($, api) {
	'use strict';

	api.controlConstructor['bb3-sections'] = api.Control.extend({
		ready: function () {
			var control = this;
			var $list = control.container.find('.bb3-sections-list');
			var save = function () {
				var value = $list.children('li').map(function () {
					var $li = $(this);
					return ($li.find('input[type="checkbox"]').is(':checked') ? '' : '-') + $li.data('key');
				}).get().join(',');
				control.setting.set(value);
			};
			$list.sortable({ handle: '.bb3-sec-handle', axis: 'y', update: save });
			$list.on('change', 'input[type="checkbox"]', save);
			$list.on('click', '.bb3-sec-up, .bb3-sec-down', function () {
				var $btn = $(this);
				var $li = $btn.closest('li');
				if ($btn.hasClass('bb3-sec-up')) $li.prev().before($li); else $li.next().after($li);
				save();
				$btn.trigger('focus');
			});
		}
	});
})(jQuery, wp.customize);
