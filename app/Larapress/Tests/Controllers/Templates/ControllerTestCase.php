<?php namespace Larapress\Tests\Controllers\Templates;

use Mockery;
use Mockery\Mock;
use PHPUnit_Framework_TestCase;

abstract class ControllerTestCase extends PHPUnit_Framework_TestCase {

	/**
	 * @var Mock
	 */
	protected $helpers;

	public function setUp()
	{
		parent::setUp();

		$this->helpers = Mockery::mock('\Larapress\Interfaces\HelpersInterface');
		$this->helpers->shouldReceive('initBaseController')->withNoArgs()->once();
	}

	public function tearDown()
	{
		parent::tearDown();

		Mockery::close();
	}

}
