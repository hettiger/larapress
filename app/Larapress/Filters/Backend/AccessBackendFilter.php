<?php namespace Larapress\Filters\Backend;

use Illuminate\Http\RedirectResponse;
use Larapress\Exceptions\PermissionMissingException;
use Permission;
use Redirect;
use Session;

class AccessBackendFilter
{

    /**
     * Check if the user has the permission to access the backend
     * If not redirect him to the login page with some flash message
     *
     * @return RedirectResponse|null
     */
    public function filter()
    {
        try
        {
            Permission::has('access.backend');
        }
        catch (PermissionMissingException $e)
        {
            Session::flash('error', $e->getMessage());
            return Redirect::route('larapress.home.login.get');
        }

        return null; // User has access
    }

}
