<?php 
namespace APC\Popup\Creator;

class Frontend {
      public function __construct() {
            add_action( 'wp_footer', array( $this, 'load_modal_on_footer' ) );
      }
      public function load_modal_on_footer() {
            $args = array(
                  'post_type'   => 'popup',
                  'post_status' => 'publish',
                  'meta_key'    => 'pc_active',
                  'meta_value'  => 1,
            );

            $pc_query = new \WP_Query( $args );
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
}