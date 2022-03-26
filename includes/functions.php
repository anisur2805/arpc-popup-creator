<?php

/**
 * Register popup creator image sizes
 */
function register_image_size() {
      add_image_size('popup-creator-landscape', 800, 600, true);
      add_image_size('popup-creator-square', 500, 500, true);
      add_image_size('popup-creator-thumbnail', 70);
}
