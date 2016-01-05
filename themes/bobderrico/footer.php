<?php
  /**
   * The template for displaying the footer.
   *
   * Contains the closing of the #content div and all content after.
   *
   * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
   *
   * @package bobderrico
   */

?>

      </div><!-- #content -->
    </div><!-- .page-wrap -->

    <footer id="colophon" class="site-footer" role="contentinfo">
      <div class="site-info">
        Copyright &copy; <?= date('Y') ?> by Bob D'Errico
      </div><!-- .site-info -->
    </footer><!-- #colophon -->

    <?php wp_footer(); ?>

  </body>
</html>
