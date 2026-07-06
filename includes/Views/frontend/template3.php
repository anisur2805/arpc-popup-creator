<?php
/**
 * Template 3 view.
 *
 * @var string $title          Popup title.
 * @var string $subtitle       Popup subtitle.
 * @var string $form_shortcode Custom form shortcode.
 * @var array  $categories     Interest category labels.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$content = get_the_content();
$content = $content ? $content : __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'arpc-popup-creator' );

$has_categories = ! empty( $categories ) && empty( $form_shortcode );
?>
<div class="arpc__template arpc__template_style_3">
	<div class="arpc-popup-creator-body-inner">
		<?php if ( $title ) : ?>
			<h3 class="arpc-popup-modal-title"><?php echo esc_html( $title ); ?></h3>
		<?php endif; ?>
		<div><?php echo wp_kses_post( $content ); ?></div>

		<div class="arpc-popup-form">
			<div class="arpc-popup-creator-wrapper" id="arpc-popup-creator-wrapper">
				<form action="" method="post">
					<div class="arpc-form-group-row">
						<input class="regular-text arpc_input" type="email" name="arpc-email" value="" placeholder="<?php esc_attr_e( 'Enter your email', 'arpc-popup-creator' ); ?>" required/>
						<button type="submit" class="arpc_submit" name="arpc_submit"><?php esc_html_e( 'Count Me In!', 'arpc-popup-creator' ); ?></button>
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
		</div>
	</div>
</div>
