<?php

namespace ARPC\Popup\Controllers;

use ARPC\Popup\Models\Popup;

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
			'delay'              => Popup::get_meta( $post->ID, 'arpc_show_in_delay' ) ?: 1,
			'title'              => Popup::get_meta( $post->ID, 'arpc_title' ),
			'subtitle'           => Popup::get_meta( $post->ID, 'arpc_subtitle' ),
			'auto_hide_delay_in' => Popup::get_meta( $post->ID, 'arpc_auto_hide_in' ),
			'auto_hide'          => Popup::get_meta( $post->ID, 'arpc_auto_hide_pu' ),
			'image_size'         => Popup::get_meta( $post->ID, 'arpc_image_size' ),
			'show_on_exit'       => Popup::get_meta( $post->ID, 'arpc_show_on_exit' ) ?: 0,
			'popup_url'          => Popup::get_meta( $post->ID, 'arpc_popup_url' ),
			'is_active'          => Popup::get_meta( $post->ID, 'arpc_active' ),
			'selected_page'      => Popup::get_meta( $post->ID, 'arpc_ww_show' ),
			'image_id'           => Popup::get_meta( $post->ID, 'arpc_image_id' ),
			'image_url'          => Popup::get_meta( $post->ID, 'arpc_image_url' ),
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

		Popup::save_metabox( $post_id, $_POST );
	}
}
