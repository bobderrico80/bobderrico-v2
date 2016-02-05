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
        <div class="hero-content-wrap">
          <h1 class="hero-title">
            <?= get_the_title() ?>
          </h1>
        </div>
      </header>
  </div>
