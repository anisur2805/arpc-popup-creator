<?php

namespace ARPC\Popup;

class Ajax {
    public function __construct() {

        add_action( 'wp_ajax_arpc_modal_form_action', array( $this, 'arpc_modal_form' ) );
        add_action( 'wp_ajax_nopriv_arpc_modal_form_action', array( $this, 'arpc_modal_form' ) );
        add_action( 'wp_ajax_arpc-delete-subscriber', array( $this, 'arpc_delete_subscriber' ) );
        
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
            
            $args = [
                'name'    => $arpc_name,
                'email'   => $arpc_email,
            ];
            
            if ( $id ) {
                $args['id'] = $id;
            }
            
            arpc_insert_popup( $args );
            wp_send_json_success();

        }
    }

    public function arpc_delete_subscriber() {

        $id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;

        if( !current_user_can( 'manage_options' ) ) {
            return;
        }       

        if( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'admin-subscriber' ) ) {

            wp_send_json_error( __( 'No Cheating', 'arpc-popup-creator' ) );

        } else {

            arpc_delete_subscriber( $id );
            wp_send_json_success( __('Deleted %s', $id ) );

        }

    }
}
