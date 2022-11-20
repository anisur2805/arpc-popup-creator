;(function ($) {
    var exitModals = []
    var popupDisplayed = false
    var delayPopups = []

    $(document).ready(function () {
        var modalsElms = document.querySelectorAll(".arpc-popup-creator")
        PlainModal.closeByEscKey = false
        PlainModal.closeByOverlay = false

        for (var i = 0; i < modalsElms.length; i++) {
            var modalContent = modalsElms[i]
            var modal = new PlainModal(modalContent)
            modal.closeButton = modalContent.querySelector(".arpc-close-button")
            var displayPage = modalsElms[i].getAttribute("data-page")
            var delay = modalsElms[i].getAttribute("data-delay")
            var isExit = modalsElms[i].getAttribute("data-exit")

            if ("1" === isExit) {
                if (delay) {
                    delayPopups.push({
                        modal: modal,
                        delay: delay,
                        displayPage: displayPage,
                    })
                } else {
                    checkCookie_loadModal(displayPage, modal)
                }
            } else {
                const abc = "abc"
                exitModals.push(modal)
            }
        }

        for (const i in delayPopups) {
            setTimeout(
                function (i) {
                    checkCookie_loadModal(displayPage, delayPopups[i].modal)
                },
                delayPopups[i].delay,
                i
            )
        }

        if (exitModals.length > 0) {
            window.addEventListener("beforeunload", (e) => {
                for (const i in exitModals) {
                    checkCookie_loadModal(displayPage, exitModals[i])
                }

                const confirmationMessage = "\\o/"
                // Gecko + IE
                ;(e || window.event).returnValue = confirmationMessage

                // Safari, Chrome, and other WebKit-derived browsers
                return confirmationMessage
            })
        }

        // check current page cookie
        function checkCookie_loadModal(currentPage, loadModal) {
            if (
                !document.cookie
                    .split("; ")
                    .find((row) => row.startsWith(`${currentPage}`))
            ) {
                loadModal.open()
                loadModal.overlayBlur = 3
                arpcCookie(displayPage, "once")
            }
        }

        // create cookie
        function arpcCookie(cName, cVal) {
            document.cookie =
                cName + "=" + escape(cVal) + "; path=" + displayPage
        }
    })
})(jQuery)
