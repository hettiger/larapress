<?php namespace Larapress\Tests\Services;

use Larapress\Services\Mockably;
use Larapress\Tests\TestCase;

class MockablyTest extends TestCase {

	/**
	 * @test microtime() can return float
	 */
	public function microtime_can_return_float()
	{
		$mockably = new Mockably;
		$this->assertInternalType('float', $mockably->microtime());
	}

}
