<input type="text"
       class="<?= $args['class_name'] ?>"
       id="<?= $this->id ?>"
       name="<?= $this->options_key ?>[<?= $this->id ?>]"
       value="<?= get_option($this->options_key)[$this->id] ?>"/>
<p class="description"><?= $args['help_text'] ?></p>