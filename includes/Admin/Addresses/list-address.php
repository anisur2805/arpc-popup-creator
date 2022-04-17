<?php

use ARPC\Popup\Admin\Addresses\Addresses_List_Table;

?>
<div class="wrap">
    <h1 class="wp-heading-inline"> <?php _e( 'Address Book', 'oop-academy' );?> </h1>

    <a href="<?php echo admin_url( 'edit.php?post_type=arpc_popup&page=arpc-popup-form&action=new' ) ?>" class="page-title-action"> <?php _e( 'Add New', 'oop-academy' );?> </a>

    <?php if ( isset( $_GET['inserted'] ) ) {?>
		<div class="notice notice-success">
        	<p><?php _e( 'Address added successfully!', 'oop-academy' );?></p>
		</div>
    <?php } ?>
	
    <?php if ( isset( $_GET['address-deleted'] ) ) { ?>
		<div class="notice notice-success">
        	<p><?php _e( 'Address deleted successfully!', 'oop-academy' );?></p>
		</div>
    <?php } ?>
	
    <form action="" method="post">
	
        <?php
			$table = new Addresses_List_Table();
			$table->prepare_items();
			$table->display();
		?>
		
    </form>
</div>