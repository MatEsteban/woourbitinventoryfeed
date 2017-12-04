<?php

if (!defined( 'URBIT_INVENTORY_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UIF_Feed
 */
class UIF_Feed
{
    const SCHEDULE_INTERVAL_5MIN   = '5MIN';
    const SCHEDULE_INTERVAL_15MIN  = '15MIN';
    const SCHEDULE_INTERVAL_30MIN  = '30MIN';
    const SCHEDULE_INTERVAL_45MIN  = '45MIN';
    const SCHEDULE_INTERVAL_HOURLY = 'HOURLY';

    const SCHEDULE_INTERVAL_5MIN_TIME   = 5;
    const SCHEDULE_INTERVAL_15MIN_TIME  = 15;
    const SCHEDULE_INTERVAL_30MIN_TIME  = 30;
    const SCHEDULE_INTERVAL_45MIN_TIME  = 45;
    const SCHEDULE_INTERVAL_HOURLY_TIME = 60;

    const FEED_VERSION = '2017-06-28-1';

    /**
     * @var UIF_Core
     */
    protected $core;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * UIF_Feed constructor.
     * @param UIF_Core $core
     */
    public function __construct(UIF_Core $core)
    {
        $this->core = $core;
    }

    /**
     * @param array $filter
     */
    public function generate($filter = array())
    {
        /** @var UIF_Cache $cache */
        $cache = $this->core->getCache();

        $feedResult = '';
        $cacheFile  = $cache->getLastCacheFile();

        if ($cache->checkFeedCacheExpired($cacheFile)) {
            $feedResult = $this->getFeedJson($filter);
            $cache->saveFeedToCache($feedResult);
        }

        if (!$feedResult) {
            $feedResult = $cache->getFeedFromCache($cacheFile);
        }

        //change header content type
        header('Content-Type: application/json');

        //print feed data
        echo $feedResult;
    }

    /**
     * Process feed generation
     * @param array $filter
     */
    protected function process($filter = array())
    {
        $this->core->getCache()->flushAllCacheFiles();

        $selectedProducts = $this->core->getConfig()->getSelect("filter/product", []);

        foreach ($selectedProducts as $productId) {
            $this->processProduct(
                $this->core->getProduct($productId)
            );
        }
    }

    protected function processProduct(UIF_Product $product)
    {
        if ($product->isVariable()) {
            foreach ($product->getVariables() as $product) {
                $this->processProduct($product);
            }

            return;
        }

        if ($product->process()) {
            $this->data[] = $product->toArray();
        }
    }

    /**
     * @param array $filter
     * @return array
     */
    public function getFeedData($filter = array())
    {
        $lang = get_locale();

        $version = $this->getFeedVersion();

        return array(
            '$schema' => "https://raw.githubusercontent.com/urbitassociates/urbit-merchant-feeds/master/schemas/inventory/{$version}/inventory.json",
            'content_language' => $lang,
            'attribute_language' => $lang,
            'content_type' => 'products',
            'target_country' => array(
	            $this->country_handle(),
            ),
            'version' => $version,
            'feed_format' => array(
                "encoding" => "UTF-8",
            ),
            'schedule' => array(
                'interval' => $this->getIntervalText(),
            ),
            'entities' => $this->getData($filter),
        );
    }

    /**
     * @return string
     */
    private function country_handle()
    {
        $countries = new WC_Countries();
        $code = $this->core->getConfig()->getSelect("filter/countries", []);
        $country = WC_Tax::find_rates(array('country' => $code[0]));
        if(count($country) == 0) {
            return $countries->get_base_country();
        }

        return $code[0];
    }

    /**
     * @param array $filter
     * @return string
     */
    public function getFeedJson($filter = array())
    {
        return json_encode($this->getFeedData($filter), JSON_PRETTY_PRINT);
    }

    /**
     * @param array $filter
     * @return array
     */
    public function getData($filter = array())
    {
        if (empty($this->data)) {
            $this->process($filter);
        }

        return $this->data;
    }

    /**
     * Get schedule interval value
     * @return string
     */
    public function getIntervalText()
    {
        $cacheDuration = $this->core->getConfig()->get(
            "cron/cache_duration",
            self::SCHEDULE_INTERVAL_5MIN
        );

        foreach (array(
            self::SCHEDULE_INTERVAL_5MIN_TIME   => self::SCHEDULE_INTERVAL_5MIN,
            self::SCHEDULE_INTERVAL_15MIN_TIME  => self::SCHEDULE_INTERVAL_15MIN,
            self::SCHEDULE_INTERVAL_30MIN_TIME  => self::SCHEDULE_INTERVAL_30MIN,
            self::SCHEDULE_INTERVAL_45MIN_TIME  => self::SCHEDULE_INTERVAL_45MIN,
            self::SCHEDULE_INTERVAL_HOURLY_TIME => self::SCHEDULE_INTERVAL_HOURLY,
        ) as $time => $val) {
            if ($cacheDuration <= $time) {
                return $val;
            }
        }

        return static::SCHEDULE_INTERVAL_HOURLY;
    }

    public function getFeedVersion()
    {
        return static::FEED_VERSION;
    }
}