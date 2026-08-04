<?php
/**
 * Assets service.
 *
 * @package ARPC\Popup
 */

namespace ARPC\Popup\Services;

/**
 * Assets Service
 *
 * Handles asset registration and enqueuing.
 */
class Assets {

	/**
	 * Constructor - register asset hooks.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Get script definitions.
	 *
	 * @return array
	 */
	public function get_scripts() {
		return array(
			'plain-modal'         => array(
				'src'     => ARPC_ASSETS . '/js/jquery.plainmodal.min.js',
				'version' => filemtime( ARPC_PATH . '/assets/js/jquery.plainmodal.min.js' ),
				'deps'    => array( 'jquery' ),
			),
			'arpc-main'           => array(
				'src'     => ARPC_ASSETS . '/js/popup-main.js',
				'version' => filemtime( ARPC_PATH . '/assets/js/popup-main.js' ),
				'deps'    => array( 'jquery' ),
			),
			'arpc-metabox-script' => array(
				'src'     => ARPC_ASSETS . '/js/metabox.js',
				'version' => filemtime( ARPC_PATH . '/assets/js/metabox.js' ),
				'deps'    => array( 'jquery' ),
			),
			'arpc-modal-form'     => array(
				'src'     => ARPC_ASSETS . '/js/popup-form.js',
				'version' => filemtime( ARPC_PATH . '/assets/js/popup-form.js' ),
				'deps'    => array( 'jquery' ),
			),
			'arpc-tabbed'         => array(
				'src'     => ARPC_ASSETS . '/js/tabbed.js',
				'version' => filemtime( ARPC_PATH . '/assets/js/tabbed.js' ),
				'deps'    => array(),
			),
			'arpc-admin-subscriber' => array(
				'src'     => ARPC_ASSETS . '/js/admin-subscriber.js',
				'version' => filemtime( ARPC_PATH . '/assets/js/admin-subscriber.js' ),
				'deps'    => array( 'jquery', 'wp-util' ),
			),
		);
	}

	/**
	 * Get style definitions.
	 *
	 * @return array
	 */
	public function get_styles() {
		return array(
			'arpc-metabox'     => array(
				'src'     => ARPC_ASSETS . '/css/metabox.css',
				'version' => filemtime( ARPC_PATH . '/assets/css/metabox.css' ),
			),
			'arpc-admin-style' => array(
				'src'     => ARPC_ASSETS . '/css/admin-style.css',
				'version' => filemtime( ARPC_PATH . '/assets/css/admin-style.css' ),
			),
			'arpc-style'       => array(
				'src'     => ARPC_ASSETS . '/css/puc-style.css',
				'version' => filemtime( ARPC_PATH . '/assets/css/puc-style.css' ),
			),
		);
	}

	/**
	 * Register and enqueue assets.
	 */
	public function enqueue() {
		foreach ( $this->get_scripts() as $handle => $script ) {
			wp_register_script( $handle, $script['src'], $script['deps'], $script['version'], true );
		}

		foreach ( $this->get_styles() as $handle => $style ) {
			wp_register_style( $handle, $style['src'], array(), $style['version'] );
		}

		// Localize scripts with AJAX data.
		wp_localize_script(
			'arpc-modal-form',
			'arpcModalForm',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'success' => __( 'Thanks for subscribe', 'arpc-popup-creator' ),
				'error'   => __( 'Something went wrong in Front area', 'arpc-popup-creator' ),
			)
		);

		wp_localize_script(
			'arpc-main',
			'arpcAnalytics',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'arpc_analytics' ),
			)
		);

		wp_localize_script(
			'arpc-admin-subscriber',
			'arpcAdminSub',
			array(
				'nonce'   => wp_create_nonce( 'arpc-admin-subscriber' ),
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'confirm' => __( 'Are you sure?', 'arpc-popup-creator' ),
				'success' => __( 'Thanks for subscribe', 'arpc-popup-creator' ),
				'error'   => __( 'Something went wrong', 'arpc-popup-creator' ),
			)
		);
	}
}
