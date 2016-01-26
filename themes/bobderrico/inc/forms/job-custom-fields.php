<table class="form-table">
  <tbody>
    <tr>
      <th scope="row">
        <label for="title">
          <?= __('Job Title', 'bobderrico'); ?>
        </label>
      </th>
      <td>
        <input class="regular-text" type="text" id="title" name="values[title]" value="<?= esc_attr($values['title']) ?>" />
      </td>
    </tr>
    <tr>
      <th scope="row">
        <label for="location">
          <?= __('Location', 'bobderrico'); ?>
        </label>
      </th>
      <td>
        <input class="regular-text" type="text" id="location" name="values[location]" value="<?= esc_attr($values['location']) ?>" />
      </td>
    </tr>
    <tr>
      <th scope="row">
        <label for="start_date">
          <?= __('Start Date', 'bobderrico'); ?>
        </label>
      </th>
      <td>
        <input class="regular-text" type="date" id="start_date" name="values[start_date]" value="<?= esc_attr($values['start_date']) ?>" />
      </td>
    </tr>
    <tr>
      <th scope="row">
        <label for="end_date">
          <?= __('End Date', 'bobderrico'); ?>
        </label>
      </th>
      <td>
        <input class="regular-text" type="date" id="end_date" name="values[end_date]" value="<?= esc_attr($values['end_date']) ?>" />
      </td>
    </tr>
  </tbody>
</table>

