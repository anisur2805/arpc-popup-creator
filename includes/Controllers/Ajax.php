<?php

namespace ARPC\Popup\Controllers;

use ARPC\Popup\Models\Subscriber;

/**
 * Ajax Controller
 *
 * Handles all AJAX requests for the plugin.
 */
class Ajax {

	/**
	 * Constructor - register AJAX actions.
	 */
	public function __construct() {
		add_action( 'wp_ajax_arpc_modal_form_action', array( $this, 'handle_modal_form' ) );
		add_action( 'wp_ajax_nopriv_arpc_modal_form_action', array( $this, 'handle_modal_form' ) );
		add_action( 'wp_ajax_arpc-delete-subscriber', array( $this, 'handle_delete_subscriber' ) );
	}

	/**
	 * Handle modal form submission.
	 */
	public function handle_modal_form() {
		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'arpc-modal-form' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce verify failed!', 'arpc-popup-creator' ),
				)
			);
		}

		$categories = isset( $_POST['arpc-categories'] ) && is_array( $_POST['arpc-categories'] )
			? array_map( 'sanitize_text_field', wp_unslash( $_POST['arpc-categories'] ) )
			: array();

		$data = array(
			'name'      => isset( $_POST['arpc-name'] ) ? sanitize_text_field( $_POST['arpc-name'] ) : '',
			'email'     => isset( $_POST['arpc-email'] ) ? sanitize_text_field( $_POST['arpc-email'] ) : '',
			'interests' => implode( ', ', $categories ),
			'popup_id'  => isset( $_POST['arpc-popup-id'] ) ? intval( $_POST['arpc-popup-id'] ) : 0,
		);

		$result = Subscriber::insert( $data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success();
	}

	/**
	 * Handle subscriber deletion.
	 */
	public function handle_delete_subscriber() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Permission denied.', 'arpc-popup-creator' ) );
		}

		$id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'admin-subscriber' ) ) {
			wp_send_json_error( __( 'No Cheating', 'arpc-popup-creator' ) );
		}

		Subscriber::delete( $id );

		wp_send_json_success( __( 'Deleted successfully', 'arpc-popup-creator' ) );
	}
}
