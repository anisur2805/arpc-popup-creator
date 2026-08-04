<?php
/**
 * Template 3 view.
 *
 * @package ARPC\Popup
 *
 * @var string $popup_content  Rendered popup body content.
 * @var string $title          Popup title.
 * @var string $subtitle       Popup subtitle.
 * @var string $form_shortcode Custom form shortcode.
 * @var array  $categories     Interest category labels.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Already run through the_content in Frontend::render_popups().
$content = isset( $popup_content ) ? $popup_content : '';
?>
<div class="arpc__template arpc__template_style_3">
	<div class="arpc-popup-creator-body-inner">
		<?php if ( $title ) : ?>
			<h3 class="arpc-popup-modal-title"><?php echo esc_html( $title ); ?></h3>
		<?php endif; ?>
		<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Post content already rendered through the_content, which kses-filters on save; escaping again would strip video and other embeds. ?>
		<div><?php echo $content; ?></div>

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
