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
	//
});


App::after(function($request, $response)
{
	//
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
    if ( ! Sentry::check())
    {
        Session::flash('error', 'User is not logged in.');
        return Redirect::route('larapress.home.login.get');
    }
    else
    {
        try
        {
            $user = Sentry::getUser();

            if ( ! $user->hasAccess('access.backend') )
            {
                Session::flash('error', 'User is missing access rights for this area.');
                return Redirect::route('larapress.home.login.get');
            }
            else
            {
                return null; // User has access
            }
        }
        catch (Cartalyst\Sentry\UserNotFoundException $e)
        {
            Session::flash('error', 'User was not found.');
            return Redirect::route('larapress.home.login.get');
        }
    }
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
