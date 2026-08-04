<?php
/**
 * Popup analytics dashboard view.
 *
 * @package ARPC\Popup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$popups = get_posts(
	array(
		'post_type'      => 'arpc_popup',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	)
);
?>

<div class="wrap">
	<h1><?php esc_html_e( 'Popup Analytics', 'arpc-popup-creator' ); ?></h1>

	<?php if ( ! $popups ) : ?>
		<p><?php esc_html_e( 'No popups found.', 'arpc-popup-creator' ); ?></p>
		<?php return; ?>
	<?php endif; ?>

	<table class="widefat fixed striped arpc-analytics-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Popup', 'arpc-popup-creator' ); ?></th>
				<th><?php esc_html_e( 'Views', 'arpc-popup-creator' ); ?></th>
				<th><?php esc_html_e( 'Opens', 'arpc-popup-creator' ); ?></th>
				<th><?php esc_html_e( 'Closes', 'arpc-popup-creator' ); ?></th>
				<th><?php esc_html_e( 'Conversions', 'arpc-popup-creator' ); ?></th>
				<th><?php esc_html_e( 'Conversion Rate', 'arpc-popup-creator' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $popups as $popup ) : ?>
				<?php
				$stats = \ARPC\Popup\Services\Popup_Settings::get_analytics( $popup->ID );
				$rate  = $stats['open'] > 0 ? round( ( $stats['conversion'] / $stats['open'] ) * 100, 1 ) : 0;
				?>
				<tr>
					<td>
						<a href="<?php echo esc_url( get_edit_post_link( $popup->ID ) ); ?>">
							<?php echo esc_html( $popup->post_title ); ?>
						</a>
					</td>
					<td><?php echo esc_html( $stats['view'] ); ?></td>
					<td><?php echo esc_html( $stats['open'] ); ?></td>
					<td><?php echo esc_html( $stats['close'] ); ?></td>
					<td><?php echo esc_html( $stats['conversion'] ); ?></td>
					<td><?php echo esc_html( $rate . '%' ); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
