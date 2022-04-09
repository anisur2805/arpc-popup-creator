;(function ($) {
	var exitModals = [];
	var popupsDisplayed = false;
	var delayPopups = [];

	$(document).ready(function () {
		PlainModal.closeByEscKey = false;
		PlainModal.closeByOverlay = false;

		var modalsElms = document.querySelectorAll(".arpc-popup-creator");

		for (var i = 0; i < modalsElms.length; i++) {
			var modalContent = modalsElms[i];
			var modal = new PlainModal(modalContent);
			modal.closeButton = modalContent.querySelector(".arpc-close-button");

			var delay = modalsElms[i].getAttribute("data-delay");
			var isExit = modalsElms[i].getAttribute("data-exit");

			if (isExit == 1) {
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
			setTimeout(
				function (i) {
					delayPopups[i].modal.open();
				},
				delayPopups[i].delay,
				i
			);
		}
		
		if (exitModals.length > 0) {
			window.onbeforeunload = function (event) {
				if (!popupsDisplayed) {
					for (var i in exitModals) {
						exitModals[i].open();
					}
					popupsDisplayed = true;
					return "random";
				}
			};
		}
	});
})(jQuery);
