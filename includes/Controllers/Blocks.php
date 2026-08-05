<?php
/**
 * Block registration controller.
 *
 * @package ARPC\Popup
 */

namespace ARPC\Popup\Controllers;

/**
 * Blocks Controller
 *
 * Registers the plugin's server-rendered blocks from their block.json metadata.
 */
class Blocks {

	/**
	 * Constructor - register hooks.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Register every block shipped in the blocks/ directory.
	 */
	public function register() {
		if ( ! function_exists( 'register_block_type_from_metadata' ) ) {
			return;
		}

		register_block_type_from_metadata( ARPC_PATH . '/blocks/social-proof' );
	}
}
