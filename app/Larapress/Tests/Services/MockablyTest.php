<?php namespace Larapress\Tests\Services;

use Larapress\Services\Mockably;
use PHPUnit_Framework_TestCase;

class MockablyTest extends PHPUnit_Framework_TestCase {

	/**
	 * @test microtime() can return float
	 */
	public function microtime_can_return_float()
	{
		$mockably = new Mockably;
		$this->assertInternalType('float', $mockably->microtime());
	}

}
