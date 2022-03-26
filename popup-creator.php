<?php
 /**
  * Plugin Name: PC Popup Creator
  * Description: Awesome Popup Creator
  * Plugin URI:  http://github.com/anisur2805/pc-popup-creator
  * Version:     1.0
  * Author:      Anisur Rahman
  * Author URI:  http://github.com/anisur2805
  * Text Domain: popup-creator
  * License:     GPL v2 or later
  * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
  */

use APC\Popup\Creator\Data_Table;
use APC\Popup\Creator\Frontend;
use APC\Popup\Creator\Popup_Admin_Assets;
use APC\Popup\Creator\Popup_Assets;
use APC\Popup\Creator\Popup_MetaBox;
use APC\Popup\Creator\Popup_Post_Type;

if ( !defined( 'ABSPATH' ) ) {
      exit;
}
require_once __DIR__ . "/vendor/autoload.php";

// require_once PUC_DIR_PATH . 'includes/popup-table.php';
// require_once PUC_DIR_PATH . 'includes/class.metabox.php';
// require_once PUC_DIR_PATH . 'includes/class.front-end.php';
// require_once PUC_DIR_PATH . 'includes/class.post-type.php';
// require_once PUC_DIR_PATH . 'admin/admin.php';
// require_once PUC_DIR_PATH . 'public/public.php';

      final class Popup_Creator {
            public function __construct() {
                  $this->define_constants();
                  add_action( 'plugins_loaded', array( $this, 'init_plugin') );
            }
            
            /**
             * Initialize a singleton instance
             *
             * @return Popup_Creator
             */
            public static function init() {
                  static $instance = false;
                  if ( !$instance ) {
                  $instance = new self();
                  }

                  return $instance;
            }
            
            /**
             * define plugin require constants
             *
             * @return void
             */
            public function define_constants() {
                  define( "PUC_DIR_PATH", plugin_dir_path( __FILE__ ) );
                  define( "PUC_DIR_URL", plugin_dir_url( __FILE__ ) );
                  define( "PUC_PUBLIC_URL", PUC_DIR_URL . 'public' );
                  define( "PUC_ADMIN_URL", PUC_DIR_URL . 'admin' );
                  define( "PUC_IMG_URL", PUC_PUBLIC_URL . "/images" ); 
            }

            /**
             * Load plugin text domain 
             */
            public function init_plugin() {
                  load_plugin_textdomain( 'popup-creator', false, plugin_dir_path( __FILE__ ) . 'languages' );
                  
                  if( is_admin() ) {
                        // Instantiate Meta box
                        new Popup_Admin_Assets();
                        
                        // Instantiate Data Table
                        new Data_Table();

                        //Instantiate Meta box
                        new Popup_MetaBox();
                        
                        // Instantiate Front End Popup
                        new Popup_Post_Type();
                        
                  } else {
                       
                  }
                  // Instantiate Front End Popup
                   new Frontend();
                        
                   // Enqueue admin assets
                   new Popup_Assets();                    
            }
           
      }
            
/**
 * Initialize the main plugin
 *
 * @return Popup_Creator
 */
function puc_popup_creator() {
      return Popup_Creator::init();
}
puc_popup_creator();