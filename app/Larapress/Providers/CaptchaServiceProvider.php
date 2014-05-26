<?php namespace Larapress\Providers;

use Illuminate\Support\ServiceProvider;
use Larapress\Services\Captcha;
use Larapress\Services\Helpers;

class CaptchaServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('captcha', function()
        {
            return new Captcha(
                $this->app['view'],
                $this->app['config'],
                $this->app['session.store'],
                new Helpers
            );
        });
    }

}
