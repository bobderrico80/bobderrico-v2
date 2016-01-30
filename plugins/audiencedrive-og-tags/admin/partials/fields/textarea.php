<textarea class="<?= $args['class_name'] ?>"
          id="<?= $this->id ?>"
          name="<?= $this->options_key ?>[<?= $this->id ?>]"
          rows="<?= $args['rows'] ?>"
          cols="50"><?= get_option($this->options_key)[$this->id] ?></textarea>
<p class="description"><?= $args['help_text'] ?></p>
