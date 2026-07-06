<?php

namespace ARPC\Popup\Controllers;

use ARPC\Popup\Data_Table\Data_Table;
use ARPC\Popup\Data_Table\Subscribers_List_Table;
use ARPC\Popup\Services\Settings;

/**
 * Admin Controller
 *
 * Handles admin menu, settings pages, and subscriber management.
 */
class Admin {

	/**
	 * Subscribers list table instance.
	 *
	 * @var Subscribers_List_Table
	 */
	public $subscriber_table;

	/**
	 * Constructor - initialize admin functionality.
	 */
	public function __construct() {
		new Data_Table();
		new Metabox();
		new Settings();

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'maybe_migrate' ) );
		add_filter( 'set-screen-option', array( __CLASS__, 'set_screen' ), 10, 3 );
		add_action( 'admin_head', array( $this, 'load_assets' ) );
	}

	/**
	 * Run table migrations on admin pages for existing installations.
	 */
	public function maybe_migrate() {
		$installer = new \ARPC\Popup\Services\Installer();
		$installer->migrate();
	}

	/**
	 * Load admin assets.
	 */
	public function load_assets() {
		wp_enqueue_style( 'arpc-metabox' );
		wp_enqueue_script( 'arpc-main-ajax' );
	}

	/**
	 * Register admin menus.
	 */
	public function admin_menu() {
		$capability  = 'manage_options';
		$parent_slug = 'edit.php?post_type=arpc_popup';

		add_submenu_page(
			$parent_slug,
			__( 'Settings', 'arpc-popup-creator' ),
			__( 'Settings', 'arpc-popup-creator' ),
			$capability,
			'arpc-popup-settings',
			array( $this, 'settings_page' )
		);

		$hook = add_submenu_page(
			$parent_slug,
			__( 'Subscribers', 'arpc-popup-creator' ),
			__( 'Subscribers', 'arpc-popup-creator' ),
			$capability,
			'arpc-popup-subscribers',
			array( $this, 'subscribers_page' )
		);

		add_action( "load-$hook", array( $this, 'load_subscribers_screen_options' ) );

		wp_enqueue_style( 'arpc-admin-style' );
		wp_enqueue_script( 'arpc-tabbed' );
		wp_enqueue_script( 'admin-subscriber' );
	}

	/**
	 * Load screen options for subscribers page.
	 */
	public function load_subscribers_screen_options() {
		$args = array(
			'label'   => __( 'Subscribers Per Page', 'arpc-popup-creator' ),
			'default' => 5,
			'option'  => 'arpc_subscribers_per_page',
		);

		add_screen_option( 'per_page', $args );

		$this->subscriber_table = new Subscribers_List_Table();
	}

	/**
	 * Set screen option value.
	 *
	 * @param bool   $status Screen option status.
	 * @param string $option Option name.
	 * @param mixed  $value  Option value.
	 * @return mixed
	 */
	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	/**
	 * Render settings page.
	 */
	public function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error(
				'arpc_settings_messages',
				'arpc_settings_message',
				__( 'Settings Saved.', 'arpc-popup-creator' ),
				'updated'
			);
		}

		$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';

		include ARPC_PATH . '/includes/Views/admin/settings.php';
	}

	/**
	 * Render subscribers page.
	 */
	public function subscribers_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$subscriber_table = new Subscribers_List_Table();

		include ARPC_PATH . '/includes/Views/admin/subscribers.php';
	}
}
