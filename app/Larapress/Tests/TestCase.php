<?php namespace Larapress\Tests;

ini_set('memory_limit', '64M');

use Mockery;
use PHPUnit_Framework_TestCase;

abstract class TestCase extends PHPUnit_Framework_TestCase {

	protected function tearDown()
	{
		parent::tearDown();

		Mockery::close();
	}

}
