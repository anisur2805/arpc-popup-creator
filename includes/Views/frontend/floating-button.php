<?php
/**
 * Floating launcher button.
 *
 * Rendered outside the popup wrapper (which is hidden) so visitors can open the
 * popup at any time, including after they have dismissed it.
 *
 * @package ARPC\Popup
 *
 * @var array $popup_settings Popup settings.
 * @var array $popup_config   Popup config passed to the frontend script.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $popup_settings['floating_button_enabled'] ) ) {
	return;
}

$arpc_button_label = ! empty( $popup_settings['floating_button_label'] )
	? $popup_settings['floating_button_label']
	: __( 'Open', 'arpc-popup-creator' );

$arpc_button_classes = array(
	'arpc-floating-button',
	'arpc-floating-button--' . $popup_settings['floating_button_position'],
);
?>
<button
	type="button"
	class="<?php echo esc_attr( implode( ' ', $arpc_button_classes ) ); ?>"
	data-arpc-floating-trigger="<?php echo esc_attr( $popup_config['triggerKey'] ); ?>">
	<?php echo esc_html( $arpc_button_label ); ?>
</button>
