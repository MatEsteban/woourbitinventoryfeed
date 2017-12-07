<?php

if (!defined( 'URBIT_INVENTORY_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UIF_Admin_Page_Abstract
 */
abstract class UIF_Admin_Page_Abstract extends UIF_Template_Abstract
{
    /**
     * Page slug
     */
    const SLUG = '';

    /**
     * @var UIF_Admin_Menu_Element
     */
    protected $menuElement;

    /**
     * @var array
     */
    protected $childPages;

    /**
     * UIF_Admin_Page_Abstract constructor.
     * @param UIF_Core $core
     */
    public function __construct(UIF_Core $core)
    {
        $this->init();
        $this->menuElement->setFunction(array($this, 'printTemplate'));

        parent::__construct($core);
    }

    public function printTemplate($vars = array(), $template = null)
    {
        parent::printTemplate($vars, $template);
    }

    /**
     * @return UIF_Admin_Menu_Element
     */
    public function getMenuElement()
    {
        return $this->menuElement;
    }

    /**
     * Init menu element
     */
    protected function init()
    {
        $this->menuElement = new UIF_Admin_Menu_Element(
            '',
            '',
            '',
            static::SLUG,
            '',
            ''
        );
    }

    /**
     * @param UIF_Admin_Page_Abstract $page
     */
    public function addChildPage(UIF_Admin_Page_Abstract $page)
    {
        $this->childPages[] = $page;
    }

    /**
     * @return UIF_Admin_Page_Abstract[]
     */
    public function getChildPages()
    {
        return $this->childPages;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return self::SLUG;
    }
}