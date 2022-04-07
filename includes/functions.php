<?php

/**
 * Insert a new address
 *
 * @param array $args
 *
 * @return int|WP_Error
 */
function apc_insert_popup( $args = [] ) {
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
function apc_get_addresses( $args = [] ) {
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
function apc_addresses_count() {
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
function apc_get_address( $id ) {
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
function apc_delete_address( $id ) {
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

function input_field( $type = "text", $id='', $name='', $placeholder='', $value='', $class = "regular-text apc_input" ) {
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
