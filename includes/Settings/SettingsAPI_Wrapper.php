<?php

namespace ARPC\Popup\Settings;

if ( !class_exists('SettingsAPI_Wrapper' ) ):
class SettingsAPI_Wrapper {

    private $settings_api;

    public function __construct() {
        $this->settings_api = new Settings_API();
    }

    
}
endif;