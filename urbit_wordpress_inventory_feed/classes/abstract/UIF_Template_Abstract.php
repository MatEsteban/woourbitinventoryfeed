<?php

if (!defined( 'URBIT_INVENTORY_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UIF_Template_Abstract
 */
abstract class UIF_Template_Abstract
{
    /**
     * Base template
     */
    const BASE_TEMPLATE = '';

    /**
     * Templates directory
     */
    const TEMPLATES_DIR = URBIT_INVENTORY_FEED_PLUGIN_DIR . '/templates';

    /**
     * @var string
     */
    protected $template;

    /**
     * @var UIF_Core
     */
    protected $core;

    /**
     * UIF_Template_Abstract constructor.
     * @param UIF_Core $core
     */
    public function __construct(UIF_Core $core)
    {
        $this->core = $core;
        $this->template = static::BASE_TEMPLATE;
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->template;
    }

    /**
     * Get rendered template
     *
     * @param string $template
     * @param array $vars
     * @return string
     */
    public function getTemplate($vars, $template = null)
    {
        ob_start();

        $this->printTemplate($vars, $template);

        return ob_get_clean();
    }

    /**
     * Print template by name
     *
     * @param string $template
     * @param array $vars
     */
    public function printTemplate($vars = array(), $template = null)
    {
        $templatePath = static::TEMPLATES_DIR . '/' . ($template ? $template : $this->template) . '.php';

        if (is_file($templatePath)) {
            if (!empty($vars)) {
                extract($vars);
            }

            require $templatePath;
        }
    }
}