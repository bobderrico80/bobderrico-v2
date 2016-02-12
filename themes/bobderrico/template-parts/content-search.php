<?php
  /**
   * Template part for displaying results in search pages.
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
$comment_count = get_comments_number($the_id);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <header class="entry-header">
    <h2 class="entry-title">
      <a href="<?= $permalink ?>" rel="bookmark">
        <?= $title ?>
      </a>
    </h2>
    <?php $bobderrico->render_post_type_text($post); ?>
    <div class="entry-meta">
      <?php if ($post->post_type === 'post'): ?>
        <?php $bobderrico->render_post_time(); ?>
      <?php elseif ($post->post_type === 'project'): ?>
        <?php $bobderrico->render_project_links($the_id) ?>
      <?php elseif ($post->post_type === 'job'): ?>
        <?php $bobderrico->render_job_info($the_id) ?>
      <?php endif; ?>
    </div><!-- .entry - meta-->
  </header><!-- .entry-header -->
  <div class="entry-main">
    <?php $bobderrico->render_featured_image($the_id, 'square-mb') ?>
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
    <?php if ($comment_count): ?>
      <div class="entry-comment-count">
        <a href="<?= $permalink ?>#comments">
          <i class="fa fa-comment"></i> <?= $comment_count ?>
        </a>
      </div>
    <?php endif; ?>
    <div class="clearfix"></div>
  </footer><!-- .entry-footer -->
</article><!-- #post-## -->

