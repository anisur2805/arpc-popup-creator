<?php
/**
 * Shared newsletter form partial.
 *
 * Used by all template files to keep a single form that includes
 * both the email input and the interest category checkboxes.
 *
 * @var array  $categories List of interest category labels.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$has_categories = ! empty( $categories ) && empty( $form_shortcode );
?>
<div class="arpc-popup-creator-wrapper" id="arpc-popup-creator-wrapper">
	<form action="" method="post">
		<div class="arpc-form-group-row">
			<input class="regular-text arpc_input" type="text" name="arpc-name" value="" placeholder="<?php esc_attr_e( 'Enter your name', 'arpc-popup-creator' ); ?>" />
		</div>
		<div class="arpc-form-group-row">
			<input class="regular-text arpc_input" type="email" name="arpc-email" value="" placeholder="<?php esc_attr_e( 'Enter your email', 'arpc-popup-creator' ); ?>" required/>
			<button type="submit" class="arpc_submit" name="arpc_submit"><?php esc_html_e( 'Subscribe Now', 'arpc-popup-creator' ); ?></button>
			<?php wp_nonce_field( 'arpc-modal-form' ); ?>
			<input type="hidden" name="action" value="arpc_modal_form_action">
			<input type="hidden" name="arpc-popup-id" value="<?php echo esc_attr( get_the_ID() ); ?>" />
		</div>
		<?php if ( $has_categories ) : ?>
			<ul class="arpc_categories">
				<?php foreach ( $categories as $category ) : ?>
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
