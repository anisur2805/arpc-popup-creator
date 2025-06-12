<?php
namespace ARPC\Popup;

defined( 'ABSPATH' ) || exit;

class Metabox {
	/**
	 * Initialize hooks.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
		add_action( 'save_post', [ $this, 'save' ] );
		add_action( 'admin_head', [ $this, 'metabox_enqueue' ] );
	}

	/**
	 * Enqueue scripts for the metabox.
	 */
	public function metabox_enqueue() {
		wp_enqueue_script( 'arpc-metabox-script' );
	}

	/**
	 * Register the meta box for supported post types.
	 *
	 * @param string $post_type Post type.
	 */
	public function add_meta_box( $post_type ) {
		$post_types = array( 'arpc_popup' );

		if ( in_array( $post_type, $post_types, true ) ) {
			add_meta_box(
				'arpc_popup_metabox',
				esc_html__( 'Popup Creator', 'arpc-popup-creator' ),
				[ $this, 'render_meta_box_content' ],
				$post_type,
				'advanced',
				'high'
			);
		}
	}

	/**
	 * Security checks for saving meta.
	 *
	 * @param string $nonce_field Nonce field key.
	 * @param string $action      Nonce action.
	 * @param int    $post_id     Post ID.
	 * @return bool
	 */
	private function is_secured( $nonce_field, $action, $post_id ) {
		$nonce = isset( $_POST[ $nonce_field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $nonce_field ] ) ) : '';

		if ( empty( $nonce ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( $nonce, $action ) ) {
			return false;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param \WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
		wp_nonce_field( 'popup_creator', 'popup_creator_nonce' );

		$fields = array(
			'delay'              => 'arpc_show_in_delay',
			'title'              => 'arpc_title',
			'subtitle'           => 'arpc_subtitle',
			'auto_hide_delay_in' => 'arpc_auto_hide_in',
			'auto_hide'          => 'arpc_auto_hide_pu',
			'image_size'         => 'arpc_image_size',
			'show_on_exit'       => 'arpc_show_on_exit',
			'popup_url'          => 'arpc_popup_url',
			'is_active'          => 'arpc_active',
			'selected_page'      => 'arpc_ww_show',
			'image_id'           => 'arpc_image_id',
			'image_url'          => 'arpc_image_url',
		);

		foreach ( $fields as $var => $meta_key ) {
			$$var = get_post_meta( $post->ID, $meta_key, true );
		}

		if ( '' === $delay ) {
			$delay = 1;
		}
		if ( '' === $show_on_exit ) {
			$show_on_exit = 0;
		}
		?>
		<div class="arpc_metabox_wrapper">
			<div class="arpc_form_group">
				<label for="arpc_active">
					<?php esc_html_e( 'Is Active?', 'arpc-popup-creator' ); ?>
				</label>
				<input type="checkbox" name="arpc_active" id="arpc_active" value="1" <?php checked( 1, intval( $is_active ) ); ?> />
			</div>

			<div class="arpc_form_group auto_hide_gp arpc-coming-soon">
				<label for="arpc_auto_hide_pu">
					<?php esc_html_e( 'Auto Hide', 'arpc-popup-creator' ); ?>
				</label>
				<input disabled type="checkbox" name="arpc_auto_hide_pu" id="arpc_auto_hide_pu" value="1" <?php checked( 1, intval( $auto_hide ) ); ?> />
				<small><?php esc_html_e( 'Default 30ms', 'arpc-popup-creator' ); ?></small>
			</div>

			<div class="arpc_form_group auto_hide_in_gp">
				<label for="arpc_auto_hide_in">
					<?php esc_html_e( 'Auto Hide In', 'arpc-popup-creator' ); ?>
				</label>
				<input class="regular-text" type="number" id="arpc_auto_hide_in" name="arpc_auto_hide_in" placeholder="5000" value="<?php echo esc_attr( $auto_hide_delay_in ); ?>" />
			</div>

			<div class="arpc_form_group">
				<label for="arpc_show_in_delay">
					<?php esc_html_e( 'Show in Delay', 'arpc-popup-creator' ); ?>
				</label>
				<div>
					<input class="regular-text" type="number" id="arpc_show_in_delay" min="1" max="15" name="arpc_show_in_delay" placeholder="5" value="<?php echo esc_attr( $delay ); ?>" />
					<small><?php esc_html_e( 'Insert time in seconds (1 - 15s)', 'arpc-popup-creator' ); ?></small>
				</div>
			</div>

			<div class="arpc_form_group">
				<label for="arpc_title">
					<?php esc_html_e( 'Popup Title', 'arpc-popup-creator' ); ?>
				</label>
				<input class="regular-text" type="text" id="arpc_title" name="arpc_title" placeholder="<?php esc_attr_e( 'Our Spring Sale Has Started', 'arpc-popup-creator' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
			</div>

			<div class="arpc_form_group">
				<label for="arpc_subtitle">
					<?php esc_html_e( 'Popup Sub Title', 'arpc-popup-creator' ); ?>
				</label>
				<input class="regular-text" type="text" id="arpc_subtitle" name="arpc_subtitle" placeholder="<?php esc_attr_e( 'Ex. Subscribe Our News Letter', 'arpc-popup-creator' ); ?>" value="<?php echo esc_attr( $subtitle ); ?>" />
			</div>

			<div class="arpc_form_group">
				<label for="arpc_show_on_exit">
					<?php esc_html_e( 'Show when', 'arpc-popup-creator' ); ?>
				</label>
				<div class="arpc_form_group_inner">
					<label>
						<input type="radio" name="arpc_show_on_exit" value="0" <?php checked( $show_on_exit, 0 ); ?> />
						<?php esc_html_e( 'On Page Exit', 'arpc-popup-creator' ); ?>
					</label>
					<label>
						<input type="radio" name="arpc_show_on_exit" value="1" <?php checked( $show_on_exit, 1 ); ?> />
						<?php esc_html_e( 'On Page Load', 'arpc-popup-creator' ); ?>
					</label>
					<label>
						<input disabled type="radio" name="arpc_show_on_exit" value="2" <?php checked( $show_on_exit, 2 ); ?> />
						<?php esc_html_e( 'On Page Scroll Bottom (up coming...)', 'arpc-popup-creator' ); ?>
					</label>
				</div>
			</div>

			<div class="arpc_form_group">
				<label for="arpc_popup_url">
					<?php esc_html_e( 'Enter popup URL', 'arpc-popup-creator' ); ?>
				</label>
				<input class="regular-text" type="url" id="arpc_popup_url" name="arpc_popup_url" value="<?php echo esc_url( $popup_url ); ?>" />
			</div>

			<div class="arpc_form_group">
				<label for="arpc_image_size">
					<?php esc_html_e( 'Select Image Size', 'arpc-popup-creator' ); ?>
				</label>
				<select class="regular-text" name="arpc_image_size" id="arpc_image_size">
					<option value=""><?php esc_html_e( 'Select Image Size', 'arpc-popup-creator' ); ?></option>
					<option value="original" <?php selected( 'original', $image_size ); ?>><?php esc_html_e( 'Original', 'arpc-popup-creator' ); ?></option>
					<option value="landscape" <?php selected( 'landscape', $image_size ); ?>><?php esc_html_e( 'Landscape', 'arpc-popup-creator' ); ?></option>
					<option value="square" <?php selected( 'square', $image_size ); ?>><?php esc_html_e( 'Square', 'arpc-popup-creator' ); ?></option>
				</select>
			</div>

			<div class="arpc_form_group hide-elem">
				<label><?php esc_html_e( 'Upload Image for Feature', 'arpc-popup-creator' ); ?></label>
				<div id="myImageMetaBox">
					<button class="button" id="arpc_upload_image" type="button"><?php esc_html_e( 'Upload Image', 'arpc-popup-creator' ); ?></button>
					<button class="hidden button" id="arpc_delete_custom_img" type="button"><?php esc_html_e( 'Remove Image', 'arpc-popup-creator' ); ?></button>
					<input type="hidden" name="arpc_image_id" id="arpc_image_id" value="<?php echo esc_attr( $image_id ); ?>" />
					<input type="hidden" name="arpc_image_url" id="arpc_image_url" value="<?php echo esc_url( $image_url ); ?>" />
					<div id="arpc_image_container"></div>
				</div>
			</div>

			<div class="arpc_form_group">
				<label for="arpc_ww_show">
					<?php esc_html_e( 'Where to show', 'arpc-popup-creator' ); ?>
				</label>
				<?php
				wp_dropdown_pages(
					array(
						'depth'             => 1,
						'class'             => 'arpc-admin-pages regular-text',
						'id'                => 'arpc_ww_show',
						'name'              => 'arpc_ww_show',
						'show_option_none'  => esc_html__( 'Select a page', 'arpc-popup-creator' ),
						'option_none_value' => 0,
						'echo'              => 1,
						'selected'          => $selected_page,
					)
				);
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Update post meta with sanitized value.
	 *
	 * @param string $key     Meta key.
	 * @param int    $post_id Post ID.
	 */
	protected function update_post_meta_render( $key, $post_id ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
		if ( ! $this->is_secured( 'popup_creator_nonce', 'popup_creator', $post_id ) ) {
			return $post_id;
		}

		$meta_fields = array(
			'arpc_popup_url',
			'arpc_image_size',
			'arpc_auto_hide_in',
			'arpc_show_in_delay',
			'arpc_title',
			'arpc_subtitle',
			'arpc_show_on_exit',
			'arpc_ww_show',
			'arpc_image_id',
			'arpc_image_url',
		);

		foreach ( $meta_fields as $field ) {
			$this->update_post_meta_render( $field, $post_id );
		}

		// Save checkboxes as boolean values.
		update_post_meta( $post_id, 'arpc_active', isset( $_POST['arpc_active'] ) ? 1 : 0 );
		update_post_meta( $post_id, 'arpc_auto_hide_pu', isset( $_POST['arpc_auto_hide_pu'] ) ? 1 : 0 );
	}

	/**
	 * Get meta box value or empty string.
	 *
	 * @param mixed $value Value.
	 * @return string
	 */
	public function get_popup_metabox_value( $value ) {
		return ( isset( $value ) && '' !== $value ) ? $value : '';
	}
}
