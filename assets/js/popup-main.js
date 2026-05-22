;(function ($) {
    var exitModals = []
    var popupDisplayed = false
    var delayPopups = []

    $(document).ready(function () {
        var modalsElms = document.querySelectorAll(".arpc-popup-creator")
        console.log("ARPC: Found " + modalsElms.length + " popup elements")
        console.log("ARPC: Cookies = " + document.cookie)
        PlainModal.closeByEscKey = false
        PlainModal.closeByOverlay = false

        for (var i = 0; i < modalsElms.length; i++) {
            var modalContent = modalsElms[i]
            var modal = new PlainModal(modalContent)
            modal.closeButton = modalContent.querySelector(".arpc-close-button")
            var displayPage = modalsElms[i].getAttribute("data-page")
            var delay = modalsElms[i].getAttribute("data-delay")
            var isExit = modalsElms[i].getAttribute("data-exit")
            console.log("ARPC: Popup " + i + " - isExit=" + isExit + ", delay=" + delay + ", page=" + displayPage)

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
                console.log("ARPC: Added to exitModals (shows on page exit)")
            }
        }

        console.log("ARPC: Total delayPopups=" + delayPopups.length + ", exitModals=" + exitModals.length)

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
            console.log("ARPC: checkCookie_loadModal called for page=" + currentPage)
            var hasCookie = document.cookie
                    .split("; ")
                    .find((row) => row.startsWith(`${currentPage}`))
            console.log("ARPC: hasCookie=" + hasCookie)
            if (!hasCookie) {
                console.log("ARPC: Opening popup now!")
                loadModal.open()
                loadModal.overlayBlur = 3
                arpcCookie(displayPage, "once")
            } else {
                console.log("ARPC: Popup BLOCKED by cookie - clear cookies to see popup again")
            }
        }

        // create cookie
        function arpcCookie(cName, cVal) {
            document.cookie =
                cName + "=" + escape(cVal) + "; path=" + displayPage
        }
    })
})(jQuery)
