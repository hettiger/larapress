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

        Route::get('login', array('uses' => 'HomeController@getLogin', 'as' => 'home.login.get'));

    }
);
