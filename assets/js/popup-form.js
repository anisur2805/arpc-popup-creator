;(function ($) {
    $(document).ready(function () {
        var allInputs = document.querySelectorAll(
            "#arpc-popup-creator-wrapper form .regular-text"
        )
        var submitBtn = document.querySelector(".arpc_submit")

        if (submitBtn) {
            submitBtn.setAttribute("disabled", "disabled")
        }

        console.log(submitBtn)

        allInputs.forEach((input, i) => {
            input.addEventListener("input", function (e) {
                if (e.target.value) {
                    submitBtn.setAttribute("disabled", "")
                }
            })
        })

        $("#arpc-popup-creator-wrapper form").on("submit", function (e) {
            var $this = $(this)
            e.preventDefault()

            var values = $(this).serialize()

            if (values) {
                const data = {
                    action: "arpc_modal_form",
                    status: "enabled",
                    nonce: arpcModalForm.nonce,
                    modalEntries: values,
                }

                $.post(arpcModalForm.ajaxUrl, data, function (response) {
                    if (response) {
                        if (response.success) {
                            $this.find("p.hide").addClass("success")
                            $this[0].reset()
                        }
                    }
                })
                    .fail(function () {
                        console.log(arpcModalForm.error)
                    })
                    .always(function () {
                        console.log("form submitted")
                    })
            }
        })
    })
})(jQuery)
