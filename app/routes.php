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
|--------------------------------------------------------------------------
| Larapress Routes
|--------------------------------------------------------------------------
|
| Please be aware of collision with routes that come with larapress. To
| make it as simple as possible for you larapress only uses grouped and
| prefixed routes. The prefixes can be customised in the larapress config
| file.
|
*/

Route::get('/', function()
{
    return View::make('hello');
});
