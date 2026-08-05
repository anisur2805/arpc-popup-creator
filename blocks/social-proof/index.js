/**
 * Editor script for the Popup Social Proof block.
 *
 * Written as plain JavaScript so the plugin needs no build step. The block is
 * server-rendered, so `save` returns null and the editor shows a static summary
 * of the current settings rather than live subscriber counts.
 */
( function ( blocks, element, blockEditor, components, i18n ) {
	'use strict';

	var el = element.createElement;
	var __ = i18n.__;
	var sprintf = i18n.sprintf;
	var _n = i18n._n;

	blocks.registerBlockType( 'arpc/social-proof', {
		edit: function ( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = blockEditor.useBlockProps( {
				className: 'arpc-social-proof',
			} );

			var timeframe = sprintf(
				/* translators: %s: number of hours. */
				_n(
					'the last %s hour',
					'the last %s hours',
					attributes.hours,
					'arpc-popup-creator'
				),
				attributes.hours
			);

			var preview = sprintf(
				/* translators: %s: time window, for example "the last 24 hours". */
				__(
					'Subscriber count for %s appears here on the front end.',
					'arpc-popup-creator'
				),
				timeframe
			);

			var inspector = el(
				blockEditor.InspectorControls,
				null,
				el(
					components.PanelBody,
					{ title: __( 'Social proof', 'arpc-popup-creator' ) },
					el( components.RangeControl, {
						label: __( 'Time window (hours)', 'arpc-popup-creator' ),
						value: attributes.hours,
						min: 1,
						max: 720,
						onChange: function ( value ) {
							setAttributes( { hours: value || 1 } );
						},
					} ),
					el( components.RangeControl, {
						label: __( 'Hide below', 'arpc-popup-creator' ),
						help: __(
							'Hide the block when fewer than this many people subscribed in the window.',
							'arpc-popup-creator'
						),
						value: attributes.minCount,
						min: 1,
						max: 100,
						onChange: function ( value ) {
							setAttributes( { minCount: value || 1 } );
						},
					} )
				)
			);

			return el(
				element.Fragment,
				null,
				inspector,
				el( 'p', blockProps, preview )
			);
		},

		save: function () {
			return null;
		},
	} );
} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.i18n
);
