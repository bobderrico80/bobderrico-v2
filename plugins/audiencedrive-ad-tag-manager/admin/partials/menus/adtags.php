<?php

  if(!empty($_POST)) {
    if (empty($_POST['placement_name'])) {
      $error = 'Please provide a name for your placement';
      $ad_tag = $_POST['ad_tag'];
    } else {
      $ad_tag = [
        'name' => $_POST['placement_name'],
        'tag' => $_POST['ad_tag']
      ];
      $aatm_ad_tags = get_option('aatm_ad_tags');
      $aatm_ad_tags[] = $ad_tag;
      update_option('aatm_ad_tags', $aatm_ad_tags);
    }
  }

?>

<div class="wrap">
  <?php if (isset($error)) : ?>
    <div class="error"><?= $error ?></div>
  <?php endif ?>
  <h1>Ad Tags</h1>
  <form method="POST">
    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row">
            <label for="new_ad_tag_name">Placement Name</label>
          </th>
          <td>
            <input type="text" class="regular-text" id="placement_name" name="placement_name" />
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="new_ad_tag_body">Ad Tag</label>
          </th>
          <td>
            <textarea class="large-text code" id="ad_tag" name="ad_tag" rows="10" cols="50"><?php if (isset($error)) {echo $_POST['ad_tag'];} ?></textarea>
          </td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <input type="submit" name="submit" class="button button-primary" value="Save new ad tag" />
    </p>
  </form>
</div>

<?php
$adTagListTable = new AATM_List_Ad_tags();