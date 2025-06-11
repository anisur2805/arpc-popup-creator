; ( function ( $ ) {
    $( document ).ready( function () {

        var allInputs = document.querySelectorAll( "#arpc-popup-creator-wrapper form .regular-text" );
        var submitBtn = document.querySelector( ".arpc_submit" );

        if ( submitBtn ) {
            submitBtn.disabled = true;
        }

        // disable if input/s empty
        allInputs.forEach( ( input, i ) => {
            input.addEventListener( "input", function ( e ) {
                if ( e.target.value ) {
                    submitBtn.disabled = false;
                }
            } );
        } );

        jQuery("#arpc-popup-creator-wrapper form").on("submit", function (e) {
            e.preventDefault();

            var $form = jQuery(this);
            var name = $form.find("input[name='arpc-name']").val().trim();
            var email = $form.find("input[name='arpc-email']").val().trim();
            var $message = $form.find("p.hide");
        
            // Clear previous messages
            $message.removeClass("success error").text("");
        
            // Simple validation
            if (!name || !email) {
                $message
                    .addClass("error")
                    .text("Name and email are required.")
                    .show()
                    .css({
                        "opacity": 1,
                        "visibility": "visible"
                    })
                return;
            }
        
            // Submit if valid
            var data = $form.serialize();
        
            jQuery.ajax({
                url: arpcModalForm.ajaxUrl,
                method: "POST",
                data: data,
                success: function (response) {
                    if (response.success) {
                        $message
                            .removeClass("error")
                            .addClass("success")
                            .text("Thanks for subscribing.")
                            .show()
                            .css({
                                "opacity": 1,
                                "visibility": "visible"
                            });
                        $form[0].reset();
                    } else {
                        $message
                            .removeClass("success")
                            .addClass("error")
                            .text(response.data?.message || "Something went wrong.")
                            .show()
                            .css({
                                "opacity": 1,
                                "visibility": "visible"
                            });
                    }
                },
                error: function () {
                    $message
                        .removeClass("success")
                        .addClass("error")
                        .text("Submission failed. Please try again.")
                        .show()
                        .css({
                            "opacity": 1,
                            "visibility": "visible"
                        });
                }
            });
        });
    } );
} )( jQuery );
