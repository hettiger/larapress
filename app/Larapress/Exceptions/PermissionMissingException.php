<?php namespace Larapress\Exceptions;

use App;
use Exception;

class PermissionMissingException extends Exception {}

App::error(function(PermissionMissingException $exception) {
    return $exception;
});
