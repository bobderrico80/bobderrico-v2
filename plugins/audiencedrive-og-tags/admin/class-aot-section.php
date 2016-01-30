<?php

class AOT_Section {

  private $sections = [];

  public function __construct($id, $page, $title = '') {

    $this->define_section($id, $page, $title);

  }

  public function define_section($id, $page, $title = '') {

    $this->sections[] = [
      'id' => $id,
      'page' => $page,
      'title' => $title
    ];

  }

  public function add_sections() {

    foreach ($this->sections as $section) {
      add_settings_section(
        $section['id'],
        $section['title'],
        [$this, 'render_section'],
        $section['page']
      );
    }

  }

  // TODO make this function render a page template in admin/partials/sections
  public function render_section() {
    echo '';
  }
}