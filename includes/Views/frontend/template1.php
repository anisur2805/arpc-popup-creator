<?php
/**
 * Template 1 view.
 *
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

$content = get_the_content();
?>
<div class="arpc__template arpc__template_style_1">
	<div class="arpc-popup-creator-body-header">
		<?php if ( $title ) : ?>
			<h3 class="arpc-popup-modal-title"><?php echo esc_html( $title ); ?></h3>
		<?php endif; ?>
		<?php if ( $subtitle ) : ?>
			<h4 class="arpc-popup-modal-subtitle"><?php echo esc_html( $subtitle ); ?></h4>
		<?php endif; ?>
		<div><?php echo wp_kses_post( $content ); ?></div>
	</div>
	<div class="arpc-popup-creator-body-inner">
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
