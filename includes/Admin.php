<?php

namespace APC\Popup\Creator;

use APC\Popup\Creator\Data_Table;
use APC\Popup\Creator\Metabox;
use APC\Popup\Creator\Post_Type;

class Admin {
      public function __construct() {

            // Instantiate Data Table
            new Data_Table();

            //Instantiate Meta box
            new Metabox();
            
            wp_enqueue_style( 'puc-metabox' );
            wp_enqueue_script( 'puc-main-ajax' );

            // Instantiate Front End Popup
            new Post_Type();
      }
}
