<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function()
{
    Session::put('start.time', Mockably::microtime());

    $throttleProvider = Sentry::getThrottleProvider();
    $throttleProvider->enable();
});


App::after(function()
{
    if ( Config::get('larapress.settings.log') )
    {
        Helpers::logPerformance();
    }
});

/*
|--------------------------------------------------------------------------
| Special larapress Filters
|--------------------------------------------------------------------------
|
| The following filters are developed for larapress but may be also useful
| for your website. You can apply them to any route you'd like.
|
*/

Route::filter('force.human', 'Larapress\Filters\Special\ForceHumanFilter');

/*
|--------------------------------------------------------------------------
| Filters for the larapress backend
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application and has the required permissions
| for several tasks.
|
*/

Route::filter('access.backend', 'Larapress\Filters\Backend\AccessBackendFilter');
Route::filter('force.ssl', 'Larapress\Filters\Backend\ForceSSLFilter');

/*
|--------------------------------------------------------------------------
| Pattern Filters for the larapress backend
|--------------------------------------------------------------------------
|
| The following filters are used to define when to use larapress's filters.
|
*/

$backend_url = Config::get('larapress.urls.backend');

Route::when($backend_url . '/cp*', 'access.backend');
Route::when($backend_url . '/reset-password', 'force.human', array('post'));
Route::when($backend_url . '*', 'force.ssl');
