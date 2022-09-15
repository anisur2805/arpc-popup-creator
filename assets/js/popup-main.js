; (function ($) {
	var exitModals = [];
	var popupDisplayed = false;
	var delayPopups = [];

	$(document).ready(function () {
		PlainModal.closeByEscKey = false;
		PlainModal.closeByOverlay = false;
		var modalsElms = document.querySelectorAll(".arpc-popup-creator");
		var getShowOnceRaw = localStorage.getItem("showOnce");
		var getShowOnce = JSON.parse(getShowOnceRaw);

		var siteName = window.location.hostname;
		var currentPath = siteName.hostname;

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
					modal.open();
					modal.overlayBlur = 3;
				}
			} else {
				exitModals.push(modal);
			}
		}

		for (i in delayPopups) {
			if (!getShowOnce) {
				setTimeout(
					function (i) {
						delayPopups[i].modal.open();
					},
					delayPopups[i].delay,
					i
				);

			}
		}

		modal.closeButton.addEventListener('click', function () {
			localStorage.setItem('showOnce', true);
			arpcCookie(displayPage + "-ck", '1', 60);
		});

		function arpcCookie(cName, cVal, cExpiryInSeconds) {
			var expiry = new Date();
			expiry.setTime(expiry.getTime() + 1000 * cExpiryInSeconds);
			document.cookie = cName + "=" + escape(cVal) + ";expires=" + expiry.toGMTString() + "; path=" + currentPath;
		}

		if (exitModals.length > 0) {
			window.onbeforeunload = function (event) {
				if (!popupDisplayed) {
					for (var i in exitModals) {
						exitModals[i].open();
					}
					popupDisplayed = true;
				}
				event.preventDefault();
				return event.returnValue = "random";

			};
		}
	});
})(jQuery);
