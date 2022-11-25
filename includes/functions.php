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
function arpc_title_text($title) {
      $screen = get_current_screen();

      if ('arpc_popup' == $screen->post_type) {
            $title = __('My Popup Name', 'popup-creator');
      }

      return $title;
}

add_filter('enter_title_here', 'arpc_title_text');

/**
 * Delete a subscriber
 *
 * @param int $id
 *
 * @return int|boolean
 */
function arpc_delete_subscriber( $id ) {
      global $wpdb;
  
      return $wpdb->delete(
          $wpdb->prefix . 'arpc_subscriber',
          ['id' => $id],
          ['%d'],
      );
  }

/**
 * REST API init 
 */  
add_action( 'rest_api_init', 'arpc_rest_api_init');
function arpc_rest_api_init(){

    register_rest_route( 'abcde/v1', '/popup', array(
        'methods' => 'GET',
        'callback' => 'arpc_set_item',
        'permission_callback' => 'set_item_permissions_check',
    ) );

    // register_rest_route( 'arpc_popup/v2', '/popup-creator', 
    // array(
    //     'methods' => 'PATCH',
    //     'callback' => 'arpc_item_update',
    //     // 'permission_callback' => 'set_item_permissions_check',
    // )
    // );
}

function arpc_set_item( ) {
    $response = array("abced efg yes");
    return rest_ensure_response( $response );
}

function arpc_item_update( $response ) {
    return rest_ensure_response( $response );
}

function set_item_permissions_check(){
    return true;
}