<?php

/**
 * Defines a text area field using the WP Settings API
 */

class AATM_Textarea_Field extends AATM_Field {

  public function __construct($id, $label, $target_page, $target_section, $help_text = '', $class_name='large-text code', $rows="10") {

    $this->template_slug = 'textarea';
    $this->opt_args['help_text'] = $help_text;
    $this->opt_args['class_name'] = $class_name;
    $this->opt_args['rows'] = $rows;

    parent::__construct($id, $label, $target_page, $target_section);

  }

}