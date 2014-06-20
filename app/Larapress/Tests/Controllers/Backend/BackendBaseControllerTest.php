<?php namespace Larapress\Tests\Controllers\Backend;

use Larapress\Tests\Controllers\Backend\Proxies\BackendBaseControllerProxy;
use Larapress\Tests\Controllers\Backend\Templates\BackendControllerTestCase;

class BackendBaseControllerTest extends BackendControllerTestCase {

	protected function getBackendBaseControllerInstance()
	{
		return new BackendBaseControllerProxy($this->helpers);
	}

	/**
	 * @test init() shares important data to all views
	 */
	public function init_shares_important_data_to_all_views()
	{
		// The BackendControllerTestCase is mocking the Helpers::initBaseController() method
		$controller = $this->getBackendBaseControllerInstance();

		$controller->init();
	}

}
