<?php
namespace ARPC\Popup\Data_Table;

/*
 * Popup creator data table
 */
class Data_Table {
	/**
	 * Popup creator data table
	 */
	public function __construct() {
		add_action( 'manage_arpc_popup_posts_columns', array( $this, 'add_columns' ) );
		add_filter( 'manage_edit-arpc_popup_sortable_columns', array( $this, 'columns_sortable' ) );
		add_action( 'pre_get_posts', array( $this, 'columns_sorting_logic' ) );
		add_filter( 'manage_arpc_popup_posts_custom_column', array( $this, 'column_content' ), 10, 2 );
	}

	/**
	* Manage Popup creator columns
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
	* Output table column values
	*/
	public function column_content( $column, $post_id ) {
		switch ( $column ) {
			case 'image':
				echo get_the_post_thumbnail( $post_id, 'popup-creator-thumbnail' );
				break;
			case 'show_on':
					$page_id = get_post_meta( $post_id, 'arpc_ww_show', true );
					echo get_the_title( $page_id );
				break;
			case 'show_time':
					$show_time = get_post_meta( $post_id, 'arpc_show_on_exit', true );
					echo ( $show_time == 0 ? 'On Page Exit' : 'On Page Reload' );
				break;
			case 'active':
					$is_active = get_post_meta( $post_id, 'arpc_active', true );
					echo $is_active ? __( 'Yes', 'arpc-popup-creator' ) : __( 'No', 'arpc-popup-creator' );
				break;

			default:
				break;
		}
	}

	/**
	* Making columns sortable
	*/
	public function columns_sortable( $columns ) {
		$columns['show_on']   = 'show_on';
		$columns['active']    = 'active';
		$columns['show_time'] = 'show_time';

		return $columns;
	}

	/**
	* Columns sorting logic
	*/
	public function columns_sorting_logic( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
				return;
		}

		if ( 'show_on' === $query->get( 'orderby' ) ) {
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'meta_key', 'arpc_ww_show' );
		}

		if ( 'active' === $query->get( 'orderby' ) ) {
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'meta_key', 'arpc_active' );
		}

		if ( 'show_time' === $query->get( 'orderby' ) ) {
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'meta_key', 'arpc_show_on_exit' );
		}
	}
}
