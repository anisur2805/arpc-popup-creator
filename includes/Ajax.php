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
            $arpc_name  = isset( $_POST['arpc-name'] ) ? sanitize_text_field( $_POST['arpc-name'] ) : '';
            $arpc_email = isset( $_POST['arpc-email'] ) ? sanitize_text_field( $_POST['arpc-email'] ) : '';
            wp_send_json_success( [
                'name'    => $arpc_name,
                'email'   => $arpc_email,
                'message' => 'Nonce verify successful!',
            ] );
        }
        exit();
    }
}
