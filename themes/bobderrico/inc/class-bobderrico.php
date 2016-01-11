<?php
/**
 * A general class for various theme functions
 *
 * @package bobderrico
 */

class Bobderrico {

  private $excerpt_length;
  private $excerpt_more;

  function __construct($config) {

    $this->excerpt_length = $config->excerpt_length;
    $this->excerpt_more = $config->excerpt_more;
    $this->register_hooks();

  }

  public function register_hooks() {

    add_filter('excerpt_length', [$this, 'set_excerpt_length']);
    add_filter('excerpt_more', [$this, 'set_excerpt_more']);
    add_action('pre_get_posts', [$this, 'alter_home_pagination']);
    add_filter('found_posts', [$this, 'adjust_offset_pagination'], 1, 2);

  }

  public function set_excerpt_length() {

    return $this->excerpt_length;

  }

  public function set_excerpt_more() {

    return $this->excerpt_more;

  }

  public function alter_home_pagination(&$query) {

    if (!$query->is_home()) {
      return;
    }

    if (!$query->is_main_query()) {
      return;
    }

    $ppp = get_option('posts_per_page');

    if ($query->is_paged()) {
      $page_offset = 1 + (($query->query_vars['paged'] - 1) * $ppp);
      $query->set('offset', $page_offset);
    } else {
      $query->set('offset', 1);
    }

  }

  public function adjust_offset_pagination($found_posts, $query) {

    if ($query->is_home()) {
      return $found_posts - 1;
    }

    return $found_posts;

  }

}