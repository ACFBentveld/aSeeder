<?php

namespace ACFBentveld\Shop\Controllers;

use Illuminate\Http\Request;


/**
 * Init the cart controller
 *
 */
class OrderController
{


    public function init()
    {
        return $this;
    }

    public function moveCartToOrder($order, $user_id = false)
    {
        $cart = \Shop::cart($user_id);
        if($order) {
            foreach($cart->cart->products as $product){
                $this->runOptionSupply($product);
                $order->products()->attach($product->id, ['amount' => $product->pivot->amount, 'unit_price' => $product->getPrices()->total, 'options' => $product->pivot->options]);
            }
            $this->runSupply($cart);
        }
        return $this;
    }



    /**
     * Check the supply for every product in the cart and update it
     *
     * @param type $cart
     * @return boolean
     */
    private function runSupply($cart)
    {
        if(!$cart->cart->products){
            return false;
        }
        foreach($cart->cart->products as $product){
            if(!$product->details->remove_stock){
                continue;
            }
            $supply = $product->details->quantity;
            $amount = $product->pivot->amount;
            $product->details->update(['quantity' => $supply - $amount]);
        }
        return true;
    }

    /**
     * Update the stock of the product options
     *
     * @param type $product
     */
    private function runOptionSupply($product)
    {
        $options = $product->getOptions();
        foreach($options as $option){
            if(!$option->remove_from_stock){
                continue;
            }
            $option->quantity = $option->quantity - $product->pivot->amount;
            $option->save();
        }
    }







}