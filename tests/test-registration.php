<?php
/**
 * Tests that the plugin registers its post type, patterns and blocks.
 *
 * @package ARPC\Popup
 */

/**
 * Registration test case.
 */
class Test_Registration extends WP_UnitTestCase {

	/**
	 * The popup post type is registered and exposed to the REST API so the
	 * block editor can save popups.
	 */
	public function test_popup_post_type_is_registered_for_rest() {
		$this->assertTrue( post_type_exists( 'arpc_popup' ) );

		$post_type = get_post_type_object( 'arpc_popup' );

		$this->assertTrue( $post_type->show_in_rest, 'The block editor needs show_in_rest.' );
		$this->assertTrue( post_type_supports( 'arpc_popup', 'editor' ) );
		$this->assertTrue( post_type_supports( 'arpc_popup', 'title' ) );
	}

	/**
	 * The starter patterns are registered under the plugin's own category.
	 */
	public function test_block_patterns_are_registered() {
		$registry = WP_Block_Patterns_Registry::get_instance();
		$patterns = $registry->get_all_registered();

		$slugs = wp_list_pluck( $patterns, 'name' );
		$ours  = array_filter(
			$slugs,
			function ( $slug ) {
				return 0 === strpos( $slug, 'arpc-popup/' );
			}
		);

		$this->assertNotEmpty( $ours, 'The plugin must register starter patterns.' );

		// Every pattern must carry real block markup, not an empty string.
		foreach ( $patterns as $pattern ) {
			if ( 0 !== strpos( $pattern['name'], 'arpc-popup/' ) ) {
				continue;
			}

			$this->assertNotEmpty( $pattern['title'], $pattern['name'] . ' needs a title.' );
			$this->assertStringContainsString(
				'<!-- wp:',
				$pattern['content'],
				$pattern['name'] . ' must contain block markup.'
			);
			$this->assertStringNotContainsString(
				'{$',
				$pattern['content'],
				$pattern['name'] . ' has an uninterpolated placeholder.'
			);
		}
	}

	/**
	 * The social proof block is registered from its block.json metadata.
	 */
	public function test_social_proof_block_is_registered() {
		$registry = WP_Block_Type_Registry::get_instance();
		$block    = $registry->get_registered( 'arpc/social-proof' );

		$this->assertNotNull( $block, 'arpc/social-proof must be registered.' );
		$this->assertSame( 3, $block->api_version );
		$this->assertIsCallable( $block->render_callback, 'The block must be server-rendered.' );
		$this->assertSame( 24, $block->attributes['hours']['default'] );
		$this->assertSame( 1, $block->attributes['minCount']['default'] );
	}
}
