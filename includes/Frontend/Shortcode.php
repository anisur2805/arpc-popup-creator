<?php

namespace ARPC\Popup\Frontend;

class Shortcode {
      public function __construct() {
            
            add_shortcode('arpc_newsletter', array($this, 'render_shortcode'));
            // add_shortcode('arpc_frontend', array($this, 'render_arpc_frontend'));

      }

     
      
      public function render_shortcode($atts, $content) {
            $atts = shortcode_atts(array(
                  'title'           => __('Sign up for Snappy News!', 'popup-creator'),
                  'content'         => __('Get Free WordPress Videos, Plugins, and Other Useful Resources', 'popup-creator'),
            ), $atts, 'newsletter');

            ob_start();
            include __DIR__ . "/views/signup-form.php";
            return ob_get_clean();
            
            // $content = '<div>';
            // $content .= '<p>Hello world</p>';
            // $content .= '</div>';
            // return $content;
      }     

      public function render_arpc_frontend($atts, $content) {
            $atts = shortcode_atts(array(
                  'title'           => __('Sign up for Snappy News!', 'popup-creator'),
                  'content'         => __('Get Free WordPress Videos, Plugins, and Other Useful Resources', 'popup-creator'),
            ), $atts, 'newsletter');

            ob_start();
            include __DIR__ . "/views/react-frontend.php";
            return ob_get_clean();
            
            // $content = '<div>';
            // $content .= '<p>Hello world</p>';
            // $content .= '</div>';
            // return $content;
      }
}
