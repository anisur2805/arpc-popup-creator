<h1><?php esc_html_e(get_admin_page_title()); ?></h1>
<form action="options.php" method="post">
    <?php 
    settings_fields('arpc_setting_opg'); 
    do_settings_sections('arpc-popup-settings');
    ?>
    <?php submit_button('Save Settings'); ?>
</form>