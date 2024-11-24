<div class="arpc-popup-creator-wrapper" id="arpc-popup-creator-wrapper">
	<form action="" method="post">
		<div class="arpc-form-group-row">
			<input class="regular-text arpc_input" type="email" name="arpc-email" value="" placeholder="Enter your email" required/>
			<button type="submit" class="arpc_submit" name="arpc_submit" id="arpc_submit"><?php esc_attr_e( 'Count Me In!', 'arpc-popup-creator' ); ?></button>
			<?php wp_nonce_field( 'arpc-modal-form' ); ?>
			<input type="hidden" name="action" value="arpc_modal_form_action">
		</div>
		<p class="arpc-response hide"><?php echo esc_attr( 'Thanks for subscribe', 'arpc-popup-creator' ); ?></p>
	</form>
</div>