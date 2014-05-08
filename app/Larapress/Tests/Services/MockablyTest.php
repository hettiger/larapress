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

}
