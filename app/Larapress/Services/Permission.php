<?php namespace Larapress\Services;

use Larapress\Exceptions\PermissionMissingException;
use Larapress\Interfaces\PermissionInterface;
use Cartalyst\Sentry\Sentry;

class Permission implements PermissionInterface
{

    /**
     * @var \Cartalyst\Sentry\Sentry
     */
    private $sentry;

    /**
     * @param Sentry $sentry
     *
     * @return void
     */
    public function __construct(Sentry $sentry)
    {
        $this->sentry = $sentry;
    }

    /**
     * Check if a user is logged in and has the desired permissions
     *
     * @param string|array $permission One permission as string or several in an array to check against
     * @throws PermissionMissingException Throws an exception containing further information as message
     * @return bool Returns true if the logged in user has the required permissions
     */
    public function has($permission)
    {
        if ( ! $this->sentry->check())
        {
            throw new PermissionMissingException('User is not logged in.');
        }
        else
        {
            $user = $this->sentry->getUser();

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
