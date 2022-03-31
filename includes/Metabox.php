<?php
namespace APC\Popup;
class Metabox {
 
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
    
    private function is_secured($nonce_field, $action, $post_id) {
        $nonce = isset($_POST[$nonce_field]) ? $_POST[$nonce_field] : '';

        if ($nonce == '') {
            return false;
        }

        if (!wp_verify_nonce($nonce, $action)) {
            return false;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return false;
        }

        if (wp_is_post_autosave($post_id)) {
            return false;
        }

        if (wp_is_post_revision($post_id)) {
            return false;
        }

        return true;
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
        $delay = get_post_meta( $post->ID, 'pc_show_in_delay', true );
        $auto_hide2 = get_post_meta( $post->ID, 'pc_auto_hide_pu', true );
        $image_size = get_post_meta( $post->ID, 'pc_image_size', true );
        $show_on_exit = get_post_meta( $post->ID, 'pc_show_on_exit', true );
        $url = get_post_meta( $post->ID, 'pc_url', true );
        $is_active = get_post_meta( $post->ID, 'pc_active', true );
        $selected_page = get_post_meta( $post->ID, 'pc_ww_show', true );

        // Display the form, using the current value.
        ?>
        <div class="pc_metabox_wrapper">
            
            <div class="pc_form_group">
                <label for="pc_active">
                    <?php _e( 'Is Active?', 'popup-creator' ); ?>
                </label>
                <input type="checkbox" name="pc_active" id="pc_active" value="<?php echo esc_attr( $is_active ); ?>" <?php checked( 1, $this->get_popup_metabox_value( $is_active ) ); ?> />
            </div>
            
            <div class="pc_form_group">
                <label for="pc_auto_hide_pu">
                    <?php _e( 'Auto Hide', 'popup-creator' ); ?>
                </label>
                <input type="checkbox" name="pc_auto_hide_pu" id="pc_auto_hide_pu" value="1" <?php checked( 1, $this->get_popup_metabox_value( $auto_hide2 ) );?> /> <?php echo esc_attr( $auto_hide2 ); ?>
            </div>
            
            <div class="pc_form_group">
                <label for="pc_show_in_delay">
                    <?php _e( 'Show in Delay', 'popup-creator' ); ?>
                </label>
                <input class="regular-text" type="text" id="pc_show_in_delay" name="pc_show_in_delay" placeholder="5000" value="<?php echo esc_attr( $delay ); ?>" size="25" />
            </div>
            
            <div class="pc_form_group">
                <label for="pc_show_on_exit">
                    <?php _e( 'Show when', 'popup-creator' ); ?>
                </label>
                <div>
                    <span><input type="radio" name="pc_show_on_exit" id="pc_show_on_exit" value="0"  <?php checked( $show_on_exit, 0 );?> /> <?php _e('On Page Exit', 'popup-creator' ); ?></span>
                    <span><input type="radio" name="pc_show_on_exit" id="pc_show_on_exit" value="1"  <?php checked( $show_on_exit, 1 );?>/> <?php _e('On Page Load', 'popup-creator' ); ?></span>
                </div>
            </div>
            
            <div class="pc_form_group">
                <label for="pc_url">
                    <?php _e( 'Enter popup URL', 'popup-creator' ); ?>
                </label>
                <input class="regular-text" type="url" id="pc_url" name="pc_url" value="<?php echo esc_attr( $url ); ?>" size="25" />
            </div>
            
            <div class="pc_form_group">
                <label for="pc_image_size">
                    <?php _e( 'Select Image Size', 'popup-creator' ); ?>
                </label>
                <select class="regular-text" name="pc_image_size" id="pc_image_size">
                    <option value="">Select Image Size</option>
                    <option value="original" <?php selected( 'original', $this->get_popup_metabox_value( $image_size ) ) ?> >Original</option>
                    <option value="landscape" <?php selected( 'landscape', $this->get_popup_metabox_value( $image_size ) ) ?> >Landscape</option>
                    <option value="square"  <?php selected( 'square', $this->get_popup_metabox_value( $image_size ) ) ?> >Square</option>
                </select>
            </div>
            
            <div class="pc_form_group">
                <label for="pc_ww_show">
                    <?php _e( 'Where to show', 'popup-creator' ); ?>
                </label>
                
                <?php 
                    $args          = array(
                        'depth'             => 1,
                        'class'             => 'pc-admin-pages regular-text',
                        'id'                => 'pc_ww_show',
                        'name'              => 'pc_ww_show',
                        'show_option_none'  => __('Select a page', 'popup-creator' ),
                        'option_none_value' => 0,
                        'echo'              => 1,
                        'selected'          => $selected_page,
                    );
                    
                    wp_dropdown_pages( $args );
                ?>
            </div>
        
        </div>
        <?php
    }
    
    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {
        if (!$this->is_secured('popup_creator_nonce', 'popup_creator', $post_id)) {
            return $post_id;
        }
        
        if( isset( $_POST['pc_url'] ) ) {
            update_post_meta($post_id, 'pc_url', sanitize_text_field( $_POST['pc_url'] ) );
        }
        
        if( isset( $_POST['pc_auto_hide_pu'] ) ) {
            update_post_meta($post_id, 'pc_auto_hide_pu', sanitize_text_field( $_POST['pc_auto_hide_pu'] ) );
        }
        
        if( isset( $_POST['pc_image_size'] ) ) {
            update_post_meta($post_id, 'pc_image_size', sanitize_text_field( $_POST['pc_image_size'] ) );
        }
        
        if( isset( $_POST['pc_show_in_delay'] ) ) {
            update_post_meta($post_id, 'pc_show_in_delay', sanitize_text_field( $_POST['pc_show_in_delay'] ) );
        }
        
        if( isset( $_POST['pc_show_on_exit'] ) ) {
            update_post_meta($post_id, 'pc_show_on_exit', sanitize_text_field( $_POST['pc_show_on_exit'] ) );
        }
        
        if( isset( $_POST['pc_ww_show'] ) ) {
            update_post_meta($post_id, 'pc_ww_show', sanitize_text_field( $_POST['pc_ww_show'] ) );
        }
        
        if( isset( $_POST['pc_active'] ) ) {
            update_post_meta($post_id, 'pc_active', true );
        } else {
            update_post_meta($post_id, 'pc_active', false );
        }
             
    }
    
    public function get_popup_metabox_value( $value ) {
        if( isset( $value ) && !empty( $value ) ) {
            return $value;
        } else {
            return '';
        }
    }
    
  }