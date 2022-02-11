<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://tjhokopaint.co.za/
 * @since      1.0.0
 *
 * @package    Palladium_Api_Webstore_Addon
 * @subpackage Palladium_Api_Webstore_Addon/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Palladium_Api_Webstore_Addon
 * @subpackage Palladium_Api_Webstore_Addon/includes
 * @author     Palladium Development team <#>
 */
class Palladium_Api_Webstore_Addon_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'palladium-api-webstore-addon',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
