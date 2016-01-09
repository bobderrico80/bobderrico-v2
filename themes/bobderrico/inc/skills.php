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
    
  }
  
  public function render_skills_meta_box($post) {
    
    $all_terms = get_terms('skills', ['hide_empty' => 0]);
    $post_terms = get_the_terms($post->ID, 'skills');
    $name = 'tax_input[' . 'skills' . ']{}';
    
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
    add_meta_box('tagsdiv-skills', 'Skills', 'skills_meta_box', $post_type, 'side');
  }
  
  public function register_skills_taxonomy() {
    register_taxonomy('skills',
                      'post',
                      [
                        'label' => __('Skills')
                      ]);
  }
  
}