<?php

if (!defined('URBIT_PRODUCT_FEED_PLUGIN_DIR')) {
    exit;
}

/**
 * Class UPF_Feed
 */
class UPF_Feed
{
    const SCHEDULE_INTERVAL_HOURLY = 'HOURLY';
    const SCHEDULE_INTERVAL_DAILY = 'DAILY';
    const SCHEDULE_INTERVAL_WEEKLY = 'WEEKLY';
    const SCHEDULE_INTERVAL_MONTHLY = 'MONTHLY';
    const SCHEDULE_INTERVAL_HOURLY_TIME = 1;
    const SCHEDULE_INTERVAL_DAILY_TIME = 24;
    const SCHEDULE_INTERVAL_WEEKLY_TIME = 168;
    const SCHEDULE_INTERVAL_MONTHLY_TIME = 5040;
    const FEED_VERSION = '2017-06-28-1';

    /**
     * @var UPF_Core
     */
    protected $core;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * UPF_Feed constructor.
     * @param UPF_Core $core
     */
    public function __construct(UPF_Core $core)
    {
        $this->core = $core;
    }

    /**
     * @param array $filter
     */
    public function generate($filter = [])
    {
        /** @var UPF_Cache $cache */
        $cache = $this->core->getCache();

        $feedResult = '';
        $cacheFile = $cache->getLastCacheFile();

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
    protected function process($filter = [])
    {
        $this->core->getCache()->flushAllCacheFiles();

        $query = $this->core->getQuery()->productsQuery($filter);

        foreach ($query->posts as $productId) {
            $this->processProduct(
                $this->core->getProduct($productId)
            );
        }
    }

    protected function processProduct(UPF_Product $product)
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
    public function getFeedData($filter = [])
    {
        $lang = get_locale();

        $version = $this->getFeedVersion();

        return [
            '$schema'            => "https://raw.githubusercontent.com/urbitassociates/urbit-merchant-feeds/master/schemas/inventory/{$version}/product.json",
            'content_language'   => $lang,
            'attribute_language' => $lang,
            'content_type'       => 'products',
            'target_country'     => [
                $this->country_handle(),
            ],
            'version'            => $version,
            'feed_format'        => [
                "encoding" => "UTF-8",
            ],
            'schedule'           => [
                'interval' => $this->getIntervalText(),
            ],
            'entities'           => $this->getData($filter),
        ];
    }

    /**
     * @return string
     */
    private function country_handle()
    {
        $countries = new WC_Countries();
        $code = $this->core->getConfig()->getSelect("filter/countries", []);
        $country = WC_Tax::find_rates(['country' => $code[0]]);

        if (count($country) == 0) {
            return $countries->get_base_country();
        }

        return $code[0];
    }

    /**
     * @param array $filter
     * @return string
     */
    public function getFeedJson($filter = [])
    {
        return json_encode($this->getFeedData($filter), JSON_PRETTY_PRINT);
    }

    /**
     * @param array $filter
     * @return array
     */
    public function getData($filter = [])
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
            self::SCHEDULE_INTERVAL_HOURLY_TIME
        );

        foreach ([
            static::SCHEDULE_INTERVAL_HOURLY_TIME  => static::SCHEDULE_INTERVAL_HOURLY,
            static::SCHEDULE_INTERVAL_DAILY_TIME   => static::SCHEDULE_INTERVAL_DAILY,
            static::SCHEDULE_INTERVAL_WEEKLY_TIME  => static::SCHEDULE_INTERVAL_WEEKLY,
            static::SCHEDULE_INTERVAL_MONTHLY_TIME => static::SCHEDULE_INTERVAL_MONTHLY,
        ] as $time => $val) {
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
