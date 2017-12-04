<?php

if (!defined( 'URBIT_INVENTORY_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UrbitProductFeedTemplate
 */
class UIF_Template extends UIF_Template_Abstract
{
    /**
     * Our page name
     */
    const POST_NAME = 'urbit-inventory-feed';

    /**
     * Setup template
     */
    const BASE_TEMPLATE = 'empty';

    /**
     * @var UIF_Core
     */
    protected $core;

    /**
     * UIF_Template constructor.
     *
     * @param UIF_Core $core
     */
    public function __construct(UIF_Core $core)
    {
        parent::__construct($core);

        $this->init();
    }

    /**
     * Initialize plugin templates
     */
    protected function init()
    {
        if ($this->core->checkWoocommerce()){
            add_filter('template_include', array($this, 'filter_template_include'), 10, 1);
        }
    }

    /**
     * Check if page is feed and rewrite template
     *
     * @param string $template
     * @return string
     */
    public function filter_template_include($template)
    {
        if (is_page(self::POST_NAME)) {
            $this->core->getFeed()->generate(
                $this->core->getConfig()->get('filter', [])
            );

            die();
        }

        return $template;
    }
}