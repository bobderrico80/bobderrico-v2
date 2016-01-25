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
      <div class="skills-list-description">
        <?php
          echo wp_is_mobile() ? __('Tap', 'bobderrico') : __('Click', 'bobderrico');
          echo ' ';
          echo __('a skill to view relevant projects, work experience, and blog posts.', 'bobderrico');
        ?>
      </div>
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

    </main><!-- #main -->
  </div><!-- #primary -->

<?php
get_footer();
