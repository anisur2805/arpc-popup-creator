<?php

namespace ARPC\Popup;

use ARPC\Popup\Admin\Menu;
use ARPC\Popup\Data_Table\Data_Table;
use ARPC\Popup\Metabox;
use ARPC\Popup\Post_Type;
use ARPC\Popup\Traits\Form_Error;

class Admin {

	use Form_Error;

	public function __construct() {

		// Instantiate Data Table
		new Data_Table();

		//Instantiate Meta box
		new Metabox();

		// Add Menu page
		new Menu();

		// Instantiate Front End Popup
		new Post_Type();

		add_action( 'admin_head', array( $this, 'load_assets' ) );
	}

	public function load_assets() {
		wp_enqueue_style( 'arpc-metabox' );
		wp_enqueue_script( 'arpc-main-ajax' );
	}
}
