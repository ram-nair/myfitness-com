<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ( (env('APP_ENV') == 'staging') || (env('APP_ENV') == 'production')) {
              \URL::forceScheme('https');
        }
        Schema::defaultStringLength(191);
    }
}
