<label>
  <input
    type="checkbox"
    id="<?= $this->ID ?>"
    name="<?= $this->options_key ?>[<?= $this->id ?>]"
    value="1"
    <?= checked(get_option($this->options_key)[$this->id], "1", false) ?>
  />
  <?= $args['cb_text'] ?>
</label>