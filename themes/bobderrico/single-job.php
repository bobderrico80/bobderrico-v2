<?php
/**
 * The template for displaying all single projects.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package bobderrico
 */

get_header();


?>

  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

      <?php
      while (have_posts()) : the_post();

        get_template_part('template-parts/hero', 'single-job');
        ?>
      <div class="content-wrap">
        <?php
        get_template_part('template-parts/content', 'single-job');

        the_post_navigation([
          'prev_text' => '&leftarrow; ' . __('Previous Job', 'bobderrico'),
          'next_text' => __('Next Job', 'bobderrico;') . ' &rightarrow;']);

        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) :
          comments_template();
        endif;

      endwhile; // End of the loop.
      ?>

      </div>
    </main><!-- #main -->
  </div><!-- #primary -->

<?php
get_footer();
