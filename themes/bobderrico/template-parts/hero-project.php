<?php
/**
 * The template for displaying the hero on the home (blog) page
 *
 * @package bobderrico
 */

// Create loop with only the latest post
$latest = new WP_Query(['post_type' => 'project',
                        'post_status' => 'publish',
                        'posts_per_page' => '1',
                        'no_paging' => true,
                        'order' => 'DESC'
                       ]);

global $bobderrico;
global $bd_skills;

if ($latest->have_posts()) : while($latest->have_posts()) : $latest->the_post();

  $the_id = get_the_ID();

  $has_post_thumbnail = has_post_thumbnail();
  if ($has_post_thumbnail) {
    $featured_image_src = wp_get_attachment_image_src(get_post_thumbnail_id($the_id), 'full')[0];
  } else {
    $featured_image_src = '';
  }

  $project_urls = $bobderrico->get_project_urls($the_id);
?>

<div class="hero-container">
  <div class="hero-blank-bg"></div>
  <?php if ($has_post_thumbnail) : ?>
  <div class="hero-img-bg" style="background-image: url('<?= $featured_image_src ?>')"></div>
  <?php endif; ?>
  <header class="hero-content">
    <h1 class="hero-title">
        Featured Project: <br class="mobile-br-only"><?= get_the_title() ?>
    </h1>
    <?php $bobderrico->render_project_links($the_id) ?>
    <p class="hero-excerpt"><?= get_the_excerpt() ?></p>
    <a href="<?= get_the_permalink() ?>" class="btn-link btn-hero-more">
      Read More
    </a>
    <div class="hero-meta">
      <?php $bd_skills->render_skills_icons(get_the_ID(), 'hero-skills'); ?>
    </div>
  </header>
</div>
<?php endwhile; endif; wp_reset_postdata(); ?>
