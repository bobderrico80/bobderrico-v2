<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           AATM
 *
 * @wordpress-plugin
 * Plugin Name:       AudienceDrive Ad Tag Manager
 * Plugin URI:        http://audiencedrive.com/
 * Description:       An easy way to manage all of your ad tags
 * Version:           1.0.0
 * Author:            AudienceDrive
 * Author URI:        http://audiencedrive.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       aatm
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-aatm-activator.php
 */
function activate_aatm() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aatm-activator.php';
	AATM_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-aatm-deactivator.php
 */
function deactivate_aatm() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aatm-deactivator.php';
	AATM_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_aatm' );
register_deactivation_hook( __FILE__, 'deactivate_aatm' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-aatm.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_aatm() {

	$plugin = new AATM();
	$plugin->run();

}
run_aatm();
