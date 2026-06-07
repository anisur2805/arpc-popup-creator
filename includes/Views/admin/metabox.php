<?php
/**
 * Metabox view template.
 *
 * @var array  $popup_settings
 * @var string $title
 * @var string $subtitle
 * @var string $image_size
 * @var string $popup_url
 * @var string $image_id
 * @var string $image_url
 * @var array  $role_labels
 * @var string $manual_trigger
 * @var array  $location_type_labels
 * @var array  $location_targets
 * @var array  $open_animation_options
 * @var array  $close_animation_options
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locations = isset( $popup_settings['display_locations'] ) && is_array( $popup_settings['display_locations'] ) ? $popup_settings['display_locations'] : array();

if ( empty( $locations ) ) {
	$locations[] = array(
		'mode' => 'include',
		'type' => 'sitewide',
		'ids'  => array(),
	);
}

$position_labels = array(
	'top-left'      => __( 'Top Left', 'arpc-popup-creator' ),
	'top-center'    => __( 'Top Center', 'arpc-popup-creator' ),
	'top-right'     => __( 'Top Right', 'arpc-popup-creator' ),
	'center-left'   => __( 'Center Left', 'arpc-popup-creator' ),
	'center-center' => __( 'Center', 'arpc-popup-creator' ),
	'center-right'  => __( 'Center Right', 'arpc-popup-creator' ),
	'bottom-left'   => __( 'Bottom Left', 'arpc-popup-creator' ),
	'bottom-center' => __( 'Bottom Center', 'arpc-popup-creator' ),
	'bottom-right'  => __( 'Bottom Right', 'arpc-popup-creator' ),
);

$render_location_row = static function ( $index, $location, $location_type_labels, $location_targets ) {
	$location = wp_parse_args(
		$location,
		array(
			'mode' => 'include',
			'type' => 'sitewide',
			'ids'  => array(),
		)
	);
	?>
	<div class="arpc-location-rule" data-location-rule>
		<div class="arpc-location-rule__header">
			<strong><?php esc_html_e( 'Location Rule', 'arpc-popup-creator' ); ?></strong>
			<button type="button" class="button-link-delete" data-remove-location><?php esc_html_e( 'Remove', 'arpc-popup-creator' ); ?></button>
		</div>
		<div class="arpc-field-grid arpc-field-grid--compact">
			<div class="arpc-field">
				<label for="arpc-location-mode-<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Mode', 'arpc-popup-creator' ); ?></label>
				<select id="arpc-location-mode-<?php echo esc_attr( $index ); ?>" name="arpc_popup_settings[display_locations][<?php echo esc_attr( $index ); ?>][mode]">
					<option value="include" <?php selected( $location['mode'], 'include' ); ?>><?php esc_html_e( 'Include', 'arpc-popup-creator' ); ?></option>
					<option value="exclude" <?php selected( $location['mode'], 'exclude' ); ?>><?php esc_html_e( 'Exclude', 'arpc-popup-creator' ); ?></option>
				</select>
			</div>
			<div class="arpc-field">
				<label for="arpc-location-type-<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Target', 'arpc-popup-creator' ); ?></label>
				<select id="arpc-location-type-<?php echo esc_attr( $index ); ?>" name="arpc_popup_settings[display_locations][<?php echo esc_attr( $index ); ?>][type]" data-location-type>
					<?php foreach ( $location_type_labels as $type_key => $type_label ) : ?>
						<option value="<?php echo esc_attr( $type_key ); ?>" <?php selected( $location['type'], $type_key ); ?>><?php echo esc_html( $type_label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<?php foreach ( $location_targets as $target_key => $target_data ) : ?>
			<div class="arpc-location-targets <?php echo $target_key === $location['type'] ? '' : 'is-hidden'; ?>" data-location-targets="<?php echo esc_attr( $target_key ); ?>">
				<label><?php echo esc_html( $target_data['label'] ); ?></label>
				<?php if ( ! empty( $target_data['items'] ) ) : ?>
					<select multiple size="6" name="arpc_popup_settings[display_locations][<?php echo esc_attr( $index ); ?>][ids][]" class="arpc-multi-select">
						<?php foreach ( $target_data['items'] as $target_item ) : ?>
							<option value="<?php echo esc_attr( $target_item->ID ); ?>" <?php selected( in_array( $target_item->ID, $location['ids'], true ), true ); ?>><?php echo esc_html( $target_item->post_title ); ?></option>
						<?php endforeach; ?>
					</select>
				<?php else : ?>
					<p class="arpc-empty-note"><?php esc_html_e( 'No items found for this target type yet.', 'arpc-popup-creator' ); ?></p>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
};
?>

<div class="arpc-settings-panel" data-arpc-metabox>
	<div class="arpc-settings-panel__tabs" role="tablist">
		<button type="button" class="arpc-settings-panel__tab is-active" data-arpc-tab="general"><?php esc_html_e( 'General', 'arpc-popup-creator' ); ?></button>
		<button type="button" class="arpc-settings-panel__tab" data-arpc-tab="customization"><?php esc_html_e( 'Customization', 'arpc-popup-creator' ); ?></button>
		<button type="button" class="arpc-settings-panel__tab" data-arpc-tab="conditions"><?php esc_html_e( 'Display Conditions', 'arpc-popup-creator' ); ?></button>
	</div>

	<div class="arpc-settings-panel__body">
		<section class="arpc-settings-panel__section is-active" data-arpc-panel="general">
			<div class="arpc-settings-card">
				<h3><?php esc_html_e( 'General', 'arpc-popup-creator' ); ?></h3>
				<div class="arpc-field-grid">
					<div class="arpc-field arpc-field--inline">
						<label for="arpc-popup-enabled"><?php esc_html_e( 'Pop-up Enable', 'arpc-popup-creator' ); ?></label>
						<label class="arpc-switch">
							<input id="arpc-popup-enabled" type="checkbox" name="arpc_popup_settings[enabled]" value="1" <?php checked( ! empty( $popup_settings['enabled'] ) ); ?> />
							<span class="arpc-switch__slider"></span>
						</label>
					</div>

					<div class="arpc-field">
						<label><?php esc_html_e( 'Trigger Mode', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-choice-row">
							<?php
							$trigger_modes = array(
								'click'      => __( 'Click', 'arpc-popup-creator' ),
								'load'       => __( 'On Load', 'arpc-popup-creator' ),
								'scroll'     => __( 'On Scroll', 'arpc-popup-creator' ),
								'exit'       => __( 'On Exit', 'arpc-popup-creator' ),
								'inactivity' => __( 'On Inactivity', 'arpc-popup-creator' ),
							);
							foreach ( $trigger_modes as $mode_key => $mode_label ) :
								?>
								<label class="arpc-choice-pill">
									<input type="radio" name="arpc_popup_settings[trigger_mode]" value="<?php echo esc_attr( $mode_key ); ?>" <?php checked( $popup_settings['trigger_mode'], $mode_key ); ?> />
									<span><?php echo esc_html( $mode_label ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="arpc-field <?php echo 'click' === $popup_settings['trigger_mode'] ? 'is-hidden' : ''; ?>" data-trigger-section="timed">
						<label><?php esc_html_e( 'Time Duration', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-inline-inputs">
							<div>
								<input type="number" min="0" name="arpc_popup_settings[open_delay]" value="<?php echo esc_attr( $popup_settings['open_delay'] ); ?>" />
								<small><?php esc_html_e( 'Open Delay (sec)', 'arpc-popup-creator' ); ?></small>
							</div>
							<div>
								<input type="number" min="0" name="arpc_popup_settings[auto_close_delay]" value="<?php echo esc_attr( $popup_settings['auto_close_delay'] ); ?>" />
								<small><?php esc_html_e( 'Auto Close (sec)', 'arpc-popup-creator' ); ?></small>
							</div>
						</div>
					</div>

					<div class="arpc-field <?php echo 'click' === $popup_settings['trigger_mode'] ? 'is-hidden' : ''; ?>" data-trigger-section="timed">
						<label><?php esc_html_e( 'Periodicity', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-choice-row">
							<label class="arpc-choice-pill">
								<input type="radio" name="arpc_popup_settings[periodicity]" value="every_time" <?php checked( $popup_settings['periodicity'], 'every_time' ); ?> />
								<span><?php esc_html_e( 'Every Time', 'arpc-popup-creator' ); ?></span>
							</label>
							<label class="arpc-choice-pill">
								<input type="radio" name="arpc_popup_settings[periodicity]" value="once_per_period" <?php checked( $popup_settings['periodicity'], 'once_per_period' ); ?> />
								<span><?php esc_html_e( 'Once Per Period', 'arpc-popup-creator' ); ?></span>
							</label>
							<label class="arpc-choice-pill">
								<input type="radio" name="arpc_popup_settings[periodicity]" value="once_only" <?php checked( $popup_settings['periodicity'], 'once_only' ); ?> />
								<span><?php esc_html_e( 'Once Only', 'arpc-popup-creator' ); ?></span>
							</label>
						</div>
						<div class="arpc-inline-inputs <?php echo 'once_per_period' === $popup_settings['periodicity'] ? '' : 'is-hidden'; ?>" data-periodicity-options>
							<div>
								<input type="number" min="1" name="arpc_popup_settings[period_value]" value="<?php echo esc_attr( $popup_settings['period_value'] ); ?>" />
								<input type="hidden" name="arpc_popup_settings[period_unit]" value="hour" />
								<small><?php esc_html_e( 'Period Hours', 'arpc-popup-creator' ); ?></small>
							</div>
							<div class="arpc-unit-chip-wrap">
								<span class="arpc-unit-chip"><?php esc_html_e( 'hrs', 'arpc-popup-creator' ); ?></span>
							</div>
						</div>
					</div>

					<div class="arpc-field <?php echo 'click' === $popup_settings['trigger_mode'] ? 'is-hidden' : ''; ?>" data-trigger-section="timed">
						<label><?php esc_html_e( 'Activity', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-choice-row">
							<label class="arpc-choice-pill">
								<input type="radio" name="arpc_popup_settings[activity_mode]" value="always" <?php checked( $popup_settings['activity_mode'], 'always' ); ?> />
								<span><?php esc_html_e( 'Always', 'arpc-popup-creator' ); ?></span>
							</label>
							<label class="arpc-choice-pill">
								<input type="radio" name="arpc_popup_settings[activity_mode]" value="certain_period" <?php checked( $popup_settings['activity_mode'], 'certain_period' ); ?> />
								<span><?php esc_html_e( 'Certain Period', 'arpc-popup-creator' ); ?></span>
							</label>
						</div>
						<div class="arpc-inline-inputs <?php echo 'certain_period' === $popup_settings['activity_mode'] ? '' : 'is-hidden'; ?>" data-activity-options>
							<div>
								<input type="datetime-local" name="arpc_popup_settings[activity_start]" value="<?php echo esc_attr( $popup_settings['activity_start'] ); ?>" />
								<small><?php esc_html_e( 'Start Date', 'arpc-popup-creator' ); ?></small>
							</div>
							<div>
								<input type="datetime-local" name="arpc_popup_settings[activity_end]" value="<?php echo esc_attr( $popup_settings['activity_end'] ); ?>" />
								<small><?php esc_html_e( 'End Date', 'arpc-popup-creator' ); ?></small>
							</div>
						</div>
					</div>

					<div class="arpc-field <?php echo 'click' === $popup_settings['trigger_mode'] ? '' : 'is-hidden'; ?>" data-trigger-section="click">
						<label for="arpc-manual-trigger"><?php esc_html_e( 'Manual Trigger', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-copy-field">
							<input id="arpc-manual-trigger" type="text" readonly value="<?php echo esc_attr( $manual_trigger ); ?>" />
							<button type="button" class="button" data-copy-trigger><?php esc_html_e( 'Copy', 'arpc-popup-creator' ); ?></button>
						</div>
						<small><?php esc_html_e( 'Use this key with .popup_ID, #popup_ID, or data-arpc-trigger attributes.', 'arpc-popup-creator' ); ?></small>
					</div>

					<div class="arpc-field <?php echo 'click' === $popup_settings['trigger_mode'] ? '' : 'is-hidden'; ?>" data-trigger-section="click">
						<label for="arpc-open-selector"><?php esc_html_e( 'CSS Custom Selector', 'arpc-popup-creator' ); ?></label>
						<input id="arpc-open-selector" type="text" name="arpc_popup_settings[open_selector]" value="<?php echo esc_attr( $popup_settings['open_selector'] ); ?>" placeholder=".open-popup, #launch-popup" />
					</div>

					<div class="arpc-field <?php echo 'click' === $popup_settings['trigger_mode'] ? '' : 'is-hidden'; ?>" data-trigger-section="click">
						<label for="arpc-close-selector"><?php esc_html_e( 'Close Button CSS Selector', 'arpc-popup-creator' ); ?></label>
						<input id="arpc-close-selector" type="text" name="arpc_popup_settings[close_selector]" value="<?php echo esc_attr( $popup_settings['close_selector'] ); ?>" placeholder=".close-popup" />
					</div>

					<div class="arpc-field arpc-field--inline">
						<label for="arpc-disable-link"><?php esc_html_e( 'Disable Link', 'arpc-popup-creator' ); ?></label>
						<label class="arpc-switch">
							<input id="arpc-disable-link" type="checkbox" name="arpc_popup_settings[disable_link]" value="1" <?php checked( ! empty( $popup_settings['disable_link'] ) ); ?> />
							<span class="arpc-switch__slider"></span>
						</label>
					</div>

					<div class="arpc-field arpc-field--inline">
						<label for="arpc-close-overlay"><?php esc_html_e( 'Close Popup on Overlay Click', 'arpc-popup-creator' ); ?></label>
						<label class="arpc-switch">
							<input id="arpc-close-overlay" type="checkbox" name="arpc_popup_settings[close_overlay]" value="1" <?php checked( ! empty( $popup_settings['close_overlay'] ) ); ?> />
							<span class="arpc-switch__slider"></span>
						</label>
					</div>

					<div class="arpc-field arpc-field--inline">
						<label for="arpc-prevent-scroll"><?php esc_html_e( 'Prevent Page Scrolling', 'arpc-popup-creator' ); ?></label>
						<label class="arpc-switch">
							<input id="arpc-prevent-scroll" type="checkbox" name="arpc_popup_settings[prevent_scroll]" value="1" <?php checked( ! empty( $popup_settings['prevent_scroll'] ) ); ?> />
							<span class="arpc-switch__slider"></span>
						</label>
					</div>

					<div class="arpc-field arpc-field--inline">
						<label for="arpc-close-back"><?php esc_html_e( 'Close by Clicking Back Button', 'arpc-popup-creator' ); ?></label>
						<label class="arpc-switch">
							<input id="arpc-close-back" type="checkbox" name="arpc_popup_settings[close_back]" value="1" <?php checked( ! empty( $popup_settings['close_back'] ) ); ?> />
							<span class="arpc-switch__slider"></span>
						</label>
					</div>
				</div>
			</div>

			<div class="arpc-settings-card">
				<h3><?php esc_html_e( 'Content & Media', 'arpc-popup-creator' ); ?></h3>
				<div class="arpc-field-grid">
					<div class="arpc-field">
						<label for="arpc-title"><?php esc_html_e( 'Popup Title', 'arpc-popup-creator' ); ?></label>
						<input id="arpc-title" type="text" name="arpc_title" value="<?php echo esc_attr( $title ); ?>" placeholder="<?php esc_attr_e( 'Our Spring Sale Has Started', 'arpc-popup-creator' ); ?>" />
					</div>
					<div class="arpc-field">
						<label for="arpc-subtitle"><?php esc_html_e( 'Popup Subtitle', 'arpc-popup-creator' ); ?></label>
						<input id="arpc-subtitle" type="text" name="arpc_subtitle" value="<?php echo esc_attr( $subtitle ); ?>" placeholder="<?php esc_attr_e( 'Subscribe to our newsletter', 'arpc-popup-creator' ); ?>" />
					</div>
					<div class="arpc-field">
						<label for="arpc-popup-url"><?php esc_html_e( 'Popup URL', 'arpc-popup-creator' ); ?></label>
						<input id="arpc-popup-url" type="url" name="arpc_popup_url" value="<?php echo esc_url( $popup_url ); ?>" placeholder="https://example.com" />
					</div>
					<div class="arpc-field">
						<label for="arpc-image-size"><?php esc_html_e( 'Select Image Size', 'arpc-popup-creator' ); ?></label>
						<select id="arpc-image-size" name="arpc_image_size">
							<option value=""><?php esc_html_e( 'Featured Image Default', 'arpc-popup-creator' ); ?></option>
							<option value="original" <?php selected( 'original', $image_size ); ?>><?php esc_html_e( 'Original', 'arpc-popup-creator' ); ?></option>
							<option value="landscape" <?php selected( 'landscape', $image_size ); ?>><?php esc_html_e( 'Landscape', 'arpc-popup-creator' ); ?></option>
							<option value="square" <?php selected( 'square', $image_size ); ?>><?php esc_html_e( 'Square', 'arpc-popup-creator' ); ?></option>
						</select>
					</div>
					<div class="arpc-field arpc-field--full">
						<label><?php esc_html_e( 'Fallback Image', 'arpc-popup-creator' ); ?></label>
						<div id="myImageMetaBox" class="arpc-image-uploader">
							<div class="arpc-image-uploader__actions">
								<button type="button" class="button" id="arpc_upload_image"><?php esc_html_e( 'Upload Image', 'arpc-popup-creator' ); ?></button>
								<button type="button" class="button <?php echo $image_url ? '' : 'hidden'; ?>" name="arpc_image_remove" id="arpc_delete_custom_img"><?php esc_html_e( 'Remove Image', 'arpc-popup-creator' ); ?></button>
							</div>
							<input type="hidden" name="arpc_image_id" id="arpc_image_id" value="<?php echo esc_attr( $image_id ); ?>" />
							<input type="hidden" name="arpc_image_url" id="arpc_image_url" value="<?php echo esc_attr( $image_url ); ?>" />
							<div id="arpc_image_container" class="arpc-image-uploader__preview"></div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="arpc-settings-panel__section" data-arpc-panel="customization">
			<div class="arpc-settings-card">
				<h3><?php esc_html_e( 'Customization', 'arpc-popup-creator' ); ?></h3>
				<div class="arpc-field-grid">
					<div class="arpc-field">
						<label for="arpc-overlay-color"><?php esc_html_e( 'Overlay Background Color', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-reset-field">
							<input id="arpc-overlay-color" type="text" name="arpc_popup_settings[overlay_color]" value="<?php echo esc_attr( $popup_settings['overlay_color'] ); ?>" placeholder="rgba(0, 0, 0, 0.7)" data-default-value="rgba(0, 0, 0, 0.7)" />
							<button type="button" class="button button-secondary" data-reset-field="#arpc-overlay-color"><?php esc_html_e( 'Reset', 'arpc-popup-creator' ); ?></button>
						</div>
					</div>

					<div class="arpc-field arpc-field--inline">
						<label for="arpc-overlay-blur"><?php esc_html_e( 'Enable Overlay Blur', 'arpc-popup-creator' ); ?></label>
						<label class="arpc-switch">
							<input id="arpc-overlay-blur" type="checkbox" name="arpc_popup_settings[overlay_blur]" value="1" <?php checked( ! empty( $popup_settings['overlay_blur'] ) ); ?> />
							<span class="arpc-switch__slider"></span>
						</label>
					</div>

					<div class="arpc-field <?php echo ! empty( $popup_settings['overlay_blur'] ) ? '' : 'is-hidden'; ?>" data-overlay-blur-amount>
						<label for="arpc-overlay-blur-amount"><?php esc_html_e( 'Overlay Blur Amount', 'arpc-popup-creator' ); ?></label>
						<input id="arpc-overlay-blur-amount" type="number" min="0" max="40" name="arpc_popup_settings[overlay_blur_amount]" value="<?php echo esc_attr( $popup_settings['overlay_blur_amount'] ); ?>" />
					</div>

					<div class="arpc-field">
						<label for="arpc-overlay-zindex"><?php esc_html_e( 'Overlay Z Index', 'arpc-popup-creator' ); ?></label>
						<input id="arpc-overlay-zindex" type="number" min="1" name="arpc_popup_settings[overlay_z_index]" value="<?php echo esc_attr( $popup_settings['overlay_z_index'] ); ?>" />
					</div>

					<div class="arpc-field">
						<label><?php esc_html_e( 'Popup Layout Style', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-choice-row">
							<label class="arpc-choice-pill">
								<input type="radio" name="arpc_popup_settings[layout_style]" value="box" <?php checked( $popup_settings['layout_style'], 'box' ); ?> />
								<span><?php esc_html_e( 'Box Width', 'arpc-popup-creator' ); ?></span>
							</label>
							<label class="arpc-choice-pill">
								<input type="radio" name="arpc_popup_settings[layout_style]" value="full" <?php checked( $popup_settings['layout_style'], 'full' ); ?> />
								<span><?php esc_html_e( 'Full Width', 'arpc-popup-creator' ); ?></span>
							</label>
						</div>
					</div>

					<div class="arpc-field">
						<label><?php esc_html_e( 'Popup Position', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-position-grid">
							<?php foreach ( $position_labels as $position_key => $position_label ) : ?>
								<label class="arpc-position-grid__cell">
									<input type="radio" name="arpc_popup_settings[popup_position]" value="<?php echo esc_attr( $position_key ); ?>" <?php checked( $popup_settings['popup_position'], $position_key ); ?> />
									<span><?php echo esc_html( $position_label ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="arpc-field">
						<label for="arpc-open-animation"><?php esc_html_e( 'Opening Animation Effect', 'arpc-popup-creator' ); ?></label>
						<select id="arpc-open-animation" name="arpc_popup_settings[open_animation]">
							<?php foreach ( $open_animation_options as $animation_key => $animation_label ) : ?>
								<option value="<?php echo esc_attr( $animation_key ); ?>" <?php selected( $popup_settings['open_animation'], $animation_key ); ?>><?php echo esc_html( $animation_label ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="arpc-field">
						<label for="arpc-close-animation"><?php esc_html_e( 'Closing Animation Effect', 'arpc-popup-creator' ); ?></label>
						<select id="arpc-close-animation" name="arpc_popup_settings[close_animation]">
							<?php foreach ( $close_animation_options as $animation_key => $animation_label ) : ?>
								<option value="<?php echo esc_attr( $animation_key ); ?>" <?php selected( $popup_settings['close_animation'], $animation_key ); ?>><?php echo esc_html( $animation_label ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="arpc-field">
						<label for="arpc-close-button-position"><?php esc_html_e( 'Closing Button Position', 'arpc-popup-creator' ); ?></label>
						<select id="arpc-close-button-position" name="arpc_popup_settings[close_button_position]">
							<option value="top-left" <?php selected( $popup_settings['close_button_position'], 'top-left' ); ?>><?php esc_html_e( 'Top Left', 'arpc-popup-creator' ); ?></option>
							<option value="top-center" <?php selected( $popup_settings['close_button_position'], 'top-center' ); ?>><?php esc_html_e( 'Top Center', 'arpc-popup-creator' ); ?></option>
							<option value="top-right" <?php selected( $popup_settings['close_button_position'], 'top-right' ); ?>><?php esc_html_e( 'Top Right', 'arpc-popup-creator' ); ?></option>
							<option value="bottom-left" <?php selected( $popup_settings['close_button_position'], 'bottom-left' ); ?>><?php esc_html_e( 'Bottom Left', 'arpc-popup-creator' ); ?></option>
							<option value="bottom-center" <?php selected( $popup_settings['close_button_position'], 'bottom-center' ); ?>><?php esc_html_e( 'Bottom Center', 'arpc-popup-creator' ); ?></option>
							<option value="bottom-right" <?php selected( $popup_settings['close_button_position'], 'bottom-right' ); ?>><?php esc_html_e( 'Bottom Right', 'arpc-popup-creator' ); ?></option>
						</select>
					</div>

					<div class="arpc-field arpc-field--inline">
						<label for="arpc-hide-close-button"><?php esc_html_e( 'Hide Close Button', 'arpc-popup-creator' ); ?></label>
						<label class="arpc-switch">
							<input id="arpc-hide-close-button" type="checkbox" name="arpc_popup_settings[hide_close_button]" value="1" <?php checked( ! empty( $popup_settings['hide_close_button'] ) ); ?> />
							<span class="arpc-switch__slider"></span>
						</label>
					</div>

					<div class="arpc-field">
						<label for="arpc-close-tooltip-text"><?php esc_html_e( 'Close Button Tooltip Text', 'arpc-popup-creator' ); ?></label>
						<input id="arpc-close-tooltip-text" type="text" name="arpc_popup_settings[close_tooltip_text]" value="<?php echo esc_attr( $popup_settings['close_tooltip_text'] ); ?>" />
					</div>

					<div class="arpc-field">
						<label for="arpc-tooltip-text-color"><?php esc_html_e( 'Tooltip Text Color', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-reset-field">
							<input id="arpc-tooltip-text-color" type="text" name="arpc_popup_settings[tooltip_text_color]" value="<?php echo esc_attr( $popup_settings['tooltip_text_color'] ); ?>" data-default-value="#ffffff" />
							<button type="button" class="button button-secondary" data-reset-field="#arpc-tooltip-text-color"><?php esc_html_e( 'Reset', 'arpc-popup-creator' ); ?></button>
						</div>
					</div>

					<div class="arpc-field">
						<label for="arpc-tooltip-background-color"><?php esc_html_e( 'Tooltip Background Color', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-reset-field">
							<input id="arpc-tooltip-background-color" type="text" name="arpc_popup_settings[tooltip_background_color]" value="<?php echo esc_attr( $popup_settings['tooltip_background_color'] ); ?>" data-default-value="#111111" />
							<button type="button" class="button button-secondary" data-reset-field="#arpc-tooltip-background-color"><?php esc_html_e( 'Reset', 'arpc-popup-creator' ); ?></button>
						</div>
					</div>

					<div class="arpc-field arpc-field--inline">
						<label for="arpc-close-button-outside"><?php esc_html_e( 'Place Close Button Outside', 'arpc-popup-creator' ); ?></label>
						<label class="arpc-switch">
							<input id="arpc-close-button-outside" type="checkbox" name="arpc_popup_settings[close_button_outside]" value="1" <?php checked( ! empty( $popup_settings['close_button_outside'] ) ); ?> />
							<span class="arpc-switch__slider"></span>
						</label>
					</div>

					<div class="arpc-field">
						<label for="arpc-close-button-icon-color"><?php esc_html_e( 'Close Button Icon Color', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-reset-field">
							<input id="arpc-close-button-icon-color" type="text" name="arpc_popup_settings[close_button_icon_color]" value="<?php echo esc_attr( $popup_settings['close_button_icon_color'] ); ?>" data-default-value="#000000" />
							<button type="button" class="button button-secondary" data-reset-field="#arpc-close-button-icon-color"><?php esc_html_e( 'Reset', 'arpc-popup-creator' ); ?></button>
						</div>
					</div>

					<div class="arpc-field">
						<label for="arpc-close-button-background-color"><?php esc_html_e( 'Close Button Background Color', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-reset-field">
							<input id="arpc-close-button-background-color" type="text" name="arpc_popup_settings[close_button_background_color]" value="<?php echo esc_attr( $popup_settings['close_button_background_color'] ); ?>" data-default-value="#ffffff" />
							<button type="button" class="button button-secondary" data-reset-field="#arpc-close-button-background-color"><?php esc_html_e( 'Reset', 'arpc-popup-creator' ); ?></button>
						</div>
					</div>

					<div class="arpc-field">
						<label for="arpc-close-button-icon-size"><?php esc_html_e( 'Close Button Icon Size', 'arpc-popup-creator' ); ?></label>
						<input id="arpc-close-button-icon-size" type="number" min="8" max="96" name="arpc_popup_settings[close_button_icon_size]" value="<?php echo esc_attr( $popup_settings['close_button_icon_size'] ); ?>" />
					</div>

					<div class="arpc-field arpc-field--full">
						<label><?php esc_html_e( 'Close Button Padding', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-box-inputs">
							<input type="number" name="arpc_popup_settings[close_button_padding][top]" value="<?php echo esc_attr( $popup_settings['close_button_padding']['top'] ); ?>" placeholder="<?php esc_attr_e( 'Top', 'arpc-popup-creator' ); ?>" />
							<input type="number" name="arpc_popup_settings[close_button_padding][right]" value="<?php echo esc_attr( $popup_settings['close_button_padding']['right'] ); ?>" placeholder="<?php esc_attr_e( 'Right', 'arpc-popup-creator' ); ?>" />
							<input type="number" name="arpc_popup_settings[close_button_padding][bottom]" value="<?php echo esc_attr( $popup_settings['close_button_padding']['bottom'] ); ?>" placeholder="<?php esc_attr_e( 'Bottom', 'arpc-popup-creator' ); ?>" />
							<input type="number" name="arpc_popup_settings[close_button_padding][left]" value="<?php echo esc_attr( $popup_settings['close_button_padding']['left'] ); ?>" placeholder="<?php esc_attr_e( 'Left', 'arpc-popup-creator' ); ?>" />
						</div>
					</div>

					<div class="arpc-field arpc-field--full">
						<label><?php esc_html_e( 'Close Button Margin', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-box-inputs">
							<input type="number" name="arpc_popup_settings[close_button_margin][top]" value="<?php echo esc_attr( $popup_settings['close_button_margin']['top'] ); ?>" placeholder="<?php esc_attr_e( 'Top', 'arpc-popup-creator' ); ?>" />
							<input type="number" name="arpc_popup_settings[close_button_margin][right]" value="<?php echo esc_attr( $popup_settings['close_button_margin']['right'] ); ?>" placeholder="<?php esc_attr_e( 'Right', 'arpc-popup-creator' ); ?>" />
							<input type="number" name="arpc_popup_settings[close_button_margin][bottom]" value="<?php echo esc_attr( $popup_settings['close_button_margin']['bottom'] ); ?>" placeholder="<?php esc_attr_e( 'Bottom', 'arpc-popup-creator' ); ?>" />
							<input type="number" name="arpc_popup_settings[close_button_margin][left]" value="<?php echo esc_attr( $popup_settings['close_button_margin']['left'] ); ?>" placeholder="<?php esc_attr_e( 'Left', 'arpc-popup-creator' ); ?>" />
						</div>
					</div>

					<div class="arpc-field arpc-field--full">
						<label><?php esc_html_e( 'Close Button Border Radius', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-box-inputs">
							<input type="number" name="arpc_popup_settings[close_button_border_radius][top]" value="<?php echo esc_attr( $popup_settings['close_button_border_radius']['top'] ); ?>" placeholder="<?php esc_attr_e( 'Top Left', 'arpc-popup-creator' ); ?>" />
							<input type="number" name="arpc_popup_settings[close_button_border_radius][right]" value="<?php echo esc_attr( $popup_settings['close_button_border_radius']['right'] ); ?>" placeholder="<?php esc_attr_e( 'Top Right', 'arpc-popup-creator' ); ?>" />
							<input type="number" name="arpc_popup_settings[close_button_border_radius][bottom]" value="<?php echo esc_attr( $popup_settings['close_button_border_radius']['bottom'] ); ?>" placeholder="<?php esc_attr_e( 'Bottom Right', 'arpc-popup-creator' ); ?>" />
							<input type="number" name="arpc_popup_settings[close_button_border_radius][left]" value="<?php echo esc_attr( $popup_settings['close_button_border_radius']['left'] ); ?>" placeholder="<?php esc_attr_e( 'Bottom Left', 'arpc-popup-creator' ); ?>" />
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="arpc-settings-panel__section" data-arpc-panel="conditions">
			<div class="arpc-settings-card">
				<h3><?php esc_html_e( 'Display Conditions', 'arpc-popup-creator' ); ?></h3>
				<div class="arpc-field-grid">
					<div class="arpc-field">
						<label><?php esc_html_e( 'Specify Visibility by Role', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-checkbox-grid">
							<?php foreach ( $role_labels as $role_key => $role_label ) : ?>
								<label class="arpc-checkbox-pill">
									<input type="checkbox" name="arpc_popup_settings[visibility_roles][]" value="<?php echo esc_attr( $role_key ); ?>" <?php checked( in_array( $role_key, $popup_settings['visibility_roles'], true ) ); ?> />
									<span><?php echo esc_html( $role_label ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="arpc-field">
						<label><?php esc_html_e( 'Hide On Device', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-checkbox-grid">
							<?php
							$devices = array(
								'mobile'  => __( 'Mobile', 'arpc-popup-creator' ),
								'tablet'  => __( 'Tablet', 'arpc-popup-creator' ),
								'desktop' => __( 'Desktop', 'arpc-popup-creator' ),
							);
							foreach ( $devices as $device_key => $device_label ) :
								?>
								<label class="arpc-checkbox-pill">
									<input type="checkbox" name="arpc_popup_settings[hide_devices][]" value="<?php echo esc_attr( $device_key ); ?>" <?php checked( in_array( $device_key, $popup_settings['hide_devices'], true ) ); ?> />
									<span><?php echo esc_html( $device_label ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="arpc-field arpc-field--full">
						<label><?php esc_html_e( 'Specify Visibility by Page / Post', 'arpc-popup-creator' ); ?></label>
						<div class="arpc-location-rules" data-location-rules>
							<?php foreach ( $locations as $index => $location ) : ?>
								<?php $render_location_row( $index, $location, $location_type_labels, $location_targets ); ?>
							<?php endforeach; ?>
						</div>
						<p>
							<button type="button" class="button button-secondary" data-add-location><?php esc_html_e( 'Add New Location', 'arpc-popup-creator' ); ?></button>
						</p>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<script type="text/template" id="tmpl-arpc-location-rule">
	<?php
	$render_location_row(
		'{{{data.index}}}',
		array(
			'mode' => 'include',
			'type' => 'sitewide',
			'ids'  => array(),
		),
		$location_type_labels,
		$location_targets
	);
	?>
</script>
