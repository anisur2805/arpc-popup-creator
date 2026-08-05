<?php
/**
 * Tests for the AJAX handlers, focused on nonce and capability enforcement.
 *
 * @package ARPC\Popup
 */

use ARPC\Popup\Models\Subscriber;

/**
 * AJAX handler test case.
 *
 * @group ajax
 */
class Test_Ajax extends WP_Ajax_UnitTestCase {

	/**
	 * Reset request state and the subscriber table.
	 */
	public function set_up() {
		parent::set_up();

		global $wpdb;
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}arpc_subscriber" ); // phpcs:ignore

		$_POST    = array();
		$_REQUEST = array();
	}

	/**
	 * Count rows in the subscriber table.
	 *
	 * @return int
	 */
	private function subscriber_count() {
		global $wpdb;

		return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}arpc_subscriber" ); // phpcs:ignore
	}

	/**
	 * Dispatch an AJAX action and return the decoded JSON response.
	 *
	 * @param string $action Action name.
	 * @return array
	 */
	private function dispatch( $action ) {
		try {
			$this->_handleAjax( $action );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		} catch ( WPAjaxDieStopException $e ) {
			unset( $e );
		}

		return json_decode( $this->_last_response, true );
	}

	/**
	 * A submission without a valid nonce is rejected and stores nothing.
	 */
	public function test_modal_form_rejects_invalid_nonce() {
		$_POST['_wpnonce']   = 'not-a-real-nonce';
		$_POST['arpc-email'] = 'someone@example.com';
		$_REQUEST            = $_POST;

		$response = $this->dispatch( 'arpc_modal_form_action' );

		$this->assertFalse( $response['success'] );
		$this->assertSame( 0, $this->subscriber_count(), 'No row may be stored without a valid nonce.' );
	}

	/**
	 * A malformed email is rejected even with a good nonce.
	 */
	public function test_modal_form_rejects_invalid_email() {
		$_POST['_wpnonce']   = wp_create_nonce( 'arpc-modal-form' );
		$_POST['arpc-email'] = 'definitely-not-an-email';
		$_REQUEST            = $_POST;

		$response = $this->dispatch( 'arpc_modal_form_action' );

		$this->assertFalse( $response['success'] );
		$this->assertSame( 0, $this->subscriber_count() );
	}

	/**
	 * A valid submission stores the subscriber against the owning popup.
	 */
	public function test_modal_form_stores_valid_submission() {
		$popup_id = self::factory()->post->create( array( 'post_type' => 'arpc_popup' ) );

		$_POST['_wpnonce']      = wp_create_nonce( 'arpc-modal-form' );
		$_POST['arpc-email']    = 'ada@example.com';
		$_POST['arpc-name']     = 'Ada';
		$_POST['arpc-popup-id'] = $popup_id;
		$_REQUEST               = $_POST;

		$response = $this->dispatch( 'arpc_modal_form_action' );

		$this->assertTrue( $response['success'] );
		$this->assertSame( 1, $this->subscriber_count() );

		$rows = Subscriber::all();
		$this->assertSame( 'ada@example.com', $rows[0]['email'] );
	}

	/**
	 * Analytics tracking requires a valid nonce.
	 */
	public function test_track_event_rejects_invalid_nonce() {
		$popup_id = self::factory()->post->create( array( 'post_type' => 'arpc_popup' ) );

		$_POST['_wpnonce'] = 'bogus';
		$_POST['popup_id'] = $popup_id;
		$_POST['event']    = 'view';
		$_REQUEST          = $_POST;

		$response = $this->dispatch( 'arpc_track_event' );

		$this->assertFalse( $response['success'] );
		$this->assertSame( '', get_post_meta( $popup_id, 'arpc_analytics_view', true ) );
	}

	/**
	 * A valid tracking request increments the counter.
	 */
	public function test_track_event_increments_counter() {
		$popup_id = self::factory()->post->create( array( 'post_type' => 'arpc_popup' ) );

		$_POST['_wpnonce'] = wp_create_nonce( 'arpc_analytics' );
		$_POST['popup_id'] = $popup_id;
		$_POST['event']    = 'view';
		$_REQUEST          = $_POST;

		$response = $this->dispatch( 'arpc_track_event' );

		$this->assertTrue( $response['success'] );
		$this->assertSame( '1', get_post_meta( $popup_id, 'arpc_analytics_view', true ) );
	}

	/**
	 * Unknown event names are ignored rather than creating arbitrary meta keys.
	 */
	public function test_track_event_ignores_unknown_event_names() {
		$popup_id = self::factory()->post->create( array( 'post_type' => 'arpc_popup' ) );

		$_POST['_wpnonce'] = wp_create_nonce( 'arpc_analytics' );
		$_POST['popup_id'] = $popup_id;
		$_POST['event']    = 'evil_key';
		$_REQUEST          = $_POST;

		$this->dispatch( 'arpc_track_event' );

		$this->assertSame( '', get_post_meta( $popup_id, 'arpc_analytics_evil_key', true ) );
	}

	/**
	 * Subscriber deletion is refused for users without manage_options.
	 */
	public function test_delete_subscriber_requires_capability() {
		$id = Subscriber::insert(
			array(
				'name'  => 'Ada',
				'email' => 'ada@example.com',
			)
		);

		wp_set_current_user( self::factory()->user->create( array( 'role' => 'subscriber' ) ) );

		$_REQUEST['_wpnonce'] = wp_create_nonce( 'arpc-admin-subscriber' );
		$_REQUEST['id']       = $id;

		$response = $this->dispatch( 'arpc-delete-subscriber' );

		$this->assertFalse( $response['success'] );
		$this->assertSame( 1, $this->subscriber_count(), 'A subscriber-role user must not delete rows.' );
	}
}
