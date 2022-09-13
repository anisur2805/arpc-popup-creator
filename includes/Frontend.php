<?php 
namespace ARPC\Popup;

use ARPC\Popup\Frontend\Modal;
use ARPC\Popup\Frontend\Shortcode;

class Frontend {
      public function __construct() {
            new Modal();
            new Shortcode();
      } 
      
}
