<?php namespace Larapress\Providers;

use Illuminate\Support\ServiceProvider;
use Larapress\Services\Narrator;

class NarratorServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('narrator', function()
        {
            return new Narrator;
        });
    }

}
