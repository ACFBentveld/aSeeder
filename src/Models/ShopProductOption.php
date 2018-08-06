<?php

namespace ACFBentveld\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProductOption extends Model
{

    protected $fillable = [
        'product_id', 'option_id', 'quantity', 'remove_from_stock', 'price_prefix', 'required', 'price', 'value_id'
    ];

    /**
     * Return the original option
     *
     * @return belongsTo
     */
    public function option()
    {
        return $this->belongsTo(config('shop.option-model'), 'option_id');
    }

    /**
     * Return the original value
     *
     * @return belongsTo
     */
    public function value()
    {
        return $this->belongsTo(config('shop.option-values-model'), 'value_id');
    }
   
}
