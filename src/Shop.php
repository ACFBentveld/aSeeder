<?php

namespace ACFBentveld\Shop;

use ACFBentveld\Shop\Controllers\ShopController;
use ACFBentveld\Shop\Controllers\CartController;
use ACFBentveld\Shop\Controllers\UserController;
use ACFBentveld\Shop\Controllers\OptionController;
use ACFBentveld\Shop\Controllers\CategoryController;
use ACFBentveld\Shop\Controllers\ProductController;
use ACFBentveld\Shop\Controllers\OrderController;

/**
 * An laravel Shop package
 *
 */
class Shop extends ShopController
{

    /**
     * Init the cart class
     *
     * @param type $user_id
     * @return CartController
     */
    public static function cart($user_id = false)
    {
        return (new CartController)->cart($user_id);
    }

    /**
     * Init the user class
     *
     * @param type $user_id
     * @return UserController
     */
    public static function user($user_id = false)
    {
        return (new UserController)->user($user_id);
    }

    /**
     * Init the options class
     *
     * @return OptionController
     */
    public static function option()
    {
        return (new OptionController)->init();
    }

    /**
     * Init the categories class
     *
     * @return CategoryController
     */
    public static function category()
    {
        return (new CategoryController)->init();
    }

    /**
     * Init the products class
     *
     * @return ProductController
     */
    public static function product()
    {
        return (new ProductController)->init();
    }

    /**
     * Init the order controller
     *
     * @return OrderController
     */
    public static function order()
    {
        return (new OrderController)->init();
    }

	
}


