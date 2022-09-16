<?php

namespace ARPC\Popup\Frontend;

class Modal {

      public function __construct() {
            add_action('wp_footer', array($this, 'load_modal_on_footer'));
      }

      public function load_modal_on_footer() {

            $options = get_option('arpc_setting_opn');

            wp_enqueue_style('arpc-style');
            wp_enqueue_script('plain-modal');
            wp_enqueue_script('arpc-modal-form');
            wp_enqueue_script('arpc-main');

            $args = array(
                  'post_type'   => 'arpc_popup',
                  'post_status' => 'publish',
                  'meta_key'    => 'arpc_active',
                  'meta_value'  => 1,
            );

            $arpc_query = new \WP_Query($args);
            while ($arpc_query->have_posts()) {
                  $arpc_query->the_post();
                  $image_size    = get_post_meta(get_the_ID(), 'arpc_image_size', true);
                  $exit          = get_post_meta(get_the_ID(), 'arpc_show_on_exit', true);
                  $delay         = get_post_meta(get_the_ID(), 'arpc_show_in_delay', true);
                  $title         = get_post_meta(get_the_ID(), 'arpc_title', true);
                  $subtitle      = get_post_meta(get_the_ID(), 'arpc_subtitle', true);
                  $feature_image = get_the_post_thumbnail_url(get_the_ID(), $image_size);
                  $popup_url     = get_post_meta(get_the_ID(), 'arpc_popup_url', true);
                  $show_in_obj   = get_post_meta(get_the_ID(), 'arpc_ww_show', true);
                  $auto_hide     = get_post_meta(get_the_ID(), 'arpc_auto_hide_pu', true);

                  if ($delay) {
                        $delay *= 1000;
                  } else {
                        $delay = 0;
                  }

                  $show_in   = get_post($show_in_obj);
                  $post   = get_post($show_in_obj);
                  $slug = $post->post_name;
                  $showIn_id = $show_in->ID;
                  $template = isset( $options['arpc_choose_temp'])? $options['arpc_choose_temp'] : 'template1';

                  $getURI = isset($_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
                  $filterURI =  str_replace( '/', '', $_SERVER['REQUEST_URI']); 

                  // if( ! (isset($_COOKIE[$filterURI]) && $_COOKIE[$filterURI] != 1 )){
                        if (is_page($showIn_id)) { 
                              ?>
                              <div class="arpc-popup-creator arpc-template arpc-<?php echo esc_attr($template); ?>" id="arpc-popup-creator" data-auto-hide="<?php echo ($auto_hide == 1) ? 'yes' : 'no'; ?>" data-id="popup-<?php echo get_the_ID(); ?>" data-popup-image-size="<?php echo esc_attr($image_size); ?>" data-exit="<?php echo esc_attr($exit); ?>" data-delay="<?php echo $delay ?>" data-show="<?php echo $showIn_id; ?>" data-page="<?php echo $slug; ?>">
                                    <div class="arpc-popup-creator-body">
                                          <?php
                                                include __DIR__ . "/views/$template.php";
                                          ?>
                                    </div>
                              </div>
                              <?php
                        }
                  // }
            }

            wp_reset_query();
      }
}
