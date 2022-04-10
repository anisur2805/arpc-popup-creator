(function ($) {
	var auto_hide_input = document.querySelector(".auto_hide_gp input");
	var auto_hide_in_gp = document.querySelector(".auto_hide_in_gp");
	auto_hide_in_gp.style.display = 'none';

	auto_hide_input.addEventListener("change", function () {
		if (this.checked) {
			auto_hide_in_gp.style.display = 'flex';
			console.log("Hiddne");
		} else {
			auto_hide_in_gp.style.display = 'none';
		}
	});

	$(".arpc_add_contact_wrap form").on("submit", function (e) {
		e.preventDefault();
		var data = $(this).serialize();

		$.post(arpcPopup.ajaxUrl, data, function (data) {}).fail(function () {
			alert(arpcPopup.error);
		});
	});
})(jQuery);
