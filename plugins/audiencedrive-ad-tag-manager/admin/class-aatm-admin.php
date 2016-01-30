<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    AATM
 * @subpackage AATM/admin
 */


class AATM_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

  private $current_slugname;

	public function __construct($plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

  public function initialize_menus() {

    $submenus = new AATM_Submenu(
      'sitetags',
      'Site Tags',
      'Site Tags',
      'edit.php?post_type=aatm_ad_tag'
    );

    $submenus->add_menu_pages();

  }

	public function initialize_options() {

    $sections = new AATM_Section('sitetags-main', 'sitetags');
    $sections->add_sections();

    $fields = [];

    $fields[] = new AATM_Textarea_Field(
      'header_code',
      'Header Code',
      'sitetags',
      'sitetags-main',
      'Code here will be inserted into the &lt;head&gt; tag of every page'
    );

    $fields[] = new AATM_Textarea_Field(
      'dfp_base',
      'DFP Base Code',
      'sitetags',
      'sitetags-main',
      'DFP header base code. Remove all <code>googletag.defineSlot()</code> functions, and replace with <code>//placements' .
      '</code>. These will be inserted dynamically'
    );

    $fields[] = new AATM_Textarea_Field(
      'footer_code',
      'Footer Code',
      'sitetags',
      'sitetags-main',
      'Code here will be inserted just below the closing &lt;/body&gt; tag of every page'
    );

    foreach ($fields as $field) {
      $field->add_field();
    }

    register_setting('aatm_options', 'aatm_options');
	}

  public function register_aatm_post_type() {

    register_post_type('aatm_ad_tag',
      [
        'labels' => [
          'name' => 'Ad Tags',
          'singular_name' => 'Ad Tag',
          'add_new_item' => 'Add New Ad Tag',
          'edit_item' => 'Edit Ad Tag',
          'new_item' => 'New Ad Tag',
          'view_item' => 'View Ad Tag',
          'not_found' => 'No ad tags found',
          'not_found_in_trash' => 'No ad tags found in Trash',
        ],
        'exclude_from_search' => true,
        'public_queryable' => false,
        'show_ui' => true,
        'show_in_nav_menus' => false,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-editor-code',
        'supports' => ['title'],
        'register_meta_box_cb' => [$this, 'register_aatm_custom_fields'],
        'rewrite' => false,
        'query_var' => false
      ]
    );

  }

  public function customize_ad_tag_title_placeholder($title) {

    $screen = get_current_screen();

    if ('aatm_ad_tag' == $screen->post_type) {
      $title = 'Enter placement name here';
    }

    return $title;

  }

  public function customize_ad_tag_list_columns($columns) {

    unset($columns['date']);
    return [
      'cb' => $columns['cb'],
      'title' => $columns['title'],
      'is_dfp' => 'DFP?',
      'is_desktop' => 'Desktop?',
      'is_mobile' => 'Mobile?',
      'show_disclaimer' => 'Disclaimer',
      'placement_shortcode' => 'Placement Shortcode'
    ];

  }

  public function customize_ad_tag_list_custom_column($column, $post_id) {

    if ($column === 'placement_shortcode') {
      $post = get_post($post_id, ARRAY_A);
      echo '[aatm_placement id="' . $post['post_name'] . '"]';
      return;
    }

    if ($column === 'is_dfp') {
      $this->render_boolean_post_column($post_id, '_aatm_is_dfp', 'DFP', '');
      return;
    }

    if ($column === 'is_desktop') {
      $this->render_boolean_post_column($post_id, '_aatm_is_desktop', 'Desktop', '');
    }

    if ($column === 'is_mobile') {
      $this->render_boolean_post_column($post_id, '_aatm_is_mobile', 'Mobile', '');
    }

    if ($column === 'show_disclaimer') {
      $this->render_boolean_post_column($post_id, '_aatm_show_disclaimer', 'Disclaimer', '');
    }

  }

  private function render_boolean_post_column($post_id, $key, $true_text = 'Yes', $false_text = 'No') {

    $value = get_post_meta($post_id, $key, true);
    if ($value) {
      echo $true_text;
    } else {
      echo $false_text;
    }

    return;

  }

  public function register_aatm_custom_fields($post) {

    remove_meta_box('fbc_sectionid', 'aatm_ad_tag', 'advanced');
    remove_meta_box('td_post_theme_settings_metabox', 'aatm_ad_tag', 'normal');

    add_meta_box(
      'ad_tag_code',
      'Ad Tag Code',
      [$this, 'render_ad_tag_code_field'],
      'aatm_ad_tag',
      'normal'
    );

    add_meta_box(
      'is_desktop',
      'Desktop Ad',
      [$this, 'render_is_desktop_field'],
      'aatm_ad_tag',
      'normal'
    );

    add_meta_box(
      'is_mobile',
      'Mobile Ad',
      [$this, 'render_is_mobile_field'],
      'aatm_ad_tag',
      'normal'
    );

    add_meta_box(
      'show_disclaimer',
      'Display disclaimer',
      [$this, 'render_show_disclaimer_field'],
      'aatm_ad_tag',
      'normal'
    );

    add_meta_box(
      'is_dfp',
      'Google DFP Placement',
      [$this, 'render_is_dfp_field'],
      'aatm_ad_tag',
      'normal'
    );

    add_meta_box(
      'dfp_defineslot_args',
      'Google DFP defineSlot function Arguments',
      [$this, 'render_dfp_defineslot_args'],
      'aatm_ad_tag',
      'normal'
    );

    add_meta_box(
      'dfp_locations',
      'DFP Tag Locations',
      [$this, 'render_dfp_locations'],
      'aatm_ad_tag',
      'normal'
    );

  }

  public function render_ad_tag_code_field($post) {

    $this->render_field($post, 'ad_tag_code', 'textarea');

  }

  public function render_is_dfp_field($post) {

    $this->render_field($post, 'is_dfp', 'cb', 'This is a Google DFP placement');

  }

  public function render_dfp_defineslot_args($post) {

    $this->render_field($post, 'dfp_defineslot_args', 'text', '', 'large-text');

  }

  public function render_dfp_locations($post) {

    $this->render_field($post, 'dfp_locations', 'textarea', '', 'regular-text code');

  }

  public function render_is_mobile_field($post) {

    $this->render_field($post, 'is_mobile', 'cb', 'Run this ad on mobile');

  }

  public function render_is_desktop_field($post) {

    $this->render_field($post, 'is_desktop', 'cb', 'Run this ad on desktop');

  }

  public function render_show_disclaimer_field($post) {

    $this->render_field($post, 'show_disclaimer', 'cb', 'Display a disclaimer reading \'Advertisement\' above the ad');

  }

  private function render_field($post, $id, $type, $label='', $class=false) {

    if (!$class) {
      $class = $this->get_default_class($type);
    }

    wp_nonce_field('save_' . $id, $id . '_nonce');
    $value = get_post_meta($post->ID, '_aatm_' . $id, true);

    require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/custom-fields/' . $type . '.php';

  }

  private function get_default_class($type) {

    if ($type === 'text') {
      return 'regular-text';
    }

    if ($type === 'textarea') {
      return 'large-text code';
    }

    return '';

  }

  public function register_custom_field_save_callbacks($post_id) {

    $this->save_ad_tag_code_field($post_id);
    $this->save_is_dfp_field($post_id);
    $this->save_dfp_defineslot_args($post_id);
    $this->save_dfp_locations($post_id);
    $this->save_is_mobile_field($post_id);
    $this->save_is_desktop_field($post_id);
    $this->save_show_disclaimer_field($post_id);

  }

  public function save_ad_tag_code_field($post_id) {

    $this->save_field($post_id, 'ad_tag_code');

  }

  public function save_is_dfp_field($post_id) {

    $this->save_field($post_id, 'is_dfp');

  }

  public function save_dfp_defineslot_args($post_id) {

    $this->save_field($post_id, 'dfp_defineslot_args');

  }

  public function save_dfp_locations($post_id) {

    $this->save_field($post_id, 'dfp_locations');

  }

  public function save_is_mobile_field($post_id) {

    $this->save_field($post_id, 'is_mobile');

  }

  public function save_is_desktop_field($post_id) {

    $this->save_field($post_id, 'is_desktop');

  }

  public function save_show_disclaimer_field($post_id) {

    $this->save_field($post_id, 'show_disclaimer');

  }

  private function save_field($post_id, $id) {

    if (!isset($_POST[$id . '_nonce'])) {
      return;
    }

    if (!wp_verify_nonce($_POST[$id . '_nonce'], 'save_' . $id)) {
      return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    if (isset($_POST['post_type']) && 'aatm_ad_tag' == $_POST['post_type']) {
      if (!current_user_can('edit_page', $post_id)) {
        return;
      }
    } else {
      if (!current_user_can('edit_post', $post_id)) {
        return;
      }
    }

    $my_data = $_POST[$id];
    update_post_meta($post_id, '_aatm_' . $id, $my_data);

  }

}

