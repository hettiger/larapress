<?php namespace Larapress\Providers;

use Illuminate\Support\ServiceProvider;
use Larapress\Services\Helpers;

class HelpersServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('helpers', function()
        {
            return new Helpers;
        });
    }

}
