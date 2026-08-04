<?php
/**
 * AJAX request handlers.
 *
 * @package ARPC\Popup
 */

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
		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'arpc-modal-form' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce verify failed!', 'arpc-popup-creator' ),
				)
			);
		}

		$categories = isset( $_POST['arpc-categories'] ) && is_array( $_POST['arpc-categories'] )
			? array_map( 'sanitize_text_field', wp_unslash( $_POST['arpc-categories'] ) )
			: array();

		$email = isset( $_POST['arpc-email'] ) ? sanitize_email( wp_unslash( $_POST['arpc-email'] ) ) : '';

		if ( ! is_email( $email ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Please enter a valid email address.', 'arpc-popup-creator' ),
				)
			);
		}

		$data = array(
			'name'      => isset( $_POST['arpc-name'] ) ? sanitize_text_field( wp_unslash( $_POST['arpc-name'] ) ) : '',
			'email'     => $email,
			'interests' => implode( ', ', $categories ),
			'popup_id'  => isset( $_POST['arpc-popup-id'] ) ? absint( wp_unslash( $_POST['arpc-popup-id'] ) ) : 0,
		);

		$result = Subscriber::insert( $data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success(
			array(
				'message' => __( 'Thanks for subscribing!', 'arpc-popup-creator' ),
			)
		);
	}

	/**
	 * Handle subscriber deletion.
	 */
	public function handle_delete_subscriber() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Permission denied.', 'arpc-popup-creator' ) );
		}

		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'admin-subscriber' ) ) {
			wp_send_json_error( __( 'No Cheating', 'arpc-popup-creator' ) );
		}

		$id = isset( $_REQUEST['id'] ) ? absint( wp_unslash( $_REQUEST['id'] ) ) : 0;

		Subscriber::delete( $id );

		wp_send_json_success( __( 'Deleted successfully', 'arpc-popup-creator' ) );
	}
}
