<?php
wp_nonce_field('bobderrico_save_project_info', 'bobderrico_project_info_nonce');
$project_url = get_post_meta($post->ID, '_bd_project_url', true);
$github_url = get_post_meta($post->ID, '_bd_github_url', true);
?>

<table class="form-table">
  <tbody>
    <tr>
      <th scope="row">
        <label for="project_url">
          <?= __('Project URL', 'bobderrico'); ?>
        </label>
      </th>
      <td>
        <input class="regular-text" type="url" id="project_url" name="project_url" value="<?= esc_attr($project_url) ?>" />
      </td>
    </tr>
    <tr>
      <th scope="row">
        <label for="github_url">
          <?= __('GitHub URL', 'bobderrico'); ?>
        </label>
      </th>
      <td>
        <input class="regular-text" type="url" id="github_url" name="github_url" value="<?= esc_attr($github_url) ?>" />
      </td>
    </tr>
  </tbody>
</table>

