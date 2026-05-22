<?php

namespace ARPC\Popup\Controllers;

/**
 * Frontend Controller
 *
 * Handles frontend popup display and shortcodes.
 */
class Frontend {

	/**
	 * Constructor - initialize frontend functionality.
	 */
	public function __construct() {
		add_action( 'wp_footer', array( $this, 'render_popups' ) );
		add_shortcode( 'arpc_newsletter', array( $this, 'render_newsletter_shortcode' ) );
		add_shortcode( 'arpc_newsletter2', array( $this, 'render_newsletter2_shortcode' ) );
	}

	/**
	 * Render active popups in footer.
	 */
	public function render_popups() {
		// Debug: verify hook is called
		echo '<!-- ARPC: render_popups called -->';

		$options = get_option( 'arpc_setting_opn' );
		$setting = get_option( 'arpc_general_setting' );
		$value   = isset( $setting['arpc_general_settings_template'] ) ? $setting['arpc_general_settings_template'] : 'template1';

		wp_enqueue_style( 'arpc-style' );
		wp_enqueue_script( 'plain-modal' );
		wp_enqueue_script( 'arpc-main' );
		wp_enqueue_script( 'arpc-modal-form' );

		$args = array(
			'post_type'   => 'arpc_popup',
			'post_status' => 'publish',
			'meta_key'    => 'arpc_active',
			'meta_value'  => 1,
		);

		$arpc_query = new \WP_Query( $args );

		// Debug: check if popups are found
		echo '<!-- ARPC Debug: Found ' . $arpc_query->found_posts . ' active popups -->';

		while ( $arpc_query->have_posts() ) {
			$arpc_query->the_post();

			$image_size    = get_post_meta( get_the_ID(), 'arpc_image_size', true );
			$exit          = get_post_meta( get_the_ID(), 'arpc_show_on_exit', true );
			$delay         = get_post_meta( get_the_ID(), 'arpc_show_in_delay', true );
			$title         = get_post_meta( get_the_ID(), 'arpc_title', true );
			$subtitle      = get_post_meta( get_the_ID(), 'arpc_subtitle', true );
			$feature_image = get_the_post_thumbnail_url( get_the_ID(), $image_size );
			$popup_url     = get_post_meta( get_the_ID(), 'arpc_popup_url', true );
			$show_in_obj   = get_post_meta( get_the_ID(), 'arpc_ww_show', true );
			$auto_hide     = get_post_meta( get_the_ID(), 'arpc_auto_hide_pu', true );

			$delay = $delay ? $delay * 1000 : 0;

			$show_in    = get_post( $show_in_obj );
			$post       = get_post( $show_in_obj );
			$slug       = $post ? $post->post_name : '';
			$show_in_id = $show_in ? $show_in->ID : 0;
			$template   = isset( $options['arpc_choose_temp'] ) ? $options['arpc_choose_temp'] : 'template1';

			// Debug: check page matching
			echo '<!-- ARPC Debug: Popup ' . get_the_ID() . ' targets page ' . $show_in_id . ', is_page: ' . (is_page( $show_in_id ) ? 'yes' : 'no') . ' -->';

			if ( is_page( $show_in_id ) ) {
				include ARPC_PATH . '/includes/Views/frontend/modal.php';
			}
		}

		wp_reset_query();
	}

	/**
	 * Render newsletter shortcode.
	 *
	 * @param array  $atts    Shortcode attributes.
	 * @param string $content Shortcode content.
	 * @return string
	 */
	public function render_newsletter_shortcode( $atts, $content = '' ) {
		$atts = shortcode_atts(
			array(
				'title'    => __( 'Sign up for Snappy News!', 'arpc-popup-creator' ),
				'content'  => __( 'Get Free WordPress Videos, Plugins, and Other Useful Resources', 'arpc-popup-creator' ),
				'popup_id' => 0,
			),
			$atts,
			'arpc_newsletter'
		);

		$popup_id = intval( $atts['popup_id'] );

		ob_start();
		include ARPC_PATH . '/includes/Views/frontend/signup-form.php';
		return ob_get_clean();
	}

	/**
	 * Render newsletter2 shortcode.
	 *
	 * @param array  $atts    Shortcode attributes.
	 * @param string $content Shortcode content.
	 * @return string
	 */
	public function render_newsletter2_shortcode( $atts, $content = '' ) {
		$atts = shortcode_atts(
			array(
				'title'   => __( 'Sign up for Snappy News!', 'arpc-popup-creator' ),
				'content' => __( 'Get Free WordPress Videos, Plugins, and Other Useful Resources', 'arpc-popup-creator' ),
			),
			$atts,
			'arpc_newsletter2'
		);

		ob_start();
		include ARPC_PATH . '/includes/Views/frontend/signup-form-3.php';
		return ob_get_clean();
	}
}
