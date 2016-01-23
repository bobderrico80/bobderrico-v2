<?php

/**
 * A general class for various theme functions
 *
 * @package bobderrico
 */
class Bobderrico {

  private $excerpt_length;
  private $excerpt_more;
  private $altered_post_type_archives;

  function __construct() {

    $this->home_excerpt_length = 30;
    $this->project_excerpt_length = 20;
    $this->excerpt_more = '...';
    $this->altered_post_types = ['post', 'project'];
    $this->register_hooks();

  }

  public function register_hooks() {

    add_filter('excerpt_length', [$this, 'set_excerpt_length']);
    add_filter('excerpt_more', [$this, 'set_excerpt_more']);
    add_action('pre_get_posts', [$this, 'alter_home_pagination']);
    add_filter('found_posts', [$this, 'adjust_offset_pagination'], 1, 2);
    add_action('init', [$this, 'register_custom_post_types']);
    add_action('save_post', [$this, 'save_custom_fields']);

  }

  public function set_excerpt_length() {

    if (is_home()) {
      return $this->home_excerpt_length;
    }

    if (is_post_type_archive('project')) {
      return $this->project_excerpt_length;
    }

  }

  public function set_excerpt_more() {

    return $this->excerpt_more;

  }

  public function register_custom_post_types() {

    $this->register_project_post_type();

  }

  private function register_project_post_type() {

    register_post_type('project',
      [
        'labels' => [
          'name' => _x('Projects', 'post type general name', 'bobderrico'),
          'singular_name' => _x('Project', 'post type singular name', 'bobderrico'),
          'add_new' => _x('Add New', 'project', 'bobderrico'),
          'add_new_item' => __('Add New Project', 'bobderrico'),
          'new_item' => __('New Project', 'bobderrico'),
          'edit_item' => __('Edit Project', 'bobderrico'),
          'view_item' => __('View Project', 'bobderrico'),
          'all_items' => __('All Projects', 'bobderrico'),
          'search_items' => __('Search Projects', 'bobderrico'),
          'not_found' => __('No projects found.', 'bobderrico'),
          'not_found_in_trash' => __('No projects found in trash', 'bobderrico')
        ],
        'description' => __('Project portfolio items', 'bobderrico'),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'projects'],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 20,
        'taxonomies' => ['skills'],
        'menu_icon' => 'dashicons-welcome-widgets-menus',
        'supports' => ['title', 'editor', 'thumbnail'],
        'register_meta_box_cb' => [$this, 'register_project_custom_fields']
      ]
    );

  }

  public function register_project_custom_fields() {

    add_meta_box('project-info', __('Project Info', 'bobderrico'), [$this, 'render_project_custom_fields'], 'project',
                 'normal', 'default');

  }

  public function render_project_custom_fields($post) {

    require('forms/project-custom-fields.php');

  }

  public function save_custom_fields($post_id) {

    $this->save_project($post_id);

  }

  private function save_project($post_id) {

    if (!isset($_POST['bobderrico_project_info_nonce'])) {
      return;
    }

    if (!wp_verify_nonce($_POST['bobderrico_project_info_nonce'], 'bobderrico_save_project_info')) {
      return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    if (isset($_POST['post_type']) && 'project' === $_POST['post_type'] ) {

      if (!current_user_can('edit_post', $post_id)) {
        return;
      }

    }

    if (!isset($_POST['project_url']) || !isset($_POST['github_url'])) {
      return;
    }

    $project_url = sanitize_text_field($_POST['project_url']);
    $github_url = sanitize_text_field($_POST['github_url']);

    update_post_meta($post_id, '_bd_project_url', $project_url);
    update_post_meta($post_id, '_bd_github_url', $github_url);

  }

  public function alter_home_pagination(&$query) {

    if (!$query->is_home() && !$this->is_altered_post_type($query)) {
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

  private function is_altered_post_type($query) {

    foreach ($this->altered_post_types as $altered_post_type) {
      if ($query->is_post_type_archive($altered_post_type)) {
        return true;
      }
    }

    return false;

  }

  public function adjust_offset_pagination($found_posts, $query) {

    if ($query->is_home() || $this->is_altered_post_type($query)) {
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

  public function get_project_urls($post_id) {

    $project_urls = [];
    $project_urls['github'] = esc_url(get_post_meta(get_the_id(), '_bd_github_url', true));
    $project_urls['project'] = esc_url(get_post_meta(get_the_id(), '_bd_project_url', true));

    return $project_urls;

  }

  public function render_project_links($post_id) {

    $project_urls = $this->get_project_urls($post_id);

    ?>

    <p class="project-project-url">
      <a href="<?= $project_urls['project'] ?>">
        <?= $project_urls['project'] ?>
      </a>
    </p>
    <p class="project-github-url">
      <i class="fa fa-github-square"></i>
      <a href="<?= $project_urls['github'] ?>">
        <?= $project_urls['github'] ?>
      </a>
    </p>

    <?php
  }

}