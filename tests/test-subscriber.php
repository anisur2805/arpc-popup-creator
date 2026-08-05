<?php
/**
 * Tests for the subscriber model.
 *
 * @package ARPC\Popup
 */

use ARPC\Popup\Models\Subscriber;

/**
 * Subscriber model test case.
 */
class Test_Subscriber extends WP_UnitTestCase {

	/**
	 * Clear the social proof cache between tests.
	 */
	public function set_up() {
		parent::set_up();

		global $wpdb;
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}arpc_subscriber" ); // phpcs:ignore

		delete_transient( 'arpc_social_proof_24' );
		delete_transient( 'arpc_social_proof_1' );
	}

	/**
	 * Insert a subscriber with an explicit creation time.
	 *
	 * @param string $created_at Site-time MySQL datetime.
	 * @return void
	 */
	private function insert_subscriber_at( $created_at ) {
		global $wpdb;

		$wpdb->insert( // phpcs:ignore
			"{$wpdb->prefix}arpc_subscriber",
			array(
				'popup_id'   => 1,
				'name'       => 'Test Person',
				'email'      => 'test' . wp_rand( 1000, 9999 ) . '@example.com',
				'created_at' => $created_at,
				'created_by' => 0,
			),
			array( '%d', '%s', '%s', '%s', '%d' )
		);
	}

	/**
	 * A subscriber added just now falls inside the window.
	 */
	public function test_count_since_includes_recent_subscriber() {
		$this->insert_subscriber_at( current_time( 'mysql' ) );

		$this->assertSame( 1, Subscriber::count_since( 24 ) );
	}

	/**
	 * A subscriber older than the window is excluded.
	 */
	public function test_count_since_excludes_older_subscriber() {
		$this->insert_subscriber_at(
			current_datetime()->modify( '-3 days' )->format( 'Y-m-d H:i:s' )
		);

		$this->assertSame( 0, Subscriber::count_since( 24 ) );
	}

	/**
	 * The window boundary is honoured: 2 hours ago is outside a 1 hour window.
	 */
	public function test_count_since_respects_window_boundary() {
		$this->insert_subscriber_at(
			current_datetime()->modify( '-2 hours' )->format( 'Y-m-d H:i:s' )
		);

		$this->assertSame( 0, Subscriber::count_since( 1 ) );
		$this->assertSame( 1, Subscriber::count_since( 24 ) );
	}

	/**
	 * An empty table counts zero rather than erroring.
	 */
	public function test_count_since_returns_zero_when_empty() {
		$this->assertSame( 0, Subscriber::count_since( 24 ) );
	}

	/**
	 * The count is cached so front-end page loads do not re-query.
	 */
	public function test_count_since_caches_the_result() {
		$this->insert_subscriber_at( current_time( 'mysql' ) );

		$this->assertSame( 1, Subscriber::count_since( 24 ) );
		$this->assertSame( 1, (int) get_transient( 'arpc_social_proof_24' ) );

		// A second row must not be visible until the transient expires.
		$this->insert_subscriber_at( current_time( 'mysql' ) );
		$this->assertSame( 1, Subscriber::count_since( 24 ) );
	}

	/**
	 * Inserting through the model stores the row.
	 */
	public function test_insert_stores_subscriber() {
		$id = Subscriber::insert(
			array(
				'name'  => 'Ada',
				'email' => 'ada@example.com',
			)
		);

		$this->assertIsInt( $id );
		$this->assertGreaterThan( 0, $id );

		$row = Subscriber::find( $id );
		$this->assertSame( 'ada@example.com', $row->email );
	}
}
