<?php

namespace ARPC\Popup\Controllers;

/**
 * REST API Controller
 *
 * Handles REST API endpoints for the plugin.
 */
class Rest_Api {

	/**
	 * Constructor - register REST routes.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register REST routes.
	 */
	public function register_routes() {
		register_rest_route(
			'arpc/v1',
			'/popup',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_popups' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);
	}

	/**
	 * Get popups endpoint.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_popups( $request ) {
		$popups = get_posts(
			array(
				'post_type'   => 'arpc_popup',
				'post_status' => 'publish',
				'numberposts' => -1,
			)
		);

		$data = array();

		foreach ( $popups as $popup ) {
			$data[] = array(
				'id'    => $popup->ID,
				'title' => $popup->post_title,
			);
		}

		return rest_ensure_response( $data );
	}

	/**
	 * Check permissions for REST endpoint.
	 *
	 * @return bool
	 */
	public function check_permissions() {
		return current_user_can( 'read' );
	}
}
