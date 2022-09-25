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

        // submit data to PHP end
        $( "#arpc-popup-creator-wrapper form" ).on( "submit", function ( e ) {
            e.preventDefault();
            var self = $( this ),
                data = self.serialize();

            $.post( arpcModalForm.ajaxUrl, data, function ( response ) {

                if ( response.success ) {
                    if ( response.success ) {
                        self.find( "p.hide" ).addClass( "success" );
                        self[0].reset();
                    }
                }
                
            } ).fail( function ( e ) {
                console.log( arpcModalForm.error, e );
            } );
        } );
        
    } );
} )( jQuery );
