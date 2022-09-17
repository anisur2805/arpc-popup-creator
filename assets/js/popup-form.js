;(function ($) {
    $(document).ready(function () {
        var allInputs = document.querySelectorAll( "#arpc-popup-creator-wrapper form .regular-text" )
        var submitBtn = document.querySelector(".arpc_submit")

        // if (submitBtn) {
        //     submitBtn.disabled = true
        // }

        // allInputs.forEach((input, i) => {
        //     input.addEventListener("input", function (e) {
        //         if (e.target.value) {
        //             submitBtn.disabled = false
        //         }
        //     })
        // })

        $("#arpc-popup-creator-wrapper form").on("submit", function (e) {
            e.preventDefault();
            var $this = $(this)

            var values = $this.serialize()

            // if (values) {
                const data = {
                    action: "arpc_modal_form",
                    status: "enabled",
                    nonce: arpcModalForm.nonce,
                    modalEntries: values,
                    testData: 'testData'
                }

                $.post(arpcModalForm.ajaxUrl, data, function (response) {
                    if (response) {
                        if (response?.success) {
                            $this.find("p.hide").addClass("success")
                            $this[0].reset()
                        }
                    }
                })
                    .fail(function (e) {
                        console.log(arpcModalForm.error, e)
                    })
                    .always(function () {
                        console.log("form submitted")
                    })
            // }
        })
    })
})(jQuery)
