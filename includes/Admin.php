<?php

namespace ARPC\Popup;

use ARPC\Popup\Admin\Menu;
use ARPC\Popup\Data_Table;
use ARPC\Popup\Metabox;
use ARPC\Popup\Post_Type;

class Admin {
      public function __construct() {

            // Instantiate Data Table
            new Data_Table();
            
            // Instantiate Subscriber Data Table
            // new Subscriber_Data_Table();

            //Instantiate Meta box
            new Metabox();
            
            // Add Menu page 
            new Menu();
            
            // Instantiate Front End Popup
            new Post_Type();            
            
            if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
                  new Ajax();
            }
            
            add_action( 'admin_head', array( $this, 'load_assets' ) );
      }
      
      public function load_assets() {
            wp_enqueue_style( 'arpc-metabox' );
            wp_enqueue_script( 'arpc-main-ajax' );
      }
      
}
