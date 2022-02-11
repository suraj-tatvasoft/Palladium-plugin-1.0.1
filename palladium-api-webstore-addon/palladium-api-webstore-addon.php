<?php

/**
 * The plugin main file
 *
 * This file includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://tjhokopaint.co.za/
 * @since             1.0.0
 * @package           Palladium_Api_Webstore_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       Palladium API integration with Webstore
 * Plugin URI:        https://tjhokopaint.co.za/
 * Description:       This is custom Woocoomerce addon for Palladium webstore API integration
 * Version:           1.0.1
 * Author:            Palladium Development team
 * Author URI:        https://tjhokopaint.co.za/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       palladium-api-webstore-addon
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Rename this for your plugin and update it as you release new versions.
 */
define('PALLADIUM_API_WEBSTORE_ADDON_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-palladium-api-webstore-addon-activator.php
 */
function activate_palladium_api_webstore_addon() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-palladium-api-webstore-addon-activator.php';
    Palladium_Api_Webstore_Addon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-palladium-api-webstore-addon-deactivator.php
 */
function deactivate_palladium_api_webstore_addon() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-palladium-api-webstore-addon-deactivator.php';
    Palladium_Api_Webstore_Addon_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_palladium_api_webstore_addon');
register_deactivation_hook(__FILE__, 'deactivate_palladium_api_webstore_addon');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-palladium-api-webstore-addon.php';

require_once plugin_dir_path(__FILE__) . 'admin/partials/palladium-api-webstore-admin-page.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_palladium_api_webstore_addon() {

    $plugin = new Palladium_Api_Webstore_Addon();
    $plugin->run();
}

run_palladium_api_webstore_addon();
