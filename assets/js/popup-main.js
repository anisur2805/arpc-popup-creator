;(function ($) {
	var exitModals = [];
	var popupDisplayed = false;
	var delayPopups = [];

	$(document).ready(function () {
		PlainModal.closeByEscKey = false;
		PlainModal.closeByOverlay = false;
		var modalsElms = document.querySelectorAll(".arpc-popup-creator");

		var siteName = window.location;
		var currentPath = siteName.pathname.replace(/\//g, '');
		currentPath = (currentPath.length === 0) ? 'home' : currentPath;

		for (var i = 0; i < modalsElms.length; i++) {
			var modalContent = modalsElms[i];
			var modal = new PlainModal(modalContent);
			modal.closeButton = modalContent.querySelector(".arpc-close-button");
			var displayPage = modalsElms[i].getAttribute("data-page");
			var delay = modalsElms[i].getAttribute("data-delay");
			var isExit = modalsElms[i].getAttribute("data-exit");

			if (isExit == 1) {
				if (delay > 0) {
					delayPopups.push({ modal: modal, delay: delay, displayPage: displayPage });
				} else {
					if (!document.cookie.split('; ').find((row) => row.startsWith(`${currentPath}`))) {
						modal.open();
						modal.overlayBlur = 3;
					}
				}
			} else {
				exitModals.push(modal);
			}
		}

		for (i in delayPopups) { 
				setTimeout(
					function (i) {
						if (!document.cookie.split('; ').find((row) => row.startsWith(`${currentPath}`))) {
							console.log( "currentPath ", currentPath )
							delayPopups[i].modal.open();
						}
					},
					delayPopups[i].delay,
					i
				);

			// }
		}


			modal?.closeButton.addEventListener('click', function () {
				arpcCookie(currentPath, 'once');
			}) 

		function arpcCookie(cName, cVal) {
			document.cookie = cName + "=" + escape(cVal) + "; path=" + currentPath;
		}

		if (exitModals.length > 0) {
			window.onbeforeunload = function (event) {
				if (!popupDisplayed) {
					for (var i in exitModals) {
						if (!document.cookie.split('; ').find((row) => row.startsWith(`${currentPath}`))) {
							exitModals[i].open();
						}
					}
					popupDisplayed = true;
				}
				event.preventDefault();
				return event.returnValue = "random";

			};
		}
	});
})(jQuery);
