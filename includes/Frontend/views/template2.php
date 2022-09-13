<div class="arpc__template arpc__template_style_2">
	<div class="arpc__feature-image-wrapper">
		<?php if ($feature_image) : ?>
			<div class="arpc-popup-image">
				<?php if ($popup_url) { ?>
					<a target="_blank" href="<?php echo esc_url($popup_url); ?>">
						<img src="<?php echo esc_url($feature_image); ?>" alt="<?php _e('Popup', 'popup-creator') ?>" />
					</a>
				<?php } else { ?>
					<img src="<?php echo esc_url($feature_image); ?>" alt="<?php _e('Popup', 'popup-creator') ?>" />
				<?php } ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="arpc-popup-creator-body-inner">
		<p><strong>Subscribe Now</strong></p>
		<?php

			$content = wp_trim_words(get_the_content(), 5, '');
			if ($title) :
				printf('<h3 class="arpc-popup-modal-title">%s</h3>', $title);
			endif;
			// if ($subtitle) :
			// 	printf('<h4 class="arpc-popup-modal-title">%s</h4>', $subtitle);
			// endif;
			// printf('<div>%s</div>', $content);
		?>
		<p>Do subscribe to receive updates on new arrivals, special offers & our promotions</p>
		<div class="arpc-popup-form">
			<?php echo do_shortcode('[arpc_newsletter]'); ?>
		</div>
	</div>

	<button class="arpc-close-button"></button>

</div>