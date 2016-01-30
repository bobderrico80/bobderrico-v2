<?php
/**
 * A class containing all logic for managing the skills taxonomy
 * 
 * @package bobderrico
 */

class Bobderrico_Skills {
  
  function __construct () {

    $this->register_hooks();
    
  }
  
  public function register_hooks() {
    
    add_action('init', [$this, 'register_skills_taxonomy']);
    add_action('add_meta_boxes', [$this, 'do_skills_meta_box']);
    add_filter('body_class', [$this, 'add_skills_list_class']);
    add_action('pre_get_posts', [$this, 'modify_skills_archive_query']);

  }

  public function add_skills_list_class($classes) {
    global $post;

    if ($post && $post->post_name === 'skills') {

      $classes[] = 'skills-list';
      return $classes;
    }
    return $classes;
  }

  public function render_skills_meta_box($post) {
    
    $all_terms = get_terms('skills', ['hide_empty' => 0]);
    $post_terms = get_the_terms($post->ID, 'skills');
    $name = 'tax_input[' . 'skills' . '][]';
    
    $post_term_ids = [];
    if ($post_terms) {
      foreach ($post_terms as $post_term) {
        $post_term_ids[] = $post_term->term_id;
      }
    }
    
    ?>
    
    <div id="taxonomy-<?= 'skills'; ?>" class="categorydiv">
      <input type="hidden" name="<?= $name ?>" value="0" />
      <ul>
        <?php 
        foreach ($all_terms as $term) :
          if (in_array($term->term_id, $post_term_ids)) {
            $checked = 'checked = ""';
          } else {
            $checked = '';
          }
          $id = 'skills' . '-' . $term->term_id;
          ?>
          <li id="<?= $id ?>">
            <label class="selectit">
              <input type="checkbox" name="<?= $name ?>" id="in-<?= $id ?>" <?= $checked ?> value="<?= $term->slug ?>" />
              <?= $term->name ?>
            </label>
          </li>
        <?php endforeach; ?>  
      </ul>
    </div> 
      
    <?php
    
  }
  
  public function do_skills_meta_box($post_type) {
    remove_meta_box('tagsdiv-skills', $post_type, 'normal');
    add_meta_box('tagsdiv-skills', 'Skills', [$this, 'render_skills_meta_box'], $post_type, 'side');
  }
  
  public function register_skills_taxonomy() {
    register_taxonomy('skills',
                      'post',
                      [
                        'label' => __('Skills')
                      ]);
    register_taxonomy_for_object_type('skills', 'project');
    register_taxonomy_for_object_type('skills', 'job');
  }

  public function render_skills_icons($post_id, $class='') {

    $skills = get_the_terms($post_id, 'skills');

    if (!$skills) {
      return false;
    }

    ?>
    <ul class="skills-icons <?= $class ?>">
      <?php foreach ($skills as $skill) : ?>
        <li>
          <a href="<?= get_term_link($skill) ?>" title="<?= $skill->name ?>">
            <?php $this->render_skills_icon($skill, true); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php

  }

  public function render_skills_icon($skill, $render_name = false) {
    ?>

      <?php if (substr($skill->description, 0, 4) === 'icon'): ?>
        <i class="<?= $skill->description ?>"></i>
      <?php elseif ($render_name): ?>
        <?= $skill->name ?>
      <?php endif; ?>

    <?php
  }

  public function modify_skills_archive_query($query) {

    if (!$query->is_tax('skills')) {
      return;
    }

    $query->set('post_type', ['post', 'project', 'job']);

  }
  
}