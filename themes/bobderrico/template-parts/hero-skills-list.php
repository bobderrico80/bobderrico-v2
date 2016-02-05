<?php
/**
 * The template for displaying the hero on the single post view
 *
 * @package bobderrico
 */

?>

<div class="hero-container">
  <div class="hero-blank-bg"></div>
    <header class="hero-content">
      <div class="hero-content-wrap">
        <h2>
          <?php
          echo wp_is_mobile() ? __('Tap', 'bobderrico') : __('Click', 'bobderrico');
          echo ' ';
          echo __('a skill to view relevant projects, work experience, and blog posts.', 'bobderrico');
          ?>
        </h2>
      </div>
    </header>
</div>
