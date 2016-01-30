<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    AOT
 * @subpackage AOT/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    AOT
 * @subpackage AOT/includes
 * @author     Your Name <email@example.com>
 */
class AOT {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      AOT_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_id    The string used to uniquely identify this plugin.
	 */
	protected $plugin_id;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The Facebook app ID for this website.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     string     $app_id  The Facebook app ID for this website.
	 */
	protected $app_id;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_id = 'aot';
		$this->version = '1.0.0';

    $this->set_app_id();
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - AOT_Loader. Orchestrates the hooks of the plugin.
	 * - AOT_i18n. Defines internationalization functionality.
	 * - AOT_Admin. Defines all hooks for the admin area.
	 * - AOT_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-aot-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-aot-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-aot-admin.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-aot-field.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-aot-menu.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-aot-section.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-aot-text-field.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-aot-textarea-field.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-aot-checkbox-field.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-aot-public.php';

		$this->loader = new AOT_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the AOT_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new AOT_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new AOT_Admin($this);

    $this->loader->add_action('admin_menu', $plugin_admin, 'initialize_menus');
    $this->loader->add_action('admin_init', $plugin_admin, 'initialize_options');
		$this->loader->add_action('add_meta_boxes', $plugin_admin, 'add_post_override_fields');
		$this->loader->add_action('save_post', $plugin_admin, 'save_post_override_fields');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new AOT_Public($this);

		$this->loader->add_action('wp_head', $plugin_public, 'render_og_tags');

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_id() {
		return $this->plugin_id;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    AOT_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

  /**
   * Sets the Facebook app ID for the site on the plugin object
   *
   * @since  1.0.0
   */
  private function set_app_id() {

    $this->app_id = $this->get_plugin_option('app_id');

  }

	/**
	 * Retrieve the site Facebook app id from the database
	 *
	 * @since     1.0.0
	 * @return    string    The Facebook app id of the site.
	 */
	public function get_app_id() {

		return $this->app_id;

	}

  /**
   * Return the wp_options prefix for this plugin in the format '_pluginid_'
   *
   * @since   1.0.0
   * @return  string  The wp_options prefix
   */
	public function get_option_prefix() {
    return '_' . $this->plugin_id . '_';
  }

  /**
   * Wrapper for get_option() that adds the proper prefix for this plugin.
   *
   * @since   1.0.0
   * @param   string          $key  The key to lookup.
   * @return  string|boolean  The value of the key or false if key does not exist
   */
  public function get_plugin_option($key) {
    $options_array = get_option($this->get_option_prefix() . 'options');
    return $options_array[$key];
  }

}
