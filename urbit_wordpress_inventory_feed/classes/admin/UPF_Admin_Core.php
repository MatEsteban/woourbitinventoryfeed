<?php

if (!defined( 'URBIT_PRODUCT_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UPF_Admin_Core
 */
class UPF_Admin_Core
{
    /**
     * @var UPF_Admin_Pages
     */
    protected $pages;

    /**
     * @var UPF_Core
     */
    protected $core;

    /**
     * UPF_Admin_Core constructor.
     * @param UPF_Core $core
     */
    public function __construct(UPF_Core $core)
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
        $mainPage   = new UPF_Admin_Main_Page($this->core);
        $configPage = new UPF_Admin_Config_Page($this->core);

        //set child pages
        $mainPage->addChildPage($configPage);

        $this->pages = new UPF_Admin_Pages(array($mainPage));
    }

    /**
     * @return UPF_Admin_Pages
     */
    public function getPagesObject()
    {
        return $this->pages;
    }
}