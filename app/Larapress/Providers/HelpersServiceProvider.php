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
            $defaultDbConnection = $this->app->make('db')->getDefaultConnection();

            return new Helpers(
                $this->app->make('config'),
                $this->app->make('translator'),
                $this->app->make('view'),
                $this->app->make('mockably'),
                $this->app->make('log')->getMonolog(),
                $this->app->make('request'),
                $this->app->make('session.store'),
                $this->app->make('db')->connection($defaultDbConnection),
                $this->app->make('redirect')
            );
        });
    }

}
