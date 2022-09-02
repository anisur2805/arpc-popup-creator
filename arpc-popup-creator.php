<?php

/**
 * Plugin Name: Popup Creator
 * Description: Awesome Popup Creator
 * Plugin URI:  http://github.com/anisur2805/arpc-popup-creator
 * Version:     1.0
 * Author:      Anisur Rahman
 * Author URI:  http://github.com/anisur2805
 * Text Domain: arpc-popup-creator
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

use ARPC\Popup\Admin;
use ARPC\Popup\Ajax;
use ARPC\Popup\Assets;
use ARPC\Popup\Frontend;
use ARPC\Popup\Installer;

if (!defined('ABSPATH')) {
      exit;
}

require_once __DIR__ . "/vendor/autoload.php";

final class ARPC_Popup_Creator {
      const version = '1.0';

      private function __construct() {
            $this->define_constants();
            add_action('plugins_loaded', array($this, 'init_plugin'));
            register_activation_hook(__FILE__, array($this, 'activate'));
      }

      /**
       * Initialize a singleton instance
       *
       * @return Popup_Creator
       */
      public static function init() {
            static $instance = false;

            if (!$instance) {
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
            define('ARPC_VERSION', self::version);
            define('ARPC_FILE', __FILE__);
            define("ARPC_PATH", __DIR__);
            define("ARPC_URL", plugins_url('', __FILE__));
            define("ARPC_ASSETS", ARPC_URL . '/assets');
            define("ARPC_INCLUDES", ARPC_URL . "/includes");
      }

      /**
       * Do stuff upon plugin installation
       */
      public function activate() {

            $installer = new Installer();
            $installer->run();

            $installed = get_option('arpc_installed');

            if (!$installed) {
                  update_option('arpc_installed', time());
            }

            update_option('arpc_version', ARPC_VERSION);
      }

      /**
       * Load plugin text domain 
       */
      public function init_plugin() {
            load_plugin_textdomain('arpc-popup-creator', false, plugin_dir_path(__FILE__) . 'languages');

            if (defined('DOING_AJAX') && DOING_AJAX) {
                  new Ajax();
            }

            if (is_admin()) {
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
 * @return ARPC_Popup_Creator
 */
function arpc_popup_creator() {
      return ARPC_Popup_Creator::init();
}

// kick-off the plugin
arpc_popup_creator();
