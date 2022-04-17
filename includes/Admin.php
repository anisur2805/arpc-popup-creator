<?php

namespace ARPC\Popup;

use ARPC\Popup\Admin\Menu;
use ARPC\Popup\Data_Table;
use ARPC\Popup\Metabox;
use ARPC\Popup\Post_Type;
use ARPC\Popup\Addresses;
use ARPC\Popup\Traits\Form_Error;

class Admin {

    use Form_Error;

    public function __construct() {
        // Instantiate Address
        $address = new Addresses();

        $this->dispatch_actions( $address );

        // Instantiate Data Table
        new Data_Table();

        // Instantiate Subscriber Data Table
        // new Subscriber_Data_Table();

        //Instantiate Meta box
        new Metabox();

        // Add Menu page
        new Menu( $address );

        // Instantiate Front End Popup
        new Post_Type();

        add_action('admin_head', array($this, 'load_assets'));

        // add_action('admin_menu', [$this, 'admin_form_menu']);
    }

    public function admin_form_menu() {
        $capability = 'manage_options';
        $parent_slug = 'edit.php?post_type=arpc_popup';
        add_submenu_page($parent_slug, __('Addresses', 'arpc-popup-creator'), __('Addresses', 'arpc-popup-creator'), $capability, 'arpc-address-form', [$this, 'Admin_Form_page']);
    }

    public function Admin_Form_page() {
        include __DIR__ . '/Admin/Addresses/AddressList.php';
    }
    public function dispatch_actions( $address ) {
        add_action('admin_init', array( $address, 'form_handler'));
    }

    public function load_assets() {
        wp_enqueue_style('arpc-metabox');
        wp_enqueue_script('arpc-main-ajax');
    }
}
