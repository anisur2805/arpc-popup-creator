<?php

namespace APC\Popup;

use APC\Popup\Admin\Menu;
use APC\Popup\Data_Table;
use APC\Popup\Metabox;
use APC\Popup\Post_Type;

class Admin {
      public function __construct() {

            // Instantiate Data Table
            new Data_Table();

            //Instantiate Meta box
            new Metabox();
            
            // Add Menu page 
            new Menu();
            
            if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
                  new Ajax();
            }
            
            wp_enqueue_style( 'puc-metabox' );
            wp_enqueue_script( 'puc-main-ajax' );

            // Instantiate Front End Popup
            new Post_Type();
      }
}
