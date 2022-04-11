<?php
namespace ARPC\Popup;

use ARPC\Popup\Traits\Form_Error;

class Ajax {
      use Form_Error;
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
            
            // wp_send_json_success( [
            //       'message' => 'submit done!',
            // ] );
            
            var_dump($_POST['arpc_submit']);
            if ( !isset( $_POST['arpc_submit'] ) ) {
                  return;
              }
      
              if ( !current_user_can( 'manage_options' ) ) {
                  wp_die( 'Are you cheating!' );
              }
      
              $id      = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
              $name    = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : "";
              $email = isset( $_POST['email'] ) ? sanitize_textarea_field( $_POST['email'] ) : "";
      
              if ( empty( $name ) ) {
                  $this->errors['name'] = __( "Please provide a name.", "oop-academy" );
              }
      
              if ( empty( $email ) ) {
                  $this->errors['email'] = __( "Please provide a email number.", "oop-academy" );
              }
      
              if ( !empty( $this->errors ) ) {
                  return;
              }
      
              $args = [
                  "name" => $name,
                  "email" => $email,
              ];
      
              if ( $id ) {
                  $args['id'] = $id;
              }
      
              $insert_id = arpc_insert_popup( $args );
      
              if ( is_wp_error( $insert_id ) ) {
                  wp_die( $insert_id->get_error_message() );
              }
              
              echo $insert_id;
            //   if ( $id ) {
            //       $redirect_to = admin_url( "admin.php?page=oop-academy&action=edit&address-updated&id=" . $id );
            //   } else {
            //       $redirect_to = admin_url( "admin.php?page=oop-academy&inserted=true" );
            //   }
      
            //   wp_redirect( $redirect_to );
              exit();
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