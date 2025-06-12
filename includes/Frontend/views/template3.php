<div class="arpc__template arpc__template_style_3">
	<div class="arpc-popup-creator-body-inner">
		<?php
		if ( $title ) :
			printf(
				'<h3 class="arpc-popup-modal-title">%s</h3>',
				esc_html( $title )
			);
			endif;

			if ( $content ) {
				printf(
					'<div class="arpc-popup-modal-content">%s</div>',
					wp_kses_post( get_the_content() )
				);
			}
		?>
		<div class="arpc-popup-form">
			<?php echo do_shortcode( '[arpc_newsletter2]' ); ?>
		</div>

		<ul class="arpc_categories">
			<li>
				<label>
					<input type="checkbox" name="" />
					<span>Tutorials</span>
				</label>
			</li>
			<li>
				<label>
					<input type="checkbox" name="" />
					<span>Products</span>
				</label>
			</li>
		</ul>
	</div>

	<button class="arpc-close-button"></button>

</div>