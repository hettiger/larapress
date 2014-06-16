<?php namespace Larapress\Exceptions;

use App;
use Exception;

class PasswordResetFailedException extends Exception {}

if ( ! defined('RUNNING_TESTS') )
{
	App::error(function (PasswordResetFailedException $exception)
	{
		return $exception;
	});
}
