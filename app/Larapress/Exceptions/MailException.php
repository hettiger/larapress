<?php namespace Larapress\Exceptions;

use App;
use Exception;

class MailException extends Exception {}

App::error(function(MailException $exception) {
    return $exception;
});
