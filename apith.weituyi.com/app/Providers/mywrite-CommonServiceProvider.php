<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CommonServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('CommonService', function () {
            return new \App\Services\CommonUtils();
        });
    }
}