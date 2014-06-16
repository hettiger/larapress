<?php namespace Larapress\Exceptions;

use App;
use Exception;

class PermissionMissingException extends Exception {}

if ( ! defined('RUNNING_TESTS') )
{
	App::error(function (PermissionMissingException $exception)
	{
		return $exception;
	});
}
