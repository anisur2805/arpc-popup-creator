<?php
/**
 * Tests for the legacy settings screens.
 *
 * @package ARPC\Popup
 */

/**
 * Settings registration test case.
 */
class Test_Settings extends WP_UnitTestCase {

	/**
	 * Options registered by the plugin.
	 *
	 * @return array[]
	 */
	public function option_provider() {
		return array(
			array( 'arpc_general_setting' ),
			array( 'arpc_setting_opn' ),
			array( 'arpc_adv_setting' ),
		);
	}

	/**
	 * Every registered option must declare a sanitize callback, otherwise raw
	 * admin input is written straight to the options table.
	 *
	 * @dataProvider option_provider
	 *
	 * @param string $option Option name.
	 */
	public function test_registered_settings_declare_a_sanitize_callback( $option ) {
		global $wp_registered_settings;

		( new ARPC\Popup\Services\Settings() )->register();

		$this->assertArrayHasKey( $option, $wp_registered_settings, "{$option} must be registered." );
		$this->assertArrayHasKey(
			'sanitize_callback',
			$wp_registered_settings[ $option ],
			"{$option} must declare a sanitize_callback."
		);
		$this->assertIsCallable( $wp_registered_settings[ $option ]['sanitize_callback'] );
	}

	/**
	 * The sanitizer strips markup from nested values.
	 *
	 * @dataProvider option_provider
	 *
	 * @param string $option Option name.
	 */
	public function test_sanitizer_strips_markup( $option ) {
		global $wp_registered_settings;

		( new ARPC\Popup\Services\Settings() )->register();

		$callback = $wp_registered_settings[ $option ]['sanitize_callback'];

		$clean = call_user_func(
			$callback,
			array(
				'plain'  => '<script>alert(1)</script>hello',
				'nested' => array( 'deep' => '<b>bold</b>' ),
			)
		);

		$this->assertStringNotContainsString( '<script>', $clean['plain'] );
		$this->assertStringNotContainsString( '<b>', $clean['nested']['deep'] );
	}
}
