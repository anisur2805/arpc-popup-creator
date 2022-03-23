<?php
 /**
  * Plugin Name: PC Popup Creator
  * Description: Awesome Popup Creator
  * Plugin URI:  http://github.com/pc-popup-creator
  * Version:     1.0
  * Author:      Anisur Rahman
  * Author URI:  http://github.com/anisur2805
  * Text Domain: popup-creator
  * License:     GPL v2 or later
  * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
  * Plugin Type: Piklist
  */

 if ( !defined( 'ABSPATH' ) ) {
  exit;
 }

 // Constant Variables
 define( "PUC_DIR_PATH", plugin_dir_path( __FILE__ ) );
 define( "PUC_DIR_URL", plugin_dir_url( __FILE__ ) );
 define( "PUC_PUBLIC_URL", PUC_DIR_URL . 'public' );
 define( "PUC_ADMIN_URL", PUC_DIR_URL . 'admin' );
 define( "PUC_IMG_URL", PUC_PUBLIC_URL . "/images" ); 
 
//  die('PUC_PUBLIC_URL' .PUC_PUBLIC_URL);
//  die('PUC_ADMIN_URL' .PUC_ADMIN_URL);
 
 require_once PUC_DIR_PATH . 'includes/popup-table.php';
 require_once PUC_DIR_PATH . 'includes/class.metabox.php';
 require_once PUC_DIR_PATH . 'admin/admin.php';
 require_once PUC_DIR_PATH . 'public/public.php';
 
 class Popup_Creator {
  public function __construct() {
   add_action( 'plugin_loaded', [$this, 'load_text_domain'] );
   add_action( 'init', [$this, 'register_popup_creator'] );
   add_action( 'init', [$this, 'register_image_size'] );
   add_action( 'wp_footer', [$this, 'load_modal_on_footer'] );
    
   /**
    * Instantiate Meta box
    */
    new Popup_Admin_Assets();
    
   /**
    * Instantiate Meta box
    */
   new Popup_MetaBox();
   
  }

  public function load_modal_on_footer() {
   $args = array(
    'post_type'   => 'popup',
    'post_status' => 'publish',
    'meta_key'    => 'pc_active',
    'meta_value'  => 1,
   );

   $pc_query = new WP_Query( $args );
   while ( $pc_query->have_posts() ) {
      $pc_query->the_post();
      $image_size    = get_post_meta( get_the_ID(), 'pc_image_size', true );
      $exit          = get_post_meta( get_the_ID(), 'pc_show_on_exit', true );
      $delay         = get_post_meta( get_the_ID(), 'pc_show_in_delay', true );
      $feature_image = get_the_post_thumbnail_url( get_the_ID(), $image_size );
      $pc_url        = get_post_meta( get_the_ID(), 'pc_url', true );
      $show_in_obj   = get_post_meta( get_the_ID(), 'pc_ww_show', true );

      if ( $delay ) {
            $delay *= 1000;
      } else {
            $delay = 0;
      }

      $show_in   = get_post( $show_in_obj );
      $showIn_id = $show_in->ID;

            if ( is_page( $showIn_id ) ) {
                  ?>
                        <div class="popup-creator" id="popup-creator" data-id="popup-<?php echo get_the_ID(); ?>" data-popup-size="<?php echo esc_attr( $image_size ); ?>" data-exit="<?php echo esc_attr( $exit ); ?>" data-delay="<?php echo $delay ?>" data-show="<?php echo $showIn_id; ?>">
                              <?php if ( $pc_url ) { ?>
                                    <a target="_blank" href="<?php echo esc_url( $pc_url ); ?>">
                                          <img src="<?php echo esc_url( $feature_image ); ?>" alt="<?php _e( 'Popup', 'popup-creator' )?>" />
                                    </a>
                              <?php } else {?>
                                    <img src="<?php echo esc_url( $feature_image ); ?>" alt="<?php _e( 'Popup', 'popup-creator' )?>" />
                                    <?php }?>
                              <button class="close-button">
                                    <img src="<?php echo esc_url( PUC_IMG_URL . '/close.svg' ) ?>" alt="Close" />
                              </button>
                        </div>
                  <?php
            }
      }

      wp_reset_query();

}
            /**
             * Load plugin text domain 
             */
            public function load_text_domain() {
                  load_plugin_textdomain( 'popup-creator', false, plugin_dir_path( __FILE__ ) . 'languages' );
            }
            
            
            /**
             * Register popup creator image sizes
             */
            public function register_image_size() {
                  add_image_size( 'popup-creator-landscape', 800, 600, true );
                  add_image_size( 'popup-creator-square', 500, 500, true );
                  add_image_size( 'popup-creator-thumbnail', 70 );
            }

            public function register_popup_creator() {
                  $labels = array(
                        'name'               => __( 'Popups Creator', 'popup-creator' ),
                        'singular_name'      => __( 'Popup Creator', 'popup-creator' ),
                        'featured_image'     => __( 'Popup Image' ),
                        'set_featured_image' => __( 'Set Popup Image' ),
                  );
                  $args = array(
                        'label'               => __( 'Popups', 'popup-creator' ),
                        'description'         => __( 'Popup Description', 'popup-creator' ),
                        'labels'              => $labels,
                        'supports'            => array( 'title', 'thumbnail' ),
                        'hierarchical'        => false,
                        'public'              => false,
                        'publicly_queryable'  => true,
                        'show_ui'             => true,
                        'show_in_menu'        => true,
                        'show_in_rest'        => true,
                        'menu_position'       => 60,
                        'show_in_admin_bar'   => true,
                        'show_in_nav_menus'   => true,
                        'has_archive'         => false,
                        'exclude_from_search' => false,
                        'capability_type'     => 'post',
                        'rewrite'             => array( 'slug' => 'popup', 'with_front' => true ),
                        'menu_icon'           => 'dashicons-screenoptions',
                  );
                  register_post_type( 'popup', $args );
            }

            }
            
            /**
             * Instantiate the plugin
             */
            // if ( is_admin() ) {
                  new Popup_Creator();
            // }

      /**
       * Enqueue admin assets 
      */
      new Popup_Assets();