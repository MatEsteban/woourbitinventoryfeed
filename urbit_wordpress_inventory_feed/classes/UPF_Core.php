<?php

if (!defined( 'URBIT_PRODUCT_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UrbitProductFeedCore
 */
class UPF_Core
{
    /**
     * Name of the page that is created
     */
    const PAGE_NAME = 'Urbit Product Feed';

    /**
     * Config key
     */
    const CONFIG_KEY = 'urbit_productfeed_config';

    /**
     * @var UPF_Template
     */
    protected $template;

    /**
     * @var UPF_Cache
     */
    protected $cache;

    /**
     * @var UPF_Feed
     */
    protected $feed;

    /**
     * @var UPF_Query
     */
    protected $query;

    /**
     * @var UPF_Product
     */
    protected $product;

    /**
     * @var UPF_Config
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
        $this->template = new UPF_Template($this);
        $this->cache    = new UPF_Cache($this);
        $this->feed     = new UPF_Feed($this);
        $this->query    = new UPF_Query($this);
        $this->product  = new UPF_Product($this);
        $this->config   = new UPF_Config($this);

        $this->wcProductFactory = new WC_Product_Factory();
    }

    /**
     * @return UPF_Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return UPF_Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @return UPF_Feed
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * @return UPF_Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param null|int|WC_Product $product
     * @return UPF_Product
     */
    public function getProduct($product = null)
    {
        if ($product) {
            return new UPF_Product(
                $this,
                $product instanceof WC_Product ? $product : $this->wcProductFactory->get_product((int) $product)
            );
        }

        return $this->product;
    }

    /**
     * @return UPF_Config
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
            'cache' => UPF_Feed::SCHEDULE_INTERVAL_HOURLY_TIME
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
            apply_filters('active_plugins', get_option('active_plugins'))
        );
    }
}

