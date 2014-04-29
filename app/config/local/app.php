<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => true,

    /*
    |--------------------------------------------------------------------------
    | Append Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | For development tasks we need some more Service Providers being
    | autoloaded. Those are listed below.
    |
    */

    'providers' => append_config(
        array(
            'Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider',
        )
    ),

);
