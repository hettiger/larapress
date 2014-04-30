<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Configure the CMS here
    |
    */

    'settings' => array(

        'log' => false, // Logs the application performance

        'ssl' => true, // Force to use https:// requests in the backend

    ),

    'email' => array(

        'from' => array('address' => null, 'name' => null),

    ),

    /*
    |--------------------------------------------------------------------------
    | Names
    |--------------------------------------------------------------------------
    |
    | Define all kinds of names here
    |
    */

    'names' => array(

        'cms' => 'larapress',

    ),

    /*
    |--------------------------------------------------------------------------
    | URL Configuration
    |--------------------------------------------------------------------------
    |
    | Define larapress related urls here
    |
    */

    'urls' => array(

        'backend' => 'admin'

    ),

);
