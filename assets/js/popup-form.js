; (function ($) {

	$(document).ready(function () {
		$('.arpc_submit').prop('disabled',true);

		$("#arpc-popup-creator-wrapper form").on("submit", function (e) {
			var $this = $(this);
			e.preventDefault();

			var values = $(this).serialize();

			if (values) {
				$('.arpc_submit').prop('disabled',false);
				console.log("have some val")
				console.log($('.arpc_submit'));

				const data = {
					action: 'arpc_modal_form',
					status: 'enabled',
					nonce: arpcModalForm.nonce,
					modalEntries: values
				}

				$.post(arpcModalForm.ajaxUrl, data, function (response) {
					if (response) {
						// console.log(arpcModalForm.success);
						if(response.success){
							$this.find('p.hide').removeClass('hide');
							console.log('hi');
							$this[0].reset();
						}
						console.log( data.status );
					}
				}).fail(function () {
					console.log(arpcModalForm.error);
				}).always(function () {
					console.log('form submitted')
				});
			} else {
				$('.arpc_submit').prop('disabled',true);
			}
			
		});
	});
})(jQuery);
