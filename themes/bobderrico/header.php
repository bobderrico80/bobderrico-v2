<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?= get_bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?= get_bloginfo('pingback_url'); ?>">

    <?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?>>
    <div id="page" class="site">
      <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', '_s'); ?></a>
      <div class="page-wrap">
        <header id="masthead" class="site-header" role="banner">
          <nav id="site-navigation" class="main-navigation" role="navigation">
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
              <i class="fa fa-bars"></i>
            </button>
            <div class="site-branding">
              <h1 class="site-title">
                <a href="<?= esc_url(home_url('/')); ?>" rel="home">
                  <?= get_bloginfo('name'); ?>
                </a>
              </h1>
              <p class="site-description"><?= get_bloginfo('description', 'display') ?></p>
            </div><!-- .site-branding -->
            <div class="nav-menu" aria-expanded="false">
              <?php wp_nav_menu(array('theme_location' => 'primary', 'menu_id' => 'primary-menu')); ?>
              <ul class="social-buttons">
                <li>
                  <a href="https://twitter.com/bobderrico80" target="_blank">
                    <i class="fa fa-twitter"></i>
                  </a>
                </li>
                <li>
                  <a href="https://www.linkedin.com/in/bobderrico" target="_blank">
                    <i class="fa fa-linkedin"></i>
                  </a>
                </li>
                <li>
                  <a href="https://github.com/bobderrico80" target="_blank">
                    <i class="fa fa-github"></i>
                  </a>
                </li>
                <li>
                  <a href="https://www.instagram.com/drfinale/" target="_blank">
                    <i class="fa fa-instagram"></i>
                  </a>
                </li>
                <li>
                  <a href="https://www.facebook.com/robert.derrico" target="_blank">
                    <i class="fa fa-facebook-official"></i>
                  </a>
                </li>
              </ul>
            </div>
          </nav><!-- #site-navigation -->
        </header><!-- #masthead -->
        <div id="content" class="site-content">