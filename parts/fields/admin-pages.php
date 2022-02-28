<?php

global $post;

$pc_ww_show = ( isset( $meta['pc_ww_show'][0] ) && '' !== $meta['pc_ww_show'][0] ) ? $meta['pc_ww_show'][0] : '';

$selected_page = get_post_meta($post->ID, 'pc_ww_show', true);
      $args = array(
            'depth' => 1,
            'class' => 'pc-admin-pages',
            'name'                  => 'pc_ww_show',
            'id'                    => 'pc_ww_show',
            'show_option_none'      => 'Select a page',
            'option_none_value'      => 0,
            'echo' => 1,
            'selected' => get_option('pc_ww_show'),
            // 'selected' => $pc_ww_show,
      );

      wp_dropdown_pages($args);