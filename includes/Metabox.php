<?php
namespace ARPC\Popup;
class Metabox {
 
    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post',      array( $this, 'save' ) );
        add_action( 'admin_head', array( $this, 'metaboxEnqueue' ) );
    }
    
    public function metaboxEnqueue() {
        wp_enqueue_script( 'arpc-metabox-script' );
    }
    
   
    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'arpc_popup' );

        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'arpc_popup_metabox',
                __( 'Popup Creator' ,'arpc-popup-creator' ),
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
        $delay              = get_post_meta( $post->ID, 'arpc_show_in_delay', true );
        $title              = get_post_meta( $post->ID, 'arpc_title', true );
        $subtitle           = get_post_meta( $post->ID, 'arpc_subtitle', true );
        $auto_hide_delay_in = get_post_meta( $post->ID, 'arpc_auto_hide_in', true );
        $auto_hide          = get_post_meta( $post->ID, 'arpc_auto_hide_pu', true );
        $image_size         = get_post_meta( $post->ID, 'arpc_image_size', true );
        $show_on_exit       = get_post_meta( $post->ID, 'arpc_show_on_exit', true );
        $show_on_exit       = isset( $show_on_exit ) ? $show_on_exit : 1;
        $popup_url          = get_post_meta( $post->ID, 'arpc_popup_url', true );
        $is_active          = get_post_meta( $post->ID, 'arpc_active', true );
        $selected_page      = get_post_meta( $post->ID, 'arpc_ww_show', true );
        $image_id           = get_post_meta($post->ID, 'arpc_image_id', true);
        $image_url          = get_post_meta($post->ID, 'arpc_image_url', true);

        // Display the form, using the current value.
        ?>
        <div class="arpc_metabox_wrapper">
            
            <div class="arpc_form_group">
                <label for="arpc_active">
                    <?php _e( 'Is Active?' ,'arpc-popup-creator' ); ?>
                </label>
                <input type="checkbox" name="arpc_active" id="arpc_active" value="<?php echo esc_attr( $is_active ); ?>" <?php checked( 1, $this->get_popup_metabox_value( $is_active ) ); ?> />
            </div>
            
            <div class="arpc_form_group auto_hide_gp">
                <label for="arpc_auto_hide_pu">
                    <?php _e( 'Auto Hide' ,'arpc-popup-creator' ); ?>
                </label>
                <input type="checkbox" name="arpc_auto_hide_pu" id="arpc_auto_hide_pu" value="<?php echo esc_attr( $auto_hide ); ?>" <?php checked( 1, $this->get_popup_metabox_value( $auto_hide ) );?> />
                <small><?php _e('Default 30ms', '') ?></small>
            </div>
            
            <div class="arpc_form_group auto_hide_in_gp">
                <label for="arpc_auto_hide_in">
                    <?php _e( 'Auto Hide In' ,'arpc-popup-creator' ); ?>
                </label>
                <input class="regular-text" type="text" id="arpc_auto_hide_in" name="arpc_auto_hide_in" placeholder="5000" value="<?php echo esc_attr( $auto_hide_delay_in ); ?>" />
            </div>
            
            <div class="arpc_form_group">
                <label for="arpc_show_in_delay">
                    <?php _e( 'Show in Delay' ,'arpc-popup-creator' ); ?>
                </label>
                <input class="regular-text" type="text" id="arpc_show_in_delay" name="arpc_show_in_delay" placeholder="5000" value="<?php echo esc_attr( $delay ); ?>" />
            </div>
            
            <div class="arpc_form_group">
                <label for="arpc_title">
                    <?php _e( 'Popup Title' ,'arpc-popup-creator' ); ?>
                </label>
                <input class="regular-text" type="text" id="arpc_title" name="arpc_title" placeholder="Our Spring Sale Has Started" value="<?php echo esc_attr( $title ); ?>" />
            </div>
            
            <div class="arpc_form_group">
                <label for="arpc_subtitle">
                    <?php _e( 'Popup Sub Title' ,'arpc-popup-creator' ); ?>
                </label>
                <input class="regular-text" type="text" id="arpc_subtitle" name="arpc_subtitle" placeholder="Ex. Subscribe Our News Letter" value="<?php echo esc_attr( $subtitle ); ?>" />
            </div>
            
            <div class="arpc_form_group">
                <label for="arpc_show_on_exit">
                    <?php _e( 'Show when' ,'arpc-popup-creator' ); ?>
                </label>
                <div class="arpc_form_group_inner">
                    <label><input type="radio" name="arpc_show_on_exit" id="arpc_show_on_exit" value="0"  <?php checked( $show_on_exit, 0 );?> /> <?php _e('On Page Exit' ,'arpc-popup-creator' ); ?></label>
                    <label><input type="radio" name="arpc_show_on_exit" id="arpc_show_on_exit" value="1"  <?php checked( $show_on_exit, 1 );?>/> <?php _e('On Page Load' ,'arpc-popup-creator' ); ?></label>
                    <label><input type="radio" name="arpc_show_on_exit" id="arpc_show_on_exit" value="2"  <?php checked( $show_on_exit, 2 );?>/> <?php _e('On Page Scroll Bottom (up coming...)' ,'arpc-popup-creator' ); ?></label>
                </div>
            </div>
            
            <div class="arpc_form_group">
                <label for="arpc_popup_url">
                    <?php _e( 'Enter popup URL' ,'arpc-popup-creator' ); ?>
                </label>
                <input class="regular-text" type="url" id="arpc_popup_url" name="arpc_popup_url" value="<?php echo esc_attr( $popup_url ); ?>" />
            </div>
            
            <div class="arpc_form_group">
                <label for="arpc_image_size">
                    <?php _e( 'Select Image Size' ,'arpc-popup-creator' ); ?>
                </label>
                <select class="regular-text" name="arpc_image_size" id="arpc_image_size">
                    <option value="">Select Image Size</option>
                    <option value="original" <?php selected( 'original', $this->get_popup_metabox_value( $image_size ) ) ?> >Original</option>
                    <option value="landscape" <?php selected( 'landscape', $this->get_popup_metabox_value( $image_size ) ) ?> >Landscape</option>
                    <option value="square"  <?php selected( 'square', $this->get_popup_metabox_value( $image_size ) ) ?> >Square</option>
                </select>
            </div>
            
            <div class="arpc_form_group">
                <label><?php _e('Upload Image for Feature', 'arpc-popup-creator') ?></label>
                <div id="myImageMetaBox">
                    <button class="button" id="arpc_upload_image"><?php _e('Upload Image', 'arpc-popup-creator') ?></button>
                    <button class="hidden button" name="arpc_image_remove" id="arpc_delete_custom_img">Remove Image</button>
                    <input type="hidden" name="arpc_image_id" id="arpc_image_id" value="<?php echo $image_id; ?>" />
                    <input type="hidden" name="arpc_image_url" id="arpc_image_url" value="<?php echo $image_url; ?>" />
                    <div id="arpc_image_container"></div>
                </div>
            </div>
            
            <div class="arpc_form_group">
                <label for="arpc_ww_show">
                    <?php _e( 'Where to show' ,'arpc-popup-creator' ); ?>
                </label>
                
                <?php 
                    $args          = array(
                        'depth'             => 1,
                        'class'             => 'arpc-admin-pages regular-text',
                        'id'                => 'arpc_ww_show',
                        'name'              => 'arpc_ww_show',
                        'show_option_none'  => __('Select a page' ,'arpc-popup-creator' ),
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
     * Update post meta
     *
     * @param [type] $key
     * @param [type] $post_id
     * @return void
     */
    protected function update_post_meta_render( $key, $post_id ) {
        if( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, sanitize_text_field( $_POST[ $key ] ) );
        }
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
        
        if( isset( $_POST['arpc_popup_url'] ) ) {
            update_post_meta($post_id, 'arpc_popup_url', sanitize_text_field( $_POST['arpc_popup_url'] ) );
        }
        
        if( isset( $_POST['arpc_image_size'] ) ) {
            update_post_meta($post_id, 'arpc_image_size', sanitize_text_field( $_POST['arpc_image_size'] ) );
        }
        
        if( isset( $_POST['arpc_auto_hide_in'] ) ) {
            update_post_meta($post_id, 'arpc_auto_hide_in', sanitize_text_field( $_POST['arpc_auto_hide_in'] ) );
        }
        
        if( isset( $_POST['arpc_show_in_delay'] ) ) {
            update_post_meta($post_id, 'arpc_show_in_delay', sanitize_text_field( $_POST['arpc_show_in_delay'] ) );
        }
        
        if( isset( $_POST['arpc_title'] ) ) {
            update_post_meta($post_id, 'arpc_title', sanitize_text_field( $_POST['arpc_title'] ) );
        }
        
        if( isset( $_POST['arpc_subtitle'] ) ) {
            update_post_meta($post_id, 'arpc_subtitle', sanitize_text_field( $_POST['arpc_subtitle'] ) );
        }
        
        if( isset( $_POST['arpc_show_on_exit'] ) ) {
            update_post_meta($post_id, 'arpc_show_on_exit', sanitize_text_field( $_POST['arpc_show_on_exit'] ) );
        }
        
        if( isset( $_POST['arpc_ww_show'] ) ) {
            update_post_meta($post_id, 'arpc_ww_show', sanitize_text_field( $_POST['arpc_ww_show'] ) );
        }
        
        if( isset( $_POST['arpc_active'] ) ) {
            update_post_meta($post_id, 'arpc_active', true );
        } else {
            update_post_meta($post_id, 'arpc_active', false );
        }
        
        if( isset( $_POST['arpc_auto_hide_pu'] ) ) {
            update_post_meta($post_id, 'arpc_auto_hide_pu', true );
        } else {
            update_post_meta($post_id, 'arpc_auto_hide_pu', false );
        }
        
        if( isset( $_POST['arpc_image_id'] ) ) {
            update_post_meta($post_id, 'arpc_image_id', sanitize_text_field( $_POST['arpc_image_id'] ) );
        }
        
        // if( isset( $_POST['arpc_image_url'] ) ) {
        //     update_post_meta($post_id, 'arpc_image_url', sanitize_text_field( $_POST['arpc_image_url'] ) );
        // }

        $this->update_post_meta_render( 'arpc_image_url', $post_id );
             
    }
    
    public function get_popup_metabox_value( $value ) {
        if( isset( $value ) && !empty( $value ) ) {
            return $value;
        } else {
            return '';
        }
    }
    
  }