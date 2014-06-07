<?php namespace Larapress\Tests\Filters\Backend\Proxies;

use Larapress\Filters\Backend\AccessBackendFilter;

class AccessBackendFilterProxy extends AccessBackendFilter {

	public function __construct($permission, $session, $redirect)
	{
		$this->permission = $permission;
		$this->session = $session;
		$this->redirect = $redirect;
	}

}
