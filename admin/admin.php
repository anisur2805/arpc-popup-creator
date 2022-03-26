<?php
namespace APC\Popup\Creator;
class Popup_Admin_Assets { 
      public function __construct() {
            add_action('admin_enqueue_scripts', array( $this, 'load_assets' ) );
      }
      /**
       * Includes plugin asset files
       */
      public function load_assets() {
            wp_enqueue_style('popup-admin-style', PUC_ADMIN_URL . '/css/metabox.css', time());
            // wp_enqueue_script('plain-modal', PUC_PUBLIC_URL . '/js/jquery.plainmodal.min.js', null, time(), true);
            // wp_enqueue_script('popup-creator-main', PUC_PUBLIC_URL . '/js/popup-main.js', array('jquery', 'plain-modal'), time(), true);
      }
}
