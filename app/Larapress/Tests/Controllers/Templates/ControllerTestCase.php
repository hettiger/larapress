<?php namespace Larapress\Tests\Controllers\Templates;

use Larapress\Tests\TestCase;
use Mockery;
use Mockery\Mock;

abstract class ControllerTestCase extends TestCase {

	/**
	 * @var Mock
	 */
	protected $helpers;

	public function setUp()
	{
		parent::setUp();

		$this->helpers = Mockery::mock('\Larapress\Interfaces\HelpersInterface');
	}

}
