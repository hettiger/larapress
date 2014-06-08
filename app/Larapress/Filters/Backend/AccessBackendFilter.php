<?php namespace Larapress\Filters\Backend;

use Larapress\Exceptions\PermissionMissingException;
use Larapress\Filters\Templates\RedirectFilter;

class AccessBackendFilter extends RedirectFilter {

	/**
	 * @var \Larapress\Interfaces\PermissionInterface
	 */
	protected $permission;

	/**
	 * @codeCoverageIgnore
	 */
	protected function init($app)
	{
		$this->permission = $app['permission'];
	}

	/**
	 * Redirect the user with a flash message if he's missing permissions to the backend
	 *
	 * @return bool|\Illuminate\HTTP\RedirectResponse
	 */
	protected function redirect()
	{
		try
		{
			$this->permission->has('access.backend');
			return false;
		}
		catch (PermissionMissingException $e)
		{
			return $this->helpers->redirectWithFlashMessage('error', $e->getMessage(), 'larapress.home.login.get');
		}
	}

}
