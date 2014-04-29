<?php namespace Larapress\Tests\Services;

use Config;
use Helpers;
use Lang;
use Mockery;
use Larapress\Tests\TestCase;
use View;

class HelpersTest extends TestCase
{

    /*
    |--------------------------------------------------------------------------
    | Helpers::setPageTitle() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Helpers::setPageTitle() method
    |
    */

    public function test_can_set_page_title()
    {
        Config::shouldReceive('get')->with('larapress.names.cms')->once()->andReturn('foo')->shouldReceive('offsetGet');
        Lang::shouldReceive('get')->with('general.bar')->once()->andReturn('bar');
        View::shouldReceive('share')->with('title', 'foo | bar')->once();

        Helpers::setPageTitle('bar');
    }

}
