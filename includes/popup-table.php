<?php
/**
 * Manage Popup creator columns
 */
function puc_add_columns( $columns ) {
      unset( $columns['date'] );
      unset( $columns['title'] );
      $columns['title']   = __( 'Popup Title', 'popup-creator' );
      $columns['active']  = __( 'Is Active', 'popup-creator' );
      $columns['show_on'] = __( 'Display On Page', 'popup-creator' );
      $columns['show_time'] = __( 'Display Time', 'popup-creator' );
      $columns['image']   = __( 'Thumbnail', 'popup-creator' );
      $columns['date']    = __( 'Date', 'popup-creator' );
      return $columns;
}

add_action( 'manage_popup_posts_columns', 'puc_add_columns' );

/**
 * Output table column values 
 */
function puc_column_content( $column, $post_id ) {
      switch( $column ) {
            case 'image':
                  echo get_the_post_thumbnail( $post_id, 'popup-creator-thumbnail' );
                  break;
            case 'show_on':
                  $page_id = get_post_meta( $post_id, 'pc_ww_show', true );
                  echo get_the_title( $page_id );
                  break;
            case 'show_time':
                  $show_time = get_post_meta( $post_id, 'pc_show_on_exit', true );
                  echo ( $show_time == 1 ? 'On Page Reload' : 'On Page Exit' );
                  break;
            case 'active':
                  $is_active = get_post_meta( $post_id, 'pc_active', true );
                  echo $is_active ? __( 'Yes', 'popup-creator' ) : __( 'No', 'popup-creator' );
                  break;
                  
            default:
                  break;
      }
}

add_filter( 'manage_popup_posts_custom_column', 'puc_column_content', 10, 2 );

/**
 * Making columns sortable 
 */
function puc_columns_sortable( $columns ) {
      $columns['show_on'] = 'show_on';
      $columns['active'] = 'active';
      $columns['show_time'] = 'show_time';
      
      return $columns;
}
add_filter( 'manage_edit-popup_sortable_columns', 'puc_columns_sortable' );

/**
 * Columns sorting logic
 */
function puc_columns_sorting_logic( $query ) {
      if( ! is_admin() || ! $query->is_main_query() ) {
            return;
      }
      
      if( 'show_on' === $query->get( 'orderby' ) ) {
            $query->set( 'orderby', 'meta_value' );
            $query->set( 'meta_key', 'pc_ww_show' );
      }
      
      if( 'active' === $query->get( 'orderby' ) ) {
            $query->set( 'orderby', 'meta_value' );
            $query->set( 'meta_key', 'pc_active' );
      }
      
      if( 'show_time' === $query->get( 'orderby' ) ) {
            $query->set( 'orderby', 'meta_value' );
            $query->set( 'meta_key', 'pc_show_on_exit' );
      }
}
add_action( 'pre_get_posts', 'puc_columns_sorting_logic' );