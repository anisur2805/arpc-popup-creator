(function ($) {
	var exitModals = [];
	var popupsDisplayed = false;
	var delayPopups = [];

	$(document).ready(function () {
		PlainModal.closeByEscKey = false;
		PlainModal.closeByOverlay = false;

		var modalsElms = document.querySelectorAll(".popup-creator");

		for (var i = 0; i < modalsElms.length; i++) {
			var modalContent = modalsElms[i];
			var modal = new PlainModal(modalContent);
			modal.closeButton = modalContent.querySelector(".close-button");
			var delay = modalsElms[i].getAttribute("data-delay");

			if (modalsElms[i].getAttribute("data-exit") == "1") {
				if (delay > 0) {
					delayPopups.push({ modal: modal, delay: delay });
				} else {
					modal.open();
				}
			} else {
				exitModals.push(modal);
			}
		}

		for (i in delayPopups) {
			setTimeout(function (i) {
				delayPopups[i].modal.open();
			}, delayPopups[i].delay, i);
		}
		
	});

	if (exitModals > 0) {
		window.onbeforeunload = function () {
			if (!popupsDisplayed) {
				for (var i in exitModals) {
					exitModals[i].open();
				}
				popupsDisplayed = true;
				return "true";
			}
		};
	}
})(jQuery);
