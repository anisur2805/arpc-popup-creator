<?php
/**
 * Template selector view for settings.
 *
 * @var string $value Current template value.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<select name="arpc_general_setting[arpc_general_settings_template]" id="arpc_general_settings_template" style="display: none;">
	<option value=""><?php esc_html_e( 'Select Template', 'arpc-popup-creator' ); ?></option>
	<option value="template1" <?php selected( $value, 'template1' ); ?>><?php esc_html_e( 'Template 1', 'arpc-popup-creator' ); ?></option>
	<option value="template2" <?php selected( $value, 'template2' ); ?>><?php esc_html_e( 'Template 2', 'arpc-popup-creator' ); ?></option>
	<option value="template3" <?php selected( $value, 'template3' ); ?>><?php esc_html_e( 'Template 3', 'arpc-popup-creator' ); ?></option>
</select>

<div id="choose_template" class="tab-pane active">
	<div>
		<div class="choose-template-wrapper">
			<fieldset>
				<label>
					<input type="radio" name="arpc_general_setting[arpc_general_settings_template]" value="template1" <?php checked( $value, 'template1' ); ?>>
					<?php esc_html_e( 'Template 1', 'arpc-popup-creator' ); ?>
					<img src="<?php echo esc_url( ARPC_ASSETS . '/images/template1.png' ); ?>" alt="<?php esc_attr_e( 'Template 1', 'arpc-popup-creator' ); ?>" />
				</label>
				<label>
					<input type="radio" name="arpc_general_setting[arpc_general_settings_template]" value="template2" <?php checked( $value, 'template2' ); ?>>
					<?php esc_html_e( 'Template 2', 'arpc-popup-creator' ); ?>
					<img src="<?php echo esc_url( ARPC_ASSETS . '/images/template2.png' ); ?>" alt="<?php esc_attr_e( 'Template 2', 'arpc-popup-creator' ); ?>" />
				</label>
				<label>
					<input type="radio" name="arpc_general_setting[arpc_general_settings_template]" value="template3" <?php checked( $value, 'template3' ); ?>>
					<?php esc_html_e( 'Template 3', 'arpc-popup-creator' ); ?>
					<img src="<?php echo esc_url( ARPC_ASSETS . '/images/template3.png' ); ?>" alt="<?php esc_attr_e( 'Template 3', 'arpc-popup-creator' ); ?>" />
				</label>
			</fieldset>
		</div>
	</div>
</div>
