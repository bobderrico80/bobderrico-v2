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
$the_id = get_the_ID();
$permalink = esc_url(get_permalink());
$title = get_the_title();
$featured_image_info = $bobderrico->get_featured_image_info($the_id);
$project_urls = $bobderrico->get_project_urls($the_id);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <header class="entry-header">
    <h2 class="entry-title">
      <a href="<?= $permalink ?>" rel="bookmark">
        <?= $title ?>
      </a>
    </h2>
    <div class="entry-meta">
      <?php $bobderrico->render_job_info($the_id) ?>
    </div><!-- .entry - meta-->
  </header><!-- .entry-header -->
  <div class="entry-content">
    <?php
    $read_more_text = esc_html__('Read More', 'bobderrico');
    $continue = $read_more_text . '<span class="screen-reader-text">' . $title . '</span>';
    $continue .= '<span class="meta-nav">&rarr;</span>';

    the_content($continue);

    ?>
  </div><!-- .entry-content -->

  <footer class="entry-footer">
    <?php $bd_skills->render_skills_icons($the_id, 'home-skills'); ?>
  </footer><!-- .entry-footer -->
</article><!-- #post-## -->
