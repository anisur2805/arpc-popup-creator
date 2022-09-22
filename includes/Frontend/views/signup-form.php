<div class="arpc-popup-creator-wrapper" id="arpc-popup-creator-wrapper">
      <form action="" method="post">
            <div class="arpc-form-group-row">
                  <input class="regular-text arpc_input" type="text" name="arpc-name" value="" placeholder="Enter your name" />
            </div>

            <div class="arpc-form-group-row">
                  <input class="regular-text arpc_input" type="email" name="arpc-email" value="" placeholder="Enter your email" required/>
            </div>

            <div class="arpc-form-group-row">
                  <?php wp_nonce_field( 'arpc-modal-form' ); ?>
                  <input type="hidden" name="action" value="arpc_modal_form_action">
                  <button type="submit" class="arpc_submit" name="arpc_submit" id="arpc_submit"><?php esc_attr_e('Subscribe Now', 'arpc-popup-creator'); ?></button>
                  <p class="hide"><?php echo esc_attr('Thanks for subscribe', 'arpc-popup-creator'); ?></p>
            </div>
            
      </form>
</div>