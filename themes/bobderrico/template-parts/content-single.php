<?php
/**
 *
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bobderrico
 */

global $bobderrico;
$title = get_the_title();
?>
  <div class="entry-content">
    <?php the_content(); ?>
  </div><!-- .entry-content -->
  <div class="entry-footer">
    <div class="tag-list">
      <?= get_the_tag_list(__('Tagged as: ', 'bobderrico'), ', ') ?>
    </div>
  </div>
</article><!-- #post-## -->
