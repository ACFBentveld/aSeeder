<?php

namespace ACFBentveld\Shop;

use Illuminate\Support\Facades\Facade;

class ShopFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Shop';
    }
}
