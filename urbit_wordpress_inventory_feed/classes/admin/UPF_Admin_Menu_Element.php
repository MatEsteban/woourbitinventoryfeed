<?php

if (!defined( 'URBIT_PRODUCT_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UPF_Admin_Menu_Element
 */
class UPF_Admin_Menu_Element
{
    /**
     * Menu element types
     */
    const TYPE_MAIN = 0x00;
    const TYPE_CHILD = 0x01;

    /**
     * @var string
     */
    public $pageTitle;

    /**
     * @var string
     */
    public $menuTitle;

    /**
     * @var string
     */
    public $capability;

    /**
     * @var string
     */
    public $menuSlug;

    /**
     * @var string
     */
    public $iconUrl;

    /**
     * @var int
     */
    public $position;

    /**
     * @var UPF_Admin_Menu_Element
     */
    protected $parentMenuElement;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var array
     */
    protected $function;

    /**
     * UPF_Admin_Menu_Element constructor.
     *
     * @param string $pageTitle
     * @param string $menuTitle
     * @param string $capability
     * @param string $menuSlug
     * @param string string $iconUrl
     * @param int $position
     */
    public function __construct($pageTitle, $menuTitle, $capability, $menuSlug, $iconUrl = '', $position = 9000)
    {
        $this->pageTitle  = $pageTitle;
        $this->menuTitle  = $menuTitle;
        $this->capability = $capability;
        $this->menuSlug   = $menuSlug;
        $this->iconUrl    = $iconUrl;
        $this->position   = $position;

        $this->type = static::TYPE_MAIN;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param UPF_Admin_Menu_Element $parentMenuElement
     */
    public function setParentMenuElement(UPF_Admin_Menu_Element $parentMenuElement)
    {
        $this->parentMenuElement = $parentMenuElement;
        $this->type = static::TYPE_CHILD;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return UPF_Admin_Menu_Element
     */
    public function getParentMenuElement()
    {
        return $this->parentMenuElement;
    }

    /**
     * @param array $function
     */
    public function setFunction($function)
    {
        $this->function = $function;
    }

    /**
     * @return array
     */
    public function getFunction()
    {
        return $this->function;
    }
}