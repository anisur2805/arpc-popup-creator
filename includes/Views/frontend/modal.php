<?php
/**
 * Modal popup view template.
 *
 * @var int    $popup->ID    Popup post ID.
 * @var string $image_size   Image size.
 * @var mixed  $exit         Show on exit setting.
 * @var int    $delay        Delay in milliseconds.
 * @var string $title        Popup title.
 * @var string $subtitle     Popup subtitle.
 * @var string $feature_image Feature image URL.
 * @var string $popup_url    Popup link URL.
 * @var int    $show_in_id   Page ID to show on.
 * @var string $slug         Page slug.
 * @var mixed  $auto_hide    Auto hide setting.
 * @var string $template     Template name.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="arpc-popup-creator arpc-template arpc-<?php echo esc_attr( $template ); ?>"
     id="arpc-popup-creator"
     data-auto-hide="<?php echo ( 1 == $auto_hide ) ? 'yes' : 'no'; ?>"
     data-id="popup-<?php echo esc_attr( $popup->ID ); ?>"
     data-popup-image-size="<?php echo esc_attr( $image_size ); ?>"
     data-exit="<?php echo esc_attr( $exit ); ?>"
     data-delay="<?php echo esc_attr( $delay ); ?>"
     data-show="<?php echo esc_attr( $show_in_id ); ?>"
     data-page="<?php echo esc_attr( $slug ); ?>">
	<div class="arpc-popup-creator-body">
		<?php include ARPC_PATH . "/includes/Views/frontend/{$template}.php"; ?>
	</div>
</div>
