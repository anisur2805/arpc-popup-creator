<?php
/**
 * Popup list table columns.
 *
 * @package ARPC\Popup
 */

namespace ARPC\Popup\Data_Table;

/**
 * Popup creator data table
 */
class Data_Table {

	/**
	 * Constructor - register hooks.
	 */
	public function __construct() {
		add_action( 'manage_arpc_popup_posts_columns', array( $this, 'add_columns' ) );
		add_filter( 'manage_edit-arpc_popup_sortable_columns', array( $this, 'columns_sortable' ) );
		add_action( 'pre_get_posts', array( $this, 'columns_sorting_logic' ) );
		add_filter( 'manage_arpc_popup_posts_custom_column', array( $this, 'column_content' ), 10, 2 );
	}

	/**
	 * Manage Popup creator columns.
	 *
	 * @param array $columns Existing columns.
	 * @return array
	 */
	public function add_columns( $columns ) {
		unset( $columns['date'] );
		unset( $columns['title'] );

		$columns['title']     = __( 'Popup Name', 'arpc-popup-creator' );
		$columns['active']    = __( 'Is Active', 'arpc-popup-creator' );
		$columns['show_on']   = __( 'Display On Page', 'arpc-popup-creator' );
		$columns['show_time'] = __( 'Display Time', 'arpc-popup-creator' );
		$columns['image']     = __( 'Thumbnail', 'arpc-popup-creator' );
		$columns['date']      = __( 'Date', 'arpc-popup-creator' );

		return $columns;
	}

	/**
	 * Output table column values.
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 */
	public function column_content( $column, $post_id ) {
		switch ( $column ) {
			case 'image':
				echo get_the_post_thumbnail( $post_id, 'popup-creator-thumbnail' );
				break;
			case 'show_on':
				$page_id = get_post_meta( $post_id, 'arpc_ww_show', true );
				echo esc_html( get_the_title( $page_id ) );
				break;
			case 'show_time':
				$show_time = get_post_meta( $post_id, 'arpc_show_on_exit', true );
				echo esc_html( '0' === (string) $show_time ? __( 'On Page Exit', 'arpc-popup-creator' ) : __( 'On Page Reload', 'arpc-popup-creator' ) );
				break;
			case 'active':
				$is_active = get_post_meta( $post_id, 'arpc_active', true );
				echo esc_html( $is_active ? __( 'Yes', 'arpc-popup-creator' ) : __( 'No', 'arpc-popup-creator' ) );
				break;
			default:
				break;
		}
	}

	/**
	 * Make columns sortable.
	 *
	 * @param array $columns Sortable columns.
	 * @return array
	 */
	public function columns_sortable( $columns ) {
		$columns['active']    = 'active';
		$columns['show_on']   = 'show_on';
		$columns['show_time'] = 'show_time';
		$columns['image']     = 'image';

		return $columns;
	}

	/**
	 * Column sorting logic.
	 *
	 * @param \WP_Query $query WP_Query instance.
	 */
	public function columns_sorting_logic( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$orderby = $query->get( 'orderby' );

		switch ( $orderby ) {
			case 'active':
			case 'show_on':
			case 'show_time':
				$query->set( 'meta_key', 'arpc_show_in_delay' );
				$query->set( 'orderby', 'meta_value' );
				break;
		}
	}
}
