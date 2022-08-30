<?php

namespace ARPC\Popup;

class Ajax {
    function __construct() {
        // $this->arpc_dispatch();

        add_action('wp_ajax_arpc_add_contact', array($this, 'arpc_add_contact'));
        add_action('wp_ajax_nopriv_arpc_add_contact', array($this, 'arpc_add_contact'));
        add_action('wp_ajax_arpc_modal_form', array($this, 'arpc_modal_form'));
    }

    public function arpc_dispatch() {
        // add_action('admin_init', ( array( $this, 'add_address_form_handler' ) ) );
    }


    public function arpc_modal_form() {

        // if( ! isset( $_POST['arpc_submit' ] ) ) {
        //     return;
        // }

        // if (! check_ajax_referer('arpc_modal_form', 'nonce') ) {
            if( ! wp_verify_nonce( $_REQUEST['nonce'], 'arpc_modal_form' )) {
            wp_send_json_error([
                'message' => 'Nonce verify failed!',
            ]);
        } else {
            wp_send_json_success([
                'message' => 'Nonce verify successful!',
            ]);
        }

        // check_ajax_referer('arpc_modal_form', 'nonce');
        $newsletterValues = isset($_POST['modalEntries']) ? $_POST['modalEntries'] : '';
        // echo "<pre>";
        var_dump($newsletterValues);

        echo "Yeah";

        exit();

        // $to_admin_email = get_option( 'admin_email' );
        // $subject = "This is an testing message";
        // $message = "This is a body content of test message";
        // $headers = array( 'Content-Type: text/html; charset=UTF-8' );

        // exit();
    }

    public function arpc_add_contact() {
        check_ajax_referer('arpc-ajax-nonce', 'nonce');
        $firstName = isset($_POST['popup_settings_data']) ? $_POST['popup_settings_data'] : '';
        var_dump($firstName);
        die();
    }

    
}
