<?php

namespace MixCode\BaseUtilities;

use Illuminate\Support\ServiceProvider;

class BaseUtilitiesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if($this->app->runningInConsole()) {
            // $this->commands([]);
        }
        
        $this->publishes([
            __DIR__ . '/../config/mix-code-base-utilities.php' => config_path('/mix-code-base-utilities.php'),
        ], 'mix-code-base-utilities-config');
    }

    public function register()
    {
        // $this->mergeConfigFrom(__DIR__ . '/../config/package-name.php', 'package-name');
    }
}