<?php

namespace App\Providers;

use App\Services\Api\Coinpayments;
use App\Services\Api\Eth;
use App\Services\Api\Btc;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //on local, clear the logs for each request for easier debugging
        if ($this->app->environment() === 'local') {
            //$logpath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . '../logs/laravel.log';
            //exec('echo "" > ' . $logpath);
        }
        Blade::withoutDoubleEncoding();
        Paginator::useBootstrapThree();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('api.gateway.btc', Btc::class);
        $this->app->bind('api.gateway.eth', Eth::class);
        $this->app->bind('api.gateway.coinpayments', Coinpayments::class);

        $this->registerRollbar();
    }

    private function registerRollbar()
    {
        if ($this->app->environment('production') || $this->app->environment('staging')) {
            $this->app->register(\Rollbar\Laravel\RollbarServiceProvider::class);
        }
    }
}
