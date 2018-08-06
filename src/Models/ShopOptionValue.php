<?php

namespace ACFBentveld\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopOptionValue extends Model
{

    protected $fillable = [
        'lang_id', 'option_id', 'value', 'image'
    ];

    /**
     * Return the language
     *
     * @return belongsTo
     */
    public function language()
    {
        return $this->belongsTo(config('shop.language-model'));
    }

    
}
