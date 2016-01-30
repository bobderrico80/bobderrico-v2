<?php

class AOT_Menu {

  private $menu;
  private $submenus = [];

  public function __construct($id, $title_text, $menu_text, $icon) {

    $this->menu = [
      'id' => $id,
      'title_text' => $title_text,
      'menu_text' => $menu_text,
      'icon' => $icon
    ];

  }

  public function define_submenu($id, $title_text, $menu_text, $parent=false) {


    if (!$parent) {
      $parent = $this->menu['id'];
    }

    $this->submenus[] = [
      'id' => $id,
      'title_text' => $title_text,
      'menu_text' => $menu_text,
      'parent' => $parent
    ];

  }

  public function add_menu_pages() {

    $this->add_top_level_menu();
    $this->add_submenus();

  }

  private function add_top_level_menu() {

    add_menu_page(
      $this->menu['title_text'],
      $this->menu['menu_text'],
      'manage_options',
      $this->menu['id'],
      [$this, 'render_menu_page'],
      $this->menu['icon']
    );

  }

  protected function add_submenus() {

    foreach ($this->submenus as $submenu) {

      add_submenu_page(
        $submenu['parent'],
        $submenu['title_text'],
        $submenu['menu_text'],
        'manage_options',
        $submenu['id'],
        [$this, 'render_menu_page']
      );

    }

  }

  public function render_menu_page() {


    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/menus/' . $_GET['page']  . '.php';

  }

}

class AOT_Submenu extends AOT_Menu {

  public function __construct($id, $title_text, $menu_text, $parent) {

    $this->define_submenu($id, $title_text, $menu_text, $parent);

  }

  public function add_menu_pages() {

    $this->add_submenus();

  }

}