<?php

namespace ARPC\Popup\Admin;

use ARPC\Popup\Subscriber_Data_Table;

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

            add_submenu_page($parent_slug, __('Settings', 'popup'), __('Settings', 'popup'), $capability, 'arpc-popup-settings', [$this, 'settings_page']);
            add_submenu_page($parent_slug, __('Subscribers', 'popup'), __('Subscribers', 'popup'), $capability, 'arpc-popup-subscribers', [$this, 'subscribers_page']);
            
            wp_enqueue_style('arpc-tabbed');
            wp_enqueue_script('arpc-tabbed');
            
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
            
            <div class="arpc_tabbed_wrapper">
                  <ul class="nav nav-tabs">
                        <li class="active"><a href="#overlay"><?php _e('Overlay', 'arpc-popup-creator'); ?></a></li>
                        <li><a href="#container"><?php _e('Container', 'arpc-popup-creator'); ?></a></li>
                        <li><a href="#title"><?php _e('Title', 'arpc-popup-creator'); ?></a></li>
                        <li><a href="#content"><?php _e('Content', 'arpc-popup-creator'); ?></a></li>
                        <li><a href="#close"><?php _e('Close Icon', 'arpc-popup-creator'); ?></a></li>
                  </ul>

                  <div class="tab-content">
                        <div id="overlay" class="tab-pane active">
                              <div>
                              <p>Some sort of content</p>
                              </div>
                        </div>
                        <div id="container" class="tab-pane">
                              <p>Hello world</p>
                        </div>
                        <div id="title" class="tab-pane">
                              <p>Some sort of content</p>
                        </div>
                        <div id="content" class="tab-pane">
                              <p>Some sort of content</p>
                        </div>
                        <div id="close" class="tab-pane">
                              <p>Some sort of content</p>
                        </div>
                  </div>
            </div>
            <?php
            
      }
      
      public function subscribers_page() {
           ?>
           <h2><?php _e('Subscriber Lists') ?></h2>
                  <?php
                  
                  // global $wpdb;
                  // $dbdemo_users = $wpdb->get_results($wpdb->prepare("SELECT id, name, email FROM {$wpdb->prefix}persons ORDER BY id DESC"), ARRAY_A);
                  // print_r( $dbdemo_users );
                  // die();
                  // $data = array();
                  $subscriber_table = new Subscriber_Data_Table();
                  ?>
                  <div class="wrap">
                        <form id="art-search-form" method="GET">
                        <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
                              <?php 
                                    $subscriber_table->prepare_items();
                                    $subscriber_table->search_box( 'search', 'search_id' );
                                    $subscriber_table->display();
                              ?>
                        </form>
                  </div>
                  <?php            
            
      }
}
