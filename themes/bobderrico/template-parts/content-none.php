<?php
  /**
   * Template part for displaying a message that posts cannot be found.
   *
   * @link https://codex.wordpress.org/Template_Hierarchy
   *
   * @package bobderrico
   */

?>

<section class="no-results not-found">
  <header class="page-header">
    <p><i class="fa fa-meh-o"></i></p>
    <h1 class="page-title"><?php esc_html_e('Nothing Found', 'bobderrico'); ?></h1>
  </header><!-- .page-header -->

  <div class="page-content">
    <?php
      if (is_home() && current_user_can('publish_posts')) : ?>

        <p><?php printf(wp_kses(__('Ready to publish your first post? <a href="%1$s">Get started here</a>.',
                                   'bobderrico'), array('a' => array('href' => array()))),
                        esc_url(admin_url('post-new.php'))); ?></p>

      <?php elseif (is_search()) : ?>

        <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.',
                            'bobderrico'); ?></p>
        <?php
        get_search_form();

      else : ?>

        <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.',
                            'bobderrico'); ?></p>
        <?php
        get_search_form();

      endif; ?>
  </div><!-- .page-content -->
</section><!-- .no-results -->
