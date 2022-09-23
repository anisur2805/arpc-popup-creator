<?php

namespace ARPC\Popup;

class Ajax {
    public function __construct() {
        add_action( 'wp_ajax_arpc_modal_form_action', array( $this, 'arpc_modal_form' ) );
        add_action( 'wp_ajax_nopriv_arpc_modal_form_action', array( $this, 'arpc_modal_form' ) );
    }

    public function arpc_modal_form() {
        if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'arpc-modal-form' ) ) {

            wp_send_json_error( [
                'message' => 'Nonce verify failed!',
            ] );

        } else {

            $id      = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
            $arpc_name  = isset( $_POST['arpc-name'] ) ? sanitize_text_field( $_POST['arpc-name'] ) : '';
            $arpc_email = isset( $_POST['arpc-email'] ) ? sanitize_text_field( $_POST['arpc-email'] ) : '';
            
            // if ( empty( $arpc_name ) ) {
            //     $this->errors['arpc-name'] = __( "Please provide a name.", "arpc-popup-creator" );
            // }
            
            // if ( empty( $arpc_email ) ) {
            //     $this->errors['arpc-email'] = __( "Please provide email.", "arpc-popup-creator" );
            // }
            
            $args = [
                'name'    => $arpc_name,
                'email'   => $arpc_email,
            ];
            
            if ( $id ) {
                $args['id'] = $id;
            }
            
            arpc_insert_popup( $args );

            // var_dump($insert_id);
    
            // if ( is_wp_error( $insert_id ) ) {
            //     wp_die( $insert_id->get_error_message() );
            // }

            wp_send_json_success();
            // exit();

        }
    }
}
