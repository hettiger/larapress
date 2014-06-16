<?php namespace Larapress\Exceptions;

use App;
use Exception;

class PasswordResetCodeInvalidException extends Exception {}

if ( ! defined('RUNNING_TESTS') )
{
	App::error(function (PasswordResetCodeInvalidException $exception)
	{
		return $exception;
	});
}
