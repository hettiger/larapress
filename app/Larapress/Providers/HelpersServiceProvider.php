<?php namespace Larapress\Providers;

use Illuminate\Database\DatabaseManager;
use Illuminate\Log\Writer;
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
            /**
             * @var DatabaseManager
             */
            $db = $this->app['db'];

            /**
             * @var Writer
             */
            $log = $this->app['log'];

            $defaultDbConnection = $this->app->make('db')->getDefaultConnection();

            return new Helpers(
                $this->app['config'],
                $this->app['translator'],
                $this->app['view'],
                $this->app['mockably'],
                $log->getMonolog(),
                $this->app['request'],
                $this->app['session.store'],
                $db->connection($defaultDbConnection),
                $this->app['redirect']
            );
        });
    }

}
