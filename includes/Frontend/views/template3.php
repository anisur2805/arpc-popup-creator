<div class="arpc__template arpc__template_style_3">
	<div class="arpc-popup-creator-body-inner">
		<?php
			$content = wp_trim_words(get_the_content(), 5, '' );
			$content = $content ? $content : __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.');
			if ($title) :
				printf('<h3 class="arpc-popup-modal-title">%s</h3>', $title);
			endif;
			printf('<div>%s</div>', $content);
		?>
		<div class="arpc-popup-form">
			<?php echo do_shortcode('[arpc_newsletter2]'); ?>
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