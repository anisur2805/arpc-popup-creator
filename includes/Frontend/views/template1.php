<div class="arpc__template arpc__template_style_1">
	<div class="arpc-popup-creator-body-header">
		<?php
		if ( $title ) :
			printf(
				'<h3 class="arpc-popup-modal-title">%s</h3>',
				esc_html( $title )
			);
		endif;

		if ( $subtitle ) :
			printf(
				'<h4 class="arpc-popup-modal-subtitle">%s</h4>',
				esc_html( $subtitle )
			);
		endif;

		$content = get_the_content();
		if ( $content ) {
			printf(
				'<div>%s</div>',
				wp_kses_post( $content )
			);
		}
		?>
	</div>
	<div class="arpc-popup-creator-body-inner">
		<?php if ( $feature_image ) : ?>
			<div class="arpc-popup-image">
				<?php
				$image_html = sprintf(
					'<img src="%s" alt="%s" />',
					esc_url( $feature_image ),
					esc_attr__( 'Popup', 'popup-creator' )
				);

				if ( $popup_url ) {
					printf(
						'<a target="_blank" href="%s">%s</a>',
						esc_url( $popup_url ),
						$image_html
					);
				} else {
					echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		<?php endif; ?>

		<div class="arpc-popup-form">
			<?php
			$form_html = do_shortcode( '[arpc_newsletter]' );
			printf(
				'%s',
				$form_html // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			?>
		</div>
	</div>
	<button class="arpc-close-button"></button>
</div>