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

App::bind('Larapress\Interfaces\CaptchaInterface', 'Larapress\Services\Captcha');
App::bind('Larapress\Interfaces\HelpersInterface', 'Larapress\Services\Helpers');
App::bind('Larapress\Interfaces\MockablyInterface', 'Larapress\Services\Mockably');
App::bind('Larapress\Interfaces\NarratorInterface', 'Larapress\Services\Narrator');
App::bind('Larapress\Interfaces\NullObjectInterface', 'Larapress\Services\NullObject');
App::bind('Larapress\Interfaces\PermissionInterface', 'Larapress\Services\Permission');
