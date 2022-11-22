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
        add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
                
        // include __DIR__ . "./Settings/SettingsAPI.php";
        new SettingsAPI_Wrapper();

        add_action('admin_init', [ $this, 'settings_init' ] );

    }

    function settings_init() {
        register_setting('arpc-popup-general-settings', 'arpc_general_setting');
        register_setting('arpc-popup-adv-settings', 'arpc_adv_setting');
    
        add_settings_section(
            'arpc_general_settings_section',
            'General Settings Section', [ $this, 'arpc_general_settings_section_callback' ],
            'arpc-popup-general-settings'
        );
        
        add_settings_section(
            'arpc_adv_settings_section',
            'Advance Settings Section', [ $this, 'arpc_adv_settings_section_callback' ],
            'arpc-popup-adv-settings'
        );
    
        // add_settings_field(
        //     'arpc_general_settings_logo',
        //     'Upload Logo', [ $this, 'arpc_logo_callback' ],
        //     'arpc-popup-general-settings',
        //     'arpc_general_settings_section'
        // );

        add_settings_field(
            'arpc_general_settings_template',
            __( 'Choose Template', 'arpc-popup-creator' ), [ $this, 'arpc_setting_template_callback' ],
            'arpc-popup-general-settings',
            'arpc_general_settings_section'
        );

        // add_settings_field(
        //     'arpc_adv_settings_name',
        //     'Name', [ $this, 'arpc_name_callback' ],
        //     'arpc-popup-adv-settings',
        //     'arpc_adv_settings_section'
        // );
    }

    /**
         * callback functions
         */
        
        // section content cb
        function arpc_general_settings_section_callback() {
            echo '<p>General Section Introduction.</p>';
        }

        function arpc_adv_settings_section_callback() {
            echo '<p>Advanced Section Introduction. <small>More features coming soon.</small></p>';
        }
        
        // Logo field content cb
        function arpc_logo_callback() {
            // get the value of the setting we've registered with register_setting()
            $setting = get_option('arpc_general_setting');
            $value = isset( $setting['arpc_general_settings_logo'] ) ? $setting['arpc_general_settings_logo'] : 'Chat with us';

            // output the field
            ?>
            <input type="text" name="arpc_general_setting[arpc_general_settings_logo]" value="<?php echo esc_attr( $value ); ?>" />
            <?php
        }
        
        // Template field content cb
        function arpc_setting_template_callback() {
            // get the value of the setting we've registered with register_setting()
            $setting = get_option('arpc_general_setting');
            $value = isset( $setting['arpc_general_settings_template'] ) ? $setting['arpc_general_settings_template'] : 'template1';
            
            ?>
            <select name="arpc_general_setting[arpc_general_settings_template]" id="arpc_general_setting[arpc_general_settings_template]" style="display: none;">
                <option value=""><?php _e( 'Select Template', 'arpc-popup-creator' ); ?></option>
                <option value="template1" <?php selected( $value, "template1"); ?>><?php _e( 'Template 1', 'arpc-popup-creator' ); ?></option>
                <option value="template2" <?php selected( $value, "template2"); ?>><?php _e( 'Template 2', 'arpc-popup-creator' ); ?></option>
                <option value="template3" <?php selected( $value, "template3"); ?>><?php _e( 'Template 3', 'arpc-popup-creator' ); ?></option>
            </select>


            <div id="choose_template" class="tab-pane active">
				<div>
					<div class="choose-template-wrapper">
						<fieldset>
							<label>
                                <input type="radio" name="arpc_general_setting[arpc_general_settings_template]" id="arpc_general_setting[arpc_general_settings_template]" value="template1" <?php checked( $value, 'template1'); ?>> <?php esc_html_e('Template 1'); ?>
								<img src="<?php echo esc_url(ARPC_ASSETS . '/images/template1.png') ?>" alt="Template 1" />
							</label>
							<label>
                                <input type="radio" name="arpc_general_setting[arpc_general_settings_template]" id="arpc_general_setting[arpc_general_settings_template]" value="template2" <?php checked( $value, 'template2'); ?>> <?php esc_html_e('Template 2'); ?>
								<img src="<?php echo esc_url(ARPC_ASSETS . '/images/template2.png') ?>" alt="Template 2" />
							</label>
							<label>
                                <input type="radio" name="arpc_general_setting[arpc_general_settings_template]" id="arpc_general_setting[arpc_general_settings_template]" value="template3" <?php checked( $value, 'template3'); ?>> <?php esc_html_e('Template 3'); ?>
								<img src="<?php echo esc_url(ARPC_ASSETS . '/images/template3.png') ?>" alt="Template 3" />
							</label>
						</fieldset>
					</div>
				</div>
			</div>

            <?php
        }
        
        // Name field content cb
        function arpc_name_callback() {
            
            $setting = get_option('arpc_adv_setting');
            $value = isset( $setting['arpc_adv_settings_logo'] ) ? $setting['arpc_adv_settings_logo'] : 'Anisur Rahman';

            // output the field
            ?>
            <input type="text" name="arpc_adv_setting[arpc_adv_settings_logo]" value="<?php echo esc_attr( $value ); ?>" />
            
            <?php
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

    public static function set_screen( $status, $option, $value ) {
        return $value;
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

                <?php 
                    $active_tab = (isset( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';
                    $active_general = ( $active_tab == 'general') ? 'nav-tab-active' : '';

                    $active_adv = ( $active_tab == 'adv') ? 'nav-tab-active' : '';

                    echo '<h2 class="nav-tab-wrapper">';
                    
                    echo '<a href="?post_type=arpc_popup&page=arpc-popup-settings&tab=general" class="nav-tab '. $active_general . '">General</a>' . "\n";
                    echo '<a href="?post_type=arpc_popup&page=arpc-popup-settings&tab=adv" class="nav-tab '. $active_adv . '">Advance </a>' . "\n";

                    echo '</h2>';
                ?>

                <form action="options.php" method="post">
                    <?php 

                    if( $active_tab == "general") {
                        settings_fields('arpc-popup-general-settings'); 
                        do_settings_sections('arpc-popup-general-settings');
                    } else {
                        settings_fields('arpc-popup-adv-settings'); 
                        do_settings_sections('arpc-popup-adv-settings');
                    }
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
