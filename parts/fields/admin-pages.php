<?php

$selected_page = get_post_meta($post->ID, 'pc_ww_show', true);

      $args = array(
            'depth' => 1,
            'class' => 'pc-admin-pages',
            'id'                    => piklist_form::get_field_id($arguments),
            'name'                  => piklist_form::get_field_name($arguments),
            'show_option_none'      => 'Select a page',
            'option_none_value'      => 0,
            'echo' => 1,
            'selected' => $selected_page,
            // 'selected' => get_option('pc_ww_show'),
      );

      wp_dropdown_pages($args);
      
