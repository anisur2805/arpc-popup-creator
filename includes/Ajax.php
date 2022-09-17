<?php

namespace ARPC\Popup;

class Ajax {
    function __construct() {
        add_action('wp_ajax_arpc_modal_form', array($this, 'arpc_modal_form'));
        add_action('wp_ajax_nopriv_arpc_modal_form', array($this, 'arpc_modal_form'));
    }

    public function arpc_modal_form() {
        if (wp_verify_nonce($_REQUEST['_wpnonce'], 'arpc_modal_form')) {
            wp_send_json_success([
                'message' => 'Nonce verify successful!',
            ]);
        } else {
           
            wp_send_json_error([
                'message' => 'Nonce verify failed!',
            ]);
        }

        exit();
    }
}
