<?php
  /**
   * bobderrico functions and definitions.
   *
   * @link https://developer.wordpress.org/themes/basics/theme-functions/
   *
   * @package bobderrico
   */

  if (!function_exists('bobderrico_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */


    function bobderrico_setup() {
      /*
       * Make theme available for translation.
       * Translations can be filed in the /languages/ directory.
       * If you're building a theme based on bobderrico, use a find and replace
       * to change 'bobderrico' to the name of your theme in all the template files.
       */
      load_theme_textdomain('bobderrico', get_template_directory() . '/languages');

      // Add default posts and comments RSS feed links to head.
      add_theme_support('automatic-feed-links');

      /*
       * Let WordPress manage the document title.
       * By adding theme support, we declare that this theme does not use a
       * hard-coded <title> tag in the document head, and expect WordPress to
       * provide it for us.
       */
      add_theme_support('title-tag');

      /*
       * Enable support for Post Thumbnails on posts and pages.
       *
       * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
       */
      add_theme_support('post-thumbnails');

      // This theme uses wp_nav_menu() in one location.
      register_nav_menus([
                             'primary' => esc_html__('Primary', 'bobderrico'),
                         ]);

      /*
       * Switch default core markup for search form, comment form, and comments
       * to output valid HTML5.
       */
      add_theme_support('html5', [
          'search-form',
          'comment-form',
          'comment-list',
          'gallery',
          'caption'
      ]);

      /*
       * Enable support for Post Formats.
       * See https://developer.wordpress.org/themes/functionality/post-formats/
       */
      add_theme_support('post-formats', [
          'aside',
          'image',
          'video',
          'quote',
          'link'
      ]);

      // Set up the WordPress core custom background feature.
      add_theme_support('custom-background', apply_filters('bobderrico_custom_background_args', [
          'default-color' => 'ffffff',
          'default-image' => ''
      ]));
    }
  endif;
  add_action('after_setup_theme', 'bobderrico_setup');

  /**
   * Set the content width in pixels, based on the theme's design and stylesheet.
   *
   * Priority 0 to make it available to lower priority callbacks.
   *
   * @global int $content_width
   */
  function bobderrico_content_width() {
    $GLOBALS['content_width'] = apply_filters('bobderrico_content_width', 640);
  }

  add_action('after_setup_theme', 'bobderrico_content_width', 0);

  /**
   * Register widget area.
   *
   * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
   */
  function bobderrico_widgets_init() {
    register_sidebar([
                         'name' => esc_html__('Sidebar', 'bobderrico'),
                         'id' => 'sidebar-1',
                         'description' => '',
                         'before_widget' => '<section id="%1$s" class="widget %2$s">',
                         'after_widget' => '</section>',
                         'before_title' => '<h2 class="widget-title">',
                         'after_title' => '</h2>'
                     ]);
  }

  add_action('widgets_init', 'bobderrico_widgets_init');

  /**
   * Enqueue scripts and styles.
   */
  function bobderrico_scripts() {
    wp_enqueue_style('bootstrap-css',
                     get_template_directory_uri() . '/assets/dist/vendor/bootstrap/dist/css/bootstrap.min.css');
    wp_enqueue_style('bootstrap-theme-css',
                     get_template_directory_uri() . '/assets/dist/vendor/bootstrap/dist/css/bootstrap-theme.min.css',
                     ['bootstrap-css']);
    wp_enqueue_style('bobderrico-styles', get_template_directory_uri() . '/assets/dist/styles/main.css',
                     ['bootstrap-css', 'bootstrap-theme-css'], wp_get_theme()->get('Version'));
    wp_enqueue_script('jquery', get_template_directory_uri() . '/assets/dist/vendor/jquery/dist/jquery.min.js');
    wp_enqueue_script('bootstrap-js',
                      get_template_directory_uri() . '/assets/dist/vendor/bootstrap/dist/js/bootstrap.min.js',
                      ['jquery']);
    wp_enqueue_script('bobderrico-scripts', get_template_directory_uri() . '/assets/dist/scripts/main.js',
                      ['jquery', 'bootstrap-js'], wp_get_theme()->get('Version'), true);
  }

  add_action('wp_enqueue_scripts', 'bobderrico_scripts');

  /**
   * Implement the Custom Header feature.
   */
  require get_template_directory() . '/inc/custom-header.php';

  /**
   * Custom template tags for this theme.
   */
  require get_template_directory() . '/inc/template-tags.php';

  /**
   * Custom functions that act independently of the theme templates.
   */
  require get_template_directory() . '/inc/extras.php';

  /**
   * Customizer additions.
   */
  require get_template_directory() . '/inc/customizer.php';

  /**
   * Load Jetpack compatibility file.
   */
  require get_template_directory() . '/inc/jetpack.php';
