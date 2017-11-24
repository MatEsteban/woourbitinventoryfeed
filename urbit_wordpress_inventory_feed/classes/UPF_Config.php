<?php

if (!defined( 'URBIT_PRODUCT_FEED_PLUGIN_DIR' )) {
    exit;
}

/**
 * Class UPF_Config
 * @property array cron
 * @property array filter
 * @property array attribute
 */
class UPF_Config
{
    /**
     * Config key
     */
    const CONFIG_KEY = 'urbit_productfeed_config';

    /**
     * @var array
     */
    protected static $config;

    /**
     * UPF_Config constructor.
     */
    public function __construct()
    {
        $this->load();
    }

    /**
     * Load plugin configuration data
     */
    public function load()
    {
        if (empty(self::$config)) {
            self::$config = get_option(self::CONFIG_KEY);
        }
    }

    /**
     * @param string|null $name
     * @param mixed $default
     * @return mixed
     * @throws Exception
     */
    public function get($name = null, $default = null)
    {
        if ($name === null) {
            return self::$config;
        }

        $param = false;

        if (stripos($name, '/')) {
            list($name, $param) = explode('/', $name);
        }

        if (!isset(self::$config[$name])) {
            if ($default !== null) {
                return $default;
            }

            throw new Exception("Try to get unknown configuration field - `{$name}`");
        }

        if ($param !== false) {
            if (!isset(self::$config[$name][$param])) {
                if ($default !== null) {
                    return $default;
                }

                throw new Exception("Try to get unknown parameter of configuration field - `{$name}/{$param}`");
            }

            return self::$config[$name][$param];
        }

        return self::$config[$name];
    }

    /**
     * Get parameter of multiselect config field
     * @param string $name
     * @param mixed $default
     * @return array
     */
    public function getSelect($name, $default = null)
    {
        $val = $this->get($name, $default);

        if (is_array($val)) {
            return $val;
        }

        return explode(",", $val);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param bool $create
     * @return mixed
     * @throws Exception
     */
    public function set($name, $value, $create = false)
    {
        $param = false;

        if (stripos($name, '/')) {
            list($name, $param) = explode('/', $name);
        }

        if (!isset(self::$config[$name]) && !$create) {
            throw new Exception("Try to set unknown configuration field - `{$name}`");
        }

        if ($param !== false) {
            if (!isset(self::$config[$name]) && $create) {
                self::$config[$name] = [];
            }

            if (!isset(self::$config[$name][$param]) && !$create) {
                throw new Exception("Try to set unknown parameter of configuration field - `{$name}/{$param}`");
            }

            self::$config[$name][$param] = $value;
        } else {
            self::$config[$name] = $value;
        }

        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @throws Exception
     */
    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }
}