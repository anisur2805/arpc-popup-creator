<?php
/**
 * Metabox view template.
 *
 * @var int    $delay
 * @var string $title
 * @var string $subtitle
 * @var string $auto_hide_delay_in
 * @var mixed  $auto_hide
 * @var string $image_size
 * @var mixed  $show_on_exit
 * @var string $popup_url
 * @var mixed  $is_active
 * @var int    $selected_page
 * @var string $image_id
 * @var string $image_url
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="arpc_metabox_wrapper">
	<div class="arpc_form_group">
		<label for="arpc_active">
			<?php esc_html_e( 'Is Active?', 'arpc-popup-creator' ); ?>
		</label>
		<input type="checkbox" name="arpc_active" id="arpc_active" value="<?php echo esc_attr( $is_active ); ?>" <?php checked( 1, $is_active ); ?> />
	</div>

	<div class="arpc_form_group auto_hide_gp arpc-coming-soon">
		<label for="arpc_auto_hide_pu">
			<?php esc_html_e( 'Auto Hide', 'arpc-popup-creator' ); ?>
		</label>
		<input disabled type="checkbox" name="arpc_auto_hide_pu" id="arpc_auto_hide_pu" value="<?php echo esc_attr( $auto_hide ); ?>" <?php checked( 1, $auto_hide ); ?> />
		<small><?php esc_html_e( 'Default 30ms', 'arpc-popup-creator' ); ?></small>
	</div>

	<div class="arpc_form_group auto_hide_in_gp">
		<label for="arpc_auto_hide_in">
			<?php esc_html_e( 'Auto Hide In', 'arpc-popup-creator' ); ?>
		</label>
		<input class="regular-text" type="text" id="arpc_auto_hide_in" name="arpc_auto_hide_in" placeholder="5000" value="<?php echo esc_attr( $auto_hide_delay_in ); ?>" />
	</div>

	<div class="arpc_form_group">
		<label for="arpc_show_in_delay">
			<?php esc_html_e( 'Show in Delay', 'arpc-popup-creator' ); ?>
		</label>
		<div>
			<input class="regular-text" type="number" id="arpc_show_in_delay" min="1" max="15" name="arpc_show_in_delay" placeholder="5000" value="<?php echo esc_attr( $delay ); ?>" />
			<small><?php esc_html_e( 'Insert time in seconds (1 - 15s)', 'arpc-popup-creator' ); ?></small>
		</div>
	</div>

	<div class="arpc_form_group">
		<label for="arpc_title">
			<?php esc_html_e( 'Popup Title', 'arpc-popup-creator' ); ?>
		</label>
		<input class="regular-text" type="text" id="arpc_title" name="arpc_title" placeholder="Our Spring Sale Has Started" value="<?php echo esc_attr( $title ); ?>" />
	</div>

	<div class="arpc_form_group">
		<label for="arpc_subtitle">
			<?php esc_html_e( 'Popup Sub Title', 'arpc-popup-creator' ); ?>
		</label>
		<input class="regular-text" type="text" id="arpc_subtitle" name="arpc_subtitle" placeholder="Ex. Subscribe Our News Letter" value="<?php echo esc_attr( $subtitle ); ?>" />
	</div>

	<div class="arpc_form_group">
		<label for="arpc_show_on_exit">
			<?php esc_html_e( 'Show when', 'arpc-popup-creator' ); ?>
		</label>
		<div class="arpc_form_group_inner">
			<label><input type="radio" name="arpc_show_on_exit" id="arpc_show_on_exit" value="0" <?php checked( $show_on_exit, 0 ); ?> /> <?php esc_html_e( 'On Page Exit', 'arpc-popup-creator' ); ?></label>
			<label><input type="radio" name="arpc_show_on_exit" id="arpc_show_on_load" value="1" <?php checked( $show_on_exit, 1 ); ?> /> <?php esc_html_e( 'On Page Load', 'arpc-popup-creator' ); ?></label>
			<label><input disabled type="radio" name="arpc_show_on_exit" id="arpc_show_on_scroll" value="2" <?php checked( $show_on_exit, 2 ); ?> /> <?php esc_html_e( 'On Page Scroll Bottom (up coming...)', 'arpc-popup-creator' ); ?></label>
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
			<button class="button" id="arpc_upload_image"><?php esc_html_e( 'Upload Image', 'arpc-popup-creator' ); ?></button>
			<button class="hidden button" name="arpc_image_remove" id="arpc_delete_custom_img"><?php esc_html_e( 'Remove Image', 'arpc-popup-creator' ); ?></button>
			<input type="hidden" name="arpc_image_id" id="arpc_image_id" value="<?php echo esc_attr( $image_id ); ?>" />
			<input type="hidden" name="arpc_image_url" id="arpc_image_url" value="<?php echo esc_attr( $image_url ); ?>" />
			<div id="arpc_image_container"></div>
		</div>
	</div>

	<div class="arpc_form_group">
		<label for="arpc_ww_show">
			<?php esc_html_e( 'Where to show', 'arpc-popup-creator' ); ?>
		</label>

		<?php
		$args = array(
			'depth'             => 1,
			'class'             => 'arpc-admin-pages regular-text',
			'id'                => 'arpc_ww_show',
			'name'              => 'arpc_ww_show',
			'show_option_none'  => __( 'Select a page', 'arpc-popup-creator' ),
			'option_none_value' => 0,
			'echo'              => 1,
			'selected'          => $selected_page,
		);

		wp_dropdown_pages( $args );
		?>
	</div>
</div>
