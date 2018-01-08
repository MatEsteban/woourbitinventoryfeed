<?php
/**
 * Plugin Name: Urbit Inventory Feed
 * Plugin URI: https://urb-it.com/
 * Description: Urbit Inventory Feed plugin for Woocommerce.
 * Version: 1.0.3
 * Author: Urb-IT
 * Author URI: https://urb-it.com/
 */

/**
 * Init constaints
 */
require_once dirname(__FILE__) . '/constants.php';

/*
 * Init classes
 */
require_once URBIT_INVENTORY_FEED_CLASS_DIR . '/_init.php';

/*
 * Run plugin
 */
$UIF = new UIF_Core(__FILE__);

/*
 * Run admin
 */
if (is_admin()) {
    function load_inventory_feed_styles($hook) {
        if($hook != 'urbit_page_inventory-feed' && $hook != 'urbit-feed_page_inventory-feed') {
            return;
        }
        wp_enqueue_style( 'bootstrap_css', plugins_url('templates/admin/assets/css/bootstrap.min.css', __FILE__) );
        wp_enqueue_style( 'config_css', plugins_url('templates/admin/assets/css/config.css', __FILE__) );
        wp_enqueue_script( 'bootstrap_js', plugins_url('templates/admin/assets/js/bootstrap.js', __FILE__) );
        wp_enqueue_script( 'jquery', plugins_url('templates/admin/assets/js/jquery-3.2.1.min.js', __FILE__) );
        wp_enqueue_script( 'multiselect', plugins_url('templates/admin/assets/js/multiselect.js', __FILE__) );
        wp_enqueue_script( 'config_js', plugins_url('templates/admin/assets/js/config.js', __FILE__) );
    }
    add_action( 'admin_enqueue_scripts', 'load_inventory_feed_styles' );

    $UIFAdmin = new UIF_Admin_Core($UIF);
}
