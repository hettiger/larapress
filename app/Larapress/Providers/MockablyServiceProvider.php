<?php namespace Larapress\Providers;

use Illuminate\Support\ServiceProvider;
use Larapress\Services\Mockably;

class MockablyServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('Larapress\Services\Mockably', function ()
		{
			return new Mockably;
		});
	}

}
