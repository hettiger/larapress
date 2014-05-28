<?php namespace Larapress\Providers;

use Illuminate\Database\DatabaseManager;
use Illuminate\Log\Writer;
use Illuminate\Support\ServiceProvider;
use Larapress\Services\Helpers;

class HelpersServiceProvider extends ServiceProvider {

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * @var Writer
     */
    private $log;

    private $defaultDbConnection;

    public function __construct()
    {
        $this->db = $this->app['db'];
        $this->log = $this->app['log'];
        $this->defaultDbConnection = $this->db->getDefaultConnection();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('helpers', function()
        {
            return new Helpers(
                $this->app['config'],
                $this->app['translator'],
                $this->app['view'],
                $this->app['mockably'],
                $this->log->getMonolog(),
                $this->app['request'],
                $this->app['session.store'],
                $this->db->connection($this->defaultDbConnection),
                $this->app['redirect']
            );
        });
    }

}
