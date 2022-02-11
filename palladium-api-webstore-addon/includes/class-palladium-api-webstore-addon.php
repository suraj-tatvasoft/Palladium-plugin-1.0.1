<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://tjhokopaint.co.za/
 * @since      1.0.0
 *
 * @package    Palladium_Api_Webstore_Addon
 * @subpackage Palladium_Api_Webstore_Addon/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Palladium_Api_Webstore_Addon
 * @subpackage Palladium_Api_Webstore_Addon/includes
 * @author     Palladium Development team <#>
 */
class Palladium_Api_Webstore_Addon {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Palladium_Api_Webstore_Addon_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
	if (defined('PALLADIUM_API_WEBSTORE_ADDON_VERSION')) {
	    $this->version = PALLADIUM_API_WEBSTORE_ADDON_VERSION;
	} else {
	    $this->version = '1.0.0';
	}
	$this->plugin_name = 'palladium-api-webstore-addon';

	$this->load_dependencies();
	$this->set_locale();
	$this->define_admin_hooks();
    $this->define_public_hooks();
    // add_action( 'manage_edit-shop_order_columns', array( $this, 'custom_shop_order_column_sync_to_palladium' ) );
    // add_action( 'manage_shop_order_posts_custom_column', array( $this, 'custom_order_sync_palladium' ) );
    // add_action( 'pld_every_hour_product_price_cron',array($this, 'import_customer_prices_from_api_test')  );
    // add_action( 'manage_shop_order_posts_custom_column', array( $this, 'custom_order_sync_palladium' ) );
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Palladium_Api_Webstore_Addon_Loader. Orchestrates the hooks of the plugin.
     * - Palladium_Api_Webstore_Addon_i18n. Defines internationalization functionality.
     * - Palladium_Api_Webstore_Addon_Admin. Defines all hooks for the admin area.
     * - Palladium_Api_Webstore_Addon_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

	/**
	 * The class responsible for orchestrating the actions and filters of the
	 * core plugin.
	 */
	require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-palladium-api-webstore-addon-loader.php';

	/**
	 * The class responsible for defining internationalization functionality
	 * of the plugin.
	 */
	require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-palladium-api-webstore-addon-i18n.php';

	/**
	 * The class responsible for defining all actions that occur in the admin area.
	 */
	require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-palladium-api-webstore-addon-admin.php';

	/**
	 * The class responsible for defining all actions that occur in the public-facing
	 * side of the site.
	 */
	require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-palladium-api-webstore-addon-public.php';

	$this->loader = new Palladium_Api_Webstore_Addon_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Palladium_Api_Webstore_Addon_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

	$plugin_i18n = new Palladium_Api_Webstore_Addon_i18n();

	$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

	$plugin_admin = new Palladium_Api_Webstore_Addon_Admin($this->get_plugin_name(), $this->get_version());

	$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
	$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
	$this->loader->add_action('admin_menu', $plugin_admin, 'pld_add_admin_settings_page');
	$this->loader->add_action('admin_init', $plugin_admin, 'pld_admin_register_settings');
	$this->loader->add_action('admin_notices', $plugin_admin, 'pld_admin_notice_permission');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

	$plugin_public = new Palladium_Api_Webstore_Addon_Public($this->get_plugin_name(), $this->get_version());

	$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
	$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	$this->loader->add_action('woocommerce_thankyou', $plugin_public, 'pld_manage_woocom_order_details', null, 1);
	$this->loader->add_action('woocommerce_created_customer', $plugin_public, 'pld_manage_woocom_customer_details', null, 1);
	$this->loader->add_action('rest_api_init', $plugin_public, 'pld_rest_api_customer');
	// $this->loader->add_action('manage_edit-shop_order_columns', $plugin_public, 'custom_shop_order_column_sync_to_palladium');
	// $this->loader->add_action('manage_shop_order_posts_custom_column', $plugin_public, 'custom_order_sync_palladium');
        
        $this->loader->add_action('woocommerce_get_price_html', $plugin_public, 'pld_display_palladium_custom_price', null, 2);
        $this->loader->add_action('woocommerce_before_calculate_totals', $plugin_public, 'pld_cart_palladium_custom_price', null, 1);

        $this->loader->add_filter('woocommerce_is_purchasable', $plugin_public, 'pld_is_purchasable_cb', 10, 2);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
	$this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
	return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Palladium_Api_Webstore_Addon_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
	return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
	return $this->version;
    }

}
