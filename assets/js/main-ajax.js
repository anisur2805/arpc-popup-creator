; (function ($) {

	$(".arpc_add_contact_wrap form").on("submit", function (e) {
		e.preventDefault();
		var data = $(this).serialize();

		$.post(arpcPopup.ajaxUrl, data, function (response) {
			if (response.success) {
				console.log(response.success);
			} else {
				alert(response.message.data);
			}
		}).fail(function () {
			alert(arpcPopup.error);
		});
	});

	var auto_hide_input = document.querySelector(".auto_hide_gp input");
	var auto_hide_in_gp = document.querySelector(".auto_hide_in_gp");
	auto_hide_in_gp.style.display = 'none';

	auto_hide_input.addEventListener("change", function () {
		if (this.checked) {
			auto_hide_in_gp.style.display = 'flex';
		} else {
			auto_hide_in_gp.style.display = 'none';
		}
	});

})(jQuery);
