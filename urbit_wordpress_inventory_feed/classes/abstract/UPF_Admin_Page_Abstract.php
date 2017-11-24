<?php

if (!defined( 'URBIT_PRODUCT_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UPF_Admin_Page_Abstract
 */
abstract class UPF_Admin_Page_Abstract extends UPF_Template_Abstract
{
    /**
     * Page slug
     */
    const SLUG = '';

    /**
     * @var UPF_Admin_Menu_Element
     */
    protected $menuElement;

    /**
     * @var array
     */
    protected $childPages;

    /**
     * UPF_Admin_Page_Abstract constructor.
     * @param UPF_Core $core
     */
    public function __construct(UPF_Core $core)
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
     * @return UPF_Admin_Menu_Element
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
        $this->menuElement = new UPF_Admin_Menu_Element(
            '',
            '',
            '',
            static::SLUG,
            '',
            ''
        );
    }

    /**
     * @param UPF_Admin_Page_Abstract $page
     */
    public function addChildPage(UPF_Admin_Page_Abstract $page)
    {
        $this->childPages[] = $page;
    }

    /**
     * @return UPF_Admin_Page_Abstract[]
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