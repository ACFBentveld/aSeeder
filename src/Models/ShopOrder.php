<?php

namespace ACFBentveld\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{

    protected $fillable = [
        'user_id', 'status', 'payment_id', 'method', 'note', 'amount', 'description', 'order_status','shipping_id'
    ];

    /**
     * Return the products inside the cart
     *
     * @return belongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(config('shop.product-model'), 'shop_order_products', 'order_id', 'product_id')->withPivot('amount', 'unit_price', 'options');
    }

    /**
     * Return the user
     *
     * @return belongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('shop.user-model'));
    }

    /**
     * Return the order data
     * can be multiple for there are mutliple adresses
     *
     * @return hasMany
     */
    public function data()
    {
        return $this->hasMany(config('shop.order-data-model'), 'order_id');
    }

    /**
     * Return the status of the order
     *
     * @return belongsTo
     */
    public function shopStatus()
    {
        return $this->belongsTo(config('shop.order-status-model'), 'order_status');
    }

    /**
     * Return the shipping method
     *
     * @return belongsTo
     */
    public function shipping()
    {
        return $this->belongsTo(config('shop.shipping-model'), 'shipping_id');
    }

    
}
