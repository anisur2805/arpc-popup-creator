<?php

namespace ARPC\Popup;

use ARPC\Popup\Admin\Menu;
use ARPC\Popup\Data_Table;
use ARPC\Popup\Metabox;
use ARPC\Popup\Post_Type;

class Admin {
    public $errors = [];

    public function __construct() {

        $this->dispatch_actions();

        // Instantiate Data Table
        new Data_Table();

        // Instantiate Subscriber Data Table
        // new Subscriber_Data_Table();

        //Instantiate Meta box
        new Metabox();

        // Add Menu page
        new Menu();

        // Instantiate Front End Popup
        new Post_Type();

        add_action( 'admin_head', array( $this, 'load_assets' ) );
    }

    public function dispatch_actions() {
        add_action( 'admin_init', array( $this, 'form_handler' ) );
    }

    public function load_assets() {
        wp_enqueue_style( 'arpc-metabox' );
        wp_enqueue_script( 'arpc-main-ajax' );
    }

    public function form_handler() {

        if ( !isset( $_POST['submit_new_form'] ) ) {
            return;
        }

        if ( !current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'new-form' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $name  = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
        $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
        $phone = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
        $address  = isset( $_POST['address'] ) ? wc_sanitize_textarea( $_POST['address'] ) : '';

        if ( empty( $name ) ) {
            $this->errors['name'] = __( 'Must provide name', 'arpc-popup-creator' );
        }

        if ( empty( $email ) ) {
            $this->errors['email'] = __( 'Must provide email', 'arpc-popup-creator' );
        }

        if ( empty( $phone ) ) {
            $this->errors['phone'] = __( 'Must provide phone number', 'arpc-popup-creator' );
        }

        if ( empty( $address ) ) {
            $this->errors['address'] = __( 'Must provide address', 'arpc-popup-creator' );
        }

        if ( ! empty( $this->errors ) ) {
            return;
        }
        
        $inserted_id = arpc_insert_users( [
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'address' => $address,
        ] );

        var_dump( $inserted_id );

        if ( is_wp_error( $inserted_id ) ) {
            echo "did not inserted";
            wp_die( $inserted_id->get_error_message() );
        }

        $redirected_to = admin_url('wp-admin/edit.php?post_type=arpc_popup&page=arpc-popup-form&success=true');
        wp_redirect( $redirected_to );
        exit;
    }
}
