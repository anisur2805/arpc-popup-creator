;(function ($) {

	$(".arpc-popup-creator-wrapper form").on("submit", function (e) {
		e.preventDefault();
		
		var data = $(this).serialize();

		$.post(arpcModalForm.ajaxUrl, data, function (data) {}).fail(function () {
			// alert(arpcModalForm.success);
		});
	});
})(jQuery);
