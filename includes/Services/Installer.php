<?php

namespace ARPC\Popup\Services;

/**
 * Installer Service
 *
 * Handles plugin installation and database table creation.
 */
class Installer {

	/**
	 * Run installation tasks.
	 */
	public function run() {
		$this->add_version();
		$this->create_tables();
	}

	/**
	 * Add plugin version to options.
	 */
	public function add_version() {
		$installed = get_option( 'arpc_popup_installed' );
		if ( ! $installed ) {
			update_option( 'arpc_popup_installed', time() );
		}

		update_option( 'arpc_popup_version', ARPC_VERSION );
	}

	/**
	 * Create database tables.
	 */
	public function create_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}arpc_popup`(
			id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			name varchar(100) NOT NULL DEFAULT '',
			pc_active int(1) DEFAULT NULL,
			pc_auto_hide_pu int(1) DEFAULT NULL,
			pc_show_in_delay varchar(10) DEFAULT NULL,
			pc_url varchar(100) DEFAULT NULL,
			pc_image_size varchar(100) DEFAULT NULL,
			pc_ww_show varchar(100) DEFAULT NULL,
			pc_show_on_exit BOOLEAN NOT NULL,
			created_at DATETIME NOT NULL,
			created_by BIGINT(20) UNSIGNED NOT NULL,
			PRIMARY KEY (`id`)
		) $charset_collate";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		dbDelta( $schema );

		$subscriber_schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}arpc_subscriber`(
			id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			popup_id INT(11) UNSIGNED NOT NULL,
			name varchar(100) NOT NULL DEFAULT '',
			email varchar(100) DEFAULT NULL,
			interests varchar(255) DEFAULT '',
			created_at DATETIME NOT NULL,
			created_by BIGINT(20) UNSIGNED NOT NULL,
			PRIMARY KEY (`id`)
		) $charset_collate";

		dbDelta( $subscriber_schema );

		$this->maybe_migrate_subscriber_table();
	}

	/**
	 * Add the interests column to existing subscriber tables.
	 */
	private function maybe_migrate_subscriber_table() {
		global $wpdb;

		$table  = "{$wpdb->prefix}arpc_subscriber";
		$column = $wpdb->get_results( "SHOW COLUMNS FROM `{$table}` LIKE 'interests'" );

		if ( empty( $column ) ) {
			$wpdb->query( "ALTER TABLE `{$table}` ADD COLUMN `interests` varchar(255) DEFAULT '' AFTER `email`" ); // phpcs:ignore
		}
	}
}
