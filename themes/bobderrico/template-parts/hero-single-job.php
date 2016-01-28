<?php
/**
 * The template for displaying the hero on the single job view
 *
 * @package bobderrico
 */

global $bobderrico;
global $bd_skills;
global $wp_query;

$skill = get_term_by('slug', get_query_var('term'), 'skills');

?>

  <div class="hero-container">
    <div class="hero-blank-bg"></div>
    <header class="hero-content">
      <div class="job-header">
        <h1 class="entry-title"><?= get_the_title() ?></h1>
        <div class="entry-meta">
          <?php $bobderrico->render_job_info(get_the_ID()) ?>
          <?php $bd_skills->render_skills_icons(get_the_ID()) ?>
        </div>
      </div>
    </header>
  </div>
