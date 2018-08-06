<?php

namespace ACFBentveld\Shop\Controllers;

/**
 * Init the category controller
 *
 */
class CategoryController
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
        $this->model = config('shop.category-model');
        return $this;
    }

    /**
     * Get catgeory by slug
     *
     * @param string $slug
     * @return $this->model
     */
    public function getBySlug(string $slug)
    {
        $category = $this->model::whereSlug($slug)->first();
        return $category;
    }

    /**
     * Get category by ID
     *
     * @param int $id
     * @return $this->model
     */
    public function getById(int $id)
    {
        $category = $this->model::find($id);
        return $category;
    }



}