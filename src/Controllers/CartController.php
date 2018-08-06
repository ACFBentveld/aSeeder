<?php

namespace ACFBentveld\Shop\Controllers;

use Illuminate\Http\Request;
use Auth;
/**
 * Init the cart controller
 *
 */
class CartController
{
    /**
     * The cart model
     *
     * @var void
     */
    protected $model;

    /**
     * User id if logged in
     *
     * @var integer
     */
    protected $user_id;

    /**
     * The users IP
     *
     * @var string
     */
    protected $user_ip;
    

    /**
     * Init the cart
     *
     * @return $this
     */
    public function cart($user_id = false)
    {
        $model = config('shop.cart-model');
        $this->model = new $model();
        $this->user_ip = \Request::ip();
        $this->user_id = ($user_id)?$user_id:optional(Auth::user())->id;
        $this->makeCart($user_id);
        return $this;
    }

    /**
     * Create the cart
     */
    private function makeCart($user_id)
    {
        if($user_id){
            $this->cart = $this->model::whereUserId($this->user_id)->first();
        }else{
            $this->cart = (Auth::check()) ? $this->model::whereUserId($this->user_id)->first() : $this->model::whereUserId(null)->whereUserIp($this->user_ip)->first() ;
            if(!$this->cart){
                $this->cart = $this->model::firstOrCreate([
                    'user_id' => $this->user_id,
                    'user_ip' => $this->user_ip
                ]);
            }
            if(Auth::check() && !$this->cart->user_id){
                $this->cart->update(['user_id' => $this->user_id]);
            }
        }
    }

    /**
     * Add product to cart
     *
     * @param int $product_id
     * @param int $amount
     * @return boolean
     */
    public function addproduct(int $product_id, int $amount = 1, $options = [])
    {
        $model = config('shop.product-model');
        $product = $model::find($product_id);
        $supplyCheck = \Shop::getSettingByName('allow_order_without_supply');
        if($product && ($product->details->quantity >= $amount || $supplyCheck->value)){
            $options = $this->getOptions($options, $product);
            $this->cart->products()->attach($product->id, ['amount' => $amount, 'options' => serialize($options)]);
            return true;
        }
        return false;
    }

    /**
     * Add product to the cart
     *
     * @param int $product_id
     * @param int $amount
     * @return response
     */
    public function addToCart(Request $request)
    {
        $this->cart();
        
        $product_id = (config('shop.routes_encrypted'))?decrypt($request->get('product_id')):$request->get('product_id');
        $amount = ($request->has('amount') && is_numeric($request->get('amount')))?$request->get('amount'):1;
        $model = config('shop.product-model');
        $product = $model::find($product_id);
        if($product){
            $options = $this->getOptions($request->get('options', []), $product);
            $this->cart->products()->attach($product->id, ['amount' => $amount, 'options' => serialize($options)]);
            return response()->json(['status' => 'success', 'message' => null]);
        }
        return response()->json(['status' => 'error', 'message' => 'product not found'], 400);
    }

    /**
     * Get all options selected by the user
     *
     * @param type $options
     * @param type $product
     * @return colelction
     */
    private function getOptions($options, $product)
    {
        $items = [];
        $model = config('shop.product-options-model');
        foreach($options as $value_id){
            $id = (config('shop.routes_encrypted'))?decrypt($value_id):$value_id;
            $productOption = $model::find($id);
            if($productOption){
                $items[] = $productOption->toArray();
            }
        }
        return $items;
    }

    /**
     * Clean the cart and return product id's as array
     *
     * @return array
     */
    public function cleanCart($user_id = false)
    {
        $ids = [];
        $this->cart($user_id);
        foreach($this->getProducts() as $product){
            $ids[] = $product->id;
            $this->cart->products()->detach($product->id);
        }
        return $ids;
    }

    /**
     * Remove a product from the cart
     *
     * @param Request $request
     */
    public function removeProduct(Request $request)
    {
        $this->cart();
        if($request->has('product_id')){
            $id = (config('shop.routes_encrypted'))?decrypt($request->get('product_id')):$request->get('product_id');
            $this->detachProduct($id);
            return response()->json(['status' => 'success', 'message' => null]);
        }
        return response()->json(['status' => 'error', 'message' => 'field product_id is missing'], 400);
    }

    /**
     * Detach the product from the cart
     *
     * @param int $id
     * @return boolean
     */
    public function detachProduct(int $id)
    {
        $this->cart->products()->detach($id);
        return true;
    }

    /**
     * Update the ampount of the product inside the cart
     * Function accessible using a post
     *
     * @param Request $request
     * @return response json
     */
    public function changeAmount(Request $request)
    {
        $id = (config('shop.routes_encrypted'))?decrypt($request->get('product_id')):$request->get('product_id');
        $this->updateAmount($id, $request->get('amount'));
        return response()->json(['status' => 'success', 'message' => null]);
    }

    /**
     * Update the amoutn of the product inside the cart
     *
     * @param int $product_id
     * @param int $amount
     * @return boolean
     */
    public function updateAmount(int $product_id, int $amount)
    {
        $this->cart();
        if($amount <= 0){
            $this->cart->products()->detach($product_id);
            return true;
        }
        $product = $this->cart->products->find($product_id);
        if($product){
            $product->pivot->amount = $amount;
            $product->pivot->save();
        }
        return true;
    }

    /**
     * Get the cart products
     *
     * @return ShopProduct
     */
    public function getProducts()
    {
        return $this->cart->products;
    }

    /**
     * Count the total products inside the cart
     *
     * @return int
     */
    public function countProducts()
    {
        return $this->cart->products->count();
    }

    /**
     * Return the cart prices
     *
     * @return collection
     */
    public function getPrices()
    {
        $this->prices = new \stdClass();
        $this->prices->ex_tax   = 0;
        $this->prices->tax      = 0;
        $this->prices->total    = 0;
        $this->pricePerProduct();
        return $this->prices;
    }

    /**
     * Calculate the price per product
     *
     */
    private function pricePerProduct()
    {
        $btw = str_replace('%', '', config('shop.product_tax'));
        foreach($this->cart->products as $product){
            $options = \Shop::option()->unserialize($product->pivot->options, true);
            $extra = 0;
            if($options){
                $extra = ($extra + $options->where('price_prefix', '+')->sum('extra')) -
                    $options->where('price_prefix', '-')->sum('extra');
            }
            $this->prices->ex_tax   += ($product->price() + $extra)  * $product->pivot->amount;
        }
        $this->prices->total    += ($this->prices->ex_tax / 100)  * (100 + $btw);
        $this->prices->tax      += $this->prices->total - $this->prices->ex_tax;
    }


}