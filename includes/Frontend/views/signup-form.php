<div class="arpc-popup-creator-wrapper" id="arpc-popup-creator-wrapper">
      <form action="" method="post">
            <div class="arpc-form-group-row">
                  <?php
                  arpc_input_field("text", "name", "name", "Enter your name", '');
                  ?>
            </div>

            <div class="arpc-form-group-row">
                  <?php
                  arpc_input_field("email", "email", "email", "Enter your email", '');
                  ?>
            </div>

            <div class="arpc-form-group-row">
                  <?php wp_nonce_field('arpc_enquiry'); ?>
                  <input type="hidden" name="action" value="arpc_enquiry">
                  <input type="submit" class="arpc_submit" name="arpc_submit" id="arpc_submit" value="<?php esc_attr_e('Give Me 3 Free Resources', 'arpc-popup-creator'); ?>" />
            </div>
            
      </form>
</div>