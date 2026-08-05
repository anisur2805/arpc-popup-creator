<?php
/**
 * Subscribers list table.
 *
 * @package ARPC\Popup\Data_Table
 */

namespace ARPC\Popup\Data_Table;

use ARPC\Popup\Models\Subscriber;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class Subscribers_List_Table
 *
 * @package ARPC\Popup\Data_Table
 */
class Subscribers_List_Table extends \WP_List_Table {

	/**
	 * Rows fetched for the current screen.
	 *
	 * @var array
	 */
	private $users_data = array();

	/**
	 * Subscribers_List_Table constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'subscriber',
				'plural'   => 'subscribers',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Get bulk actions.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = array(
			'bulk-delete' => __( 'Move to Trash', 'arpc-popup-creator' ),
		);

		return $actions;
	}

	/**
	 * Process the bulk delete action.
	 *
	 * @return void
	 */
	public function process_bulk_action() {
		if ( 'bulk-delete' !== $this->current_action() ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to delete subscribers.', 'arpc-popup-creator' ) );
		}

		// WP_List_Table renders its bulk nonce as 'bulk-' . the plural arg.
		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'bulk-' . $this->_args['plural'] ) ) {
			$this->invalid_nonce_redirect();
		}

		$ids = isset( $_REQUEST['bulk-delete'] ) ? array_map( 'absint', (array) wp_unslash( $_REQUEST['bulk-delete'] ) ) : array();
		$ids = array_filter( $ids );

		if ( empty( $ids ) ) {
			return;
		}

		Subscriber::bulk_delete( $ids );

		$sendback = remove_query_arg(
			array( 'action', 'action2', '_wpnonce', '_wp_http_referer', 'bulk-delete', 'delete_id', 'deleted' ),
			wp_get_referer()
		);

		wp_safe_redirect( add_query_arg( 'deleted', count( $ids ), $sendback ) );
		exit;
	}

	/**
	 * Die when the nonce check fails.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function invalid_nonce_redirect() {
		wp_die(
			esc_html__( 'Invalid Nonce', 'arpc-popup-creator' ),
			esc_html__( 'Error', 'arpc-popup-creator' ),
			array(
				'response'  => 403,
				'back_link' => esc_url( admin_url( 'edit.php?post_type=arpc_popup&page=arpc-popup-subscribers' ) ),
			)
		);
	}

	/**
	 * Fetch subscriber rows, optionally filtered by a search term.
	 *
	 * @param string $search Search term.
	 * @return array
	 */
	private function fetch_subscribers_data( $search = '' ) {

		global $wpdb;

		// popup_id only exists once the migration has run on this install.
		$popup_column = Subscriber::has_column( 'popup_id' ) ? 'popup_id,' : '';

		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared -- Column list comes from a hardcoded allow-list; the search term is bound via $wpdb->prepare() below.
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom plugin table.
		if ( ! empty( $search ) ) {
			$like = '%' . $wpdb->esc_like( $search ) . '%';

			$query = $wpdb->prepare(
				"SELECT id, name, email, interests, {$popup_column} created_at
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

			$results = $wpdb->get_results( $query, ARRAY_A );
		} else {
			$results = $wpdb->get_results( "SELECT id, name, email, interests, {$popup_column} created_at FROM {$wpdb->prefix}arpc_subscriber", ARRAY_A );
		}
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared

		return is_array( $results ) ? $results : array();
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 *
	 * @return void
	 */
	public function prepare_items() {

		$this->process_bulk_action();

		$this->_column_headers = $this->get_column_info();

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only list search.
		$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

		$this->users_data = $this->fetch_subscribers_data( $search );

		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		usort( $this->users_data, array( $this, 'sort_data' ) );

		$per_page     = $this->get_items_per_page( 'arpc_subscribers_per_page' );
		$current_page = $this->get_pagenum();
		$total_items  = count( $this->users_data );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => (int) ceil( $total_items / max( 1, $per_page ) ),
			)
		);

		$this->users_data      = array_slice( $this->users_data, ( $current_page - 1 ) * $per_page, $per_page );
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = $this->users_data;
	}

	/**
	 * Table columns.
	 *
	 * @return array
	 */
	public function get_columns() {

		$columns = array(
			'cb'         => '<input type="checkbox" />',
			'name'       => __( 'Full Name', 'arpc-popup-creator' ),
			'popup'      => __( 'Popup', 'arpc-popup-creator' ),
			'email'      => __( 'Email', 'arpc-popup-creator' ),
			'interests'  => __( 'Interests', 'arpc-popup-creator' ),
			'created_at' => __( 'Subscribed On', 'arpc-popup-creator' ),
		);

		return $columns;
	}

	/**
	 * Render the row-selection checkbox.
	 *
	 * @param array $item Row data.
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="bulk-delete[]" value="%d" />', absint( $item['id'] ) );
	}

	/**
	 * Render the originating popup for a subscriber row.
	 *
	 * @param array $item Row data.
	 * @return string
	 */
	public function column_popup( $item ) {
		$popup_id = isset( $item['popup_id'] ) ? absint( $item['popup_id'] ) : 0;

		if ( ! $popup_id ) {
			return '—';
		}

		$title = get_the_title( $popup_id );

		if ( '' === $title ) {
			return '—';
		}

		$edit_link = get_edit_post_link( $popup_id );

		if ( ! $edit_link ) {
			return esc_html( $title );
		}

		return sprintf( '<a href="%1$s">%2$s</a>', esc_url( $edit_link ), esc_html( $title ) );
	}

	/**
	 * Render the interests column.
	 *
	 * @param array $item Row data.
	 * @return string
	 */
	public function column_interests( $item ) {
		return ! empty( $item['interests'] ) ? esc_html( $item['interests'] ) : '—';
	}

	/**
	 * Render the subscription date column.
	 *
	 * @param array $item Row data.
	 * @return string
	 */
	public function column_created_at( $item ) {
		return ! empty( $item['created_at'] ) ? esc_html( $item['created_at'] ) : '—';
	}

	/**
	 * Render the name column with its row actions.
	 *
	 * @param array $item Row data.
	 * @return string
	 */
	public function column_name( $item ) {
		// The delete request is nonced client-side via the localised arpc-admin-subscriber nonce.
		$actions = array(
			'delete' => sprintf(
				'<a class="arpc-subscriber-delete" data-id="%1$d" href="#" title="%2$s">%2$s</a>',
				absint( $item['id'] ),
				esc_attr__( 'Delete', 'arpc-popup-creator' )
			),
		);

		return sprintf(
			'<strong>%1$s</strong>%2$s',
			esc_html( $item['name'] ),
			$this->row_actions( $actions )
		);
	}

	/**
	 * Message shown when there are no subscribers.
	 *
	 * @return void
	 */
	public function no_items() {
		esc_html_e( 'No subscribers available.', 'arpc-popup-creator' );
	}

	/**
	 * Columns hidden by default.
	 *
	 * @return array
	 */
	public function get_hidden_columns() {
		return array();
	}

	/**
	 * Sortable columns. Actual sorting happens in prepare_items().
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		$sortable_columns = array(
			'id'         => array( 'id', true ),
			'name'       => array( 'name', true ),
			'email'      => array( 'email', true ),
			'popup'      => array( 'popup_id', true ),
			'created_at' => array( 'created_at', true ),
		);

		return $sortable_columns;
	}

	/**
	 * Fallback renderer for columns without a dedicated method.
	 *
	 * @param array  $item        Row data.
	 * @param string $column_name Column key.
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		return isset( $item[ $column_name ] ) ? esc_html( $item[ $column_name ] ) : '';
	}

	/**
	 * Sort callback for the fetched rows.
	 *
	 * @param array $a First row.
	 * @param array $b Second row.
	 * @return int
	 */
	private function sort_data( $a, $b ) {
		$sortable = $this->get_sortable_columns();

		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Read-only list sorting.
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : 'name';
		$order   = ( ! empty( $_GET['order'] ) ) ? sanitize_key( wp_unslash( $_GET['order'] ) ) : 'asc';
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		// Map the column key to the underlying data key (e.g. 'popup' => 'popup_id').
		if ( isset( $sortable[ $orderby ][0] ) ) {
			$orderby = $sortable[ $orderby ][0];
		}

		if ( ! isset( $a[ $orderby ], $b[ $orderby ] ) ) {
			$orderby = 'name';
		}

		$result = strcmp( (string) $a[ $orderby ], (string) $b[ $orderby ] );

		return 'asc' === $order ? $result : -$result;
	}
}
