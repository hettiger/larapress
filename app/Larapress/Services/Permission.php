<?php namespace Larapress\Services;

use Larapress\Exceptions\PermissionMissingException;
use Larapress\Interfaces\PermissionInterface;
use Sentry;

class Permission implements PermissionInterface
{

    /**
     * Check if a user is logged in and has the desired permissions
     *
     * @param string|array $permission One permission as string or several in an array to check against
     * @throws PermissionMissingException Throws an exception containing further information as message
     * @return bool Returns true if the logged in user has the required permissions
     */
    public function has($permission)
    {
        if ( ! Sentry::check())
        {
            throw new PermissionMissingException('User is not logged in.');
        }
        else
        {
            $user = Sentry::getUser();

            if ( ! $user->hasAccess($permission) )
            {
                throw new PermissionMissingException('User is missing permissions.');
            }
            else
            {
                return true;
            }
        }
    }

}
