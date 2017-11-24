<?php

if (!defined( 'URBIT_PRODUCT_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UPF_Admin_Setting_Section
 */
class UPF_Admin_Settings_Section
{
    /**
     * Page suffix
     */
    const PAGE_SUFFIX = '_suffix';

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $pageId;

    /**
     * @var UPF_Admin_Settings_Field[]
     */
    protected $fields;

    /**
     * UPF_Admin_Settings_Section constructor.
     * @param string $id
     * @param string $name
     * @param mixed $pageId
     */
    public function __construct($id, $name, $pageId = null)
    {
        $this->id = $id;
        $this->name = $name;

        if($pageId === null){
            $pageId = $id . static::PAGE_SUFFIX;
        }

        $this->pageId = $pageId;
    }

    /**
     * @param UPF_Admin_Settings_Field $field
     */
    public function addField(UPF_Admin_Settings_Field $field)
    {
        $this->fields[] = $field;
    }

    /**
     * Register section
     */
    public function registerSection()
    {
        add_settings_section($this->id, $this->name, '', $this->pageId);

        //register fields
        foreach ($this->fields as $field){
            $field->registerField($this);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPageId()
    {
        return $this->pageId;
    }
}