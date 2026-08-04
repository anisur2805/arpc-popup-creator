; ( function ( $ ) {
    $( document ).ready( function () {

        // Bind every form independently so multiple popups or shortcodes
        // on the same page cannot interfere with each other.
        $( ".arpc-popup-creator-wrapper .arpc-subscribe-form" ).each( function () {

            var $form     = $( this ),
                $submit   = $form.find( ".arpc_submit" ),
                $inputs   = $form.find( ".regular-text" ),
                $response = $form.find( ".arpc-response" );

            function showResponse( message, state ) {
                $response
                    .text( message )
                    .removeClass( "hide success error" )
                    .addClass( state );
            }

            if ( $submit.length ) {
                $submit.prop( "disabled", true );
            }

            // Enable this form's submit button once any of its own inputs is filled.
            $inputs.on( "input", function () {
                var filled = false;

                $inputs.each( function () {
                    if ( $( this ).val() ) {
                        filled = true;
                    }
                } );

                $submit.prop( "disabled", ! filled );
            } );

            $form.on( "submit", function ( e ) {
                e.preventDefault();

                $submit.prop( "disabled", true );

                $.post( arpcModalForm.ajaxUrl, $form.serialize(), function ( response ) {

                    if ( response.success ) {
                        showResponse( arpcModalForm.success, "success" );
                        $form[0].reset();
                        return;
                    }

                    showResponse(
                        ( response.data && response.data.message ) ? response.data.message : arpcModalForm.error,
                        "error"
                    );
                    $submit.prop( "disabled", false );

                } ).fail( function () {
                    showResponse( arpcModalForm.error, "error" );
                    $submit.prop( "disabled", false );
                } );
            } );

        } );

    } );
} )( jQuery );
