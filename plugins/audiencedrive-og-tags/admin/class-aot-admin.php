<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://audiencedrive.com
 * @since      1.0.0
 *
 * @package    AOT
 * @subpackage AOT/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    AOT
 * @subpackage AOT/admin
 * @author     Your Name <rderrico@audiencedrive.com>
 */
class AOT_Admin {

	/**
	 * A reference to the instance of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      AOT      $plugin    A reference to the instance of the plugin.
	 */
	private $plugin;

	public function __construct($plugin) {

		$this->plugin = $plugin;

	}

	/**
	 * Initialize admin menus. Called by admin_menu hook.
	 *
	 * @since  1.0.0
	 */
	public function initialize_menus() {

		$submenus = new AOT_Submenu(
      'aot_options',
      'AudienceDrive OpenGraph Tag Options',
      'AOT Options',
      'options-general.php'
    );

    $submenus->add_menu_pages();

	}

  /**
   * Initialize admin options. Called by admin_init hook.
   *
   * @since  1.0.0
   */
  public function initialize_options() {

    $sections = new AOT_Section(
      'aot_options_main',
      'aot_options'
    );
    $sections->add_sections();

    $fields = [];
    $options_key = $this->plugin->get_option_prefix() . 'options';

    $fields[] = new AOT_Text_Field(
      $options_key,
      'app_id',
      'Facebook App Id',
      'aot_options',
      'aot_options_main',
      'Enter the Facebook App ID for the site'
    );

    $fields[] = new AOT_Text_Field(
      $options_key,
      'site_logo',
      'Site Logo URL',
      'aot_options',
      'aot_options_main',
      'Absolute URL to the site\'s default logo'
    );

    $fields[] = new AOT_Textarea_Field(
      $options_key,
      'site_description',
      'Site Description',
      'aot_options',
      'aot_options_main',
      'Description to use on non-single pages',
      'large-text'
    );

    $fields[] = new AOT_Checkbox_Field(
      $options_key,
      'display_overrides_box',
      'Display Overrides Fields',
      'aot_options',
      'aot_options_main',
      'Display overrides custom fields box on posts/pages'
    );

    $fields[] = new AOT_Text_Field(
      $options_key,
      'custom_post_title_override_field',
      'Custom Title Override Field',
      'aot_options',
      'aot_options_main',
      'Existing post meta field to use for title override'
    );

    $fields[] = new AOT_Text_Field(
      $options_key,
      'custom_post_description_override_field',
      'Custom Description Override Field',
      'aot_options',
      'aot_options_main',
      'Existing post meta field to use for description override'
    );

    foreach ($fields as $field) {
      $field->add_field();
    }

    register_setting('_aot_options', '_aot_options');

  }

  public function add_post_override_fields() {

    $override_option = $this->plugin->get_plugin_option('display_overrides_box');
    if (isset($override_option)) {
      $screens = ['post', 'page'];

      foreach ($screens as $screen) {
        add_meta_box(
          $this->plugin->get_plugin_id() . '_og_overrides',
          'AudienceDrive OG Tag Overrides',
          [$this, 'render_post_override_fields'],
          $screen,
          'normal'
        );
      }
    }

  }

  public function render_post_override_fields($post) {

    wp_nonce_field('save_post_override_fields', 'post_override_fields_nonce');
    $title_override = get_post_meta($post->ID, $this->plugin->get_option_prefix() . 'post_title_override', true);
    $description_override = get_post_meta($post->ID, $this->plugin->get_option_prefix() . 'post_description_override', true);

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/custom-fields/post-override-fields.php';

  }

  public function save_post_override_fields($post_id) {

    if (!isset($_POST['post_override_fields_nonce'])) {
      return;
    }

    if (!wp_verify_nonce($_POST['post_override_fields_nonce'], 'save_post_override_fields')) {
      return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
      if (!current_user_can('edit_page', $post_id)) {
        return;
      }
    } else {
      if (!current_user_can('edit_post', $post_id)) {
        return;
      }
    }

    if (!isset($_POST['post_title_override']) || !isset($_POST['post_description_override'])) {
      return;
    }

    $title_override = $_POST['post_title_override'];
    $description_override = $_POST['post_description_override'];
    update_post_meta($post_id, $this->plugin->get_option_prefix() . 'post_title_override', $title_override);
    update_post_meta($post_id, $this->plugin->get_option_prefix() . 'post_description_override', $description_override);

  }


}
