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
?>
<div class="arpc__template arpc__template_style_3">
	<div class="arpc-popup-creator-body-inner">
		<?php if ( $title ) : ?>
			<h3 class="arpc-popup-modal-title"><?php echo esc_html( $title ); ?></h3>
		<?php endif; ?>
		<div><?php echo wp_kses_post( $content ); ?></div>
		<div class="arpc-popup-form">
			<?php
			if ( ! empty( $form_shortcode ) ) {
				echo do_shortcode( wp_kses_post( $form_shortcode ) );
			} else {
				echo do_shortcode( '[arpc_newsletter2]' );
			}
			?>
		</div>

		<?php if ( ! empty( $categories ) ) : ?>
			<ul class="arpc_categories">
				<?php foreach ( $categories as $category ) : ?>
					<li>
						<label>
							<input type="checkbox" name="" />
							<span><?php echo esc_html( $category ); ?></span>
						</label>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</div>
