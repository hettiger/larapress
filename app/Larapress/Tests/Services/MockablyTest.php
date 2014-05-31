<?php namespace Larapress\Tests\Services;

use Larapress\Tests\TestCase;
use Mockably;

class MockablyTest extends TestCase
{

    /*
    |--------------------------------------------------------------------------
    | Mockably::microtime() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Mockably::microtime() method
    |
    */

    public function test_can_return_float()
    {
        $this->assertInternalType('float', Mockably::microtime());
    }

	/**
	 * @test str_random() can return a random string of given length
	 */
	public function str_random_can_return_a_random_string_of_given_length()
	{
		$string = Mockably::str_random(3);

		$this->assertInternalType('string', $string);
		$this->assertEquals(3, strlen($string));
	}

}
