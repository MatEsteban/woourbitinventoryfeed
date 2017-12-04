<?php
/**
 * Load main classes
 */

if (!defined( 'URBIT_INVENTORY_FEED_PLUGIN_DIR' )) {
    exit;
}

/*
 * Abstract classes
 */
require URBIT_INVENTORY_FEED_CLASS_DIR . '/abstract/UIF_Template_Abstract.php';
require URBIT_INVENTORY_FEED_CLASS_DIR . '/abstract/UIF_Admin_Page_Abstract.php';

require URBIT_INVENTORY_FEED_CLASS_DIR . "/UIF_Config.php";
require URBIT_INVENTORY_FEED_CLASS_DIR . "/UIF_Core.php";
require URBIT_INVENTORY_FEED_CLASS_DIR . "/UIF_Query.php";
require URBIT_INVENTORY_FEED_CLASS_DIR . "/UIF_Template.php";
require URBIT_INVENTORY_FEED_CLASS_DIR . "/UIF_Product.php";
require URBIT_INVENTORY_FEED_CLASS_DIR . "/UIF_Feed.php";
require URBIT_INVENTORY_FEED_CLASS_DIR . "/UIF_Cache.php";

/*
 * Init admin classes
 */
require URBIT_INVENTORY_FEED_ADMIN_CLASS_DIR . '/UIF_Admin_Core.php';
require URBIT_INVENTORY_FEED_ADMIN_CLASS_DIR . '/UIF_Admin_Pages.php';
require URBIT_INVENTORY_FEED_ADMIN_CLASS_DIR . '/UIF_Admin_Menu_Element.php';
require URBIT_INVENTORY_FEED_ADMIN_CLASS_DIR . '/UIF_Admin_Menu.php';
require URBIT_INVENTORY_FEED_ADMIN_CLASS_DIR . '/UIF_Admin_Settings_Section.php';
require URBIT_INVENTORY_FEED_ADMIN_CLASS_DIR . '/UIF_Admin_Settings_Field.php';

//admin pages
require URBIT_INVENTORY_FEED_ADMIN_CLASS_DIR . '/pages/UIF_Admin_Main_Page.php';
require URBIT_INVENTORY_FEED_ADMIN_CLASS_DIR . '/pages/UIF_Admin_Config_Page.php';