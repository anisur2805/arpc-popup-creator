<?php

namespace ARPC\Popup\Services;

/**
 * Settings Service
 *
 * Consolidates all plugin settings registration and rendering.
 */
class Settings {

	/**
	 * Constructor - register settings hooks.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'register' ) );
	}

	/**
	 * Register settings, sections, and fields.
	 */
	public function register() {
		// General settings.
		register_setting( 'arpc-popup-general-settings', 'arpc_general_setting' );

		add_settings_section(
			'arpc_general_settings_section',
			__( 'General Settings Section', 'arpc-popup-creator' ),
			array( $this, 'general_section_callback' ),
			'arpc-popup-general-settings'
		);

		add_settings_field(
			'arpc_general_settings_template',
			__( 'Choose Template', 'arpc-popup-creator' ),
			array( $this, 'template_field_callback' ),
			'arpc-popup-general-settings',
			'arpc_general_settings_section'
		);

		// Legacy settings (for backward compatibility).
		register_setting( 'arpc_setting_opg', 'arpc_setting_opn' );

		add_settings_section(
			'arpc_section_tabbed',
			__( 'ARPC Settings', 'arpc-popup-creator' ),
			array( $this, 'legacy_section_callback' ),
			'arpc-popup-settings'
		);

		add_settings_field(
			'arpc_choose_temp',
			__( 'Choose Pill', 'arpc-popup-creator' ),
			array( $this, 'legacy_template_callback' ),
			'arpc-popup-settings',
			'arpc_section_tabbed',
			array(
				'label_for'        => 'arpc_choose_temp',
				'class'            => 'test',
				'arpc_custom_data' => 'custom',
			)
		);

		add_settings_field(
			'arpc_title',
			__( 'Choose Title', 'arpc-popup-creator' ),
			array( $this, 'title_field_callback' ),
			'arpc-popup-settings',
			'arpc_section_tabbed',
			array( 'label_for' => 'arpc_title' )
		);

		// Advanced settings.
		register_setting( 'arpc-popup-adv-settings', 'arpc_adv_setting' );

		add_settings_section(
			'arpc_adv_settings_section',
			__( 'Advance Settings Section', 'arpc-popup-creator' ),
			array( $this, 'adv_section_callback' ),
			'arpc-popup-adv-settings'
		);
	}

	/**
	 * General section callback.
	 */
	public function general_section_callback() {
		echo '<p>' . esc_html__( 'General Section Introduction.', 'arpc-popup-creator' ) . '</p>';
	}

	/**
	 * Advanced section callback.
	 */
	public function adv_section_callback() {
		echo '<p>' . esc_html__( 'Advanced Section Introduction.', 'arpc-popup-creator' ) . ' <small>' . esc_html__( 'More features coming soon.', 'arpc-popup-creator' ) . '</small></p>';
	}

	/**
	 * Legacy section callback.
	 */
	public function legacy_section_callback() {
		echo '<p>' . esc_html__( 'Choose the template you like!', 'arpc-popup-creator' ) . '</p>';
	}

	/**
	 * Template field callback for general settings.
	 */
	public function template_field_callback() {
		$setting = get_option( 'arpc_general_setting' );
		$value   = isset( $setting['arpc_general_settings_template'] ) ? $setting['arpc_general_settings_template'] : 'template1';

		include ARPC_PATH . '/includes/Views/admin/template-selector.php';
	}

	/**
	 * Legacy template callback.
	 *
	 * @param array $args Field arguments.
	 */
	public function legacy_template_callback( $args ) {
		$options = get_option( 'arpc_setting_opn' );
		?>
		<div class="arpc_tabbed_wrapper">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#choose_template"><?php esc_html_e( 'Choose template', 'arpc-popup-creator' ); ?></a></li>
				<li><a href="#overlay"><?php esc_html_e( 'Overlay', 'arpc-popup-creator' ); ?></a></li>
				<li><a href="#container"><?php esc_html_e( 'Container', 'arpc-popup-creator' ); ?></a></li>
				<li><a href="#title"><?php esc_html_e( 'Title', 'arpc-popup-creator' ); ?></a></li>
				<li><a href="#content"><?php esc_html_e( 'Content', 'arpc-popup-creator' ); ?></a></li>
				<li><a href="#close"><?php esc_html_e( 'Close Icon', 'arpc-popup-creator' ); ?></a></li>
			</ul>

			<div class="tab-content">
				<div id="choose_template" class="tab-pane active">
					<div class="choose-template-wrapper">
						<fieldset>
							<label>
								<input type="radio" name="arpc_setting_opn[arpc_choose_temp]" value="template1" <?php checked( isset( $options['arpc_choose_temp'] ) ? $options['arpc_choose_temp'] : '', 'template1' ); ?>>
								<?php esc_html_e( 'Template 1', 'arpc-popup-creator' ); ?>
								<img src="<?php echo esc_url( ARPC_ASSETS . '/images/template1.png' ); ?>" alt="<?php esc_attr_e( 'Template 1', 'arpc-popup-creator' ); ?>" />
							</label>
							<label>
								<input type="radio" name="arpc_setting_opn[arpc_choose_temp]" value="template2" <?php checked( isset( $options['arpc_choose_temp'] ) ? $options['arpc_choose_temp'] : '', 'template2' ); ?>>
								<?php esc_html_e( 'Template 2', 'arpc-popup-creator' ); ?>
								<img src="<?php echo esc_url( ARPC_ASSETS . '/images/template2.png' ); ?>" alt="<?php esc_attr_e( 'Template 2', 'arpc-popup-creator' ); ?>" />
							</label>
							<label>
								<input type="radio" name="arpc_setting_opn[arpc_choose_temp]" value="template3" <?php checked( isset( $options['arpc_choose_temp'] ) ? $options['arpc_choose_temp'] : '', 'template3' ); ?>>
								<?php esc_html_e( 'Template 3', 'arpc-popup-creator' ); ?>
								<img src="<?php echo esc_url( ARPC_ASSETS . '/images/template3.png' ); ?>" alt="<?php esc_attr_e( 'Template 3', 'arpc-popup-creator' ); ?>" />
							</label>
						</fieldset>
					</div>
				</div>
				<div id="overlay" class="tab-pane">
					<div class="template-properties">
						<ul>
							<li>
								<label for="arpc_overlay_color"><?php esc_html_e( 'Overlay Background Color', 'arpc-popup-creator' ); ?></label>
								<input type="color" name="arpc_setting_opn[arpc_overlay_color]" id="arpc_overlay_color" value="<?php echo esc_attr( isset( $options['arpc_overlay_color'] ) ? $options['arpc_overlay_color'] : '#242121ed' ); ?>">
							</li>
							<li>
								<label for="arpc_overlay_opacity"><?php esc_html_e( 'Overlay Background Opacity', 'arpc-popup-creator' ); ?></label>
								<input type="number" name="arpc_setting_opn[arpc_overlay_opacity]" id="arpc_overlay_opacity" value="<?php echo esc_attr( isset( $options['arpc_overlay_opacity'] ) ? $options['arpc_overlay_opacity'] : '' ); ?>">
							</li>
						</ul>
					</div>
				</div>
				<div id="container" class="tab-pane">
					<div class="template-properties">
						<ul>
							<li>
								<label for="arpc_container_width"><?php esc_html_e( 'Popup Max Width (px)', 'arpc-popup-creator' ); ?></label>
								<input type="number" name="arpc_setting_opn[arpc_container_width]" id="arpc_container_width" value="<?php echo esc_attr( isset( $options['arpc_container_width'] ) ? $options['arpc_container_width'] : '' ); ?>">
							</li>
							<li>
								<label for="arpc_container_height"><?php esc_html_e( 'Popup Max Height (px)', 'arpc-popup-creator' ); ?></label>
								<input type="number" name="arpc_setting_opn[arpc_container_height]" id="arpc_container_height" value="<?php echo esc_attr( isset( $options['arpc_container_height'] ) ? $options['arpc_container_height'] : '' ); ?>">
							</li>
							<li>
								<label for="arpc_container_bg_color"><?php esc_html_e( 'Popup Background Color', 'arpc-popup-creator' ); ?></label>
								<input type="color" name="arpc_setting_opn[arpc_container_bg_color]" id="arpc_container_bg_color" value="<?php echo esc_attr( isset( $options['arpc_container_bg_color'] ) ? $options['arpc_container_bg_color'] : '#242121ed' ); ?>">
							</li>
						</ul>
					</div>
				</div>
				<div id="title" class="tab-pane">
					<div class="template-properties">
						<ul>
							<li>
								<label for="arpc_title_color"><?php esc_html_e( 'Title Color', 'arpc-popup-creator' ); ?></label>
								<input type="color" name="arpc_setting_opn[arpc_title_color]" id="arpc_title_color" value="<?php echo esc_attr( isset( $options['arpc_title_color'] ) ? $options['arpc_title_color'] : '#ffffff' ); ?>">
							</li>
							<li>
								<label for="arpc_title_size"><?php esc_html_e( 'Title Font Size (px)', 'arpc-popup-creator' ); ?></label>
								<input type="number" name="arpc_setting_opn[arpc_title_size]" id="arpc_title_size" value="<?php echo esc_attr( isset( $options['arpc_title_size'] ) ? $options['arpc_title_size'] : '' ); ?>">
							</li>
						</ul>
					</div>
				</div>
				<div id="content" class="tab-pane">
					<div class="template-properties">
						<ul>
							<li>
								<label for="arpc_content_color"><?php esc_html_e( 'Content Color', 'arpc-popup-creator' ); ?></label>
								<input type="color" name="arpc_setting_opn[arpc_content_color]" id="arpc_content_color" value="<?php echo esc_attr( isset( $options['arpc_content_color'] ) ? $options['arpc_content_color'] : '#ffffff' ); ?>">
							</li>
							<li>
								<label for="arpc_content_size"><?php esc_html_e( 'Content Font Size (px)', 'arpc-popup-creator' ); ?></label>
								<input type="number" name="arpc_setting_opn[arpc_content_size]" id="arpc_content_size" value="<?php echo esc_attr( isset( $options['arpc_content_size'] ) ? $options['arpc_content_size'] : '' ); ?>">
							</li>
						</ul>
					</div>
				</div>
				<div id="close" class="tab-pane">
					<div class="template-properties">
						<ul>
							<li>
								<label for="arpc_close_bg_color"><?php esc_html_e( 'Close Button Background', 'arpc-popup-creator' ); ?></label>
								<input type="color" name="arpc_setting_opn[arpc_close_bg_color]" id="arpc_close_bg_color" value="<?php echo esc_attr( isset( $options['arpc_close_bg_color'] ) ? $options['arpc_close_bg_color'] : '#fff' ); ?>">
							</li>
							<li>
								<label for="arpc_close_color"><?php esc_html_e( 'Close Icon Color', 'arpc-popup-creator' ); ?></label>
								<input type="color" name="arpc_setting_opn[arpc_close_color]" id="arpc_close_color" value="<?php echo esc_attr( isset( $options['arpc_close_color'] ) ? $options['arpc_close_color'] : '#000' ); ?>">
							</li>
							<li>
								<label for="arpc_close_size"><?php esc_html_e( 'Close Icon Size', 'arpc-popup-creator' ); ?></label>
								<input type="number" name="arpc_setting_opn[arpc_close_size]" id="arpc_close_size" value="<?php echo esc_attr( isset( $options['arpc_close_size'] ) ? $options['arpc_close_size'] : '' ); ?>">
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Title field callback.
	 */
	public function title_field_callback() {
		$options = get_option( 'arpc_setting_opn' );
		$title   = isset( $options['arpc_title'] ) ? $options['arpc_title'] : 'Our Spring Sale Has Started';
		?>
		<input type="text" name="arpc_setting_opn[arpc_title]" class="regular-text" value="<?php echo esc_attr( $title ); ?>">
		<?php
	}
}
