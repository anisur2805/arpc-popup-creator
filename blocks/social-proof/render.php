<?php
/**
 * Server render for the Popup Social Proof block.
 *
 * Only an aggregate count is rendered. Subscriber names and email addresses are
 * never exposed on the front end.
 *
 * @package ARPC\Popup
 *
 * @var array $attributes Block attributes.
 */

use ARPC\Popup\Models\Subscriber;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$arpc_hours     = isset( $attributes['hours'] ) ? absint( $attributes['hours'] ) : 24;
$arpc_min_count = isset( $attributes['minCount'] ) ? absint( $attributes['minCount'] ) : 1;

$arpc_hours     = min( max( $arpc_hours, 1 ), 720 );
$arpc_min_count = max( $arpc_min_count, 1 );

$arpc_count = Subscriber::count_since( $arpc_hours );

if ( $arpc_count < $arpc_min_count ) {
	return;
}

$arpc_timeframe = sprintf(
	/* translators: %s: number of hours. */
	_n( 'the last %s hour', 'the last %s hours', $arpc_hours, 'arpc-popup-creator' ),
	number_format_i18n( $arpc_hours )
);

$arpc_message = sprintf(
	/* translators: 1: number of subscribers, 2: time window, for example "the last 24 hours". */
	_n(
		'%1$s person subscribed in %2$s',
		'%1$s people subscribed in %2$s',
		$arpc_count,
		'arpc-popup-creator'
	),
	number_format_i18n( $arpc_count ),
	$arpc_timeframe
);
?>
<p <?php echo get_block_wrapper_attributes( array( 'class' => 'arpc-social-proof' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core escapes the attribute string it returns. ?>>
	<?php echo esc_html( $arpc_message ); ?>
</p>
