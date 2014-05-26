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
        $self = $this;

        $this->app->bind('captcha', function() use ($self)
        {
            return new Captcha(
                $self->app['view'],
                $self->app['config'],
                $self->app['session.store'],
                new Helpers
            );
        });
    }

}
