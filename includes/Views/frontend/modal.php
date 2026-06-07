<?php
/**
 * Modal popup view template.
 *
 * @var string $image_size
 * @var string $title
 * @var string $subtitle
 * @var string $feature_image
 * @var string $popup_url
 * @var string $template
 * @var array  $popup_settings
 * @var array  $popup_config
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$position_map = array(
	'top-left'      => 'flex-start|flex-start',
	'top-center'    => 'center|flex-start',
	'top-right'     => 'flex-end|flex-start',
	'center-left'   => 'flex-start|center',
	'center-center' => 'center|center',
	'center-right'  => 'flex-end|center',
	'bottom-left'   => 'flex-start|flex-end',
	'bottom-center' => 'center|flex-end',
	'bottom-right'  => 'flex-end|flex-end',
);

$position_pair = isset( $position_map[ $popup_settings['popup_position'] ] ) ? $position_map[ $popup_settings['popup_position'] ] : $position_map['center-center'];
$position_pair = explode( '|', $position_pair );
$close_margin  = $popup_settings['close_button_margin'];
$close_padding = $popup_settings['close_button_padding'];
$close_radius  = $popup_settings['close_button_border_radius'];
$open_animation_family  = \ARPC\Popup\Services\Popup_Settings::animation_family( $popup_settings['open_animation'] );
$close_animation_family = \ARPC\Popup\Services\Popup_Settings::animation_family( $popup_settings['close_animation'] );

$style_vars = array(
	'--arpc-overlay-color:' . $popup_settings['overlay_color'],
	'--arpc-overlay-blur:' . ( ! empty( $popup_settings['overlay_blur'] ) ? absint( $popup_settings['overlay_blur_amount'] ) . 'px' : '0px' ),
	'--arpc-z-index:' . absint( $popup_settings['overlay_z_index'] ),
	'--arpc-align-x:' . $position_pair[0],
	'--arpc-align-y:' . $position_pair[1],
	'--arpc-close-icon-color:' . $popup_settings['close_button_icon_color'],
	'--arpc-close-bg:' . $popup_settings['close_button_background_color'],
	'--arpc-close-icon-size:' . absint( $popup_settings['close_button_icon_size'] ) . 'px',
	'--arpc-close-padding:' . intval( $close_padding['top'] ) . 'px ' . intval( $close_padding['right'] ) . 'px ' . intval( $close_padding['bottom'] ) . 'px ' . intval( $close_padding['left'] ) . 'px',
	'--arpc-close-margin:' . intval( $close_margin['top'] ) . 'px ' . intval( $close_margin['right'] ) . 'px ' . intval( $close_margin['bottom'] ) . 'px ' . intval( $close_margin['left'] ) . 'px',
	'--arpc-close-radius:' . intval( $close_radius['top'] ) . '% ' . intval( $close_radius['right'] ) . '% ' . intval( $close_radius['bottom'] ) . '% ' . intval( $close_radius['left'] ) . '%',
	'--arpc-tooltip-text:' . $popup_settings['tooltip_text_color'],
	'--arpc-tooltip-bg:' . $popup_settings['tooltip_background_color'],
);

$wrapper_classes = array(
	'arpc-popup-creator',
	'arpc-template',
	'arpc-' . $template,
	'arpc-layout-' . $popup_settings['layout_style'],
	'arpc-close-' . $popup_settings['close_button_position'],
);

if ( ! empty( $popup_settings['close_button_outside'] ) ) {
	$wrapper_classes[] = 'arpc-close-outside';
}
?>
<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
	data-popup-id="<?php echo esc_attr( get_the_ID() ); ?>"
	data-trigger-key="<?php echo esc_attr( $popup_config['triggerKey'] ); ?>"
	data-trigger-mode="<?php echo esc_attr( $popup_settings['trigger_mode'] ); ?>"
	data-open-animation="<?php echo esc_attr( $popup_settings['open_animation'] ); ?>"
	data-close-animation="<?php echo esc_attr( $popup_settings['close_animation'] ); ?>"
	data-open-animation-family="<?php echo esc_attr( $open_animation_family ); ?>"
	data-close-animation-family="<?php echo esc_attr( $close_animation_family ); ?>"
	data-popup-config="<?php echo esc_attr( wp_json_encode( $popup_config ) ); ?>"
	style="<?php echo esc_attr( implode( ';', $style_vars ) ); ?>"
	hidden>
	<div class="arpc-popup-creator__overlay" data-arpc-overlay></div>
	<div class="arpc-popup-creator__viewport">
		<div class="arpc-popup-creator__dialog" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr( $title ? $title : get_the_title() ); ?>">
			<?php if ( empty( $popup_settings['hide_close_button'] ) ) : ?>
				<button
					type="button"
					class="arpc-close-button"
					data-arpc-close
					aria-label="<?php esc_attr_e( 'Close popup', 'arpc-popup-creator' ); ?>"
					<?php if ( ! empty( $popup_settings['close_tooltip_text'] ) ) : ?>
						data-tooltip="<?php echo esc_attr( $popup_settings['close_tooltip_text'] ); ?>"
					<?php endif; ?>>
					<span aria-hidden="true">&times;</span>
				</button>
			<?php endif; ?>
			<div class="arpc-popup-creator-body">
				<?php include ARPC_PATH . "/includes/Views/frontend/{$template}.php"; ?>
			</div>
		</div>
	</div>
</div>
