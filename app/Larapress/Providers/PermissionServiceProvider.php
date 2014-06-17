<?php namespace Larapress\Providers;

use Illuminate\Support\ServiceProvider;
use Larapress\Services\Permission;

class PermissionServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('Larapress\Services\Permission', function ()
		{
			return new Permission(
				$this->app['sentry']
			);
		});
	}

}
