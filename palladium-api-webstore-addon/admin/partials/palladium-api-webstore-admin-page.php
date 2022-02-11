<?php
add_action('wp_ajax_nopriv_order_sync', 'proceed_order_sync');
add_action('wp_ajax_order_sync', 'proceed_order_sync');

function proceed_order_sync() { 
    ob_start();
    $order_id       = (isset($_POST['order_id'])) ? $_POST['order_id'] : 0;
    $order = wc_get_order($order_id);
    $order_data = $order->get_data();
    // print_r($order_data);
    // exit();
	$user = $order->get_user(); // Get the WP_User object
    $userData = $user->data;
    // print_r($user->data);
    $user_cust_ID = $userData->ID;
    if($user_cust_ID) {
        echo "chhe";
        $falg = get_user_meta($user_cust_ID, 'register_flag');
        if($falg[0] != 2) {
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
            $currency = pld_get_country_currency($country_code);
            $country_name = pld_show_country($country_code);
            // print_r($currency);
            // print_r($country_name);
            // exit();
            $customer_id = $user_cust_ID;
            update_user_meta($customer_id, 'customer_unique_id', $unique_name);

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
				// print_r($DefaultTerm);
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
                $user_cust_ID && isset($options) && !empty($options) &&
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
                print_r($response);
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
                    // $falgValue = get_user_meta($customer_id, 'register_flag');
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
        // echo "nathi";
    }
    // exit();
    
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



        $user_id = $order->get_user_id(); // Get the costumer ID
        $user = $order->get_user(); // Get the WP_User object
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
        $DefaultTaxCode 	= $results->DefaultTaxCode;
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
            // print_r("<pre>");
            // print_r($item);
            // print_r($product);
            
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
        if (isset($user_id)) {
            $unique_id = get_user_meta($user_id, 'customer_unique_id', true);
            // print_r($unique_id);
            if (empty($unique_id)) {
                $unique_name = strtoupper(substr($order_billing_first_name, 0, 3)) . 'WOO' . rand(0, 10000);
                update_user_meta($user_id, 'customer_unique_id', $unique_name);
                $unique_id = get_user_meta($user_id, 'customer_unique_id', true);
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
            'ShipTo' => $order_shipping_first_name . $order_shipping_last_name . '\r\n' . $order_shipping_address_1 . '\r\n' . $order_shipping_address_2 . '\r\n' . $order_shipping_country,
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
        print_r("<pre>");
        print_r($orderApiData);
die("710");
        $displayName = $user->display_name;

        $options = get_option('pld_api_options');
        // print_r($orderApiData);

		if (
			$orderApiData && isset($options) && !empty($options) &&
			!empty($options['pld_webstore_endpoint']) && !empty($options['pld_webstore_database']) &&
			!empty($options['pld_webstore_orders_api_path'])
		) {

			/**
			 * get api options and generate request based on that
			 */
			$pld_webstore_endpoint = $options['pld_webstore_endpoint'];
			$pld_webstore_database = $options['pld_webstore_database'];
			$pld_webstore_orders_api_path = $options['pld_webstore_orders_api_path'];

			$order_api_data = json_encode($orderApiData);
            
			/**
			 * send API request via cURL
			 */
			$ch = curl_init();
			$headerArray = array(
                "Content-Type: application/json",
                "auth-database: " . $pld_webstore_database,
			);
            // print_r($pld_webstore_orders_api_path);
			/**
			 * set the complete URL, to process the order on the external system
			 */
			curl_setopt($ch, CURLOPT_URL, $pld_webstore_endpoint . $pld_webstore_orders_api_path);
            curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $order_api_data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
			$response = curl_exec($ch);
            print_r($response);
            // print_r("here");
            // exit();
			/* Write orders logs */
			// $upload_dir = wp_upload_dir();
			// $orders_dirname = $upload_dir['basedir'] . '/palldium-api-logs/orders';
			// if (!file_exists($orders_dirname)) {
			// mkdir($orders_dirname, 0777, true);
			// }

			// $order_log_filename = $orders_dirname . '/order_' . $order_id . '.log';

			// $logfile = fopen($order_log_filename, "a");
			// $log_text = "[" . date('Y-m-d H:i:s') . "] : Sending Order details to Palladium API";
			// $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : Order ID : " . $order_id;
			// $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : User : " . $displayName;
			// $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : API URL : " . $pld_webstore_endpoint . $pld_webstore_orders_api_path;
			// $log_text .= "\n=====================";
			// $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : Order Request in API : ";
			// $log_text .= "\n" . $order_api_data;
			// $log_text .= "\n=====================";
			// $log_text .= "\n[" . date('Y-m-d H:i:s') . "] : Response from API : ";
			// $log_text .= "\n" . $response;
			// $log_text .= "\n=====================\n";

			// fwrite($logfile, $log_text);
			// fclose($logfile);
			// curl_close($ch);
			/* End - Write orders logs */

			curl_close($ch);

			$jsonDecode = json_decode($response);
			if ($jsonDecode->IsSuccess == true) {
                update_post_meta($order_id, '_the_meta_key1', 'Yes');
                $success = get_post_meta( $order_id, '_the_meta_key1', true );
				print_r('done');
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
    $content = ob_get_clean();
    $retrun = array('content' => $content, 'status' => "1" , 'sync_order'=>$success  ,"order_tr_id"=>'post-'.$order_id,);
    echo json_encode($retrun);
    exit;
    // return $order_id;
}

    // get currency from country code
    function pld_get_country_currency($key) {
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
    function pld_show_country($key) {
		$country_array = array(
			"AF" => "Afghanistan", "AL" => "Albania", "DZ" => "Algeria", "AS" => "American Samoa", "AD" => "Andorra", "AO" => "Angola", "AI" => "Anguilla", "AQ" => "Antarctica", "AG" => "Antigua and Barbuda", "AR" => "Argentina", "AM" => "Armenia", "AW" => "Aruba", "AU" => "Australia", "AT" => "Austria", "AZ" => "Azerbaijan", "BS" => "Bahamas", "BH" => "Bahrain", "BD" => "Bangladesh", "BB" => "Barbados", "BY" => "Belarus", "BE" => "Belgium", "BZ" => "Belize", "BJ" => "Benin", "BM" => "Bermuda", "BT" => "Bhutan", "BO" => "Bolivia", "BA" => "Bosnia and Herzegovina", "BW" => "Botswana", "BV" => "Bouvet Island", "BR" => "Brazil", "BQ" => "British Antarctic Territory", "IO" => "British Indian Ocean Territory", "VG" => "British Virgin Islands", "BN" => "Brunei", "BG" => "Bulgaria", "BF" => "Burkina Faso", "BI" => "Burundi", "KH" => "Cambodia", "CM" => "Cameroon", "CA" => "Canada", "CT" => "Canton and Enderbury Islands", "CV" => "Cape Verde", "KY" => "Cayman Islands", "CF" => "Central African Republic", "TD" => "Chad", "CL" => "Chile", "CN" => "China", "CX" => "Christmas Island", "CC" => "Cocos [Keeling] Islands", "CO" => "Colombia", "KM" => "Comoros", "CG" => "Congo - Brazzaville", "CD" => "Congo - Kinshasa", "CK" => "Cook Islands", "CR" => "Costa Rica", "HR" => "Croatia", "CU" => "Cuba", "CY" => "Cyprus", "CZ" => "Czech Republic", "CI" => "Côte d’Ivoire", "DK" => "Denmark", "DJ" => "Djibouti", "DM" => "Dominica", "DO" => "Dominican Republic", "NQ" => "Dronning Maud Land", "DD" => "East Germany", "EC" => "Ecuador", "EG" => "Egypt", "SV" => "El Salvador", "GQ" => "Equatorial Guinea", "ER" => "Eritrea", "EE" => "Estonia", "ET" => "Ethiopia", "FK" => "Falkland Islands", "FO" => "Faroe Islands", "FJ" => "Fiji", "FI" => "Finland", "FR" => "France", "GF" => "French Guiana", "PF" => "French Polynesia", "TF" => "French Southern Territories", "FQ" => "French Southern and Antarctic Territories", "GA" => "Gabon", "GM" => "Gambia", "GE" => "Georgia", "DE" => "Germany", "GH" => "Ghana", "GI" => "Gibraltar", "GR" => "Greece", "GL" => "Greenland", "GD" => "Grenada", "GP" => "Guadeloupe", "GU" => "Guam", "GT" => "Guatemala", "GG" => "Guernsey", "GN" => "Guinea", "GW" => "Guinea-Bissau", "GY" => "Guyana", "HT" => "Haiti", "HM" => "Heard Island and McDonald Islands", "HN" => "Honduras", "HK" => "Hong Kong SAR China", "HU" => "Hungary", "IS" => "Iceland", "IN" => "India", "ID" => "Indonesia", "IR" => "Iran", "IQ" => "Iraq", "IE" => "Ireland", "IM" => "Isle of Man", "IL" => "Israel", "IT" => "Italy", "JM" => "Jamaica", "JP" => "Japan", "JE" => "Jersey", "JT" => "Johnston Island", "JO" => "Jordan", "KZ" => "Kazakhstan", "KE" => "Kenya", "KI" => "Kiribati", "KW" => "Kuwait", "KG" => "Kyrgyzstan", "LA" => "Laos", "LV" => "Latvia", "LB" => "Lebanon", "LS" => "Lesotho", "LR" => "Liberia", "LY" => "Libya", "LI" => "Liechtenstein", "LT" => "Lithuania", "LU" => "Luxembourg", "MO" => "Macau SAR China", "MK" => "Macedonia", "MG" => "Madagascar", "MW" => "Malawi", "MY" => "Malaysia", "MV" => "Maldives", "ML" => "Mali", "MT" => "Malta", "MH" => "Marshall Islands", "MQ" => "Martinique", "MR" => "Mauritania", "MU" => "Mauritius", "YT" => "Mayotte", "FX" => "Metropolitan France", "MX" => "Mexico", "FM" => "Micronesia", "MI" => "Midway Islands", "MD" => "Moldova", "MC" => "Monaco", "MN" => "Mongolia", "ME" => "Montenegro", "MS" => "Montserrat", "MA" => "Morocco", "MZ" => "Mozambique", "MM" => "Myanmar [Burma]", "NA" => "Namibia", "NR" => "Nauru", "NP" => "Nepal", "NL" => "Netherlands", "AN" => "Netherlands Antilles", "NT" => "Neutral Zone", "NC" => "New Caledonia", "NZ" => "New Zealand", "NI" => "Nicaragua", "NE" => "Niger", "NG" => "Nigeria", "NU" => "Niue", "NF" => "Norfolk Island", "KP" => "North Korea", "VD" => "North Vietnam", "MP" => "Northern Mariana Islands", "NO" => "Norway", "OM" => "Oman", "PC" => "Pacific Islands Trust Territory", "PK" => "Pakistan", "PW" => "Palau", "PS" => "Palestinian Territories", "PA" => "Panama", "PZ" => "Panama Canal Zone", "PG" => "Papua New Guinea", "PY" => "Paraguay", "YD" => "People's Democratic Republic of Yemen", "PE" => "Peru", "PH" => "Philippines", "PN" => "Pitcairn Islands", "PL" => "Poland", "PT" => "Portugal", "PR" => "Puerto Rico", "QA" => "Qatar", "RO" => "Romania", "RU" => "Russia", "RW" => "Rwanda", "RE" => "Réunion", "BL" => "Saint Barthélemy", "SH" => "Saint Helena", "KN" => "Saint Kitts and Nevis", "LC" => "Saint Lucia", "MF" => "Saint Martin", "PM" => "Saint Pierre and Miquelon", "VC" => "Saint Vincent and the Grenadines", "WS" => "Samoa", "SM" => "San Marino", "SA" => "Saudi Arabia", "SN" => "Senegal", "RS" => "Serbia", "CS" => "Serbia and Montenegro", "SC" => "Seychelles", "SL" => "Sierra Leone", "SG" => "Singapore", "SK" => "Slovakia", "SI" => "Slovenia", "SB" => "Solomon Islands", "SO" => "Somalia", "ZA" => "South Africa", "GS" => "South Georgia and the South Sandwich Islands", "KR" => "South Korea", "ES" => "Spain", "LK" => "Sri Lanka", "SD" => "Sudan", "SR" => "Suriname", "SJ" => "Svalbard and Jan Mayen", "SZ" => "Swaziland", "SE" => "Sweden", "CH" => "Switzerland", "SY" => "Syria", "ST" => "São Tomé and Príncipe", "TW" => "Taiwan", "TJ" => "Tajikistan", "TZ" => "Tanzania", "TH" => "Thailand", "TL" => "Timor-Leste", "TG" => "Togo", "TK" => "Tokelau", "TO" => "Tonga", "TT" => "Trinidad and Tobago", "TN" => "Tunisia", "TR" => "Turkey", "TM" => "Turkmenistan", "TC" => "Turks and Caicos Islands", "TV" => "Tuvalu", "UM" => "U.S. Minor Outlying Islands", "PU" => "U.S. Miscellaneous Pacific Islands", "VI" => "U.S. Virgin Islands", "UG" => "Uganda", "UA" => "Ukraine", "SU" => "Union of Soviet Socialist Republics", "AE" => "United Arab Emirates", "GB" => "United Kingdom", "US" => "United States", "ZZ" => "Unknown or Invalid Region", "UY" => "Uruguay", "UZ" => "Uzbekistan", "VU" => "Vanuatu", "VA" => "Vatican City", "VE" => "Venezuela", "VN" => "Vietnam", "WK" => "Wake Island", "WF" => "Wallis and Futuna", "EH" => "Western Sahara", "YE" => "Yemen", "ZM" => "Zambia", "ZW" => "Zimbabwe", "AX" => "Åland Islands",
		);
		if (array_key_exists($key, $country_array)) {
			$countryFull = $country_array[$key];
		}
		return $countryFull;
    }