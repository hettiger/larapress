<?php namespace Larapress\Tests\Services;

use BadMethodCallException;
use Config;
use DB;
use Helpers;
use Lang;
use Log;
use Mockably;
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
    | Helpers::getCurrentTimeDifference() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Helpers::getCurrentTimeDifference() method
    |
    */

    public function test_can_return_the_current_time_difference_in_minutes_per_default()
    {
        Mockably::shouldReceive('microtime')->once()->andReturn(60.00);

        $e = 1;
        $a = Helpers::getCurrentTimeDifference(0.00);

        $this->assertEquals($e, $a);
    }

    public function test_can_return_the_current_time_difference_in_minutes_on_parameter()
    {
        Mockably::shouldReceive('microtime')->once()->andReturn(60.00);

        $e = 1;
        $a = Helpers::getCurrentTimeDifference(0.00, 'm');

        $this->assertEquals($e, $a);
    }

    public function test_can_round_minutes_correctly()
    {
        Mockably::shouldReceive('microtime')->once()->andReturn(100.00);

        $e = 1;
        $a = Helpers::getCurrentTimeDifference(0.00, 'm');

        $this->assertEquals($e, $a);
    }

    public function test_can_return_the_current_time_difference_in_seconds_on_parameter()
    {
        Mockably::shouldReceive('microtime')->once()->andReturn(60.00);

        $e = 30;
        $a = Helpers::getCurrentTimeDifference(30.00, 's');

        $this->assertEquals($e, $a);
    }

    public function test_can_return_the_current_time_difference_in_milliseconds_on_parameter()
    {
        Mockably::shouldReceive('microtime')->once()->andReturn(60.00);

        $e = 30000; // 1 second = 1000 milliseconds
        $a = Helpers::getCurrentTimeDifference(30.00, 'ms');

        $this->assertEquals($e, $a);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function test_can_throw_a_bad_method_call_exception()
    {
        Helpers::getCurrentTimeDifference(microtime(true), 'foo');
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
        $this->assertAttributeContains('larapress::errors.404', 'view', $result->getOriginalContent());
    }

}
