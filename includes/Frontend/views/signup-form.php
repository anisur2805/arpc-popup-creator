<div class="popup-creator-wrapper" id="popup-creator-wrapper">
      <form action="" method="post">
            <div class="form-group-row">
                  <?php
                  input_field("text", "name", "name", "Enter your name", '');
                  ?>
            </div>

            <div class="form-group-row">
                  <?php
                  input_field("email", "email", "email", "Enter your email", '');
                  ?>
            </div>

            <div class="form-group-row">
                  <?php wp_nonce_field('apc-enquiry-form'); ?>
                  <input type="hidden" name="action" value="apc_enquiry">
                  <input type="submit" class="apc_submit" name="send_enquiry" value="<?php esc_attr_e('Give Me 3 Free Resources', 'popup-creator'); ?>" />
            </div>
      </form>
</div>