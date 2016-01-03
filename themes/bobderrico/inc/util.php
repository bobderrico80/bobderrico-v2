<?php
  /**
   * A place for various utiliy functions related to this theme
   * @package bobderrico
   */

  /*
   * Adds .active class to current nav menu items
   */
  function special_nav_class($classes, $item) {
    if (in_array('current-menu-item', $classes)) {
      $classes[] = 'active ';
    }
    return $classes;
  }
  add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);
