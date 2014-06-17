<?php namespace Larapress\Providers;

use Illuminate\Support\ServiceProvider;
use Larapress\Services\NullObject;

class NullObjectServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('Larapress\Services\NullObject', function ()
		{
			return new NullObject;
		});
	}

}
