<?php
/**
 * The template for displaying the hero on the home (blog) page
 *
 * @package bobderrico
 */

// Create loop with only the latest post
$latest = new WP_Query(['post_type' => 'post',
                        'post_status' => 'publish',
                        'posts_per_page' => '1',
                        'no_paging' => true,
                        'order' => 'DESC'
                       ]);

if ($latest->have_posts()) : while($latest->have_posts()) : $latest->the_post();

  $has_post_thumbnail = has_post_thumbnail();
  if ($has_post_thumbnail) {
    $featured_image_src = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full')[0];
  } else {
    $featured_image_src = '';
  }
?>

<a class="hero-link" href="<?= get_the_permalink() ?>">
  <div class="hero-container">
    <div class="hero-blank-bg"></div>
    <?php if ($has_post_thumbnail) : ?>
    <div class="hero-img-bg" style="background-image: url('<?= $featured_image_src ?>')"></div>
    <?php endif; ?>
      <header class="hero-content">
        <h1 class="hero-title">
            <?= get_the_title() ?>
        </h1>
        <p class="hero-excerpt"><?= get_the_excerpt() ?></p>
      </header>
  </div>
</a>
<?php endwhile; endif; wp_reset_postdata(); ?>
