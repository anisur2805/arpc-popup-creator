<?php
/*
Title: Popup Creator
Post Type: popup
 */

piklist('field', array(
      'type'    => 'checkbox',
      'field'   => 'pc_active',
      'label'   => __('Active', 'popup-creator'),
      'value'   => 1,
      'choices' => array(
            1  => __('Active', 'popup-creator'),
      ),
));

piklist('field', array(
      'type' => 'text', 
      'field' => 'pc_show_in_delay', 
      'label' => __('Show in Delay', 'popup-creator'),
      'value' => '5',
      'help' => __('In seconds', 'popup-creator'),
));

piklist('field', array(
      'type' => 'url', 
      'field' => 'pc_url', 
      'label' => 'Popup URL', 
      'value' => '',
));

piklist('field', array(
      'type'    => 'radio',
      'field'   => 'pc_show_on_exit',
      'label'   => __('Display on', 'popup-creator'),
      'value'   => 1,
      'choices' => array(
            0  => __('On Page Exit', 'popup-creator'),
            1  => __('On Page Load', 'popup-creator'),
      ),
));

piklist('field', array(
      'type'    => 'checkbox',
      'field'   => 'pc_auto_hide',
      'label'   => __('Auto Hide', 'popup-creator'),
      'value'   => 1,
      'choices' => array(
            1  => __('Don\'t Hide', 'popup-creator'),
      ),
));

piklist('field', array(
      'type' => 'select', 
      'field' => 'pc_image_size', 
      'label' => 'Image Size', 
      'value' => 'landscape',
      'choices' => array(
            'popup-creator-landscape' => __('Landscape', 'popup-creator'),
            'popup-creator-square' => __('Square', 'popup-creator'), 
            'full' => __('Original', 'popup-creator'), 
      ),
));

piklist('field', array(
      'type'    => 'admin-pages',
      'field'   => 'pc_ww_show',
      'label'   => __('Where to show', 'popup-creator'),
));