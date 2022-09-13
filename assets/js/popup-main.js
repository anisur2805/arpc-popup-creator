; (function ($) {
	var exitModals = [];
	var popupDisplayed = false;
	var delayPopups = [];

	$(document).ready(function () {
		PlainModal.closeByEscKey = false;
		PlainModal.closeByOverlay = false;
		var modalsElms = document.querySelectorAll(".arpc-popup-creator");
		var getShowOnce = JSON.parse(localStorage.getItem("showOnce"));

		for (var i = 0; i < modalsElms.length; i++) {
			var modalContent = modalsElms[i];
			var modal = new PlainModal(modalContent);
			modal.closeButton = modalContent.querySelector(".arpc-close-button");
			var displayPage = modalsElms[i].getAttribute("data-page");
			console.log( displayPage )
			
			var delay = modalsElms[i].getAttribute("data-delay");
			var isExit = modalsElms[i].getAttribute("data-exit");
			if (isExit == 1) {
				if (delay > 0) {
					delayPopups.push({ modal: modal, delay: delay });
				} else {
					modal.open();
					modal.overlayBlur = 3;
				}
			} else {
				exitModals.push(modal);
			}
		}

		for (i in delayPopups) {
			console.log("getShowOnce ", getShowOnce);
			if (!getShowOnce) {
				setTimeout(
					function (i) {
						delayPopups[i].modal.open();
					},
					delayPopups[i].delay,
					i
				);
				
			}

			modal.closeButton.addEventListener('click', function () {
				localStorage.setItem('showOnce', true);
			});

		}

		// if (exitModals.length > 0) {
		// 	window.onbeforeunload = function (event) {
		// 		if (!popupDisplayed) {
		// 			for (var i in exitModals) {
		// 				exitModals[i].open();
		// 			}
		// 			popupDisplayed = true;
		// 		}
		// 		event.preventDefault();
		// 		return event.returnValue = "random";

		// 	};
		// }


	});
})(jQuery);
