<?php

namespace ARPC\Popup\Settings;

if ( ! class_exists( 'Settings_API_Wrapper' ) ) :
	class Settings_API_Wrapper {

		private $settings_api;

		public function __construct() {
			$this->settings_api = new Settings_API();
		}
	}
endif;
