<?php
/**
 * The template for displaying the hero on the skill view
 *
 * @package bobderrico
 */

// Create loop with only the latest post
global $bobderrico;
global $bd_skills;
global $wp_query;

$skill = get_term_by('slug', get_query_var('term'), 'skills');

?>

  <div class="hero-container">
    <div class="hero-blank-bg"></div>
    <header class="hero-content">
      <div class="skill-header">
        <?php $bd_skills->render_skills_icon($skill) ?>
        <span class="skill-title"><?= $skill->name ?></span>
      </div>
    </header>
  </div>
