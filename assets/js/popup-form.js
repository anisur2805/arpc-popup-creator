;(function ($) {
    $(document).ready(function () {
        var allInputs = document.querySelectorAll(
            "#arpc-popup-creator-wrapper form .regular-text"
        )
        var submitBtn = document.querySelector(".arpc_submit")

        if (submitBtn) {
            submitBtn.disabled = true
        }

        allInputs.forEach((input, i) => {
            input.addEventListener("input", function (e) {
                if (e.target.value) {
                    submitBtn.disabled = false
                }
            })
        })

        $("#arpc-popup-creator-wrapper form").on("submit", function (e) {
            e.preventDefault()
            var self = $(this),
                data = self.serialize();

            $.post(arpcModalForm.ajaxUrl, data, function(response) {
                console.log( arpcModalForm.ajaxUrl, data, response )
                if( response.success ) {
                    console.log( "response ", response )
                    if ( response.success ) {
                        self.find( "p.hide" ).addClass( "success" );
                        self[0].reset();
                        console.log("self ", self[0])
                        console.log( "abcd" )
                    }
                }
            }).fail(function (e) {
                console.log(arpcModalForm.error, e)
            })
        })
    })
})(jQuery)
