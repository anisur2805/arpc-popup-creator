<?php
/**
 * Shared newsletter form partial.
 *
 * Used by all template files to keep a single form that includes
 * both the email input and the interest category checkboxes.
 *
 * @package ARPC\Popup
 *
 * @var array $categories List of interest category labels.
 * @var int   $popup_id   Popup ID that owns this form.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Attribute the submission to the owning popup, not the page it renders on.
$arpc_form_popup_id = ! empty( $popup_id ) ? absint( $popup_id ) : absint( get_the_ID() );

// Show checkboxes only when using the built-in newsletter (not a custom form shortcode).
// Use saved categories; fall back to defaults when none are configured.
if ( empty( $form_shortcode ) ) {
	$display_categories = ! empty( $categories ) ? $categories : array(
		__( 'Tutorials', 'arpc-popup-creator' ),
		__( 'Products', 'arpc-popup-creator' ),
	);
} else {
	$display_categories = array();
}
?>
<div class="arpc-popup-creator-wrapper" data-arpc-popup-id="<?php echo esc_attr( $arpc_form_popup_id ); ?>">
	<form action="" method="post" class="arpc-subscribe-form">
		<div class="arpc-form-group-row">
			<input class="regular-text arpc_input" type="text" name="arpc-name" value="" placeholder="<?php esc_attr_e( 'Enter your name', 'arpc-popup-creator' ); ?>" />
		</div>
		<div class="arpc-form-group-row">
			<input class="regular-text arpc_input" type="email" name="arpc-email" value="" placeholder="<?php esc_attr_e( 'Enter your email', 'arpc-popup-creator' ); ?>" required/>
			<button type="submit" class="arpc_submit" name="arpc_submit"><?php esc_html_e( 'Subscribe Now', 'arpc-popup-creator' ); ?></button>
			<?php wp_nonce_field( 'arpc-modal-form' ); ?>
			<input type="hidden" name="action" value="arpc_modal_form_action">
			<input type="hidden" name="arpc-popup-id" value="<?php echo esc_attr( $arpc_form_popup_id ); ?>" />
		</div>
		<?php if ( ! empty( $display_categories ) ) : ?>
			<ul class="arpc_categories">
				<?php foreach ( $display_categories as $category ) : ?>
					<li>
						<label>
							<input type="checkbox" name="arpc-categories[]" value="<?php echo esc_attr( $category ); ?>" />
							<span><?php echo esc_html( $category ); ?></span>
						</label>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<p class="arpc-response hide"><?php esc_html_e( 'Thanks for subscribe', 'arpc-popup-creator' ); ?></p>
	</form>
</div>
