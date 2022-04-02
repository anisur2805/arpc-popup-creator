<?php

namespace APC\Popup\Admin;

/**
 * Menu class
 */
class Menu {
      public function __construct() {
            add_action('admin_menu', [$this, 'admin_menu']);
      }

      public function admin_menu() {
            $capability = 'manage_options';
            $parent_slug = 'edit.php?post_type=popup';
            $page_title = __('Settings', 'popup');

            add_submenu_page($parent_slug, __('Settings', 'popup'), __('Settings', 'popup'), $capability, 'popup-settings', [$this, 'settings_page']);
      }

      public function settings_page() {
            ?>
            <div class="wrap add_contact_wrap">
                  <h1><?php _e('Popup Settings', 'popup-creator'); ?></h1>
                  <form method="post" class="pc_metabox_wrapper">
                        <div class="apc_form_group">
                              <label for="apc_fname">First Name</label>
                              <input type="text" class="regular-text" name="apc_fname" id="apc_fname" />
                        </div>
                        <div class="apc_form_group">
                              <label for="apc_lname">Last Name</label>
                              <input type="text" class="regular-text" id="apc_lname" name="apc_lname" />
                        </div>
                        <div class="apc_form_group">
                              <label for="apc_email">Email</label>
                              <input type="text" class="regular-text" id="apc_email" name="apc_email" />
                        </div>
                        <input type="hidden" name="action" value="apc_add_contact" />
                        <?php 
                              wp_nonce_field('apc-add-contact');
                              submit_button('Add contact', 'primary', 'add_contact'); 
                        ?>
                  </form>
            </div>
            <?php
      }
}
