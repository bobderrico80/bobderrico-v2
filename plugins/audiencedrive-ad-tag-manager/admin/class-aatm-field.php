<?php

/**
 * An abstract class for defining fields using the WP Settings API
 *
 * @since 1.0.0
 * @package AATM
 * @subpackage AATM/field
 */

abstract class AATM_Field {

  protected $id;
  protected $label;
  protected $target_page;
  protected $target_section;
  protected $opt_args;
  protected $template_slug;

  public function __construct($id, $label, $target_page, $target_section) {
    $this->id = $id;
    $this->label = $label;
    $this->target_page = $target_page;
    $this->target_section = $target_section;
    $this->opt_args['label_for'] = $this->id;
  }

  public function add_field() {
    add_settings_field(
      $this->id,
      $this->label,
      [$this, 'render_field'],
      $this->target_page,
      $this->target_section,
      $this->opt_args
    );
  }

  public function render_field($args) {
    require plugin_dir_path(dirname(__FILE__)) . 'admin/partials/fields/' . $this->template_slug . '.php';
  }
}

