<?php

namespace ACFBentveld\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopOrderData extends Model
{

    protected $fillable = [
        'order_id', 'email', 'name', 'address', 'postalcode', 'city'
    ];

    
}
