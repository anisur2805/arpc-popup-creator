<?php

/**
 * Insert a new address
 *
 * @param array $args
 *
 * @return int|WP_Error
 */
function arpc_insert_popup( $args = [] ) {
 global $wpdb;

 if ( empty( $args['name'] ) ) {
  return new \WP_Error( 'no-name', __( 'You must provide a name', 'oop-academy' ) );
 }

 $defaults = [
  "name"       => '',
  "address"    => '',
  "phone"      => '',
  "created_by" => get_current_user_id(),
  "created_at" => current_time( 'mysql' ),
 ];

 $data = wp_parse_args( $args, $defaults );

 if ( isset( $data['id'] ) ) {

  $id = $data['id'];
  unset( $data['id'] );

  $updated = $wpdb->update(
   "{$wpdb->prefix}ac_addresses",
   $data,
   ['id' => $id],
   [
    '%s',
    '%s',
    '%s',
    '%d',
    '%s',
   ],
   ['%d']
  );

  return $updated;
 } else {

  $inserted = $wpdb->insert(
   "{$wpdb->prefix}ac_addresses",
   $data,
   [
    '%s',
    '%s',
    '%s',
    '%d',
    '%s',
   ]
  );

  if ( !$inserted ) {
   return new \WP_Error( 'failed-to-insert', __( 'Failed to insert', 'oop-academy' ) );
  }

  return $wpdb->insert_id;
 }

}

/**
 * Fetch address
 *
 * @param array
 *
 * @return array
 */
function arpc_get_addresses( $args = [] ) {
 global $wpdb;

 $defaults = [
  "offset"  => 0,
  "number"  => 20,
  "orderby" => "id",
  "order"   => "ASC",
 ];

 $args = wp_parse_args( $args, $defaults );

 $items = $wpdb->get_results(
  $wpdb->prepare(
   "SELECT * FROM {$wpdb->prefix}ac_addresses
            ORDER BY {$args["orderby"]} {$args["order"]}
            LIMIT %d OFFSET %d",
   $args["number"], $args["offset"],
  )
 );

 return $items;

}

/**
 * Get the count
 *
 * @return int
 */
function arpc_addresses_count() {
 global $wpdb;

 return (int) $wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}ac_addresses " );
}

/**
 * Fetch a single contact form DB
 *
 * @param int $id
 *
 * @return object
 */
function arpc_get_address( $id ) {
 global $wpdb; // Global WPDB class object

 $address = wp_cache_get( 'book-' . $id, 'address' );
 if ( false === $address ) {
  $address = $wpdb->get_row(
   $wpdb->prepare(  // use prepare for avoid sql injection
    "SELECT  * FROM {$wpdb->prefix}ac_addresses WHERE id = %d", $id // select by id
   )
  );

  wp_cache_set( 'book-' . $id, $address, 'address' );

 }
 return $address;

}

/**
 * Delete an address
 *
 * @param int $id
 *
 * @return int|boolean
 */
function arpc_delete_address( $id ) {
 global $wpdb;

 return $wpdb->delete(
  $wpdb->prefix . 'ac_addresses',
  ['id' => $id],
  ['%d'],
 );
}


/**
 * Register popup creator image sizes
 */
function register_image_size() {
      add_image_size('popup-creator-landscape', 800, 600, true);
      add_image_size('popup-creator-square', 500, 500, true);
      add_image_size('popup-creator-thumbnail', 70);
}

register_image_size();

// Form input field and label 
function inputLabel( $for, $name ) {
      echo '<label for="' . $for . '">' . __( $name, 'popup-creator' ) . '</label>';
}

function input_field( $type = "text", $id='', $name='', $placeholder='', $value='', $class = "regular-text arpc_input" ) {
      printf(
            '<input 
                  type="%s" 
                  id="%s" 
                  name="%s" 
                  placeholder="%s" 
                  value="%s" 
                  class="%s" 
                  require
            />',
                  
                  $type, 
                  $id, 
                  $name, 
                  $placeholder, 
                  $value, 
                  $class 
      );
}


// Change Add title to 
function arpc_title_text( $title ){
      $screen = get_current_screen();
    
      if  ( 'popup' == $screen->post_type ) {
           $title = __( 'Add popup title', 'popup-creator' );
      }
    
      return $title;
 }
    
 add_filter( 'enter_title_here', 'arpc_title_text' );
 
 
 function test_sc( $atts = array() ) {
      $atts = shortcode_atts( array(
            'count' => 5,
            'meetup' => 'georgetown-tx-wordpress',
      ), $atts, 'arpc_upcoming_meetups' );

      $key = 'arpc_upcoming_meetups_' . sanitize_title( $atts['meetup'] ) . '_' . intval( $atts['count'] );
      $output = get_transient( $key );
      
      if( false === $output ) {

            $query_args = array(
                  'sign' => 'true',
                  'photo-host' => 'public',
                  'page' => intval( $atts['count'] ),
            );

            $request_uri = 'https://www.meetup.com/api/' . sanitize_title( $atts['meetup'] ) . '/events';
            $request = wp_remote_get( add_query_arg( $query_args, $request_uri ) );

            if( is_wp_error( $request ) || '200' != wp_remote_retrieve_response_code( $request ) )
                  return;

            $events = json_decode( wp_remote_retrieve_body( $request ) );
            if( empty( $events ) )
                  return;

            $output = '<ul>';
            $output .= "<p>Hello </p>";
            foreach( $events as $event ) {
                  $date = strtotime( $event->local_date . ' ' . $event->local_time );
                  $output .= sprintf(
                        '<li><a href="%s">%s</a> on %s at %s</li>',
                        esc_url_raw( $event->link ),
                        esc_html( $event->name ),
                        date( 'F jS', $date ),
                        date( 'g:ia', $date )
                  );
            }
            $output .= '</ul>';

            set_transient( $key, $output, DAY_IN_SECONDS );
      }

      return $output;
}

add_shortcode('arpc_upcoming_meetups', 'test_sc' );