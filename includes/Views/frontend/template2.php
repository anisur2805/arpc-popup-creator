<?php
/**
 * Template 2 view.
 *
 * @var string $title         Popup title.
 * @var string $subtitle      Popup subtitle.
 * @var string $feature_image Feature image URL.
 * @var string $popup_url     Popup link URL.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$content = wp_trim_words( get_the_content(), 5, '' );
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
		<p><?php esc_html_e( 'Do subscribe to receive updates on new arrivals, special offers & our promotions', 'arpc-popup-creator' ); ?></p>
		<div class="arpc-popup-form">
			<?php echo do_shortcode( '[arpc_newsletter]' ); ?>
		</div>
	</div>
</div>
