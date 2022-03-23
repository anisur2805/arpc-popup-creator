<?php
class Popup_MetaBox {
 
      /**
       * Hook into the appropriate actions when the class is constructed.
       */
      public function __construct() {
          add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
          add_action( 'save_post',      array( $this, 'save' ) );
      }
   
      /**
       * Adds the meta box container.
       */
      public function add_meta_box( $post_type ) {
          // Limit meta box to certain post types.
          $post_types = array( 'popup' );
   
          if ( in_array( $post_type, $post_types ) ) {
              add_meta_box(
                  'popup_metabox',
                  __( 'Popup Creator', 'popup-creator' ),
                  array( $this, 'render_meta_box_content' ),
                  $post_type,
                  'advanced',
                  'high'
              );
          }
      }
   
      /**
       * Save the meta when the post is saved.
       *
       * @param int $post_id The ID of the post being saved.
       */
      public function save( $post_id ) {
   
          /*
           * We need to verify this came from the our screen and with proper authorization,
           * because save_post can be triggered at other times.
           */
   
          // Check if our nonce is set.
          if ( ! isset( $_POST['popup_creator_nonce'] ) ) {
              return $post_id;
          }
   
          $nonce = $_POST['popup_creator_nonce'];
   
          // Verify that the nonce is valid.
          if ( ! wp_verify_nonce( $nonce, 'popup_creator' ) ) {
              return $post_id;
          }
   
          /*
           * If this is an autosave, our form has not been submitted,
           * so we don't want to do anything.
           */
          if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
              return $post_id;
          }
   
          // Check the user's permissions.
          if ( 'page' == $_POST['post_type'] ) {
              if ( ! current_user_can( 'edit_page', $post_id ) ) {
                  return $post_id;
              }
          } else {
              if ( ! current_user_can( 'edit_post', $post_id ) ) {
                  return $post_id;
              }
          }
   
          /* OK, it's safe for us to save the data now. */
   
          // Sanitize the user input.
          $mydata = sanitize_text_field( $_POST['myplugin_new_field'] );
   
          // Update the meta field.
          update_post_meta( $post_id, '_pc_show_in_delay', $mydata );
      }
   
   
      /**
       * Render Meta Box content.
       *
       * @param WP_Post $post The post object.
       */
      public function render_meta_box_content( $post ) {
   
          // Add an nonce field so we can check for it later.
          wp_nonce_field( 'popup_creator', 'popup_creator_nonce' );
   
          // Use get_post_meta to retrieve an existing value from the database.
          $delay = get_post_meta( $post->ID, '_pc_show_in_delay', true );
          $url = get_post_meta( $post->ID, '_pc_url', true );
   
          // Display the form, using the current value.
          ?>
          <div class="pc_metabox_wrapper">
              
                <div class="pc_form_group">
                    <label for="pc_active">
                        <?php _e( 'Is Active?', 'popup-creator' ); ?>
                    </label>
                    <input type="checkbox" name="pc_active" id="pc_active" value="1" /> <?php _e('Yes', 'popup-creator' ); ?>
                </div>
                
                <div class="pc_form_group">
                    <label for="pc_auto_hide">
                        <?php _e( 'Auto Hide', 'popup-creator' ); ?>
                    </label>
                    <input type="checkbox" name="pc_auto_hide" id="pc_auto_hide" value="1" /> <?php _e('Yes', 'popup-creator' ); ?>
                </div>
                
                <div class="pc_form_group">
                    <label for="pc_show_in_delay">
                        <?php _e( 'Show in Delay', 'popup-creator' ); ?>
                    </label>
                    <input type="text" id="pc_show_in_delay" name="pc_show_in_delay" value="<?php echo esc_attr( $delay ); ?>" size="25" />
                </div>
                
                <div class="pc_form_group">
                    <label for="pc_show_on_exit">
                        <?php _e( 'Is Active?', 'popup-creator' ); ?>
                    </label>
                    <div>
                        <span><input type="radio" name="pc_show_on_exit" id="pc_show_on_exit" value="0" /> <?php _e('On Page Load', 'popup-creator' ); ?></span>
                        <span><input type="radio" name="pc_show_on_exit" id="pc_show_on_exit" value="1" /> <?php _e('On Page Exit', 'popup-creator' ); ?></span>
                    </div>
                </div>
                
                <div class="pc_form_group">
                    <label for="pc_url">
                        <?php _e( 'Enter popup URL', 'popup-creator' ); ?>
                    </label>
                    <input type="url" id="pc_url" name="pc_url" value="<?php echo esc_attr( $url ); ?>" size="25" />
                </div>
                
                <div class="pc_form_group">
                    <label for="pc_image_size">
                        <?php _e( 'Select Image Size', 'popup-creator' ); ?>
                    </label>
                    <select name="pc_image_size" id="pc_image_size">
                        <option value="">Select Image Size</option>
                        <option value="original">Original</option>
                        <option value="landscape">Landscape</option>
                        <option value="square">Square</option>
                    </select>
                </div>
                
                <div class="pc_form_group">
                    <label for="pc_ww_show">
                        <?php _e( 'Where to show', 'popup-creator' ); ?>
                    </label>
                    
                    <?php 
                        $args          = array(
                            'depth'             => 1,
                            'class'             => 'pc-admin-pages',
                            'id'                => 'pc_ww_show',
                            'name'              => 'pc_ww_show',
                            'show_option_none'  => __('Select a page', 'popup-creator' ),
                            'option_none_value' => 0,
                            'echo'              => 1,
                            // 'selected'          => $selected_page,
                      );
                      
                      wp_dropdown_pages( $args );
                    ?>
                </div>
                
               
                
                
                
          </div>
          <?php
      }
  }