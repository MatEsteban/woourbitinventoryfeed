<?php

if (!defined( 'URBIT_PRODUCT_FEED_PLUGIN_DIR' )) {
    exit;
}

class UPF_Cache
{
    /**
     * Cache directory
     */
    const CACHE_DIR = URBIT_PRODUCT_FEED_PLUGIN_DIR . '/cache';

    /**
     * Cache file name suffix
     */
    const CACHE_NAME_SUFFIX = 'product-feed';

    /**
     * @var UPF_Core
     */
    protected $core;

    /**
     * UPF_Cache constructor.
     * @param UPF_Core $core
     */
    public function __construct(UPF_Core $core)
    {
        $this->core = $core;
    }

    /**
     * Get feed from cache file
     *
     * @param $filePath
     * @return bool|string
     */
    public function getFeedFromCache($filePath)
    {
        if ($filePath) {
            return file_get_contents($filePath);
        }

        return false;
    }

    /**
     * Save feed to file
     *
     * @param $feed
     */
    public function saveFeedToCache($feed)
    {
        //check and create dir
        if (!is_dir(self::CACHE_DIR)) {
            mkdir(self::CACHE_DIR);
        }

        $dateTimePrefix = date('Y-m-d-H-i-s') . '_';
        $cacheFile = self::CACHE_DIR . '/' . $dateTimePrefix . self::CACHE_NAME_SUFFIX;

        file_put_contents($cacheFile, $feed);
    }

    /**
     * Check feed cache expited
     *
     * @param $cacheFile
     * @return bool
     */
    public function checkFeedCacheExpired($cacheFile)
    {
    	return true;
        if (!$cacheFile) {
            return true;
        }

        //get cache duration value from config
        $duration = $this->core->getConfig()->get(
            "cron/cache_duration",
            UPF_Feed::SCHEDULE_INTERVAL_HOURLY_TIME
        );

        if (empty($duration)) {
            return true;
        }

        /** @var DateTime $cacheTimeStamp */
        $cacheTimeStamp = $this->getCacheFileDate($cacheFile)->getTimestamp();

        /** @var DateTime $nowTimeStamp */
        $nowTimeStamp = date_create()->getTimestamp();

        $timeDiff = $nowTimeStamp - $cacheTimeStamp;
        $durationInSeconds = $duration * 60 * 60;

        if ($timeDiff >= $durationInSeconds) {
            return true;
        }

        return false;
    }

    /**
     * Get last cache file
     *
     * @return bool|string
     */
    public function getLastCacheFile()
    {
        //check and create dir
        if (!is_dir(self::CACHE_DIR)) {
            mkdir(self::CACHE_DIR);
        }

        $files = scandir(self::CACHE_DIR);

        foreach ($files as $file) {
            $filePath = self::CACHE_DIR . '/' . $file;
            $match = preg_match('/[0-9]{4}(-[0-9]{2}){5}_product-feed/', $file);

            if ($match === 1 && is_file($filePath)) {
                return $filePath;
            }
        }

        return false;
    }

    /**
     * Get datetime cache file
     *
     * @param $cacheFile
     * @return bool|DateTime
     */
    public function getCacheFileDate($cacheFile)
    {
        $matchResult = preg_match('/([0-9]{4}(-[0-9]{2}){5})_product-feed/', $cacheFile, $matches);

        if ($matchResult === 1) {
            $dateTime = date_create_from_format('Y-m-d-H-i-s', $matches[1]);
            return $dateTime;
        }

        return false;
    }

    /**
     * Delete all cache files
     */
    public function flushAllCacheFiles()
    {
        $files = scandir(self::CACHE_DIR);

        foreach ($files as $file) {
            $filePath = self::CACHE_DIR . '/' . $file;

            if (is_file($filePath)){
                unlink($filePath);
            }
        }
    }
}