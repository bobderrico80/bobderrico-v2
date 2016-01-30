<?php

class AOT_Checkbox_Field extends AOT_Field {

  public function __construct($options_key, $id, $label, $target_page, $target_section, $cb_text) {

    $this->template_slug = 'checkbox';
    $this->opt_args['cb_text'] = $cb_text;

    parent::__construct($options_key, $id, $label, $target_page, $target_section);

  }
}