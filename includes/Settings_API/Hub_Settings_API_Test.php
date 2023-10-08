<?php
namespace ARPC\Popup\Settings_API;

/**
 * WordPress settings API demo class
 *
 */
if ( ! class_exists( 'Hub_Settings_API_Test' ) ) :
	class Hub_Settings_API_Test {

		private $settings_api;

		public function __construct() {
			$this->settings_api = new Hub_Settings_API();

			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		public function admin_init() {

			//set the settings
			$this->settings_api->set_sections( $this->get_settings_sections() );
			$this->settings_api->set_fields( $this->get_settings_fields() );

			//initialize settings
			$this->settings_api->admin_init();
		}

		public function admin_menu() {
			add_options_page( 'Theme Settings', 'Theme Settings', 'delete_posts', 'settings_api_test', array( $this, 'plugin_page' ) );
		}

		public function get_settings_sections(): array {
			$sections = array(
				array(
					'id'    => 'Hub_basics',
					'title' => __( 'Basic Settings', 'Hub' ),
				),
				array(
					'id'    => 'hub_products',
					'title' => __( 'Product Shop Wise', 'Hub' ),
				),
				array(
					'id'    => 'hub_advance',
					'title' => __( 'Advance Tab', 'Hub' ),
				),
				array(
					'id'    => 'hub_general',
					'title' => __( 'General Tab', 'Hub' ),
				),
			);

			return $sections;
		}

		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		public function get_settings_fields() {
			$settings_fields = array(
				'hub_advance'  => array(
					array(
						'name'              => 'hub_advance1',
						'label'             => __( 'Hub Advanced Tab One*', 'Hub' ),
						'desc'              => __( 'hello word 1234', 'Hub' ),
						'placeholder'       => __( '35', 'Hub' ),
						'type'              => 'textarea',
						'default'           => 'Title',
					),
					array(
						'name'              => 'hub_advance2',
						'label'             => __( 'Hub Advanced Tab 2*', 'Hub' ),
						'desc'              => __( '`', 'Hub' ),
						'placeholder'       => __( '35', 'Hub' ),
						'min'               => 0,
						'max'               => 100000,
						'step'              => '',
						'type'              => 'text',
						'default'           => 'Title',
						'sanitize_callback' => 'floatval',
					),
					array(
						'name'              => 'hub_advance',
						'label'             => __( 'Hub Advanced Tab*', 'Hub' ),
						'desc'              => __( '`', 'Hub' ),
						'placeholder'       => __( '35', 'Hub' ),
						'min'               => 0,
						'max'               => 100000,
						'step'              => '',
						'type'              => 'number',
						'default'           => 'Title',
						'sanitize_callback' => 'floatval',
					),
				),
				'Hub_basics'   => array(
					array(
						'name'              => 'order_to_wrh',
						'label'             => __( 'Number Of Hood To WRH Per Day *Priority*', 'Hub' ),
						'desc'              => __( '`', 'Hub' ),
						'placeholder'       => __( '35', 'Hub' ),
						'min'               => 0,
						'max'               => 100000,
						'step'              => '',
						'type'              => 'number',
						'default'           => 'Title',
						'sanitize_callback' => 'floatval',
					),
					array(
						'name'              => 'order_to_wilkes',
						'label'             => __( 'Number Of Hood To Wilkes Per Day', 'Hub' ),
						'desc'              => __( '`', 'Hub' ),
						'placeholder'       => __( '35', 'Hub' ),
						'min'               => 0,
						'max'               => 100000,
						'step'              => '',
						'type'              => 'number',
						'default'           => 'Title',
						'sanitize_callback' => 'floatval',
					),
					array(
						'name'              => 'tradewinds_rep',
						'label'             => __( 'Tradwinds Rep. Email', 'Hub' ),
						'desc'              => __( 'Tradwinds representative email for stem #1 form', 'Hub' ),
						'type'              => 'text',
						'default'           => 'tradwinds-rep1@hoodslyhub.com',
						'sanitize_callback' => 'string',
					),
					array(
						'name'              => 'rl_rep',
						'label'             => __( 'RL Rep. Email', 'Hub' ),
						'desc'              => __( 'RL representative email for shipping damage', 'Hub' ),
						'type'              => 'text',
						'default'           => 'rl@hoodslyhub.com',
						'sanitize_callback' => 'string',
					),
					array(
						'name'              => 'zline_rep',
						'label'             => __( 'Zline Rep. Email', 'Hub' ),
						'desc'              => __( 'Zline representative email for zline form', 'Hub' ),
						'type'              => 'text',
						'default'           => 'zline-rep@hoodslyhub.com',
						'sanitize_callback' => 'string',
					),
				),
				'hub_products' => array(
					array(
						'name'    => 'Scoop With Brass Strapping',
						'label'   => __( 'Scoop With Brass Strapping', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Farmhouse',
						'label'   => __( 'Farmhouse', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Scoop With Steel Strapping',
						'label'   => __( 'Scoop With Steel Strapping', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Tapered Without Trim',
						'label'   => __( 'Tapered Without Trim', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Angled Without Trim',
						'label'   => __( 'Angled Without Trim', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Sloped Without Trim',
						'label'   => __( 'Sloped Without Trim', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Bell Style Straight',
						'label'   => __( 'Bell Style Straight', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Farmhouse X',
						'label'   => __( 'Farmhouse X', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Tapered With Strapping',
						'label'   => __( 'Tapered With Strapping', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Angled With Strapping',
						'label'   => __( 'Angled With Strapping', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Bell Style With Strapping',
						'label'   => __( 'Bell Style With Strapping', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Angled With Walnut Band',
						'label'   => __( 'Angled With Walnut Band', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Curved',
						'label'   => __( 'Curved', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Curved With Strapping',
						'label'   => __( 'Curved With Strapping', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Curved With Steel Strapping',
						'label'   => __( 'Curved With Steel Strapping', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Curved With Brass Strapping',
						'label'   => __( 'Curved With Brass Strapping', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Curved With Brass Apron',
						'label'   => __( 'Curved With Brass Apron', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Tapered With Shiplap',
						'label'   => __( 'Tapered With Shiplap', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Sloped With Strapping',
						'label'   => __( 'Sloped With Strapping', 'Hub' ),

						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Tapered',
						'label'   => __( 'Tapered', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Angled',
						'label'   => __( 'Angled', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Curved With Strapping',
						'label'   => __( 'Curved With Strapping', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Sloped',
						'label'   => __( 'Sloped', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Box With Shiplap & Walnut Band',
						'label'   => __( 'Box With Shiplap & Walnut Band', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Curved With Arched Apron',
						'label'   => __( 'Curved With Arched Apron', 'Hub' ),

						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Box Straight With Shiplap',
						'label'   => __( 'Box Straight With Shiplap', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Tapered With Reclaimed Wood Band',
						'label'   => __( 'Tapered With Reclaimed Wood Band', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Curved With Brass Strapping & Buttons',
						'label'   => __( 'Curved With Brass Strapping & Buttons', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Box Straight No Shiplap',
						'label'   => __( 'Box Straight No Shiplap', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Box With Trim',
						'label'   => __( 'Box With Trim', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Curved With Stainless Steel Strapping',
						'label'   => __( 'Curved With Stainless Steel Strapping', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Sloped With Steel Strapping',
						'label'   => __( 'Sloped With Steel Strapping', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Sloped With Brass Strapping',
						'label'   => __( 'Sloped With Brass Strapping', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Tapered With Brass Strapping',
						'label'   => __( 'Tapered With Brass Strapping', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Tapered With Steel Strapping',
						'label'   => __( 'Tapered With Steel Strapping', 'Hub' ),

						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Curved Hood With Corbels',
						'label'   => __( 'Curved Hood With Corbels', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Bell Style Wood Hood',
						'label'   => __( 'Bell Style Wood Hood', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Curved With Strapping Expivi',
						'label'   => __( 'Curved With Strapping Expivi', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Shaker Tapered',
						'label'   => __( 'Shaker Tapered', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Shaker Tapered X',
						'label'   => __( 'Shaker Tapered X', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Curved Without Trim',
						'label'   => __( 'Curved Without Trim', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Box Without Trim',
						'label'   => __( 'Box Without Trim', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Curved With Strapping Large Sizes',
						'label'   => __( 'Curved With Strapping Large Sizes', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Curved Large Sizes',
						'label'   => __( 'Curved Large Sizes', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Quick Shipping Options',
						'label'   => __( 'Quick Shipping Options', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Scoop With Steel Strapping And Buttons',
						'label'   => __( 'Scoop With Steel Strapping And Buttons', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
					array(
						'name'    => 'Scoop With Brass Strapping And Buttons',
						'label'   => __( 'Scoop With Brass Strapping And Buttons', 'Hub' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'WRH'    => 'WRH',
							'WILKES' => 'WILKES',
						),
					),
				),
			);

			return $settings_fields;
		}

		public function plugin_page() {
			echo '<div class="wrap">';

			$this->settings_api->show_navigation();
			$this->settings_api->show_forms();

			echo '</div>';
		}

		/**
		 * Get all the pages
		 *
		 * @return array page names with key value pairs
		 */
		public function get_pages() {
			$pages         = get_pages();
			$pages_options = array();
			if ( $pages ) {
				foreach ( $pages as $page ) {
					$pages_options[ $page->ID ] = $page->post_title;
				}
			}

			return $pages_options;
		}

	}
endif;
