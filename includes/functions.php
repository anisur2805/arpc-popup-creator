<?php

/**
 * Insert a new popup to DB `arpc_subscriber` table
 *
 * @param array $args
 *
 * @return int|WP_Error
 */
function arpc_insert_popup( $args = [] ) {
    global $wpdb;

    $defaults = [
        "name"       => '',
        "email"      => '',
        "created_by" => get_current_user_id(),
        "created_at" => current_time( 'mysql' ),
    ];

    $data   = wp_parse_args( $args, $defaults );
    $format = [ '%s', '%s', '%d', '%s' ];

    $inserted = $wpdb->insert( "{$wpdb->prefix}arpc_subscriber", $data, $format );

    if ( !$inserted ) {
        return new \WP_Error( 'failed-to-insert', __( 'Failed to insert', 'arpc-popup-creator' ) );
    }

    return $wpdb->insert_id;
}

/**
 * Register popup creator image sizes
 */
function register_image_size() {
    add_image_size( 'popup-creator-landscape', 800, 600, true );
    add_image_size( 'popup-creator-square', 500, 500, true );
    add_image_size( 'popup-creator-thumbnail', 70 );
}

register_image_size();

// Change Add title to
function arpc_title_text( $title ) {
    $screen = get_current_screen();

    if ( 'arpc_popup' == $screen->post_type ) {
        $title = __( 'Add Popup title', 'popup-creator' );
    }

    return $title;
}

add_filter( 'enter_title_here', 'arpc_title_text' );
