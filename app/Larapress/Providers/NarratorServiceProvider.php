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
		$this->app->bind('Larapress\Services\Narrator', function ()
		{
			return new Narrator(
				$this->app['config'],
				$this->app['mailer'],
				$this->app['translator'],
				$this->app['request'],
				$this->app['sentry'],
				$this->app['Larapress\Interfaces\NullObjectInterface'],
				$this->app['Larapress\Interfaces\MockablyInterface']
			);
		});
	}

}
