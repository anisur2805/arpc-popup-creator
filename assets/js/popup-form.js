; (function ($) {

	$(document).ready(function () {
		$("#arpc-popup-creator-wrapper form").on("submit", function (e) {
			var $this = $(this);
			e.preventDefault();

			var values = $(this).serialize();

			if (values) {

				const data = {
					action: 'arpc_modal_form',
					status: 'enabled',
					nonce: arpcModalForm.nonce,
					modalEntries: values
				};

				$.post(arpcModalForm.ajaxUrl, data, function (response) {
					if (response) {
						if (response.success) {
							$this.find('p.hide').removeClass('hide');
							$this[0].reset();
						}
					}
				}).fail(function () {
					console.log(arpcModalForm.error);
				}).always(function () {
					console.log('form submitted');
				});
			}

		});
	});
})(jQuery);
