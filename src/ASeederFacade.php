<?php

namespace ACFBentveld\ASeeder;

use Illuminate\Support\Facades\Facade;

class ASeederFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ASeeder';
    }
}
