<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Database Credentials
    |--------------------------------------------------------------------------
    |
    | Here you may provide your environment specific MySQL database credentials
    |
    */

    'MYSQL_HOST'        => 'localhost',
    'MYSQL_DATABASE'    => 'database',
    'MYSQL_USERNAME'    => 'root',
    'MYSQL_PASSWORD'    => '',

    /*
    |--------------------------------------------------------------------------
    | SMTP Credentials
    |--------------------------------------------------------------------------
    |
    | Here you may provide your environment specific SMTP (Mail) credentials
    |
    */

    'SMTP_HOST'         => 'smtp.mailgun.org',
    'SMTP_PORT'         => 587,
    'SMTP_ENCRYPTION'   => 'tls',
    'SMTP_USERNAME'     => null,
    'SMTP_PASSWORD'     => null,

    /*
    |--------------------------------------------------------------------------
    | Google reCAPTCHA API Keys
    |--------------------------------------------------------------------------
    |
    | Set the public and private API keys as provided by reCAPTCHA.
    |
    */

    'RECAPTCHA_PUBLIC'  => 'your-public-key',
    'RECAPTCHA_PRIVATE' => 'your-private-key',

);
