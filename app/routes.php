<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

/*
|--------------------------------------------------------------------------
| Routes for the larapress backend
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for the backend.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(
    array(
        'namespace' => 'Larapress\Controllers',
        'prefix' => Config::get('larapress.urls.backend')
    ),
    function () {

        Route::group(array('prefix' => 'cp'), function() {
            Route::get('dashboard', function()
            {
                return 'Dashboard';
            });
        });

        Route::get('login', array('uses' => 'HomeController@getLogin', 'as' => 'larapress.home.login.get'));

    }
);
