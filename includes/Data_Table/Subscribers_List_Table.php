<?php
namespace ARPC\Popup\Data_Table;

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Subscribers_List_Table extends \WP_List_Table {

    private $_items;
    private $users_data;

    public function __construct() {
        parent::__construct( [
            'singular'  => 'subscriber',
            'plural'    => 'subscribers',
            'ajax'      => false,
        ] );
    }

    /**
	 * Get bulk actions.
	 *
	 * @return array
	 */
    public function get_bulk_actions() {
        return [
            'trash'     => __( 'Move to Trash', 'arpc-popup-creator' ),
        ];
    }

    /**
	 * Get bulk actions.
	 *
	 * @return void
	 */
	public function process_bulk_action() {

        if ( 'trash' === $this->current_action() ) {
			$post_ids = filter_input( INPUT_GET, 'draft_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

			if ( is_array( $post_ids ) ) {
				$post_ids = array_map( 'intval', $post_ids );

				if ( count( $post_ids ) ) {
					array_map( 'wp_trash_post', $post_ids );
				}
			}
            
		}

    }    

    private function fetch_subscribers_data( $search = '' ) {
        global $wpdb;
        if ( !empty( $search ) ) {
            $orderby = ( isset( $_GET[ 'orderby' ] ) ) ? esc_sql( $_GET[ 'orderby' ] ) : 'name';
            $order = ( isset( $_GET[ 'order' ] ) ) ? esc_sql( $_GET[ 'order' ] ) : 'ASC';

            return $wpdb->get_results(
                "SELECT id, name, email, created_at from {$wpdb->prefix}arpc_subscriber WHERE id LIKE '%{$search}%' OR name Like '%{$search}%' OR email Like '%{$search}%' OR created_at Like '%{$search}%' ORDER BY $orderby $order", ARRAY_A );
        } else {
            return $wpdb->get_results(
                "SELECT id, name, email, created_at from {$wpdb->prefix}arpc_subscriber", ARRAY_A );
        }
    }

    public function prepare_items() {

        if ( isset( $_GET['page'] ) && isset( $_GET['s'] ) ) {
            $this->users_data = $this->fetch_subscribers_data( $_GET['s'] );
        } else {
            $this->users_data = $this->fetch_subscribers_data();
        }

        $columns  = $this->get_columns();
        $hidden   = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        // usort( $this->users_data, [$this, 'sort_data'] );

        $perPage     = $this->get_items_per_page( 'arpc_subscribers_per_page' );

        $currentPage = $this->get_pagenum();
        // $totalItems  = count($this->_items);
        $totalItems = count( $this->users_data );

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage,
            'total_pages' => ceil( $totalItems / $perPage ),
        ) );

        // $data = array_slice($this->_items, ($currentPage - 1) * $perPage, $perPage);
        $this->users_data      = array_slice( $this->users_data, ( $currentPage - 1 ) * $perPage, $perPage );
        $this->_column_headers = array( $columns, $hidden, $sortable );
        $this->items           = $this->users_data;
        // $this->table_data($data);
    }

    public function get_columns() {

        $columns = array(
            'cb'    => '<input type="checkbox" />',
            'name'  => __( 'Name', 'arpc-popup-creator' ),
            'email' => __( 'Email', 'arpc-popup-creator' ),
            'created_at'  => __( 'Date', 'arpc-popup-creator' ),
        );

        return $columns;

    }

    public function column_cb( $item ) {
        return "<input type='checkbox' value='{$item["id"]}'/>";
    }

    public function column_date( $item ) {
      // print_r($item);
        return $item['created_at'];
    }

    public function column_name( $item ) {

        $actions = [];
        $actions['delete'] = sprintf( '<a class="arpc-subscriber-delete" data-id="%s" href="#" title="%s">%s</a>', $item['id'], __( 'Delete', 'arpc-popup-creator' ), __( 'Delete', 'arpc-popup-creator' ) );

        return sprintf( '<strong>%1$s</strong>%2$s', $item['name'], $this->row_actions( $actions ) );

    }

    public function no_items() {
        _e( 'No subscribers available.', $this->plugin_text_domain );
    }

    public function get_hidden_columns() {
        return array();
    }

    protected function get_sortable_columns() {
        /*
            * actual sorting still needs to be done by prepare_items.
            * specify which columns should have the sort icon.	
        */

        $sortable_columns = array(
            // 'id'    => array( 'id', true ),
            'name'          => array( 'name', true ),
            'email'         => array( 'email', true ),
            'created_at'    => array( 'date', true ),
        );

        return $sortable_columns;

    }

    public function arpc_user_search( $item ) {
        $name        = strtolower( $item['name'] );
        $search_name = sanitize_text_field( $_REQUEST['s'] );
        if ( strpos( $name, $search_name ) !== false ) {
            return true;
            wp_die( "You gonna die" );
        }
        return false;
    }

    public function filter_callback( $item ) {
        $director = $_REQUEST['filter_s'] ? $_REQUEST['filter_s'] : 'all';
        $director = strtolower( $director );

        if ( 'all' == $director ) {
            return true;
        } else {
            if ( $director == $item['director'] ) {
                return true;
            }
        }

        return false;

    }

    private function table_data( $data ) {
        global $wpdb;
        $data = $wpdb->get_results( $wpdb->prepare( "SELECT name FROM {$wpdb->prefix}persons" ), ARRAY_A );

        if ( isset( $_REQUEST['s'] ) ) {
            $data2 = array_filter( $data, array( $this, 'arpc_user_search' ) );
        }

        return $data2;
    }

    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'value':
                break;
                
            default:
                return isset( $item[$column_name] ) ? $item[$column_name] : '';
        }

        // return $item[$column_name];
    }

    private function sort_data( $a, $b ) {
        $orderby = 'name';
        $order   = 'asc';

        if ( !empty( $_GET['orderby'] ) ) {
            $orderby = $_GET['orderby'];
        }

        if ( !empty( $_GET['order'] ) ) {
            $order = $_GET['order'];
        }

        $result = strcmp( $a[$orderby], $b[$orderby] );

        if ( $order === 'asc' ) {
            return $result;
        }

        return $result;
    }
}
