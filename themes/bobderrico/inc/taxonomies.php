<?php
/**
 * Custom taxonomies go here
 *
 * @package bobderrico
 */


function skills_meta_box($post) {

  $taxonomy = 'skills';

  $all_ctax_terms = get_terms($taxonomy, ['hide_empty' => 0]);
  $all_post_terms = get_the_terms($post->ID, $taxonomy);
  $name = 'tax_input[' . $taxonomy . '][]';

  $array_post_term_ids = [];
  if ($all_post_terms) {
    foreach ($all_post_terms as $post_term) {
      $post_term_id = $post_term->term_id;
      $array_post_term_ids[] = $post_term_id;
    }
  }

  ?>

  <div id="taxonomy-<?= $taxonomy; ?>" class="categorydiv">

    <input type="hidden" name="<?= $name; ?>" value="0"/>

    <ul>
      <?php foreach ($all_ctax_terms as $term) :
        if (in_array($term->term_id, $array_post_term_ids)) {
          $checked = "checked = ''";
        } else {
          $checked = "";
        }
        $id = $taxonomy . '-' . $term->term_id;
        ?>
      <li id="<?= $id ?>" >
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

function do_skills_meta_box($post_type) {
  remove_meta_box('tagsdiv-skills', $post_type, 'normal');
  add_meta_box('tagsdiv-skills', 'Skills', 'skills_meta_box', $post_type, 'side');
}

add_action('add_meta_boxes', 'do_skills_meta_box');

function skills_init() {
  register_taxonomy('skills',
                    'post',
                    [
                        'label' => __('Skills'),
                        'rewrite' => ['slug' => 'person']
                    ]);
}

add_action('init', 'skills_init');

