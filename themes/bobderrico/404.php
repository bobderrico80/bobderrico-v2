<?php
  /**
   * The template for displaying 404 pages (not found).
   *
   * @link https://codex.wordpress.org/Creating_an_Error_404_Page
   *
   * @package bobderrico
   */

  get_header(); ?>

  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

      <section class="error-404 not-found">
        <header class="page-header">
          <p><i class="fa fa-warning"></i></p>
          <h1 class="page-title"><?php esc_html_e('404 Error', 'bobderrico'); ?></h1>
        </header><!-- .page-header -->

        <div class="page-content">
          <p><?php esc_html_e('Sorry, there\'s nothing to see here. Maybe check the menu above or try searching?',
                              'bobderrico'); ?></p>

          <?php
            get_search_form();
          ?>

        </div><!-- .page-content -->
      </section><!-- .error-404 -->

    </main><!-- #main -->
  </div><!-- #primary -->

<?php
  get_footer();
