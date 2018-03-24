<?php

namespace Statamic\Addons\GoogleAnalytics;

use Statamic\Extend\ServiceProvider;

class GoogleAnalyticsServiceProvider extends ServiceProvider
{
    public $providers = [
        \JRC9DS\Analytics\AnalyticsServiceProvider::class
    ];

    public $aliases = [
        'Analytics' => \JRC9DS\Analytics\AnalyticsFacade::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
      //
    }
}
