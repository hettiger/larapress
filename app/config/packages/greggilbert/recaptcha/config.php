<?php

$app_lang = Config::get('app.locale');

return array(

	/*
	|--------------------------------------------------------------------------
	| API Keys
	|--------------------------------------------------------------------------
	|
	| Set the public and private API keys as provided by reCAPTCHA.
	|
	*/
	'public_key'  => getenv('RECAPTCHA_PUBLIC'),
	'private_key' => getenv('RECAPTCHA_PRIVATE'),

	/*
	|--------------------------------------------------------------------------
	| Template
	|--------------------------------------------------------------------------
	|
	| Set a template to use if you don't want to use the standard one.
	|
	*/
	'template'    => '',

	/*
	|--------------------------------------------------------------------------
	| Options
	|--------------------------------------------------------------------------
	|
	| Apply all reCAPTCHA Options you may need here. A reference of available
	| Options can be found at the google documentation:
	|
	| https://developers.google.com/recaptcha/docs/customization
	|
	*/
	'options'     => array(

		'lang'  => $app_lang,

		'theme' => 'white',

	),

);