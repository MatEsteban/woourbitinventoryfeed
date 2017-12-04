<?php

if (!defined( 'URBIT_INVENTORY_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UIF_Admin_Pages
 */
class UIF_Admin_Pages
{
    /**
     * @var array
     */
    protected $pages;

    /**
     * @var UIF_Admin_Menu
     */
    protected $menu;

    /**
     * @var array
     */
    protected $menuElements;

    /**
     * UIF_Admin_Pages constructor.
     * Init all pages
     *
     * @param UIF_Admin_Page_Abstract[] $pages
     */
    public function __construct($pages)
    {
        foreach ($pages as $page) {
            if ($page instanceof UIF_Admin_Page_Abstract && !empty($page->getChildPages())) {
                $menuElement = $page->getMenuElement();
                $this->pages[] = $page;
                $this->menuElements[] = $menuElement;

                foreach ($page->getChildPages() as $childPage) {
                    $childPage->getMenuElement()->setParentMenuElement($menuElement);
                    $this->menuElements[] = $childPage->getMenuElement();
                }
            }
        }

        $this->menu = new UIF_Admin_Menu($this->menuElements);
    }

    /**
     * @return array
     */
    public function getMenuElements()
    {
        return $this->menuElements;
    }

    /**
     * @return UIF_Admin_Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @return array
     */
    public function getPages()
    {
        return $this->pages;
    }
}