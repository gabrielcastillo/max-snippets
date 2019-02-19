<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://gabrielcastillo.net
 * @since             1.0.0
 * @package           Max_Sher_Snippets
 *
 * @wordpress-plugin
 * Plugin Name:       Max Sher Snippets
 * Plugin URI:        https://gabrielcastillo.net/
 * Description:       Create and display snippets within a widget or shortcode. Shortcode: "[max_sher_snippets]"
 * Version:           1.0.0
 * Author:            Gabriel Castillo
 * Author URI:        https://gabrielcastillo.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       max-sher-snippets
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-max-sher-snippets-activator.php
 */
function activate_max_sher_snippets() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-max-sher-snippets-activator.php';
	Max_Sher_Snippets_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-max-sher-snippets-deactivator.php
 */
function deactivate_max_sher_snippets() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-max-sher-snippets-deactivator.php';
	Max_Sher_Snippets_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_max_sher_snippets' );
register_deactivation_hook( __FILE__, 'deactivate_max_sher_snippets' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-max-sher-snippets.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_max_sher_snippets() {

	$plugin = new Max_Sher_Snippets();
	$plugin->run();

}
run_max_sher_snippets();
