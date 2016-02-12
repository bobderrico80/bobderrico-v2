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
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <header class="entry-header">
    <h2 class="entry-title">
      <a href="<?= $permalink ?>" rel="bookmark">
        <?= $title ?>
      </a>
    </h2>
    <div class="entry-meta">
      <?php $bobderrico->render_post_time(); ?>
    </div><!-- .entry - meta-->
  </header><!-- .entry-header -->
  <div class="entry-main">
    <?php $bobderrico->render_featured_image($the_id, 'square-xs') ?>
    <div class="entry-content">
      <?php
      $read_more_text = esc_html__('Read More', 'bobderrico');
      $continue = $read_more_text . '<span class="screen-reader-text">' . $title . '</span>';
      $continue .= '<span class="meta-nav">&rarr;</span>';
      the_content($continue);
      ?>
    </div><!-- .entry-content -->
  </div>
  <footer class="entry-footer">
    <?php $bd_skills->render_skills_icons(get_the_ID(), 'home-skills'); ?>
    <?php $bobderrico->render_comment_count(get_the_ID()) ?>
  </footer><!-- .entry-footer -->
</article><!-- #post-## -->
