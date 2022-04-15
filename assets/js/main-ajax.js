; (function ($) {
	$(document).ready(function () {
		$(".arpc_settings__form").on("submit", function (e) {
			e.preventDefault();

			var values = $(this).serialize();

			if (values) {

				const data = {
					action: 'arpc_add_contact',
					status: 'enabled',
					nonce: arpcPopup.nonce,
					popup_settings_data : values
				}

				$.post(arpcPopup.ajaxUrl, data, function (response) {
					if (response) {
						console.log(arpcPopup.success);
					}
				}).fail(function () {
					console.error(arpcPopup.error);
				})
					.always(() => console.log('form submitted'))
			}
		});
	});

})(jQuery);
