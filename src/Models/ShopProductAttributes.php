<?php

namespace ACFBentveld\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProductAttributes extends Model
{

    protected $fillable = [
        'product_id', 'name', 'description' , 'lang_id'
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
