<?php

namespace ARPC\Popup\Admin;

use ARPC\Popup\Data_Table\Subscribers_List_Table;
use ARPC\Popup\Settings\Settings_API_Wrapper;

/**
 * Menu class
 */
class Menu {

	/**
	 * User List Table
	 *
	 * @var Subscribers_List_Table
	 * @since    1.0.0
	 * @access   public
	 */
	public $subscriber_table;

	/**
	 * Menu class constructor
	 *
	 * @return void
	 * @since    1.0.0
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'set-screen-option', array( __CLASS__, 'set_screen' ), 10, 3 );

		// include __DIR__ . "./Settings/SettingsAPI.php";
		new Settings_API_Wrapper();
	}

	public function admin_menu() {

		$capability  = 'manage_options';
		$parent_slug = 'edit.php?post_type=arpc_popup';

		add_submenu_page( $parent_slug, __( 'Settings', 'arpc-popup-creator' ), __( 'Settings', 'arpc-popup-creator' ), $capability, 'arpc-popup-settings', array( $this, 'arpc_settings_page' ) );
		$hook = add_submenu_page( $parent_slug, __( 'Subscribers', 'arpc-popup-creator' ), __( 'Subscribers', 'arpc-popup-creator' ), $capability, 'arpc-popup-subscribers', array( $this, 'subscribers_page' ) );

		add_action( "load-$hook", array( $this, 'load_user_list_table_screen_options' ) );

		wp_enqueue_style( 'arpc-admin-style' );
		wp_enqueue_script( 'arpc-tabbed' );
		wp_enqueue_script( 'admin-subscriber' );
	}

	public function load_user_list_table_screen_options() {

		$args = array(
			'label'   => __( 'Subscribers Per Page', 'arpc-popup-creator' ),
			'default' => 5,
			'option'  => 'arpc_subscribers_per_page',
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
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error( 'arpc_settings_messages', 'arpc_settings_message', __( 'Settings Saved, khush', 'arpc-popup-creator' ), 'updated' );
		}
		// show error/ update messages
		settings_errors( 'arpc_settings_messages' );
		?>
			<div class="wrap">
				<h1><?php echo esc_html( get_admin_page_title(), 'arpc-popup-creator' ); ?></h1>
				<form action="options.php" method="post">
					<?php
					settings_fields( 'arpc_setting_opg' );
					do_settings_sections( 'arpc-popup-settings' );
					?>
					<?php submit_button( 'Save Settings' ); ?>
				</form>
			</div>
		<?php
	}

	public function subscribers_page() {

		?>
		<h2><?php _e( 'Subscriber Lists' ); ?></h2>
		<?php $subscriber_table = new Subscribers_List_Table(); ?>
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
