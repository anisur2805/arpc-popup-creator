<?php
/**
 * Editor script dependencies for the social proof block.
 *
 * Hand-maintained because the block ships as plain JavaScript with no build step.
 * WordPress reads this file when registering `editorScript` from block.json.
 *
 * @package ARPC\Popup
 */

return array(
	'dependencies' => array(
		'wp-blocks',
		'wp-block-editor',
		'wp-components',
		'wp-element',
		'wp-i18n',
	),
	'version'      => '1.0.0',
);
