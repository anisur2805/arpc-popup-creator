<?php
/**
 * Plugin Name: Popup Creator
 * Description: Awesome Popup Creator
 * Plugin URI:  http://github.com/anisur2805/arpc-popup-creator
 * Version:     1.0
 * Author:      Anisur Rahman
 * Author URI:  http://github.com/anisur2805
 * Text Domain: arpc-popup-creator
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

use ARPC\Popup\Controllers\Admin;
use ARPC\Popup\Controllers\Ajax;
use ARPC\Popup\Controllers\Frontend;
use ARPC\Popup\Controllers\Post_Type;
use ARPC\Popup\Controllers\Block_Patterns;
use ARPC\Popup\Services\Assets;
use ARPC\Popup\Services\Installer;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class ARPC_Popup_Creator
 *
 * @package ARPC\Popup
 * @since   1.0
 * @version 1.0
 */
final class ARPC_Popup_Creator {

	const VERSION = '1.0';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
	}

	/**
	 * Initialize singleton instance.
	 *
	 * @return self
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Define plugin constants.
	 */
	public function define_constants() {
		define( 'ARPC_VERSION', self::VERSION );
		define( 'ARPC_FILE', __FILE__ );
		define( 'ARPC_PATH', __DIR__ );
		define( 'ARPC_URL', plugins_url( '', __FILE__ ) );
		define( 'ARPC_ASSETS', ARPC_URL . '/assets' );
	}

	/**
	 * Initialize plugin after plugins are loaded.
	 */
	public function init_plugin() {
		// Run schema migrations so existing installations pick up new columns.
		$installer = new Installer();
		$installer->migrate();

		// Register the popup post type in every context (admin, frontend, REST)
		// so the block editor's REST routes are available for saving.
		new Post_Type();
		new Block_Patterns();

		if ( is_admin() ) {
			new Admin();
		} else {
			new Frontend();
		}

		new Ajax();
		new Assets();
	}

	/**
	 * Run on plugin activation.
	 */
	public function activate() {
		$installer = new Installer();
		$installer->run();

		$installed = get_option( 'arpc_popup_installed' );
		if ( ! $installed ) {
			update_option( 'arpc_popup_installed', time() );
		}

		update_option( 'arpc_popup_version', ARPC_VERSION );

		flush_rewrite_rules();
	}
}

/**
 * Initialize the plugin.
 *
 * @return ARPC_Popup_Creator
 */
function arpc_popup_creator() {
	return ARPC_Popup_Creator::init();
}

arpc_popup_creator();

// Register image sizes.
add_action(
	'after_setup_theme',
	function () {
		add_image_size( 'popup-creator-landscape', 800, 600, true );
		add_image_size( 'popup-creator-square', 500, 500, true );
		add_image_size( 'popup-creator-thumbnail', 70 );
	}
);

// Change Add title text.
add_filter(
	'enter_title_here',
	function ( $title ) {
		$screen = get_current_screen();

		if ( 'arpc_popup' === $screen->post_type ) {
			$title = __( 'Add Popup title', 'arpc-popup-creator' );
		}

		return $title;
	}
);
