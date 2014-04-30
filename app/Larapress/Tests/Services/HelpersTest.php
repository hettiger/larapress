<?php namespace Larapress\Tests\Services;

use Config;
use DB;
use Helpers;
use Lang;
use Log;
use Mockery;
use Larapress\Tests\TestCase;
use Redirect;
use Request;
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

    /*
    |--------------------------------------------------------------------------
    | Helpers::logPerformance() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Helpers::logPerformance() method
    |
    */

    public function test_can_log_the_applications_performance()
    {
        Log::shouldReceive('info')->once();
        Request::shouldReceive('getRequestUri')->once();
        DB::shouldReceive('getQueryLog')->once();

        Helpers::logPerformance();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers::forceSSL() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Helpers::forceSSL() method
    |
    */

    public function test_can_force_ssl()
    {
        Request::shouldReceive('secure')->once()->andReturn(false);
        Request::shouldReceive('getRequestUri')->once()->andReturn('foo');
        Request::shouldReceive('root');
        Redirect::shouldReceive('secure')->with('foo')->once();

        Helpers::forceSSL();
    }

    public function test_can_remain_silent()
    {
        Request::shouldReceive('secure')->once()->andReturn(true);
        Redirect::shouldReceive('secure')->never();

        $this->assertNull(Helpers::forceSSL());
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers::force404() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Helpers::force404() method
    |
    */

    public function test_can_abort_the_app_and_return_the_backend_404_view()
    {
        $result = Helpers::force404();

        $this->assertInstanceOf('Illuminate\Http\Response', $result);
        $this->assertAttributeContains('larapress.errors.404', 'view', $result->getOriginalContent());
    }

}
