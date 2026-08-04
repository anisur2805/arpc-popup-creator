<?php
/**
 * Template 2 view.
 *
 * @package ARPC\Popup
 *
 * @var string $popup_content  Rendered popup body content.
 * @var string $title          Popup title.
 * @var string $subtitle       Popup subtitle.
 * @var string $feature_image  Feature image URL.
 * @var string $popup_url      Popup link URL.
 * @var string $form_shortcode Custom form shortcode.
 * @var array  $categories     Interest category labels.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Already run through the_content in Frontend::render_popups().
$content = isset( $popup_content ) ? $popup_content : '';
?>
<div class="arpc__template arpc__template_style_2">
	<div class="arpc__feature-image-wrapper">
		<?php if ( $feature_image ) : ?>
			<div class="arpc-popup-image">
				<?php if ( $popup_url ) : ?>
					<a target="_blank" href="<?php echo esc_url( $popup_url ); ?>">
						<img src="<?php echo esc_url( $feature_image ); ?>" alt="<?php esc_attr_e( 'Popup', 'arpc-popup-creator' ); ?>" />
					</a>
				<?php else : ?>
					<img src="<?php echo esc_url( $feature_image ); ?>" alt="<?php esc_attr_e( 'Popup', 'arpc-popup-creator' ); ?>" />
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="arpc-popup-creator-body-inner">
		<p><strong><?php esc_html_e( 'Subscribe Now', 'arpc-popup-creator' ); ?></strong></p>
		<?php if ( $title ) : ?>
			<h3 class="arpc-popup-modal-title"><?php echo esc_html( $title ); ?></h3>
		<?php endif; ?>
		<?php if ( '' !== trim( (string) $content ) ) : ?>
			<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Post content already rendered through the_content, which kses-filters on save; escaping again would strip video and other embeds. ?>
			<div><?php echo $content; ?></div>
		<?php else : ?>
			<p><?php esc_html_e( 'Do subscribe to receive updates on new arrivals, special offers & our promotions', 'arpc-popup-creator' ); ?></p>
		<?php endif; ?>
		<div class="arpc-popup-form">
			<?php
			if ( ! empty( $form_shortcode ) ) {
				echo do_shortcode( wp_kses_post( $form_shortcode ) );
			} else {
				include ARPC_PATH . '/includes/Views/frontend/newsletter-form.php';
			}
			?>
		</div>
	</div>
</div>
