<?php

namespace ARPC\Popup\Admin;

use ARPC\Popup\Subscriber_Data_Table;

/**
 * Menu class
 */
class Menu {
      public $address;

      public function __construct( $address ) {
            $this->address = $address;

            add_action('admin_menu', [$this, 'admin_menu']);
      }

      public function admin_menu() {
            $capability = 'manage_options';
            $parent_slug = 'edit.php?post_type=arpc_popup';
            $page_title = __('Settings', 'arpc-popup-creator');

            add_submenu_page($parent_slug, __('Settings', 'arpc-popup-creator'), __('Settings', 'arpc-popup-creator'), $capability, 'arpc-popup-settings', [ $this->address, 'settings_page']);
            add_submenu_page($parent_slug, __('Subscribers', 'arpc-popup-creator'), __('Subscribers', 'arpc-popup-creator'), $capability, 'arpc-popup-subscribers', [ $this->address, 'subscribers_page']);

            add_submenu_page($parent_slug, __('Addresses', 'arpc-popup-creator'), __('Addresses', 'arpc-popup-creator'), $capability, 'arpc-popup-form', [$this->address, 'address_page']);
            
            wp_enqueue_style('arpc-admin-style');
            wp_enqueue_script('arpc-tabbed');
            
      }

}
