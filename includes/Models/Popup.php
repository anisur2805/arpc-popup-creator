<?php
/**
 * Popup model.
 *
 * @package ARPC\Popup
 */

namespace ARPC\Popup\Models;

/**
 * Popup Model
 *
 * Handles all database operations for the arpc_popup table.
 */
class Popup {

	/**
	 * Get all popups with optional filters.
	 *
	 * @param array $args Query arguments.
	 * @return array
	 */
	public static function all( $args = array() ) {
		$defaults = array(
			'post_type'   => 'arpc_popup',
			'post_status' => 'publish',
			'numberposts' => -1,
		);

		$args = wp_parse_args( $args, $defaults );

		return get_posts( $args );
	}

	/**
	 * Get active popups.
	 *
	 * @return array
	 */
	public static function get_active() {
		return self::all(
			array(
				'meta_key'   => 'arpc_active',
				'meta_value' => 1,
			)
		);
	}

	/**
	 * Get a single popup by ID.
	 *
	 * @param int $id Popup post ID.
	 * @return \WP_Post|null
	 */
	public static function find( $id ) {
		return get_post( $id );
	}

	/**
	 * Get popup meta data.
	 *
	 * @param int    $post_id Popup post ID.
	 * @param string $key     Meta key.
	 * @param bool   $single  Whether to return single value.
	 * @return mixed
	 */
	public static function get_meta( $post_id, $key, $single = true ) {
		return get_post_meta( $post_id, $key, $single );
	}

	/**
	 * Update popup meta data.
	 *
	 * @param int    $post_id Popup post ID.
	 * @param string $key     Meta key.
	 * @param mixed  $value   Meta value.
	 * @return bool
	 */
	public static function update_meta( $post_id, $key, $value ) {
		return update_post_meta( $post_id, $key, sanitize_text_field( $value ) );
	}

	/**
	 * Save popup settings from metabox.
	 *
	 * @param int   $post_id Popup post ID.
	 * @param array $data    Form data.
	 * @return void
	 */
	public static function save_metabox( $post_id, $data ) {
		// Legacy fields removed. Gutenberg block content is now the primary
		// popup body; the metabox owns behavior, targeting, and appearance only.
	}
}
