<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    AOT
 * @subpackage AOT/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    AOT
 * @subpackage AOT/public
 * @author     Your Name <rderrico@audiencedrive.com>
 */
class AOT_Public {

	/**
	 * A reference to the current instance of the plugin.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     AOT      $plugin  A reference to the current instance of the plugin.
	 */
	private $plugin;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  1.0.0
   * @param  AOT  $plugin  A reference to the current instance of the plugin.
	 */
	public function __construct($plugin) {

		$this->plugin = $plugin;

	}

  /**
   * Renders the relevant OG meta tags. Called by wp_head hook.
   *
   * @since    1.0.0
   */
	public function render_og_tags() {

    $query = $this->get_query_object();
    $post = $query->post;

    $this->render_general_tags();

    if (is_front_page()) {
      $this->render_home_tags();
      return;
    }

    if (is_category()) {
      $this->render_category_tags($query);
      return;
    }

    if (is_singular()) {
      $this->render_single_tags($post);
      return;
    }

    // If none of the above match, just render the home tags
    $this->render_home_tags();

	}


  /**
   * Gets a reference to the query object
   *
   * @since  1.0.0
   * @return WP_Query
   */
  private function get_query_object() {

    global $wp_query;
    return $wp_query;

  }

  /**
   * Renders tags that should appear on every route.
   *
   * @since  1.0.0
   */
  private function render_general_tags() {

    $this->render_fb_app_id_tag();
    $this->render_tag('site_name', get_bloginfo('name'));
    $this->render_tag('type', 'website');
    $this->render_tag('locale', 'en_US');

  }

  /**
   * Renders tags for single posts and pages
   *
   * @since  1.0.0
   * @param  WP_Post  $post  The post object
   */
	private function render_single_tags($post) {

    $image_url = $this->get_featured_image_url($post);
    if (!$image_url) {
      $image_url = $this->get_site_logo_url();
    }

    $title = $this->get_override_tag($post, 'post_title') ? $this->get_override_tag($post, 'post_title') : $post->post_title;
    $excerpt = wp_strip_all_tags(apply_filters('the_excerpt', $post->post_excerpt));
    $description = $this->get_override_tag($post, 'post_description') ? $this->get_override_tag($post, 'post_description') : $excerpt;

    $this->render_tag('title', $title);
    $this->render_tag('description', $description);
    $this->render_tag('url', get_permalink());
    $this->render_tag('image', $image_url);

  }

  /**
   * Returns the specified override tag set on the post. Returns an empty string if there is no override set.
   *
   * @since   1.0.0
   * @param   WP_POST  $post      The post object
   * @param   string   $tag_type  The override to retrieve ('post_title' or 'post_description')
   * @return  string              The override value, or an empty string
   */
  private function get_override_tag($post, $tag_type) {

    $override_key = $this->plugin->get_plugin_option('custom_' . $tag_type . '_override_field');
    $override_key = $override_key ? $override_key : $this->plugin->get_option_prefix() . $tag_type . '_override';

    return get_post_meta($post->ID, $override_key, true);

  }

  /**
   * Renders tags for the blog home page (and all other routes not included here)
   *
   * @since  1.0.0
   */
  private function render_home_tags() {

    $this->render_tag('title', get_bloginfo('name'));
    $this->render_tag('description', $this->get_site_description());
    $this->render_tag('url', get_bloginfo('url'));
    $this->render_tag('image', $this->get_site_logo_url());

  }

  /**
   * Renders tags for category archive pages
   *
   * @param   WP_Query  $query  The current query object
   */
  private function render_category_tags($query) {

    $category_slug = $query->query['category_name'];
    $category_id = get_cat_ID($category_slug);
    $category_name = get_cat_name($category_id);
    $category_description = category_description($category_id);
    $category_url = get_category_link($category_id);

    if ($category_description === '') {
      $category_description = $this->get_site_description();
    }

    $this->render_tag('title', $category_name);
    $this->render_tag('description', $category_description);
    $this->render_tag('image', $this->get_site_logo_url());
    $this->render_tag('url', $category_url);

  }

  /**
   * Renders an individual tag. If either param is false, the tag will not render.
   *
   * @since  1.0.0
   * @param  string  $property  the og property attribute
   * @param  string  $content   the og content attribute
   */
  private function render_tag($property, $content) {

    if (!$property || !$content) {
      return;
    }

    echo '<meta property="og:' . $property . '" content="' . $content . '" />' . PHP_EOL;

  }

  /**
   * Renders the Facebook app ID tag
   *
   * @since  1.0.0
   */
  private function render_fb_app_id_tag() {

    $app_id = $this->plugin->get_app_id();
    if (!$app_id) {
      return;
    }
    echo '<meta property="fb:app_id" content="' . $this->plugin->get_app_id() . '" />' . PHP_EOL;

  }

  /**
   * Returns the featured image URL for the post, or the site logo if there is no image
   *
   * @since  1.0.0
   * @param  WP_Post  $post  The post object
   * @return string          The full url of the featured image
   */
  private function get_featured_image_url($post) {

    $thumb_id = get_post_thumbnail_id($post->ID);
    $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'large');

    if (!$thumb_url_array) {
      return $site_logo_url = $this->get_site_logo_url();
    }
    return $thumb_url_array[0];

  }

  /**
   * Returns the site logo url set in the options, returns false if the site logo is not set
   *
   * @return  bool|string  The site logo url, or false if not set
   */
  private function get_site_logo_url() {

    return $this->plugin->get_plugin_option('site_logo');

  }

  /**
   * Returns the site description set in the options, returns false if the site description is not set
   *
   * @return  bool|string  The site description, or false if not set
   */
  private function get_site_description() {

    return $this->plugin->get_plugin_option('site_description');

  }


}
