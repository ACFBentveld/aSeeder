<?php

namespace ACFBentveld\Shop\Controllers;

use Illuminate\Http\Request;
use Auth;
/**
 * Init the cart controller
 *
 */
class OptionController
{


    public function init()
    {
        return $this;
    }

    /**
     * Unserialize options and return new collection with relations
     *
     * @param string $serialized
     * @param type $withPrice
     * @return collection
     */
    public function unserialize($serialized, $withPrice = false)
    {
        $options = unserialize($serialized);
        $items = [];
        $model = config('shop.product-options-model');
        if(is_array($options)){
            foreach($options as $option){
                $item = $model::whereId($option['id'])->with('option', 'value')->first();
                if($withPrice){
                    $item->extra = $this->createPrice($item);
                }
                $items[] = $item;
            }
            return collect($items);
        }
        $item = $model::whereId($options['id'])->with('option', 'value')->first();
        return $item;
    }

    /**
     * Create price for options
     *
     * @param type $item
     * @return boolean
     */
    private function createPrice($item)
    {
        $price = 0;
        switch($item->price_prefix){
            case '+' :
                return $price + $item->price;
            case '-' :
                return $price + $item->price;
        }
    }



}