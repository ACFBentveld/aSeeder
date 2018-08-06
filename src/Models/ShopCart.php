<?php

namespace ACFBentveld\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCart extends Model
{

    protected $fillable = [
        'user_id', 'user_ip'
    ];

    /**
     * Return the products inside the cart
     *
     * @return belongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(config('shop.product-model'), 'shop_cart_products', 'cart_id', 'product_id')->withPivot('amount', 'options');
    }

    
}
