<?php namespace Larapress\Exceptions;

use App;
use Exception;

class MailException extends Exception {}

if ( ! defined('RUNNING_TESTS') )
{
	App::error(function (MailException $exception)
	{
		return $exception;
	});
}
