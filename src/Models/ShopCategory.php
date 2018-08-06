<?php

namespace ACFBentveld\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{

    protected $fillable = [
        'name', 'description', 'image', 'lang_id', 'is_sub'
    ];

    /**
     * Return children categories
     *
     * @return hasMany
     */
    public function subcategories()
    {
        return $this->hasMany(config('shop.category-model'), 'is_sub');
    }

    /**
     * Return the parent category
     *
     * @return belongsTo
     */
    public function category()
    {
        return $this->belongsTo(config('shop.category-model'), 'is_sub');
    }

    /**
     * Return the parent category
     *
     * @return belongsTo
     */
    public function products()
    {
       return $this->belongsToMany(config('shop.product-model'), 'shop_product_categories', 'product_id', 'category_id');
    }


}
