<?php

namespace ARPC\Popup\Admin;

use ARPC\Popup\Data_Table\Subscribers_List_Table;
use ARPC\Popup\Settings\SettingsAPI_Wrapper;

/**
 * Menu class
 */
class Menu {
    
    public function __construct() {

        add_action('admin_menu', [$this, 'admin_menu']);
                
        // include __DIR__ . "./Settings/SettingsAPI.php";
        new SettingsAPI_Wrapper();

    }

    public function admin_menu() {
        
        $capability = 'manage_options';
        $parent_slug = 'edit.php?post_type=arpc_popup';

        add_submenu_page($parent_slug, __('Settings', 'arpc-popup-creator'), __('Settings', 'arpc-popup-creator'), $capability, 'arpc-popup-settings', [ $this, 'arpc_settings_page' ] );
        $hook = add_submenu_page( $parent_slug, __('Subscribers', 'arpc-popup-creator'), __('Subscribers', 'arpc-popup-creator'), $capability, 'arpc-popup-subscribers', [ $this, 'subscribers_page' ] );
        
        add_action( "load-$hook", [ $this, 'load_user_list_table_screen_options' ] );

        wp_enqueue_style('arpc-admin-style');
        wp_enqueue_script('arpc-tabbed');
        wp_enqueue_script('admin-subscriber');

    }

    public function load_user_list_table_screen_options() {

        $args = array(
            'label'		=>	__( 'Subscribers Per Page', 'arpc-popup-creator' ),
            'default'	=>	5,
            'option'	=>	'arpc_subscribers_per_page'
        );

        add_screen_option( 'per_page', $args );
        
        /*
         * Instantiate the User List Table. Creating an instance here will allow the core WP_List_Table class to automatically
         * load the table columns in the screen options panel		 
         */	 
        $this->subscriber_table = new Subscribers_List_Table();	

    }

    public function arpc_settings_page() { 
        if(!current_user_can('manage_options')) {
            return;
        }
        if(isset($_GET['settings-updated'])) {
            add_settings_error('arpc_settings_messages', 'arpc_settings_message', __('Settings Saved, khush', 'arpc-popup-creator'), 'updated');
        }
        // show error/ update messages
        settings_errors('arpc_settings_messages');
        ?>
        	<div class="wrap">
                <h1><?php esc_html_e(get_admin_page_title()); ?></h1>
                <form action="options.php" method="post">
                    <?php 
                    settings_fields('arpc_setting_opg'); 
                    do_settings_sections('arpc-popup-settings');
                    ?>
                    <?php submit_button('Save Settings'); ?>
                </form>
            </div>
    <?php

    }

    public function subscribers_page() { ?>
        <h2><?php _e('Subscriber Lists') ?></h2>
        <?php $subscriber_table = new Subscribers_List_Table(); ?>
        <div class="wrap">
            <form id="art-search-form" method="GET">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
                <?php
                $subscriber_table->prepare_items();
                $subscriber_table->search_box('search', 'search_id');
                $subscriber_table->display();
                ?>
            </form>
        </div>
    <?php
    }
}
