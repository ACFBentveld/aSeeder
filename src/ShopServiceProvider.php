<?php

namespace ACFBentveld\Shop;

use Illuminate\Support\ServiceProvider;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/shop.php');
        $this->publishes([
        __DIR__.'/../config/shop.php' => config_path('shop.php')
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('Shop', 'ACFBentveld\Shop\Shop');
        $this->mergeConfigFrom(
            __DIR__.'/../config/shop.php', 'shop'
        );
    }
}
