<?php

/**
 * Class UIF_Product
 * Working and process with Woocommerce Product
 *
 * Magic properties
 * @property boolean $isSimple
 * @property boolean $isVariable
 * @property boolean $isVariation
 * @property boolean $isProcessable
 *
 * Field properties (for feed $data property):
 * @property string $id
 * @property array  $prices
 * @property array  $inventory
 */
class UIF_Product
{
    /**
     * @var UIF_Core
     */
    protected $core;

    /**
     * @var WC_Product
     */
    protected $product;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * UIF_Product constructor.
     * @param UIF_Core $core
     * @param WC_Product|null $product
     */
    public function __construct(UIF_Core $core, WC_Product $product = null)
    {
        $this->core    = $core;
        $this->product = $product;
    }

    /**
     * @return bool
     */
    public function isVariable()
    {
        return $this->product->get_type() === 'variable';
    }

    /**
     * @return bool
     */
    public function isSimple()
    {
        return $this->product->get_type() === 'simple';
    }

    /**
     * @return bool
     */
    public function isVariation()
    {
        return $this->product->get_type() === 'variation';
    }

    /**
     * Check that product is available to process
     * @return bool
     */
    public function isProcessable()
    {
        return $this->isSimple || $this->isVariation;
    }

    /**
     * Return array of variations
     * @return UIF_Product[]
     */
    public function getVariables()
    {
        if (!$this->isVariable) {
            return array();
        }

        /** @var WC_Product_Variable $product */
        $product = $this->product;

        $childs = array();

        foreach ($product->get_visible_children() as $childID) {
            $childs[] = new UIF_Product($this->core, new WC_Product_Variation($childID));
        }

        return $childs;
    }

    /**
     * Process product data
     * @return bool
     */
    public function process()
    {
        $product = $this->product;

        if (!$product->get_id() || !$this->isProcessable) {
            return false;
        }

        $sku = $product->get_sku();

        if ($this->isVariation && !$sku) {
            $parentData = $product->get_parent_data();
            $sku = $parentData['sku'] ? $parentData['sku'] . '-' . $product->get_id() : false;
        }

        $this->id = (string) ($sku ? $sku : "product_" . $product->get_id());

        $this->processPrices();

        $hasStock = $this->processStock();

        return $hasStock;
    }

    /**
     * Process product prices
     */
    protected function processPrices()
    {
        $currency = get_option('woocommerce_currency');
        $tax = $this->getTax();
        $regular = $this->product->get_regular_price();
        $value = $regular + $regular * $tax;

        $prices = [
            [
                'currency' => $currency,
                'value'    => (int)($value * 100),
                'type'     => 'regular',
                'vat'      => $tax * 10000
            ]
        ];

        if ($sale = $this->product->get_sale_price()) {
            $sale += $sale * $tax;
            $prices[] = [
                'currency' => $currency,
                'value'    => (int)($sale * 100),
                'type'     => 'sale',
                'vat'      => $tax * 10000
            ];
        }

        $this->prices = $prices;
    }

    /**
     * Tax handler
     */
    private function getTax()
    {
        $code = $this->core->getConfig()->getSelect("filter/countries", []);
        $country = WC_Tax::find_rates(array('country' => $code[0]));
        if(count($country) == 0) {
            if(count(WC_Tax::get_base_tax_rates()) == 0) return 0;
            $country = WC_Tax::get_base_tax_rates();
        }
        $unformatted_tax = array_pop($country)['rate'];
        $tax = number_format($unformatted_tax, 2, '.', '') / 100;

        return $tax;
    }

    public function processStock()
    {
        $qty = $this->product->get_stock_quantity();

        $this->inventory = array(
            array(
                'location' => get_current_blog_id(),
                'quantity' => $qty,
            )
        );

        return true;
    }

    /**
     * Get product data for feed
     * @return array
     */
    public function toArray()
    {
        if (empty($this->data)) {
            $this->process();
        }

        return $this->data;
    }

    /**
     * Get feed product data fields
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        if (stripos($name, 'is') === 0 && method_exists($this, $name)) {
            return $this->{$name}();
        }

        $getMethod = "get{$name}";

        if (method_exists($this, $getMethod)) {
            return $this->{$getMethod}();
        }

        return null;
    }

    /**
     * Set feed product data fields
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $setMethod = "set{$name}";

        if (method_exists($this, $setMethod)) {
            $this->{$setMethod}($value);
            return;
        }

        $this->data[$name] = $value;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return $this|mixed|null
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        $property      = strtolower(preg_replace("/^(unset|get|set)/", '', $name));
        $propertyExist = isset($this->data[$property]);

        if ($propertyExist) {
            if (stripos($name, 'unset') === 0) {
                unset($this->data[$property]);
                return $this;
            }

            if (stripos($name, 'get') === 0) {
                return $this->{$property};
            }

            if (stripos($name, 'set') === 0 && isset($arguments[0])) {
                $this->{$property} = $arguments[0];
                return $this;
            }
        }

        throw new Exception("Unknown method {$name}");
    }
}