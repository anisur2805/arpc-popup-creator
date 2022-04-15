; (function ($) {

	$(document).ready(function () {
		$("#arpc-popup-creator-wrapper form").on("submit", function (e) {
			e.preventDefault();

			var values = $(this).serialize();

			if (values) {

				const data = {
					action: 'arpc_modal_form',
					status: 'enabled',
					nonce: arpcModalForm.nonce,
					modalEntries: values
				}

				$.post(arpcModalForm.ajaxUrl, data, function (response) {
					if (response) {
						// console.log(arpcModalForm.success);
						console.log( data );
					}
				}).fail(function () {
					console.log(arpcModalForm.error);
				}).always(function () {
					console.log('form submitted')
				});
			}
		});
	});
})(jQuery);
