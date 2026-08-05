<?php
/**
 * PHPUnit bootstrap for the WordPress integration test suite.
 *
 * Run inside the wp-env tests container, where WP_TESTS_DIR points at the
 * WordPress PHPUnit library:
 *
 *   npx wp-env run tests-cli --env-cwd=wp-content/plugins/arpc-popup-creator \
 *       vendor/bin/phpunit
 *
 * @package ARPC\Popup
 */

if ( PHP_SAPI !== 'cli' ) {
	exit;
}

$arpc_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $arpc_tests_dir ) {
	$arpc_tests_dir = '/wordpress-phpunit';
}

if ( ! file_exists( $arpc_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find the WordPress test suite at {$arpc_tests_dir}.\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI bootstrap.
	exit( 1 );
}

require_once $arpc_tests_dir . '/includes/functions.php';

/**
 * Load the plugin before WordPress finishes booting.
 */
function arpc_manually_load_plugin() {
	require dirname( __DIR__ ) . '/arpc-popup-creator.php';
}

tests_add_filter( 'muplugins_loaded', 'arpc_manually_load_plugin' );

require $arpc_tests_dir . '/includes/bootstrap.php';

// Activation hooks do not fire in the test suite, so create the custom tables here.
$arpc_installer = new ARPC\Popup\Services\Installer();
$arpc_installer->run();
