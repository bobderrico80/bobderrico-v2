<?php

class AOT_Text_Field extends AOT_Field {

  public function __construct($options_key, $id, $label, $target_page, $target_section, $help_text = '', $class_name='regular-text') {

    $this->template_slug = 'text';
    $this->opt_args['help_text'] = $help_text;
    $this->opt_args['class_name'] = $class_name;

    parent::__construct($options_key, $id, $label, $target_page, $target_section);

  }
}