; ( function ( $ ) {
    $( document ).ready( function () {

        // submit data to PHP end
        $( "table.wp-list-table.subscribers" ).on( "click", "a.arpc-subscriber-delete", function ( e ) {

            e.preventDefault();
            if( !confirm( arpcAdminSub.confirm ) ) return;
            var self = $( this ),
                id = self.data( 'id' );

            wp.ajax.send(
                "arpc-delete-subscriber", {
                data: {
                    _wpnonce: arpcAdminSub.nonce,
                    id: id
                }
            } )
            .done( function ( response ) {
                self.closest( 'tr' ).css( 'background-color', '#f00' )
                .hide( 400, function () {
                    $(this).remove();
                } )
            } )
            .fail( function ( e ) {
                console.log( arpcAdminSub.error );
            } );
        } )
    } );
} )( jQuery );