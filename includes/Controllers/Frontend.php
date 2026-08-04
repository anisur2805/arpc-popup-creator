<?php
/**
 * Frontend popup rendering and shortcodes.
 *
 * @package ARPC\Popup
 */

namespace ARPC\Popup\Controllers;

use ARPC\Popup\Services\Popup_Settings;

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
		add_shortcode( 'arpc_popup_trigger', array( $this, 'render_popup_trigger_shortcode' ) );
	}

	/**
	 * Render active popups in footer.
	 */
	public function render_popups() {
		$options = get_option( 'arpc_setting_opn' );
		$setting = get_option( 'arpc_general_setting' );
		$value   = isset( $setting['arpc_general_settings_template'] ) ? $setting['arpc_general_settings_template'] : '';

		wp_enqueue_style( 'arpc-style' );
		wp_enqueue_script( 'arpc-main' );
		wp_enqueue_script( 'arpc-modal-form' );

		$args = array(
			'post_type'      => 'arpc_popup',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'   => 'arpc_active',
					'value' => 1,
				),
			),
		);

		// Preview mode: render only the requested popup, bypassing all
		// targeting/frequency rules. Gated to logged-in editors+ so visitors
		// cannot trigger arbitrary popups via query string.
		$preview_id = 0;
		if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
			$preview_id = isset( $_GET['arpc_popup_preview'] ) ? absint( $_GET['arpc_popup_preview'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Preview is capability-gated, not a write action.
		}

		$arpc_query = new \WP_Query( $args );

		while ( $arpc_query->have_posts() ) {
			$arpc_query->the_post();

			$popup_id       = get_the_ID();
			$popup_settings = Popup_Settings::get( $popup_id );

			if ( ! $preview_id && ! Popup_Settings::matches_request( $popup_id, $popup_settings ) ) {
				continue;
			}

			if ( $preview_id && $preview_id !== $popup_id ) {
				continue;
			}

			// Run the editor content through the_content so Gutenberg blocks,
			// embeds and shortcodes render. get_the_content() alone returns raw
			// block markup, which never reaches do_blocks().
			$popup_content = apply_filters( 'the_content', get_the_content() );

			$image_size     = get_post_meta( $popup_id, 'arpc_image_size', true );
			$title          = get_post_meta( $popup_id, 'arpc_title', true );
			$subtitle       = get_post_meta( $popup_id, 'arpc_subtitle', true );
			$feature_image  = $this->get_feature_image_url( $popup_id, $image_size );
			$popup_url      = get_post_meta( $popup_id, 'arpc_popup_url', true );
			$form_shortcode = get_post_meta( $popup_id, 'arpc_form_shortcode', true );
			$categories     = get_post_meta( $popup_id, 'arpc_categories', true );
			$categories     = is_array( $categories ) ? array_filter( array_map( 'trim', $categories ) ) : array();
			$template       = ! empty( $value ) ? $value : ( isset( $options['arpc_choose_temp'] ) ? $options['arpc_choose_temp'] : 'template1' );
			$popup_config   = array(
				'id'            => $popup_id,
				'triggerKey'    => Popup_Settings::manual_trigger_key( $popup_id ),
				'settings'      => $popup_settings,
				'hideDevices'   => isset( $popup_settings['hide_devices'] ) ? $popup_settings['hide_devices'] : array(),
				'openSelectors' => array_filter(
					array(
						'.' . Popup_Settings::manual_trigger_key( $popup_id ),
						'#' . Popup_Settings::manual_trigger_key( $popup_id ),
						'[data-arpc-trigger="' . Popup_Settings::manual_trigger_key( $popup_id ) . '"]',
						$popup_settings['open_selector'],
					)
				),
				'closeSelector' => $popup_settings['close_selector'],
			);

			include ARPC_PATH . '/includes/Views/frontend/modal.php';
			include ARPC_PATH . '/includes/Views/frontend/floating-button.php';
		}

		wp_reset_postdata();
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

	/**
	 * Render a popup trigger element.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function render_popup_trigger_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'id'    => '',
				'label' => __( 'Open Popup', 'arpc-popup-creator' ),
				'tag'   => 'button',
				'class' => '',
			),
			$atts,
			'arpc_popup_trigger'
		);

		$tag = in_array( $atts['tag'], array( 'button', 'a', 'span' ), true ) ? $atts['tag'] : 'button';
		$id  = sanitize_text_field( $atts['id'] );

		if ( is_numeric( $id ) ) {
			$id = Popup_Settings::manual_trigger_key( absint( $id ) );
		}

		if ( empty( $id ) ) {
			return '';
		}

		$class_names   = preg_split( '/\s+/', (string) $atts['class'] );
		$class_names   = array_filter( array_map( 'sanitize_html_class', $class_names ) );
		$class_names[] = 'arpc-popup-trigger';
		$class_name    = trim( implode( ' ', array_unique( $class_names ) ) );
		$label         = esc_html( $atts['label'] );

		if ( 'a' === $tag ) {
			return '<a href="#" class="' . esc_attr( $class_name ) . '" data-arpc-trigger="' . esc_attr( $id ) . '">' . $label . '</a>';
		}

		if ( 'span' === $tag ) {
			return '<span class="' . esc_attr( $class_name ) . '" data-arpc-trigger="' . esc_attr( $id ) . '">' . $label . '</span>';
		}

		return '<button type="button" class="' . esc_attr( $class_name ) . '" data-arpc-trigger="' . esc_attr( $id ) . '">' . $label . '</button>';
	}

	/**
	 * Resolve the featured image URL for a popup.
	 *
	 * @param int    $popup_id Popup post ID.
	 * @param string $image_size Requested image size.
	 * @return string
	 */
	private function get_feature_image_url( $popup_id, $image_size ) {
		$size_map = array(
			'landscape' => 'popup-creator-landscape',
			'square'    => 'popup-creator-square',
			'original'  => 'full',
		);

		$resolved_size = isset( $size_map[ $image_size ] ) ? $size_map[ $image_size ] : 'full';
		$image_url     = get_the_post_thumbnail_url( $popup_id, $resolved_size );

		if ( $image_url ) {
			return $image_url;
		}

		return get_post_meta( $popup_id, 'arpc_image_url', true );
	}
}
