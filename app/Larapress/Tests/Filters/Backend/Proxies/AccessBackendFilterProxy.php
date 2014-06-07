<?php namespace Larapress\Tests\Filters\Backend\Proxies;

use Larapress\Filters\Backend\AccessBackendFilter;

class AccessBackendFilterProxy extends AccessBackendFilter {

	public function __construct($permission, $helpers)
	{
		$this->permission = $permission;
		$this->helpers = $helpers;
	}

}
