<?php

	declare ( strict_types = 1 );
	/**
	 * Setup Wizard class file.
	 */

	namespace ARPC\Popup;

	/**
	 * ARPC_Setup_Wizard class
	 *
	 * @category WordPress_Plugin.
	 *
	 * @package WordPress
	 * @author  Anisur <anisur2805@gmail.com>
	 * @license MIT License
	 * @link    http://url.com
	 */
	class ARPC_Setup_Wizard {
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'setup_wizard_scripts' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'wp_ajax_save_setup_wizard_data', [$this, 'save_setup_wizard_data'] );
			add_action( 'wp_ajax_save_arpc_elements_data', [$this, 'save_arpc_elements_data'] );
			add_action( 'in_admin_header', [$this, 'remove_notice'], 1000 );
			// $this->templately_status = $this->templately_active_status();
		}

		public function save_arpc_elements_data() {
			check_ajax_referer( 'arpc-setup-wizard', 'security' );

		if ( !current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		if ( !isset( $_POST[ 'fields' ] ) ) {
			return;
		}

		wp_parse_str( $_POST[ 'fields' ], $fields );

		update_option( 'arpc_save_elements_settings', $fields );

		wp_send_json_success( [
			'message' => __('Saveed')
		] );


		}
		
		/**
		 * Remove all notice in setup wizard page
		 */
		public function remove_notice() {
			if ( isset( $_GET['page'] ) && $_GET['page'] == 'arpc-setup-wizard' ) {
				remove_all_actions( 'admin_notices' );
				remove_all_actions( 'all_admin_notices' );
			}
		}

		public function setup_wizard_scripts( $hook ) {
			if ( isset( $hook ) && $hook == 'admin_page_arpc-setup-wizard' ) {
				wp_enqueue_style( 'arpc-setup-wizard-css', ARPC_ASSETS . '/css/arpc-setup-wizard-css.css', false, time() );
				wp_enqueue_script( 'arpc-setup-wizard-script', ARPC_ASSETS . '/js/arpc-setup-wizard-script.js', ['jquery'], time(), true );
				wp_localize_script( 'arpc-setup-wizard-script', 'localize', array(
					'ajaxurl'       => esc_url( admin_url( 'admin-ajax.php' ) ),
					'nonce'         => wp_create_nonce( 'arpc-setup-wizard' ),
					// 'success_image' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/success.gif',
				) );
			}
			return [];
		}

		public static function redirect() {
			update_option( 'arpc_setup_wizard', 'init' );
			wp_redirect( admin_url( 'admin.php?page=arpc-setup-wizard' ) );
		}

		/**
		 * Create admin menu for setup wizard
		 */
		public function admin_menu() {

			add_submenu_page(
				'',
				__( 'ARPC Setup Widget', '' ),
				__( 'ARPC Setup Widget', '' ),
				'manage_options',
				'arpc-setup-wizard',
				[$this, 'render_wizard']
			);
		}

		/**
		 * Render_wizard
		 */
		public function render_wizard() {
		?>
		<div class="arpc-quick-setup-wizard-wrap">
			<?php
				$this->change_site_title();
						$this->tab_step();
						$this->tab_content();
						$this->setup_wizard_footer();
					?>
		</div>
	<?php
		}

			public function change_site_title() {
			?>
		<script>
			document.title = "<?php _e( 'Quick Setup Wizard- ARPC Popup', '' );?>"
		</script>
	<?php
		}

			public function tab_step() {
				$items = [
					__( 'Configuration', 'arpc-popup-creator' ),
					__( 'Elements', 'arpc-popup-creator' ),
					__( 'Go PRO', 'arpc-popup-creator' ),
					__( 'Templately', 'arpc-popup-creator' ),
					__( 'Integrations', 'arpc-popup-creator' ),
					__( 'Finalize', 'arpc-popup-creator' ),
				];

				$i = 0;
			?>
		<ul class="setup-wizard-ul" data-step="1">
			<?php foreach ( $items as $item ): ?>
				<li class="arpc-popup-creator-step active <?php echo esc_attr( strtolower( $item ) ); ?>">
					<div><?php echo esc_html( ++$i ); ?></div>
					<div><?php echo esc_html( $item ); ?></div>
				</li>
			<?php endforeach;?>

		</ul>
	<?php
		}

			public function tab_content() {
			?>
		<div class="eael-quick-setup-body">
			<form class="eael-setup-wizard-form eael-quick-setup-wizard-form" method="post">
				<?php
					$this->configuration_tab();
							$this->elements_tab();
							$this->go_pro_tab();
							$this->templately_integrations();
							$this->eael_integrations();
							$this->finalize_tab();
						?>
			</form>
		</div>
	<?php
		}

			public function configuration_tab() {
				?>
				<div id="configuration" class="eael-quick-setup-tab-content configuration setup-content">
					<h1>Hello from Configuration tab</h1>
				</div>
				<?php
			}

			/**
			 * get_element_list
			 * @return array[]
			 */
			public function get_element_list() {
				return [
					'content-elements'         => [
						'title'    => __( 'Content Elements', 'essential-addons-for-elementor-lite' ),
						'elements' => [
							[
								'key'         => 'creative-btn',
								'title'       => __( 'Creative Button', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'team-members',
								'title'       => __( 'Team Member', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'testimonials',
								'title'       => __( 'Testimonial', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'flip-box',
								'title'       => __( 'Flip Box', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'info-box',
								'title'       => __( 'Info Box', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'dual-header',
								'title'       => __( 'Dual Color Heading', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'tooltip',
								'title'       => __( 'Tooltip', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'adv-accordion',
								'title'       => __( 'Advanced Accordion', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'adv-tabs',
								'title'       => __( 'Advanced Tabs', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'feature-list',
								'title'       => __( 'Feature List', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',

							],
							[
								'key'         => 'sticky-video',
								'title'       => __( 'Sticky Video', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'event-calendar',
								'title'       => __( 'Event Calendar', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'advance',
							],
							[
								'key'         => 'simple-menu',
								'title'       => __( 'Simple Menu', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
						],
					],
					'dynamic-content-elements' => [
						'title'    => __( 'Dynamic Content Elements', 'essential-addons-for-elementor-lite' ),
						'elements' => [
							[
								'key'         => 'post-grid',
								'title'       => __( 'Post Grid', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'advance',
							],
							[
								'key'   => 'post-timeline',
								'title' => __( 'Post Timeline', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'         => 'data-table',
								'title'       => __( 'Data Table', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'   => 'advanced-data-table',
								'title' => __( 'Advanced Data Table', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'         => 'content-ticker',
								'title'       => __( 'Content Ticker', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'nft-gallery',
								'title'       => __( 'NFT Gallery', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'   => 'business-reviews',
								'title' => __( 'Business Reviews', 'essential-addons-for-elementor-lite' ),
							],
						],
					],
					'creative-elements'        => [
						'title'    => __( 'Creative Elements', 'essential-addons-for-elementor-lite' ),
						'elements' => [
							[
								'key'         => 'count-down',
								'title'       => __( 'Countdown', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'fancy-text',
								'title'       => __( 'Fancy Text', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'filter-gallery',
								'title'       => __( 'Filterable Gallery', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'image-accordion',
								'title'       => __( 'Image Accordion', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'progress-bar',
								'title'       => __( 'Progress Bar', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'interactive-circle',
								'title'       => __( 'Interactive Circle', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'advance',
							],
							[
								'key'         => 'svg-draw',
								'title'       => __( 'SVG Draw', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'advance',
							],
						],
					],
					'marketing-elements'       => [
						'title'    => __( 'Marketing & Social Feed Elements', 'essential-addons-for-elementor-lite' ),
						'elements' => [
							[
								'key'         => 'call-to-action',
								'title'       => __( 'Call To Action', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'price-table',
								'title'       => __( 'Pricing Table', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'basic',
							],
							[
								'key'         => 'twitter-feed',
								'title'       => __( 'Twitter Feed', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'advance',
							],
							[
								'key'         => 'facebook-feed',
								'title'       => __( 'Facebook Feed', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'advance',
							],

						],
					],
					'form-styler-elements'     => [
						'title'    => __( 'Form Styler Elements', 'essential-addons-for-elementor-lite' ),
						'elements' => [
							[
								'key'         => 'contact-form-7',
								'title'       => __( 'Contact Form 7', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'advance',
							],
							[
								'key'   => 'weforms',
								'title' => __( 'weForms', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'   => 'ninja-form',
								'title' => __( 'Ninja Form', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'   => 'gravity-form',
								'title' => __( 'Gravity Form', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'   => 'caldera-form',
								'title' => __( 'Caldera Form', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'   => 'wpforms',
								'title' => __( 'WPForms', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'   => 'fluentform',
								'title' => __( 'Fluent Forms', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'   => 'formstack',
								'title' => __( 'Formstack', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'   => 'typeform',
								'title' => __( 'Typeform', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'         => 'login-register',
								'title'       => __( 'Login Register Form', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'advance',
							],
						],
					],
					'documentation-elements'   => [
						'title'    => __( 'Documentation Elements', 'essential-addons-for-elementor-lite' ),
						'elements' => [
							[
								'key'   => 'betterdocs-category-grid',
								'title' => __( 'BetterDocs Category Grid', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'   => 'betterdocs-category-box',
								'title' => __( 'BetterDocs Category Box', 'essential-addons-for-elementor-lite' ),

							],
							[
								'key'   => 'betterdocs-search-form',
								'title' => __( 'BetterDocs Search Form', 'essential-addons-for-elementor-lite' ),
							],
						],
					],
					'woocommerce-elements'     => [
						'title'    => __( 'WooCommerce Elements', 'essential-addons-for-elementor-lite' ),
						'elements' => [
							[
								'key'         => 'product-grid',
								'title'       => __( 'Product Grid', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'advance',
							],
							[
								'key'   => 'woo-product-carousel',
								'title' => __( 'Woo Product Carousel', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'   => 'woo-checkout',
								'title' => __( 'Woo Checkout', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'   => 'woo-cart',
								'title' => __( 'Woo Cart', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'   => 'woo-cross-sells',
								'title' => __( 'Woo Cross Sells', 'essential-addons-for-elementor-lite' ),
							],
							[
								'key'         => 'woo-product-compare',
								'title'       => __( 'Woo Product Compare', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'advance',
							],
							[
								'key'         => 'woo-product-gallery',
								'title'       => __( 'Woo Product Gallery', 'essential-addons-for-elementor-lite' ),
								'preferences' => 'advance',
							],
						],
					],
				];
			}

			public function pro_elements() {
				return [
					'event-calender'     => [
						'title' => __( 'Event Calender' ),
						'link'  => esc_url( 'https://essential-addons.com/elementor/event-calendar/' ),
						'logo'  => esc_url( ARPC_URL . '/assets/images/setup-wizard/event-cal.svg' ),
					],
					'toggle'             => [
						'title' => __( 'Toggle', 'essential-addons-for-elementor-lite' ),
						'link'  => esc_url( 'https://essential-addons.com/elementor/content-toggle/' ),
						'logo'  => esc_url( ARPC_URL . '/assets/images/setup-wizard/toggle.svg' ),
					],
					'adv-google-map'     => [
						'title' => __( 'Advanced Google Map', 'essential-addons-for-elementor-lite' ),
						'link'  => esc_url( 'https://essential-addons.com/elementor/advanced-google-map/' ),
						'logo'  => esc_url( ARPC_URL . '/assets/images/setup-wizard/adv-google-map.svg' ),
					],
					'dynamic-gallery'    => [
						'title' => __( 'Dynamic Gallery', 'essential-addons-for-elementor-lite' ),
						'link'  => esc_url( 'https://essential-addons.com/elementor/dynamic-gallery/' ),
						'logo'  => esc_url( ARPC_URL . '/assets/images/setup-wizard/dynamic-gallery.svg' ),
					],
					'image-hotspots'     => [
						'title' => __( 'Image Hotspots', 'essential-addons-for-elementor-lite' ),
						'link'  => esc_url( 'https://essential-addons.com/elementor/image-hotspots/' ),
						'logo'  => esc_url( ARPC_URL . '/assets/images/setup-wizard/image-hotspots.svg' ),
					],
					'lightbox-and-modal' => [
						'title' => __( 'Lightbox and Modal', 'essential-addons-for-elementor-lite' ),
						'link'  => esc_url( 'https://essential-addons.com/elementor/lightbox-modal/' ),
						'logo'  => esc_url( ARPC_URL . '/assets/images/setup-wizard/lightbox-and-modal.svg' ),
					],
					'mailchimp'          => [
						'title' => __( 'Mailchimp', 'essential-addons-for-elementor-lite' ),
						'link'  => esc_url( 'https://essential-addons.com/elementor/mailchimp/' ),
						'logo'  => esc_url( ARPC_URL . '/assets/images/setup-wizard/mailchimp.svg' ),
					],
					'instagram-feed'     => [
						'title' => __( 'Instagram Feed', 'essential-addons-for-elementor-lite' ),
						'link'  => esc_url( 'https://essential-addons.com/elementor/instagram-feed/' ),
						'logo'  => esc_url( ARPC_URL . '/assets/images/setup-wizard/instagram-feed.svg' ),
					],
				];
			}

			/**
			 * get_plugin_list
			 * @return array
			 */
			public function get_plugin_list() {
				return [
					[
						'slug'     => 'betterdocs',
						'basename' => 'betterdocs/betterdocs.php',
						'logo'     => ARPC_URL . '/assets/images/setup-wizard/bd-new.svg',
						'title'    => __( 'BetterDocs', 'essential-addons-for-elementor-lite' ),
						'desc'     => __( 'BetterDocs will help you to create & organize your documentation page in a beautiful way that will make your visitors find any help article easily.', 'essential-addons-for-elementor-lite' ),
					],
					[
						'slug'     => 'embedpress',
						'basename' => 'embedpress/embedpress.php',
						'logo'     => ARPC_URL . '/assets/images/setup-wizard/ep-logo.png',
						'title'    => __( 'EmbedPress', 'essential-addons-for-elementor-lite' ),
						'desc'     => __( 'EmbedPress lets you embed videos, images, posts, audio, maps and upload PDF, DOC, PPT & all other types of content into your WordPress site. ', 'essential-addons-for-elementor-lite' ),
					],
					[
						'slug'     => 'reviewx',
						'basename' => 'reviewx/reviewx.php',
						'logo'     => ARPC_URL . '/assets/images/setup-wizard/review-logo.svg',
						'title'    => __( 'ReviewX', 'essential-addons-for-elementor-lite' ),
						'desc'     => __( 'ReviewX lets you get instant customer ratings and multi criteria reviews to add credibility to your WooCommerce Store and increase conversion rates.', 'essential-addons-for-elementor-lite' ),
					],
					[
						'slug'     => 'notificationx',
						'basename' => 'notificationx/notificationx.php',
						'logo'     => ARPC_URL . '/assets/images/setup-wizard/nx-logo.svg',
						'title'    => __( 'NotificationX', 'essential-addons-for-elementor-lite' ),
						'desc'     => __( 'Best FOMO Social Proof Plugin to boost your sales conversion. Create stunning Sales Popup & Notification Bar With Elementor Support.', 'essential-addons-for-elementor-lite' ),
					],
				];
			}

			public function elements_tab() {
				$init = 0;
				?>
				<div class="eael-quick-setup-tab-content elements setup-content" id="elements" style="display:none">
					<div class="eael-quick-setup-intro-wrapper">
						<div class="eael-quick-setup-intro">
							<h2 class="eael-quick-setup-title"> Turn on the Elements that you need</h2>
							<p class="eael-quick-setup-text">Enable/Disable the elements anytime you want from Essential Addons Dashboard</p>
						</div>


						<div class="eael-quick-setup-elements-body">
							<?php
								foreach ( $this->get_element_list() as $key => $item ):
											$init++;
										?>
									<div class="eael-quick-setup-post-grid-panel ">
										<h3 class="eael-quick-setup-post-grid-panel-title"><?php echo esc_html( $item['title'] ); ?></h3>

										<div class="eael-quick-setup-post-grid-wrapper eael-<?php echo esc_attr( $key ); ?>">
											<?php
													foreach ( $item['elements'] as $element ):
																$preferences = $checked = '';
																if ( isset( $element['preferences'] ) ) {
																	$preferences = $element['preferences'];
																	if ( $element['preferences'] == 'basic' ) {
																		$checked = 'checked';
																	}
																}
															?>
													<div class="eael-quick-setup-post-grid">
														<h3 class="eael-quick-setup-title"><?php echo esc_html( $element['title'] ) ?></h3>
														<label class="eael-quick-setup-toggler">
															<input data-preferences="<?php echo esc_attr( $preferences ) ?>" type="checkbox" class="eael-element" id="<?php echo esc_attr( $element['key'] ) ?>" name="eael_element[<?php echo esc_attr( $element['key'] ) ?>]"<?php echo esc_attr( $checked ); ?>>
															<span class="eael-quick-setup-toggler-icons"></span>
														</label>
													</div>

												<?php endforeach;?>
										</div>
									</div>
								<?php endforeach;?>
						</div>
					</div>
				</div>
			<?php
			}

			public function templately_integrations() {
			?>
		<div id="templately" class="eael-quick-setup-tab-content templately setup-content"  style="display:none; background-image: url(<?php echo esc_url( ARPC_URL . '/assets/images/setup-wizard/mask-group.png' ); ?>);">
			<div class="eael-quick-setup-logo">
				<button data-action="install" data-slug="templately" class="button eael-quick-setup-templately-button wpdeveloper-plugin-installer">
					Install Templately</button>
				<img src="<?php echo esc_url( ARPC_URL . '/assets/images/setup-wizard/templately-logo.svg' ); ?>" alt="Logo">
			</div>
			<div class="eael-quick-setup-title">
				Get access to <span class="eael-quick-setup-highlighted-red">4000+</span> Elementor Templates with Templately! <img draggable="false" role="img" class="emoji" alt="🚀" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f680.svg"> </div>
			<div class="eael-quick-setup-text">
				Want to create websites faster than ever? Check out Templately, the ultimate templates cloud that comes with thousands of ready Elementor templates for every niche! </div>
			<ul class="eael-quick-setup-list">
				<li class="eael-quick-setup-list-item">
					<span class="eael-quick-setup-icon"><img draggable="false" role="img" class="emoji" alt="🌟" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f31f.svg"></span>
					Access Thousands Of Stunning, Ready Website Templates
				</li>
				<li class="eael-quick-setup-list-item">
					<span class="eael-quick-setup-icon"><img draggable="false" role="img" class="emoji" alt="🔥" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f525.svg"></span>
					Save Your Design Anywhere With MyCloud Storage Space
				</li>
				<li class="eael-quick-setup-list-item">
					<span class="eael-quick-setup-icon"><img draggable="false" role="img" class="emoji" alt="🚀" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f680.svg"></span>
					Add Team Members &amp; Collaborate On Cloud With Templately WorkSpace
				</li>
			</ul>
		</div>
	<?php
		}

			public function get_local_plugin_data( $basename = '' ) {
				if ( empty( $basename ) ) {
					return false;
				}

				if ( !function_exists( 'get_plugins' ) ) {
					include_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$plugins = get_plugins();

				if ( !isset( $plugins[$basename] ) ) {
					return false;
				}

				return $plugins[$basename];
			}

			public function go_pro_tab() {
			?>
		<div id="go-pro" class="eael-quick-setup-tab-content go_pro setup-content" style="display:none">
			<div class="eael-quick-setup-intro">
				<div class="eael-quick-setup-logo">
					<img src="<?php echo esc_url( ARPC_URL . '/assets/images/setup-wizard/go-pro.svg' ); ?>" alt="Go Pro Logo" />
				</div>
				<h2 class="eael-quick-setup-title"> Enhance Your Elementor Experience By Unlocking 30+ Advanced PRO Elements </h2>
			</div>
			<div class="eael-quick-setup-input-group">
				<?php foreach ( $this->pro_elements() as $key => $element ): ?>
					<a target="_blank" href="<?php echo esc_url( $element['link'] ); ?>" class="eael-quick-setup-content">
						<span class="eael-quick-setup-icon">
							<img src="<?php echo esc_url( $element['logo'] ); ?>" alt="<?php echo esc_attr( $element['title'] ); ?>" />
						</span>
						<p class="eael-quick-setup-title"><?php echo esc_html( $element['title'] ); ?></p>
					</a>
				<?php endforeach;?>
			</div>
			<div class="eael-quick-setup-pro-button-wrapper">
				<a target="_blank" href="https://wpdeveloper.com/in/upgrade-essential-addons-elementor" class="button eael-quick-setup-btn eael-quick-setup-pro-button"> Upgrade to PRO </a>
			</div>
		</div>

	<?php

			}

			public function eael_integrations() {
			?>
		<div id="integrations" class="eael-quick-setup-tab-content integrations setup-content" style="display:none">
			<div class="eael-quick-setup-admin-block-wrapper">
				<?php foreach ( $this->get_plugin_list() as $plugin ): ?>
					<div class="eael-quick-setup-admin-block eael-quick-setup-admin-block-integrations">
						<span class="eael-quick-setup-logo">
							<img src="<?php echo esc_url( $plugin['logo'] ); ?>" alt="logo" />
						</span>
						<h4 class="eael-quick-setup-title"><?php echo esc_html( $plugin['title'] ); ?></h4>
						<p class="eael-quick-setup-text"><?php echo esc_textarea( $plugin['desc'] ); ?></p>

						<?php if ( $this->get_local_plugin_data( $plugin['basename'] ) === false ) {?>
							<button class="wpdeveloper-plugin-installer eael-quick-setup-wpdeveloper-plugin-installer" data-action="install" data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>"> Install </button>
						<?php } else {?>
<?php if ( is_plugin_active( $plugin['basename'] ) ) {?>
								<button class="wpdeveloper-plugin-installer eael-quick-setup-wpdeveloper-plugin-installer" data-action="install" data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>"> Activated </button>
							<?php } else {?>
								<button class="wpdeveloper-plugin-installer eael-quick-setup-wpdeveloper-plugin-installer" data-action="install" data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>"> Activate </button>
							<?php }?>
<?php }?>


					</div>
				<?php endforeach;?>
			</div>
		</div>

	<?php
		}

			public function finalize_tab() {
			?>
		<div id="finalize" class="eael-quick-setup-tab-content finalize setup-content" style="display:none">
			<div class="eael-quick-setup-modal">
				<div class="eael-quick-setup-modal-content">
					<div class="eael-quick-setup-modal-header">
						<div class="eael-quick-setup-intro">
							<h2 class="eael-quick-setup-title">
								<img draggable="false" role="img" class="emoji" alt="💪" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4aa.svg"> Make Essential Addons more awesome by being our Contributor
							</h2>
						</div>
					</div>
					<div class="eael-quick-setup-modal-body">
						<div class="eael-quick-setup-message-wrapper">
							<div class="eael-quick-setup-message">
								We collect non-sensitive diagnostic data and plugin usage
								information. Your site URL, WordPress &amp; PHP version, plugins &amp;
								themes and email address to send you the discount coupon. This
								data lets us make sure this plugin always stays compatible with
								the most popular plugins and themes. No spam, we promise. </div>
						</div>
					</div>
					<div class="eael-quick-setup-modal-footer">
						<button class="eael-button eael-quick-setup-button eael-setup-wizard-save">No, Thanks</button>
						<button id="eael-count-me-bt" class="eael-setup-wizard-save eael-button eael-quick-setup-button eael-quick-setup-filled-button">
							Count me in </button>
					</div>
					<input type="hidden" value="0" id="eael_user_email_address" name="eael_user_email_address">
				</div>
			</div>
		</div>
	<?php
		}

			public function setup_wizard_footer() {
			?>
		<div class="setup-wizard-footer">

			<button id="arpc-prev" class="button arpc-quick-setup-btn arpc-quick-setup-prev-button">
				<img src="<?php echo esc_url( ARPC_URL . '/assets/images/setup-wizard/left-arrow.svg' ); ?>" alt="<?php _e( 'Go Pro Logo', 'arpc-popup-creator' );?>">
				<?php _e( 'Previous', 'arpc-popup-creator' )?>
			</button>
			<button id="arpc-next" class="button  arpc-quick-setup-btn arpc-quick-setup-next-button"><?php _e( 'Next', 'arpc-popup-creator' )?>
				<img src="<?php echo esc_url( ARPC_URL . '/assets/images/setup-wizard/right-arrow.svg' ); ?>" alt="<?php _e( 'Right', 'arpc-popup-creator' );?>"></button>
			<button id="arpc-save" style="display: none" class="button arpc-quick-setup-btn arpc-quick-setup-next-button arpc-setup-wizard-save"><?php _e( 'Finish', 'arpc-popup-creator' )?></button>
		</div>
<?php
	}
}
