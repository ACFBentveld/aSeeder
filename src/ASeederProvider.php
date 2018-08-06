<?php

namespace ACFBentveld\ASeeder;

use Illuminate\Support\ServiceProvider;

class ASeederServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \ACFBentveld\ASeeder\Commands\MakeSeed::class
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        
    }
}
