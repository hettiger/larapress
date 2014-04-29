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

App::before(function($request)
{
    // Record the starting time for logging the application performance
    Session::put('start.time', microtime(true));

    // Get the Throttle Provider
    $throttleProvider = Sentry::getThrottleProvider();

    // Enable the Throttling Feature
    $throttleProvider->enable();
});


App::after(function($request, $response)
{
    // Write performance related statistics into the log file
    if ( Config::get('larapress.settings.log') )
    {
        Helpers::logPerformance();
    }
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::guest('login');
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

// Apply the csrf filter to all POST, PUT, PATCH and DELETE requests
Route::when('*', 'csrf', array('post', 'put', 'patch', 'delete'));

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

Route::filter('access.backend', function()
{
    try
    {
        Permission::has('access.backend');
    }
    catch (\Larapress\Exceptions\PermissionMissingException $e)
    {
        Session::flash('error', $e->getMessage());
        return Redirect::route('larapress.home.login.get');
    }

    return null; // User has access
});

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
