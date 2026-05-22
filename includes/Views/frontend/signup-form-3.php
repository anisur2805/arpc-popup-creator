<?php
/**
 * Signup form 3 view.
 *
 * @var array $atts Shortcode attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="arpc-popup-creator-wrapper" id="arpc-popup-creator-wrapper">
	<form action="" method="post">
		<div class="arpc-form-group-row">
			<input class="regular-text arpc_input" type="email" name="arpc-email" value="" placeholder="<?php esc_attr_e( 'Enter your email', 'arpc-popup-creator' ); ?>" required/>
			<button type="submit" class="arpc_submit" name="arpc_submit" id="arpc_submit"><?php esc_html_e( 'Count Me In!', 'arpc-popup-creator' ); ?></button>
			<?php wp_nonce_field( 'arpc-modal-form' ); ?>
			<input type="hidden" name="action" value="arpc_modal_form_action">
		</div>
		<p class="arpc-response hide"><?php esc_html_e( 'Thanks for subscribe', 'arpc-popup-creator' ); ?></p>
	</form>
</div>
