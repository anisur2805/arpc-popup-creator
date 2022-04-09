<div class="popup-creator-wrapper" id="popup-creator-wrapper">
      <form action="" method="post">
            <div class="arpc-form-group-row">
                  <?php
                  input_field("text", "name", "name", "Enter your name", '');
                  ?>
            </div>

            <div class="arpc-form-group-row">
                  <?php
                  input_field("email", "email", "email", "Enter your email", '');
                  ?>
            </div>

            <div class="arpc-form-group-row">
                  <?php wp_nonce_field('arpc-enquiry-form'); ?>
                  <input type="hidden" name="action" value="arpc_enquiry">
                  <input type="submit" class="arpc_submit" name="send_enquiry" value="<?php esc_attr_e('Give Me 3 Free Resources', 'popup-creator'); ?>" />
            </div>
      </form>
</div>