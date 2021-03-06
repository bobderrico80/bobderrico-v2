<?php
/**
 * The skills list template file. Shown on a page with the slug `skills`.
 *
 * Lists all items in the skills taxonomy
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bobderrico
 */

$skills = get_terms('skills', ['orderby' => 'count', 'order' => 'DESC']);

get_header(); ?>

  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
      <?php get_template_part('template-parts/hero', 'skills-list') ?>
      <div class="content-wrap">
      <?php

      if (count($skills)) :

        /* Start the Loop */
        foreach ($skills as $skill):

          require('template-parts/content-skills-page.php');

        endforeach;

      else :

        ?>
          <div class="no-skills-found">
            <?php __('No skills were found', 'bobderrico') ?>
          </div>

        <?php

      endif; ?>

        </div>
    </main><!-- #main -->
  </div><!-- #primary -->

<?php
get_footer();
