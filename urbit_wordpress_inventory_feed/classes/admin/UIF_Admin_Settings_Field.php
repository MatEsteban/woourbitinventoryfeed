<?php

if (!defined( 'URBIT_INVENTORY_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UIF_Admin_Field
 */
class UIF_Admin_Settings_Field extends UIF_Template_Abstract
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $vars;

    /**
     * @var string
     */
    protected $pageId;

    /**
     * UIF_Admin_Settings_Field constructor.
     *
     * @param string $id
     * @param string $name
     * @param string $pageId
     * @param string $template
     * @param array $vars
     */
    public function __construct($id, $name, $pageId, $template, $vars = array())
    {
        $this->id = $id;
        $this->name = $name;
        $this->template = $template;
        $this->vars = $vars;
        $this->pageId = $pageId;
    }

    /**
     * @param UIF_Admin_Settings_Section $section
     */
    public function registerField(UIF_Admin_Settings_Section $section)
    {
        add_settings_field(
            $this->id,
            $this->name,
            array($this, 'printTemplate'),
            $this->pageId,
            $section->getId(),
            $this->vars
        );
    }
}