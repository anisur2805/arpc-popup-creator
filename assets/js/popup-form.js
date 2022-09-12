; (function ($) {
	$(document).ready(function () {
		var allInputs = document.querySelectorAll("#arpc-popup-creator-wrapper form .regular-text");
		var submitBtn = document.querySelector(".arpc_submit");
		
		submitBtn.disabled = true;

		allInputs.forEach((input, i) => {
			input.addEventListener('input', function (e) {
				if (e.target.value) {
					submitBtn.disabled = false;
				}
			});
		});

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
