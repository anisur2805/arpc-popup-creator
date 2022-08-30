<?php

namespace ARPC\Popup;

use ARPC\Popup\Traits\Form_Error;

class Addresses {

    use Form_Error;

    public function address_page() {

        $action = isset($_GET['action']) ? $_GET['action'] : 'list';
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        switch ($action) {
            case 'new':
                $template = __DIR__ . '/Admin/Addresses/new-address.php';
                break;
            case 'edit':
                $template = __DIR__ . '/Admin/Addresses/edit-address.php';
                break;
            case 'view':
                $template = __DIR__ . '/Admin/Addresses/views-address.php';
                break;
            default:
                $template = __DIR__ . '/Admin/Addresses/list-address.php';
                break;
        }

        if (file_exists($template)) {
            include $template;
        }
    }

    public function form_handler() {

        if (!isset($_POST['submit_new_form'])) {
            return;
        }

        if (!current_user_can('manage_options')) {
            return;
        }

        if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'new-form')) {
            wp_die('Are you cheating?');
        }

        $name  = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $address  = isset($_POST['address']) ? sanitize_textarea_field($_POST['address']) : '';

        if (empty($name)) {
            $this->errors['name'] = __('Must provide a name', 'arpc-popup-creator');
        }

        if (empty($email)) {
            $this->errors['email'] = __('Must provide email', 'arpc-popup-creator');
        }

        if (empty($phone)) {
            $this->errors['phone'] = __('Must provide phone number', 'arpc-popup-creator');
        }

        if (empty($address)) {
            $this->errors['address'] = __('Must provide address', 'arpc-popup-creator');
        }

        if (!empty($this->errors)) {
            return;
        }

        $args = [
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'address' => $address,
        ];

        $insert_id = arpc_insert_users($args);

        if (is_wp_error($insert_id)) {
            wp_die($insert_id->get_error_message());
        }

        $redirected_to = admin_url('edit.php?post_type=arpc_popup&page=arpc-popup-form&action=new&inserted=true');
        wp_redirect($redirected_to);

        exit();
        
    }

    public function settings_page() { ?>
        <div class="wrap arpc_add_contact_wrap">
            <h1><?php _e('Popup Settings', 'popup-creator'); ?></h1>
            <form method="post" class="arpc_settings__form d-none">
                <div class="arpc_form_group">
                    <label for="arpc_fname">First Name</label>
                    <input type="text" class="regular-text" name="arpc_fname" id="arpc_fname" />
                </div>
                <div class="arpc_form_group">
                    <label for="arpc_lname">Last Name</label>
                    <input type="text" class="regular-text" id="arpc_lname" name="arpc_lname" />
                </div>
                <div class="arpc_form_group">
                    <label for="arpc_email">Email</label>
                    <input type="text" class="regular-text" id="arpc_email" name="arpc_email" />
                </div>
                <?php
                // wp_nonce_field('arpc-add-contact');
                // submit_button('Add Address', 'primary', 'arpc_submit_address_form'); 
                ?>
                <input type="hidden" name="action" value="arpc-add-contact" />
                <input type="submit" class="button button-primary arpc_submit_address_form" name="arpc_submit_address_form" id="arpc_submit_address_form" value="<?php esc_attr_e('Add Address'); ?>" />
            </form>

            <div class="arpc_tabbed_wrapper">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="choose_template"><?php _e('Choose template', 'arpc-popup-creator'); ?></a></li>
                    <li><a href="#overlay"><?php _e('Overlay', 'arpc-popup-creator'); ?></a></li>
                    <li><a href="#container"><?php _e('Container', 'arpc-popup-creator'); ?></a></li>
                    <li><a href="#title"><?php _e('Title', 'arpc-popup-creator'); ?></a></li>
                    <li><a href="#content"><?php _e('Content', 'arpc-popup-creator'); ?></a></li>
                    <li><a href="#close"><?php _e('Close Icon', 'arpc-popup-creator'); ?></a></li>
                </ul>

                <div class="tab-content">
                    <div id="choose_template" class="tab-pane active">
                        <div>
                            <form action="">
                                <div class="choose-template-wrapper">
                                    <label>
                                        <div>
                                            <input type="radio" name="template" value="0" <?php checked(0, get_option('template'), 0); ?> id="template1" />
                                            Template 1
                                        </div>
                                        <img src="<?php echo esc_url(ARPC_ASSETS . '/images/template1.png') ?>" alt="Template 1" />
                                    </label>
                                    
                                    <label>
                                        <div>
                                            <input type="radio" name="template" value="1" <?php checked(1, get_option('template'), 1); ?> id="template2" />
                                            Template 2
                                        </div>
                                        <img src="<?php echo esc_url(ARPC_ASSETS . '/images/template2.png') ?>" alt="Template 2" />
                                    </label>
                                </div>                           
                            </form>
                        </div>
                    </div>    
                    <div id="overlay" class="tab-pane">
                        <div>
                            <p>Some sort of content</p>
                        </div>
                    </div>
                    <div id="container" class="tab-pane">
                        <p>Hello world</p>
                    </div>
                    <div id="title" class="tab-pane">
                        <p>Some sort of content</p>
                    </div>
                    <div id="content" class="tab-pane">
                        <p>Some sort of content</p>
                    </div>
                    <div id="close" class="tab-pane">
                        <p>Some sort of content</p>
                    </div>
                </div>
            </div>

        </div>
    <?php

    }

    public function subscribers_page() {
    ?>
        <h2><?php _e('Subscriber Lists') ?></h2>
        <?php

        // global $wpdb;
        // $dbdemo_users = $wpdb->get_results($wpdb->prepare("SELECT id, name, email FROM {$wpdb->prefix}persons ORDER BY id DESC"), ARRAY_A);
        // print_r( $dbdemo_users );
        // die();
        // $data = array();
        $subscriber_table = new Subscriber_Data_Table();
        ?>
        <div class="wrap">
            <form id="art-search-form" method="GET">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
                <?php
                $subscriber_table->prepare_items();
                $subscriber_table->search_box('search', 'search_id');
                $subscriber_table->display();
                ?>
            </form>
        </div>
    <?php

    }

    public function form_page() {
    ?>
        <h2><?php _e('Form') ?></h2>
        <form method="post" class="arpc_settings__form2">
            <div class="arpc_form_group">
                <label for="name">Name</label>
                <input type="text" class="regular-text" name="name" id="name" />
                <?php if ($this->has_errors('name')) { ?>
                    <p class="description error"><?php echo $this->get_error('name'); ?></p>
                <?php } ?>
            </div>
            <div class="arpc_form_group">
                <label for="email">Email</label>
                <input type="text" class="regular-text" id="email" name="email" />
                <?php if ($this->has_errors('email')) { ?>
                    <p class="description error"><?php echo $this->get_error('email'); ?></p>
                <?php } ?>
            </div>
            <div class="arpc_form_group">
                <label for="phone">Phone</label>
                <input type="text" class="regular-text" id="phone" name="phone" />
                <?php if ($this->has_errors('phone')) { ?>
                    <p class="description error"><?php echo $this->get_error('phone'); ?></p>
                <?php } ?>
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
<?php

    }
}
