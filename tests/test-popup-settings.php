<?php
/**
 * Tests for the popup settings schema, sanitization and request matching.
 *
 * @package ARPC\Popup
 */

use ARPC\Popup\Services\Popup_Settings;

/**
 * Popup settings test case.
 */
class Test_Popup_Settings extends WP_UnitTestCase {

	/**
	 * Every default key must have a sanitized counterpart, and vice versa.
	 */
	public function test_defaults_and_sanitize_stay_in_lockstep() {
		$defaults  = Popup_Settings::defaults();
		$sanitized = Popup_Settings::sanitize( array() );

		$this->assertSame(
			array(),
			array_diff( array_keys( $defaults ), array_keys( $sanitized ) ),
			'Every default key must survive sanitize().'
		);

		$this->assertSame(
			array(),
			array_diff( array_keys( $sanitized ), array_keys( $defaults ) ),
			'sanitize() must not invent keys that have no default.'
		);
	}

	/**
	 * Free trigger modes are accepted.
	 */
	public function test_sanitize_accepts_free_trigger_modes() {
		foreach ( Popup_Settings::free_trigger_modes() as $mode ) {
			$settings = Popup_Settings::sanitize( array( 'trigger_mode' => $mode ) );
			$this->assertSame( $mode, $settings['trigger_mode'] );
		}
	}

	/**
	 * Pro trigger modes must be rejected server-side, not merely hidden in the UI.
	 */
	public function test_sanitize_rejects_pro_trigger_modes() {
		$defaults = Popup_Settings::defaults();

		foreach ( Popup_Settings::pro_trigger_modes() as $mode => $label ) {
			$settings = Popup_Settings::sanitize( array( 'trigger_mode' => $mode ) );

			$this->assertSame(
				$defaults['trigger_mode'],
				$settings['trigger_mode'],
				sprintf( 'Pro trigger "%s" must fall back to the default.', $mode )
			);
		}
	}

	/**
	 * Unknown choice values fall back to the default instead of being stored.
	 */
	public function test_sanitize_rejects_unknown_popup_type() {
		$defaults = Popup_Settings::defaults();
		$settings = Popup_Settings::sanitize( array( 'popup_type' => 'totally-made-up' ) );

		$this->assertSame( $defaults['popup_type'], $settings['popup_type'] );
	}

	/**
	 * Integer fields are clamped to their allowed range.
	 */
	public function test_sanitize_clamps_integer_ranges() {
		$settings = Popup_Settings::sanitize(
			array(
				'open_delay'          => 999999,
				'overlay_blur_amount' => -5,
			)
		);

		$this->assertSame( 86400, $settings['open_delay'] );
		$this->assertSame( 0, $settings['overlay_blur_amount'] );
	}

	/**
	 * Dependent fields are reset when their parent is switched off.
	 */
	public function test_sanitize_resets_dependent_fields() {
		$defaults = Popup_Settings::defaults();

		$settings = Popup_Settings::sanitize(
			array(
				'periodicity'    => 'every_time',
				'period_value'   => 42,
				'period_unit'    => 'week',
				'activity_mode'  => 'always',
				'activity_start' => '2026-01-01T10:00',
				'activity_end'   => '2026-02-01T10:00',
			)
		);

		$this->assertSame( $defaults['period_value'], $settings['period_value'] );
		$this->assertSame( $defaults['period_unit'], $settings['period_unit'] );
		$this->assertSame( '', $settings['activity_start'] );
		$this->assertSame( '', $settings['activity_end'] );
	}

	/**
	 * Saving mirrors the enabled flag into the arpc_active meta the frontend query uses.
	 */
	public function test_save_mirrors_active_meta() {
		$post_id = self::factory()->post->create( array( 'post_type' => 'arpc_popup' ) );

		Popup_Settings::save( $post_id, array( 'enabled' => '1' ) );
		$this->assertSame( '1', get_post_meta( $post_id, 'arpc_active', true ) );

		Popup_Settings::save( $post_id, array() );
		$this->assertSame( '', get_post_meta( $post_id, 'arpc_active', true ) );
	}

	/**
	 * A disabled popup never matches the request.
	 */
	public function test_matches_request_requires_enabled() {
		$post_id  = self::factory()->post->create( array( 'post_type' => 'arpc_popup' ) );
		$settings = Popup_Settings::sanitize( array() );

		$this->assertFalse( Popup_Settings::matches_request( $post_id, $settings ) );
	}

	/**
	 * The activity window is evaluated in site time, not UTC.
	 *
	 * A site ahead of UTC would previously drop popups whose window was open,
	 * because the stored local datetime was compared against a shifted timestamp.
	 */
	public function test_activity_window_respects_site_timezone() {
		update_option( 'timezone_string', 'Asia/Dhaka' );

		$post_id = self::factory()->post->create( array( 'post_type' => 'arpc_popup' ) );
		$now     = current_datetime();

		$settings = Popup_Settings::sanitize(
			array(
				'enabled'        => '1',
				'activity_mode'  => 'certain_period',
				'activity_start' => $now->modify( '-1 hour' )->format( 'Y-m-d\TH:i' ),
				'activity_end'   => $now->modify( '+1 hour' )->format( 'Y-m-d\TH:i' ),
			)
		);

		$this->assertTrue(
			Popup_Settings::matches_request( $post_id, $settings ),
			'A window open right now must match on a UTC+6 site.'
		);
	}

	/**
	 * A window that has already closed does not match.
	 */
	public function test_activity_window_rejects_past_window() {
		update_option( 'timezone_string', 'Asia/Dhaka' );

		$post_id = self::factory()->post->create( array( 'post_type' => 'arpc_popup' ) );
		$now     = current_datetime();

		$settings = Popup_Settings::sanitize(
			array(
				'enabled'        => '1',
				'activity_mode'  => 'certain_period',
				'activity_start' => $now->modify( '-10 days' )->format( 'Y-m-d\TH:i' ),
				'activity_end'   => $now->modify( '-9 days' )->format( 'Y-m-d\TH:i' ),
			)
		);

		$this->assertFalse( Popup_Settings::matches_request( $post_id, $settings ) );
	}

	/**
	 * The PHP period units must stay in sync with the multiplier table in popup-main.js.
	 */
	public function test_period_units_match_the_javascript_multiplier_table() {
		$js = file_get_contents( dirname( __DIR__ ) . '/assets/js/popup-main.js' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading a local fixture file in a test.

		foreach ( array_keys( Popup_Settings::period_unit_choices() ) as $unit ) {
			$this->assertStringContainsString(
				$unit,
				$js,
				sprintf( 'Period unit "%s" has no multiplier in popup-main.js.', $unit )
			);
		}
	}
}
