<?php

namespace ARPC\Popup\Models;

/**
 * Subscriber Model
 *
 * Handles all database operations for the arpc_subscriber table.
 */
class Subscriber {

	/**
	 * Insert a new subscriber.
	 *
	 * @param array $args Subscriber data.
	 * @return int|\WP_Error Inserted ID or error.
	 */
	public static function insert( $args = array() ) {
		global $wpdb;

		$defaults = array(
			'name'       => '',
			'email'      => '',
			'interests'  => '',
			'popup_id'   => 0,
			'created_by' => get_current_user_id(),
			'created_at' => current_time( 'mysql' ),
		);

		$data   = wp_parse_args( $args, $defaults );
		$format = array( '%s', '%s', '%s', '%d', '%d', '%s' );

		$inserted = $wpdb->insert( "{$wpdb->prefix}arpc_subscriber", $data, $format );

		if ( ! $inserted ) {
			return new \WP_Error( 'failed-to-insert', __( 'Failed to insert', 'arpc-popup-creator' ) );
		}

		return $wpdb->insert_id;
	}

	/**
	 * Delete a subscriber by ID.
	 *
	 * @param int $id Subscriber ID.
	 * @return bool|int
	 */
	public static function delete( $id ) {
		global $wpdb;

		return $wpdb->delete(
			$wpdb->prefix . 'arpc_subscriber',
			array( 'id' => $id ),
			array( '%d' )
		);
	}

	/**
	 * Delete multiple subscribers.
	 *
	 * @param array $ids Array of subscriber IDs.
	 * @return void
	 */
	public static function bulk_delete( $ids ) {
		foreach ( $ids as $id ) {
			self::delete( intval( $id ) );
		}
	}

	/**
	 * Get all subscribers with optional search.
	 *
	 * @param string $search Search term.
	 * @return array
	 */
	public static function all( $search = '' ) {
		global $wpdb;

		if ( ! empty( $search ) ) {
			$like  = '%' . $wpdb->esc_like( $search ) . '%';
			$query = $wpdb->prepare(
				"SELECT id, name, email, interests, created_at
				FROM {$wpdb->prefix}arpc_subscriber
				WHERE id LIKE %s
				OR name LIKE %s
				OR email LIKE %s
				OR interests LIKE %s
				OR created_at LIKE %s",
				$like,
				$like,
				$like,
				$like,
				$like
			);

			return $wpdb->get_results( $query, ARRAY_A ); // phpcs:ignore
		}

		return $wpdb->get_results(
			"SELECT id, name, email, interests, created_at FROM {$wpdb->prefix}arpc_subscriber",
			ARRAY_A
		);
	}

	/**
	 * Get a single subscriber by ID.
	 *
	 * @param int $id Subscriber ID.
	 * @return object|null
	 */
	public static function find( $id ) {
		global $wpdb;

		return $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}arpc_subscriber WHERE id = %d",
				$id
			)
		);
	}
}
