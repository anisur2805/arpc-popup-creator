<?php
namespace ARPC\Popup;

class ARPC_Popup_Rest_API {
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'arpc_rest_api_init' ) );
	}
	/**
	 * REST API init
	 */
	public function arpc_rest_api_init() {

		register_rest_route(
			'arpc/v1',
			'/popup',
			array(
				'methods'             => 'GET',
				'callback'            => 'arpc_set_item',
				'permission_callback' => array( $this, 'set_item_permissions_check' ),
			)
		);

		// register_rest_route( 'arpc_popup/v2', '/popup-creator',
		// array(
		//     'methods' => 'PATCH',
		//     'callback' => 'arpc_item_update',
		//     // 'permission_callback' => 'set_item_permissions_check',
		// )
		// );
	}

	public function arpc_set_item() {
		$response = array( 'abced efg yes' );
		return rest_ensure_response( $response );
	}

	public function arpc_item_update( $response ) {
		return rest_ensure_response( $response );
	}

	public function set_item_permissions_check() {
		return true;
	}
}
