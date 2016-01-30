<table class="form-table">
  <tbody>
    <tr>
      <th scope="row">
        <label for="post_title_override">Post Title Override</label>
      </th>
      <td>
        <input type="text" id="post_title_override" name="post_title_override" class="large-text" value="<?= $title_override ?>" />
      </td>
    </tr>
    <tr>
      <th>
        <label for="post_description_override">Post Description Override</label>
      </th>
      <td>
        <textarea class="large-text" id="post_description_override" name="post_description_override" rows="10" cols="50"><?= $description_override ?></textarea>
      </td>
    </tr>
  </tbody>
</table>
