<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://gabrielcastillo.net
 * @since      1.0.0
 *
 * @package    Max_Sher_Snippets
 * @subpackage Max_Sher_Snippets/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Max_Sher_Snippets
 * @subpackage Max_Sher_Snippets/includes
 * @author     Gabriel Castillo <gabriel@stellervision.com>
 */
class Max_Sher_Snippets_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'max-sher-snippets',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
