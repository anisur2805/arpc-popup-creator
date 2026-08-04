<?php
/**
 * Popup starter block patterns.
 *
 * @package ARPC\Popup
 */

namespace ARPC\Popup\Controllers;

/**
 * Block Patterns Controller
 *
 * Registers popup starter layouts as block patterns scoped to the popup
 * post type. Patterns drive the "Choose a pattern" starter modal on new
 * popups and appear in the editor inserter under "Popup Layouts".
 */
class Block_Patterns {

	/**
	 * Pattern category slug.
	 *
	 * @var string
	 */
	const CATEGORY = 'arpc-popup-layouts';

	/**
	 * Constructor - hook pattern registration.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Register the pattern category and popup layout patterns.
	 */
	public function register() {
		if ( ! function_exists( 'register_block_pattern' ) || ! function_exists( 'register_block_pattern_category' ) ) {
			return;
		}

		register_block_pattern_category(
			self::CATEGORY,
			array( 'label' => __( 'Popup Layouts', 'arpc-popup-creator' ) )
		);

		foreach ( $this->patterns() as $slug => $pattern ) {
			register_block_pattern( 'arpc-popup/' . $slug, $pattern );
		}
	}

	/**
	 * Build the list of popup layout patterns.
	 *
	 * @return array
	 */
	private function patterns() {
		$shared = array(
			'categories' => array( self::CATEGORY ),
			'postTypes'  => array( 'arpc_popup' ),
		);

		return array(
			'image-and-form' => array_merge(
				$shared,
				array(
					'title'       => __( 'Newsletter — Image & Form', 'arpc-popup-creator' ),
					'description' => __( 'Heading and intro above a two-column image and opt-in form.', 'arpc-popup-creator' ),
					'content'     => $this->image_and_form(),
				)
			),
			'image-top'      => array_merge(
				$shared,
				array(
					'title'       => __( 'Newsletter — Image Top', 'arpc-popup-creator' ),
					'description' => __( 'Centered image, heading, blurb and opt-in form.', 'arpc-popup-creator' ),
					'content'     => $this->image_top(),
				)
			),
			'simple-choices' => array_merge(
				$shared,
				array(
					'title'       => __( 'Newsletter — Simple with Choices', 'arpc-popup-creator' ),
					'description' => __( 'Heading, intro, opt-in form and an interest checklist.', 'arpc-popup-creator' ),
					'content'     => $this->simple_choices(),
				)
			),
			'video'          => array_merge(
				$shared,
				array(
					'title'       => __( 'Video — Embed & Call to Action', 'arpc-popup-creator' ),
					'description' => __( 'Heading above an embedded video with a call-to-action button.', 'arpc-popup-creator' ),
					'content'     => $this->video(),
				)
			),
		);
	}

	/**
	 * Pattern: two-column image and form.
	 *
	 * @return string
	 */
	private function image_and_form() {
		$heading = esc_html__( 'Our Spring Sale Has Started', 'arpc-popup-creator' );
		$intro   = esc_html__( 'Subscribe to our newsletter and never miss a deal.', 'arpc-popup-creator' );

		return <<<HTML
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">{$heading}</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">{$intro}</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image -->
<figure class="wp-block-image"><img alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:shortcode -->
[arpc_newsletter]
<!-- /wp:shortcode --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
HTML;
	}

	/**
	 * Pattern: centered image on top.
	 *
	 * @return string
	 */
	private function image_top() {
		$kicker  = esc_html__( 'Subscribe Now', 'arpc-popup-creator' );
		$heading = esc_html__( 'Join Our Newsletter', 'arpc-popup-creator' );
		$intro   = esc_html__( 'Do subscribe to receive updates on new arrivals, special offers and our promotions.', 'arpc-popup-creator' );

		return <<<HTML
<!-- wp:image {"align":"center","width":"160px"} -->
<figure class="wp-block-image aligncenter is-resized"><img alt="" style="width:160px"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><strong>{$kicker}</strong></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">{$heading}</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">{$intro}</p>
<!-- /wp:paragraph -->

<!-- wp:shortcode -->
[arpc_newsletter]
<!-- /wp:shortcode -->
HTML;
	}

	/**
	 * Pattern: embedded video with a call to action.
	 *
	 * Uses core/embed so any provider oEmbed supports (YouTube, Vimeo, ...) works.
	 * Swap in core/video instead to host the file in the media library.
	 *
	 * @return string
	 */
	private function video() {
		$heading = esc_html__( 'Watch How It Works', 'arpc-popup-creator' );
		$intro   = esc_html__( 'A two minute tour of everything you can do.', 'arpc-popup-creator' );
		$cta     = esc_html__( 'Get Started', 'arpc-popup-creator' );

		return <<<HTML
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">{$heading}</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">{$intro}</p>
<!-- /wp:paragraph -->

<!-- wp:embed {"type":"video","responsive":true,"className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
<figure class="wp-block-embed is-type-video wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
</div></figure>
<!-- /wp:embed -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">{$cta}</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
HTML;
	}

	/**
	 * Pattern: simple body with an interest checklist.
	 *
	 * @return string
	 */
	private function simple_choices() {
		$heading = esc_html__( 'And Get Offer On New Collection', 'arpc-popup-creator' );
		$intro   = esc_html__( 'Some useful content goes here.', 'arpc-popup-creator' );
		$first   = esc_html__( 'Tutorials', 'arpc-popup-creator' );
		$second  = esc_html__( 'Products', 'arpc-popup-creator' );

		return <<<HTML
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">{$heading}</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">{$intro}</p>
<!-- /wp:paragraph -->

<!-- wp:shortcode -->
[arpc_newsletter2]
<!-- /wp:shortcode -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>{$first}</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>{$second}</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->
HTML;
	}
}
