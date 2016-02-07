<?php
/**
 * The home template file.
 *
 * Used for the blog posts index page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bobderrico
 */
get_header(); ?>

  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
      <?php get_template_part('template-parts/hero', 'home'); ?>
      <?php if (have_posts()): ?>
        <?php if (!is_front_page()) : ?>
          <header>
            <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
          </header>
        <?php endif; ?>
        <div class="content-wrap">
          <?php while (have_posts()) : the_post();
            get_template_part('template-parts/content', get_post_format());
          endwhile;
          the_posts_navigation();
        else :
          get_template_part('template-parts/content', 'none');
        endif; ?>
      </div>
    </main><!-- #main -->
  </div><!-- #primary -->

<?php
get_footer();
