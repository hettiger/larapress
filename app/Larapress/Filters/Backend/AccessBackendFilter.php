<?php namespace Larapress\Filters\Backend;

use Illuminate\Http\RedirectResponse;
use Larapress\Exceptions\PermissionMissingException;

class AccessBackendFilter {

	/**
	 * @var \Larapress\Interfaces\PermissionInterface
	 */
	protected $permission;

	/**
	 * @var \Illuminate\Session\Store
	 */
	protected $session;

	/**
	 * @var \Illuminate\Routing\Redirector
	 */
	protected $redirect;

	public function __construct()
	{
		$app = app();

		$this->permission = $app['permission'];
		$this->session = $app['session.store'];
		$this->redirect = $app['redirect'];
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
			$this->session->flash('error', $e->getMessage());

			return $this->redirect->route('larapress.home.login.get');
		}

		return null; // User has access
	}

}
