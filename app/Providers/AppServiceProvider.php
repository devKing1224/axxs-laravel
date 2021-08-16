<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Validator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191); 
        Validator::extend('deny_email', function($attribute, $value, $parameters) {
            $domain = substr($value, strpos($value, '@') + 1);
            if ($domain == 'theaxxstablet.com' || $domain == 'axxstablet.com') {
                return false;
            } else{
                return true;
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
