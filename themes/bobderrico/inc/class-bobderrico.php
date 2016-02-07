<?php

/**
 * A general class for various theme functions
 *
 * @package bobderrico
 */
class Bobderrico {

  private $project_excerpt_length;
  private $home_excerpt_length;
  private $excerpt_more;
  private $altered_post_types;
  private $custom_post_types = [];

  function __construct() {

    $this->home_excerpt_length = 30;
    $this->project_excerpt_length = 20;
    $this->job_excerpt_length = 20;
    $this->excerpt_more = '...';
    $this->altered_post_types = ['post', 'project', 'job'];
    $this->register_hooks();

  }

  public function register_hooks() {

    add_filter('excerpt_length', [$this, 'set_excerpt_length']);
    add_filter('excerpt_more', [$this, 'set_excerpt_more']);
    add_action('pre_get_posts', [$this, 'alter_home_pagination']);
    add_action('pre_get_posts', [$this, 'alter_job_sort_order']);
    add_filter('found_posts', [$this, 'adjust_offset_pagination'], 1, 2);
    add_action('init', [$this, 'register_custom_post_types']);
    add_action('save_post', [$this, 'save_custom_fields']);
    add_filter('wp_list_pages', [$this, 'add_custom_post_archives_to_menu']);
    add_filter('wp_nav_menu_items', [$this, 'add_custom_post_archives_to_menu']);
    add_action('after_setup_theme', [$this, 'add_custom_image_sizes']);
    add_filter('body_class', [$this, 'add_slug_to_body_class']);

  }

  public function set_excerpt_length() {

    if (is_home()) {
      return $this->home_excerpt_length;
    }

    if (is_post_type_archive('project')) {
      return $this->project_excerpt_length;
    }

    if (is_post_type_archive('job')) {
      return $this->job_excerpt_length;
    }

  }

  public function set_excerpt_more() {

    return $this->excerpt_more;

  }

  public function register_custom_post_types() {

    $this->register_custom_post_type('project', 'projects', 'Project portfolio items', 'dashicons-welcome-widgets-menus');
    $this->register_custom_post_type('job', 'jobs', 'Professional jobs held', 'dashicons-businessman');

  }

  private function register_custom_post_type($singular_name, $plural_name, $description, $icon) {

    $this->custom_post_types[] = [
      'name' => _x(ucfirst($plural_name), 'post type general name', 'bobderrico'),
      'slug' => $plural_name,
      'post_type' => $singular_name
    ];

    register_post_type($singular_name,
                       [
                           'labels' => [
                               'name' => _x(ucfirst($plural_name), 'post type general name', 'bobderrico'),
                               'singular_name' => _x(ucfirst($singular_name), 'post type singular name', 'bobderrico'),
                               'add_new' => _x('Add New', $singular_name, 'bobderrico'),
                               'add_new_item' => __('Add New ' . ucfirst($singular_name), 'bobderrico'),
                               'new_item' => __('New ' . ucfirst($singular_name), 'bobderrico'),
                               'edit_item' => __('Edit ' . ucfirst($singular_name), 'bobderrico'),
                               'view_item' => __('View ' . ucfirst($singular_name), 'bobderrico'),
                               'all_items' => __('All ' . ucfirst($plural_name), 'bobderrico'),
                               'search_items' => __('Search ' . ucfirst($plural_name), 'bobderrico'),
                               'not_found' => __('No ' . $plural_name . ' found.', 'bobderrico'),
                               'not_found_in_trash' => __('No '. $plural_name . ' found in trash', 'bobderrico')
                           ],
                           'description' => __($description, 'bobderrico'),
                           'public' => true,
                           'publicly_queryable' => true,
                           'show_ui' => true,
                           'show_in_menu' => true,
                           'query_var' => true,
                           'rewrite' => ['slug' => $plural_name],
                           'capability_type' => 'post',
                           'has_archive' => true,
                           'hierarchical' => false,
                           'menu_position' => 20,
                           'taxonomies' => ['skills'],
                           'menu_icon' => $icon,
                           'supports' => ['title', 'editor', 'thumbnail'],
                           'register_meta_box_cb' => [$this, 'register_custom_post_custom_fields']
                       ]
    );

  }

  public function register_custom_post_custom_fields($post) {

    if ($post->post_type === 'project') {
      $this->register_custom_fields('project_info', 'Project Info', 'project', ['project_url', 'github_url']);;
    } elseif ($post->post_type === 'job') {
      $this->register_custom_fields('job_info', 'Job Info', 'job', ['company', 'location', 'url', 'start_date', 'end_date']);
    }

  }

  private function register_custom_fields($name, $display_name, $screen, $meta_keys) {

    add_meta_box($name, __($display_name, 'bobderrico'), [$this, 'render_custom_metabox'], $screen, 'normal', 'default',
                 ['meta_keys' => $meta_keys, 'name' => $screen]);

  }

  public function render_custom_metabox($post, $args) {

    $name = $args['args']['name'];
    $values = [];

    foreach ($args['args']['meta_keys'] as $key) {
      $values[$key] = get_post_meta($post->ID, '_bd_' . $key, true);
    }

    wp_nonce_field('bobderrico_save_' . $name, 'bobderrico_' . $name . '_nonce');
    require('forms/' . $name . '-custom-fields.php');

  }

  public function save_custom_fields($post_id) {

    if (!isset($_POST['post_type'])) {
      return;
    }

    $name = $_POST['post_type'];

    if (!isset($_POST['bobderrico_' . $name . '_nonce'])) {
      return;
    }

    if (!wp_verify_nonce($_POST['bobderrico_' . $name . '_nonce'], 'bobderrico_save_' . $name)) {
      return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    if (!current_user_can('edit_post', $post_id)) {
      return;
    }

    if (!isset($_POST['values'])) {
      return;
    }

    foreach ($_POST['values'] as $key => $value) {
      update_post_meta($post_id, '_bd_' . $key, $value);
    }

  }

  public function alter_home_pagination(&$query) {

    if ($query->is_admin) {
      return;
    }

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

  public function alter_job_sort_order($query) {

    if ($query->is_admin) {
      return;
    }

    if ($query->get('post_type') !== 'job') {
      return;
    }

    $query->set('meta_key', '_bd_start_date');
    $query->set('orderby', 'meta_value');
    $query->set('order', 'DESC');

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

  public function get_featured_image_info($post_id, $size = null) {

    if (has_post_thumbnail()) {
      $featured_image_info = [];
      $post_thumbnail_id = get_post_thumbnail_id($post_id);
      $featured_image_info['src'] = wp_get_attachment_image_url($post_thumbnail_id, 'full');
      $featured_image_info['srcset'] = wp_get_attachment_image_srcset($post_thumbnail_id, $size);
      $featured_image_info['alt'] = get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', true);

      return $featured_image_info;
    }

    return false;
  }

  public function get_project_urls($post_id) {

    $project_urls = [];
    $project_urls['github'] = esc_url(get_post_meta($post_id, '_bd_github_url', true));
    $project_urls['project'] = esc_url(get_post_meta($post_id, '_bd_project_url', true));

    return $project_urls;

  }

  public function get_job_info($post_id) {

    $job_info = [];
    $job_info['location'] = get_post_meta($post_id, '_bd_location', true);
    $job_info['url'] = esc_url(get_post_meta($post_id, '_bd_url', true));
    $job_info['start_date'] = $this->convert_date(get_post_meta($post_id, '_bd_start_date', true));
    $job_info['end_date'] = $this->convert_date(get_post_meta($post_id, '_bd_end_date', true), 'present');
    $job_info['company'] = get_post_meta($post_id, '_bd_company', true);

    return $job_info;

  }

  private function convert_date($date_string, $default = false) {

    if (!$date_string) {
      return $default;
    }

    $wp_date_format = get_option('date_format');
    $date = new DateTime($date_string);

    return $date->format($wp_date_format);

  }

  public function render_featured_image($post_id, $size = null) {

    $featured_image_info = $this->get_featured_image_info($post_id, $size);

    if ($featured_image_info):
      ?>
      <img class="entry-featured-image" src="<?= $featured_image_info['src'] ?>" srcset="<?= $featured_image_info['srcset'] ?>"
           alt="<?= $featured_image_info['alt'] ?>">
      <?php
    endif;

  }

  public function render_project_links($post_id) {

    $project_urls = $this->get_project_urls($post_id);

    ?>

    <p class="project-project-url">
      <i class="fa fa-globe"></i>
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

  public function render_job_info($post_id) {

    $job_info = $this->get_job_info($post_id);

    ?>

    <h3 class="job-company">
      <a href="<?= $job_info['url'] ?>" target="_blank">
        <?= $job_info['company'] ?>
      </a>
    </h3>
    <p class="job-location"><?= $job_info['location'] ?></p>
    <p class="job-timeframe"><?= $job_info['start_date'] ?> &mdash; <?= $job_info['end_date'] ?></p>

    <?php

  }

  public function get_post_type_text($post) {

    if ($post->post_type === 'project') {
      return __('Project', 'bobderrico');
    }

    if ($post->post_type === 'job') {
      return false;
    }

    return __('Blog post', 'bobderrico');

  }

  public function render_post_type_text($post) {

    $text = $this->get_post_type_text($post);

    if ($text) {
      ?>

      <h3 class="skills-post-type">
        <?= $text ?>
      </h3>

      <?php
    }

  }

  public function add_custom_post_archives_to_menu($items) {

    global $wp_query;
    $item_array = explode('</li>', $items);
    $class = 'menu-item menu-item-type-post_type menu-item-object-page';
    $insert_after_item = 1;

    foreach ($this->custom_post_types as $link) {
      if (isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] === $link['post_type']) {
        $active_class = 'current-menu-item';
      } else {
        $active_class = '';
      }

      $item = '<li class="' . $class . ' ' . $active_class  . '"><a href="' . site_url() . '/' . $link['slug'] . '">' . $link['name'] . '</a>';
      array_splice($item_array, $insert_after_item, 0, $item);
      $insert_after_item++;
    }

    return implode('</li>', $item_array);

  }

  public function add_custom_image_sizes() {
    add_image_size('square-xs', 768, 768, true);
    add_image_size('square-sm', 868, 868, true);
    add_image_size('square-md', 1000, 1000, true);
  }

  public function add_slug_to_body_class($classes) {

    $classes[] = get_post_field('post_name', get_the_ID());

    return $classes;

  }

  public function render_comment_count($post_id, $class = 'entry-comment-count') {

    if ($comment_count = get_comments_number($post_id)) {
      ?>
      <div class="<?= $class ?>">
        <a href="<?= get_the_permalink($post_id) ?>#comments">
          <i class="fa fa-comment"></i> <?= $comment_count ?>
        </a>
      </div>
      <?php
    }
  }




}