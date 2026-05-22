<?php

namespace ARPC\Popup\Controllers;

use ARPC\Popup\Models\Popup;

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
		$settings = get_option( 'arpc_general_setting' );
		$template = isset( $settings['arpc_general_settings_template'] ) ? $settings['arpc_general_settings_template'] : 'template1';

		wp_enqueue_style( 'arpc-style' );
		wp_enqueue_script( 'plain-modal' );
		wp_enqueue_script( 'arpc-main' );
		wp_enqueue_script( 'arpc-modal-form' );

		$popups = Popup::get_active();

		foreach ( $popups as $popup ) {
			$this->render_single_popup( $popup, $template );
		}
	}

	/**
	 * Render a single popup.
	 *
	 * @param \WP_Post $popup    Popup post object.
	 * @param string   $template Template name.
	 */
	private function render_single_popup( $popup, $template ) {
		$image_size    = Popup::get_meta( $popup->ID, 'arpc_image_size' );
		$exit          = Popup::get_meta( $popup->ID, 'arpc_show_on_exit' );
		$delay         = Popup::get_meta( $popup->ID, 'arpc_show_in_delay' );
		$title         = Popup::get_meta( $popup->ID, 'arpc_title' );
		$subtitle      = Popup::get_meta( $popup->ID, 'arpc_subtitle' );
		$feature_image = get_the_post_thumbnail_url( $popup->ID, $image_size );
		$popup_url     = Popup::get_meta( $popup->ID, 'arpc_popup_url' );
		$show_in_obj   = Popup::get_meta( $popup->ID, 'arpc_ww_show' );
		$auto_hide     = Popup::get_meta( $popup->ID, 'arpc_auto_hide_pu' );

		$delay = $delay ? $delay * 1000 : 0;

		$show_in = get_post( $show_in_obj );
		$slug    = $show_in ? $show_in->post_name : '';
		$show_in_id = $show_in ? $show_in->ID : 0;

		if ( ! is_page( $show_in_id ) ) {
			return;
		}

		include ARPC_PATH . '/includes/Views/frontend/modal.php';
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
