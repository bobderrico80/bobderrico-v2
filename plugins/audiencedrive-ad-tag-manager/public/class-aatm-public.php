<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    AATM
 * @subpackage AATM/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    AATM
 * @subpackage AATM/public
 * @author     Your Name <email@example.com>
 */
class AATM_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $aatm    The ID of this plugin.
	 */
	private $aatm;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * @var  array  An array of ad tag objects retrieved from the db
	 */
	private $ad_tags = [];

	/**
	 * @var  bool True if the user agent is mobile
	 */
	private $is_mobile = false;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $aatm       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $aatm, $version ) {

		$this->aatm = $aatm;
		$this->version = $version;

	}

	public function get_ad_tags() {

		$aatm_query = new WP_Query([
			'post_type' => 'aatm_ad_tag',
			'nopaging' => true
		]);

		foreach ($aatm_query->posts as $post) {
			$ad_tag = new AATM_Ad_Tag($post);
			$this->ad_tags[$ad_tag->slug] = $ad_tag;
		}

	}

	public function set_is_mobile() {

    $this->is_mobile = wp_is_mobile();

	}

	/**
	 *
	 * @since    1.0.0
	 */
	public function render_header_code() {

    echo $this->wrap_site_code(get_option('aatm_options')['header_code'], 'header');
		echo $this->wrap_site_code($this->get_dynamic_dfp_code(), 'dfp dynamic header');

	}

  private function get_dynamic_dfp_code() {

    $dfp_base = get_option('aatm_options')['dfp_base'];
    $dfp_functions = $this->get_dfp_functions();

		return str_replace('//placements', $dfp_functions, $dfp_base);

  }

  private function get_dfp_functions() {

		// get the DFP ad tags
		$dfp_ad_tags = [];
		foreach ($this->ad_tags as $ad_tag) {
			if ($ad_tag->is_dfp) {
				$dfp_ad_tags[] = $ad_tag;
			}
		}

		$functions = '';

		// get all page types associated with this page
		$page_types = $this->get_page_types();

		foreach ($dfp_ad_tags as $ad_tag) {

			// check desktop/mobile
			if (($this->is_mobile && $ad_tag->is_mobile) || (!$this->is_mobile && $ad_tag->is_desktop)) {
				if (!$ad_tag->dfp_defineslot_args) {
					continue;
				}

				// if no locations specified, run everywhere
				if (!$ad_tag->dfp_locations) {
					$functions .= $this->get_dfp_function($ad_tag->dfp_defineslot_args);
					continue;
				}

				$includes = $ad_tag->dfp_locations['includes'];
				$excludes = $ad_tag->dfp_locations['excludes'];

				// check page types against excludes list
				$allowed = true;
				foreach ($excludes as $exclude) {
					if (in_array($exclude, $page_types)) {
						$allowed = false;
						break;
					}
				}

				if (!$allowed) {
					continue;
				}

				// check page types against includes list
				foreach ($includes as $include) {
					if (in_array($include, $page_types)) {
						$functions .= $this->get_dfp_function($ad_tag->dfp_defineslot_args);
						break;
					}
				}
			}
		}

		return $functions;

  }

  private function get_dfp_function($args) {

    return 'googletag.defineSlot(' . $args . ').addService(googletag.pubads());' . PHP_EOL;

  }

	private function get_page_types() {

		$page_types = $this->get_wp_page_types();
		$page_types = $this->get_td_templates($page_types);
		$page_types = $this->get_current_page_number($page_types);

		return $page_types;

	}

	private function get_wp_page_types() {

		$all_types = ['home', 'front_page', 'admin', 'single', 'sticky', 'post_type_archive', 'page', 'page_template', 'category',
			            'tag', 'tax', 'author', 'date', 'year', 'month', 'day', 'time', 'archive', 'search', '404', 'paged',
			            'attachment', 'singular'];

		$matching_types = [];

		foreach ($all_types as $type) {
			$is_page_types = 'is_' . $type;
			if ($is_page_types()) {
				$matching_types[] = $type;
			}
		}

		return $matching_types;

	}

	private function get_td_templates($array = []) {

		global $wp_query;

		$post = $wp_query->posts[0];
		$td_meta = get_post_meta($post->ID, 'td_post_theme_settings', true);

		if (!$td_meta) {
			return $array;
		}

		foreach ($td_meta as $templates) {
			$array[] = $templates;
		}

		return $array;

	}

	private function get_current_page_number($array = false) {

		$page = get_query_var('page', false);
		$paged = get_query_var('paged', false);

		if (!$page && !$paged) {
			$page_no = 1;
		} else {
			$page_no = intval($page) + intval($paged);
		}

		if (is_array($array)) {
			$array[] = 'page' . $page_no;
			return $array;
		}

		return 'page' . $page_no;

	}

	/**
	 *
	 * @since    1.0.0
	 */
	public function render_footer_code() {

		echo $this->wrap_site_code(get_option('aatm_options')['footer_code'], 'footer');

	}

	private function wrap_site_code($code, $section) {

		$pre = '<!-- AudienceDrive Ad Tag Plugin ' . $section . ' code -->' . PHP_EOL;
		$code = $code . PHP_EOL;
		$post = '<!-- End AudienceDrive Ad Tag Plugin code -->' . PHP_EOL;

		return $pre . $code . $post;
	}

	public function render_placement($atts) {

		$code = '';

		if (!isset($this->ad_tags[$atts['id']])) {
			return $code;
		}

		$ad_tag = $this->ad_tags[$atts['id']];

		if (!$ad_tag->code) {
			return $code;
		}

		if (($this->is_mobile && $ad_tag->is_mobile) || (!$this->is_mobile && $ad_tag->is_desktop)) {
			$code = '<div ' . $this->get_css_class($ad_tag, 'placement') . '>' . PHP_EOL;

			if ($ad_tag->show_disclaimer) {
				$code = $this->render_disclaimer($code, $ad_tag);
			}

			$code .= $ad_tag->code . PHP_EOL;
			$code .= '</div>' . PHP_EOL;

			// removing this for now since it breaks formatting in td widgets due to the comment line being wrapped in a <p> tag
			// TODO make this a setting on the ad tag to toggle on/off for specific placements
			// $code = $this->wrap_placement($code, $ad_tag);

		}

		return $code;

	}

	private function render_disclaimer($code, $ad_tag) {

		$disclaimer = '<div ' . $this->get_css_class($ad_tag, 'disclaimer') . '>Advertisement</div>' . PHP_EOL;

		return $code . PHP_EOL . $disclaimer;

	}

	private function wrap_placement($code, $ad_tag) {

		$pre = '<!-- AATM Placement - ' . $ad_tag->placement . ' -->' . PHP_EOL;
		$code = $code . PHP_EOL;
		$post = '<!-- End AATM Placement -->' . PHP_EOL;

		return $pre . $code . $post;

	}

	private function get_css_class($ad_tag, $base_class) {

		$class = 'class="aatm-' . $base_class . ' ';

		if ($ad_tag->is_mobile) {
			$class .= 'aatm-mobile ';
		}

		if ($ad_tag->is_desktop) {
			$class .= 'aatm-desktop ';
		}

		$class .= 'aatm-' . $ad_tag->placement . '"';

		return $class;

	}

}
