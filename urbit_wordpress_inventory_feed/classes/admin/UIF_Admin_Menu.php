<?php

if (!defined( 'URBIT_INVENTORY_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UIF_Admin_Menu
 */
class UIF_Admin_Menu
{
    /**
     * @var array
     */
    protected $menuElements;

    /**
     * UIF_Admin_Menu constructor.
     *
     * @param array $menuElements
     */
    public function __construct($menuElements)
    {
        $this->menuElements = $menuElements;
        $this->init();
    }

    /**
     * Init menu hooks
     */
    protected function init()
    {
        add_action('admin_menu', array($this, 'registerMenuElements'));
    }

    /**
     * Register menu elements in wp
     */
    public function registerMenuElements()
    {
        global $admin_page_hooks;

        /** @var UIF_Admin_Menu_Element $menuElement */
        foreach ($this->getMenuElements() as $menuElement){
            switch ($menuElement->getType()){
                case UIF_Admin_Menu_Element::TYPE_MAIN:
                    if (!empty($admin_page_hooks[$menuElement->menuSlug])) {
                        continue 2;
                    }

                    add_menu_page(
                        $menuElement->pageTitle,
                        $menuElement->menuTitle,
                        $menuElement->capability,
                        $menuElement->menuSlug,
                        $menuElement->getFunction(),
                        $menuElement->iconUrl,
                        $menuElement->position
                    );

                    break;

                case UIF_Admin_Menu_Element::TYPE_CHILD:
                    add_submenu_page(
                        $menuElement->getParentMenuElement()->menuSlug,
                        $menuElement->pageTitle,
                        $menuElement->menuTitle,
                        $menuElement->capability,
                        $menuElement->menuSlug,
                        $menuElement->getFunction()
                    );

                    break;
            }
        }
    }

    /**
     * Add menu element
     *
     * @param UIF_Admin_Menu_Element $element
     */
    public function addMenuElement(UIF_Admin_Menu_Element $element)
    {
        array_push($this->menuElements, $element);
    }

    /**
     * Get menu elements from file
     *
     * @return bool|array
     */
    public function getMenuElements()
    {
        return $this->menuElements;
    }
}