<?php
/**
 *
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bobderrico
 */

global $bobderrico;
global $bd_skills;
?>

<a class="skill-link" href="<?= get_term_link($skill) ?>">
  <div class="skill-block" id="skill-<?= $skill->term_id ?>">
    <div class="skill-icon-container">
      <?php $bd_skills->render_skills_icon($skill) ?>
    </div>
    <h2 class="skill-title">
      <?= $skill->name ?><span class="skill-count"><?= $skill->count ?></span>
    </h2>
  </div>
</a>
