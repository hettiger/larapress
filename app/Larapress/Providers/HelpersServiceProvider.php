<?php namespace Larapress\Providers;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\ServiceProvider;
use Larapress\Controllers\BaseController;
use Larapress\Services\Helpers;

class HelpersServiceProvider extends ServiceProvider {

	/**
	 * @var DatabaseManager
	 */
	private $db;

	private $defaultDbConnection;

	protected function init()
	{
		$this->db = $this->app['db'];
		$this->defaultDbConnection = $this->db->getDefaultConnection();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->init();

		$this->app->bind('helpers', function ()
		{
			return new Helpers(
				$this->app['config'],
				$this->app['translator'],
				$this->app['view'],
				$this->app['mockably'],
				$this->app->make('log')->getMonolog(),
				$this->app['request'],
				$this->app['session.store'],
				$this->db->connection($this->defaultDbConnection),
				$this->app['redirect'],
				new BaseController
			);
		});
	}

}
