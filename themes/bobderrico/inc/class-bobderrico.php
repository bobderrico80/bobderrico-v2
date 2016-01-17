<?php

/**
 * A general class for various theme functions
 *
 * @package bobderrico
 */
class Bobderrico {

  private $excerpt_length;
  private $excerpt_more;

  function __construct() {

    $this->excerpt_length = 30;
    $this->excerpt_more = '...';
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

  public function render_post_time($class = '') {

    $class = 'entry-date published ' . $class;

    if (get_the_time('U') !== get_the_modified_time('U')) {
      $class .= ' updated';
    }

    $iso_date = esc_attr(get_the_date('c'));
    $date = esc_html(get_the_date());

    ?>

    <time class="<?= $class ?>" datetime="<?= $iso_date?>">
      <i class="fa fa-calendar"></i> <?= $date ?>
    </time>

    <?php

  }

  public function get_featured_image_info($post_id) {

    if (has_post_thumbnail()) {
      $featured_image_info = [];
      $post_thumbnail_id = get_post_thumbnail_id($post_id);
      $featured_image_info['src'] = wp_get_attachment_image_url($post_thumbnail_id, 'full');
      $featured_image_info['srcset'] = wp_get_attachment_image_srcset($post_thumbnail_id);
      $featured_image_info['alt'] = get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', true);

      return $featured_image_info;
    }

    return false;
  }

}