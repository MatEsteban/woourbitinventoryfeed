<?php

if (!defined( 'URBIT_INVENTORY_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UrbitProductFeedCore
 */
class UIF_Core
{
    /**
     * Name of the page that is created
     */
    const PAGE_NAME = 'Urbit Inventory Feed';

    /**
     * Config key
     */
    const CONFIG_KEY = 'urbit_inventoryfeed_config';

    /**
     * @var UIF_Template
     */
    protected $template;

    /**
     * @var UIF_Cache
     */
    protected $cache;

    /**
     * @var UIF_Feed
     */
    protected $feed;

    /**
     * @var UIF_Query
     */
    protected $query;

    /**
     * @var UIF_Product
     */
    protected $product;

    /**
     * @var UIF_Config
     */
    protected $config;

    /**
     * @var WC_Product_Factory
     */
    protected $wcProductFactory;

    /**
     * UrbitProductFeedCore constructor.
     * @param string $pluginFile
     */
    public function __construct($pluginFile)
    {
        register_activation_hook($pluginFile, array($this, '_install'));
        register_deactivation_hook($pluginFile, array($this, '_uninstall'));

        add_action('wp_loaded', array($this, 'init'));
    }

    /**
     * Plugin initialization
     */
    public function init()
    {
        $this->template = new UIF_Template($this);
        $this->cache    = new UIF_Cache($this);
        $this->feed     = new UIF_Feed($this);
        $this->query    = new UIF_Query($this);
        $this->product  = new UIF_Product($this);
        $this->config   = new UIF_Config($this);

        $this->wcProductFactory = new WC_Product_Factory();
    }

    /**
     * @return UIF_Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return UIF_Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @return UIF_Feed
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * @return UIF_Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param null|int|WC_Product $product
     * @return UIF_Product
     */
    public function getProduct($product = null)
    {
        if ($product) {
            return new UIF_Product(
                $this,
                $product instanceof WC_Product ? $product : $this->wcProductFactory->get_product((int) $product)
            );
        }

        return $this->product;
    }

    /**
     * @return UIF_Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Plugin installation hook
     */
    public function _install()
    {
        if (!self::checkWoocommerce()) {
            wp_die('Woocommerce not active! Please enable it first.');
        }

        $newPageTitle = self::PAGE_NAME;
        $pageCheck    = get_page_by_title($newPageTitle);

        $newPage = array(
            'post_type'    => 'page',
            'post_title'   => $newPageTitle,
            'post_content' => '',
            'post_status'  => 'publish',
            'post_author'  => 1,
        );

        if (!isset($pageCheck->ID)) {
            wp_insert_post($newPage);
        }

        //setup default cache duration
        update_option(self::CONFIG_KEY, array(
            'cache' => UIF_Feed::SCHEDULE_INTERVAL_5MIN_TIME
        ));
    }

    /**
     * Plugin uninstallation hook
     */
    public function _uninstall()
    {
        $pageCheck = get_page_by_title(self::PAGE_NAME);

        if (!empty($pageCheck->ID)) {
            wp_delete_post($pageCheck->ID, true);
        }
    }

    /**
     * @return bool
     */
    public function checkWoocommerce()
    {
        return in_array(
            'woocommerce/woocommerce.php',
            apply_filters('active_plugins', get_option( 'active_plugins'))
        );
    }
}

