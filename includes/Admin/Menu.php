<?php

namespace ARPC\Popup\Admin;

/**
 * Menu class
 */
class Menu {
      public function __construct() {
            add_action('admin_menu', [$this, 'admin_menu']);
      }

      public function admin_menu() {
            $capability = 'manage_options';
            $parent_slug = 'edit.php?post_type=arpc_popup';
            $page_title = __('Settings', 'popup');

            add_submenu_page($parent_slug, __('Settings', 'popup'), __('Settings', 'popup'), $capability, 'popup-settings', [$this, 'settings_page']);
      }

      public function settings_page() {
            ?>
            <div class="wrap arpc_add_contact_wrap">
                  <h1><?php _e('Popup Settings', 'popup-creator'); ?></h1>
                  <form method="post" class="arpc_metabox_wrapper">
                        <div class="arpc_form_group">
                              <label for="arpc_fname">First Name</label>
                              <input type="text" class="regular-text" name="arpc_fname" id="arpc_fname" />
                        </div>
                        <div class="arpc_form_group">
                              <label for="arpc_lname">Last Name</label>
                              <input type="text" class="regular-text" id="arpc_lname" name="arpc_lname" />
                        </div>
                        <div class="arpc_form_group">
                              <label for="arpc_email">Email</label>
                              <input type="text" class="regular-text" id="arpc_email" name="arpc_email" />
                        </div>
                        <input type="hidden" name="action" value="arpc_add_contact" />
                        <?php 
                              wp_nonce_field('arpc-add-contact');
                              submit_button('Add contact', 'primary', 'add_contact'); 
                        ?>
                  </form>
            </div>
            <?php
      }
}
