<?php

if (!defined( 'URBIT_PRODUCT_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UPF_Admin_Field
 */
class UPF_Admin_Settings_Field extends UPF_Template_Abstract
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
     * UPF_Admin_Settings_Field constructor.
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
     * @param UPF_Admin_Settings_Section $section
     */
    public function registerField(UPF_Admin_Settings_Section $section)
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