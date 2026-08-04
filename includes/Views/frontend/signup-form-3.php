<?php
/**
 * Signup form 3 view.
 *
 * @package ARPC\Popup
 *
 * @var array $atts     Shortcode attributes.
 * @var int   $popup_id Popup ID.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Attribute the submission to the owning popup, not the page it renders on.
$arpc_form_popup_id = ! empty( $popup_id ) ? absint( $popup_id ) : absint( get_the_ID() );
?>
<div class="arpc-popup-creator-wrapper" data-arpc-popup-id="<?php echo esc_attr( $arpc_form_popup_id ); ?>">
	<form action="" method="post" class="arpc-subscribe-form">
		<div class="arpc-form-group-row">
			<input class="regular-text arpc_input" type="email" name="arpc-email" value="" placeholder="<?php esc_attr_e( 'Enter your email', 'arpc-popup-creator' ); ?>" required/>
			<button type="submit" class="arpc_submit" name="arpc_submit"><?php esc_html_e( 'Count Me In!', 'arpc-popup-creator' ); ?></button>
			<?php wp_nonce_field( 'arpc-modal-form' ); ?>
			<input type="hidden" name="action" value="arpc_modal_form_action">
			<input type="hidden" name="arpc-popup-id" value="<?php echo esc_attr( $arpc_form_popup_id ); ?>" />
		</div>
		<p class="arpc-response hide"><?php esc_html_e( 'Thanks for subscribe', 'arpc-popup-creator' ); ?></p>
	</form>
</div>
