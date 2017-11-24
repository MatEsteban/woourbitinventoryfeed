<?php
/**
 * Load main classes
 */

if (!defined( 'URBIT_PRODUCT_FEED_PLUGIN_DIR' )) {
    exit;
}

/*
 * Abstract classes
 */
require URBIT_PRODUCT_FEED_CLASS_DIR . '/abstract/UPF_Template_Abstract.php';
require URBIT_PRODUCT_FEED_CLASS_DIR . '/abstract/UPF_Admin_Page_Abstract.php';

require URBIT_PRODUCT_FEED_CLASS_DIR . "/UPF_Config.php";
require URBIT_PRODUCT_FEED_CLASS_DIR . "/UPF_Core.php";
require URBIT_PRODUCT_FEED_CLASS_DIR . "/UPF_Query.php";
require URBIT_PRODUCT_FEED_CLASS_DIR . "/UPF_Template.php";
require URBIT_PRODUCT_FEED_CLASS_DIR . "/UPF_Product.php";
require URBIT_PRODUCT_FEED_CLASS_DIR . "/UPF_Feed.php";
require URBIT_PRODUCT_FEED_CLASS_DIR . "/UPF_Cache.php";

/*
 * Init admin classes
 */
require URBIT_PRODUCT_FEED_ADMIN_CLASS_DIR . '/UPF_Admin_Core.php';
require URBIT_PRODUCT_FEED_ADMIN_CLASS_DIR . '/UPF_Admin_Pages.php';
require URBIT_PRODUCT_FEED_ADMIN_CLASS_DIR . '/UPF_Admin_Menu_Element.php';
require URBIT_PRODUCT_FEED_ADMIN_CLASS_DIR . '/UPF_Admin_Menu.php';
require URBIT_PRODUCT_FEED_ADMIN_CLASS_DIR . '/UPF_Admin_Settings_Section.php';
require URBIT_PRODUCT_FEED_ADMIN_CLASS_DIR . '/UPF_Admin_Settings_Field.php';

//admin pages
require URBIT_PRODUCT_FEED_ADMIN_CLASS_DIR . '/pages/UPF_Admin_Main_Page.php';
require URBIT_PRODUCT_FEED_ADMIN_CLASS_DIR . '/pages/UPF_Admin_Config_Page.php';