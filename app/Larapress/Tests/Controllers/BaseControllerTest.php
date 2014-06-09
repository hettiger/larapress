<?php namespace Larapress\Tests\Controllers;

use Larapress\Tests\Controllers\Proxies\BaseControllerProxy;
use Mockery;
use Mockery\Mock;
use PHPUnit_Framework_TestCase;

class BaseControllerTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Mock
	 */
	private $helpers;

	public function setUp()
	{
		parent::setUp();

		$this->helpers = Mockery::mock('\Larapress\Interfaces\HelpersInterface');
	}

	public function tearDown()
	{
		parent::tearDown();

		Mockery::close();
	}

	protected function getBaseControllerInstance()
	{
		return new BaseControllerProxy($this->helpers);
	}

	protected function getInitFixture()
	{
		$this->helpers->shouldReceive('initBaseController')->withNoArgs()->atLeast()->once();
	}

	/**
	 * @test init() shares some important data for all larapress views
	 */
	public function init_shares_some_important_data_for_all_larapress_views()
	{
		$this->getInitFixture();
		$controller = $this->getBaseControllerInstance();

		$controller->init();
	}

	/**
	 * @test missingMethod() calls the force404 helpers method
	 */
	public function missingMethod_returns_the_force_404_helpers_method()
	{
		$this->getInitFixture();
		$this->helpers->shouldReceive('force404')->withNoArgs()->once()->andReturn('baz');
		$controller = $this->getBaseControllerInstance();

		$this->assertEquals('baz', $controller->missingMethod());
	}

}
