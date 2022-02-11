<?php

/**
 * Fired during plugin activation
 *
 * @link       https://tjhokopaint.co.za/
 * @since      1.0.0
 *
 * @package    Palladium_Api_Webstore_Addon
 * @subpackage Palladium_Api_Webstore_Addon/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Palladium_Api_Webstore_Addon
 * @subpackage Palladium_Api_Webstore_Addon/includes
 * @author     Palladium Development team <#>
 */
class Palladium_Api_Webstore_Addon_Activator {

    /**
     * Create tables on plugin activation
     *
     * @since    1.0.0
     */
    public static function activate() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $pld_create_customer_price_table_query = "
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

        $pld_create_discount_column_matrix_table_query = "
            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}palladium_discount_matrix_columns` (
                `column_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Column Id',
                `column_name` varchar(255) DEFAULT NULL COMMENT 'Column Name',
                PRIMARY KEY (column_id)
            ) $charset_collate;";

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
        dbDelta($pld_create_customer_price_table_query);
        dbDelta($pld_create_discount_column_matrix_table_query);
        dbDelta($pld_create_discount_matrix_table_query);
        dbDelta($pld_create_taxcode_table_query);
    }

}
