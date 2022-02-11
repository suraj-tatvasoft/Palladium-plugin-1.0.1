<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://tjhokopaint.co.za/
 * @since      1.0.0
 *
 * @package    Palladium_Api_Webstore_Addon
 * @subpackage Palladium_Api_Webstore_Addon/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Palladium_Api_Webstore_Addon
 * @subpackage Palladium_Api_Webstore_Addon/public
 * @author     Palladium Development team <#>
 */
class Palladium_Api_Webstore_Addon_Public {

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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action( 'wp', array( $this, 'schedule_event' ) );
        add_action( 'init', array( $this, 'schedule_event' ) );
        

        add_filter( 'cron_schedules', array($this,'pld_every_day_product_price_cron') );
        add_action( 'pld_every_day_product_price_cron',array($this, 'pld_every_day_import_product_price')  );

        add_filter( 'cron_schedules', array($this,'pld_every_day_tax_codes_cron') );
        add_action( 'pld_every_day_tax_codes_cron',array($this, 'pld_every_day_import_tax_code')  );
    
        add_filter( 'cron_schedules', array($this,'pld_every_day_sales_discount_cron') );
        add_action( 'pld_every_day_sales_discount_cron',array($this, 'pld_every_day_import_sales_discount')  );

        add_filter( 'cron_schedules', array($this,'pld_every_day_customer_import_cron') );
        add_action( 'pld_every_day_customer_import_cron',array($this, 'pld_every_day_import_customer')  );

        add_filter( 'cron_schedules', array($this,'pld_every_day_product_import_cron') );
        add_action( 'pld_every_day_product_import_cron',array($this, 'pld_every_day_import_product')  );
    }

    /**
     * Schedule cron once daily for product price
     */
    public function pld_every_day_product_price_cron( $schedules ) {
        $schedules['every_day'] = array(
                'interval'  => 86400,
                'display'   => __( 'Every Day')
        );
        return $schedules;
    }

    /**
     * Schedule cron once daily for tax code
     */
    function pld_every_day_tax_codes_cron( $schedules ) {
        $schedules['every_day'] = array(
                'interval'  => 86400,
                'display'   => __( 'Every Day')
        );
        return $schedules;
    }

    /**
     * Schedule cron once daily for discount matrix
     */
    function pld_every_day_sales_discount_cron( $schedules ) {
        $schedules['every_day'] = array(
                'interval'  => 86400,
                'display'   => __( 'Every Day')
        );
        return $schedules;
    }

    /**
     * Schedule cron once daily for import customer
     */
    function pld_every_day_customer_import_cron( $schedules ) {
        $schedules['every_day'] = array(
                'interval'  => 86400,
                'display'   => __( 'Every Day' )
        );
        return $schedules;
    }

    /**
     * Schedule cron once daily for import customer
     */
    function pld_every_day_product_import_cron( $schedules ) {
        $schedules['every_day'] = array(
                'interval'  => 86400,
                'display'   => __( 'Every Day' )
        );
        return $schedules;
    }

    /**
     * Schedule Event function
     */
    public function schedule_event( ) {
        if ( ! wp_next_scheduled( 'pld_every_day_product_price_cron' ) ) {
            wp_schedule_event( time(), 'every_day','pld_every_day_product_price_cron');
        }
        if ( ! wp_next_scheduled( 'pld_every_day_tax_codes_cron' ) ) {
            wp_schedule_event( time(), 'every_day','pld_every_day_tax_codes_cron');
        }

        if ( ! wp_next_scheduled( 'pld_every_day_sales_discount_cron' ) ) {
            wp_schedule_event( time(), 'every_day', 'pld_every_day_sales_discount_cron' );
        }

        if ( ! wp_next_scheduled( 'pld_every_day_customer_import_cron' ) ) {
            wp_schedule_event( time(), 'every_day','pld_every_day_customer_import_cron' );
        }

        if ( ! wp_next_scheduled( 'pld_every_day_product_import_cron' ) ) {
            wp_schedule_event( time(), 'every_day','pld_every_day_product_import_cron' );
        }

        
    }

   
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/palladium-api-webstore-addon-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/palladium-api-webstore-addon-public.js', array('jquery'), $this->version, false);
    }

    /**
     * Import product price function
     */
    public function pld_every_day_import_product_price() {
        $this->import_customer_prices_from_api();
    }

    /**
     * Import tax code function 
     */
    public function pld_every_day_import_tax_code() {
        $this->import_customer_taxcode_from_api();
    }

    /**
     * Import sales discount function 
     */
    public function pld_every_day_import_sales_discount() {
        $this->import_sales_discount_matrix_from_api();
    }

    /**
     * Import import customer function 
     */
    public function pld_every_day_import_customer() {
        $this->import_customer_from_api();
    }

    /**
     * Import import product function 
     */
    public function pld_every_day_import_product() {
        $this->import_product_from_api();
    }
    
    

    /**
     * Pull order details from woocommerce orders on woocommerce_thankyou action
     *
     * @since    1.0.0
     */
    public function pld_manage_woocom_order_details($order_id) {
        /**
         * get order object and order details
         */
        $order = wc_get_order($order_id);
        $order_data = $order->get_data();
        $email = $order_data['billing']['email'];
        if (isset($email) && !empty($email)) {
            // Next, sanitize the data
            $email_addr = trim(strip_tags(stripslashes($email)));
            if (filter_var($email_addr, FILTER_VALIDATE_EMAIL)) {

                if (false == get_user_by('email', $email_addr)) {
                    // billing details
                    $billing_first_name = $order_data['billing']['first_name'];
                    $billing_last_name = $order_data['billing']['last_name'];
                    $billing_company = $order_data['billing']['company'];
                    $billing_country_code = $order_data['billing']['country'];
                    $billing_address1 = $order_data['billing']['address_1'];
                    $billing_address2 = $order_data['billing']['address_2'];
                    $billing_city = $order_data['billing']['city'];
                    $billing_phone = $order_data['billing']['phone'];
                    $billing_state = $order_data['billing']['state'];
                    $billing_postcode = $order_data['billing']['postcode'];
                    $billing_notes = $order_data['customer_note'];
                    // shipping details
                    $shipping_first_name = $order_data['shipping']['first_name'];
                    $shipping_last_name = $order_data['shipping']['last_name'];
                    $shipping_company = $order_data['shipping']['company'];
                    $shipping_address1 = $order_data['shipping']['address_1'];
                    $shipping_address2 = $order_data['shipping']['address_2'];
                    $shipping_city = $order_data['shipping']['city'];
                    $shipping_postcode = $order_data['shipping']['postcode'];
                    $userdata = array(
                        'user_login' => $email,
                        'user_email' => $email,
                        'role' => 'customer',
                        'display_name' => $billing_first_name . $billing_last_name,
                        'first_name' => $billing_first_name,
                        'last_name' => $billing_last_name,
                        'user_pass' => NULL
                    );                    
                    wp_insert_user($userdata);

                    $dataUser = get_user_by('email', $email_addr);
                    $userData = $dataUser->data;
                    $user_id = $userData->ID;
                    update_post_meta($order_id, '_customer_user', $user_id);

                    // You will need also to add this billing meta data
                    update_user_meta($user_id, 'billing_first_name', $billing_first_name);
                    update_user_meta($user_id, 'billing_last_name', $billing_last_name);
                    update_user_meta($user_id, 'billing_email', $email);
                    update_user_meta($user_id, 'billing_address_1', $billing_address1);
                    update_user_meta($user_id, 'billing_address_2', $billing_address2);
                    update_user_meta($user_id, 'billing_city', $billing_city);
                    update_user_meta($user_id, 'billing_postcode', $billing_postcode);
                    update_user_meta($user_id, 'billing_phone', $billing_phone);
                    update_user_meta($user_id, 'billing_company', $billing_company);

                    // You will need also to add this shipping meta data
                    update_user_meta($user_id, 'shipping_first_name', $shipping_first_name);
                    update_user_meta($user_id, 'shipping_last_name', $shipping_last_name);
                    update_user_meta($user_id, 'shipping_address_1', $shipping_address1);
                    update_user_meta($user_id, 'shipping_address_2', $shipping_address2);
                    update_user_meta($user_id, 'shipping_city', $shipping_city);
                    update_user_meta($user_id, 'shipping_postcode', $shipping_postcode);
                    update_user_meta($user_id, 'shipping_company', $shipping_company);


                    update_user_meta($user_id, 'pld_customer_cust_category', 'DEFAULT');
                    update_user_meta($user_id, 'pld_customer_pricelist', 'REGULAR');
                    update_user_meta($user_id, 'pld_customer_tax_code', '01');

                    // customer api
                    if ($user_id) {
                        $firstname = $order_data['billing']['first_name'];
                        $unique_name = strtoupper(substr($firstname, 0, 3)) . 'WOO' . rand(0, 10000);
                        $date = date('d F y');
                        $unixDate = strtotime($date);
                        $currency = $this->pld_get_country_currency($billing_country_code);
                        $country_name = $this->pld_show_country($billing_country_code);

                        update_user_meta($user_id, 'customer_unique_id', $unique_name);

                        $options = get_option('pld_api_options');

                        if (
                                isset($options) && !empty($options) &&
                                !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                                !empty($options['pld_webstore_get_customer_from_magento_store'])
                        ) {
                            $palladium_webstore_endpoint = $options['pld_webstore_endpoint'];
                            $palladium_webstore_database = $options['pld_webstore_database'];

                            // Initiate curl session in a variable (resource)
                            $curl_handle = curl_init();
                            $url = $palladium_webstore_endpoint . '/MagentoWebStore/GetNextDocSalesOrder/' . $options['pld_webstore_select_salesname'];

                            $headerArrayPalladium = array(
                                "Content-Type: application/json",
                                "auth-database: " . $palladium_webstore_database,
                            );
                            $indexParameter = json_encode($data);
                            curl_setopt($curl_handle, CURLOPT_URL, $url);
                            curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "GET");
                            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArrayPalladium);
                            
                            $curl_data = curl_exec($curl_handle);

                            curl_close($curl_handle);

                            // Decode JSON into PHP array
                            $result = json_decode($curl_data);
                            $results = $result->Data;

                            $SalesPerson = $results->DefaultSalesPerson;
                            $DefaultARDepartment = $results->DefaultARDepartment;
                            $DefaultTerm = $results->DefaultTerm;
                            $DefaultTaxCode = $results->DefaultTaxCode;
                        }
                        $data_for_customer_api = array(
                            "IsChkTargets" => false,
                            "CustomerExList" => "",
                            "CustomerExWTList" => "",
                            "CustomerLocList" => "",
                            "CustomersTList" => "",
                            "InvoiceDocList" => "[]",
                            "CustomerTransactionsList" => "",
                            "CustomerTransactionsDTList" => "",
                            "CustomerItemsData" => "",
                            "InventoryData" => "",
                            "FilesList" => "",
                            "CustomerInvoiceCopy" => "",
                            'CustomerName' => $unique_name,
                            'Street1' => $billing_address1,
                            'Street2' => $billing_address2,
                            'City' => $billing_city,
                            "Province" => $billing_state,
                            'Postal' => $billing_postcode,
                            'Phone' => $billing_phone,
                            'CellPhone' => $billing_phone,
                            'Fax' => null,
                            'Email' => $email,
                            "VAT" => "",
                            "CK" => "",
                            "BalDue" => 0,
                            "YTDPurch" => 0,
                            "OpenOrders" => 0,
                            "DelMethod" => 1,
                            "IsCreditCtrl" => true,
                            "CreditLimit" => 0,
                            "IsCreditHold" => false,
                            "IsInactive" => false,
                            "Updated" => "/Date(-62135596800000)/",
                            "Contact" => "",
                            "Field1" => "",
                            "Field2" => "",
                            "Field3" => "",
                            "Field4" => "",
                            "Field5" => "",
                            "Field6" => "",
                            "TaxCode" => $DefaultTaxCode ? $DefaultTaxCode : "01",
                            "Since" => "/Date(1530297000000)/",
                            'Notes' => $billing_notes,
                            'Pricelist' => "REGULAR",
                            'ID' => "00000000-0000-0000-0000-000000000000",
                            'IntRate' => 0,
                            'Description' => $billing_first_name,
                            'Country' => $country_name,
                            'Term' => $DefaultTerm ? $DefaultTerm : '',
                            'Department' => $DefaultARDepartment ? $DefaultARDepartment : '',
                            'Currency' => $currency,
                            "BalDueF" => 0,
                            "YTDPurchF" => 0,
                            "OpenOrdersF" => 0,
                            "Field7" => "",
                            "Field8" => "",
                            "Field9" => "",
                            "Field10" => "",
                            "Field11" => "",
                            "Field12" => "",
                            "EmailInv" => "",
                            "EmailStat" => "",
                            "Category" => "DEFAULT",
                            'CountryCode' => $country_code,
                            "TenderType" => "On Account",
                            "TradeDiscPerc" => 0,
                            "CreditLimitOverride" => 0,
                            "CreditLimitExpiryDate" => "/Date(1530901800000)/",
                            "PartialBackorders" => 0,
                            "IsWithholdTax" => false,
                            "WithholdTaxCode" => "",
                            "INVFormDocName" => null,
                            "ORDFormDocName" => null,
                            "IsMatrixType" => false,
                            "MatrixType" => "DEFAULT",
                            "ParentCust" => $unique_name,
                            "ValueBased" => null,
                            "IsInvWorkflowNew" => true,
                            "IsInvWorkflowDA" => true,
                            "IsInvWorkflowSO" => true,
                            "Website" => "",
                            "RSTTerminalSync" => "",
                            "Contact2" => "",
                            "Suburb" => "",
                            "EmailOso" => "",
                            "NAPrintMethod" => 0,
                            "DelMethodCode" => null,
                            "SalesName" => $SalesPerson ? $SalesPerson : '',
                            "Center" => null,
                            "OpenQuotes" => 0,
                            "IsRowSelected" => false,
                            "CurrencySymbol" => null,
                            "APR" => "12.25",
                            "CustNameForCustomerContactTable" => null,
                            "OldCustNameForCustomerContactTable" => "a7875073-4a88-4d1d-a55e-ad7b6790425e",
                            "NewCustNameForCustomerContactTable" => $unique_name,
                            "CenterID" => "",
                            "PL_HistoryOn" => true,
                            "CustomerChildrens" => "",
                            "CurrentYrStarts" => "/Date(" . $unixDate . ")/",
                            "OldName" => null,
                            "ParentCustNew" => null,
                            "ProfitCenters" => null,
                            "IsExcludeFromCommission" => false,
                        );

                        if (
                                $user_id && isset($options) && !empty($options) &&
                                !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                                !empty($options['pld_webstore_customers_api_path'] && !empty($options['pld_webstore_select_salesname']))
                        ) {


                            /**
                             * get api options and generate request based on that
                             */
                            $pld_webstore_endpoint = $options['pld_webstore_endpoint'];
                            $pld_webstore_database = $options['pld_webstore_database'];
                            $pld_webstore_customers_api_path = $options['pld_webstore_customers_api_path'];

                            $customer_api_data = json_encode($data_for_customer_api);
                            /**
                             * send API request via cURL
                             */
                            $ch = curl_init();
                            $headerArray = array(
                                "Content-Type: application/json",
                                "auth-database: " . $pld_webstore_database,
                            );

                            /**
                             * set the complete URL, to save the customers in the external system
                             */
                            curl_setopt($ch, CURLOPT_URL, $pld_webstore_endpoint . $pld_webstore_customers_api_path);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 10000);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $customer_api_data);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
                            $response = curl_exec($ch);
                            /* Write customer logs */
                            $upload_dir = wp_upload_dir();
                            $customers_dirname = $upload_dir['basedir'] . '/palldium-api-logs/customers';
                            if (!file_exists($customers_dirname)) {
                                mkdir($customers_dirname, 0777, true);
                            }

                            $customer_log_filename = $customers_dirname . '/customer_' . $user_id . '.log';

                            $logfile = fopen($customer_log_filename, "a");
                            $log_text = "[" . date('Y-m-d H:i:s') . "] : Sending Customers details to Palladium API";
                            $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : Customer ID : " . $user_id;
                            $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : API URL : " . $pld_webstore_endpoint . $pld_webstore_customers_api_path;
                            $log_text .= "\n=====================";
                            $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : Request in API : ";
                            $log_text .= "\n" . $customer_api_data;
                            $log_text .= "\n=====================";
                            $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : Response from API : ";
                            $log_text .= "\n" . $response;
                            $log_text .= "\n=====================\n";

                            fwrite($logfile, $log_text);
                            fclose($logfile);
                            curl_close($ch);
                            /* End - Write customer logs */

                            $jsonDecode = json_decode($response);
                            if ($jsonDecode->IsSuccess == true) {
                                // update_user_meta($customer_id, 'sendtoapi' , 'true');
                                update_user_meta($customer_id, 'register_flag', 2);
                            }
                            /**
                             *  The handle response
                             */
                            if (!curl_errno($ch)) {
                                /**
                                 * success here
                                 */
                            }
                        } else {
                            /**
                             * failed, No options found
                             */
                        }
                    }
                } else {
                    // The user exists
                    $dataUser = get_user_by('email', $email_addr);
                    $userData = $dataUser->data;
                    $cust_user_id = $userData->ID;
                    update_post_meta($order_id, '_customer_user', $cust_user_id);
                    $user_id = $order->get_user_id();
                    $falg = get_user_meta($user_id, 'register_flag');
                    $user = $order->get_user(); // Get the WP_User object
                    $order_data = $order->get_data();
                    // customer api
                    if ($user_id) {
                        $user_info = $user->data;
                        $user_email = $user_info->user_email;
                        $the_user = get_user_by('email', $user_email);
                        $customer_id = $the_user->ID;
                        $falg = get_user_meta($customer_id, 'register_flag');
                        if ($falg[0] == 1 || empty($falg)) {
                            $firstname = $order_data['billing']['first_name'];
                            $unique_name = strtoupper(substr($firstname, 0, 3)) . 'WOO' . rand(0, 10000);
                            $date = date('d F y');
                            $unixDate = strtotime($date);
                            $first_name = $order_data['billing']['first_name'];
                            $last_name = $order_data['billing']['last_name'];
                            $company = $order_data['billing']['company'];
                            $country_code = $order_data['billing']['country'];
                            $address1 = $order_data['billing']['billing_address_1'];
                            $address2 = $order_data['billing']['billing_address_2'];
                            $city = $order_data['billing']['city'];
                            $phone = $order_data['billing']['phone'];
                            $email = $order_data['billing']['email'];
                            $state = $order_data['billing']['state'];
                            $postcode = $order_data['billing']['postcode'];
                            $notes = $customer_data['order_comments'];
                            $currency = $this->pld_get_country_currency($country_code);
                            $country_name = $this->pld_show_country($country_code);

                            update_user_meta($customer_id, 'customer_unique_id', $unique_name);
                            update_user_meta($customer_id, 'pld_customer_cust_category', 'DEFAULT');
                            update_user_meta($customer_id, 'pld_customer_pricelist', 'REGULAR');
                            update_user_meta($customer_id, 'pld_customer_tax_code', '01');

                            $options = get_option('pld_api_options');

                            if (
                                    isset($options) && !empty($options) &&
                                    !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                                    !empty($options['pld_webstore_get_customer_from_magento_store'])
                            ) {
                                $palladium_webstore_endpoint = $options['pld_webstore_endpoint'];
                                $palladium_webstore_database = $options['pld_webstore_database'];

                                // Initiate curl session in a variable (resource)
                                $curl_handle = curl_init();
                                $url = $palladium_webstore_endpoint . '/MagentoWebStore/GetNextDocSalesOrder/' . $options['pld_webstore_select_salesname'];

                                $headerArrayPalladium = array(
                                    "Content-Type: application/json",
                                    "auth-database: " . $palladium_webstore_database,
                                );
                                $indexParameter = json_encode($data);
                                curl_setopt($curl_handle, CURLOPT_URL, $url);
                                curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "GET");
                                // This option will return data as a string instead of direct output
                                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArrayPalladium);

                                // Execute curl & store data in a variable
                                $curl_data = curl_exec($curl_handle);

                                curl_close($curl_handle);

                                // Decode JSON into PHP array
                                $result = json_decode($curl_data);
                                $results = $result->Data;

                                $SalesPerson = $results->DefaultSalesPerson;
                                $DefaultARDepartment = $results->DefaultARDepartment;
                                $DefaultTerm = $results->DefaultTerm;
                                $DefaultTaxCode = $results->DefaultTaxCode;
                            }

                            $data_for_customer_api = array(
                                "IsChkTargets" => false,
                                "CustomerExList" => "",
                                "CustomerExWTList" => "",
                                "CustomerLocList" => "",
                                "CustomersTList" => "",
                                "InvoiceDocList" => "[]",
                                "CustomerTransactionsList" => "",
                                "CustomerTransactionsDTList" => "",
                                "CustomerItemsData" => "",
                                "InventoryData" => "",
                                "FilesList" => "",
                                "CustomerInvoiceCopy" => "",
                                'CustomerName' => $unique_name,
                                'Street1' => $address1,
                                'Street2' => $address2,
                                'City' => $city,
                                "Province" => $state,
                                'Postal' => $postcode,
                                'Phone' => $phone,
                                'CellPhone' => $phone,
                                'Fax' => null,
                                'Email' => $email,
                                "VAT" => "",
                                "CK" => "",
                                "BalDue" => 0,
                                "YTDPurch" => 0,
                                "OpenOrders" => 0,
                                "DelMethod" => 1,
                                "IsCreditCtrl" => true,
                                "CreditLimit" => 0,
                                "IsCreditHold" => false,
                                "IsInactive" => false,
                                "Updated" => "/Date(-62135596800000)/",
                                "Contact" => "",
                                "Field1" => "",
                                "Field2" => "",
                                "Field3" => "",
                                "Field4" => "",
                                "Field5" => "",
                                "Field6" => "",
                                "TaxCode" => $DefaultTaxCode ? $DefaultTaxCode : "01",
                                "Since" => "/Date(1530297000000)/",
                                'Notes' => $notes,
                                'Pricelist' => "REGULAR",
                                'ID' => "00000000-0000-0000-0000-000000000000",
                                'IntRate' => 0,
                                'Description' => $first_name,
                                'Country' => $country_name,
                                'Term' => $DefaultTerm ? $DefaultTerm : '',
                                'Department' => $DefaultARDepartment ? $DefaultARDepartment : '',
                                'Currency' => $currency,
                                "BalDueF" => 0,
                                "YTDPurchF" => 0,
                                "OpenOrdersF" => 0,
                                "Field7" => "",
                                "Field8" => "",
                                "Field9" => "",
                                "Field10" => "",
                                "Field11" => "",
                                "Field12" => "",
                                "EmailInv" => "",
                                "EmailStat" => "",
                                "Category" => "DEFAULT",
                                'CountryCode' => $country_code,
                                "TenderType" => "On Account",
                                "TradeDiscPerc" => 0,
                                "CreditLimitOverride" => 0,
                                "CreditLimitExpiryDate" => "/Date(1530901800000)/",
                                "PartialBackorders" => 0,
                                "IsWithholdTax" => false,
                                "WithholdTaxCode" => "",
                                "INVFormDocName" => null,
                                "ORDFormDocName" => null,
                                "IsMatrixType" => false,
                                "MatrixType" => "DEFAULT",
                                "ParentCust" => $unique_name,
                                "ValueBased" => null,
                                "IsInvWorkflowNew" => true,
                                "IsInvWorkflowDA" => true,
                                "IsInvWorkflowSO" => true,
                                "Website" => "",
                                "RSTTerminalSync" => "",
                                "Contact2" => "",
                                "Suburb" => "",
                                "EmailOso" => "",
                                "NAPrintMethod" => 0,
                                "DelMethodCode" => null,
                                "SalesName" => $SalesPerson ? $SalesPerson : '',
                                "Center" => null,
                                "OpenQuotes" => 0,
                                "IsRowSelected" => false,
                                "CurrencySymbol" => null,
                                "APR" => "12.25",
                                "CustNameForCustomerContactTable" => null,
                                "OldCustNameForCustomerContactTable" => "a7875073-4a88-4d1d-a55e-ad7b6790425e",
                                "NewCustNameForCustomerContactTable" => $unique_name,
                                "CenterID" => "",
                                "PL_HistoryOn" => true,
                                "CustomerChildrens" => "",
                                "CurrentYrStarts" => "/Date(" . $unixDate . ")/",
                                "OldName" => null,
                                "ParentCustNew" => null,
                                "ProfitCenters" => null,
                                "IsExcludeFromCommission" => false,
                            );

                            if (
                                    $user_id && isset($options) && !empty($options) &&
                                    !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                                    !empty($options['pld_webstore_customers_api_path'] && !empty($options['pld_webstore_select_salesname']))
                            ) {


                                /**
                                 * get api options and generate request based on that
                                 */
                                $pld_webstore_endpoint = $options['pld_webstore_endpoint'];
                                $pld_webstore_database = $options['pld_webstore_database'];
                                $pld_webstore_customers_api_path = $options['pld_webstore_customers_api_path'];

                                $customer_api_data = json_encode($data_for_customer_api);
                                /**
                                 * send API request via cURL
                                 */
                                $ch = curl_init();
                                $headerArray = array(
                                    "Content-Type: application/json",
                                    "auth-database: " . $pld_webstore_database,
                                );

                                /**
                                 * set the complete URL, to save the customers in the external system
                                 */
                                curl_setopt($ch, CURLOPT_URL, $pld_webstore_endpoint . $pld_webstore_customers_api_path);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 10000);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $customer_api_data);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
                                $response = curl_exec($ch);
                                // console.log()
                                // print_r($response);
                                /* Write customer logs */
                                $upload_dir = wp_upload_dir();
                                $customers_dirname = $upload_dir['basedir'] . '/palldium-api-logs/customers';
                                if (!file_exists($customers_dirname)) {
                                    mkdir($customers_dirname, 0777, true);
                                }

                                $customer_log_filename = $customers_dirname . '/customer_' . $customer_id . '.log';

                                $logfile = fopen($customer_log_filename, "a");
                                $log_text = "[" . date('Y-m-d H:i:s') . "] : Sending Customers details to Palladium API";
                                $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : Customer ID : " . $customer_id;
                                $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : API URL : " . $pld_webstore_endpoint . $pld_webstore_customers_api_path;
                                $log_text .= "\n=====================";
                                $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : Request in API : ";
                                $log_text .= "\n" . $customer_api_data;
                                $log_text .= "\n=====================";
                                $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : Response from API : ";
                                $log_text .= "\n" . $response;
                                $log_text .= "\n=====================\n";

                                fwrite($logfile, $log_text);
                                fclose($logfile);
                                curl_close($ch);
                                /* End - Write customer logs */

                                $jsonDecode = json_decode($response);
                                if ($jsonDecode->IsSuccess == true) {
                                    // update_user_meta($customer_id, 'sendtoapi' , 'true');
                                    update_user_meta($customer_id, 'register_flag', 2);
                                }
                                /**
                                 *  The handle response
                                 */
                                if (!curl_errno($ch)) {
                                    /**
                                     * success here
                                     */
                                }
                            } else {
                                /**
                                 * failed, No options found
                                 */
                            }
                        }
                    }
                }
            } else {

                // An invalid email format has been entered
            }
        }


        if ($order_id) {
            /**
             * get order object and order details
             */
            $order = wc_get_order($order_id);
            $order_id = $order->get_id();
            $order_data = $order->get_data();

            $date_details = $order->get_date_created();
            $createdDate = $date_details->date('Y-m-d H:i:s');

            $order_discount_total = $order_data['discount_total'];
            $order_discount_tax = $order_data['discount_tax'];
            $order_shipping_total = $order_data['shipping_total'];
            $order_shipping_tax = $order_data['shipping_tax'];
            $order_total = $order_data['total'];
            $order_total_tax = $order_data['total_tax'];
            $order_customer_id = $order_data['customer_id'];

            ## BILLING INFORMATION:

            $order_billing_first_name = $order_data['billing']['first_name'];
            $order_billing_last_name = $order_data['billing']['last_name'];
            $order_billing_company = $order_data['billing']['company'];
            $order_billing_address_1 = $order_data['billing']['address_1'];
            $order_billing_address_2 = $order_data['billing']['address_2'];
            $order_billing_city = $order_data['billing']['city'];
            $order_billing_state = $order_data['billing']['state'];
            $order_billing_postcode = $order_data['billing']['postcode'];
            $order_billing_country = $order_data['billing']['country'];
            $order_billing_email = $order_data['billing']['email'];
            $order_billing_phone = $order_data['billing']['phone'];
            $order_billing_phone = $order_data['billing']['phone'];

            ## SHIPPING INFORMATION:
            $order_shipping_first_name = $order_data['shipping']['first_name'];
            $order_shipping_last_name = $order_data['shipping']['last_name'];
            $order_shipping_company = $order_data['shipping']['company'];
            $order_shipping_address_1 = $order_data['shipping']['address_1'];
            $order_shipping_address_2 = $order_data['shipping']['address_2'];
            $order_shipping_city = $order_data['shipping']['city'];
            $order_shipping_state = $order_data['shipping']['state'];
            $order_shipping_postcode = $order_data['shipping']['postcode'];
            $order_shipping_country = $order_data['shipping']['country'];


            /**
             * get coupon information (if applicable)
             */
            $coupons = array();
            $coupon = array();
            $coupons = $order->get_items('coupon');

            foreach ($coupons as $cp) {
                /**
                 * get coupon titles (and additional details if accepted by the API)
                 */
                $coupon[] = $cp['name'];
            }



            $customer_user_id = $order->get_user_id(); // Get the costumer ID
            $user = $order->get_user(); // Get the WP_User object
            // print_r($user);
            $currency_code = $order->get_currency();
            $currency_symbol = get_woocommerce_currency_symbol($currency_code);

            $options = get_option('pld_api_options');

            if (
                    isset($options) && !empty($options) &&
                    !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                    !empty($options['pld_webstore_get_customer_from_magento_store'])
            ) {
                $palladium_webstore_endpoint = $options['pld_webstore_endpoint'];
                $palladium_webstore_database = $options['pld_webstore_database'];

                // Initiate curl session in a variable (resource)
                $curl_handle = curl_init();
                $url = $palladium_webstore_endpoint . '/MagentoWebStore/GetNextDocSalesOrder/' . $options['pld_webstore_select_salesname'];

                $headerArrayPalladium = array(
                    "Content-Type: application/json",
                    "auth-database: " . $palladium_webstore_database,
                );
                $indexParameter = json_encode($data);
                curl_setopt($curl_handle, CURLOPT_URL, $url);
                curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "GET");
                // This option will return data as a string instead of direct output
                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArrayPalladium);

                // Execute curl & store data in a variable
                $curl_data = curl_exec($curl_handle);

                curl_close($curl_handle);

                // Decode JSON into PHP array
                $result = json_decode($curl_data);
                $results = $result->Data;

                $DefaultSalesPerson = $results->DefaultSalesPerson;
                $DefaultARDepartment = $results->DefaultARDepartment;
                $DefaultTerm = $results->DefaultTerm;
                $DefaultTaxCode = $results->DefaultTaxCode;
            }

            /**
             * for online payments, send across the transaction ID/key. 
             * If the payment is handled offline, you could send across the order key instead
             */
            $transaction_key = get_post_meta($order_id, '_transaction_id', true);
            $transaction_key = empty($transaction_key) ? $_GET['key'] : $transaction_key;



            /**
             * get product details
             */
            $items = $order->get_items();
            $item_name = array();
            $item_qty = array();
            $item_price = array();
            $item_sku = array();
            $i = 0;
            foreach ($items as $key => $item) {
                $item_id = $item['product_id'];

                $product = new WC_Product($item_id);

                $item_array[$i]['LineNumber'] = 1;
                $item_array[$i]['Number'] = $product->get_sku();
                $item_array[$i]['Location'] = 'Default';
                $item_array[$i]['Description'] = 'BAGUETTE TABLE KNIFE 18\/10';
                $item_array[$i]['Qty'] = $item['qty'];
                $item_array[$i]['OpenQty'] = $item['qty'];
                $item_array[$i]['Unit'] = '12\/144';
                $item_array[$i]['Price'] = $item['line_total'];
                $item_array[$i]['DiscountPercentage'] = 0;
                $item_array[$i]['MarginPercentage'] = 1;
                $item_array[$i]['TaxCode'] = $DefaultTaxCode;
                $item_array[$i]['Tax'] = $item['taxes'][''];
                $item_array[$i]['Amount'] = $item['line_total'];
                $item_array[$i]['TaxObject'] = null;
                $item_array[$i]['IsAllowChngPrice'] = false;
                $item_array[$i]['SellQty'] = $item['qty'];
                $item_array[$i]['Inv1'] = '';
                $item_array[$i]['Inv2'] = '';
                $item_array[$i]['Inv3'] = '';
                $item_array[$i]['Inv4'] = '';
                $item_array[$i]['Inv5'] = '';
                $item_array[$i]['Inv6'] = '';
                $item_array[$i]['Line1'] = '';
                $item_array[$i]['Line2'] = '';
                $item_array[$i]['Line3'] = '';
                $item_array[$i]['Line4'] = '';
                $item_array[$i]['Line5'] = '';
                $item_array[$i]['Line6'] = '';
                $item_array[$i]['Line7'] = '';
                $item_array[$i]['Line8'] = '';
                $item_array[$i]['Line9'] = '';
                $item_array[$i]['Line10'] = '';
                $item_array[$i]['Line11'] = '';
                $item_array[$i]['Line12'] = '';
                $item_array[$i]['Discount'] = '0';
                $item_array[$i]['IsDiscountTAX'] = false;
                $item_array[$i]['ProjectName'] = '';
                $item_array[$i]['StockQty'] = $item['qty'];
                $item_array[$i]['StockUnit'] = $item['qty'];
                $item_array[$i]['OrigSOQtyStock'] = $item['qty'];
                $item_array[$i]['SerialOrLot'] = null;
                $item_array[$i]['IsInventorySerial'] = false;
                $item_array[$i]['OrigPrice'] = $item['price'];
                $item_array[$i]['OrigDiscountPercent'] = '0';
                $item_array[$i]['SellFromStock'] = 0;
                $item_array[$i]['RemainOnSO'] = '25';
                $item_array[$i]['Cost'] = 0;
                $item_array[$i]['OrigQtyRemain'] = 0;
                $item_array[$i]['IsCancelItemOrder'] = false;
                $item_array[$i]['ImageCount'] = 0;
                $item_array[$i]['SalesPerson'] = 'JHB WEB ORDERS';
                $item_array[$i]['IsVoucherEnabled'] = false;
                $item_array[$i]['VoucherCode'] = null;
                $item_array[$i]['DimensionProfile'] = '';
                $item_array[$i]['IsCostPriceDefault'] = false;
                $item_array[$i]['IsCostPriceOverride'] = false;
                $item_array[$i]['CostPriceValue'] = 0;
                $item_array[$i]['CostPriceTotal'] = 0;
                $item_array[$i]['SOQty'] = 0;
                $item_array[$i]['OQtyRemain'] = 0;
                $item_array[$i]['Extended'] = "";
                $item_array[$i]['PreviousSalesperson'] = null;
                $item_array[$i]['IsSOToSI'] = false;
                $item_array[$i]['IsSQToSI'] = false;
                $item_array[$i]['SODocNum'] = null;
                $item_array[$i]['SQDocNum'] = null;
                $item_array[$i]['IsMarginOverride'] = true;
                $item_array[$i]['DimensionGUID'] = '00000000-0000-0000-0000-000000000000';
                $item_array[$i]['ItemWeight'] = '1';
                $item_array[$i]['VolumetricWeight'] = '0.0000';
                $item_array[$i]['UnitOfMeasureWeight'] = 'kg';
                $item_array[$i]['IsContainerItem'] = false;
                $item_array[$i]['IsUpsellItem'] = false;
                $item_array[$i]['IsUpsellItemLaunched'] = false;
                $item_array[$i]['IsCostDel'] = false;
                $item_array[$i]['BillingMethod'] = 0;
                $item_array[$i]['IsSerialDimension'] = false;
                $item_array[$i]['REName'] = false;
                $item_array[$i]['TransactionFieldGUID'] = $transaction_key;
                $item_array[$i]['QuotedQty'] = 0;
                $item_array[$i]['JobQty'] = 0;
                $item_array[$i]['DecimalQuantity'] = 0;
                $item_array[$i]['AvailableQty'] = 0;
                $item_array[$i]['ImgData'] = null;
                $item_array[$i]['IsSerialAssigned'] = false;
                $item_array[$i]['SerialInfo'] = null;
                $item_array[$i]['UserSerialData'] = [];
                $item_array[$i]['FromBinID'] = 0;
                $item_array[$i]['ToBinID'] = 0;
                $item_array[$i]['Total'] = $order_total + $order_total_tax;


                $i++;
            }
            if (isset($customer_user_id)) {
                $unique_id = get_user_meta($customer_user_id, 'customer_unique_id', true);
                if (empty($unique_id)) {
                    $unique_name = strtoupper(substr($order_billing_first_name, 0, 3)) . 'WOO' . rand(0, 10000);
                    update_user_meta($customer_user_id, 'customer_unique_id', $unique_name);
                    $unique_id = get_user_meta($customer_user_id, 'customer_unique_id', true);
                }
            }
            /**
             * setup the data which has to be sent API
             */
            $orderApiData = array(
                'PartNumber' => null,
                'Location' => 'Default',
                'IsAdjusting' => false,
                'TransactionType' => 1,
                'CustomerCurrencyCode' => $currency_code,
                'ExchangeValue' => 1,
                'DocumentNumber' => 'SO-' . $order_id,
                'JournalDate' => $createdDate,
                'Required' => '2020-09-01T00:00:00+02:00',
                'UserName' => $unique_id,
                'CustomerNumber' => null,
                'SoldTo' => $order_billing_first_name . $order_billing_last_name . '\r\n' . $order_billing_address_1 . '\r\n' . $order_billing_address_2 . '\r\n' . $order_billing_phone . '\r\n' . $order_billing_country,
                'ShipTo' => !empty($order_shipping_first_name) ? $order_shipping_first_name . $order_shipping_last_name . '\r\n' . $order_shipping_address_1 . '\r\n' . $order_shipping_address_2 . '\r\n' . $order_shipping_postcode . '\r\n' . $order_shipping_country : $order_billing_first_name . $order_billing_last_name . '\r\n' . $order_billing_address_1 . '\r\n' . $order_billing_address_2 . '\r\n' . $order_billing_phone . '\r\n' . $order_billing_country,
                'Reference' => 'Ref Webstore',
                'DiscountPercentage' => 0,
                'DiscountTempPerc' => 0,
                'SubTotal' => $order_total,
                'DiscountAmount' => $order_discount_total,
                'VAT' => $order_total_tax,
                'Total' => $order_total + $order_total_tax,
                'DeliveryCost' => '0.0000',
                'Notes' => $order->customer_note,
                'SelectedSalesPerson' => $DefaultSalesPerson,
                'SelectedDepartment' => $DefaultARDepartment,
                'SelectedDepartmentIndex' => 0,
                'AdditionalInfo' => '',
                'CurrencyCode' => null,
                'IsForeign' => false,
                'SubTotalForeign' => 0,
                'DiscountAmountForeign' => 0,
                'VATForeign' => 0,
                'TotalForeign' => 0,
                'DeliveryCostForeign' => 0,
                'Term' => $DefaultTerm,
                'Dept' => $DefaultARDepartment,
                'Field1' => '',
                'Field2' => '',
                'Field3' => '',
                'Field4' => '',
                'Field5' => '',
                'Field6' => '',
                'Field7' => '',
                'Field8' => '',
                'Field9' => '',
                'Field10' => '',
                'Field11' => '',
                'Field12' => '',
                'Field13' => '2018-10-11T00:00:00+05:30',
                'Field14' => '2018-10-11T00:00:00+05:30',
                'SubFormatted' => 'R 3,567.00',
                'DiscountFormatted' => 'R 0.00',
                'VATFormatted' => 'R 0.00',
                'TotalFormatted' => 'R 3,567.00',
                'DeliveryCostFormatted' => 'R 0.00',
                'OrdFQDocID' => null,
                'StatusTypeCode' => null,
                'IsBookmark' => false,
                'IsRepairChecked' => false,
                'IsSalesFreehandQuotes' => false,
                'IsSalesOrder' => true,
                'SalesFQDocNumber' => null,
                'TotalWeight' => 0,
                'RevisionNumber' => 0,
                'SelectedStatusType' => null,
                'IsBookmarkChkVisible' => false,
                'IsBookmarkChecked' => false,
                'SelectedCreditNote' => null,
                'SalesOrderDocEntity' => null,
                'IsDeliveryMethodVisible' => true,
                'SelectedDeliveryMethod' => null,
                'IsActivateProcessingDoc' => true,
                'SelectedRevisionNumber' => '1',
                'IsAllowReceiptOnSalesOrder' => false,
                'IsFixDocNumber' => false,
                'SalesEntities' => $item_array,
                'IsCreditRequestConvert' => false,
                'NextInvoiceNumber' => 138637,
                'InvNumber' => '',
                'SONumber' => '',
                'DocumentType' => '',
                'Title' => '',
                'NextSONumber' => '185491',
                'NextCNNumber' => 101365,
                'CNNumber' => '',
                'CreditNote' => 'CREDIT NOTE',
                'CreditRequest' => 'CREDIT REQUEST',
                'NextCRNumber' => 9,
                'CRNumber' => '',
                'NextQuoteNumber' => 241,
                'QuoteNumber' => "",
                'IsConvertingDA' => false,
                'IsMultipleDA' => false,
                'ExistingDANumber' => null,
                'IsMaintainCenters' => false,
                'PL_VoucherIntegrationDisabled' => false,
                'IsCreditBtnSelected' => false,
                'OrigDocNumber' => null,
                'Provider' => 0,
                'IsAdjustingInv' => false,
                'IsDTLinesChanged' => false,
                'CustName' => $unique_id,
                'PL_NonIntegratedStock' => false,
                'TaxNotSetup' => false,
                'Tax' => $order_total_tax,
                'ReturnSubTotal' => 0,
                'ReturnTotalTax' => 0,
                'TaxLineList' => NULL,
                'Tuple' => NULL,
                'IsSPLineLevel' => false,
                'Module' => 0,
                'IsQuoteToSo' => false,
                'IsQuoteToInvoice' => false,
                'ExistingQuoteNumber' => '',
                'AuthorizeTrackingList' => [],
                'IsCustomerChanged' => false,
                'OldCustomerName' => $unique_id,
                'PL_DepartmentsEnabled' => true,
                'GlLinkedAccountAR' => NULL,
                'SplitPaymentCashAmount' => 0,
                'SplitPaymentChangeAmount' => 0,
                'ExistingSOSQNumber' => NULL,
                'PaymentPlanCashAmount' => 0,
                'PaymentPlanChangeAmount' => 0,
                'SplitPaymentChangeAmountFormatted' => NULL,
                'LinkedAcctDoc_CurrencyExchange' => NULL,
                'LinkedAcctDoc_CurrentEarnings' => NULL,
                'LinkedAcctDoc_DeliveryAdvice' => NULL,
                'IsSoToInvoice' => false,
                'ExistingSoNumber' => NULL,
                'IsSoNumberInv' => false,
                'SoNumberInv' => NULL,
                'SelectedPaymentIndex' => 0,
                'IsOnAccount' => false,
                'SelectedPayment' => NULL,
                'IsISTerm' => false,
                'AccountNumber' => NULL,
                'IsSplitPmtUsed' => false,
                'TempGuid' => '',
                'IsProjNotAllocAll' => false,
                'LinkedAccountSession_AcctNo' => NULL,
                'PL_HomeCurrencyCode' => $currency_code,
                'LinkedAcctDoc_Deposits' => NULL,
                'IsAutoApplyCreditNotes' => false,
                'PL_MulticurrencyEnabled' => true,
                'LotNumberNotAvailable' => NULL,
                'SerialLotNumberNotAvailable' => NULL,
                'DocumentTypeIncorrect' => NULL,
                'IsActivateERPIntegration' => false,
                'IsERPAR' => false,
                'CurrentUserRCNumber' => NULL,
                'DeleteSOLinesToBeCancelledList' => NULL,
                'REName' => NULL,
                'IsProfitCenterAutoAssign' => false,
                'ProfitCenterUID' => '00000000-0000-0000-0000-000000000000',
                'CentersRemoved' => 0,
                'IsMultipleSOSQ' => false,
                'IsPartialBackordersAllowed' => false,
                'BackOrderReasonCode' => NULL,
                'ExceptionData' => NULL,
                'IsMatrixType' => false,
                'RecordTag' => NULL,
                'IsApplyDiscount' => NULL,
                'MatrixTotal' => 0,
                'MatrixSubTotal' => 0,
                'MatrixSubTotalTag' => NULL,
                'MatrixVAT' => 0,
                'VatTemp' => 0,
                'SubTotalTemp' => 0,
                'DiscountVAT' => 0,
                'IsBookMarkChkVisible' => false,
                'IsCreditNoteReasonVisible' => false,
                'IsCreditNoteEnabled' => false,
                'OrderTotalNonAccountPaymentSplitPmt' => 0,
                'OrderTotalNonAccountPaymentPlanDeposit' => 0,
                'DueDate' => '2018-05-29T00:00:00',
                'ExceptionMessage' => NULL,
                'EDI' => '',
                'TotalMarginPercentage' => 0,
                'DeliveryLocationSelected' => 'Default',
                'CustomerSignature' => '',
                'CurrentLocation' => array(
                    'UserName' => $user->display_name,
                    'Latitude' => 23.035062,
                    'Longitude' => 72.50052,
                    'Email' => $order_billing_email,
                ),
            );

            $displayName = $user->display_name;

            /**
             * Call internal function to send order details
             */
            $this->pld_send_order_details($orderApiData, $order_id, $displayName, $customer_user_id);
        }
    }

    /**
     * Send order details to API on woocommerce_thankyou action
     *
     * @since    1.0.0
     */
    public function pld_send_order_details($order_details, $order_id, $displayName, $user_id) {
        $options = get_option('pld_api_options');

        if (
                $order_details && isset($options) && !empty($options) &&
                !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                !empty($options['pld_webstore_orders_api_path'])
        ) {

            /**
             * get api options and generate request based on that
             */
            $pld_webstore_endpoint = $options['pld_webstore_endpoint'];
            $pld_webstore_database = $options['pld_webstore_database'];
            $pld_webstore_orders_api_path = $options['pld_webstore_orders_api_path'];

            $order_api_data = json_encode($order_details);
            // print_r($order_api_data);
            /**
             * send API request via cURL
             */
            $ch = curl_init();
            $headerArray = array(
                "Content-Type: application/json",
                "auth-database: " . $pld_webstore_database,
            );

            /**
             * set the complete URL, to process the order on the external system
             */
            curl_setopt($ch, CURLOPT_URL, $pld_webstore_endpoint . $pld_webstore_orders_api_path);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $order_api_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
            $response = curl_exec($ch);
            /* Write orders logs */
            $upload_dir = wp_upload_dir();
            $orders_dirname = $upload_dir['basedir'] . '/palldium-api-logs/orders';
            if (!file_exists($orders_dirname)) {
                mkdir($orders_dirname, 0777, true);
            }

            $order_log_filename = $orders_dirname . '/order_' . $order_id . '.log';

            $logfile = fopen($order_log_filename, "a");
            $log_text = "[" . date('Y-m-d H:i:s') . "] : Sending Order details to Palladium API";
            $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : Order ID : " . $order_id;
            $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : User ID : " . $user_id;
            $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : User : " . $displayName;
            $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : API URL : " . $pld_webstore_endpoint . $pld_webstore_orders_api_path;
            $log_text .= "\n=====================";
            $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : Order Request in API : ";
            $log_text .= "\n" . $order_api_data;
            $log_text .= "\n=====================";
            $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : Response from API : ";
            $log_text .= "\n" . $response;
            $log_text .= "\n=====================\n";

            fwrite($logfile, $log_text);
            fclose($logfile);
            curl_close($ch);
            /* End - Write orders logs */

            // curl_close($ch);

            $jsonDecode = json_decode($response);
            if ($jsonDecode->IsSuccess == true) {
                update_post_meta($order_id, '_the_meta_key1', 'Yes');
                // print_r('done');
            }
            /**
             *  The handle response
             */
            if (!curl_errno($ch)) {
                /**
                 * success here
                 */
            }
        } else {
            /**
             * failed, No options found
             */
        }
    }

    /**
     * Get customer details on woocommerce_created_customer action
     *
     * @since    1.0.0
     */
    public function pld_manage_woocom_customer_details($customer_id) {
        update_user_meta($customer_id, 'register_flag', 1);
    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function pld_rest_api_customer() {

        /* Register route to import customers prices */
        $namespacePrice = 'import/';
        $basePrice = 'pld-customers-prices';

        register_rest_route($namespacePrice, '/' . $basePrice, array(
            array(
                'methods' => 'GET', //POST method // READABLE - GET method
                'callback' => array($this, 'import_customer_prices_from_api'),
            ),
        ));

         /* Register route to import customers prices */
         $namespacePrice_paging = 'import/';
         $basePrice_paging = 'pld-customers-prices-bypaging';
 
         register_rest_route($namespacePrice_paging, '/' . $basePrice_paging, array(
             array(
                 'methods' => 'GET', //POST method
                 'callback' => array($this, 'import_customer_prices_from_api_updated_date'),
             ),
         ));

        /* Register route to import sales discount matrix */
        $namespaceSalesDiscount = 'import/';
        $baseSalesDiscount = 'pld-sales-discount-matrix';

        register_rest_route($namespaceSalesDiscount, '/' . $baseSalesDiscount, array(
            array(
                'methods' => 'GET', //POST method // READABLE - GET method
                'callback' => array($this, 'import_sales_discount_matrix_from_api'),
            ),
        ));

        /* Register route to import customer tax code */
        $namespaceTaxCode = 'import/';
        $baseTaxCode = 'pld-customers-tax-code';

        register_rest_route($namespaceTaxCode, '/' . $baseTaxCode, array(
            array(
                'methods' => 'GET', //POST method // READABLE - GET method
                'callback' => array($this, 'import_customer_taxcode_from_api'),
            ),
        ));
    }

    /**
     * import cutomer data from api 
     */
    public function import_customer_from_api() {
        $options = get_option('pld_api_options');
        if (
                isset($options) && !empty($options) &&
                !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                !empty($options['pld_webstore_get_customer_from_api_path'])
        ) {

            $pld_webstore_endpoint = $options['pld_webstore_endpoint'];
            $pld_webstore_database = $options['pld_webstore_database'];
            $get_customer_from_api_path = $options['pld_webstore_get_customer_from_api_path'];


            $customer_last_import_timestamp_options = get_option('pld_customer_last_import_timestamp');

            $customer_last_import_timestamp = '';
            if (isset($customer_last_import_timestamp_options) && !empty($customer_last_import_timestamp_options)) {
                $customer_last_import_timestamp = $customer_last_import_timestamp_options;
                //$customer_last_import_timestamp = '2020-11-05 06:01:43';
            }

            $i = 1;
            while (1) {

                // Initiate curl session in a variable (resource)
                $curl_handle = curl_init();
                $url = $pld_webstore_endpoint . $get_customer_from_api_path;
                $data = array(
                    "PageIndex" => $i,
                    "PageSize" => 50,
                    "LastUpdated" => $customer_last_import_timestamp
                );

                $headerArray = array(
                    "Content-Type: application/json",
                    "auth-database: " . $pld_webstore_database,
                );
                $indexParameter = json_encode($data);
                curl_setopt($curl_handle, CURLOPT_URL, $url);

                // This option will return data as a string instead of direct output
                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $indexParameter);
                curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArray);

                // Execute curl & store data in a variable
                $curl_data = curl_exec($curl_handle);

                curl_close($curl_handle);

                // Decode JSON into PHP array
                $customers = json_decode($curl_data);
                $customers_Data = $customers->Data;
                $totalPage = $customers_Data[0]->TotalPages;
                $j = 0;

                foreach ($customers_Data as $customersData) {
                    $email = $customersData->CustomerEmail;
                    if ($email) {
                        if (count(explode(";", $email)) == 1) {
                            $CustomerName = $customersData->CustomerName;
                            $name = $customersData->Contact;
                            $Street1 = $customersData->Street1;
                            $Street2 = $customersData->Street2;
                            $City = $customersData->City;
                            //$Phone = $customersData->Phone;
                            $Phone = $customersData->CellPhone;
                            $CountryCode = $customersData->CountryCode;
                            $Country = $customersData->Country;
                            $Province = $customersData->Province;
                            $Postal = $customersData->Postal;
                            //$Company = $customersData->CustomerDescription;
                            $Pricelist = $customersData->Pricelist;
                            $custCategory = $customersData->CustCategory;
                            $taxCode = $customersData->TaxCode;

                            $user_email_data = get_user_by('email', $email); // return WP_User object otherwise return false if not found 
                            if (empty($user_email_data)) {
                                $userdata = array(
                                    'user_login' => $CustomerName,
                                    'user_email' => $email,
                                    'role' => 'customer',
                                    //'display_name' => $CustomerName,
                                    'user_pass' => NULL // When creating an user, `user_pass` is expected.
                                );
                                // insert new user
                                $user_id = wp_insert_user($userdata);
                                if (!is_wp_error($user_id)) {
                                    // You will need also to add this billing meta data
                                    update_user_meta($user_id, 'register_flag', 2);
                                    update_user_meta($user_id, 'customer_unique_id', $CustomerName);
                                    update_user_meta($user_id, 'billing_email', $email);
                                    update_user_meta($user_id, 'billing_address_1', $Street1);
                                    update_user_meta($user_id, 'billing_address_2', $Street2);
                                    update_user_meta($user_id, 'billing_city', $City);
                                    update_user_meta($user_id, 'billing_postcode', $Postal);
                                    //update_user_meta($user_id, 'billing_country', $Country);
                                    update_user_meta($user_id, 'billing_country', $CountryCode);
                                    update_user_meta($user_id, 'billing_state', $Province);
                                    update_user_meta($user_id, 'billing_phone', $Phone);
                                    //update_user_meta($user_id, 'billing_company', $Company);
                                    update_user_meta($user_id, 'pld_customer_pricelist', $Pricelist);
                                    update_user_meta($user_id, 'pld_customer_cust_category', $custCategory);
                                    update_user_meta($user_id, 'pld_customer_tax_code', $taxCode);
                                }
                            } else {
                                $userData = $user_email_data->data;
                                $user_id = $userData->ID;
                                if ($user_email_data->roles[0] == 'administrator') {
                                    $roles = 'administrator';
                                } else {
                                    $roles = 'customer';
                                }
                                $user_array = array(
                                    'ID' => $user_id,
                                    'user_login' => $CustomerName,
                                    'user_email' => $email,
                                    'role' => $roles,
                                    'display_name' => $CustomerName,
                                );
                                // update user
                                wp_update_user($user_array);
                                update_user_meta($user_id, 'register_flag', 2);
                                update_user_meta($user_id, 'customer_unique_id', $CustomerName);
                                update_user_meta($user_id, 'billing_address_1', $Street1);
                                update_user_meta($user_id, 'billing_address_2', $Street2);
                                update_user_meta($user_id, 'billing_city', $City);
                                update_user_meta($user_id, 'billing_postcode', $Postal);
                                //update_user_meta($user_id, 'billing_country', $Country);
                                update_user_meta($user_id, 'billing_country', $CountryCode);
                                update_user_meta($user_id, 'billing_state', $Province);
                                update_user_meta($user_id, 'billing_phone', $Phone);
                                //update_user_meta($user_id, 'billing_company', $Company);
                                update_user_meta($user_id, 'pld_customer_pricelist', $Pricelist);
                                update_user_meta($user_id, 'pld_customer_cust_category', $custCategory);
                                update_user_meta($user_id, 'pld_customer_tax_code', $taxCode);
                            }

                            $j++;
                        }
                    }
                }

                if ($totalPage <= $i) {
                    break;
                }
                $i++;
            }

            // Store the time stamp when the product cron run
            update_option('pld_customer_last_import_timestamp', date('Y-m-d h:i:s'));


            return new WP_REST_Response(array(
                'status' => 'success',
                'response' => 'Cron run successfully.',
                'body_response' => 200
            ));
        }
    }
	
    /**
     * import product data from api 
     */
    public function import_product_from_api() {
        $options = get_option('pld_api_options');
        if (
                isset($options) && !empty($options) &&
                !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                !empty($options['pld_webstore_get_product_from_api_path'])
        ) {

            $pld_webstore_endpoint = $options['pld_webstore_endpoint'];
            $pld_webstore_database = $options['pld_webstore_database'];
            $pld_webstore_get_product_from_api_path = $options['pld_webstore_get_product_from_api_path'];
            //$pld_webstore_product_key = $options['pld_webstore_product_key'];
            //$pld_webstore_product_secret = $options['pld_webstore_product_secret'];

            $product_last_import_timestamp_options = get_option('pld_product_last_import_timestamp');

            $product_last_import_timestamp = '';
            if (isset($product_last_import_timestamp_options) && !empty($product_last_import_timestamp_options)) {
                //$product_last_import_timestamp = $product_last_import_timestamp_options;
                $product_last_import_timestamp = '2010-11-05 06:01:43';
            }
            $i = 1;

            global $wpdb;
            $tablename_posts = $wpdb->prefix . 'posts';

            while (1) {
                // Initiate curl session in a variable (resource)
                $curl_handle = curl_init();
                $url = $pld_webstore_endpoint . $pld_webstore_get_product_from_api_path;
                $data = '["","","","","2019-03-06",0,false,25,' . $i . ',"","' . $product_last_import_timestamp . '"]';

                $headerArray = array(
                    "Content-Type: application/json",
                    "auth-database: " . $pld_webstore_database,
                );
                $indexParameter = json_encode($data);
                curl_setopt($curl_handle, CURLOPT_URL, $url);
                // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArray);

                // Execute curl & store data in a variable
                $curl_data = curl_exec($curl_handle);

                curl_close($curl_handle);

                // Decode JSON into PHP array
                $products = json_decode($curl_data);
                $products = $products->Data;

                if (isset($products) && !empty($products)) {
                    $totalPages = $products[0]->TotalPages;

                    $url = explode('/', $pld_webstore_endpoint);
                    array_pop($url);
                    $imagepath = implode('/', $url);

                    foreach ($products as $product) {
                        $title = trim($product->Description);
                        $sku = trim($product->PartNumber);
                        $height = $product->Height;
                        $width = $product->Width;
                        //$category = $product->Category;
                        $category = ucwords(strtolower($product->CategoryDesc));
                        $type = $product->Type;
                        $mass = $product->Mass;
                        $qty = $product->SumOnHand;
                        //$subCategory = $product->SubCategory;
                        $subCategory = ucwords(strtolower($product->SubCategory));

                        // check product stock
                        /* if ($product->SumOnHand > 0) {
                          $is_in_stock = "instock";
                          } else {
                          $is_in_stock = "outofstock";
                          } */

                        // check product active or not
                        if ($product->IsInactive == false) {
                            $postStatus = 'publish';
                        } else {
                            $postStatus = 'draft';
                        }

                        // check product image
                        if (!empty($product->ProductImagesPath)) {
                            $productImage = $product->ProductImagesPath[0];
                        } else {
                            $productImage = $product->ImagePath;
                        }

                        $productImage = $imagepath . $productImage;

                        // check product type
                        $variable_product = false;
                        if (empty($product->Field6)) {
                            $productType = 'simple';
                        } else {
                            $variable_product = true;
                            $productType = 'variable';
                        }

                        $webstoreProduct = $this->get_product_by_sku($sku);

                        // If variable type product
                        // create attribute / terms and variation product
                        if ($variable_product) {
                            // create / update product and variation here
                            // if found product by sku then here and update details

                            $attr_label = 'size';
                            $attr_slug = sanitize_title($attr_label);

                            if ($webstoreProduct) {

                                $item_type = $product->Field2 . $product->Field3;
                                $variation_id = $webstoreProduct;
                                update_post_meta($variation_id, '_regular_price', 0.0);
                                update_post_meta($variation_id, '_price', 0.0);
                                /* update_post_meta($variation_id, '_stock_qty', $is_in_stock); */
                                update_post_meta($variation_id, '_sku', $sku);
                                update_post_meta($variation_id, 'attribute_' . $attr_slug, $item_type);

                                update_post_meta($variation_id, '_width', $width);
                                update_post_meta($variation_id, '_height', $height);
                                update_post_meta($variation_id, '_stock', $qty);
                                update_post_meta($variation_id, '_weight', $mass);

                                update_post_meta($variation_id, 'category', $category);
                                update_post_meta($variation_id, 'subCategory', $subCategory);
                                update_post_meta($variation_id, 'productImage', $productImage);

                                update_post_meta($variation_id, 'Field1', $product->Field1);
                                update_post_meta($variation_id, 'Field2', $product->Field2);
                                update_post_meta($variation_id, 'Field3', $product->Field3);
                                update_post_meta($variation_id, 'Field4', $product->Field4);
                                update_post_meta($variation_id, 'Field5', $product->Field5);
                                update_post_meta($variation_id, 'Field6', $product->Field6);

                                update_post_meta($variation_id, '_variation_description', $title);

                                $wpdb->query($wpdb->prepare("UPDATE $tablename_posts 
                                        SET post_title='%s' 
                                        WHERE ID = %s", $title, $variation_id));
                            } else {
                                // no product found by current product variation sku
                                $item_type = $product->Field2 . $product->Field3;

                                $variation = array(
                                    'post_title' => '',
                                    'post_content' => 'Variation of ' . $sku,
                                    'post_status' => 'publish',
                                    'post_type' => 'product_variation'
                                );

                                $variation_id = wp_insert_post($variation);

                                update_post_meta($variation_id, '_regular_price', 0.0);
                                update_post_meta($variation_id, '_price', 0.0);
                                /* update_post_meta($variation_id, '_stock_qty', $is_in_stock); */
                                update_post_meta($variation_id, '_sku', $sku);
                                update_post_meta($variation_id, 'attribute_' . $attr_slug, $item_type);

                                update_post_meta($variation_id, '_width', $width);
                                update_post_meta($variation_id, '_height', $height);
                                update_post_meta($variation_id, '_stock', $qty);
                                update_post_meta($variation_id, '_weight', $mass);

                                update_post_meta($variation_id, 'category', $category);
                                update_post_meta($variation_id, 'subCategory', $subCategory);
                                update_post_meta($variation_id, 'productImage', $productImage);

                                update_post_meta($variation_id, 'Field1', $product->Field1);
                                update_post_meta($variation_id, 'Field2', $product->Field2);
                                update_post_meta($variation_id, 'Field3', $product->Field3);
                                update_post_meta($variation_id, 'Field4', $product->Field4);
                                update_post_meta($variation_id, 'Field5', $product->Field5);
                                update_post_meta($variation_id, 'Field6', $product->Field6);

                                update_post_meta($variation_id, '_variation_description', $title);

                                $wpdb->query($wpdb->prepare("UPDATE $tablename_posts 
                                        SET post_title='%s' 
                                        WHERE ID = %s", $title, $variation_id));
                            }
                        } else {
                            // If normal product then go here 
                            if (empty($webstoreProduct)) {

                                //Create Post
                                $product_id = wp_insert_post(array(
                                    'post_title' => $title,
                                    'post_content' => $title,
                                    'post_status' => $postStatus,
                                    'post_type' => "product",
                                ));

                                if (!is_wp_error($product_id)) {
                                    update_post_meta($product_id, '_regular_price', '0.0');
                                    update_post_meta($product_id, '_price', 0.0);
                                    update_post_meta($product_id, '_sku', $sku);
                                    /* update_post_meta($product_id, '_stock_status', $is_in_stock); */
                                    update_post_meta($product_id, '_weight', $mass);
                                    wp_set_object_terms($product_id, $productType, 'product_type');

                                    update_post_meta($product_id, '_width', $mass);
                                    update_post_meta($product_id, '_height', $height);
                                    update_post_meta($product_id, '_stock', $qty);

                                    update_post_meta($product_id, 'Field1', $product->Field1);
                                    update_post_meta($product_id, 'Field2', $product->Field2);
                                    update_post_meta($product_id, 'Field3', $product->Field3);
                                    update_post_meta($product_id, 'Field4', $product->Field4);
                                    update_post_meta($product_id, 'Field5', $product->Field5);
                                    update_post_meta($product_id, 'Field6', $product->Field6);

                                    //set Product Category
                                    $existing_category = get_term_by('name', $category, 'product_cat');
                                    if ($existing_category) {
                                        $existing_category_id = $existing_category->term_id;
                                    } else {
                                        $category_detail = wp_insert_term($category, 'product_cat');
                                        $existing_category_id = $category_detail['term_id'];
                                    }
                                    $category_detail = wp_set_object_terms($product_id, $existing_category_id, 'product_cat');
                                    if (!empty($subCategory)) {
                                        if (!term_exists($subCategory, 'product_cat')) {
                                            $subcategory_detail = wp_insert_term($subCategory, 'product_cat', array('parent' => $existing_category_id));
                                            wp_set_object_terms($product_id, array($existing_category_id, $subcategory_detail['term_id']), 'product_cat');
                                        } else {
                                            $existing_subcategory = get_term_by('name', $subCategory, 'product_cat');
                                            wp_set_object_terms($product_id, array($existing_category_id, $existing_subcategory->term_id), 'product_cat');
                                        }
                                    }

                                    // Product image
                                    /* $productImage = $imagepath . $productImage;
                                      $productImageWithoutFilter = explode($imagepath, $productImage);
                                      $productImage = end($productImageWithoutFilter);
                                      $productImage = $imagepath . $productImage;

                                      $imageAttachID = $this->pld_insert_attachment_from_url($productImage);
                                      set_post_thumbnail($product_id, $imageAttachID); */
                                }
                            } else {

                                if (is_object($webstoreProduct)) {
                                    $product_id = $webstoreProduct->get_id();


                                    $productImage = $imagepath . $productImage;

                                    $product_id = wp_update_post(array(
                                        'ID' => $product_id,
                                        'post_title' => $title,
                                        'post_content' => $title,
                                        'post_status' => $postStatus,
                                        'post_type' => "product",
                                    ));
                                    if (!is_wp_error($product_id)) {
                                        update_post_meta($product_id, '_regular_price', '0.0');
                                        update_post_meta($product_id, '_price', 0.0);
                                        update_post_meta($product_id, '_sku', $sku);
                                        /* update_post_meta($product_id, '_stock_status', $is_in_stock); */
                                        update_post_meta($product_id, '_weight', $mass);
                                        wp_set_object_terms($product_id, $productType, 'product_type');

                                        update_post_meta($product_id, '_width', $mass);
                                        update_post_meta($product_id, '_height', $height);
                                        update_post_meta($product_id, '_stock', $qty);

                                        update_post_meta($product_id, 'Field1', $product->Field1);
                                        update_post_meta($product_id, 'Field2', $product->Field2);
                                        update_post_meta($product_id, 'Field3', $product->Field3);
                                        update_post_meta($product_id, 'Field4', $product->Field4);
                                        update_post_meta($product_id, 'Field5', $product->Field5);
                                        update_post_meta($product_id, 'Field6', $product->Field6);

                                        //set Product Category
                                        $existing_category = get_term_by('name', $category, 'product_cat');
                                        if ($existing_category) {
                                            $existing_category_id = $existing_category->term_id;
                                        } else {
                                            $category_detail = wp_insert_term($category, 'product_cat');
                                            $existing_category_id = $category_detail['term_id'];
                                        }
                                        $category_detail = wp_set_object_terms($product_id, $existing_category_id, 'product_cat');
                                        if (!empty($subCategory)) {
                                            if (!term_exists($subCategory, 'product_cat')) {
                                                $subcategory_detail = wp_insert_term($subCategory, 'product_cat', array('parent' => $existing_category_id));
                                                wp_set_object_terms($product_id, array($existing_category_id, $subcategory_detail['term_id']), 'product_cat');
                                            } else {
                                                $existing_subcategory = get_term_by('name', $subCategory, 'product_cat');
                                                if (!$existing_subcategory) {
                                                    $subcategory_detail = wp_insert_term($subCategory, 'product_cat', array('parent' => $existing_category_id));
                                                    wp_set_object_terms($product_id, array($existing_category_id, $subcategory_detail['term_id']), 'product_cat');
                                                } else {
                                                    wp_set_object_terms($product_id, array($existing_category_id, $existing_subcategory->term_id), 'product_cat');
                                                }
                                            }
                                        }

                                        // Product image
                                        $productImage = $imagepath . $productImage;
                                        $productImageWithoutFilter = explode($imagepath, $productImage);
                                        $productImage = end($productImageWithoutFilter);
                                        $productImage = $imagepath . $productImage;

                                        /* if (has_post_thumbnail($product_id)) {
                                          $attachment_id = get_post_thumbnail_id($product_id);
                                          $kd_featured_image_url = wp_get_attachment_url($attachment_id);

                                          $image_url_base = basename($productImage);
                                          $kd_featured_image_base = basename($kd_featured_image_url);

                                          if ($kd_featured_image_base != $image_url_base) {
                                          wp_delete_attachment($attachment_id, true);
                                          $imageAttachID = $this->pld_insert_attachment_from_url($productImage);
                                          set_post_thumbnail($product_id, $imageAttachID);
                                          }
                                          } else {
                                          $imageAttachID = $this->pld_insert_attachment_from_url($productImage);
                                          set_post_thumbnail($product_id, $imageAttachID);
                                          } */
                                    }
                                }
                            }
                        }
                    }
                    // end of foreach
                    //if (50 <= $i) {
                    if ($totalPages <= $i) {
                        break;
                    }
                    $i++;
                } else {
                    break;
                }
            }
            // end of while loop
            // Store the time stamp when the product cron run
            update_option('pld_product_last_import_timestamp', date('Y-m-d h:i:s'));

            return new WP_REST_Response(array(
                'status' => 'success',
                'response' => 'Cron run successfully.',
                'body_response' => 200
            ));
        }
    }

    /**
     * Insert an attachment from an URL address.
     *
     * @param  String $url
     * @param  Int    $parent_post_id
     * @return Int    Attachment ID
     */
    function pld_insert_attachment_from_url($url, $parent_post_id = null) {

        if (!class_exists('WP_Http'))
            include_once( ABSPATH . WPINC . '/class-http.php' );

        $http = new WP_Http();
        $response = $http->request($url);
        if (is_wp_error($response) || $response['response']['code'] != 200) {
            return false;
        }

        $upload = wp_upload_bits(basename($url), null, $response['body']);
        if (!empty($upload['error'])) {
            return false;
        }

        $file_path = $upload['file'];
        $file_name = basename($file_path);
        $file_type = wp_check_filetype($file_name, null);
        $attachment_title = sanitize_file_name(pathinfo($file_name, PATHINFO_FILENAME));
        $wp_upload_dir = wp_upload_dir();

        $post_info = array(
            'guid' => $wp_upload_dir['url'] . '/' . $file_name,
            'post_mime_type' => $file_type['type'],
            'post_title' => $attachment_title,
            'post_content' => '',
            'post_status' => 'inherit',
        );

        // Create the attachment
        $attach_id = wp_insert_attachment($post_info, $file_path, $parent_post_id);

        // Include image.php
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);

        // Assign metadata to attachment
        wp_update_attachment_metadata($attach_id, $attach_data);

        return $attach_id;
    }


    public function get_product_by_sku($sku) {

        global $wpdb;

        $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku));
        if (get_post_type($product_id) == 'product_variation') {
            return $product_id;
        }
        if ($product_id) {
            return new WC_Product($product_id);
        }

        return null;
    }

    // get currency from country code
    public function pld_get_country_currency($key) {
        $country_currency = array(
            'AF' => 'AFN',
            'AL' => 'ALL',
            'DZ' => 'DZD',
            'AS' => 'USD',
            'AD' => 'EUR',
            'AO' => 'AOA',
            'AI' => 'XCD',
            'AQ' => 'XCD',
            'AG' => 'XCD',
            'AR' => 'ARS',
            'AM' => 'AMD',
            'AW' => 'AWG',
            'AU' => 'AUD',
            'AT' => 'EUR',
            'AZ' => 'AZN',
            'BS' => 'BSD',
            'BH' => 'BHD',
            'BD' => 'BDT',
            'BB' => 'BBD',
            'BY' => 'BYR',
            'BE' => 'EUR',
            'BZ' => 'BZD',
            'BJ' => 'XOF',
            'BM' => 'BMD',
            'BT' => 'BTN',
            'BO' => 'BOB',
            'BA' => 'BAM',
            'BW' => 'BWP',
            'BV' => 'NOK',
            'BR' => 'BRL',
            'IO' => 'USD',
            'BN' => 'BND',
            'BG' => 'BGN',
            'BF' => 'XOF',
            'BI' => 'BIF',
            'KH' => 'KHR',
            'CM' => 'XAF',
            'CA' => 'CAD',
            'CV' => 'CVE',
            'KY' => 'KYD',
            'CF' => 'XAF',
            'TD' => 'XAF',
            'CL' => 'CLP',
            'CN' => 'CNY',
            'HK' => 'HKD',
            'CX' => 'AUD',
            'CC' => 'AUD',
            'CO' => 'COP',
            'KM' => 'KMF',
            'CG' => 'XAF',
            'CD' => 'CDF',
            'CK' => 'NZD',
            'CR' => 'CRC',
            'HR' => 'HRK',
            'CU' => 'CUP',
            'CY' => 'EUR',
            'CZ' => 'CZK',
            'DK' => 'DKK',
            'DJ' => 'DJF',
            'DM' => 'XCD',
            'DO' => 'DOP',
            'EC' => 'ECS',
            'EG' => 'EGP',
            'SV' => 'SVC',
            'GQ' => 'XAF',
            'ER' => 'ERN',
            'EE' => 'EUR',
            'ET' => 'ETB',
            'FK' => 'FKP',
            'FO' => 'DKK',
            'FJ' => 'FJD',
            'FI' => 'EUR',
            'FR' => 'EUR',
            'GF' => 'EUR',
            'TF' => 'EUR',
            'GA' => 'XAF',
            'GM' => 'GMD',
            'GE' => 'GEL',
            'DE' => 'EUR',
            'GH' => 'GHS',
            'GI' => 'GIP',
            'GR' => 'EUR',
            'GL' => 'DKK',
            'GD' => 'XCD',
            'GP' => 'EUR',
            'GU' => 'USD',
            'GT' => 'QTQ',
            'GG' => 'GGP',
            'GN' => 'GNF',
            'GW' => 'GWP',
            'GY' => 'GYD',
            'HT' => 'HTG',
            'HM' => 'AUD',
            'HN' => 'HNL',
            'HU' => 'HUF',
            'IS' => 'ISK',
            'IN' => 'INR',
            'ID' => 'IDR',
            'IR' => 'IRR',
            'IQ' => 'IQD',
            'IE' => 'EUR',
            'IM' => 'GBP',
            'IL' => 'ILS',
            'IT' => 'EUR',
            'JM' => 'JMD',
            'JP' => 'JPY',
            'JE' => 'GBP',
            'JO' => 'JOD',
            'KZ' => 'KZT',
            'KE' => 'KES',
            'KI' => 'AUD',
            'KP' => 'KPW',
            'KR' => 'KRW',
            'KW' => 'KWD',
            'KG' => 'KGS',
            'LA' => 'LAK',
            'LV' => 'EUR',
            'LB' => 'LBP',
            'LS' => 'LSL',
            'LR' => 'LRD',
            'LY' => 'LYD',
            'LI' => 'CHF',
            'LT' => 'EUR',
            'LU' => 'EUR',
            'MK' => 'MKD',
            'MG' => 'MGF',
            'MW' => 'MWK',
            'MY' => 'MYR',
            'MV' => 'MVR',
            'ML' => 'XOF',
            'MT' => 'EUR',
            'MH' => 'USD',
            'MQ' => 'EUR',
            'MR' => 'MRO',
            'MU' => 'MUR',
            'YT' => 'EUR',
            'MX' => 'MXN',
            'FM' => 'USD',
            'MD' => 'MDL',
            'MC' => 'EUR',
            'MN' => 'MNT',
            'ME' => 'EUR',
            'MS' => 'XCD',
            'MA' => 'MAD',
            'MZ' => 'MZN',
            'MM' => 'MMK',
            'NA' => 'NAD',
            'NR' => 'AUD',
            'NP' => 'NPR',
            'NL' => 'EUR',
            'AN' => 'ANG',
            'NC' => 'XPF',
            'NZ' => 'NZD',
            'NI' => 'NIO',
            'NE' => 'XOF',
            'NG' => 'NGN',
            'NU' => 'NZD',
            'NF' => 'AUD',
            'MP' => 'USD',
            'NO' => 'NOK',
            'OM' => 'OMR',
            'PK' => 'PKR',
            'PW' => 'USD',
            'PA' => 'PAB',
            'PG' => 'PGK',
            'PY' => 'PYG',
            'PE' => 'PEN',
            'PH' => 'PHP',
            'PN' => 'NZD',
            'PL' => 'PLN',
            'PT' => 'EUR',
            'PR' => 'USD',
            'QA' => 'QAR',
            'RE' => 'EUR',
            'RO' => 'RON',
            'RU' => 'RUB',
            'RW' => 'RWF',
            'SH' => 'SHP',
            'KN' => 'XCD',
            'LC' => 'XCD',
            'PM' => 'EUR',
            'VC' => 'XCD',
            'WS' => 'WST',
            'SM' => 'EUR',
            'ST' => 'STD',
            'SA' => 'SAR',
            'SN' => 'XOF',
            'RS' => 'RSD',
            'SC' => 'SCR',
            'SL' => 'SLL',
            'SG' => 'SGD',
            'SK' => 'EUR',
            'SI' => 'EUR',
            'SB' => 'SBD',
            'SO' => 'SOS',
            'ZA' => 'ZAR',
            'GS' => 'GBP',
            'SS' => 'SSP',
            'ES' => 'EUR',
            'LK' => 'LKR',
            'SD' => 'SDG',
            'SR' => 'SRD',
            'SJ' => 'NOK',
            'SZ' => 'SZL',
            'SE' => 'SEK',
            'CH' => 'CHF',
            'SY' => 'SYP',
            'TW' => 'TWD',
            'TJ' => 'TJS',
            'TZ' => 'TZS',
            'TH' => 'THB',
            'TG' => 'XOF',
            'TK' => 'NZD',
            'TO' => 'TOP',
            'TT' => 'TTD',
            'TN' => 'TND',
            'TR' => 'TRY',
            'TM' => 'TMT',
            'TC' => 'USD',
            'TV' => 'AUD',
            'UG' => 'UGX',
            'UA' => 'UAH',
            'AE' => 'AED',
            'GB' => 'GBP',
            'US' => 'USD',
            'UM' => 'USD',
            'UY' => 'UYU',
            'UZ' => 'UZS',
            'VU' => 'VUV',
            'VE' => 'VEF',
            'VN' => 'VND',
            'VI' => 'USD',
            'WF' => 'XPF',
            'EH' => 'MAD',
            'YE' => 'YER',
            'ZM' => 'ZMW',
            'ZW' => 'ZWD',
        );
        if (array_key_exists($key, $country_currency)) {
            $currency = $country_currency[$key];
        }
        return $currency;
    }

    // get country full name from country code
    public function pld_show_country($key) {
        $country_array = array(
            "AF" => "Afghanistan", "AL" => "Albania", "DZ" => "Algeria", "AS" => "American Samoa", "AD" => "Andorra", "AO" => "Angola", "AI" => "Anguilla", "AQ" => "Antarctica", "AG" => "Antigua and Barbuda", "AR" => "Argentina", "AM" => "Armenia", "AW" => "Aruba", "AU" => "Australia", "AT" => "Austria", "AZ" => "Azerbaijan", "BS" => "Bahamas", "BH" => "Bahrain", "BD" => "Bangladesh", "BB" => "Barbados", "BY" => "Belarus", "BE" => "Belgium", "BZ" => "Belize", "BJ" => "Benin", "BM" => "Bermuda", "BT" => "Bhutan", "BO" => "Bolivia", "BA" => "Bosnia and Herzegovina", "BW" => "Botswana", "BV" => "Bouvet Island", "BR" => "Brazil", "BQ" => "British Antarctic Territory", "IO" => "British Indian Ocean Territory", "VG" => "British Virgin Islands", "BN" => "Brunei", "BG" => "Bulgaria", "BF" => "Burkina Faso", "BI" => "Burundi", "KH" => "Cambodia", "CM" => "Cameroon", "CA" => "Canada", "CT" => "Canton and Enderbury Islands", "CV" => "Cape Verde", "KY" => "Cayman Islands", "CF" => "Central African Republic", "TD" => "Chad", "CL" => "Chile", "CN" => "China", "CX" => "Christmas Island", "CC" => "Cocos [Keeling] Islands", "CO" => "Colombia", "KM" => "Comoros", "CG" => "Congo - Brazzaville", "CD" => "Congo - Kinshasa", "CK" => "Cook Islands", "CR" => "Costa Rica", "HR" => "Croatia", "CU" => "Cuba", "CY" => "Cyprus", "CZ" => "Czech Republic", "CI" => "Côte d’Ivoire", "DK" => "Denmark", "DJ" => "Djibouti", "DM" => "Dominica", "DO" => "Dominican Republic", "NQ" => "Dronning Maud Land", "DD" => "East Germany", "EC" => "Ecuador", "EG" => "Egypt", "SV" => "El Salvador", "GQ" => "Equatorial Guinea", "ER" => "Eritrea", "EE" => "Estonia", "ET" => "Ethiopia", "FK" => "Falkland Islands", "FO" => "Faroe Islands", "FJ" => "Fiji", "FI" => "Finland", "FR" => "France", "GF" => "French Guiana", "PF" => "French Polynesia", "TF" => "French Southern Territories", "FQ" => "French Southern and Antarctic Territories", "GA" => "Gabon", "GM" => "Gambia", "GE" => "Georgia", "DE" => "Germany", "GH" => "Ghana", "GI" => "Gibraltar", "GR" => "Greece", "GL" => "Greenland", "GD" => "Grenada", "GP" => "Guadeloupe", "GU" => "Guam", "GT" => "Guatemala", "GG" => "Guernsey", "GN" => "Guinea", "GW" => "Guinea-Bissau", "GY" => "Guyana", "HT" => "Haiti", "HM" => "Heard Island and McDonald Islands", "HN" => "Honduras", "HK" => "Hong Kong SAR China", "HU" => "Hungary", "IS" => "Iceland", "IN" => "India", "ID" => "Indonesia", "IR" => "Iran", "IQ" => "Iraq", "IE" => "Ireland", "IM" => "Isle of Man", "IL" => "Israel", "IT" => "Italy", "JM" => "Jamaica", "JP" => "Japan", "JE" => "Jersey", "JT" => "Johnston Island", "JO" => "Jordan", "KZ" => "Kazakhstan", "KE" => "Kenya", "KI" => "Kiribati", "KW" => "Kuwait", "KG" => "Kyrgyzstan", "LA" => "Laos", "LV" => "Latvia", "LB" => "Lebanon", "LS" => "Lesotho", "LR" => "Liberia", "LY" => "Libya", "LI" => "Liechtenstein", "LT" => "Lithuania", "LU" => "Luxembourg", "MO" => "Macau SAR China", "MK" => "Macedonia", "MG" => "Madagascar", "MW" => "Malawi", "MY" => "Malaysia", "MV" => "Maldives", "ML" => "Mali", "MT" => "Malta", "MH" => "Marshall Islands", "MQ" => "Martinique", "MR" => "Mauritania", "MU" => "Mauritius", "YT" => "Mayotte", "FX" => "Metropolitan France", "MX" => "Mexico", "FM" => "Micronesia", "MI" => "Midway Islands", "MD" => "Moldova", "MC" => "Monaco", "MN" => "Mongolia", "ME" => "Montenegro", "MS" => "Montserrat", "MA" => "Morocco", "MZ" => "Mozambique", "MM" => "Myanmar [Burma]", "NA" => "Namibia", "NR" => "Nauru", "NP" => "Nepal", "NL" => "Netherlands", "AN" => "Netherlands Antilles", "NT" => "Neutral Zone", "NC" => "New Caledonia", "NZ" => "New Zealand", "NI" => "Nicaragua", "NE" => "Niger", "NG" => "Nigeria", "NU" => "Niue", "NF" => "Norfolk Island", "KP" => "North Korea", "VD" => "North Vietnam", "MP" => "Northern Mariana Islands", "NO" => "Norway", "OM" => "Oman", "PC" => "Pacific Islands Trust Territory", "PK" => "Pakistan", "PW" => "Palau", "PS" => "Palestinian Territories", "PA" => "Panama", "PZ" => "Panama Canal Zone", "PG" => "Papua New Guinea", "PY" => "Paraguay", "YD" => "People's Democratic Republic of Yemen", "PE" => "Peru", "PH" => "Philippines", "PN" => "Pitcairn Islands", "PL" => "Poland", "PT" => "Portugal", "PR" => "Puerto Rico", "QA" => "Qatar", "RO" => "Romania", "RU" => "Russia", "RW" => "Rwanda", "RE" => "Réunion", "BL" => "Saint Barthélemy", "SH" => "Saint Helena", "KN" => "Saint Kitts and Nevis", "LC" => "Saint Lucia", "MF" => "Saint Martin", "PM" => "Saint Pierre and Miquelon", "VC" => "Saint Vincent and the Grenadines", "WS" => "Samoa", "SM" => "San Marino", "SA" => "Saudi Arabia", "SN" => "Senegal", "RS" => "Serbia", "CS" => "Serbia and Montenegro", "SC" => "Seychelles", "SL" => "Sierra Leone", "SG" => "Singapore", "SK" => "Slovakia", "SI" => "Slovenia", "SB" => "Solomon Islands", "SO" => "Somalia", "ZA" => "South Africa", "GS" => "South Georgia and the South Sandwich Islands", "KR" => "South Korea", "ES" => "Spain", "LK" => "Sri Lanka", "SD" => "Sudan", "SR" => "Suriname", "SJ" => "Svalbard and Jan Mayen", "SZ" => "Swaziland", "SE" => "Sweden", "CH" => "Switzerland", "SY" => "Syria", "ST" => "São Tomé and Príncipe", "TW" => "Taiwan", "TJ" => "Tajikistan", "TZ" => "Tanzania", "TH" => "Thailand", "TL" => "Timor-Leste", "TG" => "Togo", "TK" => "Tokelau", "TO" => "Tonga", "TT" => "Trinidad and Tobago", "TN" => "Tunisia", "TR" => "Turkey", "TM" => "Turkmenistan", "TC" => "Turks and Caicos Islands", "TV" => "Tuvalu", "UM" => "U.S. Minor Outlying Islands", "PU" => "U.S. Miscellaneous Pacific Islands", "VI" => "U.S. Virgin Islands", "UG" => "Uganda", "UA" => "Ukraine", "SU" => "Union of Soviet Socialist Republics", "AE" => "United Arab Emirates", "GB" => "United Kingdom", "US" => "United States", "ZZ" => "Unknown or Invalid Region", "UY" => "Uruguay", "UZ" => "Uzbekistan", "VU" => "Vanuatu", "VA" => "Vatican City", "VE" => "Venezuela", "VN" => "Vietnam", "WK" => "Wake Island", "WF" => "Wallis and Futuna", "EH" => "Western Sahara", "YE" => "Yemen", "ZM" => "Zambia", "ZW" => "Zimbabwe", "AX" => "Åland Islands",
        );
        if (array_key_exists($key, $country_array)) {
            $countryFull = $country_array[$key];
        }
        return $countryFull;
    }

    public function does_file_exists($filename) {
        global $wpdb;

        return intval($wpdb->get_var("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%/$filename'"));
    }

    /**
     * Import customer price data from api 
     */
    public function import_customer_prices_from_api() {
        $options = get_option('pld_api_options');
        if (
                isset($options) && !empty($options) &&
                !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                !empty($options['pld_webstore_get_pricelist_from_api_path'])
        ) {

            $pld_webstore_endpoint = $options['pld_webstore_endpoint'];
            $pld_webstore_database = $options['pld_webstore_database'];
            $pld_webstore_get_pricelist_from_api_path = $options['pld_webstore_get_pricelist_from_api_path'];

            // Initiate curl session in a variable (resource)
            $curl_handle = curl_init();
            $url = $pld_webstore_endpoint . $pld_webstore_get_pricelist_from_api_path;

            $headerArray = array(
                "Content-Type: application/json",
                "auth-database: " . $pld_webstore_database,
            );
            curl_setopt($curl_handle, CURLOPT_URL, $url);

            // This option will return data as a string instead of direct output
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArray);

            // Execute curl & store data in a variable
            $curl_data = curl_exec($curl_handle);

            curl_close($curl_handle);

            // Decode JSON into PHP array
            $customers_prices = json_decode($curl_data);
            $customers_prices_Data = $customers_prices->Data;

            // If data is received from API
            if ($customers_prices_Data) {
                global $wpdb;
                $tablename = $wpdb->prefix . 'palladium_customer_prices';

                // Check if table exist or not, if not create it first
                $table_exist_query = $wpdb->prepare('SHOW TABLES LIKE %s', $tablename);
                if (!$wpdb->get_var($table_exist_query) == $tablename) {
                    $charset_collate = $wpdb->get_charset_collate();
                    $pld_create_table_query = "
                        CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}palladium_customer_prices` (
                            `customerprice_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Customerprice ID',
                            `description` varchar(255) DEFAULT NULL COMMENT 'Customerprice Description',
                            `product_sku` varchar(255) DEFAULT NULL COMMENT 'Customerprice Product Sku',
                            `lastpp_unit` int(11) DEFAULT NULL COMMENT 'Customerprice LastPPUnit',
                            `selling` varchar(255) DEFAULT NULL COMMENT 'Customerprice Selling',
                            `category` varchar(255) DEFAULT NULL COMMENT 'Customerprice Category',
                            `sell_from_stock` int(11) DEFAULT NULL COMMENT 'Customerprice SellFromStock',
                            `est_local_cost` varchar(255) DEFAULT NULL COMMENT 'Customerprice EstLocalCost',
                            `est_import_cost` varchar(255) DEFAULT NULL COMMENT 'Customerprice EstImportCost',
                            `price_list` varchar(255) DEFAULT NULL COMMENT 'Customerprice Pricelist',
                            `price_based_id` varchar(255) DEFAULT NULL COMMENT 'Customerprice PriceBasedID',
                            `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Customerprice StartDate',
                            `finish_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Customerprice FinishDate',
                            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Customerprice Created At',
                            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Customerprice Updated At',
                            PRIMARY KEY (customerprice_id)
                        ) $charset_collate;";
                    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                    dbDelta($pld_create_table_query);
                }

                // loop through price data
                foreach ($customers_prices_Data as $customersPrice) {
                    $partNumber = $customersPrice->PartNumber;
                    $desc = $customersPrice->Desc;
                    $lastPPUnit = $customersPrice->LastPPUnit;
                    $selling = $customersPrice->Selling;
                    $category = $customersPrice->Category;
                    $sellFromStock = $customersPrice->SellFromStock;
                    $estLocalCost = $customersPrice->EstLocalCost;
                    $estImportCost = $customersPrice->EstImportCost;
                    $pricelist = $customersPrice->Pricelist;
                    $priceBasedID = $customersPrice->PriceBasedID;
                    $startDate = $customersPrice->StartDate;
                    $finishDate = $customersPrice->FinishDate;

                    if ($partNumber && $pricelist) {
                        $check_query = $wpdb->prepare("SELECT customerprice_id,product_sku, price_list "
                                . "FROM $tablename "
                                . "WHERE product_sku = %s AND price_list = %s ", $partNumber, $pricelist);

                        $check_if_exist = $wpdb->get_row($check_query, ARRAY_A);
                        $data = array(
                            'product_sku' => $partNumber,
                            'description' => $desc,
                            'lastpp_unit' => $lastPPUnit,
                            'selling' => $selling,
                            'category' => $category,
                            'sell_from_stock' => $sellFromStock,
                            'est_local_cost' => $estLocalCost,
                            'est_import_cost' => $estImportCost,
                            'price_list' => $pricelist,
                            'price_based_id' => $priceBasedID,
                            'start_date' => $startDate,
                            'finish_date' => $finishDate
                        );

                        // if already exist in database, just update otherwise insert new
                        if ($check_if_exist) {
                            $wpdb->update($tablename, $data, array('customerprice_id' => $check_if_exist['customerprice_id']));
                        } else {
                            $wpdb->insert($tablename, $data);
                        }
                    }
                }
                return new WP_REST_Response(array(
                    'status' => 'success',
                    'response' => 'Cron run successfully.',
                    'body_response' => 200
                ));

                // update_option()
                

            } else {
                return new WP_Error('no_found', 'Cron run successfully, no any customer prices found', array('status' => 404));
            }
        }
    }

    public function import_customer_prices_from_api_updated_date() {
        $options = get_option('pld_api_options');        
        if (
                isset($options) && !empty($options) &&
                !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                !empty($options['pld_webstore_get_pricelist_from_api_path'])
        ) {

            $pld_webstore_endpoint = $options['pld_webstore_endpoint'];
            $pld_webstore_database = $options['pld_webstore_database'];
            $pld_webstore_get_pricelist_from_api_path = $options['pld_webstore_get_pricelist_from_api_path'];
            $pld_webstore_get_pricelist_from_api_path = '/InventoryPicker/GetPriceListsWebStoreByPaging';

            $price_last_import_timestamp_options = get_option('pld_price_last_import_timestamp') ? get_option('pld_price_last_import_timestamp') : '2018-01-01';
            $price_last_import_timestamp_options = '2018-01-01';
            $url = $pld_webstore_endpoint . $pld_webstore_get_pricelist_from_api_path;
            $i = 1;
            $total_count = $total_pages = 0;
            $max_per_page = 1000;
            $today_date = date("Y-m-d");

            $data = '["1","1","' . $price_last_import_timestamp_options . '"]';
            $headerArray = array(
                "Content-Type: application/json",
                "auth-database: " . $pld_webstore_database,
            );
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, $url);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10000);
            curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArray);

            $curl_data = curl_exec($curl_handle);
            $httpcode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
            curl_close($curl_handle);

            $customer_prices = json_decode($curl_data);
            $customers_prices_Data = $customer_prices->Data;
            if(!empty($customers_prices_Data) && isset($customers_prices_Data[0])){
                if(isset($customers_prices_Data[0]->TotalRows)){
                    $total_count = $customers_prices_Data[0]->TotalRows;
                }
                $total_pages = ceil($total_count / $max_per_page);
            }
            $updated_arr = array();
            // If data is received from API
            if (!empty($customers_prices_Data)) {
                global $wpdb;
                $tablename = $wpdb->prefix . 'palladium_customer_prices';

                // Check if table exist or not, if not create it first
                $table_exist_query = $wpdb->prepare('SHOW TABLES LIKE %s', $tablename);
                if (!$wpdb->get_var($table_exist_query) == $tablename) {
                    $charset_collate = $wpdb->get_charset_collate();
                    $pld_create_table_query = "
                        CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}palladium_customer_prices` (
                            `customerprice_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Customerprice ID',
                            `description` varchar(255) DEFAULT NULL COMMENT 'Customerprice Description',
                            `product_sku` varchar(255) DEFAULT NULL COMMENT 'Customerprice Product Sku',
                            `lastpp_unit` int(11) DEFAULT NULL COMMENT 'Customerprice LastPPUnit',
                            `selling` varchar(255) DEFAULT NULL COMMENT 'Customerprice Selling',
                            `category` varchar(255) DEFAULT NULL COMMENT 'Customerprice Category',
                            `sell_from_stock` int(11) DEFAULT NULL COMMENT 'Customerprice SellFromStock',
                            `est_local_cost` varchar(255) DEFAULT NULL COMMENT 'Customerprice EstLocalCost',
                            `est_import_cost` varchar(255) DEFAULT NULL COMMENT 'Customerprice EstImportCost',
                            `price_list` varchar(255) DEFAULT NULL COMMENT 'Customerprice Pricelist',
                            `price_based_id` varchar(255) DEFAULT NULL COMMENT 'Customerprice PriceBasedID',
                            `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Customerprice StartDate',
                            `finish_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Customerprice FinishDate',
                            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Customerprice Created At',
                            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Customerprice Updated At',
                            PRIMARY KEY (customerprice_id)
                        ) $charset_collate;";
                    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                    dbDelta($pld_create_table_query);
                }
                // loop through price data
                $u_i = 1;
                for($i = 1;$i <= $total_pages;$i++) {
                    $data_param = '['.$i.','.$max_per_page.',"' . $price_last_import_timestamp_options . '"]';
                    $curl_handle = curl_init();
                    curl_setopt($curl_handle, CURLOPT_URL, $url);
                    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data_param);
                    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10000);
                    curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArray);

                    $curl_data = curl_exec($curl_handle);
                    $httpcode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
                    curl_close($curl_handle);

                    $final_customer_prices = json_decode($curl_data);
                    $final_customers_prices_Data = $final_customer_prices->Data;

                    foreach ($final_customers_prices_Data as $customersPrice) {
                        $partNumber = $customersPrice->PartNumber;
                        $desc = $customersPrice->Desc;
                        $lastPPUnit = $customersPrice->LastPPUnit;
                        $selling = $customersPrice->Selling;
                        $category = $customersPrice->Category;
                        $sellFromStock = $customersPrice->SellFromStock;
                        $estLocalCost = $customersPrice->EstLocalCost;
                        $estImportCost = $customersPrice->EstImportCost;
                        $pricelist = $customersPrice->Pricelist;
                        $priceBasedID = $customersPrice->PriceBasedID;
                        $startDate = $customersPrice->StartDate;
                        $finishDate = $customersPrice->FinishDate;

                        if ($partNumber && $pricelist) {
                            $check_query = $wpdb->prepare("SELECT customerprice_id,product_sku, price_list "
                                    . "FROM $tablename "
                                    . "WHERE product_sku = %s AND price_list = %s ", $partNumber, $pricelist);

                            $check_if_exist = $wpdb->get_row($check_query, ARRAY_A);
                            $data = array(
                                'product_sku' => $partNumber,
                                'description' => $desc,
                                'lastpp_unit' => $lastPPUnit,
                                'selling' => $selling,
                                'category' => $category,
                                'sell_from_stock' => $sellFromStock,
                                'est_local_cost' => $estLocalCost,
                                'est_import_cost' => $estImportCost,
                                'price_list' => $pricelist,
                                'price_based_id' => $priceBasedID,
                                'start_date' => $startDate,
                                'finish_date' => $finishDate
                            );

                            // if already exist in database, just update otherwise insert new
                            if ($check_if_exist) {
                                $wpdb->update($tablename, $data, array('customerprice_id' => $check_if_exist['customerprice_id']));
                            } else {
                                $wpdb->insert($tablename, $data);
                            }
                        }
                        $u_i++;
                    }
                }
                update_option('pld_price_last_import_timestamp', $today_date);
                return new WP_REST_Response(array(
                    'status' => 'success',
                    'response' => 'Cron run successfully.',
                    'body_response' => 200
                ));
            } else {
                // sendMail_for_API_record_update('', '', 'Customer price API import failed', 'Customer price API failed to update the record or No record found in API. Failed API date: "'. $today_date .'"');
                return new WP_Error('no_found', 'Cron run successfully, no any customer prices found', array('status' => 404));
            }
        }
    }

    /**
     * Import sales discount matrix from api 
     */
    public function import_sales_discount_matrix_from_api() {
        $options = get_option('pld_api_options');
        if (
                isset($options) && !empty($options) &&
                !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                !empty($options['pld_webstore_get_sales_discount_matrix_from_api_path']) &&
                !empty($options['pld_webstore_get_customer_category_api'])
        ) {

            $pld_webstore_endpoint = $options['pld_webstore_endpoint'];
            $pld_webstore_database = $options['pld_webstore_database'];
            $pld_webstore_get_customer_category_api = $options['pld_webstore_get_customer_category_api'];
            $pld_webstore_get_sales_discount_matrix_from_api_path = $options['pld_webstore_get_sales_discount_matrix_from_api_path'];

            /*
             *  Import customers categories API first
             */
            $curl_customer_category = curl_init();
            $url_customer_category = $pld_webstore_endpoint . $pld_webstore_get_customer_category_api;

            $headerArray = array(
                "Content-Type: application/json",
                "auth-database: " . $pld_webstore_database,
            );

            curl_setopt($curl_customer_category, CURLOPT_URL, $url_customer_category);
            curl_setopt($curl_customer_category, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_customer_category, CURLOPT_HTTPHEADER, $headerArray);
            $curl_data_cusomters = curl_exec($curl_customer_category);
            curl_close($curl_customer_category);

            // Decode JSON into PHP array
            $customers_categories = json_decode($curl_data_cusomters);
            $customers_categories_Data = $customers_categories->Data;

            global $wpdb;
            $tablename_column = $wpdb->prefix . 'palladium_discount_matrix_columns';

            // If data is received from API
            if ($customers_categories_Data) {
                // Check if table exist or not, if not create it first
                $column_table_exist_query = $wpdb->prepare('SHOW TABLES LIKE %s', $tablename_column);
                if (!$wpdb->get_var($column_table_exist_query) == $tablename_column) {
                    $charset_collate = $wpdb->get_charset_collate();
                    $pld_create_discount_column_matrix_table_query = "
                        CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}palladium_discount_matrix_columns` (
                            `column_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Column Id',
                            `column_name` varchar(255) DEFAULT NULL COMMENT 'Column Name',
                            PRIMARY KEY (column_id)
                        ) $charset_collate;";
                    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                    dbDelta($pld_create_discount_column_matrix_table_query);
                }

                // loop through price matrix data
                foreach ($customers_categories_Data as $customersData) {
                    $custoCategory = $customersData->CustCategory;
                    if ($custoCategory) {
                        // check if "CustCategory" is exist in matrix column table or not
                        // if not create and return matrix column id
                        $check_column_query = $wpdb->prepare("SELECT column_id "
                                . "FROM $tablename_column "
                                . "WHERE column_name = %s", $custoCategory);

                        $check_if_column_exist = $wpdb->get_row($check_column_query, ARRAY_A);
                        $data = array('column_name' => $custoCategory);

                        if ($check_if_column_exist) {
                            $wpdb->update($tablename_column, $data, array('column_id' => $check_if_column_exist['column_id']));
                            $metrix_column_id = $check_if_column_exist['column_id'];
                        } else {
                            $wpdb->insert($tablename_column, $data);
                            $metrix_column_id = $wpdb->insert_id;
                        }
                    }
                }
            }

            /*
             *  Import discount maxtirx now
             */
            $curl_handle = curl_init();
            $url = $pld_webstore_endpoint . $pld_webstore_get_sales_discount_matrix_from_api_path;
            curl_setopt($curl_handle, CURLOPT_URL, $url);

            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArray);
            $curl_data = curl_exec($curl_handle);
            curl_close($curl_handle);

            // Decode JSON into PHP array
            $customers_matrix = json_decode($curl_data);
            $customers_matrix_Data = $customers_matrix->Data;
            $tablename = $wpdb->prefix . 'palladium_discount_matrix';

            // Check if table exist or not, if not create it first
            $table_exist_query = $wpdb->prepare('SHOW TABLES LIKE %s', $tablename);
            if (!$wpdb->get_var($table_exist_query) == $tablename) {
                $charset_collate = $wpdb->get_charset_collate();
                $pld_create_discount_matrix_table_query = "
                        CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}palladium_discount_matrix` (
                            `discount_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Discount ID',
                            `category_id` int(11) DEFAULT NULL COMMENT 'Discount Category Id',
                            `discount_percentage` varchar(255) DEFAULT NULL COMMENT 'Discount Discount Percentage',
                            `matrix_columns` int(11) DEFAULT NULL COMMENT 'Discount Discount Column',
                            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Discount Created At',
                            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Discount Updated At',
                            PRIMARY KEY (discount_id)
                        ) $charset_collate;";
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                dbDelta($pld_create_discount_matrix_table_query);
            }

            if ($customers_matrix_Data) {
                // loop through price matrix data
                foreach ($customers_matrix_Data as $customersPrice) {
                    $custoCategory = $customersPrice->CustCategory;
                    $invCategory = $customersPrice->InvCategory;
                    $discount = $customersPrice->Discount;

                    if ($custoCategory && $invCategory) {
                        // check if "CustCategory" is exist in matrix column table or not
                        // if yes return matrix column id
                        $check_column_query = $wpdb->prepare("SELECT column_id "
                                . "FROM $tablename_column "
                                . "WHERE column_name = %s", $custoCategory);

                        $check_if_column_exist = $wpdb->get_row($check_column_query, ARRAY_A);

                        // check if "InvCategory" is exist in wordpress or not
                        // if not create and return term taxonomy id
                        $existing_category = get_term_by('name', $invCategory, 'product_cat');
                        if ($existing_category) {
                            $existing_category_id = $existing_category->term_id;
                        } else {
                            $category_detail = wp_insert_term($invCategory, 'product_cat');
                            $existing_category_id = $category_detail['term_id'];
                        }

                        if (isset($check_if_column_exist['column_id']) && $existing_category_id) {
                            $check_query = $wpdb->prepare("SELECT discount_id "
                                    . "FROM $tablename "
                                    . "WHERE matrix_columns = %s AND category_id = %s", $check_if_column_exist['column_id'], $existing_category_id);

                            $check_if_exist = $wpdb->get_row($check_query, ARRAY_A);
                            $data = array(
                                'category_id' => $existing_category_id,
                                'matrix_columns' => $check_if_column_exist['column_id'],
                                'discount_percentage' => $discount
                            );

                            // if already exist in database, just update otherwise insert new
                            if ($check_if_exist) {
                                $wpdb->update($tablename, $data, array('discount_id' => $check_if_exist['discount_id']));
                            } else {
                                $wpdb->insert($tablename, $data);
                            }
                        }
                    }
                }
            }
            return new WP_REST_Response(array(
                'status' => 'success',
                'response' => 'Cron run successfully.',
                'body_response' => 200
            ));
        }
    }

    /**
     * Import customer tax code from api 
     */
    public function import_customer_taxcode_from_api() {
        $options = get_option('pld_api_options');
        if (
                isset($options) && !empty($options) &&
                !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                !empty($options['pld_webstore_get_customer_taxcode_api'])
        ) {

            $pld_webstore_endpoint = $options['pld_webstore_endpoint'];
            $pld_webstore_database = $options['pld_webstore_database'];
            $pld_webstore_get_customer_taxcode_api = $options['pld_webstore_get_customer_taxcode_api'];

            // Initiate curl session in a variable (resource)
            $curl_handle = curl_init();
            $url = $pld_webstore_endpoint . $pld_webstore_get_customer_taxcode_api;

            $headerArray = array(
                "Content-Type: application/json",
                "auth-database: " . $pld_webstore_database,
            );
            curl_setopt($curl_handle, CURLOPT_URL, $url);

            // This option will return data as a string instead of direct output
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArray);

            // Execute curl & store data in a variable
            $curl_data = curl_exec($curl_handle);

            curl_close($curl_handle);

            // Decode JSON into PHP array
            $customers_taxcodes = json_decode($curl_data);
            $customers_taxcodes_Data = $customers_taxcodes->Data;

            // If data is received from API
            if ($customers_taxcodes_Data) {
                global $wpdb;
                $tablename = $wpdb->prefix . 'palladium_customer_taxcode';

                // Check if table exist or not, if not create it first
                $table_exist_query = $wpdb->prepare('SHOW TABLES LIKE %s', $tablename);
                if (!$wpdb->get_var($table_exist_query) == $tablename) {
                    $charset_collate = $wpdb->get_charset_collate();
                    $pld_create_taxcode_table_query = "
                        CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}palladium_customer_taxcode` (
                            `tax_entity_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Tax Entity ID',
                            `taxcode` varchar(255) DEFAULT NULL COMMENT 'Taxcode',
                            `taxname` varchar(255) DEFAULT NULL COMMENT 'TaxName',
                            `status` tinyint(1) DEFAULT NULL COMMENT 'Status',
                            `rate` float DEFAULT NULL COMMENT 'Rate',
                            `is_included` tinyint(1) DEFAULT NULL COMMENT 'IsIncluded',
                            `is_refundable` tinyint(1) DEFAULT NULL COMMENT 'IsRefundable',
                            PRIMARY KEY (tax_entity_id)
                        ) $charset_collate;";
                    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                    dbDelta($pld_create_taxcode_table_query);
                }

                // loop through price data
                foreach ($customers_taxcodes_Data as $customersTaxcodes) {
                    $taxCode = $customersTaxcodes->TaxCode;
                    $taxName = $customersTaxcodes->TaxName;
                    $status = $customersTaxcodes->Status;
                    $rate = $customersTaxcodes->Rate;
                    $isIncluded = $customersTaxcodes->IsIncluded;
                    $isRefundable = $customersTaxcodes->IsRefundable;

                    if ($taxCode) {
                        $check_query = $wpdb->prepare("SELECT tax_entity_id FROM $tablename "
                                . "WHERE taxcode = %s ", $taxCode);

                        $check_if_exist = $wpdb->get_row($check_query, ARRAY_A);
                        $data = array(
                            'taxcode' => $taxCode,
                            'taxname' => $taxName,
                            'status' => $status,
                            'rate' => $rate,
                            'is_included' => $isIncluded,
                            'is_refundable' => $isRefundable
                        );

                        // if already exist in database, just update otherwise insert new
                        if ($check_if_exist) {
                            $wpdb->update($tablename, $data, array('tax_entity_id' => $check_if_exist['tax_entity_id']));
                        } else {
                            $wpdb->insert($tablename, $data);
                        }
                    }
                }
                return new WP_REST_Response(array(
                    'status' => 'success',
                    'response' => 'Cron run successfully.',
                    'body_response' => 200
                ));
            } else {
                return new WP_Error('no_found', 'Cron run successfully, no any customer tax codes found', array('status' => 404));
            }
        }
    }

    /**
     * Alter Product Pricing Part 1 - WooCommerce Product
     */
    public function pld_display_palladium_custom_price($price_html, $product) {

        // get default price list from backend
        global $wpdb;
        $options = get_option('pld_api_options');
        $user_price_list = $options['pld_webstore_default_price_list'];
        $includeVAT = $options['pld_webstore_include_vat_in_price'];
        $defaultTaxCode = $options['pld_webstore_default_tax_code'];

        // If customer is logged in, apply discount 
        if (is_user_logged_in() && wc_current_user_has_role('customer')) {
            $user_price_list = get_user_meta(get_current_user_id(), 'pld_customer_pricelist', true);
            if (!$user_price_list) {
                $user_price_list = $options['pld_webstore_default_price_list'];
            }

            // If customer category is assigned to customer
            // get discount from sales metrix table using product category and user category
            $user_cust_category = get_user_meta(get_current_user_id(), 'pld_customer_cust_category', true);
            if ($user_cust_category) {
                $product_cats = wp_get_post_terms($product->get_id(), 'product_cat');
                if (!empty($product_cats)) {
                    $category_discount = $this->pld_get_category_discount_price($product_cats, $user_cust_category);
                }
            }

            // get selling price from wp_palladium_customer_prices table
            // by product sku and user price list
            $selling_price = $this->pld_get_selling_custom_price($product->get_sku(), $user_price_list);

            // if discount is available for customer calculate it
            if ($category_discount) {
                $percentInDecimal = $category_discount / 100;
                $discount = $percentInDecimal * $selling_price;
                if ($discount) {
                    $selling_price = $selling_price - $discount;
                }
            }

            // include tax in selling price if enabled
            $pld_customer_tax_code = get_user_meta(get_current_user_id(), 'pld_customer_tax_code', true);
            if ($pld_customer_tax_code) {
                $defaultTaxCode = $pld_customer_tax_code;
            }
            if ($includeVAT && $defaultTaxCode && $includeVAT == 'on') {
                $check_query = $wpdb->prepare("SELECT rate FROM `{$wpdb->prefix}palladium_customer_taxcode` "
                        . "WHERE taxcode = %s ", $defaultTaxCode);

                $check_if_exist = $wpdb->get_row($check_query, ARRAY_A);
                if ($check_if_exist['rate']) {
                    $selling_price = $selling_price + (($selling_price * $check_if_exist['rate']) / 100);
                }
            }
        } else {
            // get selling price from wp_palladium_customer_prices table
            // by product sku and "REGULAR" user price list
            $selling_price = $this->pld_get_selling_custom_price($product->get_sku(), $user_price_list);

            // include tax in selling price if enabled
            if ($includeVAT && $defaultTaxCode && $includeVAT == 'on') {
                $check_query = $wpdb->prepare("SELECT rate FROM `{$wpdb->prefix}palladium_customer_taxcode` "
                        . "WHERE taxcode = %s ", $defaultTaxCode);

                $check_if_exist = $wpdb->get_row($check_query, ARRAY_A);
                if ($check_if_exist['rate']) {
                    $selling_price = $selling_price + (($selling_price * $check_if_exist['rate']) / 100);
                }
            }
        }

        // if($selling_price == '' || $selling_price == 0){
        //     return $price_html;
        // }

        $price_html = wc_price($selling_price);
        return $price_html;
    }

    /**
     * Alter Product Pricing Part 2 - WooCommerce Cart/Checkout
     */
    function pld_cart_palladium_custom_price($cart) {
        if (is_admin() && !defined('DOING_AJAX'))
            return;

        if (did_action('woocommerce_before_calculate_totals') >= 2)
            return;

        // get default price list from backend
        global $wpdb;
        $options = get_option('pld_api_options');
        $user_price_list = $options['pld_webstore_default_price_list'];
        $includeVAT = $options['pld_webstore_include_vat_in_price'];
        $defaultTaxCode = $options['pld_webstore_default_tax_code'];

        // If customer is logged in, apply discount 
        if (is_user_logged_in() && wc_current_user_has_role('customer')) {

            // LOOP THROUGH CART ITEMS & APPLY 20% DISCOUNT
            $user_price_list = get_user_meta(get_current_user_id(), 'pld_customer_pricelist', true);
            if (!$user_price_list) {
                $user_price_list = $options['pld_webstore_default_price_list'];
            }

            foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
                $product = $cart_item['data'];

                if ($product->get_parent_id()) {
                    $product_id = $product->get_parent_id();
                } else {
                    $product_id = $product->get_id();
                }

                // If customer category is assigned to customer
                // get discount from sales metrix table using product category and user category
                $user_cust_category = get_user_meta(get_current_user_id(), 'pld_customer_cust_category', true);
                if ($user_cust_category) {
                    $product_cats = wp_get_post_terms($product_id, 'product_cat');
                    if (!empty($product_cats)) {
                        $category_discount = $this->pld_get_category_discount_price($product_cats, $user_cust_category);
                    }
                }

                // get selling price from wp_palladium_customer_prices table
                // by product sku and user price list
                $selling_price = $this->pld_get_selling_custom_price($product->get_sku(), $user_price_list);

                // if discount is available for customer calculate it
                if ($category_discount) {
                    $percentInDecimal = $category_discount / 100;
                    $discount = $percentInDecimal * $selling_price;
                    if ($discount) {
                        $selling_price = $selling_price - $discount;
                    }
                }

                // include tax in selling price if enabled
                $pld_customer_tax_code = get_user_meta(get_current_user_id(), 'pld_customer_tax_code', true);
                if ($pld_customer_tax_code) {
                    $defaultTaxCode = $pld_customer_tax_code;
                }
                if ($includeVAT && $defaultTaxCode && $includeVAT == 'on') {
                    $check_query = $wpdb->prepare("SELECT rate FROM `{$wpdb->prefix}palladium_customer_taxcode` "
                            . "WHERE taxcode = %s ", $defaultTaxCode);

                    $check_if_exist = $wpdb->get_row($check_query, ARRAY_A);
                    if ($check_if_exist['rate']) {
                        $selling_price = $selling_price + (($selling_price * $check_if_exist['rate']) / 100);
                    }
                }

                $cart_item['data']->set_price($selling_price);
            }
        } else {
            foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
                $product = $cart_item['data'];
                // get selling price from wp_palladium_customer_prices table
                // by product sku and user price list
                $selling_price = $this->pld_get_selling_custom_price($product->get_sku(), $user_price_list);

                // include tax in selling price if enabled
                if ($includeVAT && $defaultTaxCode && $includeVAT == 'on') {
                    $check_query = $wpdb->prepare("SELECT rate FROM `{$wpdb->prefix}palladium_customer_taxcode` "
                            . "WHERE taxcode = %s ", $defaultTaxCode);

                    $check_if_exist = $wpdb->get_row($check_query, ARRAY_A);
                    if ($check_if_exist['rate']) {
                        $selling_price = $selling_price + (($selling_price * $check_if_exist['rate']) / 100);
                    }
                }

                $cart_item['data']->set_price($selling_price);
            }
        }
    }

    /**
     * Get selling price from price list custom table
     */
    function pld_get_selling_custom_price($product_sku, $price_list) {
        global $wpdb;
        $tablename = $wpdb->prefix . 'palladium_customer_prices';
        $product_selling_price_query = $wpdb->prepare(""
                . "SELECT customerprice_id, selling, start_date, finish_date"
                . " FROM " . $tablename . " WHERE product_sku='%s' AND price_list='%s'", $product_sku, $price_list);
        $price_lists = $wpdb->get_results($product_selling_price_query, ARRAY_A);

        // default price will be 0
        $price = 0.00;

        if (count($price_lists) > 0) {
            $current = date("Y-m-d 00:00:00");
            foreach ($price_lists as $customprice) {
                $start = $customprice['start_date'];
                $end = $customprice['finish_date'];
                $valid_date = (($start <= $current && $end >= $current) || (($start <= $current && $start != NULL) && $end == ''));
                if ($valid_date) {
                    $price = $customprice['selling'];
                }
            }
        }

        return $price;
    }

    /**
     * Get discount based on category
     */
    function pld_get_category_discount_price($product_category, $user_cust_discount_category) {
        global $wpdb;
        $tablename_column = $wpdb->prefix . 'palladium_discount_matrix_columns';
        $tablename_matrix = $wpdb->prefix . 'palladium_discount_matrix';
        $discount_amount = array();

        // loop through product category
        foreach ($product_category as $key => $cat) {

            // get column id from customer assigned category
            $check_column_query = $wpdb->prepare("SELECT column_id FROM $tablename_column "
                    . "WHERE column_name = %s", $user_cust_discount_category);
            $check_if_column_exist = $wpdb->get_row($check_column_query, ARRAY_A);

            // found discount percentage by product category id and matrix column (column id from customer assigned category)
            if ($check_if_column_exist['column_id']) {
                $column_id = $check_if_column_exist['column_id'];

                $check_query = $wpdb->prepare("SELECT discount_percentage FROM $tablename_matrix "
                        . "WHERE matrix_columns = %s AND category_id = %s", $column_id, $cat->term_id);

                $discount_results = $wpdb->get_row($check_query, ARRAY_A);
                $discount_amount[] = $discount_results['discount_percentage'];
            }
        }

        // if found multiple category for product, return max discount percentage
        if (sizeof($discount_amount) > 0) {
            return max($discount_amount);
        } else {
            return NULL;
        }
    }

    /**
     * Check if purchasable 
     */
    function pld_is_purchasable_cb($purchasable, $product) {
        global $wpdb;
        $options = get_option('pld_api_options');
        $user_price_list = $options['pld_webstore_default_price_list'];
        $includeVAT = $options['pld_webstore_include_vat_in_price'];
        $defaultTaxCode = $options['pld_webstore_default_tax_code'];

        // If customer is logged in, apply discount 
        if (is_user_logged_in() && wc_current_user_has_role('customer')) {
            $user_price_list = get_user_meta(get_current_user_id(), 'pld_customer_pricelist', true);
            if (!$user_price_list) {
                $user_price_list = $options['pld_webstore_default_price_list'];
            }

            // If customer category is assigned to customer
            // get discount from sales metrix table using product category and user category
            $user_cust_category = get_user_meta(get_current_user_id(), 'pld_customer_cust_category', true);
            if ($user_cust_category) {
                $product_cats = wp_get_post_terms($product->get_id(), 'product_cat');
                if (!empty($product_cats)) {
                    $category_discount = $this->pld_get_category_discount_price($product_cats, $user_cust_category);
                }
            }

            // get selling price from wp_palladium_customer_prices table
            // by product sku and user price list
            $selling_price = $this->pld_get_selling_custom_price($product->get_sku(), $user_price_list);

            // if discount is available for customer calculate it
            if ($category_discount) {
                $percentInDecimal = $category_discount / 100;
                $discount = $percentInDecimal * $selling_price;
                if ($discount) {
                    $selling_price = $selling_price - $discount;
                }
            }

            // include tax in selling price if enabled
            $pld_customer_tax_code = get_user_meta(get_current_user_id(), 'pld_customer_tax_code', true);
            if ($pld_customer_tax_code) {
                $defaultTaxCode = $pld_customer_tax_code;
            }
            if ($includeVAT && $defaultTaxCode && $includeVAT == 'on') {
                $check_query = $wpdb->prepare("SELECT rate FROM `{$wpdb->prefix}palladium_customer_taxcode` "
                        . "WHERE taxcode = %s ", $defaultTaxCode);

                $check_if_exist = $wpdb->get_row($check_query, ARRAY_A);
                if ($check_if_exist['rate']) {
                    $selling_price = $selling_price + (($selling_price * $check_if_exist['rate']) / 100);
                }
            }
        } else {
            // get selling price from wp_palladium_customer_prices table
            // by product sku and "REGULAR" user price list
            $selling_price = $this->pld_get_selling_custom_price($product->get_sku(), $user_price_list);

            // include tax in selling price if enabled
            if ($includeVAT && $defaultTaxCode && $includeVAT == 'on') {
                $check_query = $wpdb->prepare("SELECT rate FROM `{$wpdb->prefix}palladium_customer_taxcode` "
                        . "WHERE taxcode = %s ", $defaultTaxCode);

                $check_if_exist = $wpdb->get_row($check_query, ARRAY_A);
                if ($check_if_exist['rate']) {
                    $selling_price = $selling_price + (($selling_price * $check_if_exist['rate']) / 100);
                }
            }
        }

        if ($selling_price == 0)
            $purchasable = false;
        return $purchasable;
    }

}

// ADDING 2 NEW COLUMNS WITH THEIR TITLES (keeping "Total" and "Actions" columns at the end)
add_filter('manage_edit-shop_order_columns', 'custom_shop_order_column_sync_to_palladium', 20);

function custom_shop_order_column_sync_to_palladium($columns) {
    $reordered_columns = array();

    // Inserting columns to a specific location
    foreach ($columns as $key => $column) {
        $reordered_columns[$key] = $column;
        if ($key == 'order_status') {
            // Inserting after "Status" column
            $reordered_columns['sync-order'] = __('Sync order to palladium', 'theme_domain');
            $reordered_columns['sync-order-button'] = __('Sync order to palladium Button', 'theme_domain');
        }
    }
    return $reordered_columns;
}

add_action('manage_shop_order_posts_custom_column', 'custom_order_sync_palladium', 20, 2);

function custom_order_sync_palladium($column, $post_id) {
    switch ($column) {
        case 'sync-order' :
            // Get custom post meta data
            $my_var_one = get_post_meta($post_id, '_the_meta_key1', true);
            if (!empty($my_var_one))
                echo $my_var_one;

            // Testing (to be removed) - Empty value case
            else
                echo 'No';

            break;

        case 'sync-order-button' :
            $my_var_one = get_post_meta($post_id, '_the_meta_key1', true);
            if (empty($my_var_one)) {
                // print_r($post_id);
                $orderKey = get_post_meta($post_id, '_order_key', true);
                // https://www.anasource.com/team4/tjhokopalladium-webfootprint-copy/checkout/order-received/$post_id/?key=$orderKey
                // print_r(wp_nonce_url( add_query_arg( 'order_again', $order->id ) , 'woocommerce-place_order' ));
                echo "<p><a href='javascript:void(0)' class='sync-order button' style='float:left; margin-top:0px; height:27px;' data-order='$post_id'>Sync with palladium</a></p>";
            }
    }
}

add_action('wp_ajax_sync_palladium', 'sync_palladium_order');

function sync_palladium_order() {
    global $wpdb; // this is how you get access to the database

    $order_id = intval($_GET['order_id']);
    print_r($order_id);
}


//Optimized code for Customer Data API to overcome maximum timeout issue

add_action( 'plugins_loaded', 'add_enqueue_scripts' );
function add_enqueue_scripts(){
	wp_enqueue_style( 'custom-import', plugins_url('/css/custom-import.css?v='.time(), __FILE__),'all');
	wp_register_script( 
    'ajaxHandle', 
    plugins_url('js/jquery.ajax.js?v='.time(), __FILE__), 
    array(), 
    false, 
    true 
  );
  wp_enqueue_script( 'ajaxHandle' );
  wp_localize_script( 
    'ajaxHandle', 
    'ajax_object', 
    array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) 
  );
}

add_action( "wp_ajax_customer_data", "customer_data_ajax_function" );
add_action( "wp_ajax_nopriv_customer_data", "customer_data_ajax_function" );
function customer_data_ajax_function(){
	if(!empty($_POST)){ //Check whether data are posted or not
		$postData = $_POST;
	  //DO whatever you want with data posted
	  //To send back a response you have to echo the result!
  
  
  
		$options = get_option('pld_api_options');
        if (
                isset($options) && !empty($options) &&
                !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                !empty($options['pld_webstore_get_customer_from_api_path'])
        ) {

            $pld_webstore_endpoint = $options['pld_webstore_endpoint'];
            $pld_webstore_database = $options['pld_webstore_database'];
            $get_customer_from_api_path = $options['pld_webstore_get_customer_from_api_path'];


            $customer_last_import_timestamp_options = get_option('pld_customer_last_import_timestamp');

            $customer_last_import_timestamp = '';
            if (isset($customer_last_import_timestamp_options) && !empty($customer_last_import_timestamp_options)) {
                $customer_last_import_timestamp = $customer_last_import_timestamp_options;
                //$customer_last_import_timestamp = '2010-11-05 06:01:43';
            }
			if($postData['requestType'] == 'load'){ //Initial load time set Page Index
				$postData['pageIndex'] = 1;
			}
            $i = $postData['pageIndex'];
			
           // while (1) { 

                // Initiate curl session in a variable (resource)
                $curl_handle = curl_init();
                $url = $pld_webstore_endpoint . $get_customer_from_api_path;
                $data = array(
                    "PageIndex" => $i,
                    "PageSize" => 50,
                    "LastUpdated" => $customer_last_import_timestamp
                );

                $headerArray = array(
                    "Content-Type: application/json",
                    "auth-database: " . $pld_webstore_database,
                );
                $indexParameter = json_encode($data);
                curl_setopt($curl_handle, CURLOPT_URL, $url);

                // This option will return data as a string instead of direct output
                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $indexParameter);
                curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArray);

                // Execute curl & store data in a variable
                $curl_data = curl_exec($curl_handle);

                curl_close($curl_handle);

                // Decode JSON into PHP array
                $customers = json_decode($curl_data);
				$customers_Data = $customers->Data;
                $j = 0;
                $insertedUserCnt = $updatedUserCnt = $skippedUserCnt =0;
				$skippedEmail = $skippedUsername = array();
			if(!empty($customers_Data)){
				$totalPage = $customers_Data[0]->TotalPages;
                foreach ($customers_Data as $customersData) {
                    $email = $customersData->CustomerEmail;
                    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        if (count(explode(";", $email)) == 1) {
                            $CustomerName = $customersData->CustomerName;
                            $name = $customersData->Contact;
                            $Street1 = $customersData->Street1;
                            $Street2 = $customersData->Street2;
                            $City = $customersData->City;
                            //$Phone = $customersData->Phone;
                            $Phone = $customersData->CellPhone;
                            $CountryCode = $customersData->CountryCode;
                            $Country = $customersData->Country;
                            $Province = $customersData->Province;
                            $Postal = $customersData->Postal;
                            //$Company = $customersData->CustomerDescription;
                            $Pricelist = $customersData->Pricelist;
                            $custCategory = $customersData->CustCategory;
                            $taxCode = $customersData->TaxCode;

                            $user_email_data = get_user_by('email', $email); // return WP_User object otherwise return false if not found 
                            if (empty($user_email_data)) {
                                $userdata = array(
                                    'user_login' => $CustomerName,
                                    'user_email' => $email,
                                    'role' => 'customer',
                                    //'display_name' => $CustomerName,
                                    'user_pass' => NULL // When creating an user, `user_pass` is expected.
                                );
                                // insert new user
                                $user_id = wp_insert_user($userdata);
                                if (!is_wp_error($user_id)) {
                                    // You will need also to add this billing meta data
                                    update_user_meta($user_id, 'register_flag', 2);
                                    update_user_meta($user_id, 'customer_unique_id', $CustomerName);
                                    update_user_meta($user_id, 'billing_email', $email);
                                    update_user_meta($user_id, 'billing_address_1', $Street1);
                                    update_user_meta($user_id, 'billing_address_2', $Street2);
                                    update_user_meta($user_id, 'billing_city', $City);
                                    update_user_meta($user_id, 'billing_postcode', $Postal);
                                    //update_user_meta($user_id, 'billing_country', $Country);
                                    update_user_meta($user_id, 'billing_country', $CountryCode);
                                    update_user_meta($user_id, 'billing_state', $Province);
                                    update_user_meta($user_id, 'billing_phone', $Phone);
                                    //update_user_meta($user_id, 'billing_company', $Company);
                                    update_user_meta($user_id, 'pld_customer_pricelist', $Pricelist);
                                    update_user_meta($user_id, 'pld_customer_cust_category', $custCategory);
                                    update_user_meta($user_id, 'pld_customer_tax_code', $taxCode);
                                }
                                $insertedUserCnt++;
                            } else {
                                $userData = $user_email_data->data;
                                $user_id = $userData->ID;
                                if ($user_email_data->roles[0] == 'administrator') {
                                    $roles = 'administrator';
                                } else {
                                    $roles = 'customer';
                                }
                                $user_array = array(
                                    'ID' => $user_id,
                                    'user_login' => $CustomerName,
                                    'user_email' => $email,
                                    'role' => $roles,
                                    'display_name' => $CustomerName,
                                );
                                // update user
                                wp_update_user($user_array);
                                update_user_meta($user_id, 'register_flag', 2);
                                update_user_meta($user_id, 'customer_unique_id', $CustomerName);
                                update_user_meta($user_id, 'billing_address_1', $Street1);
                                update_user_meta($user_id, 'billing_address_2', $Street2);
                                update_user_meta($user_id, 'billing_city', $City);
                                update_user_meta($user_id, 'billing_postcode', $Postal);
                                //update_user_meta($user_id, 'billing_country', $Country);
                                update_user_meta($user_id, 'billing_country', $CountryCode);
                                update_user_meta($user_id, 'billing_state', $Province);
                                update_user_meta($user_id, 'billing_phone', $Phone);
                                //update_user_meta($user_id, 'billing_company', $Company);
                                update_user_meta($user_id, 'pld_customer_pricelist', $Pricelist);
                                update_user_meta($user_id, 'pld_customer_cust_category', $custCategory);
                                update_user_meta($user_id, 'pld_customer_tax_code', $taxCode);

                                $updatedUserCnt++;
                            }

                            $j++;
                        }else{
							$skippedUserCnt++;
							$skippedEmail = $email;
							$skippedUsername = $customersData->CustomerName;
						}
                    }else{
						$skippedUserCnt++;
						$skippedEmail = $email;
						$skippedUsername = $customersData->CustomerName;
					}
                }

                if ($totalPage <= $i) {
                   // break;
                }
                $i++;
            //}

            if ($i > $totalPage) {
                // Store the time stamp when the product cron run
                update_option('pld_customer_last_import_timestamp', date('Y-m-d h:i:s'));
            }

			$data = array(
                'status' => 'success',
                'response' => 'Cron run successfully.',
                'body_response' => 200,
				'pageIndex' => $i,
				'totalPages' => $totalPage,
                'insertedRecords' => $insertedUserCnt,
                'updatedRecords' => $updatedUserCnt,
                'skippedRecords' => $skippedUserCnt,
				'skippedEmail' => json_encode($skippedEmail),
				'skippedUsername' => json_encode($skippedUsername)
            );
			echo json_encode($data);wp_die(); // ajax call must die to avoid trailing 0 in your response
		}else{
			$data = array(
                'status' => 'failed',
                'response' => 'No Records found in API Response!',
                'body_response' => 200,
				'pageIndex' => 0,
				'totalPages' => 0
            );
			echo json_encode($data);wp_die(); // ajax call must die to avoid trailing 0 in your response
		}
            /*return new WP_REST_Response(array(
                'status' => 'success',
                'response' => 'Cron run successfully.',
                'body_response' => 200
            ));*/
        }
		
 // wp_die(); // ajax call must die to avoid trailing 0 in your response
	}else{
		$data = array(
			'status' => 'failed',
			'response' => 'Something went wrong with your request! Please contact to your site admin.',
			'body_response' => 200,
			'pageIndex' => 0,
			'totalPages' => 0
		);
		echo json_encode($data);wp_die(); // ajax call must die to avoid trailing 0 in your response
	}
}
/**
 * Create a product variation for a defined variable product ID.
 *
 * @since 3.0.0
 * @param int   $product_id | Post ID of the product parent variable product.
 * @param array $variation_data | The data to insert in the product.
 */


/**
 * Save a new product attribute from his name (slug).
 *
 * @since 3.0.0
 * @param string $name  | The product attribute name (slug).
 * @param string $label | The product attribute label (name).
 */
function save_product_attribute_from_name( $name, $label='', $set=true ){
    if( ! function_exists ('get_attribute_id_from_name') ) return;

    global $wpdb;

    $label = $label == '' ? ucfirst($name) : $label;
    $attribute_id = get_attribute_id_from_name( $name );

    if( empty($attribute_id) ){
        $attribute_id = NULL;
    } else {
        $set = false;
    }
    $args = array(
        'attribute_id'      => $attribute_id,
        'attribute_name'    => $name,
        'attribute_label'   => $label,
        'attribute_type'    => 'select',
        'attribute_orderby' => 'menu_order',
        'attribute_public'  => 0,
    );

    if( empty($attribute_id) )
        $wpdb->insert(  "{$wpdb->prefix}woocommerce_attribute_taxonomies", $args );

    if( $set ){
        $attributes = wc_get_attribute_taxonomies();
        $args['attribute_id'] = get_attribute_id_from_name( $name );
        $attributes[] = (object) $args;
        set_transient( 'wc_attribute_taxonomies', $attributes );
    } else {
        return;
    }
}

/**
 * Get the product attribute ID from the name.
 *
 * @since 3.0.0
 * @param string $name | The name (slug).
 */
function get_attribute_id_from_name( $name ){
    global $wpdb;
    $attribute_id = $wpdb->get_col("SELECT attribute_id
    FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
    WHERE attribute_name LIKE '$name'");
    return reset($attribute_id);
}

/**
 * Create Product Attributes 
 * @param  string $name    Attribute name
 * @param  array $options Options values
 * @return Object          WC_Product_Attribute 
 */
function pricode_create_attributes( $name, $options ){
    $attribute = new WC_Product_Attribute();

    $attribute->set_id(0);
    $attribute->set_name($name);
    $attribute->set_options($options);
    $attribute->set_visible(true);
    $attribute->set_variation(true);
    return $attribute;
}


/**
 * Store all child SKU in option - palladium_child_sku_lists
 */
function store_child_sku_option(){
   
    global $wpdb;
    $options = get_option('pld_api_options');
    $pld_webstore_endpoint = $options['pld_webstore_endpoint'];
    $pld_webstore_database = $options['pld_webstore_database'];
    $pld_webstore_get_child_product_items_from_api_path = $options['pld_webstore_get_child_product_items_from_api_path'];
    $apiurl = explode('InventoryPicker', $pld_webstore_endpoint);

    if(!empty($pld_webstore_endpoint) && !empty($pld_webstore_get_child_product_items_from_api_path)){
        $url = $pld_webstore_endpoint . $pld_webstore_get_child_product_items_from_api_path;
        $curl_handle = curl_init();
        $data = 'Body : ["",1,"25",""]';
        $headerArray = array(
            "Content-Type: application/json",
            "auth-database: ".$pld_webstore_database,
            "Cookie: ASP.NET_SessionId=wwmd115c4qzmqwfapn5zahou"
        );
        $indexParameter = json_encode($data);
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10000);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArray);

        $curl_data = curl_exec($curl_handle);

        curl_close($curl_handle);
        $child_data = json_decode($curl_data, true);
        if($child_data && isset($child_data['Data']) && !empty($child_data['Data'])){
            $childList = json_encode($child_data['Data']);
            update_option('palladium_child_sku_lists', $childList);
        }
    }
}

add_action( "wp_ajax_nopriv_product_data", "wp_ajax_product_data" );
add_action( "wp_ajax_product_data", "wp_ajax_product_data" );
function wp_ajax_product_data(){
    global $wpdb;
	if(!empty($_POST)){
		$postData = $_POST;  
		$options = get_option('pld_api_options');
        if (
                isset($options) && !empty($options) &&
                !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                !empty($options['pld_webstore_get_product_from_api_path'])
        ) 
        {

            $pld_webstore_endpoint = $options['pld_webstore_endpoint'];
            $pld_webstore_database = $options['pld_webstore_database'];
            $pld_webstore_get_product_from_api_path = $options['pld_webstore_get_product_from_api_path'];

            $product_last_import_timestamp_options = get_option('pld_product_last_import_timestamp');

            $product_last_import_timestamp = '';
            if (isset($product_last_import_timestamp_options) && !empty($product_last_import_timestamp_options)) {
                $product_last_import_timestamp = $product_last_import_timestamp_options;
            }
            if($postData['requestType'] == 'load'){
				$postData['pageIndex'] = 1;
                store_child_sku_option();
			}
            $i = $postData['pageIndex'];           
            $tablename_posts = $wpdb->prefix . 'posts';            
            $curl_handle = curl_init();
            $url = $pld_webstore_endpoint . $pld_webstore_get_product_from_api_path;
            $data = '["",' . $i . ',"25"]';

            $headerArray = array(
                "Content-Type: application/json",
                "auth-database: " . $pld_webstore_database,
            );
            $indexParameter = json_encode($data);
            curl_setopt($curl_handle, CURLOPT_URL, $url);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10000);
            curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArray);

            $curl_data = curl_exec($curl_handle);

            curl_close($curl_handle);
            $products = json_decode($curl_data);
            if(!empty($products) && isset($products->Data) && !empty($products->Data)){
                $products = $products->Data;
            } else{
                $products = array();
            }            
            
            $insertedUserCnt = $updatedUserCnt = $skippedUserCnt =0;
            $skippedEmail = $skippedUsername = array();
            if (isset($products) && !empty($products)) {
                $totalPages = $products[0]->TotalPages;

                $url = explode('/', $pld_webstore_endpoint);
                array_pop($url);
                $imagepath = implode('/', $url);
                $arr_child_list = array();
                $all_child_list = get_option('palladium_child_sku_lists', true);
                if($all_child_list){
                    $arr_child_list = json_decode($all_child_list, true);
                }                
                foreach ($products as $product) {
                    $title = trim($product->Description);
                    $sku = trim($product->PartNumber);
                    $height = $product->Height;
                    $width = $product->Width;
                    $category = ucwords(strtolower($product->CategoryDesc));
                    $type = $product->Type;
                    $mass = $product->Mass;
                    $qty = $product->SumOnHand;
                    $subCategory = ucwords(strtolower($product->SubCategory));
                    if ($product->IsInactive == false) {
                        $postStatus = 'publish';
                    } else {
                        $postStatus = 'draft';
                    }
                    // $postStatus = 'draft';

                    if (!empty($product->ProductImagesPath)) {
                        $productImage = $product->ProductImagesPath[0];
                    } else {
                        $productImage = $product->ImagePath;
                    }

                    $productImage = $imagepath . $productImage;

                    $variable_product = false;
                    $yourRawAttributeList = array();
                    if(isset($product->WebstoreAttributes) && !empty($product->WebstoreAttributes)){
                        $webAttributes = $product->WebstoreAttributes;
                    } else{
                        $webAttributes = array();
                    }
                    if(isset($product->RelatedItems) && !empty($product->RelatedItems)){
                        $RelatedItems = $product->RelatedItems;
                    } else{
                        $RelatedItems = array();
                    }
                    if(isset($product->ParentAttributes) && !empty($product->ParentAttributes)){
                        $ParentAttributes = $product->ParentAttributes;
                    } else{
                        $ParentAttributes = array();
                    } 
                    
                    if(!empty($RelatedItems) && !empty($ParentAttributes)){
                        $variable_product = true;
                        $productType = 'variable';
                    } else {
                        $productType = 'simple';  
                    }                    
                    $webstoreProduct = import_get_product_by_sku($sku);
                    if($webstoreProduct){
                        $p_type = $webstoreProduct->product_type;
                        if($p_type == 'simple'){
                            $productType = $p_type;
                        } elseif($p_type == 'variable' || $p_type == 'variation'){
                            $variable_product = true;
                            $productType == $p_type;
                        }
                        
                    }
                    if($productType == 'simple' && !empty($arr_child_list) && in_array($sku ,$arr_child_list)){
                        continue;
                    }
                                        
                    if ($variable_product) {
                        if ($webstoreProduct) {
                            $updatedUserCnt++;
                            if($productType == 'variable'){
                                $item_type = $product->Field2 . $product->Field3;
                                $product_id = $webstoreProduct->get_id();
                                $productImage = $imagepath . $productImage;
                                $product_id = wp_update_post(array(
                                    'ID' => $product_id,
                                    'post_title' => $title,
                                    'post_content' => "",
                                    'post_status' => $postStatus,
                                    'post_type' => "product",
                                ));
                                $existing_category = get_term_by('name', $category, 'product_cat');
                                if ($existing_category) {
                                    $existing_category_id = $existing_category->term_id;
                                } else {
                                    $category_detail = wp_insert_term($category, 'product_cat');
                                    $existing_category_id = $category_detail['term_id'];
                                }
                                $category_detail = wp_set_object_terms($product_id, $existing_category_id, 'product_cat');
                                if (!empty($subCategory)) {
                                    if (!term_exists($subCategory, 'product_cat')) {
                                        $subcategory_detail = wp_insert_term($subCategory, 'product_cat', array('parent' => $existing_category_id));
                                        wp_set_object_terms($product_id, array($existing_category_id, $subcategory_detail['term_id']), 'product_cat');
                                    } else {
                                        $existing_subcategory = get_term_by('name', $subCategory, 'product_cat');
                                        if (!$existing_subcategory) {
                                            $subcategory_detail = wp_insert_term($subCategory, 'product_cat', array('parent' => $existing_category_id));
                                            wp_set_object_terms($product_id, array($existing_category_id, $subcategory_detail['term_id']), 'product_cat');
                                        } else {
                                            wp_set_object_terms($product_id, array($existing_category_id, $existing_subcategory->term_id), 'product_cat');
                                        }
                                    }
                                }                                        
                                $productImage = $imagepath . $productImage;
                                $productImageWithoutFilter = explode($imagepath, $productImage);
                                $productImage = end($productImageWithoutFilter);
                                $productImage = $imagepath . $productImage;

                                if (has_post_thumbnail($product_id)) {
                                    $attachment_id = get_post_thumbnail_id($product_id);
                                    $kd_featured_image_url = wp_get_attachment_url($attachment_id);

                                    $image_url_base = basename($productImage);
                                    $kd_featured_image_base = basename($kd_featured_image_url);

                                    if ($kd_featured_image_base != $image_url_base) {
                                        wp_delete_attachment($attachment_id, true);
                                        $imageAttachID = pld_insert_attachment_from_url($productImage);
                                        set_post_thumbnail($product_id, $imageAttachID);
                                    }
                                } else {
                                    $imageAttachID = pld_insert_attachment_from_url($productImage);
                                    set_post_thumbnail($product_id, $imageAttachID);
                                }
                            
                                if(!empty($ParentAttributes)){
                                    foreach($ParentAttributes as $k => $v){
                                        $caption = $v->Caption;
                                        if(isset($v->AttributeList) && !empty($v->AttributeList)){
                                            $yourRawAttributeList[$caption] = $v->AttributeList;
                                        }
                                    }
                                }
                                update_post_meta($product_id, '_regular_price', 10.0);
                                update_post_meta($product_id, '_price', 10.0);
                                update_post_meta($product_id, '_sku', $sku);
                                update_post_meta($product_id, '_width', $width);
                                update_post_meta($product_id, '_height', $height);
                                // update_post_meta($product_id, '_stock', $qty);
                                update_post_meta($product_id, '_weight', $mass);
                                update_post_meta($product_id, 'category', $category);
                                update_post_meta($product_id, 'subCategory', $subCategory);
                                update_post_meta($product_id, 'productImage', $productImage);
                                // update_post_meta($product_id, '_variation_description', $title);
                                if(!empty($yourRawAttributeList)){
                                    $attribs = generate_attributes_list_for_product($yourRawAttributeList);
                                    $p = new WC_Product_Variable($product_id);
                                    $p->set_props(array(
                                        'attributes'        => $attribs
                                    ));
                                    $postID = $p->save();
                                    if ($postID <= 0) continue;
                                    if(!empty($attribs)){
                                        foreach ($attribs as $attrib) {						
                                            $tax = $attrib->get_name();
                                            $vals = $attrib->get_options();
                                            $termsToAdd = array();
                                            if (is_array($vals) && count($vals) > 0){
                                                foreach ($vals as $val){
                                                    $term = get_attribute_term($val, $tax);
                                                    if ($term['id']) $termsToAdd[] = $term['id'];
                                                }
                                            }
                                            if (count($termsToAdd) > 0)	{
                                                wp_set_object_terms($postID, $termsToAdd, $tax, true);
                                            }
                                        }
                                        if(!empty($RelatedItems)){               
                                            foreach($RelatedItems as $k => $v){
                                                $attr = $v->WebstoreAttributes;
                                                $v_sku = $v->RelatedItemPartNumber;
                                                $v_qty = 0;
                                                $variation_sku_data = import_get_product_by_sku($v_sku);
                                                // echo "<pre>";print_r($variation_sku_data);echo "</pre>";
                                                
                                                $v_weight = $v_width = $v_height = '';
                                                if($v->ChildItemDetail){
                                                    $child_Details = $v->ChildItemDetail;
                                                    if(!empty($child_Details)){
                                                        if(isset($child_Details->SumOnHand)){
                                                            $v_qty = $child_Details->SumOnHand;
                                                        }
                                                        if(isset($child_Details->Mass)){
                                                            $v_weight = $child_Details->Mass;
                                                        }
                                                        if(isset($child_Details->Width)){
                                                            $v_width = $child_Details->Width;
                                                        }
                                                        if(isset($child_Details->Height)){
                                                            $v_height = $child_Details->Height;
                                                        }
                                                    }
                                                }
                                                if($variation_sku_data){
                                                    $var_id = $variation_sku_data->get_id();
                                                    update_post_meta($var_id, '_regular_price', 10.0);
                                                    update_post_meta($var_id, '_price', 10.0);
                                                    update_post_meta($var_id, '_width', $v_width);
                                                    update_post_meta($var_id, '_height', $v_height);
                                                    update_post_meta($var_id, '_stock', $v_qty);
                                                    update_post_meta($var_id, '_weight', $v_weight);
                                                } else{
                                                    
                                                    $all_var = array();
                                                    if(!empty($attr)){
                                                        foreach($attr as $var){
                                                            if(!empty($var->AttributeValue)){
                                                                $all_var[$var->Caption] = $var->AttributeValue;
                                                            }
                                                        }
                                                        $variation_data =  array(
                                                            'attributes' => $all_var,
                                                            'sku'           => $v_sku,
                                                            'width'           => $v_width,
                                                            'height'           => $v_height,
                                                            'weight'           => $v_weight,
                                                            'regular_price' => '1.00',
                                                            'sale_price'    => '',
                                                            'stock_qty'     => $v_qty,
                                                        );
                                                        create_product_variation( $postID, $variation_data);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            } elseif($productType == 'variation'){
                                $variation_id = $webstoreProduct;
                                update_post_meta($product_id, '_regular_price', 10.0);
                                update_post_meta($product_id, '_price', 10.0);
                                update_post_meta($product_id, '_sku', $sku);
                                update_post_meta($product_id, '_width', $width);
                                update_post_meta($product_id, '_height', $height);
                                update_post_meta($product_id, '_stock', $qty);
                                update_post_meta($product_id, '_weight', $mass);
                                // update_post_meta($product_id, 'category', $category);
                                // update_post_meta($product_id, 'subCategory', $subCategory);
                                // update_post_meta($product_id, 'productImage', $productImage);
                                
                            }
                        } else {
                            $insertedUserCnt++;
                            $post_data = array(
                                'post_title' => $title,
                                'post_content' => "",
                                'post_status' => $postStatus,
                                'post_type' => "product",
                            );                               
                            $product_id = wp_insert_post( $post_data );
                            $product = new WC_Product_Variable( $product_id );
                            $product->save();                                
                            if( ! empty($sku) )
                                $product->set_sku($sku);

                            if( ! empty($mass) ){
                                $product->set_weight(''); 
                            } else{
                                $product->set_weight($mass);
                            }
                            wp_set_object_terms($product_id, $productType, 'product_type');
                                
                            $product->validate_props();
                            
                            $product->save();
                            $existing_category = get_term_by('name', $category, 'product_cat');
                            if ($existing_category) {
                                $existing_category_id = $existing_category->term_id;
                            } else {
                                $category_detail = wp_insert_term($category, 'product_cat');
                                $existing_category_id = $category_detail['term_id'];
                            }
                            $category_detail = wp_set_object_terms($product_id, $existing_category_id, 'product_cat');
                            if (!empty($subCategory)) {
                                if (!term_exists($subCategory, 'product_cat')) {
                                    $subcategory_detail = wp_insert_term($subCategory, 'product_cat', array('parent' => $existing_category_id));
                                    wp_set_object_terms($product_id, array($existing_category_id, $subcategory_detail['term_id']), 'product_cat');
                                } else {
                                    $existing_subcategory = get_term_by('name', $subCategory, 'product_cat');
                                    wp_set_object_terms($product_id, array($existing_category_id, $existing_subcategory->term_id), 'product_cat');
                                }
                            }                                   
                            $productImage = $imagepath . $productImage;
                            $productImageWithoutFilter = explode($imagepath, $productImage);
                            $productImage = end($productImageWithoutFilter);
                            $productImage = $imagepath . $productImage;

                            $imageAttachID = pld_insert_attachment_from_url($productImage);
                            set_post_thumbnail($product_id, $imageAttachID);

                            $attribs = array();
                            if(!empty($ParentAttributes)){
                                foreach($ParentAttributes as $k => $v){
                                    $caption = $v->Caption;
                                    if(isset($v->AttributeList) && !empty($v->AttributeList)){
                                        $yourRawAttributeList[$caption] = $v->AttributeList;
                                    }
                                }
                            }
                            if(!empty($yourRawAttributeList)){
                                $attribs = generate_attributes_list_for_product($yourRawAttributeList);
                                $p = new WC_Product_Variable($product_id);
                                $p->set_props(array(
                                    'attributes'        => $attribs
                                ));
                                $postID = $p->save();
                                if ($postID <= 0) continue;
                                if(!empty($attribs)){
                                    foreach ($attribs as $attrib) {						
                                        $tax = $attrib->get_name();
                                        $vals = $attrib->get_options();
                                        $termsToAdd = array();
                                        if (is_array($vals) && count($vals) > 0){
                                            foreach ($vals as $val){
                                                $term = get_attribute_term($val, $tax);
                                                if ($term['id']) $termsToAdd[] = $term['id'];
                                            }
                                        }
                                        if (count($termsToAdd) > 0)	{
                                            wp_set_object_terms($postID, $termsToAdd, $tax, true);
                                        }
                                    }
                                
                                    if(!empty($RelatedItems)){               
                                        foreach($RelatedItems as $k => $v){
                                            $attr = $v->WebstoreAttributes;
                                            $v_sku = $v->RelatedItemPartNumber;
                                            $v_qty = 0;
                                            if($v->ChildItemDetail){
                                                $child_Details = $v->ChildItemDetail;
                                                if(!empty($child_Details) && isset($child_Details->SumOnHand)){
                                                    $v_qty = $child_Details->SumOnHand;
                                                }
                                            }
                                            $all_var = array();
                                            if(!empty($attr)){                                               
                                                foreach($attr as $var){
                                                    if(!empty($var->AttributeValue)){
                                                        $all_var[$var->Caption] = $var->AttributeValue;
                                                    }
                                                }
                                                $variation_data =  array(
                                                    'attributes' => $all_var,
                                                    'sku'           => $v_sku,
                                                    'regular_price' => '1.00',
                                                    'sale_price'    => '',
                                                    'stock_qty'     => $v_qty,
                                                );
                                                create_product_variation( $postID, $variation_data);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if (empty($webstoreProduct)) {                            
                            $product_id = wp_insert_post(array(
                                'post_title' => $title,
                                'post_content' => "",
                                'post_status' => $postStatus,
                                'post_type' => "product",
                            ));

                            $insertedUserCnt++;
                            if (!is_wp_error($product_id)) {
                                update_post_meta($product_id, '_regular_price', '10.0');
                                update_post_meta($product_id, '_price', 10.0);
                                update_post_meta($product_id, '_sku', $sku);
                                update_post_meta($product_id, '_weight', $mass);
                                update_post_meta($product_id, '_width', $mass);
                                update_post_meta($product_id, '_height', $height);
                                update_post_meta($product_id, '_stock', $qty);
                                wp_set_object_terms($product_id, $productType, 'product_type');
                                add_post_meta($product_id, 'Field1', $product->Field1);
                                add_post_meta($product_id, 'Field2', $product->Field2);
                                add_post_meta($product_id, 'Field3', $product->Field3);
                                add_post_meta($product_id, 'Field4', $product->Field4);
                                add_post_meta($product_id, 'Field5', $product->Field5);
                                add_post_meta($product_id, 'Field6', $product->Field6);

                                
                                $existing_category = get_term_by('name', $category, 'product_cat');
                                if ($existing_category) {
                                    $existing_category_id = $existing_category->term_id;
                                } else {
                                    $category_detail = wp_insert_term($category, 'product_cat');
                                    $existing_category_id = $category_detail['term_id'];
                                }
                                $category_detail = wp_set_object_terms($product_id, $existing_category_id, 'product_cat');
                                if (!empty($subCategory)) {
                                    if (!term_exists($subCategory, 'product_cat')) {
                                        $subcategory_detail = wp_insert_term($subCategory, 'product_cat', array('parent' => $existing_category_id));
                                        wp_set_object_terms($product_id, array($existing_category_id, $subcategory_detail['term_id']), 'product_cat');
                                    } else {
                                        $existing_subcategory = get_term_by('name', $subCategory, 'product_cat');
                                        wp_set_object_terms($product_id, array($existing_category_id, $existing_subcategory->term_id), 'product_cat');
                                    }
                                }                                   
                                $productImage = $imagepath . $productImage;
                                $productImageWithoutFilter = explode($imagepath, $productImage);
                                $productImage = end($productImageWithoutFilter);
                                $productImage = $imagepath . $productImage;

                                $imageAttachID = pld_insert_attachment_from_url($productImage);
                                set_post_thumbnail($product_id, $imageAttachID);
                            }
                        } else {
                            if (is_object($webstoreProduct)) {
                                $product_id = $webstoreProduct->get_id();
                                $productImage = $imagepath . $productImage;
                                $product_id = wp_update_post(array(
                                    'ID' => $product_id,
                                    'post_title' => $title,
                                    'post_content' => "",
                                    'post_status' => $postStatus,
                                    'post_type' => "product",
                                ));
                                $updatedUserCnt++;
                                if (!is_wp_error($product_id)) {
                                    update_post_meta($product_id, '_regular_price', '10.0');
                                    update_post_meta($product_id, '_price', 10.0);
                                    update_post_meta($product_id, '_sku', $sku);
                                    update_post_meta($product_id, '_weight', $mass);
                                    update_post_meta($product_id, '_width', $mass);
                                    update_post_meta($product_id, '_height', $height);
                                    update_post_meta($product_id, '_stock', $qty);
                                    update_post_meta($product_id, 'Field1', $product->Field1);
                                    update_post_meta($product_id, 'Field2', $product->Field2);
                                    update_post_meta($product_id, 'Field3', $product->Field3);
                                    update_post_meta($product_id, 'Field4', $product->Field4);
                                    update_post_meta($product_id, 'Field5', $product->Field5);
                                    update_post_meta($product_id, 'Field6', $product->Field6);
                                    wp_set_object_terms($product_id, $productType, 'product_type');
                                    $existing_category = get_term_by('name', $category, 'product_cat');
                                    if ($existing_category) {
                                        $existing_category_id = $existing_category->term_id;
                                    } else {
                                        $category_detail = wp_insert_term($category, 'product_cat');
                                        $existing_category_id = $category_detail['term_id'];
                                    }
                                    $category_detail = wp_set_object_terms($product_id, $existing_category_id, 'product_cat');
                                    if (!empty($subCategory)) {
                                        if (!term_exists($subCategory, 'product_cat')) {
                                            $subcategory_detail = wp_insert_term($subCategory, 'product_cat', array('parent' => $existing_category_id));
                                            wp_set_object_terms($product_id, array($existing_category_id, $subcategory_detail['term_id']), 'product_cat');
                                        } else {
                                            $existing_subcategory = get_term_by('name', $subCategory, 'product_cat');
                                            if (!$existing_subcategory) {
                                                $subcategory_detail = wp_insert_term($subCategory, 'product_cat', array('parent' => $existing_category_id));
                                                wp_set_object_terms($product_id, array($existing_category_id, $subcategory_detail['term_id']), 'product_cat');
                                            } else {
                                                wp_set_object_terms($product_id, array($existing_category_id, $existing_subcategory->term_id), 'product_cat');
                                            }
                                        }
                                    }                                        
                                    $productImage = $imagepath . $productImage;
                                    $productImageWithoutFilter = explode($imagepath, $productImage);
                                    $productImage = end($productImageWithoutFilter);
                                    $productImage = $imagepath . $productImage;

                                    if (has_post_thumbnail($product_id)) {
                                        $attachment_id = get_post_thumbnail_id($product_id);
                                        $kd_featured_image_url = wp_get_attachment_url($attachment_id);
                                        $image_url_base = basename($productImage);
                                        $kd_featured_image_base = basename($kd_featured_image_url);
                                        if ($kd_featured_image_base != $image_url_base) {
                                            wp_delete_attachment($attachment_id, true);
                                            $imageAttachID = pld_insert_attachment_from_url($productImage);
                                            set_post_thumbnail($product_id, $imageAttachID);
                                        }
                                    } else {
                                        $imageAttachID = pld_insert_attachment_from_url($productImage);
                                        set_post_thumbnail($product_id, $imageAttachID);
                                    }
                                }
                            }
                        }
                    }
                }               
                $i++;
                
                if ($i > $totalPages) {
                    update_option('pld_product_last_import_timestamp', date('Y-m-d h:i:s'));
                }
                
                $data = array(
                    'status' => 'success',
                    'response' => 'Cron run successfully.',
                    'body_response' => 200,
                    'pageIndex' => $i,
                    'totalPages' => $totalPages,
                    'insertedRecords' => $insertedUserCnt,
                    'updatedRecords' => $updatedUserCnt,
                    'skippedRecords' => $skippedUserCnt,
                    'skippedEmail' => json_encode($skippedEmail),
                    'skippedUsername' => json_encode($skippedUsername)
                );
                echo json_encode($data);wp_die();
                
		} else {
            $data = array(
                'status' => 'failed',
                'response' => 'No Records found in API Response!',
                'body_response' => 200,
				'pageIndex' => 0,
				'totalPages' => 0,
				'error_index' => $i,
				'data' => json_encode($data),
				'curl_handle' => json_decode($curl_handle)
            );
			echo json_encode($data);
            wp_die();
        }
	} 
           
    } else{
		$data = array(
			'status' => 'failed',
			'response' => 'Something went wrong with your request! Please contact to your site admin.',
			'body_response' => 200,
			'pageIndex' => 0,
			'totalPages' => 0
		);
		echo json_encode($data);wp_die();
	}
}

add_action( "wp_ajax_nopriv_price_data", "wp_ajax_price_data" );
add_action( "wp_ajax_price_data", "wp_ajax_price_data" );
function wp_ajax_price_data(){
    global $wpdb;
    $tablename = $wpdb->prefix . 'palladium_customer_prices';
	if(!empty($_POST)){ //Check whether data are posted or not
		$postData = $_POST;
	 
		$options = get_option('pld_api_options');        
        if (
                isset($options) && !empty($options) &&
                !empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
                !empty($options['pld_webstore_get_pricelist_from_api_path'])
        ) {

            $pld_webstore_endpoint = $options['pld_webstore_endpoint'];
            $pld_webstore_database = $options['pld_webstore_database'];
            $pld_webstore_get_pricelist_from_api_path = $options['pld_webstore_get_pricelist_from_api_path'];
            $pld_webstore_get_pricelist_from_api_path = '/InventoryPicker/GetPriceListsWebStoreByPaging';

            $price_last_import_timestamp_options = get_option('pld_price_last_import_timestamp') ? get_option('pld_price_last_import_timestamp') : '2018-01-01';
            // $price_last_import_timestamp_options = '2018-01-01';
            $url = $pld_webstore_endpoint . $pld_webstore_get_pricelist_from_api_path;
            // $i = 1;
            $total_count = $total_pages = 0;
            $max_per_page = 1000;
            $updatedPricecnt = 0;
            $insertedPricecnt = 0;
            $updated_sku = array();
            $today_date = date("Y-m-d");
           
            if($postData['requestType'] == 'load'){ //Initial load time set Page Index                
				$postData['pageIndex'] = 1;

			}
            $i = $postData['pageIndex'];

           
            $data = '["'.$i.'","'.$max_per_page.'","' . $price_last_import_timestamp_options . '"]';
            $headerArray = array(
                "Content-Type: application/json",
                "auth-database: " . $pld_webstore_database,
            );
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, $url);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10000);
            curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headerArray);

            $curl_data = curl_exec($curl_handle);
            $httpcode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
            curl_close($curl_handle);

            $customer_prices = json_decode($curl_data);
            $customers_prices_Data = $customer_prices->Data;
            // echo "<pre>";print_r($customers_prices_Data);echo "</pre>";
            
            if (isset($customers_prices_Data) && !empty($customers_prices_Data)) {
                $totalPages = $customers_prices_Data[0]->TotalPages;
                foreach ($customers_prices_Data as $key => $customersPrice) {
                    $partNumber = $customersPrice->PartNumber;
                    $desc = $customersPrice->Desc;
                    $lastPPUnit = $customersPrice->LastPPUnit;
                    $selling = $customersPrice->Selling;
                    $category = $customersPrice->Category;
                    $sellFromStock = $customersPrice->SellFromStock;
                    $estLocalCost = $customersPrice->EstLocalCost;
                    $estImportCost = $customersPrice->EstImportCost;
                    $pricelist = $customersPrice->Pricelist;
                    $priceBasedID = $customersPrice->PriceBasedID;
                    $startDate = $customersPrice->StartDate;
                    $finishDate = $customersPrice->FinishDate;

                    if ($partNumber && $pricelist) {
                        $check_query = $wpdb->prepare("SELECT customerprice_id,product_sku, price_list "
                                . "FROM $tablename "
                                . "WHERE product_sku = %s AND price_list = %s ", $partNumber, $pricelist);
                        // echo $check_query;die;
                        $check_if_exist = $wpdb->get_row($check_query, ARRAY_A);
                        // echo "<pre>";print_r($check_if_exist);echo "</pre>"
                        $data = array(
                            'product_sku' => $partNumber,
                            'description' => $desc,
                            'lastpp_unit' => $lastPPUnit,
                            'selling' => $selling,
                            'category' => $category,
                            'sell_from_stock' => $sellFromStock,
                            'est_local_cost' => $estLocalCost,
                            'est_import_cost' => $estImportCost,
                            'price_list' => $pricelist,
                            'price_based_id' => $priceBasedID,
                            'start_date' => $startDate,
                            'finish_date' => $finishDate
                        );

                        // if already exist in database, just update otherwise insert new
                        if ($check_if_exist) {
                            $updated_sku[$key]['sku'] = $partNumber;
                            $updated_sku[$key]['price'] = $pricelist;
                            $updated_sku[$key]['customerprice_id'] = $check_if_exist['customerprice_id'];
                            $updated_sku[$key]['query'] = $check_query;
                            
                            $updatedPricecnt++;
                            $wpdb->update($tablename, $data, array('customerprice_id' => $check_if_exist['customerprice_id']));
                        } else {
                            $insertedPricecnt++;
                            $wpdb->insert($tablename, $data);
                        }
                    }
                }
                if ($totalPages <= $i) {
                    // break;
                }
                $i++;
                
                if ($i > $totalPages) {						
                    update_option('pld_price_last_import_timestamp', $today_date);
                }
                
                $data = array(
                    'status' => 'success',
                    'response' => 'Cron run successfully.',
                    'body_response' => 200,
                    'pageIndex' => $i,
                    'totalPages' => $totalPages,
                    'insertedRecords' => $insertedPricecnt,
                    'updatedRecords' => $updatedPricecnt,
                    'updated_sku' => $updated_sku,
                    // 'skippedUsername' => json_encode($skippedUsername)
                );
                echo json_encode($data);wp_die();					
            } else {
                $data = array(
                    'status' => 'failed',
                    'response' => 'No Records found in API Response!',
                    'body_response' => 200,
                    'pageIndex' => 0,
                    'totalPages' => 0,
                    'error_index' => $i,
                    'data' => json_encode($data),
                    'curl_handle' => json_decode($curl_handle)
                );
                echo json_encode($data);
                // sendMail_for_API_record_update('', '', 'Customer price API import failed', 'Customer price API failed to update the record or No record found in API. Failed API date: "'. $today_date .'" And page index: "'.$i.'"');
                wp_die();
            }
	    } 
    } else{
		$data = array(
			'status' => 'failed',
			'response' => 'Something went wrong with your request! Please contact to your site admin.',
			'body_response' => 200,
			'pageIndex' => 0,
			'totalPages' => 0
		);
        //sendMail_for_API_record_update('', '', 'Customer price API import failed', 'Customer price API failed to update the record or No record found in API. Failed API date: "'. $today_date .'"');
		echo json_encode($data);wp_die();
	}
}

/**
 * Send mail when record will not update/failed to update
 */
function sendMail_for_API_record_update($to, $from, $subject, $message){
    //php mailer variables
    if(empty($to)){
        $to = get_option('admin_email');
    }
    if(empty($from)){
        $from = $to;
    }
    if(empty($subject)){
        $subject = "Import status for API";
    }
    
    $headers = 'From: '.  $from . "\r\n" .
        'Reply-To: ' .  $to . "\r\n";
    //Here put your Validation and send mail
    $sent = wp_mail($to, $subject, strip_tags($message), $headers);
    return $sent;
}

function import_get_product_by_sku($sku) {
        global $wpdb;
        $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku));
        if ($product_id) {
            return wc_get_product($product_id);
        }

        return null;

}

function pld_insert_attachment_from_url($url, $parent_post_id = null) {

    if (!class_exists('WP_Http'))
        include_once( ABSPATH . WPINC . '/class-http.php' );

    $http = new WP_Http();
    $response = $http->request($url);
    if (is_wp_error($response) || $response['response']['code'] != 200) {
        return false;
    }

    $upload = wp_upload_bits(basename($url), null, $response['body']);
    if (!empty($upload['error'])) {
        return false;
    }

    $file_path = $upload['file'];
    $file_name = basename($file_path);
    $file_type = wp_check_filetype($file_name, null);
    $attachment_title = sanitize_file_name(pathinfo($file_name, PATHINFO_FILENAME));
    $wp_upload_dir = wp_upload_dir();

    $post_info = array(
        'guid' => $wp_upload_dir['url'] . '/' . $file_name,
        'post_mime_type' => $file_type['type'],
        'post_title' => $attachment_title,
        'post_content' => '',
        'post_status' => 'inherit',
    );
    $attach_id = wp_insert_attachment($post_info, $file_path, $parent_post_id);
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);

    wp_update_attachment_metadata($attach_id, $attach_data);

    return $attach_id;
}

/**
 * Function for create product variations
 */
function create_product_variation( $product_id, $variation_data ){
    global $products;
    $product = wc_get_product($product_id);
    $parent_sku = $product->get_sku();
    $variation_post = array(
        'post_title'  => $product->get_name(),
        'post_name'   => 'product-'.$product_id.'-variation',
        'post_status' => 'publish',
        'post_parent' => $product_id,
        'post_type'   => 'product_variation',
        'guid'        => $product->get_permalink()
    );
    $variation_id = wp_insert_post( $variation_post );
    $variation = new WC_Product_Variation( $variation_id );
    foreach ($variation_data['attributes'] as $attribute => $term_name ){
        $existingTaxes = wc_get_attribute_taxonomies();
        $attribute_labels = wp_list_pluck( $existingTaxes, 'attribute_label', 'attribute_name' );
        $attr_slug = array_search( $attribute, $attribute_labels, true );
        if(empty($attr_slug)){
            $attr_slug = $attribute;
        }
        $taxonomy = 'pa_'.$attr_slug;
        if( ! taxonomy_exists( $taxonomy ) ){
            register_taxonomy(
                $taxonomy,
               'product_variation',
                array(
                    'hierarchical' => false,
                    'label' => ucfirst( $attr_slug ),
                    'query_var' => true,
                    'rewrite' => array( 'slug' => sanitize_title($attr_slug) ), // The base slug
                )
            );
        }
        if( ! term_exists( $term_name, $taxonomy ) )
            wp_insert_term( $term_name, $taxonomy );

        $term_slug = get_term_by('name', $term_name, $taxonomy )->slug;
        $post_term_names =  wp_get_post_terms( $product_id, $taxonomy, array('fields' => 'names') );
        if( ! in_array( $term_name, $post_term_names ) )
            wp_set_post_terms( $product_id, $term_name, $taxonomy, true );
        update_post_meta( $variation_id, 'attribute_'.$taxonomy, $term_slug );
    }
    $sku_exist = false;
    if(!empty($variation_data['sku'])){
        $webstoreProduct = import_get_product_by_sku($variation_data['sku']);
        if($webstoreProduct || $parent_sku == $variation_data['sku']){
            $sku_exist = true;
        }
    }

    if( ! empty( $variation_data['sku'] ) && $sku_exist == false)
        $variation->set_sku( $variation_data['sku'] );
    if( empty( $variation_data['sale_price'] ) ){
        $variation->set_price( $variation_data['regular_price'] );
    } else {
        $variation->set_price( $variation_data['sale_price'] );
        $variation->set_sale_price( $variation_data['sale_price'] );
    }
    $variation->set_regular_price( $variation_data['regular_price'] );
    
    if( isset($variation_data['weight']) && ! empty($variation_data['weight']) && $variation_data['weight'] > 0){
        $variation->set_weight( $variation_data['weight'] );
    }
    if( isset($variation_data['width']) && ! empty($variation_data['width']) && $variation_data['width'] > 0){
        $variation->set_width( $variation_data['width'] );
    }
    if( isset($variation_data['height']) && ! empty($variation_data['height']) && $variation_data['height'] > 0){
        $variation->set_height( $variation_data['height'] );
    }
    if( ! empty($variation_data['stock_qty']) && $variation_data['stock_qty'] > 0){
        $variation->set_stock_quantity( $variation_data['stock_qty'] );
        $variation->set_manage_stock(true);
        $variation->set_stock_status('instock');
    } else {
        $variation->set_manage_stock(false);
    }    
    $variation->save();
}


function create_global_attribute($name, $slug){
    $taxonomy_name = wc_attribute_taxonomy_name( $slug );
    if (taxonomy_exists($taxonomy_name)) {
        return wc_attribute_taxonomy_id_by_name($slug);
    }
    $attribute_id = wc_create_attribute( array(
        'name'         => $name,
        'slug'         => $slug,
        'type'         => 'select',
        'order_by'     => 'menu_order',
        'has_archives' => false,
    ) );

    register_taxonomy(
        $taxonomy_name,
        apply_filters( 'woocommerce_taxonomy_objects_' . $taxonomy_name, array( 'product' ) ),
        apply_filters( 'woocommerce_taxonomy_args_' . $taxonomy_name, array(
            'labels'       => array(
                'name' => $name,
            ),
            'hierarchical' => true,
            'show_ui'      => false,
            'query_var'    => true,
            'rewrite'      => false,
        ) )
    );
    delete_transient( 'wc_attribute_taxonomies' );
    return $attribute_id;
}

//$rawDataAttributes must be in the form of array("Color"=>array("blue", "red"), "Size"=>array(12,13,14),... etc.)
/**
 * Function for assign attribute list to product
 * formate - array("Color"=>array("blue", "red"), "Size"=>array(12,13,14))
 */
function generate_attributes_list_for_product($rawDataAttributes) {
    $attributes = array();
    $pos = 0;
    foreach ($rawDataAttributes as $name => $values){
        if (empty($name) || empty($values)) continue;

        if (!is_array($values)) $values = array($values);

        $attribute = new WC_Product_Attribute();
        $attribute->set_id( 0 );
        $attribute->set_position($pos);
        $attribute->set_visible( true );
        $attribute->set_variation( true );

        $pos++;
        $existingTaxes = wc_get_attribute_taxonomies();
        $attribute_labels = wp_list_pluck( $existingTaxes, 'attribute_label', 'attribute_name' );
        $slug = array_search( $name, $attribute_labels, true );

        if (!$slug){
            $slug = wc_sanitize_taxonomy_name($name);
            $attribute_id = create_global_attribute($name, $slug);
        }else{
            //Taxonomies are in the format: array("slug" => 12, "slug" => 14)
            $taxonomies = wp_list_pluck($existingTaxes, 'attribute_id', 'attribute_name');
            if (!isset($taxonomies[$slug])){
                continue;
            }
            $attribute_id = (int)$taxonomies[$slug];
        }
        $taxonomy_name = wc_attribute_taxonomy_name($slug);
        $attribute->set_id( $attribute_id );
        $attribute->set_name( $taxonomy_name );
        $attribute->set_options($values);
        $attributes[] = $attribute;
    }
    return $attributes;
}


function get_attribute_term($value, $taxonomy) {
    $term = get_term_by('name', $value, $taxonomy);

    if (!$term){
        
        $term = wp_insert_term($value, $taxonomy);
        if (is_wp_error($term)){        
            return array('id'=>false, 'slug'=>false);
        }
        $termId = $term['term_id'];
        $term_slug = get_term($termId, $taxonomy)->slug; // Get the term slug
    }else {
        $termId = $term->term_id;
        $term_slug = $term->slug;
    }
    return array('id'=>$termId, 'slug'=>$term_slug);
}


function woo_custom_ajax_variation_threshold( $qty, $product ) {
    return 50;
}       
add_filter( 'woocommerce_ajax_variation_threshold', 'woo_custom_ajax_variation_threshold', 10, 2 );