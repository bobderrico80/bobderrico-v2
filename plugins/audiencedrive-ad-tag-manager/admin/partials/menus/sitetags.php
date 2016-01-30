<div class="wrap">
  <h1>Site Tags</h1>
  <form method="POST" action="options.php">
    <?php settings_fields('aatm_options'); ?>
    <?php do_settings_sections('sitetags'); ?>
    <?php submit_button(); ?>
  </form>

</div>
