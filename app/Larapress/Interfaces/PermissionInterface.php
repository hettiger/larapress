<?php namespace Larapress\Interfaces;

use Larapress\Exceptions\PermissionMissingException;

interface PermissionInterface {

    /**
     * Check if a user is logged in and has the desired permissions
     *
     * @param string|array $permission One permission as string or several in an array to check against
     * @throws PermissionMissingException Throws an exception containing further information as message
     * @return bool Returns true if the logged in user has the required permissions
     */
    public function has($permission);

}
