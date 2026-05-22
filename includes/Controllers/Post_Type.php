<?php

namespace ARPC\Popup\Controllers;

/**
 * Post Type Controller
 *
 * Registers the custom post type for popups.
 */
class Post_Type {

	/**
	 * Constructor - register post type.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Register the arpc_popup post type.
	 */
	public function register() {
		$labels = array(
			'name'                  => __( 'Popups Creator', 'arpc-popup-creator' ),
			'singular_name'         => __( 'Popup Creator', 'arpc-popup-creator' ),
			'featured_image'        => __( 'Popup Image', 'arpc-popup-creator' ),
			'set_featured_image'    => __( 'Set Popup Image as Background', 'arpc-popup-creator' ),
			'search_items'          => __( 'Search Popup', 'arpc-popup-creator' ),
			'all_items'             => __( 'All Popups', 'arpc-popup-creator' ),
			'add_new_item'          => __( 'Add New', 'arpc-popup-creator' ),
			'add_new'               => __( 'Add New', 'arpc-popup-creator' ),
			'new_item'              => __( 'New Popup', 'arpc-popup-creator' ),
			'edit_item'             => __( 'Edit Popup', 'arpc-popup-creator' ),
			'update_item'           => __( 'Update Popup', 'arpc-popup-creator' ),
			'view_item'             => __( 'View Popup', 'arpc-popup-creator' ),
			'remove_featured_image' => __( 'Remove Popup Image', 'arpc-popup-creator' ),
		);

		$args = array(
			'label'                 => __( 'Popups', 'arpc-popup-creator' ),
			'description'           => __( 'Popup Description', 'arpc-popup-creator' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail' ),
			'hierarchical'          => false,
			'public'                => false,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_rest'          => false,
			'rest_base'             => 'arpc-popup',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'menu_position'         => 60,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'has_archive'           => false,
			'exclude_from_search'   => false,
			'capability_type'       => 'post',
			'rewrite'               => array(
				'slug'       => 'popup',
				'with_front' => true,
			),
			'menu_icon'             => 'dashicons-screenoptions',
		);

		register_post_type( 'arpc_popup', $args );
	}
}
