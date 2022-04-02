;(function( $ ) {
     $('.add_contact_wrap form').on('submit', function( e ) {
           e.preventDefault();
           var data = $(this).serialize();
           
           $.post(pucPopup.ajaxUrl, data, function( data ) {
                 
           })
           .fail(function(){
                 alert( pucPopup.error )
           })
     })
})(jQuery);