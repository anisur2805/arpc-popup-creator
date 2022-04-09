;(function( $ ) {
     $('.arpc_add_contact_wrap form').on('submit', function( e ) {
           e.preventDefault();
           var data = $(this).serialize();
           
           $.post(arpcPopup.ajaxUrl, data, function( data ) {
                 
           })
           .fail(function(){
                 alert( arpcPopup.error )
           })
     })
})(jQuery);