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
        $actions = [
            'bulk-delete'     => __( 'Move to Trash', 'arpc-popup-creator' ),
        ];

        return $actions;
    }

    public function subscriber_bulk_delete() {
        return 'Hello world';
    }

    public function delete_the_things() {
        echo "deleted";
    }
    
    

    /**
	 * Get bulk actions.
	 *
	 * @return void
	 */
	public function process_bulk_action() {

        if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'bulk-' . sanitize_key( 'bulk-delete' ) ) ) {

            if ( ( isset( $_GET['action'] ) && 'bulk-delete' === $_GET['action'] ) ||
                 ( isset( $_GET['action2'] ) && 'bulk-delete' === $_GET['action2'] ) ) {
    
                if ( empty( $_GET['bulk-item-selection'] ) ) {
    
                    return;
    
                }
    
                $delete_error = delete_the_things();
    
                $sendback = remove_query_arg( array( 'action', 'action2', '_wpnonce', '_wp_http_referer', 'bulk-item-selection', 'delete_id', 'updated' ), wp_get_referer() );
    
                if ( false === $delete_error ) {
    
                    $sendback = add_query_arg( 'deleted', 'error', $sendback );
    
                } else {
    
                    $sendback = add_query_arg( 'deleted', 'success', $sendback );
    
                }
    
                wp_redirect( $sendback );
                exit();
    
            }
        }

    }

    /**
	 * Stop execution and exit
	 *
	 * @since    1.0.0
	 * 
	 * @return void
	 */    
	public function graceful_exit() {
        exit;
    }

    /**
	 * Die when the nonce check fails.
	 *
	 * @since    1.0.0
	 * 
	 * @return void
	 */    	 
	 public function invalid_nonce_redirect() {
		wp_die( __( 'Invalid Nonce', $this->plugin_text_domain ),
				__( 'Error', $this->plugin_text_domain ),
				array( 
						'response' 	=> 403, 
						'back_link' =>  esc_url( add_query_arg( array( 'page' => wp_unslash( $_REQUEST['page'] ) ) , admin_url( 'edit.php?post_type=arpc_popup&page=arpc-popup-subscribers' ) ) ),
					)
		);
	 }

    private function fetch_subscribers_data( $search = '' ) {

        global $wpdb;

        if ( !empty( $search ) ) {
            $orderby = ( isset( $_GET[ 'orderby' ] ) ) ? esc_sql( $_GET[ 'orderby' ] ) : 'name';
            $order = ( isset( $_GET[ 'order' ] ) ) ? esc_sql( $_GET[ 'order' ] ) : 'ASC';

            //  ORDER BY $orderby $order
            return $wpdb->get_results(
                "SELECT id, name, email, created_at from {$wpdb->prefix}arpc_subscriber WHERE id LIKE '%{$search}%' OR name Like '%{$search}%' OR email Like '%{$search}%' OR created_at Like '%{$search}%'", ARRAY_A );

        } else {

            return $wpdb->get_results( "SELECT id, name, email, created_at from {$wpdb->prefix}arpc_subscriber", ARRAY_A );

        }
    }

    /**
	 * Handles data query and filter, sorting, and pagination.
	 */
    public function prepare_items() {

        $this->_column_headers = $this->get_column_info();

        if ( isset( $_GET['page'] ) && isset( $_GET['s'] ) ) {
            $this->users_data = $this->fetch_subscribers_data( $_GET['s'] );
        } else {
            $this->users_data = $this->fetch_subscribers_data();
        }

        $this->process_bulk_action();

        if ( ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'bulk-delete' ) 
        || ( isset( $_REQUEST['action2'] ) && $_REQUEST['action2'] === 'bulk-delete' ) ) {

            echo "hey";
            die("die");
            $nonce = wp_unslash( $_REQUEST['_wpnonce'] );

            // if ( ! wp_verify_nonce( $nonce, 'bulk-delete' ) ) {
			// 	$this->invalid_nonce_redirect();
			// }
			// else {
			// 	$this->subscriber_bulk_delete( $_REQUEST['users']);
			// 	$this->graceful_exit();
			// }

        } else {
            // die("again");
        }

        $columns  = $this->get_columns();
        $hidden   = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        usort( $this->users_data, [$this, 'sort_data'] );

        $perPage     = $this->get_items_per_page( 'arpc_subscribers_per_page' );

        $currentPage = $this->get_pagenum();
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
        return "<input type='checkbox' name='bulk-delete[]' value='{$item["id"]}'/>";
    }

    public function column_created_at( $item ) {
      // print_r($item);
        return $item['created_at'];
    }

    public function column_name( $item ) {

        // create a nonce
        $delete_nonce   = wp_create_nonce( 'arpc_delete_sub' );
        
        $actions = [];
        $actions['delete'] = sprintf( 
            '<a class="arpc-subscriber-delete" data-id="%s" href="#" title="%s">%s</a>', 
            $item['id'],
            __( 'Delete', 'arpc-popup-creator' ), 
            __( 'Delete', 'arpc-popup-creator' ) 
        );

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
            'id'    => array( 'id', true ),
            'name'          => array( 'name', true ),
            'email'         => array( 'email', true ),
            'created_at'    => array( 'created_at', true ),
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

    /**
        * Delete a subscriber record.
        *
        * @param int $id subscriber ID
    */
    public static function delete_subscriber( $id ) {
        global $wpdb;
        
        $wpdb->delete(
            "{$wpdb->prefix}arpc_subscriber",
            [ 'ID' => $id ],
            [ '%d' ]
        );
    }

    private function sort_data( $a, $b ) {

        $orderby    = ( !empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'name';
        $order      = ( !empty( $_GET['order'] ) ) ? $_GET['order'] : 'asc';
        $result     = strcmp( $a[$orderby], $b[$orderby] );

        return $order === 'asc' ? $result : - $result;

    }
}
