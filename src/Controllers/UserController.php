<?php

namespace ACFBentveld\Shop\Controllers;

use Illuminate\Http\Request;
use Auth;
/**
 * Init the cart controller
 *
 */
class UserController
{

    protected $user_id;
    protected $user;


    public function user($user_id)
    {
        if(Auth::check()){
            $this->user = Auth::user();
            $this->user_id = $this->user->id;
        }
        return $this;
    }

    /**
     * Return the lastest order
     *
     * @return \ACFBentveld\Shop\Models\ShopOrder
     */
    public function latestOrder()
    {
        $model = config('shop.order-model');
        $order = $model::whereUserId($this->user_id)->latest()->first();
        return $order;
    }

    /**
     * Return all the user orders
     *
     * @return \ACFBentveld\Shop\Models\ShopOrder
     * @param int $paginate use paginationor not
     */
    public function getOrders($paginate = false, $sort = 'asc')
    {
        $model = config('shop.order-model');
        $orders = (!$paginate)?
            $model::whereUserId($this->user_id)->orderBy('id', $sort)->get():
            $model::whereUserId($this->user_id)->orderBy('id', $sort)->paginate($paginate);
        return $orders;
    }

    /**
     * Return a single order of a user
     *
     * @param string $payment_id
     * @return \ACFBentveld\Shop\Models\ShopOrder
     */
    public function getOrderByPaymentID(string $payment_id)
    {
        $model = config('shop.order-model');
        $order = $model::whereUserId($this->user_id)->wherePaymentId($payment_id)->first();
        return $order;
    }






}