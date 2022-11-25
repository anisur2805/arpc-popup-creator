<?php
namespace ARPC\Popup;
/**
 * Popup creator post type main class file
 */
class Post_Type {
      public function __construct() {
            add_action('init', array($this, 'register_popup_creator_post_type'));
      }

      public function register_popup_creator_post_type() {
            $labels = array(
                  'name'               => __('Popups Creator', 'popup-creator'),
                  'singular_name'      => __('Popup Creator', 'popup-creator'),
                  'featured_image'     => __('Popup Image', 'popup-creator'),
                  'set_featured_image' => __('Set Popup Image', 'popup-creator'),
                  'search_items'       => __('Search Popup', 'popup-creator'),
                  'all_items'          => __('All Popups', 'popup-creator'),
                  'add_new_item'       => __('Add New', 'popup-creator'),
                  'add_new'            => __('Add New', 'popup-creator'),
                  'new_item'           => __('New Popup', 'popup-creator'),
                  'edit_item'          => __('Edit Popup', 'popup-creator'),
                  'update_item'        => __('Update Popup', 'popup-creator'),
                  'view_item'          => __('View Popup', 'popup-creator'),
                  'remove_featured_image' => __('Remove Popup Image', 'popup-creator'),
            );
            $args = array(
                  'label'               => __('Popups', 'popup-creator'),
                  'description'         => __('Popup Description', 'popup-creator'),
                  'labels'              => $labels,
                  'supports'            => array('title', 'editor', 'thumbnail'),
                  'hierarchical'        => false,
                  'public'              => false,
                  'publicly_queryable'  => true,
                  'show_ui'             => true,
                  'show_in_menu'        => true,
                  'show_in_rest'        => false,
                  // 'rest_base'          => 'arpc_popup',
                  // 'rest_controller_class' => 'WP_REST_Posts_Controller',
                  'menu_position'       => 60,
                  'show_in_admin_bar'   => true,
                  'show_in_nav_menus'   => true,
                  'has_archive'         => false,
                  'exclude_from_search' => false,
                  'capability_type'     => 'post',
                  'rewrite'             => array('slug' => 'popup', 'with_front' => true),
                  'menu_icon'           => 'dashicons-screenoptions',
            );
            register_post_type('arpc_popup', $args);
      }
}
