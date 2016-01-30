<?php
/**
 * A class for creating ad tag objects
 */

class AATM_Ad_Tag {

  public $placement;
  public $slug;
  public $code;
  public $is_desktop;
  public $is_mobile;
  public $show_disclaimer;
  public $is_dfp;
  public $dfp_defineslot_args;
  public $dfp_locations;

  function __construct($ad_tag_post) {

    if ($ad_tag_post->post_type !== 'aatm_ad_tag') {
      throw new InvalidArgumentException('$ad_tag_post_obj must have a post_type of aatm_ad_tag');
    }

    $this->placement = $ad_tag_post->post_title;
    $this->slug = $ad_tag_post->post_name;

    $post_meta = get_post_meta($ad_tag_post->ID);

    $this->code = $this->get_default_meta($post_meta, '_aatm_ad_tag_code');
    $this->is_desktop = $this->get_default_meta($post_meta, '_aatm_is_desktop', true);
    $this->is_mobile = $this->get_default_meta($post_meta, '_aatm_is_mobile', true);
    $this->show_disclaimer = $this->get_default_meta($post_meta, '_aatm_show_disclaimer', true);
    $this->is_dfp = $this->get_default_meta($post_meta, '_aatm_is_dfp', true);
    $this->dfp_defineslot_args = $this->get_default_meta($post_meta, '_aatm_dfp_defineslot_args');
    $this->dfp_locations = $this->get_dfp_locations_array($this->get_default_meta($post_meta, '_aatm_dfp_locations'));

  }

  /**
   * Returns a value given post_meta array & a meta key, or a default value if the key doesn't exist or is falsy
   *
   * @param   array   $post_meta  The post_meta array (result of get_post_meta($meta_key))
   * @param   string  $meta_key   The key to look up.
   * @param   bool    $boolean    Whether to return true if the value is truthy (optional, default: false);
   * @param   bool    $default    The default value to return if value is falsy (optional, default: false);
   * @return  mixed   The meta value or default value
   */
  private function get_default_meta ($post_meta, $meta_key, $boolean=false, $default=false) {

    if (isset($post_meta[$meta_key]) && $post_meta[$meta_key][0]) {
      if ($boolean) {
        return true;
      }

      return $post_meta[$meta_key][0];
    }

    return $default;

  }

  /**
   * Gets a multi-dimensional array of DFP locations for this placement. Inner arrays are an 'includes' array of
   * locations to run the DFP header code on, and 'excludes' array of location not to.
   *
   * @param   string      $dfp_locations  the value of the post_meta key _aatm_dfp_locations
   * @return  array|bool  The multidimensional locations array, or false if there are no locations specified
   */
  private function get_dfp_locations_array($dfp_locations) {

    if (!$dfp_locations) {
      return false;
    }

    $tokens = explode(',', $dfp_locations);
    $dfp_locations_array = [];
    $includes = [];
    $excludes = [];

    foreach ($tokens as $i => $token) {
      $token = trim($token);

      if (substr($token, 0, 1) === '!') {
        $token = trim($token, '!');
        $excludes[] = $token;
      } else {
        $includes[] = $token;
      }
    }

    $dfp_locations_array['includes'] = $includes;
    $dfp_locations_array['excludes'] = $excludes;

    return $dfp_locations_array;

  }


}