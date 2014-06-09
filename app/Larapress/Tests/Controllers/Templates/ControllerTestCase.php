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
	}

	public function tearDown()
	{
		parent::tearDown();

		Mockery::close();
	}

}
