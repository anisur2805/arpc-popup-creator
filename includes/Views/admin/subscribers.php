<?php
/**
 * Subscribers page view template.
 *
 * @package ARPC\Popup
 *
 * @var object $subscriber_table Subscribers_List_Table instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Subscriber Lists', 'arpc-popup-creator' ); ?></h1>
	<form id="art-search-form" method="GET">
		<?php // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only screen state, no data is written here. ?>
		<input type="hidden" name="page" value="<?php echo esc_attr( isset( $_REQUEST['page'] ) ? sanitize_key( wp_unslash( $_REQUEST['page'] ) ) : '' ); ?>" />
		<?php
		$subscriber_table->prepare_items();
		$subscriber_table->search_box( 'search', 'search_id' );
		$subscriber_table->display();
		?>
	</form>
</div>
