<?php

namespace APC\Popup\Creator;

class Assets {
  public function __construct() {
    add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
  }

  public function get_scripts() {
    return [
      'plain-modal' => [
        'src'     => PUC_ASSETS . '/js/jquery.plainmodal.min.js',
        'version' => filemtime(PUC_PATH . '/assets/js/jquery.plainmodal.min.js'),
        'deps'    => ['jquery'],
      ],

      'puc-main'    => [
        'src'     => PUC_ASSETS . '/js/popup-main.js',
        'version' => filemtime(PUC_PATH . '/assets/js/popup-main.js'),
        'deps'    => ['jquery'],
      ],
      
      'puc-main-ajax'    => [
        'src'     => PUC_ASSETS . '/js/main-ajax.js',
        'version' => filemtime(PUC_PATH . '/assets/js/main-ajax.js'),
        'deps'    => ['jquery'],
      ],
    ];
  }

  public function get_styles() {
    return [
      'puc-metabox' => [
        'src'     => PUC_ASSETS . '/css/metabox.css',
        'version' => filemtime(PUC_PATH . '/assets/css/metabox.css'),
      ],

      'puc-style'   => [
        'src'     => PUC_ASSETS . '/css/puc-style.css',
        'version' => filemtime(PUC_PATH . '/assets/css/puc-style.css'),
      ],
    ];
  }

  public function enqueue_assets() {
    $scripts = $this->get_scripts();

    foreach ($scripts as $handle => $script) {
      $deps = isset($script['deps']) ? $script['deps'] : false;
      wp_register_script($handle, $script['src'], $deps, $script['version'], true);
    }

    $styles = $this->get_styles();

    foreach ($styles as $handle => $style) {
      $deps = isset($style['deps']) ? $style['deps'] : false;
      wp_register_style($handle, $style['src'], $deps, $style['version']);
    }

    wp_localize_script( 'main-ajax', 'pucPopup', [
      'nonce'   => wp_create_nonce( 'puc-ajax-nonce' ),
      'ajaxUrl' => admin_url( 'admin-ajax.php' ),
      'confirm' => __( 'Are you sure?', 'oop-academy' ),
      'error'   => __( 'Something went wrong', 'oop-academy' ),
    ] );

  }
}
