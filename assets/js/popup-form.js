;(function ($) {
    $(document).ready(function () {
        var allInputs = document.querySelectorAll(
            "#arpc-popup-creator-wrapper form .regular-text"
        )
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
            e.preventDefault()
            var self = $(this),
                data = self.serialize()

            $.post(arpcModalForm.ajaxUrl, data, function (response) {
                console.log( 'abc' )
                if (response.success) {
                    console.log( 'efg' )
                    self.find("p.hide").addClass("success")
                    self[0].reset()
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
