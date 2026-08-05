<?php
/**
 * Tests for the social proof block's server rendering.
 *
 * @package ARPC\Popup
 */

/**
 * Social proof render test case.
 */
class Test_Social_Proof_Block extends WP_UnitTestCase {

	/**
	 * Start each test with an empty subscriber table and no cached count.
	 */
	public function set_up() {
		parent::set_up();

		global $wpdb;
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}arpc_subscriber" ); // phpcs:ignore

		foreach ( array( 1, 24, 48, 720 ) as $window ) {
			delete_transient( 'arpc_social_proof_' . $window );
		}
	}

	/**
	 * Add N subscribers created right now.
	 *
	 * @param int $count How many rows to insert.
	 * @return void
	 */
	private function add_subscribers( $count ) {
		global $wpdb;

		for ( $i = 0; $i < $count; $i++ ) {
			$wpdb->insert( // phpcs:ignore
				"{$wpdb->prefix}arpc_subscriber",
				array(
					'popup_id'   => 1,
					'name'       => 'Person ' . $i,
					'email'      => 'person' . $i . '@example.com',
					'created_at' => current_time( 'mysql' ),
					'created_by' => 0,
				),
				array( '%d', '%s', '%s', '%s', '%d' )
			);
		}
	}

	/**
	 * Render the block through the real block pipeline.
	 *
	 * render_block() is used rather than calling the render callback directly so
	 * that block supports are set up exactly as they are on a real request.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	private function render( $attributes = array() ) {
		return render_block(
			array(
				'blockName'    => 'arpc/social-proof',
				'attrs'        => $attributes,
				'innerBlocks'  => array(),
				'innerHTML'    => '',
				'innerContent' => array(),
			)
		);
	}

	/**
	 * With enough subscribers the block reports the count.
	 */
	public function test_renders_count_when_threshold_met() {
		$this->add_subscribers( 3 );

		$output = $this->render(
			array(
				'hours'    => 24,
				'minCount' => 1,
			)
		);

		$this->assertStringContainsString( '3', $output );
		$this->assertStringContainsString( 'people subscribed', $output );
	}

	/**
	 * Below the threshold the block renders nothing at all.
	 */
	public function test_renders_nothing_below_min_count() {
		$this->add_subscribers( 2 );

		$output = $this->render(
			array(
				'hours'    => 24,
				'minCount' => 5,
			)
		);

		$this->assertSame( '', trim( $output ) );
	}

	/**
	 * An empty site renders nothing rather than "0 people subscribed".
	 */
	public function test_renders_nothing_when_no_subscribers() {
		$output = $this->render(
			array(
				'hours'    => 24,
				'minCount' => 1,
			)
		);

		$this->assertSame( '', trim( $output ) );
	}

	/**
	 * A single subscriber uses the singular message.
	 */
	public function test_uses_singular_message_for_one_subscriber() {
		$this->add_subscribers( 1 );

		$output = $this->render(
			array(
				'hours'    => 24,
				'minCount' => 1,
			)
		);

		$this->assertStringContainsString( 'person subscribed', $output );
		$this->assertStringNotContainsString( 'people subscribed', $output );
	}

	/**
	 * Out-of-range windows are clamped rather than trusted.
	 */
	public function test_clamps_absurd_hour_values() {
		$this->add_subscribers( 1 );

		$output = $this->render(
			array(
				'hours'    => 999999,
				'minCount' => 1,
			)
		);

		$this->assertStringContainsString( '720', $output, 'The window must clamp to 720 hours.' );
	}

	/**
	 * Never leak subscriber identities to the front end.
	 */
	public function test_never_exposes_subscriber_details() {
		global $wpdb;

		$wpdb->insert( // phpcs:ignore
			"{$wpdb->prefix}arpc_subscriber",
			array(
				'popup_id'   => 1,
				'name'       => 'Grace Hopper',
				'email'      => 'grace@example.com',
				'created_at' => current_time( 'mysql' ),
				'created_by' => 0,
			),
			array( '%d', '%s', '%s', '%s', '%d' )
		);

		$output = $this->render(
			array(
				'hours'    => 24,
				'minCount' => 1,
			)
		);

		$this->assertStringNotContainsString( 'Grace Hopper', $output );
		$this->assertStringNotContainsString( 'grace@example.com', $output );
	}
}
