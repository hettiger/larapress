<?php namespace Larapress\Tests\Controllers;

use Larapress\Controllers\BaseController;
use Larapress\Tests\Controllers\Templates\ControllerTestCase;

class BaseControllerTest extends ControllerTestCase {

	protected function getBaseControllerInstance()
	{
		return new BaseController($this->helpers);
	}

	/**
	 * @test missingMethod() calls the force404 helpers method
	 */
	public function missingMethod_returns_the_force_404_helpers_method()
	{
		$this->helpers->shouldReceive('force404')->withNoArgs()->once()->andReturn('baz');
		$controller = $this->getBaseControllerInstance();

		$this->assertEquals('baz', $controller->missingMethod());
	}

}
