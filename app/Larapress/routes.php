<?php

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

        Route::group(array('namespace' => 'Api', 'prefix' => 'api'), function() {
            Route::controller('/captcha', 'CaptchaController', array(
                'postValidate' => 'larapress.api.captcha.validate.post',
            ));
        });

        Route::group(array('prefix' => 'cp'), function() {
            Route::controller('/', 'ControlPanelController', array(
                'getDashboard' => 'larapress.cp.dashboard.get',
            ));
        });

        Route::controller('/', 'HomeController', array(
            'getLogin' => 'larapress.home.login.get',
            'postLogin' => 'larapress.home.login.post',
            'getLogout' => 'larapress.home.logout.get',
            'getResetPassword' => 'larapress.home.reset.password.get',
            'postResetPassword' => 'larapress.home.reset.password.post',
            'getSendNewPassword' => 'larapress.home.send.new.password.get',
        ));

    }
);
