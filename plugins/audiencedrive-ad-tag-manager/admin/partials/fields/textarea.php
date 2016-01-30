<textarea class="<?= $args['class_name'] ?>"
          id="<?= $this->id ?>"
          name="aatm_options[<?= $this->id ?>]"
          rows="<?= $args['rows'] ?>"
          cols="50"><?= get_option('aatm_options')[$this->id] ?></textarea>
<p class="description"><?= $args['help_text'] ?></p>
