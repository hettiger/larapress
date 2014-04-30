<?php namespace Larapress\Exceptions;

use App;
use Exception;

class PasswordResetFailedException extends Exception {}

App::error(function(PasswordResetFailedException $exception) {
    return $exception;
});
