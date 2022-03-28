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

use APC\Popup\Creator\Admin;
use APC\Popup\Creator\Assets;
use APC\Popup\Creator\Frontend;

if ( !defined( 'ABSPATH' ) ) {
      exit;
}

require_once __DIR__ . "/vendor/autoload.php";

      final class Popup_Creator {
            const version = '1.0';
            
            private function __construct() {
                  $this->define_constants();
                  add_action( 'plugins_loaded', array( $this, 'init_plugin') );
                  
                  register_activation_hook( __FILE__, array( $this, 'activate' ) );
            }
            
            /**
             * Initialize a singleton instance
             *
             * @return Popup_Creator
             */
            public static function init() {
                  static $instance = false;
                  
                  if ( ! $instance ) {
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
                  define( 'PUC_VERSION', self::version );
                  define( 'PUC_FILE', __FILE__ );
                  define( "PUC_PATH", __DIR__ );
                  define( "PUC_URL", plugins_url( '', __FILE__ ) );
                  define( "PUC_ASSETS", PUC_URL . '/assets' );
                  define( "PUC_INCLUDES", PUC_URL . "/includes" ); 
            }
            
            /**
             * Do stuff upon plugin installation
             */
            public function activate() {
                  $installed = get_option( 'puc_installed' );
                  
                  if( ! $installed ) {
                        update_option( 'puc_installed', time() );
                  }
                  
                  update_option( 'puc_version', PUC_VERSION );
            }
            

            /**
             * Load plugin text domain 
             */
            public function init_plugin() {
                  load_plugin_textdomain( 'popup-creator', false, plugin_dir_path( __FILE__ ) . 'languages' );
                  
                  if( is_admin() ) {
                        // Instantiate Meta box
                        new Admin();
                  } else {
                       // Instantiate Front End Popup
                        new Frontend();    
                  }
                  
                  new Assets();
                                 
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

// kick-off the plugin
puc_popup_creator();