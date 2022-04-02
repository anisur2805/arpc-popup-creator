<?php 
namespace APC\Popup;

use APC\Popup\Frontend\Modal;

class Frontend {
      public function __construct() {
            wp_enqueue_style( 'puc-style' );
            wp_enqueue_scripts( 'plain-modal' );
            wp_enqueue_script( 'puc-main' );

            new Modal();
      } 
      
}
