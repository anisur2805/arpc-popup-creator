<?php
/**
 * Subscribers page view template.
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
		<input type="hidden" name="page" value="<?php echo esc_attr( isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '' ); ?>" />
		<?php
		$subscriber_table->prepare_items();
		$subscriber_table->search_box( 'search', 'search_id' );
		$subscriber_table->display();
		?>
	</form>
</div>
