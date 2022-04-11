<?php
namespace ARPC\Popup;
class Ajax {
      function __construct() {
            add_action('wp_ajax_arpc_add_contact', array( $this, 'add_contact' ) );
            add_action('wp_ajax_arpc_enquiry', array( $this, 'arpc_modal_form' ) );
      }
      
      public function arpc_modal_form() {
            if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'arpc_enquiry' ) ) {
                  
                  wp_send_json_error( [
                   'message' => 'Nonce verify failed!',
                  ] );
            }
            
            wp_send_json_success( [
                  'message' => 'submit done!',
            ] );
            
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