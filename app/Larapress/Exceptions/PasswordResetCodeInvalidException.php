<?php namespace Larapress\Exceptions;

use App;
use Exception;

class PasswordResetCodeInvalidException extends Exception {}

App::error(function(PasswordResetCodeInvalidException $exception) {
    return $exception;
});
