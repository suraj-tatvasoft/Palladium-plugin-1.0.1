<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://tjhokopaint.co.za/
 * @since      1.0.0
 *
 * @package    Palladium_Api_Webstore_Addon
 * @subpackage Palladium_Api_Webstore_Addon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript and some actions.
 *
 * @package    Palladium_Api_Webstore_Addon
 * @subpackage Palladium_Api_Webstore_Addon/admin
 * @author     Palladium Development team <#>
 */
class Palladium_Api_Webstore_Addon_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

	$this->plugin_name = $plugin_name;
	$this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
	wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/palladium-api-webstore-addon-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
	wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/palladium-api-webstore-addon-admin.js', array('jquery'), $this->version, false);
    }

    /**
     * Add the admin settings page for plugin.
     *
     * @since    1.0.0
     */
    public function pld_add_admin_settings_page() {
	add_menu_page(__('Palladium webstore API settings'), __('Palladium webstore API settings'), 'manage_options', 'pld-webstore-api-settings', array($this, 'pld_admin_menu_render'), 'dashicons-admin-settings');
    }

    /**
     * Render the settings field in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_admin_menu_render() {
	?>
	<form action="options.php" method="post" id="pld-webstore-api-settings-form">
	    <?php
	    settings_fields('pld_api_options');
	    do_settings_sections('pld_api_section');
	    ?>
	    <br/>
	    <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save Settings'); ?>" />
        <br/>
        <h2><?php esc_attr_e('Import Palladium Cron URL'); ?></h2>
        <a href="<?php echo site_url(); ?>/?importKey=pld-customers-data&importType=palladium" target="_blank" class="button button-primary"><?php esc_attr_e('Import Palladium Customer'); ?></a>
        <a href="<?php echo site_url(); ?>/?importKey=pld-product-data&importType=palladium" target="_blank" class="button button-primary"><?php esc_attr_e('Import Palladium Product'); ?></a>
        <?php /* <a href="<?php echo site_url(); ?>/?importKey=pld-product-variant-data&importType=palladium" target="_blank" class="button button-primary"><?php esc_attr_e('Import Palladium variation Product'); ?></a> */ ?>
        <a href="<?php echo site_url(); ?>/wp-json/import/pld-customers-prices" target="_blank" class="button button-primary"><?php esc_attr_e('Import Customer Prices'); ?></a>
        <a href="<?php echo site_url(); ?>/wp-json/import/pld-customers-tax-code" target="_blank" class="button button-primary"><?php esc_attr_e('Import Customer Tax Code'); ?></a>
        <a href="<?php echo site_url(); ?>/wp-json/import/pld-sales-discount-matrix" target="_blank" class="button button-primary"><?php esc_attr_e('Import Sales Discount Matrix'); ?></a>
	</form>
	<?php
    }

    /**
     * Register and display the admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_admin_register_settings() {
        register_setting('pld_api_options', 'pld_api_options');
        add_settings_section('pld_api_settings', 'Palladium Webstore API Settings', array($this, 'pld_plugin_section_description'), 'pld_api_section');

        add_settings_field('pld_webstore_endpoint', 'API Endpoint (Required) : ', array($this, 'pld_webstore_endpoint'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_database', 'API Database (Required) : ', array($this, 'pld_webstore_database'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_customers_api_path', 'Add Customer API Path (Required) : ', array($this, 'pld_webstore_customers_api_path'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_orders_api_path', 'Orders API Path (Required) : ', array($this, 'pld_webstore_orders_api_path'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_get_customer_from_api_path', 'Get Customer API Path (Required) : ', array($this, 'pld_webstore_get_customer_from_api_path'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_get_customer_from_magento_store', 'Get Customer From magento store Path (Required) : ', array($this, 'pld_webstore_get_customer_from_magento_store'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_select_salesname', 'Please select Particular Salesname (Required) : ', array($this, 'pld_webstore_select_salesname'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_get_product_from_api_path', 'Get product from palladium API Path (Required) : ', array($this, 'pld_webstore_get_product_from_api_path'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_get_child_product_items_from_api_path', 'Get child products from palladium API Path (Required) : ', array($this, 'pld_webstore_get_child_product_items_from_api_path'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_get_pricelist_from_api_path', 'Get pricelist from palladium API Path (Required) : ', array($this, 'pld_webstore_get_pricelist_from_api_path'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_get_sales_discount_matrix_from_api_path', 'Get Sales Discount Matrix from palladium API Path (Required) : ', array($this, 'pld_webstore_get_sales_discount_matrix_from_api_path'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_get_customer_category_api', 'Get Customers Category API Path (Required) : ', array($this, 'pld_webstore_get_customer_category_api'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_get_customer_taxcode_api', 'Get Customers Tax Code API Path (Required) : ', array($this, 'pld_webstore_get_customer_taxcode_api'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_default_price_list', 'Default Price List (Required) : ', array($this, 'pld_webstore_default_price_list'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_include_vat_in_price', 'Include VAT in price? : ', array($this, 'pld_webstore_include_vat_in_price'), 'pld_api_section', 'pld_api_settings');
        add_settings_field('pld_webstore_default_tax_code', 'Default Tax code (Required) : ', array($this, 'pld_webstore_default_tax_code'), 'pld_api_section', 'pld_api_settings');
    }

    /**
     * Display the description for fields in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_plugin_section_description() {
        echo '<p>Here you can set all the options for using the Paladium Webstore API. </p>';
    }

    /**
     * Display the field for api endpoint option in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_endpoint() {
        $options = get_option('pld_api_options');
        echo "<input id='pld_webstore_endpoint' placeholder='like http://129.232.223.226/MagentoAPI/api' name='pld_api_options[pld_webstore_endpoint]' type='text' value='" . $options['pld_webstore_endpoint'] . "' />";
    }

    /**
     * Display the field for api database option in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_database() {
        $options = get_option('pld_api_options');
        echo "<input id='pld_webstore_database' placeholder='like paldbTJHOKOWebstore' name='pld_api_options[pld_webstore_database]' type='text' value='" . $options['pld_webstore_database'] . "' />";
    }

    /**
     * Display the field for api path for customer API in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_customers_api_path() {
        $options = get_option('pld_api_options');
        echo "<input id='pld_webstore_customers_api_path' placeholder='like /Customer/SaveAddEditCustomerDetails' name='pld_api_options[pld_webstore_customers_api_path]' type='text' value='" . $options['pld_webstore_customers_api_path'] . "' />";
    }

    /**
     * Display the field for api path for orders API in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_orders_api_path() {
        $options = get_option('pld_api_options');
        echo "<input id='pld_webstore_orders_api_path' placeholder='like /Sales/AddUpdateOrderDoc' name='pld_api_options[pld_webstore_orders_api_path]' type='text' value='" . $options['pld_webstore_orders_api_path'] . "' />";
    }

    /**
     * Display the field for api path for customer sync API from api to webstore in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_get_customer_from_api_path() {
        $options = get_option('pld_api_options');
        echo "<input id='pld_webstore_customers_api_path' placeholder='like /Common/GetCustomerAdvanceSearchDataWebStore' name='pld_api_options[pld_webstore_get_customer_from_api_path]' type='text' value='" . $options['pld_webstore_get_customer_from_api_path'] . "' />";
    }

    /**
     * Display the field for api path for get user from magento store in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_get_customer_from_magento_store() {
        $options = get_option('pld_api_options');
        echo "<input id='pld_webstore_get_customer_from_magento_store' placeholder='like /MagentoWebStore/GetUsersList' name='pld_api_options[pld_webstore_get_customer_from_magento_store]' type='text' value='" . $options['pld_webstore_get_customer_from_magento_store'] . "' />";
    }

    /**
     * Display the field for api path for get user from magentp store in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_select_salesname() {
	$options = get_option('pld_api_options');

	if (
		isset($options) && !empty($options) &&
		!empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
		!empty($options['pld_webstore_get_customer_from_magento_store'])
	) {

	    $pld_webstore_endpoint = $options['pld_webstore_endpoint'];
	    $pld_webstore_database = $options['pld_webstore_database'];
	    $pld_webstore_get_customer_from_magento_store = $options['pld_webstore_get_customer_from_magento_store'];

      
	    // Initiate curl session in a variable (resource)
	    $curl_handle = curl_init();
	    $url = $pld_webstore_endpoint . $pld_webstore_get_customer_from_magento_store;
        
	    $headerArray = array(
		"Content-Type: application/json",
		"auth-database: " . $pld_webstore_database,
	    );
	    curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10000);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        
	    // This option will return data as a string instead of direct output
	    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArray);

	    // Execute curl & store data in a variable
	    $curl_data = curl_exec($curl_handle);

	    curl_close($curl_handle);
       
	    // Decode JSON into PHP array
	    $salesNames = json_decode($curl_data);
	    $salesNames = $salesNames->Data;
       
	    echo "<select name='pld_api_options[pld_webstore_select_salesname]' id='pld_webstore_select_salesname'>";
	    if (!empty($options['pld_webstore_select_salesname'])) {
		echo "<option value='" . $options['pld_webstore_select_salesname'] . "'>" . $options['pld_webstore_select_salesname'] . "</option>";
	    } else {
		echo "<option value='null'>Select</option>";
	    }
	    foreach ($salesNames as $salesName) {
		if ($options['pld_webstore_select_salesname'] != $salesName) {
		    echo "<option value='" . $salesName . "'>" . $salesName . "</option>";
		}
	    }
	    echo "</select>";
        } else {
            echo "<i>* Please add and save values in above field first. After saving above details and page refresh, dropdown will be display based on those fields</i>";
	}
    }

    /**
     * Display the field for api path for product sync API from api to webstore in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_get_product_from_api_path() {
        $options = get_option('pld_api_options');
        echo "<input id='pld_webstore_get_product_from_api_path' placeholder='like /InventoryPicker/GetInventoryItemsWebStore' name='pld_api_options[pld_webstore_get_product_from_api_path]' type='text' value='" . $options['pld_webstore_get_product_from_api_path'] . "' />";
    }
    public function pld_webstore_get_child_product_items_from_api_path() {
        $options = get_option('pld_api_options');
        echo "<input id='pld_webstore_get_child_product_items_from_api_path' placeholder='like /InventoryPicker/GetChildItemList' name='pld_api_options[pld_webstore_get_child_product_items_from_api_path]' type='text' value='" . $options['pld_webstore_get_child_product_items_from_api_path'] . "' />";
    }

    /**
     * Display the field for api path for pricelist API in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_get_pricelist_from_api_path() {
        $options = get_option('pld_api_options');
        echo "<input id='pld_webstore_get_pricelist_from_api_path' placeholder='like /InventoryPicker/GetPriceListsWebStore' name='pld_api_options[pld_webstore_get_pricelist_from_api_path]' type='text' value='" . $options['pld_webstore_get_pricelist_from_api_path'] . "' />";
    }

    /**
     * Display the field for api path for Sales discount matrix API in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_get_sales_discount_matrix_from_api_path() {
        $options = get_option('pld_api_options');
        echo "<input id='pld_webstore_get_sales_discount_matrix_from_api_path' placeholder='like /SalesDiscountMatrix/GetSalesDiscountMatrixDataByInvCategoryWebStore' name='pld_api_options[pld_webstore_get_sales_discount_matrix_from_api_path]' type='text' value='" . $options['pld_webstore_get_sales_discount_matrix_from_api_path'] . "' />";
    }

    /**
     * Display the field for api path for get customers category API in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_get_customer_category_api() {
        $options = get_option('pld_api_options');
        echo "<input id='pld_webstore_get_customer_category_api' placeholder='like /CustomerCategories/GetCustomerCategories/false' name='pld_api_options[pld_webstore_get_customer_category_api]' type='text' value='" . $options['pld_webstore_get_customer_category_api'] . "' />";
    }

    /**
     * Display the field for api path for get customers tax code API in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_get_customer_taxcode_api() {
        $options = get_option('pld_api_options');
        echo "<input id='pld_webstore_get_customer_taxcode_api' placeholder='like /Taxes/GetAllTaxCodeExt' name='pld_api_options[pld_webstore_get_customer_taxcode_api]' type='text' value='" . $options['pld_webstore_get_customer_taxcode_api'] . "' />";
    }

    /**
     * Display the field for add default price list in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_default_price_list() {
        $options = get_option('pld_api_options');
        echo "<input id='pld_webstore_default_price_list' placeholder='like REGULAR' name='pld_api_options[pld_webstore_default_price_list]' type='text' value='" . $options['pld_webstore_default_price_list'] . "' />";
    }

    /**
     * Display the field for add default tax code in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_include_vat_in_price() {
        $options = get_option('pld_api_options');
        $include_vat = $options['pld_webstore_include_vat_in_price'];
        $first_select = '';
        $second_select = ' checked="checked"';
        if ($include_vat && $include_vat == 'on') {
            $first_select = ' checked="checked"';
            $second_select = '';
        }
        echo "<input id='pld_webstore_include_vat_in_price' value='on' name='pld_api_options[pld_webstore_include_vat_in_price]' type='radio' " . $first_select . "/> Yes  ";
        echo "<input id='pld_webstore_include_vat_in_price_no' value='off' name='pld_api_options[pld_webstore_include_vat_in_price]' type='radio' " . $second_select . "/> No";
    }

    /**
     * Display the field for add default tax code in admin settings page for plugin
     *
     * @since    1.0.0
     */
    public function pld_webstore_default_tax_code() {
        $options = get_option('pld_api_options');
        echo "<input id='pld_webstore_default_tax_code' placeholder='like 01' name='pld_api_options[pld_webstore_default_tax_code]' type='text' value='" . $options['pld_webstore_default_tax_code'] . "' />";
    }

    /**
     * Add the admin notice for file write permission.
     *
     * @since    1.0.0
     */
    public function pld_admin_notice_permission() {
        $upload_dir = wp_upload_dir();
        $customers_dirname = $upload_dir['basedir'] . '/palldium-api-logs/customers';
        $orders_dirname = $upload_dir['basedir'] . '/palldium-api-logs/orders';
        $options = get_option('pld_api_options');

        if (!file_exists($customers_dirname) || !file_exists($orders_dirname)) {
            mkdir($customers_dirname, 0777, true);
            mkdir($orders_dirname, 0777, true);
        }

        $error_messages = array();

        if (!file_exists($customers_dirname) || !file_exists($orders_dirname)) {
            $error_messages[] = 'Palladium logs write error : Unable to create directory wp-content/uploads/palldium-api-logs/. Is its parent directory writable by the server?';
        } if (empty($options['pld_webstore_endpoint'])) {
            $error_messages[] = 'Palladium endpoint API error : Please add endpoint';
        } if ($options['pld_webstore_select_salesname'] == 'null') {
            $error_messages[] = 'Palladium salesman error : Please Select Salesman';
        } if (empty($options['pld_webstore_database'])) {
            $error_messages[] = 'Palladium database error : Please add API database';
        } if (empty($options['pld_webstore_customers_api_path'])) {
            $error_messages[] = 'Palladium customer API error : Please add customer API path';
        } if (empty($options['pld_webstore_orders_api_path'])) {
            $error_messages[] = 'Palladium order API error : Please add order API path';
        } if (empty($options['pld_webstore_get_customer_from_api_path'])) {
            $error_messages[] = 'Palladium get customer API error : Please add get customer API path';
        } if (empty($options['pld_webstore_get_customer_from_magento_store'])) {
            $error_messages[] = 'Palladium get customer from magento store error : Please add get customer from magento store path';
        } if (empty($options['pld_webstore_get_product_from_api_path'])) {
            $error_messages[] = 'Palladium product API error : Please add product API path';
        } if (empty($options['pld_webstore_get_child_product_items_from_api_path'])) {
            $error_messages[] = 'Palladium child product API error : Please add child product API path';
        } if (empty($options['pld_webstore_get_pricelist_from_api_path'])) {
            $error_messages[] = 'Palladium pricelist API error : Please add pricelist API path';
        } if (empty($options['pld_webstore_get_sales_discount_matrix_from_api_path'])) {
            $error_messages[] = 'Palladium sales discount matrix API error : Please add sales discount matrix API path';
        } if (empty($options['pld_webstore_get_customer_category_api'])) {
            $error_messages[] = 'Palladium customers categories API error : Please add customers categories API path';
        } if (empty($options['pld_webstore_get_customer_taxcode_api'])) {
            $error_messages[] = 'Palladium get customer Tax code error : Please add  customer Tax code API path';
        } if (empty($options['pld_webstore_default_price_list'])) {
            $error_messages[] = 'Palladium Default Price List error : Please add Default Price List';
        } if (empty($options['pld_webstore_include_vat_in_price'])) {
            $error_messages[] = 'Palladium include VAT in price error : Please select include VAT in price';
        } if (empty($options['pld_webstore_default_tax_code'])) {
            $error_messages[] = 'Palladium Default tax code error : Please add default tax code';
        }

        if (isset($error_messages) && !empty($error_messages)) {
            echo '<div class="notice notice-error is-dismissible"><h2>Palladium plugin configuration error</h2>';
            foreach ($error_messages as $key => $error_msg) {
                echo '<p><i>' . ($key + 1) . '. ' . $error_msg . '</i></p>';
        }
            echo '</div>';
    }
}

}

add_action('admin_enqueue_scripts', 'enqueue_my_scripts');

function enqueue_my_scripts($hook) {
    $screen = get_current_screen(); 
    if ('shop_order' != $screen->post_type) {
        return;
    }

    wp_register_script('pld_wp_admin_script', plugins_url('js/palladium-admin.js', __FILE__), array(), time());
    wp_localize_script('pld_wp_admin_script', 'ajax_pld_order', array('ajaxurl' => admin_url('admin-ajax.php')));
    wp_enqueue_script('pld_wp_admin_script');
}