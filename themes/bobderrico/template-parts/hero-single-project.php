<?php
/**
 * The template for displaying the hero on the single post view
 *
 * @package bobderrico
 */

// Create loop with only the latest post
global $bobderrico;
global $bd_skills;

$comment_count = get_comments_number(get_the_ID());
$has_post_thumbnail = has_post_thumbnail();
if ($has_post_thumbnail) {
  $featured_image_src = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full')[0];
} else {
  $featured_image_src = '';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="hero-container">
    <div class="hero-blank-bg"></div>
    <?php if ($has_post_thumbnail) : ?>
    <div class="hero-img-bg" style="background-image: url('<?= $featured_image_src ?>')"></div>
    <?php endif; ?>
      <header class="hero-content">
        <h1 class="hero-title">
          <?= get_the_title() ?>
        </h1>
        <?php $bobderrico->render_post_time('hero-date'); ?>
        <?php $bd_skills->render_skills_icons(get_the_ID(), 'hero-skills'); ?>
        <?php if ($comment_count): ?>
          <div class="hero-comment-count">
            <a href="#comments">
              <i class="fa fa-comment"></i> <?= $comment_count ?>
            </a>
          </div>
        <?php endif; ?>
        <div class="clearfix"></div>
      </header>
  </div>
