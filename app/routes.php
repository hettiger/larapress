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
            Route::controller('/', 'ControlPanelController', array(
                'getDashboard' => 'larapress.cp.dashboard.get',
            ));
        });

        Route::controller('/', 'HomeController', array(
            'getLogin' => 'larapress.home.login.get',
            'getLogout' => 'larapress.home.logout.get',
            'getResetPassword' => 'larapress.home.reset.password.get',
            'getSendNewPassword' => 'larapress.home.send.new.password.get',
        ));

    }
);
