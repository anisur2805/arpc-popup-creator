<?php

namespace ARPC\Popup\Controllers;

use ARPC\Popup\Models\Popup;
use ARPC\Popup\Services\Popup_Settings;

/**
 * Metabox Controller
 *
 * Handles metabox registration, rendering, and saving.
 */
class Metabox {

	/**
	 * Constructor - hook into WordPress actions.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
		add_action( 'admin_head', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue metabox scripts.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'arpc-metabox-script' );
	}

	/**
	 * Add meta box to popup post type.
	 *
	 * @param string $post_type Current post type.
	 */
	public function add_meta_box( $post_type ) {
		$post_types = array( 'arpc_popup' );

		if ( in_array( $post_type, $post_types, true ) ) {
			add_meta_box(
				'arpc_popup_metabox',
				__( 'Popup Creator', 'arpc-popup-creator' ),
				array( $this, 'render' ),
				$post_type,
				'advanced',
				'high'
			);
		}
	}

	/**
	 * Render the metabox content.
	 *
	 * @param \WP_Post $post Current post object.
	 */
	public function render( $post ) {
		wp_nonce_field( 'popup_creator', 'popup_creator_nonce' );

		$data = array(
			'popup_settings'     => Popup_Settings::get( $post->ID ),
			'title'              => Popup::get_meta( $post->ID, 'arpc_title' ),
			'subtitle'           => Popup::get_meta( $post->ID, 'arpc_subtitle' ),
			'image_size'         => Popup::get_meta( $post->ID, 'arpc_image_size' ),
			'popup_url'          => Popup::get_meta( $post->ID, 'arpc_popup_url' ),
			'image_id'           => Popup::get_meta( $post->ID, 'arpc_image_id' ),
			'image_url'          => Popup::get_meta( $post->ID, 'arpc_image_url' ),
			'role_labels'        => Popup_Settings::role_labels(),
			'manual_trigger'     => Popup_Settings::manual_trigger_key( $post->ID ),
			'location_type_labels' => Popup_Settings::location_type_choices(),
			'location_targets'   => Popup_Settings::location_target_sources(),
			'open_animation_options' => Popup_Settings::opening_animation_options(),
			'close_animation_options' => Popup_Settings::closing_animation_options(),
		);

		extract( $data ); // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found

		include ARPC_PATH . '/includes/Views/admin/metabox.php';
	}

	/**
	 * Verify nonce security.
	 *
	 * @param string $nonce_field Nonce field name.
	 * @param string $action      Nonce action.
	 * @param int    $post_id     Post ID.
	 * @return bool
	 */
	private function is_secured( $nonce_field, $action, $post_id ) {
		$nonce = isset( $_POST[ $nonce_field ] ) ? $_POST[ $nonce_field ] : '';

		if ( empty( $nonce ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( $nonce, $action ) ) {
			return false;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		if ( wp_is_post_autosave( $post_id ) ) {
			return false;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Save metabox data.
	 *
	 * @param int $post_id Post ID.
	 */
	public function save( $post_id ) {
		if ( ! $this->is_secured( 'popup_creator_nonce', 'popup_creator', $post_id ) ) {
			return;
		}

		$settings = isset( $_POST['arpc_popup_settings'] ) ? wp_unslash( $_POST['arpc_popup_settings'] ) : array();
		Popup_Settings::save( $post_id, $settings );
		Popup::save_metabox( $post_id, $_POST );
	}
}
