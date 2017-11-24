<?php

/**
 * Class UPF_Product
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
 * @property string $name
 * @property string $description
 * @property string $link
 * @property array $prices
 * @property array $brands
 * @property array $attributes
 * @property string $gtin
 * @property array $categories
 * @property string $image_link
 * @property array $additional_image_links
 * @property string $item_group_id
 * @property array $dimensions
 *
 * @property string $sizeType
 * @property string $size
 * @property string $color
 * @property string $gender
 * @property string $material
 * @property string $pattern
 * @property string $age_group
 * @property string $condition
 */
class UPF_Product
{
    /**
     * @var UPF_Core
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
     * UPF_Product constructor.
     * @param UPF_Core $core
     * @param WC_Product|null $product
     */
    public function __construct(UPF_Core $core, WC_Product $product = null)
    {
        $this->core = $core;
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
     * @return UPF_Product[]
     */
    public function getVariables()
    {
        if (!$this->isVariable) {
            return [];
        }

        /** @var WC_Product_Variable $product */
        $product = $this->product;

        $childs = [];

        foreach ($product->get_visible_children() as $childID) {
            $childs[] = new UPF_Product($this->core, new WC_Product_Variation($childID));
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

        $this->id = (string)($sku ? $sku : "product_" . $product->get_id());
        $this->name = $product->get_title();
        $this->description = $product->get_description();
        $this->link = get_permalink($product->get_id());

        $this->processPrices();
        $this->processCategories();
        $this->processImages();
        $this->processAttributes();
        $this->processConfigurableFields();

        return true;
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
                'value'    => (int)($sale),
                'type'     => 'sale',
                'vat'      => $tax * 10000,
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
        $country = WC_Tax::find_rates(['country' => $code[0]]);
        if (count($country) == 0) {
            if (count(WC_Tax::get_base_tax_rates()) == 0) {
                return 0;
            }
            $country = WC_Tax::get_base_tax_rates();
        }
        $unformatted_tax = array_pop($country)['rate'];
        $tax = number_format($unformatted_tax, 2, '.', '') / 100;

        return $tax;
    }

    /**
     * Process product categories
     */
    protected function processCategories()
    {
        $categories = [];
        $categoryIds = $this->product->get_category_ids();

        foreach ($categoryIds as $categoryId) {
            $this->processCategoryWithParent($categoryId, $categories);
        }

        if (!empty($categories)) {
            sort($categories);
            $this->categories = $categories;
        }
    }

    /**
     * Helper function
     * Fetch category with parents
     * @param int $categoryID
     * @param array &$categories
     * @return bool
     */
    private function processCategoryWithParent($categoryID, &$categories)
    {
        $term = get_term_by('id', $categoryID, 'product_cat');

        if (!$term) {
            return false;
        }

        if (isset($categories[$term->term_id])) {
            return true;
        }

        $category = [
            'id'   => $term->term_id,
            'name' => $term->name,
        ];

        if ($term->parent && $this->processCategoryWithParent($term->parent, $categories)) {
            $category['parent_id'] = $term->parent;
        }

        $categories[$term->term_id] = $category;

        return true;
    }

    /**
     * Process product Dimensions
     */
    protected function processDimensions()
    {
        $dimensionUnit = get_option('woocommerce_dimension_unit');
        $weightUnit = get_option('woocommerce_weight_unit');

        $product = $this->product;

        $dimensions = [];

        if ($height = $product->get_height()) {
            $dimensions['height'] = [
                'value' => $height,
                'unit'  => $dimensionUnit,
            ];
        }

        if ($length = $product->get_length()) {
            $dimensions['length'] = [
                'value' => $length,
                'unit'  => $dimensionUnit,
            ];
        }

        if ($width = $product->get_width()) {
            $dimensions['width'] = [
                'value' => $width,
                'unit'  => $dimensionUnit,
            ];
        }

        if ($weight = $product->get_weight()) {
            $dimensions['weight'] = [
                'value' => $weight,
                'unit'  => $weightUnit,
            ];
        }

        if (!empty($dimensions)) {
            $this->dimensions = $dimensions;
        }
    }

    /**
     * Process product images
     */
    protected function processImages()
    {
        $product = $this->product;

        // Process main image
        $mainImage = wp_get_attachment_image_src($product->get_image_id(), 'full');

        if (!empty($mainImage)) {
            $this->image_link = $mainImage[0];
        }

        // Process additional images
        $additionalImages = [];
        $wpImageIds = $product->get_gallery_image_ids();

        foreach ($wpImageIds as $imageId) {
            $additionalImage = wp_get_attachment_image_src($imageId, 'full');

            if (empty($additionalImage) || $this->image_link === $additionalImage[0]) {
                continue;
            }

            $additionalImages[] = $additionalImage[0];
        }

        if (!empty($additionalImages)) {
            $this->additional_image_links = $additionalImages;
        }
    }

    /**
     * Process product attributes
     */
    protected function processAttributes()
    {
        $attributes = $this->core->getConfig()->getSelect("attributes/additional", []);

        $product = $this->product;

        foreach ($attributes as $attribute_name) {
            $attribute_name = str_replace(' ', '_', $attribute_name);

            $attr = $product->get_attribute($attribute_name);

            if ($attr) {
                $additional_attributes[] = [
                    'name'  => $attribute_name,
                    'type'  => 'string',
                    // 'unit' => null,
                    'value' => $attr,
                ];
            }
        }

        if (!empty($additional_attributes)) {
            $this->attributes = $additional_attributes;
        }
    }

    /**
     * Process configurable fields (associated custom product attributes)
     */
    protected function processConfigurableFields()
    {
        foreach ([
            'color', 'gender', 'size', 'material', 'pattern',
            'age group', 'condition', 'sizeType', 'sizeSystem',
        ] as $name) {
            $key = str_replace(" ", "_", $name);

            $attribute_name = $this->core->getConfig()->get("attributes/{$key}", false);
            $attribute_value = $this->product->get_attribute($attribute_name);

            if ($attribute_name != 'Not selected' && $attribute_value) {
                $this->$key = $attribute_value;
            }
        }
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
        $property = strtolower(preg_replace("/^(unset|get|set)/", '', $name));
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
