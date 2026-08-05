<?php
/**
 * Uninstall routine.
 *
 * Removes every option, post and table the plugin created. This runs only when the
 * plugin is deleted from the Plugins screen, never on deactivation.
 *
 * @package ARPC\Popup
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$arpc_options = array(
	'arpc_popup_installed',
	'arpc_popup_version',
	'arpc_db_version',
	'arpc_general_setting',
	'arpc_setting_opn',
	'arpc_adv_setting',
);

foreach ( $arpc_options as $arpc_option ) {
	delete_option( $arpc_option );
}

// Delete every popup. wp_delete_post() with $force_delete also removes the post meta.
$arpc_popup_ids = $wpdb->get_col(
	$wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s", 'arpc_popup' )
); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One-off cleanup on uninstall.

foreach ( $arpc_popup_ids as $arpc_popup_id ) {
	wp_delete_post( (int) $arpc_popup_id, true );
}

// Drop the plugin's own tables.
$arpc_tables = array(
	$wpdb->prefix . 'arpc_popup',
	$wpdb->prefix . 'arpc_subscriber',
);

foreach ( $arpc_tables as $arpc_table ) {
	$wpdb->query( "DROP TABLE IF EXISTS `{$arpc_table}`" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange -- Table names come from $wpdb->prefix; identifiers cannot be prepared.
}
