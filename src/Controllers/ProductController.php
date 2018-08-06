<?php

namespace ACFBentveld\Shop\Controllers;

/**
 * Init the category controller
 *
 */
class ProductController
{

    /**
     * The collection model
     *
     * @var void
     */
    public $model = null;

    /**
     * Init the class
     *
     * @return $this
     */
    public function init()
    {
        $this->model = config('shop.product-model');
        return $this;
    }

    /**
     * Get product by slug
     *
     * @param string $slug
     * @return $this->model
     */
    public function getBySlug(string $slug)
    {
        $product = $this->model::whereHas('products', function($query) use($slug){
            $query->whereSlug($slug);
        })->first();
        return $product;
    }

    /**
     * Get product by ID
     *
     * @param int $id
     * @return $this->model
     */
    public function getById(int $id)
    {
        $product = $this->model::find($id);
        return $product;
    }

    /**
     * Return related products
     *
     * @param type $product
     * @return collection
     */
    public function related($product)
    {
        $products = $this->model::where('id','!=', $product->id)->get();
        return $products;
    }


}