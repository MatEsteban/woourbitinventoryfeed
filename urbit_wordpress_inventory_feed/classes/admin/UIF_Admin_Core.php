<?php

if (!defined( 'URBIT_INVENTORY_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UIF_Admin_Core
 */
class UIF_Admin_Core
{
    /**
     * @var UIF_Admin_Pages
     */
    protected $pages;

    /**
     * @var UIF_Core
     */
    protected $core;

    /**
     * UIF_Admin_Core constructor.
     * @param UIF_Core $core
     */
    public function __construct(UIF_Core $core)
    {
        $this->core = $core;

        $this->init();
    }

    /**
     * Initializate admin functional
     */
    public function init()
    {
        /*
         * Setup pages
         */
        $mainPage   = new UIF_Admin_Main_Page($this->core);
        $configPage = new UIF_Admin_Config_Page($this->core);

        //set child pages
        $mainPage->addChildPage($configPage);

        $this->pages = new UIF_Admin_Pages(array($mainPage));
    }

    /**
     * @return UIF_Admin_Pages
     */
    public function getPagesObject()
    {
        return $this->pages;
    }
}