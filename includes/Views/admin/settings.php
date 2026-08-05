<?php
/**
 * Settings page view template.
 *
 * @package ARPC\Popup
 *
 * @var string $active_tab Current active tab.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$active_general = ( 'general' === $active_tab ) ? 'nav-tab-active' : '';
$active_adv     = ( 'adv' === $active_tab ) ? 'nav-tab-active' : '';
?>
<div class="wrap arpc-popup-settings-wrapper">
	<h1><?php esc_html_e( 'Settings', 'arpc-popup-creator' ); ?></h1>

	<?php settings_errors( 'arpc_settings_messages' ); ?>

	<h2 class="nav-tab-wrapper">
		<a href="admin.php?page=arpc-popup-settings&tab=general" class="nav-tab <?php echo esc_attr( $active_general ); ?>">
			<?php esc_html_e( 'General', 'arpc-popup-creator' ); ?>
		</a>
		<a href="admin.php?page=arpc-popup-settings&tab=adv" class="nav-tab <?php echo esc_attr( $active_adv ); ?>">
			<?php esc_html_e( 'Advance', 'arpc-popup-creator' ); ?>
		</a>
	</h2>

	<form action="options.php" method="post">
		<?php
		if ( 'general' === $active_tab ) {
			settings_fields( 'arpc-popup-general-settings' );
			do_settings_sections( 'arpc-popup-general-settings' );
		} else {
			settings_fields( 'arpc-popup-adv-settings' );
			do_settings_sections( 'arpc-popup-adv-settings' );
		}

		submit_button( __( 'Save Settings', 'arpc-popup-creator' ) );
		?>
	</form>
</div>
