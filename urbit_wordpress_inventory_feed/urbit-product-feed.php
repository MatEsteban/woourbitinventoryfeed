<?php
/**
 * Plugin Name: Urbit Product Feed
 * Plugin URI: https://urb-it.com/
 * Description: Urbit Product Feed plugin for Woocommerce.
 * Version: 1.0.2
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
require_once URBIT_PRODUCT_FEED_CLASS_DIR . '/_init.php';

/*
 * Run plugin
 */
$UPF = new UPF_Core(__FILE__);

/*
 * Run admin
 */
if (is_admin()) {
    $UPFAdmin = new UPF_Admin_Core($UPF);
}
