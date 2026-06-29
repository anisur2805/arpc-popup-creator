<?php

namespace ARPC\Popup\Services;

/**
 * Popup Settings Service
 *
 * Stores, sanitizes, and evaluates popup settings.
 */
class Popup_Settings {

	/**
	 * Meta key used to persist popup settings.
	 *
	 * @var string
	 */
	const META_KEY = 'arpc_popup_settings';

	/**
	 * Get default settings.
	 *
	 * @return array
	 */
	public static function defaults() {
		return array(
			'enabled'                       => false,
			'trigger_mode'                  => 'load',
			'scroll_depth'                  => 50,
			'open_delay'                    => 0,
			'auto_close_delay'              => 0,
			'periodicity'                   => 'every_time',
			'period_value'                  => 24,
			'period_unit'                   => 'hour',
			'activity_mode'                 => 'always',
			'activity_start'                => '',
			'activity_end'                  => '',
			'open_selector'                 => '',
			'close_selector'                => '',
			'disable_link'                  => false,
			'close_overlay'                 => true,
			'prevent_scroll'                => true,
			'close_back'                    => true,
			'countdown_enabled'             => false,
			'countdown_target'              => '',
			'countdown_expire_text'         => '',
			'popup_type'                    => 'modal',
			'bar_position'                  => 'top',
			'overlay_color'                 => 'rgba(0, 0, 0, 0.7)',
			'overlay_blur'                  => false,
			'overlay_blur_amount'           => 4,
			'overlay_z_index'               => 999999,
			'layout_style'                  => 'box',
			'popup_position'                => 'center-center',
			'open_animation'                => 'select',
			'close_animation'               => 'select',
			'close_button_position'         => 'top-right',
			'hide_close_button'             => false,
			'close_tooltip_text'            => '',
			'tooltip_text_color'            => '#ffffff',
			'tooltip_background_color'      => '#111111',
			'close_button_outside'          => false,
			'close_button_icon_color'       => '#000000',
			'close_button_background_color' => '#ffffff',
			'close_button_icon_size'        => 18,
			'close_button_padding'          => array(
				'top'    => 8,
				'right'  => 8,
				'bottom' => 8,
				'left'   => 8,
			),
			'close_button_margin'           => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'close_button_border_radius'    => array(
				'top'    => 50,
				'right'  => 50,
				'bottom' => 50,
				'left'   => 50,
			),
			'visibility_roles'              => array( 'all' ),
			'hide_devices'                  => array(),
			'display_locations'             => array(
				array(
					'mode' => 'include',
					'type' => 'sitewide',
					'ids'  => array(),
				),
			),
		);
	}

	/**
	 * Get popup settings for a post.
	 *
	 * @param int $post_id Popup post ID.
	 * @return array
	 */
	public static function get( $post_id ) {
		$saved    = get_post_meta( $post_id, self::META_KEY, true );
		$settings = is_array( $saved ) ? $saved : array();

		$settings = self::merge_dimension_sets( $settings );
		$settings = wp_parse_args( $settings, self::legacy_defaults( $post_id ) );
		$settings = wp_parse_args( $settings, self::defaults() );

		if ( empty( $settings['display_locations'] ) || ! is_array( $settings['display_locations'] ) ) {
			$settings['display_locations'] = self::defaults()['display_locations'];
		}

		return $settings;
	}

	/**
	 * Save popup settings.
	 *
	 * @param int   $post_id Popup post ID.
	 * @param array $raw_settings Raw settings payload.
	 * @return array
	 */
	public static function save( $post_id, $raw_settings ) {
		$settings = self::sanitize( $raw_settings );
		update_post_meta( $post_id, self::META_KEY, $settings );
		update_post_meta( $post_id, 'arpc_active', ! empty( $settings['enabled'] ) );

		return $settings;
	}

	/**
	 * Sanitize popup settings payload.
	 *
	 * @param array $raw_settings Raw settings payload.
	 * @return array
	 */
	public static function sanitize( $raw_settings ) {
		$raw_settings = is_array( $raw_settings ) ? $raw_settings : array();
		$defaults     = self::defaults();

		$settings = array(
			'enabled'                       => ! empty( $raw_settings['enabled'] ),
			'trigger_mode'                  => self::sanitize_choice( $raw_settings, 'trigger_mode', self::free_trigger_modes(), $defaults['trigger_mode'] ),
			'scroll_depth'                  => (int) self::sanitize_choice( $raw_settings, 'scroll_depth', array( '25', '50', '75' ), (string) $defaults['scroll_depth'] ),
			'open_delay'                    => self::sanitize_int( $raw_settings, 'open_delay', 0, 86400, $defaults['open_delay'] ),
			'auto_close_delay'              => self::sanitize_int( $raw_settings, 'auto_close_delay', 0, 86400, $defaults['auto_close_delay'] ),
			'periodicity'                   => self::sanitize_choice( $raw_settings, 'periodicity', array( 'every_time', 'once_per_period', 'once_only' ), $defaults['periodicity'] ),
			'period_value'                  => self::sanitize_int( $raw_settings, 'period_value', 1, 3650, $defaults['period_value'] ),
			'period_unit'                   => self::sanitize_choice( $raw_settings, 'period_unit', array( 'hour' ), $defaults['period_unit'] ),
			'activity_mode'                 => self::sanitize_choice( $raw_settings, 'activity_mode', array( 'always', 'certain_period' ), $defaults['activity_mode'] ),
			'activity_start'                => self::sanitize_datetime( $raw_settings, 'activity_start' ),
			'activity_end'                  => self::sanitize_datetime( $raw_settings, 'activity_end' ),
			'open_selector'                 => self::sanitize_text( $raw_settings, 'open_selector' ),
			'close_selector'                => self::sanitize_text( $raw_settings, 'close_selector' ),
			'disable_link'                  => ! empty( $raw_settings['disable_link'] ),
			'countdown_enabled'             => ! empty( $raw_settings['countdown_enabled'] ),
			'countdown_target'              => self::sanitize_datetime( $raw_settings, 'countdown_target' ),
			'countdown_expire_text'         => self::sanitize_text( $raw_settings, 'countdown_expire_text' ),
			'popup_type'                    => self::sanitize_choice( $raw_settings, 'popup_type', array_keys( self::popup_type_choices() ), $defaults['popup_type'] ),
			'bar_position'                  => self::sanitize_choice( $raw_settings, 'bar_position', array( 'top', 'bottom' ), $defaults['bar_position'] ),
			'close_overlay'                 => ! empty( $raw_settings['close_overlay'] ),
			'prevent_scroll'                => ! empty( $raw_settings['prevent_scroll'] ),
			'close_back'                    => ! empty( $raw_settings['close_back'] ),
			'overlay_color'                 => self::sanitize_color_string( $raw_settings, 'overlay_color', $defaults['overlay_color'] ),
			'overlay_blur'                  => ! empty( $raw_settings['overlay_blur'] ),
			'overlay_blur_amount'           => self::sanitize_int( $raw_settings, 'overlay_blur_amount', 0, 40, $defaults['overlay_blur_amount'] ),
			'overlay_z_index'               => self::sanitize_int( $raw_settings, 'overlay_z_index', 1, 99999999, $defaults['overlay_z_index'] ),
			'layout_style'                  => self::sanitize_choice( $raw_settings, 'layout_style', array( 'box', 'full' ), $defaults['layout_style'] ),
			'popup_position'                => self::sanitize_choice( $raw_settings, 'popup_position', self::position_choices(), $defaults['popup_position'] ),
			'open_animation'                => self::sanitize_choice( $raw_settings, 'open_animation', array_keys( self::opening_animation_options() ), $defaults['open_animation'] ),
			'close_animation'               => self::sanitize_choice( $raw_settings, 'close_animation', array_keys( self::closing_animation_options() ), $defaults['close_animation'] ),
			'close_button_position'         => self::sanitize_choice( $raw_settings, 'close_button_position', array( 'top-left', 'top-center', 'top-right', 'bottom-left', 'bottom-center', 'bottom-right' ), $defaults['close_button_position'] ),
			'hide_close_button'             => ! empty( $raw_settings['hide_close_button'] ),
			'close_tooltip_text'            => self::sanitize_text( $raw_settings, 'close_tooltip_text' ),
			'tooltip_text_color'            => self::sanitize_color_string( $raw_settings, 'tooltip_text_color', $defaults['tooltip_text_color'] ),
			'tooltip_background_color'      => self::sanitize_color_string( $raw_settings, 'tooltip_background_color', $defaults['tooltip_background_color'] ),
			'close_button_outside'          => ! empty( $raw_settings['close_button_outside'] ),
			'close_button_icon_color'       => self::sanitize_color_string( $raw_settings, 'close_button_icon_color', $defaults['close_button_icon_color'] ),
			'close_button_background_color' => self::sanitize_color_string( $raw_settings, 'close_button_background_color', $defaults['close_button_background_color'] ),
			'close_button_icon_size'        => self::sanitize_int( $raw_settings, 'close_button_icon_size', 8, 96, $defaults['close_button_icon_size'] ),
			'close_button_padding'          => self::sanitize_box_values( isset( $raw_settings['close_button_padding'] ) ? $raw_settings['close_button_padding'] : array(), 0, 120, $defaults['close_button_padding'] ),
			'close_button_margin'           => self::sanitize_box_values( isset( $raw_settings['close_button_margin'] ) ? $raw_settings['close_button_margin'] : array(), -120, 120, $defaults['close_button_margin'] ),
			'close_button_border_radius'    => self::sanitize_box_values( isset( $raw_settings['close_button_border_radius'] ) ? $raw_settings['close_button_border_radius'] : array(), 0, 100, $defaults['close_button_border_radius'] ),
			'visibility_roles'              => self::sanitize_roles( isset( $raw_settings['visibility_roles'] ) ? $raw_settings['visibility_roles'] : array() ),
			'hide_devices'                  => self::sanitize_device_choices( isset( $raw_settings['hide_devices'] ) ? $raw_settings['hide_devices'] : array() ),
			'display_locations'             => self::sanitize_locations( isset( $raw_settings['display_locations'] ) ? $raw_settings['display_locations'] : array() ),
		);

		if ( 'certain_period' !== $settings['activity_mode'] ) {
			$settings['activity_start'] = '';
			$settings['activity_end']   = '';
		}

		if ( 'once_per_period' !== $settings['periodicity'] ) {
			$settings['period_value'] = $defaults['period_value'];
			$settings['period_unit']  = $defaults['period_unit'];
		}

		return $settings;
	}

	/**
	 * Determine whether a popup should render on the current request.
	 *
	 * @param int   $post_id Popup post ID.
	 * @param array $settings Popup settings.
	 * @return bool
	 */
	public static function matches_request( $post_id, $settings ) {
		if ( empty( $settings['enabled'] ) ) {
			return false;
		}

		if ( ! self::matches_activity_window( $settings ) ) {
			return false;
		}

		if ( ! self::matches_role_visibility( $settings ) ) {
			return false;
		}

		if ( ! self::matches_locations( $post_id, $settings ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the manual trigger key for a popup.
	 *
	 * @param int $post_id Popup post ID.
	 * @return string
	 */
	public static function manual_trigger_key( $post_id ) {
		return 'popup_' . absint( $post_id );
	}

	/**
	 * Get available role labels.
	 *
	 * @return array
	 */
	public static function role_labels() {
		$labels = array(
			'all'   => __( 'All User', 'arpc-popup-creator' ),
			'guest' => __( 'Guest', 'arpc-popup-creator' ),
		);

		if ( function_exists( 'get_editable_roles' ) ) {
			foreach ( get_editable_roles() as $role_key => $role_data ) {
				$labels[ $role_key ] = translate_user_role( $role_data['name'] );
			}
		}

		return $labels;
	}

	/**
	 * Trigger modes available in the free tier.
	 *
	 * @return array
	 */
	public static function free_trigger_modes() {
		return array( 'click', 'load', 'scroll' );
	}

	/**
	 * Trigger modes reserved for the pro tier.
	 *
	 * Shown disabled in the UI as an upgrade teaser; rejected on save.
	 *
	 * @return array
	 */
	public static function pro_trigger_modes() {
		return array(
			'exit'       => __( 'On Exit', 'arpc-popup-creator' ),
			'inactivity' => __( 'On Inactivity', 'arpc-popup-creator' ),
		);
	}

	/**
	 * Get popup type choices.
	 *
	 * @return array
	 */
	public static function popup_type_choices() {
		return array(
			'modal'            => __( 'Modal', 'arpc-popup-creator' ),
			'slide-in'         => __( 'Slide In', 'arpc-popup-creator' ),
			'notification-bar' => __( 'Notification Bar', 'arpc-popup-creator' ),
			'fullscreen'       => __( 'Fullscreen', 'arpc-popup-creator' ),
		);
	}

	/**
	 * Get popup position choices.
	 *
	 * @return array
	 */
	public static function position_choices() {
		return array(
			'top-left',
			'top-center',
			'top-right',
			'center-left',
			'center-center',
			'center-right',
			'bottom-left',
			'bottom-center',
			'bottom-right',
		);
	}

	/**
	 * Get opening animation options.
	 *
	 * @return array
	 */
	public static function opening_animation_options() {
		return array(
			'select'                 => __( 'Select', 'arpc-popup-creator' ),
			'popup-load'             => __( 'Popup Load', 'arpc-popup-creator' ),
			'zoom-center'            => __( 'Zoom Center', 'arpc-popup-creator' ),
			'zoom-center-rev'        => __( 'Zoom Center Rev', 'arpc-popup-creator' ),
			'slide-left'             => __( 'Slide Left', 'arpc-popup-creator' ),
			'slide-right-rev'        => __( 'Slide Right Rev', 'arpc-popup-creator' ),
			'slide-up'               => __( 'Slide Up', 'arpc-popup-creator' ),
			'slide-up-rev'           => __( 'Slide Up Rev', 'arpc-popup-creator' ),
			'slide-down'             => __( 'Slide Down', 'arpc-popup-creator' ),
			'slide-down-rev'         => __( 'Slide Down Rev', 'arpc-popup-creator' ),
			'fade-in-rev'            => __( 'Fade In Rev', 'arpc-popup-creator' ),
			'bounce'                 => __( 'Bounce', 'arpc-popup-creator' ),
			'pulse'                  => __( 'Pulse', 'arpc-popup-creator' ),
			'shake-x'                => __( 'ShakeX', 'arpc-popup-creator' ),
			'shake-y'                => __( 'ShakeY', 'arpc-popup-creator' ),
			'head-shake'             => __( 'headShake', 'arpc-popup-creator' ),
			'heart-beat'             => __( 'heartBeat', 'arpc-popup-creator' ),
			'bounce-in'              => __( 'BounceIn', 'arpc-popup-creator' ),
			'bounce-in-down'         => __( 'BounceInDown', 'arpc-popup-creator' ),
			'bounce-in-left'         => __( 'BounceInLeft', 'arpc-popup-creator' ),
			'bounce-in-right'        => __( 'BounceInRight', 'arpc-popup-creator' ),
			'bounce-in-up'           => __( 'BounceInUp', 'arpc-popup-creator' ),
			'fade-in-down'           => __( 'FadeInDown', 'arpc-popup-creator' ),
			'fade-in-down-big'       => __( 'FadeInDownBig', 'arpc-popup-creator' ),
			'fade-in-left'           => __( 'FadeInLeft', 'arpc-popup-creator' ),
			'fade-in-left-big'       => __( 'FadeInLeftBig', 'arpc-popup-creator' ),
			'fade-in-right'          => __( 'FadeInRight', 'arpc-popup-creator' ),
			'fade-in-right-big'      => __( 'FadeInRightBig', 'arpc-popup-creator' ),
			'fade-in-up-big'         => __( 'FadeInUpBig', 'arpc-popup-creator' ),
			'fade-out'               => __( 'FadeOut', 'arpc-popup-creator' ),
			'fade-out-down'          => __( 'FadeOutDown', 'arpc-popup-creator' ),
			'fade-out-left'          => __( 'FadeOutLeft', 'arpc-popup-creator' ),
			'fade-out-right'         => __( 'FadeOutRight', 'arpc-popup-creator' ),
			'fade-out-up'            => __( 'FadeOutUp', 'arpc-popup-creator' ),
			'flip-in-x'              => __( 'FlipInX', 'arpc-popup-creator' ),
			'flip-in-y'              => __( 'FlipInY', 'arpc-popup-creator' ),
			'light-speed-in-right'   => __( 'LightSpeedInRight', 'arpc-popup-creator' ),
			'light-speed-in-left'    => __( 'LightSpeedInLeft', 'arpc-popup-creator' ),
			'light-speed-out-right'  => __( 'LightSpeedOutRight', 'arpc-popup-creator' ),
			'light-speed-out-left'   => __( 'LightSpeedOutLeft', 'arpc-popup-creator' ),
			'zoom-in'                => __( 'zoomIn', 'arpc-popup-creator' ),
			'zoom-in-down'           => __( 'zoomInDown', 'arpc-popup-creator' ),
			'zoom-out'               => __( 'zoomOut', 'arpc-popup-creator' ),
			'zoom-out-down'          => __( 'zoomOutDown', 'arpc-popup-creator' ),
		);
	}

	/**
	 * Get closing animation options.
	 *
	 * @return array
	 */
	public static function closing_animation_options() {
		return array(
			'select'                => __( 'Select', 'arpc-popup-creator' ),
			'popup-load'            => __( 'Popup Load', 'arpc-popup-creator' ),
			'zoom-center'           => __( 'Zoom Center', 'arpc-popup-creator' ),
			'zoom-center-rev'       => __( 'Zoom Center Rev', 'arpc-popup-creator' ),
			'slide-left'            => __( 'Slide Left', 'arpc-popup-creator' ),
			'slide-right-rev'       => __( 'Slide Right Rev', 'arpc-popup-creator' ),
			'slide-up'              => __( 'Slide Up', 'arpc-popup-creator' ),
			'slide-up-rev'          => __( 'Slide Up Rev', 'arpc-popup-creator' ),
			'slide-down'            => __( 'Slide Down', 'arpc-popup-creator' ),
			'slide-down-rev'        => __( 'Slide Down Rev', 'arpc-popup-creator' ),
			'fade-in-rev'           => __( 'Fade In Rev', 'arpc-popup-creator' ),
			'bounce'                => __( 'Bounce', 'arpc-popup-creator' ),
			'shake-x'               => __( 'ShakeX', 'arpc-popup-creator' ),
			'shake-y'               => __( 'ShakeY', 'arpc-popup-creator' ),
			'bounce-in'             => __( 'BounceIn', 'arpc-popup-creator' ),
			'bounce-in-up'          => __( 'BounceInUp', 'arpc-popup-creator' ),
			'fade-in-down'          => __( 'FadeInDown', 'arpc-popup-creator' ),
			'fade-in-down-big'      => __( 'FadeInDownBig', 'arpc-popup-creator' ),
			'fade-in-right'         => __( 'FadeInRight', 'arpc-popup-creator' ),
			'fade-in-up-big'        => __( 'FadeInUpBig', 'arpc-popup-creator' ),
			'fade-out'              => __( 'FadeOut', 'arpc-popup-creator' ),
			'fade-out-down'         => __( 'FadeOutDown', 'arpc-popup-creator' ),
			'fade-out-left'         => __( 'FadeOutLeft', 'arpc-popup-creator' ),
			'fade-out-right'        => __( 'FadeOutRight', 'arpc-popup-creator' ),
			'fade-out-up'           => __( 'FadeOutUp', 'arpc-popup-creator' ),
			'light-speed-in-left'   => __( 'LightSpeedInLeft', 'arpc-popup-creator' ),
			'light-speed-out-right' => __( 'LightSpeedOutRight', 'arpc-popup-creator' ),
			'light-speed-out-left'  => __( 'LightSpeedOutLeft', 'arpc-popup-creator' ),
			'zoom-out'              => __( 'zoomOut', 'arpc-popup-creator' ),
			'zoom-out-down'         => __( 'zoomOutDown', 'arpc-popup-creator' ),
		);
	}

	/**
	 * Get supported location target choices.
	 *
	 * @return array
	 */
	public static function location_type_choices() {
		$choices = array(
			'sitewide' => __( 'Sitewide', 'arpc-popup-creator' ),
			'page'     => __( 'Pages', 'arpc-popup-creator' ),
			'post'     => __( 'Posts', 'arpc-popup-creator' ),
			'category' => __( 'Categories', 'arpc-popup-creator' ),
			'post_tag' => __( 'Tags', 'arpc-popup-creator' ),
		);

		$post_types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);

		foreach ( $post_types as $post_type ) {
			if ( in_array( $post_type->name, array( 'post', 'page', 'attachment', 'arpc_popup' ), true ) ) {
				continue;
			}

			$choices[ $post_type->name ] = $post_type->labels->name;
		}

		return $choices;
	}

	/**
	 * Get available content choices for location targeting.
	 *
	 * @return array
	 */
	public static function location_target_sources() {
		$sources = array(
			'page'     => array(
				'label' => __( 'Pages', 'arpc-popup-creator' ),
				'items' => self::post_items(
					get_pages(
						array(
							'sort_column' => 'post_title',
							'sort_order'  => 'ASC',
						)
					)
				),
			),
			'post'     => array(
				'label' => __( 'Posts', 'arpc-popup-creator' ),
				'items' => self::post_items(
					get_posts(
						array(
							'post_type'        => 'post',
							'post_status'      => 'publish',
							'numberposts'      => 200,
							'orderby'          => 'title',
							'order'            => 'ASC',
							'suppress_filters' => false,
						)
					)
				),
			),
			'category' => array(
				'label' => __( 'Categories', 'arpc-popup-creator' ),
				'items' => self::term_items( 'category' ),
			),
			'post_tag' => array(
				'label' => __( 'Tags', 'arpc-popup-creator' ),
				'items' => self::term_items( 'post_tag' ),
			),
		);

		foreach ( self::location_type_choices() as $type => $label ) {
			if ( isset( $sources[ $type ] ) || 'sitewide' === $type ) {
				continue;
			}

			$sources[ $type ] = array(
				'label' => $label,
				'items' => self::post_items(
					get_posts(
						array(
							'post_type'        => $type,
							'post_status'      => 'publish',
							'numberposts'      => 200,
							'orderby'          => 'title',
							'order'            => 'ASC',
							'suppress_filters' => false,
						)
					)
				),
			);
		}

		return $sources;
	}

	/**
	 * Normalize post objects into id/label pairs.
	 *
	 * @param array $posts Post objects.
	 * @return array
	 */
	private static function post_items( $posts ) {
		$items = array();

		foreach ( (array) $posts as $post ) {
			$items[] = array(
				'id'    => (int) $post->ID,
				'label' => $post->post_title,
			);
		}

		return $items;
	}

	/**
	 * Normalize taxonomy terms into id/label pairs.
	 *
	 * @param string $taxonomy Taxonomy slug.
	 * @return array
	 */
	private static function term_items( $taxonomy ) {
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'number'     => 200,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		$items = array();

		foreach ( $terms as $term ) {
			$items[] = array(
				'id'    => (int) $term->term_id,
				'label' => $term->name,
			);
		}

		return $items;
	}

	/**
	 * Normalize an animation slug into a CSS animation family.
	 *
	 * @param string $slug Animation slug.
	 * @return string
	 */
	public static function animation_family( $slug ) {
		$families = array(
			'select'                => 'select',
			'popup-load'            => 'popup-load',
			'zoom-center'           => 'zoom-in',
			'zoom-center-rev'       => 'zoom-out',
			'slide-left'            => 'slide-left',
			'slide-right-rev'       => 'slide-right',
			'slide-up'              => 'slide-up',
			'slide-up-rev'          => 'slide-up-rev',
			'slide-down'            => 'slide-down',
			'slide-down-rev'        => 'slide-down-rev',
			'fade-in-rev'           => 'fade-in-rev',
			'bounce'                => 'bounce',
			'pulse'                 => 'pulse',
			'shake-x'               => 'shake-x',
			'shake-y'               => 'shake-y',
			'head-shake'            => 'head-shake',
			'heart-beat'            => 'heart-beat',
			'bounce-in'             => 'bounce-in',
			'bounce-in-down'        => 'bounce-in-down',
			'bounce-in-left'        => 'bounce-in-left',
			'bounce-in-right'       => 'bounce-in-right',
			'bounce-in-up'          => 'bounce-in-up',
			'fade-in-down'          => 'fade-in-down',
			'fade-in-down-big'      => 'fade-in-down-big',
			'fade-in-left'          => 'fade-in-left',
			'fade-in-left-big'      => 'fade-in-left-big',
			'fade-in-right'         => 'fade-in-right',
			'fade-in-right-big'     => 'fade-in-right-big',
			'fade-in-up-big'        => 'fade-in-up-big',
			'fade-out'              => 'fade-out',
			'fade-out-down'         => 'fade-out-down',
			'fade-out-left'         => 'fade-out-left',
			'fade-out-right'        => 'fade-out-right',
			'fade-out-up'           => 'fade-out-up',
			'flip-in-x'             => 'flip-in-x',
			'flip-in-y'             => 'flip-in-y',
			'light-speed-in-right'  => 'light-speed-in-right',
			'light-speed-in-left'   => 'light-speed-in-left',
			'light-speed-out-right' => 'light-speed-out-right',
			'light-speed-out-left'  => 'light-speed-out-left',
			'zoom-in'               => 'zoom-in',
			'zoom-in-down'          => 'zoom-in-down',
			'zoom-out'              => 'zoom-out',
			'zoom-out-down'         => 'zoom-out-down',
		);

		return isset( $families[ $slug ] ) ? $families[ $slug ] : 'select';
	}

	/**
	 * Build legacy defaults from old meta values.
	 *
	 * @param int $post_id Popup post ID.
	 * @return array
	 */
	private static function legacy_defaults( $post_id ) {
		$legacy_page = absint( get_post_meta( $post_id, 'arpc_ww_show', true ) );
		$legacy_mode = get_post_meta( $post_id, 'arpc_show_on_exit', true );
		$enabled     = (bool) get_post_meta( $post_id, 'arpc_active', true );

		$display_locations = array(
			array(
				'mode' => 'include',
				'type' => $legacy_page ? 'page' : 'sitewide',
				'ids'  => $legacy_page ? array( $legacy_page ) : array(),
			),
		);

		$legacy = array(
			'enabled'           => $enabled,
			'open_delay'        => absint( get_post_meta( $post_id, 'arpc_show_in_delay', true ) ),
			'auto_close_delay'  => absint( get_post_meta( $post_id, 'arpc_auto_hide_in', true ) ),
			'display_locations' => $display_locations,
		);

		// Only map trigger_mode when the legacy meta actually exists; otherwise
		// new popups would inherit a legacy default instead of defaults().
		if ( '' !== (string) $legacy_mode ) {
			$legacy['trigger_mode'] = '2' === (string) $legacy_mode ? 'scroll' : 'load';
		}

		return $legacy;
	}

	/**
	 * Merge box-value settings with defaults.
	 *
	 * @param array $settings Raw saved settings.
	 * @return array
	 */
	private static function merge_dimension_sets( $settings ) {
		$sets = array(
			'close_button_padding'       => self::defaults()['close_button_padding'],
			'close_button_margin'        => self::defaults()['close_button_margin'],
			'close_button_border_radius' => self::defaults()['close_button_border_radius'],
		);

		foreach ( $sets as $key => $default_values ) {
			$current          = isset( $settings[ $key ] ) && is_array( $settings[ $key ] ) ? $settings[ $key ] : array();
			$settings[ $key ] = wp_parse_args( $current, $default_values );
		}

		return $settings;
	}

	/**
	 * Match visibility roles.
	 *
	 * @param array $settings Popup settings.
	 * @return bool
	 */
	private static function matches_role_visibility( $settings ) {
		$roles = isset( $settings['visibility_roles'] ) && is_array( $settings['visibility_roles'] ) ? $settings['visibility_roles'] : array( 'all' );

		if ( in_array( 'all', $roles, true ) ) {
			return true;
		}

		if ( ! is_user_logged_in() ) {
			return in_array( 'guest', $roles, true );
		}

		$user = wp_get_current_user();
		if ( empty( $user->roles ) || ! is_array( $user->roles ) ) {
			return false;
		}

		return (bool) array_intersect( $roles, $user->roles );
	}

	/**
	 * Match popup activity window.
	 *
	 * @param array $settings Popup settings.
	 * @return bool
	 */
	private static function matches_activity_window( $settings ) {
		if ( 'certain_period' !== $settings['activity_mode'] ) {
			return true;
		}

		$now = current_time( 'timestamp' );

		if ( ! empty( $settings['activity_start'] ) ) {
			$start = strtotime( $settings['activity_start'] );
			if ( $start && $now < $start ) {
				return false;
			}
		}

		if ( ! empty( $settings['activity_end'] ) ) {
			$end = strtotime( $settings['activity_end'] );
			if ( $end && $now > $end ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Match request locations.
	 *
	 * @param int   $post_id Popup post ID.
	 * @param array $settings Popup settings.
	 * @return bool
	 */
	private static function matches_locations( $post_id, $settings ) {
		$locations = isset( $settings['display_locations'] ) && is_array( $settings['display_locations'] ) ? $settings['display_locations'] : array();
		$includes  = array();
		$excludes  = array();

		foreach ( $locations as $location ) {
			$location = wp_parse_args(
				$location,
				array(
					'mode' => 'include',
					'type' => 'sitewide',
					'ids'  => array(),
				)
			);

			if ( 'exclude' === $location['mode'] ) {
				$excludes[] = $location;
			} else {
				$includes[] = $location;
			}
		}

		foreach ( $excludes as $location ) {
			if ( self::location_matches_request( $location ) ) {
				return false;
			}
		}

		if ( empty( $includes ) ) {
			return true;
		}

		foreach ( $includes as $location ) {
			if ( self::location_matches_request( $location ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Match one location rule.
	 *
	 * @param array $location Location rule.
	 * @return bool
	 */
	private static function location_matches_request( $location ) {
		$type = isset( $location['type'] ) ? $location['type'] : 'sitewide';
		$ids  = isset( $location['ids'] ) && is_array( $location['ids'] ) ? array_map( 'absint', $location['ids'] ) : array();

		if ( 'sitewide' === $type ) {
			return true;
		}

		if ( 'page' === $type ) {
			if ( empty( $ids ) ) {
				return is_page();
			}

			return is_page( $ids );
		}

		if ( 'post' === $type ) {
			if ( empty( $ids ) ) {
				return is_singular( 'post' );
			}

			return is_single( $ids );
		}

		if ( 'category' === $type ) {
			if ( empty( $ids ) ) {
				return is_category();
			}

			return is_category( $ids ) || ( is_singular( 'post' ) && has_category( $ids ) );
		}

		if ( 'post_tag' === $type ) {
			if ( empty( $ids ) ) {
				return is_tag();
			}

			return is_tag( $ids ) || ( is_singular( 'post' ) && has_tag( $ids ) );
		}

		if ( isset( self::location_type_choices()[ $type ] ) ) {
			if ( empty( $ids ) ) {
				return is_singular( $type );
			}

			return is_singular( $type ) && in_array( get_queried_object_id(), $ids, true );
		}

		return false;
	}

	/**
	 * Sanitize a text field.
	 *
	 * @param array  $payload Raw payload.
	 * @param string $key Field key.
	 * @return string
	 */
	private static function sanitize_text( $payload, $key ) {
		return isset( $payload[ $key ] ) ? sanitize_text_field( wp_unslash( $payload[ $key ] ) ) : '';
	}

	/**
	 * Sanitize an integer field.
	 *
	 * @param array  $payload Raw payload.
	 * @param string $key Field key.
	 * @param int    $min Minimum value.
	 * @param int    $max Maximum value.
	 * @param int    $default Default value.
	 * @return int
	 */
	private static function sanitize_int( $payload, $key, $min, $max, $default ) {
		if ( ! isset( $payload[ $key ] ) || '' === $payload[ $key ] ) {
			return $default;
		}

		$value = intval( $payload[ $key ] );
		if ( $value < $min ) {
			return $min;
		}

		if ( $value > $max ) {
			return $max;
		}

		return $value;
	}

	/**
	 * Sanitize a field with predefined choices.
	 *
	 * @param array  $payload Raw payload.
	 * @param string $key Field key.
	 * @param array  $choices Allowed choices.
	 * @param string $default Default value.
	 * @return string
	 */
	private static function sanitize_choice( $payload, $key, $choices, $default ) {
		$value = isset( $payload[ $key ] ) && is_scalar( $payload[ $key ] ) ? sanitize_text_field( wp_unslash( (string) $payload[ $key ] ) ) : $default;
		return in_array( $value, $choices, true ) ? $value : $default;
	}

	/**
	 * Sanitize a color-like string.
	 *
	 * @param array  $payload Raw payload.
	 * @param string $key Field key.
	 * @param string $default Default value.
	 * @return string
	 */
	private static function sanitize_color_string( $payload, $key, $default ) {
		$value = isset( $payload[ $key ] ) ? sanitize_text_field( wp_unslash( $payload[ $key ] ) ) : '';

		if ( preg_match( '/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $value ) ) {
			return $value;
		}

		if ( preg_match( '/^rgba?\([0-9\.\,\s]+\)$/', $value ) ) {
			return $value;
		}

		return $default;
	}

	/**
	 * Sanitize a datetime-local string.
	 *
	 * @param array  $payload Raw payload.
	 * @param string $key Field key.
	 * @return string
	 */
	private static function sanitize_datetime( $payload, $key ) {
		$value = isset( $payload[ $key ] ) ? sanitize_text_field( wp_unslash( $payload[ $key ] ) ) : '';

		if ( preg_match( '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $value ) ) {
			return $value;
		}

		return '';
	}

	/**
	 * Sanitize box-value inputs.
	 *
	 * @param array $values Raw values.
	 * @param int   $min Minimum allowed.
	 * @param int   $max Maximum allowed.
	 * @param array $defaults Default values.
	 * @return array
	 */
	private static function sanitize_box_values( $values, $min, $max, $defaults ) {
		$sanitized = array();
		$values    = is_array( $values ) ? $values : array();

		foreach ( $defaults as $side => $default ) {
			$current = isset( $values[ $side ] ) && '' !== $values[ $side ] ? intval( $values[ $side ] ) : $default;

			if ( $current < $min ) {
				$current = $min;
			}

			if ( $current > $max ) {
				$current = $max;
			}

			$sanitized[ $side ] = $current;
		}

		return $sanitized;
	}

	/**
	 * Sanitize visibility roles.
	 *
	 * @param array $roles Raw roles.
	 * @return array
	 */
	private static function sanitize_roles( $roles ) {
		$roles   = is_array( $roles ) ? $roles : array();
		$allowed = array_keys( self::role_labels() );

		$roles = array_map( 'sanitize_text_field', wp_unslash( $roles ) );
		$roles = array_values( array_intersect( $roles, $allowed ) );

		if ( empty( $roles ) ) {
			$roles = array( 'all' );
		}

		return $roles;
	}

	/**
	 * Sanitize hidden device choices.
	 *
	 * @param array $devices Raw devices.
	 * @return array
	 */
	private static function sanitize_device_choices( $devices ) {
		$devices = is_array( $devices ) ? $devices : array();
		$devices = array_map( 'sanitize_text_field', wp_unslash( $devices ) );

		return array_values(
			array_intersect(
				$devices,
				array( 'mobile', 'tablet', 'desktop' )
			)
		);
	}

	/**
	 * Sanitize display location rules.
	 *
	 * @param array $locations Raw locations.
	 * @return array
	 */
	private static function sanitize_locations( $locations ) {
		$locations = is_array( $locations ) ? $locations : array();
		$cleaned   = array();

		foreach ( $locations as $location ) {
			if ( ! is_array( $location ) ) {
				continue;
			}

			$mode = isset( $location['mode'] ) && 'exclude' === $location['mode'] ? 'exclude' : 'include';
			$allowed_types = array_keys( self::location_type_choices() );
			$type          = isset( $location['type'] ) && in_array( $location['type'], $allowed_types, true ) ? $location['type'] : 'sitewide';
			$ids  = isset( $location['ids'] ) ? $location['ids'] : array();

			if ( ! is_array( $ids ) ) {
				$ids = explode( ',', (string) $ids );
			}

			$ids = array_filter( array_map( 'absint', $ids ) );

			$cleaned[] = array(
				'mode' => $mode,
				'type' => $type,
				'ids'  => array_values( $ids ),
			);
		}

		if ( empty( $cleaned ) ) {
			$cleaned[] = self::defaults()['display_locations'][0];
		}

		return $cleaned;
	}
}
