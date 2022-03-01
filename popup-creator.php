<?php
/**
 * Plugin Name: PC Popup Creator
 * Description: Awesome Desc...
 * Plugin URI:  http://github.com/#
 * Version:     1.0
 * Author:      Anisur Rahman
 * Author URI:  http://github.com/anisur2805/
 * Text Domain: popup-creator
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Plugin Type: Piklist
*/

if ( !defined( 'ABSPATH' ) ) {
      exit;
}

// Constant Variables
define("PUC_DIR_URL", plugin_dir_url(__FILE__) . "assets");
define("PUC_IMG_URL", PUC_DIR_URL . "/images");

class Popup_Creator {
      public function __construct() {
            add_action('plugin_loaded', [$this, 'load_text_domain']);
            add_action('init', [ $this, 'register_popup_creator' ]);
            add_action('init', [ $this, 'register_image_size' ]);
            add_action('wp_enqueue_scripts', [ $this, 'load_assets' ]);
            add_action('wp_footer', [ $this, 'load_modal_on_footer' ]);
      }
      
      public function load_modal_on_footer() {
            $args = array(
                  'post_type'       => 'popup',
                  'post_status'     => 'publish',
                  'meta_key'        => 'pc_active',
                  'meta_value'      => 1,
            );
            
            $pc_query = new WP_Query($args);
            while($pc_query->have_posts()) {
                  $pc_query->the_post();
                  $image_size = get_post_meta(get_the_ID(), 'pc_image_size', true);
                  $exit = get_post_meta(get_the_ID(), 'pc_show_on_exit', true);
                  $delay = get_post_meta(get_the_ID(), 'pc_show_in_delay', true);
                  $feature_image = get_the_post_thumbnail_url( get_the_ID(), $image_size );
                  $pc_url = get_post_meta(get_the_ID(), 'pc_url', true );
                  $show_in = get_post_meta(get_the_ID(), 'pc_ww_show', true );
                  
                  if( $delay ) {
                        $delay *= 1000;
                  } else {
                        $delay = 0;
                  }
                  
                  if( $show_in ) {
                        $show_in = get_post( $show_in );
                  }
                  
                  
                  
                  ?>
                  <div class="popup-creator" id="popup-creator" data-id="popup-<?php echo get_the_ID(); ?>" data-popup-size="<?php echo esc_attr( $image_size ); ?>" data-exit="<?php echo esc_attr( $exit ); ?>" data-delay="<?php echo esc_attr( $delay, $show_in ); ?>">
                        <?php if( $pc_url ) { ?>
                              <a target="_blank" href="<?php echo esc_url( $pc_url); ?>">
                                    <img src="<?php echo esc_url( $feature_image ); ?>" alt="<?php _e('Popup', 'popup-creator') ?>" />
                              </a>
                        <?php } else {?>
                              <img src="<?php echo esc_url( $feature_image ); ?>" alt="<?php _e('Popup', 'popup-creator') ?>" />
                              <?php } ?>
                        <button class="close-button">
                              <img src="<?php echo esc_url( PUC_IMG_URL . '/close.svg') ?>" alt="Close" />
                        </button>
                  </div>
      
                  <?php 
                  
            }
            
            wp_reset_query();
           
      }
      
      public function load_assets() {
            wp_enqueue_style('popup-creator-style', PUC_DIR_URL . '/css/popup-creator.css', time());
            wp_enqueue_script('plain-modal', PUC_DIR_URL . '/js/jquery.plainmodal.min.js', null,  time(), true );
            wp_enqueue_script('popup-creator-main', PUC_DIR_URL . '/js/popup-main.js', array('jquery', 'plain-modal'),  time(), true );
      }
      
      
      public function register_image_size() {
            add_image_size('popup-creator-landscape', 800, 600, true );
            add_image_size('popup-creator-square', 500, 500, true );
      }
      
      
      public function load_text_domain() {
            load_plugin_textdomain('popup-creator', false, plugin_dir_path(__FILE__) . 'languages');
      }
      
      
      public function register_popup_creator() {
            $labels = array(
                  'name'                  => _x('Popups Creator', 'popup-creator'),
                  'singular_name'         => _x('Popup', 'popup-creator'),
                  'featured_image'        => __('Popup Image'),
                  'set_featured_image'        => __('Set Popup Image'),
            );
            $args = array(
                  'label'                 => __('Popups', 'popup-creator'),
                  'description'           => __('Popup Description', 'popup-creator'),
                  'labels'                => $labels,
                  'supports'              => array('title', 'thumbnail'),
                  'hierarchical'          => false,
                  'public'                => false,
                  'publicly_queryable'    => true,
                  'show_ui'               => true,
                  'show_in_menu'          => true,
                  'menu_position'         => 7,
                  'show_in_admin_bar'     => true,
                  'show_in_nav_menus'     => true,
                  'has_archive'           => false,
                  'exclude_from_search'   => false,
                  'capability_type'       => 'post',
                  'rewrite'               => array('slug' => 'popup', 'with_front' => true),
            );
            register_post_type('popup', $args);
      }
      
}

function init_plugin() {
      new Popup_Creator();
}
init_plugin();

