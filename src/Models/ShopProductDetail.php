<?php

namespace ACFBentveld\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Request;

class ShopProductDetail extends Model
{

    protected $fillable = [
        'product_id', 'model', 'sku', 'upc', 'mpn', 'quantity','taxable', 'remove_stock', 'requires_shipping', 'available_on'
    ];

    /**
     * Boot up
     *
     */
    public static function boot()
    {
        parent::boot();

        ShopProductDetail::saving(function($details){
            //Change date format to the standard for mysql
           $details->available_on = date('Y-m-d', strtotime($details->available_on));
        });

    }


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
