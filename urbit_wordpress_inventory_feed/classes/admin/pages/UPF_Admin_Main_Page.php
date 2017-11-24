<?php

if (!defined( 'URBIT_PRODUCT_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UPF_Admin_Main_Page
 */
class UPF_Admin_Main_Page extends UPF_Admin_Page_Abstract
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
        $this->menuElement = new UPF_Admin_Menu_Element(
            'Urbit Settings Page',
            'Urbit Feed',
            null,
            static::SLUG,
            'dashicons-cart',
            81
        );
    }
}