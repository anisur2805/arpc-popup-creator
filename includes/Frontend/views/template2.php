<div class="arpc__template arpc__template_style_2">
	<div class="arpc__feature-image-wrapper">
		<?php if ( $feature_image ) : ?>
			<div class="arpc-popup-image">
				<?php if ( $popup_url ) { ?>
					<a target="_blank" href="<?php echo esc_url( $popup_url ); ?>">
						<img src="<?php echo esc_url( $feature_image ); ?>" alt="<?php _e( 'Popup', 'popup-creator' ); ?>" />
					</a>
				<?php } else { ?>
					<img src="<?php echo esc_url( $feature_image ); ?>" alt="<?php _e( 'Popup', 'popup-creator' ); ?>" />
				<?php } ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="arpc-popup-creator-body-inner">
		<p><strong>Subscribe Now</strong></p>
		<?php
		if ( $title ) :
			printf(
				'<h3 class="arpc-popup-modal-title">%s</h3>',
				esc_html( $title )
			);
		endif;

		if ( $subtitle ) :
			 printf(
				'<h4 class="arpc-popup-modal-title">%s</h4>',
				esc_html( $subtitle )
			);
		endif;

		if ( $content ) {
			printf(
				'<div class="arpc-popup-modal-content">%s</div>',
				wp_kses_post( get_the_content() )
			);
		}

		printf(
			'<p>%s</p>',
			esc_html( 'Do subscribe to receive updates on new arrivals, special offers & our promotions' )
		);
		?>
		<div class="arpc-popup-form">
			<?php echo do_shortcode( '[arpc_newsletter]' ); ?>
		</div>
	</div>
	<button class="arpc-close-button"></button>
</div>