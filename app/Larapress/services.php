<?php

/*
|--------------------------------------------------------------------------
| Larapress Services
|--------------------------------------------------------------------------
|
| Here is where you can bind concrete classes to larapress's service
| interfaces.
|
*/

App::bind('Cartalyst\Sentry\Sentry', 'sentry');

App::bind('Larapress\Interfaces\CaptchaInterface', 'captcha');
App::bind('Larapress\Interfaces\HelpersInterface', 'helpers');
App::bind('Larapress\Interfaces\MockablyInterface', 'mockably');
App::bind('Larapress\Interfaces\NarratorInterface', 'narrator');
App::bind('Larapress\Interfaces\NullObjectInterface', 'null.object');
App::bind('Larapress\Interfaces\PermissionInterface', 'permission');
