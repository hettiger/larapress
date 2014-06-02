<?php namespace Larapress\Providers;

use Illuminate\Support\ServiceProvider;
use Larapress\Services\Captcha;

class CaptchaServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('captcha', function()
        {
            return new Captcha(
                $this->app['view'],
                $this->app['config'],
                $this->app['session.store'],
                $this->app['helpers'],
				$this->app['mockably']
            );
        });
    }

}
