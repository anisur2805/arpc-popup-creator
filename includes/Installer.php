<?php
namespace APC\Popup;

/**
 * Installer class
 */
class Installer {
    public function run() {
        $this->add_version();
        $this->create_tables();
    }

    public function add_version() {
        $installed = get_option( 'apc_popup_installed' );
        if ( !$installed ) {
            update_option( 'apc_popup_installed', time() );
        }

        update_option( 'apc_popup_version', PUC_VERSION );
    }

    public function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}apc_popup`(
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

        if ( !function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }

}
