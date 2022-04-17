<div class="wrap">
<h1 class="wp-heading-inline"><?php _e('Address Lists') ?></h1>

<a href="<?php echo admin_url('edit.php?post_type=arpc_popup&page=arpc-address-form&action=new') ?>" class="page-title-action"><?php _e('Add New', 'arpc-popup-creator') ?></a>

<?php if (isset($_GET['inserted'])) { ?>
      <div class="notice notice-success">
            <p><?php _e('Address added successfully!', 'arpc-popup-creator'); ?></p>
      </div>
<?php } ?>
<form method="post" class="arpc_settings__form2">
      <div class="arpc_form_group<?php echo $this->has_errors('name') ? ' form-invalid ' : ''; ?>">
            <label for="name">Name</label>
            <div>
                  <input type="text" class="regular-text" name="name" id="name" />
                  <?php if ($this->has_errors('name')) { ?>
                        <p class="description error"><?php echo $this->get_error('name'); ?></p>
                  <?php } ?>
            </div>
      </div>
      <div class="arpc_form_group<?php echo $this->has_errors('email') ? ' form-invalid ' : ''; ?>">
            <label for="email">Email</label>
            <div>
            <input type="text" class="regular-text" id="email" name="email" />
            <?php if ($this->has_errors('email')) { ?>
                  <p class="description error"><?php echo $this->get_error('email'); ?></p>
            <?php } ?>
            </div>
      </div>
      <div class="arpc_form_group<?php echo $this->has_errors('phone') ? ' form-invalid ' : ''; ?>">
            <label for="phone">Phone</label>
            <div>
            <input type="text" class="regular-text" id="phone" name="phone" />
            <?php if ($this->has_errors('phone')) { ?>
                  <p class="description error"><?php echo $this->get_error('phone'); ?></p>
            <?php } ?>
            </div>
      </div>
      <div class="arpc_form_group">
            <label for="address">Address</label>
            <textarea class="regular-text" id="address" name="address"></textarea>
      </div>
      <?php
      wp_nonce_field('new-form');
      submit_button('Add Address', 'primary', 'submit_new_form');
      ?>
</form>
</div>