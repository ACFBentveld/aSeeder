<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Route::group(['prefix' => config('shop.route_prefix'), 'middleware' => config('shop.middleware')],function () {

    Route::delete('cart/product/remove'     , '\ACFBentveld\Shop\Controllers\CartController@removeProduct')->name('shop:cart.product.remove');
    
    Route::post('cart/product/add'          , '\ACFBentveld\Shop\Controllers\CartController@addToCart')->name('shop:cart.product.add');

    Route::post('cart/update/amount'        , '\ACFBentveld\Shop\Controllers\CartController@changeAmount')->name('shop:cart.update.amount');


    Route::group(['prefix' => 'webhooks'],function () {
        Route::post('mollie'        , '\ACFBentveld\Shop\Webhooks\MollieController@handle')->name('shop:webhooks.mollie');
    });

});

