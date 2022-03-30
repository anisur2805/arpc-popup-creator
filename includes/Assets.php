<?php
namespace APC\Popup\Creator;

class Assets {
 public function __construct() {
  add_action( 'wp_enqueue_scripts', [$this, 'enqueue_assets'] );
  add_action( 'admin_enqueue_scripts', [$this, 'enqueue_assets'] );
 }

 
//  wp_enqueue_style('popup-admin-style', PUC_ADMIN_URL . '/css/metabox.css', time());
 // wp_enqueue_script('plain-modal', PUC_PUBLIC_URL . '/js/jquery.plainmodal.min.js', null, time(), true);
 // wp_enqueue_script('popup-creator-main', PUC_PUBLIC_URL . '/js/popup-main.js', array('jquery', 'plain-modal'), time(), true);
 
 public function get_scripts() {
  return [
   'academy-script' => [
    'src'     => PUC_ASSETS . '/js/jquery.plainmodal.min.js',
    'version' => filemtime( PUC_PATH . '/assets/js/jquery.plainmodal.min.js' ),
    'deps'    => ['jquery'],
   ],
   'enquiry-script' => [
    'src'     => PUC_ASSETS . '/js/popup-main.js',
    'version' => filemtime( PUC_PATH . '/assets/js/popup-main.js' ),
    'deps'    => ['jquery'],
   ],
//    'admin-script'   => [
//     'src'     => PUC_ASSETS . '/js/admin.js',
//     'version' => filemtime( PUC_PATH . '/assets/js/admin.js' ),
//     'deps'    => ['jquery', 'wp-util'],
//    ],
  ];
 }

 public function get_styles() {
  return [
   'puc-metabox' => [
    'src'     => PUC_ASSETS . '/css/metabox.css',
    'version' => filemtime( PUC_PATH . '/assets/css/metabox.css' ),
   ],
//    'admin-style'   => [
//     'src'     => PUC_ASSETS . '/css/admin.css',
//     'version' => filemtime( PUC_PATH . '/assets/css/admin.css' ),
//    ],
//    'enquiry-style' => [
//     'src'     => PUC_ASSETS . '/css/enquiry.css',
//     'version' => filemtime( PUC_PATH . '/assets/css/enquiry.css' ),
//    ],
  ];
 }

 public function enqueue_assets() {
  $scripts = $this->get_scripts();

  foreach ( $scripts as $handle => $script ) {
   $deps = isset( $script['deps'] ) ? $script['deps'] : false;
   wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
  }

  $styles = $this->get_styles();

  foreach ( $styles as $handle => $style ) {
   $deps = isset( $style['deps'] ) ? $style['deps'] : false;
   wp_register_style( $handle, $style['src'], $deps, $style['version'] );

  }

//   wp_localize_script( 'enquiry-script', 'oopAcademy', [
//    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
//    'error'   => __( 'Something went wrong', 'oop-academy' ),
//   ] );

//   wp_localize_script( 'admin-script', 'oopAcademy', [
//    'nonce'   => wp_create_nonce( 'oop-academy-admin-nonce' ),
//    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
//    'confirm' => __( 'Are you sure?', 'oop-academy' ),
//    'error'   => __( 'Something went wrong', 'oop-academy' ),
//   ] );

 }

}
