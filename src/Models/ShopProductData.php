<?php

namespace ACFBentveld\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProductData extends Model
{

    protected $fillable = [
        'product_id', 'lang_id', 'slug', 'name', 'description'
    ];

    /**
     * Return the parent product
     * 
     * @return belongsTo
     */
    public function product()
    {
        return $this->belongsTo(config('shop.product-model'), 'product_id');
    }

    
}
