<?php 
namespace APC\Popup\Creator;

use APC\Popup\Creator\Frontend\Modal;

class Frontend {
      public function __construct() {
            add_action('wp_enqueue_scripts', array( $this, 'load_assets' ) );
            
           
            
      }
      
      
      /**
       * Includes plugin asset files
       */
      public function load_assets() {
      new Modal();
            
            wp_enqueue_style('popup-creator-style', PUC_PUBLIC_URL . '/css/popup-creator.css', time());
            wp_enqueue_script('plain-modal', PUC_PUBLIC_URL . '/js/jquery.plainmodal.min.js', null, time(), true);
            wp_enqueue_script('popup-creator-main', PUC_PUBLIC_URL . '/js/popup-main.js', array('jquery', 'plain-modal'), time(), true);
      }
}
