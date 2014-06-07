<?php namespace Larapress\Filters\Backend;

use Illuminate\Http\RedirectResponse;
use Larapress\Exceptions\PermissionMissingException;

class AccessBackendFilter {

	/**
	 * @var \Larapress\Interfaces\PermissionInterface
	 */
	protected $permission;

	/**
	 * @var \Larapress\Interfaces\HelpersInterface
	 */
	protected $helpers;

	/**
	 * @codeCoverageIgnore
	 */
	public function __construct()
	{
		$app = app();

		$this->permission = $app['permission'];
		$this->helpers = $app['helpers'];
	}

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
			$this->permission->has('access.backend');
		}
		catch (PermissionMissingException $e)
		{
			return $this->helpers->redirectWithFlashMessage('error', $e->getMessage(), 'larapress.home.login.get');
		}

		return null; // User has access
	}

}
