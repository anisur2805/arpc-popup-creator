<?php 
namespace APC\Popup\Creator;

use APC\Popup\Creator\Frontend\Modal;

class Frontend {
      public function __construct() {
                        
            wp_enqueue_style( 'puc-style' );
            wp_enqueue_script( 'plain-modal' );
            wp_enqueue_script( 'puc-main' );
            
            new Modal();
      }
}
