<?php

if (!defined( 'URBIT_INVENTORY_FEED_PLUGIN_DIR' )) {
    exit;
}

class UIF_Query
{
    /**
     * @var UIF_Core
     */
    protected $core;

    /**
     * UIF_Query constructor.
     * @param UIF_Core $core
     */
    public function __construct(UIF_Core $core)
    {
        $this->core = $core;
        add_action( 'wp_ajax_filter_handler', array($this, 'filter_handler') );
    }

    function filter_handler() {
        $selectedTags = $_POST['tags'];
        $selectedCollects = $_POST['collects'];
        $stock = $_POST['stock'];
        $result = $_POST['result'];
        $filter_query = $this->productsQuery(['categories' => $selectedCollects, 'tags' => $selectedTags, 'stock' => $stock]);
        $product_posts = $filter_query->get_posts();
        $products = array();
        $pf = new WC_Product_Factory();
        foreach ($product_posts as $post)
        {
            $temp = $pf->get_product($post);
            if(!in_array((string)$temp->get_id(), $result))
                array_push($products, ['product_id' => $temp->get_id(), 'title' => $temp->get_name()]);
        }
        $return = array(
            'message'  => 'Ok!',
            'result' => $products
        );

        wp_send_json($return);
    }

    /**
     * Get all products query
     *
     * @param array $args
     * @return WP_Query
     */
    function productsQuery($args = array())
    {
        // Set base query arguments
        $queryArgs = array(
            'fields'         => 'ids',
            'posts_per_page' => -1,
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'meta_query'     => array(),
        );

        // Filter products by categories
        if (!empty($args['categories'])) {
            $queryArgs['tax_query']['relation'] = 'OR';
            $queryArgs['tax_query'][] = array(
                'taxonomy'  => 'product_cat',
                'field'     => 'term_id',
                'terms'     => $args['categories']
            );
        }

        // Filter products by tags
        if (!empty($args['tags'])) {
            $queryArgs['tax_query']['relation'] = 'OR';
            $queryArgs['tax_query'][] = array(
                'taxonomy'  => 'product_tag',
                'field'     => 'term_id',
                'terms'     => $args['tags']
            );
        }

        // Filter by minimal stock
        if (!empty($args['stock']) && $args['stock'] > 0) {
            $queryArgs['meta_query']['relation'] = 'AND';
            $queryArgs['meta_query'][] = array(
                'key'       => '_stock',
                'value'     => (int) $args['stock'],
                'compare'   => '>=',
                'type'      => 'NUMERIC',
            );
        }

        return new WP_Query($queryArgs);
    }

    /**
     * @param array $queryArgs
     * @param array $dimension
     * @param string $key
     */
    protected function addDimensionToQueryArgs(&$queryArgs, $dimension, $key)
    {
        if (!empty($dimension['from'])) {
            $queryArgs['meta_query']['relation'] = 'AND';
            $queryArgs['meta_query'][] = array(
                'key'       => '_' . $key,
                'value'     => (int) $dimension['from'],
                'compare'   => '>=',
                'type'      => 'NUMERIC',
            );
        }

        if (!empty($dimension['to'])) {
            $queryArgs['meta_query']['relation'] = 'AND';
            $queryArgs['meta_query'][] = array(
                'key'       => '_' . $key,
                'value'     => (int) $dimension['to'],
                'compare'   => '<=',
                'type'      => 'NUMERIC',
            );
        }
    }
}