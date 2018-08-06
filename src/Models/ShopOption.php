<?php

namespace ACFBentveld\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopOption extends Model
{

    protected $fillable = [
        'name', 'type_id'
    ];

    /**
     * Return the option type
     * 
     * @return belongsTo
     */
    public function type()
    {
        return $this->belongsTo(config('shop.option-types-model'), 'type_id');
    }

    /**
     * Return the option values
     *
     * @return hasMany
     */
    public function values()
    {
        return $this->hasMany(config('shop.option-values-model'), 'option_id');
    }

    
}
