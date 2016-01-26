<table class="form-table">
  <tbody>
    <tr>
      <th scope="row">
        <label for="project_url">
          <?= __('Project URL', 'bobderrico'); ?>
        </label>
      </th>
      <td>
        <input class="regular-text" type="url" id="project_url" name="values[project_url]" value="<?= esc_attr($values['project_url']) ?>" />
      </td>
    </tr>
    <tr>
      <th scope="row">
        <label for="github_url">
          <?= __('GitHub URL', 'bobderrico'); ?>
        </label>
      </th>
      <td>
        <input class="regular-text" type="url" id="github_url" name="values[github_url]" value="<?= esc_attr($values['github_url']) ?>" />
      </td>
    </tr>
  </tbody>
</table>

