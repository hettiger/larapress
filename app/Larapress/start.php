<?php

/*
|--------------------------------------------------------------------------
| Require The Larapress Routes File
|--------------------------------------------------------------------------
|
| Require larapress's separated routes file.
|
*/

require app_path() . '/Larapress/routes.php';

/*
|--------------------------------------------------------------------------
| Require The Larapress Filters File
|--------------------------------------------------------------------------
|
| Require larapress's separated filters file.
|
*/

require app_path() . '/Larapress/filters.php';

/*
|--------------------------------------------------------------------------
| Require The Larapress Services File
|--------------------------------------------------------------------------
|
| Require larapress's services file to define IoC bindings
|
*/

require app_path() . '/Larapress/services.php';
