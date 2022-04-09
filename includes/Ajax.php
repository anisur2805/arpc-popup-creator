<?php
namespace ARPC\Popup;
class Ajax {
      function __construct() {
            add_action('wp_ajax_arpc_add_contact', array( $this, 'add_contact' ) );
      }
      
      public function add_contact() {
            if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'arpc_add_contact' ) ) {
                  wp_send_json_error( [
                   'message' => 'Nonce verify failed!',
                  ] );
                 }
                 wp_send_json_success( [
                  'message' => 'submit done!',
                 ] );
      }
      
}