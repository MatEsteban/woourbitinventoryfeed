<?php

if (!defined( 'URBIT_INVENTORY_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UIF_Admin_Main_Page
 */
class UIF_Admin_Main_Page extends UIF_Admin_Page_Abstract
{
    /**
     * Page slug
     */
    const SLUG = 'urbit';

    /**
     * Setup template
     */
    const BASE_TEMPLATE = 'admin/main_page';

    /**
     * Init menu element
     */
    protected function init()
    {
        $this->menuElement = new UIF_Admin_Menu_Element(
            'Urbit Settings Page',
            'Urbit',
            null,
            static::SLUG,
            'dashicons-cart',
            81
        );
    }
}