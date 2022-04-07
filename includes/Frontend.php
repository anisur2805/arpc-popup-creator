<?php 
namespace APC\Popup;

use APC\Popup\Frontend\Modal;
use APC\Popup\Frontend\Shortcode;

class Frontend {
      public function __construct() {
            
            new Modal();
            new Shortcode();
      } 
      
}
